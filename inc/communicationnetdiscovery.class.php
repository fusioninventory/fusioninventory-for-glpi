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
         $input['globalcriteria'][] = 1;
         $input['serialnumber'] = (string)$p_xml->SERIAL;
      }
      if ((isset($p_xml->MAC)) AND (!empty($p_xml->MAC))) {
         $input['globalcriteria'][] = 2;
         $input['mac'] = (string)$p_xml->MAC;
      }
      if ((isset($p_xml->MODELSNMP)) AND (!empty($p_xml->MODELSNMP))) {
         $input['globalcriteria'][] = 3;
         $input['model'] = (string)$p_xml->MODELSNMP;
      }
      if ((isset($p_xml->NETBIOSNAME)) AND (!empty($p_xml->NETBIOSNAME))) {
         $input['globalcriteria'][] = 4;
         $input['name'] = (string)$p_xml->NETBIOSNAME;
      } else if ((isset($p_xml->SNMPHOSTNAME)) AND (!empty($p_xml->SNMPHOSTNAME))) {
         $input['globalcriteria'][] = 4;
         $input['name'] = (string)$p_xml->SNMPHOSTNAME;
      }
      $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize($input);
      $rule = new PluginFusinvsnmpRuleNetdiscoveryCollection();
      $data = array ();
      $data = $rule->processAllRules($input, array());
   }



   function checkCriteria($a_criteria) {
      global $DB;
      
      $PluginFusinvsnmpCommunicationSNMP = new PluginFusinvsnmpCommunicationSNMP();
      
      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);

      switch ($xml->TYPE) {

         case '0':
            // Don't know what device type it is

            break;

         case '1':
            // Computer
            $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'Computer');
            $result = $a_return[0];
            $input = $a_return[1];

            break;

         case '2':
            // NetworkEquipment
            $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'NetworkEquipment');
            $result = $a_return[0];
            $input = $a_return[1];
            if ($DB->numrows($result)) {
               $this->importDevice('NetworkEquipment', $DB->result($result,0,'id'));
            } else {
               // unknowndevice
               $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'PluginFusioninventoryUnknownDevice');
               $result = $a_return[0];
               $input = $a_return[1];
               if (isset($result) AND ($DB->numrows($result) > 0)) {
                  $this->importDevice('PluginFusioninventoryUnknownDevice', $DB->result($result,0,'id'));
               } else {
                  $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
                  $id = $PluginFusioninventoryUnknownDevice->add($input);
                  $this->importDevice('PluginFusioninventoryUnknownDevice', $id);
               }
            }
            break;

         case '3':
            // Printer
            $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'Printer');
            $result = $a_return[0];
            $input = $a_return[1];
            if ($DB->numrows($result)) {
               $this->importDevice('Printer', $DB->result($result,0,'id'));
            } else {
               // unknowndevice
               $a_return = $PluginFusinvsnmpCommunicationSNMP->searchDevice($a_criteria, 'PluginFusioninventoryUnknownDevice');
               $result = $a_return[0];
               $input = $a_return[1];
               if (isset($result) AND ($DB->numrows($result) > 0)) {
                  $this->importDevice('PluginFusioninventoryUnknownDevice', $DB->result($result,0,'id'));
               } else {
                  $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
                  $id = $PluginFusioninventoryUnknownDevice->add($input);
                  $this->importDevice('PluginFusioninventoryUnknownDevice', $id);
               }
            }
            break;

      }

      
   }


   function importDevice($itemtype, $items_id) {

      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);
      $class = new $itemtype();
      $class->getFromDB($items_id);

      $a_lockable = PluginFusioninventoryLock::getLockFields($itemtype, $items_id);

      switch ($itemtype) {
         
         case 'Computer':
         case 'Printer':


            break;

         case 'PluginFusioninventoryUnknownDevice':
            if ($class->fields['name'] && !in_array('name', $a_lockable)) {
               if (!empty($xml->NETBIOSNAME)) {
                  $class->fields['name'] = $xml->NETBIOSNAME;
               } else if (!empty($xml->SNMPHOSTNAME)) {
                  $class->fields['name'] = $xml->SNMPHOSTNAME;
               } else if (!empty($xml->DNSHOSTNAME)) {
                  $class->fields['name'] = $xml->DNSHOSTNAME;
               }
            }
            if ($class->fields['serial'] && !in_array('serial', $a_lockable))
               $class->fields['serial'] = trim($xml->SERIAL);
            if ($class->fields['contact'] && !in_array('contact', $a_lockable))
               $class->fields['contact'] = $xml->USERSESSION;
            if (isset($class->fields['domain'])) {
               if ($class->fields['domain'] && !in_array('domain', $a_lockable)) {
                  if (!empty($xml->WORKGROUP)) {
                  $class->fields['domain'] = Dropdown::importExternal("Domain",
                                          $xml->WORKGROUP,$xml->ENTITY);
                  }
               }
            }
            if (!empty($xml->TYPE)) {
               switch ($xml->TYPE) {

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
               $class->update($class->fields);
            }
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