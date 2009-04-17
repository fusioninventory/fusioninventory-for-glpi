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

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}

function plugin_tracker_createfirstaccess($ID){

	global $DB;
	
	$plugin_tracker_Profile=new plugin_tracker_Profile();
	if (!$plugin_tracker_Profile->GetfromDB($ID)){
		
		$Profile=new Profile();
		$Profile->GetfromDB($ID);
		$name=$Profile->fields["name"];

		$query = "INSERT INTO `glpi_plugin_tracker_profiles` ".
				 "( `ID`, `name` , `interface`, `is_default`, `snmp_networking`, `snmp_peripherals`, `snmp_printers`, `snmp_models`, `snmp_authentification`, `snmp_scripts_infos`, `snmp_discovery`, `general_config` ) ".
				 "VALUES ('$ID', '$name','tracker','0','w','w','w','w','w','w','w','w');";
		$DB->query($query);
	}
}

function plugin_tracker_createaccess($ID){

	GLOBAL $DB;
	
	$Profile=new Profile();
	$Profile->GetfromDB($ID);
	$name=$Profile->fields["name"];
	
	$query = "INSERT INTO `glpi_plugin_tracker_profiles` ".
			 "( `ID`, `name` , `interface`, `is_default`, `snmp_networking`, `snmp_peripherals`, `snmp_printers`, `snmp_models`, `snmp_authentification`, `snmp_scripts_infos`, `snmp_discovery`, `general_config` ) ".
			 "VALUES ('$ID', '$name','tracker','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);";
	$DB->query($query);
}

function getIdFromUser($name) {
	
	global $DB;
	
	$query = "SELECT ID ".
			 "FROM glpi_users ".
			 "WHERE name = '".$name."';";

	if ( ($result = $DB->query($query)) ) {
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
	if ( $result = $DB->query($query) ) {
		if ( ($fields=$DB->fetch_row($result)) && ($fields['0'] != NULL) )
			return $fields['0'];
	}
	return $return;
}


?>