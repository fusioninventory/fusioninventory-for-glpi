<?php











namespace Composer\Script;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\DependencyResolver\Operation\OperationInterface;






class PackageEvent extends Event
{



private $operation;










public function __construct($name, Composer $composer, IOInterface $io, $devMode, OperationInterface $operation)
{
parent::__construct($name, $composer, $io, $devMode);
$this->operation = $operation;
}






public function getOperation()
{
return $this->operation;
}
}
