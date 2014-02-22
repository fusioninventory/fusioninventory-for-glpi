<?php










namespace Symfony\Component\Process\Exception;

use Symfony\Component\Process\Process;






class ProcessFailedException extends RuntimeException
{
private $process;

public function __construct(Process $process)
{
if ($process->isSuccessful()) {
throw new InvalidArgumentException('Expected a failed process, but the given process was successful.');
}

parent::__construct(
sprintf(
'The command "%s" failed.'."\nExit Code: %s(%s)\n\nOutput:\n================\n%s\n\nError Output:\n================\n%s",
$process->getCommandLine(),
$process->getExitCode(),
$process->getExitCodeText(),
$process->getOutput(),
$process->getErrorOutput()
)
);

$this->process = $process;
}

public function getProcess()
{
return $this->process;
}
}
