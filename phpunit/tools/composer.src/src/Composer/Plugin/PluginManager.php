<?php











namespace Composer\Plugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\Package;
use Composer\Package\Version\VersionParser;
use Composer\Repository\RepositoryInterface;
use Composer\Package\AliasPackage;
use Composer\Package\PackageInterface;
use Composer\Package\Link;
use Composer\Package\LinkConstraint\VersionConstraint;
use Composer\DependencyResolver\Pool;






class PluginManager
{
protected $composer;
protected $io;
protected $globalRepository;
protected $versionParser;

protected $plugins = array();

private static $classCounter = 0;








public function __construct(Composer $composer, IOInterface $io, RepositoryInterface $globalRepository = null)
{
$this->composer = $composer;
$this->io = $io;
$this->globalRepository = $globalRepository;
$this->versionParser = new VersionParser();
}




public function loadInstalledPlugins()
{
$repo = $this->composer->getRepositoryManager()->getLocalRepository();

if ($repo) {
$this->loadRepository($repo);
}
if ($this->globalRepository) {
$this->loadRepository($this->globalRepository);
}
}






public function addPlugin(PluginInterface $plugin)
{
$this->plugins[] = $plugin;
$plugin->activate($this->composer, $this->io);

if ($plugin instanceof EventSubscriberInterface) {
$this->composer->getEventDispatcher()->addSubscriber($plugin);
}
}






public function getPlugins()
{
return $this->plugins;
}










public function loadRepository(RepositoryInterface $repo)
{
foreach ($repo->getPackages() as $package) {
if ($package instanceof AliasPackage) {
continue;
}
if ('composer-plugin' === $package->getType()) {
$requiresComposer = null;
foreach ($package->getRequires() as $link) {
if ($link->getTarget() == 'composer-plugin-api') {
$requiresComposer = $link->getConstraint();
}
}

if (!$requiresComposer) {
throw new \RuntimeException("Plugin ".$package->getName()." is missing a require statement for a version of the composer-plugin-api package.");
}

if (!$requiresComposer->matches(new VersionConstraint('==', $this->versionParser->normalize(PluginInterface::PLUGIN_API_VERSION)))) {
$this->io->write("<warning>The plugin ".$package->getName()." requires a version of composer-plugin-api that does not match your composer installation. You may need to run composer update with the '--no-plugins' option.</warning>");
}

$this->registerPackage($package);
}

 if ('composer-installer' === $package->getType()) {
$this->registerPackage($package);
}
}
}










protected function collectDependencies(Pool $pool, array $collected, PackageInterface $package)
{
$requires = array_merge(
$package->getRequires(),
$package->getDevRequires()
);

foreach ($requires as $requireLink) {
$requiredPackage = $this->lookupInstalledPackage($pool, $requireLink);
if ($requiredPackage && !isset($collected[$requiredPackage->getName()])) {
$collected[$requiredPackage->getName()] = $requiredPackage;
$collected = $this->collectDependencies($pool, $collected, $requiredPackage);
}
}

return $collected;
}











protected function lookupInstalledPackage(Pool $pool, Link $link)
{
$packages = $pool->whatProvides($link->getTarget(), $link->getConstraint());

return (!empty($packages)) ? $packages[0] : null;
}









public function registerPackage(PackageInterface $package)
{
$oldInstallerPlugin = ($package->getType() === 'composer-installer');

$extra = $package->getExtra();
if (empty($extra['class'])) {
throw new \UnexpectedValueException('Error while installing '.$package->getPrettyName().', composer-plugin packages should have a class defined in their extra key to be usable.');
}
$classes = is_array($extra['class']) ? $extra['class'] : array($extra['class']);

$pool = new Pool('dev');
$localRepo = $this->composer->getRepositoryManager()->getLocalRepository();
$pool->addRepository($localRepo);
if ($this->globalRepository) {
$pool->addRepository($this->globalRepository);
}

$autoloadPackages = array($package->getName() => $package);
$autoloadPackages = $this->collectDependencies($pool, $autoloadPackages, $package);

$generator = $this->composer->getAutoloadGenerator();
$autoloads = array();
foreach ($autoloadPackages as $autoloadPackage) {
$downloadPath = $this->getInstallPath($autoloadPackage, ($this->globalRepository && $this->globalRepository->hasPackage($autoloadPackage)));
$autoloads[] = array($autoloadPackage, $downloadPath);
}

$map = $generator->parseAutoloads($autoloads, new Package('dummy', '1.0.0.0', '1.0.0'));
$classLoader = $generator->createLoader($map);
$classLoader->register();

foreach ($classes as $class) {
if (class_exists($class, false)) {
$code = file_get_contents($classLoader->findFile($class));
$code = preg_replace('{^(\s*)class\s+(\S+)}mi', '$1class $2_composer_tmp'.self::$classCounter, $code);
eval('?>'.$code);
$class .= '_composer_tmp'.self::$classCounter;
self::$classCounter++;
}

if ($oldInstallerPlugin) {
$installer = new $class($this->io, $this->composer);
$this->composer->getInstallationManager()->addInstaller($installer);
} else {
$plugin = new $class();
$this->addPlugin($plugin);
}
}
}









public function getInstallPath(PackageInterface $package, $global = false)
{
if (!$global) {
return $this->composer->getInstallationManager()->getInstallPath($package);
}

$targetDir = $package->getTargetDir();
$vendorDir = $this->composer->getConfig()->get('home').'/vendor';

return ($vendorDir ? $vendorDir.'/' : '').$package->getPrettyName().($targetDir ? '/'.$targetDir : '');
}
}
