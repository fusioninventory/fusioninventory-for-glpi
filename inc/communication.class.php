<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

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
// Purpose of file: management of communication with ocsinventoryng agents
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
 * Class to communicate with agents using XML
 **/
class PluginFusioninventoryCommunication {
   private $sxml, $deviceId, $ptd, $type='', $logFile;

   function __construct() {
      $this->sxml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><REPLY></REPLY>");
         $sxml_option = $this->sxml->addChild('OPTION');
            $sxml_option->addChild('NAME', 'DOWNLOAD');
            $sxml_param = $sxml_option->addChild('PARAM');
               $sxml_param->addAttribute('FRAG_LATENCY', '10');
               $sxml_param->addAttribute('PERIOD_LATENCY', '10');
               $sxml_param->addAttribute('TIMEOUT', '30');
               $sxml_param->addAttribute('ON', '1');
               $sxml_param->addAttribute('TYPE', 'CONF');
               $sxml_param->addAttribute('CYCLE_LATENCY', '60');
               $sxml_param->addAttribute('PERIOD_LENGTH', '10');
         $this->sxml->addChild('PROLOG_FREQ', '24'); // a recup dans base config --> pas trouvé
         $this->logFile = GLPI_ROOT.'/files/_plugins/fusioninventory/communication.log';
         $this->addLog('New PluginFusioninventoryCommunication object.');
   }

   /**
    * Get readable XML code (add carriage returns)
    *
    *@return readable XML code
    **/
   function getXML() {
      return $this->formatXmlString();
   }

   /**
    * Set XML code
    *
    *@param $p_xml XML code
    *@return nothing
    **/
   function setXML($p_xml) {
      $this->sxml = @simplexml_load_string($p_xml); // @ to avoid xml warnings
   }

   /**
    * Get XML code
    *
    *@return XML code
    **/
   function get() {
      if ($GLOBALS["HTTP_RAW_POST_DATA"] == '') {
         return '';
      } else {
         return gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
      }
   }

   /**
    * Get data ready to be send (gzcompressed)
    * 
    *@return data ready to be send
    **/
   function getSend() {
      return gzcompress($this->sxml->asXML());
   }

   /**
    * Check connection string
    *
    *@param &$errors errors string to be alimented if connection ko
    *@return true (connection ok) / false (connection ko)
    **/
   function connectionOK(&$errors='') {
      // TODO : gérer l'encodage, la version
      // pas gérer le REQUEST (tjs pareil)
/*      $get = '<?xml version="1.0" encoding="UTF-8"?>
      <REQUEST>
        <DEVICEID>idefix-2009-11-18-10-19-58-1</DEVICEID>
        <QUERY>PROLOG</QUERY>
      </REQUEST>';*/
      $get=$this->get();
      $errors='';
      $sxml_prolog = @simplexml_load_string($get); // @ to avoid xml warnings


      if ($sxml_prolog->DEVICEID=='') {
         $errors.="DEVICEID invalide\n";
      }
      if ($sxml_prolog->QUERY!='PROLOG') {
         $errors.="QUERY invalide\n";
      }
      $result=false;
      if ($errors=='') {
         $result=true;
      }
      return $result;
   }

   /**
    * Add SNMPQUERY string to XML code
    *
    *@return nothing
    **/
   function addQuery($pxml, $task=0) {
      $ptmi    = new PluginFusioninventoryModelInfos;
      $ptsnmpa = new PluginFusioninventorySnmpauth;
      $pta     = new PluginFusioninventoryAgents;
      $ptap    = new PluginFusioninventoryAgentsProcesses;
      $ptrip   = new PluginFusioninventoryRangeIP;
      $ptt     = new PluginFusioninventoryTask;

      $agent = $pta->InfosByKey($pxml->DEVICEID);
      $count_range = $ptrip->Counter($agent["ID"], "query");
      $count_range += $ptt->Counter($agent["ID"], "SNMPQUERY");
      if ($task == "1") {
         $tasks = $ptt->ListTask($agent["ID"], "SNMPQUERY");
         foreach ($tasks as $task_id=>$taskInfos) {
            file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/query.log".rand(), $agent["ID"]);
            if ($tasks[$task_id]["param"] == PLUGIN_FUSIONINVENTORY_SNMP_AGENTS) {
               $task = "0";
            }
         }
         if ($task == "1") {
            $agent["core_query"] = 1;
            $agent["threads_query"] = 1;
         }
      }

      // Get total number of devices to query
      $ranges = $ptrip->ListRange($agent["ID"], "query");
      $modelslistused = array();
      foreach ($ranges as $range_id=>$rangeInfos) {
         $modelslistused = $this->addDevice($sxml_option, 'networking', $ranges[$range_id]["ifaddr_start"],
                     $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["FK_entities"], $modelslistused,0);
         $modelslistused = $this->addDevice($sxml_option, 'printer', $ranges[$range_id]["ifaddr_start"],
                     $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["FK_entities"], $modelslistused,0);
      }


      if ((($count_range > 0) AND ($agent["lock"] == 0) AND (!empty($modelslistused))) OR ($task == "1")) {
         $a_input = array();
         if ($_SESSION['glpi_plugin_fusioninventory_addagentprocess'] == '0') {
            $this->addProcessNumber($ptap->addProcess($pxml));
            $_SESSION['glpi_plugin_fusioninventory_addagentprocess'] = '1';
         }
         $a_input['query_core'] = $agent["core_query"];
         $a_input['query_threads'] = $agent["threads_query"];
         $ptap->updateProcess($this->sxml->PROCESSNUMBER, $a_input);

         $sxml_option = $this->sxml->addChild('OPTION');
            $sxml_option->addChild('NAME', 'SNMPQUERY');
            $sxml_param = $sxml_option->addChild('PARAM');
               $sxml_param->addAttribute('CORE_QUERY', $agent["core_query"]);
               $sxml_param->addAttribute('THREADS_QUERY', $agent["threads_query"]);
               $sxml_param->addAttribute('PID', $this->sxml->PROCESSNUMBER);


               if ($task == "1") {
                  foreach ($tasks as $task_id=>$taskInfos) {
                     // TODO : envoyer une plage avec juste cette ip ***
                     switch ($tasks[$task_id]['itemtype']) {

                        case NETWORKING_TYPE:
                           $modelslistused = $this->addDevice($sxml_option, 'networking', 0,
                                 0, "-1", $modelslistused, 1, $tasks[$task_id]['on_device']);
                           break;

                        case PRINTER_TYPE:
                           $modelslistused = $this->addDevice($sxml_option, 'printer', 0,
                                 0, "-1", $modelslistused, 1, $tasks[$task_id]['on_device']);
                           break;
                        
                     }


                     //
                     //
//                     $modelslistused = $this->addDevice($sxml_option, 'networking', $ranges[$range_id]["ifaddr_start"],
//                                 $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["FK_entities"], $modelslistused);
                  }
               } else {
                  $ranges = $ptrip->ListRange($agent["ID"], "query");
                  $modelslistused = array();
                  foreach ($ranges as $range_id=>$rangeInfos) {
                     $modelslistused = $this->addDevice($sxml_option, 'networking', $ranges[$range_id]["ifaddr_start"],
                                 $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["FK_entities"], $modelslistused);
                     $modelslistused = $this->addDevice($sxml_option, 'printer', $ranges[$range_id]["ifaddr_start"],
                                 $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["FK_entities"], $modelslistused);
                  }
               }

            $snmpauthlist=$ptsnmpa->find();
            if (count($snmpauthlist)){
               foreach ($snmpauthlist as $snmpauth){
                  $this->addAuth($sxml_option, $snmpauth['ID']);
               }
            }

            $modelslist=$ptmi->find();
            if (count($modelslist)){
               foreach ($modelslist as $model){
                  if (isset($modelslistused[$model['ID']])) {
                     $this->addModel($sxml_option, $model['ID']);
                  }
               }
            }
      }
   }

