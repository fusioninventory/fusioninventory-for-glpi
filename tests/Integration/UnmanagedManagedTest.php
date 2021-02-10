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

class UnmanagedManagedTest extends TestCase {

   /*
    * When switch get unknown mac address, it create unknown device (in reality a computer)
    * When have computer inventory, it must delete unknown device with same mac and get
    * the connections to the switch
    */

   public static function setUpBeforeClass(): void {

      // Delete all networkequipment
      $networkEquipment = new NetworkEquipment();
      $items = $networkEquipment->find();
      foreach ($items as $item) {
         $networkEquipment->delete(['id' => $item['id']], true);
      }

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }
   }


   /**
    * @test
    */
   public function AddNetworkEquipment() {

      $this->update_time = date('Y-m-d H:i:s');

      $a_inventory = [
         'PluginFusioninventoryNetworkEquipment' => [
            'sysdescr'                    => 'Cisco IOS Software, C2960 Software (C2960-LANBASEK9-M), Version 12.2(50)SE4, RELEASE SOFTWARE (fc1)\nTechnical Support: http://www.cisco.com/techsupport\nCopyright (c) 1986-2010 by Cisco Systems, Inc.\nCompiled Fri 26-Mar-10 09:14 by prod_rel_team',
            'last_fusioninventory_update' => $this->update_time,
            'cpu'                         => 5,
            'memory'                      => 18,
            'uptime'                      => '157 days, 02:14:44.00'
         ],
         'networkport'       => [],
         'connection-mac'    => [],
         'vlans'             => [],
         'connection-lldp'   => [],
         'internalport'      => ['192.168.30.2'],
         'itemtype'          => 'NetworkEquipment'
      ];
      $a_inventory['NetworkEquipment'] = [
         'name'               => 'switchr2d2',
         'id'                 => 96,
         'serial'             => 'FOC147UJXXX',
         'otherserial'        => '',
         'manufacturers_id'   => 29,
         'locations_id'       => 3,
         'networkequipmentmodels_id' => 3,
         'networkequipmentfirmwares_id' => 3,
         'memory'             => 18,
         'ram'                => 64,
         'is_dynamic'         => 1,
         'mac'                => '6c:50:4d:39:59:90'
      ];

      $a_inventory['networkport'] = [
         '10001' => [
            'ifdescr'          => 'FastEthernet0/1',
            'ifinerrors'       => 869,
            'ifinoctets'       => 1953319640,
            'ifinternalstatus' => 1,
            'iflastchange'     => '156 days, 08:37:22.84',
            'ifmtu'            => 1500,
            'name'             => 'Fa0/1',
            'logical_number'   => 10001,
            'ifouterrors'      => 0,
            'ifoutoctets'      => 554008368,
            'speed'            => 100000000,
            'ifstatus'         => 1,
            'iftype'           => 6,
            'mac'              => '6c:50:4d:39:59:81',
            'trunk'            => 0,
            'ifspeed'          => 100000000
         ]
      ];
      $a_inventory['connection-mac'] = [
         '10001' => ['cc:f9:54:a1:03:45']
      ];
      $a_inventory['vlans'] = [];
      $a_inventory['connection-lldp'] = [];

      $pfiNetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment = new NetworkEquipment();

      $this->items_id = $networkEquipment->add(['serial'      => 'FOC147UJXXX',
         'entities_id' => 0]);

      $this->assertGreaterThan(0, $this->items_id);

      $pfiNetworkEquipmentLib->updateNetworkEquipment($a_inventory, $this->items_id);

      // To be sure not have 2 same informations
      $pfiNetworkEquipmentLib->updateNetworkEquipment($a_inventory, $this->items_id);
   }


   /**
    * @test
    */
   public function NewComputer() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_inventory = [
         'fusioninventorycomputer' => [
            'last_fusioninventory_update' => date('Y-m-d H:i:s')
         ],
         'soundcard'      => [],
         'graphiccard'    => [],
         'controller'     => [],
         'processor'      => [],
         'computerdisk'   => [],
         'memory'         => [],
         'monitor'        => [],
         'printer'        => [],
         'peripheral'     => [],
         'networkport'    => [],
         'software'       => [],
         'harddrive'      => [],
         'virtualmachine' => [],
         'antivirus'      => [],
         'storage'        => [],
         'licenseinfo'    => [],
         'networkcard'    => [],
         'drive'          => [],
         'batteries'      => [],
         'remote_mgmt'    => [],
          'bios'           => [],
         'itemtype'       => 'Computer'
      ];
      $a_inventory['Computer'] = [
         'name'                             => 'pc',
         'users_id'                         => 0,
         'operatingsystems_id'              => 1,
         'operatingsystemversions_id'       => 1,
         'uuid'                             => 1,
         'os_licenseid'                     => '',
         'os_license_number'                => '',
         'operatingsystemservicepacks_id'   => 1,
         'manufacturers_id'                 => 0,
         'computermodels_id'                => 0,
         'serial'                           => 'XB63J7D',
         'computertypes_id'                 => 1,
         'is_dynamic'                       => 1,
         'contact'                          => 'ddurieux'
      ];
      $a_inventory['networkport'] = [
         'em0-cc:f9:54:a1:03:45' => [
            'name'                 => 'em0',
            'netmask'              => '255.255.255.0',
            'subnet'               => '192.168.30.0',
            'mac'                  => 'cc:f9:54:a1:03:45',
            'instantiation_type'   => 'NetworkPortEthernet',
            'virtualdev'           => 0,
            'ssid'                 => '',
            'gateway'              => '',
            'dhcpserver'           => '',
            'logical_number'       => 1,
            'ipaddress'            => ['192.168.30.198']
         ]
      ];

      $networkPort = new NetworkPort();

      $a_networkports = $networkPort->find(['mac' => 'cc:f9:54:a1:03:45']);

      $a_networkport = current($a_networkports);
      $networkports_id = $a_networkport['id'];

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      $computers_id = $computer->add(['serial'      => 'XB63J7D',
                                      'entities_id' => 0]);

      $pfiComputerLib->updateComputer($a_inventory, $computers_id, false);

      $a_networkports = $networkPort->find(['mac' => 'cc:f9:54:a1:03:45']);

      $this->assertEquals(1, count($a_networkports),
         "The MAC address cc:f9:54:a1:03:45 must be tied to only one port");

      $a_networkport = current($a_networkports);

      $this->assertEquals($networkports_id, $a_networkport['id'],
         'The networkport ID is not the same '.
         'between the unknown device and the computer');

      $this->assertEquals('Computer', $a_networkport['itemtype'], "Maybe Computer ");
   }
}
