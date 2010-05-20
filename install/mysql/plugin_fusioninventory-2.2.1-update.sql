CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_agents_errors` (
  `ID` int(11) NOT NULL auto_increment,
  `process_number` varchar(255) collate utf8_unicode_ci default NULL,
  `on_device` int(11) NOT NULL default '0',
  `device_type` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `agent_type` varchar(255) collate utf8_unicode_ci default NULL,
  `error_message` text collate utf8_unicode_ci,
  PRIMARY KEY  (`ID`),
  KEY `process_number` (`process_number`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_snmp_history` (
  `ID` int(11) NOT NULL auto_increment,
  `FK_ports` int(11) NOT NULL,
  `Field` varchar(255) collate utf8_unicode_ci NOT NULL default '0',
  `date_mod` datetime default NULL,
  `old_value` varchar(255) collate utf8_unicode_ci default NULL,
  `old_device_type` int(11) NOT NULL default '0',
  `old_device_ID` int(11) NOT NULL default '0',
  `new_value` varchar(255) collate utf8_unicode_ci default NULL,
  `new_device_type` int(11) NOT NULL default '0',
  `new_device_ID` int(11) NOT NULL default '0',
  `FK_process` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`ID`),
  KEY `FK_ports` (`FK_ports`,`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;