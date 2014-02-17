<?php











namespace Composer\Util;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;





class Filesystem
{
private $processExecutor;

public function __construct(ProcessExecutor $executor = null)
{
$this->processExecutor = $executor ?: new ProcessExecutor();
}

public function remove($file)
{
if (is_dir($file)) {
return $this->removeDirectory($file);
}

if (file_exists($file)) {
return unlink($file);
}

return false;
}







public function isDirEmpty($dir)
{
$dir = rtrim($dir, '/\\');

return count(glob($dir.'/*') ?: array()) === 0 && count(glob($dir.'/.*') ?: array()) === 2;
}










public function removeDirectory($directory)
{
if (!is_dir($directory)) {
return true;
}

if (preg_match('{^(?:[a-z]:)?[/\\\\]+$}i', $directory)) {
throw new \RuntimeException('Aborting an attempted deletion of '.$directory.', this was probably not intended, if it is a real use case please report it.');
}

if (!function_exists('proc_open')) {
return $this->removeDirectoryPhp($directory);
}

if (defined('PHP_WINDOWS_VERSION_BUILD')) {
$cmd = sprintf('rmdir /S /Q %s', escapeshellarg(realpath($directory)));
} else {
$cmd = sprintf('rm -rf %s', escapeshellarg($directory));
}

$result = $this->getProcess()->execute($cmd, $output) === 0;


 clearstatcache();

return $result && !is_dir($directory);
}











public function removeDirectoryPhp($directory)
{
$it = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
$ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

foreach ($ri as $file) {
if ($file->isDir()) {
rmdir($file->getPathname());
} else {
unlink($file->getPathname());
}
}

return rmdir($directory);
}

public function ensureDirectoryExists($directory)
{
if (!is_dir($directory)) {
if (file_exists($directory)) {
throw new \RuntimeException(
$directory.' exists and is not a directory.'
);
}
if (!@mkdir($directory, 0777, true)) {
throw new \RuntimeException(
$directory.' does not exist and could not be created.'
);
}
}
}










public function copyThenRemove($source, $target)
{
$it = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
$ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);
$this->ensureDirectoryExists($target);

foreach ($ri as $file) {
$targetPath = $target . DIRECTORY_SEPARATOR . $ri->getSubPathName();
if ($file->isDir()) {
$this->ensureDirectoryExists($targetPath);
} else {
copy($file->getPathname(), $targetPath);
}
}

$this->removeDirectoryPhp($source);
}

public function rename($source, $target)
{
if (true === @rename($source, $target)) {
return;
}

if (!function_exists('proc_open')) {
return $this->copyThenRemove($source, $target);
}

if (defined('PHP_WINDOWS_VERSION_BUILD')) {

 $command = sprintf('xcopy %s %s /E /I /Q', escapeshellarg($source), escapeshellarg($target));
$result = $this->processExecutor->execute($command, $output);


 clearstatcache();

if (0 === $result) {
$this->remove($source);

return;
}
} else {

 
 $command = sprintf('mv %s %s', escapeshellarg($source), escapeshellarg($target));
$result = $this->processExecutor->execute($command, $output);


 clearstatcache();

if (0 === $result) {
return;
}
}

return $this->copyThenRemove($source, $target);
}










public function findShortestPath($from, $to, $directories = false)
{
if (!$this->isAbsolutePath($from) || !$this->isAbsolutePath($to)) {
throw new \InvalidArgumentException(sprintf('$from (%s) and $to (%s) must be absolute paths.', $from, $to));
}

$from = lcfirst($this->normalizePath($from));
$to = lcfirst($this->normalizePath($to));

if ($directories) {
$from .= '/dummy_file';
}

if (dirname($from) === dirname($to)) {
return './'.basename($to);
}

$commonPath = $to;
while (strpos($from.'/', $commonPath.'/') !== 0 && '/' !== $commonPath && !preg_match('{^[a-z]:/?$}i', $commonPath)) {
$commonPath = strtr(dirname($commonPath), '\\', '/');
}

if (0 !== strpos($from, $commonPath) || '/' === $commonPath) {
return $to;
}

$commonPath = rtrim($commonPath, '/') . '/';
$sourcePathDepth = substr_count(substr($from, strlen($commonPath)), '/');
$commonPathCode = str_repeat('../', $sourcePathDepth);

return ($commonPathCode . substr($to, strlen($commonPath))) ?: './';
}










public function findShortestPathCode($from, $to, $directories = false)
{
if (!$this->isAbsolutePath($from) || !$this->isAbsolutePath($to)) {
throw new \InvalidArgumentException(sprintf('$from (%s) and $to (%s) must be absolute paths.', $from, $to));
}

$from = lcfirst($this->normalizePath($from));
$to = lcfirst($this->normalizePath($to));

if ($from === $to) {
return $directories ? '__DIR__' : '__FILE__';
}

$commonPath = $to;
while (strpos($from.'/', $commonPath.'/') !== 0 && '/' !== $commonPath && !preg_match('{^[a-z]:/?$}i', $commonPath) && '.' !== $commonPath) {
$commonPath = strtr(dirname($commonPath), '\\', '/');
}

if (0 !== strpos($from, $commonPath) || '/' === $commonPath || '.' === $commonPath) {
return var_export($to, true);
}

$commonPath = rtrim($commonPath, '/') . '/';
if (strpos($to, $from.'/') === 0) {
return '__DIR__ . '.var_export(substr($to, strlen($from)), true);
}
$sourcePathDepth = substr_count(substr($from, strlen($commonPath)), '/') + $directories;
$commonPathCode = str_repeat('dirname(', $sourcePathDepth).'__DIR__'.str_repeat(')', $sourcePathDepth);
$relTarget = substr($to, strlen($commonPath));

return $commonPathCode . (strlen($relTarget) ? '.' . var_export('/' . $relTarget, true) : '');
}







public function isAbsolutePath($path)
{
return substr($path, 0, 1) === '/' || substr($path, 1, 1) === ':';
}









public function size($path)
{
if (!file_exists($path)) {
throw new \RuntimeException("$path does not exist.");
}
if (is_dir($path)) {
return $this->directorySize($path);
}

return filesize($path);
}








public function normalizePath($path)
{
$parts = array();
$path = strtr($path, '\\', '/');
$prefix = '';
$absolute = false;

if (preg_match('{^([0-9a-z]+:(?://(?:[a-z]:)?)?)}i', $path, $match)) {
$prefix = $match[1];
$path = substr($path, strlen($prefix));
}

if (substr($path, 0, 1) === '/') {
$absolute = true;
$path = substr($path, 1);
}

$up = false;
foreach (explode('/', $path) as $chunk) {
if ('..' === $chunk && ($absolute || $up)) {
array_pop($parts);
$up = !(empty($parts) || '..' === end($parts));
} elseif ('.' !== $chunk && '' !== $chunk) {
$parts[] = $chunk;
$up = '..' !== $chunk;
}
}

return $prefix.($absolute ? '/' : '').implode('/', $parts);
}

protected function directorySize($directory)
{
$it = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
$ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

$size = 0;
foreach ($ri as $file) {
if ($file->isFile()) {
$size += $file->getSize();
}
}

return $size;
}

protected function getProcess()
{
return new ProcessExecutor;
}
}
