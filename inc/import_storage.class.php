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

class PluginFusinvinventoryImport_Storage extends CommonDBTM {


   /**
   * Add or update storage
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the storage
   * @param $dataSection array all values of the section
   *
   *@return id of the storage or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      
      $type_tmp = "";
      $drive_idfield = "";
      $DeviceDrive = "";
      $CompDevice = "";

      $type_tmp = $this->getTypeDrive($dataSection);
      if ($type_tmp == "Drive") {
         // it's cd-rom / dvd
         if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_drive") == '0') {
            return;
         }
         $CompDevice = new Computer_Device('DeviceDrive');
         $DeviceDrive = new DeviceDrive();
         $type_tmp = "Drive";
         $drive_idfield = 'devicedrives_id';
      } else {
         // it's harddisk
         if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_harddrive") == '0') {
            return;
         }
         $CompDevice = new Computer_Device('DeviceHardDrive');
         $DeviceDrive = new DeviceHardDrive();
         $drive_idfield = 'deviceharddrives_id';
      }

      $devID = 0;
      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
      } else if ($type == "add") {
         $devID = 0;
      }
      $drive = array();
      $specificity = "";

      if (isset($dataSection['MODEL'])) {
         $drive['designation'] = $dataSection['MODEL'];
      } else if (isset($dataSection['NAME'])) {
         $drive['designation'] = $dataSection['NAME'];
      }
      if (isset($dataSection['MANUFACTURER'])) {
         $drive['manufacturers_id'] = Dropdown::importExternal('Manufacturer',$dataSection['MANUFACTURER']);
      }
      if (isset($dataSection['INTERFACE'])) {
         $drive['interfacetypes_id'] = Dropdown::importExternal('InterfaceType',$dataSection['INTERFACE']);
      }
      if ($type_tmp == "HardDrive") {
         $specificity = $drive['specif_default'] = "0";
         if ((isset($dataSection['DISKSIZE'])) AND (!empty($dataSection['DISKSIZE']))) {
            $specificity = $drive['specif_default'] = $dataSection['DISKSIZE'];
         }
      }
      $drive_id = $DeviceDrive->import($drive);

      if ($drive_id) {
         if ($type == "update") {
            $array = array();
            $array['id'] =  $items_id;
            $array['computers_id'] =  $CompDevice->fields['computers_id'];
            $array['_itemtype'] =  $DeviceDrive->getType();
            $array[$drive_idfield] =  $drive_id;
            if ($type_tmp == "HardDrive") {
               $array['specificity'] =  $specificity;
            }

            $devID = $CompDevice->update($array);
         } else if ($type == "add") {
            $array = array();
            $array['computers_id'] = $items_id;
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $array['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $array['_itemtype'] =  $DeviceDrive->getType();
            $array[$drive_idfield] =  $drive_id;
            if ($type_tmp == "HardDrive") {
               $array['specificity'] =  $specificity;
            }
            $devID = $CompDevice->add($array);
         }
         return $devID;
      }
      return "";
   }



   /**
   * Delete storage
   *
   * @param $items_id integer id of the storage
   * @param $idmachine integer id of the computer
   *
   *@return nothing
   *
   **/
   function deleteItem($items_id, $idmachine, $sectiondetail) {
      $sectiondetail = unserialize($sectiondetail);

      // Detect if it's drive or harddrive
      $typeDevice = $this->getTypeDrive($sectiondetail);

      $CompDevice = new Computer_Device('DeviceDrive');
      
      $CompHardDevice = new Computer_Device('DeviceHardDrive');
      
      if ($typeDevice == "Drive") {
         $CompDevice->getFromDB($items_id);
         if ($CompDevice->fields['computers_id'] == $idmachine) {
            $CompDevice->delete(array("id" => $items_id,
                                          "_itemtype" => 'DeviceDrive'));
         }
      } else if ($typeDevice == "HardDrive") {
         $CompHardDevice->getFromDB($items_id);
         if ($CompHardDevice->fields['computers_id'] == $idmachine) {
            $CompHardDevice->delete(array("id" => $items_id,
                                          "_itemtype" => 'DeviceHardDrive'));
         }
      }
   }

   

   /**
   * Get type of the drive
   *
   * @param $data array of the storage
   *
   *@return "Drive" or "HardDrive" 
   *
   **/
   function getTypeDrive($data) {
      if (((isset($data['TYPE'])) AND
              ((preg_match("/rom/i", $data["TYPE"])) OR (preg_match("/dvd/i", $data["TYPE"]))
               OR (preg_match("/blue.{0,1}ray/i", $data["TYPE"]))))
            OR
         ((isset($data['MODEL'])) AND
              ((preg_match("/rom/i", $data["MODEL"])) OR (preg_match("/dvd/i", $data["MODEL"]))
               OR (preg_match("/blue.{0,1}ray/i", $data["MODEL"]))))
            OR
         ((isset($data['NAME'])) AND
              ((preg_match("/rom/i", $data["NAME"])) OR (preg_match("/dvd/i", $data["NAME"]))
               OR (preg_match("/blue.{0,1}ray/i", $data["NAME"]))))) {
         
         return "Drive";
      } else {
         return "HardDrive";
      }
   }
}

?>