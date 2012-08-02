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
   @author    Alexandre Delaunay
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/inc/includes.php");
Session::checkLoginUser();

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}

$task = new PluginFusinvdeployTask();

if (isset($_POST['forcestart'])) {
   $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
   $PluginFusioninventoryTaskjob->forceRunningTask($_POST['id']);
   Html::back();

} else if (isset($_POST['reset'])) {
   $pFusioninventoryTask = new PluginFusioninventoryTask();
   $pFusioninventoryTask->getFromDB($_POST['id']);
   $query = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
         SET `execution_id`='".$pFusioninventoryTask->fields['execution_id']."',
            `status`='0'
      WHERE `plugin_fusioninventory_tasks_id`='".$_POST['id']."'";
   $DB->query($query);
   Html::back();

} else if (isset($_POST["add"])) {
   $task->check(-1, 'w', $_POST);
   $newID = $task->add($_POST);
   Html::redirect(GLPI_ROOT."/plugins/fusinvdeploy/front/task.form.php?id=".$newID);

} else if (isset($_POST["delete"])) {
   $task->check($_POST['id'], 'd');
   $ok = $task->delete($_POST);

   $task->redirectToList();

} else if (isset($_REQUEST["purge"])) {
   $task->check($_REQUEST['id'], 'd');
   $ok = $task->delete($_REQUEST,1);

   $task->redirectToList();

} else if (isset($_POST["update"])) {
   $task->check($_POST['id'], 'w');
   $task->update($_POST);

   Html::back();

} else {
   Html::header($LANG['plugin_fusinvdeploy']['title'][0],$_SERVER["PHP_SELF"],"plugins",
   "fusioninventory","task");

   PluginFusioninventoryMenu::displayMenu("mini");

   $task->showForm($_GET["id"]);
   Html::footer();
}

?>