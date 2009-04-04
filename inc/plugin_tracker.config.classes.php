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

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}


class plugin_tracker_config extends CommonDBTM {

	function plugin_tracker_config() {
		$this->table="glpi_plugin_tracker_config";
		$this->type=-1;
	}

	function initConfig() {
		global $DB;
		
		$query = "INSERT INTO ".$this->table." ".
				 "(ID, 	activation_history, activation_connection, activation_snmp_networking, activation_snmp_peripheral, activation_snmp_phone, activation_snmp_printer, authsnmp) ".
				 "VALUES ('1', '0', '0', '0', '0', '0', '0', 'DB')";
		
		$DB->query($query);
	}
	
	/* Function to get the value of a field */
	function getValue($field) {
		global $DB;

		$query = "SELECT ".$field." FROM ".$this->table." ".
				 "WHERE ID = '1'";
		if ( $result = $DB->query($query) ) {
			if ( $this->fields = $DB->fetch_row($result) )
				return $this->fields['0'];
		}
		return false;
	}

	// Confirm if the functionality is activated, or not
	function isActivated($functionality) {
		
		if ( !($this->getValue($functionality)) )
			return false;
		else
			return true;
	}
	
	function showTabs ($type) {

		GLOBAL $LANG,$LANGTRACKER;
		
		if (haveRight("config","w")) {
			// tabs
			echo "<div id='barre_onglets'>\n";
			echo "<ul id='onglet'>\n";
			
			echo "<li";
			if ($type == "general")
				echo " class='actif'";			
			echo "><a href='plugin_tracker.functionalities.form.php'>&nbsp;".$LANGTRACKER["functionalities"][2]."&nbsp;</a></li>\n";
			echo "<li";
			if ($type == "smp-script")
				echo " class='actif'";			
			echo "><a href='plugin_tracker.functionalities.form.snmp-script.php'>&nbsp;".$LANGTRACKER["functionalities"][3]." - ".$LANGTRACKER["functionalities"][5]."&nbsp;</a></li>\n";

			if ($this->getValue("activation_snmp_networking") == "1")
			{
				echo "<li";
				if ($type == "smp-networking")
					echo " class='actif'";
				echo "><a href='plugin_tracker.functionalities.form.snmp-networking.php'>&nbsp;".$LANGTRACKER["functionalities"][3]." - ".$LANG["Menu"][1]."&nbsp;</a></li>\n";
			}
			if ($this->getValue("activation_snmp_peripheral") == "1")
			{
				echo "<li";
				if ($type == "smp-peripheral")
					echo " class='actif'";
				echo "><a href='plugin_tracker.functionalities.form.snmp-peripheral.php'>&nbsp;".$LANGTRACKER["functionalities"][3]." - ".$LANG["Menu"][16]."&nbsp;</a></li>\n";
			}
			if ($this->getValue("activation_snmp_printer") == "1")
			{
				echo "<li";
				if ($type == "smp-printer")
					echo " class='actif'";
				echo "><a href='plugin_tracker.functionalities.form.snmp-printers.php'>&nbsp;".$LANGTRACKER["functionalities"][3]." - ".$LANG["Menu"][2]."&nbsp;</a></li>\n";
			}
			if ($this->getValue("activation_snmp_phone") == "1")
			{
				echo "<li";
				if ($type == "smp-phone")
					echo " class='actif'";
				echo "><a href='plugin_tracker.functionalities.form.snmp-phone.php'>&nbsp;".$LANGTRACKER["functionalities"][3]." - ".$LANG["Menu"][34]."&nbsp;</a></li>\n";
			}
			if ($this->getValue("activation_connection") == "1")
			{
				echo "<li><a href=''>&nbsp;".$LANGTRACKER["functionalities"][4]."&nbsp;</a></li>\n";
			}
			echo "<ul>\n";
			echo "</div>\n";


			/*echo "<div align='center'><form method='post' name='functionalities_form' id='functionalities_form'  action=\"".$target."\">";
	
			echo "<table class='tab_cadre_fixe' cellpadding='5'><tr><th colspan='2'>";
			echo $LANGTRACKER["functionalities"][1]." :</th></tr>";
			
			echo "<tr class='tab_bg_1'><th colspan='2'>";
			echo $LANGTRACKER["functionalities"][10]." :</th></tr>";
			
/*			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][11]." ";
			echo "<img src='/glpi/pics/aide.png' alt=\"\" onmouseout=\"setdisplay(getElementById('wire_control_info'),'none')\" onmouseover=\"setdisplay(getElementById('wire_control_info'),'block')\"><span class='over_link' id='wire_control_info'>".$LANGTRACKER["functionalities"][12]."</span>";
			echo "</td>";
			echo "<td>";
			dropdownYesNo("wire_control", $this->isActivated('wire_control'));
			echo "</td></tr>";
*/			
			/*echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][13]."</td>";
			echo "<td>";
			dropdownYesNo("computers_history", $this->isActivated('computers_history'));
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][14]."</td>";
			echo "<td>";
			dropdownYesNo("update_contact", $this->isActivated('update_contact'));
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][15]."</td>";
			echo "<td>";
			dropdownYesNo("update_user", $this->isActivated('update_user'));
			echo "</td></tr>";

			echo "<tr class='tab_bg_1'><th colspan='2'>";
			echo $LANGTRACKER["functionalities"][20]." :</th></tr>";
			
/*			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][21]."</td>";
			echo "<td>";
			dropdownYesNo("counters_statement", $this->isActivated('counters_statement'));
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][22]." ";
			echo "<img src='/glpi/pics/aide.png' alt=\"\" onmouseout=\"setdisplay(getElementById('info_statement_default_value'),'none')\" onmouseover=\"setdisplay(getElementById('info_statement_default_value'),'block')\"><span class='over_link' id='info_statement_default_value'>".$LANGTRACKER["functionalities"][23]."</span>";
			echo "</td>";
			echo "<td>";
			dropdownYesNo("statement_default_value", $this->isActivated('statement_default_value'));
			echo "</td></tr>";
*/			
			/*echo "<tr class='tab_bg_1'><th colspan='2'>";
			echo $LANGTRACKER["functionalities"][30]." :</th></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][31]."</td>";
			echo "<td>";
			dropdownYesNo("cleaning", $this->isActivated('cleaning'));
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][32]."</td>";
			echo "<td>";
			echo "<input type='text' name='cleaning_days' value='".$this->getValue("cleaning_days")."' size='6'>";
			echo "</td></tr>";
			
			echo "<tr class='tab_bg_1'><th colspan='2'>";
			echo $LANGTRACKER["functionalities"][40]." :</th></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][41]."</td>";
			echo "<td>";
			dropdownValue("glpi_dropdown_state", "active_device_state", $this->getValue("active_device_state"));
			echo "</td></tr>";
			
/*			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][42]."</td>";
			echo "<td>";
			dropdownValue("glpi_type_networking", "networking_switch_type", $this->getValue("networking_switch_type"));
			echo "</td></tr>";
*/
			/*echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][43]."</td>";
			echo "<td>";
			echo "<select name='authsnmp'>";
			echo "<option>-----</option>";
			$selected = "";
			if ($this->getValue("authsnmp") == "DB")
				$selected = "selected";
			echo "<option value='DB' ".$selected.">".$LANGTRACKER["functionalities"][44]."</option>";
			$selected = "";
			if ($this->getValue("authsnmp") == "file")
				$selected = "selected";
			echo "<option value='file' ".$selected.">".$LANGTRACKER["functionalities"][45]."</option>";
			echo "</select>";
			echo "</td></tr>";

			echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
			echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";	
			echo "</table></form></div>";
			*/
		}
	}
	

	
	function showForm_general($target,$ID)
	{
		GLOBAL $LANG,$LANGTRACKER;
		
		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANGTRACKER["functionalities"][1]." :";
		echo "</th>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][10]."</td>";
		echo "<td>";
		dropdownYesNo("activation_history", $this->isActivated('activation_history'));
		echo "</td>";
		echo "</tr>";
/* Disable for the moment SEE IT WALID
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][11]."</td>";
		echo "<td>";
		dropdownYesNo("activation_connection", $this->isActivated('activation_connection'));
		echo "</td>";
		echo "</tr>";		
*/
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][12]."</td>";
		echo "<td>";
		dropdownYesNo("activation_snmp_networking", $this->isActivated('activation_snmp_networking'));
		echo "</td>";
		echo "</tr>";	
/* Disable PERIPHERAL because not completely implemented
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][13]."</td>";
		echo "<td>";
		dropdownYesNo("activation_snmp_peripheral", $this->isActivated('activation_snmp_peripheral'));
		echo "</td>";
		echo "</tr>";
*/
/* Disable PHONE because not completely implemented
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][14]."</td>";
		echo "<td>";
		dropdownYesNo("activation_snmp_phone", $this->isActivated('activation_snmp_phone'));
		echo "</td>";
		echo "</tr>";	
*/
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][15]."</td>";
		echo "<td>";
		dropdownYesNo("activation_snmp_printer", $this->isActivated('activation_snmp_printer'));
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][16]."</td>";
		echo "<td>";
		unset($ArrayValues);
		$ArrayValues['DB']= $LANGTRACKER["functionalities"][17];
		$ArrayValues['file']= $LANGTRACKER["functionalities"][18];
		dropdownArrayValues('authsnmp', $ArrayValues,$this->getValue('authsnmp'));
		echo "</td></tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";		
	}
}



