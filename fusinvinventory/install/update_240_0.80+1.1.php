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
   Original Author of file: Walid Nouh
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

// Update from 2.2.1 to 2.3.0
function update240to080011() {
   global $DB, $CFG_GLPI, $LANG;

   ini_set("max_execution_time", "0");

   echo "<strong>Update 2.4.0 to 0.80+1.1</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("0.80+1.1"); // Start
   $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         for ($i=1; $i<4;$i++) {
            $queryUpdate = "UPDATE `glpi_plugin_fusinvinventory_libserialization`
               SET `serialized_sections$i` = '" .
               mysql_real_escape_string(htmlspecialchars_decode($data['serialized_sections'.$i])) ."'
               WHERE `internal_id` = '" . $data['internal_id'] . "'";
            $DB->query($queryUpdate);
         }
      }
   }
   
   plugin_fusioninventory_displayMigrationMessage("0.80+1.1"); // End
}

?>
