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
   @since     2013

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryCollect extends CommonDBTM {

   static function getTypeName($nb=0) {
      return __('Collect information', 'fusioninventory');
   }



   static function canCreate() {
      return PluginFusioninventoryProfile::haveRight("collect", "w");
   }



   static function canView() {
      return PluginFusioninventoryProfile::haveRight("collect", "r");
   }




   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      return array();
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      return TRUE;
   }



   static function getTypes() {
      $elements = array();
      $elements['registry'] = __('Registry', 'fusioninventory');
      $elements['wmi'] = __('WMI', 'fusioninventory');
      $elements['file'] = __('Find file', 'fusioninventory');

      return $elements;
   }



   function showForm($ID, $options=array()) {

      $this->initForm($ID, $options);
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Name');
      echo "</td>";
      echo "<td>";
      Html::autocompletionTextField($this,'name');
      echo "</td>";
      echo "<td>".__('Type')."</td>";
      echo "<td>";
      Dropdown::showFromArray('type',
                              PluginFusioninventoryCollect::getTypes(),
                              array('value' => $this->fields['type']));
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Comments');
      echo "</td>";
      echo "<td class='middle'>";
      echo "<textarea cols='45' rows='3' name='comment' >".
              $this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "<td>".__('Active')."</td>";
      echo "<td>";
      Dropdown::showYesNo('is_active', $this->fields['is_active']);
      echo "</td>";
      echo "</tr>\n";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return TRUE;
   }



   function prepareRun($taskjobs_id) {
      global $DB;

      $task       = new PluginFusioninventoryTask();
      $job        = new PluginFusioninventoryTaskjob();
      $joblog     = new PluginFusioninventoryTaskjoblog();
      $jobstate  = new PluginFusioninventoryTaskjobstate();
      $agent      = new PluginFusioninventoryAgent();

      $uniqid= uniqid();

      $job->getFromDB($taskjobs_id);
      $task->getFromDB($job->fields['plugin_fusioninventory_tasks_id']);

      $communication= $task->fields['communication'];

      $actions     = importArrayFromDB($job->fields['action']);
      $definitions   = importArrayFromDB($job->fields['definition']);
      $taskvalid = 0;

      $computers = array();
      foreach ($actions as $action) {
         $itemtype = key($action);
         $items_id = current($action);

         switch($itemtype) {

            case 'Computer':
               $computers[] = $items_id;
               break;

            case 'Group':
               $computer_object = new Computer();

               //find computers by user associated with this group
               $group_users   = new Group_User();
               $group         = new Group();
               $group->getFromDB($items_id);

               $members = array();
               $computers_a_1 = array();
               $computers_a_2 = array();

               //array_keys($group_users->find("groups_id = '$items_id'"));
               $members = $group_users->getGroupUsers($items_id);

               foreach ($members as $member) {
                  $computers = $computer_object->find("users_id = '${member['id']}'");
                  foreach($computers as $computer) {
                     $computers_a_1[] = $computer['id'];
                  }
               }

               //find computers directly associated with this group
               $computers = $computer_object->find("groups_id = '$items_id'");
               foreach($computers as $computer) {
                  $computers_a_2[] = $computer['id'];
               }

               //merge two previous array and deduplicate entries
               $computers = array_unique(array_merge($computers_a_1, $computers_a_2));
               break;

            case 'PluginFusioninventoryDeployGroup':
               $group = new PluginFusioninventoryDeployGroup;
               $group->getFromDB($items_id);

               switch ($group->getField('type')) {

                  case 'STATIC':
                     $query = "SELECT items_id
                     FROM glpi_plugin_fusioninventory_deploygroups_staticdatas
                     WHERE groups_id = '$items_id'
                     AND itemtype = 'Computer'";
                     $res = $DB->query($query);
                     while ($row = $DB->fetch_assoc($res)) {
                        $computers[] = $row['items_id'];
                     }
                     break;

                  case 'DYNAMIC':
                     $query = "SELECT fields_array
                     FROM glpi_plugin_fusioninventory_deploygroups_dynamicdatas
                     WHERE groups_id = '$items_id'
                     LIMIT 1";
                     $res = $DB->query($query);
                     $row = $DB->fetch_assoc($res);

                     if (isset($_GET)) {
                        $get_tmp = $_GET;
                     }
                     if (isset($_SESSION["glpisearchcount"]['Computer'])) {
                        unset($_SESSION["glpisearchcount"]['Computer']);
                     }
                     if (isset($_SESSION["glpisearchcount2"]['Computer'])) {
                        unset($_SESSION["glpisearchcount2"]['Computer']);
                     }

                     $_GET = importArrayFromDB($row['fields_array']);

                     $_GET["glpisearchcount"] = count($_GET['field']);
                     if (isset($_GET['field2'])) {
                        $_GET["glpisearchcount2"] = count($_GET['field2']);
                     }

                     $pfSearch = new PluginFusioninventorySearch();
                     Search::manageGetValues('Computer');
                     $glpilist_limit = $_SESSION['glpilist_limit'];
                     $_SESSION['glpilist_limit'] = 999999999;
                     $result = $pfSearch->constructSQL('Computer',
                                                       $_GET);
                     $_SESSION['glpilist_limit'] = $glpilist_limit;
                     while ($data=$DB->fetch_array($result)) {
                        $computers[] = $data['id'];
                     }
                     if (count($get_tmp) > 0) {
                        $_GET = $get_tmp;
                     }

                     break;

               }
               break;

         }
      }

      $c_input= array();
      $c_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $c_input['state']                              = 0;
      $c_input['plugin_fusioninventory_agents_id']   = 0;

      $pfCollect = new PluginFusioninventoryCollect();

      foreach($computers as $computer_id) {
         //get agent if for this computer
         $agents_id = $agent->getAgentWithComputerid($computer_id);
         if($agents_id === FALSE) {
            $jobstates_id = $jobstate->add($c_input);
            $jobstate->changeStatusFinish($jobstates_id,
                                          0,
                                          '',
                                          1,
                                          "No agent found for [[Computer::".$computer_id."]]",
                                          0,
                                          0);
         } else {
            foreach($definitions as $definition) {
               $pfCollect->getFromDB($definition['PluginFusioninventoryCollect']);

               switch ($pfCollect->fields['type']) {

                  case 'registry':
                     // get all registry
                     $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
                     $a_registries = $pfCollect_Registry->find(
                             "`plugin_fusioninventory_collects_id`='".
                             $pfCollect->fields['id']."'");
                     foreach ($a_registries as $data_r) {
                        $uniqid= uniqid();
                        $c_input['state'] = 0;
                        $c_input['itemtype'] = 'PluginFusioninventoryCollect_Registry';
                        $c_input['items_id'] = $data_r['id'];
                        $c_input['date'] = date("Y-m-d H:i:s");
                        $c_input['uniqid'] = $uniqid;

                        $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

                        # Push the agent, in the stack of agent to awake
                        if ($communication == "push") {
                           $_SESSION['glpi_plugin_fusioninventory']['agents'][$agents_id] = 1;
                        }

                        $jobstates_id= $jobstate->add($c_input);

                        //Add log of taskjob
                        $c_input['plugin_fusioninventory_taskjobstates_id'] = $jobstates_id;
                        $c_input['state']= PluginFusioninventoryTaskjoblog::TASK_PREPARED;
                        $taskvalid++;
                        $joblog->add($c_input);
                     }
                     break;

                  case 'wmi':
                     // get all wmi
                     $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
                     $a_wmies = $pfCollect_Wmi->find(
                             "`plugin_fusioninventory_collects_id`='".
                             $pfCollect->fields['id']."'");
                     foreach ($a_wmies as $data_r) {
                        $uniqid= uniqid();
                        $c_input['state'] = 0;
                        $c_input['itemtype'] = 'PluginFusioninventoryCollect_Wmi';
                        $c_input['items_id'] = $data_r['id'];
                        $c_input['date'] = date("Y-m-d H:i:s");
                        $c_input['uniqid'] = $uniqid;

                        $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

                        # Push the agent, in the stack of agent to awake
                        if ($communication == "push") {
                           $_SESSION['glpi_plugin_fusioninventory']['agents'][$agents_id] = 1;
                        }

                        $jobstates_id= $jobstate->add($c_input);

                        //Add log of taskjob
                        $c_input['plugin_fusioninventory_taskjobstates_id'] = $jobstates_id;
                        $c_input['state']= PluginFusioninventoryTaskjoblog::TASK_PREPARED;
                        $taskvalid++;
                        $joblog->add($c_input);
                     }
                     break;

                  case 'file':
                     // find files
                     $pfCollect_File = new PluginFusioninventoryCollect_File();
                     $a_files = $pfCollect_File->find(
                             "`plugin_fusioninventory_collects_id`='".
                             $pfCollect->fields['id']."'");
                     foreach ($a_files as $data_r) {
                        $uniqid= uniqid();
                        $c_input['state'] = 0;
                        $c_input['itemtype'] = 'PluginFusioninventoryCollect_File';
                        $c_input['items_id'] = $data_r['id'];
                        $c_input['date'] = date("Y-m-d H:i:s");
                        $c_input['uniqid'] = $uniqid;

                        $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

                        # Push the agent, in the stack of agent to awake
                        if ($communication == "push") {
                           $_SESSION['glpi_plugin_fusioninventory']['agents'][$agents_id] = 1;
                        }

                        $jobstates_id= $jobstate->add($c_input);

                        //Add log of taskjob
                        $c_input['plugin_fusioninventory_taskjobstates_id'] = $jobstates_id;
                        $c_input['state']= PluginFusioninventoryTaskjoblog::TASK_PREPARED;
                        $taskvalid++;
                        $joblog->add($c_input);
                     }
                     break;


               }
            }
         }
      }

      if ($taskvalid > 0) {
         $job->fields['status']= 1;
         $job->update($job->fields);
      } else {
         $job->reinitializeTaskjobs($job->fields['plugin_fusioninventory_tasks_id']);
      }
   }



   function run($taskjob, $agent) {
      $output = array();
      switch ($taskjob['itemtype']) {

         case 'PluginFusioninventoryCollect_Registry':
            $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
            $pfCollect_Registry->getFromDB($taskjob['items_id']);
            $output['function'] = 'getFromRegistry';
            $output['path'] = $pfCollect_Registry->fields['hive'].
                    $pfCollect_Registry->fields['path'].
                    $pfCollect_Registry->fields['key'];
            $output['uuid'] = $taskjob['uniqid'];
            break;

         case 'PluginFusioninventoryCollect_Wmi':
            $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
            $pfCollect_Wmi->getFromDB($taskjob['items_id']);
            $output['function'] = 'getFromWMI';
//            $output['moniker'] = $pfCollect_Wmi->fields['moniker'];
            $output['class'] = $pfCollect_Wmi->fields['class'];
            $output['properties'] = array($pfCollect_Wmi->fields['properties']);
            $output['uuid'] = $taskjob['uniqid'];
            break;

         case 'PluginFusioninventoryCollect_File':
            $pfCollect_File = new PluginFusioninventoryCollect_File();
            $pfCollect_File->getFromDB($taskjob['items_id']);
            $output['function'] = 'findFile';
            $output['dir'] = $pfCollect_File->fields['dir'];
            $output['limit'] = $pfCollect_File->fields['limit'];
            $output['recursive'] = $pfCollect_File->fields['is_recursive'];
            $output['filter'] = array();
            if ($pfCollect_File->fields['filter_regex'] != '') {
               $output['filter']['regex'] = $pfCollect_File->fields['filter_regex'];
            }
            if ($pfCollect_File->fields['filter_sizeequals'] > 0) {
               $output['filter']['sizeEquals'] = $pfCollect_File->fields['filter_sizeequals'];
            } else if ($pfCollect_File->fields['filter_sizegreater'] > 0) {
               $output['filter']['sizeGreater'] = $pfCollect_File->fields['filter_sizegreater'];
            } else if ($pfCollect_File->fields['filter_sizelower'] > 0) {
               $output['filter']['sizeLower'] = $pfCollect_File->fields['filter_sizelower'];
            }
            if ($pfCollect_File->fields['filter_checksumsha512'] != '') {
               $output['filter']['checkSumSHA512'] = $pfCollect_File->fields['filter_checksumsha512'];
            }
            if ($pfCollect_File->fields['filter_checksumsha2'] != '') {
               $output['filter']['checkSumSHA2'] = $pfCollect_File->fields['filter_checksumsha2'];
            }
            if ($pfCollect_File->fields['filter_name'] != '') {
               $output['filter']['name'] = $pfCollect_File->fields['filter_name'];
            }
            if ($pfCollect_File->fields['filter_iname'] != '') {
               $output['filter']['iname'] = $pfCollect_File->fields['filter_iname'];
            }
            $output['filter']['is_file'] = $pfCollect_File->fields['filter_is_file'];
            $output['filter']['is_dir'] = $pfCollect_File->fields['filter_is_dir'];
            $output['uuid'] = $taskjob['uniqid'];
            break;


      }
      return $output;
   }
}

?>
