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
   *@return id of the memory or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_memory") == '0') {
         return;
      }
      
      $CompDevice = new Computer_Device('DeviceMemory');

      $devID = 0;
      $computer_memory = array();
      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
         $computer_memory = $CompDevice->fields;
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
      if ((!isset($dataSection["CAPACITY"])) OR ((isset($dataSection["CAPACITY"])) AND (!is_numeric($dataSection["CAPACITY"])))) {
         return;
         //$dataSection["CAPACITY"]=0;
      }

      $memory["specif_default"] = $dataSection["CAPACITY"];

      if (isset($dataSection["SPEED"])) {
         $memory["frequence"] = $dataSection["SPEED"];
      }
      if (isset($dataSection["TYPE"])) {
         $memory["devicememorytypes_id"]
               = Dropdown::importExternal('DeviceMemoryType', $dataSection["TYPE"]);
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
   *@return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $CompDevice = new Computer_Device('DeviceMemory');
      $CompDevice->getFromDB($items_id);
      if ($CompDevice->fields['computers_id'] == $idmachine) {
         $CompDevice->delete(array("id" => $items_id,
                                   "_itemtype" => 'DeviceMemory'));
      }
   }
}

?>