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

// Update from 2.3.1 to 2.3.2
function update231to232() {
   global $DB, $CFG_GLPI, $LANG;

   echo "<strong>Update 2.3.1 to 2.3.2</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("232"); // Start

   plugin_fusioninventory_displayMigrationMessage("232", $LANG['update'][141]); // Updating schema
   
   plugin_fusioninventory_displayMigrationMessage("232", $LANG['update'][141]." - import locks");

   // Import OCS locks
   $PluginFusinvinventoryLock = new PluginFusinvinventoryLock();
   $PluginFusinvinventoryLock->importFromOcs();

   plugin_fusioninventory_displayMigrationMessage("232"); // End
}

?>