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

class PluginFusinvinventoryImport_Controller extends CommonDBTM {


   /**
   * Add or update controller
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the controller
   * @param $dataSection array all values of the section
   *
   * @return id of the controller or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $pfConfig = new PluginFusioninventoryConfig();
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "component_control") == '0') {
         return;
      }

      $CompDevice = new Computer_Device('DeviceControl');

      $devID = 0;
      $computer_controller = array();
      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
         $computer_controller = $CompDevice->fields;
      } else if ($type == "add") {
         $devID = 0;
      }
      $controller = array();
      if (isset($dataSection['NAME'])) {
         $controller['designation'] = $dataSection['NAME'];
      }
      if ((isset($dataSection['MANUFACTURER']))
              AND (!empty($dataSection['MANUFACTURER']))
              AND (!preg_match("/^\((.*)\)$/", $dataSection['MANUFACTURER'])) ) {
         
         $controller['manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                     $dataSection['MANUFACTURER'],
                                                                     $_SESSION["plugin_fusinvinventory_entity"]);
      }

      $DeviceControl = new DeviceControl();
      $controller_id = $DeviceControl->import($controller);

      if ($controller_id) {
         $computer_controller['devicecontrols_id'] = $controller_id;
         $computer_controller['_itemtype'] = 'DeviceControl';
         if ($type == "update") {
            $computer_controller['id'] = $items_id;
            $CompDevice->update($computer_controller);
         } else if ($type == "add") {
            $computer_controller['computers_id'] = $items_id;
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $computer_controller['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $devID = $CompDevice->add($computer_controller);
         }
         return $devID;         
      }
      return "";
   }



   /**
   * Delete controller
   *
   * @param $items_id integer id of the controller
   * @param $idmachine integer id of the computer
   *
   * @return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $CompDevice = new Computer_Device('DeviceControl');
      $CompDevice->getFromDB($items_id);
      if ($CompDevice->fields['computers_id'] == $idmachine) {
         $input = array();
         $input['id'] = $items_id;
         $input['_itemtype'] = 'DeviceControl';
         if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
            $input['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
         }
         $CompDevice->delete($input, 0, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
   }

}

?>