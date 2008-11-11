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

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
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
				
				// Ajout d'une dernière ligne pour ajouter nouveau OID
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
		
		echo "Ajouté avec succès<br/>";
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.models.form.php?ID=".$ID."'><b>Retour</b></a>";
		
	}
}

?>