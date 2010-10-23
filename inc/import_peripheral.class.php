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
class PluginFusinvinventoryImport_Peripheral extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      foreach($dataSection as $key=>$value) {
         $dataSection[$key] = addslashes_deep($value);
      }

      $Peripheral = new Peripheral();

      $a_Peripheral = array();
      if ($type == "update") {
         $devID = $items_id;
         $Peripheral->getFromDB($items_id);
         $a_Peripheral = $Peripheral->fields;
      } else if ($type == "add") {
         $id_Peripheral = 0;
      }

      // Search if exists peripheral with with serial
      if (isset($dataSection['SERIAL'])) {
         $a_array = $Peripheral->find("`serial`='".$dataSection['SERIAL']."'", "", "1");
         foreach ($a_array as $id => $a_Peripheral) {
            $type = "update";
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
                                                                          $dataSection['MANUFACTURER']);
      }

      if ($type == "update") {
         $peripheral_id = $Peripheral->update($a_Peripheral);
      } else if ($type == "add") {
         $peripheral_id = $Peripheral->add($a_Peripheral);
      }

      $Computer_Item = new Computer_Item();
      $devID = $Computer_Item->add(array('computers_id' => $items_id,
                                         'itemtype'     => 'Peripheral',
                                         'items_id'     => $peripheral_id,
                                         '_no_history'  => true));

      return $devID;
   }



   function deleteItem() {

   }

}

?>