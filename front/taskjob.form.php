<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","tasks");

PluginFusioninventoryProfile::checkRight("fusioninventory", "task","r");


if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");

   if (isset($_POST['method_id'])) {
      $_POST['method'] = $_POST['method_id'];
   }
   $_POST['plugins_id'] = $_POST['method-'.$_POST['method']];

   if (!empty($_POST['definitionlist'])) {
      $a_definitionlist = explode(',', $_POST['definitionlist']);
      foreach ($a_definitionlist as $num=>$data) {
         $dataDB = explode('-&gt;', $data);
         if (isset($dataDB[1]) AND $dataDB > 0) {
            $a_definitionlistDB[][$dataDB[0]] = $dataDB[1];
         }
      }
      $_POST['definition'] = exportArrayToDB($a_definitionlistDB);
   }
   if (!empty($_POST['actionlist'])) {
      $a_actionlist = explode(',', $_POST['actionlist']);
      foreach ($a_actionlist as $num=>$data) {
         $dataDB = explode('-&gt;', $data);
         if (isset($dataDB[1]) AND $dataDB > 0) {
            $a_actionlistDB[][$dataDB[0]] = $dataDB[1];
         }
      }
      $_POST['action'] = exportArrayToDB($a_actionlistDB);
   }


   $PluginFusioninventoryTaskjob->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");

   $PluginFusioninventoryTaskjob->delete($_POST);
   glpi_header($CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/task.php");
} else if (isset($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");


   if (!empty($_POST['definitionlist'])) {
      $a_definitionlist = explode(',', $_POST['definitionlist']);
      foreach ($a_definitionlist as $num=>$data) {
         $dataDB = explode('-&gt;', $data);
         if (isset($dataDB[1]) AND $dataDB > 0) {
            $a_definitionlistDB[][$dataDB[0]] = $dataDB[1];
         }
      }
      $_POST['definition'] = exportArrayToDB($a_definitionlistDB);
   }
   if (!empty($_POST['actionlist'])) {
      $a_actionlist = explode(',', $_POST['actionlist']);
      foreach ($a_actionlist as $num=>$data) {
         $dataDB = explode('-&gt;', $data);
         if (isset($dataDB[1]) AND $dataDB > 0) {
            $a_actionlistDB[][$dataDB[0]] = $dataDB[1];
         }
      }
      $_POST['action'] = exportArrayToDB($a_actionlistDB);
   }
   $PluginFusioninventoryTaskjob->update($_POST);

   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['itemaddaction'])) {
   $array = explode("||", $_POST['methodaction']);
   $module = $array[0];
   $method = $array[1];
   // Add task
   $PluginFusioninventoryTask = new PluginFusioninventoryTask;
   $input = array();
   $input['name'] = $method;

   $task_id = $PluginFusioninventoryTask->add($input);
   
   // Add job with this device
   $input = array();
   $input['plugin_fusioninventory_tasks_id'] = $task_id;
   $input['name'] = $method;
   $input['date_scheduled'] = $_POST['date_scheduled'];

   $input['plugins_id'] = PluginFusioninventoryModule::getModuleId($module);
   $input['method'] = $method;
   $a_selectionDB = array();
   $a_selectionDB[][$_POST['itemtype']] = $_POST['items_id'];
   $input['definition'] = exportArrayToDB($a_selectionDB);
   $input['selection_type'] =
      call_user_func("plugin_".$module."_task_selection_type_".$method, $_POST['itemtype']);

   $PluginFusioninventoryTaskjob->add($input);
   // Upsate task to activate it
   $PluginFusioninventoryTask->getFromDB($task_id);
   $PluginFusioninventoryTask->fields['is_active'] = "1";
   $PluginFusioninventoryTask->update($PluginFusioninventoryTask->fields);
   // force running this job (?)

   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['forceend'])) {
   $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
   $PluginFusioninventoryTaskjobstatus->getFromDB($_POST['taskjobstatus_id']);
   if ($PluginFusioninventoryTaskjobstatus->fields['state'] != "3") {
      $PluginFusioninventoryTaskjobstatus->changeStatusFinish($_POST['taskjobstatus_id'],
                                                                    0,
                                                                    '',
                                                                    1,
                                                                    "Action cancelled by user");
      $PluginFusioninventoryTaskjob->getFromDB($_POST['taskjobs_id']);
      $PluginFusioninventoryTaskjob->fields['status'] = 1;
      $PluginFusioninventoryTaskjob->update($PluginFusioninventoryTaskjob->fields);
   }
   
   glpi_header($_SERVER['HTTP_REFERER']);
}

$PluginFusioninventoryTaskjob->redirectTask($_GET['id']);

commonFooter();

?>