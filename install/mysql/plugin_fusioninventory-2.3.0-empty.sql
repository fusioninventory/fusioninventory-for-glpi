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

## renamed tables
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_config_modules`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lock`;
DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lockable`;
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



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_lockables`;

CREATE TABLE `glpi_plugin_fusioninventory_lockables` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
   `tablename` VARCHAR( 64 ) COLLATE utf8_unicode_ci NOT NULL,
   `tablefields` TEXT,
   `entities_id` int(11) NOT NULL DEFAULT '-1',
   `is_recursive` TINYINT( 1 ) NOT NULL DEFAULT '0',
   PRIMARY KEY ( `id` ),
   KEY `tablename` ( `tablename` ),
   KEY `entities_id` ( `entities_id` ),
   KEY `is_recursive` ( `is_recursive` )
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
   `type` VARCHAR( 255 ) NULL DEFAULT NULL,
   `accepted` INT( 1 ) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
   `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `hub` int(1) NOT NULL DEFAULT '0',
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
   VALUES (NULL,'PluginFusioninventoryAgent', '8', '1', '0'),
          (NULL,'PluginFusioninventoryAgent', '9', '2', '0'),
          (NULL,'PluginFusioninventoryAgent', '10', '3', '0'),
          (NULL,'PluginFusioninventoryAgent', '11', '4', '0'),
          (NULL,'PluginFusioninventoryAgent', '12', '5', '0'),
          (NULL,'PluginFusioninventoryAgent', '13', '6', '0'),
          (NULL,'PluginFusioninventoryAgent', '14', '7', '0'),

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
          (NULL, 'PluginFusioninventoryUnknownDevice', '9', '11', '0');

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`)
   VALUES (NULL,'PluginFusioninventoryTaskjob', '1', '1', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '2', '2', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '3', '3', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '4', '4', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '5', '5', '0');


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
