<?php
/*
 * @version $Id: connection.function.php 6975 2008-06-13 15:43:18Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------


function plugin_tracker_getSearchOption(){
	global $LANG, $LANGTRACKER;
	$sopt=array();

	// Part header
	$sopt[PLUGIN_TRACKER_ERROR_TYPE]['common']=$LANGTRACKER["errors"][0];
	
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['table']='glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['field']='ifaddr';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['linkfield']='ifaddr';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['name']=$LANGTRACKER["errors"][1];
	
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][2]['table']='glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][2]['field']='ID';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][2]['linkfield']='ID';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][2]['name']=$LANG["common"][2];	
	
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][3]['table']='glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][3]['field']='device_type';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][3]['linkfield']='device_type';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][3]['name']=$LANG["common"][1];
	
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][4]['table']='glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][4]['field']='device_id';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][4]['linkfield']='device_id';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][4]['name']=$LANG["common"][16];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['table']='glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['field']='description';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['linkfield']='description';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['name']=$LANGTRACKER["errors"][2];
	
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['table']='glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['field']='first_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['linkfield']='first_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['name']=$LANGTRACKER["errors"][3];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['table']='glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['field']='last_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['linkfield']='last_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['name']=$LANGTRACKER["errors"][4];
	
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['table']='glpi_entities';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['field']='completename';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['linkfield']='FK_entities';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['name']=$LANG["entity"][0];
	

	$sopt[PLUGIN_TRACKER_MODEL]['common']=$LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_MODEL][1]['table']='glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][1]['field']='ID';
	$sopt[PLUGIN_TRACKER_MODEL][1]['linkfield']='ID';
	$sopt[PLUGIN_TRACKER_MODEL][1]['name']=$LANG["common"][2];	

	$sopt[PLUGIN_TRACKER_MODEL][2]['table']='glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][2]['field']='name';
	$sopt[PLUGIN_TRACKER_MODEL][2]['linkfield']='name';
	$sopt[PLUGIN_TRACKER_MODEL][2]['name']=$LANG["common"][16];	

	$sopt[PLUGIN_TRACKER_MODEL][3]['table']='glpi_dropdown_model_networking';
	$sopt[PLUGIN_TRACKER_MODEL][3]['field']='name';
	$sopt[PLUGIN_TRACKER_MODEL][3]['linkfield']='FK_model_networking';
	$sopt[PLUGIN_TRACKER_MODEL][3]['name']=$LANG["common"][22];	

	$sopt[PLUGIN_TRACKER_MODEL][4]['table']='glpi_dropdown_firmware';
	$sopt[PLUGIN_TRACKER_MODEL][4]['field']='name';
	$sopt[PLUGIN_TRACKER_MODEL][4]['linkfield']='FK_firmware';
	$sopt[PLUGIN_TRACKER_MODEL][4]['name']=$LANG["networking"][49];	


	$sopt[PLUGIN_TRACKER_SNMP_AUTH]['common']=$LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['table']='glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['field']='ID';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['linkfield']='ID';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['name']=$LANG["common"][2];
	
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][2]['table']='glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][2]['field']='name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][2]['linkfield']='name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][2]['name']=$LANG["common"][16];
	
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['table']='glpi_dropdown_plugin_tracker_snmp_version';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['field']='name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['linkfield']='FK_snmp_version';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['name']=$LANGTRACKER["model_info"][2];
	
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['table']='glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['field']='community';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['linkfield']='community';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['name']=$LANGTRACKER["snmpauth"][1];
	
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['table']='glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['field']='sec_name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['linkfield']='sec_name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['name']=$LANGTRACKER["snmpauth"][2];
	
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][6]['table']='glpi_dropdown_plugin_tracker_snmp_auth_sec_level';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][6]['field']='name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][6]['linkfield']='sec_level';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][6]['name']=$LANGTRACKER["snmpauth"][3];
	
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['table']='glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['field']='name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['linkfield']='auth_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['name']=$LANGTRACKER["snmpauth"][4];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['table']='glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['field']='auth_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['linkfield']='auth_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['name']=$LANGTRACKER["snmpauth"][5];
	
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['table']='glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['field']='name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['linkfield']='priv_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['name']=$LANGTRACKER["snmpauth"][6];
	
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['table']='glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['field']='priv_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['linkfield']='priv_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['name']=$LANGTRACKER["snmpauth"][7];



	
	
	return $sopt;
}

function plugin_tracker_giveItem($type,$field,$data,$num,$linkfield=""){
	global $CFG_GLPI, $INFOFORM_PAGES, $LANGTRACKER;

	switch ($field){
		case "glpi_plugin_tracker_model_infos.name" :
			$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/" . $INFOFORM_PAGES[$type] . "?ID=" . $data['ID'] . "\">";
			$out .= $data["ITEM_$num"];
			if ($CFG_GLPI["view_ID"] || empty ($data["ITEM_$num"]))
				$out .= " (" . $data["ID"] . ")";
			$out .= "</a>";
			return $out;
			break;
		case "glpi_plugin_tracker_model_infos.FK_model_networking" :
			$out=getDropdownName("glpi_dropdown_networking",$data["ITEM_$num"], 0);
			return $out;
		case "glpi_plugin_tracker_model_infos.FK_firmware" :
			$out=getDropdownName("glpi_dropdown_firmware",$data["ITEM_$num"], 0);
			return $out;
		case "glpi_dropdown_plugin_tracker_snmp_version.FK_snmp_version" :
			$out=getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$data["ITEM_$num"], 0);
			return $out;
		case "glpi_plugin_tracker_snmp_connection.FK_snmp_connection" :
			$out=getDropdownName("glpi_plugin_tracker_snmp_connection",$data["ITEM_$num"], 0);
			return $out;
		case "glpi_plugin_tracker_errors.device_type":
			switch($data["ITEM_$num"]) {
				case COMPUTER_TYPE:
					$out = $LANGTRACKER["type"][1];
					break;
				case NETWORKING_TYPE:
					$out = $LANGTRACKER["type"][2];
					break;
				case PRINTER_TYPE:
					$out = $LANGTRACKER["type"][3];
					break;
			}
			return $out;
			break;
		case "glpi_plugin_tracker_snmp_connection.name" :
			$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/" . $INFOFORM_PAGES[$type] . "?ID=" . $data['ID'] . "\">";
			$out .= $data["ITEM_$num"];
			if ($CFG_GLPI["view_ID"] || empty ($data["ITEM_$num"]))
				$out .= " (" . $data["ID"] . ")";
			$out .= "</a>";
			return $out;
			break;		
			
		case "glpi_plugin_tracker_errors.device_id":
			$device_type = $data["ITEM_1"];
			$ID = $data["ITEM_$num"];
			$name = plugin_tracker_getDeviceFieldFromId($device_type, $ID, "name", NULL);
			
			$out = "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES["$device_type"]."?ID=".$ID."\">";
			$out .= $name;
			if (empty($name) || $CFG_GLPI["view_ID"])
				$out .= " ($ID)";
			$out .= "</a>";
			return $out;
			break;

		case "glpi_plugin_tracker_errors.first_pb_date":
			$out = convDateTime($data["ITEM_$num"]);
			return $out;
			break;
			
		case "glpi_plugin_tracker_errors.last_pb_date":
			$out = convDateTime($data["ITEM_$num"]);
			return $out;
			break;
	}
	return "";
}
// Define Dropdown tables to be manage in GLPI :
function plugin_tracker_getDropdown(){
        // Table => Name
        global $LANGTRACKER;
        if (isset($_SESSION["glpi_plugin_tracker_installed"]) && $_SESSION["glpi_plugin_tracker_installed"]==1)
                return array("glpi_dropdown_plugin_tracker_snmp_version"=>"SNMP version",
                                                "glpi_dropdown_plugin_tracker_mib_oid"=>"OID MIB",
                                                "glpi_dropdown_plugin_tracker_mib_object"=>"Objet MIB",
                                                "glpi_dropdown_plugin_tracker_mib_label"=>"Label MIB");
        else
                return array();

}

/* Cron for cleaning and printing counters */
function cron_plugin_tracker() {
	plugin_tracker_printingCounters();
	plugin_tracker_cleaningHistory();
}

