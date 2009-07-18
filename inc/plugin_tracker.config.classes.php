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


class plugin_tracker_config extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_tracker_config";
		$this->type=PLUGIN_TRACKER_SNMP_CONFIG;
	}

	function initConfig($version) {
		global $DB,$CFG_GLPI;
		$url = str_replace("http:","https:",$CFG_GLPI["url_base"]);
		$query = "INSERT INTO ".$this->table." ".
				 "(ID, version,	activation_history, activation_connection, activation_snmp_networking, activation_snmp_peripheral, activation_snmp_phone, activation_snmp_printer, authsnmp, URL_agent_conf, ssl_only) ".
				 "VALUES ('1', '".$version."', '0', '0', '0', '0', '0', '0', 'DB', '".$url."', '1')";
		
		$DB->query($query);
	}
	
	/* Function to get the value of a field */
	function getValue($field) {
		global $DB;

		$query = "SELECT ".$field." FROM ".$this->table." ".
				 "WHERE ID = '1'";
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
		GLOBAL $LANG,$CFG_GLPI;

		$ong[1]=$LANG['plugin_tracker']["functionalities"][2];
		$ong[2]=$LANG['plugin_tracker']["functionalities"][3]." - ".$LANG['plugin_tracker']["functionalities"][5];
		$ong[3]=$LANG['plugin_tracker']["functionalities"][3]." - ".$LANG['plugin_tracker']["discovery"][3];
		if ($this->getValue("activation_snmp_networking") == "1") {
			$ong[4]=$LANG['plugin_tracker']["functionalities"][3]." - ".$LANG["Menu"][1];
      }
		//$ong[5]=$LANG['plugin_tracker']["functionalities"][3]." - ".$LANG["Menu"][16];
		if ($this->getValue("activation_snmp_printer") == "1") {
			$ong[6]=$LANG['plugin_tracker']["functionalities"][3]." - ".$LANG["Menu"][2];
      }
		//$ong[7]=$LANG['plugin_tracker']["functionalities"][3]." - ".$LANG["Menu"][34];
		//$ong[8]=$LANG['plugin_tracker']["functionalities"][4];
		return $ong;
	}
	

	
	function showForm($target,$ID) {
		GLOBAL $LANG,$CFG_GLPI;

		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_tracker']["functionalities"][2]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][27]."</td>";
		echo "<td>";
		dropdownYesNo("ssl_only", $this->isActivated('ssl_only'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][26]." (ex : https://192.168.0.1/glpi)</td>";
		echo "<td>";
		echo "<input type='text' name='URL_agent_conf' size='30' value='".$this->getValue('URL_agent_conf')."' />";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][10]."</td>";
		echo "<td>";
		dropdownYesNo("activation_history", $this->isActivated('activation_history'));
		echo "</td>";
		echo "</tr>";
/* Disable for the moment SEE IT WALID
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][11]."</td>";
		echo "<td>";
		dropdownYesNo("activation_connection", $this->isActivated('activation_connection'));
		echo "</td>";
		echo "</tr>";		
*/
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][12]."</td>";
		echo "<td>";
		dropdownYesNo("activation_snmp_networking", $this->isActivated('activation_snmp_networking'));
		echo "</td>";
		echo "</tr>";	
/* Disable PERIPHERAL because not completely implemented
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][13]."</td>";
		echo "<td>";
		dropdownYesNo("activation_snmp_peripheral", $this->isActivated('activation_snmp_peripheral'));
		echo "</td>";
		echo "</tr>";
*/
/* Disable PHONE because not completely implemented
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][14]."</td>";
		echo "<td>";
		dropdownYesNo("activation_snmp_phone", $this->isActivated('activation_snmp_phone'));
		echo "</td>";
		echo "</tr>";	
*/
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][15]."</td>";
		echo "<td>";
		dropdownYesNo("activation_snmp_printer", $this->isActivated('activation_snmp_printer'));
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][16]."</td>";
		echo "<td>";
		unset($ArrayValues);
		$ArrayValues['DB']= $LANG['plugin_tracker']["functionalities"][17];
		$ArrayValues['file']= $LANG['plugin_tracker']["functionalities"][18];
		dropdownArrayValues('authsnmp', $ArrayValues,$this->getValue('authsnmp'));
		echo "</td></tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='hidden' name='tabs' value='config' />";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";

	}
}



