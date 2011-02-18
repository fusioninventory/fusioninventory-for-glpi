<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

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
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

require_once(GLPI_ROOT."/plugins/fusioninventory/inc/communication.class.php");

class PluginFusinvsnmpSnmpinventory extends PluginFusioninventoryCommunication {

   // Get all devices and put in taskjobstatus each task for each device for each agent
   function prepareRun($taskjobs_id) {
      global $DB;
      
      $PluginFusioninventoryTask = new PluginFusioninventoryTask();
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange();
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();

      $uniqid = uniqid();

      $PluginFusioninventoryTaskjob->getFromDB($taskjobs_id);
      $PluginFusioninventoryTask->getFromDB($PluginFusioninventoryTaskjob->fields['plugin_fusioninventory_tasks_id']);

      $NetworkEquipment = new NetworkEquipment();
      $Printer = new Printer();

      /*
       * * Different possibilities  :
       * IP RANGE 
       * NetworkEquipment
       * Printer
       *
       * We will count total number of devices to query
       */

      // get items_id by type
      $a_iprange = array();
      $a_NetworkEquipment = array();
      $a_Printer = array();
      $a_definition = importArrayFromDB($PluginFusioninventoryTaskjob->fields['definition']);
      foreach ($a_definition as $datas) {
         $itemtype = key($datas);
         $items_id = current($datas);

         switch($itemtype) {

            case 'PluginFusinvsnmpIPRange':
               $a_iprange[] = $items_id;
               break;

            case 'NetworkEquipment':
               $a_NetworkEquipment[] = $items_id;
               break;

            case 'Printer':
               $a_Printer[] = $items_id;
               break;

         }
      }

      $a_iprange_NetworkEquipment = array();
      $a_iprange_Printer = array();
      // Get all devices on each iprange
      foreach ($a_iprange as $items_id) {
         $PluginFusinvsnmpIPRange->getFromDB($items_id);
      // Search NetworkEquipment
         $query = "SELECT `glpi_networkequipments`.`id` AS `gID`,
                            `glpi_networkequipments`.`ip` AS `gnifaddr`,
                            `plugin_fusinvsnmp_configsecurities_id`, `plugin_fusinvsnmp_models_id`
                     FROM `glpi_networkequipments`
                     LEFT JOIN `glpi_plugin_fusinvsnmp_networkequipments`
                          ON `networkequipments_id`=`glpi_networkequipments`.`id`
                     INNER join `glpi_plugin_fusinvsnmp_models`
                          ON `plugin_fusinvsnmp_models_id`=`glpi_plugin_fusinvsnmp_models`.`id`
                     WHERE `glpi_networkequipments`.`is_deleted`='0'
                          AND `plugin_fusinvsnmp_models_id`!='0'
                          AND `plugin_fusinvsnmp_configsecurities_id`!='0'";
         if ($PluginFusinvsnmpIPRange->fields['entities_id'] != '-1') {
           $query .= "AND `glpi_networkequipments`.`entities_id`='".$PluginFusinvsnmpIPRange->fields['entities_id']."' ";
         }
         $query .= " AND inet_aton(`ip`)
                         BETWEEN inet_aton('".$PluginFusinvsnmpIPRange->fields['ip_start']."')
                         AND inet_aton('".$PluginFusinvsnmpIPRange->fields['ip_end']."') ";

        $result=$DB->query($query);
        while ($data=$DB->fetch_array($result)) {
           $a_NetworkEquipment[] = $data['gID'];
        }
     // Search Printer
        $query = "SELECT `glpi_printers`.`ID` AS `gID`,
                         `glpi_networkports`.`ip` AS `gnifaddr`,
                         `plugin_fusinvsnmp_configsecurities_id`, `plugin_fusinvsnmp_models_id`
                  FROM `glpi_printers`
                  LEFT JOIN `glpi_plugin_fusinvsnmp_printers`
                          ON `printers_id`=`glpi_printers`.`id`
                  LEFT JOIN `glpi_networkports`
                          ON `items_id`=`glpi_printers`.`id`
                             AND `itemtype`='Printer'
                  INNER join `glpi_plugin_fusinvsnmp_models`
                       ON `plugin_fusinvsnmp_models_id`=`glpi_plugin_fusinvsnmp_models`.`id`
                  INNER join `glpi_plugin_fusinvsnmp_configsecurities`
                       ON `plugin_fusinvsnmp_configsecurities_id`=`glpi_plugin_fusinvsnmp_configsecurities`.`id`
                  WHERE `glpi_printers`.`is_deleted`=0
                        AND `plugin_fusinvsnmp_models_id`!='0'
                        AND `plugin_fusinvsnmp_configsecurities_id`!='0'";
         if ($PluginFusinvsnmpIPRange->fields['entities_id'] != '-1') {
            $query .= "AND `glpi_printers`.`entities_id`='".$PluginFusinvsnmpIPRange->fields['entities_id']."' ";
         }
         $query .= " AND inet_aton(`ip`)
                      BETWEEN inet_aton('".$PluginFusinvsnmpIPRange->fields['ip_start']."')
                      AND inet_aton('".$PluginFusinvsnmpIPRange->fields['ip_end']."') ";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $a_Printer[] = $data['gID'];
         }
      }
      $count_device = count($a_NetworkEquipment) + count($a_Printer);

