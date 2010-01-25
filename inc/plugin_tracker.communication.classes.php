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
class PluginTrackerCommunication {
   private $sxml, $deviceId, $ptd, $type='';

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
      if ($errors=='') {
         $result=true;
      } else {
         $result=false;
      }
      return $result;
   }

   /**
    * Add SNMPQUERY string to XML code
    *
    *@return nothing
    **/
   function addQuery($pxml) {
      $ptmi    = new PluginTrackerModelInfos;
      $ptsnmpa = new PluginTrackerSNMPAuth;
      $pta     = new PluginTrackerAgents;
      $ptap    = new PluginTrackerAgentsProcesses;
      $ptrip   = new PluginTrackerRangeIP;
      $ptt     = new PluginTrackerTask;

      $agent = $pta->InfosByKey($pxml->DEVICEID);
      $count_range = $ptrip->Counter($agent["ID"], "query");
      $count_range += $ptt->Counter($agent["ID"], "SNMPQUERY");

      if (($count_range > 0) && ($agent["lock"] == 0)) {
         $a_input['query_core'] = $agent["core_query"];
         $a_input['query_threads'] = $agent["threads_query"];
         $ptap->updateProcess($this->sxml->PROCESSNUMBER, $a_input);

         $sxml_option = $this->sxml->addChild('OPTION');
            $sxml_option->addChild('NAME', 'SNMPQUERY');
            $sxml_param = $sxml_option->addChild('PARAM');
               $sxml_param->addAttribute('CORE_QUERY', $agent["core_query"]);
               $sxml_param->addAttribute('THREADS_QUERY', $agent["threads_query"]);
               $sxml_param->addAttribute('PID', $this->sxml->PROCESSNUMBER);
               $sxml_param->addAttribute('LOGS', $agent["logs"]);

               $ranges = $ptrip->ListRange($agent["ID"], "query");
               foreach ($ranges as $range_id=>$rangeInfos) {
                  $this->addDevice($sxml_option, 'networking', $ranges[$range_id]["ifaddr_start"],
                              $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["FK_entities"]);
                  $this->addDevice($sxml_option, 'printer', $ranges[$range_id]["ifaddr_start"],
                              $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["FK_entities"]);
               }

            $snmpauthlist=$ptsnmpa->find();
            if (count($snmpauthlist)){
               foreach ($snmpauthlist as $snmpauth){
                  $this->addAuth($sxml_option, $snmpauth['ID']);
               }
            }

            $modelslist=$ptmi->find();
            $db_plugins=array();
            if (count($modelslist)){
               foreach ($modelslist as $model){
                  $this->addModel($sxml_option, $model['ID']);
               }
            }
      }
   }

   /**
    * Add NETDISCOVERY string to XML code
    *
    *@return nothing
    **/
   function addDiscovery($pxml) {
      $ptsnmpa = new PluginTrackerSNMPAuth;
      $pta     = new PluginTrackerAgents;
      $ptap    = new PluginTrackerAgentsProcesses;
      $ptrip   = new PluginTrackerRangeIP;
      $ptt     = new PluginTrackerTask;

      $agent = $pta->InfosByKey($pxml->DEVICEID);
      $count_range = $ptrip->Counter($agent["ID"], "discover");
      $count_range += $ptt->Counter($agent["ID"], "NETDISCOVERY");

      if (($count_range > 0) && ($agent["lock"] == 0)) {
         $a_input['discovery_core'] = $agent["core_discovery"];
         $a_input['discovery_threads'] = $agent["threads_discovery"];
         $ptap->updateProcess($this->sxml->PROCESSNUMBER, $a_input);

         $sxml_option = $this->sxml->addChild('OPTION');
            $sxml_option->addChild('NAME', 'NETDISCOVERY');
            $sxml_param = $sxml_option->addChild('PARAM');
               $sxml_param->addAttribute('CORE_DISCOVERY', $agent["core_discovery"]);
               $sxml_param->addAttribute('THREADS_DISCOVERY', $agent["threads_discovery"]);
               $sxml_param->addAttribute('PID', $this->sxml->PROCESSNUMBER);
               $sxml_param->addAttribute('LOGS', $agent["logs"]);

            $ranges = $ptrip->ListRange($agent["ID"], "discover");
            foreach ($ranges as $range_id=>$rangeInfos) {
               $sxml_rangeip = $sxml_option->addChild('RANGEIP');
                  $sxml_rangeip->addAttribute('ID', $range_id);
                  $sxml_rangeip->addAttribute('IPSTART', $ranges[$range_id]["ifaddr_start"]);
                  $sxml_rangeip->addAttribute('IPEND', $ranges[$range_id]["ifaddr_end"]);
                  $sxml_rangeip->addAttribute('ENTITY', $ranges[$range_id]["FK_entities"]);
            }
            
            $tasks = $ptt->ListTask($agent["ID"], "NETDISCOVERY");
            foreach ($tasks as $task_id=>$taskInfos) {
               $sxml_rangeip = $sxml_option->addChild('RANGEIP');
                  $sxml_rangeip->addAttribute('ID', $task_id);
                  $sxml_rangeip->addAttribute('IPSTART', $tasks[$task_id]["ifaddr"]);
                  $sxml_rangeip->addAttribute('IPEND', $tasks[$task_id]["ifaddr"]);
                  $sxml_rangeip->addAttribute('ENTITY', "");
                  $sxml_rangeip->addAttribute('DEVICEID', $tasks[$task_id]["on_device"]);
                  $sxml_rangeip->addAttribute('TYPE', $tasks[$task_id]["device_type"]);
            }
            
            $snmpauthlist=$ptsnmpa->find();
            if (count($snmpauthlist)){
               foreach ($snmpauthlist as $snmpauth){
                  $this->addAuth($sxml_option, $snmpauth['ID']);
               }
            }
         $this->sxml->addChild('RESPONSE', 'SEND');
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
      $ptsnmpa = new PluginTrackerSNMPAuth;
      $ptsnmpa->getFromDB($p_id);

      $sxml_authentication = $p_sxml_node->addChild('AUTHENTICATION');
         $sxml_authentication->addAttribute('ID', $p_id);
         $sxml_authentication->addAttribute('COMMUNITY', $ptsnmpa->fields['community']);
         $sxml_authentication->addAttribute('VERSION',
                            getDropdownName('glpi_dropdown_plugin_tracker_snmp_version',
                                            $ptsnmpa->fields['FK_snmp_version']));
         $sxml_authentication->addAttribute('USERNAME', $ptsnmpa->fields['sec_name']);
         if ($ptsnmpa->fields['auth_protocol'] == '0') {
            $sxml_authentication->addAttribute('AUTHPROTOCOL', '');
         } else {
            $sxml_authentication->addAttribute('AUTHPROTOCOL',
                            getDropdownName('glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol',
                                            $ptsnmpa->fields['auth_protocol']));
         }
         $sxml_authentication->addAttribute('AUTHPASSPHRASE', $ptsnmpa->fields['auth_passphrase']);
         if ($ptsnmpa->fields['priv_protocol'] == '0') {
            $sxml_authentication->addAttribute('PRIVPROTOCOL', '');
         } else {
            $sxml_authentication->addAttribute('PRIVPROTOCOL',
                    getDropdownName('glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol',
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
      $models = new PluginTrackerModelInfos;
      $mib_networking = new PluginTrackerMibNetworking;

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
   function addDevice($p_sxml_node, $p_type, $p_ipstart, $p_ipend, $p_entity) {
      global $DB;

      $type='';
      switch ($p_type) {
         
         case "networking":
            $type='NETWORKING';
            $query = "SELECT `glpi_networking`.`ID` AS `gID`,
                             `glpi_networking`.`ifaddr` AS `gnifaddr`,
                             `FK_snmp_connection`, `FK_model_infos`
                      FROM `glpi_networking`
                           LEFT JOIN `glpi_plugin_tracker_networking`
                           ON `FK_networking`=`glpi_networking`.`ID`
                      WHERE `deleted`='0'
                           AND `FK_model_infos`!='0'
                           AND `FK_snmp_connection`!='0'
                           AND `FK_entities`='".$p_entity."'
                           AND inet_aton(`ifaddr`)
                               BETWEEN inet_aton('".$p_ipstart."')
                               AND inet_aton('".$p_ipend."') ";
            break;
         
         case "printer":
            $type='PRINTER';
            $query = "SELECT `glpi_printers`.`ID` AS `gID`,
                             `glpi_networking_ports`.`ifaddr` AS `gnifaddr`,
                             `FK_snmp_connection`, `FK_model_infos`
                      FROM `glpi_printers`
                           LEFT JOIN `glpi_plugin_tracker_printers`
                              ON `FK_printers`=`glpi_printers`.`ID`
                           LEFT JOIN `glpi_networking_ports`
                              ON `on_device`=`glpi_printers`.`ID`
                                 AND `device_type`='".PRINTER_TYPE."'
                      WHERE `deleted`=0
                            AND `FK_model_infos`!='0'
                            AND `FK_snmp_connection`!='0'
                            AND `FK_entities`='".$p_entity."'
                            AND inet_aton(`ifaddr`)
                                BETWEEN inet_aton('".$p_ipstart."')
                                AND inet_aton('".$p_ipend."') ";
            break;
         
         default: // type non géré
            return false;
      }

      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $this->addInfo($p_sxml_node, 
                        $data['gID'],
                        $data['gnifaddr'],
                        $data['FK_snmp_connection'],
                        $data['FK_model_infos'],
                        $type);         
      }
      return true;
   }

   /**
    * Import data
    *
    *@param $p_xml XML code to import
    *@param &$p_errors errors string to be alimented if import ko
    *@return true (import ok) / false (import ko)
    **/
   function import($p_xml, &$p_errors='') {
      // TODO : gérer l'encodage, la version
      // Do not manage <REQUEST> element (always the same)
      $this->setXML($p_xml);
      $errors = '';

      switch ($this->sxml->QUERY) {
         case 'SNMPQUERY' :
            $errors.=$this->importContent($this->sxml->CONTENT);
            break;
         
         case 'NETDISCOVERY' :
            $pti = new PluginTrackerImportExport;
            $errors.=$pti->import_netdiscovery($this->sxml->CONTENT, $this->sxml->DEVICEID);
            break;

         default :
            $errors.=$LANG['plugin_tracker']["errors"][22].' QUERY : *'.$this->sxml->QUERY."*\n";
      }
      if ($errors=='') {
         $result=true;
      } else {
         $result=false;
         $p_errors=$errors;
         if (isset($_SESSION['glpi_plugin_tracker_processnumber'])) {
            $ptap = new PluginTrackerAgentsProcesses;
            $ptap->updateProcess($_SESSION['glpi_plugin_tracker_processnumber'],
                                 array('comments' => $errors));
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

      $ptap = new PluginTrackerAgentsProcesses;
      $pta  = new PluginTrackerAgents;
      
      $errors='';
      $nbDevices = 0;
      $_SESSION['glpi_plugin_tracker_processnumber'] = $this->sxml->CONTENT->PROCESSNUMBER;

      foreach ($p_content->children() as $child) {
         switch ($child->getName()) {
            case 'DEVICE' :
               $errors.=$this->importDevice($child);
               $nbDevices++;
               break;

            case 'AGENT' :
               if (isset($this->sxml->CONTENT->AGENT->START)) {
                  $ptap->updateProcess($this->sxml->CONTENT->PROCESSNUMBER,
                                       array('start_time_query' => date("Y-m-d H:i:s")));
               } else if (isset($this->sxml->CONTENT->AGENT->END)) {
                  $ptap->updateProcess($this->sxml->CONTENT->PROCESSNUMBER,
                                       array('end_time_query' => date("Y-m-d H:i:s")));
               } else if (isset($this->sxml->CONTENT->AGENT->EXIT)) {
                  $ptap->endProcess($this->sxml->CONTENT->PROCESSNUMBER, date("Y-m-d H:i:s"));
               }
               if (isset($this->sxml->CONTENT->AGENT->AGENTVERSION)) {
                  $agent = $pta->InfosByKey($this->sxml->DEVICEID);
                  $agent['tracker_agent_version'] = $this->sxml->CONTENT->AGENT->AGENTVERSION;
                  $agent['last_agent_update'] = date("Y-m-d H:i:s");
                  $p_xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
                  $pta->update($agent);
               }
               break;
            
            case 'PROCESSNUMBER':
               break;
            
            default :
               $errors.=$LANG['plugin_tracker']["errors"][22].' CONTENT : '.$child->getName()."\n";
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

      $ptap = new PluginTrackerAgentsProcesses;
      $ptae = new PluginTrackerAgentsErrors;

      $errors=''; $this->deviceId='';
      switch ($p_device->INFO->TYPE) {
         case 'PRINTER':
            $this->type = PRINTER_TYPE;
            break;
         case 'NETWORKING':
            $this->type = NETWORKING_TYPE;
            break;
         default:
            $errors.=$LANG['plugin_tracker']["errors"][22].' TYPE : '
                              .$p_device->INFO->TYPE."\n";
      }
      if (isset($p_device->ERROR)) {
         $ptap->updateProcess($_SESSION['glpi_plugin_tracker_processnumber'],
                              array('query_nb_error' => '1'));
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
         $errors.=$this->importInfo($p_device->INFO);
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
                     $errors.=$LANG['plugin_tracker']["errors"][22].' DEVICE : '
                              .$child->getName()."\n";
               }
            }
            if ($errors=='') {
               $this->ptd->updateDB();
            }
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
   function importInfo($p_info) {
      global $LANG;

      $errors='';
      $criteria['serial']  = $p_info->SERIAL;
      $criteria['name']    = $p_info->NAME;
      $criteria['macaddr'] = $p_info->MAC; //TODO get mac in PORT for printer
      if ($p_info->TYPE=='NETWORKING') {
         $this->deviceId = plugin_tracker_discovery_criteria($criteria, NETWORKING_TYPE);
         if ($this->deviceId != '') {
            $errors.=$this->importInfoNetworking($p_info);
         } else {
            $errors.=$LANG['plugin_tracker']["errors"][23].'
                     type : "'.$p_info->TYPE.'"
                     ID : "'.$p_info->ID.'"
                     serial : "'.$p_info->SERIAL.'"
                     name : "'.$p_info->NAME.'"
                     macaddress : "'.$p_info->MAC.'"'."\n";
         }
      } elseif ($p_info->TYPE=='PRINTER') {
         //TODO
         $this->deviceId = plugin_tracker_discovery_criteria($criteria, PRINTER_TYPE);
         if ($this->deviceId != '') {
            $errors.=$this->importInfoPrinter($p_info);
         } else {
            $errors.=$LANG['plugin_tracker']["errors"][23].'
                     type : "'.$p_info->TYPE.'"
                     ID : "'.$p_info->ID.'"
                     serial : "'.$p_info->SERIAL.'"
                     name : "'.$p_info->NAME.'"
                     macaddress : "'.$p_info->MAC.'"'."\n";
         }
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
      $this->ptd = new PluginTrackerNetworking2;
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
               $errors.=$LANG['plugin_tracker']["errors"][22].' INFO : '.$child->getName()."\n";
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
      $this->ptd = new PluginTrackerPrinter;
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
               $errors.=$LANG['plugin_tracker']["errors"][22].' INFO : '.$child->getName()."\n";
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
      $pti = new PluginTrackerIfaddr;
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
               $errors.=$LANG['plugin_tracker']["errors"][22].' IPS : '.$child->getName()."\n";
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
               $errors.=$LANG['plugin_tracker']["errors"][22].' PORTS : '.$child->getName()."\n";
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

      $errors='';
      $ptp = new PluginTrackerPort(NETWORKING_TYPE);
      $ifType = $p_port->IFTYPE;
      if ( (strstr($ifType, "ethernetCsmacd"))
            OR ($ifType == "6")
            OR ($ifType == "ethernet-csmacd(6)")
            OR (strstr($ifType, "iso88023Csmacd"))
            OR ($ifType == "7")) { // not virtual port
         $portIndex = $this->ptd->getPortIndex($p_port->MAC, $this->getConnectionIP($p_port));
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
                  plugin_tracker_networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('name', $child);
                  break;
               case 'MAC' :
                  plugin_tracker_networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('ifmac', $child);
                  break;
               case 'IFNUMBER' :
                  plugin_tracker_networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('logical_number', $child);
                  break;
               case 'IFTYPE' : // already managed
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
               case 'TRUNK' :
                  plugin_tracker_networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue(strtolower($name), $p_port->$name);
                  break;
               default :
                  $errors.=$LANG['plugin_tracker']["errors"][22].' PORT : '.$name."\n";
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
      $ptp = new PluginTrackerPort(PRINTER_TYPE);
      $ifType = $p_port->IFTYPE;
      if ( substr($p_port->IP, 0, 4) != '127') {
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
                  plugin_tracker_networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('name', $child);
                  break;
               case 'MAC' :
                  plugin_tracker_networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('ifmac', $child);
                  break;
               case 'IP' :
                  plugin_tracker_networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('ifaddr', $child);
                  break;
               case 'IFNUMBER' :
                  plugin_tracker_networking_ports_addLog($ptp->getValue('ID'), $child, strtolower($name));
                  $ptp->setValue('logical_number', $child);
                  break;
               case 'IFTYPE' : // already managed
                  break;
               default :
                  $errors.=$LANG['plugin_tracker']["errors"][22].' PORT : '.$name."\n";
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
            case 'BLACK' :
            case 'BLACKPHOTO' :
            case 'CYAN' :
            case 'CYANLIGHT' :
            case 'YELLOW' :
            case 'MAGENTA' :
            case 'MAGENTALIGHT' :
            case 'PHOTOCONDUCTOR' :
            case 'PHOTOCONDUCTORBLACK' :
            case 'PHOTOCONDUCTORCOLOR' :
            case 'PHOTOCONDUCTORCYAN' :
            case 'PHOTOCONDUCTORYELLOW' :
            case 'PHOTOCONDUCTORMAGENTA' :
            case 'UNITTRANSFERBLACK' :
            case 'UNITTRANSFERCYAN' :
            case 'UNITTRANSFERYELLOW' :
            case 'UNITTRANSFERMAGENTA' :
            case 'WASTE' :
            case 'FUSER' :
            case 'BELTCLEANER' :
            case 'MAINTENANCEKIT' :
               $ptc = new PluginTrackerCommonDBTM("glpi_plugin_tracker_printers_cartridges");
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
               $errors.=$LANG['plugin_tracker']["errors"][22].' CARTRIDGES : '.$name()."\n";
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
               $errors.=$LANG['plugin_tracker']["errors"][22].' PAGECOUNTERS : '.$name."\n";
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

      $errors='';
      if (isset($p_connections->CDP)) {
         $cdp = $p_connections->CDP;
         if ($cdp==1) {
            $p_oPort->setCDP();
         } else {
            $errors.=$LANG['plugin_tracker']["errors"][22].' CONNECTIONS : CDP='.$cdp."\n";
         }
      } else {
         $cdp=0;
      }
      foreach ($p_connections->children() as $name=>$child) {
         switch ($child->getName()) {
            case 'CDP' : // already managed
               break;
            case 'CONNECTION' :
               $errors.=$this->importConnection($child, $p_oPort, $cdp);
               break;
            default :
               $errors.=$LANG['plugin_tracker']["errors"][22].' CONNECTIONS : '
                        .$child->getName()."\n";
         }
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

      $errors='';
      $portID=''; $mac=''; $ip='';
      $ptsnmp= new PluginTrackerSNMP;
      if ($p_cdp==1) {
         $ifdescr='';
         foreach ($p_connection->children() as $name=>$child) {
            switch ($child->getName()) {
               case 'IP' :
                  $ip=$child;
                  break;
               case 'IFDESCR' :
                  $ifdescr=$child;
                  break;
               default :
                  $errors.=$LANG['plugin_tracker']["errors"][22].' CONNECTION (CDP='.$p_cdp.') : '
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
                  break;
               case 'IP' ://TODO : si ip ajouter une tache de decouverte sur l'ip pour recup autre info // utile seulement si mac inconnu dans glpi
                  $ip=$child;
                  break;
               default :
                  $errors.=$LANG['plugin_tracker']["errors"][22].' CONNECTION (CDP='.$p_cdp.') : '
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
               $errors.=$LANG['plugin_tracker']["errors"][22].' VLANS : '.$child->getName()."\n";
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
               $errors.=$LANG['plugin_tracker']["errors"][22].' VLAN : '.$child->getName()."\n";
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
                                 $ip=$ipChild;
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
                        $mac=$macChild;
                        if ($macChild != '') return $macChild;
                  }
               }
         }
      }
      return '';
   }
}
?>
