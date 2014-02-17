<?php











namespace Composer\DependencyResolver;






class Problem
{




protected $reasonSeen;





protected $reasons = array();

protected $section = 0;

protected $pool;

public function __construct(Pool $pool)
{
$this->pool = $pool;
}






public function addRule(Rule $rule)
{
$this->addReason($rule->getId(), array(
'rule' => $rule,
'job' => $rule->getJob(),
));
}






public function getReasons()
{
return $this->reasons;
}







public function getPrettyString(array $installedMap = array())
{
$reasons = call_user_func_array('array_merge', array_reverse($this->reasons));

if (count($reasons) === 1) {
reset($reasons);
$reason = current($reasons);

$rule = $reason['rule'];
$job = $reason['job'];

if ($job && $job['cmd'] === 'install' && empty($job['packages'])) {

 if (0 === stripos($job['packageName'], 'ext-')) {
$ext = substr($job['packageName'], 4);
$error = extension_loaded($ext) ? 'has the wrong version ('.(phpversion($ext) ?: '0').') installed' : 'is missing from your system';

return "\n    - The requested PHP extension ".$job['packageName'].$this->constraintToText($job['constraint']).' '.$error.'.';
}


 if (0 === stripos($job['packageName'], 'lib-')) {
if (strtolower($job['packageName']) === 'lib-icu') {
$error = extension_loaded('intl') ? 'has the wrong version installed, try upgrading the intl extension.' : 'is missing from your system, make sure the intl extension is loaded.';

return "\n    - The requested linked library ".$job['packageName'].$this->constraintToText($job['constraint']).' '.$error;
}

return "\n    - The requested linked library ".$job['packageName'].$this->constraintToText($job['constraint']).' has the wrong version installed or is missing from your system, make sure to load the extension providing it.';
}

if (!preg_match('{^[A-Za-z0-9_./-]+$}', $job['packageName'])) {
$illegalChars = preg_replace('{[A-Za-z0-9_./-]+}', '', $job['packageName']);

return "\n    - The requested package ".$job['packageName'].' could not be found, it looks like its name is invalid, "'.$illegalChars.'" is not allowed in package names.';
}

if (!$this->pool->whatProvides($job['packageName'])) {
return "\n    - The requested package ".$job['packageName'].' could not be found in any version, there may be a typo in the package name.';
}

return "\n    - The requested package ".$job['packageName'].$this->constraintToText($job['constraint']).' could not be found.';
}
}

$messages = array();

foreach ($reasons as $reason) {
$rule = $reason['rule'];
$job = $reason['job'];

if ($job) {
$messages[] = $this->jobToText($job);
} elseif ($rule) {
if ($rule instanceof Rule) {
$messages[] = $rule->getPrettyString($installedMap);
}
}
}

return "\n    - ".implode("\n    - ", $messages);
}







protected function addReason($id, $reason)
{
if (!isset($this->reasonSeen[$id])) {
$this->reasonSeen[$id] = true;
$this->reasons[$this->section][] = $reason;
}
}

public function nextSection()
{
$this->section++;
}







protected function jobToText($job)
{
switch ($job['cmd']) {
case 'install':
if (!$job['packages']) {
return 'No package found to satisfy install request for '.$job['packageName'].$this->constraintToText($job['constraint']);
}

return 'Installation request for '.$job['packageName'].$this->constraintToText($job['constraint']).' -> satisfiable by '.$this->getPackageList($job['packages']).'.';
case 'update':
return 'Update request for '.$job['packageName'].$this->constraintToText($job['constraint']).'.';
case 'remove':
return 'Removal request for '.$job['packageName'].$this->constraintToText($job['constraint']).'';
}

return 'Job(cmd='.$job['cmd'].', target='.$job['packageName'].', packages=['.$this->getPackageList($job['packages']).'])';
}

protected function getPackageList($packages)
{
$prepared = array();
foreach ($packages as $package) {
$prepared[$package->getName()]['name'] = $package->getPrettyName();
$prepared[$package->getName()]['versions'][$package->getVersion()] = $package->getPrettyVersion();
}
foreach ($prepared as $name => $package) {
$prepared[$name] = $package['name'].'['.implode(', ', $package['versions']).']';
}

return implode(', ', $prepared);
}







protected function constraintToText($constraint)
{
return ($constraint) ? ' '.$constraint->getPrettyString() : '';
}
}
