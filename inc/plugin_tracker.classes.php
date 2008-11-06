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

class plugin_tracker_errors extends CommonDBTM {

	function plugin_tracker_errors() {
		$this->table="glpi_plugin_tracker_errors";
		$this->type=PLUGIN_TRACKER_ERROR_TYPE;
		$this->entity_assign = true;
	}
	
	/* Useful function for : getIDandNewDescrFromDevice */
	function getIDandDescrFromDevice($device_type, $value) {
		global $DB;
		
		if ( $device_type == COMPUTER_TYPE)
			$field = 'ifaddr';
		else // networking or printer
			$field = 'device_id';

		$query = "SELECT ID, description ".
				 "FROM ".$this->table." ".
				 "WHERE ".$field." = '".$value."' ".
				 "AND device_type = '".$device_type."';";
		
		if ( ($result = $DB->query($query)) ) {
			if ( ($this->fields = $DB->fetch_assoc($result)) )
				return true;
		}
		return false;
	}
	
	/**
	 * Function that finds if there is already an entry for a device in errors table.
	 * Returns new description error and ID if an entry already exists, else : false.
	 * 
	 * $identifiant : ip for a computer, device ID for the others
	 * $device_type : type of the device
	 * $error_type : type of error : snmp, entries in GLPI DB, etc...
	 * $new_error : description of the new error
	 * 
	 * => Puts ID and description into $this->fields
	 */
	function getIDandNewDescrFromDevice($device_type, $identifiant, $error_type, $new_error) {
			
		global $LANGTRACKER;

		if ( !($this->getIDandDescrFromDevice($device_type, $identifiant)) )
				return false;

		// string to be checked if already exists into description
		if ( $error_type == 'db' )
			$string = $LANGTRACKER["errors"][10];
		if ( $error_type == 'snmp' )
			$string = $LANGTRACKER["errors"][20];
		if ( $error_type == 'wire' )
			$string = $LANGTRACKER["errors"][30];

		$description = explode('. ', $this->fields['description']);
		$num = count($description);
		$i = 0;
		$find = false;
		
		while ( ($i<$num) && ($find == false) ) {
			if ( strstr($description["$i"], $string) ) {
				$description["$i"] = " ".$new_error;
				$find = true;
			}
		$i++;
		}
		if ( $find == false )
			$description["$num"] = " ".$new_error;

		$this->fields['description'] = implode('. ', $description);
		
		return true;
	}
	
	/* returns false if can't find computer (by IP, name or otherserial), else returns the ID of the computer */
	function writeComputerDbError($device_type, $input) {
		global $LANGTRACKER;
		global $DB;
		
		if ( !($input['ifaddr'] && $input['name']) )
			return false;
			
		// Trying to find ID by IP
		$query = "SELECT pc.ID AS ID, pc.name AS name, pc.otherserial AS otherserial, pc.FK_entities AS FK_entities ".
				 "FROM glpi_computers pc, glpi_networking_ports port ".
	   			 "WHERE port.device_type = ".$device_type." ".
	   			 "AND port.ifaddr = '".$input['ifaddr']."' ".
	   			 "AND port.on_device = pc.ID;";
		
		// else, find ID by name
		$query2 = "SELECT ID, otherserial, FK_entities ".
				  "FROM glpi_computers pc ".
				  "WHERE pc.name = '".$input['name']."';";
		
		// else, find ID by otherserial
		$query3 = "SELECT ID, FK_entities ".
				  "FROM glpi_computers pc ".
	   			  "WHERE pc.otherserial = '".$input['otherserial']."';";
	
		$fields = array();
		$input['description'] = "".$LANGTRACKER["errors"][10]." : ";
		
		// if error = 0, no error
		$error = 2;
		
		/// Query 1 : if can find ip
		if ( !($result = $DB->query($query)) )
			return false;

		else if ( $fields=$DB->fetch_assoc($result) ) {
			if ($fields['name'] == $input['name']) {
				// we only keep what is false
				$input['name'] = 'ok';
				$error--;
			}
			if ($fields['otherserial'] == $input['otherserial']) {
				$input['otherserial'] = 'ok';
				$error--;
			}
			// if no error => end and returns the ID of the device
			if ($error == 0)
				return $fields['ID'];
			else
				$input['description'] .= "IP : ok, ";
		}
		
		/// Query 2
		else if ( !($result = $DB->query($query2)) )
			return false;

		else if ( $fields=$DB->fetch_assoc($result) ) {
			$input['name'] = 'ok';
			if ($fields['otherserial'] == $input['otherserial'])
				$input['otherserial'] = 'ok';
		}
		
		/// Query 3
		else if ( !($result = $DB->query($query3)) )
			return false;

		else if ( $fields = $DB->fetch_assoc($result) )
			$input['otherserial'] = 'ok';

		// can't find computer ID
		else
			$input['description'] .= "".$LANGTRACKER["errors"][11]." ,";
		
		
		/// Get all inputs for DB
		$input['device_type'] = $device_type;

		if ( isset($fields['ID']) )
			$input['device_id'] = $fields['ID'];
		else
			$input['device_id'] = NULL;
	
		if ( isset($fields['FK_entities']) )
			$input['FK_entities'] = $fields['FK_entities'];
		else
			$input['FK_entities'] = 0;
		
		// if no description => unknown IP
		if ( !isset($input['description']) )
			$input['description'] = "".$LANGTRACKER["errors"][12]." ,";
		// add the other elements of the error messages
		$input['description'] .= "NetBIOS : ".$input['name'].", Admisys : ".$input['otherserial'];


		/// Check if this IP has already an entry in errors DB
		if ( $this->getIDandNewDescrFromDevice($device_type, $input['ifaddr'], 'db', $input['description']) ) {
			$input['ID'] = $this->fields['ID'];
			$input['description'] = $this->fields['description'];
			$this->update($input);
		}
		else {
			$input['first_pb_date'] = $input['last_pb_date'];
			$this->add($input);
		}
		
		if ( isset($fields['ID']) )
			return $fields['ID'];
			
		return false;

	}
	
