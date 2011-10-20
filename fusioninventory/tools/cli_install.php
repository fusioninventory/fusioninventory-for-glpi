<?php

if (in_array('--help', $_SERVER['argv'])) {
   die("usage: ".$_SERVER['argv'][0]." [ --optimize ]\n");
}

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT . "/inc/includes.php");

// Init debug variable
$_SESSION['glpi_use_mode'] = DEBUG_MODE;
$_SESSION['glpilanguage']  = "en_GB";

LoadLanguage();

// Only show errors
$CFG_GLPI["debug_sql"]        = $CFG_GLPI["debug_vars"] = 0;
$CFG_GLPI["use_log_in_files"] = 1;
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
set_error_handler('userErrorHandlerDebug');

$DB = new DB();
if (!$DB->connected) {
   die("No DB connection\n");
}

/* ----------------------------------------------------------------- */
/**
 * Extends class Migration to redefine display mode
**/
class CliMigration extends Migration {

   function __construct($ver) {
      global $LANG;

      $this->deb     = time();
      $this->version = $ver;
   }


   function displayMessage ($msg) {

      $msg .= " (".timestampToString(time()-$this->deb).")";
      echo str_pad($msg, 100)."\r";
   }


   function displayTitle($title) {
      echo "\n".str_pad(" $title ", 100, '=', STR_PAD_BOTH)."\n";
   }


   function displayWarning($msg, $red=false) {

      if ($red) {
         $msg = "** $msg";
      }
      echo str_pad($msg, 100)."\n";
   }
}

/*---------------------------------------------------------------------*/

if (!TableExists("glpi_configs")) {
   die("GLPI not installed\n");
}

include (GLPI_ROOT . "/plugins/fusioninventory/install/update.php");
include (GLPI_ROOT . "/plugins/fusioninventory/locales/en_GB.php");
$current_version = pluginFusioninventoryGetCurrentVersion(PLUGIN_FUSIONINVENTORY_VERSION);

$migration = new CliMigration($current_version);

if (!isset($current_version)) {
   $current_version = 0;
}

$migration->displayWarning("Current FusionInventory version: $current_version");
$migration->displayWarning("Version to update: ".PLUGIN_FUSIONINVENTORY_VERSION);

// To prevent problem of execution time
ini_set("max_execution_time", "0");

if (($current_version != PLUGIN_FUSIONINVENTORY_VERSION)
     AND $current_version!='0') {
   pluginFusioninventoryUpdate($current_version, $migration);
   $migration->displayWarning("Update done.");
} else if ($current_version == PLUGIN_FUSIONINVENTORY_VERSION) {
   $migration->displayWarning("No migration needed.");
} else {
   include (GLPI_ROOT . "/plugins/fusioninventory/install/install.php");
   pluginFusioninventoryInstall(PLUGIN_FUSIONINVENTORY_VERSION, $migration);
   $migration->displayWarning("installation done.");
}

$plugin = new Plugin();
$plugin->getFromDBbyDir("fusioninventory");
$function = 'plugin_fusioninventory_check_config';
if (function_exists($function)) {
   if ($function()) {
      $plugin->update(array('id'    => $plugin->fields['id'],
                          'state' => Plugin::NOTACTIVATED));
   } else {
      $plugin->update(array('id'    => $plugin->fields['id'],
                          'state' => Plugin::TOBECONFIGURED));
   }
}


if (in_array('--optimize', $_SERVER['argv'])) {

   $migration->displayTitle($LANG['update'][139]);
   DBmysql::optimize_tables($migration);

   $migration->displayWarning("Optimize done.");
}
