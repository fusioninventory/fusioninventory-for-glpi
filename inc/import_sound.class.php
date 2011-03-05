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
   *@return id of the sound or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
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
                                                                          $dataSection['MANUFACTURER']);
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
   *@return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $CompDevice = new Computer_Device('DeviceSoundCard');
      $CompDevice->getFromDB($items_id);
      if ($CompDevice->fields['computers_id'] == $idmachine) {
         $CompDevice->delete(array("id" => $items_id,
                                   "_itemtype" => 'DeviceSoundCard'));
      }
   }
}

?>