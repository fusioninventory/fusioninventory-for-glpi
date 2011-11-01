<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David Durieux
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
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
   
   // update from current_version to last case version + 1
//   switch ($current_version){
//      case "1.0.0":
//         include("update_100_110.php");
//         update100to110();
//      case "1.1.0":
//         include("update_110_200.php");
//         update110to200();
//      case "2.0.0":
//         include("update_200_201.php");
//         update200to201();
//      case "2.0.1":
//         include("update_201_202.php");
//         update201to202();
//      case "2.0.2":
//         include("update_202_210.php");
//         update202to210();
//      case "2.1.0":
//         include("update_210_211.php");
//         update210to211();
//      case "2.1.1":
//         include("update_211_212.php");
//         update211to212();
//      case "2.1.2":
//         include("update_212_213.php");
//         update212to213();
//      case "2.1.3":
//         include("update_213_220.php");
//         update213to220();
//      case "2.2.0":
//         include("update_220_221.php");
//         update220to221($migrationname);
//      case "2.2.1":
//      case "2.2.2":
//      case "2.2.3":
//      case "2.2.4":
//      case "2.2.5":
//         include("update_221_230.php");
//         update221to230($migrationname);
//      case "2.3.0":
//      case "2.3.1":
//         include("update_231_232.php");
//         update231to232();
//      case "2.3.2":
//         include("update_232_233.php");
//         update232to233();
//      case "2.3.3":
//      case "2.3.4":
//      case "2.3.5":
//      case "2.3.6":
//      case "2.3.7":
//      case "2.3.8":
//      case "2.3.9":
//      case "2.3.10":
//      case "2.3.11":
//         include("update_232_240.php");
//         update232to240();
//      case "2.4.0":
//         include("update_240_08011.php");
//         update240to080011();
//
//   }
   
   $migration = new $migrationname($current_version);
   $prepare_task = array();
   $prepare_rangeip = array();
   $prepare_Config = array();
   
   
   // TODO remove
