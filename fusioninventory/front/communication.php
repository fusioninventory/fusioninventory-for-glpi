<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

ob_start();
ini_set("memory_limit", "-1");
ini_set("max_execution_time", "0");

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

if (session_id()=="") {
   session_start();
}

$_SESSION['glpi_use_mode'] = 2;
include_once(GLPI_ROOT."/inc/includes.php");
if (!isset($_SESSION['glpilanguage'])) {
   $_SESSION['glpilanguage'] = 'fr_FR';
}
   
ini_set('display_errors','On');
error_reporting(E_ALL | E_STRICT);
set_error_handler(array('Toolbox','userErrorHandlerDebug'));
$_SESSION['glpi_use_mode'] = 2;

ob_end_clean();
header("server-type: glpi/fusioninventory ".PLUGIN_FUSINVINVENTORY_VERSION);
if (!class_exists("PluginFusioninventoryConfig")) {
   header("Content-Type: application/xml");
   echo "<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
   <ERROR>Plugin FusionInventory not installed!</ERROR>
</REPLY>";
   session_destroy();
   exit();
}

if (isset($_GET['action']) && isset($_GET['machineid'])) {
   // new REST protocol
   $response = PluginFusioninventoryCommunicationRest::communicate($_GET);
   if ($response) {
      echo json_encode($response);
   } else {
      PluginFusioninventoryCommunicationRest::sendError();
   }
} else if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
   // old XML protocol
   
   // ***** For debug only ***** //
   //$GLOBALS["HTTP_RAW_POST_DATA"] = gzcompress('');
   // ********** End ********** //

   $config = new PluginFusioninventoryConfig();
   $module = new PluginFusioninventoryModule();
   
   ob_start();
   $module_id = $module->getModuleId("fusioninventory");
   $users_id  = $config->getValue($module_id, 'users_id', '');
   $_SESSION['glpiID'] = $users_id;
   $_SESSION['glpiactiveprofile'] = array();
   $_SESSION['glpiactiveprofile']['interface'] = '';
   $plugin = new Plugin();
   $plugin->init();
   $LOADED_PLUGINS = array();
   if (isset($_SESSION["glpi_plugins"]) && is_array($_SESSION["glpi_plugins"])) {
      //doHook("config");
      if (count($_SESSION["glpi_plugins"])) {
         foreach ($_SESSION["glpi_plugins"] as $name) {
            Plugin::load($name);
         }
      }
      // For plugins which require action after all plugin init
      Plugin::doHook("post_init");
   }
   ob_end_clean();

   $communication  = new PluginFusioninventoryCommunication();

   // identify message compression algorithm
   $xml = '';
   $pfTaskjob = new PluginFusioninventoryTaskjob();
   $pfTaskjob->disableDebug();
   if ($_SERVER['CONTENT_TYPE'] == "application/x-compress-zlib") {
         $xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
         $compressmode = "zlib";
   } else if ($_SERVER['CONTENT_TYPE'] == "application/x-compress-gzip") {
      $xml = PluginFusioninventoryToolbox::gzdecode(
         $GLOBALS["HTTP_RAW_POST_DATA"]
      );
         $compressmode = "gzip";
   } else if ($_SERVER['CONTENT_TYPE'] == "application/xml") {
         $xml = $GLOBALS["HTTP_RAW_POST_DATA"];
         $compressmode = 'none';
   } else {
      # try each algorithm successively
      if ($xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"])) {
         $compressmode = "zlib";
      } else if ($xml = PluginFusioninventoryToolbox::gzdecode($GLOBALS["HTTP_RAW_POST_DATA"])) {
         $compressmode = "gzip";
      } else if ($xml = gzinflate(substr($GLOBALS["HTTP_RAW_POST_DATA"], 2))) {
         // accept deflate for OCS agent 2.0 compatibility,
         // but use zlib for answer
         if (strstr($xml, "<QUERY>PROLOG</QUERY>")
                 AND !strstr($xml, "<TOKEN>")) {
            $compressmode = "zlib";
         } else {
            $compressmode = "deflate";
         } 
      } else {
         $xml = $GLOBALS["HTTP_RAW_POST_DATA"];
         $compressmode = 'none';
      }
   }
   $taskjob->reenableusemode();

   // check if we are in ssl only mode
   $ssl = $config->getValue($module_id, 'ssl_only', '');
   if (
      $ssl == "1"
         AND
      (!isset($_SERVER["HTTPS"]) OR $_SERVER["HTTPS"] != "on")
   ) {
      $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
<ERROR>SSL REQUIRED BY SERVER</ERROR>
</REPLY>");
      $communication->formatMessage();
      $communication->sendMessage($compressmode);
      session_destroy();
      exit();
   }

   PluginFusioninventoryToolbox::logIfExtradebug(
      GLPI_PLUGIN_DOC_DIR."/fusioninventory/dial.log".uniqid(),
      $xml
   );

   // Check XML integrity
   $pxml = '';
   if ($pxml = @simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA)) {

   } else if ($pxml = @simplexml_load_string(utf8_encode($xml),'SimpleXMLElement', LIBXML_NOCDATA)) {
      $xml = utf8_encode($xml);
   } else {
      $xml = preg_replace ('/<FOLDER>.*?<\/SOURCE>/', '', $xml);
      $pxml = @simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);

      if (!$pxml) {
         $communication = new PluginFusioninventoryCommunication();
         $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
<ERROR>XML not well formed!</ERROR>
</REPLY>");
         $communication->formatMessage();
         $communication->sendMessage($compressmode);
         session_destroy();
         exit();
      }
   }

   // Clean for XSS and other in XML
   $pxml = PluginFusioninventoryToolbox::cleanXML($pxml);
                     
   $agent = new PluginFusioninventoryAgent();
   $agents_id = $agent->importToken($pxml);
   $_SESSION['plugin_fusioninventory_agents_id'] = $agents_id;
   
   if (!$communication->import($pxml)) {

      if (isset($pxml->DEVICEID)) {

         $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
</REPLY>");

         $a_agent = $agent->InfosByKey(Toolbox::addslashes_deep($pxml->DEVICEID));
         
         // Get taskjob in waiting
         $communication->getTaskAgent($a_agent['id']);
         // ******** Send XML

         $communication->addInventory($a_agent['id']);
         $communication->addProlog();
         $communication->formatMessage();

         $communication->sendMessage($compressmode);
      }
   } else {
      $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
</REPLY>");
      $communication->formatMessage();
      $communication->sendMessage($compressmode);
   }
}
session_destroy();

?>
