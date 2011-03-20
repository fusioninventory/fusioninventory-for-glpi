<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: Vincent Mazzoni
   Co-authors of file: David Durieux
   Purpose of file:
   ----------------------------------------------------------------------
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

      global $LANG;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pta  = new PluginFusioninventoryAgent();
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $PluginFusinvsnmpAgentconfig = new PluginFusinvsnmpAgentconfig();


      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationNetDiscovery->import().');

      $errors = '';

      $a_agent = $pta->InfosByKey($p_DEVICEID);
      if (isset($p_CONTENT->PROCESSNUMBER)) {
         $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $p_CONTENT->PROCESSNUMBER;
         $PluginFusioninventoryTaskjobstatus->getFromDB($p_CONTENT->PROCESSNUMBER);
         if ($PluginFusioninventoryTaskjobstatus->fields['state'] != "3") {
            $PluginFusioninventoryTaskjobstatus->changeStatus($p_CONTENT->PROCESSNUMBER, 2);
            if ((!isset($p_CONTENT->AGENT->START)) AND (!isset($p_CONTENT->AGENT->END))) {
               $nb_devices = 0;
               foreach($p_CONTENT->DEVICE as $child) {
                  $nb_devices++;
               }
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = $p_CONTENT->PROCESSNUMBER;
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $a_agent['id'];
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = $nb_devices.' ==fusinvsnmp::2==';
               $this->addtaskjoblog();
            }
         }
      }

      $PluginFusioninventoryTaskjobstatus->getFromDB($p_CONTENT->PROCESSNUMBER);
      if ($PluginFusioninventoryTaskjobstatus->fields['state'] != "3") {
         $pti = new PluginFusinvsnmpImportExport();
         $errors.=$pti->import_netdiscovery($p_CONTENT, $p_DEVICEID);
         if (isset($p_CONTENT->AGENT->END)) {
            if ((isset($p_CONTENT->DICO)) AND ($p_CONTENT->DICO == "REQUEST")) {
               $PluginFusioninventoryAgent->getFromDB($PluginFusioninventoryTaskjobstatus->fields["plugin_fusioninventory_agents_id"]);
               $PluginFusinvsnmpAgentconfig->loadAgentconfig($PluginFusioninventoryAgent->fields['id']);
               $PluginFusinvsnmpAgentconfig->fields["senddico"] = "1";
               $PluginFusinvsnmpAgentconfig->update($PluginFusinvsnmpAgentconfig->fields);

               $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = $p_CONTENT->PROCESSNUMBER;
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $a_agent['id'];
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '==fusinvsnmp::3==';
               $this->addtaskjoblog();
            }

            $PluginFusioninventoryTaskjobstatus->changeStatusFinish($p_CONTENT->PROCESSNUMBER,
                                                                    $a_agent['id'],
                                                                    'PluginFusioninventoryAgent');
         
         }
      }
      return $errors;
   }


   function sendCriteria($p_xml) {
      if ((isset($p_xml->MAC)) AND ($p_xml->MAC == "00:00:00:00:00:00")) {
         unset($p_xml->MAC);
      }

      $_SESSION['SOURCE_XMLDEVICE'] = $p_xml->asXML();

      $input = array();

      // Global criterias

      if ((isset($p_xml->SERIAL)) AND (!empty($p_xml->SERIAL))) {
         $input['serial'] = (string)$p_xml->SERIAL;
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
      if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
         logInFile("pluginFusioninventory-rules", print_r($data, true));
      }
      if ((isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1'))
            OR (isset($data['action']) AND ($data['action'] == '2'))) {
         if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
            logInFile("pluginFusioninventory-rules", "norulematch = 1");
         }
         if (isset($input['itemtype'])) {
            $this->rulepassed(0, $input['itemtype']);
         } else {
            $this->rulepassed(0, "PluginFusioninventoryUnknownDevice");
         }
      }
   }



   function rulepassed($items_id, $itemtype) {
      global $DB;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->rulepassed().');

      $class = new $itemtype();
      if ($items_id == "0") {
         $input = array();
         $input['date_mod'] = date("Y-m-d H:i:s");
         $items_id = $class->add($input);
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
               '[detail] Add '.$class->getTypeName().' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
      } else {
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
               '[detail] Update '.$class->getTypeName().' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
      }
      $this->importDevice($itemtype, $items_id);
   }


