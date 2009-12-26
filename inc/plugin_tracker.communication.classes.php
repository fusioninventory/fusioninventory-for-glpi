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
   private $sxml, $deviceId, $ptn;

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
   function addQuery() {
      $ptmi = new PluginTrackerModelInfos;

      $sxml_option = $this->sxml->addChild('OPTION');
         $sxml_option->addChild('NAME', 'SNMPQUERY');
         $sxml_param = $sxml_option->addChild('PARAM');
            $sxml_param->addAttribute('CORE_QUERY', '1');
            $sxml_param->addAttribute('THREADS_QUERY', '5');
            $sxml_param->addAttribute('PID', '03201054001');
            $sxml_param->addAttribute('LOGS', '2');
         $this->addDevice($sxml_option, 'networking');
         //$this->addDevice($sxml_option, 'printer');
         $this->addAuth($sxml_option, 2, 'public', '2c');
         $this->addAuth($sxml_option, 1, 'public', '1');

         $modelslist=$ptmi->find();
         $db_plugins=array();
         if (count($modelslist)){
            foreach ($modelslist as $model){
               $this->addModel($sxml_option, $model['ID']);
            }
         }
   }

   /**
    * Add NETDISCOVERY string to XML code
    *
    *@return nothing
    **/
   function addDiscovery() {
      $p_xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
      $pxml = @simplexml_load_string($p_xml);

      $pta = new PluginTrackerAgents;
      $ptrip = new PluginTrackerRangeIP;
      $ptt =  new PluginTrackerTask;

      $agent = $pta->InfosByKey($pxml->DEVICEID);
      $count_range = $ptrip->Counter($agent["ID"], "discover");
      $count_range += $ptt->Counter($agent["ID"], "NETDISCOVERY");

      if (($count_range > 0) && ($agent["lock"] == 0)) {
         $sxml_option = $this->sxml->addChild('OPTION');
            $sxml_option->addChild('NAME', 'NETDISCOVERY');
            $sxml_param = $sxml_option->addChild('PARAM');
               $sxml_param->addAttribute('CORE_DISCOVERY', $agent["core_discovery"]);
               $sxml_param->addAttribute('THREADS_DISCOVERY', $agent["threads_discovery"]);
               $sxml_param->addAttribute('PID', '03201054001');
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
            
            $this->addAuth($sxml_option, 2, 'public', '2c');
            $this->addAuth($sxml_option, 1, 'public', '1');
         $this->sxml->addChild('RESPONSE', 'SEND');
      }
   }

   /**
    * Add AUTHENTICATION string to XML node
    *
    *@param $p_sxml_node XML node to authenticate
    *@param $p_id Authenticate id
    *@param $p_community Value of COMMUNITY attribute
    *@param $p_version SNMP version
    *@param $p_sec_name='' Value of SEC_NAME attribute
    *@param $p_sec_level='' Value of SEC_LEVEL attribute
    *@param $p_auth_prot='' Value of AUTH_PROTOCOLE attribute
    *@param $p_auth_pass='' Value of AUTH_PASSPHRASE attribute
    *@param $p_priv_prot='' Value of PRIV_PROTOCOLE attribute
    *@param $p_priv_pass='' Value of PRIV_PASSPHRASE attribute
    *@return nothing
    **/
   function addAuth($p_sxml_node, $p_id, $p_community, $p_version, $p_sec_name='', $p_sec_level='',
                    $p_auth_prot='', $p_auth_pass='', $p_priv_prot='', $p_priv_pass='') {
   /*
    * table snmp_connections -->
    * 1. modifier addAuth() pour lui passer la réf de l'emplacment de la base ou elle va trouver les donnees d'authentification
    * 2. ne pas modifier addAuth() et insérer avant une fonction getAuth() qui l'alimentera avec les bonnes donnees
    */
      $sxml_authentication = $p_sxml_node->addChild('AUTHENTICATION');
         $sxml_authentication->addAttribute('ID', $p_id);
         $sxml_authentication->addAttribute('COMMUNITY', $p_community);
         $sxml_authentication->addAttribute('VERSION', $p_version);
         $sxml_authentication->addAttribute('SEC_NAME', $p_sec_name);
         $sxml_authentication->addAttribute('SEC_LEVEL', $p_sec_level);
         $sxml_authentication->addAttribute('AUTH_PROTOCOLE', $p_auth_prot);
         $sxml_authentication->addAttribute('AUTH_PASSPHRASE', $p_auth_pass);
         $sxml_authentication->addAttribute('PRIV_PROTOCOLE', $p_priv_prot);
         $sxml_authentication->addAttribute('PRIV_PASSPHRASE', $p_priv_pass);
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
    *@return true (device added) / false (unknown type of device)
    **/
   function addDevice($p_sxml_node, $p_type) {
      global $DB;
// ne pas renvoyer toutes les données d'authentification:
// seulement $sxml_authentication->addAttribute('ID', $p_id);
      $type='';
      switch ($p_type) {
         case "networking":
            $type='NETWORKING';
            break;
         case "printer":
            $type='PRINTER';
            break;
         default: // type non géré
            return false;
      }
//      $sxml_device = $p_sxml_node->addChild('DEVICE');
//         $sxml_device->addAttribute('TYPE', $type);
      $query = "SELECT glpi_networking.ID AS gnID, glpi_networking.ifaddr AS gnifaddr, FK_snmp_connection, FK_model_infos FROM glpi_networking
         LEFT JOIN glpi_plugin_tracker_networking on FK_networking=glpi_networking.ID
         WHERE FK_model_infos!=0
            AND FK_snmp_connection!=0
            AND glpi_networking.ID='3' ";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $this->addInfo($p_sxml_node, 
                        $data['gnID'],
                        $data['gnifaddr'],
                        $data['FK_snmp_connection'],
                        $data['FK_model_infos'],
                        $type);         
      }



