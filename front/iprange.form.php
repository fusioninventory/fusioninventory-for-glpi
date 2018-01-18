<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the IP range form.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

include ("../../../inc/includes.php");

$iprange = new PluginFusioninventoryIPRange();

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"], "admin",
             "pluginfusioninventorymenu", "iprange");

Session::checkRight('plugin_fusioninventory_iprange', READ);

PluginFusioninventoryMenu::displayMenu("mini");

if (isset ($_POST["add"])) {
   Session::checkRight('plugin_fusioninventory_iprange', CREATE);
   if ($iprange->checkip($_POST)) {
      $_POST['ip_start']  = (int)$_POST['ip_start0'].".".(int)$_POST['ip_start1'].".";
      $_POST['ip_start'] .= (int)$_POST['ip_start2'].".".(int)$_POST['ip_start3'];
      $_POST['ip_end']    = (int)$_POST['ip_end0'].".".(int)$_POST['ip_end1'].".";
      $_POST['ip_end']   .= (int)$_POST['ip_end2'].".".(int)$_POST['ip_end3'];
      $iprange->add($_POST);
      Html::back();
   } else {
      Html::back();
   }
} else if (isset ($_POST["update"])) {
   if (isset($_POST['communication'])) {
      //task permanent update
      $task = new PluginFusioninventoryTask();
      $taskjob = new PluginFusioninventoryTaskjob();
      $task->getFromDB($_POST['task_id']);
      $input_task = [];
      $input_task['id'] = $task->fields['id'];
      $taskjob->getFromDB($_POST['taskjob_id']);
      $input_taskjob                   = [];
      $input_taskjob['id']             = $taskjob->fields['id'];
      $input_task["is_active"]         = $_POST['is_active'];
      $input_task["periodicity_count"] = $_POST['periodicity_count'];
      $input_task["periodicity_type"]  = $_POST['periodicity_type'];
      if (!empty($_POST['action'])) {
         $a_actionDB                                 = [];
         $a_actionDB[]['PluginFusioninventoryAgent'] = $_POST['action'];
         $input_taskjob["action"]                    = exportArrayToDB($a_actionDB);
      } else {
         $input_taskjob["action"] = '';
      }
      $a_definition = [];
      $a_definition[]['PluginFusioninventoryIPRange'] = $_POST['iprange'];
      $input_taskjob['definition'] = exportArrayToDB($a_definition);
      $input_task["communication"] = $_POST['communication'];

      $task->update($input_task);
      $taskjob->update($input_taskjob);
   } else {
      Session::checkRight('plugin_fusioninventory_iprange', UPDATE);
      if ($iprange->checkip($_POST)) {
         $_POST['ip_start']  = (int)$_POST['ip_start0'].".".(int)$_POST['ip_start1'].".";
         $_POST['ip_start'] .= (int)$_POST['ip_start2'].".".(int)$_POST['ip_start3'];
         $_POST['ip_end']    = (int)$_POST['ip_end0'].".".(int)$_POST['ip_end1'].".";
         $_POST['ip_end']   .= (int)$_POST['ip_end2'].".".(int)$_POST['ip_end3'];
         $iprange->update($_POST);
      }
   }
   Html::back();
} else if (isset ($_POST["purge"])) {
   if (isset($_POST['communication'])) {
      $task = new PluginFusioninventoryTask();
      $task->delete(['id' => $_POST['task_id']], 1);
      $_SERVER['HTTP_REFERER'] = str_replace("&allowcreate=1", "", $_SERVER['HTTP_REFERER']);
      Html::back();
   } else {
      Session::checkRight('plugin_fusioninventory_iprange', PURGE);

      $iprange->delete($_POST);
      Html::redirect(Toolbox::getItemTypeSearchURL('PluginFusioninventoryIPRange'));
   }
}

$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}
$allowcreate = 0;
if (isset($_GET['allowcreate'])) {
   $allowcreate = $_GET['allowcreate'];
}

$iprange->display(['id' => $id]);

Html::footer();

