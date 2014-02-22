<?php










namespace Symfony\Component\Console\Helper;

use Symfony\Component\Console\Formatter\OutputFormatter;






class FormatterHelper extends Helper
{









public function formatSection($section, $message, $style = 'info')
{
return sprintf('<%s>[%s]</%s> %s', $style, $section, $style, $message);
}










public function formatBlock($messages, $style, $large = false)
{
$messages = (array) $messages;

$len = 0;
$lines = array();
foreach ($messages as $message) {
$message = OutputFormatter::escape($message);
$lines[] = sprintf($large ? '  %s  ' : ' %s ', $message);
$len = max($this->strlen($message) + ($large ? 4 : 2), $len);
}

$messages = $large ? array(str_repeat(' ', $len)) : array();
foreach ($lines as $line) {
$messages[] = $line.str_repeat(' ', $len - $this->strlen($line));
}
if ($large) {
$messages[] = str_repeat(' ', $len);
}

foreach ($messages as &$message) {
$message = sprintf('<%s>%s</%s>', $style, $message, $style);
}

return implode("\n", $messages);
}




public function getName()
{
return 'formatter';
}
}
