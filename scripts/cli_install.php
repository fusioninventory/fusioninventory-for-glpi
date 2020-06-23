<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

// To prevent problem of execution time
ini_set("max_execution_time", "0");
ini_set("memory_limit", "-1");
ini_set("session.use_cookies", "0");
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
//set_error_handler('userErrorHandlerDebug');

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

include ("./docopt.php");

$doc = <<<DOC
cli_install.php

Usage:
   cli_install.php [--no-models-update] [--force-install] [--force-upgrade] [--as-user USER] [--optimize] [ --tests ]

Options:
   --force-install      Force plugin installation.
   --force-upgrade      Force plugin upgrade.
   --no-models-update   Do not perform SNMP models update.
   --as-user USER       Do install/upgrade as specified USER.
   --optimize           Optimize tables.
   --tests              Use GLPi test database

DOC;

$docopt = new \Docopt\Handler();
$args = $docopt->handle($doc);

if (isset($args)) {
   if (isset($args['--tests']) &&  $args['--tests'] !== false) {
      // Use test GLPi's database
      // Requires use of cliinstall of GLPI with --tests argument
      define('GLPI_ROOT', dirname(dirname(dirname(__DIR__))));
      define("GLPI_CONFIG_DIR", GLPI_ROOT . "/tests");
   }
}

include ("../../../inc/includes.php");

// Init debug variable
$_SESSION['glpilanguage']  = "en_GB";

Session::loadLanguage();

// Only show errors
$CFG_GLPI["debug_sql"]        = $CFG_GLPI["debug_vars"] = 0;
$CFG_GLPI["use_log_in_files"] = 1;

$DB = new DB();
if (!$DB->connected) {
   die("No DB connection\n");
}


/*---------------------------------------------------------------------*/

if (!$DB->tableExists("glpi_configs")) {
   die("GLPI not installed\n");
}

if (!is_null($args['--as-user'])) {
   $user = new User();
   if ($user->getFromDBbyName($args['--as-user'])) {
      $auth = new Auth();
      $auth->auth_succeded = true;
      $auth->user = $user;
      Session::init($auth);
   } else {
      die("User account not found\n");
   }
} else {
   die("No user defined with --as-user\n");
}
print("Running...\n");

$plugin = new Plugin();
$plugin->checkPluginState('fusioninventory');
$plugin->init();

require_once (PLUGIN_FUSIONINVENTORY_DIR . "/install/climigration.class.php");
include (PLUGIN_FUSIONINVENTORY_DIR . "/install/update.php");
$current_version = pluginFusioninventoryGetCurrentVersion();

$migration = new CliMigration($current_version);

if (!plugin_fusioninventory_check_prerequisites()) {
   echo "Function plugin_fusioninventory_check_prerequisites not exist\n";
   exit(1);
}

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

$mess = '';
if (($current_version != PLUGIN_FUSIONINVENTORY_VERSION)
     AND $current_version!='0') {
   $mess = "Update needed.";
} else if ($current_version == PLUGIN_FUSIONINVENTORY_VERSION) {
   $mess = "No migration needed.";
} else {
   $mess = "installation done.";
}

$migration->displayWarning($mess);

if ($args['--no-models-update']) {
   define('NO_MODELS_UPDATE', true);
}

if ($args['--force-install']) {
   define('FORCE_INSTALL', true);
}


if ($args['--force-upgrade']) {
   define('FORCE_UPGRADE', true);
}

$plugin->getFromDBbyDir("fusioninventory");
print("Installing Plugin...\n");
$plugin->install($plugin->fields['id']);
print("Install Done\n");
print("Activating Plugin...\n");
$plugin->activate($plugin->fields['id']);
print("Activation Done\n");
print("Loading Plugin...\n");
$plugin->load("fusioninventory");
print("Load Done...\n");


if ($args['--optimize']) {

   $migration->displayTitle(__('Optimizing tables'));

   DBmysql::optimize_tables($migration);

   $migration->displayWarning("Optimize done.");
}
