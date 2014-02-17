<?php











namespace Composer;

use Composer\Autoload\AutoloadGenerator;
use Composer\DependencyResolver\DefaultPolicy;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\DependencyResolver\Operation\OperationInterface;
use Composer\DependencyResolver\Pool;
use Composer\DependencyResolver\Request;
use Composer\DependencyResolver\Rule;
use Composer\DependencyResolver\Solver;
use Composer\DependencyResolver\SolverProblemsException;
use Composer\Downloader\DownloadManager;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Installer\InstallationManager;
use Composer\Config;
use Composer\Installer\NoopInstaller;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Package\AliasPackage;
use Composer\Package\Link;
use Composer\Package\LinkConstraint\VersionConstraint;
use Composer\Package\Locker;
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Repository\CompositeRepository;
use Composer\Repository\InstalledArrayRepository;
use Composer\Repository\InstalledFilesystemRepository;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;
use Composer\Repository\RepositoryManager;
use Composer\Script\ScriptEvents;







class Installer
{



protected $io;




protected $config;




protected $package;




protected $downloadManager;




protected $repositoryManager;




protected $locker;




protected $installationManager;




protected $eventDispatcher;




protected $autoloadGenerator;

protected $preferSource = false;
protected $preferDist = false;
protected $optimizeAutoloader = false;
protected $devMode = false;
protected $dryRun = false;
protected $verbose = false;
protected $update = false;
protected $runScripts = true;
protected $updateWhitelist = null;
protected $whitelistDependencies = false;




protected $suggestedPackages;




protected $additionalInstalledRepository;














public function __construct(IOInterface $io, Config $config, RootPackageInterface $package, DownloadManager $downloadManager, RepositoryManager $repositoryManager, Locker $locker, InstallationManager $installationManager, EventDispatcher $eventDispatcher, AutoloadGenerator $autoloadGenerator)
{
$this->io = $io;
$this->config = $config;
$this->package = $package;
$this->downloadManager = $downloadManager;
$this->repositoryManager = $repositoryManager;
$this->locker = $locker;
$this->installationManager = $installationManager;
$this->eventDispatcher = $eventDispatcher;
$this->autoloadGenerator = $autoloadGenerator;
}






public function run()
{
if ($this->dryRun) {
$this->verbose = true;
$this->runScripts = false;
$this->installationManager->addInstaller(new NoopInstaller);
$this->mockLocalRepositories($this->repositoryManager);
}


 
 $devRepo = new InstalledFilesystemRepository(new JsonFile($this->config->get('vendor-dir').'/composer/installed_dev.json'));
if ($devRepo->getPackages()) {
$this->io->write('<warning>BC Notice: Removing old dev packages to migrate to the new require-dev handling.</warning>');
foreach ($devRepo->getPackages() as $package) {
if ($this->installationManager->isPackageInstalled($devRepo, $package)) {
$this->installationManager->uninstall($devRepo, new UninstallOperation($package));
}
}
unlink($this->config->get('vendor-dir').'/composer/installed_dev.json');
}
unset($devRepo, $package);


if ($this->runScripts) {

 $eventName = $this->update ? ScriptEvents::PRE_UPDATE_CMD : ScriptEvents::PRE_INSTALL_CMD;
$this->eventDispatcher->dispatchCommandEvent($eventName, $this->devMode);
}

$this->downloadManager->setPreferSource($this->preferSource);
$this->downloadManager->setPreferDist($this->preferDist);


 
 
 $installedRootPackage = clone $this->package;
$installedRootPackage->setRequires(array());
$installedRootPackage->setDevRequires(array());


 $localRepo = $this->repositoryManager->getLocalRepository();
$platformRepo = new PlatformRepository();
$repos = array(
$localRepo,
new InstalledArrayRepository(array($installedRootPackage)),
$platformRepo,
);
$installedRepo = new CompositeRepository($repos);
if ($this->additionalInstalledRepository) {
$installedRepo->addRepository($this->additionalInstalledRepository);
}

$aliases = $this->getRootAliases();
$this->aliasPlatformPackages($platformRepo, $aliases);

try {
$this->suggestedPackages = array();
$res = $this->doInstall($localRepo, $installedRepo, $platformRepo, $aliases, $this->devMode);
if ($res !== 0) {
return $res;
}
} catch (\Exception $e) {
$this->installationManager->notifyInstalls();

throw $e;
}
$this->installationManager->notifyInstalls();


 foreach ($this->suggestedPackages as $suggestion) {
$target = $suggestion['target'];
foreach ($installedRepo->getPackages() as $package) {
if (in_array($target, $package->getNames())) {
continue 2;
}
}

$this->io->write($suggestion['source'].' suggests installing '.$suggestion['target'].' ('.$suggestion['reason'].')');
}

if (!$this->dryRun) {

 if ($this->update || !$this->locker->isLocked()) {
$localRepo->reload();


 
 $devPackages = ($this->devMode || !$this->package->getDevRequires()) ? array() : null;


 if ($this->devMode && $this->package->getDevRequires()) {
$policy = $this->createPolicy();
$pool = $this->createPool(true);
$pool->addRepository($installedRepo, $aliases);


 $request = $this->createRequest($pool, $this->package, $platformRepo);
$request->updateAll();
foreach ($this->package->getRequires() as $link) {
$request->install($link->getTarget(), $link->getConstraint());
}

$solver = new Solver($policy, $pool, $installedRepo);
$ops = $solver->solve($request);
foreach ($ops as $op) {
if ($op->getJobType() === 'uninstall') {
$devPackages[] = $op->getPackage();
}
}
}

$platformReqs = $this->extractPlatformRequirements($this->package->getRequires());
$platformDevReqs = $this->devMode ? $this->extractPlatformRequirements($this->package->getDevRequires()) : array();

$updatedLock = $this->locker->setLockData(
array_diff($localRepo->getCanonicalPackages(), (array) $devPackages),
$devPackages,
$platformReqs,
$platformDevReqs,
$aliases,
$this->package->getMinimumStability(),
$this->package->getStabilityFlags()
);
if ($updatedLock) {
$this->io->write('<info>Writing lock file</info>');
}
}


 if ($this->optimizeAutoloader) {
$this->io->write('<info>Generating optimized autoload files</info>');
} else {
$this->io->write('<info>Generating autoload files</info>');
}

$this->autoloadGenerator->dump($this->config, $localRepo, $this->package, $this->installationManager, 'composer', $this->optimizeAutoloader);

if ($this->runScripts) {

 $eventName = $this->update ? ScriptEvents::POST_UPDATE_CMD : ScriptEvents::POST_INSTALL_CMD;
$this->eventDispatcher->dispatchCommandEvent($eventName, $this->devMode);
}
}

return 0;
}

protected function doInstall($localRepo, $installedRepo, $platformRepo, $aliases, $withDevReqs)
{

 $lockedRepository = null;
$repositories = null;


 $installFromLock = false;
if (!$this->update && $this->locker->isLocked()) {
$installFromLock = true;
try {
$lockedRepository = $this->locker->getLockedRepository($withDevReqs);
} catch (\RuntimeException $e) {

 if ($this->package->getDevRequires()) {
throw $e;
}

 $lockedRepository = $this->locker->getLockedRepository();
}
}

$this->whitelistUpdateDependencies(
$localRepo,
$withDevReqs,
$this->package->getRequires(),
$this->package->getDevRequires()
);

$this->io->write('<info>Loading composer repositories with package information</info>');


 $policy = $this->createPolicy();
$pool = $this->createPool($withDevReqs);
$pool->addRepository($installedRepo, $aliases);
if ($installFromLock) {
$pool->addRepository($lockedRepository, $aliases);
}

if (!$installFromLock) {
$repositories = $this->repositoryManager->getRepositories();
foreach ($repositories as $repository) {
$pool->addRepository($repository, $aliases);
}
}


 $request = $this->createRequest($pool, $this->package, $platformRepo);

if (!$installFromLock) {

 $removedUnstablePackages = array();
foreach ($localRepo->getPackages() as $package) {
if (
!$pool->isPackageAcceptable($package->getNames(), $package->getStability())
&& $this->installationManager->isPackageInstalled($localRepo, $package)
) {
$removedUnstablePackages[$package->getName()] = true;
$request->remove($package->getName(), new VersionConstraint('=', $package->getVersion()));
}
}
}

if ($this->update) {
$this->io->write('<info>Updating dependencies'.($withDevReqs?' (including require-dev)':'').'</info>');

$request->updateAll();

if ($withDevReqs) {
$links = array_merge($this->package->getRequires(), $this->package->getDevRequires());
} else {
$links = $this->package->getRequires();
}

foreach ($links as $link) {
$request->install($link->getTarget(), $link->getConstraint());
}


 
 if ($this->updateWhitelist) {
if ($this->locker->isLocked()) {
try {
$currentPackages = $this->locker->getLockedRepository($withDevReqs)->getPackages();
} catch (\RuntimeException $e) {

 $currentPackages = $this->locker->getLockedRepository()->getPackages();
}
} else {
$currentPackages = $installedRepo->getPackages();
}


 $candidates = array();
foreach ($links as $link) {
$candidates[$link->getTarget()] = true;
}
foreach ($localRepo->getPackages() as $package) {
$candidates[$package->getName()] = true;
}


 foreach ($candidates as $candidate => $dummy) {
foreach ($currentPackages as $curPackage) {
if ($curPackage->getName() === $candidate) {
if (!$this->isUpdateable($curPackage) && !isset($removedUnstablePackages[$curPackage->getName()])) {
$constraint = new VersionConstraint('=', $curPackage->getVersion());
$request->install($curPackage->getName(), $constraint);
}
break;
}
}
}
}
} elseif ($installFromLock) {
$this->io->write('<info>Installing dependencies'.($withDevReqs?' (including require-dev)':'').' from lock file</info>');

if (!$this->locker->isFresh()) {
$this->io->write('<warning>Warning: The lock file is not up to date with the latest changes in composer.json. You may be getting outdated dependencies. Run update to update them.</warning>');
}

foreach ($lockedRepository->getPackages() as $package) {
$version = $package->getVersion();
if (isset($aliases[$package->getName()][$version])) {
$version = $aliases[$package->getName()][$version]['alias_normalized'];
}
$constraint = new VersionConstraint('=', $version);
$constraint->setPrettyString($package->getPrettyVersion());
$request->install($package->getName(), $constraint);
}

foreach ($this->locker->getPlatformRequirements($withDevReqs) as $link) {
$request->install($link->getTarget(), $link->getConstraint());
}
} else {
$this->io->write('<info>Installing dependencies'.($withDevReqs?' (including require-dev)':'').'</info>');

if ($withDevReqs) {
$links = array_merge($this->package->getRequires(), $this->package->getDevRequires());
} else {
$links = $this->package->getRequires();
}

foreach ($links as $link) {
$request->install($link->getTarget(), $link->getConstraint());
}
}


 $this->processDevPackages($localRepo, $pool, $policy, $repositories, $lockedRepository, $installFromLock, 'force-links');


 $solver = new Solver($policy, $pool, $installedRepo);
try {
$operations = $solver->solve($request);
} catch (SolverProblemsException $e) {
$this->io->write('<error>Your requirements could not be resolved to an installable set of packages.</error>');
$this->io->write($e->getMessage());

return max(1, $e->getCode());
}


 $operations = $this->processDevPackages($localRepo, $pool, $policy, $repositories, $lockedRepository, $installFromLock, 'force-updates', $operations);


 if (!$operations) {
$this->io->write('Nothing to install or update');
}

$operations = $this->movePluginsToFront($operations);

foreach ($operations as $operation) {

 if ('install' === $operation->getJobType()) {
foreach ($operation->getPackage()->getSuggests() as $target => $reason) {
$this->suggestedPackages[] = array(
'source' => $operation->getPackage()->getPrettyName(),
'target' => $target,
'reason' => $reason,
);
}
}

$event = 'Composer\Script\ScriptEvents::PRE_PACKAGE_'.strtoupper($operation->getJobType());
if (defined($event) && $this->runScripts) {
$this->eventDispatcher->dispatchPackageEvent(constant($event), $this->devMode, $operation);
}


 if (!$installFromLock) {
$package = null;
if ('update' === $operation->getJobType()) {
$package = $operation->getTargetPackage();
} elseif ('install' === $operation->getJobType()) {
$package = $operation->getPackage();
}
if ($package && $package->isDev()) {
$references = $this->package->getReferences();
if (isset($references[$package->getName()])) {
$package->setSourceReference($references[$package->getName()]);
$package->setDistReference($references[$package->getName()]);
}
}
}


 if ($this->dryRun && false === strpos($operation->getJobType(), 'Alias')) {
$this->io->write('  - ' . $operation);
$this->io->write('');
} elseif ($this->io->isDebug() && false !== strpos($operation->getJobType(), 'Alias')) {
$this->io->write('  - ' . $operation);
$this->io->write('');
}

$this->installationManager->execute($localRepo, $operation);


 if ($this->verbose && $this->io->isVeryVerbose() && in_array($operation->getJobType(), array('install', 'update'))) {
$reason = $operation->getReason();
if ($reason instanceof Rule) {
switch ($reason->getReason()) {
case Rule::RULE_JOB_INSTALL:
$this->io->write('    REASON: Required by root: '.$reason->getRequiredPackage());
$this->io->write('');
break;
case Rule::RULE_PACKAGE_REQUIRES:
$this->io->write('    REASON: '.$reason->getPrettyString());
$this->io->write('');
break;
}
}
}

$event = 'Composer\Script\ScriptEvents::POST_PACKAGE_'.strtoupper($operation->getJobType());
if (defined($event) && $this->runScripts) {
$this->eventDispatcher->dispatchPackageEvent(constant($event), $this->devMode, $operation);
}

if (!$this->dryRun) {
$localRepo->write();
}
}

return 0;
}














private function movePluginsToFront(array $operations)
{
$installerOps = array();
foreach ($operations as $idx => $op) {
if ($op instanceof InstallOperation) {
$package = $op->getPackage();
} elseif ($op instanceof UpdateOperation) {
$package = $op->getTargetPackage();
} else {
continue;
}

if ($package->getRequires() === array() && ($package->getType() === 'composer-plugin' || $package->getType() === 'composer-installer')) {
$installerOps[] = $op;
unset($operations[$idx]);
}
}

return array_merge($installerOps, $operations);
}

private function createPool($withDevReqs)
{
$minimumStability = $this->package->getMinimumStability();
$stabilityFlags = $this->package->getStabilityFlags();

if (!$this->update && $this->locker->isLocked()) {
$minimumStability = $this->locker->getMinimumStability();
$stabilityFlags = $this->locker->getStabilityFlags();
}

$requires = $this->package->getRequires();
if ($withDevReqs) {
$requires = array_merge($requires, $this->package->getDevRequires());
}
$rootConstraints = array();
foreach ($requires as $req => $constraint) {
$rootConstraints[$req] = $constraint->getConstraint();
}

return new Pool($minimumStability, $stabilityFlags, $rootConstraints);
}

private function createPolicy()
{
return new DefaultPolicy($this->package->getPreferStable());
}

private function createRequest(Pool $pool, RootPackageInterface $rootPackage, PlatformRepository $platformRepo)
{
$request = new Request($pool);

$constraint = new VersionConstraint('=', $rootPackage->getVersion());
$constraint->setPrettyString($rootPackage->getPrettyVersion());
$request->install($rootPackage->getName(), $constraint);

$fixedPackages = $platformRepo->getPackages();
if ($this->additionalInstalledRepository) {
$additionalFixedPackages = $this->additionalInstalledRepository->getPackages();
$fixedPackages = array_merge($fixedPackages, $additionalFixedPackages);
}


 
 $provided = $rootPackage->getProvides();
foreach ($fixedPackages as $package) {
$constraint = new VersionConstraint('=', $package->getVersion());
$constraint->setPrettyString($package->getPrettyVersion());


 if ($package->getRepository() !== $platformRepo
|| !isset($provided[$package->getName()])
|| !$provided[$package->getName()]->getConstraint()->matches($constraint)
) {
$request->install($package->getName(), $constraint);
}
}

return $request;
}

private function processDevPackages($localRepo, $pool, $policy, $repositories, $lockedRepository, $installFromLock, $task, array $operations = null)
{
if ($task === 'force-updates' && null === $operations) {
throw new \InvalidArgumentException('Missing operations argument');
}
if ($task === 'force-links') {
$operations = array();
}

foreach ($localRepo->getCanonicalPackages() as $package) {

 if (!$package->isDev()) {
continue;
}


 foreach ($operations as $operation) {
if (('update' === $operation->getJobType() && $operation->getInitialPackage()->equals($package))
|| ('uninstall' === $operation->getJobType() && $operation->getPackage()->equals($package))
) {
continue 2;
}
}


 if ($installFromLock) {
foreach ($lockedRepository->findPackages($package->getName()) as $lockedPackage) {
if ($lockedPackage->isDev() && $lockedPackage->getVersion() === $package->getVersion()) {
if ($task === 'force-links') {
$package->setRequires($lockedPackage->getRequires());
$package->setConflicts($lockedPackage->getConflicts());
$package->setProvides($lockedPackage->getProvides());
$package->setReplaces($lockedPackage->getReplaces());
} elseif ($task === 'force-updates') {
if (($lockedPackage->getSourceReference() && $lockedPackage->getSourceReference() !== $package->getSourceReference())
|| ($lockedPackage->getDistReference() && $lockedPackage->getDistReference() !== $package->getDistReference())
) {
$operations[] = new UpdateOperation($package, $lockedPackage);
}
}

break;
}
}
} else {

 if ($this->update) {

 if ($this->updateWhitelist && !$this->isUpdateable($package)) {
continue;
}


 $matches = $pool->whatProvides($package->getName(), new VersionConstraint('=', $package->getVersion()));
foreach ($matches as $index => $match) {

 if (!in_array($match->getRepository(), $repositories, true)) {
unset($matches[$index]);
continue;
}


 if ($match->getName() !== $package->getName()) {
unset($matches[$index]);
continue;
}

$matches[$index] = $match->getId();
}


 if ($matches && $matches = $policy->selectPreferedPackages($pool, array(), $matches)) {
$newPackage = $pool->literalToPackage($matches[0]);

if ($task === 'force-links' && $newPackage) {
$package->setRequires($newPackage->getRequires());
$package->setConflicts($newPackage->getConflicts());
$package->setProvides($newPackage->getProvides());
$package->setReplaces($newPackage->getReplaces());
}

if ($task === 'force-updates' && $newPackage && (
(($newPackage->getSourceReference() && $newPackage->getSourceReference() !== $package->getSourceReference())
|| ($newPackage->getDistReference() && $newPackage->getDistReference() !== $package->getDistReference())
)
)) {
$operations[] = new UpdateOperation($package, $newPackage);
}
}
}

if ($task === 'force-updates') {

 $references = $this->package->getReferences();

if (isset($references[$package->getName()]) && $references[$package->getName()] !== $package->getSourceReference()) {

 $operations[] = new UpdateOperation($package, clone $package);
}
}
}
}

return $operations;
}

private function getRootAliases()
{
if (!$this->update && $this->locker->isLocked()) {
$aliases = $this->locker->getAliases();
} else {
$aliases = $this->package->getAliases();
}

$normalizedAliases = array();

foreach ($aliases as $alias) {
$normalizedAliases[$alias['package']][$alias['version']] = array(
'alias' => $alias['alias'],
'alias_normalized' => $alias['alias_normalized']
);
}

return $normalizedAliases;
}

private function aliasPlatformPackages(PlatformRepository $platformRepo, $aliases)
{
foreach ($aliases as $package => $versions) {
foreach ($versions as $version => $alias) {
$packages = $platformRepo->findPackages($package, $version);
foreach ($packages as $package) {
$aliasPackage = new AliasPackage($package, $alias['alias_normalized'], $alias['alias']);
$aliasPackage->setRootPackageAlias(true);
$platformRepo->addPackage($aliasPackage);
}
}
}
}

private function isUpdateable(PackageInterface $package)
{
if (!$this->updateWhitelist) {
throw new \LogicException('isUpdateable should only be called when a whitelist is present');
}

foreach ($this->updateWhitelist as $whiteListedPattern => $void) {
$cleanedWhiteListedPattern = str_replace('\\*', '.*', preg_quote($whiteListedPattern));

if (preg_match("{^".$cleanedWhiteListedPattern."$}i", $package->getName())) {
return true;
}
}

return false;
}

private function extractPlatformRequirements($links)
{
$platformReqs = array();
foreach ($links as $link) {
if (preg_match(PlatformRepository::PLATFORM_PACKAGE_REGEX, $link->getTarget())) {
$platformReqs[$link->getTarget()] = $link->getPrettyConstraint();
}
}

return $platformReqs;
}













private function whitelistUpdateDependencies($localRepo, $devMode, array $rootRequires, array $rootDevRequires)
{
if (!$this->updateWhitelist) {
return;
}

$requiredPackageNames = array();
foreach (array_merge($rootRequires, $rootDevRequires) as $require) {
$requiredPackageNames[] = $require->getTarget();
}

if ($devMode) {
$rootRequires = array_merge($rootRequires, $rootDevRequires);
}

$skipPackages = array();
foreach ($rootRequires as $require) {
$skipPackages[$require->getTarget()] = true;
}

$pool = new Pool;
$pool->addRepository($localRepo);

$seen = array();

foreach ($this->updateWhitelist as $packageName => $void) {
$packageQueue = new \SplQueue;

$depPackages = $pool->whatProvides($packageName);
if (count($depPackages) == 0 && !in_array($packageName, $requiredPackageNames) && !in_array($packageName, array('nothing', 'lock'))) {
$this->io->write('<warning>Package "' . $packageName . '" listed for update is not installed. Ignoring.</warning>');
}

foreach ($depPackages as $depPackage) {
$packageQueue->enqueue($depPackage);
}

while (!$packageQueue->isEmpty()) {
$package = $packageQueue->dequeue();
if (isset($seen[$package->getId()])) {
continue;
}

$seen[$package->getId()] = true;
$this->updateWhitelist[$package->getName()] = true;

if (!$this->whitelistDependencies) {
continue;
}

$requires = $package->getRequires();

foreach ($requires as $require) {
$requirePackages = $pool->whatProvides($require->getTarget());

foreach ($requirePackages as $requirePackage) {
if (isset($skipPackages[$requirePackage->getName()])) {
continue;
}
$packageQueue->enqueue($requirePackage);
}
}
}
}
}








private function mockLocalRepositories(RepositoryManager $rm)
{
$packages = array();
foreach ($rm->getLocalRepository()->getPackages() as $package) {
$packages[(string) $package] = clone $package;
}
foreach ($packages as $key => $package) {
if ($package instanceof AliasPackage) {
$alias = (string) $package->getAliasOf();
$packages[$key] = new AliasPackage($packages[$alias], $package->getVersion(), $package->getPrettyVersion());
}
}
$rm->setLocalRepository(
new InstalledArrayRepository($packages)
);
}








public static function create(IOInterface $io, Composer $composer)
{
return new static(
$io,
$composer->getConfig(),
$composer->getPackage(),
$composer->getDownloadManager(),
$composer->getRepositoryManager(),
$composer->getLocker(),
$composer->getInstallationManager(),
$composer->getEventDispatcher(),
$composer->getAutoloadGenerator()
);
}

public function setAdditionalInstalledRepository(RepositoryInterface $additionalInstalledRepository)
{
$this->additionalInstalledRepository = $additionalInstalledRepository;

return $this;
}







public function setDryRun($dryRun = true)
{
$this->dryRun = (boolean) $dryRun;

return $this;
}







public function setPreferSource($preferSource = true)
{
$this->preferSource = (boolean) $preferSource;

return $this;
}







public function setPreferDist($preferDist = true)
{
$this->preferDist = (boolean) $preferDist;

return $this;
}







public function setOptimizeAutoloader($optimizeAutoloader = false)
{
$this->optimizeAutoloader = (boolean) $optimizeAutoloader;

return $this;
}







public function setUpdate($update = true)
{
$this->update = (boolean) $update;

return $this;
}







public function setDevMode($devMode = true)
{
$this->devMode = (boolean) $devMode;

return $this;
}







public function setRunScripts($runScripts = true)
{
$this->runScripts = (boolean) $runScripts;

return $this;
}







public function setConfig(Config $config)
{
$this->config = $config;

return $this;
}







public function setVerbose($verbose = true)
{
$this->verbose = (boolean) $verbose;

return $this;
}








public function setUpdateWhitelist(array $packages)
{
$this->updateWhitelist = array_flip(array_map('strtolower', $packages));

return $this;
}







public function setWhitelistDependencies($updateDependencies = true)
{
$this->whitelistDependencies = (boolean) $updateDependencies;

return $this;
}










public function disablePlugins()
{
$this->installationManager->disablePlugins();

return $this;
}
}
