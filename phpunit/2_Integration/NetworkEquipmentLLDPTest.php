<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

class NetworkEquipmentLLDPTest extends Common_TestCase {

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
      global $DB;

      $DB->connect();

      self::restore_database();

      $a_lldp = array(
          'ifdescr'        => '',
          'logical_number' => 22,
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '',
          'mac'            => '00:24:b5:bd:c8:01',
          'name'           => ''
      );

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();
      $GLPIlog                = new GLPIlogs();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add(array(
          'name'        => 'nortel',
          'entities_id' => 0
      ));

      $networkports_id = $networkport->add(array(
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ));

      // Another switch
      $networkequipments_other_id = $networkEquipment->add(array(
          'name'        => 'otherswitch',
          'entities_id' => 0
      ));

      $networkports_other_id = $networkport->add(array(
          'itemtype'       => 'NetworkEquipment',
          'items_id'       => $networkequipments_other_id,
          'entities_id'    => 0,
          'mac'            => '00:24:b5:bd:c8:01',
          'logical_number' => 22
      ));

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDatasFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDatasFromTable('glpi_networkports');

      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, TRUE).')');


      $a_ref = array(
          'id'                => 1,
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkports_other_id
      );

      $this->assertEquals($a_ref,
                          current($a_portslinks),
                          'Link port');

   }



   /**
    * @test
    */
   public function NortelUnmanaged() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $a_lldp = array(
          'ifdescr'        => '',
          'logical_number' => 22,
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '',
          'mac'            => '00:24:b5:bd:c8:01',
          'name'           => ''
      );

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();
      $pfUnmanaged            = new PluginFusioninventoryUnmanaged();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add(array(
          'name'        => 'nortel',
          'entities_id' => 0
      ));

      $networkports_id = $networkport->add(array(
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ));

      // Unmanaged
      $unmanageds_id = $pfUnmanaged->add(array(
          'name'        => 'otherswitch',
          'entities_id' => 0
      ));

      $networkports_unknown_id = $networkport->add(array(
          'itemtype'       => 'PluginFusioninventoryUnmanaged',
          'items_id'       => $unmanageds_id,
          'entities_id'    => 0,
          'mac'            => '00:24:b5:bd:c8:01'
      ));

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDatasFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDatasFromTable('glpi_networkports');

      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, TRUE).')');


      $a_ref = array(
          'id'                => 1,
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkports_unknown_id
      );

      $this->assertEquals($a_ref,
                          current($a_portslinks),
                          'Link port');

   }



   /**
    * @test
    */
   public function NortelNodevice() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $a_lldp = array(
          'ifdescr'        => '',
          'logical_number' => 22,
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '',
          'mac'            => '00:24:b5:bd:c8:01',
          'name'           => ''
      );

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add(array(
          'name'        => 'nortel',
          'entities_id' => 0
      ));

      $networkports_id = $networkport->add(array(
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ));


      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDatasFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDatasFromTable('glpi_networkports');

      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, TRUE).')');


      $a_ref = array(
          'id'                => 1,
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => 2
      );

      $this->assertEquals($a_ref,
                          current($a_portslinks),
                          'Link port');

   }



   /**
    * @test
    */
   public function Cisco1Switch() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $a_lldp = array(
          'ifdescr'        => 'GigabitEthernet0/10',
          'logical_number' => '',
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '192.168.200.124',
          'mac'            => '',
          'name'           => ''
      );

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();
      $networkName            = new NetworkName();
      $iPAddress              = new IPAddress();
      $pfNetworkPort          = new PluginFusioninventoryNetworkPort();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add(array(
          'name'        => 'cisco1',
          'entities_id' => 0
      ));

      $networkports_id = $networkport->add(array(
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ));

      // Another switch
      $networkequipments_other_id = $networkEquipment->add(array(
          'name'        => 'otherswitch',
          'entities_id' => 0
      ));

      // Management port
      $managementports_id = $networkport->add(array(
          'itemtype'          => 'NetworkEquipment',
          'instantiation_type'=> 'NetworkPortAggregate',
          'items_id'          => $networkequipments_other_id,
          'entities_id'       => 0
      ));
      $networknames_id = $networkName->add(array(
          'entities_id' => 0,
          'itemtype'    => 'NetworkPort',
          'items_id'    => $managementports_id
      ));
      $iPAddress->add(array(
          'entities_id' => 0,
          'itemtype' => 'NetworkName',
          'items_id' => $networknames_id,
          'name' => '192.168.200.124'
      ));

      // Port GigabitEthernet0/10
      $networkports_other_id = $networkport->add(array(
          'itemtype'       => 'NetworkEquipment',
          'items_id'       => $networkequipments_other_id,
          'entities_id'    => 0,
          'mac'            => '00:24:b5:bd:c8:01',
          'logical_number' => 22
      ));
      $pfNetworkPort->add(array(
          'networkports_id' => $networkports_other_id,
          'ifdescr' => 'GigabitEthernet0/10'
      ));

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDatasFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDatasFromTable('glpi_networkports');

      $this->assertEquals(3,
                          count($a_networkports),
                          'May have 3 network ports ('.print_r($a_networkports, TRUE).')');


      $a_ref = array(
          'id'                => 1,
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkports_other_id
      );

      $this->assertEquals($a_ref,
                          current($a_portslinks),
                          'Link port');

   }



   /*
    * @test
    * It find unknown device, but may add the port with this ifdescr
    */
   public function Cisco1Unmanaged() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $a_lldp = array(
          'ifdescr'        => 'GigabitEthernet0/10',
          'logical_number' => '',
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '192.168.200.124',
          'mac'            => '',
          'name'           => ''
      );

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();
      $networkName            = new NetworkName();
      $iPAddress              = new IPAddress();
      $pfNetworkPort          = new PluginFusioninventoryNetworkPort();
      $pfUnmanaged            = new PluginFusioninventoryUnmanaged();

      // Nortel switch
      $networkequipments_id = $networkEquipment->add(array(
          'name'        => 'cisco1',
          'entities_id' => 0
      ));

      $networkports_id = $networkport->add(array(
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ));

      // Unmanaged
      $unmanageds_id = $pfUnmanaged->add(array(
          'name'        => 'otherswitch',
          'entities_id' => 0
      ));

      $networkports_unknown_id = $networkport->add(array(
          'itemtype'       => 'PluginFusioninventoryUnmanaged',
          'items_id'       => $unmanageds_id,
          'entities_id'    => 0
      ));

      $networknames_id = $networkName->add(array(
          'entities_id' => 0,
          'itemtype'    => 'NetworkPort',
          'items_id'    => $networkports_unknown_id
      ));
      $iPAddress->add(array(
          'entities_id' => 0,
          'itemtype' => 'NetworkName',
          'items_id' => $networknames_id,
          'name' => '192.168.200.124'
      ));


      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDatasFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDatasFromTable('glpi_networkports');

      $this->assertEquals(3,
                          count($a_networkports),
                          'May have 3 network ports ('.print_r($a_networkports, TRUE).')');

      $a_unknowns = getAllDatasFromTable('glpi_plugin_fusioninventory_unmanageds');

      $this->assertEquals(1,
                          count($a_unknowns),
                          'May have only one unknown device ('.print_r($a_unknowns, TRUE).')');


      $a_networkport_ref = array(
          'id'                 => '3',
          'items_id'           => $unmanageds_id,
          'itemtype'           => 'PluginFusioninventoryUnmanaged',
          'entities_id'        => '0',
          'is_recursive'       => '0',
          'logical_number'     => '0',
          'name'               => 'GigabitEthernet0/10',
          'instantiation_type' => 'NetworkPortEthernet',
          'mac'                => NULL,
          'comment'            => NULL,
          'is_deleted'         => '0',
          'is_dynamic'         => '0'

      );
      $networkport->getFromDB(3);
      $this->assertEquals($a_networkport_ref,
                          $networkport->fields,
                          'New unknown port created');


      $a_ref = array(
          'id'                => 1,
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => 3
      );

      $this->assertEquals($a_ref,
                          current($a_portslinks),
                          'Link port');


   }



   /**
    * @test
    */
   public function Cisco1Nodevice() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $a_lldp = array(
          'ifdescr'        => 'GigabitEthernet0/10',
          'logical_number' => '',
          'sysdescr'       => '',
          'model'          => '',
          'ip'             => '192.168.200.124',
          'mac'            => '',
          'name'           => ''
      );

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();

      // Cisco switch
      $networkequipments_id = $networkEquipment->add(array(
          'name'        => 'cisco',
          'entities_id' => 0
      ));

      $networkports_id = $networkport->add(array(
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ));


      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDatasFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDatasFromTable('glpi_networkports');

      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, TRUE).')');

      $a_ref = array(
          'id'                => 1,
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => 2
      );

      $this->assertEquals($a_ref,
                          current($a_portslinks),
                          'Link port');
   }



   /**
    * @test
    */
   public function Cisco2Switch() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $a_lldp = array(
          'ifdescr'        => 'ge-0/0/1.0',
          'logical_number' => '504',
          'sysdescr'       => 'Juniper Networks, Inc. ex2200-24t-4g , version 10.1R1.8 Build date: 2010-02-12 16:59:31 UTC ',
          'model'          => '',
          'ip'             => '',
          'mac'            => '2c:6b:f5:98:f9:70',
          'name'           => 'juniperswitch3'
      );

      $pfINetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment       = new NetworkEquipment();
      $networkport            = new NetworkPort();
      $pfNetworkPort          = new PluginFusioninventoryNetworkPort();

      // Cisco switch
      $networkequipments_id = $networkEquipment->add(array(
          'name'        => 'cisco2',
          'entities_id' => 0
      ));

      $networkports_id = $networkport->add(array(
          'itemtype'    => 'NetworkEquipment',
          'items_id'    => $networkequipments_id,
          'entities_id' => 0
      ));

      // Another switch
      $networkequipments_other_id = $networkEquipment->add(array(
          'name'        => 'juniperswitch3',
          'entities_id' => 0
      ));

      // Port ge-0/0/1.0
      $networkports_other_id = $networkport->add(array(
          'itemtype'       => 'NetworkEquipment',
          'items_id'       => $networkequipments_other_id,
          'entities_id'    => 0,
          'mac'            => '2c:6b:f5:98:f9:70',
          'logical_number' => 504
      ));
      $pfNetworkPort->add(array(
          'networkports_id' => $networkports_other_id,
          'ifdescr' => 'ge-0/0/1.0'
      ));

      $pfINetworkEquipmentLib->importConnectionLLDP($a_lldp, $networkports_id);

      $a_portslinks = getAllDatasFromTable('glpi_networkports_networkports');

      $this->assertEquals(1,
                          count($a_portslinks),
                          'May have 1 connection between 2 network ports');

      $a_networkports = getAllDatasFromTable('glpi_networkports');

      $this->assertEquals(2,
                          count($a_networkports),
                          'May have 2 network ports ('.print_r($a_networkports, TRUE).')');


      $a_ref = array(
          'id'                => 1,
          'networkports_id_1' => $networkports_id,
          'networkports_id_2' => $networkports_other_id
      );

      $this->assertEquals($a_ref,
                          current($a_portslinks),
                          'Link port');

   }


   /**
    * @test
    */
   public function testCisco2Unmanaged() {

      $this->mark_incomplete();

      global $DB;

      $DB->connect();
   }



   /**
    * @test
    */
   public function Cisco2Nodevice() {

      $this->mark_incomplete();

      global $DB;

      $DB->connect();

   }

}
?>
