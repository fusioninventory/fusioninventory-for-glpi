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
				 "(ID, computers_history, update_contact, update_user, wire_control, counters_statement, statement_default_value, cleaning, cleaning_days, active_device_state, networking_switch_type) ".
				 "VALUES ('1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
		
		$DB->query($query);
	}
	
/*	function updateConfig($input) {
		global $DB;

		$query = "UPDATE ".$this->table." ".
				 "SET computers_history = '".$newconfig['computers_history']."', ".
					 "contact_field = '".$newconfig['contact_field']."', ".
					 "user_field = '".$newconfig['user_field']."', ".
					 "wire_control = '".$newconfig['wire_control']."', ".
					 "printing_counters = '".$newconfig['printing_counters']."', ".
				     "cleaning_history = '".$newconfig['cleaning_history']."', ".
				     "cleaning_frequency = '".$newconfig['cleaning_frequency']."' ".
				 "WHERE ID = '1'";
		
		$DB->query($query);
			
	}
*/
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
	
	function showForm ($target,$ID) {

		GLOBAL $LANG, $LANGTRACKER;
		
		if (haveRight("config","w")) {
			// tabs
			echo "<div id='barre_onglets'>\n";
			echo "<ul id='onglet'>\n";
			
			echo "<li><a href=''>&nbsp;".$LANGTRACKER["functionalities"][2]."&nbsp;</a></li>\n";
			
			echo "<li><a href=''>&nbsp;".$LANGTRACKER["functionalities"][3]." - ".$LANG["Menu"][1]."&nbsp;</a></li>\n";

			echo "<li><a href=''>&nbsp;".$LANGTRACKER["functionalities"][3]." - ".$LANG["Menu"][2]."&nbsp;</a></li>\n";

			echo "<li><a href=''>&nbsp;".$LANGTRACKER["functionalities"][4]."&nbsp;</a></li>\n";

			echo "<ul>\n";
			echo "</div>\n";




			echo "<div align='center'><form method='post' name='functionalities_form' id='functionalities_form'  action=\"".$target."\">";
	
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
			echo "<tr class='tab_bg_1'>";
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
			echo "<tr class='tab_bg_1'><th colspan='2'>";
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
			echo "<tr class='tab_bg_1'>";
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
		}
	}
}

?>