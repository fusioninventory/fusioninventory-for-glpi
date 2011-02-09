DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_label`;

CREATE TABLE `glpi_dropdown_plugin_fusioninventory_mib_label` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_object`;

CREATE TABLE `glpi_dropdown_plugin_fusioninventory_mib_object` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_oid`;

CREATE TABLE `glpi_dropdown_plugin_fusioninventory_mib_oid` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol`;

CREATE TABLE `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol`;

CREATE TABLE `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_version`;

CREATE TABLE `glpi_dropdown_plugin_fusioninventory_snmp_version` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents`;

CREATE TABLE `glpi_plugin_fusioninventory_agents` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `core_discovery` int(11) NOT NULL DEFAULT '1',
  `threads_discovery` int(11) NOT NULL DEFAULT '1',
  `core_query` int(11) NOT NULL DEFAULT '1',
  `threads_query` int(11) NOT NULL DEFAULT '1',
  `last_agent_update` datetime DEFAULT NULL,
  `fusioninventory_agent_version` varchar(255) DEFAULT NULL,
  `lock` int(1) NOT NULL DEFAULT '0',
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `on_device` int(11) NOT NULL DEFAULT '0',
  `device_type` smallint(6) NOT NULL DEFAULT '0',
  `token` varchar(255) COLLATE utf8_unicode_ci NULL,
  `module_inventory` INT( 1 ) NOT NULL DEFAULT '0',
  `module_netdiscovery` INT( 1 ) NOT NULL DEFAULT '0',
  `module_snmpquery` INT( 1 ) NOT NULL DEFAULT '0',
  `module_wakeonlan` INT( 1 ) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `name` (`name`),
  KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents_errors`;

