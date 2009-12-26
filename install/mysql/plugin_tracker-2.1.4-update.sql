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

# Configurations tables

ALTER TABLE `glpi_plugin_tracker_config_discovery` ADD `link_macaddr` INT( 1 ) NOT NULL DEFAULT '0' AFTER `link_serial`;
ALTER TABLE `glpi_plugin_tracker_config_discovery` ADD `link2_macaddr` INT( 1 ) NOT NULL DEFAULT '0';



ALTER TABLE `glpi_plugin_tracker_config`
   DROP `activation_history`,
   DROP `activation_connection`,
   DROP `activation_snmp_computer`,
   DROP `activation_snmp_networking`,
   DROP `activation_snmp_peripheral`,
   DROP `activation_snmp_phone`,
   DROP `activation_snmp_printer`,
   DROP `authsnmp`,
   DROP `URL_agent_conf`;

ALTER TABLE `glpi_plugin_tracker_config` 
   ADD `authsnmp` varchar(255) NOT NULL,
   ADD `inventory_frequence` INT( 11 ) NULL DEFAULT '24' ,
   ADD `criteria1_ip` INT( 1 ) NOT NULL DEFAULT '0',
   ADD `criteria1_name` INT( 1 ) NOT NULL DEFAULT '0',
   ADD `criteria1_serial` INT( 1 ) NOT NULL DEFAULT '0',
   ADD `criteria1_macaddr` INT( 1 ) NOT NULL DEFAULT '0',
   ADD `criteria2_ip` INT( 1 ) NOT NULL DEFAULT '0',
   ADD `criteria2_name` INT( 1 ) NOT NULL DEFAULT '0',
   ADD `criteria2_serial` INT( 1 ) NOT NULL DEFAULT '0',
   ADD `criteria2_macaddr` INT( 1 ) NOT NULL DEFAULT '0';

DROP TABLE `glpi_plugin_tracker_config_discovery` ;

DROP TABLE `glpi_plugin_tracker_config_snmp_printer`

CREATE TABLE `glpi_plugin_tracker_config_modules` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `snmp` INT( 1 ) NOT NULL DEFAULT '0',
   `inventoryocs` INT( 1 ) NOT NULL DEFAULT '0',
   `netdiscovery` INT( 1 ) NOT NULL DEFAULT '0',
   `remotehttpagent` INT( 1 ) NOT NULL DEFAULT '0'
   ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_tracker_lock`;

CREATE TABLE `glpi_plugin_tracker_lock` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `itemtype` INT( 11 ) NOT NULL ,
   `items_id` INT( 11 ) NOT NULL ,
   `fields` LONGTEXT ,
   PRIMARY KEY ( `ID` ) ,
   KEY `itemtype` ( `itemtype` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_tracker_lockable`;

CREATE TABLE `glpi_plugin_tracker_lockable` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `itemtype` INT( 11 ) NOT NULL ,
   `fields` LONGTEXT ,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `recursive` TINYINT( 1 ) NOT NULL DEFAULT '0',
   PRIMARY KEY ( `ID` ) ,
   KEY `itemtype` ( `itemtype` ),
   KEY `entities_id` ( `entities_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci;


DROP TABLE `glpi_plugin_tracker_config_snmp_script`;


CREATE TABLE `glpi072`.`glpi_plugin_tracker_task` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `date` DATETIME NOT NULL ,
   `agent_id` INT( 11 ) NOT NULL ,
   `action` VARCHAR( 255 ) NOT NULL ,
   `param` varchar(255) NOT NULL,
   `on_device` INT( 11 ) NOT NULL ,
   `device_type` SMALLINT( 6 ) NOT NULL ,
   `single` int(1) NOT NULL,
   PRIMARY KEY ( `id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci;


ALTER TABLE `glpi_plugin_tracker_profiles` CHANGE `snmp_scripts_infos` `tracker_task` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL
