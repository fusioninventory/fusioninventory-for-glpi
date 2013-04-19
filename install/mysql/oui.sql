DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_ouis`;

CREATE TABLE `glpi_plugin_fusioninventory_ouis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mac` (`mac`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_fusioninventory_ouis`
      (`id`, `mac`, `name`) VALUES ;