<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

function pluginFusinvsnmpGetCurrentVersion($version) {
   global $DB;

   if ((!TableExists("glpi_plugin_tracker_config")) &&
      (!TableExists("glpi_plugin_fusioninventory_config")) &&
      (!TableExists("glpi_plugin_fusinvsnmp_agentconfigs")) &&
      (!TableExists("glpi_plugin_fusinvsnmp_tmp_configs"))) {
      return '0';
   } else if ((TableExists("glpi_plugin_tracker_config")) ||
         (TableExists("glpi_plugin_fusioninventory_config"))) {

      if ((!TableExists("glpi_plugin_tracker_agents")) &&
         (!TableExists("glpi_plugin_fusioninventory_agents"))) {
         return "1.1.0";
      }
      if ((!TableExists("glpi_plugin_tracker_config_discovery")) &&
         (!TableExists("glpi_plugin_fusioninventory_config"))) {
         return "2.0.0";
      }
      if (((TableExists("glpi_plugin_tracker_agents")) &&
           (!FieldExists("glpi_plugin_tracker_config", "version"))) &&
         (!TableExists("glpi_plugin_fusioninventory_config"))) {
         return "2.0.2";
      }
      if (((TableExists("glpi_plugin_tracker_agents")) &&
           (FieldExists("glpi_plugin_tracker_config", "version"))) ||
         (TableExists("glpi_plugin_fusioninventory_config"))) {

         $query = "";
         if (TableExists("glpi_plugin_tracker_agents")) {
            $query = "SELECT version FROM glpi_plugin_tracker_config LIMIT 1";
         } else if (TableExists("glpi_plugin_fusioninventory_config")) {
            $query = "SELECT version FROM glpi_plugin_fusioninventory_config LIMIT 1";
         }
         if ($query != "") {
            if ($result=$DB->query($query)) {
               if ($DB->numrows($result) == "1") {
                  $data = $DB->fetch_assoc($result);
               }
            }
         }
         if (!isset($data['version'])) {
            return "2.0.2";
         } else if ($data['version'] == "0") {
            return "2.0.2";
         } else {
            return $data['version'];
         }
      }      
   } else {
      if (!class_exists('PluginFusioninventoryConfig')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/config.class.php");
      }
      if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
      }
      if (!class_exists('PluginFusioninventoryModule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/module.class.php");
      }
      
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $versionconfig = $PluginFusioninventoryConfig->getValue($plugins_id, "version");
      if ((isset($versionconfig)) AND (!empty($versionconfig))) {
         if ($versionconfig == '2.2.1'
                 AND TableExists("glpi_plugin_fusinvsnmp_configlogfields")) {
            return "2.3.0-1";
         }
      }
      if ($versionconfig == '') {
         $pFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
         $a_findmodule = current($pFusioninventoryAgentmodule->find("`modulename`='NETDISCOVERY'", "", 1));
         if (isset($a_findmodule['plugins_id'])) {
            $versionconfig = $PluginFusioninventoryConfig->getValue($a_findmodule['plugins_id'], "version");
            if ((isset($versionconfig)) AND (!empty($versionconfig))) {
               if ($versionconfig == '2.2.1'
                       AND TableExists("glpi_plugin_fusinvsnmp_configlogfields")) {
                  return "2.3.0-1";
               }
            }
            if ($plugins_id != $a_findmodule['plugins_id']) {
               $query = "UPDATE `glpi_plugin_fusioninventory_configs`
                  SET `plugins_id`='".$plugins_id."' 
                  WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
               $DB->query($query);
               $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
                  SET `plugins_id`='".$plugins_id."' 
                  WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
               $DB->query($query);
               $query = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
                  SET `plugins_id`='".$plugins_id."' 
                  WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
               $DB->query($query);
               $query = "UPDATE `glpi_plugin_fusioninventory_profiles`
                  SET `plugins_id`='".$plugins_id."' 
                  WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
               $DB->query($query);
            }
         }
      }
      return $versionconfig;
   }
}



function pluginFusinvsnmpUpdate($current_version) {

   if (!class_exists('PluginFusioninventoryMapping')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/mapping.class.php");
   }
   
   switch ($current_version){
      case "2.2.1":
         include("update_221_230.php");
         update221to230();
      case "2.3.0-1":
      case "2.3.1-1":
         include("update_231_232.php");
         update231to232();
      case "2.3.2-1":
      case "2.3.3-1":
      case "2.3.4-1":
      case "2.3.5-1":
      case "2.3.6-1":
      case "2.3.7-1":
      case "2.3.8-1":
      case "2.3.9-1":
         include("update_232_240.php");
         update232to240();
         PluginFusinvsnmpModel::importAllModels();
   }

   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
   $config->updateConfigType($plugins_id, 'version', PLUGIN_FUSINVSNMP_VERSION);
}
?>