      //list all agents
      $a_agent = importArrayFromDB($PluginFusioninventoryTaskjob->fields['action']);
      $dynagent = 0;
      $a_agentlist = array();
      foreach ($a_agent as $agent) {
         $agent_id = current($agent);
         if ($agent_id == '.1') {
            $dynagent = 1;
         } else {
            $a_ip = $PluginFusioninventoryAgent->getIPs($agent_id);
            $PluginFusioninventoryAgent->getFromDB($agent_id);
            foreach($a_ip as $ip) {
               if ($PluginFusioninventoryTask->fields['communication'] == 'push') {
                  $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
                  if ($agentStatus) {
                     $a_agentlist[$agent_id] = $ip;
                  }
               } else if ($PluginFusioninventoryTask->fields['communication'] == 'pull') {
                  $a_agentlist[$agent_id] = 1;
               }
            }
         }
      }
      if ($dynagent == '1') {
         $a_agents = $PluginFusioninventoryAgentmodule->getAgentsCanDo('SNMPQUERY');
         foreach($a_agents as $data) {
            if (($count_ip / 10) >= count($a_agentlist)) {
               $a_ip = $PluginFusioninventoryAgent->getIPs($data['id']);
               $PluginFusioninventoryAgent->getFromDB($data['id']);
               foreach($a_ip as $ip) {
                  if ($PluginFusioninventoryTask->fields['communication'] == 'push') {
                     $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
                     if ($agentStatus) {
                        $a_agentlist[$data['id']] = $ip;
                     }
                  } else if ($PluginFusioninventoryTask->fields['communication'] == 'pull') {
                     $a_agentlist[$data['id']] = 1;
                  }
               }
            }
         }         
      }

