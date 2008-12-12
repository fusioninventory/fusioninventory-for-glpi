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

function plugin_tracker_discovery_startmenu($target)
{
	global $LANG, $LANGTRACKER;	

	echo "<br>";
	echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";

	echo "<table class='tab_cadre' cellpadding='5' width='800'>";
	
	echo "<tr class='tab_bg_1'>";
	echo "<th colspan='3'>";
	echo $LANGTRACKER["menu"][0];
	echo "</th>";
	echo "</tr>";

	echo "<tr class='tab_bg_1'>";
	echo "<td align='center' rowspan='2'>".$LANGTRACKER["discovery"][0]."</td>";
	echo "<td align='center'>";
	dropdownInteger("ip11", "", 0, 254);
	echo " . ";
	dropdownInteger("ip12", "", 0, 254);
	echo " . ";
	dropdownInteger("ip13", "", 0, 254);
	echo " . ";
	dropdownInteger("ip14", "", 0, 254);
	echo "</td>";
	echo "</tr>";

	echo "<tr class='tab_bg_1'>";
	echo "<td align='center'>";
	dropdownInteger("ip21", "", 0, 254);
	echo " . ";
	dropdownInteger("ip22", "", 0, 254);
	echo " . ";
	dropdownInteger("ip23", "", 0, 254);
	echo " . ";
	dropdownInteger("ip24", "", 0, 254);
	echo "</td>";
	echo "</tr>";	

	echo "<tr class='tab_bg_1'>";
	echo "<td colspan='2'>";
	echo "<div align='center'>";
	echo "<input type='submit' name='discover' value=\"".$LANGTRACKER["buttons"][0]."\" class='submit' >";
	echo "</td>";
	echo "</tr>";

	echo "</table></form>";

}

function plugin_tracker_discovery_scan($Array_IP)
{

	global $DB,$TRACKER_MAPPING;

	$plugin_tracker_snmp = new plugin_tracker_snmp;
	$plugin_tracker_snmp_auth = new plugin_tracker_snmp_auth;

	// Clear DB table
	$query = "TRUNCATE TABLE `glpi_plugin_tracker_discover`";
	$DB->query($query);

	// Load snmp auth
	$snmp_auth = $plugin_tracker_snmp_auth->GetInfos("all","",0);
var_dump($snmp_auth);
	$i = 0;
	$ip1 = $Array_IP["ip11"];
	$ip2 = $Array_IP["ip12"];
	$ip3 = $Array_IP["ip13"];
	$ip4 = $Array_IP["ip14"];
	while ($i != 1)
	{
		// Test if port 161 is open
		foreach ($snmp_auth as $num=>$field)
		{
			echo "IP :".$ip1.".".$ip2.".".$ip3.".".$ip4."\n";
			$Array_sysdescr = $plugin_tracker_snmp->SNMPQuery(array("sysDescr"=>".1.3.6.1.2.1.1.1.0"),$ip1.".".$ip2.".".$ip3.".".$ip4,$snmp_auth[$num]['snmp_version'],$snmp_auth[$num]);
			if ($Array_sysdescr["sysDescr"] != ""){
				$Array_Name = $plugin_tracker_snmp->SNMPQuery(array("sysName"=>".1.3.6.1.2.1.1.5.0"),$ip1.".".$ip2.".".$ip3.".".$ip4,$snmp_auth[$num]['snmp_version'],$snmp_auth[$num]);
				//Port is open, test with oids to determine the device type
				$device_type = 0;
				foreach ($TRACKER_MAPPING['discovery'] as $num_const=>$value_const)
				{
					$plugin_tracker_snmp->DefineObject(array($TRACKER_MAPPING['discovery'][$num_const]['object']=>$TRACKER_MAPPING['discovery'][$num_const]['oid']),$ip1.".".$ip2.".".$ip3.".".$ip4);
					$Array_type = $plugin_tracker_snmp->SNMPQuery(array($TRACKER_MAPPING['discovery'][$num_const]['object']=>$TRACKER_MAPPING['discovery'][$num_const]['oid']),$ip1.".".$ip2.".".$ip3.".".$ip4,$snmp_auth[$num]['snmp_version'],$snmp_auth[$num]);
					if ($Array_type[$TRACKER_MAPPING['discovery'][$num_const]['object']] != ""){
						echo "TYPE :".$TRACKER_MAPPING['discovery'][$num_const]['type']."<br/>";
						$device_type = $TRACKER_MAPPING['discovery'][$num_const]['type'];
					}
				}
				$query_ins = "INSERT INTO glpi_plugin_tracker_discover
				(date,ifaddr,name,descr,type,FK_snmp_connection)
				VALUES ('".strftime("%Y-%m-%d %H:%M:%S", time())."', 
				'".$ip1.".".$ip2.".".$ip3.".".$ip4."',
				'".$Array_Name["sysName"]."',
				'".$Array_sysdescr["sysDescr"]."', 
				'".$device_type."',
				'".$snmp_auth[$num]["ID"]."' ) ";
				$DB->query($query_ins);				
				
				break;
			}	
		}




		// Increment for next IP
		if (($ip1 == $Array_IP["ip21"]) 
			AND ($ip2 == $Array_IP["ip22"])
			AND ($ip3 == $Array_IP["ip23"]))
		{
			if ($ip4 == $Array_IP["ip24"])
			{
				$i = 1;
			}
		}
		else if (($ip1 == $Array_IP["ip21"]) 
			AND ($ip2 == $Array_IP["ip22"]))
		{
			if ($ip4 == "254")
			{
				$ip4 = -1;
				$ip3++;
			}
		}
		else if (($ip1 == $Array_IP["ip21"]))
		{
			if (($ip4 == "254") AND ($ip3 == "254"))
			{
				$ip4 = -1;
				$ip3 = 0;
				$ip2++;
			}
			else if ($ip4 == "254")
			{
				$ip4 = -1;
				$ip3++;
			}
		}
		else
		{
			if (($ip4 == "254") AND ($ip3 == "254") AND ($ip2 == "254"))
			{
				$ip4 = -1;
				$ip3 = 0;
				$ip2 = 0;
				$ip1++;
			}
			else if (($ip4 == "254") AND ($ip3 == "254"))
			{
				$ip4 = -1;
				$ip3 = 0;
				$ip2++;
			}
			else if ($ip4 == "254")
			{
				$ip4 = -1;
				$ip3++;
			}
				
		}
		$ip4++;
	}
}


