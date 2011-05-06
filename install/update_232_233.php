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

// Update from 2.3.2 to 2.3.3
function update232to233() {
   global $DB, $CFG_GLPI, $LANG;

   ini_set("max_execution_time", "0");

   echo "<strong>Update 2.3.2 to 2.3.3</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("233"); // Start

   plugin_fusioninventory_displayMigrationMessage("233", $LANG['update'][141]); // Updating schema
   
   // bug of purge network port when purge unknown devices
   $networkPort = new NetworkPort();
   $sql = "SELECT `glpi_networkports`.`id` as nid FROM `glpi_networkports`
      LEFT JOIN `glpi_plugin_fusioninventory_unknowndevices`
         ON `glpi_plugin_fusioninventory_unknowndevices`.`id` = `glpi_networkports`.`items_id`
      WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
         AND `glpi_plugin_fusioninventory_unknowndevices`.`id` IS NULL ";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $networkPort->delete(array('id'=>$data['nid']), 1);
   }
   
   plugin_fusioninventory_displayMigrationMessage("233"); // End
}

?>