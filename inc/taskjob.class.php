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
               AND (method = 'deployinstall' OR method = 'deployuninstall')";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
      $temp_tasks = array();
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

               $json['tasks'][$i]['package_id'] = $package['PluginFusinvdeployPackage'];

               $json['tasks'][$i]['method'] = $task['method'];
               $json['tasks'][$i]['comment'] = $task['comment'];
               $json['tasks'][$i]['retry_nb'] = $task['retry_nb'];
               $json['tasks'][$i]['retry_time'] = $task['retry_time'];

               $json['tasks'][$i]['action_type'] = $action_type;
               $json['tasks'][$i]['action_selection'] = $action[$action_type];

               $obj_action = new $action_type;
               $obj_action->getFromDB($action[$action_type]);
               $json['tasks'][$i]['action_name'] = $obj_action->getField('name');

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
            '$tasks_id', 'job_".$tasks_id."_$i', NOW(),
            '$plugins_id', '".$task['method']."', '$definition', '$action',
            '".$task['retry_nb']."', '".$task['retry_time']."'
         )";
         $i++;
      }
      foreach($sql_tasks as $query) {
         $res = $DB->query($query);
      }
   }

   static function getActionTypes() {
      global $LANG;

      return array(
         array(
            'name' => $LANG['Menu'][0],
            'value' => 'Computer',
         ),
         array(
            'name' => $LANG['plugin_fusinvdeploy']['group'][0],
            'value' => 'PluginFusinvdeployGroup',
         )
      );
   }

   static function getActions($params) {
      global $DB;

      $res = '';
      if (!isset($params['get'])) exit;
      switch($params['get']) {
         case "type";
            $res = array(
               'action_types' =>self::getActionTypes()
            );
            $res = json_encode($res);
            break;
         case "selection";
            switch ($params['type']) {
               case 'Computer':
                  $query = "SELECT id, name FROM glpi_computers";
                  if (isset($params['query'])) {
                     $like = mysql_escape_string($params['query']);
                     $query .= " WHERE name LIKE '%$like'";
                  }
                  $query .= " ORDER BY name ASC";
                  $query_res = $DB->query($query);
                  $i = 0;
                  while ($row = $DB->fetch_array($query_res)) {
                     $res['action_selections'][$i]['id'] = $row['id'];
                     $res['action_selections'][$i]['name'] = $row['name'];
                     $i++;
                  }

                  $res = json_encode($res);
                  break;
               case 'PluginFusinvdeployGroup':
                  $res = PluginFusinvdeployGroup::getAllDatas('action_selections');
                  break;
            }

            break;
         case "oneSelection":

            break;
         default:
            $res = '';
      }

      return $res;
   }
}

?>
