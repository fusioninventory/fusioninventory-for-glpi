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

class Switchinventory extends PHPUnit_Framework_TestCase {


   public function testSetModuleInventoryOn() {
      $DB = new DB();

      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
         SET `is_active`='1'
         WHERE `modulename`='SNMPQUERY' ";
      $DB->query($query);
      
      $networkEquipment = new NetworkEquipment();
      $a_equipments = $networkEquipment->find();
      foreach ($a_equipments as $id=>$data) {
         $networkEquipment->delete(array('id'=>$id), 1);
      }

   }


   public function testSendinventories() {
      global $DB;
      
      $plugin = new Plugin();
      $plugin->getFromDBbyDir("fusioninventory");
      $plugin->activate($plugin->fields['id']);
      Plugin::load("fusioninventory");
      
      // Active extra-debug
      $pfConfig = new PluginFusioninventoryConfig();
      $pfConfig->updateConfigType($plugin->fields['id'], "extradebug", "1");
      
      // Add task and taskjob
      $pfTask = new PluginFusioninventoryTask();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();

      $input = array();
      $input['entities_id'] = '0';
      $input['name'] = 'snmpquery';
      $tasks_id = $pfTask->add($input);

      $input = array();
      $input['plugin_fusioninventory_tasks_id'] = $tasks_id;
      $input['method'] = 'snmpquery';
      $input['status'] = 1;
      $taskjobs_id = $pfTaskjob->add($input);

      $input = array();
      $input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $input['itemtype'] = 'NetworkEquipment';
      $input['items_id'] = '1';
      $input['state'] = 1;
      $input['plugin_fusioninventory_agents_id'] = 1;
      $pfTaskjobstatus->add($input);
      $input['items_id'] = '2';
      $pfTaskjobstatus->add($input);

      $switch1 = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <COMMENTS>Cisco IOS Software, 1841 Software (C1841-ADVIPSERVICESK9-M), Version 12.4(25d), RELEASE SOFTWARE (fc1)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Wed 18-Aug-10 04:40 by prod_rel_team</COMMENTS>
        <ID>53</ID>
        <IPS>
          <IP>172.27.2.22</IP>
          <IP>212.99.4.74</IP>
          <IP>212.99.4.73</IP>
          <IP>172.27.2.21</IP>
        </IPS>
        <LOCATION>RMS Grenoble </LOCATION>
        <MAC>00:00:00:00:00:00</MAC>
        <MODEL>CISCO1841</MODEL>
        <NAME>vpn1.vpn.rms.loc</NAME>
        <SERIAL>FCZ11161074</SERIAL>
        <TYPE>NETWORKING</TYPE>
        <UPTIME>26 days, 02:17:14.06</UPTIME>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>GigabitEthernet1/0/22</IFDESCR>
              <IP>172.27.0.40</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/1</IFDESCR>
          <IFINERRORS>31</IFINERRORS>
          <IFINOCTETS>3088668153</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>15.24 seconds</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Fa0/1</IFNAME>
          <IFNUMBER>2</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>3475169543</IFOUTOCTETS>
          <IFSPEED>100000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1b:54:99:62:45</MAC>
        </PORT>
        <PORT>
          <IFDESCR>Null0</IFDESCR>
          <IFINERRORS>0</IFINERRORS>
          <IFINOCTETS>0</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>0.00 seconds</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Nu0</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>0</IFOUTOCTETS>
          <IFSPEED>4294967295</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>1</IFTYPE>
        </PORT>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>GigabitEthernet1/0/23</IFDESCR>
              <IP>172.27.0.40</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/0</IFDESCR>
          <IFINERRORS>232</IFINERRORS>
          <IFINOCTETS>4006858975</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>15.23 seconds</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Fa0/0</IFNAME>
          <IFNUMBER>1</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>1553256247</IFOUTOCTETS>
          <IFSPEED>100000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1b:54:99:62:44</MAC>
        </PORT>
        <PORT>
          <VLANS>
            <VLAN>
               <NUMBER>10</NUMBER>
               <NAME>Servers</NAME>
            </VLAN>
          </VLANS>
          <IFDESCR>FastEthernet0/12</IFDESCR>
          <IFINERRORS>232</IFINERRORS>
          <IFINOCTETS>4006858975</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>15.23 seconds</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Fa0/12</IFNAME>
          <IFNUMBER>12</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>1553256247</IFOUTOCTETS>
          <IFSPEED>100000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1b:54:99:62:52</MAC>
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
        <COMMENTS>Cisco IOS Software, C3750 Software (C3750-IPSERVICESK9-M), Version 12.2(55)SE, RELEASE SOFTWARE (fc2)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Sat 07-Aug-10 22:45 by prod_rel_team</COMMENTS>
        <CPU>6</CPU>
        <FIRMWARE>12.2(55)SE</FIRMWARE>
        <ID>1585</ID>
        <IPS>
          <IP>172.27.0.40</IP>
        </IPS>
        <LOCATION>RMS Grenoble </LOCATION>
        <MAC>00:1b:2b:20:40:80</MAC>
        <MEMORY>33</MEMORY>
        <MODEL>WS-C3750G-24T-S</MODEL>
        <NAME>sw1.inf.rms.loc</NAME>
        <RAM>128</RAM>
        <SERIAL>CAT1109RGVK</SERIAL>
        <TYPE>NETWORKING</TYPE>
        <UPTIME>41 days, 06:53:36.46</UPTIME>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>FastEthernet0/0</IFDESCR>
              <IP>212.99.4.74</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>GigabitEthernet1/0/23</IFDESCR>
          <IFINERRORS>0</IFINERRORS>
          <IFINOCTETS>3245688497</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>2 days, 03:56:07.66</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Gi1/0/23</IFNAME>
          <IFNUMBER>10123</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>851136551</IFOUTOCTETS>
          <IFSPEED>100000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1b:2b:20:40:97</MAC>
          <TRUNK>0</TRUNK>
        </PORT>
        <PORT>
          <CONNECTIONS>
            <CDP>1</CDP>
            <CONNECTION>
              <IFDESCR>FastEthernet0/1</IFDESCR>
              <IP>172.27.2.22</IP>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>GigabitEthernet1/0/22</IFDESCR>
          <IFINERRORS>0</IFINERRORS>
          <IFINOCTETS>949702179</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>2 days, 03:56:07.64</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Gi1/0/22</IFNAME>
          <IFNUMBER>10122</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>2633042471</IFOUTOCTETS>
          <IFSPEED>100000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1b:2b:20:40:96</MAC>
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

      $networkPort = new NetworkPort();
      $networkEquipment = new NetworkEquipment();
      $a_networkequipments = $networkEquipment->find();
      foreach ($a_networkequipments as $data) {
         $networkEquipment->delete($data, 1);
      }
      
      // * 1. Create switch 1
      $this->testSendinventory("toto", $switch1, 1);
         
      // * 2. Create switch 2 
      $this->testSendinventory("toto", $switch2, 1);
      
      // * 3. update switch 1
      $this->testSendinventory("toto", $switch1);
         
         // CHECK 1 : Check ip of ports
         $a_ports = $networkPort->find("`name`='Fa0/1'");
         $a_port = current($a_ports);
         $this->assertEquals($a_port['ip'], "", 'IP of port Fa/01 not right');
         $this->assertEquals($a_port['mac'], "00:1b:54:99:62:45", 'MAC of port Fa/01 not right');
         
         $a_ports = $networkPort->find("`name`='Fa0/0'");
         $a_port = current($a_ports);
         $this->assertEquals($a_port['ip'], "", 'IP of port Fa0/0 not right');
         $this->assertEquals($a_port['mac'], "00:1b:54:99:62:44", 'MAC of port Fa0/0 not right');
         
         $a_ports = $networkPort->find("`name`='Gi1/0/23'");
         $a_port = current($a_ports);
         $this->assertEquals($a_port['ip'], "", 'IP of port Gi1/0/23 not right');
         $this->assertEquals($a_port['mac'], "00:1b:2b:20:40:97", 'MAC of port Gi1/0/23 not right');
         
         $a_ports = $networkPort->find("`name`='Gi1/0/22'");
         $a_port = current($a_ports);
         $this->assertEquals($a_port['ip'], "", 'IP of port Gi1/0/22 not right');
         $this->assertEquals($a_port['mac'], "00:1b:2b:20:40:96", 'MAC of port Gi1/0/22 not right');
         
         $GLPIlog = new GLPIlogs();
         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         // Verify not have networkport_networkport with networkports_id = 0
         $zombieConnect = $networkPort_NetworkPort->find("`networkports_id_1`='0'
            OR `networkports_id_2`='0'");
         $this->assertEquals(count($zombieConnect), 0, 'Zombie connections detected : '.print_r($zombieConnect, true));
      
         // Verify have only 2 switches
         $this->assertEquals(count($networkEquipment->find()), 2, '[1] May have 2 switches created');
      
         // verify Vlan
         $vlan = new Vlan();
         $networkPort_Vlan = new NetworkPort_Vlan();
         $a_vlans = $vlan->find("`tag`='10' AND `name`='Servers'");
         $this->assertEquals(count($a_vlans), 1, 'Vlan 10 not created');
         $a_vlan = current($a_vlans);
         $a_ports12 = $networkPort->find("`name`='Fa0/12'");
         $a_port12 = current($a_ports12);
         $a_np_vlans = $networkPort_Vlan->find("`networkports_id`='".$a_port12['id']."'
            AND `vlans_id`='".$a_vlan['id']."'");
         $this->assertEquals(count($a_np_vlans), 1, 'Vlan not assigned to port Fa0/12');
         
      // * Test modifications of IP of the switch
      $networkEquipment = new NetworkEquipment();
      $a_switches = $networkEquipment->find("`serial`='FCZ11161074'");
      $a_switch = current($a_switches);
      
      $switch1bis = str_replace('<IP>172.27.2.22</IP>', '', $switch1);
      $this->testSendinventory("toto", $switch1bis);
      
         $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                 WHERE `networkequipments_id`='".$a_switch['id']."'";
         $result = $DB->query($query);
         $this->assertEquals($DB->numrows($result), 3, 'May have 3 IPs for this switch');
         
         // Verify have only 2 switches
         $this->assertEquals(count($networkEquipment->find()), 2, '[2] May have 2 switches created');
      
      $switch1bis = str_replace('<IP>212.99.4.74</IP>', '', $switch1);
      $this->testSendinventory("toto", $switch1bis);      
         $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                 WHERE `networkequipments_id`='".$a_switch['id']."'";
         $result = $DB->query($query);
         $this->assertEquals($DB->numrows($result), 3, 'May have 3 IPs for this switch');

         $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                 WHERE `networkequipments_id`='".$a_switch['id']."'
                    AND `ip`='172.27.2.22'";
         $result = $DB->query($query);
         $this->assertEquals($DB->numrows($result), 1, 'IP 172.27.2.22 may be here 1 time');

         $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                 WHERE `networkequipments_id`='".$a_switch['id']."'
                    AND `ip`='212.99.4.74'";
         $result = $DB->query($query);
         $this->assertEquals($DB->numrows($result), 0, 'IP 212.99.4.74 may be here 0 time');

         // Verify have only 2 switches
         $this->assertEquals(count($networkEquipment->find()), 2, '[3] May have 2 switches created');
      
         
         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();
         
      // Modify vlan
      $switch1bis = str_replace('<NUMBER>10</NUMBER>', '<NUMBER>20</NUMBER>', $switch1);
      $this->testSendinventory("toto", $switch1bis);
         
         // verify Vlan
         $vlan = new Vlan();
         $networkPort_Vlan = new NetworkPort_Vlan();
         $a_vlans = $vlan->find("`tag`='20' AND `name`='Servers'");
         $this->assertEquals(count($a_vlans), 1, 'Vlan 20 not created');
         $a_vlan = current($a_vlans);
         $a_ports12 = $networkPort->find("`name`='Fa0/12'");
         $a_port12 = current($a_ports12);
         $a_np_vlans = $networkPort_Vlan->find("`networkports_id`='".$a_port12['id']."'
            AND `vlans_id`='".$a_vlan['id']."'");
         $this->assertEquals(count($a_np_vlans), 1, 'Vlan 20 not assigned to port Fa0/12');
         $a_np_vlans = $networkPort_Vlan->find("`networkports_id`='".$a_port12['id']."'");
         $this->assertEquals(count($a_np_vlans), 1, 'Port Fa0/12 may have only 1 vlan');
   }



   function testSendinventory($xmlFile='', $xmlstring='', $create='0') {

      if (empty($xmlFile)) {
         echo "testSendinventory with no arguments...\n";
         return;
      }

      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/fusion0.83/plugins/fusioninventory/front/communication.php";
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
      
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }

}



class Switchinventory_AllTests  {

   public static function suite() {

      $GLPIInstall = new GLPIInstall();
      $Install = new Install();
      $GLPIInstall->testInstall();
      $Install->testInstall(0);
      
      $suite = new PHPUnit_Framework_TestSuite('Switchinventory');
      return $suite;
   }
}

?>