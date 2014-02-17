<?php











namespace Composer\Config;

use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;







class JsonConfigSource implements ConfigSourceInterface
{
private $file;
private $manipulator;






public function __construct(JsonFile $file)
{
$this->file = $file;
}




public function addRepository($name, $config)
{
$this->manipulateJson('addRepository', $name, $config, function (&$config, $repo, $repoConfig) {
$config['repositories'][$repo] = $repoConfig;
});
}




public function removeRepository($name)
{
$this->manipulateJson('removeRepository', $name, function (&$config, $repo) {
unset($config['repositories'][$repo]);
});
}




public function addConfigSetting($name, $value)
{
$this->manipulateJson('addConfigSetting', $name, $value, function (&$config, $key, $val) {
$config['config'][$key] = $val;
});
}




public function removeConfigSetting($name)
{
$this->manipulateJson('removeConfigSetting', $name, function (&$config, $key) {
unset($config['config'][$key]);
});
}




public function addLink($type, $name, $value)
{
$this->manipulateJson('addLink', $type, $name, $value, function (&$config, $type, $name, $value) {
$config[$type][$name] = $value;
});
}




public function removeLink($type, $name)
{
$this->manipulateJson('removeSubNode', $type, $name, function (&$config, $type, $name) {
unset($config[$type][$name]);
});
}

protected function manipulateJson($method, $args, $fallback)
{
$args = func_get_args();

 array_shift($args);
$fallback = array_pop($args);

if ($this->file->exists()) {
$contents = file_get_contents($this->file->getPath());
} else {
$contents = "{\n    \"config\": {\n    }\n}\n";
}
$manipulator = new JsonManipulator($contents);

$newFile = !$this->file->exists();


 if (call_user_func_array(array($manipulator, $method), $args)) {
file_put_contents($this->file->getPath(), $manipulator->getContents());
} else {

 $config = $this->file->read();
array_unshift($args, $config);
call_user_func_array($fallback, $args);
$this->file->write($config);
}

if ($newFile) {
@chmod($this->file->getPath(), 0600);
}
}
}
