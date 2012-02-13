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

// Update from 2.1.0 to 2.1.1
function update210to211() {
   global $DB,$LANG;

   echo "<strong>Update 2.1.0 to 2.1.1</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("211"); // Start

   plugin_fusioninventory_displayMigrationMessage("211", $LANG['update'][141]); // Updating schema


   if (!isIndex("glpi_plugin_tracker_tmp_netports", "cdp")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_tmp_netports`
         ADD INDEX (`cdp`)";
      $DB->query($sql);
   }
   if (!isIndex("glpi_plugin_tracker_tmp_netports", "FK_networking")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_tmp_netports`
         ADD INDEX `FK_networking` ( `FK_networking` , `FK_networking_port` )";
      $DB->query($sql);
   }
   if (!isIndex("glpi_plugin_tracker_model_infos", "device_type")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_model_infos`
         ADD INDEX (`device_type`)";
      $DB->query($sql);
   }
   if (!isIndex("glpi_plugin_tracker_snmp_connection", "FK_snmp_version")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_snmp_connection`
         ADD INDEX (`FK_snmp_version`)";
      $DB->query($sql);
   }
   if (!isIndex("glpi_plugin_tracker_printers", "FK_snmp_connection")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_printers`
         ADD INDEX (`FK_snmp_connection`)";
      $DB->query($sql);
   }
   if (!isIndex("glpi_plugin_tracker_tmp_connections", "macaddress")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_tmp_connections`
         ADD INDEX (`macaddress`)";
      $DB->query($sql);
   }
   if (!TableExists("glpi_plugin_tracker_config_snmp_history")) {
      $sql = "CREATE TABLE `glpi_plugin_tracker_config_snmp_history` (
         `id` INT( 8 ) NOT NULL AUTO_INCREMENT ,
         `field` VARCHAR( 255 ) NOT NULL ,
         PRIMARY KEY ( `id` ) ,
         INDEX ( `field` )
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
      $DB->query($sql);
   }

   $DB->query("UPDATE `glpi_plugin_tracker_config`
               SET `version` = '2.1.1'
               WHERE `ID`=1
               LIMIT 1 ;");

   plugin_fusioninventory_displayMigrationMessage("211"); // End

   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";
}

?>