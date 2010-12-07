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
   function prepareRun($itemtype, $items_id, $communication) {
      global $DB;
      
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange;
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;
      
      if ($items_id == '-1') {
         // no => search an agent can do snmp
         $a_agents = $PluginFusioninventoryAgentmodule->getAgentsCanDo('NETDISCOVERY');
         foreach($a_agents as $agents_id=>$data) {
            $a_ip = $PluginFusioninventoryAgent->getIPs($agents_id);
            $PluginFusioninventoryAgent->getFromDB($agents_id);
            foreach($a_ip as $num=>$ip) {
               if ($communication == 'push') {
                  $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
                  if ($agentStatus ==  true) {
                     $return = array();
                     $return['ip'] = $ip;
                     $return['token'] = $PluginFusioninventoryAgent->fields['token'];
                     $return['agents_id'] = $PluginFusioninventoryAgent->fields['id'];
                     return $return;
                  }
               } else  if ($communication == 'pull') {
                  $return = array();
                  $return['ip'] = $ip;
                  $return['token'] = $PluginFusioninventoryAgent->fields['token'];
                  $return['agents_id'] = $PluginFusioninventoryAgent->fields['id'];
                  return $return;
               }
            }
         }
      } else {
         $PluginFusioninventoryAgent->getFromDB($items_id);
         $return = array();
         $return['token'] = $PluginFusioninventoryAgent->fields['token'];
         $return['agents_id'] = $items_id;
         if ($communication == 'push') {
            $a_ip = $PluginFusioninventoryAgent->getIPs($items_id);
            $PluginFusioninventoryAgent->getFromDB($items_id);
            foreach($a_ip as $num=>$ip) {
               $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
               if ($agentStatus ==  true) {
                  $return['ip'] = $ip;
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
         $sxml_rangeip->addAttribute('IPSTART', $PluginFusinvsnmpIPRange->fields["ip_start"]);
         $sxml_rangeip->addAttribute('IPEND', $PluginFusinvsnmpIPRange->fields["ip_end"]);
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