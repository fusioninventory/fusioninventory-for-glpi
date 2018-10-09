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

class RuleImportTest extends Common_TestCase {


   protected function setUp() {
      global $DB;

      parent::setUp();

      $DB->connect();

      self::restore_database();

   }

   function changeRulesForPrinterRules() {
      global $DB;

      $DB->query("UPDATE `glpi_rules`
         SET `is_active`='0'
         WHERE `sub_type`='PluginFusioninventoryInventoryRuleImport'");


      $rule = new Rule();
      // Add a rule test check model
      $input = [];
      $input['is_active']=1;
      $input['name']='Printer model';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = 198;
      $rule_id = $rule->add($input);

         // Add criteria
         $rulecriteria = new RuleCriteria();
         $input = [];
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "model";
         $input['pattern']= '1';
         $input['condition']=10;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction();
         $input = [];
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      // Add a rule to ignore import
      // Create rule for import into unknown devices
      $input = [];
      $input['is_active']=1;
      $input['name']='Import pinter';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = 199;
      $rule_id = $rule->add($input);

         // Add criteria
         $rulecriteria = new RuleCriteria();
         $input = [];
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= '1';
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = [];
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= '1';
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = [];
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= '1';
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = [];
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Printer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction();
         $input = [];
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      // Denied import
      $input = [];
      $input['is_active']=1;
      $input['name']='Import pinter';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = 200;
      $rule_id = $rule->add($input);

         // Add criteria
         $input = [];
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= '1';
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $input = [];
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '2';
         $ruleaction->add($input);

   }


   /**
    * @test
    */
   public function PrinterDiscoveryImport() {
      global $DB, $PF_CONFIG;

      $this->changeRulesForPrinterRules();

      $a_inventory = [
          'AUTHSNMP'     => '1',
          'DESCRIPTION'  => 'Brother NC-6400h, Firmware Ver.1.11  (06.12.20),MID 84UZ92',
          'ENTITY'       => '0',
          'FIRMWARE'     => '',
          'IP'           => '10.36.4.29',
          'MAC'          => '00:80:77:d9:51:c3',
          'MANUFACTURER' => 'Brother',
          'MODEL'        => '',
          'MODELSNMP'    => 'Printer0442',
          'NETBIOSNAME'  => 'UH4DLPT01',
          'SERIAL'       => 'E8J596100',
          'SNMPHOSTNAME' => 'UH4DLPT01',
          'TYPE'         => 'PRINTER'
      ];

      $pfCommunicationNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $printer = new Printer();

      $printer->add([
          'entities_id' => '0',
          'serial'      => 'E8J596100'
      ]);

      $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = 1;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id']    = '1';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype']    = 'Printer';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['state']       = 0;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']     = '';

      $pfCommunicationNetworkDiscovery->sendCriteria($a_inventory);

      $a_printers = $printer->find();
      $this->assertEquals(1, count($a_printers), 'May have only one Printer');

      $a_printer = current($a_printers);
      $this->assertEquals('UH4DLPT01', $a_printer['name'], 'Hostname of printer may be updated');

   }


   /**
    * @test
    */
   public function PrinterDiscoveryImportDenied() {
      global $DB;

      $this->changeRulesForPrinterRules();

      $a_inventory = [
          'AUTHSNMP'     => '1',
          'DESCRIPTION'  => 'Brother NC-6400h, Firmware Ver.1.11  (06.12.20),MID 84UZ92',
          'ENTITY'       => '0',
          'FIRMWARE'     => '',
          'IP'           => '10.36.4.29',
          'MAC'          => '00:80:77:d9:51:c3',
          'MANUFACTURER' => 'Brother',
          'MODEL'        => '',
          'MODELSNMP'    => 'Printer0442',
          'NETBIOSNAME'  => 'UH4DLPT01',
          'SERIAL'       => 'E8J596100A',
          'SNMPHOSTNAME' => 'UH4DLPT01',
          'TYPE'         => 'PRINTER'
      ];

      $pfCommunicationNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $printer = new Printer();

      $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = 1;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id']    = '1';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype']    = 'Printer';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['state']       = 0;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']     = '';

      $pfCommunicationNetworkDiscovery->sendCriteria($a_inventory);

      $a_printers = $printer->find();
      $this->assertEquals(0, count($a_printers), 'May have only one Printer');

      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $a_logs = $pfTaskjoblog->find("`comment` LIKE '%importdenied%'", '`id` DESC', 1);
      $a_log = current($a_logs);
      $this->assertEquals('==importdenied== [serial]:E8J596100A, '.
              '[mac]:00:80:77:d9:51:c3, [ip]:10.36.4.29, [model]:Printer0442, '.
              '[name]:UH4DLPT01, [entities_id]:0, [itemtype]:Printer',
              $a_log['comment'], 'Import denied message');
   }


   /**
    * @test
    */
   public function SwitchLLDPImport() {
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
      $ports = $networkPort->find("`mac`='b4:39:d6:3b:22:bd'", "", 1);
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
      $ports = $networkPort->find("`name`='port28'", "", 1);
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
      $ports = $networkPort->find("`name`='port28'", "", 1);
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
      $ports = $networkPort->find("`name`='port21'", "", 1);
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
      $ports = $networkPort->find("`name`='port21'", "", 1);
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
      $ports = $networkPort->find("`name`='port21'", "", 1);
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
      $ports = $networkPort->find("`name`='port48'", "", 1);
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
