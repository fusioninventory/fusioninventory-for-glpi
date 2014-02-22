<?php










namespace Symfony\Component\Console\Output;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
















class ConsoleOutput extends StreamOutput implements ConsoleOutputInterface
{
private $stderr;










public function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = null, OutputFormatterInterface $formatter = null)
{
$outputStream = 'php://stdout';
if (!$this->hasStdoutSupport()) {
$outputStream = 'php://output';
}

parent::__construct(fopen($outputStream, 'w'), $verbosity, $decorated, $formatter);

$this->stderr = new StreamOutput(fopen('php://stderr', 'w'), $verbosity, $decorated, $formatter);
}




public function setDecorated($decorated)
{
parent::setDecorated($decorated);
$this->stderr->setDecorated($decorated);
}




public function setFormatter(OutputFormatterInterface $formatter)
{
parent::setFormatter($formatter);
$this->stderr->setFormatter($formatter);
}




public function setVerbosity($level)
{
parent::setVerbosity($level);
$this->stderr->setVerbosity($level);
}




public function getErrorOutput()
{
return $this->stderr;
}




public function setErrorOutput(OutputInterface $error)
{
$this->stderr = $error;
}











protected function hasStdoutSupport()
{
return ('OS400' != php_uname('s'));
}
}
