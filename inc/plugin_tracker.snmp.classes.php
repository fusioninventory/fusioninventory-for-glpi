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

abstract class plugin_tracker_snmp {
	
	// fields of the result of a MySQL request
	var $fields;
	
	// type of the device
	var $type;
	// MySQL table of the device type
	var $table;
	// ID of the device
	var $ID;
	// ID of the device or the switch port into the table "glpi_networking_ports"
	var $networking_ports_ID;
	// IP of the device
	var $ip;
	// community for snmpget()
	var $community;
	
	// SNMP info of the device
	var $snmp;
	
	// Right variables for glpi and Tracker
	var $glpi_right;
	var $tracker_right;

	// Init
	function plugin_tracker_snmp() {
		$this->fields = array();
		$this->type = "";
		$this->table = "";
		$this->ID = -1;
		$this->networking_ports_ID = -1;
		$this->ip = "";
		$this->community = "public";
		$this->snmp = array("name" => "", "contact" => "", "location" => "", "netmask" => "");
		$this->glpi_right = "";
		$this->tracker_right = "";
	}
	
	/* to get a string object from a snmpget result (without "STRING: " prefix)
	function snmpgetStringObject($object_id) {
		$result[0] = @snmpget($this->ip, $this->community, $object_id);
		if ($result[0] != false ) {
			$result[1] = eregi_replace("STRING: ","",$result[0]);
			// if "" into the string
			$result[2] = str_replace('"', '', $result[1]);
			return $result[2];
		}
		else
			return "";
	}
	
	/* to get an IP object from a snmpget result (without "IpAddress: " prefix)
	function snmpgetIpObject($object_id) {
		$result[0] = @snmpget($this->ip, $this->community, $object_id);
		if ($result[0] != false ) {
			$result[1] = eregi_replace("IpAddress: ","",$result[0]);
			return $result[1];
		}
		else
			return "";
	}
	
	/* to get a counter object from a snmpget result (without "Counter32: " prefix)
	function snmpgetCounterObject($object_id) {
		$result[0] = @snmpget($this->ip, $this->community, $object_id);
		if ($result[0] != false ) {
			$result[1] = intval( eregi_replace("Counter32: ","",$result[0]) );
			return $result[1];
		}
		else
			return "";
	}
	
	/* to get an integer object from a snmpget result (without "INTEGER: " prefix)
	function snmpgetIntegerObject($object_id) {
		$result[0] = @snmpget($this->ip, $this->community, $object_id);
		if ($result[0] != false ) {
			$result[1] = intval( eregi_replace("INTEGER: ","",$result[0]) );
			return $result[1];
		}
		else
			return "";
	}

	/* to get an hexadecimal string object from a snmpget result (without "Hex-STRING: " prefix)
	function snmpgetHexStringObject($object_id) {
		$result[0] = @snmpget($this->ip, $this->community, $object_id);
		if ($result[0] != false ) {
			$result[1] = eregi_replace("Hex-STRING: ","",$result[0]);
			// if "" into the string
			$result[2] = str_replace('"', '', $result[1]);
			return $result[2];
		}
		else
			return "";
	}*/
	
	// to check if the device is working
	function isActive() {
		global $DB;
		
		$config = new plugin_tracker_config();
		
		// state number for an active device
		if ( !($active_device_state = $config->getValue("active_device_state")) )
			return false;
			
		// compare device status and active device status
		$query = "SELECT state ".
				 "FROM $this->table ".
				 "WHERE ID='".$this->ID."';";
		if ( $result = $DB->query($query) ) {
			if ( $fields = $DB->fetch_row($result) ) {
				if ( ($fields['0']) == $active_device_state )
					return true;
			}
		}
		return false;
	}
	
	/* to check if we can get info from SNMP (for instance : device connected)...  Returns false if possible */
	abstract function cantGetInfo();
	
	/* Writes messages errors from canGetInfo() and returns false if no error */
	abstract function getError();
	
	abstract function getIPfromID();
	
