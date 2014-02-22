<?php










namespace Symfony\Component\Process;








class ProcessUtils
{



private function __construct()
{
}








public static function escapeArgument($argument)
{

 
 
 
 if (defined('PHP_WINDOWS_VERSION_BUILD')) {
if ('' === $argument) {
return escapeshellarg($argument);
}

$escapedArgument = '';
foreach (preg_split('/([%"])/i', $argument, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE) as $part) {
if ('"' === $part) {
$escapedArgument .= '\\"';
} elseif ('%' === $part) {
$escapedArgument .= '^%';
} else {
$escapedArgument .= escapeshellarg($part);
}
}

return $escapedArgument;
}

return escapeshellarg($argument);
}
}
