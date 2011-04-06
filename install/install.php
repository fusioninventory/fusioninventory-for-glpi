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
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

function pluginFusioninventoryInstall($version) {
   global $DB,$LANG,$CFG_GLPI;

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
   if (!class_exists('PluginFusioninventoryRuleImportEquipmentCollection')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/ruleimportequipmentcollection.class.php");
   }
   if (!class_exists('PluginFusioninventoryRuleImportEquipment')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/ruleimportequipment.class.php");
   }
   if (!class_exists('PluginFusioninventoryRuleCollection')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/rulecollection.class.php");
   }
   if (!class_exists('PluginFusioninventoryRule')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/rule.class.php");
   }
   if (!class_exists('PluginFusioninventoryRuleCriteria')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/rulecriteria.class.php");
   }
   if (!class_exists('PluginFusioninventoryRuleAction')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/ruleaction.class.php");
   }
   // Get informations of plugin

   // Clean if FUsion / Tracker has been installed and uninstalled (not clean correctly)
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


   // Insert in DB
   $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-"
              .$version."-empty.sql";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
      if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
   }

   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml');
   }

   $plugin = new Plugin();
   $data = $plugin->find("`name` = 'FusionInventory'");
   $fields = current($data);
   $plugins_id = $fields['id'];
   PluginFusioninventoryProfile::initProfile('fusioninventory', $plugins_id);

   // glpi_plugin_fusioninventory_configs
   $query = "INSERT INTO `glpi_plugin_fusioninventory_configs`
                         (`type`, `value`, `plugins_id`)
             VALUES ('version', '".$version."', '".$plugins_id."'),
                    ('ssl_only', '0', '".$plugins_id."'),
                    ('delete_task', '20', '".$plugins_id."'),
                    ('inventory_frequence', '24', '".$plugins_id."'),
                    ('agent_port', '62354', '".$plugins_id."'),
                    ('extradebug', '0', '".$plugins_id."')";
   $DB->query($query);

   PluginFusioninventoryProfile::changeProfile($plugins_id);
   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
   $input = array();
   $input['plugins_id'] = $plugins_id;
   $input['modulename'] = "WAKEONLAN";
   $input['is_active']  = 0;
   $input['exceptions'] = exportArrayToDB(array());
   $PluginFusioninventoryAgentmodule->add($input);

   CronTask::Register('PluginFusioninventoryTaskjob', 'taskscheduler', '60', array('mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30));
   Crontask::Register('PluginFusioninventoryTaskjobstatus', 'cleantaskjob', (3600 * 24), array('mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30));


   $PluginFusioninventorySetup = new PluginFusioninventorySetup();
   $PluginFusioninventorySetup->initRules();
}

?>