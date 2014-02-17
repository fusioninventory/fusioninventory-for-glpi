<?php











namespace Composer\IO;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;







class ConsoleIO extends BaseIO
{
protected $input;
protected $output;
protected $helperSet;
protected $lastMessage;
private $startTime;








public function __construct(InputInterface $input, OutputInterface $output, HelperSet $helperSet)
{
$this->input = $input;
$this->output = $output;
$this->helperSet = $helperSet;
}

public function enableDebugging($startTime)
{
$this->startTime = $startTime;
}




public function isInteractive()
{
return $this->input->isInteractive();
}




public function isDecorated()
{
return $this->output->isDecorated();
}




public function isVerbose()
{
return $this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE;
}




public function isVeryVerbose()
{
return $this->output->getVerbosity() >= 3; 
 }




public function isDebug()
{
return $this->output->getVerbosity() >= 4; 
 }




public function write($messages, $newline = true)
{
if (null !== $this->startTime) {
$messages = (array) $messages;
$messages[0] = sprintf(
'[%.1fMB/%.2fs] %s',
memory_get_usage() / 1024 / 1024,
microtime(true) - $this->startTime,
$messages[0]
);
}
$this->output->write($messages, $newline);
$this->lastMessage = join($newline ? "\n" : '', (array) $messages);
}




public function overwrite($messages, $newline = true, $size = null)
{

 $messages = join($newline ? "\n" : '', (array) $messages);


 if (!isset($size)) {

 $size = strlen(strip_tags($this->lastMessage));
}

 $this->write(str_repeat("\x08", $size), false);


 $this->write($messages, false);

$fill = $size - strlen(strip_tags($messages));
if ($fill > 0) {

 $this->write(str_repeat(' ', $fill), false);

 $this->write(str_repeat("\x08", $fill), false);
}

if ($newline) {
$this->write('');
}
$this->lastMessage = $messages;
}




public function ask($question, $default = null)
{
return $this->helperSet->get('dialog')->ask($this->output, $question, $default);
}




public function askConfirmation($question, $default = true)
{
return $this->helperSet->get('dialog')->askConfirmation($this->output, $question, $default);
}




public function askAndValidate($question, $validator, $attempts = false, $default = null)
{
return $this->helperSet->get('dialog')->askAndValidate($this->output, $question, $validator, $attempts, $default);
}




public function askAndHideAnswer($question)
{

 if (defined('PHP_WINDOWS_VERSION_BUILD')) {
$exe = __DIR__.'\\hiddeninput.exe';


 if ('phar:' === substr(__FILE__, 0, 5)) {
$tmpExe = sys_get_temp_dir().'/hiddeninput.exe';


 
 $source = fopen(__DIR__.'\\hiddeninput.exe', 'r');
$target = fopen($tmpExe, 'w+');
stream_copy_to_stream($source, $target);
fclose($source);
fclose($target);
unset($source, $target);

$exe = $tmpExe;
}

$this->write($question, false);
$value = rtrim(shell_exec($exe));
$this->write('');


 if (isset($tmpExe)) {
unlink($tmpExe);
}

return $value;
}

if (file_exists('/usr/bin/env')) {

 $test = "/usr/bin/env %s -c 'echo OK' 2> /dev/null";
foreach (array('bash', 'zsh', 'ksh', 'csh') as $sh) {
if ('OK' === rtrim(shell_exec(sprintf($test, $sh)))) {
$shell = $sh;
break;
}
}
if (isset($shell)) {
$this->write($question, false);
$readCmd = ($shell === 'csh') ? 'set mypassword = $<' : 'read -r mypassword';
$command = sprintf("/usr/bin/env %s -c 'stty -echo; %s; stty echo; echo \$mypassword'", $shell, $readCmd);
$value = rtrim(shell_exec($command));
$this->write('');

return $value;
}
}


 return $this->ask($question);
}
}
