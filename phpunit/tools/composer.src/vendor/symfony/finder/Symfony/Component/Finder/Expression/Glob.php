<?php










namespace Symfony\Component\Finder\Expression;




class Glob implements ValueInterface
{



private $pattern;




public function __construct($pattern)
{
$this->pattern = $pattern;
}




public function render()
{
return $this->pattern;
}




public function renderPattern()
{
return $this->pattern;
}




public function getType()
{
return Expression::TYPE_GLOB;
}




public function isCaseSensitive()
{
return true;
}




public function prepend($expr)
{
$this->pattern = $expr.$this->pattern;

return $this;
}




public function append($expr)
{
$this->pattern .= $expr;

return $this;
}






public function isExpandable()
{
return false !== strpos($this->pattern, '{')
&& false !== strpos($this->pattern, '}');
}







public function toRegex($strictLeadingDot = true, $strictWildcardSlash = true)
{
$firstByte = true;
$escaping = false;
$inCurlies = 0;
$regex = '';
$sizeGlob = strlen($this->pattern);
for ($i = 0; $i < $sizeGlob; $i++) {
$car = $this->pattern[$i];
if ($firstByte) {
if ($strictLeadingDot && '.' !== $car) {
$regex .= '(?=[^\.])';
}

$firstByte = false;
}

if ('/' === $car) {
$firstByte = true;
}

if ('.' === $car || '(' === $car || ')' === $car || '|' === $car || '+' === $car || '^' === $car || '$' === $car) {
$regex .= "\\$car";
} elseif ('*' === $car) {
$regex .= $escaping ? '\\*' : ($strictWildcardSlash ? '[^/]*' : '.*');
} elseif ('?' === $car) {
$regex .= $escaping ? '\\?' : ($strictWildcardSlash ? '[^/]' : '.');
} elseif ('{' === $car) {
$regex .= $escaping ? '\\{' : '(';
if (!$escaping) {
++$inCurlies;
}
} elseif ('}' === $car && $inCurlies) {
$regex .= $escaping ? '}' : ')';
if (!$escaping) {
--$inCurlies;
}
} elseif (',' === $car && $inCurlies) {
$regex .= $escaping ? ',' : '|';
} elseif ('\\' === $car) {
if ($escaping) {
$regex .= '\\\\';
$escaping = false;
} else {
$escaping = true;
}

continue;
} else {
$regex .= $car;
}
$escaping = false;
}

return new Regex('^'.$regex.'$');
}
}
