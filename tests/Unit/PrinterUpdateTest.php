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

class PrinterUpdateTest extends TestCase {

   public $items_id = 0;
   public $update_time = '';

   public static function setUpBeforeClass(): void {
      global $DB;

      // Delete all printers
      $printer = new Printer();
      $items = $printer->find();
      foreach ($items as $item) {
         $printer->delete(['id' => $item['id']], true);
      }
      $_SESSION["glpiID"] = 0;

      // delete locks
      $DB->query('DELETE FROM glpi_plugin_fusioninventory_locks');
   }

   public static function tearDownAfterClass(): void {
      $_SESSION["glpiID"] = 2;
   }

   /**
    * @test
    */
   public function AddPrinter() {

      $this->update_time = date('Y-m-d H:i:s');

      $a_inventory = [
         'PluginFusioninventoryPrinter' => [
            'sysdescr'                    => 'HP ETHERNET MULTI-ENVIRONMENT',
            'last_fusioninventory_update' => $this->update_time
         ],
         'networkport' => [],
         'cartridge'   => [
            '63' => 60, // toner black
            '71' => 40, // toner cyan
            '79' => 80, //toner yelllow
            '75' => 100 // toner magenta
         ],
         'itemtype'    => 'Printer'
      ];
      $a_inventory['Printer'] = [
         'name'               => 'ARC12-B09-N',
         'id'                 => 54,
         'serial'             => 'VRG5XUT5',
         'manufacturers_id'   => 10,
         'locations_id'       => 102,
         'printermodels_id'   => 15,
         'memory_size'        => 64,
         'is_dynamic'         => 1,
         'have_ethernet'      => 1
      ];
      $a_inventory['pagecounters'] = [
         'pages_total'        => 15134,
         'pages_n_b'          => 10007,
         'pages_color'        => 5127,
         'pages_recto_verso'  => 0,
         'pages_total_copy'   => 0,
         'scanned'            => 0,
         'pages_total_print'  => 0,
         'pages_n_b_print'    => 0,
         'pages_color_print'  => 0,
         'pages_n_b_copy'     => 0,
         'pages_color_copy'   => 0,
         'pages_total_fax'    => 0

      ];

      $pfiPrinterLib = new PluginFusioninventoryInventoryPrinterLib();
      $printer = new Printer();

      $this->items_id = $printer->add([
         'serial'      => 'VRG5XUT5',
         'entities_id' => 0
      ]);

      $this->assertGreaterThan(0, $this->items_id);

      $pfiPrinterLib->updatePrinter($a_inventory, $this->items_id, 1);

      // To be sure not have 2 same information
      $pfiPrinterLib->updatePrinter($a_inventory, $this->items_id, 0);

   }


   /**
    * @test
    */
   public function PrinterGeneral() {

      $printer = new Printer();
      $printer->getFromDBByCrit(['name' => 'ARC12-B09-N']);
      $printerId = $printer->fields['id'];
      unset($printer->fields['id']);
      unset($printer->fields['date_mod']);
      unset($printer->fields['date_creation']);
      $a_reference = [
         'name'                 => 'ARC12-B09-N',
         'serial'               => 'VRG5XUT5',
         'otherserial'          => null,
         'manufacturers_id'     => 10,
         'locations_id'         => 102,
         'printermodels_id'     => 15,
         'memory_size'          => 64,
         'entities_id'          => 0,
         'is_recursive'         => 0,
         'contact'              => null,
         'contact_num'          => null,
         'users_id_tech'        => 0,
         'groups_id_tech'       => 0,
         'have_serial'          => 0,
         'have_parallel'        => 0,
         'have_usb'             => 0,
         'have_wifi'            => 0,
         'have_ethernet'        => 1,
         'comment'              => null,
         'networks_id'          => 0,
         'printertypes_id'      => 0,
         'is_global'            => 0,
         'is_deleted'           => 0,
         'is_template'          => 0,
         'template_name'        => null,
         'init_pages_counter'   => 0,
         'last_pages_counter'   => 15134,
         'users_id'             => 0,
         'groups_id'            => 0,
         'states_id'            => 0,
         'ticket_tco'           => '0.0000',
         'is_dynamic'           => 1,
      ];

      $this->assertEquals($a_reference, $printer->fields);

      //Check if no log has been added for the counter's update
      $nb = countElementsInTable('glpi_logs',
                                 ['itemtype'         => 'Printer',
                                  'items_id'         => $printerId,
                                  'linked_action'    => 0,
                                  'id_search_option' => 12
                                  ]);
      $this->assertEquals($nb, 0);

   }


