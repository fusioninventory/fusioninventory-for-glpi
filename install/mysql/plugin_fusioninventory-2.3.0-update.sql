############### Drop ###############

DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknown_mac`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_computers`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_snmp_networking`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_connection_history`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_connection_stats`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_discovery`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_errors`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_version`;  -- obsolete table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_walks`;  -- obsolete table

############## Create ##############


CREATE TABLE `glpi_plugin_fusioninventory_modules` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `locale` INT( 4 ) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `glpi_plugin_fusioninventory_mappings` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `tablefield` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `locale` INT( 4 ) NOT NULL,
   `shortlocale` INT( 4 ) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `itemtype` (`itemtype`)
##   UNIQUE KEY `unicity` (`name`, `itemtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

############## Alter ##############


############## Rename ###############

## Rename tables

RENAME TABLE `glpi_plugin_fusioninventory_agents_inventory_state` TO `glpi_plugin_fusioninventory_agentinventorystates`;
RENAME TABLE `glpi_plugin_fusioninventory_agents_errors` TO `glpi_plugin_fusioninventory_agentprocesserrors`;
RENAME TABLE `glpi_plugin_fusioninventory_agents_processes` TO `glpi_plugin_fusioninventory_agentprocesses`;
RENAME TABLE `glpi_plugin_fusioninventory_agents` TO `glpi_plugin_fusioninventory_agents`;
RENAME TABLE `glpi_plugin_fusioninventory_config_snmp_history` TO `glpi_plugin_fusioninventory_configlogfields`;
RENAME TABLE `glpi_plugin_fusioninventory_config_modules` TO `glpi_plugin_fusioninventory_configmodules`;
RENAME TABLE `glpi_plugin_fusioninventory_config` TO `glpi_plugin_fusioninventory_configs`;
RENAME TABLE `glpi_plugin_fusioninventory_snmp_connection` TO `glpi_plugin_fusioninventory_configsnmpsecurities`;
RENAME TABLE `glpi_plugin_fusioninventory_construct_mibs` TO `glpi_plugin_fusioninventory_constructdevice_miboids`;
RENAME TABLE `glpi_plugin_fusioninventory_construct_device` TO `glpi_plugin_fusioninventory_constructdevices`;
RENAME TABLE `glpi_plugin_fusioninventory_construct_walks` TO `glpi_plugin_fusioninventory_constructdevicewalks`;
RENAME TABLE `glpi_plugin_fusioninventory_rangeip` TO `glpi_plugin_fusioninventory_ipranges`;
RENAME TABLE `glpi_plugin_fusioninventory_lockable` TO `glpi_plugin_fusioninventory_lockables`;
RENAME TABLE `glpi_plugin_fusioninventory_lock` TO `glpi_plugin_fusioninventory_locks`;
RENAME TABLE `glpi_dropdown_plugin_fusioninventory_mib_label` TO `glpi_plugin_fusioninventory_miblabels`;
RENAME TABLE `glpi_dropdown_plugin_fusioninventory_mib_object` TO `glpi_plugin_fusioninventory_mibobjects`;
RENAME TABLE `glpi_dropdown_plugin_fusioninventory_mib_oid` TO `glpi_plugin_fusioninventory_miboids`;
RENAME TABLE `glpi_plugin_fusioninventory_mib_networking` TO `glpi_plugin_fusioninventory_snmpmodelmibs`;
RENAME TABLE `glpi_plugin_fusioninventory_model_infos` TO `glpi_plugin_fusioninventory_snmpmodels`;
RENAME TABLE `glpi_plugin_fusioninventory_networking_ifaddr` TO `glpi_plugin_fusioninventory_networkequipmentips`;
RENAME TABLE `glpi_plugin_fusioninventory_networking` TO `glpi_plugin_fusioninventory_networkequipments`;
RENAME TABLE `glpi_plugin_fusioninventory_snmp_history_connections` TO `glpi_plugin_fusioninventory_networkportconnectionlogs`;
RENAME TABLE `glpi_plugin_fusioninventory_snmp_history` TO `glpi_plugin_fusioninventory_networkportlogs`;
RENAME TABLE `glpi_plugin_fusioninventory_networking_ports` TO `glpi_plugin_fusioninventory_networkports`;
RENAME TABLE `glpi_plugin_fusioninventory_printers_history` TO `glpi_plugin_fusioninventory_printerlogs`;
RENAME TABLE `glpi_plugin_fusioninventory_printers` TO `glpi_plugin_fusioninventory_printers`;
RENAME TABLE `glpi_plugin_fusioninventory_printers_cartridges` TO `glpi_plugin_fusioninventory_printercartridges`;
RENAME TABLE `glpi_plugin_fusioninventory_profiles` TO `glpi_plugin_fusioninventory_profiles`;
RENAME TABLE `glpi_plugin_fusioninventory_task` TO `glpi_plugin_fusioninventory_tasks`;
RENAME TABLE `glpi_plugin_fusioninventory_unknown_device` TO `glpi_plugin_fusioninventory_unknowndevices`;


