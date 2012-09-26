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

class PluginFusioninventoryTaskjoblog extends CommonDBTM {

   /*
    * Define different state
    */

   const TASK_STARTED            = 1;
   const TASK_OK                 = 2;
   const TASK_ERROR_OR_REPLANNED = 3;
   const TASK_ERROR              = 4;
   const TASK_UNKNOWN            = 5;
   const TASK_RUNNING            = 6;
   const TASK_PREPARED           = 7;


   
   /**
    * return array with state mapping name
    * 
    * @return array with all elements
    */
   function dropdownStateValues() {
      global $LANG;
      
      $elements = array();
      $elements[7] = $LANG['plugin_fusioninventory']['taskjoblog'][7];
      $elements[1] = $LANG['plugin_fusioninventory']['taskjoblog'][1];
      $elements[6] = $LANG['plugin_fusioninventory']['taskjoblog'][6];
      $elements[2] = $LANG['plugin_fusioninventory']['taskjoblog'][2];
      $elements[3] = $LANG['plugin_fusioninventory']['taskjoblog'][3];
      $elements[4] = $LANG['plugin_fusioninventory']['taskjoblog'][4];
      $elements[5] = $LANG['plugin_fusioninventory']['taskjoblog'][5];
      
      return $elements;
   }
   
   

   /**
    * Return name of state
    * 
    * @param type $states_id
    * 
    * @return string name of state number
    */
   function getState($states_id) {
      $elements = $this->dropdownStateValues();
      return $elements[$states_id];
   }
   
   
   
