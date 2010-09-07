DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_packages`;

CREATE TABLE `glpi_plugin_fusinvdeploy_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `comment` text,
  `date_mod` datetime DEFAULT NULL,
  `action` int(1) NOT NULL DEFAULT '0',
  `commandline` text,
  `filename` varchar(255) DEFAULT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  `sha1sum` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fragments` int(11) NOT NULL DEFAULT '0',
  `modulename` varchar(255) DEFAULT NULL,
  `operatingsystems_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_packages_dependencies`;

CREATE TABLE `glpi_plugin_fusinvdeploy_packages_dependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusinvdeploy_packages_id_1` int(11) NOT NULL DEFAULT '0',
  `plugin_fusinvdeploy_packages_id_2` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_files`;

CREATE TABLE `glpi_plugin_fusinvdeploy_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  `sha1sum` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `operatingsystems_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_packages_files`;

CREATE TABLE `glpi_plugin_fusinvdeploy_packages_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusinvdeploy_packages_id` int(11) NOT NULL DEFAULT '0',
  `plugin_fusinvdeploy_files_id` int(11) NOT NULL DEFAULT '0',
  `packagepath` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
