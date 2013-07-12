<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Alexandre Delaunay
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryDeployState extends CommonDBTM {

   const RECEIVED       = 'received';
   const DOWNLOADING    = 'downloading';
   const EXTRACTING     = 'extracting';
   const PROCESSING     = 'processing';

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
      echo "<link rel='stylesheet' type='text/css' href='".
              GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/treegrid.css'>";
      echo "<script type='text/javascript' src='".
              GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridSorter.js'></script>";
      echo "<script type='text/javascript' src='".
              GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridColumnResizer.js'>".
              "</script>";
      echo "<script type='text/javascript' src='".
              GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridNodeUI.js'></script>";
      echo "<script type='text/javascript' src='".
              GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridLoader.js'></script>";
      echo "<script type='text/javascript' src='".
              GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGridColumns.js'></script>";
      echo "<script type='text/javascript' src='".
              GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/treegrid/TreeGrid.js'></script>";

      //load js view
      require GLPI_ROOT."/plugins/fusinvdeploy/js/deploystate.front.php";
   }



   static function getTaskjobsDatas() {
      global $DB;

      $query = "SELECT taskjobs.id as job_id, taskjobs.name,
         tasks.name as task_name, tasks.id as task_id,
         taskjobstatus.id as status_id, taskjobstatus.state as status,
         taskjobstatus.itemtype, taskjobstatus.items_id
      FROM glpi_plugin_fusioninventory_deploytaskjobs taskjobs
      INNER JOIN glpi_plugin_fusioninventory_deploytasks tasks
         ON tasks.id = taskjobs.plugin_fusioninventory_deploytasks_id
      LEFT JOIN glpi_plugin_fusioninventory_taskjobstates taskjobstatus
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



   static function processComment($state, $comment) {
      if ($comment == "") {
         switch ($state) {

            case PluginFusioninventoryTaskjoblog::TASK_OK:
               $comment = __('Ok', 'fusioninventory');
               break;

            case PluginFusioninventoryTaskjoblog::TASK_ERROR_OR_REPLANNED:
               $comment = __('Error / rescheduled', 'fusioninventory');
               break;

            case PluginFusioninventoryTaskjoblog::TASK_ERROR:
               $comment = __('Error', 'fusioninventory');
               break;

            case PluginFusioninventoryTaskjoblog::TASK_PREPARED:
               $comment = __('Prepared', 'fusioninventory');
               break;

         }
      } elseif ($state == PluginFusioninventoryTaskjoblog::TASK_ERROR_OR_REPLANNED) {
         $comment = __('Error / rescheduled', 'fusioninventory');
      }
      return $comment;
   }



   static function getTaskJobLogsDatasTree($params) {
      global $DB;

      $res = array();

      if (!isset($params['items_id'])
              || !isset($params['taskjobs_id'])) {
         exit;
      }

      $query = "SELECT DISTINCT plugin_fusioninventory_taskjobstates_id, id, date, state, comment
      FROM (
         SELECT logs.plugin_fusioninventory_taskjobstates_id, logs.id, logs.date, logs.state,
            logs.comment
         FROM glpi_plugin_fusioninventory_taskjoblogs logs
         INNER JOIN glpi_plugin_fusioninventory_taskjobstates status
            ON status.id = logs.plugin_fusioninventory_taskjobstates_id
            AND status.plugin_fusioninventory_taskjobs_id = '".$params['taskjobs_id']."'
         WHERE status.items_id = '".$params['items_id']."'
            AND status.itemtype = 'Computer'
         ORDER BY logs.id DESC
      ) as t1
      GROUP BY plugin_fusioninventory_taskjobstates_id
      ORDER BY date ASC";

      $query_res = $DB->query($query);
      $i = 0;
      while ($row = $DB->fetch_assoc($query_res)) {
         $row['comment']= self::processComment($row['state'], $row['comment']);

         $res[$i]['logs_id']     = $row['id'];
         $res[$i]['type']        = "group";
         $res[$i]['log']         = "";
         $res[$i]['comment']     = $row['comment'];
         $res[$i]['state']       = $row['state'];
         $res[$i]['date']        = $row['date'];
         $res[$i]['status_id']   = $row['plugin_fusioninventory_taskjobstates_id'];
         $res[$i]['iconCls']     = "no-icon";
         $res[$i]['cls']         = "group";
         $i++;
      }

      return json_encode($res);
   }



   static function getTaskJobLogsDatasTreeNode($params) {
      global $DB;

      $res = array();

      if (!isset($params['status_id'])) {
         exit;
      }

      $query = "SELECT id, state, comment, date
      FROM glpi_plugin_fusioninventory_taskjoblogs
      WHERE plugin_fusioninventory_taskjobstates_id = '".$params['status_id']."'
      ORDER BY id ASC";
      $query_res = $DB->query($query);
      $i = 0;
      while ($row = $DB->fetch_assoc($query_res)) {
         $row['log'] = '';
         $logs_pos = strpos($row['comment'], "log:");
         if ($logs_pos !== FALSE) {
            $row['log'] = substr($row['comment'], $logs_pos+4);
            $row['comment'] = substr($row['comment'], 0, $logs_pos);
         }
         $row['comment']= self::processComment($row['state'], $row['comment']);


         $res[$i]['logs_id']     = $row['id'];
         $res[$i]['type']        = "log";
         $res[$i]['log']         = $row['log'];
         $res[$i]['comment']     = $row['comment'];
         $res[$i]['state']       = $row['state'];
         $res[$i]['date']        = $row['date'];
         $res[$i]['status_id']   = 0;
         $res[$i]['leaf']        = TRUE;
         $res[$i]['iconCls']     = "no-icon";

         $i++;
      }

      return json_encode($res);
   }



   static function getTaskjobsAllDatasTree() {
      global $DB, $CFG_GLPI;

      $res = array();

      //get all tasks with job and status
      $i = 0;
      $query_tasks = "SELECT DISTINCT(tasks.name), tasks.id, tasks.date_scheduled as date
         FROM glpi_plugin_fusioninventory_deploytasks tasks
         INNER JOIN glpi_plugin_fusioninventory_deploytaskjobs jobs
            ON jobs.plugin_fusioninventory_deploytasks_id = tasks.id
            AND jobs.method = 'deployinstall' OR jobs.method = 'deployuninstall'
         INNER JOIN glpi_plugin_fusioninventory_taskjobstates status
            ON status.plugin_fusioninventory_taskjobs_id = jobs.id
         ORDER BY date DESC";
      $res_tasks = $DB->query($query_tasks);
      while ($row_tasks = $DB->fetch_assoc($res_tasks)) {
         $res[$i]['name'] = $row_tasks['name'];
         $res[$i]['type'] = "task";
         $res[$i]['date'] = $row_tasks['date'];
         $res[$i]['state'] = "null";
         $res[$i]['icon'] = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/ext/task.png";
         $res[$i]['progress'] = self::getTaskPercent($row_tasks['id']);

         //get all job for this task
         $j = 0;
         $query_jobs = "SELECT id, action
            FROM glpi_plugin_fusioninventory_deploytaskjobs
            WHERE plugin_fusioninventory_deploytasks_id = '".$row_tasks['id']."'";
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
                  FROM (
                     SELECT id, itemtype, items_id, state
                     FROM glpi_plugin_fusioninventory_taskjobstates
                     WHERE plugin_fusioninventory_taskjobs_id = '".$row_jobs['id']."'
                     ORDER BY id DESC
                  ) as t1
                  GROUP BY itemtype, items_id";
               $res_status = $DB->query($query_status);

               //no status for this job
               if ($DB->numrows($res_status) <= 0) {
                  unset ($res[$i]['children'][$j]);
                  //$res[$i]['children'][$j]['leaf'] = TRUE;
                  continue;
               }

               switch ($action_type) {
                  case 'Computer':
                     $row_status = $DB->fetch_assoc($res_status);

                     $res[$i]['children'][$j]['icon'] = $CFG_GLPI['root_doc'].
                             "/plugins/fusioninventory/pics/ext/computer.png";
                     $res[$i]['children'][$j]['leaf'] = TRUE; //final children
                     $res[$i]['children'][$j]['progress'] = $row_status['state'];
                     $res[$i]['children'][$j]['items_id'] = $row_status['items_id'];
                     $res[$i]['children'][$j]['taskjobs_id'] = $row_jobs['id'];

                     break;
                  case 'PluginFusioninventoryDeployGroup':
                     $res[$i]['children'][$j]['icon'] = $CFG_GLPI['root_doc'].
                              "/plugins/fusioninventory/pics/ext/group.png";
                     $res[$i]['children'][$j]['progress'] = self::getTaskPercent($row_jobs['id'],
                                                                                 'group');

                     $k = 0;
                     while ($row_status = $DB->fetch_assoc($res_status)) {
                        $computer = new Computer;
                        $computer->getFromDB($row_status['items_id']);

                        $res[$i]['children'][$j]['children'][$k]['name'] =
                                       $computer->getField('name');
                        $res[$i]['children'][$j]['children'][$k]['leaf'] = TRUE;
                        $res[$i]['children'][$j]['children'][$k]['type'] = "Computer";
                        $res[$i]['children'][$j]['children'][$k]['progress'] = $row_status['state'];
                        $res[$i]['children'][$j]['children'][$k]['icon'] = $CFG_GLPI['root_doc'].
                                       "/plugins/fusioninventory/pics/ext/computer.png";
                        $res[$i]['children'][$j]['children'][$k]['items_id'] =
                                       $row_status['items_id'];
                        $res[$i]['children'][$j]['children'][$k]['taskjobs_id'] = $row_jobs['id'];

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



   static function getTaskjobsDatasTreenode($params = array()) {
      global $DB, $CFG_GLPI;

      $items_id = $params['items_id'];
      $parent_type = $params['parent_type'];

      $res = array();
      $i = 0;
      switch($parent_type) {
         case 'all':
            //get all tasks
            $query_tasks = "SELECT DISTINCT(tasks.name), tasks.id, tasks.date_scheduled as date
               FROM glpi_plugin_fusioninventory_deploytasks tasks
               INNER JOIN glpi_plugin_fusioninventory_deploytaskjobs jobs
                  ON jobs.plugin_fusioninventory_deploytasks_id = tasks.id
                  AND jobs.method = 'deployinstall' OR jobs.method = 'deployuninstall'
               INNER JOIN glpi_plugin_fusioninventory_taskjobstates status
                  ON status.plugin_fusioninventory_taskjobs_id = jobs.id
               ORDER BY date DESC";
            $res_tasks = $DB->query($query_tasks);
            while ($row_tasks = $DB->fetch_assoc($res_tasks)) {
               $res[$i]['items_id'] = $row_tasks['id'];
               $res[$i]['name']     = $row_tasks['name'];
               $res[$i]['type']     = "task";
               $res[$i]['state']    = "null";
               $res[$i]['date']     = $row_tasks['date'];
               $res[$i]['icon']     = $CFG_GLPI['root_doc'].
                                         "/plugins/fusioninventory/pics/ext/task.png";
               $res[$i]['progress'] = self::getTaskPercent($row_tasks['id']);
               $i++;
            }
            break;
         case 'task':
            //get all job for this task
            $query_jobs = "SELECT id, action
               FROM glpi_plugin_fusioninventory_deploytaskjobs
               WHERE plugin_fusioninventory_deploytasks_id = '$items_id'";
            $res_jobs = $DB->query($query_jobs);
            while ($row_jobs = $DB->fetch_assoc($res_jobs)) {
               $actions = importArrayFromDB($row_jobs['action']);
               foreach ($actions as $action) {
                  $action_type = key($action);
                  $obj_action = new $action_type;
                  $obj_action->getFromDB($action[$action_type]);

                  $res[$i]['name']     = $obj_action->getField('name');
                  $res[$i]['items_id'] = $row_jobs['id'];
                  $res[$i]['type']     = $action_type;

                  //get all status for this job
                  $query_status = "SELECT id, items_id, state
                     FROM (
                        SELECT id, itemtype, items_id, state
                        FROM glpi_plugin_fusioninventory_taskjobstates
                        WHERE plugin_fusioninventory_taskjobs_id = '".$row_jobs['id']."'
                        ORDER BY id DESC
                     ) as t1
                     GROUP BY itemtype, items_id";
                  $res_status = $DB->query($query_status);

                  //no status for this job
                  if ($DB->numrows($res_status) <= 0) {
//                     unset ($res[$i]['children'][$j]);
                     //$res[$i]['children'][$j]['leaf'] = TRUE;
                     continue;
                  }

                  switch ($action_type) {
                     case 'Computer':
                        $row_status = $DB->fetch_assoc($res_status);

                        //get last job state
                        $query_jobs_state = "SELECT state
                        FROM glpi_plugin_fusioninventory_taskjoblogs
                        WHERE plugin_fusioninventory_taskjobstates_id = '".$row_status['id']."'
                        ORDER BY id DESC
                        LIMIT 1";

                        $res_jobs_state = $DB->query($query_jobs_state);
                        $row_jobs_state = $DB->fetch_assoc($res_jobs_state);

                        $res[$i]['icon'] = $CFG_GLPI['root_doc'].
                                             "/plugins/fusioninventory/pics/ext/computer.png";
                        $res[$i]['leaf'] = TRUE; //final children
                        $res[$i]['progress'] = $row_jobs_state['state'];
                        $res[$i]['items_id'] = $row_status['items_id'];
                        $res[$i]['taskjobs_id'] = $row_jobs['id'];

                        break;
                     case 'PluginFusioninventoryDeployGroup':
                        $res[$i]['icon'] = $CFG_GLPI['root_doc'].
                                             "/plugins/fusioninventory/pics/ext/group.png";
                        $res[$i]['progress'] = self::getTaskPercent($row_jobs['id'], 'group');

                  }
                  $i++;
               }
            }
            break;
         case 'PluginFusioninventoryDeployGroup':
            //get all status for this job
               $query_status = "SELECT id, items_id, state
                  FROM (
                     SELECT id, itemtype, items_id, state
                     FROM glpi_plugin_fusioninventory_taskjobstates
                     WHERE plugin_fusioninventory_taskjobs_id = '$items_id'
                     ORDER BY id DESC
                  ) as t1
                  GROUP BY itemtype, items_id";
               $res_status = $DB->query($query_status);
               while ($row_status = $DB->fetch_assoc($res_status)) {
                  //get last job state
                  $query_jobs_state = "SELECT state
                  FROM glpi_plugin_fusioninventory_taskjoblogs
                  WHERE plugin_fusioninventory_taskjobstates_id = '".$row_status['id']."'
                  ORDER BY id DESC
                  LIMIT 1";

                  $res_jobs_state = $DB->query($query_jobs_state);
                  $row_jobs_state = $DB->fetch_assoc($res_jobs_state);

                  $computer = new Computer;
                  $computer->getFromDB($row_status['items_id']);

                  $res[$i]['name'] = $computer->getField('name');
                  $res[$i]['leaf'] = TRUE;
                  $res[$i]['type'] = "Computer";
                  $res[$i]['progress'] = $row_jobs_state['state'];
                  $res[$i]['icon'] = $CFG_GLPI['root_doc'].
                                       "/plugins/fusioninventory/pics/ext/computer.png";
                  $res[$i]['items_id'] = $row_status['items_id'];
                  $res[$i]['taskjobs_id'] = $items_id;

                  $i++;
               }
            break;
      }

      return json_encode($res);
   }



   static function getTaskPercent($id, $type = 'task') {
      global $DB;

      $taskjob = new PluginFusioninventoryTaskjob();
      $taskjobstate = new PluginFusioninventoryTaskjobstate();

      if ($type == 'task') {
         $a_taskjobs = $taskjob->find("`plugin_fusioninventory_tasks_id`='".$id."'");


         //temporary fix for get a 100% progress when all the jobs for the task are done
         $finished = FALSE;
         foreach ($a_taskjobs as $job) {
            $finished = FALSE;
            $query_status = "SELECT id, items_id, state
               FROM (
                  SELECT id, itemtype, items_id, state
                  FROM glpi_plugin_fusioninventory_taskjobstates
                  WHERE plugin_fusioninventory_taskjobs_id = '".$job['id']."'
                  ORDER BY id DESC
               ) as t1
               GROUP BY itemtype, items_id";
            $res_status = $DB->query($query_status);
            $row_status = $DB->fetch_assoc($res_status);

            $query_jobs_state = "SELECT state
               FROM glpi_plugin_fusioninventory_taskjoblogs
               WHERE plugin_fusioninventory_taskjobstates_id = '".$row_status['id']."'
               ORDER BY id DESC
               LIMIT 1";
            $res_jobs_state = $DB->query($query_jobs_state);
            $row_jobs_state = $DB->fetch_assoc($res_jobs_state);
            if ($row_jobs_state['state'] != PluginFusioninventoryTaskjoblog::TASK_OK) {
               continue;
            }

            $finished = TRUE;
         }
         if ($finished) {
            return "100%";
         }

         $tmp = array_pop($a_taskjobs);
         $taskjobs_id = $tmp['id'];
      } elseif ($type == 'group') {
         $taskjobs_id = $id;
      }

      $percent = $taskjobstate->stateTaskjob($taskjobs_id, 0, '');
      return ceil($percent)."%";
   }
}

?>
