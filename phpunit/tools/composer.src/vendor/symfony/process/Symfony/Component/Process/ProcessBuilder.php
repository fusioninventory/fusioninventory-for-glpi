<?php










namespace Symfony\Component\Process;

use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;






class ProcessBuilder
{
private $arguments;
private $cwd;
private $env = array();
private $stdin;
private $timeout = 60;
private $options = array();
private $inheritEnv = true;
private $prefix = array();

public function __construct(array $arguments = array())
{
$this->arguments = $arguments;
}

public static function create(array $arguments = array())
{
return new static($arguments);
}








public function add($argument)
{
$this->arguments[] = $argument;

return $this;
}










public function setPrefix($prefix)
{
$this->prefix = is_array($prefix) ? $prefix : array($prefix);

return $this;
}






public function setArguments(array $arguments)
{
$this->arguments = $arguments;

return $this;
}

public function setWorkingDirectory($cwd)
{
$this->cwd = $cwd;

return $this;
}

public function inheritEnvironmentVariables($inheritEnv = true)
{
$this->inheritEnv = $inheritEnv;

return $this;
}

public function setEnv($name, $value)
{
$this->env[$name] = $value;

return $this;
}

public function addEnvironmentVariables(array $variables)
{
$this->env = array_replace($this->env, $variables);

return $this;
}

public function setInput($stdin)
{
$this->stdin = $stdin;

return $this;
}












public function setTimeout($timeout)
{
if (null === $timeout) {
$this->timeout = null;

return $this;
}

$timeout = (float) $timeout;

if ($timeout < 0) {
throw new InvalidArgumentException('The timeout value must be a valid positive integer or float number.');
}

$this->timeout = $timeout;

return $this;
}

public function setOption($name, $value)
{
$this->options[$name] = $value;

return $this;
}

public function getProcess()
{
if (0 === count($this->prefix) && 0 === count($this->arguments)) {
throw new LogicException('You must add() command arguments before calling getProcess().');
}

$options = $this->options;

$arguments = array_merge($this->prefix, $this->arguments);
$script = implode(' ', array_map(array(__NAMESPACE__.'\\ProcessUtils', 'escapeArgument'), $arguments));

if ($this->inheritEnv) {

 $env = array_replace($_ENV, $_SERVER, $this->env);
} else {
$env = $this->env;
}

return new Process($script, $this->cwd, $env, $this->stdin, $this->timeout, $options);
}
}
