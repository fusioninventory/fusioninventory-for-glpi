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
	GLOBAL $CFG_GLPI, $LANG, $LANGTRACKER;
	echo "<br>";
	echo "<div align='center'><table class='tab_cadre'>";
	echo "<tr><th width='450'>".$LANGTRACKER["snmp"][2]."</th></tr>";
	echo "<tr class='tab_bg_1'><td align='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.models.php'><b>".$LANGTRACKER["model_info"][4]."</b></a></td></tr>";
	echo "<tr class='tab_bg_1'><td align='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.snmp_auth.php'><b>".$LANGTRACKER["model_info"][3]."</b></a></td></tr>";
	echo "<tr class='tab_bg_1'><td align='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.processes.php'><b>".$LANGTRACKER["processes"][0]."</b></a></td></tr>";
	echo "</table></div>";

}

function plugin_tracker_models_infos()
{
	GLOBAL $DB,$CFG_GLPI, $LANG, $LANGTRACKER;
	$query = "
	SELECT * 
	FROM glpi_plugin_tracker_model_infos 
	ORDER BY name";
	$result = $DB->query($query);
	$number = $DB->numrows($result);
	
	if($number !="0"){
		echo "<br>";
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='6'>".$LANGTRACKER["model_info"][4]." :</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANG["common"][22]."</th>";
		echo "<th>".$LANG["networking"][49]."</th>";
		echo "<th>".$LANGTRACKER["model_info"][2]."</th>";
		echo "<th>".$LANGTRACKER["model_info"][3]."</th>";
		echo "<th>".$LANG["buttons"][31]."</th>";
		echo "</tr>";

		while ($data=$DB->fetch_array($result)){
			
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'><a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.models.form.php?ID=".$data["ID"]."'><b>".$data["name"]."</b></a></td>";

			echo "<td align='center'>".getDropdownName("glpi_dropdown_model_networking",$data["FK_model_networking"])."</td>";

			echo "<td align='center'>".getDropdownName("glpi_dropdown_firmware",$data["FK_firmware"])."</td>";
			echo "<td align='center'>".getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$data["FK_snmp_version"])."</td>";
			echo "<td align='center'>".getDropdownName("glpi_plugin_tracker_snmp_connection",$data["FK_snmp_connection"])."</td>";
			echo "<td align='center'>
			<form method='get' action='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.models.export.php' target='_blank'>
			<input type='hidden' name='model' value='".$data["ID"]."' />
			<input name='export' src='".GLPI_ROOT."/pics/right.png' title='Exporter' value='Exporter' type='image'>
			</form></td>";
			echo "</tr>";

		}
		echo "</table></div>";
	}
	else{


		echo "<br><form method='post' action=\"./plugin_ticketreport.form.php\">";
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='5'>".$LANG["document"][21].":</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th width='100px'>".$LANG["document"][2]."</th>";
		echo "<th>".$LANG["document"][33]."</th>";
		echo "<th>".$LANG["document"][3]."</th>";
		echo "<th>".$LANG["document"][4]."</th>";
		echo "</tr>";
		echo "</table></div></form>";
	}

}

function plugin_tracker_mib_management()
{
	GLOBAL $DB,$CFG_GLPI, $LANG, $LANGTRACKER;
	$query = "
	SELECT * 
	FROM glpi_plugin_tracker_mib_networking 
	ORDER BY FK_model_infos";
	$result = $DB->query($query);
	$number = $DB->numrows($result);
	
	if($number !="0"){
		echo "<br><form method='post' action=\"./plugin_ticketreport.form.php\">";
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='4'>".$LANGTRACKER["model_info"][5]." :</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANGTRACKER["mib"][1]."</th>";
		echo "<th>".$LANGTRACKER["mib"][2]."</th>";
		echo "<th>".$LANGTRACKER["mib"][3]."</th>";
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
		echo "<tr><th colspan='3'>".$LANGTRACKER["model_info"][5].":</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANG["login"][6]."</th>";
		echo "<th>".$LANG["login"][7]."</th>";
		echo "</tr>";
		echo "</table></div></form>";
	}

}

function plugin_tracker_snmp_connections()
{
	GLOBAL $DB,$CFG_GLPI, $LANG, $LANGTRACKER;
	$query = "
	SELECT * 
	FROM glpi_plugin_tracker_snmp_connection 
	ORDER BY name";
	$result = $DB->query($query);
	$number = $DB->numrows($result);
	
	if($number !="0"){
		echo "<br><form method='post' action=\"./plugin_ticketreport.form.php\">";
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='3'>".$LANGTRACKER["model_info"][3]." :</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANG["login"][6]."</th>";
		echo "<th>".$LANG["login"][7]."</th>";
		echo "</tr>";

		while ($data=$DB->fetch_array($result)){
			
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'><a href=''><b>".$data["name"]."</b></a></td>";
			echo "<td align='center'>".$data["login"]."</td>";
			echo "<td align='center'>".$data["password"]."</td>";
			echo "</tr>";

		}
		echo "</table></div></form>";
	}
	else{


		echo "<br><form method='post' action=\"./plugin_ticketreport.form.php\">";
		echo "<div align='center'><table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='3'>".$LANGTRACKER["model_info"][3].":</th></tr>";
		echo "<tr><th>".$LANG["common"][16]."</th>";
		echo "<th>".$LANG["login"][6]."</th>";
		echo "<th>".$LANG["login"][7]."</th>";
		echo "</tr>";
		echo "</table></div></form>";
	}

}
?>