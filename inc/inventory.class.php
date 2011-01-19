<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------

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
            $input['serial'] = (string)$xml->CONTENT->BIOS->SSN;
         }
         if ((isset($xml->CONTENT->HARDWARE->UUID)) AND (!empty($xml->CONTENT->HARDWARE->UUID))) {
            $input['uuid'] = (string)$xml->CONTENT->HARDWARE->UUID;
         }
         if (isset($xml->CONTENT->NETWORKS)) {
            foreach($xml->CONTENT->NETWORKS as $network) {
               if (((isset($network->VIRTUALDEV)) AND ($network->VIRTUALDEV != '1'))
                       OR (!isset($network->VIRTUALDEV))){
                  if ((isset($network->MACADDR)) AND (!empty($network->MACADDR))) {
                     $input['mac'][] = (string)$network->MACADDR;
                  }
               }
            }
         }
         if ((isset($xml->CONTENT->HARDWARE->WINPRODKEY)) AND (!empty($xml->CONTENT->HARDWARE->WINPRODKEY))) {
            $input['mskey'] = (string)$xml->CONTENT->HARDWARE->WINPRODKEY;
         }
         if ((isset($xml->CONTENT->BIOS->SMODEL)) AND (!empty($xml->CONTENT->BIOS->SMODEL))) {
            $input['model'] = (string)$xml->CONTENT->BIOS->SMODEL;
         }
         if (isset($xml->CONTENT->STORAGES)) {
            foreach($xml->CONTENT->STORAGES as $storage) {
               if ((isset($storage->SERIALNUMBER)) AND (!empty($storage->SERIALNUMBER))) {
                  $input['partitionserial'][] = (string)$storage->SERIALNUMBER;
               }
            }
         }
         if (isset($xml->CONTENT->DRIVES)) {
            foreach($xml->CONTENT->DRIVES as $drive) {
               if ((isset($drive->SERIAL)) AND (!empty($drive->SERIAL))) {
                  $input['hdserial'][] = (string)$drive->SERIAL;
               }
            }
         }
         if ((isset($xml->CONTENT->BIOS->ASSETTAG)) AND (!empty($xml->CONTENT->BIOS->ASSETTAG))) {
            $input['tag'] = (string)$xml->CONTENT->BIOS->ASSETTAG;
         }
         if ((isset($xml->CONTENT->HARDWARE->NAME)) AND (!empty($xml->CONTENT->HARDWARE->NAME))) {
            $input['name'] = (string)$xml->CONTENT->HARDWARE->NAME;
         }
         $input['itemtype'] = "Computer";

      $_SESSION['plugin_fusinvinventory_datacriteria'] = serialize($input);
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "PluginFusinvinventoryInventory";
      $rule = new PluginFusioninventoryRuleImportEquipmentCollection();
      $data = array();
      $data = $rule->processAllRules($input, array());
   }
   


   function rulepassed($items_id, $itemtype) {

      $xml = simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA);

      if ($itemtype == 'Computer') {
         $PluginFusinvinventoryLib = new PluginFusinvinventoryLib();
         $Computer = new Computer();
         
         if ($items_id == '0') {
            $input = array();
            $input['date_mod'] = date("Y-m-d H:i:s");
            $items_id = $Computer->add($input);
            $PluginFusinvinventoryLib->startAction($xml, $items_id, '1');
         } else {
            $PluginFusinvinventoryLib->startAction($xml, $items_id, '0');
         }

         

         
      }







      return;
      // =================================================================== //
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/FusionLibServer.class.php";
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/MyException.class.php";
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/Logger.class.php";

      $config = array();

      $config['storageEngine'] = "MySQL";
      $config['storageLocation'] = "/../../../../../../../files/_plugins/fusinvinventory";
$datascriterias = unserialize($_SESSION['plugin_fusinvinventory_datacriteria']);
      // get criteria from rules
      //$config['criterias'] = $criterias;
