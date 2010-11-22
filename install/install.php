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
   // Get informations of plugin
   $a_plugin = plugin_version_fusioninventory();

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
                    ('delete_task', '24', '".$plugins_id."'),
                    ('inventory_frequence', '24', '".$plugins_id."'),
                    ('agent_port', '62354', '".$plugins_id."');";
   $DB->query($query);

   PluginFusioninventoryProfile::changeProfile($plugins_id);
   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
   $input = array();
   $input['plugins_id'] = $plugins_id;
   $input['modulename'] = "WAKEONLAN";
   $input['is_active']  = 0;
   $input['exceptions'] = exportArrayToDB(array());
   $PluginFusioninventoryAgentmodule->add($input);

   CronTask::Register('PluginFusioninventoryTaskjob', 'taskscheduler', '300', array('mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30));
}

?>