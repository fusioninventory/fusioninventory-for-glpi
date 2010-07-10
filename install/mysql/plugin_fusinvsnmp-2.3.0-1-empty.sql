## obsolete tables
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknown_mac`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_computers`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_snmp_networking`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_connection_history`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_connection_stats`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_discovery`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_errors`;
#DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol`;
#DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol`;
#DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_version`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_walks`;

## renamed tables
#DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_label`;
#DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_object`;
#DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_mib_oid`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents_inventory_state`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_modules`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_snmp_history`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_device`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_walks`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_construct_mibs`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lock`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lockable`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_mib_networking`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_model_infos`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking_ifaddr`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_networking_ports`;
#DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_printers_history`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_rangeip`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_history_connections`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_connection`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_snmp_history`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_task`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknown_device`;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_miblabels`;

CREATE TABLE `glpi_plugin_fusinvsnmp_miblabels` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_mibobjects`;

CREATE TABLE `glpi_plugin_fusinvsnmp_mibobjects` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_miboids`;

CREATE TABLE `glpi_plugin_fusinvsnmp_miboids` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_configs`;

CREATE TABLE `glpi_plugin_fusinvsnmp_configs` (
   `id` int(1) NOT NULL AUTO_INCREMENT,
   `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
   `URL_agent_conf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `ssl_only` int(1) NOT NULL DEFAULT '0',
   `storagesnmpauth` varchar(255) NOT NULL,
   `inventory_frequence` INT( 11 ) NULL DEFAULT '24',
   `criteria1_ip` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria1_name` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria1_serial` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria1_macaddr` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria2_ip` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria2_name` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria2_serial` INT( 1 ) NOT NULL DEFAULT '0',
   `criteria2_macaddr` INT( 1 ) NOT NULL DEFAULT '0',
   `delete_agent_process` INT( 11 ) NOT NULL DEFAULT '24',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_configlogfields`;

