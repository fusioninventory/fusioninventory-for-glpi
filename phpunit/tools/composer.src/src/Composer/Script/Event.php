<?php











namespace Composer\Script;

use Composer\Composer;
use Composer\IO\IOInterface;







class Event extends \Composer\EventDispatcher\Event
{



private $composer;




private $io;




private $devMode;









public function __construct($name, Composer $composer, IOInterface $io, $devMode = false)
{
parent::__construct($name);
$this->composer = $composer;
$this->io = $io;
$this->devMode = $devMode;
}






public function getComposer()
{
return $this->composer;
}






public function getIO()
{
return $this->io;
}






public function isDevMode()
{
return $this->devMode;
}
}
