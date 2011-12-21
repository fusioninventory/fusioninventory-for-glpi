CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_agents` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifaddr_start` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifaddr_end` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nb_process_query` int(11) NOT NULL DEFAULT '1',
  `nb_process_discovery` int(11) NOT NULL DEFAULT '1',
  `last_agent_update` datetime DEFAULT NULL,
  `tracker_agent_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lock` int(1) NOT NULL DEFAULT '0',
  `logs` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_computers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` int(11) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_config` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `activation_history` int(1) DEFAULT NULL,
  `activation_connection` int(1) DEFAULT NULL,
  `activation_snmp_computer` int(1) NOT NULL DEFAULT '0',
  `activation_snmp_networking` int(1) DEFAULT NULL,
  `activation_snmp_peripheral` int(1) DEFAULT NULL,
  `activation_snmp_phone` int(1) DEFAULT NULL,
  `activation_snmp_printer` int(1) DEFAULT NULL,
  `authsnmp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nb_process_query` int(11) NOT NULL DEFAULT '1',
  `nb_process_discovery` int(11) NOT NULL DEFAULT '1',
  `logs` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_config_snmp_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `active_device_state` int(11) NOT NULL DEFAULT '0',
  `history_wire` int(11) NOT NULL DEFAULT '0',
  `history_ports_state` int(11) NOT NULL DEFAULT '0',
  `history_unknown_mac` int(11) NOT NULL DEFAULT '0',
  `history_snmp_errors` int(11) NOT NULL DEFAULT '0',
  `history_process` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_config_snmp_printer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `active_device_state` int(1) NOT NULL DEFAULT '0',
  `manage_cartridges` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_connection_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_users` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_connection_stats` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `device_type` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL,
  `checksum` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_discover` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ifaddr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descr` text COLLATE utf8_unicode_ci,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `FK_model_infos` int(11) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_discover_conf` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ifaddr_start` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifaddr_end` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discover` int(11) NOT NULL DEFAULT '0',
  `getserialnumber` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_errors` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ifaddr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_type` smallint(6) NOT NULL,
  `device_id` int(11) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `first_pb_date` datetime DEFAULT NULL,
  `last_pb_date` datetime DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ifaddr` (`ifaddr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_mib_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_model_infos` int(8) DEFAULT NULL,
  `FK_mib_label` int(8) DEFAULT NULL,
  `FK_mib_oid` int(8) DEFAULT NULL,
  `FK_mib_object` int(8) DEFAULT NULL,
  `oid_port_counter` int(1) DEFAULT NULL,
  `oid_port_dyn` int(1) DEFAULT NULL,
  `mapping_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mapping_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_model_infos` (`FK_model_infos`),
  KEY `FK_model_infos_2` (`FK_model_infos`,`oid_port_dyn`),
  KEY `FK_model_infos_3` (`FK_model_infos`,`oid_port_counter`,`mapping_name`),
  KEY `FK_model_infos_4` (`FK_model_infos`,`mapping_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=45 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_model_infos` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `device_type` int(8) NOT NULL DEFAULT '0',
  `deleted` int(1) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(8) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  `uptime` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `cpu` int(3) NOT NULL DEFAULT '0',
  `memory` int(8) NOT NULL DEFAULT '0',
  `last_tracker_update` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_networking` (`FK_networking`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_networking_ifaddr` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(11) NOT NULL,
  `ifaddr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ifaddr` (`ifaddr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_networking_ports` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking_ports` int(8) NOT NULL,
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
  `ifmac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifdescr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `portduplex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trunk` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_networking_ports` (`FK_networking_ports`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_printers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  `frequence_days` int(5) NOT NULL DEFAULT '1',
  `last_tracker_update` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `FK_printers` (`FK_printers`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_printers_cartridges` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL,
  `object_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FK_cartridges` int(11) NOT NULL DEFAULT '0',
  `state` int(3) NOT NULL DEFAULT '100',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_printers_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT '0000-00-00 00:00:00',
  `pages_total` int(11) NOT NULL DEFAULT '0',
  `pages_n_b` int(11) NOT NULL DEFAULT '0',
  `pages_color` int(11) NOT NULL DEFAULT '0',
  `pages_recto_verso` int(11) NOT NULL DEFAULT '0',
  `scanned` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_processes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(4) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(4) NOT NULL DEFAULT '0',
  `error_msg` text COLLATE utf8_unicode_ci,
  `process_id` int(11) NOT NULL DEFAULT '0',
  `network_queries` int(8) NOT NULL DEFAULT '0',
  `printer_queries` int(8) NOT NULL DEFAULT '0',
  `ports_queries` int(8) NOT NULL DEFAULT '0',
  `discovery_queries` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `end_time` (`end_time`),
  KEY `process_id` (`process_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_processes_values` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_processes` int(8) NOT NULL,
  `device_ID` int(8) NOT NULL DEFAULT '0',
  `device_type` int(8) NOT NULL DEFAULT '0',
  `port` int(8) NOT NULL DEFAULT '0',
  `unknow_mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_errors` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropdown_add` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `unknow_mac` (`unknow_mac`,`FK_processes`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_profiles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interface` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'tracker',
  `is_default` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_networking` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_peripherals` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_printers` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_models` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_authentification` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_scripts_infos` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_discovery` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `general_config` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_snmp_connection` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_snmp_version` int(8) NOT NULL,
  `community` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sec_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sec_level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_snmp_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_ports` int(11) NOT NULL,
  `Field` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_device_type` int(11) NOT NULL DEFAULT '0',
  `old_device_ID` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_device_type` int(11) NOT NULL DEFAULT '0',
  `new_device_ID` int(11) NOT NULL DEFAULT '0',
  `FK_process` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_ports` (`FK_ports`,`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=257 ;



CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_unknown_mac` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `start_FK_processes` int(8) NOT NULL,
  `end_FK_processes` int(8) NOT NULL,
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `port` int(8) NOT NULL,
  `unknow_mac` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;



INSERT INTO `glpi_plugin_tracker_config` (`ID`, `activation_history`, `activation_connection`, `activation_snmp_computer`, `activation_snmp_networking`, `activation_snmp_peripheral`, `activation_snmp_phone`, `activation_snmp_printer`, `authsnmp`, `nb_process_query`, `nb_process_discovery`, `logs`) VALUES
(1, 1, 0, 0, 1, 0, 0, 1, 'DB', 5, 10, 2);



INSERT INTO `glpi_plugin_tracker_config_snmp_networking` (`ID`, `active_device_state`, `history_wire`, `history_ports_state`, `history_unknown_mac`, `history_snmp_errors`, `history_process`) VALUES
(1, 1, 0, 0, 0, 0, 0);



INSERT INTO `glpi_plugin_tracker_config_snmp_printer` (`ID`, `active_device_state`, `manage_cartridges`) VALUES
(1, 0, 0);



INSERT INTO `glpi_plugin_tracker_discover` (`ID`, `date`, `ifaddr`, `name`, `descr`, `serialnumber`, `type`, `FK_model_infos`, `FK_snmp_connection`) VALUES
(1, '2009-03-11 15:15:05', '192.168.0.1', 'pfsense.local', 'pfsense.local 0 FreeBSD 6.2-RELEASE-p11', NULL, 1, 0, 2),
(2, '2009-03-11 15:16:05', '192.168.0.80', 'switch2960-001', 'Cisco IOS Software, C2960 Software (C2960-LANBASE-M), Version 12.2(25)SEE2, RELEASE SOFTWARE (fc1)\r\nCopyright (c) 1986-2006 by Cisco Systems, Inc.\r\nCompiled Fri 28-Jul-06 04:33 by yenanh', NULL, 2, 0, 2),
(3, '2009-03-11 15:17:03', '192.168.0.150', 'SP C410DN', 'NRG SP C410DN 1.09 / NRG Network Printer C model', NULL, 3, 0, 2),
(4, '2009-03-11 15:17:29', '192.168.0.180', 'repondeur', 'Windows repondeur 5.0.2195 Service Pack 4 2000 Professional x86 Family 6 Model 6 Stepping 2', NULL, 0, 0, 2);



INSERT INTO `glpi_plugin_tracker_discover_conf` (`ID`, `ifaddr_start`, `ifaddr_end`, `discover`, `getserialnumber`) VALUES
(1, '192.168.0.1', '192.168.0.254', 0, 0);



INSERT INTO `glpi_plugin_tracker_mib_networking` (`ID`, `FK_model_infos`, `FK_mib_label`, `FK_mib_oid`, `FK_mib_object`, `oid_port_counter`, `oid_port_dyn`, `mapping_type`, `mapping_name`) VALUES
(1, 1, NULL, 1, 1, 1, 0, '0', ''),
(2, 1, NULL, 2, 2, 0, 1, '2', 'ifspeed'),
(3, 1, NULL, 3, 3, 0, 1, '2', 'ifinoctets'),
(4, 1, NULL, 4, 4, 0, 0, '2', 'cpu'),
(5, 1, NULL, 5, 5, 0, 0, '2', 'uptime'),
(6, 1, NULL, 6, 6, 0, 0, '2', 'serial'),
(7, 1, NULL, 7, 7, 0, 1, '2', 'ifmtu'),
(8, 1, NULL, 8, 8, 0, 0, '2', 'location'),
(9, 1, NULL, 9, 9, 0, 1, '2', 'ifinternalstatus'),
(10, 1, NULL, 10, 10, 0, 1, '2', 'iflastchange'),
(11, 1, NULL, 11, 11, 0, 1, '2', 'ifinerrors'),
(12, 1, NULL, 12, 12, 0, 1, '2', 'ifoutoctets'),
(13, 1, NULL, 13, 13, 0, 1, '2', 'ifouterrors'),
(14, 1, NULL, 14, 14, 0, 1, '2', 'ifPhysAddress'),
(15, 1, NULL, 15, 15, 0, 1, '2', 'ifName'),
(16, 1, NULL, 16, 16, 0, 1, '2', 'ifType'),
(17, 1, NULL, 17, 17, 0, 0, '2', 'vtpVlanName'),
(18, 1, NULL, 18, 18, 0, 0, '2', 'memory'),
(19, 1, NULL, 19, 19, 0, 0, '2', 'ram'),
(20, 1, NULL, 20, 20, 0, 1, '2', 'ifstatus'),
(21, 1, NULL, 21, 21, 0, 1, '2', 'ifdescr'),
(22, 1, NULL, 22, 22, 0, 0, '2', 'name'),
(23, 2, NULL, 1, 1, 1, 0, '0', ''),
(24, 2, NULL, 2, 2, 0, 1, '2', 'ifspeed'),
(25, 2, NULL, 3, 3, 0, 1, '2', 'ifinoctets'),
(26, 2, NULL, 4, 4, 0, 0, '2', 'cpu'),
(27, 2, NULL, 5, 5, 0, 0, '2', 'uptime'),
(28, 2, NULL, 23, 23, 0, 0, '2', 'serial'),
(29, 2, NULL, 7, 7, 0, 1, '2', 'ifmtu'),
(30, 2, NULL, 8, 8, 0, 0, '2', 'location'),
(31, 2, NULL, 9, 9, 0, 1, '2', 'ifinternalstatus'),
(32, 2, NULL, 10, 10, 0, 1, '2', 'iflastchange'),
(33, 2, NULL, 11, 11, 0, 1, '2', 'ifinerrors'),
(34, 2, NULL, 12, 12, 0, 1, '2', 'ifoutoctets'),
(35, 2, NULL, 13, 13, 0, 1, '2', 'ifouterrors'),
(36, 2, NULL, 14, 14, 0, 1, '2', 'ifPhysAddress'),
(37, 2, NULL, 15, 15, 0, 1, '2', 'ifName'),
(38, 2, NULL, 16, 16, 0, 1, '2', 'ifType'),
(39, 2, NULL, 17, 17, 0, 0, '2', 'vtpVlanName'),
(40, 2, NULL, 18, 18, 0, 0, '2', 'memory'),
(41, 2, NULL, 19, 19, 0, 0, '2', 'ram'),
(42, 2, NULL, 20, 20, 0, 1, '2', 'ifstatus'),
(43, 2, NULL, 21, 21, 0, 1, '2', 'ifdescr'),
(44, 2, NULL, 22, 22, 0, 0, '2', 'name');



INSERT INTO `glpi_plugin_tracker_model_infos` (`ID`, `name`, `device_type`, `deleted`) VALUES
(2, 'Cisco 2960', 2, NULL);



INSERT INTO `glpi_plugin_tracker_networking` (`ID`, `FK_networking`, `FK_model_infos`, `FK_snmp_connection`, `uptime`, `cpu`, `memory`, `last_tracker_update`) VALUES
(1, 1, 2, 2, '(915705801) 105 days, 23:37:38.01', 4, 36, '2009-03-17 09:35:08');



INSERT INTO `glpi_plugin_tracker_networking_ifaddr` (`ID`, `FK_networking`, `ifaddr`) VALUES
(1, 1, '192.168.0.80');



INSERT INTO `glpi_plugin_tracker_networking_ports` (`ID`, `FK_networking_ports`, `ifmtu`, `ifspeed`, `ifinternalstatus`, `ifconnectionstatus`, `iflastchange`, `ifinoctets`, `ifinerrors`, `ifoutoctets`, `ifouterrors`, `ifstatus`, `ifmac`, `ifdescr`, `portduplex`, `trunk`) VALUES
(1, 1, 1500, 100000000, 'up(1)', 0, '(563338622) 65 days, 4:49:46.22', 797365260, 67432, 860852169, 0, 'up(1)', NULL, 'FastEthernet0/1', NULL, 1),
(2, 2, 1500, 10000000, 'up(1)', 0, '(4125) 0:00:41.25', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/2', NULL, 0),
(3, 3, 1500, 10000000, 'up(1)', 0, '(4125) 0:00:41.25', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/3', NULL, 0),
(4, 4, 1500, 100000000, 'up(1)', 0, '(915533234) 105 days, 23:08:52.34', 1264246593, 0, 2614105200, 0, 'up(1)', NULL, 'FastEthernet0/4', NULL, 0),
(5, 5, 1500, 10000000, 'up(1)', 0, '(4126) 0:00:41.26', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/5', NULL, 0),
(6, 6, 1500, 100000000, 'up(1)', 0, '(865423625) 100 days, 3:57:16.25', 3868675618, 0, 2427461220, 0, 'down(2)', NULL, 'FastEthernet0/6', NULL, 0),
(7, 7, 1500, 10000000, 'up(1)', 0, '(4126) 0:00:41.26', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/7', NULL, 0),
(8, 8, 1500, 100000000, 'up(1)', 0, '(865500290) 100 days, 4:10:02.90', 3976920277, 0, 142381786, 0, 'up(1)', NULL, 'FastEthernet0/8', NULL, 0),
(9, 9, 1500, 100000000, 'up(1)', 0, '(483310062) 55 days, 22:31:40.62', 508058239, 1, 1161918692, 0, 'down(2)', NULL, 'FastEthernet0/9', NULL, 0),
(10, 10, 1500, 100000000, 'up(1)', 0, '(864727977) 100 days, 2:01:19.77', 80752741, 0, 404701514, 0, 'down(2)', NULL, 'FastEthernet0/10', NULL, 0),
(11, 11, 1500, 10000000, 'up(1)', 0, '(4126) 0:00:41.26', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/11', NULL, 0),
(12, 12, 1500, 100000000, 'up(1)', 0, '(881883891) 102 days, 1:40:38.91', 1696765667, 0, 1542021917, 0, 'down(2)', NULL, 'FastEthernet0/12', NULL, 0),
(13, 13, 1500, 100000000, 'up(1)', 0, '(434239789) 50 days, 6:13:17.89', 11956, 0, 6005692, 0, 'down(2)', NULL, 'FastEthernet0/13', NULL, 0),
(14, 14, 1500, 100000000, 'up(1)', 0, '(371460363) 42 days, 23:50:03.63', 3511137851, 0, 2749410611, 0, 'down(2)', NULL, 'FastEthernet0/14', NULL, 0),
(15, 15, 1500, 10000000, 'up(1)', 0, '(4127) 0:00:41.27', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/15', NULL, 0),
(16, 16, 1500, 100000000, 'up(1)', 0, '(864730044) 100 days, 2:01:40.44', 593654475, 0, 899706968, 0, 'up(1)', NULL, 'FastEthernet0/16', NULL, 0),
(17, 17, 1500, 10000000, 'up(1)', 0, '(4127) 0:00:41.27', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/17', NULL, 0),
(18, 18, 1500, 100000000, 'up(1)', 0, '(889706824) 102 days, 23:24:28.24', 2019626092, 2, 2601420949, 0, 'up(1)', NULL, 'FastEthernet0/18', NULL, 0),
(19, 19, 1500, 10000000, 'up(1)', 0, '(4127) 0:00:41.27', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/19', NULL, 0),
(20, 20, 1500, 100000000, 'up(1)', 0, '(371459738) 42 days, 23:49:57.38', 502665366, 0, 2087837550, 0, 'down(2)', NULL, 'FastEthernet0/20', NULL, 0),
(21, 21, 1500, 100000000, 'up(1)', 0, '(491995038) 56 days, 22:39:10.38', 961075134, 0, 2593252620, 0, 'down(2)', NULL, 'FastEthernet0/21', NULL, 0),
(22, 22, 1500, 100000000, 'up(1)', 0, '(914984455) 105 days, 21:37:24.55', 2671816467, 0, 684125974, 0, 'up(1)', NULL, 'FastEthernet0/22', NULL, 0),
(23, 23, 1500, 10000000, 'up(1)', 0, '(4128) 0:00:41.28', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/23', NULL, 0),
(24, 24, 1500, 10000000, 'up(1)', 0, '(4128) 0:00:41.28', 0, 0, 0, 0, 'down(2)', NULL, 'FastEthernet0/24', NULL, 0),
(25, 25, 1500, 10000000, 'up(1)', 0, '(4128) 0:00:41.28', 0, 0, 0, 0, 'down(2)', NULL, 'GigabitEthernet0/1', NULL, 0),
(26, 26, 1500, 10000000, 'up(1)', 0, '(4128) 0:00:41.28', 0, 0, 0, 0, 'down(2)', NULL, 'GigabitEthernet0/2', NULL, 0);



INSERT INTO `glpi_plugin_tracker_processes` (`ID`, `thread_id`, `start_time`, `end_time`, `status`, `error_msg`, `process_id`, `network_queries`, `printer_queries`, `ports_queries`, `discovery_queries`) VALUES
(1, 0, '2009-03-11 11:47:41', '2009-03-11 11:47:44', 3, '0', 701147, 1, 0, 26, 0),
(2, 0, '2009-03-11 11:50:37', '2009-03-11 11:50:40', 3, '0', 701150, 1, 0, 26, 0),
(3, 0, '2009-03-11 11:53:38', '2009-03-11 11:53:41', 3, '0', 701153, 1, 0, 26, 0),
(4, 0, '2009-03-11 11:57:46', '2009-03-11 11:57:49', 3, '0', 701157, 1, 0, 26, 0),
(5, 0, '2009-03-11 11:59:50', '2009-03-11 11:59:52', 3, '0', 701159, 1, 0, 26, 0),
(6, 0, '2009-03-11 12:00:15', '2009-03-11 12:00:17', 3, '0', 701200, 1, 0, 26, 0),
(7, 0, '2009-03-11 13:17:07', '2009-03-11 13:17:41', 3, '0', 701317, 1, 0, 26, 0),
(8, 0, '2009-03-11 13:27:22', '2009-03-11 13:27:26', 3, '0', 701327, 1, 0, 26, 0),
(9, 0, '2009-03-11 13:37:43', '2009-03-11 13:37:46', 3, '0', 701337, 1, 0, 26, 0),
(10, 0, '2009-03-11 13:42:27', '2009-03-11 13:42:30', 3, '0', 701342, 1, 0, 26, 0),
(11, 0, '2009-03-11 13:43:34', '2009-03-11 13:43:37', 3, '0', 701343, 1, 0, 26, 0),
(12, 0, '2009-03-11 13:44:48', '2009-03-11 13:44:51', 3, '0', 701344, 1, 0, 26, 0),
(13, 0, '2009-03-11 13:47:16', '2009-03-11 13:47:19', 3, '0', 701347, 1, 0, 26, 0),
(14, 0, '2009-03-11 13:48:02', '2009-03-11 13:48:05', 3, '0', 701348, 1, 0, 26, 0),
(15, 0, '2009-03-11 13:54:23', '2009-03-11 13:54:26', 3, '0', 701354, 1, 0, 26, 0),
(16, 0, '2009-03-11 13:56:16', '2009-03-11 13:56:19', 3, '0', 701356, 1, 0, 26, 0),
(17, 0, '2009-03-11 13:59:51', '2009-03-11 13:59:54', 3, '0', 701359, 1, 0, 26, 0),
(18, 0, '2009-03-11 14:02:19', '2009-03-11 14:02:21', 3, '0', 701402, 1, 0, 26, 0),
(19, 0, '2009-03-11 14:06:11', '2009-03-11 14:06:14', 3, '0', 701406, 1, 0, 26, 0),
(20, 0, '2009-03-11 14:07:08', '2009-03-11 14:07:11', 3, '0', 701407, 1, 0, 26, 0),
(21, 0, '2009-03-11 14:08:10', '2009-03-11 14:08:12', 3, '0', 701408, 1, 0, 26, 0),
(22, 0, '2009-03-11 14:19:43', '2009-03-11 14:19:47', 3, '0', 701419, 1, 0, 26, 0),
(23, 0, '2009-03-11 15:15:03', '2009-03-11 15:18:40', 3, '0', 701515, 0, 0, 0, 254),
(24, 0, '2009-03-12 10:22:36', '2009-03-12 10:22:41', 3, '0', 711022, 1, 0, 26, 0),
(25, 0, '2009-03-12 10:27:21', '2009-03-12 10:27:23', 3, '0', 711027, 1, 0, 26, 0),
(26, 0, '2009-03-12 10:28:26', '2009-03-12 10:28:35', 3, '0', 711028, 1, 0, 26, 0),
(27, 0, '2009-03-12 14:00:16', '2009-03-12 14:00:30', 3, '0', 711400, 2, 0, 52, 0),
(28, 0, '2009-03-12 14:00:26', '2009-03-12 14:00:30', 3, '0', 711400, 1, 0, 26, 0),
(29, 0, '2009-03-17 09:35:06', '2009-03-17 09:35:09', 3, '0', 760935, 1, 0, 26, 0);



INSERT INTO `glpi_plugin_tracker_profiles` (`ID`, `name`, `interface`, `is_default`, `snmp_networking`, `snmp_peripherals`, `snmp_printers`, `snmp_models`, `snmp_authentification`, `snmp_scripts_infos`, `snmp_discovery`, `general_config`) VALUES
(4, 'super-admin', 'tracker', '0', 'w', 'w', 'w', 'w', 'w', 'w', 'w', 'w');



INSERT INTO `glpi_plugin_tracker_snmp_connection` (`ID`, `name`, `FK_snmp_version`, `community`, `sec_name`, `sec_level`, `auth_protocol`, `auth_passphrase`, `priv_protocol`, `priv_passphrase`, `deleted`) VALUES
(1, 'Communauté Public v1', 1, 'public', '', '0', '0', '', '0', '', 0),
(2, 'Communauté Public v2c', 2, 'public', '', '0', '0', '', '0', '', 0);



INSERT INTO `glpi_plugin_tracker_snmp_history` (`ID`, `FK_ports`, `Field`, `date_mod`, `old_value`, `old_device_type`, `old_device_ID`, `new_value`, `new_device_type`, `new_device_ID`, `FK_process`) VALUES
(1, 1, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/1', 0, 0, 701147),
(2, 1, 'réseaux > port > nombre d''erreurs entrées', '2009-03-11 11:47:43', '0', 0, 0, '67349', 0, 0, 701147),
(3, 1, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(4, 1, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(563338622) 65 days, 4:49:46.22', 0, 0, 701147),
(5, 1, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(6, 1, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:81', 0, 0, 701147),
(7, 1, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(8, 1, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(9, 2, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/2', 0, 0, 701147),
(10, 2, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(11, 2, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4125) 0:00:41.25', 0, 0, 701147),
(12, 2, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(13, 2, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:82', 0, 0, 701147),
(14, 2, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(15, 2, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(16, 3, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/3', 0, 0, 701147),
(17, 3, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(18, 3, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4125) 0:00:41.25', 0, 0, 701147),
(19, 3, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(20, 3, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:83', 0, 0, 701147),
(21, 3, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(22, 3, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(23, 4, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/4', 0, 0, 701147),
(24, 4, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(25, 4, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(858579986) 99 days, 8:56:39.86', 0, 0, 701147),
(26, 4, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(27, 4, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:84', 0, 0, 701147),
(28, 4, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(29, 4, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(30, 5, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/5', 0, 0, 701147),
(31, 5, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(32, 5, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4126) 0:00:41.26', 0, 0, 701147),
(33, 5, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(34, 5, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:85', 0, 0, 701147),
(35, 5, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(36, 5, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(37, 6, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/6', 0, 0, 701147),
(38, 6, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(39, 6, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(321480424) 37 days, 5:00:04.24', 0, 0, 701147),
(40, 6, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(41, 6, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:86', 0, 0, 701147),
(42, 6, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(43, 6, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(44, 7, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/7', 0, 0, 701147),
(45, 7, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(46, 7, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4126) 0:00:41.26', 0, 0, 701147),
(47, 7, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(48, 7, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:87', 0, 0, 701147),
(49, 7, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(50, 7, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(51, 8, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/8', 0, 0, 701147),
(52, 8, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(53, 8, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(813544117) 94 days, 3:50:41.17', 0, 0, 701147),
(54, 8, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(55, 8, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:88', 0, 0, 701147),
(56, 8, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(57, 8, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(58, 9, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/9', 0, 0, 701147),
(59, 9, 'réseaux > port > nombre d''erreurs entrées', '2009-03-11 11:47:43', '0', 0, 0, '1', 0, 0, 701147),
(60, 9, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(61, 9, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(483310062) 55 days, 22:31:40.62', 0, 0, 701147),
(62, 9, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(63, 9, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:89', 0, 0, 701147),
(64, 9, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(65, 9, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(66, 10, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/10', 0, 0, 701147),
(67, 10, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(68, 10, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(806341622) 93 days, 7:50:16.22', 0, 0, 701147),
(69, 10, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(70, 10, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:8a', 0, 0, 701147),
(71, 10, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(72, 10, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(73, 11, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/11', 0, 0, 701147),
(74, 11, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(75, 11, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4126) 0:00:41.26', 0, 0, 701147),
(76, 11, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(77, 11, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:8b', 0, 0, 701147),
(78, 11, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(79, 11, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(80, 12, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/12', 0, 0, 701147),
(81, 12, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(82, 12, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(491857378) 56 days, 22:16:13.78', 0, 0, 701147),
(83, 12, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(84, 12, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:8c', 0, 0, 701147),
(85, 12, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(86, 12, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(87, 13, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/13', 0, 0, 701147),
(88, 13, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(89, 13, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(434239789) 50 days, 6:13:17.89', 0, 0, 701147),
(90, 13, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(91, 13, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:8d', 0, 0, 701147),
(92, 13, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(93, 13, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(94, 14, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/14', 0, 0, 701147),
(95, 14, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(96, 14, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(371460363) 42 days, 23:50:03.63', 0, 0, 701147),
(97, 14, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(98, 14, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:8e', 0, 0, 701147),
(99, 14, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(100, 14, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(101, 15, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/15', 0, 0, 701147),
(102, 15, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(103, 15, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4127) 0:00:41.27', 0, 0, 701147),
(104, 15, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(105, 15, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:8f', 0, 0, 701147),
(106, 15, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(107, 15, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(108, 16, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/16', 0, 0, 701147),
(109, 16, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(110, 16, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(380059360) 43 days, 23:43:13.60', 0, 0, 701147),
(111, 16, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(112, 16, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:90', 0, 0, 701147),
(113, 16, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(114, 16, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(115, 17, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/17', 0, 0, 701147),
(116, 17, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(117, 17, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4127) 0:00:41.27', 0, 0, 701147),
(118, 17, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(119, 17, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:91', 0, 0, 701147),
(120, 17, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(121, 17, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(122, 18, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/18', 0, 0, 701147),
(123, 18, 'réseaux > port > nombre d''erreurs entrées', '2009-03-11 11:47:43', '0', 0, 0, '2', 0, 0, 701147),
(124, 18, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(125, 18, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(397341965) 45 days, 23:43:39.65', 0, 0, 701147),
(126, 18, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(127, 18, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:92', 0, 0, 701147),
(128, 18, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(129, 18, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(130, 19, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/19', 0, 0, 701147),
(131, 19, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(132, 19, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4127) 0:00:41.27', 0, 0, 701147),
(133, 19, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(134, 19, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:93', 0, 0, 701147),
(135, 19, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(136, 19, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(137, 20, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/20', 0, 0, 701147),
(138, 20, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(139, 20, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(371459738) 42 days, 23:49:57.38', 0, 0, 701147),
(140, 20, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(141, 20, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:94', 0, 0, 701147),
(142, 20, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(143, 20, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(144, 21, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/21', 0, 0, 701147),
(145, 21, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(146, 21, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(491995038) 56 days, 22:39:10.38', 0, 0, 701147),
(147, 21, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(148, 21, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:95', 0, 0, 701147),
(149, 21, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(150, 21, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(151, 22, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/22', 0, 0, 701147),
(152, 22, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(153, 22, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(863642228) 99 days, 23:00:22.28', 0, 0, 701147),
(154, 22, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(155, 22, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:96', 0, 0, 701147),
(156, 22, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '100000000', 0, 0, 701147),
(157, 22, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(158, 23, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/23', 0, 0, 701147),
(159, 23, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(160, 23, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4128) 0:00:41.28', 0, 0, 701147),
(161, 23, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(162, 23, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:97', 0, 0, 701147),
(163, 23, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(164, 23, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(165, 24, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'FastEthernet0/24', 0, 0, 701147),
(166, 24, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(167, 24, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4128) 0:00:41.28', 0, 0, 701147),
(168, 24, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(169, 24, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:98', 0, 0, 701147),
(170, 24, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(171, 24, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(172, 25, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'GigabitEthernet0/1', 0, 0, 701147),
(173, 25, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(174, 25, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4128) 0:00:41.28', 0, 0, 701147),
(175, 25, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(176, 25, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:99', 0, 0, 701147),
(177, 25, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(178, 25, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(179, 26, 'réseaux > port > description du port', '2009-03-11 11:47:43', '', 0, 0, 'GigabitEthernet0/2', 0, 0, 701147),
(180, 26, 'réseaux > port > statut interne', '2009-03-11 11:47:43', '', 0, 0, 'up(1)', 0, 0, 701147),
(181, 26, 'réseaux > port > Dernier changement', '2009-03-11 11:47:43', '', 0, 0, '(4128) 0:00:41.28', 0, 0, 701147),
(182, 26, 'réseaux > port > mtu', '2009-03-11 11:47:43', '0', 0, 0, '1500', 0, 0, 701147),
(183, 26, 'réseaux > port > adresse MAC', '2009-03-11 11:47:43', '', 0, 0, '00:1a:6c:9a:fc:9a', 0, 0, 701147),
(184, 26, 'réseaux > port > vitesse', '2009-03-11 11:47:43', '0', 0, 0, '10000000', 0, 0, 701147),
(185, 26, 'réseaux > port > statut de la connexion', '2009-03-11 11:47:43', '', 0, 0, 'down(2)', 0, 0, 701147),
(186, 1, 'trunk', '2009-03-11 11:47:43', '0', 0, 0, '1', 0, 0, 701147),
(187, 28, '0', '2009-03-11 11:50:39', NULL, 0, 0, '00:1a:6c:9a:fc:84', 2, 1, 701150),
(188, 4, '0', '2009-03-11 11:50:39', NULL, 0, 0, '00:1B:21:1A:9F:BB', 1, 2, 701150),
(189, 31, '0', '2009-03-11 11:50:40', NULL, 0, 0, '00:1a:6c:9a:fc:96', 2, 1, 701150),
(190, 22, '0', '2009-03-11 11:50:40', NULL, 0, 0, '00:1d:72:17:08:f4', 1, 3, 701150),
(191, 30, '0', '2009-03-11 11:50:40', NULL, 0, 0, '00:1a:6c:9a:fc:90', 2, 1, 701150),
(192, 16, '0', '2009-03-11 11:50:40', NULL, 0, 0, '00:30:64:01:67:F4', 1, 2, 701150),
(193, 6, 'réseaux > port > Dernier changement', '2009-03-11 11:53:40', '(321480424) 37 days, 5:00:04.24', 0, 0, '(864692968) 100 days, 1:55:29.68', 0, 0, 701153),
(194, 6, 'réseaux > port > statut de la connexion', '2009-03-11 11:53:40', 'down(2)', 0, 0, 'up(1)', 0, 0, 701153),
(195, 8, 'réseaux > port > Dernier changement', '2009-03-11 11:53:40', '(813544117) 94 days, 3:50:41.17', 0, 0, '(864692612) 100 days, 1:55:26.12', 0, 0, 701153),
(196, 8, 'réseaux > port > statut de la connexion', '2009-03-11 11:53:40', 'up(1)', 0, 0, 'down(2)', 0, 0, 701153),
(197, 10, 'réseaux > port > Dernier changement', '2009-03-11 11:59:52', '(806341622) 93 days, 7:50:16.22', 0, 0, '(864727977) 100 days, 2:01:19.77', 0, 0, 701159),
(198, 10, 'réseaux > port > statut de la connexion', '2009-03-11 11:59:52', 'up(1)', 0, 0, 'down(2)', 0, 0, 701159),
(199, 16, 'réseaux > port > Dernier changement', '2009-03-11 11:59:52', '(380059360) 43 days, 23:43:13.60', 0, 0, '(864730044) 100 days, 2:01:40.44', 0, 0, 701159),
(200, 18, 'réseaux > port > Dernier changement', '2009-03-11 11:59:52', '(397341965) 45 days, 23:43:39.65', 0, 0, '(864728346) 100 days, 2:01:23.46', 0, 0, 701159),
(201, 18, 'réseaux > port > statut de la connexion', '2009-03-11 11:59:52', 'down(2)', 0, 0, 'up(1)', 0, 0, 701159),
(202, 1, 'réseaux > port > nombre d''erreurs entrées', '2009-03-11 13:17:39', '67349', 0, 0, '67353', 0, 0, 701317),
(203, 6, 'réseaux > port > Dernier changement', '2009-03-11 13:27:24', '(864692968) 100 days, 1:55:29.68', 0, 0, '(865253694) 100 days, 3:28:56.94', 0, 0, 701327),
(204, 6, 'réseaux > port > statut de la connexion', '2009-03-11 13:27:24', 'up(1)', 0, 0, 'down(2)', 0, 0, 701327),
(205, 8, 'réseaux > port > Dernier changement', '2009-03-11 13:27:24', '(864692612) 100 days, 1:55:26.12', 0, 0, '(865254054) 100 days, 3:29:00.54', 0, 0, 701327),
(206, 8, 'réseaux > port > statut de la connexion', '2009-03-11 13:27:24', 'down(2)', 0, 0, 'up(1)', 0, 0, 701327),
(207, 1, 'réseaux > port > nombre d''erreurs entrées', '2009-03-11 13:37:44', '67353', 0, 0, '67354', 0, 0, 701337),
(208, 4, 'réseaux > port > Dernier changement', '2009-03-11 13:37:44', '(858579986) 99 days, 8:56:39.86', 0, 0, '(865314205) 100 days, 3:39:02.05', 0, 0, 701337),
(209, 8, 'réseaux > port > Dernier changement', '2009-03-11 13:37:44', '(865254054) 100 days, 3:29:00.54', 0, 0, '(865314084) 100 days, 3:39:00.84', 0, 0, 701337),
(210, 4, '0', '2009-03-11 13:37:45', '00:1B:21:1A:9F:BB', 1, 2, NULL, 0, 0, 701337),
(211, 28, '0', '2009-03-11 13:37:45', '00:1a:6c:9a:fc:84', 2, 1, NULL, 0, 0, 701337),
(212, 28, '0', '2009-03-11 13:37:45', NULL, 0, 0, '00:1a:6c:9a:fc:88', 2, 1, 701337),
(213, 8, '0', '2009-03-11 13:37:45', NULL, 0, 0, '00:1B:21:1A:9F:BB', 1, 2, 701337),
(214, 4, 'réseaux > port > Dernier changement', '2009-03-11 13:42:29', '(865314205) 100 days, 3:39:02.05', 0, 0, '(865345527) 100 days, 3:44:15.27', 0, 0, 701342),
(215, 8, 'réseaux > port > Dernier changement', '2009-03-11 13:42:29', '(865314084) 100 days, 3:39:00.84', 0, 0, '(865345171) 100 days, 3:44:11.71', 0, 0, 701342),
(216, 8, 'réseaux > port > statut de la connexion', '2009-03-11 13:42:29', 'up(1)', 0, 0, 'down(2)', 0, 0, 701342),
(217, 8, '0', '2009-03-11 13:42:30', '00:1B:21:1A:9F:BB', 1, 2, NULL, 0, 0, 701342),
(218, 28, '0', '2009-03-11 13:42:30', '00:1a:6c:9a:fc:88', 2, 1, NULL, 0, 0, 701342),
(219, 28, '0', '2009-03-11 13:42:30', NULL, 0, 0, '00:1a:6c:9a:fc:84', 2, 1, 701342),
(220, 4, '0', '2009-03-11 13:42:30', NULL, 0, 0, '00:1B:21:1A:9F:BB', 1, 2, 701342),
(221, 32, '0', '2009-03-11 13:43:37', NULL, 0, 0, '00:1a:6c:9a:fc:90', 2, 1, 701343),
(222, 16, '0', '2009-03-11 13:43:37', NULL, 0, 0, '00:30:64:01:67:f4', 1, 4, 701343),
(223, 1, 'réseaux > port > nombre d''erreurs entrées', '2009-03-11 13:44:50', '67354', 0, 0, '67355', 0, 0, 701344),
(224, 4, 'réseaux > port > Dernier changement', '2009-03-11 13:44:50', '(865345527) 100 days, 3:44:15.27', 0, 0, '(865359696) 100 days, 3:46:36.96', 0, 0, 701344),
(225, 4, 'réseaux > port > Dernier changement', '2009-03-11 13:48:04', '(865359696) 100 days, 3:46:36.96', 0, 0, '(865381617) 100 days, 3:50:16.17', 0, 0, 701348),
(226, 4, 'réseaux > port > statut de la connexion', '2009-03-11 13:48:04', 'up(1)', 0, 0, 'down(2)', 0, 0, 701348),
(227, 6, 'réseaux > port > Dernier changement', '2009-03-11 13:48:04', '(865253694) 100 days, 3:28:56.94', 0, 0, '(865381977) 100 days, 3:50:19.77', 0, 0, 701348),
(228, 6, 'réseaux > port > statut de la connexion', '2009-03-11 13:48:04', 'down(2)', 0, 0, 'up(1)', 0, 0, 701348),
(229, 1, 'réseaux > port > nombre d''erreurs entrées', '2009-03-11 13:54:25', '67355', 0, 0, '67356', 0, 0, 701354),
(230, 1, 'réseaux > port > nombre d''erreurs entrées', '2009-03-11 13:56:18', '67356', 0, 0, '67357', 0, 0, 701356),
(231, 4, 'réseaux > port > Dernier changement', '2009-03-11 13:56:18', '(865381617) 100 days, 3:50:16.17', 0, 0, '(865423980) 100 days, 3:57:19.80', 0, 0, 701356),
(232, 4, 'réseaux > port > statut de la connexion', '2009-03-11 13:56:18', 'down(2)', 0, 0, 'up(1)', 0, 0, 701356),
(233, 6, 'réseaux > port > Dernier changement', '2009-03-11 13:56:18', '(865381977) 100 days, 3:50:19.77', 0, 0, '(865423625) 100 days, 3:57:16.25', 0, 0, 701356),
(234, 6, 'réseaux > port > statut de la connexion', '2009-03-11 13:56:18', 'up(1)', 0, 0, 'down(2)', 0, 0, 701356),
(235, 4, 'réseaux > port > Dernier changement', '2009-03-11 14:06:13', '(865423980) 100 days, 3:57:19.80', 0, 0, '(865477754) 100 days, 4:06:17.54', 0, 0, 701406),
(236, 4, 'réseaux > port > statut de la connexion', '2009-03-11 14:06:13', 'up(1)', 0, 0, 'down(2)', 0, 0, 701406),
(237, 8, 'réseaux > port > Dernier changement', '2009-03-11 14:06:13', '(865345171) 100 days, 3:44:11.71', 0, 0, '(865478105) 100 days, 4:06:21.05', 0, 0, 701406),
(238, 8, 'réseaux > port > statut de la connexion', '2009-03-11 14:06:13', 'down(2)', 0, 0, 'up(1)', 0, 0, 701406),
(239, 8, 'réseaux > port > Dernier changement', '2009-03-11 14:07:10', '(865478105) 100 days, 4:06:21.05', 0, 0, '(865494001) 100 days, 4:09:00.01', 0, 0, 701407),
(240, 28, '0', '2009-03-11 14:07:11', NULL, 0, 0, '00:1a:6c:9a:fc:88', 2, 1, 701407),
(241, 8, '0', '2009-03-11 14:07:11', NULL, 0, 0, '00:1B:21:1A:9F:BB', 1, 2, 701407),
(242, 8, 'réseaux > port > Dernier changement', '2009-03-11 14:08:11', '(865494001) 100 days, 4:09:00.01', 0, 0, '(865500290) 100 days, 4:10:02.90', 0, 0, 701408),
(243, 1, 'réseaux > port > nombre d''erreurs entrées', '2009-03-12 10:22:40', '67357', 0, 0, '67406', 0, 0, 711022),
(244, 4, 'réseaux > port > Dernier changement', '2009-03-12 10:22:40', '(865477754) 100 days, 4:06:17.54', 0, 0, '(865574937) 100 days, 4:22:29.37', 0, 0, 711022),
(245, 4, 'réseaux > port > statut de la connexion', '2009-03-12 10:22:40', 'down(2)', 0, 0, 'up(1)', 0, 0, 711022),
(246, 22, 'réseaux > port > Dernier changement', '2009-03-12 10:22:40', '(863642228) 99 days, 23:00:22.28', 0, 0, '(872305607) 100 days, 23:04:16.07', 0, 0, 711022),
(247, 28, '0', '2009-03-12 10:22:41', NULL, 0, 0, '00:1a:6c:9a:fc:84', 2, 1, 711022),
(248, 4, '0', '2009-03-12 10:22:41', NULL, 0, 0, '00:1B:21:1A:9F:BB', 1, 2, 711022),
(249, 1, 'réseaux > port > nombre d''erreurs entrées', '2009-03-12 14:00:18', '67406', 0, 0, '67412', 0, 0, 711400),
(250, 4, 'réseaux > port > Dernier changement', '2009-03-12 14:00:18', '(865574937) 100 days, 4:22:29.37', 0, 0, '(873303808) 101 days, 1:50:38.08', 0, 0, 711400),
(251, 12, 'réseaux > port > Dernier changement', '2009-03-12 14:00:18', '(491857378) 56 days, 22:16:13.78', 0, 0, '(873393310) 101 days, 2:05:33.10', 0, 0, 711400),
(252, 1, 'réseaux > port > nombre d''erreurs entrées', '2009-03-17 09:35:08', '67412', 0, 0, '67432', 0, 0, 760935),
(253, 4, 'réseaux > port > Dernier changement', '2009-03-17 09:35:08', '(873303808) 101 days, 1:50:38.08', 0, 0, '(915533234) 105 days, 23:08:52.34', 0, 0, 760935),
(254, 12, 'réseaux > port > Dernier changement', '2009-03-17 09:35:08', '(873393310) 101 days, 2:05:33.10', 0, 0, '(881883891) 102 days, 1:40:38.91', 0, 0, 760935),
(255, 18, 'réseaux > port > Dernier changement', '2009-03-17 09:35:08', '(864728346) 100 days, 2:01:23.46', 0, 0, '(889706824) 102 days, 23:24:28.24', 0, 0, 760935),
(256, 22, 'réseaux > port > Dernier changement', '2009-03-17 09:35:08', '(872305607) 100 days, 23:04:16.07', 0, 0, '(914984455) 105 days, 21:37:24.55', 0, 0, 760935);



INSERT INTO `glpi_plugin_tracker_unknown_mac` (`ID`, `start_FK_processes`, `end_FK_processes`, `start_time`, `end_time`, `port`, `unknow_mac`) VALUES
(1, 701344, 701347, '2009-03-11 13:44:50', '2009-03-11 13:47:19', 4, '00:1b:21:1b:f6:ff'),
(2, 701354, 701354, '2009-03-11 13:54:26', '2009-03-11 13:54:26', 6, '00:1b:21:1b:f6:ff'),
(3, 701356, 701402, '2009-03-11 13:56:18', '2009-03-11 14:02:21', 4, '00:1b:21:1b:f6:ff'),
(4, 701406, 760935, '2009-03-11 14:06:14', '2009-03-17 09:35:09', 8, '00:1b:21:1b:f6:ff');
