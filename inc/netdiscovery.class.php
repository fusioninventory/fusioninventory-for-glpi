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

class PluginFusinvsnmpNetdiscovery extends PluginFusioninventoryCommunication {

   // Get all devices and put in taskjobstatus each task for each device for each agent
   function prepareRun($itemtype, $items_id, $communication, $taskjobs_id) {
      global $DB;
      
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange;
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;

      // Count ips of this range
      $PluginFusioninventoryTaskjob->getFromDB($taskjobs_id);
      $PluginFusinvsnmpIPRange->getFromDB($PluginFusioninventoryTaskjob->fields['argument']);
      $s = ip2long($PluginFusinvsnmpIPRange->fields['ip_start']);
      $e = ip2long($PluginFusinvsnmpIPRange->fields['ip_end']);
      $count_ip = $e-$s+1;

      if ($items_id == '.1') {
         // no => search an agent can do snmp
         $a_agents = $PluginFusioninventoryAgentmodule->getAgentsCanDo('NETDISCOVERY');
         $i = 0;
         $return = array();
         foreach($a_agents as $data) {
            if (($count_ip / 10) >= $i) {
               $a_ip = $PluginFusioninventoryAgent->getIPs($data['id']);
               $PluginFusioninventoryAgent->getFromDB($data['id']);
               foreach($a_ip as $ip) {
                  if ($communication == 'push') {
                     $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
                     if ($agentStatus) {
                        $return[$i]['ip'] = $ip;
                        $return[$i]['token'] = $PluginFusioninventoryAgent->fields['token'];
                        $return[$i]['agents_id'] = $PluginFusioninventoryAgent->fields['id'];
                        // Distapch range ip into many range like you have agents
                        $i++;
                     }
                  } else  if ($communication == 'pull') {
                     $return[0]['ip'] = $ip;
                     $return[0]['token'] = $PluginFusioninventoryAgent->fields['token'];
                     $return[0]['agents_id'] = $PluginFusioninventoryAgent->fields['id'];
                     return $return;
                  }
               }
            }
         }
         if (count($return) > 0) {
            foreach ($return as $num => $datas) {
               $return[$num]['specificity'] = $num."-".ceil($count_ip / count($return));
            }
            return $return;
         }
      } else {
         $PluginFusioninventoryAgent->getFromDB($items_id);
         $return = array();
         $return[0]['token'] = $PluginFusioninventoryAgent->fields['token'];
         $return[0]['agents_id'] = $items_id;
         if ($communication == 'push') {
            $a_ip = $PluginFusioninventoryAgent->getIPs($items_id);
            $PluginFusioninventoryAgent->getFromDB($items_id);
            foreach($a_ip as $num=>$ip) {
               $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
               if ($agentStatus ==  true) {
                  $return[0]['ip'] = $ip;
                  return $return;
               }
            }
         }
         return $return;
      }
   }



   // When agent contact server, this function send datas to agent
   // $a_devices = array(itemtype, items_id);
   function run($items_id, $itemtype, $taskjobs_id, $taskjobstatus_id) {
      global $DB;

      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;
      $PluginFusinvsnmpAgentconfig = new  PluginFusinvsnmpAgentconfig;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange;
      $PluginFusinvsnmpConfigSecurity = new PluginFusinvsnmpConfigSecurity;
      $PluginFusinvsnmpCommunicationSNMP = new PluginFusinvsnmpCommunicationSNMP;

      $PluginFusioninventoryTaskjobstatus->getFromDB($taskjobstatus_id);
      $PluginFusioninventoryAgent->getFromDB($PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_agents_id']);
      $PluginFusioninventoryTaskjob->getFromDB($PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_taskjobs_id']);

      $PluginFusinvsnmpAgentconfig->loadAgentconfig($PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_agents_id']);

      $sxml_option = $this->sxml->addChild('OPTION');
      $sxml_option->addChild('NAME', 'NETDISCOVERY');
      $sxml_param = $sxml_option->addChild('PARAM');
         $sxml_param->addAttribute('CORE_DISCOVERY', "1");
         $sxml_param->addAttribute('THREADS_DISCOVERY', $PluginFusinvsnmpAgentconfig->fields["threads_netdiscovery"]);
         $sxml_param->addAttribute('PID', $taskjobs_id);

         $PluginFusinvsnmpIPRange->getFromDB($PluginFusioninventoryTaskjob->fields['argument']);
      $sxml_rangeip = $sxml_option->addChild('RANGEIP');
         $sxml_rangeip->addAttribute('ID', $PluginFusinvsnmpIPRange->fields['id']);

         if (!is_null($PluginFusioninventoryTaskjobstatus->fields['specificity'])) {
            $a_split = explode("-", $PluginFusioninventoryTaskjobstatus->fields['specificity']);
            $first_ip = ip2long($PluginFusinvsnmpIPRange->fields["ip_start"]);
            $first_ip = long2ip($first_ip + ($a_split[0] * $a_split[1]));
            $last_ip = long2ip(ip2long($first_ip) + $a_split[1] - 1);
            $sxml_rangeip->addAttribute('IPSTART', $first_ip);
            $sxml_rangeip->addAttribute('IPEND', $last_ip);
         } else {
            $sxml_rangeip->addAttribute('IPSTART', $PluginFusinvsnmpIPRange->fields["ip_start"]);
            $sxml_rangeip->addAttribute('IPEND', $PluginFusinvsnmpIPRange->fields["ip_end"]);
         }
         $sxml_rangeip->addAttribute('ENTITY', $PluginFusinvsnmpIPRange->fields["entities_id"]);

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