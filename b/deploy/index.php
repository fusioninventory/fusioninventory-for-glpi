<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @author    Kevin Roy
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */
ob_start();
include ("../../../../inc/includes.php");
ob_end_clean();

$response = FALSE;
//Agent communication using REST protocol
if (isset($_GET['action'])) {
   switch ($_GET['action']) {
      case 'getJobs':
         if(isset($_GET['machineid'])) {
            $pfAgent        = new PluginFusioninventoryAgent();
            $pfAgentModule  = new PluginFusioninventoryAgentModule();
            $pfTask         = new PluginFusioninventoryTask();
            $pfTaskjob      = new PluginFusioninventoryTaskjob();
            $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

            $agent = $pfAgent->InfosByKey(Toolbox::addslashes_deep($_GET['machineid']));

            if (isset($agent['id'])) {

               $taskjobstates = $pfTask->getTaskjobstatesForAgent(
                  $agent['id'],
                  array('deployinstall', 'deployuninstall')
               );
               if (!$pfAgentModule->isAgentCanDo("DEPLOY", $agent['id'])) {
                  foreach($taskjobstates as $taskjobstate) {
                     $taskjobstate->cancel(
                        __("Deploy module has been disabled for this agent", 'fusioninventory')
                     );
                  }
                  $response = "{}";
               } else {

                  //sort taskjobs by key id
                  /**
                   * TODO: sort taskjobs by 'index' field in the taskjob query since it can be
                   * manipulated by drag and drop (cf. Task::getTaskjobsForAgent() ).
                   */
                  ////start of json response
                  $order = new stdClass;
                  $order->jobs = array();
                  $order->associatedFiles = new stdClass;

                  ////aggregate json orders in a single json response
                  foreach ($taskjobstates as $taskjobstate) {

                     // TODO: The run() method should be renamed as getData() and moved to the Package
                     // class since we want package configuration (Order class may be useless ... needs
                     // some thinking)
                     $deploycommon = new PluginFusioninventoryDeployCommon();
                     // Get taskjob json order
                     $jobstate_order = $deploycommon->run($taskjobstate);

                     // Append order to the final json
                     $order->jobs[] = $jobstate_order['job'];
                     // Update associated files list
                     foreach( $jobstate_order['associatedFiles'] as $hash=>$associatedFiles) {
                        if(!array_key_exists($hash, $order->associatedFiles) ) {
                           $order->associatedFiles->$hash = $associatedFiles;
                        }
                     }
                     $taskjobstate->changeStatus(
                        $taskjobstate->fields['id'] ,
                        $taskjobstate::SERVER_HAS_SENT_DATA
                     );
                  }

                  // return an empty dictionnary if there are no jobs.
                  if ( count($order->jobs) == 0) {
                     $response = "{}";
                  } else {
                     $response = json_encode($order);
                  }
               }
            }
         }
         break;

      case 'getFilePart':
         $DB->close();
         PluginFusioninventoryDeployFilepart::httpSendFile($_GET);
         exit;
         break;

      case 'setStatus':

         $partjob_mapping = array(
            "checking"    => __('Checks', 'fusioninventory'),
            "downloading" => __('Files download', 'fusioninventory'),
            "prepare"     => __('Files preparation', 'fusioninventory'),
            "processing"  => __('Actions', 'fusioninventory'),
         );

         $error = FALSE;

         $params = array(
            'machineid' => $_GET['machineid'],
            'uuid'      => $_GET['uuid']
         );

         if ( array_key_exists("status", $_GET)
                 && $_GET['status'] == 'ko') {
            $params['code'] = 'ko';
            if (array_key_exists("currentStep", $_GET)) {
               $params['msg'] = $partjob_mapping[$_GET['currentStep']] . ":" . $_GET['msg'];
            } else {
               $params['msg'] = $_GET['msg'];
            }
            $error = TRUE;
         }


         if ( $error != TRUE) {
            if ( array_key_exists("msg", $_GET)
                    && $_GET['msg'] === 'job successfully completed') {
               //Job is ended and status should be ok
               $params['code'] = 'ok';
               $params['msg'] = $_GET['msg'];
            } else {
               $params['code'] = 'running';
               if (array_key_exists("currentStep", $_GET)) {
                  $params['msg'] = $partjob_mapping[$_GET['currentStep']] . ":" . $_GET['msg'];
               } else {
                  $params['msg'] = $_GET['msg'];
               }
            }
         }
         if (is_array($params['msg']) ) {

            if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
               $htmlspecialchars_flags = ENT_SUBSTITUTE | ENT_DISALLOWED;
            } else {
               $htmlspecialchars_flags = NULL;
            }

            $tmp_msg = implode("\n", $params['msg']);
            $flags = NULL;
            $tmp_msg =
               stripcslashes(
                  htmlspecialchars(
                     $tmp_msg,
                     $htmlspecialchars_flags,
                     'UTF-8',
                     FALSE
                  )
               );
            $params['msg'] = nl2br($tmp_msg);
         }

         //Generic method to update logs
         PluginFusioninventoryCommunicationRest::updateLog($params);
         break;

   }

   if ($response !== FALSE) {
      echo $response;
   } else {
      echo json_encode((object)array());
   }
}

?>
