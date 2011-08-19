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
   if ((isset($version_detected)) && ($version_detected != $a_plugin['version'])) {
      // Update
      pluginFusinvdeployUpdate();
   } else {
      // Installation

      // Create database
      $DB_file    = GLPI_ROOT ."/plugins/fusinvdeploy/install/mysql/plugin_fusinvdeploy-".
                     $a_plugin['version']."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query  = fread($DBf_handle, filesize($DB_file));
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

      include_once (GLPI_ROOT . "/plugins/fusinvdeploy/inc/staticmisc.class.php");
      $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
      PluginFusioninventoryProfile::initProfile($a_plugin['shortname'], $plugins_id);
      PluginFusioninventoryProfile::changeProfile($plugins_id);

      $config = new PluginFusioninventoryConfig;

      $insert = array('glpi_path' => '');
      $config->initConfig($plugins_id, $insert);

      $agentmodule         = new PluginFusioninventoryAgentmodule;
      $input               = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "DEPLOY";
      $input['is_active']  = 1;
      $input['exceptions'] = exportArrayToDB(array());
      $input['url']        = PluginFusioninventoryRestCommunication:: getDefaultRestURL($_SERVER['HTTP_REFERER'],
                                                                                        'fusinvdeploy',
                                                                                        'deploy');

      $agentmodule->add($input);
   }
}


function pluginFusinvdeployUninstall() {
   global $DB;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvdeploy();

   $setup = new PluginFusioninventorySetup();

   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
      $setup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
   }

   PluginFusioninventoryProfile::cleanProfile($a_plugin['shortname']);


   //clean tasks
   $task = new PluginFusinvdeployTask;
   $query_tasks = "SELECT DISTINCT task.id
   FROM glpi_plugin_fusioninventory_tasks as task
   LEFT JOIN glpi_plugin_fusioninventory_taskjobs as job
      ON job.plugin_fusioninventory_tasks_id = task.id
   WHERE job.method='deployinstall' OR job.method='deployuninstall'
   ";
   $res_tasks = $DB->query($query_tasks);
   while ($row_tasks = $DB->fetch_array($res_tasks)) {
    //  $task->getFromDB($row_tasks['id']);
      $task->delete(array('id' => $row_tasks['id']));
   }

   //delete tables
   $query = "SHOW FULL TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (strstr($data[0],"glpi_plugin_".$a_plugin['shortname']."_")){
         if ($data['Table_type'] != "VIEW") $query_delete = "DROP TABLE `".$data[0]."`;";
         else  $query_delete = "DROP VIEW `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }

   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');

   $agentmodule = new PluginFusioninventoryAgentmodule;
   $agentmodule->deleteModule($plugins_id);

   $config = new PluginFusioninventoryConfig();
   $config->cleanConfig(
           PluginFusioninventoryModule::getModuleId($a_plugin['shortname']));

   return true;
}

?>
