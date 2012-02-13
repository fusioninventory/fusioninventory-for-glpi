<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

// Update from 2.3.0 to 2.4.0
function update232to240() {
   global $DB;

   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","networkmodel_id")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `networkmodel_id` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }
   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","printermodel_id")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `printermodel_id` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }
   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","have_someinformations")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `have_someinformations` INT( 1 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }   
   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","have_importantinformations")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `have_importantinformations` INT( 1 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }
   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","have_ports")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `have_ports` INT( 1 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }
   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","have_portsconnections")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `have_portsconnections` INT( 1 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }   
   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","have_vlan")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `have_vlan` INT( 1 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }
   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","have_trunk")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `have_trunk` INT( 1 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }   
   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","released")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `released` INT( 1 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }   
   if (!FieldExists("glpi_plugin_fusinvsnmp_constructdevices","releasedsnmpmodel_id")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_constructdevices` 
         ADD `releasedsnmpmodel_id` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($query);
   }      
   
   $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_networkportconnectionlogs` 
      CHANGE `plugin_fusioninventory_agentprocesses_id` `plugin_fusioninventory_agentprocesses_id` INT( 11 ) NOT NULL DEFAULT '0'";
   $DB->query($query);
   
   if (!isIndex("glpi_plugin_fusinvsnmp_printers","printers_id")) {
      $query = "ALTER TABLE `glpi_plugin_fusinvsnmp_printers` 
         ADD INDEX ( `printers_id` )";
      $DB->query($query);      
   }

   if (!TableExists("glpi_plugin_fusioninventory_config_snmp_history")) {
      $sql = "DROP TABLE `glpi_plugin_fusioninventory_config_snmp_history`";
      $DB->query($sql);
   }

   if (!TableExists("glpi_plugin_fusioninventory_config_snmp_networking")) {
      $sql = "DROP TABLE `glpi_plugin_fusioninventory_config_snmp_networking`";
      $DB->query($sql);
   }

   
   
   
   
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
   $insert = array('threads_netdiscovery' => 1,
                   'threads_snmpquery'    => 1);
   $pluginFusioninventoryConfig = new PluginFusioninventoryConfig();
   $pluginFusioninventoryConfig->initConfig($plugins_id, $insert);
   
   // Update mapping:
   $query ="INSERT INTO `glpi_plugin_fusioninventory_mappings`
      (`itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
   VALUES ('NetworkEquipment','cdpCacheVersion','','',435,NULL),
          ('NetworkEquipment','cdpCacheDeviceId','','',436,NULL),
          ('NetworkEquipment','cdpCachePlatform','','',437,NULL),
          ('NetworkEquipment','lldpRemSysDesc','','',438,NULL),
          ('NetworkEquipment','lldpRemSysName','','',439,NULL),
          ('NetworkEquipment','lldpRemPortDesc','','',440,NULL)";
   $DB->query($query);
   
   // Fix problem with mapping with many entries with same mapping
   $a_mapping = array();
   $a_mappingdouble = array();
   $query = "SELECT * FROM `glpi_plugin_fusioninventory_mappings`
      ORDER BY `id`";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (!isset($a_mapping[$data['itemtype'].".".$data['name']])) {
         $a_mapping[$data['itemtype'].".".$data['name']] = $data['id'];
      } else {
         $a_mappingdouble[$data['id']] = $data['itemtype'].".".$data['name'];
      }
   }   
   foreach($a_mappingdouble as $mapping_id=>$mappingkey) {
      $query = "UPDATE `glpi_plugin_fusinvsnmp_modelmibs`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "UPDATE `glpi_plugin_fusinvsnmp_printercartridges`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "UPDATE `glpi_plugin_fusinvsnmp_networkportlogs`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "UPDATE `glpi_plugin_fusinvsnmp_configlogfields`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "DELETE FROM `glpi_plugin_fusioninventory_mappings`
         WHERE `id` = '".$mapping_id."'";
      $DB->query($query);
   }

   
   
   
   
}
?>