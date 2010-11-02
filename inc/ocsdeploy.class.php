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

class PluginFusinvdeployOcsdeploy extends PluginFusioninventoryCommunication {

   // Get all devices and put in taskjobstatus each task for each device for each agent
   function prepareRun($itemtype, $items_id, $communication) {
      global $DB;


      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;

      switch($itemtype) {

         case 'Computer':

            break;

         case 'rules':

            break;

         case 'devicegroups':

            break;

      }


      // Add package to computer/agent



         // Search if agent is associate to this computer
         if ($agent_id = $PluginFusioninventoryAgent->getAgentWithComputerid($items_id)) {
            // Verify agent can do ocsdeploy
            if ($PluginFusioninventoryAgentmodule->getAgentsCanDo('OCSDEPLOY', $agent_id)) {

               // verify agent answer
               $a_ip = $PluginFusioninventoryAgent->getIPs($agent_id);
               $PluginFusioninventoryAgent->getFromDB($agent_id);
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
                  } else if ($communication == 'pull') {
                     $return = array();
                     $return['ip'] = $ip;
                     $return['token'] = $PluginFusioninventoryAgent->fields['token'];
                     $return['agents_id'] = $PluginFusioninventoryAgent->fields['id'];
                     return $return;
                  }
               }
               // Error, Agent not remotly accessible
            } else {
               // Error deploy not actived on this agent
            }
         } else {
            // Error, no agent available
         }
    }



   // When agent contact server, this function send datas to agent
   // $a_devices = array(itemtype, items_id);
   function run($items_id, $itemtype, $taskjobs_id, $taskjobstatus_id) {
      global $DB;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');

 /*
 *<OPTION>
 *  <NAME>DOWNLOAD</NAME>
 *  <PARAM FRAG_LATENCY="10" PERIOD_LATENCY="1" TIMEOUT="30" ON="1" TYPE="CONF" CYCLE_LATENCY="60" PERIOD_LENGTH="10" />
 *  <PARAM ID="1283788502" CERT_PATH="INSTALL_PATH" PACK_LOC="127.0.0.1/DOWNLOAD" CERT_FILE="INSTALL_PATH/cacert.pem" TYPE="PACK" INFO_LOC="127.0.0.1/DOWNLOAD" />
 *</OPTION>
 *
 */
      $PluginFusinvdeployPackage = new PluginFusinvdeployPackage;
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;


      $sxml_option = $this->sxml->addChild('OPTION');
      $sxml_option->addChild('NAME', 'DOWNLOAD');
      $sxml_param = $sxml_option->addChild('PARAM');
         $sxml_param->addAttribute('FRAG_LATENCY', "10");
         $sxml_param->addAttribute('PERIOD_LATENCY', "1");
         $sxml_param->addAttribute('TIMEOUT', "1");
         $sxml_param->addAttribute('ON', "1");
         $sxml_param->addAttribute('TYPE', "CONF");
         $sxml_param->addAttribute('CYCLE_LATENCY', "60");
         $sxml_param->addAttribute('PERIOD_LENGTH', "10");

      // Get datas from package
      $sxml_param = $sxml_option->addChild('PARAM');
         $PluginFusioninventoryTaskjob->getFromDB($taskjobs_id);
         $PluginFusinvdeployPackage->getFromDB($PluginFusioninventoryTaskjob->fields['argument']);
         $sxml_param->addAttribute('ID', $PluginFusinvdeployPackage->fields['id']);
         $sxml_param->addAttribute('CERT_PATH', "INSTALL_PATH");
         // PACK_LOC="127.0.0.1/DOWNLOAD"
         $sxml_param->addAttribute('PACK_LOC', $PluginFusioninventoryConfig->getValue($plugins_id, 'glpi_path')."/plugins/fusinvdeploy/front/downloadfragments.php?file=");
         $sxml_param->addAttribute('CERT_FILE', "INSTALL_PATH/cacert.pem");
         $sxml_param->addAttribute('TYPE', "PACK");
         $sxml_param->addAttribute('INFO_LOC', $PluginFusioninventoryConfig->getValue($plugins_id, 'glpi_path')."/plugins/fusinvdeploy/front/downloadfragments.php?info=");
         $sxml_param->addAttribute('FORCEREPLAY', "1");
         return $this->sxml;

   }
}

?>