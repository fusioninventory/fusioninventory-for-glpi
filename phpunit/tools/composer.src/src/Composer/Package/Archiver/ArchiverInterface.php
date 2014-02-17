<?php











namespace Composer\Package\Archiver;






interface ArchiverInterface
{










public function archive($sources, $target, $format, array $excludes = array());









public function supports($format, $sourceType);
}
