<?php

/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of GLPI.

   GLPI is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusioninventoryTaskjobstatus extends CommonDBTM {

   /*
    * Define different state
    *
    * 0 : define for each job, what computer and what agent will do task
    * 1 : server has sent datas to agent
    * 2 : return of agent data and update glpi
    * 3 : finish
    */


   function __construct() {
      $this->table = "glpi_plugin_fusioninventory_taskjobstatus";
      $this->type = 'PluginFusioninventoryTaskjobstatus';
   }


   function stateTaskjob ($taskjobs_id, $width = '930', $return = 'html', $style = '') {
      global $DB;

      $state = array();
      $state[0] = 0;
      $state[1] = 0;
      $state[2] = 0;
      $state[3] = 0;
      $a_taskjobstatus = $this->find("`plugin_fusioninventory_taskjobs_id`='".$taskjobs_id."'");
      $total = 0;
      foreach ($a_taskjobstatus as $taskjobstatus_id=>$data) {
         $total++;
         $state[$data['state']]++;         
      }

      if ($total == '0') {
         $globalState = 0;
      } else {
         $first = 25;
         $second = ((($state[1]+$state[2]+$state[3]) * 100) / $total) / 4;
         $third = ((($state[2]+$state[3]) * 100) / $total) / 4;
         $fourth = (($state[3] * 100) / $total) / 4;
         $globalState = $first + $second + $third + $fourth;
      }
      if ($return == 'html') {
         if ($style == 'simple') {
            displayProgressBar($width,ceil($globalState), array('simple' => 1));
         } else {
            displayProgressBar($width,ceil($globalState));
         }
      } else if ($return == 'htmlvar') {
         if ($style == 'simple') {
            return PluginFusioninventoryDisplay::getProgressBar($width,ceil($globalState), array('simple' => 1));
         } else {
            return PluginFusioninventoryDisplay::getProgressBar($width,ceil($globalState));
         }
      } else {
         return ceil($globalState);
      }
   }



   function stateTaskjobItem($items_id, $itemtype, $state='all') {
      global $DB,$LANG;

      $PluginFusioninventoryTaskjoblogs = new PluginFusioninventoryTaskjoblogs;
      $PluginFusioninventoryTaskjob     = new PluginFusioninventoryTaskjob;
      $PluginFusioninventoryTask        = new PluginFusioninventoryTask;

      switch ($state) {

         case 'running':
            $search = " AND `state`!='3'";
            $title = $LANG['plugin_fusioninventory']["task"][19];
            $icon = "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/task_running.png'/>";
            break;

         case 'finished':
            $search = " AND `state`='3'";
            $title = $LANG['plugin_fusioninventory']["task"][20];
            $icon = "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/task_finished.png'/>";
            break;

         case 'nostarted';
            $query = "SELECT *, `glpi_plugin_fusioninventory_taskjobs`.`id` as tjid FROM `glpi_plugin_fusioninventory_taskjobs`
               LEFT JOIN ".$this->table." on `plugin_fusioninventory_taskjobs_id` = `glpi_plugin_fusioninventory_taskjobs`.`id`
               WHERE `selection` LIKE '%\"".$itemtype."\":\"".$items_id."\"%'
                  AND `plugin_fusioninventory_taskjobs_id` is null";
            $a_taskjobs = array();
            if ($result = $DB->query($query)) {
               while ($data=$DB->fetch_array($result)) {
                  $a_taskjobs[] = $data;                  
               }
            }
            $title = $LANG['plugin_fusioninventory']["task"][22];
            $icon = "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/task_scheduled.png'/>";
            break;

         case 'all':
            $search = "";
            $title = $LANG['plugin_fusioninventory']["task"][18];
            $icon = "";
            break;

      }
      if (isset($search)) {
         $query = "SELECT * FROM ".$this->table."
            LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` on `glpi_plugin_fusioninventory_taskjobs`.`id` = `plugin_fusioninventory_taskjobs_id`
            WHERE `items_id`='".$items_id."' AND `itemtype`='".$itemtype."'".$search."
            ORDER BY `date_scheduled` DESC";
         $a_taskjobs = array();
         if ($result = $DB->query($query)) {
            while ($data=$DB->fetch_array($result)) {
               $a_taskjobs[] = $data;
            }
         }
      }


      echo "<br/><div align='center'>";

      echo "<table  class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th width='110'>";
      echo $icon;
      echo "</th>";
      echo "<th>";
      echo $title."&nbsp;:";
      echo "</th>";
      echo "</tr>";

      echo "</table>";
      echo "<br/>";

      foreach ($a_taskjobs as $num=>$data) {
         echo "<table  class='tab_cadre_fixe' style='width: 800px'>";
         echo "<tr>";
         echo "<th>";
         $PluginFusioninventoryTask->getFromDB($data['plugin_fusioninventory_tasks_id']);
         
         echo $LANG['plugin_fusioninventory']["task"][2]." : ".$data['name']." (".
            $LANG['plugin_fusioninventory']["task"][0]." : ".$PluginFusioninventoryTask->getLink().")";
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_4'>";
         echo "<td>";
         echo "<br/>";
         if ($state == 'nostarted') {
            $PluginFusioninventoryTaskjob->showMiniAction($data['tjid'], '750');
         } else {
            if ($state != 'finished') {
               $this->stateTaskjob($data['plugin_fusioninventory_taskjobs_id'], $width = '730');
               echo "<br/>";
            }
            $PluginFusioninventoryTaskjoblogs->showHistory($data['plugin_fusioninventory_taskjobs_id'], '750');
         }
         echo "<br/>";
         echo "</td>";
         echo "</tr>";
         echo "</table><br/>";
      }
      echo "</div>";
   }


   function changeStatus($id, $state) {
      $this->getFromDB($id);
      $input = $this->fields;
      $input['state'] = $state;
      $this->update($input);      
   }


   
   function getTaskjobsAgent($agent_id) {
      global $DB;

      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;

      $moduleRun = array();

      $a_taskjobstatus = $this->find("`plugin_fusioninventory_agents_id`='".$agent_id."' AND `state`='0'");
      foreach ($a_taskjobstatus as $taskjobstatus_id=>$data) {

         // Get job and data to send to agent
         $PluginFusioninventoryTaskjob->getFromDB($data['plugin_fusioninventory_taskjobs_id']);

         $pluginName = PluginFusioninventoryModule::getModuleName($PluginFusioninventoryTaskjob->fields['plugins_id']);
         $className = "Plugin".ucfirst($pluginName).ucfirst($PluginFusioninventoryTaskjob->fields['method']);
         $moduleRun[$className] = $data;
      }
      return $moduleRun;
   }


   function changeStatusFinish($taskjobs_id, $items_id, $itemtype, $error=0, $message='') {

      // Add status if not exist
      $a_taskjobstatus = $this->find("`plugin_fusioninventory_taskjobs_id`='".$taskjobs_id."'
                     AND `items_id`='".$items_id."'
                     AND`itemtype`='".$itemtype."'");
      if (count($a_taskjobstatus) =="0") {
         $input = array();
         $input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $input['items_id'] = $items_id;
         $input['itemtype'] = $itemtype;
         $input['state'] = 3;
         $this->add($input);
      } else {
         foreach($a_taskjobstatus as $taskjobstatus_id=>$data) {
            $this->getFromDB($taskjobstatus_id);
            $this->fields['state'] = 3;
            $this->update($this->fields);
         }
      }

      $PluginFusioninventoryTaskjoblogs = new PluginFusioninventoryTaskjoblogs;
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;
      // Check if we have retry
      $PluginFusioninventoryTaskjob->getFromDB($taskjobs_id);
      $input = array();
      if($PluginFusioninventoryTaskjob->fields['retry_nb'] > 0) {
         // Replanification
         $a_input['state'] = 3;
         
         $PluginFusioninventoryTaskjob->fields['retry_nb']--;
         $PluginFusioninventoryTaskjob->fields['date_creation'] =
         $PluginFusioninventoryTaskjob->fields['date_scheduled'] = date("Y-m-d H:i:s");
                 date("Y-m-d H:i:s", time() + ($PluginFusioninventoryTaskjob->fields['retry_time'] * 60));
         $PluginFusioninventoryTaskjob->fields['status'] = 0;
         $PluginFusioninventoryTaskjob->fields['rescheduled_taskjob_id'] = $PluginFusioninventoryTaskjob->fields['id'];
         unset($PluginFusioninventoryTaskjob->fields['id']);
         $PluginFusioninventoryTaskjob->add($PluginFusioninventoryTaskjob->fields);
      } else {
       $a_input['state'] = 4;
      }
      $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $a_input['items_id'] = $items_id;
      $a_input['itemtype'] = $itemtype;
      $a_input['date'] = date("Y-m-d H:i:s");
      $a_input['comment'] = $message;
      $PluginFusioninventoryTaskjoblogs->add($a_input);      
   }

}

?>