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

DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_label`;  -- renamed table
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_object`;  -- renamed table
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_oid`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents_inventory_state`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_modules`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_snmp_history`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_device`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_walks`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_mibs`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lock`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lockable`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_mib_networking`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_model_infos`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking_ifaddr`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking_ports`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printers_history`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_rangeip`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_history_connections`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_connection`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_history`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_task`;  -- renamed table
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknown_device`;  -- renamed table



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_miblabels`;

CREATE TABLE `glpi_plugin_fusioninventory_miblabels` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_mibobjects`;

CREATE TABLE `glpi_plugin_fusioninventory_mibobjects` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_miboids`;

CREATE TABLE `glpi_plugin_fusioninventory_miboids` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents`;

CREATE TABLE `glpi_plugin_fusioninventory_agents` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `core_discovery` int(11) NOT NULL DEFAULT '1',
   `threads_discovery` int(11) NOT NULL DEFAULT '1',
   `core_query` int(11) NOT NULL DEFAULT '1',
   `threads_query` int(11) NOT NULL DEFAULT '1',
   `last_agent_update` datetime DEFAULT NULL,
   `fusioninventory_agent_version` varchar(255) DEFAULT NULL,
   `lock` int(1) NOT NULL DEFAULT '0',
   `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `items_id` int(11) NOT NULL DEFAULT '0',
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `token` varchar(255) COLLATE utf8_unicode_ci NULL,
   `module_inventory` INT( 1 ) NOT NULL DEFAULT '0',
   `module_netdiscovery` INT( 1 ) NOT NULL DEFAULT '0',
   `module_snmpquery` INT( 1 ) NOT NULL DEFAULT '0',
   `module_wakeonlan` INT( 1 ) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents_errors`;

