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

function plugin_tracker_menu() {
	global $CFG_GLPI,$LANG;

	$width="150";

	echo "<br>";
	echo "<div align='center'>
		<table class='tab_cadre'>";

	echo "<tr><th colspan='4'>".$LANG['plugin_tracker']["title"][0]."</th></tr>";
	
	echo "<tr class='tab_bg_1'><td align='center' width='".$width."' height='130'>";
	if(plugin_tracker_HaveRight("snmp_models","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.models.php'>
			<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_models.png'/>
			<br/><b>".$LANG['plugin_tracker']["model_info"][4]."</b></a>";
   }
	echo "</td>";

	echo "<td align='center' width='".$width."' height='130'>";
	if(plugin_tracker_HaveRight("snmp_authentification","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.snmp_auth.php'>
			<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_authentification.png'/>
			<br/><b>".$LANG['plugin_tracker']["model_info"][3]."</b></a>";
   }
	echo "</td>";

	echo "<td align='center' width='".$width."' height='130'>";
	if(plugin_tracker_HaveRight("snmp_iprange","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.rangeip.php'>
		<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_rangeip.png'/>
		<br/><b>".$LANG['plugin_tracker']["menu"][2]."</b></a>";
   }
	echo "</td>";

	echo "<td align='center' width='".$width."' height='130'>";
	if(plugin_tracker_HaveRight("snmp_agent","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.agents.php'>
		<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_agents.png'/><br/>
		<b>".$LANG['plugin_tracker']["menu"][1]."</b></a>";
   }
	echo "</td>";
	echo "</tr>";

	
	echo "<tr class='tab_bg_1'>";
	echo "<td align='center' width='".$width."' height='130'>";
	if(plugin_tracker_HaveRight("tracker_task","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.task.php'>
			<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_task.png'/>
			<br/><b>".$LANG['plugin_tracker']["task"][1]."</b></a>";
   }
	echo "</td>";

	echo "<td align='center' width='".$width."' height='130'>";
	if(plugin_tracker_HaveRight("snmp_agent_infos","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"].
               "/plugins/tracker/front/plugin_tracker.agents.processes.php'>
		<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_info_agents.png'/>
		<br/><b>".$LANG['plugin_tracker']["processes"][19]."</b></a>";
   }
	echo "</td>";

	echo "<td align='center' width='".$width."' height='130'>";
	if(plugin_tracker_HaveRight("snmp_networking","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.unknown.php'>
			<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_unknown_device.png'/>
			<br/><b>".$LANG['plugin_tracker']["menu"][4]."</b></a>";
   }
	echo "</td>";

	echo "<td align='center' width='".$width."' height='130'>";
	if(plugin_tracker_HaveRight("snmp_report","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.report.php'>
		<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_rapports.png'/>
		<br/><b>".$LANG['plugin_tracker']["processes"][20]."</b></a>";
   }
	echo "</td>";
	echo "</tr>";

	echo "</table></div>";

}



function plugin_tracker_mini_menu() {
	global $CFG_GLPI,$LANG;
	
	$width="50";

	echo "<div align='center'>
		<table class='tab_cadre'>";

//	echo "<tr><th colspan='8'>".$LANG['plugin_tracker']["menu"][3]."</th></tr>";
	
	echo "<tr class='tab_bg_1'><td align='center' width='".$width."' height='40'>";
	if(plugin_tracker_HaveRight("snmp_models","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.models.php'>
			<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_mini_models.png'
				 onmouseout=\"cleanhide('menu_mini_models')\"
             onmouseover=\"cleandisplay('menu_mini_models')\" /></a>";
   }
	echo "<span class='over_link' id='menu_mini_models'>".$LANG['plugin_tracker']["model_info"][4].
        "</span>";
	echo "</td>";
	echo "<td align='center' width='".$width."' height='40'>";
	if(plugin_tracker_HaveRight("snmp_authentification","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.snmp_auth.php'>
			<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_mini_authentification.png'
				 onmouseout=\"cleanhide('menu_mini_authentification')\"
             onmouseover=\"cleandisplay('menu_mini_authentification')\" /></a>";
   }
	echo "<span class='over_link' id='menu_mini_authentification'>".
        $LANG['plugin_tracker']["model_info"][3]."</span>";
	echo "</td>";
	echo "<td align='center' width='".$width."' height='40'>";
	if(plugin_tracker_HaveRight("snmp_iprange","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.rangeip.php'>
		<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_mini_rangeip.png'
				 onmouseout=\"cleanhide('menu_mini_rangeip')\"
             onmouseover=\"cleandisplay('menu_mini_rangeip')\" /></a>";
   }
   echo "<span class='over_link' id='menu_mini_rangeip'>".$LANG['plugin_tracker']["menu"][2].
        "</span>";
	echo "</td>";
	echo "<td align='center' width='".$width."' height='40'>";
	if(plugin_tracker_HaveRight("snmp_agent","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.agents.php'>
		<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_mini_agents.png'
				 onmouseout=\"cleanhide('menu_mini_agents')\"
             onmouseover=\"cleandisplay('menu_mini_agents')\" /></a>";
   }
   echo "<span class='over_link' id='menu_mini_agents'>".$LANG['plugin_tracker']["menu"][1].
        "</span>";
	echo "</td>";
	echo "<td align='center' width='".$width."' height='40'>";
	if(plugin_tracker_HaveRight("tracker_task","r")){
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.task.php'>
			<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_mini_task.png'
				 onmouseout=\"cleanhide('menu_mini_task')\"
             onmouseover=\"cleandisplay('menu_mini_task')\" /></a>";
   }
   echo "<span class='over_link' id='menu_mini_task'>".
            $LANG['plugin_tracker']["task"][1]."</span>";
	echo "</td>";
	echo "<td align='center' width='".$width."' height='40'>";
	if(plugin_tracker_HaveRight("snmp_agent_infos","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"].
         "/plugins/tracker/front/plugin_tracker.agents.processes.php'>
		<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_mini_info_agents.png'
				 onmouseout=\"cleanhide('menu_mini_info_agents')\"
             onmouseover=\"cleandisplay('menu_mini_info_agents')\" /></a>";
   }
   echo "<span class='over_link' id='menu_mini_info_agents'>".
            $LANG['plugin_tracker']["processes"][19]."</span>";
	echo "</td>";
	echo "<td align='center' width='".$width."' height='40'>";
	if(plugin_tracker_HaveRight("snmp_discovery","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.unknown.php'>
			<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_mini_unknown_device.png'
				 onmouseout=\"cleanhide('menu_mini_unknown')\"
             onmouseover=\"cleandisplay('menu_mini_unknown')\" /></a>";
   }
   echo "<span class='over_link' id='menu_mini_unknown'>".$LANG['plugin_tracker']["menu"][4].
        "</span>";
	echo "</td>";
	echo "<td align='center' width='".$width."' height='40'>";
	if(plugin_tracker_HaveRight("snmp_report","r")) {
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.report.php'>
		<img src='".GLPI_ROOT."/plugins/tracker/pics/menu_mini_rapports.png'
				 onmouseout=\"cleanhide('menu_mini_rapports')\"
             onmouseover=\"cleandisplay('menu_mini_rapports')\" /></a>";
   }
   echo "<span class='over_link' id='menu_mini_rapports'>".$LANG['plugin_tracker']["processes"][20].
        "</span>";
	echo "</td>";

	echo "</tr>";
	
	echo "</table></div>";

	echo "<table>
		<tr>
			<td height='2'></td>
		</tr>
	</table>";

}



function plugin_tracker_mib_management() {
	global $DB,$CFG_GLPI,$LANG;
   
	$query = "SELECT *
             FROM `glpi_plugin_tracker_mib_networking`
             ORDER BY `FK_model_infos`;";
	$result = $DB->query($query);
	$number = $DB->numrows($result);
	
	if($number !="0") {
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='4'>".$LANG['plugin_tracker']["model_info"][5]." :</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANG['plugin_tracker']["mib"][1]."</th>";
		echo "<th>".$LANG['plugin_tracker']["mib"][2]."</th>";
		echo "<th>".$LANG['plugin_tracker']["mib"][3]."</th>";
		echo "</tr>";

		while ($data=$DB->fetch_array($result)) {
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'><a href=''><b>
            ".getDropdownName("glpi_plugin_tracker_model_infos",$data["FK_model_infos"]).
            "</b></a></td>";
			echo "<td align='center'>
            ".getDropdownName("glpi_dropdown_plugin_tracker_mib_label",$data["FK_mib_oid"])."</td>";
			echo "<td align='center'>
            ".getDropdownName("glpi_dropdown_plugin_tracker_mib_object",$data["FK_mib_object"]).
            "</td>";
			echo "<td align='center'>
            ".getDropdownName("glpi_dropdown_plugin_tracker_mib_oid",$data["FK_mib_oid"])."</td>";
			echo "</tr>";

		}
		echo "</table></div>";
	} else {
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='3'>".$LANG['plugin_tracker']["model_info"][5].":</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANG["login"][6]."</th>";
		echo "<th>".$LANG["login"][7]."</th>";
		echo "</tr>";
		echo "</table></div>";
	}
}



function plugin_tracker_Bar ($pourcentage, $message="",$order='') {
	if ((!empty($pourcentage)) AND ($pourcentage < 0)) {
		$pourcentage = "";
   } else if ((!empty($pourcentage)) AND ($pourcentage > 100)) {
		$pourcentage = "";
   }
	echo "<div>
				<table class='tab_cadre' width='400'>
					<tbody>
						<tr>
							<td align='center' width='400'>";

	if ((!empty($pourcentage)) OR ($pourcentage == "0")) {
		echo $pourcentage."% ".$message;
   }

   echo						"</td>
						</tr>
						<tr>
							<td>
								<div>
								<table>
									<tbody>
										<tr>
											<td width='400' height='0' colspan='2'></td>										
										</tr>
										<tr>";
	if (empty($pourcentage)) {
		echo "<td></td>";
   } else {
		echo "										<td bgcolor='";
		if ($order!= '') {
			if ($pourcentage > 80) {
				echo "red";
         } else if($pourcentage > 60) {
				echo "orange";
         } else {
				echo "green";
         }
		} else {
			if ($pourcentage < 20) {
				echo "red";
         } else if($pourcentage < 40) {
				echo "orange";
         } else {
				echo "green";
         }
		}
		if ($pourcentage == 0) {
			echo "' height='20' width='1'>&nbsp;</td>";
      } else {
         echo "' height='20' width='".(4 * $pourcentage)."'>&nbsp;</td>";
      }
	}
   if ($pourcentage == 0) {
      echo "									<td width='1'></td>";
   } else {
      echo "									<td width='".(400 - (4 * $pourcentage))."'></td>";
   }
   echo "								</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>";
}
	
?>
