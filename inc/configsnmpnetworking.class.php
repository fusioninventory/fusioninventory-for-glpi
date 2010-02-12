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


class PluginFusionInventoryConfigSNMPNetworking extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_config_snmp_networking";
		$this->type=-1;
	}	


	function initConfig() {
		global $DB;
		
		$query = "INSERT INTO ".$this->table."(
                            `ID`, `active_device_state`, `history_wire`, `history_ports_state`,
                            `history_unknown_mac`, `history_snmp_errors`, `history_process`)
                VALUES ('1', '0', '0', '0', '0', '0', '0');";
		
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


	
	function showForm($target,$ID) {
		global $LANG;
		
		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["functionalities"][3]." - ".$LANG["Menu"][1]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][20]."</td>";
		echo "<td>";
		dropdownValue("glpi_dropdown_state", "active_device_state", $this->getValue("active_device_state"));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][21]."</td>";
		echo "<td>";
		dropdownInteger("history_wire", $this->getValue('history_wire'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][22]."</td>";
		echo "<td>";
		dropdownInteger("history_ports_state", $this->getValue('history_ports_state'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][23]."</td>";
		echo "<td>";
		dropdownInteger("history_unknown_mac", $this->getValue('history_unknown_mac'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][24]."</td>";
		echo "<td>";
		dropdownInteger("history_snmp_errors", $this->getValue('history_snmp_errors'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][25]."</td>";
		echo "<td>";
		dropdownInteger("history_process", $this->getValue('history_process'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='hidden' name='tabs' value='snmp_networking' />";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";	
	}

   function CleanHistory($option) {
      global $DB;

      switch ($option) {
         case "history_process":
            if ($this->getValue("history_process") != 0) {
               $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_processes`
                                WHERE `start_time` < date_add(now(),interval -".
                                       $this->getValue("history_process")." day);";
               $DB->query($query_delete);
            }
            break;
      }
   }
}


?>