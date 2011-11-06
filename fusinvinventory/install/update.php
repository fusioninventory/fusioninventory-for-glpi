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

function pluginFusinvinventoryGetCurrentVersion($version) {
   global $DB;

   $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
   $version_installed = $PluginFusioninventoryConfig->getValue(PluginFusioninventoryModule::getModuleId("fusinvinventory"),
                                             "version");
   $versionconfig = '';

   if ($version_installed) {
      return $version_installed;
   } else {
      $pFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_findmodule = current($pFusioninventoryAgentmodule->find("`modulename`='INVENTORY'", "", 1));
      if (isset($a_findmodule['plugins_id'])) {
         $versionconfig = $PluginFusioninventoryConfig->getValue($a_findmodule['plugins_id'], "version");
         if (PluginFusioninventoryModule::getModuleId("fusinvinventory") != $a_findmodule['plugins_id']) {
            $query = "UPDATE `glpi_plugin_fusioninventory_configs`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId("fusinvinventory")."' 
               WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
            $DB->query($query);
            $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId("fusinvinventory")."' 
               WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
            $DB->query($query);
            $query = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId("fusinvinventory")."' 
               WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
            $DB->query($query);
            $query = "UPDATE `glpi_plugin_fusioninventory_profiles`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId("fusinvinventory")."' 
               WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
            $DB->query($query);
         }
      }
      if ($versionconfig) {
         return $versionconfig;
      }
      return '0';
   }
}


function pluginFusinvinventoryUpdate($current_version, $migrationname='Migration') {
   global $DB;

   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');   
   
   if (!PluginFusioninventoryConfig::getValue($plugins_id, 'states_id_default')) {
      $config->initConfig($plugins_id, array('states_id_default' => 0));
   }
   if (!PluginFusioninventoryConfig::getValue($plugins_id, "import_vm")) {
       $config->initConfig($plugins_id, array("import_vm" => "1"));
   }
   if (!PluginFusioninventoryConfig::getValue($plugins_id, "component_networkdrive")) {
       $config->initConfig($plugins_id, array("component_networkdrive" => "1"));
   }
   if (!PluginFusioninventoryConfig::getValue($plugins_id, "group")) {
       $config->initConfig($plugins_id, array("group" => "0"));
   }
   if (!PluginFusioninventoryConfig::getValue($plugins_id, "component_networkcardvirtual")) {
       $config->initConfig($plugins_id, array("component_networkcardvirtual" => "1"));
   }
   
   if (TableExists("glpi_plugin_fusinvinventory_computers")) {
      $Computer = new Computer();
      $sql = "SELECT * FROM `glpi_plugin_fusinvinventory_computers`";
      $result=$DB->query($sql);
      while ($data = $DB->fetch_array($result)) {
         if ($Computer->getFromDB($data['items_id'])) {
            $input = array();
            $input['id'] = $data['items_id'];
            $input['uuid'] = $data['uuid'];
            $Computer->update($input);
         }
      }
      $sql = "DROP TABLE `glpi_plugin_fusinvinventory_computers`";
      $DB->query($sql);
   	
   }
   
   


   $config->updateConfigType($plugins_id, 'version', PLUGIN_FUSINVINVENTORY_VERSION);
}

?>