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

$iprange = new PluginFusinvsnmpIPRange;

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","iprange");

PluginFusioninventoryProfile::checkRight("fusinvsnmp", "iprange","r");

PluginFusioninventoryMenu::displayMenu("mini");

if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "iprange","w");
   if ($iprange->checkip($_POST)) {
      $_POST['ip_start'] = $_POST['ip_start0'].".".$_POST['ip_start1'].".".$_POST['ip_start2'].".".$_POST['ip_start3'];
      $_POST['ip_end'] = $_POST['ip_end0'].".".$_POST['ip_end1'].".".$_POST['ip_end2'].".".$_POST['ip_end3'];
      $iprange->add($_POST);
   }
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
   if (isset($_POST['communication'])) {
      //task permanent update
      $PluginFusioninventoryTask = new PluginFusioninventoryTask();
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTask->getFromDB($_POST['task_id']);
      $PluginFusioninventoryTaskjob->getFromDB($_POST['taskjob_id']);
      $PluginFusioninventoryTask->fields["is_active"] = $_POST['is_active'];
      $PluginFusioninventoryTask->fields["periodicity_count"] = $_POST['periodicity_count'];
      $PluginFusioninventoryTask->fields["periodicity_type"] = $_POST['periodicity_type'];
      if (!empty($_POST['action'])) {
         $a_action = explode(',', $_POST['action']);
         foreach ($a_action as $num=>$data) {
            $dataDB = explode('-', $data);
            if (isset($dataDB[1]) AND $dataDB > 0) {
               $a_actionDB[][$dataDB[0]] = $dataDB[1];
            }
         }
         $PluginFusioninventoryTaskjob->fields["action"] = exportArrayToDB($a_actionDB);
      } else {
         $PluginFusioninventoryTaskjob->fields["action"] = '';
      }
      $a_definition = array();
      $a_definition[]['PluginFusinvsnmpIPRange'] = $_POST['iprange'];
      $PluginFusioninventoryTaskjob->fields['definition'] = exportArrayToDB($a_definition);
      $PluginFusioninventoryTask->fields["communication"] = $_POST['communication'];

      $PluginFusioninventoryTask->update($PluginFusioninventoryTask->fields);
      $PluginFusioninventoryTaskjob->update($PluginFusioninventoryTaskjob->fields);
   } else {
      PluginFusioninventoryProfile::checkRight("fusinvsnmp", "iprange","w");
      if ($iprange->checkip($_POST)) {
         $_POST['ip_start'] = $_POST['ip_start0'].".".$_POST['ip_start1'].".".$_POST['ip_start2'].".".$_POST['ip_start3'];
         $_POST['ip_end'] = $_POST['ip_end0'].".".$_POST['ip_end1'].".".$_POST['ip_end2'].".".$_POST['ip_end3'];
         $iprange->update($_POST);
      }
   }
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "iprange","w");
	$agents->iprange($_POST);
	glpi_header("iprange.php");
}


$id = "";
if (isset($_GET["id"])) {
	$id = $_GET["id"];
}

$iprange->showForm($id);

commonFooter();

?>