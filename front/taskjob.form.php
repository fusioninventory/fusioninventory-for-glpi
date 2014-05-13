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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

$mytaskjob = new PluginFusioninventoryTaskjob();

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"], "plugins",
             "fusioninventory", "tasks");

PluginFusioninventoryProfile::checkRight("task", "r");

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
   Html::back();
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
   Html::back();
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
   Html::back();
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
   Html::back();
} else if (isset($_POST['quickform'])) {
   $pfTask = new PluginFusioninventoryTask();

   if (isset($_POST['update'])) {
      $mytaskjob->getFromDB($_POST['id']);
      $pfTask->getFromDB($mytaskjob->fields['plugin_fusioninventory_tasks_id']);
   }

   $inputtaskjob = array();
   $inputtask = array();
   if (isset($_POST['update'])) {
      $inputtaskjob['id'] = $_POST['id'];
      $inputtask['id'] = $mytaskjob->fields['plugin_fusioninventory_tasks_id'];
   }

   $inputtaskjob['name'] = $_POST['name'];
   if (isset($_POST['add']) OR $pfTask->fields['name'] == '') {
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
      $pfTask->update($inputtask);
      Html::back();
   } else if (isset($_POST['add'])) {
      if (!isset($_POST['entities_id'])) {
         $_POST['entities_id'] = $_SESSION['glpidefault_entity'];
      }
      // Get entity of task
      if (isset($_POST['plugin_fusioninventory_tasks_id'])) {
         $pfTask = new PluginFusioninventoryTask();
         $pfTask->getFromDB($_POST['plugin_fusioninventory_tasks_id']);
         $entities_list = getSonsOf('glpi_entities', $pfTask->fields['entities_id']);
         if (!in_array($_POST['entities_id'], $entities_list)) {
            $_POST['entities_id'] = $pfTask->fields['entities_id'];
         }
      } else {
         $inputtask['date_scheduled'] = date("Y-m-d H:i:s");
         $task_id = $pfTask->add($inputtask);
         $inputtaskjob['plugin_fusioninventory_tasks_id'] = $task_id;
      }
      if (isset($_POST['method_id'])) {
         $_POST['method']  = $_POST['method_id'];
      }
      $inputtaskjob['plugins_id'] = $_POST['method-'.$_POST['method']];
      $taskjobs_id = $mytaskjob->add($inputtaskjob);

      $redirect = $_SERVER['HTTP_REFERER'];
      $redirect = str_replace('&id=0', '&id='.$taskjobs_id, $redirect);
      Html::redirect($redirect);
   }
} else if (isset($_POST['taskjobstoforcerun'])) {
   // * Force running many tasks (wizard)
   PluginFusioninventoryProfile::checkRight("task", "w");
   $pfTaskjob = new PluginFusioninventoryTaskjob();
   $_SESSION["plugin_fusioninventory_forcerun"] = array();
   foreach ($_POST['taskjobstoforcerun'] as $taskjobs_id) {
      $pfTaskjob->getFromDB($taskjobs_id);
      $uniqid = $pfTaskjob->forceRunningTask($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);
      $_SESSION["plugin_fusioninventory_forcerun"][$taskjobs_id] = $uniqid;
   }
   unset($_SESSION["MESSAGE_AFTER_REDIRECT"]);
   Html::back();
} else if (isset($_POST['add']) || isset($_POST['update'])) {
   // * Add and update taskjob
   PluginFusioninventoryProfile::checkRight("task", "w");


   if (isset($_POST['add'])) {

      if (!isset($_POST['entities_id'])) {
         $_POST['entities_id'] = $_SESSION['glpidefault_entity'];
      }
      // Get entity of task
      $pfTask = new PluginFusioninventoryTask();
      $pfTask->getFromDB($_POST['plugin_fusioninventory_tasks_id']);
      $entities_list = getSonsOf('glpi_entities', $pfTask->fields['entities_id']);
      if (!in_array($_POST['entities_id'], $entities_list)) {
         $_POST['entities_id'] = $pfTask->fields['entities_id'];
      }
      $_POST['execution_id'] = $pfTask->fields['execution_id'];
      $mytaskjob->add($_POST);
   } else {
      if (isset($_POST['method_id'])) {
         $_POST['method']  = $_POST['method_id'];
      }
      $_POST['plugins_id'] = $_POST['method-'.$_POST['method']];
      $mytaskjob->update($_POST);
   }
   Html::back();

} else if (isset($_POST["delete"])) {
   // * delete taskjob
   PluginFusioninventoryProfile::checkRight("task", "w");

   $mytaskjob->delete($_POST);
   Html::redirect(Toolbox::getItemTypeFormURL('PluginFusioninventoryTask')."?id=".
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

   Html::back();

} elseif (isset($_POST['forceend'])) {
   if (isset($_POST['taskjobstates_id'])) {
      $mytaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $mytaskjobstate->getFromDB($_POST['taskjobstates_id']);
      $jobstate = $mytaskjobstate->fields;
      $a_taskjobstates = $mytaskjobstate->find("`uniqid`='".$mytaskjobstate->fields['uniqid']."'");
      foreach($a_taskjobstates as $data) {
         if ($data['state'] != PluginFusioninventoryTaskjobstate::FINISHED) {
            $mytaskjobstate->changeStatusFinish($data['id'],
                                                0, '', 1, "Action cancelled by user", 0, 0);
         }
      }
      $pfTaskjob->getFromDB($jobstate['plugin_fusioninventory_taskjobs_id']);
      $pfTaskjob->reinitializeTaskjobs($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);
   } else {
      $query = 'SELECT * FROM `glpi_plugin_fusioninventory_taskjobstates`
         WHERE `plugin_fusioninventory_taskjobs_id`="'.$_POST['taskjobs_id'].'"
            AND `state`!="3"
         GROUP BY uniqid, plugin_fusioninventory_agents_id
         ORDER BY `id` DESC';
      $result = $DB->query($query);
      $mytaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      while ($data=$DB->fetch_array($result)) {
         $a_taskjobstates = $mytaskjobstate->find("`uniqid`='".$data['uniqid']."'");
         foreach($a_taskjobstates as $datast) {
            if ($datast['state'] != PluginFusioninventoryTaskjobstate::FINISHED) {
               $mytaskjobstate->changeStatusFinish($datast['id'],
                                                   0, '', 1, "Action cancelled by user", 0, 0);
            }
         }
      }
      $pfTaskjob->getFromDB($_POST['taskjobs_id']);
      $pfTaskjob->reinitializeTaskjobs($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);
   }
   Html::back();
}

if (strstr($_SERVER['HTTP_REFERER'], "wizard.php")) {
   Html::redirect($_SERVER['HTTP_REFERER']."&id=".$_GET['id']);
} else {
   $mytaskjob->redirectTask($_GET['id']);
}

Html::footer();

?>