function plugin_get_headings_tracker($type,$withtemplate){	

	global $LANGTRACKER;
	$config = new plugin_tracker_config();

	switch ($type){

		case COMPUTER_TYPE :
			// template case
			if ($withtemplate)
				return array();
			// Non template case
			else {
				$array = array();
				
				if ( (plugin_tracker_haveRight("computers_history","r")) && (($config->isActivated('computers_history')) == true) ) {
					$array = array(1 => $LANGTRACKER["title"][2]);
				}
				
				if (plugin_tracker_haveRight("errors","r")) {
					$array = array_merge($array, array(1 => $LANGTRACKER["title"][3]));
				}
				
				return $array;
			}
			
			break;
			
		case PRINTER_TYPE :
			// template case
			if ($withtemplate)
				return array();
			// Non template case
			else {
				$array = array();
				
				if (plugin_tracker_haveRight("printers_info","r")) {
					$array = array(1 => $LANGTRACKER["title"][1]);
				}
							
				if ( (plugin_tracker_haveRight("printers_history","r"))  && (($config->isActivated('counters_statement')) == true) ) {
					$array = array_merge($array, array(1 => $LANGTRACKER["title"][2]));
				}					
				
				if (plugin_tracker_haveRight("errors","r"))	{
					$array = array_merge($array, array(1 => $LANGTRACKER["title"][3]));
				}
				
				if ( (plugin_tracker_haveRight("printers_history","w"))  && (($config->isActivated('counters_statement')) == true) )	{
					$array = array_merge($array, array(1 => $LANGTRACKER["title"][4]));
				}
				
				return $array;
			}
			
			break;
			
		case NETWORKING_TYPE :
			// template case
			if ($withtemplate)
				return array();
			// Non template case
			else {
				if (plugin_tracker_haveRight("networking_info","r")) {
					$array = array(1 => $LANGTRACKER["title"][1]);
				}

				if (plugin_tracker_haveRight("errors","r"))	{
					$array = array_merge($array, array(1 => $LANGTRACKER["title"][3]));
				}	

				return $array;
			}
			
			break;
			
		case USER_TYPE :
			// template case
			if ($withtemplate)
				return array();
			// Non template case
			else {
				if ( (plugin_tracker_haveRight("computers_history","r")) && (($config->isActivated('computers_history')) == true) ) {
					return array(
							1 => $LANGTRACKER["title"][2]
					 	   	);
				}
			}

			break;
			
	}
	return false;
}

