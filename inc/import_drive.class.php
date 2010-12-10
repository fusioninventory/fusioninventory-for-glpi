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
class PluginFusinvinventoryImport_Drive extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_drive") == '0') {
         return;
      }

      if ((isset($dataSection['TYPE'])) AND
              (($dataSection['TYPE'] == "Removable Disk")
             OR ($dataSection['TYPE'] == "Compact Disc"))) {

         return "";
      }

      $ComputerDisk = new ComputerDisk;

      $id_disk = 0;
      $disk=array();
      if ($type == "update") {
         $id_disk = $items_id;
         $ComputerDisk->getFromDB($items_id);
         $disk = $ComputerDisk->fields;
      } else if ($type == "add") {
         $id_disk = 0;
         $disk=array();
         $disk['computers_id']=$items_id;
      }

      // totalsize 	freesize
      if ((isset($dataSection['LABEL'])) AND (!empty($dataSection['LABEL']))) {
         $disk['name']=$dataSection['LABEL'];
      } else if (((!isset($dataSection['VOLUMN'])) OR (empty($dataSection['VOLUMN']))) AND (isset($dataSection['LETTER']))) {
         $disk['name']=$dataSection['LETTER'];
      } else if (isset($dataSection['TYPE'])) {
         $disk['name']=$dataSection['TYPE'];
      }
      if (isset($dataSection['VOLUMN'])) {
         $disk['device']=$dataSection['VOLUMN'];
      }
      if (isset($dataSection['MOUNTPOINT'])) {
         $disk['mountpoint'] = $dataSection['MOUNTPOINT'];
      } else if (isset($dataSection['LETTER'])) {
         $disk['mountpoint'] = $dataSection['LETTER'];
      } else if (isset($dataSection['TYPE'])) {
         $disk['mountpoint'] = $dataSection['TYPE'];
      }
      if (isset($dataSection["FILESYSTEM"])) {
         $disk['filesystems_id']=Dropdown::importExternal('Filesystem', $dataSection["FILESYSTEM"]);
      }
      if (isset($dataSection['TOTAL'])) {
         $disk['totalsize']=$dataSection['TOTAL'];
      }
      $disk['freesize'] = 0;
      if ((isset($dataSection['FREE'])) AND (!empty($dataSection['FREE']))) {
         $disk['freesize']=$dataSection['FREE'];
      }
      if ($disk['freesize'] == '') {
         $disk['freesize'] = 0;
      }
      if (isset($disk['name']) && !empty($disk["name"])) {
         if ($type == "update") {
            $id_disk = $ComputerDisk->update($disk);
         } else if ($type == "add") {
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $disk['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $id_disk = $ComputerDisk->add($disk);
         }
      }
      return $id_disk;
   }


   
   function deleteItem($items_id, $idmachine) {
      $ComputerDisk = new ComputerDisk;
      $ComputerDisk->getFromDB($items_id);
      if ($ComputerDisk->fields['computers_id'] == $idmachine) {
         $ComputerDisk->delete(array("id" => $items_id));
      }
   }

}

?>