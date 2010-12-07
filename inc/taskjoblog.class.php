<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusioninventoryTaskjoblog extends CommonDBTM {

   /*
    * Define different state
    *
    * 1 : started
    * 2 : ok
    * 3 : error / replaned
    * 4 : error
    * 5 : Unknown
    */



   function __construct() {
      $this->table = "glpi_plugin_fusioninventory_taskjoblogs";
      $this->type = 'PluginFusioninventoryTaskjoblog';
   }


   function showHistory($id, $width="950") {
      global $DB,$CFG_GLPI,$LANG;

      echo "<center><table class='tab_cadrehov' style='width: ".$width."px'>";
      echo "<tr>";
      echo "<th colspan='4'>".$LANG['title'][38]."</th>";
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
               echo $LANG['plugin_fusioninventory']["taskjoblog"][1];
               break;

            case 2 :
               echo "<td style='background-color: rgb(0, 255, 0);' align='center'>";
               echo $LANG['plugin_fusioninventory']["taskjoblog"][2];
               break;

            case 3 :
               echo "<td style='background-color: rgb(255, 120, 0);' align='center'>";
               echo "<strong>".$LANG['plugin_fusioninventory']["taskjoblog"][3]."</strong>";
               break;

            case 4 :
               echo "<td style='background-color: rgb(255, 0, 0);' align='center'>";
               echo "<strong>".$LANG['plugin_fusioninventory']["taskjoblog"][4]."</strong>";
               break;

            case 5 :
               echo "<td style='background-color: rgb(255, 200, 0);' align='center'>";
               echo "<strong>".$LANG['plugin_fusioninventory']["taskjoblog"][5]."</strong>";
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


   function addTaskjoblog($taskjobs_id, $items_id, $itemtype, $state, $comment) {

      $this->getEmpty();
      $this->fields['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $this->fields['date'] = date("Y-m-d H:i:s");
      $this->fields['items_id'] = $items_id;
      $this->fields['itemtype'] = $itemtype;
      $this->fields['state'] = $state;
      $this->fields['comment'] = $comment;

      $this->addToDB();
   }


   function graphFinish($taskjobs_id) {
      global $LANG;

      $finishState = array();
      $finishState[2] = 0;
      $finishState[3] = 0;
      $finishState[4] = 0;
      $finishState[5] = 0;

      // Get logs information (ok, error, replanned, unknow)
      $a_joblogs = $this->find("`plugin_fusioninventory_taskjobs_id`='".$taskjobs_id."'
            AND `state` IN (2, 3, 4, 5)");
      foreach($a_joblogs as $joblog_id=>$datajob) {
         $finishState[$datajob['state']]++;
      }
      $input = array();
      $input[$LANG['plugin_fusioninventory']["taskjoblog"][2]] = $finishState[2];
      $input[$LANG['plugin_fusioninventory']["taskjoblog"][3]] = $finishState[3];
      $input[$LANG['plugin_fusioninventory']["taskjoblog"][4]] = $finishState[4];
      $input[$LANG['plugin_fusioninventory']["taskjoblog"][5]] = $finishState[5];

      Stat::showGraph(array('status'=>$input),
               array('title'  => '',
                  'unit'      => '',
                  'type'      => 'pie',
                  'height'    => 150,
                  'showtotal' => false));

   }

}

?>
