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

class NetworkEquipmentUpdateDiscoveryTest extends TestCase {

   public $item_id = 0;
   public $datelatupdate = '';


   public $networkports_reference = [
      [
         'items_id'            => 1,
         'itemtype'            => 'NetworkEquipment',
         'entities_id'         => 0,
         'is_recursive'        => 0,
         'logical_number'      => 0,
         'name'                => 'management',
         'instantiation_type'  => 'NetworkPortAggregate',
         'mac'                 => '38:22:d6:3c:da:e7',
         'comment'             => null,
         'is_deleted'          => 0,
         'is_dynamic'          => 0
      ]
   ];

   public $ipaddresses_reference = [
      [
         'entities_id'   => 0,
         'items_id'      => 1,
         'itemtype'      => 'NetworkName',
         'version'       => 4,
         'name'          => '99.99.10.10',
         'binary_0'      => 0,
         'binary_1'      => 0,
         'binary_2'      => 65535,
         'binary_3'      => 1667435018,
         'is_deleted'    => 0,
         'is_dynamic'    => 0,
         'mainitems_id'  => 1,
         'mainitemtype'  => 'NetworkEquipment'

      ]
   ];

   public $source_xmldevice = [
      'SNMPHOSTNAME' => 'switch H3C',
      'DESCRIPTION' => 'H3C Comware Platform Software, Software Version 5.20 Release 2208',
      'AUTHSNMP' => '1',
      'IP' => '99.99.10.10',
      'MAC' => '38:22:d6:3c:da:e7',
      'MANUFACTURER' => 'H3C'
   ];

   public static function setUpBeforeClass(): void {
      // Delete all network equipments
      $networkEquipment = new NetworkEquipment();
      $items = $networkEquipment->find();
      foreach ($items as $item) {
         $networkEquipment->delete(['id' => $item['id']], true);
      }

      // Delete all printers
      $printer = new Printer();
      $items = $printer->find();
      foreach ($items as $item) {
         $printer->delete(['id' => $item['id']], true);
      }

      // Delete all computer
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      // Delete all ipaddresses
      $ipAddress = new IPAddress();
      $items = $ipAddress->find();
      foreach ($items as $item) {
         $ipAddress->delete(['id' => $item['id']], true);
      }

      // Delete all networknames
      $networkName= new NetworkName();
      $items = $networkName->find();
      foreach ($items as $item) {
         $networkName->delete(['id' => $item['id']], true);
      }
   }


   /**
    * @test
    */
   public function AddNetworkEquipment() {

      // Load session rights
      $_SESSION['glpidefault_entity'] = 0;
      Session::initEntityProfiles(2);
      Session::changeProfile(4);
      plugin_init_fusioninventory();

      $pfCND = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $networkEquipment = new NetworkEquipment();

      $input = [
          'name'        => 'switch H3C',
          'entities_id' => '0'
      ];
      $this->item_id = $networkEquipment->add($input);
      $this->assertNotFalse($this->item_id, "Add network equipment failed");
      $networkEquipment->getFromDB($this->item_id);

      $_SESSION['SOURCE_XMLDEVICE'] = $this->source_xmldevice;
      $pfCND->importDevice($networkEquipment);

      $this->assertEquals(1, count($networkEquipment->find()));
   }


   /**
    * @test
    */
   public function NewNetworkEquipmentHasPorts() {
      $networkports = getAllDataFromTable('glpi_networkports');

      $networkEquipment = new NetworkEquipment();
      $item = current($networkEquipment->find([], [], 1));
      $this->networkports_reference[0]['items_id'] = $item['id'];

      $reference = [];
      foreach ($networkports as $data) {
         unset($data['id']);
         unset($data['date_mod']);
         unset($data['date_creation']);
         $reference[] = $data;
      }

      $this->assertEquals($this->networkports_reference,
                          $reference,
                          "Network ports does not match reference on first update");

   }


   /**
    * @test
    */
   public function NewNetworkEquipmentHasIpAdresses() {
      $ipaddresses = getAllDataFromTable('glpi_ipaddresses');

      $items = [];
      foreach ($ipaddresses as $data) {
         unset($data['id']);
         unset($data['date_mod']);
         unset($data['date_creation']);
         $items[] = $data;
      }

      $networkName = new NetworkName();
      $item = current($networkName->find([], [], 1));
      $this->ipaddresses_reference[0]['items_id'] = $item['id'];

      $networkEquipment = new NetworkEquipment();
      $item = current($networkEquipment->find([], [], 1));
      $this->ipaddresses_reference[0]['mainitems_id'] = $item['id'];

      $this->assertEquals($this->ipaddresses_reference,
                          $items,
                          "IP addresses does not match reference on first update");

   }


   /**
    * @test
    */
   public function UpdateNetworkEquipment() {

      // Load session rights
      $_SESSION['glpidefault_entity'] = 0;
      Session::initEntityProfiles(2);
      Session::changeProfile(4);
      plugin_init_fusioninventory();

      // Update 2nd time
      $pfCND = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $networkEquipment = new NetworkEquipment();
      $item = current($networkEquipment->find([], [], 1));

      $networkEquipment->getFromDB($item['id']);

      $_SESSION['SOURCE_XMLDEVICE'] = $this->source_xmldevice;
      $pfCND->importDevice($networkEquipment);

      $this->assertEquals(1, count($networkEquipment->find()));
   }

   /**
    * @test
    */
   public function UpdateNetworkEquipmentOnlyOneNetworkName() {
      $networkNames = getAllDataFromTable('glpi_networknames');
      $this->assertEquals(1, count($networkNames));
   }


   /**
    * @test
    */
   public function UpdateNetworkEquipmentOnlyOneIpaddress() {
      $Ips = getAllDataFromTable('glpi_ipaddresses');
      $this->assertEquals(1, count($Ips));
   }


   /**
    * @test
    */
   public function UpdatedNetworkEquipmentHasPorts() {
      $networkports = getAllDataFromTable('glpi_networkports');

      $this->assertEquals(1, count($networkports), "Must have only 1 network port");

      $networkEquipment = new NetworkEquipment();
      $item = current($networkEquipment->find([], [], 1));
      $this->networkports_reference[0]['items_id'] = $item['id'];

      $reference = [];
      foreach ($networkports as $data) {
         unset($data['id']);
         unset($data['date_mod']);
         unset($data['date_creation']);
         $reference[] = $data;
      }

      $this->assertEquals($this->networkports_reference,
                          $reference,
                          "network ports does not match reference on second update");
   }


   /**
    * @test
    */
   public function UpdateNetworkEquipmentHasIpAdresses() {
      $ipaddresses = getAllDataFromTable('glpi_ipaddresses');

      $items = [];
      foreach ($ipaddresses as $data) {
         unset($data['id']);
         unset($data['date_mod']);
         unset($data['date_creation']);
         $items[] = $data;
      }

      $networkName = new NetworkName();
      $item = current($networkName->find([], [], 1));
      $this->ipaddresses_reference[0]['items_id'] = $item['id'];

      $networkEquipment = new NetworkEquipment();
      $item = current($networkEquipment->find([], [], 1));
      $this->ipaddresses_reference[0]['mainitems_id'] = $item['id'];

      $this->assertEquals(
         $this->ipaddresses_reference,
         $items,
         "IP addresses does not match reference on second update:\n".
         print_r($this->ipaddresses_reference, true)."\n".
         print_r($ipaddresses, true)."\n"
      );
   }
}
