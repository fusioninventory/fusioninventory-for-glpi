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

   static function getTaskjobsDatas() {
      global $DB;

      $query = "SELECT taskjobs.id as job_id, taskjobs.name,
         tasks.name as task_name, tasks.id as task_id,
         taskjobstatus.id as status_id, taskjobstatus.state as status,
         taskjobstatus.itemtype, taskjobstatus.items_id
      FROM glpi_plugin_fusinvdeploy_taskjobs taskjobs
      INNER JOIN glpi_plugin_fusinvdeploy_tasks tasks
         ON tasks.id = taskjobs.plugin_fusinvdeploy_tasks_id
      LEFT JOIN glpi_plugin_fusioninventory_taskjobstatus taskjobstatus
         ON taskjobs.id = taskjobstatus.plugin_fusioninventory_taskjobs_id
      ";
      $query_res = $DB->query($query);
      while ($row = $DB->fetch_assoc($query_res)) {
         $computer = new Computer;
         $computer->getFromDB($row['items_id']);
         $row['computer_name'] = $computer->getField('name');
         $row['task_percent'] = self::getTaskPercent($row['task_id']);
         $res['taskjobs'][] = $row;
      }

      return json_encode($res);
   }

   static function getTaskJobLogsDatas($params) {
      global $DB;

      if (!isset($params['status_id'])) exit;

      $query = "SELECT *
      FROM glpi_plugin_fusioninventory_taskjoblogs
      WHERE plugin_fusioninventory_taskjobstatus_id = '".$params['status_id']."'";

      $query_res = $DB->query($query);
      while ($row = $DB->fetch_assoc($query_res)) {
         $res['taskjoblogs'][] = $row;
      }

      return json_encode($res);
   }

   static function getTaskPercent($task_id) {
      global $DB;

      $taskjob = new PluginFusioninventoryTaskjob;
      $taskjobstatus = new PluginFusioninventoryTaskjobstatus;

      $a_taskjobs = $taskjob->find("`plugin_fusioninventory_tasks_id`='".$task_id."'");

      $a_taskjobstatus = $taskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".
            key($a_taskjobs)."' AND `state`!='".PluginFusioninventoryTaskjobstatus::FINISHED."'");

      $state = array();
      $state[0] = 0;
      $state[1] = 0;
      $state[2] = 0;
      $state[3] = 0;
      $total = 0;
      $globalState = 0;

      if (count($a_taskjobstatus) > 0) {
         foreach ($a_taskjobstatus as $data) {
            $total++;
            $state[$data['state']]++;
         }

         $first = 25;
         $second = ((($state[1]+$state[2]+$state[3]) * 100) / $total) / 4;
         $third = ((($state[2]+$state[3]) * 100) / $total) / 4;
         $fourth = (($state[3] * 100) / $total) / 4;
         $globalState = $first + $second + $third + $fourth;
      }

      return ceil($globalState)."%";
   }
}
