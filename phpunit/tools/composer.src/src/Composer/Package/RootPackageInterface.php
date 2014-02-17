<?php











namespace Composer\Package;






interface RootPackageInterface extends CompletePackageInterface
{





public function getAliases();






public function getMinimumStability();








public function getStabilityFlags();








public function getReferences();






public function getPreferStable();






public function setRequires(array $requires);






public function setDevRequires(array $devRequires);
}
