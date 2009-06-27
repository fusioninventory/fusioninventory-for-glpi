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

if (!defined('GLPI_ROOT'))
	die("Sorry. You can't access directly to this file");



function tracker_snmp_addLog($port,$field,$old_value,$new_value,$FK_process=0)
{
	global $DB,$CFG_GLPI;
	$history = new plugin_tracker_SNMP_history;
	
	$array["FK_ports"] = $port;
	$array["field"] = $field;
	$array["old_value"] = $old_value;
	$array["new_value"] = $new_value;
	
	// Ajouter en DB
	$history->insert_connection("field",$array,$FK_process);
}


	
// $status = connection or disconnection	
function addLogConnection($status,$port,$FK_process=0)
{
	global $DB,$CFG_GLPI;
	$CommonItem = new CommonItem;
	$history = new plugin_tracker_SNMP_history;
	// Récupérer le port de la machine associé au port du switch
	$nw=new Netwire;
	// Récupérer le type de matériel
	$netport=new Netport;
	$array["FK_ports"] = $port;
	$opposite_port = $nw->getOppositeContact($port);
	if ($opposite_port == "0")
		return;
	$netport->getFromDB($opposite_port);
	$array["device_type"] = $netport->fields["device_type"];
	
	// Récupérer l'adresse MAC
	$netport->getFromDB($opposite_port);
	$array["value"] = $netport->fields["ifmac"];
	
	// Récupération de l'id du matériel
	$array["device_ID"] = $netport->fields["on_device"];
	// Ajouter en DB
	$history->insert_connection($status,$array,$FK_process);
}


// $status = connection or disconnection
function plugin_tracker_addLogConnection_unknown_mac($macaddress,$port,$FK_process=0)
{
	global $DB,$CFG_GLPI;

	$history = new plugin_tracker_SNMP_history;
	$netwire = new Netwire;
	$processes = new Threads;

	// * If glpi device connected to this port, disconnect it
	$queryVerif = "SELECT *
		FROM glpi_networking_wire
		WHERE end1 = '".$port."'
			OR end2  = '".$port."' ";

	if ($resultVerif=$DB->query($queryVerif))
	{
		if ( $DB->numrows($resultVerif) != "0" )
		{
			addLogConnection("remove",$netwire->getOppositeContact($port),$FK_process);
			addLogConnection("remove",$port,$FK_process);
			while ( $dataVerif2=$DB->fetch_array($resultVerif) )
			{
				$query_del = "DELETE FROM glpi_networking_wire
					WHERE ID='".$dataVerif2["ID"]."' ";
				$DB->query($query_del);
			}
		}
	}

	// * If other unknown mac adress connected, disconnect it
	$query = "SELECT last_PID_update FROM glpi_networking_ports
		LEFT JOIN glpi_networking ON glpi_networking.ID = glpi_networking_ports.on_device
		LEFT JOIN glpi_plugin_tracker_networking ON glpi_plugin_tracker_networking.FK_networking = glpi_networking.ID
		WHERE glpi_networking_ports.ID=".$port." ";

	$result = $DB->query($query);
	$data = $DB->fetch_assoc($result);

	$PID = $data["last_PID_update"];
echo $PID." => ".$FK_process." => ".$port."\n";
	list($unknownMac, $unknownIP) = $processes->getUnknownMacFromPIDandPort($PID,$port);
	if ((!empty($unknownMac)) AND ($unknownMac != $macaddress))
	{
		$array["FK_ports"] = $port;
		$array["value"] = $unknownMac;
		$array["device_type"] = 0;
		$array["device_ID"] = 0;
		$history->insert_connection("remove",$array,$FK_process);
	}

	// * If this mac address is connected on other port, disconnect it.
		//$query = "SELECT * FROM glpi_plugin_tracker_unknown_mac
		// WHERE unknow_mac
		// AND $FK_process


	list($unknownMac_now, $unknownIP_now) = $processes->getUnknownMacFromPIDandPort($FK_process,$port);
echo $unknownMac." - ".$macaddress."\n";
	// * If same unknown mac adresse connected, nothing
	if ($unknownMac == $macaddress)
	{
echo "1- Nothing \n";
	}
	// else connect in this process
	elseif ($unknownMac_now == $macaddress)
	{
echo "2- Nothing \n";
	}
	// else connect it
	else
	{
		echo "3- Connect \n";
		$array["FK_ports"] = $port;
		$array["value"] = $macaddress;
		$array["device_type"] = 0;
		$array["device_ID"] = 0;
		$history->insert_connection("make",$array,$FK_process);
	}
}


