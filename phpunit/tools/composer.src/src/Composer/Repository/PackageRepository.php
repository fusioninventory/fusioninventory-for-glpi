<?php











namespace Composer\Repository;

use Composer\Package\Loader\ArrayLoader;
use Composer\Package\Loader\ValidatingArrayLoader;






class PackageRepository extends ArrayRepository
{
private $config;






public function __construct(array $config)
{
$this->config = $config['package'];


 if (!is_numeric(key($this->config))) {
$this->config = array($this->config);
}
}




protected function initialize()
{
parent::initialize();

$loader = new ValidatingArrayLoader(new ArrayLoader, false);
foreach ($this->config as $package) {
try {
$package = $loader->load($package);
} catch (\Exception $e) {
throw new InvalidRepositoryException('A repository of type "package" contains an invalid package definition: '.$e->getMessage()."\n\nInvalid package definition:\n".json_encode($package));
}

$this->addPackage($package);
}
}
}
