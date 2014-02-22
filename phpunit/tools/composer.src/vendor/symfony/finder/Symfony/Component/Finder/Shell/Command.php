<?php










namespace Symfony\Component\Finder\Shell;




class Command
{



private $parent;




private $bits = array();




private $labels = array();




private $errorHandler;






public function __construct(Command $parent = null)
{
$this->parent = $parent;
}






public function __toString()
{
return $this->join();
}








public static function create(Command $parent = null)
{
return new self($parent);
}








public static function escape($input)
{
return escapeshellcmd($input);
}








public static function quote($input)
{
return escapeshellarg($input);
}








public function add($bit)
{
$this->bits[] = $bit;

return $this;
}








public function top($bit)
{
array_unshift($this->bits, $bit);

foreach ($this->labels as $label => $index) {
$this->labels[$label] += 1;
}

return $this;
}








public function arg($arg)
{
$this->bits[] = self::quote($arg);

return $this;
}








public function cmd($esc)
{
$this->bits[] = self::escape($esc);

return $this;
}










public function ins($label)
{
if (isset($this->labels[$label])) {
throw new \RuntimeException(sprintf('Label "%s" already exists.', $label));
}

$this->bits[] = self::create($this);
$this->labels[$label] = count($this->bits)-1;

return $this->bits[$this->labels[$label]];
}










public function get($label)
{
if (!isset($this->labels[$label])) {
throw new \RuntimeException(sprintf('Label "%s" does not exist.', $label));
}

return $this->bits[$this->labels[$label]];
}








public function end()
{
if (null === $this->parent) {
throw new \RuntimeException('Calling end on root command doesn\'t make sense.');
}

return $this->parent;
}






public function length()
{
return count($this->bits);
}






public function setErrorHandler(\Closure $errorHandler)
{
$this->errorHandler = $errorHandler;

return $this;
}




public function getErrorHandler()
{
return $this->errorHandler;
}








public function execute()
{
if (null === $this->errorHandler) {
exec($this->join(), $output);
} else {
$process = proc_open($this->join(), array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
$output = preg_split('~(\r\n|\r|\n)~', stream_get_contents($pipes[1]), -1, PREG_SPLIT_NO_EMPTY);

if ($error = stream_get_contents($pipes[2])) {
call_user_func($this->errorHandler, $error);
}

proc_close($process);
}

return $output ?: array();
}






public function join()
{
return implode(' ', array_filter(
array_map(function ($bit) {
return $bit instanceof Command ? $bit->join() : ($bit ?: null);
}, $this->bits),
function ($bit) { return null !== $bit; }
));
}









public function addAtIndex($bit, $index)
{
array_splice($this->bits, $index, 0, $bit);

return $this;
}
}
