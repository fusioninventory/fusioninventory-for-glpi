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
   Co-authors of file: David DURIEUX
   Purpose of file:
   ----------------------------------------------------------------------
 */

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
set_error_handler('userErrorHandlerDebug');
$_SESSION['glpi_use_mode'] = 2;

$PluginFusioninventoryCommunication  = new PluginFusioninventoryCommunication();
$pta  = new PluginFusioninventoryAgent();

$errors='';

// ***** For debug only ***** //
//$GLOBALS["HTTP_RAW_POST_DATA"] = gzcompress('');
// ********** End ********** //

if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
   // Get conf tu know if SSL is only

   $fusioninventory_config = new PluginFusioninventoryConfig();
   $PluginFusioninventoryModule = new PluginFusioninventoryModule();
   $fusioninventoryModule_id = $PluginFusioninventoryModule->getModuleId("fusioninventory");

   $ssl = $fusioninventory_config->getValue($fusioninventoryModule_id, 'ssl_only');
   if (((isset($_SERVER["HTTPS"])) AND ($_SERVER["HTTPS"] == "on") AND ($ssl == "1"))
       OR ($ssl == "0")) {
      // echo "On continue";
   } else {
      $PluginFusioninventoryCommunication->setXML("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
</REPLY>");
      $PluginFusioninventoryCommunication->noSSL();
      exit();
   }

   // Check XML integrity
   $xml = '';
   $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
   $PluginFusioninventoryTaskjob->disableDebug();
   $comp = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
   $PluginFusioninventoryTaskjob->reenableusemode();
   if ($comp) {
      $xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
      logInFile("ocs2", $xml);
   } else if (gzinflate (substr($GLOBALS["HTTP_RAW_POST_DATA"], 2))) {
      // ** OCS agent 2.0 Compatibility
      $xml = gzinflate (substr($GLOBALS["HTTP_RAW_POST_DATA"], 2));
   } else {
      $xml = $GLOBALS["HTTP_RAW_POST_DATA"];
   }

   if (@simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA)) {
      $pxml = @simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);
   } else {
      $PluginFusioninventoryCommunication->setXML("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
   <ERROR>XML not well formed!</ERROR>
</REPLY>");
      $PluginFusioninventoryCommunication->emptyAnswer();
   }

   //

   if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
      file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/dial.log".uniqid(), $xml);
   }
   $pta->importToken($xml);

   $top0 = 0;
   $top0 = gettimeofday();
   if (!$PluginFusioninventoryCommunication->import($xml)) {

      if (isset($pxml->DEVICEID)) {

         $PluginFusioninventoryCommunication->setXML("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
</REPLY>");

         $a_agent = $pta->InfosByKey($pxml->DEVICEID);

         // Get taskjob in waiting
         $PluginFusioninventoryCommunication->getTaskAgent($a_agent['id']);
         // ******** Send XML

         $PluginFusioninventoryCommunication->addInventory($a_agent['id']);
         $PluginFusioninventoryCommunication->addProlog();
         $PluginFusioninventoryCommunication->setXML($PluginFusioninventoryCommunication->getXML());

         echo $PluginFusioninventoryCommunication->getSend();
      }
   } else {
      $PluginFusioninventoryCommunication->setXML("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
</REPLY>");
      $PluginFusioninventoryCommunication->emptyAnswer();
   }
}

?>