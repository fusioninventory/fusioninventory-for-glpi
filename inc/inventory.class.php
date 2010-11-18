<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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

/**
 * Class 
 **/
class PluginFusinvinventoryInventory {

   
   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {
      global $LANG;

      $errors = '';

      $this->sendCriteria($p_DEVICEID, $p_CONTENT, $p_xml);

      return $errors;
   }



   function sendCriteria($p_DEVICEID, $p_CONTENT, $p_xml) {

      $PluginFusinvinventoryBlacklist = new PluginFusinvinventoryBlacklist();
      $p_xml = $PluginFusinvinventoryBlacklist->cleanBlacklist($p_xml);

      define('SOURCEXML', $p_xml);

      $xml = simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA);
      $input = array();

      // Global criterias

         if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
            $input['globalcriteria'][] = 1;
            $input['serialnumber'] = $xml->CONTENT->BIOS->SSN;
         }
         if ((isset($xml->CONTENT->HARDWARE->UUID)) AND (!empty($xml->CONTENT->HARDWARE->UUID))) {
            $input['globalcriteria'][] = 2;
            $input['uuid'] = $xml->CONTENT->HARDWARE->UUID;
         }
         if (isset($xml->CONTENT->NETWORKS)) {
            foreach($xml->CONTENT->NETWORKS as $network) {
               if ((isset($network->MACADDR)) AND (!empty($network->MACADDR))) {
                  $input['globalcriteria'][] = 3;
                  $input['mac'][] = $network->MACADDR;
               }
            }
         }
         if ((isset($xml->CONTENT->HARDWARE->WINPRODKEY)) AND (!empty($xml->CONTENT->HARDWARE->WINPRODKEY))) {
            $input['globalcriteria'][] = 4;
            $input['windowskey'] = $xml->CONTENT->HARDWARE->WINPRODKEY;
         }
         if ((isset($xml->CONTENT->BIOS->SMODEL)) AND (!empty($xml->CONTENT->BIOS->SMODEL))) {
            $input['globalcriteria'][] = 5;
            $input['model'] = $xml->CONTENT->BIOS->SMODEL;
         }
         if (isset($xml->CONTENT->STORAGES)) {
            foreach($xml->CONTENT->STORAGES as $storage) {
               if ((isset($storage->SERIALNUMBER)) AND (!empty($storage->SERIALNUMBER))) {
                  $input['globalcriteria'][] = 6;
                  $input['storageserial'][] = $storage->SERIALNUMBER;
               }
            }
         }
         if (isset($xml->CONTENT->DRIVES)) {
            foreach($xml->CONTENT->DRIVES as $drive) {
               if ((isset($drive->SERIAL)) AND (!empty($drive->SERIAL))) {
                  $input['globalcriteria'][] = 7;
                  $input['drivesserial'][] = $drive->SERIAL;
               }
            }
         }
         if ((isset($xml->CONTENT->BIOS->ASSETTAG)) AND (!empty($xml->CONTENT->BIOS->ASSETTAG))) {
            $input['globalcriteria'][] = 8;
            $input['assettag'] = $xml->CONTENT->BIOS->ASSETTAG;
         }
      $rule = new PluginFusinvinventoryRuleInventoryCollection();
      $data = array ();
      $data = $rule->processAllRules($input, array());
      
   }
   


   function sendLib($criterias) {
      logInFile('criteria', print_r($criterias, true));
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/FusionLibServer.class.php";
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/MyException.class.php";
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/Logger.class.php";

      $config = array();

      $config['storageEngine'] = "Directory";
      $config['storageLocation'] = "/../../../../../../../files/_plugins/fusinvinventory";

      // get criteria from rules
      $config['criterias'] = $criterias;

      $config['maxFalse'] = 0;

      $config['filter'] = 1;
      $config['printError'] = 0;

      $config['sections'][] = "DRIVES";
      $config['sections'][] = "NETWORKS";
      $config['sections'][] = "PROCESSES";

      define("LIBSERVERFUSIONINVENTORY_LOG_FILE",GLPI_PLUGIN_DOC_DIR.'/fusioninventory/logs');
      define("LIBSERVERFUSIONINVENTORY_STORAGELOCATION",GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      define("LIBSERVERFUSIONINVENTORY_HOOKS_CLASSNAME","PluginFusinvinventoryLibhook");
      define("LIBSERVERFUSIONINVENTORY_LOG_DIR",GLPI_PLUGIN_DOC_DIR.'/fusioninventory/');
      define("LIBSERVERFUSIONINVENTORY_PRINTERROR",$config['printError']);
      $log = new Logger('../../../../../../files/_plugins/fusioninventory/logs');

      $action = ActionFactory::createAction("inventory");
      
      //$action->checkConfig("../../../../../fusinvinventory/inc", $config);
      $action->checkConfig("", $config);
      ob_start();
      $action->startAction(simplexml_load_string(SOURCEXML,'SimpleXMLElement', LIBXML_NOCDATA));
      $output = ob_flush();
      if (!empty($output)) {
         logInFile("fusinvinventory", $output);
      }
   }


   function sendUnknownDevice() {

      $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $NetworkPort = new NetworkPort();

      $xml = simplexml_load_string(SOURCEXML,'SimpleXMLElement', LIBXML_NOCDATA);
      //Search with serial
      if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
         $a_device = $PluginFusioninventoryUnknownDevice->find("`serial`='".$xml->CONTENT->BIOS->SSN."'");
         if (count($a_device) == "1") {
            foreach ($a_device as $id => $datas) {
               if (isset($xml->CONTENT->HARDWARE->NAME)) {
                  $datas['name'] = $xml->CONTENT->HARDWARE->NAME;
               }
               if (isset($xml->CONTENT->HARDWARE->USERID)) {
                  $datas['contact'] = $xml->CONTENT->HARDWARE->USERID;
               }
               if ((isset($xml->CONTENT->HARDWARE->USERDOMAIN)) AND (!empty($xml->CONTENT->HARDWARE->USERDOMAIN))) {
                  $datas['domain'] = Dropdown::importExternal('glpi_domains', $xml->CONTENT->HARDWARE->USERDOMAIN);
               }
               $datas['type'] = 'Computer';
               $PluginFusioninventoryUnknownDevice->add($datas);
               $PluginFusioninventoryUnknownDevice->writeXML($datas['id'], SOURCEXML);
               return;
            }
         }
      }
      //Search with mac address
//      if (isset($XML->CONTENT->NETWORKS)) {
//         foreach ($xml->CONTENT->NETWORKS->children() as $name=>$child) {
//            $a_port = $NetworkPort->find("`mac`='".$child->MACADDR."' AND `itemtype`='PluginFusioninventoryUnknownDevice'");
//
//
//         }
//      }
      //Else add unknown device
      $input = array();
      if (isset($xml->CONTENT->HARDWARE->NAME)) {
         $input['name'] = $xml->CONTENT->HARDWARE->NAME;
      }
      if (isset($xml->CONTENT->HARDWARE->USERID)) {
         $input['contact'] = $xml->CONTENT->HARDWARE->USERID;
      }
      if ((isset($xml->CONTENT->HARDWARE->USERDOMAIN)) AND (!empty($xml->CONTENT->HARDWARE->USERDOMAIN))) {
         $input['domain'] = Dropdown::importExternal('glpi_domains', $xml->CONTENT->HARDWARE->USERDOMAIN);
      }
      if (isset($xml->CONTENT->BIOS->SSN)) {
         $input['serial'] = $xml->CONTENT->BIOS->SSN;
      }
      $input['type'] = 'Computer';
      $unknown_id = $PluginFusioninventoryUnknownDevice->add($input);
      $PluginFusioninventoryUnknownDevice->writeXML($unknown_id, SOURCEXML);
   }
}

?>