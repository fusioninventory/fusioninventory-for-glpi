<?php











namespace Composer\Config;







interface ConfigSourceInterface
{






public function addRepository($name, $config);






public function removeRepository($name);







public function addConfigSetting($name, $value);






public function removeConfigSetting($name);








public function addLink($type, $name, $value);







public function removeLink($type, $name);
}
