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


   function stateTaskjob ($taskjobs_id) {
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
      displayProgressBar('930',ceil($globalState));
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



}

?>