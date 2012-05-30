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

class PluginFusioninventoryTaskjobstatus extends CommonDBTM {

   /*
    * Define different state
    *
    * 0 : task prepared : not data yet sent
    * 1 : server has sent data to agent
    * 2 : return of agent data and update glpi
    * 3 : finish
    */

   const PREPARED             = 0;
   const SERVER_HAS_SENT_DATA = 1;
   const AGENT_HAS_SENT_DATA  = 2;
   const FINISHED             = 3;

   
   
   /**
   * Display state of taskjob
   *
   * @param $taskjobs_id integer id of the taskjob
   * @param $width integer how large in pixel display array
   * @param $return value display or return in var (html or htmlvar or other value
   *        to have state number in %)
   * @param $style '' = normal or 'simple' for very simple display
   *
   * @return nothing, html or pourcentage value
   *
   **/
   function stateTaskjob ($taskjobs_id, $width = '930', $return = 'html', $style = '') {

      $state = array();
      $state[0] = 0;
      $state[1] = 0;
      $state[2] = 0;
      $state[3] = 0;
      $a_taskjobstatus = $this->find("`plugin_fusioninventory_taskjobs_id`='".
                                        $taskjobs_id."' AND `state`!='".self::FINISHED."'");
      $total = 0;
      if (count($a_taskjobstatus) > 0) {

         foreach ($a_taskjobstatus as $data) {
            $total++;
            $state[$data['state']]++;
         }
         $globalState = 0;
         if ($total == '0') {
            $globalState = 0;
         } else {
            $first = 25;
            $second = ((($state[1]+$state[2]+$state[3]) * 100) / $total) / 4;
            $third = ((($state[2]+$state[3]) * 100) / $total) / 4;
            $fourth = (($state[3] * 100) / $total) / 4;
            $globalState = $first + $second + $third + $fourth;
         }
         if ($return == 'html') {
            if ($style == 'simple') {
               displayProgressBar($width,ceil($globalState), array('simple' => 1));
            } else {
               displayProgressBar($width,ceil($globalState));
            }
         } else if ($return == 'htmlvar') {
            if ($style == 'simple') {
               return PluginFusioninventoryDisplay::getProgressBar($width,ceil($globalState),
                                                                   array('simple' => 1));
            } else {
               return PluginFusioninventoryDisplay::getProgressBar($width,ceil($globalState));
            }
         } else {
            return ceil($globalState);
         }
      }
   }



   /**
   * Display state of an item of a taskjob
   *
   * @param $items_id integer id of the item
   * @param $itemtype value type of the item
   * @param $state value (all or each state : running, finished, nostarted)
   *
   * @return nothing
   *
   **/
   function stateTaskjobItem($items_id, $itemtype, $state='all') {
      global $DB,$LANG, $CFG_GLPI;

      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $icon = "";
      $title = "";

      $PluginFusioninventoryTaskjoblog->javascriptHistory();

      switch ($state) {

         case 'running':
            $search = " AND `state`!='".self::FINISHED."'";
            $title = $LANG['plugin_fusioninventory']['task'][19];
            $icon = "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/task_running.png'/>";
            break;

         case 'finished':
            $search = " AND `state`='".self::FINISHED."'";
            $title = $LANG['plugin_fusioninventory']['task'][20];
            $icon = "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/task_finished.png'/>";
            break;

         case 'all':
            $search = "";
            $title = $LANG['plugin_fusioninventory']['task'][18];
            $icon = "";
            break;

      }
      if (!isset($search)) {
         return;
      }

      $a_taskjobs = array();
      if (isset($search)) {
         $query = "SELECT * FROM `".$this->getTable()."`
                   WHERE `items_id`='".$items_id."' AND `itemtype`='".$itemtype."'".$search."
                   ORDER BY `".$this->getTable()."`.`id` DESC";
         $a_taskjobs = array();
         $result = $DB->query($query);
         if ($result) {
            while ($data=$DB->fetch_array($result)) {
               $a_taskjobs[] = $data;
            }
         }
      }

      echo "<div align='center'>";
      echo "<table  class='tab_cadre' width='950'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th  width='32'>";
      echo $icon;
      echo "</th>";
      echo "<td>";
      if (count($a_taskjobs) > 0) {
         echo "<table class='tab_cadre' width='950'>";
         echo "<tr>";
         echo "<th></th>";
         echo "<th>".$LANG['plugin_fusioninventory']['task'][47]."</th>";
         echo "<th>".$LANG['plugin_fusioninventory']['task'][2]."</th>";
         echo "<th>".$LANG['plugin_fusioninventory']['agents'][28]."</th>";
         echo "<th>";
         echo $LANG['common'][27];
         echo "</th>";
         echo "<th>";
         echo $LANG['joblist'][0];
         echo "</th>";
         $nb_td = 6;
         if ($state == 'running') {
            $nb_td++;
            echo "<th>";
            echo $LANG['common'][25];
            echo "</th>";
         }
         echo "</tr>";
         foreach ($a_taskjobs as $data) {
            $PluginFusioninventoryTaskjoblog->showHistoryLines($data['id'], 0, 1, $nb_td);
         }
         echo "</table>";
      }
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "<br/>";

      echo "</div>";
   }



   /**
   * Change the state
   *
   * @param $id integer id of the statusjobstatus
   * @param $state value state to set
   *
   * @return nothing
   *
   **/
   function changeStatus($id, $state) {
      $this->getFromDB($id);
      $input = $this->fields;
      $input['state'] = $state;
      $this->update($input);      
   }



