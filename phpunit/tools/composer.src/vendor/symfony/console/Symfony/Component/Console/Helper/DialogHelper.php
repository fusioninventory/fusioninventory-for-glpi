<?php










namespace Symfony\Component\Console\Helper;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;






class DialogHelper extends InputAwareHelper
{
private $inputStream;
private static $shell;
private static $stty;
















public function select(OutputInterface $output, $question, $choices, $default = null, $attempts = false, $errorMessage = 'Value "%s" is invalid', $multiselect = false)
{
$width = max(array_map('strlen', array_keys($choices)));

$messages = (array) $question;
foreach ($choices as $key => $value) {
$messages[] = sprintf("  [<info>%-${width}s</info>] %s", $key, $value);
}

$output->writeln($messages);

$result = $this->askAndValidate($output, '> ', function ($picked) use ($choices, $errorMessage, $multiselect) {

 $selectedChoices = str_replace(" ", "", $picked);

if ($multiselect) {

 if (!preg_match('/^[a-zA-Z0-9_-]+(?:,[a-zA-Z0-9_-]+)*$/', $selectedChoices, $matches)) {
throw new \InvalidArgumentException(sprintf($errorMessage, $picked));
}
$selectedChoices = explode(",", $selectedChoices);
} else {
$selectedChoices = array($picked);
}

$multiselectChoices = array();

foreach ($selectedChoices as $value) {
if (empty($choices[$value])) {
throw new \InvalidArgumentException(sprintf($errorMessage, $value));
}
array_push($multiselectChoices, $value);
}

if ($multiselect) {
return $multiselectChoices;
}

return $picked;
}, $attempts, $default);

return $result;
}













public function ask(OutputInterface $output, $question, $default = null, array $autocomplete = null)
{
if ($this->input && !$this->input->isInteractive()) {
return $default;
}

$output->write($question);

$inputStream = $this->inputStream ?: STDIN;

if (null === $autocomplete || !$this->hasSttyAvailable()) {
$ret = fgets($inputStream, 4096);
if (false === $ret) {
throw new \RuntimeException('Aborted');
}
$ret = trim($ret);
} else {
$ret = '';

$i = 0;
$ofs = -1;
$matches = $autocomplete;
$numMatches = count($matches);

$sttyMode = shell_exec('stty -g');


 shell_exec('stty -icanon -echo');


 $output->getFormatter()->setStyle('hl', new OutputFormatterStyle('black', 'white'));


 while (!feof($inputStream)) {
$c = fread($inputStream, 1);


 if ("\177" === $c) {
if (0 === $numMatches && 0 !== $i) {
$i--;

 $output->write("\033[1D");
}

if ($i === 0) {
$ofs = -1;
$matches = $autocomplete;
$numMatches = count($matches);
} else {
$numMatches = 0;
}


 $ret = substr($ret, 0, $i);
} elseif ("\033" === $c) { 
 $c .= fread($inputStream, 2);


 if ('A' === $c[2] || 'B' === $c[2]) {
if ('A' === $c[2] && -1 === $ofs) {
$ofs = 0;
}

if (0 === $numMatches) {
continue;
}

$ofs += ('A' === $c[2]) ? -1 : 1;
$ofs = ($numMatches + $ofs) % $numMatches;
}
} elseif (ord($c) < 32) {
if ("\t" === $c || "\n" === $c) {
if ($numMatches > 0 && -1 !== $ofs) {
$ret = $matches[$ofs];

 $output->write(substr($ret, $i));
$i = strlen($ret);
}

if ("\n" === $c) {
$output->write($c);
break;
}

$numMatches = 0;
}

continue;
} else {
$output->write($c);
$ret .= $c;
$i++;

$numMatches = 0;
$ofs = 0;

foreach ($autocomplete as $value) {

 if (0 === strpos($value, $ret) && $i !== strlen($value)) {
$matches[$numMatches++] = $value;
}
}
}


 $output->write("\033[K");

if ($numMatches > 0 && -1 !== $ofs) {

 $output->write("\0337");

 $output->write('<hl>'.substr($matches[$ofs], $i).'</hl>');

 $output->write("\0338");
}
}


 shell_exec(sprintf('stty %s', $sttyMode));
}

return strlen($ret) > 0 ? $ret : $default;
}












public function askConfirmation(OutputInterface $output, $question, $default = true)
{
$answer = 'z';
while ($answer && !in_array(strtolower($answer[0]), array('y', 'n'))) {
$answer = $this->ask($output, $question);
}

if (false === $default) {
return $answer && 'y' == strtolower($answer[0]);
}

return !$answer || 'y' == strtolower($answer[0]);
}












public function askHiddenResponse(OutputInterface $output, $question, $fallback = true)
{
if (defined('PHP_WINDOWS_VERSION_BUILD')) {
$exe = __DIR__.'/../Resources/bin/hiddeninput.exe';


 if ('phar:' === substr(__FILE__, 0, 5)) {
$tmpExe = sys_get_temp_dir().'/hiddeninput.exe';
copy($exe, $tmpExe);
$exe = $tmpExe;
}

$output->write($question);
$value = rtrim(shell_exec($exe));
$output->writeln('');

if (isset($tmpExe)) {
unlink($tmpExe);
}

return $value;
}

if ($this->hasSttyAvailable()) {
$output->write($question);

$sttyMode = shell_exec('stty -g');

shell_exec('stty -echo');
$value = fgets($this->inputStream ?: STDIN, 4096);
shell_exec(sprintf('stty %s', $sttyMode));

if (false === $value) {
throw new \RuntimeException('Aborted');
}

$value = trim($value);
$output->writeln('');

return $value;
}

if (false !== $shell = $this->getShell()) {
$output->write($question);
$readCmd = $shell === 'csh' ? 'set mypassword = $<' : 'read -r mypassword';
$command = sprintf("/usr/bin/env %s -c 'stty -echo; %s; stty echo; echo \$mypassword'", $shell, $readCmd);
$value = rtrim(shell_exec($command));
$output->writeln('');

return $value;
}

if ($fallback) {
return $this->ask($output, $question);
}

throw new \RuntimeException('Unable to hide the response');
}



















public function askAndValidate(OutputInterface $output, $question, $validator, $attempts = false, $default = null, array $autocomplete = null)
{
$that = $this;

$interviewer = function () use ($output, $question, $default, $autocomplete, $that) {
return $that->ask($output, $question, $default, $autocomplete);
};

return $this->validateAttempts($interviewer, $output, $validator, $attempts);
}




















public function askHiddenResponseAndValidate(OutputInterface $output, $question, $validator, $attempts = false, $fallback = true)
{
$that = $this;

$interviewer = function () use ($output, $question, $fallback, $that) {
return $that->askHiddenResponse($output, $question, $fallback);
};

return $this->validateAttempts($interviewer, $output, $validator, $attempts);
}








public function setInputStream($stream)
{
$this->inputStream = $stream;
}






public function getInputStream()
{
return $this->inputStream;
}




public function getName()
{
return 'dialog';
}






private function getShell()
{
if (null !== self::$shell) {
return self::$shell;
}

self::$shell = false;

if (file_exists('/usr/bin/env')) {

 $test = "/usr/bin/env %s -c 'echo OK' 2> /dev/null";
foreach (array('bash', 'zsh', 'ksh', 'csh') as $sh) {
if ('OK' === rtrim(shell_exec(sprintf($test, $sh)))) {
self::$shell = $sh;
break;
}
}
}

return self::$shell;
}

private function hasSttyAvailable()
{
if (null !== self::$stty) {
return self::$stty;
}

exec('stty 2>&1', $output, $exitcode);

return self::$stty = $exitcode === 0;
}













private function validateAttempts($interviewer, OutputInterface $output, $validator, $attempts)
{
$error = null;
while (false === $attempts || $attempts--) {
if (null !== $error) {
$output->writeln($this->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
}

try {
return call_user_func($validator, $interviewer());
} catch (\Exception $error) {
}
}

throw $error;
}
}
