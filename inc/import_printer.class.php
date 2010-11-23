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
class PluginFusinvinventoryImport_Printer extends CommonDBTM {

   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $Computer_Item = new Computer_Item();
            
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "import_printer") == '0') {
         return;
      }

      foreach($dataSection as $key=>$value) {
         $dataSection[$key] = addslashes_deep($value);
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
                  foreach($a_printers as $printer_id=>$data) {
                     $a_printer = $data;
                  }
               }
            }
         } else if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_printer") == '3') {

            if ((isset($dataSection['SERIAL'])) AND (!empty($dataSection['SERIAL']))) {
               $a_printers = $printer->find("`serial`='".$dataSection['SERIAL']."'","", 1);
               if (count($a_printers) > 0) {
                  foreach($a_printers as $printer_id=>$data) {
                     $a_printer = $data;
                  }
               }
            }
            if (count($a_printer) == 0) {
               return;
            }
         } else if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                 "import_printer") == '1') {
            if ((isset($dataSection['NAME'])) AND (!empty($dataSection['NAME']))) {
               $a_printers = $printer->find("`name`='".$dataSection['NAME']."'","", 1);
               if (count($a_printers) > 0) {
                  foreach($a_printers as $printer_id=>$data) {
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

      if (!isset($a_printer['id'])) {
         $printer_id = $printer->add($a_printer);
      } else {
         $printer_id = $a_printer['id'];
      }

      $devID = $Computer_Item->add(array('computers_id' => $items_id,
                                 'itemtype'     => 'Printer',
                                 'items_id'     => $printer_id,
                                 '_no_history'  => $_SESSION["plugin_fusinvinventory_no_history_add"]));
      return $devID;
   }



   function deleteItem($items_id) {
      $Computer_Item = new Computer_Item();
      $Computer_Item->getFromDB($items_id);
      if ($Computer_Item->fields['computers_id'] == $idmachine) {
         $Computer_Item->delete(array("id" => $items_id,
                                      "itemtype" => "Printer"));
      }
   }

}

?>