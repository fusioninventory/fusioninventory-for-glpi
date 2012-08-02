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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryInventory {
   private $p_xml;
   
   /**
   * Import data
   *
   * @param $p_DEVICEID XML code to import
   * @param $p_CONTENT XML code of the Computer
   * @param $p_CONTENT XML code of all agent have sent
   *
   * @return nothing (import ok) / error string (import ko)
   **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {
      global $DB;

      $errors = '';

      $ret = $DB->query("SELECT GET_LOCK('inventory', 15)");
      if ($DB->result($ret, 0, 0) == 1) {
          $this->sendCriteria($p_DEVICEID, $p_CONTENT, $p_xml);

          $DB->request("SELECT RELEASE_LOCK('inventory')");
      } else {
          die ("TIMEOUT: SERVER OVERLOADED\n");
      }

      return $errors;
   }



   /**
   * Send Computer to ruleimportequipment
   *
   * @param $p_DEVICEID XML code to import
   * @param $p_CONTENT XML code of the Computer
   * @param $p_CONTENT XML code of all agent have sent
   *
   * @return nothing
   *
   **/
   function sendCriteria($p_DEVICEID, $p_CONTENT, $p_xml) {

      // Hack
          
         // Hack to put OS in software
         $sxml_soft = $p_xml->CONTENT->addChild('SOFTWARES');
         $sxml_soft->addChild('COMMENTS', (string)$p_xml->CONTENT->HARDWARE->OSCOMMENTS);
         $sxml_soft->addChild('NAME', (string)$p_xml->CONTENT->HARDWARE->OSNAME);
         $sxml_soft->addChild('VERSION', (string)$p_xml->CONTENT->HARDWARE->OSVERSION);
      
         // Hack for USB Printer serial
         if (isset($p_xml->CONTENT->PRINTERS)) {
            foreach($p_xml->CONTENT->PRINTERS as $printer) {
               if ((isset($printer->SERIAL)) 
                       AND (preg_match('/\/$/', (string)$printer->SERIAL))) {
                  $printer->SERIAL = preg_replace('/\/$/', '', (string)$printer->SERIAL);
               }
            }
         }
         
         // Hack to remove Memories with Flash types see ticket http://forge.fusioninventory.org/issues/1337
         if (isset($p_xml->CONTENT->MEMORIES)) {
            $i = 0;
            $arrayName = array();
            foreach($p_xml->CONTENT->MEMORIES as $memory) {
               if ((isset($memory->TYPE)) 
                       AND (preg_match('/Flash/', (string)$memory->TYPE))) {

                  $arrayName[] = $i;
               }
               $i++;
            }
            foreach ($arrayName as $key) {
               unset($p_xml->CONTENT->MEMORIES[$key]);
            }
         }
      // End hack
         
      // Know if computer is HP to remove S in prefix of serial number
         if ((isset($p_xml->CONTENT->BIOS->SMANUFACTURER))
               AND (strstr($p_xml->CONTENT->BIOS->SMANUFACTURER, "ewlett"))) {

            $_SESSION["plugin_fusioninventory_manufacturerHP"] = 1;
         } else {
            if (isset($_SESSION["plugin_fusioninventory_manufacturerHP"])) {
               unset($_SESSION["plugin_fusioninventory_manufacturerHP"]);
            }
         }
                  
      // End code for HP computers
      
      // Get tag is defined and put it in fusioninventory_agent table
         if (isset($p_xml->CONTENT->ACCOUNTINFO)) {
            foreach($p_xml->CONTENT->ACCOUNTINFO as $tag) {
               if (isset($tag->KEYNAME)
                       AND $tag->KEYNAME == 'TAG') {
                  if (isset($tag->KEYVALUE)
                          AND $tag->KEYVALUE != '') {
                     $pfAgent = new PluginFusioninventoryAgent();
                     $input = array();
                     $input['id'] = $_SESSION['plugin_fusioninventory_agents_id'];
                     $input['tag'] = $tag->KEYVALUE;
                     $pfAgent->update($input);
                  }                  
               }
            }
         }
         
         
         
      $pfBlacklist = new PluginFusinvinventoryBlacklist();
      $p_xml = $pfBlacklist->cleanBlacklist($p_xml);

      $this->p_xml = $p_xml;
//      $_SESSION['SOURCEXML'] = $p_xml;

      $xml = $p_xml;
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
                  if ((isset($network->IPADDRESS)) AND (!empty($network->IPADDRESS))) {
                     if ((string)$network->IPADDRESS != '127.0.0.1') {
                        $input['ip'][] = (string)$network->IPADDRESS;
                     }
                  }
                  if ((isset($network->IPSUBNET)) AND (!empty($network->IPSUBNET))) {
                     $input['subnet'][] = (string)$network->IPSUBNET;
                  }
               }
            }
         }
         if ((isset($xml->CONTENT->HARDWARE->WINPRODKEY)) 
               AND (!empty($xml->CONTENT->HARDWARE->WINPRODKEY))) {
            $input['mskey'] = (string)$xml->CONTENT->HARDWARE->WINPRODKEY;
         }
         if ((isset($xml->CONTENT->HARDWARE->OSNAME)) 
               AND (!empty($xml->CONTENT->HARDWARE->OSNAME))) {
            $input['osname'] = (string)$xml->CONTENT->HARDWARE->OSNAME;

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
         if ((isset($xml->CONTENT->ACCOUNTINFO->KEYNAME)) AND ($xml->CONTENT->ACCOUNTINFO->KEYNAME == 'TAG')) {
            if (isset($xml->CONTENT->ACCOUNTINFO->KEYVALUE)) {
               $input['tag'] = (string)$xml->CONTENT->ACCOUNTINFO->KEYVALUE;
            }
         }
         if ((isset($xml->CONTENT->HARDWARE->NAME)) 
                 AND ((string)$xml->CONTENT->HARDWARE->NAME != '')) {
            $input['name'] = (string)$xml->CONTENT->HARDWARE->NAME;
         } else {
            $input['name'] = '';
         }
         $input['itemtype'] = "Computer";
         
         // If transfer is disable, get entity and search only on this entity (see http://forge.fusioninventory.org/issues/1503)
         $pfConfig = new PluginFusioninventoryConfig();
         $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');

         if ($pfConfig->getValue($plugins_id, 'transfers_id_auto') == '0') {
            $inputent = $input;
            if ((isset($xml->CONTENT->HARDWARE->WORKGROUP)) AND (!empty($xml->CONTENT->HARDWARE->WORKGROUP))) {
               $inputent['domain'] = Toolbox::addslashes_deep((string)$xml->CONTENT->HARDWARE->WORKGROUP);
            }
            if (isset($inputent['serial'])) {
               $inputent['serialnumber'] = $inputent['serial'];
            }
            $ruleEntity = new PluginFusinvinventoryRuleEntityCollection();
            $dataEntity = array ();
            $dataEntity = $ruleEntity->processAllRules($inputent, array());
            if (isset($dataEntity['entities_id'])) {
               $_SESSION['plugin_fusioninventory_entityrestrict'] = $dataEntity['entities_id'];
               $input['entities_id'] = $dataEntity['entities_id'];
            }
         }
         // End transfer disabled
         
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "PluginFusinvinventoryInventory";
      $rule = new PluginFusioninventoryRuleImportEquipmentCollection();
      $data = array();
      $data = $rule->processAllRules($input, array(), array('class'=>$this));
      PluginFusioninventoryLogger::logIfExtradebug("pluginFusioninventory-rules", 
                                                   print_r($data, true));
      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         $this->rulepassed(0, "Computer");
      } else if (!isset($data['found_equipment'])) {
         $pFusioninventoryIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
         $inputdb = array();
         $inputdb['name'] = $input['name'];
         $inputdb['date'] = date("Y-m-d H:i:s");
         $inputdb['itemtype'] = "Computer";
         
         if ((isset($xml->CONTENT->HARDWARE->WORKGROUP)) AND (!empty($xml->CONTENT->HARDWARE->WORKGROUP))) {
            $input['domain'] = Toolbox::addslashes_deep((string)$xml->CONTENT->HARDWARE->WORKGROUP);
         }
         if (isset($input['serial'])) {
            $input['serialnumber'] = $input['serial'];
         }
         if ($pfConfig->getValue($plugins_id, 'transfers_id_auto') != '0') {
            $ruleEntity = new PluginFusinvinventoryRuleEntityCollection();
            $dataEntity = array ();
            $dataEntity = $ruleEntity->processAllRules($input, array());
            if (isset($dataEntity['entities_id'])) {
               $inputdb['entities_id'] = $dataEntity['entities_id'];
            }
         }
         
         if (isset($input['ip'])) {
            $inputdb['ip'] = exportArrayToDB($input['ip']);
         }
         if (isset($input['mac'])) {
            $inputdb['mac'] = exportArrayToDB($input['mac']);
         }
         $inputdb['rules_id'] = $data['_ruleid'];
         $inputdb['method'] = 'inventory';
         $pFusioninventoryIgnoredimportdevice->add($inputdb);
      }
   }
   


   /**
   * If rule have found computer or rule give to create computer
   *
   * @param $items_id integer id of the computer found (or 0 if must be created)
   * @param $itemtype value Computer type here
   *
   * @return nothing
   *
   **/
   function rulepassed($items_id, $itemtype) {
      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Rule passed : ".$items_id.", ".$itemtype."\n"
      );
      //$xml = simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA);
      //$xml = $_SESSION['SOURCEXML'];
      $xml = $this->p_xml;
      
      if ($itemtype == 'Computer') {
         $pfLib = new PluginFusinvinventoryLib();
         $Computer = new Computer();

         // ** Get entity with rules
            $input_rules = array();
            if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
               $input_rules['serialnumber'] = (string)$xml->CONTENT->BIOS->SSN;
            }
            if ((isset($xml->CONTENT->HARDWARE->NAME)) AND (!empty($xml->CONTENT->HARDWARE->NAME))) {
               $input_rules['name'] = (string)$xml->CONTENT->HARDWARE->NAME;
            }
            if (isset($xml->CONTENT->NETWORKS)) {
               foreach($xml->CONTENT->NETWORKS as $network) {
                  if ((isset($network->IPADDRESS)) AND (!empty($network->IPADDRESS))) {
                     if ((string)$network->IPADDRESS != '127.0.0.1') {
                        $input_rules['ip'][] = (string)$network->IPADDRESS;
                     }
                  }
                  if ((isset($network->IPSUBNET)) AND (!empty($network->IPSUBNET))) {
                     $input_rules['subnet'][] = (string)$network->IPSUBNET;
                  }
               }
            }
            if ((isset($xml->CONTENT->HARDWARE->WORKGROUP)) AND (!empty($xml->CONTENT->HARDWARE->WORKGROUP))) {
               $input_rules['domain'] = (string)$xml->CONTENT->HARDWARE->WORKGROUP;
            }
            if ((isset($xml->CONTENT->ACCOUNTINFO->KEYNAME)) 
                  AND ($xml->CONTENT->ACCOUNTINFO->KEYNAME == 'TAG')) {
               if (isset($xml->CONTENT->ACCOUNTINFO->KEYVALUE)) {
                  $input_rules['tag'] = (string)$xml->CONTENT->ACCOUNTINFO->KEYVALUE;
               }
            }

            $ruleEntity = new PluginFusinvinventoryRuleEntityCollection();
            $dataEntity = array ();
            $dataEntity = $ruleEntity->processAllRules($input_rules, array());
            if (isset($dataEntity['_ignore_import'])) {
               return;
            }
            if (isset($dataEntity['entities_id'])) {
               if ($dataEntity['entities_id'] == "-1") {
                  $_SESSION["plugin_fusinvinventory_entity"] = 0;
               } else {
                  $_SESSION["plugin_fusinvinventory_entity"] = $dataEntity['entities_id'];
               }
            } else {
               $_SESSION["plugin_fusinvinventory_entity"] = "N/A";
            }
            

            PluginFusioninventoryLogger::logIfExtradebug(
               "pluginFusinvinventory-entityrules",
               print_r($dataEntity, true)
            );
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = "'".$_SESSION["plugin_fusinvinventory_entity"]."'";
         }
         if ($items_id == '0') {
            if ($_SESSION["plugin_fusinvinventory_entity"] == NOT_AVAILABLE) {
               $_SESSION["plugin_fusinvinventory_entity"] = 0;
            }
            $input = array();
            $input['date_mod'] = date("Y-m-d H:i:s");
            $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
            if (isset($dataEntity['locations_id'])) {
               $input["locations_id"] = $dataEntity['locations_id'];
            }

            self::addDefaultStateIfNeeded($input, false);
            $items_id = $Computer->add($input);
            if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
               $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
               $inputrulelog = array();
               $inputrulelog['date'] = date('Y-m-d H:i:s');
               $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
               if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
                  $inputrulelog['plugin_fusioninventory_agents_id'] = $_SESSION['plugin_fusioninventory_agents_id'];
               }
               $inputrulelog['items_id'] = $items_id;
               $inputrulelog['itemtype'] = $itemtype;
               $inputrulelog['method'] = 'inventory';
               $pfRulematchedlog->add($inputrulelog);
               $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
               unset($_SESSION['plugin_fusioninventory_rules_id']);
            }
            $pfLib->startAction($xml, $items_id, '1');
         } else {
            $computer = new Computer();
            $operatingSystem = new OperatingSystem();
            $computer->getFromDB($items_id);
            if ((isset($xml->CONTENT->HARDWARE->OSNAME)) 
                    AND ($computer->fields['operatingsystems_id'] 
                            != $operatingSystem->importExternal((string)$xml->CONTENT->HARDWARE->OSNAME,
                                                                $_SESSION["plugin_fusinvinventory_entity"]))) {
               $_SESSION["plugin_fusinvinventory_history_add"] = false;
               $_SESSION["plugin_fusinvinventory_no_history_add"] = true;
            }
            $pfLib->startAction($xml, $items_id, '0');
         }
      } else if ($itemtype == 'PluginFusioninventoryUnknownDevice') {
         $class = new $itemtype();
         if ($items_id == "0") {
            $input = array();
            $input['date_mod'] = date("Y-m-d H:i:s");
            $items_id = $class->add($input);
            if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
               $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
               $inputrulelog = array();
               $inputrulelog['date'] = date('Y-m-d H:i:s');
               $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
               if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
                  $inputrulelog['plugin_fusioninventory_agents_id'] = $_SESSION['plugin_fusioninventory_agents_id'];
               }
               $inputrulelog['items_id'] = $items_id;
               $inputrulelog['itemtype'] = $itemtype;
               $inputrulelog['method'] = 'inventory';
               $pfRulematchedlog->add($inputrulelog);
               $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
               unset($_SESSION['plugin_fusioninventory_rules_id']);
            }
         }
         $class->getFromDB($items_id);
         $_SESSION["plugin_fusinvinventory_entity"] = $class->fields['entities_id'];
         $input = array();
         $input['id'] = $class->fields['id'];
         
         // Write XML file
         if (isset($xml)) {
            PluginFusioninventoryUnknownDevice::writeXML($items_id, $xml->asXML());
         }
         
         if (isset($xml->CONTENT->HARDWARE->NAME)) {
            $input['name'] = (string)$xml->CONTENT->HARDWARE->NAME;
         }
         $input['item_type'] = "Computer";
         if (isset($xml->CONTENT->HARDWARE->WORKGROUP)) {
            $input['domain'] = Dropdown::importExternal("Domain",
                                                        (string)$xml->CONTENT->HARDWARE->WORKGROUP,
                                                        $_SESSION["plugin_fusinvinventory_entity"]);
         }
         if (isset($xml->CONTENT->BIOS->SSN)) {
            $input['serial'] = (string)$xml->CONTENT->BIOS->SSN;
         } else if(isset($xml->CONTENT->BIOS->MSN)) {
            $input['serial'] = (string)$xml->CONTENT->BIOS->MSN;
         }
         $class->update($input);
      }
   }
   
   

   /**
    * Get default value for state of devices (monitor, printer...)
    * 
    * @param type $input
    * @param type $check_management
    * @param type $management_value 
    * 
    */
   static function addDefaultStateIfNeeded(&$input, $check_management = false, $management_value = 0) {
      $config = new PluginFusioninventoryConfig();
      $state = $config->getValue($_SESSION["plugin_fusinvinventory_moduleid"], "states_id_default");
      if ($state) {
         if (!$check_management || ($check_management && !$management_value)) {
            $input['states_id'] = $state;         
         }      
      }
   }
   
   

   /**
   * Create GLPI existant computer (never in lib) in Lib FusionInventory
   *
   * @param $items_id integer id of the computer
   * @param $internal_id value uniq id in internal lib
   *
   * @return nothing
   *
   **/
   function createMachineInLib($items_id, $internal_id) {

      $NetworkPort = new NetworkPort();
      $Computer = new Computer();
      $a_sectionsinfos = array();

      $Computer->getFromDB($items_id);
      $datas = $Computer->fields;

      $xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><REQUEST></REQUEST>");
      $xml_content = $xml->addChild('IMPORT', 'GLPI');
      $xml_content = $xml->addChild('CONTENT');

      // ** NETWORKS
      $a_networkport = $NetworkPort->find("`items_id`='".$items_id."' AND `itemtype`='Computer' ");
      foreach ($a_networkport as $networkport_id => $networkport_data) {
         $a_sectionsinfos[] = "NETWORKS/".$networkport_id;
         $xml_networks = $xml_content->addChild("NETWORKS");
         $xml_networks->addChild("MACADDR", $networkport_data['mac']);
         $xml_networks->addChild("IPADDRESS", $networkport_data['ip']);
         $xml_networks->addChild("IPMASK", $networkport_data['netmask']);
         $xml_networks->addChild("IPSUBNET", $networkport_data['subnet']);
         $xml_networks->addChild("IPGATEWAY", $networkport_data['gateway']);
         $xml_networks->addChild("DESCRIPTION", $networkport_data['name']);
         $network_type = Dropdown::getDropdownName('glpi_networkinterfaces', 
                                                   $networkport_data['networkinterfaces_id']);
         if ($network_type != "&nbsp;") {
            $xml_networks->addChild("TYPE", $network_type);
         }
      }

      // ** BIOS
      $xml_bios = $xml_content->addChild("BIOS");
      $a_sectionsinfos[] = "BIOS/".$items_id;
      $xml_bios->addChild("SSN", $datas['serial']);
      $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                $datas['manufacturers_id']);
      if ($manufacturer != "&nbsp;") {
         $xml_bios->addChild("SMANUFACTURER", $manufacturer);
      }
      $model = Dropdown::getDropdownName(getTableForItemType('ComputerModel'), 
                                         $datas['computermodels_id']);
      if ($model != "&nbsp;") {
         $xml_bios->addChild("SMODEL", $model);
      }
      $type = Dropdown::getDropdownName(getTableForItemType('ComputerType'), 
                                        $datas['computertypes_id']);
      if ($type != "&nbsp;") {
         $xml_bios->addChild("TYPE", $type);
      }

      // ** HARDWARE
      $xml_hardware = $xml_content->addChild("HARDWARE");
      $a_sectionsinfos[] = "HARDWARE/".$items_id;
      $xml_hardware->addChild("NAME", $datas['name']);
      $osname = Dropdown::getDropdownName(getTableForItemType('OperatingSystem'), 
                                          $datas['operatingsystems_id']);
      if ($osname != "&nbsp;") {
         $xml_bios->addChild("OSNAME", $osname);
      }
      $osversion = Dropdown::getDropdownName(getTableForItemType('OperatingSystemVersion'), 
                                             $datas['operatingsystemversions_id']);
      if ($osversion != "&nbsp;") {
         $xml_bios->addChild("OSVERSION", $osversion);
      }
      $xml_hardware->addChild("WINPRODID", $datas['os_licenseid']);
      $xml_hardware->addChild("WINPRODKEY", $datas['os_license_number']);
      $workgroup = Dropdown::getDropdownName(getTableForItemType('Domain'), $datas['domains_id']);
      if ($workgroup != "&nbsp;") {
         $xml_bios->addChild("WORKGROUP", $workgroup);
      }
      $xml_hardware->addChild("DESCRIPTION", $datas['comment']);

      // ** CONTROLLERS
      $CompDeviceControl = new Computer_Device('DeviceControl');
      $DeviceControl = new DeviceControl();
      $a_deviceControl = $CompDeviceControl->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceControl as $deviceControl_id => $deviceControl_data) {
         $a_sectionsinfos[] = "CONTROLLERS/".$deviceControl_id;
         $xml_controller = $xml_content->addChild("CONTROLLERS");
         $DeviceControl->getFromDB($deviceControl_data['devicecontrols_id']);
         $xml_controller->addChild("CAPTION", $DeviceControl->fields['designation']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceControl->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_controller->addChild("MANUFACTURER", $manufacturer);
         }
         $xml_controller->addChild("NAME", $DeviceControl->fields['designation']);
      }

      // ** CPUS
      $CompDeviceProcessor = new Computer_Device('DeviceProcessor');
      $DeviceProcessor = new DeviceProcessor();
      $a_deviceProcessor = $CompDeviceProcessor->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceProcessor as $deviceProcessor_id => $deviceProcessor_data) {
         $a_sectionsinfos[] = "CPUS/".$deviceProcessor_id;
         $xml_cpu = $xml_content->addChild("CPUS");
         $DeviceProcessor->getFromDB($deviceProcessor_data['deviceprocessors_id']);
         $xml_cpu->addChild("NAME", $DeviceProcessor->fields['designation']);
         $xml_cpu->addChild("SPEED", $deviceProcessor_data['specificity']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceProcessor->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_cpu->addChild("MANUFACTURER", $manufacturer);
         }
      }
      
      // ** STORAGE
      $CompDeviceDrive = new Computer_Device('DeviceDrive');
      $DeviceDrive = new DeviceDrive();
      $a_deviceDrive = $CompDeviceDrive->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceDrive as $deviceDrive_id => $deviceDrive_data) {
         $a_sectionsinfos[] = "STORAGES/d".$deviceDrive_id;
         $xml_storage = $xml_content->addChild("STORAGES");
         $DeviceDrive->getFromDB($deviceDrive_data['devicedrives_id']);
         $xml_storage->addChild("NAME", $DeviceDrive->fields['designation']);
         $xml_storage->addChild("MODEL", $DeviceDrive->fields['designation']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceDrive->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_storage->addChild("MANUFACTURER", $manufacturer);
         }
         $interface = Dropdown::getDropdownName(getTableForItemType('InterfaceType'), 
                                                $DeviceDrive->fields['interfacetypes_id']);
         if ($interface != "&nbsp;") {
            $xml_storage->addChild("INTERFACE", $interface);
         }
      }
      $CompDeviceHardDrive = new Computer_Device('DeviceHardDrive');
      $DeviceHardDrive = new DeviceHardDrive();
      $a_DeviceHardDrive = $CompDeviceHardDrive->find("`computers_id`='".$items_id."' ");
      foreach ($a_DeviceHardDrive as $DeviceHardDrive_id => $DeviceHardDrive_data) {
         $a_sectionsinfos[] = "STORAGES/".$DeviceHardDrive_id;
         $xml_storage = $xml_content->addChild("STORAGES");
         $DeviceHardDrive->getFromDB($DeviceHardDrive_data['deviceharddrives_id']);
         $xml_storage->addChild("NAME", $DeviceHardDrive->fields['designation']);
         $xml_storage->addChild("MODEL", $DeviceHardDrive->fields['designation']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceHardDrive->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_storage->addChild("MANUFACTURER", $manufacturer);
         }
         $interface = Dropdown::getDropdownName(getTableForItemType('InterfaceType'), 
                                                $DeviceHardDrive->fields['interfacetypes_id']);
         if ($interface != "&nbsp;") {
            $xml_storage->addChild("INTERFACE", $interface);
         }
      }

      // ** DRIVES
      $ComputerDisk = new ComputerDisk;
      $a_disk = $ComputerDisk->find("`computers_id`='".$items_id."' ");
      foreach ($a_disk as $disk_id => $disk_data) {
         $a_sectionsinfos[] = "DRIVES/".$disk_id;
         $xml_drive = $xml_content->addChild("DRIVES");
         $xml_drive->addChild("LABEL", $disk_data['name']);
         $xml_drive->addChild("VOLUMN", $disk_data['device']);
         $xml_drive->addChild("MOUNTPOINT", $disk_data['mountpoint']);
         $filesystem = Dropdown::importExternal('Filesystem', 
                                                $disk_data['filesystems_id'],
                                                $_SESSION["plugin_fusinvinventory_entity"]);
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
         $a_sectionsinfos[] = "MEMORIES/".$deviceMemory_id;
         $xml_memory = $xml_content->addChild("MEMORIES");
         $DeviceMemory->getFromDB($deviceMemory_data['devicememories_id']);
         $xml_memory->addChild("DESCRIPTION", $DeviceMemory->fields['designation']);
         $xml_memory->addChild("CAPACITY", $deviceMemory_data['specificity']);
         $xml_memory->addChild("SPEED", $DeviceMemory->fields['frequence']);
         $type = Dropdown::getDropdownName(getTableForItemType('DeviceMemoryType'), 
                                           $DeviceMemory->fields['devicememorytypes_id']);
         if ($type != "&nbsp;") {
            $xml_memory->addChild("TYPE", $type);
         }
      }


      // ** MONITORS
      $Monitor = new Monitor();
      $Computer_Item = new Computer_Item();
      $a_ComputerMonitor = $Computer_Item->find("`computers_id`='".$items_id.
                                                "' AND 'itemtype' = 'Monitor'");
      foreach ($a_ComputerMonitor as $ComputerMonitor_id => $ComputerMonitor_data) {
         $a_sectionsinfos[] = "MONITORS/".$ComputerMonitor_id;
         $xml_monitor = $xml_content->addChild("MONITORS");
         $Monitor->getFromDB($ComputerMonitor_data['items_id']);
         $xml_monitor->addChild("CAPTION", $Monitor->fields['name']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $Monitor->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_monitor->addChild("MANUFACTURER", $manufacturer);
         }
         $xml_monitor->addChild("SERIAL", $Monitor->fields['serial']);
         $xml_monitor->addChild("DESCRIPTION", $Monitor->fields['comment']);
      }


      // ** PRINTERS
      $Printer = new Printer();
      $Computer_Item = new Computer_Item();
      $a_ComputerPrinter = $Computer_Item->find("`computers_id`='".$items_id.
                                                "' AND 'itemtype' = 'Printer'");
      foreach ($a_ComputerPrinter as $ComputerPrinter_id => $ComputerPrinter_data) {
         $a_sectionsinfos[] = "PRINTERS/".$ComputerPrinter_id;
         $xml_printer = $xml_content->addChild("PRINTERS");
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
         $a_sectionsinfos[] = "SOFTWARES/".$softwareversion_id;
         $xml_software = $xml_content->addChild("SOFTWARES");
         $xml_software->addChild("NAME", $Software->fields['name']);
         $xml_software->addChild("VERSION", $SoftwareVersion->fields['name']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $Software->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_software->addChild("PUBLISHER", $manufacturer);
         }
      }

      
      // ** SOUNDS
      $CompDeviceSoundCard = new Computer_Device('DeviceSoundCard');
      $DeviceSoundCard = new DeviceSoundCard();
      $a_deviceSoundCard = $CompDeviceSoundCard->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceSoundCard as $deviceSoundCard_id => $deviceSoundCard_data) {
         $a_sectionsinfos[] = "SOUNDS/".$deviceSoundCard_id;
         $xml_sound = $xml_content->addChild("SOUNDS");
         $DeviceSoundCard->getFromDB($deviceSoundCard_data['devicesoundcards_id']);
         $xml_sound->addChild("NAME", $DeviceSoundCard->fields['designation']);
         $xml_sound->addChild("DESCRIPTION", $DeviceSoundCard->fields['comment']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceSoundCard->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_sound->addChild("MANUFACTURER", $manufacturer);
         }
      }
     

      // ** VIDEOS
      $CompDeviceGraphicCard = new Computer_Device('DeviceGraphicCard');
      $DeviceGraphicCard = new DeviceGraphicCard();
      $a_deviceGraphicCard = $CompDeviceGraphicCard->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceGraphicCard as $deviceGraphicCard_id => $deviceGraphicCard_data) {
         $a_sectionsinfos[] = "VIDEOS/".$deviceGraphicCard_id;
         $xml_video = $xml_content->addChild("VIDEOS");
         $DeviceGraphicCard->getFromDB($deviceGraphicCard_data['devicegraphiccards_id']);
         $xml_video->addChild("NAME", $DeviceGraphicCard->fields['designation']);
         $xml_video->addChild("MEMORY", $deviceGraphicCard_data['specificity']);
      }

      // ** VIRTUALMACHINES
      $ComputerVirtualMachine = new ComputerVirtualMachine();
      $a_VirtualMachines = $ComputerVirtualMachine->find("`computers_id`='".$items_id."' ");
      foreach ($a_VirtualMachines as $VirtualMachines_id=>$VirtualMachines_data) {
         $a_sectionsinfos[] = "VIRTUALMACHINES/".$VirtualMachines_id;
         $xml_virtualmachine = $xml_content->addChild("VIRTUALMACHINES");
         $xml_virtualmachine->addChild("MEMORY", $VirtualMachines_data['ram']."MB");
         $xml_virtualmachine->addChild("NAME", $VirtualMachines_data['name']);

         $xml_virtualmachine->addChild("STATUS", 
                                       Dropdown::getDropdownName("glpi_virtualmachinestates",
                                                                 $VirtualMachines_data['virtualmachinestates_id']));
         $xml_virtualmachine->addChild("SUBSYSTEM", 
                                       Dropdown::getDropdownName("glpi_virtualmachinesystems",
                                                                 $VirtualMachines_data['virtualmachinesystems_id']));
         $xml_virtualmachine->addChild("UUID", $VirtualMachines_data['uuid']);
         $xml_virtualmachine->addChild("VCPU", $VirtualMachines_data['vcpu']);
         $xml_virtualmachine->addChild("VMTYPE", 
                                       Dropdown::getDropdownName("glpi_virtualmachinetypes",
                                                                 $VirtualMachines_data['virtualmachinetypes_id']));
      }
      
      // ** USBDEVICES (PERIPHERALS)
      $Peripheral = new Peripheral();
      $Computer_Item = new Computer_Item();
      $a_ComputerPeripheral = $Computer_Item->find("`computers_id`='".$items_id."' AND `itemtype`='Peripheral'");
      foreach ($a_ComputerPeripheral as $ComputerPeripheral_id => $ComputerPeripheral_data) {
         $a_sectionsinfos[] = "USBDEVICES/".$ComputerPeripheral_id;
         $xml_peripheral = $xml_content->addChild("USBDEVICES");
         $Peripheral->getFromDB($ComputerPeripheral_data['items_id']);
         $xml_peripheral->addChild("NAME", $Peripheral->fields['name']);
         if ($Peripheral->fields['serial'] != "") {
            $xml_peripheral->addChild("SERIAL", $Peripheral->fields['serial']);
         }
      }
      
      $pfLib = new PluginFusinvinventoryLib();
      $pfLib->addLibMachineFromGLPI($items_id, $internal_id, $xml, $a_sectionsinfos);
   }
   
   
   
   /**
    * Return method name of this class/plugin
    * 
    * @return value  
    */
   static function getMethod() {
      return 'inventory';
   }
}

?>