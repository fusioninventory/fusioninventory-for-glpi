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

class NetworkEquipmentUpdate extends RestoreDatabase_TestCase {

   public $items_id = 0;
   public $datelatupdate = '';


   /**
    * @test
    */
   public function AddNetworkEquipment() {
      global $DB;

      $DB->connect();

      $DB->query("UPDATE `glpi_plugin_fusioninventory_networkporttypes`"
              ." SET `import`='1'"
              ." WHERE `number`='54'");

      $this->datelatupdate = date('Y-m-d H:i:s');

      $a_inventory = array(
          'PluginFusioninventoryNetworkEquipment' => Array(
                  'sysdescr'                    => 'Cisco IOS Software, C2960 Software (C2960-LANBASEK9-M), Version 12.2(50)SE4, RELEASE SOFTWARE (fc1)\nTechnical Support: http://www.cisco.com/techsupport\nCopyright (c) 1986-2010 by Cisco Systems, Inc.\nCompiled Fri 26-Mar-10 09:14 by prod_rel_team',
                  'last_fusioninventory_update' => $this->datelatupdate,
                  'cpu'                         => 5,
                  'memory'                      => 18,
                  'uptime'                      => '157 days, 02:14:44.00'
                ),
          'networkport'       => array(),
          'connection-mac'    => array(),
          'vlans'             => array(),
          'connection-lldp'   => array(),
          'internalport'      => array('192.168.30.67', '192.168.40.67', '192.168.50.67'),
          'itemtype'          => 'NetworkEquipment'
          );
      $a_inventory['NetworkEquipment'] = array(
               'name'               => 'switchr2d2',
               'id'                 => 96,
               'serial'             => 'FOC147UJEU4',
               'manufacturers_id'   => 29,
               'locations_id'       => 3,
               'networkequipmentmodels_id' => 3,
               'networkequipmentfirmwares_id' => 3,
               'memory'             => 18,
               'ram'                => 64,
               'is_dynamic'         => 1,
               'mac'                => '6c:50:4d:39:59:80'
      );

      $a_inventory['networkport'] = array(
          '10001' => array(
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
          ),
          '10002' => array(
              'ifdescr'          => 'FastEthernet0/2',
              'ifinerrors'       => 0,
              'ifinoctets'       => 1953319640,
              'ifinternalstatus' => 1,
              'iflastchange'     => '53.53 seconds',
              'ifmtu'            => 1500,
              'name'             => 'Fa0/2',
              'logical_number'   => 10002,
              'ifouterrors'      => 0,
              'ifoutoctets'      => 554008368,
              'speed'            => 10000000,
              'ifstatus'         => 1,
              'iftype'           => 6,
              'mac'              => '6c:50:4d:39:59:82',
              'trunk'            => 1,
              'ifspeed'          => 10000000
          ),
          '5005' => array(
              'ifdescr'          => 'Port-channel10',
              'ifinerrors'       => 0,
              'ifinoctets'       => 1076823325,
              'ifinternalstatus' => 1,
              'iflastchange'     => '53.53 seconds',
              'ifmtu'            => 1500,
              'name'             => 'Po10',
              'logical_number'   => 5005,
              'ifouterrors'      => 0,
              'ifoutoctets'      => 2179528910,
              'speed'            => 4294967295,
              'ifstatus'         => 1,
              'iftype'           => 53,
              'mac'              => '6c:50:4d:39:59:88',
              'trunk'            => 1,
              'ifspeed'          => 4294967295
          ),
          '5006' => array(
              'ifdescr'          => 'vlan0',
              'ifinerrors'       => 0,
              'ifinoctets'       => 1076823325,
              'ifinternalstatus' => 1,
              'iflastchange'     => '53.53 seconds',
              'ifmtu'            => 1500,
              'name'             => 'vlan0',
              'logical_number'   => 5006,
              'ifouterrors'      => 0,
              'ifoutoctets'      => 2179528910,
              'speed'            => 4294967295,
              'ifstatus'         => 1,
              'iftype'           => 54,
              'mac'              => '6c:50:4d:39:59:89',
              'trunk'            => 1,
              'ifspeed'          => 4294967295
          )
      );
      $a_inventory['connection-mac'] = array(
          '10001' => array('cc:f9:54:a1:03:35'),
          '10002' => array('cc:f9:54:a1:03:36')
      );
      $a_inventory['vlans'] = array(
          '10001' => array(
              '281' => array(
                  'name' => 'printer',
                  'tag'  => 281
              )
          ),
          '10002' => array(
              '281' => array(
                  'name' => 'printer',
                  'tag'  => 281
              ),
              '280' => array(
                  'name' => 'admin',
                  'tag'  => 280
              )
          )
      );
      $a_inventory['connection-lldp'] = array(
          '10002' => array(
              'ifdescr'          => 'GigabitEthernet1/0/2',
              'ip'               => '192.168.100.100',
              'model'            => 'cisco WS-C3750G-24PS',
              'sysdescr'         => 'Cisco IOS Software, C3750 Software (C3750-ADVIPSERVICESK9-M), Version 12.2(46)SE, RELEASE SOFTWARE (fc2)\nCopyright (c) 1986-2008 by Cisco Systems, Inc.\nCompiled Thu 21-Aug-08 15:43 by nachen',
              'name'             => 'CENTRALSWITCH',
              'logical_number'   => '',
              'mac'              => ''
          )
      );
      $a_inventory['aggregate'] = array(
          '5005' => array('10001', '10002')
      );


      $pfiNetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkEquipment = new NetworkEquipment();

      $this->items_id = $networkEquipment->add(array('serial'      => 'FOC147UJEU4',
                                                     'entities_id' => 0));

      $this->assertGreaterThan(0, $this->items_id);

      $pfiNetworkEquipmentLib->updateNetworkEquipment($a_inventory, $this->items_id);

      $DB->query("UPDATE `glpi_plugin_fusioninventory_networkporttypes`"
              ." SET `import`='0'"
              ." WHERE `number`='54'");

      // To be sure not have 2 sme informations
      $pfiNetworkEquipmentLib->updateNetworkEquipment($a_inventory, $this->items_id);

   }


