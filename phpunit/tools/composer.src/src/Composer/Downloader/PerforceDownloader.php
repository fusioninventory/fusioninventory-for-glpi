<?php











namespace Composer\Downloader;

use Composer\Package\PackageInterface;
use Composer\Repository\VcsRepository;
use Composer\Util\Perforce;




class PerforceDownloader extends VcsDownloader
{
protected $perforce;
protected $perforceInjected = false;




public function doDownload(PackageInterface $package, $path)
{
$ref = $package->getSourceReference();
$label = $package->getPrettyVersion();

$this->io->write('    Cloning ' . $ref);
$this->initPerforce($package, $path);
$this->perforce->setStream($ref);
$this->perforce->p4Login($this->io);
$this->perforce->writeP4ClientSpec();
$this->perforce->connectClient();
$this->perforce->syncCodeBase($label);
$this->perforce->cleanupClientSpec();
}

public function initPerforce($package, $path)
{
if ($this->perforce) {
$this->perforce->initializePath($path);
return;
}

$repository = $package->getRepository();
$repoConfig = null;
if ($repository instanceof VcsRepository) {
$repoConfig = $this->getRepoConfig($repository);
}
$this->perforce = Perforce::create($repoConfig, $package->getSourceUrl(), $path);
}

private function getRepoConfig(VcsRepository $repository)
{
return $repository->getRepoConfig();
}




public function doUpdate(PackageInterface $initial, PackageInterface $target, $path)
{
$this->doDownload($target, $path);
}




public function getLocalChanges(PackageInterface $package, $path)
{
$this->io->write('Perforce driver does not check for local changes before overriding', true);

return;
}




protected function getCommitLogs($fromReference, $toReference, $path)
{
$commitLogs = $this->perforce->getCommitLogs($fromReference, $toReference);

return $commitLogs;
}

public function setPerforce($perforce)
{
$this->perforce = $perforce;
}
}
