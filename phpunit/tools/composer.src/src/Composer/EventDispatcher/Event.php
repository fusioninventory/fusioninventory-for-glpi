<?php











namespace Composer\EventDispatcher;






class Event
{



protected $name;




private $propagationStopped = false;






public function __construct($name)
{
$this->name = $name;
}






public function getName()
{
return $this->name;
}






public function isPropagationStopped()
{
return $this->propagationStopped;
}




public function stopPropagation()
{
$this->propagationStopped = true;
}
}
