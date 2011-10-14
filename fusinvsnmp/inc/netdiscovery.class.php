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
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

require_once(GLPI_ROOT."/plugins/fusioninventory/inc/communication.class.php");

class PluginFusinvsnmpNetdiscovery extends PluginFusioninventoryCommunication {

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

      $communication = $PluginFusioninventoryTask->fields['communication'];
      
      //list all iprange
      $a_iprange = importArrayFromDB($PluginFusioninventoryTaskjob->fields['definition']);
      $count_ip = 0;
      $a_iprangelist = array();
      $a_subnet_nbip = array();
      foreach ($a_iprange as $iprange) {
         $iprange_id = current($iprange);
         $a_iprangelist[] = $iprange_id;
         $PluginFusinvsnmpIPRange->getFromDB($iprange_id);
         $s = $PluginFusinvsnmpIPRange->getIp2long($PluginFusinvsnmpIPRange->fields['ip_start']);
         $e = $PluginFusinvsnmpIPRange->getIp2long($PluginFusinvsnmpIPRange->fields['ip_end']);
         $a_subnet_nbip[$iprange_id] = $e-$s;
         $count_ip += $e-$s;
      }

      //list all agents
      $a_agent = importArrayFromDB($PluginFusioninventoryTaskjob->fields['action']);
      $dynagent = 0;
      $a_agentlist = array();
      foreach ($a_agent as $agent) {
         $agent_id = current($agent);
         if ($agent_id == '.1') {
            $dynagent = 1;
         } else if ($agent_id == '.2') {
            $dynagent = 2;
         } else {
            // Detect if agent exists
            if ($PluginFusioninventoryAgent->getFromDB($agent_id)) {
               if ($PluginFusioninventoryTask->fields['communication'] == 'pull') {
                  $a_agentlist[$agent_id] = 1;
               } else {
                  $a_ip = $PluginFusioninventoryAgent->getIPs($agent_id);
                  $PluginFusioninventoryAgent->getFromDB($agent_id);
                  foreach($a_ip as $ip) {
                     $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
                     if ($agentStatus) {
                        $a_agentlist[$agent_id] = $ip;
                     }
                  }
               }
            }
         }
      }
      if ($dynagent == '1') {
         $a_agents = $PluginFusioninventoryAgentmodule->getAgentsCanDo('NETDISCOVERY');
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


      if ($dynagent == '2') {
         // Dynamic with subnet
         $PluginFusinvsnmpSnmpinventory = new PluginFusinvsnmpSnmpinventory();
         foreach($a_subnet_nbip as $iprange_id=>$nbips) {
            //$maxagentpossible = $nbips/10;
            $PluginFusinvsnmpIPRange->getFromDB($iprange_id);
            $a_agentListComplete = array();
            $a_agentList = $PluginFusinvsnmpSnmpinventory->getAgentsSubnet($nbips, "push", "",
                                                      $PluginFusinvsnmpIPRange->fields['ip_start'],
                                                      $PluginFusinvsnmpIPRange->fields['ip_end']);
            if (isset($a_agentList)) {
               $a_agentListComplete = array_merge($a_agentListComplete, $a_agentList);
            }

            if (!isset($a_agentListComplete) or empty($a_agentListComplete)) {
               $a_input = array();
               $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
               $a_input['state'] = 1;
               $a_input['plugin_fusioninventory_agents_id'] = 0;
               $a_input['itemtype'] = 'PluginFusinvsnmpIPRange';
               $a_input['items_id'] = $iprange_id;
               $a_input['uniqid'] = $uniqid;
               $Taskjobstatus_id = $PluginFusioninventoryTaskjobstatus->add($a_input);
                  //Add log of taskjob
                  $a_input['plugin_fusioninventory_taskjobstatus_id'] = $Taskjobstatus_id;
                  $a_input['state'] = 7;
                  $a_input['date'] = date("Y-m-d H:i:s");
                  $PluginFusioninventoryTaskjoblog->add($a_input);

               $PluginFusioninventoryTaskjobstatus->changeStatusFinish($Taskjobstatus_id,
                                                                       0,
                                                                       'PluginFusinvsnmpIPRange',
                                                                       1,
                                                                       "Unable to find agent to run this job");
               $input_taskjob = array();
               $input_taskjob['id'] = $PluginFusioninventoryTaskjob->fields['id'];
               $input_taskjob['status'] = 1;
               $PluginFusioninventoryTaskjob->update($input_taskjob);
            } else {
               $s = $PluginFusinvsnmpIPRange->getIp2long($PluginFusinvsnmpIPRange->fields['ip_start']);
               $e = $PluginFusinvsnmpIPRange->getIp2long($PluginFusinvsnmpIPRange->fields['ip_end']);
               $nbIpAgent = ceil(($e-$s) / count($a_agentListComplete));
               $iptimes = 0;

               foreach ($a_agentListComplete as $agent_id) {

                  $_SESSION['glpi_plugin_fusioninventory']['agents'][$agent_id] = 1;
                  //Add jobstatus and put status (waiting on server = 0)
                  $a_input = array();
                  $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
                  $a_input['state'] = 0;
                  $a_input['plugin_fusioninventory_agents_id'] = $agent_id;
                  $a_input['itemtype'] = 'PluginFusinvsnmpIPRange';
                  $a_input['uniqid'] = $uniqid;

                  $a_input['items_id'] = $iprange_id;
                  if (($iptimes + $nbIpAgent) > ($e-$s)) {
                     $a_input['specificity'] = $iptimes."-".($e-$s);
                  } else {
                     $a_input['specificity'] = $iptimes."-".($iptimes + $nbIpAgent);
                  }
                  $Taskjobstatus_id = $PluginFusioninventoryTaskjobstatus->add($a_input);
                     //Add log of taskjob
                     $a_input['plugin_fusioninventory_taskjobstatus_id'] = $Taskjobstatus_id;
                     $a_input['state'] = 7;
                     $a_input['date'] = date("Y-m-d H:i:s");
                     $PluginFusioninventoryTaskjoblog->add($a_input);
                     unset($a_input['state']);
                  $iptimes += $nbIpAgent + 1;
                  if (($iptimes) >= ($e-$s+1)) {
                     break;
                  }
                  $input_taskjob = array();
                  $input_taskjob['id'] = $PluginFusioninventoryTaskjob->fields['id'];
                  $input_taskjob['status'] = 1;
                  $PluginFusioninventoryTaskjob->update($input_taskjob);
               }               
            }
         }

      // *** Add jobstatus
      } else if (count($a_agentlist) == 0) {
         $a_input = array();
         $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $a_input['state'] = 1;
         $a_input['plugin_fusioninventory_agents_id'] = 0;
         $a_input['itemtype'] = 'PluginFusinvsnmpIPRange';
         $a_input['items_id'] = 0;
         $a_input['uniqid'] = $uniqid;
         $Taskjobstatus_id = $PluginFusioninventoryTaskjobstatus->add($a_input);
            //Add log of taskjob
            $a_input['plugin_fusioninventory_taskjobstatus_id'] = $Taskjobstatus_id;
            $a_input['state'] = 7;
            $a_input['date'] = date("Y-m-d H:i:s");
            $PluginFusioninventoryTaskjoblog->add($a_input);

         $PluginFusioninventoryTaskjobstatus->changeStatusFinish($Taskjobstatus_id,
                                                                 0,
                                                                 'PluginFusinvsnmpIPRange',
                                                                 1,
                                                                 "Unable to find agent to run this job");
         $input_taskjob = array();
         $input_taskjob['id'] = $PluginFusioninventoryTaskjob->fields['id'];
         $input_taskjob['status'] = 1;
         $PluginFusioninventoryTaskjob->update($input_taskjob);
      } else {
         $iptimes = 0;
         $nbIpadded = 0;
         $iptimes = 0;
         $break = 0;
         $numberIpByAgent = ceil($count_ip / (count($a_agentlist)));
         $a_iprangelistTmp = $a_iprangelist;
         $ip_id = array_shift($a_iprangelistTmp);
         foreach ($a_agentlist as $agent_id => $ip) {

            //Add jobstatus and put status (waiting on server = 0)
            $a_input = array();
            $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
            $a_input['state'] = 0;
            $a_input['plugin_fusioninventory_agents_id'] = $agent_id;
            $a_input['itemtype'] = 'PluginFusinvsnmpIPRange';
            $a_input['uniqid'] = $uniqid;

//            $nbIpAgent = $numberIpByAgent;
            $nbIpadded = 0;
            foreach($a_iprangelist as $iprange_id) {
               if ($ip_id == $iprange_id) {
                  $PluginFusinvsnmpIPRange->getFromDB($iprange_id);
                  $s = $PluginFusinvsnmpIPRange->getIp2long($PluginFusinvsnmpIPRange->fields['ip_start']);
                  $e = $PluginFusinvsnmpIPRange->getIp2long($PluginFusinvsnmpIPRange->fields['ip_end']);
                  if ($communication == "push") {
                     $_SESSION['glpi_plugin_fusioninventory']['agents'][$agent_id] = 1;
                  }

                  $a_input['items_id'] = $iprange_id;
                  $nbIpAgent = $numberIpByAgent - $nbIpadded;
                  if (($iptimes + $nbIpAgent) > ($e-$s)) {
                     $a_input['specificity'] = $iptimes."-".($e-$s);
                     $nbIpadded = ($e-$s) - $iptimes;
                     $ip_id = array_shift($a_iprangelistTmp);
                     $iptimes = 0;
                  } else {
                     $a_input['specificity'] = $iptimes."-".($iptimes + $nbIpAgent);
                     $iptimes += $nbIpAgent+1;
                     $nbIpadded = 0;
                     $break = 1;
                  }
                  $Taskjobstatus_id = $PluginFusioninventoryTaskjobstatus->add($a_input);
                     //Add log of taskjob
                     $a_input['plugin_fusioninventory_taskjobstatus_id'] = $Taskjobstatus_id;
                     $a_input['state'] = 7;
                     $a_input['date'] = date("Y-m-d H:i:s");
                     $PluginFusioninventoryTaskjoblog->add($a_input);
                     unset($a_input['state']);
               }
            }
            $input_taskjob = array();
            $input_taskjob['id'] = $PluginFusioninventoryTaskjob->fields['id'];
            $input_taskjob['status'] = 1;
            $PluginFusioninventoryTaskjob->update($input_taskjob);
         }
      }
   }