	/* to get an object from a snmpget result, without prefix like "STRING: " for instance */
	function snmpgetObject($object_id) {
		$result[0] = @snmpget($this->ip, $this->community, $object_id);
		if ($result[0] != false ) {
			$pos = strpos($result[0], " ");
			$result[1] = substr($result[0], $pos+1);
			// if "" into the string
			$result[2] = str_replace('"', '', $result[1]);
			return $result[2];
		}
		else
			return false;
	}
	
	function getName() {
		if ( !($this->snmp['name'] = $this->snmpgetObject(MIB_NAME)) ) {
			$this->snmp['name'] = "";
			return false;
		}
		return $this->snmp['name'];
	}
	
	function getContact() {
		if ( !($this->snmp['contact'] = $this->snmpgetObject(MIB_CONTACT)) ) {
			$this->snmp['contact'] = "";
			return false;
		}
		return $this->snmp['contact'];
	}
	
	function getLocation() {
		if ( !($this->snmp['location'] = $this->snmpgetObject(MIB_LOCATION)) ) {
			$this->snmp['location'] = "";
			return false;
		}
		return $this->snmp['location'];
	}
	
	function getNetmask() {
		$mib_netmask = MIB_NETMASK_PREFIX.".".$this->ip;
		if ( !($this->snmp['netmask'] = $this->snmpgetObject($mib_netmask)) ) {
			$this->snmp['netmask'] = "";
			return false;
		}
		return $this->snmp['netmask'];
	}
	
	/* Groups all of the information */
	abstract function getAll();
	
	/* Contents for info */
	abstract function showFormContents();
	
	// To update checked info
	abstract function update($input);
	
	function showForm($target,$ID) {
		
		global $LANG, $LANGTRACKER;	
		
		if ( !plugin_tracker_haveRight($this->tracker_right,"r") )
			return false;
		if ( (plugin_tracker_haveRight($this->tracker_right,"w")) && (haveRight($this->glpi_right,"w")) )
			$canedit = true;
		else
			$canedit = false;
		
		$this->ID = $ID;
		
		// Checking errors and get IP (if exists)
		if ( $this->getError() )
			return false;

		$this->getAll();
		
		echo "<br>";
		echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";
		echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='3'>";
		echo $LANGTRACKER["snmp"][0]." :</th></tr>";
		
		$this->showFormContents();

		if ( $canedit ) {
			
			echo "<tr class='tab_bg_1'><td colspan='3'>";
			echo "<div align='center'><a onclick= \"if ( markAllRows('snmp_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG["buttons"][18]."</a>";
			echo " - <a onclick= \"if ( unMarkAllRows('snmp_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG["buttons"][19]."</a> ";
			echo "<input type='hidden' name='ID' value='".$ID."'>";
			echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
			echo "</table></div>";
		}
			
	}
	
	/* Useful to get the ID of a device into the table "glpi_networking_ports */
	function getNetworkingPortsIDfromID() {
		global $DB;
		$query = "SELECT ID FROM glpi_networking_ports ".
				 "WHERE on_device='".$this->ID."' ".
				 "AND device_type='".$this->type."';";
		if ( $result=$DB->query($query) ) {
			$this->fields = $DB->fetch_row($result);
			// check if IP is in db
			if ( ($this->fields['0']) != NULL ) {
				$this->networking_ports_ID = $this->fields['0'];
				return $this->networking_ports_ID;
			}
			else
				return false;
		}
	}
	
	function getIDfromNetworkingPortsID() {
		global $DB;
		$query = "SELECT on_device FROM glpi_networking_ports ".
				 "WHERE ID='".$this->networking_ports_ID."' ".
				 "AND device_type='".$this->type."';";
		if ( $result=$DB->query($query) ) {
			$this->fields = $DB->fetch_row($result);
			// check if IP is in db
			if ( ($this->fields['0']) != NULL ) {
				$this->ID = $this->fields['0'];
				return $this->ID;
			}
			else
				return false;
		}
	}
}

class plugin_tracker_printer_snmp extends plugin_tracker_snmp {

	//var $cablage = array("switch", "etat", "port");
	
