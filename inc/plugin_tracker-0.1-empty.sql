DROP TABLE IF EXISTS `glpi_plugin_tracker_computers_history`;

CREATE TABLE `glpi_plugin_tracker_computers_history` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` INT(11) NOT NULL DEFAULT '0',
  `date` DATETIME DEFAULT NULL,
  `state` INT(1) NOT NULL DEFAULT '0',
  `username` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_users` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY(`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_errors`;

CREATE TABLE `glpi_plugin_tracker_errors` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `ifaddr` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_type` SMALLINT(6) NOT NULL,
  `device_id` INT(11) NOT NULL DEFAULT '0',
  `FK_entities` INT(11) NOT NULL DEFAULT '0',
  `first_pb_date` DATETIME DEFAULT NULL,
  `last_pb_date` DATETIME DEFAULT NULL,
  `description` TEXT collate utf8_unicode_ci,
  PRIMARY KEY(`ID`),
  UNIQUE KEY `ifaddr` (`ifaddr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_printers_history`;

CREATE TABLE `glpi_plugin_tracker_printers_history` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` INT(11) NOT NULL DEFAULT '0',
  `date` DATETIME DEFAULT NULL,
  `pages` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY(`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_config`;

CREATE TABLE `glpi_plugin_tracker_config` (
  `ID` INT(1) NOT NULL AUTO_INCREMENT,
  `computers_history` INT(1) NOT NULL DEFAULT '0',
  `update_contact` INT(1) NOT NULL DEFAULT '0',
  `update_user` INT(1) NOT NULL DEFAULT '0',
  `wire_control` INT(1) NOT NULL DEFAULT '0',
  `counters_statement` INT(1) NOT NULL DEFAULT '0',
  `statement_default_value` INT(1) NOT NULL DEFAULT '0',
  `cleaning` INT(1) NOT NULL DEFAULT '0',
  `cleaning_days` VARCHAR(50) NOT NULL DEFAULT '0',
  `active_device_state` VARCHAR(50) NOT NULL DEFAULT '0',
  `networking_switch_type` VARCHAR(50) NOT NULL DEFAULT '0',
  PRIMARY KEY(`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_printers_history_config`;

CREATE TABLE `glpi_plugin_tracker_printers_history_config` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` INT(11) NOT NULL,
  `counter` INT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY(`ID`),
  UNIQUE KEY `FK_printers` (`FK_printers`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_profiles`;

CREATE TABLE `glpi_plugin_tracker_profiles` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) DEFAULT NULL,
  `interface` VARCHAR(50) NOT NULL DEFAULT 'tracker',
  `is_default` enum('0','1') DEFAULT NULL,
  `computers_history` CHAR(1) DEFAULT NULL,
  `printers_history` CHAR(1) DEFAULT NULL,
  `printers_info` CHAR(1) DEFAULT NULL,
  `networking_info` CHAR(1) DEFAULT NULL,
  `errors` CHAR(1) DEFAULT NULL,
  PRIMARY KEY(`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_label`;

CREATE TABLE `glpi_dropdown_plugin_tracker_mib_label` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_object`;

CREATE TABLE `glpi_dropdown_plugin_tracker_mib_object` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_oid`;

CREATE TABLE `glpi_dropdown_plugin_tracker_mib_oid` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_version`;

CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_version` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_links_oid_fields`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_links_oid_fields` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `table` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dropdown` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_mib_networking`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_mib_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_model_infos` int(8) NOT NULL,
  `FK_mib_label` int(8) NOT NULL,
  `FK_mib_oid` int(8) NOT NULL,
  `FK_mib_object` int(8) NOT NULL,
  `oid_port_counter` int(1) NOT NULL,
  `oid_port_dyn` int(1) NOT NULL,
  `FK_links_oid_fields` int(8) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_model_infos` (`FK_model_infos`),
  KEY `FK_model_infos_2` (`FK_model_infos`,`oid_port_dyn`),
  KEY `FK_model_infos_3` (`FK_model_infos`,`oid_port_counter`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_model_infos`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_model_infos` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `FK_model_networking` int(8) NOT NULL,
  `FK_firmware` int(8) NOT NULL,
  `FK_snmp_version` int(8) NOT NULL,
  `FK_snmp_connection` int(8) NOT NULL,
  `deleted` int(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_model_networking` (`FK_model_networking`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_networking`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(8) NOT NULL,
  `FK_model_infos` int(8) NOT NULL,
  `uptime` int(8) NOT NULL,
  `cpu` int(3) NOT NULL,
  `memory` int(8) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_networking` (`FK_networking`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_networking_ports`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_networking_ports` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking_ports` int(8) NOT NULL,
  `ifmtu` int(8) NOT NULL,
  `ifspeed` int(8) NOT NULL,
  `ifinternalstatus` int(8) NOT NULL,
  `ifconnectionstatus` int(8) NOT NULL,
  `iflastchange` int(8) NOT NULL,
  `ifinoctets` int(10) NOT NULL,
  `ifinerrors` int(10) NOT NULL,
  `ifoutoctets` int(10) NOT NULL,
  `ifouterrors` int(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_snmp_connection`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_snmp_connection` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  `login` varchar(64) CHARACTER SET latin1 NOT NULL,
  `password` varchar(64) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_processes`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_processes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(4) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(4) NOT NULL DEFAULT '0',
  `error_msg` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `process_id` int(11) NOT NULL DEFAULT '0',
  `network_queries` int(8) NOT NULL,
  `printer_queries` int(8) NOT NULL,
  `ports_queries` int(8) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `end_time` (`end_time`),
  KEY `process_id` (`process_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','3','2','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','4','3','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','6','4','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','7','5','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','8','6','0');

INSERT INTO `glpi_plugin_tracker_links_oid_fields` (`ID`, `name`, `table`, `field`, `dropdown`) VALUES
('', 'reseaux/lieu', 'glpi_networking', 'location', 'glpi_dropdown_locations'),
('', 'réseaux/firmware', 'glpi_networking', 'firmware', 'glpi_dropdown_firmware'),
('', 'réseaux/uptime', 'glpi_plugin_tracker_networking', 'uptime', ''),
('', 'réseaux/port/mtu', 'glpi_plugin_tracker_networking_ports', 'ifmtu', ''),
('', 'réseaux/port/vitesse', 'glpi_plugin_tracker_networking_ports', 'ifspeed', ''),
('', 'réseaux/port/statut interne', 'glpi_plugin_tracker_networking_ports', 'ifinternalstatus', ''),
('', 'réseaux/port/statut connexion', 'glpi_plugin_tracker_networking_ports', 'ifconnectionstatus', ''),
('', 'réseaux/port/nombre d''octets entrés', 'glpi_plugin_tracker_networking_ports', 'ifinoctets', ''),
('', 'réseaux/port/nombre d''octets sortis', 'glpi_plugin_tracker_networking_ports', 'ifoutoctets', ''),
('', 'réseaux/port/nombre d''erreurs entrées', 'glpi_plugin_tracker_networking_ports', 'ifinerrors', ''),
('', 'réseaux/port/nombre d''erreurs sorties', 'glpi_plugin_tracker_networking_ports', 'ifouterrors', ''),
('', 'réseaux/utilisation du CPU', 'glpi_plugin_tracker_networking', 'cpu', '');


INSERT INTO `glpi_plugin_tracker_model_infos` (`ID`, `name`, `FK_model_networking`, `FK_firmware`, `FK_snmp_version`, `FK_snmp_connection`, `deleted`) VALUES
('', 'Cisco 2950 ancien', 1, 1, 1, 2, 0);