CREATE TABLE `glpi_plugin_fusioninventory_agents_errors` (
`ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`process_number` VARCHAR( 255 )  COLLATE utf8_unicode_ci DEFAULT NULL,
`on_device` INT( 11 ) NOT NULL DEFAULT '0',
`device_type` INT( 11 ) NOT NULL DEFAULT '0',
`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
`agent_type` VARCHAR( 255 ) COLLATE utf8_unicode_ci DEFAULT NULL ,
`error_message` text collate utf8_unicode_ci,
PRIMARY KEY ( `ID` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents_inventory_state`;

CREATE TABLE `glpi_plugin_fusioninventory_agents_inventory_state` (
`ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`device_id` INT( 11 ) NOT NULL DEFAULT '0',
`state` INT( 1 ) NOT NULL DEFAULT '0',
`date_mod` DATETIME NULL ,
PRIMARY KEY ( `ID` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents_processes`;

CREATE TABLE `glpi_plugin_fusioninventory_agents_processes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `process_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_agent` int(11) NOT NULL DEFAULT '0',
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
  `comments` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY (`ID`),
  KEY `process_number` (`process_number`,`FK_agent`),
  KEY `process_number_2` (`process_number`,`FK_agent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_connection_history`;

CREATE TABLE `glpi_plugin_fusioninventory_connection_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_users` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_computers`;

CREATE TABLE `glpi_plugin_fusioninventory_computers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` int(11) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config`;

CREATE TABLE `glpi_plugin_fusioninventory_config` (
   `ID` int(1) NOT NULL AUTO_INCREMENT,
   `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
   `URL_agent_conf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `ssl_only` int(1) NOT NULL DEFAULT '0',
   `authsnmp` varchar(255) NOT NULL,
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
   PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_modules`;

CREATE TABLE `glpi_plugin_fusioninventory_config_modules` (
   `ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `snmp` INT( 1 ) NOT NULL DEFAULT '0',
   `inventoryocs` INT( 1 ) NOT NULL DEFAULT '0',
   `netdiscovery` INT( 1 ) NOT NULL DEFAULT '0',
   `remotehttpagent` INT( 1 ) NOT NULL DEFAULT '0',
   `wol` INT( 1 ) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_snmp_history`;

CREATE TABLE `glpi_plugin_fusioninventory_config_snmp_history` (
   `ID` INT( 8 ) NOT NULL AUTO_INCREMENT ,
   `field` VARCHAR( 255 ) NOT NULL ,
   `days` int(255) NOT NULL DEFAULT '-1',
   PRIMARY KEY ( `id` ) ,
   INDEX ( `field` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_device`;

CREATE TABLE `glpi_plugin_fusioninventory_construct_device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_glpi_enterprise` int(11) NOT NULL DEFAULT '0',
  `device` varchar(255) DEFAULT NULL,
  `firmware` varchar(255) DEFAULT NULL,
  `sysdescr` text,
  `type` varchar(255) DEFAULT NULL,
  `snmpmodel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_walks`;

CREATE TABLE `glpi_plugin_fusioninventory_construct_walks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `construct_device_id` int(11) NOT NULL DEFAULT '0',
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_mibs`;

CREATE TABLE `glpi_plugin_fusioninventory_construct_mibs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `mib_oid_id` int(11) NOT NULL DEFAULT '0',
  `construct_device_id` int(11) NOT NULL DEFAULT '0',
  `mapping_name` varchar(255) DEFAULT NULL,
  `oid_port_counter` int(1) NOT NULL DEFAULT '0',
  `oid_port_dyn` int(1) NOT NULL DEFAULT '0',
  `mapping_type` varchar(255) DEFAULT NULL,
  `vlan` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_history_connections`;

CREATE TABLE `glpi_plugin_fusioninventory_snmp_history_connections` (
   `ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `creation` INT( 1 ) NOT NULL DEFAULT '0',
   `FK_port_source` INT( 11 ) NOT NULL DEFAULT '0',
   `FK_port_destination` INT( 11 ) NOT NULL DEFAULT '0',
   `process_number` VARCHAR( 255 ) NULL ,
   PRIMARY KEY ( `ID` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_snmp_networking`;

CREATE TABLE `glpi_plugin_fusioninventory_config_snmp_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `active_device_state` int(11) NOT NULL DEFAULT '0',
  `history_wire` int(11) NOT NULL DEFAULT '0',
  `history_ports_state` int(11) NOT NULL DEFAULT '0',
  `history_unknown_mac` int(11) NOT NULL DEFAULT '0',
  `history_snmp_errors` int(11) NOT NULL DEFAULT '0',
  `history_process` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_discovery`;

CREATE TABLE `glpi_plugin_fusioninventory_discovery` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_processes` int(11) NOT NULL DEFAULT '0',
  `FK_agents` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ifaddr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descr` text COLLATE utf8_unicode_ci,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `FK_model_infos` int(11) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(11) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_errors`;

CREATE TABLE `glpi_plugin_fusioninventory_errors` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ifaddr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_type` smallint(6) NOT NULL,
  `device_id` int(11) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `first_pb_date` datetime DEFAULT NULL,
  `last_pb_date` datetime DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ifaddr` (`ifaddr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lock`;

CREATE TABLE `glpi_plugin_fusioninventory_lock` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `itemtype` INT( 11 ) NOT NULL ,
   `items_id` INT( 11 ) NOT NULL ,
   `fields` LONGTEXT ,
   PRIMARY KEY ( `ID` ) ,
   KEY `itemtype` ( `itemtype` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lockable`;

CREATE TABLE `glpi_plugin_fusioninventory_lockable` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `itemtype` INT( 11 ) NOT NULL ,
   `fields` LONGTEXT ,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `recursive` TINYINT( 1 ) NOT NULL DEFAULT '0',
   PRIMARY KEY ( `ID` ) ,
   KEY `itemtype` ( `itemtype` ),
   KEY `entities_id` ( `entities_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_mib_networking`;

CREATE TABLE `glpi_plugin_fusioninventory_mib_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_model_infos` int(8) DEFAULT NULL,
  `FK_mib_label` int(8) DEFAULT NULL,
  `FK_mib_oid` int(8) DEFAULT NULL,
  `FK_mib_object` int(8) DEFAULT NULL,
  `oid_port_counter` int(1) DEFAULT NULL,
  `oid_port_dyn` int(1) DEFAULT NULL,
  `mapping_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mapping_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activation` int(1) NOT NULL DEFAULT '1',
  `vlan` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_model_infos` (`FK_model_infos`),
  KEY `FK_model_infos_2` (`FK_model_infos`,`oid_port_dyn`),
  KEY `FK_model_infos_3` (`FK_model_infos`,`oid_port_counter`,`mapping_name`),
  KEY `FK_model_infos_4` (`FK_model_infos`,`mapping_name`),
  KEY `oid_port_dyn` (`oid_port_dyn`),
  KEY `activation` (`activation`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_model_infos`;

CREATE TABLE `glpi_plugin_fusioninventory_model_infos` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `device_type` int(8) NOT NULL DEFAULT '0',
  `deleted` int(1) DEFAULT NULL,
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `activation` int(1) NOT NULL DEFAULT '1',
  `discovery_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`),
  KEY `name` (`name`),
  KEY `device_type` (`device_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking`;

CREATE TABLE `glpi_plugin_fusioninventory_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(8) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  `uptime` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `cpu` int(3) NOT NULL DEFAULT '0',
  `memory` int(8) NOT NULL DEFAULT '0',
  `last_fusioninventory_update` datetime DEFAULT NULL,
  `last_PID_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_networking` (`FK_networking`),
  KEY `FK_model_infos` (`FK_model_infos`,`FK_snmp_connection`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking_ifaddr`;

CREATE TABLE `glpi_plugin_fusioninventory_networking_ifaddr` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(11) NOT NULL,
  `ifaddr` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ifaddr` (`ifaddr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking_ports`;

CREATE TABLE `glpi_plugin_fusioninventory_networking_ports` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking_ports` int(8) NOT NULL,
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
  `ifmac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifdescr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `portduplex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trunk` int(1) NOT NULL DEFAULT '0',
  `lastup` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `FK_networking_ports` (`FK_networking_ports`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printers_history`;

CREATE TABLE `glpi_plugin_fusioninventory_printers_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL DEFAULT '0',
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
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printers`;

CREATE TABLE `glpi_plugin_fusioninventory_printers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  `frequence_days` int(5) NOT NULL DEFAULT '1',
  `last_fusioninventory_update` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `FK_printers` (`FK_printers`),
  KEY `FK_snmp_connection` (`FK_snmp_connection`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printers_cartridges`;

CREATE TABLE `glpi_plugin_fusioninventory_printers_cartridges` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL,
  `object_name` varchar(255) NOT NULL,
  `FK_cartridges` int(11) NOT NULL DEFAULT '0',
  `state` int(3) NOT NULL DEFAULT '100',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_task`;

CREATE TABLE `glpi_plugin_fusioninventory_task` (
   `ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `date` DATETIME NOT NULL ,
   `agent_id` INT( 11 ) NOT NULL ,
   `action` VARCHAR( 255 ) NOT NULL ,
   `param` varchar(255) NOT NULL,
   `on_device` INT( 11 ) NOT NULL ,
   `device_type` SMALLINT( 6 ) NOT NULL ,
   `single` int(1) NOT NULL,
   PRIMARY KEY ( `id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_profiles`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_profiles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interface` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'fusioninventory',
  `is_default` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_networking` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_printers` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_models` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_authentification` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rangeip` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agents` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remotecontrol` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agentsprocesses` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unknowndevices` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reports` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deviceinventory` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `netdiscovery` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_query` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wol` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `configuration` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_rangeip`;

CREATE TABLE `glpi_plugin_fusioninventory_rangeip` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `FK_fusioninventory_agents_discover` int(11) NOT NULL DEFAULT '0',
  `FK_fusioninventory_agents_query` INT( 11 ) NOT NULL DEFAULT '0',
  `ifaddr_start` varchar(255) DEFAULT NULL,
  `ifaddr_end` varchar(255) DEFAULT NULL,
  `discover` int(1) NOT NULL DEFAULT '0',
  `query` int(1) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_connection`;

CREATE TABLE `glpi_plugin_fusioninventory_snmp_connection` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_snmp_version` int(8) NOT NULL,
  `community` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sec_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_snmp_version` (`FK_snmp_version`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_history`;

CREATE TABLE `glpi_plugin_fusioninventory_snmp_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_ports` int(11) NOT NULL,
  `Field` varchar(255) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  `old_value` varchar(255) DEFAULT NULL,
  `old_device_type` int(11) NOT NULL DEFAULT '0',
  `old_device_ID` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) DEFAULT NULL,
  `new_device_type` int(11) NOT NULL DEFAULT '0',
  `new_device_ID` int(11) NOT NULL DEFAULT '0',
  `FK_process` VARCHAR( 255 ) NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_ports` (`FK_ports`,`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknown_device`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_unknown_device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dnsname` VARCHAR( 255 ) NULL DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `location` int(11) NOT NULL DEFAULT '0',
  `deleted` smallint(6) NOT NULL DEFAULT '0',
  `serial` VARCHAR( 255 ) NULL DEFAULT NULL,
  `otherserial` VARCHAR( 255 ) NULL DEFAULT NULL,
  `contact` VARCHAR( 255 ) NULL DEFAULT NULL,
  `domain` INT( 11 ) NOT NULL DEFAULT '0',
  `comments` TEXT NULL DEFAULT NULL,
  `type` INT( 11 ) NOT NULL DEFAULT '0',
  `snmp` INT( 1 ) NOT NULL DEFAULT '0',
  `FK_model_infos` INT( 11 ) NOT NULL DEFAULT '0',
  `FK_snmp_connection` INT( 11 ) NOT NULL DEFAULT '0',
  `accepted` INT( 1 ) NOT NULL DEFAULT '0',
  `FK_agent` int(11) NOT NULL DEFAULT '0',
  `ifaddr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifmac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hub` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_connection_stats`;

CREATE TABLE `glpi_plugin_fusioninventory_connection_stats` (
  `ID` int(11) NOT NULL auto_increment,
  `device_type` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL,
  `checksum` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_walks`;

CREATE TABLE `glpi_plugin_fusioninventory_walks` (
  `ID` int(30) NOT NULL AUTO_INCREMENT,
  `on_device` int(11) NOT NULL DEFAULT '0',
  `device_type` int(11) NOT NULL DEFAULT '0',
  `FK_agents_processes` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vlan` varchar(255) DEFAULT NULL,
  `oid` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



INSERT INTO `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol` VALUES (1,'MD5','');
INSERT INTO `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol` VALUES (2,'SHA','');
INSERT INTO `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol` VALUES (3,'DES','');
INSERT INTO `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol` VALUES (4,'AES128','');
INSERT INTO `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol` VALUES (5,'AES192','');
INSERT INTO `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol` VALUES (6,'AES256','');

INSERT INTO `glpi_dropdown_plugin_fusioninventory_snmp_version` VALUES (1,'1','');
INSERT INTO `glpi_dropdown_plugin_fusioninventory_snmp_version` VALUES (2,'2c','');
INSERT INTO `glpi_dropdown_plugin_fusioninventory_snmp_version` VALUES (3,'3','');

INSERT INTO `glpi_plugin_fusioninventory_snmp_connection` VALUES (1, 'Communauté Public v1', '1', 'public', '', '0', '', '0', '', '0');
INSERT INTO `glpi_plugin_fusioninventory_snmp_connection` VALUES (2, 'Communauté Public v2c', '2', 'public', '', '0', '', '0', '', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','3','1','0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','4','2','0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','6','3','0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','7','4','0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','8','5','0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5151', '3', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5151', '5', '2', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '3', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '4', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '5', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '7', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '8', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '9', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '10', '7', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '4', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '3', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '5', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '7', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '10', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '11', '7', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '18', '8', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '14', '9', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '15', '10', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '9', '11', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '3', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '4', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '5', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '6', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '7', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '8', '7', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '9', '8', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '10', '9', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '11', '10', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '14', '11', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '12', '12', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '13', '13', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '8', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '9', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '10', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '11', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '12', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '13', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '14', '7', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '3', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '5', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '6', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '9', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '7', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '8', '7', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '3', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '4', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '5', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '6', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '7', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '8', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '9', '7', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '10', '8', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '3', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '4', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '5', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '6', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '7', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '8', '7', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '9', '8', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '10', '9', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '11', '10', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '12', '11', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '3', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '4', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '5', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '6', '5', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5163', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5163', '3', '2', '0');
