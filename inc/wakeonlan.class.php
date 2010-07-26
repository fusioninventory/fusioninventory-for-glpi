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

class PluginFusioninventoryWakeonlan extends CommonDBTM {

   // Get all devices and put in taskjobstatus each task for each device for each agent
   function prepareRun($itemtype, $items_id) {
      global $DB;
      // Get ids of operating systems which can make real wakeonlan
      $OperatingSystem = new OperatingSystem;
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;

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

      if ($osfind == '()') {
         $osfind = '';
      } else {
         $osfind = 'AND operatingsystems_id IN '.$osfind;
      }

      // Get subnet of device
      $query_subnet = "SELECT * FROM `glpi_networkports`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='".$itemtype."'
            AND `mac`!='' ";
      if ($result_subnet = $DB->query($query_subnet)) {
         while ($data_subnet=$DB->fetch_array($result_subnet)) {
            // TODO : add left join agent and agent exist for computer
            $query = "SELECT * FROM `glpi_networkports`
               LEFT JOIN `glpi_computers` ON items_id=`glpi_computers`.`id`

               WHERE `itemtype`='Computer'
                  AND subnet='".$data_subnet['subnet']."'
                  ".$osfind." ";

            // OR
               // Get config for agent for wakeonlan
                  //find in glpi_plugin_fusioninventory_agentmodules
                  // => liste des agent qui ne sont pas configuré pour le wol
                  // => liste des agent qui sont configure pour le wol
               // Search agent
            $query = "SELECT * FROM glpi_plugin_fusioninventory_agents
               LEFT JOIN 


               WHERE `itemtype`='Computer'";


            if ($result = $DB->query($query)) {
               while ($data=$DB->fetch_array($result)) {
                  $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($data['ip'],0);
                  if ($agentStatus ==  true) {
                     return $data_subnet['subnet']."-agents_id";
                  }
               }
            }
         }
      }
   }



   // When agent contact server, this function send datas to agent
   // $a_devices = array(itemtype, items_id);
   function run($a_devices, $taskjobs_id, $agents_id) {
      global $DB;

      $sxml_option = $this->sxml->addChild('OPTION');
      $sxml_option->addChild('NAME', 'WAKEONLAN');

      foreach ($a_devices as $itemtype=>$items_id) {
         // Get ip
         $a_networkPort = NetworkPort::find("`itemtype`='".$itemtype."' AND `items_id`='".$items_id."' ");
         foreach ($a_networkPort as $networkPort_id=>$data) {
            if ($data['ip'] != "127.0.0.1") {
               $sxml_param = $sxml_option->addChild('PARAM');
               $sxml_param->addAttribute('MAC', $data['mac']);
               $sxml_param->addAttribute('IP', $data['ip']);

               // Update taskjobstatus (state = 1 : running);
               $query_update = "UPDATE `glpi_plugin_fusioninventory_taskjobstatus`
                  SET `state`='1'
                  WHERE `plugin_fusioninventory_taskjobs_id`='".$taskjobs_id."'
                  AND `items_id`='".$items_id."'
                  AND `itemtype`='".$itemtype."'
                  AND `plugin_fusioninventory_agents_id`='".$agents_id."' ";
               $DB->query($query_update);
            }
         }
      }
   }
}

?>