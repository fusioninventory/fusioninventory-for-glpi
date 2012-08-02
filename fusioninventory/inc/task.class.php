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

class PluginFusioninventoryTask extends CommonDBTM {

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['task'][1];
   }


   
   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "task", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "task", "r");
   }


   
   function getSearchOptions() {
      global $LANG;

      $sopt = array();

      $sopt['common'] = $LANG['plugin_fusioninventory']['task'][0];

      $sopt[1]['table']          = $this->getTable();
      $sopt[1]['field']          = 'name';
      $sopt[1]['linkfield']      = 'name';
      $sopt[1]['name']           = $LANG['common'][16];
      $sopt[1]['datatype']       = 'itemlink';

      $sopt[2]['table']          = $this->getTable();
      $sopt[2]['field']          = 'date_scheduled';
      $sopt[2]['linkfield']      = 'date_scheduled';
      $sopt[2]['name']           = $LANG['plugin_fusioninventory']['task'][14];
      $sopt[2]['datatype']       = 'datetime';

      $sopt[3]['table']          = 'glpi_entities';
      $sopt[3]['field']          = 'completename';
      $sopt[3]['linkfield']      = 'entities_id';
      $sopt[3]['name']           = $LANG['entity'][0];
  
      $sopt[4]['table']          = $this->getTable();
      $sopt[4]['field']          = 'comment';
      $sopt[4]['linkfield']      = 'comment';
      $sopt[4]['name']           = $LANG['common'][25];

      $sopt[5]['table']          = $this->getTable();
      $sopt[5]['field']          = 'is_active';
      $sopt[5]['linkfield']      = 'is_active';
      $sopt[5]['name']           = $LANG['common'][60];
      $sopt[5]['datatype']       = 'bool';

      $sopt[6]['table']          = $this->getTable();
      $sopt[6]['field']          = 'communication';
      $sopt[6]['linkfield']      = '';
      $sopt[6]['name']           = $LANG['plugin_fusioninventory']['task'][33];

      $sopt[8]['table']          = 'glpi_plugin_fusioninventory_taskjoblogs';
      $sopt[8]['field']          = 'state';
      $sopt[8]['name']           = 'Running';
      
      $sopt[30]['table']          = $this->getTable();
      $sopt[30]['field']          = 'id';
      $sopt[30]['linkfield']      = '';
      $sopt[30]['name']           = $LANG['common'][2];

      return $sopt;
   }


   
   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      $ong[1] = $LANG['title'][26];

      if ($this->fields['id'] > 0) {
         if ($this->fields["is_advancedmode"] == '1') {
            $pft = new PluginFusioninventoryTaskjob;
            $a_taskjob = $pft->find("`plugin_fusioninventory_tasks_id`='".$_GET['id']."'
                  AND `rescheduled_taskjob_id`='0' ", "id");
            $i = 1;
            foreach($a_taskjob as $datas) {
               $i++;
               $ong[$i] = $LANG['plugin_fusioninventory']['task'][2]." ".($i-1);
            }

            $i++;
            $ong[$i] = $LANG['plugin_fusioninventory']['task'][15]." <img src='".$CFG_GLPI['root_doc']."/pics/add_dropdown.png'/>";
         }
      }
      return $ong;
   }



   /**
   * Display form for task configuration
   *
   * @param $items_id integer ID of the task
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   function showForm($id, $options=array()) {
      global $LANG;

      $pFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      
      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }
      
      $options['colspan'] = 2;
      $this->showTabs($options);
      $this->showFormHeader($options);
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";

      $pfTaskjob = new PluginFusioninventoryTaskjob;
      $a_taskjob = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$id."'", "id");
      echo "<td>";
      echo $LANG['common'][60]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      if (count($a_taskjob) > 0) {
         Dropdown::showYesNo("is_active",$this->fields["is_active"]);
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      $display_but = 0;
      if ($this->fields["is_active"]) {
         if ($id!='') {
            $forcerundisplay = 1;
            $a_taskjobs = $pFusioninventoryTaskjob->find("`plugin_fusioninventory_tasks_id`='".$id."'");
            foreach ($a_taskjobs as $data) {
               $statejob = $pfTaskjobstate->stateTaskjob($data['id'], '930', 'value');
               if ($statejob != '') {
                  $forcerundisplay = 0;
               }
            }
            if ($forcerundisplay == '1') {
               echo '<th colspan="2">';
               echo '<input name="forcestart" value="'.$LANG['plugin_fusioninventory']['task'][40].'"
                      class="submit" type="submit">';
               echo '</th>';
               $display_but = 1;
            }
         }
         
         // * Manage reset / reinitialization
         $reset = 0;
         $a_taskjobs = $pFusioninventoryTaskjob->find("`plugin_fusioninventory_tasks_id`='".$id."'");
         foreach ($a_taskjobs as $data) {
            $statejob = $pfTaskjobstate->stateTaskjob($data['id'], '930', 'value');
            if ($statejob == '') {
               if ($data['execution_id'] != $this->fields['execution_id']) {
                  $reset = 1;
               }
            }
         }
         if ($reset == '1') {
            echo '<th colspan="2">';
            echo '<input name="reset" value="'.$LANG['plugin_fusioninventory']['task'][46].'"
                   class="submit" type="submit">';
            echo '</th>';
            $display_but = 1;
         }
      }
      if ($display_but == '0') {
         echo "<td colspan='2'></td>";
      }
      
      echo "<td>".$LANG['plugin_fusioninventory']['task'][14]."&nbsp;:</td>";
      echo "<td>";
      if ($id) {
         Html::showDateTimeFormItem("date_scheduled",$this->fields["date_scheduled"],1,false);
      } else {
         Html::showDateTimeFormItem("date_scheduled",date("Y-m-d H:i:s"),1);
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td rowspan='2'>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td rowspan='2'>";
      echo "<textarea cols='39' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][33]."&nbsp;:</td>";
      echo "<td>";
      $com = array();
      $com['push'] = $LANG['plugin_fusioninventory']['task'][41];
      $com['pull'] = $LANG['plugin_fusioninventory']['task'][42];
      Dropdown::showFromArray("communication", $com, array('value'=>$this->fields["communication"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][17]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showInteger("periodicity_count", $this->fields['periodicity_count'], 0, 300);
      $a_time = array();
      $a_time[] = "------";
      $a_time['minutes'] = $LANG['job'][22];
      $a_time['hours'] = ucfirst($LANG['gmt'][1]);
      $a_time['days'] = ucfirst($LANG['calendar'][12]);
      $a_time['months'] = ucfirst($LANG['calendar'][14]);
      Dropdown::showFromArray("periodicity_type", $a_time, array('value'=>$this->fields['periodicity_type']));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][49]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showYesNo("is_advancedmode",$this->fields["is_advancedmode"]);
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }



   /**
   * Purge task and taskjob
   *
   * @param $parm object to purge
   *
   * @return nothing
   *
   **/
   static function purgeTask($parm) {
      // $parm["id"]
      $pfTaskjob = new PluginFusioninventoryTaskjob();

      // all taskjobs
      $a_taskjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$parm->fields["id"]."'");
      foreach($a_taskjobs as $a_taskjob) {
         $pfTaskjob->delete($a_taskjob, 1);
      }
   }



   /**
   * Purge task and taskjob related with method 
   *
   * @param $method value name of the method
   *
   * @return nothing
   *
   **/
   static function cleanTasksbyMethod($method) {
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTask = new PluginFusioninventoryTask();

      $a_taskjobs = $pfTaskjob->find("`method`='".$method."'");
      $task_id = 0;
      foreach($a_taskjobs as $a_taskjob) {
         $pfTaskjob->delete($a_taskjob, 1);
         if (($task_id != $a_taskjob['plugin_fusioninventory_tasks_id'])
            AND ($task_id != '0')) {

            // Search if this task have other taskjobs, if not, we will delete it
            $findtaskjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$task_id."'");
            if (count($findtaskjobs) == '0') {
               $pfTask->delete(array('id'=>$task_id), 1);
            }
         }
         $task_id = $a_taskjob['plugin_fusioninventory_tasks_id'];         
      }
      if ($task_id != '0') {

         // Search if this task have other taskjobs, if not, we will delete it
         $findtaskjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$task_id."'");
         if (count($findtaskjobs) == '0') {
            $pfTask->delete(array('id'=>$task_id), 1);
         }
      }
   }
   
   
   
   function taskMenu() {
      global $DB,$CFG_GLPI,$LANG;

      $resultTasksPlanned = $this->getTasksPlanned();
      $resultTasksRunning = $this->getTasksRunning();
      $resultTasksInerror = $this->getTasksInerror();
      $a_tasksActives = $this->find("`is_active` = '1' ".getEntitiesRestrictRequest("AND", 'glpi_plugin_fusioninventory_tasks'));
      $a_tasksInactives = $this->find("`is_active` = '0' ".getEntitiesRestrictRequest("AND", 'glpi_plugin_fusioninventory_tasks'));
      $a_tasksAll = $this->find(getEntitiesRestrictRequest("", 'glpi_plugin_fusioninventory_tasks'));

      
      if (!isset($_GET['see'])) {
         if ($DB->numrows($resultTasksPlanned) > 0) {
            $_GET['see'] = 'next';
         } else if ($DB->numrows($resultTasksRunning) > 0) {
            $_GET['see'] = 'running';
         } else if ($DB->numrows($resultTasksInerror) > 0) {
            $_GET['see'] = 'inerror';
         } else if (count($a_tasksActives) > 0) {
            $_GET['see'] = 'actives';
         } else {
            $_GET['see'] = 'all';
         }
      }
      
      Session::initNavigateListItems($this->getType());

      unset($_GET['field']);
      unset($_GET['searchtype']);
      unset($_GET['contains']);
      unset($_GET['itemtype']);
      $_GET['reset'] = 'reset';
      if ($_GET['see'] == 'actives') {
         $_GET['field'] = array('5');
         $_GET['searchtype'] = array('equals');
         $_GET['contains'] = array('1');
         $_GET['itemtype'] = array('PluginFusioninventoryTask');
      } else if ($_GET['see'] == 'inactives') {
         $_GET['field'] = array('5');
         $_GET['searchtype'] = array('equals');
         $_GET['contains'] = array('0');
         $_GET['itemtype'] = array('PluginFusioninventoryTask');
      }
      
      Search::manageGetValues($this->getType());
      
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      
      // ** Get task in next execution
      $cell = 'td';
      if ($_GET['see'] == 'next') {
         $cell = 'th';
      }
      echo "<".$cell." align='center'><a href='".$_SERVER['PHP_SELF']."?see=next'>".
              $LANG['plugin_fusioninventory']['task'][56]."<sup>(".
              $DB->numrows($resultTasksPlanned).")</sup></a></".$cell.">";

      // ** Get task running 
      $cell = 'td';
      if ($_GET['see'] == 'running') {
         $cell = 'th';
      }
      echo "<".$cell." align='center'><a href='".$_SERVER['PHP_SELF']."?see=running'>".
              $LANG['plugin_fusioninventory']['taskjoblog'][6]."<sup>(".
              $DB->numrows($resultTasksRunning).")</sup></a></".$cell.">";
            
      // ** Get task in error
      $cell = 'td';
      if ($_GET['see'] == 'inerror') {
         $cell = 'th';
      }
      echo "<".$cell." align='center'><a href='".$_SERVER['PHP_SELF']."?see=inerror'>".
              $LANG['plugin_fusioninventory']['task'][58]."<sup>(".
              $DB->numrows($resultTasksInerror).")</sup></a></".$cell.">";

      // ** Get task active
      $cell = 'td';
      if ($_GET['see'] == 'actives') {
         $cell = 'th';
      }
      echo "<".$cell." align='center'><a href='".$_SERVER['PHP_SELF']."?see=actives'>".$LANG['common'][60]."<sup>(".
              count($a_tasksActives).")</sup></a></".$cell.">";
      
      // ** Get task inactive
      $cell = 'td';
      if ($_GET['see'] == 'inactives') {
         $cell = 'th';
      }
      echo "<".$cell." align='center'><a href='".$_SERVER['PHP_SELF']."?see=inactives'>".$LANG['rulesengine'][107]."<sup>(".
              count($a_tasksInactives).")</sup></a></".$cell.">";
      
      // ** Get all task
      $cell = 'td';
      if ($_GET['see'] == 'all') {
         $cell = 'th';
      }
      echo "<".$cell." align='center'><a href='".$_SERVER['PHP_SELF']."?see=all'>".$LANG['common'][66]."<sup>(".
              count($a_tasksAll).")</sup></a></".$cell.">";
      
      echo "</tr>";
      echo "</table>";
      

      echo "<div class='center' id='searchform' style='display:none'>";
      
//      Search::show($this->getType());
      Search::manageGetValues($this->getType());
      Search::showGenericSearch($this->getType(), $_GET);
      
      echo "</div>";
   }

   
   
   function displayTaks($condition) {
      global $DB,$LANG;

      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      
      echo "<table class='tab_cadrehov'>";
      
      $where = "";
      switch ($condition) {
         
         case 'next':
            $result = $this->getTasksPlanned();
            break;

         case 'running':
            $result = $this->getTasksRunning();
            break;
         
         case 'inerror':
            $result = $this->getTasksInerror();
            break;

         case 'actives':
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_tasks`
               WHERE `is_active`='1' ".getEntitiesRestrictRequest("AND", 'glpi_plugin_fusioninventory_tasks');
            $result = $DB->query($query);
            break;
         
         case 'inactives':
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_tasks`
               WHERE `is_active`='0' ".getEntitiesRestrictRequest("AND", 'glpi_plugin_fusioninventory_tasks');
            $result = $DB->query($query);
            break;
         
         case 'all':
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_tasks`
               WHERE ".getEntitiesRestrictRequest("", 'glpi_plugin_fusioninventory_tasks');
            $result = $DB->query($query);
            break;

      }
      while ($data_task=$DB->fetch_array($result)) {
         $this->getFromDB($data_task['id']);
         Session::addToNavigateListItems($this->getType(), $data_task['id']);
         echo "<tr class='tab_bg_1'>";
         echo "<td width='32'>";
         $conditionpic = $condition;
         if ($this->fields['is_active'] == '0') {
            $conditionpic = 'inactives';
         } else if ($DB->numrows($this->getTasksPlanned($this->fields['id'])) > 0) {
            $conditionpic = 'next';
         } else if ($DB->numrows($this->getTasksRunning($this->fields['id'])) > 0){
            $conditionpic = 'running';
         }
         
         if ($conditionpic == 'next') {
            echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/task_scheduled.png'/></td>";
         } else if ($conditionpic == 'inactives') {
            echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/task_disabled.png'/></td>";
         } else if ($conditionpic == 'actives') {
            echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/task_enabled.png'/></td>";
         } else if ($conditionpic == 'running') {
            echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/task_running.png'/></td>";
         } else {
            
         }
         echo "<td>
            <a href='".$this->getFormURL()."?id=".$data_task['id']."' style='font-size: 16px; '>"
                 .$this->getName()."</a> (".ucfirst($data_task['communication'])." ";
         if ($data_task['communication'] == "push") {
            Html::showToolTip($LANG['plugin_fusioninventory']['task'][41]);
         } else if ($data_task['communication'] == "pull") {
            Html::showToolTip($LANG['plugin_fusioninventory']['task'][42]);
         }
         echo ")<br/>&nbsp;";
         $a_taskjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$data_task['id']."'");
         if (count($a_taskjobs) > 1) {
            foreach ($a_taskjobs as $data_taskjob) {
               $pfTaskjob->getFromDB($data_taskjob['id']);
               echo "| ".$pfTaskjob->getLink(1)." ";
            }
            if (count($a_taskjobs) > 0) {
               echo "|";
            }
         }
         echo "</td>";
         
         echo "<td>".$LANG['crontask'][41]." : <br/>".$data_task['date_scheduled']."</td>";

         $queryt = "SELECT * FROM `glpi_plugin_fusioninventory_taskjobstates`
            LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` 
               ON `plugin_fusioninventory_taskjobs_id`=`glpi_plugin_fusioninventory_taskjobs`.`id`
            WHERE `plugin_fusioninventory_tasks_id`='".$data_task['id']."'
            ORDER BY `uniqid`
            LIMIT 1";
          $resultt = $DB->query($queryt);
          if ($DB->numrows($resultt) != 0) {
              $datat = $DB->fetch_assoc($resultt);
              $pfTaskjoblog->displayShortLogs($datat['plugin_fusioninventory_taskjobs_id'], 1);
          } else {
             echo "<td>".$LANG['crontask'][40]." :<br/>
                ".$LANG['setup'][307]."</td>";             
          }
         
         echo "</tr>";
      }
      
      echo "</table>";      
   }
   
   
   
   function getTasksRunning($tasks_id=0) {
      global $DB;
      
      $where = '';
      $where .= getEntitiesRestrictRequest("AND", 'task');
      if ($tasks_id > 0) {
         $where = " AND task.`id`='".$tasks_id."'
            LIMIT 1 "; 
      }
      
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_tasks` as task
         WHERE execution_id != 
            (SELECT execution_id FROM glpi_plugin_fusioninventory_taskjobs as taskjob
               WHERE taskjob.`plugin_fusioninventory_tasks_id`=task.`id`
               ORDER BY execution_id DESC 
               LIMIT 1
            )".$where;
      return $DB->query($query);
   }

   
   function getTasksPlanned($tasks_id=0) {
      global $DB;
      
      $where = '';
      $where .= getEntitiesRestrictRequest("AND", 'task');
      if ($tasks_id > 0) {
         $where = " AND task.`id`='".$tasks_id."'
            LIMIT 1 "; 
      }

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_tasks` as task
         WHERE execution_id = 
            (SELECT execution_id FROM glpi_plugin_fusioninventory_taskjobs as taskjob
               WHERE taskjob.`plugin_fusioninventory_tasks_id`=task.`id`
               ORDER BY execution_id DESC 
               LIMIT 1
            )
            AND `is_active`='1'
            AND `periodicity_count` > 0 
            AND `periodicity_type` != '0' ".$where;
      return $DB->query($query);
   }
   
   
   
   function getTasksInerror() {      
      global $DB;
      
      $where = '';
      $where .= getEntitiesRestrictRequest("AND", 'glpi_plugin_fusioninventory_tasks');
      
      $query = "SELECT `glpi_plugin_fusioninventory_tasks`.*
         FROM `glpi_plugin_fusioninventory_tasks`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` AS taskjobs ON `plugin_fusioninventory_tasks_id` = `glpi_plugin_fusioninventory_tasks`.`id`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobstates` AS taskjobstates ON taskjobstates.`id` = 
            (SELECT id
             FROM glpi_plugin_fusioninventory_taskjobstates
             WHERE plugin_fusioninventory_taskjobs_id = taskjobs.`id`
             ORDER BY id DESC
             LIMIT 1
            )
         LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs` ON `glpi_plugin_fusioninventory_taskjoblogs`.`id` = 
            (SELECT `id` 
            FROM `glpi_plugin_fusioninventory_taskjoblogs`
            WHERE `plugin_fusioninventory_taskjobstates_id`= taskjobstates.`id`
            ORDER BY id DESC LIMIT 1 )
         WHERE `glpi_plugin_fusioninventory_taskjoblogs`.`state`='4'
         ".$where."
         GROUP BY plugin_fusioninventory_tasks_id
         ORDER BY `glpi_plugin_fusioninventory_taskjoblogs`.`date` DESC";
     
      
      
      return $DB->query($query);
   }
   
   
   
   function menuTasksLogs() {
      global $CFG_GLPI,$LANG;
      
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      
      $cell = 'td';
      if (strstr($_SERVER['PHP_SELF'],'/tasksummary.')) {
         $cell ='th';
      }
      echo "<".$cell." align='center' width='33%'>";
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/tasksummary.php'>".
              $LANG['plugin_fusioninventory']['task'][1]." (".$LANG['state'][1].")</a>";
      echo "</".$cell.">";
      
      $cell = 'td';
      if (strstr($_SERVER['PHP_SELF'],'/task.')) {
         $cell ='th';
      }
      echo "<".$cell." align='center' width='33%'>";
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/task.php'>".
              $LANG['plugin_fusioninventory']['task'][1]." (".$LANG['setup'][135].")</a>";
      echo "</".$cell.">";
      
      $cell = 'td';
      if (strstr($_SERVER['PHP_SELF'],'/taskjoblog.')) {
         $cell ='th';
      }
      echo "<".$cell." align='center'>";
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/taskjoblog.php'>".$LANG['Menu'][30]."</a>";
      echo "</".$cell.">";
      echo "</tr>";
      echo "</table>";
      
   }
}

?>