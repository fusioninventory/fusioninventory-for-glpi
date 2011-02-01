## obsolete tables
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol`;
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol`;
DROP TABLE IF EXISTS `glpi_dropdown_plugin_fusioninventory_snmp_version`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents_errors`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents_inventory_state`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agentprocesses`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_computers`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_modules`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_snmp_networking`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_connection_history`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_connection_stats`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_discovery`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_errors`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknown_mac`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_walks`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lockable`;

## renamed tables
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_modules`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lock`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_task`;
#DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknown_device`;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agents`;

CREATE TABLE `glpi_plugin_fusioninventory_agents` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `last_contact` datetime DEFAULT NULL,
   `version` varchar(255) DEFAULT NULL,
   `lock` int(1) NOT NULL DEFAULT '0',
   `device_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'XML <DEVICE_ID> TAG VALUE',
   `items_id` int(11) NOT NULL DEFAULT '0',
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `token` varchar(255) COLLATE utf8_unicode_ci NULL,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `device_id` (`device_id`),
   KEY `item` (`itemtype`,`items_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_configs`;

CREATE TABLE `glpi_plugin_fusioninventory_configs` (
   `id` int(1) NOT NULL AUTO_INCREMENT,
   `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`type`, `plugins_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_locks`;

CREATE TABLE `glpi_plugin_fusioninventory_locks` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   `tablename` VARCHAR( 64 ) COLLATE utf8_unicode_ci NOT NULL,
   `items_id` INT( 11 ) NOT NULL,
   `tablefields` TEXT,
   PRIMARY KEY ( `id` ),
   KEY `tablename` ( `tablename` ),
   KEY `items_id` (`items_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_tasks`;

CREATE TABLE `glpi_plugin_fusioninventory_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '-1',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `is_active` int(1) NOT NULL DEFAULT '0',
  `communication` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'push',
  `permanent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_scheduled` datetime DEFAULT NULL,
  `periodicity_count` int(6) NOT NULL DEFAULT '0',
  `periodicity_type` varchar(255) NULL,
  `execution_id` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `entities_id` ( `entities_id` ),
  KEY `is_active` ( `is_active` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_taskjobs`;

CREATE TABLE `glpi_plugin_fusioninventory_taskjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_tasks_id` int(11) NOT NULL DEFAULT '0',
  `entities_id` int(11) NOT NULL DEFAULT '-1',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `retry_nb` int(2) NOT NULL DEFAULT '0',
  `retry_time` int(11) NOT NULL DEFAULT '0',
  `plugins_id` int(11) NOT NULL DEFAULT '0',
  `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `definition` text COLLATE utf8_unicode_ci,
  `action` text COLLATE utf8_unicode_ci,
  `comment` text COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `rescheduled_taskjob_id` int(11) NOT NULL DEFAULT '0',
  `statuscomments` text COLLATE utf8_unicode_ci,
  `periodicity_count` int(6) NOT NULL DEFAULT '0',
  `periodicity_type` varchar(255) NULL,
  `execution_id` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_fusioninventory_tasks_id` (`plugin_fusioninventory_tasks_id`),
  KEY `entities_id` (`entities_id`),
  KEY `plugins_id` (`plugins_id`),
  KEY `users_id` (`users_id`),
  KEY `rescheduled_taskjob_id` (`rescheduled_taskjob_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_taskjoblogs`;

CREATE TABLE `glpi_plugin_fusioninventory_taskjoblogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_taskjobstatus_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `plugin_fusioninventory_taskjobstatus_id` (`plugin_fusioninventory_taskjobstatus_id`,`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_taskjobstatus`;

CREATE TABLE `glpi_plugin_fusioninventory_taskjobstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_taskjobs_id` int(11) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
  `specificity` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_fusioninventory_taskjobs_id` (`plugin_fusioninventory_taskjobs_id`),
  KEY `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`,`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_profiles`;

CREATE TABLE `glpi_plugin_fusioninventory_profiles` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `right` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   `profiles_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`type`, `plugins_id`, `profiles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_mappings`;

CREATE TABLE `glpi_plugin_fusioninventory_mappings` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `tablefield` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `locale` INT( 4 ) NOT NULL,
   `shortlocale` INT( 4 ) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `itemtype` (`itemtype`),
   KEY `table` (`table`),
   KEY `tablefield` (`tablefield`)
--   UNIQUE KEY `unicity` (`name`, `itemtype`) -- Specified key was too long; max key length is 1000 bytes
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknowndevices`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_unknowndevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `date_mod` datetime DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `location` int(11) NOT NULL DEFAULT '0',
   `is_deleted` smallint(6) NOT NULL DEFAULT '0',
   `serial` VARCHAR( 255 ) NULL DEFAULT NULL,
   `otherserial` VARCHAR( 255 ) NULL DEFAULT NULL,
   `contact` VARCHAR( 255 ) NULL DEFAULT NULL,
   `domain` INT( 11 ) NOT NULL DEFAULT '0',
   `comment` TEXT NULL DEFAULT NULL,
   `itemtype` VARCHAR( 255 ) NULL DEFAULT NULL,
   `accepted` INT( 1 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
   `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `hub` int(1) NOT NULL DEFAULT '0',
   `states_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `entities_id` (`entities_id`),
   KEY `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`),
   KEY `is_deleted` (`is_deleted`),
   KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agentmodules`;

CREATE TABLE `glpi_plugin_fusioninventory_agentmodules` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   `modulename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `is_active` int(1) NOT NULL DEFAULT '0',
   `exceptions` TEXT COMMENT 'array(agent_id)',
   `entities_id` int(11) NOT NULL DEFAULT '-1',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`plugins_id`, `modulename`),
   KEY `is_active` (`is_active`),
   KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



## INSERT
## glpi_displaypreferences
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) 
   VALUES (NULL,'PluginFusioninventoryAgent', '2', '1', '0'),
          (NULL,'PluginFusioninventoryAgent', '3', '2', '0'),
          (NULL,'PluginFusioninventoryAgent', '4', '3', '0'),
          (NULL,'PluginFusioninventoryAgent', '5', '4', '0'),
          (NULL,'PluginFusioninventoryAgent', '6', '5', '0'),
          (NULL,'PluginFusioninventoryAgent', '7', '6', '0'),
          (NULL,'PluginFusioninventoryAgent', '0', '7', '0'),

          (NULL, 'PluginFusioninventoryUnknownDevice', '2', '1', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '4', '2', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '3', '3', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '5', '4', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '7', '5', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '10', '6', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '11', '7', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '18', '8', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '14', '9', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '15', '10', '0'),
          (NULL, 'PluginFusioninventoryUnknownDevice', '9', '11', '0'),

          (NULL, 'PluginFusioninventoryTask', '2', '1', '0'),
          (NULL, 'PluginFusioninventoryTask', '3', '2', '0'),
          (NULL, 'PluginFusioninventoryTask', '4', '3', '0'),
          (NULL, 'PluginFusioninventoryTask', '5', '4', '0'),
          (NULL, 'PluginFusioninventoryTask', '6', '5', '0'),
          (NULL, 'PluginFusioninventoryTask', '7', '6', '0'),
          (NULL, 'PluginFusioninventoryTask', '30', '7', '0'),

          (NULL,'PluginFusioninventoryTaskjob', '1', '1', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '2', '2', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '3', '3', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '4', '4', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '5', '5', '0');






