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


function pluginFusinvdeployInstall() {
   global $DB,$LANG;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvdeploy();

   include (GLPI_ROOT . "/plugins/fusinvdeploy/install/update.php");
   $version_detected = pluginfusinvdeployGetCurrentVersion($a_plugin['version']);
   if ((isset($version_detected)) AND ($version_detected != $a_plugin['version'])) {
      // Update
      pluginFusinvdeployUpdate();
   } else {
      // Installation

      // Create database
      $DB_file = GLPI_ROOT ."/plugins/fusinvdeploy/install/mysql/plugin_fusinvdeploy-".$a_plugin['version']."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
      }

      // Create folder in GLPI_PLUGIN_DOC_DIR
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/files');
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/packages');
      }

      $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
      PluginFusioninventoryProfile::initProfile($a_plugin['shortname'], $plugins_id);

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;

      $insert = array('glpi_path'=>'');
      $PluginFusioninventoryConfig->initConfig($plugins_id, $insert);

      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "DEPLOYMENT";
      $input['is_active']  = 1;
      $input['exceptions'] = exportArrayToDB(array());
      $PluginFusioninventoryAgentmodule->add($input);


      // Creation config values
//    PluginFusioninventoryConfig::add($modules_id, type, value);

   }
}


function pluginFusinvdeployUninstall() {
   global $DB;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvdeploy();

   $PluginFusioninventorySetup = new PluginFusioninventorySetup();

   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
      $PluginFusioninventorySetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
   }

   PluginFusioninventoryProfile::cleanProfile($a_plugin['shortname']);

   $query = "SHOW TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (strstr($data[0],"glpi_plugin_".$a_plugin['shortname']."_")){
         $query_delete = "DROP TABLE `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }

   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');

   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
   $PluginFusioninventoryAgentmodule->deleteModule($plugins_id);



   $config = new PluginFusioninventoryConfig();
   $config->cleanConfig(
           PluginFusioninventoryModule::getModuleId($a_plugin['shortname']));

   return true;
}

?>