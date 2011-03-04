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

class PluginFusinvinventoryImport_Graphiccard extends CommonDBTM {


   /**
   * Add or update graphic card
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the graphic card
   * @param $dataSection array all values of the section
   *
   *@return id of the graphic card or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_graphiccard") == '0') {
         return;
      }

      $CompDevice = new Computer_Device('DeviceGraphicCard');

      $devID = 0;
      $computer_graphiccard = array();
      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
         $computer_graphiccard = $CompDevice->fields;
      } else if ($type == "add") {
         $devID = 0;
      }
      $graphiccard = array();

      if (isset($dataSection['NAME'])) {
         $graphiccard['designation'] = $dataSection['NAME'];
      }
      $graphiccard['specif_default'] = "0";
      if ((isset($dataSection['MEMORY'])) AND (!empty($dataSection['MEMORY']))) {
         $graphiccard['specif_default'] = $dataSection['MEMORY'];
      }

      if ($graphiccard['specif_default'] == "") {
         $graphiccard['specif_default'] = "0";
      }

      $DeviceGraphicCard = new DeviceGraphicCard();
      $graphiccard_id = $DeviceGraphicCard->import($graphiccard);

      if ($graphiccard_id) {
         $array = array();
         $array['_itemtype'] = 'DeviceGraphicCard';
         $array['devicegraphiccards_id'] = $graphiccard_id;
         $array['specificity'] = $graphiccard['specif_default'];
         if ($type == "update") {
            $array['computers_id'] = $computer_graphiccard['computers_id'];
            $array['id'] = $items_id;
            $CompDevice->update($array);
         } else if ($type == "add") {
            $array['computers_id'] = $items_id;
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $array['_no_history']= $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $devID = $CompDevice->add($array);
         }
         return $devID;
      }
      return "";
   }



   /**
   * Delete graphic card
   *
   * @param $items_id integer id of the graphic card
   * @param $idmachine integer id of the computer
   *
   *@return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $CompDevice = new Computer_Device('DeviceGraphicCard');
      $CompDevice->getFromDB($items_id);
      if ($CompDevice->fields['computers_id'] == $idmachine) {
         $CompDevice->delete(array("id" => $items_id,
                                   "_itemtype" => 'DeviceGraphicCard'));
      }
   }
}

?>