DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_label`;

CREATE TABLE `glpi_dropdown_plugin_tracker_mib_label` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_object`;

CREATE TABLE `glpi_dropdown_plugin_tracker_mib_object` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_oid`;

CREATE TABLE `glpi_dropdown_plugin_tracker_mib_oid` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;




DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol`;

CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol`;

CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_auth_sec_level`;

CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_version`;

CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_version` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `glpi_plugin_tracker_connection_history`;

CREATE TABLE `glpi_plugin_tracker_connection_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_users` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_config`;

CREATE TABLE `glpi_plugin_tracker_config` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `computers_history` int(1) NOT NULL DEFAULT '0',
  `update_contact` int(1) NOT NULL DEFAULT '0',
  `update_user` int(1) NOT NULL DEFAULT '0',
  `wire_control` int(1) NOT NULL DEFAULT '0',
  `counters_statement` int(1) NOT NULL DEFAULT '0',
  `statement_default_value` int(1) NOT NULL DEFAULT '0',
  `cleaning` int(1) NOT NULL DEFAULT '0',
  `cleaning_days` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `active_device_state` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `networking_switch_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `authsnmp` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `active_device_state` (`active_device_state`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_errors`;

CREATE TABLE `glpi_plugin_tracker_errors` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_mib_networking`;

CREATE TABLE `glpi_plugin_tracker_mib_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_model_infos` int(8) NOT NULL,
  `FK_mib_label` int(8) NOT NULL,
  `FK_mib_oid` int(8) NOT NULL,
  `FK_mib_object` int(8) NOT NULL,
  `oid_port_counter` int(1) NOT NULL,
  `oid_port_dyn` int(1) NOT NULL,
  `mapping_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mapping_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_model_infos` (`FK_model_infos`),
  KEY `FK_model_infos_2` (`FK_model_infos`,`oid_port_dyn`),
  KEY `FK_model_infos_3` (`FK_model_infos`,`oid_port_counter`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=FIXED;



DROP TABLE IF EXISTS `glpi_plugin_tracker_model_infos`;

CREATE TABLE `glpi_plugin_tracker_model_infos` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `FK_model_networking` int(8) NOT NULL,
  `FK_firmware` int(8) NOT NULL,
  `FK_snmp_version` int(8) NOT NULL,
  `FK_snmp_connection` int(8) NOT NULL,
  `deleted` int(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_model_networking` (`FK_model_networking`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `glpi_plugin_tracker_networking`;

CREATE TABLE `glpi_plugin_tracker_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(8) NOT NULL,
  `FK_model_infos` int(8) NOT NULL,
  `FK_snmp_connection` int(8) NOT NULL,
  `uptime` varchar(255) NOT NULL,
  `cpu` int(3) NOT NULL,
  `memory` int(8) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_networking` (`FK_networking`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=FIXED;



DROP TABLE IF EXISTS `glpi_plugin_tracker_networking_ports`;

CREATE TABLE `glpi_plugin_tracker_networking_ports` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking_ports` int(8) NOT NULL,
  `ifmtu` int(8) NOT NULL,
  `ifspeed` int(12) NOT NULL,
  `ifinternalstatus` varchar(255) NOT NULL,
  `ifconnectionstatus` int(8) NOT NULL,
  `iflastchange` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ifinoctets` int(10) NOT NULL,
  `ifinerrors` int(10) NOT NULL,
  `ifoutoctets` int(10) NOT NULL,
  `ifouterrors` int(10) NOT NULL,
  `ifstatus` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ifmac` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=FIXED;



DROP TABLE IF EXISTS `glpi_plugin_tracker_printers_history`;

CREATE TABLE `glpi_plugin_tracker_printers_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `pages` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_printers_history_config`;

CREATE TABLE `glpi_plugin_tracker_printers_history_config` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL,
  `counter` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `FK_printers` (`FK_printers`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_processes`;

CREATE TABLE `glpi_plugin_tracker_processes` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_processes_values`;

CREATE TABLE `glpi_plugin_tracker_processes_values` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_processes` int(8) NOT NULL,
  `device_ID` int(8) NOT NULL,
  `device_type` int(8) NOT NULL,
  `port` int(8) NOT NULL,
  `unknow_mac` varchar(255) NOT NULL,
  `snmp_errors` varchar(255) NOT NULL,
  `dropdown_add` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `unknow_mac` (`unknow_mac`,`FK_processes`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_profiles`;

CREATE TABLE `glpi_plugin_tracker_profiles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interface` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'tracker',
  `is_default` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  `computers_history` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `printers_history` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `printers_info` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `networking_info` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `errors` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_snmp_connection`;

CREATE TABLE `glpi_plugin_tracker_snmp_connection` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_snmp_version` int(8) NOT NULL,
  `community` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sec_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sec_level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `glpi_plugin_tracker_snmp_history`;

CREATE TABLE `glpi_plugin_tracker_snmp_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_ports` int(11) NOT NULL,
  `Field` varchar(255) NOT NULL,
  `date_mod` datetime DEFAULT NULL,
  `old_value` varchar(255) NOT NULL,
  `old_device_type` int(11) NOT NULL,
  `old_device_ID` int(11) NOT NULL,
  `new_value` varchar(255) NOT NULL,
  `new_device_type` int(11) NOT NULL,
  `new_device_ID` int(11) NOT NULL,
  `FK_process` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_ports` (`FK_ports`,`date_mod`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_unknown_mac`;

CREATE TABLE `glpi_plugin_tracker_unknown_mac` (
	`ID` INT( 100 ) NOT NULL AUTO_INCREMENT,
	`start_FK_processes` INT( 8 ) NOT NULL,
	`end_FK_processes` INT( 8 ) NOT NULL,
	`start_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`end_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`port` INT( 8 ) NOT NULL,
	`unknow_mac` VARCHAR( 255 ) NOT NULL,
	PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 


DROP TABLE IF EXISTS `glpi_plugin_tracker_connection_stats`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_connection_stats` (
  `ID` int(11) NOT NULL auto_increment,
  `device_type` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL,
  `checksum` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` VALUES ('','MD5','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` VALUES ('','SHA','');

INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` VALUES ('','DES','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` VALUES ('','AES128','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` VALUES ('','AES192','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` VALUES ('','AES256','');

INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` VALUES ('','noAuthNoPriv','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` VALUES ('','authNoPriv','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` VALUES ('','authPriv','');

INSERT INTO `glpi_dropdown_plugin_tracker_snmp_version` VALUES (1,'1','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_version` VALUES (2,'2c','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_version` VALUES (3,'3','');

INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','3','2','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','4','3','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','6','4','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','7','5','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','8','6','0');

INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5151','2','1','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5151','3','2','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5151','4','3','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5151','5','4','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5151','6','5','0');