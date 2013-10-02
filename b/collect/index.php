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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
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
   switch ($_GET['action']) {
      
      case 'getJobs':
         if(isset($_GET['machineid'])) {
            $pfAgent = new PluginFusioninventoryAgent();
            $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

            $a_agent = $pfAgent->InfosByKey(Toolbox::addslashes_deep($_GET['machineid']));
            if (isset($a_agent['id'])) {
               $moduleRun = $pfTaskjobstate->getTaskjobsAgent($a_agent['id']);
             
               foreach ($moduleRun as $className => $array) {
                  if (class_exists($className)) {
                     if ($className == "PluginFusioninventoryCollect") {
                        $class = new PluginFusioninventoryCollect();
                        foreach ($array as $data) {
                           $out = $class->run($data, $a_agent);
                           if (count($out) > 0) {
                              $response[] = $out;
                           }
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
         $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
         $pfAgent = new PluginFusioninventoryAgent();

         $jobstate = current($pfTaskjobstate->find("`uniqid`='".$_GET['uuid']."'
            AND `state`!='".PluginFusioninventoryTaskjobstate::FINISHED."'", '', 1));
         
         $pfAgent->getFromDB($jobstate['plugin_fusioninventory_agents_id']);
         $computers_id = $pfAgent->fields['computers_id'];

         $a_values = $_GET;
         unset($a_values['action']);
         unset($a_values['uuid']);
         
         switch ($jobstate['itemtype']) {
            
            case 'PluginFusioninventoryCollect_Registry':
               // update registry content
               $pfCRC = new PluginFusioninventoryCollect_Registry_Content();
               $pfCRC->updateComputer($computers_id, 
                                      $a_values,
                                      $jobstate['items_id']);
               break;

         }
         break;

   }

   if (count($response) > 0) {
      echo json_encode($response);
   } else {
      echo json_encode((object)array());
   }

}

?>