//   $migration = new Migration($current_version);
   // END TODO remove
   
   
      
      
   // ** Manage glpi_plugin_fusioninventory_agents
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
      } else if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_agents` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `entities_id` int(11) NOT NULL DEFAULT '0',
                     `is_recursive` tinyint(1) NOT NULL DEFAULT '1',
                     `name` varchar(255) DEFAULT NULL,
                     `last_contact` datetime DEFAULT NULL,
                     `version` varchar(255) DEFAULT NULL,
                     `lock` int(1) NOT NULL DEFAULT '0',
                     `device_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'XML <DEVICE_ID> TAG VALUE',
                     `items_id` int(11) NOT NULL DEFAULT '0',
                     `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `useragent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                     PRIMARY KEY (`id`),
                     KEY `name` (`name`),
                     KEY `device_id` (`device_id`),
                     KEY `item` (`itemtype`,`items_id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);         
      }
      $migration->renameTable("glpi_plugin_tracker_agents", $newTable);
      $migration->changeField($newTable,
                              "ID",
                              "id",
                              "int(11) NOT NULL");
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
                              "device_type",
                              "itemtype",
                              "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");

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


      $migration->addField($newTable, 
                           "entities_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "is_recursive", 
                           "tinyint(1) NOT NULL DEFAULT '1'");
      $migration->addField($newTable, 
                           "useragent", 
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->addField($newTable, 
                           "itemtype", 
                           "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->addField($newTable, 
                           "items_id", 
                           "int(11) NOT NULL DEFAULT '0'");

      $migration->dropKey($newTable, 
                          "key");

      $migration->addKey($newTable,
                         "device_id");
      $migration->addKey($newTable,
                         array("itemtype", "items_id"),
                         "item");
      $migration->addKey($newTable,
                         "items_id");
      
      
   // ** glpi_plugin_fusioninventory_agentmodules
      $newTable = "glpi_plugin_fusioninventory_agentmodules";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_agentmodules` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `plugins_id` int(11) NOT NULL DEFAULT '0',
                        `modulename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                        `is_active` int(1) NOT NULL DEFAULT '0',
                        `exceptions` text DEFAULT NULL COMMENT 'array(agent_id)',
                        `entities_id` int(11) NOT NULL DEFAULT '-1',
                        `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `unicity` (`plugins_id`, `modulename`),
                        KEY `is_active` (`is_active`),
                        KEY `entities_id` (`entities_id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      } else {
         $migration->changeField($newTable, 'id', 'id', "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 'plugins_id', 'plugins_id', "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'modulename', 'modulename', "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 'is_active', 'is_active', "int(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'exceptions', 'exceptions', "text COMMENT 'array(agent_id)'");
         $migration->changeField($newTable, 'entities_id', 'entities_id', "int(11) NOT NULL DEFAULT '-1'");
         $migration->changeField($newTable, 'url', 'url', "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable, 'url', "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
      }
      
      
      
   // ** glpi_plugin_fusioninventory_configs
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
         $DB->query("DROP TABLE glpi_plugin_tracker_config");
      }
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_configs` (
                     `id` int(1) NOT NULL AUTO_INCREMENT,
                     `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                     `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `plugins_id` int(11) NOT NULL DEFAULT '0',
                     PRIMARY KEY (`id`),
                     UNIQUE KEY `unicity` (`type`, `plugins_id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
      

   
   // ** Manage glpi_plugin_fusioninventory_ipranges
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
      $newTable = "glpi_plugin_fusioninventory_ipranges";
      $migration->renameTable("glpi_plugin_tracker_rangeip", 
                              $newTable);
      $migration->renameTable("glpi_plugin_fusinvsnmp_ipranges", 
                              $newTable);
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
   
      
      
      
   // ** glpi_plugin_fusioninventory_unknowndevices
      $newTable = "glpi_plugin_fusioninventory_unknowndevices";
      if (TableExists('glpi_plugin_tracker_unknown_device')) {
         $migration->renameTable("glpi_plugin_tracker_unknown_device", $newTable);
      } else if (!TableExists($newTable)) {
         $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_unknowndevices` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `date_mod` datetime DEFAULT NULL,
                     `entities_id` int(11) NOT NULL DEFAULT '0',
                     `locations_id` int(11) NOT NULL DEFAULT '0',
                     `is_deleted` smallint(6) NOT NULL DEFAULT '0',
                     `serial` varchar(255) DEFAULT NULL,
                     `otherserial` varchar(255) DEFAULT NULL,
                     `contact` varchar(255) DEFAULT NULL,
                     `domain` int(11) NOT NULL DEFAULT '0',
                     `comment` text DEFAULT NULL,
                     `item_type` varchar(255) DEFAULT NULL,
                     `accepted` int(1) NOT NULL DEFAULT '0',
                     `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
                     `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `hub` int(1) NOT NULL DEFAULT '0',
                     `states_id` int(11) NOT NULL DEFAULT '0',
                     PRIMARY KEY (`id`),
                     KEY `entities_id` (`entities_id`),
                     KEY `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`),
                     KEY `is_deleted` (`is_deleted`),
                     KEY `date_mod` (`date_mod`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      } else {    
         $migration->changeField($newTable, 'id', 'id', "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 'FK_entities', 'entities_id', "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'location', 'locations_id', "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'deleted', 'is_deleted', "smallint(6) NOT NULL DEFAULT '0'");
         
         $migration->changeField($newTable, 'name', 'name', 'varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL');
         $migration->changeField($newTable, 'date_mod', 'date_mod', 'datetime DEFAULT NULL');
         $migration->changeField($newTable, 'entities_id', 'entities_id', "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'locations_id', 'locations_id', "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'is_deleted', 'is_deleted', "smallint(6) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'serial', 'serial', "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 'otherserial', 'otherserial', "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 'contact', 'contact', "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 'domain', 'domain', "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'comment', 'comment', "text DEFAULT NULL");
         $migration->changeField($newTable, 'item_type', 'item_type', "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 'accepted', 'accepted', "int(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'plugin_fusioninventory_agents_id', 'plugin_fusioninventory_agents_id', "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'ip', 'ip', "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 'mac', 'mac', "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 'hub', 'hub', "int(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 'states_id', 'states_id', "int(11) NOT NULL DEFAULT '0'");
      }
      
   
   // ** glpi_plugin_fusioninventory_credentials
      $newTable = "glpi_plugin_fusioninventory_credentials";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE  `glpi_plugin_fusioninventory_credentials` (
                     `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                     `entities_id` int(11) NOT NULL DEFAULT '0',
                     `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
                     `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                     `username` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                     `password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                     `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
                     `date_mod` DATETIME NOT NULL ,
                     `itemtype` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                     PRIMARY KEY (  `id` )
                     ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
         $DB->query($query);
      }

   // ** glpi_plugin_fusioninventory_credentialips
      $newTable = "glpi_plugin_fusioninventory_credentialips";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE  `glpi_plugin_fusioninventory_credentialips` (
                     `id` int(11) NOT NULL AUTO_INCREMENT ,
                     `entities_id` int(11) NOT NULL DEFAULT '0',
                     `plugin_fusioninventory_credentials_id` int(11) NOT NULL DEFAULT  '0',
                     `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                     `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
                     `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                     `date_mod` datetime NOT NULL ,
                     PRIMARY KEY (  `id` )
                     ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci";
         $DB->query($query);
      }
      
  
   // ** glpi_plugin_fusioninventory_locks
      $newTable = "glpi_plugin_fusioninventory_locks";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_locks` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `tablename` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                     `items_id` int(11) NOT NULL DEFAULT '0',
                     `tablefields` text DEFAULT NULL,
                     PRIMARY KEY (`id`),
                     KEY `tablename` (`tablename`),
                     KEY `items_id` (`items_id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      } else {
         $migration->changeField($newTable, 'items_id', 'items_id', "int(11) NOT NULL DEFAULT '0'");
      }
      
      
      
   // ** glpi_plugin_fusioninventory_tasks
      $newTable = "glpi_plugin_fusioninventory_tasks";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_tasks` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `entities_id` int(11) NOT NULL DEFAULT '-1',
                    `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                    `date_creation` datetime DEFAULT NULL,
                    `comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
                    `is_active` int(1) NOT NULL DEFAULT '0',
                    `communication` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'push',
                    `permanent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                    `date_scheduled` datetime DEFAULT NULL,
                    `periodicity_count` int(6) NOT NULL DEFAULT '0',
                    `periodicity_type` varchar(255) DEFAULT NULL,
                    `execution_id` int(20) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id`),
                    KEY `entities_id` ( `entities_id` ),
                    KEY `is_active` ( `is_active` )
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
      
      
      
   // ** glpi_plugin_fusioninventory_taskjobs
      $newTable = "glpi_plugin_fusioninventory_taskjobs";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_taskjobs` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `plugin_fusioninventory_tasks_id` int(11) NOT NULL DEFAULT '0',
                    `entities_id` int(11) NOT NULL DEFAULT '-1',
                    `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                    `date_creation` datetime DEFAULT NULL,
                    `retry_nb` int(2) NOT NULL DEFAULT '0',
                    `retry_time` int(11) NOT NULL DEFAULT '0',
                    `plugins_id` int(11) NOT NULL DEFAULT '0',
                    `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                    `definition` text DEFAULT NULL COLLATE utf8_unicode_ci,
                    `action` text DEFAULT NULL COLLATE utf8_unicode_ci,
                    `comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
                    `users_id` int(11) NOT NULL DEFAULT '0',
                    `status` int(11) NOT NULL DEFAULT '0',
                    `rescheduled_taskjob_id` int(11) NOT NULL DEFAULT '0',
                    `statuscomments` text DEFAULT NULL COLLATE utf8_unicode_ci,
                    `periodicity_count` int(6) NOT NULL DEFAULT '0',
                    `periodicity_type` varchar(255) DEFAULT NULL,
                    `execution_id` int(20) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id`),
                    KEY `plugin_fusioninventory_tasks_id` (`plugin_fusioninventory_tasks_id`),
                    KEY `entities_id` (`entities_id`),
                    KEY `plugins_id` (`plugins_id`),
                    KEY `users_id` (`users_id`),
                    KEY `rescheduled_taskjob_id` (`rescheduled_taskjob_id`),
                    KEY `method` (`method`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
      
      

   // ** glpi_plugin_fusioninventory_taskjoblogs
      $newTable = "glpi_plugin_fusioninventory_taskjoblogs";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_taskjoblogs` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `plugin_fusioninventory_taskjobstatus_id` int(11) NOT NULL DEFAULT '0',
                    `date` datetime DEFAULT NULL,
                    `items_id` int(11) NOT NULL DEFAULT '0',
                    `itemtype` varchar(100) DEFAULT NULL,
                    `state` int(11) NOT NULL DEFAULT '0',
                    `comment` text DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `plugin_fusioninventory_taskjobstatus_id` (`plugin_fusioninventory_taskjobstatus_id`,`state`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
      
      

   // ** glpi_plugin_fusioninventory_taskjobstatus
      $newTable = "glpi_plugin_fusioninventory_taskjobstatus";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_taskjobstatus` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `plugin_fusioninventory_taskjobs_id` int(11) NOT NULL DEFAULT '0',
                    `items_id` int(11) NOT NULL DEFAULT '0',
                    `itemtype` varchar(100) DEFAULT NULL,
                    `state` int(11) NOT NULL DEFAULT '0',
                    `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
                    `specificity` varchar(255) DEFAULT NULL,
                    `uniqid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `plugin_fusioninventory_taskjobs_id` (`plugin_fusioninventory_taskjobs_id`),
                    KEY `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`,`state`),
                    KEY `plugin_fusioninventory_taskjob_2` (`plugin_fusioninventory_taskjobs_id`,`state`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
      
      
      

   // ** glpi_plugin_fusioninventory_profiles
      $newTable = "glpi_plugin_fusioninventory_profiles";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_profiles` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                     `right` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `plugins_id` int(11) NOT NULL DEFAULT '0',
                     `profiles_id` int(11) NOT NULL DEFAULT '0',
                     PRIMARY KEY (`id`),
                     UNIQUE KEY `unicity` (`type`, `plugins_id`, `profiles_id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
      
      
      
   // ** glpi_plugin_fusioninventory_mappings
      $newTable = "glpi_plugin_fusioninventory_mappings";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `glpi_plugin_fusioninventory_mappings` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `tablefield` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                     `locale` int(4) NOT NULL DEFAULT '0',
                     `shortlocale` int(4) DEFAULT NULL,
                     PRIMARY KEY (`id`),
                     KEY `name` (`name`),
                     KEY `itemtype` (`itemtype`),
                     KEY `table` (`table`),
                     KEY `tablefield` (`tablefield`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
      
      
      
   // ** Delete old table not used
      if (!TableExists("glpi_plugin_tracker_computers")) {
         $DB->query("DROP TABLE `glpi_plugin_tracker_computers`");
      }
      
      
      
   $migration->executeMigration();
   
   // TODO update itemtypes
   
   
   
   
   // ** Add default rules
      if (TableExists("glpi_plugin_tracker_config_discovery")) {
         // TODO Add the default rules...

      }
   
   

   $plugins_id = PluginFusioninventoryModule::getModuleId("fusioninventory");
   include_once(GLPI_ROOT."/plugins/fusioninventory/inc/profile.class.php");
   PluginFusioninventoryProfile::changeProfile($plugins_id);

   // ** Manage configuration of plugin
      include_once(GLPI_ROOT."/plugins/fusioninventory/inc/config.class.php");
      $config = new PluginFusioninventoryConfig();
      $a_input = array();
      $a_input['version'] = PLUGIN_FUSIONINVENTORY_VERSION;
      if (!$config->getValue($plugins_id, "ssl_only")) {
         $a_input['ssl_only'] = 0;
      }
      if (isset($prepare_Config['ssl_only'])) {
         $a_input['ssl_only'] = $prepare_Config['ssl_only'];
      }
      if (!$config->getValue($plugins_id, "delete_task")) {
         $a_input['delete_task'] = 20;
      }
      if (!$config->getValue($plugins_id, "inventory_frequence")) {
         $a_input['inventory_frequence'] = 24;
      }
      if (!$config->getValue($plugins_id, "agent_port")) {
         $a_input['agent_port'] = 62354;
      }
      if (!$config->getValue($plugins_id, "extradebug")) {
         $a_input['extradebug'] = 0;
      }
      if (!$config->getValue($plugins_id, "users_id")) {
         $a_input['users_id'] = 0;
      }
      $config->initConfig($plugins_id, $a_input);
   
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
		$tps = timestampToString($fin-$deb);
		echo "<script type='text/javascript'>document.getElementById('migration_message_$id').innerHTML = '<p class=\"center\">$msg ($tps)</p>';</script>\n";
	}
	glpi_flush();
}

?>