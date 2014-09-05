<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2013 FusionInventory team
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


class PluginFusioninventoryCommunicationRest {

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
               case 'getJobs':
                  $response = self::getJobsByAgent($params);
               case 'wait':
                  break;

            }
         } else {
            $response = FALSE;
         }
      } else {
         $response = FALSE;
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

      $a_agent = PluginFusioninventoryAgent::getByDeviceID($params['machineid']);

      if (isset($params['task'])) {
         foreach (array_keys($params['task']) as $task) {
            foreach (PluginFusioninventoryStaticmisc::getmethods() as $method) {
               $class= PluginFusioninventoryStaticmisc::getStaticmiscClass($method['module']);
               if (
                     (isset($method['task']) && strtolower($method['task']) == strtolower($task))
                  && (isset($method['use_rest']) && $method['use_rest'])
                  && method_exists($class, self::getMethodForParameters($task))
               ) {
                  /*
                   * Since migration, there is only one plugin in one directory
                   * It's maybe time to redo this function -- kiniou
                   */
                  $schedule[] = call_user_func(array($class, self::getMethodForParameters($task)), $a_agent['entities_id']);
                  break; //Stop the loop since we found the module corresponding to the asked task
               }
            }
         }
      }
      return array('configValidityPeriod' => 600, 'schedule' => $schedule);
   }

   /**
    * Get jobs for an agent
    * TODO: This methods must be used inplace of other methods in order to mutualize code and
    * to fully support FusionInventory REST API for every task's types
    *       -- kiniou
    */
   static function getJobsByAgent($params = array()) {
//      $jobs = array();
//      $methods = PluginFusioninventoryStaticmisc::getmethods();
//      if( isset($params['task']) ) {
//         foreach(array_keys($params['task']) as $task) {
//
//         }
//      }
      return FALSE;
   }


   /**
    * Send to the agent an OK code
    */
   static function sendOk() {
      header("HTTP/1.1 200", TRUE, 200);
   }



   /**
    * Send to the agent an error code
    * when the request sent by the agent is invalid
    */
   static function sendError() {
      header("HTTP/1.1 400", TRUE, 400);
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
      global $DB;

      $p = array();
      $p['machineid'] = ''; //DeviceId
      $p['uuid']      = ''; //Task uuid
      $p['msg']       = 'ok'; //status of the task
      $p['code']      = ''; //current step of processing
      foreach ($params as $key => $value) {
         $p[$key] = $value;
      }


      //Get the agent ID by its deviceid
      $agent = PluginFusioninventoryAgent::getByDeviceID($p['machineid']);

      //No need to continue since the requested agent doesn't exists in database
      if ($agent === FALSE) {
         self::sendError();
         return;
      }
      //Get task job status : identifier is the uuid given by the agent
      $taskjobstates = $DB->request(
         getTableForItemType('PluginFusioninventoryTaskjobstate'),
         "`uniqid`='".$p['uuid']."'"
      );

      $taskjobstate = new PluginFusioninventoryTaskjobstate();
      foreach( $taskjobstates as $jobstate ) {
         $taskjobstate->getFromDB($jobstate['id']);

         //Get taskjoblog associated
         $taskjoblog = new PluginFusioninventoryTaskjobLog();
         $taskjoblog->getFromDBByQuery(
            "WHERE `plugin_fusioninventory_taskjobstates_id`=". $jobstate['id']
         );
         switch($p['code']) {
            case 'running':
               $taskjoblog->addTaskjoblog(
                  $taskjobstate->fields['id'],
                  $taskjobstate->fields['items_id'],
                  $taskjobstate->fields['itemtype'],
                  PluginFusioninventoryTaskjoblog::TASK_RUNNING,
                  $p['msg']
               );
               break;
            case 'ok':
               $taskjobstate->changeStatusFinish(
                  $taskjobstate->fields['id'],
                  $taskjobstate->fields['items_id'],
                  $taskjobstate->fields['itemtype'],
                  0, // everything goes well
                  $p['msg']
               );
               break;
            case 'ko':
               $taskjobstate->changeStatusFinish(
                  $taskjobstate->fields['id'],
                  $taskjobstate->fields['items_id'],
                  $taskjobstate->fields['itemtype'],
                  1, // there was an error
                  $p['msg']
               );
               break;
         }
      }
      self::sendOk();
   }


   /**
    * Test a given url
    *
    * @param url the url to test
    *
    * @return TRUE if url is valid, FALSE otherwise
    */
   static function testRestURL($url) {

      //If fopen is not allowed, we cannot check and then return TRUE...
      if (!ini_get('allow_url_fopen')) {
         return TRUE;
      }

      $handle = fopen($url, 'rb');
      if (!$handle) {
         return FALSE;
      } else {
         fclose($handle);
         return TRUE;
      }
   }

   
   
   /**
    * Manage REST parameters
    **/
   static function handleFusionCommunication() {
      $response = PluginFusioninventoryCommunicationRest::communicate($_GET);
      if ($response) {
         echo json_encode($response);
      } else {
         PluginFusioninventoryCommunicationRest::sendError();
      }
   }
}

?>