## Rename fields

ALTER TABLE `glpi_plugin_fusioninventory_agentinventorystates`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `device_id` `computers_id` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_agentprocesserrors`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `process_number` `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `agent_type` `plugin_fusioninventory_modules_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `device_type` `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `on_device` `items_id` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_agentprocesses`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_agent` `plugin_fusioninventory_agents_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `comments` `comment` text COLLATE utf8_unicode_ci,
   DROP KEY `process_number_2`;

ALTER TABLE `glpi_plugin_fusioninventory_agents`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `device_type` `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `on_device` `items_id` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_configlogfields`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `glpi_plugin_fusioninventory_configmodules`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `glpi_plugin_fusioninventory_configs`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `authsnmp` `storagesnmpauth` VARCHAR( 255 ) COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `glpi_plugin_fusioninventory_configsnmpsecurities`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `secname` `username` VARCHAR( 255 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `auth_protocole` `authentication` VARCHAR( 255 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `priv_protocole` `encryption` VARCHAR( 255 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `deleted` `is_deleted` INT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_constructdevice_miboids`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `mapping_type` `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `mapping_name` `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   CHANGE `mib_oid_id` `plugin_fusioninventory_miboids_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `construct_device_id` `plugin_fusioninventory_constructdevices_id` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_constructdevices`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_glpi_enterprise` `manufacturers_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `snmp_model_id` `plugin_fusioninventory_snmpmodels_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `type` `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL,
   DROP `device`,
   DROP `firmware`;

ALTER TABLE `glpi_plugin_fusioninventory_constructdevicewalks`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `construct_device_id` `plugin_fusioninventory_constructdevices_id` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_ipranges`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_fusioninventory_agents_discover` `plugin_fusioninventory_agents_id_discover` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_fusioninventory_agents_query` `plugin_fusioninventory_agents_id_query` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `ifaddr_start` `ip_start` VARCHAR( 255 ) COLLATE utf8_unicode_ci,
   CHANGE `ifaddr_end` `ip_end` VARCHAR( 255 ) COLLATE utf8_unicode_ci,
   CHANGE `FK_entities` `entities_id` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_lockables`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_entities` `entities_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `recursive` `is_recursive` TINYINT( 1 ) NOT NULL DEFAULT '0',
   CHANGE `fields` `tablefields` TEXT;

ALTER TABLE `glpi_plugin_fusioninventory_locks`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `fields` `tablefields` TEXT;

ALTER TABLE `glpi_plugin_fusioninventory_miblabels`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `comments` `comment` text COLLATE utf8_unicode_ci;

ALTER TABLE `glpi_plugin_fusioninventory_mibobjects`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `comments` `comment` text COLLATE utf8_unicode_ci;

ALTER TABLE `glpi_plugin_fusioninventory_miboids`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `comments` `comment` text COLLATE utf8_unicode_ci;

ALTER TABLE `glpi_plugin_fusioninventory_snmpmodelmibs`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `mapping_name` `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   CHANGE `FK_model_infos` `plugin_fusioninventory_snmpmodels_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_mib_label` `plugin_fusioninventory_miblabels_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_mib_oid` `plugin_fusioninventory_miboids_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_mib_object` `plugin_fusioninventory_mibobjects_id` INT( 11 ) NOT NULL DEFAULT '0'
   DROP `mapping_type`;

ALTER TABLE `glpi_plugin_fusioninventory_snmpmodels`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `device_type` `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `FK_entities` `entities_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `deleted` `is_deleted` INT( 1 ) NOT NULL DEFAULT '0',
   CHANGE `comments` `comment` text COLLATE utf8_unicode_ci;

ALTER TABLE `glpi_plugin_fusioninventory_networkequipmentips`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `ifaddr` `ip` VARCHAR( 255 ) COLLATE utf8_unicode_ci,
   CHANGE `FK_networking` `networkequipments_id` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_networkequipments`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_networking` `networkequipments_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_model_infos` `plugin_fusioninventory_snmpmodels_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_snmp_connection` `plugin_fusioninventory_configsnmpsecurities_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `memory` `memory` INT( 11 ) NOT NULL,
   CHANGE `cpu` `cpu` int(3) NOT NULL DEFAULT '0' COMMENT '%';

