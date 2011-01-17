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
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if(!isset($_POST["id"])) {
   exit();
}

if(!isset($_POST["sort"])) $_POST["sort"] = "";
if(!isset($_POST["order"])) $_POST["order"] = "";
if(!isset($_POST["withtemplate"])) $_POST["withtemplate"] = "";

$pft = new PluginFusioninventoryTask;
$PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog;

$pftj = new PluginFusioninventoryTaskjob;
$a_taskjob = $pftj->find("`plugin_fusioninventory_tasks_id`='".$_POST["id"]."'
      AND `rescheduled_taskjob_id`='0' ", "id");
$i = 1;

switch($_POST['glpi_tab']) {
//   case -1 :
//      $pfia->showFormAdvancedOptions($_POST["id"]);
//      break;
//
   case 1 :
//      $pfia->showFormAdvancedOptions($_POST["id"]);
      break;
}

if ($_POST['glpi_tab'] > 1) {
   foreach($a_taskjob as $taskjob_id=>$datas) {
      $i++;
      if ($_POST['glpi_tab'] == $i) {
         $pftj->showForm($taskjob_id);
         $PluginFusioninventoryTaskjoblog->showHistory($taskjob_id);
         $taskjob_id_next = $taskjob_id;
         for ($j=2 ; $j > 1; $j++) {
            $a_taskjobreties = $pftj->find("`rescheduled_taskjob_id`='".$taskjob_id_next."' ", "", 1);
            if (!empty($a_taskjobreties)) {
               foreach($a_taskjobreties as $taskjob_id_next=>$datas2) {
                  $pftj->showForm($taskjob_id_next);
                  $PluginFusioninventoryTaskjoblog->showHistory($taskjob_id_next);
               }
            } else {
               $j = 0;
            }
         }
      }
   }
}

// New taskjob
$i++;
if ($_POST['glpi_tab'] == $i) {
   $pftj->showForm(0);
}

ajaxFooter();

?>