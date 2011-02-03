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

      $displayforceend = "0";
		echo "<script  type='text/javascript'>
function close_array(id){
	document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".GLPI_ROOT."/pics/collapse.gif\''+
      'onClick=\'Effect.Fade(\"viewfollowup'+id+'\");appear_array('+id+');\' />';
}
function appear_array(id){
	document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".GLPI_ROOT."/pics/expand.gif\''+
      'onClick=\'Effect.Appear(\"viewfollowup'+id+'\");close_array('+id+');\' />';
}

		</script>";

		echo "<script type='text/javascript' src='".GLPI_ROOT."/plugins/fusioninventory/prototype.js'></script>";
      echo "<script type='text/javascript' src='".GLPI_ROOT."/plugins/fusioninventory/effects.js'></script>";




      echo "<center><table class='tab_cadrehov' style='width: ".$width."px'>";
      echo "<tr>";
      echo "<th colspan='8'>".$LANG['title'][38]."</th>";
      echo "</tr>";

      $a_jobstatus = $PluginFusioninventoryTaskjobstatus->find('`plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'" GROUP BY uniqid,plugin_fusioninventory_agents_id',
                           '`id` DESC');

      echo "<tr>";
      echo "<th></th>";
      echo "<th>Uniqid</th>";
      echo "<th>".$LANG['plugin_fusioninventory']['processes'][38]."</th>";
      echo "<th>".$LANG['plugin_fusioninventory']['agents'][28]."</th>";
      echo "<th>";
      echo $LANG['common'][27];
      echo "</th>";
      echo "<th>";
      echo $LANG['joblist'][0];
      echo "</th>";
      echo "<th>";
      echo $LANG['common'][25];
      echo "</th>";
      echo "<th></th>";
      echo "</tr>";

      foreach ($a_jobstatus as $data) {

         $a_history = $this->find('`plugin_fusioninventory_taskjobstatus_id` = "'.$data['id'].'"', 'id');

         echo "<tr class='tab_bg_1'>";
         echo "<td id='plusmoins".$data["id"]."'><img src='".GLPI_ROOT.
                  "/pics/expand.gif' onClick='Effect.Appear(\"viewfollowup".$data["id"].
                  "\");close_array(".$data["id"].");' /></td>";

         echo "<td>";
         echo $data['uniqid'];
         echo "</td>";
         echo "<td>";
         echo $data['id'];
         echo "</td>";
         echo "<td>";
         $PluginFusioninventoryAgent->getFromDB($data['plugin_fusioninventory_agents_id']);
         echo $PluginFusioninventoryAgent->getLink(1);
         echo "</td>";
         $a_return = $this->displayHistoryDetail(array_pop($a_history));
         $count = $a_return[0];
         $displayforceend += $count;
         echo $a_return[1];

         echo "<td align='center'>";
         if ($displayforceend == "0") {
            echo "<form name='form' method='post' action='".GLPI_ROOT."/plugins/fusioninventory/front/taskjob.form.php'>";
            echo "<input type='hidden' name='taskjobstatus_id' value='".$data['id']."' />";
            echo "<input type='hidden' name='taskjobs_id' value='".$taskjobs_id."' />";
            echo '<input name="forceend" value="'.$LANG['plugin_fusioninventory']['task'][32].'"
                class="submit" type="submit">';
            echo "</form>";
         }
         echo "</td>";
         echo "</tr>";

         echo "<tr style='display: none;' id='viewfollowup".$data["id"]."'>
            <td colspan='8'>".$this->showHistoryInDetail($data['plugin_fusioninventory_agents_id'], $data['uniqid'], "850")."</td>
         </tr>";

      }
      echo "</table></center>";

      return true;

   }




   function showHistoryInDetail($agents_id, $uniqid, $width="950") {
      global $DB,$CFG_GLPI,$LANG;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();

      $text = "<center><table class='tab_cadrehov' style='width: ".$width."px'>";

      $a_jobstatus = $PluginFusioninventoryTaskjobstatus->find('`plugin_fusioninventory_agents_id`="'.$agents_id.'" AND `uniqid`="'.$uniqid.'"', '`id` DESC');

      foreach ($a_jobstatus as $data) {

         $displayforceend = 0;
         $a_history = $this->find('`plugin_fusioninventory_taskjobstatus_id` = "'.$data['id'].'"', 'id');

         $text .= "<tr>";
         $text .= "<th colspan='2'><img src='".GLPI_ROOT."/pics/puce.gif' />".$LANG['plugin_fusioninventory']['processes'][38]."&nbsp;: ".$data['id']."</th>";
         $text .= "<th>";
         $text .= $LANG['common'][27];
         $text .= "</th>";
         $text .= "<th>";
         $text .= $LANG['joblist'][0];
         $text .= "</th>";
         $text .= "<th>";
         $text .= $LANG['common'][25];
         $text .= "</th>";
         $text .= "</tr>";

         $text .= "<tr class='tab_bg_1'>";
         $text .= "<th>";
         $text .= $LANG['plugin_fusioninventory']['agents'][28]."&nbsp;:";
         $text .= "</th>";
         $text .= "<th>";
         $PluginFusioninventoryAgent->getFromDB($data['plugin_fusioninventory_agents_id']);
         $text .= $PluginFusioninventoryAgent->getLink(1);
         $text .= "</th>";
         $a_return = $this->displayHistoryDetail(array_shift($a_history));
         $count = $a_return[0];
         $text .= $a_return[1];
         $displayforceend += $count;
         $text .= "</tr>";

         $text .= "<tr class='tab_bg_1'>";
         $text .= "<th>";
         $text .= $LANG['plugin_fusioninventory']['task'][27]."&nbsp;:";
         $text .= "</th>";
         $text .= "<th>";
         $device = new $data["itemtype"]();
         $device->getFromDB($data["items_id"]);
         $text .= $device->getLink(1);
         $text .= "&nbsp;";
         $text .= "(".$device->getTypeName().")";
         $text .= "</th>";
         $a_return = $this->displayHistoryDetail(array_shift($a_history));
         $count = $a_return[0];
         $text .= $a_return[1];
         $displayforceend += $count;
         $text .= "</tr>";

         if (count($a_history) > 0) {
            $text .= "<tr class='tab_bg_1'>";
            $text .= "<td colspan='2' rowspan='".count($a_history)."'>";
            $text .= "</td>";
            $a_return = $this->displayHistoryDetail(array_shift($a_history));
            $count = $a_return[0];
            $text .= $a_return[1];
            $displayforceend += $count;
            $text .= "</tr>";

            foreach ($a_history as $datas) {
               $text .= "<tr class='tab_bg_1'>";
               $a_return = $this->displayHistoryDetail(array_shift($a_history));
               $count = $a_return[0];
               $text .= $a_return[1];
               $displayforceend += $count;
               $text .= "</tr>";
            }

         }

         $text .= "<tr class='tab_bg_4'>";
         $text .= "<td colspan='5' height='4'>";
         $text .= "</td>";
         $text .= "</tr>";

      }
      $text .= "</table></center>";
      return $text;
   }


   function displayHistoryDetail($datas) {
      global $LANG;

      $text = "<td align='center'>";
      $text .= convDateTime($datas['date']);
      $text .= "</td>";
      $finish = 0;

      switch ($datas['state']) {

         case 7 :
            $text .= "<td align='center'>";
            $text .= $LANG['plugin_fusioninventory']['taskjoblog'][7];
            break;

         case 1 :
            $text .= "<td align='center'>";
            $text .= $LANG['plugin_fusioninventory']['taskjoblog'][1];
            break;

         case 2 :
            $text .= "<td style='background-color: rgb(0, 255, 0);' align='center'>";
            $text .= $LANG['plugin_fusioninventory']['taskjoblog'][2];
            $finish++;
            break;

         case 3 :
            $text .= "<td style='background-color: rgb(255, 120, 0);' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][3]."</strong>";
            $finish++;
            break;

         case 4 :
            $text .= "<td style='background-color: rgb(255, 0, 0);' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][4]."</strong>";
            $finish++;
            break;

         case 5 :
            $text .= "<td style='background-color: rgb(255, 200, 0);' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][5]."</strong>";
            $finish++;
            break;

         case 6 :
            $text .= "<td style='background-color: rgb(255, 200, 0);' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][6]."</strong>";
            break;

         default:
            $text .= "<td>";
            break;
      }

      $text .= "</td>";
      $text .= "<td align='center'>";
      $text .= $datas['comment'];
      $text .= "</td>";
      return array($finish, $text);
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
      $input[$LANG['plugin_fusioninventory']['taskjoblog'][2]] = $finishState[2];
      $input[$LANG['plugin_fusioninventory']['taskjoblog'][3]] = $finishState[3];
      $input[$LANG['plugin_fusioninventory']['taskjoblog'][4]] = $finishState[4];
      $input[$LANG['plugin_fusioninventory']['taskjoblog'][5]] = $finishState[5];

      Stat::showGraph(array('status'=>$input),
               array('title'  => '',
                  'unit'      => '',
                  'type'      => 'pie',
                  'height'    => 150,
                  'showtotal' => false));

   }

}

?>