<?php











namespace Composer\Downloader;




class TransportException extends \Exception
{
protected $headers;

public function setHeaders($headers)
{
$this->headers = $headers;
}

public function getHeaders()
{
return $this->headers;
}
}
