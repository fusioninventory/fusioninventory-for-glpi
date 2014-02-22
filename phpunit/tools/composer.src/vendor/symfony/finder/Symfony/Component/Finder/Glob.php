<?php










namespace Symfony\Component\Finder;























class Glob
{









public static function toRegex($glob, $strictLeadingDot = true, $strictWildcardSlash = true)
{
$firstByte = true;
$escaping = false;
$inCurlies = 0;
$regex = '';
$sizeGlob = strlen($glob);
for ($i = 0; $i < $sizeGlob; $i++) {
$car = $glob[$i];
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

return '#^'.$regex.'$#';
}
}
