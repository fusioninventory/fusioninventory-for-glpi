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

class DevicesLocks extends RestoreDatabase_TestCase {


   /**
    * @test
    *
    * lock model, import, field may not change and model may not be created
    */
   public function computerLockItem() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfInventoryComputerInventory = new PluginFusioninventoryInventoryComputerInventory();
      $computer = new Computer();
      $pfLock = new PluginFusioninventoryLock();

      $a_computerinventory = [
          "Computer" => [
              "name"   => "pc002",
              "serial" => "ggheb7ne7",
              "computermodels_id" => "model xxx",
              "manufacturers_id"  => "Dell"
          ],
          "fusioninventorycomputer" => [
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ],
          'soundcard'      => [],
          'graphiccard'    => [],
          'controller'     => [],
          'processor'      => [],
          "computerdisk"   => [],
          'memory'         => [],
          'monitor'        => [],
          'printer'        => [],
          'peripheral'     => [],
          'networkport'    => [],
          'SOFTWARES'      => [],
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

      $pfInventoryComputerInventory->fillArrayInventory($a_computerinventory);
      $pfInventoryComputerInventory->rulepassed(0, 'Computer');

      $this->assertEquals(countElementsInTable('glpi_computers'), 1, 'Must have 1 computer created');
      $computer->getFromDB(1);
      $this->assertEquals($computer->fields['computermodels_id'], 1, "Model not right");
      $this->assertEquals($computer->fields['manufacturers_id'], 1, "Manufacturer not right");

      $input = [
          'tablename'   => 'glpi_computers',
          'items_id'    => 1,
          'tablefields' => exportArrayToDB(['computermodels_id'])
      ];

      $pfLock->add($input);
      $this->assertEquals(countElementsInTable('glpi_plugin_fusioninventory_locks'), 1, 'Lock not right added');

      $a_computerinventory['Computer']['computermodels_id'] = "model yyy";
      $a_computerinventory['Computer']['manufacturers_id'] = "Dell inc.";
      $pfInventoryComputerInventory->fillArrayInventory($a_computerinventory);
      $pfInventoryComputerInventory->rulepassed(1, 'Computer');

      $this->assertEquals(countElementsInTable('glpi_computers'), 1, 'More than 1 computer created');
      $computer->getFromDB(1);
      $this->assertEquals($computer->fields['computermodels_id'], 1, "Model not right");
      $this->assertEquals($computer->fields['manufacturers_id'], 2, "Manufacturer not right");

      $this->assertEquals(countElementsInTable('glpi_computermodels'), 1, 'More than 1 computer model created');
      $this->assertEquals(countElementsInTable('glpi_manufacturers'), 2, 'More than 2 manufacturers created');

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

   }


   /**
    * @test
    *
    * idem but with general lock on itemtype
    */
   public function computerLockItemtype() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfInventoryComputerInventory = new PluginFusioninventoryInventoryComputerInventory();
      $computer = new Computer();
      $pfLock = new PluginFusioninventoryLock();

      $a_computerinventory = [
          "Computer" => [
              "name"   => "pc002",
              "serial" => "ggheb7ne7",
              "computermodels_id" => "model zzz",
              "manufacturers_id"  => "Dell2 inc."
          ],
          "fusioninventorycomputer" => [
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ],
          'soundcard'      => [],
          'graphiccard'    => [],
          'controller'     => [],
          'processor'      => [],
          "computerdisk"   => [],
          'memory'         => [],
          'monitor'        => [],
          'printer'        => [],
          'peripheral'     => [],
          'networkport'    => [],
          'SOFTWARES'      => [],
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

      $input = [
          'tablename'   => 'glpi_computers',
          'items_id'    => 0,
          'tablefields' => exportArrayToDB(['manufacturers_id'])
      ];
      $pfLock->add($input);

      $pfInventoryComputerInventory->fillArrayInventory($a_computerinventory);
      $pfInventoryComputerInventory->rulepassed(1, 'Computer');

      $this->assertEquals(countElementsInTable('glpi_computers'), 1, 'More than 1 computer created');
      $computer->getFromDB(1);
      $this->assertEquals($computer->fields['computermodels_id'], 1, "Model not right");
      $this->assertEquals($computer->fields['manufacturers_id'], 2, "Manufacturer not right");

      $this->assertEquals(countElementsInTable('glpi_computermodels'), 1, 'More than 1 computer model created');
      $this->assertEquals(countElementsInTable('glpi_manufacturers'), 2, 'More than 2 manufacturers created');

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

   }


   /**
    * @test
    *
    * Test computer with manufacturer lock
    */
   public function computerManufacturerLock() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfInventoryComputerInventory = new PluginFusioninventoryInventoryComputerInventory();
      $pfInventoryComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
      $computer = new Computer();
      $pfLock = new PluginFusioninventoryLock();
      $manufacturer = new Manufacturer();

