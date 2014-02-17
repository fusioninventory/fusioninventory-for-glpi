<?php











namespace Composer\Package;




class RootAliasPackage extends AliasPackage implements RootPackageInterface
{
public function __construct(RootPackageInterface $aliasOf, $version, $prettyVersion)
{
parent::__construct($aliasOf, $version, $prettyVersion);
}




public function getAliases()
{
return $this->aliasOf->getAliases();
}




public function getMinimumStability()
{
return $this->aliasOf->getMinimumStability();
}




public function getStabilityFlags()
{
return $this->aliasOf->getStabilityFlags();
}




public function getReferences()
{
return $this->aliasOf->getReferences();
}




public function getPreferStable()
{
return $this->aliasOf->getPreferStable();
}




public function setRequires(array $require)
{
return $this->aliasOf->setRequires($require);
}




public function setDevRequires(array $devRequire)
{
return $this->aliasOf->setDevRequires($devRequire);
}

public function __clone()
{
parent::__clone();
$this->aliasOf = clone $this->aliasOf;
}
}
