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

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}

// Installation function
function plugin_tracker_installing($version) {

	global $DB;

	$DB_file = GLPI_ROOT ."/plugins/tracker/inc/plugin_tracker-".$version."-empty.sql";
	$DBf_handle = fopen($DB_file, "rt");
	$sql_query = fread($DBf_handle, filesize($DB_file));
	fclose($DBf_handle);
	foreach ( explode(";\n", "$sql_query") as $sql_line) {
		if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
		if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
	}

	cleanCache("GLPI_HEADER_".$_SESSION["glpiID"]);
	plugin_tracker_createfirstaccess($_SESSION['glpiactiveprofile']['ID']);
	if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/tracker')) {
		mkdir(GLPI_PLUGIN_DOC_DIR.'/tracker');
	}
	
	$config = new plugin_tracker_config;
	$config->initConfig();
	$config_discovery = new plugin_tracker_config_discovery;
	$config_discovery->initConfig();
	$config_snmp_networking = new plugin_tracker_config_snmp_networking;
	$config_snmp_networking->initConfig();
	$config_snmp_printer = new plugin_tracker_config_snmp_printer;
	$config_snmp_printer->initConfig();
	$config_snmp_script = new glpi_plugin_tracker_config_snmp_script;
	$config_snmp_script->initConfig();
	// Import models
	$importexport = new plugin_tracker_importexport;
	foreach (glob(GLPI_ROOT.'/plugins/tracker/models/*.xml') as $file) $importexport->import($file,0,1);
	
	plugin_tracker_initSession();
   return true;
}


function plugin_tracker_update($version) {
	
	GLOBAL $DB;
	
	$DB_file = GLPI_ROOT ."/plugins/tracker/inc/plugin_tracker-".$version."-update.sql";
	$DBf_handle = fopen($DB_file, "rt");
	$sql_query = fread($DBf_handle, filesize($DB_file));
	fclose($DBf_handle);
	foreach ( explode(";\n", "$sql_query") as $sql_line) {
		if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
		$DB->query($sql_line);
	}
	if ($version == "2.0.0")
	{
		if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/tracker')) {
			mkdir(GLPI_PLUGIN_DOC_DIR.'/tracker');
		}
		$config_discovery = new plugin_tracker_config_discovery;
		$config_discovery->initConfig();
		$config_snmp_script = new glpi_plugin_tracker_config_snmp_script;
		$config_snmp_script->initConfig();
		// Import models
		$importexport = new plugin_tracker_importexport;
		foreach (glob(GLPI_ROOT.'/plugins/tracker/models/*.xml') as $file) $importexport->import($file,0);
		// Clean DB (ports in glpi_plugin_tracker_networking_ports..... )
		plugin_tracker_clean_db();
	}

	plugin_tracker_initSession();
}


// Uninstallation function
function plugin_tracker_uninstall() {
	
	global $DB;

	if (is_dir(GLPI_PLUGIN_DOC_DIR.'/tracker')) {
		rmdir(GLPI_PLUGIN_DOC_DIR.'/tracker');
	}
	
	$query = "DROP TABLE `glpi_dropdown_plugin_tracker_mib_label`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_dropdown_plugin_tracker_mib_object`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_dropdown_plugin_tracker_mib_oid`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_dropdown_plugin_tracker_snmp_auth_sec_level`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_dropdown_plugin_tracker_snmp_version`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_agents`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_agents_processes`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_computers`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_config`;";
	$DB->query($query) or die($DB->error());

	$query = "DROP TABLE `glpi_plugin_tracker_config_discovery`;";
	$DB->query($query) or die($DB->error());

	$query = "DROP TABLE `glpi_plugin_tracker_config_snmp_networking`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_config_snmp_printer`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_config_snmp_script`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_connection_history`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_connection_stats`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_discovery`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_errors`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_mib_networking`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_model_infos`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_networking`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_networking_ifaddr`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_networking_ports`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_printers`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_printers_cartridges`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_printers_history`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_processes`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_processes_values`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_profiles`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_rangeip`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_snmp_connection`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_snmp_history`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_unknown_mac`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_tracker_walks`;";
	$DB->query($query) or die($DB->error());
	

	
	$query="DELETE FROM glpi_display 
	WHERE type='".PLUGIN_TRACKER_ERROR_TYPE."' 
		OR type='".PLUGIN_TRACKER_MODEL."' 
		OR type='".PLUGIN_TRACKER_SNMP_AUTH."' 
		OR type='".PLUGIN_TRACKER_MAC_UNKNOWN."'
		OR type='".PLUGIN_TRACKER_PRINTERS_CARTRIDGES."' 
		OR type='".PLUGIN_TRACKER_SNMP_NETWORKING_PORTS."' 
		OR type='".PLUGIN_TRACKER_SNMP_AGENTS."' 
		OR type='".PLUGIN_TRACKER_SNMP_RANGEIP."' 
		OR type='".PLUGIN_TRACKER_SNMP_DISCOVERY."' 
		OR type='".PLUGIN_TRACKER_AGENTS_PROCESSES."' 
		OR type='".PLUGIN_TRACKER_SNMP_HISTORY."' 
		OR type='".PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2."' ;";
	$DB->query($query) or die($DB->error());

}

?>