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

class PluginFusionInventoryAgents extends CommonDBTM {

   function __construct() {
		$this->table = "glpi_plugin_fusioninventory_agents";
		$this->type = PLUGIN_FUSIONINVENTORY_SNMP_AGENTS;
	}


	function defineTabs($ID,$withtemplate){
		global $LANG,$CFG_GLPI;

      $ptcm = new PluginFusionInventoryConfigModules;

      $ong = array();
		if ($ID > 0){
         $ong[1]=$LANG['plugin_fusioninventory']["agents"][9];
         if (($ptcm->isActivated('remotehttpagent')) AND(plugin_fusioninventory_HaveRight("remotecontrol","w"))) {
            $ong[2]=$LANG['plugin_fusioninventory']["task"][2];
         }
      }
		return $ong;
	}



	function PushData($ID, $key) {
		$this->getFromDB($ID);
		// Name of server
		// $this->fields["name"];
		
		$xml = "<snmp>\n";
		// ** boucle sur les équipements réseau
		// ** détection des équipements avec le bon status et l'IP dans la plage de l'agent
		//  Ecriture du fichier xml pour l'envoi à l'agent
	
		$xml .= "</snmp>\n";
		// Affichage du fichier xml pour que l'agent récupère les paramètres
		echo $xml;
	}


	function showForm($target, $ID = '') {
		global $DB,$CFG_GLPI,$LANG;

		if ($ID!='') {
			$this->getFromDB($ID);
      } else {
			$this->getEmpty();
      }

      $CommonItem = new CommonItem;
      $ptcm       = new PluginFusionInventoryConfigModules;

		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table class='tab_cadre' cellpadding='5' width='950'>";
		echo "<tr>";
		echo "<th colspan='4'>";
		echo $LANG['plugin_fusioninventory']["agents"][0];
		echo " :</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>" . $LANG["common"][16] . " :</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
		echo "</td>";

      if ($ptcm->getValue('inventoryocs') == "1") {
         echo "<td>".$LANG['plugin_fusioninventory']['config'][3]." :</td>";
         echo "<td align='center'>";
		dropdownYesNo("module_inventory",$this->fields["module_inventory"]);
		echo "</td>";
      } else {
         echo "<td colspan='2'></td>";
      }
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']["agents"][5]." :</td>";
		echo "<td align='center'>";
		echo $this->fields["fusioninventory_agent_version"];
		echo "</td>";

      if ($ptcm->getValue('netdiscovery') == "1") {
         echo "<td>".$LANG['plugin_fusioninventory']['config'][4]." :</td>";
         echo "<td align='center'>";
         dropdownYesNo("module_netdiscovery",$this->fields["module_netdiscovery"]);
         echo "</td>";
      } else {
         echo "<td colspan='2'></td>";
      }
		echo "</tr>";


		echo "<tr class='tab_bg_1'>";
		echo "<td>" . $LANG['plugin_fusioninventory']["agents"][6] . " :</td>";
		echo "<td align='center'>";
		dropdownYesNo("lock",$this->fields["lock"]);
		echo "</td>";

      if ($ptcm->getValue('snmp') == "1") {
         echo "<td>".$LANG['plugin_fusioninventory']['config'][7]." :</td>";
         echo "<td align='center'>";
         dropdownYesNo("module_snmpquery",$this->fields["module_snmpquery"]);
         echo "</td>";
      } else {
         echo "<td colspan='2'></td>";
      }
		echo "</tr>";

      		
		echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']["agents"][23]." :</td>";
		echo "<td align='center'>";
      if ($this->fields["on_device"] != "0") {
         $CommonItem->getFromDB(COMPUTER_TYPE,
                                   $this->fields["on_device"]);
         echo $CommonItem->getLink(1);
      } else {
         dropdownConnect(COMPUTER_TYPE,COMPUTER_TYPE,'on_device', $_SESSION['glpiactive_entity']);
      }
		echo "</td>";

      if ($ptcm->getValue('wol') == "1") {
         echo "<td>".$LANG['plugin_fusioninventory']['config'][6]." :</td>";
         echo "<td align='center'>";
         dropdownYesNo("module_wakeonlan",$this->fields["module_wakeonlan"]);
         echo "</td>";
		} else {
         echo "<td colspan='2'></td>";
      }

		echo "<tr class='tab_bg_1'>";
      echo "<td>Token :</td>";
		echo "<td align='center' colspan='3'>";
		echo $this->fields["token"];
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_2'>";
      if(plugin_fusioninventory_HaveRight("agents","w")) {
         if ($ID=='') {
            echo "<td align='center' colspan='4'>";
            echo "<div align='center'><input type='submit' name='add' value=\"" . $LANG["buttons"][8] . "\" class='submit' >";
            echo "</td>";
         } else {
            echo "<td align='center' colspan='2'>";
            echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
            echo "<div align='center'><input type='submit' name='update' value=\"" . $LANG["buttons"][7] . "\" class='submit' >";
            echo "</td>";
            echo "<td align='center' colspan='2'>";
            echo "<input type='submit' name='delete' value=\"" . $LANG["buttons"][6] . "\" class='submit'>";
            echo "</td>";
         }
      }
		echo "</tr>";
		echo "</table></form></div>";

	}



