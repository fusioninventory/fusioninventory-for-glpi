<?php











namespace Composer\EventDispatcher;

use Composer\IO\IOInterface;
use Composer\Composer;
use Composer\DependencyResolver\Operation\OperationInterface;
use Composer\Script;
use Composer\Script\CommandEvent;
use Composer\Script\PackageEvent;
use Composer\Util\ProcessExecutor;














class EventDispatcher
{
protected $composer;
protected $io;
protected $loader;
protected $process;








public function __construct(Composer $composer, IOInterface $io, ProcessExecutor $process = null)
{
$this->composer = $composer;
$this->io = $io;
$this->process = $process ?: new ProcessExecutor($io);
}







public function dispatch($eventName, Event $event = null)
{
if (null == $event) {
$event = new Event($eventName);
}

$this->doDispatch($event);
}







public function dispatchScript($eventName, Script\Event $event = null)
{
if (null == $event) {
$event = new Script\Event($eventName, $this->composer, $this->io);
}

$this->doDispatch($event);
}








public function dispatchPackageEvent($eventName, $devMode, OperationInterface $operation)
{
$this->doDispatch(new PackageEvent($eventName, $this->composer, $this->io, $devMode, $operation));
}







public function dispatchCommandEvent($eventName, $devMode)
{
$this->doDispatch(new CommandEvent($eventName, $this->composer, $this->io, $devMode));
}








protected function doDispatch(Event $event)
{
$listeners = $this->getListeners($event);

foreach ($listeners as $callable) {
if (!is_string($callable) && is_callable($callable)) {
call_user_func($callable, $event);
} elseif ($this->isPhpScript($callable)) {
$className = substr($callable, 0, strpos($callable, '::'));
$methodName = substr($callable, strpos($callable, '::') + 2);

if (!class_exists($className)) {
$this->io->write('<warning>Class '.$className.' is not autoloadable, can not call '.$event->getName().' script</warning>');
continue;
}
if (!is_callable($callable)) {
$this->io->write('<warning>Method '.$callable.' is not callable, can not call '.$event->getName().' script</warning>');
continue;
}

try {
$this->executeEventPhpScript($className, $methodName, $event);
} catch (\Exception $e) {
$message = "Script %s handling the %s event terminated with an exception";
$this->io->write('<error>'.sprintf($message, $callable, $event->getName()).'</error>');
throw $e;
}
} else {
if (0 !== ($exitCode = $this->process->execute($callable))) {
$event->getIO()->write(sprintf('<error>Script %s handling the %s event returned with an error</error>', $callable, $event->getName()));

throw new \RuntimeException('Error Output: '.$this->process->getErrorOutput(), $exitCode);
}
}

if ($event->isPropagationStopped()) {
break;
}
}
}






protected function executeEventPhpScript($className, $methodName, Event $event)
{
$className::$methodName($event);
}








protected function addListener($eventName, $listener, $priority = 0)
{
$this->listeners[$eventName][$priority][] = $listener;
}








public function addSubscriber(EventSubscriberInterface $subscriber)
{
foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
if (is_string($params)) {
$this->addListener($eventName, array($subscriber, $params));
} elseif (is_string($params[0])) {
$this->addListener($eventName, array($subscriber, $params[0]), isset($params[1]) ? $params[1] : 0);
} else {
foreach ($params as $listener) {
$this->addListener($eventName, array($subscriber, $listener[0]), isset($listener[1]) ? $listener[1] : 0);
}
}
}
}







protected function getListeners(Event $event)
{
$scriptListeners = $this->getScriptListeners($event);

if (!isset($this->listeners[$event->getName()][0])) {
$this->listeners[$event->getName()][0] = array();
}
krsort($this->listeners[$event->getName()]);

$listeners = $this->listeners;
$listeners[$event->getName()][0] = array_merge($listeners[$event->getName()][0], $scriptListeners);

return call_user_func_array('array_merge', $listeners[$event->getName()]);
}







protected function getScriptListeners(Event $event)
{
$package = $this->composer->getPackage();
$scripts = $package->getScripts();

if (empty($scripts[$event->getName()])) {
return array();
}

if ($this->loader) {
$this->loader->unregister();
}

$generator = $this->composer->getAutoloadGenerator();
$packages = $this->composer->getRepositoryManager()->getLocalRepository()->getCanonicalPackages();
$packageMap = $generator->buildPackageMap($this->composer->getInstallationManager(), $package, $packages);
$map = $generator->parseAutoloads($packageMap, $package);
$this->loader = $generator->createLoader($map);
$this->loader->register();

return $scripts[$event->getName()];
}







protected function isPhpScript($callable)
{
return false === strpos($callable, ' ') && false !== strpos($callable, '::');
}
}
