<?php











namespace Composer\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Package\PackageInterface;
use Composer\Util\Filesystem;







class LibraryInstaller implements InstallerInterface
{
protected $composer;
protected $vendorDir;
protected $binDir;
protected $downloadManager;
protected $io;
protected $type;
protected $filesystem;









public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null)
{
$this->composer = $composer;
$this->downloadManager = $composer->getDownloadManager();
$this->io = $io;
$this->type = $type;

$this->filesystem = $filesystem ?: new Filesystem();
$this->vendorDir = rtrim($composer->getConfig()->get('vendor-dir'), '/');
$this->binDir = rtrim($composer->getConfig()->get('bin-dir'), '/');
}




public function supports($packageType)
{
return $packageType === $this->type || null === $this->type;
}




public function isInstalled(InstalledRepositoryInterface $repo, PackageInterface $package)
{
return $repo->hasPackage($package) && is_readable($this->getInstallPath($package));
}




public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
{
$this->initializeVendorDir();
$downloadPath = $this->getInstallPath($package);


 if (!is_readable($downloadPath) && $repo->hasPackage($package)) {
$this->removeBinaries($package);
}

$this->installCode($package);
$this->installBinaries($package);
if (!$repo->hasPackage($package)) {
$repo->addPackage(clone $package);
}
}




public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
{
if (!$repo->hasPackage($initial)) {
throw new \InvalidArgumentException('Package is not installed: '.$initial);
}

$this->initializeVendorDir();

$this->removeBinaries($initial);
$this->updateCode($initial, $target);
$this->installBinaries($target);
$repo->removePackage($initial);
if (!$repo->hasPackage($target)) {
$repo->addPackage(clone $target);
}
}




public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
{
if (!$repo->hasPackage($package)) {
throw new \InvalidArgumentException('Package is not installed: '.$package);
}

$this->removeCode($package);
$this->removeBinaries($package);
$repo->removePackage($package);

$downloadPath = $this->getPackageBasePath($package);
if (strpos($package->getName(), '/')) {
$packageVendorDir = dirname($downloadPath);
if (is_dir($packageVendorDir) && !glob($packageVendorDir.'/*')) {
@rmdir($packageVendorDir);
}
}
}




public function getInstallPath(PackageInterface $package)
{
$targetDir = $package->getTargetDir();

return $this->getPackageBasePath($package) . ($targetDir ? '/'.$targetDir : '');
}

protected function getPackageBasePath(PackageInterface $package)
{
$this->initializeVendorDir();

return ($this->vendorDir ? $this->vendorDir.'/' : '') . $package->getPrettyName();
}

protected function installCode(PackageInterface $package)
{
$downloadPath = $this->getInstallPath($package);
$this->downloadManager->download($package, $downloadPath);
}

protected function updateCode(PackageInterface $initial, PackageInterface $target)
{
$initialDownloadPath = $this->getInstallPath($initial);
$targetDownloadPath = $this->getInstallPath($target);
if ($targetDownloadPath !== $initialDownloadPath) {

 
 if (substr($initialDownloadPath, 0, strlen($targetDownloadPath)) === $targetDownloadPath
|| substr($targetDownloadPath, 0, strlen($initialDownloadPath)) === $initialDownloadPath
) {
$this->removeCode($initial);
$this->installCode($target);

return;
}

$this->filesystem->rename($initialDownloadPath, $targetDownloadPath);
}
$this->downloadManager->update($initial, $target, $targetDownloadPath);
}

protected function removeCode(PackageInterface $package)
{
$downloadPath = $this->getPackageBasePath($package);
$this->downloadManager->remove($package, $downloadPath);
}

protected function getBinaries(PackageInterface $package)
{
return $package->getBinaries();
}

protected function installBinaries(PackageInterface $package)
{
$binaries = $this->getBinaries($package);
if (!$binaries) {
return;
}
foreach ($binaries as $bin) {
$binPath = $this->getInstallPath($package).'/'.$bin;
if (!file_exists($binPath)) {
$this->io->write('    <warning>Skipped installation of '.$bin.' for package '.$package->getName().': file not found in package</warning>');
continue;
}


 
 
 
 $binPath = realpath($binPath);

$this->initializeBinDir();
$link = $this->binDir.'/'.basename($bin);
if (file_exists($link)) {
if (is_link($link)) {

 
 
 @chmod($link, 0777 & ~umask());
}
$this->io->write('    Skipped installation of '.$bin.' for package '.$package->getName().': name conflicts with an existing file');
continue;
}
if (defined('PHP_WINDOWS_VERSION_BUILD')) {

 if ('.bat' !== substr($binPath, -4)) {
file_put_contents($link, $this->generateUnixyProxyCode($binPath, $link));
@chmod($link, 0777 & ~umask());
$link .= '.bat';
if (file_exists($link)) {
$this->io->write('    Skipped installation of '.$bin.'.bat proxy for package '.$package->getName().': a .bat proxy was already installed');
}
}
if (!file_exists($link)) {
file_put_contents($link, $this->generateWindowsProxyCode($binPath, $link));
}
} else {
$cwd = getcwd();
try {

 
 $relativeBin = $this->filesystem->findShortestPath($link, $binPath);
chdir(dirname($link));
if (false === symlink($relativeBin, $link)) {
throw new \ErrorException();
}
} catch (\ErrorException $e) {
file_put_contents($link, $this->generateUnixyProxyCode($binPath, $link));
}
chdir($cwd);
}
@chmod($link, 0777 & ~umask());
}
}

protected function removeBinaries(PackageInterface $package)
{
$binaries = $this->getBinaries($package);
if (!$binaries) {
return;
}
foreach ($binaries as $bin) {
$link = $this->binDir.'/'.basename($bin);
if (is_link($link) || file_exists($link)) {
unlink($link);
}
if (file_exists($link.'.bat')) {
unlink($link.'.bat');
}
}
}

protected function initializeVendorDir()
{
$this->filesystem->ensureDirectoryExists($this->vendorDir);
$this->vendorDir = realpath($this->vendorDir);
}

protected function initializeBinDir()
{
$this->filesystem->ensureDirectoryExists($this->binDir);
$this->binDir = realpath($this->binDir);
}

protected function generateWindowsProxyCode($bin, $link)
{
$binPath = $this->filesystem->findShortestPath($link, $bin);
if ('.bat' === substr($bin, -4) || '.exe' === substr($bin, -4)) {
$caller = 'call';
} else {
$handle = fopen($bin, 'r');
$line = fgets($handle);
fclose($handle);
if (preg_match('{^#!/(?:usr/bin/env )?(?:[^/]+/)*(.+)$}m', $line, $match)) {
$caller = trim($match[1]);
} else {
$caller = 'php';
}
}

return "@ECHO OFF\r\n".
"SET BIN_TARGET=%~dp0/".trim(escapeshellarg($binPath), '"')."\r\n".
"{$caller} \"%BIN_TARGET%\" %*\r\n";
}

protected function generateUnixyProxyCode($bin, $link)
{
$binPath = $this->filesystem->findShortestPath($link, $bin);

return "#!/usr/bin/env sh\n".
'SRC_DIR="`pwd`"'."\n".
'cd "`dirname "$0"`"'."\n".
'cd '.escapeshellarg(dirname($binPath))."\n".
'BIN_TARGET="`pwd`/'.basename($binPath)."\"\n".
'cd "$SRC_DIR"'."\n".
'"$BIN_TARGET" "$@"'."\n";
}
}
