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
            PluginFusioninventoryTaskjobstate::AGENT_HAS_SENT_DATA,
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

      // Fetch a list of unique actors since the same actor can be assigned to many jobs.
      $actors = array();
      foreach($results as $result) {
         $actors_from_job = importArrayFromDB($result['job']['actors']);
         foreach($actors_from_job as $actor) {
            $actor_key = "".key($actor)."_".$actor[key($actor)];
            if( !isset($actors[$actor_key]) ) {
               $actors[$actor_key] = array();
               foreach( $this->getAgentsFromActors(array($actor)) as $agent ) {
                  $actors[$actor_key][$agent] = true;
               }
            }
         }
      }

      // Merge agents into one list
      $agents = array();
      foreach($actors as $key=>$agents_list) {
         //Toolbox::logDebug(array("agents_list count" => count($agents_list)));
         //Toolbox::logDebug(array("agents_list" => $agents_list));
         foreach( $agents_list as $id => $val) {
            if(! isset($agents[$id])) {
               $agents[$id] = true;
            }
         }
      }
      $agents = array_keys($agents);
      //Toolbox::logDebug(array("final agents list count" => count($agents)));
      //Toolbox::logDebug(array("final agents list" => $agents));


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
       * - The agent is still present in the dynamic actors (eg. Dynamic groups)
       */
      foreach($results as $result ) {

         $jobstate = new PluginFusioninventoryTaskjobstate();
         $jobstate->getFromDB($result['run']['id']);

         //Cancel the job it has already been sent to the agent but the agent did not replied
         if (
            $result['run']['state'] == $jobstate::SERVER_HAS_SENT_DATA
            or $result['run']['state'] == $jobstate::AGENT_HAS_SENT_DATA
         ) {
            $jobstates_to_cancel[$jobstate->fields['id']] = array(
               'jobstate' => $jobstate,
               'reason'   => __("The agent is requesting a configuration that has already been sent to him by the server. It is more likely that the agent is subject to a critical error.", 'fusioninventory'),
               'code' => $jobstate::IN_ERROR
            );
            continue;
         }
         //Cancel the jobstate if the related tasks has been deactivated
         if ($result['task']['is_active'] == 0) {
            $jobstates_to_cancel[$jobstate->fields['id']] = array(
               'jobstate' => $jobstate,
               'reason' => __('The task has been deactivated after preparation of this job.', 'fusioninventory')
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
                  'reason' => __("This job can not be executed anymore due to the task's schedule.", 'fusioninventory')
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
                  'reason' => __("This job can not be executed anymore due to the task's timeslot.", 'fusioninventory')
               );
               continue;
            }
         }

         // Make sure the agent is still present in the list of actors that generated
         // this jobstate.
         // TODO: If this jobstate needs to be cancelled, it would be worth to point out which actor
         // is the source of this execution. To do this, we need to track the 'actor_source' in the
         // jobstate when it's generated by prepareTaskjobs().

         //$job_actors = importArrayFromDB($result['job']['actors']);
         $chrono = microtime(true);
         if ( !in_array($agent_id, $agents) ) {
            $jobstates_to_cancel[$jobstate->fields['id']] = array(
               'jobstate' => $jobstate,
               'reason' => __('This agent does not belong anymore in the actors defined in the job.', 'fusioninventory')
            );
            continue;
         }

         //Toolbox::logDebug(
         //   sprintf(
         //      "Time to check if '%s' is still in actors list: %5.6f",
         //      $_GET['machineid'],(microtime(true) - $chrono)
         //   )
         //);

         //TODO: The following method (actually defined as member of taskjob) needs to be
         //initialized when getting the jobstate from DB (with a getfromDB hook for example)
         $jobstate->method = $result['job']['method'];

         //Add the jobstate to the list since previous checks are good.
         $jobstates[$jobstate->fields['id']] = $jobstate;
      }

      //Remove the list of jobstates previously filtered for removal.
      foreach( $jobstates_to_cancel as $jobstate) {
         if (!isset($jobstate['code'])) {
            $jobstate['code'] = PluginFusioninventoryTaskjobstate::CANCELLED;
         }
         switch($jobstate['code']) {
            case PluginFusioninventoryTaskjobstate::IN_ERROR:
               $jobstate['jobstate']->fail($jobstate['reason']);
               break;
            default:
               $jobstate['jobstate']->cancel($jobstate['reason']);
               break;
         }
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

      $query = implode( " \n", array(
         "SELECT",
         "     task.`id`, task.`name`,",
         "     job.`id`, job.`name`, job.`method`, ",
         "     job.`targets`, job.`actors`",
         "FROM `glpi_plugin_fusioninventory_taskjobs` job",
         "LEFT JOIN `glpi_plugin_fusioninventory_tasks` task",
         "  ON task.`id` = job.`plugin_fusioninventory_tasks_id`",
         "WHERE task.`is_active` = 1",
         "AND (",
         /**
          * Filter jobs by the schedule and timeslots
          */
         // check only if now() >= datetime_start if datetime_end is null
         "        (   task.`datetime_start` IS NOT NULL AND task.`datetime_end` IS NULL",
         "              AND '".$now->format("Y-m-d H:i:s")."' >= task.`datetime_start` )",
         "     OR",
         // check if now() is between datetime_start and datetime_end
         "        (   task.`datetime_start` IS NOT NULL AND task.`datetime_end` IS NOT NULL",
         "              AND '".$now->format("Y-m-d H:i:s")."' ",
         "                    between task.`datetime_start` AND task.`datetime_end` )",
         "     OR",
         // finally, check if this task can be run at any time ( datetime_start and datetime_end are
         // both null)
         "        ( task.`datetime_start` IS NULL AND task.`datetime_end` IS NULL )",
         ")",
         "AND job.`method` IN (".$methods.")",
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

      // Fetch a list of actors to be prepared. We may have the same actors for each job so this
      // part can speed up the process.
      //$actors = array();

      // Set basic elements of jobstates
      $run_base = array(
         'state' => PluginFusioninventoryTaskjobstate::PREPARED,
      );
      $log_base = array(
         'date'    => $now->format("Y-m-d H:i:s"),
         'state'   => PluginFusioninventoryTaskjoblog::TASK_PREPARED,
         'comment' => ''
      );

      $jobstate = new PluginFusioninventoryTaskjobstate();
      $joblog   = new PluginFusioninventoryTaskjoblog();

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
         $saved_agent_ids = $agent_ids;
         $targets = importArrayFromDB($result['job']['targets']);
         if ($result['job']['method'] == 'networkinventory') {
            $pfNetworkinventory = new PluginFusioninventoryNetworkinventory();
            foreach($targets as $keyt=>$target) {
               $item_type = key($target);
               $items_id = current($target);
               if ($item_type == 'PluginFusioninventoryIPRange') {
                  unset($targets[$keyt]);
                  // In this case get devices of this iprange
                  $deviceList = $pfNetworkinventory->getDevicesOfIPRange($items_id);
                  $targets = array_merge($targets, $deviceList);
               }
            }
         }

         $limit = 0;
         foreach($targets as $target) {
            $agent_ids = $saved_agent_ids;
            $item_type = key($target);
            $item_id = current($target);
            $job_id = $result['job']['id'];
            $jobstates_running = $jobstate->find(
               implode(" \n", array(
                  "    `itemtype` = '" . $item_type . "'",
                  "AND `items_id` = ".$item_id,
                  "AND `plugin_fusioninventory_taskjobs_id` = ". $job_id,
                  "AND `state` not in ('" . implode( "','" , array(
                     PluginFusioninventoryTaskjobstate::FINISHED,
                     PluginFusioninventoryTaskjobstate::IN_ERROR,
                     PluginFusioninventoryTaskjobstate::CANCELLED
                  )) . "')",
                  "AND `plugin_fusioninventory_agents_id` IN (",
                  "'" . implode("','", array_keys($agent_ids)) . "'",
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



            // Cancel agents prepared but not in $agent_ids (like computer
            // not in dynamic group)
            $jobstates_tocancel = $jobstate->find(
               implode(" \n", array(
                  "    `itemtype` = '" . $item_type . "'",
                  "AND `items_id` = ".$item_id,
                  "AND `plugin_fusioninventory_taskjobs_id` = ". $job_id,
                  "AND `state` not in ('" . implode( "','" , array(
                     PluginFusioninventoryTaskjobstate::FINISHED,
                     PluginFusioninventoryTaskjobstate::IN_ERROR,
                     PluginFusioninventoryTaskjobstate::CANCELLED
                  )) . "')",
                  "AND `plugin_fusioninventory_agents_id` NOT IN (",
                  "'" . implode("','", array_keys($agent_ids)) . "'",
                  ")"
               ))
            );
            foreach ($jobstates_tocancel as $jobstate_tocancel) {
               $jobstate->getFromDB($jobstate_tocancel['id']);
               $jobstate->cancel(__('Device no longer defined in definition of job', 'fusioninventory'));
            }

            foreach($agent_ids as $agent_id => $agent_not_running) {

               if( $agent_not_running) {

                  $limit += 1;
                  if ($limit > 500) {
                     $limit = 0;
                     break;
                  }
                  $run = array_merge(
                     $run_base,
                     array(
                        'itemtype'                           => $item_type,
                        'items_id'                           => $item_id,
                        'plugin_fusioninventory_taskjobs_id' => $job_id,
                        'plugin_fusioninventory_agents_id'   => $agent_id,
                        'uniqid'                             => uniqid(),
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
      $pfToolbox = new PluginFusioninventoryToolbox();
      foreach($actors as $actor) {
         $itemtype = key($actor);
         $itemid = $actor[$itemtype];
         $item = getItemForItemtype($itemtype);
         $dbresult = $item->getFromDB($itemid);
         // If this item doesn't exists, we continue to the next actor item.
         // TODO: remove this faulty actor from the list of job actor.
         if ($dbresult === false) { continue ; }

         switch($itemtype) {

            case 'Computer':
                  $computers[$itemid] = 1;
               break;

            case 'PluginFusioninventoryDeployGroup':

               $group_targets = $pfToolbox->executeAsFusioninventoryUser(
                  'PluginFusioninventoryDeployGroup::getTargetsForGroup',
                  array($itemid)
               );
               foreach( $group_targets as $computerid ) {
                  $computers[$computerid] = 1;
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

      ini_set("max_execution_time", "0");

      $task = new self();
      $methods = array();
      foreach( PluginFusioninventoryStaticmisc::getmethods() as $method) {
         $methods[] = $method['method'];
      }
      $task->prepareTaskjobs($methods);
      return true;
   }

   static function FormatChrono($chrono) {
      $interval = abs($chrono['end'] - $chrono['start']);
      $micro = intval($interval * 100);
      $seconds = intval($interval % 60);
      $minutes = intval($interval / 60);
      $hours = intval($interval / 60 / 60);
      return "${hours}h ${minutes}m ${seconds}s ${micro}µs";
   }

   function getJoblogs($task_ids = array()) {
      global $DB;

      $debug_mode = ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE);

      $runclass = new PluginFusioninventoryTaskjobstate();
      $run_states = $runclass->getStateNames();

      $query_where = array();
      $query_where[] = "WHERE 1";

      if (isset($_SESSION['glpiactiveentities_string'])) {
         $query_where[] = getEntitiesRestrictRequest("AND", 'task');
      }

      if ( is_array($task_ids) and count($task_ids) > 0 ) {
         $query_where[] = "AND task.`id` IN (" . implode(",",$task_ids) . ")";
      }

      // quickly filter empty WHERE entry
      $query_where = array_filter($query_where);

      $query_fields = array(
         array('task.id'      , 'task.`id`'),
         array('task.name'    , 'task.`name`'),
         array('job.id'       , 'job.`id`'),
         array('job.name'     , 'job.`name`'),
         array('job.method'   , 'job.`method`'),
         array('job.targets'   , 'job.`targets`'),
      );

      $fieldmap = array();
      foreach($query_fields  as $index => $key) {
         $fieldmap[$key[0]]=$index;
      }

      $query_select = array();
      foreach($query_fields as $index => $key) {
         $query_select[] = $key[1] . " AS '" . $key[0] . "'";
      }

      $query_joins = array();
      $query_joins['task'] = implode( "\n", array(
         "INNER JOIN `glpi_plugin_fusioninventory_tasks` as task",
         "  ON job.`plugin_fusioninventory_tasks_id` = task.`id`",
      ));

      $data_structure = array(
         'query' => implode("\n", array(
            "SELECT",
            implode(",\n", $query_select),
            "FROM `glpi_plugin_fusioninventory_taskjobs` as job",
            implode("\n", $query_joins),
            implode("\n", $query_where)
         )),
         'result' => null
      );

      $data_structure['result'] = $DB->query($data_structure['query']);

      //Results grouped by tasks > jobs > jobstates
      $logs = array();

      //Target cache (used to speed up data formatting)
      $targets_cache = array();

      while( $result = $data_structure['result']->fetch_row() ) {
         $task_id = $result[$fieldmap['task.id']];
         if (!array_key_exists($task_id, $logs)) {
            $logs[$task_id] = array(
               'task_name' => $result[$fieldmap['task.name']],
               'task_id' => $result[$fieldmap['task.id']],
               'jobs'      => array()
            );
         }
         $job_id = $result[$fieldmap['job.id']];
         $jobs_handle = &$logs[$task_id]['jobs'];
         if ( !array_key_exists($job_id, $jobs_handle) ) {
            $jobs_handle[$job_id] = array(
               'name'    => $result[ $fieldmap['job.name']],
               'id'      => $result[ $fieldmap['job.id']],
               'method'  => $result[ $fieldmap['job.method']],
               'targets' => array()
            );
         }
         $targets = importArrayFromDB($result[$fieldmap['job.targets']]);
         $targets_handle = &$jobs_handle[$job_id]['targets'];
         $agent_state_types = array(
            'agents_prepared', 'agents_cancelled', 'agents_running',
            'agents_success', 'agents_error', 'agents_notdone'
         );
         foreach( $targets as $target) {
            $item_type = key($target);
            $item_id = current($target);

            $target_id = $item_type . "_" . $item_id;
            if (isset($targets_cache[$target_id])) {
               $item = $targets_cache[$target_id];
            } else {
               $item = new $item_type();
               $item->getFromDB($item_id);
               $targets_cache[$target_id] = $item;
            }
            $targets_handle[$target_id] = array(
               'id'        => $item->fields['id'],
               'name'      => $item->fields['name'],
               'type_name' => $item->getTypeName(),
               'item_link' => $item->getLinkUrl(),
               'counters'  => array(),
               'agents' => array()
            );
            // create agent states counter lists
            foreach($agent_state_types as $type) {
               $targets_handle[$target_id]['counters'][$type] = array();
            }
         }
      }


      // Query fields mapping used to easily select fields by name
      $query_fields = array(
         array( 'task.id'        , 'task.`id`'),
         array( 'task.name'      , 'task.`name`'),
         array( 'job.id'         , 'job.`id`'),
         array( 'job.name'       , 'job.`name`'),
         array( 'job.method'     , 'job.`method`'),
         array( 'agent.id'       , 'agent.`id`'),
         array( 'agent.name'     , 'agent.`name`'),
         array( 'run.id'         , 'run.`id`'),
         array( 'run.itemtype'   , 'run.`itemtype`'),
         array( 'run.items_id'   , 'run.`items_id`'),
         array( 'run.state'      , 'run.`state`'),
         array( 'log.last_date'  , 'log.`date`'),
         array( 'log.last_timestamp'  , 'UNIX_TIMESTAMP(log.`date`)'),
         array( 'log.last_id'  , 'log.`id`'),
         array( 'log.last_comment'  , 'log.`comment`'),
      );
      $fieldmap = array();
      foreach($query_fields  as $index => $key) {
         $fieldmap[$key[0]]=$index;
      }

      $query_select = array();
      foreach($query_fields as $index => $key) {
         $query_select[] = $key[1] . " AS '" . $key[0] . "'";
      }

      $query_joins = array();
      $query_joins['max_run'] = implode("\n",array(
         "INNER JOIN (",
         "  SELECT",
         "     MAX(run.`id`) AS max_id,",
         "     run.`plugin_fusioninventory_agents_id`,",
         "     run.`plugin_fusioninventory_taskjobs_id`,",
         "     run.`items_id`, run.`itemtype`,",
         "     MAX(log.`id`) AS max_log_id",
         "  FROM `glpi_plugin_fusioninventory_taskjobstates` AS run",
         "  LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs` AS log",
         "  ON log.`plugin_fusioninventory_taskjobstates_id` = run.`id`",
         "  GROUP BY",
         "     run.`plugin_fusioninventory_agents_id`,",
         "     run.`plugin_fusioninventory_taskjobs_id`,",
         "     run.`items_id`, run.`itemtype`",
         ") max_run ON max_run.`plugin_fusioninventory_agents_id` = agent.`id`",
      ));

      $query_joins['run'] = implode("\n",array(
         "INNER JOIN `glpi_plugin_fusioninventory_taskjobstates` AS run",
         "  ON max_run.`max_id` = run.`id`",
      ));
      $query_joins['log'] = implode( "\n", array(
         "LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs` as log",
         "  ON log.`id` = max_run.`max_log_id`",
      ));
      $query_joins['job'] = implode( "\n", array(
         "INNER JOIN `glpi_plugin_fusioninventory_taskjobs` AS job",
         "  ON job.`id` = run.`plugin_fusioninventory_taskjobs_id`",
      ));
      $query_joins['task'] = implode( "\n", array(
         "INNER JOIN `glpi_plugin_fusioninventory_tasks` as task",
         "  ON job.`plugin_fusioninventory_tasks_id` = task.`id`",
      ));

      $queries = array();

      /*
       * Get latest jobstates for agents
       */
      $queries['1_last_runs'] = array(
         'query' => implode(" \n", array(
            "SELECT",
            implode( ",\n", $query_select),
            "FROM `glpi_plugin_fusioninventory_agents` AS agent",
            implode( "\n", $query_joins),
            implode("\n", $query_where),
            "GROUP BY job.`id`, agent.`id`, run.`id`",
         )),
         'result' => null
      );

      /*
       * Get last finished jobstates (ie. `state` >= 3)
       */
      $query_joins['max_run'] = implode("\n",array(
         "INNER JOIN (",
         "  SELECT",
         "     MAX(run.`id`) AS max_id,",
         "     run.`plugin_fusioninventory_agents_id`,",
         "     run.`plugin_fusioninventory_taskjobs_id`,",
         "     run.`items_id`, run.`itemtype`,",
         "     MAX(log.`id`) AS max_log_id",
         "  FROM `glpi_plugin_fusioninventory_taskjobstates` AS run",
         "  LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs` AS log",
         "  ON log.`plugin_fusioninventory_taskjobstates_id` = run.`id`",
         "  WHERE run.`state` IN ( ".
            implode(",", array(
               PluginFusioninventoryTaskjobstate::FINISHED,
               PluginFusioninventoryTaskjobstate::IN_ERROR,
            )) .
         " )",
         "  GROUP BY",
         "     run.`plugin_fusioninventory_agents_id`,",
         "     run.`plugin_fusioninventory_taskjobs_id`,",
         "     run.`items_id`, run.`itemtype`",
         ") max_run ON max_run.`plugin_fusioninventory_agents_id` = agent.`id`",
      ));
      $queries['2_finished_runs'] = array(
         'query' => implode(" \n", array(
            "SELECT",
            implode( ",\n", $query_select),
            "FROM `glpi_plugin_fusioninventory_agents` AS agent",
            implode( "\n", $query_joins),
            implode( "\n", $query_where),
            "GROUP BY job.`id`, agent.`id`, run.`id`",
         )),
         'result' => null
      );


      $query_chrono = array(
         "start" => microtime(true),
         "end"   => 0
      );
      ksort($queries);
      foreach($queries as $query_name => $contents) {
         $queries[$query_name]['result'] = $DB->query($contents['query']);

         $query_chrono['end'] = microtime(true);
         // For debug only
         //if ($debug_mode) {
         //   file_put_contents("/tmp/glpi_".$query_name.".sql",$contents['query']);
         //}
      };


      $agents = array();


      $format_chrono = array(
         "start" => microtime(true),
         "end"   => 0
      );

      foreach($queries as $query_name => $contents) {
         if (!is_null($contents['result'])) {
            while( $result = $contents['result']->fetch_row()) {

               // We need to check if the results are consistent with the view's structure gathered
               // by the first query
               $task_id = $result[$fieldmap['task.id']];
               //if (!array_key_exists($task_id, $logs)) {
               if ( !isset($logs[$task_id]) ) {
                  continue;
               };

               $job_id = $result[$fieldmap['job.id']];
               $jobs = &$logs[$task_id]['jobs'];
               if ( !isset($jobs[$job_id]) ) {
                  continue;
               }

               $target_id = $result[ $fieldmap['run.itemtype']].'_'.$result[$fieldmap['run.items_id']];
               $targets = &$jobs[$job_id]['targets'];
               if ( !isset($targets[$target_id]) ) {
                  continue;
               }
               $counters = &$targets[$target_id]['counters'];

               $agent_id = $result[$fieldmap['agent.id']];
               $agents[$agent_id] = $result[$fieldmap['agent.name']];

               if ( ! isset($targets[$target_id]['agents'][$agent_id]) ) {
                  $targets[$target_id]['agents'][$agent_id] = array();
               }
               $agent_state = '';
               // Update counters
               switch ($result[$fieldmap['run.state']] ) {

                  case PluginFusioninventoryTaskjobstate::CANCELLED :
                     // We put this agent in the cancelled counter if it does not have any other job
                     // states.
                     if (
                        !isset( $counters['agents_prepared'][$agent_id])
                        and !isset( $counters['agents_running'][$agent_id])
                     ) {
                        $counters['agents_cancelled'][$agent_id] = 1;
                        $agent_state = 'cancelled';
                     }

                     break;

                  case PluginFusioninventoryTaskjobstate::PREPARED :
                     // We put this agent in the prepared counter if it has not yet completed any job.
                     $counters['agents_prepared'][$agent_id] = 1;
                     $agent_state = 'prepared';
                     break;

                  case PluginFusioninventoryTaskjobstate::SERVER_HAS_SENT_DATA :
                  case PluginFusioninventoryTaskjobstate::AGENT_HAS_SENT_DATA :
                     // This agent is running so it must not be in any other counter
                     foreach( $agent_state_types as $type ) {
                        if ( isset($counters[$type][$agent_id]) ){
                           unset($counters[$type][$agent_id]);
                        }
                     }
                     $counters['agents_running'][$agent_id] = 1;
                     $agent_state = 'running';

                     break;

                  case PluginFusioninventoryTaskjobstate::IN_ERROR :
                     if( isset($counters['agents_success'][$agent_id]) ) {
                        unset($counters['agents_success'][$agent_id]);
                     }

                     $counters['agents_error'][$agent_id] = 1;
                     $agent_state = 'error';

                     if ( isset($counters['agents_notdone'][$agent_id]) ) {
                        unset($counters['agents_notdone'][$agent_id]);
                     }
                     break;
                  case PluginFusioninventoryTaskjobstate::FINISHED :
                     if( isset($counters['agents_error'][$agent_id]) ) {
                        unset($counters['agents_error'][$agent_id]);
                     }

                     $counters['agents_success'][$agent_id] = 1;
                     $agent_state = 'success';

                     if ( isset($counters['agents_notdone'][$agent_id]) ) {
                        unset($counters['agents_notdone'][$agent_id]);
                     }
                     break;

               }
               if (
                      !isset($counters['agents_error'][$agent_id])
                  and !isset($counters['agents_success'][$agent_id])
               ){
                  $counters['agents_notdone'][$agent_id] = 1;
               }
               if (
                     isset($counters['agents_running'][$agent_id])
                  or isset($counters['agents_prepared'][$agent_id])
               ) {
                  unset($counters['agents_cancelled'][$agent_id]);
               }

               $targets[$target_id]['agents'][$agent_id][] = array(
                  'agent_id' => $agent_id,
                  'numstate' => $result[$fieldmap['run.state']],
                  'state' => $agent_state,
                  'jobstate_id' => $result[$fieldmap['run.id']],
                  'last_log_id' => $result[$fieldmap['log.last_id']],
                  'last_log_date' => $result[$fieldmap['log.last_date']],
                  'timestamp' => $result[$fieldmap['log.last_timestamp']],
                  'last_log' => $result[$fieldmap['log.last_comment']]
               );
            }
         }
      }

      $format_chrono['end'] = microtime(true);
      if ($debug_mode) {

         function tmp_display_log($log) {
            return "ID:". $log['task_id'] . "(".$log['task_name'].")";
         }
         if (PluginFusioninventoryConfig::isExtradebugActive()) {
            Toolbox::logDebug(
               array(
                  "tasks" => implode(',',array_map('tmp_display_log', $logs)),
                  "row count" => count($logs),
                  "Joblogs Query"=>self::FormatChrono($query_chrono),
                  "Format logs results" => self::FormatChrono($format_chrono),
               )
            );
         }
      }
      return array('tasks' => $logs, 'agents' => $agents);
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
//         if ( $filter['is_running'] ) {
//            $where[] = "( task.`execution_id` != taskjob.`execution_id` )";
//         } else {
//            $where[] = "( task.`execution_id` = taskjob.`execution_id` )";
//         }
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

      // Filter by action classes
      if (isset($filter['actions'])
            && is_array($filter['actions']) ) {
         $where_tmp = array();
         //check classes existence and append them to the query filter
         foreach($filter['actions'] as $itemclass => $itemid) {
            if ( class_exists($itemclass) ) {

               $cond = "taskjob.`action` LIKE '%\"".$itemclass."\"";

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



   /**
    * @see CommonDBTM::post_updateItem()
   **/
   function post_updateItem($history=1) {
      global $DB, $CFG_GLPI;

      if (isset($this->oldvalues['is_active'])
              && $this->oldvalues['is_active'] == 1) {
         // If disable task, must end all taskjobstates prepared
         $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
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
            "  run.`state` IN ('". implode("','", array(
               PluginFusioninventoryTaskjobstate::PREPARED,
            ))."')",
            "  AND task.`id` = " . $this->fields['id'],
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
         foreach ($results as $data) {
            $pfTaskjobstate->getFromDB($data['run']['id']);
            $pfTaskjobstate->cancel(__('Task has been disabled', 'fusioninventory'));
         }
      }
      parent::post_updateItem($history);
   }
}

