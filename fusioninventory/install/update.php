<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the termas of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

function pluginFusioninventoryGetCurrentVersion($version) {
   global $DB;
   
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/module.class.php");
   
   if ((!TableExists("glpi_plugin_tracker_config")) &&
      (!TableExists("glpi_plugin_fusioninventory_config")) &&
      (!TableExists("glpi_plugin_fusioninventory_configs"))) {
      return '0';
   } else if ((TableExists("glpi_plugin_tracker_config")) ||
         (TableExists("glpi_plugin_fusioninventory_config"))) {

      if (TableExists("glpi_plugin_fusioninventory_configs")) {
         $query = "SELECT `value` FROM `glpi_plugin_fusioninventory_configs`
            WHERE `type`='version'
               AND `plugins_id`='".PluginFusioninventoryModule::getModuleId('fusioninventory')."'
            LIMIT 1";

         $data = array();
         if ($result=$DB->query($query)) {
            if ($DB->numrows($result) == "1") {
               $data = $DB->fetch_assoc($result);
               return $data['value'];
            }
         }
      }
      
      if ((!TableExists("glpi_plugin_tracker_agents")) &&
         (!TableExists("glpi_plugin_fusioninventory_agents"))) {
         return "1.1.0";
      }
      if ((!TableExists("glpi_plugin_tracker_config_discovery")) &&
         (!TableExists("glpi_plugin_fusioninventory_config"))) {
         return "2.0.0";
      }
      if (((TableExists("glpi_plugin_tracker_agents")) &&
           (!FieldExists("glpi_plugin_tracker_config", "version"))) &&
         (!TableExists("glpi_plugin_fusioninventory_config"))) {
         return "2.0.1";
      }
      if (((TableExists("glpi_plugin_tracker_agents")) &&
           (FieldExists("glpi_plugin_tracker_config", "version"))) ||
         (TableExists("glpi_plugin_fusioninventory_config"))) {

         $query = "";
         if (TableExists("glpi_plugin_tracker_agents")) {
            $query = "SELECT version FROM glpi_plugin_tracker_config LIMIT 1";
         } else if (TableExists("glpi_plugin_fusioninventory_config")) {
            $query = "SELECT version FROM glpi_plugin_fusioninventory_config LIMIT 1";
         }

         $data = array();
         if ($result=$DB->query($query)) {
            if ($DB->numrows($result) == "1") {
               $data = $DB->fetch_assoc($result);
            }
         }

         if  ($data['version'] == "0") {
            return "2.0.2";
         } else {
            return $data['version'];
         }
      }
   } else if (TableExists("glpi_plugin_fusioninventory_configs")) {
      $query = "SELECT `value` FROM `glpi_plugin_fusioninventory_configs`
         WHERE `type`='version'
            AND `plugins_id`='".PluginFusioninventoryModule::getModuleId('fusioninventory')."'
         LIMIT 1";

      $data = array();
      if ($result=$DB->query($query)) {
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            return $data['value'];
         }
      }
      $query = "SELECT `plugins_id` FROM `glpi_plugin_fusioninventory_agentmodules`
         WHERE `modulename`='WAKEONLAN'
         LIMIT 1";
      if ($result=$DB->query($query)) {
         if ($DB->numrows($result) == "1") {
            $ex_pluginid = $DB->fetch_assoc($result);
            
            // Update plugins_id in tables : 
            $query = "UPDATE `glpi_plugin_fusioninventory_configs`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId('fusioninventory')."'
                  WHERE `plugins_id`='".$ex_pluginid['plugins_id']."'";
            $DB->query($query);
            $query = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId('fusioninventory')."'
                  WHERE `plugins_id`='".$ex_pluginid['plugins_id']."'";
            $DB->query($query);
            $query = "UPDATE `glpi_plugin_fusioninventory_profiles`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId('fusioninventory')."'
                  WHERE `plugins_id`='".$ex_pluginid['plugins_id']."'";
            $DB->query($query);
            $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId('fusioninventory')."'
                  WHERE `plugins_id`='".$ex_pluginid['plugins_id']."'";
            $DB->query($query);            
            
            $query = "SELECT `value` FROM `glpi_plugin_fusioninventory_configs`
               WHERE `type`='version'
                  AND `plugins_id`='".PluginFusioninventoryModule::getModuleId('fusioninventory')."'
               LIMIT 1";

            $data = array();
            if ($result=$DB->query($query)) {
               if ($DB->numrows($result) == "1") {
                  $data = $DB->fetch_assoc($result);
                  return $data['value'];
               }
            }
         }
      }
   }

}



