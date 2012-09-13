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
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$pfConstructDevice = new PluginFusinvsnmpConstructDevice();

Html::header($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","constructdevice");
Session::checkLoginUser();

PluginFusioninventoryMenu::displayMenu("mini");

if (isset($_POST['update'])) {
   // Convert in nice array, cenvert to JSON and send to server
   $a_json = array();
   foreach($_POST['oidsselected'] as $num) {
      $split = explode("-", $num);
      $portcounter = 0;
      if (isset($_POST['oid_port_counter_'.$split[0]])) {
         $portcounter = $_POST['oid_port_counter_'.$split[0]];
      }
      $a_json['updateMib'][] = array('oid_id' => $split[0],
                                 'vlan' => $_POST['vlan_'.$split[0]],
                                 'oid_port_dyn' => $_POST['oid_port_dyn_'.$split[0]],
                                 'mappings_id' => $split[1],
                                 'oid_port_counter' => $portcounter); 
   }
   
   $a_json['devices_id'] = $_POST['devices_id'];
   
   $pfConstructmodel = new PluginFusinvsnmpConstructmodel();
   if ($pfConstructmodel->connect()) {
      if ($pfConstructmodel->showAuth()) {
         if (isset($_POST['devices_id'])
            AND $_POST['devices_id'] > 0) {
            
            $pfConstructDevice = new PluginFusinvsnmpConstructDevice();
            $dataret = $pfConstructmodel->sendMib($a_json);
         }
      }
   }
   $pfConstructmodel->closeConnection();
   Html::redirect($CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/constructmodel.php?devices_id=".$a_json['devices_id']);
}

Html::footer();

?>