	function plugin_tracker_printer_snmp() {
		$this->plugin_tracker_snmp();
		$this->type = PRINTER_TYPE;
		$this->table ="glpi_printers";
		$this->snmp = array_merge( $this->snmp, array("model" => "", "serial" => "", "counter" => "", "ifmac" => "") );
		$this->glpi_right = "printer";
		$this->tracker_right = "printers_info";
	}
	
	function cantGetInfo() {
		if ($this->isActive()) {
			if ($this->getIPfromID())
				return false;
			else
				return 33;
		}
		else
			return 32;
	}
	
	function getError() {
		global $LANGTRACKER;
		
		if ( !($error = $this->cantGetInfo()) )
			return false; // no error
		else {
			switch($error) {
				case 32:
					echo "".$LANGTRACKER["snmp"][32]."";
					break;
				case 33:
					echo "".$LANGTRACKER["snmp"][33]."";
					break;	
			}
		return true;
		}
	}
	
	function getIPfromID() {
		global $DB;
		$query = "SELECT ifaddr FROM glpi_networking_ports ".
				 "WHERE on_device='".$this->ID."' ".
				 "AND device_type='".$this->type."';";
		if ( $result=$DB->query($query) ) {
			$this->fields = $DB->fetch_row($result);
			// check if IP is in db
			if ( ($this->fields['0']) != NULL ) {
				$this->ip = $this->fields['0'];
				return $this->ip;
			}
			else
				return false;
		}
	}
	
	function getModel() {
		if ( !($this->snmp['model'] = $this->snmpgetObject(MIB_PRINTER_MODEL)) ) {
			$this->snmp['model'] = "";
			return false;
		}
		return $this->snmp['model'];
	}
	
	function getSerial() {
		if ( !($this->snmp['serial'] = $this->snmpgetObject(MIB_PRINTER_SERIAL)) ) {
			$this->snmp['serial'] = "";
			return false;
		}
		return $this->snmp['serial'];
	}
	
	function getCounter() {
		if ( !($this->snmp['counter'] = $this->snmpgetObject(MIB_PRINTER_COUNTER)) ) {
			$this->snmp['counter'] = "";
			return false;
		}
		return $this->snmp['counter'];
	}
	
	function getIfmac() {
		$ifmac1 = $this->snmpgetObject(MIB_PRINTER_IFMAC_1);
		$ifmac2 = $this->snmpgetObject(MIB_PRINTER_IFMAC_2);
		if ( !$ifmac1 && !$ifmac2 ) {		
			$this->snmp['ifmac'] = "";
			return false;
		}

		if ( ($ifmac = plugin_tracker_stringToIfmac($ifmac1)) || ($ifmac = plugin_tracker_stringToIfmac($ifmac2)) )
			$this->snmp['ifmac'] = $ifmac;
		else
			$this->snmp['ifmac'] = "";
		return $this->snmp['ifmac'];
	}
		
	function getAll() {
		
		$error = new plugin_tracker_errors();
		
		if ( !($this->getName()) ) {
			$date = date("Y-m-d H:i:s");
			$input['ifaddr'] = $this->ip;
			$input['device_id'] = $this->ID;
			$error->writeError($this->type, 'snmp', $input, $date);
			return false;
		}
		$this->getContact();
		$this->getLocation();
		$this->getNetmask();
		$this->getModel();
		$this->getSerial();
		$this->getCounter();
		$this->getIfmac();
	}
	
	/* Will get all snmp info of printers whose functionnality is set to 1 */
	function cron($date) {
		
		$config = new glpi_plugin_tracker_printers_history_config();
		$history = new plugin_tracker_printers_history();
		$error = new plugin_tracker_errors();
		
		if ( !($printers = $config->getAllActivated()) )
			return false;
			
		for ($i=0; $i<$printers['number']; $i++) {
			$this->ID = $printers["$i"]['FK_printers'];
			if ( !($this->cantGetInfo()) ) {
				// if can't get counter => write error in DB
				if ( !($this->getCounter()) ) {
					$date = date("Y-m-d H:i:s");
					$input['ifaddr'] = $this->ip;
					$input['device_id'] = $this->ID;
					$error->writeError($this->type, 'snmp', $input, $date);
				}
				else {
					$this->updateCounter();
					
					// Set history
					$input['FK_printers'] = $this->ID;
					$input['date'] = $date;
					$input['pages'] = $this->snmp['counter'];
					$history->add($input);
				}
			}
		}
		return false;
	}
	
