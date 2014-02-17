<?php











namespace Composer\Repository;

use Composer\IO\IOInterface;
use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;








class RepositoryManager
{
private $localRepository;
private $repositories = array();
private $repositoryClasses = array();
private $io;
private $config;
private $eventDispatcher;

public function __construct(IOInterface $io, Config $config, EventDispatcher $eventDispatcher = null)
{
$this->io = $io;
$this->config = $config;
$this->eventDispatcher = $eventDispatcher;
}









public function findPackage($name, $version)
{
foreach ($this->repositories as $repository) {
if ($package = $repository->findPackage($name, $version)) {
return $package;
}
}
}









public function findPackages($name, $version)
{
$packages = array();

foreach ($this->repositories as $repository) {
$packages = array_merge($packages, $repository->findPackages($name, $version));
}

return $packages;
}






public function addRepository(RepositoryInterface $repository)
{
$this->repositories[] = $repository;
}









public function createRepository($type, $config)
{
if (!isset($this->repositoryClasses[$type])) {
throw new \InvalidArgumentException('Repository type is not registered: '.$type);
}

$class = $this->repositoryClasses[$type];

return new $class($config, $this->io, $this->config, $this->eventDispatcher);
}







public function setRepositoryClass($type, $class)
{
$this->repositoryClasses[$type] = $class;
}






public function getRepositories()
{
return $this->repositories;
}






public function setLocalRepository(WritableRepositoryInterface $repository)
{
$this->localRepository = $repository;
}






public function getLocalRepository()
{
return $this->localRepository;
}







public function getLocalRepositories()
{
trigger_error('This method is deprecated, use getLocalRepository instead since the getLocalDevRepository is now gone', E_USER_DEPRECATED);

return array($this->localRepository);
}
}
