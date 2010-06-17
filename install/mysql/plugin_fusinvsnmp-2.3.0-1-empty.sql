## renamed tables
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_label`;
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_object`;
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_oid`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_snmp_history`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_device`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_walks`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_mibs`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_mib_networking`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_model_infos`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking_ifaddr`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking_ports`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printers_history`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_rangeip`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_history_connections`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_connection`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_history`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknown_device`;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_miblabels`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_miblabels`;

CREATE TABLE `glpi_plugin_fusinvsnmp_miblabels` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_mibobjects`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_mibobjects`;

CREATE TABLE `glpi_plugin_fusinvsnmp_mibobjects` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_miboids`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_miboids`;

CREATE TABLE `glpi_plugin_fusinvsnmp_miboids` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_configlogfields`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_configlogfields`;

CREATE TABLE `glpi_plugin_fusinvsnmp_configlogfields` (
   `id` INT( 8 ) NOT NULL AUTO_INCREMENT ,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `days` int(255) NOT NULL DEFAULT '-1',
   PRIMARY KEY ( `id` ) ,
   INDEX ( `plugin_fusioninventory_mappings_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_constructdevices`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_constructdevices`;

CREATE TABLE `glpi_plugin_fusinvsnmp_constructdevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `manufacturers_id` int(11) NOT NULL DEFAULT '0',
   `sysdescr` text,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugin_fusinvsnmp_models_id` int(11) DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_constructdevicewalks`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_constructdevicewalks`;

CREATE TABLE `glpi_plugin_fusinvsnmp_constructdevicewalks` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusinvsnmp_constructdevices_id` int(11) NOT NULL DEFAULT '0',
   `log` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_constructdevice_miboids`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_constructdevice_miboids`;

CREATE TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusinvsnmp_miboids_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_constructdevices_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `oid_port_counter` int(1) NOT NULL DEFAULT '0',
   `oid_port_dyn` int(1) NOT NULL DEFAULT '0',
   `itemtype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
   `vlan` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkportconnectionlogs`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_networkportconnectionlogs`;

