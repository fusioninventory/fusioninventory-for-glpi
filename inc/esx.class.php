<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: Walid Nouh
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryESX extends PluginFusioninventoryCommunication {

   // Get all devices and put in taskjobstatus each task for each device for each agent
   function prepareRun($taskjobs_id) {
      global $DB;
   
      $task       = new PluginFusioninventoryTask();
      $job        = new PluginFusioninventoryTaskjob();
      $joblog     = new PluginFusioninventoryTaskjoblog();
      $jobstatus  = new PluginFusioninventoryTaskjobstatus();
   
      $uniqid= uniqid();
   
      $job->getFromDB($taskjobs_id);
      $task->getFromDB($job->fields['plugin_fusioninventory_tasks_id']);
   
      $communication= $task->fields['communication'];
   
      //list all agents
      $agent_actions     = importArrayFromDB($job->fields['action']);
      $task_definitions  = importArrayFromDB($job->fields['definition']);
      $agent_actionslist = array();
      foreach($agent_actions as $targets) {
         foreach ($targets as $itemtype => $items_id) {
            $item = new $itemtype();
            // Detect if agent exists
            if($item->getFromDB($items_id)) {
               $a_ip= $item->getIPs($items_id);
               foreach($a_ip as $ip) {
                  if($task->fields['communication'] == 'push') {
                     $agentStatus= $job->getStateAgent($ip, 0);
                     if($agentStatus) {
                        $agent_actionslist[$items_id] = $ip;
                     }
                  } elseif($task->fields['communication'] == 'pull') {
                     $agent_actionslist[$items_id] = 1;
                  }
               }
            }
         }
      }
   
      // *** Add jobstatus
      if(empty($agent_actionslist)) {
         $a_input= array();
         $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $a_input['state']                              = 0;
         $a_input['plugin_fusioninventory_agents_id']   = 0;
         $a_input['uniqid']                             = $uniqid;

         foreach ($task_definitions as $task_definition) {
            foreach ($task_definition as $task_itemtype => $task_items_id) {
               $a_input['itemtype'] = $task_itemtype;
               $a_input['items_id'] = $task_items_id;
               $jobstatus_id= $jobstatus->add($a_input);
               //Add log of taskjob
               $a_input['plugin_fusioninventory_taskjobstatus_id']= $jobstatus_id;
               $a_input['state'] = PluginFusioninventoryTaskjoblog::TASK_PREPARED;
               $a_input['date']  = date("Y-m-d H:i:s");
               $joblog->add($a_input);

               $jobstatus->changeStatusFinish($jobstatus_id, 0, 'PluginFusinvinventoryESX', 1, 
                                              "Unable to find agent to run this job");

            }
         }
         $job->fields['status']= 1;
         $job->update($job->fields);
      } else {
         foreach($agent_actions as $targets) {
            foreach ($targets as $items_id) {

               if ($communication == "push") {
                  $_SESSION['glpi_plugin_fusioninventory']['agents'][$items_id] = 1;
               }
               
               foreach ($task_definitions as $task_definition) {
                  foreach ($task_definition as $task_itemtype => $task_items_id) {
                     $a_input = array();
                     $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
                     $a_input['state']                              = 0;
                     $a_input['plugin_fusioninventory_agents_id']   = $items_id;
                     $a_input['itemtype']                           = $task_itemtype;
                     $a_input['items_id']                           = $task_items_id;
                     $a_input['uniqid']                             = $uniqid;
                     $a_input['date']                               = date("Y-m-d H:i:s");
                     $jobstatus_id = $jobstatus->add($a_input);
                     //Add log of taskjob
                     $a_input['plugin_fusioninventory_taskjobstatus_id'] = $jobstatus_id;
                     $a_input['state']= PluginFusioninventoryTaskjoblog::TASK_PREPARED;
      
                     $joblog->add($a_input);
                     unset($a_input['state']);
                  }
               }
            }
         }
   
         $job->fields['status']= 1;
         $job->update($job->fields);
      }
      return $uniqid;
   }
   

   
   function run($itemtype) {
      //Nothing to send in XML
      return $this->sxml;
   }
   
   
   
   static function getJobs($device_id) {
      $response      = array();
      $taskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $credential    = new PluginFusioninventoryCredential();
      $credentialip  = new PluginFusioninventoryCredentialIp();
      
      //Get the agent ID by his deviceid
      $agents = PluginFusioninventoryAgent::getByDeviceID($device_id);
      if ($agents) {
         
         //Get tasks associated with the agent
         $tasks_list = $taskjobstatus->getTaskjobsAgent($agents['id']);
         foreach ($tasks_list as $tasks) {
            //Foreach task for this agent build the response array
            foreach ($tasks as $task) {
               if ($task['state'] == PluginFusioninventoryTaskjobstatus::PREPARED) {
                  $credentialip->getFromDB($task['items_id']);
                  $credential->getFromDB($credentialip->fields['plugin_fusioninventory_credentials_id']);
                  $tmp = array();
                  $tmp['uuid']        = $task['id'];
                  $tmp['host']        = $credentialip->fields['ip'];
                  $tmp['user']        = $credential->fields['username'];
                  $tmp['password']    = $credential->fields['password'];
                  $response['jobs'][] = $tmp;
               }
            }
         }
      }
      return $response;
   }
}

?>