CREATE TABLE `glpi_plugin_fusioninventory_agentprocesserrors` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `plugin_fusioninventory_agentprocesses_id` VARCHAR( 255 )  COLLATE utf8_unicode_ci DEFAULT NULL,
   `items_id` INT( 11 ) NOT NULL DEFAULT '0',
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
   `plugin_fusioninventory_modules_id` INT( 11 ) NOT NULL DEFAULT '0',
   `error_message` text collate utf8_unicode_ci,
   PRIMARY KEY ( `id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agentinventorystates`;

CREATE TABLE `glpi_plugin_fusioninventory_agentinventorystates` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `computers_id` INT( 11 ) NOT NULL DEFAULT '0',
   `state` INT( 1 ) NOT NULL DEFAULT '0',
   `date_mod` DATETIME NULL ,
   PRIMARY KEY ( `id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agentprocesses`;

CREATE TABLE `glpi_plugin_fusioninventory_agentprocesses` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `process_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
   `status` int(1) NOT NULL DEFAULT '0',
   `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `start_time_discovery` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `end_time_discovery` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `discovery_core` int(11) NOT NULL DEFAULT '0',
   `discovery_threads` int(11) NOT NULL DEFAULT '0',
   `discovery_nb_ip` INT( 11 ) NOT NULL DEFAULT '0',
   `discovery_nb_found` int(11) NOT NULL DEFAULT '0',
   `discovery_nb_error` INT( 11 ) NOT NULL DEFAULT '0',
   `discovery_nb_exists` int(11) NOT NULL DEFAULT '0',
   `discovery_nb_import` int(11) NOT NULL DEFAULT '0',
   `start_time_query` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `end_time_query` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `query_core` int(11) NOT NULL DEFAULT '0',
   `query_threads` int(11) NOT NULL DEFAULT '0',
   `query_nb_query` int(11) NOT NULL DEFAULT '0',
   `query_nb_error` int(11) NOT NULL DEFAULT '0',
   `query_nb_connections_created` int(11) NOT NULL DEFAULT '0',
   `query_nb_connections_deleted` int(11) NOT NULL DEFAULT '0',
   `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
   PRIMARY KEY (`id`),
   KEY `process_number` (`process_number`,`FK_agent`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_configs`;

CREATE TABLE `glpi_plugin_fusioninventory_configs` (
   `id` int(1) NOT NULL AUTO_INCREMENT,
   `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
   `URL_agent_conf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `ssl_only` int(1) NOT NULL DEFAULT '0',
   `storagesnmpauth` varchar(255) NOT NULL,
   `inventory_frequence` INT( 11 ) NULL DEFAULT '24',
   `criteria1_ip` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria1_name` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria1_serial` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria1_macaddr` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria2_ip` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria2_name` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria2_serial` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria2_macaddr` INT( 1 ) NOT NULL DEFAULT '0',
   `delete_agent_process` INT( 11 ) NOT NULL DEFAULT '24',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_configmodules`;

CREATE TABLE `glpi_plugin_fusioninventory_configmodules` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `snmp` INT( 1 ) NOT NULL DEFAULT '0',
   `inventoryocs` INT( 1 ) NOT NULL DEFAULT '0',
   `netdiscovery` INT( 1 ) NOT NULL DEFAULT '0',
   `remotehttpagent` INT( 1 ) NOT NULL DEFAULT '0',
   `wol` INT( 1 ) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_configlogfields`;

CREATE TABLE `glpi_plugin_fusioninventory_configlogfields` (
   `id` INT( 8 ) NOT NULL AUTO_INCREMENT ,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `days` int(255) NOT NULL DEFAULT '-1',
   PRIMARY KEY ( `id` ) ,
   INDEX ( `plugin_fusioninventory_mappings_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_constructdevices`;

CREATE TABLE `glpi_plugin_fusioninventory_constructdevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `manufacturers_id` int(11) NOT NULL DEFAULT '0',
   `sysdescr` text,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `snmpmodels_id` int(11) DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_constructdevicewalks`;

CREATE TABLE `glpi_plugin_fusioninventory_constructdevicewalks` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_constructdevices_id` int(11) NOT NULL DEFAULT '0',
   `log` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_constructdevices_miboids`;

CREATE TABLE `glpi_plugin_fusioninventory_constructdevices_miboids` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_miboids_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_constructdevices_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `oid_port_counter` int(1) NOT NULL DEFAULT '0',
   `oid_port_dyn` int(1) NOT NULL DEFAULT '0',
   `mapping_type` varchar(255) DEFAULT NULL,
   `vlan` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkportconnectionlogs`;

CREATE TABLE `glpi_plugin_fusioninventory_networkportconnectionlogs` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `creation` INT( 1 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_networkports_id_source` INT( 11 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_networkports_id_destination` INT( 11 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL DEFAULT '0',
   PRIMARY KEY ( `id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_locks`;

CREATE TABLE `glpi_plugin_fusioninventory_locks` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
   `items_id` INT( 11 ) NOT NULL ,
   `tablefields` TEXT ,
   PRIMARY KEY ( `id` ) ,
   KEY `itemtype` ( `itemtype` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lockables`;

CREATE TABLE `glpi_plugin_fusioninventory_lockables` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL;
   `tablefields` TEXT ,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` TINYINT( 1 ) NOT NULL DEFAULT '0',
   PRIMARY KEY ( `id` ) ,
   KEY `itemtype` ( `itemtype` ),
   KEY `entities_id` ( `entities_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmpmodelmibs`;

CREATE TABLE `glpi_plugin_fusioninventory_snmpmodelmibs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_snmpmodels_id` int(11) DEFAULT NULL,
   `plugin_fusioninventory_miblabels_id` int(11) DEFAULT NULL,
   `plugin_fusioninventory_miboids_id` int(11) DEFAULT NULL,
   `plugin_fusioninventory_mibobjects_id` int(11) DEFAULT NULL,
   `oid_port_counter` int(1) DEFAULT NULL,
   `oid_port_dyn` int(1) DEFAULT NULL,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `activation` int(1) NOT NULL DEFAULT '1',
   `vlan` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusioninventory_snmpmodels_id` (`plugin_fusioninventory_snmpmodels_id`),
   KEY `plugin_fusioninventory_snmpmodels_id_2` (`plugin_fusioninventory_snmpmodels_id`,`oid_port_dyn`),
   KEY `plugin_fusioninventory_snmpmodels_id_3` (`plugin_fusioninventory_snmpmodels_id`,`oid_port_counter`,`plugin_fusioninventory_mappings_id`),
   KEY `plugin_fusioninventory_snmpmodels_id_4` (`plugin_fusioninventory_snmpmodels_id`,`plugin_fusioninventory_mappings_id`),
   KEY `oid_port_dyn` (`oid_port_dyn`),
   KEY `activation` (`activation`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmpmodels`;

CREATE TABLE `glpi_plugin_fusioninventory_snmpmodels` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
   `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL,
   `is_deleted` int(1) DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `activation` int(1) NOT NULL DEFAULT '1',
   `discovery_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `itemtype` (`itemtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkequipments`;

CREATE TABLE `glpi_plugin_fusioninventory_networkequipments` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkequipments_id` int(11) NOT NULL,
   `plugin_fusioninventory_snmpmodels_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_configsnmpsecurities_id` int(11) NOT NULL DEFAULT '0',
   `uptime` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
   `cpu` int(3) NOT NULL DEFAULT '0' COMMENT '%',
   `memory` int(11) NOT NULL DEFAULT '0',
   `last_fusioninventory_update` datetime DEFAULT NULL,
   `last_PID_update` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `networkequipments_id` (`networkequipments_id`),
   KEY `plugin_fusioninventory_snmpmodels_id` (`plugin_fusioninventory_snmpmodels_id`,`plugin_fusioninventory_configsnmpsecurities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkequipmentips`;

CREATE TABLE `glpi_plugin_fusioninventory_networkequipmentips` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkequipments_id` int(11) NOT NULL,
   `ip` varchar(255) NOT NULL,
   PRIMARY KEY (`id`),
   KEY `ip` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkports`;

CREATE TABLE `glpi_plugin_fusioninventory_networkports` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkports_id` int(11) NOT NULL,
   `ifmtu` int(8) NOT NULL DEFAULT '0',
   `ifspeed` int(12) NOT NULL DEFAULT '0',
   `ifinternalstatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `ifconnectionstatus` int(8) NOT NULL DEFAULT '0',
   `iflastchange` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `ifinoctets` bigint(50) NOT NULL DEFAULT '0',
   `ifinerrors` bigint(50) NOT NULL DEFAULT '0',
   `ifoutoctets` bigint(50) NOT NULL DEFAULT '0',
   `ifouterrors` bigint(50) NOT NULL DEFAULT '0',
   `ifstatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `ifdescr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `portduplex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `trunk` int(1) NOT NULL DEFAULT '0',
   `lastup` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   PRIMARY KEY (`id`),
   KEY `networkports_id` (`networkports_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printerlogs`;

CREATE TABLE `glpi_plugin_fusioninventory_printerlogs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `printers_id` int(11) NOT NULL DEFAULT '0',
   `date` datetime DEFAULT '0000-00-00 00:00:00',
   `pages_total` int(11) NOT NULL DEFAULT '0',
   `pages_n_b` int(11) NOT NULL DEFAULT '0',
   `pages_color` int(11) NOT NULL DEFAULT '0',
   `pages_recto_verso` int(11) NOT NULL DEFAULT '0',
   `scanned` int(11) NOT NULL DEFAULT '0',
   `pages_total_print` int(11) NOT NULL DEFAULT '0',
   `pages_n_b_print` int(11) NOT NULL DEFAULT '0',
   `pages_color_print` int(11) NOT NULL DEFAULT '0',
   `pages_total_copy` int(11) NOT NULL DEFAULT '0',
   `pages_n_b_copy` int(11) NOT NULL DEFAULT '0',
   `pages_color_copy` int(11) NOT NULL DEFAULT '0',
   `pages_total_fax` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printers`;

CREATE TABLE `glpi_plugin_fusioninventory_printers` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `printers_id` int(11) NOT NULL,
   `plugin_fusioninventory_snmpmodels_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_configsnmpsecurities_id` int(11) NOT NULL DEFAULT '0',
   `frequence_days` int(5) NOT NULL DEFAULT '1',
   `last_fusioninventory_update` datetime DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`printers_id`),
   KEY `plugin_fusioninventory_configsnmpsecurities_id` (`plugin_fusioninventory_configsnmpsecurities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printercartridges`;

CREATE TABLE `glpi_plugin_fusioninventory_printercartridges` (
   `id` int(100) NOT NULL AUTO_INCREMENT,
   `printers_id` int(11) NOT NULL,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `cartridges_id` int(11) NOT NULL DEFAULT '0',
   `state` int(3) NOT NULL DEFAULT '100',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_tasks`;

CREATE TABLE `glpi_plugin_fusioninventory_tasks` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `date` DATETIME NOT NULL ,
   `plugin_fusioninventory_agents_id` INT( 11 ) NOT NULL,
   `plugin_fusioninventory_modules_id` INT( 11 ) NOT NULL,
   `param` varchar(255) NOT NULL,
   `items_id` INT( 11 ) NOT NULL ,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `single` int(1) NOT NULL,
   PRIMARY KEY ( `id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_profiles`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_profiles` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `interface` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'fusioninventory',
   `is_default` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
   `snmp_networking` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `snmp_printers` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `snmp_models` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `snmp_authentication` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `iprange` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `agents` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `remotecontrol` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `agentprocesses` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `unknowndevices` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `reports` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `deviceinventory` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `netdiscovery` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `snmp_query` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `wol` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `configuration` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_ipranges`;

CREATE TABLE `glpi_plugin_fusioninventory_ipranges` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `plugin_fusioninventory_agents_id_discover` int(11) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agents_id_query` INT( 11 ) NOT NULL DEFAULT '0',
   `ip_start` varchar(255) DEFAULT NULL,
   `ip_end` varchar(255) DEFAULT NULL,
   `discover` int(1) NOT NULL DEFAULT '0',
   `query` int(1) NOT NULL DEFAULT '0',
   `entities_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_configsnmpsecurities`;

CREATE TABLE `glpi_plugin_fusioninventory_configsnmpsecurities` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
   `snmpversion` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
   `community` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `authentication` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `auth_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `encryption` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `priv_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `is_deleted` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `snmpversion` (`snmpversion`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkportlogs`;

CREATE TABLE `glpi_plugin_fusioninventory_networkportlogs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_networkports_id` int(11) NOT NULL,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `date_mod` datetime DEFAULT NULL,
   `value_old` varchar(255) DEFAULT NULL,
   `value_new` varchar(255) DEFAULT NULL,
   `plugin_fusioninventory_agentprocesses_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusioninventory_networkports_id` (`plugin_fusioninventory_networkports_id`,`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknowndevices`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_unknowndevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `dnsname` VARCHAR( 255 ) NULL DEFAULT NULL,
   `date_mod` datetime DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `location` int(11) NOT NULL DEFAULT '0',
   `is_deleted` smallint(6) NOT NULL DEFAULT '0',
   `serial` VARCHAR( 255 ) NULL DEFAULT NULL,
   `otherserial` VARCHAR( 255 ) NULL DEFAULT NULL,
   `contact` VARCHAR( 255 ) NULL DEFAULT NULL,
   `domain` INT( 11 ) NOT NULL DEFAULT '0',
   `comment` TEXT NULL DEFAULT NULL,
   `type` INT( 11 ) NOT NULL DEFAULT '0',
   `snmp` INT( 1 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_snmpmodels_id` INT( 11 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_configsnmpsecurities_id` INT( 11 ) NOT NULL DEFAULT '0',
   `accepted` INT( 1 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
   `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `hub` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_modules`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_modules` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `locale` INT( 4 ) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_mappings`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_mappings` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `tablefield` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `locale` INT( 4 ) NOT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`name`, `type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



INSERT INTO `glpi_plugin_fusioninventory_configsnmpsecurities` (`id`, `name`, `snmpversion`, `community`, `username`, `authentication`, `auth_passphrase`, `encryption`, `priv_passphrase`, `is_deleted`) VALUES (1, 'Communauté Public v1', '1', 'public', '', '0', '', '0', '', '0');
INSERT INTO `glpi_plugin_fusioninventory_configsnmpsecurities` (`id`, `name`, `snmpversion`, `community`, `username`, `authentication`, `auth_passphrase`, `encryption`, `priv_passphrase`, `is_deleted`) VALUES (2, 'Communauté Public v2c', '2', 'public', '', '0', '', '0', '', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventorySnmpModel', '3', '1', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventorySnmpModel', '5', '2', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryConfigSnmpSecurity', '3', '1', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryConfigSnmpSecurity', '4', '2', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryConfigSnmpSecurity', '5', '3', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryConfigSnmpSecurity', '7', '4', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryConfigSnmpSecurity', '8', '5', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryConfigSnmpSecurity', '9', '6', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryConfigSnmpSecurity', '10', '7', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '2', '1', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '4', '2', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '3', '3', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '5', '4', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '7', '5', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '10', '6', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '11', '7', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '18', '8', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '14', '9', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '15', '10', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL, 'PluginFusioninventoryUnknownDevice', '9', '11', '0');

--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '2', '1', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '3', '2', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '4', '3', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '5', '4', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '6', '5', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '7', '6', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '8', '7', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '9', '8', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '10', '9', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '11', '10', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '14', '11', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '12', '12', '0');
--INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '13', '13', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgent', '8', '1', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgent', '9', '2', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgent', '10', '3', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgent', '11', '4', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgent', '12', '5', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgent', '13', '6', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgent', '14', '7', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryIpRange', '2', '1', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryIpRange', '3', '2', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryIpRange', '5', '3', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryIpRange', '6', '4', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryIpRange', '9', '5', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryIpRange', '7', '6', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryIpRange', '8', '7', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '2', '1', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '3', '2', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '4', '3', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '5', '4', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '6', '5', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '7', '6', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '8', '7', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '9', '8', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '10', '9', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '11', '10', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryAgentProcess', '12', '11', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPortLog', '2', '1', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPortLog', '3', '2', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPortLog', '4', '3', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPortLog', '5', '4', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPortLog', '6', '5', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '2', '1', '0');
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) VALUES (NULL,'PluginFusioninventoryNetworkPort', '3', '2', '0');
