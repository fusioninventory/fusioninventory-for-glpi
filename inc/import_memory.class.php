<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
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

require_once GLPI_ROOT.'/plugins/fusinvsnmp/inc/communicationsnmp.class.php';

/**
 * Class 
 **/
class PluginFusinvinventoryImport_Memory extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      foreach($dataSection as $key=>$value) {
         $dataSection[$key] = addslashes_deep($value);
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
      if ($dataSection["TYPE"]!="Empty Slot" && $dataSection["TYPE"] != "Unknown") {
         $memory["designation"]=$dataSection["TYPE"];
      }
      if (isset($dataSection["DESCRIPTION"])) {
         if (!empty($memory["designation"])) {
            $memory["designation"].=" - ";
         }
         $memory["designation"] .= $dataSection["DESCRIPTION"];
      }
      if (!is_numeric($dataSection["CAPACITY"])) {
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
         if ($type == "update") {
            $devID = $CompDevice->add(array('computers_id' => $items_id,
                                            '_itemtype'     => 'DeviceMemory',
                                            'devicememories_id'     => $memory_id,
                                            'specificity'  => $dataSection["CAPACITY"]));
         } else if ($type == "add") {
            $devID = $CompDevice->add(array('computers_id' => $items_id,
                                            '_no_history' => true,
                                            '_itemtype'     => 'DeviceMemory',
                                            'devicememories_id'     => $memory_id,
                                            'specificity'  => $dataSection["CAPACITY"]));
         }
         return $devID;
      }
      return "";
   }


   
   function deleteItem($items_id) {
      $CompDevice = new Computer_Device('DeviceMemory');
      $CompDevice->delete(array("id" => $items_id));
   }

}

?>