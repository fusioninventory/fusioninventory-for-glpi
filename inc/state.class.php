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
// Original Author of file: Alexandre DELAUNAY
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployState extends CommonDBTM {
   static function showTasks() {
      global $LANG, $CFG_GLPI;

       echo "<table class='deploy_extjs'>
         <tbody>
            <tr>
               <td id='deployStates'>
               </td>
            </tr>
         </tbody>
      </table>";

      // Include JS
      require GLPI_ROOT."/plugins/fusinvdeploy/js/deploystate.front.php";
   }

   static function getTasksDatas() {
      global $DB;

      $query = "SELECT taskjobs.id, taskjobs.name,
         tasks.name as task_name, tasks.id as task_id,
         taskjobstatus.state as status
      FROM glpi_plugin_fusinvdeploy_taskjobs taskjobs
      INNER JOIN glpi_plugin_fusinvdeploy_tasks tasks
         ON tasks.id = taskjobs.plugin_fusinvdeploy_tasks_id
      INNER JOIN glpi_plugin_fusioninventory_taskjobstatus taskjobstatus
         ON taskjobs.id = taskjobstatus.plugin_fusioninventory_taskjobs_id
      ";
      $query = "SELECT taskjobs.id, taskjobs.name,
         tasks.name as task_name, tasks.id as task_id
      FROM glpi_plugin_fusinvdeploy_taskjobs taskjobs
      INNER JOIN glpi_plugin_fusinvdeploy_tasks tasks
         ON tasks.id = taskjobs.plugin_fusinvdeploy_tasks_id
      ";
      $query_res = $DB->query($query);
      while ($row = $DB->fetch_assoc($query_res)) {
         $res['taskjobs'][] = $row;
      }

      return json_encode($res);
   }

   static function getTaskJobLogsDatas() {

   }
}
