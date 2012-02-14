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

// Update from 2.3.1 to 2.3.2
function update231to232() {
   global $LANG;

   echo "<strong>Update 2.3.1 to 2.3.2</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("232"); // Start

   plugin_fusioninventory_displayMigrationMessage("232", $LANG['update'][141]); // Updating schema
   
   plugin_fusioninventory_displayMigrationMessage("232", $LANG['update'][141]." - import locks");

   // Import OCS locks
   if (!class_exists("PluginFusinvinventoryLib")) {
      include_once(GLPI_ROOT."/plugins/fusinvinventory/inc/lib.class.php");
   }
   if (!class_exists("PluginFusinvinventoryLibhook")) {
      include_once(GLPI_ROOT."/plugins/fusinvinventory/inc/libhook.class.php");
   }
   if (!class_exists("PluginFusinvinventoryLock")) {
      include_once(GLPI_ROOT."/plugins/fusinvinventory/inc/lock.class.php");
   }
   if (!class_exists("PluginFusioninventoryLock")) {
      include_once(GLPI_ROOT."/plugins/fusioninventory/inc/lock.class.php");
   }
//   $PluginFusinvinventoryLock = new PluginFusinvinventoryLock();
//   $PluginFusinvinventoryLock->importFromOcs();

   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');

   //Add configuration entry for default state of a newly imported asset
   if (!$config->getValue($plugins_id, 'states_id_default')) {
      $config->addConfig($plugins_id, 'states_id_default', 0);
   }
   
   plugin_fusioninventory_displayMigrationMessage("232"); // End
}

?>