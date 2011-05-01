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
function update232to231() {
   global $DB;

   // Import models
   $importexport = new PluginFusinvsnmpImportExport();

   $nb = 0;
   foreach (glob(GLPI_ROOT.'/plugins/fusinvsnmp/models/*.xml') as $file) {
      $nb++;
   }
   $i = 0;
   echo "<table class='tab_cadre_fixe'>";
   echo "<tr class='tab_bg_1'>";
   echo "<th align='center'>";
   echo "Importing SNMP models, please wait...";
   echo "</th>";
   echo "</tr>";
   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";
   createProgressBar("Importing SNMP models, please wait...");
   foreach (glob(GLPI_ROOT.'/plugins/fusinvsnmp/models/*.xml') as $file) {
      $importexport->import($file,0,1);
      $i++;
      changeProgressBarPosition($i,$nb,"$i / $nb");
   }
   echo "</td>";
   echo "</table>";
}
?>