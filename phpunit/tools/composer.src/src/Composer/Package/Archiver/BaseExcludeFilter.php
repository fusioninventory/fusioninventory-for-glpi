<?php











namespace Composer\Package\Archiver;

use Symfony\Component\Finder;




abstract class BaseExcludeFilter
{



protected $sourcePath;




protected $excludePatterns;




public function __construct($sourcePath)
{
$this->sourcePath = $sourcePath;
$this->excludePatterns = array();
}











public function filter($relativePath, $exclude)
{
foreach ($this->excludePatterns as $patternData) {
list($pattern, $negate, $stripLeadingSlash) = $patternData;

if ($stripLeadingSlash) {
$path = substr($relativePath, 1);
} else {
$path = $relativePath;
}

if (preg_match($pattern, $path)) {
$exclude = !$negate;
}
}

return $exclude;
}









protected function parseLines(array $lines, $lineParser)
{
return array_filter(
array_map(
function ($line) use ($lineParser) {
$line = trim($line);

$commentHash = strpos($line, '#');
if ($commentHash !== false) {
$line = substr($line, 0, $commentHash);
}

if ($line) {
return call_user_func($lineParser, $line);
}

return null;
}, $lines),
function ($pattern) {
return $pattern !== null;
}
);
}








protected function generatePatterns($rules)
{
$patterns = array();
foreach ($rules as $rule) {
$patterns[] = $this->generatePattern($rule);
}

return $patterns;
}








protected function generatePattern($rule)
{
$negate = false;
$pattern = '#';

if (strlen($rule) && $rule[0] === '!') {
$negate = true;
$rule = substr($rule, 1);
}

if (strlen($rule) && $rule[0] === '/') {
$pattern .= '^/';
$rule = substr($rule, 1);
} elseif (false === strpos($rule, '/') || strlen($rule) - 1 === strpos($rule, '/')) {
$pattern .= '/';
}


 $pattern .= substr(Finder\Glob::toRegex($rule), 2, -1);

return array($pattern . '#', $negate, false);
}
}
