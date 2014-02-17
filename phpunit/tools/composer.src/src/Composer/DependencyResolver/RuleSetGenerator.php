<?php











namespace Composer\DependencyResolver;

use Composer\Package\PackageInterface;
use Composer\Package\AliasPackage;




class RuleSetGenerator
{
protected $policy;
protected $pool;
protected $rules;
protected $jobs;
protected $installedMap;

public function __construct(PolicyInterface $policy, Pool $pool)
{
$this->policy = $policy;
$this->pool = $pool;
}















protected function createRequireRule(PackageInterface $package, array $providers, $reason, $reasonData = null)
{
$literals = array(-$package->getId());

foreach ($providers as $provider) {

 if ($provider === $package) {
return null;
}
$literals[] = $provider->getId();
}

return new Rule($this->pool, $literals, $reason, $reasonData);
}













protected function createInstallOneOfRule(array $packages, $reason, $job)
{
$literals = array();
foreach ($packages as $package) {
$literals[] = $package->getId();
}

return new Rule($this->pool, $literals, $reason, $job['packageName'], $job);
}












protected function createRemoveRule(PackageInterface $package, $reason, $job)
{
return new Rule($this->pool, array(-$package->getId()), $reason, $job['packageName'], $job);
}















protected function createConflictRule(PackageInterface $issuer, PackageInterface $provider, $reason, $reasonData = null)
{

 if ($issuer === $provider) {
return null;
}

return new Rule($this->pool, array(-$issuer->getId(), -$provider->getId()), $reason, $reasonData);
}










private function addRule($type, Rule $newRule = null)
{
if (!$newRule || $this->rules->containsEqual($newRule)) {
return;
}

$this->rules->add($newRule, $type);
}

protected function addRulesForPackage(PackageInterface $package)
{
$workQueue = new \SplQueue;
$workQueue->enqueue($package);

while (!$workQueue->isEmpty()) {
$package = $workQueue->dequeue();
if (isset($this->addedMap[$package->getId()])) {
continue;
}

$this->addedMap[$package->getId()] = true;

foreach ($package->getRequires() as $link) {
$possibleRequires = $this->pool->whatProvides($link->getTarget(), $link->getConstraint());

$this->addRule(RuleSet::TYPE_PACKAGE, $rule = $this->createRequireRule($package, $possibleRequires, Rule::RULE_PACKAGE_REQUIRES, $link));

foreach ($possibleRequires as $require) {
$workQueue->enqueue($require);
}
}

foreach ($package->getConflicts() as $link) {
$possibleConflicts = $this->pool->whatProvides($link->getTarget(), $link->getConstraint());

foreach ($possibleConflicts as $conflict) {
$this->addRule(RuleSet::TYPE_PACKAGE, $this->createConflictRule($package, $conflict, Rule::RULE_PACKAGE_CONFLICT, $link));
}
}


 $isInstalled = (isset($this->installedMap[$package->getId()]));

foreach ($package->getReplaces() as $link) {
$obsoleteProviders = $this->pool->whatProvides($link->getTarget(), $link->getConstraint());

foreach ($obsoleteProviders as $provider) {
if ($provider === $package) {
continue;
}

if (!$this->obsoleteImpossibleForAlias($package, $provider)) {
$reason = ($isInstalled) ? Rule::RULE_INSTALLED_PACKAGE_OBSOLETES : Rule::RULE_PACKAGE_OBSOLETES;
$this->addRule(RuleSet::TYPE_PACKAGE, $this->createConflictRule($package, $provider, $reason, $link));
}
}
}

$obsoleteProviders = $this->pool->whatProvides($package->getName(), null);

foreach ($obsoleteProviders as $provider) {
if ($provider === $package) {
continue;
}

if (($package instanceof AliasPackage) && $package->getAliasOf() === $provider) {
$this->addRule(RuleSet::TYPE_PACKAGE, $rule = $this->createRequireRule($package, array($provider), Rule::RULE_PACKAGE_ALIAS, $package));
} elseif (!$this->obsoleteImpossibleForAlias($package, $provider)) {
$reason = ($package->getName() == $provider->getName()) ? Rule::RULE_PACKAGE_SAME_NAME : Rule::RULE_PACKAGE_IMPLICIT_OBSOLETES;
$this->addRule(RuleSet::TYPE_PACKAGE, $rule = $this->createConflictRule($package, $provider, $reason, $package));
}
}
}
}

protected function obsoleteImpossibleForAlias($package, $provider)
{
$packageIsAlias = $package instanceof AliasPackage;
$providerIsAlias = $provider instanceof AliasPackage;

$impossible = (
($packageIsAlias && $package->getAliasOf() === $provider) ||
($providerIsAlias && $provider->getAliasOf() === $package) ||
($packageIsAlias && $providerIsAlias && $provider->getAliasOf() === $package->getAliasOf())
);

return $impossible;
}







private function addRulesForUpdatePackages(PackageInterface $package)
{
$updates = $this->policy->findUpdatePackages($this->pool, $this->installedMap, $package);

foreach ($updates as $update) {
$this->addRulesForPackage($update);
}
}

protected function addRulesForJobs()
{
foreach ($this->jobs as $job) {
switch ($job['cmd']) {
case 'install':
if ($job['packages']) {
foreach ($job['packages'] as $package) {
if (!isset($this->installedMap[$package->getId()])) {
$this->addRulesForPackage($package);
}
}

$rule = $this->createInstallOneOfRule($job['packages'], Rule::RULE_JOB_INSTALL, $job);
$this->addRule(RuleSet::TYPE_JOB, $rule);
}
break;
case 'remove':

 
 foreach ($job['packages'] as $package) {
$rule = $this->createRemoveRule($package, Rule::RULE_JOB_REMOVE, $job);
$this->addRule(RuleSet::TYPE_JOB, $rule);
}
break;
}
}
}

public function getRulesFor($jobs, $installedMap)
{
$this->jobs = $jobs;
$this->rules = new RuleSet;
$this->installedMap = $installedMap;

foreach ($this->installedMap as $package) {
$this->addRulesForPackage($package);
$this->addRulesForUpdatePackages($package);
}

$this->addRulesForJobs();

return $this->rules;
}
}
