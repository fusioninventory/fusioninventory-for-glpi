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

$NEEDED_ITEMS=array("profile");
define('GLPI_ROOT', '../../..'); 

include (GLPI_ROOT."/inc/includes.php");
checkRight("profile","r");

commonHeader($LANG["Menu"][35],$_SERVER["PHP_SELF"],"plugins","tracker","summary");

$prof=new plugin_tracker_Profile();

if(!isset($_POST["ID"])) $ID=0;
else $ID=$_POST["ID"];

if (isset($_POST["add"])){
	
		checkRight("profile","w");
		$ID=$prof->add($_POST);
		
}else  if (isset($_POST["delete"])){
	checkRight("profile","w");
		
		$prof->delete($_POST);
		$ID=0;
}elseif (isset($_POST["delete_profile"])){
	
	foreach ($_POST["item"] as $key => $val){
		if ($val==1) {

			$query="DELETE FROM glpi_plugin_tracker_profiles WHERE ID='".$key."'";
			$DB->query($query);
		}
	}
			
	glpi_header($_SERVER['HTTP_REFERER']);
		
}
else  if (isset($_POST["update"])){
	checkRight("profile","w");
		
		$prof->update($_POST);
}

echo "<div align='center'><form method='post' name='massiveaction_form' id='massiveaction_form'  action=\"./plugin_tracker.profile.php\">";

echo "<table class='tab_cadre' cellpadding='5'>";
echo "<tr>";
echo "	<th colspan='11'>".$LANG['plugin_tracker']["profile"][10]." : </th>";
echo "</tr>";
echo "<tr>";
echo "	<th></th>";
echo "	<th></th>";
echo "	<th>".$LANG["Menu"][35]."</th>";
echo "	<th>".$LANG['plugin_tracker']["profile"][16]."</th>";
echo "	<th>".$LANG['plugin_tracker']["profile"][17]."</th>";
echo "	<th>".$LANG['plugin_tracker']["profile"][18]."</th>";
echo "	<th>".$LANG['plugin_tracker']["profile"][19]."</th>";
echo "	<th>".$LANG['plugin_tracker']["profile"][20]."</th>";
echo "	<th>".$LANG['plugin_tracker']["profile"][21]."</th>";
echo "	<th>".$LANG['plugin_tracker']["profile"][22]."</th>";
echo "	<th>".$LANG['plugin_tracker']["profile"][23]."</th>";

$query0="SELECT * FROM glpi_plugin_tracker_profiles ORDER BY name";
$result0=$DB->query($query0);

while ($data0=$DB->fetch_assoc($result0)){
	$ID0=$data0['ID'];
	echo "<tr class='tab_bg_1'>";
	echo "<td align='center'>";
	echo "<input type='hidden' name='ID' value='$ID0'>";
	echo "<input type='checkbox' name='item[$ID0]' value='1'>";
	echo "</td>";
	echo "<td>".$data0['ID']."</td><td>".$data0['name']."</td>";
	
	if ($data0['snmp_networking']=='r')
		echo "<td>".$LANG["profiles"][10]."</td>";
	elseif ($data0['snmp_networking']=='w')
		echo "<td>".$LANG["profiles"][11]."</td>";
	else
		echo "<td>".$LANG["profiles"][12]."</td>";
	
	if ($data0['snmp_peripherals']=='r')
		echo "<td>".$LANG["profiles"][10]."</td>";
	elseif ($data0['snmp_peripherals']=='w')
		echo "<td>".$LANG["profiles"][11]."</td>";
	else
		echo "<td>".$LANG["profiles"][12]."</td>";

	if ($data0['snmp_printers']=='r')
		echo "<td>".$LANG["profiles"][10]."</td>";
	elseif ($data0['snmp_printers']=='w')
		echo "<td>".$LANG["profiles"][11]."</td>";
	else
		echo "<td>".$LANG["profiles"][12]."</td>";
	
	if ($data0['snmp_models']=='r')
		echo "<td>".$LANG["profiles"][10]."</td>";
	elseif ($data0['snmp_models']=='w')
		echo "<td>".$LANG["profiles"][11]."</td>";
	else
		echo "<td>".$LANG["profiles"][12]."</td>";
	
	if ($data0['snmp_authentification']=='r')
		echo "<td>".$LANG["profiles"][10]."</td>";
	elseif ($data0['snmp_authentification']=='w')
		echo "<td>".$LANG["profiles"][11]."</td>";
	else
		echo "<td>".$LANG["profiles"][12]."</td>";
	
	if ($data0['snmp_scripts_infos']=='r')
		echo "<td>".$LANG["profiles"][10]."</td>";
	elseif ($data0['snmp_scripts_infos']=='w')
		echo "<td>".$LANG["profiles"][11]."</td>";
	else
		echo "<td>".$LANG["profiles"][12]."</td>";
	
	if ($data0['snmp_discovery']=='r')
		echo "<td>".$LANG["profiles"][10]."</td>";
	elseif ($data0['snmp_discovery']=='w')
		echo "<td>".$LANG["profiles"][11]."</td>";
	else
		echo "<td>".$LANG["profiles"][12]."</td>";
	
	if ($data0['general_config']=='r')
		echo "<td>".$LANG["profiles"][10]."</td>";
	elseif ($data0['general_config']=='w')
		echo "<td>".$LANG["profiles"][11]."</td>";
	else
		echo "<td>".$LANG["profiles"][12]."</td>";

}

echo "<tr class='tab_bg_1'><td colspan='11'>";
echo "<div align='center'><a onclick= \"if ( markAllRows('massiveaction_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG["buttons"][18]."</a>";
echo " - <a onclick= \"if ( unMarkAllRows('massiveaction_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG["buttons"][19]."</a> ";
echo "<input type='submit' name='delete_profile' value=\"".$LANG["buttons"][6]."\" class='submit' ></div></td></tr>";	
echo "</table></form></div>";

echo "<div align='center'><form method='post' action=\"".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.profile.php\">";
echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='2'>";
echo $LANG["profiles"][1].": </th></tr><tr class='tab_bg_1'><td>";

$query="SELECT ID, name FROM glpi_profiles ORDER BY name";
$result=$DB->query($query);

echo "<select name='ID'>";
while ($data=$DB->fetch_assoc($result)){
	echo "<option value='".$data["ID"]."' ".($ID==$data["ID"]?"selected":"").">".$data['name']."</option>";
}
echo "</select>";
echo "<td><input type='submit' value=\"".$LANG["buttons"][2]."\" class='submit' ></td></tr>";
echo "</table></form></div>";

if ($ID>0){	
	if ($prof->GetfromDB($ID)){
		$prof->showprofileForm($_SERVER["PHP_SELF"],$ID);
	}
	else {
		plugin_tracker_createaccess($ID);
		$prof->showprofileForm($_SERVER["PHP_SELF"],$ID);		
	}
}

commonFooter();

?>
