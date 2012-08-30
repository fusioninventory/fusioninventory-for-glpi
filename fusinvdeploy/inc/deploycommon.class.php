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
   @author    Alexandre Delaunay
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if(!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


require_once(GLPI_ROOT."/plugins/fusioninventory/inc/ocscommunication.class.php");

class PluginFusinvdeployDeployCommon extends PluginFusioninventoryOCSCommunication {

   // Get all devices and put in taskjobstate each task for each device for each agent
   function prepareRun($taskjobs_id) {
      global $DB;

      $task       = new PluginFusioninventoryTask();
      $job        = new PluginFusioninventoryTaskjob();
      $joblog     = new PluginFusioninventoryTaskjoblog();
      $jobstate  = new PluginFusioninventoryTaskjobstate();
      $agent      = new PluginFusioninventoryAgent();

      $uniqid= uniqid();

      $job->getFromDB($taskjobs_id);
      $task->getFromDB($job->fields['plugin_fusioninventory_tasks_id']);

      $communication= $task->fields['communication'];

      $actions     = importArrayFromDB($job->fields['action']);
      $definitions   = importArrayFromDB($job->fields['definition']);
      $taskvalid = 0;

      $computers = array();
      foreach ($actions as $action) {
         $itemtype = key($action);
         $items_id = current($action);
         
         switch($itemtype) {
            case 'Computer':
               $computers[] = $items_id;
               break;
            case 'Group':
               $computer = new Computer;

               //find computers by user associated with this group
               $group_users = new Group_User;
               $users_id_a = array_keys($group_users->find("groups_id = '$items_id'"));
               $computers_a_1 = array();
               foreach ($users_id_a as $users_id) {
                  $computers_a_1 = array_keys($computer->find("users_id = '$users_id'"));
               }

               //find computers directly associated with this group
               $computers_a_2 = array_keys($computer->find("groups_id = '$items_id'"));

               //merge two previous array and deduplicate entries
               $computers = array_unique(array_merge($computers_a_1, $computers_a_2));

               break;
            case 'PluginFusinvdeployGroup':
               $group = new PluginFusinvdeployGroup;
               $group->getFromDB($items_id);

               switch ($group->getField('type')) {
                  case 'STATIC':
                     $query = "SELECT items_id
                     FROM glpi_plugin_fusinvdeploy_groups_staticdatas
                     WHERE groups_id = '$items_id'
                     AND itemtype = 'Computer'";
                     $res = $DB->query($query);
                     while ($row = $DB->fetch_assoc($res)) {
                        $computers[] = $row['items_id'];
                     }
                     break;
                  case 'DYNAMIC':
                     $query = "SELECT fields_array
                     FROM glpi_plugin_fusinvdeploy_groups_dynamicdatas
                     WHERE groups_id = '$items_id'";
                     $res = $DB->query($query);
                     $row = $DB->fetch_assoc($res);
                     $fields_array = unserialize($row['fields_array']);
                     if ($fields_array['operatingsystems_id'] != 0) unset($fields_array['operatingsystem_name']);
                     if ($fields_array['operatingsystems_id'] == 0) unset($fields_array['operatingsystems_id']);
                     if ($fields_array['locations_id'] == 0) unset($fields_array['locations_id']);
                     foreach($fields_array as $key =>$field) {
                        if (trim($field) == '') unset($fields_array[$key]);
                     }
                     $fields_array['limit'] == 999999999;
                     $datas = PluginWebservicesMethodInventaire::methodListInventoryObjects($fields_array, '');
                     foreach($datas as $data) {
                        $computers[] = $data['id'];
                     }
                     break;
               }
               break;
         }
      }


      $c_input= array();
      $c_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $c_input['state']                              = 0;
      $c_input['plugin_fusioninventory_agents_id']   = 0;
      $package = new PluginFusinvdeployPackage();

      foreach($computers as $computer_id) {
         //Unique Id match taskjobstatuses for an agent(computer)
//         $uniqid= uniqid();

         foreach($definitions as $definition) {
            $package->getFromDB($definition['PluginFusinvdeployPackage']);

            $c_input['state'] = 0;
            $c_input['itemtype'] = 'PluginFusinvdeployPackage';
            $c_input['items_id'] = $package->fields['id'];
            $c_input['date'] = date("Y-m-d H:i:s");
            $c_input['uniqid'] = $uniqid;

            //get agent if for this computer
            $agents_id = $agent->getAgentWithComputerid($computer_id);
            if($agents_id === false) {
               $jobstates_id = $jobstate->add($c_input);
               $jobstate->changeStatusFinish($jobstates_id,
                                                     0,
                                                     '',
                                                     1,
                                                     "No agent found for [[Computer::".$computer_id."]]",
                                                     0,
                                                     0);
            } else {
               $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

               # Push the agent, in the stack of agent to awake
               if ($communication == "push") {
                  $_SESSION['glpi_plugin_fusioninventory']['agents'][$agents_id] = 1;
               }

               $jobstates_id= $jobstate->add($c_input);

               //Add log of taskjob
               $c_input['plugin_fusioninventory_taskjobstates_id'] = $jobstates_id;
               $c_input['state']= PluginFusioninventoryTaskjoblog::TASK_PREPARED;
               $taskvalid++;
               $joblog->add($c_input);
               unset($c_input['state']);
               unset($c_input['plugin_fusioninventory_agents_id']);
            }
         }
      }

      if ($taskvalid > 0) {
         $job->fields['status']= 1;
         $job->update($job->fields);
      } else {
         $job->reinitializeTaskjobs($job->fields['plugin_fusioninventory_tasks_id']);
      }

   }

   // When agent contact server, this function send datas to agent
   /*
    * $itemtype = type of device in definition
    * $array = array with different ID
    *
    */
   function run($itemtype) {
      return $this->message;
   }
}

?>