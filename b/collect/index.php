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

$response = array();
//Agent communication using REST protocol

switch (filter_input(INPUT_GET, "action")) {

   case 'getJobs':
      $machineid = filter_input(INPUT_GET, "machineid");
      if (!empty($machineid)) {
         $pfAgent        = new PluginFusioninventoryAgent();
         $pfAgentModule  = new PluginFusioninventoryAgentmodule();
         $pfTask         = new PluginFusioninventoryTask();
         $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
         $pfTaskjoblog   = new PluginFusioninventoryTaskjoblog();

         $agent = $pfAgent->infoByKey(Toolbox::addslashes_deep($machineid));
         if (isset($agent['id'])) {
            $taskjobstates = $pfTask->getTaskjobstatesForAgent(
               $agent['id'],
               array('collect')
            );
            if (!$pfAgentModule->isAgentCanDo("Collect", $agent['id'])) {
               foreach ($taskjobstates as $taskjobstate) {
                  $taskjobstate->cancel(
                     __("Collect module has been disabled for this agent", 'fusioninventory')
                  );
               }
               $response = "{}";
            } else {
               $order = new stdClass;
               $order->jobs = array();

               $class = new PluginFusioninventoryCollect();
               foreach ($taskjobstates as $taskjobstate) {
                  $out = $class->run($taskjobstate, $agent);
                  if (count($out) > 0) {
                     $order->jobs = array_merge($order->jobs, $out);
                  }
                  $pfTaskjobstate->changeStatus(
                          $taskjobstate->fields['id'],
                          PluginFusioninventoryTaskjobstate::SERVER_HAS_SENT_DATA
                  );

                  $a_input = array();
                  $a_input['plugin_fusioninventory_taskjobstates_id'] = $taskjobstate->fields['id'];
                  $a_input['items_id'] = $agent['id'];
                  $a_input['itemtype'] = 'PluginFusioninventoryAgent';
                  $a_input['date'] = date("Y-m-d H:i:s");
                  $a_input['comment'] = '';
                  $a_input['state'] = PluginFusioninventoryTaskjoblog::TASK_STARTED;
                  $pfTaskjoblog->add($a_input);
               }
               // return an empty dictionnary if there are no jobs.
               if (count($order->jobs) == 0) {
                  $response = "{}";
               } else {
                  $response = json_encode($order);
               }
            }
         }
      }
      break;

   case 'setAnswer':
      // example
      // ?action=setAnswer&InformationSource=0x00000000&BIOSVersion=VirtualBox&SystemManufacturer=innotek%20GmbH&uuid=fepjhoug56743h&SystemProductName=VirtualBox&BIOSReleaseDate=12%2F01%2F2006
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfCollect = new PluginFusioninventoryCollect();
      $pfAgent = new PluginFusioninventoryAgent();

      $jobstate = current($pfTaskjobstate->find("`uniqid`='".filter_input(INPUT_GET, "uuid")."'
         AND `state`!='".PluginFusioninventoryTaskjobstate::FINISHED."'", '', 1));

      if (isset($jobstate['plugin_fusioninventory_agents_id'])) {
         $pfAgent->getFromDB($jobstate['plugin_fusioninventory_agents_id']);
         $computers_id = $pfAgent->fields['computers_id'];

         $a_values = $_GET;
         unset($a_values['action']);
         unset($a_values['uuid']);

         $pfCollect->getFromDB($jobstate['items_id']);

         switch ($pfCollect->fields['type']) {

            case 'registry':
               // update registry content
               $pfCRC = new PluginFusioninventoryCollect_Registry_Content();
               $pfCRC->updateComputer($computers_id,
                                      $a_values,
                                      filter_input(INPUT_GET, "_sid"));
               $pfTaskjobstate->changeStatus(
                       $jobstate['id'],
                       PluginFusioninventoryTaskjobstate::AGENT_HAS_SENT_DATA);
               if (isset($a_values['_cpt'])
                       && $a_values['_cpt'] == 0) { // it not find the path
                  $pfTaskjobstate->changeStatusFinish(
                       $jobstate['id'],
                       $jobstate['items_id'],
                       $jobstate['itemtype'],
                       1,
                       'Path not found');
               }
               $response = "{}";
               break;

            case 'wmi':
               // update wmi content
               $pfCWC = new PluginFusioninventoryCollect_Wmi_Content();
               $pfCWC->updateComputer($computers_id,
                                      $a_values,
                                      filter_input(INPUT_GET, "_sid"));
               $pfTaskjobstate->changeStatus(
                       $jobstate['id'],
                       PluginFusioninventoryTaskjobstate::AGENT_HAS_SENT_DATA);
               $response = "{}";
               break;

            case 'file':
               // update files content
               $params = array(
                  'machineid' => $pfAgent->fields['device_id'],
                  'uuid'      => filter_input(INPUT_GET, "uuid")
               );
               $pfCFC = new PluginFusioninventoryCollect_File_Content();
               $pfCFC->storeTempFilesFound($jobstate['id'], $a_values);
               $params['code'] = 'running';
               $params['msg'] = "file ".$a_values['path']." | size ".$a_values['size'];

               $pfTaskjobstate->changeStatus(
                       $jobstate['id'],
                       PluginFusioninventoryTaskjobstate::AGENT_HAS_SENT_DATA);

               PluginFusioninventoryCommunicationRest::updateLog($params);
               if ($a_values['_cpt'] == 1) { // it last value
                  $pfCFC->updateComputer($computers_id,
                                         filter_input(INPUT_GET, "_sid"),
                                         $jobstate['id']);
               }
               $response = "{}";
               break;

         }
      }
      break;

   case 'jobsDone':
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $jobstate = current($pfTaskjobstate->find("`uniqid`='".filter_input(INPUT_GET, "uuid")."'
         AND `state`!='".PluginFusioninventoryTaskjobstate::FINISHED."'", '', 1));
      if (isset($jobstate['plugin_fusioninventory_agents_id'])) {
         $pfTaskjobstate->changeStatusFinish(
              $jobstate['id'],
              $jobstate['items_id'],
              $jobstate['itemtype']);
      }
      $response = "{}";
      break;
}

if ($response !== FALSE) {
   echo $response;
} else {
   echo json_encode((object)array());
}

?>