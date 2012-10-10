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

class PluginFusioninventoryInventoryComputerInventory {
   private $p_xml;
   private $arrayinventory = array();

   /**
   * Import data
   *
   * @param $p_DEVICEID XML code to import
   * @param $p_CONTENT XML code of the Computer
   * @param $p_CONTENT XML code of all agent have sent
   *
   * @return nothing (import ok) / error string (import ko)
   **/
   function import($p_DEVICEID, $a_CONTENT, $arrayinventory) {
      global $DB;

      $errors = '';

      $ret = $DB->query("SELECT GET_LOCK('inventory', 15)");
      if ($DB->result($ret, 0, 0) == 1) {
          $this->sendCriteria($p_DEVICEID, $a_CONTENT, $arrayinventory);

          $DB->request("SELECT RELEASE_LOCK('inventory')");
      } else {
          die ("TIMEOUT: SERVER OVERLOADED\n");
      }

      return $errors;
   }



   /**
   * Send Computer to inventoryruleimport
   *
   * @param $p_DEVICEID XML code to import
   * @param $p_CONTENT XML code of the Computer
   * @param $p_CONTENT XML code of all agent have sent
   *
   * @return nothing
   *
   **/
   function sendCriteria($p_DEVICEID, $a_CONTENT, $arrayinventory) {

      pluginfusioninventoryconfig::loadCache();
      // * Hacks

         // Hack to put OS in software
         $inputos = array();
         $inputos['COMMENTS'] = $arrayinventory['CONTENT']['HARDWARE']['OSCOMMENTS'];
         $inputos['NAME']     = $arrayinventory['CONTENT']['HARDWARE']['OSNAME'];
         $inputos['VERSION']  = $arrayinventory['CONTENT']['HARDWARE']['OSVERSION'];
         $arrayinventory['CONTENT']['SOFTWARES'][] = $inputos;
         
         // Hack for USB Printer serial
         if (isset($arrayinventory['CONTENT']['PRINTERS'])) {
            foreach($arrayinventory['CONTENT']['PRINTERS'] as $key=>$printer) {
               if ((isset($printer['SERIAL']))
                       AND (preg_match('/\/$/', $printer['SERIAL']))) {
                  $arrayinventory['CONTENT']['PRINTERS'][$key]['SERIAL'] = preg_replace('/\/$/', '', $printer['SERIAL']);
               }
            }
         }

         // Hack to remove Memories with Flash types see ticket http://forge.fusioninventory.org/issues/1337
         if (isset($arrayinventory['CONTENT']['MEMORIES'])) {
            $i = 0;
            $arrayName = array();
            foreach($arrayinventory['CONTENT']['MEMORIES'] as $key=>$memory) {
               if ((isset($memory['TYPE']))
                       AND (preg_match('/Flash/', $memory['TYPE']))) {
                  
                  unset($arrayinventory['CONTENT']['MEMORIES'][$key]);
               }
            }
         }
      // End hack
      $a_computerinventory = PluginFusioninventoryFormatconvert::computerInventoryTransformation($arrayinventory['CONTENT']);

      // Get tag is defined and put it in fusioninventory_agent table
         if (isset($a_computerinventory['ACCOUNTINFO'])) {
            foreach($a_computerinventory['ACCOUNTINFO'] as $tag) {
               if (isset($tag['KEYNAME'])
                       AND $tag['KEYNAME'] == 'TAG') {
                  if (isset($tag['KEYVALUE'])
                          AND $tag['KEYVALUE'] != '') {
                     $pfAgent = new PluginFusioninventoryAgent();
                     $input = array();
                     $input['id'] = $_SESSION['plugin_fusioninventory_agents_id'];
                     $input['tag'] = $tag['KEYVALUE'];
                     $pfAgent->update($input);
                  }
               }
            }
         }


      $pfBlacklist = new PluginFusioninventoryInventoryComputerBlacklist();
      $a_computerinventory = $pfBlacklist->cleanBlacklist($a_computerinventory);

      $this->arrayinventory = $a_computerinventory;

      $input = array();

      // Global criterias

         if ((isset($a_computerinventory['computer']['serial'])) 
                 AND (!empty($a_computerinventory['computer']['serial']))) {
            $input['serial'] = $a_computerinventory['computer']['serial'];
         }
         if ((isset($a_computerinventory['computer']['uuid'])) 
                 AND (!empty($a_computerinventory['computer']['uuid']))) {
            $input['uuid'] = $a_computerinventory['computer']['uuid'];
         }
         foreach($a_computerinventory['networkport'] as $network) {
            if (((isset($network['virtualdev'])) AND ($network['virtualdev'] != '1'))
                    OR (!isset($network['virtualdev']))){
               if ((isset($network['mac'])) AND (!empty($network['mac']))) {
                  $input['mac'][] = $network['mac'];
               }
               foreach ($network['ipaddress'] as $ip) {
                  if ($ip != '127.0.0.1' AND $ip != '::1') {
                     $input['ip'][] = $ip;
                  }
               }
               if ((isset($network['subnet'])) AND (!empty($network['subnet']))) {
                  $input['subnet'][] = $network['subnet'];
               }
            }
         }
         if ((isset($a_computerinventory['computer']['os_license_number']))
               AND (!empty($a_computerinventory['computer']['os_license_number']))) {
            $input['mskey'] = $a_computerinventory['computer']['os_license_number'];
         }
         if ((isset($a_computerinventory['computer']['operatingsystems_id']))
               AND (!empty($a_computerinventory['computer']['operatingsystems_id']))) {
            $input['osname'] = $a_computerinventory['computer']['operatingsystems_id'];

         }
         if ((isset($a_computerinventory['computer']['models_id'])) 
                 AND (!empty($a_computerinventory['computer']['models_id']))) {
            $input['model'] = $a_computerinventory['computer']['models_id'];
         }
         // TODO
//         if (isset($arrayinventory['CONTENT']['STORAGES'])) {
//            foreach($arrayinventory['CONTENT']['STORAGES'] as $storage) {
//               if ((isset($storage['SERIALNUMBER'])) AND (!empty($storage['SERIALNUMBER']))) {
//                  $input['partitionserial'][] = $storage['SERIALNUMBER'];
//               }
//            }
//         }
//         if (isset($arrayinventory['CONTENT']['computerdisk'])) {
//            foreach($arrayinventory['CONTENT']['DRIVES'] as $drive) {
//               if ((isset($drive['SERIAL'])) AND (!empty($drive['SERIAL']))) {
//                  $input['hdserial'][] = $drive['SERIAL'];
//               }
//            }
//         }
         if ((isset($a_computerinventory['ACCOUNTINFO']['KEYNAME'])) 
                 AND ($a_computerinventory['ACCOUNTINFO']['KEYNAME'] == 'TAG')) {
            if (isset($a_computerinventory['ACCOUNTINFO']['KEYVALUE'])) {
               $input['tag'] = $a_computerinventory['ACCOUNTINFO']['KEYVALUE'];
            }
         }
         if ((isset($a_computerinventory['computer']['name']))
                 AND ($a_computerinventory['computer']['name'] != '')) {
            $input['name'] = $a_computerinventory['computer']['name'];
         } else {
            $input['name'] = '';
         }
         $input['itemtype'] = "Computer";

         // If transfer is disable, get entity and search only on this entity (see http://forge.fusioninventory.org/issues/1503)
         $pfConfig = new PluginFusioninventoryConfig();
         $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

         //if ($pfConfig->getValue($plugins_id, 'transfers_id_auto', 'inventory') == '0') {
            $inputent = $input;
            if ((isset($a_computerinventory['computer']['domains_id'])) 
                    AND (!empty($a_computerinventory['computer']['domains_id']))) {
               $inputent['domain'] = $a_computerinventory['computer']['domains_id'];
            }
            if (isset($inputent['serial'])) {
               $inputent['serialnumber'] = $inputent['serial'];
            }
            $ruleEntity = new PluginFusioninventoryInventoryRuleEntityCollection();
            $dataEntity = array ();
            $dataEntity = $ruleEntity->processAllRules($inputent, array());
            if (isset($dataEntity['entities_id'])) {
               $_SESSION['plugin_fusioninventory_entityrestrict'] = $dataEntity['entities_id'];
               $input['entities_id'] = $dataEntity['entities_id'];
            }
            if (isset($dataEntity['locations_id'])) {
               $_SESSION['plugin_fusioninventory_locations_id'] = $dataEntity['locations_id'];
            }
         //}
         // End transfer disabled

      $_SESSION['plugin_fusioninventory_classrulepassed'] = "PluginFusioninventoryInventoryComputerInventory";
      $rule = new PluginFusioninventoryInventoryRuleImportCollection();
      $data = array();
      $data = $rule->processAllRules($input, array(), array('class'=>$this));
      PluginFusioninventoryToolbox::logIfExtradebug("pluginFusioninventory-rules",
                                                   $data);
      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         $this->rulepassed(0, "Computer");
      } else if (!isset($data['found_equipment'])) {
         $pFusioninventoryIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
         $inputdb = array();
         $inputdb['name'] = $input['name'];
         $inputdb['date'] = date("Y-m-d H:i:s");
         $inputdb['itemtype'] = "Computer";

         if ((isset($a_computerinventory['computer']['domains_id'])) 
                    AND (!empty($a_computerinventory['computer']['domains_id']))) {
               $input['domain'] = $a_computerinventory['computer']['domains_id'];
            }
         if (isset($input['serial'])) {
            $input['serialnumber'] = $input['serial'];
         }
         if ($pfConfig->getValue($plugins_id, 'transfers_id_auto', 'inventory') != '0') {
            $ruleEntity = new PluginFusioninventoryInventoryRuleEntityCollection();
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
      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Rule passed : ".$items_id.", ".$itemtype."\n"
      );
      $a_computerinventory = $this->arrayinventory;

      if ($itemtype == 'Computer') {
         
         if ($items_id == '0') {
            if (isset($_SESSION['plugin_fusioninventory_entityrestrict'])) {
               $_SESSION["plugin_fusinvinventory_entity"] = $_SESSION['plugin_fusioninventory_entityrestrict'];
            }
            if (!isset($_SESSION["plugin_fusinvinventory_entity"])
                    OR $_SESSION["plugin_fusinvinventory_entity"] == NOT_AVAILABLE
                    OR $_SESSION["plugin_fusinvinventory_entity"] == '-1') {
               $_SESSION["plugin_fusinvinventory_entity"] = 0;
            }
            $_SESSION['glpiactiveentities'] = array($_SESSION["plugin_fusinvinventory_entity"]);
            $_SESSION['glpiactiveentities_string'] = $_SESSION["plugin_fusinvinventory_entity"];
            $_SESSION['glpiactive_entity'] = $_SESSION["plugin_fusinvinventory_entity"];
            
            $a_computerinventory = PluginFusioninventoryFormatconvert::computerSoftwareTransformation($a_computerinventory);
            
            $this->addNewComputer($a_computerinventory);
//            if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
//               $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
//               $inputrulelog = array();
//               $inputrulelog['date'] = date('Y-m-d H:i:s');
//               $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
//               if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
//                  $inputrulelog['plugin_fusioninventory_agents_id'] = $_SESSION['plugin_fusioninventory_agents_id'];
//               }
//               $inputrulelog['items_id'] = $items_id;
//               $inputrulelog['itemtype'] = $itemtype;
//               $inputrulelog['method'] = 'inventory';
//               $pfRulematchedlog->add($inputrulelog);
//               $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
//               unset($_SESSION['plugin_fusioninventory_rules_id']);
//            }
         } else {
            $computer   = new Computer();
            $pfConfig   = new PluginFusioninventoryConfig();
            $pfLib      = new PluginFusioninventoryInventoryComputerLib();
            
            $computer->getFromDB($items_id);
            if ($pfConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"], 'transfers_id_auto', 'inventory') != 0) {
               if (isset($_SESSION['plugin_fusioninventory_entityrestrict'])) {               
                  $_SESSION["plugin_fusinvinventory_entity"] = $_SESSION['plugin_fusioninventory_entityrestrict'];
               }
               if (!isset($_SESSION["plugin_fusinvinventory_entity"])
                       OR $_SESSION["plugin_fusinvinventory_entity"] == NOT_AVAILABLE
                       OR $_SESSION["plugin_fusinvinventory_entity"] == '-1') {
                  $_SESSION["plugin_fusinvinventory_entity"] = 0;
               }
            } else {
               $_SESSION["plugin_fusinvinventory_entity"] = $computer->fields['entities_id'];
            }
            $_SESSION['glpiactiveentities'] = array($_SESSION["plugin_fusinvinventory_entity"]);
            $_SESSION['glpiactiveentities_string'] = $_SESSION["plugin_fusinvinventory_entity"];
            $_SESSION['glpiactive_entity'] = $_SESSION["plugin_fusinvinventory_entity"];
            
                        
            if ((isset($a_computerinventory['computer']['operatingsystems_id']))
                    AND ($computer->fields['operatingsystems_id'] != $a_computerinventory['computer']['operatingsystems_id'])) {
               $_SESSION["plugin_fusinvinventory_history_add"] = false;
               $_SESSION["plugin_fusinvinventory_no_history_add"] = true;
            }
            
            $a_computerinventory = PluginFusioninventoryFormatconvert::computerSoftwareTransformation($a_computerinventory);
            
            $pfLib->updateComputer($a_computerinventory, $items_id);
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

         if (isset($arrayinventory['CONTENT']['HARDWARE']['NAME'])) {
            $input['name'] = $arrayinventory['CONTENT']['HARDWARE']['NAME'];
         }
         $input['item_type'] = "Computer";
         if (isset($arrayinventory['CONTENT']['HARDWARE']['WORKGROUP'])) {
            $input['domain'] = Dropdown::importExternal("Domain",
                                                        $arrayinventory['CONTENT']['HARDWARE']['WORKGROUP'],
                                                        $_SESSION["plugin_fusinvinventory_entity"]);
         }
         if (isset($arrayinventory['CONTENT']['BIOS']['SSN'])) {
            $input['serial'] = $arrayinventory['CONTENT']['BIOS']['SSN'];
         } else if(isset($arrayinventory['CONTENT']['BIOS']['MSN'])) {
            $input['serial'] = $arrayinventory['CONTENT']['BIOS']['MSN'];
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
      $state = $config->getValue($_SESSION["plugin_fusioninventory_moduleid"],
              "states_id_default", 'inventory');
      if ($state) {
         if (!$check_management || ($check_management && !$management_value)) {
            $input['states_id'] = $state;
         }
      }
      return $input;
   }

   
   
   function addNewComputer($a_computerinventory) {
      
      $computer                     = new Computer();
      $pfInventoryComputerComputer  = new PluginFusioninventoryInventoryComputerComputer();
      $item_DeviceProcessor         = new Item_DeviceProcessor();
      $deviceProcessor              = new DeviceProcessor();
      $item_DeviceMemory            = new Item_DeviceMemory();
      $deviceMemory                 = new DeviceMemory();
      $Software                     = new Software();
      $softwareVersion              = new SoftwareVersion();
      $computer_SoftwareVersion     = new Computer_SoftwareVersion();
      $computerVirtualmachine       = new ComputerVirtualMachine();
      $computerDisk                 = new ComputerDisk();
      $item_DeviceControl           = new Item_DeviceControl();
      $deviceControl                = new DeviceControl();
      $item_DeviceGraphicCard       = new Item_DeviceGraphicCard();
      $deviceGraphicCard            = new DeviceGraphicCard();
      $item_DeviceSoundCard         = new Item_DeviceSoundCard();
      $deviceSoundCard              = new DeviceSoundCard();
      $networkPort                  = new NetworkPort();
      $networkName                  = new NetworkName();
      $iPAddress                    = new IPAddress();
      $pfInventoryComputerAntivirus = new PluginFusioninventoryInventoryComputerAntivirus();
      $pfConfig                     = new PluginFusioninventoryConfig();
      
      $a_computerinventory = PluginFusioninventoryFormatconvert::computerReplaceids($a_computerinventory);
      
      // * Computer
      $a_computerinventory['computer']['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
      if (isset($_SESSION['plugin_fusioninventory_locations_id'])) {
         $a_computerinventory['computer']["locations_id"] = $_SESSION['plugin_fusioninventory_locations_id'];
      }
      $a_computerinventory['computer'] = self::addDefaultStateIfNeeded($a_computerinventory['computer'], false);
      $computers_id = $computer->add($a_computerinventory['computer'], array(), false);
      
      // * Computer fusion (ext)
      $a_computerinventory['fusioninventorycomputer']['computers_id'] = $computers_id;
      $pfInventoryComputerComputer->add($a_computerinventory['fusioninventorycomputer'], array(), false);
      
      // * Processors
      foreach ($a_computerinventory['processor'] as $a_processor) {
         $processors_id = $deviceProcessor->import($a_processor);
         $a_processor['deviceprocessors_id'] = $processors_id;
         $a_processor['itemtype'] = 'Computer';
         $a_processor['items_id'] = $computers_id;
         $a_processor['frequency'] = $a_processor['frequence'];
         $a_processor['_no_history'] = true;
         $item_DeviceProcessor->add($a_processor);
      }
      
      // * Memories
      foreach ($a_computerinventory['memory'] as $a_memory) {
         $memories_id = $deviceMemory->import($a_memory);
         $a_memory['devicememories_id'] = $memories_id;
         $a_memory['itemtype'] = 'Computer';
         $a_memory['items_id'] = $computers_id;
         $a_memory['_no_history'] = true;
         $item_DeviceMemory->add($a_memory);
      }
      
      // * Graphiccard
      foreach ($a_computerinventory['graphiccard'] as $a_graphiccard) {
         $graphiccards_id = $deviceGraphicCard->import($a_graphiccard);
         $a_graphiccard['devicegraphiccards_id'] = $graphiccards_id;
         $a_graphiccard['itemtype'] = 'Computer';
         $a_graphiccard['items_id'] = $computers_id;
         $a_graphiccard['_no_history'] = true;
         $item_DeviceGraphicCard->add($a_graphiccard);
      }
      
      // * Sound
      foreach ($a_computerinventory['sound'] as $a_sound) {
         $sounds_id = $deviceSoundCard->import($a_sound);
         $a_sound['devicesounds_id'] = $sounds_id;
         $a_sound['itemtype'] = 'Computer';
         $a_sound['items_id'] = $computers_id;
         $a_sound['_no_history'] = true;
         $item_DeviceSoundCard->add($a_sound);
      }
      
      // * Controllers
      foreach ($a_computerinventory['controller'] as $a_controller) {
         $controllers_id = $deviceControl->import($a_controller);
         $a_controller['devicecontrols_id'] = $controllers_id;
         $a_controller['itemtype'] = 'Computer';
         $a_controller['items_id'] = $computers_id;
         $a_controller['_no_history'] = true;
         $item_DeviceControl->add($a_controller);
      }
      
      // * Software
      if ($pfConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"],
              "import_software", 'inventory') != 0) {
         foreach ($a_computerinventory['software'] as $a_software) {
            $softwares_id = $Software->addOrRestoreFromTrash($a_software['name'],
                                                            $a_software['manufacturer'],
                                                            $a_software['entities_id']);
            $a_software['softwares_id'] = $softwares_id;
            $a_software['name'] = $a_software['version'];
            $softwareversions_id = $softwareVersion->add($a_software);
            $a_software['computers_id'] = $computers_id;
            $a_software['softwareversions_id'] = $softwareversions_id;
            $a_software['_no_history'] = true;
            $computer_SoftwareVersion->add($a_software);
         }
      }

      // * Virtualmachines
      foreach ($a_computerinventory['virtualmachine'] as $a_virtualmachine) {
         $a_virtualmachine['computers_id'] = $computers_id;
         $a_virtualmachine['_no_history'] = true;
         $computerVirtualmachine->add($a_virtualmachine);
      }
      
      // * ComputerDisk
      foreach ($a_computerinventory['computerdisk'] as $a_computerdisk) {
         $a_computerdisk['computers_id'] = $computers_id;
         $a_computerdisk['_no_history'] = true;
         $computerDisk->add($a_computerdisk);
      }
      
      // * Networkports
      foreach ($a_computerinventory['networkport'] as $a_networkport) {
         $a_networkport['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
         $a_networkport['items_id'] = $computers_id;
         $a_networkport['itemtype'] = "Computer";
         $a_networkport['_no_history'] = true;
         $a_networkport['items_id'] = $networkPort->add($a_networkport);
         $a_networkport['is_recursive'] = 0;
         $a_networkport['itemtype'] = 'NetworkPort';
         $a_networknames_id = $networkName->add($a_networkport);
         foreach ($a_networkport['ipaddress'] as $ip) {
            $input = array();
            $input['items_id'] = $a_networknames_id;
            $input['itemtype'] = 'NetworkName';
            $input['name'] = $ip;
            $iPAddress->add($input);
         }
      }
      
      // * Antivirus
      foreach ($a_computerinventory['antivirus'] as $a_antivirus) {
         $a_antivirus['computers_id'] = $computers_id;
         $a_antivirus['_no_history'] = true;
         $pfInventoryComputerAntivirus->add($a_antivirus);
      }
      
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
