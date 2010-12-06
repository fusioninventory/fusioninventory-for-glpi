<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file: 
// ----------------------------------------------------------------------


if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class 
 **/
class PluginFusinvinventoryImport_Memory extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_memory") == '0') {
         return;
      }
      
      $CompDevice = new Computer_Device('DeviceMemory');

      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
         $computer_memory = $CompDevice->fields;
      } else if ($type == "add") {
         $id_memory = 0;
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
         $dataSection["CAPACITY"]=0;
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
            $devID = $CompDevice->update($array);
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