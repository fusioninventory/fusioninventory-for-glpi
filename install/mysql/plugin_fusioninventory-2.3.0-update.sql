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
   `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `tablefield` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `locale` INT( 4 ) NOT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`name`, `type`)
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