$config['criterias'][] = "ssn";

      $config['maxFalse'] = 0;

      $config['filter'] = 1;
      $config['printError'] = 0;

      $config['sections'][] = "DRIVES";
      $config['sections'][] = "SOFTWARES";
      $config['sections'][] = "CONTROLLERS";
      $config['sections'][] = "ENVS";
      $config['sections'][] = "INPUTS";
      $config['sections'][] = "MEMORIES";
      $config['sections'][] = "MONITORS";
      $config['sections'][] = "NETWORKS";
      $config['sections'][] = "PORTS";
      $config['sections'][] = "PRINTERS";
      $config['sections'][] = "PROCESSES";
      $config['sections'][] = "SOUNDS";
      $config['sections'][] = "STORAGES";
      $config['sections'][] = "USERS";
      $config['sections'][] = "VIDEOS";
      $config['sections'][] = "USBDEVICES";

      $config['hostMySQL']['server'] = "127.0.0.1";
      $config['hostMySQL']['port'] = "3306";
      $config['hostMySQL']['user'] = "root";
      $config['hostMySQL']['password'] = "DestroyBSD";
      $config['hostMySQL']['db'] = "glpi078";


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
      //$action->startAction(simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA));

      $simpleXMLObj = simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA);
      $libData = StorageInventoryFactory::createStorage($action->_applicationName, $action->_config, $simpleXMLObj);

      //if ($items_id != "0") {
         // get $internalId

         //Sections update
            $xmlSections = $action->_getXMLSections($simpleXMLObj);
            $libData->updateLibMachine($xmlSections, $internalId);


      //}

      $output = ob_flush();
      if (!empty($output)) {
         logInFile("fusinvinventory", $output);
      }
   }


   function sendUnknownDevice() {

      $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();

      $xml = simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA);
      //Search with serial
      if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
         $a_device = $PluginFusioninventoryUnknownDevice->find("`serial`='".$xml->CONTENT->BIOS->SSN."'");
         if (count($a_device) == "1") {
            foreach ($a_device as $datas) {
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
//       $NetworkPort = new NetworkPort();
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
         $input['domain'] = Dropdown::importExternal('Domain', $xml->CONTENT->HARDWARE->USERDOMAIN);
      }
      if (isset($xml->CONTENT->BIOS->SSN)) {
         $input['serial'] = $xml->CONTENT->BIOS->SSN;
      }
      $input['type'] = 'Computer';
      $unknown_id = $PluginFusioninventoryUnknownDevice->add($input);
      // Create ports
      $PluginFusinvinventoryImport_Networkport = new PluginFusinvinventoryImport_Networkport();
      foreach ($xml->CONTENT->NETWORKS as $child) {
         $dataSection = array();
         if (isset($child->DESCRIPTION)) {
            $dataSection['DESCRIPTION'] = $child->DESCRIPTION;
         }
         if (isset($child->IPADDRESS)) {
            $dataSection['IPADDRESS'] = $child->IPADDRESS;
         }
         if (isset($child->IPADDRESS6)) {
            $dataSection['IPADDRESS6'] = $child->IPADDRESS6;
         }
         if (isset($child->IPDHCP)) {
            $dataSection['IPDHCP'] = $child->IPDHCP;
         }
         if (isset($child->IPGATEWAY)) {
            $dataSection['IPGATEWAY'] = $child->IPGATEWAY;
         }
         if (isset($child->IPMASK)) {
            $dataSection['IPMASK'] = $child->IPMASK;
         }
         if (isset($child->IPSUBNET)) {
            $dataSection['IPSUBNET'] = $child->IPSUBNET;
         }
         if (isset($child->MACADDR)) {
            $dataSection['MACADDR'] = $child->MACADDR;
         }
         if (isset($child->STATUS)) {
            $dataSection['STATUS'] = $child->STATUS;
         }
         if (isset($child->VIRTUALDEV)) {
            $dataSection['VIRTUALDEV'] = $child->VIRTUALDEV;
         }
         $PluginFusinvinventoryImport_Networkport->AddUpdateItem("add", $unknown_id, $dataSection, 'PluginFusioninventoryUnknownDevice');
      }

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
      foreach ($a_computersDB as $datas) {
         if (!isset($computerInLib[$datas['id']])) {
            $this->createMachineInLib($datas['id']);
         }
      }
   }


   function createMachineInLib($items_id) {

      $NetworkPort = new NetworkPort();
      $Computer = new Computer();

      $Computer->getFromDB($items_id);
      $datas = $Computer->fields;

      $xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><REQUEST></REQUEST>");
      $xml_content = $xml->addChild('IMPORT', 'GLPI');
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

      
      // ** SOUNDS
      $CompDeviceSoundCard = new Computer_Device('DeviceSoundCard');
      $DeviceSoundCard = new DeviceSoundCard();
      $a_deviceSoundCard = $CompDeviceSoundCard->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceSoundCard as $deviceSoundCard_id => $deviceSoundCard_data) {
         $xml_sound = $xml_content->addChild("SOUNDS");
         $_SESSION['pluginFusinvinventoryImportMachine']['SOUNDS'][] = $deviceSoundCard_id;
         $DeviceSoundCard->getFromDB($deviceSoundCard_data['devicesoundcards_id']);
         $xml_sound->addChild("NAME", $DeviceSoundCard->fields['designation']);
         $xml_sound->addChild("DESCRIPTION", $DeviceSoundCard->fields['comment']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), $DeviceSoundCard->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_sound->addChild("MANUFACTURER", $manufacturer);
         }
      }


      // TODO      
      $CompDeviceDrive = new Computer_Device('DeviceDrive');
      $DeviceDrive = new DeviceDrive();
      $a_deviceDrive = $CompDeviceDrive->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceDrive as $deviceDrive_id => $deviceDrive_data) {
         $xml_storage = $xml_content->addChild("STORAGES");
         $_SESSION['pluginFusinvinventoryImportMachine']['STORAGES'][] = $deviceDrive_id;
         $DeviceDrive->getFromDB($deviceDrive_data['devicedrives_id']);
         $xml_storage->addChild("NAME", $DeviceDrive->fields['designation']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), $DeviceDrive->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_storage->addChild("MANUFACTURER", $manufacturer);
         }
         $interface = Dropdown::getDropdownName(getTableForItemType('InterfaceType'), $DeviceDrive->fields['interfacetypes_id']);
         if ($interface != "&nbsp;") {
            $xml_storage->addChild("INTERFACE", $interface);
         }
      }
      




      // ** VIDEOS
      $CompDeviceGraphicCard = new Computer_Device('DeviceGraphicCard');
      $DeviceGraphicCard = new DeviceGraphicCard();
      $a_deviceGraphicCard = $CompDeviceGraphicCard->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceGraphicCard as $deviceGraphicCard_id => $deviceGraphicCard_data) {
         $xml_video = $xml_content->addChild("VIDEOS");
         $_SESSION['pluginFusinvinventoryImportMachine']['VIDEOS'][] = $deviceGraphicCard_id;
         $DeviceGraphicCard->getFromDB($deviceGraphicCard_data['devicegraphiccards_id']);
         $xml_video->addChild("NAME", $DeviceSoundCard->fields['designation']);
         $xml_video->addChild("MEMORY", $deviceGraphicCard_data['specificity']);
      }

      

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