	function showFormContents() {
		
		global $LANG, $LANGTRACKER;
		
		echo "<tr class='tab_bg_1'><th colspan='3'>";
		echo $LANGTRACKER["snmp"][1]." :</th></tr>";
			
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "<input type='checkbox' name='cname' value='1'>";
		echo "</td>";
		echo "<td>".$LANG["common"][16]."</td>";
		echo "<td><input  type='text' name='name' value='".$this->snmp['name']."' size='20'></td></tr>";
		
		echo "<tr class='tab_bg_1'>";
 		echo "<td>x</td>";
//		echo "<td align='center'>";
//		echo "<input type='checkbox' name='cmodel' value='1'>";
//		echo "</td>";
		echo "<td>".$LANG["common"][22]."</td>";
		echo "<td><input  type='text' name='model' value='".$this->snmp['model']."' size='20'>";
		echo " <img src='/glpi/pics/aide.png' alt=\"\" onmouseout=\"setdisplay(getElementById('model'),'none')\" onmouseover=\"setdisplay(getElementById('model'),'block')\"><span class='over_link' id='model'>".$this->snmp['model']."</span>";
		echo "</td></tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "<input type='checkbox' name='cserial' value='1'>";
		echo "</td>";
		echo "<td>".$LANG["common"][19]."</td>";
		echo "<td><input  type='text' name='serial' value='".$this->snmp['serial']."' size='20'></td></tr>";
				
		echo "<tr class='tab_bg_1'>";
		echo "<td>x</td>";
//		echo "<td align='center'>";
//		echo "<input type='checkbox' name='cifmac' value='1'>";
//		echo "</td>";
		echo "<td>".$LANG["device_iface"][2]."</td>";
		echo "<td><input  type='text' name='ifmac' value='".$this->snmp['ifmac']."' size='20'></td></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>x</td>";
//		echo "<td align='center'>";
//		echo "<input type='checkbox' name='cnetmask' value='1'>";
//		echo "</td>";
		echo "<td>".$LANG["networking"][60]."</td>";
		echo "<td><input  type='text' name='netmask' value='".$this->snmp['netmask']."' size='20'></td></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>x</td>";
//		echo "<td align='center'>";
//		echo "<input type='checkbox' name='clocation' value='1'>";
//		echo "</td>";
		echo "<td>".$LANG["common"][15]."</td>";
		echo "<td><input  type='text' name='location' value='".$this->snmp['location']."' size='20'></td></tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "<input type='checkbox' name='ccontact' value='1'>";
		echo "</td>";
		echo "<td>".$LANG["common"][18]."</td>";
		echo "<td><input  type='text' name='contact' value='".$this->snmp['contact']."' size='20'></td></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "<input type='checkbox' name='cinitial_pages' value='1'>";
		echo "</td>";
		echo "<td>".$LANG["printers"][30]."</td>";
		echo "<td><input  type='text' name='initial_pages' value='".$this->snmp['counter']."' size='20'></td></tr>";
		
		echo "<tr class='tab_bg_1'><th colspan='3'>";
		echo $LANGTRACKER["snmp"][2]." :</th></tr>";
		
	}
	
	/* useful for counters cron, works for one printer only */
	function updateCounter() {
		$print = new Printer();
		if ( $this->snmp['counter'] != "" ) {
			$input['ID'] = $this->ID;
			$input['initial_pages'] = $this->snmp['counter'];
			$print->update($input);
		}
	}
	
	function update($input) {
				
		// update in table : glpi_printers
		$print = new Printer();

		if ( !isset($input['ID']) )
			return false;
		$general['ID']=$input['ID'];
		if ( isset($input['name']) )
			$general['name']=$input['name'];
		if ( isset($input['serial']) )
			$general['serial']=$input['serial'];
		if ( isset($input['contact']) )
			$general['contact']=$input['contact'];
		if ( isset($input['initial_pages']) )
			$general['initial_pages']=$input['initial_pages'];
		
		$print->update($general);
		
		// update in table : glpi_networking_ports
		
	}
}