CREATE TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `creation` INT( 1 ) NOT NULL DEFAULT '0',
   `networkports_id_source` INT( 11 ) NOT NULL DEFAULT '0',
   `networkports_id_destination` INT( 11 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL DEFAULT '0',
   PRIMARY KEY ( `id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmpmodelmibs`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_modelmibs`;

CREATE TABLE `glpi_plugin_fusinvsnmp_modelmibs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusinvsnmp_models_id` int(11) DEFAULT NULL,
   `plugin_fusinvsnmp_miblabels_id` int(11) DEFAULT NULL,
   `plugin_fusinvsnmp_miboids_id` int(11) DEFAULT NULL,
   `plugin_fusinvsnmp_mibobjects_id` int(11) DEFAULT NULL,
   `oid_port_counter` int(1) DEFAULT NULL,
   `oid_port_dyn` int(1) DEFAULT NULL,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `activation` int(1) NOT NULL DEFAULT '1',
   `vlan` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`),
   KEY `plugin_fusinvsnmp_models_id_2` (`plugin_fusinvsnmp_models_id`,`oid_port_dyn`),
   KEY `plugin_fusinvsnmp_models_id_3` (`plugin_fusinvsnmp_models_id`,`oid_port_counter`,`plugin_fusioninventory_mappings_id`),
   KEY `plugin_fusinvsnmp_odels_id_4` (`plugin_fusinvsnmp_models_id`,`plugin_fusioninventory_mappings_id`),
   KEY `oid_port_dyn` (`oid_port_dyn`),
   KEY `activation` (`activation`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmpmodels`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_models`;

CREATE TABLE `glpi_plugin_fusinvsnmp_models` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
   `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL,
   `is_deleted` int(1) NOT NULL DEFAULT '0',
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `activation` int(1) NOT NULL DEFAULT '1',
   `discovery_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `itemtype` (`itemtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkequipments`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_networkequipments`;

CREATE TABLE `glpi_plugin_fusinvsnmp_networkequipments` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkequipments_id` int(11) NOT NULL,
   `plugin_fusinvsnmp_models_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_configsecurities_id` int(11) NOT NULL DEFAULT '0',
   `uptime` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
   `cpu` int(3) NOT NULL DEFAULT '0' COMMENT '%',
   `memory` int(11) NOT NULL DEFAULT '0',
   `last_fusinvsnmp_update` datetime DEFAULT NULL,
   `last_PID_update` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `networkequipments_id` (`networkequipments_id`),
   KEY `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`,`plugin_fusinvsnmp_configsecurities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkequipmentips`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_networkequipmentips`;

CREATE TABLE `glpi_plugin_fusinvsnmp_networkequipmentips` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkequipments_id` int(11) NOT NULL,
   `ip` varchar(255) NOT NULL,
   PRIMARY KEY (`id`),
   KEY `ip` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkports`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_networkports`;

CREATE TABLE `glpi_plugin_fusinvsnmp_networkports` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkports_id` int(11) NOT NULL,
   `ifmtu` int(8) NOT NULL DEFAULT '0',
   `ifspeed` int(12) NOT NULL DEFAULT '0',
   `ifinternalstatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `ifconnectionstatus` int(8) NOT NULL DEFAULT '0',
   `iflastchange` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `ifinoctets` bigint(50) NOT NULL DEFAULT '0',
   `ifinerrors` bigint(50) NOT NULL DEFAULT '0',
   `ifoutoctets` bigint(50) NOT NULL DEFAULT '0',
   `ifouterrors` bigint(50) NOT NULL DEFAULT '0',
   `ifstatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `ifdescr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `portduplex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `trunk` int(1) NOT NULL DEFAULT '0',
   `lastup` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   PRIMARY KEY (`id`),
   KEY `networkports_id` (`networkports_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printerlogs`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_printerlogs`;

CREATE TABLE `glpi_plugin_fusinvsnmp_printerlogs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `printers_id` int(11) NOT NULL DEFAULT '0',
   `date` datetime DEFAULT '0000-00-00 00:00:00',
   `pages_total` int(11) NOT NULL DEFAULT '0',
   `pages_n_b` int(11) NOT NULL DEFAULT '0',
   `pages_color` int(11) NOT NULL DEFAULT '0',
   `pages_recto_verso` int(11) NOT NULL DEFAULT '0',
   `scanned` int(11) NOT NULL DEFAULT '0',
   `pages_total_print` int(11) NOT NULL DEFAULT '0',
   `pages_n_b_print` int(11) NOT NULL DEFAULT '0',
   `pages_color_print` int(11) NOT NULL DEFAULT '0',
   `pages_total_copy` int(11) NOT NULL DEFAULT '0',
   `pages_n_b_copy` int(11) NOT NULL DEFAULT '0',
   `pages_color_copy` int(11) NOT NULL DEFAULT '0',
   `pages_total_fax` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printers`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_printers`;

CREATE TABLE `glpi_plugin_fusinvsnmp_printers` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `printers_id` int(11) NOT NULL,
   `plugin_fusinvsnmp_models_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_configsecurities_id` int(11) NOT NULL DEFAULT '0',
   `frequence_days` int(5) NOT NULL DEFAULT '1',
   `last_fusinvsnmp_update` datetime DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`printers_id`),
   KEY `plugin_fusinvsnmp_configsecurities_id` (`plugin_fusinvsnmp_configsecurities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_printercartridges`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_printercartridges`;

CREATE TABLE `glpi_plugin_fusinvsnmp_printercartridges` (
   `id` int(100) NOT NULL AUTO_INCREMENT,
   `printers_id` int(11) NOT NULL,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `cartridges_id` int(11) NOT NULL DEFAULT '0',
   `state` int(3) NOT NULL DEFAULT '100',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_ipranges`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_ipranges`;

CREATE TABLE `glpi_plugin_fusinvsnmp_ipranges` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `plugin_fusioninventory_agents_id_discover` int(11) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agents_id_query` INT( 11 ) NOT NULL DEFAULT '0',
   `ip_start` varchar(255) DEFAULT NULL,
   `ip_end` varchar(255) DEFAULT NULL,
   `discover` int(1) NOT NULL DEFAULT '0',
   `query` int(1) NOT NULL DEFAULT '0',
   `entities_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_configsnmpsecurities`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_configsecurities`;

CREATE TABLE `glpi_plugin_fusinvsnmp_configsecurities` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
   `snmpversion` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
   `community` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `authentication` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `auth_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `encryption` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `priv_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `is_deleted` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `snmpversion` (`snmpversion`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networkportlogs`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_networkportlogs`;

CREATE TABLE `glpi_plugin_fusinvsnmp_networkportlogs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkports_id` int(11) NOT NULL,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `date_mod` datetime DEFAULT NULL,
   `value_old` varchar(255) DEFAULT NULL,
   `value_new` varchar(255) DEFAULT NULL,
   `plugin_fusioninventory_agentprocesses_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `networkports_id` (`networkports_id`,`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknowndevices`;
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_unknowndevices`;

CREATE TABLE `glpi_plugin_fusinvsnmp_unknowndevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `dnsname` VARCHAR( 255 ) NULL DEFAULT NULL,
   `date_mod` datetime DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `location` int(11) NOT NULL DEFAULT '0',
   `is_deleted` smallint(6) NOT NULL DEFAULT '0',
   `serial` VARCHAR( 255 ) NULL DEFAULT NULL,
   `otherserial` VARCHAR( 255 ) NULL DEFAULT NULL,
   `contact` VARCHAR( 255 ) NULL DEFAULT NULL,
   `domain` INT( 11 ) NOT NULL DEFAULT '0',
   `comment` TEXT NULL DEFAULT NULL,
   `type` INT( 11 ) NOT NULL DEFAULT '0',
   `snmp` INT( 1 ) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_models_id` INT( 11 ) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_configsecurities_id` INT( 11 ) NOT NULL DEFAULT '0',
   `accepted` INT( 1 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
   `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `hub` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




## INSERT
## glpi_plugin_fusinvsnmp_configsecurities
INSERT INTO `glpi_plugin_fusinvsnmp_configsecurities`
      (`id`, `name`, `snmpversion`, `community`, `username`, `authentication`, `auth_passphrase`,
       `encryption`, `priv_passphrase`, `is_deleted`)
   VALUES (1, 'Communauté Public v1', '1', 'public', '', '0', '', '0', '', '0'),
          (2, 'Communauté Public v2c', '2', 'public', '', '0', '', '0', '', '0');


## glpi_displaypreferences
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) 
   VALUES (NULL, 'PluginFusinvsnmpModel', '3', '1', '0'),
          (NULL, 'PluginFusinvsnmpModel', '5', '2', '0'),

          (NULL, 'PluginFusinvsnmpConfigSecurity', '3', '1', '0'),
          (NULL, 'PluginFusinvsnmpConfigSecurity', '4', '2', '0'),
          (NULL, 'PluginFusinvsnmpConfigSecurity', '5', '3', '0'),
          (NULL, 'PluginFusinvsnmpConfigSecurity', '7', '4', '0'),
          (NULL, 'PluginFusinvsnmpConfigSecurity', '8', '5', '0'),
          (NULL, 'PluginFusinvsnmpConfigSecurity', '9', '6', '0'),
          (NULL, 'PluginFusinvsnmpConfigSecurity', '10', '7', '0'),

          (NULL, 'PluginFusinvsnmpUnknownDevice', '2', '1', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '4', '2', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '3', '3', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '5', '4', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '7', '5', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '10', '6', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '11', '7', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '18', '8', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '14', '9', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '15', '10', '0'),
          (NULL, 'PluginFusinvsnmpUnknownDevice', '9', '11', '0'),

##          (NULL,'PluginFusinvsnmpNetworkPort', '2', '1', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '3', '2', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '4', '3', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '5', '4', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '6', '5', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '7', '6', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '8', '7', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '9', '8', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '10', '9', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '11', '10', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '14', '11', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '12', '12', '0'),
##          (NULL,'PluginFusinvsnmpNetworkPort', '13', '13', '0'),

          (NULL,'PluginFusinvsnmpIPRange', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '5', '3', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '6', '4', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '9', '5', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '7', '6', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '8', '7', '0'),

          (NULL,'PluginFusinvsnmpNetworkPortLog', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '4', '3', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '5', '4', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '6', '5', '0'),

          (NULL,'PluginFusinvsnmpNetworkPort', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpNetworkPort', '3', '2', '0');

