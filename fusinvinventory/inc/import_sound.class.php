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

class PluginFusinvinventoryImport_Sound extends CommonDBTM {


   /**
   * Add or update sound
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the sound
   * @param $dataSection array all values of the section
   *
   * @return id of the sound or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $pfConfig = new PluginFusioninventoryConfig();
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_soundcard") == '0') {
         return;
      }

      $CompDevice = new Computer_Device('DeviceSoundCard');

      $devID = 0;
      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
      } else if ($type == "add") {
         $devID = 0;
      }
      $sound = array();
      if (isset($dataSection['NAME'])) {
         $sound['designation'] = $dataSection['NAME'];
      }
      if ((isset($dataSection['MANUFACTURER']))
              AND (!empty($dataSection['MANUFACTURER']))
              AND (!preg_match("/^\((.*)\)$/", $dataSection['MANUFACTURER'])) ) {
         
         $sound['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                               $dataSection['MANUFACTURER'],
                                                               $_SESSION["plugin_fusinvinventory_entity"]);
      }
      if (isset($dataSection['DESCRIPTION'])) {
         $sound['comment'] = $dataSection['DESCRIPTION'];
      }

      $DeviceSoundCard = new DeviceSoundCard();
      $sound_id = $DeviceSoundCard->import($sound);

      if ($sound_id) {
         $array = array();
         $array['_itemtype'] = 'DeviceSoundCard';
         $array['devicesoundcards_id'] = $sound_id;
         if ($type == "update") {
            $array['id'] = $items_id;
            $array['computers_id'] = $CompDevice->fields['computers_id'];
            $devID = $CompDevice->update($array);
         } else if ($type == "add") {
            $array['computers_id'] = $items_id;
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $array['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $devID = $CompDevice->add($array);
         }
         return $devID;         
      }
      return "";
   }



   /**
   * Delete sound
   *
   * @param $items_id integer id of the sound
   * @param $idmachine integer id of the computer
   *
   * @return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $CompDevice = new Computer_Device('DeviceSoundCard');
      $CompDevice->getFromDB($items_id);
      if ($CompDevice->fields['computers_id'] == $idmachine) {
         $input = array();
         $input['id'] = $items_id;
         $input['_itemtype'] = "DeviceSoundCard";
         if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
            $input['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
         }
         $CompDevice->delete($input, 0, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
   }
}

?>