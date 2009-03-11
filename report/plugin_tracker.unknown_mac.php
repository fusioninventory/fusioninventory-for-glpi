<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: Balpe Dévi
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}

$NEEDED_ITEMS=array("tracker","search","commonitem","networking","computer","printer");
include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANGTRACKER["title"][0],$_SERVER["PHP_SELF"],"plugins","tracker");

plugin_tracker_checkRight("snmp_scripts_infos","r");

manageGetValuesInSearch(PLUGIN_TRACKER_MAC_UNKNOW);

searchForm(PLUGIN_TRACKER_MAC_UNKNOW,$_SERVER['PHP_SELF'],$_GET["field"],$_GET["contains"],$_GET["sort"],$_GET["deleted"],$_GET["link"],$_GET["distinct"],$_GET["link2"],$_GET["contains2"],$_GET["field2"],$_GET["type2"]);
showList(PLUGIN_TRACKER_MAC_UNKNOW,$_SERVER['PHP_SELF'],$_GET["field"],$_GET["contains"],$_GET["sort"],$_GET["order"],$_GET["start"],$_GET["deleted"],$_GET["link"],$_GET["distinct"],$_GET["link2"],$_GET["contains2"],$_GET["field2"],$_GET["type2"]);
commonFooter();
/*

manageGetValuesInSearch(COMPUTER_TYPE);

if(isset($_GET["dropdown_state"]) && isset($_GET["dropdown_network"]) && isset($_GET["dropdown_calendar"]) 
	&& isset($_GET["dropdown_and1"]) && isset($_GET["dropdown_and2"]) && isset($_GET["dropdown_sup_inf"]))
	{
		
	$i=0;
	
	$field[$i] = 103;
	
	$contains[0] = (($_GET["dropdown_sup_inf"]=="sup")?">":"<").$_GET["dropdown_calendar"];
	$i++;
	
	if($_GET["dropdown_state"])
		{
		$field[$i] = 31;
		$contains[$i] = getDropdownName("glpi_dropdown_state",$_GET["dropdown_state"]);
		$link[$i] = $_GET["dropdown_and1"];
		$_GET["link"] = $link;
		$i++;
		}
	if($_GET["dropdown_network"])
		{
		$field[$i] = 32;
		$contains[$i] = getDropdownName("glpi_dropdown_network",$_GET["dropdown_network"]);
		$link[$i] = $_GET["dropdown_and2"];
		$_GET["link"] = $link;
		$i++;
		}
	
	$session_temp = $_SESSION["glpisearchcount"][COMPUTER_TYPE];
	
	$_SESSION["glpisearchcount"][COMPUTER_TYPE] = $i;
	
	$_GET["field"] = $field;
	$_GET["contains"] = $contains;

	showList(COMPUTER_TYPE,$_SERVER['PHP_SELF'],$_GET["field"],$_GET["contains"],$_GET["sort"],$_GET["order"],$_GET["start"],$_GET["deleted"],$_GET["link"],$_GET["distinct"],$_GET["link2"],$_GET["contains2"],$_GET["field2"],$_GET["type2"]);

	$_SESSION["glpisearchcount"][COMPUTER_TYPE] = $session_temp;
	
	} 
	//else showList(COMPUTER_TYPE,$_SERVER['PHP_SELF'],$_GET["field"],$_GET["contains"],$_GET["sort"],$_GET["order"],$_GET["start"],$_GET["deleted"],$_GET["link"],$_GET["distinct"],$_GET["link2"],$_GET["contains2"],$_GET["field2"],$_GET["type2"]);		
	
echo "</form>";

commonFooter(); 

function displaySearchForm()
{
	global $_SERVER,$_GET,$GEDIFFREPORTLANG,$LANG,$CFG_GLPI;
	echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'>";
	echo "<table class='tab_cadre' cellpadding='5'>";
	echo "<tr class='tab_bg_1' align='center'>";
	echo "<td>";
	echo $LANG["state"][0]." :&nbsp;";
	
	$values["AND"]=$GEDIFFREPORTLANG["computer"][3];
	$values["AND NOT"]=$GEDIFFREPORTLANG["computer"][4];
	dropdownArrayValues("dropdown_and1",$values,(isset($_GET["dropdown_and1"])?$_GET["dropdown_and1"]:"AND"));
	dropdownValue("glpi_dropdown_state","dropdown_state",(isset($_GET["dropdown_state"])?$_GET["dropdown_state"]:3));
	echo "</td>";
	
	echo "<td>";
	echo $LANG["setup"][88]." :&nbsp;";
	dropdownArrayValues("dropdown_and2",$values,(isset($_GET["dropdown_and2"])?$_GET["dropdown_and2"]:"AND NOT"));
	echo "&nbsp;";
	dropdownValue("glpi_dropdown_network","dropdown_network",(isset($_GET["dropdown_network"])?$_GET["dropdown_network"]:4));
	echo "</td>";
	
	echo "<td>";
	echo $LANG["ocsng"][14]." :&nbsp;";
	$values=array();
	$values["sup"]=">";
	$values["inf"]="<";
	dropdownArrayValues("dropdown_sup_inf",$values,(isset($_GET["dropdown_sup_inf"])?$_GET["dropdown_sup_inf"]:"inf"));
	echo "&nbsp;";
	showCalendarForm("form_ic","dropdown_calendar",(isset($_GET["dropdown_calendar"])?$_GET["dropdown_calendar"]:
		date("Y-m-d", mktime(0,0,0,date('m')-3,date('d'),date('Y')))));
	
	echo "</td>";

	// Display Reset search
	echo "<td>";
	echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/reports/report/plugin_reports.computer.php?reset_search=reset_search' ><img title=\"".$LANG["buttons"][16]."\" alt=\"".$LANG["buttons"][16]."\" src='".$CFG_GLPI["root_doc"]."/pics/reset.png' class='calendrier'></a>";
	echo "</td>";
 	
	echo "<td>";
	echo "<input type='submit' value='Valider' class='submit' />";
	echo "</td>";
	
	echo "</tr>";
	echo "</table></form>";
}
 
function getValues($get,$post)
{
	$get=array_merge($get,$post);
	if (isset($get["field"]))
	{
		foreach ($get["field"] as $index => $value)
		{
			switch($value)
			{
				case 103:
					if (strpos( $get["contains"][$index],"lt;")==1)
						$get["dropdown_sup_inf"]="inf";
					else
						$get["dropdown_sup_inf"]="sup";
						
					$get["dropdown_calendar"] = substr($get["contains"][$index],4);
					break;
				case 31:
					$input["tablename"]="glpi_dropdown_state";
					$input["value"]=$get["contains"][$index];
					$input["FK_entities"]=0;
					$get["dropdown_state"] = getDropdownID($input);
					break;
				case 32:
					$input["tablename"]="glpi_dropdown_network";
					$input["value"]=$get["contains"][$index];
					$input["FK_entities"]=0;
					$get["dropdown_network"] = $get["contains"][$index];
					break;
			}
		}
		
		if (isset($get["link"]))
			foreach ($get["link"] as $index=>$value)
				$get["dropdown_and"][$index]=$value;
	}
	return $get;	
}

function resetSearch()
{
	$_GET["start"]=0;
	$_GET["order"]="ASC";
	$_GET["deleted"]=0;
	$_GET["distinct"]="N";
	$_GET["link"]=array();
	$_GET["field"]=array(0=>"view");
	$_GET["contains"]=array(0=>"");
	$_GET["link2"]=array();
	$_GET["field2"]=array(0=>"view");
	$_GET["contains2"]=array(0=>"");
	$_GET["type2"]="";
	$_GET["sort"]=1;
	
	$_GET["dropdown_state"]=5;
	$_GET["dropdown_network"]=4;
	$_GET["dropdown_sup_inf"]="inf";
	$_GET["dropdown_calendar"]=date("Y-m-d", mktime(0,0,0,date('m')-3,date('d'),date('Y')));
}
*/
?>
