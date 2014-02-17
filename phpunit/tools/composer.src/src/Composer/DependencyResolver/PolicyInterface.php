<?php











namespace Composer\DependencyResolver;

use Composer\Package\PackageInterface;




interface PolicyInterface
{
public function versionCompare(PackageInterface $a, PackageInterface $b, $operator);
public function findUpdatePackages(Pool $pool, array $installedMap, PackageInterface $package);
public function selectPreferedPackages(Pool $pool, array $installedMap, array $literals);
}
