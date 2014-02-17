<?php











namespace Composer\Package\LinkConstraint;








class VersionConstraint extends SpecificConstraint
{
private $operator;
private $version;







public function __construct($operator, $version)
{
if ('=' === $operator) {
$operator = '==';
}

if ('<>' === $operator) {
$operator = '!=';
}

$this->operator = $operator;
$this->version = $version;
}

public function versionCompare($a, $b, $operator, $compareBranches = false)
{
$aIsBranch = 'dev-' === substr($a, 0, 4);
$bIsBranch = 'dev-' === substr($b, 0, 4);
if ($aIsBranch && $bIsBranch) {
return $operator == '==' && $a === $b;
}


 if (!$compareBranches && ($aIsBranch || $bIsBranch)) {
return false;
}

return version_compare($a, $b, $operator);
}






public function matchSpecific(VersionConstraint $provider, $compareBranches = false)
{
static $cache = array();
if (isset($cache[$this->operator][$this->version][$provider->operator][$provider->version][$compareBranches])) {
return $cache[$this->operator][$this->version][$provider->operator][$provider->version][$compareBranches];
}

return $cache[$this->operator][$this->version][$provider->operator][$provider->version][$compareBranches] =
$this->doMatchSpecific($provider, $compareBranches);
}






private function doMatchSpecific(VersionConstraint $provider, $compareBranches = false)
{
$noEqualOp = str_replace('=', '', $this->operator);
$providerNoEqualOp = str_replace('=', '', $provider->operator);

$isEqualOp = '==' === $this->operator;
$isNonEqualOp = '!=' === $this->operator;
$isProviderEqualOp = '==' === $provider->operator;
$isProviderNonEqualOp = '!=' === $provider->operator;


 
 if ($isNonEqualOp || $isProviderNonEqualOp) {
return !$isEqualOp && !$isProviderEqualOp
|| $this->versionCompare($provider->version, $this->version, '!=', $compareBranches);
}


 
 if ($this->operator != '==' && $noEqualOp == $providerNoEqualOp) {
return true;
}

if ($this->versionCompare($provider->version, $this->version, $this->operator, $compareBranches)) {

 
 if ($provider->version == $this->version && $provider->operator == $providerNoEqualOp && $this->operator != $noEqualOp) {
return false;
}

return true;
}

return false;
}

public function __toString()
{
return $this->operator.' '.$this->version;
}
}
