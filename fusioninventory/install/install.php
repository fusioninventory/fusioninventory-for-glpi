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

function pluginFusioninventoryInstall($version, $migration='') {
   global $DB,$LANG,$CFG_GLPI;

   if ($migration == '') {
      $migration = new Migration($version);
   }
   
   $migration->displayMessage("Installation of plugin FusionInventory");
   
   if (!class_exists('PluginFusioninventoryProfile')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/profile.class.php");
   }
   if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
   }
   if (!class_exists('PluginFusioninventoryStaticmisc')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/staticmisc.class.php");
   }
   if (!class_exists('PluginFusioninventorySetup')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
   }
   if (!class_exists('PluginFusioninventoryUnknownDevice')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/unknowndevice.class.php");
   }
   if (!class_exists('PluginFusioninventoryRuleImportEquipmentCollection')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/ruleimportequipmentcollection.class.php");
   }
   if (!class_exists('PluginFusioninventoryRuleImportEquipment')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/ruleimportequipment.class.php");
   }
   if (!class_exists('PluginFusioninventoryModule')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/module.class.php");
   }
   // Get informations of plugin

   // ** Clean if FUsion / Tracker has been installed and uninstalled (not clean correctly)
   $migration->displayMessage("Clean data from old installation of the plugin");
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5150'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5151'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5152'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5153'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5156'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5157'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5158'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5159'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5161'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5165'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5166'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5167'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='5168'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype` LIKE 'PluginFusioninventory%'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype` LIKE 'PluginFusinvinventory%'";
   $DB->query($sql);
   $sql = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype` LIKE 'PluginFusinvsnmp%'";
   $DB->query($sql);
      // Purge network ports have itemtype tp 5153
      $networkPort = new NetworkPort();
      $sql = "SELECT * FROM `glpi_networkports`
         WHERE `itemtype`='5153'";
      $result=$DB->query($sql);
      while ($data=$DB->fetch_array($result)) {
         $networkPort->delete(array('id'=>$data['id']), 1);
      }


   // Remove old rules
   $migration->displayMessage("Clean rules from old installation of the plugin");
   $Rule = new Rule();
   $a_rules = $Rule->find("`sub_type`='PluginFusioninventoryRuleImportEquipment'");
   foreach ($a_rules as $data) {
      $Rule->delete($data);
   }



   // ** Insert in DB
   $migration->displayMessage("Creation tables in database");
   $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-"
              .$version."-empty.sql";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (Toolbox::get_magic_quotes_runtime()) $sql_line=Toolbox::stripslashes_deep($sql_line);
      if (!empty($sql_line)) $DB->query($sql_line);
   }
   $migration->displayMessage("Creation of folders");
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml');
   }

   $migration->displayMessage("Initialize profiles");
   $plugin = new Plugin();
   $data = $plugin->find("`name` = 'FusionInventory'");
   $fields = current($data);
   $plugins_id = $fields['id'];
   PluginFusioninventoryProfile::initProfile('fusioninventory', $plugins_id);

   // bug of purge network port when purge unknown devices
   $sql = "SELECT `glpi_networkports`.`id` as nid FROM `glpi_networkports`
      LEFT JOIN `glpi_plugin_fusioninventory_unknowndevices`
         ON `glpi_plugin_fusioninventory_unknowndevices`.`id` = `glpi_networkports`.`items_id`
      WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
         AND `glpi_plugin_fusioninventory_unknowndevices`.`id` IS NULL ";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $networkPort->delete(array('id'=>$data['nid']), 1);
   }

   // glpi_plugin_fusioninventory_configs
   $pfSetup = new PluginFusioninventorySetup();
   $users_id = $pfSetup->createFusionInventoryUser();
   $query = "INSERT INTO `glpi_plugin_fusioninventory_configs`
                         (`type`, `value`, `plugins_id`)
             VALUES ('version', '".$version."', '".$plugins_id."'),
                    ('ssl_only', '0', '".$plugins_id."'),
                    ('delete_task', '20', '".$plugins_id."'),
                    ('inventory_frequence', '24', '".$plugins_id."'),
                    ('agent_port', '62354', '".$plugins_id."'),
                    ('extradebug', '0', '".$plugins_id."'),
                    ('users_id', '".$users_id."', '".$plugins_id."')";
   $DB->query($query);

   PluginFusioninventoryProfile::changeProfile($plugins_id);
   $pfAgentmodule = new PluginFusioninventoryAgentmodule();
   $input = array();
   $input['plugins_id'] = $plugins_id;
   $input['modulename'] = "WAKEONLAN";
   $input['is_active']  = 0;
   $input['exceptions'] = exportArrayToDB(array());
   $pfAgentmodule->add($input);

   CronTask::Register('PluginFusioninventoryTaskjob', 'taskscheduler', '60', 
                      array('mode' => 2, 'allowmode' => 3, 'logs_lifetime'=> 30));
   Crontask::Register('PluginFusioninventoryTaskjobstate', 'cleantaskjob', (3600 * 24), 
                      array('mode' => 2, 'allowmode' => 3, 'logs_lifetime' => 30));


   $migration->displayMessage("Create rules");
   $pfSetup = new PluginFusioninventorySetup();
   $pfSetup->initRules();
}

?>