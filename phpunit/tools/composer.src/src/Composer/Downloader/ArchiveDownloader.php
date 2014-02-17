<?php











namespace Composer\Downloader;

use Composer\Package\PackageInterface;








abstract class ArchiveDownloader extends FileDownloader
{



public function download(PackageInterface $package, $path)
{
$temporaryDir = $this->config->get('vendor-dir').'/composer/'.substr(md5(uniqid('', true)), 0, 8);
$retries = 3;
while ($retries--) {
$fileName = parent::download($package, $path);

if ($this->io->isVerbose()) {
$this->io->write('    Extracting archive');
}

try {
$this->filesystem->ensureDirectoryExists($temporaryDir);
try {
$this->extract($fileName, $temporaryDir);
} catch (\Exception $e) {

 parent::clearCache($package, $path);
throw $e;
}

unlink($fileName);


 $contentDir = $this->listFiles($temporaryDir);


 if (1 === count($contentDir) && !is_file($contentDir[0])) {
$contentDir = $this->listFiles($contentDir[0]);
}


 foreach ($contentDir as $file) {
$this->filesystem->rename($file, $path . '/' . basename($file));
}

$this->filesystem->removeDirectory($temporaryDir);
} catch (\Exception $e) {

 $this->filesystem->removeDirectory($path);
$this->filesystem->removeDirectory($temporaryDir);


 if ($retries && $e instanceof \UnexpectedValueException && class_exists('ZipArchive') && $e->getCode() === \ZipArchive::ER_NOZIP) {
$this->io->write('    Invalid zip file, retrying...');
usleep(500000);
continue;
}

throw $e;
}

break;
}

$this->io->write('');
}




protected function getFileName(PackageInterface $package, $path)
{
return rtrim($path.'/'.md5($path.spl_object_hash($package)).'.'.pathinfo(parse_url($package->getDistUrl(), PHP_URL_PATH), PATHINFO_EXTENSION), '.');
}




protected function processUrl(PackageInterface $package, $url)
{
if ($package->getDistReference() && strpos($url, 'github.com')) {
if (preg_match('{^https?://(?:www\.)?github\.com/([^/]+)/([^/]+)/(zip|tar)ball/(.+)$}i', $url, $match)) {

 $url = 'https://api.github.com/repos/' . $match[1] . '/'. $match[2] . '/' . $match[3] . 'ball/' . $package->getDistReference();
} elseif ($package->getDistReference() && preg_match('{^https?://(?:www\.)?github\.com/([^/]+)/([^/]+)/archive/.+\.(zip|tar)(?:\.gz)?$}i', $url, $match)) {

 $url = 'https://api.github.com/repos/' . $match[1] . '/'. $match[2] . '/' . $match[3] . 'ball/' . $package->getDistReference();
} elseif ($package->getDistReference() && preg_match('{^https?://api\.github\.com/repos/([^/]+)/([^/]+)/(zip|tar)ball(?:/.+)?$}i', $url, $match)) {

 $url = 'https://api.github.com/repos/' . $match[1] . '/'. $match[2] . '/' . $match[3] . 'ball/' . $package->getDistReference();
}
}

if (!extension_loaded('openssl') && (0 === strpos($url, 'https:') || 0 === strpos($url, 'http://github.com'))) {
throw new \RuntimeException('You must enable the openssl extension to download files via https');
}

return parent::processUrl($package, $url);
}









abstract protected function extract($file, $path);




private function listFiles($dir)
{
$files = array_merge(glob($dir . '/.*') ?: array(), glob($dir . '/*') ?: array());

return array_values(array_filter($files, function ($el) {
return basename($el) !== '.' && basename($el) !== '..';
}));
}
}
