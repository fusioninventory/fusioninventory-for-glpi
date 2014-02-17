<?php











namespace Composer\Repository\Vcs;

use Composer\Downloader\TransportException;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Util\ProcessExecutor;
use Composer\Util\RemoteFilesystem;






abstract class VcsDriver implements VcsDriverInterface
{
protected $url;
protected $originUrl;
protected $repoConfig;
protected $io;
protected $config;
protected $process;
protected $remoteFilesystem;










final public function __construct(array $repoConfig, IOInterface $io, Config $config, ProcessExecutor $process = null, RemoteFilesystem $remoteFilesystem = null)
{

if (self::isLocalUrl($repoConfig['url'])) {
$repoConfig['url'] = realpath(
preg_replace('/^file:\/\//', '', $repoConfig['url'])
);
}

$this->url = $repoConfig['url'];
$this->originUrl = $repoConfig['url'];
$this->repoConfig = $repoConfig;
$this->io = $io;
$this->config = $config;
$this->process = $process ?: new ProcessExecutor($io);
$this->remoteFilesystem = $remoteFilesystem ?: new RemoteFilesystem($io);
}




public function hasComposerFile($identifier)
{
try {
return (bool) $this->getComposerInformation($identifier);
} catch (TransportException $e) {
}

return false;
}








protected function getScheme()
{
if (extension_loaded('openssl')) {
return 'https';
}

return 'http';
}








protected function getContents($url)
{
return $this->remoteFilesystem->getContents($this->originUrl, $url, false);
}







protected static function isLocalUrl($url)
{
return (bool) preg_match('{^(file://|/|[a-z]:[\\\\/])}i', $url);
}




public function cleanup()
{
return;
}
}
