<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (in_array('--help', $_SERVER['argv'])) {
   die("usage: ".$_SERVER['argv'][0]." [ --optimize ]\n");
}

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT . "/inc/includes.php");

// Init debug variable
$_SESSION['glpi_use_mode'] = Session::DEBUG_MODE;
$_SESSION['glpilanguage']  = "en_GB";

Session::LoadLanguage();

// Only show errors
$CFG_GLPI["debug_sql"]        = $CFG_GLPI["debug_vars"] = 0;
$CFG_GLPI["use_log_in_files"] = 1;
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
//set_error_handler('userErrorHandlerDebug');

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

      $msg .= " (".Html::timestampToString(time()-$this->deb).")";
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

$plugin = new Plugin();
   
if (!isset($_SERVER['argv'][1])) {

   include (GLPI_ROOT . "/plugins/fusioninventory/install/update.php");
   include (GLPI_ROOT . "/plugins/fusioninventory/locales/en_GB.php");
   include (GLPI_ROOT . "/plugins/fusioninventory/hook.php");
   $current_version = pluginFusioninventoryGetCurrentVersion(PLUGIN_FUSIONINVENTORY_VERSION);

   $migration = new CliMigration($current_version);

   if (!isset($current_version)) {
      $current_version = 0;
   }
   if ($current_version == '0') {
      $migration->displayWarning("***** Install process of plugin FUSIONINVENTORY *****");
   } else {
      $migration->displayWarning("***** Update process of plugin FUSIONINVENTORY *****");
   }

   $migration->displayWarning("Current FusionInventory version: $current_version");
   $migration->displayWarning("Version to update: ".PLUGIN_FUSIONINVENTORY_VERSION);

   // To prevent problem of execution time
   ini_set("max_execution_time", "0");
   ini_set("memory_limit", "-1");

   $mess = '';
   if (($current_version != PLUGIN_FUSIONINVENTORY_VERSION)
        AND $current_version!='0') {
      $mess = "Update done.";      
   } else if ($current_version == PLUGIN_FUSIONINVENTORY_VERSION) {
      $mess = "No migration needed.";
   } else {
      $mess = "installation done.";
   }
   plugin_fusioninventory_install();
   $migration->displayWarning($mess);

   $plugin->getFromDBbyDir("fusioninventory");
   $plugin->load("fusioninventory");
   $plugin->activate($plugin->fields['id']);
   $plugin->load("fusioninventory");
   
   system("php -q cli_install.php fusinvsnmp");
   system("php -q cli_install.php fusinvinventory");
   
   include_once(GLPI_ROOT . "/plugins/webservices/hook.php");
   include (GLPI_ROOT . "/plugins/webservices/locales/en_GB.php");
   plugin_webservices_install();
   $plugin->getFromDBbyDir("webservices");
   $plugin->load("webservices");
   $plugin->activate($plugin->fields['id']);
   $plugin->load("webservices");
   system("php -q cli_install.php fusinvdeploy");

} else if ($_SERVER['argv'][1] == 'fusinvsnmp') {
   
   // ** Install / update too plugin fusinvsnmp
   if ($plugin->getFromDBbyDir("fusinvsnmp")) {
      include_once(GLPI_ROOT . "/plugins/fusinvsnmp/install/update.php");
      include_once(GLPI_ROOT . "/plugins/fusinvsnmp/locales/en_GB.php");
      $current_version = pluginFusinvsnmpGetCurrentVersion(PLUGIN_FUSINVSNMP_VERSION);

      $migration = new CliMigration($current_version);

      if (!isset($current_version)) {
         $current_version = 0;
      }
      if ($current_version == '0') {
         $migration->displayWarning("***** Install process of plugin FUSINVSNMP *****");
      } else {
         $migration->displayWarning("***** Update process of plugin FUSINVSNMP *****");
      }

      $migration->displayWarning("Current FusinvSNMP version: $current_version");
      $migration->displayWarning("Version to update: ".PLUGIN_FUSINVSNMP_VERSION);

      if (($current_version != PLUGIN_FUSINVSNMP_VERSION)
           AND $current_version!='0') {
      pluginFusinvsnmpUpdate($current_version, $migration);
         $migration->displayWarning("Update done.");
      } else if ($current_version == PLUGIN_FUSINVSNMP_VERSION) {
         $migration->displayWarning("No migration needed.");
      } else {
         include (GLPI_ROOT . "/plugins/fusinvsnmp/install/install.php");
         pluginFusinvsnmpInstall(PLUGIN_FUSINVSNMP_VERSION, $migration);
         $migration->displayWarning("installation done.");
      }

      $plugin->getFromDBbyDir("fusinvsnmp");
      $plugin->load("fusinvsnmp");
      $plugin->activate($plugin->fields['id']);
      $plugin->load("fusinvsnmp");
   }
} else if ($_SERVER['argv'][1] == 'fusinvinventory') {

   // ** Install / update too plugin fusinvinventory
   if ($plugin->getFromDBbyDir("fusinvinventory")) {
      include_once(GLPI_ROOT . "/plugins/fusinvinventory/install/update.php");
      include_once(GLPI_ROOT . "/plugins/fusinvinventory/locales/en_GB.php");
      $current_version = pluginFusinvinventoryGetCurrentVersion(PLUGIN_FUSINVINVENTORY_VERSION);

      $migration = new CliMigration($current_version);

      if (!isset($current_version)) {
         $current_version = 0;
      }
      if ($current_version == '0') {
         $migration->displayWarning("***** Install process of plugin FUSINVINVENTORY *****");
      } else {
         $migration->displayWarning("***** Update process of plugin FUSINVINVENTORY *****");
      }

      $migration->displayWarning("Current FusinvINVENTORY version: $current_version");
      $migration->displayWarning("Version to update: ".PLUGIN_FUSINVINVENTORY_VERSION);

      if (($current_version != PLUGIN_FUSINVINVENTORY_VERSION)
           AND $current_version!='0') {
      pluginFusinvinventoryUpdate($current_version, $migration);
         $migration->displayWarning("Update done.");
      } else if ($current_version == PLUGIN_FUSINVINVENTORY_VERSION) {
         $migration->displayWarning("No migration needed.");
      } else {
         include (GLPI_ROOT . "/plugins/fusinvinventory/install/install.php");
         pluginFusinvinventoryInstall(PLUGIN_FUSINVINVENTORY_VERSION, $migration);
         $migration->displayWarning("installation done.");
      }
      $plugin->getFromDBbyDir("fusinvinventory");
      $plugin->load("fusinvinventory");
      $plugin->activate($plugin->fields['id']);
      $plugin->load("fusinvinventory");
   }
} else if ($_SERVER['argv'][1] == 'fusinvdeploy') {
   
   // ** Install / update too plugin fusinvdeploy
   if ($plugin->getFromDBbyDir("fusinvdeploy")) {
      include_once(GLPI_ROOT . "/plugins/fusinvdeploy/install/update.php");
      include_once(GLPI_ROOT . "/plugins/fusinvdeploy/locales/en_GB.php");
      $a_plugin = plugin_version_fusinvdeploy();
      $current_version = pluginfusinvdeployGetCurrentVersion($a_plugin['version']);

      $migration = new CliMigration($current_version);

      if (!isset($current_version)) {
         $current_version = 0;
      }
      if ($current_version == '0') {
         $migration->displayWarning("***** Install process of plugin FUSINVDEPLOY *****");
      } else {
         $migration->displayWarning("***** Update process of plugin FUSINVDEPLOY *****");
      }

      $migration->displayWarning("Current FusinvDEPLOY version: ".$current_version);
      $migration->displayWarning("Version to update: ".$a_plugin['version']);

      if (($current_version != $a_plugin['version'])
           AND $current_version!='0') {
         pluginFusinvdeployUpdate($current_version, $migration);
         $migration->displayWarning("Update done.");
      } else if ($current_version == $a_plugin['version']) {
         $migration->displayWarning("No migration needed.");
      } else {
         include (GLPI_ROOT . "/plugins/fusinvdeploy/install/install.php");
         pluginFusinvdeployInstall($a_plugin['version'], $migration);
         $migration->displayWarning("installation done.");
      }
      $plugin->getFromDBbyDir("fusinvdeploy");
      $plugin->load("fusinvdeploy");
      $plugin->activate($plugin->fields['id']);
      $plugin->load("fusinvdeploy");
   }
}


if (in_array('--optimize', $_SERVER['argv'])) {

   $migration->displayTitle($LANG['update'][139]);
   DBmysql::optimize_tables($migration);

   $migration->displayWarning("Optimize done.");
}
