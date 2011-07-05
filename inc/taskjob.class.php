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

      $group_id = $params['group_id'];

      $sql = " SELECT id, name, date_creation, retry_nb,
               retry_time, definition
               FROM `".$this->getTable()."`
               WHERE `plugin_fusinvdeploy_tasks_id` = '$group_id'
               AND method = 'deployinstall' OR method = 'deployuninstall'";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
      while($row = $DB->fetch_array($res)) {
         $definition = importArrayFromDB($row['definition']);
         $row['package'] = $definition[0]['PluginFusinvdeployPackage'];
         $row['group'] = $group_id;
         $json['tasks'][] = $row;
      }

      return json_encode($json);
   }
}

?>
