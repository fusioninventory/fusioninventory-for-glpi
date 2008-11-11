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


class plugin_tracker_model_infos extends CommonDBTM
{

	function addentry($target,$ArrayPost)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
		
		$query = "INSERT INTO glpi_plugin_tracker_model_infos
		(name, FK_model_networking, FK_firmware, FK_snmp_version, FK_snmp_connection)
		VALUES ('".$ArrayPost["name"]."', '".$ArrayPost["FK_model_networking"]."', '".$ArrayPost["FK_firmware"]."', 
		'".$ArrayPost["FK_snmp_version"]."','".$ArrayPost["FK_snmp_connection"]."')";
		
		$DB->query($query);
		
		echo "Ajouté avec succès<br/>";
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.models.form.php?ID=".$ID."'><b>Retour</b></a>";
		
	}
	
	

	function showForm($target,$ID,$table)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
		
		if ( !plugin_tracker_haveRight("errors","r") )
		{
			return false;
		}
		else
		{

			$query = "
			SELECT * 
			FROM ".$table."
			WHERE ID=".$ID." ";

			if ($result = $DB->query($query))
			{
				$data= $DB->fetch_array($result);
				echo "<br>";
				echo "<div align='center'><form method='post' name='' id=''  action=\"".$target."\">";
		
				echo "<table class='tab_cadre' cellpadding='5' width='600'><tr><th colspan='2'>";
				if ($ID == "0")
				{
					echo $LANGTRACKER["model_info"][7];
				}
				else
				{
					echo $LANGTRACKER["model_info"][6];
				}
				
				echo " :</th></tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANG["common"][16]."</td>";
				echo "<td align='center'>";
				echo "<input name='name' value='".$data["name"]."'/>";
				echo "</td>";
				echo "</tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANG["common"][22]."</td>";
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_model_networking","model_networking",$data["FK_model_networking"],1);
				echo "</td>";
				echo "</tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANG["networking"][49]."</td>";
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_firmware","firmware",$data["FK_firmware"],1,-1,"");
				echo "</td>";
				echo "</tr>";
	
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANGTRACKER["model_info"][2]."</td>";
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_snmp_version","snmp_version",$data["FK_snmp_connection"],1);
				echo "</td>";
				echo "</tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'>".$LANGTRACKER["model_info"][3]."</td>";
				echo "<td align='center'>";
				dropdownValue("glpi_plugin_tracker_snmp_connection","snmp_connection","",0);
				echo "</td>";
				echo "</tr>";
				
				echo "<tr class='tab_bg_2'><td colspan='2'>";
				echo "<input type='hidden' value='".$ID."'/>";
				if ($ID == "0"){
					echo "<div align='center'><input type='submit' name='add' value=\"".$LANG["buttons"][8]."\" class='submit' >";
				
				}else{
					echo "<div align='center'><input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
					if ($data["deleted"]=='0')
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit'>";
					else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='restore' value=\"".$LANG["buttons"][21]."\" class='submit'>";
		
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$LANG["buttons"][22]."\" class='submit'>";
					}
				}
				echo "</td>";		
				echo "</tr>";
	//			echo "<div align='center'><input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";	
				echo "</table></form></div>";
			}
		}
	}
}


?>