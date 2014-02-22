<?php










namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Expression\Expression;






class FilenameFilterIterator extends MultiplePcreFilterIterator
{






public function accept()
{
$filename = $this->current()->getFilename();


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
return Expression::create($str)->getRegex()->render();
}
}
