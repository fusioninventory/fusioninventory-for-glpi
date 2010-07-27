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

class PluginFusioninventoryTaskjob extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_taskjobs";
      $this->type = 'PluginFusioninventoryTaskjob';
	}


   static function getTypeName() {
      global $LANG;
      
      return $LANG['plugin_fusioninventory']["task"][2];
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



   function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;

      if ($id!='') {
			$this->getFromDB($id);
      } else {
			$this->getEmpty();
      }


$this->cronTaskScheduler();
      $this->showFormHeader($options);
      
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
		echo "</td>";
		echo "<td>Méthode&nbsp;:</td>";
		echo "<td align='center'>";
      $this->dropdownMethod("method_id", $this->fields['method'], $this->fields['selection_type']);
		echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][34]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo getUserName($this->fields["users_id"],1);
		echo "</td>";
      echo "<td>selection_type&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<span id='show_SelectionType_id'>";
      //$this->dropdownSelectionType("selection_type");
      echo "</span>";
		echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>Planifié le&nbsp;:</td>";
		echo "<td align='center'>";
      if ($id) {
         showDateTimeFormItem("date_scheduled",$this->fields["date_scheduled"],1,false);
      } else {
         showDateTimeFormItem("date_scheduled",date("Y-m-d H:i:s"),1);
      }
		echo "</td>";
      echo "<td rowspan='4'>Selection&nbsp;:</td>";
      echo "<td align='center' rowspan='4'>";
      echo "<span id='show_Selection_id'>";
      echo "</span>";
      echo "<span id='show_selectionList'>";
      echo "</span>";
      $a_deviceList = importArrayFromDB($this->fields['selection']);
      $selection = '';
      $selectionDisplayOptions = '';
      for ($i=0; $i < count($a_deviceList) ; $i++) {
         foreach ($a_deviceList[$i] as $itemtype=>$items_id) {
            $selection .= ','.$itemtype.'-'.$items_id;

            $class = new $itemtype();
            $class->getFromDB($items_id);
            $selectionDisplayOptions .= "<option value='".$itemtype.'-'.$items_id."'>".$class->fields['name']."</option>";
         }
      }
      echo "<br/><select name='selectionDisplay' id='selectionDisplay' size='10' multiple='multiple'>".$selectionDisplayOptions."</select>";
      echo "<div style='visibility:hidden'>";
      echo "<textarea name='selection' id='selection'>".$selection."</textarea>";
      echo "</div>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
		echo "<td>Nombre d'essais&nbsp;:</td>";
		echo "<td align='center'>";
      Dropdown::showInteger("retry_nb", $this->fields["retry_nb"], 1, 30);
		echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>Temps entre 2 essais (en minutes)&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("retry_time", $this->fields["retry_time"], 0, 360);
		echo "</td>";
      echo "</tr>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['common'][25]."&nbsp;:</td>";
		echo "<td align='center'>";
      echo "<textarea cols='40' rows='5' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "<input type='hidden' name='plugin_fusioninventory_tasks_id' value='".$_POST['id']."' />";
		echo "</td>";
      echo "</tr>";

      if ($id) {
         if (count($PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$id."'")) > 0) {
            echo "</table><br/>";
            if ($id) {
               $PluginFusioninventoryTaskjobstatus->stateTaskjob($id);
               echo "<br/>";
            }
         } else {
            $this->showFormButtons($options);
         }
      } else  {
         $this->showFormButtons($options);
      }

      return true;
   }



   function dropdownMethod($myname,$value=0,$valueType=0,$entity_restrict='') {
      global $DB,$CFG_GLPI;

      $a_methods = array();
      $a_methods = plugin_fusioninventory_task_methods();
      $a_modules = PluginFusioninventoryModule::getAll();
      foreach ($a_modules as $module_id=>$datas) {
         if (function_exists("plugin_".$datas['name']."_task_methods")) {
            $a_methods = call_user_func("plugin_".$datas['name']."_task_methods");
         }
      }
      $a_methods2 = array();
      $a_methods2[''] = "------";
      foreach ($a_methods as $num=>$datas) {
         $a_methods2[$datas['method']] = $datas['method'];
      }

      $rand = Dropdown::showFromArray($myname, $a_methods2);

      $params=array('method_id'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname
                     );
      ajaxUpdateItemOnSelectEvent("dropdown_method_id".$rand,"show_SelectionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownMethod.php",$params);

      if ($value != "0") {
         $i = -1;
         foreach ($a_methods2 as $method) {
            $i++;
            if ($method == $value) {
               $numSelected = $i;
            } 
         }
         $params['value'] = $valueType;
         if (isset($numSelected)) {
            echo "<script type='text/javascript'>
            document.getElementById('dropdown_".$myname.$rand."').selectedIndex = ".$numSelected.";
";
       ajaxUpdateItemJsCode("show_SelectionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownMethod.php",$params,true,"dropdown_method_id".$rand);

            echo "</script>";
         }
      }

      return $rand;
   }



   function dropdownSelectionType($myname,$method,$value=0,$entity_restrict='') {
      global $DB,$CFG_GLPI;

      $a_methods = array();
      $a_methods = plugin_fusioninventory_task_methods();
      $a_modules = PluginFusioninventoryModule::getAll();
      foreach ($a_modules as $module_id=>$datas) {
         if (function_exists("plugin_".$datas['name']."_task_methods")) {
            $a_methods = call_user_func("plugin_".$datas['name']."_task_methods");
         }
      }
      $a_selectiontype = array();
      $a_selectiontype[''] = "------";
      foreach ($a_methods as $num=>$datas) {
         if ($datas['method'] == $method) {
            $a_selectiontype[$datas['selection_type']] = $datas['selection_type'];
         }
      }

      $rand = Dropdown::showFromArray($myname, $a_selectiontype);

      $params=array('selection_type'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname
                     );

      ajaxUpdateItemOnSelectEvent("dropdown_selection_type".$rand,"show_Selection_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownSelectionType.php",$params);

      if ($value != "0") {
         $i = -1;
         foreach ($a_selectiontype as $type) {
            $i++;
            if ($type == $value) {
               $numSelected = $i;
            } 
         }
         if (isset($numSelected)) {
            echo "<script type='text/javascript'>
            document.getElementById('dropdown_".$myname.$rand."').selectedIndex = ".$numSelected.";
               ";
            ajaxUpdateItemJsCode("show_Selection_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownSelectionType.php",$params,true,"dropdown_selection_type".$rand);

            echo "</script>";
         }
      }

   }



   function dropdownSelection($myname,$selectiontype,$value=0,$entity_restrict='') {
      global $DB,$CFG_GLPI;
      
      $types = '';
      $internal = 1;
      if ($selectiontype == "devices") {

      } else if ($selectiontype == "rules") {

      } else if ($selectiontype == "devicegroups") {
         $types = array();
         $types[] = 'Group';
      } else {
         $internal = 0;
      }
      if ($internal == "1") {
         Dropdown::showAllItems($myname, 0, 0, -1, $types);
      } else {
         // <select> personalisé :
         plugin_fusioninventory_task_wakeonlan_fromothertasks();
      }
      echo "<input type='button' name='addObject' id='addObject' value='Ajouter' class='submit'/>";

      $params=array('selection'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'myname'=>$myname
                     );


      ajaxUpdateItemOnEvent('addObject','show_selectionList',$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownSelection.php",$params,array("click"));
   }
   

   function cronTaskScheduler() {
      global $DB;

      $dateNow = date("Y-m-d H:i:s");

      $PluginFusioninventoryTask = new PluginFusioninventoryTask;
      $PluginFusioninventoryTaskjoblogs = new PluginFusioninventoryTaskjoblogs;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;

      $remoteStartAgents = array();

      $query = "SELECT `".$this->table."`.* FROM ".$this->table."
         LEFT JOIN `glpi_plugin_fusioninventory_tasks` ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
         WHERE `is_active`='1'
            AND `status` = '0'
            AND date_scheduled < '".$dateNow."' ";
      if ($result = $DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {

            // Get list of devices listed in this job
            unset($a_deviceList);
            switch ($data['selection_type']) {

               case 'devices':
                  $a_deviceList = importArrayFromDB($data['selection']);
                  break;

               case 'rules':

                  break;

               case 'devicegroups':

                  break;

               case 'fromothertasks':

                  break;
            }

            if (isset($a_deviceList)) {

               // Run function of this method for each device
               foreach ($a_deviceList as $num=>$devicecomposed_id) {
                  foreach ($devicecomposed_id as $itemtype=>$items_id) {
                     
                  }
                  // Get module name
                  $pluginName = PluginFusioninventoryModule::getModuleName($data['plugins_id']);
                  $className = "Plugin".ucfirst($pluginName).ucfirst($data['method']);
                  $class = new $className;
                  
                  $a_agents = $class->prepareRun($itemtype, $items_id);
                  // Add jobstatus and put status (waiting on server = 0)
                  $a_input['plugin_fusioninventory_taskjobs_id'] = $data['id'];
                  $a_input['items_id'] = $items_id;
                  $a_input['itemtype'] = $itemtype;
                  $a_input['state'] = 0;
                  $a_input['plugin_fusioninventory_agents_id'] = $a_agents['agents_id'];
                  $PluginFusioninventoryTaskjobstatus->add($a_input);

                  //Add log of taskjob
                  unset($a_input['plugin_fusioninventory_agents_id']);
                  $a_input['state'] = 1;
                  $a_input['date'] = date("Y-m-d H:i:s");
                  $PluginFusioninventoryTaskjoblogs->add($a_input);

                  $remoteStartAgents[$a_agents['ip']] = $a_agents['token'];

                  // TODO : put status = 1 in glpi_plugin_fusioninventory_taskjobs
               }
            }
         }
      }
      // remote start agents
      foreach ($remoteStartAgents as $ip=>$token) {
         $this->RemoteStartAgent($ip, $token);
      }     

   }



   function getStateAgent($ip, $agentid, $type="") {
      global $LANG;

      //PluginFusioninventoryDisplay::disableDebug();
      $state = false;
      $ctx = stream_context_create(array(
          'http' => array(
              'timeout' => 2
              )
          )
      );

      $url = "http://".$ip.":62354/status";

      $str = @file_get_contents($url, 0, $ctx);
      if (strstr($str, "waiting")) {
         return true;
      }
      return $state;
   }
   


   function RemoteStartAgent($ip, $token) {
      if(!($fp = fsockopen($ip, 62354, $errno, $errstr, 1))) {
         $input = 'Agent don\'t respond';
         return false;
      } else {
         $handle = fopen("http://".$ip.":62354/now/".$token, "r");
         $input = 'Agent run Now';
         fclose($fp);
         return true;
      }

   }








   












// **********************************************************************
// TODO : see in all function under this text if is ok or must be deleted


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
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
      if ((!$ptc->is_active($plugins_id, 'remotehttpagent')) AND
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
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
      if ((!$ptc->is_active($plugins_id, 'remotehttpagent')) AND
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





   function RemoteStateAgent($target, $id, $type, $a_modules = array()) {
      global $LANG,$CFG_GLPI;

      $ptc = new PluginFusioninventoryConfig;
      $pfia = new PluginFusioninventoryAgent;
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
      if ((!$ptc->is_active($plugins_id, 'remotehttpagent')) AND
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
                                `plugin_fusioninventory_snmpauths_id`, `plugin_fusinvsnmp_models_id`
                         FROM `glpi_networkequipments`
                         LEFT JOIN `glpi_plugin_fusinvsnmp_networkequipments`
                              ON `networkequipments_id`=`glpi_networkequipments`.`id`
                         INNER join `glpi_plugin_fusinvsnmp_models`
                              ON `plugin_fusinvsnmp_models_id`=`glpi_plugin_fusinvsnmp_models`.`id`
                         WHERE `glpi_networkequipments`.`is_deleted`='0'
                              AND `plugin_fusinvsnmp_models_id`!='0'
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
                                `plugin_fusioninventory_snmpauths_id`, `plugin_fusinvsnmp_models_id`
                         FROM `glpi_printers`
                         LEFT JOIN `glpi_plugin_fusinvsnmp_printers`
                              ON `printers_id`=`glpi_printers`.`id`
                         LEFT JOIN `glpi_networkports`
                                 ON `items_id`=`glpi_printers`.`id`
                                    AND `itemtype`='".PRINTER_TYPE."'
                         INNER join `glpi_plugin_fusinvsnmp_models`
                              ON `plugin_fusinvsnmp_models_id`=`glpi_plugin_fusinvsnmp_models`.`id`
                         WHERE `glpi_printers`.`is_deleted`='0'
                              AND `plugin_fusinvsnmp_models_id`!='0'
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
