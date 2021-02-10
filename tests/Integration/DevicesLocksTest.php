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
   @since     2016

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class DevicesLocksTest extends TestCase {

   public static function setUpBeforeClass(): void {
      global $DB;

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      // Delete all computermodels
      $computerModel = new ComputerModel();
      $items = $computerModel->find();
      foreach ($items as $item) {
         $computerModel->delete(['id' => $item['id']], true);
      }

      // Delete all networkequipment
      $networkEquipment = new NetworkEquipment();
      $items = $networkEquipment->find();
      foreach ($items as $item) {
         $networkEquipment->delete(['id' => $item['id']], true);
      }

      // Delete all devicebios
      $deviceBios = new DeviceFirmware();
      $items = $deviceBios->find();
      foreach ($items as $item) {
         $deviceBios->delete(['id' => $item['id']], true);
      }

      // Delete all manufacturers
      $manufacturer = new Manufacturer();
      $items = $manufacturer->find();
      foreach ($items as $item) {
         $manufacturer->delete(['id' => $item['id']], true);
      }

      // Delete all locations
      $location = new Location();
      $items = $location->find();
      foreach ($items as $item) {
         $location->delete(['id' => $item['id']], true);
      }

      // Delete all locks
      $DB->query('DELETE FROM glpi_plugin_fusioninventory_locks');

   }


   public static function tearDownAfterClass(): void {
      global $DB;

      // Delete all locks
      $DB->query('DELETE FROM glpi_plugin_fusioninventory_locks');
   }


   /**
    * @test
    *
    * lock model, import, field may not change and model may not be created
    */
   public function computerLockItem() {
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

      $this->assertEquals(1, countElementsInTable('glpi_computers'), 'Must have 1 computer created');
      $computer = new Computer();
      $computerModel = new ComputerModel();
      $manufacturer = new Manufacturer();

      $computer->getFromDBByCrit(['name' => 'pc002']);
      $computerModel->getFromDBByCrit(['name' => 'model xxx']);
      $manufacturer->getFromDBByCrit(['name' => 'Dell']);

      $this->assertEquals($computerModel->fields['id'], $computer->fields['computermodels_id'], "Model not right");
      $this->assertEquals($manufacturer->fields['id'], $computer->fields['manufacturers_id'], "Manufacturer not right");

      $input = [
          'tablename'   => 'glpi_computers',
          'items_id'    => $computer->fields['id'],
          'tablefields' => exportArrayToDB(['computermodels_id'])
      ];

      $pfLock->add($input);
      $this->assertEquals(1, countElementsInTable('glpi_plugin_fusioninventory_locks'), 'Lock not right added');

      $a_computerinventory['Computer']['computermodels_id'] = "model yyy";
      $a_computerinventory['Computer']['manufacturers_id'] = "Dell inc.";
      $pfInventoryComputerInventory->fillArrayInventory($a_computerinventory);
      $pfInventoryComputerInventory->rulepassed($computer->fields['id'], 'Computer');

      $this->assertEquals(1, countElementsInTable('glpi_computers'), 'More than 1 computer created');
      $computer = new Computer();
      $computerModel = new ComputerModel();
      $manufacturer = new Manufacturer();

      $computer->getFromDBByCrit(['name' => 'pc002']);
      $computerModel->getFromDBByCrit(['name' => 'model xxx']);
      $manufacturer->getFromDBByCrit(['name' => 'Dell inc.']);

      $this->assertEquals($computerModel->fields['id'], $computer->fields['computermodels_id'], "Model not right");
      $this->assertEquals($manufacturer->fields['id'], $computer->fields['manufacturers_id'], "Manufacturer not right");

      $this->assertEquals(1, countElementsInTable('glpi_computermodels'), 'More than 1 computer model created');
      $this->assertEquals(2, countElementsInTable('glpi_manufacturers'), 'More than 2 manufacturers created');
   }


   /**
    * @test
    *
    * idem but with general lock on itemtype
    */
   public function computerLockItemtype() {
      global $DB;

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfInventoryComputerInventory = new PluginFusioninventoryInventoryComputerInventory();
      $computer = new Computer();

      // Delete all computers
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }
      // Delete all locks
      $pfLock = new PluginFusioninventoryLock();
      $DB->query('DELETE FROM glpi_plugin_fusioninventory_locks');

      $a_computerinventory = [
         "Computer" => [
            "name"   => "pc003",
            "serial" => "ggheb7ne8",
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
         'tablefields' => exportArrayToDB(['serial', 'computermodels_id'])
      ];

      $pfLock->add($input);
      $this->assertEquals(1, countElementsInTable('glpi_plugin_fusioninventory_locks'), 'Lock not right added');

      $pfInventoryComputerInventory->fillArrayInventory($a_computerinventory);
      $pfInventoryComputerInventory->rulepassed(0, 'Computer');

      $computer = new Computer();
      $manufacturer = new Manufacturer();

      $computer->getFromDBByCrit(['name' => 'pc003']);
      $manufacturer->getFromDBByCrit(['name' => 'Dell2 inc.']);

      $this->assertEquals(0, $computer->fields['computermodels_id'], "Model not right");
      $this->assertEquals($manufacturer->fields['id'], $computer->fields['manufacturers_id'], "Manufacturer not right");
      $this->assertEquals('', $computer->fields['serial'], "Serial not right");

   }


   /**
    * @test
    *
    * idem but with general lock on itemtype
    */
   public function switchLockItemtypeNotDuplicated() {
      $pfLock = new PluginFusioninventoryLock();

      $input = [
         'tablename'   => 'glpi_computers',
         'items_id'    => 0,
         'tablefields' => exportArrayToDB(['computermodels_id'])
      ];

      ob_start();
      $ret = $pfLock->add($input);
      ob_end_clean();
      $items = getAllDataFromTable('glpi_plugin_fusioninventory_locks');
      $this->assertEquals(1, count($items), 'Lock must be unique '.print_r($items, true));
      $this->assertFalse($ret);
   }


   /**
    * @test
    *
    * idem but with general lock on itemtype
    */
   public function switchLockItemtype() {
      global $DB;

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfLock = new PluginFusioninventoryLock();
      $networkEquipment = new NetworkEquipment();
      $pfCommunicationNetworkInventory = new PluginFusioninventoryCommunicationNetworkInventory();

      // Delete all networkequipments
      $items = $networkEquipment->find();
      foreach ($items as $item) {
         $networkEquipment->delete(['id' => $item['id']], true);
      }
      // Delete all locks
      $pfLock = new PluginFusioninventoryLock();
      $DB->query('DELETE FROM glpi_plugin_fusioninventory_locks');

      $input = [
          'tablename'   => 'glpi_networkequipments',
          'items_id'    => 0,
          'tablefields' => exportArrayToDB(['locations_id'])
      ];
      $pfLock->add($input);

      $input = [
         'name'        => 'sw1',
         'serial'      => 'FOC147UJEU4',
         'entities_id' => 0
      ];
      $networkEquipmentId = $networkEquipment->add($input);
      $this->assertNotFalse($networkEquipmentId);

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
         'name'                         => 'switchr2d2',
         'id'                           => $networkEquipmentId,
         'serial'                       => 'FOC147UJEU4',
         'manufacturers_id'             => 'Cisco',
         'locations_id'                 => 'dc1 > rack 02',
         'networkequipmentmodels_id'    => 'C2960',
         'networkequipmentfirmwares_id' => '12.2(50)SE4',
         'memory'                       => 18,
         'ram'                          => 64,
         'is_dynamic'                   => 1,
         'mac'                          => '6c:50:4d:39:59:80'
      ];

      $PLUGIN_FUSIONINVENTORY_XML = '';
      $pfCommunicationNetworkInventory->importDevice('NetworkEquipment', $networkEquipmentId, $a_inventory, 1);

      $this->assertEquals(0, countElementsInTable('glpi_locations'), 'Location has been created :/');
      $networkEquipment = new NetworkEquipment();
      $networkEquipment->getFromDBByCrit(['serial' => 'FOC147UJEU4']);
      $this->assertEquals('switchr2d2', $networkEquipment->fields['name'], "Switch not right updated");
      $this->assertEquals(0, $networkEquipment->fields['locations_id'], "Locations id must be 0");
   }


   /**
    * @test
    */
   public function testLockMonitor() {
      global $DB;

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCommunication  = new PluginFusioninventoryCommunication();
      $monitor = new Monitor();
      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      // Delete all monitors
      $items = $monitor->find();
      foreach ($items as $item) {
         $monitor->delete(['id' => $item['id']], true);
      }
      // Delete all computers
      $computer = new COmputer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }
      // Delete all locks
      $DB->query('DELETE FROM glpi_plugin_fusioninventory_locks');

      $computer_xml =
      '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <ACCESSLOG>
      <LOGDATE>2017-02-01 06:27:09</LOGDATE>
    </ACCESSLOG>
    <BIOS>
      <ASSETTAG/>  <BDATE>03/01/2016</BDATE>
      <BMANUFACTURER>Dell Inc.</BMANUFACTURER>
      <BVERSION>1.3.3</BVERSION>
      <MMANUFACTURER>Dell Inc.</MMANUFACTURER>
      <MMODEL>07TYC2</MMODEL>
      <MSN>/5BTGP72/CN12963646012E/</MSN>
      <SKUNUMBER>0704</SKUNUMBER>
      <SMANUFACTURER>Dell Inc.</SMANUFACTURER>
      <SMODEL>XPS 13 9350</SMODEL>
      <SSN>5BTGP72</SSN>
    </BIOS>
    <HARDWARE>
      <ARCHNAME>x86_64-linux-gnu-thread-multi</ARCHNAME>
      <CHASSIS_TYPE>Laptop</CHASSIS_TYPE>
      <CHECKSUM>70383</CHECKSUM>
      <DATELASTLOGGEDUSER>Mon Jan 30 16:49</DATELASTLOGGEDUSER>
      <DEFAULTGATEWAY>172.28.213.1</DEFAULTGATEWAY>
      <DNS>172.28.200.20/127.0.0.1</DNS>
      <ETIME>3</ETIME>
      <IPADDR>172.28.213.147/172.28.213.114/172.17.0.1</IPADDR>
      <LASTLOGGEDUSER>adelauna</LASTLOGGEDUSER>
      <MEMORY>7830</MEMORY>
      <NAME>LU002</NAME>
      <OSCOMMENTS>#201611260431 SMP Sat Nov 26 09:33:21 UTC 2016</OSCOMMENTS>
      <OSNAME>Ubuntu 16.04.1 LTS</OSNAME>
      <OSVERSION>4.8.11-040811-generic</OSVERSION>
      <PROCESSORN>1</PROCESSORN>
      <PROCESSORS>2300</PROCESSORS>
      <PROCESSORT>Intel(R) Core(TM) i5-6200U CPU @ 2.30GHz</PROCESSORT>
      <SWAP>8035</SWAP>
      <USERID>adelaunay</USERID>
      <UUID>4C4C4544-0042-5410-8047-B5C04F503732</UUID>
      <VMSYSTEM>Physical</VMSYSTEM>
      <WINPRODID>ID-1000010001</WINPRODID>
      <WORKGROUP>ad.teclib.infra/luisant.chartres.workgroup.teclib.infra</WORKGROUP>
    </HARDWARE>
    <MONITORS>
      <BASE64>AP///////wAQrFVAUzIyNQ0UAQMKLBl47u6Vo1RMmSYPUFSlSwBxT4GAqcABAQEBAQEBAQEBMCpAyGCEZDAYUBMAu/kQAAAeAAAA/wBQMTI1UjAzVTUyMlMKAAAA/ABERUxMIFAyMDEwSAogAAAA/QA4TB5TEAAKICAgICAgAOc=</BASE64>
      <CAPTION>DELL P2010H</CAPTION>
      <DESCRIPTION>13/2010</DESCRIPTION>
      <MANUFACTURER>Dell Inc.</MANUFACTURER>
      <NAME>Dell P2010H (Analog)</NAME>
      <PORT>VGA</PORT>
      <SERIAL>P125R03U243P</SERIAL>
      <TYPE>Dell P2010H (Analog)</TYPE>
    </MONITORS>
  </CONTENT>
  <DEVICEID>LU002-2016-05-12-10-04-59</DEVICEID>
  <QUERY>INVENTORY</QUERY>