   /**
    * @test
    */
   public function PrinterSnmpExtension() {

      $pfPrinter = new PluginFusioninventoryPrinter();
      $printer = new Printer();
      $printer->getFromDBByCrit(['name' => 'ARC12-B09-N']);

      $a_printer = current($pfPrinter->find(['printers_id' => $printer->fields['id']], [], 1));
      unset($a_printer['id']);
      unset($a_printer['last_fusioninventory_update']);
      $a_reference = [
         'printers_id'                                  => $printer->fields['id'],
         'sysdescr'                                     => 'HP ETHERNET MULTI-ENVIRONMENT',
         'plugin_fusioninventory_configsecurities_id'   => 0,
         'frequence_days'                               => 1,
         'serialized_inventory'                         => null
      ];

      $this->assertEquals($a_reference, $a_printer);

   }


   /**
    * @test
    */
   public function PrinterPageCounter() {

      $pfPrinterLog = new PluginFusioninventoryPrinterLog();
      $printer = new Printer();
      $printer->getFromDBByCrit(['name' => 'ARC12-B09-N']);

      $a_pages = $pfPrinterLog->find(['printers_id' => $printer->fields['id']]);

      $this->assertEquals(1, count($a_pages), print_r($a_pages, true));

   }


   /**
    * @test
    */
   public function PrinterCartridgeBlack() {

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
      $printer = new Printer();
      $printer->getFromDBByCrit(['name' => 'ARC12-B09-N']);

      $a_cartridge = $pfPrinterCartridge->find(
            ['printers_id'                        => $printer->fields['id'],
             'plugin_fusioninventory_mappings_id' => 63,
             'state'                              => 60]);

      $this->assertEquals(1, count($a_cartridge));
   }


   /**
    * @test
    */
   public function PrinterCartridgeCyan() {

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
      $printer = new Printer();
      $printer->getFromDBByCrit(['name' => 'ARC12-B09-N']);

      $a_cartridge = $pfPrinterCartridge->find(
            ['printers_id'                        => $printer->fields['id'],
             'plugin_fusioninventory_mappings_id' => 71,
             'state'                              => 40]);

      $this->assertEquals(1, count($a_cartridge));
   }


   /**
    * @test
    */
   public function PrinterCartridgeYellow() {

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
      $printer = new Printer();
      $printer->getFromDBByCrit(['name' => 'ARC12-B09-N']);

      $a_cartridge = $pfPrinterCartridge->find(
            ['printers_id'                        => $printer->fields['id'],
             'plugin_fusioninventory_mappings_id' => 79,
             'state'                              => 80]);

      $this->assertEquals(1, count($a_cartridge));
   }


   /**
    * @test
    */
   public function PrinterCartridgeMagenta() {

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
      $printer = new Printer();
      $printer->getFromDBByCrit(['name' => 'ARC12-B09-N']);

      $a_cartridge = $pfPrinterCartridge->find(
            ['printers_id'                        => $printer->fields['id'],
             'plugin_fusioninventory_mappings_id' => 75,
             'state'                              => 100]);

      $this->assertEquals(1, count($a_cartridge));
   }


   /**
    * @test
    */
   public function PrinterAllCartridges() {

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();

      $a_cartridge = $pfPrinterCartridge->find();

      $this->assertEquals(4, count($a_cartridge));
   }


