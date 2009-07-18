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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

function plugin_tracker_createfirstaccess($ID) {

	global $DB;
	
	$plugin_tracker_Profile=new plugin_tracker_Profile();
	if (!$plugin_tracker_Profile->GetfromDB($ID)) {
		$Profile=new Profile();
		$Profile->GetfromDB($ID);
		$name=$Profile->fields["name"];

		$query = "INSERT INTO `glpi_plugin_tracker_profiles` ".
				 "( `ID`, `name`, `interface`, `is_default`, `snmp_networking`, `snmp_printers`, `snmp_models`, `snmp_authentification`, `snmp_scripts_infos`, `snmp_discovery`, `general_config`, `snmp_iprange`, `snmp_agent`, `snmp_agent_infos`, `snmp_report` ) ".
				 "VALUES ('$ID', '$name','tracker','0','w','w','w','w','w','w','w','w','w','w','w');";
		$DB->query($query);
	}
}

function plugin_tracker_createaccess($ID) {

	GLOBAL $DB;
	
	$Profile=new Profile();
	$Profile->GetfromDB($ID);
	$name=$Profile->fields["name"];
	
	$query = "INSERT INTO `glpi_plugin_tracker_profiles` ".
			 "( `ID`, `name` , `interface`, `is_default`, `snmp_networking`, `snmp_printers`, `snmp_models`, `snmp_authentification`, `snmp_scripts_infos`, `snmp_discovery`, `general_config`, `snmp_iprange`, `snmp_agent`, `snmp_agent_infos`, `snmp_report` ) ".
			 "VALUES ('$ID', '$name','tracker','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);";
	$DB->query($query);
}

function plugin_tracker_getIdFromUser($name) {
	
	global $DB;
	
	$query = "SELECT ID ".
			 "FROM glpi_users ".
			 "WHERE name = '".$name."';";

	if (($result = $DB->query($query))) {
		return $DB->result($result,0,"ID");
	}
	return false;
}

function plugin_tracker_getDeviceFieldFromId($type, $ID, $field, $return) {
	global $DB;
	switch($type) {
		case COMPUTER_TYPE:
			$table = "glpi_computers";
			break;
			
		case NETWORKING_TYPE:
			$table = "glpi_networking";
			break;
			
		case PRINTER_TYPE:
			$table = "glpi_printers";
			break;
			
		case USER_TYPE:
			$table = "glpi_users";
			break;
			
		default:
			return $return;
			break;
	}
	
	$query = "SELECT ".$field." FROM ".$table." ".
			 "WHERE ID = '".$ID."';";
	if ($result = $DB->query($query)) {
		if (($fields=$DB->fetch_row($result)) && ($fields['0'] != NULL)) {
			return $fields['0'];
      }
	}
	return $return;
}


function plugin_tracker_clean_db() {
	GLOBAL $DB;
	
	// * Clean glpi_plugin_tracker_networking_ports
	$query_select = "SELECT glpi_plugin_tracker_networking_ports.ID FROM glpi_plugin_tracker_networking_ports
	LEFT JOIN glpi_networking_ports ON glpi_networking_ports.ID = FK_networking_ports
	LEFT JOIN glpi_networking ON glpi_networking.ID = on_device
	WHERE glpi_networking.ID IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
		$query_delete = "DELETE FROM glpi_plugin_tracker_networking_ports
		WHERE ID='".$data["ID"]."'";
		$DB->query($query_delete);
	}
	
	// * Clean glpi_plugin_tracker_networking
	$query_select = "SELECT glpi_plugin_tracker_networking.ID FROM glpi_plugin_tracker_networking
	LEFT JOIN glpi_networking ON glpi_networking.ID = FK_networking
	WHERE glpi_networking.ID IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
		$query_delete = "DELETE FROM glpi_plugin_tracker_networking
		WHERE ID='".$data["ID"]."'";
		$DB->query($query_delete);
	}
	
	// * Clean glpi_plugin_tracker_printers
	$query_select = "SELECT glpi_plugin_tracker_printers.ID FROM glpi_plugin_tracker_printers
	LEFT JOIN glpi_printers ON glpi_printers.ID = FK_printers
	WHERE glpi_printers.ID IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
		$query_delete = "DELETE FROM glpi_plugin_tracker_printers
		WHERE ID='".$data["ID"]."'";
		$DB->query($query_delete);
	}

	// * Clean glpi_plugin_tracker_printers_cartridges
	$query_select = "SELECT glpi_plugin_tracker_printers_cartridges.ID FROM glpi_plugin_tracker_printers_cartridges
	LEFT JOIN glpi_printers ON glpi_printers.ID = FK_printers
	WHERE glpi_printers.ID IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
		$query_delete = "DELETE FROM glpi_plugin_tracker_printers_cartridges
		WHERE ID='".$data["ID"]."'";
		$DB->query($query_delete);
	}

	// * Clean glpi_plugin_tracker_printers_history
	$query_select = "SELECT glpi_plugin_tracker_printers_history.ID FROM glpi_plugin_tracker_printers_history
	LEFT JOIN glpi_printers ON glpi_printers.ID = FK_printers
	WHERE glpi_printers.ID IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
		$query_delete = "DELETE FROM glpi_plugin_tracker_printers_history
		WHERE ID='".$data["ID"]."'";
		$DB->query($query_delete);
	}
}

?>