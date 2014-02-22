<?php










namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\SplFileInfo;






class FilePathsIterator extends \ArrayIterator
{



private $baseDir;




private $baseDirLength;




private $subPath;




private $subPathname;




private $current;





public function __construct(array $paths, $baseDir)
{
$this->baseDir = $baseDir;
$this->baseDirLength = strlen($baseDir);

parent::__construct($paths);
}







public function __call($name, array $arguments)
{
return call_user_func_array(array($this->current(), $name), $arguments);
}






public function current()
{
return $this->current;
}




public function key()
{
return $this->current->getPathname();
}

public function next()
{
parent::next();
$this->buildProperties();
}

public function rewind()
{
parent::rewind();
$this->buildProperties();
}




public function getSubPath()
{
return $this->subPath;
}




public function getSubPathname()
{
return $this->subPathname;
}

private function buildProperties()
{
$absolutePath = parent::current();

if ($this->baseDir === substr($absolutePath, 0, $this->baseDirLength)) {
$this->subPathname = ltrim(substr($absolutePath, $this->baseDirLength), '/\\');
$dir = dirname($this->subPathname);
$this->subPath = '.' === $dir ? '' : $dir;
} else {
$this->subPath = $this->subPathname = '';
}

$this->current = new SplFileInfo(parent::current(), $this->subPath, $this->subPathname);
}
}
