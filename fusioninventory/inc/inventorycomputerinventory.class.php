<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

      $errors = '';
      $_SESSION["plugin_fusinvinventory_entity"] = 0;

      $this->sendCriteria($p_DEVICEID, $a_CONTENT, $arrayinventory);
      
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

      // * Hacks

         // Hack to put OS in software
         if (isset($arrayinventory['CONTENT']['HARDWARE']['OSNAME'])) {
            $inputos = array();
            if (isset($arrayinventory['CONTENT']['HARDWARE']['OSCOMMENTS'])) {
               $inputos['COMMENTS'] = $arrayinventory['CONTENT']['HARDWARE']['OSCOMMENTS'];
            }
            $inputos['NAME']     = $arrayinventory['CONTENT']['HARDWARE']['OSNAME'];
            if (isset($arrayinventory['CONTENT']['HARDWARE']['OSVERSION'])) {
               $inputos['VERSION']  = $arrayinventory['CONTENT']['HARDWARE']['OSVERSION'];
            }
            if (isset($arrayinventory['CONTENT']['SOFTWARES']['VERSION'])) {
               $temparray = $arrayinventory['CONTENT']['SOFTWARES'];
               $arrayinventory['CONTENT']['SOFTWARES'] = array();
               $arrayinventory['CONTENT']['SOFTWARES'][] = $temparray;
            }
            $arrayinventory['CONTENT']['SOFTWARES'][] = $inputos;
         }
         
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

      $serialized = gzcompress(serialize($a_computerinventory));
      $a_computerinventory['fusioninventorycomputer']['serialized_inventory'] = 
               Toolbox::addslashes_deep($serialized);

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

         //if ($pfConfig->getValue('transfers_id_auto') == '0') {
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
               //$_SESSION['plugin_fusioninventory_entityrestrict'] = $dataEntity['entities_id'];
               $_SESSION["plugin_fusinvinventory_entity"] = $dataEntity['entities_id'];
               //$input['entities_id'] = $dataEntity['entities_id'];
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
         if ($pfConfig->getValue('transfers_id_auto') != '0') {
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
      global $DB;
if ($items_id > 0) {
   //exit;
}
      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Rule passed : ".$items_id.", ".$itemtype."\n"
      );
      $a_computerinventory = $this->arrayinventory;

      if ($itemtype == 'Computer') {
         $pfInventoryComputerLib      = new PluginFusioninventoryInventoryComputerLib();
         $pfFormatconvert             = new PluginFusioninventoryFormatconvert();
         
         $computer   = new Computer();
         if ($items_id == '0') {
            $_SESSION['glpiactiveentities'] = array($_SESSION["plugin_fusinvinventory_entity"]);
            $_SESSION['glpiactiveentities_string'] = $_SESSION["plugin_fusinvinventory_entity"];
            $_SESSION['glpiactive_entity'] = $_SESSION["plugin_fusinvinventory_entity"];
         } else {
            $pfConfig   = new PluginFusioninventoryConfig();
            $computer->getFromDB($items_id);
            if ($pfConfig->getValue('transfers_id_auto') == 0) {
               $_SESSION["plugin_fusinvinventory_entity"] = $computer->fields['entities_id'];
            }
            $_SESSION['glpiactiveentities'] = array($_SESSION["plugin_fusinvinventory_entity"]);
            $_SESSION['glpiactiveentities_string'] = $_SESSION["plugin_fusinvinventory_entity"];
            $_SESSION['glpiactive_entity'] = $_SESSION["plugin_fusinvinventory_entity"];
         }
         
         if (isset($_SESSION['plugin_fusioninventory_entityrestrict'])) {
            $_SESSION["plugin_fusinvinventory_entity"] = $_SESSION['plugin_fusioninventory_entityrestrict'];
         }
         if (!isset($_SESSION["plugin_fusinvinventory_entity"])
                 OR $_SESSION["plugin_fusinvinventory_entity"] == NOT_AVAILABLE
                 OR $_SESSION["plugin_fusinvinventory_entity"] == '-1') {
            $_SESSION["plugin_fusinvinventory_entity"] = 0;
         }
         $no_history = false;
         // * New
         if ($items_id == '0') {
            $input = array();
            $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
            $items_id = $computer->add($input);
            $no_history = true;
         }

         $ret = $DB->query("SELECT IS_USED_LOCK('inventory".$items_id."')");
         if (!is_null($DB->result($ret, 0, 0))) {
            $communication = new PluginFusioninventoryCommunication();
            $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
      <REPLY>
      <ERROR>ERROR: SAME COMPUTER IS CURRENTLY UPDATED</ERROR>
      </REPLY>");
            $communication->sendMessage($_SESSION['plugin_fusioninventory_compressmode']);
            exit;            
         }
         
         $a_computerinventory = $pfFormatconvert->computerSoftwareTransformation($a_computerinventory, $_SESSION["plugin_fusinvinventory_entity"]);
         $a_computerinventory = $pfFormatconvert->replaceids($a_computerinventory);

         
$start = microtime(true);
         $ret = $DB->query("SELECT GET_LOCK('inventory".$items_id."', 300)");
         if ($DB->result($ret, 0, 0) == 1) {

            $pfInventoryComputerLib->updateComputer($a_computerinventory, $items_id, $no_history);
            
            $DB->request("SELECT RELEASE_LOCK('inventory".$items_id."')");
Toolbox::logInFile("exetime", (microtime(true) - $start)." (".$items_id.")\n");
            $pfInventoryComputerLib->addLog();

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
               $pfRulematchedlog->add($inputrulelog, array(), false);
               $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
               unset($_SESSION['plugin_fusioninventory_rules_id']);
            }            
/*         } else {
//            $communication = new PluginFusioninventoryCommunication();
//            $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
//      <REPLY>
//      <ERROR>TIMEOUT: SERVER OVERLOADED</ERROR>
//      </REPLY>");
//            $communication->sendMessage($_SESSION['plugin_fusioninventory_compressmode']);
//            exit;
//         }
 */
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
      $state = $config->getValue("states_id_default");
      if ($state) {
         if (!$check_management || ($check_management && !$management_value)) {
            $input['states_id'] = $state;
         }
      }
      return $input;
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