CREATE TABLE `glpi_plugin_fusinvsnmp_configlogfields` (
   `id` INT( 8 ) NOT NULL AUTO_INCREMENT ,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `days` int(255) NOT NULL DEFAULT '-1',
   PRIMARY KEY ( `id` ) ,
   KEY `plugin_fusioninventory_mappings_id` ( `plugin_fusioninventory_mappings_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_constructdevices`;

CREATE TABLE `glpi_plugin_fusinvsnmp_constructdevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `manufacturers_id` int(11) NOT NULL DEFAULT '0',
   `sysdescr` text,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugin_fusinvsnmp_models_id` int(11) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `plugin_fusinvsnmp_models_id` ( `manufacturers_id`, `plugin_fusinvsnmp_models_id` ),
   KEY `itemtype` ( `itemtype` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_constructdevicewalks`;

CREATE TABLE `glpi_plugin_fusinvsnmp_constructdevicewalks` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusinvsnmp_constructdevices_id` int(11) NOT NULL DEFAULT '0',
   `log` text,
  PRIMARY KEY (`id`),
  KEY `plugin_fusinvsnmp_constructdevices_id` ( `plugin_fusinvsnmp_constructdevices_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



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
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` ( `plugin_fusinvsnmp_miboids_id`,
      `plugin_fusinvsnmp_constructdevices_id`, `plugin_fusioninventory_mappings_id` ),
   KEY `itemtype` ( `itemtype` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_networkportconnectionlogs`;

CREATE TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
   `date_mod` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `creation` INT( 1 ) NOT NULL DEFAULT '0',
   `networkports_id_source` INT( 11 ) NOT NULL DEFAULT '0',
   `networkports_id_destination` INT( 11 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL DEFAULT '0',
   PRIMARY KEY ( `id` ),
   KEY `networkports_id_source` ( `networkports_id_source`, `networkports_id_destination`,
          `plugin_fusioninventory_agentprocesses_id` ),
   KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



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
   `is_active` int(1) NOT NULL DEFAULT '1',
   `vlan` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`),
   KEY `plugin_fusinvsnmp_models_id_2` (`plugin_fusinvsnmp_models_id`,`oid_port_dyn`),
   KEY `plugin_fusinvsnmp_models_id_3` (`plugin_fusinvsnmp_models_id`,`oid_port_counter`,`plugin_fusioninventory_mappings_id`),
   KEY `plugin_fusinvsnmp_models_id_4` (`plugin_fusinvsnmp_models_id`,`plugin_fusioninventory_mappings_id`),
   KEY `oid_port_dyn` (`oid_port_dyn`),
   KEY `is_active` (`is_active`),
   KEY `plugin_fusioninventory_mappings_id` (`plugin_fusioninventory_mappings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_models`;

CREATE TABLE `glpi_plugin_fusinvsnmp_models` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
   `itemtype` VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL,
   `is_deleted` int(1) NOT NULL DEFAULT '0',
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_active` int(1) NOT NULL DEFAULT '1',
   `discovery_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `itemtype` (`itemtype`),
   KEY `entities_id` (`entities_id`),
   KEY `is_deleted` (`is_deleted`),
   KEY `is_active` (`is_active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_networkequipments`;

CREATE TABLE `glpi_plugin_fusinvsnmp_networkequipments` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkequipments_id` int(11) NOT NULL,
   `plugin_fusinvsnmp_models_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_configsecurities_id` int(11) NOT NULL DEFAULT '0',
   `uptime` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
   `cpu` int(3) NOT NULL DEFAULT '0' COMMENT '%',
   `memory` int(11) NOT NULL DEFAULT '0',
   `last_fusioninventory_update` datetime DEFAULT NULL,
   `last_PID_update` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `networkequipments_id` (`networkequipments_id`),
   KEY `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`,
         `plugin_fusinvsnmp_configsecurities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_networkequipmentips`;

CREATE TABLE `glpi_plugin_fusinvsnmp_networkequipmentips` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkequipments_id` int(11) NOT NULL,
   `ip` varchar(255) NOT NULL,
   PRIMARY KEY (`id`),
   KEY `ip` (`ip`),
   KEY `networkequipments_id` (`networkequipments_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



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
   PRIMARY KEY (`id`),
   KEY `printers_id` (`printers_id`),
   KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_printers`;

CREATE TABLE `glpi_plugin_fusinvsnmp_printers` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `printers_id` int(11) NOT NULL,
   `plugin_fusinvsnmp_models_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_configsecurities_id` int(11) NOT NULL DEFAULT '0',
   `frequence_days` int(5) NOT NULL DEFAULT '1',
   `last_fusioninventory_update` datetime DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`printers_id`),
   KEY `plugin_fusinvsnmp_configsecurities_id` (`plugin_fusinvsnmp_configsecurities_id`),
   KEY `printers_id` (`printers_id`),
   KEY `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_printercartridges`;

CREATE TABLE `glpi_plugin_fusinvsnmp_printercartridges` (
   `id` int(100) NOT NULL AUTO_INCREMENT,
   `printers_id` int(11) NOT NULL,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `cartridges_id` int(11) NOT NULL DEFAULT '0',
   `state` int(3) NOT NULL DEFAULT '100',
   PRIMARY KEY (`id`),
   KEY `printers_id` (`printers_id`),
   KEY `plugin_fusioninventory_mappings_id` (`plugin_fusioninventory_mappings_id`),
   KEY `cartridges_id` (`cartridges_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



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
   PRIMARY KEY (`id`),
   KEY `plugin_fusioninventory_agents_id_discover` (`plugin_fusioninventory_agents_id_discover`),
   KEY `plugin_fusioninventory_agents_id_query` (`plugin_fusioninventory_agents_id_query`),
   KEY `entities_id` (`entities_id`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_configsnmpsecurities`;

CREATE TABLE `glpi_plugin_fusinvsnmp_configsnmpsecurities` (
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
   KEY `snmpversion` (`snmpversion`),
   KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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
   KEY `networkports_id` (`networkports_id`,`date_mod`),
   KEY `plugin_fusioninventory_mappings_id` (`plugin_fusioninventory_mappings_id`),
   KEY `plugin_fusioninventory_agentprocesses_id` (`plugin_fusioninventory_agentprocesses_id`),
   KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_unknowndevices`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvsnmp_unknowndevices` (
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
   PRIMARY KEY (`id`),
   KEY `entities_id` (`entities_id`),
   KEY `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`),
   KEY `plugin_fusinvsnmp_configsecurities_id` (`plugin_fusinvsnmp_configsecurities_id`),
   KEY `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`),
   KEY `is_deleted` (`is_deleted`),
   KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



## INSERT
## glpi_plugin_fusinvsnmp_configsnmpsecurities
INSERT INTO `glpi_plugin_fusinvsnmp_configsnmpsecurities`
      (`id`, `name`, `snmpversion`, `community`, `username`, `authentication`, `auth_passphrase`,
       `encryption`, `priv_passphrase`, `is_deleted`)
   VALUES (1, 'Communauté Public v1', '1', 'public', '', '0', '', '0', '', '0'),
          (2, 'Communauté Public v2c', '2', 'public', '', '0', '', '0', '', '0');


## glpi_displaypreferences
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) 
   VALUES (NULL, 'PluginFusinvsnmpModel', '3', '1', '0'),
          (NULL, 'PluginFusinvsnmpSnmpModel', '5', '2', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) 
   VALUES (NULL, 'PluginFusinvsnmpConfigSnmpSecurity', '3', '1', '0'),
          (NULL, 'PluginFusinvsnmpConfigSnmpSecurity', '4', '2', '0'),
          (NULL, 'PluginFusinvsnmpConfigSnmpSecurity', '5', '3', '0'),
          (NULL, 'PluginFusinvsnmpConfigSnmpSecurity', '7', '4', '0'),
          (NULL, 'PluginFusinvsnmpConfigSnmpSecurity', '8', '5', '0'),
          (NULL, 'PluginFusinvsnmpConfigSnmpSecurity', '9', '6', '0'),
          (NULL, 'PluginFusinvsnmpConfigSnmpSecurity', '10', '7', '0'),

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

          (NULL,'PluginFusinvsnmpAgent', '8', '1', '0'),
          (NULL,'PluginFusinvsnmpAgent', '9', '2', '0'),
          (NULL,'PluginFusinvsnmpAgent', '10', '3', '0'),
          (NULL,'PluginFusinvsnmpAgent', '11', '4', '0'),
          (NULL,'PluginFusinvsnmpAgent', '12', '5', '0'),
          (NULL,'PluginFusinvsnmpAgent', '13', '6', '0'),
          (NULL,'PluginFusinvsnmpAgent', '14', '7', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '5', '3', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '6', '4', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '9', '5', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '7', '6', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '8', '7', '0'),

          (NULL,'PluginFusinvsnmpAgentProcess', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '4', '3', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '5', '4', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '6', '5', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '7', '6', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '8', '7', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '9', '8', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '10', '9', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '11', '10', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '12', '11', '0'),

          (NULL,'PluginFusinvsnmpNetworkPortLog', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '4', '3', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '5', '4', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '6', '5', '0'),

          (NULL,'PluginFusinvsnmpNetworkPort', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpNetworkPort', '3', '2', '0');


## glpi_plugin_fusioninventory_mappings
INSERT INTO `glpi_plugin_fusioninventory_mappings`
      (`id`, `itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
   VALUES (1,'NetworkEquipment','location','glpi_networkequipments','locations_id',1,NULL),
          (2,'NetworkEquipment','firmware','glpi_networkequipments',
             'networkequipmentfirmwares_id',2,NULL),
          (3,'NetworkEquipment','firmware1','','',2,NULL),
          (4,'NetworkEquipment','firmware2','','',2,NULL),
          (5,'NetworkEquipment','contact','glpi_networkequipments','contact',403,NULL),
          (6,'NetworkEquipment','comments','glpi_networkequipments','comment',404,NULL),
          (7,'NetworkEquipment','uptime','glpi_plugin_fusinvsnmp_networkequipments',
             'uptime',3,NULL),
          (8,'NetworkEquipment','cpu','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',12,NULL),
          (9,'NetworkEquipment','cpuuser','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',401,NULL),
          (10,'NetworkEquipment','cpusystem','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',402,NULL),
          (11,'NetworkEquipment','serial','glpi_networkequipments','serial',13,NULL),
          (12,'NetworkEquipment','otherserial','glpi_networkequipments','otherserial',419,NULL),
          (13,'NetworkEquipment','name','glpi_networkequipments','name',20,NULL),
          (14,'NetworkEquipment','ram','glpi_networkequipments','ram',21,NULL),
          (15,'NetworkEquipment','memory','glpi_plugin_fusinvsnmp_networkequipments',
             'memory',22,NULL),
          (16,'NetworkEquipment','vtpVlanName','','',19,NULL),
          (17,'NetworkEquipment','vmvlan','','',430,NULL),
          (18,'NetworkEquipment','entPhysicalModelName','glpi_networkequipments',
             'networkequipmentmodels_id',17,NULL),
          (19,'NetworkEquipment','macaddr','glpi_networkequipments','ip',417,NULL),
## Network CDP (Walk)
          (20,'NetworkEquipment','cdpCacheAddress','','',409,NULL),
          (21,'NetworkEquipment','cdpCacheDevicePort','','',410,NULL),
          (22,'NetworkEquipment','vlanTrunkPortDynamicStatus','','',411,NULL),
          (23,'NetworkEquipment','dot1dTpFdbAddress','','',412,NULL),
          (24,'NetworkEquipment','ipNetToMediaPhysAddress','','',413,NULL),
          (25,'NetworkEquipment','dot1dTpFdbPort','','',414,NULL),
          (26,'NetworkEquipment','dot1dBasePortIfIndex','','',415,NULL),
          (27,'NetworkEquipment','PortVlanIndex','','',422,NULL),
## NetworkPorts
          (28,'NetworkEquipment','ifIndex','','',408,NULL),
          (29,'NetworkEquipment','ifmtu','glpi_plugin_fusinvsnmp_networkports',
             'ifmtu',4,NULL),
          (30,'NetworkEquipment','ifspeed','glpi_plugin_fusinvsnmp_networkports',
             'ifspeed',5,NULL),
          (31,'NetworkEquipment','ifinternalstatus','glpi_plugin_fusinvsnmp_networkports',
             'ifinternalstatus',6,NULL),
          (32,'NetworkEquipment','iflastchange','glpi_plugin_fusinvsnmp_networkports',
             'iflastchange',7,NULL),
          (33,'NetworkEquipment','ifinoctets','glpi_plugin_fusinvsnmp_networkports',
             'ifinoctets',8,NULL),
          (34,'NetworkEquipment','ifoutoctets','glpi_plugin_fusinvsnmp_networkports',
             'ifoutoctets',9,NULL),
          (35,'NetworkEquipment','ifinerrors','glpi_plugin_fusinvsnmp_networkports',
             'ifinerrors',10,NULL),
          (36,'NetworkEquipment','ifouterrors','glpi_plugin_fusinvsnmp_networkports',
             'ifouterrors',11,NULL),
          (37,'NetworkEquipment','ifstatus','glpi_plugin_fusinvsnmp_networkports',
             'ifstatus',14,NULL),
          (38,'NetworkEquipment','ifPhysAddress','glpi_networkports','mac',15,NULL),
          (39,'NetworkEquipment','ifName','glpi_networkports','name',16,NULL),
          (40,'NetworkEquipment','ifName','glpi_networkports','name',16,NULL),
          (41,'NetworkEquipment','ifType','','',18,NULL),
          (42,'NetworkEquipment','ifdescr','glpi_plugin_fusinvsnmp_networkports',
             'ifdescr',23,NULL),
          (43,'NetworkEquipment','portDuplex','glpi_plugin_fusinvsnmp_networkports',
             'portduplex',33,NULL),
## Printers
          (44,'Printer','model','glpi_printers','printermodels_id',25,NULL),
          (45,'Printer','enterprise','glpi_printers','manufacturers_id',420,NULL),
          (46,'Printer','contact','glpi_printers','contact',405,NULL),
          (47,'Printer','comments','glpi_printers','comment',406,NULL),
          (48,'Printer','otherserial','glpi_printers','otherserial',418,NULL),
          (49,'Printer','memory','glpi_printers','memory_size',26,NULL),
          (50,'Printer','location','glpi_printers','locations_id',56,NULL),
          (51,'Printer','informations','','',165,165),
## Cartridges
          (52,'Printer','tonerblack','','',157,157),
          (53,'Printer','tonerblack2','','',166,166),
          (54,'Printer','tonercyan','','',158,158),
          (55,'Printer','tonermagenta','','',159,159),
          (56,'Printer','toneryellow','','',160,160),
          (57,'Printer','wastetoner','','',151,151),
          (58,'Printer','cartridgeblack','','',134,134),
          (59,'Printer','cartridgeblackphoto','','',135,135),
          (60,'Printer','cartridgecyan','','',136,136),
          (61,'Printer','cartridgecyanlight','','',139,139),
          (62,'Printer','cartridgemagenta','','',138,138),
          (63,'Printer','cartridgemagentalight','','',140,140),
          (64,'Printer','cartridgeyellow','','',137,137),
          (65,'Printer','maintenancekit','','',156,156),
          (66,'Printer','drumblack','','',161,161),
          (67,'Printer','drumcyan','','',162,162),
          (68,'Printer','drummagenta','','',163,163),
          (69,'Printer','drumyellow','','',164,164),
## Printers : Counter pages
          (70,'Printer','pagecountertotalpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total',28,128),
          (71,'Printer','pagecounterblackpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b',29,129),
          (72,'Printer','pagecountercolorpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color',30,130),
          (73,'Printer','pagecounterrectoversopages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_recto_verso',54,154),
          (74,'Printer','pagecounterscannedpages','glpi_plugin_fusinvsnmp_printerlogs',
             'scanned',55,155),
          (75,'Printer','pagecountertotalpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_print',423,1423),
          (76,'Printer','pagecounterblackpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b_print',424,1424),
          (77,'Printer','pagecountercolorpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color_print',425,1425),
          (78,'Printer','pagecountertotalpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_copy',426,1426),
          (79,'Printer','pagecounterblackpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b_copy',427,1427),
          (80,'Printer','pagecountercolorpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color_copy',428,1428),
          (81,'Printer','pagecountertotalpages_fax','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_fax',429,1429),
## Printers : NetworkPort
          (82,'Printer','ifPhysAddress','glpi_networkports','mac',58,NULL),
          (83,'Printer','ifName','glpi_networkports','name',57,NULL),
          (84,'Printer','ifaddr','glpi_networkports','ip',407,NULL),
          (85,'Printer','ifType','','',97,NULL),
          (86,'Printer','ifIndex','','',416,NULL),
## Computer
          (87,'Computer','serial','','serial',13,NULL),
          (88,'Computer','ifPhysAddress','','mac',15,NULL);
