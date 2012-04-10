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

class PluginFusinvinventoryImport_Peripheral extends CommonDBTM {


   /**
   * Add or update peripheral
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the peripheral
   * @param $dataSection array all values of the section
   *
   * @return id of the peripheral or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $pfConfig = new PluginFusioninventoryConfig();
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "import_peripheral") == '0') {
         return;
      }

      $Peripheral = new Peripheral();
      $Computer_Item = new Computer_Item();

      $a_Peripheral = array();
      
      if ($type == "update") {
         $Computer_Item->getFromDB($items_id);
         $a_Peripheral = $Peripheral->getFromDB($Computer_Item->fields['items_id']);
      } else {
         // Search if a peripheral yet exist
         if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_peripheral") == '2') {

            if ((isset($dataSection['SERIAL'])) AND (!empty($dataSection['SERIAL']))) {
               $a_peripherals = $Peripheral->find("`serial`='".$dataSection['SERIAL']."'","", 1);
               if (count($a_peripherals) > 0) {
                  foreach($a_peripherals as $data) {
                     $a_Peripheral = $data;
                  }
               }
            }
         } else if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_peripheral") == '3') {

            if ((isset($dataSection['SERIAL'])) AND (!empty($dataSection['SERIAL']))) {
               $a_peripherals = $Peripheral->find("`serial`='".$dataSection['SERIAL']."'","", 1);
               if (count($a_peripherals) > 0) {
                  foreach($a_peripherals as $data) {
                     $a_Peripheral = $data;
                  }
               }
            } else {
               return;
            }
         } else if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_peripheral") == '1') {
            // GLOBAL
            $name = '';
            if ((isset($dataSection['PRODUCTNAME']))
                    AND (!empty($dataSection['PRODUCTNAME']))) {

               $name = $dataSection['PRODUCTNAME'];
            } else if (isset($dataSection['NAME'])) {
               $name = $dataSection['NAME'];
            }
            if ($name != '') {
               $a_peripherals = $Peripheral->find("`name`='".$name."'
                              AND `is_global`='1'","", 1);
               if (count($a_peripherals) > 0) {
                  foreach($a_peripherals as $data) {
                     $a_Peripheral = $data;
                  }
               } else {
                  $a_Peripheral = array();
               }
               $a_Peripheral['is_global'] = 1;
            }

         }
         if (count($a_Peripheral) == 0) {
            $a_Peripheral = array();
            $a_Peripheral['is_global'] = 0;
         }
      }

      if ((isset($dataSection['PRODUCTNAME']))
              AND (!empty($dataSection['PRODUCTNAME']))) {

         $a_Peripheral['name'] = $dataSection['PRODUCTNAME'];
      } else if (isset($dataSection['NAME'])) {
         $a_Peripheral['name'] = $dataSection['NAME'];
      }
      if (isset($dataSection['SERIAL'])) {
         $a_Peripheral['serial'] = $dataSection['SERIAL'];
      }
      if ((isset($dataSection['MANUFACTURER']))
              AND (!empty($dataSection['MANUFACTURER']))) {
         $a_Peripheral['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                      $dataSection['MANUFACTURER'],
                                                                      $_SESSION["plugin_fusinvinventory_entity"]);
      }
      $a_Peripheral['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];

      $peripheral_id = 0;
      if ($type == "update") {
         $Peripheral->update($a_Peripheral);
      } else if ($type == "add") {
         if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_peripheral") == '1') {
            $a_Peripheral['is_global'] = 1;
         }

         PluginFusinvinventoryInventory::addDefaultStateIfNeeded($a_Peripheral, true, 
                                                                 $a_Peripheral['is_global']);
         if (isset($a_Peripheral['id'])) {
            $Peripheral->update($a_Peripheral);
            $peripheral_id = $a_Peripheral['id'];
         } else {
            $peripheral_id = $Peripheral->add($a_Peripheral);
         }
      }

      $array = array();
      $array['computers_id'] = $items_id;
      $array['itemtype'] = 'Peripheral';
      $array['items_id'] = $peripheral_id;
      if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
         $array['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
      }
      if ($type == "add") { // Case where have same peripheral xx times
         $a_find = $Computer_Item->find("`computers_id`='".$items_id."'
            AND `itemtype` = 'Peripheral'
            AND `items_id`='".$peripheral_id."'");
         if (count($a_find) > 0) {
            return;
         }
      }
      $devID = $Computer_Item->add($array);
      return $devID;
   }



   /**
   * Delete peripheral
   *
   * @param $items_id integer id of the peripheral
   * @param $idmachine integer id of the computer
   *
   * @return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $pfConfig = new PluginFusioninventoryConfig();
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_peripheral") != '0') {
         $Computer_Item = new Computer_Item();
         if ($Computer_Item->getFromDB($items_id)) {
            if ($Computer_Item->fields['computers_id'] == $idmachine) {
               $input = array();
               $input['id'] = $items_id;
   //            $input['itemtype'] = "Peripheral";
               if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
                  $input['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
               }
               $Computer_Item->delete($input, 0, $_SESSION["plugin_fusinvinventory_history_add"]);
            }
         }
      }
   }
}

?>