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

require_once GLPI_ROOT.'/plugins/fusinvsnmp/inc/communicationsnmp.class.php';

/**
 * Class 
 **/
class PluginFusinvinventoryInventory extends PluginFusinvsnmpCommunicationSNMP {
//   private $sxml, $deviceId, $ptd, $type='', $logFile;


   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT) {
      global $LANG;

      $this->setXML($p_CONTENT);
      $errors = '';

      $glpi_id = "3005";
      // Criteria to find right device

      // if found, update fields
      $this->parseSections();


      return $errors;
   }


   function parseSections() {
      //foreach
      
   }


   function sendLib($p_DEVICEID, $p_CONTENT, $p_xml) {
      //$this->sendInventoryToOcsServer($p_xml);
      //$GLOBALS["HTTP_RAW_POST_DATA"] = gzcompress('$p_xml');

      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/FusionLibServer.class.php";
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/MyException.class.php";
      require_once GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/Classes/Logger.class.php";

//      $configs = parse_ini_file(GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/user/configs.ini", true);
//
//      if (file_exists ($path=GLPI_ROOT ."/plugins/fusioninventory/lib/libfusioninventory-server-php/user/applications/{$configs['application']['name']}/FusInvHooks.class.php"))
//      {
//          require_once $path;
//      } else {
//          throw new MyException ("you have to put FusInvHooks class in applications/{$configs['application']['name']}/ directory");
//      }
//      $fusionLibServer = FusionLibServer::getInstance();
//
//      $fusionLibServer->setApplicationName($configs['application']['name']);
//      //$fusionLibServer->setPrologFreq($configs['prolog']['freq']);
//
//      //We set configs for each action
//      foreach($configs['actions'] as $action){
//          $fusionLibServer->setActionConfig($action, $configs[$action]);
//      }
//
//      $fusionLibServer->start("inventory");
      $config = array();
      //$config['application']['name'] = "../../../../../plugins/fusinvinventory/inc";

      $config['storageEngine'] = "Directory";
      $config['storageLocation'] = "/../../../../../../../files/_plugins/fusinvinventory";

      // criterias available: "motherboardSerial", "assetTag", "msn",
      // "ssn", "baseboardSerial", "macAddress", "uuid", "winProdKey",
      // "biosSerial","enclosureSerial","smodel","storagesSerial","drivesSerial"
//      $config['criterias'][] = "assetTag";
//      $config['criterias'][] = "motherboardSerial";
      $config['criterias'][] = "uuid";
//      $config['criterias'][] = "smodel";
      $config['criterias'][] = "ssn";

      $config['maxFalse'] = 0;

//      $config = array();
//      $config = array(
//"storageEngine" => "Directory",
//"storageLocation" => "../../../../../../files/_plugins/fusioninventory/data",
//"applicationName" => "../../../../../fusinvinventory/inc",
//"criterias" => array("maxFalse" => 1, "items" => array("assetTag", "motherboardSerial", "macAddress", "baseboardSerial")));

      define("LIBSERVERFUSIONINVENTORY_LOG_FILE",GLPI_PLUGIN_DOC_DIR.'/fusioninventory/logs');
      define("LIBSERVERFUSIONINVENTORY_STORAGELOCATION",GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      define("LIBSERVERFUSIONINVENTORY_HOOKS_CLASSNAME","PluginFusinvinventoryLibhook");

      $log = new Logger('../../../../../../files/_plugins/fusioninventory/logs');

      $action = ActionFactory::createAction("inventory");
      //$action->setConfigs($config);

      $action->checkConfig("../../../../../fusinvinventory/inc", $config);
      $action->startAction(simplexml_load_string($p_xml));
   }
   
}

?>