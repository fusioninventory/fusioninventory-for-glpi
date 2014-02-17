<?php











namespace Composer;

use Composer\Config\ConfigSourceInterface;




class Config
{
public static $defaultConfig = array(
'process-timeout' => 300,
'use-include-path' => false,
'preferred-install' => 'auto',
'notify-on-install' => true,
'github-protocols' => array('git', 'https', 'ssh'),
'vendor-dir' => 'vendor',
'bin-dir' => '{$vendor-dir}/bin',
'cache-dir' => '{$home}/cache',
'cache-files-dir' => '{$cache-dir}/files',
'cache-repo-dir' => '{$cache-dir}/repo',
'cache-vcs-dir' => '{$cache-dir}/vcs',
'cache-ttl' => 15552000, 
 'cache-files-ttl' => null, 
 'cache-files-maxsize' => '300MiB',
'discard-changes' => false,
'autoloader-suffix' => null,
'optimize-autoloader' => false,
'prepend-autoloader' => true,
'github-domains' => array('github.com'),
);

public static $defaultRepositories = array(
'packagist' => array(
'type' => 'composer',
'url' => 'https?://packagist.org',
'allow_ssl_downgrade' => true,
)
);

private $config;
private $repositories;
private $configSource;

public function __construct()
{

 $this->config = static::$defaultConfig;
$this->repositories = static::$defaultRepositories;
}

public function setConfigSource(ConfigSourceInterface $source)
{
$this->configSource = $source;
}

public function getConfigSource()
{
return $this->configSource;
}






public function merge(array $config)
{

 if (!empty($config['config']) && is_array($config['config'])) {
foreach ($config['config'] as $key => $val) {
if (in_array($key, array('github-oauth')) && isset($this->config[$key])) {
$this->config[$key] = array_merge($this->config[$key], $val);
} else {
$this->config[$key] = $val;
}
}
}

if (!empty($config['repositories']) && is_array($config['repositories'])) {
$this->repositories = array_reverse($this->repositories, true);
$newRepos = array_reverse($config['repositories'], true);
foreach ($newRepos as $name => $repository) {

 if (false === $repository) {
unset($this->repositories[$name]);
continue;
}


 if (1 === count($repository) && false === current($repository)) {
unset($this->repositories[key($repository)]);
continue;
}


 if (is_int($name)) {
$this->repositories[] = $repository;
} else {
$this->repositories[$name] = $repository;
}
}
$this->repositories = array_reverse($this->repositories, true);
}
}




public function getRepositories()
{
return $this->repositories;
}








public function get($key)
{
switch ($key) {
case 'vendor-dir':
case 'bin-dir':
case 'process-timeout':
case 'cache-dir':
case 'cache-files-dir':
case 'cache-repo-dir':
case 'cache-vcs-dir':

 $env = 'COMPOSER_' . strtoupper(strtr($key, '-', '_'));

return rtrim($this->process(getenv($env) ?: $this->config[$key]), '/\\');

case 'cache-ttl':
return (int) $this->config[$key];

case 'cache-files-maxsize':
if (!preg_match('/^\s*([0-9.]+)\s*(?:([kmg])(?:i?b)?)?\s*$/i', $this->config[$key], $matches)) {
throw new \RuntimeException(
"Could not parse the value of 'cache-files-maxsize': {$this->config[$key]}"
);
}
$size = $matches[1];
if (isset($matches[2])) {
switch (strtolower($matches[2])) {
case 'g':
$size *= 1024;

 case 'm':
$size *= 1024;

 case 'k':
$size *= 1024;
break;
}
}

return $size;

case 'cache-files-ttl':
if (isset($this->config[$key])) {
return (int) $this->config[$key];
}

return (int) $this->config['cache-ttl'];

case 'home':
return rtrim($this->process($this->config[$key]), '/\\');

case 'discard-changes':
if ($env = getenv('COMPOSER_DISCARD_CHANGES')) {
if (!in_array($env, array('stash', 'true', 'false', '1', '0'), true)) {
throw new \RuntimeException(
"Invalid value for COMPOSER_DISCARD_CHANGES: {$env}. Expected 1, 0, true, false or stash"
);
}
if ('stash' === $env) {
return 'stash';
}


 return $env !== 'false' && (bool) $env;
}

if (!in_array($this->config[$key], array(true, false, 'stash'), true)) {
throw new \RuntimeException(
"Invalid value for 'discard-changes': {$this->config[$key]}. Expected true, false or stash"
);
}

return $this->config[$key];

case 'github-protocols':
if (reset($this->config['github-protocols']) === 'http') {
throw new \RuntimeException('The http protocol for github is not available anymore, update your config\'s github-protocols to use "https", "git" or "ssh"');
}

return $this->config[$key];

default:
if (!isset($this->config[$key])) {
return null;
}

return $this->process($this->config[$key]);
}
}

public function all()
{
$all = array(
'repositories' => $this->getRepositories(),
);
foreach (array_keys($this->config) as $key) {
$all['config'][$key] = $this->get($key);
}

return $all;
}

public function raw()
{
return array(
'repositories' => $this->getRepositories(),
'config' => $this->config,
);
}







public function has($key)
{
return array_key_exists($key, $this->config);
}







private function process($value)
{
$config = $this;

if (!is_string($value)) {
return $value;
}

return preg_replace_callback('#\{\$(.+)\}#', function ($match) use ($config) {
return $config->get($match[1]);
}, $value);
}
}
