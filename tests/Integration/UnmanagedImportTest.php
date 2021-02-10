<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2021 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class UnmanagedImportTest extends TestCase {

   public static function setUpBeforeClass(): void {

      // Delete all networkEquipments
      $networkEquipment = new NetworkEquipment();
      $items = $networkEquipment->find();
      foreach ($items as $item) {
         $networkEquipment->delete(['id' => $item['id']], true);
      }

      // Delete all networkports
      $networkPort = new NetworkPort();
      $items = $networkPort->find();
      foreach ($items as $item) {
         $networkPort->delete(['id' => $item['id']], true);
      }
   }


   /**
    * @test
    */
   public function ImportNetworkEquipment() {

      $pfUnmanaged      = new PluginFusioninventoryUnmanaged();
      $networkEquipment = new NetworkEquipment();
      $networkPort      = new NetworkPort();
      $networkName      = new NetworkName();
      $iPAddress        = new IPAddress();

      $input= [
          'name'        => 'switch',
          'entities_id' => 0,
          'item_type'   => 'NetworkEquipment',
          'sysdescr'    => 'Cisco machin chose',
          'locations_id'=> 1,
          'is_dynamic'  => 1,
          'serial'      => 'XXS6BEF3',
          'comment'     => 'this is a comment',
          'plugin_fusioninventory_configsecurities_id' => 1
      ];
      $unmanageds_id = $pfUnmanaged->add($input);

      // * Add networkport
      $input = [];
      $input['itemtype']            = 'PluginFusioninventoryUnmanaged';
      $input['items_id']            = $unmanageds_id;
      $input['instantiation_type']  = 'NetworkPortEthernet';
      $input['name']                = 'general';
      $input['mac']                 = '00:00:00:43:ae:0f';
      $input['is_dynamic']          = 1;
      $networkports_id = $networkPort->add($input);

      $input = [];
      $input['items_id']   = $networkports_id;
      $input['itemtype']   = 'NetworkPort';
      $input['name']       = '';
      $input['is_dynamic'] = 1;
      $networknames_id     = $networkName->add($input);

      $input = [];
      $input['entities_id']   = 0;
      $input['itemtype']      = 'NetworkName';
      $input['items_id']      = $networknames_id;
      $input['name']          = '192.168.20.1';
      $input['is_dynamic']    = 1;
      $iPAddress->add($input);

      $pfUnmanaged->import($unmanageds_id);

      $cnt = countElementsInTable("glpi_networkequipments");

      $this->assertEquals(1, $cnt, "May have network equipment added");

      $cnt = countElementsInTable("glpi_plugin_fusioninventory_unmanageds");

      $this->assertEquals(0, $cnt, "Unknown device may be deleted");

      $networkEquipment->getFromDBByCrit(['name' => 'switch']);

      $this->assertEquals('XXS6BEF3', $networkEquipment->fields['serial'], "Serial");
      $this->assertEquals(1, $networkEquipment->fields['is_dynamic'], "is_dynamic");
      $this->assertEquals(1, $networkEquipment->fields['locations_id'], "locations_id");
      $this->assertEquals('this is a comment', $networkEquipment->fields['comment'], "comment");

      $networkPort->getFromDBByCrit([]);
      unset($networkPort->fields['date_mod']);
      unset($networkPort->fields['date_creation']);
      $networkPortId = $networkPort->fields['id'];
      unset($networkPort->fields['id']);
      $a_reference = [
          'name'                 => 'general',
          'items_id'             => $networkEquipment->fields['id'],
          'itemtype'             => 'NetworkEquipment',
          'entities_id'          => 0,
          'is_recursive'         => 0,
          'logical_number'       => 0,
          'instantiation_type'   => 'NetworkPortEthernet',
          'mac'                  => '00:00:00:43:ae:0f',
          'comment'              => null,
          'is_deleted'           => 0,
          'is_dynamic'           => 1
      ];
      $this->assertEquals($a_reference, $networkPort->fields, "Networkport");
      $networkName->getFromDBByCrit(['items_id' => $networkPortId]);
      unset($networkName->fields['date_mod']);
      unset($networkName->fields['date_creation']);
      $networkNameId = $networkName->fields['id'];
      unset($networkName->fields['id']);
      $a_reference = [
          'entities_id' => 0,
          'items_id'    => $networkPortId,
          'itemtype'    => 'NetworkPort',
          'comment'     => null,
          'fqdns_id'    => 0,
          'is_deleted'  => 0,
          'is_dynamic'  => 1,
          'name'        => ''
      ];
      $this->assertEquals($a_reference, $networkName->fields, "Networkname");
      $iPAddress->getFromDBByCrit(['name' => '192.168.20.1']);
      $a_reference = [
          'name'        => '192.168.20.1',
          'entities_id' => 0,
          'items_id'    => $networkNameId,
          'itemtype'    => 'NetworkName',
          'version'     => 4,
          'binary_0'    => 0,
          'binary_1'    => 0,
          'binary_2'    => 65535,
          'binary_3'    => 3232240641,
          'is_deleted'  => 0,
          'is_dynamic'  => 1,
          'mainitems_id'  => $networkEquipment->fields['id'],
          'mainitemtype'  => 'NetworkEquipment'
      ];
      unset($iPAddress->fields['id']);
      $this->assertEquals($a_reference, $iPAddress->fields, "IPAddress");
   }
}
