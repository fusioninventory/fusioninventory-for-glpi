<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------


// Update from 2.2.1 to 2.3.0
function update221to230() {
   global $DB, $CFG_GLPI;


   /*
    * Update `glpi_dropdown_plugin_fusioninventory_mib_label`
    * to `glpi_plugin_fusinvsnmp_miblabels`
    */
   $sql = "RENAME TABLE `glpi_dropdown_plugin_fusioninventory_mib_label`
      TO `glpi_plugin_fusinvsnmp_miblabels`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_miblabels`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_miblabels`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);

   /*
    * Update `glpi_dropdown_plugin_fusioninventory_mib_object`
    * to `glpi_plugin_fusinvsnmp_mibobjects`
    */
   $sql = "RENAME TABLE `glpi_dropdown_plugin_fusioninventory_mib_object`
      TO `glpi_plugin_fusinvsnmp_mibobjects`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_mibobjects`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_mibobjects`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);

   /*
    * Update `glpi_dropdown_plugin_fusioninventory_mib_oid`
    * to `glpi_plugin_fusinvsnmp_miboids`
    */
   $sql = "RENAME TABLE `glpi_dropdown_plugin_fusioninventory_mib_oid`
      TO `glpi_plugin_fusinvsnmp_miboids`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_miboids`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_miboids`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);

   /*
    * Drop `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol`
    */
   $sql = "DROP TABLE `glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol`";
   $DB->query($sql);

   /*
    * Drop `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol`
    */
   $sql = "DROP TABLE `glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol`";
   $DB->query($sql);

   /*
    * Drop `glpi_dropdown_plugin_fusioninventory_snmp_version`
    */
   $sql = "DROP TABLE `glpi_dropdown_plugin_fusioninventory_snmp_version`";
   $DB->query($sql);

   /*
    * Migration des agents fusion
    */
    $sql = "CREATE TABLE `glpi_plugin_fusinvsnmp_tmp_agents` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `snmpquery` int(1) NOT NULL DEFAULT '0',
   `threads_query` int(11) NOT NULL DEFAULT '1',
   `netdiscovery` int(1) NOT NULL DEFAULT '0',
   `threads_discovery` int(11) NOT NULL DEFAULT '1',
   PRIMARY KEY (`id`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($sql);

    $sql = "CREATE TABLE `glpi_plugin_fusinvinventory_tmp_agents` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `inventory` int(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($sql);

   $sql = "CREATE TABLE `glpi_plugin_fusioninventory_agentmodules` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   `modulename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `is_active` int(1) NOT NULL DEFAULT '0',
   `exceptions` TEXT COMMENT 'array(agent_id)',
   `entities_id` int(11) NOT NULL DEFAULT '-1',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`plugins_id`, `modulename`),
   KEY `is_active` (`is_active`),
   KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($sql);
   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
   $input = array();
   $input['plugins_id'] = $plugins_id;
   $input['modulename'] = "WAKEONLAN";
   $input['is_active']  = 0;
   $input['exceptions'] = exportArrayToDB(array());
   $PluginFusioninventoryAgentmodule->add($input);

   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_agents`";
   $result=$DB->query($sql);
	while ($data=$DB->fetch_array($result)) {
      if ($data['module_inventory'] == '1') {
         $sql_ins = "INSERT INTO `glpi_plugin_fusinvinventory_tmp_agents`
            VALUE('".$data['ID']."', '1')";
         $DB->query($sql_ins);
      }
        
      $sql_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_tmp_agents`
         VALUE('".$data['ID']."',
            '".$data['module_snmpquery']."',
            '".$data['threads_query']."'
            '".$data['module_netdiscovery']."',
            '".$data['threads_discovery']."')";
      $DB->query($sql_ins);

      if ($data['module_wakeonlan'] == '1') {
         $a_modules = $PluginFusioninventoryAgentmodule->find("`modulename`='WAKEONLAN'");
         $a_module = current($a_modules);
         $a_exceptions = importArrayFromDB($a_module['exceptions']);
         $a_exceptions[] = $data['ID'];
         $a_module['exceptions'] = exportArrayToDB($a_exceptions);
         $PluginFusioninventoryAgentmodule->update($a_module);
      }      
   }
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      DROP `core_discovery`,
      DROP `threads_discovery`,
      DROP `core_query`,
      DROP `threads_query`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `last_agent_update` `last_contact` DATETIME NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `fusioninventory_agent_version` `version` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `key` `device_id` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `on_device` `items_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      CHANGE `device_type` `itemtype` VARCHAR( 100 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_agents`
      DROP `module_inventory`,
      DROP `module_netdiscovery`,
      DROP `module_snmpquery`,
      DROP `module_wakeonlan`;";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_agents_inventory_state`
    */
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_agents_inventory_state`";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_connection_history`
    */
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_connection_history`";
   $DB->query($sql);

   /*
    * Get config from `glpi_plugin_fusioninventory_config`
    * and set config into `glpi_plugin_fusioninventory_configs`
    * and drop `glpi_plugin_fusioninventory_config`
    */
    $sql = "CREATE TABLE `glpi_plugin_fusinvsnmp_tmp_configs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `authsnmp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($sql);

   $sql = "CREATE TABLE `glpi_plugin_fusioninventory_configs` (
   `id` int(1) NOT NULL AUTO_INCREMENT,
   `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`type`, `plugins_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
   $DB->query($sql);

   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_config` ";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $sql_ins = "INSERT INTO `glpi_plugin_fusioninventory_configs`
            (`type`, `value`, `plugins_id`)
         VALUES('version', '2.3.0', '".$plugins_id."',
                'ssl_only', '".$data['ssl_only ']."', '".$plugins_id."',
                'inventory_frequence', '".$data['inventory_frequence  ']."', '".$plugins_id."',
                'delete_task', '".$data['delete_agent_process']."', '".$plugins_id."',
                'agent_port', '62354', '".$plugins_id."')";
      $DB->query($sql_ins);

      $sql_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_tmp_configs`
         VALUES('1', '".$data['authsnmp']."')";
      $DB->query($sql_ins);
   }
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_config`";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_config_modules`
    */
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_config_modules`";
   $DB->query($sql);

   /*
    * Modify glpi_plugin_fusioninventory_construct_device
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_construct_device`
      TO `glpi_plugin_fusinvsnmp_constructdevices`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      CHANGE `FK_glpi_enterprise` `manufacturers_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      DROP `device`,
      DROP `firmware`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      CHANGE `type` `itemtype` VARCHAR( 100 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);   
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices`
      CHANGE `snmpmodel_id` `plugin_fusinvsnmp_models_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);







   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);
   $sql = "";
   $DB->query($sql);

   
   //TODO
// Plugin::migrateItemType();


}

?>