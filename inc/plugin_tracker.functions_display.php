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

function plugin_tracker_menu()
{
	GLOBAL $CFG_GLPI, $LANG;
	echo "<br>";
	echo "<div align='center'><table class='tab_cadre'>";
	echo "<tr><th width='450'>".$LANG['plugin_tracker']["snmp"][2]."</th></tr>";
	if(plugin_tracker_HaveRight("snmp_models","r"))
		echo "<tr class='tab_bg_1'><td align='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.models.php'><b>".$LANG['plugin_tracker']["model_info"][4]."</b></a></td></tr>";
	if(plugin_tracker_HaveRight("snmp_authentification","r"))
		echo "<tr class='tab_bg_1'><td align='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.snmp_auth.php'><b>".$LANG['plugin_tracker']["model_info"][3]."</b></a></td></tr>";
	if(plugin_tracker_HaveRight("snmp_scripts_infos","r"))
		echo "<tr class='tab_bg_1'><td align='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.processes.php'><b>".$LANG['plugin_tracker']["processes"][0]."</b></a></td></tr>";
	if(plugin_tracker_HaveRight("snmp_discovery","r"))
		echo "<tr class='tab_bg_1'><td align='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.discovery.php'><b>".$LANG['plugin_tracker']["menu"][0]."</b></a></td></tr>";
	echo "</table></div>";

}


function plugin_tracker_mib_management()
{
	GLOBAL $DB,$CFG_GLPI, $LANG;
	$query = "
	SELECT * 
	FROM glpi_plugin_tracker_mib_networking 
	ORDER BY FK_model_infos";
	$result = $DB->query($query);
	$number = $DB->numrows($result);
	
	if($number !="0"){
		echo "<br><form method='post' action=\"./plugin_ticketreport.form.php\">";
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='4'>".$LANG['plugin_tracker']["model_info"][5]." :</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANG['plugin_tracker']["mib"][1]."</th>";
		echo "<th>".$LANG['plugin_tracker']["mib"][2]."</th>";
		echo "<th>".$LANG['plugin_tracker']["mib"][3]."</th>";
		echo "</tr>";

		while ($data=$DB->fetch_array($result)){
			
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'><a href=''><b>
			".getDropdownName("glpi_plugin_tracker_model_infos",$data["FK_model_infos"])."</b></a></td>";
			echo "<td align='center'>
			".getDropdownName("glpi_dropdown_plugin_tracker_mib_label",$data["FK_mib_oid"])."</td>";
			echo "<td align='center'>
			".getDropdownName("glpi_dropdown_plugin_tracker_mib_object",$data["FK_mib_object"])."</td>";
			echo "<td align='center'>
			".getDropdownName("glpi_dropdown_plugin_tracker_mib_oid",$data["FK_mib_oid"])."</td>";
			echo "</tr>";

		}
		echo "</table></div></form>";
	}
	else{


		echo "<br><form method='post' action=\"./plugin_ticketreport.form.php\">";
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='3'>".$LANG['plugin_tracker']["model_info"][5].":</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANG["login"][6]."</th>";
		echo "<th>".$LANG["login"][7]."</th>";
		echo "</tr>";
		echo "</table></div></form>";
	}

}



function plugin_tracker_Bar ($pourcentage, $message="")
{
//	echo "<div class='doaction_cadre' style='height: 20px; '><div  style='width: ".$pourcentage."%;' class='doaction_progress'>".
//  			"<div class='doaction_pourcent' style='margin-top: 3px; '>".$pourcentage."% ".$message."</div></div></div> ";

	echo "<div class='center'>
				<table class='tab_cadre' width='400'>
					<tbody>
						<tr>
							<td align='center' width='400'>".$pourcentage."% ".$message."</td>
						</tr>
						<tr>
							<td>
								<table>
									<tbody>
										<tr>
											<td bgcolor='red' height='20' width='".(4 * $pourcentage)."'>&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>";
}
	


function plugin_tracker_phpextensions ()
{
	global $LANG;
	$snmp = 0;
	$runkit = 0;
	if (extension_loaded('snmp'))
	{
		$snmp = 1;
	}
	if (extension_loaded('runkit'))
	{
		$runkit = 1;
	}
	if (($snmp == "0") OR ($runkit == "0"))
	{
		echo "<div align='center'>";
		echo "<table class='tab_cadre' cellpadding='5'>";
		echo "<tr><th>".$LANG['plugin_tracker']["setup"][13];
		echo "</th></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
		if ($snmp == "0")
			echo $LANG['plugin_tracker']["setup"][14];
		echo "<br/>";
		if ($runkit == "0")
			echo $LANG['plugin_tracker']["setup"][15];
		echo "</td>";
		echo "</tr>";
		echo "</table><br/>";
	
	}
}



?>