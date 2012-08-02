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
   
   if (!class_exists('PluginFusioninventoryModule')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/module.class.php");
   }
   
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
   $migration->renameTable("glpi_plugin_fusioninventory_construct_mibs", "glpi_plugin_fusinvsnmp_constructdevice_miboids");
   $migration->renameTable("glpi_plugin_fusioninventory_construct_walks", "glpi_plugin_fusinvsnmp_constructdevicewalks");
   $migration->renameTable("glpi_plugin_fusioninventory_networking", "glpi_plugin_fusinvsnmp_networkequipments");
   $migration->renameTable("glpi_plugin_fusioninventory_networking_ifaddr", "glpi_plugin_fusinvsnmp_networkequipmentips");
   $migration->renameTable("glpi_plugin_fusioninventory_printers", "glpi_plugin_fusinvsnmp_printers");
   $migration->renameTable("glpi_plugin_fusioninventory_printers_cartridges", "glpi_plugin_fusinvsnmp_printercartridges");
   $migration->renameTable("glpi_plugin_fusioninventory_printers_history", "glpi_plugin_fusinvsnmp_printerlogs");
   $migration->renameTable("glpi_plugin_fusioninventory_model_infos", "glpi_plugin_fusinvsnmp_models");
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

   
   
   /*
    * Add WakeOnLan module appear in version 2.3.0
    */
   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` 
      WHERE `modulename`='WAKEONLAN'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
      }
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
      if (!class_exists('PluginFusioninventorySetup')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
      }
      $pfSetup = new PluginFusioninventorySetup();
      $pfSetup->initRules();
   }   
   

   $plugins_id = PluginFusioninventoryModule::getModuleId("fusioninventory");
   include_once(GLPI_ROOT."/plugins/fusioninventory/inc/profile.class.php");
   PluginFusioninventoryProfile::changeProfile($plugins_id);

   /*
    *  Manage configuration of plugin
    */
      include_once(GLPI_ROOT."/plugins/fusioninventory/inc/config.class.php");
      $config = new PluginFusioninventoryConfig();
      if (!class_exists('PluginFusioninventorySetup')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
      }
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
      if (is_null($config->getValue($plugins_id, "ssl_only"))) {
         $a_input['ssl_only'] = 0;
      }
      if (isset($prepare_Config['ssl_only'])) {
         $a_input['ssl_only'] = $prepare_Config['ssl_only'];
      }
      if (is_null($config->getValue($plugins_id, "delete_task"))) {
         $a_input['delete_task'] = 20;
      }
      if (is_null($config->getValue($plugins_id, "inventory_frequence"))) {
         $a_input['inventory_frequence'] = 24;
      }
      if (is_null($config->getValue($plugins_id, "agent_port"))) {
         $a_input['agent_port'] = 62354;
      }
      if (is_null($config->getValue($plugins_id, "extradebug"))) {
         $a_input['extradebug'] = 0;
      }
      if (is_null($config->getValue($plugins_id, "users_id"))) {
         $a_input['users_id'] = 0;
      }
      $config->addValues($plugins_id, $a_input);
      
      if (!class_exists('PluginFusioninventorySetup')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
      }
      $pfSetup = new PluginFusioninventorySetup();
      $users_id = $pfSetup->createFusionInventoryUser();
      $query = "UPDATE `glpi_plugin_fusioninventory_configs`
                         SET `value`='".$users_id."'
                  WHERE `type`='users_id'";
      $DB->query($query);
      
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
   

   if (!class_exists('PluginFusioninventoryIgnoredimportdevice')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/ignoredimportdevice.class.php");
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