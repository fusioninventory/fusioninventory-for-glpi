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

class Netdiscovery extends PHPUnit_Framework_TestCase {


   public function testCountDevicesTasklog() {
      global $DB,$CFG_GLPI;
      
      $plugin = new Plugin();
      $plugin->getFromDBbyDir("fusioninventory");
      $plugin->activate($plugin->fields['id']);
      Plugin::load("fusioninventory");
      
      loadLanguage("en_GB");

      $CFG_GLPI['root_doc'] = "http://127.0.0.1/fusion0.80/";
      
      $pfAgent   = new PluginFusioninventoryAgent();
      $pfIPRange = new PluginFusioninventoryIPRange();
      $pfTask    = new PluginFusioninventoryTask();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      
      // Create agent
      $device_id = "testcomputerfordiscovery-2011-10-28-09-55-42";
      $input = array();
      $input['name '] = $device_id;
      $input['device_id'] = $device_id;
      $agents_id = $pfAgent->add($input);

      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules` 
         SET `is_active` = '1' 
         WHERE `modulename`='NETDISCOVERY'";
      $DB->query($query);
      
      
      $input = array();
      $input['name']     = 'LAN';
      $input['ip_start'] = '192.168.20.1';
      $input['ip_den']   = '192.168.20.254';
      $ipranges_id = $pfIPRange->add($input);
      
      $input = array();
      $input['name'] = 'NETDISCOVERY';
      $input['is_active'] = 1;
      $input['communication'] = 'pull';
      $input['date_scheduled'] = date('Y-m-d')." 00:00:00";
      $tasks_id = $pfTask->add($input);
      
      $input = array();
      $input['plugin_fusioninventory_tasks_id'] = $tasks_id;
      $input['plugins_id'] = PluginFusioninventoryModule::getModuleId("fusinvsnmp");
      $input['method'] = 'netdiscovery';
      $input['definition'] = '[{"PluginFusioninventoryIPRange":"'.$ipranges_id.'"}]';
      $input['action'] = '[{"PluginFusioninventoryAgent":"'.$agents_id.'"}]';
      $pfTaskjob->add($input);
      
      // prepare run task
      $pfTaskjob->cronTaskscheduler();
      
      // Send data to server
      
      $emulatorAgent = new emulatorAgent();
      $emulatorAgent->server_urlpath = "/fusion0.80/plugins/fusioninventory/";
      
      $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <DEVICEID>'.$device_id.'</DEVICEID>
  <QUERY>PROLOG</QUERY>
  <TOKEN>KDCWKQXA</TOKEN>
</REQUEST>';

      $returnAgent = $emulatorAgent->sendProlog($input_xml);
      echo $returnAgent."\n";
      
      
      $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <AGENT>
      <AGENTVERSION>2.1.11-1</AGENTVERSION>
      <START>1</START>
    </AGENT>
    <MODULEVERSION>1.5</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>'.$device_id.'</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';
      
      $returnAgent = $emulatorAgent->sendProlog($input_xml);
      echo $returnAgent."\n";
      
      
      $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <AGENT>
      <NBIP>254</NBIP>
    </AGENT>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>'.$device_id.'</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';
      
      $returnAgent = $emulatorAgent->sendProlog($input_xml);
      echo $returnAgent."\n";
      
      
      $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <ENTITY>0</ENTITY>
      <IP>192.168.20.150</IP>
      <MAC>00:00:00:00:00:00</MAC>
      <NETBIOSNAME>WIFI-BUREAU</NETBIOSNAME>
      <USERSESSION>WIFI-BUREAU</USERSESSION>
      <WORKGROUP>LOCALDOMAIN</WORKGROUP>
    </DEVICE>
    <MODULEVERSION>1.5</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>'.$device_id.'</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';
      
      $returnAgent = $emulatorAgent->sendProlog($input_xml);
      echo $returnAgent."\n";     
      
      
      $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <DNSHOSTNAME>pfsense.localdomain</DNSHOSTNAME>
      <ENTITY>0</ENTITY>
      <IP>192.168.20.1</IP>
    </DEVICE>
    <DEVICE>
      <DNSHOSTNAME>pctest</DNSHOSTNAME>
      <ENTITY>0</ENTITY>
      <IP>192.168.20.3</IP>
    </DEVICE>
    <MODULEVERSION>1.5</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>'.$device_id.'</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';
      
      $returnAgent = $emulatorAgent->sendProlog($input_xml);
      echo $returnAgent."\n"; 

      
      $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <AGENT>
      <END>1</END>
    </AGENT>
    <MODULEVERSION>1.5</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>'.$device_id.'</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';
      
      $returnAgent = $emulatorAgent->sendProlog($input_xml);
      echo $returnAgent."\n";
      
      
      // Verify have correct number of devices found in taskjoblog
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_taskjoblogs`
         WHERE `comment` LIKE '%==fusinvsnmp::2=='"; 
      $result=$DB->query($query);
      $number = 0;
      while ($data=$DB->fetch_array($result)) {
         $split = explode(" ", $data['comment']);
         $number += $split[0];
      }
      
      $this->assertEquals($number, 3, 'Difference devices discovered : '.$number.' instead of 3');
   }   
}



class Netdiscovery_AllTests  {

   public static function suite() {
      
      $GLPIInstall = new GLPIInstall();
      $Install = new Install();
      $GLPIInstall->testInstall();
      $Install->testInstall(0);
    
      $suite = new PHPUnit_Framework_TestSuite('Netdiscovery');
      return $suite;
   }
}

?>