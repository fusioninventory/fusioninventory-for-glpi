<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2015 by the FusionInventory Development Team.

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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2015 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

ob_start();
include ("../../../../inc/includes.php");
ob_end_clean();

$response = array();

//Agent communication using REST protocol
if (isset($_GET['action'])) {
   $pfAgent        = new PluginFusioninventoryAgent();
   $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
   $pfTaskjoblog   = new PluginFusioninventoryTaskjoblog();
   $plCollect      = new PluginFusioninventoryCollect();

   switch ($_GET['action']) {

      case 'getJobs':
         if(isset($_GET['machineid'])) {

            $a_agent = $pfAgent->InfosByKey(Toolbox::addslashes_deep($_GET['machineid']));
            if (isset($a_agent['id'])) {
               $moduleRun = $pfTaskjobstate->getTaskjobsAgent($a_agent['id']);
               foreach ($moduleRun as $className => $array) {
                  if (class_exists($className)) {
                     if ($className == "PluginFusioninventoryCollect") {
                        $response['jobs'] = array();
                        foreach ($array as $data) {
                           $out = $plCollect->run($data, $a_agent);
                           if (count($out) > 0) {
                              $response['jobs'] = array_merge($response['jobs'], $out);
                              $response['postmethod'] = 'POST';
                              $response['token'] = Session::getNewCSRFToken();
                           }

                           $a_input = array();
                           $a_input['plugin_fusioninventory_taskjobstates_id'] = $data['id'];
                           $a_input['items_id'] = $a_agent['id'];
                           $a_input['itemtype'] = 'PluginFusioninventoryAgent';
                           $a_input['date'] = date("Y-m-d H:i:s");
                           $a_input['comment'] = '';
                           $a_input['state'] = PluginFusioninventoryTaskjoblog::TASK_STARTED;
                           $pfTaskjoblog->add($a_input);


                        }
                     }
                  }
               }
            }
         }
         break;

      case 'setAnswer':
         // example
         // ?action=setAnswer&InformationSource=0x00000000&BIOSVersion=VirtualBox&SystemManufacturer=innotek%20GmbH&uuid=fepjhoug56743h&SystemProductName=VirtualBox&BIOSReleaseDate=12%2F01%2F2006
         $jobstate = current($pfTaskjobstate->find("`uniqid`='".$_GET['uuid']."'
            AND `state`!='".PluginFusioninventoryTaskjobstate::FINISHED."'", '', 1));

         if (isset($jobstate['plugin_fusioninventory_agents_id'])) {
            $pfAgent->getFromDB($jobstate['plugin_fusioninventory_agents_id']);
            $computers_id = $pfAgent->fields['computers_id'];

            $a_values = $_GET;
            if (isset($_GET['method']) && $_GET['method'] == 'POST') {
                $a_values =  $_POST;
                $response['token'] = Session::getNewCSRFToken();
                unset($a_values['_glpi_csrf_token']);
            }
            unset($a_values['action']);
            unset($a_values['uuid']);

            $plCollect->getFromDB($jobstate['items_id']);

            switch ($plCollect->fields['type']) {
               case 'registry':
                  $pfCollect_subO = new PluginFusioninventoryCollect_Registry_Content();
                  break;

               case 'wmi':
                  $pfCollect_subO = new PluginFusioninventoryCollect_Wmi_Content();
                  break;

               case 'file':
                  $pfCollect_subO = new PluginFusioninventoryCollect_File_Content();
                  $pfCollect_subO->storeTempFilesFound($jobstate['id'], $a_values);
                  break;
            }

            if (!isset($pfCollect_subO)) {
               die("collect type not found");
            }

            // update datas in table
            $pfCollect_subO->updateComputer($computers_id,
                                            $a_values,
                                            $jobstate['items_id']);

            // change status of state table row
            $pfTaskjobstate->changeStatus($jobstate['id'],
                          PluginFusioninventoryTaskjobstate::AGENT_HAS_SENT_DATA);

            $keys = $a_values;
            unset($keys['_cpt']);
            if (count($keys)) {
               $pfTaskjoblog->addTaskjoblog($jobstate['id'],
                                            $jobstate['items_id'],
                                            $jobstate['itemtype'],
                                            PluginFusioninventoryTaskjoblog::TASK_INFO,
                                            json_encode($keys, JSON_UNESCAPED_SLASHES));
            } else {
               $pfTaskjoblog->addTaskjoblog($jobstate['id'],
                                            $jobstate['items_id'],
                                            $jobstate['itemtype'],
                                            PluginFusioninventoryTaskjoblog::TASK_ERROR,
                                            __('Path not found', 'fusioninventory'));
            }
         }
         break;



       case 'jobsDone':
         $jobstate = current($pfTaskjobstate->find("`uniqid`='".$_GET['uuid']."'
            AND `state`!='".PluginFusioninventoryTaskjobstate::FINISHED."'", '', 1));
         $pfTaskjobstate->changeStatusFinish($jobstate['id'],
                                             $jobstate['items_id'],
                                             $jobstate['itemtype']);

         break;
   }


   // send response
   if (count($response) > 0) {
      echo json_encode($response);
   } else {
      echo json_encode((object)array());
   }

}

?>