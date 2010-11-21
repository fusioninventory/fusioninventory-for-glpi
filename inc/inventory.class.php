<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class 
 **/
class PluginFusinvinventoryInventory {

   
   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {
      global $LANG;

      $errors = '';

      $this->sendCriteria($p_DEVICEID, $p_CONTENT, $p_xml);

      return $errors;
   }



   function sendCriteria($p_DEVICEID, $p_CONTENT, $p_xml) {

      $PluginFusinvinventoryBlacklist = new PluginFusinvinventoryBlacklist();
      $p_xml = $PluginFusinvinventoryBlacklist->cleanBlacklist($p_xml);

      $_SESSION['SOURCEXML'] = $p_xml;

      $xml = simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA);
      $input = array();

      // Global criterias

         if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
            $input['globalcriteria'][] = 1;
            $input['serialnumber'] = $xml->CONTENT->BIOS->SSN;
         }
         if ((isset($xml->CONTENT->HARDWARE->UUID)) AND (!empty($xml->CONTENT->HARDWARE->UUID))) {
            $input['globalcriteria'][] = 2;
            $input['uuid'] = $xml->CONTENT->HARDWARE->UUID;
         }
         if (isset($xml->CONTENT->NETWORKS)) {
            foreach($xml->CONTENT->NETWORKS as $network) {
               if ((isset($network->MACADDR)) AND (!empty($network->MACADDR))) {
                  $input['globalcriteria'][] = 3;
                  $input['mac'][] = $network->MACADDR;
               }
            }
         }
         if ((isset($xml->CONTENT->HARDWARE->WINPRODKEY)) AND (!empty($xml->CONTENT->HARDWARE->WINPRODKEY))) {
            $input['globalcriteria'][] = 4;
            $input['windowskey'] = $xml->CONTENT->HARDWARE->WINPRODKEY;
         }
         if ((isset($xml->CONTENT->BIOS->SMODEL)) AND (!empty($xml->CONTENT->BIOS->SMODEL))) {
            $input['globalcriteria'][] = 5;
            $input['model'] = $xml->CONTENT->BIOS->SMODEL;
         }
         if (isset($xml->CONTENT->STORAGES)) {
            foreach($xml->CONTENT->STORAGES as $storage) {
               if ((isset($storage->SERIALNUMBER)) AND (!empty($storage->SERIALNUMBER))) {
                  $input['globalcriteria'][] = 6;
                  $input['storageserial'][] = $storage->SERIALNUMBER;
               }
            }
         }
         if (isset($xml->CONTENT->DRIVES)) {
            foreach($xml->CONTENT->DRIVES as $drive) {
               if ((isset($drive->SERIAL)) AND (!empty($drive->SERIAL))) {
                  $input['globalcriteria'][] = 7;
                  $input['drivesserial'][] = $drive->SERIAL;
               }
            }
         }
         if ((isset($xml->CONTENT->BIOS->ASSETTAG)) AND (!empty($xml->CONTENT->BIOS->ASSETTAG))) {
            $input['globalcriteria'][] = 8;
            $input['assettag'] = $xml->CONTENT->BIOS->ASSETTAG;
         }
      $rule = new PluginFusinvinventoryRuleInventoryCollection();
      $data = array ();
      $data = $rule->processAllRules($input, array());
      
   }
   


   function sendLib($criterias) {
      logInFile('criteria', print_r($criterias, true));
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/FusionLibServer.class.php";
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/MyException.class.php";
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/Logger.class.php";

      $config = array();

      $config['storageEngine'] = "Directory";
      $config['storageLocation'] = "/../../../../../../../files/_plugins/fusinvinventory";

      // get criteria from rules
      $config['criterias'] = $criterias;

      $config['maxFalse'] = 0;

      $config['filter'] = 1;
      $config['printError'] = 0;

      $config['sections'][] = "DRIVES";
      $config['sections'][] = "NETWORKS";
      $config['sections'][] = "PROCESSES";

      define("LIBSERVERFUSIONINVENTORY_LOG_FILE",GLPI_PLUGIN_DOC_DIR.'/fusioninventory/logs');
      define("LIBSERVERFUSIONINVENTORY_STORAGELOCATION",GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      define("LIBSERVERFUSIONINVENTORY_HOOKS_CLASSNAME","PluginFusinvinventoryLibhook");
      define("LIBSERVERFUSIONINVENTORY_LOG_DIR",GLPI_PLUGIN_DOC_DIR.'/fusioninventory/');
      define("LIBSERVERFUSIONINVENTORY_PRINTERROR",$config['printError']);
      $log = new Logger('../../../../../../files/_plugins/fusioninventory/logs');

      $action = ActionFactory::createAction("inventory");
      
      //$action->checkConfig("../../../../../fusinvinventory/inc", $config);
      $action->checkConfig("", $config);
      ob_start();
      $action->startAction(simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA));
      $output = ob_flush();
      if (!empty($output)) {
         logInFile("fusinvinventory", $output);
      }
   }


   function sendUnknownDevice() {

      $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $NetworkPort = new NetworkPort();

      $xml = simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA);
      //Search with serial
      if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
         $a_device = $PluginFusioninventoryUnknownDevice->find("`serial`='".$xml->CONTENT->BIOS->SSN."'");
         if (count($a_device) == "1") {
            foreach ($a_device as $id => $datas) {
               if (isset($xml->CONTENT->HARDWARE->NAME)) {
                  $datas['name'] = $xml->CONTENT->HARDWARE->NAME;
               }
               if (isset($xml->CONTENT->HARDWARE->USERID)) {
                  $datas['contact'] = $xml->CONTENT->HARDWARE->USERID;
               }
               if ((isset($xml->CONTENT->HARDWARE->USERDOMAIN)) AND (!empty($xml->CONTENT->HARDWARE->USERDOMAIN))) {
                  $datas['domain'] = Dropdown::importExternal('glpi_domains', $xml->CONTENT->HARDWARE->USERDOMAIN);
               }
               $datas['type'] = 'Computer';
               $PluginFusioninventoryUnknownDevice->add($datas);
               $PluginFusioninventoryUnknownDevice->writeXML($datas['id'], $_SESSION['SOURCEXML']);
               return;
            }
         }
      }
      //Search with mac address
