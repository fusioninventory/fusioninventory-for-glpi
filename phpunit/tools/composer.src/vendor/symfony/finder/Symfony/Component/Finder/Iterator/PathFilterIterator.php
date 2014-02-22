<?php










namespace Symfony\Component\Finder\Iterator;







class PathFilterIterator extends MultiplePcreFilterIterator
{






public function accept()
{
$filename = $this->current()->getRelativePathname();

if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
$filename = strtr($filename, '\\', '/');
}


 foreach ($this->noMatchRegexps as $regex) {
if (preg_match($regex, $filename)) {
return false;
}
}


 $match = true;
if ($this->matchRegexps) {
$match = false;
foreach ($this->matchRegexps as $regex) {
if (preg_match($regex, $filename)) {
return true;
}
}
}

return $match;
}















protected function toRegex($str)
{
return $this->isRegex($str) ? $str : '/'.preg_quote($str, '/').'/';
}
}
