<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

class Hub extends PHPUnit_Framework_TestCase {


   public function testSetModuleInventoryOff() {
      global $DB;
      
      Config::detectRootDoc();

     // set in config module inventory = yes by default
     $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
        SET `is_active`='0'
        WHERE `modulename`='SNMPQUERY' ";
     $DB->query($query);

   }



   public function testSetModuleInventoryOn() {
      $DB = new DB();

      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
         SET `is_active`='1'
         WHERE `modulename`='SNMPQUERY' ";
      $DB->query($query);

   }


   public function testSendinventories() {
      
      $plugin = new Plugin();
      $plugin->getFromDBbyDir("fusioninventory");
      $plugin->activate($plugin->fields['id']);
      Plugin::load("fusioninventory");
      
      // Add task and taskjob
      $pluginFusioninventoryTask = new PluginFusioninventoryTask();
      $pluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $pluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();

      $input = array();
      $input['entities_id'] = '0';
      $input['name'] = 'snmpquery';
      $tasks_id = $pluginFusioninventoryTask->add($input);

      $input = array();
      $input['plugin_fusioninventory_tasks_id'] = $tasks_id;
      $input['method'] = 'snmpquery';
      $input['status'] = 1;
      $taskjobs_id = $pluginFusioninventoryTaskjob->add($input);

      $input = array();
      $input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $input['itemtype'] = 'NetworkEquipment';
      $input['items_id'] = '1';
      $input['state'] = 1;
      $input['plugin_fusioninventory_agents_id'] = 1;
      $pluginFusioninventoryTaskjobstatus->add($input);
      $input['items_id'] = '2';
      $pluginFusioninventoryTaskjobstatus->add($input);

      $switch1 = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <COMMENTS>Cisco IOS Software, C2960 Software (C2960-LANBASEK9-M), Version 12.2(52)SE, RELEASE SOFTWARE (fc3)
Copyright (c) 1986-2009 by Cisco Systems, Inc.
Compiled Fri 25-Sep-09 08:49 by sasyamal</COMMENTS>
        <CPU>11</CPU>
        <ID>1</ID>
        <IPS>
          <IP>192.168.20.80</IP>
        </IPS>
        <MAC>00:1a:6c:9a:fc:80</MAC>
        <NAME>switch2960-001</NAME>
        <SERIAL>FOC1757ZFMY</SERIAL>
        <TYPE>NETWORKING</TYPE>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CONNECTION>
              <MAC>00:23:18:cf:0d:93</MAC>
            </CONNECTION>
            <CONNECTION>
              <MAC>f0:ad:4e:00:19:f7</MAC>
            </CONNECTION>
            <CONNECTION>
              <MAC>f0:ad:4e:10:39:f9</MAC>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/1</IFDESCR>
          <IFNAME>Fa0/1</IFNAME>
          <IFNUMBER>10001</IFNUMBER>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1a:6c:9a:fc:85</MAC>
          <TRUNK>0</TRUNK>
          <VLANS>
            <VLAN>
              <NAME>VLAN0020</NAME>
              <NUMBER>20</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $switch2 = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <COMMENTS>Cisco IOS Software, C2960 Software (C2960-LANBASEK9-M), Version 12.2(52)SE, RELEASE SOFTWARE (fc3)
Copyright (c) 1986-2009 by Cisco Systems, Inc.
Compiled Fri 25-Sep-09 08:49 by sasyamal</COMMENTS>
        <CPU>11</CPU>
        <ID>2</ID>
        <IPS>
          <IP>192.168.20.81</IP>
        </IPS>
        <MAC>00:1a:6c:9a:fa:80</MAC>
        <NAME>switch2960-002</NAME>
        <SERIAL>FOC1040ZFNU</SERIAL>
        <TYPE>NETWORKING</TYPE>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/1</IFDESCR>
          <IFNAME>Fa0/1</IFNAME>
          <IFNUMBER>10001</IFNUMBER>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1a:6c:9a:fa:85</MAC>
          <TRUNK>0</TRUNK>
          <VLANS>
            <VLAN>
              <NAME>VLAN0020</NAME>
              <NUMBER>20</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
        <PORT>
          <CONNECTIONS>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/2</IFDESCR>
          <IFNAME>Fa0/2</IFNAME>
          <IFNUMBER>10002</IFNUMBER>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1a:6c:9a:fa:86</MAC>
          <TRUNK>0</TRUNK>
       </PORT>
        <PORT>
          <CONNECTIONS>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/3</IFDESCR>
          <IFNAME>Fa0/3</IFNAME>
          <IFNUMBER>10003</IFNUMBER>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1a:6c:9a:fa:87</MAC>
          <TRUNK>0</TRUNK>
       </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>2</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

      $pluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $pluginFusinvsnmpNetworkPortConnectionLog = new PluginFusinvsnmpNetworkPortConnectionLog();
      $networkEquipment = new NetworkEquipment();
      $networkPort = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();

      // * 1. Create switchs
      $this->testSendinventory("toto", $switch1, 1);
      $this->testSendinventory("toto", $switch2, 1);
         // CHECK 1 : verify hub created on port1 of switch 1
         $a_list = $networkEquipment->find("`serial`='FOC1757ZFMY'");
         $this->assertEquals(count($a_list), 1, 'switch 1 not added in GLPI');
         $a_switch = current($a_list);
         $a_ports = $networkPort->find("`itemtype`='NetworkEquipment'
               AND `items_id`='".$a_switch['id']."'");
         $this->assertEquals(count($a_ports), 1, 'switch 1 haven\'t port fa0/1 added in GLPI');
         $a_port = current($a_ports);
         $contactport_id = $networkPort->getContact($a_port['id']);
         $networkPort->getFromDB($contactport_id);
         if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $pluginFusioninventoryUnknownDevice->getFromDB($networkPort->fields['items_id']);
            $this->assertEquals($pluginFusioninventoryUnknownDevice->fields['hub'],
                              '1', 'No hub connected on port fa0/1 of switch 1');
         } else {
            $t = 0;
            $this->assertEquals($t, '1', 'No hub port connected on port fa0/1 of switch 1');
         }
         // CHECK 2 : Verify number of networkportconnectionslog
         $a_conn = $pluginFusinvsnmpNetworkPortConnectionLog->find("`creation` = '1'");
         $this->assertEquals(count($a_conn), '1', '(1) Connections logs not equal to 1 ('.count($a_conn).')');
      
      $switch1bis = str_replace("            <CONNECTION>
              <MAC>00:23:18:cf:0d:93</MAC>
            </CONNECTION>", "", $switch1);

      // * 2. Update switchs
      $this->testSendinventory("toto", $switch1bis);
      //$this->testSendinventory("toto", $switch2);
         // CHECK 1 : verify hub always here and connected
         $a_ports = $networkPort->find("`itemtype`='NetworkEquipment'
               AND `items_id`='".$a_switch['id']."'");
         $this->assertEquals(count($a_ports), 1, '(2)switch 1 haven\'t port fa0/1 added in GLPI');
         $a_port = current($a_ports);
         $contactport_id = $networkPort->getContact($a_port['id']);
         $networkPort->getFromDB($contactport_id);
         if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $pluginFusioninventoryUnknownDevice->getFromDB($networkPort->fields['items_id']);
            $this->assertEquals($pluginFusioninventoryUnknownDevice->fields['hub'],
                              '1', '(2)No hub connected on port fa0/1 of switch 1');
         } else {
            $t = 0;
            $this->assertEquals($t, '1', '(2)No hub port connected on port fa0/1 of switch 1');
         }
         // CHECK 2 : verify hub has always the 3 ports connected (3 mac addresses)
         $a_portshub = $networkPort->find("`itemtype`='PluginFusioninventoryUnknownDevice'
            AND `items_id`='".$networkPort->fields['items_id']."'");
         $this->assertEquals(count($a_portshub),
                           '4', '(2)Don\'t have the 4 ports connected to hub');
         // CHECK 3 : Verify number of networkportconnectionslog
         $a_conn = $pluginFusinvsnmpNetworkPortConnectionLog->find("`creation` = '1'");
         $this->assertEquals(count($a_conn), '1', '(2) Connections logs not equal to 1 ('.count($a_conn).')');
 

      
      $switch2 = str_replace("</CONNECTIONS>
          <IFDESCR>FastEthernet0/1</IFDESCR>", "               <CONNECTION>
               <MAC>00:23:18:cf:0d:93</MAC>
               </CONNECTION>
            </CONNECTIONS>
          <IFDESCR>FastEthernet0/1</IFDESCR>", $switch2);

      // * 3. Update switchs
      $this->testSendinventory("toto", $switch2);
         // CHECK 1 : verify hub always here and connected
         $a_ports = $networkPort->find("`itemtype`='NetworkEquipment'
               AND `items_id`='".$a_switch['id']."'");
         $this->assertEquals(count($a_ports), 1, '(3)switch 1 haven\'t port fa0/1 added in GLPI');
         $a_port = current($a_ports);
         $contactport_id = $networkPort->getContact($a_port['id']);
         $networkPort->getFromDB($contactport_id);
         if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $pluginFusioninventoryUnknownDevice->getFromDB($networkPort->fields['items_id']);
            $this->assertEquals($pluginFusioninventoryUnknownDevice->fields['hub'],
                              '1', '(3)No hub connected on port fa0/1 of switch 1');
         } else {
            $t = 0;
            $this->assertEquals($t, '1', '(3)No hub port connected on port fa0/1 of switch 1');
         }
         // CHECK 2 : verify hub has loose one port (2 mac addresses)
         $a_portshub = $networkPort->find("`itemtype`='PluginFusioninventoryUnknownDevice'
            AND `items_id`='".$networkPort->fields['items_id']."'");
         $this->assertEquals(count($a_portshub),
                           '3', '(3)Don\'t have the 3 ports connected to hub');
         // CHECK 3 : verify port disconnected has been connected to port1 of switch 2
         $a_ports = $networkPort->find("`itemtype`='PluginFusioninventoryUnknownDevice'
               AND `mac`='00:23:18:cf:0d:93'");
         $this->assertEquals(count($a_ports), 1, '(3)port with mac 00:23:18:cf:0d:93 is not in GLPI');
         $a_port = current($a_ports);
         $contactport_id = $networkPort->getContact($a_port['id']);
         $networkPort->getFromDB($contactport_id);
         if ($networkPort->fields['itemtype'] == 'NetworkEquipment') {
            $this->assertEquals($networkPort->fields['items_id'],
                              '2', '(3)port with mac 00:23:18:cf:0d:93 not connected with swith 2');
         } else {
            $t = 0;
            $this->assertEquals($t, '1', '(3)port with mac 00:23:18:cf:0d:93 not connected to a switch');
         }
         // CHECK 4 : Verify number of networkportconnectionslog
         $a_conn = $pluginFusinvsnmpNetworkPortConnectionLog->find("`creation` = '1'");
         $this->assertEquals(count($a_conn), '2', '(3) Connections logs not equal to 2 ('.count($a_conn).')');
         
         

      $switch1bis = $switch1;
      $switch1bis = str_replace("<CONNECTION>
              <MAC>f0:ad:4e:00:19:f7</MAC>
            </CONNECTION>", "", $switch1bis);
      
      $switch1bis = str_replace("<CONNECTION>
              <MAC>00:23:18:cf:0d:93</MAC>
            </CONNECTION>", "", $switch1bis);

      // * 4. Update switchs
      $this->testSendinventory("toto", $switch1bis);
      //$this->testSendinventory("toto", $switch2);
         // CHECK 1 : verify hub always on port 1 of switch 1 
         $a_ports = $networkPort->find("`itemtype`='NetworkEquipment'
               AND `items_id`='".$a_switch['id']."'");
         $this->assertEquals(count($a_ports), 1, '(4)switch 1 haven\'t port fa0/1 added in GLPI');
         $a_port = current($a_ports);
         $contactport_id = $networkPort->getContact($a_port['id']);
         $networkPort->getFromDB($contactport_id);
         if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $pluginFusioninventoryUnknownDevice->getFromDB($networkPort->fields['items_id']);
            $this->assertEquals($pluginFusioninventoryUnknownDevice->fields['hub'],
                              '1', '(4)Hub not connected on port fa0/1 of switch 1');
         }
         // CHECK 2 : verify port 1 of the switch don't have 2 connections
         $a_list_connections = $networkPort_NetworkPort->find("`networkports_id_1`='1'");
         $this->assertEquals(count($a_list_connections),
                              '1', '(4) Port fa0/1 of switch 1 not connected to 1 port ('.
                                    count($a_list_connections).')');

      $switch2bis = $switch2;
      $switch2 = str_replace("</CONNECTIONS>
          <IFDESCR>FastEthernet0/2</IFDESCR>", "               <CONNECTION>
               <MAC>f0:ad:4e:00:19:f7</MAC>
               </CONNECTION>
            </CONNECTIONS>
          <IFDESCR>FastEthernet0/2</IFDESCR>", $switch2);
      
      // * 5. Update switchs
      $this->testSendinventory("toto", $switch2);
         // CHECK 1 : verify hub deleted
         $a_list_hub = $pluginFusioninventoryUnknownDevice->find("`hub`='1'");
         $this->assertEquals(count($a_list_hub),
                              '0', '(5) Hub not deleted');
         // CHECK 2 : verify port 1 of the switch don't have 2 connections
         $a_list_connections = $networkPort_NetworkPort->find("`networkports_id_1`='1'");
         $this->assertEquals(count($a_list_connections),
                              '1', '(5) Port fa0/1 of switch 1 not connected to 1 port ('.
                                    count($a_list_connections).')');
         // CHECK 3 : verify port 1 of switch 1 connected directly to port
         $a_connection = current($a_list_connections);
         $networkPort->getFromDB($a_connection['networkports_id_2']);
         $this->assertEquals($networkPort->fields['mac'],
                              'f0:ad:4e:10:39:f9', '(5) Port 1 of switch 1 not connected to port with mac f0:ad:4e:10:39:f9');
         
         
         // TODO: Verify port connected to port 2 of switch 2
         
         // CHECK 4 : Verify number of networkportconnectionslog
         $a_conn = $pluginFusinvsnmpNetworkPortConnectionLog->find("`creation` = '1'");
         $this->assertEquals(count($a_conn), '4', '(5) Connections logs not equal to 4 ('.count($a_conn).')');
         // CHECK 5 : Verify number of networkportconnectionslog
         $a_conn = $pluginFusinvsnmpNetworkPortConnectionLog->find("`creation` = '0'");
         $this->assertEquals(count($a_conn), '1', '(5) Connections logs not equal to 1 ('.count($a_conn).')');




      // * 6. Update switchs
      // $switch1bis have 2 mac
      $switch1bis = $switch1;
      $switch1bis = str_replace("<CONNECTION>
              <MAC>f0:ad:4e:00:19:f7</MAC>
            </CONNECTION>", "", $switch1bis);
      $this->testSendinventory("toto", $switch1bis);
      $this->testSendinventory("toto", $switch2bis);
         // CHECK 1 : Verify have hub on port 1 of switch 1
         $a_ports = $networkPort->find("`itemtype`='NetworkEquipment'
               AND `items_id`='".$a_switch['id']."'");
         $this->assertEquals(count($a_ports), 1, '(6)switch 1 haven\'t port fa0/1 added in GLPI');
         $a_port = current($a_ports);
         $contactport_id = $networkPort->getContact($a_port['id']);
         $networkPort->getFromDB($contactport_id);
         if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $pluginFusioninventoryUnknownDevice->getFromDB($networkPort->fields['items_id']);
            $this->assertEquals($pluginFusioninventoryUnknownDevice->fields['hub'],
                              '1', '(6) Hub not connected on port fa0/1 of switch 1');
         } else {
            $t = 0;
            $this->assertEquals($t, '1', '(6) Hub not connected on port fa0/1 of switch 1');
         }
         // CHECK 2 : verify port 1 of the switch don't have 2 connections
         $a_list_connections = $networkPort_NetworkPort->find("`networkports_id_1`='1'");
         $this->assertEquals(count($a_list_connections),
                              '1', '(6) Port fa0/1 of switch 1 not connected to 1 port ('.
                                    count($a_list_connections).')');
         
      /* 
       * 7. When have hub on port and next inventory have a CDP device
       *    hub must be deconnected 
       */
      $switch1biscdp = str_replace("<CONNECTION>
              <MAC>f0:ad:4e:00:19:f7</MAC>
            </CONNECTION>
            <CONNECTION>
              <MAC>f0:ad:4e:10:39:f9</MAC>
            </CONNECTION>", "<CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>fa0/2</IFDESCR>
              <IP>192.168.30.51</IP>
            </CONNECTION>", $switch1bis);
      $this->testSendinventory("toto", $switch1biscdp);
        // CHECK 1 : Verify have no hub on port 1 of switch 1
         $a_ports = $networkPort->find("`itemtype`='NetworkEquipment'
               AND `items_id`='".$a_switch['id']."'");
         $this->assertEquals(count($a_ports), 1, '(7)switch 1 haven\'t port fa0/1 added in GLPI');
         $a_port = current($a_ports);
         $contactport_id = $networkPort->getContact($a_port['id']);
         $networkPort->getFromDB($contactport_id);
         if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $pluginFusioninventoryUnknownDevice->getFromDB($networkPort->fields['items_id']);
            $this->assertEquals($pluginFusioninventoryUnknownDevice->fields['hub'],
                              '0', '(7) Hub connected on port fa0/1 of switch 1');
         }

         
      /* 
       * 8. When have hub on port and next inventory have a CDP device
       *    hub must be deconnected (but CDP device is your switch 2
       */
      $this->testSendinventory("toto", $switch1bis);
      $switch1biscdp = str_replace("<CONNECTION>
              <MAC>f0:ad:4e:00:19:f7</MAC>
            </CONNECTION>
            <CONNECTION>
              <MAC>f0:ad:4e:10:39:f9</MAC>
            </CONNECTION>", "<CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>Fa0/3</IFDESCR>
              <IP>192.168.20.81</IP>
            </CONNECTION>", $switch1bis);
      $this->testSendinventory("toto", $switch1biscdp);
         // CHECK 1 : Verify have no hub on port 1 of switch 1
         $a_ports = $networkPort->find("`itemtype`='NetworkEquipment'
               AND `items_id`='".$a_switch['id']."'");
         $this->assertEquals(count($a_ports), 1, '(8)switch 1 haven\'t port fa0/1 added in GLPI');
         $a_port = current($a_ports);
         $contactport_id = $networkPort->getContact($a_port['id']);
         $networkPort->getFromDB($contactport_id);
         if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
            $pluginFusioninventoryUnknownDevice->getFromDB($networkPort->fields['items_id']);
            $this->assertEquals($pluginFusioninventoryUnknownDevice->fields['hub'],
                              '0', '(8) Hub connected on port fa0/1 of switch 1');
         }
         
         
      // Verifiy 3 mac => 1 Mac not delete hub
         $this->testSendinventory("toto", $switch1);
         $switch1bis = $switch1;
         $switch1bis = str_replace("            <CONNECTION>
              <MAC>00:23:18:cf:0d:93</MAC>
            </CONNECTION>
            <CONNECTION>
              <MAC>f0:ad:4e:00:19:f7</MAC>
            </CONNECTION>", "", $switch1);
         $this->testSendinventory("toto", $switch1bis);
         $a_ports = $networkPort->find("`mac`='00:1a:6c:9a:fc:85'");
         $a_port = current($a_port);
         $id = $networkPort->getContact($a_port['id']);
         $networkPort->getFromDB($id);
         $this->assertEquals($networkPort->fields['itemtype'],
                              'PluginFusioninventoryUnknownDevice', '(9) Port may be connected to unknown device (hub)');
         
         
         
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }



   function testSendinventory($xmlFile='', $xmlstring='', $create='0') {

      if (empty($xmlFile)) {
         echo "testSendinventory with no arguments...\n";
         return;
      }

      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/fusion0.80/plugins/fusioninventory/front/communication.php";
      if (empty($xmlstring)) {
         $xml = simplexml_load_file($xmlFile,'SimpleXMLElement', LIBXML_NOCDATA);
      } else {
         $xml = simplexml_load_string($xmlstring);
      }

      if ($create == '1') {
         // Send prolog for creation of agent in GLPI
         $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
   <REQUEST>
     <DEVICEID>'.$xml->DEVICEID.'</DEVICEID>
     <QUERY>PROLOG</QUERY>
     <TOKEN>CBXTMXLU</TOKEN>
   </REQUEST>';
         $emulatorAgent->sendProlog($input_xml);

         foreach ($xml->CONTENT->DEVICE as $child) {
            foreach ($child->INFO as $child2) {
               if ($child2->TYPE == 'NETWORKING') {
                  // Create switch in asset
                  $NetworkEquipment = new NetworkEquipment();
                  $input = array();
                  if (isset($child2->SERIAL)) {
                     $input['serial']=$child2->SERIAL;
                  } else {
                     $input['name']=$child2->NAME;
                  }
                  $input['entities_id'] = 0;
                  $NetworkEquipment->add($input);
               }
            }
         }
      }
      $input_xml = $xml->asXML();
      $code = $emulatorAgent->sendProlog($input_xml);
      echo $code."\n";
      $this->assertEquals($code, '<?xml version="1.0" encoding="UTF-8"?>
<REPLY>
</REPLY>
', 'Return code not right');
      
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }
   
}



class Hub_AllTests  {

   public static function suite() {
      
      $GLPIInstall = new GLPIInstall();
      $Install = new Install();
      $GLPIInstall->testInstall();
      $Install->testInstall(0);

      $suite = new PHPUnit_Framework_TestSuite('Hub');
      return $suite;
   }
}
?>
