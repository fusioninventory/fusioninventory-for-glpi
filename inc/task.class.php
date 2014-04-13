<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */


class PluginFusioninventoryTask extends PluginFusioninventoryTaskView {

   static $rightname = 'plugin_fusioninventory_task';

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Task management', 'fusioninventory');
   }

   /**
    * This class can be created by GLPI framework.
    */
   static function canCreate() {
      return true;
   }


   function getSearchOptions() {

      $sopt = array();

      $sopt['common'] = __('Task');


      $sopt[1]['table']          = $this->getTable();
      $sopt[1]['field']          = 'name';
      $sopt[1]['linkfield']      = 'name';
      $sopt[1]['name']           = __('Name');
      $sopt[1]['datatype']       = 'itemlink';

      $sopt[2]['table']          = $this->getTable();
      $sopt[2]['field']          = 'datetime_start';
      $sopt[2]['linkfield']      = 'datetime_start';
      $sopt[2]['name']           = __('Schedule start', 'fusioninventory');
      $sopt[2]['datatype']       = 'datetime';

      $sopt[2]['table']          = $this->getTable();
      $sopt[2]['field']          = 'datetime_end';
      $sopt[2]['linkfield']      = 'datetime_end';
      $sopt[2]['name']           = __('Schedule end', 'fusioninventory');
      $sopt[2]['datatype']       = 'datetime';

      $sopt[3]['table']          = 'glpi_entities';
      $sopt[3]['field']          = 'completename';
      $sopt[3]['linkfield']      = 'entities_id';
      $sopt[3]['name']           = __('Entity');

      $sopt[4]['table']          = $this->getTable();
      $sopt[4]['field']          = 'comment';
      $sopt[4]['linkfield']      = 'comment';
      $sopt[4]['name']           = __('Comments');

      $sopt[5]['table']          = $this->getTable();
      $sopt[5]['field']          = 'is_active';
      $sopt[5]['linkfield']      = 'is_active';
      $sopt[5]['name']           = __('Active');
      $sopt[5]['datatype']       = 'bool';

      $sopt[30]['table']          = $this->getTable();
      $sopt[30]['field']          = 'id';
      $sopt[30]['linkfield']      = '';
      $sopt[30]['name']           = __('ID');
      $sopt[30]['datatype']      = 'number';

      return $sopt;
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

   /**
    * Get the list of taskjobstates
    */
   function getTaskjobstatesForAgent($agent_id, $methods = array(), $options=array()) {
      global $DB;

      // Check for read only which means we do not change the jobstates state (especially usefull
      // for the get_agent_jobs.php script).
      $read_only = false;
      if ( isset($options['read_only']) ) {
         $read_only = $options['read_only'];
      }

      $pfTimeslot = new PluginFusioninventoryTimeslot();

      $jobstates = array();

      //Get the datetime of agent request
      $now = new Datetime();

      // list of jobstates not allowed to run (ie. filtered by schedule and timeslots)
      $jobstates_to_cancel = array();

      $query = implode(" \n", array(
         "SELECT",
         "     task.`id`, task.`name`, task.`is_active`,",
         "     task.`datetime_start`, task.`datetime_end`,",
         "     task.`plugin_fusioninventory_timeslots_id` as timeslot_id,",
         "     job.`id`, job.`name`, job.`method`, job.`actors`,",
         "     run.`itemtype`, run.`items_id`, run.`state`,",
         "     run.`id`, run.`plugin_fusioninventory_agents_id`",
         "FROM `glpi_plugin_fusioninventory_taskjobstates` run",
         "LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` job",
         "  ON job.`id` = run.`plugin_fusioninventory_taskjobs_id`",
         "LEFT JOIN `glpi_plugin_fusioninventory_tasks` task",
         "  ON task.`id` = job.`plugin_fusioninventory_tasks_id`",
         "WHERE",
         "  job.`method` IN ('".implode("','", $methods)."')",
         "  and run.`state` IN ('". implode("','", array(
            PluginFusioninventoryTaskjobstate::PREPARED,
            PluginFusioninventoryTaskjobstate::SERVER_HAS_SENT_DATA,
         ))."')",
         "  AND run.`plugin_fusioninventory_agents_id` = " . $agent_id,
         // order the result by job.id
         // TODO: the result should be ordered by the future job.index field when drag and drop
         // feature will be properly activated in the taskjobs list.
         "ORDER BY job.`id`",
      ));

      $query_result = $DB->query($query);
      $results = array();
      if ($query_result) {
         $results = PluginFusioninventoryToolbox::fetchAssocByTable($query_result);
      }

      // Get timeslot's entries from this list at the time of the request (ie. get entries according
      // to the day of the week)
      $timeslot_entries = array();
      $day_of_week = $now->format("N");

      $timeslot_ids = array();
      foreach( $results as $result ) {
         $timeslot_ids[$result['task']['timeslot_id']] = 1;
      }
      $timeslot_entries = $pfTimeslot->getTimeslotEntries(array_keys($timeslot_ids), $day_of_week);

      $timeslot_cursor = $pfTimeslot->getTimeslotCursor($now);

      /**
       * Ensure the agent's jobstates are allowed to run at the time of the agent's request.
       * The following checks if:
       * - The tasks associated with those taskjobs are not disabled.
       * - The task's schedule and timeslots still match the time those jobstates have been
       * requested.
       * - [ToBeDone] The agent is still present in the dynamic actors (eg. Dynamic groups)
       * TODO: add the timeslot condition.
       */
      foreach($results as $result ) {

         $jobstate = new PluginFusioninventoryTaskjobstate();
         $jobstate->getFromDB($result['run']['id']);

         //Cancel the job it has already been sent to the agent but the agent did not replied
         if ($result['run']['state'] == $jobstate::SERVER_HAS_SENT_DATA) {
            $jobstates_to_cancel[$jobstate->fields['id']] = array(
               'jobstate' => $jobstate,
               'reason'   => __(
                  "The agent is requesting a configuration that has already been sent to ".
                  "him by the server. It is more likely that the agent is subject to a critical ".
                  "error."
               )
            );
            continue;
         }
         //Cancel the jobstate if the related tasks has been deactivated
         if ($result['task']['is_active'] == 0) {
            $jobstates_to_cancel[$jobstate->fields['id']] = array(
               'jobstate' => $jobstate,
               'reason' => __('The task has been deactivated after preparation of this job.')
            );
            continue;
         };

         // Cancel the jobstate if it the schedule doesn't match.
         if ( !is_null($result['task']['datetime_start']) ) {
            $schedule_start = new DateTime($result['task']['datetime_start']);

            if ( !is_null($result['task']['datetime_end']) ) {
               $schedule_end = new DateTime($result['task']['datetime_end']);
            } else {
               $schedule_end = $now;
            }

            if ( !($schedule_start <= $now and $now <= $schedule_end) ) {
               $jobstates_to_cancel[$jobstate->fields['id']] = array(
                  'jobstate' => $jobstate,
                  'reason' => __(
                     "This job can not be executed anymore due to the task\'s schedule."
                  )
               );
               continue;
            }
         }

         // Cancel the jobstate if it is requested outside of any timeslot.
         $timeslot_id = $result['task']['timeslot_id'];

         // Do nothing if there are no defined timeslots for this jobstate.
         if ($timeslot_id > 0 ) {
            $timeslot_matched = false;

            // We do nothing if there are no timeslot_entries, meaning this jobstate is not allowed
            // to be executed at the day of request.
            if ( array_key_exists($timeslot_id, $timeslot_entries) ) {
               foreach( $timeslot_entries[$timeslot_id] as $timeslot_entry ) {
                  if (
                     $timeslot_entry['begin'] <= $timeslot_cursor
                     and $timeslot_cursor <= $timeslot_entry['end']
                  ) {
                     //The timeslot cursor (ie. time of request) matched a timeslot entry so we can
                     //break the loop here.
                     $timeslot_matched = true;
                     break;
                  }
               }
            }
            // If no timeslot matched, cancel this jobstate.
            if ( !$timeslot_matched ) {
               $jobstates_to_cancel[$jobstate->fields['id']] = array(
                  'jobstate' => $jobstate,
                  'reason' => __(
                     "This job can not be executed anymore due to the task\'s timeslot."
                  )
               );
               continue;
            }
         }

         // Make sure the agent is still present in the list of actors that generated
         // this jobstate.
         // TODO: If this jobstate needs to be cancelled, it would be worth to point out which actor
         // is the source of this execution. To do this, we need to track the 'actor_source' in the
         // jobstate when it's generated by prepareTaskjobs().

         $actors = importArrayFromDB($result['job']['actors']);
         $agents = $this->getAgentsFromActors($actors);
         if ( !in_array($agent_id, $agents) ) {
            $jobstates_to_cancel[$jobstate->fields['id']] = array(
               'jobstate' => $jobstate,
               'reason' => __(
                  'This agent does not belong anymore in the actors defined in the job.'
               )
            );
            continue;
         }

         //TODO: The following method (actually defined as member of taskjob) needs to be
         //initialized when getting the jobstate from DB (with a getfromDB hook for example)
         $jobstate->method = $result['job']['method'];

         //Add the jobstate to the list since previous checks are good.
         $jobstates[$jobstate->fields['id']] = $jobstate;
      }

      //Remove the list of jobstates previously filtered for removal.
      foreach( $jobstates_to_cancel as $jobstate) {
         $jobstate['jobstate']->cancel($jobstate['reason']);
      }

      return $jobstates;
   }


   function prepareTaskjobs( $methods = array()) {
      global $DB;

      $agent = new PluginFusioninventoryAgent();

      $timeslot = new PluginFusioninventoryTimeslot();

      $now = new DateTime();

      //transform methods array into string for database query
      $methods = "'" . implode("','", $methods) . "'";

      $query = implode( "\n", array(
         "select",
         "     task.`id`, task.`name`,",
         "     job.`id`, job.`name`, job.`method`, ",
         "     job.`targets`, job.`actors`,",
         "     run.`id`, run.`plugin_fusioninventory_agents_id`",
         "from `glpi_plugin_fusioninventory_taskjobs` job",
         "left join `glpi_plugin_fusioninventory_tasks` task",
         "  on task.`id` = job.`plugin_fusioninventory_tasks_id`",
         "left join `glpi_plugin_fusioninventory_taskjobstates` run",
         "  on job.`id` = run.`plugin_fusioninventory_taskjobs_id`",
         "where task.`is_active` = 1",
         "and (",
         /**
          * Filter jobs by the schedule and timeslots
          */
         // check only if now() >= datetime_start if datetime_end is null
         "        (   task.`datetime_start` is not null and task.`datetime_end` is null",
         "              and '".$now->format("Y-m-d H:i:s")."' >= task.`datetime_start` )",
         "     or",
         // check if now() is between datetime_start and datetime_end
         "        (   task.`datetime_start` is not null and task.`datetime_end` is not null",
         "              and '".$now->format("Y-m-d H:i:s")."' ",
         "                    between task.`datetime_start` and task.`datetime_end` )",
         "     or",
         // finally, check if this task can be run at any time ( datetime_start and datetime_end are
         // both null)
         "        ( task.`datetime_start` is null and task.`datetime_end` is null )",
         ")",
         "and job.`method` in (".$methods.")",
         // order the result by job.id
         // TODO: the result should be ordered by the future job.index field when drag and drop
         // feature will be properly activated in the taskjobs list.
         "order by job.`id`",
      ));

      $query_result = $DB->query($query);
      $results = array();
      if ($query_result) {
         $results = PluginFusioninventoryToolbox::fetchAssocByTable($query_result);
      }

      // Set basic elements of jobstates
      $run_base = array(
         'state' => PluginFusioninventoryTaskjobstate::PREPARED,
      );
      $log_base = array(
         'date'  => $now->format("Y-m-d H:i:s"),
         'state' => PluginFusioninventoryTaskjoblog::TASK_PREPARED,
         'comment' => ''
      );

      $jobstate = new PluginFusioninventoryTaskjobstate();
      $joblog = new PluginFusioninventoryTaskjoblog();
      foreach($results as $index => $result) {

         $actors = importArrayFromDB($result['job']['actors']);
         // Get agents linked to the actors
         $agent_ids = array();
         foreach( $this->getAgentsFromActors($actors) as $agent_id) {
            $agent_ids[$agent_id] = true;
         }
         //Continue with next job if there are no agents found from actors.
         //TODO: This may be good to report this kind of information. We just need to do a list of
         //agent's ids generated by actors like array('actors_type-id' => array( 'agent_0',...).
         //Then the following could be put in the targets foreach loop before looping through
         //agents.
         if (count($agent_ids) == 0) {
            continue;
         }

         $targets = importArrayFromDB($result['job']['targets']);

         foreach($targets as $target) {
            $item_type = key($target);
            $item_id = current($target);
            $job_id = $result['job']['id'];
            $jobstates_running = $jobstate->find(
               implode("\n", array(
                  "    `itemtype` = '" . $item_type . "'",
                  "and `items_id` = ".$item_id,
                  "and `plugin_fusioninventory_taskjobs_id` = ". $job_id,
                  "and `state` not in ( " . implode( "," , array(
                     PluginFusioninventoryTaskjobstate::FINISHED,
                     PluginFusioninventoryTaskjobstate::IN_ERROR,
                     PluginFusioninventoryTaskjobstate::CANCELLED
                  )) . ")",
                  "and `plugin_fusioninventory_agents_id` in (",
                  "  '" . implode("','", array_keys($agent_ids)) . "'",
                  ")"
               ))
            );

            // Filter out agents that are already running the targets.
            foreach( $jobstates_running as $jobstate_running) {

               $jobstate_agent_id = $jobstate_running['plugin_fusioninventory_agents_id'];
               if ( isset( $agent_ids[$jobstate_agent_id] )
               ) {
                  $agent_ids[$jobstate_agent_id] = false;
               }
            }

            foreach($agent_ids as $agent_id => $agent_not_running) {
               if( $agent_not_running) {
                  $run = array_merge(
                     $run_base,
                     array(
                        'itemtype' => $item_type,
                        'items_id' => $item_id,
                        'plugin_fusioninventory_taskjobs_id' => $job_id,
                        'plugin_fusioninventory_agents_id' => $agent_id,
                        'uniqid' => uniqid(),
                     )
                  );

                  $run_id = $jobstate->add($run);
                  if ($run_id !== false) {
                     $log = array_merge(
                        $log_base,
                        array(
                           'plugin_fusioninventory_taskjobstates_id' => $run_id,
                           ''
                        )
                     );
                     $joblog->add($log);
                  }
               }
            }

         }

      }

      return true;

   }

   /**
    * Get Computers from Actors defined in taskjobs
    * TODO: this method should be rewritten to call directly a getAgents() method in the
    * corresponding itemtype classes.
    */
   public function getAgentsFromActors($actors = array()) {
      $agents = array();
      $computers = array();
      $computer = new Computer();
      $agent = new PluginFusioninventoryAgent();
      foreach($actors as $actor) {
         $itemtype = key($actor);
         $itemid = $actor[$itemtype];
         $item = getItemForItemtype($itemtype);
         $item->getFromDB($itemid);
         switch($itemtype) {
            case 'Computer':
                  $computers[$itemid] = 1;
               break;
            case 'PluginFusioninventoryDeployGroup':

               // Force user and active entity Session since Search class can't live without it.
               // (cf. DeployGroupDynamicData)
               $OLD_SESSION = array();
               if (isset($_SESSION['glpiname'])) {
                  $OLD_SESSION['glpiname'] = $_SESSION['glpiname'];
               }
               if (isset($_SESSION['glpiactiveentities_string'])) {
                  $OLD_SESSION['glpiactiveentities_string'] = $_SESSION['glpiactiveentities_string'];
               }
               $_SESSION['glpiname'] = 'Plugin_FusionInventory';
               $_SESSION['glpiactiveentities_string'] = '0';

               foreach(
                  PluginFusioninventoryDeployGroup::getTargetsForGroup($itemid) as $computerid
               ) {
                  $computers[$computerid] = 1;
               }
               // Get back to original session variable
               if (isset($OLD_SESSION['glpiname'])) {
                  $_SESSION['glpiname'] = $OLD_SESSION['glpiname'];
               }
               if (isset($OLD_SESSION['glpiactiveentities_string'])) {
                  $_SESSION['glpiactiveentities_string'] = $OLD_SESSION['glpiactiveentities_string'];
               }
               break;
            case 'Group':

               //find computers by user associated with this group
               $group_users   = new Group_User();

               $members = array();

               //array_keys($group_users->find("groups_id = '$items_id'"));
               $members = $group_users->getGroupUsers($itemid);

               foreach ($members as $member) {
                  $computers_from_user = $computer->find("users_id = '${member['id']}'");
                  foreach($computers_from_user as $computer_entry) {
                     $computers[$computer_entry['id']] = 1;
                  }
               }

               //find computers directly associated with this group
               $computer_from_group = $computer->find("groups_id = '$itemid'");
               foreach($computer_from_group as $computer_entry) {
                  $computers[$computer_entry['id']] = 1;
               }

               break;

            /**
             * TODO: The following should be replaced with Dynamic groups
             */
            case 'PluginFusioninventoryAgent':
               switch($itemid) {
                  case "dynamic":
                     break;
                  case "dynamic-same-subnet":
                     break;
                  default:
                     $agents[$itemid] = 1;
                     break;
               }
               break;
         }
      }
      //Get agents from the computer's ids list
      foreach($agent->getAgentsFromComputers(array_keys($computers)) as $agent_entry) {
         $agents[$agent_entry['id']] = 1;
      }

      // Return the list of agent's ids.
      // (We used hash keys to avoid duplicates in the list)
      return(array_keys($agents));
   }

   /**
   * Prepare Taskjobs for current
   *
   * @return bool cron is ok or not
   *
   **/
   static function cronTaskscheduler() {
      $task = new self();
      $methods = array();
      foreach( PluginFusioninventoryStaticmisc::getmethods() as $method) {
         $methods[] = $method['method'];
      }
      $task->prepareTaskjobs($methods);
      return true;
   }

   static function getJoblogs($task_ids = array()) {
      global $DB;

      //$methods_restrict = null;
      //if( !is_array($methods) ) {
      //   trigger_error("'methods' must be an array.");
      //} else {
      //   if (count($methods_restrict) > 0) {
      //      $methods_restrict = "and job.`method` in ('".implode("','",$methods)."')";
      //   }
      //}


      $where = array();
      $where[] = "where 1";

      if (isset($_SESSION['glpiactiveentities_string'])) {
         $where[] = getEntitiesRestrictRequest("AND", 'task');
      }

      if ( is_array($task_ids) and count($task_ids) > 0 ) {
         $where[] = "and task.`id` in (" . implode(",",$task_ids) . ")";
      }
      $query = implode("\n", array(
         "select",
         "  task.`id`, task.`name`,",
         "  job.`id`, job.`name`, job.`method`,",
         "  run.*,",
         "  agent.*,",
         "  log.*",
         "from `glpi_plugin_fusioninventory_tasks` as task",
         "left join `glpi_plugin_fusioninventory_taskjobs` as job",
         "  on job.`plugin_fusioninventory_tasks_id` = task.`id`",
         "left join `glpi_plugin_fusioninventory_taskjobstates` as run",
         "  on run.`plugin_fusioninventory_taskjobs_id` = job.`id`",
         // Filter out entries where agents doesn't exists anymore
         // TODO: Clean those entries in database by the logcleaner crontask.
         "inner join `glpi_plugin_fusioninventory_agents` as agent",
         "  on run.`plugin_fusioninventory_agents_id` = agent.`id`",
         "left join `glpi_plugin_fusioninventory_taskjoblogs` as log",
         "  on log.`plugin_fusioninventory_taskjobstates_id` = run.`id`",
         implode("\n", array_filter($where)),
         "order by",
         "  task.`id`, job.`id`, log.`date` DESC, log.`id` DESC",
      ));
      $query_result = $DB->query($query);
      $results = array();
      if ( $query_result ) {
         $results = PluginFusioninventoryToolbox::fetchAssocByTable($query_result);
      }
      //Reformat result by task's id then by job's ids then by jobstate's ids
      $logs = array();
      $runclass = new PluginFusioninventoryTaskjobstate();
      $run_states = $runclass->getStateNames();

      foreach($results as $result) {
         $task_id = $result['task']['id'];
         if (!array_key_exists($task_id, $logs)) {
            $logs[$task_id] = array(
               'task_name' => $result['task']['name'],
               'jobs' => array()
            );
         };


         $job_id = $result['job']['id'];
         $jobs =& $logs[$task_id]['jobs'];
         if ( !array_key_exists($job_id, $jobs) ) {
            $jobs[$job_id] = array(
               'name' => $result['job']['name'],
               'id' => $result['job']['method'] . '_'. $result['job']['id'],
               'method' => $result['job']['method'],
               'targets' => array()
            );
         }

         $target_id = $result['run']['itemtype'].'_'.$result['run']['items_id'];
         $targets =& $jobs[$job_id]['targets'];
         $agent_state_types = array(
            'agents_prepared', 'agents_cancelled', 'agents_running',
            'agents_success', 'agents_error', 'agents_notdone'
         );
         if ( !array_key_exists($target_id, $targets) ) {
            $item_class =$result['run']['itemtype'];
            $item = new $item_class();
            $item->getFromDB($result['run']['items_id']);
            $targets[$target_id] = array(
               'id'   => $item->fields['id'],
               'name' => $item->fields['name'],
               'type_name' => $item->getTypeName(),
               'item_link' => $item->getLinkUrl(),
               'counters' => array()
            );
            // create agent states counter lists
            foreach($agent_state_types as $type) {
               $targets[$target_id]['counters'][$type] = array();
            }

            $targets[$target_id]['agents'] = array();
         }
         $counters = &$targets[$target_id]['counters'];


         $agent_id = $result['run']['plugin_fusioninventory_agents_id'];
         $agents =& $targets[$target_id]['agents'];
         if ( !array_key_exists($agent_id, $agents) ) {
            $agent_class = new PluginFusioninventoryAgent();
            $agent_class->fields['id'] = $agent_id;
            $result['agent']['url'] = $agent_class->getLinkUrl();
            $agents[$agent_id] = array_merge(
               $result['agent'],
               array(
                  'runs' => array()
               )
            );
         }

         $run_id = $result['run']['id'];
         $runs =& $agents[$agent_id]['runs'];
         if ( !array_key_exists($run_id, $runs) ) {
            $runs[$run_id] = array_merge(
               $result['run'],
               array(
                  'state'  => $result['run']['state'],
                  'state_name' => $run_states[$result['run']['state']],
                  'agent'  => $result['agent']['name'],
                  'logs'   => array()
               )
            );
         }

         // Finally, add logs to the current run
         $log = $result['log'];
         $runs[$run_id]['logs'][] = $log;

         // Update counters
         // TODO: This should be done after parsing and formatting the result if we sort those logs
         // by ascending or descending log.`date`
         switch ($result['run']['state'] ) {
            case PluginFusioninventoryTaskjobstate::CANCELLED :
               // We put this agent in the cancelled counter if it does not have any other job
               // states.
               if (
                  !isset( $counters['agents_prepared'][$agent_id])
                  and !isset( $counters['agents_running'][$agent_id])
               ) {
                  $counters['agents_cancelled'][$agent_id] = 1;
               }

               break;
            case PluginFusioninventoryTaskjobstate::PREPARED :
               // We put this agent in the prepared counter if it has not yet completed any job.
               $counters['agents_prepared'][$agent_id] = 1;
               break;
            case PluginFusioninventoryTaskjobstate::SERVER_HAS_SENT_DATA :
            case PluginFusioninventoryTaskjobstate::AGENT_HAS_SENT_DATA :
               // This agent is running so it must not be in any other counter
               foreach( $agent_state_types as $type ) {
                  if ( isset($counters[$type][$agent_id]) ){
                     unset($counters[$type][$agent_id]);
                  }
                  $counters['agents_running'][$agent_id] = 1;
               }

               break;
            case PluginFusioninventoryTaskjobstate::IN_ERROR :
            case PluginFusioninventoryTaskjobstate::FINISHED :
               if (
                     !isset($counters['agents_error'][$agent_id])
                     and !isset($counters['agents_success'][$agent_id])
               ) {
                  reset($runs[$run_id]['logs']);
                  $last_log = current($runs[$run_id]['logs']);
                  $pfLog = new PluginFusioninventoryTaskjoblog();
                  // This agent is finished so it must not be in any other counter
                  //foreach( array('agents_success', 'agents_error') as $type ) {
                  //   if ( isset($current_target[$type][$agent_id]) ){
                  //      unset($current_target[$type][$agent_id]);
                  //   }
                  //}
                  switch($last_log['state']) {
                     case $pfLog::TASK_ERROR :
                        // TODO: The following state can be dropped but we must adapt
                        // every submodule before removal.
                     case $pfLog::TASK_ERROR_OR_REPLANNED :
                        $counters['agents_error'][$agent_id] = 1;
                        break;

                     case $pfLog::TASK_OK :
                        $counters['agents_success'][$agent_id] = 1;
                        break;

                  }
                  if ( isset($counters['agents_notdone'][$agent_id]) ) {
                     unset($counters['agents_notdone'][$agent_id]);
                  }
               }
               break;
         }
         if (
               !isset($counters['agents_error'][$agent_id])
            and !isset($counters['agents_success'][$agent_id])
         ) {
            $counters['agents_notdone'][$agent_id] = 1;
         }
         if (
                  isset($counters['agents_error'][$agent_id])
               or isset($counters['agents_success'][$agent_id])
               or isset($counters['agents_running'][$agent_id])
               or isset($counters['agents_prepared'][$agent_id])
         ) {
            unset($counters['agents_cancelled'][$agent_id]);
         }
      }
      return $logs;
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



   /**
   *  Get tasks filtered by relevant criterias
   *  @param $filter criterias to filter in the request
   **/
   static function getItemsFromDB($filter) {

      global $DB;
      $select = array("tasks"=>"task.*");
      $where = array();
      $leftjoin = array();

      // Filter active tasks
      if (     isset($filter['is_active'])
            && is_bool($filter['is_active']) ) {
         $where[] = "task.`is_active` = " . $filter['is_active'];
      }

      //Filter by running taskjobs
      if (     isset( $filter['is_running'] )
            && is_bool( $filter['is_running'] ) ) {
         //TODO: get running taskjobs
         if ( $filter['is_running'] ) {
            $where[] = "( task.`execution_id` != taskjob.`execution_id` )";
         } else {
            $where[] = "( task.`execution_id` = taskjob.`execution_id` )";
         }
         // add taskjobs table JOIN statement if not already set
         if ( !isset( $leftjoin['taskjobs'] ) ) {
               $leftjoin_bak = $leftjoin;
               $leftjoin_tmp = PluginFusioninventoryTaskJob::getJoinQuery();
               $leftjoin = array_merge( $leftjoin_bak, $leftjoin_tmp );
            if (!isset( $select["taskjobs"]) ) {
               $select['taskjobs'] = "taskjob.*";
            }
         }
      }

      //Filter by definition classes
      if (     isset($filter['definitions'])
            && is_array($filter['definitions']) ) {
         $where_tmp = array();
         //check classes existence and append them to the query filter
         foreach($filter['definitions'] as $itemclass => $itemid) {
            if ( class_exists($itemclass) ) {

               $cond = "taskjob.`definition` LIKE '%\"".$itemclass."\"";

               //adding itemid if not empty
               if (!empty($itemid)) {
                     $cond .= ":\"".$itemid."\"";
               }
               //closing LIKE statement
               $cond .= "%'";
               $where_tmp[] = $cond;
            }
         }
         //join every filtered conditions
         if( count($where_tmp) > 0) {
            // add taskjobs table JOIN statement if not already set
            if ( !isset( $leftjoin['taskjobs'] ) ) {
               $leftjoin_bak = $leftjoin;
               $leftjoin_tmp = PluginFusioninventoryTaskJob::getJoinQuery();
               $leftjoin = array_merge( $leftjoin_bak, $leftjoin_tmp );
            }
            if (!isset( $select["taskjobs"]) ) {
               $select['taskjobs'] = "taskjob.*";
            }
            $where[] = "( " . implode("OR", $where_tmp) . " )";
         }
      }

      //TODO: Filter by action classes

      //TODO: Filter by list of IDs
      if (     isset($filter['by_ids'])
            && is_bool($filter['by_entities']) ) {
      }

      // Filter by entity
      if (     isset($filter['by_entities'])
            && is_bool($filter['by_entities']) ) {
         $where[] = getEntitiesRestrictRequest( "", 'task' );
      }

      $results = NULL;
      $query =
         implode(
            "\n", array(
               "SELECT ".implode(',', $select),
               "FROM `glpi_plugin_fusioninventory_tasks` as task",
               implode("\n", $leftjoin),
               "WHERE\n    ".implode("\nAND ", $where)
            )
         );

      $results = array();
      $r = $DB->query($query);
      if ($r) {
         $results = PluginFusioninventoryToolbox::fetchAssocByTable($r);
      }

      return($results);
   }



   function getTasksInerror() {
      global $DB;

      $where = '';
      $where .= getEntitiesRestrictRequest("AND", 'glpi_plugin_fusioninventory_tasks');

      $query = "SELECT `glpi_plugin_fusioninventory_tasks`.*
         FROM `glpi_plugin_fusioninventory_tasks`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` AS taskjobs
            ON `plugin_fusioninventory_tasks_id` = `glpi_plugin_fusioninventory_tasks`.`id`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobstates` AS taskjobstates
            ON taskjobstates.`id` =
            (SELECT MAX(`id`)
             FROM glpi_plugin_fusioninventory_taskjobstates
             WHERE plugin_fusioninventory_taskjobs_id = taskjobs.`id`
             ORDER BY id DESC
             LIMIT 1
            )
         LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs`
            ON `glpi_plugin_fusioninventory_taskjoblogs`.`id` =
            (SELECT MAX(`id`)
            FROM `glpi_plugin_fusioninventory_taskjoblogs`
            WHERE `plugin_fusioninventory_taskjobstates_id`= taskjobstates.`id`
            ORDER BY id DESC LIMIT 1 )
         WHERE `glpi_plugin_fusioninventory_taskjoblogs`.`state`='4'
         ".$where."
         GROUP BY plugin_fusioninventory_tasks_id
         ORDER BY `glpi_plugin_fusioninventory_taskjoblogs`.`date` DESC";



      return $DB->query($query);
   }


}