   /**
    * @test
    */
   public function NewPrinterFromNetdiscovery() {

      $pfCNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $networkName = new NetworkName();
      $iPAddress = new IPAddress();

      $_SESSION['SOURCE_XMLDEVICE'] = [
         'AUTHSNMP'     => '1',
         'DESCRIPTION'  => 'Photosmart D7200 series',
         'ENTITY'       => '0',
         'FIRMWARE'     => '',
         'IP'           => '192.168.20.100',
         'MAC'          => '00:21:5a:0b:bb:c4',
         'MANUFACTURER' => 'Hewlett-Packard',
         'MODEL'        => '',
         'MODELSNMP'    => 'Printer0093',
         'NETBIOSNAME'  => 'HP00215A0BBBC4',
         'SERIAL'       => 'MY89AQG0V9050N',
         'SNMPHOSTNAME' => 'HP0BBBC4',
         'TYPE'         => 'PRINTER'
      ];

      $printer = new Printer();
      $printers_id = $printer->add(['serial' => 'MY89AQG0V9050N',
         'entities_id' => 0]);
      $printer->getFromDB($printers_id);
      $pfCNetworkDiscovery->importDevice($printer);

      $printer->getFromDB($printers_id);
      $this->assertEquals('HP0BBBC4', $printer->fields['name'], 'Name must be updated');

      $a_printerextends = getAllDataFromTable('glpi_plugin_fusioninventory_printers',
         ['printers_id' => $printers_id]);

      $this->assertEquals('1', count($a_printerextends),
         'May have one printer extend line for this printer');

      $a_printerextend = current($a_printerextends);
      $this->assertEquals('1', $a_printerextend['plugin_fusioninventory_configsecurities_id'],
         'SNMPauth may be with id 1');
      $this->assertEquals('Photosmart D7200 series', $a_printerextend['sysdescr'],
         'Sysdescr not updated correctly');

      // Check mac
      $networkPort = new NetworkPort();
      $a_ports = $networkPort->find(['itemtype' => 'Printer', 'items_id' => $printers_id]);
      $this->assertEquals('1', count($a_ports),
         'May have one network port');
      $a_port = current($a_ports);
      $this->assertEquals('00:21:5a:0b:bb:c4', $a_port['mac'],
         'Mac address');

      // check ip
      $a_networknames = $networkName->find(
            ['itemtype' => 'NetworkPort',
             'items_id' => $a_port['id']]);
      $this->assertEquals('1', count($a_networknames),
         'May have one networkname');
      $a_networkname = current($a_networknames);
      $a_ipaddresses = $iPAddress->find(
            ['itemtype' => 'NetworkName',
             'items_id' => $a_networkname['id']]);
      $this->assertEquals('1', count($a_ipaddresses),
         'May have one IP address');
      $a_ipaddress = current($a_ipaddresses);
      $this->assertEquals('192.168.20.100', $a_ipaddress['name'],
         'IP address');
   }


   /**
    * @test
    */
   public function updatePrinterFromNetdiscovery() {
      global $DB;

      $pfCNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $networkName = new NetworkName();
      $iPAddress = new IPAddress();

      $_SESSION["glpiID"] = 0;

      $_SESSION['SOURCE_XMLDEVICE'] = [
         'AUTHSNMP'     => '1',
         'DESCRIPTION'  => 'Photosmart D7200 series',
         'ENTITY'       => '0',
         'FIRMWARE'     => '',
         'IP'           => '192.168.20.102',
         'MAC'          => '00:21:5a:0b:bb:c4',
         'MANUFACTURER' => 'Hewlett-Packard',
         'MODEL'        => '',
         'MODELSNMP'    => 'Printer0093',
         'NETBIOSNAME'  => 'HP00215A0BBBC4',
         'SERIAL'       => 'MY89AQG0V9050N',
         'SNMPHOSTNAME' => 'HP0BBBC4new',
         'TYPE'         => 'PRINTER'
      ];

      $printer = new Printer();
      $printer->getFromDBByCrit(['serial' => 'MY89AQG0V9050N']);
      $this->assertArrayHasKey('id', $printer->fields);

      // delete locks
      $DB->query('DELETE FROM glpi_plugin_fusioninventory_locks');

      $pfCNetworkDiscovery->importDevice($printer);

      $printer = new Printer();
      $printer->getFromDBByCrit(['serial' => 'MY89AQG0V9050N']);
      $this->assertArrayHasKey('id', $printer->fields);

      $this->assertEquals('HP0BBBC4new', $printer->fields['name'], 'Name must be updated');

      $a_printerextends = getAllDataFromTable('glpi_plugin_fusioninventory_printers',
         ['printers_id' => $printer->fields['id']]);

      $this->assertEquals('1', count($a_printerextends),
         'May have one printer extend line for this printer');

      $a_printerextend = current($a_printerextends);
      $this->assertEquals('1', $a_printerextend['plugin_fusioninventory_configsecurities_id'],
         'SNMPauth may be with id 1');
      $this->assertEquals('Photosmart D7200 series', $a_printerextend['sysdescr'],
         'Sysdescr not updated correctly');

      // Check mac
      $networkPort = new NetworkPort();
      $a_ports = $networkPort->find(['itemtype' => 'Printer', 'items_id' => $printer->fields['id']]);
      $this->assertEquals('1', count($a_ports),
         'May have one network port');
      $a_port = current($a_ports);
      $this->assertEquals('00:21:5a:0b:bb:c4', $a_port['mac'],
         'Mac address');

      // check ip
      $a_networknames = $networkName->find(
            ['itemtype' => 'NetworkPort',
             'items_id' => $a_port['id']]);
      $this->assertEquals('1', count($a_networknames),
         'May have one networkname');
      $a_networkname = current($a_networknames);
      $a_ipaddresses = $iPAddress->find(
            ['itemtype' => 'NetworkName',
             'items_id' => $a_networkname['id']]);
      $this->assertEquals('1', count($a_ipaddresses),
         'May have one IP address');
      $a_ipaddress = current($a_ipaddresses);
      $this->assertEquals('192.168.20.102', $a_ipaddress['name'],
         'IP address');
   }

