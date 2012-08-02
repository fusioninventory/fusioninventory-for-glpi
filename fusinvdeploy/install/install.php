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
   @co-author Alexandre Delaunay
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

function pluginFusinvdeployInstall($version, $migration='') {
   global $DB,$LANG, $CFG_GLPI;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvdeploy();

   include_once (GLPI_ROOT . "/plugins/fusinvdeploy/install/update.php");
   $version_detected = pluginFusinvdeployGetCurrentVersion($a_plugin['version']);
   if ($version_detected !== false && ($version_detected != $a_plugin['version'])) {
      // Update
      return pluginFusinvdeployUpdate($version_detected);
   } else {
      // Installation
      if ($migration == '') {
         $migration = new Migration($version);
      }

      // Create database
      $DB_file    = GLPI_ROOT ."/plugins/fusinvdeploy/install/mysql/plugin_fusinvdeploy-".
                     $a_plugin['version']."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query  = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (Toolbox::get_magic_quotes_runtime()) $sql_line=Toolbox::stripslashes_deep($sql_line);
         if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
      }

      // Create folder in GLPI_PLUGIN_DOC_DIR
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/files');
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/packages');
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/upload');
      }

      include_once (GLPI_ROOT . "/plugins/fusinvdeploy/inc/staticmisc.class.php");
      $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
      PluginFusioninventoryProfile::initProfile($a_plugin['shortname'], $plugins_id);
      PluginFusioninventoryProfile::changeProfile($plugins_id);
	
	   // Create configuration
	   $PluginFusinvdeployConfig = new PluginFusinvdeployConfig();
	   $PluginFusinvdeployConfig->initConfigModule();

      $agentmodule         = new PluginFusioninventoryAgentmodule;
      $input               = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "DEPLOY";
      $input['is_active']  = 1;
      $input['exceptions'] = exportArrayToDB(array());
      $url= '';
      if (isset($_SERVER['HTTP_REFERER'])) {
         $url = $_SERVER['HTTP_REFERER'];
      }
      $input['url'] = '';

      $agentmodule->add($input);
   }
}


function pluginFusinvdeployUninstall() {
   global $DB;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvdeploy();

   if (class_exists('PluginFusioninventorySetup')) {
      $setup = new PluginFusioninventorySetup();

      if (file_exists(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
         $setup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
      }
   }

   if (class_exists('PluginFusioninventoryProfile')) {
      PluginFusioninventoryProfile::cleanProfile($a_plugin['shortname']);
   }


   //clean tasks
   $query_tasks = "SELECT DISTINCT task.id
   FROM glpi_plugin_fusioninventory_tasks as task
   LEFT JOIN glpi_plugin_fusioninventory_taskjobs as job
      ON job.plugin_fusioninventory_tasks_id = task.id
   WHERE job.method='deployinstall' OR job.method='deployuninstall'
   ";

   $res_tasks = $DB->query($query_tasks);
   while ($row_tasks = $DB->fetch_array($res_tasks)) {

      //clean jobs
      $datas_job =  getAllDatasFromTable('glpi_plugin_fusioninventory_taskjobs',
               "plugin_fusioninventory_tasks_id = '".$row_tasks['id']."'");
      foreach($datas_job as $job_id => $job) {

         //clean jobstatus
         $datas_status =  getAllDatasFromTable('glpi_plugin_fusioninventory_taskjobstates',
                  "plugin_fusioninventory_taskjobs_id = '$job_id'");
         foreach($datas_status as $status_id => $status) {
            //clean jobstatuslogs
            $datas_logs =  getAllDatasFromTable('glpi_plugin_fusioninventory_taskjoblogs',
                     "plugin_fusioninventory_taskjobstates_id = '$status_id'");
            foreach($datas_logs as $log_id => $log) {
               //delete logs
               $query_delete_jobstatuslog = "DELETE FROM glpi_plugin_fusioninventory_taskjoblogs
                  WHERE id = '$log_id'";
               $DB->query($query_delete_jobstatuslog);
            }

            //delete status
            $query_delete_jobstatus = "DELETE FROM glpi_plugin_fusioninventory_taskjobstates
               WHERE id = '$status_id'";
            $DB->query($query_delete_jobstatus);
         }

         //delete jobs
         $query_delete_jobs = "DELETE FROM glpi_plugin_fusioninventory_taskjobs
            WHERE id = '$job_id'";
         $DB->query($query_delete_jobs);
      }

      //delete tasks
      $query_delete_tasks = "DELETE FROM glpi_plugin_fusioninventory_tasks
         WHERE id = '".$row_tasks['id']."'";
      $DB->query($query_delete_tasks);

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

   if (class_exists('PluginFusioninventoryModule')) {
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');

      $agentmodule = new PluginFusioninventoryAgentmodule;
      $agentmodule->deleteModule($plugins_id);

      $config = new PluginFusioninventoryConfig();
      $config->cleanConfig(
               PluginFusioninventoryModule::getModuleId($a_plugin['shortname']));
   }

   return true;
}

?>