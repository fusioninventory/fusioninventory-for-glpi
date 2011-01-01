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
    * 
    * 1 : started
    * 2 : ok
    * 3 : error / replaned
    * 4 : error
    * 5 : Unknown
    * 6 : Running
    * 7 : prepared
    * 
    */



   function showHistory($taskjobs_id, $width="950") {
      global $DB,$CFG_GLPI,$LANG;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();

      echo "<center><table class='tab_cadrehov' style='width: ".$width."px'>";
      echo "<tr>";
      echo "<th colspan='5'>".$LANG['title'][38]."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_4'>";
      echo "<td colspan='5' height='4'>";
      echo "</td>";
      echo "</tr>";

      $a_jobstatus = $PluginFusioninventoryTaskjobstatus->find('`plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'"', '`id` DESC');

      foreach ($a_jobstatus as $data) {

         $a_history = $this->find('`plugin_fusioninventory_taskjobstatus_id` = "'.$data['id'].'"', 'id');

         echo "<tr>";
         echo "<th colspan='2'><img src='".GLPI_ROOT."/pics/puce.gif' />".$LANG['plugin_fusioninventory']['processes'][38]."&nbsp;: ".$data['id']."</th>";
         echo "<th>";
         echo $LANG['common'][27];
         echo "</th>";
         echo "<th>";
         echo $LANG['joblist'][0];
         echo "</th>";
         echo "<th>";
         echo $LANG['common'][25];
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<th>";
         echo $LANG['plugin_fusioninventory']['agents'][28]."&nbsp;:";
         echo "</th>";
         echo "<th>";
         $PluginFusioninventoryAgent->getFromDB($data['plugin_fusioninventory_agents_id']);
         echo $PluginFusioninventoryAgent->getLink(1);
         echo "</th>";
         $this->displayHistoryDetail(array_shift($a_history));
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<th>";
         echo $LANG['plugin_fusioninventory']['task'][27]."&nbsp;:";
         echo "</th>";
         echo "<th>";
         $device = new $data["itemtype"]();
         $device->getFromDB($data["items_id"]);
         echo $device->getLink(1);
         echo "&nbsp;";
         echo "(".$device->getTypeName().")";
         echo "</th>";
         $this->displayHistoryDetail(array_shift($a_history));
         echo "</tr>";

         if (count($a_history) > 0) {
            echo "<tr class='tab_bg_1'>";
            echo "<td colspan='2' rowspan='".count($a_history)."'>";
            echo "</td>";
            $this->displayHistoryDetail(array_shift($a_history));
            echo "</tr>";

            foreach ($a_history as $datas) {
               echo "<tr class='tab_bg_1'>";
               $this->displayHistoryDetail(array_shift($a_history));
               echo "</tr>";
            }

         }


         echo "<tr class='tab_bg_4'>";
         echo "<td colspan='5' height='4'>";
         echo "</td>";
         echo "</tr>";

      }
      echo "</table></center>";

      return true;
  
   }


   function displayHistoryDetail($datas) {
      global $LANG;

      echo "<td align='center'>";
      echo convDateTime($datas['date']);
      echo "</td>";

      switch ($datas['state']) {

         case 7 :
            echo "<td align='center'>";
            echo $LANG['plugin_fusioninventory']['taskjoblog'][7];
            break;

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

         case 6 :
            echo "<td style='background-color: rgb(255, 200, 0);' align='center'>";
            echo "<strong>".$LANG['plugin_fusioninventory']["taskjoblog"][6]."</strong>";
            break;

         default:
            echo "<td>";
            break;
      }

      echo "</td>";
      echo "<td align='center'>";
      echo $datas['comment'];
      echo "</td>";
   }


   function addTaskjoblog($taskjobs_id, $items_id, $itemtype, $state, $comment) {

      $this->getEmpty();
      unset($this->fields['id']);
      $this->fields['plugin_fusioninventory_taskjobstatus_id'] = $taskjobs_id;
      $this->fields['date'] = date("Y-m-d H:i:s");
      $this->fields['items_id'] = $items_id;
      $this->fields['itemtype'] = $itemtype;
      $this->fields['state'] = $state;
      $this->fields['comment'] = $comment;

      $this->addToDB();
   }


   function graphFinish($taskjobs_id) {
      global $LANG;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();

      $finishState = array();
      $finishState[2] = 0;
      $finishState[3] = 0;
      $finishState[4] = 0;
      $finishState[5] = 0;

      $a_jobstatus = $PluginFusioninventoryTaskjobstatus->find('`plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'"');
      $search = '(';
      foreach ($a_jobstatus as $data) {
         $search .= $data['id'].",";
      }
      $search .= ')';
      $search = str_replace(',)', ')', $search);

      $a_joblogs = $this->find('`plugin_fusioninventory_taskjobstatus_id` IN '.$search.' AND `state` IN (2, 3, 4, 5)');
      foreach($a_joblogs as $datajob) {
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