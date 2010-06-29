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

class PluginFusioninventoryTask extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_tasks";
      $this->type = 'PluginFusioninventoryTask';
	}


   function getSearchOptions() {
      global $LANG;

      $sopt = array();

      $sopt['common'] = $LANG['plugin_fusioninventory']["task"][0];

      $sopt[1]['table']          = $this->getTable();
      $sopt[1]['field']          = 'name';
      $sopt[1]['linkfield']      = '';
      $sopt[1]['name']           = $LANG["common"][16];
      $sopt[1]['datatype']       = 'itemlink';

      $sopt[2]['table']          = $this->getTable();
      $sopt[2]['field']          = 'date_creation';
      $sopt[2]['linkfield']      = '';
      $sopt[2]['name']           = $LANG["common"][27];
      $sopt[2]['datatype']       = 'datetime';

      $sopt[3]['table']          = $this->getTable();
      $sopt[3]['field']          = 'entities_id';
      $sopt[3]['linkfield']      = '';
      $sopt[3]['name']           = '';

      $sopt[30]['table']          = $this->getTable();
      $sopt[30]['field']          = 'id';
      $sopt[30]['linkfield']      = '';
      $sopt[30]['name']           = $LANG['common'][2];

      return $sopt;
   }



   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']["task"][1];
   }


   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCancel() {
      return true;
   }

   function canUndo() {
      return true;
   }

   function canValidate() {
      return true;
   }



   function defineTabs($options=array()){
		global $LANG,$CFG_GLPI,$DB;

      $ong = array();
      $ong[1] = $LANG['title'][26];

      $pft = new PluginFusioninventoryTaskjob;
      $a_taskjob = $pft->find("`plugin_fusioninventory_tasks_id`='".$_GET['id']."'", "date_scheduled,id");
      $i = 1;
      foreach($a_taskjob as $taskjob_id=>$datas) {
         $i++;
         $ong[$i] = "Tâche ".($i-1);
      }

      $i++;
      $ong[$i] = "Nouvelle tâche";
      return $ong;
	}



	function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

      if ($id!='') {
			$this->getFromDB($id);
      } else {
			$this->getEmpty();
      }

		$this->showTabs($options);
      $this->showFormHeader($options);
      
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
		echo "</td>";

		echo "<td rowspan='3'>".$LANG['common'][25]."&nbsp;:</td>";
		echo "<td align='center' rowspan='3'>";
      echo "<textarea cols='45' rows='5' name='comment' >".$this->fields["comment"]."</textarea>";
		echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][34]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo getUserName($this->fields["users_id"],1);
		echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][60]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo("is_active",$this->fields["is_active"]);
		echo "</td>";
      echo "</tr>";







      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }








   














   function Counter($agent_id, $action) {
      global $DB;

      $count = 0;
      $query = "SELECT COUNT(*) as count FROM `glpi_plugin_fusioninventory_tasks`
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
      $list = $this->find("`agent_id`='".$agent_id."' AND `action`='".$action."' ");
      foreach ($list as $task_id=>$data) {
         switch ($data['itemtype']) {
 
            case NETWORKING_TYPE:
               $query = "SELECT glpi_plugin_fusioninventory_tasks.id as id, param, ip, single,
                           glpi_plugin_fusioninventory_tasks.items_id as items_id, glpi_plugin_fusioninventory_tasks.itemtype as itemtype
                        FROM `glpi_plugin_fusioninventory_tasks`
                        INNER JOIN glpi_networkequipments on glpi_plugin_fusioninventory_tasks.items_id=glpi_networkequipments.id
                        WHERE `agent_id`='".$agent_id."'
                           AND `action`='".$action."'";
               break;

            case COMPUTER_TYPE:
            case PRINTER_TYPE:
               $query = "SELECT glpi_plugin_fusioninventory_tasks.id as id, param, ip, single,
                           glpi_plugin_fusioninventory_tasks.items_id as items_id, glpi_plugin_fusioninventory_tasks.itemtype as itemtype
                        FROM `glpi_plugin_fusioninventory_tasks`
                        INNER JOIN glpi_networkports on (glpi_plugin_fusioninventory_tasks.items_id=glpi_networkports.items_id
                                                      AND glpi_plugin_fusioninventory_tasks.itemtype=glpi_networkports.itemtype)
                        WHERE `agent_id`='".$agent_id."'
                           AND `action`='".$action."'
                           AND `ip`!='127.0.0.1'";

               break;
         }         
      }

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($data=$DB->fetch_array($result)) {
               $tasks[$data["id"]] = $data;
               $type='';
               switch ($tasks[$data["id"]]["itemtype"]) {
                  case "networking":
                     $tasks[$data["id"]]["itemtype"]='NETWORKING';
                     break;
                  case "printer":
                     $tasks[$data["id"]]["itemtype"]='PRINTER';
                     break;
               }
            }
         }
      }
      return $tasks;
   }


   function formAddTask($target, $input=array()) {
      global $LANG;

      $pta = new PluginFusioninventoryAgent;
      $ptc = new PluginFusioninventoryConfig;
      if ((!$ptc->isActivated('remotehttpagent')) AND 
              (!PluginFusioninventoryProfile::haveRight("Fusioninventory", "remotecontrol", "w"))) {
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
      Dropdown::show("PluginFusioninventoryAgent",
                     array('name'=>'agentocs',
                           'entity'=>1)); //TODO : check
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

      PluginFusioninventoryDisplay::disableDebug();
      if(!($fp = fsockopen("192.168.0.201", 62354, $errno, $errstr, 1))) {
          echo "<b>".$LANG['plugin_fusioninventory']["task"][9]."</b>";
      } else {
          echo "<b>".$LANG['plugin_fusioninventory']["task"][8]."</b>";
          $active_valid = 1;
          fclose($fp);
      }
      PluginFusioninventoryDisplay::reenableusemode();

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



   function addTask($device_id, $itemtype, $action, $agent_id, $param="") {
      $ptc = new PluginFusioninventoryConfig;
      if ((!$ptc->isActivated('remotehttpagent')) AND 
              (!PluginFusioninventoryProfile::haveRight("Fusioninventory", "remotecontrol", "w"))) {
         return false;
      }
      if ($param == 'PluginFusioninventoryAgent') {
         $items_id = 0;
         $device_typ = 0;
      }
      // Find if task don't exist yet
      $a_datas = $this->find("`agent_id`='".$agent_id."'
                     AND `action`='".$action."'
                     AND `items_id`='".$device_id."'
                     AND `itemtype`='".$itemtype."'
                     AND `param`='".$param."' ");
      if (empty($a_datas)) {
         $a_input['date'] = date("Y-m-d H:i:s");
         $a_input['agent_id'] = $agent_id;
         $a_input['action'] = $action;
         $a_input['param'] = $param;
         $a_input['items_id'] = $device_id;
         $a_input['itemtype'] = $itemtype;
         $a_input['single'] = 1;
         $this->add($a_input);
         return true;
      }
      return false;
   }


   function getTask($deviceid) {
      $pta = new PluginFusioninventoryAgent;
      $ptc = new PluginFusioninventoryCommunication;

      $a_agent = $pta->InfosByKey($deviceid);
      $a_tasks = $this->find("`agent_id`='".$a_agent['id']."'", "date");
      // TODO gest last
      foreach ($a_tasks as $task_id=>$datas) {
         if ($a_tasks[$task_id]['action'] == 'INVENTORY') {
            $ptc->addInventory();
         }
      }
      // If not unique get all in addition ;)
      
   }



   function RemoteStartAgent($id, $ip) {
      $ptc = new PluginFusioninventoryConfig;
      $pfia = new PluginFusioninventoryAgent;

      if ((!$ptc->isActivated('remotehttpagent')) AND
              (!PluginFusioninventoryProfile::haveRight("Fusioninventory", "remotecontrol", "w"))) {
         return false;
      }
      $pfia->getFromDB($id);
      if(!($fp = fsockopen($ip, 62354, $errno, $errstr, 1))) {
         $input = 'Agent don\'t respond';
         addMessageAfterRedirect($input);
         return false;
      } else {
         $handle = fopen("http://".$ip.":62354/now/".$pfia->fields['token'], "r");
         $input = 'Agent run Now';
         fclose($fp);
         addMessageAfterRedirect($input);
         return true;
      }

   }

   function RemoteStateAgent($target, $id, $type, $a_modules = array()) {
      global $LANG,$CFG_GLPI;

      $ptc = new PluginFusioninventoryConfig;
      $pfia = new PluginFusioninventoryAgent;

      if ((!$ptc->isActivated('remotehttpagent')) AND
              (!PluginFusioninventoryProfile::haveRight("Fusioninventory", "remotecontrol", "w"))) {
         return;
      }
      if ($type == 'PluginFusioninventoryAgent') {
         $pfia->getFromDB($id);
         $items_id = $id;
      } else if ($type == COMPUTER_TYPE) {
         $agentlist = $pfia->find("items_id='".$id."'", "", "1");
         foreach ($agentlist as $data){
            $pfia->getFromDB($data['id']);
            $items_id = $pfia->fields['items_id'];
         }
      } else if (($type == NETWORKING_TYPE) OR ($type == PRINTER_TYPE)) {
         $items_id = $id;
      }
      
      echo "<div align='center'><form method='post' name='' id=''  action=\"".GLPI_ROOT . "/plugins/fusioninventory/front/agents.state.php\">";

		echo "<table  class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusioninventory']["agents"][14];
      echo " : </th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2' align='center'>";
      $array_actions = array();
      $array_actions[""] = "------";
      switch($type) {

         case NETWORKING_TYPE :
         case PRINTER_TYPE :
            if ((isset($a_modules["INVENTORY"])) AND ($ptcm->getValue("snmp") == '1')) {
               $array_actions["INVENTORY"] = $LANG['plugin_fusioninventory']['config'][3];
            }
            break;

         case 'PluginFusioninventoryAgent':
         case COMPUTER_TYPE:
            if (((isset($a_modules["INVENTORY"])) AND ($ptcm->getValue("inventoryocs") == '1') AND (isset($pfia->fields['module_inventory'])) AND ($pfia->fields['module_inventory'] == '1'))
                     OR ((isset($a_modules["INVENTORY"])) AND ($ptcm->getValue("snmp") == '1') AND (isset($pfia->fields['module_snmpquery'])) AND ($pfia->fields['module_snmpquery'] == '1'))){
               $array_actions["INVENTORY"] = $LANG['plugin_fusioninventory']['config'][3];
            }

            if (($type == 'PluginFusioninventoryAgent') AND (isset($a_modules["NETDISCOVERY"])) AND ($ptcm->getValue("netdiscovery") == '1') AND ($pfia->fields['module_netdiscovery'] == '1')) {
               $array_actions["NETDISCOVERY"] = $LANG['plugin_fusioninventory']['config'][4];
            }


            if ((isset($a_modules["WAKEONLAN"])) AND ($ptcm->getValue("wol") == '1')) {
               // Code for 'PluginFusioninventoryAgent' if  ($pfia->fields['module_wakeonlan'] == '1')
               // so :
               if ($type == COMPUTER_TYPE) {
                   $array_actions["WAKEONLAN"] = $LANG['plugin_fusioninventory']['config'][6];
               }
            }
            break;

      }

//      if ((isset($a_modules["NETDISCOVERY"])) AND ($ptcm->getValue("netdiscovery") == '1') AND ($pfia->fields['module_netdiscovery'] == '1')) {
//         $array_actions["NETDISCOVERY"] = $LANG['plugin_fusioninventory']['config'][4];
//      }
//      if ((isset($a_modules["SNMPQUERY"])) AND ($ptcm->getValue("snmp") == '1') AND ($pfia->fields['module_snmpquery'] == '1')) {
//         $array_actions["SNMPQUERY"] = $LANG['plugin_fusioninventory']['config'][7];
//      }

      $rand = Dropdown::showFromArray("agentaction",$array_actions);
      echo "</td>";
      echo "</tr>";
      if (!isset($items_id)) {
         $items_id = $id;
      }
      $params=array('action'=>'__VALUE__', 'items_id'=>$items_id, 'itemtype'=>$type);
      ajaxUpdateItemOnSelectEvent("dropdown_agentaction$rand","updateAgentState_$rand",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/agentsState.php",$params,false);

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2' align='center'>";
      echo "<span id='updateAgentState_$rand'>\n";
      echo "&nbsp;";
      echo "</span>\n";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td align='center'>";
      echo "<input type='hidden' name='items_id' value='".$id."'/>";
      echo "<input type='hidden' name='itemtype' value='".$type."'/>";
      echo "<div id='displaybutton' style='visibility:hidden'>";
      echo "<input type='submit' name='startagent' value=\"".$LANG['plugin_fusioninventory']["task"][12]."\" class='submit' >";
      echo "</div>";

      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "</form>";
      echo "</div>";
   }



   function showAgentInventory($items_id, $itemtype) {
      global $DB,$LANG;

      $pfia = new PluginFusioninventoryAgent;
      $computer_ID = 0;
      $count_agent_on = 0;

      switch ($itemtype) {

         case 'PluginFusioninventoryAgent':
            echo "<select name='device'>";
            // afficher la machine associe a l'agent
            echo "<optgroup label=\"".$LANG['help'][25]."\">";
            
            $query = "SELECT glpi_computers.* FROM glpi_plugin_fusioninventory_agents
               LEFT JOIN glpi_computers
                  ON glpi_computers.id=items_id
               WHERE glpi_plugin_fusioninventory_agents.id='".$items_id."' ";
            if ($result = $DB->query($query)) {
               if ($DB->numrows($result) != 0) {
                  while ($data=$DB->fetch_array($result)) {
                     $computer_ID = $data['id'];
                     echo "<option value='".COMPUTER_TYPE."-".$data['id']."'>".
                           $data['name']." - ".$data['serial']." - ".$data['otherserial']."</option>";
                  }
               }
            }
            echo "</optgroup>";
            // lister les switch ou imprimante
            echo $this->dropdownNetworkPrinterSNMP($items_id);
            echo "</select><br/>";
            break;

         case COMPUTER_TYPE:
            // afficher la machine ou juste valider
            echo "<input type='hidden' name='device' value='".COMPUTER_TYPE."-".$items_id."' />";
            $computer_ID = $items_id;
            break;

         case NETWORKING_TYPE:
         case PRINTER_TYPE:
            // Choisir parmi les agents qui repondent et on le SNMP d'activé
            echo "<input type='hidden' name='device' value='".$itemtype."-".$items_id."' />";
            echo "<table>";
            $count_agent_on = $this->showAgentSNMPQuery($items_id, $itemtype);
            echo "</table>";
            break;

      }


      // Recherche de chaque port de l'équipement
      $np = new NetworkPort;

      $agent_id = 0;

      $a_portsList = $np->find('items_id='.$computer_ID.' AND itemtype="'.COMPUTER_TYPE.'"');

      switch ($itemtype) {

         case 'PluginFusioninventoryAgent':
            $agent_id = $items_id;
            break;

         case COMPUTER_TYPE:
            // Search id of agent
            $list = $pfia->find('items_id='.$computer_ID.' AND itemtype="'.COMPUTER_TYPE.'"');
            foreach ($list as $id=>$data) {
               $agent_id = $id;
            }
            break;

      }
//      if ($agent_id == "0") {
//         return;
//      }
      foreach ($a_portsList as $id=>$data) {
         if ($data['ip'] != "127.0.0.1") {
            if ($this->getStateAgent($data['ip'],$agent_id)) {
               $count_agent_on++;
            }
         }
      }
      if ($count_agent_on == 0) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center' colspan='2'>";
         echo "<b>".$LANG['plugin_fusioninventory']["task"][13]."</b>";
         echo "</td>";
         echo "</tr>";
      } else {
         echo "<script>
            document.getElementById('displaybutton').style.visibility='visible';
         </script>";
      }
   }


   function showAgentNetDiscovery($items_id, $itemtype) {
      global $LANG;

      // Recherche des agents qui ont le NETDISCOVERY à oui
      $np = new NetworkPort;
      $pfia = new PluginFusioninventoryAgent;
      $count_agent_on = 0;
      $existantantip = array();
      $existantantip["127.0.0.1"] = 1;
      if ($itemtype == 'PluginFusioninventoryAgent') {
         $a_agents = $pfia->find('module_netdiscovery=1 AND id='.$items_id);
         $type = 'PluginFusioninventoryAgent';
      } else if ($itemtype == COMPUTER_TYPE) {

      } else {
         $a_agents = $pfia->find('module_netdiscovery=1');
         $type = "";
      }
      foreach ($a_agents as $IDagent=>$data) {
         $a_portsList = $np->find('items_id='.$data['items_id'].' AND itemtype='.$data['itemtype']);

         foreach ($a_portsList as $id=>$datapl) {
            if (!isset($existantantip[$datapl['ip']])) {
               $existantantip[$datapl['ip']] = 1;
               if ($this->getStateAgent($datapl['ip'], $IDagent, $type)) {
                  $count_agent_on++;
               }
            }
         }
      }
      if ($count_agent_on == 0) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center' colspan='2'>";
         echo "<b>".$LANG['plugin_fusioninventory']["task"][13]."</b>";
         echo "</td>";
         echo "</tr>";
      } else {
         echo "<script>
            document.getElementById('displaybutton').style.visibility='visible';
         </script>";
      }
   }

   function showAgentSNMPQuery($items_id, $itemtype) {
      global $LANG;
      // Recherche des agents qui ont le SNMPQUERY à oui
      $np = new NetworkPort;
      $pfia = new PluginFusioninventoryAgent;

      $count_agent_on = 0;
      $existantantip = array();
      $existantantip["127.0.0.1"] = 1;

      switch ($itemtype) {

         case NETWORKING_TYPE:
         case PRINTER_TYPE:
            $a_agents = $pfia->find('module_snmpquery=1');
            foreach ($a_agents as $IDagent=>$data) {
               $a_portsList = $np->find('items_id='.$data['items_id'].' AND itemtype='.$data['itemtype']);

               foreach ($a_portsList as $id=>$datapl) {
                  if (!isset($existantantip[$datapl['ip']])) {
                     $existantantip[$datapl['ip']] = 1;
                     if ($this->getStateAgent($datapl['ip'], $IDagent)) {
                        $count_agent_on++;
                     }
                  }
               }
            }
            break;

         case COMPUTER_TYPE:
            $a_agents = $pfia->find('module_wakeonlan=1');
            foreach ($a_agents as $IDagent=>$data) {
               $a_portsList = $np->find('items_id='.$data['items_id'].' AND itemtype='.$data['itemtype']);

               foreach ($a_portsList as $id=>$datapl) {
                  if (!isset($existantantip[$datapl['ip']])) {
                     $existantantip[$datapl['ip']] = 1;
                     if ($this->getStateAgent($datapl['ip'], $IDagent)) {
                        $count_agent_on++;
                     }
                  }
               }
            }
            break;

      }


      if ($count_agent_on == 0) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center' colspan='2'>";
         echo "<b>".$LANG['plugin_fusioninventory']["task"][13]."</b>";
         echo "</td>";
         echo "</tr>";
      } else {
         echo "<script>
            document.getElementById('displaybutton').style.visibility='visible';
         </script>";
      }
      return $count_agent_on;
   }

   function showAgentWol($items_id, $itemtype) {
      global $LANG;

      $np = new NetworkPort;
      $pfia = new PluginFusioninventoryAgent;

      $count_agent_on = 0;
      $existantantip = array();
      $existantantip["127.0.0.1"] = 1;

      switch ($itemtype) {

         case COMPUTER_TYPE:
            // Choisir parmi les agents qui repondent et on le SNMP d'activé
            echo "<input type='hidden' name='device' value='".$itemtype."-".$items_id."' />";
            echo "<table>";

            $a_agents = $pfia->find('module_wakeonlan=1');
            foreach ($a_agents as $IDagent=>$data) {
               $a_portsList = $np->find('items_id='.$data['items_id'].' AND itemtype='.$data['itemtype']);
               foreach ($a_portsList as $id=>$datapl) {
                  if (!isset($existantantip[$datapl['ip']])) {
                     $existantantip[$datapl['ip']] = 1;
                     if ($this->getStateAgent($datapl['ip'], $IDagent)) {
                        $count_agent_on++;
                     }
                  }
               }
            }

            echo "</table>";
            break;
      }

      if ($count_agent_on == 0) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center' colspan='2'>";
         echo "<b>".$LANG['plugin_fusioninventory']["task"][13]."</b>";
         echo "</td>";
         echo "</tr>";
      } else {
         echo "<script>
            document.getElementById('displaybutton').style.visibility='visible';
         </script>";
      }

   }

   function getStateAgent($ip, $agentid, $type="") {
      global $LANG;

      PluginFusioninventoryDisplay::disableDebug();
      $state = false;
      if($fp = fsockopen($ip, 62354, $errno, $errstr, 1)) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo "<input type='checkbox' name='agent-ip[]' value='$agentid-$ip-$type'/>";
         echo "</td>";
         echo "<td align='center'>".$ip;
         echo "</td>";
         echo "<td align='center'>";
         echo $LANG['plugin_fusioninventory']["task"][8];
         echo "</td>";
         echo "</tr>";

         fclose($fp);
         $state = true;
      }
      PluginFusioninventoryDisplay::reenableusemode();
      return $state;
   }




   // TODO : **************************************************

   function dropdownNetworkPrinterSNMP($agent_id) {
      // Dropdown with printer and network devices who have model and auth
      global $DB,$LANG;

      $ptc = new PluginFusioninventoryConfig;
      $pfia = new PluginFusioninventoryAgent;

      $dropdownOptions = "";

      $pfia->getFromDB($agent_id);
      if (($ptc->getValue("snmp") == '1') AND ($pfia->fields['module_snmpquery'] == '1')) {
         // Networking
         $dropdownOptions = "<optgroup label=\"".$LANG['help'][26]."\">";
         $query = "SELECT `glpi_networkequipments`.`id` AS `gID`,
                           `glpi_networkequipments`.`name` AS `name`, `serial`, `otherserial`,
                                `plugin_fusioninventory_snmpauths_id`, `plugin_fusioninventory_snmpmodels_id`
                         FROM `glpi_networkequipments`
                         LEFT JOIN `glpi_plugin_fusioninventory_networkequipments`
                              ON `networkequipments_id`=`glpi_networkequipments`.`id`
                         INNER join `glpi_plugin_fusioninventory_snmpmodels`
                              ON `plugin_fusioninventory_snmpmodels_id`=`glpi_plugin_fusioninventory_snmpmodels`.`id`
                         WHERE `glpi_networkequipments`.`is_deleted`='0'
                              AND `plugin_fusioninventory_snmpmodels_id`!='0'
                              AND `plugin_fusioninventory_snmpauths_id`!='0'
                         GROUP BY networkequipments_id";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $dropdownOptions .= "<option value='".NETWORKING_TYPE."-".$data['gID']."'>".
               $data['name']." - ".$data['serial']." - ".$data['otherserial']."</option>";
         }
         $dropdownOptions .= "</optgroup>";

         // Printers
         $dropdownOptions .= "<optgroup label=\"".$LANG['help'][27]."\">";
         $query = "SELECT `glpi_printers`.`id` AS `gID`,
                           `glpi_printers`.`name` AS `name`, `serial`, `otherserial`,
                                `plugin_fusioninventory_snmpauths_id`, `plugin_fusioninventory_snmpmodels_id`
                         FROM `glpi_printers`
                         LEFT JOIN `glpi_plugin_fusioninventory_printers`
                              ON `printers_id`=`glpi_printers`.`id`
                         LEFT JOIN `glpi_networkports`
                                 ON `items_id`=`glpi_printers`.`id`
                                    AND `itemtype`='".PRINTER_TYPE."'
                         INNER join `glpi_plugin_fusioninventory_snmpmodels`
                              ON `plugin_fusioninventory_snmpmodels_id`=`glpi_plugin_fusioninventory_snmpmodels`.`id`
                         WHERE `glpi_printers`.`is_deleted`='0'
                              AND `plugin_fusioninventory_snmpmodels_id`!='0'
                              AND `plugin_fusioninventory_snmpauths_id`!='0'
                         GROUP BY printers_id";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $dropdownOptions .= "<option value='".NETWORKING_TYPE."-".$data['gID']."'>".
               $data['name']." - ".$data['serial']." - ".$data['otherserial']."</option>";
         }
         $dropdownOptions .= "</optgroup>";
      }
      return $dropdownOptions;
   }



   function dropdownAgentsSNMPQuery() {
      // Dropdown agent who are ok to SNMPQuery
      
   }

}

?>
