DROP TABLE IF EXISTS `glpi_plugin_tracker_agents`;

CREATE TABLE `glpi_plugin_tracker_agents` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `core_discovery` int(11) NOT NULL DEFAULT '1',
  `threads_discovery` int(11) NOT NULL DEFAULT '1',
  `core_query` int(11) NOT NULL DEFAULT '1',
  `threads_query` int(11) NOT NULL DEFAULT '1',
  `last_agent_update` datetime DEFAULT NULL,
  `tracker_agent_version` varchar(255) DEFAULT NULL,
  `lock` int(1) NOT NULL DEFAULT '0',
  `logs` int(1) NOT NULL DEFAULT '0',
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fragment` int(11) NOT NULL DEFAULT '50',
  PRIMARY KEY (`ID`),
  KEY `name` (`name`),
  KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_agents_processes`;

CREATE TABLE `glpi_plugin_tracker_agents_processes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `process_number` varchar(255) DEFAULT NULL,
  `FK_agent` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_discovery` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_discovery` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_query` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_query` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `errors` int(11) NOT NULL DEFAULT '0',
  `error_msg` varchar(255) NOT NULL DEFAULT '0',
  `networking_queries` int(11) NOT NULL DEFAULT '0',
  `printers_queries` int(11) NOT NULL DEFAULT '0',
  `discovery_queries` int(11) NOT NULL DEFAULT '0',
  `discovery_queries_total` int(11) NOT NULL DEFAULT '0',
  `networking_ports_queries` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `process_number` (`process_number`,`FK_agent`),
  KEY `process_number_2` (`process_number`,`FK_agent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



ALTER TABLE `glpi_plugin_tracker_config` DROP `nb_process_query`;
ALTER TABLE `glpi_plugin_tracker_config` DROP `nb_process_discovery`;
ALTER TABLE `glpi_plugin_tracker_config` DROP `logs`;
ALTER TABLE `glpi_plugin_tracker_config` ADD `URL_agent_conf` VARCHAR( 255 ) NULL;
ALTER TABLE `glpi_plugin_tracker_config` ADD `ssl_only` INT( 1 ) NOT NULL DEFAULT '1';


DROP TABLE IF EXISTS `glpi_plugin_tracker_config_discovery`;

CREATE TABLE `glpi_plugin_tracker_config_discovery` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `link_ip` int(1) NOT NULL DEFAULT '0',
  `link_name` int(1) NOT NULL DEFAULT '0',
  `link_serial` int(1) NOT NULL DEFAULT '0',
  `link2_ip` int(1) NOT NULL DEFAULT '0',
  `link2_name` int(1) NOT NULL DEFAULT '0',
  `link2_serial` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_config_snmp_script`;

CREATE TABLE `glpi_plugin_tracker_config_snmp_script` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nb_process` int(11) NOT NULL DEFAULT '1',
  `logs` int(1) NOT NULL DEFAULT '0',
  `lock` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_discover`;

CREATE TABLE `glpi_plugin_tracker_discovery` (
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



DROP TABLE IF EXISTS `glpi_plugin_tracker_discover_conf`;



ALTER TABLE `glpi_plugin_tracker_mib_networking` ADD `activation` int(1) NOT NULL DEFAULT '1';
ALTER TABLE `glpi_plugin_tracker_mib_networking` ADD `vlan` int(1) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_plugin_tracker_mib_networking` ADD INDEX ( `oid_port_dyn` ) ;
ALTER TABLE `glpi_plugin_tracker_mib_networking` ADD INDEX ( `activation` ) ;

ALTER TABLE `glpi_plugin_tracker_model_infos` ADD `FK_entities` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_plugin_tracker_model_infos` ADD `activation` int(1) NOT NULL DEFAULT '1';
ALTER TABLE `glpi_plugin_tracker_model_infos` ADD `discovery_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL;


ALTER TABLE `glpi_plugin_tracker_networking` ADD `last_PID_update` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_plugin_tracker_networking` ADD INDEX `FK_model_infos` ( `FK_model_infos` , `FK_snmp_connection` );


ALTER TABLE `glpi_plugin_tracker_networking_ports` ADD `lastup` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';

ALTER TABLE `glpi_plugin_tracker_profiles` DROP `snmp_peripherals` ;

ALTER TABLE `glpi_plugin_tracker_profiles` ADD `snmp_iprange` char(1) COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE `glpi_plugin_tracker_profiles` ADD `snmp_agent` char(1) COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE `glpi_plugin_tracker_profiles` ADD `snmp_agent_infos` char(1) COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE `glpi_plugin_tracker_profiles` ADD `snmp_report` char(1) COLLATE utf8_unicode_ci DEFAULT NULL;


DROP TABLE IF EXISTS `glpi_plugin_tracker_rangeip`;

CREATE TABLE `glpi_plugin_tracker_rangeip` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `FK_tracker_agents` int(11) NOT NULL DEFAULT '0',
  `ifaddr_start` varchar(255) DEFAULT NULL,
  `ifaddr_end` varchar(255) DEFAULT NULL,
  `discover` int(1) NOT NULL DEFAULT '0',
  `query` int(1) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_tracker_agents` (`FK_tracker_agents`,`discover`),
  KEY `FK_tracker_agents_2` (`FK_tracker_agents`,`query`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



ALTER TABLE `glpi_plugin_tracker_unknown_mac` ADD `unknown_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL;



DROP TABLE IF EXISTS `glpi_plugin_tracker_connection_stats`;

CREATE TABLE `glpi_plugin_tracker_connection_stats` (
  `ID` int(11) NOT NULL auto_increment,
  `device_type` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL,
  `checksum` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_walks`;

CREATE TABLE `glpi_plugin_tracker_walks` (
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


INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '8', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '9', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '10', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '11', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '12', '5', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '3', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '5', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '6', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '7', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '8', '6', '0');

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
