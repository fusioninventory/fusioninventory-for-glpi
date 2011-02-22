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

   $typetoname=array(
      GENERAL_TYPE => "",// For tickets
      COMPUTER_TYPE => "Computer",
      NETWORKING_TYPE => "NetworkEquipment",
      PRINTER_TYPE => "Printer",
      MONITOR_TYPE => "Monitor",
      PERIPHERAL_TYPE => "Peripheral",
      SOFTWARE_TYPE => "Software",
      CONTACT_TYPE => "Contact",
      ENTERPRISE_TYPE => "Supplier",
      INFOCOM_TYPE => "Infocom",
      CONTRACT_TYPE => "Contract",
      CARTRIDGEITEM_TYPE => "CartridgeItem",
      TYPEDOC_TYPE => "DocumentType",
      DOCUMENT_TYPE => "Document",
      KNOWBASE_TYPE => "KnowbaseItem",
      USER_TYPE => "User",
      TRACKING_TYPE => "Ticket",
      CONSUMABLEITEM_TYPE => "ConsumableItem",
      CONSUMABLE_TYPE => "Consumable",
      CARTRIDGE_TYPE => "Cartridge",
      SOFTWARELICENSE_TYPE => "SoftwareLicense",
      LINK_TYPE => "Link",
      STATE_TYPE => "States",
      PHONE_TYPE => "Phone",
      DEVICE_TYPE => "Device",
      REMINDER_TYPE => "Reminder",
      STAT_TYPE => "Stat",
      GROUP_TYPE => "Group",
      ENTITY_TYPE => "Entity",
      RESERVATION_TYPE => "ReservationItem",
      AUTHMAIL_TYPE => "AuthMail",
      AUTHLDAP_TYPE => "AuthLDAP",
      OCSNG_TYPE => "OcsServer",
      REGISTRY_TYPE => "RegistryKey",
      PROFILE_TYPE => "Profile",
      MAILGATE_TYPE => "MailCollector",
      RULE_TYPE => "Rule",
      TRANSFER_TYPE => "Transfer",
      BOOKMARK_TYPE => "Bookmark",
      SOFTWAREVERSION_TYPE => "SoftwareVersion",
      PLUGIN_TYPE => "Plugin",
      COMPUTERDISK_TYPE => "ComputerDisk",
      NETWORKING_PORT_TYPE => "NetworkPort",
      FOLLOWUP_TYPE => "TicketFollowup",
      BUDGET_TYPE => "Budget",
      // End is not used in 0.72.x
   );



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
    * Update `glpi_plugin_fusioninventory_construct_device`
    * to `glpi_plugin_fusinvsnmp_constructdevices`
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

   /*
    * Update `glpi_plugin_fusioninventory_construct_walks`
    * to `glpi_plugin_fusinvsnmp_constructdevicewalks`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_construct_walks`
      TO `glpi_plugin_fusinvsnmp_constructdevicewalks`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevicewalks`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevicewalks`
      CHANGE `construct_device_id` `plugin_fusinvsnmp_constructdevices_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   /*
    * Update `glpi_plugin_fusioninventory_construct_mibs`
    * to `glpi_plugin_fusinvsnmp_constructdevice_miboids`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_construct_mibs`
      TO `glpi_plugin_fusinvsnmp_constructdevice_miboids`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `mib_oid_id` `plugin_fusinvsnmp_miboids_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `construct_device_id` `plugin_fusinvsnmp_constructdevices_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);

   // ***** TODO : modify mapping_name to plugin_fusioninventory_mappings_id

   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `mapping_name` `plugin_fusioninventory_mappings_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevice_miboids`
      CHANGE `mapping_type` `itemtype` VARCHAR( 100 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_snmp_history_connections`
    * to `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_snmp_history_connections`
      TO `glpi_plugin_fusinvsnmp_networkportconnectionlogs`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `date` `date_mod` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `FK_port_source` `networkports_id_source` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `FK_port_destination` `networkports_id_destination` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);

   // ***** TODO : process_number to taskjob_id

   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
      CHANGE `process_number` `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_discovery`
    */
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_discovery`";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_errors`
    */
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_errors`";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_lock`
    * to `glpi_plugin_fusioninventory_locks`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_lock`
      TO `glpi_plugin_fusioninventory_locks`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_locks`
      CHANGE `itemtype` `tablename` VARCHAR( 64 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_locks`
      CHANGE `fields` `tablefields` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   // Convert datas
   foreach ($typetoname as $key => $itemtype) {
      $table = getTableForItemType($itemtype);
      $sql = "UPDATE `glpi_plugin_fusioninventory_locks`
         SET `tablename` = '".$table."'
         WHERE `tablename` = '".$key."'";
      $DB->query($sql);
   }
   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_locks`";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $a_fields = importArrayFromDB($data['tablefields']);
      $a_input = array();
      foreach ($a_fields as $key => $field) {
         $a_input[] = $field;
      }
      $data['tablefields'] = exportArrayToDB($a_input);
      $sql_update = "UPDATE `glpi_plugin_fusioninventory_locks`
         SET `tablefields` = '".$data['tablefields']."'
         WHERE `id`='".$data['id']."' ";
      $DB->query($sql_update);
   }

   /*
    * Drop `glpi_plugin_fusioninventory_lockable`
    */
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_lockable`";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_mib_networking`
    * to `glpi_plugin_fusinvsnmp_modelmibs`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_mib_networking`
      TO `glpi_plugin_fusinvsnmp_modelmibs`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `FK_model_infos` `plugin_fusinvsnmp_models_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `FK_mib_label` `plugin_fusinvsnmp_miblabels_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `FK_mib_oid` `plugin_fusinvsnmp_miboids_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `FK_mib_object` `plugin_fusinvsnmp_mibobjects_id` INT( 11 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   
   // ***** TODO : modify mapping_name to plugin_fusioninventory_mappings_id

   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `mapping_type` `plugin_fusioninventory_mappings_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      DROP `mapping_name`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_modelmibs`
      CHANGE `activation` `is_active` INT( 1 ) NOT NULL DEFAULT '1'";
   $DB->query($sql);
   
   /*
    * Update `glpi_plugin_fusioninventory_model_infos`
    * to `glpi_plugin_fusinvsnmp_models`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_model_infos`
      TO `glpi_plugin_fusinvsnmp_models`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      CHANGE `device_type` `itemtype` VARCHAR( 100 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      DROP `deleted`,
      DROP `FK_entities`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      CHANGE `activation` `is_active` INT( 1 ) NOT NULL DEFAULT '1'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_models`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   // Convert datas
   foreach ($typetoname as $key => $itemtype) {
      $sql = "UPDATE `glpi_plugin_fusinvsnmp_models`
         SET `itemtype` = '".$itemtype."'
         WHERE `itemtype` = '".$key."'";
      $DB->query($sql);
   }

   /*
    * Update `glpi_plugin_fusioninventory_networking`
    * to `glpi_plugin_fusinvsnmp_networkequipments`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_networking`
      TO `glpi_plugin_fusinvsnmp_networkequipments`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `FK_networking` `networkequipments_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      ADD `sysdescr` TEXT NULL DEFAULT NULL AFTER `networkequipments_id` ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `FK_model_infos` `plugin_fusinvsnmp_models_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `FK_snmp_connection` `plugin_fusinvsnmp_configsecurities_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipments`
      CHANGE `memory` `memory` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_networking_ifaddr`
    * to `glpi_plugin_fusinvsnmp_networkequipmentips`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_networking_ifaddr`
      TO `glpi_plugin_fusinvsnmp_networkequipmentips`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipmentips`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipmentips`
      CHANGE `FK_networking` `networkequipments_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkequipmentips`
      CHANGE `ifaddr` `ip` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);
   
   /*
    * Update `glpi_plugin_fusioninventory_networking_ports`
    * to `glpi_plugin_fusinvsnmp_networkequipmentips`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_networking_ports`
      TO `glpi_plugin_fusinvsnmp_networkports`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkports`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkports`
      CHANGE `FK_networking_ports` `networkports_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkports`
      CHANGE `ifspeed` `ifspeed` BIGINT( 50 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkports`
      CHANGE `ifmac` `mac` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_printers_history`
    * to `glpi_plugin_fusinvsnmp_printerlogs`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_printers_history`
      TO `glpi_plugin_fusinvsnmp_printerlogs`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
      CHANGE `FK_printers` `printers_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printerlogs`
      ADD `pages_total_print` INT( 11 ) NOT NULL DEFAULT '0',
      ADD `pages_n_b_print` INT( 11 ) NOT NULL DEFAULT '0',
      ADD `pages_color_print` INT( 11 ) NOT NULL DEFAULT '0',
      ADD `pages_total_copy` INT( 11 ) NOT NULL DEFAULT '0',
      ADD `pages_n_b_copy` INT( 11 ) NOT NULL DEFAULT '0',
      ADD `pages_color_copy` INT( 11 ) NOT NULL DEFAULT '0',
      ADD `pages_total_fax` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   
   /*
    * Update `glpi_plugin_fusioninventory_printers`
    * to `glpi_plugin_fusinvsnmp_printers`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_printers`
      TO `glpi_plugin_fusinvsnmp_printers`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      CHANGE `FK_printers` `printers_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      ADD `sysdescr` TEXT NULL DEFAULT NULL AFTER `printers_id` ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      CHANGE `FK_model_infos` `plugin_fusinvsnmp_models_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers`
      CHANGE `FK_snmp_connection` `plugin_fusinvsnmp_configsecurities_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_printers_cartridges`
    * to `glpi_plugin_fusinvsnmp_printercartridges`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_printers_cartridges`
      TO `glpi_plugin_fusinvsnmp_printercartridges`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printercartridges`
      CHANGE `ID` `id` INT( 100 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printercartridges`
      CHANGE `FK_printers` `printers_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);

   // ***** TODO : modify mapping_name to plugin_fusioninventory_mappings_id

   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printercartridges`
      CHANGE `object_name` `plugin_fusioninventory_mappings_id` INT( 11 ) NOT NULL DEFAULT '0' ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_printercartridges`
      CHANGE `FK_cartridges` `cartridges_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);

   /*
    * Drop `glpi_plugin_fusioninventory_task`
    */
   $sql = "DROP TABLE `glpi_plugin_fusioninventory_task`";
   $DB->query($sql);

   /*
    * Recreate profiles
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_profiles`
      TO `glpi_plugin_fusioninventory_temp_profiles`";
   $DB->query($sql);
   $sql = "CREATE TABLE `glpi_plugin_fusioninventory_profiles` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
   `right` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugins_id` int(11) NOT NULL DEFAULT '0',
   `profiles_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   UNIQUE KEY `unicity` (`type`, `plugins_id`, `profiles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
   $DB->query($sql);

   // Convert datas
   PluginFusioninventoryProfile::initProfile('fusioninventory', $plugins_id);
   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_temp_profiles`";
   $result=$DB->query($sql);
   $Profile = new Profile();
   while ($data=$DB->fetch_array($result)) {
      $a_profiles = $Profile->find("`name`='".$data['name']."'");
      $a_profile = current($a_profiles);
      $profile_id = $a_profile['id'];
      if ($profile_id != $_SESSION['glpiactiveprofile']['id']) {

         if (!is_null($data['agents'])) {
            $input = array();
            PluginFusioninventoryProfile::addProfile($plugins_id,
                                                     "agent",
                                                     $data['agents'],
                                                     $profile_id);
         }
         if (!is_null($data['configuration'])) {
            $input = array();
            PluginFusioninventoryProfile::addProfile($plugins_id,
                                                     "configuration",
                                                     $data['configuration'],
                                                     $profile_id);
         }
         if (!is_null($data['wol'])) {
            $input = array();
            PluginFusioninventoryProfile::addProfile($plugins_id,
                                                     "wol",
                                                     $data['wol'],
                                                     $profile_id);
         }
         if (!is_null($data['remotecontrol'])) {
            $input = array();
            PluginFusioninventoryProfile::addProfile($plugins_id,
                                                     "remotecontrol",
                                                     $data['remotecontrol'],
                                                     $profile_id);
         }
         if (!is_null($data['unknowndevices'])) {
            $input = array();
            PluginFusioninventoryProfile::addProfile($plugins_id,
                                                     "unknowndevice",
                                                     $data['unknowndevices'],
                                                     $profile_id);
         }
         if (!is_null($data['agentsprocesses'])) {
            $input = array();
            PluginFusioninventoryProfile::addProfile($plugins_id,
                                                     "task",
                                                     $data['agentsprocesses'],
                                                     $profile_id);
         }
      }
   }
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_temp_profiles`
      TO `glpi_plugin_fusinvsnmp_temp_profiles`";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_rangeip`
    * to `glpi_plugin_fusinvsnmp_ipranges`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_rangeip`
      TO `glpi_plugin_fusinvsnmp_ipranges`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      DROP `FK_fusioninventory_agents_discover`,
      DROP `FK_fusioninventory_agents_query`,
      DROP `discover`,
      DROP `query`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      MODIFY COLUMN FK_entities INT AFTER name";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      CHANGE `FK_entities` `entities_id` INT( 11 ) NULL DEFAULT '0' ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      CHANGE `ifaddr_start` `ip_start` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_ipranges`
      CHANGE `ifaddr_end` `ip_end` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   
   /*
    * Update `glpi_plugin_fusioninventory_snmp_connection`
    * to `glpi_plugin_fusinvsnmp_configsecurities`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_snmp_connection`
      TO `glpi_plugin_fusinvsnmp_configsecurities`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `FK_snmp_version` `snmpversion` VARCHAR( 8 ) NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `sec_name` `username` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `auth_protocol` `authentication` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `priv_protocol` `encryption` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_configsecurities`
      CHANGE `deleted` `is_deleted` INT( 1 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_snmp_history`
    * to `glpi_plugin_fusinvsnmp_networkportlogs`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_snmp_history`
      TO `glpi_plugin_fusinvsnmp_networkportlogs`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `FK_ports` `networkports_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);

   // ***** TODO : modify mapping_name to plugin_fusioninventory_mappings_id

   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `Field` `plugin_fusioninventory_mappings_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `old_value` `value_old` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      DROP `old_device_type`,
      DROP `old_device_ID`,
      DROP `new_device_type`,
      DROP `new_device_ID`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `new_value` `value_new` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportlogs`
      CHANGE `FK_process` `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL ";
   $DB->query($sql);

   /*
    * Update `glpi_plugin_fusioninventory_unknown_device`
    * to `glpi_plugin_fusioninventory_unknowndevices`
    */
   $sql = "RENAME TABLE `glpi_plugin_fusioninventory_unknown_device`
      TO `glpi_plugin_fusioninventory_unknowndevices`";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
   $DB->query($sql);
   $sql = "CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvsnmp_unknowndevices` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_fusioninventory_unknowndevices_id` int(11) NOT NULL DEFAULT '0',
   `sysdescr` TEXT,
   `plugin_fusinvsnmp_models_id` INT( 11 ) NOT NULL DEFAULT '0',
   `plugin_fusinvsnmp_configsecurities_id` INT( 11 ) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusioninventory_unknowndevices_id` (`plugin_fusioninventory_unknowndevices_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
   $DB->query($sql);

   // Convert data for SNMP
   $sql = "SELECT * FROM `glpi_plugin_fusioninventory_unknowndevices`
      WHERE `snmp`='1' ";
   $result=$DB->query($sql);
	while ($data=$DB->fetch_array($result)) {
      $sql_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_unknowndevices`
            (`plugin_fusioninventory_unknowndevices_id`,
            `plugin_fusinvsnmp_models_id`,
            `plugin_fusinvsnmp_configsecurities_id`)
          VALUES('".$data['id']."',
                 '".$data['FK_model_infos']."',
                 '".$data['FK_snmp_connection']."')";
      $DB->query($sql_ins);
   }
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      DROP `dnsname`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `FK_entities` `entities_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `location` `locations_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `deleted` `is_deleted` SMALLINT( 6 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `comments` `comment` TEXT CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `type` `itemtype` VARCHAR( 255 ) NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      DROP `snmp`,
      DROP `FK_model_infos`,
      DROP `FK_snmp_connection`;";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `FK_agent` `plugin_fusioninventory_agents_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `ifaddr` `ip` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      CHANGE `ifmac` `mac` VARCHAR( 255 ) CHARACTER
      SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ";
   $DB->query($sql);
   $sql = "ALTER TABLE `glpi_plugin_fusioninventory_unknowndevices`
      ADD `states_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($sql);
   
   // Convert datas
   foreach ($typetoname as $key => $itemtype) {
      $sql = "UPDATE `glpi_plugin_fusioninventory_unknowndevices`
         SET `itemtype` = '".$itemtype."'
         WHERE `itemtype` = '".$key."'";
      $DB->query($sql);
   }








   
   //TODO
// Plugin::migrateItemType();


}

?>