	/* needs : ifaddr, device_id */
	function writeSnmpError($device_type, $input) {
		
		global $LANGTRACKER;
		
		$input['device_type'] = $device_type;
		$input['FK_entities'] = plugin_tracker_getDeviceFieldFromId($device_type, $input['device_id'], "FK_entities", false);

		$input['description'] = $LANGTRACKER["errors"][20]." : ";
		$input['description'].= $LANGTRACKER["errors"][21];
		
		// if there is already an error entry for the device
		if ( $this->getIDandNewDescrFromDevice($device_type, $input['device_id'], 'snmp', $input['description']) ) {
			$input['ID'] = $this->fields['ID'];
			$input['description'] = $this->fields['description'];
			$this->update($input);	
		}
		else {
			$input['first_pb_date'] = $input['last_pb_date'];
			$this->add($input);
		}
	}
	
	/* needs : ifaddr, device_id */
	function writeWireError($device_type, $input) {
		
		global $LANGTRACKER;
		
		$input['device_type'] = $device_type;
		$input['FK_entities'] = plugin_tracker_getDeviceFieldFromId($device_type, $input['device_id'], "FK_entities", false);
		
		$input['description'] = $LANGTRACKER["errors"][30];
		
		// if there is already an error entry for the device
		if ( $this->getIDandNewDescrFromDevice($device_type, $input['device_id'], 'wire', $input['description']) ) {
			$input['ID'] = $this->fields['ID'];
			$input['description'] = $this->fields['description'];
			$this->update($input);	
		}
		else {
			$input['first_pb_date'] = $input['last_pb_date'];
			$this->add($input);
		}
	}
	
	/**
	 * Function which writes errors in DB
	 * 
	 * $input is an array
	 * $input has to contain :
	 * - ifaddr for a computer for a wire control
	 * - ifaddr, name and otherserial in case of db control for a computer 
	 * - device_id and ifaddr for another device
	 */
	function writeError($device_type, $error_type, $input, $date) {
		
		$input['last_pb_date'] = $date;

		if ( $error_type == 'db' )
			return $this->writeComputerDbError($device_type, $input);

		else if ( $error_type == 'snmp' )
			$this->writeSnmpError($device_type, $input);
			
		else if ( $error_type == 'wire' )
			$this->writeWireError($device_type, $input);
	}

	function countEntries($type, $ID) {
		global $DB;
		
		$num = 0;
		$query = "SELECT count(DISTINCT ID) ".
				 "FROM ".$this->table." ";
		
		if ( $type == COMPUTER_TYPE )
			$query .="WHERE device_type = '".COMPUTER_TYPE."' ";
		else if ( $type == NETWORKING_TYPE )
			$query .="WHERE device_type = '".NETWORKING_TYPE."' ";
		else // $type == PRINTER_TYPE
			$query .="WHERE device_type = '".PRINTER_TYPE."' ";
			
		$query .= "AND device_id = '".$ID."';";
		
		if ( $result_num=$DB->query($query) ) {
			if ( $field = $DB->result($result_num,0,0) )
				$num += $field;
		}
		return $num;
	}

