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
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class
 **/
class PluginFusinvinventoryImport_Storage extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      foreach($dataSection as $key=>$value) {
         $dataSection[$key] = addslashes_deep($value);
      }
      
      $type_tmp = "";
      $drive_idfield = "";
      $DeviceDrive = "";
      $CompDevice = "";

      $type_tmp = $this->getTypeDrive($dataSection);
      if ($type_tmp == "Drive") {         
         // it's cd-rom / dvd
         $CompDevice = new Computer_Device('DeviceDrive');
         $DeviceDrive = new DeviceDrive();
         $type_tmp = "Drive";
         $drive_idfield = 'devicedrives_id';
      } else {
         // it's harddisk
         $CompDevice = new Computer_Device('DeviceHardDrive');
         $DeviceDrive = new DeviceHardDrive();
         $drive_idfield = 'deviceharddrives_id';
      }

      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
         $computer_drive = $CompDevice->fields;
      } else if ($type == "add") {
         $id_drive = 0;
      }
      $drive = array();

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
            $array['computers_id'] =  $computer_drive['computers_id'];
            $array['_itemtype'] =  $DeviceDrive->getType();
            $array[$drive_idfield] =  $drive_id;
            if ($type_tmp == "HardDrive") {
               $array['specificity'] =  $specificity;
            }

            $devID = $CompDevice->update($array);
         } else if ($type == "add") {
            $array = array();
            $array['computers_id'] = $items_id;
            $array['_no_history'] = true;
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



   function deleteItem($items_id, $idmachine) {

      // Detect if it's drive or harddrive
      $xml = simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA);

      $a_designation = array();
      foreach ($xml->CONTENT->STORAGES as $child) {
         $data = array();
         if (isset($child->MODEL)) {
            $data['MODEL'] = $child->MODEL;
         }
         if (isset($child->TYPE)) {
            $data['TYPE'] = $child->TYPE;
         }
         if (isset($child->NAME)) {
            $data['NAME'] = $child->NAME;
         }
         $typeDevice = $this->getTypeDrive($data);


         if (isset($child->MODEL)) {
            $a_designation['"'.$child->MODEL."'"] = $typeDevice;
         } else if (isset($child->NAME)) {
            $a_designation['"'.$child->NAME."'"] = $typeDevice;
         }
      }
      $CompDevice = new Computer_Device('DeviceDrive');
      $DeviceDrive = new DeviceDrive();
      
      $a_drives = $CompDevice->find("`computers_id`='".$idmachine."' ");
      foreach ($a_drives as $id => $data) {
         $DeviceDrive->getFromDB($data['devicedrives_id']);
         if (isset($a_designation['"'.$DeviceDrive->fields['designation']."'"])) {
            unset($a_designation['"'.$DeviceDrive->fields['designation']."'"]);
         }
      }

      $CompHardDevice = new Computer_Device('DeviceHardDrive');
      $DeviceHardDrive = new DeviceHardDrive();

      $a_drives = $CompHardDevice->find("`computers_id`='".$idmachine."' ");
      foreach ($a_drives as $id => $data) {
         $DeviceHardDrive->getFromDB($data['deviceharddrives_id']);
         if (isset($a_designation['"'.$DeviceHardDrive->fields['designation']."'"])) {
            unset($a_designation['"'.$DeviceHardDrive->fields['designation']."'"]);
         }
      }

      foreach($a_designation as $name => $type) {
         if ($type == "Drive") {
            $CompDevice->getFromDB($items_id);
            if ($CompDevice->fields['computers_id'] == $idmachine) {
               $CompDevice->delete(array("id" => $items_id));
            }
         } else if ($type == "HardDrive") {
            $CompHardDevice->getFromDB($items_id);
            if ($CompHardDevice->fields['computers_id'] == $idmachine) {
               $CompHardDevice->delete(array("id" => $items_id));
            }
         }
      }
   }

   

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