      // *** Add jobstatus
      if (count($a_agentlist) == '0') {
logInFile("inventory", "unable");
         $a_input = array();
         $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $a_input['state'] = 1;
         $a_input['plugin_fusioninventory_agents_id'] = 0;
         $a_input['itemtype'] = '';
         $a_input['items_id'] = 0;
         $a_input['uniqid'] = $uniqid;
         //$Taskjobstatus_id = $PluginFusioninventoryTaskjobstatus->add($a_input);
            //Add log of taskjob
            $a_input['plugin_fusioninventory_taskjobstatus_id'] = $Taskjobstatus_id;
            $a_input['state'] = 7;
            $a_input['date'] = date("Y-m-d H:i:s");
            $PluginFusioninventoryTaskjoblog->add($a_input);
         $PluginFusioninventoryTaskjobstatus->changeStatusFinish($Taskjobstatus_id,
                                                                 0,
                                                                 '',
                                                                 1,
                                                                 "Unable to find agent to run this job");
         $PluginFusioninventoryTaskjob->fields['status'] = 1;
         $PluginFusioninventoryTaskjob->update($PluginFusioninventoryTaskjob->fields);
      } elseif ($count_device == '0') {
logInFile("inventory", "unable2");
         $a_input = array();
         $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $a_input['state'] = 1;
         $a_input['plugin_fusioninventory_agents_id'] = 0;
         $a_input['itemtype'] = '';
         $a_input['items_id'] = 0;
         $a_input['uniqid'] = $uniqid;
         //$Taskjobstatus_id = $PluginFusioninventoryTaskjobstatus->add($a_input);
            //Add log of taskjob
            $a_input['plugin_fusioninventory_taskjobstatus_id'] = $Taskjobstatus_id;
            $a_input['state'] = 7;
            $a_input['date'] = date("Y-m-d H:i:s");
            $PluginFusioninventoryTaskjoblog->add($a_input);
         $PluginFusioninventoryTaskjobstatus->changeStatusFinish($Taskjobstatus_id,
                                                                 0,
                                                                 '',
                                                                 1,
                                                                 "No devices to inventory");
         $PluginFusioninventoryTaskjob->fields['status'] = 1;
         $PluginFusioninventoryTaskjob->update($PluginFusioninventoryTaskjob->fields);
      } else {
logInFile("inventory", "else");
logInFile("inventory", print_r($a_agentlist, true));
         foreach ($a_agentlist as $agent_id => $ip) {
            //Add jobstatus and put status (waiting on server = 0)
            $a_input = array();
            $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
            $a_input['state'] = 0;
            $a_input['plugin_fusioninventory_agents_id'] = $agent_id;
            $a_input['uniqid'] = $uniqid;
logInFile("inventory", $count_device);
            $alternate = 0;
            for ($d=0; $d < ceil($count_device / count($a_agentlist)); $d++) {
               $getdevice = "NetworkEquipment";
               if ($alternate == "1") {
                  $getdevice = "Printer";
                  $alternate = 0;
               } else {
                  $getdevice = "NetworkEquipment";
                  $alternate++;
               }
               if (count($a_NetworkEquipment) == '0') {
                  $getdevice = "Printer";
               } else if (count($a_Printer) == '0') {
                  $getdevice = "NetworkEquipment";
               }
               $a_input['itemtype'] = $getdevice;

               switch($getdevice) {

                  case 'NetworkEquipment':
                     $a_input['items_id'] = array_pop($a_NetworkEquipment);
                     break;

                  case 'Printer':
                     $a_input['items_id'] = array_pop($a_Printer);
                     break;

               }
               $Taskjobstatus_id = $PluginFusioninventoryTaskjobstatus->add($a_input);
               //Add log of taskjob
                  $a_input['plugin_fusioninventory_taskjobstatus_id'] = $Taskjobstatus_id;
                  $a_input['state'] = 7;
                  $a_input['date'] = date("Y-m-d H:i:s");
                  $PluginFusioninventoryTaskjoblog->add($a_input);
                  unset($a_input['state']);
            }
logInFile("inventory", print_r($a_input, true));
            $PluginFusioninventoryTaskjob->fields['status'] = 1;
            $PluginFusioninventoryTaskjob->update($PluginFusioninventoryTaskjob->fields);


         }
         if ($PluginFusioninventoryTask->fields['communication'] == 'push') {
            foreach ($a_agentlist as $agent_id => $ip) {
               $PluginFusioninventoryAgent->getFromDB($agent_id);
               $PluginFusioninventoryTaskjob->remoteStartAgent($ip, $PluginFusioninventoryAgent->fields['token']);
            }
         }
      }
   }



   // When agent contact server, this function send datas to agent
   /*
    * $itemtype = type of device in definition
    * $array = array with different ID
    *
    */
   function run($itemtype, $a_Taskjobstatus) {
      global $DB;
      
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;
      $PluginFusinvsnmpAgentconfig = new  PluginFusinvsnmpAgentconfig;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange;
      $PluginFusinvsnmpConfigSecurity = new PluginFusinvsnmpConfigSecurity;
      $PluginFusinvsnmpCommunicationSNMP = new PluginFusinvsnmpCommunicationSNMP;
      $PluginFusinvsnmpModel = new PluginFusinvsnmpModel();

      $NetworkEquipment = new NetworkEquipment();
      $Printer = new Printer();
      $NetworkPort = new NetworkPort();
      $PluginFusinvsnmpNetworkEquipment = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
      $PluginFusinvsnmpPrinter = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_printers");

      $modelslistused = array();
      $current = current($a_Taskjobstatus);
      $PluginFusioninventoryAgent->getFromDB($current['plugin_fusioninventory_agents_id']);

      $PluginFusinvsnmpAgentconfig->loadAgentconfig($PluginFusioninventoryAgent->fields['id']);
      $sxml_option = $this->sxml->addChild('OPTION');
      $sxml_option->addChild('NAME', 'SNMPQUERY');
      $sxml_param = $sxml_option->addChild('PARAM');
         $sxml_param->addAttribute('CORE_QUERY', "1");
         $sxml_param->addAttribute('THREADS_QUERY', $PluginFusinvsnmpAgentconfig->fields["threads_snmpquery"]);
         $sxml_param->addAttribute('PID', $current['id']);

      $changestatus = 0;
      foreach ($a_Taskjobstatus as $taskjobstatusdatas) {
         $sxml_device = $sxml_option->addChild('DEVICE');
            switch ($taskjobstatusdatas['itemtype']) {

               case 'NetworkEquipment':
                  $NetworkEquipment->getFromDB($taskjobstatusdatas['items_id']);
                  $sxml_device->addAttribute('TYPE', 'NETWORKING');
                  $sxml_device->addAttribute('ID', $taskjobstatusdatas['items_id']);
                  $sxml_device->addAttribute('IP', $NetworkEquipment->fields['ip']);
                  $a_data = $PluginFusinvsnmpNetworkEquipment->find("`networkequipments_id`='".$taskjobstatusdatas['items_id']."'", "", "1");
                  $data = current($a_data);
                  $sxml_device->addAttribute('AUTHSNMP_ID', $data['plugin_fusinvsnmp_configsecurities_id']);
                  $sxml_device->addAttribute('MODELSNMP_ID', $data['plugin_fusinvsnmp_models_id']);
                  $modelslistused[$data['plugin_fusinvsnmp_models_id']] = 1;
                  break;

               case 'Printer':
                  $a_Printerport = $NetworkPort->find("`itemtype`='Printer' AND `items_id`='".$taskjobstatusdatas['items_id']."'");
                  foreach($a_Printerport as $portdata) {
                     if (!empty($portdata['ip']) AND ($portdata['ip'] != '127.0.0.1')) {
                        $port_ip = $portdata['ip'];
                        break;
                     }
                  }
                  $sxml_device->addAttribute('TYPE', 'PRINTER');
                  $sxml_device->addAttribute('ID', $taskjobstatusdatas['items_id']);
                  $sxml_device->addAttribute('IP', $port_ip);
                  $a_data = $PluginFusinvsnmpPrinter->find("`printers_id`='".$taskjobstatusdatas['items_id']."'", "", "1");
                  $data = current($a_data);
                  $sxml_device->addAttribute('AUTHSNMP_ID', $data['plugin_fusinvsnmp_configsecurities_id']);
                  $sxml_device->addAttribute('MODELSNMP_ID', $data['plugin_fusinvsnmp_models_id']);
                  $modelslistused[$data['plugin_fusinvsnmp_models_id']] = 1;
                  break;
               
            }

            if ($changestatus == '0') {
               $PluginFusioninventoryTaskjobstatus->changeStatus($taskjobstatusdatas['id'], 1);
               $PluginFusioninventoryTaskjoblog->addTaskjoblog($taskjobstatusdatas['id'],
                                       '0',
                                       'PluginFusioninventoryAgent',
                                       '1',
                                       '');
               $changestatus = $PluginFusioninventoryTaskjobstatus->fields['id'];
            } else {
               $PluginFusioninventoryTaskjobstatus->changeStatusFinish($taskjobstatusdatas['id'],
                                                                 $taskjobstatusdatas['items_id'],
                                                                 $taskjobstatusdatas['itemtype'],
                                                                 0,
                                                                 "Merged with ".$changestatus);
            }
      }
      // Add auth
      $snmpauthlist=$PluginFusinvsnmpConfigSecurity->find();
      if (count($snmpauthlist)){
         foreach ($snmpauthlist as $snmpauth){
            $PluginFusinvsnmpCommunicationSNMP->addAuth($sxml_option, $snmpauth['id']);
         }
      }

      $modelslist=$PluginFusinvsnmpModel->find();
      if (count($modelslist)){
         foreach ($modelslist as $model){
            if (isset($modelslistused[$model['id']])) {
               $PluginFusinvsnmpCommunicationSNMP->addModel($sxml_option, $model['id']);
            }
         }
      }

      return $this->sxml;
   }
}

?>