function pluginFusioninventoryUpdate($current_version, $migrationname='Migration') {
   global $DB;
   
   ini_set("max_execution_time", "0");

   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/snmpmodel.class.php");
   require_once(GLPI_ROOT . "/plugins/fusinvsnmp/inc/importexport.class.php");
   require_once(GLPI_ROOT . "/plugins/fusinvsnmp/inc/commondbtm.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/snmpmodelmib.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/configlogfield.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/mapping.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/communicationrest.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/inventorycomputercomputer.class.php");
   require_once(GLPI_ROOT . "/plugins/fusinvinventory/inc/lib.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/profile.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/config.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/ignoredimportdevice.class.php");

   $migration = new $migrationname($current_version);
   $prepare_task = array();
   $prepare_rangeip = array();
   $prepare_Config = array();
   
   $a_plugin = plugin_version_fusioninventory();
   $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
   
   $migration->displayMessage("Update of plugin FusionInventory");
   
   /*
    * CHeck if folders are right created
    */
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
   }
      

   // * Rename tables from old version of FuionInventory (2.2.1 for example)
   $migration->renameTable("glpi_plugin_fusioninventory_rangeip", "glpi_plugin_fusioninventory_ipranges");
   $migration->renameTable("glpi_plugin_fusioninventory_lock", "glpi_plugin_fusioninventory_locks");
   $migration->renameTable("glpi_plugin_fusioninventory_unknown_device", "glpi_plugin_fusioninventory_unknowndevices");
   $migration->renameTable("glpi_plugin_fusioninventory_config", "glpi_plugin_fusioninventory_configs");
   
   $migration->renameTable("glpi_plugin_fusioninventory_networking_ports", "glpi_plugin_fusinvsnmp_networkports"); 
   $migration->renameTable("glpi_plugin_fusioninventory_construct_device", "glpi_plugin_fusinvsnmp_constructdevices");
   $migration->renameTable("glpi_plugin_fusioninventory_construct_mibs", "glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids");
   $migration->renameTable("glpi_plugin_fusioninventory_construct_walks", "glpi_plugin_fusinvsnmp_constructdevicewalks");
   $migration->renameTable("glpi_plugin_fusioninventory_networking", "glpi_plugin_fusioninventory_networkequipments");
   $migration->renameTable("glpi_plugin_fusioninventory_networking_ifaddr", "glpi_plugin_fusinvsnmp_networkequipmentips");
   $migration->renameTable("glpi_plugin_fusioninventory_printers", "glpi_plugin_fusinvsnmp_printers");
   $migration->renameTable("glpi_plugin_fusioninventory_printers_cartridges", "glpi_plugin_fusinvsnmp_printercartridges");
   $migration->renameTable("glpi_plugin_fusioninventory_printers_history", "glpi_plugin_fusinvsnmp_printerlogs");
   $migration->renameTable("glpi_plugin_fusioninventory_model_infos", "glpi_plugin_fusioninventory_snmpmodels");
   $migration->renameTable("glpi_plugin_fusioninventory_mib_networking", "glpi_plugin_fusinvsnmp_modelmibs");
   $migration->renameTable("glpi_plugin_fusioninventory_snmp_connection", "glpi_plugin_fusinvsnmp_configsecurities");
   $migration->renameTable("glpi_plugin_fusioninventory_snmp_history", "glpi_plugin_fusinvsnmp_networkportlogs");
   $migration->renameTable("glpi_plugin_fusioninventory_snmp_history_connections", "glpi_plugin_fusinvsnmp_networkportconnectionlogs");
   
   
   
   $newTable = "glpi_plugin_fusioninventory_agents_inventory_state";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_config_modules";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_connection_stats";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_discovery";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_errors";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_lockable";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_connection_history";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_walks";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_config_snmp_history";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_config_snmp_networking";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   $newTable = "glpi_plugin_fusioninventory_task";
   if (TableExists($newTable)) {
      $DB->query("DROP TABLE `".$newTable."`");
   }
   
   
      
   /*
    * Table glpi_plugin_fusioninventory_agentmodules
    */
      $newTable = "glpi_plugin_fusioninventory_agentmodules";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugins_id', 
                                 'plugins_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'modulename', 
                                 'modulename', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'is_active', 
                                 'is_active', 
                                 "int(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'exceptions', 
                                 'exceptions', 
                                 "text COMMENT 'array(agent_id)'");
         $migration->changeField($newTable, 
                                 'entities_id', 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '-1'");
         $migration->changeField($newTable, 
                                 'url', 
                                 'url', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         
      $migration->migrationOneTable($newTable);
      
         $migration->addField($newTable, 
                              'id', 
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                              'plugins_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'modulename', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'is_active', 
                              "int(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'exceptions', 
                              "text COMMENT 'array(agent_id)'");
         $migration->addField($newTable, 
                              'entities_id', 
                              "int(11) NOT NULL DEFAULT '-1'");
         $migration->addField($newTable, 
                              'url', 
                              "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addKey($newTable,
                            array("plugins_id", "modulename"),
                            "unicity",
                            "UNIQUE");
         $migration->addKey($newTable,
                            "is_active");
         $migration->addKey($newTable,
                            "entities_id");
   
      $migration->migrationOneTable($newTable);
      
   
   
   /*
    *  Table glpi_plugin_fusioninventory_agents
    */
      $newTable = "glpi_plugin_fusioninventory_agents";
      $prepare_agentConfig = array();
      if (TableExists("glpi_plugin_tracker_agents")
              AND FieldExists("glpi_plugin_tracker_agents", 
                              "ifaddr_start")) {
         $query = "SELECT * FROM `glpi_plugin_tracker_agents`";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $prepare_rangeip[] = array("ip_start"=> $data['ifaddr_start'],
                                       "ip_end"  => $data['ifaddr_end'],
                                       "name"    => $data['name']);
            $prepare_agentConfig[] = array("name" => $data["name"],
                                           "lock" => $data['lock'],
                                           "threads_snmpquery"    => $data['nb_process_query'],
                                           "threads_netdiscovery" => $data['nb_process_discovery']);
         }
      } else if (TableExists("glpi_plugin_tracker_agents")
                  AND FieldExists("glpi_plugin_tracker_agents", 
                              "core_discovery")) {
         $query = "SELECT * FROM `glpi_plugin_tracker_agents`";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $prepare_agentConfig[] = array("name" => $data["name"],
                                           "lock" => $data['lock'],
                                           "threads_snmpquery"    => $data['threads_query'],
                                           "threads_netdiscovery" => $data['threads_discovery']);
         }
      } else if (TableExists("glpi_plugin_fusioninventory_agents")) {
         if (FieldExists($newTable, "module_snmpquery")) {
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_agents`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $prepare_agentConfig[] = array("id" => $data["ID"],
                                              "threads_snmpquery"    => $data['threads_query'],
                                              "threads_netdiscovery" => $data['threads_discovery'],
                                              "SNMPQUERY" => $data['module_snmpquery'],
                                              "NETDISCOVERY" => $data['module_netdiscovery'],
                                              "INVENTORY" => $data['module_inventory'],
                                              "WAKEONLAN" => $data['module_wakeonlan']);
            }
         }
      }
      $migration->renameTable("glpi_plugin_tracker_agents", $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);         
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "entities_id",
                                 "entities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "is_recursive",
                                 "is_recursive",
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "last_contact",
                                 "last_contact",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "version",
                                 "version",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "lock",
                                 "lock",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "device_id",
                                 "device_id",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'XML <DEVICE_ID> TAG VALUE'");
         $migration->changeField($newTable,
                                 "items_id",
                                 "items_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "token",
                                 "token",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "useragent",
                                 "useragent",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "tag",
                                 "tag",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "last_agent_update",
                                 "last_contact",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "fusioninventory_agent_version",
                                 "version",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "key",
                                 "device_id",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "on_device",
                                 "items_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "lock",
                                 "lock",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->dropField($newTable, 
                               "module_snmpquery");
         $migration->dropField($newTable, 
                               "module_netdiscovery");
         $migration->dropField($newTable, 
                               "module_inventory");
         $migration->dropField($newTable, 
                               "module_wakeonlan");
         $migration->dropField($newTable, 
                               "core_discovery");
         $migration->dropField($newTable, 
                               "threads_discovery");
         $migration->dropField($newTable, 
                               "core_query");
         $migration->dropField($newTable, 
                               "threads_query");
         $migration->dropField($newTable, 
                               "tracker_agent_version");
         $migration->dropField($newTable, 
                               "logs");
         $migration->dropField($newTable, 
                               "fragment");
         $migration->dropField($newTable, 
                               "itemtype");
         $migration->dropField($newTable, 
                               "device_type");
         $migration->dropKey($newTable, 
                             "key");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              "entities_id", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "is_recursive", 
                              "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                              "name", 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              "last_contact", 
                              "datetime DEFAULT NULL");
         $migration->addField($newTable, 
                              "version", 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              "lock", 
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "device_id", 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              "items_id", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "token", 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              "useragent", 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              "tag", 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addKey($newTable,
                            "name");
         $migration->addKey($newTable,
                            "device_id");
         $migration->addKey($newTable,
                            "items_id");

      $migration->migrationOneTable($newTable);
      $DB->list_fields($newTable, false);
      
   /*
    * Table glpi_plugin_fusioninventory_agentmodules
    */
      $newTable = "glpi_plugin_fusioninventory_agentmodules";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugins_id', 
                                 'plugins_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'modulename', 
                                 'modulename', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'is_active', 
                                 'is_active', 
                                 "int(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'exceptions', 
                                 'exceptions', 
                                 "text COMMENT 'array(agent_id)'");
         $migration->changeField($newTable, 
                                 'entities_id', 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '-1'");
         $migration->changeField($newTable, 
                                 'url', 
                                 'url', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
      $migration->migrationOneTable($newTable);
 
      
      
   /*
    * Add WakeOnLan module appear in version 2.3.0
    */
   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` WHERE `modulename`='WAKEONLAN'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      $query_ins= "INSERT INTO `glpi_plugin_fusioninventory_agentmodules`
            (`plugins_id`, `modulename`, `is_active`, `exceptions`)
         VALUES ('".$plugins_id."', 'WAKEONLAN', '0', '".exportArrayToDB(array())."')";
      $DB->query($query_ins);
   }
      

   
   /*
    * Table glpi_plugin_fusioninventory_configs
    */
      $newTable = "glpi_plugin_fusioninventory_configs";
      if (TableExists('glpi_plugin_tracker_config')) {
         if (FieldExists('glpi_plugin_tracker_config', 'ssl_only')) {
            $query = "SELECT * FROM `glpi_plugin_tracker_config`
               LIMIT 1"; 
            $result = $DB->query($query);
            if ($DB->numrows($result) > 0) {
               $data = $DB->fetch_assoc($result);
               $prepare_Config['ssl_only'] = $data['ssl_only'];
            }            
         }         
      }
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(1) NOT NULL AUTO_INCREMENT,
                     PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'ID', 
                                 'id', 
                                 "int(1) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(1) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'type', 
                                 'type', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable, 
                                 'value', 
                                 'value', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'plugins_id', 
                                 'plugins_id', 
                                 "int(11) NOT NULL DEFAULT '0'");         
      $migration->migrationOneTable($newTable);
         $migration->dropField($newTable, "version");
         $migration->dropField($newTable, "URL_agent_conf");
         $migration->dropField($newTable, "ssl_only");
         $migration->dropField($newTable, "authsnmp");
         $migration->dropField($newTable, "inventory_frequence");
         $migration->dropField($newTable, "criteria1_ip");
         $migration->dropField($newTable, "criteria1_name");
         $migration->dropField($newTable, "criteria1_serial");
         $migration->dropField($newTable, "criteria1_macaddr");
         $migration->dropField($newTable, "criteria2_ip");
         $migration->dropField($newTable, "criteria2_name");
         $migration->dropField($newTable, "criteria2_serial");
         $migration->dropField($newTable, "criteria2_macaddr");
         $migration->dropField($newTable, "delete_agent_process");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'id', 
                              "int(1) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                              'type', 
                              "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable, 
                              'value', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'plugins_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'module', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addKey($newTable,
                            array("type", "plugins_id"),
                            "unicity",
                            "UNIQUE");
      $migration->migrationOneTable($newTable);
      // Reinitialize cache of fields of table
      $DB->list_fields($newTable, false);
      
   /*
    * Table glpi_plugin_fusioninventory_credentials
    */
      $newTable = "glpi_plugin_fusioninventory_credentials";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "entities_id",
                                 "entities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "is_recursive",
                                 "is_recursive",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable,
                                 "username",
                                 "username",
                                 "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable,
                                 "password",
                                 "password",
                                 "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "date_mod",
                                 "date_mod",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "itemtype",
                                 "itemtype",
                                 "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "entities_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "is_recursive",
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "name",
                              "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable,
                              "username",
                              "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable,
                              "password",
                              "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable,
                              "comment",
                              "text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "date_mod",
                              "datetime DEFAULT NULL");
         $migration->addField($newTable,
                              "itemtype",
                              "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
      $migration->migrationOneTable($newTable);
         
      
      
   /*
    * Table glpi_plugin_fusioninventory_credentialips
    */
      $newTable = "glpi_plugin_fusioninventory_credentialips";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "entities_id",
                                 "entities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_credentials_id",
                                 "plugin_fusioninventory_credentials_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "ip",
                                 "ip",
                                 "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable,
                                 "date_mod",
                                 "date_mod",
                                 "datetime DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "entities_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "plugin_fusioninventory_credentials_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "name",
                              "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable,
                              "comment",
                              "text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "ip",
                              "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable,
                              "date_mod",
                              "datetime DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         
         
 
   /*
    * Table glpi_plugin_fusioninventory_ipranges
    */
      $newTable = "glpi_plugin_fusioninventory_ipranges";
      if (TableExists("glpi_plugin_tracker_rangeip")) {
         // Get all data to create task
         $query = "SELECT * FROM `glpi_plugin_tracker_rangeip`";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            if ($data['discover'] == '1') {
               $prepare_task[] = array("agents_id" => $data['FK_tracker_agents'],
                                       "ipranges_id" => $data['ID'],
                                       "netdiscovery" => "1");
            }
            if ($data['query'] == '1') {
               $prepare_task[] = array("agents_id" => $data['FK_tracker_agents'],
                                       "ipranges_id" => $data['ID'],
                                       "snmpquery" => "1");
            }
         }
      }
      if (TableExists("glpi_plugin_fusioninventory_rangeip")
              AND FieldExists("glpi_plugin_fusioninventory_rangeip", 
                              "FK_fusioninventory_agents_discover")) {
         
         // Get all data to create task
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_rangeip`";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            if ($data['discover'] == '1') {
               $prepare_task[] = array("agents_id" => $data['FK_fusioninventory_agents_discover'],
                                       "ipranges_id" => $data['ID'],
                                       "netdiscovery" => "1");
            }
            if ($data['query'] == '1') {
               $prepare_task[] = array("agents_id" => $data['FK_fusioninventory_agents_query'],
                                       "ipranges_id" => $data['ID'],
                                       "snmpquery" => "1");
            }
         }         
      }    
      $migration->renameTable("glpi_plugin_tracker_rangeip", 
                              $newTable);
      $migration->renameTable("glpi_plugin_fusinvsnmp_ipranges", 
                              $newTable);      
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(1) NOT NULL AUTO_INCREMENT,
                     PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }      
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "entities_id",
                                 "entities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ip_start",
                                 "ip_start",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "ip_end",
                                 "ip_end",
                                 "varchar(255) DEFAULT NULL");      
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "ifaddr_start",
                                 "ip_start",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "ifaddr_end",
                                 "ip_end",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "FK_entities",
                                 "entities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->dropField($newTable,
                               "FK_tracker_agents");
         $migration->dropField($newTable,
                               "discover");
         $migration->dropField($newTable,
                               "query");
         $migration->dropField($newTable,
                               "FK_fusioninventory_agents_discover");
         $migration->dropField($newTable,
                               "FK_fusioninventory_agents_query");
         $migration->dropKey($newTable, "FK_tracker_agents");
         $migration->dropKey($newTable, "FK_tracker_agents_2");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "name",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "entities_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "ip_start",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "ip_end",
                              "varchar(255) DEFAULT NULL");      
         $migration->addKey($newTable,
                            "entities_id");
      $migration->migrationOneTable($newTable);


      
   /*
    * Table glpi_plugin_fusioninventory_locks
    */
      $newTable = "glpi_plugin_fusioninventory_locks";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "tablename",
                                 "tablename",
                                 "varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable,
                                 "items_id",
                                 "items_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "fields",
                                 "tablefields",
                                 "text DEFAULT NULL");
         $migration->changeField($newTable,
                                 "tablefields",
                                 "tablefields",
                                 "text DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->dropField($newTable, "itemtype");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "tablename",
                              "varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable,
                              "items_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "tablefields",
                              "text DEFAULT NULL");
         $migration->addKey($newTable,
                            "tablename");
         $migration->addKey($newTable,
                            "items_id");
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusioninventory_mappings
    */
      $newTable = "glpi_plugin_fusioninventory_mappings";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "itemtype",
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "table",
                                 "table",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "tablefield",
                                 "tablefield",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");      
         $migration->changeField($newTable,
                                 "locale",
                                 "locale",
                                 "int(4) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "shortlocale",
                                 "shortlocale",
                                 "int(4) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "table",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "tablefield",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");      
         $migration->addField($newTable,
                                 "locale",
                                 "int(4) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "shortlocale",
                                 "int(4) DEFAULT NULL");
         $migration->addKey($newTable,
                            "name");
         $migration->addKey($newTable,
                            "itemtype");
         $migration->addKey($newTable,
                            "table");
         $migration->addKey($newTable,
                            "tablefield");
      $migration->migrationOneTable($newTable);
      
      
         
   /*
    * Table glpi_plugin_fusioninventory_profiles
    */
      $newTable = "glpi_plugin_fusioninventory_profiles";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "type",
                                 "type",
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable,
                                 "right",
                                 "right",
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugins_id",
                                 "plugins_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "profiles_id",
                                 "profiles_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->dropField($newTable,
                               "name");
         $migration->dropField($newTable,
                               "interface");
         $migration->dropField($newTable,
                               "is_default");
         $migration->dropField($newTable,
                               "snmp_networking");
         $migration->dropField($newTable,
                               "snmp_printers");
         $migration->dropField($newTable,
                               "snmp_models");
         $migration->dropField($newTable,
                               "snmp_authentification");
         $migration->dropField($newTable,
                               "rangeip");
         $migration->dropField($newTable,
                               "agents");
         $migration->dropField($newTable,
                               "remotecontrol");
         $migration->dropField($newTable,
                               "agentsprocesses");
         $migration->dropField($newTable,
                               "unknowndevices");
         $migration->dropField($newTable,
                               "reports");
         $migration->dropField($newTable,
                               "deviceinventory");
         $migration->dropField($newTable,
                               "netdiscovery");
         $migration->dropField($newTable,
                               "snmp_query");
         $migration->dropField($newTable,
                               "wol");
         $migration->dropField($newTable,
                               "configuration");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              "type", 
                              "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable, 
                              "right", 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              "plugins_id", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "profiles_id", 
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      
         // Remove multiple lines can have problem with unicity
         $query = "SELECT * , count(`id`) AS cnt
            FROM `glpi_plugin_fusioninventory_profiles`
            GROUP BY `type`,`plugins_id`,`profiles_id`
            HAVING cnt >1
            ORDER BY cnt";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $queryd = "DELETE FROM `glpi_plugin_fusioninventory_profiles`
               WHERE `type`='".$data['type']."'
                  AND `plugins_id`='".$data['plugins_id']."'
                  AND `profiles_id`='".$data['profiles_id']."'
               ORDER BY `id` DESC
               LIMIT ".($data['cnt'] - 1)." ";
            $DB->query($queryd);
         }
      
         $migration->addKey($newTable,
                            array("type", "plugins_id", "profiles_id"),
                            "unicity",
                            "UNIQUE");
      $migration->migrationOneTable($newTable);

         
      
   /*
    * Table glpi_plugin_fusioninventory_tasks
    */
      $newTable = "glpi_plugin_fusioninventory_tasks";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "entities_id",
                                 "entities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "date_creation",
                                 "date_creation",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable,
                                 "is_active",
                                 "is_active",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "communication",
                                 "communication",
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'push'");
         $migration->changeField($newTable,
                                 "permanent",
                                 "permanent",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "date_scheduled",
                                 "date_scheduled",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "periodicity_count",
                                 "periodicity_count",
                                 "int(6) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "periodicity_type",
                                 "periodicity_type",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "execution_id",
                                 "execution_id",
                                 "bigint(20) NOT NULL DEFAULT '0'");         
         $migration->changeField($newTable,
                                 "is_advancedmode",
                                 "is_advancedmode",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "entities_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "name",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "date_creation",
                              "datetime DEFAULT NULL");
         $migration->addField($newTable,
                              "comment",
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable,
                              "is_active",
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "communication",
                              "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'push'");
         $migration->addField($newTable,
                              "permanent",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "date_scheduled",
                              "datetime DEFAULT NULL");
         $migration->addField($newTable,
                              "periodicity_count",
                              "int(6) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "periodicity_type",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "execution_id",
                              "bigint(20) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "is_advancedmode",
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "entities_id");
         $migration->addKey($newTable,
                            "is_active");
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusioninventory_taskjobs
    */
      $newTable = "glpi_plugin_fusioninventory_taskjobs";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_tasks_id",
                                 "plugin_fusioninventory_tasks_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "entities_id",
                                 "entities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "date_creation",
                                 "date_creation",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "retry_nb",
                                 "retry_nb",
                                 "tinyint(2) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "retry_time",
                                 "retry_time",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugins_id",
                                 "plugins_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "method",
                                 "method",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "definition",
                                 "definition",
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable,
                                 "action",
                                 "action",
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable,
                                 "users_id",
                                 "users_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "status",
                                 "status",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "rescheduled_taskjob_id",
                                 "rescheduled_taskjob_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "statuscomments",
                                 "statuscomments",
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable,
                                 "periodicity_count",
                                 "periodicity_count",
                                 "int(6) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "periodicity_type",
                                 "periodicity_type",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "execution_id",
                                 "execution_id",
                                 "bigint(20) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "plugin_fusioninventory_tasks_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "entities_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "name",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "date_creation",
                              "datetime DEFAULT NULL");
         $migration->addField($newTable,
                              "retry_nb",
                              "tinyint(2) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "retry_time",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "plugins_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "method",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "definition",
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable,
                              "action",
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable,
                              "comment",
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable,
                              "users_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "status",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "rescheduled_taskjob_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "statuscomments",
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable,
                              "periodicity_count",
                              "int(6) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "periodicity_type",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "execution_id",
                              "bigint(20) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_tasks_id");
         $migration->addKey($newTable,
                            "entities_id");
         $migration->addKey($newTable,
                            "plugins_id");
         $migration->addKey($newTable,
                            "users_id");
         $migration->addKey($newTable,
                            "rescheduled_taskjob_id");
         $migration->addKey($newTable,
                            "method");
      $migration->migrationOneTable($newTable);

         
      
   /*
    * Table glpi_plugin_fusioninventory_taskjoblogs
    */
      $newTable = "glpi_plugin_fusioninventory_taskjoblogs";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` bigint(20) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "bigint(20) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_taskjobstatus_id",
                                 "plugin_fusioninventory_taskjobstates_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "date",
                                 "date",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "items_id",
                                 "items_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "itemtype",
                                 "itemtype",
                                 "varchar(100) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "state",
                                 "state",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text DEFAULT NULL");
         $migration->dropKey($newTable,
                             "plugin_fusioninventory_taskjobstatus_id");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "bigint(20) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "plugin_fusioninventory_taskjobstates_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "date",
                              "datetime DEFAULT NULL");
         $migration->addField($newTable,
                              "items_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "itemtype",
                              "varchar(100) DEFAULT NULL");
         $migration->addField($newTable,
                              "state",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "comment",
                              "text DEFAULT NULL");
         $migration->addKey($newTable,
                            array("plugin_fusioninventory_taskjobstates_id", "state", "date"),
                            "plugin_fusioninventory_taskjobstates_id");
      $migration->migrationOneTable($newTable);
      
         
      
   /*
    * Table glpi_plugin_fusioninventory_taskjobstates
    */
      $newTable = "glpi_plugin_fusioninventory_taskjobstates";
      if (TableExists("glpi_plugin_fusioninventory_taskjobstatus")) {
         $migration->renameTable("glpi_plugin_fusioninventory_taskjobstatus", $newTable);
      }
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` bigint(20) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "bigint(20) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_taskjobs_id",
                                 "plugin_fusioninventory_taskjobs_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "items_id",
                                 "items_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "itemtype",
                                 "itemtype",
                                 "varchar(100) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "state",
                                 "state",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_agents_id",
                                 "plugin_fusioninventory_agents_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "specificity",
                                 "specificity",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "uniqid",
                                 "uniqid",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable,
                              "id",
                              "bigint(20) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "plugin_fusioninventory_taskjobs_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "items_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "itemtype",
                              "varchar(100) DEFAULT NULL");
         $migration->addField($newTable,
                              "state",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "plugin_fusioninventory_agents_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "specificity",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "uniqid",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_taskjobs_id");
         $migration->addKey($newTable,
                            array("plugin_fusioninventory_agents_id", "state"),
                            "plugin_fusioninventory_agents_id");
      $migration->migrationOneTable($newTable);
         
      
      
   /*
    * Table glpi_plugin_fusioninventory_unknowndevices
    */
      $newTable = "glpi_plugin_fusioninventory_unknowndevices";
      if (TableExists('glpi_plugin_tracker_unknown_device')) {
         $migration->renameTable("glpi_plugin_tracker_unknown_device", $newTable);
      } else if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 'varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL');
         $migration->changeField($newTable, 
                                 'date_mod', 
                                 'date_mod', 
                                 'datetime DEFAULT NULL');
         $migration->changeField($newTable, 
                                 'entities_id', 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'locations_id', 
                                 'locations_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'is_deleted', 
                                 'is_deleted', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'serial', 
                                 'serial', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'otherserial', 
                                 'otherserial', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'contact', 
                                 'contact', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'domain', 
                                 'domain', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'comments', 
                                 'comment', 
                                 "text DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'comment', 
                                 'comment', 
                                 "text DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'type', 
                                 'item_type', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'item_type', 
                                 'item_type', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'accepted', 
                                 'accepted', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_fusioninventory_agents_id', 
                                 'plugin_fusioninventory_agents_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'ifaddr', 
                                 'ip', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'ip', 
                                 'ip', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'ifmac', 
                                 'mac', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'mac', 
                                 'mac', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'hub', 
                                 'hub', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'states_id', 
                                 'states_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);      
         $migration->changeField($newTable, 
                                 'ID', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'FK_entities', 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'location', 
                                 'locations_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'deleted', 
                                 'is_deleted', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");      
      $migration->migrationOneTable($newTable);   
         $migration->dropField($newTable, "dnsname");
         $migration->dropField($newTable, "snmp");
         $migration->dropField($newTable, "FK_model_infos");
         $migration->dropField($newTable, "FK_snmp_connection");
         $migration->dropField($newTable, "FK_agent");
      $migration->migrationOneTable($newTable); 
         $migration->addField($newTable, 
                              'id', 
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                              'name', 
                              'varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL');
         $migration->addField($newTable, 
                              'date_mod', 
                              'datetime DEFAULT NULL');
         $migration->addField($newTable, 
                              'entities_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'locations_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'is_deleted', 
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'serial', 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              'otherserial', 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              'contact', 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              'domain', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'comment', 
                              "text DEFAULT NULL");
         $migration->addField($newTable, 
                              'item_type', 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              'accepted', 
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'plugin_fusioninventory_agents_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'ip', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'mac', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'hub', 
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'states_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "entities_id");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_agents_id");
         $migration->addKey($newTable,
                            "is_deleted");
         $migration->addKey($newTable,
                            "date_mod");
      $migration->migrationOneTable($newTable);
      
   
      
         

   /*
    * Table glpi_plugin_fusioninventory_ignoredimportdevices
    */
      $newTable = "glpi_plugin_fusioninventory_ignoredimportdevices";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "date",
                                 "date",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "itemtype",
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "entities_id",
                                 "entities_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
         $migration->changeField($newTable,
                                 "ip",
                                 "ip",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "mac",
                                 "mac",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "rules_id",
                                 "rules_id",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "date",
                                 "datetime DEFAULT NULL");
         $migration->addField($newTable,
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "entities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ip",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "mac",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "rules_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "method",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         
      
         
   /*
    * Table glpi_plugin_fusioninventory_rulematchedlogs
    */
      $newTable = "glpi_plugin_fusioninventory_rulematchedlogs";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         
      $migration->migrationOneTable($newTable);
      
         $migration->addField($newTable,
                                 "date",
                                 "datetime DEFAULT NULL");
      $migration->addField($newTable,
                                 "items_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "rules_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_agents_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "method",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         
         

   /*
    * Table glpi_plugin_fusioninventory_ignoredimportdevices
    */
      $newTable = "glpi_plugin_fusioninventory_inventorycomputercriterias";      
      $migration->renameTable("glpi_plugin_fusinvinventory_criterias", $newTable);
  
      
      
   /*
    * Table glpi_plugin_fusioninventory_inventorycomputerlibserialization
    */
      $newTable = "glpi_plugin_fusioninventory_inventorycomputerlibserialization";      
      $migration->renameTable("glpi_plugin_fusinvinventory_libserialization", $newTable);
      if (!TableExists($newTable)) {
         $DB->query("CREATE TABLE `".$newTable."` (
                        `internal_id` varchar(255) NOT NULL DEFAULT '',
                        PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      }   
         $migration->changeField($newTable,
                                 "internal_id",
                                 "internal_id",
                                 "varchar(255) NOT NULL DEFAULT ''");
         $migration->changeField($newTable,
                                 "computers_id",
                                 "computers_id",
                                 "int(11) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "serialized_sections1",
                                 "serialized_sections1",
                                 "longtext DEFAULT NULL");
         $migration->changeField($newTable,
                                 "serialized_sections2",
                                 "serialized_sections2",
                                 "longtext DEFAULT NULL");
         $migration->changeField($newTable,
                                 "serialized_sections3",
                                 "serialized_sections3",
                                 "longtext DEFAULT NULL");
         $migration->changeField($newTable,
                                 "hash",
                                 "hash",
                                 "varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "last_fusioninventory_update",
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");

      $migration->migrationOneTable($newTable);

         $migration->addField($newTable,
                                 "internal_id",
                                 "varchar(255) NOT NULL DEFAULT ''");
         $migration->addField($newTable,
                                 "computers_id",
                                 "int(11) DEFAULT NULL");
         $migration->addField($newTable,
                                 "serialized_sections1",
                                 "longtext DEFAULT NULL");
         $migration->addField($newTable,
                                 "serialized_sections2",
                                 "longtext DEFAULT NULL");
         $migration->addField($newTable,
                                 "serialized_sections3",
                                 "longtext DEFAULT NULL");
         $migration->addField($newTable,
                                 "hash",
                                 "varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
      $migration->migrationOneTable($newTable);
    
      
   /*
    * Table glpi_plugin_fusioninventory_inventorycomputerblacklists
    */
      $newTable = "glpi_plugin_fusioninventory_inventorycomputerblacklists";      
      $migration->renameTable("glpi_plugin_fusinvinventory_blacklists", $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_fusioninventory_criterium_id', 
                                 'plugin_fusioninventory_criterium_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'value', 
                                 'value', 
                                 "varchar(255) DEFAULT NULL");
      
      $migration->migrationOneTable($newTable);
      
         $migration->addField($newTable, 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                                 'plugin_fusioninventory_criterium_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'value', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addKey($newTable, 
                            "plugin_fusioninventory_criterium_id");
      $migration->migrationOneTable($newTable);
            
   /*
    *  Udpate criteria for blacklist
    */
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_inventorycomputercriterias`
         WHERE `name`='Manufacturer'";
      $result = $DB->query($query);
      if ($DB->numrows($result) == '0') {
         $query_ins = "INSERT INTO `glpi_plugin_fusioninventory_inventorycomputercriterias` (`name`, `comment`) 
            VALUES ('Manufacturer', 'manufacturer')";
         $id = $DB->query($query_ins);
         $query_ins = "INSERT INTO `".$newTable."` 
               (`plugin_fusioninventory_criterium_id`, `value`) 
            VALUES ('".$id."', 'System manufacturer')";
      }
       
   /*
    * Update blacklist
    */
      $input = array();
      $input['03000200-0400-0500-0006-000700080009'] = '2';
      $input['6AB5B300-538D-1014-9FB5-B0684D007B53'] = '2';
      $input['01010101-0101-0101-0101-010101010101'] = '2';
      $input['20:41:53:59:4e:ff'] = '3';
      $input['02:00:4e:43:50:49'] = '3';
      $input['e2:e6:16:20:0a:35'] = '3';
      $input['d2:0a:2d:a0:04:be'] = '3';
      $input['00:a0:c6:00:00:00'] = '3';
      $input['d2:6b:25:2f:2c:e7'] = '3';
      $input['33:50:6f:45:30:30'] = '3';
      $input['0a:00:27:00:00:00'] = '3';
      $input['00:50:56:C0:00:01'] = '3';
      $input['00:50:56:C0:00:08'] = '3';
      $input['MB-1234567890'] = '1';
      foreach ($input as $value=>$type) {
         $query = "SELECT * FROM `".$newTable."`
            WHERE `plugin_fusioninventory_criterium_id`='".$type."'
             AND `value`='".$value."'";
         $result=$DB->query($query);
         if ($DB->numrows($result) == '0') {
            $query = "INSERT INTO `".$newTable."` 
                  (`plugin_fusioninventory_criterium_id`, `value`) 
               VALUES ( '".$type."', '".$value."')";
            $DB->query($query);         
         }
      }
      
      
   /*
    * Table glpi_plugin_fusioninventory_inventorycomputerantivirus
    */
      $newTable = "glpi_plugin_fusioninventory_inventorycomputerantivirus";      
      $migration->renameTable("glpi_plugin_fusinvinventory_antivirus", $newTable);
      if (!TableExists($newTable)) {
         $DB->query("CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");
      }   
      $migration->addField($newTable, 
                           "id", 
                           "int(11) NOT NULL AUTO_INCREMENT");
      $migration->addField($newTable, 
                           "computers_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "name", 
                           "varchar(255) DEFAULT NULL");
      $migration->addField($newTable, 
                           "manufacturers_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "version", 
                           "varchar(255) DEFAULT NULL");  
      $migration->addField($newTable, 
                           "is_active", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "uptodate", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addKey($newTable, 
                          "name");
      $migration->addKey($newTable, 
                          "version");
      $migration->addKey($newTable, 
                          "is_active");
      $migration->addKey($newTable, 
                          "uptodate");
      $migration->migrationOneTable($newTable);
      
      
   /*
    * Table glpi_plugin_fusioninventory_inventorycomputercomputers
    */
      if (TableExists("glpi_plugin_fusinvinventory_computers")
              AND FieldExists("glpi_plugin_fusinvinventory_computers", "uuid")) {
         $Computer = new Computer();
         $sql = "SELECT * FROM `glpi_plugin_fusinvinventory_computers`";
         $result=$DB->query($sql);
         while ($data = $DB->fetch_array($result)) {
            if ($Computer->getFromDB($data['items_id'])) {
               $input = array();
               $input['id'] = $data['items_id'];
               $input['uuid'] = $data['uuid'];
               $Computer->update($input);
            }
         }
         $sql = "DROP TABLE `glpi_plugin_fusinvinventory_computers`";
         $DB->query($sql);      
      }
      if (TableExists("glpi_plugin_fusinvinventory_tmp_agents")) {
         $sql = "DROP TABLE `glpi_plugin_fusinvinventory_tmp_agents`";
         $DB->query($sql);
      }
      $newTable = "glpi_plugin_fusioninventory_inventorycomputercomputers";      
      $migration->renameTable("glpi_plugin_fusinvinventory_computers", $newTable);
      if (!TableExists($newTable)) {
         $DB->query("CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");
      }   
         $migration->addField($newTable, 
                              "id", 
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                              "computers_id", 
                              "int(11) NOT NULL DEFAULT '0'");   
         $migration->addField($newTable, 
                              "bios_date", 
                              "datetime DEFAULT NULL");
         $migration->addField($newTable, 
                              "bios_version", 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              "bios_manufacturers_id", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "operatingsystem_installationdate", 
                              "datetime DEFAULT NULL");
         $migration->addField($newTable, 
                              "winowner", 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              "wincompany", 
                              "varchar(255) DEFAULT NULL");
         $migration->addKey($newTable, 
                             "computers_id");

      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusioninventory_snmpmodelmiblabels
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodelmiblabels";
      $migration->renameTable("glpi_dropdown_plugin_tracker_mib_label", 
                              $newTable);
      $migration->renameTable("glpi_plugin_fusinvsnmp_miblabels", 
                              $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }   
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");      
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "comments",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");         
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusioninventory_snmpmodelmibobjects
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodelmibobjects";
      $migration->renameTable("glpi_dropdown_plugin_tracker_mib_object", 
                              $newTable);
      $migration->renameTable("glpi_plugin_fusinvsnmp_mibobjects", 
                              $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");      
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "comments",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");         
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         
      
   
   /*
    * Table glpi_plugin_fusioninventory_snmpmodelmiboids
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodelmiboids";
      $migration->renameTable("glpi_dropdown_plugin_tracker_mib_oid", 
                              $newTable);
      $migration->renameTable("glpi_plugin_fusinvsnmp_miboids", 
                              $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");      
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "comments",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->dropField($newTable,
                               "plugin_fusinvsnmp_constructdevices_id");
         $migration->dropField($newTable,
                               "oid_port_counter");
         $migration->dropField($newTable,
                               "oid_port_dyn");
         $migration->dropField($newTable,
                               "itemtype");
         $migration->dropField($newTable,
                               "vlan");         
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         
      
      
   /*
    * glpi_plugin_fusioninventory_configlogfields
    */
      $newTable = "glpi_plugin_fusioninventory_configlogfields";
      $migration->renameTable("glpi_plugin_fusioninventory_config_snmp_history", 
                              $newTable);
      $migration->renameTable("glpi_plugin_fusinvsnmp_configlogfields", 
                              $newTable);
      if (TableExists($newTable)) {
         if (FieldExists($newTable, "field")) {
            $query = "SELECT * FROM `".$newTable."`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $pFusioninventoryMapping = new PluginFusioninventoryMapping();
               $mapping = 0;
               if ($mapping = $pFusioninventoryMapping->get("NetworkEquipment", $data['field'])) {
                  $queryu = "UPDATE `".$newTable."`
                     SET `field`='".$mapping['id']."'
                     WHERE `field`='".$data['field']."'";
                  $DB->query($queryu);
               }
            }
         }
      }
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(8) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(8) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "days",
                                 "days",
                                 "int(255) NOT NULL DEFAULT '-1'");         
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(8) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "field",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
      $migration->migrationOneTable($newTable);             
         $migration->addField($newTable,
                                 "id",
                                 "int(8) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "days",
                                 "int(255) NOT NULL DEFAULT '-1'");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_mappings_id");
      $migration->migrationOneTable($newTable);

         $configLogField = new PluginFusioninventoryConfigLogField();
         $configLogField->initConfig();
      
         
      
   /*
    * glpi_plugin_fusioninventory_snmpmodelconstructdevices
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodelconstructdevices";
      $migration->renameTable("glpi_plugin_fusinvsnmp_constructdevices", 
                        $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "have_someinformations",
                                 "have_someinformations",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "have_importantinformations",
                                 "have_importantinformations",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "have_ports",
                                 "have_ports",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "have_portsconnections",
                                 "have_portsconnections",
                                 "tinyint(1) NOT NULL DEFAULT '0'"); 
         $migration->changeField($newTable,
                                 "have_vlan",
                                 "have_vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "have_trunk",
                                 "have_trunk",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "released",
                                 "released",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "snmpmodel_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'"); 
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'"); 
         $migration->changeField($newTable,
                                 "FK_glpi_enterprise",
                                 "manufacturers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 "type",
                                 "itemtype", 
                                 "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->dropField($newTable, "device");
         $migration->dropField($newTable, "firmware");
   $migration->migrationOneTable($newTable);      
      $migration->addField($newTable, 
                           "manufacturers_id", 
                           "int(11) NOT NULL DEFAULT '0'");   
      $migration->addField($newTable, 
                           "sysdescr", 
                           "text DEFAULT NULL");
      $migration->addField($newTable, 
                           "itemtype", 
                           "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->addField($newTable, 
                           "plugin_fusioninventory_snmpmodels_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "networkmodel_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "printermodel_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_someinformations", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_importantinformations", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_ports", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_portsconnections", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_vlan", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_trunk", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "released", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "releasedsnmpmodel_id", 
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
         
         
   
   /*
    * Table glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks";
      $migration->renameTable("glpi_plugin_fusinvsnmp_constructdevicewalks", 
                              $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "construct_device_id",
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_constructdevices_id",
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "log",
                                 "log",
                                 "text DEFAULT NULL");          
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "plugin_fusioninventory_snmpmodelconstructdevices_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "log",
                              "text DEFAULT NULL");
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids";
      $migration->renameTable("glpi_plugin_fusinvsnmp_constructdevice_miboids", 
                              $newTable);      
      // Update with mapping
      if (TableExists($newTable)) {
         if (FieldExists($newTable, "mapping_name")
                 AND FieldExists($newTable, "itemtype")) {
            $query = "SELECT * FROM `".$newTable."`
               GROUP BY `itemtype`, `mapping_type`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               if (!is_numeric($data['mapping_name'])) {
                  $pFusioninventoryMapping = new PluginFusioninventoryMapping();
                  $mapping = 0;
                  $mapping_type = '';
                  if ($data['itemtype'] == 'glpi_networkequipments') {
                     $mapping_type = 'NetworkEquipment';
                  } else if ($data['itemtype'] == 'glpi_printers') {
                     $mapping_type = 'Printer';
                  }
                  if ($mapping = $pFusioninventoryMapping->get($mapping_type, $data['mapping_name'])) {
                     $data['mapping_name'] = $mapping['id'];
                     $queryu = "UPDATE `".$newTable."`
                        SET `mapping_name`='".$mapping['id']."',
                           `mapping_type`='".$mapping_type."'
                        WHERE `itemtype`='".$data['itemtype']."'
                           AND `mapping_name`='".$data['mapping_name']."'";
                     $DB->query($queryu);
                  }
               }
            }
         }
         $migration->changeField($newTable,
                                 "mapping_name",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
      }   
   $migration->migrationOneTable($newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "mib_oid_id",
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miboids_id",
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "int(11) NOT NULL DEFAULT '0'");         
         $migration->changeField($newTable,
                                 "construct_device_id",
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_constructdevices_id",
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");         
         $migration->changeField($newTable,
                                 "oid_port_counter",
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_dyn",
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "itemtype",
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci NOT NULL");
         $migration->changeField($newTable,
                                 "vlan",
                                 "vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'"); 
      $migration->migrationOneTable($newTable);
         $migration->dropField($newTable, "mapping_type");
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");         
         $migration->addField($newTable,
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci NOT NULL");
         $migration->addField($newTable,
                                 "vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            array("plugin_fusioninventory_snmpmodelmiboids", "plugin_fusioninventory_snmpmodelconstructdevices_id", "plugin_fusioninventory_mappings_id"),
                            "unicity",
                            "UNIQUE");
      $migration->migrationOneTable($newTable);

      
      
   /*
    * Table glpi_plugin_fusioninventory_networkportconnectionlogs
    */
      $newTable = "glpi_plugin_fusioninventory_networkportconnectionlogs";
      $migration->renameTable("glpi_plugin_fusinvsnmp_networkportconnectionlogs", 
                              $newTable);
      
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                              "id",
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                              "date",
                              "date_mod",
                              "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                              "date_mod",
                              "date_mod",
                              "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                              "creation",
                              "creation",
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "FK_port_source",
                              "networkports_id_source",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "networkports_id_source",
                              "networkports_id_source",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "FK_port_destination",
                              "networkports_id_destination",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "networkports_id_destination",
                              "networkports_id_destination",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "plugin_fusioninventory_agentprocesses_id",
                              "plugin_fusioninventory_agentprocesses_id",
                              "int(11) NOT NULL DEFAULT '0'");      
         $migration->dropField($newTable, "process_number");
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable, 
                              "id", 
                              "int(11) NOT NULL AUTO_INCREMENT");      
         $migration->addField($newTable, 
                              "date_mod", 
                              "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");      
         $migration->addField($newTable, 
                              "creation", 
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "networkports_id_source", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "networkports_id_destination", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "plugin_fusioninventory_agentprocesses_id", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            array("networkports_id_source", "networkports_id_destination", "plugin_fusioninventory_agentprocesses_id"),
                            "networkports_id_source");        
         $migration->addKey($newTable,
                            "date_mod");  
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusioninventory_snmpmodelmibs
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodelmibs";
      $migration->renameTable("glpi_plugin_fusinvsnmp_modelmibs", 
                              $newTable);
      $migration->renameTable("glpi_plugin_tracker_mib_networking", 
                              $newTable);
      if (FieldExists($newTable, "FK_mib_label")) {
         $query = "UPDATE `".$newTable."`
            SET `FK_mib_label`='0' 
            WHERE `FK_mib_label` IS NULL";
         $DB->query($query);
      }
      if (FieldExists($newTable, "plugin_fusinvsnmp_miblabels_id")) {
         $query = "UPDATE `".$newTable."`
            SET `plugin_fusinvsnmp_miblabels_id`='0' 
            WHERE `plugin_fusinvsnmp_miblabels_id` IS NULL";
         $DB->query($query);
      }
      if (FieldExists($newTable, "plugin_fusinvsnmp_mibobjects_id")) {
         $query = "UPDATE `".$newTable."`
            SET `plugin_fusinvsnmp_mibobjects_id`='0' 
            WHERE `plugin_fusinvsnmp_mibobjects_id` IS NULL";
         $DB->query($query);
      }
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miboids_id",
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_counter",
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_dyn",
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "is_active",
                                 "is_active",
                                 "tinyint(1) NOT NULL DEFAULT '1'");      
      $migration->migrationOneTable($newTable);      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_model_infos",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_mib_label",
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_mib_oid",
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_mib_object",
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_counter",
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_dyn",
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "plugin_fusioninventory_mappings_id", 
                              "int(11) NOT NULL DEFAULT '0'");         
      $migration->migrationOneTable($newTable);
      
         // Update with mapping
         if (FieldExists($newTable, "mapping_type")) {
            $query = "SELECT * FROM `".$newTable."`
               GROUP BY `mapping_type`, `mapping_name`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $pFusioninventoryMapping = new PluginFusioninventoryMapping();
               $mapping = 0;
               $mapping_type = '';
               if ($data['mapping_type'] == '2') {
                  $mapping_type == 'NetworkEquipment';
               } else if ($data['mapping_type'] == '3') {
                  $mapping_type == 'Printer';
               }
               if ($mapping = $pFusioninventoryMapping->get($mapping_type, $data['mapping_name'])) {
                  $data['mapping_name'] = $mapping['id'];
                  $queryu = "UPDATE `".$newTable."`
                     SET `plugin_fusioninventory_mappings_id`='".$mapping['id']."',
                        `mapping_type`='".$mapping_type."'
                     WHERE `mapping_type`='".$data['mapping_type']."'
                        AND `mapping_name`='".$data['mapping_name']."'";
                  $DB->query($queryu);
               }
            }
         }
         $migration->dropField($newTable,
                               "mapping_type");
         $migration->dropField($newTable,
                               "mapping_name");
         $migration->dropField($newTable,
                               "name");
         $migration->dropField($newTable,
                               "itemtype");
         $migration->dropField($newTable,
                               "discovery_key");
         $migration->dropField($newTable,
                               "comment");
         $migration->changeField($newTable,
                                 "activation",
                                 "is_active",
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 "vlan",
                                 "vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'");

         $migration->dropKey($newTable, 
                             "FK_model_infos");
         $migration->dropKey($newTable, 
                             "FK_model_infos_2");
         $migration->dropKey($newTable, 
                             "FK_model_infos_3");
         $migration->dropKey($newTable, 
                             "FK_model_infos_4");
         $migration->dropKey($newTable, 
                             "oid_port_dyn");
         $migration->dropKey($newTable, 
                             "activation");      
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_snmpmodelmiboids",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "is_active",
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 "vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_snmpmodels_id");      
         $migration->addKey($newTable,
                            array("plugin_fusioninventory_snmpmodels_id", "oid_port_dyn"),
                            "plugin_fusioninventory_snmpmodels_id_2");      
         $migration->addKey($newTable,
                            array("plugin_fusioninventory_snmpmodels_id", "oid_port_counter", "plugin_fusioninventory_mappings_id"),
                            "plugin_fusioninventory_snmpmodels_id_3");
         $migration->addKey($newTable,
                            array("plugin_fusioninventory_snmpmodels_id", "plugin_fusioninventory_mappings_id"),
                            "plugin_fusioninventory_snmpmodels_id_4");
         $migration->addKey($newTable,
                            "oid_port_dyn");
         $migration->addKey($newTable,
                            "is_active"); 
         $migration->addKey($newTable,
                            "plugin_fusioninventory_mappings_id");
      $migration->migrationOneTable($newTable);

      
      
   /*
    * Table glpi_plugin_fusioninventory_snmpmodels
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodels";
      $migration->renameTable("glpi_plugin_fusinvsnmp_models", 
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable, 
                                 "id", 
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 "name", 
                                 "name", 
                                 "varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable, 
                                 "device_type", 
                                 "itemtype", 
                                 "varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable, 
                                 "itemtype", 
                                 "itemtype", 
                                 "varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable, 
                                 "discovery_key", 
                                 "discovery_key", 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 "comments", 
                                 "comment", 
                                 "text COLLATE utf8_unicode_ci"); 
         $migration->changeField($newTable, 
                                 "comment", 
                                 "comment", 
                                 "text COLLATE utf8_unicode_ci"); 
      $migration->migrationOneTable($newTable);
         $migration->dropField($newTable, "deleted");
         $migration->dropField($newTable, "FK_entities");
         $migration->dropField($newTable, "activation");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              "id", 
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                              "name", 
                              "varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable, 
                              "itemtype", 
                              "varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable, 
                              "discovery_key", 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              "comment", 
                              "text COLLATE utf8_unicode_ci");
         $migration->addKey($newTable,
                            "name");      
         $migration->addKey($newTable,
                            "itemtype");   
      $migration->migrationOneTable($newTable);
      
   
   
   
   
      
      
      
      
      
      
   /*
    * Add ESX module appear in version 2.4.0(0.80+1.0)
    */

      $agentmodule = new PluginFusioninventoryAgentmodule();
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` 
         WHERE `modulename`='ESX'
         LIMIT 1";
      $result = $DB->query($query);
      if ($DB->numrows($result) == '0') {
         $input = array();
         $input['plugins_id'] = $plugins_id;
         $input['modulename'] = "ESX";
         $input['is_active']  = 0;
         $input['exceptions'] = exportArrayToDB(array());
         $url= '';
         if (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
         }
         $input['url'] = PluginFusioninventoryCommunicationRest::getDefaultRestURL($_SERVER['HTTP_REFERER'], 
                                                                                    'fusioninventory', 
                                                                                    'esx');
         $agentmodule->add($input);
      } else {
         $data = $DB->fetch_assoc($result);
         $input = array();
         $input['id'] = $data['id'];
         $input['url'] = PluginFusioninventoryCommunicationRest::getDefaultRestURL($_SERVER['HTTP_REFERER'], 
                                                                                    'fusioninventory', 
                                                                                    'esx');
         $agentmodule->update($input);
      }
      
      

      /*
       * Update pci and usb ids
       */
      foreach (array('usbid.sql', 'pciid.sql') as $sql) {
         $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/$sql";
         $DBf_handle = fopen($DB_file, "rt");
         $sql_query = fread($DBf_handle, filesize($DB_file));
         fclose($DBf_handle);
         foreach ( explode(";\n", "$sql_query") as $sql_line) {
            if (Toolbox::get_magic_quotes_runtime()) $sql_line=Toolbox::stripslashes_deep($sql_line);
            if (!empty($sql_line)) {
               $DB->query($sql_line)/* or die($DB->error())*/;
            }
         }
      }
      
      
      
   /*
    * Update serialized sections to mysql_real_escape_string(htmlspecialchars_decode("data"))
    */
   if (!strstr($current_version, "+")) {// All version before 0.80+1.1 (new versioning)
      $computer = new Computer();
      $pfComputer = new PluginFusinvinventoryComputer();
      $migration->displayMessage("Convert computer inventory, may require some minutes");
      $pfLib = new PluginFusioninventoryInventoryComputerLib();
      $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $infoSections = array();
            $infoSections["externalId"] = '';
            $infoSections["sections"] = array();
            $infoSections["sectionsToModify"] = array();

            /* Variables for the recovery and changes in the serialized sections */
            $serializedSections = "";
            $arraySerializedSections = array();
            $arraySerializedSectionsTemp = array();

            $infoSections["externalId"] = $data['internal_id'];
            $serializedSections = htmlspecialchars_decode($data['serialized_sections1'].$data['serialized_sections2'].$data['serialized_sections3'], ENT_QUOTES); // Recover double quotes
            $arraySerializedSections = explode("\n", $serializedSections); // Recovering a table with one line per entry
            $previous_infosection = array();
            foreach ($arraySerializedSections as $valeur) {
               $arraySerializedSectionsTemp = explode("<<=>>", $valeur); // For each line, we create a table with data separated
               if (isset($arraySerializedSectionsTemp[0]) AND isset($arraySerializedSectionsTemp[1])) {
                  if ($arraySerializedSectionsTemp[0] != "" && $arraySerializedSectionsTemp[1] != "") { // that is added to infosections
                     $infoSections["sections"][$arraySerializedSectionsTemp[0]] = $arraySerializedSectionsTemp[1];
                  }
                  $previous_infosection = $arraySerializedSectionsTemp[0];
               } else if ($valeur != '') {
                  $infoSections["sections"][$previous_infosection] .= "\n".$valeur;
               }
            }
            $infoSections['sections'] = $pfLib->convertData($infoSections['sections']);

            $serializedSections = "";
            foreach($infoSections["sections"] as $key => $serializedSection) {
               if (!strstr($key, "ENVS/")
                     AND !strstr($key, "PROCESSES/")) {

                  $serializedSections .= $key."<<=>>".$serializedSection."
";
               }
            }
            if ($computer->getFromDB($data['computers_id'])) {
               $pfLib->_serializeIntoDB($data['internal_id'], $serializedSections);

               // * Add informations of BIOS (table glpi_plugin_fusinvinventory_computers)
               $input = array();
               $input['computers_id'] = $data['computers_id'];
               foreach($infoSections['sections'] as $name=>$section) {
                  $split = explode("/", $name);
                  if (($split[1] > 0) OR (strstr($split[1], 'd'))) {
                     $dataSection = unserialize($section);

                     if ($split[0] == 'BIOS') {
                        if (isset($dataSection['BDATE'])) {
                           $a_split = explode("/", $dataSection['BDATE']);
                           if (isset($a_split[1]) AND isset($a_split[2])) {
                              $input['bios_date'] = $a_split[2]."-".$a_split[0]."-".$a_split[1];
                           }
                        }
                        if (isset($dataSection['BVERSION'])) {
                           $input['bios_version'] = $dataSection['BVERSION'];
                        }
                        if (isset($dataSection['BMANUFACTURER'])) {
                           $input['bios_manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                                       $dataSection['BMANUFACTURER'],
                                                                                       $computer->fields['entities_id']);
                        }
                     }
                     if ($split[0] == 'HARDWARE') {
                        if (isset($dataSection['OSINSTALLDATE'])) {
                           $input['operatingsystem_installationdate'] = date("Y-m-d", $dataSection['OSINSTALLDATE']);
                        }
                        if (isset($dataSection['WINOWNER'])) {
                           $input['winowner'] = $dataSection['WINOWNER'];
                        }
                        if (isset($dataSection['WINCOMPANY'])) {
                           $input['wincompany'] = $dataSection['WINCOMPANY'];
                        }
                     }
                  }
               }
               $pfComputer->add($input);
            } else {
               $DB->query("DELETE FROM `glpi_plugin_fusinvinventory_libserialization`
                  WHERE `internal_id`='".$data['internal_id']."'");
            }
         }
      }
   }
      
      
      
   /*
    * Table Delete old table not used
    */
      if (TableExists("glpi_plugin_tracker_computers")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_computers`");
      }
      if (TableExists("glpi_plugin_tracker_connection_history")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_connection_history`");
      }
      if (TableExists("glpi_plugin_tracker_agents_processes")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_agents_processes`");
      }
      if (TableExists("glpi_plugin_tracker_config_snmp_history")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_config_snmp_history`");
      }
      if (TableExists("glpi_plugin_tracker_config_snmp_networking")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_config_snmp_networking`");
      }
      if (TableExists("glpi_plugin_tracker_config_snmp_printer")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_config_snmp_printer`");         
      }
      if (TableExists("glpi_plugin_tracker_config_snmp_script")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_config_snmp_script`");         
      }
      if (TableExists("glpi_plugin_tracker_connection_stats")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_connection_stats`");         
      }
      if (TableExists("glpi_plugin_tracker_discovery")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_discovery`");         
      }
      if (TableExists("glpi_plugin_tracker_errors")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_errors`");         
      }
      if (TableExists("glpi_plugin_tracker_model_infos")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_model_infos`");         
      }
      if (TableExists("glpi_plugin_tracker_processes")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_processes`");         
      }
      if (TableExists("glpi_plugin_tracker_processes_values")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_processes_values`");         
      }
      if (TableExists("glpi_plugin_fusioninventory_agents_errors")) {
         $DB->query("DROP TABLE `glpi_plugin_fusioninventory_agents_errors`");         
      }
      if (TableExists("glpi_plugin_fusioninventory_agents_processes")) {
         $DB->query("DROP TABLE `glpi_plugin_fusioninventory_agents_processes`");
      }
      if (TableExists("glpi_plugin_fusioninventory_computers")) {
         $DB->query("DROP TABLE `glpi_plugin_fusioninventory_computers`");
      }
   $migration->executeMigration();


   
   /*
    * Add WakeOnLan module appear in version 2.3.0
    */
   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` 
      WHERE `modulename`='WAKEONLAN'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "WAKEONLAN";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $agentmodule->add($input);
   }
   

   
   /*
    * Clean for port orphelin
    */
   //networkports with item_type = 0
   $NetworkPort = new NetworkPort();
   $NetworkPort_Vlan = new NetworkPort_Vlan();
   $NetworkPort_NetworkPort = new NetworkPort_NetworkPort();
   $a_networkports = $NetworkPort->find("`itemtype`=''");
   foreach ($a_networkports as $data) {
      if ($NetworkPort_NetworkPort->getFromDBForNetworkPort($data['id'])) {
         $NetworkPort_NetworkPort->delete($NetworkPort_NetworkPort->fields);
      }
      $a_vlans = $NetworkPort_Vlan->find("`networkports_id`='".$data['id']."'");
      foreach ($a_vlans as $a_vlan) {
         $NetworkPort_Vlan->delete($a_vlan);
      }
      $NetworkPort->delete($data, 1);
   }
   
   
   
   /*
    * Update networports to convert itemtype 5153 to PluginFusioninventoryUnknownDevice
    */
   $sql = "UPDATE `glpi_networkports`
      SET `itemtype`='PluginFusioninventoryUnknownDevice'
      WHERE `itemtype`='5153'";
   $DB->query($sql);
   $sql = "UPDATE `glpi_networkports`
      SET `itemtype`='PluginFusioninventoryTask'
      WHERE `itemtype`='5166'";
   $DB->query($sql);

   /*
    * Clean display preferences not used 
    */
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5150' ";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5160' ";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5161' ";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5163' ";
   $DB->query($sql);   
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5165' ";
   $DB->query($sql);
   

   
   /*
    * Update display preferences
    */
   changeDisplayPreference("5153", "PluginFusioninventoryUnknownDevice");
   changeDisplayPreference("5158", "PluginFusioninventoryAgent");
   changeDisplayPreference("PluginFusinvinventoryBlacklist", "PluginFusioninventoryInventoryComputerBlacklist");
   
   /*
    * Convert taskjob definition from PluginFusinvsnmpIPRange to PluginFusioninventoryIPRange
    */
   $query = "SELECT * FROM `glpi_plugin_fusioninventory_taskjobs`";
   $result = $DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      $a_defs = importArrayFromDB($data['definition']);
      foreach ($a_defs as $num=>$a_def) {
         if (key($a_def) == 'PluginFusinvsnmpIPRange') {
            $a_defs[$num] = array('PluginFusioninventoryIPRange'=>current($a_def));
         }
      }
      $queryu = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
         SET `definition`='".exportArrayToDB($a_defs)."'
         WHERE `id`='".$data['id']."'";
      $DB->query($queryu);
   }
   
   
   
   /*
    *  Add default rules
    */
   if (TableExists("glpi_plugin_tracker_config_discovery")) {
      $migration->displayMessage("Create rules");
      $pfSetup = new PluginFusioninventorySetup();
      $pfSetup->initRules();
   }   
   

   $plugins_id = PluginFusioninventoryModule::getModuleId("fusioninventory");
   PluginFusioninventoryProfile::changeProfile($plugins_id);

   /*
    *  Manage configuration of plugin
    */
      $config = new PluginFusioninventoryConfig();
      $PluginFusioninventorySetup = new PluginFusioninventorySetup();
      $users_id = $PluginFusioninventorySetup->createFusionInventoryUser();
      $a_input = array();
      $a_input['ssl_only'] = 0;
      $a_input['delete_task'] = 20;
      $a_input['inventory_frequence'] = 24;
      $a_input['agent_port'] = 62354;
      $a_input['extradebug'] = 0;
      $a_input['users_id'] = $users_id;
      foreach ($a_input as $type=>$value) {
         if (is_null($config->getValue($plugins_id, $type))) {
            $config->addValues($plugins_id, array($type=>$value));
         }
      }
     $DB->query("DELETE FROM `glpi_plugin_fusioninventory_configs`
        WHERE `plugins_id`='0'");
      
      
      $a_input = array();
      $a_input['version'] = PLUGIN_FUSIONINVENTORY_VERSION;
      if (is_null($config->getValue($plugins_id, "ssl_only", ''))) {
         $a_input['ssl_only'] = 0;
      }
      if (isset($prepare_Config['ssl_only'])) {
         $a_input['ssl_only'] = $prepare_Config['ssl_only'];
      }
      if (is_null($config->getValue($plugins_id, "delete_task", ''))) {
         $a_input['delete_task'] = 20;
      }
      if (is_null($config->getValue($plugins_id, "inventory_frequence", ''))) {
         $a_input['inventory_frequence'] = 24;
      }
      if (is_null($config->getValue($plugins_id, "agent_port", ''))) {
         $a_input['agent_port'] = 62354;
      }
      if (is_null($config->getValue($plugins_id, "extradebug", ''))) {
         $a_input['extradebug'] = 0;
      }
      if (is_null($config->getValue($plugins_id, "users_id", ''))) {
         $a_input['users_id'] = 0;
      }
      $config->addValues($plugins_id, $a_input);
      
      $pfSetup = new PluginFusioninventorySetup();
      $users_id = $pfSetup->createFusionInventoryUser();
      $query = "UPDATE `glpi_plugin_fusioninventory_configs`
                         SET `value`='".$users_id."'
                  WHERE `type`='users_id'";
      $DB->query($query);
      
      // Update fusinvinventory _config values to this plugin
      $input = array();
      $input['import_monitor']         = 2;
      $input['import_printer']         = 2;
      $input['import_peripheral']      = 2;
      $input['import_software']        = 1;
      $input['import_volume']          = 1;
      $input['import_antivirus']       = 1;
      $input['import_registry']        = 1;
      $input['import_process']         = 1;
      $input['import_vm']              = 1;
      $input['component_processor']    = 1;
      $input['component_memory']       = 1;
      $input['component_harddrive']    = 1;
      $input['component_networkcard']  = 1;
      $input['component_graphiccard']  = 1;
      $input['component_soundcard']    = 1;
      $input['component_drive']        = 1;
      $input['component_networkdrive'] = 1;
      $input['component_control']      = 1;
      $input['transfers_id_auto']      = 1;
      $input['states_id_default']      = 0;
      $input['location']               = 0;
      $input['group']                  = 0;
      $input['component_networkcardvirtual'] = 1;
      foreach ($input as $key=>$value) {
         $sql = "UPDATE `glpi_plugin_fusioninventory_configs`
            SET `plugins_id`='".$plugins_id."',`module`='inventory'
            WHERE `type`='".$key."'";
         $DB->query($sql);
      }
      foreach ($input as $key => $value) {
         $config->addValues($plugins_id, array($key => $value), 'inventory');
      }
      
      
      
   /*
    * Remove / at the end of printers (bugs in older versions of agents.
    */
      $printer = new Printer();
      $query = "SELECT * FROM `glpi_printers`
         WHERE `serial` LIKE '%/' ";
      $result=$DB->query($query);
      while ($data = $DB->fetch_array($result)) {
         $cleanSerial = preg_replace('/\/$/', '', $data['serial']);
         $querynb = "SELECT * FROM `glpi_printers`
            WHERE `serial`='".$cleanSerial."'
            LIMIT 1";
         $resultnb=$DB->query($querynb);
         if ($DB->numrows($resultnb) == '0') {
            $input = array();
            $input['id'] = $data['id'];
            $input["serial"] = $cleanSerial;
            $printer->update($input);
         }
      }
      
      
      
   /*
    * Update blacklist
    */
   $input = array();
   $input['03000200-0400-0500-0006-000700080009'] = '2';
   $input['6AB5B300-538D-1014-9FB5-B0684D007B53'] = '2';
   $input['01010101-0101-0101-0101-010101010101'] = '2';
   $input['20:41:53:59:4e:ff'] = '3';
   $input['02:00:4e:43:50:49'] = '3';
   $input['e2:e6:16:20:0a:35'] = '3';
   $input['d2:0a:2d:a0:04:be'] = '3';
   $input['00:a0:c6:00:00:00'] = '3';
   $input['d2:6b:25:2f:2c:e7'] = '3';
   $input['33:50:6f:45:30:30'] = '3';
   $input['0a:00:27:00:00:00'] = '3';
   $input['00:50:56:C0:00:01'] = '3';
   $input['00:50:56:C0:00:02'] = '3';
   $input['00:50:56:C0:00:03'] = '3';
   $input['00:50:56:C0:00:04'] = '3';
   $input['00:50:56:C0:00:08'] = '3';
   $input['FE:FF:FF:FF:FF:FF'] = '3';
   $input['00:00:00:00:00:00'] = '3';
   $input['00:0b:ca:fe:00:00'] = '3';
   $input['02:80:37:EC:02:00'] = '3';
   $input['MB-1234567890'] = '1';
   $input['Not Specified'] = '1';
   $input['OEM_Serial'] = '1';
   $input['SystemSerialNumb'] = '1';
   $input['Not'] = '2';
   foreach ($input as $value=>$type) {
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_inventorycomputerblacklists`
         WHERE `plugin_fusioninventory_criterium_id`='".$type."'
          AND `value`='".$value."'";
      $result=$DB->query($query);
      if ($DB->numrows($result) == '0') {
         $query = "INSERT INTO `glpi_plugin_fusioninventory_inventorycomputerblacklists` 
            (`plugin_fusioninventory_criterium_id`, `value`) VALUES
            ( '".$type."', '".$value."')";
         $DB->query($query);         
      }
   }
      
      
      
   /*
    * Add Crontask if not exist
    */
   $crontask = new CronTask();
   if (!$crontask->getFromDBbyName('PluginFusioninventoryTaskjob', 'taskscheduler')) {
      CronTask::Register('PluginFusioninventoryTaskjob', 'taskscheduler', '60', 
                         array('mode' => 2, 'allowmode' => 3, 'logs_lifetime'=> 30));
   }
   if ($crontask->getFromDBbyName('PluginFusioninventoryTaskjobstate', 'cleantaskjob')
           AND $crontask->getFromDBbyName('PluginFusioninventoryTaskjobstatus', 'cleantaskjob')) {
      $crontask->getFromDBbyName('PluginFusioninventoryTaskjobstatus', 'cleantaskjob');
      $crontask->delete($crontask->fields);
   }
   
   if ($crontask->getFromDBbyName('PluginFusioninventoryTaskjobstatus', 'cleantaskjob')) {
      $query = "UPDATE `glpi_crontasks` SET `itemtype`='PluginFusioninventoryTaskjobstate'
         WHERE `itemtype`='PluginFusioninventoryTaskjobstatus'";
      $DB->query($query);
   }
   if (!$crontask->getFromDBbyName('PluginFusioninventoryTaskjobstate', 'cleantaskjob')) {
      Crontask::Register('PluginFusioninventoryTaskjobstate', 'cleantaskjob', (3600 * 24), 
                         array('mode' => 2, 'allowmode' => 3, 'logs_lifetime' => 30));
   }
   

