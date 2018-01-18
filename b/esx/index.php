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
 * This file is used to manage the REST communication for ESX module
 * with the agent
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

//This call is to check that the ESX inventory service is up and running
$fi_status =filter_input(INPUT_GET, "status");
if (!empty($fi_status)) {
   return 'ok';
}
ob_start();
include ("../../../../inc/includes.php");
ob_end_clean();

$response = false;
//Agent communication using REST protocol
$fi_machineid = filter_input(INPUT_GET, "machineid");
if (!empty($fi_machineid)) {

   switch (filter_input(INPUT_GET, "action")) {

      case 'getJobs':
         $pfAgent        = new PluginFusioninventoryAgent();
         $pfTask         = new PluginFusioninventoryTask();
         $pfTaskjob      = new PluginFusioninventoryTaskjob();
         $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

         $agent = $pfAgent->infoByKey(Toolbox::addslashes_deep(filter_input(INPUT_GET, "machineid")));

         if (isset($agent['id'])) {
            $taskjobstates = $pfTask->getTaskjobstatesForAgent(
               $agent['id'],
               ['InventoryComputerESX']
            );

            ////start of json response
            $order = new stdClass;
            $order->jobs = [];

            $module = new PluginFusioninventoryInventoryComputerESX();
            foreach ($taskjobstates as $taskjobstate) {
               $order->jobs[] = $module->run($taskjobstate);

               $taskjobstate->changeStatus(
                  $taskjobstate->fields['id'],
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

         break;

      case 'setLog':
         //Generic method to update logs
         PluginFusioninventoryCommunicationRest::updateLog($_GET);
         break;
   }

   if ($response !== false) {
      echo $response;
   } else {
      echo json_encode((object)[]);
   }
}

