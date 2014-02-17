<?php











namespace Composer\Repository\Vcs;

use Composer\Config;
use Composer\Json\JsonFile;
use Composer\Util\ProcessExecutor;
use Composer\Util\Filesystem;
use Composer\IO\IOInterface;




class HgDriver extends VcsDriver
{
protected $tags;
protected $branches;
protected $rootIdentifier;
protected $repoDir;
protected $infoCache = array();




public function initialize()
{
if (static::isLocalUrl($this->url)) {
$this->repoDir = str_replace('file://', '', $this->url);
} else {
$cacheDir = $this->config->get('cache-vcs-dir');
$this->repoDir = $cacheDir . '/' . preg_replace('{[^a-z0-9]}i', '-', $this->url) . '/';

$fs = new Filesystem();
$fs->ensureDirectoryExists($cacheDir);

if (!is_writable(dirname($this->repoDir))) {
throw new \RuntimeException('Can not clone '.$this->url.' to access package information. The "'.$cacheDir.'" directory is not writable by the current user.');
}


 if (is_dir($this->repoDir) && 0 === $this->process->execute('hg summary', $output, $this->repoDir)) {
if (0 !== $this->process->execute('hg pull', $output, $this->repoDir)) {
$this->io->write('<error>Failed to update '.$this->url.', package information from this repository may be outdated ('.$this->process->getErrorOutput().')</error>');
}
} else {

 $fs->removeDirectory($this->repoDir);

if (0 !== $this->process->execute(sprintf('hg clone --noupdate %s %s', escapeshellarg($this->url), escapeshellarg($this->repoDir)), $output, $cacheDir)) {
$output = $this->process->getErrorOutput();

if (0 !== $this->process->execute('hg --version', $ignoredOutput)) {
throw new \RuntimeException('Failed to clone '.$this->url.', hg was not found, check that it is installed and in your PATH env.' . "\n\n" . $this->process->getErrorOutput());
}

throw new \RuntimeException('Failed to clone '.$this->url.', could not read packages from it' . "\n\n" .$output);
}
}
}

$this->getTags();
$this->getBranches();
}




public function getRootIdentifier()
{
if (null === $this->rootIdentifier) {
$this->process->execute(sprintf('hg tip --template "{node}"'), $output, $this->repoDir);
$output = $this->process->splitLines($output);
$this->rootIdentifier = $output[0];
}

return $this->rootIdentifier;
}




public function getUrl()
{
return $this->url;
}




public function getSource($identifier)
{
return array('type' => 'hg', 'url' => $this->getUrl(), 'reference' => $identifier);
}




public function getDist($identifier)
{
return null;
}




public function getComposerInformation($identifier)
{
if (!isset($this->infoCache[$identifier])) {
$this->process->execute(sprintf('hg cat -r %s composer.json', escapeshellarg($identifier)), $composer, $this->repoDir);

if (!trim($composer)) {
return;
}

$composer = JsonFile::parseJson($composer, $identifier);

if (!isset($composer['time'])) {
$this->process->execute(sprintf('hg log --template "{date|rfc3339date}" -r %s', escapeshellarg($identifier)), $output, $this->repoDir);
$date = new \DateTime(trim($output), new \DateTimeZone('UTC'));
$composer['time'] = $date->format('Y-m-d H:i:s');
}
$this->infoCache[$identifier] = $composer;
}

return $this->infoCache[$identifier];
}




public function getTags()
{
if (null === $this->tags) {
$tags = array();

$this->process->execute('hg tags', $output, $this->repoDir);
foreach ($this->process->splitLines($output) as $tag) {
if ($tag && preg_match('(^([^\s]+)\s+\d+:(.*)$)', $tag, $match)) {
$tags[$match[1]] = $match[2];
}
}
unset($tags['tip']);

$this->tags = $tags;
}

return $this->tags;
}




public function getBranches()
{
if (null === $this->branches) {
$branches = array();
$bookmarks = array();

$this->process->execute('hg branches', $output, $this->repoDir);
foreach ($this->process->splitLines($output) as $branch) {
if ($branch && preg_match('(^([^\s]+)\s+\d+:([a-f0-9]+))', $branch, $match)) {
$branches[$match[1]] = $match[2];
}
}

$this->process->execute('hg bookmarks', $output, $this->repoDir);
foreach ($this->process->splitLines($output) as $branch) {
if ($branch && preg_match('(^(?:[\s*]*)([^\s]+)\s+\d+:(.*)$)', $branch, $match)) {
$bookmarks[$match[1]] = $match[2];
}
}


 $this->branches = array_merge($bookmarks, $branches);
}

return $this->branches;
}




public static function supports(IOInterface $io, Config $config, $url, $deep = false)
{
if (preg_match('#(^(?:https?|ssh)://(?:[^@]@)?bitbucket.org|https://(?:.*?)\.kilnhg.com)#i', $url)) {
return true;
}


 if (static::isLocalUrl($url)) {
if (!is_dir($url)) {
throw new \RuntimeException('Directory does not exist: '.$url);
}

$process = new ProcessExecutor();
$url = str_replace('file://', '', $url);

 if ($process->execute('hg summary', $output, $url) === 0) {
return true;
}
}

if (!$deep) {
return false;
}

$processExecutor = new ProcessExecutor();
$exit = $processExecutor->execute(sprintf('hg identify %s', escapeshellarg($url)), $ignored);

return $exit === 0;
}
}
