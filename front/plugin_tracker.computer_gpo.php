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

$NEEDED_ITEMS = array("computer", "commonitem", "printer", "monitor", "peripheral", "phone", "ocsng");

define('GLPI_ROOT', '../../..'); 

include (GLPI_ROOT."/inc/includes.php");

$config = new plugin_tracker_config();

$errors = new plugin_tracker_errors();
$computers_history = new plugin_tracker_computers_history();

$computer=new Computer();

// Get date
$date = date("Y-m-d H:i:s");

// Get IP
$error["ifaddr"] = (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"]);

// Get NetBIOS : name
$error["name"] = (isset($_GET["NetBIOS"]) ? $_GET["NetBIOS"] : "");

// Get Admisys : otherserial
$error["otherserial"] = (isset($_GET["Admisys"]) ? $_GET["Admisys"] : 0);

// Get the computer username : contact
$contact = (isset($_GET["User"]) ? $_GET["User"] : "");


/// Check and write error ///
$computer_id = $errors->writeError(COMPUTER_TYPE, 'db', $error, $date);


// if computers history is active
if ( ($config->isActivated('computers_history')) && ($history['FK_computers'] = $computer_id) ) {
	
	//Get user : contact
	$history["username"] = $contact;
	
	// Get state of computer session : 0, 1 or 2, i.e. : Off, On or Connected
	$history["state"] = (isset($_GET["State"]) ? $_GET["State"] : "");
	
	$computers_history->addHistory($history, $date);
	
}

// fields to update into glpi_computers table
$update = array();

if ( $config->isActivated('update_contact') ) {
	if ( $contact != "")
		$update['contact'] = $contact;
}

if ( $config->isActivated('update_user') ) {
	if ( isset($_GET["GLPI_User"]) ) {
		if ( !($update['FK_users'] = getIdFromUser($_GET["GLPI_User"])) )
			unset($update['FK_users']);
	}
}

if ( (count($update)) != 0 ) {
	$update['ID'] = $computer_id;
	$computer->update($update);
}

?>
