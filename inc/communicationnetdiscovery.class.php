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
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

require_once GLPI_ROOT.'/plugins/fusinvsnmp/inc/communicationsnmp.class.php';

class PluginFusinvsnmpCommunicationNetDiscovery extends PluginFusinvsnmpCommunicationSNMP {


   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {

      global $LANG;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pta  = new PluginFusioninventoryAgent();


      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationNetDiscovery->import().');
      //$this->setXML($p_CONTENT);
      $errors = '';

      $a_agent = $pta->InfosByKey($p_DEVICEID);
      if (isset($p_CONTENT->PROCESSNUMBER)) {
         $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $p_CONTENT->PROCESSNUMBER;
         $PluginFusioninventoryTaskjobstatus->getFromDB($p_CONTENT->PROCESSNUMBER);
         $PluginFusioninventoryTaskjobstatus->changeStatus($p_CONTENT->PROCESSNUMBER, 2);
         if ((!isset($p_CONTENT->AGENT->START)) AND (!isset($p_CONTENT->AGENT->END))) {
            $nb_devices = 0;
            foreach($p_CONTENT->DEVICE as $child) {
               $nb_devices++;
            }
            $PluginFusioninventoryTaskjoblog->addTaskjoblog($p_CONTENT->PROCESSNUMBER,
                                                   $a_agent['id'],
                                                   'PluginFusioninventoryAgent',
                                                   '6',
                                                   $nb_devices.' devices found');
         }
      }

      $moduleversion = "1.0";
      if (isset($p_CONTENT->MODULEVERSION)) {
         $moduleversion = $p_CONTENT->PROCESSNUMBER;
      }
      $pti = new PluginFusinvsnmpImportExport;
      $errors.=$pti->import_netdiscovery($p_CONTENT, $p_DEVICEID, $moduleversion);
      if (isset($p_CONTENT->AGENT->END)) {
         $PluginFusioninventoryTaskjobstatus->changeStatusFinish($p_CONTENT->PROCESSNUMBER,
                                                   $a_agent['id'],
                                                   'PluginFusioninventoryAgent');
      }
      return $errors;
   }


   function sendCriteria($p_xml) {
      
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
   }



   function rulepassed($items_id, $itemtype) {
      global $DB;
      $PluginFusinvsnmpCommunicationSNMP = new PluginFusinvsnmpCommunicationSNMP();

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->rulepassed().');

      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);

      $datacriteria = unserialize($_SESSION['plugin_fusinvsnmp_datacriteria']);

      $class = new $itemtype();
      if ($items_id == "0") {
         $input = array();
         $input['date_mod'] = date("Y-m-d H:i:s");
         $items_id = $class->add($input);
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
         case 'Printer':


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
               }
            }
            foreach ($a_unknownPorts as $a_unknownPort) {
               $NetworkPort->delete($a_unknownPort, 1);
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


//
//            $class->update($class->fields);
//            if (isset($class->fields['ip'])) {
//               if ($class->fields['ip'] && !in_array('ip', $a_lockable)) {
//                  $class->fields['ip'] = $xml->IP;
//               }
//            }
//            if (isset($class->fields['mac'])) {
//               if ($class->fields['mac'] && !in_array('mac', $a_lockable)) {
//                  $class->fields['mac'] = $xml->MAC;
//               }
//            }

            
            break;
         
         case 'NetworkEquipment':
            if (isset($xml->MAC) AND !empty($xml->MAC)) {
               $class->fields['mac'] = $xml->MAC;
            }
            if (isset($xml->IP)) {
               $class->fields['ip'] = $xml->IP;
            }

            $class->update($class->fields);
            break;
      }




//      if ($class->fields['name'] && !in_array('name', $a_lockable)) {
//         if (!empty($xml->NETBIOSNAME)) {
//            $class->fields['name'] = $xml->NETBIOSNAME;
//         } else if (!empty($xml->SNMPHOSTNAME)) {
//            $class->fields['name'] = $xml->SNMPHOSTNAME;
//         } else if (!empty($xml->DNSHOSTNAME)) {
//            $class->fields['name'] = $xml->DNSHOSTNAME;
//         }
//      }
//      if (isset($class->fields['dnsname'])) {
//         if ($class->fields['dnsname'] && !in_array('dnsname', $a_lockable)) {
//            $class->fields['dnsname'] = $xml->DNSHOSTNAME;
//         }
//      }
//      if ($class->fields['serial'] && !in_array('serial', $a_lockable))
//         $class->fields['serial'] = trim($xml->SERIAL);
//      if ($class->fields['contact'] && !in_array('contact', $a_lockable))
//         $class->fields['contact'] = $xml->USERSESSION;
//      if (isset($class->fields['domain'])) {
//         if ($class->fields['domain'] && !in_array('domain', $a_lockable)) {
//            if (!empty($xml->WORKGROUP)) {
//            $class->fields['domain'] = Dropdown::importExternal("Domain",
//                                    $xml->WORKGROUP,$xml->ENTITY);
//            }
//         }
//      }
//      if (isset($class->fields['ip'])) {
//         if ($class->fields['ip'] && !in_array('ip', $a_lockable)) {
//            $class->fields['ip'] = $xml->IP;
//         }
//      }
//      if (isset($class->fields['mac'])) {
//         if ($class->fields['mac'] && !in_array('mac', $a_lockable)) {
//            $class->fields['mac'] = $xml->MAC;
//         }
//      }
//
//      if ($itemtype == 'PluginFusioninventoryUnknownDevice') {
//         if ($class->fields['comment'] && !in_array('comment', $a_lockable))
//            $class->fields['comment'] = trim($xml->DESCRIPTION);
//      }

//      $class->update($class->fields);
      
   }

}

?>