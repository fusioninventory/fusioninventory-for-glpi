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
 * This file is used to manage the wake up the agents
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the wake up the agents remotely.
 */
class PluginFusioninventoryAgentWakeup extends  CommonDBTM {


   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_taskjob';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('Job', 'fusioninventory');
   }


   /**
    * Check if can wake up an agent
    *
    * @return true
    */
   static function canCreate() {
      return true;
   }


   /*
    * @function cronWakeupAgents
    * This function update already running tasks with dynamic groups
    */


   /**
    * Cron task: wake up agents. Configuration is in each tasks
    *
    * @global object $DB
    * @param object $crontask
    * @return boolean true if successfully, otherwise false
    */
   static function cronWakeupAgents($crontask) {
      global $DB;

      $wakeupArray = [];
      $tasks       = [];
      //Get the maximum number of agent to wakeup,
      //as allowed in the general configuration
      $config = new PluginFusioninventoryConfig();
      $agent  = new PluginFusioninventoryAgent();

      $maxWakeUp   = $config->getValue('wakeup_agent_max');

      //Get all active timeslots
      $timeslot = new PluginFusioninventoryTimeslot();
      $timeslots = $timeslot->getCurrentActiveTimeslots();
      $query_timeslots = [
         'plugin_fusioninventory_timeslots_exec_id'   => 0
      ];
      if (!empty($timeslots)) {
         array_push($query_timeslots, [
            'plugin_fusioninventory_timeslots_exec_id' => $timeslots
         ]);
      }
      //Get all active task requiring an agent wakeup
      //Check all tasks without timeslot or task with a current active timeslot
      $iterator = $DB->request([
         'SELECT' => ['id', 'wakeup_agent_counter', 'wakeup_agent_time', 'last_agent_wakeup'],
         'FROM'   => 'glpi_plugin_fusioninventory_tasks',
         'WHERE'  => [
            'wakeup_agent_counter'  => ['>', 0],
            'wakeup_agent_time'     => ['>', 0],
            'is_active'             => 1,
            [
               'OR'   => $query_timeslots
            ]
         ]
      ]);

      while ($task = $iterator->next()) {
         if (!is_null($task['wakeup_agent_time'])) {
            //Do not wake up is last wake up in inferior to the minimum wake up interval
            $interval   = time() - strtotime($task['last_agent_wakeup']);
            if ($interval < ($task['wakeup_agent_time'] * MINUTE_TIMESTAMP)) {
               continue;
            }
         }
         $maxWakeUpTask = $task['wakeup_agent_counter'];
         if ($maxWakeUp < $maxWakeUpTask) {
            $maxWakeUpTask = $maxWakeUp;
         }

         //Store task ID
         if (!in_array($task['id'], $tasks)) {
            $tasks[] = $task['id'];
         }

         //For each task, get a number of taskjobs at the PREPARED state
         //(the maximum is defined in wakeup_agent_counter)
         $iterator2 = $DB->request([
            'SELECT'    => [
               'glpi_plugin_fusioninventory_taskjobstates.plugin_fusioninventory_agents_id',
            ],
            'FROM'      => [
               'glpi_plugin_fusioninventory_taskjobstates'
            ],
            'LEFT JOIN' => [
               'glpi_plugin_fusioninventory_taskjobs' => [
                  'FKEY' => [
                     'glpi_plugin_fusioninventory_taskjobs'    => 'id',
                     'glpi_plugin_fusioninventory_taskjobstates' => 'plugin_fusioninventory_taskjobs_id'
                  ]
               ]
            ],
            'WHERE'     => [
               'glpi_plugin_fusioninventory_taskjobs.plugin_fusioninventory_tasks_id' => $task['id'],
               'glpi_plugin_fusioninventory_taskjobstates.state'  => PluginFusioninventoryTaskjobstate::PREPARED
            ],
            'ORDER'     => 'glpi_plugin_fusioninventory_taskjobstates.id',
            'START'     => 0,
         ]);
         $counter = 0;

         while ($state = $iterator2->next()) {
            $agents_id = $state['plugin_fusioninventory_agents_id'];
            if (isset($wakeupArray[$agents_id])) {
               $counter++;
            } else {
               $agent->getFromDB($agents_id);
               $statusAgent = $agent->getStatus();
               if ($statusAgent['message'] == 'waiting') {
                  $wakeupArray[$agents_id] = $agents_id;
                  $counter++;
               }
            }

            // check if max number of agent reached for this task
            if ($counter >= $maxWakeUpTask) {
               break;
            }
         }
      }

      //Number of agents successfully woken up
      $wokeup = 0;
      if (!empty($tasks)) {
         //Update last wake up time each task
         $DB->update(
            'glpi_plugin_fusioninventory_tasks', [
               'last_agent_wakeup' => $_SESSION['glpi_currenttime']
            ], [
               'id' => $tasks
            ]
         );

         //Try to wake up agents one by one
         foreach ($wakeupArray as $ID) {
            $agent->getFromDB($ID);
            if ($agent->wakeUp()) {
               $wokeup++;
            }
         }
      }

      $crontask->addVolume($wokeup);
      return true;
   }


}