   function showFormAdvancedOptions($target, $ID = '') {
      global $DB,$CFG_GLPI,$LANG;
      
      echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table class='tab_cadre' cellpadding='5' width='950'>";

		echo "<tr>";
		echo "<th colspan='4'>";
		echo $LANG['plugin_fusioninventory']["agents"][9];
		echo " :</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center' width='200'>" . $LANG['plugin_fusioninventory']["agents"][11] . "</td>";
		echo "<td align='center' width='200'>";
		dropdownInteger("core_discovery", $this->fields["core_discovery"],1,32);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["agents"][3]."</td>";
		echo "<td align='center'>";
		dropdownInteger("threads_discovery", $this->fields["threads_discovery"],1,400);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["agents"][10]."</td>";
		echo "<td align='center'>";
		dropdownInteger("core_query", $this->fields["core_query"],1,32);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["agents"][2] . "</td>";
		echo "<td align='center'>";
		dropdownInteger("threads_query", $this->fields["threads_query"],1,200);
		echo "</td>";
		echo "</tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td align='center' colspan='4'>";
      echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
      echo "<div align='center'><input type='submit' name='update' value=\"" . $LANG["buttons"][7] . "\" class='submit' >";
      echo "</td>";
      echo "</tr>";

		echo "</table></form></div>";
   }