class plugin_tracker_switch_snmp extends plugin_tracker_snmp {
	
	// Number of switch ports
	var $num_ports = 0;

	function plugin_tracker_switch_snmp() {
		$this->plugin_tracker_snmp();
		$this->type = NETWORKING_TYPE;
		$this->table ="glpi_networking";
		$this->snmp = array_merge( $this->snmp, array("model" => "", "serial" => "", "firmware" => "", "ifmac" => "", "ciscoRam" => "") );
		$this->glpi_right = "networking";
		$this->tracker_right = "networking_info";	
		$this->num_ports = 0;
	}

	
	// to check if the networking device is a switch (not a hub, router,...)
	function isSwitch() {
		global $DB;
		
		$config = new plugin_tracker_config();
		
		// type number for a switch
		if ( !($networking_switch_type = $config->getValue("networking_switch_type")) )
			return false;
			
		// compare device type number and switch type number
		$query = "SELECT type ".
				 "FROM $this->table ".
				 "WHERE ID='".$this->ID."';";
		if ( $result = $DB->query($query) ) {
			if ( $fields = $DB->fetch_row($result) ) {
				if ( ($fields['0']) == $networking_switch_type )
					return true;
			}
		}
		return false;
	}
	
	function cantGetInfo() {
		if ( ($this->isSwitch()) ) {
			if ($this->isActive()) {
				if ($this->getIPfromID())
					return false;
				else
					return 33;
			}
			else
				return 32;
		}
		else
			return 31;
	}
	
	function getError() {
		global $LANGTRACKER;
		
		if ( !($error = $this->cantGetInfo()) )
			return false; // no error
		else {
			switch($error) {
				case 31:
					echo "".$LANGTRACKER["snmp"][31]."";
					break;
				case 32:
					echo "".$LANGTRACKER["snmp"][32]."";
					break;
				case 33:
					echo "".$LANGTRACKER["snmp"][33]."";
					break;	
			}
		return true;
		}
	}
	
	// to check if the device is from Cisco (useful for RAM displaying)
	function isCisco() {
		global $DB;
		$query = "SELECT glpi_dropdown_manufacturer.name ".
				 "FROM glpi_dropdown_manufacturer ".
				 "LEFT JOIN glpi_networking ".
				 "ON glpi_dropdown_manufacturer.ID = glpi_networking.FK_glpi_enterprise ".
				 "WHERE glpi_networking.ID='".$this->ID."';";
		if ( $result=$DB->query($query) ) {
			$fields = $DB->fetch_row($result);
			if ( ($fields['0']) != NULL ) {
				$manufacturer = strtolower($fields['0']); // lowercase
				// to check if the string contains "cisco"
				if ( strstr($manufacturer, 'cisco') )
					return true;
			}
			return false;
		}
	}
	
	function getIPfromID() {
		global $DB;
		$query = "SELECT ifaddr FROM glpi_networking WHERE ID='".$this->ID."';";
		if ( $result=$DB->query($query) ) {
			$this->fields = $DB->fetch_row($result);
			// check if IP is in db
			if ( ($this->fields['0']) != NULL ) {
				$this->ip = $this->fields['0'];
				return $this->ip;
			}
			else
				return false;
		}
	}
	
	function getNumberOfPorts() {
		global $DB;
		$query = "";
		if ( $result=$DB->query($query) ) {
			
		}
	}
	
	function getModel() {
		if ( !($this->snmp['model'] = $this->snmpgetObject(MIB_SWITCH_MODEL)) ) {
			$this->snmp['model'] = "";
			return false;
		}
		return $this->snmp['model'];
	}
	
	function getSerial() {
		if ( !($this->snmp['serial'] = $this->snmpgetObject(MIB_SWITCH_SERIAL)) ) {
			$this->snmp['serial'] = "";
			return false;
		}
		return $this->snmp['serial'];
	}
	
	function getFirmware() {
		if ( !($this->snmp['firmware'] = $this->snmpgetObject(MIB_SWITCH_FIRMWARE)) ) {
			$this->snmp['firmware'] = "";
			return false;
		}
		return $this->snmp['firmware'];
	}
	
