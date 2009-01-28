ALTER TABLE `glpi_plugin_tracker_config` ADD `logs2` INT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `glpi_plugin_tracker_networking` ADD `last_tracker_update` DATETIME NULL DEFAULT NULL;

ALTER TABLE `glpi_plugin_tracker_printers` ADD `last_tracker_update` DATETIME NULL DEFAULT NULL;