// Define headings actions added by the plugin	 
function plugin_headings_actions_tracker($type){
	
	$config = new plugin_tracker_config();

	switch ($type){
		case COMPUTER_TYPE :
			
			$array = array();
			
			if ( (plugin_tracker_haveRight("computers_history","r")) && (($config->isActivated('computers_history')) == true) ) {
				$array = array(1 => "plugin_headings_tracker_computerHistory");
			}
			
			if (plugin_tracker_haveRight("errors","r")) {
				$array = array_merge($array, array(1 => "plugin_headings_tracker_computerErrors"));
			}
			
			return $array;

			break;

		case PRINTER_TYPE :
		
			$array = array();
			
			if (plugin_tracker_haveRight("printers_info","r")) {
				$array = array(1 => "plugin_headings_tracker_printerInfo");
			}
						
			if ( (plugin_tracker_haveRight("printers_history","r")) && (($config->isActivated('counters_statement')) == true) ) {
				$array = array_merge($array, array(1 => "plugin_headings_tracker_printerHistory"));
			}					
			
			if (plugin_tracker_haveRight("errors","r"))	{
				$array = array_merge($array, array(1 => "plugin_headings_tracker_printerErrors"));
			}
				
			if ( (plugin_tracker_haveRight("printers_history","w"))  && (($config->isActivated('counters_statement')) == true) )	{
				$array = array_merge($array, array(1 => "plugin_headings_tracker_printerCronConfig"));
			}
			
			return $array;

			break;	
			
		case NETWORKING_TYPE :
			
			if (plugin_tracker_haveRight("networking_info","r")) {
				$array = array(1 => "plugin_headings_tracker_networkingInfo");
			}
			
			if (plugin_tracker_haveRight("errors","r"))	{
				$array = array_merge($array, array(1 => "plugin_headings_tracker_networkingErrors"));
			}
			
			return $array;
				
			break;
			
		case USER_TYPE :
			
			if ( (plugin_tracker_haveRight("computers_history","r")) && (($config->isActivated('computers_history')) == true) ) {
				return	array(
						 1 => "plugin_headings_tracker_userHistory"
						 );
			}

			break;
			
	}
	return false;
}

function plugin_headings_tracker_computerHistory($type,$ID){

	$computer_history = new plugin_tracker_computers_history();
	$computer_history->showForm(COMPUTER_TYPE, GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.computer_history.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_computerErrors($type,$ID){

	$errors = new plugin_tracker_errors();
	$errors->showForm(COMPUTER_TYPE, GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.errors.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerInfo($type,$ID){

	$snmp = new plugin_tracker_printer_snmp();
	$snmp->showForm(GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.printer_info.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerHistory($type,$ID){

	$print_history = new plugin_tracker_printers_history();
	$print_history->showForm(GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.printer_history.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerErrors($type,$ID){

	$errors = new plugin_tracker_errors();
	$errors->showForm(PRINTER_TYPE, GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.errors.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerCronConfig($type,$ID){
	
	$print_config = new glpi_plugin_tracker_printers_history_config();
	$print_config->showForm(GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.printer_history_config.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_networkingInfo($type,$ID){

	$snmp = new plugin_tracker_switch_snmp();
	$snmp->showForm(GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.switch_info.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_networkingErrors($type,$ID){

	$errors = new plugin_tracker_errors();
	$errors->showForm(NETWORKING_TYPE, GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.errors.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_userHistory($type,$ID){

	$computer_history = new plugin_tracker_computers_history();
	$computer_history->showForm(USER_TYPE, GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.computer_history.form.php', $_GET["ID"]);
}







?>