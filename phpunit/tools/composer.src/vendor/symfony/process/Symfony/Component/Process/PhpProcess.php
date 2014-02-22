<?php










namespace Symfony\Component\Process;

use Symfony\Component\Process\Exception\RuntimeException;












class PhpProcess extends Process
{
private $executableFinder;












public function __construct($script, $cwd = null, array $env = array(), $timeout = 60, array $options = array())
{
parent::__construct(null, $cwd, $env, $script, $timeout, $options);

$this->executableFinder = new PhpExecutableFinder();
}






public function setPhpBinary($php)
{
$this->setCommandLine($php);
}




public function start($callback = null)
{
if (null === $this->getCommandLine()) {
if (false === $php = $this->executableFinder->find()) {
throw new RuntimeException('Unable to find the PHP executable.');
}
$this->setCommandLine($php);
}

parent::start($callback);
}
}
