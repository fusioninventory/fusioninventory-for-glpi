<?php










namespace Symfony\Component\Finder\Iterator;







class FilecontentFilterIterator extends MultiplePcreFilterIterator
{





public function accept()
{
if (!$this->matchRegexps && !$this->noMatchRegexps) {
return true;
}

$fileinfo = $this->current();

if ($fileinfo->isDir() || !$fileinfo->isReadable()) {
return false;
}

$content = $fileinfo->getContents();
if (!$content) {
return false;
}


 foreach ($this->noMatchRegexps as $regex) {
if (preg_match($regex, $content)) {
return false;
}
}


 $match = true;
if ($this->matchRegexps) {
$match = false;
foreach ($this->matchRegexps as $regex) {
if (preg_match($regex, $content)) {
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
