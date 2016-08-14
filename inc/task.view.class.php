<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the display part of tasks.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @author    Kevin Roy
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Manage the display part of tasks.
 */
class PluginFusioninventoryTaskView extends PluginFusioninventoryCommonView {

   /**
    * __contruct function where initialize base URLs
    */
   function __construct() {
      parent::__construct();
      $this->base_urls = array_merge( $this->base_urls, array(
         'fi.job.logs' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_logs.php",
      ));
   }



   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      $tab_names = array();

      if ($this->can("task", "r")) {
         if ($item->getType() == 'Computer') {
            return __('FusInv', 'fusioninventory').' '. _n('Task', 'Tasks', 2);
         }
      }
      return '';
   }



   /**
    * Define tabs to display on form page
    *
    * @param array $options
    * @return array containing the tabs name
    */
   function defineTabs($options=array()) {
      $ong = array();

      $this->addDefaultFormTab($ong);

      return $ong;
   }



   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      if ($item->getType() == 'Computer') {
         echo "<b>".__('To Be Done', 'fusioninventory')."</b>";
         return TRUE;
      }
      return FALSE;
   }



   /**
    * Show job logs
    */
   function showJobLogs() {
      $refresh_intervals = array(
         "off"  => __('Off', 'fusioninventory'),
         "1"    => '1 '._n('second','seconds',1),
         "5"    => '5 '._n('second','seconds',5),
         "10"   => '10 '._n('second', 'seconds', 10),
         "60"   => '1 '._n('minute', 'minutes', 1),
         "120"  => '2 '._n('minute', 'minutes', 2),
         "300"  => '5 '._n('minute', 'minutes', 5),
         "600"  => '10 '._n('minute', 'minutes', 10),
      );
      echo "<div class='fusinv_panel'>";
      echo "   <div class='fusinv_form large'>";
      $refresh_randid = $this->showDropdownFromArray(
         __("refresh interval", "fusioninventory"),
         null,
         $refresh_intervals,
         array(
            'value' => 'off', // set default to 10 seconds
            'width' => '20%'
         )
      );
      // Add a manual refresh button
      echo "      <div class='refresh_button submit'>";
      echo "      <span></span></div>";
      echo "   </div>"; // end of fusinv_form

      echo "</div>";

      //$pfTaskjob = new PluginFusioninventoryTaskjob();
      //$taskjobs = $pfTaskjob->find(
      //   "`plugin_fusioninventory_tasks_id`='".$this->fields['id']."'",
      //   "id"
      //);

      // Template structure for tasks' blocks
      echo implode("\n", array(
         "<script id='template_task' type='x-tmpl-mustache'>",
         "<div id='{{task_id}}' class='task_block {{expanded}}'>",
         "  <h3>".__("Task",'fusioninventory')." <span class='task_name'>{{task_name}}</span></h3>",
         "  <div class='jobs_block'></div>",
         "</div>",
         "</script>"
      ));

      // Template structure for jobs' blocks
      echo implode("\n", array(
         "<script id='template_job' type='x-tmpl-mustache'>",
         "<div id='{{job_id}}' class='job_block'>",
         "  <div class='refresh_button submit'><span></span></div>",
         "  <h3 class='job_name'>{{job_name}}</h3>",
         "  <div class='targets_block'></div>",
         "</div>",
         "</script>"
      ));

      // Template structure for targets' blocks
      echo implode("\n", array(
         "<script id='template_target' type='x-tmpl-mustache'>",
         "<div id='{{target_id}}' class='target_block'>",
         "  <div class='target_details'>",
         "  <div class='target_infos'>",
         "     <h4 class='target_name'>",
         "        <a target='_blank' href={{target_link}}>",
         "          {{target_name}}",
         "        </a>",
         "     </h4>",
         "     <div class='target_stats'>",
         "     </div>",
         "  </div>",
         "  <div class='progressbar'></div>",
         "  </div>",
         "  <div class='show_more'></div>",
         "  <div class='agents_block'></div>",
         "  <div class='show_more'></div>",
         "</script>"
      ));

      // Template structure for targets' statistics
      echo implode("\n", array(
         "<script id='template_target_stats' type='x-tmp-mustache'>",
         "  <div class='{{stats_type}} stats_block'>",
         "  </div>",
         "</script>",
      ));

      // Template for counters' blocks
      echo implode("\n", array(
         "<script id='template_counter_block' type='x-tmpl-mustache'>",
         "<div class='counter_block {{counter_type}} {{#counter_empty}}empty{{/counter_empty}}'>",
         "<a",
         "  href='javascript:void(0)'",
         "  class='' ",
         "  title='".__("Show/Hide details","fusioninventory")."'",
         "  onclick='taskjobs.toggle_details_type(this, \"{{counter_type}}\", \"{{chart_id}}\")'",
         ">",
         "<div class='fold'></div>",
         "<span class='counter_name'>{{counter_type_name}}</span>",
         "<span class='counter_value'>{{counter_value}}</span>",
         "</div>",
         "</a>",
         "</script>"
      ));

      /*
       * List of counter names
       */
      echo implode("\n", array(
         "<script type='text/javascript'>",
         "  taskjobs.statuses_order = {",
         "     last_executions : [",
         "        'agents_prepared',",
         "        'agents_running',",
         "        'agents_cancelled'",
         "     ],",
         "     last_finish_states : [",
         "        'agents_notdone',",
         "        'agents_success',",
         "        'agents_error'",
         "     ]",
         "  };",
         "  taskjobs.statuses_names = {",
         "     'agents_notdone'   : '". __('Not done yet', 'fusioninventory')."',",
         "     'agents_error'     : '". __('In error', 'fusioninventory') . "',",
         "     'agents_success'   : '". __('Successful', 'fusioninventory')."',",
         "     'agents_running'   : '". __('Running', 'fusioninventory')."',",
         "     'agents_prepared'  : '". __('Prepared' , 'fusioninventory')."',",
         "     'agents_cancelled' : '". __('Cancelled', 'fusioninventory')."',",
         "  };",
         "  taskjobs.logstatuses_names = " . json_encode(
            PluginFusioninventoryTaskjoblog::dropdownStateValues()
         ) . ";",
         "</script>",
      ));

      // Template for agents' blocks
      echo implode("\n", array(
         "<script id='template_agent' type='x-tmpl-mustache'>",
         "<div class='agent_block' id='{{agent_id}}'>",
         "  <div class='status {{status.last_exec}}'></span>",
         "  <div class='status {{status.last_finish}}'></span>",
         "</div>",
         "</script>"
      ));

      // Display empty block for each jobs display which will be rendered later by mustache.js
      echo implode("\n", array(
         "<div class='tasks_block'>",
         "</div>",
//         "<pre class='debuglogs' style='text-align:left;'></pre>"
      ));

      if (isset($this->fields['id']) ) {
         $task_id = $this->fields['id'];
      } else {
         $task_id = json_encode(array());
      }
      $pfAgent = new PluginFusioninventoryAgent();
      $Computer = new Computer();
      echo implode( "\n", array(
         "<script type='text/javascript'>",
         "  taskjobs.agents_url = '". $pfAgent->getFormURL()."'",
         "  taskjobs.computers_url = '". $Computer->getFormURL()."'",
         "  taskjobs.init_templates();",
         "  taskjobs.init_refresh_form(",
         "     '".$this->getBaseUrlFor('fi.job.logs')."',",
         "     ".$task_id.",",
         "     'dropdown_".$refresh_randid."'",
         "  );",
         "  taskjobs.update_logs_timeout(",
         "     '".$this->getBaseUrlFor('fi.job.logs')."',",
         "     ".$task_id.",",
         "     'dropdown_".$refresh_randid."'",
         "  );",
         "</script>"
      ));
   }



   /**
    * Ajax called to get job logs
    *
    * @todo Move this method in task.class
    *
    * @param integer $task_id
    */
   function ajaxGetJobLogs($task_id) {
      if (!empty($task_id)) {
         if (is_array($task_id)) {
            $task_ids = $task_id;
         } else {
            $task_ids = array($task_id);
         }
      } else {
         $task_ids = array();
      }
      $logs = $this->getJoblogs($task_ids);
      echo json_encode($logs);
   }



   /**
    * Get translated name of counter type
    *
    * @param string $type
    * @return string
    */
   function getCounterTypeName($type = "") {
      $typenames = array(
         "agents_notdone"   => __('Not done yet', 'fusioninventory'),
         "agents_error"     => __('In error', 'fusioninventory'),
         "agents_success"   => __('Successful', 'fusioninventory'),
         "agents_running"   => __('Running', 'fusioninventory'),
         "agents_prepared"  => __('Prepared' , 'fusioninventory'),
         "agents_cancelled" => __('Cancelled', 'fusioninventory')
      );

      if (isset($typenames[$type])) {
         return $typenames[$type];
      } else {
         return __('N/A', 'fusioninventory');
      }
   }



   /**
    * Get agents logs
    *
    * @param array $agents
    * @param array $counters
    * @param string $target_id
    * @return array
    */
   function getAgentsLogs($agents = array(), $counters = array(), $target_id = "") {
      $display_list = array();
      $display_list[] = "<div class='job_agents'>";
      $display_list[] = "<ul>";

      foreach ($agents as $agent) {
         $agent_id = $target_id . "_agent_".$agent['id'];
         $display_tags = array();
         $agent_css = array();
         foreach ($counters as $type=>$list) {
            if (isset( $list[$agent['id']])) {
               $display_tags[] = "<span class='".$type."'>";
               $display_tags[] = $this->getCounterTypeName($type);
               $display_tags[] = "</span>";
               $agent_css[] = $type;
            }
         }
         $display_list[] = "<li class='".implode(" ", $agent_css)."'>";
         $display_list[] = "<div class='agent_block' id='".$agent_id."'>";
         //Add fold/unfold icon
         $display_list[] = " <div ";
         $display_list[] = "  class='fold'";
         $display_list[] = "  title='".__("Show/Hide Agent details","fusioninventory")."'";
         $display_list[] = "  onclick='taskjobs.toggle_agent_fold(this)'";
         $display_list[] = " ></div>";

         $display_list[] = "<a target='_blank' href='".$agent['url']."'>";
         $display_list[] = $agent['name'];
         $display_list[] = "</a>";
         $display_list = array_merge($display_list, $display_tags);
         $display_list[] = "</div>"; //end of .agent_block
         $display_list[] = "<div class='runs_block'>";
         foreach ($agent['runs'] as $run) {
            $display_list = array_merge($display_list, $this->getRunLogs($run));
         }
         $display_list[] = "</div>"; //end of .run_block
         $display_list[] = "</li>";
      }
      $display_list[] = "</ul>";
      $display_list[] = "</div>";

      return $display_list;
   }



   /**
    * Get run logs
    *
    * @param array $run
    * @return array
    */
   function getRunLogs($run = array()) {

      $logClass = new PluginFusioninventoryTaskjoblog();
      $display = array();
      $display[] = "<div class='run_block'>";
      $display[] = " <h4>" . __('Execution', 'fusioninventory')." ".$run['uniqid']."</h4>";
      $display[] = " <table class='logs_block'>";
      foreach ($run['logs'] as $log) {
         $css_state = $logClass::getStateCSSName($log['state']);
         $state_name = $logClass::getStateName($log['state']);
         $display[] = "<tr>";
         $display[] = "    <td class='log_date'>".$log['date']."</td>";
         $display[] = "    <td class='log_state'>";
         $display[] = "       <span class='".$css_state."'>".$state_name."</span>";
         $display[] = "    </td>";
         $display[] = "   <td class='log_comment'>".$log['comment']."</td>";
         $display[] = "</tr>";
      }
      $display[] = " </table>";
      $display[] = "</div>";
      return $display;
   }



   /**
    * Display form for task configuration
    *
    * @param integer $id ID of the task
    * @param $options array
    * @return boolean TRUE if form is ok
    *
    **/
   function showForm($id, $options=array()) {
      $pfTaskjob = new PluginFusioninventoryTaskjob();

      $taskjobs = array();
      $new_item = false;

      if ($id > 0) {
         $this->getFromDB($id);
         $taskjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$id."'", "id");
      } else {
         $this->getEmpty();
         $new_item = true;
      }

      $options['colspan'] = 2;
      $this->initForm($id,$options);
      $this->showFormHeader($options);


      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='4'>";
      echo "<div class='fusinv_form'>";

      $this->showTextField( __('Name'), "name");
      $this->showTextArea(__('Comments'), "comment");
      $this->showCheckboxField(__('Re-prepare a target-actor if previous run is successful', 'fusioninventory'),
                               "reprepare_if_successful");

      echo "</div>";
      if (!$new_item) {
         echo "<div class='fusinv_form'>";
         $this->showCheckboxField( __('Active'), "is_active" );

         $datetime_field_options = array(
            'timestep' => 1,
            'maybeempty' => true,
         );
         $this->showDateTimeField(
            __('Schedule start', 'fusioninventory'),
            "datetime_start",
            $datetime_field_options
         );

         $this->showDateTimeField(
            __('Schedule end', 'fusioninventory'),
            "datetime_end",
            $datetime_field_options
         );

         $this->showDropdownForItemtype(
            __('Timeslot','fusioninventory'),
            "PluginFusioninventoryTimeslot",
            array('value' => $this->fields['plugin_fusioninventory_timeslots_id'])
            );

         $this->showIntegerField( __('Agent wakeup interval (in minutes)', 'fusioninventory'), "wakeup_agent_time",
                                 array('value' => $this->fields['wakeup_agent_time'],
                                       'toadd' => array('0' => __('Never')),
                                       'min'   => 1,
                                       'step'  => 1) );

         $this->showIntegerField( __('Number of agents to wake up', 'fusioninventory'), "wakeup_agent_counter",
                                 array('value' => $this->fields['wakeup_agent_counter'],
                                       'toadd' => array('0' => __('None')),
                                       'min'   => 0,
                                       'step'  => 1) );

         echo "</div>";
      }

      echo "</div>";
      echo "</td>";
      echo "</tr>";
      $this->showFormButtons($options);

      return true;
   }



   /**
    * Manage the different actions in when submit form (add, update,purge...)
    *
    * @param array $postvars
    */
   public function submitForm($postvars) {

      if (isset($postvars['forcestart'])) {
         Session::checkRight('plugin_fusioninventory_task', UPDATE);

         /**
          * TODO: forcing the task execution should be done in the task object
          */
         $pfTaskjob = new PluginFusioninventoryTaskjob();
         $pfTaskjob->forceRunningTask($postvars['id']);
         Html::back();
      } else if (isset ($postvars["add"])) {
         Session::checkRight('plugin_fusioninventory_task', CREATE);
         $items_id = $this->add($postvars);
         Html::redirect(str_replace("add=1", "", $_SERVER['HTTP_REFERER'])."?id=".$items_id);
      } else if (isset($postvars["purge"])) {
         Session::checkRight('plugin_fusioninventory_task', PURGE);
         $pfTaskJob = new PluginFusioninventoryTaskjob();
         $taskjobs = $pfTaskJob->find("`plugin_fusioninventory_tasks_id` = '".$postvars['id']."' ");
         foreach ($taskjobs as $taskjob) {
            $pfTaskJob->delete($taskjob);
         }
         $this->delete($postvars);
         Html::redirect(Toolbox::getItemTypeSearchURL(get_class($this)));
      } else if (isset($_POST["update"])) {
         Session::checkRight('plugin_fusioninventory_task', UPDATE);
         $this->getFromDB($postvars['id']);
         //Ensure empty value are set to NULL for datetime fields
         if (isset($postvars['datetime_start']) and $postvars['datetime_start'] === '') {
            $postvars['datetime_start'] = 'NULL';
         }
         if (isset($postvars['datetime_end']) and $postvars['datetime_end'] === '') {
            $postvars['datetime_end'] = 'NULL';
         }
         $this->update($postvars);
         Html::back();
      }
   }



   /**
    * Define reprepare_if_successful field when get empty item
    */
   function getEmpty() {
      parent::getEmpty();
      $this->fields['reprepare_if_successful'] = 1;
   }
}
