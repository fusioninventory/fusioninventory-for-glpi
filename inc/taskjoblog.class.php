<?php

/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of GLPI.

   GLPI is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusioninventoryTaskjoblogs extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_taskjoblogs";
      $this->type = 'PluginFusioninventoryTaskjoblogs';
	}


   function showHistory($id) {
		global $DB,$CFG_GLPI,$LANG;

      echo "<center><table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th colspan='4'>Historique</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>";
      echo "Date";
      echo "</th>";
      echo "<th>";
      echo "Device";
      echo "</th>";
      echo "<th>";
      echo "State";
      echo "</th>";
      echo "<th>";
      echo "Comment";
      echo "</th>";
      echo "</tr>";

      $a_history = $this->find('plugin_fusioninventory_taskjobs_id="'.$id.'" ', 'id');

      foreach($a_history as $history_id=>$datas) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo convDateTime($datas['date']);
         echo "</td>";
         echo "<td align='center'>";
         $device = new $datas["itemtype"]();
         $device->getFromDB($datas["items_id"]);
         echo $device->getLink(1);
         echo "</td>";
         switch ($datas['state']) {

            case 1 :
               echo "<td align='center'>";
               echo "Started";
               break;

            case 2 :
               echo "<td style='background-color: rgb(0, 255, 0);' align='center'>";
               echo "Ok";
               break;

            case 3 :
               echo "<td style='background-color: rgb(255, 120, 0);' align='center'>";
               echo "<strong>Error / replaned</strong>";
               break;

            case 4 :
               echo "<td style='background-color: rgb(255, 0, 0);' align='center'>";
               echo "<strong>Error</strong>";
               break;

            case 5 :
               echo "<td style='background-color: rgb(255, 200, 0);' align='center'>";
               echo "<strong>Unknow</strong>";
               break;

         }

         echo "</td>";
         echo "<td align='center'>";
         echo $datas['comment'];
         echo "</td>";
         echo "</tr>";
      }

      echo "</table></center>";

      return true;
  
   }

}

?>
