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
      global $DB, $LANG;

      $tasks_id = $params['tasks_id'];

      $sql = " SELECT id, name, date_creation, retry_nb,
               retry_time, definition, action
               FROM `".$this->getTable()."`
               WHERE `plugin_fusinvdeploy_tasks_id` = '$tasks_id'
               AND method = 'deployinstall' OR method = 'deployuninstall'";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
      while($row = $DB->fetch_assoc($res)) {
         $row['packages'] = importArrayFromDB($row['definition']);
         $row['groups'] = importArrayFromDB($row['action']);

         $temp_tasks[] = $row;
      }

      //printCleanArray($temp_tasks);

      $i = 0;
      foreach ($temp_tasks as $key => $task) {
         foreach ($task['groups'] as $group) {
            foreach ($task['packages'] as $package) {
               $group_obj = new PluginFusinvdeployGroup;
               $group_obj->getFromDB($group['PluginFusinvdeployGroup']);
               $json['tasks'][$i]['group_id'] = $group['PluginFusinvdeployGroup'];
               $json['tasks'][$i]['group_name'] = $group_obj->getName();

               $package_obj = new PluginFusinvdeployPackage;
               $package_obj->getFromDB($package['PluginFusinvdeployPackage']);
               $json['tasks'][$i]['package_id'] = $package['PluginFusinvdeployPackage'];
               $json['tasks'][$i]['package_name'] = $package_obj->getName();

               $json['tasks'][$i]['date_creation'] = $task['date_creation'];
               $json['tasks'][$i]['retry_nb'] = $task['retry_nb'];
               $json['tasks'][$i]['retry_time'] = $task['retry_time'];
               $i++;
            }
         }
      }



      return json_encode($json);
   }
}

?>
