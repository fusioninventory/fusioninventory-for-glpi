DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_criteria`;

CREATE TABLE `glpi_plugin_fusinvinventory_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_fusinvinventory_criteria` (`id`, `name`, `comment`) VALUES
(1, 'Serial number', NULL),
(2, 'uuid', NULL),
(3, 'Mac address', NULL),
(4, 'Windows product key', NULL),
(5, 'Model', NULL),
(6, 'storage serial', NULL),
(7, 'drives serial', NULL);