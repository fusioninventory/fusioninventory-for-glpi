<?php










namespace Symfony\Component\Console\Event;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;






class ConsoleExceptionEvent extends ConsoleEvent
{
private $exception;
private $exitCode;

public function __construct(Command $command, InputInterface $input, OutputInterface $output, \Exception $exception, $exitCode)
{
parent::__construct($command, $input, $output);

$this->setException($exception);
$this->exitCode = (int) $exitCode;
}






public function getException()
{
return $this->exception;
}








public function setException(\Exception $exception)
{
$this->exception = $exception;
}






public function getExitCode()
{
return $this->exitCode;
}
}
