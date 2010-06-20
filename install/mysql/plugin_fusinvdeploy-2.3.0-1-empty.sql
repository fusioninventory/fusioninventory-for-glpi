DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_packages`;

CREATE TABLE `glpi_plugin_fusinvdeploy_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `comment` text,
  `document_id` int(11) NOT NULL DEFAULT '0',
  `action` int(1) NOT NULL DEFAULT '0',
  `commandline` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