   // When agent contact server, this function send datas to agent
   /*
    * $a_Taskjobstatus array with all taskjobstatus
    *
    */
   function run($a_Taskjobstatus) {
      global $DB;

      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;
      $PluginFusinvsnmpAgentconfig = new  PluginFusinvsnmpAgentconfig;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange;
      $PluginFusinvsnmpConfigSecurity = new PluginFusinvsnmpConfigSecurity;
      $PluginFusinvsnmpCommunicationSNMP = new PluginFusinvsnmpCommunicationSNMP;


      $current = current($a_Taskjobstatus);
      $PluginFusioninventoryAgent->getFromDB($current['plugin_fusioninventory_agents_id']);

      $PluginFusinvsnmpAgentconfig->loadAgentconfig($PluginFusioninventoryAgent->fields['id']);
      $sxml_option = $this->sxml->addChild('OPTION');
      $sxml_option->addChild('NAME', 'NETDISCOVERY');

      $a_versions = importArrayFromDB($PluginFusioninventoryAgent->fields["version"]);
      if (((isset($a_versions["NETDISCOVERY"])) AND ($a_versions["NETDISCOVERY"] >= 1.3))
              OR !isset($a_versions["NETDISCOVERY"])) {
         $sxml_option->addChild('DICOHASH', md5_file(GLPI_ROOT."/plugins/fusinvsnmp/tool/discovery.xml"));
      }
      if (($PluginFusinvsnmpAgentconfig->fields["senddico"] == "1")) {
         
         if (((isset($a_versions["NETDISCOVERY"]))
                 AND ($a_versions["NETDISCOVERY"] >= 1.3))) {

            $sxml_option->addChild('DICO', file_get_contents(GLPI_ROOT."/plugins/fusinvsnmp/tool/discovery.xml"));
         }
         $input = array();
         $input['id'] = $PluginFusinvsnmpAgentconfig->fields['id'];
         $input["senddico"] = "0";
         $PluginFusinvsnmpAgentconfig->update($input);
      }

      $sxml_param = $sxml_option->addChild('PARAM');
         $sxml_param->addAttribute('CORE_DISCOVERY', "1");
         $sxml_param->addAttribute('THREADS_DISCOVERY', $PluginFusinvsnmpAgentconfig->fields["threads_netdiscovery"]);
         $sxml_param->addAttribute('PID', $current['id']);

      $changestatus = 0;
      foreach ($a_Taskjobstatus as $taskjobstatusdatas) {
         $sxml_rangeip = $sxml_option->addChild('RANGEIP');
            $PluginFusioninventoryTaskjob->getFromDB($taskjobstatusdatas['plugin_fusioninventory_taskjobs_id']);
            $PluginFusioninventoryTaskjobstatus->getFromDB($taskjobstatusdatas['id']);
            $PluginFusinvsnmpIPRange->getFromDB($taskjobstatusdatas['items_id']);

            $sxml_rangeip->addAttribute('ID', $PluginFusinvsnmpIPRange->fields['id']);

            if (!is_null($PluginFusioninventoryTaskjobstatus->fields['specificity'])) {
               $a_split = explode("-", $PluginFusioninventoryTaskjobstatus->fields['specificity']);

               $first_ip = $PluginFusinvsnmpIPRange->getIp2long($PluginFusinvsnmpIPRange->fields["ip_start"]);

               $last_ip = long2ip($first_ip + $a_split[1]);
               $first_ip = long2ip($first_ip + $a_split[0]);
               $sxml_rangeip->addAttribute('IPSTART', $first_ip);
               $sxml_rangeip->addAttribute('IPEND', $last_ip);
            } else {
               $sxml_rangeip->addAttribute('IPSTART', $PluginFusinvsnmpIPRange->fields["ip_start"]);
               $sxml_rangeip->addAttribute('IPEND', $PluginFusinvsnmpIPRange->fields["ip_end"]);
            }
            $sxml_rangeip->addAttribute('ENTITY', $PluginFusinvsnmpIPRange->fields["entities_id"]);

            if ($changestatus == '0') {
               $PluginFusioninventoryTaskjobstatus->changeStatus($PluginFusioninventoryTaskjobstatus->fields['id'], 1);
               $PluginFusioninventoryTaskjoblog->addTaskjoblog($PluginFusioninventoryTaskjobstatus->fields['id'],
                                       '0',
                                       'PluginFusioninventoryAgent',
                                       '1',
                                       '');
               $changestatus = $PluginFusioninventoryTaskjobstatus->fields['id'];
            } else {
               $PluginFusioninventoryTaskjobstatus->changeStatusFinish($PluginFusioninventoryTaskjobstatus->fields['id'],
                                                                 $taskjobstatusdatas['items_id'],
                                                                 $taskjobstatusdatas['itemtype'],
                                                                 0,
                                                                 "Merged with ".$changestatus);
            }
      }
      $snmpauthlist=$PluginFusinvsnmpConfigSecurity->find();
      if (count($snmpauthlist)){
         foreach ($snmpauthlist as $snmpauth){
            $PluginFusinvsnmpCommunicationSNMP->addAuth($sxml_option, $snmpauth['id']);
         }
      }
      return $this->sxml;
   }
}

?>