class glpi_plugin_tracker_config_snmp_script extends CommonDBTM {

	function glpi_plugin_tracker_config_snmp_script() {
		$this->table="glpi_plugin_tracker_config_snmp_script";
		$this->type=-1;
	}


	function initConfig() {
		global $DB;
		
		$query = "INSERT INTO ".$this->table." ".
				 "(ID, nb_process, logs,`lock`) ".
				 "VALUES ('1', '1', '0', '0')";
		
		$DB->query($query);
	}
	

	/* Function to get the value of a field */
	function getValue($field) {
		global $DB;

		$query = "SELECT ".$field." FROM ".$this->table." ".
				 "WHERE ID = '1'";
		$result = $DB->query($query);
		if ( $this->fields = $DB->fetch_row($result) )
			return $this->fields['0'];
		return false;
	}


	function showForm($target,$ID)
	{
		GLOBAL $LANG,$LANGTRACKER;
		
		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANGTRACKER["functionalities"][1]." :";
		echo "</th>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][53]."</td>";
		echo "<td>";
		dropdownInteger("nb_process", $this->getValue('nb_process'),1,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][52]."</td>";
		echo "<td>";
		unset($ArrayValues);
		$ArrayValues[]= $LANG["choice"][0];
		$ArrayValues[]= $LANG["choice"][1];
		$ArrayValues[]= $LANG["setup"][137];
		dropdownArrayValues('logs', $ArrayValues,$this->getValue('logs'));
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["agents"][6]."</td>";
		echo "<td>";
		dropdownYesNo("lock", $this->getValue('`lock`'));
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";	
	}
}



