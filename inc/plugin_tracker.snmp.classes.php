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
// Modification by David
// Remplacement de plugin_tracker_snmp en plugin_tracker_snmp2
abstract class plugin_tracker_snmp2 {
	
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
		$this->community = "gerlandro";
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
		
		global $DB,$CFG_GLPI,$LANG, $LANGTRACKER;	
		
		if ( !plugin_tracker_haveRight($this->tracker_right,"r") )
			return false;
		if ( (plugin_tracker_haveRight($this->tracker_right,"w")) && (haveRight($this->glpi_right,"w")) )
			$canedit = true;
		else
			$canedit = false;
		
		$this->ID = $ID;
		
		// Checking errors and get IP (if exists)
	//	if ( $this->getError() )
	//		return false;

		// ** $this->getAll();
		
		$query = "
		SELECT * 
		FROM glpi_plugin_tracker_networking
		WHERE FK_networking=".$ID." ";

		$result = $DB->query($query);		
		$data = $DB->fetch_assoc($result);
		// Form networking informations
		echo "<br>";
		echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";
		echo "<table class='tab_cadre' cellpadding='5' width='800'>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th colspan='3'>";
		echo $LANGTRACKER["snmp"][11];
		echo "</th>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANGTRACKER["model_info"][4]."</td>";
		echo "<td align='center'>";
		dropdownValue("glpi_plugin_tracker_model_infos","model_infos",$data["FK_model_infos"],0);
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANGTRACKER["snmp"][13]."</td>";
		echo "<td align='center'>";
		echo "<input  type='text' name='cpu' value='".$data["cpu"]."' size='20'>";
		echo "</td>";
		echo "</tr>";	

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANGTRACKER["snmp"][12]."</td>";
		echo "<td align='center'>";
		echo "<input  type='text' name='uptime' value='".$data["uptime"]."' size='20'>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td colspan='2'>";
		echo "<div align='center'><input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
		echo "</td>";
		echo "</tr>";

		echo "</table>";
		
		
// ************ A FAIRE ******************************************************************************* //
// ***************************************** METTRE TABLEAU DES PORTS ********************************* //
// **************************************************************************************************** //	
	function ByteSize($bytes,$sizeoct=1024){
		$size = $bytes / $sizeoct;
		if($size < $sizeoct){
			$size = number_format($size, 0);
			$size .= ' K';
		}else {
			if($size / $sizeoct < $sizeoct) {
				$size = number_format($size / $sizeoct, 0);
				$size .= ' M';
			} else if($size / $sizeoct / $sizeoct < $sizeoct) {
				$size = number_format($size / $sizeoct / $sizeoct, 0);
				$size .= ' G';
			} else if($size / $sizeoct / $sizeoct / $sizeoct < $sizeoct) {
				$size = number_format($size / $sizeoct / $sizeoct / $sizeoct, 0);
				$size .= ' T';
			}
		}
		return $size;
	}
		$query = "
		SELECT * 
		
		FROM glpi_plugin_tracker_networking_ports

		LEFT JOIN glpi_networking_ports
		ON glpi_plugin_tracker_networking_ports.FK_networking_ports = glpi_networking_ports.ID 
		WHERE glpi_networking_ports.on_device='".$ID."'
		ORDER BY logical_number ";

		echo "<br>";
		echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";
		echo "<table class='tab_cadre' cellpadding='5' width='1000'>";

		echo "<tr class='tab_bg_1'>";
		echo "<th colspan='11'>";
		echo "Tableau des ports";
		echo "</th>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th>".$LANGTRACKER["snmp"][41]."</th>";
		echo "<th>".$LANGTRACKER["snmp"][42]."</th>";
		echo "<th>".$LANGTRACKER["snmp"][43]."</th>";
		echo "<th>".$LANGTRACKER["snmp"][44]."</th>";
		echo "<th>".$LANGTRACKER["snmp"][45]."</th>";
		echo "<th>".$LANGTRACKER["snmp"][46]."</th>";
		echo "<th>".$LANGTRACKER["snmp"][47]."</th>";
		echo "<th>".$LANGTRACKER["snmp"][48]."</th>";
		echo "<th>".$LANGTRACKER["snmp"][49]."</th>";
		echo "<th>".$LANG["networking"][15]."</th>";
		echo "<th>".$LANGTRACKER["snmp"][50]."</th>";
		echo "</tr>";
		
		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
			
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'><a href='networking.port.php?ID=".$data["ID"]."'>".$data["name"]."</a></td>";
				echo "<td align='center'>".$data["ifmtu"]."</td>";
				echo "<td align='center'>".ByteSize($data["ifspeed"],1000)."bps</td>";
				echo "<td align='center'>".$data["ifinternalstatus"]."</td>";
				echo "<td align='center'>".$data["iflastchange"]."</td>";
				echo "<td align='center'>";
				if ($data["ifinoctets"] == "0")
				{
					echo "-";

				}
				else
				{
					echo ByteSize($data["ifinoctets"],1000)."o";
				}
				echo "</td>";
				