	function getIfmac() {
		if ( !($ifmac = $this->snmpgetObject(MIB_SWITCH_IFMAC)) ) {
			$this->snmp['ifmac'] = "";
			return false;
		}
		$ifmac = plugin_tracker_stringToIfmac($ifmac);
		if ( $ifmac )
			$this->snmp['ifmac'] = $ifmac;
		else
			$this->snmp['ifmac'] = "";
		return $this->snmp['ifmac'];
	}
	
	function getCiscoRam() {
		if ( !($bytes = $this->snmpgetObject(MIB_CISCO_SWITCH_RAM)) ) {
			$this->snmp['ciscoRam'] = "";
			return false;
		}
		// convert bytes to kbytes
		$this->snmp['ciscoRam'] = number_format($bytes/1048576, 0, '.', '');
		return $this->snmp['ciscoRam'];
	}
	
	function getPortDescr() {
		return $portDescr;
	}
	
	function getAll() {
		
		$error = new plugin_tracker_errors();
		
		if ( !($this->getName()) ) {
			$date = date("Y-m-d H:i:s");
			$input['ifaddr'] = $this->ip;
			$input['device_id'] = $this->ID;
			$error->writeError($this->type, 'snmp', $input, $date);
			return false;
		}
		$this->getContact();
		$this->getLocation();
		$this->getModel();
		$this->getSerial();
		$this->getFirmware();
		$this->getIfmac();
		$this->getCiscoRam();
	}
	
	function showFormContents() {
		
		global $LANG, $LANGTRACKER;
			
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "<input type='checkbox' name='cname' value='1'>";
		echo "</td>";
		echo "<td>".$LANG["common"][16]."</td>";
		echo "<td><input  type='text' name='name' value='".$this->snmp['name']."' size='20'></td></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td>x</td>";
//		echo "<td align='center'>";
//		echo "<input type='checkbox' name='cmodel' value='1'>";
//		echo "</td>";
		echo "<td>".$LANG["common"][22]."</td>";
		echo "<td><input  type='text' name='model' value='".$this->snmp['model']."' size='20'>";
		echo " <img src='/glpi/pics/aide.png' alt=\"\" onmouseout=\"setdisplay(getElementById('model'),'none')\" onmouseover=\"setdisplay(getElementById('model'),'block')\"><span class='over_link' id='model'>".$this->snmp['model']."</span>";
		echo "</td></tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "<input type='checkbox' name='cserial' value='1'>";
		echo "</td>";
		echo "<td>".$LANG["common"][19]."</td>";
		echo "<td><input  type='text' name='serial' value='".$this->snmp['serial']."' size='20'>";
		
		echo "<tr class='tab_bg_1'>";
 		echo "<td>x</td>";
//		echo "<td align='center'>";
//		echo "<input type='checkbox' name='cfirmware' value='1'>";
//		echo "</td>";
		echo "<td>".$LANG["networking"][49]."</td>";
		echo "<td><input  type='text' name='firmware' value='".$this->snmp['firmware']."' size='20'>";
		echo " <img src='/glpi/pics/aide.png' alt=\"\" onmouseout=\"setdisplay(getElementById('firmware'),'none')\" onmouseover=\"setdisplay(getElementById('firmware'),'block')\"><span class='over_link' id='firmware'>".$this->snmp['firmware']."</span>";
		echo "</td></tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>x</td>";
//		echo "<td align='center'>";
//		echo "<input type='checkbox' name='cifmac' value='1'>";
//		echo "</td>";
		echo "<td>".$LANG["device_iface"][2]."</td>";
		echo "<td><input  type='text' name='ifmac' value='".$this->snmp['ifmac']."' size='20'></td></tr>";
		
		echo "<tr class='tab_bg_1'>";
 		echo "<td>x</td>";
//		echo "<td align='center'>";
//		echo "<input type='checkbox' name='clocation' value='1'>";
//		echo "</td>";
		echo "<td>".$LANG["common"][15]."</td>";
		echo "<td><input  type='text' name='location' value='".$this->snmp['location']."' size='20'></td></tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "<input type='checkbox' name='ccontact' value='1'>";
		echo "</td>";
		echo "<td>".$LANG["common"][18]."</td>";
		echo "<td><input  type='text' name='contact' value='".$this->snmp['contact']."' size='20'></td></tr>";
		
		// to display RAM quantity of a Cisco Switch
		if ( $this->isCisco() ) {
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "<input type='checkbox' name='cram' value='1'>";
		echo "</td>";
		echo "<td>".$LANG["networking"][5]."</td>";
		echo "<td><input  type='text' name='ram' value='".$this->snmp['ciscoRam']."' size='20'></td></tr>";
		}
		
	}
	
