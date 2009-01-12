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

class plugin_tracker_snmp_auth extends CommonDBTM {
	function __construct() {
		$this->table = "glpi_plugin_tracker_snmp_connection";
		$this->type = PLUGIN_TRACKER_SNMP_AUTH;
	}

	function showForm($target, $ID = '') {
		global $DB, $CFG_GLPI, $LANG, $LANGTRACKER;

		plugin_tracker_checkRight("snmp_authentification","r");

		if ($ID!='')
			$this->getFromDB($ID);
		else
			$this->getEmpty();	

		echo "<br>";
		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table class='tab_cadre' cellpadding='5' width='600'><tr><th colspan='2'>";
		echo ($ID =='' ? $LANGTRACKER["model_info"][7] : $LANGTRACKER["model_info"][3]);
		echo " :</th></tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='" . $this->fields["name"] . "'/>";
		echo "</td>";
		echo "</tr>";
/*
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][22] . "</td>";
		echo "<td align='center'>";
		dropdownValue("glpi_dropdown_model_networking", "FK_model_networking", $this->fields["FK_model_networking"], 1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["networking"][49] . "</td>";
		echo "<td align='center'>";
		dropdownValue("glpi_dropdown_firmware", "FK_firmware", $this->fields["FK_firmware"], 1, -1, "");
		echo "</td>";
		echo "</tr>";
*/
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["model_info"][2] . "</td>";
		echo "<td align='center'>";
		dropdownValue("glpi_dropdown_plugin_tracker_snmp_version", "FK_snmp_version", $this->fields["FK_snmp_version"], 0);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["snmpauth"][1] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='community' value='" . $this->fields["community"] . "'/>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["snmpauth"][2] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='sec_name' value='" . $this->fields["sec_name"] . "'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["snmpauth"][3] . "</td>";
		echo "<td align='center'>";
		dropdownValue("glpi_dropdown_plugin_tracker_snmp_auth_sec_level", "sec_level", $this->fields["sec_level"], 0);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["snmpauth"][4] . "</td>";
		echo "<td align='center'>";
		dropdownValue("glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol", "auth_protocol", $this->fields["auth_protocol"], 0);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["snmpauth"][5] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='auth_passphrase' value='" . $this->fields["auth_passphrase"] . "'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["snmpauth"][6] . "</td>";
		echo "<td align='center'>";
		dropdownValue("glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol", "priv_protocol", $this->fields["priv_protocol"], 0);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["snmpauth"][7] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='priv_passphrase' value='" . $this->fields["priv_passphrase"] . "'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_2'><td colspan='2'>";
		if ($ID=='') {
			echo "<div align='center'><input type='submit' name='add' value=\"" . $LANG["buttons"][8] . "\" class='submit' >";

		} else {
			echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
			echo "<div align='center'><input type='submit' name='update' value=\"" . $LANG["buttons"][7] . "\" class='submit' >";
			if (!$this->fields["deleted"])
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"" . $LANG["buttons"][6] . "\" class='submit'>";
			else {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='restore' value=\"" . $LANG["buttons"][21] . "\" class='submit'>";

				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"" . $LANG["buttons"][22] . "\" class='submit'>";
			}
		}
		echo "</td>";
		echo "</tr>";
		echo "</table></form></div>";
	}
	
	
	
