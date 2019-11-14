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
      $this->base_urls = array_merge( $this->base_urls, [
         'fi.job.logs' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_logs.php",
      ]);
   }


   /**
    * Show job logs
    */
   function showJobLogs() {
      echo "<div class='fusinv_panel'>";
      echo "<div class='fusinv_form large'>";

      // add a list limit for include old jobs
      $include_oldjobs_id = $this->showDropdownFromArray(
         __("Include old jobs", 'fusioninventory'),
         null,
         [
            1   => __('Last'),
            2   => 2,
            5   => 5,
            10  => 10,
            25  => 25,
            50  => 50,
            100 => 100,
            250 => 250,
            -1  => __('All')
         ],
         ['value' => $_SESSION['glpi_plugin_fusioninventory']['includeoldjobs']]
      );

      // add an auto-refresh control
      $refresh_randid = $this->showDropdownFromArray(
         __("refresh interval", "fusioninventory"),
         null,
         [
            "off"  => __('Off', 'fusioninventory'),
            "1"    => '1 '._n('second', 'seconds', 1),
            "5"    => '5 '._n('second', 'seconds', 5),
            "10"   => '10 '._n('second', 'seconds', 10),
            "60"   => '1 '._n('minute', 'minutes', 1),
            "120"  => '2 '._n('minute', 'minutes', 2),
            "300"  => '5 '._n('minute', 'minutes', 5),
            "600"  => '10 '._n('minute', 'minutes', 10),
         ],
         ['value' => $_SESSION['glpi_plugin_fusioninventory']['refresh']]
      );

      // display export button
      echo "<i class='openExportDialog pointer fa fa-lg fa-save'
               title='"._sx('button', 'Export')."'></i>";

      // Add a manual refresh button
      echo "<div class='refresh_button submit'>";
      echo "<span></span>";
      echo "</div>"; // .refresh_button

      echo "</div>"; // .fusinv_form
      echo "</div>"; // .fusinv_panel

      // Template structure for tasks' blocks
      echo "<script id='template_task' type='x-tmpl-mustache'>
               <div id='{{task_id}}' class='task_block {{expanded}}'>
                  <h3>".
                     __("Task", 'fusioninventory')."
                     <span class='task_name'>{{task_name}}</span>
                  </h3>
                  <a href='".PluginFusioninventoryTask::getFormURL()."?id={{task_id}}'
                     class='task_block_link'>
                     <i class='fa fa-link pointer'></i>
                  </a>
                  <div class='jobs_block'></div>
               </div>
            </script>";

      // Template structure for jobs' blocks
      echo "<script id='template_job' type='x-tmpl-mustache'>
               <div id='{{job_id}}' class='job_block'>
                  <div class='refresh_button submit'><span></span></div>
                     <h3 class='job_name'>{{job_name}}</h3>
                  <div class='targets_block'></div>
               </div>
            </script>";

      // Template structure for targets' blocks
      echo "<script id='template_target' type='x-tmpl-mustache'>
               <div id='{{target_id}}' class='target_block'>
                  <div class='target_details'>
                  <div class='target_infos'>
                     <h4 class='target_name'>
                        <a target='_blank' href={{target_link}}>{{target_name}}</a>
                     </h4>
                     <div class='target_stats'>
                     </div>
                  </div>
                  <div class='progressbar'></div>
               </div>
               <div class='show_more'></div>
               <div class='agents_block'></div>
               <div class='show_more'></div>
            </script>";

      // Template structure for targets' statistics
      echo "<script id='template_target_stats' type='x-tmp-mustache'>
               <div class='{{stats_type}} stats_block'></div>
            </script>";

      // Template for counters' blocks
      echo "<script id='template_counter_block' type='x-tmpl-mustache'>
               <div class='counter_block {{counter_type}} {{#counter_empty}}empty{{/counter_empty}}'>
                  <a class='toggle_details_type'
                     data-counter_type='{{counter_type}}'
                     data-chart_id='{{chart_id}}'
                     title='".__("Show/Hide details", "fusioninventory")."'>
                     <div class='fold'></div>
                     <span class='counter_name'>{{counter_type_name}}</span>
                     <span class='counter_value'>{{counter_value}}</span>
                  </a>
               </div>
            </script>";

      // List of counter names
      echo Html::scriptBlock("$(document).ready(function() {
         taskjobs.statuses_order = {
            last_executions: [
               'agents_prepared',
               'agents_running',
               'agents_cancelled',
            ],
            last_finish_states: [
               'agents_notdone',
               'agents_success',
               'agents_error'
            ]
         };

         taskjobs.statuses_names = {
            'agents_notdone':   '". __('Not done yet', 'fusioninventory')."',
            'agents_error':     '". __('In error', 'fusioninventory') . "',
            'agents_success':   '". __('Successful', 'fusioninventory')."',
            'agents_running':   '". __('Running', 'fusioninventory')."',
            'agents_prepared':  '". __('Prepared', 'fusioninventory')."',
            'agents_postponed':  '". __('Postponed', 'fusioninventory')."',
            'agents_cancelled': '". __('Cancelled', 'fusioninventory')."',
         };

         taskjobs.logstatuses_names = ".
            json_encode(PluginFusioninventoryTaskjoblog::dropdownStateValues()).";
      });");

      // Template for agents' blocks
      echo "<script id='template_agent' type='x-tmpl-mustache'>
               <div class='agent_block' id='{{agent_id}}'>
                  <div class='status {{status.last_exec}}'></span>
                  <div class='status {{status.last_finish}}'></span>
               </div>
            </script>";

      // Display empty block for each jobs display
      // which will be rendered later by mustache.js
      echo "<div class='tasks_block'></div>";

      if (isset($this->fields['id'])) {
         $task_id = $this->fields['id'];
      } else {
         $task_id = null;
      }
      $pfAgent      = new PluginFusioninventoryAgent();
      $Computer     = new Computer();

      echo Html::scriptBlock("$(document).ready(function() {
         taskjobs.task_id        = '".$task_id."';
         taskjobs.ajax_url       = '".$this->getBaseUrlFor('fi.job.logs')."';
         taskjobs.agents_url     = '".$pfAgent->getFormUrl()."';
         taskjobs.includeoldjobs = '".$_SESSION['glpi_plugin_fusioninventory']['includeoldjobs']."';
         taskjobs.refresh        = '".$_SESSION['glpi_plugin_fusioninventory']['refresh']."';
         taskjobs.computers_url  = '".$Computer->getFormUrl()."';
         taskjobs.init_templates();
         taskjobs.init_refresh_form(
            '".$this->getBaseUrlFor('fi.job.logs')."',
            '".$task_id."',
            'dropdown_".$refresh_randid."'
         );
         taskjobs.init_include_old_jobs_buttons(
            '".$this->getBaseUrlFor('fi.job.logs')."',
            '".$task_id."',
            'dropdown_".$include_oldjobs_id."'
         );
         taskjobs.update_logs_timeout(
            '".$this->getBaseUrlFor('fi.job.logs')."',
            '".$task_id."',
            'dropdown_".$refresh_randid."'
         );
      });");

      // Display Export modal
      echo "<div id='fiTaskExport_modalWindow'>";
      echo "<form method='POST' class='task_export_form center'
                  action='".self::getFormURLWithID($task_id) ."'>";

      // states checkboxes
      echo "<label for='include_old_jobs'>".__("Task execution states", 'fusioninventory').
           "</label>";
      echo "<div class='state_checkboxes'>";
       // set options checked by default
      $agent_state_types = [
         'agents_prepared'  => false,
         'agents_running'   => true,
         'agents_cancelled' => false,
         'agents_success'   => true,
         'agents_error'     => true,
         'agents_postponed' => false,
      ];
      foreach ($agent_state_types as $agent_state_type => $agent_state_checked) {
         $agent_state_type = str_replace("agents_", "", $agent_state_type);
         $locale  = __(ucfirst($agent_state_type), 'fusioninventory');
         $checked = "";
         if ($agent_state_checked) {
            $checked = "checked='checked'";
         }
         echo "<div class='agent_state_type_checkbox'>";
         echo "<input type='checkbox' $checked name='agent_state_types[]' ".
              "value='$agent_state_type' id='agent_state_types_$agent_state_type' />";
         echo "<label for='agent_state_types_$agent_state_type'>&nbsp;$locale</label>";
         echo "</div>";
      }
      echo "</div>"; // .state_checkboxes

      echo "<div class='clear_states'></div>";

      echo Html::hidden('task_id', ['value' => $task_id]);
      echo Html::submit(_sx('button', 'Export'), ['name' => 'export_jobs']);
      Html::closeForm();
      echo "</div>"; // #fiTaskExport_modalWindow
   }


   /**
    * Display form for task configuration
    *
    * @param integer $id ID of the task
    * @param $options array
    * @return boolean TRUE if form is ok
    *
    **/
   function showForm($id, $options = []) {
      $pfTaskjob = new PluginFusioninventoryTaskjob();

      $taskjobs = [];
      $new_item = false;

      if ($id > 0) {
         $this->getFromDB($id);
         $taskjobs = $pfTaskjob->find(['plugin_fusioninventory_tasks_id' => $id], ['id']);
      } else {
         $this->getEmpty();
         $new_item = true;
      }

      $options['colspan'] = 2;
      $this->initForm($id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='4'>";
      echo "<div class='fusinv_form'>";

      $this->showTextField( __('Name'), "name");
      $this->showTextArea(__('Comments'), "comment");
      $this->showCheckboxField(__('Re-prepare a target-actor if previous run is successful', 'fusioninventory'),
                               "reprepare_if_successful");
      if ($this->fields['is_deploy_on_demand']) {
         echo "<div class='input_wrap'>";
         echo __("This is an on demand deployment task", "fusioninventory");
         echo "</div>";
      }
      echo "</div>";
      if (!$new_item) {
         echo "<div class='fusinv_form'>";
         $this->showCheckboxField( __('Active'), "is_active" );

         $datetime_field_options = [
            'timestep'   => 1,
            'maybeempty' => true,
         ];
         $this->showDateTimeField(__('Schedule start', 'fusioninventory'),
                                  "datetime_start", $datetime_field_options
         );

         $this->showDateTimeField(__('Schedule end', 'fusioninventory'),
                                  "datetime_end", $datetime_field_options
         );

         $this->showDropdownForItemtype(__('Preparation timeslot', 'fusioninventory'),
                                        "PluginFusioninventoryTimeslot",
                                       ['name'  => 'plugin_fusioninventory_timeslots_prep_id',
                                        'value' => $this->fields['plugin_fusioninventory_timeslots_prep_id']
                                       ]
            );

         $this->showDropdownForItemtype(
            __('Execution timeslot', 'fusioninventory'),
            "PluginFusioninventoryTimeslot",
            ['name'  => 'plugin_fusioninventory_timeslots_exec_id',
                  'value' => $this->fields['plugin_fusioninventory_timeslots_exec_id']]
            );

         $this->showIntegerField( __('Agent wakeup interval (in minutes)', 'fusioninventory'), "wakeup_agent_time",
                                 ['value' => $this->fields['wakeup_agent_time'],
                                  'toadd' => ['0' => __('Never')],
                                  'min'   => 1,
                                  'step'  => 1
                                 ] );

         $this->showIntegerField( __('Number of agents to wake up', 'fusioninventory'), "wakeup_agent_counter",
                                 ['value' => $this->fields['wakeup_agent_counter'],
                                  'toadd' => ['0' => __('None')],
                                  'min'   => 0,
                                  'step'  => 1
                                 ] );

         echo "</div>";
      }

      echo "</div>";
      echo "</td>";
      echo "</tr>";
      $this->showFormButtons($options);

      return true;
   }


   function showFormButtons($options = []) {
      if (isset($this->fields['id'])) {
         $ID = $this->fields['id'];
      }

      echo "<tr>";
      echo "<td colspan='2'>";
      if ($this->isNewID($ID)) {
         echo Html::submit(_x('button', 'Add'), ['name' => 'add']);
      } else {
         echo Html::hidden('id', ['value' => $ID]);
         echo Html::submit(_x('button', 'Save'), ['name' => 'update']);
      }
      echo "</td>";

      if ($this->fields['is_active']) {
         echo "<td>";
         echo Html::submit(__('Force start', 'fusioninventory'),
                           ['name' => 'forcestart']);
         echo "</td>";
      }

      echo "<td>";
      if ($this->can($ID, PURGE)) {
         echo Html::submit(_x('button', 'Delete permanently'),
                           ['name'    => 'purge',
                            'confirm' => __('Confirm the final deletion?')
                           ]);
      }
      echo "</td>";
      echo "</tr>";

      // Close for Form
      echo "</table></div>";
      Html::closeForm();
   }


   /**
    * Manage the different actions in when submit form (add, update,purge...)
    *
    * @param array $postvars
    */
   public function submitForm($postvars) {

      if (isset($postvars['forcestart'])) {
         Session::checkRight('plugin_fusioninventory_task', UPDATE);

         $this->getFromDB($postvars['id']);
         $this->forceRunning();

         Html::back();
      } else if (isset ($postvars["add"])) {
         Session::checkRight('plugin_fusioninventory_task', CREATE);
         $items_id = $this->add($postvars);
         Html::redirect(str_replace("add=1", "", $_SERVER['HTTP_REFERER'])."?id=".$items_id);
      } else if (isset($postvars["purge"])) {
         Session::checkRight('plugin_fusioninventory_task', PURGE);
         $pfTaskJob = new PluginFusioninventoryTaskjob();
         $taskjobs = $pfTaskJob->find(['plugin_fusioninventory_tasks_id' => $postvars['id']]);
         foreach ($taskjobs as $taskjob) {
            $pfTaskJob->delete($taskjob);
         }
         $this->delete($postvars);
         Html::redirect(Toolbox::getItemTypeSearchURL(get_class($this)));
      } else if (isset($_POST["update"])) {
         Session::checkRight('plugin_fusioninventory_task', UPDATE);
         $this->getFromDB($postvars['id']);
         //Ensure empty value are set to NULL for datetime fields
         if (isset($postvars['datetime_start']) && $postvars['datetime_start'] === '') {
            $postvars['datetime_start'] = 'NULL';
         }
         if (isset($postvars['datetime_end']) && $postvars['datetime_end'] === '') {
            $postvars['datetime_end'] = 'NULL';
         }
         $this->update($postvars);
         Html::back();
      } else if (isset($postvars['export_jobs'])) {
         Session::checkRight('plugin_fusioninventory_task', READ);
         $this->csvExport($postvars);
      }
   }


   /**
    * Define reprepare_if_successful field when get empty item
    */
   function getEmpty() {
      parent::getEmpty();
      $pfConfig = new PluginFusioninventoryConfig();
      $this->fields['reprepare_if_successful'] = $pfConfig->getValue('reprepare_job');
   }


   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id'           => 'common',
         'name'         => __('Characteristics')
      ];

      $tab[] = [
         'id'           => '1',
         'table'        => $this->getTable(),
         'field'        => 'name',
         'name'         => __('Name'),
         'datatype'     => 'itemlink',
         'autocomplete' => true,
      ];

      return $tab;
   }
}
