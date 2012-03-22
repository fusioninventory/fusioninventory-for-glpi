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

class InventoryComputer extends PHPUnit_Framework_TestCase {


    public function testSetModuleInventoryOff() {
       global $DB,$LANG,$CFG_GLPI;
       
       $plugin = new Plugin();
       $plugin->getFromDBbyDir("fusioninventory");
       $plugin->activate($plugin->fields['id']);
       Plugin::load("fusioninventory");
       $plugin->getFromDBbyDir("fusinvinventory");
       $plugin->activate($plugin->fields['id']);
       Plugin::load("fusinvinventory");

       
       loadLanguage("en_GB");

       $CFG_GLPI['root_doc'] = "http://127.0.0.1/fusion0.80/";
         //deleteDir(GLPI_ROOT."/files/_plugins/fusioninventory/criterias");
         //deleteDir(GLPI_ROOT."/files/_plugins/fusioninventory/machines");
         system("rm -fr ".GLPI_ROOT."/files/_plugins/fusioninventory/criterias");
         system("rm -fr ".GLPI_ROOT."/files/_plugins/fusioninventory/machines");


         // Add in blacklit : 30003000000000000000000000300000000000000000000000000000000000000000000000000000000000
         $PluginFusinvinventoryBlacklist = new PluginFusinvinventoryBlacklist();
         $input = array();
         $input['plugin_fusioninventory_criterium_id'] = '1';
         $input['value'] = '30003000000000000000000000300000000000000000000000000000000000000000000000000000000000';
         $PluginFusinvinventoryBlacklist->add($input);


        // set in config module inventory = yes by default
        $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
           SET `is_active`='0'
           WHERE `modulename`='INVENTORY' ";
        $result = $DB->query($query);

        // Activate Extra-debug
         $plugin = new Plugin();
         $data = $plugin->find("`name` = 'FusionInventory'");
         $fields = current($data);
         $plugins_id = $fields['id'];
         $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
         $PluginFusioninventoryConfig->updateConfigType($plugins_id, "extradebug", "1");
       
    }


//    public function testSendinventoryOff() {
//       $this->testSendinventory();
//    }
//
//
//   public function testMachinesCriteriasFoldersOff() {
//      $exist = 0;
//      if (file_exists(GLPI_ROOT."/files/_plugins/fusioninventory/machines")) {
//         $exist = 1;
//      }
//      $this->assertEquals($exist, 0, 'Problem on inventory, machines & criterias folder must not create because inventory not allowed on this agent');
//   }



    public function testSetModuleInventoryOn() {
       global $DB;
       
        // set in config module inventory = yes by default
        $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
           SET `is_active` = '1'
           WHERE `modulename` = 'INVENTORY'";
        $result = $DB->query($query);
     }

     

