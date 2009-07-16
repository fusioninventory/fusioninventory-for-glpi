DROP TABLE IF EXISTS `glpi_plugin_tracker_unknown_device`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_unknown_device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `location` int(11) NOT NULL DEFAULT '0',
  `deleted` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `glpi_plugin_tracker_config` ADD `version` VARCHAR( 255 ) NOT NULL DEFAULT '0' AFTER `ID` ;

UPDATE `glpi0715`.`glpi_plugin_tracker_config` SET `version` = '2.0.2' WHERE `glpi_plugin_tracker_config`.`ID` =1 LIMIT 1 ;

ALTER TABLE `glpi_plugin_tracker_printers_history` ADD `pages_total_print` INT( 11 ) NOT NULL DEFAULT '0',
ADD `pages_n_b_print` INT( 11 ) NOT NULL DEFAULT '0',
ADD `pages_color_print` INT( 11 ) NOT NULL DEFAULT '0',
ADD `pages_total_copy` INT( 11 ) NOT NULL DEFAULT '0',
ADD `pages_n_b_copy` INT( 11 ) NOT NULL DEFAULT '0',
ADD `pages_color_copy` INT( 11 ) NOT NULL DEFAULT '0',
ADD `pages_total_fax` INT( 11 ) NOT NULL DEFAULT '0';
