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
 * This file is used to manage the REST communication for deploy module
 * with the agent
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Kevin Roy
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

$response = FALSE;
//Agent communication using REST protocol
switch (filter_input(INPUT_GET, "action")) {

   case 'getJobs':
      $machineid = filter_input(INPUT_GET, "machineid");
      if (isset($machineid)) {
         $pfAgent        = new PluginFusioninventoryAgent();
         $pfAgentModule  = new PluginFusioninventoryAgentmodule();
         $pfTask         = new PluginFusioninventoryTask();
         $pfTaskjob      = new PluginFusioninventoryTaskjob();
         $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

         $agent = $pfAgent->infoByKey(Toolbox::addslashes_deep($machineid));

         if (isset($agent['id'])) {

            $taskjobstates = $pfTask->getTaskjobstatesForAgent(
               $agent['id'],
               array('deployinstall')
            );
            if (!$pfAgentModule->isAgentCanDo("DEPLOY", $agent['id'])) {
               foreach ($taskjobstates as $taskjobstate) {
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
                  foreach ($jobstate_order['associatedFiles'] as $hash=>$associatedFiles) {
                     if (!array_key_exists($hash, $order->associatedFiles)) {
                        $order->associatedFiles->$hash = $associatedFiles;
                     }
                  }
                  $taskjobstate->changeStatus(
                     $taskjobstate->fields['id'] ,
                     $taskjobstate::SERVER_HAS_SENT_DATA
                  );
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

   case 'getFilePart':
      $DB->close();
      PluginFusioninventoryDeployFilepart::httpSendFile(filter_input(INPUT_GET, "file"));
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
         'machineid' => filter_input(INPUT_GET, "machineid"),
         'uuid'      => filter_input(INPUT_GET, "uuid")
      );

      if (filter_input(INPUT_GET, "status") == 'ko') {
         $params['code'] = 'ko';
         $fi_currentStep = filter_input(INPUT_GET, "currentStep");
         if (!empty($fi_currentStep)) {
            $params['msg'] = $partjob_mapping[filter_input(INPUT_GET, "currentStep")] . ":" . filter_input(INPUT_GET, "msg");
         } else {
            $params['msg'] = filter_input(INPUT_GET, "msg");
         }
         $error = TRUE;
      }


      if ($error != TRUE) {
         if (filter_input(INPUT_GET, "msg") === 'job successfully completed') {
            //Job is ended and status should be ok
            $params['code'] = 'ok';
            $params['msg'] = filter_input(INPUT_GET, "msg");
         } else {
            $params['code'] = 'running';
            $fi_currentStep = filter_input(INPUT_GET, "currentStep");
            if (!empty($fi_currentStep)) {
               $params['msg'] = $partjob_mapping[filter_input(INPUT_GET, "currentStep")] . ":" . filter_input(INPUT_GET, "msg");
            } else {
               $params['msg'] = filter_input(INPUT_GET, "msg");
            }
         }
      }
      if (is_array($params['msg'])) {
         $htmlspecialchars_flags = ENT_SUBSTITUTE | ENT_DISALLOWED;

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

?>
