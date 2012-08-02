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
   @author    Vincent Mazzoni
   @co-author David Durieux
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

require_once GLPI_ROOT.'/plugins/fusinvsnmp/inc/communicationsnmp.class.php';

class PluginFusinvsnmpCommunicationNetDiscovery extends PluginFusinvsnmpCommunicationSNMP {


   /**
    * Import data
    *
    * @param $p_DEVICEID XML code to import
    * @param $p_CONTENT XML code to import
    * @param $p_xml value XML code to import
    *
    * @return "" (import ok) / error string (import ko)
    * 
    **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfAgent = new PluginFusioninventoryAgent();
      $pfAgentconfig = new PluginFusinvsnmpAgentconfig();


      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationNetDiscovery->import().'
      );

      $errors = '';

      $a_agent = $pfAgent->InfosByKey($p_DEVICEID);
      if (isset($p_CONTENT->PROCESSNUMBER)) {
         $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $p_CONTENT->PROCESSNUMBER;
         if ($pfTaskjobstate->getFromDB($p_CONTENT->PROCESSNUMBER)) {
            if ($pfTaskjobstate->fields['state'] != "3") {
               $pfTaskjobstate->changeStatus($p_CONTENT->PROCESSNUMBER, 2);
               if ((!isset($p_CONTENT->AGENT->START)) AND (!isset($p_CONTENT->AGENT->END))) {
                  $nb_devices = 0;
                  $segs=$p_CONTENT->xpath('//DEVICE');
                  $nb_devices = count($segs);

                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = $p_CONTENT->PROCESSNUMBER;
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $a_agent['id'];
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = $nb_devices.' ==fusinvsnmp::2==';
                  $this->addtaskjoblog();
               }
            }
         }
      }

      if ($pfTaskjobstate->getFromDB($p_CONTENT->PROCESSNUMBER)) {
         if ($pfTaskjobstate->fields['state'] != "3") {
            $pfImportExport = new PluginFusinvsnmpImportExport();
            $errors.=$pfImportExport->import_netdiscovery($p_CONTENT, $p_DEVICEID);
            if (isset($p_CONTENT->AGENT->END)) {
               if ((isset($p_CONTENT->DICO)) AND ($p_CONTENT->DICO == "REQUEST")) {
                  $pfAgent->getFromDB($pfTaskjobstate->fields["plugin_fusioninventory_agents_id"]);
                  $pfAgentconfig->loadAgentconfig($pfAgent->fields['id']);
                  $input = array();
                  $input['id'] = $pfAgentconfig->fields['id'];
                  $input["senddico"] = "1";
                  $pfAgentconfig->update($input);

                  $pfTaskjobstate->changeStatusFinish($p_CONTENT->PROCESSNUMBER,
                                                                          $a_agent['id'],
                                                                          'PluginFusioninventoryAgent',
                                                                          '1',
                                                                          '==fusinvsnmp::3==');
               } else {

                  $pfTaskjobstate->changeStatusFinish($p_CONTENT->PROCESSNUMBER,
                                                                       $a_agent['id'],
                                                                       'PluginFusioninventoryAgent');
               }
            }
         }
      }
      return $errors;
   }

   

   /**
    * Prepare data and send them to rule engine
    * 
    * @param type $p_xml simpleXML object
    */
   function sendCriteria($p_xml) {
      
      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationNetDiscovery->sendCriteria().'
      );
      
      if ((isset($p_xml->MAC)) AND ($p_xml->MAC == "00:00:00:00:00:00")) {
         unset($p_xml->MAC);
      }

      $_SESSION['SOURCE_XMLDEVICE'] = $p_xml->asXML();

      $input = array();

      // Global criterias

