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


class PluginFusionInventoryProfile extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_profiles";
		$this->type=-1;
	}
	
	//if profile deleted
	function cleanProfiles($ID) {
		global $DB;

		$query = "DELETE FROM `glpi_plugin_fusioninventory_profiles`
                WHERE `ID`='$ID';";
		$DB->query($query);
	}
		
	function showprofileForm($target,$ID) {
		global $LANG,$CFG_GLPI;

		if (!haveRight("profile","r")) return false;

		$onfocus="";
		if ($ID) {
			$this->getFromDB($ID);
		} else {
			$this->getEmpty();
			$onfocus="onfocus=\"this.value=''\"";
		}

		if (empty($this->fields["interface"])) $this->fields["interface"]="fusioninventory";
		if (empty($this->fields["name"])) $this->fields["name"]=$LANG["common"][0];


		echo "<form name='form' method='post' action=\"$target\">";
		echo "<div align='center'>";
		echo "<table class='tab_cadre'><tr>";
		echo "<table class='tab_cadre_fixe'>";
		echo "<th>".$LANG["common"][16].":</th>";
		echo "<th><input type='text' name='name' value=\"".$this->fields["name"]."\" $onfocus></th>";
		echo "<tr><th colspan='2' align='center'><strong>TEST ".$this->fields["name"]."</strong></th></tr>";

		echo "<th>".$LANG["profiles"][2].":</th>";
		echo "<th><select name='interface' id='profile_interface'>";
		echo "<option value='fusioninventory' ".($this->fields["interface"]!="fusioninventory"?"selected":"").">".$LANG['plugin_fusioninventory']["profile"][1]."</option>";

		echo "</select></th>";
		echo "</tr></table>";
		echo "</div>";
		
		$params=array('interface'=>'__VALUE__',
				'ID'=>$ID,
			);
		ajaxUpdateItemOnSelectEvent("profile_interface","profile_form",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/profiles.php",$params,false);
		ajaxUpdateItem("profile_form",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/profiles.php",$params,false,'profile_interface');
//$prof=new PluginFusionInventoryProfile;

//	$prof->showfusioninventoryForm($_POST["ID"]);

		echo "<br>";

		echo "<div align='center' id='profile_form'>";
		echo "</div>";

		echo "</form>";

	}
	
	function showForm($target,$ID) {
		global $LANG;

		if (!haveRight("profile","r")) return false;
		$canedit=haveRight("profile","w");
		if ($ID) {
			$this->getFromDB($ID);
		}

		echo "<form action='".$target."' method='post'>";
		echo "<table class='tab_cadre_fixe'>";

		echo "<tr>";
      echo "<th colspan='4' align='center'>";
      echo $LANG['plugin_fusioninventory']["profile"][0]." ".$this->fields["name"];
      echo "</th>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][16]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("snmp_networking",$this->fields["snmp_networking"],1,1,1);
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][23]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("configuration",$this->fields["configuration"],1,1,1);
		echo "</td>";
		echo "</tr>";      

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][18]." :</td><td>";
		dropdownNoneReadWrite("snmp_printers",$this->fields["snmp_printers"],1,1,1);
		echo "</td>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusioninventory']["profile"][34]." :";
      echo "</th>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][19]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("snmp_models",$this->fields["snmp_models"],1,1,1);
		echo "</td>";

		echo "<td>".$LANG['plugin_fusioninventory']["profile"][29]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("remotecontrol",$this->fields["remotecontrol"],1,0,1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][20]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("snmp_authentification",$this->fields["snmp_authentification"],1,1,1);
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][31]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("deviceinventory",$this->fields["deviceinventory"],1,0,1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][25]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("rangeip",$this->fields["rangeip"],1,1,1);
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][22]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("netdiscovery",$this->fields["netdiscovery"],1,0,1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][26]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("agents",$this->fields["agents"],1,1,1);
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][32]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("snmp_query",$this->fields["snmp_query"],1,0,1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][27]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("agentsprocesses",$this->fields["agentsprocesses"],1,1,0);
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][33]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("wol",$this->fields["wol"],1,0,1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][30]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("unknowndevices",$this->fields["unknowndevices"],1,1,1);
		echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][28]." :</td>";
      echo "<td>";
		dropdownNoneReadWrite("reports",$this->fields["reports"],1,1,0);
		echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
		echo "</tr>";

		if ($canedit) {
			echo "<tr class='tab_bg_1'>";
			echo "<td  align='center' colspan='4'>";
			echo "<input type='hidden' name='ID' value=$ID>";
			echo "<input type='submit' name='update_user_profile' value=\"".$LANG["buttons"][7]."\" class='submit'>";
			echo "</td></tr>\n";
		}
		echo "</table>";

	}
	
}

?>