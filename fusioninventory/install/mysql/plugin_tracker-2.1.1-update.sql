ALTER TABLE `glpi_plugin_tracker_tmp_netports` ADD INDEX (`cdp`);

ALTER TABLE `glpi_plugin_tracker_tmp_netports` ADD INDEX `FK_networking` ( `FK_networking` , `FK_networking_port` );

ALTER TABLE `glpi_plugin_tracker_model_infos` ADD INDEX (`device_type`);

ALTER TABLE `glpi_plugin_tracker_snmp_connection` ADD INDEX (`FK_snmp_version`);

ALTER TABLE `glpi_plugin_tracker_printers` ADD INDEX (`FK_snmp_connection`);

ALTER TABLE `glpi_plugin_tracker_tmp_connections` ADD INDEX (`macaddress`);


DROP TABLE IF EXISTS `glpi_plugin_tracker_config_snmp_history`;

CREATE TABLE `glpi_plugin_tracker_config_snmp_history` (
   `id` INT( 8 ) NOT NULL AUTO_INCREMENT ,
   `field` VARCHAR( 255 ) NOT NULL ,
   PRIMARY KEY ( `id` ) ,
   INDEX ( `field` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