      if ((isset($p_xml->SERIAL)) AND (!empty($p_xml->SERIAL))) {
         $input['serial'] = (string)$p_xml->SERIAL;
      }
      if ((isset($p_xml->MANUFACTURER)) AND (!empty($p_xml->MANUFACTURER))) {
         $input['manufacturer_id'] = Dropdown::importExternal(
            'Manufacturer',  (string)$p_xml->MANUFACTURER
         );
      }
      if ((isset($p_xml->MAC)) AND (!empty($p_xml->MAC))) {
         $input['mac'][] = (string)$p_xml->MAC;
      }
      if ((isset($p_xml->IP)) AND (!empty($p_xml->IP))) {
         $input['ip'][] = (string)$p_xml->IP;
      }
      if ((isset($p_xml->MODELSNMP)) AND (!empty($p_xml->MODELSNMP))) {
         $input['model'] = (string)$p_xml->MODELSNMP;
      }
      if ((isset($p_xml->NETBIOSNAME)) AND (!empty($p_xml->NETBIOSNAME))) {
         $input['name'] = (string)$p_xml->NETBIOSNAME;
      } else if ((isset($p_xml->SNMPHOSTNAME)) AND (!empty($p_xml->SNMPHOSTNAME))) {
         $input['name'] = (string)$p_xml->SNMPHOSTNAME;
      } else if ((isset($p_xml->DNSHOSTNAME)) AND (!empty($p_xml->DNSHOSTNAME))) {
         $input['name'] = (string)$p_xml->DNSHOSTNAME;
      }
      $input['entities_id'] = (string)$p_xml->ENTITY;
      switch ($p_xml->TYPE) {

         case '1':
            $input['itemtype'] = "Computer";
            // Computer

             break;

         case '2':
            $input['itemtype'] = "NetworkEquipment";
             break;

         case '3':
            $input['itemtype'] = "Printer";
             break;

      }

