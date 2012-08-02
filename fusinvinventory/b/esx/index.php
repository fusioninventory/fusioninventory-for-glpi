<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Walid Nouh
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
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

if(!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../../..');
}
include (GLPI_ROOT."/inc/includes.php");

$response = false;
//Agent communication using REST protocol
if (isset($_GET['action']) && isset($_GET['machineid'])) {
   switch ($_GET['action']) {
      case 'getJobs':
         //Specific to ESX
         $pfAgent = new PluginFusioninventoryAgent();
         $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

         $a_agent = $pfAgent->InfosByKey(Toolbox::addslashes_deep($_GET['machineid']));
           if (count($a_agent)) {
            $moduleRun = $pfTaskjobstate->getTaskjobsAgent($a_agent['id']);
            foreach ($moduleRun as $className => $array) {
               if (class_exists($className)) {
                  if ($className == "PluginFusinvinventoryESX") {
                     $class = new $className();
                     $response = $class->run($array, $response);
                  }
               }
            }
         }
         break;
      case 'setLog':
         //Generic method to update logs
         PluginFusioninventoryFusionCommunication::updateLog($_GET);
         break;
   }
   
   if ($response) {
      echo json_encode($response);
    }

}

?>