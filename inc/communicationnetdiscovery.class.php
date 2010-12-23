<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
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
 * Class to communicate with agents using XML
 **/
class PluginFusinvsnmpCommunicationNetDiscovery extends PluginFusinvsnmpCommunicationSNMP {
//   private $sxml, $deviceId, $ptd, $type='', $logFile;

   /**
    * Add NETDISCOVERY string to XML code
    *
    *@return nothing
    **/
//   function addDiscovery($pxml, $task=0) {
//      $ptsnmpa = new PluginFusinvsnmpConfigSecurity;
//      $pta     = new PluginFusioninventoryAgent;
//      $ptap    = new PluginFusioninventoryAgentProcess;
//      $ptrip   = new PluginFusioninventoryIPRange;
//      $ptt     = new PluginFusioninventoryTask;
//
//      $agent = $pta->InfosByKey($pxml->DEVICEID);
//      $count_range = $ptrip->Counter($agent["id"], "discover");
//      $count_range += $ptt->Counter($agent["id"], "NETDISCOVERY");
//      if ($task == "1") {
//         $tasks = $ptt->ListTask($agent["id"], "NETDISCOVERY");
//         foreach ($tasks as $task_id=>$taskInfos) {
//            if ($tasks[$task_id]["param"] == 'PluginFusioninventoryAgent') {
//               $task = "0";
//            }
//         }
//         if ($task == "1") {
//            $agent["core_discovery"] = 1;
//            $agent["threads_discovery"] = 1;
//         }
//      }
//
//      if ((($count_range > 0) && ($agent["lock"] == 0)) OR ($task == "1") ) {
//         $a_input = array();
//         if ($_SESSION['glpi_plugin_fusioninventory_addagentprocess'] == '0') {
//            $this->addProcessNumber($ptap->addProcess($pxml));
//            $_SESSION['glpi_plugin_fusioninventory_addagentprocess'] = '1';
//         }
//         $a_input['discovery_core'] = $agent["core_discovery"];
//         $a_input['discovery_threads'] = $agent["threads_discovery"];
//         $ptap->updateProcess($this->sxml->PROCESSNUMBER, $a_input);
//
//         $sxml_option = $this->sxml->addChild('OPTION');
//            $sxml_option->addChild('NAME', 'NETDISCOVERY');
//            $sxml_param = $sxml_option->addChild('PARAM');
//               $sxml_param->addAttribute('CORE_DISCOVERY', $agent["core_discovery"]);
//               $sxml_param->addAttribute('THREADS_DISCOVERY', $agent["threads_discovery"]);
//               $sxml_param->addAttribute('PID', $this->sxml->PROCESSNUMBER);
//
//            if ($task == "1") {
//               foreach ($tasks as $task_id=>$taskInfos) {
//                  $sxml_rangeip = $sxml_option->addChild('RANGEIP');
//                     $sxml_rangeip->addAttribute('ID', $task_id);
//                     $sxml_rangeip->addAttribute('IPSTART', $tasks[$task_id]["ip"]);
//                     $sxml_rangeip->addAttribute('IPEND', $tasks[$task_id]["ip"]);
//                     $sxml_rangeip->addAttribute('ENTITY', "");
//                     $sxml_rangeip->addAttribute('DEVICEID', $tasks[$task_id]["items_id"]);
//                     $sxml_rangeip->addAttribute('TYPE', $tasks[$task_id]["itemtype"]);
//
//                     $ptt->deleteFromDB($task_id);
//               }
//
//            } else {
//               $ranges = $ptrip->ListRange($agent["id"], "discover");
//               foreach ($ranges as $range_id=>$rangeInfos) {
//                  $sxml_rangeip = $sxml_option->addChild('RANGEIP');
//                     $sxml_rangeip->addAttribute('ID', $range_id);
//                     $sxml_rangeip->addAttribute('IPSTART', $ranges[$range_id]["ifaddr_start"]);
//                     $sxml_rangeip->addAttribute('IPEND', $ranges[$range_id]["ifaddr_end"]);
//                     $sxml_rangeip->addAttribute('ENTITY', $ranges[$range_id]["entities_id"]);
//               }
//            }
//
//            $snmpauthlist=$ptsnmpa->find();
//            if (count($snmpauthlist)){
//               foreach ($snmpauthlist as $snmpauth){
//                  $this->addAuth($sxml_option, $snmpauth['id']);
//               }
//            }
//         //$this->sxml->addChild('RESPONSE', 'SEND');
//      }
//   }

   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {
      global $LANG;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pta  = new PluginFusioninventoryAgent();


      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationNetDiscovery->import().');
      //$this->setXML($p_CONTENT);
      $errors = '';

      if (isset($p_CONTENT->PROCESSNUMBER)) {
         $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $p_CONTENT->PROCESSNUMBER;
         $a_agent = $pta->InfosByKey($p_DEVICEID);
         $a_jobstatus = $PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$p_CONTENT->PROCESSNUMBER."'
            AND `plugin_fusioninventory_agents_id`='".$a_agent['id']."' ");
         foreach($a_jobstatus as $jobstatus_id=>$data) {
            
         }
         $PluginFusioninventoryTaskjobstatus->getFromDB($jobstatus_id);
         $PluginFusioninventoryTaskjobstatus->changeStatus($jobstatus_id, 2);
         if ((!isset($p_CONTENT->AGENT->START)) AND (!isset($p_CONTENT->AGENT->END))) {
            $nb_devices = 0;
            foreach($p_CONTENT->DEVICE as $child) {
               $nb_devices++;
            }
            $PluginFusioninventoryTaskjoblog->addTaskjoblog($p_CONTENT->PROCESSNUMBER,
                                                   $PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_agents_id'],
                                                   'PluginFusioninventoryAgent',
                                                   '6',
                                                   $nb_devices.' devices found');
         }
      }

      $moduleversion = "1.0";
      if (isset($p_CONTENT->MODULEVERSION)) {
         $moduleversion = $p_CONTENT->PROCESSNUMBER;
      }
      $pti = new PluginFusinvsnmpImportExport;
      $errors.=$pti->import_netdiscovery($p_CONTENT, $p_DEVICEID, $moduleversion);
      if (isset($p_CONTENT->AGENT->END)) {
         $PluginFusioninventoryTaskjobstatus->changeStatusFinish($p_CONTENT->PROCESSNUMBER,
                                                   $PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_agents_id'],
                                                   'PluginFusioninventoryAgent');
      }
      return $errors;
   }


