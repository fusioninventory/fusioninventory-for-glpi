<?php










namespace Symfony\Component\Finder\Iterator;






class ExcludeDirectoryFilterIterator extends FilterIterator
{
private $patterns = array();







public function __construct(\Iterator $iterator, array $directories)
{
foreach ($directories as $directory) {
$this->patterns[] = '#(^|/)'.preg_quote($directory, '#').'(/|$)#';
}

parent::__construct($iterator);
}






public function accept()
{
$path = $this->isDir() ? $this->current()->getRelativePathname() : $this->current()->getRelativePath();
$path = strtr($path, '\\', '/');
foreach ($this->patterns as $pattern) {
if (preg_match($pattern, $path)) {
return false;
}
}

return true;
}
}
