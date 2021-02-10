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

class NetworkEquipmentLLDPTest extends TestCase {

   function setUp(): void {
      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      // Delete all network equipments
      $networkEquipment = new NetworkEquipment();
      $items = $networkEquipment->find();
      foreach ($items as $item) {
         $networkEquipment->delete(['id' => $item['id']], true);
      }

      // Delete all unmanaged items
      $pfUnmanaged = new PluginFusioninventoryUnmanaged();
      $items = $pfUnmanaged->find();
      foreach ($items as $item) {
         $pfUnmanaged->delete(['id' => $item['id']], true);
      }

   }

   // Cases of LLDP informations

   /*
    * Nortel
    */

   /*
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFNUMBER>22</IFNUMBER>
              <SYSMAC>00:24:b5:bd:c8:01</SYSMAC>
            </CONNECTION>
          </CONNECTIONS>
   */

   /*
    * Cisco
    */

   /*
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>GigabitEthernet0/10</IFDESCR>
              <IP>192.168.200.124</IP>
            </CONNECTION>
          </CONNECTIONS>
   */

   /*
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>ge-0/0/1.0</IFDESCR>
              <IFNUMBER>504</IFNUMBER>
              <SYSDESCR>Juniper Networks, Inc. ex2200-24t-4g , version 10.1R1.8 Build date: 2010-02-12 16:59:31 UTC </SYSDESCR>
              <SYSMAC>2c:6b:f5:98:f9:70</SYSMAC>
              <SYSNAME>juniperswitch3</SYSNAME>
            </CONNECTION>
          </CONNECTIONS>
   */


   /*
    * Procurve
    */

   /*
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>28</IFDESCR>
              <IP>10.226.164.55</IP>
            </CONNECTION>
          </CONNECTIONS>
   */

   /*
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>48</IFDESCR>
              <IP>172.16.100.252</IP>
              <MODEL>ProCurve J9148A 2910al-48G-PoE Switch, revision W.14.49, ROM W.14.04 (/sw/code/build/sbm(t4a))</MODEL>
              <SYSDESCR>ProCurve J9148A 2910al-48G-PoE Switch, revision W.14.49, ROM W.14.04 (/sw/code/build/sbm(t4a))</SYSDESCR>
              <SYSNAME>0x78acc0146cc0</SYSNAME>
            </CONNECTION>
          </CONNECTIONS>
   */


