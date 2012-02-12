<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
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

   const TASK_STARTED            = 1;
   const TASK_OK                 = 2;
   const TASK_ERROR_OR_REPLANNED = 3;
   const TASK_ERROR              = 4;
   const TASK_UNKNOWN            = 5;
   const TASK_RUNNING            = 6;
   const TASK_PREPARED           = 7;

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

      $this->javascriptHistory();
      $a_uniqid = array();

      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      $where = '';
      if (isset($options['items_id']) AND isset($options['itemtype'])) {
         $where = " AND `items_id`='".$options['items_id']."'
                    AND `itemtype`='".$options['itemtype']."' ";
      }
      if (isset($options['uniqid'])) {
         $where .= " AND `uniqid`='".$options['uniqid']."' ";
      }

      echo "<center>";
      $query = 'SELECT * FROM `glpi_plugin_fusioninventory_taskjobstatus`
         WHERE `plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'"
            AND `state`!="3"
            '.$where.'
         GROUP BY uniqid,plugin_fusioninventory_agents_id
         ORDER BY `id` DESC';
      $result = $DB->query($query);
      // ***** Display for all status running / prepared
      if (isset($options['uniqid']) AND $DB->numrows($result) == '0') {

      } else {
         // Display

         echo "<table class='tab_cadre' style='width: ".$width."px'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th width='32'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/task_running.png'/>";
         echo "</th>";
         echo "<td>";
         if ($DB->numrows($result) > 0) {
            echo "<table class='tab_cadre'>";
            echo "<tr>";
            echo "<th></th>";
            echo "<th>".$LANG['plugin_fusioninventory']['task'][47]."</th>";
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
            echo "</tr>";
            while ($data=$DB->fetch_array($result)) {
               $this->showHistoryLines($data['id'], 1, 0, 7);
               $a_uniqid[] = $data['uniqid'];
            }
            echo "</table>";
         }
         echo "</td>";
         echo "</tr>";
         echo "</table><br/>";
      }


      // ***** Display for statusjob OK
      if (count($a_uniqid) > 0) {
         $where .= " AND `uniqid` NOT IN ('".implode("','", $a_uniqid)."')";
         $query = 'SELECT * FROM `glpi_plugin_fusioninventory_taskjobstatus`
            WHERE `plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'"
               AND `state`!="3"
               '.$where.'
            GROUP BY uniqid,plugin_fusioninventory_agents_id
            ORDER BY `id` DESC';
      }
      $querycount = 'SELECT count(*) AS cpt FROM `glpi_plugin_fusioninventory_taskjobstatus`
            WHERE `plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'"
               AND `state`="3"
               '.$where.'
            GROUP BY uniqid,plugin_fusioninventory_agents_id';
         $resultcount = $DB->query($querycount);
         $number = $DB->numrows($resultcount);
      if (isset($options['uniqid']) AND $number == '0') {

      } else {
         // display
         echo "<table class='tab_cadre' width='".$width."'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th width='32'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/task_finished.png'/>";
         echo "</td>";
         echo "<td>";
            echo "<table class='tab_cadre' >";
            echo "<tr>";
            echo "<td colspan='5'>";
            Html::printAjaxPager('',$start,$number);
            echo "</td>";
            echo "</tr>";

            $query = str_replace('`state`!="3"', '`state`="3"', $query);
            $query .= ' LIMIT '.intval($start).','.intval($_SESSION['glpilist_limit']);
            $result = $DB->query($query);
            echo "<tr>";
            echo "<th></th>";
            echo "<th>".$LANG['plugin_fusioninventory']['task'][47]."</th>";
            echo "<th>".$LANG['plugin_fusioninventory']['agents'][28]."</th>";
            echo "<th>";
            echo $LANG['common'][27];
            echo "</th>";
            echo "<th>";
            echo $LANG['joblist'][0];
            echo "</th>";
            echo "</tr>";

            while ($data=$DB->fetch_array($result)) {
               $this->showHistoryLines($data['id'], 0, 0,5);
            }

            echo "<tr>";
            echo "<td colspan='5'>";
            Html::printAjaxPager('',$start,$number);
            echo "</td>";
            echo "</tr>";
            echo "</table>";

         echo "</td>";
         echo "</tr>";
         echo "</table>";
      }
      echo "</center>";
      return true;
   }



   /**
    * Display javascript functions for history
    */
   function javascriptHistory() {
      global $CFG_GLPI;
      
            echo "<script  type='text/javascript'>
function close_array(id){
   document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/collapse.png\''+
      'onClick=\'document.getElementById(\"viewfollowup'+id+'\").hide();appear_array('+id+');\' />&nbsp;<img src=\'".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/refresh.png\' />';
   document.getElementById('plusmoins'+id).style.backgroundColor = '#e4e4e2';
}
function appear_array(id){
   document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/expand.png\''+
      'onClick=\'document.getElementById(\"viewfollowup'+id+'\").show();close_array('+id+');\' />';
   document.getElementById('plusmoins'+id).style.backgroundColor = '#f2f2f2';

}

      </script>";
     
      echo "<script type='text/javascript' src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/prototype.js'></script>";
      echo "<script type='text/javascript' src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/effects.js'></script>";

      
   }


   
   /**
   * Display each history line
   *
   * @param $taskjobstatus_id integer id of the taskjobstatus
   *
   * @return nothing
   *
   **/
   function showHistoryLines($taskjobstatus_id, $displayprocess = 1, $displaytaskjob=0, $nb_td='5') {
      global $LANG,$CFG_GLPI;
      
      $pfTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $pfTaskjobstatus->getFromDB($taskjobstatus_id);
      $pfAgent = new PluginFusioninventoryAgent();
      
      $displayforceend = 0;
      $a_history = $this->find('`plugin_fusioninventory_taskjobstatus_id` = "'.$pfTaskjobstatus->fields['id'].'"', 
                               'id DESC',
                               '1');

      echo "<tr class='tab_bg_1'>";
      echo "<td width='40' id='plusmoins".$pfTaskjobstatus->fields["id"]."'><img src='".$CFG_GLPI['root_doc'].
               "/plugins/fusioninventory/pics/expand.png' onClick='document.getElementById(\"viewfollowup".$pfTaskjobstatus->fields["id"].
               "\").show();close_array(".$pfTaskjobstatus->fields["id"].");' /></td>";
      
      echo "<td>";
      echo $pfTaskjobstatus->fields['uniqid'];
      echo "</td>";
      if ($displayprocess == '1') {
         echo "<td>";
         echo $pfTaskjobstatus->fields['id'];
         echo "</td>";
      }
      if ($displaytaskjob == '1') {
         $pfTaskjob = new PluginFusioninventoryTaskjob();
         $pfTask = new PluginFusioninventoryTask();
         $pfTaskjob->getFromDB($pfTaskjobstatus->fields['plugin_fusioninventory_taskjobs_id']);
         $pfTask->getFromDB($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);
         echo "<td>";
         echo $pfTaskjob->getLink(1)." (".$pfTask->getLink().")";
         echo "</td>";
      }
      echo "<td>";
      $pfAgent->getFromDB($pfTaskjobstatus->fields['plugin_fusioninventory_agents_id']);
      echo $pfAgent->getLink(1);
      
      Ajax::UpdateItemOnEvent('plusmoins'.$pfTaskjobstatus->fields["id"],
                      'viewfollowup'.$pfTaskjobstatus->fields["id"],
                      $CFG_GLPI['root_doc']."/plugins/fusioninventory/ajax/showtaskjoblogdetail.php",
                      array('agents_id' => $pfTaskjobstatus->fields['plugin_fusioninventory_agents_id'],
                          'uniqid' => $pfTaskjobstatus->fields['uniqid']),
                      array("click"));
      
      echo "</td>";
      $a_return = $this->displayHistoryDetail(array_pop($a_history), 0);
      $count = $a_return[0];
      $displayforceend += $count;
      echo $a_return[1];

      if ($displayforceend == "0") {
         echo "<td align='center'>";
         echo "<form name='form' method='post' action='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/taskjob.form.php'>";
         echo "<input type='hidden' name='taskjobstatus_id' value='".$pfTaskjobstatus->fields['id']."' />";
         echo "<input type='hidden' name='taskjobs_id' value='".$pfTaskjobstatus->fields['plugin_fusioninventory_taskjobs_id']."' />";
         echo '<input name="forceend" value="'.$LANG['plugin_fusioninventory']['task'][32].'"
             class="submit" type="submit">';
         echo "</form>";
         echo "</td>";
      }      
      echo "</tr>";

      echo "<tr><td colspan='".$nb_td."' style='display: none;' id='viewfollowup".$pfTaskjobstatus->fields["id"]."' class='tab_bg_4'>";

      echo "</td></tr>";
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
      global $CFG_GLPI,$LANG;

      $pfTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $pfAgent = new PluginFusioninventoryAgent();

      $text = "<center><table class='tab_cadrehov' style='width: ".$width."px'>";

      $a_jobstatus = $pfTaskjobstatus->find('`plugin_fusioninventory_agents_id`="'.$agents_id.'" AND `uniqid`="'.$uniqid.'"', '`id` DESC');
      $a_devices_merged = array();

      foreach ($a_jobstatus as $data) {

         $displayforceend = 0;
         $a_history = $this->find('`plugin_fusioninventory_taskjobstatus_id` = "'.$data['id'].'"', 'id');

         if (strstr(exportArrayToDB($a_history), "Merged with ")) {
            $classname = $data['itemtype'];
            $Class = new $classname;
            $Class->getFromDB($data['items_id']);
            $a_devices_merged[] = $Class->getLink(1)."&nbsp;(".$Class->getTypeName().")";
         } else {
            $text .= "<tr>";
            $text .= "<th colspan='2'><img src='".$CFG_GLPI['root_doc']."/pics/puce.gif' />".$LANG['plugin_fusioninventory']['processes'][38]."&nbsp;: ".$data['id']."</th>";
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
            $text .= "<th colspan='2'>";
            $text .= $LANG['plugin_fusioninventory']['agents'][28];
            $text .= "</th>";
            $a_return = $this->displayHistoryDetail(array_shift($a_history));
            $count = $a_return[0];
            $text .= $a_return[1];
            $displayforceend += $count;
            $text .= "</tr>";

            $text .= "<tr class='tab_bg_1'>";
            $text .= "<td colspan='2'>";
            $pfAgent->getFromDB($data['plugin_fusioninventory_agents_id']);
            $text .= $pfAgent->getLink(1);
            $text .= "</td>";
            $a_return = $this->displayHistoryDetail(array_shift($a_history));
            $count = $a_return[0];
            $text .= $a_return[1];
            $displayforceend += $count;
            $text .= "</tr>";

            $text .= "<tr class='tab_bg_1'>";
            $text .= "<th colspan='2'>";
            $text .= $LANG['plugin_fusioninventory']['task'][27];
            $text .= "</th>";
            $a_return = $this->displayHistoryDetail(array_shift($a_history));
            $count = $a_return[0];
            $text .= $a_return[1];
            $displayforceend += $count;
            $text .= "</tr>";

            $text .= "<tr class='tab_bg_1'>";
            $text .= "<td colspan='2'>";
            if (!empty($data["itemtype"])) {
               $device = new $data["itemtype"]();
               $device->getFromDB($data["items_id"]);
               $text .= $device->getLink(1);
               $text .= "&nbsp;";
               $text .= "(".$device->getTypeName().")";
            }
            $text .= "</td>";
            $a_return = $this->displayHistoryDetail(array_shift($a_history));
            $count = $a_return[0];
            $text .= $a_return[1];
            $displayforceend += $count;
            $text .= "</tr>";


            while (count($a_history) != 0) {
               if (count($a_devices_merged) > 0) {
                  $text .= "<tr class='tab_bg_1'>";
                  $text .= "<td colspan='2'>";
                  $text .= array_pop($a_devices_merged);
                  $text .= "</td>";
                  $a_return = $this->displayHistoryDetail(array_shift($a_history));
                  $count = $a_return[0];
                  $text .= $a_return[1];
                  $displayforceend += $count;
                  $text .= "</tr>";                  
               } else {
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
            }
            $display = 1;
            while (count($a_devices_merged) != 0) {
               $text .= "<tr class='tab_bg_1'>";
               $text .= "<td colspan='2'>";
               $text .= array_pop($a_devices_merged);
               $text .= "</td>";
               if ($display == "1") {
                  $text .= "<td colspan='3' rowspan='".(count($a_devices_merged) + 1)."'></td>";
                  $display = 0;
               }
               $text .= "</tr>";
            }

            $text .= "<tr class='tab_bg_4'>";
            $text .= "<td colspan='5' height='4'>";
            $text .= "</td>";
            $text .= "</tr>";
         }
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
   function displayHistoryDetail($datas, $comment=1) {
      global $LANG;

      $text = "<td align='center'>";
      $text .= Html::convDateTime($datas['date']);
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
            $text .= "<td style='background-color: rgb(0, 255, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][2]."</strong>";
            $finish++;
            break;

         case 3 :
            $text .= "<td style='background-color: rgb(255, 120, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][3]."</strong>";
            $finish++;
            break;

         case 4 :
            $text .= "<td style='background-color: rgb(255, 0, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][4]."</strong>";
            $finish++;
            break;

         case 5 :
            $text .= "<td style='background-color: rgb(255, 200, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][5]."</strong>";
            $finish++;
            break;

         case 6 :
            $text .= "<td style='background-color: rgb(255, 200, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][6]."</strong>";
            break;

         default:
            $text .= "<td>";
            break;
      }

      $text .= "</td>";
      if ($comment == '1') {
         $text .= "<td align='center'>";
         $matches = array();
         // Search for replace [[itemtype::items_id]] by link
         preg_match_all("/\[\[(.*)\:\:(.*)\]\]/", $datas['comment'], $matches);
         foreach($matches[0] as $num=>$commentvalue) {
            $classname = $matches[1][$num];
            if ($classname != '') {
               $Class = new $classname;
               $Class->getFromDB($matches[2][$num]);
               $datas['comment'] = str_replace($commentvalue, $Class->getLink(), $datas['comment']);
            }
         }
         // Search for code to display lang traduction ==pluginname::9876==
         preg_match_all("/==(\w*)\:\:([0-9]*)==/", $datas['comment'], $matches);
         foreach($matches[0] as $num=>$commentvalue) {
            $datas['comment'] = str_replace($commentvalue, $LANG['plugin_'.$matches[1][$num]]["codetasklog"][$matches[2][$num]], $datas['comment']);
         }
         $datas['comment'] = str_replace(",[", "<br/>[", $datas['comment']);
         $text .= $datas['comment'];
         $text .= "</td>";
      }
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
      global $LANG,$DB;

      $finishState = array();
      $finishState[2] = 0;
      $finishState[3] = 0;
      $finishState[4] = 0;
      $finishState[5] = 0;

      $query = "SELECT `glpi_plugin_fusioninventory_taskjoblogs`.`state`
         FROM glpi_plugin_fusioninventory_taskjobstatus
         LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs` on plugin_fusioninventory_taskjobstatus_id=`glpi_plugin_fusioninventory_taskjobstatus`.`id`
         WHERE `plugin_fusioninventory_taskjobs_id`='".$taskjobs_id."'
         AND (`glpi_plugin_fusioninventory_taskjoblogs`.`state` = '2' 
            OR `glpi_plugin_fusioninventory_taskjoblogs`.`state` = '3'
            OR `glpi_plugin_fusioninventory_taskjoblogs`.`state` = '4' 
            OR `glpi_plugin_fusioninventory_taskjoblogs`.`state` = '5')
         GROUP BY glpi_plugin_fusioninventory_taskjobstatus.uniqid,plugin_fusioninventory_agents_id";
      $result=$DB->query($query);
      if ($result) {
         while ($datajob=$DB->fetch_array($result)) {
            $finishState[$datajob['state']]++;
         }
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

   
   
   /**
    * Get taskjobstatus by uniqid
    * 
    * @param type $uuid value uniqid
    * 
    * @return array
    */
   static function getByUniqID($uuid) {
      $results = getAllDatasFromTable('glpi_plugin_fusioninventory_taskjobstatus',
                                      "`uniqid`='$uuid'");
      foreach ($results as $result) {
         return $result;
      }
      return array();
   }
}

?>