	function getEntries($type, $ID, $begin, $limit) {
		global $DB;
		
		$datas=array();
		$query = "SELECT * FROM ".$this->table." ";
		
		if ( $type == COMPUTER_TYPE )
			$query .= "WHERE device_type = '".COMPUTER_TYPE."' ";
		else if ( $type == NETWORKING_TYPE )
			$query .= "WHERE device_type = '".NETWORKING_TYPE."' ";
		else // $type == PRINTER_TYPE
			$query .= "WHERE device_type = '".PRINTER_TYPE."' ";
			
		$query .= "AND device_id = '".$ID."' ".
				  "LIMIT ".$begin.", ".$limit.";";
		
		if ( $result=$DB->query($query) ){
			$i = 0;
			while ( $data=$DB->fetch_assoc($result) ) {
				$data['first_pb_date'] = convDateTime($data['first_pb_date']);
				$data['last_pb_date'] = convDateTime($data['last_pb_date']);
				$datas["$i"] = $data;
				$i++;
			}
			return $datas;
		}
		return false;
	}
	
	function showForm($type, $target, $ID) {
			
		GLOBAL $LANG, $LANGTRACKER;
		
		if ( !plugin_tracker_haveRight("errors","r") )
			return false;
		
		// preparing to display history
		if ( !isset($_GET['start']) )
			$_GET['start'] = 0;
		
		$numrows = $this->countEntries($type, $ID);
		$parameters = "ID=".$_GET["ID"]."&onglet=".$_SESSION["glpi_onglet"];	
		
		echo "<br>";
		printPager($_GET['start'], $numrows, $_SERVER['PHP_SELF'], $parameters);

		if ( $_SESSION["glpilist_limit"] < $numrows )
			$limit = $_SESSION["glpilist_limit"];
		else
			$limit = $numrows;
			
		// Get history
		if ( !($data = $this->getEntries($type, $ID, $_GET['start'], $limit)) )
			return false;

		// for $_GET['type'] (useful to check rights)
		if ( $type == COMPUTER_TYPE )
			echo "<div align='center'><form method='post' name='errors_form' id='errors_form'  action=\"".$target."?type=".COMPUTER_TYPE."\">";
		else if ( $type == NETWORKING_TYPE )
			echo "<div align='center'><form method='post' name='errors_form' id='errors_form'  action=\"".$target."?type=".NETWORKING_TYPE."\">";
		else // $type == PRINTER_TYPE
			echo "<div align='center'><form method='post' name='errors_form' id='errors_form'  action=\"".$target."?type=".PRINTER_TYPE."\">";

		echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='5'>";
		echo $LANGTRACKER["errors"][0]." :</th></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th></th>";
		echo "<th>".$LANGTRACKER["errors"][1]." :</th>";
		echo "<th>".$LANGTRACKER["errors"][2]." :</th>";
		echo "<th>".$LANGTRACKER["errors"][3]." :</th>";
		echo "<th>".$LANGTRACKER["errors"][4]." :</th></tr>";

		for ($i=0; $i<$limit; $i++) {
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>";
			echo "<input type='checkbox' name='checked_$i' value='1'>";
			echo "</td>";
			echo "<td align='center'>".$data["$i"]['ifaddr']."</td>";
			echo "<td align='center'>".$data["$i"]['description']."</td>";
			echo "<td align='center'>".$data["$i"]['first_pb_date']."</td>";
			echo "<td align='center'>".$data["$i"]['last_pb_date']."</td>";
			echo "</td></tr>";
			echo "<input type='hidden' name='ID_$i' value='".$data["$i"]['ID']."'>";
		}
		
		if ( !plugin_tracker_haveRight("errors","w") )
			return false;
			
		echo "<input type='hidden' name='limit' value='".$limit."'>";
		echo "<tr class='tab_bg_1'><td colspan='5'>";
		echo "<div align='center'><a onclick= \"if ( markAllRows('errors_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG["buttons"][18]."</a>";
		echo " - <a onclick= \"if ( unMarkAllRows('errors_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG["buttons"][19]."</a> ";
		echo "<input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit' ></div></td></tr>";	
		echo "</table></form></div>";
	}
}

class plugin_tracker_printers_history extends CommonDBTM {

	function plugin_tracker_printers_history() {
		$this->table="glpi_plugin_tracker_printers_history";
		$this->type=-1;
	}
	
	function countAllEntries($ID) {
		global $DB;
		
		$num = 0;
		$query = "SELECT count(DISTINCT ID) ".
				 "FROM ".$this->table." ".
				 "WHERE FK_printers = '".$ID."';";
		if ( $result_num=$DB->query($query) ) {
			if ( $field = $DB->result($result_num,0,0) )
				$num += $field;
		}
		return $num;
	}

