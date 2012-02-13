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
   @author    Walid Nouh
   @co-author David Durieux
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

// Update from 2.2.1 to 2.3.0
function update232to240() {
   global $DB, $CFG_GLPI, $LANG;

   ini_set("max_execution_time", "0");

   echo "<strong>Update 2.3.2 to 2.4.0</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("240"); // Start
   
   if (!class_exists('PluginFusioninventoryConfig')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/config.class.php");
   }
   if (!class_exists('PluginFusioninventorySetup')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   }
   
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
   $config = new PluginFusioninventoryConfig();
   $PluginFusioninventorySetup = new PluginFusioninventorySetup();
   $users_id = $PluginFusioninventorySetup->createFusionInventoryUser();
   if (!PluginFusioninventoryConfig::getValue($plugins_id, "users_id")) {
       $config->initConfig($plugins_id, array("users_id" => $users_id));
   }
   
   if (TableExists("glpi_plugin_fusinvsnmp_ipranges")) {
      //Rename table
      $query = "RENAME TABLE  `glpi_plugin_fusinvsnmp_ipranges` " .
               "TO `glpi_plugin_fusioninventory_ipranges` ;";
      $DB->query($query) or die ("Rename glpi_plugin_fusinvsnmp_ipranges " .
                                 "to glpi_plugin_fusioninventory_ipranges".
                                 $LANG['update'][90] . $DB->error());
      
      
      //Migrate itemtype in all tables
      //First taskjobstatus
      $query = "UPDATE `glpi_displaypreferences` " .
               "SET `itemtype`='PluginFusioninventoryIPRange' " .
               "WHERE `itemtype`='PluginFusinvsnmpIPRange'";
      $DB->query($query) or die ("Rename itemtype in glpi_displaypreferences".
                                 $LANG['update'][90] . $DB->error());

      $plugins_id = PluginFusioninventoryModule::getModuleId("fusioninventory");
      $query = "UPDATE `glpi_plugin_fusioninventory_profiles` SET `plugins_id` = '$plugins_id' " .
               "WHERE `glpi_plugin_fusioninventory_profiles`.`type`='iprange'";
      $DB->query($query) or die ("Update iprange profile values ".
                                 $LANG['update'][90] . $DB->error());

      foreach (array('glpi_plugin_fusioninventory_taskjobstatus', 
                     'glpi_plugin_fusioninventory_taskjoblogs') as $table) {
         $query = "UPDATE `$table` " .
                  "SET `itemtype`='PluginFusioninventoryIPRange' " .
                  "WHERE `itemtype`='PluginFusinvsnmpIPRange'";
         $DB->query($query) or die ("Rename itemtype in $table".
                                    $LANG['update'][90] . $DB->error());
      }

      //Now taskjob
      include_once(GLPI_ROOT."/plugins/fusioninventory/inc/taskjob.class.php");
      $job = new PluginFusioninventoryTaskjob();
      foreach (getAllDatasFromTable('glpi_plugin_fusioninventory_taskjobs') as $taskjob) {
         $definition = json_decode($taskjob['definition'], true);
         foreach ($definition as $id => $content) {
            if ($content['PluginFusinvsnmpIPRange']) {
               $definition[$id]['PluginFusioninventoryIPRange'] = $content['PluginFusinvsnmpIPRange'];
               unset($definition[$id]['PluginFusinvsnmpIPRange']);
            }
         }
         $taskjob['definition'] = json_encode($definition);
         $taskjob['status'] = '0';
         $job->update($taskjob);
      }

   }
      
   $query = "CREATE TABLE  `glpi_plugin_fusioninventory_credentials` (
               `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
               `entities_id` INT( 11 ) NOT NULL DEFAULT '0',
               `is_recursive` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
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
                  
      
   if (!TableExists("glpi_plugin_fusioninventory_credentialips")) {
      $query = "CREATE TABLE  `glpi_plugin_fusioninventory_credentialips` (
                  `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                  `entities_id` INT( 11 ) NOT NULL DEFAULT '0',
                  `plugin_fusioninventory_credentials_id` INT( 11 ) NOT NULL DEFAULT  '0',
                  `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                  `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
                  `ip` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
                  `date_mod` DATETIME NOT NULL ,
                  PRIMARY KEY (  `id` )
                  ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
      $DB->query($query) or die ("Create table glpi_plugin_fusioninventory_credentialips".
                                 $LANG['update'][90] . $DB->error());
   }

   include_once(GLPI_ROOT."/plugins/fusioninventory/inc/profile.class.php");
   include_once(GLPI_ROOT."/plugins/fusioninventory/inc/staticmisc.class.php");
   PluginFusioninventoryProfile::initProfile("FUSIONINVENTORY", $plugins_id);

   
   if (!FieldExists('glpi_plugin_fusioninventory_agentmodules', 'url')) {
      $query = "ALTER TABLE `glpi_plugin_fusioninventory_agentmodules` 
                ADD `url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' ";
      $DB->query($query) or die ("Add url to  glpi_plugin_fusioninventory_agentmodules".
                                 $LANG['update'][90] . $DB->error());
   }
   if (!FieldExists('glpi_plugin_fusioninventory_agents', 'useragent')) {
      $query = "ALTER TABLE `glpi_plugin_fusioninventory_agents` ADD `useragent` VARCHAR( 255 ) NULL";
      $DB->query($query) or die ("Add useragent to glpi_plugin_fusioninventory_agents".
                                 $LANG['update'][90] . $DB->error());
   }
   
   if (TableExists("glpi_plugin_fusioninventory_agents_errors")) {
      $sql = "DROP TABLE `glpi_plugin_fusioninventory_agents_errors`";
      $DB->query($sql);
   }
   
   if (TableExists("glpi_plugin_fusioninventory_agents_processes")) {
      $sql = "DROP TABLE `glpi_plugin_fusioninventory_agents_processes`";
      $DB->query($sql);
   }

   if (TableExists("glpi_plugin_fusioninventory_computers")) {
      $sql = "DROP TABLE `glpi_plugin_fusioninventory_computers`";
      $DB->query($sql);
   }
   
   plugin_fusioninventory_displayMigrationMessage("240"); // End
}

?>