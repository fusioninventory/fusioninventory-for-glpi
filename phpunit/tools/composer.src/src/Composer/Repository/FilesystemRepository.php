<?php











namespace Composer\Repository;

use Composer\Json\JsonFile;
use Composer\Package\Loader\ArrayLoader;
use Composer\Package\Dumper\ArrayDumper;







class FilesystemRepository extends WritableArrayRepository
{
private $file;






public function __construct(JsonFile $repositoryFile)
{
$this->file = $repositoryFile;
}




protected function initialize()
{
parent::initialize();

if (!$this->file->exists()) {
return;
}

try {
$packages = $this->file->read();

if (!is_array($packages)) {
throw new \UnexpectedValueException('Could not parse package list from the repository');
}
} catch (\Exception $e) {
throw new InvalidRepositoryException('Invalid repository data in '.$this->file->getPath().', packages could not be loaded: ['.get_class($e).'] '.$e->getMessage());
}

$loader = new ArrayLoader();
foreach ($packages as $packageData) {
$package = $loader->load($packageData);
$this->addPackage($package);
}
}

public function reload()
{
$this->packages = null;
$this->initialize();
}




public function write()
{
$data = array();
$dumper = new ArrayDumper();

foreach ($this->getCanonicalPackages() as $package) {
$data[] = $dumper->dump($package);
}

$this->file->write($data);
}
}