   /**
    * @test
    */
   public function NetworkEquipmentGeneral() {
      global $DB;

      $DB->connect();

      $networkEquipment = new NetworkEquipment();

      $networkEquipment->getFromDB(1);
      unset($networkEquipment->fields['date_mod']);
      $a_reference = array(
          'name'                 => 'switchr2d2',
          'serial'               => 'FOC147UJEU4',
          'id'                   => '1',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'ram'                  => '64',
          'otherserial'          => NULL,
          'contact'              => NULL,
          'contact_num'          => NULL,
          'users_id_tech'        => '0',
          'groups_id_tech'       => '0',
          'comment'              => NULL,
          'locations_id'         => '3',
          'domains_id'           => '0',
          'networks_id'          => '0',
          'networkequipmenttypes_id' => '0',
          'networkequipmentmodels_id' => '3',
          'networkequipmentfirmwares_id' => '3',
          'manufacturers_id'     => '29',
          'is_deleted'           => '0',
          'is_template'          => '0',
          'template_name'        => NULL,
          'users_id'             => '0',
          'groups_id'            => '0',
          'states_id'            => '0',
          'ticket_tco'           => '0.0000',
          'is_dynamic'           => '1',
      );

      $this->assertEquals($a_reference, $networkEquipment->fields);
   }



   /**
    * @test
    */
   public function NetworkEquipmentSnmpExtension() {
      global $DB;

      $DB->connect();

      $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
      $a_networkequipment = current($pfNetworkEquipment->find("`networkequipments_id`='1'", "", 1));
      unset($a_networkequipment['last_fusioninventory_update']);
      $a_reference = array(
          'id'                                          => '1',
          'networkequipments_id'                        => '1',
          'sysdescr'                                    => 'Cisco IOS Software, C2960 Software (C2960-LANBASEK9-M), Version 12.2(50)SE4, RELEASE SOFTWARE (fc1)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Fri 26-Mar-10 09:14 by prod_rel_team',
          'plugin_fusioninventory_configsecurities_id'  => '0',
          'uptime'                                      => '157 days, 02:14:44.00',
          'cpu'                                         => '5',
          'memory'                                      => '18',
          'last_PID_update'                             => '0',
          'serialized_inventory'                        => NULL
      );

      $this->assertEquals($a_reference, $a_networkequipment);

   }


