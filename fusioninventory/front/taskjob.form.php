<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

$mytaskjob = new PluginFusioninventoryTaskjob();

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins",
             "fusioninventory","tasks");

PluginFusioninventoryProfile::checkRight("fusioninventory", "task", "r");

if (isset($_POST['definition_add'])) {
   // * Add a definition
   $mytaskjob->getFromDB($_POST['id']);
   $a_listdef = importArrayFromDB($mytaskjob->fields['definition']);
   $add = 1;
   foreach ($a_listdef as $dataDB) {
      if (isset($dataDB[$_POST['DefinitionType']]) 
              AND $dataDB[$_POST['DefinitionType']] == $_POST['definitionselectiontoadd']) {
         $add = 0;
         break;
      }
   }
   if ($add == '1') {
      if (isset($_POST['DefinitionType']) 
              AND $_POST['DefinitionType'] != '') {
         $a_listdef[] = array($_POST['DefinitionType']=>$_POST['definitionselectiontoadd']);
      }
   }
   $input = array();
   $input['id'] = $_POST['id'];
   $input['definition'] = exportArrayToDB($a_listdef);
   $mytaskjob->update($input);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['action_add'])) {
   // * Add an action
   $mytaskjob->getFromDB($_POST['id']);
   $a_listact = importArrayFromDB($mytaskjob->fields['action']);
   $add = 1;
   foreach ($a_listact as $dataDB) {
      if (isset($dataDB[$_POST['ActionType']])
              AND $dataDB[$_POST['ActionType']] == $_POST['actionselectiontoadd']) {
         $add = 0;
         break;
      }
   }
   if ($add == '1') {
      if (isset($_POST['ActionType']) 
              AND $_POST['ActionType'] != '') {
         $a_listact[] = array($_POST['ActionType']=>$_POST['actionselectiontoadd']);
      }
   }
   $input = array();
   $input['id'] = $_POST['id'];
   $input['action'] = exportArrayToDB($a_listact);
   $mytaskjob->update($input);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['definition_delete'])) {
   // * Delete definition
   $mytaskjob->getFromDB($_POST['id']);
   $a_listdef = importArrayFromDB($mytaskjob->fields['definition']);

   foreach ($_POST['definition_to_delete'] as $itemdelete) {
      $datadel = explode('-', $itemdelete);
      foreach ($a_listdef as $num=>$dataDB) {
         if (isset($dataDB[$datadel[0]]) AND $dataDB[$datadel[0]] == $datadel[1]) {
            unset($a_listdef[$num]);
         }
      }
   }
   $input = array();
   $input['id'] = $_POST['id'];
   $input['definition'] = exportArrayToDB($a_listdef);
   $mytaskjob->update($input);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['action_delete'])) {
   // * Delete action
   $mytaskjob->getFromDB($_POST['id']);
   $a_listact = importArrayFromDB($mytaskjob->fields['action']);

   foreach ($_POST['action_to_delete'] as $itemdelete) {
      $datadel = explode('-', $itemdelete);
      foreach ($a_listact as $num=>$dataDB) {
         if (isset($dataDB[$datadel[0]]) AND $dataDB[$datadel[0]] == $datadel[1]) {
            unset($a_listact[$num]);
         }
      }
   }
   $input = array();
   $input['id'] = $_POST['id'];
   $input['action'] = exportArrayToDB($a_listact);
   $mytaskjob->update($input);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['quickform'])) {
   $pluginFusioninventoryTask = new PluginFusioninventoryTask();

   if (isset($_POST['update'])) {
      $mytaskjob->getFromDB($_POST['id']);
      $pluginFusioninventoryTask->getFromDB($mytaskjob->fields['plugin_fusioninventory_tasks_id']);
   }
   
   $inputtaskjob = array();
   $inputtask = array();
   if (isset($_POST['update'])) {
      $inputtaskjob['id'] = $_POST['id'];
      $inputtask['id'] = $mytaskjob->fields['plugin_fusioninventory_tasks_id'];
   }

   $inputtaskjob['name'] = $_POST['name'];
   if (isset($_POST['add']) OR $pluginFusioninventoryTask->fields['name'] == '') {
      $inputtask['name'] = $_POST['name'];
   }
   $inputtask['is_active'] = $_POST['is_active'];
   $inputtaskjob['method'] = $_POST['method'];
   $inputtask['communication'] = $_POST['communication'];
   $inputtask['periodicity_count'] = $_POST['periodicity_count'];
   $inputtask['periodicity_type'] = $_POST['periodicity_type'];

   $inputtask['entities_id'] = $_SESSION['glpiactive_entity'];
   $inputtaskjob['entities_id'] = $_SESSION['glpiactive_entity'];

   if (isset($_POST['update'])) {
      $mytaskjob->update($inputtaskjob);
      $pluginFusioninventoryTask->update($inputtask);
      glpi_header($_SERVER['HTTP_REFERER']);
   } else if (isset($_POST['add'])) {
      if (!isset($_POST['entities_id'])) {
         $_POST['entities_id'] = $_SESSION['glpidefault_entity'];
      }
      // Get entity of task
      if (isset($_POST['plugin_fusioninventory_tasks_id'])) {
         $pluginFusioninventoryTask = new PluginFusioninventoryTask();
         $pluginFusioninventoryTask->getFromDB($_POST['plugin_fusioninventory_tasks_id']);
         $entities_list = getSonsOf('glpi_entities', $pluginFusioninventoryTask->fields['entities_id']);
         if (!in_array($_POST['entities_id'], $entities_list)) {
            $_POST['entities_id'] = $pluginFusioninventoryTask->fields['entities_id'];
         }
      } else {
         $inputtask['date_scheduled'] = date("Y-m-d H:i:s");
         $task_id = $pluginFusioninventoryTask->add($inputtask);
         $inputtaskjob['plugin_fusioninventory_tasks_id'] = $task_id;
      }
      if (isset($_POST['method_id'])) {
         $_POST['method']  = $_POST['method_id'];
      }
      $inputtaskjob['plugins_id'] = $_POST['method-'.$_POST['method']];
      $taskjobs_id = $mytaskjob->add($inputtaskjob);

      $redirect = $_SERVER['HTTP_REFERER'];
      $redirect = str_replace('&id=0', '&id='.$taskjobs_id, $redirect);
      glpi_header($redirect);
   }
} else if (isset($_POST['taskjobstoforcerun'])) {
   // * Force running many tasks (wizard)
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");
   $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
   $_SESSION["plugin_fusioninventory_forcerun"] = array();
   foreach ($_POST['taskjobstoforcerun'] as $taskjobs_id) {
      $PluginFusioninventoryTaskjob->getFromDB($taskjobs_id);
      $uniqid = $PluginFusioninventoryTaskjob->forceRunningTask($PluginFusioninventoryTaskjob->fields['plugin_fusioninventory_tasks_id']);
      $_SESSION["plugin_fusioninventory_forcerun"][$taskjobs_id] = $uniqid;
   }
   unset($_SESSION["MESSAGE_AFTER_REDIRECT"]);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['add']) || isset($_POST['update'])) {
   // * Add and update taskjob
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task", "w");

   if (isset($_POST['method_id'])) {
      $_POST['method']  = $_POST['method_id'];
   }
   $_POST['plugins_id'] = $_POST['method-'.$_POST['method']];

   if (isset($_POST['add'])) {
      if (!isset($_POST['entities_id'])) {
         $_POST['entities_id'] = $_SESSION['glpidefault_entity'];
      }
      // Get entity of task
      $pluginFusioninventoryTask = new PluginFusioninventoryTask();
      $pluginFusioninventoryTask->getFromDB($_POST['plugin_fusioninventory_tasks_id']);
      $entities_list = getSonsOf('glpi_entities', $pluginFusioninventoryTask->fields['entities_id']);
      if (!in_array($_POST['entities_id'], $entities_list)) {
         $_POST['entities_id'] = $pluginFusioninventoryTask->fields['entities_id'];
      }
      $_POST['execution_id'] = $pluginFusioninventoryTask->fields['execution_id'];
      $mytaskjob->add($_POST);
   } else {
      $mytaskjob->update($_POST);
   }
   glpi_header($_SERVER['HTTP_REFERER']);   

} else if (isset($_POST["delete"])) {
   // * delete taskjob
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
   $pFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
   $mytaskjobstatus->getFromDB($_POST['taskjobstatus_id']);
   $jobstatus = $mytaskjobstatus->fields;
   $a_taskjobstatus = $mytaskjobstatus->find("`uniqid`='".$mytaskjobstatus->fields['uniqid']."'");
   foreach($a_taskjobstatus as $data) {
      if ($data['state'] != PluginFusioninventoryTaskjobstatus::FINISHED) {
         $mytaskjobstatus->changeStatusFinish($data['id'], 0, '', 1, "Action cancelled by user", 0, 0);
      }
   }
   
   $pFusioninventoryTaskjob->getFromDB($jobstatus['plugin_fusioninventory_taskjobs_id']);
   $pFusioninventoryTaskjob->reinitializeTaskjobs($pFusioninventoryTaskjob->fields['plugin_fusioninventory_tasks_id']);

   glpi_header($_SERVER['HTTP_REFERER']);
}

if (strstr($_SERVER['HTTP_REFERER'], "wizard.php")) {
   glpi_header($_SERVER['HTTP_REFERER']."&id=".$_GET['id']);
} else {
   $mytaskjob->redirectTask($_GET['id']);
}

commonFooter();

?>
