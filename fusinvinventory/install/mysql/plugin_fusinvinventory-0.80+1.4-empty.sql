DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_criterias`;

CREATE TABLE `glpi_plugin_fusinvinventory_criterias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_libserialization`;

CREATE TABLE `glpi_plugin_fusinvinventory_libserialization` (
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



DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_blacklists`;

CREATE TABLE `glpi_plugin_fusinvinventory_blacklists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_criterium_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_fusioninventory_criterium_id` (`plugin_fusioninventory_criterium_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_antivirus`;

CREATE TABLE `glpi_plugin_fusinvinventory_antivirus` (
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



DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_computers`;

CREATE TABLE `glpi_plugin_fusinvinventory_computers` (
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



INSERT INTO `glpi_plugin_fusinvinventory_criterias` (`id`, `name`, `comment`) VALUES
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

INSERT INTO `glpi_plugin_fusinvinventory_blacklists` (`id`, `plugin_fusioninventory_criterium_id`, `value`) VALUES
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
(57, 3, '00:50:56:C0:00:02'),
(58, 3, '00:50:56:C0:00:03'),
(59, 3, '00:50:56:C0:00:04'),
(60, 3, '00:50:56:C0:00:08'),
(61, 3, 'FE:FF:FF:FF:FF:FF'),
(62, 3, '00:00:00:00:00:00'),
(63, 3, '00:0b:ca:fe:00:00'),
(64, 1, 'MB-1234567890'),
(65, 1, 'Not Specified'),
(66, 1, 'OEM_Serial'),
(67, 1, 'SystemSerialNumb'),
(68, 2, 'Not');

INSERT INTO `glpi_displaypreferences` (`itemtype`, `num`, `rank`, `users_id`) VALUES
('PluginFusinvinventoryBlacklist', 2, 1, 0);