   function InfosByKey($key) {
      global $DB;

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_agents`
      WHERE `key`='".$key."' LIMIT 1";

      $agent = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            $agent = $DB->fetch_assoc($result);
         }
      }
      return $agent;
   }


   function RemoteStartAgent($ID, $ip) {
      $ptcm = new PluginFusionInventoryConfigModules;
      if ((!$ptcm->isActivated('remotehttpagent')) AND(!plugin_fusioninventory_HaveRight("remotecontrol","w"))) {
         return false;
      }
      $this->getFromDB($ID);
      if(!($fp = fsockopen($ip, 62354, $errno, $errstr, 1))) {
         $input = 'Agent don\'t respond';
         addMessageAfterRedirect($input);
         return false;
      } else {
         $handle = fopen("http://".$ip.":62354/now/".$this->fields['token'], "r");
         $input = 'Agent run Now';
         fclose($fp);
         addMessageAfterRedirect($input);
         return true;
      }
      
   }

   function RemoteStateAgent($target, $ID, $type, $a_modules = array()) {
      global $LANG,$CFG_GLPI;

      $ptcm = new PluginFusionInventoryConfigModules;

      if ((!$ptcm->isActivated('remotehttpagent')) AND(!plugin_fusioninventory_HaveRight("remotecontrol","w"))) {
         return;
      }
      $this->getFromDB($ID);

      echo "<div align='center'><form method='post' name='' id=''  action=\"plugin_fusioninventory.agents.state.php\">";

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
      if ((isset($a_modules["INVENTORY"])) AND ($ptcm->getValue("inventoryocs") == '1') AND ($this->fields['module_inventory'] == '1')) {
         $array_actions["INVENTORY"] = $LANG['plugin_fusioninventory']['config'][3];
      }
      if ((isset($a_modules["NETDISCOVERY"])) AND ($ptcm->getValue("netdiscovery") == '1') AND ($this->fields['module_netdiscovery'] == '1')) {
         $array_actions["NETDISCOVERY"] = $LANG['plugin_fusioninventory']['config'][4];
      }
      if ((isset($a_modules["SNMPQUERY"])) AND ($ptcm->getValue("snmp") == '1') AND ($this->fields['module_snmpquery'] == '1')) {
         $array_actions["SNMPQUERY"] = $LANG['plugin_fusioninventory']['config'][7];
      }
      if ((isset($a_modules["WAKEONLAN"])) AND ($ptcm->getValue("wol") == '1') AND ($this->fields['module_wakeonlan'] == '1')) {
         $array_actions["WAKEONLAN"] = $LANG['plugin_fusioninventory']['config'][6];
      }

      $rand = dropdownArrayValues("agentaction",$array_actions);
      echo "</td>";
      echo "</tr>";
      $params=array('action'=>'__VALUE__', 'on_device'=>$this->fields['on_device'], 'device_type'=>COMPUTER_TYPE);
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
      echo "<input type='hidden' name='on_device' value='".$ID."'/>";
      echo "<input type='hidden' name='device_type' value='".$type."'/>";
      echo "<input type='submit' name='startagent' value=\"".$LANG['plugin_fusioninventory']["task"][12]."\" class='submit' >";
      
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "</form>";
      echo "</div>";      
   }


   
   function showAgentInventory($on_device, $device_type) {
      global $DB,$LANG;

      // Recherche de chaque port de l'équipement
      $np = new Netport;
      $count_agent_on = 0;
      $agent_id = 0;

      $a_portsList = $np->find('on_device='.$on_device.' AND device_type='.$device_type);
      $a_agent = $this->find('on_device='.$on_device.' AND device_type IN ('.$device_type.', 0)', "", 1);

      foreach ($a_agent as $agent_id=>$data) {

      }
      if ($agent_id == "0") {
         return;
      }
      foreach ($a_portsList as $ID=>$data) {
         if ($data['ifaddr'] != "127.0.0.1") {
            if ($this->getStateAgent($data['ifaddr'],$agent_id)) {
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
      }
   }


   function showAgentNetDiscovery($on_device, $device_type) {
      global $LANG;

      // Recherche des agents qui ont le NETDISCOVERY à oui
      $np = new Netport;
      $count_agent_on = 0;
      $existantantip = array();
      $existantantip["127.0.0.1"] = 1;

      if ($device_type == PLUGIN_FUSIONINVENTORY_SNMP_AGENTS) {
         $a_agents = $this->find('module_netdiscovery=1 AND ID='.$on_device);
         $type = PLUGIN_FUSIONINVENTORY_SNMP_AGENTS;
      } else {
         $a_agents = $this->find('module_netdiscovery=1');
         $type = "";
      }
      foreach ($a_agents as $IDagent=>$data) {
         $a_portsList = $np->find('on_device='.$data['on_device'].' AND device_type='.$data['device_type']);

         foreach ($a_portsList as $ID=>$datapl) {
            if (!isset($existantantip[$datapl['ifaddr']])) {
               $existantantip[$datapl['ifaddr']] = 1;
               if ($this->getStateAgent($datapl['ifaddr'], $IDagent, $type)) {
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
      }
   }

   function showAgentSNMPQuery($on_device, $device_type) {
      global $LANG;
      // Recherche des agents qui ont le SNMPQUERY à oui
      $np = new Netport;
      $count_agent_on = 0;
      $existantantip = array();
      $existantantip["127.0.0.1"] = 1;

      $a_agents = $this->find('module_snmpquery=1');
      foreach ($a_agents as $IDagent=>$data) {
         $a_portsList = $np->find('on_device='.$data['on_device'].' AND device_type='.$data['device_type']);

         foreach ($a_portsList as $ID=>$datapl) {
            if (!isset($existantantip[$datapl['ifaddr']])) {
               $existantantip[$datapl['ifaddr']] = 1;
               if ($this->getStateAgent($datapl['ifaddr'], $IDagent)) {
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
      }
   }

   function showAgentWol($on_device, $device_type) {
      



      
   }

   function getStateAgent($ip, $agentid, $type="") {
      global $LANG;
      plugin_fusioninventory_disableDebug();
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
      plugin_fusioninventory_reenableusemode();
      return $state;
   }
}

?>