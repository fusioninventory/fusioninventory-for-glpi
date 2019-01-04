<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the extended information of a computer.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Common inventory methods (local or snmp)
 */
class PluginFusioninventoryInventoryCommon extends CommonDBTM {

   /**
    * Import firmwares
    * @since 9.2+2.0
    *
    * @param string $itemtype the itemtype to be inventoried
    * @param array   $a_inventory Inventory data
    * @param integer     Network equipment id
    *
    * @return void
    */
   function importFirmwares($itemtype, $a_inventory, $items_id, $no_history = false) {
      if (!isset($a_inventory['firmwares']) || !count($a_inventory['firmwares'])) {
         return;
      }

      $ftype = new DeviceFirmwareType();
      $ftype->getFromDBByCrit(['name' => 'Firmware']);
      $default_type = $ftype->getId();
      foreach ($a_inventory['firmwares'] as $a_firmware) {
         $firmware = new DeviceFirmware();
         $input = [
            'designation'              => $a_firmware['name'],
            'version'                  => $a_firmware['version'],
            'devicefirmwaretypes_id'   => isset($a_firmware['devicefirmwaretypes_id']) ? $a_firmware['devicefirmwaretypes_id'] : $default_type,
            'manufacturers_id'         => $a_firmware['manufacturers_id']
         ];

         //Check if firmware exists
         $firmware->getFromDBByCrit($input);
         if ($firmware->isNewItem()) {
            $input['entities_id'] = $_SESSION['glpiactive_entity'];
            //firmware does not exists yet, create it
            $fid = $firmware->add($input);
         } else {
            $fid = $firmware->getID();
         }

         $relation = new Item_DeviceFirmware();
         $input = [
            'itemtype'           => $itemtype,
            'items_id'           => $items_id,
            'devicefirmwares_id' => $fid
         ];
         //Check if firmware relation with equipment
         $relation->getFromDBByCrit($input);
         if ($relation->isNewItem()) {
            $input = $input + [
               'is_dynamic'   => 1,
               'entities_id'  => $_SESSION['glpiactive_entity']
            ];
            $relation->add($input, [], !$no_history);
         }
      }
   }

   /**
    * Import ports
    *
    * @param array $a_inventory
    * @param integer $items_id
    */
   function importPorts($itemtype, $a_inventory, $items_id, $no_history = false) {

      $networkPort     = new NetworkPort();
      $pfNetworkPort   = new PluginFusioninventoryNetworkPort();
      $networkports_id = 0;

      foreach ($a_inventory['networkport'] as $a_port) {

         $params = [
            'itemtype'           => $itemtype,
            'items_id'           => $items_id,
            'instantiation_type' => 'NetworkPortEthernet',
            'logical_number'     => $a_port['logical_number']
         ];
         $new = false;
         if ($networkPort->getFromDBByCrit($params) == false) {
            //The port has not been found.
            //We then try to check if the port exists with another
            //logical_number but the same mac
            //The case has been found on SHARP printers
            $params = ['itemtype'           => $itemtype,
                       'items_id'           => $items_id,
                       'instantiation_type' => 'NetworkPortEthernet',
                       'mac'                => $a_port['mac']];
            if ($networkPort->getFromDBByCrit($params) == false) {
               $new = true;
            }
         }
         if ($new) {
            // Add port
            $a_port['instantiation_type'] = 'NetworkPortEthernet';
            $a_port['items_id']    = $items_id;
            $a_port['itemtype']    = $itemtype;
            $networkports_id = $networkPort->add($a_port, [], !$no_history);
            unset($a_port['id']);
            $a_pfnetworkport_DB = current($pfNetworkPort->find(
                    ['networkports_id' => $networkports_id], [], 1));
            $a_port['id'] = $a_pfnetworkport_DB['id'];
            $pfNetworkPort->update($a_port);
         } else {
            // Update port
            $networkports_id = $networkPort->fields['id'];
            $a_port['id']    = $networkPort->fields['id'];
            $networkPort->update($a_port);
            unset($a_port['id']);

            // Check if pfnetworkport exist.
            $a_pfnetworkport_DB = current($pfNetworkPort->find(
                    ['networkports_id' => $networkports_id], [], 1));
            $a_port['networkports_id'] = $networkports_id;
            if (isset($a_pfnetworkport_DB['id'])) {
               $a_port['id'] = $a_pfnetworkport_DB['id'];
               $pfNetworkPort->update($a_port);
            } else {
               $a_port['networkports_id'] = $networkports_id;
               $pfNetworkPort->add($a_port);
            }
         }
      }
   }

   /**
    * Import firmwares
    * @since 9.2+2.0
    *
    * @param string $itemtype the itemtype to be inventoried
    * @param array   $a_inventory Inventory data
    * @param integer     Network equipment id
    *
    * @return void
    */
   function importSimcards($itemtype, $a_inventory, $items_id, $no_history = false) {
      if (!isset($a_inventory['simcards']) || !count($a_inventory['simcards'])) {
         return;
      }

      $simcard  = new DeviceSimcard();

      foreach ($a_inventory['simcards'] as $a_simcard) {
         $relation = new Item_DeviceSimcard();

         $input = [
            'designation' => 'Simcard',
         ];

         //Check if the simcard already exists
         $simcard->getFromDBByCrit($input);
         if ($simcard->isNewItem()) {
            $input['entities_id'] = $_SESSION['glpiactive_entity'];
            //firmware does not exists yet, create it
            $simcards_id = $simcard->add($input);
         } else {
            $simcards_id = $simcard->getID();
         }

         //Import Item_DeviceSimcard
         $input = [
            'serial'            => $a_simcard['serial'],
            'msin'              => $a_simcard['msin'],
            'devicesimcards_id' => $simcards_id
         ];
         //Check if there's already a connection between the simcard and an asset
         $relation->getFromDBByCrit($input);

         $input['itemtype']      = $itemtype;
         $input['items_id']      = $items_id;
         $input['is_dynamic']    = 1;
         $input['entities_id']   = $_SESSION['glpiactive_entity'];
         if ($relation->isNewItem()) {
            $relations_id = $relation->add($input, [], !$no_history);
         } else {
            $input['id']  = $relation->getID();
            $relations_id = $relation->update($input);
         }
      }
   }
}