//      if (isset($XML->CONTENT->NETWORKS)) {
//         foreach ($xml->CONTENT->NETWORKS->children() as $name=>$child) {
//            $a_port = $NetworkPort->find("`mac`='".$child->MACADDR."' AND `itemtype`='PluginFusioninventoryUnknownDevice'");
//
//
//         }
//      }
      //Else add unknown device
      $input = array();
      if (isset($xml->CONTENT->HARDWARE->NAME)) {
         $input['name'] = $xml->CONTENT->HARDWARE->NAME;
      }
      if (isset($xml->CONTENT->HARDWARE->USERID)) {
         $input['contact'] = $xml->CONTENT->HARDWARE->USERID;
      }
      if ((isset($xml->CONTENT->HARDWARE->USERDOMAIN)) AND (!empty($xml->CONTENT->HARDWARE->USERDOMAIN))) {
         $input['domain'] = Dropdown::importExternal('glpi_domains', $xml->CONTENT->HARDWARE->USERDOMAIN);
      }
      if (isset($xml->CONTENT->BIOS->SSN)) {
         $input['serial'] = $xml->CONTENT->BIOS->SSN;
      }
      $input['type'] = 'Computer';
      $unknown_id = $PluginFusioninventoryUnknownDevice->add($input);
      $PluginFusioninventoryUnknownDevice->writeXML($unknown_id, $_SESSION['SOURCEXML']);
   }


   // Only for computer yet in GLPI DB or added manually
   function createMachinesInLib() {

      $Computer = new Computer();

      $computerInLib = array();
      $a_machines = scandir(GLPI_DOC_DIR."/_plugins/fusioninventory/machines");
      foreach ($a_machines as $machine) {
         if (($machine != ".") AND ($machine != "..")) {

            $fileinfo = fopen(GLPI_DOC_DIR."/_plugins/fusioninventory/machines/".$machine."/infos.file","r" );
            $i = 0;
            while ($i < 1) {
               $computerInLib[trim(fgets($fileinfo))] = 1;
               $i++;
            }
            fclose($fileinfo);
         }
      }

      $a_computersDB = $Computer->find();
      foreach ($a_computersDB as $items_id => $datas) {
         if (!isset($computerInLib[$items_id])) {
            $this->createMachineInLib($items_id);           
         }
      }
   }


   function createMachineInLib($items_id) {

      $NetworkPort = new NetworkPort();
      $Computer = new Computer();

      $Computer->getFromDB($items_id);
      $datas = $Computer->fields;

      $xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><REQUEST></REQUEST>");
      $xml_content = $xml->addChild('CONTENT');

      // ** NETWORKS
      $a_networkport = $NetworkPort->find("`items_id`='".$items_id."' AND `itemtype`='Computer' ");
      foreach ($a_networkport as $networkport_id => $networkport_data) {
         $xml_networks = $xml_content->addChild("NETWORKS");
         $_SESSION['pluginFusinvinventoryImportMachine']['NETWORKS'][] = $networkport_id;
         $xml_networks->addChild("MACADDR", $networkport_data['mac']);
         $xml_networks->addChild("IPADDRESS", $networkport_data['ip']);
         $xml_networks->addChild("IPMASK", $networkport_data['netmask']);
         $xml_networks->addChild("IPSUBNET", $networkport_data['subnet']);
         $xml_networks->addChild("IPGATEWAY", $networkport_data['gateway']);
         $xml_networks->addChild("DESCRIPTION", $networkport_data['name']);
         $network_type = Dropdown::getDropdownName('glpi_networkinterfaces', $networkport_data['networkinterfaces_id']);
         if ($network_type != "&nbsp;") {
            $xml_networks->addChild("TYPE", $network_type);
         }
      }

      // ** BIOS
      $xml_bios = $xml_content->addChild("BIOS");
      $_SESSION['pluginFusinvinventoryImportMachine']['BIOS'] = $items_id;
      $xml_bios->addChild("SSN", $datas['serial']);
      $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), $datas['manufacturers_id']);
      if ($manufacturer != "&nbsp;") {
         $xml_bios->addChild("SMANUFACTURER", $manufacturer);
      }
      $model = Dropdown::getDropdownName(getTableForItemType('ComputerModel'), $datas['computermodels_id']);
      if ($model != "&nbsp;") {
         $xml_bios->addChild("SMODEL", $model);
      }
      $type = Dropdown::getDropdownName(getTableForItemType('ComputerType'), $datas['computertypes_id']);
      if ($type != "&nbsp;") {
         $xml_bios->addChild("TYPE", $type);
      }

      // ** HARDWARE
      $xml_hardware = $xml_content->addChild("HARDWARE");
      $_SESSION['pluginFusinvinventoryImportMachine']['HARDWARE'] = $items_id;
      $xml_hardware->addChild("NAME", $datas['name']);
      $osname = Dropdown::getDropdownName(getTableForItemType('OperatingSystem'), $datas['operatingsystems_id']);
      if ($osname != "&nbsp;") {
         $xml_bios->addChild("OSNAME", $osname);
      }
      $osversion = Dropdown::getDropdownName(getTableForItemType('OperatingSystemVersion'), $datas['operatingsystemversions_id']);
      if ($osversion != "&nbsp;") {
         $xml_bios->addChild("OSVERSION", $osversion);
      }
      $xml_hardware->addChild("WINPRODID", $datas['os_licenseid']);
      $xml_hardware->addChild("WINPRODKEY", $datas['os_license_number']);
      $workgroup = Dropdown::getDropdownName(getTableForItemType('Domain'), $datas['domains_id']);
      if ($workgroup != "&nbsp;") {
         $xml_bios->addChild("WORKGROUP", $workgroup);
      }

      // ** CONTROLLERS
      $CompDeviceControl = new Computer_Device('DeviceControl');
      $DeviceControl = new DeviceControl();
      $a_deviceControl = $CompDeviceControl->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceControl as $deviceControl_id => $deviceControl_data) {
         $xml_controller = $xml_content->addChild("CONTROLLERS");
         $_SESSION['pluginFusinvinventoryImportMachine']['CONTROLLERS'][] = $deviceControl_id;
         $DeviceControl->getFromDB($deviceControl_data['devicecontrols_id']);
         $xml_controller->addChild("NAME", $DeviceControl->fields['name']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), $DeviceControl->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_controller->addChild("MANUFACTURER", $manufacturer);
         }
      }


      // ** CPUS
      $CompDeviceProcessor = new Computer_Device('DeviceProcessor');
      $DeviceProcessor = new DeviceProcessor();
      $a_deviceProcessor = $CompDeviceProcessor->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceProcessor as $deviceProcessor_id => $deviceProcessor_data) {
         $xml_cpu = $xml_content->addChild("CPUS");
         $_SESSION['pluginFusinvinventoryImportMachine']['CPUS'][] = $deviceProcessor_id;
         $DeviceProcessor->getFromDB($deviceProcessor_data['deviceprocessors_id']);
         $xml_cpu->addChild("NAME", $DeviceProcessor->fields['name']);
         $xml_cpu->addChild("SPEED", $deviceProcessor_data['specificity']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), $DeviceProcessor->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_cpu->addChild("MANUFACTURER", $manufacturer);
         }
      }

      // ** DRIVES
      $ComputerDisk = new ComputerDisk;
      $a_disk = $ComputerDisk->find("`computers_id`='".$items_id."' ");
      foreach ($a_disk as $disk_id => $disk_data) {
         $xml_drive = $xml_content->addChild("DRIVES");
         $_SESSION['pluginFusinvinventoryImportMachine']['DRIVES'][] = $disk_id;
         $xml_drive->addChild("LABEL", $disk_data['name']);
         $xml_drive->addChild("VOLUMN", $disk_data['device']);
         $xml_drive->addChild("MOUNTPOINT", $disk_data['mountpoint']);
         $filesystem = Dropdown::importExternal('Filesystem', $disk_data['filesystems_id']);
         if ($filesystem != "&nbsp;") {
            $xml_drive->addChild("FILESYSTEM", $filesystem);
         }
         $xml_drive->addChild("TOTAL", $disk_data['totalsize']);
         $xml_drive->addChild("FREE", $disk_data['freesize']);
      }


      // ** MEMORIES
      $CompDeviceMemory = new Computer_Device('DeviceMemory');
      $DeviceMemory = new DeviceMemory();
      $a_deviceMemory = $CompDeviceMemory->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceMemory as $deviceMemory_id => $deviceMemory_data) {
         $xml_memory = $xml_content->addChild("MEMORIES");
         $_SESSION['pluginFusinvinventoryImportMachine']['MEMORIES'][] = $deviceMemory_id;
         $DeviceMemory->getFromDB($deviceMemory_data['devicememories_id']);
         $xml_memory->addChild("DESCRIPTION", $DeviceMemory->fields['designation']);
         $xml_memory->addChild("CAPACITY", $deviceMemory_data['specificity']);
         $xml_memory->addChild("SPEED", $DeviceMemory->fields['frequence']);
         $type = Dropdown::getDropdownName(getTableForItemType('DeviceMemoryType'), $DeviceMemory->fields['devicememorytypes_id']);
         if ($type != "&nbsp;") {
            $xml_memory->addChild("TYPE", $type);
         }
      }


      // ** MONITORS
      $Monitor = new Monitor();
      $Computer_Item = new Computer_Item();
      $a_ComputerMonitor = $Computer_Item->find("`computers_id`='".$items_id."' AND 'itemtype' = 'Monitor'");
      foreach ($a_ComputerMonitor as $ComputerMonitor_id => $ComputerMonitor_data) {
         $xml_monitor = $xml_content->addChild("MONITORS");
         $_SESSION['pluginFusinvinventoryImportMachine']['MONITORS'][] = $ComputerMonitor_id;
         $Monitor->getFromDB($ComputerMonitor_data['items_id']);
         $xml_monitor->addChild("CAPTION", $Monitor->fields['name']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), $Monitor->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_monitor->addChild("MANUFACTURER", $manufacturer);
         }
         $xml_monitor->addChild("SERIAL", $Monitor->fields['serial']);
         $xml_monitor->addChild("DESCRIPTION", $Monitor->fields['comment']);
      }


      // ** PRINTERS
      $Printer = new Printer();
      $Computer_Item = new Computer_Item();
      $a_ComputerPrinter = $Computer_Item->find("`computers_id`='".$items_id."' AND 'itemtype' = 'Printer'");
      foreach ($a_ComputerPrinter as $ComputerPrinter_id => $ComputerPrinter_data) {
         $xml_printer = $xml_content->addChild("PRINTERS");
         $_SESSION['pluginFusinvinventoryImportMachine']['PRINTERS'][] = $ComputerPrinter_id;
         $Printer->getFromDB($ComputerPrinter_data['items_id']);
         $xml_printer->addChild("NAME", $Printer->fields['name']);
         $xml_printer->addChild("SERIAL", $Printer->fields['serial']);
         if ($Printer->fields['have_usb'] == "1") {
            $xml_printer->addChild("PORT", 'USB');
         }
      }
      

      // ** SOFTWARE
      $Computer_SoftwareVersion = new Computer_SoftwareVersion();
      $SoftwareVersion = new SoftwareVersion();
      $Software = new Software();
      $a_softwareVersion = $Computer_SoftwareVersion->find("`computers_id`='".$items_id."' ");
      foreach ($a_softwareVersion as $softwareversion_id => $softwareversion_data) {
         $SoftwareVersion->getFromDB($softwareversion_data['softwareversions_id']);
         $Software->getFromDB($SoftwareVersion->fields['softwares_id']);
         $xml_software = $xml_content->addChild("SOFTWARES");
         $_SESSION['pluginFusinvinventoryImportMachine']['SOFTWARES'][] = $softwareversion_id;
         $xml_software->addChild("VERSION", $SoftwareVersion->fields['name']);
         $xml_software->addChild("NAME", $Software->fields['name']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), $Software->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_software->addChild("PUBLISHER", $manufacturer);
         }
      }

      
      // TODO
      $xml_sound = $xml_content->addChild("SOUNDS");

      // TODO
      $xml_storage = $xml_content->addChild("STORAGES");

      // TODO
      $xml_video = $xml_content->addChild("VIDEOS");

      // Convert XML
      $xmlXml = str_replace("><", ">\n<", $xml->asXML());
      $token      = strtok($xmlXml, "\n");
      $result     = '';
      $pad        = 0;
      $matches    = array();
      $indent     = 0;

      while ($token !== false) {
         if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
            $indent=0;
         elseif (preg_match('/^<\/\w/', $token, $matches)) :
            $pad = $pad-3;
         elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
            $indent=3;
         else :
            $indent = 0;
         endif;
         $line    = str_pad($token, strlen($token)+$pad, '  ', STR_PAD_LEFT);
         $result .= $line . "\n";
         $token   = strtok("\n");
         $pad    += $indent;
      }
      $xml = simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
print_r($xml->asXML());
      // ** Send to rules => lib fusioninventory
      $this->sendCriteria("", "", $xml->asXML());
      unset($_SESSION['pluginFusinvinventoryImportMachine']);


   }
}

?>