class plugin_tracker_config_discovery extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_tracker_config_discovery";
		$this->type=-1;
	}

	function initConfig() {
		global $DB;

		$query = "INSERT INTO ".$this->table." ".
				 "(ID, link_ip, link_name, link_serial, link2_ip, link2_name, link2_serial) ".
				 "VALUES ('1', '0', '0', '0', '0', '0', '0')";

		$DB->query($query);
	}

	/* Function to get the value of a field */
	function getValue($field) {
		global $DB;

		$query = "SELECT ".$field." FROM ".$this->table." ".
				 "WHERE ID = '1'";
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
		GLOBAL $LANG;

		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";

		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_tracker']["functionalities"][3]." - ".$LANG['plugin_tracker']["discovery"][3]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_tracker']["discovery"][6]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td width='500'>".$LANG["networking"][14]."</td>";
		echo "<td>";
		dropdownYesNo("link_ip", $this->isActivated('link_ip'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][16]."</td>";
		echo "<td>";
		dropdownYesNo("link_name", $this->isActivated('link_name'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][19]."</td>";
		echo "<td>";
		dropdownYesNo("link_serial", $this->isActivated('link_serial'));
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_tracker']["discovery"][6]." 2 :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td colspan='2'>";
		echo $LANG['plugin_tracker']["discovery"][8];
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td width='500'>".$LANG["networking"][14]."</td>";
		echo "<td>";
		dropdownYesNo("link2_ip", $this->isActivated('link2_ip'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][16]."</td>";
		echo "<td>";
		dropdownYesNo("link2_name", $this->isActivated('link2_name'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][19]."</td>";
		echo "<td>";
		dropdownYesNo("link2_serial", $this->isActivated('link2_serial'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='hidden' name='tabs' value='snmp_discovery' />";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";
	}
}



class glpi_plugin_tracker_config_snmp_script extends CommonDBTM {

	function __construct() {
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
		if ($this->fields = $DB->fetch_row($result)) {
			return $this->fields['0'];
      }
		return false;
	}


	function showForm($target,$ID) {
		GLOBAL $LANG;
		
		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_tracker']["functionalities"][3]." - ".$LANG['plugin_tracker']["functionalities"][5]." :";
		echo "</th>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][53]."</td>";
		echo "<td>";
		dropdownInteger("nb_process", $this->getValue('nb_process'),1,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][52]."</td>";
		echo "<td>";
		unset($ArrayValues);
		$ArrayValues[]= $LANG["choice"][0];
		$ArrayValues[]= $LANG["choice"][1];
		$ArrayValues[]= $LANG["setup"][137];
		dropdownArrayValues('logs', $ArrayValues,$this->getValue('logs'));
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["agents"][6]."</td>";
		echo "<td>";
		dropdownYesNo("lock", $this->getValue('`lock`'));
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='hidden' name='tabs' value='snmp_script' />";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";	
	}
}



class plugin_tracker_config_snmp_networking extends CommonDBTM {

	function __construct() {
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
		GLOBAL $LANG;
		
		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_tracker']["functionalities"][3]." - ".$LANG["Menu"][1]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][20]."</td>";
		echo "<td>";
		dropdownValue("glpi_dropdown_state", "active_device_state", $this->getValue("active_device_state"));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][21]."</td>";
		echo "<td>";
		dropdownInteger("history_wire", $this->getValue('history_wire'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][22]."</td>";
		echo "<td>";
		dropdownInteger("history_ports_state", $this->getValue('history_ports_state'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][23]."</td>";
		echo "<td>";
		dropdownInteger("history_unknown_mac", $this->getValue('history_unknown_mac'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][24]."</td>";
		echo "<td>";
		dropdownInteger("history_snmp_errors", $this->getValue('history_snmp_errors'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][25]."</td>";
		echo "<td>";
		dropdownInteger("history_process", $this->getValue('history_process'),0,100);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='hidden' name='tabs' value='snmp_networking' />";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";	
	}
}



class plugin_tracker_config_snmp_printer extends CommonDBTM {

	function __construct() {
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
		GLOBAL $LANG;
		
		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_tracker']["functionalities"][3]." - ".$LANG["Menu"][2]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']["functionalities"][30]."</td>";
		echo "<td>";
		dropdownValue("glpi_dropdown_state", "active_device_state", $this->getValue("active_device_state"));
		echo "</td>";
		echo "</tr>";

//		echo "<tr class='tab_bg_1'>";
//		echo "<td>".$LANG['plugin_tracker']["functionalities"][31]."</td>";
//		echo "<td>";
//		dropdownYesNo("manage_cartridges", $this->isActivated('manage_cartridges'));
//		echo "</td>";
//		echo "</tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='hidden' name='tabs' value='snmp_printer' />";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";	
	}
}		

?>