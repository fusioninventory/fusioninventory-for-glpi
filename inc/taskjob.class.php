<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

class PluginFusinvdeployTaskjob extends CommonDBTM {

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function getAllDatas($params) {
      global $DB;

      $tasks_id = $params['tasks_id'];

      $sql = " SELECT *
               FROM `".$this->getTable()."`
               WHERE `plugin_fusinvdeploy_tasks_id` = '$tasks_id'
               AND method = 'deployinstall' OR method = 'deployuninstall'";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
      while($row = $DB->fetch_assoc($res)) {
         $row['packages'] = importArrayFromDB($row['definition']);
         $row['actions'] = importArrayFromDB($row['action']);

         $temp_tasks[] = $row;
      }



      $i = 0;
      foreach ($temp_tasks as $key => $task) {
         foreach ($task['actions'] as $action) {
            foreach ($task['packages'] as $package) {

               $tmp = array_keys($action);
               $action_type = $tmp[0];


               $json['tasks'][$i]['group_id'] = $action['PluginFusinvdeployGroup'];
               $json['tasks'][$i]['package_id'] = $package['PluginFusinvdeployPackage'];

               $json['tasks'][$i]['method'] = $task['method'];
               $json['tasks'][$i]['comment'] = $task['comment'];
               $json['tasks'][$i]['retry_nb'] = $task['retry_nb'];
               $json['tasks'][$i]['retry_time'] = $task['retry_time'];

               $json['tasks'][$i]['action_type'] = $action_type;
               $json['tasks'][$i]['action_selection'] = $action[$action_type];
               $i++;
            }
         }
      }



      return json_encode($json);
   }

   function saveDatas($params)  {
      global $DB;

      $tasks_id = $params['tasks_id'];
      $tasks = json_decode($params['tasks']);
      logDebug($tasks);

      //remove old jobs from task
      $query = "DELETE FROM ".$this->getTable()."
      WHERE plugin_fusinvdeploy_tasks_id = '".$tasks_id."'";
      $res = $DB->query($query);

      //get plugin id
      $plug = new Plugin;
      $plug->getFromDBbyDir('fusinvdeploy');
      $plugins_id = $plug->getField('id');

      //insert new rows
      $sql_tasks = array();
      $i = 0;

      foreach($tasks as $task) {
         $task = get_object_vars($task);

         //encode action and definition
         $action = exportArrayToDB(array(array($task['action_type'] => $task['action_selection'])));
         $definition = exportArrayToDB(array(array('PluginFusinvdeployPackage' => $task['package_id'])));

         $sql_tasks[] = "INSERT INTO ".$this->getTable()."
         (
            plugin_fusinvdeploy_tasks_id, name, date_creation,
            plugins_id, method, definition, action,
            retry_nb, retry_time
         ) VALUES (
            '$tasks_id', 'job_".$tasks_id."_$i', CURDATE(),
            '$plugins_id', '".$task['method']."', '$definition', '$action',
            '".$task['retry_nb']."', '".$task['retry_time']."'
         )";
         $i++;
      }
      foreach($sql_tasks as $query) {
         $res = $DB->query($query);
      }
   }
}

?>
