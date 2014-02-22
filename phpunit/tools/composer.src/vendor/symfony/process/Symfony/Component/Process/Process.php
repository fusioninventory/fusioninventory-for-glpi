<?php










namespace Symfony\Component\Process;

use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Exception\RuntimeException;









class Process
{
const ERR = 'err';
const OUT = 'out';

const STATUS_READY = 'ready';
const STATUS_STARTED = 'started';
const STATUS_TERMINATED = 'terminated';

const STDIN = 0;
const STDOUT = 1;
const STDERR = 2;


 const TIMEOUT_PRECISION = 0.2;

private $callback;
private $commandline;
private $cwd;
private $env;
private $stdin;
private $starttime;
private $lastOutputTime;
private $timeout;
private $idleTimeout;
private $options;
private $exitcode;
private $fallbackExitcode;
private $processInformation;
private $stdout;
private $stderr;
private $enhanceWindowsCompatibility;
private $enhanceSigchildCompatibility;
private $process;
private $status = self::STATUS_READY;
private $incrementalOutputOffset;
private $incrementalErrorOutputOffset;
private $tty;

private $useFileHandles = false;

private $processPipes;

private static $sigchild;








public static $exitCodes = array(
0 => 'OK',
1 => 'General error',
2 => 'Misuse of shell builtins',

126 => 'Invoked command cannot execute',
127 => 'Command not found',
128 => 'Invalid exit argument',


 129 => 'Hangup',
130 => 'Interrupt',
131 => 'Quit and dump core',
132 => 'Illegal instruction',
133 => 'Trace/breakpoint trap',
134 => 'Process aborted',
135 => 'Bus error: "access to undefined portion of memory object"',
136 => 'Floating point exception: "erroneous arithmetic operation"',
137 => 'Kill (terminate immediately)',
138 => 'User-defined 1',
139 => 'Segmentation violation',
140 => 'User-defined 2',
141 => 'Write to pipe with no one reading',
142 => 'Signal raised by alarm',
143 => 'Termination (request to terminate)',

 145 => 'Child process terminated, stopped (or continued*)',
146 => 'Continue if stopped',
147 => 'Stop executing temporarily',
148 => 'Terminal stop signal',
149 => 'Background process attempting to read from tty ("in")',
150 => 'Background process attempting to write to tty ("out")',
151 => 'Urgent data available on socket',
152 => 'CPU time limit exceeded',
153 => 'File size limit exceeded',
154 => 'Signal raised by timer counting virtual time: "virtual timer expired"',
155 => 'Profiling timer expired',

 157 => 'Pollable event',

 159 => 'Bad syscall',
);















public function __construct($commandline, $cwd = null, array $env = null, $stdin = null, $timeout = 60, array $options = array())
{
if (!function_exists('proc_open')) {
throw new RuntimeException('The Process class relies on proc_open, which is not available on your PHP installation.');
}

$this->commandline = $commandline;
$this->cwd = $cwd;


 
 
 

if (null === $this->cwd && (defined('ZEND_THREAD_SAFE') || defined('PHP_WINDOWS_VERSION_BUILD'))) {
$this->cwd = getcwd();
}
if (null !== $env) {
$this->setEnv($env);
} else {
$this->env = null;
}
$this->stdin = $stdin;
$this->setTimeout($timeout);
$this->useFileHandles = defined('PHP_WINDOWS_VERSION_BUILD');
$this->enhanceWindowsCompatibility = true;
$this->enhanceSigchildCompatibility = !defined('PHP_WINDOWS_VERSION_BUILD') && $this->isSigchildEnabled();
$this->options = array_replace(array('suppress_errors' => true, 'binary_pipes' => true), $options);
}

public function __destruct()
{

 $this->stop();
}

public function __clone()
{
$this->resetProcessData();
}




















public function run($callback = null)
{
$this->start($callback);

return $this->wait();
}
























public function start($callback = null)
{
if ($this->isRunning()) {
throw new RuntimeException('Process is already running');
}

$this->resetProcessData();
$this->starttime = $this->lastOutputTime = microtime(true);
$this->callback = $this->buildCallback($callback);
$descriptors = $this->getDescriptors();

$commandline = $this->commandline;

if (defined('PHP_WINDOWS_VERSION_BUILD') && $this->enhanceWindowsCompatibility) {
$commandline = 'cmd /V:ON /E:ON /C "'.$commandline.'"';
if (!isset($this->options['bypass_shell'])) {
$this->options['bypass_shell'] = true;
}
}

$this->process = proc_open($commandline, $descriptors, $this->processPipes->pipes, $this->cwd, $this->env, $this->options);

if (!is_resource($this->process)) {
throw new RuntimeException('Unable to launch a new process.');
}
$this->status = self::STATUS_STARTED;

$this->processPipes->unblock();

if ($this->tty) {
$this->status = self::STATUS_TERMINATED;

return;
}

$this->processPipes->write(false, $this->stdin);
$this->updateStatus(false);
$this->checkTimeout();
}
















public function restart($callback = null)
{
if ($this->isRunning()) {
throw new RuntimeException('Process is already running');
}

$process = clone $this;
$process->start($callback);

return $process;
}















public function wait($callback = null)
{
$this->updateStatus(false);
if (null !== $callback) {
$this->callback = $this->buildCallback($callback);
}

do {
$this->checkTimeout();
$running = defined('PHP_WINDOWS_VERSION_BUILD') ? $this->isRunning() : $this->processPipes->hasOpenHandles();
$close = !defined('PHP_WINDOWS_VERSION_BUILD') || !$running;;
$this->readPipes(true, $close);
} while ($running);

while ($this->isRunning()) {
usleep(1000);
}

if ($this->processInformation['signaled']) {
if ($this->isSigchildEnabled()) {
throw new RuntimeException('The process has been signaled.');
}

throw new RuntimeException(sprintf('The process has been signaled with signal "%s".', $this->processInformation['termsig']));
}

return $this->exitcode;
}








public function getPid()
{
if ($this->isSigchildEnabled()) {
throw new RuntimeException('This PHP has been compiled with --enable-sigchild. The process identifier can not be retrieved.');
}

$this->updateStatus(false);

return $this->isRunning() ? $this->processInformation['pid'] : null;
}











public function signal($signal)
{
if (!$this->isRunning()) {
throw new LogicException('Can not send signal on a non running process.');
}

if ($this->isSigchildEnabled()) {
throw new RuntimeException('This PHP has been compiled with --enable-sigchild. The process can not be signaled.');
}

if (true !== @proc_terminate($this->process, $signal)) {
throw new RuntimeException(sprintf('Error while sending signal `%d`.', $signal));
}

return $this;
}








public function getOutput()
{
$this->readPipes(false, defined('PHP_WINDOWS_VERSION_BUILD') ? !$this->processInformation['running'] : true);

return $this->stdout;
}









public function getIncrementalOutput()
{
$data = $this->getOutput();

$latest = substr($data, $this->incrementalOutputOffset);
$this->incrementalOutputOffset = strlen($data);

return $latest;
}






public function clearOutput()
{
$this->stdout = '';
$this->incrementalOutputOffset = 0;

return $this;
}








public function getErrorOutput()
{
$this->readPipes(false, defined('PHP_WINDOWS_VERSION_BUILD') ? !$this->processInformation['running'] : true);

return $this->stderr;
}










public function getIncrementalErrorOutput()
{
$data = $this->getErrorOutput();

$latest = substr($data, $this->incrementalErrorOutputOffset);
$this->incrementalErrorOutputOffset = strlen($data);

return $latest;
}






public function clearErrorOutput()
{
$this->stderr = '';
$this->incrementalErrorOutputOffset = 0;

return $this;
}










public function getExitCode()
{
if ($this->isSigchildEnabled() && !$this->enhanceSigchildCompatibility) {
throw new RuntimeException('This PHP has been compiled with --enable-sigchild. You must use setEnhanceSigchildCompatibility() to use this method');
}

$this->updateStatus(false);

return $this->exitcode;
}












public function getExitCodeText()
{
$exitcode = $this->getExitCode();

return isset(self::$exitCodes[$exitcode]) ? self::$exitCodes[$exitcode] : 'Unknown error';
}








public function isSuccessful()
{
return 0 === $this->getExitCode();
}












public function hasBeenSignaled()
{
if ($this->isSigchildEnabled()) {
throw new RuntimeException('This PHP has been compiled with --enable-sigchild. Term signal can not be retrieved');
}

$this->updateStatus(false);

return $this->processInformation['signaled'];
}












public function getTermSignal()
{
if ($this->isSigchildEnabled()) {
throw new RuntimeException('This PHP has been compiled with --enable-sigchild. Term signal can not be retrieved');
}

$this->updateStatus(false);

return $this->processInformation['termsig'];
}










public function hasBeenStopped()
{
$this->updateStatus(false);

return $this->processInformation['stopped'];
}










public function getStopSignal()
{
$this->updateStatus(false);

return $this->processInformation['stopsig'];
}






public function isRunning()
{
if (self::STATUS_STARTED !== $this->status) {
return false;
}

$this->updateStatus(false);

return $this->processInformation['running'];
}






public function isStarted()
{
return $this->status != self::STATUS_READY;
}






public function isTerminated()
{
$this->updateStatus(false);

return $this->status == self::STATUS_TERMINATED;
}








public function getStatus()
{
$this->updateStatus(false);

return $this->status;
}











public function stop($timeout = 10, $signal = null)
{
$timeoutMicro = microtime(true) + $timeout;
if ($this->isRunning()) {
proc_terminate($this->process);
do {
usleep(1000);
} while ($this->isRunning() && microtime(true) < $timeoutMicro);

if ($this->isRunning() && !$this->isSigchildEnabled()) {
if (null !== $signal || defined('SIGKILL')) {
$this->signal($signal ?: SIGKILL);
}
}
}

$this->updateStatus(false);
if ($this->processInformation['running']) {
$this->close();
}

$this->status = self::STATUS_TERMINATED;

return $this->exitcode;
}






public function addOutput($line)
{
$this->lastOutputTime = microtime(true);
$this->stdout .= $line;
}






public function addErrorOutput($line)
{
$this->lastOutputTime = microtime(true);
$this->stderr .= $line;
}






public function getCommandLine()
{
return $this->commandline;
}








public function setCommandLine($commandline)
{
$this->commandline = $commandline;

return $this;
}






public function getTimeout()
{
return $this->timeout;
}






public function getIdleTimeout()
{
return $this->idleTimeout;
}












public function setTimeout($timeout)
{
$this->timeout = $this->validateTimeout($timeout);

return $this;
}












public function setIdleTimeout($timeout)
{
$this->idleTimeout = $this->validateTimeout($timeout);

return $this;
}








public function setTty($tty)
{
$this->tty = (Boolean) $tty;

return $this;
}






public function isTty()
{
return $this->tty;
}






public function getWorkingDirectory()
{
if (null === $this->cwd) {

 
 return getcwd() ?: null;
}

return $this->cwd;
}








public function setWorkingDirectory($cwd)
{
$this->cwd = $cwd;

return $this;
}






public function getEnv()
{
return $this->env;
}














public function setEnv(array $env)
{

 $env = array_filter($env, function ($value) { if (!is_array($value)) { return true; } });

$this->env = array();
foreach ($env as $key => $value) {
$this->env[(binary) $key] = (binary) $value;
}

return $this;
}






public function getStdin()
{
return $this->stdin;
}








public function setStdin($stdin)
{
$this->stdin = $stdin;

return $this;
}






public function getOptions()
{
return $this->options;
}








public function setOptions(array $options)
{
$this->options = $options;

return $this;
}








public function getEnhanceWindowsCompatibility()
{
return $this->enhanceWindowsCompatibility;
}








public function setEnhanceWindowsCompatibility($enhance)
{
$this->enhanceWindowsCompatibility = (Boolean) $enhance;

return $this;
}






public function getEnhanceSigchildCompatibility()
{
return $this->enhanceSigchildCompatibility;
}












public function setEnhanceSigchildCompatibility($enhance)
{
$this->enhanceSigchildCompatibility = (Boolean) $enhance;

return $this;
}









public function checkTimeout()
{
if (null !== $this->timeout && $this->timeout < microtime(true) - $this->starttime) {
$this->stop(0);

throw new ProcessTimedOutException($this, ProcessTimedOutException::TYPE_GENERAL);
}

if (null !== $this->idleTimeout && $this->idleTimeout < microtime(true) - $this->lastOutputTime) {
$this->stop(0);

throw new ProcessTimedOutException($this, ProcessTimedOutException::TYPE_IDLE);
}
}






private function getDescriptors()
{
$this->processPipes = new ProcessPipes($this->useFileHandles, $this->tty);
$descriptors = $this->processPipes->getDescriptors();

if (!$this->useFileHandles && $this->enhanceSigchildCompatibility && $this->isSigchildEnabled()) {

 $descriptors = array_merge($descriptors, array(array('pipe', 'w')));

$this->commandline = '('.$this->commandline.') 3>/dev/null; code=$?; echo $code >&3; exit $code';
}

return $descriptors;
}











protected function buildCallback($callback)
{
$that = $this;
$out = self::OUT;
$err = self::ERR;
$callback = function ($type, $data) use ($that, $callback, $out, $err) {
if ($out == $type) {
$that->addOutput($data);
} else {
$that->addErrorOutput($data);
}

if (null !== $callback) {
call_user_func($callback, $type, $data);
}
};

return $callback;
}






protected function updateStatus($blocking)
{
if (self::STATUS_STARTED !== $this->status) {
return;
}

$this->processInformation = proc_get_status($this->process);
$this->captureExitCode();

$this->readPipes($blocking, defined('PHP_WINDOWS_VERSION_BUILD') ? !$this->processInformation['running'] : true);

if (!$this->processInformation['running']) {
$this->close();
$this->status = self::STATUS_TERMINATED;
}
}






protected function isSigchildEnabled()
{
if (null !== self::$sigchild) {
return self::$sigchild;
}

ob_start();
phpinfo(INFO_GENERAL);

return self::$sigchild = false !== strpos(ob_get_clean(), '--enable-sigchild');
}








private function validateTimeout($timeout)
{
$timeout = (float) $timeout;

if (0.0 === $timeout) {
$timeout = null;
} elseif ($timeout < 0) {
throw new InvalidArgumentException('The timeout value must be a valid positive integer or float number.');
}

return $timeout;
}






private function readPipes($blocking, $close)
{
if ($close) {
$result = $this->processPipes->readAndCloseHandles($blocking);
} else {
$result = $this->processPipes->read($blocking);
}

foreach ($result as $type => $data) {
if (3 == $type) {
$this->fallbackExitcode = (int) $data;
} else {
call_user_func($this->callback, $type === self::STDOUT ? self::OUT : self::ERR, $data);
}
}
}




private function captureExitCode()
{
if (isset($this->processInformation['exitcode']) && -1 != $this->processInformation['exitcode']) {
$this->exitcode = $this->processInformation['exitcode'];
}
}






private function close()
{
$exitcode = -1;

$this->processPipes->close();
if (is_resource($this->process)) {
$exitcode = proc_close($this->process);
}

$this->exitcode = $this->exitcode !== null ? $this->exitcode : -1;
$this->exitcode = -1 != $exitcode ? $exitcode : $this->exitcode;

if (-1 == $this->exitcode && null !== $this->fallbackExitcode) {
$this->exitcode = $this->fallbackExitcode;
} elseif (-1 === $this->exitcode && $this->processInformation['signaled'] && 0 < $this->processInformation['termsig']) {

 $this->exitcode = 128 + $this->processInformation['termsig'];
}

return $this->exitcode;
}




private function resetProcessData()
{
$this->starttime = null;
$this->callback = null;
$this->exitcode = null;
$this->fallbackExitcode = null;
$this->processInformation = null;
$this->stdout = null;
$this->stderr = null;
$this->process = null;
$this->status = self::STATUS_READY;
$this->incrementalOutputOffset = 0;
$this->incrementalErrorOutputOffset = 0;
}
}
