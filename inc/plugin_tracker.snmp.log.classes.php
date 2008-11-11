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
class plugin_tracker_SNMP_log extends CommonDBTM {


	function addLog()
	{
		global $DB;

			
	
	
	}
	
	
	// $status = connection or disconnection	
	function addLogConnection($status,$port_networking,$ID2=0)
	{
		global $DB,$CFG_GLPI;
		$CommonItem = new CommonItem;
		// Récupérer le port de la machine associé au port du switch
		$nw=new Netwire;
		if ($ID2 == "0")
			$ID2=$nw->getOppositeContact($port_networking);
		// Récupérer le type de matériel
		$netport=new Netport;
		$netport->getFromDB($ID2);
		$device_type = $netport->fields["device_type"];
		// Récupérer l'adresse MAC
		$netport->getFromDB($ID2);
		$ifmac = $netport->fields["ifmac"];
		// Récupération de l'id du matériel
		$mat_ID = $netport->fields["on_device"];
echo ">>>> LOG-JOURNAL ".$mat_ID."<<<<\n";
		// Ajouter en DB
		if ($status == "remove")
		{
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,old_value,old_device_type,old_device_ID,date_mod)
			VALUES('".$port_networking."','".$ifmac."','".$device_type."','".$mat_ID."','".date("Y-m-d H:i:s")."')";
		}
		else if ($status == "make")
		{
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,new_value,new_device_type,new_device_ID,date_mod)
			VALUES('".$port_networking."','".$ifmac."','".$device_type."','".$mat_ID."','".date("Y-m-d H:i:s")."')";
		}
		$DB->query($query);
		
		$netport->getFromDB($port_networking);
		$device_type = $netport->fields["device_type"];
		// Récupérer l'adresse MAC
		$netport->getFromDB($port_networking);
		$ifmac = $netport->fields["ifmac"];
		// Récupération de l'id du matériel
		$mat_ID = $netport->fields["on_device"];
		// Ajouter en DB
		if ($status == "remove")
		{
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,old_value,old_device_type,old_device_ID,date_mod)
			VALUES('".$ID2."','".$ifmac."','".$device_type."','".$mat_ID."','".date("Y-m-d H:i:s")."')";
		}
		else if ($status == "make")
		{
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,new_value,new_device_type,new_device_ID,date_mod)
			VALUES('".$ID2."','".$ifmac."','".$device_type."','".$mat_ID."','".date("Y-m-d H:i:s")."')";
	
		}
		$DB->query($query);
	}
	
	
	
	function showHistory($ID_port)
	{
		global $DB,$LANG,$LANGTRACKER,$INFOFORM_PAGES,$CFG_GLPI;

		$CommonItem = new CommonItem;

		$query = "
		SELECT * FROM glpi_plugin_tracker_snmp_history
		
		WHERE FK_ports='".$ID_port."'
		
		ORDER BY date_mod
		
		LIMIT 0,30";		
	
		$text = "<table class='tab_cadre' cellpadding='5' width='950'>";
	
		$text .= "<tr class='tab_bg_1'>";
		$text .= "<th colspan='8'>";
		$text .= "Historique";
		$text .= "</th>";
		$text .= "</tr>";
		
		$text .= "<tr class='tab_bg_1'>";
		$text .= "<th>".$LANGTRACKER["snmp"][50]."</th>";
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
						$text .= "<td align='center'>Déconnexion</td>";
						$CommonItem->getFromDB($data["old_device_type"],$data["old_device_ID"]);
						$text .= "<td align='center'>".$CommonItem->getLink(1)."</td>";						
						$text .= "<td align='center'>".$data["old_value"]."</td>";
					}
					else if ($data["new_device_ID"] != "0")
					{
						$text .= "<td align='center'>Connexion</td>";
						$CommonItem->getFromDB($data["new_device_type"],$data["new_device_ID"]);
						$text .= "<td align='center'>".$CommonItem->getLink(1)."</td>";
						$text .= "<td align='center'>".$data["new_value"]."</td>";
					}
					$text .= "<td align='center' colspan='4'></td>";
					$text .= "<td align='center'>".$data["date_mod"]."</td>";

				}
				else
				{
				
					// Changes values
					$text .= "<td align='center' colspan='3'></td>";
					$text .= "<td align='center'>".$data["Field"]."</td>";
					$text .= "<td align='center'>".$data["old_value"]."</td>";
					$text .= "<td align='center'>-></td>";
					$text .= "<td align='center'>".$data["new_value"]."</td>";
					$text .= "<td align='center'>".$data["date_mod"]."</td>";
				
				
				}
				
				$text .= "</tr>";
			}
			
		}

		$text .= "</table>";

		return $text;
	}
}
?>