	function plugin_tracker_snmp_connections()
	{
		GLOBAL $CFG_GLPI, $LANG, $LANGTRACKER;

		echo "<br><form method='post' action=\"./plugin_ticketreport.form.php\">";
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='10'>".$LANGTRACKER["model_info"][3]." :</th></tr>";
		echo "<tr><th>".$LANG["common"][2]."</th>";
		echo "<th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANGTRACKER["model_info"][2]."</th>";
		echo "<th>".$LANGTRACKER["snmpauth"][1]."</th>";
		echo "<th>".$LANGTRACKER["snmpauth"][2]."</th>";
		echo "<th>".$LANGTRACKER["snmpauth"][3]."</th>";
		echo "<th>".$LANGTRACKER["snmpauth"][4]."</th>";
		echo "<th>".$LANGTRACKER["snmpauth"][5]."</th>";
		echo "<th>".$LANGTRACKER["snmpauth"][6]."</th>";
		echo "<th>".$LANGTRACKER["snmpauth"][7]."</th>";
		echo "</tr>";
	
		$xml = simplexml_load_file(GLPI_ROOT."/plugins/tracker/scripts/auth.xml");
		
		$i = -1;
		foreach($xml->auth[0] as $num){
			$i++;
			$j = 0;
			foreach($xml->auth->conf[$i] as $item){
				$j++;
				switch ($j)	
				{
					case 1:
						$numero[$i] = $item;
						break;
					case 2:
						$name[$i] = $item;
						break;
					case 3:
						$snmp_version[$i] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$item);
						break;
					case 4:
						$community[$i] = $item;
						break;
					case 5:
						$sec_name[$i] = $item;
						break;
					case 6:
						$sec_level[$i] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_sec_level",$item);
						break;
					case 7:
						$auth_protocol[$i] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol",$item);
						break;
					case 8:
						$auth_passphrase[$i] = $item;
						break;
					case 9:
						$priv_protocol[$i] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol",$item);
						break;
					case 10:
						$priv_passphrase[$i] = $item;
						break;
				}
			}
		}
		
