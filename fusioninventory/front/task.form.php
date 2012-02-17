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

$pft = new PluginFusioninventoryTask();

Html::header($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","tasks");

PluginFusioninventoryProfile::checkRight("fusioninventory", "task","r");

PluginFusioninventoryMenu::displayMenu("mini");

if (isset($_POST['forcestart'])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");
   $pfTaskjob = new PluginFusioninventoryTaskjob();
   $pfTaskjob->forceRunningTask($_POST['id']);

   Html::back();
} else if (isset($_POST['reset'])) {
   $pFusioninventoryTask    = new PluginFusioninventoryTask();

   $pFusioninventoryTask->getFromDB($_POST['id']);
   $query = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
         SET `execution_id`='".$pFusioninventoryTask->fields['execution_id']."',
            `status`='0'
      WHERE `plugin_fusioninventory_tasks_id`='".$_POST['id']."'";
   $DB->query($query);   
   Html::back();
   
} else if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");
   
   $itens_id = $pft->add($_POST);
   Html::redirect(str_replace("add=1", "", $_SERVER['HTTP_REFERER'])."id=".$itens_id);
} else if (isset($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");

   $pftj = new PluginFusioninventoryTaskjob();
   
   $a_taskjob = $pftj->find("`plugin_fusioninventory_tasks_id` = '".$_POST['id']."' ");
   foreach ($a_taskjob as $datas) {
      $pftj->delete($datas);
   }
   $pft->delete($_POST);
   Html::redirect(Toolbox::getItemTypeSearchURL('PluginFusioninventoryTask'));
} else if (isset($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");

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

PluginFusioninventoryTaskjob::getAllowurlfopen();

if (isset($_GET["id"])) {
   $pft->showForm($_GET["id"]);
} else {
   $pft->showForm("");
}

Html::footer();

?>