   /**
    * Add NETDISCOVERY string to XML code
    *
    *@return nothing
    **/
   function addDiscovery($pxml, $task=0) {
      $ptsnmpa = new PluginFusioninventorySnmpauth;
      $pta     = new PluginFusioninventoryAgents;
      $ptap    = new PluginFusioninventoryAgentsProcesses;
      $ptrip   = new PluginFusioninventoryRangeIP;
      $ptt     = new PluginFusioninventoryTask;

      $agent = $pta->InfosByKey($pxml->DEVICEID);
      $count_range = $ptrip->Counter($agent["ID"], "discover");
      $count_range += $ptt->Counter($agent["ID"], "NETDISCOVERY");
      if ($task == "1") {
         $tasks = $ptt->ListTask($agent["ID"], "NETDISCOVERY");
         foreach ($tasks as $task_id=>$taskInfos) {
            if ($tasks[$task_id]["param"] == PLUGIN_FUSIONINVENTORY_SNMP_AGENTS) {
               $task = "0";
            }
         }
         if ($task == "1") {
            $agent["core_discovery"] = 1;
            $agent["threads_discovery"] = 1;
         }
      }

      if ((($count_range > 0) && ($agent["lock"] == 0)) OR ($task == "1") ) {
         $a_input = array();
         if ($_SESSION['glpi_plugin_fusioninventory_addagentprocess'] == '0') {
            $this->addProcessNumber($ptap->addProcess($pxml));
            $_SESSION['glpi_plugin_fusioninventory_addagentprocess'] = '1';
         }
         $a_input['discovery_core'] = $agent["core_discovery"];
         $a_input['discovery_threads'] = $agent["threads_discovery"];
         $ptap->updateProcess($this->sxml->PROCESSNUMBER, $a_input);

         $sxml_option = $this->sxml->addChild('OPTION');
            $sxml_option->addChild('NAME', 'NETDISCOVERY');
            $sxml_param = $sxml_option->addChild('PARAM');
               $sxml_param->addAttribute('CORE_DISCOVERY', $agent["core_discovery"]);
               $sxml_param->addAttribute('THREADS_DISCOVERY', $agent["threads_discovery"]);
               $sxml_param->addAttribute('PID', $this->sxml->PROCESSNUMBER);

            if ($task == "1") {
               foreach ($tasks as $task_id=>$taskInfos) {
                  $sxml_rangeip = $sxml_option->addChild('RANGEIP');
                     $sxml_rangeip->addAttribute('ID', $task_id);
                     $sxml_rangeip->addAttribute('IPSTART', $tasks[$task_id]["ifaddr"]);
                     $sxml_rangeip->addAttribute('IPEND', $tasks[$task_id]["ifaddr"]);
                     $sxml_rangeip->addAttribute('ENTITY', "");
                     $sxml_rangeip->addAttribute('DEVICEID', $tasks[$task_id]["on_device"]);
                     $sxml_rangeip->addAttribute('TYPE', $tasks[$task_id]["itemtype"]);

                     $ptt->deleteFromDB($task_id);
               }

            } else {
               $ranges = $ptrip->ListRange($agent["ID"], "discover");
               foreach ($ranges as $range_id=>$rangeInfos) {
                  $sxml_rangeip = $sxml_option->addChild('RANGEIP');
                     $sxml_rangeip->addAttribute('ID', $range_id);
                     $sxml_rangeip->addAttribute('IPSTART', $ranges[$range_id]["ifaddr_start"]);
                     $sxml_rangeip->addAttribute('IPEND', $ranges[$range_id]["ifaddr_end"]);
                     $sxml_rangeip->addAttribute('ENTITY', $ranges[$range_id]["FK_entities"]);
               }
            }
            
            $snmpauthlist=$ptsnmpa->find();
            if (count($snmpauthlist)){
               foreach ($snmpauthlist as $snmpauth){
                  $this->addAuth($sxml_option, $snmpauth['ID']);
               }
            }
         //$this->sxml->addChild('RESPONSE', 'SEND');
      }
   }