	/* Gets history (and the number of entries) of one printer */
	function getEntries($ID, $begin, $limit) {
		global $DB;
		
		$datas=array();
		$query = "SELECT * FROM ".$this->table." ".
				 "WHERE FK_printers = '".$ID."' ";
				 "LIMIT ".$begin.", ".$limit.";";

		if ( $result=$DB->query($query) ){
			$i = 0;
			while ( $data=$DB->fetch_assoc($result) ) {
				$data['date'] = convDateTime($data['date']);
				$datas["$i"] = $data;
				$i++;
			}
			return $datas;
		}
		return false;
	}
	
/*	function cleanHistory($date, $dept) {
		
	}*/
	
	function stats($ID) {
		
		global $DB;
		
		$query = "SELECT MIN(date) AS min_date, MIN(pages) AS min_pages, ".
				 		"MAX(date) AS max_date, MAX(pages) AS max_pages ".
				 "FROM ".$this->table." ".
				 "WHERE FK_printers = '".$ID."';";

		if ( $result = $DB->query($query) ) {
			if ( $fields = $DB->fetch_assoc($result) ) {
				$output['num_days'] = ceil((strtotime($fields['max_date']) - strtotime($fields['min_date']))/(60*60*24));
				$output['num_pages'] = $fields['max_pages'] - $fields['min_pages'];
				$output['pages_per_day'] = round($output['num_pages'] / $output['num_days']);
				return $output;
			}
		}
		return false;
	}
	
	function showForm($target, $ID) {
		
		GLOBAL $LANG, $LANGTRACKER;
		
		if ( !plugin_tracker_haveRight("printers_history","r") )
			return false;
		
		// display stats
		if ( $stats = $this->stats($ID) ) {
				
			echo "<br><div align = 'center'>";
			echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='2'>";
			echo $LANGTRACKER["prt_history"][10]." ".$stats["num_days"]." ".$LANGTRACKER["prt_history"][11]."</th></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["prt_history"][12]." : </td>";
			echo "<td>".$stats["num_pages"]."</td></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["prt_history"][13]." : </td>";
			echo "<td>".$stats["pages_per_day"]."</td></tr>";
			
			echo "</table></div>";
		
		}
		
		// preparing to display history
		if ( !isset($_GET['start']) )
			$_GET['start'] = 0;
		
		$numrows = $this->countAllEntries($ID);
		$parameters = "ID=".$_GET["ID"]."&onglet=".$_SESSION["glpi_onglet"];	
		
		echo "<br>";
		printPager($_GET['start'], $numrows, $_SERVER['PHP_SELF'], $parameters);

		if ( $_SESSION["glpilist_limit"] < $numrows )
			$limit = $_SESSION["glpilist_limit"];
		else
			$limit = $numrows;
			
		// Get history
		if ( !($data = $this->getEntries($ID, $_GET['start'], $limit)) )
			return false;

		echo "<div align='center'><form method='post' name='printer_history_form' id='printer_history_form'  action=\"".$target."\">";

		echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='3'>";
		echo $LANGTRACKER["prt_history"][20]." :</th></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th></th>";
		echo "<th>".$LANGTRACKER["prt_history"][21]." :</th>";
		echo "<th>".$LANGTRACKER["prt_history"][22]." :</th></tr>";

		for ($i=0; $i<$limit; $i++) {
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>";
			echo "<input type='checkbox' name='checked_$i' value='1'>";
			echo "</td>";
			echo "<td align='center'>".$data["$i"]['date']."</td>";
			echo "<td align='center'>".$data["$i"]['pages']."</td>";
			echo "</td></tr>";
			echo "<input type='hidden' name='ID_$i' value='".$data["$i"]['ID']."'>";
		}
		
		if ( !plugin_tracker_haveRight("printers_history","w") )
			return false;
			
		echo "<input type='hidden' name='limit' value='".$limit."'>";
		echo "<tr class='tab_bg_1'><td colspan='3'>";
		echo "<div align='center'><a onclick= \"if ( markAllRows('printer_history_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG["buttons"][18]."</a>";
		echo " - <a onclick= \"if ( unMarkAllRows('printer_history_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG["buttons"][19]."</a> ";
		echo "<input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit' ></div></td></tr>";	
		echo "</table></form></div>";
	}
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
			echo "<div align='center'><form method='post' name='functionalities_form' id='functionalities_form'  action=\"".$target."\">";
	
			echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='2'>";
			echo $LANGTRACKER["functionalities"][1]." :</th></tr>";
			