</REQUEST>';

      $pfCommunication->handleOCSCommunication($computer_xml, '', 'glpi');

      $this->assertEquals(countElementsInTable('glpi_monitors'), 1, 'The monitor has not been created :/');

      // Check computer and monitor have id 1
      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'The monitor has not id 1 :/');
      $this->assertEquals(1, countElementsInTable('glpi_computers'), 'The computer has not id 1 :/');

      $monitor->update([
         'id' => 1,
         'name' => 'another name',
         'serial' => 'XXXX-1-XXXX'
      ]);

      // Test replaceids and lock may not delete fields if in lock
      $a_inventory = [
         "Computer" => [
            "name"   => "LU002",
            "serial" => "5BTGP72"
          ],
          'monitor'        => [
              [
                  'name'    => 'DELL P2010H',
                  'manufacturers_id'=> "Dell Inc.",
                  'serial'  => 'P125R03U243P',
                  'is_dynamic' => 1
              ]
          ],
      ];
      $manufacturer = new Manufacturer();
      $computer = new Computer();
      $manufacturer->getFromDBByCrit(['name' => 'Dell Inc.']);
      $computer->getFromDBByCrit(['name' => 'LU002']);

      $a_computerinventory = $pfFormatconvert->replaceids($a_inventory, 'Computer', $computer->fields['id']);
      $reference = [
         "Computer" => [
            "name"   => "LU002",
            "serial" => "5BTGP72"
          ],
          'monitor'        => [
              [
                  'name'    => 'DELL P2010H',
                  'manufacturers_id'=> $manufacturer->fields['id'],
                  'serial'  => 'P125R03U243P',
                  'is_dynamic' => 1
              ]
          ],
      ];
      $this->assertEquals($a_computerinventory, $reference, 'Replaceid with lock must not delete fields');
   }
}
