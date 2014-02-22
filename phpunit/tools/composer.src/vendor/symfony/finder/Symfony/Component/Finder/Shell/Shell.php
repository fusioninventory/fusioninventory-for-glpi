<?php










namespace Symfony\Component\Finder\Shell;




class Shell
{
const TYPE_UNIX = 1;
const TYPE_DARWIN = 2;
const TYPE_CYGWIN = 3;
const TYPE_WINDOWS = 4;
const TYPE_BSD = 5;




private $type;






public function getType()
{
if (null === $this->type) {
$this->type = $this->guessType();
}

return $this->type;
}








public function testCommand($command)
{
if (self::TYPE_WINDOWS === $this->type) {

 return false;
}

if (!function_exists('exec')) {
return false;
}


 exec('command -v '.$command, $output, $code);

return 0 === $code && count($output) > 0;
}






private function guessType()
{
$os = strtolower(PHP_OS);

if (false !== strpos($os, 'cygwin')) {
return self::TYPE_CYGWIN;
}

if (false !== strpos($os, 'darwin')) {
return self::TYPE_DARWIN;
}

if (false !== strpos($os, 'bsd')) {
return self::TYPE_BSD;
}

if (0 === strpos($os, 'win')) {
return self::TYPE_WINDOWS;
}

return self::TYPE_UNIX;
}
}