function plugin_tracker_discovery_getConf()
{
	global $DB;
	
	$query = "SELECT * FROM glpi_plugin_tracker_discover_conf
	LIMIT 0,1 ";

	$result = $DB->query($query);		
	$data = $DB->fetch_assoc($result);

	return $data;	
}
	
	

function plugin_tracker_discovery_display_array($target)
{
	global $DB,$LANG,$LANGTRACKER,$TRACKER_MAPPING;

	$CommonItem = new CommonItem;

	echo "<br>";
	echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";

	echo "<table class='tab_cadre' cellpadding='5' width='90%'>";
	
	echo "<tr class='tab_bg_1'>";
	echo "<th colspan='9'>";
	echo $LANGTRACKER["discovery"][1];
	echo "</th>";
	echo "</tr>";
	
	echo "<tr class='tab_bg_1'>";
	echo "<th></th>";
	echo "<th>".$LANG["common"][27]."</th>";
	echo "<th>".$LANG["networking"][14]."</th>";
	echo "<th>".$LANG["common"][16]."</th>";
	echo "<th>".$LANG["joblist"][6]."</th>";
	echo "<th>".$LANG["common"][19]."</th>";
	echo "<th>".$LANG["common"][17]."</th>";
	echo "<th>".$LANGTRACKER["model_info"][4]."</th>";
	echo "<th>".$LANGTRACKER["model_info"][3]."</th>";
	echo "</tr>";

	$types_numbers = array();
	foreach ($TRACKER_MAPPING['discovery'] as $num_const=>$value_const)
	{
		$types_numbers[] = $TRACKER_MAPPING['discovery'][$num_const]['type'];
	}

	$query = "SELECT * FROM glpi_plugin_tracker_discover";
	if ( $result=$DB->query($query) )
	{
		while ( $data=$DB->fetch_array($result) )
		{
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'><input type='checkbox' name='check[]' value='".$data['ID']."' /></td>";
			echo "<td align='center'>".convdate($data['date'])."</td>";
			echo "<td align='center'>".$data['ifaddr']."</td>";
			echo "<td align='center'>".$data['name']."</td>";
			echo "<td align='center'>".$data['descr']."</td>";
			echo "<td align='center'>".$data['serialnumber']."</td>";
			if ($data['type'] == "0")
			{
				echo "<td align='center'>";
				dropdownDeviceTypes("type-".$data['ID'], 1, $types_numbers);
				echo "</td>";
			}
			else
			{
				echo "<td align='center'>";
				dropdownDeviceTypes("type-".$data['ID'], $data['type'], $types_numbers);
				echo "</td>";
			}
			echo "<td align='center'>";
			dropdownValue("glpi_plugin_tracker_model_infos","model_infos-".$data['ID'],$data["FK_model_infos"],0);
			echo "</td>";
			echo "<td align='center'>";
			plugin_tracker_snmp_auth_dropdown($data["FK_snmp_connection"]);
			echo "</td>";
			echo "</tr>";
		}
	}

	echo "<tr class='tab_bg_1'>";
	echo "<td colspan='9'>";
	echo "<div align='center'>";
	echo "<input type='submit' name='import' value=\"".$LANG["buttons"][37]."\" class='submit' > ";
	echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
	echo "</td>";
	echo "</tr>";

	echo "</table></form>";


}
	
	

function plugin_tracker_discovery_update_devices($array, $target)
{
	global $DB;

	foreach ($array as $key=>$value)
	{
		if (ereg("model_infos", $key))
		{
			$explode = explode ("-", $key);
			$query = "UPDATE glpi_plugin_tracker_discover
			SET FK_model_infos='".$value."',type='".$array['type-'.$explode[1]]."'
			WHERE ID='".$explode[1]."' ";
			$DB->query($query);
		}
	}
}


?>