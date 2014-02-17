<?php











namespace Composer\Repository\Vcs;

use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Util\ProcessExecutor;
use Composer\Util\Perforce;




class PerforceDriver extends VcsDriver
{
protected $depot;
protected $branch;
protected $perforce;
protected $composerInfo;
protected $composerInfoIdentifier;




public function initialize()
{
$this->depot = $this->repoConfig['depot'];
$this->branch = '';
if (isset($this->repoConfig['branch'])) {
$this->branch = $this->repoConfig['branch'];
}

$this->initPerforce($this->repoConfig);
$this->perforce->p4Login($this->io);
$this->perforce->checkStream($this->depot);

$this->perforce->writeP4ClientSpec();
$this->perforce->connectClient();

return true;
}

private function initPerforce($repoConfig)
{
if (isset($this->perforce)) {
return;
}

$repoDir = $this->config->get('cache-vcs-dir') . '/' . $this->depot;
$this->perforce = Perforce::create($repoConfig, $this->getUrl(), $repoDir, $this->process);
}




public function getComposerInformation($identifier)
{
if (isset($this->composerInfoIdentifier)) {
if (strcmp($identifier, $this->composerInfoIdentifier) === 0) {
return $this->composerInfo;
}
}
$composer_info = $this->perforce->getComposerInformation($identifier);

return $composer_info;
}




public function getRootIdentifier()
{
return $this->branch;
}




public function getBranches()
{
$branches = $this->perforce->getBranches();

return $branches;
}




public function getTags()
{
$tags = $this->perforce->getTags();

return $tags;
}




public function getDist($identifier)
{
return null;
}




public function getSource($identifier)
{
$source = array(
'type' => 'perforce',
'url' => $this->repoConfig['url'],
'reference' => $identifier,
'p4user' => $this->perforce->getUser()
);

return $source;
}




public function getUrl()
{
return $this->url;
}




public function hasComposerFile($identifier)
{
$this->composerInfo = $this->perforce->getComposerInformation('//' . $this->depot . '/' . $identifier);
$this->composerInfoIdentifier = $identifier;
$result = false;
if (isset($this->composerInfo)) {
$result = count($this->composerInfo) > 0;
}

return $result;
}




public function getContents($url)
{
return false;
}




public static function supports(IOInterface $io, Config $config, $url, $deep = false)
{
if ($deep || preg_match('#\b(perforce|p4)\b#i', $url)) {
return Perforce::checkServerExists($url, new ProcessExecutor($io));
}

return false;
}




public function cleanup()
{
$this->perforce->cleanupClientSpec();
$this->perforce = null;
}

public function getDepot()
{
return $this->depot;
}

public function getBranch()
{
return $this->branch;
}

public function setPerforce(Perforce $perforce)
{
$this->perforce = $perforce;
}
}