ALTER TABLE `glpi_plugin_fusioninventory_networkportconnectionlogs`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_port_source` `plugin_fusioninventory_networkports_id_source` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_port_destination` `plugin_fusioninventory_networkports_id_destination` INT( 11 ) NOT NULL DEFAULT '0';
   CHANGE `process_number` `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `glpi_plugin_fusioninventory_networkportlogs`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_ports` `plugin_fusioninventory_networkports_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `Field` `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   CHANGE `old_value` `value_old` VARCHAR( 255 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `new_value` `value_new` VARCHAR( 255 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `FK_process` `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL DEFAULT '0',
   DROP `old_device_type`,
   DROP `old_device_ID`,
   DROP `new_device_type`,
   DROP `new_device_ID`;

ALTER TABLE `glpi_plugin_fusioninventory_networkports`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_networking_ports` `plugin_fusioninventory_networkports_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `ifmac` `mac` VARCHAR( 255 ) COLLATE utf8_unicode_ci;

ALTER TABLE `glpi_plugin_fusioninventory_printerlogs`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_printers` `printers_id` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_printers`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_printers` `printers_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_model_infos` `plugin_fusioninventory_snmpmodels_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_snmp_connection` `plugin_fusioninventory_configsnmpsecurities_id` INT( 11 ) NOT NULL DEFAULT '0',
   DROP KEY `printers_id`,
   ADD UNIQUE KEY `unicity` (`printers_id`);

ALTER TABLE `glpi_plugin_fusioninventory_printercartridges`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_printers` `printers_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `object-name` `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   CHANGE `FK_cartridges` `cartridges_id` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_fusioninventory_profiles`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `snmp_authentification` `snmp_authentication` CHAR( 1 ) COLLATE utf8_unicode_ci,
   CHANGE `rangeip` `iprange` CHAR( 1 ) COLLATE utf8_unicode_ci,
   CHANGE `agentsprocesses` `agentprocesses` CHAR( 1 ) COLLATE utf8_unicode_ci;

ALTER TABLE `glpi_plugin_fusioninventory_tasks`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `agent_id` `plugin_fusioninventory_agents_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `device_type` `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL,
   CHANGE `on_device` `items_id` INT( 11 ) NOT NULL,
   CHANGE `action` `plugin_fusioninventory_modules_id` INT( 11 ) NOT NULL;

ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
   CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   CHANGE `FK_entities` `entities_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `deleted` `is_deleted` INT( 1 ) NOT NULL DEFAULT '0',
   CHANGE `comments` `comment` text COLLATE utf8_unicode_ci,
   CHANGE `FK_model_infos` `plugin_fusioninventory_snmpmodels_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_snmp_connection` `plugin_fusioninventory_configsnmpsecurities_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `FK_agent` `plugin_fusioninventory_agents_id` INT( 11 ) NOT NULL DEFAULT '0',
   CHANGE `ifaddr` `ip` VARCHAR( 255 ) COLLATE utf8_unicode_ci,
   CHANGE `ifmac` `mac` VARCHAR( 255 ) COLLATE utf8_unicode_ci;



## Others

## glpi_plugin_fusioninventory_mappings
INSERT INTO `glpi_plugin_fusioninventory_mappings`
      (`id`, `itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
   VALUES (NULL,'NetworkEquipment','location','glpi_networkequipments','locations_id',1,NULL),
          (NULL,'NetworkEquipment','firmware','glpi_networkequipments',
             'networkequipmentfirmwares_id',2,NULL),
          (NULL,'NetworkEquipment','firmware1','','',2,NULL),
          (NULL,'NetworkEquipment','firmware2','','',2,NULL),
          (NULL,'NetworkEquipment','contact','glpi_networkequipments','contact',403,NULL),
          (NULL,'NetworkEquipment','comments','glpi_networkequipments','comment',404,NULL),
          (NULL,'NetworkEquipment','uptime','glpi_plugin_fusioninventory_networkequipments',
             'uptime',3,NULL),
          (NULL,'NetworkEquipment','cpu','glpi_plugin_fusioninventory_networkequipments',
             'cpu',12,NULL),
          (NULL,'NetworkEquipment','cpuuser','glpi_plugin_fusioninventory_networkequipments',
             'cpu',401,NULL),
          (NULL,'NetworkEquipment','cpusystem','glpi_plugin_fusioninventory_networkequipments',
             'cpu',402,NULL),
          (NULL,'NetworkEquipment','serial','glpi_networkequipments','serial',13,NULL),
          (NULL,'NetworkEquipment','otherserial','glpi_networkequipments','otherserial',419,NULL),
          (NULL,'NetworkEquipment','name','glpi_networkequipments','name',20,NULL),
          (NULL,'NetworkEquipment','ram','glpi_networkequipments','ram',21,NULL),
          (NULL,'NetworkEquipment','memory','glpi_plugin_fusioninventory_networkequipments',
             'memory',22,NULL),
          (NULL,'NetworkEquipment','vtpVlanName','','',19,NULL),
          (NULL,'NetworkEquipment','vmvlan','','',430,NULL),
          (NULL,'NetworkEquipment','entPhysicalModelName','glpi_networkequipments',
             'networkequipmentmodels_id',17,NULL),
          (NULL,'NetworkEquipment','macaddr','glpi_networkequipments','ip',417,NULL),
## Network CDP (Walk)
          (NULL,'NetworkEquipment','cdpCacheAddress','','',409,NULL),
          (NULL,'NetworkEquipment','cdpCacheDevicePort','','',410,NULL),
          (NULL,'NetworkEquipment','vlanTrunkPortDynamicStatus','','',411,NULL),
          (NULL,'NetworkEquipment','dot1dTpFdbAddress','','',412,NULL),
          (NULL,'NetworkEquipment','ipNetToMediaPhysAddress','','',413,NULL),
          (NULL,'NetworkEquipment','dot1dTpFdbPort','','',414,NULL),
          (NULL,'NetworkEquipment','dot1dBasePortIfIndex','','',415,NULL),
          (NULL,'NetworkEquipment','PortVlanIndex','','',422,NULL),
## NetworkPorts
          (NULL,'NetworkEquipment','ifIndex','','',408,NULL),
          (NULL,'NetworkEquipment','ifmtu','glpi_plugin_fusioninventory_networkports',
             'ifmtu',4,NULL),
          (NULL,'NetworkEquipment','ifspeed','glpi_plugin_fusioninventory_networkports',
             'ifspeed',5,NULL),
          (NULL,'NetworkEquipment','ifinternalstatus','glpi_plugin_fusioninventory_networkports',
             'ifinternalstatus',6,NULL),
          (NULL,'NetworkEquipment','iflastchange','glpi_plugin_fusioninventory_networkports',
             'iflastchange',7,NULL),
          (NULL,'NetworkEquipment','ifinoctets','glpi_plugin_fusioninventory_networkports',
             'ifinoctets',8,NULL),
          (NULL,'NetworkEquipment','ifoutoctets','glpi_plugin_fusioninventory_networkports',
             'ifoutoctets',9,NULL),
          (NULL,'NetworkEquipment','ifinerrors','glpi_plugin_fusioninventory_networkports',
             'ifinerrors',10,NULL),
          (NULL,'NetworkEquipment','ifouterrors','glpi_plugin_fusioninventory_networkports',
             'ifouterrors',11,NULL),
          (NULL,'NetworkEquipment','ifstatus','glpi_plugin_fusioninventory_networkports',
             'ifstatus',14,NULL),
          (NULL,'NetworkEquipment','ifPhysAddress','glpi_networkports','mac',15,NULL),
          (NULL,'NetworkEquipment','ifName','glpi_networkports','name',16,NULL),
          (NULL,'NetworkEquipment','ifName','glpi_networkports','name',16,NULL),
          (NULL,'NetworkEquipment','ifType','','',18,NULL),
          (NULL,'NetworkEquipment','ifdescr','glpi_plugin_fusioninventory_networkports',
             'ifdescr',23,NULL),
          (NULL,'NetworkEquipment','portDuplex','glpi_plugin_fusioninventory_networkports',
             'portduplex',33,NULL),
## Printers
          (NULL,'Printer','model','glpi_printers','printermodels_id',25,NULL),
          (NULL,'Printer','enterprise','glpi_printers','manufacturers_id',420,NULL),
          (NULL,'Printer','contact','glpi_printers','contact',405,NULL),
          (NULL,'Printer','comments','glpi_printers','comment',406,NULL),
          (NULL,'Printer','otherserial','glpi_printers','otherserial',418,NULL),
          (NULL,'Printer','memory','glpi_printers','memory_size',26,NULL),
          (NULL,'Printer','location','glpi_printers','locations_id',56,NULL),
          (NULL,'Printer','informations','','',165,165),
## Cartridges
          (NULL,'Printer','tonerblack','','',157,157),
          (NULL,'Printer','tonerblack2','','',166,166),
          (NULL,'Printer','tonercyan','','',158,158),
          (NULL,'Printer','tonermagenta','','',159,159),
          (NULL,'Printer','toneryellow','','',160,160),
          (NULL,'Printer','wastetoner','','',151,151),
          (NULL,'Printer','cartridgeblack','','',134,134),
          (NULL,'Printer','cartridgeblackphoto','','',135,135),
          (NULL,'Printer','cartridgecyan','','',136,136),
          (NULL,'Printer','cartridgecyanlight','','',139,139),
          (NULL,'Printer','cartridgemagenta','','',138,138),
          (NULL,'Printer','cartridgemagentalight','','',140,140),
          (NULL,'Printer','cartridgeyellow','','',137,137),
          (NULL,'Printer','maintenancekit','','',156,156),
          (NULL,'Printer','drumblack','','',161,161),
          (NULL,'Printer','drumcyan','','',162,162),
          (NULL,'Printer','drummagenta','','',163,163),
          (NULL,'Printer','drumyellow','','',164,164),
## Printers : Counter pages
          (NULL,'Printer','pagecountertotalpages','glpi_plugin_fusioninventory_printerlogs',
             'pages_total',28,128),
          (NULL,'Printer','pagecounterblackpages','glpi_plugin_fusioninventory_printerlogs',
             'pages_n_b',29,129),
          (NULL,'Printer','pagecountercolorpages','glpi_plugin_fusioninventory_printerlogs',
             'pages_color',30,130),
          (NULL,'Printer','pagecounterrectoversopages','glpi_plugin_fusioninventory_printerlogs',
             'pages_recto_verso',54,154),
          (NULL,'Printer','pagecounterscannedpages','glpi_plugin_fusioninventory_printerlogs',
             'scanned',55,155),
          (NULL,'Printer','pagecountertotalpages_print','glpi_plugin_fusioninventory_printerlogs',
             'pages_total_print',423,1423),
          (NULL,'Printer','pagecounterblackpages_print','glpi_plugin_fusioninventory_printerlogs',
             'pages_n_b_print',424,1424),
          (NULL,'Printer','pagecountercolorpages_print','glpi_plugin_fusioninventory_printerlogs',
             'pages_color_print',425,1425),
          (NULL,'Printer','pagecountertotalpages_copy','glpi_plugin_fusioninventory_printerlogs',
             'pages_total_copy',426,1426),
          (NULL,'Printer','pagecounterblackpages_copy','glpi_plugin_fusioninventory_printerlogs',
             'pages_n_b_copy',427,1427),
          (NULL,'Printer','pagecountercolorpages_copy','glpi_plugin_fusioninventory_printerlogs',
             'pages_color_copy',428,1428),
          (NULL,'Printer','pagecountertotalpages_fax','glpi_plugin_fusioninventory_printerlogs',
             'pages_total_fax',429,1429),
## Printers : NetworkPort
          (NULL,'Printer','ifPhysAddress','glpi_networkports','mac',58,NULL),
          (NULL,'Printer','ifName','glpi_networkports','name',57,NULL),
          (NULL,'Printer','ifaddr','glpi_networkports','ip',407,NULL),
          (NULL,'Printer','ifType','','',97,NULL),
          (NULL,'Printer','ifIndex','','',416,NULL),
## Computer
          (NULL,'Computer','serial','','serial',13,NULL),
          (NULL,'Computer','ifPhysAddress','','mac',15,NULL);