## glpi_plugin_fusioninventory_mappings
INSERT INTO `glpi_plugin_fusioninventory_mappings`
      (`id`, `itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
   VALUES (NULL,'NetworkEquipment','location','glpi_networkequipments','locations_id',1,NULL),
          (NULL,'NetworkEquipment','firmware','glpi_networkequipments',
             'networkequipmentfirmwares_id',2,NULL),
          (NULL,'NetworkEquipment','firmware1','','',2,NULL),
          (NULL,'NetworkEquipment','firmware2','','',2,NULL),
          (NULL,'NetworkEquipment','contact','glpi_networkequipments','contact',403,NULL),
          (NULL,'NetworkEquipment','comments','glpi_networkequipments','comment',404,NULL),
          (NULL,'NetworkEquipment','uptime','glpi_plugin_fusinvsnmp_networkequipments',
             'uptime',3,NULL),
          (NULL,'NetworkEquipment','cpu','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',12,NULL),
          (NULL,'NetworkEquipment','cpuuser','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',401,NULL),
          (NULL,'NetworkEquipment','cpusystem','glpi_plugin_fusinvsnmp_networkequipments',
             'cpu',402,NULL),
          (NULL,'NetworkEquipment','serial','glpi_networkequipments','serial',13,NULL),
          (NULL,'NetworkEquipment','otherserial','glpi_networkequipments','otherserial',419,NULL),
          (NULL,'NetworkEquipment','name','glpi_networkequipments','name',20,NULL),
          (NULL,'NetworkEquipment','ram','glpi_networkequipments','ram',21,NULL),
          (NULL,'NetworkEquipment','memory','glpi_plugin_fusinvsnmp_networkequipments',
             'memory',22,NULL),
          (NULL,'NetworkEquipment','vtpVlanName','','',19,NULL),
          (NULL,'NetworkEquipment','vmvlan','','',430,NULL),
          (NULL,'NetworkEquipment','entPhysicalModelName','glpi_networkequipments',
             'networkequipmentmodels_id',17,NULL),
          (NULL,'NetworkEquipment','macaddr','glpi_networkequipments','mac',417,NULL),
          (NULL,'NetworkEquipment','ipAdEntAddr','glpi_networkequipments','ip',421,NULL),
## Network CDP (Walk)
          (NULL,'NetworkEquipment','cdpCacheAddress','','',409,NULL),
          (NULL,'NetworkEquipment','cdpCacheDevicePort','','',410,NULL),
          (NULL,'NetworkEquipment','vlanTrunkPortDynamicStatus','','',411,NULL),
          (NULL,'NetworkEquipment','dot1dTpFdbAddress','','',412,NULL),
          (NULL,'NetworkEquipment','ipNetToMediaPhysAddress','','',413,NULL),
          (NULL,'NetworkEquipment','dot1dTpFdbPort','','',414,NULL),
          (NULL,'NetworkEquipment','dot1dBasePortIfIndex','','',415,NULL),
          (NULL,'NetworkEquipment','PortVlanIndex','','',422,NULL),
## NetworkPorts
          (NULL,'NetworkEquipment','ifIndex','','',408,NULL),
          (NULL,'NetworkEquipment','ifmtu','glpi_plugin_fusinvsnmp_networkports',
             'ifmtu',4,NULL),
          (NULL,'NetworkEquipment','ifspeed','glpi_plugin_fusinvsnmp_networkports',
             'ifspeed',5,NULL),
          (NULL,'NetworkEquipment','ifinternalstatus','glpi_plugin_fusinvsnmp_networkports',
             'ifinternalstatus',6,NULL),
          (NULL,'NetworkEquipment','iflastchange','glpi_plugin_fusinvsnmp_networkports',
             'iflastchange',7,NULL),
          (NULL,'NetworkEquipment','ifinoctets','glpi_plugin_fusinvsnmp_networkports',
             'ifinoctets',8,NULL),
          (NULL,'NetworkEquipment','ifoutoctets','glpi_plugin_fusinvsnmp_networkports',
             'ifoutoctets',9,NULL),
          (NULL,'NetworkEquipment','ifinerrors','glpi_plugin_fusinvsnmp_networkports',
             'ifinerrors',10,NULL),
          (NULL,'NetworkEquipment','ifouterrors','glpi_plugin_fusinvsnmp_networkports',
             'ifouterrors',11,NULL),
          (NULL,'NetworkEquipment','ifstatus','glpi_plugin_fusinvsnmp_networkports',
             'ifstatus',14,NULL),
          (NULL,'NetworkEquipment','ifPhysAddress','glpi_networkports','mac',15,NULL),
          (NULL,'NetworkEquipment','ifName','glpi_networkports','name',16,NULL),
          (NULL,'NetworkEquipment','ifName','glpi_networkports','name',16,NULL),
          (NULL,'NetworkEquipment','ifType','','',18,NULL),
          (NULL,'NetworkEquipment','ifdescr','glpi_plugin_fusinvsnmp_networkports',
             'ifdescr',23,NULL),
          (NULL,'NetworkEquipment','portDuplex','glpi_plugin_fusinvsnmp_networkports',
             'portduplex',33,NULL),
## Printers
          (NULL,'Printer','model','glpi_printers','printermodels_id',25,NULL),
          (NULL,'Printer','enterprise','glpi_printers','manufacturers_id',420,NULL),
          (NULL,'Printer','contact','glpi_printers','contact',405,NULL),
          (NULL,'Printer','comments','glpi_printers','comment',406,NULL),
          (NULL,'Printer','otherserial','glpi_printers','otherserial',418,NULL),
          (NULL,'Printer','memory','glpi_printers','memory_size',26,NULL),
          (NULL,'Printer','location','glpi_printers','locations_id',56,NULL),
          (NULL,'Printer','informations','','',165,165),
          (NULL,'Printer','name','glpi_printers','name',24,116),
          (NULL,'Printer','serial','glpi_printers','serial',27,NULL),
## Cartridges
          (NULL,'Printer','tonerblack','','',157,157),
          (NULL,'Printer','tonerblack2','','',166,166),
          (NULL,'Printer','tonercyan','','',158,158),
          (NULL,'Printer','tonermagenta','','',159,159),
          (NULL,'Printer','toneryellow','','',160,160),
          (NULL,'Printer','wastetoner','','',151,151),
          (NULL,'Printer','cartridgeblack','','',134,134),
          (NULL,'Printer','cartridgeblackphoto','','',135,135),
          (NULL,'Printer','cartridgecyan','','',136,136),
          (NULL,'Printer','cartridgecyanlight','','',139,139),
          (NULL,'Printer','cartridgemagenta','','',138,138),
          (NULL,'Printer','cartridgemagentalight','','',140,140),
          (NULL,'Printer','cartridgeyellow','','',137,137),
          (NULL,'Printer','maintenancekit','','',156,156),
          (NULL,'Printer','drumblack','','',161,161),
          (NULL,'Printer','drumcyan','','',162,162),
          (NULL,'Printer','drummagenta','','',163,163),
          (NULL,'Printer','drumyellow','','',164,164),
## Printers : Counter pages
          (NULL,'Printer','pagecountertotalpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total',28,128),
          (NULL,'Printer','pagecounterblackpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b',29,129),
          (NULL,'Printer','pagecountercolorpages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color',30,130),
          (NULL,'Printer','pagecounterrectoversopages','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_recto_verso',54,154),
          (NULL,'Printer','pagecounterscannedpages','glpi_plugin_fusinvsnmp_printerlogs',
             'scanned',55,155),
          (NULL,'Printer','pagecountertotalpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_print',423,1423),
          (NULL,'Printer','pagecounterblackpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b_print',424,1424),
          (NULL,'Printer','pagecountercolorpages_print','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color_print',425,1425),
          (NULL,'Printer','pagecountertotalpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_copy',426,1426),
          (NULL,'Printer','pagecounterblackpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_n_b_copy',427,1427),
          (NULL,'Printer','pagecountercolorpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_color_copy',428,1428),
          (NULL,'Printer','pagecountertotalpages_fax','glpi_plugin_fusinvsnmp_printerlogs',
             'pages_total_fax',429,1429),
## Printers : NetworkPort
          (NULL,'Printer','ifPhysAddress','glpi_networkports','mac',58,NULL),
          (NULL,'Printer','ifName','glpi_networkports','name',57,NULL),
          (NULL,'Printer','ifaddr','glpi_networkports','ip',407,NULL),
          (NULL,'Printer','ifType','','',97,NULL),
          (NULL,'Printer','ifIndex','','',416,NULL),
## Computer
          (NULL,'Computer','serial','','serial',13,NULL),
          (NULL,'Computer','ifPhysAddress','','mac',15,NULL);
