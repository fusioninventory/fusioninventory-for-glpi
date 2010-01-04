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

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

// Installation function
function plugin_tracker_installing($version) {
	global $DB,$LANG;

	$DB_file = GLPI_ROOT ."/plugins/tracker/install/mysql/plugin_tracker-".$version."-empty.sql";
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
	
	$config = new PluginTrackerConfig;
	$config->initConfig($version);
	$config_modules = new PluginTrackerConfigModules;
	$config_modules->initConfig();
	$config_snmp_networking = new PluginTrackerConfigSNMPNetworking;
	$config_snmp_networking->initConfig();
	// Import models
	$importexport = new PluginTrackerImportExport;
	include(GLPI_ROOT.'/inc/setup.function.php');
	include(GLPI_ROOT.'/inc/rulesengine.function.php');
	foreach (glob(GLPI_ROOT.'/plugins/tracker/models/*.xml') as $file) $importexport->import($file,0,1);
	
	plugin_tracker_initSession();
   return true;
}


function plugin_tracker_update($version) {
	global $DB;

   if (file_exists(GLPI_ROOT ."/plugins/tracker/install/mysql/plugin_tracker-".$version."-update.sql")) {
   	$DB_file = GLPI_ROOT ."/plugins/tracker/install/mysql/plugin_tracker-".$version."-update.sql";
   	$DBf_handle = fopen($DB_file, "rt");
   	$sql_query = fread($DBf_handle, filesize($DB_file));
   	fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) {
            $DB->query($sql_line);
         }
      }
   }
	if ($version == "2.0.0") {
		if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/tracker')) {
			mkdir(GLPI_PLUGIN_DOC_DIR.'/tracker');
		}
		$config_discovery = new PluginTrackerConfigDiscovery;
		$config_discovery->initConfig();
		$config_snmp_script = new PluginTrackerConfigSNMPScript;
		$config_snmp_script->initConfig();
		// Import models
		$importexport = new PluginTrackerImportExport;
		foreach (glob(GLPI_ROOT.'/plugins/tracker/models/*.xml') as $file) $importexport->import($file,0);
		// Clean DB (ports in glpi_plugin_tracker_networking_ports..... )
		plugin_tracker_clean_db();
	}
	if ($version == "2.0.2") {
		// Migrate unknown mac address in unknown device (MySQL table)
		$ptud = new PluginTrackerUnknownDevice;
		$ptud->updateFromOldVersion_unknown_mac;
		// Delete MySQL table "glpi_plugin_tracker_unknown_mac"
		$DB->query("DROP TABLE `glpi_plugin_tracker_unknown_mac`;");
		$DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.0.2'
                  WHERE `ID`='1'
                  LIMIT 1 ;");
	}
   if ($version == "2.1.0") {
      $DB->query("UPDATE `glpi_plugin_tracker_networking`
                  SET `last_PID_update` = '0';");
      $DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.1.0'
                  WHERE `ID`='1'
                  LIMIT 1 ;");
   }
   if ($version == "2.1.1") {
      $DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.1.1'
                  WHERE `ID`=1
                  LIMIT 1 ;");
   }
   if ($version == "2.1.2") {
      $DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.1.3'
                  WHERE `ID`=1
                  LIMIT 1 ;");
   }
   if ($version == "2.1.3") {
      $DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.2.0'
                  WHERE `ID`=1
                  LIMIT 1 ;");
      $pthc = new PluginTrackerHistoryConnections;
      $pthc->migration();
   }
	plugin_tracker_initSession();
}


// Uninstallation function
function plugin_tracker_uninstall() {
   global $DB;

	if($dir = @opendir(GLPI_PLUGIN_DOC_DIR.'/tracker')) {
      $current_dir = GLPI_PLUGIN_DOC_DIR.'/tracker/';
		while (($f = readdir($dir)) !== false) {
			if($f > '0' and filetype($current_dir.$f) == "file") {
				unlink($current_dir.$f);
			} else if ($f > '0' and filetype($current_dir.$f) == "dir") {
				remove_dir($current_dir.$f."\\");
			}
		}
		closedir($dir);
		rmdir($current_dir);
	}

	$query = "SHOW TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if ((strstr($data[0],"glpi_dropdown_plugin_tracker_"))
         OR (strstr($data[0],"glpi_plugin_tracker_"))){
         $query_delete = "DROP TABLE `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }

	$query="DELETE FROM `glpi_display`
           WHERE `type`='".PLUGIN_TRACKER_ERROR_TYPE."'
                 OR `type`='".PLUGIN_TRACKER_MODEL."'
                 OR `type`='".PLUGIN_TRACKER_SNMP_AUTH."'
                 OR `type`='".PLUGIN_TRACKER_MAC_UNKNOWN."'
                 OR `type`='".PLUGIN_TRACKER_PRINTERS_CARTRIDGES."'
                 OR `type`='".PLUGIN_TRACKER_SNMP_NETWORKING_PORTS."'
                 OR `type`='".PLUGIN_TRACKER_SNMP_AGENTS."'
                 OR `type`='".PLUGIN_TRACKER_SNMP_RANGEIP."'
                 OR `type`='".PLUGIN_TRACKER_SNMP_DISCOVERY."'
                 OR `type`='".PLUGIN_TRACKER_AGENTS_PROCESSES."'
                 OR `type`='".PLUGIN_TRACKER_SNMP_HISTORY."'
                 OR `type`='".PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2."' ;";
	$DB->query($query) or die($DB->error());

}

?>