   static function getStateItemtype($taskjoblogs_id) {
      global $DB;
      
      $query = "SELECT * FROM glpi_plugin_fusioninventory_taskjobstates
         LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs` 
            ON `plugin_fusioninventory_taskjobstates_id`=`glpi_plugin_fusioninventory_taskjobstates`.`id`
         WHERE `glpi_plugin_fusioninventory_taskjoblogs`.`id`='".$taskjoblogs_id."'
         LIMIT 1";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         return $data["itemtype"];
      }
      return '';      
   }
   
   
   
   function getSearchOptions() {
      global $LANG;

      $sopt = array();

      $sopt['common'] = $LANG['Menu'][30];

      $sopt[1]['table']         = $this->getTable();
      $sopt[1]['field']         = 'id';
      $sopt[1]['name']          = $LANG['common'][2];
      $sopt[1]['massiveaction'] = false; // implicit field is id
      
      $sopt[2]['table']          = 'glpi_plugin_fusioninventory_tasks';
      $sopt[2]['field']          = 'name';
      $sopt[2]['name']           = $LANG['plugin_fusioninventory']['task'][18];
      $sopt[2]['datatype']       = 'itemlink';
      $sopt[2]['itemlink_type']  = "PluginFusioninventoryTask";

      $sopt[3]['table']          = 'glpi_plugin_fusioninventory_taskjobs';
      $sopt[3]['field']          = 'name';
      $sopt[3]['name']           = $LANG['plugin_fusioninventory']['task'][2];
      $sopt[3]['datatype']       = 'itemlink';
      $sopt[3]['itemlink_type']  = "PluginFusioninventoryTaskjob";
     
      $sopt[4]['table']          = $this->getTable();
      $sopt[4]['field']          = 'state';
      $sopt[4]['name']           = $LANG['joblist'][0];
      $sopt[4]['searchtype']     = 'equals';
      
      $sopt[5]['table']         = $this->getTable();
      $sopt[5]['field']         = 'date';
      $sopt[5]['name']          = $LANG['common'][27];
      $sopt[5]['datatype']      = 'datetime';
      $sopt[5]['massiveaction'] = false;
      
      $sopt[6]['table']          = 'glpi_plugin_fusioninventory_taskjobstates';
      $sopt[6]['field']          = 'uniqid';
      $sopt[6]['name']           = $LANG['plugin_fusioninventory']['task'][47];
      $sopt[6]['datatype']       = 'string';
      
      $sopt[7]['table']          = $this->getTable();
      $sopt[7]['field']          = 'comment';
      $sopt[7]['name']           = $LANG['common'][25];
      $sopt[7]['datatype']       = 'string';
      
      $sopt[8]['table']          = "glpi_plugin_fusioninventory_taskjobstates";
      $sopt[8]['field']          = 'plugin_fusioninventory_agents_id';
      $sopt[8]['name']           = $LANG['plugin_fusioninventory']['agents'][28];
      
      return $sopt;
   }

 
   
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
      $query = 'SELECT * FROM `glpi_plugin_fusioninventory_taskjobstates`
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

      // ***** Display for statejob OK
      if (count($a_uniqid) > 0) {
         $where .= " AND `uniqid` NOT IN ('".implode("','", $a_uniqid)."')";
         $query = 'SELECT * FROM `glpi_plugin_fusioninventory_taskjobstates`
            WHERE `plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'"
               AND `state`!="3"
               '.$where.'
            GROUP BY uniqid,plugin_fusioninventory_agents_id
            ORDER BY `id` DESC';
      }
      $querycount = 'SELECT count(*) AS cpt FROM `glpi_plugin_fusioninventory_taskjobstates`
         WHERE `plugin_fusioninventory_taskjobs_id`="'.$taskjobs_id.'"
            AND `state`="3"
            '.$where.'
         GROUP BY uniqid,plugin_fusioninventory_agents_id';
      $resultcount = $DB->query($querycount);
      $a_datacount = $DB->fetch_assoc($resultcount);
      $number = $a_datacount['cpt'];
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
   * @param $taskjobstates_id integer id of the taskjobstate
   *
   * @return nothing
   *
   **/
   function showHistoryLines($taskjobstates_id, $displayprocess = 1, $displaytaskjob=0, $nb_td='5') {
      global $LANG,$CFG_GLPI;
      
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfAgent        = new PluginFusioninventoryAgent();
      
      $pfTaskjobstate->getFromDB($taskjobstates_id);
      
      $displayforceend = 0;
      $a_history = $this->find('`plugin_fusioninventory_taskjobstates_id` = "'.$pfTaskjobstate->fields['id'].'"', 
                               'id DESC',
                               '1');

      echo "<tr class='tab_bg_1'>";
      echo "<td width='40' id='plusmoins".$pfTaskjobstate->fields["id"]."'><img src='".$CFG_GLPI['root_doc'].
               "/plugins/fusioninventory/pics/expand.png' onClick='document.getElementById(\"viewfollowup".$pfTaskjobstate->fields["id"].
               "\").show();close_array(".$pfTaskjobstate->fields["id"].");' /></td>";
      
      echo "<td>";
      echo $pfTaskjobstate->fields['uniqid'];
      echo "</td>";
      if ($displayprocess == '1') {
         echo "<td>";
         echo $pfTaskjobstate->fields['id'];
         echo "</td>";
      }
      if ($displaytaskjob == '1') {
         $pfTaskjob = new PluginFusioninventoryTaskjob();
         $pfTask    = new PluginFusioninventoryTask();
         
         $pfTaskjob->getFromDB($pfTaskjobstate->fields['plugin_fusioninventory_taskjobs_id']);
         $pfTask->getFromDB($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);
         echo "<td>";
         echo $pfTaskjob->getLink(1)." (".$pfTask->getLink().")";
         echo "</td>";
      }
      echo "<td>";
      $pfAgent->getFromDB($pfTaskjobstate->fields['plugin_fusioninventory_agents_id']);
      echo $pfAgent->getLink(1);
      
      Ajax::UpdateItemOnEvent('plusmoins'.$pfTaskjobstate->fields["id"],
                      'viewfollowup'.$pfTaskjobstate->fields["id"],
                      $CFG_GLPI['root_doc']."/plugins/fusioninventory/ajax/showtaskjoblogdetail.php",
                      array('agents_id' => $pfTaskjobstate->fields['plugin_fusioninventory_agents_id'],
                          'uniqid' => $pfTaskjobstate->fields['uniqid']),
                      array("click"));
      
      echo "</td>";
      $a_return = $this->displayHistoryDetail(array_pop($a_history), 0);
      $count = $a_return[0];
      $displayforceend += $count;
      echo $a_return[1];

      if ($displayforceend == "0") {
         echo "<td align='center'>";
         echo "<form name='form' method='post' action='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/taskjob.form.php'>";
         echo "<input type='hidden' name='taskjobstates_id' value='".$pfTaskjobstate->fields['id']."' />";
         echo "<input type='hidden' name='taskjobs_id' value='".$pfTaskjobstate->fields['plugin_fusioninventory_taskjobs_id']."' />";
         echo '<input name="forceend" value="'.$LANG['plugin_fusioninventory']['task'][32].'"
             class="submit" type="submit">';
         Html::closeForm();
         echo "</td>";
      }      
      echo "</tr>";

      echo "<tr>";
      echo "<td colspan='".$nb_td."' style='display: none;' id='viewfollowup".$pfTaskjobstate->fields["id"]."' class='tab_bg_4'>";
      echo "</td>";
      echo "</tr>";
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

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfAgent        = new PluginFusioninventoryAgent();

      $text = "<center><table class='tab_cadrehov' style='width: ".$width."px'>";

      $a_jobstates = $pfTaskjobstate->find('`plugin_fusioninventory_agents_id`="'.$agents_id.'" AND `uniqid`="'.$uniqid.'"', '`id` DESC');
      $a_devices_merged = array();

      foreach ($a_jobstates as $data) {

         $displayforceend = 0;
         $a_history = $this->find('`plugin_fusioninventory_taskjobstates_id` = "'.$data['id'].'"', 'id');

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
            $text .= "<sup>(".(count($a_devices_merged) + 1).")</sup>";
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

                  while (count($a_history) != 0) {
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
   * @param $comment boolean 0/1 display comment or not 
   *
   * @return array
   *               - boolean 0/1 if this log = finish
   *               - text to display 
   *
   **/
   function displayHistoryDetail($datas, $comment=1) {
      global $LANG;

      $text = "<td align='center'>";
      $text .= Html::convDateTime($datas['date']);
      $text .= "</td>";
      $finish = 0;

      switch ($datas['state']) {

         case 7:
            $text .= "<td align='center'>";
            $text .= $LANG['plugin_fusioninventory']['taskjoblog'][7];
            break;

         case 1:
            $text .= "<td align='center'>";
            $text .= $LANG['plugin_fusioninventory']['taskjoblog'][1];
            break;

         case 2:
            $text .= "<td style='background-color: rgb(0, 255, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][2]."</strong>";
            $finish++;
            break;

         case 3:
            $text .= "<td style='background-color: rgb(255, 120, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][3]."</strong>";
            $finish++;
            break;

         case 4:
            $text .= "<td style='background-color: rgb(255, 0, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][4]."</strong>";
            $finish++;
            break;

         case 5:
            $text .= "<td style='background-color: rgb(255, 200, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
            $text .= "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][5]."</strong>";
            $finish++;
            break;

         case 6:
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
               $item = new $classname;
               $item->getFromDB($matches[2][$num]);
               $datas['comment'] = str_replace($commentvalue, $item->getLink(), $datas['comment']);
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
   * @param $state value state of this taskjobstate
   * @param $comment value the comment of this insertion
   *
   * @return nothing
   *
   **/
   function addTaskjoblog($taskjobs_id, $items_id, $itemtype, $state, $comment) {

      $this->getEmpty();
      unset($this->fields['id']);
      $this->fields['plugin_fusioninventory_taskjobstates_id'] = $taskjobs_id;
      $this->fields['date']      = date("Y-m-d H:i:s");
      $this->fields['items_id']  = $items_id;
      $this->fields['itemtype']  = $itemtype;
      $this->fields['state']     = $state;
      $this->fields['comment']   = $comment;

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
         FROM glpi_plugin_fusioninventory_taskjobstates
         LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs` on plugin_fusioninventory_taskjobstates_id=`glpi_plugin_fusioninventory_taskjobstates`.`id`
         WHERE `plugin_fusioninventory_taskjobs_id`='".$taskjobs_id."'
         AND (`glpi_plugin_fusioninventory_taskjoblogs`.`state` = '2' 
            OR `glpi_plugin_fusioninventory_taskjoblogs`.`state` = '3'
            OR `glpi_plugin_fusioninventory_taskjoblogs`.`state` = '4' 
            OR `glpi_plugin_fusioninventory_taskjoblogs`.`state` = '5')
         GROUP BY glpi_plugin_fusioninventory_taskjobstates.uniqid,plugin_fusioninventory_agents_id";
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
    * Get taskjobstate by uniqid
    * 
    * @param type $uuid value uniqid
    * 
    * @return array with data of table glpi_plugin_fusioninventory_taskjobstates
    */
   static function getByUniqID($uuid) {
      $a_datas = getAllDatasFromTable('glpi_plugin_fusioninventory_taskjobstates',
                                      "`uniqid`='$uuid'",
                                      "1");
      foreach ($a_datas as $a_data) {
         return $a_data;
      }
      return array();
   }
   
   

   /**
    * Display short logs
    *
    * @param $taskjobs_id integer id of taskjob
    * @param $veryshort boolean activation to have very very short display
    * 
    * @return nothing
    */
   function displayShortLogs($taskjobs_id, $veryshort=0) {
      global $DB,$CFG_GLPI,$LANG;
      
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      
      echo "<td colspan='2' valign='top'>";
      
      if ($veryshort == '0') {
         echo "<table width='100%'>";
         echo "<tr class='tab_bg_3'>";
      } else {
         echo "<table>";
         echo "<tr class='tab_bg_1'>";
      }      
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_taskjobstates`
         WHERE `plugin_fusioninventory_taskjobs_id`='".$taskjobs_id."'
         ORDER BY `uniqid` DESC
         LIMIT 1";
      $result=$DB->query($query);
      $uniqid = 0;
      while ($data=$DB->fetch_array($result)) {
         $uniqid = $data['uniqid'];         
      }
      
      $query = "SELECT `glpi_plugin_fusioninventory_taskjoblogs`.* FROM `glpi_plugin_fusioninventory_taskjoblogs` 
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobstates` 
            ON plugin_fusioninventory_taskjobstates_id = `glpi_plugin_fusioninventory_taskjobstates`.`id`
         WHERE `uniqid`='".$uniqid."'
         ORDER BY `glpi_plugin_fusioninventory_taskjoblogs`.`id` DESC
         LIMIT 1";
      $state = 0;      
      $date = '';
      $comment = '';
      $taskstates_id = 0;
      
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $state = $data['state'];
         $date = $data['date'];
         $comment = $data['comment'];
         $taskstates_id = $data['plugin_fusioninventory_taskjobstates_id'];
      }
      
      if (strstr($comment, "Merged with")) {
         $state = '7';
      }      

      $a_taskjobstates = count($pfTaskjobstate->find("`plugin_fusioninventory_taskjobs_id`='".$taskjobs_id."'
               AND `state` != '3'
               AND `uniqid`='".$uniqid."'"));
      
      if (    $state == '1'
           OR $state == '6'
           OR $state == '7') { // not finish
         
         if ($veryshort == '0') {
            echo "<th>";
            echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/task_running.png'/>";
            echo "</th>";
         }
         echo $this->getDivState($state, 'td');
         echo "<td align='center'>";
         echo " <a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/taskjoblog.php?sort=1&order=DESC&field[0]=6&searchtype[0]=contains&contains[0]=".$uniqid."&itemtype=PluginFusioninventoryTaskjoblog&start=0'>".
            $LANG['plugin_fusioninventory']['taskjoblog'][9]."</a>";
         echo "<input type='hidden' name='taskjobstates_id' value='".$taskstates_id."' />";
         echo "<input type='hidden' name='taskjobs_id' value='".$taskjobs_id."' />";
         echo '&nbsp;&nbsp;&nbsp;<input name="forceend" value="'.$LANG['plugin_fusioninventory']['task'][32].'"
             class="submit" type="submit">';
         echo "</td>";
         if ($veryshort == '0') {
            echo "</tr>";
            echo "<tr class='tab_bg_3'>";
            echo "<th>";
            echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/task_finished.png'/>";
            echo "</th>";
            echo "<td colspan='2' align='center'>";
            echo " <a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/taskjoblog.php?sort=1&order=DESC&field[0]=3&searchtype[0]=equals&contains[0]=".$taskjobs_id."&itemtype=PluginFusioninventoryTaskjoblog&start=0'>".
               $LANG['plugin_fusioninventory']['taskjoblog'][8]."</a>";
            echo "</td>";
            echo "</tr>";
         }
      } else { // Finish
         if ($veryshort == '0') {
            echo "<th rowspan='2' height='64'>";
            echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/task_finished.png'/>";
            echo "</th>";
         }
         echo $this->getDivState($state, 'td');
         if ($veryshort == '0') {
            echo "<td align='center'>";
         } else {
            echo "<td>";
         }
         if ($taskstates_id == '0') {
            echo $LANG['crontask'][40]."&nbsp;:&nbsp;".$LANG['setup'][307];
         } else {
            
            if ($veryshort == '0') {
               if ($a_taskjobstates == '0') {
                  echo $LANG['crontask'][40]." (".Html::convDateTime($date).") : ";
               }
               echo "<a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/taskjoblog.php?field[0]=6&searchtype[0]=contains&contains[0]=".$uniqid."&itemtype=PluginFusioninventoryTaskjoblog&start=0'>".
                  $LANG['plugin_fusioninventory']['taskjoblog'][9]."</a>";
            } else {
               if ($a_taskjobstates == '0') {
                  echo $LANG['crontask'][40]." :<br/> ".Html::convDateTime($date)."";
               }
            }
         }
         if ($a_taskjobstates != '0') {
            echo "<input type='hidden' name='taskjobstates_id' value='".$taskstates_id."' />";
            echo "<input type='hidden' name='taskjobs_id' value='".$taskjobs_id."' />";
            echo '&nbsp;&nbsp;&nbsp;<input name="forceend" value="'.$LANG['plugin_fusioninventory']['task'][32].'"
                class="submit" type="submit">';
         }
         echo "</td>";
         echo "</tr>";
         if ($veryshort == '0') {
            echo "<tr class='tab_bg_3'>";
            echo "<td colspan='2' align='center'>";
            echo " <a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/taskjoblog.php?field[0]=3&searchtype[0]=equals&contains[0]=".$taskjobs_id."&itemtype=PluginFusioninventoryTaskjoblog&start=0'>".
               $LANG['plugin_fusioninventory']['taskjoblog'][8]."</a>";
            echo "</td>";
            echo "</tr>";
         }
      }
      echo "</table>";
      
      echo "</td>";
   }
   
   
   
   /**
    * Get div with text/color depend on state
    * 
    * @param $state integer state number
    * @param $type string div / td
    * 
    * @return string complete node (openned and closed)
    */
   function getDivState($state, $type='div') {
      global $LANG;

      $width = '50';
      
      switch ($state) {

         case 7:
            return "<".$type." align='center' width='".$width."'>".$LANG['plugin_fusioninventory']['taskjoblog'][7]."</".$type.">";
            break;

         case 1:
            return "<".$type." align='center' width='".$width."'>".$LANG['plugin_fusioninventory']['taskjoblog'][1]."</".$type.">";
            break;

         case 2:
            return "<".$type." style='background-color: rgb(0, 255, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center' width='".$width."'>".
               "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][2]."</strong></".$type.">";
            break;

         case 3:
            return "<".$type." style='background-color: rgb(255, 120, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center' width='".$width."'>".
               "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][3]."</strong></".$type.">";
            break;

         case 4:
            return "<".$type." style='background-color: rgb(255, 0, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center' width='".$width."'>".
               "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][4]."</strong></".$type.">";
            break;

         case 5:
            return "<".$type." style='background-color: rgb(255, 200, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center' width='".$width."'>".
               "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][5]."</strong></".$type.">";
            break;

         case 6:
            return "<".$type." style='background-color: rgb(255, 200, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center' width='".$width."'>".
               "<strong>".$LANG['plugin_fusioninventory']['taskjoblog'][6]."</strong></".$type.">";
            break;

      }
   }
   
   
   
   /**
    * Display quick list logs
    * 
    * @param $tasks_id integer id of task
    * 
    * @return nothing
    */
   static function quickListLogs($tasks_id) {
      global $DB,$LANG;
      
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_taskjobstates` 
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs`
            ON `plugin_fusioninventory_taskjobs_id` = `glpi_plugin_fusioninventory_taskjobs`.`id`
         WHERE `plugin_fusioninventory_tasks_id`='".$tasks_id."'
         ORDER BY uniqid DESC
         LIMIT 1";
      $result = $DB->query($query);
      $uniqid = 0;
      $action = '';
      while ($data=$DB->fetch_array($result)) {
         $uniqid = $data['uniqid'];
         $action = $data['action'];
      }
      if ($uniqid == '0') {
         if ($action == '') {
            echo "<center><strong>No agent found for this task</strong></center>";
         }
      } else {
         $params = array();
         $params['field'][0]      = '6';
         $params['searchtype'][0] = 'contains';
         $params['contains'][0]   = $uniqid;
         $params['itemtype']      = 'PluginFusioninventoryTaskjoblog';
         $params['start']         = '0';
         Search::manageGetValues('PluginFusioninventoryTaskjoblog');
         Search::showList('PluginFusioninventoryTaskjoblog', $params);
      }      
   }
}

?>