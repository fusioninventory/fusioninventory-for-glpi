<?php










namespace Symfony\Component\Console\Descriptor;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;




class ApplicationDescription
{
const GLOBAL_NAMESPACE = '_global';




private $application;




private $namespace;




private $namespaces;




private $commands;




private $aliases;







public function __construct(Application $application, $namespace = null)
{
$this->application = $application;
$this->namespace = $namespace;
}




public function getNamespaces()
{
if (null === $this->namespaces) {
$this->inspectApplication();
}

return $this->namespaces;
}




public function getCommands()
{
if (null === $this->commands) {
$this->inspectApplication();
}

return $this->commands;
}








public function getCommand($name)
{
if (!isset($this->commands[$name]) && !isset($this->aliases[$name])) {
throw new \InvalidArgumentException(sprintf('Command %s does not exist.', $name));
}

return isset($this->commands[$name]) ? $this->commands[$name] : $this->aliases[$name];
}

private function inspectApplication()
{
$this->commands = array();
$this->namespaces = array();

$all = $this->application->all($this->namespace ? $this->application->findNamespace($this->namespace) : null);
foreach ($this->sortCommands($all) as $namespace => $commands) {
$names = array();


foreach ($commands as $name => $command) {
if (!$command->getName()) {
continue;
}

if ($command->getName() === $name) {
$this->commands[$name] = $command;
} else {
$this->aliases[$name] = $command;
}

$names[] = $name;
}

$this->namespaces[$namespace] = array('id' => $namespace, 'commands' => $names);
}
}






private function sortCommands(array $commands)
{
$namespacedCommands = array();
foreach ($commands as $name => $command) {
$key = $this->application->extractNamespace($name, 1);
if (!$key) {
$key = '_global';
}

$namespacedCommands[$key][$name] = $command;
}
ksort($namespacedCommands);

foreach ($namespacedCommands as &$commands) {
ksort($commands);
}

return $namespacedCommands;
}
}