   /**
    * @test
    */
   public function NortelSwitch() {

      $a_lldp = [
          'ifdescr'        => '',
          'logical_number' => 22,
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '',
          'mac'            => '00:24:b5:bd:c8:01',
          'name'           => ''
      ];

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add([
          'name'        => 'nortel',
          'entities_id' => 0
      ]);

      $networkports_id = $networkport->add([
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ]);
      $this->assertNotFalse($networkports_id);

      // Another switch
      $networkequipments_other_id = $networkEquipment->add([
          'name'        => 'otherswitch',
          'entities_id' => 0
      ]);

      $networkports_other_id = $networkport->add([
          'itemtype'       => 'NetworkEquipment',
          'items_id'       => $networkequipments_other_id,
          'entities_id'    => 0,
          'mac'            => '00:24:b5:bd:c8:01',
          'logical_number' => 22
      ]);
      $this->assertNotFalse($networkports_other_id);

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDataFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDataFromTable('glpi_networkports');

      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, true).')');

      $a_ref = [
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkports_other_id
      ];

      $portLink = current($a_portslinks);
      unset($portLink['id']);
      $this->assertEquals($a_ref,
                          $portLink,
                          'Link port');

   }


   /**
    * @test
    */
   public function NortelUnmanaged() {

      $a_lldp = [
          'ifdescr'        => '',
          'logical_number' => 22,
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '',
          'mac'            => '00:24:b5:bd:c8:01',
          'name'           => ''
      ];

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();
      $pfUnmanaged            = new PluginFusioninventoryUnmanaged();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add([
          'name'        => 'nortel',
          'entities_id' => 0
      ]);

      $networkports_id = $networkport->add([
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ]);

      // Unmanaged
      $unmanageds_id = $pfUnmanaged->add([
          'name'        => 'otherswitch',
          'entities_id' => 0
      ]);

      $networkports_unknown_id = $networkport->add([
          'itemtype'       => 'PluginFusioninventoryUnmanaged',
          'items_id'       => $unmanageds_id,
          'entities_id'    => 0,
          'mac'            => '00:24:b5:bd:c8:01',
          'logical_number' => 22,
      ]);

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDataFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDataFromTable('glpi_networkports');

      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, true).')');

      $a_ref = [
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkports_unknown_id
      ];

      $portLink = current($a_portslinks);
      unset($portLink['id']);
      $this->assertEquals($a_ref,
                          $portLink,
                          'Link port');

   }


   /**
    * @test
    */
   public function NortelNodevice() {

      $a_lldp = [
          'ifdescr'        => '',
          'logical_number' => 22,
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '',
          'mac'            => '00:24:b5:bd:c8:01',
          'name'           => ''
      ];

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkPort            = new NetworkPort();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add([
          'name'        => 'nortel',
          'entities_id' => 0
      ]);

      $networkports_id = $networkPort->add([
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ]);

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDataFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDataFromTable('glpi_networkports');
      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, true).')');

      $networkPort = new NetworkPort();
      $networkPort->getFromDBByCrit(['mac' => '00:24:b5:bd:c8:01']);

      $a_ref = [
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkPort->fields['id']
      ];

      $portLink = current($a_portslinks);
      unset($portLink['id']);
      $this->assertEquals($a_ref,
                          $portLink,
                          'Link port');

   }


   /**
    * @test
    */
   public function Cisco1Switch() {

      $a_lldp = [
          'ifdescr'        => 'GigabitEthernet0/10',
          'logical_number' => '',
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '192.168.200.124',
          'mac'            => '',
          'name'           => ''
      ];

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();
      $networkName            = new NetworkName();
      $iPAddress              = new IPAddress();
      $pfNetworkPort          = new PluginFusioninventoryNetworkPort();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add([
          'name'        => 'cisco1',
          'entities_id' => 0
      ]);

      $networkports_id = $networkport->add([
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ]);

      // Another switch
      $networkequipments_other_id = $networkEquipment->add([
          'name'        => 'otherswitch',
          'entities_id' => 0
      ]);

      // Management port
      $managementports_id = $networkport->add([
          'itemtype'          => 'NetworkEquipment',
          'instantiation_type'=> 'NetworkPortAggregate',
          'items_id'          => $networkequipments_other_id,
          'entities_id'       => 0
      ]);
      $networknames_id = $networkName->add([
          'entities_id' => 0,
          'itemtype'    => 'NetworkPort',
          'items_id'    => $managementports_id
      ]);
      $iPAddress->add([
          'entities_id' => 0,
          'itemtype' => 'NetworkName',
          'items_id' => $networknames_id,
          'name' => '192.168.200.124'
      ]);

      // Port GigabitEthernet0/10
      $networkports_other_id = $networkport->add([
          'itemtype'       => 'NetworkEquipment',
          'items_id'       => $networkequipments_other_id,
          'entities_id'    => 0,
          'mac'            => '00:24:b5:bd:c8:01',
          'logical_number' => 22
      ]);
      $pfNetworkPort->add([
          'networkports_id' => $networkports_other_id,
          'ifdescr' => 'GigabitEthernet0/10'
      ]);

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDataFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDataFromTable('glpi_networkports');

      $this->assertEquals(3,
                          count($a_networkports),
                          'May have 3 network ports ('.print_r($a_networkports, true).')');

      $a_ref = [
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkports_other_id
      ];

      $portLink = current($a_portslinks);
      unset($portLink['id']);
      $this->assertEquals($a_ref,
                          $portLink,
                          'Link port');

   }


   /*
    * @test
    * It find unknown device, but may add the port with this ifdescr
    */
   public function Cisco1Unmanaged() {

      $a_lldp = [
          'ifdescr'        => 'GigabitEthernet0/10',
          'logical_number' => '',
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '192.168.200.124',
          'mac'            => '',
          'name'           => ''
      ];

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();
      $networkName            = new NetworkName();
      $iPAddress              = new IPAddress();
      $pfUnmanaged            = new PluginFusioninventoryUnmanaged();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add([
          'name'        => 'cisco1',
          'entities_id' => 0
      ]);

      $networkports_id = $networkport->add([
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ]);

      // Unmanaged
      $unmanageds_id = $pfUnmanaged->add([
          'name'        => 'otherswitch',
          'entities_id' => 0
      ]);

      $networkports_unknown_id = $networkport->add([
          'itemtype'       => 'PluginFusioninventoryUnmanaged',
          'items_id'       => $unmanageds_id,
          'entities_id'    => 0
      ]);

      $networknames_id = $networkName->add([
          'entities_id' => 0,
          'itemtype'    => 'NetworkPort',
          'items_id'    => $networkports_unknown_id
      ]);
      $iPAddress->add([
          'entities_id' => 0,
          'itemtype' => 'NetworkName',
          'items_id' => $networknames_id,
          'name' => '192.168.200.124'
      ]);

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDataFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDataFromTable('glpi_networkports');

      $this->assertEquals(3,
                          count($a_networkports),
                          'May have 3 network ports ('.print_r($a_networkports, true).')');

      $a_unknowns = getAllDataFromTable('glpi_plugin_fusioninventory_unmanageds');

      $this->assertEquals(1,
                          count($a_unknowns),
                          'May have only one unknown device ('.print_r($a_unknowns, true).')');

      $a_networkport_ref = [
          'items_id'           => $unmanageds_id,
          'itemtype'           => 'PluginFusioninventoryUnmanaged',
          'entities_id'        => 0,
          'is_recursive'       => 0,
          'logical_number'     => 0,
          'name'               => 'GigabitEthernet0/10',
          'instantiation_type' => 'NetworkPortEthernet',
          'mac'                => null,
          'comment'            => null,
          'is_deleted'         => 0,
          'is_dynamic'         => 0

      ];
      $networkport = new NetworkPort();
      $networkport->getFromDBByCrit(['name' => 'GigabitEthernet0/10']);
      unset($networkport->fields['id']);
      $this->assertEquals($a_networkport_ref,
                          $networkport->fields,
                          'New unknown port created');

      $a_ref = [
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => 3
      ];

      $portLink = current($a_portslinks);
      unset($portLink['id']);
      $this->assertEquals($a_ref,
                          $portLink,
                          'Link port');
   }


   /**
    * @test
    */
   public function Cisco1Nodevice() {

      $a_lldp = [
          'ifdescr'        => 'GigabitEthernet0/10',
          'logical_number' => '',
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '192.168.200.124',
          'mac'            => '',
          'name'           => ''
      ];

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkPort            = new NetworkPort();

      // Cisco switch
      $networkequipments_id = $networkEquipment->add([
          'name'        => 'cisco',
          'entities_id' => 0
      ]);

      $networkports_id = $networkPort->add([
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ]);
      $this->assertNotFalse($networkports_id);

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDataFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDataFromTable('glpi_networkports');

      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, true).')');

      $networkPort = new NetworkPort();
      $networkPort->getFromDBByCrit(['name' => 'GigabitEthernet0/10']);

      $a_ref = [
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkPort->fields['id']
      ];

      $portLink = current($a_portslinks);
      unset($portLink['id']);
      $this->assertEquals($a_ref,
                          $portLink,
                          'Link port');

   }


   /**
    * @test
    */
   public function Cisco2Switch() {

      $a_lldp = [
          'ifdescr'        => 'ge-0/0/1.0',
          'logical_number' => '504',
          'sysdescr'       => 'Juniper Networks, Inc. ex2200-24t-4g , version 10.1R1.8 Build date: 2010-02-12 16:59:31 UTC ',
          'model'          => '',
          'ip'             => '',
          'mac'            => '2c:6b:f5:98:f9:70',
          'name'           => 'juniperswitch3'
      ];

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();
      $pfNetworkPort          = new PluginFusioninventoryNetworkPort();

      // Cisco switch
      $networkequipments_id = $networkEquipment->add([
          'name'        => 'cisco2',
          'entities_id' => 0
      ]);

      $networkports_id = $networkport->add([
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ]);

      // Another switch
      $networkequipments_other_id = $networkEquipment->add([
          'name'        => 'juniperswitch3',
          'entities_id' => 0
      ]);

      // Port ge-0/0/1.0
      $networkports_other_id = $networkport->add([
          'itemtype'       => 'NetworkEquipment',
          'items_id'       => $networkequipments_other_id,
          'entities_id'    => 0,
          'mac'            => '2c:6b:f5:98:f9:70',
          'logical_number' => 504
      ]);
      $pfNetworkPort->add([
          'networkports_id' => $networkports_other_id,
          'ifdescr' => 'ge-0/0/1.0'
      ]);

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDataFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDataFromTable('glpi_networkports');

      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, true).')');

      $a_ref = [
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkports_other_id
      ];

      $portLink = current($a_portslinks);
      unset($portLink['id']);
      $this->assertEquals($a_ref,
                          $portLink,
                          'Link port');
   }


   /**
    * @test
    */
   public function SwitchLldpImport() {

      $xml_source = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <TYPE>NETWORKING</TYPE>
        <MANUFACTURER>Hewlett-Packard</MANUFACTURER>
        <MODEL>J9085A</MODEL>
        <DESCRIPTION>ProCurve J9085A</DESCRIPTION>
        <NAME>FR-SW01</NAME>
        <LOCATION>BAT A - Niv 3</LOCATION>
        <CONTACT>Admin</CONTACT>
        <SERIAL>CN536H7J</SERIAL>
        <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
        <UPTIME>8 days, 01:48:57.95</UPTIME>
        <MAC>b4:39:d6:3a:7f:00</MAC>
        <ID>0</ID>
        <IPS>
          <IP>192.168.1.56</IP>
          <IP>192.168.10.56</IP>
        </IPS>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>ge-0/0/1.0</IFDESCR>
              <IFNUMBER>504</IFNUMBER>
              <SYSDESCR>Juniper Networks, Inc. ex2200-24t-4g , version 10.1R1.8 Build date: 2010-02-12 16:59:31 UTC </SYSDESCR>
              <SYSMAC>2c:6b:f5:98:f9:70</SYSMAC>
              <SYSNAME>juniperswitch3</SYSNAME>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>3</IFDESCR>
          <IFNAME>3</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFSPEED>1000000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFPORTDUPLEX>2</IFPORTDUPLEX>
          <IFTYPE>6</IFTYPE>
          <MAC>b4:39:d6:3b:22:bd</MAC>
          <VLANS>
            <VLAN>
              <NAME>VLAN160</NAME>
              <NUMBER>160</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>3.0</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>foo</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $pfCommunication         = new PluginFusioninventoryCommunication();
      $networkEquipment        = new NetworkEquipment();
      $networkPort             = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();
      $pfNetworkPort           = new PluginFusioninventoryNetworkPort();

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'juniperswitch3',
      ]);
      $this->assertNotFalse($networkEquipments_id);

      // Add management port
      // 2c:6b:f5:98:f9:70
      $mngtports_id = $networkPort->add([
         'mac'                => '2c:6b:f5:98:f9:70',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         'instantiation_type' => 'NetworkPortAggregate',
         'name'               => 'general',
      ]);
      $this->assertNotFalse($mngtports_id);

      $ports_id = $networkPort->add([
         'mac'                => '2c:6b:f5:98:f9:71',
         'name'               => 'ge-0/0/1.0',
         'logical_number'     => '504',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);

      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => 'ge-0/0/1.0',
      ]);
      $this->assertNotFalse($id);

      // Import the switch into GLPI
      $pfCommunication->handleOCSCommunication('', $xml_source, 'glpi');

      // get port of Procurve
      $ports = $networkPort->find(['mac' => 'b4:39:d6:3b:22:bd'], [], 1);
      $this->assertCount(1, $ports);
      $procurvePort = current($ports);
      $linkPort = $networkPort_NetworkPort->getFromDBForNetworkPort($procurvePort['id']);
      $this->assertNotFalse($linkPort);
   }


   /**
    * @test
    *
    * case 1 : IP on management port of the switch
    */
   public function SwitchLLDPImport_ifdescr_ip_case1() {

      $xml_source = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <TYPE>NETWORKING</TYPE>
        <MANUFACTURER>Hewlett-Packard</MANUFACTURER>
        <MODEL>J9085A</MODEL>
        <DESCRIPTION>ProCurve J9085A</DESCRIPTION>
        <NAME>FR-SW01</NAME>
        <LOCATION>BAT A - Niv 3</LOCATION>
        <CONTACT>Admin</CONTACT>
        <SERIAL>CN536H7J</SERIAL>
        <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
        <UPTIME>8 days, 01:48:57.95</UPTIME>
        <MAC>b4:39:d6:3a:7f:00</MAC>
        <ID>0</ID>
        <IPS>
          <IP>192.168.1.56</IP>
          <IP>192.168.10.56</IP>
        </IPS>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>28</IFDESCR>
              <IP>10.226.164.55</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>3</IFDESCR>
          <IFNAME>3</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFSPEED>1000000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFPORTDUPLEX>2</IFPORTDUPLEX>
          <IFTYPE>6</IFTYPE>
          <MAC>b4:39:d6:3b:22:bd</MAC>
          <VLANS>
            <VLAN>
              <NAME>VLAN160</NAME>
              <NUMBER>160</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>3.0</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>foo</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $pfCommunication         = new PluginFusioninventoryCommunication();
      $networkEquipment        = new NetworkEquipment();
      $networkPort             = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();
      $pfNetworkPort           = new PluginFusioninventoryNetworkPort();

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'sw10',
      ]);
      $this->assertNotFalse($networkEquipments_id);

      // Add management port
      $mngtports_id = $networkPort->add([
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         'instantiation_type' => 'NetworkPortAggregate',
         'name'               => 'general',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '10.226.164.55'
         ],

      ]);
      $this->assertNotFalse($mngtports_id);

      // Add a port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'mac'                => '00:6b:03:98:f9:70',
         'name'               => 'port27',
         'logical_number'     => '28',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '27',
      ]);
      $this->assertNotFalse($id);

      // Add the second port right
      $ports_id = $networkPort->add([
         'mac'                => '00:6b:03:98:f9:71',
         'name'               => 'port28',
         'logical_number'     => '30',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '28',
      ]);
      $this->assertNotFalse($id);

      // Add another port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'mac'                => '00:6b:03:98:f9:72',
         'name'               => 'port29',
         'logical_number'     => '29',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '29',
      ]);
      $this->assertNotFalse($id);

      // Import the switch into GLPI
      $pfCommunication->handleOCSCommunication('', $xml_source, 'glpi');

      // get port of Procurve
      $ports = $networkPort->find(['name' => 'port28'], [], 1);
      $this->assertCount(1, $ports);
      $procurvePort = current($ports);
      $linkPort = $networkPort_NetworkPort->getFromDBForNetworkPort($procurvePort['id']);
      $this->assertNotFalse($linkPort);

   }


   /**
    * @test
    *
    * case 2 : IP on the port of the switch
    */
   public function SwitchLLDPImport_ifdescr_ip_case2() {

      $xml_source = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <TYPE>NETWORKING</TYPE>
        <MANUFACTURER>Hewlett-Packard</MANUFACTURER>
        <MODEL>J9085A</MODEL>
        <DESCRIPTION>ProCurve J9085A</DESCRIPTION>
        <NAME>FR-SW01</NAME>
        <LOCATION>BAT A - Niv 3</LOCATION>
        <CONTACT>Admin</CONTACT>
        <SERIAL>CN536H7J</SERIAL>
        <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
        <UPTIME>8 days, 01:48:57.95</UPTIME>
        <MAC>b4:39:d6:3a:7f:00</MAC>
        <ID>0</ID>
        <IPS>
          <IP>192.168.1.56</IP>
          <IP>192.168.10.56</IP>
        </IPS>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>28</IFDESCR>
              <IP>10.226.164.55</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>3</IFDESCR>
          <IFNAME>3</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFSPEED>1000000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFPORTDUPLEX>2</IFPORTDUPLEX>
          <IFTYPE>6</IFTYPE>
          <MAC>b4:39:d6:3b:22:bd</MAC>
          <VLANS>
            <VLAN>
              <NAME>VLAN160</NAME>
              <NUMBER>160</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>3.0</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>foo</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $pfCommunication         = new PluginFusioninventoryCommunication();
      $networkEquipment        = new NetworkEquipment();
      $networkPort             = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();
      $pfNetworkPort           = new PluginFusioninventoryNetworkPort();

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'sw10',
      ]);
      $this->assertNotFalse($networkEquipments_id);

      // Add a port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'mac'                => '00:6b:03:98:f9:70',
         'name'               => 'port27',
         'logical_number'     => '28',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '10.226.164.55'
         ],
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '27',
      ]);
      $this->assertNotFalse($id);

      // Add the second port right
      $ports_id = $networkPort->add([
         'mac'                => '00:6b:03:98:f9:71',
         'name'               => 'port28',
         'logical_number'     => '30',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '10.226.164.55'
         ],
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '28',
      ]);
      $this->assertNotFalse($id);

      // Add another port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'mac'                => '00:6b:03:98:f9:72',
         'name'               => 'port29',
         'logical_number'     => '31',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '10.226.164.55'
         ],
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '29',
      ]);
      $this->assertNotFalse($id);

      // Import the switch into GLPI
      $pfCommunication->handleOCSCommunication('', $xml_source, 'glpi');

      // get port of Procurve
      $ports = $networkPort->find(['name' => 'port28'], [], 1);
      $this->assertCount(1, $ports);
      $procurvePort = current($ports);
      $linkPort = $networkPort_NetworkPort->getFromDBForNetworkPort($procurvePort['id']);
      $this->assertNotFalse($linkPort);
   }

   /**
    * @test
    *
    * case 1 : mac on management port
    */
   public function SwitchLLDPImport_ifnumber_mac_case1() {

      $xml_source = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <TYPE>NETWORKING</TYPE>
        <MANUFACTURER>Hewlett-Packard</MANUFACTURER>
        <MODEL>J9085A</MODEL>
        <DESCRIPTION>ProCurve J9085A</DESCRIPTION>
        <NAME>FR-SW01</NAME>
        <LOCATION>BAT A - Niv 3</LOCATION>
        <CONTACT>Admin</CONTACT>
        <SERIAL>CN536H7J</SERIAL>
        <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
        <UPTIME>8 days, 01:48:57.95</UPTIME>
        <MAC>b4:39:d6:3a:7f:00</MAC>
        <ID>0</ID>
        <IPS>
          <IP>192.168.1.56</IP>
          <IP>192.168.10.56</IP>
        </IPS>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFNUMBER>21</IFNUMBER>
              <SYSMAC>00:24:b5:bd:c8:01</SYSMAC>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>3</IFDESCR>
          <IFNAME>3</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFSPEED>1000000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFPORTDUPLEX>2</IFPORTDUPLEX>
          <IFTYPE>6</IFTYPE>
          <MAC>b4:39:d6:3b:22:bd</MAC>
          <VLANS>
            <VLAN>
              <NAME>VLAN160</NAME>
              <NUMBER>160</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>3.0</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>foo</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $pfCommunication         = new PluginFusioninventoryCommunication();
      $networkEquipment        = new NetworkEquipment();
      $networkPort             = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();
      $pfNetworkPort           = new PluginFusioninventoryNetworkPort();

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'sw10',
      ]);
      $this->assertNotFalse($networkEquipments_id);

      // Add management port
      $mngtports_id = $networkPort->add([
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         'instantiation_type' => 'NetworkPortAggregate',
         'name'               => 'general',
         'mac'                => '00:24:b5:bd:c8:01',
      ]);
      $this->assertNotFalse($mngtports_id);

      // Add a port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'name'               => 'port20',
         'logical_number'     => '20',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '20',
      ]);
      $this->assertNotFalse($id);

      // Add the second port right
      $ports_id = $networkPort->add([
         'name'               => 'port21',
         'logical_number'     => '21',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '21',
      ]);
      $this->assertNotFalse($id);

      // Add another port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'name'               => 'port22',
         'logical_number'     => '22',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '22',
      ]);
      $this->assertNotFalse($id);

      // Import the switch into GLPI
      $pfCommunication->handleOCSCommunication('', $xml_source, 'glpi');

      // get port of Procurve
      $ports = $networkPort->find(['name' => 'port21'], [], 1);
      $this->assertCount(1, $ports);
      $procurvePort = current($ports);
      $linkPort = $networkPort_NetworkPort->getFromDBForNetworkPort($procurvePort['id']);
      $this->assertNotFalse($linkPort);
   }

   /**
    * @test
    *
    * case 2 : mac on the right port
    */
   public function SwitchLLDPImport_ifnumber_mac_case2() {

      $xml_source = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <TYPE>NETWORKING</TYPE>
        <MANUFACTURER>Hewlett-Packard</MANUFACTURER>
        <MODEL>J9085A</MODEL>
        <DESCRIPTION>ProCurve J9085A</DESCRIPTION>
        <NAME>FR-SW01</NAME>
        <LOCATION>BAT A - Niv 3</LOCATION>
        <CONTACT>Admin</CONTACT>
        <SERIAL>CN536H7J</SERIAL>
        <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
        <UPTIME>8 days, 01:48:57.95</UPTIME>
        <MAC>b4:39:d6:3a:7f:00</MAC>
        <ID>0</ID>
        <IPS>
          <IP>192.168.1.56</IP>
          <IP>192.168.10.56</IP>
        </IPS>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFNUMBER>21</IFNUMBER>
              <SYSMAC>00:24:b5:bd:c8:01</SYSMAC>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>3</IFDESCR>
          <IFNAME>3</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFSPEED>1000000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFPORTDUPLEX>2</IFPORTDUPLEX>
          <IFTYPE>6</IFTYPE>
          <MAC>b4:39:d6:3b:22:bd</MAC>
          <VLANS>
            <VLAN>
              <NAME>VLAN160</NAME>
              <NUMBER>160</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>3.0</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>foo</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $pfCommunication         = new PluginFusioninventoryCommunication();
      $networkEquipment        = new NetworkEquipment();
      $networkPort             = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();
      $pfNetworkPort           = new PluginFusioninventoryNetworkPort();

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'sw10',
      ]);
      $this->assertNotFalse($networkEquipments_id);

      // Add a port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'mac'                => '00:24:b5:bd:c8:00',
         'name'               => 'port20',
         'logical_number'     => '20',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '20',
      ]);
      $this->assertNotFalse($id);

      // Add the second port right
      $ports_id = $networkPort->add([
         'mac'                => '00:24:b5:bd:c8:01',
         'name'               => 'port21',
         'logical_number'     => '21',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '21',
      ]);
      $this->assertNotFalse($id);

      // Add another port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'mac'                => '00:24:b5:bd:c8:02',
         'name'               => 'port22',
         'logical_number'     => '22',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '22',
      ]);
      $this->assertNotFalse($id);

      // Import the switch into GLPI
      $pfCommunication->handleOCSCommunication('', $xml_source, 'glpi');

      // get port of Procurve
      $ports = $networkPort->find(['name' => 'port21'], [], 1);
      $this->assertCount(1, $ports);
      $procurvePort = current($ports);
      $linkPort = $networkPort_NetworkPort->getFromDBForNetworkPort($procurvePort['id']);
      $this->assertNotFalse($linkPort);
   }

   /**
    * @test
    *
    * case 3 : same mac on all ports
    */
   public function SwitchLLDPImport_ifnumber_mac_case3() {

      $xml_source = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <TYPE>NETWORKING</TYPE>
        <MANUFACTURER>Hewlett-Packard</MANUFACTURER>
        <MODEL>J9085A</MODEL>
        <DESCRIPTION>ProCurve J9085A</DESCRIPTION>
        <NAME>FR-SW01</NAME>
        <LOCATION>BAT A - Niv 3</LOCATION>
        <CONTACT>Admin</CONTACT>
        <SERIAL>CN536H7J</SERIAL>
        <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
        <UPTIME>8 days, 01:48:57.95</UPTIME>
        <MAC>b4:39:d6:3a:7f:00</MAC>
        <ID>0</ID>
        <IPS>
          <IP>192.168.1.56</IP>
          <IP>192.168.10.56</IP>
        </IPS>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFNUMBER>21</IFNUMBER>
              <SYSMAC>00:24:b5:bd:c8:01</SYSMAC>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>3</IFDESCR>
          <IFNAME>3</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFSPEED>1000000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFPORTDUPLEX>2</IFPORTDUPLEX>
          <IFTYPE>6</IFTYPE>
          <MAC>b4:39:d6:3b:22:bd</MAC>
          <VLANS>
            <VLAN>
              <NAME>VLAN160</NAME>
              <NUMBER>160</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>3.0</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>foo</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $pfCommunication         = new PluginFusioninventoryCommunication();
      $networkEquipment        = new NetworkEquipment();
      $networkPort             = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();
      $pfNetworkPort           = new PluginFusioninventoryNetworkPort();

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'sw10',
      ]);
      $this->assertNotFalse($networkEquipments_id);

      // Add a port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'mac'                => '00:24:b5:bd:c8:01',
         'name'               => 'port20',
         'logical_number'     => '20',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '20',
      ]);
      $this->assertNotFalse($id);

      // Add the second port right
      $ports_id = $networkPort->add([
         'mac'                => '00:24:b5:bd:c8:01',
         'name'               => 'port21',
         'logical_number'     => '21',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '21',
      ]);
      $this->assertNotFalse($id);

      // Add another port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'mac'                => '00:24:b5:bd:c8:01',
         'name'               => 'port22',
         'logical_number'     => '22',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '22',
      ]);
      $this->assertNotFalse($id);

      // Import the switch into GLPI
      $pfCommunication->handleOCSCommunication('', $xml_source, 'glpi');

      // get port of Procurve
      $ports = $networkPort->find(['name' => 'port21'], [], 1);
      $this->assertCount(1, $ports);
      $procurvePort = current($ports);
      $linkPort = $networkPort_NetworkPort->getFromDBForNetworkPort($procurvePort['id']);
      $this->assertNotFalse($linkPort);
   }


   /**
    * @test
    */
   public function SwitchLLDPImport_othercase1() {

      $xml_source = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <TYPE>NETWORKING</TYPE>
        <MANUFACTURER>Hewlett-Packard</MANUFACTURER>
        <MODEL>J9085A</MODEL>
        <DESCRIPTION>ProCurve J9085A</DESCRIPTION>
        <NAME>FR-SW01</NAME>
        <LOCATION>BAT A - Niv 3</LOCATION>
        <CONTACT>Admin</CONTACT>
        <SERIAL>CN536H7J</SERIAL>
        <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
        <UPTIME>8 days, 01:48:57.95</UPTIME>
        <MAC>b4:39:d6:3a:7f:00</MAC>
        <ID>0</ID>
        <IPS>
          <IP>192.168.1.56</IP>
          <IP>192.168.10.56</IP>
        </IPS>
      </INFO>
      <PORTS>
        <PORT>
         <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>48</IFDESCR>
              <IP>172.16.100.252</IP>
              <MODEL>ProCurve J9148A 2910al-48G-PoE Switch, revision W.14.49, ROM W.14.04 (/sw/code/build/sbm(t4a))</MODEL>
              <SYSDESCR>ProCurve J9148A 2910al-48G-PoE Switch, revision W.14.49, ROM W.14.04 (/sw/code/build/sbm(t4a))</SYSDESCR>
              <SYSNAME>0x78acc0146cc0</SYSNAME>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>3</IFDESCR>
          <IFNAME>3</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFSPEED>1000000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFPORTDUPLEX>2</IFPORTDUPLEX>
          <IFTYPE>6</IFTYPE>
          <MAC>b4:39:d6:3b:22:bd</MAC>
          <VLANS>
            <VLAN>
              <NAME>VLAN160</NAME>
              <NUMBER>160</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>3.0</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>foo</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $pfCommunication         = new PluginFusioninventoryCommunication();
      $networkEquipment        = new NetworkEquipment();
      $networkPort             = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();
      $pfNetworkPort           = new PluginFusioninventoryNetworkPort();

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'sw001',
      ]);
      $this->assertNotFalse($networkEquipments_id);

      // Add management port
      $mngtports_id = $networkPort->add([
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         'instantiation_type' => 'NetworkPortAggregate',
         'name'               => 'general',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '172.16.100.252'
         ],
      ]);
      $this->assertNotFalse($mngtports_id);

      // Add a port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'name'               => 'port47',
         'logical_number'     => '47',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '47',
      ]);
      $this->assertNotFalse($id);

      // Add the second port right
      $ports_id = $networkPort->add([
         'name'               => 'port48',
         'logical_number'     => '48',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '48',
      ]);
      $this->assertNotFalse($id);

      // Add another port that will not be used, but needed for the test
      $ports_id = $networkPort->add([
         'name'               => 'port49',
         'logical_number'     => '49',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => '49',
      ]);
      $this->assertNotFalse($id);

      // Import the switch into GLPI
      $pfCommunication->handleOCSCommunication('', $xml_source, 'glpi');

      // get port of Procurve
      $ports = $networkPort->find(['name' => 'port48'], [], 1);
      $this->assertCount(1, $ports);
      $procurvePort = current($ports);
      $linkPort = $networkPort_NetworkPort->getFromDBForNetworkPort($procurvePort['id']);
      $this->assertNotFalse($linkPort);
   }

   /*
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>48</IFDESCR>
              <IP>172.16.100.252</IP>
              <MODEL>ProCurve J9148A 2910al-48G-PoE Switch, revision W.14.49, ROM W.14.04 (/sw/code/build/sbm(t4a))</MODEL>
              <SYSDESCR>ProCurve J9148A 2910al-48G-PoE Switch, revision W.14.49, ROM W.14.04 (/sw/code/build/sbm(t4a))</SYSDESCR>
              <SYSNAME>sw001</SYSNAME>
            </CONNECTION>
          </CONNECTIONS>
   */

      /* Scenarii:
       *
       * IP + name + itemtype phone
       * IP + ifdescr in unmanaged
       * MAC unmanaged
       *
       */
}
