<?php











namespace Composer\Package\Archiver;






class PharArchiver implements ArchiverInterface
{
protected static $formats = array(
'zip' => \Phar::ZIP,
'tar' => \Phar::TAR,
);




public function archive($sources, $target, $format, array $excludes = array())
{
$sources = realpath($sources);


 if (file_exists($target)) {
unlink($target);
}

try {
$phar = new \PharData($target, null, null, static::$formats[$format]);
$files = new ArchivableFilesFinder($sources, $excludes);
$phar->buildFromIterator($files, $sources);

return $target;
} catch (\UnexpectedValueException $e) {
$message = sprintf("Could not create archive '%s' from '%s': %s",
$target,
$sources,
$e->getMessage()
);

throw new \RuntimeException($message, $e->getCode(), $e);
}
}




public function supports($format, $sourceType)
{
return isset(static::$formats[$format]);
}
}