   function sendCriteria($p_xml) {

      $_SESSION['glpi_plugin_fusinvsnmp_xmlDevice'] = $p_xml;

      $input = array();

      // Global criterias

      if ((isset($p_xml->SERIAL)) AND (!empty($p_xml->SERIAL))) {
         $input['globalcriteria'][] = 1;
         $input['serial'] = strval($p_xml->SERIAL);
      }
      if ((isset($p_xml->MAC)) AND (!empty($p_xml->MAC))) {
         $input['globalcriteria'][] = 2;
         $input['mac'] = strval($p_xml->MAC);
      }
      if ((isset($p_xml->MODELSNMP)) AND (!empty($p_xml->MODELSNMP))) {
         $input['globalcriteria'][] = 3;
         $input['model'] = strval($p_xml->MODELSNMP);
      }
      if ((isset($p_xml->NETBIOSNAME)) AND (!empty($p_xml->NETBIOSNAME))) {
         $input['globalcriteria'][] = 4;
         $input['name'] = strval($p_xml->NETBIOSNAME);
      } else if ((isset($p_xml->SNMPHOSTNAME)) AND (!empty($p_xml->SNMPHOSTNAME))) {
         $input['globalcriteria'][] = 4;
         $input['name'] = strval($p_xml->SNMPHOSTNAME);
      }

      define('DATACRITERIA', serialize($input));
      $rule = new PluginFusinvsnmpRuleNetdiscovery();
      $data = array ();
      $data = $rule->processAllRules($input, array());
   }



   function checkCriteria($a_criteria) {
      global $DB;

      $condition = "WHERE 1 ";
      $condition_ports = "WHERE 1 ";
      $select = "id";
      $select_ports = "id";
      $datacriteria = unserialize(DATACRITERIA);

      foreach ($a_criteria as $criteria) {
         switch ($criteria) {

           case 'serial':
               $condition .= "AND `serial`='".$datacriteria['serial']."' ";
               $select .= ", serial";
               $condition_ports .= "AND `serial`='".$datacriteria['serial']."' ";
               $select_ports .= ", serial";
               break;

            case 'mac':
               $condition .= "AND `mac`='".$datacriteria['mac']."' ";
               $select .= ", mac";
               $condition_ports .= "AND `glpi_networkports`.`mac`='".$datacriteria['mac']."' ";
               $select_ports .= ", `glpi_networkports`.`mac`";
               break;

            case 'model':
               $condition .= "AND `models_id`='".$datacriteria['model']."' ";
               $select .= ", models_id";
               $condition_ports .= "AND `models_id`='".$datacriteria['model']."' ";
               $select_ports .= ", models_id";
               break;

            case 'name':
               $condition .= "AND `name`='".$datacriteria['name']."' ";
               $select .= ", name";
               $condition_ports .= "AND `name`='".$datacriteria['name']."' ";
               $select_ports .= ", name";
               break;
         }
      }

      $query1 = "SELECT ".str_replace('models_id', 'printermodels_id', $select_ports)." FROM `".getTableForItemType("Printer")."`
         ".str_replace('models_id', 'printermodels_id', $condition_ports)." ";
      $result1=$DB->query($query1);

      $query2 = "SELECT ".str_replace('models_id', 'networkequipmentmodels_id', $select)." FROM `".getTableForItemType("NetworkEquipment")."`
         ".str_replace('models_id', 'networkequipmentmodels_id', $condition)." ";
      $result2=$DB->query($query2);

      $query3 = "SELECT ".str_replace('models_id', 'computermodels_id', $select)." FROM `".getTableForItemType("Computer")."`
         ".str_replace('models_id', 'computermodels_id', $condition_ports)." ";
      $result3=$DB->query($query3);

      if (($DB->numrows($result1) + $DB->numrows($result2)) == "1") {
         if ($DB->numrows($result1) == "1") {
   			$data = $DB->fetch_assoc($result1);
            $this->importDevice('Printer', $data['id']);
         } else if ($DB->numrows($result2) == "1") {
            $data = $DB->fetch_assoc($result2);
            $this->importDevice('NetworkEquipment', $data['id']);
         } else if ($DB->numrows($result3) == "1") {
            $data = $DB->fetch_assoc($result3);
            $this->importDevice('Computer', $data['id']);
         }
      }
   }

}

?>