   /**
   * Get taskjobs of an agent
   *
   * @param $agent_id integer id of the agent
   *
   * @return nothing
   *
   **/
   function getTaskjobsAgent($agent_id) {

      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();

      $moduleRun = array();

      $a_taskjobstatus = $this->find("`plugin_fusioninventory_agents_id`='".$agent_id.
                                     "' AND `state`='".self::PREPARED."'",
                                     "`id`");
      foreach ($a_taskjobstatus as $data) {
         // Get job and data to send to agent
         $PluginFusioninventoryTaskjob->getFromDB($data['plugin_fusioninventory_taskjobs_id']);

         $pluginName = PluginFusioninventoryModule::getModuleName($PluginFusioninventoryTaskjob->fields['plugins_id']);
         if ($pluginName) {
            $className = "Plugin".ucfirst($pluginName).ucfirst($PluginFusioninventoryTaskjob->fields['method']);
            $moduleRun[$className][] = $data;
         }         
      }
      return $moduleRun;
   }



   /**
   * Change the status to finish
   *
   * @param $taskjobstatus integer id of the taskjobstatus
   * @param $items_id integer id of the item
   * @param $itemtype value type of the item
   * @param $error bool error
   * @param $message value message for the status
   * @param $unknown bool unknown or not device
   *
   * @return nothing
   *
   **/
   function changeStatusFinish($taskjobstatus, $items_id, $itemtype, $error=0, $message='', $unknown=0, $reinitialize=1) {
      global $DB;
      
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $pFusioninventoryTask = new PluginFusioninventoryTask();

      $this->getFromDB($taskjobstatus);
      $input = array();
      $input['id'] = $this->fields['id'];
      $input['state'] = 3;
      $this->update($input);
            
      $a_input = array();
      if ($unknown ==  "1") {
         $a_input['state'] = 5;
      } else if ($error == "1") {
         // Check if we have retry
         $PluginFusioninventoryTaskjob->getFromDB($this->fields['plugin_fusioninventory_taskjobs_id']);
         if($PluginFusioninventoryTaskjob->fields['retry_nb'] > 0) {
            // 1. Calculate start timeof the task
            $period = 0;
            $period = $PluginFusioninventoryTaskjob->periodicityToTimestamp(
                    $PluginFusioninventoryTaskjob->fields['periodicity_type'], 
                    $PluginFusioninventoryTaskjob->fields['periodicity_count']);
            $query = "SELECT *, UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp
                  FROM `".$pFusioninventoryTask->getTable()."`
               WHERE `id`='".$PluginFusioninventoryTaskjob->fields['plugin_fusioninventory_tasks_id']."' 
                  LIMIT 1";
            $result = $DB->query($query);
            $data_task = $DB->fetch_assoc($result);
            $start_taskjob = $data_task['date_scheduled_timestamp'] + $period;
            // 2. See how errors in taskjobstatus
            $query = "SELECT * FROM `".$this->getTable()."`
               LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs` on `plugin_fusioninventory_taskjobstatus_id` = `".$this->getTable()."`.`id`
               WHERE `plugin_fusioninventory_taskjobs_id`='".$this->fields['plugin_fusioninventory_taskjobs_id']."'
                     AND `glpi_plugin_fusioninventory_taskjoblogs`.`state`='3'
                     AND `date`>='".date("Y-m-d H:i:s",$start_taskjob)."'
                     AND `uniqid` != '".$this->fields['uniqid']."'
               GROUP BY `uniqid`";
            $result = $DB->query($query);
            if ($DB->numrows($result) >= ($PluginFusioninventoryTaskjob->fields['retry_nb'] - 1)) {
               $a_input['state'] = 4;
            } else {
               // Replanification
               $a_input['state'] = 3;
            }
         } else {
          $a_input['state'] = 4;
         }
      } else {
         $a_input['state'] = 2;
      }
      $a_input['plugin_fusioninventory_taskjobstatus_id'] = $taskjobstatus;
      $a_input['items_id'] = $items_id;
      $a_input['itemtype'] = $itemtype;
      $a_input['date'] = date("Y-m-d H:i:s");
      $a_input['comment'] = $message;
      $PluginFusioninventoryTaskjoblog->add($a_input);

      $PluginFusioninventoryTaskjob->getFromDB($this->fields['plugin_fusioninventory_taskjobs_id']);
      $input = array();
      $input['id'] = $this->fields['plugin_fusioninventory_taskjobs_id'];
      $input['status'] = 0;
      $PluginFusioninventoryTaskjob->update($input);
      if ($reinitialize == '1') {
         $PluginFusioninventoryTaskjob->reinitializeTaskjobs($PluginFusioninventoryTaskjob->fields['plugin_fusioninventory_tasks_id']);
      }
   }

   

   /**
    * Cron for clean taskjob
    * 
    * @return nothing
    */
   static function cronCleantaskjob() {
      global $DB;

      $retentiontime = PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'delete_task');
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $sql = "SELECT * FROM `glpi_plugin_fusioninventory_taskjoblogs`
         WHERE  `date` < date_add(now(),interval -".$retentiontime." day)
         GROUP BY `plugin_fusioninventory_taskjobstatus_id`";
      $result=$DB->query($sql);
      if ($result) {
         while ($data=$DB->fetch_array($result)) {
            $PluginFusioninventoryTaskjobstatus->getFromDB($data['plugin_fusioninventory_taskjobstatus_id']);
            $PluginFusioninventoryTaskjobstatus->delete($PluginFusioninventoryTaskjobstatus->fields, 1);
            $sql_delete = "DELETE FROM `glpi_plugin_fusioninventory_taskjoblogs`
               WHERE `plugin_fusioninventory_taskjobstatus_id` = '".$data['plugin_fusioninventory_taskjobstatus_id']."'";
            $DB->query($sql_delete);
         }
      }
   }
}

?>
