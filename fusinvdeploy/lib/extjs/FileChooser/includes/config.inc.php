<?php
if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include_once (GLPI_ROOT."/inc/includes.php");

$PluginFusioninventoryConfig = new PluginFusioninventoryConfig;
$plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');

define('EXT_DIRECTORY', '/scripts/ext-3.1'); // The web accessible path to your ext source files - No trailing slash!
define('DIRECTORY', $PluginFusioninventoryConfig->getValue($plugins_id, 'server_upload_path')); // The directory of files that the file manager will access - No trailing slash!
define('WEB_DIRECTORY', $PluginFusioninventoryConfig->getValue($plugins_id, 'server_upload_path')); // The web accessible path to the same directory - No trailing slash!

?>