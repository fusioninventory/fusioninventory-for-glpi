<?php










namespace Symfony\Component\Console\Tester;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;











class ApplicationTester
{
private $application;
private $input;
private $output;
private $statusCode;






public function __construct(Application $application)
{
$this->application = $application;
}















public function run(array $input, $options = array())
{
$this->input = new ArrayInput($input);
if (isset($options['interactive'])) {
$this->input->setInteractive($options['interactive']);
}

$this->output = new StreamOutput(fopen('php://memory', 'w', false));
if (isset($options['decorated'])) {
$this->output->setDecorated($options['decorated']);
}
if (isset($options['verbosity'])) {
$this->output->setVerbosity($options['verbosity']);
}

return $this->statusCode = $this->application->run($this->input, $this->output);
}








public function getDisplay($normalize = false)
{
rewind($this->output->getStream());

$display = stream_get_contents($this->output->getStream());

if ($normalize) {
$display = str_replace(PHP_EOL, "\n", $display);
}

return $display;
}






public function getInput()
{
return $this->input;
}






public function getOutput()
{
return $this->output;
}






public function getStatusCode()
{
return $this->statusCode;
}
}
