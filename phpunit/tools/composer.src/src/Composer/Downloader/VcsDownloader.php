<?php











namespace Composer\Downloader;

use Composer\Config;
use Composer\Package\PackageInterface;
use Composer\Package\Version\VersionParser;
use Composer\Util\ProcessExecutor;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;




abstract class VcsDownloader implements DownloaderInterface, ChangeReportInterface
{
protected $io;
protected $config;
protected $process;
protected $filesystem;

public function __construct(IOInterface $io, Config $config, ProcessExecutor $process = null, Filesystem $fs = null)
{
$this->io = $io;
$this->config = $config;
$this->process = $process ?: new ProcessExecutor($io);
$this->filesystem = $fs ?: new Filesystem;
}




public function getInstallationSource()
{
return 'source';
}




public function download(PackageInterface $package, $path)
{
if (!$package->getSourceReference()) {
throw new \InvalidArgumentException('Package '.$package->getPrettyName().' is missing reference information');
}

$this->io->write("  - Installing <info>" . $package->getName() . "</info> (<comment>" . VersionParser::formatVersion($package) . "</comment>)");
$this->filesystem->removeDirectory($path);
$this->doDownload($package, $path);
$this->io->write('');
}




public function update(PackageInterface $initial, PackageInterface $target, $path)
{
if (!$target->getSourceReference()) {
throw new \InvalidArgumentException('Package '.$target->getPrettyName().' is missing reference information');
}

$name = $target->getName();
if ($initial->getPrettyVersion() == $target->getPrettyVersion()) {
if ($target->getSourceType() === 'svn') {
$from = $initial->getSourceReference();
$to = $target->getSourceReference();
} else {
$from = substr($initial->getSourceReference(), 0, 7);
$to = substr($target->getSourceReference(), 0, 7);
}
$name .= ' '.$initial->getPrettyVersion();
} else {
$from = VersionParser::formatVersion($initial);
$to = VersionParser::formatVersion($target);
}

$this->io->write("  - Updating <info>" . $name . "</info> (<comment>" . $from . "</comment> => <comment>" . $to . "</comment>)");

$this->cleanChanges($initial, $path, true);
try {
$this->doUpdate($initial, $target, $path);
} catch (\Exception $e) {

 $this->reapplyChanges($path);

throw $e;
}
$this->reapplyChanges($path);


 if ($this->io->isVerbose()) {
$message = 'Pulling in changes:';
$logs = $this->getCommitLogs($initial->getSourceReference(), $target->getSourceReference(), $path);

if (!trim($logs)) {
$message = 'Rolling back changes:';
$logs = $this->getCommitLogs($target->getSourceReference(), $initial->getSourceReference(), $path);
}

if (trim($logs)) {
$logs = implode("\n", array_map(function ($line) {
return '      ' . $line;
}, explode("\n", $logs)));

$this->io->write('    '.$message);
$this->io->write($logs);
}
}

$this->io->write('');
}




public function remove(PackageInterface $package, $path)
{
$this->io->write("  - Removing <info>" . $package->getName() . "</info> (<comment>" . $package->getPrettyVersion() . "</comment>)");
$this->cleanChanges($package, $path, false);
if (!$this->filesystem->removeDirectory($path)) {

 if (!defined('PHP_WINDOWS_VERSION_BUILD') || (usleep(250) && !$this->filesystem->removeDirectory($path))) {
throw new \RuntimeException('Could not completely delete '.$path.', aborting.');
}
}
}





public function setOutputProgress($outputProgress)
{
return $this;
}










protected function cleanChanges(PackageInterface $package, $path, $update)
{

 if (null !== $this->getLocalChanges($package, $path)) {
throw new \RuntimeException('Source directory ' . $path . ' has uncommitted changes.');
}
}







protected function reapplyChanges($path)
{
}







abstract protected function doDownload(PackageInterface $package, $path);








abstract protected function doUpdate(PackageInterface $initial, PackageInterface $target, $path);









abstract protected function getCommitLogs($fromReference, $toReference, $path);
}
