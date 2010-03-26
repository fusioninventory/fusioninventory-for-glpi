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
// Purpose of file: test of communication class
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}
$NEEDED_ITEMS=array("computer","device","printer","networking","peripheral","monitor","software","infocom",
	"phone","tracking","enterprise","reservation","setup","group","registry","rulesengine","ocsng","admininfo",
   "rule.ocs","rule.softwarecategories","rule.dictionnary.software","rule.dictionnary.dropdown","entity");


include (GLPI_ROOT."/inc/includes.php");

$_SESSION["glpi_use_mode"] = 2;

//header('Content-type: application/x-compress');

$ptc  = new PluginFusionInventoryCommunication;
$ptap = new PluginFusionInventoryAgentsProcesses;

$res='';
$errors='';

// Get conf tu know if SSL is only
$fusioninventory_config = new PluginFusionInventoryConfig;
$ssl = $fusioninventory_config->getValue('ssl_only');
if (((isset($_SERVER["HTTPS"])) AND ($_SERVER["HTTPS"] == "on") AND ($ssl == "1"))
    OR ($ssl == "0")) {
	// echo "On continue";
} else {
   $ptc->setXML("<?xml version='1.0' encoding='ISO-8859-1'?>
<REPLY>
</REPLY>");
   $ptc->noSSL();
	exit();
}
$ocsinventory = '0';
file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/dial.log".rand(), gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]));
$state = $ptc->importToken(gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]));
if ($state == '2') {
   $ocsinventory = '1';
}
$top0 = gettimeofday();
if (!$ptc->import(gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]))) {
   //if ($ac->connectionOK($errors)) {
   if (1) {
      $res .= "1'".$errors."'";

      $p_xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
      $pxml = @simplexml_load_string($p_xml);
     
      if (isset($pxml->DEVICEID)) {

         $ptc->setXML("<?xml version='1.0' encoding='ISO-8859-1'?>
<REPLY>
</REPLY>");

      $pta  = new PluginFusionInventoryAgents;
      $ptt  = new PluginFusionInventoryTask;
      $ptcm = new PluginFusionInventoryConfigModules;


      $a_agent = $pta->InfosByKey($pxml->DEVICEID);
      $a_tasks = $ptt->find("`agent_id`='".$a_agent['ID']."'", "date");

      $single = 0;
      $_SESSION['glpi_plugin_fusioninventory_addagentprocess'] = '0';
      
      foreach ($a_tasks as $task_id=>$datas) {
         if (($a_tasks[$task_id]['action'] == 'INVENTORY')
                 AND ($ptcm->isActivated('inventoryocs'))
                 AND ($a_agent['module_inventory'] == '1')) {
            $ptc->addInventory();
            $input['ID'] = $task_id;
            $ptt->delete($input);
            $ocsinventory = '0';
            $single = 1;
         }
         if (($a_tasks[$task_id]['action'] == 'NETDISCOVERY')
                 AND ($ptcm->isActivated('netdiscovery'))
                 AND ($a_agent['module_netdiscovery'] == '1')) {
            $single = 1;
            $ptc->addDiscovery($pxml, 1);
         }
      }
      
      if ($single == "0") {
         if ($a_agent['module_netdiscovery'] == '1') {
            $ptc->addDiscovery($pxml);
         }
         if ($a_agent['module_snmpquery'] == '1') {
            $ptc->addQuery($pxml);
         }
      }
      if (($ocsinventory == '1')
              AND ($a_agent['module_inventory'] == '1')) {
         $ptc->addInventory();
      }
//      $ptc->addWakeonlan();
      // ******** Send XML
         $ptc->setXML($ptc->getXML());
         echo $ptc->getSend(); // echo response for the agent
      }
   } else {
      $res .= "0'".$errors."'";
   }
}

?>