	function update($input) {
		
		// update in table : glpi_networking
		$netdevice=new Netdevice();

		if ( !isset($input['ID']) )
			return false;
		$general['ID']=$input['ID'];
		if ( isset($input['name']) )
			$general['name']=$input['name'];
		if ( isset($input['serial']) )
			$general['serial']=$input['serial'];
		if ( isset($input['ifmac']) )
			$general['ifmac']=$input['ifmac'];
		if ( isset($input['netmask']) )
			$general['netmask']=$input['netmask'];
		if ( isset($input['contact']) )
			$general['contact']=$input['contact'];
		if ( isset($input['ram']) )
			$general['ram']=$input['ram'];
			
		$netdevice->update($general);
			
	}
}

/* Class for the cablage of a connected device */
/*class plugin_tracker_netport_snmp extends plugin_tracker_snmp {
	
	// networking_ports ID of a connected device
	var $connected_ID = -1;
	// MAC address of a connected device
	var $connected_ifmac = "";
	
	function plugin_tracker_netport_snmp() {
		$this->connected_ID = -1;
		$this->connected_ifmac = "";
		$this->glpi_right = "networking";
		$this->tracker_right = "networking_info";
	}

	// to get the ID of the switch where a device is connected to
	function getSwitchIDfromConnectedDevice() {
		global $DB;
		$query = "SELECT end2 FROM glpi_networking_wire WHERE end1='".$this->connected_ID."';";
		if ( $result=$DB->query($query) ) {
			$this->fields = $DB->fetch_row($result);
			// check if IP is in db
			if ( ($this->fields['0']) != NULL ) {
				$this->networking_ports_ID = $this->fields['0']; // networking_ports_ID of the switch port
				$this->getIDfromNetworkingPortsID();
				return $this->ID;
			}
			else
				return false;
		}
	}
	
	// gets the IP of the switch from the Switch ID
	function getIPfromID() {
		$switch = new plugin_tracker_switch_snmp();
		return $switch->getIPfromID();
	}
	
	// to get the number of the port where a device is connected to
	function getPortLogicalNumber() {
		$dec_ifmac = plugin_tracker_ifmacToDecimal($this->connected_ifmac);
		$mib_port = MIB_SWITCH_PORT_PREFIX.".".$dec_ifmac;
		$this->snmp['port'] = $this->snmpgetObject($mib_port);
		return $this->snmp['port'];
	}
	
	// Gets the state of this port
	function getPortState() {
		$dec_ifmac = plugin_tracker_ifmacToDecimal($this->connected_ifmac);
		$mib_state = MIB_SWITCH_STATE_PREFIX.".".$dec_ifmac;
		$state = $this->snmpgetObject($mib_state);
		return $state;
	}
	
	function showFormContents($connected_ID, $connected_ifmac) {
		global $LANGTRACKER;
		$this->connected_ID = $connected_ID;
		$this->connected_ifmac = $connected_ifmac;
		if  ( $this->getSwitchIDfromConnectedDevice() ) {
			if ( $this->getIPfromID() ) {
				echo "<br>voici ip switch : ".$this->ip;
				$state = $this->getPortState();
				$port = $this->getPortgetPortLogicalNumber();
				echo "<br>Voici l'état ".$state;
				echo "<br>Voici le port ".$port;
			}
			else
				echo "".$LANGTRACKER["snmp"][33]."";
		}
		else
			echo "".$LANGTRACKER["snmp"][34]."";
	}
	
	function update($input) {
		
	}
}*/

?>
