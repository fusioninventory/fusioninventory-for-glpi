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

   /**
   * Display history of taskjob
   *
   * @param $taskjobs_id integer id of the taskjob
   * @param $width integer how large in pixel display array
   * @param $options array to display with specific options
   *     - items_id integer id of item to display history
   *     - itemtype value type of item to display
   *
   * @return bool true if form is ok
   *
   **/
   function showHistory($taskjobs_id, $width="950", $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

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

      $where = '';
      if (isset($options['items_id']) AND isset($options['itemtype'])) {
         $where = " AND `items_id`='".$options['items_id']."'
                    AND `itemtype`='".$options['itemtype']."' ";
      }

      $query = 'SELECT * FROM `glpi_plugin_fusioninventory_taskjobstatus`
         WHERE `plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'"
            AND `state`!="3"
            '.$where.'
         GROUP BY uniqid,plugin_fusioninventory_agents_id
         ORDER BY `id` DESC';
      // ***** Display for all status running / prepared
      echo "<tr>";
      echo "<th colspan='2'>";
      echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/task_running.png'/>";
      echo "</th>";
      echo "<th colspan='6'>";
      echo $LANG['plugin_fusioninventory']['task'][19]."&nbsp;:";
      echo "</th>";
      echo "</tr>";
      $result = $DB->query($query);
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
      while ($data=$DB->fetch_array($result)) {
         $this->showHistoryLines($data['id']);
      }


      // ***** Display for statusjob OK
      echo "<tr>";
      echo "<th colspan='2'>";
      echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/task_finished.png'/>";
      echo "</th>";
      echo "<th colspan='6'>";
      echo $LANG['plugin_fusioninventory']['task'][20]."&nbsp;:";
      echo "</th>";
      echo "</tr>";
      $query = str_replace('`state`!="3"', '`state`="3"', $query);
      $result = $DB->query($query);
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
      while ($data=$DB->fetch_array($result)) {
         $this->showHistoryLines($data['id']);
      }

      echo "</table></center>";

      return true;
   }



   /**
   * Display each history line
   *
   * @param $taskjobstatus_id integer id of the taskjobstatus
   *
   * @return nothing
   *
   **/
   function showHistoryLines($taskjobstatus_id) {
      global $LANG;
      
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjobstatus->getFromDB($taskjobstatus_id);
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();

      $displayforceend = 0;
      $a_history = $this->find('`plugin_fusioninventory_taskjobstatus_id` = "'.$PluginFusioninventoryTaskjobstatus->fields['id'].'"', 'id');

      echo "<tr class='tab_bg_1'>";
      echo "<td id='plusmoins".$PluginFusioninventoryTaskjobstatus->fields["id"]."'><img src='".GLPI_ROOT.
               "/pics/expand.gif' onClick='Effect.Appear(\"viewfollowup".$PluginFusioninventoryTaskjobstatus->fields["id"].
               "\");close_array(".$PluginFusioninventoryTaskjobstatus->fields["id"].");' /></td>";

      echo "<td>";
      echo $PluginFusioninventoryTaskjobstatus->fields['uniqid'];
      echo "</td>";
      echo "<td>";
      echo $PluginFusioninventoryTaskjobstatus->fields['id'];
      echo "</td>";
      echo "<td>";

      $PluginFusioninventoryAgent->getFromDB($PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_agents_id']);
      echo $PluginFusioninventoryAgent->getLink(1);
      echo "</td>";
      $a_return = $this->displayHistoryDetail(array_pop($a_history));
      $count = $a_return[0];
      $displayforceend += $count;
      echo $a_return[1];

      echo "<td align='center'>";
      if ($displayforceend == "0") {
         echo "<form name='form' method='post' action='".GLPI_ROOT."/plugins/fusioninventory/front/taskjob.form.php'>";
         echo "<input type='hidden' name='taskjobstatus_id' value='".$PluginFusioninventoryTaskjobstatus->fields['id']."' />";
         echo "<input type='hidden' name='taskjobs_id' value='".$PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_taskjobs_id']."' />";
         echo '<input name="forceend" value="'.$LANG['plugin_fusioninventory']['task'][32].'"
             class="submit" type="submit">';
         echo "</form>";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr style='display: none;' id='viewfollowup".$PluginFusioninventoryTaskjobstatus->fields["id"]."'>
         <td colspan='8'>".$this->showHistoryInDetail($PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_agents_id'], $PluginFusioninventoryTaskjobstatus->fields['uniqid'], "850")."</td>
      </tr>";      
   }



   /**
   * Display detail of each history line
   *
   * @param  $agents_id integer id of the agent
   * @param $uniqid integer uniq id of each taskjobs runing
   * @param $width integer how large in pixel display array
   *
   * @return value all text to display
   *
   **/
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
         if (!empty($data["itemtype"])) {
            $device = new $data["itemtype"]();
            $device->getFromDB($data["items_id"]);
            $text .= $device->getLink(1);
            $text .= "&nbsp;";
            $text .= "(".$device->getTypeName().")";
         }
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



   /**
   * Display high detail of each history line
   *
   * @param $datas array datas of history
   *
   * @return value all text to display
   *
   **/
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



   /**
   * Add a new line of log for a taskjob status
   *
   * @param $taskjobs_id integer id of the taskjob
   * @param $items_id integer id of the item associated with taskjob status
   * @param $itemtype value type name of the item associated with taskjob status
   * @param $state value state of this taskjobstatus
   * @param $comment value the comment of this insertion
   *
   * @return value all text to display
   *
   **/
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



   /**
   * Display the graph of finished tasks
   *
   * @param $taskjobs_id integer id of the taskjob
   *
   * @return nothing
   *
   **/
   function graphFinish($taskjobs_id) {
      global $LANG;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();

      $finishState = array();
      $finishState[2] = 0;
      $finishState[3] = 0;
      $finishState[4] = 0;
      $finishState[5] = 0;

      $a_jobstatus = $PluginFusioninventoryTaskjobstatus->find('`plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'" GROUP BY uniqid,plugin_fusioninventory_agents_id');

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