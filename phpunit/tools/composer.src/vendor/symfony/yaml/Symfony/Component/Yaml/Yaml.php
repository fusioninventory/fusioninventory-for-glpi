<?php










namespace Symfony\Component\Yaml;

use Symfony\Component\Yaml\Exception\ParseException;








class Yaml
{


























public static function parse($input, $exceptionOnInvalidType = false, $objectSupport = false)
{

 $file = '';
if (strpos($input, "\n") === false && is_file($input)) {
if (false === is_readable($input)) {
throw new ParseException(sprintf('Unable to parse "%s" as the file is not readable.', $input));
}

$file = $input;
$input = file_get_contents($file);
}

$yaml = new Parser();

try {
return $yaml->parse($input, $exceptionOnInvalidType, $objectSupport);
} catch (ParseException $e) {
if ($file) {
$e->setParsedFile($file);
}

throw $e;
}
}

















public static function dump($array, $inline = 2, $indent = 4, $exceptionOnInvalidType = false, $objectSupport = false)
{
$yaml = new Dumper();
$yaml->setIndentation($indent);

return $yaml->dump($array, $inline, 0, $exceptionOnInvalidType, $objectSupport);
}
}
