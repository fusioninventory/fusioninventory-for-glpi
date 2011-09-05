<?php


/*
 * @version $Id: deploycommon.class.php 116 2011-03-16 08:33:25Z wnouh $
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if(!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


require_once(GLPI_ROOT."/plugins/fusioninventory/inc/communication.class.php");

class PluginFusinvdeployDeployCommon extends PluginFusioninventoryCommunication {

   // Get all devices and put in taskjobstatus each task for each device for each agent
   function prepareRun($taskjobs_id) {
      global $DB;

      $task       = new PluginFusioninventoryTask();
      $job        = new PluginFusioninventoryTaskjob();
      $joblog     = new PluginFusioninventoryTaskjoblog();
      $jobstatus  = new PluginFusioninventoryTaskjobstatus();
      $agent      = new PluginFusioninventoryAgent();

      $uniqid= uniqid();

      $job->getFromDB($taskjobs_id);
      $task->getFromDB($job->fields['plugin_fusioninventory_tasks_id']);

      $communication= $task->fields['communication'];

      $actions     = importArrayFromDB($job->fields['action']);
      $definitions   = importArrayFromDB($job->fields['definition']);

      $computers = array();
      foreach ($actions as $action) {
         $itemtype = key($action);
         $items_id = current($action);

         switch($itemtype) {
            case 'Computer':
               $computers[] = $items_id;
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
                     foreach($fields_array as $key =>$field) {
                        if (trim($field) == '') unset($fields_array[$key]);
                     }
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
      $c_input['uniqid']                             = $uniqid;

      foreach($computers as $computer_id) {
         $c_input['state'] = 0;
         $c_input['itemtype'] = 'Computer';
         $c_input['items_id'] = $computer_id;
         $c_input['date'] = date("Y-m-d H:i:s");

         //get agent if for this computer
         if(!$agents_id = $agent->getAgentWithComputerid($computer_id)) continue;
         $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

         $jobstatus_id= $jobstatus->add($c_input);

         //Add log of taskjob
         $c_input['plugin_fusioninventory_taskjobstatus_id'] = $jobstatus_id;
         $c_input['state']= PluginFusioninventoryTaskjoblog::TASK_PREPARED;

         $joblog->add($c_input);
         unset($c_input['state']);
      }

      $job->fields['status']= 1;
      $job->update($job->fields);

      /*$agent_actionslist = array();
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
            }
         }
         //Add log of taskjob
         $a_input['plugin_fusioninventory_taskjobstatus_id']= $jobstatus_id;
         $a_input['state'] = PluginFusioninventoryTaskjoblog::TASK_PREPARED;
         $a_input['date']  = date("Y-m-d H:i:s");
         $joblog->add($a_input);

         $jobstatus->changeStatusFinish($jobstatus_id, 0, 'PluginFusinvdeployPackage', 1,
                                        "Unable to find agent to run this job");
         $job->fields['status']= 1;
         $job->update($job->fields);
      } else {
         foreach($agent_actions as $targets) {
            foreach ($targets as $itemtype => $items_id) {

               if ($communication == "push") {
                  $_SESSION['glpi_plugin_fusioninventory']['agents'][$items_id] = 1;
               }

               foreach ($task_definitions as $task_definition) {
                  foreach ($task_definition as $task_itemtype => $task_items_id) {
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
      }*/
   }

   // When agent contact server, this function send datas to agent
   /*
    * $itemtype = type of device in definition
    * $array = array with different ID
    *
    */
   function run($itemtype) {
      return $this->sxml;
   }
}
?>
