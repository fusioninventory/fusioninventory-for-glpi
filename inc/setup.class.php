<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: MAZZONI Vincent
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusioninventorySetup {
   // Installation function
   static function install($version) {
      global $DB,$LANG;

      $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
      }

      PluginFusioninventoryDb::createfirstaccess($_SESSION['glpiactiveprofile']['id']);
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
      }

      $config = new PluginFusioninventoryConfig;
      $config->initConfig($version);
      $config_modules = new PluginFusioninventoryConfigModules;
      $config_modules->initConfig();
      $config_snmp_networking = new PluginFusioninventoryConfigSNMPNetworking;
      $config_snmp_networking->initConfig();
      $config_history = new PluginFusioninventoryConfigSNMPHistory;
      $config_history->initConfig();

      // Import models
      $importexport = new PluginFusioninventoryImportExport;
      include(GLPI_ROOT.'/inc/setup.function.php');
      include(GLPI_ROOT.'/inc/rulesengine.function.php');
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);

      PluginFusioninventory::initSession();
      return true;
   }

   static function update($version) {
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
   //		$config_discovery = new PluginFusioninventoryConfigDiscovery;
   //		$config_discovery->initConfig();
   //		$config_snmp_script = new PluginFusioninventoryConfigSNMPScript;
   //		$config_snmp_script->initConfig();
         // Import models
   //		$importexport = new PluginFusioninventoryImportExport;
   //		foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0);
         // Clean DB (ports in glpi_plugin_fusioninventory_networkports..... )

      }
      if ($version == "2.0.2") {
         // Delete MySQL table "glpi_plugin_fusioninventory_unknown_mac"
         $DB->query("DROP TABLE `glpi_plugin_tracker_unknown_mac`;");
         $DB->query("UPDATE `glpi_plugin_tracker_config`
                     SET `version` = '2.0.2'
                     WHERE `id`='1'
                     LIMIT 1 ;");
      }
      if ($version == "2.1.0") {
         $DB->query("UPDATE `glpi_plugin_tracker_networking`
                     SET `last_PID_update` = '0';");
         $DB->query("UPDATE `glpi_plugin_tracker_config`
                     SET `version` = '2.1.0'
                     WHERE `id`='1'
                     LIMIT 1 ;");
      }
      if ($version == "2.1.1") {
         $DB->query("UPDATE `glpi_plugin_tracker_config`
                     SET `version` = '2.1.1'
                     WHERE `id`=1
                     LIMIT 1 ;");
      }
      if ($version == "2.1.2") {
         $DB->query("UPDATE `glpi_plugin_tracker_config`
                     SET `version` = '2.1.3'
                     WHERE `id`=1
                     LIMIT 1 ;");
         //PluginFusioninventoryDb::clean_db();
      }
      if ($version == "2.1.3") {
         $DB->query("UPDATE `glpi_plugin_tracker_config`
                     SET `version` = '2.2.0'
                     WHERE `id`=1
                     LIMIT 1 ;");
         ini_set("memory_limit","-1");
         ini_set("max_execution_time", "0");
         $pthc = new PluginFusioninventorySnmphistoryconnection;
         $pthc->migration();
         PluginFusioninventoryDb::clean_db();
      }
      if ($version == "2.2.0") {
         ini_set("memory_limit", "-1");
         ini_set("max_execution_time", "0");

         $config_modules = new PluginFusioninventoryConfigModules;
         $config_modules->initConfig();
         if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
            mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
         }
         if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
            mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
         }
         // Update right
         PluginFusioninventory::updateaccess($_SESSION['glpiactiveprofile']['id']);

         // Delete old agents
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_agents`";
         $DB->query($query_delete);

         // Delete models
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_modelinfos`";
         $DB->query($query_delete);

         // Import models
         $importexport = new PluginFusioninventoryImportExport;
         include(GLPI_ROOT.'/inc/setup.function.php');
         include(GLPI_ROOT.'/inc/rulesengine.function.php');
         foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);

         // Update ports history from lang traduction into field constant (MySQL fiel 'Field')
         $pfisnmph = new PluginFusioninventorySnmphistory;
         $pfisnmph->ConvertField();

         // Delete all values in glpi_plugin_fusioninventory_config_snmp_history
         $pficsnmph = new PluginFusioninventoryConfigSNMPHistory;
         $pficsnmph->initConfig();
         $pficsnmph->updateTrackertoFusion();
      }
      PluginFusioninventory::initSession();
   }

   // Uninstallation function
   static function uninstall() {
      global $DB;

      $np = new Networkport;

      if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         if($dir = @opendir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
            $current_dir = GLPI_PLUGIN_DOC_DIR.'/fusioninventory/';
            while (($f = readdir($dir)) !== false) {
               if($f > '0' and filetype($current_dir.$f) == "file") {
                  unlink($current_dir.$f);
               } else if ($f > '0' and filetype($current_dir.$f) == "dir") {
                  PluginFusioninventorySetup::delTree($current_dir.$f);
               }
            }
            closedir($dir);
            rmdir($current_dir);
         }
      }

      $query = "SHOW TABLES;";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0],"glpi_plugin_fusioninventory_")){
            $query_delete = "DROP TABLE `".$data[0]."`;";
            $DB->query($query_delete) or die($DB->error());
         }
      }

      $query="DELETE FROM `glpi_displaypreferences`
              WHERE `type`='PluginFusioninventoryError'
                    OR `type`='PluginFusioninventoryModelinfo'
                    OR `type`='PluginFusioninventorySnmpauth'
                    OR `type`='PluginFusioninventoryUnknowndevice'
                    OR `type`='".PLUGIN_FUSIONINVENTORY_PRINTERS_CARTRIDGES."'
                    OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS."'
                    OR `type`='PluginFusioninventoryAgent'
                    OR `type`='PluginFusioninventoryIprange'
                    OR `type`='PluginFusioninventoryAgentsProcess'
                    OR `type`='PluginFusioninventorySnmphistory'
                    OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2."'
                    OR `type`='PluginFusioninventoryConfig'
                    OR `type`='PluginFusioninventoryTask'
                    OR `type`='PluginFusioninventoryConstructDevices' ;";
      $DB->query($query) or die($DB->error());


      $a_netports = $np->find("`itemtype`='PluginFusioninventoryUnknowndevice' ");
      foreach ($a_netports as $Networkport){
         $np->cleanDBonPurge($Networkport['id']);
         $np->deleteFromDB($Networkport['id']);
      }

      return true;
   }

   static function delTree($dir) {
       $files = glob( $dir . '*', GLOB_MARK );
       foreach( $files as $file ){
           if( is_dir( $file ) )
               PluginFusioninventorySetup::delTree( $file );
           else
               unlink( $file );
       }

       if (is_dir($dir)) rmdir( $dir );
   }
}

?>
