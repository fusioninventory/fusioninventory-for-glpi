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

function plugin_tracker_discovery_scan($Array_IP,$target)
{

	global $DB,$TRACKER_MAPPING;

	$plugin_tracker_snmp = new plugin_tracker_snmp;
	$plugin_tracker_snmp_auth = new plugin_tracker_snmp_auth;

	// Load snmp auth
	$snmp_auth = $plugin_tracker_snmp_auth->GetInfos("all","",0);

	// scan for each IP
	ini_set("memory_limit","-1");
	ini_set("max_execution_time", "0");

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
			$Array_sysdescr = $plugin_tracker_snmp->SNMPQuery(array("sysDescr"=>".1.3.6.1.2.1.1.1.0"),$ip1.".".$ip2.".".$ip3.".".$ip4,$snmp_auth[$num]['snmp_version'],$snmp_auth[$num]);
			if ($Array_sysdescr["sysDescr"] != ""){
				echo $ip1.".".$ip2.".".$ip3.".".$ip4."<br/>";
				var_dump($Array_sysdescr);
				//Port is open, test with oids to determine the device type
				foreach ($TRACKER_MAPPING['discovery'] as $num_const=>$value_const)
				{
					$plugin_tracker_snmp->DefineObject(array($TRACKER_MAPPING['discovery'][$num_const]['object']=>$TRACKER_MAPPING['discovery'][$num_const]['oid']),$ip1.".".$ip2.".".$ip3.".".$ip4);
					$Array_type = $plugin_tracker_snmp->SNMPQuery(array($TRACKER_MAPPING['discovery'][$num_const]['object']=>$TRACKER_MAPPING['discovery'][$num_const]['oid']),$ip1.".".$ip2.".".$ip3.".".$ip4,$snmp_auth[$num]['snmp_version'],$snmp_auth[$num]);
					if ($Array_sysdescr["sysDescr"] != ""){
						echo "TYPE :".$TRACKER_MAPPING['discovery'][$num_const]['type']."<br/>";
					}
				
				}
				
				
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
				break;
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

?>