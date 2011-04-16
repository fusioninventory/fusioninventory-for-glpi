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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


class PluginFusioninventoryWizard {

   function filAriane($a_list) {
      $i = 0;
      echo "<table class='tab_cadre'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "<strong>Fil d'ariane</strong>";
      echo "</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "<img src='".GLPI_ROOT."/pics/right.png'/>";
      echo " choix de l'action";
      $i++;
      echo "</td>";
      echo "</tr>";

      echo "</table>";
   }


   function displayButtons($a_buttons) {

      echo "<style type='text/css'>
.bgout {
   background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button.png);
}
.bgover {
   background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button_active.png);
}
</style>";
      echo "<center><table>";
      echo "<tr>";
      echo "<td valign='top'>";
      $this->filAriane(array());
      echo "</td>";
      echo "<td>";
         echo "<table cellspacing='10'>";
         echo "<tr>";
         foreach ($a_buttons as $name=>$link) {
            echo "<td class='bgout'
               onmouseover='this.className=\"bgover\"' onmouseout='this.className=\"bgout\"'
               width='240' height='155' align='center'>";
            echo "<a href='".$link."'><strong>".$name."</strong></a>";
            echo "</td>";
         }
         echo "</tr>";
         echo "</table>";
      echo "</td>";
      echo "</tr>";
      echo "</table></center>";

      
   }



}

?>