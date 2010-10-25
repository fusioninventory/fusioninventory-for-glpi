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

require_once GLPI_ROOT.'/plugins/fusinvsnmp/inc/communicationsnmp.class.php';

/**
 * Class
 **/
class PluginFusinvinventoryImport_Printer extends CommonDBTM {

   function AddUpdateItem($type, $items_id, $dataSection) {

      foreach($dataSection as $key=>$value) {
         $dataSection[$key] = addslashes_deep($value);
      }

      $printer = new Printer();

      $a_printer = array();

      if ($type == "update") {
         return "";
      }
      // Else (type == "add")
      // Search if a printer yet exist
      if ((isset($dataSection['SERIAL'])) AND (!empty($dataSection['SERIAL']))) {
         $a_printers = $printer->find("`serial`='".$dataSection['SERIAL']."'","", 1);
         if (count($a_printers) == 0) {
            $a_printer = array();
         } else {
            foreach($a_printers as $printer_id=>$data) {
               $a_printer = $data;
            }
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


      if (!isset($a_printer['id'])) {
         $printer_id = $printer->add($a_printer);
      } else {
         $printer_id = $a_printer['id'];
      }

      $Computer_Item = new Computer_Item();
      $devID = $Computer_Item->add(array('computers_id' => $items_id,
                                 'itemtype'     => 'Printer',
                                 'items_id'     => $printer_id,
                                 '_no_history'  => true));
      return $devID;
   }



   function deleteItem() {

   }

}

?>