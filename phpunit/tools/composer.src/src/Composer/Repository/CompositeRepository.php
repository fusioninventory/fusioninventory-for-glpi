<?php











namespace Composer\Repository;

use Composer\Package\PackageInterface;






class CompositeRepository implements RepositoryInterface
{




private $repositories;





public function __construct(array $repositories)
{
$this->repositories = array();
foreach ($repositories as $repo) {
$this->addRepository($repo);
}
}






public function getRepositories()
{
return $this->repositories;
}




public function hasPackage(PackageInterface $package)
{
foreach ($this->repositories as $repository) {

if ($repository->hasPackage($package)) {
return true;
}
}

return false;
}




public function findPackage($name, $version)
{
foreach ($this->repositories as $repository) {

$package = $repository->findPackage($name, $version);
if (null !== $package) {
return $package;
}
}

return null;
}




public function findPackages($name, $version = null)
{
$packages = array();
foreach ($this->repositories as $repository) {

$packages[] = $repository->findPackages($name, $version);
}

return $packages ? call_user_func_array('array_merge', $packages) : array();
}




public function search($query, $mode = 0)
{
$matches = array();
foreach ($this->repositories as $repository) {

$matches[] = $repository->search($query, $mode);
}

return $matches ? call_user_func_array('array_merge', $matches) : array();
}




public function filterPackages($callback, $class = 'Composer\Package\Package')
{
foreach ($this->repositories as $repository) {
if (false === $repository->filterPackages($callback, $class)) {
return false;
}
}

return true;
}




public function getPackages()
{
$packages = array();
foreach ($this->repositories as $repository) {

$packages[] = $repository->getPackages();
}

return $packages ? call_user_func_array('array_merge', $packages) : array();
}




public function removePackage(PackageInterface $package)
{
foreach ($this->repositories as $repository) {

$repository->removePackage($package);
}
}




public function count()
{
$total = 0;
foreach ($this->repositories as $repository) {

$total += $repository->count();
}

return $total;
}





public function addRepository(RepositoryInterface $repository)
{
if ($repository instanceof self) {
foreach ($repository->getRepositories() as $repo) {
$this->addRepository($repo);
}
} else {
$this->repositories[] = $repository;
}
}
}
