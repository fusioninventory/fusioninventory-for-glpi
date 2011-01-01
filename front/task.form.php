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

$pft = new PluginFusioninventoryTask;

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","tasks");

PluginFusioninventoryProfile::checkRight("fusioninventory", "task","r");

PluginFusioninventoryMenu::displayMenu("mini");

if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");
   $_POST['periodicity'] = $_POST['periodicity-1']."-".$_POST['periodicity-2'];

   $pft->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");

   $pftj = new PluginFusioninventoryTaskjob;
   
   $a_taskjob = $pftj->find("`plugin_fusioninventory_tasks_id` = '".$_POST['id']."' ");
   foreach ($a_taskjob as $jobtask_id => $datas) {
      $pftj->delete($datas);
   }
   $pft->delete($_POST);
   glpi_header($CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/task.php");
} else if (isset($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "task","w");

   $_POST['periodicity'] = $_POST['periodicity-1']."-".$_POST['periodicity-2'];

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