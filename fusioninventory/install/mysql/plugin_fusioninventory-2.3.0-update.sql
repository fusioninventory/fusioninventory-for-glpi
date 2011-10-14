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
  KEY `rescheduled_taskjob_id` (`rescheduled_taskjob_id`),
  KEY `method` (`method`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_taskjoblogs`;

CREATE TABLE `glpi_plugin_fusioninventory_taskjoblogs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



## INSERT
## glpi_displaypreferences
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`)
   VALUES (NULL, 'PluginFusioninventoryTask', '2', '1', '0'),
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
