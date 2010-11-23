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

class PluginFusioninventoryWakeonlan extends PluginFusioninventoryCommunication {

   // Get all devices and put in taskjobstatus each task for each device for each agent
   function prepareRun($itemtype, $items_id, $communication) {
      global $DB;
      // Get ids of operating systems which can make real wakeonlan
      $OperatingSystem = new OperatingSystem;
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;

      $a_os = $OperatingSystem->find(" `name` LIKE '%Linux%' ");
      $osfind = '(';
      $i = 0;
      foreach ($a_os as $os_id=>$data) {
         $comma = '';
         if ($i > 0) {
            $comma = ',';
         }
         $osfind .= $comma.$os_id;
         $i++;
      }
      $osfind .= ')';
      $pass_count = 1;
      if ($osfind == '()') {
         $osfind = '';
      } else {
         $pass_count++;
         $osfind = 'AND operatingsystems_id IN '.$osfind;
      }
      for ($pass = 0; $pass < $pass_count; $pass++) {
         if ($pass == "1") {
            // It's not linux
            $osfind = str_replace('AND operatingsystems_id IN ', 'AND operatingsystems_id NOT IN ', $osfind);
         }

         // Get subnet of device
         $query_subnet = "SELECT * FROM `glpi_networkports`
            WHERE `items_id`='".$items_id."'
               AND `itemtype`='".$itemtype."'
               AND `mac`!='' ";
         if ($result_subnet = $DB->query($query_subnet)) {
            while ($data_subnet=$DB->fetch_array($result_subnet)) {
               $agentModule = $PluginFusioninventoryAgentmodule->getActivationExceptions('WAKEONLAN');
               $where = "";
               if ($agentModule['is_active'] == 0) {
                  $a_agentList = importArrayFromDB($agentModule['exceptions']);
                  if (count($a_agentList) > 0) {
                     $where = " AND `glpi_plugin_fusioninventory_agents`.`ID` IN (";
                     $i = 0;
                     $sep  = '';
                     foreach ($a_agentList as $agent_id=>$num) {
                        if ($i> 1) {
                           $sep  = ',';
                        }
                        $where .= $agent_id.$sep;
                        $i++;
                     }
                     $where .= ") ";
                  }
               } else {
                  $a_agentList = importArrayFromDB($agentModule['exceptions']);
                  if (count($a_agentList) > 0) {
                     $where = " AND `glpi_plugin_fusioninventory_agents`.`ID` NOT IN (";
                     $i = 0;
                     $sep  = '';
                     foreach ($a_agentList as $agent_id=>$num) {
                        if ($i> 1) {
                           $sep  = ',';
                        }
                        $where .= $agent_id.$sep;
                        $i++;
                     }
                     $where .= ") ";
                  }
               }

               $query = "SELECT `glpi_plugin_fusioninventory_agents`.`id` as `a_id`, ip, subnet, token FROM `glpi_plugin_fusioninventory_agents`
                  LEFT JOIN `glpi_networkports` ON `glpi_networkports`.`items_id` = `glpi_plugin_fusioninventory_agents`.`items_id`
                  LEFT JOIN `glpi_computers` ON `glpi_computers`.`id` = `glpi_plugin_fusioninventory_agents`.`items_id`
                  WHERE `glpi_networkports`.`itemtype`='Computer'
                     AND subnet='".$data_subnet['subnet']."'
                     ".$osfind."
                     ".$where." ";
               if ($result = $DB->query($query)) {
                  while ($data=$DB->fetch_array($result)) {
                     if ($communication == 'push') {
                        $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($data['ip'],0);
                        if ($agentStatus ==  true) {
                           $return = array();
                           $return['ip'] = $data['ip'];
                           $return['token'] = $data['token'];
                           $return['agents_id'] = $data['a_id'];
                           return $return;
                        }
                     } else if ($communication == 'pull') {
                        $return = array();
                        $return['ip'] = $data['ip'];
                        $return['token'] = $data['token'];
                        $return['agents_id'] = $data['a_id'];
                        return $return;
                     }
                  }
               }
            }
         }
      }
   }



   // When agent contact server, this function send datas to agent
   // $a_devices = array(itemtype, items_id);
   function run($items_id, $itemtype, $taskjobs_id, $taskjobstatus_id) {
      global $DB;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $NetworkPort                        = new NetworkPort;

      $sxml_option = $this->sxml->addChild('OPTION');
      $sxml_option->addChild('NAME', 'WAKEONLAN');

      // Get ip
      $a_networkPort = $NetworkPort->find("`itemtype`='".$itemtype."' AND `items_id`='".$items_id."' ");
      foreach ($a_networkPort as $networkPort_id=>$data) {
         if ($data['ip'] != "127.0.0.1") {
            $sxml_param = $sxml_option->addChild('PARAM');
            $sxml_param->addAttribute('MAC', $data['mac']);
            $sxml_param->addAttribute('IP', $data['ip']);

            // Update taskjobstatus (state = 3 : finish); Because we haven't return of agent on this action
            $PluginFusioninventoryTaskjobstatus->changeStatusFinish($taskjobs_id, $items_id, $itemtype, 0, 'WakeOnLan have not return state', 1);
         }
      }
      return $this->sxml;
   }
}

?>