			echo "<tr class='tab_bg_1'><th colspan='2'>";
			echo $LANGTRACKER["functionalities"][10]." :</th></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][11]." ";
			echo "<img src='/glpi/pics/aide.png' alt=\"\" onmouseout=\"setdisplay(getElementById('wire_control_info'),'none')\" onmouseover=\"setdisplay(getElementById('wire_control_info'),'block')\"><span class='over_link' id='wire_control_info'>".$LANGTRACKER["functionalities"][12]."</span>";
			echo "</td>";
			echo "<td>";
			dropdownYesNo("wire_control", $this->isActivated('wire_control'));
			echo "</td></tr>";
			
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
			
			echo "<tr class='tab_bg_1'>";
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
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["functionalities"][42]."</td>";
			echo "<td>";
			dropdownValue("glpi_type_networking", "networking_switch_type", $this->getValue("networking_switch_type"));
			echo "</td></tr>";
						
			echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
			echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";	
			echo "</table></form></div>";
		}
	}
}

class glpi_plugin_tracker_printers_history_config extends CommonDBTM {

	function glpi_plugin_tracker_printers_history_config() {
		$this->table="glpi_plugin_tracker_printers_history_config";
		$this->type=-1;
	}
	
	/**
	 * To get value of one specific cron
	 *
	 * @return value of the counter setting
	 * @return -1 if no entry found
	 * @return false if DB connexion error (which implies same value as no activation)
	 */	
	function getCounterValue($ID) {
		global $DB;

		$query = "SELECT counter FROM ".$this->table." ".
				 "WHERE FK_printers = '".$ID."';";
		if ( $result = $DB->query($query) ) {
			if ( $this->fields = $DB->fetch_row($result) )
				return $this->fields['0'];
			else
				return -1;
		}
		return false;
	}
	