		foreach ($numero AS $key=>$numero)
		{
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>".$numero."</td>";
			echo "<td align='center'>".$name[$key]."</td>";
			echo "<td align='center'>".$snmp_version[$key]."</td>";
			echo "<td align='center'>".$community[$key]."</td>";
			echo "<td align='center'>".$sec_name[$key]."</td>";
			echo "<td align='center'>".$sec_level[$key]."</td>";
			echo "<td align='center'>".$auth_protocol[$key]."</td>";
			echo "<td align='center'>".$auth_passphrase[$key]."</td>";
			echo "<td align='center'>".$priv_protocol[$key]."</td>";
			echo "<td align='center'>".$priv_passphrase[$key]."</td>";
			echo "</tr>";
		
		
		
		}
		echo "</table></div></form>";
	}
	

	
	function add_xml()
	{
		// Get new ID
		$xml = simplexml_load_file(GLPI_ROOT."/plugins/tracker/scripts/auth.xml");
		
		$ID = $xml->incrementID[0];
		
		// Write XML file
		$xml_write = "<snmp>\n";
		$xml_write .= "	<incrementID>".$ID++."</incrementID>\n";
		$xml_write .= "	<auth>\n";
		$i = -1;
		foreach($xml->auth[0] as $num){
			$i++;
			$xml_write .= "		<conf>\n";
			$j = 0;
			foreach($xml->auth->conf[$i] as $item){
				$j++;
				switch ($j)	
				{
					case 1:
						$xml_write .= "			<Num>".$item."</Num>\n";
						break;
					case 2:
						$xml_write .= "			<Name><![CDATA[".$item."]]></Name>\n";
						break;
					case 3:
						$xml_write .= "			<snmp_version>".$item."</snmp_version>\n";
						break;
					case 4:
						$xml_write .= "			<community><![CDATA[".$item."]]></community>\n";
						break;
					case 5:
						$xml_write .= "			<sec_name><![CDATA[".$item."]]></sec_name>\n";
						break;
					case 6:
						$xml_write .= "			<sec_level>".$item."</sec_level>\n";
						break;
					case 7:
						$xml_write .= "			<auth_protocol>".$item."</auth_protocol>\n";
						break;
					case 8:
						$xml_write .= "			<auth_passphrase><![CDATA[".$item."]]></auth_passphrase>\n";
						break;
					case 9:
						$xml_write .= "			<priv_protocol>".$item."</priv_protocol>\n";
						break;
					case 10:
						$xml_write .= "			<priv_passphrase><![CDATA[".$item."]]></priv_passphrase>\n";
						break;
				}
			}
			$xml_write .= "		</conf>\n";
		}
		// Write new Line
		$xml_write .= "		<conf>\n";
		$xml_write .= "			<Num>".$ID."</Num>\n";
		$xml_write .= "			<Name><![CDATA[".$_POST["name"]."]]></Name>\n";
		$xml_write .= "			<snmp_version>".$_POST["FK_snmp_version"]."</snmp_version>\n";
		$xml_write .= "			<community><![CDATA[".$_POST["community"]."]]></community>\n";
		$xml_write .= "			<sec_name><![CDATA[".$_POST["sec_name"]."]]></sec_name>\n";
		$xml_write .= "			<sec_level>".$_POST["sec_level"]."</sec_level>\n";
		$xml_write .= "			<auth_protocol>".$_POST["auth_protocol"]."</auth_protocol>\n";
		$xml_write .= "			<auth_passphrase><![CDATA[".$_POST["auth_passphrase"]."]]></auth_passphrase>\n";
		$xml_write .= "			<priv_protocol>".$_POST["priv_protocol"]."</priv_protocol>\n";
		$xml_write .= "			<priv_passphrase><![CDATA[".$_POST["priv_passphrase"]."]]></priv_passphrase>\n";
		$xml_write .= "		</conf>\n";
		
		$xml_write .= "	</auth>\n";
		$xml_write .= "</snmp>\n";
		
		$myFile = GLPI_ROOT."/plugins/tracker/scripts/auth.xml";
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $xml_write);
		fclose($fh);
		
		return $ID;
	}
	
	
	
	function selectbox($selected=0)
	{
		$xml = simplexml_load_file(GLPI_ROOT."/plugins/tracker/scripts/auth.xml");
		$i = -1;
		$selectbox = "<select name='auth_snmp' size='1'>\n<option value='0'>-----</option>\n";
		foreach($xml->auth[0] as $num)
		{
			$i++;

			$j = 0;
			foreach($xml->auth->conf[$i] as $item)
			{
				$j++;
				switch ($j)	
				{
					case 1:
						if ($item == $selected)
							$selectbox .= "<option selected='selected' value='".$item."'>";
						else
							$selectbox .= "<option value='".$item."'>";
						break;
					case 2:
						$selectbox .= $item."</option>\n";
							
				}
			}
		}
		$selectbox .= "</select>\n";
		
		return $selectbox;

	}



	/**
	 * Get SNMP version and authentification 
	 *
	 * @param $ID_Device ID of the device ("all" if we want to get all snmp auth)
	 * @param $xml_auth_rep folder where as stocked authxml file (if the management is by FILE)
	 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
	 *
	 * @return $snmp_auth : array with auth informations && version
	 *
	**/
	function GetInfos($ID_Device,$xml_auth_rep,$type)
	{
		global $DB,$CFG_GLPI,$LANG, $LANGTRACKER;

		$config = new plugin_tracker_config();

		if ($ID_Device != "all")
		{
			switch ($type)
			{
				case NETWORKING_TYPE :
					$query = "SELECT * FROM glpi_plugin_tracker_networking
					WHERE FK_networking='".$ID_Device."' ";
					break;
				case PRINTER_TYPE :
					$query = "SELECT * FROM glpi_plugin_tracker_printers
					WHERE FK_printers='".$ID_Device."' ";
					break;
			}		
			$result=$DB->query($query);
			if ($DB->numrows($result) > 0)
			{
				$ID_auth = $DB->result($result,0,"FK_snmp_connection");
			}
			else
			{
				return;
			}
		}
		else
		{
			// Put Default community of devices
			$snmp_auth[0]["Name"] = "Public-v2c";
			$snmp_auth[0]["snmp_version"] = "2c";
			$snmp_auth[0]["community"] = "public";
			$snmp_auth[0]["sec_name"] = "";
			$snmp_auth[0]["sec_level"] = "";
			$snmp_auth[0]["auth_protocol"] = "";
			$snmp_auth[0]["auth_passphrase"] = "";
			$snmp_auth[0]["priv_protocol"] = "";
			$snmp_auth[0]["priv_passphrase"] = "";			
			$snmp_auth[0]["ID"] = 0;
			$snmp_auth[1]["Name"] = "Public-v1";
			$snmp_auth[1]["snmp_version"] = "1";
			$snmp_auth[1]["community"] = "public";
			$snmp_auth[1]["sec_name"] = "";
			$snmp_auth[1]["sec_level"] = "";
			$snmp_auth[1]["auth_protocol"] = "";
			$snmp_auth[1]["auth_passphrase"] = "";
			$snmp_auth[1]["priv_protocol"] = "";
			$snmp_auth[1]["priv_passphrase"] = "";		
			$snmp_auth[1]["ID"] = 0;
		}

		if ($config->getValue("authsnmp") == "file")
		{		
	
			$xml = simplexml_load_file($xml_auth_rep."auth.xml");
		
			$i=-1;
			foreach($xml->auth[0] as $num){
				$i++;
				$j = 0;
				$recup = 0;
				foreach($xml->auth->conf[$i] as $item){
					$j++;
					switch ($j)	
					{
						case 1:
							if ($ID_Device == "all")
							{
								$recup = 1;
								$snmp_auth[($i+2)]["ID"] = $item;
							}
							else if ($item == $ID_auth)
							{
								$recup = 1;
							}
							break;
						case 2:
							if (($recup == "1") AND ($ID_Device != "all"))
									$snmp_auth["Name"] = $item;
							if ($ID_Device == "all")
									$snmp_auth[($i+2)]["Name"] = $item;
							break;
						case 3:
							if (($recup == "1") AND ($ID_Device != "all"))
									$snmp_auth["snmp_version"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$item);
							if ($ID_Device == "all")
									$snmp_auth[($i+2)]["snmp_version"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$item);
							break;
						case 4:
							if (($recup == "1") AND ($ID_Device != "all"))
									$snmp_auth["community"] = $item;
							if ($ID_Device == "all")
									$snmp_auth[($i+2)]["community"] = $item;
							break;
						case 5:
							if (($recup == "1") AND ($ID_Device != "all"))
									$snmp_auth["sec_name"] = $item;
							if ($ID_Device == "all")
									$snmp_auth[($i+2)]["sec_name"] = $item;
							break;
						case 6:
							if (($recup == "1") AND ($ID_Device != "all"))
									$snmp_auth["sec_level"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_sec_level",$item);
							if ($ID_Device == "all")
									$snmp_auth[($i+2)]["sec_level"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_sec_level",$item);
							break;
						case 7:
							if (($recup == "1") AND ($ID_Device != "all"))
									$snmp_auth["auth_protocol"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol",$item);
							if ($ID_Device == "all")
									$snmp_auth[($i+2)]["auth_protocol"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol",$item);
							break;
						case 8:
							if (($recup == "1") AND ($ID_Device != "all"))
									$snmp_auth["auth_passphrase"] = $item;
							if ($ID_Device == "all")
									$snmp_auth[($i+2)]["auth_passphrase"] = $item;
							break;
						case 9:
							if (($recup == "1") AND ($ID_Device != "all"))
									$snmp_auth["priv_protocol"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol",$item);
							if ($ID_Device == "all")
									$snmp_auth[($i+2)]["priv_protocol"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol",$item);
							break;
						case 10:
							if (($recup == "1") AND ($ID_Device != "all"))
									$snmp_auth["priv_passphrase"] = $item;
							if ($ID_Device == "all")
									$snmp_auth[($i+2)]["priv_passphrase"] = $item;
							break;
					}
				}
			}	
		}
		else if ($config->getValue("authsnmp") == "DB")
		{
			if ($ID_Device == "all")
			{
				$query = "SELECT * FROM glpi_plugin_tracker_snmp_connection";
			}
			else
			{
				$query = "SELECT * FROM glpi_plugin_tracker_snmp_connection
				WHERE ID='".$ID_auth."' ";
			}
			$result=$DB->query($query);
			if (($DB->numrows($result) == "0") AND ($ID_Device != "all"))
			{
				$snmp_auth["Name"] = "";
				$snmp_auth["snmp_version"] = "";
				$snmp_auth["community"] = "";
				$snmp_auth["sec_name"] = "";
				$snmp_auth["sec_level"] = "";
				$snmp_auth["auth_protocol"] = "";
				$snmp_auth["auth_passphrase"] = "";
				$snmp_auth["priv_protocol"] = "";
				$snmp_auth["priv_passphrase"] = "";
			}
			else if ($ID_Device != "all")
			{
				$snmp_auth["Name"] = $DB->result($result,0,"name");
				$snmp_auth["snmp_version"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$DB->result($result,0,"FK_snmp_version"));
				$snmp_auth["community"] = $DB->result($result,0,"community");
				$snmp_auth["sec_name"] = $DB->result($result,0,"sec_name");
				$snmp_auth["sec_level"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_sec_level",$DB->result($result,0,"sec_level"));
				$snmp_auth["auth_protocol"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol",$DB->result($result,0,"auth_protocol"));
				$snmp_auth["auth_passphrase"] = $DB->result($result,0,"auth_passphrase");
				$snmp_auth["priv_protocol"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol",$DB->result($result,0,"priv_protocol"));
				$snmp_auth["priv_passphrase"] = $DB->result($result,0,"priv_passphrase");
			}
			else if ($ID_Device == "all")
			{
				$i = 2;
				while ( $data=$DB->fetch_array($result) )
				{
					if (($snmp_auth[0]["snmp_version"] == getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$data["FK_snmp_version"]))
					AND ($snmp_auth[0]["community"] == $data["community"]))
					{
						$snmp_auth[0]["ID"] = $data["ID"];
					}
					else if (($snmp_auth[1]["snmp_version"] == getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$data["FK_snmp_version"]))
					AND ($snmp_auth[1]["community"] == $data["community"]))
					{
						$snmp_auth[1]["ID"] = $data["ID"];
					}
					else
					{
						$snmp_auth[$i]["ID"] = $data["ID"];
						$snmp_auth[$i]["Name"] = $data["name"];
						$snmp_auth[$i]["snmp_version"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$data["FK_snmp_version"]);
						$snmp_auth[$i]["community"] = $data["community"];
						$snmp_auth[$i]["sec_name"] = $data["sec_name"];
						$snmp_auth[$i]["sec_level"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_sec_level",$data["sec_level"]);
						$snmp_auth[$i]["auth_protocol"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol",$data["auth_protocol"]);
						$snmp_auth[$i]["auth_passphrase"] = $data["auth_passphrase"];
						$snmp_auth[$i]["priv_protocol"] = getDropdownName("glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol",$data["priv_protocol"]);
						$snmp_auth[$i]["priv_passphrase"] = $data["priv_passphrase"];
						$i++;
					}
				}
			}
		}
		return $snmp_auth;
	}



	function GetSNMPAuth($IDNetworking,$type)
	{
	
		global $DB;
		switch ($type)
		{
			case NETWORKING_TYPE :
				$query = "SELECT FK_snmp_connection
				FROM glpi_plugin_tracker_networking 
				WHERE FK_networking='".$IDNetworking."' ";
				break;
			case PRINTER_TYPE :
				$query = "SELECT FK_snmp_connection
				FROM glpi_plugin_tracker_printers 
				WHERE FK_printers='".$IDNetworking."' ";
				break;
		}
		
		if ( ($result = $DB->query($query)) )
		{
			if ( $DB->numrows($result) != 0 )
			{
				return $DB->result($result, 0, "FK_snmp_connection");
			}
		}	
	
	}
	
	

	
}
?>