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

class plugin_tracker_printers_cartridges extends CommonDBTM {
	function __construct() {
		$this->table = "glpi_plugin_tracker_printers_cartridges";
		//$this->type = PLUGIN_TRACKER_PRINTERS_CARTRIDGES;
	}
}

class plugin_tracker_printers extends CommonDBTM {
	function __construct() {
		$this->table = "glpi_plugin_tracker_printers";
		//$this->type = PLUGIN_TRACKER_PRINTERS_CARTRIDGES;
	}



	function showFormPrinter($target,$ID)
	{
		global $DB,$CFG_GLPI,$LANG,$LANGTRACKER,$TRACKER_MAPPING;	
	
		$this->ID = $ID;
		
		$query = "
		SELECT * 
		FROM glpi_plugin_tracker_printers
		WHERE FK_printers=".$ID." ";

		$result = $DB->query($query);		
		$data = $DB->fetch_assoc($result);
		
		// Add in database if not exist
		if ($DB->numrows($result) == "0")
		{
			$query_add = "INSERT INTO glpi_plugin_tracker_printers
			(FK_printers) VALUES('".$ID."') ";
			
			$DB->query($query_add);
		}
		
		// Form printer informations
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
		dropdownValue("glpi_plugin_tracker_model_infos","FK_model_infos",$data["FK_model_infos"],0);
		echo "</td>";
		echo "</tr>";
	
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANGTRACKER["functionalities"][43]."</td>";
		echo "<td align='center'>";
		plugin_tracker_snmp_auth_dropdown($data["FK_snmp_connection"]);
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANGTRACKER["functionalities"][24]."</td>";
		echo "<td align='center'>";
		dropdownInteger("frequence_days",$data["frequence_days"], 1,100);
		echo "&nbsp;&nbsp;".$LANG["stats"][31];
		echo "</td>";
		echo "</tr>";		
		
		echo "<tr class='tab_bg_1'>";
		echo "<td colspan='2'>";
		echo "<div align='center'>";
		echo "<input type='hidden' name='ID' value='".$ID."'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
		echo "</td>";
		echo "</tr>";

		echo "</table></form>";

		// ** FORM FOR CARTRIDGES

		echo "<br/><div align='center'><form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";

		echo "<table class='tab_cadre' cellpadding='5' width='800'>";		

		echo "<tr class='tab_bg_1'>";
		echo "<th align='center' colspan='2'>";
		echo $LANG["cartridges"][16];
		echo "</th>";
		echo "</tr>";

		$query_cartridges = "
		SELECT * 
		FROM glpi_plugin_tracker_printers_cartridges
		WHERE FK_printers=".$ID." ";
		if ( $result_cartridges=$DB->query($query_cartridges) )
		{
			while ( $data_cartridges=$DB->fetch_array($result_cartridges) )
			{
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>";
				echo $TRACKER_MAPPING[PRINTER_TYPE][$data_cartridges['object_name']]['shortname'];
				echo " : ";
				dropdownValue("glpi_cartridges_type","FK_cartridges",$data_cartridges['FK_cartridges'],0);
				echo "</td>";
				echo "<td align='center'>";
				plugin_tracker_Bar($data_cartridges['state']); 
				echo "</td>";
				echo "</tr>";
			}
		}
				
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		echo "<select name='object_name'>";
		foreach ($TRACKER_MAPPING[PRINTER_TYPE] AS $cartridges=>$value)
		{
			if (ereg("cartridges", $cartridges))
			{
				echo "<option value='".$cartridges."'>".$TRACKER_MAPPING[PRINTER_TYPE][$cartridges]['name']."</option>";
			}
		}
		echo "</select>";
		echo "</td>";
		echo "<td align='center'>";
		dropdownCompatibleCartridges($ID);
		echo "</td>";
		echo "</tr>";	
			
		echo "<tr class='tab_bg_1'>";
		echo "<td colspan='2'>";
		echo "<div align='center'>";
		echo "<input type='hidden' name='ID' value='".$ID."'>";
		echo "<input type='hidden' name='state' value='100'>";
		echo "<input type='submit' name='add' value=\"".$LANG["buttons"][8]."\" class='submit' >";
		echo "</td>";
		echo "</tr>";

		echo "</table></form>";
		
	
	}



	function update_printers_infos($ID, $FK_model_infos, $FK_snmp_connection)
	{
		global $DB;
		
		$query = "UPDATE glpi_plugin_tracker_printers
		SET FK_model_infos='".$FK_model_infos."',FK_snmp_connection='".$FK_snmp_connection."'
		WHERE FK_printers='".$ID."' ";
	
		$DB->query($query);

	}	
}
?>