<?php











namespace Composer\Downloader;

use Composer\Package\PackageInterface;
use Composer\Downloader\DownloaderInterface;
use Composer\Util\Filesystem;






class DownloadManager
{
private $preferDist = false;
private $preferSource = false;
private $filesystem;
private $downloaders = array();







public function __construct($preferSource = false, Filesystem $filesystem = null)
{
$this->preferSource = $preferSource;
$this->filesystem = $filesystem ?: new Filesystem();
}







public function setPreferSource($preferSource)
{
$this->preferSource = $preferSource;

return $this;
}







public function setPreferDist($preferDist)
{
$this->preferDist = $preferDist;

return $this;
}








public function setOutputProgress($outputProgress)
{
foreach ($this->downloaders as $downloader) {
$downloader->setOutputProgress($outputProgress);
}

return $this;
}








public function setDownloader($type, DownloaderInterface $downloader)
{
$type = strtolower($type);
$this->downloaders[$type] = $downloader;

return $this;
}









public function getDownloader($type)
{
$type = strtolower($type);
if (!isset($this->downloaders[$type])) {
throw new \InvalidArgumentException(sprintf('Unknown downloader type: %s. Available types: %s.', $type, implode(', ', array_keys($this->downloaders))));
}

return $this->downloaders[$type];
}











public function getDownloaderForInstalledPackage(PackageInterface $package)
{
$installationSource = $package->getInstallationSource();

if ('metapackage' === $package->getType()) {
return;
}

if ('dist' === $installationSource) {
$downloader = $this->getDownloader($package->getDistType());
} elseif ('source' === $installationSource) {
$downloader = $this->getDownloader($package->getSourceType());
} else {
throw new \InvalidArgumentException(
'Package '.$package.' seems not been installed properly'
);
}

if ($installationSource !== $downloader->getInstallationSource()) {
throw new \LogicException(sprintf(
'Downloader "%s" is a %s type downloader and can not be used to download %s',
get_class($downloader), $downloader->getInstallationSource(), $installationSource
));
}

return $downloader;
}










public function download(PackageInterface $package, $targetDir, $preferSource = null)
{
$preferSource = null !== $preferSource ? $preferSource : $this->preferSource;
$sourceType = $package->getSourceType();
$distType = $package->getDistType();

if ((!$package->isDev() || $this->preferDist || !$sourceType) && !($preferSource && $sourceType) && $distType) {
$package->setInstallationSource('dist');
} elseif ($sourceType) {
$package->setInstallationSource('source');
} else {
throw new \InvalidArgumentException('Package '.$package.' must have a source or dist specified');
}

$this->filesystem->ensureDirectoryExists($targetDir);

$downloader = $this->getDownloaderForInstalledPackage($package);
if ($downloader) {
$downloader->download($package, $targetDir);
}
}










public function update(PackageInterface $initial, PackageInterface $target, $targetDir)
{
$downloader = $this->getDownloaderForInstalledPackage($initial);
if (!$downloader) {
return;
}

$installationSource = $initial->getInstallationSource();

if ('dist' === $installationSource) {
$initialType = $initial->getDistType();
$targetType = $target->getDistType();
} else {
$initialType = $initial->getSourceType();
$targetType = $target->getSourceType();
}


 if ($target->isDev() && 'dist' === $installationSource) {
$downloader->remove($initial, $targetDir);
$this->download($target, $targetDir);

return;
}

if ($initialType === $targetType) {
$target->setInstallationSource($installationSource);
$downloader->update($initial, $target, $targetDir);
} else {
$downloader->remove($initial, $targetDir);
$this->download($target, $targetDir, 'source' === $installationSource);
}
}







public function remove(PackageInterface $package, $targetDir)
{
$downloader = $this->getDownloaderForInstalledPackage($package);
if ($downloader) {
$downloader->remove($package, $targetDir);
}
}
}