   /**
    * @test
    */
   public function NetworkEquipmentInternalPorts() {
      global $DB;

      $DB->connect();

      $networkPort = new NetworkPort();
      $networkName = new NetworkName();
      $iPAddress   = new IPAddress();

      $a_networkports = $networkPort->find("`instantiation_type`='NetworkPortAggregate'
         AND `itemtype`='NetworkEquipment'
         AND `items_id`='1'
         AND `logical_number`='0'");

      $this->assertEquals(1, count($a_networkports), 'Number internal ports');

      $a_networkport = current($a_networkports);
      $this->assertEquals('6c:50:4d:39:59:80', $a_networkport['mac']);

      // May have 3 IP
      $a_networkname = current($networkName->find("`items_id`='".$a_networkport['id']."'
                                                         AND `itemtype`='NetworkPort'", "", 1));
      $a_ips_fromDB = $iPAddress->find("`itemtype`='NetworkName'
                                     AND `items_id`='".$a_networkname['id']."'", "`name`");
      $a_ips = array();
      foreach ($a_ips_fromDB as $data) {
         $a_ips[] = $data['name'];
      }
      $this->assertEquals(array('192.168.30.67', '192.168.40.67', '192.168.50.67'), $a_ips);

   }


   /**
    * @test
    */
   public function UnmanagedNetworkPort() {
      global $DB;

      $DB->connect();

      $networkPort = new NetworkPort();

      $a_networkports = $networkPort->find("`mac`='cc:f9:54:a1:03:35'
         AND `itemtype`='PluginFusioninventoryUnmanaged'");

      $this->assertEquals(1, count($a_networkports), 'Number of networkport may be 1');

      $a_networkport = current($a_networkports);
      $this->assertEquals('NetworkPortEthernet', $a_networkport['instantiation_type'], 'instantiation type may be "NetworkPortEthernet"');

      $this->assertGreaterThan(0, $a_networkport['items_id'], 'items_id may be more than 0');
   }



   /**
    * @test
    */
   public function NetworkPortConnection() {
      global $DB;

      $DB->connect();

      $networkPort = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();
      $pfUnmanaged = new PluginFusioninventoryUnmanaged();

      $a_networkports = $networkPort->find("`logical_number`='10001'");

      $this->assertEquals(1, count($a_networkports), 'Number of networkport 10001 may be 1');

      $a_networkport= current($a_networkports);
      $opposites_id = $networkPort_NetworkPort->getOppositeContact($a_networkport['id']);

      $networkPort->getFromDB($opposites_id);
      $pfUnmanaged->getFromDB($networkPort->fields['items_id']);

      $this->assertEquals(0, $pfUnmanaged->fields['hub'], 'May not be a hub');

      $a_networkports = $networkPort->find("`items_id`='".$pfUnmanaged->fields['id']."'
         AND `itemtype`='PluginFusioninventoryUnmanaged'");

      $this->assertEquals(1, count($a_networkports), 'Number of networkport of unknown ports may be 1');
   }



   /**
    * @test
    */
   public function NetworkPortAggregation() {
      global $DB;

      $DB->connect();

      $networkPort = new NetworkPort();
      $networkPortAggregate = new NetworkPortAggregate();

      $a_networkports = $networkPort->find("`logical_number`='5005'");

      $this->assertEquals(1, count($a_networkports), 'Number of networkport 5005 may be 1');

      $a_networkport= current($a_networkports);

      $a_aggregate = current($networkPortAggregate->find("`networkports_id`='".$a_networkport['id']."'", '', 1));

      $a_ports = importArrayFromDB($a_aggregate['networkports_id_list']);

      $this->assertEquals(array('2', '4'), $a_ports, 'aggregate ports');
   }



   /**
    * @test
    */
   public function VlansPort10002() {
      global $DB;

      $DB->connect();

      $networkPort = new NetworkPort();

      $a_networkports = $networkPort->find("`instantiation_type`='NetworkPortEthernet'
         AND `itemtype`='NetworkEquipment'
         AND `items_id`='1'
         AND `name`='Fa0/2'");

      $this->assertEquals(1, count($a_networkports),
         'Networkport 10002 of switch must have only 1 port'
      );

      $a_networkport = current($a_networkports);

      $a_vlans = NetworkPort_Vlan::getVlansForNetworkPort($a_networkport['id']);
      $this->assertEquals(2, count($a_vlans), 'Networkport 10002 of switch may have 2 Vlans');
   }



   /**
    * @test
    */
   public function NetworkPortCreated() {
      global $DB;

      $DB->connect();

      $networkPort = new NetworkPort();
      $a_networkports = $networkPort->find("`itemtype`='NetworkEquipment'");

      $this->assertEquals(4, count($a_networkports), 'Number of networkport must be 4');

   }

}
?>
