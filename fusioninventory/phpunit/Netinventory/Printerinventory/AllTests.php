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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

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

class Printerinventory extends PHPUnit_Framework_TestCase {


   public function testSetModuleInventoryOn() {
      global $DB;
      
      $DB->connect();

      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
         SET `is_active`='1'
         WHERE `modulename`='SNMPQUERY' ";
      $DB->query($query);
      
      $printer = new Printer();
      $a_printers = $printer->find();
      foreach ($a_printers as $id=>$data) {
         $printer->delete(array('id'=>$id), 1);
      }

   }


   public function testSendinventories() {
      global $DB;
      
      $DB->connect();
      
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
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
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
      $input['itemtype'] = 'Printer';
      $input['items_id'] = '1';
      $input['state'] = 1;
      $input['plugin_fusioninventory_agents_id'] = 1;
      $pfTaskjobstate->add($input);
      $input['items_id'] = '2';
      $pfTaskjobstate->add($input);

      $printer1 = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
   <CONTENT>
   <DEVICE>
      <CARTRIDGES>
        <TONERBLACK>56</TONERBLACK>
      </CARTRIDGES>
      <INFO>
        <COMMENTS>HP ETHERNET MULTI-ENVIRONMENT</COMMENTS>
        <ID>81</ID>
        <LOCATION> </LOCATION>
        <MANUFACTURER>Hewlett Packard</MANUFACTURER>
        <MODEL>HP LaserJet P1505n</MODEL>
        <NAME>ARC12-B09-N</NAME>
        <SERIAL>ARC12-B09-N</SERIAL>
        <TYPE>PRINTER</TYPE>
      </INFO>
      <PAGECOUNTERS>
        <BLACK/>  <COLOR/>  <COPYBLACK/>  <COPYCOLOR/>  <COPYTOTAL/>  <FAXTOTAL/>  <PRINTBLACK/>  <PRINTCOLOR/>  <PRINTTOTAL/>  <RECTOVERSO/>  <SCANNED/>  <TOTAL>54679</TOTAL>
      </PAGECOUNTERS>
      <PORTS>
        <PORT>
          <IFNAME>NetDrvr</IFNAME>
          <IFNUMBER>1</IFNUMBER>
          <IFTYPE>6</IFTYPE>
          <IP>10.10.4.20</IP>
          <MAC>00:23:7d:84:fd:d9</MAC>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';


      $networkPort = new NetworkPort();
      $printer = new Printer();
      $a_printers = $printer->find();
      foreach ($a_printers as $data) {
         $printer->delete($data, 1);
      }
      
      // * 1. Create switch 1
      $this->testSendinventory("toto", $printer1, 1);
         
      // * 3. update switch 1
      $this->testSendinventory("toto", $printer1);
         
         // CHECK 1 : Check ip of ports
         $a_ports = $networkPort->find("`name`='NetDrvr'");
         $this->assertEquals(count($a_ports), 1, 'Port of printer not created');
         $a_port = current($a_ports);
         $this->assertEquals($a_port['ip'], "10.10.4.20", 'IP of port NetDrvr not right');
         $this->assertEquals($a_port['mac'], "00:23:7d:84:fd:d9", 'MAC of port NetDrvr not right');
         
         $GLPIlog = new GLPIlogs();
         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

      // * Test modifications of IP of the printer
      $a_printers = $printer->find("`name`='ARC12-B09-N'");
      $a_printer = current($a_printers);
      
      $printer1bis = str_replace('<IP>10.10.4.20</IP>', '<IP>10.10.4.10</IP>', $printer1);
      $this->testSendinventory("toto", $printer1bis);
      $a_ports = $networkPort->find("`name`='NetDrvr'");
      $a_port = current($a_ports);
      $this->assertEquals($a_port['ip'], "10.10.4.10", 'IP of port NetDrvr not right');
         

   }



   function testSendinventory($xmlFile='', $xmlstring='', $create='0') {
      global $DB;
      
      $DB->connect();

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
               if ($child2->TYPE == 'PRINTER') {
                  // Create switch in asset
                  $printer = new Printer();
                  $input = array();
                  if (isset($child2->SERIAL)) {
                     $input['serial']=$child2->SERIAL;
                  } else {
                     $input['name']=$child2->NAME;
                  }
                  $input['entities_id'] = 0;
                  $printer->add($input);
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



class Printerinventory_AllTests  {

   public static function suite() {

      $GLPIInstall = new GLPIInstall();
      $Install = new Install();
      $GLPIInstall->testInstall();
      $Install->testInstall(0);
      
      $suite = new PHPUnit_Framework_TestSuite('Printerinventory');
      return $suite;
   }
}

?>
