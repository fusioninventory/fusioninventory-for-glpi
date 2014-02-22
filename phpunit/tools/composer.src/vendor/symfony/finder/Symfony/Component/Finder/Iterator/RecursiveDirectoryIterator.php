<?php










namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Finder\SplFileInfo;






class RecursiveDirectoryIterator extends \RecursiveDirectoryIterator
{



private $ignoreUnreadableDirs;




private $rewindable;










public function __construct($path, $flags, $ignoreUnreadableDirs = false)
{
if ($flags & (self::CURRENT_AS_PATHNAME | self::CURRENT_AS_SELF)) {
throw new \RuntimeException('This iterator only support returning current as fileinfo.');
}

parent::__construct($path, $flags);
$this->ignoreUnreadableDirs = $ignoreUnreadableDirs;
}






public function current()
{
return new SplFileInfo(parent::current()->getPathname(), $this->getSubPath(), $this->getSubPathname());
}






public function getChildren()
{
try {
return parent::getChildren();
} catch (\UnexpectedValueException $e) {
if ($this->ignoreUnreadableDirs) {

 return new \RecursiveArrayIterator(array());
} else {
throw new AccessDeniedException($e->getMessage(), $e->getCode(), $e);
}
}
}




public function rewind()
{
if (false === $this->isRewindable()) {
return;
}


 parent::next();

parent::rewind();
}






public function isRewindable()
{
if (null !== $this->rewindable) {
return $this->rewindable;
}

if (false !== $stream = @opendir($this->getPath())) {
$infos = stream_get_meta_data($stream);
closedir($stream);

if ($infos['seekable']) {
return $this->rewindable = true;
}
}

return $this->rewindable = false;
}
}
