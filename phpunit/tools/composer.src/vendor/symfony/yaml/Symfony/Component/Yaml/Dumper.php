<?php










namespace Symfony\Component\Yaml;






class Dumper
{





protected $indentation = 4;






public function setIndentation($num)
{
$this->indentation = (int) $num;
}












public function dump($input, $inline = 0, $indent = 0, $exceptionOnInvalidType = false, $objectSupport = false)
{
$output = '';
$prefix = $indent ? str_repeat(' ', $indent) : '';

if ($inline <= 0 || !is_array($input) || empty($input)) {
$output .= $prefix.Inline::dump($input, $exceptionOnInvalidType, $objectSupport);
} else {
$isAHash = array_keys($input) !== range(0, count($input) - 1);

foreach ($input as $key => $value) {
$willBeInlined = $inline - 1 <= 0 || !is_array($value) || empty($value);

$output .= sprintf('%s%s%s%s',
$prefix,
$isAHash ? Inline::dump($key, $exceptionOnInvalidType, $objectSupport).':' : '-',
$willBeInlined ? ' ' : "\n",
$this->dump($value, $inline - 1, $willBeInlined ? 0 : $indent + $this->indentation, $exceptionOnInvalidType, $objectSupport)
).($willBeInlined ? "\n" : '');
}
}

return $output;
}
}
