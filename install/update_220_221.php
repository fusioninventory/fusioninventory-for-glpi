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

// Update from 2.2.0 to 2.2.1
function update220to221() {
   global $DB, $LANG;

   echo "<strong>Update 2.2.0 to 2.2.1</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("221"); // Start

   plugin_fusioninventory_displayMigrationMessage("221", $LANG['update'][141]); // Updating schema

   plugin_fusioninventory_displayMigrationMessage("221", $LANG['update'][141]." Clean networkports not linked with devices");
   // Clean fusion IP when networkequipments_id has been deleted
   // (bug from Tracker 2.1.3 and before)
   $query = "SELECT `glpi_plugin_fusioninventory_networking_ifaddr`.*
             FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                  LEFT JOIN `glpi_networkequipments`
                     ON `FK_networking`=`glpi_networkequipments`.`id`
             WHERE `glpi_networkequipments`.`id` is null";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                          WHERE `ID`='".$data['ID']."' ";
         $DB->query($query_delete);
      }
   }

   plugin_fusioninventory_displayMigrationMessage("221", $LANG['update'][141]." Clean networkequipment IPs not linked with networkequipment (bug Tracker)");
   // delete when IP not valid (bug from Tracker 2.1.3 and before)
   $query = "SELECT * FROM `glpi_plugin_fusioninventory_networkequipmentsips`";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         if (!preg_match("/^((25[0-5]|2[0-4]\d|1?\d?\d).){3}(25[0-5]|2[0-4]\d|1?\d?\d)$/",$data['ip'])) {
            $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                             WHERE ID='".$data['ID']."' ";
            $DB->query($query_delete);
         }
      }
   }
   plugin_fusioninventory_displayMigrationMessage("221", $LANG['update'][141]." Different cleaning DB");
   // locations with entity -1 (bad code)
   $query = "DELETE FROM `glpi_locations`
             WHERE `entities_id`='-1' ";
   $DB->query($query);
   //CLean glpi_display
   $query = "DELETE FROM `glpi_displaypreferences`
             WHERE `itemtype`='PluginFusioninventorySnmpModel'
                   AND `num` NOT IN (1, 30, 3, 5, 6, 7, 8)";
   $DB->query($query);
   $query = "DELETE FROM `glpi_displaypreferences`
             WHERE `itemtype`='PluginFusioninventoryConfigSnmpSecurity'
                   AND `num` NOT IN (1, 30, 3, 4, 5, 7, 8, 9, 10)";
   $DB->query($query);
   $query = "DELETE FROM `glpi_displaypreferences`
             WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
                   AND `num` NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19)";
   $DB->query($query);
//         $query = "DELETE FROM `glpi_displaypreferences`
//                   WHERE `itemtype`='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS."'
//                         AND `num` NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15)";
//         $DB->query($query);
   $query = "DELETE FROM `glpi_displaypreferences`
             WHERE `itemtype`='PluginFusioninventoryAgent'
                   AND `num` NOT IN (1, 30, 4, 6, 8, 9, 10, 11, 12, 13, 14, 15)";
   $DB->query($query);
   $query = "DELETE FROM `glpi_displaypreferences`
             WHERE `itemtype`='PluginFusioninventoryIPRange'
                   AND `num` NOT IN (1, 2, 3, 30, 5, 6, 7, 8, 9)";
   $DB->query($query);
   $query = "DELETE FROM `glpi_displaypreferences`
             WHERE `itemtype`='PluginFusioninventoryNetworkPortLog'
                   AND `num` NOT IN (1, 2, 3, 4, 5, 6)";
   $DB->query($query);
   $query = "DELETE FROM `glpi_displaypreferences`
             WHERE `itemtype`='PluginFusioninventoryNetworkPort'
                   AND `num` NOT IN (30, 1, 2, 3)";
   $DB->query($query);

   $DB->query("UPDATE `glpi_plugin_fusioninventory_config`
      SET `version` = '2.2.1'
      WHERE `ID`=1
      LIMIT 1");

   plugin_fusioninventory_displayMigrationMessage("221"); // End

   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

}

?>