// List of history in networking display
function tracker_snmp_showHistory($ID_port)
{
	global $DB,$LANG,$INFOFORM_PAGES,$CFG_GLPI;

	$CommonItem = new CommonItem;

	$query = "SELECT * FROM glpi_plugin_tracker_snmp_history
	WHERE FK_ports='".$ID_port."'
	ORDER BY date_mod DESC
	LIMIT 0,30";		

	$text = "<table class='tab_cadre' cellpadding='5' width='950'>";

	$text .= "<tr class='tab_bg_1'>";
	$text .= "<th colspan='8'>";
	$text .= "Historique";
	$text .= "</th>";
	$text .= "</tr>";
	
	$text .= "<tr class='tab_bg_1'>";
	$text .= "<th>".$LANG['plugin_tracker']["snmp"][50]."</th>";
	$text .= "<th>".$LANG["common"][1]."</th>";
	$text .= "<th>".$LANG["networking"][15]."</th>";
	$text .= "<th>".$LANG["event"][18]."</th>";
	$text .= "<th></th>";
	$text .= "<th></th>";
	$text .= "<th></th>";
	$text .= "<th>".$LANG["common"][27]."</th>";
	$text .= "</tr>";
	
	if ( $result=$DB->query($query) )
	{
		while ( $data=$DB->fetch_array($result) )
		{
			$text .= "<tr class='tab_bg_1'>";
			
			if (($data["old_device_ID"] != "0") OR ($data["new_device_ID"] != "0"))
			{
				// Connections and disconnections
				if ($data["old_device_ID"] != "0")
				{
					$text .= "<td align='center'>".$LANG['plugin_tracker']["history"][2]."</td>";
					$CommonItem->getFromDB($data["old_device_type"],$data["old_device_ID"]);
					$text .= "<td align='center'>".$CommonItem->getLink(1)."</td>";						
					$text .= "<td align='center'>".$data["old_value"]."</td>";
				}
				else if ($data["new_device_ID"] != "0")
				{
					$text .= "<td align='center'>".$LANG['plugin_tracker']["history"][3]."</td>";
					$CommonItem->getFromDB($data["new_device_type"],$data["new_device_ID"]);
					$text .= "<td align='center'>".$CommonItem->getLink(1)."</td>";
					$text .= "<td align='center'>".$data["new_value"]."</td>";
				}
				$text .= "<td align='center' colspan='4'></td>";
				$text .= "<td align='center'>".convDateTime($data["date_mod"])."</td>";

			}
			elseif (($data["old_device_ID"] == "0") AND ($data["new_device_ID"] == "0") AND ($data["Field"] == "0"))
			{
				// Unknown Mac address
				if (!empty($data["old_value"]))
				{
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$LANGTRACKER["history"][2]."</td>";
					$CommonItem->getFromDB($data["old_device_type"],$data["old_device_ID"]);
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$CommonItem->getLink(1)."</td>";
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$data["old_value"]."</td>";
				}
				elseif (!empty($data["new_value"]))
				{
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$LANGTRACKER["history"][3]."</td>";
					$CommonItem->getFromDB($data["new_device_type"],$data["new_device_ID"]);
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$CommonItem->getLink(1)."</td>";
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$data["new_value"]."</td>";
				}
				$text .= "<td align='center' colspan='4' background='#cf9b9b' class='tab_bg_1_2'></td>";
				$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".convDateTime($data["date_mod"])."</td>";
			}
			else
			{
				// Changes values
				$text .= "<td align='center' colspan='3'></td>";
				$text .= "<td align='center'>".$data["Field"]."</td>";
				$text .= "<td align='center'>".$data["old_value"]."</td>";
				$text .= "<td align='center'>-></td>";
				$text .= "<td align='center'>".$data["new_value"]."</td>";
				$text .= "<td align='center'>".convDateTime($data["date_mod"])."</td>";
			}
			$text .= "</tr>";
		}
	}
	$text .= "<tr class='tab_bg_1'>";
	$text .= "<th colspan='8'>";
	$text .= "<a href='".GLPI_ROOT."/plugins/tracker/report/plugin_tracker.switch_ports.history.php?FK_networking_ports=".$ID_port."'>Voir l'historique complet</a>";
	$text .= "</th>";
	$text .= "</tr>";
	$text .= "</table>";
	return $text;
}

?>