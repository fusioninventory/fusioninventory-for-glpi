<?php










namespace Symfony\Component\Console\Command;

use Symfony\Component\Console\Descriptor\TextDescriptor;
use Symfony\Component\Console\Descriptor\XmlDescriptor;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;








class Command
{
private $application;
private $name;
private $aliases = array();
private $definition;
private $help;
private $description;
private $ignoreValidationErrors = false;
private $applicationDefinitionMerged = false;
private $applicationDefinitionMergedWithArgs = false;
private $code;
private $synopsis;
private $helperSet;










public function __construct($name = null)
{
$this->definition = new InputDefinition();

if (null !== $name) {
$this->setName($name);
}

$this->configure();

if (!$this->name) {
throw new \LogicException('The command name cannot be empty.');
}
}






public function ignoreValidationErrors()
{
$this->ignoreValidationErrors = true;
}








public function setApplication(Application $application = null)
{
$this->application = $application;
if ($application) {
$this->setHelperSet($application->getHelperSet());
} else {
$this->helperSet = null;
}
}






public function setHelperSet(HelperSet $helperSet)
{
$this->helperSet = $helperSet;
}






public function getHelperSet()
{
return $this->helperSet;
}








public function getApplication()
{
return $this->application;
}









public function isEnabled()
{
return true;
}




protected function configure()
{
}

















protected function execute(InputInterface $input, OutputInterface $output)
{
throw new \LogicException('You must override the execute() method in the concrete command class.');
}







protected function interact(InputInterface $input, OutputInterface $output)
{
}










protected function initialize(InputInterface $input, OutputInterface $output)
{
}




















public function run(InputInterface $input, OutputInterface $output)
{

 $this->getSynopsis();


 $this->mergeApplicationDefinition();


 try {
$input->bind($this->definition);
} catch (\Exception $e) {
if (!$this->ignoreValidationErrors) {
throw $e;
}
}

$this->initialize($input, $output);

if ($input->isInteractive()) {
$this->interact($input, $output);
}

$input->validate();

if ($this->code) {
$statusCode = call_user_func($this->code, $input, $output);
} else {
$statusCode = $this->execute($input, $output);
}

return is_numeric($statusCode) ? (int) $statusCode : 0;
}

















public function setCode($code)
{
if (!is_callable($code)) {
throw new \InvalidArgumentException('Invalid callable provided to Command::setCode.');
}

$this->code = $code;

return $this;
}








public function mergeApplicationDefinition($mergeArgs = true)
{
if (null === $this->application || (true === $this->applicationDefinitionMerged && ($this->applicationDefinitionMergedWithArgs || !$mergeArgs))) {
return;
}

if ($mergeArgs) {
$currentArguments = $this->definition->getArguments();
$this->definition->setArguments($this->application->getDefinition()->getArguments());
$this->definition->addArguments($currentArguments);
}

$this->definition->addOptions($this->application->getDefinition()->getOptions());

$this->applicationDefinitionMerged = true;
if ($mergeArgs) {
$this->applicationDefinitionMergedWithArgs = true;
}
}










public function setDefinition($definition)
{
if ($definition instanceof InputDefinition) {
$this->definition = $definition;
} else {
$this->definition->setDefinition($definition);
}

$this->applicationDefinitionMerged = false;

return $this;
}








public function getDefinition()
{
return $this->definition;
}











public function getNativeDefinition()
{
return $this->getDefinition();
}













public function addArgument($name, $mode = null, $description = '', $default = null)
{
$this->definition->addArgument(new InputArgument($name, $mode, $description, $default));

return $this;
}














public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
{
$this->definition->addOption(new InputOption($name, $shortcut, $mode, $description, $default));

return $this;
}

















public function setName($name)
{
$this->validateName($name);

$this->name = $name;

return $this;
}








public function getName()
{
return $this->name;
}










public function setDescription($description)
{
$this->description = $description;

return $this;
}








public function getDescription()
{
return $this->description;
}










public function setHelp($help)
{
$this->help = $help;

return $this;
}








public function getHelp()
{
return $this->help;
}







public function getProcessedHelp()
{
$name = $this->name;

$placeholders = array(
'%command.name%',
'%command.full_name%'
);
$replacements = array(
$name,
$_SERVER['PHP_SELF'].' '.$name
);

return str_replace($placeholders, $replacements, $this->getHelp());
}












public function setAliases($aliases)
{
foreach ($aliases as $alias) {
$this->validateName($alias);
}

$this->aliases = $aliases;

return $this;
}








public function getAliases()
{
return $this->aliases;
}






public function getSynopsis()
{
if (null === $this->synopsis) {
$this->synopsis = trim(sprintf('%s %s', $this->name, $this->definition->getSynopsis()));
}

return $this->synopsis;
}












public function getHelper($name)
{
return $this->helperSet->get($name);
}








public function asText()
{
$descriptor = new TextDescriptor();
$output = new BufferedOutput(BufferedOutput::VERBOSITY_NORMAL, true);
$descriptor->describe($output, $this, array('raw_output' => true));

return $output->fetch();
}










public function asXml($asDom = false)
{
$descriptor = new XmlDescriptor();

if ($asDom) {
return $descriptor->getCommandDocument($this);
}

$output = new BufferedOutput();
$descriptor->describe($output, $this);

return $output->fetch();
}










private function validateName($name)
{
if (!preg_match('/^[^\:]++(\:[^\:]++)*$/', $name)) {
throw new \InvalidArgumentException(sprintf('Command name "%s" is invalid.', $name));
}
}
}
