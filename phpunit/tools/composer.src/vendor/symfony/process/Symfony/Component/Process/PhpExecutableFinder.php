<?php










namespace Symfony\Component\Process;







class PhpExecutableFinder
{
private $executableFinder;

public function __construct()
{
$this->executableFinder = new ExecutableFinder();
}






public function find()
{

 if (defined('HHVM_VERSION') && false !== $hhvm = getenv('PHP_BINARY')) {
return $hhvm;
}


 if (defined('PHP_BINARY') && PHP_BINARY && in_array(PHP_SAPI, array('cli', 'cli-server')) && is_file(PHP_BINARY)) {
return PHP_BINARY;
}

if ($php = getenv('PHP_PATH')) {
if (!is_executable($php)) {
return false;
}

return $php;
}

if ($php = getenv('PHP_PEAR_PHP_BIN')) {
if (is_executable($php)) {
return $php;
}
}

$dirs = array(PHP_BINDIR);
if (defined('PHP_WINDOWS_VERSION_BUILD')) {
$dirs[] = 'C:\xampp\php\\';
}

return $this->executableFinder->find('php', false, $dirs);
}
}
