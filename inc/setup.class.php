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
      $configLogField = new PluginFusioninventoryConfigLogField();
      $configLogField->initConfig();

      // Import models
      $importexport = new PluginFusioninventoryImportExport;
//      include(GLPI_ROOT.'/inc/setup.function.php');
//      include(GLPI_ROOT.'/inc/rulesengine.function.php');
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);

      PluginFusioninventoryAuth::initSession();
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
         $pthc = new PluginFusioninventoryNetworkPortConnectionLog;
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
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_snmpmodels`";
         $DB->query($query_delete);

         // Import models
         $importexport = new PluginFusioninventoryImportExport;
//         include_once(GLPI_ROOT.'/inc/setup.function.php');
//         include_once(GLPI_ROOT.'/inc/rulesengine.function.php');
         foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);

         // Update ports history from lang traduction into field constant (MySQL fiel 'Field')
         $pfisnmph = new PluginFusioninventoryNetworkPortLog;
         $pfisnmph->ConvertField();

         // Delete all values in glpi_plugin_fusioninventory_configlogfields
         $pficlf = new PluginFusioninventoryConfigLogField();
         $pficlf->initConfig();
         $pficlf->updateTrackertoFusion();
         // Delete all ports present in fusion but deleted in glpi_networking
         $query = "SELECT `glpi_plugin_fusioninventory_networkports`.`id` AS `fusinvId`
                   FROM `glpi_plugin_fusioninventory_networkports`
                        LEFT JOIN `glpi_networkports`
                           ON `networkports_id`=`glpi_networkports`.`id`
                   WHERE `glpi_networkports`.`id` IS NULL";
         if ($result=$DB->query($query)) {
            while ($data=$DB->fetch_array($result)) {
               $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networkports`
                  WHERE `id`='".$data['fusinvId']."' ";
               $DB->query($query_delete);
            }
         }
         // Add IP of switch in table glpi_plugin_fusioninventory_networkequimentips if not present
         $query = "SELECT * FROM glpi_networkequipments";
         if ($result=$DB->query($query)) {
            while ($data=$DB->fetch_array($result)) {
               $query_ip = "SELECT * FROM `glpi_plugin_fusioninventory_networkequipmentips`
                  WHERE `ip`='".$data['ip']."' ";
               $result_ip = $DB->query($query_ip);
               if ($DB->numrows($result_ip) == "0") {
                  $query_add = "INSERT INTO `glpi_plugin_fusioninventory_networkequipmentips`
                     (`networkequipments_id`, `ip`) VALUES ('".$data['id']."', '".$data['ip']."')";
                  $DB->query($query_add);
               }
            }
         }
      }
      if ($version == "2.2.1") {
         // Clean fusion IP when networkequipments_id has been deleted
         // (bug from Tracker 2.1.3 and before)
         $query = "SELECT `glpi_plugin_fusioninventory_networking_ifaddr`.*
                   FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                        LEFT JOIN `glpi_networkequipments`
                           ON `FK_networking`=`glpi_networkequipments`.`id`
                   WHERE `glpi_networkequipments`.`id` is null";
         if ($result=$DB->query($query)) {
            while ($data=$DB->fetch_array($result)) {
               $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                                WHERE `id`='".$data['id']."' ";
               $DB->query($query_delete);
            }
         }
         // delete when IP not valid (bug from Tracker 2.1.3 and before)
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_networking_ifaddr`";
         if ($result=$DB->query($query)) {
            while ($data=$DB->fetch_array($result)) {
               if (!preg_match("/^((25[0-5]|2[0-4]\d|1?\d?\d).){3}(25[0-5]|2[0-4]\d|1?\d?\d)$/",$data['ip'])) {
                  $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                                   WHERE id='".$data['id']."' ";
                  $DB->query($query_delete);
               }
            }
         }
         // locations with entity -1 (bad code)
         $query = "DELETE FROM `glpi_locations`
                   WHERE `entities_id`='-1' ";
         $DB->query($query);
         //CLean glpi_display
         $query = "DELETE FROM `glpi_displaypreferences`
                   WHERE `itemtype`='PluginFusioninventorySnmpModel'
                         AND `num` NOT IN (1, 30, 3, 5, 6, 7, 8)";
         $DB->query($query);
         $query = "DELETE FROM `glpi_displaypreferences`
                   WHERE `itemtype`='PluginFusioninventoryConfigSnmpSecurity'
                         AND `num` NOT IN (1, 30, 3, 4, 5, 7, 8, 9, 10)";
         $DB->query($query);
         $query = "DELETE FROM `glpi_displaypreferences`
                   WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
                         AND `num` NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19)";
         $DB->query($query);
//         $query = "DELETE FROM `glpi_displaypreferences`
//                   WHERE `itemtype`='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS."'
//                         AND `num` NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15)";
//         $DB->query($query);
         $query = "DELETE FROM `glpi_displaypreferences`
                   WHERE `itemtype`='PluginFusioninventoryAgent'
                         AND `num` NOT IN (1, 30, 4, 6, 8, 9, 10, 11, 12, 13, 14, 15)";
         $DB->query($query);
         $query = "DELETE FROM `glpi_displaypreferences`
                   WHERE `itemtype`='PluginFusioninventoryIPRange'
                         AND `num` NOT IN (1, 2, 3, 30, 5, 6, 7, 8, 9)";
         $DB->query($query);
         $query = "DELETE FROM `glpi_displaypreferences`
                   WHERE `itemtype`='PluginFusioninventoryNetworkPortLog'
                         AND `num` NOT IN (1, 2, 3, 4, 5, 6)";
         $DB->query($query);
         $query = "DELETE FROM `glpi_displaypreferences`
                   WHERE `itemtype`='PluginFusioninventoryNetworkPort'
                         AND `num` NOT IN (30, 1, 2, 3)";
         $DB->query($query);
      }
      // Remote IP of switch ports
      $query = "UPDATE `glpi_networkports`
                SET `ip` = NULL
                WHERE `itemtype` ='NetworkEquipment'
                   AND `ip` IS NOT NULL ";
      $DB->query($query);

      if ($version == "2.3.0") {
         //TODO
//         Plugin::migrateItemType();

      }
      PluginFusioninventoryAuth::initSession();
   }

   // Uninstallation function
   static function uninstall() {
      global $DB;

      $np = new NetworkPort;

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
              WHERE `itemtype`='PluginFusioninventoryError'
                    OR `itemtype`='PluginFusioninventorySNMPModel'
                    OR `itemtype`='PluginFusioninventoryConfigSNMPSecurity'
                    OR `itemtype`='PluginFusioninventoryUnknownDevice'
                    OR `itemtype`='PluginFusioninventoryNetworkPort'
                    OR `itemtype`='PluginFusioninventoryNetworkport2'
                    OR `itemtype`='PluginFusioninventoryAgent'
                    OR `itemtype`='PluginFusioninventoryIPRange'
                    OR `itemtype`='PluginFusioninventoryAgentProcess'
                    OR `itemtype`='PluginFusioninventoryNetworkPortLog'
                    OR `itemtype`='PluginFusioninventoryConfig'
                    OR `itemtype`='PluginFusioninventoryTask'
                    OR `itemtype`='PluginFusioninventoryConstructDevices' ;";
      $DB->query($query) or die($DB->error());


      $a_netports = $np->find("`itemtype`='PluginFusioninventoryUnknownDevice' ");
      foreach ($a_netports as $NetworkPort){
         $np->cleanDBonPurge($NetworkPort['id']);
         $np->deleteFromDB($NetworkPort['id']);
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
