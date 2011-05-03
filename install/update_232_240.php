<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: Walid Nouh
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

// Update from 2.2.1 to 2.3.0
function update230to240() {
   global $DB, $CFG_GLPI, $LANG;

   ini_set("max_execution_time", "0");

   echo "<strong>Update 2.3.0 to 2.4.0</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("240"); // Start
   
   if (TableExists("`glpi_plugin_fusinvsnmp_ipranges`")) {
      //Rename table
      $query = "RENAME TABLE  `glpi_plugin_fusinvsnmp_ipranges` " .
               "TO `glpi_plugin_fusioninventory_ipranges` ;";
      $DB->query($query) or die ("Rename glpi_plugin_fusinvsnmp_ipranges " .
                                 "to glpi_plugin_fusioninventory_ipranges".
                                 $LANG['update'][90] . $DB->error());
      
      //Migrate itemtype in all tables
      //First taskjobstatus
      $query = "UPDATE `glpi_plugin_fusioninventory_taskjobstatus` " .
               "SET `itemtype`='PluginFusioninventoryIPRange' " .
               "WHERE `itemtype`='PluginFusinvsnmpIPRange'";
      $DB->query($query) or die ("Rename itemtype in glpi_plugin_fusioninventory_taskjobstatus".
                                 $LANG['update'][90] . $DB->error());
      
      $query = "CREATE TABLE  `glpi_plugin_fusioninventory_credentials` (
                  `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                  `entities_id` INT( 11 ) NOT NULL AUTO_INCREMENT DEFAULT '0',
                  `is_recursive` TINYINT( 1 ) NOT NULL AUTO_INCREMENT DEFAULT '0' ,
                  `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                  `username` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                  `password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                  `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
                  `date_mod` DATETIME NOT NULL ,
                  `itemtype` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                  PRIMARY KEY (  `id` )
                  ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
      $DB->query($query) or die ("Create table glpi_plugin_fusioninventory_credentials".
                                 $LANG['update'][90] . $DB->error());
                  
      //Now taskjob
      $job = new PluginFusioninventoryTaskjob();
      foreach (getAllDatasFromTable('glpi_plugin_fusioninventory_taskjobs') as $taskjob) {
         $definition = json_decode($taskjob['definition']);
         if (isset($definition['PluginFusinvsnmpIPRange'])) {
            $definition['PluginFusioninventoryIPRange'] = $definition['PluginFusinvsnmpIPRange'];
            unset($definition['PluginFusinvsnmpIPRange']);
            $taskjob['definition'] = $definition;
            $job->update($taskjob);
         }
      }
      
      if (!TableExists("glpi_plugin_fusioninventory_credentialips")) {
         $query = "CREATE TABLE  `glpi_plugin_fusioninventory_credentialips` (
                     `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                     `entities_id` INT( 11 ) NOT NULL AUTO_INCREMENT DEFAULT '0',
                     `plugin_fusioninventory_credentials_id` INT( 11 ) NOT NULL DEFAULT  '0',
                     `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                     `ip` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                     `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
                     `date_mod` DATETIME NOT NULL ,
                     PRIMARY KEY (  `id` )
                     ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
         $DB->query($query) or die ("Create table glpi_plugin_fusioninventory_credentialips".
                                    $LANG['update'][90] . $DB->error());
      }
   }
   
   if (!FieldExists('glpi_plugin_fusioninventory_agentmodules', 'url')) {
      $query = "ALTER TABLE `glpi_plugin_fusioninventory_agentmodules` 
                ADD `url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
         $DB->query($query) or die ("Add url to  glpi_plugin_fusioninventory_agentmodules".
                                    $LANG['update'][90] . $DB->error());
   }
   
   plugin_fusioninventory_displayMigrationMessage("240"); // End
}

?>