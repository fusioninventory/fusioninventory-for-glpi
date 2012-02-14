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

// Update from 2.0.1 to 2.0.2
function update201to202() {
   global $DB,$LANG;

   echo "<strong>Update 2.0.1 to 2.0.2</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("202"); // Start

   plugin_fusioninventory_displayMigrationMessage("202", $LANG['update'][141]); // Updating schema

   if (!TableExists("glpi_plugin_tracker_unknown_device")) {
      $sql = "CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_unknown_device` (
        `ID` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `date_mod` datetime DEFAULT NULL,
        `FK_entities` int(11) NOT NULL DEFAULT '0',
        `location` int(11) NOT NULL DEFAULT '0',
        `deleted` smallint(6) NOT NULL DEFAULT '0',
        PRIMARY KEY (`ID`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($sql);
   }
   if (!FieldExists("glpi_plugin_tracker_config", "version")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_config`
         ADD `version` VARCHAR( 255 ) NOT NULL DEFAULT '0' AFTER `ID`";
      $DB->query($sql);
   }
   if (!FieldExists("glpi_plugin_tracker_printers_history", "pages_total_print")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_printers_history`
         ADD `pages_total_print` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists("glpi_plugin_tracker_printers_history", "pages_n_b_print")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_printers_history`
         ADD `pages_n_b_print` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists("glpi_plugin_tracker_printers_history", "pages_color_print")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_printers_history`
         ADD `pages_color_print` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists("glpi_plugin_tracker_printers_history", "pages_total_copy")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_printers_history`
         ADD `pages_total_copy` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists("glpi_plugin_tracker_printers_history", "pages_n_b_copy")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_printers_history`
         ADD `pages_n_b_copy` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists("glpi_plugin_tracker_printers_history", "pages_color_copy")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_printers_history`
         ADD `pages_color_copy` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }
   if (!FieldExists("glpi_plugin_tracker_printers_history", "pages_total_fax")) {
      $sql = "ALTER TABLE `glpi_plugin_tracker_printers_history`
         ADD `pages_total_fax` INT( 11 ) NOT NULL DEFAULT '0'";
      $DB->query($sql);
   }

   if (!TableExists("glpi_plugin_tracker_tmp_netports")) {
      $sql = "CREATE TABLE `glpi_plugin_tracker_tmp_netports` (
        `ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
        `FK_networking` INT( 11 ) NOT NULL DEFAULT '0',
        `FK_networking_port` INT( 11 ) NOT NULL DEFAULT '0',
        `cdp` INT( 1 ) NOT NULL DEFAULT '0',
        PRIMARY KEY ( `ID` )
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
      $DB->query($sql);
   }
   if (!TableExists("glpi_plugin_tracker_tmp_connections")) {
      $sql = "CREATE TABLE `glpi_plugin_tracker_tmp_connections` (
        `ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
        `FK_tmp_netports` INT( 11 ) NOT NULL DEFAULT '0',
        `macaddress` VARCHAR( 255 ) NULL ,
        PRIMARY KEY ( `ID` )
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
      $DB->query($sql);
   }


   // Migrate unknown mac address in unknown device (MySQL table)
    updateFromOldVersion_unknown_mac();
    // Delete MySQL table "glpi_plugin_tracker_unknown_mac"
    $DB->query("DROP TABLE `glpi_plugin_tracker_unknown_mac`");


   $sql = "UPDATE `glpi_plugin_tracker_config`
      SET `version` = '2.0.2' WHERE `glpi_plugin_tracker_config`.`ID`=1 LIMIT 1";
   $DB->query($sql);

   plugin_fusioninventory_displayMigrationMessage("202"); // End

   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";
}


function updateFromOldVersion_unknown_mac() {
   global $DB,$LANG;

   $NetworkPort=new NetworkPort();
   $NetworkPort_NetworkPort = new NetworkPort_NetworkPort();

   $query = "SELECT DISTINCT unknow_mac,unknown_ip,port,end_FK_processes
         FROM glpi_plugin_tracker_unknown_mac
         WHERE end_FK_processes=(select max(end_FK_processes) from glpi_plugin_tracker_unknown_mac) ";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         $sql_ins = "INSERT INTO `glpi_plugin_tracker_unknown_device`
            (`name`, `date_mod`)
            VALUES('', '".date("Y-m-d H:i:s")."')";
         $newID=$DB->query($sql_ins);
          // Add networking_port
         $input = array();
         $input["items_id"] = $newID;
         $input["itemtype"] = '5153';
         $input["ip"] = $data["unknown_ip"];
         $input['mac'] = $data["unknow_mac"];
         $port_ID = $NetworkPort->add($input);

         if ($port_ID) {
            // Connection between ports (wire table in DB)
            $input = array();
            $input['networkports_id_1'] = $data["port"];
            $input['networkports_id_2'] = $port_ID;
            $NetworkPort_NetworkPort->add($input);
         }
      }
   }
}

?>