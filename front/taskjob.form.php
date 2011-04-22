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
   Original Author of file: David Durieux
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

$mytaskjob = new PluginFusioninventoryTaskjob();

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins",
             "fusioninventory","tasks");

PluginFusioninventoryProfile::checkRight("fusioninventory", "task", "r");

if (isset($_POST['add']) || isset($_POST['update'])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task", "w");

   if (isset($_POST['method_id'])) {
      $_POST['method']  = $_POST['method_id'];
   }
   $_POST['plugins_id'] = $_POST['method-'.$_POST['method']];

   foreach (array('definitionlist' => 'definition', 'actionlist' => 'action') as $list => $tosave) {
      if (!empty($_POST[$list])) {
         $a_definitionlist   = explode(',', $_POST[$list]);
         $a_definitionlistDB = array();
         foreach ($a_definitionlist as $data) {
            $dataDB          = explode('-&gt;', $data);
            if (isset($dataDB[1]) AND $dataDB > 0) {
               $a_definitionlistDB[][$dataDB[0]] = $dataDB[1];
            }
         }
         $_POST[$tosave] = exportArrayToDB($a_definitionlistDB);

      }
   }

   logDebug($_POST);
   if (isset($_POST['add'])) {
      $mytaskjob->add($_POST);
   } else {
      $mytaskjob->update($_POST);
   }
   glpi_header($_SERVER['HTTP_REFERER']);
   

} else if (isset($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task", "w");

   $mytaskjob->delete($_POST);
   glpi_header(getItemTypeFormURL('PluginFusioninventoryTask')."?id=".
                                     $_POST['plugin_fusioninventory_tasks_id']);
                                     
} elseif (isset($_POST['itemaddaction'])) {
   $array                     = explode("||", $_POST['methodaction']);
   $module                    = $array[0];
   $method                    = $array[1];
   // Add task
   $mytask = new PluginFusioninventoryTask();
   $input                     = array();
   $input['name']             = $method;

   $task_id = $mytask->add($input);
   
   // Add job with this device
   $input = array();
   $input['plugin_fusioninventory_tasks_id'] = $task_id;
   $input['name']                            = $method;
   $input['date_scheduled']                  = $_POST['date_scheduled'];

   $input['plugins_id']                      = PluginFusioninventoryModule::getModuleId($module);
   $input['method']                          = $method;
   $a_selectionDB                            = array();
   $a_selectionDB[][$_POST['itemtype']]      = $_POST['items_id'];
   $input['definition']                      = exportArrayToDB($a_selectionDB);
   
   $taskname = "plugin_".$module."_task_selection_type_".$method;
   if (is_callable($taskname)) {
      $input['selection_type'] = call_user_func($taskname, $_POST['itemtype']);
   }
   $mytaskjob->add($input);
   // Upsate task to activate it
   $mytask->getFromDB($task_id);
   $mytask->fields['is_active'] = "1";
   $mytask->update($mytask->fields);
   // force running this job (?)

   glpi_header($_SERVER['HTTP_REFERER']);
   
} elseif (isset($_POST['forceend'])) {
   $mytaskjobstatus = new PluginFusioninventoryTaskjobstatus();
   $mytaskjobstatus->getFromDB($_POST['taskjobstatus_id']);
   $a_taskjobstatus = $mytaskjobstatus->find("`uniqid`='".$mytaskjobstatus->fields['uniqid']."'");
   foreach($a_taskjobstatus as $data) {

      if ($data['state'] != PluginFusioninventoryTaskjobstatus.FINISHED) {
         $mytaskjobstatus->changeStatusFinish($data['id'], 0, '', 1, "Action cancelled by user");
      }
   }
   $mytaskjob->getFromDB($_POST['taskjobs_id']);
   $mytaskjob->fields['status'] = 1;
   $mytaskjob->update($mytaskjob->fields);

   glpi_header($_SERVER['HTTP_REFERER']);
}

$mytaskjob->redirectTask($_GET['id']);

commonFooter();

?>