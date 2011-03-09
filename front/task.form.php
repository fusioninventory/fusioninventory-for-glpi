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

$pft = new PluginFusioninventoryTask();

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","tasks");

PluginFusioninventoryProfile::checkRight("fusioninventory", "task","r");

PluginFusioninventoryMenu::displayMenu("mini");

if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");
   
   $itens_id = $pft->add($_POST);
   glpi_header(str_replace("add=1", "", $_SERVER['HTTP_REFERER'])."id=".$itens_id);
} else if (isset($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");

   $pftj = new PluginFusioninventoryTaskjob();
   
   $a_taskjob = $pftj->find("`plugin_fusioninventory_tasks_id` = '".$_POST['id']."' ");
   foreach ($a_taskjob as $datas) {
      $pftj->delete($datas);
   }
   $pft->delete($_POST);
   glpi_header(GLPI_ROOT."/plugins/fusioninventory/front/task.php");
} else if (isset($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");

   $pft->update($_POST);

   glpi_header($_SERVER['HTTP_REFERER']);
}

if (isset($_GET["id"])) {
   $pft->showForm($_GET["id"]);
} else {
   $pft->showForm("");
}

commonFooter();

?>