      $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize($input);
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "PluginFusinvsnmpCommunicationNetDiscovery";
      $rule = new PluginFusioninventoryRuleImportEquipmentCollection();
      $data = array ();
      $data = $rule->processAllRules($input, array());
      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusioninventory-rules",
         print_r($data, true)
      );

      if (isset($data['action'])
              AND ($data['action'] == PluginFusioninventoryRuleImportEquipment::LINK_RESULT_DENIED)) {
         
         $a_text = '';
         foreach ($input as $key=>$data) {
            if (is_array($data)) {
               $a_text[] = "[".$key."]:".implode(",", $data);
            } else {
               $a_text[] = "[".$key."]:".$data;
            }
         }
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '==fusioninventory::3== '.implode(",", $a_text);
         $this->addtaskjoblog();
         
         $pFusioninventoryIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
         $inputdb = array();
         $inputdb['name'] = $input['name'];
         $inputdb['date'] = date("Y-m-d H:i:s");
         $inputdb['itemtype'] = $input['itemtype'];
         if (isset($input['serial'])) {
            $input['serialnumber'] = $input['serial'];
         }
         if (isset($input['ip'])) {
            $inputdb['ip'] = exportArrayToDB($input['ip']);
         }
         if (isset($input['mac'])) {
            $inputdb['mac'] = exportArrayToDB($input['mac']);
         }
         $inputdb['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
         $inputdb['method'] = 'netdiscovery';
         $pFusioninventoryIgnoredimportdevice->add($inputdb);
         unset($_SESSION['plugin_fusioninventory_rules_id']);
      }
      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = "'".$input['entities_id']."'";
         }
         if (isset($input['itemtype'])
              AND isset($data['action'])
              AND ($data['action'] == PluginFusioninventoryRuleImportEquipment::LINK_RESULT_CREATE)) {

            $this->rulepassed(0, $input['itemtype'],$input['entities_id']);
         } else if (isset($input['itemtype'])
                AND !isset($data['action'])) {
            $this->rulepassed(0, $input['itemtype'],$input['entities_id']);           
         } else {
            $this->rulepassed(0, "PluginFusioninventoryUnknownDevice",$input['entities_id']);
         }
      }
   }



   /**
    * After rule engine passed, update task (log) and create item if required
    * 
    * @param type $items_id
    * @param type $itemtype
    * @param type $entities_id 
    */
   function rulepassed($items_id, $itemtype, $entities_id=0) {

      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Rule passed : ".$items_id.", ".$itemtype."\n"
      );
      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationNetDiscovery->rulepassed().'
      );
      
      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = "'".$entities_id."'";
      }

      $item = new $itemtype();
      if ($items_id == "0") {
         $input = array();
         $input['date_mod'] = date("Y-m-d H:i:s");
         $input['entities_id'] = $entities_id;
         $items_id = $item->add($input);
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
            $inputrulelog['method'] = 'netdiscovery';
            $pfRulematchedlog->add($inputrulelog);
            $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
            unset($_SESSION['plugin_fusioninventory_rules_id']);
         }
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = "'".$entities_id."'";
         } 
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
               '[==fusinvsnmp::7==] ==fusinvsnmp::4== '.$item->getTypeName().' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
      } else {
         
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
               '[==fusinvsnmp::7==] ==fusinvsnmp::5== '.$item->getTypeName().' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
      }
      $item->getFromDB($items_id);
      $this->importDevice($item);
   }



   /**
    * Import discovered device (add / update data in GLPI DB)
    * 
    * @param object $item 
    */
   function importDevice($item) {
      
      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationNetDiscovery->importDevice().'
      );
      
      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);
      $input = array();
      $input['id'] = $item->getID();

      $a_lockable = PluginFusioninventoryLock::getLockFields(getTableForItemType($item->getType()), $item->getID());
      
      if (!in_array('name', $a_lockable)) {
         if (isset($xml->NETBIOSNAME) AND !empty($xml->NETBIOSNAME)) {
            $input['name'] = (string)$xml->NETBIOSNAME;
         } else if (isset($xml->SNMPHOSTNAME) AND !empty($xml->SNMPHOSTNAME)) {
            $input['name'] = (string)$xml->SNMPHOSTNAME;
         } else if (isset($xml->DNSHOSTNAME) AND !empty($xml->DNSHOSTNAME)) {
            $input['name'] = (string)$xml->DNSHOSTNAME;
         }
      }
      if (!in_array('serial', $a_lockable)) {
         if (trim($xml->SERIAL) != '') {
            $input['serial'] = trim($xml->SERIAL);
         }
      }
      
      if (isset($xml->ENTITY) AND !empty($xml->ENTITY)) {
         $input['entities_id'] = $xml->ENTITY;
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = "'".$xml->ENTITY."'";
         }
      }      
      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = "'".$item->fields['entities_id']."'";
      }      
      
      switch ($item->getType()) {
         
         case 'Computer':
            // If computer is update with Agent, don't update it
            if (Dropdown::getDropdownName("glpi_autoupdatesystems", $item->fields['autoupdatesystems_id']) != 'FusionInventory') {
               if (isset($xml->WORKGROUP)) {
                  $domain = new Domain();
                  if (!in_array('domains_id', $a_lockable)) {
                     $input['domains_id'] = $domain->import(array('name'=>(string)$xml->WORKGROUP));
                  }
               }
               $item->update($input);
               //Manage IP and Mac address
               $NetworkPort = new NetworkPort();
               $a_computerports = array();
               $a_computerports = $NetworkPort->find("`itemtype`='Computer'
                     AND `items_id`='".$item->getID()."'");
               $update = 0;
               foreach ($a_computerports as $a_computerport) {
                  if (isset($xml->MAC) AND !empty($xml->MAC)) {
                     $xml->MAC = strtolower((string)$xml->MAC);
                     if ($a_computerport['mac'] == (string)$xml->MAC) {
                        $input = array();
                        $input['id'] = $a_computerport['id'];
                        $input['mac'] = (string)$xml->MAC;
                        if (isset($xml->IP)) {
                           $input['ip'] = (string)$xml->IP;
                        }
                        $NetworkPort->update($input);
                        unset($a_computerports[$a_computerport['id']]);
                        $update = 1;
                        break;
                     }
                  }
               }
               foreach ($a_computerports as $a_computerport) {
                  if ($a_computerport['ip'] != '127.0.0.1') {
                     $NetworkPort->delete($a_computerport, 1);
                  }
               }
               if ($update == '0') {
                  $input = array();
                  if (isset($xml->MAC) AND !empty($xml->MAC)) {
                     $input['mac'] = (string)$xml->MAC;
                  }
                  if (isset($xml->IP)) {
                     $input['ip'] = (string)$xml->IP;
                  }
                  $input['items_id'] = $item->getID();
                  $input['itemtype'] = 'Computer';
                  $input['entities_id'] = $item->fields['entities_id'];
                  $NetworkPort->add($input);
               }
            }
            break;

         case 'PluginFusioninventoryUnknownDevice':
            // Write XML file
            if (isset($_SESSION['SOURCE_XMLDEVICE'])) {
               PluginFusioninventoryUnknownDevice::writeXML($item->getID(), $_SESSION['SOURCE_XMLDEVICE']);
            }
             

            if (!in_array('contact', $a_lockable)) {
               $input['contact'] = (string)$xml->USERSESSION;
            }
            if (!in_array('domain', $a_lockable)) {
               if (!empty($xml->WORKGROUP)) {
               $input['domain'] = Dropdown::importExternal("Domain",
                                       (string)$xml->WORKGROUP,(string)$xml->ENTITY);
               }
            }
            if (!empty($xml->TYPE)) {
               switch ((string)$xml->TYPE) {

                  case '1':
                     $input['item_type'] = 'Computer';
                     break;

                  case '2':
                     $input['item_type'] = 'NetworkEquipment';
                     break;

                  case '3':
                     $input['item_type'] = 'Printer';
                     break;
                  
               }
            }
            $input['plugin_fusioninventory_agents_id'] = $_SESSION['glpi_plugin_fusioninventory_agentid'];
            $item->update($input);

            //Manage IP and Mac address
            $NetworkPort = new NetworkPort();
            $a_unknownPorts = array();
            $a_unknownPorts = $NetworkPort->find("`itemtype`='PluginFusioninventoryUnknownDevice'
                  AND `items_id`='".$item->getID()."'");
            $update = 0;
            foreach ($a_unknownPorts as $a_unknownPort) {
               if (isset($xml->MAC) AND !empty($xml->MAC)) {
                  $xml->MAC = strtolower((string)$xml->MAC);
                  if ($a_unknownPort['mac'] == (string)$xml->MAC) {
                     $a_unknownPort['mac'] = (string)$xml->MAC;
                     if (isset($xml->IP)) {
                        $a_unknownPort['ip'] = (string)$xml->IP;
                     }
                     $NetworkPort->update($a_unknownPort);
                     unset($a_unknownPorts[$a_unknownPort['id']]);
                     $update = 1;
                     break;
                  }
               } else if (isset($xml->IP) AND !empty($xml->IP)) {
                  if ($a_unknownPort['ip'] == (string)$xml->IP) {
                     unset($a_unknownPorts[$a_unknownPort['id']]);
                     $update = 1;
                     break;
                  }
               }
            }
            foreach ($a_unknownPorts as $a_unknownPort) {
               $NetworkPort->delete($a_unknownPort);
            }
            if ($update == '0') {
               $input = array();
               if (isset($xml->MAC) AND !empty($xml->MAC)) {
                  $input['mac'] = (string)$xml->MAC;
               }
               if (isset($xml->IP)) {
                  $input['ip'] = (string)$xml->IP;
               }
               $input['items_id'] = $item->getID();
               $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
               $input['entities_id'] = $item->fields['entities_id'];
               $NetworkPort->add($input);
            }

            // Add informations for SNMP
            $pfUnknownDevice = new PluginFusinvsnmpUnknownDevice();
            $a_devices = $pfUnknownDevice->find("`plugin_fusioninventory_unknowndevices_id`='".$item->getID()."'");
            if (count($a_devices) > 0) {
               foreach ($a_devices as $data) {
                  $pfUnknownDevice->getFromDB($data['id']);
               }
            } else {
               $input = array();
               $input['plugin_fusioninventory_unknowndevices_id'] = $item->getID();
               $device_id = $pfUnknownDevice->add($input);
               $pfUnknownDevice->getFromDB($device_id);
            }
            $input = array();
            $input['id'] = $pfUnknownDevice->fields['id'];
            if (isset($xml->DESCRIPTION) AND !empty($xml->DESCRIPTION)) {
               $input['sysdescr'] = $xml->DESCRIPTION;
            }
            // <MODELSNMP>Printer0093</MODELSNMP>
            if (isset($xml->MODELSNMP) AND !empty($xml->MODELSNMP)) {
               $pfModel = new PluginFusinvsnmpModel();
               $model_id = $pfModel->getModelByKey($xml->MODELSNMP);
               if (($model_id == '0') AND (isset($xml->DESCRIPTION)) AND (!empty($xml->DESCRIPTION))) {
                  $model_id = $pfModel->getModelBySysdescr($xml->DESCRIPTION);
               }
               if ($model_id != '0') {
                  $input['plugin_fusinvsnmp_models_id'] = $model_id;
               }
            }

            if (isset($xml->AUTHSNMP) AND !empty($xml->AUTHSNMP)) {
               $input['plugin_fusinvsnmp_configsecurities_id'] = $xml->AUTHSNMP;
            }
            $pfUnknownDevice->update($input);
            break;
         
         case 'NetworkEquipment':
            if (isset($xml->MAC) AND !empty($xml->MAC)) {
               if (!in_array('mac', $a_lockable)) {
                  $input['mac'] = $xml->MAC;
               }
            }
            if (isset($xml->IP)) {
               if (!in_array('ip', $a_lockable)) {
                  $input['ip'] = $xml->IP;
               }
            }

            $item->update($input);

            // Update SNMP informations
            $pfNetworkEquipment = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
            $a_snmpnetworkequipments = $pfNetworkEquipment->find("`networkequipments_id`='".$item->getID()."'");
            if (count($a_snmpnetworkequipments) > 0) {
               $a_snmpnetworkequipment = current($a_snmpnetworkequipments);
               $pfNetworkEquipment->load($a_snmpnetworkequipment['id']);
               $pfNetworkEquipment->setValue('id', $a_snmpnetworkequipment['id']);
            } else {
               $pfNetworkEquipment->load();
               $pfNetworkEquipment->setValue('networkequipments_id', $item->getID());
            }
            // Write XML file
            if (isset($_SESSION['SOURCE_XMLDEVICE'])
                    AND is_null($pfNetworkEquipment->getValue('last_fusioninventory_update'))) {
               PluginFusioninventoryUnknownDevice::writeXML($input['id'], 
                                          $_SESSION['SOURCE_XMLDEVICE'],
                                          "fusinvsnmp",
                                          "NetworkEquipment");
            }
            $pfNetworkEquipment->setValue('sysdescr', $xml->DESCRIPTION);
            $pfModel = new PluginFusinvsnmpModel();
            if (isset($xml->MODELSNMP) AND !empty($xml->MODELSNMP)) {
               $model_id = $pfModel->getModelByKey($xml->MODELSNMP);
               if ($model_id != '0') {
                  $pfNetworkEquipment->setValue('plugin_fusinvsnmp_models_id', $model_id);
               }
            }
            $pfNetworkEquipment->setValue('plugin_fusinvsnmp_configsecurities_id', $xml->AUTHSNMP);
            $pfNetworkEquipment->updateDB();
            break;

         case 'Printer':
            $input['have_ethernet'] = '1';
            $item->update($input);

            //Manage IP and Mac address
            $NetworkPort = new NetworkPort();
            $a_printerports = array();
            $a_printerports = $NetworkPort->find("`itemtype`='Printer'
                  AND `items_id`='".$item->getID()."'");
            $update = 0;
            foreach ($a_printerports as $a_printerport) {
               if (isset($xml->MAC) AND !empty($xml->MAC)) {
                  $xml->MAC = strtolower((string)$xml->MAC);
                  if ($a_printerport['mac'] == (string)$xml->MAC) {
                     $a_printerport['mac'] = (string)$xml->MAC;
                     if (isset($xml->IP)) {
                        $a_printerport['ip'] = (string)$xml->IP;
                     }
                     $NetworkPort->update($a_printerport);
                     unset($a_printerports[$a_printerport['id']]);
                     $update = 1;
                     break;
                  }
               }
            }
            foreach ($a_printerports as $a_printerport) {
               if ($a_printerport['ip'] != '127.0.0.1') {
                  $NetworkPort->delete($a_printerport, 1);
               }
            }
            if ($update == '0') {
               $input = array();
               if (isset($xml->MAC) AND !empty($xml->MAC)) {
                  $input['mac'] = (string)$xml->MAC;
               }
               if (isset($xml->IP)) {
                  $input['ip'] = (string)$xml->IP;
               }
               $input['items_id'] = $item->getID();
               $input['itemtype'] = 'Printer';
               $input['entities_id'] = $item->fields['entities_id'];
               $NetworkPort->add($input);
            }
            
            // Update SNMP informations
            $pfPrinter = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_printers");
            $a_snmpprinters = $pfPrinter->find("`printers_id`='".$item->getID()."'");
            if (count($a_snmpprinters) > 0) {
               $a_snmpprinter = current($a_snmpprinters);
               $pfPrinter->load($a_snmpprinter['id']);
               $pfPrinter->setValue('id', $a_snmpprinter['id']);
            } else {
               $pfPrinter->load();
               $pfPrinter->setValue('printers_id', $item->getID());
            }
            // Write XML file
            if (isset($_SESSION['SOURCE_XMLDEVICE'])
                    AND is_null($pfPrinter->getValue('last_fusioninventory_update'))) {
               PluginFusioninventoryUnknownDevice::writeXML($item->getID(), 
                                          $_SESSION['SOURCE_XMLDEVICE'],
                                          "fusinvsnmp",
                                          "Printer");
            }
            $pfPrinter->setValue('sysdescr', $xml->DESCRIPTION);
            if (isset($xml->MODELSNMP) AND !empty($xml->MODELSNMP)) {
               $pfModel = new PluginFusinvsnmpModel();
               $model_id = $pfModel->getModelByKey($xml->MODELSNMP);
               if ($model_id != '0') {
                  $pfPrinter->setValue('plugin_fusinvsnmp_models_id', $model_id);
               }
            }
            $pfPrinter->setValue('plugin_fusinvsnmp_configsecurities_id', $xml->AUTHSNMP);
            $pfPrinter->updateDB();
            break;
            
      }
   }


   
   /**
    * Used to add log in the task
    */
   function addtaskjoblog() {

      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfTaskjoblog->addTaskjoblog(
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']);
   }
   
   

   static function getMethod() {
      return 'netdiscovery';
   }
   
}

?>