<?php
/*
 * @version $Id: job.class.php 166 2011-03-21 22:57:17Z wnouh $
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file: 
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Class to parse agent's requests and build responses
 **/
class PluginFusinvdeployJob {
   
   static function get($device_id) {
      $response      = array();
      $taskjoblog    = new PluginFusioninventoryTaskjoblog();
      $taskjobstatus = new PluginFusioninventoryTaskjobstatus();
      
      //Get the agent ID by his deviceid
      if ($agents_id = PluginFusinvdeployJob::getAgentByDeviceID($device_id)) {
         
         //Get tasks associated with the agent
         $tasks_list = $taskjobstatus->getTaskjobsAgent($agents_id);
         foreach ($tasks_list as $itemtype => $tasks) {
            
            //Foreach task for this agent build the response array
            foreach ($tasks as $task) {
               switch ($itemtype) {
                  default:
                     $ordertype = -1;
                     break;
                  
                  //Install a package
                  case 'PluginFusinvdeployDeploymentinstall':
                     $ordertype = PluginFusinvdeployOrder::INSTALLATION_ORDER;
                     break;
                  
                  //Uninstall a package
                  case 'PluginFusinvdeployDeploymentuninstall':
                     $ordertype = PluginFusinvdeployOrder::UNINSTALLATION_ORDER;
                     break;
               }
               if ($ordertype != -1) {
                  $response[] = PluginFusinvdeployOrder::getOrderDetails($task, $ordertype);
               }
            }
         }
      }
      return $response;
   }

   /**
    * Update agent status for a task
    * @param params parameters from the GET HTTP request
    * @return nothing
    */
   static function update($params = array(),$update_job = true) {
      $p['d']         = ''; //DeviceId
      $p['part']      = ''; //fragment downloaded
      $p['uuid']      = ''; //Task uuid
      $p['s']         = 'ok'; //status of the task
      $p['c']         = ''; //current step of processing
      $p['msg']       = ''; //Message to be logged
      foreach ($params as $key => $value) {
         $p[$key] = $value;
      }

      //Get the agent ID by his deviceid
      if ($agents_id = PluginFusinvdeployJob::getAgentByDeviceID($p['d'])) {
         
         $job = PluginFusioninventoryTaskjoblog::getByUniqID($p['uuid']);
         
         if ($update_job) {
            $taskjob = new PluginFusioninventoryTaskjoblog();
            $taskjob->update($job);
         }
         $taskjoblog = new PluginFusioninventoryTaskjoblog();
         $tmp['plugin_fusioninventory_taskjobstatus_id'] = $job['id'];
         $tmp['itemtype']                                = $job['itemtype'];
         $tmp['items_id']                                = $job['items_id'];
         $tmp['comment']                                 = $p['msg'];
         $tmp['date']                                    = date("Y-m-d H:i:s");
         if ($p['s'] == 'ko') {
            $tmp['state'] = PluginFusioninventoryTaskjoblog::TASK_ERROR;
         } else {
            if ($p['c'] == '') {
               $tmp['state'] = PluginFusioninventoryTaskjoblog::TASK_OK;
            } else {
               $tmp['state'] = PluginFusioninventoryTaskjoblog::TASK_RUNNING;
            }
         }
         $taskjoblog->add($tmp);
         
      }
      self::sendOk();
   }
   
   /**
    * Get an agent ID by his deviceid
    * @param device_id the agent's device_id
    * @return the agent ID if agent found, or false
    */
   static function getAgentByDeviceID($device_id) {
      $result = getAllDatasFromTable('glpi_plugin_fusioninventory_agents',
                                     "`device_id`='$device_id'");
      if (!empty($result)) {
         $agent = array_pop($result);
         return $agent['id'];
      } else {
         return false;
      }
   }
   
   static function sendOk() {
      header("HTTP/1.1 200",true,200);
   }
}

?>