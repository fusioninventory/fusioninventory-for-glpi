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
   Original Author of file: David Durieux
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

function pluginFusioninventoryGetCurrentVersion($version) {
   global $DB;
   if ((!TableExists("glpi_plugin_tracker_config")) &&
      (!TableExists("glpi_plugin_fusioninventory_config"))) {
      return $version;
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
         $data = array();
         if ($result=$DB->query($query)) {
            if ($DB->numrows($result) == "1") {
               $data = $DB->fetch_assoc($result);
            }
         }

         if  ($data['version'] == "0") {
            return "2.0.2";
         } else {
            return $data['version'];
         }
      }      
   }
}


function pluginFusioninventoryUpdate($current_version) {
   // update from current_version to last case version + 1
   switch ($current_version){
      case "1.0.0":
         include("update_100_110.php");
         update100to110();
      case "1.1.0":
         include("update_110_200.php");
         update110to200();
      case "2.0.0":
         include("update_200_201.php");
         update200to201();
      case "2.0.1":
         include("update_201_202.php");
         update201to202();
      case "2.0.2":
         include("update_202_210.php");
         update202to210();
      case "2.1.0":
         include("update_210_211.php");
         update210to211();
      case "2.1.1":
         include("update_211_212.php");
         update211to212();
      case "2.1.2":
         include("update_212_213.php");
         update212to213();
      case "2.1.3":
         include("update_213_220.php");
         update213to220();
      case "2.2.0":
         include("update_220_221.php");
         update220to221();
      case "2.2.1":
         include("update_221_230.php");
         update221to230();

   }
//   // Remote IP of switch ports
//   $query = "UPDATE `glpi_networkports`
//             SET `ip` = NULL
//             WHERE `itemtype` ='NetworkEquipment'
//                AND `ip` IS NOT NULL ";
//   $DB->query($query);

   $plugins_id = PluginFusioninventoryModule::getModuleId("fusinvinventory");
   include(GLPI_ROOT."/plugins/fusioninventory/inc/profile.class.php");
   PluginFusioninventoryProfile::changeProfile($plugins_id);
   
}

?>