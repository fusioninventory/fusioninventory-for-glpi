<?php











namespace Composer\Downloader;

use Composer\Package\PackageInterface;




class HgDownloader extends VcsDownloader
{



public function doDownload(PackageInterface $package, $path)
{
$url = escapeshellarg($package->getSourceUrl());
$ref = escapeshellarg($package->getSourceReference());
$this->io->write("    Cloning ".$package->getSourceReference());
$command = sprintf('hg clone %s %s', $url, escapeshellarg($path));
if (0 !== $this->process->execute($command, $ignoredOutput)) {
throw new \RuntimeException('Failed to execute ' . $command . "\n\n" . $this->process->getErrorOutput());
}
$command = sprintf('hg up %s', $ref);
if (0 !== $this->process->execute($command, $ignoredOutput, realpath($path))) {
throw new \RuntimeException('Failed to execute ' . $command . "\n\n" . $this->process->getErrorOutput());
}
}




public function doUpdate(PackageInterface $initial, PackageInterface $target, $path)
{
$url = escapeshellarg($target->getSourceUrl());
$ref = escapeshellarg($target->getSourceReference());
$this->io->write("    Updating to ".$target->getSourceReference());

if (!is_dir($path.'/.hg')) {
throw new \RuntimeException('The .hg directory is missing from '.$path.', see http://getcomposer.org/commit-deps for more information');
}

$command = sprintf('hg pull %s && hg up %s', $url, $ref);
if (0 !== $this->process->execute($command, $ignoredOutput, realpath($path))) {
throw new \RuntimeException('Failed to execute ' . $command . "\n\n" . $this->process->getErrorOutput());
}
}




public function getLocalChanges(PackageInterface $package, $path)
{
if (!is_dir($path.'/.hg')) {
return;
}

$this->process->execute('hg st', $output, realpath($path));

return trim($output) ?: null;
}




protected function getCommitLogs($fromReference, $toReference, $path)
{
$command = sprintf('hg log -r %s:%s --style compact', $fromReference, $toReference);

if (0 !== $this->process->execute($command, $output, realpath($path))) {
throw new \RuntimeException('Failed to execute ' . $command . "\n\n" . $this->process->getErrorOutput());
}

return $output;
}
}
