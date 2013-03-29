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
   @author    Kevin Roy
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../../inc/includes.php");

$response = FALSE;
//Agent communication using REST protocol
if (isset($_GET['action'])) {
   switch ($_GET['action']) {
      case 'getJobs':
         if(isset($_GET['machineid'])) {
            $pfAgent = new PluginFusioninventoryAgent();
            $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
            $pfTaskjob = new PluginFusioninventoryTaskjob();

            $a_agent = $pfAgent->InfosByKey(Toolbox::addslashes_deep($_GET['machineid']));

            if(isset($a_agent['id'])) {
               $moduleRun = $pfTaskjobstate->getTaskjobsAgent($a_agent['id']);

               foreach ($moduleRun as $className => $array) {
                  if (class_exists($className)) {
                     if (     $className == "PluginFusioninventoryDeployinstall"
                           || $className == "PluginFusioninventoryDeployuninstall"
                     ) {
                        $class = new $className();
                        $response = $class->run($array, $a_agent);
                     }
                  }
               }
            }
         }
         break;

      case 'getFilePart':
         PluginFusioninventoryDeployFilepart::httpSendFile($_GET);
         exit;
         break;

      case 'setStatus':
         $params = array(
            'machineid' => $_GET['machineid'],
            'uuid' => $_GET['uuid']
         );
         if ( array_key_exists("status", $_GET) ) {
            $params['code'] = $_GET['status'];
            switch($params['code']) {
               case 'ok':
                  if ( !array_key_exists("currentStep", $_GET) ) {
                     $params['msg'] = $_GET['msg'];
                  } else {
                     $params['msg'] = $_GET['currentStep'] . ":" . $_GET['msg'];
                  }
                  break;
               case 'ko':
                  $params['code'] = 'ko';
                  $params['msg'] = $_GET['msg'];
                  break;
            }
         } else {
            $params['code'] = 'ok';
            if ( !array_key_exists("currentStep", $_GET) ) {
               $params['msg'] = $_GET['msg'];
            } else {
               $params['msg'] = $_GET['currentStep'] . ":" . $_GET['msg'];
            }
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
