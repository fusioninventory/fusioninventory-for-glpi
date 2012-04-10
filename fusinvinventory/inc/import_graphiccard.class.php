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
   * @return id of the graphic card or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $pfConfig = new PluginFusioninventoryConfig();
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
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
         if (count($dataSection) == '1'
                 AND isset($dataSection['MEMORY'])) {
            $array = array();
            $array['_itemtype'] = 'DeviceGraphicCard';
            $array['specificity'] = $dataSection["MEMORY"];
            $array['id'] = $items_id;
            $CompDevice->update($array);
            return;
         }
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
   * @return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $CompDevice = new Computer_Device('DeviceGraphicCard');
      $CompDevice->getFromDB($items_id);
      if ($CompDevice->fields['computers_id'] == $idmachine) {
         $input = array();
         $input['id'] = $items_id;
         $input['_itemtype'] = 'DeviceGraphicCard';
         if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
            $input['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
         }
         $CompDevice->delete($input, 0, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
   }
}

?>