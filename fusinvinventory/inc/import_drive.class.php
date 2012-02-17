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

class PluginFusinvinventoryImport_Drive extends CommonDBTM {


   /**
   * Add or update drive
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the drive
   * @param $dataSection array all values of the section
   *
   * @return id of the drive or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $pfConfig = new PluginFusioninventoryConfig();
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_drive") == '0') {
         return;
      }
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_networkdrive") == '0') {
         if (isset($dataSection['TYPE'])
                 AND $dataSection['TYPE'] == 'Network Drive') {
            return;
         } else if (isset($dataSection['FILESYSTEM'])
                 AND $dataSection['FILESYSTEM'] == 'nfs') {
            return;
         }
      }

      if ((isset($dataSection['TYPE'])) AND
              (($dataSection['TYPE'] == "Removable Disk")
             OR ($dataSection['TYPE'] == "Compact Disc"))) {

         return "";
      }

      $ComputerDisk = new ComputerDisk();

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

      // totalsize    freesize
      if ((isset($dataSection['LABEL'])) AND (!empty($dataSection['LABEL']))) {
         $disk['name']=$dataSection['LABEL'];
      } else if (((!isset($dataSection['VOLUMN'])) OR (empty($dataSection['VOLUMN']))) AND (isset($dataSection['LETTER']))) {
         $disk['name']=$dataSection['LETTER'];
      } else if (isset($dataSection['TYPE'])) {
         $disk['name']=$dataSection['TYPE'];
      } else if (isset($dataSection['VOLUMN'])) {
         $disk['name']=$dataSection['VOLUMN'];
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
         $disk['filesystems_id']=Dropdown::importExternal('Filesystem', 
                                                          $dataSection["FILESYSTEM"],
                                                          $_SESSION["plugin_fusinvinventory_entity"]);
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
            $id_disk = $ComputerDisk->update($disk, $_SESSION["plugin_fusinvinventory_history_add"]);
         } else if ($type == "add") {
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $disk['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $id_disk = $ComputerDisk->add($disk, array(), $_SESSION["plugin_fusinvinventory_history_add"]);
         }
      }
      return $id_disk;
   }



   /**
   * Delete drive
   *
   * @param $items_id integer id of the drive
   * @param $idmachine integer id of the computer
   *
   * @return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $ComputerDisk = new ComputerDisk();
      $ComputerDisk->getFromDB($items_id);
      if ($ComputerDisk->fields['computers_id'] == $idmachine) {
         $input = array();
         $input['id'] = $items_id;
         if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
            $input['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
         }
         $ComputerDisk->delete($input, 0, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
   }
}

?>