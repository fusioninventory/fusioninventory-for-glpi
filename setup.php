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

include_once ("plugin_tracker.includes.php");

// Init the hooks of tracker
function plugin_init_tracker() {
	
	global $PLUGIN_HOOKS,$CFG_GLPI,$LANGTRACKER;

	$config = new plugin_tracker_config();
	
	pluginNewType('tracker', "PLUGIN_TRACKER_ERROR_TYPE", 5150, "plugin_tracker_errors", "glpi_plugin_tracker_errors", "front/plugin_tracker.errors.form.php");
	array_push($CFG_GLPI["specif_entities_tables"],"glpi_plugin_tracker_errors");
	
	$PLUGIN_HOOKS['init_session']['tracker'] = 'plugin_tracker_initSession';
	$PLUGIN_HOOKS['change_profile']['tracker'] = 'plugin_tracker_changeprofile';

	if (isset($_SESSION["glpiID"])){

		if (haveRight("config","w")) {
			// Config page
			$PLUGIN_HOOKS['config_page']['tracker'] = 'front/plugin_tracker.config.php';
		}
	
		if(isset($_SESSION["glpi_plugin_tracker_installed"]) && $_SESSION["glpi_plugin_tracker_installed"]==1) {

			if ( ($config->isActivated('counters_statement')) || ($config->isActivated('cleaning')) )
				$PLUGIN_HOOKS['cron']['tracker'] = DAY_TIMESTAMP;
			
			if (isset($_SESSION["glpi_plugin_tracker_profile"])) {
				
				// Tabs for each type
				$PLUGIN_HOOKS['headings']['tracker'] = 'plugin_get_headings_tracker';
				$PLUGIN_HOOKS['headings_action']['tracker'] = 'plugin_headings_actions_tracker';
			
				if (plugin_tracker_haveRight("errors","r")) {
					$PLUGIN_HOOKS['menu_entry']['tracker'] = true;
//					$PLUGIN_HOOKS['submenu_entry']['tracker']['printers'] = 'front/plugin_tracker.errors.form.php?device=printer';
//					$PLUGIN_HOOKS['submenu_entry']['tracker']['computers'] = 'front/plugin_tracker.errors.form.php?device=computer';
				}
			}
		}
	}
}

// Name and Version of the plugin
function plugin_version_tracker(){

	return array( 'name'    => 'Tracker',
		'minGlpiVersion' => '0.71',
		'maxGlpiVersion' => '0.71.9',
		'version' => '0.1');
}

function plugin_tracker_haveTypeRight($type,$right){
	
	switch ($type){
		case PLUGIN_TRACKER_ERROR_TYPE :
			return plugin_tracker_haveRight("errors",$right);
			break;
	}
}

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
	
	return $sopt;
}

function plugin_tracker_giveItem($type,$field,$data,$num,$linkfield=""){
	global $CFG_GLPI, $INFOFORM_PAGES, $LANGTRACKER;

	switch ($field){

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
			
		case "glpi_plugin_tracker_errors.device_id":
			$device_type = $data["ITEM_1"];
			$out = "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES["$device_type"]."?ID=".$data["ITEM_$num"]."\">";
			$out.= $data["ITEM_$num"];
			$out.= "</a>";
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
					return array(
							1 => $LANGTRACKER["title"][1]
					 	   	);
				}
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
				return array(
						1 => "plugin_headings_tracker_networkingInfo"
						);
			}

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
	$computer_history->showForm(GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.computer_history.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_computerErrors($type,$ID){

	echo "<div align='center'>";
	echo "computerErrors : $ID";
	echo "</div>";
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

	echo "<div align='center'>";
	echo "printerErrors : $ID";
	echo "</div>";
}

function plugin_headings_tracker_printerCronConfig($type,$ID){
	
	$print_config = new glpi_plugin_tracker_printers_history_config();
	$print_config->showForm(GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.printer_history_config.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_networkingInfo($type,$ID){

	$snmp = new plugin_tracker_switch_snmp();
	$snmp->showForm(GLPI_ROOT.'/plugins/tracker/front/plugin_tracker.switch_info.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_userHistory($type,$ID){
	
	echo "<div align='center'>";
	echo "userHistory : $ID";
	echo "</div>";
}

?>
