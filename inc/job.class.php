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
      global $DB;

      $response      = array();
      $taskjoblog    = new PluginFusioninventoryTaskjoblog();
      $taskjobstatus = new PluginFusioninventoryTaskjobstatus();

      //Get the agent ID by his deviceid
      if ($agents_id = PluginFusinvdeployJob::getAgentByDeviceID($device_id)) {

         //Get tasks associated with the agent
         $task_list = $taskjobstatus->getTaskjobsAgent($agents_id);
         foreach ($task_list as $itemtype => $status_list) {

            //Foreach task for this agent build the response array
            foreach ($status_list as $status) {
               //verify whether task is active
               $sql = "SELECT is_active
                  FROM glpi_plugin_fusioninventory_tasks tasks
               LEFT JOIN glpi_plugin_fusioninventory_taskjobs jobs
                  ON jobs.plugin_fusioninventory_tasks_id = tasks.id
               WHERE jobs.id = '".$status['plugin_fusioninventory_taskjobs_id']."'
               AND is_active = '1'";
               $res = $DB->query($sql);
               if ($DB->numrows($res) == 0) break;

               switch ($itemtype) {
                  default:
                     $ordertype = -1;
                     break;

                  //Install a package
                  case 'PluginFusinvdeployDeployinstall':
                     $ordertype = PluginFusinvdeployOrder::INSTALLATION_ORDER;
                     break;

                  //Uninstall a package
                  case 'PluginFusinvdeployDeployuninstall':
                     $ordertype = PluginFusinvdeployOrder::UNINSTALLATION_ORDER;
                     break;
               }
               if ($ordertype != -1) {
                  $orderDetails = PluginFusinvdeployOrder::getOrderDetails($status, $ordertype);
                  if (count($orderDetails) == 0) return false;
                  $response[] = $orderDetails;
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
      $p['machineid']      = ''; //DeviceId
      $p['part']           = ''; //fragment downloaded
      $p['uuid']           = ''; //Task uuid
      $p['status']         = ''; //status of the task
      $p['currentStep']    = ''; //current step of processing
      $p['msg']            = ''; //Message to be logged
      $p['log']            = '';
      foreach ($params as $key => $value) {
         $p[$key] = $value;
      }

      //Get the agent ID by his deviceid
      if ($agents_id = PluginFusinvdeployJob::getAgentByDeviceID($p['machineid'])) {

         $jobstatus = PluginFusioninventoryTaskjoblog::getByUniqID($p['uuid']);

         /*if ($update_job) {
            $taskjob = new PluginFusioninventoryTaskjoblog();
            $taskjob->update($jobstatus);
         }*/
         $taskjoblog = new PluginFusioninventoryTaskjoblog();
         $tmp['plugin_fusioninventory_taskjobstatus_id'] = $jobstatus['id'];
         $tmp['itemtype']                                = $jobstatus['itemtype'];
         $tmp['items_id']                                = $jobstatus['items_id'];
         $tmp['comment']                                 = $p['msg'];
         $tmp['date']                                    = date("Y-m-d H:i:s");

         $pass_addlog = false;

         // add log message
         if (is_array($p['log'])/* && $tmp['comment'] == ""*/) {
            $tmp['comment'] = "log:";
            foreach($p['log'] as $log) {
               $tmp['comment'] .= $log."\n";
            }
         } elseif ($p['log'] != "") {
            $tmp['comment'] = "log:";
            $tmp['comment'] .= $p['log'];
         }
         if ($p['status'] == 'ko') {
            $tmp['state'] = PluginFusioninventoryTaskjoblog::TASK_ERROR;
         } elseif ($p['currentStep'] != '') {
            $tmp['state'] = PluginFusioninventoryTaskjoblog::TASK_RUNNING;
            if ($tmp['comment'] == '') $tmp['comment'] = $p['currentStep'];
            if ($p['status'] != '') {
               $tmp['comment'] .= " : ".$p['status'];
            }
         } elseif ($p['status'] == 'ok') {
            $pass_addlog = true;
         } else {
            $tmp['state'] = PluginFusioninventoryTaskjoblog::TASK_STARTED;
         }

         if (!$pass_addlog) {
            $taskjoblog->addTaskjoblog(
                  $tmp['plugin_fusioninventory_taskjobstatus_id'],
                  $tmp['items_id'],
                  $tmp['itemtype'],
                  $tmp['state'],
                  $tmp['comment']
            );
         }

         //change task to finish and replanned if retry available
         if ($p['status'] != "" && $p['currentStep'] == "" || $p['status'] == "ko") {
            $error = "0";
            if ($p['status'] == 'ko') $error = "1";
            //set status to finished and reinit job
            $taskjobstatus = new PluginFusioninventoryTaskjobstatus;
            $taskjobstatus->changeStatusFinish(
               $jobstatus['id'],
               $jobstatus['items_id'],
               $jobstatus['itemtype'],
               $error
            );
         }

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