	/**
	 * To get counter state from FK_printers (i.e. : printer ID)
	 *
	 * @param $ID : ID of the printer (equiv to FK_printers into table)
	 * @return true if get an entry, otherwise false
	 */
	function getDataFromPrinterId($ID) {
		global $DB;
		$query = "SELECT ID, counter FROM ".$this->table." ".
				 "WHERE FK_printers = '".$ID."';";
		if ( $result = $DB->query($query) ){
			if ( $DB->numrows($result) == 1 ) {
				$this->fields = $DB->fetch_assoc($result);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * set cron to 1 for all printers -- not used
	 *
	 */
	function setAll() {
		global $DB;
		$query= "SELECT ID ".
				"FROM glpi_printers ".
				"WHERE 1;";
		if ( $result=$DB->query($query) ) {
			$end = $DB->numrows($result);
			if ( ($end = $DB->numrows($result)) > 0 ) {
				$fields = $DB->fetch_row($result);
				$input['counter'] = 1;
				for ($i=0; $i<$end; $i++) {
					$input['FK_printers'] = $fields['0'];
					$this->updateOne($input);
					$fields = $DB->fetch_row($result);
				}
			}
		}
	}
	
	/**
	 * set cron to 0 for all printers -- not used
	 *
	 */
	function unsetAll() {
		global $DB;
		$query = "UPDATE ".$this->table." ".
				 "SET counter='0' ".
				 "WHERE 1;";
		$DB->query($query);	
	}
	
	/**
	 * Gets the number and all the IDs of activated printers
	 *
	 * @return $datas :
	 * - $datas['number'] for the number of activated printers
	 * - $datas['$i'] : contains the activated printers ID
	 */
	function getAllActivated() {

		global $DB;
		
		$config = new plugin_tracker_config();
		$statement = $config->getValue("statement_default_value");
		
		$datas=array();
		
		// if statement is not active by default, get exceptions
		if ( !$statement ) {
			
			$query = "SELECT FK_printers FROM ".$this->table." ".
					 "WHERE counter = '1';";
		}
		// if statement is active by default, get all without the exceptions
		else {
			
			$query= "SELECT glpi_printers.ID ".
					"FROM glpi_printers ".
					"LEFT JOIN ".$this->table." ".
					"ON glpi_printers.ID = ".$this->table.".FK_printers ".
					"WHERE ".$this->table.".counter != '0' ".
					"OR ".$this->table.".counter IS NULL;";
		}
		
		if ( $result = $DB->query($query) ){
			$i = 0;
			while ( $data=$DB->fetch_row($result) ) {
				$data['FK_printers'] = $data[0];
				unset($data[0]);
				$datas["$i"] = $data;
				$i++;
			}
			$datas['number'] = count($datas);
			return $datas;
		}
		return false;		
	}

	function updateOne($input) {
		// if exists
		if ( $this->getDataFromPrinterId($input['FK_printers']) ) {
			// default value (-1) : no entry in DB
			$input['ID'] = $this->fields['ID'];
			if ( $input['counter'] == -1 )
				$this->delete($input);
			else if ( $this->fields['counter'] != $input['counter'] ) {
				$this->update($input);
			}
		}
		else {
			if ( $input['counter'] != -1 )
				$this->add($input);
		}
	}
	
	function showForm($target,$ID) {
		global $LANG, $LANGTRACKER;
		
		if ( plugin_tracker_haveRight("printers_history","w") ) {
			echo "<br>";
			echo "<div align='center'><form method='post' name='printer_history_config_form' id='printer_history_config_form'  action=\"".$target."\">";
	
			echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='2'>";
			echo $LANGTRACKER["cron"][0]." :</th></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANGTRACKER["cron"][1]."</td>";
			echo "<td align='center'>";
			plugin_tracker_dropdownDefaultYesNo("counter", $this->getCounterValue($ID));
			echo "</td>";
			echo "</tr>";
			
			echo "<tr class='tab_bg_1'><td colspan='2'>";
			echo "<input type='hidden' name='FK_printers' value='".$ID."'>";
			echo "<div align='center'><input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";	
			echo "</table></form></div>";
		}
	}
}

class plugin_tracker_Profile extends CommonDBTM {

	function plugin_tracker_Profile() {
		$this->table="glpi_plugin_tracker_profiles";
		$this->type=-1;
	}
	
	//if profile deleted
	function cleanProfiles($ID) {
	
		global $DB;
		$query = "DELETE FROM glpi_plugin_tracker_profiles WHERE ID='$ID' ";
		$DB->query($query);
	}
		
	function showprofileForm($target,$ID){
		global $LANG,$CFG_GLPI,$LANGTRACKER;

		if (!haveRight("profile","r")) return false;

		$onfocus="";
		if ($ID){
			$this->getFromDB($ID);
		} else {
			$this->getEmpty();
			$onfocus="onfocus=\"this.value=''\"";
		}

		if (empty($this->fields["interface"])) $this->fields["interface"]="tracker";
		if (empty($this->fields["name"])) $this->fields["name"]=$LANG["common"][0];


		echo "<form name='form' method='post' action=\"$target\">";
		echo "<div align='center'>";
		echo "<table class='tab_cadre'><tr>";
		echo "<th>".$LANG["common"][16].":</th>";
		echo "<th><input type='text' name='name' value=\"".$this->fields["name"]."\" $onfocus></th>";
		echo "<th>".$LANG["profiles"][2].":</th>";
		echo "<th><select name='interface' id='profile_interface'>";
		echo "<option value='tracker' ".($this->fields["interface"]!="tracker"?"selected":"").">".$LANGTRACKER["profile"][1]."</option>";

		echo "</select></th>";
		echo "</tr></table>";
		echo "</div>";
		
		$params=array('interface'=>'__VALUE__',
				'ID'=>$ID,
			);
		ajaxUpdateItemOnSelectEvent("profile_interface","profile_form",$CFG_GLPI["root_doc"]."/plugins/tracker/ajax/profiles.php",$params,false);
		ajaxUpdateItem("profile_form",$CFG_GLPI["root_doc"]."/plugins/tracker/ajax/profiles.php",$params,false,'profile_interface');
		echo "<br>";

		echo "<div align='center' id='profile_form'>";
		echo "</div>";

		echo "</form>";

	}
	
	function showtrackerForm($ID){
		global $LANG,$LANGTRACKER;

		if (!haveRight("profile","r")) return false;
		$canedit=haveRight("profile","w");

		if ($ID){
			$this->getFromDB($ID);
		} else {
			$this->getEmpty();
		}

		echo "<table class='tab_cadre'><tr>";

		echo "<tr><th colspan='2' align='center'><strong>".$LANGTRACKER["profile"][0]."</strong></td></tr>";

		echo "<tr class='tab_bg_2'>";
		echo "<td>".$LANGTRACKER["profile"][11].":</td><td>";
		dropdownNoneReadWrite("computers_history",$this->fields["computers_history"],1,1,1);
		echo "</td>";
		echo "</tr>";
		echo "<tr class='tab_bg_2'>";
		echo "<td>".$LANGTRACKER["profile"][12].":</td><td>";
		dropdownNoneReadWrite("printers_history",$this->fields["printers_history"],1,1,1);
		echo "</td>";
		echo "</tr>";
		echo "<tr class='tab_bg_2'>";
		echo "<td>".$LANGTRACKER["profile"][13].":</td><td>";
		dropdownNoneReadWrite("printers_info",$this->fields["printers_info"],1,1,1);
		echo "</td>";
		echo "</tr>";
		echo "<tr class='tab_bg_2'>";
		echo "<td>".$LANGTRACKER["profile"][14].":</td><td>";
		dropdownNoneReadWrite("networking_info",$this->fields["networking_info"],1,1,1);
		echo "</td>";
		echo "</tr>";
		echo "<tr class='tab_bg_2'>";
		echo "<td>".$LANGTRACKER["profile"][15].":</td><td>";
		dropdownNoneReadWrite("errors",$this->fields["errors"],1,1,1);
		echo "</td>";
		echo "</tr>";
		
		if ($canedit){
			echo "<tr class='tab_bg_1'>";
			if ($ID){
				echo "<td  align='center'>";
				echo "<input type='hidden' name='ID' value=$ID>";
				echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit'>";
				echo "</td><td  align='center'>";
				echo "<input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit'>";
			} else {
				echo "<td colspan='2' align='center'>";
				echo "<input type='submit' name='add' value=\"".$LANG["buttons"][8]."\" class='submit'>";
			}
			echo "</td></tr>";
		}
		echo "</table>";

	}
	
}
// ****************************************************************************************************** //
// ****************************************************************************************************** //
// ****************************************************************************************************** //
class plugin_tracker_model_infos extends CommonDBTM
{

	function addentry($target,$ArrayPost)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
		
		$query = "INSERT INTO glpi_plugin_tracker_model_infos
		(name, FK_model_networking, FK_firmware, FK_snmp_version, FK_snmp_connection)
		VALUES ('".$ArrayPost["name"]."', '".$ArrayPost["FK_model_networking"]."', '".$ArrayPost["FK_firmware"]."', 
		'".$ArrayPost["FK_snmp_version"]."','".$ArrayPost["FK_snmp_connection"]."')";
		
		$DB->query($query);
		
		echo "Ajout� avec succ�s<br/>";
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.models.form.php?ID=".$ID."'><b>Retour</b></a>";
		
	}
	
	

	function showForm($target,$ID,$table)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
		
		if ( !plugin_tracker_haveRight("errors","r") )
		{
			return false;
		}
		else
		{

			$query = "
			SELECT * 
			FROM ".$table."
			WHERE ID=".$ID." ";

			if ($result = $DB->query($query))
			{
				$data= $DB->fetch_array($result);
				echo "<br>";
				echo "<div align='center'><form method='post' name='' id=''  action=\"".$target."\">";
		
				echo "<table class='tab_cadre' cellpadding='5' width='600'><tr><th colspan='2'>";
				echo $LANGTRACKER["model_info"][6]." :</th></tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANG["common"][16]."</td>";
				echo "<td align='center'>";
				echo "<input name='name' value='".$data["name"]."'/>";
				echo "</td>";
				echo "</tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANG["common"][22]."</td>";
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_model_networking","model_networking",$data["FK_model_networking"],1);
				echo "</td>";
				echo "</tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANG["networking"][49]."</td>";
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_firmware","firmware",$data["FK_firmware"],1,-1,"");
				echo "</td>";
				echo "</tr>";
	
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANGTRACKER["model_info"][2]."</td>";
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_snmp_version","snmp_version",$data["FK_snmp_connection"],1);
				echo "</td>";
				echo "</tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANGTRACKER["model_info"][3]."</td>";
				echo "<td align='center'>";
				dropdownValue("glpi_plugin_tracker_snmp_connection","snmp_connection","",0);
				echo "</td>";
				echo "</tr>";
				
				echo "<tr class='tab_bg_2'><td colspan='2'>";
				echo "<input type='hidden' value='".$ID."'/>";
				if ($ID == "0"){
					echo "<div align='center'><input type='submit' name='add' value=\"".$LANG["buttons"][8]."\" class='submit' >";
				
				}else{
					echo "<div align='center'><input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
					if ($data["deleted"]=='0')
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit'>";
					else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='restore' value=\"".$LANG["buttons"][21]."\" class='submit'>";
		
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$LANG["buttons"][22]."\" class='submit'>";
					}
				}
				echo "</td>";		
				echo "</tr>";
	//			echo "<div align='center'><input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";	
				echo "</table></form></div>";
			}
		}
	}
}

