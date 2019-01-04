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
 * This file is used to manage the REST communication for collect module
 * with the agent
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

ob_start();
include ("../../../../inc/includes.php");
ob_end_clean();

$response = new \stdClass();

//Agent communication using REST protocol

$pfAgent        = new PluginFusioninventoryAgent();
$pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
$pfTaskjoblog   = new PluginFusioninventoryTaskjoblog();
$pfCollect      = new PluginFusioninventoryCollect();

switch (filter_input(INPUT_GET, "action")) {

   case 'getJobs':
      $machineid = filter_input(INPUT_GET, "machineid");
      if (!empty($machineid)) {
         $pfAgentModule  = new PluginFusioninventoryAgentmodule();
         $pfTask         = new PluginFusioninventoryTask();

         $agent = $pfAgent->infoByKey(Toolbox::addslashes_deep($machineid));
         if (isset($agent['id'])) {
            $taskjobstates = $pfTask->getTaskjobstatesForAgent(
               $agent['id'],
               ['collect']
            );
            $order = new \stdClass();
            $order->jobs = [];

            foreach ($taskjobstates as $taskjobstate) {
               if (!$pfAgentModule->isAgentCanDo("Collect", $agent['id'])) {
                  $taskjobstate->cancel(
                     __("Collect module has been disabled for this agent", 'fusioninventory')
                  );
               } else {
                  $out = $pfCollect->run($taskjobstate, $agent);
                  if (count($out) > 0) {
                     $order->jobs = array_merge($order->jobs, $out);
                  }

                  // change status of state table row
                  $pfTaskjobstate->changeStatus(
                        $taskjobstate->fields['id'],
                        PluginFusioninventoryTaskjobstate::SERVER_HAS_SENT_DATA
                  );

                  $a_input = [
                        'plugin_fusioninventory_taskjobstates_id'    => $taskjobstate->fields['id'],
                        'items_id'                                   => $agent['id'],
                        'itemtype'                                   => 'PluginFusioninventoryAgent',
                        'date'                                       => date("Y-m-d H:i:s"),
                        'comment'                                    => '',
                        'state'                                      => PluginFusioninventoryTaskjoblog::TASK_STARTED
                  ];
                  $pfTaskjoblog->add($a_input);

                  if (count($order->jobs) > 0) {
                     $response = $order;
                     // Inform agent we request POST method, agent will then submit result
                     // in POST request if it supports the method or it will continue with GET
                     $response->postmethod = 'POST';
                     $response->token = Session::getNewCSRFToken();
                  }
               }
            }
         }
      }
      break;

   case 'setAnswer':
      // example
      // ?action=setAnswer&InformationSource=0x00000000&BIOSVersion=VirtualBox&SystemManufacturer=innotek%20GmbH&uuid=fepjhoug56743h&SystemProductName=VirtualBox&BIOSReleaseDate=12%2F01%2F2006
      $jobstate = current($pfTaskjobstate->find(
            ['uniqid' => filter_input(INPUT_GET, 'uuid'),
             'state'  => ['!=', PluginFusioninventoryTaskjobstate::FINISHED]],
            [], 1));

      if (isset($jobstate['plugin_fusioninventory_agents_id'])) {

         $add_value = true;

         $pfAgent->getFromDB($jobstate['plugin_fusioninventory_agents_id']);
         $computers_id = $pfAgent->fields['computers_id'];

         $a_values = $_GET;
         // Check agent uses POST method to use the right submitted values. Also renew token to support CSRF for next post.
         if (isset($_GET['method']) && $_GET['method'] == 'POST') {
             $a_values =  $_POST;
             $response->token = Session::getNewCSRFToken();
             unset($a_values['_glpi_csrf_token']);
         }
         $sid = isset($a_values['_sid'])?$a_values['_sid']:0;
         $cpt = isset($a_values['_cpt'])?$a_values['_cpt']:0;
         unset($a_values['action']);
         unset($a_values['uuid']);
         unset($a_values['_cpt']);
         unset($a_values['_sid']);

         $pfCollect->getFromDB($jobstate['items_id']);

         switch ($pfCollect->fields['type']) {
            case 'registry':
               // update registry content
               $pfCollect_subO = new PluginFusioninventoryCollect_Registry_Content();
               break;

            case 'wmi':
               // update wmi content
               $pfCollect_subO = new PluginFusioninventoryCollect_Wmi_Content();
               break;

            case 'file':
               if (!empty($a_values['path']) && !empty($a_values['size'])) {
                  // update files content
                  $params = [
                     'machineid' => $pfAgent->fields['device_id'],
                     'uuid'      => filter_input(INPUT_GET, "uuid"),
                     'code'      => 'running',
                     'msg'       => "file ".$a_values['path']." | size ".$a_values['size']
                  ];
                  PluginFusioninventoryCommunicationRest::updateLog($params);
                  $pfCollect_subO = new PluginFusioninventoryCollect_File_Content();
                  $a_values = [$sid => $a_values];
               } else {
                  $add_value = false;
               }
               break;
         }

         if (!isset($pfCollect_subO)) {
            die("collect type not found");
         }

         if ($add_value) {
            // add collected informations to computer
            $pfCollect_subO->updateComputer(
               $computers_id,
               $a_values,
               $sid
            );
         }

         // change status of state table row
         $pfTaskjobstate->changeStatus($jobstate['id'],
                    PluginFusioninventoryTaskjobstate::AGENT_HAS_SENT_DATA);

         // add logs to job
         if (count($a_values)) {
            $flag    = PluginFusioninventoryTaskjoblog::TASK_INFO;
            $message = json_encode($a_values, JSON_UNESCAPED_SLASHES);
         } else {
            $flag    = PluginFusioninventoryTaskjoblog::TASK_ERROR;
            $message = __('Path not found', 'fusioninventory');
         }
            $pfTaskjoblog->addTaskjoblog($jobstate['id'],
                                         $jobstate['items_id'],
                                         $jobstate['itemtype'],
                                         $flag,
                                         $message);
      }
      break;



   case 'jobsDone':
      $jobstate = current($pfTaskjobstate->find(
            ['uniqid' => $_GET['uuid'],
             'state'  => ['!=', PluginFusioninventoryTaskjobstate::FINISHED]],
            [], 1));
      $pfTaskjobstate->changeStatusFinish($jobstate['id'],
                                     $jobstate['items_id'],
                                     $jobstate['itemtype']);

         break;
}

echo json_encode($response);
