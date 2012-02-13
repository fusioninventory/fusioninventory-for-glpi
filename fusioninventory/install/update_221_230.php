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
   it under the terms of the GNU Affero General Public License as published by
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

// Update from 2.2.1 to 2.3.0
function update221to230($migrationname) {
   global $DB, $CFG_GLPI, $LANG;
   
   $migration = new $migrationname("2.3.0");

   ini_set("max_execution_time", "0");

   $plugin = new Plugin();
   $data = $plugin->find("`name` = 'FusionInventory'");
   $fields = current($data);
   $plugins_id = $fields['id'];

   $typetoname=array(
      "0" => "",// For tickets
      "1" => "Computer",
      "2" => "NetworkEquipment",
      "3" => "Printer",
      "4" => "Monitor",
      "5" => "Peripheral",
      "6" => "Software",
      "7" => "Contact",
      "8" => "Supplier",
      "9" => "Infocom",
      "10" => "Contract",
      "11" => "CartridgeItem",
      "12" => "DocumentType",
      "13" => "Document",
      "14" => "KnowbaseItem",
      "15" => "User",
      "16" => "Ticket",
      "17" => "ConsumableItem",
      "18" => "Consumable",
      "19" => "Cartridge",
      "20" => "SoftwareLicense",
      "21" => "Link",
      "22" => "States",
      "23" => "Phone",
      "24" => "Device",
      "25" => "Reminder",
      "26" => "Stat",
      "27" => "Group",
      "28" => "Entity",
      "29" => "ReservationItem",
      "30" => "AuthMail",
      "31" => "AuthLDAP",
      "32" => "OcsServer",
      "33" => "RegistryKey",
      "34" => "Profile",
      "35" => "MailCollector",
      "36" => "Rule",
      "37" => "Transfer",
      "38" => "Bookmark",
      "39" => "SoftwareVersion",
      "40" => "Plugin",
      "41" => "ComputerDisk",
      "42" => "NetworkPort",
      "43" => "TicketFollowup"
      // End is not used in 0.72.x
   );

   $migration->displayMessage("Create mapping table");

   $sql = "CREATE TABLE `glpi_plugin_fusioninventory_mappings` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `tablefield` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `locale` INT( 4 ) NOT NULL,
   `shortlocale` INT( 4 ) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `itemtype` (`itemtype`),
   KEY `table` (`table`),
   KEY `tablefield` (`tablefield`)
--   UNIQUE KEY `unicity` (`name`, `itemtype`) -- Specified key was too long; max key length is 1000 bytes
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
   $DB->query($sql);

   $sql = "INSERT INTO `glpi_plugin_fusioninventory_mappings`
      (`itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
   VALUES ('NetworkEquipment','location','glpi_networkequipments','locations_id',1,NULL),
          ('NetworkEquipment','firmware','glpi_networkequipments',
             'networkequipmentfirmwares_id',2,NULL),
          ('NetworkEquipment','firmware1','','',2,NULL),
          ('NetworkEquipment','firmware2','','',2,NULL),
          ('NetworkEquipment','contact','glpi_networkequipments','contact',403,NULL),
          ('NetworkEquipment','comments','glpi_networkequipments','comment',404,NULL),
          ('NetworkEquipment','uptime','glpi_plugin_fusinvsnmp_networkequipments',
             'uptime',3,NULL),
          ('NetworkEquipment','cpu','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',12,NULL),
          ('NetworkEquipment','cpuuser','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',401,NULL),
          ('NetworkEquipment','cpusystem','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',402,NULL),
          ('NetworkEquipment','serial','glpi_networkequipments','serial',13,NULL),
          ('NetworkEquipment','otherserial','glpi_networkequipments','otherserial',419,NULL),
          ('NetworkEquipment','name','glpi_networkequipments','name',20,NULL),
          ('NetworkEquipment','ram','glpi_networkequipments','ram',21,NULL),
          ('NetworkEquipment','memory','glpi_plugin_fusinvsnmp_networkequipments',
             'memory',22,NULL),
          ('NetworkEquipment','vtpVlanName','','',19,NULL),
          ('NetworkEquipment','vmvlan','','',430,NULL),
          ('NetworkEquipment','entPhysicalModelName','glpi_networkequipments',
             'networkequipmentmodels_id',17,NULL),
          ('NetworkEquipment','macaddr','glpi_networkequipments','ip',417,NULL),
## Network CDP (Walk)
          ('NetworkEquipment','cdpCacheAddress','','',409,NULL),
          ('NetworkEquipment','cdpCacheDevicePort','','',410,NULL),
          ('NetworkEquipment','lldpRemChassisId','','',431,NULL),
          ('NetworkEquipment','lldpRemPortId','','',432,NULL),
          ('NetworkEquipment','lldpLocChassisId','','',432,NULL),
          ('NetworkEquipment','vlanTrunkPortDynamicStatus','','',411,NULL),
          ('NetworkEquipment','dot1dTpFdbAddress','','',412,NULL),
          ('NetworkEquipment','ipNetToMediaPhysAddress','','',413,NULL),
          ('NetworkEquipment','dot1dTpFdbPort','','',414,NULL),
          ('NetworkEquipment','dot1dBasePortIfIndex','','',415,NULL),
          ('NetworkEquipment','ipAdEntAddr','','',421,NULL),
          ('NetworkEquipment','PortVlanIndex','','',422,NULL),
## NetworkPorts
          ('NetworkEquipment','ifIndex','','',408,NULL),
          ('NetworkEquipment','ifmtu','glpi_plugin_fusinvsnmp_networkports',
             'ifmtu',4,NULL),
          ('NetworkEquipment','ifspeed','glpi_plugin_fusinvsnmp_networkports',
             'ifspeed',5,NULL),
          ('NetworkEquipment','ifinternalstatus','glpi_plugin_fusinvsnmp_networkports',
             'ifinternalstatus',6,NULL),
          ('NetworkEquipment','iflastchange','glpi_plugin_fusinvsnmp_networkports',
             'iflastchange',7,NULL),
          ('NetworkEquipment','ifinoctets','glpi_plugin_fusinvsnmp_networkports',
             'ifinoctets',8,NULL),
          ('NetworkEquipment','ifoutoctets','glpi_plugin_fusinvsnmp_networkports',
             'ifoutoctets',9,NULL),
          ('NetworkEquipment','ifinerrors','glpi_plugin_fusinvsnmp_networkports',
             'ifinerrors',10,NULL),
          ('NetworkEquipment','ifouterrors','glpi_plugin_fusinvsnmp_networkports',
             'ifouterrors',11,NULL),
          ('NetworkEquipment','ifstatus','glpi_plugin_fusinvsnmp_networkports',
             'ifstatus',14,NULL),
          ('NetworkEquipment','ifPhysAddress','glpi_networkports','mac',15,NULL),
          ('NetworkEquipment','ifName','glpi_networkports','name',16,NULL),
          ('NetworkEquipment','ifType','','',18,NULL),
          ('NetworkEquipment','ifdescr','glpi_plugin_fusinvsnmp_networkports',
             'ifdescr',23,NULL),
          ('NetworkEquipment','portDuplex','glpi_plugin_fusinvsnmp_networkports',
             'portduplex',33,NULL),
## Printers
          ('Printer','model','glpi_printers','printermodels_id',25,NULL),
          ('Printer','enterprise','glpi_printers','manufacturers_id',420,NULL),
          ('Printer','serial','glpi_printers','serial',27,NULL),
          ('Printer','contact','glpi_printers','contact',405,NULL),
          ('Printer','comments','glpi_printers','comment',406,NULL),
          ('Printer','name','glpi_printers','comment',24,NULL),
          ('Printer','otherserial','glpi_printers','otherserial',418,NULL),
          ('Printer','memory','glpi_printers','memory_size',26,NULL),
          ('Printer','location','glpi_printers','locations_id',56,NULL),
          ('Printer','informations','','',165,165),
## Cartridges
          ('Printer','tonerblack','','',157,157),
          ('Printer','tonerblackmax','','',166,166),
          ('Printer','tonerblackused','','',167,167),
          ('Printer','tonerblackremaining','','',168,168),
          ('Printer','tonerblack2','','',157,157),
          ('Printer','tonerblack2max','','',166,166),
          ('Printer','tonerblack2used','','',167,167),
          ('Printer','tonerblack2remaining','','',168,168),
          ('Printer','tonercyan','','',158,158),
          ('Printer','tonercyanmax','','',169,169),
          ('Printer','tonercyanused','','',170,170),
          ('Printer','tonercyanremaining','','',171,171),
          ('Printer','tonermagenta','','',159,159),
          ('Printer','tonermagentamax','','',172,172),
          ('Printer','tonermagentaused','','',173,173),
          ('Printer','tonermagentaremaining','','',174,174),
          ('Printer','toneryellow','','',160,160),
          ('Printer','toneryellowmax','','',175,175),
          ('Printer','toneryellowused','','',176,176),
          ('Printer','toneryellowremaining','','',177,177),
          ('Printer','wastetoner','','',151,151),
          ('Printer','wastetonermax','','',190,190),
          ('Printer','wastetonerused','','',191,191),
          ('Printer','wastetonerremaining','','',192,192),
          ('Printer','cartridgeblack','','',134,134),
          ('Printer','cartridgeblackphoto','','',135,135),
          ('Printer','cartridgecyan','','',136,136),
          ('Printer','cartridgecyanlight','','',139,139),
          ('Printer','cartridgemagenta','','',138,138),
          ('Printer','cartridgemagentalight','','',140,140),
          ('Printer','cartridgeyellow','','',137,137),
          ('Printer','cartridgegrey','','',196,196),
          ('Printer','maintenancekit','','',156,156),
          ('Printer','maintenancekitmax','','',193,193),
          ('Printer','maintenancekitused','','',194,194),
          ('Printer','maintenancekitremaining','','',195,195),
          ('Printer','drumblack','','',161,161),
          ('Printer','drumblackmax','','',178,178),
          ('Printer','drumblackused','','',179,179),
          ('Printer','drumblackremaining','','',180,180),
          ('Printer','drumcyan','','',162,162),
          ('Printer','drumcyanmax','','',181,181),
          ('Printer','drumcyanused','','',182,182),
          ('Printer','drumcyanremaining','','',183,183),
          ('Printer','drummagenta','','',163,163),
          ('Printer','drummagentamax','','',184,184),
          ('Printer','drummagentaused','','',185,185),
          ('Printer','drummagentaremaining','','',186,186),
          ('Printer','drumyellow','','',164,164),
          ('Printer','drumyellowmax','','',187,187),
          ('Printer','drumyellowused','','',188,188),
          ('Printer','drumyellowremaining','','',189,189),
## Printers : Counter pages
          ('Printer','pagecountertotalpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total',28,128),
          ('Printer','pagecounterblackpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b',29,129),
          ('Printer','pagecountercolorpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color',30,130),
          ('Printer','pagecounterrectoversopages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_recto_verso',54,154),
          ('Printer','pagecounterscannedpages','glpi_plugin_fusinvsnmp_printerlogs',
             'scanned',55,155),
          ('Printer','pagecountertotalpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_print',423,1423),
          ('Printer','pagecounterblackpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b_print',424,1424),
          ('Printer','pagecountercolorpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color_print',425,1425),
          ('Printer','pagecountertotalpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_copy',426,1426),
          ('Printer','pagecounterblackpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b_copy',427,1427),
          ('Printer','pagecountercolorpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color_copy',428,1428),
          ('Printer','pagecountertotalpages_fax','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_fax',429,1429),
          ('Printer','pagecounterlargepages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_large',434,1434),
## Printers : NetworkPort
          ('Printer','ifPhysAddress','glpi_networkports','mac',58,NULL),
          ('Printer','ifName','glpi_networkports','name',57,NULL),
          ('Printer','ifaddr','glpi_networkports','ip',407,NULL),
          ('Printer','ifType','','',97,NULL),
          ('Printer','ifIndex','','',416,NULL),
## Computer
          ('Computer','serial','','serial',13,NULL),
          ('Computer','ifPhysAddress','','mac',15,NULL),
          ('Computer','ifaddr','','ip',407,NULL)";
   $DB->query($sql);


   /*
    * Update `glpi_dropdown_plugin_fusioninventory_mib_label`
    * to `glpi_plugin_fusinvsnmp_miblabels`
    */
   $migration->displayMessage("Update SNMP MIB labels");
   $sql = "RENAME TABLE `glpi_dropdown_plugin_fusioninventory_mib_label`
      TO `glpi_plugin_fusinvsnmp_miblabels`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_miblabels`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_miblabels`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);

   /*
    * Update `glpi_dropdown_plugin_fusioninventory_mib_object`
    * to `glpi_plugin_fusinvsnmp_mibobjects`
    */
   $migration->displayMessage("Udpate SNMP MIB objects");
   $sql = "RENAME TABLE `glpi_dropdown_plugin_fusioninventory_mib_object`
      TO `glpi_plugin_fusinvsnmp_mibobjects`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_mibobjects`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_mibobjects`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);

   /*
    * Update `glpi_dropdown_plugin_fusioninventory_mib_oid`
    * to `glpi_plugin_fusinvsnmp_miboids`
    */
   $migration->displayMessage("Update SNMP MIB OID");
   $sql = "RENAME TABLE `glpi_dropdown_plugin_fusioninventory_mib_oid`
      TO `glpi_plugin_fusinvsnmp_miboids`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_miboids`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_miboids`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);

   /*
    * Drop `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol`
    */
   $migration->displayMessage("Update SNMP authentication");
   $sql = "DROP TABLE `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol`";
   $DB->query($sql);

   /*
    * Drop `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol");
   $sql = "DROP TABLE `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol`";
   $DB->query($sql);

   /*
    * Drop `glpi_dropdown_plugin_fusioninventory_snmp_version`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_dropdown_plugin_fusioninventory_snmp_version");
   $sql = "DROP TABLE `glpi_dropdown_plugin_fusioninventory_snmp_version`";
   $DB->query($sql);

   /*
    * Migration des agents fusion
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_agents");
   $sql = "CREATE TABLE `glpi_plugin_fusinvsnmp_tmp_agents` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `snmpquery` int(1) NOT NULL DEFAULT '0',
   `threads_query` int(11) NOT NULL DEFAULT '1',
   `netdiscovery` int(1) NOT NULL DEFAULT '0',
   `threads_discovery` int(11) NOT NULL DEFAULT '1',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($sql);

    $sql = "CREATE TABLE `glpi_plugin_fusinvinventory_tmp_agents` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `inventory` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($sql);

   $sql = "CREATE TABLE `glpi_plugin_fusioninventory_agentmodules` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   `modulename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `is_active` int(1) NOT NULL DEFAULT '0',
   `exceptions` TEXT COMMENT 'array(agent_id)',
   `entities_id` int(11) NOT NULL DEFAULT '-1',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`plugins_id`, `modulename`),
   KEY `is_active` (`is_active`),
   KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($sql);
   if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
   }
   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
   $input = array();
   $input['plugins_id'] = $plugins_id;
   $input['modulename'] = "WAKEONLAN";
   $input['is_active']  = 0;
   $input['exceptions'] = exportArrayToDB(array());
   $PluginFusioninventoryAgentmodule->add($input);

   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_agents`";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      if ($data['module_inventory'] == '1') {
         $sql_ins = "INSERT INTO `glpi_plugin_fusinvinventory_tmp_agents`
            VALUE('".$data['ID']."', '1')";
         $DB->query($sql_ins);
      }
        
      $sql_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_tmp_agents`
         VALUE('".$data['ID']."',
            '".$data['module_snmpquery']."',
            '".$data['threads_query']."',
            '".$data['module_netdiscovery']."',
            '".$data['threads_discovery']."')";
      $DB->query($sql_ins);

      if ($data['module_wakeonlan'] == '1') {
         $a_modules = $PluginFusioninventoryAgentmodule->find("`modulename`='WAKEONLAN'");
         $a_module = current($a_modules);
         $a_exceptions = importArrayFromDB($a_module['exceptions']);
         $a_exceptions[] = $data['ID'];
         $a_module['exceptions'] = exportArrayToDB($a_exceptions);
         $PluginFusioninventoryAgentmodule->update($a_module);
      }      
   }
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      DROP `core_discovery`,
      DROP `threads_discovery`,
      DROP `core_query`,
      DROP `threads_query`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `last_agent_update` `last_contact` DATETIME NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `fusioninventory_agent_version` `version` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `key` `device_id` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `on_device` `items_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `device_type` `itemtype` VARCHAR( 100 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      DROP `module_inventory`,
      DROP `module_netdiscovery`,
      DROP `module_snmpquery`,
      DROP `module_wakeonlan`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      ADD `entities_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `id` ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      ADD `is_recursive` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `entities_id` ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      DROP INDEX `key`,
      ADD INDEX `device_id` (`device_id`),
      ADD INDEX `item` (`itemtype`,`items_id`) ";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_agents_inventory_state`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_agents_inventory_state");
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_agents_inventory_state`";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_connection_history`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_connection_history");
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_connection_history`";
   $DB->query($sql);

   /*
    * Get config from `glpi_plugin_fusioninventory_config`
    * and set config into `glpi_plugin_fusioninventory_configs`
    * and drop `glpi_plugin_fusioninventory_config`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_configs");
   $sql = "CREATE TABLE `glpi_plugin_fusinvsnmp_tmp_configs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `authsnmp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($sql);

   $sql = "CREATE TABLE `glpi_plugin_fusioninventory_configs` (
   `id` int(1) NOT NULL AUTO_INCREMENT,
   `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`type`, `plugins_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
   $DB->query($sql);

   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_config` ";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $sql_ins = "INSERT INTO `glpi_plugin_fusioninventory_configs`
            (`type`, `value`, `plugins_id`)
         VALUES('version', '2.3.0', '".$plugins_id."'),
               ( 'ssl_only', '".$data['ssl_only']."', '".$plugins_id."'),
               ( 'inventory_frequence', '".$data['inventory_frequence']."', '".$plugins_id."'),
               ( 'delete_task', '".$data['delete_agent_process']."', '".$plugins_id."'),
               ( 'agent_port', '62354', '".$plugins_id."'),
               ( 'extradebug', '0', '".$plugins_id."')";
      $DB->query($sql_ins);

      $sql_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_tmp_configs`
         VALUES('1', '".$data['authsnmp']."')";
      $DB->query($sql_ins);
   }
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_config`";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_config_modules`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_config_modules");
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_config_modules`";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_construct_device`
    * to `glpi_plugin_fusinvsnmp_constructdevices`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_constructdevices");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_construct_device`
      TO `glpi_plugin_fusinvsnmp_constructdevices`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      CHANGE `FK_glpi_enterprise` `manufacturers_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      DROP `device`,
      DROP `firmware`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      CHANGE `type` `itemtype` VARCHAR( 100 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);   
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      CHANGE `snmpmodel_id` `plugin_fusinvsnmp_models_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   if (isIndex('glpi_plugin_fusinvsnmp_constructdevices', 'type')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
         DROP INDEX `type`";
      $DB->query($sql);
   }
   if (!isIndex('glpi_plugin_fusinvsnmp_constructdevices', 'plugin_fusinvsnmp_models_id')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      ADD INDEX `plugin_fusinvsnmp_models_id` ( `manufacturers_id`, `plugin_fusinvsnmp_models_id` )";
      $DB->query($sql);
   }
   if (!isIndex('glpi_plugin_fusinvsnmp_constructdevices', 'itemtype')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      ADD INDEX `itemtype` ( `itemtype` ) ";
      $DB->query($sql);
   }

   /*
    * Update `glpi_plugin_fusioninventory_construct_walks`
    * to `glpi_plugin_fusinvsnmp_constructdevicewalks`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_constructdevicewalks");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_construct_walks`
      TO `glpi_plugin_fusinvsnmp_constructdevicewalks`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevicewalks`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevicewalks`
      CHANGE `construct_device_id` `plugin_fusinvsnmp_constructdevices_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevicewalks`
      ADD INDEX `plugin_fusinvsnmp_constructdevices_id` ( `plugin_fusinvsnmp_constructdevices_id` ) ";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_construct_mibs`
    * to `glpi_plugin_fusinvsnmp_constructdevice_miboids`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_constructdevice_miboids");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_construct_mibs`
      TO `glpi_plugin_fusinvsnmp_constructdevice_miboids`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `mib_oid_id` `plugin_fusinvsnmp_miboids_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `construct_device_id` `plugin_fusinvsnmp_constructdevices_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   // Convert Mapping
   foreach ($typetoname as $key => $itemtype) {
      $sql = "SELECT * FROM `glpi_plugin_fusioninventory_mappings`
         WHERE `itemtype`='".$itemtype."' ";
      $result=$DB->query($sql);
      while ($data=$DB->fetch_array($result)) {
         $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
         SET `mapping_name` = '".$data['id']."'
         WHERE `mapping_type`='".$key."'
            AND `mapping_name`='".$data['name']."' ";
         $DB->query($sql_update);
         if (($data['name'] == 'cdpCacheDevicePort')
              OR ($data['name'] == 'cdpCacheAddress')) {

            $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
            SET `mapping_name` = '".$data['id']."',
               `mapping_type`='".$key."'
            WHERE `mapping_name`='".$data['name']."'
               AND `mapping_type` IS NULL";
            $DB->query($sql_update);
         }
      }
   }
   $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      SET `mapping_name` = '0'
      WHERE `mapping_name`='' ";
   $DB->query($sql_update);
   // End convert mapping
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `mapping_name` `plugin_fusioninventory_mappings_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `mapping_type` `itemtype` VARCHAR( 100 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);
   // Convert datas
   foreach ($typetoname as $key => $itemtype) {
      $table = getTableForItemType($itemtype);
      $sql = "UPDATE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
         SET `itemtype` = '".$table."'
         WHERE `itemtype` = '".$key."'";
      $DB->query($sql);
   }
   if (isIndex('glpi_plugin_fusinvsnmp_constructdevice_miboids', 'construct_device_id')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
         DROP INDEX `construct_device_id`";
      $DB->query($sql);
   }
   if (!isIndex('glpi_plugin_fusinvsnmp_constructdevice_miboids', 'unicity')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
         ADD UNIQUE `unicity` ( `plugin_fusinvsnmp_miboids_id`,
         `plugin_fusinvsnmp_constructdevices_id`, `plugin_fusioninventory_mappings_id` )";
      $DB->query($sql);
   }
   if (!isIndex('glpi_plugin_fusinvsnmp_constructdevice_miboids', 'itemtype')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      ADD INDEX `itemtype` ( `itemtype` )";
      $DB->query($sql);
   }

   /*
    * Update `glpi_plugin_fusioninventory_snmp_history_connections`
    * to `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_networkportconnectionlogs");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_snmp_history_connections`
      TO `glpi_plugin_fusinvsnmp_networkportconnectionlogs`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `date` `date_mod` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `FK_port_source` `networkports_id_source` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `FK_port_destination` `networkports_id_destination` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);

   // ***** TODO : process_number to taskjob_id

        // TEMP :
         $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
            SET `process_number` = '0'";
         $DB->query($sql_update);

   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `process_number` `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      ADD INDEX `networkports_id_source` ( `networkports_id_source`, `networkports_id_destination`,
          `plugin_fusioninventory_agentprocesses_id` ),
      ADD INDEX `date_mod` (`date_mod`) ";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_discovery`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_discovery");
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_discovery`";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_errors`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_errors");
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_errors`";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_lock`
    * to `glpi_plugin_fusioninventory_locks`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_locks");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_lock`
      TO `glpi_plugin_fusioninventory_locks`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_locks`
      CHANGE `itemtype` `tablename` VARCHAR( 64 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_locks`
      CHANGE `fields` `tablefields` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   // Convert datas
   foreach ($typetoname as $key => $itemtype) {
      $table = getTableForItemType($itemtype);
      $sql = "UPDATE `glpi_plugin_fusioninventory_locks`
         SET `tablename` = '".$table."'
         WHERE `tablename` = '".$key."'";
      $DB->query($sql);
   }
   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_locks`";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $a_fields = importArrayFromDB($data['tablefields']);
      $a_input = array();
      foreach ($a_fields as $key => $field) {
         $a_input[] = $field;
      }
      $data['tablefields'] = exportArrayToDB($a_input);
      $sql_update = "UPDATE `glpi_plugin_fusioninventory_locks`
         SET `tablefields` = '".$data['tablefields']."'
         WHERE `id`='".$data['id']."' ";
      $DB->query($sql_update);
   }
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_locks`
      DROP INDEX `itemtype`,
      ADD INDEX `tablename` ( `tablename` ),
      ADD INDEX `items_id` (`items_id`) ";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_lockable`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_lockable");
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_lockable`";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_mib_networking`
    * to `glpi_plugin_fusinvsnmp_modelmibs`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_modelmibs");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_mib_networking`
      TO `glpi_plugin_fusinvsnmp_modelmibs`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `FK_model_infos` `plugin_fusinvsnmp_models_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `FK_mib_label` `plugin_fusinvsnmp_miblabels_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `FK_mib_oid` `plugin_fusinvsnmp_miboids_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `FK_mib_object` `plugin_fusinvsnmp_mibobjects_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   // Convert Mapping
   foreach ($typetoname as $key => $itemtype) {
      $sql = "SELECT * FROM `glpi_plugin_fusioninventory_mappings`
         WHERE `itemtype`='".$itemtype."' ";
      $result=$DB->query($sql);
      while ($data=$DB->fetch_array($result)) {
         $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_modelmibs`
         SET `mapping_type` = '".$data['id']."'
         WHERE `mapping_type`='".$key."'
            AND `mapping_name`='".$data['name']."' ";
         $DB->query($sql_update);
      }
   }
   $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_modelmibs`
      SET `mapping_type` = '0'
      WHERE `mapping_type`='' ";
   $DB->query($sql_update);
   // End convert mapping
   // End convert mapping
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `mapping_type` `plugin_fusioninventory_mappings_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      DROP `mapping_name`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `activation` `is_active` INT( 1 ) NOT NULL DEFAULT '1'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
       DROP INDEX `FK_model_infos`,
       DROP INDEX `FK_model_infos_2`,
       DROP INDEX `FK_model_infos_3`,
       DROP INDEX `FK_model_infos_4`,
       DROP INDEX `oid_port_dyn`,
       DROP INDEX `activation`,
       ADD INDEX `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`),
       ADD INDEX `plugin_fusinvsnmp_models_id_2` (`plugin_fusinvsnmp_models_id`,`oid_port_dyn`),
       ADD INDEX `plugin_fusinvsnmp_models_id_3` (`plugin_fusinvsnmp_models_id`,`oid_port_counter`,`plugin_fusioninventory_mappings_id`),
       ADD INDEX `plugin_fusinvsnmp_models_id_4` (`plugin_fusinvsnmp_models_id`,`plugin_fusioninventory_mappings_id`),
       ADD INDEX `oid_port_dyn` (`oid_port_dyn`),
       ADD INDEX `is_active` (`is_active`),
       ADD INDEX `plugin_fusioninventory_mappings_id` (`plugin_fusioninventory_mappings_id`) ";
   $DB->query($sql);
   
   /*
    * Update `glpi_plugin_fusioninventory_model_infos`
    * to `glpi_plugin_fusinvsnmp_models`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_models");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_model_infos`
      TO `glpi_plugin_fusinvsnmp_models`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      CHANGE `device_type` `itemtype` VARCHAR( 100 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      DROP `deleted`,
      DROP `FK_entities`,
      DROP `activation`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   // Convert datas
   foreach ($typetoname as $key => $itemtype) {
      $sql = "UPDATE `glpi_plugin_fusinvsnmp_models`
         SET `itemtype` = '".$itemtype."'
         WHERE `itemtype` = '".$key."'";
      $DB->query($sql);
   }
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      DROP INDEX `device_type`,
      ADD INDEX `itemtype` (`itemtype`) ";
   $DB->query($sql);
   
   /*
    * Update `glpi_plugin_fusioninventory_networking`
    * to `glpi_plugin_fusinvsnmp_networkequipments`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_networkequipments");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_networking`
      TO `glpi_plugin_fusinvsnmp_networkequipments`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `FK_networking` `networkequipments_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      ADD `sysdescr` TEXT NULL DEFAULT NULL AFTER `networkequipments_id` ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `FK_model_infos` `plugin_fusinvsnmp_models_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `FK_snmp_connection` `plugin_fusinvsnmp_configsecurities_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `memory` `memory` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      DROP INDEX `FK_networking`,
      DROP INDEX `FK_model_infos`,
      ADD INDEX `networkequipments_id` (`networkequipments_id`),
      ADD INDEX `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`,
         `plugin_fusinvsnmp_configsecurities_id`) ";
   $DB->query($sql);
   // Put networkequipment comment to sysdescr
   $NetworkEquipment = new NetworkEquipment();
   $a_networkequipemts = $NetworkEquipment->find();
   foreach ($a_networkequipemts as $data) {
      $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_networkequipments`
         SET `sysdescr`='".$data['comment']."'
         WHERE `networkequipments_id`='".$data['id']."' ";
      $DB->query($sql_update);
   }

   /*
    * Update `glpi_plugin_fusioninventory_networking_ifaddr`
    * to `glpi_plugin_fusinvsnmp_networkequipmentips`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_networkequipmentips");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_networking_ifaddr`
      TO `glpi_plugin_fusinvsnmp_networkequipmentips`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipmentips`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipmentips`
      CHANGE `FK_networking` `networkequipments_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipmentips`
      CHANGE `ifaddr` `ip` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipmentips`
      DROP INDEX `ifaddr`,
      ADD INDEX `ip` (`ip`),
      ADD INDEX `networkequipments_id` (`networkequipments_id`) ";
   $DB->query($sql);
   // TODO : check all IP addresses valides
   $sql = "SELECT * FROM `glpi_plugin_fusinvsnmp_networkequipmentips`";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $delete = 1;
      if (strstr($data['ip'], ".")){
         $splitip = explode(".", $data['ip']);
         if (count($splitip) == '4') {
            $delete = 0;
         }
      }
      if ($delete == '1') {
         $sql = "DELETE FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
            WHERE `id`='".$data['id']."'";
         $DB->query($sql);
      }      
   }

   /*
    * Update `glpi_plugin_fusioninventory_networking_ports`
    * to `glpi_plugin_fusinvsnmp_networkports`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_networkequipmentips");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_networking_ports`
      TO `glpi_plugin_fusinvsnmp_networkports`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkports`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkports`
      CHANGE `FK_networking_ports` `networkports_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkports`
      CHANGE `ifspeed` `ifspeed` BIGINT( 50 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkports`
      CHANGE `ifmac` `mac` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkports`
      DROP INDEX `FK_networking_ports`,
      ADD INDEX `networkports_id` (`networkports_id`) ";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_printers_history`
    * to `glpi_plugin_fusinvsnmp_printerlogs`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_printerlogs");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_printers_history`
      TO `glpi_plugin_fusinvsnmp_printerlogs`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
      CHANGE `FK_printers` `printers_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   if (!FieldExists('glpi_plugin_fusinvsnmp_printerlogs', 'pages_total_print')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
         ADD `pages_total_print` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists('glpi_plugin_fusinvsnmp_printerlogs', 'pages_n_b_print')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
         ADD `pages_n_b_print` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists('glpi_plugin_fusinvsnmp_printerlogs', 'pages_color_print')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
         ADD `pages_color_print` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists('glpi_plugin_fusinvsnmp_printerlogs', 'pages_total_copy')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
         ADD `pages_total_copy` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists('glpi_plugin_fusinvsnmp_printerlogs', 'pages_n_b_copy')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
         ADD `pages_n_b_copy` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists('glpi_plugin_fusinvsnmp_printerlogs', 'pages_color_copy')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
         ADD `pages_color_copy` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists('glpi_plugin_fusinvsnmp_printerlogs', 'pages_total_fax')) {
      $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
         ADD `pages_total_fax` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
      ADD INDEX `printers_id` ( `printers_id` , `date` ) ";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_printers`
    * to `glpi_plugin_fusinvsnmp_printers`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_printers");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_printers`
      TO `glpi_plugin_fusinvsnmp_printers`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      CHANGE `FK_printers` `printers_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      ADD `sysdescr` TEXT NULL DEFAULT NULL AFTER `printers_id` ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      CHANGE `FK_model_infos` `plugin_fusinvsnmp_models_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      CHANGE `FK_snmp_connection` `plugin_fusinvsnmp_configsecurities_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      DROP INDEX `FK_printers`,
      DROP INDEX `FK_snmp_connection`,
      ADD UNIQUE `unicity` (`printers_id`),
      ADD INDEX `plugin_fusinvsnmp_configsecurities_id` (`plugin_fusinvsnmp_configsecurities_id`),
      ADD INDEX `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`) ";
   $DB->query($sql);
   // Put printer comment to sysdescr
   $Printer = new Printer();
   $a_printers = $Printer->find();
   foreach ($a_printers as $data) {
      $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_printers`
         SET `sysdescr`='".$data['comment']."'
         WHERE `printers_id`='".$data['id']."' ";
      $DB->query($sql_update);
   }

   /*
    * Update `glpi_plugin_fusioninventory_printers_cartridges`
    * to `glpi_plugin_fusinvsnmp_printercartridges`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_printercartridges");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_printers_cartridges`
      TO `glpi_plugin_fusinvsnmp_printercartridges`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printercartridges`
      CHANGE `ID` `id` INT( 100 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printercartridges`
      CHANGE `FK_printers` `printers_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   // Convert Mapping
   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_mappings`
      WHERE `itemtype`='Printer' ";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      if (strstr($data['name'], 'cartridge')) {
         $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_printercartridges`
         SET `object_name` = '".$data['id']."'
         WHERE `object_name`='".$data['name']."' ";
         $DB->query($sql_update);
         $data['name'] = str_replace("cartridge", "cartridges", $data['name']);
      }
      $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_printercartridges`
      SET `object_name` = '".$data['id']."'
      WHERE `object_name`='".$data['name']."' ";
      $DB->query($sql_update);
   }
   $sql_delete = "DELETE FROM `glpi_plugin_fusinvsnmp_printercartridges`
      WHERE `object_name` LIKE 'cartridges%'";
   $DB->query($sql_delete);
    // End convert mapping
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printercartridges`
      CHANGE `object_name` `plugin_fusioninventory_mappings_id` INT( 11 ) NOT NULL DEFAULT '0' ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printercartridges`
      CHANGE `FK_cartridges` `cartridges_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printercartridges`
      ADD INDEX `printers_id` (`printers_id`),
      ADD INDEX `plugin_fusioninventory_mappings_id` (`plugin_fusioninventory_mappings_id`),
      ADD INDEX `cartridges_id` (`cartridges_id`) ";
   $DB->query($sql);


   /*
    * Drop `glpi_plugin_fusioninventory_task`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_task");
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_task`";
   $DB->query($sql);

   /*
    * Recreate profiles
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - Profile migration");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_profiles`
      TO `glpi_plugin_fusioninventory_temp_profiles`";
   $DB->query($sql);
   $sql = "CREATE TABLE `glpi_plugin_fusioninventory_profiles` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `right` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   `profiles_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`type`, `plugins_id`, `profiles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
   $DB->query($sql);
   if (!class_exists('PluginFusioninventoryStaticmisc')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/staticmisc.class.php");
   }
   
   // Convert datas
   if (is_callable(array("PluginFusioninventoryStaticmisc", "profiles"))) {
      $a_profile = call_user_func(array("PluginFusioninventoryStaticmisc", "profiles"));
      foreach ($a_profile as $data) {
         $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
            (`type`, `right`, `plugins_id`, `profiles_id`)
            VALUES('".$data['profil']."', 'w', '".$plugins_id."', '".$_SESSION['glpiactiveprofile']['id']."')";
         $DB->query($sql_ins);
      }
   }
   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_temp_profiles`";
   $result=$DB->query($sql);
   $Profile = new Profile();
   while ($data=$DB->fetch_array($result)) {
      $a_profiles = $Profile->find("`name`='".$data['name']."'");
      $a_profile = current($a_profiles);
      $profile_id = $a_profile['id'];
      if ($profile_id != $_SESSION['glpiactiveprofile']['id']) {
         if (!is_null($data['agents'])) {
            $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
               (`type`, `right`, `plugins_id`, `profiles_id`)
               VALUES('agent', '".$data['agents']."', '".$plugins_id."', '".$profile_id."')";
            $DB->query($sql_ins);
         }
         if (!is_null($data['configuration'])) {
            $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
               (`type`, `right`, `plugins_id`, `profiles_id`)
               VALUES('configuration', '".$data['configuration']."', '".$plugins_id."', '".$profile_id."')";
            $DB->query($sql_ins);
         }
         if (!is_null($data['wol'])) {
            $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
               (`type`, `right`, `plugins_id`, `profiles_id`)
               VALUES('wol', '".$data['wol']."', '".$plugins_id."', '".$profile_id."')";
            $DB->query($sql_ins);
         }
         if (!is_null($data['remotecontrol'])) {
            $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
               (`type`, `right`, `plugins_id`, `profiles_id`)
               VALUES('remotecontrol', '".$data['remotecontrol']."', '".$plugins_id."', '".$profile_id."')";
            $DB->query($sql_ins);
         }
         if (!is_null($data['unknowndevices'])) {
            $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
               (`type`, `right`, `plugins_id`, `profiles_id`)
               VALUES('unknowndevice', '".$data['unknowndevices']."', '".$plugins_id."', '".$profile_id."')";
            $DB->query($sql_ins);
         }
         if (!is_null($data['agentsprocesses'])) {
            $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
               (`type`, `right`, `plugins_id`, `profiles_id`)
               VALUES('task', '".$data['agentsprocesses']."', '".$plugins_id."', '".$profile_id."')";
            $DB->query($sql_ins);
         }
      }
   }
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_temp_profiles`
      TO `glpi_plugin_fusinvsnmp_temp_profiles`";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_rangeip`
    * to `glpi_plugin_fusinvsnmp_ipranges`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_ipranges");
   $sql = "CREATE TABLE `glpi_plugin_fusinvsnmp_tmp_tasks` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `rangeip_id` int(11) NOT NULL DEFAULT '0',
   `discoveragent_id` int(11) NOT NULL DEFAULT '0',
   `discoveractive` int(1) NOT NULL DEFAULT '0',
   `queryagent_id` int(11) NOT NULL DEFAULT '0',
   `queryactive` int(1) NOT NULL DEFAULT '0',
   `entities_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($sql);
   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_rangeip`";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $sql_insert = "INSERT INTO `glpi_plugin_fusinvsnmp_tmp_tasks`
         (`rangeip_id`, `discoveragent_id`, `discoveractive`,
         `queryagent_id`, `queryactive`, `entities_id`)
         VALUES ('".$data['ID']."',
                 '".$data['FK_fusioninventory_agents_discover']."',
                 '".$data['discover']."',
                 '".$data['FK_fusioninventory_agents_query']."',
                 '".$data['query']."',
                 '".$data['FK_entities']."')";
      $DB->query($sql_insert);
   }

   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_rangeip`
      TO `glpi_plugin_fusinvsnmp_ipranges`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      DROP `FK_fusioninventory_agents_discover`,
      DROP `FK_fusioninventory_agents_query`,
      DROP `discover`,
      DROP `query`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      MODIFY COLUMN FK_entities INT AFTER name";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      CHANGE `FK_entities` `entities_id` INT( 11 ) NULL DEFAULT '0' ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      CHANGE `ifaddr_start` `ip_start` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      CHANGE `ifaddr_end` `ip_end` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      ADD INDEX `entities_id` (`entities_id`) ";
   $DB->query($sql);
   
   /*
    * Update `glpi_plugin_fusioninventory_snmp_connection`
    * to `glpi_plugin_fusinvsnmp_configsecurities`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_configsecurities");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_snmp_connection`
      TO `glpi_plugin_fusinvsnmp_configsecurities`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `FK_snmp_version` `snmpversion` VARCHAR( 8 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `sec_name` `username` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `auth_protocol` `authentication` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `priv_protocol` `encryption` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `deleted` `is_deleted` INT( 1 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      DROP INDEX `FK_snmp_version`,
      ADD INDEX `snmpversion` (`snmpversion`),
      ADD INDEX `is_deleted` (`is_deleted`) ";
   $DB->query($sql);
   
   /*
    * Update `glpi_plugin_fusioninventory_snmp_history`
    * to `glpi_plugin_fusinvsnmp_networkportlogs`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusinvsnmp_networkportlogs");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_snmp_history`
      TO `glpi_plugin_fusinvsnmp_networkportlogs`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `FK_ports` `networkports_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   // Convert Mapping
   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_mappings`";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_networkportlogs`
      SET `Field` = '".$data['id']."'
      WHERE `Field`='".$data['name']."' ";
      $DB->query($sql_update);
      if ($data['name'] == "vlanTrunkPortDynamicStatus") {
         $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_networkportlogs`
         SET `Field` = '".$data['id']."'
         WHERE `Field`='trunk' ";
         $DB->query($sql_update);
      }
   }
   $sql = "DELETE FROM `glpi_plugin_fusinvsnmp_networkportlogs`
      WHERE `Field`='ip'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_plugin_fusinvsnmp_networkportlogs`
      WHERE `Field` NOT REGEXP '[0-9]+'";
   $DB->query($sql);

   // End convert mapping
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `Field` `plugin_fusioninventory_mappings_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `old_value` `value_old` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      DROP `old_device_type`,
      DROP `old_device_ID`,
      DROP `new_device_type`,
      DROP `new_device_ID`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `new_value` `value_new` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);

      // ***** TODO : process_number to taskjob_id

        // TEMP :
         $sql_update = "UPDATE `glpi_plugin_fusinvsnmp_networkportlogs`
            SET `FK_process` = '0'";
         $DB->query($sql_update);
         
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `FK_process` `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      DROP INDEX `FK_ports`,
      ADD INDEX `networkports_id` (`networkports_id`,`date_mod`),
      ADD INDEX `plugin_fusioninventory_mappings_id` (`plugin_fusioninventory_mappings_id`),
      ADD INDEX `plugin_fusioninventory_agentprocesses_id` (`plugin_fusioninventory_agentprocesses_id`),
      ADD INDEX `date_mod` (`date_mod`) ";
   $DB->query($sql);
   /*
    * Update `glpi_plugin_fusioninventory_unknown_device`
    * to `glpi_plugin_fusioninventory_unknowndevices`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_unknowndevices");
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_unknown_device`
      TO `glpi_plugin_fusioninventory_unknowndevices`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvsnmp_unknowndevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_unknowndevices_id` int(11) NOT NULL DEFAULT '0',
   `sysdescr` TEXT,
   `plugin_fusinvsnmp_models_id` INT( 11 ) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_configsecurities_id` INT( 11 ) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusioninventory_unknowndevices_id` (`plugin_fusioninventory_unknowndevices_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
   $DB->query($sql);

   // Convert data for SNMP
   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_unknowndevices`
      WHERE `snmp`='1' ";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $sql_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_unknowndevices`
            (`plugin_fusioninventory_unknowndevices_id`,
            `plugin_fusinvsnmp_models_id`,
            `plugin_fusinvsnmp_configsecurities_id`)
          VALUES('".$data['id']."',
                 '".$data['FK_model_infos']."',
                 '".$data['FK_snmp_connection']."')";
      $DB->query($sql_ins);
   }
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      DROP `dnsname`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `FK_entities` `entities_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `location` `locations_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `deleted` `is_deleted` SMALLINT( 6 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `type` `item_type` VARCHAR( 255 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      DROP `snmp`,
      DROP `FK_model_infos`,
      DROP `FK_snmp_connection`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `FK_agent` `plugin_fusioninventory_agents_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `ifaddr` `ip` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `ifmac` `mac` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      ADD `states_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      ADD INDEX `entities_id` (`entities_id`),
      ADD INDEX `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`),
      ADD INDEX `is_deleted` (`is_deleted`),
      ADD INDEX `date_mod` (`date_mod`) ";
   $DB->query($sql);
   
   // Convert datas
   foreach ($typetoname as $key => $itemtype) {
      $sql = "UPDATE `glpi_plugin_fusioninventory_unknowndevices`
         SET `item_type` = '".$itemtype."'
         WHERE `item_type` = '".$key."'";
      $DB->query($sql);
   }

   /*
    * Drop `glpi_plugin_fusioninventory_connection_stats`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_connection_stats");
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_connection_stats`";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_walks`
    */
   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - glpi_plugin_fusioninventory_walks");
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_walks`";
   $DB->query($sql);




   $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-2.3.0-update.sql";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
      if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
   }


   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml');
   }


   CronTask::Register('PluginFusioninventoryTaskjob', 'taskscheduler', '60', array('mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30));

   if (!class_exists('PluginFusioninventorySetup')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   }
   if (!class_exists('PluginFusioninventoryRuleImportEquipmentCollection')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/ruleimportequipmentcollection.class.php");
   }
   if (!class_exists('PluginFusioninventoryRuleImportEquipment')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/ruleimportequipment.class.php");
   }

   $PluginFusioninventorySetup = new PluginFusioninventorySetup();
   $PluginFusioninventorySetup->initRules();

   // Put fusinvsnmp into state "to update"
   $Plugin = new Plugin();
   $a_plugins = $Plugin->find("`directory`='fusinvsnmp'");
   $input = array();
   if (count($a_plugins)) {
      $input = current($a_plugins);
      $input['state '] = 6;
      $input['version'] = "2.2.1";
      $plugin->update($input);
      $snmp_id = $input['id'];
   } else {
      $input['directory'] = "fusinvsnmp";
      $input['name'] = "FusionInventory SNMP";
      $input['state '] = 6;
      $input['version'] = "2.2.1";
      $snmp_id = $plugin->add($input);
   }
   $sql_ins = "INSERT INTO `glpi_plugin_fusioninventory_configs`
         (`type`, `value`, `plugins_id`)
      VALUES('version', '2.2.1', '".$snmp_id."')";
   $DB->query($sql_ins);

   if (!class_exists('PluginFusioninventoryUnknownDevice')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/unknowndevice.class.php");
   }

   /*
    *  Convert displaypreferences
    */
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5150' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusioninventoryUnknownDevice'
      WHERE `itemtype`='5153' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusioninventoryAgent'
      WHERE `itemtype`='5158' ";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5161' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusioninventoryConfig'
      WHERE `itemtype`='5165' ";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5166' ";
   $DB->query($sql);


   plugin_fusioninventory_displayMigrationMessage("230", $LANG['update'][141]." - Clean unknown devices");
   $ptud = new PluginFusioninventoryUnknownDevice();
   $ptud->CleanOrphelinsConnections();


  plugin_fusioninventory_displayMigrationMessage("230"); // End
}

?>