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

$pft = new PluginFusioninventoryTask();

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"],
        "plugins", "pluginfusioninventorymenu", "task");

Session::checkRight('plugin_fusioninventory_task', READ);

PluginFusioninventoryMenu::displayMenu("mini");

if (isset($_POST['forcestart'])) {
   Session::checkRight('plugin_fusioninventory_task', UPDATE);
   $pfTaskjob = new PluginFusioninventoryTaskjob();
   $pfTaskjob->forceRunningTask($_POST['id']);
   Html::back();
} else if (isset($_POST['reset'])) {
   $pfTask    = new PluginFusioninventoryTask();

   $pfTask->getFromDB($_POST['id']);
   $query = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
         SET `execution_id`='".$pfTask->fields['execution_id']."',
            `status`='0'
      WHERE `plugin_fusioninventory_tasks_id`='".$_POST['id']."'";
   $DB->query($query);
   Html::back();

} else if (isset ($_POST["add"])) {
   Session::checkRight('plugin_fusioninventory_task', CREATE);

   $itens_id = $pft->add($_POST);
   Html::redirect(str_replace("add=1", "", $_SERVER['HTTP_REFERER'])."id=".$itens_id);
} else if (isset($_POST["delete"])) {
   Session::checkRight('plugin_fusioninventory_task', PURGE);

   $pftj = new PluginFusioninventoryTaskjob();

   $a_taskjob = $pftj->find("`plugin_fusioninventory_tasks_id` = '".$_POST['id']."' ");
   foreach ($a_taskjob as $datas) {
      $pftj->delete($datas);
   }
   $pft->delete($_POST);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginFusioninventoryTask'));
} else if (isset($_POST["update"])) {
   Session::checkRight('plugin_fusioninventory_task', UPDATE);

  $pft->getFromDB($_POST['id']);

  if ((($_POST['date_scheduled'] != $pft->fields['date_scheduled'])
            AND ($_POST['periodicity_count'] == '0'))
          OR ($_POST['periodicity_count'] == '0'
            AND $_POST['periodicity_count'] != $pft->fields['periodicity_count'])){
     $_POST['execution_id'] = 0;
     $query = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
            SET `execution_id`='0',
               `status`='0'
         WHERE `plugin_fusioninventory_tasks_id`='".$_POST['id']."'";
     $DB->query($query);
  }
  $pft->update($_POST);

   Html::back();
}

PluginFusioninventoryTaskjob::isAllowurlfopen();

if (isset($_GET["id"])) {
   $pft->showForm($_GET["id"]);
} else {
   $pft->showForm("");
}

Html::footer();

?>