   /**
    * @test
    */
   public function updatePrinterFromNetdiscoveryToInventory() {

      $pfCNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();

      $_SESSION["plugin_fusioninventory_entity"] = 0;

      $_SESSION['SOURCE_XMLDEVICE'] = [
         'AUTHSNMP'     => '1',
         'DESCRIPTION'  => 'SHARP MX-5140N',
         'ENTITY'       => '0',
         'FIRMWARE'     => '',
         'IP'           => '10.120.80.61',
         'IPS'          => ['IP' => '10.120.80.61', 'IP' => '127.0.0.1'],
         'MAC'          => '24:26:42:1e:5a:90',
         'MANUFACTURER' => 'Sharp',
         'MODEL'        => '',
         'NETBIOSNAME'  => 'SHARP MX-5140N',
         'SERIAL'       => '8512418234',
         'SNMPHOSTNAME' => 'SHARP MX-5140N',
         'TYPE'         => 'PRINTER'
      ];

      //First: discover the device
      $printer     = new Printer();
      $printers_id = $printer->add([
         'serial'      => '8512418234',
         'entities_id' => 0
      ]);
      $printer->getFromDB($printers_id);
      $pfCNetworkDiscovery->importDevice($printer);

      $this->assertGreaterThan(0, $printer->getFromDBByCrit(['serial' => '8512418234']));

      $this->assertEquals('SHARP MX-5140N', $printer->fields['name'], 'Name must be updated');

      // Check mac
      $networkPort = new NetworkPort();
      $a_ports = $networkPort->find(['itemtype' => 'Printer', 'items_id' => $printers_id]);
      $this->assertEquals('1', count($a_ports),
         'May have one network port');
      $a_port = current($a_ports);
      $this->assertEquals('24:26:42:1e:5a:90', $a_port['mac'], 'Mac address');

      //Logical number shoud be 0
      $this->assertEquals(0, $a_port['logical_number'], 'Logical number equals 0');

      $a_inventory = [
         'PluginFusioninventoryPrinter' => [
            'sysdescr'                    => 'SHARP MX-5140N',
            'last_fusioninventory_update' => $_SESSION['glpi_currenttime'],

         ],
         'networkport'  => [
            [
               'name'           => 'Ethernet',
               'logical_number' => 1,
               'mac'            => '24:26:42:1e:5a:90',
               'ip'             => '10.120.80.61'
            ]
         ],
         'pagecounters' => [],
         'cartridge'    => [],
         'itemtype'    => 'Printer'
      ];

      $a_inventory['Printer'] = [
         'name'               => 'SHARP MX-5140N',
         'id'                 => $printers_id,
         'serial'             => '8512418234',
         'memory_size'        => 64,
         'is_dynamic'         => 1,
         'have_ethernet'      => 1
      ];

      $pfCNetworkInventory = new PluginFusioninventoryCommunicationNetworkInventory();
      $pfCNetworkInventory->importDevice('Printer', $printers_id, $a_inventory, 0);

      $a_ports = $networkPort->find(['itemtype' => 'Printer', 'items_id' => $printers_id]);
      $this->assertEquals('1', count($a_ports), 'Should have only one port');

      $a_port = current($a_ports);
      //Logical number shoud be 0
      $this->assertEquals(1, $a_port['logical_number'], 'Logical number changed to 1');
      $this->assertEquals('Ethernet', $a_port['name'], 'Name has changed');
   }
}