				if ($data["ifinerrors"] == "0")
				{
					echo "<td align='center'>-";
				}
				else
				{		
					echo "<td align='center' class='tab_bg_1_2'>";
					echo $data["ifinerrors"];
				}
				echo "</td>";
				echo "<td align='center'>";
				if ($data["ifinoctets"] == "0")
				{
					echo "-";
				}
				else
				{		
					echo ByteSize($data["ifoutoctets"],1000)."o";
				}
				echo "</td>";

				if ($data["ifouterrors"] == "0")
				{
					echo "<td align='center'>-";
				}
				else
				{	
					echo "<td align='center' class='tab_bg_1_2'>";
					echo $data["ifouterrors"];
				}
				echo "</td>";
				
				echo "<td align='center'><a href='networking.port.php?ID=".$data["ID"]."'>".$data["ifmac"]."</a></td>";
				
				echo "<td align='center'>";
				if (ereg("up",$data["ifstatus"]))
				{
					echo "<img src='../pics/greenbutton.png'/>";
				}
				else
				{
					echo "<img src='../pics/redbutton.png'/>";
				}
				echo "</td>";
				echo "</tr>";
					
			}
		}


		echo "</tr>";

		echo "</table>";

		
		// Query/Import SNMP
/*		echo "<br>";
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
			*/
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


class plugin_tracker_switch_snmp extends plugin_tracker_snmp2 {
	
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
		echo "<td align='center'>";
		echo "<input type='checkbox' name='cifmac' value='1'>";
		echo "</td>";
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
				echo "<br>Voici l'ÃÂ©tat ".$state;
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

// Modifications by David
// Class for tracker_fullsync.php
class plugin_tracker_snmp extends CommonDBTM
{
	function getNetworkList()
	{
		global $DB;
		
		$NetworksID = array();	
		
		$query = "SELECT active_device_state FROM glpi_plugin_tracker_config ";
		
		if ( ($result = $DB->query($query)) )
		{
			$device_state = mysql_result($result, 0, "active_device_state");
		}

		$query = "SELECT ID,ifaddr 
		FROM glpi_networking 
		WHERE deleted='0' 
			AND state='".$device_state."' ";
			
		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				$NetworksID[$data["ID"]] = $data["ifaddr"];
			}
		}

