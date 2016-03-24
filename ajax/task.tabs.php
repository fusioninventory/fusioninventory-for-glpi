<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if(!isset($_POST["id"])) {
   exit();
}

if(!isset($_POST["sort"])) {
   $_POST["sort"] = "";
}

if(!isset($_POST["order"])) {
   $_POST["order"] = "";
}

if(!isset($_POST["withtemplate"])) {
   $_POST["withtemplate"] = "";
}

$pfTask = new PluginFusioninventoryTask();
$pfTaskjoblog = new PluginFusioninventoryTaskjoblog();

$pfTaskjob = new PluginFusioninventoryTaskjob();
$a_taskjob = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$_POST["id"]."'
      AND `rescheduled_taskjob_id`='0' ", "id");
$i = 1;

switch($_POST['glpi_tab']) {

   case -1:
      foreach($a_taskjob as $taskjob_id=>$datas) {
         $pfTaskjob->showForm($taskjob_id);
         $pfTaskjoblog->showHistory($taskjob_id);
         $taskjob_id_next = $taskjob_id;
         for ($j=2 ; $j > 1; $j++) {
            $a_taskjobreties = $pfTaskjob->find("`rescheduled_taskjob_id`='".$taskjob_id_next."' ", "", 1);
            if (!empty($a_taskjobreties)) {
               foreach($a_taskjobreties as $taskjob_id_next=>$datas2) {
                  $pfTaskjob->showForm($taskjob_id_next);
               }
            } else {
               $j = 0;
            }
         }
         echo "<br/>";
      }
      break;

   case 1 :
      $pfTask->getFromDB($_POST["id"]);
      if ($pfTask->fields['is_advancedmode'] == '0') {
         $taskjob = current($a_taskjob);
         if (!isset($taskjob["id"])) {
            $taskjobs_id = $pfTaskjob->add(array('name'=>$pfTask->fields['name'],
                             'entities_id'=>$pfTask->fields['entities_id'],
                             'plugin_fusioninventory_tasks_id'=>$_POST["id"]));
            $pfTaskjob->showForm($taskjobs_id);
         } else {
            $pfTaskjob->showForm($taskjob["id"]);
         }
      }
      break;

}

if ($_POST['glpi_tab'] > 1) {
   foreach($a_taskjob as $taskjob_id=>$datas) {
      $i++;
      if ($_POST['glpi_tab'] == $i) {
         $pfTaskjob->showForm($taskjob_id);
         echo "<br/>";
         $pfTaskjoblog->showHistory($taskjob_id);
         $taskjob_id_next = $taskjob_id;
         for ($j=2 ; $j > 1; $j++) {
            $a_taskjobreties = $pfTaskjob->find("`rescheduled_taskjob_id`='".$taskjob_id_next."' ", "", 1);
            if (!empty($a_taskjobreties)) {
               foreach($a_taskjobreties as $taskjob_id_next=>$datas2) {
                  $pfTaskjob->showForm($taskjob_id_next);
                  $pfTaskjoblog->showHistory($taskjob_id_next);
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
   $pfTaskjob->showForm(0);
}

$item = new $_REQUEST['itemtype']();

if (($item instanceof CommonDBTM)
    && $item->isNewItem()
    && (!isset($_REQUEST["id"]) || !$item->can($_REQUEST["id"], 'r'))) {
   exit();
}

CommonGLPI::displayStandardTab($item, $_REQUEST['glpi_tab'], '');

Html::ajaxFooter();

?>
