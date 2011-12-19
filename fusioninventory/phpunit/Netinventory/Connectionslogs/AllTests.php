<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

class Connectionslogs extends PHPUnit_Framework_TestCase {


   public function testSetModuleInventoryOff() {
      global $DB;

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
      global $DB;
      
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
            <CONNECTION>
              <MAC>00:23:18:cf:0d:93</MAC>
            </CONNECTION>
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

      $networkPort = new NetworkPort();
      $networkPort_NetworkPort = new NetworkPort_NetworkPort();
      $pluginFusinvsnmpNetworkPortConnectionLog = new PluginFusinvsnmpNetworkPortConnectionLog();

      // * 1. Create switch 1 with a connection
      $this->testSendinventory("toto", $switch1, 1);
         // CHECK 1 : Get connection created in GLPI
         $a_ports = $networkPort->find("`mac`='00:1a:6c:9a:fc:85'");
         $this->assertEquals(count($a_ports), 1, '(1) switch 1 have not port created in GLPI');
         $a_port = current($a_ports);
         $ret = $networkPort_NetworkPort->getFromDBForNetworkPort($a_port['id']);
         $this->assertEquals($ret, true, '(1) Port 1 of switch 1 not connected in GLPI');
         // Check 2 : Get if portconnectionlog is added
         $a_ports = $networkPort->find("`mac`='00:23:18:cf:0d:93'");
         $a_uport = current($a_ports);
         $a_logs = $pluginFusinvsnmpNetworkPortConnectionLog->find("
            `creation`=1 
            AND ((`networkports_id_source`='".$a_port['id']."'
                  AND `networkports_id_destination`='".$a_uport['id']."')
                OR (`networkports_id_source`='".$a_uport['id']."'
                  AND `networkports_id_destination`='".$a_port['id']."'))");
         $this->assertEquals(count($a_logs), 1, '(1) Connection log not created');
         $DB->query("DELETE FROM `glpi_plugin_fusinvsnmp_networkportconnectionlogs`");
         
         
      // * 2. Create switch 2 with connection of mac on switch 1 previously
      $this->testSendinventory("toto", $switch2, 1);
         // CHECK 1 : Get connection deleted in GLPI
         $ret = $networkPort_NetworkPort->getFromDBForNetworkPort($a_port['id']);
         $this->assertEquals($ret, false, '(2) Port 1 of switch 1 is yet connected in GLPI');
         $a_ports = $networkPort->find("`mac`='00:1a:6c:9a:fa:85'");
         $this->assertEquals(count($a_ports), 1, '(2) switch 2 have not port created in GLPI');
         $a_port1sw2 = current($a_ports);
         // CHECK 2 : Get if (remove) portconnectionlog is added
         $a_logs = $pluginFusinvsnmpNetworkPortConnectionLog->find("
            `creation`=0 
            AND ((`networkports_id_source`='".$a_port['id']."'
                  AND `networkports_id_destination`='".$a_uport['id']."')
                OR (`networkports_id_source`='".$a_uport['id']."'
                  AND `networkports_id_destination`='".$a_port['id']."'))");
         $this->assertEquals(count($a_logs), 1, '(2) Remove connection log not created');
         // CHECK 3 : Get connection created in GLPI
         $a_logs = $pluginFusinvsnmpNetworkPortConnectionLog->find("
            `creation`=1 
            AND ((`networkports_id_source`='".$a_port1sw2['id']."'
                  AND `networkports_id_destination`='".$a_uport['id']."')
                OR (`networkports_id_source`='".$a_uport['id']."'
                  AND `networkports_id_destination`='".$a_port1sw2['id']."'))");
         $this->assertEquals(count($a_logs), 1, '(2) Connection log not created 
               ('.$a_port1sw2['id'].' => '.$a_uport['id'].')');
         $DB->query("DELETE FROM `glpi_plugin_fusinvsnmp_networkportconnectionlogs`");
      
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



class Connectionslogs_AllTests  {

   public static function suite() {

      $GLPIInstall = new GLPIInstall();
      $Install = new Install();
      $GLPIInstall->testInstall();
      $Install->testInstall(0);
      
      $CFG_GLPI['root_doc'] = "http://127.0.0.1/fusion0.83/";
      
      $suite = new PHPUnit_Framework_TestSuite('Connectionslogs');
      return $suite;
   }
}
?>
