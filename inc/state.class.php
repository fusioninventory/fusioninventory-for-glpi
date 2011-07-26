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
       echo "<table class='deploy_extjs'>
         <tbody>
            <tr>
               <td id='deployStates'>
               </td>
            </tr>
         </tbody>
      </table>";

      //load extjs plugins library
      echo "<link rel='stylesheet' type='text/css' href='".GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/treegrid.css'>";
      echo "<script type='text/javascript' src='".GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridSorter.js'></script>";
      echo "<script type='text/javascript' src='".GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridColumnResizer.js'></script>";
      echo "<script type='text/javascript' src='".GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridNodeUI.js'></script>";
      echo "<script type='text/javascript' src='".GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridLoader.js'></script>";
      echo "<script type='text/javascript' src='".GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridColumns.js'></script>";
      echo "<script type='text/javascript' src='".GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGrid.js'></script>";

      //load js view
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

      $res = array();

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

   static function getTaskjobsDatasTree() {
      global $DB;

      $res = array();

      //get all tasks with job and status
      $i = 0;
      $query_tasks = "SELECT DISTINCT(tasks.name), tasks.id
         FROM glpi_plugin_fusinvdeploy_tasks tasks
         INNER JOIN glpi_plugin_fusinvdeploy_taskjobs jobs
            ON jobs.plugin_fusinvdeploy_tasks_id = tasks.id
            AND jobs.method = 'deployinstall' OR jobs.method = 'deployuninstall'
         INNER JOIN glpi_plugin_fusioninventory_taskjobstatus status
            ON status.plugin_fusioninventory_taskjobs_id = jobs.id";
      $res_tasks = $DB->query($query_tasks);
      while ($row_tasks = $DB->fetch_assoc($res_tasks)) {
         $res[$i]['name'] = $row_tasks['name'];
         $res[$i]['type'] = "task";
         $res[$i]['state'] = "null";
         $res[$i]['icon'] = GLPI_ROOT."/plugins/fusinvdeploy/pics/ext/task.png";
         $res[$i]['progress'] = self::getTaskPercent($row_tasks['id']);

         //get all job for this task
         $j = 0;
         $query_jobs = "SELECT id, action
            FROM glpi_plugin_fusinvdeploy_taskjobs
            WHERE plugin_fusinvdeploy_tasks_id = '".$row_tasks['id']."'";
         $res_jobs = $DB->query($query_jobs);
         while ($row_jobs = $DB->fetch_assoc($res_jobs)) {
            $actions = importArrayFromDB($row_jobs['action']);
            foreach ($actions as $action) {
               $action_type = key($action);
               $obj_action = new $action_type;
               $obj_action->getFromDB($action[$action_type]);

               $res[$i]['children'][$j]['name'] = $obj_action->getField('name');
               $res[$i]['children'][$j]['type'] = $action_type;

               //get all status for this job
               $query_status = "SELECT id, items_id, state
                  FROM glpi_plugin_fusioninventory_taskjobstatus
                  WHERE plugin_fusioninventory_taskjobs_id = '".$row_jobs['id']."'";
               $res_status = $DB->query($query_status);

               //no status for this job
               if ($DB->numrows($res_status) <= 0) {
                  unset ($res[$i]['children'][$j]);
                  //$res[$i]['children'][$j]['leaf'] = true;
                  continue;
               }

               switch ($action_type) {
                  case 'Computer':
                     $row_status = $DB->fetch_assoc($res_status);

                     $res[$i]['children'][$j]['icon'] = GLPI_ROOT."/plugins/fusinvdeploy/pics/ext/computer.png";
                     $res[$i]['children'][$j]['leaf'] = true; //final children
                     $res[$i]['children'][$j]['progress'] = $row_status['state'];
                     $res[$i]['children'][$j]['status_id'] = $row_status['id'];

                     break;
                  case 'PluginFusinvdeployGroup':
                     $res[$i]['children'][$j]['icon'] = GLPI_ROOT."/plugins/fusinvdeploy/pics/ext/group.png";
                     $res[$i]['children'][$j]['progress'] = self::getTaskPercent($row_jobs['id'], 'group');

                     $k = 0;
                     while ($row_status = $DB->fetch_assoc($res_status)) {
                        $computer = new Computer;
                        $computer->getFromDB($row_status['items_id']);

                        $res[$i]['children'][$j]['children'][$k]['name'] = $computer->getField('name');
                        $res[$i]['children'][$j]['children'][$k]['leaf'] = true;
                        $res[$i]['children'][$j]['children'][$k]['type'] = "Computer";
                        $res[$i]['children'][$j]['children'][$k]['progress'] = $row_status['state'];
                        $res[$i]['children'][$j]['children'][$k]['icon'] = GLPI_ROOT."/plugins/fusinvdeploy/pics/ext/computer.png";
                        $res[$i]['children'][$j]['children'][$k]['status_id'] = $row_status['id'];

                        $k++;
                     }
                     break;
               }

               $j++;
            }
         }

         $i++;
      }

      return json_encode($res);
   }

   static function getTaskPercent($id, $type = 'task') {
      global $DB;

      $taskjob = new PluginFusioninventoryTaskjob;
      $taskjobstatus = new PluginFusioninventoryTaskjobstatus;

      if ($type == 'task') {
         $a_taskjobs = $taskjob->find("`plugin_fusioninventory_tasks_id`='".$id."'");
         $taskjobs_id = key($a_taskjobs);
      } elseif ($type == 'group') {
         $taskjobs_id = $id;
      }

      $a_taskjobstatus = $taskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".
            $taskjobs_id."' AND `state`!='".PluginFusioninventoryTaskjobstatus::FINISHED."'");

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
