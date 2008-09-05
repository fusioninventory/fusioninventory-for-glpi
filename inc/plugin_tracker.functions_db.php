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
// Original Author of file: Nicolas SMOLYNIEC
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}

function plugin_tracker_createfirstaccess($ID){

	GLOBAL $DB;
	
	$plugin_tracker_Profile=new plugin_tracker_Profile();
	if (!$plugin_tracker_Profile->GetfromDB($ID)){
		
		$Profile=new Profile();
		$Profile->GetfromDB($ID);
		$name=$Profile->fields["name"];

		$query = "INSERT INTO `glpi_plugin_tracker_profiles` ".
				 "( `ID`, `name` , `interface`, `is_default`, `computers_history`, `printers_history`, `printers_info`, `networking_info`, `errors` ) ".
				 "VALUES ('$ID', '$name','tracker','0','w','w','w','w','w');";
		$DB->query($query);
	}
}

function plugin_tracker_createaccess($ID){

	GLOBAL $DB;
	
	$Profile=new Profile();
	$Profile->GetfromDB($ID);
	$name=$Profile->fields["name"];
	
	$query = "INSERT INTO `glpi_plugin_tracker_profiles` ".
			 "( `ID`, `name` , `interface`, `is_default`, `computers_history`, `printers_history`, `printers_info`, `networking_info`, `errors` ) ".
			 "VALUES ('$ID', '$name','tracker','0',NULL,NULL,NULL,NULL,NULL);";
	$DB->query($query);
}

function getIdFromUser($name) {
	
	global $DB;
	
	$query = "SELECT ID ".
			 "FROM glpi_users ".
			 "WHERE name = '".$name."';";
	
	if ( ($result = $DB->query($query)) ) {
		if ( ($fields = $DB->fetch_row($result)) )
			return $fields['0'];
	}
	return false;
}

/* to get entity ID from a device */
function getDeviceEntity($type, $ID) {
	global $DB;
	
	switch($type) {
		
		case COMPUTER_TYPE:
			$query = "SELECT FK_entities FROM glpi_computers".
					 "WHERE ID = '".$ID."';";
			if ( $result = $DB->query($query) )
				if ( $fields=$DB->fetch_row($result) ) {
					return $fields['0'];
				}
			return false;
			break;
			
		case NETWORKING_TYPE:
			$query = "SELECT FK_entities FROM glpi_networking".
					 "WHERE ID = '".$ID."';";
			if ( $result = $DB->query($query) )
				if ( $fields=$DB->fetch_row($result) ) {
					return $fields['0'];
				}
			return false;
			break;
			
		case PRINTER_TYPE:
			$query = "SELECT FK_entities FROM glpi_printers".
					 "WHERE ID = '".$ID."';";
			if ( $result = $DB->query($query) )
				if ( $fields=$DB->fetch_row($result) ) {
					return $fields['0'];
				}
			return false;
			break;
	}
}

?>