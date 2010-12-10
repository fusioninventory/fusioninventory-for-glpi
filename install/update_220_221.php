<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
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


// Update from 2.2.0 to 2.2.1
function update220to221() {
   global $DB;

   // Clean fusion IP when networkequipments_id has been deleted
   // (bug from Tracker 2.1.3 and before)
   $query = "SELECT `glpi_plugin_fusioninventory_networkequipmentips`.*
             FROM `glpi_plugin_fusioninventory_networkequipmentips`
                  LEFT JOIN `glpi_networkequipments`
                     ON `networkequipments_id`=`glpi_networkequipments`.`id`
             WHERE `glpi_networkequipments`.`id` is null";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networkequipmentips`
                          WHERE `id`='".$data['id']."' ";
         $DB->query($query_delete);
      }
   }
   // delete when IP not valid (bug from Tracker 2.1.3 and before)
   $query = "SELECT * FROM `glpi_plugin_fusioninventory_networkequipmentsips`";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         if (!preg_match("/^((25[0-5]|2[0-4]\d|1?\d?\d).){3}(25[0-5]|2[0-4]\d|1?\d?\d)$/",$data['ip'])) {
            $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networkequipmentips`
                             WHERE id='".$data['id']."' ";
            $DB->query($query_delete);
         }
      }
   }
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




}

?>