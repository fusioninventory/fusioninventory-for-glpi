<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryImport_Monitor extends CommonDBTM {


   /**
   * Add or update monitor
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the monitor
   * @param $dataSection array all values of the section
   *
   *@return id of the monitor or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "import_monitor") == '0') {
         return;
      }

      $monitor = new Monitor();
      $Computer_Item = new Computer_Item();

      $a_monitor = array();

      if ($type == "update") {
         $Computer_Item->getFromDB($items_id);
         $a_monitor = $monitor->getFromDB($Computer_Item->fields['items_id']);
      } else {
         // Search if a monitor yet exist

         if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_monitor") == '2') {

            if ((isset($dataSection['SERIAL'])) AND (!empty($dataSection['SERIAL']))) {
               $a_monitors = $monitor->find("`serial`='".$dataSection['SERIAL']."'","", 1);
               if (count($a_monitors) > 0) {
                  foreach($a_monitors as $data) {
                     $a_monitor = $data;
                  }
               }
            }
         } else if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_monitor") == '3') {
            // Import only with serial number

            if ((isset($dataSection['SERIAL'])) AND (!empty($dataSection['SERIAL']))) {
               $a_monitors = $monitor->find("`serial`='".$dataSection['SERIAL']."'","", 1);
               if (count($a_monitors) > 0) {
                  foreach($a_monitors as $data) {
                     $a_monitor = $data;
                  }
               }
            } else {
               return;
            }
         } else if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_monitor") == '1') {
            if ((isset($dataSection['NAME'])) AND (!empty($dataSection['NAME']))) {
               $a_monitors = $monitor->find("`name`='".$dataSection['NAME']."'","", 1);
               if (count($a_monitors) > 0) {
                  foreach($a_monitors as $data) {
                     $a_monitor = $data;
                  }
               } else {
                  $a_monitor = array();
               }
               $a_monitor['is_global'] = 'yes';
            }

         }
         if (count($a_monitor) == 0) {
            $a_monitor = array();
         }
      }

      if (isset($dataSection['CAPTION'])) {
         $a_monitor['name'] = $dataSection['CAPTION'];
      }
      if ((isset($dataSection['MANUFACTURER']))
              AND (!empty($dataSection['MANUFACTURER']))) {
         $a_monitor['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                   $dataSection['MANUFACTURER']);
      }
      if (isset($dataSection['SERIAL'])) {
         $a_monitor['serial'] = $dataSection['SERIAL'];
      }
      if (isset($dataSection['DESCRIPTION'])) {
         $a_monitor['comment'] = $dataSection['DESCRIPTION'];
      }
      $a_monitor['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];

      $a_monitor['is_global'] = 0;

      $monitor_id = 0;
      if (!isset($a_monitor['id'])) {
         $monitor_id = $monitor->add($a_monitor);
      } else {
         $monitor->update($a_monitor);
         $monitor_id = $a_monitor['id'];
      }

      $array = array();
      $array['computers_id'] = $items_id;
      $array['itemtype'] = 'Monitor';
      $array['items_id'] = $monitor_id;
      if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
         $array['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
      }
      $devID = $Computer_Item->add($array);
      return $devID;
   }



   /**
   * Delete monitor
   *
   * @param $items_id integer id of the monitor
   * @param $idmachine integer id of the computer
   *
   *@return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $Computer_Item = new Computer_Item();
      $Computer_Item->getFromDB($items_id);
      if ($Computer_Item->fields['computers_id'] == $idmachine) {
         $Computer_Item->delete(array("id" => $items_id,
                                      "itemtype" => "Monitor"));
      }
   }
}

?>