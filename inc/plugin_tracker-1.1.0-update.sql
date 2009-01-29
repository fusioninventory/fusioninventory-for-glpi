ALTER TABLE `glpi_plugin_tracker_config` ADD `logs2` INT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_tracker_networking` ADD `last_tracker_update` DATETIME NULL DEFAULT NULL;

ALTER TABLE `glpi_plugin_tracker_printers` ADD `last_tracker_update` DATETIME NULL DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_agents` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `ifaddr_start` varchar(255) DEFAULT NULL,
  `ifaddr_end` varchar(255) DEFAULT NULL,
  `last_agent_update` datetime DEFAULT NULL,
  `tracker_agent_version` varchar(255) DEFAULT NULL,
  `lock` int(1) NOT NULL DEFAULT '0',
  `logs` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;