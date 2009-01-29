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

class plugin_tracker_networking extends CommonDBTM
{
	
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
	
	
	function showForm($target,$ID) {
		
		global $DB,$CFG_GLPI,$LANG;	
		
		$history = new plugin_tracker_SNMP_history;
		
		if ( !plugin_tracker_haveRight("snmp_networking","r") )
			return false;
		if ( plugin_tracker_haveRight("snmp_networking","w") )
			$canedit = true;
		else
			$canedit = false;
		
		$this->ID = $ID;
		
		$nw=new Netwire;
		$processes = new Threads;
		$CommonItem = new CommonItem;
		$plugin_tracker_snmp = new plugin_tracker_snmp;

		echo "<script type='text/javascript' src='/glpi072/lib/extjs/adapter/prototype/prototype.js'></script>";
		echo "<script type='text/javascript' src='/glpi072/lib/extjs/adapter/prototype/effects.js'></script>";
		
		$query = "SELECT * FROM glpi_plugin_tracker_networking
		WHERE FK_networking=".$ID." ";

		$result = $DB->query($query);		
		$data = $DB->fetch_assoc($result);
		
		// Add in database if not exist
		if ($DB->numrows($result) == "0")
		{
			$query_add = "INSERT INTO glpi_plugin_tracker_networking
			(FK_networking) VALUES('".$ID."') ";
			
			$DB->query($query_add);
		}
		
		// Form networking informations
		echo "<br>";
		echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";

		echo "<table class='tab_cadre' cellpadding='5' width='800'>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th colspan='3'>";
		echo $LANG['plugin_tracker']["snmp"][11];
		echo "</th>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_tracker']["model_info"][4]."</td>";
		echo "<td align='center'>";
		$query_models = "SELECT * FROM glpi_plugin_tracker_model_infos
		WHERE device_type!=2 
			AND device_type!=0";
		$result_models=$DB->query($query_models);
		$exclude_models = array();
		while ( $data_models=$DB->fetch_array($result_models) )
		{
			$exclude_models[] = $data_models['ID'];		
		}
		dropdownValue("glpi_plugin_tracker_model_infos","model_infos",$data["FK_model_infos"],0,-1,'',$exclude_models);
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_tracker']["functionalities"][43]."</td>";
		echo "<td align='center'>";
		plugin_tracker_snmp_auth_dropdown($data["FK_snmp_connection"]);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center' colspan='2' height='30'>";
		echo $LANG['plugin_tracker']["snmp"][52].": ".convDateTime($data["last_tracker_update"]);
		echo "</td>";
		echo "</tr>";

		// Get link field to detect if cpu, memory and uptime are get onthis network device
		$Array_Object_TypeNameConstant = $plugin_tracker_snmp->GetLinkOidToFields($data["FK_model_infos"]);
		$mapping_name=array();
		foreach ($Array_Object_TypeNameConstant as $object=>$mapping_type_name)
		{
			$explode = explode("||", $mapping_type_name);
			$mapping_name[$explode[1]] = "1";			
		}

		if (((isset($mapping_name['cpu']))  AND ($mapping_name['cpu'] == "1"))
			OR (((isset($mapping_name['cpuuser']))  AND ($mapping_name['cpuuser'] == "1"))
				AND ((isset($mapping_name['cpusystem']))  AND ($mapping_name['cpusystem'] == "1"))
				)
			)
		{
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>".$LANG['plugin_tracker']["snmp"][13]."</td>";
			echo "<td align='center'>";
			plugin_tracker_Bar($data["cpu"]);
			echo "</td>";
			echo "</tr>";	
		}

		if ((isset($mapping_name['memory']))  AND ($mapping_name['memory'] == "1"))
		{
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>".$LANG['plugin_tracker']["snmp"][14]."</td>";
			echo "<td align='center'>";
			$query2 = "
			SELECT * 
			FROM glpi_networking
			WHERE ID=".$ID." ";
			$result2 = $DB->query($query2);		
			$data2 = $DB->fetch_assoc($result2);

			if (empty($data2["ram"])){
				$ram_pourcentage = 0;
			}else {
				$ram_pourcentage = ceil((100 * ($data2["ram"] - $data["memory"])) / $data2["ram"]);
			}
			plugin_tracker_Bar($ram_pourcentage," (".($data2["ram"] - $data["memory"])." Mo / ".$data2["ram"]." Mo)"); 
			echo "</td>";
			echo "</tr>";
		}

		if ((isset($mapping_name['uptime']))  AND ($mapping_name['uptime'] == "1"))
		{
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>".$LANG['plugin_tracker']["snmp"][12]."</td>";
			echo "<td align='center'>";
			$sysUpTime = $data["uptime"];
			if (ereg("days",$sysUpTime))
				sscanf($sysUpTime, "(%d) %d days, %d:%d:%d.%d",$uptime,$day,$hour,$minute,$sec,$ticks);
			else if($sysUpTime == "0")
			{
				$day = 0;
				$hour = 0;
				$minute = 0;
				$sec = 0;
			}
			else
			{
				sscanf($sysUpTime, "(%d) %d:%d:%d.%d",$uptime,$hour,$minute,$sec,$ticks);
				$day = 0;
			}
	
			echo "<b>$day</b> ".$LANG["stats"][31]." ";
			echo "<b>$hour</b> ".$LANG["job"][21]." ";
			echo "<b>$minute</b> ".$LANG["job"][22]." ";
			echo " ".strtolower($LANG["rulesengine"][42])." <b>$sec</b> ".$LANG["stats"][34]." ";      
	     
			echo "</td>";
			echo "</tr>";
		}
		
		echo "<tr class='tab_bg_1'>";
		echo "<td colspan='2'>";
		echo "<div align='center'>";
		echo "<input type='hidden' name='ID' value='".$ID."'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
		echo "</td>";
		echo "</tr>";

		echo "</table></form>";
		
		
// **************************************************************************************************** //
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
		SELECT *,glpi_plugin_tracker_networking_ports.ifmac as ifmacinternal
		
		FROM glpi_plugin_tracker_networking_ports

		LEFT JOIN glpi_networking_ports
		ON glpi_plugin_tracker_networking_ports.FK_networking_ports = glpi_networking_ports.ID 
		WHERE glpi_networking_ports.on_device='".$ID."'
		ORDER BY logical_number ";

		echo "<script  type='text/javascript'>
function close_array(id){
	document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".GLPI_ROOT."/pics/collapse.gif\' onClick=\'Effect.Fade(\"viewfollowup'+id+'\");appear_array('+id+');\' />';
} 
function appear_array(id){
	document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".GLPI_ROOT."/pics/expand.gif\' onClick=\'Effect.Appear(\"viewfollowup'+id+'\");close_array('+id+');\' />';
}		
		
		</script>";

		echo "<br>";
		echo "<div align='center'><!--<form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">-->";
		echo "<table class='tab_cadre' cellpadding='5' width='1100'>";

		echo "<tr class='tab_bg_1'>";
		$query_array = "SELECT * FROM glpi_display
		WHERE type='5157'
			AND FK_users='0'
		ORDER BY rank";
		$result_array=$DB->query($query_array);
		echo "<th colspan='".(mysql_num_rows($result_array) + 2)."'>";
		echo "Tableau des ports";
		echo "</th>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo '<th><img alt="Sélectionnez les éléments à afficher par défaut" title="Sélectionnez les éléments à afficher par défaut" src="'.GLPI_ROOT.'/pics/options_search.png" class="pointer" onclick="var w = window.open(\''.GLPI_ROOT.'/front/popup.php?popup=search_config&type=5157\' ,\'glpipopup\', \'height=400, width=1000, top=100, left=100, scrollbars=yes\' ); w.focus();"></th>';
		echo "<th>".$LANG["common"][16]."</th>";

		$query_array = "SELECT * FROM glpi_display
		WHERE type='5157'
			AND FK_users='0'
		ORDER BY rank";
		$result_array=$DB->query($query_array);
		while ( $data_array=$DB->fetch_array($result_array) )
		{
			echo "<th>";
			switch ($data_array['num']) {
				case 2 :
					echo $LANG['plugin_tracker']["snmp"][42];
					break;
				case 3 :
					echo $LANG['plugin_tracker']["snmp"][43];
					break;
				case 4 :
					echo $LANG['plugin_tracker']["snmp"][44];
					break;
				case 5 :
					echo $LANG['plugin_tracker']["snmp"][45];
					break;
				case 6 :
					echo $LANG['plugin_tracker']["snmp"][46];
					break;
				case 7 :
					echo $LANG['plugin_tracker']["snmp"][47];
					break;
				case 8 : 
					echo $LANG['plugin_tracker']["snmp"][48];
					break;
				case 9 : 
					echo $LANG['plugin_tracker']["snmp"][49];
					break;
				case 10 : 
					echo $LANG['plugin_tracker']["snmp"][51];
					break;
				case 11 : 
					echo $LANG['plugin_tracker']["mapping"][115];
					break;
				case 12 :
					echo $LANG["networking"][17];
					break;
				case 13 :
					echo $LANG['plugin_tracker']["snmp"][50];
					break;
			}
			echo "</th>";
		}			
		echo "</tr>";
		// Fin de l'entête du tableau
		
		
		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				$background_img = "";
				if (($data["trunk"] == "1") AND (ereg("up",$data["ifstatus"]) OR ereg("1",$data["ifstatus"])))
					$background_img = " style='background-image: url(\"".GLPI_ROOT."/plugins/tracker/pics/port_trunk.png\"); '";
				else if (ereg("up",$data["ifstatus"]) OR ereg("1",$data["ifstatus"]))
					$background_img = " style='background-image: url(\"".GLPI_ROOT."/plugins/tracker/pics/connected_trunk.png\"); '";

				echo "<tr class='tab_bg_1' height='40'".$background_img.">";
				echo "<td align='center' id='plusmoins".$data["ID"]."'><img src='".GLPI_ROOT."/pics/expand.gif' onClick='Effect.Appear(\"viewfollowup".$data["ID"]."\");close_array(".$data["ID"].");' /></td>";
				echo "<td align='center'><a href='networking.port.php?ID=".$data["ID"]."'>".$data["name"]."</a></td>";
				
				$query_array = "SELECT * FROM glpi_display
				WHERE type='5157'
					AND FK_users='0'
				ORDER BY rank";
				$result_array=$DB->query($query_array);
				while ( $data_array=$DB->fetch_array($result_array) )
				{
					switch ($data_array['num']) {
						case 2 :
							echo "<td align='center'>".$data["ifmtu"]."</td>";
							break;
						case 3 :
							echo "<td align='center'>".ByteSize($data["ifspeed"],1000)."bps</td>";
							break;
						case 4 :
							echo "<td align='center'>";			
							if (ereg("up",$data["ifstatus"]) OR ereg("1",$data["ifinternalstatus"]))
								echo "<img src='".GLPI_ROOT."/pics/greenbutton.png'/>";
							else if (ereg("down",$data["ifstatus"]) OR ereg("2",$data["ifinternalstatus"]))
								echo "<img src='".GLPI_ROOT."/pics/redbutton.png'/>";
							else if (ereg("testing",$data["ifstatus"]) OR ereg("3",$data["ifinternalstatus"]))
								echo "<img src='".GLPI_ROOT."/plugins/tracker/pics/yellowbutton.png'/>";
			
							echo "</td>";
							break;
						case 5 :
							echo "<td align='center'>".$data["iflastchange"]."</td>";
							break;
						case 6 :
							echo "<td align='center'>";
							if ($data["ifinoctets"] == "0")
								echo "-";
							else
								echo ByteSize($data["ifinoctets"],1000)."o";

							echo "</td>";
							break;
						case 7 :
							if ($data["ifinerrors"] == "0")
								echo "<td align='center'>-";
							else
							{		
								echo "<td align='center' class='tab_bg_1_2'>";
								echo $data["ifinerrors"];
							}
							echo "</td>";
							break;
						case 8 : 
							echo "<td align='center'>";
							if ($data["ifinoctets"] == "0")
								echo "-";
							else
								echo ByteSize($data["ifoutoctets"],1000)."o";

							echo "</td>";
							break;
						case 9 : 
							if ($data["ifouterrors"] == "0")
								echo "<td align='center'>-";
							else
							{	
								echo "<td align='center' class='tab_bg_1_2'>";
								echo $data["ifouterrors"];
							}
							echo "</td>";
							break;
						case 10 : 
							echo "<td align='center'>".$data["portduplex"]."</td>";
							break;
						case 11 : 
							// ** internal mac
							echo "<td align='center'>".$data["ifmac"]."</td>";
							break;
						case 12 :
							// ** Mac address and link to device which are connected to this port
							$opposite_port = $nw->getOppositeContact($data["FK_networking_ports"]);
							if ($opposite_port != ""){
								$query_device = "
								SELECT * 
								FROM glpi_networking_ports
								WHERE ID=".$opposite_port." ";
				
								$result_device = $DB->query($query_device);		
								$data_device = $DB->fetch_assoc($result_device);				
								
								$CommonItem->getFromDB($data_device["device_type"],$data_device["on_device"]);
								$link1 = $CommonItem->getLink(1);
								$link = str_replace($CommonItem->getName(0), $data_device["ifmac"],$CommonItem->getLink());
								echo "<td align='center'>".$link1."<br/>".$link."</td>";
							}
							else
							{
								// Search in unknown mac address table
								$PID = $processes->lastProcess(NETWORKING_TYPE);
								$unknownMac = $processes->getUnknownMacFromPIDandPort($PID,$data["FK_networking_ports"]);
								if (empty($unknownMac))
									echo "<td align='center'></td>";
								else
									echo "<td align='center' class='tab_bg_1_2'>".$unknownMac."</td>";

							}
							break;
						case 13 :
							// ** Connection status
							echo "<td align='center'>";
							if (ereg("up",$data["ifstatus"]) OR ereg("1",$data["ifstatus"]))
								echo "<img src='".GLPI_ROOT."/pics/greenbutton.png'/>";
							else if (ereg("down",$data["ifstatus"]) OR ereg("2",$data["ifstatus"]))
								echo "<img src='".GLPI_ROOT."/pics/redbutton.png'/>";
							else if (ereg("testing",$data["ifstatus"]) OR ereg("3",$data["ifstatus"]))
								echo "<img src='".GLPI_ROOT."/plugins/tracker/pics/yellowbutton.png'/>";
							else if (ereg("dormant",$data["ifstatus"]) OR ereg("5",$data["ifstatus"]))
								echo "<img src='".GLPI_ROOT."/plugins/tracker/pics/orangebutton.png'/>";
							
							echo "</td>";
							echo "</th>";
							break;
					}
				}

				echo "</tr>";
				
				
				// Historique
				
				echo "
				<tr style='display: none;' id='viewfollowup".$data["ID"]."'>
					<td colspan='".(mysql_num_rows($result_array) + 2)."'>".tracker_snmp_showHistory($data["ID"])."</td>
				</tr>
				";
			}
		}
		echo "</table>";
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

?>