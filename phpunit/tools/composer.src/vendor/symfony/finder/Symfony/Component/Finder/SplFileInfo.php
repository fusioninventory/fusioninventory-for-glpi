<?php










namespace Symfony\Component\Finder;






class SplFileInfo extends \SplFileInfo
{
private $relativePath;
private $relativePathname;








public function __construct($file, $relativePath, $relativePathname)
{
parent::__construct($file);
$this->relativePath = $relativePath;
$this->relativePathname = $relativePathname;
}






public function getRelativePath()
{
return $this->relativePath;
}






public function getRelativePathname()
{
return $this->relativePathname;
}








public function getContents()
{
$level = error_reporting(0);
$content = file_get_contents($this->getPathname());
error_reporting($level);
if (false === $content) {
$error = error_get_last();
throw new \RuntimeException($error['message']);
}

return $content;
}
}
