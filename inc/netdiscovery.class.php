<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

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

      // Only rangeip can arrive here

      // Search on rangeip if agent associated with agentdiscovery
      $PluginFusinvsnmpIPRange->getFromDB($items_id);
      if ($PluginFusinvsnmpIPRange->fields['plugin_fusioninventory_agents_id_discover'] != "0") {
         // yes => try to associated to this agent
         if ($PluginFusioninventoryAgentmodule->getAgentsCanDo('NETDISCOVERY', $PluginFusinvsnmpIPRange->fields['plugin_fusioninventory_agents_id_discover'])) {
            $a_ip = $PluginFusioninventoryAgent->getIPs($PluginFusinvsnmpIPRange->fields['plugin_fusioninventory_agents_id_discover']);
            $PluginFusioninventoryAgent->getFromDB($PluginFusinvsnmpIPRange->fields['plugin_fusioninventory_agents_id_discover']);
            foreach($a_ip as $num=>$ip) {
               if ($communication == 'push') {
                  $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
                  if ($agentStatus == true) {
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
      }
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
   }



   // When agent contact server, this function send datas to agent
   // $a_devices = array(itemtype, items_id);
   function run($items_id, $itemtype, $taskjobs_id, $taskjobstatus_id) {
      global $DB;

      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;
      $PluginFusinvsnmpAgentconfig = new  PluginFusinvsnmpAgentconfig;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusinvsnmpIPRange = new PluginFusinvsnmpIPRange;
      $PluginFusinvsnmpConfigSecurity = new PluginFusinvsnmpConfigSecurity;
      $PluginFusinvsnmpCommunicationSNMP = new PluginFusinvsnmpCommunicationSNMP;

      $PluginFusioninventoryTaskjobstatus->getFromDB($taskjobstatus_id);
      $PluginFusioninventoryAgent->getFromDB($PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_agents_id']);

      $PluginFusinvsnmpAgentconfig->loadAgentconfig($PluginFusioninventoryTaskjobstatus->fields['plugin_fusioninventory_agents_id']);

      $sxml_option = $this->sxml->addChild('OPTION');
      $sxml_option->addChild('NAME', 'NETDISCOVERY');
      $sxml_param = $sxml_option->addChild('PARAM');
         $sxml_param->addAttribute('CORE_DISCOVERY', "1");
         $sxml_param->addAttribute('THREADS_DISCOVERY', $PluginFusinvsnmpAgentconfig->fields["threads_netdiscovery"]);
         $sxml_param->addAttribute('PID', $taskjobs_id);
      $PluginFusinvsnmpIPRange->getFromDB($PluginFusioninventoryTaskjobstatus->fields['items_id']);
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