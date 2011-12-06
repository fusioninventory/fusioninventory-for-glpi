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



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_configlogfields`;

CREATE TABLE `glpi_plugin_fusinvsnmp_configlogfields` (
   `id` INT( 8 ) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `days` int(255) NOT NULL DEFAULT '-1',
   PRIMARY KEY ( `id` ),
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
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
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
   `discovery_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `itemtype` (`itemtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_networkequipments`;

CREATE TABLE `glpi_plugin_fusinvsnmp_networkequipments` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `networkequipments_id` int(11) NOT NULL,
   `sysdescr` text COLLATE utf8_unicode_ci,
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
   `ifspeed` bigint(50) NOT NULL DEFAULT '0',
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
   KEY `printers_id` (`printers_id`,`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_printers`;

CREATE TABLE `glpi_plugin_fusinvsnmp_printers` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `printers_id` int(11) NOT NULL,
   `sysdescr` text COLLATE utf8_unicode_ci,
   `plugin_fusinvsnmp_models_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_configsecurities_id` int(11) NOT NULL DEFAULT '0',
   `frequence_days` int(5) NOT NULL DEFAULT '1',
   `last_fusioninventory_update` datetime DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`printers_id`),
   KEY `plugin_fusinvsnmp_configsecurities_id` (`plugin_fusinvsnmp_configsecurities_id`),
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
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `ip_start` varchar(255) DEFAULT NULL,
   `ip_end` varchar(255) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



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
   `plugin_fusioninventory_unknowndevices_id` int(11) NOT NULL DEFAULT '0',
   `sysdescr` TEXT,
   `plugin_fusinvsnmp_models_id` INT( 11 ) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_configsecurities_id` INT( 11 ) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusioninventory_unknowndevices_id` (`plugin_fusioninventory_unknowndevices_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_agentconfigs`;

CREATE TABLE `glpi_plugin_fusinvsnmp_agentconfigs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
   `version_netdiscovery` TEXT COMMENT 'array(xmltag=>value)',
   `version_snmpquery` TEXT COMMENT 'array(xmltag=>value)',
   `threads_netdiscovery` int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)',
   `threads_snmpquery` int(4) DEFAULT '1' COMMENT 'array(xmltag=>value)',
   `senddico` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_statediscoveries`;

CREATE TABLE `glpi_plugin_fusinvsnmp_statediscoveries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_taskjob_id` int(11) NOT NULL DEFAULT '0',
  `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_mod` datetime DEFAULT NULL,
  `threads` int(11) NOT NULL DEFAULT '0',
  `nb_ip` int(11) NOT NULL DEFAULT '0',
  `nb_found` int(11) NOT NULL DEFAULT '0',
  `nb_error` int(11) NOT NULL DEFAULT '0',
  `nb_exists` int(11) NOT NULL DEFAULT '0',
  `nb_import` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



## INSERT
## glpi_plugin_fusinvsnmp_configsecurities
INSERT INTO `glpi_plugin_fusinvsnmp_configsecurities`
      (`id`, `name`, `snmpversion`, `community`, `username`, `authentication`, `auth_passphrase`,
       `encryption`, `priv_passphrase`, `is_deleted`)
   VALUES (1, 'Public community v1', '1', 'public', '', '0', '', '0', '', '0'),
          (2, 'Public community v2c', '2', 'public', '', '0', '', '0', '', '0');


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

          (NULL,'PluginFusinvsnmpNetworkEquipment', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '4', '3', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '5', '4', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '6', '5', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '7', '6', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '8', '7', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '9', '8', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '10', '9', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '11', '10', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '14', '11', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '12', '12', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '13', '13', '0'),

          (NULL,'PluginFusinvsnmpAgent', '8', '1', '0'),
          (NULL,'PluginFusinvsnmpAgent', '9', '2', '0'),
          (NULL,'PluginFusinvsnmpAgent', '10', '3', '0'),
          (NULL,'PluginFusinvsnmpAgent', '11', '4', '0'),
          (NULL,'PluginFusinvsnmpAgent', '12', '5', '0'),
          (NULL,'PluginFusinvsnmpAgent', '13', '6', '0'),
          (NULL,'PluginFusinvsnmpAgent', '14', '7', '0'),

          (NULL,'PluginFusinvsnmpIPRange', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpIPRange', '4', '3', '0'),

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
          (NULL,'PluginFusinvsnmpNetworkPort', '3', '2', '0'),

          (NULL,'PluginFusinvsnmpStateDiscovery', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '4', '2', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '5', '3', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '6', '4', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '7', '5', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '8', '6', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '9', '7', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '10', '8', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '11', '9', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '12', '10', '0');


## glpi_plugin_fusioninventory_mappings
INSERT INTO `glpi_plugin_fusioninventory_mappings`
      (`itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
   VALUES ('NetworkEquipment','location','glpi_networkequipments','locations_id',1,NULL),
          ('NetworkEquipment','firmware','glpi_networkequipments',
             'networkequipmentfirmwares_id',2,NULL),
          ('NetworkEquipment','firmware1','','',2,NULL),
          ('NetworkEquipment','firmware2','','',2,NULL),
          ('NetworkEquipment','contact','glpi_networkequipments','contact',403,NULL),
          ('NetworkEquipment','comments','glpi_networkequipments','comment',404,NULL),
          ('NetworkEquipment','uptime','glpi_plugin_fusinvsnmp_networkequipments',
             'uptime',3,NULL),
          ('NetworkEquipment','cpu','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',12,NULL),
          ('NetworkEquipment','cpuuser','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',401,NULL),
          ('NetworkEquipment','cpusystem','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',402,NULL),
          ('NetworkEquipment','serial','glpi_networkequipments','serial',13,NULL),
          ('NetworkEquipment','otherserial','glpi_networkequipments','otherserial',419,NULL),
          ('NetworkEquipment','name','glpi_networkequipments','name',20,NULL),
          ('NetworkEquipment','ram','glpi_networkequipments','ram',21,NULL),
          ('NetworkEquipment','memory','glpi_plugin_fusinvsnmp_networkequipments',
             'memory',22,NULL),
          ('NetworkEquipment','vtpVlanName','','',19,NULL),
          ('NetworkEquipment','vmvlan','','',430,NULL),
          ('NetworkEquipment','entPhysicalModelName','glpi_networkequipments',
             'networkequipmentmodels_id',17,NULL),
          ('NetworkEquipment','macaddr','glpi_networkequipments','ip',417,NULL),
## Network CDP (Walk)
          ('NetworkEquipment','cdpCacheAddress','','',409,NULL),
          ('NetworkEquipment','cdpCacheDevicePort','','',410,NULL),
          ('NetworkEquipment','lldpRemChassisId','','',431,NULL),
          ('NetworkEquipment','lldpRemPortId','','',432,NULL),
          ('NetworkEquipment','lldpLocChassisId','','',432,NULL),
          ('NetworkEquipment','vlanTrunkPortDynamicStatus','','',411,NULL),
          ('NetworkEquipment','dot1dTpFdbAddress','','',412,NULL),
          ('NetworkEquipment','ipNetToMediaPhysAddress','','',413,NULL),
          ('NetworkEquipment','dot1dTpFdbPort','','',414,NULL),
          ('NetworkEquipment','dot1dBasePortIfIndex','','',415,NULL),
          ('NetworkEquipment','ipAdEntAddr','','',421,NULL),
          ('NetworkEquipment','PortVlanIndex','','',422,NULL),
## NetworkPorts
          ('NetworkEquipment','ifIndex','','',408,NULL),
          ('NetworkEquipment','ifmtu','glpi_plugin_fusinvsnmp_networkports',
             'ifmtu',4,NULL),
          ('NetworkEquipment','ifspeed','glpi_plugin_fusinvsnmp_networkports',
             'ifspeed',5,NULL),
          ('NetworkEquipment','ifinternalstatus','glpi_plugin_fusinvsnmp_networkports',
             'ifinternalstatus',6,NULL),
          ('NetworkEquipment','iflastchange','glpi_plugin_fusinvsnmp_networkports',
             'iflastchange',7,NULL),
          ('NetworkEquipment','ifinoctets','glpi_plugin_fusinvsnmp_networkports',
             'ifinoctets',8,NULL),
          ('NetworkEquipment','ifoutoctets','glpi_plugin_fusinvsnmp_networkports',
             'ifoutoctets',9,NULL),
          ('NetworkEquipment','ifinerrors','glpi_plugin_fusinvsnmp_networkports',
             'ifinerrors',10,NULL),
          ('NetworkEquipment','ifouterrors','glpi_plugin_fusinvsnmp_networkports',
             'ifouterrors',11,NULL),
          ('NetworkEquipment','ifstatus','glpi_plugin_fusinvsnmp_networkports',
             'ifstatus',14,NULL),
          ('NetworkEquipment','ifPhysAddress','glpi_networkports','mac',15,NULL),
          ('NetworkEquipment','ifName','glpi_networkports','name',16,NULL),
          ('NetworkEquipment','ifType','','',18,NULL),
          ('NetworkEquipment','ifdescr','glpi_plugin_fusinvsnmp_networkports',
             'ifdescr',23,NULL),
          ('NetworkEquipment','portDuplex','glpi_plugin_fusinvsnmp_networkports',
             'portduplex',33,NULL),
## Printers
          ('Printer','model','glpi_printers','printermodels_id',25,NULL),
          ('Printer','enterprise','glpi_printers','manufacturers_id',420,NULL),
          ('Printer','serial','glpi_printers','serial',27,NULL),
          ('Printer','contact','glpi_printers','contact',405,NULL),
          ('Printer','comments','glpi_printers','comment',406,NULL),
          ('Printer','name','glpi_printers','comment',24,NULL),
          ('Printer','otherserial','glpi_printers','otherserial',418,NULL),
          ('Printer','memory','glpi_printers','memory_size',26,NULL),
          ('Printer','location','glpi_printers','locations_id',56,NULL),
          ('Printer','informations','','',165,165),
## Cartridges
          ('Printer','tonerblack','','',157,157),
          ('Printer','tonerblackmax','','',166,166),
          ('Printer','tonerblackused','','',167,167),
          ('Printer','tonerblackremaining','','',168,168),
          ('Printer','tonerblack2','','',157,157),
          ('Printer','tonerblack2max','','',166,166),
          ('Printer','tonerblack2used','','',167,167),
          ('Printer','tonerblack2remaining','','',168,168),
          ('Printer','tonercyan','','',158,158),
          ('Printer','tonercyanmax','','',169,169),
          ('Printer','tonercyanused','','',170,170),
          ('Printer','tonercyanremaining','','',171,171),
          ('Printer','tonermagenta','','',159,159),
          ('Printer','tonermagentamax','','',172,172),
          ('Printer','tonermagentaused','','',173,173),
          ('Printer','tonermagentaremaining','','',174,174),
          ('Printer','toneryellow','','',160,160),
          ('Printer','toneryellowmax','','',175,175),
          ('Printer','toneryellowused','','',176,176),
          ('Printer','toneryellowremaining','','',177,177),
          ('Printer','wastetoner','','',151,151),
          ('Printer','wastetonermax','','',190,190),
          ('Printer','wastetonerused','','',191,191),
          ('Printer','wastetonerremaining','','',192,192),
          ('Printer','cartridgeblack','','',134,134),
          ('Printer','cartridgeblackphoto','','',135,135),
          ('Printer','cartridgecyan','','',136,136),
          ('Printer','cartridgecyanlight','','',139,139),
          ('Printer','cartridgemagenta','','',138,138),
          ('Printer','cartridgemagentalight','','',140,140),
          ('Printer','cartridgeyellow','','',137,137),
          ('Printer','cartridgegrey','','',196,196),
          ('Printer','maintenancekit','','',156,156),
          ('Printer','maintenancekitmax','','',193,193),
          ('Printer','maintenancekitused','','',194,194),
          ('Printer','maintenancekitremaining','','',195,195),
          ('Printer','drumblack','','',161,161),
          ('Printer','drumblackmax','','',178,178),
          ('Printer','drumblackused','','',179,179),
          ('Printer','drumblackremaining','','',180,180),
          ('Printer','drumcyan','','',162,162),
          ('Printer','drumcyanmax','','',181,181),
          ('Printer','drumcyanused','','',182,182),
          ('Printer','drumcyanremaining','','',183,183),
          ('Printer','drummagenta','','',163,163),
          ('Printer','drummagentamax','','',184,184),
          ('Printer','drummagentaused','','',185,185),
          ('Printer','drummagentaremaining','','',186,186),
          ('Printer','drumyellow','','',164,164),
          ('Printer','drumyellowmax','','',187,187),
          ('Printer','drumyellowused','','',188,188),
          ('Printer','drumyellowremaining','','',189,189),
## Printers : Counter pages
          ('Printer','pagecountertotalpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total',28,128),
          ('Printer','pagecounterblackpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b',29,129),
          ('Printer','pagecountercolorpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color',30,130),
          ('Printer','pagecounterrectoversopages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_recto_verso',54,154),
          ('Printer','pagecounterscannedpages','glpi_plugin_fusinvsnmp_printerlogs',
             'scanned',55,155),
          ('Printer','pagecountertotalpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_print',423,1423),
          ('Printer','pagecounterblackpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b_print',424,1424),
          ('Printer','pagecountercolorpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color_print',425,1425),
          ('Printer','pagecountertotalpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_copy',426,1426),
          ('Printer','pagecounterblackpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b_copy',427,1427),
          ('Printer','pagecountercolorpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color_copy',428,1428),
          ('Printer','pagecountertotalpages_fax','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_fax',429,1429),
          ('Printer','pagecounterlargepages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_large',434,1434),
## Printers : NetworkPort
          ('Printer','ifPhysAddress','glpi_networkports','mac',58,NULL),
          ('Printer','ifName','glpi_networkports','name',57,NULL),
          ('Printer','ifaddr','glpi_networkports','ip',407,NULL),
          ('Printer','ifType','','',97,NULL),
          ('Printer','ifIndex','','',416,NULL),
## Computer
          ('Computer','serial','','serial',13,NULL),
          ('Computer','ifPhysAddress','','mac',15,NULL),
          ('Computer','ifaddr','','ip',407,NULL);