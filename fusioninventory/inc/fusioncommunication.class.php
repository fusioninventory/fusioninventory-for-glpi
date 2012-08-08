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
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Class to communicate with agents with new Fusion protocol
 **/
class PluginFusioninventoryFusionCommunication {

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

      $taskjobstates = new PluginFusioninventoryTaskjobstate();

      //Get the agent ID by his deviceid
      //Get task job status : identifier is the uuid given by the agent
      if (PluginFusioninventoryAgent::getByDeviceID($p['machineid']) 
         && $taskjobstates->getFromDB($p['uuid'])) {
         
         //Get taskjoblog associated
         $taskjob = new PluginFusioninventoryTaskjob();
         $taskjob->getFromDB($taskjobstates->fields['plugin_fusioninventory_taskjobs_id']);

         $state = 1;
         if ($p['code'] == 'ok') {
            $state = 0;
         }

         $taskjobstates->changeStatusFinish($taskjobstates->fields['id'], 
                                            $taskjobstates->fields['items_id'], 
                                            $taskjobstates->fields['itemtype'], $state, $p['msg']);
      }
      self::sendOk();
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
      if (!ini_get('allow_url_fopen')) {
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

   static function managecommunication() {
      $response = PluginFusioninventoryFusionCommunication::communicate($_GET);
      if ($response) {
         echo json_encode($response);
      } else {
         PluginFusioninventoryFusionCommunication::sendError();
      }
   }
}

?>
