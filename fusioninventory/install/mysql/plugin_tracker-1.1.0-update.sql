ALTER TABLE `glpi_plugin_tracker_config` ADD `logs` INT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_tracker_networking` ADD `last_tracker_update` DATETIME NULL DEFAULT NULL;

ALTER TABLE `glpi_plugin_tracker_printers` ADD `last_tracker_update` DATETIME NULL DEFAULT NULL;

ALTER TABLE `glpi_plugin_tracker_networking_ports` ADD INDEX ( `FK_networking_ports` );

ALTER TABLE `glpi_plugin_tracker_mib_networking` ADD INDEX `FK_model_infos_4` ( `FK_model_infos` , `mapping_name` );

ALTER TABLE `glpi_plugin_tracker_mib_networking` DROP INDEX `FK_model_infos_3` ,
ADD INDEX `FK_model_infos_3` ( `FK_model_infos` , `oid_port_counter` , `mapping_name` ) ;

ALTER TABLE `glpi_plugin_tracker_networking_ifaddr` ADD INDEX ( `ifaddr` ) ;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_agents` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `ifaddr_start` varchar(255) DEFAULT NULL,
  `ifaddr_end` varchar(255) DEFAULT NULL,
  `nb_process_query` int(11) NOT NULL DEFAULT '1',
  `nb_process_discovery` int(11) NOT NULL DEFAULT '1',
  `last_agent_update` datetime DEFAULT NULL,
  `tracker_agent_version` varchar(255) DEFAULT NULL,
  `lock` int(1) NOT NULL DEFAULT '0',
  `logs` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;