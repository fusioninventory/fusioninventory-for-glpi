<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

class NetworkEquipmentUpdateDiscovery extends RestoreDatabase_TestCase {

   public $item_id = 0;
   public $datelatupdate = '';


   public $networkports_reference = [
      1 => [
         'id'                  => '1',
         'items_id'            => '1',
         'itemtype'            => 'NetworkEquipment',
         'entities_id'         => '0',
         'is_recursive'        => '0',
         'logical_number'      => '0',
         'name'                => 'management',
         'instantiation_type'  => 'NetworkPortAggregate',
         'mac'                 => '38:22:d6:3c:da:e7',
         'comment'             => null,
         'is_deleted'          => '0',
         'is_dynamic'          => '0'
      ]
   ];

   public $ipaddresses_reference = [
      1 => [
         'id'            => '1',
         'entities_id'   => '0',
         'items_id'      => '1',
         'itemtype'      => 'NetworkName',
         'version'       => '4',
         'name'          => '99.99.10.10',
         'binary_0'      => '0',
         'binary_1'      => '0',
         'binary_2'      => '65535',
         'binary_3'      => '1667435018',
         'is_deleted'    => '0',
         'is_dynamic'    => '0',
         'mainitems_id'  => '1',
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


   /**
    * @test
    */
   public function AddNetworkEquipment() {
      global $DB;

      // Load session rights
      $_SESSION['glpidefault_entity'] = 0;
      Session::initEntityProfiles(2);
      Session::changeProfile(4);

      $pfCND = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $networkEquipment = new NetworkEquipment();

      $input = [
          'name'        => 'switch H3C',
          'entities_id' => '0'
      ];
      $this->item_id = $networkEquipment->add($input);
      $networkEquipment->getFromDB($this->item_id);

      $_SESSION['SOURCE_XMLDEVICE'] = $this->source_xmldevice;
      $pfCND->importDevice($networkEquipment);
   }


   /**
    * @test
    */
   public function NewNetworkEquipmentHasPorts() {
      $networkports = getAllDatasFromTable('glpi_networkports');

      foreach ($networkports as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $networkports[$id] = $data;
      }

      $this->assertEquals($this->networkports_reference,
                          $networkports,
                          "Network ports does not match reference on first update");

   }


   /**
    * @test
    */
   public function NewNetworkEquipmentHasIpAdresses() {
      $ipaddresses = getAllDatasFromTable('glpi_ipaddresses');

      $this->assertEquals($this->ipaddresses_reference,
                          $ipaddresses,
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

      // Update 2nd time
      $pfCND = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $networkEquipment = new NetworkEquipment();

      $networkEquipment->getFromDB(1);

      $_SESSION['SOURCE_XMLDEVICE'] = $this->source_xmldevice;
      $pfCND->importDevice($networkEquipment);
   }


   /**
    * @test
    */
   public function UpdatedNetworkEquipmentHasPorts() {
      $networkports = getAllDatasFromTable('glpi_networkports');

      foreach ($networkports as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $networkports[$id] = $data;
      }

      $this->assertEquals($this->networkports_reference,
                          $networkports,
                          "network ports does not match reference on second update");
   }


   /**
    * @test
    */
   public function UpdateNetworkEquipmentHasIpAdresses() {
      $ipaddresses = getAllDatasFromTable('glpi_ipaddresses');

      $this->assertEquals(
         $this->ipaddresses_reference,
         $ipaddresses,
         "IP addresses does not match reference on second update:\n".
         print_r($this->ipaddresses_reference, true)."\n".
         print_r($ipaddresses, true)."\n"
      );

   }


}
