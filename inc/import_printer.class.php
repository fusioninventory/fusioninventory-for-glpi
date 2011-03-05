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

class PluginFusinvinventoryImport_Printer extends CommonDBTM {


   /**
   * Add or update printer
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the printer
   * @param $dataSection array all values of the section
   *
   *@return id of the printer or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $Computer_Item = new Computer_Item();
      
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "import_printer") == '0') {
         return;
      }

      $printer = new Printer();

      $a_printer = array();

      if ($type == 'update') {
         $Computer_Item->getFromDB($items_id);
         $a_printer = $printer->getFromDB($Computer_Item->fields['items_id']);
      } else {
         // Search if a printer yet exist
         if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_printer") == '2') {

            if ((isset($dataSection['SERIAL'])) AND (!empty($dataSection['SERIAL']))) {
               $a_printers = $printer->find("`serial`='".$dataSection['SERIAL']."'","", 1);
               if (count($a_printers) > 0) {
                  foreach($a_printers as $data) {
                     $a_printer = $data;
                  }
               }
            }
         } else if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_printer") == '3') {

            if ((isset($dataSection['SERIAL'])) AND (!empty($dataSection['SERIAL']))) {
               $a_printers = $printer->find("`serial`='".$dataSection['SERIAL']."'","", 1);
               if (count($a_printers) > 0) {
                  foreach($a_printers as $data) {
                     $a_printer = $data;
                  }
               }
            } else {
               return;
            }
         } else if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_printer") == '1') {
            if ((isset($dataSection['NAME'])) AND (!empty($dataSection['NAME']))) {
               $a_printers = $printer->find("`name`='".$dataSection['NAME']."'","", 1);
               if (count($a_printers) > 0) {
                  foreach($a_printers as $data) {
                     $a_printer = $data;
                  }
               } else {
                  $a_printer = array();
               }
               $a_printer['is_global'] = 'yes';
            }

         }
         if (count($a_printer) == 0) {
            $a_printer = array();
         }
      }

      if (isset($dataSection['NAME'])) {
         $a_printer['name'] = $dataSection['NAME'];
      }
      if (isset($dataSection['SERIAL'])) {
         $a_printer['serial'] = $dataSection['SERIAL'];
      }
      if (isset($dataSection['PORT'])) {
         if (strstr($dataSection['PORT'], "USB")) {
            $a_printer['have_usb'] = 1;
         }
      }
      $a_printer['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];

      $printer_id = 0;
      if (!isset($a_printer['id'])) {
         $printer_id = $printer->add($a_printer);
      } else {
         $printer_id = $a_printer['id'];
      }

      $array = array();
      $array['computers_id'] = $items_id;
      $array['itemtype'] = 'Printer';
      $array['items_id'] = $printer_id;
      if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
         $array['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
      }
      $devID = $Computer_Item->add($array);
      return $devID;
   }



   /**
   * Delete printer
   *
   * @param $items_id integer id of the printer
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
                                      "itemtype" => "Printer"));
      }
   }
}

?>