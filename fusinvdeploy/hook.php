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
   @author    Alexandre Delaunay
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

function plugin_fusinvdeploy_install() {
   include_once (GLPI_ROOT . "/plugins/fusinvdeploy/install/install.php");
   $a_plugin = plugin_version_fusinvdeploy();
   pluginFusinvdeployInstall($a_plugin['version']);

   return true;
}



// Uninstall process for plugin : need to return true if succeeded
function plugin_fusinvdeploy_uninstall() {
   include_once (GLPI_ROOT . "/plugins/fusinvdeploy/install/install.php");
   pluginFusinvdeployUninstall();
}



/**
* Check if Fusinvdeploy need to be updated
*
* @param
*
* @return 0 (no need update) OR 1 (need update)
**/
function plugin_fusinvdeploy_needUpdate() {
   $version = "2.3.0";
   include_once (GLPI_ROOT . "/plugins/fusinvdeploy/install/update.php");
   $version_detected = pluginFusinvdeployGetCurrentVersion($version);
   if ((isset($version_detected)) AND ($version_detected != $version)) {
      return 1;
   } else {
      return 0;
   }
}



function plugin_fusinvdeploy_MassiveActions($type) {
   global $LANG;

   switch ($type) {
      case 'PluginFusinvdeployPackage' :
         return array('plugin_fusinvdeploy_duplicatePackage' => $LANG['buttons'][54]);
         break;
      case 'Computer':
         return array('plugin_fusinvdeploy_targetDeployTask'
                  => $LANG['plugin_fusinvdeploy']['massiveactions'][0]
         );
         break;
   }
   return array();
}

function plugin_fusinvdeploy_MassiveActionsDisplay($options=array()) {
   global $LANG;

   switch ($options['itemtype']) {
      case 'PluginFusinvdeployPackage' :
         switch ($options['action']) {
            case 'plugin_fusinvdeploy_duplicatePackage' :
               echo $LANG['plugin_fusinvdeploy']['package'][25].
                     ":&nbsp;<input type='text' name='newname' value=''>";
               echo "&nbsp;<input type='submit' name='massiveaction' class='submit' value='".
                     $LANG['buttons'][2]."'>&nbsp;";
            break;
         }
         break;
      case 'Computer' :
         switch ($options['action']) {
            case 'plugin_fusinvdeploy_targetDeployTask' :
               echo $LANG['plugin_fusinvdeploy']['task'][5].":&nbsp;";
               Dropdown::show('PluginFusinvdeployTask', array(
                     'name'      => "tasks_id",
                     'condition' => "is_active = 0"
               ));
               echo "&nbsp;".$LANG['plugin_fusinvdeploy']['package'][7].":&nbsp;";
               Dropdown::show('PluginFusinvdeployPackage', array(
                     'name'      => "packages_id"
               ));
               echo "&nbsp;<input type='submit' name='massiveaction' class='submit' value='".
                     $LANG['buttons'][2]."'>&nbsp;";
            break;
         }
         break;
   }
   return "";
}

function plugin_fusinvdeploy_MassiveActionsProcess($data) {
   global $DB;

   switch ($data['action']) {
      case 'plugin_fusinvdeploy_duplicatePackage' :
         if ($data['itemtype'] == 'PluginFusinvdeployPackage') {
            $package = new PluginFusinvdeployPackage;
            foreach ($data['item'] as $key => $val) {
               if ($val == 1) {
                  if ($package->getFromDB($key)) {
                     $package->package_clone($data['newname']);
                  }
               }
            }

         }
         break;
      case 'plugin_fusinvdeploy_targetDeployTask' :
         if ($data['itemtype'] == 'Computer') {
            $taskjob = new PluginFusinvdeployTaskjob;
            $tasks = array();


            //get old datas
            $oldjobs = $taskjob->find("plugin_fusinvdeploy_tasks_id = '".$data['tasks_id']."'");

            //add new datas
            foreach ($data['item'] as $key => $val) {
               $task = new StdClass;
               $task->package_id = $data['packages_id'];
               $task->action_type = 'Computer';
               $task->action_selection = $key;
               $task->method = 'deployinstall';
               $task->retry_nb = 3;
               $task->retry_time = 0;
               $tasks[] = $task;
            }

            $params = array(
               'tasks_id'        => $data['tasks_id'],
               'tasks' => json_encode($tasks)
            );
            $taskjob->saveDatas($params);

            //reimport old jobs
            foreach($oldjobs as $job) {
               $sql = "INSERT INTO glpi_plugin_fusinvdeploy_taskjobs (";
               foreach ($job as $key => $val) {
                  $sql .= "`$key`, ";
               }
               $sql = substr($sql, 0, -2).") VALUES (";
               foreach ($job as $val) {
                  if (is_numeric($val) && (int)$val == $val) {
                     $sql .= "$val, ";
                  }
                  else $sql .= "'$val', ";
               }
               $sql = substr($sql, 0, -2).");";

               $DB->query($sql);
            }
         }
         break;
   }
}
?>
