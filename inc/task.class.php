<?php

/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusionInventoryTask extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_task";
      $this->type = PLUGIN_FUSIONINVENTORY_TASK;
	}


   function Counter($agent_id, $action) {
      global $DB;

      $count = 0;
      $query = "SELECT COUNT(*) as count FROM `glpi_plugin_fusioninventory_task`
         WHERE `agent_id`='".$agent_id."'
            AND `action`='".$action."' ";

      if ($result = $DB->query($query)) {
         $res = $DB->fetch_assoc($result);
         $count = $res["count"];
      }
      return $count;
   }


   function ListTask($agent_id, $action) {
      global $DB;

      $tasks = array();
      $query = "SELECT glpi_plugin_fusioninventory_task.id as ID, param, ifaddr, single,
            glpi_plugin_fusioninventory_task.on_device as on_device, glpi_plugin_fusioninventory_task.device_type as device_type
            FROM `glpi_plugin_fusioninventory_task`
         INNER JOIN glpi_networking_ports on (glpi_plugin_fusioninventory_task.on_device=glpi_networking_ports.on_device
                                             AND glpi_plugin_fusioninventory_task.device_type=glpi_networking_ports.device_type)
         WHERE `agent_id`='".$agent_id."'
            AND `action`='".$action."'
            AND `ifaddr`!='127.0.0.1'";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($data=$DB->fetch_array($result)) {
               $tasks[$data["ID"]] = $data;
               $type='';
               switch ($tasks[$data["ID"]]["device_type"]) {
                  case "networking":
                     $tasks[$data["ID"]]["device_type"]='NETWORKING';
                     break;
                  case "printer":
                     $tasks[$data["ID"]]["device_type"]='PRINTER';
                     break;
               }
            }
         }
      }
      return $tasks;
   }


   function formAddTask($target, $input=array()) {
      global $LANG;

      $pta = new PluginFusionInventoryAgents;
      $ptcm = new PluginFusionInventoryConfigModules;
      if ((!$ptcm->isActivated('remotehttpagent')) AND (!plugin_fusioninventory_HaveRight("remotecontrol","w"))) {
         return;
      }
      // TODO: detect if task yet present in MySQL task table

      echo "<br/><div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";
		echo "<table  class='tab_cadre_fixe'>";
      
		echo "<tr><th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["task"][4];
		echo " :</th></tr>";

      echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo $LANG['plugin_fusioninventory']["task"][5];
		echo "</td>";
      
		echo "<td align='center'>";
      dropdownValue($pta->table,'agentocs','',1,1);
		echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo $LANG['plugin_fusioninventory']["task"][7];
		echo "</td>";

		echo "<td align='center'>";
      $active_valid = 0;
      if (isset($input['up']) AND (isset($input['agentocs']))) {
         
      }

      plugin_fusioninventory_disableDebug();
      if(!($fp = fsockopen("192.168.0.201", 62354, $errno, $errstr, 1))) {
          echo "<b>".$LANG['plugin_fusioninventory']["task"][9]."</b>";
      } else {
          echo "<b>".$LANG['plugin_fusioninventory']["task"][8]."</b>";
          $active_valid = 1;
          fclose($fp);
      }
      plugin_fusioninventory_reenableusemode();

		echo "</td>";
      echo "</tr>";


      echo "<tr class='tab_bg_2'>";
		echo "<td align='center'>";
      echo "<input type='submit' name='up' value=\"".$LANG['plugin_fusioninventory']["task"][6]."\" class='submit'>";
		echo "</td>";
      
		echo "<td align='center'>";
      if ($active_valid == '1') {
         echo "<input type='submit' name='add' value=\"".$LANG['buttons'][2]."\" class='submit'>";
      } else {
         echo "<input type='submit' name='add' value=\"".$LANG['buttons'][2]."\"
            STYLE='font-size: 11px; border: 1px solid #888888; cursor:pointer; background:  url(\"".GLPI_ROOT."/plugins/fusioninventory/pics/fond_form_off.png\") repeat-x'
               disabled='disabled'>";
      }
		echo "</td>";
      echo "</tr>";
  
      echo "</table>";
      echo "</form>";
      echo "</div>";
      echo "<br/>";
   }



   function addTask($device_id, $device_type, $action, $agent_id, $param="") {
      $ptcm = new PluginFusionInventoryConfigModules;
      if ((!$ptcm->isActivated('remotehttpagent')) AND (!plugin_fusioninventory_HaveRight("remotecontrol","w"))) {
         return false;
      }
      if ($param == PLUGIN_FUSIONINVENTORY_SNMP_AGENTS) {
         $on_device = 0;
         $device_typ = 0;
      }
      // Find if task don't exist yet
      $a_datas = $this->find("`agent_id`='".$agent_id."'
                     AND `action`='".$action."'
                     AND `on_device`='".$device_id."'
                     AND `device_type`='".$device_type."'
                     AND `param`='".$param."' ");
      if (empty($a_datas)) {
         $a_input['date'] = date("Y-m-d H:i:s");
         $a_input['agent_id'] = $agent_id;
         $a_input['action'] = $action;
         $a_input['param'] = $param;
         $a_input['on_device'] = $device_id;
         $a_input['device_type'] = $device_type;
         $a_input['single'] = 1;
         $this->add($a_input);
         return true;
      }
      return false;
   }


   function getTask($deviceid) {
      $pta = new PluginFusionInventoryAgents;
      $ptc = new PluginFusionInventoryCommunication;

      $a_agent = $pta->InfosByKey($deviceid);
      $a_tasks = $this->find("`agent_id`='".$a_agent['ID']."'", "date");
      // TODO gest last
      foreach ($a_tasks as $task_id=>$datas) {
         if ($a_tasks[$task_id]['action'] == 'INVENTORY') {
            $ptc->addInventory();
         }
      }
      // If not unique get all in addition ;)
      
   }
}

?>