		return $NetworksID;
	
	}



	function UpdateNetworkBySNMP($ArrayListNetworking)
	{
		foreach ( $ArrayListNetworking as $IDNetworking=>$ifIP )
		{
			$updateNetwork = new plugin_tracker_snmp;
			// Get SNMP model 
			$IDModelInfos = $updateNetwork->GetSNMPModel($IDNetworking);
			if (($IDModelInfos != "") && ($IDNetworking != ""))
			{
				// ** Get oid
				$ArrayOID = $updateNetwork->GetOID($IDModelInfos);
				//**
				$ArrayPortsName = $updateNetwork->GetPortsName($ifIP);
				//**
				$ArrayPortsID = $updateNetwork->GetPortsID($IDNetworking);
				// **
				$ArrayPortsSNMPNumber = $updateNetwork->GetPortsSNMPNumber($ifIP);
				// ** Get oid ports Counter
				$ArrayOIDPorts = $updateNetwork->GetOIDPorts($IDModelInfos,$ifIP,$IDNetworking,$ArrayPortsName,$ArrayPortsSNMPNumber);
				// ** Define oid and object name
				$updateNetwork->DefineObject($ArrayOID);
				// ** Get query SNMP on switch
				$ArraySNMPResult = $updateNetwork->SNMPQuery($ArrayOID,$ifIP);
				// ** Define oid and object name
				//$updateNetwork->DefineObject($ArrayOIDPorts);
				// ** Get query SNMP of switchs ports
				$ArraySNMPResultPorts = $updateNetwork->SNMPQuery($ArrayOIDPorts,$ifIP);
				// ** Get link OID fields
				$ArrayLinks = $updateNetwork->GetLinkOidToFields($IDModelInfos);
				// ** Update fields of switchs
				$updateNetwork->UpdateGLPINetworking($ArraySNMPResult,$ArrayLinks,$IDNetworking);
				// ** Update ports fields of switchs
				$updateNetwork->UpdateGLPINetworkingPorts($ArraySNMPResultPorts,$ArrayLinks,$IDNetworking,$ArrayPortsSNMPNumber);
				
				// ** Get MAC adress of connected ports
				$updateNetwork->GetMACtoPort($ifIP,$ArrayPortsID,$IDNetworking);
			}
		} 
	
	}
	
	
	
	/**
	 * Get SNMP model of the network materiel 
	 *
	 * @param $IDNetworking ID of the network materiel
	 *
	 * @return ID of the SNMP model or nothing 
	 *
	**/
	function GetSNMPModel($IDNetworking)
	{
	
		global $DB;

		$query = "SELECT FK_model_infos
		FROM glpi_plugin_tracker_networking 
		WHERE FK_networking='".$IDNetworking."' ";
		
		if ( ($result = $DB->query($query)) )
		{
			if ( $DB->numrows($result) != 0 )
			{
				return mysql_result($result, 0, "FK_model_infos");
			}
		}	
	
	}
	
	
	
	/**
	 * Get OID list for the SNMP model (all but not the ports OID) 
	 *
	 * @param $IDModelInfos ID of the SNMP model
	 *
	 * @return array : array with object name and oid
	 *
	**/
	function GetOID($IDModelInfos)
	{
		
		global $DB;
		
		$oidList = array();		
		
		$query = "SELECT glpi_dropdown_plugin_tracker_mib_oid.name AS oidname, 
			glpi_dropdown_plugin_tracker_mib_object.name AS objectname
		FROM glpi_plugin_tracker_mib_networking
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid
			ON glpi_plugin_tracker_mib_networking.FK_mib_oid=glpi_dropdown_plugin_tracker_mib_oid.ID
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_object
			ON glpi_plugin_tracker_mib_networking.FK_mib_object=glpi_dropdown_plugin_tracker_mib_object.ID
		
		WHERE FK_model_infos=".$IDModelInfos." 
			AND oid_port_dyn='0' ";
		
		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				$oidList[$data["objectname"]] = $data["oidname"];
			}
		}
		
		return $oidList;	
	
	}



	function GetOIDPorts($IDModelInfos,$IP,$IDNetworking,$ArrayPortsName,$ArrayPortsSNMPNumber)
	{
		
		global $DB;
		
		$oidList = array();
		$object = "";
		$portcounter = "";
		
		$query = "SELECT glpi_dropdown_plugin_tracker_mib_oid.name AS oidname, 
			glpi_dropdown_plugin_tracker_mib_object.name AS objectname
		FROM glpi_plugin_tracker_mib_networking
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid
			ON glpi_plugin_tracker_mib_networking.FK_mib_oid=glpi_dropdown_plugin_tracker_mib_oid.ID
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_object
			ON glpi_plugin_tracker_mib_networking.FK_mib_object=glpi_dropdown_plugin_tracker_mib_object.ID
		
		WHERE FK_model_infos=".$IDModelInfos."
			AND oid_port_counter='1' ";
		
		if ( ($result = $DB->query($query)) )
		{
			if ( $DB->numrows($result) != 0 )
			{
				$object = mysql_result($result, 0, "objectname");
				$portcounter = mysql_result($result, 0, "oidname");
			}
		}

		// Get query SNMP to have number of ports
		$snmp_queries = new plugin_tracker_snmp;
		if (isset($portcounter))
		{

			$snmp_queries->DefineObject(array($object=>$portcounter));
		
			$Arrayportsnumber = $snmp_queries->SNMPQuery(array($object=>$portcounter),$IP);

			$portsnumber = $Arrayportsnumber[$object];

			// We have the number of Ports
	
			// Add ports in DataBase if they don't exists
	
			$np=new Netport();

			for ($i = 1; $i <= $portsnumber; $i++)
			{
			
				$query = "SELECT ID,name
			
				FROM glpi_networking_ports
				
				WHERE on_device='".$IDNetworking."'
					AND logical_number='".$i."' ";
			
				if ( $result = $DB->query($query) )
				{
					if ( $DB->numrows($result) == 0 )
					{

						$array["logical_number"] = $i;
						$array["name"] = $ArrayPortsName[$i];
						$array["iface"] = "";
						$array["ifaddr"] = "";
						$array["ifmac"] = "";
						$array["netmask"] = "";
						$array["gateway"] = "";
						$array["subnet"] = "";
						$array["netpoint"] = "";
						$array["on_device"] = $IDNetworking;
						$array["device_type"] = "2";
						$array["add"] = "Ajouter";
						
						$IDPort = $np->add($array);
						logEvent(0, "networking", 5, "inventory", "Tracker ".$LANG["log"][70]);


						//$queryInsert = "INSERT INTO glpi_networking_ports 
						//	(on_device,device_type,logical_number)
						
						//VALUES ('".$IDNetworking."','2','".$i."') ";
						
						//$DB->query($query);
						
						//$IDPort = mysql_insert_id();
						
						$queryInsert = "INSERT INTO glpi_plugin_tracker_networking_ports 
							(FK_networking_ports)
						
						VALUES ('".$IDPort."') ";
						
						$DB->query($queryInsert);
					
					}
					else
					{
					
						// Update if it's necessary
						// $np->update
						if (mysql_result($result, 0, "name") != $ArrayPortsName[$i])
						{
							
							unset($array);
							$array["name"] = $ArrayPortsName[$i];
							$array["ID"] = mysql_result($result, 0, "ID");
							$np->update($array);
						
						}

					
					
						$queryTrackerPort = "SELECT ID
					
						FROM glpi_plugin_tracker_networking_ports
						
						WHERE FK_networking_ports='".mysql_result($result, 0, "ID")."' ";
						
						if ( $resultTrackerPort = $DB->query($queryTrackerPort) ){
							if ( $DB->numrows($resultTrackerPort) == 0 ) {
							
								$queryInsert = "INSERT INTO glpi_plugin_tracker_networking_ports 
									(FK_networking_ports)
								
								VALUES ('".mysql_result($result, 0, "ID")."') ";
								
								$DB->query($queryInsert);
							
							}
						}
						
					}
				}
			
			}

		}
		// Get oid list of ports
		
		$query = "SELECT glpi_dropdown_plugin_tracker_mib_oid.name AS oidname, 
			glpi_dropdown_plugin_tracker_mib_object.name AS objectname
		FROM glpi_plugin_tracker_mib_networking
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid
			ON glpi_plugin_tracker_mib_networking.FK_mib_oid=glpi_dropdown_plugin_tracker_mib_oid.ID
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_object
			ON glpi_plugin_tracker_mib_networking.FK_mib_object=glpi_dropdown_plugin_tracker_mib_object.ID
		
		WHERE FK_model_infos=".$IDModelInfos."
			AND oid_port_dyn='1' ";
		
		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				for ($i=1;$i <= $Arrayportsnumber[$object]; $i++)
				{
					$oidList[$data["objectname"].".".$ArrayPortsSNMPNumber[$i]] = $data["oidname"].".".$ArrayPortsSNMPNumber[$i];
				}
			}
		}
		// Debug
		foreach($oidList as $object=>$oid)
		{
			echo "===========>".$object." => ".$oid."\n";
		}
		// Debug END		
		return $oidList;
		
	}	
	
	
	
	function GetPortsName($IP)
	{
		$snmp_queries = new plugin_tracker_snmp;
		
		$Arrayportsnames = $snmp_queries->SNMPQueryWalkAll(array("IF-MIB::ifName"=>"1.3.6.1.2.1.31.1.1.1.1"),$IP);
	
		$PortsName = array();
	
		foreach($Arrayportsnames as $object=>$value)
		{
		
			$PortsName[] = $value;
		
		}
	
		return $PortsName;
	
	}
	
	
	
	function GetPortsID($IDNetworking)
	{

		global $DB;	
	
		$PortsID = array();
		
		$query = "SELECT ID,name
			
		FROM glpi_networking_ports
		
		WHERE on_device='".$IDNetworking."'
		
		ORDER BY logical_number ";

		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{

				$PortsID[$data["name"]] = $data["ID"];
			
			}
		}
	
		return $PortsID;
	}



	function GetPortsSNMPNumber($IP)
	{
		$snmp_queries = new plugin_tracker_snmp;
		
		$ArrayportsSNMPNumber = $snmp_queries->SNMPQueryWalkAll(array("IF-MIB::ifIndex"=>"1.3.6.1.2.1.2.2.1.1"),$IP);
	
		$PortsName = array();
	
		foreach($ArrayportsSNMPNumber as $object=>$value)
		{
		
			$PortsSNMPNumber[] = $value;
			echo "PORT SNMP NUMBER :".$value."\n";
		
		}
	
		return $PortsSNMPNumber;

	}
	
	
	
	/**
	 * Define a global var
	 *
	 * @param $ArrayOID Array with ObjectName and OID
	 *
	 * @return nothing
	 *
	**/
	function DefineObject($ArrayOID)
	{
		foreach($ArrayOID as $object=>$oid)
		{
echo "Objet : ".$object."\n";

			if(defined($object))
			{
				runkit_constant_remove($object);
				define($object,$oid);
			}
			else
			{
				define($object,$oid);
			}
		}
	}
	
	
	
	/**
	 * Query OID by SNMP connection
	 *
	 * @param $ArrayOID List of Object and OID in an array to get values
	 * @param $IP IP of the materiel we query
	 *
	 * @return array : array with object name and result of the query
	 *
	**/
	function SNMPQuery($ArrayOID,$IP)
	{
		
		$ArraySNMP = array();
		
		foreach($ArrayOID as $object=>$oid)
		{
			$SNMPValue = snmpget($IP, "gerlandro",$oid);
			echo "****************".$SNMPValue."****************\n";
			$ArraySNMPValues = explode(": ", $SNMPValue);
			$ArraySNMP[$object] = $ArraySNMPValues[1];
		}
		
		return $ArraySNMP;
	}
	
	

	/**
	 * Query walk to get OID and values by SNMP connection where an Object have multi-lines
	 *
	 * @param $ArrayOID List of Object and OID in an array to get values
	 * @param $IP IP of the materiel we query
	 *
	 * @return array : array with OID name and result of the query
	 *
	**/	
	function SNMPQueryWalkAll($ArrayOID,$IP)
	{
		$ArraySNMP = array();
		
		foreach($ArrayOID as $object=>$oid)
		{
			$SNMPValue = snmprealwalk($IP, "gerlandro",$oid);

			foreach($SNMPValue as $oidwalk=>$value)
			{
				$ArraySNMPValues = explode(": ", $value);
				$ArraySNMP[$oidwalk] = $ArraySNMPValues[1];

			}
			
		}
		
		return $ArraySNMP;
	
	}
	
	
	
	function GetLinkOidToFields($ID)
	{

		global $DB;
		
		$ObjectLink = array();
		
		$query = "SELECT FK_links_oid_fields, 
			glpi_dropdown_plugin_tracker_mib_object.name AS name
		FROM glpi_plugin_tracker_mib_networking
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_object
			ON glpi_plugin_tracker_mib_networking.FK_mib_object=glpi_dropdown_plugin_tracker_mib_object.ID
		
		WHERE FK_model_infos=".$ID." ";
		
		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				$ObjectLink[$data["name"]] = $data["FK_links_oid_fields"];
			}
		}
	
		return $ObjectLink;
		
	}
	
	
	
	function UpdateGLPINetworking($ArraySNMPResult,$ArrayLinks,$IDNetworking)
	{
	
		global $DB;	
	
		foreach($ArraySNMPResult as $object=>$SNMPValue)
		{
		
			$query = "SELECT *
			FROM glpi_plugin_tracker_links_oid_fields
			
			WHERE ID=".$ArrayLinks[$object]." ";
		
			if ( $result=$DB->query($query) )
			{
				while ( $data=$DB->fetch_array($result) )
				{
					if ($data["dropdown"] != "")
					{
						// Search if value of SNMP Query is in dropdown, if not, we put it
						// Wawax : si tu ajoutes le lieu manuellement tu mets un msg dans le message_after_redirect
						// 
						
						// $ArrayDropdown = getDropdownArrayNames($data["table"],"%")
						$SNMPValue = externalImportDropdown($data["dropdown"],$SNMPValue,0);

					}
					
					// Update fields
					//$query_update = "UPDATE
					if ($data["table"] == "glpi_networking")
					{
					
						$Field = "ID";
						
					}
					else
					{
						
						$Field = "FK_networking";
						
					}
					
					$SNMPValue = preg_replace('/^\"/', '',$SNMPValue);
					$SNMPValue = preg_replace('/\"$/', '',$SNMPValue);

					$queryUpdate = "UPDATE ".$data["table"]."
					
					SET ".$data["field"]."='".$SNMPValue."' 
					
					WHERE ".$Field."='".$IDNetworking."'";
					
					// update via :  $networking->update(array("serial"=>"tonnumero"));
					
					$DB->query($queryUpdate);
					
					//<MoYo> cleanAllItemCache($item,$group)
					//<MoYo> $item = ID
					//<MoYo> group = GLPI_ + NETWORKING_TYPE
				
				}
			}
		
		}
	
	}
	
	
	
	function UpdateGLPINetworkingPorts($ArraySNMPResultPorts,$ArrayLinks,$IDNetworking,$ArrayPortsSNMPNumber)
	{
	
		global $DB;	
		
		$ArrayPortsList = array();
		
		$ArrayPortListTracker = array();
		
		$ArrayPortsSNMPNumber = array_flip($ArrayPortsSNMPNumber);
		
		$query = "SELECT ID, logical_number
		
		FROM glpi_networking_ports
		
		WHERE on_device='".$IDNetworking."'
		
		ORDER BY logical_number";
		
		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
			
				$ArrayPortsList[$data["logical_number"]] = $data["ID"];
				
				$queryPortsTracker = "SELECT ID
				
				FROM glpi_plugin_tracker_networking_ports
				
				WHERE FK_networking_ports='".$data["ID"]."' ";
				
				if ( $resultPortsTracker=$DB->query($queryPortsTracker) )
				{
					while ( $dataPortsTracker=$DB->fetch_assoc($resultPortsTracker) )
					{
						$ArrayPortListTracker[$data["logical_number"]] = $dataPortsTracker["ID"];
					
					}
				} 
			
			}
		}
		
		foreach($ArraySNMPResultPorts as $object=>$SNMPValue)
		{
			$ArrayObject = explode (".",$object);
			$i = count($ArrayObject);
			$i--;
			$PortNumber = $ArrayObject[$i];

			
			$object = '';
			
			for ($j = 0; $j < $i;$j++)
			{
			
				$object .= $ArrayObject[$j];

			}
			
			$query = "SELECT *
			
			FROM glpi_plugin_tracker_links_oid_fields
			
			WHERE ID=".$ArrayLinks[$object]." ";
			
			if ( $result=$DB->query($query) )
			{
				while ( $data=$DB->fetch_array($result) )
				{
					if ($data["dropdown"] != "")
					{
					
						$SNMPValue = externalImportDropdown($data["dropdown"],$SNMPValue,0);
					
					}
					else
					{
						
						if ($data["table"] == "glpi_networking_ports")
						{
						
							$Field = $ArrayPortsList[$ArrayPortsSNMPNumber[$PortNumber]];
							
						}
						else
						{

							$Field = $ArrayPortListTracker[$ArrayPortsSNMPNumber[$PortNumber]];
							
						}						
						
						$queryUpdate = "UPDATE ".$data["table"]."
					
						SET ".$data["field"]."='".$SNMPValue."' 
						
						WHERE ID='".$Field."'";
						
						$DB->query($queryUpdate);
					
					}					
				
				}
				
			}
		
		}
	
	}

	
	
	function GetMACtoPort($IP,$ArrayPortsID,$IDNetworking)
	{

		global $DB;	
		
		$ArrayMACAdressTableObject = array("dot1dTpFdbAddress" => "1.3.6.1.2.1.17.4.3.1.1");
		
		$ArrayIPMACAdressePhysObject = array("ipNetToMediaPhysAddress" => "1.3.6.1.2.1.4.22.1.2");
		
		$snmp_queries = new plugin_tracker_snmp;
		
		$snmp_queries->DefineObject($ArrayIPMACAdressePhysObject);
		
		$ArrayIPMACAdressePhys = $snmp_queries->SNMPQueryWalkAll($ArrayIPMACAdressePhysObject,$IP);
		
		$snmp_queries->DefineObject($ArrayMACAdressTableObject);
		
		$ArrayMACAdressTable = $snmp_queries->SNMPQueryWalkAll($ArrayMACAdressTableObject,$IP);
		
		$ArrayMACAdressTableVerif = array();
		
		foreach($ArrayMACAdressTable as $oid=>$value)
		{
		
			echo $oid." => ".$value."\n";
			$oidExplode = explode(".", $oid);
			
			$OIDBridgePortNumber = "1.3.6.1.2.1.17.4.3.1.2.0.".
				$oidExplode[(count($oidExplode)-5)].".".
				$oidExplode[(count($oidExplode)-4)].".".
				$oidExplode[(count($oidExplode)-3)].".".
				$oidExplode[(count($oidExplode)-2)].".".
				$oidExplode[(count($oidExplode)-1)];
				
			$ArraySNMPBridgePortNumber = array("dot1dTpFdbPort" => $OIDBridgePortNumber);
			
			$snmp_queries->DefineObject($ArraySNMPBridgePortNumber);
			
			$ArrayBridgePortNumber = $snmp_queries->SNMPQuery($ArraySNMPBridgePortNumber,$IP);
			
			foreach($ArrayBridgePortNumber as $oidBridgePort=>$BridgePortNumber)
			{
				echo "BRIDGEPortNumber ".$BridgePortNumber."\n";
				
				$ArrayBridgePortifIndexObject = array("dot1dBasePortIfIndex" => "1.3.6.1.2.1.17.1.4.1.2.".$BridgePortNumber);
		
				$snmp_queries->DefineObject($ArrayBridgePortifIndexObject);
		
				$ArrayBridgePortifIndex = $snmp_queries->SNMPQuery($ArrayBridgePortifIndexObject,$IP);
				
				foreach($ArrayBridgePortifIndex as $oidBridgePortifIndex=>$BridgePortifIndex)
				{
					echo "BridgePortifIndex : ".$BridgePortifIndex."\n";
				
					$ArrayifNameObject = array("ifName" => "1.3.6.1.2.1.31.1.1.1.1.".$BridgePortifIndex);
		
					$snmp_queries->DefineObject($ArrayifNameObject);
			
					$ArrayifName = $snmp_queries->SNMPQuery($ArrayifNameObject,$IP);
					
					foreach($ArrayifName as $oidArrayifName=>$ifName)
					{
						echo "		ifName : *".$ifName."*\n";

						// Search portID of materiel wich we would connect to this port
						$MacAddress = trim($value);
						$MacAddress = str_replace(" ", ":", $MacAddress);
						$MacAddress = strtolower($MacAddress);
						$queryPortEnd = "SELECT * 
						
						FROM glpi_networking_ports
						
						WHERE ifmac IN ('".$MacAddress."','".strtoupper($MacAddress)."')
							AND on_device!='".$IDNetworking."' ";

						if ( $resultPortEnd=$DB->query($queryPortEnd) )
						{
							if ( $DB->numrows($resultPortEnd) != 0 )
							{
echo "QUERY : ".$queryPortEnd."\n";
								$dport = mysql_result($resultPortEnd, 0, "ID");
								echo "PORT : ".$dport."\n";
								$sport = $ArrayPortsID[$ifName];
								
								$queryVerif = "SELECT *
								
								FROM glpi_networking_wire 
								
								WHERE end1 = '$sport' 
									AND end2 = '$dport' ";

								if ($resultVerif=$DB->query($queryVerif)) {

									if ( $DB->numrows($resultVerif) == 0 )
									{
									
										removeConnector($dport);
										makeConnector($sport,$dport);
									
									}
								
								}
								
								
								
							}
						}
						
					/*	
						if (isset($_POST["dport"])&&count($_POST["dport"]))
							foreach ($_POST["dport"] as $sport => $dport){
								if($sport && $dport){
									makeConnector($sport,$dport);
								}
							}
					*/
						/**
						 * Wire the Ports
						 *
						 *@param $sport : source port ID
						 *@param $dport : destination port ID
						 *@param $dohistory : add event in the history
						 *@param $addmsg : display HTML message on success
						 * 
						 *@return true on success 
						**/
						// makeConnector($sport, $dport, $dohistory=true, $addmsg=false)
						
						
						
					}
				
				}
				
			}

		}
		
	}
	
}
?>
