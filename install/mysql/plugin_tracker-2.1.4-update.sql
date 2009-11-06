ALTER TABLE `glpi_plugin_tracker_unknown_device` ADD `serial` VARCHAR( 255 ) NULL DEFAULT NULL ,
ADD `otherserial` VARCHAR( 255 ) NULL DEFAULT NULL ,
ADD `comments` TEXT NULL DEFAULT NULL ,
ADD `accepted` INT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_plugin_tracker_unknown_device` ADD `dnsname` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `name` ;
ALTER TABLE `glpi_plugin_tracker_unknown_device` ADD `type` INT( 11 ) NOT NULL DEFAULT '0' AFTER `comments` ,
ADD `snmp` INT( 1 ) NOT NULL DEFAULT '0' AFTER `type` ,
ADD `FK_model_infos` INT( 11 ) NOT NULL DEFAULT '0' AFTER `snmp` ,
ADD `FK_snmp_connection` INT( 11 ) NOT NULL DEFAULT '0' AFTER `FK_model_infos`;
ALTER TABLE `glpi_plugin_tracker_unknown_device` ADD `contact` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `otherserial` ,
ADD `domain` INT( 11 ) NOT NULL DEFAULT '0' AFTER `contact`;


ALTER TABLE `glpi_plugin_tracker_rangeip` CHANGE `FK_tracker_agents` `FK_tracker_agents_discover` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `glpi_plugin_tracker_rangeip` ADD `FK_tracker_agents_query` INT( 11 ) NOT NULL DEFAULT '0' AFTER `FK_tracker_agents_discover` ;



ALTER TABLE `glpi_plugin_tracker_config_discovery` ADD `link_macaddr` INT( 1 ) NOT NULL DEFAULT '0' AFTER `link_serial`;
ALTER TABLE `glpi_plugin_tracker_config_discovery` ADD `link2_macaddr` INT( 1 ) NOT NULL DEFAULT '0';


