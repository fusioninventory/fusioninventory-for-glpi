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
class PluginFusinvinventoryImport_Processor extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_processor") == '0') {
         return;
      }

      $CompDevice = new Computer_Device('DeviceProcessor');

      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
         $computer_Processor = $CompDevice->fields;
      } else if ($type == "add") {
         $id_disk = 0;
      }
      $processor = array();
      $specificity = "";

      $processor = array();
      $processor['specif_default'] = 0;
      if (isset($dataSection['NAME'])) {
         $processor['designation'] = $dataSection['NAME'];
      }
      if (isset($dataSection['SPEED'])) {
         $processor['frequence'] = $dataSection['SPEED'];
         $specificity = $dataSection['SPEED'];
      }
      if (isset($dataSection['MANUFACTURER'])) {
         $processor["manufacturers_id"] = Dropdown::importExternal('Manufacturer',
                                                                 $dataSection['MANUFACTURER']);
      }
      

      $DeviceProcessor = new DeviceProcessor();
      $Processor_id = $DeviceProcessor->import($processor);

      $devID = "";
      if ($type == "add") {
         $array = array();
         $array['computers_id'] = $items_id;
         $array['_itemtype'] = 'DeviceProcessor';
         $array['specificity'] = $specificity;
         $array['deviceprocessors_id'] = $Processor_id;
         if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
            $array['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
         }
         $devID = $CompDevice->add($array);
      }
      return $devID;
   }


   
   function deleteItem($items_id, $idmachine) {
      $CompDevice = new Computer_Device('DeviceProcessor');
      $CompDevice->getFromDB($items_id);
      if ($CompDevice->fields['computers_id'] == $idmachine) {
         $CompDevice->delete(array("id" => $items_id,
                                   "_itemtype" => 'DeviceProcessor'));
      }
   }

}

?>