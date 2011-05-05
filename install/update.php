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
   $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
   $version_installed = $PluginFusioninventoryConfig->getValue(PluginFusioninventoryModule::getModuleId("fusinvinventory"),
                                             "version");

   if ($version_installed) {
      return $version_installed;
   } else {
      return $version;
   }
}


function pluginFusinvinventoryUpdate($current_version) {

   echo "<center>";
   echo "<table class='tab_cadre' width='950'>";
   echo "<tr>";
   echo "<th>Update process<th>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   include(GLPI_ROOT."/plugins/fusioninventory/install/update.php");

   switch ($current_version) {

      case "2.3.0-1":
      case "2.3.1-1":
         include("update_231_232.php");
         update231to232();
      case "2.3.2-1":

   }

   echo "</td>";
   echo "</tr>";
   echo "</table></center>";

   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');
   $config->updateConfigType($plugins_id, 'version', "2.3.2-1");
}

?>