//   function checkCriteria($a_criteria) {
//      global $DB;
//
//      $PluginFusinvsnmpCommunicationSNMP = new PluginFusinvsnmpCommunicationSNMP();
//
//      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);
//
//      switch ($xml->TYPE) {
//
//         case '0':
//            // Don't know what device type it is
//
//            break;
//
//         case '1':
//            // Computer
//            $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'Computer');
//            $result = $a_return[0];
//            $input = $a_return[1];
//
//            break;
//
//         case '2':
//            // NetworkEquipment
//            $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'NetworkEquipment');
//            $result = $a_return[0];
//            $input = $a_return[1];
//            if ($DB->numrows($result)) {
//               $this->importDevice('NetworkEquipment', $DB->result($result,0,'id'));
//            } else {
//               // unknowndevice
//               $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'PluginFusioninventoryUnknownDevice');
//               $result = $a_return[0];
//               $input = $a_return[1];
//               if (isset($result) AND ($DB->numrows($result) > 0)) {
//                  $this->importDevice('PluginFusioninventoryUnknownDevice', $DB->result($result,0,'id'));
//               } else {
//                  $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
//                  $input['plugin_fusioninventory_agents_id'] = $_SESSION['glpi_plugin_fusioninventory_agentid'];
//                  $id = $PluginFusioninventoryUnknownDevice->add($input);
//                  $this->importDevice('PluginFusioninventoryUnknownDevice', $id);
//               }
//            }
//            break;
//
//         case '3':
//            // Printer
//            $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'Printer');
//            $result = $a_return[0];
//            $input = $a_return[1];
//            if (isset($result) AND ($DB->numrows($result) > 0)) {
//               $this->importDevice('Printer', $DB->result($result,0,'id'));
//            } else {
//               // unknowndevice
//               $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'PluginFusioninventoryUnknownDevice');
//               $result = $a_return[0];
//               $input = $a_return[1];
//               if (isset($result) AND ($DB->numrows($result) > 0)) {
//                  $this->importDevice('PluginFusioninventoryUnknownDevice', $DB->result($result,0,'id'));
//               } else {
//                  $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
//                  $input['plugin_fusioninventory_agents_id'] = $_SESSION['glpi_plugin_fusioninventory_agentid'];
//                  $id = $PluginFusioninventoryUnknownDevice->add($input);
//                  $this->importDevice('PluginFusioninventoryUnknownDevice', $id);
//               }
//            }
//            break;
//
//      }
//
//
//   }


   function importDevice($itemtype, $items_id) {
      
      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);
      $class = new $itemtype();
      $class->getFromDB($items_id);

      $a_lockable = PluginFusioninventoryLock::getLockFields($itemtype, $items_id);

      if (!in_array('name', $a_lockable)) {
         if (isset($xml->NETBIOSNAME) AND !empty($xml->NETBIOSNAME)) {
            $class->fields['name'] = (string)$xml->NETBIOSNAME;
         } else if (isset($xml->SNMPHOSTNAME) AND !empty($xml->SNMPHOSTNAME)) {
            $class->fields['name'] = (string)$xml->SNMPHOSTNAME;
         } else if (isset($xml->DNSHOSTNAME) AND !empty($xml->DNSHOSTNAME)) {
            $class->fields['name'] = (string)$xml->DNSHOSTNAME;
         }
      }
      //if (!in_array('serial', $a_lockable))
         $class->fields['serial'] = trim($xml->SERIAL);
      
      if (isset($xml->ENTITY) AND !empty($xml->ENTITY)) {
         $class->fields['entities_id'] = $xml->ENTITY;
      }
      
      switch ($itemtype) {
         
         case 'Computer':

            break;

         case 'PluginFusioninventoryUnknownDevice':
            if (!in_array('contact', $a_lockable))
               $class->fields['contact'] = (string)$xml->USERSESSION;
            if (!in_array('domain', $a_lockable)) {
               if (!empty($xml->WORKGROUP)) {
               $class->fields['domain'] = Dropdown::importExternal("Domain",
                                       (string)$xml->WORKGROUP,(string) $xml->ENTITY);
               }
            }
            if (!empty($xml->TYPE)) {
               switch ((string)$xml->TYPE) {

                  case '1':
                     $class->fields['itemtype'] = 'Computer';
                     break;

                  case '2':
                     $class->fields['itemtype'] = 'NetworkEquipment';
                     break;

                  case '3':
                     $class->fields['itemtype'] = 'Printer';
                     break;
                  
               }
            }
            $class->fields['plugin_fusioninventory_agents_id'] = $_SESSION['glpi_plugin_fusioninventory_agentid'];
            $class->update($class->fields);

            //Manage IP and Mac address
            $NetworkPort = new NetworkPort();
            $a_unknownPorts = array();
            $a_unknownPorts = $NetworkPort->find("`itemtype`='PluginFusioninventoryUnknownDevice'
                  AND `items_id`='".$class->fields['id']."'");
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
               $input['items_id'] = $class->fields['id'];
               $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
               $input['entities_id'] = $class->fields['entities_id'];
               $NetworkPort->add($input);
            }

            // Add informations for SNMP
            $PluginFusinvsnmpUnknownDevice = new PluginFusinvsnmpUnknownDevice();
            $a_devices = $PluginFusinvsnmpUnknownDevice->find("`plugin_fusioninventory_unknowndevices_id`='".$items_id."'");
            if (count($a_devices) > 0) {
               foreach ($a_devices as $data) {
                  $PluginFusinvsnmpUnknownDevice->getFromDB($data['id']);
               }
            } else {
               $input = array();
               $input['plugin_fusioninventory_unknowndevices_id'] = $items_id;
               $device_id = $PluginFusinvsnmpUnknownDevice->add($input);
               $PluginFusinvsnmpUnknownDevice->getFromDB($device_id);
            }
            if (isset($xml->DESCRIPTION) AND !empty($xml->DESCRIPTION)) {
               $PluginFusinvsnmpUnknownDevice->fields['sysdescr'] = $xml->DESCRIPTION;
            }
            // <MODELSNMP>Printer0093</MODELSNMP>
            if (isset($xml->MODELSNMP) AND !empty($xml->MODELSNMP)) {
               $PluginFusinvsnmpModel = new PluginFusinvsnmpModel();
               $model_id = $PluginFusinvsnmpModel->getModelByKey($xml->MODELSNMP);
               if (($model_id == '0') AND (isset($xml->DESCRIPTION)) AND (!empty($xml->DESCRIPTION))) {
                  $model_id = $PluginFusinvsnmpModel->getModelBySysdescr($xml->DESCRIPTION);
               }
               $PluginFusinvsnmpUnknownDevice->fields['plugin_fusinvsnmp_models_id'] = $model_id;
            }

            if (isset($xml->AUTHSNMP) AND !empty($xml->AUTHSNMP)) {
               $PluginFusinvsnmpUnknownDevice->fields['plugin_fusinvsnmp_configsecurities_id'] = $xml->AUTHSNMP;
            }
            $PluginFusinvsnmpUnknownDevice->update($PluginFusinvsnmpUnknownDevice->fields);
            break;
         
         case 'NetworkEquipment':
            if (isset($xml->MAC) AND !empty($xml->MAC)) {
               $class->fields['mac'] = $xml->MAC;
            }
            if (isset($xml->IP)) {
               $class->fields['ip'] = $xml->IP;
            }

            $class->update($class->fields);

            // Update SNMP informations
            $PluginFusinvsnmpNetworkEquipment = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
            $a_snmpnetworkequipments = $PluginFusinvsnmpNetworkEquipment->find("`networkequipments_id`='".$items_id."'");
            if (count($a_snmpnetworkequipments) > 0) {
               $a_snmpnetworkequipment = current($a_snmpnetworkequipments);
               $PluginFusinvsnmpNetworkEquipment->load($a_snmpnetworkequipment['id']);
               $PluginFusinvsnmpNetworkEquipment->setValue('id', $a_snmpnetworkequipment['id']);
            } else {
               $PluginFusinvsnmpNetworkEquipment->load();
               $PluginFusinvsnmpNetworkEquipment->setValue('networkequipments_id', $items_id);
            }
            $PluginFusinvsnmpNetworkEquipment->setValue('sysdescr', $xml->DESCRIPTION);
            $PluginFusinvsnmpModel = new PluginFusinvsnmpModel();
            $model_id = $PluginFusinvsnmpModel->getModelByKey($xml->MODELSNMP);
            $PluginFusinvsnmpNetworkEquipment->setValue('plugin_fusinvsnmp_models_id', $model_id);
            $PluginFusinvsnmpNetworkEquipment->setValue('plugin_fusinvsnmp_configsecurities_id', $xml->AUTHSNMP);
            $PluginFusinvsnmpNetworkEquipment->updateDB();
            break;

         case 'Printer':

            $class->fields['have_ethernet'] = '1';
            $class->update($class->fields);

            //Manage IP and Mac address
            $NetworkPort = new NetworkPort();
            $a_printerports = array();
            $a_printerports = $NetworkPort->find("`itemtype`='Printer'
                  AND `items_id`='".$class->fields['id']."'");
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
               $input['items_id'] = $class->fields['id'];
               $input['itemtype'] = 'Printer';
               $input['entities_id'] = $class->fields['entities_id'];
               $NetworkPort->add($input);
            }
            
            // Update SNMP informations
            $PluginFusinvsnmpPrinter = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_printers");
            $a_snmpprinters = $PluginFusinvsnmpPrinter->find("`printers_id`='".$items_id."'");
            if (count($a_snmpprinters) > 0) {
               $a_snmpprinter = current($a_snmpprinters);
               $PluginFusinvsnmpPrinter->load($a_snmpprinter['id']);
               $PluginFusinvsnmpPrinter->setValue('id', $a_snmpprinter['id']);
            } else {
               $PluginFusinvsnmpPrinter->load();
               $PluginFusinvsnmpPrinter->setValue('printers_id', $items_id);
            }
            $PluginFusinvsnmpPrinter->setValue('sysdescr', $xml->DESCRIPTION);
            $PluginFusinvsnmpModel = new PluginFusinvsnmpModel();
            $model_id = $PluginFusinvsnmpModel->getModelByKey($xml->MODELSNMP);
            $PluginFusinvsnmpPrinter->setValue('plugin_fusinvsnmp_models_id', $model_id);
            $PluginFusinvsnmpPrinter->setValue('plugin_fusinvsnmp_configsecurities_id', $xml->AUTHSNMP);
            $PluginFusinvsnmpPrinter->updateDB();
            break;
            
      }
   }


   function addtaskjoblog() {

      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $PluginFusioninventoryTaskjoblog->addTaskjoblog(
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']);
   }
}

?>