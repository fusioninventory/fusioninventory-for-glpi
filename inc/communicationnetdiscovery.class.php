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
 * Class to communicate with agents using XML
 **/
class PluginFusinvsnmpCommunicationNetDiscovery extends PluginFusinvsnmpCommunicationSNMP {
//   private $sxml, $deviceId, $ptd, $type='', $logFile;

   /**
    * Add NETDISCOVERY string to XML code
    *
    *@return nothing
    **/
   function addDiscovery($pxml, $task=0) {
      $ptsnmpa = new PluginFusioninventoryConfigSNMPSecurity;
      $pta     = new PluginFusioninventoryAgent;
      $ptap    = new PluginFusioninventoryAgentProcess;
      $ptrip   = new PluginFusioninventoryIPRange;
      $ptt     = new PluginFusioninventoryTask;

      $agent = $pta->InfosByKey($pxml->DEVICEID);
      $count_range = $ptrip->Counter($agent["id"], "discover");
      $count_range += $ptt->Counter($agent["id"], "NETDISCOVERY");
      if ($task == "1") {
         $tasks = $ptt->ListTask($agent["id"], "NETDISCOVERY");
         foreach ($tasks as $task_id=>$taskInfos) {
            if ($tasks[$task_id]["param"] == 'PluginFusioninventoryAgent') {
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
                     $sxml_rangeip->addAttribute('IPSTART', $tasks[$task_id]["ip"]);
                     $sxml_rangeip->addAttribute('IPEND', $tasks[$task_id]["ip"]);
                     $sxml_rangeip->addAttribute('ENTITY', "");
                     $sxml_rangeip->addAttribute('DEVICEID', $tasks[$task_id]["items_id"]);
                     $sxml_rangeip->addAttribute('TYPE', $tasks[$task_id]["itemtype"]);

                     $ptt->deleteFromDB($task_id);
               }

            } else {
               $ranges = $ptrip->ListRange($agent["id"], "discover");
               foreach ($ranges as $range_id=>$rangeInfos) {
                  $sxml_rangeip = $sxml_option->addChild('RANGEIP');
                     $sxml_rangeip->addAttribute('ID', $range_id);
                     $sxml_rangeip->addAttribute('IPSTART', $ranges[$range_id]["ifaddr_start"]);
                     $sxml_rangeip->addAttribute('IPEND', $ranges[$range_id]["ifaddr_end"]);
                     $sxml_rangeip->addAttribute('ENTITY', $ranges[$range_id]["entities_id"]);
               }
            }
            
            $snmpauthlist=$ptsnmpa->find();
            if (count($snmpauthlist)){
               foreach ($snmpauthlist as $snmpauth){
                  $this->addAuth($sxml_option, $snmpauth['id']);
               }
            }
         //$this->sxml->addChild('RESPONSE', 'SEND');
      }
   }

   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationNetDiscovery->import().');
      $this->setXML($p_CONTENT);
      $errors = '';

      if (isset($p_CONTENT->PROCESSNUMBER)) {
         $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $p_CONTENT->PROCESSNUMBER;
      }
      if (isset($p_CONTENT->MODULEVERSION)) {
         $moduleversion = $p_CONTENT->PROCESSNUMBER;
      } else {
         $moduleversion = "1.0";
      }
      $pti = new PluginFusinvsnmpImportExport;
      $errors.=$pti->import_netdiscovery($p_CONTENT, $p_DEVICEID, $moduleversion);
      return $errors;
   }
}

?>
