<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------


function pluginFusioninventoryInstall($version) {
<<<<<<< HEAD:install/install.php
   global $DB,$LANG,$CFG_GLPI;

   $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-empty.sql";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
      if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
   }

//      PluginFusioninventoryProfile::createfirstaccess($_SESSION['glpiactiveprofile']['id']);
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
=======
   global $DB,$LANG;

   // Get informations of plugin
   $a_plugin = plugin_version_fusioninventory();

   include (GLPI_ROOT . "/plugins/fusioninventory/install/update.php");
   $version_detected = pluginFusioninventoryGetCurrentVersion($version);
   if ((isset($version_detected)) AND ($version_detected != $version)) {
      pluginFusioninventoryUpdate($version);
   } else {
      // Install
      $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
      }

      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
      }

//      $config = new PluginFusioninventoryConfig;
//      $config->initConfig($version);

      $module_id = PluginFusioninventoryModule::addModule($a_plugin['shortname']);
      $a_rights = array();
      $a_rights['agents'] = 'w';
      $a_rights['agentsprocesses'] = 'w';
      $a_rights['remotecontrol'] = 'w';
      $a_rights['wol'] = 'w';
      $a_rights['configuration'] = 'w';
      PluginFusioninventoryProfile::initProfile($module_id,$a_rights);
>>>>>>> 62286a665956ebfd6a0c3723a73d99544c150844:install/install.php
   }

   // glpi_plugin_fusioninventory_modules
   $plugin = new Plugin();
   $data = $plugin->find("`name` = 'FusionInventory'");
   $fields = current($data);
   $plugins_id = $fields['id'];
   $query = "INSERT INTO `glpi_plugin_fusioninventory_modules` (`id`, `xmltag`, `plugins_id`)
             VALUES ('0','INVENTORY', '".$plugins_id."');";
   $DB->query($query);

   // glpi_plugin_fusioninventory_configs
   $url = str_replace("http:","https:",$CFG_GLPI["url_base"]);
   $query = "INSERT INTO `glpi_plugin_fusioninventory_configs`
                         (`type`, `value`, `plugin_fusioninventory_modules_id`)
             VALUES ('version', '".$version."', '0'),
                    ('URL_agent_conf', '".$url."', '0'),
                    ('ssl_only', '0', '0'),
                    ('storagesnmpauth', 'DB', '0'),
                    ('inventory_frequence', '24', '0'),
                    ('criteria1_ip', '0', '0'),
                    ('criteria1_name', '0', '0'),
                    ('criteria1_serial', '0', '0'),
                    ('criteria1_macaddr', '0', '0'),
                    ('criteria2_ip', '0', '0'),
                    ('criteria2_name', '0', '0'),
                    ('criteria2_serial', '0', '0'),
                    ('criteria2_macaddr', '0', '0'),
                    ('delete_agent_process', '24', '0');";
   $DB->query($query);

//   $config = new PluginFusioninventoryConfig;
//   $config->initConfig($version);
   $config_modules = new PluginFusioninventoryConfigModules;
   $config_modules->initConfig();

   PluginFusioninventoryProfile::changeProfile();
}

?>