DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions`;

CREATE TABLE `glpi_plugin_fusinvdeploy_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusinvdeploy_orders_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `ranking` int(11) DEFAULT NULL,
  PRIMARY KEY (  `id` ),
  KEY `plugin_fusinvdeploy_orders_id` (`plugin_fusinvdeploy_orders_id`),
  KEY `itemtype` (`itemtype`),
  KEY `items_id` (`items_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_commands`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_commands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exec` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (  `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_deletes`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_deletes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_mkdirs`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_mkdirs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_moves`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_moves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_copies`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_copies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_commandenvvariables`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_commandenvvariables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `plugin_fusinvdeploy_commands_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_fusinvdeploy_commands_id` (`plugin_fusinvdeploy_commands_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_commandstatus`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_commandstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'RETURNCODE_OK, RETURNCODE_KO, REGEX_OK, REGEX_KO',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `plugin_fusinvdeploy_commands_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_fusinvdeploy_commands_id` (`plugin_fusinvdeploy_commands_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_checks`;

CREATE TABLE `glpi_plugin_fusinvdeploy_checks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT  'winkeyExists, winkeyEquals, winkeyMissing, fileExists, fileMissing, fileSize, fileSHA512, freespaceGreater',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ranking` int(11) DEFAULT NULL,
  `plugin_fusinvdeploy_orders_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_fusinvdeploy_orders_id` (`plugin_fusinvdeploy_orders_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_fileparts`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_fileparts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sha512` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `shortsha512` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `plugin_fusinvdeploy_orders_id` int(11) NOT NULL DEFAULT '0',
  `plugin_fusinvdeploy_files_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shortsha512` (`shortsha512`),
  KEY `plugin_fusinvdeploy_orders_id` (`plugin_fusinvdeploy_orders_id`),
  KEY `plugin_fusinvdeploy_files_id` (`plugin_fusinvdeploy_files_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_files`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_p2p` tinyint(1) NOT NULL DEFAULT '0',
  `mimetype` char(255) NOT NULL DEFAULT 'na',
  `create_date` datetime DEFAULT NULL,
  `p2p_retention_days` int(11) NOT NULL DEFAULT '0',
  `uncompress` tinyint(1) NOT NULL DEFAULT '0',
  `sha512` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `shortsha512` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `filesize` bigint(20) NOT NULL DEFAULT '0',
  `plugin_fusinvdeploy_orders_id` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `shortsha512` (`shortsha512`),
KEY `plugin_fusinvdeploy_orders_id` (`plugin_fusinvdeploy_orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_orders`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'INSTALL, UNINSTALL, OTHER',
  `create_date` datetime DEFAULT NULL,
  `plugin_fusinvdeploy_packages_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `create_date` (`create_date`),
  KEY `plugin_fusinvdeploy_packages_id` (`plugin_fusinvdeploy_packages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_packages`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_files_mirrors`;

CREATE TABLE `glpi_plugin_fusinvdeploy_files_mirrors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusinvdeploy_files_id` int(11) NOT NULL DEFAULT '0',
  `plugin_fusinvdeploy_mirrors_id` int(11) NOT NULL DEFAULT '0',
  `ranking` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_fusinvdeploy_files_id` (`plugin_fusinvdeploy_files_id`),
  KEY `plugin_fusinvdeploy_mirrors_id` (`plugin_fusinvdeploy_mirrors_id`)
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_mirrors`;

CREATE TABLE `glpi_plugin_fusinvdeploy_mirrors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `date_mod` (`date_mod`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_messages`;

CREATE TABLE `glpi_plugin_fusinvdeploy_actions_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT  'info, postpone',
  PRIMARY KEY (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;



DROP VIEW IF EXISTS `glpi_plugin_fusinvdeploy_tasks`;

CREATE VIEW `glpi_plugin_fusinvdeploy_tasks` 
AS SELECT * FROM glpi_plugin_fusioninventory_tasks;



DROP VIEW IF EXISTS `glpi_plugin_fusinvdeploy_taskjobs`;

CREATE VIEW `glpi_plugin_fusinvdeploy_taskjobs`
AS SELECT `id`,
`plugin_fusioninventory_tasks_id` AS `plugin_fusinvdeploy_tasks_id`,
`entities_id`, `name`, `date_creation`, `retry_nb`,
`retry_time`, `plugins_id`, `method`, `definition`,
`action`, `comment`, `users_id`, `status`,
`rescheduled_taskjob_id`, `statuscomments`,
`periodicity_count`, `periodicity_type`, `execution_id`
FROM glpi_plugin_fusioninventory_taskjobs;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_groups`;

CREATE TABLE `glpi_plugin_fusinvdeploy_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'STATIC, DYNAMIC',
  PRIMARY KEY (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_groups_staticdatas`;

CREATE TABLE `glpi_plugin_fusinvdeploy_groups_staticdatas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `items_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (  `id` ),
  KEY `groups_id` (`groups_id`),
  KEY `items_id` (`items_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_groups_dynamicdatas`;

CREATE TABLE `glpi_plugin_fusinvdeploy_groups_dynamicdatas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `fields_array` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `groups_id` (`groups_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
