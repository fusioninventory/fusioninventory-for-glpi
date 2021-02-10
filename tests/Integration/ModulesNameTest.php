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

/**
 * This test will check if module name is the same as the reference.
 * It's because we had many names from agent, and depends on agent version
 */

use PHPUnit\Framework\TestCase;

class ModulesNameTest extends TestCase {

   /**
    * @test
    */
   public function matchedRuleComputerinventory() {
      global $DB;

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      $xml = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <ACCESSLOG>
      <LOGDATE>2016-08-15 17:22:22</LOGDATE>
    </ACCESSLOG>
    <BIOS>
      <ASSETTAG>LAPTOP0034</ASSETTAG>
      <BDATE>10/21/2015</BDATE>
      <BMANUFACTURER>Dell Inc.</BMANUFACTURER>
      <BVERSION>A10</BVERSION>
      <MMANUFACTURER>Dell Inc.</MMANUFACTURER>
      <MMODEL>044GCP</MMODEL>
      <MSN>/5H4PRY1/CN1296139D002E/</MSN>
      <SKUNUMBER>Latitude 6430U</SKUNUMBER>
      <SMANUFACTURER>Dell Inc.</SMANUFACTURER>
      <SMODEL>Latitude 6430U</SMODEL>
      <SSN>5H4PRY1</SSN>
    </BIOS>
    <HARDWARE>
      <ARCHNAME>amd64-freebsd-thread-multi</ARCHNAME>
      <CHASSIS_TYPE>Laptop</CHASSIS_TYPE>
      <CHECKSUM>131071</CHECKSUM>
      <DATELASTLOGGEDUSER>Mon Aug 15 17:22</DATELASTLOGGEDUSER>
      <DESCRIPTION>amd64/-1-11-30 23:58:05</DESCRIPTION>
      <DNS>192.168.43.1</DNS>
      <ETIME>4</ETIME>
      <IPADDR>10.0.20.254/10.0.20.1/10.0.20.2/10.0.20.3/10.0.20.4/10.0.20.5/10.0.20.6/10.0.20.7/10.0.20.8/10.0.20.9/10.0.20.10/10.0.20.11/10.0.20.12/10.0.20.13/10.0.20.14/10.0.20.15/192.168.43.151</IPADDR>
      <LASTLOGGEDUSER>ddurieux</LASTLOGGEDUSER>
      <MEMORY>8067</MEMORY>
      <NAME>portdavid</NAME>
      <OSCOMMENTS>FreeBSD 10.3-RELEASE #0 r297264: Fri Mar 25 02:10:02 UTC 2016     root@releng1.nyi.freebsd.org:/usr/obj/usr/src/sys/GENERIC </OSCOMMENTS>
      <OSNAME>freebsd</OSNAME>
      <OSVERSION>10.3-RELEASE</OSVERSION>
      <PROCESSORN>1</PROCESSORN>
      <PROCESSORS>2100</PROCESSORS>
      <PROCESSORT>Core i7</PROCESSORT>
      <SWAP>4096</SWAP>
      <USERID>ddurieux</USERID>
      <UUID>4C4C4544-0048-3410-8050-B5C04F525931</UUID>
      <VMSYSTEM>Physical</VMSYSTEM>
    </HARDWARE>
    <CONTROLLERS>
      <CAPTION>VMXNET3 Ethernet Controller</CAPTION>
      <MANUFACTURER>VMware</MANUFACTURER>
      <NAME>VMXNET3 Ethernet Controller</NAME>
      <PCISUBSYSTEMID>15ad:07b0</PCISUBSYSTEMID>
      <PRODUCTID>07b0</PRODUCTID>
      <TYPE>vmxnet3 Ethernet Adapter</TYPE>
      <VENDORID>15ad</VENDORID>
    </CONTROLLERS>
    <NETWORKS>
      <DESCRIPTION>vmxnet3 Ethernet Adapter</DESCRIPTION>
      <IPADDRESS>10.225.4.79</IPADDRESS>
      <IPGATEWAY>10.225.4.254</IPGATEWAY>
      <IPMASK>255.255.255.0</IPMASK>
      <IPSUBNET>10.225.4.0</IPSUBNET>
      <MACADDR>00:50:56:BC:0C:90</MACADDR>
      <PCIID>15AD:07B0:07B0:15AD</PCIID>
      <PNPDEVICEID>PCI\VEN_15AD&amp;DEV_07B0&amp;SUBSYS_07B015AD&amp;REV_01\4&amp;21C36F57&amp;0&amp;00A8</PNPDEVICEID>
      <STATUS>Up</STATUS>
      <TYPE>ethernet</TYPE>
      <VIRTUALDEV>0</VIRTUALDEV>
    </NETWORKS>
    <SOFTWARES>
      <COMMENTS>PHP Scripting Language</COMMENTS>
      <NAME>php70</NAME>
      <VERSION>7.0.14</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Robust and small WWW server</COMMENTS>
      <NAME>nginx</NAME>
      <VERSION>1.10.2_3,2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Port scanning utility for large networks</COMMENTS>
      <NAME>nmap</NAME>
      <VERSION>7.40</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Network file distribution/synchronization utility</COMMENTS>
      <NAME>rsync</NAME>
      <VERSION>3.1.2_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Object-oriented interpreted scripting language</COMMENTS>
      <NAME>ruby</NAME>
      <VERSION>2.3.3_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>WiFi Networks Manager</COMMENTS>
      <NAME>wifimgr</NAME>
      <VERSION>1.11_2</VERSION>
    </SOFTWARES>
  </CONTENT>
  <DEVICEID>portdavid-2016-08-15-17-22-21</DEVICEID>
  <QUERY>INVENTORY</QUERY>
</REQUEST>';

      $pfCommunication  = new PluginFusioninventoryCommunication();

      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_rulematchedlogs`");
      $pfCommunication->handleOCSCommunication('', $xml, 'glpi');

      $iterator = $DB->request([
         'FROM'   => 'glpi_plugin_fusioninventory_rulematchedlogs',
      ]);

      $this->assertEquals(1, countElementsInTable('glpi_plugin_fusioninventory_rulematchedlogs'));
      while ($data = $iterator->next()) {
         $this->assertEquals('inventory', $data['method']);
      }

      // second run (update)
      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_rulematchedlogs`");
      $pfCommunication->handleOCSCommunication('', $xml, 'glpi');

      $iterator = $DB->request([
         'FROM'   => 'glpi_plugin_fusioninventory_rulematchedlogs',
      ]);

      $this->assertEquals(1, countElementsInTable('glpi_plugin_fusioninventory_rulematchedlogs'));
      while ($data = $iterator->next()) {
         $this->assertEquals('inventory', $data['method']);
      }

   }

