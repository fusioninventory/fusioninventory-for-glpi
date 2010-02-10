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
   DROP `authsnmp;

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
   ADD `criteria2_macaddr` INT( 1 ) NOT NULL DEFAULT '0',
   ADD `delete_agent_process` INT( 11 ) NOT NULL DEFAULT '24';

DROP TABLE `glpi_plugin_tracker_config_discovery` ;

DROP TABLE `glpi_plugin_tracker_config_snmp_printer` ;

CREATE TABLE `glpi_plugin_tracker_config_modules` (
   `ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `snmp` INT( 1 ) NOT NULL DEFAULT '0',
   `inventoryocs` INT( 1 ) NOT NULL DEFAULT '0',
   `netdiscovery` INT( 1 ) NOT NULL DEFAULT '0',
   `remotehttpagent` INT( 1 ) NOT NULL DEFAULT '0',
   `wol` INT( 1 ) NOT NULL DEFAULT '0'
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


CREATE TABLE `glpi_plugin_tracker_task` (
   `ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
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


ALTER TABLE `glpi_plugin_tracker_profiles` CHANGE `snmp_scripts_infos` `tracker_task` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;


ALTER TABLE `glpi_plugin_tracker_snmp_connection` DROP `sec_level`;

DROP TABLE `glpi_dropdown_plugin_tracker_snmp_auth_sec_level`;

ALTER TABLE `glpi_plugin_tracker_agents_processes`
  DROP `errors`,
  DROP `error_msg`,
  DROP `networking_queries`,
  DROP `printers_queries`,
  DROP `discovery_queries`,
  DROP `discovery_queries_total`,
  DROP `networking_ports_queries`;

ALTER TABLE `glpi_plugin_tracker_agents_processes`
   ADD `discovery_core` INT( 11 ) NOT NULL DEFAULT '0' AFTER `end_time_discovery` ,
   ADD `discovery_threads` INT( 11 ) NOT NULL DEFAULT '0' AFTER `discovery_core` ,
   ADD `discovery_nb_ip` INT( 11 ) NOT NULL DEFAULT '0' AFTER `discovery_threads`,
   ADD `discovery_nb_found` INT( 11 ) NOT NULL DEFAULT '0' AFTER `discovery_nb_ip` ,
   ADD `discovery_nb_exists` INT( 11 ) NOT NULL DEFAULT '0' AFTER `discovery_nb_found` ,
   ADD `discovery_nb_error` INT( 11 ) NOT NULL DEFAULT '0' AFTER `discovery_nb_exists` ,
   ADD `discovery_nb_import` INT( 11 ) NOT NULL DEFAULT '0' AFTER `discovery_nb_error` ,
   ADD `comments` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
   ADD `query_core` INT( 11 ) NOT NULL DEFAULT '0' ,
   ADD `query_threads` INT( 11 ) NOT NULL DEFAULT '0' ,
   ADD `query_nb_query` INT( 11 ) NOT NULL DEFAULT '0' ,
   ADD `query_nb_error` INT( 11 ) NOT NULL DEFAULT '0' ,
   ADD `query_nb_connections_created` INT( 11 ) NOT NULL DEFAULT '0' ,
   ADD `query_nb_connections_deleted` INT( 11 ) NOT NULL DEFAULT '0';

CREATE TABLE `glpi_plugin_tracker_agents_errors` (
   `ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `process_number` VARCHAR( 255 )  COLLATE utf8_unicode_ci DEFAULT NULL,
   `on_device` INT( 11 ) NOT NULL DEFAULT '0',
   `device_type` INT( 11 ) NOT NULL DEFAULT '0',
   `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
   `agent_type` VARCHAR( 255 ) COLLATE utf8_unicode_ci DEFAULT NULL ,
   `error_message` VARCHAR( 255 )  COLLATE utf8_unicode_ci DEFAULT NULL ,
   PRIMARY KEY ( `ID` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `glpi_plugin_tracker_snmp_history` CHANGE `FK_process` `FK_process` VARCHAR( 255 ) NULL;

DROP TABLE `glpi_plugin_tracker_tmp_connections` ;

DROP TABLE `glpi_plugin_tracker_tmp_netports` ;

CREATE TABLE `glpi_plugin_tracker_snmp_history_connections` (
   `ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `creation` INT( 1 ) NOT NULL DEFAULT '0',
   `FK_port_source` INT( 11 ) NOT NULL DEFAULT '0',
   `FK_port_destination` INT( 11 ) NOT NULL DEFAULT '0',
   `process_number` VARCHAR( 255 ) NULL ,
   PRIMARY KEY ( `ID` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE `glpi_plugin_tracker_processes` ;

DROP TABLE `glpi_plugin_tracker_processes_values` ;

CREATE TABLE `glpi_plugin_tracker_construct_device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_glpi_enterprise` int(11) NOT NULL DEFAULT '0',
  `device` varchar(255) DEFAULT NULL,
  `firmware` varchar(255) DEFAULT NULL,
  `sysdescr` text,
  `type` varchar(255) DEFAULT NULL,
  `snmpmodel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `glpi_plugin_tracker_construct_walks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `construct_device_id` int(11) NOT NULL DEFAULT '0',
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `glpi_plugin_tracker_construct_mibs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `mib_oid_id` int(11) NOT NULL DEFAULT '0',
  `construct_device_id` int(11) NOT NULL DEFAULT '0',
  `mapping_name` varchar(255) DEFAULT NULL,
  `oid_port_counter` int(1) NOT NULL DEFAULT '0',
  `oid_port_dyn` int(1) NOT NULL DEFAULT '0',
  `mapping_type` varchar(255) DEFAULT NULL,
  `vlan` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `glpi_plugin_tracker_agents` ADD `on_device` INT( 11 ) NOT NULL DEFAULT '0',
   ADD `device_type` SMALLINT( 6 ) NOT NULL DEFAULT '0',
   ADD `token` VARCHAR( 255 ) NOT NULL

CREATE TABLE `glpi_plugin_tracker_agents_inventory_state` (
`ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`device_id` INT( 11 ) NOT NULL DEFAULT '0',
`state` INT( 1 ) NOT NULL DEFAULT '0',
`date_mod` DATETIME NULL ,
PRIMARY KEY ( `ID` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