    public function testSendinventories() {
      
      $MyDirectory = opendir("InventoryComputer/xml");
      $k = 0;
      while(false !== ($Entry = readdir($MyDirectory))) {
         if(is_dir('InventoryComputer/xml/'.$Entry)&& $Entry != '.' && $Entry != '..') {
            $myVersion = opendir("InventoryComputer/xml/".$Entry);
            while(false !== ($xmlFilename = readdir($myVersion))) {
               if ($xmlFilename != '.' && $xmlFilename != '..') {
                  // We have the XML of each computer inventory
                  $xml = simplexml_load_file("InventoryComputer/xml/".$Entry."/".$xmlFilename, 'SimpleXMLElement', LIBXML_NOCDATA);
                  if ($xml->asXML()) {
echo "************************\n";
echo "Memory : ".memory_get_usage()." / ".memory_get_peak_usage()."\n";
                     $deviceid_ok = 0;
                     if (!empty($xml->DEVICEID)) {
                        $deviceid_ok = 1;
                     }
                     $this->assertEquals($deviceid_ok, 1, 'Problem on XML, DEVICEID of file InventoryComputer/xml/'.$Entry.'/'.$xmlFilename.' not good!');

                     $inputProlog = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <DEVICEID>'.$xml->DEVICEID.'</DEVICEID>
  <QUERY>PROLOG</QUERY>
  <TOKEN>NTMXKUBJ</TOKEN>
</REQUEST>';
echo "# testProlog\n";
                     $this->testProlog($inputProlog, $xml->DEVICEID);
                     $k++;
echo "# testSendinventory\n";
                     $array = $this->testSendinventory("InventoryComputer/xml/".$Entry."/".$xmlFilename, $xml);
                     $items_id = $array[0];
                     $unknown  = $array[1];
echo "# testPrinter\n";
                     $this->testPrinter("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testMonitor\n";
                     $this->testMonitor("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testCPU\n";
                     $this->testCPU("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testDrive\n";
                     $this->testDrive("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testController\n";
                     $this->testController("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testSound\n";
                     $this->testSound("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testVideo\n";
                     $this->testVideo("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testMemory\n";
                     $this->testMemory("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testNetwork\n";
                     $this->testNetwork($xml, $items_id, $unknown, "InventoryComputer/xml/".$Entry."/".$xmlFilename);
echo "# testSoftware\n";
                     $this->testSoftware("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testHardware\n";
                     $this->testHardware("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id, $unknown);
echo "# testHardwareModifications\n";
                     $this->testHardwareModifications("InventoryComputer/xml/".$Entry."/".$xmlFilename, $items_id);
                     echo "Number of files : ".$k."\n";
                     
                     $GLPIlog = new GLPIlogs();
                     $GLPIlog->testSQLlogs();
                     $GLPIlog->testPHPlogs();
                  }
               }
            }
         }
      }      
   }

   

//   public function testMachinesCriteriasFolders() {
//      $exist = 0;
//      if (file_exists(GLPI_ROOT."/files/_plugins/fusioninventory/machines")) {
//         $exist = 1;
//      }
//      $this->assertEquals($exist, 1, 'Problem on inventory, machines & criterias folder not create successfully!');
//   }


   function testProlog($inputXML='', $deviceID='') {
      global $DB;

      if (empty($inputXML)) {
         echo "testProlog with no arguments...\n";
         return;
      }
      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/fusion0.80/plugins/fusioninventory/";
      $prologXML = $emulatorAgent->sendProlog($inputXML);
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $a_agent = $PluginFusioninventoryAgent->find("`device_id`='".$deviceID."'");
      $this->assertEquals(count($a_agent), 1, 'Problem on prolog, agent ('.$deviceID.') not right created!');

      $this->assertEquals(preg_match("/<RESPONSE>SEND<\/RESPONSE>/", $prologXML), 1, 'Prolog not send to agent!');
   }

   function testSendinventory($xmlFile='', $xml='') {
      
      if (empty($xmlFile)) {
         echo "testSendinventory with no arguments...\n";
         return;
      }

      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/fusion0.80/plugins/fusioninventory/";
      echo "====================\n";
      echo $xmlFile."\n";
      $input_xml = $xml->asXML();
      $returnAgent = $emulatorAgent->sendProlog($input_xml);
      echo $returnAgent."\n";
      
      $Computer = new Computer();
//      $xml = simplexml_load_file($xmlFile,'SimpleXMLElement', LIBXML_NOCDATA);
      if (isset($xml->CONTENT->BIOS->SSN)) {
         if ($xml->CONTENT->BIOS->SSN == '30003000000000000000000000300000000000000000000000000000000000000000000000000000000000') {
            unset($xml->CONTENT->BIOS->SSN);
         } else if ($xml->CONTENT->BIOS->SSN == 'To Be Filled By O.E.M.') {
            unset($xml->CONTENT->BIOS->SSN);
         } else {
            $xml->CONTENT->BIOS->SSN = trim($xml->CONTENT->BIOS->SSN);
         }
      }
      $serial = "`serial` IS NULL";
      if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
         $serial = "`serial`='".$xml->CONTENT->BIOS->SSN."'";
      }
      $a_computers = $Computer->find("`name`='".$xml->CONTENT->HARDWARE->NAME."' AND ".$serial);
      $unknown = 0;
      if (count($a_computers) == 0) {
         // Search in unknown device
         $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
         $a_computers = $PluginFusioninventoryUnknownDevice->find("`name`='".$xml->CONTENT->HARDWARE->NAME."'");
         $unknown = 1;
      }
      $this->assertEquals(count($a_computers), 1, 'Problem on creation computer, not created ('.$xmlFile.')');
      foreach($a_computers as $items_id => $data) {
         return array($items_id, $unknown);
      }
   }


   function testPrinter($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testPrinter with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         return;
      }

      $Computer = new Computer();
      $Printer  = new Printer();

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      if (!isset($xml->CONTENT->PRINTERS)) {
         return;
      }
      // Verify not have 2 printer in DB with same printer serial
      foreach ($xml->CONTENT->PRINTERS as $child) {
         if (isset($child->SERIAL)) {
            $child->SERIAL = preg_replace('/\/$/', '', (string)$child->SERIAL);
            $a_printer = $Printer->find("`serial`='".$child->SERIAL."'");
            $this->assertEquals(count($a_printer), 1, 'Problem on printers, printer created "'.count($a_printer).'" instead 1 times (serial : '.$child->SERIAL.')');
         }         
      }
      // Verify all printers are connected to the computer
         // Get all printers connected to computer in DB
         $query = "SELECT * FROM `glpi_computers_items`
                  INNER JOIN `glpi_printers` on `glpi_printers`.`id`=`items_id`
                      WHERE `computers_id` = '".$items_id."'
                            AND `itemtype` = 'Printer'";
         $result=$DB->query($query);
         $a_printerDB = array();
         while ($data=$DB->fetch_array($result)) {
            $a_printerDB["'".$data['name']."'"] = 1;
         }
         // Verify printers in XML
         $a_printerXML = array();
         foreach ($xml->CONTENT->PRINTERS as $child) {
            $a_printerXML["'".(string)$child->NAME."'"] = 1;
         }
         // Display (test) differences
         $a_printerDiff = array();
         $a_printerDiff = array_diff_key($a_printerDB, $a_printerXML);
         if (count($a_printerDiff) < count(array_diff_key($a_printerXML, $a_printerDB))) {
            $a_printerDiff = array_diff_key($a_printerXML, $a_printerDB);
         }
         $this->assertEquals(count($a_printerDiff), 0, 'Difference of printers "'.print_r($a_printerDiff, true).'" ['.$xmlFile.']');


         // Verify fields in GLPI
         foreach($xml->CONTENT->PRINTERS as $child) {
            if (isset($child->SERIAL)) {
               $a_printer = $Printer->find("`serial`='".$child->SERIAL."' ");
               foreach ($a_printer as $printer_id => $datas) {
                  if (isset($child->NAME)) {
                     $this->assertEquals(trim($child->NAME), $datas['name'], 'Difference of printers fields ['.$xmlFile.']');
                  } else if (isset($child->DRIVER)) {
                     $this->assertEquals($child->DRIVER, $datas['name'], 'Difference of printers fields ['.$xmlFile.']');
                  }
                  if (strstr($child->PORT, "USB")) {
                     $this->assertEquals("1", $datas['have_usb'], 'Difference of printers fields ['.$xmlFile.']');
                  }
                  // Find in USBDEVICES to find manufacturer
                  foreach($xml->CONTENT->USBDEVICES as $childusb) {
                     if (isset($childusb->SERIAL)) {
                        if (file_exists(GLPI_ROOT."/files/_plugins/fusioninventory/DataFilter/usbids/".strtolower($childusb->VENDORID)."/".strtolower($childusb->PRODUCTID)."info")) {
                           $info = file_get_contents(GLPI_ROOT."/files/_plugins/fusioninventory/DataFilter/usbids/".strtolower($childusb->VENDORID)."/".strtolower($childusb->PRODUCTID)."info");
                           $array = explode("\n", $info);
                           $manufacturer_id = Dropdown::importExternal('Manufacturer', $array[0]);
                           $this->assertEquals($manufacturer_id, $datas['manufacturers_id'], 'Difference of printers fields ['.$xmlFile.']');
                        }
                     }
                  }
               }
            } else {
               $query = "SELECT `glpi_printers`.* FROM `glpi_computers_items`
                        INNER JOIN `glpi_printers` on `glpi_printers`.`id`=`items_id`
                        WHERE `computers_id` = '".$items_id."'
                            AND `itemtype` = 'Printer'";
               $result=$DB->query($query);
               $printer_select = array();
               while ($data=$DB->fetch_array($result)) {
                  if (count($printer_select) == '0') {
                     if ((isset($child->NAME)) AND ($data['name'] == $child->NAME)) {
                        $printer_select = $data;
//                     } else if ((isset($child->DRIVER)) AND ($data['name'] == $child->DRIVER)) {
//                        $printer_select = $data;
                     }
                  }
               }
               $this->assertEquals(count($printer_select['id']), "1", 'Problem to find printer for fields verification ['.$xmlFile.']');
               if (strstr($child->PORT, "USB")) {
                  $this->assertEquals("1", $printer_select['have_usb'], 'Difference of printers fields ['.$xmlFile.']');
               }
            }

         }
   }


   function testMonitor($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testMonitor with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         return;
      }

      $Computer = new Computer();
      $Monitor  = new Monitor();

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      if (!isset($xml->CONTENT->MONITORS)) {
         return;
      }

      // Verify not have 2 monitor in DB with same printer serial
      foreach ($xml->CONTENT->MONITORS as $child) {
         if (isset($child->SERIAL)) {
            $a_monitor = $Monitor->find("`serial`='".$child->SERIAL."'");
            $this->assertEquals(count($a_monitor), 1, 'Problem on monitors, monitor created "'.count($a_monitor).'" instead 1 times [serial:'.$child->SERIAL.']');
         }
      }

      // Verify all monitors are connected to the computer
         // Get all monitors connected to computer in DB
         $query = "SELECT * FROM `glpi_computers_items`
                  INNER JOIN `glpi_monitors` on `glpi_monitors`.`id`=`items_id`
                      WHERE `computers_id` = '".$items_id."'
                            AND `itemtype` = 'Monitor'";
         $result=$DB->query($query);
         $a_monitorDB = array();
         while ($data=$DB->fetch_array($result)) {
            $a_monitorDB["'".$data['name']."'"] = 1;
         }
         // Verifiy monitors in XML
         $a_monitorXML = array();
         foreach ($xml->CONTENT->MONITORS as $child) {
            $a_monitorXML["'".$child->CAPTION."'"] = 1;
         }
         // Display (test) differences
         $a_monitorDiff = array();
         $a_monitorDiff = array_diff_key($a_monitorDB, $a_monitorXML);
         if (count($a_monitorDiff) < count(array_diff_key($a_monitorXML, $a_monitorDB))) {
            $a_monitorDiff = array_diff_key($a_monitorXML, $a_monitorDB);
         }
         $this->assertEquals(count($a_monitorDiff), 0, 'Difference of monitors "'.print_r($a_monitorDiff, true).'"');

   }


   function testCPU($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testCPU with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         return;
      }

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      if (!isset($xml->CONTENT->CPUS)) {
         return;
      }

      $a_cpuXML = array();
      $i = 0;
      foreach ($xml->CONTENT->CPUS as $child) {
         if (isset($child->NAME)) {
            $a_cpuXML["'".$i."-".$child->NAME."'"] = 1;
            $i++;
         } else if (isset($child->TYPE)) {
            $a_cpuXML["'".$i."-".$child->TYPE."'"] = 1;
            $i++;
         }
      }

      $Computer = new Computer();
      $query = "SELECT * FROM `glpi_computers_deviceprocessors`
         WHERE `computers_id`='".$items_id."' ";
      $result=$DB->query($query);

      $this->assertEquals($DB->numrows($result), count($a_cpuXML), 'Difference of CPUs, created '.$DB->numrows($result).' times instead '.count($a_cpuXML).' ['.$xmlFile.']');
   }



   function testDrive($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testDrive with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         return;
      }

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      if (!isset($xml->CONTENT->DRIVE)) {
         return;
      }

      $a_driveXML = array();
      $i = 0;
      foreach ($xml->CONTENT->DRIVES as $child) {
         if (isset($child->CAPTION)) {
            $a_driveXML["'".$i."-".$child->CAPTION."'"] = 1;
            $i++;
         }
      }

      $Computer = new Computer();
      $query = "SELECT * FROM `glpi_computerdisks`
         WHERE `computers_id`='".$items_id."' ";
      $result=$DB->query($query);

      $this->assertEquals($DB->numrows($result), count($a_driveXML), 'Difference of Drives, created '.$DB->numrows($result).' times instead '.count($a_driveXML).' ['.$xmlFile.']');
   }


   function testController($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testController with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         return;
      }

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      if (!isset($xml->CONTENT->CONTROLLERS)) {
         return;
      }

      // Controller to ignore
      $ignore_controllers = array();
      foreach ($xml->CONTENT->VIDEOS as $child) {
         $ignore_controllers["'".$child->NAME."'"] = 1;
      }
      foreach ($xml->CONTENT->SOUNDS as $child) {
         $ignore_controllers["'".$child->NAME."'"] = 1;
      }

      $a_controllerXML = array();
      $i = 0;
      foreach ($xml->CONTENT->CONTROLLERS as $child) {
         if ((isset($child->NAME)) 
                 AND (!isset($ignore_controllers["'".$child->NAME."'"]))) {
            if (!(isset($child->NAME)
                    AND isset($child->CAPTION)
                    AND isset($child->TYPE)
                    AND empty($child->NAME)
                    AND empty($child->CAPTION)
                    AND empty($child->TYPE))) {
               
               $a_controllerXML["'".$i."-".$child->NAME."'"] = 1;
               $i++;
            }
         }
      }

      $Computer = new Computer();
      $query = "SELECT * FROM `glpi_computers_devicecontrols`
         WHERE `computers_id`='".$items_id."' ";
      $result=$DB->query($query);

      $this->assertEquals($DB->numrows($result), count($a_controllerXML), 'Difference of Controllers, created '.$DB->numrows($result).' times instead '.count($a_controllerXML).' ['.$xmlFile.']');
   }


   function testSound($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testSound with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         return;
      }

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      if (!isset($xml->CONTENT->SOUNDS)) {
         return;
      }

      $a_soundXML = array();
      $i = 0;
      foreach ($xml->CONTENT->SOUNDS as $child) {
         if (isset($child->NAME)) {
            $a_soundXML["'".$i."-".$child->NAME."'"] = 1;
            $i++;
         }
      }

      $Computer = new Computer();
      $query = "SELECT * FROM `glpi_computers_devicesoundcards`
         WHERE `computers_id`='".$items_id."' ";
      $result=$DB->query($query);

      $this->assertEquals($DB->numrows($result), count($a_soundXML), 'Difference of Sounds, created '.$DB->numrows($result).' times instead '.count($a_soundXML).' ['.$xmlFile.']');
   }


  function testVideo($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testVideo with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         return;
      }

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      if (!isset($xml->CONTENT->VIDEOS)) {
         return;
      }

      $a_videoXML = array();
      $i = 0;
      foreach ($xml->CONTENT->VIDEOS as $child) {
         if (isset($child->NAME)) {
            $a_videoXML["'".$i."-".$child->NAME."'"] = 1;
            $i++;
         }
      }

      $Computer = new Computer();
      $query = "SELECT * FROM `glpi_computers_devicegraphiccards`
         WHERE `computers_id`='".$items_id."' ";
      $result=$DB->query($query);

      $this->assertEquals($DB->numrows($result), count($a_videoXML), 'Difference of Videos, created '.$DB->numrows($result).' times instead '.count($a_videoXML).' ['.$xmlFile.']');
   }


  function testMemory($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testMemory with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         return;
      }

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      if (!isset($xml->CONTENT->MEMORIES)) {
         return;
      }

      $a_memoryXML = array();
      $i = 0;
      foreach ($xml->CONTENT->MEMORIES as $child) {
         if (isset($child->CAPTION)
                 AND ctype_digit((string)$child->CAPACITY)
                 AND (isset($child->TYPE)
                         AND !preg_match('/Flash/', (string)$child->TYPE))) {
            $a_memoryXML["'".$i."-".$child->CAPTION."'"] = 1;
            $i++;
         }
      }

      $Computer = new Computer();
      $query = "SELECT * FROM `glpi_computers_devicememories`
         WHERE `computers_id`='".$items_id."' ";
      $result=$DB->query($query);

      $this->assertEquals($DB->numrows($result), count($a_memoryXML), 'Difference of Memories, created '.$DB->numrows($result).' times instead '.count($a_memoryXML).' ['.$xmlFile.']');
   }



  function testNetwork($xml='', $items_id=0, $unknown=0, $xmlFile='') {
      global $DB;

      if (empty($xml)) {
         echo "testNetwork with no arguments...\n";
         return;
      }
      
      $pfBlacklist = new PluginFusinvinventoryBlacklist();
     
      if (!isset($xml->CONTENT->NETWORKS)) {
         return;
      }

      $a_networkXML = array();
      $i = 0;
      foreach ($xml->CONTENT->NETWORKS as $child) {
         if (isset($child->DESCRIPTION)) {
            $a_networkXML["'".$i."-".$child->DESCRIPTION."'"] = 1;
            $i++;
         } else if (isset($child->IPADDRESS)) {
            $a_networkXML["'".$i."-".$child->IPADDRESS."'"] = 1;
            $i++;
         }
      }

      $Computer = new Computer();
      $itemtype = "Computer";
      $query = "SELECT * FROM `glpi_networkports`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='Computer'";
      if ($unknown == '1') {
         $itemtype = "PluginFusioninventoryUnknownDevice";
         $query = "SELECT * FROM `glpi_networkports`
            WHERE `items_id`='".$items_id."'
               AND `itemtype`='PluginFusioninventoryUnknownDevice'";
      }
      $result=$DB->query($query);

      $this->assertEquals($DB->numrows($result), count($a_networkXML), 'Difference of Networks, created '.$DB->numrows($result).' times instead '.count($a_networkXML).' ['.$xmlFile.'], '.$query);

      foreach ($xml->CONTENT->NETWORKS as $child) {
         $regs = array();
         preg_match("/([0-9a-fA-F]{1,2}([:-]|$)){6}$/", (string)$child->MACADDR, $regs);
         if (empty($regs)) {
            unset($child->MACADDR);
         }
         if ((isset($child->MACADDR)) AND (!empty($child->MACADDR))) {

            $a_found = $pfBlacklist->find("`value`='".(string)$child->MACADDR."'
               AND `plugin_fusioninventory_criterium_id`='3'");
            if (count($a_found) == '0') {            
               $query = "SELECT * FROM `glpi_networkports`
               WHERE `items_id`='".$items_id."'
                  AND `itemtype`='".$itemtype."'
                  AND `mac`='".(string)$child->MACADDR."'";
               $result=$DB->query($query);
               $data = $DB->fetch_array($result);
               $this->assertEquals($data['mac'], (string)$child->MACADDR, 'Network port macaddress not right inserted, have '.$data['mac'].' instead '.(string)$child->MACADDR.' ['.$xmlFile.']');
            }
         }
      }

   }



   function testSoftware($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testSoftware with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         return;
      }

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      $sxml_soft = $xml->CONTENT->addChild('SOFTWARES');
      $sxml_soft->addChild('COMMENTS', (string)$xml->CONTENT->HARDWARE->OSCOMMENTS);
      $sxml_soft->addChild('NAME', (string)$xml->CONTENT->HARDWARE->OSNAME);
      $sxml_soft->addChild('VERSION', (string)$xml->CONTENT->HARDWARE->OSVERSION);
      
      if (!isset($xml->CONTENT->SOFTWARES)) {
         return;
      }

      $a_softwareXML = array();
      $i = 0;
      $soft = array();
      foreach ($xml->CONTENT->SOFTWARES as $child) {
         if (!isset($child->VERSION)) {
            $child->VERSION = "N/A";
         }         
         if (isset($child->NAME)) {
            if (!isset($soft[(string)$child->NAME."-".(string)$child->VERSION])) {
               $a_softwareXML["'".$i."-".(string)$child->NAME."'"] = 1;
               $i++;
               $soft[(string)$child->NAME."-".(string)$child->VERSION] = 1;
            }
         } else if (isset($child->GUID)) {
            if (!isset($soft[(string)$child->GUID."-".(string)$child->VERSION])) {
               $a_softwareXML["'".$i."-".(string)$child->GUID."'"] = 1;
               $i++;
               $soft[(string)$child->GUID."-".(string)$child->VERSION] = 1;
            }
         }
      }

      $Computer = new Computer();
      $query = "SELECT glpi_softwares.name as softname, glpi_softwareversions.name as versname
         FROM `glpi_computers_softwareversions`
         LEFT JOIN `glpi_softwareversions` on softwareversions_id = `glpi_softwareversions`.`id`
         LEFT JOIN `glpi_softwares` on `glpi_softwareversions`.`softwares_id` = `glpi_softwares`.`id`
         WHERE `computers_id`='".$items_id."' ";
      $result=$DB->query($query);
      $dbsofts = array();
      if ($result = $DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $dbsofts[$data['softname']."-".$data['versname']] = 1;
         }
      }
      $a_diff = array_diff_key($soft, $dbsofts);
      $diff = print_r($a_diff, 1);
      $a_diff2 = array_diff_key($dbsofts,$soft);
      $diff2 = print_r($a_diff2, 1);
      $this->assertEquals($DB->numrows($result), (count($a_softwareXML)), 'Difference of Softwares, created '.$DB->numrows($result).' times instead '.(count($a_softwareXML)).' ['.$xmlFile.']'.$diff.' or '.$diff2);

      // Verify fields in GLPI
      foreach($xml->CONTENT->SOFTWARES as $child) {
         if (!isset($child->VERSION)) {
            $child->VERSION = 'N/A';
         }
         $name = '';
         if (isset($child->NAME)) {
            $name = $child->NAME;
         } else if (isset($child->GUID)) {
            $name = $child->GUID;
         }
         if ($name != '') {
            // Search in GLPI if it's ok
            $query = "SELECT * FROM `glpi_computers_softwareversions`
               LEFT JOIN `glpi_softwareversions` ON `softwareversions_id`=`glpi_softwareversions`.`id`
               LEFT JOIN `glpi_softwares` ON `glpi_softwareversions`.`softwares_id` = `glpi_softwares`.`id`
               WHERE `computers_id`='".$items_id."'
                  AND `glpi_softwareversions`.`name` = '".$child->VERSION."'
                  AND `glpi_softwares`.`name` = '".addslashes_deep($name)."'
                     LIMIT 1";
            $result=$DB->query($query);

            $this->assertEquals($DB->numrows($result), 1, 'Software not find in GLPI '.$DB->numrows($result).' times instead 1 ('.addslashes_deep($child->NAME).'/'.addslashes_deep($child->GUID).') ['.$xmlFile.']');
         }
      }


   }


   function testHardware($xmlFile='', $items_id=0, $unknown=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testHardware with no arguments...\n";
         return;
      }
      if ($unknown == '1') {
         // MANAGE SOME OF DATAS !!!!
         return;
      }

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);
      if ($xml->CONTENT->BIOS->SSN == '30003000000000000000000000300000000000000000000000000000000000000000000000000000000000') {
         unset($xml->CONTENT->BIOS->SSN);
      } else if ($xml->CONTENT->BIOS->SSN == 'To Be Filled By O.E.M.') {
         unset($xml->CONTENT->BIOS->SSN);
      }


      $Computer = new Computer();
      $Computer->getFromDB($items_id);
      $pfBlacklist = new PluginFusinvinventoryBlacklist();
      foreach ($xml->CONTENT->BIOS as $child) {
         $addm = 0;
         if ((isset($child->SMANUFACTURER))
               AND (!empty($child->SMANUFACTURER))) {
            $a_found = $pfBlacklist->find("`value`='".(string)$child->SMANUFACTURER."'
               AND `plugin_fusioninventory_criterium_id`='10'");
            if (count($a_found) == '0') { 
               $this->assertEquals($Computer->fields['manufacturers_id'], Dropdown::importExternal('Manufacturer', (string)$child->SMANUFACTURER), 'Difference of Hardware manufacturer, have '.$Computer->fields['manufacturers_id'].' instead '.Dropdown::importExternal('Manufacturer', (string)$child->SMANUFACTURER).' ['.$xmlFile.']');
               $addm = 1;
            }
         } 
         if ($addm == '0'
                 AND(isset($child->MMANUFACTURER))
                 AND (!empty($child->MMANUFACTURER))) {
            $a_found = $pfBlacklist->find("`value`='".(string)$child->MMANUFACTURER."'
               AND `plugin_fusioninventory_criterium_id`='10'");
            if (count($a_found) == '0') { 
               $this->assertEquals($Computer->fields['manufacturers_id'], Dropdown::importExternal('Manufacturer', (string)$child->MMANUFACTURER), 'Difference of Hardware manufacturer, have '.$Computer->fields['manufacturers_id'].' instead '.Dropdown::importExternal('Manufacturer', (string)$child->MMANUFACTURER).' ['.$xmlFile.']');
               $addm = 1;
            }
         }
         if ($addm == '0'
                 AND (isset($child->BMANUFACTURER))
                 AND (!empty($child->BMANUFACTURER))) {

            $a_found = $pfBlacklist->find("`value`='".(string)$child->BMANUFACTURER."'
               AND `plugin_fusioninventory_criterium_id`='10'");
            if (count($a_found) == '0') { 
               $this->assertEquals($Computer->fields['manufacturers_id'], Dropdown::importExternal('Manufacturer', (string)$child->BMANUFACTURER), 'Difference of Hardware manufacturer, have '.$Computer->fields['manufacturers_id'].' instead '.Dropdown::importExternal('Manufacturer', (string)$child->BMANUFACTURER).' ['.$xmlFile.']');
               $addm = 1;
            }
         }
         if (isset($child->SMODEL)
                 AND (string)$child->SMODEL!='') {
            $ComputerModel = new ComputerModel;
            $this->assertEquals($Computer->fields['computermodels_id'], $ComputerModel->importExternal((string)$child->SMODEL), 'Difference of Hardware model, have '.$Computer->fields['computermodels_id'].' instead '.$ComputerModel->importExternal((string)$child->SMODEL).' ['.$xmlFile.']');
         } else if (isset($child->MMODEL)
                 AND (string)$child->MMODEL!='') {
            $ComputerModel = new ComputerModel;
            $this->assertEquals($Computer->fields['computermodels_id'], $ComputerModel->importExternal((string)$child->MMODEL), 'Difference of Hardware model, have '.$Computer->fields['computermodels_id'].' instead '.$ComputerModel->importExternal((string)$child->MMODEL).' ['.$xmlFile.']');
         }
         if (isset($child->SSN)) {
            if (!empty($child->SSN)) {
               $this->assertEquals($Computer->fields['serial'], trim($child->SSN), 'Difference of Hardware serial number, have '.$Computer->fields['serial'].' instead '.$child->SSN.' ['.$xmlFile.']');
            }
         }
      }

      foreach ($xml->CONTENT->HARDWARE as $child) {
         if (isset($child->NAME)) {
            $this->assertEquals($Computer->fields['name'], (string)$child->NAME, 'Difference of Hardware name, have '.$Computer->fields['name'].' instead '.(string)$child->NAME.' ['.$xmlFile.']');
         }
         if (isset($child->OSNAME)) {
            $OperatingSystem = new OperatingSystem;
            if (!strstr((string)$child->OSNAME, "Debian GNU/Linux squeeze/sid ")
                    AND !strstr((string)$child->OSNAME, "Debian GNU/Linux 5.0 ")) {
               $this->assertEquals($Computer->fields['operatingsystems_id'], $OperatingSystem->importExternal((string)$child->OSNAME), 'Difference of Hardware operatingsystems, have '.$Computer->fields['operatingsystems_id'].' instead '.$OperatingSystem->importExternal((string)$child->OSNAME).' ['.$xmlFile.']');
            }
         }
         if (isset($child->OSVERSION)) {
            $OperatingSystemVersion = new OperatingSystemVersion;
            $this->assertEquals($Computer->fields['operatingsystemversions_id'], $OperatingSystemVersion->importExternal((string)$child->OSVERSION), 'Difference of Hardware operatingsystemversions, have '.$Computer->fields['operatingsystemversions_id'].' instead '.$OperatingSystemVersion->importExternal((string)$child->OSVERSION).' ['.$xmlFile.']');
         }
         if (isset($child->WINPRODID)) {
            $this->assertEquals($Computer->fields['os_licenseid'], (string)$child->WINPRODID, 'Difference of Hardware os_licenseid, have '.$Computer->fields['os_licenseid'].' instead '.(string)$child->WINPRODID.' ['.$xmlFile.']');
         }
         if (isset($child->WINPRODKEY)) {
            $this->assertEquals($Computer->fields['os_license_number'], (string)$child->WINPRODKEY, 'Difference of Hardware os_license_number, have '.$Computer->fields['os_license_number'].' instead '.(string)$child->WINPRODKEY.' ['.$xmlFile.']');
         }
         if (isset($child->WORKGROUP)) {
            $Domain = new Domain;
            $this->assertEquals($Computer->fields['domains_id'], $Domain->import(array('name'=>(string)$child->WORKGROUP)), 'Difference of Hardware domain, have '.$Computer->fields['domains_id'].' instead '.$Domain->import(array('name'=>(string)$child->WORKGROUP)).' ['.$xmlFile.']');
         }
         if (isset($child->OSCOMMENTS)) {
            if (strstr($child->OSCOMMENTS, 'Service Pack')) {
               $OperatingSystemServicePack = new OperatingSystemServicePack;
               $this->assertEquals($Computer->fields['operatingsystemservicepacks_id'], $OperatingSystemServicePack->importExternal((string)$child->OSCOMMENTS), 'Difference of Hardware operatingsystemservicepacks_id, have '.$Computer->fields['operatingsystemservicepacks_id'].' instead '.$OperatingSystemServicePack->importExternal((string)$child->OSCOMMENTS).' ['.$xmlFile.']');

               $Computer->fields['operatingsystemservicepacks_id'] = $OperatingSystemServicePack->importExternal((string)$child->OSCOMMENTS);
            }
         }


      }

  }

   function testHardwareModifications($xmlFile='', $items_id=0) {
      global $DB;

      if (empty($xmlFile)) {
         echo "testHardwareModifications with no arguments...\n";
         return;
      }

      $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);

      // Modification of networks ports
      $modif = 0;
      foreach ($xml->CONTENT->NETWORKS as $child) {
         $ip = rand(0, 254).".".rand(0, 254).".".rand(0, 254).".";
         $child->IPADDRESS = $ip.rand(0, 254);
         $child->IPSUBNET = $ip."0";
         $modif++;
      }
      if ($modif > 0) {
//         $this->testSendinventory($xmlFile, $xml->DEVICEID);
//         $this->testNetwork($xml, $items_id, "0", $xmlFile);
      }
   }

   
   
   function testHistoryCreateComputer() {
      global $DB;
      
$XML = array();
$XML['Computer'] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<REQUEST>
  <CONTENT>
    <ACCESSLOG>
      <LOGDATE>2011-07-03 23:23:21</LOGDATE>
      <USERID>N/A</USERID>
    </ACCESSLOG>
    <BATTERIES>
      <CHEMISTRY>Lithium </CHEMISTRY>
      <DATE></DATE>
      <MANUFACTURER>TOSHIBA</MANUFACTURER>
      <SERIAL>0000000000</SERIAL>
    </BATTERIES>
    <BIOS>
      <ASSETTAG>0000000000</ASSETTAG>
      <BDATE>09/15/2010</BDATE>
      <BMANUFACTURER>TOSHIBA</BMANUFACTURER>
      <BVERSION>Version 1.60</BVERSION>
      <MMANUFACTURER>TOSHIBA</MMANUFACTURER>
      <MMODEL>Portable PC</MMODEL>
      <MSN>0000000000</MSN>
      <SKUNUMBER>0000000000</SKUNUMBER>
      <SMANUFACTURER>TOSHIBA</SMANUFACTURER>
      <SMODEL>Satellite R630</SMODEL>
      <SSN>XA201220HHHHRT</SSN>
    </BIOS>
    <CONTROLLERS>
      <CAPTION>Core Processor DRAM Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>Core Processor DRAM Controller</NAME>
      <PCICLASS>0600</PCICLASS>
      <PCIID>8086:0044</PCIID>
      <PCISLOT>00:00.0</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>Core Processor Integrated Graphics Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>Core Processor Integrated Graphics Controller</NAME>
      <PCICLASS>0300</PCICLASS>
      <PCIID>8086:0046</PCIID>
      <PCISLOT>00:02.0</PCISLOT>
      <TYPE>Display controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset HECI Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset HECI Controller</NAME>
      <PCICLASS>0780</PCICLASS>
      <PCIID>8086:3b64</PCIID>
      <PCISLOT>00:16.0</PCISLOT>
      <TYPE>Communication controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>82577LC Gigabit Network Connection</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>82577LC Gigabit Network Connection</NAME>
      <PCICLASS>0200</PCICLASS>
      <PCIID>8086:10eb</PCIID>
      <PCISLOT>00:19.0</PCISLOT>
      <TYPE>Network controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset USB2 Enhanced Host Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset USB2 Enhanced Host Controller</NAME>
      <PCICLASS>0c03</PCICLASS>
      <PCIID>8086:3b3c</PCIID>
      <PCISLOT>00:1a.0</PCISLOT>
      <TYPE>Serial bus controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset High Definition Audio</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset High Definition Audio</NAME>
      <PCICLASS>0403</PCICLASS>
      <PCIID>8086:3b56</PCIID>
      <PCISLOT>00:1b.0</PCISLOT>
      <TYPE>Multimedia controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset PCI Express Root Port 1</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset PCI Express Root Port 1</NAME>
      <PCICLASS>0604</PCICLASS>
      <PCIID>8086:3b42</PCIID>
      <PCISLOT>00:1c.0</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset PCI Express Root Port 2</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset PCI Express Root Port 2</NAME>
      <PCICLASS>0604</PCICLASS>
      <PCIID>8086:3b44</PCIID>
      <PCISLOT>00:1c.1</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset PCI Express Root Port 3</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset PCI Express Root Port 3</NAME>
      <PCICLASS>0604</PCICLASS>
      <PCIID>8086:3b46</PCIID>
      <PCISLOT>00:1c.2</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset USB2 Enhanced Host Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset USB2 Enhanced Host Controller</NAME>
      <PCICLASS>0c03</PCICLASS>
      <PCIID>8086:3b34</PCIID>
      <PCISLOT>00:1d.0</PCISLOT>
      <TYPE>Serial bus controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>82801 Mobile PCI Bridge</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>82801 Mobile PCI Bridge</NAME>
      <PCICLASS>0604</PCICLASS>
      <PCIID>8086:2448</PCIID>
      <PCISLOT>00:1e.0</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>Mobile 5 Series Chipset LPC Interface Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>Mobile 5 Series Chipset LPC Interface Controller</NAME>
      <PCICLASS>0601</PCICLASS>
      <PCIID>8086:3b09</PCIID>
      <PCISLOT>00:1f.0</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset 4 port SATA AHCI Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset 4 port SATA AHCI Controller</NAME>
      <PCICLASS>0106</PCICLASS>
      <PCIID>8086:3b29</PCIID>
      <PCISLOT>00:1f.2</PCISLOT>
      <TYPE>Mass storage controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset Thermal Subsystem</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset Thermal Subsystem</NAME>
      <PCICLASS>1180</PCICLASS>
      <PCIID>8086:3b32</PCIID>
      <PCISLOT>00:1f.6</PCISLOT>
      <TYPE>Signal processing controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>MMC/SD Host Controller</CAPTION>
      <MANUFACTURER>Ricoh Co Ltd</MANUFACTURER>
      <NAME>MMC/SD Host Controller</NAME>
      <PCICLASS>0805</PCICLASS>
      <PCIID>1180:e822</PCIID>
      <PCISLOT>01:00.0</PCISLOT>
      <TYPE>Generic system peripheral</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>BCM4313 802.11b/g/n Wireless LAN Controller</CAPTION>
      <MANUFACTURER>Broadcom Corporation</MANUFACTURER>
      <NAME>BCM4313 802.11b/g/n Wireless LAN Controller</NAME>
      <PCICLASS>0280</PCICLASS>
      <PCIID>14e4:4727</PCIID>
      <PCISLOT>02:00.0</PCISLOT>
      <TYPE>Network controller</TYPE>
    </CONTROLLERS>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>8529</FREE>
      <TOTAL>9681</TOTAL>
      <TYPE>/</TYPE>
      <VOLUMN>/dev/ad4s1a</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>194276</FREE>
      <TOTAL>403402</TOTAL>
      <TYPE>/Donnees</TYPE>
      <VOLUMN>/dev/ad4s1g</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>1213</FREE>
      <TOTAL>1447</TOTAL>
      <TYPE>/tmp</TYPE>
      <VOLUMN>/dev/ad4s1e</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>13983</FREE>
      <TOTAL>38739</TOTAL>
      <TYPE>/usr</TYPE>
      <VOLUMN>/dev/ad4s1f</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>20</FREE>
      <TOTAL>4673</TOTAL>
      <TYPE>/var</TYPE>
      <VOLUMN>/dev/ad4s1d</VOLUMN>
    </DRIVES>
    <ENVS>
      <KEY>HOST</KEY>
      <VAL>port004.bureau.siprossii.com</VAL>
    </ENVS>
    <HARDWARE>
      <ARCHNAME>amd64-freebsd-thread-multi</ARCHNAME>
      <CHASSIS_TYPE>Notebook</CHASSIS_TYPE>
      <CHECKSUM>262143</CHECKSUM>
      <DESCRIPTION>amd64/00-00-01 04:36:54</DESCRIPTION>
      <DNS>8.8.8.8</DNS>
      <ETIME>22</ETIME>
      <IPADDR>192.168.20.184/10.0.0.254/10.0.0.1</IPADDR>
      <MEMORY>3810</MEMORY>
      <NAME>port004HHT</NAME>
      <OSCOMMENTS>GENERIC (Thu Feb 17 02:41:51 UTC 2011)root@mason.cse.buffalo.edu</OSCOMMENTS>
      <OSNAME>freebsd</OSNAME>
      <OSVERSION>8.2-RELEASE</OSVERSION>
      <SWAP>4096</SWAP>
      <USERDOMAIN></USERDOMAIN>
      <USERID>ddurieux</USERID>
      <UUID>68405E00-E5BE-11DF-801C-B05981201220HHTT</UUID>
      <VMSYSTEM>Physical</VMSYSTEM>
      <WORKGROUP>bureau.siprossii.com</WORKGROUP>
    </HARDWARE>
    <MEMORIES>
      <CAPACITY>2048</CAPACITY>
      <CAPTION>DIMM0</CAPTION>
      <DESCRIPTION>SODIMM</DESCRIPTION>
      <NUMSLOTS>1</NUMSLOTS>
      <SERIALNUMBER>98F6FF18</SERIALNUMBER>
      <SPEED>1067</SPEED>
      <TYPE>DDR3</TYPE>
    </MEMORIES>
    <MEMORIES>
      <CAPACITY>2048</CAPACITY>
      <CAPTION>DIMM2</CAPTION>
      <DESCRIPTION>SODIMM</DESCRIPTION>
      <NUMSLOTS>2</NUMSLOTS>
      <SERIALNUMBER>95F1833E</SERIALNUMBER>
      <SPEED>1067</SPEED>
      <TYPE>DDR3</TYPE>
    </MEMORIES>
    <NETWORKS>
      <DESCRIPTION>em0</DESCRIPTION>
      <IPADDRESS>192.168.20.184</IPADDRESS>
      <IPGATEWAY>192.168.20.1</IPGATEWAY>
      <IPMASK>255.255.255.0</IPMASK>
      <IPSUBNET>192.168.20.0</IPSUBNET>
      <MACADDR>78:23:18:cf:0d:93</MACADDR>
      <MTU>1500</MTU>
      <STATUS>Up</STATUS>
      <TYPE>Ethernet</TYPE>
    </NETWORKS>
    <NETWORKS>
      <DESCRIPTION>lo0</DESCRIPTION>
      <IPADDRESS>127.0.0.1</IPADDRESS>
      <IPGATEWAY>192.168.20.1</IPGATEWAY>
      <IPMASK>255.0.0.0</IPMASK>
      <IPSUBNET>127.0.0.0</IPSUBNET>
      <MACADDR></MACADDR>
      <MTU>16384</MTU>
      <STATUS>Up</STATUS>
      <TYPE></TYPE>
      <VIRTUALDEV>1</VIRTUALDEV>
    </NETWORKS>
    <NETWORKS>
      <DESCRIPTION>lo1</DESCRIPTION>
      <IPADDRESS>10.0.0.1</IPADDRESS>
      <IPGATEWAY>192.168.20.1</IPGATEWAY>
      <IPMASK>255.255.255.0</IPMASK>
      <IPSUBNET>10.0.0.0</IPSUBNET>
      <MACADDR></MACADDR>
      <MTU>16384</MTU>
      <STATUS>Up</STATUS>
      <TYPE></TYPE>
      <VIRTUALDEV>1</VIRTUALDEV>
    </NETWORKS>
    <PORTS>
      <CAPTION>DB-15 female</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>RJ-45</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Network Port</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Access Bus (USB)</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>USB</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Access Bus (USB)</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>USB</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Access Bus (USB)</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>USB</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Mini Jack (headphones)</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Other</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Other</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Other</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Other</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PROCESSES>
      <CMD>[idle]</CMD>
      <CPUUSAGE>374.3</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>11</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <SLOTS>
      <DESCRIPTION>Other</DESCRIPTION>
      <NAME>SD CARD</NAME>
      <STATUS>In Use</STATUS>
    </SLOTS>
    <SLOTS>
      <DESCRIPTION>x1 PCI Express</DESCRIPTION>
      <DESIGNATION>1</DESIGNATION>
      <NAME>EXPRESS CARD</NAME>
      <STATUS>In Use</STATUS>
    </SLOTS>
    <SOFTWARES>
      <COMMENTS>Image processing tools</COMMENTS>
      <NAME>ImageMagick</NAME>
      <VERSION>6.7.0.2</VERSION>
    </SOFTWARES>
    <SOUNDS>
      <DESCRIPTION>rev 06</DESCRIPTION>
      <MANUFACTURER>Intel Corporation 5 Series/3400 Series Chipset High Definition Audio </MANUFACTURER>
      <NAME>Audio device</NAME>
    </SOUNDS>
    <STORAGES>
      <DESCRIPTION>ad4s1b</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1a</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1g</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1e</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1f</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1d</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>acd0</DESCRIPTION>
      <MODEL>MATSHITADVD-RAM UJ892ES/1.20</MODEL>
      <TYPE></TYPE>
    </STORAGES>
    <USERS>
      <LOGIN>ddurieux</LOGIN>
    </USERS>
    <VERSIONCLIENT>FusionInventory-Agent_v2.1.9-3</VERSIONCLIENT>
    <VIDEOS>
      <CHIPSET>VGA compatible controller</CHIPSET>
      <NAME>Intel Corporation Core Processor Integrated Graphics Controller </NAME>
    </VIDEOS>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>INVENTORY</QUERY>
</REQUEST>";
      
      $log = new Log();
      $countlog_start = countElementsInTable(getTableForItemType("Log"));
      $xml = simplexml_load_string($XML['Computer'], 'SimpleXMLElement', LIBXML_NOCDATA);
      $this->testSendinventory("Nothing", $xml);
      $countlog_end = countElementsInTable(getTableForItemType("Log"));
      $a_logs = $log->find("", "id DESC", ($countlog_end - $countlog_start -1));
      
      foreach ($a_logs as $key=>$data) {
         if ($data['itemtype'] == "Computer"
                 AND $data['id_search_option'] == '0') {
            unset($a_logs[$key]);
            $countlog_end--;
         }
         if ($data['itemtype'] == 'PluginFusioninventoryAgent') {
            unset($a_logs[$key]);
            $countlog_end--;
         }
         if ($data['itemtype'] == "Software"
                 AND $data['id_search_option'] == '0'){
            unset($a_logs[$key]);
            $countlog_end--;
         }
      }
      
      $this->assertEquals(($countlog_end - $countlog_start - 1), 0, 'Problem on log, must be 0 : \n'.print_r($a_logs, true));
   }
   
   
   function testHistoryWhenOSChange() {
      global $DB;
return;
      $xml = simplexml_load_file("InventoryComputer/xml/2.1.6/David-PC-2010-08-09-20-52-54-imprimante.xml", 'SimpleXMLElement', LIBXML_NOCDATA);
      $xml->CONTENT->HARDWARE->UUID = "68405E00-E5BE-11DF-801C-B05981201220HHTT";
      $xml->CONTENT->HARDWARE->NAME = "port004HHT";
      $xml->CONTENT->BIOS->SSN = "XA201220HHHHRT";
   
      $log = new Log();
      $countlog_start = countElementsInTable(getTableForItemType("Log"));
      $this->testSendinventory("Nothing", $xml);
      $countlog_end = countElementsInTable(getTableForItemType("Log"));
      $a_logs = $log->find("", "id DESC", ($countlog_end - $countlog_start));
      // Disable logs for update user in monitor, printer, peripehral because unable to disable log history
      foreach ($a_logs as $key=>$data) {
         if (($data['itemtype'] == "Printer"
                    OR $data['itemtype'] == "Monitor"
                    OR $data['itemtype'] == "Peripheral")) {
            unset($a_logs[$key]);
            $countlog_end--;
         }
         if ($data['itemtype'] == "Computer"
                 AND $data['itemtype_link'] != '0') {
            unset($a_logs[$key]);
            $countlog_end--;
         }
         if ($data['itemtype'] == 'PluginFusioninventoryAgent') {
            unset($a_logs[$key]);
            $countlog_end--;
         }
         if ($data['itemtype'] == "Software"
                 AND $data['id_search_option'] == '0'){
            unset($a_logs[$key]);
            $countlog_end--;
         }
      }
      $this->assertEquals(($countlog_end - $countlog_start), 0, 'Problem on log, must be 0 on OS change : \n'.print_r($a_logs, true));
   }
   
   
}



class InventoryComputer_AllTests  {

   public static function suite() {
      
      $GLPIInstall = new GLPIInstall();
      $Install = new Install();
      $GLPIInstall->testInstall();
      $Install->testInstall(0);

      $suite = new PHPUnit_Framework_TestSuite('InventoryComputer');
      return $suite;
   }
}
?>
