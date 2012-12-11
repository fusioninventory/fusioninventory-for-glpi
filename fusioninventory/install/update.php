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
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/snmpmodelimportexport.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/networkcommondbtm.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/snmpmodelmib.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/configlogfield.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/mapping.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/communicationrest.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/inventorycomputercomputer.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/inventorycomputerlib.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/profile.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/config.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/ignoredimportdevice.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/networkequipment.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/networkporttype.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/printer.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/printerlog.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/printerlogreport.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/snmpmodeldevice.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/toolbox.class.php");
   
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
                                           "threads_networkinventory"    => $data['nb_process_query'],
                                           "threads_networkdiscovery" => $data['nb_process_discovery']);
         }
      } else if (TableExists("glpi_plugin_tracker_agents")
                  AND FieldExists("glpi_plugin_tracker_agents",
                              "core_discovery")) {
         $query = "SELECT * FROM `glpi_plugin_tracker_agents`";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $prepare_agentConfig[] = array("name" => $data["name"],
                                           "lock" => $data['lock'],
                                           "threads_networkinventory"    => $data['threads_query'],
                                           "threads_networkdiscovery" => $data['threads_discovery']);
         }
      } else if (TableExists("glpi_plugin_fusioninventory_agents")) {
         if (FieldExists($newTable, "module_snmpquery")) {
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_agents`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $prepare_agentConfig[] = array("id" => $data["ID"],
                                              "threads_networkinventory"    => $data['threads_query'],
                                              "threads_networkdiscovery" => $data['threads_discovery'],
                                              "NETORKINVENTORY" => $data['module_snmpquery'],
                                              "NETWORKDISCOVERY" => $data['module_netdiscovery'],
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
                                 "itemtype",
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
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
         $migration->changeField($newTable,
                                 "threads_networkdiscovery",
                                 "threads_networkdiscovery",
                                 "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'");
         $migration->changeField($newTable,
                                 "threads_networkinventory",
                                 "threads_networkinventory",
                                 "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'");
         $migration->changeField($newTable,
                                 "senddico",
                                 "senddico",
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
                              "itemtype",
                              "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "token",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "useragent",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "tag",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "threads_networkdiscovery",
                                 "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'");
         $migration->addField($newTable,
                                 "threads_networkinventory",
                                 "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'");
         $migration->addField($newTable,
                                 "senddico",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
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
         $migration->dropField($newTable,
                               "url");

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
    * Add SNMPQUERY module if not present
    */
   $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
      SET `modulename`='NETWORKINVENTORY'
      WHERE `modulename`='SNMPQUERY'";
   $DB->query($query);
   
   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` WHERE `modulename`='NETWORKINVENTORY'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "NETWORKINVENTORY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $agentmodule->add($input);
   }

   /*
    * Add NETDISCOVERY module if not present
    */
   $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
      SET `modulename`='NETWORKDISCOVERY'
      WHERE `modulename`='NETDISCOVERY'";
   $DB->query($query);
   
   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` WHERE `modulename`='NETWORKDISCOVERY'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "NETWORKDISCOVERY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $agentmodule->add($input);
   }

  
   pluginFusioninventoryUpdatemapping();
   

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
      $migration->migrationOneTable($newTable);
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
         $migration->dropField($newTable,
                               "construct_device_id");
         $migration->dropField($newTable,
                               "log");
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
    * Table glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks";
      $migration->renameTable("glpi_plugin_fusioninventory_construct_walks",
                              $newTable);
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
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "log",
                                 "log",
                                 "varchar(255) DEFAULT NULL");          
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "construct_device_id",
                                 "plugin_fusioninventory_snmpmodelconstructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "plugin_fusioninventory_snmpmodelconstructdevices_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "log",
                              "varchar(255) DEFAULT NULL");          
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
                                 "text DEFAULT NULL");
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
                              "text DEFAULT NULL");
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
         $migration->changeField($newTable,
                                 "sysdescr",
                                 "sysdescr",
                                 "text DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_configsecurities_id",
                                 "plugin_fusioninventory_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "plugin_fusioninventory_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
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
         $migration->addField($newTable,
                                 "sysdescr",
                                 "text DEFAULT NULL");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_configsecurities_id",
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
      $migration->migrationOneTable($newTable);
         $migration->dropField($newTable,
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

      
   pluginFusioninventorychangeDisplayPreference("5153", "PluginFusioninventoryUnknownDevice");
   pluginFusioninventorychangeDisplayPreference("5158", "PluginFusioninventoryAgent");

      
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
    * Table glpi_plugin_fusioninventory_inventorycomputerantiviruses
    */
      $newTable = "glpi_plugin_fusioninventory_inventorycomputerantiviruses";
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
    * Table glpi_plugin_fusioninventory_snmpmodeldevices
    */
      $newTable = "glpi_plugin_fusioninventory_snmpmodeldevices";
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
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "sysdescr",
                                 "sysdescr",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "plugin_fusioninventory_snmpmodels_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "sysdescr",
                              "text COLLATE utf8_unicode_ci DEFAULT NULL");
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
               $pfMapping = new PluginFusioninventoryMapping();
               $mapping = 0;
               if ($mapping = $pfMapping->get("NetworkEquipment", $data['field'])) {
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
                  $pfMapping = new PluginFusioninventoryMapping();
                  $mapping = 0;
                  $mapping_type = '';
                  if ($data['itemtype'] == 'glpi_networkequipments') {
                     $mapping_type = 'NetworkEquipment';
                  } else if ($data['itemtype'] == 'glpi_printers') {
                     $mapping_type = 'Printer';
                  }
                  if ($mapping = $pfMapping->get($mapping_type, $data['mapping_name'])) {
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
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miboids_id",
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
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
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
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
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "plugin_fusioninventory_snmpmodelmiblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miboids_id",
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "plugin_fusioninventory_snmpmodelmibobjects_id",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodelmiblabels_id",
                                 "plugin_fusioninventory_snmpmodelmiblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodelmibobjects_id",
                                 "plugin_fusioninventory_snmpmodelmibobjects_id",
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
                                 "plugin_fusioninventory_snmpmodelmiblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "plugin_fusioninventory_snmpmodelmiblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_mib_oid",
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_mib_object",
                                 "plugin_fusioninventory_snmpmodelmibobjects_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "plugin_fusioninventory_snmpmodelmibobjects_id",
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
               $pfMapping = new PluginFusioninventoryMapping();
               $mapping = 0;
               $mapping_type = '';
               if ($data['mapping_type'] == '2') {
                  $mapping_type == 'NetworkEquipment';
               } else if ($data['mapping_type'] == '3') {
                  $mapping_type == 'Printer';
               }
               if ($mapping = $pfMapping->get($mapping_type, $data['mapping_name'])) {
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
                                 "plugin_fusioninventory_snmpmodelmiblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_snmpmodelmiboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_snmpmodelmibobjects_id",
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
    * Table glpi_plugin_fusioninventory_networkporttypes
    */
      $newTable = "glpi_plugin_fusioninventory_networkporttypes";
      $migration->renameTable("glpi_plugin_fusinvsnmp_networkporttypes",
                              $newTable);
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
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "number",
                                 "number",
                                 "int(4) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "othername",
                                 "othername",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "import",
                                 "import",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "name",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "number",
                              "int(4) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "othername",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "import",
                              "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);


      
   /*
    * Table glpi_plugin_fusioninventory_printers
    */
      $newTable = "glpi_plugin_fusioninventory_printers";
      $migration->renameTable("glpi_plugin_fusinvsnmp_printers",
                              $newTable);

      $migration->renameTable("glpi_plugin_tracker_printers",
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "printers_id",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "sysdescr",
                                 "sysdescr",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "plugin_fusioninventory_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_configsecurities_id",
                                 "plugin_fusioninventory_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "frequence_days",
                                 "frequence_days",
                                 "int(5) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 "last_fusioninventory_update",
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_printers",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_model_infos",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_snmp_connection",
                                 "plugin_fusioninventory_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "last_tracker_update",
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->dropKey($newTable,
                             "FK_printers");
         $migration->dropKey($newTable,
                             "FK_snmp_connection");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "sysdescr",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "frequence_days",
                                 "int(5) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->addKey($newTable,
                            "plugin_fusinvsnmp_configsecurities_id");
         $migration->addKey($newTable,
                            "printers_id");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_snmpmodels_id");
      $migration->migrationOneTable($newTable);

      
      
   /*
    * Table glpi_plugin_fusioninventory_printerlogs
    */
      $newTable = "glpi_plugin_fusioninventory_printerlogs";
      $migration->renameTable("glpi_plugin_fusinvsnmp_printerlogs",
                              $newTable);
      $migration->renameTable("glpi_plugin_tracker_printers_history",
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "printers_id",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "date",
                                 "date",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                                 "pages_total",
                                 "pages_total",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_n_b",
                                 "pages_n_b",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_color",
                                 "pages_color",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_recto_verso",
                                 "pages_recto_verso",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "scanned",
                                 "scanned",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_total_print",
                                 "pages_total_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_n_b_print",
                                 "pages_n_b_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_color_print",
                                 "pages_color_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_total_copy",
                                 "pages_total_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_n_b_copy",
                                 "pages_n_b_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_color_copy",
                                 "pages_color_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_total_fax",
                                 "pages_total_fax",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_printers",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "date",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->addField($newTable,
                                 "pages_total",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_n_b",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_color",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_recto_verso",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "scanned",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_total_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_n_b_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_color_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_total_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_n_b_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_color_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_total_fax",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            array("printers_id", "date"),
                            "printers_id");
      $migration->migrationOneTable($newTable);

      
      
   /*
    *  glpi_plugin_fusioninventory_printercartridges
    */
      $newTable = "glpi_plugin_fusioninventory_printercartridges";
      $migration->renameTable("glpi_plugin_fusinvsnmp_printercartridges",
                              $newTable);
      $migration->renameTable("glpi_plugin_tracker_printers_cartridges",
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` bigint(100) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "bigint(100) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "printers_id",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "cartridges_id",
                                 "cartridges_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "state",
                                 "state",
                                 "int(3) NOT NULL DEFAULT '100'");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "bigint(100) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_printers",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_cartridges",
                                 "cartridges_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "plugin_fusioninventory_mappings_id",
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);

         // Update with mapping
         if (FieldExists($newTable, "object_name")) {
            $pcartridge = new PluginFusioninventoryNetworkCommonDBTM($newTable);
            $query = "SELECT * FROM `".$newTable."`
               GROUP BY `object_name`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $pfMapping = new PluginFusioninventoryMapping();
               $mapping = 0;
               if ($mapping = $pfMapping->get("Printer", $data['object_name'])) {
                  $DB->query("UPDATE `".$newTable."`
                     SET `plugin_fusioninventory_mappings_id`='".$mapping['id']."'
                        WHERE `object_name`='".$data['object_name']."'");
               }
            }
         }
         $migration->dropField($newTable,
                               "object_name");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "bigint(100) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "cartridges_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "state",
                                 "int(3) NOT NULL DEFAULT '100'");
         $migration->addKey($newTable,
                            "printers_id");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_mappings_id");
         $migration->addKey($newTable,
                            "cartridges_id");
      $migration->migrationOneTable($newTable);

      
      
   /*
    * glpi_plugin_fusioninventory_networkports
    */
      $newTable = "glpi_plugin_fusioninventory_networkports";
      $migration->renameTable("glpi_plugin_fusinvsnmp_networkports",
                              $newTable);
      $migration->renameTable("glpi_plugin_tracker_networking_ports",
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "networkports_id",
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifmtu",
                                 "ifmtu",
                                 "int(8) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifspeed",
                                 "ifspeed",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifinternalstatus",
                                 "ifinternalstatus",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "ifconnectionstatus",
                                 "ifconnectionstatus",
                                 "int(8) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "iflastchange",
                                 "iflastchange",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "ifinoctets",
                                 "ifinoctets",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifinerrors",
                                 "ifinerrors",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifoutoctets",
                                 "ifoutoctets",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifouterrors",
                                 "ifouterrors",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifstatus",
                                 "ifstatus",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "mac",
                                 "mac",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "ifdescr",
                                 "ifdescr",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "portduplex",
                                 "portduplex",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "trunk",
                                 "trunk",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "lastup",
                                 "lastup",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_networking_ports",
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifmac",
                                 "mac",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->dropKey($newTable,
                             "FK_networking_ports");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifmtu",
                                 "int(8) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifspeed",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifinternalstatus",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "ifconnectionstatus",
                                 "int(8) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "iflastchange",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "ifinoctets",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifinerrors",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifoutoctets",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifouterrors",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifstatus",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "mac",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "ifdescr",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "portduplex",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "trunk",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "lastup",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->addKey($newTable,
                            "networkports_id");
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusioninventory_networkequipments
    */
      $newTable = "glpi_plugin_fusioninventory_networkequipments";
      $migration->renameTable("glpi_plugin_fusinvsnmp_networkequipments",
                              $newTable);
      $migration->renameTable("glpi_plugin_tracker_networking",
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "networkequipments_id",
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "sysdescr",
                                 "sysdescr",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_configsecurities_id",
                                 "plugin_fusioninventory_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "uptime",
                                 "uptime",
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "cpu",
                                 "cpu",
                                 "int(3) NOT NULL DEFAULT '0' COMMENT '%'");
         $migration->changeField($newTable,
                                 "memory",
                                 "memory",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "last_fusioninventory_update",
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "last_PID_update",
                                 "last_PID_update",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_networking",
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_model_infos",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_snmp_connection",
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "last_tracker_update",
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "plugin_fusioninventory_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->dropKey($newTable,
                             "FK_networking");
         $migration->dropKey($newTable,
                             "FK_model_infos");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "sysdescr",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_snmpmodels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "uptime",
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "cpu",
                                 "int(3) NOT NULL DEFAULT '0' COMMENT '%'");
         $migration->addField($newTable,
                                 "memory",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->addField($newTable,
                                 "last_PID_update",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "networkequipments_id");
         $migration->addKey($newTable,
                            array("plugin_fusioninventory_snmpmodels_id", "plugin_fusioninventory_configsecurities_id"),
                            "plugin_fusioninventory_snmpmodels_id");
      $migration->migrationOneTable($newTable);

      
      
   /*
    * glpi_plugin_fusioninventory_networkequipmentips
    */
      $newTable = "glpi_plugin_fusioninventory_networkequipmentips";
      $migration->renameTable("glpi_plugin_fusinvsnmp_networkequipmentips",
                              $newTable);
      $migration->renameTable("glpi_plugin_tracker_networking_ifaddr",
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "networkequipments_id",
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ip",
                                 "ip",
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_networking",
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifaddr",
                                 "ip",
                                 "varchar(255) DEFAULT NULL");
         $migration->dropKey($newTable,
                             "ifaddr");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ip",
                                 "varchar(255) DEFAULT NULL");
         $migration->addKey($newTable,
                            "ip");
         $migration->addKey($newTable,
                            "networkequipments_id");
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusioninventory_networkportlogs
    */
      $newTable = "glpi_plugin_fusioninventory_networkportlogs";
         if (TableExists("glpi_plugin_tracker_snmp_history")) {
            // **** Update history
            update213to220_ConvertField($migration);

            // **** Migration network history connections
            $query = "SELECT count(ID) FROM `glpi_plugin_tracker_snmp_history`
                              WHERE `Field`='0'";
            $result = $DB->query($query);
            $datas = $DB->fetch_assoc($result);
            $nb = $datas['count(ID)'];

            echo "Move Connections history to another table...";

            for ($i=0; $i < $nb; $i = $i + 500) {
               $migration->displayMessage("$i / $nb");
               $sql_connection = "SELECT * FROM `glpi_plugin_tracker_snmp_history`
                                 WHERE `Field`='0'
                                 ORDER BY `FK_process` DESC, `date_mod` DESC
                                 LIMIT 500";
               $result_connection = $DB->query($sql_connection);
               while ($thread_connection = $DB->fetch_array($result_connection)) {
                  $input = array();
                  $input['process_number'] = $thread_connection['FK_process'];
                  $input['date'] = $thread_connection['date_mod'];
                  if (($thread_connection["old_device_ID"] != "0")
                          OR ($thread_connection["new_device_ID"] != "0")) {

                     if ($thread_connection["old_device_ID"] != "0") {
                        // disconnection
                        $input['creation'] = '0';
                     } else if ($thread_connection["new_device_ID"] != "0") {
                        // connection
                        $input['creation'] = '1';
                     }
                     $input['FK_port_source'] = $thread_connection["FK_ports"];
                     $dataPort = array();
                     if ($thread_connection["old_device_ID"] != "0") {
                        $queryPort = "SELECT *
                                      FROM `glpi_networkports`
                                      WHERE `mac`='".$thread_connection['old_value']."'
                                      LIMIT 1";
                        $resultPort = $DB->query($queryPort);
                        $dataPort = $DB->fetch_assoc($resultPort);
                     } else if ($thread_connection["new_device_ID"] != "0") {
                        $queryPort = "SELECT *
                                      FROM `glpi_networkports`
                                      WHERE `mac`='".$thread_connection['new_value']."'
                                      LIMIT 1";
                        $resultPort = $DB->query($queryPort);
                        $dataPort = $DB->fetch_assoc($resultPort);
                     }
                     if (isset($dataPort['id'])) {
                        $input['FK_port_destination'] = $dataPort['id'];
                     } else {
                        $input['FK_port_destination'] = 0;
                     }

                     $query_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
                        (`date_mod`, `creation`, `networkports_id_source`, `networkports_id_destination`)
                        VALUES ('".$input['date']."',
                                '".$input['creation']."',
                                '".$input['FK_port_source']."',
                                '".$input['FK_port_destination']."')";
                     $DB->query($query_ins);
                  }
               }
            }
            $query_del = "DELETE FROM `glpi_plugin_tracker_snmp_history`
               WHERE `Field`='0'
               AND (`old_device_ID`!='0' OR `new_device_ID`!='0')";
            $DB->query($query_del);
            $migration->displayMessage("$nb / $nb");
         }

      $migration->renameTable("glpi_plugin_fusinvsnmp_networkportlogs",
                              $newTable);
      $migration->renameTable("glpi_plugin_tracker_snmp_history",
                              $newTable);
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
                                 "networkports_id",
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "date_mod",
                                 "date_mod",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "value_old",
                                 "value_old",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "value_new",
                                 "value_new",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_agentprocesses_id",
                                 "plugin_fusioninventory_agentprocesses_id",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_ports",
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "plugin_fusioninventory_mappings_id",
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);

         // Update with mapping
         if (FieldExists($newTable, "Field")) {
            $pFusinvsnmpNetworkPortLog = new PluginFusinvsnmpNetworkPortLog();
            $pfMapping = new PluginFusioninventoryMapping();
            $query = "SELECT * FROM `".$newTable."`
               GROUP BY `Field`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $mapping = 0;
               if ($mapping = $pfMapping->get("NetworkEquipment", $data['Field'])) {
                  $DB->query("UPDATE `".$newTable."`
                     SET `plugin_fusioninventory_mappings_id`='".$mapping['id']."'
                     WHERE `Field`='".$data['Field']."'
                        AND `plugin_fusioninventory_mappings_id`!='".$mapping['id']."'");
               }
            }
         }
         $migration->dropField($newTable,
                            "Field");
         $migration->changeField($newTable,
                                 "old_value",
                                 "value_old",
                                 "varchar(255) DEFAULT NULL");
         $migration->dropField($newTable,
                               "old_device_type");
         $migration->dropField($newTable,
                               "old_device_ID");
         $migration->changeField($newTable,
                                 "new_value",
                                 "value_new",
                                 "varchar(255) DEFAULT NULL");
         $migration->dropField($newTable,
                               "new_device_type");
         $migration->dropField($newTable,
                               "new_device_ID");
         $migration->dropField($newTable, "FK_process");
         $migration->dropKey($newTable, "FK_process");
         $migration->dropKey($newTable,
                             "FK_ports");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "date_mod",
                                 "datetime DEFAULT NULL");
         $migration->addField($newTable,
                                 "value_old",
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                                 "value_new",
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_agentprocesses_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            array("networkports_id", "date_mod"),
                            "networkports_id");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_mappings_id");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_agentprocesses_id");
         $migration->addKey($newTable,
                            "date_mod");
      $migration->migrationOneTable($newTable);

      
      
   /*
    * Table glpi_plugin_fusioninventory_configsecurities
    */
      // TODO get info to create SNMP authentification with old values of Tracker plugin
      $newTable = "glpi_plugin_fusioninventory_configsecurities";
      $migration->renameTable("glpi_plugin_fusinvsnmp_configsecurities",
                              $newTable);
      $migration->renameTable("glpi_plugin_tracker_snmp_connection",
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
          $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "snmpversion",
                                 "snmpversion",
                                 "varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 "community",
                                 "community",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "username",
                                 "username",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "authentication",
                                 "authentication",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "auth_passphrase",
                                 "auth_passphrase",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "encryption",
                                 "encryption",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "priv_passphrase",
                                 "priv_passphrase",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "is_deleted",
                                 "is_deleted",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_snmp_version",
                                 "snmpversion",
                                 "varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 "sec_name",
                                 "username",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->dropField($newTable,
                               "sec_level");
         $migration->dropField($newTable,
                               "auth_protocol");
         $migration->dropField($newTable,
                               "priv_protocol");
         $migration->dropField($newTable,
                               "deleted");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "name",
                                 "varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "snmpversion",
                                 "varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 "community",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "username",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "authentication",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "auth_passphrase",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "encryption",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "priv_passphrase",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "is_deleted",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "snmpversion");
         $migration->addKey($newTable,
                            "is_deleted");
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    *  glpi_plugin_fusioninventory_statediscoveries
    */
      $newTable = "glpi_plugin_fusioninventory_statediscoveries";
      $migration->renameTable("glpi_plugin_fusinvsnmp_statediscoveries",
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query("CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_taskjob_id",
                                 "plugin_fusioninventory_taskjob_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_agents_id",
                                 "plugin_fusioninventory_agents_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "start_time",
                                 "start_time",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                                 "end_time",
                                 "end_time",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                                 "date_mod",
                                 "date_mod",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "threads",
                                 "threads",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_ip",
                                 "nb_ip",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_found",
                                 "nb_found",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_error",
                                 "nb_error",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_exists",
                                 "nb_exists",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_import",
                                 "nb_import",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_taskjob_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_agents_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "start_time",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->addField($newTable,
                                 "end_time",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->addField($newTable,
                                 "date_mod",
                                 "datetime DEFAULT NULL");
         $migration->addField($newTable,
                                 "threads",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_ip",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_found",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_error",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_exists",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_import",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);

      
      
   /*
    *  glpi_plugin_fusioninventory_computerlicenseinfos
    */
      $newTable = "glpi_plugin_fusioninventory_computerlicenseinfos";
      if (!TableExists($newTable)) {
         $DB->query("CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "computers_id",
                                 "computers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "softwarelicenses_id",
                                 "softwarelicenses_id",
                                 "int(11) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "fullname",
                                 "fullname",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "serial",
                                 "serial",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "is_trial",
                                 "is_trial",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "is_update",
                                 "is_update",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "is_oem",
                                 "is_oem",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "activation_date",
                                 "activation_date",
                                 "datetime DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "computers_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "softwarelicenses_id",
                              "int(11) DEFAULT NULL");
         $migration->addField($newTable,
                              "name",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "fullname",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "serial",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "is_trial",
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "is_update",
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "is_oem",
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "activation_date",
                              "datetime DEFAULT NULL");
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
         $agentmodule->add($input);
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
/*   if (!strstr($current_version, "+")) {// All version before 0.80+1.1 (new versioning)
      $computer = new Computer();
      $pfComputer = new PluginFusioninventoryInventoryComputerComputer();
      $migration->displayMessage("Convert computer inventory, may require some minutes");
      $pfLib = new PluginFusioninventoryInventoryComputerLib();
      $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $infoSections = array();
            $infoSections["externalId"] = '';
            $infoSections["sections"] = array();
            $infoSections["sectionsToModify"] = array();

            // Variables for the recovery and changes in the serialized sections
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
*/
   
   /*
    * Migrate data of table glpi_plugin_fusinvsnmp_agentconfigs into glpi_plugin_fusioninventory_agents
    */
   if (TableExists("glpi_plugin_fusinvsnmp_agentconfigs")) {

      $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_agentconfigs`";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $queryu = "UPDATE `glpi_plugin_fusioninventory_agents`
            SET `threads_networkdiscovery`='".$data['threads_netdiscovery']."', 
                `threads_networkinventory`='".$data['threads_snmpquery']."',
                `senddico`='".$data['senddico']."'
            WHERE `id`='".$data['plugin_fusioninventory_agents_id']."'";
         $DB->query($queryu);
      }      
   } 
   
   
   
   // Update profiles
   if (TableExists("glpi_plugin_tracker_profiles")) {
      $profile = new Profile();
      $pFusioninventoryProfile = new PluginFusioninventoryProfile();
      $query = "SELECT * FROM `glpi_plugin_tracker_profiles`";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $profiledata = current($profile->find("`name`='".$data['name']."'", "", 1));
         if (!empty($profiledata)) {
            $newprofile = array();
            $newprofile['snmp_networking'] = "networkequipment";
            $newprofile['snmp_printers'] = "printer";
            $newprofile['snmp_models'] = "model";
            $newprofile['snmp_authentification'] = "configsecurity";
            $newprofile['general_config'] = "configuration";
            $newprofile['snmp_report'] = "reportprinter";

            foreach ($newprofile as $old=>$new) {
               if (isset($profiledata[$old])) {
                  $pFusioninventoryProfile->addProfile($plugins_id,
                                                       $new,
                                                       $profiledata[$old],
                                                       $profiledata['id']);
               }
            }
            if (isset($profiledata["snmp_report"])) {
               $pFusioninventoryProfile->addProfile($plugins_id,
                                                    "reportnetworkequipment",
                                                    $profiledata["snmp_report"],
                                                    $profiledata['id']);
            }
         }
      }
      $DB->query("DROP TABLE `glpi_plugin_tracker_profiles`");
   }

   update213to220_ConvertField($migration);
   

   /*
    * Table Delete old table not used
    */
   $a_drop = array();
   $a_drop[] = 'glpi_plugin_tracker_computers';
   $a_drop[] = 'glpi_plugin_tracker_connection_history';
   $a_drop[] = 'glpi_plugin_tracker_agents_processes';
   $a_drop[] = 'glpi_plugin_tracker_config_snmp_history';
   $a_drop[] = 'glpi_plugin_tracker_config_snmp_networking';
   $a_drop[] = 'glpi_plugin_tracker_config_snmp_printer';
   $a_drop[] = 'glpi_plugin_tracker_config_snmp_script';
   $a_drop[] = 'glpi_plugin_tracker_connection_stats';
   $a_drop[] = 'glpi_plugin_tracker_discovery';
   $a_drop[] = 'glpi_plugin_tracker_errors';
   $a_drop[] = 'glpi_plugin_tracker_model_infos';
   $a_drop[] = 'glpi_plugin_tracker_processes';
   $a_drop[] = 'glpi_plugin_tracker_processes_values';
   $a_drop[] = 'glpi_plugin_fusioninventory_agents_errors';
   $a_drop[] = 'glpi_plugin_fusioninventory_agents_processes';
   $a_drop[] = 'glpi_plugin_fusioninventory_computers';
   $a_drop[] = 'glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol';
   $a_drop[] = 'glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol';
   $a_drop[] = 'glpi_dropdown_plugin_tracker_snmp_auth_sec_level';
   $a_drop[] = 'glpi_dropdown_plugin_tracker_snmp_version';
   $a_drop[] = 'glpi_plugin_fusioninventory_config_snmp_networking';
   $a_drop[] = 'glpi_plugin_fusioninventory_config_snmp_history';
   $a_drop[] = 'glpi_plugin_fusinvsnmp_agentconfigs';
   $a_drop[] = 'glpi_plugin_tracker_computers';
   $a_drop[] = 'glpi_plugin_tracker_config';
   $a_drop[] = 'glpi_plugin_tracker_config_discovery';
   $a_drop[] = 'glpi_dropdown_plugin_fusioninventory_mib_label';
   $a_drop[] = 'glpi_dropdown_plugin_fusioninventory_mib_object';
   $a_drop[] = 'glpi_dropdown_plugin_fusioninventory_mib_oid';
   $a_drop[] = 'glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol';
   $a_drop[] = 'glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol';
   $a_drop[] = 'glpi_dropdown_plugin_fusioninventory_snmp_version';
   $a_drop[] = 'glpi_plugin_fusinvsnmp_temp_profiles';
   $a_drop[] = 'glpi_plugin_fusinvsnmp_tmp_agents';
   $a_drop[] = 'glpi_plugin_fusinvsnmp_tmp_configs';
   $a_drop[] = 'glpi_plugin_fusinvsnmp_tmp_tasks';
   
   foreach ($a_drop as $droptable) {
      if (TableExists($droptable)) {
         $DB->query("DROP TABLE `".$droptable."`");
      }      
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
    *  Clean old ports deleted but have some informations in SNMP tables
    */
   echo "Clean ports purged\n";
   $query_select = "SELECT `glpi_plugin_fusioninventory_networkports`.`id`
                    FROM `glpi_plugin_fusioninventory_networkports`
                          LEFT JOIN `glpi_networkports`
                                    ON `glpi_networkports`.`id` = `networkports_id`
                          LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `glpi_networkports`.`items_id`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_networkports`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }
   
   
   
   /*
    *  Clean for multiple IP of a switch when this switch is purged but not these IPs
    */
   echo "Clean for multiple IP of a switch when this switch is purged but not these IPs\n";
   $query_select = "SELECT `glpi_plugin_fusioninventory_networkequipmentips`.`id`
                    FROM `glpi_plugin_fusioninventory_networkequipmentips`
                          LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `networkequipments_id`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_networkequipmentips`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }
   
   
   
   /*
    * Clean for switch more informations again in DB when switch is purged
    */
   echo "Clean for switch more informations again in DB when switch is purged\n";
   $query_select = "SELECT `glpi_plugin_fusioninventory_networkequipments`.`id`
                    FROM `glpi_plugin_fusioninventory_networkequipments`
                          LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `networkequipments_id`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
       $query_del = "DELETE FROM `glpi_plugin_fusioninventory_networkequipments`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }



   /*
    * Clean for printer more informations again in DB when printer is purged
    */
   "Clean for printer more informations again in DB when printer is purged\n";
   $query_select = "SELECT `glpi_plugin_fusioninventory_printers`.`id`
                    FROM `glpi_plugin_fusioninventory_printers`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                    WHERE `glpi_printers`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_printers`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }

   
   
   /*
    *  Clean printer cartridge not deleted with the printer associated
    */
   echo "Clean printer cartridge not deleted with the printer associated\n";
   $query_select = "SELECT `glpi_plugin_fusioninventory_printercartridges`.`id`
                    FROM `glpi_plugin_fusioninventory_printercartridges`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                    WHERE `glpi_printers`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_printercartridges`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }

   
   
   /*
    *  Clean printer history not deleted with printer associated
    */
   echo "Clean printer history not deleted with printer associated\n";
   $query_select = "SELECT `glpi_plugin_fusioninventory_printerlogs`.`id`
                    FROM `glpi_plugin_fusioninventory_printerlogs`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                    WHERE `glpi_printers`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_printerlogs`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }

   
   
   /*
    * Fix problem with mapping with many entries with same mapping
    */
   $a_mapping = array();
   $a_mappingdouble = array();
   $query = "SELECT * FROM `glpi_plugin_fusioninventory_mappings`
      ORDER BY `id`";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (!isset($a_mapping[$data['itemtype'].".".$data['name']])) {
         $a_mapping[$data['itemtype'].".".$data['name']] = $data['id'];
      } else {
         $a_mappingdouble[$data['id']] = $data['itemtype'].".".$data['name'];
      }
   }
   foreach($a_mappingdouble as $mapping_id=>$mappingkey) {
      $query = "UPDATE `glpi_plugin_fusionmodel_snmpmodelmibs`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "UPDATE `glpi_plugin_fusioninventory_printercartridges`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "UPDATE `glpi_plugin_fusioninventory_networkportlogs`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "UPDATE `glpi_plugin_fusioninventory_configlogfields`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "DELETE FROM `glpi_plugin_fusioninventory_mappings`
         WHERE `id` = '".$mapping_id."'";
      $DB->query($query);
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
   changeDisplayPreference("5151", "PluginFusinvsnmpModel");
   changeDisplayPreference("PluginFusinvsnmpModel", "PluginFusioninventorySnmpmodel");
   changeDisplayPreference("5152", "PluginFusinvsnmpConfigSecurity");
   changeDisplayPreference("5156", "PluginFusinvsnmpPrinterCartridge");
   changeDisplayPreference("5157", "PluginFusinvsnmpNetworkEquipment");
   changeDisplayPreference("PluginFusinvsnmpNetworkEquipment", "PluginFusioninventoryNetworkEquipment");
   changeDisplayPreference("5159", "PluginFusinvsnmpIPRange");
   changeDisplayPreference("5162", "PluginFusinvsnmpNetworkPortLog");
   changeDisplayPreference("5167", "PluginFusioninventorySnmpmodelConstructDevice");
   changeDisplayPreference("PluginFusinvsnmpConstructDevice",
                           "PluginFusioninventorySnmpmodelConstructDevice");
   changeDisplayPreference("5168", "PluginFusinvsnmpPrinterLog");
   changeDisplayPreference("PluginFusinvsnmpPrinterLogReport", "PluginFusioninventoryPrinterLogReport");

   
   /*
    * Modify displaypreference for PluginFusioninventoryPrinterLog
    */
      $pfPrinterLogReport = new PluginFusioninventoryPrinterLog();
      $a_searchoptions = $pfPrinterLogReport->getSearchOptions();
      $query = "SELECT * FROM `glpi_displaypreferences`
      WHERE `itemtype` = 'PluginFusioninventoryPrinterLogReport'
         AND `users_id`='0'";
      $result=$DB->query($query);
      if ($DB->numrows($result) == '0') {
         $query = "INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`)
                     VALUES (NULL,'PluginFusioninventoryPrinterLogReport', '2', '1', '0'),
             (NULL,'PluginFusioninventoryPrinterLogReport', '18', '2', '0'),
             (NULL,'PluginFusioninventoryPrinterLogReport', '20', '3', '0'),
             (NULL,'PluginFusioninventoryPrinterLogReport', '5', '4', '0'),
             (NULL,'PluginFusioninventoryPrinterLogReport', '6', '5', '0')";
         $DB->query($query);
      } else {
         while ($data=$DB->fetch_array($result)) {
            if (!isset($a_searchoptions[$data['num']])) {
               $queryd = "DELETE FROM `glpi_displaypreferences`
                  WHERE `id`='".$data['id']."'";
               $DB->query($queryd);
            }
         }
      }
   
      
      
   /*
    * Modify displaypreference for PluginFusinvsnmpNetworkEquipment
    */
      $a_check = array();
      $a_check["2"] = 1;
      $a_check["3"] = 2;
      $a_check["4"] = 3;
      $a_check["5"] = 4;
      $a_check["6"] = 5;
      $a_check["7"] = 6;
      $a_check["8"] = 7;
      $a_check["9"] = 8;
      $a_check["10"] = 9;
      $a_check["11"] = 10;
      $a_check["14"] = 11;
      $a_check["12"] = 12;
      $a_check["13"] = 13;

      foreach ($a_check as $num=>$rank) {
         $query = "SELECT * FROM `glpi_displaypreferences`
         WHERE `itemtype` = 'PluginFusioninventoryNetworkEquipment'
         AND `num`='".$num."'
            AND `users_id`='0'";
         $result=$DB->query($query);
         if ($DB->numrows($result) == '0') {
            $query = "INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`)
                        VALUES (NULL,'PluginFusioninventoryNetworkEquipment', '".$num."', '".$rank."', '0')";
            $DB->query($query);
         }
      }
      $query = "SELECT * FROM `glpi_displaypreferences`
      WHERE `itemtype` = 'PluginFusioninventoryNetworkEquipment'
         AND `users_id`='0'";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (!isset($a_check[$data['num']])) {
            $queryd = "DELETE FROM `glpi_displaypreferences`
               WHERE `id`='".$data['id']."'";
            $DB->query($queryd);
         }
      }

   
   
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
         if (is_null($config->getValue($type))) {
            $config->addValues(array($type=>$value));
         }
      }
     $DB->query("DELETE FROM `glpi_plugin_fusioninventory_configs`
        WHERE `plugins_id`='0'");


      $a_input = array();
      $a_input['version'] = PLUGIN_FUSIONINVENTORY_VERSION;
      if (is_null($config->getValue("ssl_only"))) {
         $a_input['ssl_only'] = 0;
      }
      if (isset($prepare_Config['ssl_only'])) {
         $a_input['ssl_only'] = $prepare_Config['ssl_only'];
      }
      if (is_null($config->getValue("delete_task"))) {
         $a_input['delete_task'] = 20;
      }
      if (is_null($config->getValue("inventory_frequence"))) {
         $a_input['inventory_frequence'] = 24;
      }
      if (is_null($config->getValue("agent_port"))) {
         $a_input['agent_port'] = 62354;
      }
      if (is_null($config->getValue("extradebug"))) {
         $a_input['extradebug'] = 0;
      }
      if (is_null($config->getValue("users_id"))) {
         $a_input['users_id'] = 0;
      }
      $config->addValues($a_input);

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
         $config->addValues(array($key => $value));
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
   if ($crontask->getFromDBbyName('PluginFusinvsnmpNetworkPortLog', 'cleannetworkportlogs')) {
      $crontask->delete($crontask->fields);
   }
   if (!$crontask->getFromDBbyName('PluginFusioninventoryNetworkPortLog', 'cleannetworkportlogs')) {
      Crontask::Register('PluginFusioninventoryNetworkPortLog', 'cleannetworkportlogs', (3600 * 24), 
                         array('mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30));
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

   // Update networkports types
   $pfNetworkporttype = new PluginFusioninventoryNetworkporttype();
   $pfNetworkporttype->init();
  
}



function plugin_fusioninventory_displayMigrationMessage ($id, $msg="") {
   static $created=0;
   static $deb;

   if ($created != $id) {
      if (empty($msg)) $msg=__('Work in progress...');

      echo "<div id='migration_message_$id'><p class='center'>$msg</p></div>";
      $created = $id;
      $deb = time();
   } else {
      if (empty($msg)) $msg=__('Task completed.');

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



function pluginFusioninventoryUpdatemapping() {
   
   /*
    * Udpate mapping
    */
   $pfMapping = new PluginFusioninventoryMapping();

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'location';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'locations_id';
   $a_input['locale']      = 1;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'firmware';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'networkequipmentfirmwares_id';
   $a_input['locale']      = 2;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'firmware1';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 2;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'firmware2';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 2;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'contact';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'contact';
   $a_input['locale']      = 403;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'comments';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'comment';
   $a_input['locale']      = 404;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'uptime';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'uptime';
   $a_input['locale']      = 3;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cpu';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'cpu';
   $a_input['locale']      = 12;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cpuuser';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'cpu';
   $a_input['locale']      = 401;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cpusystem';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'cpu';
   $a_input['locale']      = 402;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'serial';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'serial';
   $a_input['locale']      = 13;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'otherserial';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'otherserial';
   $a_input['locale']      = 419;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'name';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'name';
   $a_input['locale']      = 20;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ram';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'ram';
   $a_input['locale']      = 21;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'memory';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'memory';
   $a_input['locale']      = 22;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'vtpVlanName';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 19;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'vmvlan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 430;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'entPhysicalModelName';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'networkequipmentmodels_id';
   $a_input['locale']      = 17;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'macaddr';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'ip';
   $a_input['locale']      = 417;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 409;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheDevicePort';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 410;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheVersion';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 435;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheDeviceId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 436;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCachePlatform';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 437;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemChassisId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 431;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemPortId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 432;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpLocChassisId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 432;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemSysDesc';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 438;
   $pfMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemSysName';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 439;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemPortDesc';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 440;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'vlanTrunkPortDynamicStatus';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 411;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'dot1dTpFdbAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 412;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ipNetToMediaPhysAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 413;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'dot1dTpFdbPort';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 414;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'dot1dBasePortIfIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 415;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ipAdEntAddr';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 421;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'PortVlanIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 422;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 408;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifmtu';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifmtu';
   $a_input['locale']      = 4;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifspeed';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifspeed';
   $a_input['locale']      = 5;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifinternalstatus';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifinternalstatus';
   $a_input['locale']      = 6;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'iflastchange';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'iflastchange';
   $a_input['locale']      = 7;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifinoctets';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifinoctets';
   $a_input['locale']      = 8;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifoutoctets';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifoutoctets';
   $a_input['locale']      = 9;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifinerrors';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifinerrors';
   $a_input['locale']      = 10;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifouterrors';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifouterrors';
   $a_input['locale']      = 11;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifstatus';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifstatus';
   $a_input['locale']      = 14;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifPhysAddress';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'mac';
   $a_input['locale']      = 15;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifName';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'name';
   $a_input['locale']      = 16;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifType';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 18;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifdescr';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifdescr';
   $a_input['locale']      = 23;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'portDuplex';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'portduplex';
   $a_input['locale']      = 33;
   $pfMapping->set($a_input);

   // Printers
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'model';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'printermodels_id';
   $a_input['locale']      = 25;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'enterprise';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'manufacturers_id';
   $a_input['locale']      = 420;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'serial';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'serial';
   $a_input['locale']      = 27;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'contact';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'contact';
   $a_input['locale']      = 405;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'comments';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'comment';
   $a_input['locale']      = 406;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'name';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'comment';
   $a_input['locale']      = 24;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'otherserial';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'otherserial';
   $a_input['locale']      = 418;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'memory';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'memory_size';
   $a_input['locale']      = 26;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'location';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'locations_id';
   $a_input['locale']      = 56;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'informations';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 165;
   $a_input['shortlocale'] = 165;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 157;
   $a_input['shortlocale'] = 157;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblackmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 166;
   $a_input['shortlocale'] = 166;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblackused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 167;
   $a_input['shortlocale'] = 167;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblackremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 168;
   $a_input['shortlocale'] = 168;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 157;
   $a_input['shortlocale'] = 157;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2max';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 166;
   $a_input['shortlocale'] = 166;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2used';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 167;
   $a_input['shortlocale'] = 167;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2remaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 168;
   $a_input['shortlocale'] = 168;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 158;
   $a_input['shortlocale'] = 158;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyanmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 169;
   $a_input['shortlocale'] = 169;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyanused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 170;
   $a_input['shortlocale'] = 170;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyanremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 171;
   $a_input['shortlocale'] = 171;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 159;
   $a_input['shortlocale'] = 159;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagentamax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 172;
   $a_input['shortlocale'] = 172;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagentaused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 173;
   $a_input['shortlocale'] = 173;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagentaremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 174;
   $a_input['shortlocale'] = 174;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellow';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 160;
   $a_input['shortlocale'] = 160;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellowmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 175;
   $a_input['shortlocale'] = 175;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellowused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 176;
   $a_input['shortlocale'] = 176;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellowused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 177;
   $a_input['shortlocale'] = 177;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetoner';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 151;
   $a_input['shortlocale'] = 151;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetonermax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 190;
   $a_input['shortlocale'] = 190;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetonerused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 191;
   $a_input['shortlocale'] = 191;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetonerremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 192;
   $a_input['shortlocale'] = 192;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 134;
   $a_input['shortlocale'] = 134;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeblackphoto';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 135;
   $a_input['shortlocale'] = 135;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgecyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 136;
   $a_input['shortlocale'] = 136;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgecyanlight';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 139;
   $a_input['shortlocale'] = 139;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgemagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 138;
   $a_input['shortlocale'] = 138;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgemagentalight';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 140;
   $a_input['shortlocale'] = 140;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeyellow';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 137;
   $a_input['shortlocale'] = 137;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgegrey';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 196;
   $a_input['shortlocale'] = 196;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekit';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 156;
   $a_input['shortlocale'] = 156;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekitmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 193;
   $a_input['shortlocale'] = 193;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekitused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 194;
   $a_input['shortlocale'] = 194;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekitremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 195;
   $a_input['shortlocale'] = 195;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 161;
   $a_input['shortlocale'] = 161;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblackmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 178;
   $a_input['shortlocale'] = 178;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblackused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 179;
   $a_input['shortlocale'] = 179;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblackremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 180;
   $a_input['shortlocale'] = 180;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 162;
   $a_input['shortlocale'] = 162;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyanmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 181;
   $a_input['shortlocale'] = 181;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyanused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 182;
   $a_input['shortlocale'] = 182;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyanremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 183;
   $a_input['shortlocale'] = 183;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 163;
   $a_input['shortlocale'] = 163;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagentamax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 184;
   $a_input['shortlocale'] = 184;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagentaused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 185;
   $a_input['shortlocale'] = 185;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagentaremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 186;
   $a_input['shortlocale'] = 186;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellow';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 164;
   $a_input['shortlocale'] = 164;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellowmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 187;
   $a_input['shortlocale'] = 187;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellowused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 188;
   $a_input['shortlocale'] = 188;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellowremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 189;
   $a_input['shortlocale'] = 189;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total';
   $a_input['locale']      = 28;
   $a_input['shortlocale'] = 128;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterblackpages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_n_b';
   $a_input['locale']      = 29;
   $a_input['shortlocale'] = 129;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountercolorpages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_color';
   $a_input['locale']      = 30;
   $a_input['shortlocale'] = 130;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterrectoversopages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_recto_verso';
   $a_input['locale']      = 54;
   $a_input['shortlocale'] = 154;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterscannedpages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'scanned';
   $a_input['locale']      = 55;
   $a_input['shortlocale'] = 155;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages_print';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total_print';
   $a_input['locale']      = 423;
   $a_input['shortlocale'] = 1423;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterblackpages_print';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_n_b_print';
   $a_input['locale']      = 424;
   $a_input['shortlocale'] = 1424;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountercolorpages_print';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_color_print';
   $a_input['locale']      = 425;
   $a_input['shortlocale'] = 1425;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages_copy';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total_copy';
   $a_input['locale']      = 426;
   $a_input['shortlocale'] = 1426;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterblackpages_copy';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_n_b_copy';
   $a_input['locale']      = 427;
   $a_input['shortlocale'] = 1427;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountercolorpages_copy';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_color_copy';
   $a_input['locale']      = 428;
   $a_input['shortlocale'] = 1428;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages_fax';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total_fax';
   $a_input['locale']      = 429;
   $a_input['shortlocale'] = 1429;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterlargepages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total_large';
   $a_input['locale']      = 434;
   $a_input['shortlocale'] = 1434;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifPhysAddress';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'mac';
   $a_input['locale']      = 48;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifName';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'name';
   $a_input['locale']      = 57;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifaddr';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'ip';
   $a_input['locale']      = 407;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifType';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 97;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 416;
   $pfMapping->set($a_input);


   // ** Computer
   $a_input = array();
   $a_input['itemtype']    = 'Computer';
   $a_input['name']        = 'serial';
   $a_input['table']       = '';
   $a_input['tablefield']  = 'serial';
   $a_input['locale']      = 13;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Computer';
   $a_input['name']        = 'ifPhysAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = 'mac';
   $a_input['locale']      = 15;
   $pfMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'Computer';
   $a_input['name']        = 'ifaddr';
   $a_input['table']       = '';
   $a_input['tablefield']  = 'ip';
   $a_input['locale']      = 407;
   $pfMapping->set($a_input);
   
}



function update213to220_ConvertField($migration) {
   global $FUSIONINVENTORY_MAPPING,$FUSIONINVENTORY_MAPPING_DISCOVERY,$DB;

   // ----------------------------------------------------------------------
   //NETWORK MAPPING MAPPING
   // ----------------------------------------------------------------------
   $constantsfield = array();

   $constantsfield['reseaux > lieu'] = 'location';
   $constantsfield['networking > location'] = 'location';
   $constantsfield['Netzwerk > Standort'] = 'location';

   $constantsfield['réseaux > firmware'] = 'firmware';
   $constantsfield['networking > firmware'] = 'firmware';
   $constantsfield['Netzwerk > Firmware'] = 'firmware';

   $constantsfield['réseaux > firmware'] = 'firmware1';
   $constantsfield['networking > firmware'] = 'firmware1';
   $constantsfield['Netzwerk > Firmware'] = 'firmware1';

   $constantsfield['réseaux > firmware'] = 'firmware2';
   $constantsfield['networking > firmware'] = 'firmware2';
   $constantsfield['Netzwerk > Firmware'] = 'firmware2';

   $constantsfield['réseaux > contact'] = 'contact';
   $constantsfield['networking > contact'] = 'contact';
   $constantsfield['Netzwerk > Kontakt'] = 'contact';

   $constantsfield['réseaux > description'] = 'comments';
   $constantsfield['networking > comments'] = 'comments';
   $constantsfield['Netzwerk > Kommentar'] = 'comments';

   $constantsfield['réseaux > uptime'] = 'uptime';
   $constantsfield['networking > uptime'] = 'uptime';
   $constantsfield['Netzwerk > Uptime'] = 'uptime';

   $constantsfield['réseaux > utilisation du CPU'] = 'cpu';
   $constantsfield['networking > CPU usage'] = 'cpu';
   $constantsfield['Netzwerk > CPU Auslastung'] = 'cpu';

   $constantsfield['réseaux > CPU user'] = 'cpuuser';
   $constantsfield['networking > CPU usage (user)'] = 'cpuuser';
   $constantsfield['Netzwerk > CPU Benutzer'] = 'cpuuser';

   $constantsfield['réseaux > CPU système'] = 'cpusystem';
   $constantsfield['networking > CPU usage (system)'] = 'cpusystem';
   $constantsfield['Netzwerk > CPU System'] = 'cpusystem';

   $constantsfield['réseaux > numéro de série'] = 'serial';
   $constantsfield['networking > serial number'] = 'serial';
   $constantsfield['Netzwerk > Seriennummer'] = 'serial';

   $constantsfield['réseaux > numéro d\'inventaire'] = 'otherserial';
   $constantsfield['networking > Inventory number'] = 'otherserial';
   $constantsfield['Netzwerk > Inventarnummer'] = 'otherserial';

   $constantsfield['réseaux > nom'] = 'name';
   $constantsfield['networking > name'] = 'name';
   $constantsfield['Netzwerk > Name'] = 'name';

   $constantsfield['réseaux > mémoire totale'] = 'ram';
   $constantsfield['networking > total memory'] = 'ram';
   $constantsfield['Netzwerk > Gesamter Speicher'] = 'ram';

   $constantsfield['réseaux > mémoire libre'] = 'memory';
   $constantsfield['networking > free memory'] = 'memory';
   $constantsfield['Netzwerk > Freier Speicher'] = 'memory';

   $constantsfield['réseaux > VLAN'] = 'vtpVlanName';
   $constantsfield['networking > VLAN'] = 'vtpVlanName';
   $constantsfield['Netzwerk > VLAN'] = 'vtpVlanName';

   $constantsfield['réseaux > port > vlan'] = 'vmvlan';
   $constantsfield['networking > port > vlan'] = 'vmvlan';

   $constantsfield['réseaux > modèle'] = 'entPhysicalModelName';
   $constantsfield['networking > model'] = 'entPhysicalModelName';
   $constantsfield['Netzwerk > Modell'] = 'entPhysicalModelName';

   $constantsfield['réseaux > adresse MAC'] = 'macaddr';
   $constantsfield['networking > MAC address'] = 'macaddr';
   $constantsfield['Netzwerk > MAC Adresse'] = 'macaddr';

   $constantsfield['réseaux > Adresse CDP'] = 'cdpCacheAddress';
   $constantsfield['networking > CDP address'] = 'cdpCacheAddress';
   $constantsfield['Netzwerk > Adresse CDP'] = 'cdpCacheAddress';

   $constantsfield['réseaux > port CDP'] = 'cdpCacheDevicePort';
   $constantsfield['networking > CDP port'] = 'cdpCacheDevicePort';
   $constantsfield['Netzwerk > Port CDP'] = 'cdpCacheDevicePort';

   $constantsfield['réseaux > chassis id distant LLDP'] = 'lldpRemChassisId';
   $constantsfield['networking > remote chassis id LLDP'] = 'lldpRemChassisId';

   $constantsfield['réseaux > port distant LLDP'] = 'lldpRemPortId';
   $constantsfield['networking > remote port LLDP'] = 'lldpRemPortId';

   $constantsfield['réseaux > chassis id local LLDP'] = 'lldpLocChassisId';
   $constantsfield['networking > localchassis id LLDP'] = 'lldpLocChassisId';

   $constantsfield['réseaux > port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';
   $constantsfield['networking > port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';
   $constantsfield['Netzwerk > Port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';

   $constantsfield['trunk'] = 'vlanTrunkPortDynamicStatus';

   $constantsfield['réseaux > Adresses mac filtrées (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';
   $constantsfield['networking > MAC address filters (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';
   $constantsfield['Netzwerk > MAC Adressen Filter (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';

   $constantsfield['réseaux > adresses physiques mémorisées (ipNetToMediaPhysAddress)'] = 'ipNetToMediaPhysAddress';
   $constantsfield['networking > Physical addresses in memory (ipNetToMediaPhysAddress)'] = 'ipNetToMediaPhysAddress';
   $constantsfield['Netzwerk > Physikalische Adressen im Speicher (ipNetToMediaPhysAddress)'] = 'ipNetToMediaPhysAddress';

   $constantsfield['réseaux > instances de ports (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';
   $constantsfield['networking > Port instances (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';
   $constantsfield['Netzwerk > Instanzen des Ports (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';

   $constantsfield['réseaux > numéro de ports associé ID du port (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';
   $constantsfield['networking > Port number associated with port ID (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';
   $constantsfield['Netzwerk > Verkn&uuml;pfung der Portnummerierung mit der ID des Ports (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';

   $constantsfield['réseaux > addresses IP'] = 'ipAdEntAddr';
   $constantsfield['networking > IP addresses'] = 'ipAdEntAddr';
   $constantsfield['Netzwerk > IP Adressen'] = 'ipAdEntAddr';

   $constantsfield['réseaux > portVlanIndex'] = 'PortVlanIndex';
   $constantsfield['networking > portVlanIndex'] = 'PortVlanIndex';
   $constantsfield['Netzwerk > portVlanIndex'] = 'PortVlanIndex';

   $constantsfield['réseaux > port > numéro index'] = 'ifIndex';
   $constantsfield['networking > port > index number'] = 'ifIndex';
   $constantsfield['Netzwerk > Port > Nummerischer Index'] = 'ifIndex';

   $constantsfield['réseaux > port > mtu'] = 'ifmtu';
   $constantsfield['networking > port > mtu'] = 'ifmtu';
   $constantsfield['Netzwerk > Port > MTU'] = 'ifmtu';

   $constantsfield['réseaux > port > vitesse'] = 'ifspeed';
   $constantsfield['networking > port > speed'] = 'ifspeed';
   $constantsfield['Netzwerk > Port > Geschwindigkeit'] = 'ifspeed';

   $constantsfield['réseaux > port > statut interne'] = 'ifinternalstatus';
   $constantsfield['networking > port > internal status'] = 'ifinternalstatus';
   $constantsfield['Netzwerk > Port > Interner Zustand'] = 'ifinternalstatus';

   $constantsfield['réseaux > port > Dernier changement'] = 'iflastchange';
   $constantsfield['networking > ports > Last change'] = 'iflastchange';
   $constantsfield['Netzwerk > Ports > Letzte &Auml;nderung'] = 'iflastchange';

   $constantsfield['réseaux > port > nombre d\'octets entrés'] = 'ifinoctets';
   $constantsfield['networking > port > number of bytes in'] = 'ifinoctets';
   $constantsfield['Netzwerk > Port > Anzahl eingegangene Bytes'] = 'ifinoctets';

   $constantsfield['réseaux > port > nombre d\'octets sortis'] = 'ifoutoctets';
   $constantsfield['networking > port > number of bytes out'] = 'ifoutoctets';
   $constantsfield['Netzwerk > Port > Anzahl ausgehende Bytes'] = 'ifoutoctets';

   $constantsfield['réseaux > port > nombre d\'erreurs entrées'] = 'ifinerrors';
   $constantsfield['networking > port > number of input errors'] = 'ifinerrors';
   $constantsfield['Netzwerk > Port > Anzahl Input Fehler'] = 'ifinerrors';

   $constantsfield['réseaux > port > nombre d\'erreurs sorties'] = 'ifouterrors';
   $constantsfield['networking > port > number of output errors'] = 'ifouterrors';
   $constantsfield['Netzwerk > Port > Anzahl Fehler Ausgehend'] = 'ifouterrors';

   $constantsfield['réseaux > port > statut de la connexion'] = 'ifstatus';
   $constantsfield['networking > port > connection status'] = 'ifstatus';
   $constantsfield['Netzwerk > Port > Verbingungszustand'] = 'ifstatus';

   $constantsfield['réseaux > port > adresse MAC'] = 'ifPhysAddress';
   $constantsfield['networking > port > MAC address'] = 'ifPhysAddress';
   $constantsfield['Netzwerk > Port > MAC Adresse'] = 'ifPhysAddress';

   $constantsfield['réseaux > port > nom'] = 'ifName';
   $constantsfield['networking > port > name'] = 'ifName';
   $constantsfield['Netzwerk > Port > Name'] = 'ifName';

   $constantsfield['réseaux > port > type'] = 'ifType';
   $constantsfield['networking > ports > type'] = 'ifType';
   $constantsfield['Netzwerk > Ports > Typ'] = 'ifType';

   $constantsfield['réseaux > port > description du port'] = 'ifdescr';
   $constantsfield['networking > port > port description'] = 'ifdescr';
   $constantsfield['Netzwerk > Port > Port Bezeichnung'] = 'ifdescr';

   $constantsfield['réseaux > port > type de duplex'] = 'portDuplex';
   $constantsfield['networking > port > duplex type'] = 'portDuplex';
   $constantsfield['Netzwerk > Port > Duplex Typ'] = 'portDuplex';

   $constantsfield['imprimante > modèle'] = 'model';
   $constantsfield['printer > model'] = 'model';
   $constantsfield['Drucker > Modell'] = 'model';

   $constantsfield['imprimante > fabricant'] = 'enterprise';
   $constantsfield['printer > manufacturer'] = 'enterprise';
   $constantsfield['Drucker > Hersteller'] = 'enterprise';

   $constantsfield['imprimante > numéro de série'] = 'serial';
   $constantsfield['printer > serial number'] = 'serial';
   $constantsfield['Drucker > Seriennummer'] = 'serial';

   $constantsfield['imprimante > contact'] = 'contact';
   $constantsfield['printer > contact'] = 'contact';
   $constantsfield['Drucker > Kontakt'] = 'contact';

   $constantsfield['imprimante > description'] = 'comments';
   $constantsfield['printer > comments'] = 'comments';
   $constantsfield['Drucker > Kommentar'] = 'comments';

   $constantsfield['imprimante > nom'] = 'name';
   $constantsfield['printer > name'] = 'name';
   $constantsfield['Drucker > Name'] = 'name';

   $constantsfield['imprimante > numéro d\'inventaire'] = 'otherserial';
   $constantsfield['printer > Inventory number'] = 'otherserial';
   $constantsfield['Drucker > Inventarnummer'] = 'otherserial';

   $constantsfield['imprimante > mémoire totale'] = 'memory';
   $constantsfield['printer > total memory'] = 'memory';
   $constantsfield['Drucker > Gesamter Speicher'] = 'memory';

   $constantsfield['imprimante > lieu'] = 'location';
   $constantsfield['printer > location'] = 'location';
   $constantsfield['Drucker > Standort'] = 'location';

   $constantsfield['Informations diverses regroupées'] = 'informations';
   $constantsfield['Many informations grouped'] = 'informations';
   $constantsfield['Many informations grouped'] = 'informations';

   $constantsfield['Toner Noir'] = 'tonerblack';
   $constantsfield['Black toner'] = 'tonerblack';

   $constantsfield['Toner Noir Max'] = 'tonerblackmax';
   $constantsfield['Black toner Max'] = 'tonerblackmax';

   $constantsfield['Toner Noir Utilisé'] = 'tonerblackused';

   $constantsfield['Toner Noir Restant'] = 'tonerblackremaining';

   $constantsfield['Toner Noir'] = 'tonerblack2';
   $constantsfield['Black toner'] = 'tonerblack2';

   $constantsfield['Toner Noir Max'] = 'tonerblack2max';
   $constantsfield['Black toner Max'] = 'tonerblack2max';

   $constantsfield['Toner Noir Utilisé'] = 'tonerblack2used';

   $constantsfield['Toner Noir Restant'] = 'tonerblack2remaining';

   $constantsfield['Toner Cyan'] = 'tonercyan';
   $constantsfield['Cyan toner'] = 'tonercyan';

   $constantsfield['Toner Cyan Max'] = 'tonercyanmax';
   $constantsfield['Cyan toner Max'] = 'tonercyanmax';

   $constantsfield['Toner Cyan Utilisé'] = 'tonercyanused';

   $constantsfield['Toner Cyan Restant'] = 'tonercyanremaining';

   $constantsfield['Toner Magenta'] = 'tonermagenta';
   $constantsfield['Magenta toner'] = 'tonermagenta';

   $constantsfield['Toner Magenta Max'] = 'tonermagentamax';
   $constantsfield['Magenta toner Max'] = 'tonermagentamax';

   $constantsfield['Toner Magenta Utilisé'] = 'tonermagentaused';
   $constantsfield['Magenta toner Utilisé'] = 'tonermagentaused';

   $constantsfield['Toner Magenta Restant'] = 'tonermagentaremaining';
   $constantsfield['Magenta toner Restant'] = 'tonermagentaremaining';

   $constantsfield['Toner Jaune'] = 'toneryellow';
   $constantsfield['Yellow toner'] = 'toneryellow';

   $constantsfield['Toner Jaune Max'] = 'toneryellowmax';
   $constantsfield['Yellow toner Max'] = 'toneryellowmax';

   $constantsfield['Toner Jaune Utilisé'] = 'toneryellowused';
   $constantsfield['Yellow toner Utilisé'] = 'toneryellowused';

   $constantsfield['Toner Jaune Restant'] = 'toneryellowremaining';
   $constantsfield['Yellow toner Restant'] = 'toneryellowremaining';

   $constantsfield['Bac récupérateur de déchet'] = 'wastetoner';
   $constantsfield['Waste bin'] = 'wastetoner';
   $constantsfield['Abfalleimer'] = 'wastetoner';

   $constantsfield['Bac récupérateur de déchet Max'] = 'wastetonermax';
   $constantsfield['Waste bin Max'] = 'wastetonermax';

   $constantsfield['Bac récupérateur de déchet Utilisé'] = 'wastetonerused';
   $constantsfield['Waste bin Utilisé'] = 'wastetonerused';

   $constantsfield['Bac récupérateur de déchet Restant'] = 'wastetonerremaining';
   $constantsfield['Waste bin Restant'] = 'wastetonerremaining';

   $constantsfield['Cartouche noir'] = 'cartridgeblack';
   $constantsfield['Black ink cartridge'] = 'cartridgeblack';
   $constantsfield['Schwarze Kartusche'] = 'cartridgeblack';

   $constantsfield['Cartouche noir photo'] = 'cartridgeblackphoto';
   $constantsfield['Photo black ink cartridge'] = 'cartridgeblackphoto';
   $constantsfield['Photoschwarz Kartusche'] = 'cartridgeblackphoto';

   $constantsfield['Cartouche cyan'] = 'cartridgecyan';
   $constantsfield['Cyan ink cartridge'] = 'cartridgecyan';
   $constantsfield['Cyan Kartusche'] = 'cartridgecyan';

   $constantsfield['Cartouche cyan clair'] = 'cartridgecyanlight';
   $constantsfield['Light cyan ink cartridge'] = 'cartridgecyanlight';
   $constantsfield['Leichtes Cyan Kartusche'] = 'cartridgecyanlight';

   $constantsfield['Cartouche magenta'] = 'cartridgemagenta';
   $constantsfield['Magenta ink cartridge'] = 'cartridgemagenta';
   $constantsfield['Magenta Kartusche'] = 'cartridgemagenta';

   $constantsfield['Cartouche magenta clair'] = 'cartridgemagentalight';
   $constantsfield['Light ink magenta cartridge'] = 'cartridgemagentalight';
   $constantsfield['Leichtes Magenta Kartusche'] = 'cartridgemagentalight';

   $constantsfield['Cartouche jaune'] = 'cartridgeyellow';
   $constantsfield['Yellow ink cartridge'] = 'cartridgeyellow';
   $constantsfield['Gelbe Kartusche'] = 'cartridgeyellow';

   $constantsfield['Cartouche grise'] = 'cartridgegrey';
   $constantsfield['Grey ink cartridge'] = 'cartridgegrey';
   $constantsfield['Grey ink cartridge'] = 'cartridgegrey';

   $constantsfield['Kit de maintenance'] = 'maintenancekit';
   $constantsfield['Maintenance kit'] = 'maintenancekit';
   $constantsfield['Wartungsmodul'] = 'maintenancekit';

   $constantsfield['Kit de maintenance Max'] = 'maintenancekitmax';
   $constantsfield['Maintenance kit Max'] = 'maintenancekitmax';

   $constantsfield['Kit de maintenance Utilisé'] = 'maintenancekitused';
   $constantsfield['Maintenance kit Utilisé'] = 'maintenancekitused';

   $constantsfield['Kit de maintenance Restant'] = 'maintenancekitremaining';
   $constantsfield['Maintenance kit Restant'] = 'maintenancekitremaining';

   $constantsfield['Tambour Noir'] = 'drumblack';
   $constantsfield['Black drum'] = 'drumblack';

   $constantsfield['Tambour Noir Max'] = 'drumblackmax';
   $constantsfield['Black drum Max'] = 'drumblackmax';

   $constantsfield['Tambour Noir Utilisé'] = 'drumblackused';
   $constantsfield['Black drum Utilisé'] = 'drumblackused';

   $constantsfield['Tambour Noir Restant'] = 'drumblackremaining';
   $constantsfield['Black drum Restant'] = 'drumblackremaining';

   $constantsfield['Tambour Cyan'] = 'drumcyan';
   $constantsfield['Cyan drum'] = 'drumcyan';

   $constantsfield['Tambour Cyan Max'] = 'drumcyanmax';
   $constantsfield['Cyan drum Max'] = 'drumcyanmax';

   $constantsfield['Tambour Cyan Utilisé'] = 'drumcyanused';
   $constantsfield['Cyan drum Utilisé'] = 'drumcyanused';

   $constantsfield['Tambour Cyan Restant'] = 'drumcyanremaining';
   $constantsfield['Cyan drumRestant'] = 'drumcyanremaining';

   $constantsfield['Tambour Magenta'] = 'drummagenta';
   $constantsfield['Magenta drum'] = 'drummagenta';

   $constantsfield['Tambour Magenta Max'] = 'drummagentamax';
   $constantsfield['Magenta drum Max'] = 'drummagentamax';

   $constantsfield['Tambour Magenta Utilisé'] = 'drummagentaused';
   $constantsfield['Magenta drum Utilisé'] = 'drummagentaused';

   $constantsfield['Tambour Magenta Restant'] = 'drummagentaremaining';
   $constantsfield['Magenta drum Restant'] = 'drummagentaremaining';

   $constantsfield['Tambour Jaune'] = 'drumyellow';
   $constantsfield['Yellow drum'] = 'drumyellow';

   $constantsfield['Tambour Jaune Max'] = 'drumyellowmax';
   $constantsfield['Yellow drum Max'] = 'drumyellowmax';

   $constantsfield['Tambour Jaune Utilisé'] = 'drumyellowused';
   $constantsfield['Yellow drum Utilisé'] = 'drumyellowused';

   $constantsfield['Tambour Jaune Restant'] = 'drumyellowremaining';
   $constantsfield['Yellow drum Restant'] = 'drumyellowremaining';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées'] = 'pagecountertotalpages';
   $constantsfield['printer > meter > total number of printed pages'] = 'pagecountertotalpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten'] = 'pagecountertotalpages';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées'] = 'pagecounterblackpages';
   $constantsfield['printer > meter > number of printed black and white pages'] = 'pagecounterblackpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedrucker Schwarz/Wei&szlig; Seiten'] = 'pagecounterblackpages';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées'] = 'pagecountercolorpages';
   $constantsfield['printer > meter > number of printed color pages'] = 'pagecountercolorpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Farbseiten'] = 'pagecountercolorpages';

   $constantsfield['imprimante > compteur > nombre de pages recto/verso imprimées'] = 'pagecounterrectoversopages';
   $constantsfield['printer > meter > number of printed duplex pages'] = 'pagecounterrectoversopages';
   $constantsfield['Drucker > Messung > Anzahl der gedruckten Duplex Seiten'] = 'pagecounterrectoversopages';

   $constantsfield['imprimante > compteur > nombre de pages scannées'] = 'pagecounterscannedpages';
   $constantsfield['printer > meter > nomber of scanned pages'] = 'pagecounterscannedpages';
   $constantsfield['Drucker > Messung > Anzahl der gescannten Seiten'] = 'pagecounterscannedpages';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (impression)'] = 'pagecountertotalpages_print';
   $constantsfield['printer > meter > total number of printed pages (print mode)'] = 'pagecountertotalpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Druck)'] = 'pagecountertotalpages_print';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées (impression)'] = 'pagecounterblackpages_print';
   $constantsfield['printer > meter > number of printed black and white pages (print mode)'] = 'pagecounterblackpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seiten (Druck)'] = 'pagecounterblackpages_print';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées (impression)'] = 'pagecountercolorpages_print';
   $constantsfield['printer > meter > number of printed color pages (print mode)'] = 'pagecountercolorpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Druck)'] = 'pagecountercolorpages_print';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (copie)'] = 'pagecountertotalpages_copy';
   $constantsfield['printer > meter > total number of printed pages (copy mode)'] = 'pagecountertotalpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Kopie)'] = 'pagecountertotalpages_copy';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées (copie)'] = 'pagecounterblackpages_copy';
   $constantsfield['printer > meter > number of printed black and white pages (copy mode)'] = 'pagecounterblackpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seite (Kopie)'] = 'pagecounterblackpages_copy';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées (copie)'] = 'pagecountercolorpages_copy';
   $constantsfield['printer > meter > number of printed color pages (copy mode)'] = 'pagecountercolorpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Kopie)'] = 'pagecountercolorpages_copy';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (fax)'] = 'pagecountertotalpages_fax';
   $constantsfield['printer > meter > total number of printed pages (fax mode)'] = 'pagecountertotalpages_fax';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Fax)'] = 'pagecountertotalpages_fax';

   $constantsfield['imprimante > compteur > nombre total de pages larges imprimées'] = 'pagecounterlargepages';
   $constantsfield['printer > meter > total number of large printed pages'] = 'pagecounterlargepages';

   $constantsfield['imprimante > port > adresse MAC'] = 'ifPhysAddress';
   $constantsfield['printer > port > MAC address'] = 'ifPhysAddress';
   $constantsfield['Drucker > Port > MAC Adresse'] = 'ifPhysAddress';

   $constantsfield['imprimante > port > nom'] = 'ifName';
   $constantsfield['printer > port > name'] = 'ifName';
   $constantsfield['Drucker > Port > Name'] = 'ifName';

   $constantsfield['imprimante > port > adresse IP'] = 'ifaddr';
   $constantsfield['printer > port > IP address'] = 'ifaddr';
   $constantsfield['Drucker > Port > IP Adresse'] = 'ifaddr';

   $constantsfield['imprimante > port > type'] = 'ifType';
   $constantsfield['printer > port > type'] = 'ifType';
   $constantsfield['Drucker > port > Typ'] = 'ifType';

   $constantsfield['imprimante > port > numéro index'] = 'ifIndex';
   $constantsfield['printer > port > index number'] = 'ifIndex';
   $constantsfield['Drucker > Port > Indexnummer'] = 'ifIndex';

   if (TableExists("glpi_plugin_tracker_snmp_history")) {
      echo "Converting history port ...\n";
      $i = 0;
      $nb = count($constantsfield);
         $migration->addKey("glpi_plugin_tracker_snmp_history",
                         "Field");
      $migration->addKey("glpi_plugin_tracker_snmp_history",
                         array("Field", "old_value"),
                         "Field_2");
      $migration->addKey("glpi_plugin_tracker_snmp_history",
                         array("Field", "new_value"),
                         "Field_3");
      $migration->migrationOneTable("glpi_plugin_tracker_snmp_history");

      foreach($constantsfield as $langvalue=>$mappingvalue) {
         $i++;
         $query_update = "UPDATE `glpi_plugin_tracker_snmp_history`
            SET `Field`='".$mappingvalue."'
            WHERE `Field`=\"".$langvalue."\" ";
         $DB->query($query_update);
         $migration->displayMessage("$i / $nb");
      }
      $migration->displayMessage("$i / $nb");

      // Move connections from glpi_plugin_fusioninventory_snmp_history to glpi_plugin_fusioninventory_snmp_history_connections
      echo "Moving creation connections history\n";
      $query = "SELECT *
                FROM `glpi_plugin_tracker_snmp_history`
                WHERE `Field` = '0'
                  AND ((`old_value` NOT LIKE '%:%')
                        OR (`old_value` IS NULL))";
      if ($result=$DB->query($query)) {
         $nb = $DB->numrows($result);
         $i = 0;
         $migration->displayMessage("$i / $nb");
         while ($data=$DB->fetch_array($result)) {
            $i++;

            // Search port from mac address
            $query_port = "SELECT * FROM `glpi_networkports`
               WHERE `mac`='".$data['new_value']."' ";
            if ($result_port=$DB->query($query_port)) {
               if ($DB->numrows($result_port) == '1') {
                  $input = array();
                  $data_port = $DB->fetch_assoc($result_port);
                  $input['FK_port_source'] = $data_port['id'];

                  $query_port2 = "SELECT * FROM `glpi_networkports`
                     WHERE `items_id` = '".$data['new_device_ID']."'
                        AND `itemtype` = '".$data['new_device_type']."' ";
                  if ($result_port2=$DB->query($query_port2)) {
                     if ($DB->numrows($result_port2) == '1') {
                        $data_port2 = $DB->fetch_assoc($result_port2);
                        $input['FK_port_destination'] = $data_port2['id'];

                        $input['date'] = $data['date_mod'];
                        $input['creation'] = 1;
                        $input['process_number'] = $data['FK_process'];
                        $query_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
                           (`date_mod`, `creation`, `networkports_id_source`, `networkports_id_destination`)
                           VALUES ('".$input['date']."',
                                   '".$input['creation']."',
                                   '".$input['FK_port_source']."',
                                   '".$input['FK_port_destination']."')";
                        $DB->query($query_ins);
                     }
                  }
               }
            }

            $query_delete = "DELETE FROM `glpi_plugin_tracker_snmp_history`
                  WHERE `ID`='".$data['ID']."' ";
            $DB->query($query_delete);
            if (preg_match("/000$/", $i)) {
               $migration->displayMessage("$i / $nb");
            }
         }
         $migration->displayMessage("$i / $nb");
      }

      echo "Moving deleted connections history\n";
      $query = "SELECT *
                FROM `glpi_plugin_tracker_snmp_history`
                WHERE `Field` = '0'
                  AND ((`new_value` NOT LIKE '%:%')
                        OR (`new_value` IS NULL))";
      if ($result=$DB->query($query)) {
         $nb = $DB->numrows($result);
         $i = 0;
         $migration->displayMessage("$i / $nb");
         while ($data=$DB->fetch_array($result)) {
            $i++;

            // Search port from mac address
            $query_port = "SELECT * FROM `glpi_networkports`
               WHERE `mac`='".$data['old_value']."' ";
            if ($result_port=$DB->query($query_port)) {
               if ($DB->numrows($result_port) == '1') {
                  $input = array();
                  $data_port = $DB->fetch_assoc($result_port);
                  $input['FK_port_source'] = $data_port['id'];

                  $query_port2 = "SELECT * FROM `glpi_networkports`
                     WHERE `items_id` = '".$data['old_device_ID']."'
                        AND `itemtype` = '".$data['old_device_type']."' ";
                  if ($result_port2=$DB->query($query_port2)) {
                     if ($DB->numrows($result_port2) == '1') {
                        $data_port2 = $DB->fetch_assoc($result_port2);
                        $input['FK_port_destination'] = $data_port2['id'];

                        $input['date'] = $data['date_mod'];
                        $input['creation'] = 1;
                        $input['process_number'] = $data['FK_process'];
                        if ($input['FK_port_source'] != $input['FK_port_destination']) {
                           $query_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
                              (`date_mod`, `creation`, `networkports_id_source`, `networkports_id_destination`)
                              VALUES ('".$input['date']."',
                                      '".$input['creation']."',
                                      '".$input['FK_port_source']."',
                                      '".$input['FK_port_destination']."')";
                           $DB->query($query_ins);
                        }
                     }
                  }
               }
            }

            $query_delete = "DELETE FROM `glpi_plugin_tracker_snmp_history`
                  WHERE `ID`='".$data['ID']."' ";
            $DB->query($query_delete);
            if (preg_match("/000$/", $i)) {
               $migration->displayMessage("$i / $nb");
            }
         }
         $migration->displayMessage("$i / $nb");
      }
   }
}
         
function pluginFusioninventorychangeDisplayPreference($olditemtype, $newitemtype) {
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
}


?>