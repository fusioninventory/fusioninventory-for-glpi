
DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_configlogfields`;

CREATE TABLE `glpi_plugin_fusinvsnmp_configlogfields` (
   `id` INT( 8 ) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `days` int(255) NOT NULL DEFAULT '-1',
   PRIMARY KEY ( `id` ),
   KEY `plugin_fusioninventory_mappings_id` ( `plugin_fusioninventory_mappings_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_agentconfigs`;

CREATE TABLE `glpi_plugin_fusinvsnmp_agentconfigs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
   `version_netdiscovery` TEXT COMMENT 'array(xmltag=>value)',
   `version_snmpquery` TEXT COMMENT 'array(xmltag=>value)',
   `threads_netdiscovery` int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)',
   `threads_snmpquery` int(4) DEFAULT '1' COMMENT 'array(xmltag=>value)',
   `senddico` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusioninventory_agents_id` (`plugin_fusioninventory_agents_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_fusinvsnmp_statediscoveries`;

CREATE TABLE `glpi_plugin_fusinvsnmp_statediscoveries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_fusioninventory_taskjob_id` int(11) NOT NULL DEFAULT '0',
  `plugin_fusioninventory_agents_id` int(11) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_mod` datetime DEFAULT NULL,
  `threads` int(11) NOT NULL DEFAULT '0',
  `nb_ip` int(11) NOT NULL DEFAULT '0',
  `nb_found` int(11) NOT NULL DEFAULT '0',
  `nb_error` int(11) NOT NULL DEFAULT '0',
  `nb_exists` int(11) NOT NULL DEFAULT '0',
  `nb_import` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



## glpi_displaypreferences
INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`)
   VALUES (NULL,'PluginFusinvsnmpNetworkEquipment', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '4', '3', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '5', '4', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '6', '5', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '7', '6', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '8', '7', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '9', '8', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '10', '9', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '11', '10', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '14', '11', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '12', '12', '0'),
          (NULL,'PluginFusinvsnmpNetworkEquipment', '13', '13', '0'),

          (NULL,'PluginFusinvsnmpAgent', '8', '1', '0'),
          (NULL,'PluginFusinvsnmpAgent', '9', '2', '0'),
          (NULL,'PluginFusinvsnmpAgent', '10', '3', '0'),
          (NULL,'PluginFusinvsnmpAgent', '11', '4', '0'),
          (NULL,'PluginFusinvsnmpAgent', '12', '5', '0'),
          (NULL,'PluginFusinvsnmpAgent', '13', '6', '0'),
          (NULL,'PluginFusinvsnmpAgent', '14', '7', '0'),

          (NULL,'PluginFusinvsnmpAgentProcess', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '4', '3', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '5', '4', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '6', '5', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '7', '6', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '8', '7', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '9', '8', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '10', '9', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '11', '10', '0'),
          (NULL,'PluginFusinvsnmpAgentProcess', '12', '11', '0'),

          (NULL,'PluginFusinvsnmpNetworkPortLog', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '3', '2', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '4', '3', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '5', '4', '0'),
          (NULL,'PluginFusinvsnmpNetworkPortLog', '6', '5', '0'),

          (NULL,'PluginFusinvsnmpStateDiscovery', '2', '1', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '4', '2', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '5', '3', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '6', '4', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '7', '5', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '8', '6', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '9', '7', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '10', '8', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '11', '9', '0'),
          (NULL,'PluginFusinvsnmpStateDiscovery', '12', '10', '0');
