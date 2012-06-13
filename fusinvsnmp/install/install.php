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

function pluginFusinvsnmpInstall($version, $migrationname='Migration') {
   global $DB;

   ini_set("max_execution_time", "0");

   $migration = new $migrationname($version);
   
   $migration->displayMessage("Installation of plugin FusinvSNMP");
   
   // Get informations of plugin
   $a_plugin = plugin_version_fusinvsnmp();

   include_once (GLPI_ROOT . "/plugins/fusinvsnmp/install/update.php");
   $version_detected = pluginfusinvsnmpGetCurrentVersion($a_plugin['version']);

   if ((isset($version_detected))
           AND ($version_detected != $a_plugin['version'])
           AND $version_detected!='0') {

      // Update
      pluginFusinvsnmpUpdate($version_detected);
   } else if ((isset($version_detected)) AND ($version_detected == $a_plugin['version'])) {
      return;
   } else {
      // Installation

      // Create database
      $DB_file = GLPI_ROOT ."/plugins/fusinvsnmp/install/mysql/plugin_fusinvsnmp-".$a_plugin['version']."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", $sql_query) as $sql_line) {
         if (get_magic_quotes_runtime())
            $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line))
            $DB->query($sql_line)/* or die($DB->error())*/;
      }

      // Create folder in GLPI_PLUGIN_DOC_DIR
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/tmp');
      }

      if (!class_exists('PluginFusinvsnmpConfigLogField')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/configlogfield.class.php");
      }
      $configLogField = new PluginFusinvsnmpConfigLogField();
      $configLogField->initConfig();

      /*
       * Import / update SNMP models
       */
      if (!class_exists('PluginFusinvsnmpModel')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/model.class.php");
      }
      if (!class_exists('PluginFusinvsnmpModelMib')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/modelmib.class.php");
      }
      if (!class_exists('PluginFusinvsnmpImportExport')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/importexport.class.php");
      }
      if (!class_exists('PluginFusinvsnmpCommonDBTM')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/commondbtm.class.php");
      }
      PluginFusinvsnmpModel::importAllModels();

      include_once (GLPI_ROOT . "/plugins/fusioninventory/inc/staticmisc.class.php");
      $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
      PluginFusioninventoryProfile::initProfile($a_plugin['shortname'], $plugins_id);

      if (!class_exists('PluginFusinvSNMPConfig')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/config.class.php");
      }
      $configSNMP = new PluginFusinvSNMPConfig();
      $configSNMP->initConfigModule();
      // Creation config values
   //      PluginFusioninventoryConfig::add($modules_id, type, value);

      PluginFusioninventoryProfile::changeProfile($plugins_id);
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "SNMPQUERY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $PluginFusioninventoryAgentmodule->add($input);

      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "NETDISCOVERY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $PluginFusioninventoryAgentmodule->add($input);

      Crontask::Register('PluginFusinvsnmpNetworkPortLog', 'cleannetworkportlogs', (3600 * 24), array('mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30));
   }
}



function pluginFusinvsnmpUninstall() {
   global $DB;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvsnmp();

   $PluginFusioninventorySetup = new PluginFusioninventorySetup();

   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
      $PluginFusioninventorySetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
   }

   PluginFusioninventoryProfile::cleanProfile($a_plugin['shortname']);

   $query = "SHOW TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (strstr($data[0],"glpi_plugin_".$a_plugin['shortname']."_")){
         $query_delete = "DROP TABLE `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }

   $query="DELETE FROM `glpi_displaypreferences`
           WHERE `itemtype` LIKE 'PluginFusinvsnmp%';";
   $DB->query($query) or die($DB->error());

   PluginFusioninventoryTask::cleanTasksbyMethod('netdiscovery');
   PluginFusioninventoryTask::cleanTasksbyMethod('snmpquery');

   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
   $PluginFusioninventoryAgentmodule->deleteModule($plugins_id);

   // Clean mapping
   $query = "DELETE FROM `glpi_plugin_fusioninventory_mappings`
      WHERE `itemtype` = 'NetworkEquipment'";
   $DB->query($query);
   $query = "DELETE FROM `glpi_plugin_fusioninventory_mappings`
      WHERE `itemtype` = 'Printer'";
   $DB->query($query);
   $query = "DELETE FROM `glpi_plugin_fusioninventory_mappings`
      WHERE `itemtype` = 'Computer'
         AND `name`='serial'";
   $DB->query($query);
   $query = "DELETE FROM `glpi_plugin_fusioninventory_mappings`
      WHERE `itemtype` = 'Computer'
         AND `name`='ifPhysAddress'";
   $DB->query($query);
   $query = "DELETE FROM `glpi_plugin_fusioninventory_mappings`
      WHERE `itemtype` = 'Computer'
         AND `name`='ifaddr'";
   $DB->query($query);
   

   $config = new PluginFusioninventoryConfig;
   $config->cleanConfig($plugins_id);
   return true;
}

?>