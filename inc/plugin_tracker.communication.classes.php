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
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginTrackerCommunication {
   private $sxml;

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

   function getXML() {
      return str_replace("><", ">\n<", $this->sxml->asXML());
   }

   function setXML($p_xml) {
      $this->sxml = @simplexml_load_string($p_xml); // @ to avoid xml warnings
   }

   function get() {
      if ($GLOBALS["HTTP_RAW_POST_DATA"] == '') {
         return '';
      } else {
         return gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
      }
   }

   function send() {
      return gzcompress($this->sxml->asXML());
   }

   /*
    * compare les données reçues avec la chaîne de connexion attendue
    */
   function connectionOK(&$errors='') {
      // gérer l'encodage, la version
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

   function addQuery() {
      $ptmi = new PluginTrackerModelInfos;

      $sxml_option = $this->sxml->addChild('OPTION');
         $sxml_option->addChild('NAME', 'SNMPQUERY');
         $sxml_param = $sxml_option->addChild('PARAM');
            $sxml_param->addAttribute('CORE_QUERY', '1');
            $sxml_param->addAttribute('THREADS_QUERY', '2');
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

   function addDiscovery() {
      $sxml_option = $this->sxml->addChild('OPTION');
         $sxml_option->addChild('NAME', 'NETDISCOVERY');
         $sxml_param = $sxml_option->addChild('PARAM');
            $sxml_param->addAttribute('CORE_DISCOVERY', '2');
            $sxml_param->addAttribute('THREADS_DISCOVERY', '5');
            $sxml_param->addAttribute('PID', '03201054001');
            $sxml_param->addAttribute('LOGS', '2');
         $sxml_rangeip = $sxml_option->addChild('RANGEIP');
            $sxml_rangeip->addAttribute('ID', '1');
            $sxml_rangeip->addAttribute('IPSTART', '192.168.0.1');
            $sxml_rangeip->addAttribute('IPEND', '192.168.0.254');
            $sxml_rangeip->addAttribute('ENTITY', '0');
         $this->addAuth($sxml_option, 2, 'public', '2c');
         $this->addAuth($sxml_option, 1, 'public', '1');
      $this->sxml->addChild('RESPONSE', 'SEND');
   }

   /*
    * p_id : identifie les données d'authentification
    * p_version : version du protocole snmp
    *
    * table snmp_connections -->
    * 1. modifier addAuth() pour lui passer la réf de l'emplacment de la base ou elle va trouver les donnees d'authentification
    * 2. ne pas modifier addAuth() et insérer avant une fonction getAuth() qui l'alimentera avec les bonnes donnees
    */
   function addAuth($p_sxml_node, $p_id, $p_community, $p_version, $p_sec_name='', $p_sec_level='',
                    $p_auth_prot='', $p_auth_pass='', $p_priv_prot='', $p_priv_pass='') {
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

   function addModel($p_sxml_node, $p_id) {
      $models = new PluginTrackerModelInfos;
      $mib_networking = new PluginTrackerMibNetworking;

      $models->getFromDB($p_id);
      $sxml_model = $p_sxml_node->addChild('MODEL');
         $sxml_model->addAttribute('ID', $p_id);
         $sxml_model->addAttribute('NAME', $models->fields['name']);
         $mib_networking->oidList($sxml_model,$p_id);
   }

   function addGet($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_get = $p_sxml_node->addChild('GET');
         $sxml_get->addAttribute('OBJECT', $p_object);
         $sxml_get->addAttribute('OID', $p_oid);
         $sxml_get->addAttribute('VLAN', $p_vlan);
         $sxml_get->addAttribute('LINK', $p_link);
   }

   function addWalk($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_walk = $p_sxml_node->addChild('WALK');
         $sxml_walk->addAttribute('OBJECT', $p_object);
         $sxml_walk->addAttribute('OID', $p_oid);
         $sxml_walk->addAttribute('VLAN', $p_vlan);
         $sxml_walk->addAttribute('LINK', $p_link);
   }

   function addInfo($p_sxml_node, $p_id, $p_ip, $p_authsnmp_id, $p_model_id) {
      $sxml_info = $p_sxml_node->addChild('INFO');
         $sxml_info->addAttribute('ID', $p_id);
         $sxml_info->addAttribute('IP', $p_ip);
         $sxml_info->addAttribute('AUTHSNMP_ID', $p_authsnmp_id);
         $sxml_info->addAttribute('MODELSNMP_ID', $p_model_id);
   }

// ne pas renvoyer toutes les données d'authentification:
// seulement $sxml_authentication->addAttribute('ID', $p_id);
   function addDevice($p_sxml_node, $p_type) {
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
      $sxml_device = $p_sxml_node->addChild('DEVICE');
         $sxml_device->addAttribute('TYPE', $type);
         $this->addInfo($sxml_device, '3', '192.168.0.80', '2', '4');
   }
}
?>
