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
function plugin_fusioninventory_installing($version) {
	global $DB,$LANG;

	$DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-empty.sql";
	$DBf_handle = fopen($DB_file, "rt");
	$sql_query = fread($DBf_handle, filesize($DB_file));
	fclose($DBf_handle);
	foreach ( explode(";\n", "$sql_query") as $sql_line) {
		if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
		if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
	}

	cleanCache("GLPI_HEADER_".$_SESSION["glpiID"]);
	plugin_fusioninventory_createfirstaccess($_SESSION['glpiactiveprofile']['ID']);
	if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
		mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
	}
	
	$config = new PluginFusionInventoryConfig;
	$config->initConfig($version);
	$config_modules = new PluginFusionInventoryConfigModules;
	$config_modules->initConfig();
	$config_snmp_networking = new PluginFusionInventoryConfigSNMPNetworking;
	$config_snmp_networking->initConfig();
   $config_history = new PluginFusionInventoryConfigSNMPHistory;
   $config_history->initConfig();

	// Import models
	$importexport = new PluginFusionInventoryImportExport;
	include(GLPI_ROOT.'/inc/setup.function.php');
	include(GLPI_ROOT.'/inc/rulesengine.function.php');
	foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);
	
	plugin_fusioninventory_initSession();
   return true;
}


function plugin_fusioninventory_update($version) {
	global $DB,$LANG;

   if ((file_exists(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-update.sql"))
         OR (file_exists(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_tracker-".$version."-update.sql"))){
   	if (file_exists(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-update.sql")) {
         $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-update.sql";
      } else if (file_exists(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_tracker-".$version."-update.sql")) {
         $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_tracker-".$version."-update.sql";
      }
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
		if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
			mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
		}
//		$config_discovery = new PluginFusionInventoryConfigDiscovery;
//		$config_discovery->initConfig();
//		$config_snmp_script = new PluginFusionInventoryConfigSNMPScript;
//		$config_snmp_script->initConfig();
		// Import models
//		$importexport = new PluginFusionInventoryImportExport;
//		foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0);
		// Clean DB (ports in glpi_plugin_fusioninventory_networking_ports..... )
		
	}
	if ($version == "2.0.2") {
		// Migrate unknown mac address in unknown device (MySQL table)
//		$ptud = new PluginFusionInventoryUnknownDevice;
//		$ptud->updateFromOldVersion_unknown_mac;
		// Delete MySQL table "glpi_plugin_fusioninventory_unknown_mac"
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
      //plugin_fusioninventory_clean_db();
   }
   if ($version == "2.1.3") {
      $DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.2.0'
                  WHERE `ID`=1
                  LIMIT 1 ;");
      ini_set("memory_limit","-1");
      ini_set("max_execution_time", "0");
      $pthc = new PluginFusionInventoryHistoryConnections;
      $pthc->migration();
      plugin_fusioninventory_clean_db();
   }
   if ($version == "2.2.0") {
      ini_set("memory_limit", "-1");
      ini_set("max_execution_time", "0");
      
      $config_modules = new PluginFusionInventoryConfigModules;
   	$config_modules->initConfig();
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
      }
      // Update right
      plugin_fusioninventory_updateaccess($_SESSION['glpiactiveprofile']['ID']);

      // Delete old agents
      $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_agents`";
      $DB->query($query_delete);

      // Delete models
      $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_model_infos`";
      $DB->query($query_delete);

      // Import models
      $importexport = new PluginFusionInventoryImportExport;
      include(GLPI_ROOT.'/inc/setup.function.php');
      include(GLPI_ROOT.'/inc/rulesengine.function.php');
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);

      // Update ports history from lang traduction into field constant (MySQL fiel 'Field')
      $pfisnmph = new PluginFusionInventorySNMPHistory;
      $pfisnmph->ConvertField();

      // Delete all values in glpi_plugin_fusioninventory_config_snmp_history
      $pficsnmph = new PluginFusionInventoryConfigSNMPHistory;
      $pficsnmph->initConfig();
      $pficsnmph->updateTrackertoFusion();

      // Delete all ports present in fusion but deleted in glpi_networking
      $query = "SELECT glpi_plugin_fusioninventory_networking_ports.ID AS fusinvID FROM `glpi_plugin_fusioninventory_networking_ports`
         LEFT JOIN `glpi_networking_ports` ON FK_networking_ports=glpi_networking_ports.ID
         WHERE glpi_networking_ports.ID IS NULL";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ports`
               WHERE `ID`='".$data['fusinvID']."' ";
            $DB->query($query_delete);

         }
      }

   }
	plugin_fusioninventory_initSession();
}



// Uninstallation function
function plugin_fusioninventory_uninstall() {
   global $DB;

   $np = new Netport;

   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      if($dir = @opendir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         $current_dir = GLPI_PLUGIN_DOC_DIR.'/fusioninventory/';
         while (($f = readdir($dir)) !== false) {
            if($f > '0' and filetype($current_dir.$f) == "file") {
               unlink($current_dir.$f);
            } else if ($f > '0' and filetype($current_dir.$f) == "dir") {
               delTree($current_dir.$f);
            }
         }
         closedir($dir);
         rmdir($current_dir);
      }
   }

	$query = "SHOW TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if ((strstr($data[0],"glpi_dropdown_plugin_fusioninventory_"))
         OR (strstr($data[0],"glpi_plugin_fusioninventory_"))){
         $query_delete = "DROP TABLE `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }

	$query="DELETE FROM `glpi_display`
           WHERE `type`='".PLUGIN_FUSIONINVENTORY_ERROR_TYPE."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_MODEL."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_AUTH."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_PRINTERS_CARTRIDGES."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_AGENTS."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_AGENTS_PROCESSES."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_HISTORY."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_CONFIG."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_TASK."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE."' ;";
	$DB->query($query) or die($DB->error());


   $a_netports = $np->find("`device_type`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."' ");
   foreach ($a_netports as $netport){
      $np->cleanDBonPurge($netport['ID']);
      $np->deleteFromDB($netport['ID']);
   }
}


function delTree($dir) {
    $files = glob( $dir . '*', GLOB_MARK );
    foreach( $files as $file ){
        if( is_dir( $file ) )
            delTree( $file );
        else
            unlink( $file );
    }

    if (is_dir($dir)) rmdir( $dir );

}

?>