//      $this->addInfo($p_sxml_node, '3', '192.168.0.80', '2', '4', $type);


//      $this->addInfo($p_sxml_node, '8', '192.168.0.81', '2', '4', $type);
//      $this->addInfo($p_sxml_node, '9', '192.168.0.80', '2', '4', $type);
//      $this->addInfo($p_sxml_node, '10', '192.168.0.80', '2', '4', $type);
//      $this->addInfo($p_sxml_node, '11', '192.168.0.80', '2', '4', $type);
//      $this->addInfo($p_sxml_node, '12', '192.168.0.80', '2', '4', $type);
//      $this->addInfo($p_sxml_node, '13', '192.168.0.80', '2', '4', $type);
//      $this->addInfo($p_sxml_node, '14', '192.168.0.80', '2', '4', $type);

// Doum : 3com     $this->addInfo($p_sxml_node, '15', '172.25.22.103', '1', '1', $type);
//      $this->addInfo($p_sxml_node, '9', '192.168.0.201', '2', '1', $type);
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
      // pas gérer le REQUEST (tjs pareil)
      $this->setXML($p_xml);
      $errors = '';

      switch ($this->sxml->QUERY) {
         case 'SNMPQUERY' :
            $errors.=$this->importContent($this->sxml->CONTENT);
            break;
         case 'NETDISCOVERY' :
            $pti = new PluginTrackerImportExport;
            $errors.=$pti->import_netdiscovery($this->sxml->CONTENT);
         default :
            $errors.='QUERY invalide : '.$this->sxml->QUERY."\n";
      }

      if ($errors=='') {
         $result=true;
      } else {
         $result=false;
         $p_errors=$errors;
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
      $errors='';
      foreach ($p_content->children() as $child)
      {
         switch ($child->getName()) {
            case 'DEVICE' :
               $errors.=$this->importDevice($child);
               break;
            default :
               $errors.='Elément invalide dans CONTENT : '.$child->getName()."\n";
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
      $errors=''; $this->deviceId='';
      $errors.=$this->importInfo($p_device->INFO);
      if ($this->deviceId!='') {
         foreach ($p_device->children() as $child)
         {
            switch ($child->getName()) {
               case 'INFO' : // already managed
                  break;
               case 'PORT' :
                  $errors.=$this->importPort($child);
                  break;
               default :
                  $errors.='Elément invalide dans DEVICE : '.$child->getName()."\n";
            }
         }
         if (is_object($this->ptn)) {
            $this->ptn->savePorts();
         }
      }

      return $errors;
   }

   /**
    * Import INFO
    *@param $p_info INFO code to import
    *
    *@return nothing
    //@//return errors string to be alimented if import ko / '' if ok
    **/
   function importInfo($p_info) {
      $errors='';
      if (isset($p_info->TYPE) AND isset($p_info->ID)) {
         $this->deviceId = $p_info->ID;
         if ($p_info->TYPE=='NETWORKING') {
            $errors.=$this->importNetworking($p_info);
         } elseif ($p_info->TYPE=='PRINTER') {
            //todo
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
   function importNetworking($p_info) {
      $errors='';
      $this->ptn = new PluginTrackerNetworking2;
      $this->ptn->load($p_info->ID);

      foreach ($p_info->children() as $child)
      {
         switch ($child->getName()) {
            case 'ID' : // already managed
               break;
            case 'TYPE' : // already managed
               break;
            case 'COMMENTS' :
               $this->ptn->setValue('comments', $p_info->COMMENTS);
               break;
            case 'CPU' :
               $this->ptn->setValue('cpu', $p_info->CPU);
               break;
            case 'FIRMWARE' :
               $this->ptn->setValue('firmware', $p_info->FIRMWARE);
               break;
            case 'MAC' :
               $this->ptn->setValue('ifmac', $p_info->MAC);
               break;
            case 'MEMORY' :
               $this->ptn->setValue('memory', $p_info->MEMORY);
               break;
            case 'MODEL' :
               $this->ptn->setValue('model', $p_info->MODEL);
               break;
            case 'NAME' :
               $this->ptn->setValue('name', $p_info->NAME);
               break;
            case 'RAM' :
               $this->ptn->setValue('ram', $p_info->RAM);
               break;
            case 'SERIAL' :
               $this->ptn->setValue('serial', $p_info->SERIAL);
               break;
            case 'UPTIME' :
               $this->ptn->setValue('uptime', $p_info->UPTIME);
               break;
            case 'IPS' :
               $errors.=$this->importIps($child);
               break;
            default :
               $errors.='Elément invalide dans INFO : '.$child->getName()."\n";
         }
      }
      if ($errors=='') {
         $this->ptn->updateDB();
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
      $errors='';
      $pti = new PluginTrackerIfaddr;
      foreach ($p_ips->children() as $name=>$child)
      {
         switch ($child->getName()) {
            case 'IP' :
               $ifaddrIndex = $this->ptn->getIfaddrIndex($child);
               if (is_int($ifaddrIndex)) {
                  $oldIfaddr = $this->ptn->getIfaddr($ifaddrIndex);
                  $pti->load($oldIfaddr->getValue('ID'));
               } else {
                  $pti->load();
               }
               $pti->setValue('ifaddr', $child);
               $this->ptn->addIfaddr(clone $pti, $ifaddrIndex);
               break;
            default :
               $errors.='Elément invalide dans IPS : '.$child->getName()."\n";
         }
      }
      $this->ptn->saveIfaddrs();
      return $errors;
   }

   /**
    * Import PORT
    *@param $p_info PORT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPort($p_port) {
      $errors='';
      $ptp = new PluginTrackerPort;
      $portIndex = $this->ptn->getPortIndex($p_port->MAC); //todo ajouter le 2e param (ip) a partir de connections
      if (is_int($portIndex)) {
         $oldPort = $this->ptn->getPort($portIndex);
         $ptp->load($oldPort->getValue('ID'));
      } else {
         $ptp->load();
      }
      foreach ($p_port->children() as $name=>$child)
      {
         switch ($name) {
            case 'CONNECTIONS' :
               $errors.=$this->importConnections($child, $ptp);
               break;
            case 'VLANS' : // todo
               break;
            case 'IFNAME' :
               $ptp->setValue('name', $child);
               break;
            case 'MAC' :
               $ptp->setValue('ifmac', $child);
               break;
            case 'IFNUMBER' :
               $ptp->setValue('logical_number', $child);
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
            case 'IFTYPE' :
            case 'TRUNK' :
               $ptp->setValue(strtolower($name), eval("return \$p_port->$name;")); //todo supprimer le eval ?
               break;
            default :
               $errors.='Elément invalide dans PORT : '.$name."\n";
         }
      }
      $this->ptn->addPort($ptp, $portIndex);
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
      $errors='';
      if (isset($p_connections->CDP)) {
         $cdp = $p_connections->CDP;
         if ($cdp!=1) {
            $errors.='Elément invalide dans CONNECTIONS : CDP='.$cdp."\n";
         }
      } else {
         $cdp=0;
      }
      foreach ($p_connections->children() as $name=>$child)
      {
         switch ($child->getName()) {
            case 'CDP' : // already managed
               break;
            case 'CONNECTION' :
               $errors.=$this->importConnection($child, $p_oPort, $cdp);
               break;
            default :
               $errors.='Elément invalide dans CONNECTIONS : '.$child->getName()."\n";
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
      $errors='';
      $portID='';
      $ptsnmp= new PluginTrackerSNMP;
      if ($p_cdp==1) {
         $ip=''; $ifdescr='';
         foreach ($p_connection->children() as $name=>$child) {
            switch ($child->getName()) {
               case 'IP' :
                  $ip=$child;
                  break;
               case 'IFDESCR' :
                  $ifdescr=$child;
                  break;
               default :
                  $errors.='Elément invalide dans CONNECTION (CDP='.$p_cdp.') : '.$child->getName()."\n";
            }
         }
         $portID=$ptsnmp->getPortIDfromDeviceIP($ip, $ifdescr);
      } else {
         foreach ($p_connection->children() as $name=>$child) {
            switch ($child->getName()) {
               case 'MAC' :
                  $portID=$ptsnmp->getPortIDfromDeviceMAC($child, $p_oPort->getValue('ID'));
                  break;
               default :
                  $errors.='Elément invalide dans CONNECTION (CDP='.$p_cdp.') : '.$child->getName()."\n";
            }            
         }
      }
      if ($portID != '') {
         $p_oPort->addConnection($portID);
      }
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

}
?>
