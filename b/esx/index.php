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
   @author    Walid Nouh
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

//This call is to check that the ESX inventory service is up and running
if (isset($_GET['status'])) {
   return 'ok';
}
ob_start();
include ("../../../../inc/includes.php");
ob_end_clean();

$response = FALSE;
//Agent communication using REST protocol
if (isset($_GET['action']) && isset($_GET['machineid'])) {

   switch ($_GET['action']) {

      case 'getJobs':
         $pfAgent        = new PluginFusioninventoryAgent();
         $pfTask         = new PluginFusioninventoryTask();
         $pfTaskjob      = new PluginFusioninventoryTaskjob();
         $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

         $agent = $pfAgent->InfosByKey(Toolbox::addslashes_deep($_GET['machineid']));

         if (isset($agent['id'])) {
            $taskjobstates = $pfTask->getTaskjobstatesForAgent(
               $agent['id'],
               array('InventoryComputerESX')
            );

            ////start of json response
            $order = new stdClass;
            $order->jobs = array();

            $module = new PluginFusioninventoryInventoryComputerESX();
            foreach ($taskjobstates as $taskjobstate) {
               $order->jobs[] = $module->run($taskjobstate);

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

         break;

      case 'setLog':
         //Generic method to update logs
         PluginFusioninventoryCommunicationRest::updateLog($_GET);
         break;
   }

   if ($response !== FALSE) {
      echo $response;
   } else {
      echo json_encode((object)array());
   }
}

?>
