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
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` tinyint(1) NOT NULL DEFAULT '1',
   `name` varchar(255) DEFAULT NULL,
   `last_contact` datetime DEFAULT NULL,
   `version` varchar(255) DEFAULT NULL,
   `lock` tinyint(1) NOT NULL DEFAULT '0',
   `device_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'XML <DEVICE_ID> TAG VALUE',
   `items_id` int(11) NOT NULL DEFAULT '0',
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `useragent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `device_id` (`device_id`),
   KEY `item` (`itemtype`,`items_id`),
   KEY `items_id` (`items_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_configs`;

CREATE TABLE `glpi_plugin_fusioninventory_configs` (
   `id` int(1) NOT NULL AUTO_INCREMENT,
   `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   `module` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`type`, `plugins_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_locks`;

CREATE TABLE `glpi_plugin_fusioninventory_locks` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `tablename` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `items_id` int(11) NOT NULL DEFAULT '0',
   `tablefields` text DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `tablename` (`tablename`),
   KEY `items_id` (`items_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_tasks`;

CREATE TABLE `glpi_plugin_fusioninventory_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `communication` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'push',
  `permanent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_scheduled` datetime DEFAULT NULL,
  `periodicity_count` int(6) NOT NULL DEFAULT '0',
  `periodicity_type` varchar(255) DEFAULT NULL,
  `execution_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_active` (`is_active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_taskjobs`;

CREATE TABLE `glpi_plugin_fusioninventory_taskjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_tasks_id` int(11) NOT NULL DEFAULT '0',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `retry_nb` tinyint(2) NOT NULL DEFAULT '0',
  `retry_time` int(11) NOT NULL DEFAULT '0',
  `plugins_id` int(11) NOT NULL DEFAULT '0',
  `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `definition` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `action` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `rescheduled_taskjob_id` int(11) NOT NULL DEFAULT '0',
  `statuscomments` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `periodicity_count` int(6) NOT NULL DEFAULT '0',
  `periodicity_type` varchar(255) DEFAULT NULL,
  `execution_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_fusioninventory_tasks_id` (`plugin_fusioninventory_tasks_id`),
  KEY `entities_id` (`entities_id`),
  KEY `plugins_id` (`plugins_id`),
  KEY `users_id` (`users_id`),
  KEY `rescheduled_taskjob_id` (`rescheduled_taskjob_id`),
  KEY `method` (`method`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_taskjoblogs`;

CREATE TABLE `glpi_plugin_fusioninventory_taskjoblogs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_taskjobstatus_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `comment` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_fusioninventory_taskjobstatus_id` (`plugin_fusioninventory_taskjobstatus_id`,`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_taskjobstatus`;

CREATE TABLE `glpi_plugin_fusioninventory_taskjobstatus` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_taskjobs_id` int(11) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
  `specificity` varchar(255) DEFAULT NULL,
  `uniqid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_fusioninventory_taskjobs_id` (`plugin_fusioninventory_taskjobs_id`),
  KEY `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`,`state`),
  KEY `plugin_fusioninventory_taskjob_2` (`plugin_fusioninventory_taskjobs_id`,`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_profiles`;

CREATE TABLE `glpi_plugin_fusioninventory_profiles` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `right` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   `profiles_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`type`, `plugins_id`, `profiles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_mappings`;

CREATE TABLE `glpi_plugin_fusioninventory_mappings` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `tablefield` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `locale` int(4) NOT NULL DEFAULT '0',
   `shortlocale` int(4) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `itemtype` (`itemtype`),
   KEY `table` (`table`),
   KEY `tablefield` (`tablefield`)
--   UNIQUE KEY `unicity` (`name`, `itemtype`) -- Specified key was too long; max key length is 1000 bytes
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_unknowndevices`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_unknowndevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `date_mod` datetime DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `locations_id` int(11) NOT NULL DEFAULT '0',
   `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
   `serial` varchar(255) DEFAULT NULL,
   `otherserial` varchar(255) DEFAULT NULL,
   `contact` varchar(255) DEFAULT NULL,
   `domain` int(11) NOT NULL DEFAULT '0',
   `comment` text DEFAULT NULL,
   `item_type` varchar(255) DEFAULT NULL,
   `accepted` tinyint(1) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
   `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `hub` tinyint(1) NOT NULL DEFAULT '0',
   `states_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `entities_id` (`entities_id`),
   KEY `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`),
   KEY `is_deleted` (`is_deleted`),
   KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_agentmodules`;

CREATE TABLE `glpi_plugin_fusioninventory_agentmodules` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   `modulename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `is_active` int(1) NOT NULL DEFAULT '0',
   `exceptions` text DEFAULT NULL COMMENT 'array(agent_id)',
   `entities_id` int(11) NOT NULL DEFAULT '-1',
   `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`plugins_id`, `modulename`),
   KEY `is_active` (`is_active`),
   KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_ipranges`;

CREATE TABLE `glpi_plugin_fusioninventory_ipranges` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `ip_start` varchar(255) DEFAULT NULL,
   `ip_end` varchar(255) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_credentials`;

CREATE TABLE  `glpi_plugin_fusioninventory_credentials` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
   `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
   `date_mod` datetime DEFAULT NULL,
   `itemtype` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   PRIMARY KEY (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_credentialips`;

CREATE TABLE  `glpi_plugin_fusioninventory_credentialips` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `plugin_fusioninventory_credentials_id` int(11) NOT NULL DEFAULT '0',
   `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
   `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `date_mod` datetime DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_ignoredimportdevices`;

CREATE TABLE `glpi_plugin_fusioninventory_ignoredimportdevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `date` datetime DEFAULT NULL,
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `rules_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_inventorycomputercriterias`;

CREATE TABLE `glpi_plugin_fusioninventory_inventorycomputercriterias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;




DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_inventorycomputerlibserialization`;

CREATE TABLE `glpi_plugin_fusioninventory_inventorycomputerlibserialization` (
  `internal_id` varchar(255) NOT NULL DEFAULT '',
  `computers_id` int(11) DEFAULT NULL,
  `serialized_sections1` longtext DEFAULT NULL,
  `serialized_sections2` longtext DEFAULT NULL,
  `serialized_sections3` longtext DEFAULT NULL,
  `hash` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_fusioninventory_update` datetime DEFAULT NULL,
  PRIMARY KEY (`internal_id`),
  KEY `computers_id` (`computers_id`),
  KEY `last_fusioninventory_update` (`last_fusioninventory_update`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_inventorycomputerblacklists`;

CREATE TABLE `glpi_plugin_fusioninventory_inventorycomputerblacklists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_criterium_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_fusioninventory_criterium_id` (`plugin_fusioninventory_criterium_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_inventorycomputerantivirus`;

CREATE TABLE `glpi_plugin_fusioninventory_inventorycomputerantivirus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `version` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `uptodate` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `version` (`version`),
  KEY `is_active` (`is_active`),
  KEY `uptodate` (`uptodate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_inventorycomputercomputers`;

CREATE TABLE `glpi_plugin_fusioninventory_inventorycomputercomputers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `computers_id` int(11) NOT NULL DEFAULT '0',
  `bios_date` datetime DEFAULT NULL,
  `bios_version` varchar(255) DEFAULT NULL,
  `bios_manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `operatingsystem_installationdate` datetime DEFAULT NULL,
  `winowner` varchar(255) DEFAULT NULL,
  `wincompany` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `computers_id` (`computers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



## INSERT
## glpi_displaypreferences
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) 
   VALUES (NULL,'PluginFusioninventoryAgent', '2', '1', '0'),
          (NULL,'PluginFusioninventoryAgent', '4', '2', '0'),
          (NULL,'PluginFusioninventoryAgent', '5', '3', '0'),
          (NULL,'PluginFusioninventoryAgent', '6', '4', '0'),
          (NULL,'PluginFusioninventoryAgent', '7', '5', '0'),
          (NULL,'PluginFusioninventoryAgent', '8', '6', '0'),
          (NULL,'PluginFusioninventoryAgent', '9', '7', '0'),

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

          (NULL,'PluginFusioninventoryIPRange', '2', '1', '0'),
          (NULL,'PluginFusioninventoryIPRange', '3', '2', '0'),
          (NULL,'PluginFusioninventoryIPRange', '4', '3', '0'),

          (NULL,'PluginFusioninventoryTaskjob', '1', '1', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '2', '2', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '3', '3', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '4', '4', '0'),
          (NULL,'PluginFusioninventoryTaskjob', '5', '5', '0'),
          (NULL,'PluginFusioninventoryInventoryComputerBlacklist', '2', '1', '0');


INSERT INTO `glpi_plugin_fusioninventory_inventorycomputercriterias` 
(`id`, `name`, `comment`) VALUES
(1, 'Serial number', 'ssn'),
(2, 'uuid', 'uuid'),
(3, 'Mac address', 'macAddress'),
(4, 'Windows product key', 'winProdKey'),
(5, 'Model', 'smodel'),
(6, 'storage serial', 'storagesSerial'),
(7, 'drives serial', 'drivesSerial'),
(8, 'Asset Tag', 'assetTag'),
(9, 'Computer name', 'name'),
(10, 'Manufacturer', 'manufacturer');

INSERT INTO `glpi_plugin_fusioninventory_inventorycomputerblacklists` 
(`id`, `plugin_fusioninventory_criterium_id`, `value`) VALUES
(1, 3, '50:50:54:50:30:30'),
(2, 1, 'N/A'),
(3, 1, '(null string)'),
(4, 1, 'INVALID'),
(5, 1, 'SYS-1234567890'),
(6, 1, 'SYS-9876543210'),
(7, 1, 'SN-12345'),
(8, 1, 'SN-1234567890'),
(9, 1, '1111111111'),
(10, 1, '1111111'),
(11, 1, '1'),
(12, 1, '0123456789'),
(13, 1, '12345'),
(14, 1, '123456'),
(15, 1, '1234567'),
(16, 1, '12345678'),
(17, 1, '123456789'),
(18, 1, '1234567890'),
(19, 1, '123456789000'),
(20, 1, '12345678901234567'),
(21, 1, '0000000000'),
(22, 1, '000000000'),
(23, 1, '00000000'),
(24, 1, '0000000'),
(25, 1, '0000000'),
(26, 1, 'NNNNNNN'),
(27, 1, 'xxxxxxxxxxx'),
(28, 1, 'EVAL'),
(29, 1, 'IATPASS'),
(30, 1, 'none'),
(31, 1, 'To Be Filled By O.E.M.'),
(32, 1, 'Tulip Computers'),
(33, 1, 'Serial Number xxxxxx'),
(34, 1, 'SN-123456fvgv3i0b8o5n6n7k'),
(35, 1, 'Unknow'),
(36, 5, 'Unknow'),
(37, 1, 'System Serial Number'),
(38, 5, 'To Be Filled By O.E.M.'),
(39, 5, '*'),
(40, 5, 'System Product Name'),
(41, 5, 'Product Name'),
(42, 5, 'System Name'),
(43, 2, 'FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF'),
(44, 10, 'System manufacturer'),
(45, 2, '03000200-0400-0500-0006-000700080009'),
(46, 2, '6AB5B300-538D-1014-9FB5-B0684D007B53'),
(47, 2, '01010101-0101-0101-0101-010101010101'),
(48, 3, '20:41:53:59:4e:ff'),
(49, 3, '02:00:4e:43:50:49'),
(50, 3, 'e2:e6:16:20:0a:35'),
(51, 3, 'd2:0a:2d:a0:04:be'),
(52, 3, '00:a0:c6:00:00:00'),
(53, 3, 'd2:6b:25:2f:2c:e7'),
(54, 3, '33:50:6f:45:30:30'),
(55, 3, '0a:00:27:00:00:00'),
(56, 3, '00:50:56:C0:00:01'),
(57, 3, '00:50:56:C0:00:08'),
(58, 3, '02:80:37:EC:02:00'),
(59, 1, 'MB-1234567890');