class plugin_tracker_mib_networking extends CommonDBTM
{
	function showAddMIB()
	{
		GLOBAL $CFG_GLPI,$LANGTRACKER;
		echo "<br>";
		echo "<div align='center'>";
		echo "<table class='tab_cadre' cellpadding='5' width='800'><tr><td class='tab_bg_2' align='center'>";
		echo "<a href=''><b>".$LANGTRACKER["mib"][4]."</b></a></td></tr>";
		echo "</table></div>";
	
	}

	function showForm($target,$ID)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
		
		if ( !plugin_tracker_haveRight("errors","r") )
		{
			return false;
		}
		else
		{
			$query = "
			SELECT glpi_plugin_tracker_mib_networking.* FROM glpi_plugin_tracker_mib_networking
			LEFT JOIN glpi_plugin_tracker_model_infos
			ON glpi_plugin_tracker_mib_networking.FK_model_infos=glpi_plugin_tracker_model_infos.ID
			WHERE glpi_plugin_tracker_model_infos.ID=".$ID." ";

			if ($result = $DB->query($query))
			{
				echo "<br>";
				echo "<div align='center'><form method='post' name='' id=''  action=\"".$target."\">";
		
				echo "<table class='tab_cadre' cellpadding='5' width='800'><tr><th colspan='6'>";
				echo $LANGTRACKER["mib"][5]."</th></tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<th align='center'>".$LANGTRACKER["mib"][1]."</th>";
				echo "<th align='center'>".$LANGTRACKER["mib"][2]."</th>";
				echo "<th align='center'>".$LANGTRACKER["mib"][3]."</th>";
				echo "<th align='center' width='120'>".$LANGTRACKER["mib"][6]."</th>";
				echo "<th align='center' width='130'>".$LANGTRACKER["mib"][7]."</th>";
				echo "<th align='center' width='130'>".$LANGTRACKER["mib"][8]."</th>";
				echo "</tr>";
				while ($data=$DB->fetch_assoc($result))
				{
					echo "<tr class='tab_bg_1'>";
					echo "<td align='center'>";
					echo getDropdownName("glpi_dropdown_plugin_tracker_mib_label",$data["FK_mib_label"]);
					echo "</td>";
					
					echo "<td align='center'>";
					echo getDropdownName("glpi_dropdown_plugin_tracker_mib_object",$data["FK_mib_object"]);
					echo "</td>";
					
					echo "<td align='center'>";
					echo getDropdownName("glpi_dropdown_plugin_tracker_mib_oid",$data["FK_mib_oid"]);
					echo "</td>";
					
					echo "<td align='center'>";
					if ($data["oid_port_counter"] == "1")
					{
						echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
					}
					echo "</td>";
					
					echo "<td align='center'>";
					if ($data["oid_port_dyn"] == "1")
					{
						echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
					}
					echo "</td>";
					
					echo "<td align='center'>";
					echo getDropdownName("glpi_plugin_tracker_links_oid_fields",$data["FK_links_oid_fields"]);
					echo "</td>";
					
					echo "</tr>";
				}
				
				// Ajout d'une derni�re ligne pour ajouter nouveau OID
				echo "<tr><th colspan='6'>".$LANGTRACKER["mib"][4]."</th></tr>";				
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_mib_label","mib_label","",1);
				echo "</td>";
				
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_mib_object","mib_object","",1);
				echo "</td>";
				
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_mib_oid","mib_oid","",1);
				echo "</td>";
				
				echo "<td align='center'>";
				echo "<input name='port_counter_new' value='1' type='checkbox'>";
				echo "</td>";
				
				echo "<td align='center'>";
				echo "<input name='port_dyn_new' value='1' type='checkbox'>";
				echo "</td>";
				
				echo "<td align='center'>";
				dropdownValue("glpi_plugin_tracker_links_oid_fields","links_oid_fields","",0);
				echo "</td>";
				
				echo "</tr>";
				
				echo "<tr class='tab_bg_1'><td colspan='6' align='center'>";
				echo "<input type='hidden' name='add_oid' value='".$ID."'/>";
				echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' >";
				echo "</td></tr>";	
				
				echo "</table></form></div>";	
			}		
		}
	}

	function addentry($target,$ID)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
		
		$query = "INSERT INTO glpi_plugin_tracker_mib_networking
		(FK_model_infos, FK_mib_label, FK_mib_oid, FK_mib_object, oid_port_counter, oid_port_dyn, FK_links_oid_fields)
		VALUES ('".$ID."', '".$_POST["mib_label"]."', '".$_POST["mib_oid"]."', '".$_POST["mib_object"]."', 
		'".$_POST["port_counter_new"]."','".$_POST["port_dyn_new"]."','".$_POST["links_oid_fields"]."')";
		
		$DB->query($query);
		
		echo "Ajout� avec succ�s<br/>";
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.models.form.php?ID=".$ID."'><b>Retour</b></a>";
		
	}
}

?>