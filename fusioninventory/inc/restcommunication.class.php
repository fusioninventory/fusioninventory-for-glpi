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

   /**
    * Manage communication between agent and server
    * 
    * @params an array of GET parameters given by the agent
    * 
    * @return an array of orders to send to the agent
    */
   static function communicate($params = array()) {
      $response = array();
      if (isset ($params['action']) && isset($params['machineid'])) {
         if (PluginFusioninventoryAgent::getByDeviceID($params['machineid'])) {
            switch ($params['action']) {
               
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
 
   
   
   /**
    * Get configuration for an agent
    * 
    * @params an array of GET parameters given by the agent
    * 
    * @return an array of orders to send to the agent
    */
   static function getConfigByAgent($params = array()) {
      $schedule = array();
      
      if (isset($params['task'])) {
         foreach ($params['task'] as $task => $version) {
            foreach (PluginFusioninventoryStaticmisc::getmethods() as $method) {
               $class= PluginFusioninventoryStaticmisc::getStaticmiscClass($method['module']);
               if (isset($method['use_rest']) 
                     && $method['use_rest'] 
                        && method_exists($class, self::getMethodForParameters($task))) {
                  $schedule[] = call_user_func(array($class, self::getMethodForParameters($task)));
               }
            }
         }
      }
      return array('configValidityPeriod' => 600, 'schedule' => $schedule);
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

   
   
   static function getMethodForParameters($task) {
      return "task_".strtolower($task)."_getParameters";
   }
   
   
   
   /**
    * Update agent status for a task
    * 
    * @param params parameters from the GET HTTP request
    * 
    * @return nothing
    */
   static function updateLog($params = array()) {
      $p = array();
      $p['machineid'] = ''; //DeviceId
      $p['uuid']      = ''; //Task uuid
      $p['msg']       = 'ok'; //status of the task
      $p['code']      = ''; //current step of processing
      foreach ($params as $key => $value) {
         $p[$key] = $value;
      }

      $taskjobstatus = new PluginFusioninventoryTaskjobstatus();

      //Get the agent ID by his deviceid
      //Get task job status : identifier is the uuid given by the agent
      if (PluginFusioninventoryAgent::getByDeviceID($p['machineid']) 
         && $taskjobstatus->getFromDB($p['uuid'])) {
         
         //Get taskjoblog associated
         $taskjob = new PluginFusioninventoryTaskjob();
         $taskjob->getFromDB($taskjobstatus->fields['plugin_fusioninventory_taskjobs_id']);

         $state = 1;
         if ($p['code'] == 'ok') {
            $state = 0;
         }

         $taskjobstatus->changeStatusFinish($taskjobstatus->fields['id'], 
                                            $taskjobstatus->fields['items_id'], 
                                            $taskjobstatus->fields['itemtype'], $state, $p['msg']);
      }
      self::sendOk();
   }
 
   
   
   /**
    * Get default URL for a REST servie
    * 
    * @param url REFERER url
    * @param plugin the plugin hosts the service
    * @param task the task to access
    * 
    * @return the url of the REST service
    */
   static function getDefaultRestURL($url, $plugin, $task) {
      global $CFG_GLPI;
      
      $task = strtolower($task);
      if (preg_match("/(.*)\/(plugins|front)/",$url,$values)) {
         return $values[1].'/plugins/'.$plugin.'/b/'.$task.'/';
      } else {
         if (isset($CFG_GLPI["root_doc"])) {
            return $CFG_GLPI["root_doc"].'/plugins/'.$plugin.'/b/'.$task.'/';
         }         
         return "";
      }
   }

   
   
   /**
    * Test a given url
    * 
    * @param url the url to test
    * 
    * @return true if url is valid, false otherwise
    */
   static function testRestURL($url) {
      
      //If fopen is not allowed, we cannot check and then return true...
      if (!PluginFusioninventoryCommunication::isFopenAllowed()) {
         return true;
      }
      
      $handle = fopen($url,'rb');
      if (!$handle) {
         return false;
      } else {
         fclose($handle);
         return true;
      }
   }
   
}

?>