      $a_computerinventory = [
          "Computer" => [
              "name"   => "pc003",
              "serial" => "gtrgvdbg",
              "computermodels_id" => "model xxx",
              "manufacturers_id"  => "Dell"
          ],
          "fusioninventorycomputer" => [
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ],
          'soundcard'      => [],
          'graphiccard'    => [],
          'controller'     => [],
          'processor'      => [],
          "computerdisk"   => [],
          'memory'         => [],
          'monitor'        => [],
          'printer'        => [],
          'peripheral'     => [],
          'networkport'    => [],
          'SOFTWARES'      => [],
          'harddrive'      => [],
          'virtualmachine' => [],
          'antivirus'      => [],
          'storage'        => [],
          'licenseinfo'    => [],
          'networkcard'    => [],
          'drive'          => [],
          'batteries'      => [],
          'remote_mgmt'    => [],
          'bios'           => [
              'manufacturers_id' => 'Award',
              'designation'      => 'Award BIOS'
          ],
          'itemtype'       => 'Computer'
      ];

      $input = [
          'name'        => 'pc003',
          'serial'      => 'gtrgvdbg',
          'entities_id' => 0
      ];
      $computers_id = $computer->add($input);

      $input = [
          'tablename'   => 'glpi_computers',
          'items_id'    => $computers_id,
          'tablefields' => exportArrayToDB(['manufacturers_id'])
      ];
      $pfLock->add($input);

      $pfInventoryComputerInventory->fillArrayInventory($a_computerinventory);
      $pfInventoryComputerInventory->rulepassed($computers_id, 'Computer');

      $this->assertEquals(countElementsInTable('glpi_computers'), 1, 'More than 1 computer created');
      $computer->getFromDB(1);
      $this->assertEquals($computer->fields['manufacturers_id'], 0, "Manufacturer not right");

      $this->assertEquals(countElementsInTable('glpi_devicefirmwares'), 1, 'More than 1 bios component created');
      $this->assertEquals(countElementsInTable('glpi_items_devicefirmwares'), 1, 'More than 1 bios component link created');

      $deviceBios = new DeviceFirmware();
      $deviceBios->getFromDB(1);

      $this->assertEquals($deviceBios->fields['manufacturers_id'], 1, "bios manufacturer not right");

      $this->assertEquals(countElementsInTable('glpi_manufacturers'), 1, 'More than 1 manufacturer created');
      $manufacturer->getFromDB($deviceBios->fields['manufacturers_id']);
      $this->assertEquals($manufacturer->fields['name'], 'Award', "Manufacturer name not right");

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }


   /**
    * @test
    *
    * idem but with general lock on itemtype
    */
   public function switchLockItemtype() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfLock = new PluginFusioninventoryLock();
      $networkEquipment = new NetworkEquipment();
      $pfCommunicationNetworkInventory = new PluginFusioninventoryCommunicationNetworkInventory();

      $input = [
          'tablename'   => 'glpi_networkequipments',
          'items_id'    => 0,
          'tablefields' => exportArrayToDB(['locations_id'])
      ];
      $pfLock->add($input);

      $a_inventory = [
          'PluginFusioninventoryNetworkEquipment' => [
                  'sysdescr'                    => 'Cisco IOS Software, C2960 Software (C2960-LANBASEK9-M), Version 12.2(50)SE4, RELEASE SOFTWARE (fc1)\nTechnical Support: http://www.cisco.com/techsupport\nCopyright (c) 1986-2010 by Cisco Systems, Inc.\nCompiled Fri 26-Mar-10 09:14 by prod_rel_team',
                  'last_fusioninventory_update' => '2016-03-24 09:41:25',
                  'cpu'                         => 5,
                  'memory'                      => 18,
                  'uptime'                      => '157 days, 02:14:44.00'
                ],
          'networkport'       => [],
          'connection-mac'    => [],
          'vlans'             => [],
          'connection-lldp'   => [],
          'internalport'      => ['192.168.30.67', '192.168.40.67', '192.168.50.67'],
          'itemtype'          => 'NetworkEquipment'
          ];
      $a_inventory['NetworkEquipment'] = [
               'name'               => 'switchr2d2',
               'id'                 => 1,
               'serial'             => 'FOC147UJEU4',
               'manufacturers_id'   => 'Cisco',
               'locations_id'       => 'dc1 > rack 02',
               'networkequipmentmodels_id' => 'C2960',
               'networkequipmentfirmwares_id' => '12.2(50)SE4',
               'memory'             => 18,
               'ram'                => 64,
               'is_dynamic'         => 1,
               'mac'                => '6c:50:4d:39:59:80'
      ];

      $input = [
          'serial'      => 'FOC147UJEU4',
          'entities_id' => 0];
      $networkEquipment->add($input);

      $PLUGIN_FUSIONINVENTORY_XML = '';
      $pfCommunicationNetworkInventory->importDevice('NetworkEquipment', 1, $a_inventory, 1);

      $this->assertEquals(countElementsInTable('glpi_locations'), 0, 'Location has been created :/');
      $networkEquipment->getFromDB(1);
      $this->assertEquals($networkEquipment->fields['name'], 'switchr2d2', "Switch not updated");
      $this->assertEquals($networkEquipment->fields['locations_id'], 0, "Locations id must be 0");

   }


}
