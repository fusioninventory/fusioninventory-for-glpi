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
   Original Author of file: Vincent MAZZONI
   Co-authors of file: David DURIEUX
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


class PluginFusioninventoryRestCommunication {

   static function communicate($params = array()) {
      $response = array();
      if (isset ($params['a']) && isset($params['d'])) {
         if (PluginFusioninventoryAgent::getByDeviceID($params['d'])) {
            switch ($params['a']) {
               case 'getConfig':
                  $response = self::getConfigByAgent($params);
                  break;
               case 'wait':
                  break;
            }

         } else {
            $response = false;
         }
         
      } else {
         $response = false;
      }
      
      return $response;
   }
   
   static function getConfigByAgent($params = array()) {
      $schedule = array();

      if (isset($params['task'])) {
         foreach ($params['task'] as $task => $version) {
            foreach (PluginFusioninventoryStaticmisc::getmethods() as $method) {
               $class= PluginFusioninventoryStaticmisc::getStaticmiscClass($method['module']);
               if (isset($method['use_rest']) 
                     && $method['use_rest'] 
                        && method_exists($class, "task_".$task."_getParameters")) {
                  $schedule[$task] = call_user_func(array($class, "task_".$task."_getParameters"));
               }
               
            }
         }
         
      }
      $schedule['configValidityPeriod'] = 600;
      return array ('schedule' => $schedule);
   }
   
   /**
    * Send to the agent an OK code
    */
   static function sendOk() {
      header("HTTP/1.1 200", true, 200);
   }

   /**
    * Send to the agent an error code
    * when the request sent by the agent is invalid
    */
   static function sendError() {
      header("HTTP/1.1 400", true, 400);
   }


   /**
    * Update agent status for a task
    * @param params parameters from the GET HTTP request
    * @return nothing
    */
   static function updateLog($params = array(),$update_job = true) {
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
      if (PluginFusioninventoryAgent::getByDeviceID($params['d'])) {
         
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
   
}

?>