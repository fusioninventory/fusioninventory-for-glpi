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


INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','3','2','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','4','3','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','6','4','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','7','5','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'5150','8','6','0');