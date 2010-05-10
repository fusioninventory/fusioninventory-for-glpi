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

class PluginFusioninventoryAgents extends CommonDBTM {
   function __construct() {
		$this->table = "glpi_plugin_fusioninventory_agents";
		$this->type = PLUGIN_FUSIONINVENTORY_SNMP_AGENTS;
	}

   function defineTabs($options=array()){
		global $LANG,$CFG_GLPI;

      $ptcm = new PluginFusioninventoryConfigModules;

      $ong = array();
		if ($this->fields['id'] > 0){
         $ong[1]=$LANG['plugin_fusioninventory']["agents"][9];
         if (($ptcm->isActivated('remotehttpagent')) AND(PluginFusioninventory::haveRight("remotecontrol","w"))) {
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


	function showForm($ID, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

		if ($ID!='') {
			$this->getFromDB($ID);
      } else {
			$this->getEmpty();
      }

      $CommonItem = new CommonItem;
      $ptcm       = new PluginFusioninventoryConfigModules;

		$this->showTabs($options);
      $this->showFormHeader($options);

		echo "<tr class='tab_bg_1'>";
		echo "<td>" . $LANG["common"][16] . " :</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
		echo "</td>";

      if ($ptcm->getValue('inventoryocs') == "1") {
         echo "<td>".$LANG['plugin_fusioninventory']['config'][3]." :</td>";
         echo "<td align='center'>";
		Dropdown::showYesNo("module_inventory",$this->fields["module_inventory"]);
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
         Dropdown::showYesNo("module_netdiscovery",$this->fields["module_netdiscovery"]);
         echo "</td>";
      } else {
         echo "<td colspan='2'></td>";
      }
		echo "</tr>";


		echo "<tr class='tab_bg_1'>";
		echo "<td>" . $LANG['plugin_fusioninventory']["agents"][6] . " :</td>";
		echo "<td align='center'>";
		Dropdown::showYesNo("lock",$this->fields["lock"]);
		echo "</td>";

      if ($ptcm->getValue('snmp') == "1") {
         echo "<td>".$LANG['plugin_fusioninventory']['config'][7]." :</td>";
         echo "<td align='center'>";
         Dropdown::showYesNo("module_snmpquery",$this->fields["module_snmpquery"]);
         echo "</td>";
      } else {
         echo "<td colspan='2'></td>";
      }
		echo "</tr>";

      		
		echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']["agents"][23]." :</td>";
		echo "<td align='center'>";
      if (($this->fields["on_device"] != "0") AND ($this->fields["on_device"] != "")) {
         $CommonItem->getFromDB(COMPUTER_TYPE,
                                   $this->fields["on_device"]);
         echo $CommonItem->getLink(1);
         echo "<input type='hidden' name='on_device' value='".$this->fields["on_device"]."'/>";
      } else {
         Computer_Item::dropdownConnect(COMPUTER_TYPE,COMPUTER_TYPE,'on_device', $_SESSION['glpiactive_entity']);
      }
		echo "</td>";

      if ($ptcm->getValue('wol') == "1") {
         echo "<td>".$LANG['plugin_fusioninventory']['config'][6]." :</td>";
         echo "<td align='center'>";
         Dropdown::showYesNo("module_wakeonlan",$this->fields["module_wakeonlan"]);
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

		$this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
	}



   function showFormAdvancedOptions($ID, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;
      
      $this->showTabs($options);
      $this->showFormHeader($options);

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["agents"][3]."</td>";
		echo "<td align='center'>";
		Dropdown::showInteger("threads_discovery", $this->fields["threads_discovery"],1,400);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["agents"][2] . "</td>";
		echo "<td align='center'>";
		Dropdown::showInteger("threads_query", $this->fields["threads_query"],1,200);
		echo "</td>";
		echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
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

}

?>
