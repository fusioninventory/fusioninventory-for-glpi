<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------


function pluginFusinvinventoryInstall() {
   global $DB,$LANG;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvinventory();

   include (GLPI_ROOT . "/plugins/fusinvinventory/install/update.php");
   $version_detected = pluginfusinvinventoryGetCurrentVersion($a_plugin['version']);
   if ((isset($version_detected)) AND ($version_detected != $a_plugin['version'])) {
      // Update
      pluginFusinvinventoryUpdate();
   } else {
      // Installation
      // Add new module in plugin_fusioninventory (core)

      // Create database
      $DB_file = GLPI_ROOT ."/plugins/fusinvinventory/install/mysql/plugin_fusinvinventory-".$a_plugin['version']."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
      }

      //Add tables for pciids
      $DB_file = GLPI_ROOT ."/plugins/fusinvinventory/install/mysql/pciid.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
      }

      //Add tables for usbids
      $DB_file = GLPI_ROOT ."/plugins/fusinvinventory/install/mysql/usbid.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
      }


      // Create folder in GLPI_PLUGIN_DOC_DIR
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
      }

      $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
      PluginFusioninventoryProfile::initProfile($a_plugin['shortname'], $plugins_id);
      // Creation of profile
//      PluginFusioninventoryProfile::initSession($modules_id, array(type, right));

      // Creation config values
//      PluginFusioninventoryConfig::add($modules_id, type, value);


      PluginFusioninventoryProfile::changeProfile($plugins_id);
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "INVENTORY";
      $input['is_active']  = 1;
      $input['exceptions'] = exportArrayToDB(array());
      $PluginFusioninventoryAgentmodule->add($input);

      // Create configuration
      $PluginFusinvinventoryConfig = new PluginFusinvinventoryConfig();
      $PluginFusinvinventoryConfig->initConfigModule();

   }
}


function pluginFusinvinventoryUninstall() {
   global $DB;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvinventory();

   $PluginFusioninventorySetup = new PluginFusioninventorySetup();

   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
      $PluginFusioninventorySetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
   }
   // Delete files of lib
   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/DataFilter')) {
      $PluginFusioninventorySetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/DataFilter');
   }
   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/criterias')) {
      $PluginFusioninventorySetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/criterias');
   }
   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/machines')) {
      $PluginFusioninventorySetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/machines');
   }

   // Delete config
   $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');
   $PluginFusioninventoryConfig->cleanConfig($plugins_id);


   PluginFusioninventoryProfile::cleanProfile($a_plugin['shortname']);

   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');
   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
   $PluginFusioninventoryAgentmodule->deleteModule($plugins_id);

   // Delete rules
   $Rule = new Rule();
   $a_rules = $Rule->find("`sub_type`='PluginFusinvinventoryRuleEntity'");
   foreach ($a_rules as $id => $data) {
      $Rule->delete($data);
   }

   
   $query = "SHOW TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (strstr($data[0],"glpi_plugin_".$a_plugin['shortname']."_")){
         $query_delete = "DROP TABLE `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }
   return true;
}

?>