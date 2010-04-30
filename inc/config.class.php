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


class PluginFusioninventoryConfig extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_config";
		$this->type=PLUGIN_FUSIONINVENTORY_SNMP_CONFIG;
	}

	function initConfig($version) {
		global $DB,$CFG_GLPI;
		$url = str_replace("http:","https:",$CFG_GLPI["url_base"]);
		$query = "INSERT INTO ".$this->table."(
                              `ID`, `version`, `URL_agent_conf`, `ssl_only`, `authsnmp`, `inventory_frequence`,
                              `criteria1_ip`, `criteria1_name`, `criteria1_serial`,
                              `criteria1_macaddr`, `criteria2_ip`, `criteria2_name`,
                              `criteria2_serial`, `criteria2_macaddr`, `delete_agent_process`)
                VALUES ('1', '".$version."', '".$url."', '0', 'DB', '24', '0', '0', '0', '0', '0', '0', '0', '0', '24');";

		$DB->query($query);
	}
	
	/* Function to get the value of a field */
	function getValue($field) {
		global $DB;

		$query = "SELECT ".$field."
                FROM ".$this->table."
                WHERE `ID` = '1';";
		if ($result = $DB->query($query)) {
			if ($this->fields = $DB->fetch_row($result)) {
				return $this->fields['0'];
         }
		}
		return false;
	}

	// Confirm if the functionality is activated, or not
	function isActivated($functionality) {
		
		if (!($this->getValue($functionality))) {
			return false;
      } else {
			return true;
      }
	}


	function defineTabs($ID,$withtemplate) {
		global $LANG,$CFG_GLPI;
      $ong = array();
		$ong[1]=$LANG['plugin_fusioninventory']["functionalities"][2];
      $ong[2]=$LANG['plugin_fusioninventory']['config'][1];
//		$ong[3]=$LANG['plugin_fusioninventory']["functionalities"][3]." - ".$LANG['plugin_fusioninventory']["functionalities"][5];
//		$ong[3]=$LANG['plugin_fusioninventory']["functionalities"][3]." - ".$LANG['plugin_fusioninventory']["discovery"][3];

      $ong[7]=$LANG['title'][38];
      $ong[8]=$LANG['plugin_fusioninventory']["functionalities"][7];

		return $ong;
	}
	

	
	function showForm($target,$ID) {
		global $LANG,$CFG_GLPI;

		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='4'>";
		echo $LANG['plugin_fusioninventory']["functionalities"][2]."&nbsp;:";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][27]."&nbsp;:</td>";
		echo "<td width='20%'>";
		Dropdown::showYesNo("ssl_only", $this->isActivated('ssl_only'));
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][16]."&nbsp;:</td>";
		echo "<td width='20%'>";
		$ArrayValues = array();
		$ArrayValues['DB']= $LANG['plugin_fusioninventory']["functionalities"][17];
		$ArrayValues['file']= $LANG['plugin_fusioninventory']["functionalities"][18];
		Dropdown::showFromArray('authsnmp', $ArrayValues,
                              array('value'=>$this->getValue('authsnmp')));
		echo "</td>";
      echo "</tr>";

 		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']['config'][0]."&nbsp;:</td>";
		echo "<td>";
      Dropdown::showInteger("inventory_frequence",$this->getValue('inventory_frequence'),1,240);
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][32]."</td>";
		echo "<td>";
      Dropdown::showInteger("delete_agent_process",$this->getValue('delete_agent_process'),1,240);
      echo " ".$LANG['gmt'][1];
		echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][26]." (ex : https://192.168.0.1/glpi)</td>";
      echo "<td>";
      echo "<input type='text' name='URL_agent_conf' size='30' value='".$this->getValue('URL_agent_conf')."' />";
      echo "</td>";
      echo "<td>";
      echo "</td>";
      echo "<td>";
      echo "</td>";
      echo "</tr>";

		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["discovery"][6]."&nbsp;:";
		echo "</th>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["discovery"][6]." 2&nbsp;:";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td width='500'>".$LANG["networking"][14]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_ip", $this->isActivated('criteria1_ip'));
		echo "</td>";
		echo "<td width='500'>".$LANG["networking"][14]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_ip", $this->isActivated('criteria2_ip'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_name", $this->isActivated('criteria1_name'));
		echo "</td>";
		echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_name", $this->isActivated('criteria2_name'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][19]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_serial", $this->isActivated('criteria1_serial'));
		echo "</td>";
		echo "<td>".$LANG["common"][19]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_serial", $this->isActivated('criteria2_serial'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['device_iface'][2]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_macaddr", $this->isActivated('criteria1_macaddr'));
		echo "</td>";
		echo "<td>".$LANG['device_iface'][2]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_macaddr", $this->isActivated('criteria2_macaddr'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='4'>";
      if (PluginFusioninventory::haveRight("configuration","w")) {
   		echo "<input type='hidden' name='tabs' value='config' />";
   		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' >";
      }
      echo "</td>";
      echo "</tr>";
		echo "</table></form>";

	}
}

?>