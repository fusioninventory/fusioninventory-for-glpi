<?php











namespace Composer\Downloader;






class FilesystemException extends \Exception
{
public function __construct($message = null, $code = null, \Exception $previous = null)
{
parent::__construct("Filesystem exception: \n".$message, $code, $previous);
}
}
