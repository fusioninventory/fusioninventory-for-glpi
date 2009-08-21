ALTER TABLE `glpi_plugin_tracker_tmp_netports` ADD INDEX (`cdp`);

ALTER TABLE `glpi_plugin_tracker_tmp_netports` ADD INDEX `FK_networking` ( `FK_networking` , `FK_networking_port` );

ALTER TABLE `glpi_plugin_tracker_model_infos` ADD INDEX (`device_type`);

ALTER TABLE `glpi_plugin_tracker_snmp_connection` ADD INDEX (`FK_snmp_version`);

ALTER TABLE `glpi_plugin_tracker_printers` ADD INDEX (`FK_snmp_connection`);

ALTER TABLE `glpi_plugin_tracker_tmp_connections` ADD INDEX (`macaddress`);


