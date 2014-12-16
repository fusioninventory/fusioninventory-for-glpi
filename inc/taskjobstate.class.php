<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2014 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

class PluginFusioninventoryTaskjobstate extends CommonDBTM {

   /*
    * Taskjobstate status definitions
    */

   const PREPARED             = 0;  // the job is just prepared and waiting for agent request
   const SERVER_HAS_SENT_DATA = 1;  // the job is running and the server sent the job config
   const AGENT_HAS_SENT_DATA  = 2;  // the job is running and the agent sent reply to the server
   const FINISHED             = 3;  // the agent completed successfully the job
   const IN_ERROR             = 4;  // the agent failed to complete the job
   const CANCELLED            = 5;  // the job has been cancelled either by a user or the agent
                                    // himself (eg. if it has been forbidden to run this taskjob)

   public $method = '';

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $CFG_GLPI;

      $tab_names = array();
      $tab_names[] = __("Job executions");
      //Return tab names if list is not empty
      if (!empty($tab_names)) {
         return $tab_names;
      } else {
         return '';
      }
   }

   static function getStateNames() {
      return array(
         self::PREPARED => __('Prepared', 'fusioninventory'),
         self::SERVER_HAS_SENT_DATA => __('Server has sent data to the agent', 'fusioninventory'),
         self::AGENT_HAS_SENT_DATA => __('Agent replied with data to the server', 'fusioninventory'),
         self::FINISHED => __('Finished', 'fusioninventory'),
         self::IN_ERROR => __('Error' , 'fusioninventory'),
         self::CANCELLED => __('Cancelled', 'fusioninventory')
      );
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      global $DB;


      if ($item->getType() == 'PluginFusioninventoryTask' ) {
         $item->showJobLogs();
      }
   }
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
      $a_taskjobstates = $this->find("`plugin_fusioninventory_taskjobs_id`='".
                                        $taskjobs_id."' AND `state`!='".self::FINISHED."'");
      $total = 0;
      if (count($a_taskjobstates) > 0) {

         foreach ($a_taskjobstates as $data) {
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
               Html::displayProgressBar($width, ceil($globalState), array('simple' => 1));
            } else {
               Html::displayProgressBar($width, ceil($globalState));
            }
         } else if ($return == 'htmlvar') {
            if ($style == 'simple') {
               return PluginFusioninventoryDisplay::getProgressBar($width, ceil($globalState),
                                                                   array('simple' => 1));
            } else {
               return PluginFusioninventoryDisplay::getProgressBar($width, ceil($globalState));
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
      global $DB, $CFG_GLPI;

      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $icon = "";
      $title = "";

      $pfTaskjoblog->javascriptHistory();

      switch ($state) {

         case 'running':
            $search = " AND `state`!='".self::FINISHED."'";
            $title = __('Running tasks', 'fusioninventory');
            $icon = "<img src='".$CFG_GLPI['root_doc'].
                    "/plugins/fusioninventory/pics/task_running.png'/>";
            break;

         case 'finished':
            $search = " AND `state`='".self::FINISHED."'";
            $title = __('Finished tasks', 'fusioninventory');
            $icon = "<img src='".$CFG_GLPI['root_doc'].
                        "/plugins/fusioninventory/pics/task_finished.png'/>";
            break;

         case 'all':
            $search = "";
            $title = _n('Task', 'Tasks', 2);
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
         echo "<th>".__('Unique id', 'fusioninventory')."</th>";
         echo "<th>".__('Job', 'fusioninventory')."</th>";
         echo "<th>".__('Agent', 'fusioninventory')."</th>";
         echo "<th>";
         echo __('Date');
         echo "</th>";
         echo "<th>";
         echo __('Status');
         echo "</th>";
         $nb_td = 6;
         if ($state == 'running') {
            $nb_td++;
            echo "<th>";
            echo __('Comments');
            echo "</th>";
         }
         echo "</tr>";
         foreach ($a_taskjobs as $data) {
            $pfTaskjoblog->showHistoryLines($data['id'], 0, 1, $nb_td);
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
   * @param $id integer id of the taskjobstate
   * @param $state value state to set
   *
   * @return nothing
   *
   * TODO: There is no need to pass $id since we should use this method with an instantiated
   * object!!
   **/
   function changeStatus($id, $state) {
      $input = array();
      $input['id'] = $id;
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

      $pfTaskjob = new PluginFusioninventoryTaskjob();

      $moduleRun = array();

      $a_taskjobstates = $this->find("`plugin_fusioninventory_agents_id`='".$agent_id.
                                     "' AND `state`='".self::PREPARED."'",
                                     "`id`");
      foreach ($a_taskjobstates as $data) {
         // Get job and data to send to agent
         if ($pfTaskjob->getFromDB($data['plugin_fusioninventory_taskjobs_id'])) {

            $pluginName = PluginFusioninventoryModule::getModuleName($pfTaskjob->fields['plugins_id']);
            if ($pluginName) {
               $className = "Plugin".ucfirst($pluginName).ucfirst($pfTaskjob->fields['method']);
               $moduleRun[$className][] = $data;
            }
         }
      }
      return $moduleRun;
   }

   /**
    * Process ajax parameters for getLogs() methods
    *
    * since 0.85+1.0
    * @param $options  array of ajax expected 'id' and 'last_date' parameters
    * @return a json encoded list of logs grouped by jobstates
    */

   function ajaxGetLogs($params) {
      $result = array();

      $id = null;
      $last_date = null;

      if( isset($params['id']) and $params['id'] > 0){
         $id = $params['id'];
      }
      if( isset($params['last_date'])){
         $last_date = $params['last_date'];
      }

      if (!is_null($id) and !is_null($last_date)) {
         echo json_encode($this->getLogs($id, $last_date));
      }

   }

   /**
    * Get logs associated to a jobstate.
    *
    * since 0.85+1.0
    */

   function getLogs( $id, $last_date ) {
      global $DB;
      $fields = array(
         'log.id' => 0,
         'log.date' => 1,
         'log.comment' => 2,
         'log.state' => 3,
         'run.id' => 4,
         );
      $query = implode("\n", array(
         "SELECT log.`id` AS 'log.id',",
         "log.`date` AS 'log.date',",
         "log.`comment` AS 'log.comment',",
         "log.`state` AS 'log.state',",
         "run.`uniqid` AS 'run.id'",
         "FROM `glpi_plugin_fusioninventory_taskjoblogs` AS log",
         "LEFT JOIN `glpi_plugin_fusioninventory_taskjobstates` AS run",
         "ON run.`id` = log.`plugin_fusioninventory_taskjobstates_id`",
         "WHERE run.`id` = ".$id,
         "AND log.`date` <= '". $last_date . "'",
         "ORDER BY log.`id` DESC"
      ));


      $res = $DB->query($query);
      $logs = array();
      while( $result = $res->fetch_row() ) {
         $run_id = $result[$fields['run.id']];
         $logs['run']  = $run_id;
         $logs['logs'][] = array(
            'log.id'      => $result[$fields['log.id']],
            'log.comment' => PluginFusioninventoryTaskjoblog::convertComment($result[$fields['log.comment']]),
            'log.date'    => $result[$fields['log.date']],
            'log.state'   => $result[$fields['log.state']]
         );
      }
      return $logs;
   }

   /**
   * Change the status to finish
   *
   * @param $taskjobstates_id integer id of the taskjobstates
   * @param $items_id integer id of the item
   * @param $itemtype value type of the item
   * @param $error bool error
   * @param $message value message for the status
   * @param $unknown bool unmanaged or not device
   *
   * @return nothing
   *
   **/
   function changeStatusFinish($taskjobstates_id, $items_id, $itemtype, $error=0, $message='',
                               $unmanaged=0, $reinitialize=1) {
      global $DB;

      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTask = new PluginFusioninventoryTask();

      $this->getFromDB($taskjobstates_id);
      $input = array();
      $input['id'] = $this->fields['id'];
      $input['state'] = self::FINISHED;

      $log_input = array();
      if ($unmanaged ==  "1") {
         $log_input['state'] = PluginFusioninventoryTaskjoblog::TASK_UNKNOWN;
         $input['state'] = self::FINISHED;
      } else if ($error == "1") {
         $log_input['state'] = PluginFusioninventoryTaskjoblog::TASK_ERROR;
         $input['state'] = self::IN_ERROR;
      } else {
         $log_input['state'] = PluginFusioninventoryTaskjoblog::TASK_OK;
         $input['state'] = self::FINISHED;
      }

      $this->update($input);
      $log_input['plugin_fusioninventory_taskjobstates_id'] = $taskjobstates_id;
      $log_input['items_id'] = $items_id;
      $log_input['itemtype'] = $itemtype;
      $log_input['date'] = date("Y-m-d H:i:s");
      $log_input['comment'] = $message;
      $pfTaskjoblog->add($log_input);

      $pfTaskjob->getFromDB($this->fields['plugin_fusioninventory_taskjobs_id']);
   }


   function fail($reason='') {
      $log = new PluginFusioninventoryTaskjoblog();

      $log_input = array(
         'plugin_fusioninventory_taskjobstates_id' => $this->fields['id'],
         'items_id' => $this->fields['items_id'],
         'itemtype' => $this->fields['itemtype'],
         'date' => date("Y-m-d H:i:s"),
         'state' => PluginFusioninventoryTaskjoblog::TASK_ERROR,
         'comment' => Toolbox::addslashes_deep($reason)
      );

      $log->add($log_input);

      $this->update(array(
         'id' => $this->fields['id'],
         'state' => self::IN_ERROR
         ));
   }

   function cancel($reason='') {

      $log = new PluginFusioninventoryTaskjoblog();

      $log_input = array(
         'plugin_fusioninventory_taskjobstates_id' => $this->fields['id'],
         'items_id' => $this->fields['items_id'],
         'itemtype' => $this->fields['itemtype'],
         'date' => date("Y-m-d H:i:s"),
         'state' => PluginFusioninventoryTaskjoblog::TASK_INFO,
         'comment' => Toolbox::addslashes_deep($reason)
      );

      $log->add($log_input);

      $this->update(array(
         'id' => $this->fields['id'],
         'state' => self::CANCELLED
         ));
   }

   /**
    * Cron for clean taskjob
    *
    * @return nothing
    */
   static function cronCleantaskjob() {
      global $DB;

      $config = new PluginFusioninventoryConfig();
      $retentiontime = $config->getValue('delete_task');
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $sql = "SELECT * FROM `glpi_plugin_fusioninventory_taskjoblogs`
         WHERE  `date` < date_add(now(), interval -".$retentiontime." day)
         GROUP BY `plugin_fusioninventory_taskjobstates_id`";
      $result=$DB->query($sql);
      if ($result) {
         while ($data=$DB->fetch_array($result)) {
            $pfTaskjobstate->getFromDB($data['plugin_fusioninventory_taskjobstates_id']);
            $pfTaskjobstate->delete($pfTaskjobstate->fields, 1);
            $sql_delete = "DELETE FROM `glpi_plugin_fusioninventory_taskjoblogs`
               WHERE `plugin_fusioninventory_taskjobstates_id` = '".
                    $data['plugin_fusioninventory_taskjobstates_id']."'";
            $DB->query($sql_delete);
         }
      }
   }
}

?>
