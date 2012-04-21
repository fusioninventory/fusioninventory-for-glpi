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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryImport_Memory extends CommonDBTM {


   /**
   * Add or update memory
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the memory
   * @param $dataSection array all values of the section
   *
   * @return id of the memory or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $pfConfig = new PluginFusioninventoryConfig();
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_memory") == '0') {
         return;
      }
      
      $CompDevice = new Computer_Device('DeviceMemory');

      if (isset($dataSection["CAPACITY"])
              AND $dataSection["CAPACITY"] == 'No') {
         $dataSection["CAPACITY"] = 0;
      }
      
      $devID = 0;
      $computer_memory = array();
      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
         $computer_memory = $CompDevice->fields;
         if (count($dataSection) == '1'
                 AND isset($dataSection['CAPACITY'])) {
            if ($dataSection['CAPACITY'] == '0') {
               $CompDevice->delete(array('id' => $items_id));
               return;
            }
            $array = array();
            $array['_itemtype'] = 'DeviceMemory';
            $array['specificity'] = $dataSection["CAPACITY"];
            $array['id'] = $items_id;
            $CompDevice->update($array);
            return;
         }
      } else if ($type == "add") {
         $devID = 0;
      }
      $memory = array();

      $memory["designation"]="";
      if (isset($dataSection["TYPE"]) && $dataSection["TYPE"]!="Empty Slot" && $dataSection["TYPE"] != "Unknown") {
         $memory["designation"]=$dataSection["TYPE"];
      }
      if (isset($dataSection["DESCRIPTION"])) {
         if (!empty($memory["designation"])) {
            $memory["designation"].=" - ";
         }
         $memory["designation"] .= $dataSection["DESCRIPTION"];
      }
      
      if ((!isset($dataSection["CAPACITY"])) 
              OR ((isset($dataSection["CAPACITY"]))
                      AND (!preg_match("/^[0-9]+$/i", $dataSection["CAPACITY"])))) {
         return;
      }
      // Not add when capacity = 0
      if (isset($dataSection["CAPACITY"])
                      AND ($dataSection["CAPACITY"]) == '0') {
         return;
      }

      $memory["specif_default"] = $dataSection["CAPACITY"];

      if (isset($dataSection["SPEED"])) {
         $memory["frequence"] = $dataSection["SPEED"];
      }
      if (isset($dataSection["TYPE"])) {
         $memory["devicememorytypes_id"]
               = Dropdown::importExternal('DeviceMemoryType', 
                                          $dataSection["TYPE"],
                                          $_SESSION["plugin_fusinvinventory_entity"]);
      }
      
      $DeviceMemory = new DeviceMemory();
      $memory_id = $DeviceMemory->import($memory);
      if ($memory_id) {
         $array = array();
         $array['_itemtype'] = 'DeviceMemory';
         $array['devicememories_id'] = $memory_id;
         $array['specificity'] = $dataSection["CAPACITY"];
         if ($type == "update") {
            $array['computers_id'] = $computer_memory['computers_id'];
            $array['id'] = $items_id;
            $CompDevice->update($array);
         } else if ($type == "add") {
            $array['computers_id'] = $items_id;
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $array['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $devID = $CompDevice->add($array);
         }
         return $devID;
      }
      return "";
   }



   /**
   * Delete memory
   *
   * @param $items_id integer id of the memory
   * @param $idmachine integer id of the computer
   *
   * @return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $CompDevice = new Computer_Device('DeviceMemory');
      $CompDevice->getFromDB($items_id);
      if ($CompDevice->fields['computers_id'] == $idmachine) {
         $input = array();
         $input['id'] = $items_id;
         $input['_itemtype'] = 'DeviceMemory';
         if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
            $input['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
         }
         $CompDevice->delete($input, 0, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
   }
}

?>