   /**
    * Add AUTHENTICATION string to XML node
    *
    *@param $p_sxml_node XML node to authenticate
    *@param $p_id Authenticate id
    *@return nothing
    **/
   function addAuth($p_sxml_node, $p_id) {
      $ptsnmpa = new PluginFusioninventorySnmpauth;
      $ptsnmpa->getFromDB($p_id);

      $sxml_authentication = $p_sxml_node->addChild('AUTHENTICATION');
         $sxml_authentication->addAttribute('ID', $p_id);
         $sxml_authentication->addAttribute('COMMUNITY', $ptsnmpa->fields['community']);
         $sxml_authentication->addAttribute('VERSION',
                            Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpversions',
                                            $ptsnmpa->fields['FK_snmp_version']));
         $sxml_authentication->addAttribute('USERNAME', $ptsnmpa->fields['sec_name']);
         if ($ptsnmpa->fields['auth_protocol'] == '0') {
            $sxml_authentication->addAttribute('AUTHPROTOCOL', '');
         } else {
            $sxml_authentication->addAttribute('AUTHPROTOCOL',
                            Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpprotocolauths',
                                            $ptsnmpa->fields['auth_protocol']));
         }
         $sxml_authentication->addAttribute('AUTHPASSPHRASE', $ptsnmpa->fields['auth_passphrase']);
         if ($ptsnmpa->fields['priv_protocol'] == '0') {
            $sxml_authentication->addAttribute('PRIVPROTOCOL', '');
         } else {
            $sxml_authentication->addAttribute('PRIVPROTOCOL',
                    Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpprotocolprivs',
                                    $ptsnmpa->fields['priv_protocol']));
         }
         $sxml_authentication->addAttribute('PRIVPASSPHRASE', $ptsnmpa->fields['priv_passphrase']);
   }

   /**
    * Add MODEL string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_id Model id
    *@return nothing
    **/
   function addModel($p_sxml_node, $p_id) {
      $models = new PluginFusioninventoryModelInfos;
      $mib_networking = new PluginFusioninventoryMib;

      $models->getFromDB($p_id);
      $sxml_model = $p_sxml_node->addChild('MODEL');
         $sxml_model->addAttribute('ID', $p_id);
         $sxml_model->addAttribute('NAME', $models->fields['name']);
         $mib_networking->oidList($sxml_model,$p_id);
   }

   /**
    * Add GET string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_object Value of OBJECT attribute
    *@param $p_oid Value of OID attribute
    *@param $p_link Value of LINK attribute
    *@param $p_vlan Value of VLAN attribute
    *@return nothing
    **/
   function addGet($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_get = $p_sxml_node->addChild('GET');
         $sxml_get->addAttribute('OBJECT', $p_object);
         $sxml_get->addAttribute('OID', $p_oid);
         $sxml_get->addAttribute('VLAN', $p_vlan);
         $sxml_get->addAttribute('LINK', $p_link);
   }

   /**
    * Add WALK string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_object Value of OBJECT attribute
    *@param $p_oid Value of OID attribute
    *@param $p_link Value of LINK attribute
    *@param $p_vlan Value of VLAN attribute
    *@return nothing
    **/
   function addWalk($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_walk = $p_sxml_node->addChild('WALK');
         $sxml_walk->addAttribute('OBJECT', $p_object);
         $sxml_walk->addAttribute('OID', $p_oid);
         $sxml_walk->addAttribute('VLAN', $p_vlan);
         $sxml_walk->addAttribute('LINK', $p_link);
   }

   /**
    * Add INFO string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_id Value of ID attribute
    *@param $p_ip Value of IP attribute
    *@param $p_authsnmp_id Value of AUTHSNMP_ID attribute
    *@param $p_model_id Value of MODELSNMP_ID attribute
    *@param $p_type device type
    *@return nothing
    **/
   function addInfo($p_sxml_node, $p_id, $p_ip, $p_authsnmp_id, $p_model_id, $p_type) {
      $sxml_device = $p_sxml_node->addChild('DEVICE');
         $sxml_device->addAttribute('TYPE', $p_type);
         $sxml_device->addAttribute('ID', $p_id);
         $sxml_device->addAttribute('IP', $p_ip);
         $sxml_device->addAttribute('AUTHSNMP_ID', $p_authsnmp_id);
         $sxml_device->addAttribute('MODELSNMP_ID', $p_model_id);
   }

   /**
    * Add DEVICE string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_type Type of device
    *@param $p_ipstart Start ip of range
    *@param $p_ipend End ip of range
    *@param $p_entity Entity of device
    *@return true (device added) / false (unknown type of device)
    **/
   function addDevice($p_sxml_node, $p_type, $p_ipstart, $p_ipend, $p_entity, $modelslistused, $addingdevice=1, $devide_id=0) {
      global $DB;

      $type='';
      switch ($p_type) {
         
         case "networking":
            $type='NETWORKING';
            $query = "SELECT `glpi_networking`.`ID` AS `gID`,
                             `glpi_networking`.`ifaddr` AS `gnifaddr`,
                             `FK_snmp_connection`, `FK_model_infos`
                      FROM `glpi_networking`
                      LEFT JOIN `glpi_plugin_fusioninventory_networking`
                           ON `FK_networking`=`glpi_networking`.`ID`
                      INNER join `glpi_plugin_fusioninventory_model_infos`
                           ON `FK_model_infos`=`glpi_plugin_fusioninventory_model_infos`.`ID`
                      WHERE `glpi_networking`.`deleted`='0'
                           AND `FK_model_infos`!='0'
                           AND `FK_snmp_connection`!='0'";
             if ($p_entity != '-1') {
               $query .= "AND `glpi_networking`.`FK_entities`='".$p_entity."' ";
             }
             if ($p_ipstart == '0') {
               $query .= " AND `glpi_networking`.`ID`='".$devide_id."'";
             } else {
               $query .= " AND inet_aton(`ifaddr`)
                               BETWEEN inet_aton('".$p_ipstart."')
                               AND inet_aton('".$p_ipend."') ";
             }

            break;
         
         case "printer":
            $type='PRINTER';
            $query = "SELECT `glpi_printers`.`ID` AS `gID`,
                             `glpi_networking_ports`.`ifaddr` AS `gnifaddr`,
                             `FK_snmp_connection`, `FK_model_infos`
                      FROM `glpi_printers`
                      LEFT JOIN `glpi_plugin_fusioninventory_printers`
                              ON `FK_printers`=`glpi_printers`.`ID`
                      LEFT JOIN `glpi_networking_ports`
                              ON `on_device`=`glpi_printers`.`ID`
                                 AND `itemtype`='".PRINTER_TYPE."'
                      INNER join `glpi_plugin_fusioninventory_model_infos`
                           ON `FK_model_infos`=`glpi_plugin_fusioninventory_model_infos`.`ID`
                      WHERE `glpi_printers`.`deleted`=0
                            AND `FK_model_infos`!='0'
                            AND `FK_snmp_connection`!='0'";
             if ($p_entity != '-1') {
               $query .= "AND `glpi_printers`.`FK_entities`='".$p_entity."' ";
             }
             if ($p_ipstart == '0') {
               $query .= " AND `glpi_printers`.`ID`='".$devide_id."'";
             } else {
               $query .= " AND inet_aton(`ifaddr`)
                               BETWEEN inet_aton('".$p_ipstart."')
                               AND inet_aton('".$p_ipend."') ";
             }


            break;
         
         default: // type non géré
            return $modelslistused;
      }
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if ($addingdevice == '1') {
            $this->addInfo($p_sxml_node,
                           $data['gID'],
                           $data['gnifaddr'],
                           $data['FK_snmp_connection'],
                           $data['FK_model_infos'],
                           $type);
         }
         $modelslistused[$data['FK_model_infos']] = 1;
      }
      return $modelslistused;
   }

   /**
    * Import data
    *
    *@param $p_xml XML code to import
    *@param &$p_errors errors string to be alimented if import ko
    *@return true (import ok) / false (import ko)
    **/
   function import($p_xml, &$p_errors='') {
      global $LANG;

      $this->addLog('Function import().');
      // TODO : gérer l'encodage, la version
      // Do not manage <REQUEST> element (always the same)
      $this->setXML($p_xml);
      $errors = '';

      if (isset($this->sxml->CONTENT->PROCESSNUMBER)) {
         $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $this->sxml->CONTENT->PROCESSNUMBER;
      }
      switch ($this->sxml->QUERY) {
         case 'SNMPQUERY' :
            $errors.=$this->importContent($this->sxml->CONTENT);
            break;
         
         case 'NETDISCOVERY' :
            $pti = new PluginFusioninventoryImportExport;
            $errors.=$pti->import_netdiscovery($this->sxml->CONTENT, $this->sxml->DEVICEID);
            break;
         
         case 'INVENTORY' :
            $this->sendInventoryToOcsServer($p_xml);
            break;

         case 'QUERY' :
            break;

         default :
            $errors.=$LANG['plugin_fusioninventory']["errors"][22].' QUERY : *'.$this->sxml->QUERY."*\n";
      }
      $result=true;
      if ($errors != '') {
         if (isset($_SESSION['glpi_plugin_fusioninventory_processnumber'])) {
            $result=true;
            $ptap = new PluginFusioninventoryAgentsProcesses;
            $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                                 array('comments' => $errors));

         } else {
            // It's PROLOG
            $result=false;
         }
      }
      return $result;
   }

   /**
    * Import CONTENT
    *@param $p_content CONTENT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importContent($p_content) {
      global $LANG;

      $this->addLog('Function importContent().');
      $ptap = new PluginFusioninventoryAgentsProcesses;
      $pta  = new PluginFusioninventoryAgents;
      
      $errors='';
      $nbDevices = 0;

      foreach ($p_content->children() as $child) {
         $this->addLog($child->getName());
         switch ($child->getName()) {
            case 'DEVICE' :
               $errors.=$this->importDevice($child);
               $nbDevices++;
               break;

            case 'AGENT' :
               if (isset($this->sxml->CONTENT->AGENT->START)) {
                  $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                                       array('start_time_query' => date("Y-m-d H:i:s")));
               } else if (isset($this->sxml->CONTENT->AGENT->END)) {
                  $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                                       array('end_time_query' => date("Y-m-d H:i:s")));
               } else if (isset($this->sxml->CONTENT->AGENT->EXIT)) {
                  $ptap->endProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                                       date("Y-m-d H:i:s"));
               }
               if (isset($this->sxml->CONTENT->AGENT->AGENTVERSION)) {
                  $agent = $pta->InfosByKey($this->sxml->DEVICEID);
                  $agent['fusioninventory_agent_version'] = $this->sxml->CONTENT->AGENT->AGENTVERSION;
                  $agent['last_agent_update'] = date("Y-m-d H:i:s");
                  //$p_xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
                  $pta->update($agent);
               }
               break;

            case 'PROCESSNUMBER' :
               break;
            
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONTENT : '.$child->getName()."\n";
         }
      }
      return $errors;
   }

   /**
    * Import DEVICE
    *@param $p_device DEVICE code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importDevice($p_device) {
      global $LANG;

      $this->addLog('Function importDevice().');
      $ptap = new PluginFusioninventoryAgentsProcesses;
      $ptae = new PluginFusioninventoryAgentsErrors;

      $errors=''; $this->deviceId='';
      switch ($p_device->INFO->TYPE) {
         case 'PRINTER':
            $this->type = PRINTER_TYPE;
            break;
         case 'NETWORKING':
            $this->type = NETWORKING_TYPE;
            break;
         default:
            $errors.=$LANG['plugin_fusioninventory']["errors"][22].' TYPE : '
                              .$p_device->INFO->TYPE."\n";
      }
      if (isset($p_device->ERROR)) {
         $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                              array('query_nb_error' => '1'));
         $a_input = array();
         $a_input['ID'] = $p_device->ERROR->ID;
         if ($p_device->ERROR->TYPE=='NETWORKING') {
            $a_input['TYPE'] = NETWORKING_TYPE;
         } elseif ($p_device->ERROR->TYPE=='PRINTER') {
            $a_input['TYPE'] = PRINTER_TYPE;
         }
         $a_input['MESSAGE'] = $p_device->ERROR->MESSAGE;
         $a_input['agent_type'] = 'SNMPQUERY';
         $ptae->addError($a_input);
      } else {
         $ptap->updateProcess($this->sxml->CONTENT->PROCESSNUMBER, array('query_nb_query' => '1'));
         $errors.=$this->importInfo($p_device->INFO, $p_device);
         if ($this->deviceId!='') {
            foreach ($p_device->children() as $child) {
               switch ($child->getName()) {
                  case 'INFO' : // already managed
                     break;
                  case 'PORTS' :
                     $errors.=$this->importPorts($child);
                     break;
                  case 'CARTRIDGES' :
                     if ($this->type == PRINTER_TYPE) {
                        $errors.=$this->importCartridges($child);
                        break;
                     }
                  case 'PAGECOUNTERS' :
                     if ($this->type == PRINTER_TYPE) {
                        $errors.=$this->importPageCounters($child);
                        break;
                     }
                  default :
                     $errors.=$LANG['plugin_fusioninventory']["errors"][22].' DEVICE : '
                              .$child->getName()."\n";
               }
            }
            if ($errors=='') {
               $this->ptd->updateDB();
            } else {
               $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                     array('query_nb_error' => '1'));
               $a_input = array();
               $a_input['ID'] = $p_device->ERROR->ID;
               if ($p_device->ERROR->TYPE=='NETWORKING') {
                  $a_input['TYPE'] = NETWORKING_TYPE;
               } elseif ($p_device->ERROR->TYPE=='PRINTER') {
                  $a_input['TYPE'] = PRINTER_TYPE;
               }
               $a_input['MESSAGE'] = $errors;
               $a_input['agent_type'] = 'SNMPQUERY';
               $ptae->addError($a_input);
            }
         } else {
            $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                  array('query_nb_error' => '1'));
            $a_input = array();
            $a_input['ID'] = $p_device->ERROR->ID;
            if ($p_device->ERROR->TYPE=='NETWORKING') {
               $a_input['TYPE'] = NETWORKING_TYPE;
            } elseif ($p_device->ERROR->TYPE=='PRINTER') {
               $a_input['TYPE'] = PRINTER_TYPE;
            }
            $a_input['MESSAGE'] = $errors;
            $a_input['agent_type'] = 'SNMPQUERY';
            $ptae->addError($a_input);
         }
      }

      return $errors;
   }

   /**
    * Import INFO
    *@param $p_info INFO code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importInfo($p_info, $p_device) {
      global $LANG;

      $this->addLog('Function importInfo().');
      $errors='';
      $criteria['serial']  = $p_info->SERIAL;
      $criteria['name']    = $p_info->NAME;
      $criteria['macaddr'] = $p_info->MAC; //TODO get mac in PORT for printer
      if ($p_info->TYPE=='NETWORKING') {
         $this->deviceId = PluginFusioninventoryDiscovery::criteria($criteria, NETWORKING_TYPE);
         if ($this->deviceId != '') {
            $errors.=$this->importInfoNetworking($p_info);
         } else {
            $errors.=$LANG['plugin_fusioninventory']["errors"][23].'<br/>
                     type : '.$p_info->TYPE.'<br/>
                     ID : '.$p_info->ID.'<br/>
                     serial : '.$p_info->SERIAL.'<br/>
                     name : '.$p_info->NAME.'<br/>
                     macaddress : '.$p_info->MAC.'\n';
         }
      } elseif ($p_info->TYPE=='PRINTER') {
         //TODO Get MAC address in port
         foreach ($p_device->children() as $child) {
            switch ($child->getName()) {
               case 'PORTS' :
                  foreach ($child->children() as $child_port) {
                     switch ($child_port->getName()) {
                        case 'PORT' :
                           $criteria['macaddr'] = $child_port->MAC;
                           if ($this->deviceId == '') {
                              $this->deviceId = PluginFusioninventoryDiscovery::criteria($criteria, PRINTER_TYPE);
                           }
                           break;
                     }
                  }
                  break;
            }
         }

         //$this->deviceId = PluginFusioninventoryDiscovery::criteria($criteria, PRINTER_TYPE);
         if ($this->deviceId != '') {
            $errors.=$this->importInfoPrinter($p_info);
         } else {
            $errors.=$LANG['plugin_fusioninventory']["errors"][23].'<br/>
                     type : '.$p_info->TYPE.'<br/>
                     ID : '.$p_info->ID.'<br/>
                     serial : '.$p_info->SERIAL.'<br/>
                     name : '.$p_info->NAME.'<br/>
                     macaddress : '.$p_info->MAC.'\n';
         }
      }
      if (!empty($errors)) {
         $pfiae = new PluginFusioninventoryAgentsErrors;

         $a_input = array();
         $a_input['ID'] = $p_info->ID;
         if ($p_info->TYPE=='NETWORKING') {
            $a_input['TYPE'] = NETWORKING_TYPE;
         } elseif ($p_info->TYPE=='PRINTER') {
            $a_input['TYPE'] = PRINTER_TYPE;
         }
         $a_input['MESSAGE'] = $errors;
         $a_input['agent_type'] = 'SNMPQUERY';
         $pfiae->addError($a_input);
      }

      return $errors;
   }

   /**
    * Import INFO:Networking
    *@param $p_info INFO code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importInfoNetworking($p_info) {
      global $LANG;

      $errors='';
      $this->ptd = new PluginFusioninventoryNetworking;
      $this->ptd->load($this->deviceId);

      foreach ($p_info->children() as $child)
      {
         switch ($child->getName()) {
            case 'ID' : // already managed
               break;
            case 'TYPE' : // already managed
               break;
            case 'COMMENTS' :
               $this->ptd->setValue('comments', $p_info->COMMENTS);
               break;
            case 'CPU' :
               $this->ptd->setValue('cpu', $p_info->CPU);
               break;
            case 'FIRMWARE' :
               $this->ptd->setValue('firmware', $p_info->FIRMWARE);
               break;
            case 'MAC' :
               $this->ptd->setValue('ifmac', $p_info->MAC);
               break;
            case 'MEMORY' :
               $this->ptd->setValue('memory', $p_info->MEMORY);
               break;
            case 'MODEL' :
               $this->ptd->setValue('model', $p_info->MODEL);
               break;
            case 'LOCATION' :
               $this->ptd->setValue('location', $p_info->LOCATION);
               break;
            case 'NAME' :
               $this->ptd->setValue('name', $p_info->NAME);
               break;
            case 'RAM' :
               $this->ptd->setValue('ram', $p_info->RAM);
               break;
            case 'SERIAL' :
               $this->ptd->setValue('serial', $p_info->SERIAL);
               break;
            case 'UPTIME' :
               $this->ptd->setValue('uptime', $p_info->UPTIME);
               break;
            case 'IPS' :
               $errors.=$this->importIps($child);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' INFO : '.$child->getName()."\n";
         }
      }
      return $errors;
   }

   /**
    * Import INFO:Printer
    *@param $p_info INFO code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importInfoPrinter($p_info) {
      global $LANG;

      $errors='';
      $this->ptd = new PluginFusioninventoryPrinter;
      $this->ptd->load($this->deviceId);
      foreach ($p_info->children() as $child) {
         switch ($child->getName()) {
            case 'ID' : // already managed
               break;
            case 'TYPE' : // already managed
               break;
            case 'COMMENTS' :
               $this->ptd->setValue('comments', $p_info->COMMENTS);
               break;
            case 'MEMORY' :
               $this->ptd->setValue('memory', $p_info->MEMORY);
               break;
            case 'MODEL' :
               $this->ptd->setValue('model', $p_info->MODEL);
               break;
            case 'NAME' :
               $this->ptd->setValue('name', $p_info->NAME);
               break;
            case 'SERIAL' :
               $this->ptd->setValue('serial', $p_info->SERIAL);
               break;
            case 'OTHERSERIAL' :
               $this->ptd->setValue('otherserial', $p_info->OTHERSERIAL);
               break;
            case 'LOCATION' :
               $this->ptd->setValue('location', $p_info->LOCATION);
               break;
            case 'CONTACT' :
               $this->ptd->setValue('contact', $p_info->CONTACT);
               break;
            case 'MANUFACTURER' :
               $this->ptd->setValue('manufacturer', $p_info->MANUFACTURER); // TODO : regrouper tout ces cases
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' INFO : '.$child->getName()."\n";
         }
      }

      return $errors;
   }

   /**
    * Import IPS
    *@param $p_ips IPS code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importIps($p_ips) {
      global $LANG;

      $errors='';
      $pti = new PluginFusioninventoryIfaddr;
      foreach ($p_ips->children() as $name=>$child) {
         switch ($child->getName()) {
            case 'IP' :
               $ifaddrIndex = $this->ptd->getIfaddrIndex($child);
               if (is_int($ifaddrIndex)) {
                  $oldIfaddr = $this->ptd->getIfaddr($ifaddrIndex);
                  $pti->load($oldIfaddr->getValue('ID'));
               } else {
                  $pti->load();
               }
               $pti->setValue('ifaddr', $child);
               $this->ptd->addIfaddr(clone $pti, $ifaddrIndex);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' IPS : '.$child->getName()."\n";
         }
      }
      $this->ptd->saveIfaddrs();
      return $errors;
   }

   /**
    * Import PORTS
    *@param $p_ports PORTS code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPorts($p_ports) {
      global $LANG;

      $this->addLog('Function importPorts().');
      $errors='';
      foreach ($p_ports->children() as $name=>$child)
      {
         switch ($child->getName()) {
            case 'PORT' :
               if ($this->type == PRINTER_TYPE) {
                  $errors.=$this->importPortPrinter($child);
               } elseif ($this->type == NETWORKING_TYPE) {
                  $errors.=$this->importPortNetworking($child);
               }
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' PORTS : '.$child->getName()."\n";
         }
      }
      return $errors;
   }

   /**
    * Import PORT Networking
    *@param $p_port PORT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPortNetworking($p_port) {
      global $LANG;

      $this->addLog('Function importPortNetworking().');
      $errors='';
//      $ptp = new PluginFusioninventoryPort(NETWORKING_TYPE);
      $ptp = new PluginFusioninventoryPort(NETWORKING_TYPE, $this->logFile);
      $ifType = $p_port->IFTYPE;
      if ( $ptp->isReal($ifType) ) { // not virtual port
         $portIndex = $this->ptd->getPortIndex($p_port->IFNUMBER, $this->getConnectionIP($p_port));
         if (is_int($portIndex)) {
            $oldPort = $this->ptd->getPort($portIndex);
            $ptp->load($oldPort->getValue('ID'));
         } else {
            $ptp->addDB($this->deviceId, TRUE);
         }
         foreach ($p_port->children() as $name=>$child) {
            switch ($name) {
               case 'CONNECTIONS' :
                  $errors.=$this->importConnections($child, $ptp);
                  break;
               case 'VLANS' :
                  $errors.=$this->importVlans($child, $ptp);
                  break;
               case 'IFNAME' :
                  PluginFusioninventorySnmphistory::networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('name', $child);
                  break;
               case 'MAC' :
                  PluginFusioninventorySnmphistory::networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('ifmac', $child);
                  break;
               case 'IFNUMBER' :
                  PluginFusioninventorySnmphistory::networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('logical_number', $child);
                  break;
               case 'IFTYPE' : // already managed
                  break;
               case 'TRUNK' :
                  if (!$ptp->getNoTrunk()) {
                     PluginFusioninventorySnmphistory::networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                     $ptp->setValue('trunk', $p_port->$name);
                  }
                  break;

               case 'IFDESCR' :
               case 'IFINERRORS' :
               case 'IFINOCTETS' :
               case 'IFINTERNALSTATUS' :
               case 'IFLASTCHANGE' :
               case 'IFMTU' :
               case 'IFOUTERRORS' :
               case 'IFOUTOCTETS' :
               case 'IFSPEED' :
               case 'IFSTATUS' :
                  PluginFusioninventorySnmphistory::networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue(strtolower($name), $p_port->$name);
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']["errors"][22].' PORT : '.$name."\n";
            }
         }
         $this->ptd->addPort($ptp, $portIndex);
      } else { // virtual port : do not import but delete if exists
         if ( is_numeric($ptp->getValue('ID')) ) $ptp->deleteDB();
      }
      return $errors;
   }

   /**
    * Import PORT Printer
    *@param $p_port PORT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPortPrinter($p_port) {
      global $LANG;

      $errors='';
      $ptp = new PluginFusioninventoryPort(PRINTER_TYPE);
      $ifType = $p_port->IFTYPE;
      if ( $ptp->isReal($ifType) ) { // not virtual port
         $portIndex = $this->ptd->getPortIndex($p_port->MAC, $p_port->IP);
         if (is_int($portIndex)) {
            $oldPort = $this->ptd->getPort($portIndex);
            $ptp->load($oldPort->getValue('ID'));
         } else {
            $ptp->addDB($this->deviceId, TRUE);
         }
         foreach ($p_port->children() as $name=>$child) {
            switch ($name) {
               case 'IFNAME' :
                  PluginFusioninventorySnmphistory::networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('name', $child);
                  break;
               case 'MAC' :
                  PluginFusioninventorySnmphistory::networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('ifmac', $child);
                  break;
               case 'IP' :
                  PluginFusioninventorySnmphistory::networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('ifaddr', $child);
                  break;
               case 'IFNUMBER' :
                  PluginFusioninventorySnmphistory::networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('logical_number', $child);
                  break;
               case 'IFTYPE' : // already managed
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']["errors"][22].' PORT : '.$name."\n";
            }
         }
         $this->ptd->addPort($ptp, $portIndex);
      }
      return $errors;
   }

   /**
    * Import CARTRIDGES
    *@param $p_cartridges CARTRIDGES code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importCartridges($p_cartridges) {
      global $LANG;

      $errors='';
      foreach ($p_cartridges->children() as $name=>$child)
      {
         switch ($name) {
            case 'TONERBLACK' :
            case 'TONERBLACK2' :
            case 'TONERCYAN' :
            case 'TONERMAGENTA' :
            case 'TONERYELLOW' :
            case 'WASTETONER' :
            case 'CARTRIDGEBLACK' :
            case 'CARTRIDGEBLACKPHOTO' :
            case 'CARTRIDGECYAN' :
            case 'CARTRIDGECYANLIGHT' :
            case 'CARTRIDGEMAGENTA' :
            case 'CARTRIDGEMAGENTALIGHT' :
            case 'CARTRIDGEYELLOW' :
            case 'MAINTENANCEKIT' :
            case 'DRUMBLACK' :
            case 'DRUMCYAN' :
            case 'DRUMMAGENTA' :
            case 'DRUMYELLOW' :
               $ptc = new PluginFusioninventoryCommonDBTM("glpi_plugin_fusioninventory_printers_cartridges");
               $cartridgeIndex = $this->ptd->getCartridgeIndex($name);
               if (is_int($cartridgeIndex)) {
                  $oldCartridge = $this->ptd->getCartridge($cartridgeIndex); //TODO ???
                  $ptc->load($oldCartridge->getValue('ID'));
               } else {
                  $ptc->addCommon(TRUE); //TODO ???
                  $ptc->setValue('FK_printers', $this->deviceId);
               }
               $ptc->setValue('object_name', $name);
               $ptc->setValue('state', $child, $ptc, 0);
               $this->ptd->addCartridge($ptc, $cartridgeIndex);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CARTRIDGES : '.$name."\n";
         }
      }
      return $errors;
   }

   /**
    * Import PAGECOUNTERS
    *@param $p_pagecounters PAGECOUNTERS code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPageCounters($p_pagecounters) {
      global $LANG;

      $errors='';
      foreach ($p_pagecounters->children() as $name=>$child)
      {
         switch ($child->getName()) {
            case 'TOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total', $child);
               break;
            case 'BLACK' :
               $errors.=$this->ptd->addPageCounter('pages_n_b', $child);
               break;
            case 'COLOR' :
               $errors.=$this->ptd->addPageCounter('pages_color', $child);
               break;
            case 'RECTOVERSO' :
               $errors.=$this->ptd->addPageCounter('pages_recto_verso', $child);
               break;
            case 'SCANNED' :
               $errors.=$this->ptd->addPageCounter('scanned', $child);
               break;
            case 'PRINTTOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total_print', $child);
               break;
            case 'PRINTBLACK' :
               $errors.=$this->ptd->addPageCounter('pages_n_b_print', $child);
               break;
            case 'PRINTCOLOR' :
               $errors.=$this->ptd->addPageCounter('pages_color_print', $child);
               break;
            case 'COPYTOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total_copy', $child);
               break;
            case 'COPYBLACK' :
               $errors.=$this->ptd->addPageCounter('pages_n_b_copy', $child);
               break;
            case 'COPYCOLOR' :
               $errors.=$this->ptd->addPageCounter('pages_color_copy', $child);
               break;
            case 'FAXTOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total_fax', $child);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' PAGECOUNTERS : '.$name."\n";
         }
      }
      return $errors;
   }

   /**
    * Import CONNECTIONS
    *@param $p_connections CONNECTIONS code to import
    *@param $p_oPort Port object to connect
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importConnections($p_connections, $p_oPort) {
      global $LANG;

      $this->addLog('Function importConnections().');
      $errors='';
      if (isset($p_connections->CDP)) {
         $cdp = $p_connections->CDP;
         if ($cdp==1) {
            $p_oPort->setCDP();
         } else {
            $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONNECTIONS : CDP='.$cdp."\n";
         }
      } else {
         $cdp = 0;
      }
      $count = 0;
      foreach ($p_connections->children() as $name=>$child) {
         switch ($child->getName()) {
            case 'CDP' : // already managed
               break;
            case 'CONNECTION' :
               $count++;
               $errors.=$this->importConnection($child, $p_oPort, $cdp);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONNECTIONS : '
                        .$child->getName()."\n";
         }
      }
      if ($p_oPort->getValue('trunk')!=1) {
         if ($count > 1) { // MultipleMac
            $p_oPort->setNoTrunk();
            $pfiud = new PluginFusioninventoryUnknownDevice;
            $pfiud->hubNetwork($p_oPort);
         } else {
            if (!$p_oPort->getNoTrunk()) {
               $p_oPort->setValue('trunk', 0);
            }
         }
//      } else {
//         if ($p_oPort->getValue('trunk') == '-1') {
//            $p_oPort->setValue('trunk', '0');
//         }
      }
      return $errors;
   }

   /**
    * Import CONNECTION
    *@param $p_connection CONNECTION code to import
    *@param $p_oPort Port object to connect
    *@param $p_cdp CDP value (1 or <>1)
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importConnection($p_connection, $p_oPort, $p_cdp) {
      global $LANG;

      $this->addLog('Function importConnection().');
      $errors='';
      $portID=''; $mac=''; $ip='';
      $ptsnmp= new PluginFusioninventorySNMP;
      if ($p_cdp==1) {
         $ifdescr='';
         foreach ($p_connection->children() as $name=>$child) {
            switch ($child->getName()) {
               case 'IP' :
                  $ip=$child;
                  $p_oPort->addIp($ip);
                  break;
               case 'IFDESCR' :
                  $ifdescr=$child;
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONNECTION (CDP='.$p_cdp.') : '
                           .$child->getName()."\n";
            }
         }
         $portID=$ptsnmp->getPortIDfromDeviceIP($ip, $ifdescr);
      } else {
         foreach ($p_connection->children() as $name=>$child) {
            switch ($child->getName()) {
               case 'MAC' :
                  $mac=$child;
                  $portID=$ptsnmp->getPortIDfromDeviceMAC($child, $p_oPort->getValue('ID'));
                  $p_oPort->addMac($mac);
                  break;
               case 'IP' ://TODO : si ip ajouter une tache de decouverte sur l'ip pour recup autre info // utile seulement si mac inconnu dans glpi
                  $ip=$child;
                  $p_oPort->addIp($ip);
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONNECTION (CDP='.$p_cdp.') : '
                           .$child->getName()."\n";
            }            
         }
      }
      if ($portID != '') {
         $p_oPort->addConnection($portID);
         if ($ip != '') $p_oPort->setValue('ifaddr', $ip);
      } else {
         $p_oPort->addUnknownConnection($mac, $ip);
         //TODO : si ip ajouter une tache de decouverte sur l'ip pour recup autre info
      }
      return $errors;
   }

   /**
    * Import VLANS
    *@param $p_vlans VLANS code to import
    *@param $p_oPort Port object to connect
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importVlans($p_vlans, $p_oPort) {
      global $LANG;

      $errors='';
      foreach ($p_vlans->children() as $name=>$child)
      {
         switch ($child->getName()) {
            case 'VLAN' :
               $errors.=$this->importVlan($child, $p_oPort);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' VLANS : '.$child->getName()."\n";
         }
      }
      return $errors;
   }

   /**
    * Import VLAN
    *@param $p_vlan VLAN code to import
    *@param $p_oPort Port object to connect
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importVlan($p_vlan, $p_oPort) {
      global $LANG;

      $errors='';
      $number=''; $name='';
      foreach ($p_vlan->children() as $child) {
         switch ($child->getName()) {
            case 'NUMBER' :
               $number=$child;
               break;
            case 'NAME' :
               $name=$child;
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' VLAN : '.$child->getName()."\n";
         }
      }
      $p_oPort->addVlan($number, $name);
      return $errors;
   }

   /**
    * Add indent in XML to have nice XML format
    *
    *@return XML
    **/
   function formatXmlString() {
      $xml = str_replace("><", ">\n<", $this->sxml->asXML());
      $token      = strtok($xml, "\n");
      $result     = '';
      $pad        = 0;
      $matches    = array();
      $indent     = 0;

      while ($token !== false) {
         // 1. open and closing tags on same line - no change
         if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
            $indent=0;
         // 2. closing tag - outdent now
         elseif (preg_match('/^<\/\w/', $token, $matches)) :
            $pad = $pad-3;
         // 3. opening tag - don't pad this one, only subsequent tags
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
      $this->setXML($result);
      return $this->sxml->asXML();
   }


   function addProcessNumber($p_pid) {
      $this->sxml->addChild('PROCESSNUMBER', $p_pid);
      //var_dump($this->sxml);
   }

   /**
    * Get connection IP
    *
    *@param $p_port PORT code to import
    *@return first connection IP or ''
    **/
   function getConnectionIP($p_port) {
      foreach ($p_port->children() as $connectionsName=>$connectionsChild) {
         switch ($connectionsName) {
            case 'CONNECTIONS' :
               foreach ($connectionsChild->children() as $connectionName=>$connectionChild) {
                  switch ($connectionName) {
                     case 'CONNECTION' :
                        foreach ($connectionChild->children() as $ipName=>$ipChild) {
                           switch ($ipName) {
                              case 'IP' :
                                 if ($ipChild != '') return $ipChild;
                           }
                        }
                  }
               }
         }
      }
      return '';
   }

   /**
    * Get printer MAC address
    *
    *@param $p_port PORT code to import
    *@return first connection IP or ''
    **/
   function getPrinterMac() {
      $ports = $this->sxml->CONTENT->DEVICE->PORTS;
      foreach ($ports->children() as $portName=>$portChild) {
         switch ($portName) {
            case 'PORT' :
               foreach ($portChild->children() as $macName=>$macChild) {
                  switch ($macName) {
                     case 'MAC' :
                        if ($macChild != '') return $macChild;
                  }
               }
         }
      }
      return '';
   }

   function importToken($p_xml) {
      $this->setXML($p_xml);

      if ((isset($this->sxml->DEVICEID)) AND (isset($this->sxml->TOKEN))) {
         $pta = new PluginFusioninventoryAgents;
         $a_agent = $pta->find("`key`='".$this->sxml->DEVICEID."'", "", "1");
         if (empty($a_agent)) {
            $a_input = array();
            $a_input['token'] = $this->sxml->TOKEN;
            $a_input['name'] = $this->sxml->DEVICEID;
            $a_input['key'] = $this->sxml->DEVICEID;
            $pta->add($a_input);
            return 2;
         } else {
            foreach ($a_agent as $id_agent=>$dataInfos) {
               $input = array();
               $input['ID'] = $id_agent;
               $input['token'] = $this->sxml->TOKEN;
               $pta->update($input);
            }
         }
      }
      return 1;
   }

   function sendInventoryToOcsServer($p_xml) {
      global $DB;

      $this->addLog('Function sendInventoryToOcsServer().');
      $ptais = new PluginFusioninventoryAgentsInventoryState;
      
      $this->setXML($p_xml);

      $query = "SELECT *
		FROM glpi_ocs_link
		WHERE ocs_deviceid='".$this->sxml->DEVICEID."'";
      $result = $DB->query($query);
      if ($DB->numrows($result) == 1) {
         $line = $DB->fetch_assoc($result);
         $ptais->changeStatus($line['glpi_id'], 3);
      }        
      
      $port = "80";
      $url = "http://127.0.0.1/ocsinventory";
      $url = preg_replace("@^http://@i", "", $url);
      $host = substr($url, 0, strpos($url, "/"));
      $uri = strstr($url, "/");
      $reqbody = gzcompress($p_xml);

      $contentlength = strlen($reqbody);
      $reqheader =  "POST $uri HTTP/1.1\r\n".
      "Host: $host\n". "User-Agent: OCS_local_5013\r\n".
      "Content-type: application/x-compress\r\n".
      "Content-Length: $contentlength\r\n\r\n".
      "$reqbody\r\n";

      $socket = @fsockopen($host, $port, $errno, $errstr);

      if (!$socket) {
         $result["errno"] = $errno;
         $result["errstr"] = $errstr;
         return $result;
      }
      if (isset($line['glpi_id']))
         $ptais->changeStatus($line['glpi_id'], 4);
      fputs($socket, $reqheader);

      while (!feof($socket)) {
         $result[] = fgets($socket, 4096);
      }

      fclose($socket);
      $this->synchroOCS($p_xml);
   }

   function synchroOCS($p_xml) {
      global $DB;

      $ptais = new PluginFusioninventoryAgentsInventoryState;
      
      $this->setXML($p_xml);

      $query = "SELECT *
		FROM glpi_ocs_link
		WHERE ocs_deviceid='".$this->sxml->DEVICEID."'";
      $result = $DB->query($query);
      if ($DB->numrows($result) == 1) {
         $line = $DB->fetch_assoc($result);
         $ptais->changeStatus($line['glpi_id'], 5);
         OcsServer::updateComputer($line['ID'], $line['ocs_server_id'], 1);
         $ptais->changeStatus($line['glpi_id'], 6);
      }
   }

   function addInventory() {
      $ptc  = new PluginFusioninventoryConfig;
      $ptap = new PluginFusioninventoryAgentsProcesses;
//      if ($_SESSION['glpi_plugin_fusioninventory_addagentprocess'] == '0') {
//         $this->addProcessNumber($ptap->addProcess($pxml));
//         $_SESSION['glpi_plugin_fusioninventory_addagentprocess'] = '1';
//      }
      $this->sxml->addAttribute('RESPONSE', "SEND");
      $this->sxml->addAttribute('PROLOG_FREQ', $ptc->getValue('inventory_frequence'));
   }


   function addWakeonlan($pxml) {
      $pta = new PluginFusioninventoryAgents;
      $ptt = new PluginFusioninventoryTask;
      $np  = new Networkport;

      $agent = $pta->InfosByKey($pxml->DEVICEID);

      $sxml_option = $this->sxml->addChild('OPTION');
         $sxml_option->addChild('NAME', 'WAKEONLAN');

      $tasks = $ptt->ListTask($agent["ID"], "WAKEONLAN");
         foreach ($tasks as $task_id=>$taskInfos) {
            if ($taskInfos['itemtype'] == COMPUTER_TYPE) {
               $a_portsList = $np->find('on_device='.$taskInfos['on_device'].' AND itemtype="'.COMPUTER_TYPE.'"');
               foreach ($a_portsList as $ID=>$data) {
                  if ($data['ifaddr'] != "127.0.0.1") {
                     $sxml_param = $sxml_option->addChild('PARAM');
                     $sxml_param->addAttribute('MAC', $data['ifmac']);
                     $sxml_param->addAttribute('IP', $data['ifaddr']);
                  }
               }
            }
         }
   }


   function noSSL() {
      $this->sxml->addAttribute('RESPONSE', "ERROR : SSL REQUIRED BY SERVER");
      $this->setXML($this->getXML());
      echo $this->getSend();
   }

   /**
    * Add logs
    *
    *@param $p_logs logs to write
    *@return nothing (write text in log file)
    **/
   function addLog($p_logs) {
//      file_put_contents($this->logFile, "\n".time().' : '.$p_logs, FILE_APPEND);
   }
}

?>