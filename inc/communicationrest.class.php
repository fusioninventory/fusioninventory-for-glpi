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
 * This file is used to manage the communication in REST with the agents.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
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
 * Manage the communication in REST with the agents.
 */
class PluginFusioninventoryCommunicationRest {


   /**
    * Manage communication between agent and server
    *
    * @param array $params
    * @return array|false array return jobs ready for the agent
    */
   static function communicate($params = []) {
      $response = [];
      if (isset ($params['action']) && isset($params['machineid'])) {
         if (PluginFusioninventoryAgent::getByDeviceID($params['machineid'])) {
            switch ($params['action']) {

               case 'getConfig':
                  $response = self::getConfigByAgent($params);
                  break;

               case 'getJobs':
                  $response = self::getJobsByAgent($params);
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
    * Get configuration for an agent and for modules requested
    *
    * @param array $params
    * @return array
    */
   static function getConfigByAgent($params = []) {
      $schedule = [];

      if (isset($params['task'])) {
         $pfAgentModule = new PluginFusioninventoryAgentmodule();
         $a_agent       = PluginFusioninventoryAgent::getByDeviceID($params['machineid']);

         foreach (array_keys($params['task']) as $task) {
            foreach (PluginFusioninventoryStaticmisc::getmethods() as $method) {
               switch (strtolower($task)) {
                  case 'deploy':
                     $classname = 'PluginFusioninventoryDeployPackage';
                     break;
                  case 'esx':
                     $classname = 'PluginFusioninventoryCredentialIp';
                     break;
                  case 'collect':
                     $classname = 'PluginFusioninventoryCollect';
                     break;
                  default:
                     $classname = '';
               }

               $taskname = $method['method'];
               if (strstr($taskname, 'deploy')) {
                  $taskname = $method['task'];
               }
               $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($method['module']);
               if ((isset($method['task']) && strtolower($method['task']) == strtolower($task))
                  && (isset($method['use_rest']) && $method['use_rest'])
                  && method_exists($class, self::getMethodForParameters($task))
                  && $pfAgentModule->isAgentCanDo($taskname, $a_agent['id'])
                  && countElementsInTable('glpi_plugin_fusioninventory_taskjobstates',
                     [
                        'plugin_fusioninventory_agents_id' => $a_agent['id'],
                        'itemtype'                         => $classname,
                        'state'                            => 0,
                     ]) > 0) {
                  /*
                   * Since migration, there is only one plugin in one directory
                   * It's maybe time to redo this function -- kiniou
                   */
                  $schedule[]
                     = call_user_func([$class, self::getMethodForParameters($task)],
                                      $a_agent['entities_id']);
                  break; //Stop the loop since we found the module corresponding to the asked task
               }
            }
         }
      }
      return ['configValidityPeriod' => 600, 'schedule' => $schedule];
   }


   /**
    * Get jobs for an agent
    * TODO: This methods must be used inplace of other methods in order to mutualize code and
    * to fully support FusionInventory REST API for every task's types
    *       -- kiniou
    *
    * @param array $params
    * @return false
    */
   static function getJobsByAgent($params = []) {
      //      $jobs = [];
      //      $methods = PluginFusioninventoryStaticmisc::getmethods();
      //      if (isset($params['task'])) {
      //         foreach (array_keys($params['task']) as $task) {
      //
      //         }
      //      }
      return false;
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
    * Generate the function name related to the module to get parameters
    *
    * @param string $task
    * @return string
    */
   static function getMethodForParameters($task) {
      return "task_".strtolower($task)."_getParameters";
   }


   /**
    * Update agent status for a taskjob
    *
    * @global object $DB
    * @param array $params
    */
   static function updateLog($params = []) {
      global $DB;

      $p              = [];
      $p['machineid'] = ''; //DeviceId
      $p['uuid']      = ''; //Task uuid
      $p['msg']       = 'ok'; //status of the task
      $p['code']      = ''; //current step of processing
      $p['sendheaders'] = true;

      foreach ($params as $key => $value) {
         $p[$key] = $value;
      }

      //Get the agent ID by its deviceid
      $agent = PluginFusioninventoryAgent::getByDeviceID($p['machineid']);

      //No need to continue since the requested agent doesn't exists in database
      if ($agent === false) {
         if ($p['sendheaders']) {
            self::sendError();
         }
         return;
      }

      $taskjobstate = new PluginFusioninventoryTaskjobstate();

      //Get task job status : identifier is the uuid given by the agent
      $params = ['FROM' => getTableForItemType("PluginFusioninventoryTaskjobstate"),
                 'FIELDS' => 'id',
                 'WHERE' => ['uniqid' => $p['uuid']]
                ];
      foreach ($DB->request($params) as $jobstate) {
         $taskjobstate->getFromDB($jobstate['id']);

         //Get taskjoblog associated
         $taskjoblog = new PluginFusioninventoryTaskjoblog();
         switch ($p['code']) {

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
            case 'ko':
               $taskjobstate->changeStatusFinish(
                  $taskjobstate->fields['id'],
                  $taskjobstate->fields['items_id'],
                  $taskjobstate->fields['itemtype'],
                  ($p['code'] == 'ok'?0:1),
                  $p['msg']
               );
               break;
         }
      }
      if ($p['sendheaders']) {
         self::sendOk();
      }
   }


   /**
    * Test a given url
    *
    * @param string $url
    * @return boolean
    */
   static function testRestURL($url) {

      //If fopen is not allowed, we cannot check and then return true...
      if (!ini_get('allow_url_fopen')) {
         return true;
      }

      $handle = fopen($url, 'rb');
      if (!$handle) {
         return false;
      } else {
         fclose($handle);
         return true;
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
