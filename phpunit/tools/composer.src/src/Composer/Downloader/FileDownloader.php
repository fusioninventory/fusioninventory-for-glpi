<?php











namespace Composer\Downloader;

use Composer\Config;
use Composer\Cache;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Package\Version\VersionParser;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PreFileDownloadEvent;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Util\Filesystem;
use Composer\Util\GitHub;
use Composer\Util\RemoteFilesystem;









class FileDownloader implements DownloaderInterface
{
protected $io;
protected $config;
protected $rfs;
protected $filesystem;
protected $cache;
protected $outputProgress = true;











public function __construct(IOInterface $io, Config $config, EventDispatcher $eventDispatcher = null, Cache $cache = null, RemoteFilesystem $rfs = null, Filesystem $filesystem = null)
{
$this->io = $io;
$this->config = $config;
$this->eventDispatcher = $eventDispatcher;
$this->rfs = $rfs ?: new RemoteFilesystem($io);
$this->filesystem = $filesystem ?: new Filesystem();
$this->cache = $cache;


if ($this->cache && $this->cache->gcIsNecessary()) {
$this->cache->gc($config->get('cache-files-ttl'), $config->get('cache-files-maxsize'));
}
}




public function getInstallationSource()
{
return 'dist';
}




public function download(PackageInterface $package, $path)
{
$url = $package->getDistUrl();
if (!$url) {
throw new \InvalidArgumentException('The given package is missing url information');
}

$this->filesystem->removeDirectory($path);
$this->filesystem->ensureDirectoryExists($path);

$fileName = $this->getFileName($package, $path);

$this->io->write("  - Installing <info>" . $package->getName() . "</info> (<comment>" . VersionParser::formatVersion($package) . "</comment>)");

$processedUrl = $this->processUrl($package, $url);
$hostname = parse_url($processedUrl, PHP_URL_HOST);

$preFileDownloadEvent = new PreFileDownloadEvent(PluginEvents::PRE_FILE_DOWNLOAD, $this->rfs, $processedUrl);
if ($this->eventDispatcher) {
$this->eventDispatcher->dispatch($preFileDownloadEvent->getName(), $preFileDownloadEvent);
}
$rfs = $preFileDownloadEvent->getRemoteFilesystem();

if (strpos($hostname, '.github.com') === (strlen($hostname) - 11)) {
$hostname = 'github.com';
}

try {
$checksum = $package->getDistSha1Checksum();
$cacheKey = $this->getCacheKey($package);

try {

 if (!$this->cache || ($checksum && $checksum !== $this->cache->sha1($cacheKey)) || !$this->cache->copyTo($cacheKey, $fileName)) {
if (!$this->outputProgress) {
$this->io->write('    Downloading');
}


 $retries = 3;
while ($retries--) {
try {
$rfs->copy($hostname, $processedUrl, $fileName, $this->outputProgress);
break;
} catch (TransportException $e) {

 if ((0 !== $e->getCode() && !in_array($e->getCode(),array(500, 502, 503, 504))) || !$retries) {
throw $e;
}
if ($this->io->isVerbose()) {
$this->io->write('    Download failed, retrying...');
}
usleep(500000);
}
}

if ($this->cache) {
$this->cache->copyFrom($cacheKey, $fileName);
}
} else {
$this->io->write('    Loading from cache');
}
} catch (TransportException $e) {
if (!in_array($e->getCode(), array(404, 403, 412))) {
throw $e;
}
if ('github.com' === $hostname && !$this->io->hasAuthentication($hostname)) {
$message = "\n".'Could not fetch '.$processedUrl.', enter your GitHub credentials '.($e->getCode() === 404 ? 'to access private repos' : 'to go over the API rate limit');
$gitHubUtil = new GitHub($this->io, $this->config, null, $rfs);
if (!$gitHubUtil->authorizeOAuth($hostname)
&& (!$this->io->isInteractive() || !$gitHubUtil->authorizeOAuthInteractively($hostname, $message))
) {
throw $e;
}
$rfs->copy($hostname, $processedUrl, $fileName, $this->outputProgress);
} else {
throw $e;
}
}

if (!file_exists($fileName)) {
throw new \UnexpectedValueException($url.' could not be saved to '.$fileName.', make sure the'
.' directory is writable and you have internet connectivity');
}

if ($checksum && hash_file('sha1', $fileName) !== $checksum) {
throw new \UnexpectedValueException('The checksum verification of the file failed (downloaded from '.$url.')');
}
} catch (\Exception $e) {

 $this->filesystem->removeDirectory($path);
$this->clearCache($package, $path);
throw $e;
}

return $fileName;
}




public function setOutputProgress($outputProgress)
{
$this->outputProgress = $outputProgress;

return $this;
}

protected function clearCache(PackageInterface $package, $path)
{
if ($this->cache) {
$fileName = $this->getFileName($package, $path);
$this->cache->remove($this->getCacheKey($package));
}
}




public function update(PackageInterface $initial, PackageInterface $target, $path)
{
$this->remove($initial, $path);
$this->download($target, $path);
}




public function remove(PackageInterface $package, $path)
{
$this->io->write("  - Removing <info>" . $package->getName() . "</info> (<comment>" . VersionParser::formatVersion($package) . "</comment>)");
if (!$this->filesystem->removeDirectory($path)) {

 if (!defined('PHP_WINDOWS_VERSION_BUILD') || (usleep(250000) && !$this->filesystem->removeDirectory($path))) {
throw new \RuntimeException('Could not completely delete '.$path.', aborting.');
}
}
}








protected function getFileName(PackageInterface $package, $path)
{
return $path.'/'.pathinfo(parse_url($package->getDistUrl(), PHP_URL_PATH), PATHINFO_BASENAME);
}










protected function processUrl(PackageInterface $package, $url)
{
if (!extension_loaded('openssl') && 0 === strpos($url, 'https:')) {
throw new \RuntimeException('You must enable the openssl extension to download files via https');
}

return $url;
}

private function getCacheKey(PackageInterface $package)
{
if (preg_match('{^[a-f0-9]{40}$}', $package->getDistReference())) {
return $package->getName().'/'.$package->getDistReference().'.'.$package->getDistType();
}

return $package->getName().'/'.$package->getVersion().'-'.$package->getDistReference().'.'.$package->getDistType();
}
}