//   $pfIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
//   $pfIgnoredimportdevice->install();   

   
   
   // Delete data in glpi_logs (agent problem => ticket http://forge.fusioninventory.org/issues/1546)
   // ** Token
   $query = "DELETE FROM `glpi_logs`
      WHERE `itemtype`='PluginFusioninventoryAgent'
         AND `id_search_option`='9'";
   $DB->query($query);
   // ** Last contact
   $query = "DELETE FROM `glpi_logs`
      WHERE `itemtype`='PluginFusioninventoryAgent'
         AND `id_search_option`='4'";
   $DB->query($query);
   // ** Version
   $query = "DELETE FROM `glpi_logs`
      WHERE `itemtype`='PluginFusioninventoryAgent'
         AND `id_search_option`='8'
         AND `old_value`=`new_value`";
   $DB->query($query);
      
   /*
    * Import / update SNMP models
    */
  PluginFusioninventorySnmpmodel::importAllModels();
   
}



function plugin_fusioninventory_displayMigrationMessage ($id, $msg="") {
   global $LANG;
   static $created=0;
   static $deb;

   if ($created != $id) {
      if (empty($msg)) $msg=$LANG['rulesengine'][90];
      echo "<div id='migration_message_$id'><p class='center'>$msg</p></div>";
      $created = $id;
      $deb = time();
   } else {
      if (empty($msg)) $msg=$LANG['rulesengine'][91];
      $fin = time();
      $tps = Html::timestampToString($fin-$deb);
      echo "<script type='text/javascript'>document.getElementById('migration_message_$id').innerHTML = '<p class=\"center\">$msg ($tps)</p>';</script>\n";
   }
   Html::glpi_flush();
}



function changeDisplayPreference($olditemtype, $newitemtype) {
   global $DB;
   
   $query = "SELECT *,count(`id`) as `cnt` FROM `glpi_displaypreferences` 
   WHERE (`itemtype` = '".$newitemtype."'
   OR `itemtype` = '".$olditemtype."')
   group by `users_id`, `num`";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if ($data['cnt'] > 1) {
         $queryd = "DELETE FROM `glpi_displaypreferences`
            WHERE `id`='".$data['id']."'";
         $DB->query($queryd);
      }
   }
   
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='".$newitemtype."'
      WHERE `itemtype`='".$olditemtype."' ";
   $DB->query($sql);   
}

?>