class plugin_tracker_config_snmp_networking extends CommonDBTM {

	function plugin_tracker_config_snmp_networking() {
		$this->table="glpi_plugin_tracker_config_snmp_networking";
		$this->type=-1;
	}	


	function initConfig() {
		global $DB;
		
		$query = "INSERT INTO ".$this->table." ".
				 "(ID, active_device_state, history_wire, history_ports_state, history_unknown_mac, history_snmp_errors, history_process) ".
				 "VALUES ('1', '0', '0', '0', '0', '0', '0')";
		
		$DB->query($query);
	}
	

	/* Function to get the value of a field */
	function getValue($field) {
		global $DB;

		$query = "SELECT ".$field." FROM ".$this->table." ".
				 "WHERE ID = '1'";
		if ( $result = $DB->query($query) ) {
			if ( $this->fields = $DB->fetch_row($result) )
				return $this->fields['0'];
		}
		return false;
	}

	// Confirm if the functionality is activated, or not
	function isActivated($functionality) {
		
		if ( !($this->getValue($functionality)) )
			return false;
		else
			return true;
	}


	
	function showForm($target,$ID)
	{
		GLOBAL $LANG,$LANGTRACKER;
		
		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANGTRACKER["functionalities"][1]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][20]."</td>";
		echo "<td>";
		dropdownValue("glpi_dropdown_state", "active_device_state", $this->getValue("active_device_state"));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][21]."</td>";
		echo "<td>";
		dropdownInteger("history_wire", $this->getValue('history_wire'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][22]."</td>";
		echo "<td>";
		dropdownInteger("history_ports_state", $this->getValue('history_ports_state'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][23]."</td>";
		echo "<td>";
		dropdownInteger("history_unknown_mac", $this->getValue('history_unknown_mac'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][24]."</td>";
		echo "<td>";
		dropdownInteger("history_snmp_errors", $this->getValue('history_snmp_errors'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][25]."</td>";
		echo "<td>";
		dropdownInteger("history_process", $this->getValue('history_process'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";	
	}
}



class plugin_tracker_config_snmp_printer extends CommonDBTM {

	function plugin_tracker_config_snmp_printer() {
		$this->table="glpi_plugin_tracker_config_snmp_printer";
		$this->type=-1;
	}	


	function initConfig() {
		global $DB;
		
		$query = "INSERT INTO ".$this->table." ".
				 "(ID, active_device_state, manage_cartridges) ".
				 "VALUES ('1', '0', '0')";
		
		$DB->query($query);
	}
	

	/* Function to get the value of a field */
	function getValue($field) {
		global $DB;

		$query = "SELECT ".$field." FROM ".$this->table." ".
				 "WHERE ID = '1'";
		if ( $result = $DB->query($query) ) {
			if ( $this->fields = $DB->fetch_row($result) )
				return $this->fields['0'];
		}
		return false;
	}

	// Confirm if the functionality is activated, or not
	function isActivated($functionality) {
		
		if ( !($this->getValue($functionality)) )
			return false;
		else
			return true;
	}


	
	function showForm($target,$ID)
	{
		GLOBAL $LANG,$LANGTRACKER;
		
		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANGTRACKER["functionalities"][1]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANGTRACKER["functionalities"][30]."</td>";
		echo "<td>";
		dropdownValue("glpi_dropdown_state", "active_device_state", $this->getValue("active_device_state"));
		echo "</td>";
		echo "</tr>";

//		echo "<tr class='tab_bg_1'>";
//		echo "<td>".$LANGTRACKER["functionalities"][31]."</td>";
//		echo "<td>";
//		dropdownYesNo("manage_cartridges", $this->isActivated('manage_cartridges'));
//		echo "</td>";
//		echo "</tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";	
	}
}		
		
		
		
?>