   /**
    * @test
    */
   public function matchedRuleNetworkinventory() {
      global $DB;

      $xml = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
   <CONTENT>
      <DEVICE>
         <INFO>
           <COMMENTS>ProCurve J9085A</COMMENTS>
           <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
           <ID>123</ID>
           <IPS>
             <IP>192.168.1.56</IP>
             <IP>192.168.10.56</IP>
           </IPS>
           <LOCATION>BAT A - Niv 3</LOCATION>
           <MAC>b4:39:d6:3a:7f:00</MAC>
           <MODEL>J9085A</MODEL>
           <NAME>FR-SW01</NAME>
           <SERIAL>CN536H7J</SERIAL>
           <TYPE>NETWORKING</TYPE>
           <UPTIME>8 days, 01:48:57.95</UPTIME>
         </INFO>
         <PORTS>
           <PORT>
             <CONNECTIONS>
               <CONNECTION>
                 <MAC>00:40:9d:3b:7f:c4</MAC>
               </CONNECTION>
             </CONNECTIONS>
             <IFDESCR>3</IFDESCR>
             <IFNAME>3</IFNAME>
             <IFNUMBER>3</IFNUMBER>
             <IFSTATUS>1</IFSTATUS>
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
   </CONTENT>
  <DEVICEID>portdavid-2016-08-15-17-22-21</DEVICEID>
  <QUERY>MODULENAMETEST</QUERY>
</REQUEST>';

      $pfCommunication  = new PluginFusioninventoryCommunication();
      $versions = ['SNMPQUERY', 'SNMPINVENTORY'];
      foreach ($versions as $versionName) {
         $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_rulematchedlogs`");
         $xmlNew = str_replace('MODULENAMETEST', $versionName, $xml);
         $pfCommunication->handleOCSCommunication('', $xmlNew, 'glpi');

         $iterator = $DB->request([
            'FROM'   => 'glpi_plugin_fusioninventory_rulematchedlogs',
         ]);
         $this->assertEquals(2, count($iterator), 'Must have 2, 1 for the switch and 1 for the device found on port');
         while ($data = $iterator->next()) {
            $this->assertEquals("networkinventory", $data['method'], "Problem with agent query: ".$versionName);
         }
      }
   }

   /**
    * @test
    */
   public function matchedRuleNetworkdiscovery() {
      global $DB;

      $xml = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <AUTHSNMP>1</AUTHSNMP>
      <DESCRIPTION>GS724TS</DESCRIPTION>
      <DNSHOSTNAME>SW-1</DNSHOSTNAME>
      <FIRMWARE>V5.2.0.11</FIRMWARE>
      <IP>192.168.0.2</IP>
      <IPS>
        <IP>0.255.255.2</IP>
        <IP>192.168.0.2</IP>
      </IPS>
      <LOCATION>RACKMOUNTA</LOCATION>
      <MAC>00:1e:2a:4b:00:01</MAC>
      <MANUFACTURER>Netgear</MANUFACTURER>
      <SERIAL>OIF4DG73H</SERIAL>
      <SNMPHOSTNAME>SW-1</SNMPHOSTNAME>
      <TYPE>NETWORKING</TYPE>
      <UPTIME>310 days, 10:10:26.00</UPTIME>
    </DEVICE>
    <MODULEVERSION>2.6</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>computer1</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';

      $pfCommunication  = new PluginFusioninventoryCommunication();
      $computer        = new Computer();
      $pfAgent         = new PluginFusioninventoryAgent();
      $pfTask          = new PluginFusioninventoryTask();
      $pfTaskjob       = new PluginFusioninventoryTaskjob;
      $pfIPRange       = new PluginFusioninventoryIPRange();
      $pfTaskjobstate  = new PluginFusioninventoryTaskjobstate();

      // Create computers + agents
      $input = [
          'entities_id' => 0,
          'name'        => 'computer1'
      ];
      $computers_id = $computer->add($input);

      $input = [
          'entities_id' => 0,
          'name'        => 'computer1',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer1',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      ];
      $pfAgent->add($input);

      // Add IPRange
      $input = [
          'entities_id' => 0,
          'name'        => 'Office',
          'ip_start'    => '10.0.0.1',
          'ip_end'      => '10.0.0.254'
      ];
      $ipranges_id = $pfIPRange->add($input);

      // Allow all agents to do network discovery
      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules` "
              . " SET `is_active`='1' "
              . " WHERE `modulename`='NETWORKDISCOVERY'";
      $DB->query($query);

      // create task
      $input = [
          'entities_id' => 0,
          'name'        => 'network discovery',
          'is_active'   => 1
      ];
      $tasks_id = $pfTask->add($input);

      // create taskjob
      $input = [
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'discovery',
          'method'                          => 'networkdiscovery',
          'targets'                         => '[{"PluginFusioninventoryIPRange":"'.$ipranges_id.'"}]',
          'actors'                          => '[{"PluginFusioninventoryAgent":"'.$pfAgent->fields['id'].'"}]'
      ];
      $pfTaskjob->add($input);

      // Prepare job
      PluginFusioninventoryTask::cronTaskscheduler();

      $jobstates = $pfTaskjobstate->find([], [], 1);
      foreach ($jobstates as $jobstate) {
         $xml = str_replace("<PROCESSNUMBER>1</PROCESSNUMBER>", "<PROCESSNUMBER>".$jobstate['id']."</PROCESSNUMBER>", $xml);
      }

      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_rulematchedlogs`");
      $pfCommunication->handleOCSCommunication('', $xml, 'glpi');

      $iterator = $DB->request([
         'FROM'   => 'glpi_plugin_fusioninventory_rulematchedlogs',
      ]);
      $this->assertEquals(1, count($iterator));
      while ($data = $iterator->next()) {
         $this->assertEquals('networkdiscovery', $data['method']);
      }
   }
}
