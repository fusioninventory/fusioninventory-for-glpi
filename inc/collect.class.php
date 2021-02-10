<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the collect (registry, file, wmi) module of
 * the agents
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the collect information by the agent.
 */
class PluginFusioninventoryCollect extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_collect';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('Collect information', 'fusioninventory');
   }


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      if ($item->fields['id'] > 0) {
         $index = self::getNumberOfCollectsForAComputer($item->fields['id']);
         $nb    = 0;
         if ($index > 0) {
            if ($_SESSION['glpishow_count_on_tabs']) {
               $nb = $index;
            }
            return self::createTabEntry(self::getTypeName(Session::getPluralNumber()), $nb);
         }
      }
      return '';
   }


   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      $pfComputer = new PluginFusioninventoryInventoryComputerComputer();
      $id = $item->fields['id'];
      if ($item->getType() == 'Computer'
         && $pfComputer->hasAutomaticInventory($id)) {
         foreach (['PluginFusioninventoryCollect_File_Content',
                   'PluginFusioninventoryCollect_Wmi_Content',
                   'PluginFusioninventoryCollect_Registry_Content'] as $itemtype) {
            $collect_item = new $itemtype();
            $collect_item->showForComputer($id);
         }
      }
      return false;
   }


   /**
   * Get the number of collects for a computer
   * @since 9.2
   *
   * @param integer $computers_id the computer ID
   * @return the number of collects for this computer
   */
   static function getNumberOfCollectsForAComputer($computers_id) {
      $tables = ['glpi_plugin_fusioninventory_collects_registries_contents',
                 'glpi_plugin_fusioninventory_collects_wmis_contents',
                 'glpi_plugin_fusioninventory_collects_files_contents',
                ];
      $total = 0;
      foreach ($tables as $table) {
         $total+= countElementsInTable($table, ['computers_id' => $computers_id]);
      }
      return $total;
   }


   /**
    * Get all collect types
    *
    * @return array [name] => description
    */
   static function getTypes() {
      return [
         'registry' => __('Registry', 'fusioninventory'),
         'wmi'      => __('WMI', 'fusioninventory'),
         'file'     => __('Find file', 'fusioninventory')
      ];
   }

   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id'           => 'common',
         'name'         => __('Characteristics')
      ];

      $tab[] = [
         'id'           => '1',
         'table'        => $this->getTable(),
         'field'        => 'name',
         'name'         => __('Name'),
         'datatype'     => 'itemlink',
         'autocomplete' => true,
      ];

      return $tab;
   }


   /**
    * Add search options
    *
    * @return array
    */
   static function getSearchOptionsToAdd($itemtype = null) {
      $tab = [];

      $i = 5200;

      $pfCollect = new PluginFusioninventoryCollect();
      foreach ($pfCollect->find(getEntitiesRestrictCriteria($pfCollect->getTable(), '', '', true), ['id ASC']) as $collect) {

         //registries
         $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
         $registries = $pfCollect_Registry->find(['plugin_fusioninventory_collects_id' => $collect['id']], ['id ASC']);
         foreach ($registries as $registry) {
            $tab[$i]['table']         = 'glpi_plugin_fusioninventory_collects_registries_contents';
            $tab[$i]['field']         = 'value';
            $tab[$i]['linkfield']     = '';
            $tab[$i]['name']          = __('Registry', 'fusioninventory')." - ".$registry['name'];
            $tab[$i]['joinparams']    = ['jointype' => 'child'];
            $tab[$i]['datatype']      = 'text';
            $tab[$i]['forcegroupby']  = true;
            $tab[$i]['massiveaction'] = false;
            $tab[$i]['nodisplay']     = true;
            $tab[$i]['joinparams']    = ['condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_registries_id` = ".$registry['id'],
                                          'jointype' => 'child'];
            $i++;
         }

         //WMIs
         $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
         $wmis = $pfCollect_Wmi->find(['plugin_fusioninventory_collects_id'  => $collect['id']], ['id ASC']);
         foreach ($wmis as $wmi) {
            $tab[$i]['table']         = 'glpi_plugin_fusioninventory_collects_wmis_contents';
            $tab[$i]['field']         = 'value';
            $tab[$i]['linkfield']     = '';
            $tab[$i]['name']          = __('WMI', 'fusioninventory')." - ".$wmi['name'];
            $tab[$i]['joinparams']    = ['jointype' => 'child'];
            $tab[$i]['datatype']      = 'text';
            $tab[$i]['forcegroupby']  = true;
            $tab[$i]['massiveaction'] = false;
            $tab[$i]['nodisplay']     = true;
            $tab[$i]['joinparams']    = ['condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_wmis_id` = ".$wmi['id'],
                                          'jointype' => 'child'];
            $i++;
         }

         //Files
         $pfCollect_File = new PluginFusioninventoryCollect_File();
         $files = $pfCollect_File->find(['plugin_fusioninventory_collects_id' => $collect['id']], ['id ASC']);
         foreach ($files as $file) {

            $tab[$i]['table']         = 'glpi_plugin_fusioninventory_collects_files_contents';
            $tab[$i]['field']         = 'pathfile';
            $tab[$i]['linkfield']     = '';
            $tab[$i]['name']          = __('Find file', 'fusioninventory').
                                    " - ".$file['name'].
                                    " - ".__('pathfile', 'fusioninventory');
            $tab[$i]['joinparams']    = ['jointype' => 'child'];
            $tab[$i]['datatype']      = 'text';
            $tab[$i]['forcegroupby']  = true;
            $tab[$i]['massiveaction'] = false;
            $tab[$i]['nodisplay']     = true;
            $tab[$i]['joinparams']    = ['condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_files_id` = ".$file['id'],
                                          'jointype' => 'child'];
            $i++;

            $tab[$i]['table']         = 'glpi_plugin_fusioninventory_collects_files_contents';
            $tab[$i]['field']         = 'size';
            $tab[$i]['linkfield']     = '';
            $tab[$i]['name']          = __('Find file', 'fusioninventory').
                                    " - ".$file['name'].
                                    " - ".__('Size', 'fusioninventory');
            $tab[$i]['joinparams']    = ['jointype' => 'child'];
            $tab[$i]['datatype']      = 'text';
            $tab[$i]['forcegroupby']  = true;
            $tab[$i]['massiveaction'] = false;
            $tab[$i]['nodisplay']     = true;
            $tab[$i]['joinparams']    = ['condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_files_id` = ".$file['id'],
                                          'jointype' => 'child'];
            $i++;
         }
      }
      return $tab;
   }


   /**
    * Display form
    *
    * @param integer $ID
    * @param array $options
    * @return true
    */
   function showForm($ID, $options = []) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Name');
      echo "</td>";
      echo "<td>";
      Html::autocompletionTextField($this, 'name');
      echo "</td>";
      echo "<td>".__('Type')."</td>";
      echo "<td>";
      Dropdown::showFromArray('type',
                              PluginFusioninventoryCollect::getTypes(),
                              ['value' => $this->fields['type']]);
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

      return true;
   }


   /**
    * Prepare run, so it prepare the taskjob with module 'collect'.
    * It prepare collect information and computer list for task run
    *
    * @global object $DB
    * @param integer $taskjobs_id id of taskjob
    */
   function prepareRun($taskjobs_id) {
      global $DB;

      $task       = new PluginFusioninventoryTask();
      $job        = new PluginFusioninventoryTaskjob();
      $joblog     = new PluginFusioninventoryTaskjoblog();
      $jobstate   = new PluginFusioninventoryTaskjobstate();
      $agent      = new PluginFusioninventoryAgent();

      $job->getFromDB($taskjobs_id);
      $task->getFromDB($job->fields['plugin_fusioninventory_tasks_id']);

      $communication = $task->fields['communication'];
      $actions       = importArrayFromDB($job->fields['action']);
      $definitions   = importArrayFromDB($job->fields['definition']);
      $taskvalid     = 0;

      $computers = [];
      foreach ($actions as $action) {
         $itemtype = key($action);
         $items_id = current($action);

         switch ($itemtype) {

            case 'Computer':
               $computers[] = $items_id;
               break;

            case 'Group':
               $computer_object = new Computer();

               //find computers by user associated with this group
               $group_users   = new Group_User();
               $group         = new Group();
               $group->getFromDB($items_id);

               $computers_a_1 = [];
               $computers_a_2 = [];

               $members = $group_users->getGroupUsers($items_id);

               foreach ($members as $member) {
                  $computers = $computer_object->find(['users_id' => $member['id']]);
                  foreach ($computers as $computer) {
                     $computers_a_1[] = $computer['id'];
                  }
               }

               //find computers directly associated with this group
               $computers = $computer_object->find(['groups_id' => $items_id]);
               foreach ($computers as $computer) {
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
                     while ($row = $DB->fetchAssoc($res)) {
                        $computers[] = $row['items_id'];
                     }
                     break;

                  case 'DYNAMIC':
                     $query = "SELECT fields_array
                     FROM glpi_plugin_fusioninventory_deploygroups_dynamicdatas
                     WHERE groups_id = '$items_id'
                     LIMIT 1";
                     $res = $DB->query($query);
                     $row = $DB->fetchAssoc($res);

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
                     $glpilist_limit = $_SESSION['glpilist_limit'];
                     $_SESSION['glpilist_limit'] = 999999999;
                     $result = $pfSearch->constructSQL('Computer',
                                                       $_GET);
                     $_SESSION['glpilist_limit'] = $glpilist_limit;
                     while ($data=$DB->fetchArray($result)) {
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

      $c_input= [];
      $c_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $c_input['state']                              = 0;
      $c_input['plugin_fusioninventory_agents_id']   = 0;
      $c_input['execution_id']                       = $task->fields['execution_id'];

      $pfCollect = new PluginFusioninventoryCollect();

      foreach ($computers as $computer_id) {
         //get agent if for this computer
         $agents_id = $agent->getAgentWithComputerid($computer_id);
         if ($agents_id === false) {
            $jobstates_id = $jobstate->add($c_input);
            $jobstate->changeStatusFinish($jobstates_id,
                                          0,
                                          '',
                                          1,
                                          "No agent found for [[Computer::".$computer_id."]]");
         } else {
            foreach ($definitions as $definition) {
               $pfCollect->getFromDB($definition['PluginFusioninventoryCollect']);

               switch ($pfCollect->fields['type']) {

                  case 'registry':
                     // get all registry
                     $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
                     $a_registries = $pfCollect_Registry->find(
                             ['plugin_fusioninventory_collects_id' => $pfCollect->fields['id']]);
                     foreach ($a_registries as $data_r) {
                        $uniqid= uniqid();
                        $c_input['state'] = 0;
                        $c_input['itemtype'] = 'PluginFusioninventoryCollect_Registry';
                        $c_input['items_id'] = $data_r['id'];
                        $c_input['date'] = date("Y-m-d H:i:s");
                        $c_input['uniqid'] = $uniqid;

                        $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

                        // Push the agent, in the stack of agent to awake
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
                             ['plugin_fusioninventory_collects_id' => $pfCollect->fields['id']]);
                     foreach ($a_wmies as $data_r) {
                        $uniqid= uniqid();
                        $c_input['state'] = 0;
                        $c_input['itemtype'] = 'PluginFusioninventoryCollect_Wmi';
                        $c_input['items_id'] = $data_r['id'];
                        $c_input['date'] = date("Y-m-d H:i:s");
                        $c_input['uniqid'] = $uniqid;

                        $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

                        // Push the agent, in the stack of agent to awake
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
                             ['plugin_fusioninventory_collects_id' => $pfCollect->fields['id']]);
                     foreach ($a_files as $data_r) {
                        $uniqid= uniqid();
                        $c_input['state'] = 0;
                        $c_input['itemtype'] = 'PluginFusioninventoryCollect_File';
                        $c_input['items_id'] = $data_r['id'];
                        $c_input['date'] = date("Y-m-d H:i:s");
                        $c_input['uniqid'] = $uniqid;

                        $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

                        // Push the agent, in the stack of agent to awake
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


   /**
    * run function, so return data to send to the agent for collect information
    *
    * @param object $taskjobstate PluginFusioninventoryTaskjobstate instance
    * @param array $agent agent information from agent table in database
    * @return array
    */
   function run($taskjobstate, $agent) {
      global $DB;

      $output = [];

      $this->getFromDB($taskjobstate->fields['items_id']);
      $sql_where = ['plugin_fusioninventory_collects_id' => $this->fields['id']];

      switch ($this->fields['type']) {

         case 'registry':
            $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
            $reg_db = $pfCollect_Registry->find($sql_where);
            foreach ($reg_db as $reg) {
               $output[] = [
                   'function' => 'getFromRegistry',
                   'path'     => $reg['hive'].$reg['path'].$reg['key'],
                   'uuid'     => $taskjobstate->fields['uniqid'],
                   '_sid'     => $reg['id']
               ];
            }
            break;

         case 'wmi':
            $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
            $wmi_db = $pfCollect_Wmi->find($sql_where);
            foreach ($wmi_db as $wmi) {
               $datawmi = [
                   'function'   => 'getFromWMI',
                   'class'      => $wmi['class'],
                   'properties' => [$wmi['properties']],
                   'uuid'       => $taskjobstate->fields['uniqid'],
                   '_sid'       => $wmi['id']];
               if ($wmi['moniker'] != '') {
                  $datawmi['moniker'] = $wmi['moniker'];
               }
               $output[] = $datawmi;
            }

            break;

         case 'file':
            $pfCollect_File = new PluginFusioninventoryCollect_File();
            $files_db = $pfCollect_File->find($sql_where);
            foreach ($files_db as $files) {
               $datafile = [
                  'function'  => 'findFile',
                  'dir'       => $files['dir'],
                  'limit'     => $files['limit'],
                  'recursive' => $files['is_recursive'],
                  'filter'    => [
                     'is_file' => $files['filter_is_file'],
                     'is_dir'  => $files['filter_is_dir']
                  ],
                  'uuid'      => $taskjobstate->fields['uniqid'],
                  '_sid'       => $files['id']
               ];
               if ($files['filter_regex'] != '') {
                  $datafile['filter']['regex'] = $files['filter_regex'];
               }
               if ($files['filter_sizeequals'] > 0) {
                  $datafile['filter']['sizeEquals'] = $files['filter_sizeequals'];
               } else if ($files['filter_sizegreater'] > 0) {
                  $datafile['filter']['sizeGreater'] = $files['filter_sizegreater'];
               } else if ($files['filter_sizelower'] > 0) {
                  $datafile['filter']['sizeLower'] = $files['filter_sizelower'];
               }
               if ($files['filter_checksumsha512'] != '') {
                  $datafile['filter']['checkSumSHA512'] = $files['filter_checksumsha512'];
               }
               if ($files['filter_checksumsha2'] != '') {
                  $datafile['filter']['checkSumSHA2'] = $files['filter_checksumsha2'];
               }
               if ($files['filter_name'] != '') {
                  $datafile['filter']['name'] = $files['filter_name'];
               }
               if ($files['filter_iname'] != '') {
                  $datafile['filter']['iname'] = $files['filter_iname'];
               }
               $output[] = $datafile;

               //clean old files
               $DB->delete(
                  'glpi_plugin_fusioninventory_collects_files_contents', [
                     'plugin_fusioninventory_collects_files_id'   => $files['id'],
                     'computers_id'                               => $agent['computers_id']
                  ]
               );
            }
            break;

      }
      return $output;
   }


   function communication($action, $machineId, $uuid) {
      $response = new \stdClass();

      if (empty($action)) {
         return $response;
      }

      $pfAgent        = new PluginFusioninventoryAgent();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog   = new PluginFusioninventoryTaskjoblog();

      switch ($action) {

         case 'getJobs':
            if (empty($machineId)) {
               return $response;
            }
            $pfAgentModule  = new PluginFusioninventoryAgentmodule();
            $pfTask         = new PluginFusioninventoryTask();

            $agent = $pfAgent->infoByKey(Toolbox::addslashes_deep($machineId));
            if (isset($agent['id'])) {
               $taskjobstates = $pfTask->getTaskjobstatesForAgent(
                  $agent['id'],
                  ['collect']
               );
               $order = new \stdClass();
               $order->jobs = [];

               foreach ($taskjobstates as $taskjobstate) {
                  if (!$pfAgentModule->isAgentCanDo("Collect", $agent['id'])) {
                     $taskjobstate->cancel(
                        __("Collect module has been disabled for this agent", 'fusioninventory')
                     );
                  } else {
                     $out = $this->run($taskjobstate, $agent);
                     if (count($out) > 0) {
                        $order->jobs = array_merge($order->jobs, $out);
                     }

                     // change status of state table row
                     $pfTaskjobstate->changeStatus(
                           $taskjobstate->fields['id'],
                           PluginFusioninventoryTaskjobstate::SERVER_HAS_SENT_DATA
                     );

                     $a_input = [
                           'plugin_fusioninventory_taskjobstates_id'    => $taskjobstate->fields['id'],
                           'items_id'                                   => $agent['id'],
                           'itemtype'                                   => 'PluginFusioninventoryAgent',
                           'date'                                       => date("Y-m-d H:i:s"),
                           'comment'                                    => '',
                           'state'                                      => PluginFusioninventoryTaskjoblog::TASK_STARTED
                     ];
                     $pfTaskjoblog->add($a_input);

                     if (count($order->jobs) > 0) {
                        $response = $order;
                        // Inform agent we request POST method, agent will then submit result
                        // in POST request if it supports the method or it will continue with GET
                        $response->postmethod = 'POST';
                        $response->token = Session::getNewCSRFToken();
                     }
                  }
               }
            }
            break;

         case 'setAnswer':
            // example
            // ?action=setAnswer&InformationSource=0x00000000&BIOSVersion=VirtualBox&SystemManufacturer=innotek%20GmbH&uuid=fepjhoug56743h&SystemProductName=VirtualBox&BIOSReleaseDate=12%2F01%2F2006
            if (empty($uuid)) {
               return $response;
            }
            $jobstate = current($pfTaskjobstate->find(
               [
                  'uniqid' => $uuid,
                  'state'  => ['!=', PluginFusioninventoryTaskjobstate::FINISHED]
               ],
               [],
               1)
            );

            if (isset($jobstate['plugin_fusioninventory_agents_id'])) {

               $add_value = true;

               $pfAgent->getFromDB($jobstate['plugin_fusioninventory_agents_id']);
               $computers_id = $pfAgent->fields['computers_id'];

               $a_values = $_GET;
               // Check agent uses POST method to use the right submitted values. Also renew token to support CSRF for next post.
               if (isset($_GET['method']) && $_GET['method'] == 'POST') {
                  $a_values = $_POST;
                  $response->token = Session::getNewCSRFToken();
                  unset($a_values['_glpi_csrf_token']);
               }
               $sid = isset($a_values['_sid'])?$a_values['_sid']:0;
               $cpt = isset($a_values['_cpt'])?$a_values['_cpt']:0;
               unset($a_values['action']);
               unset($a_values['uuid']);
               unset($a_values['_cpt']);
               unset($a_values['_sid']);

               $this->getFromDB($jobstate['items_id']);

               switch ($this->fields['type']) {
                  case 'registry':
                     // update registry content
                     $pfCollect_subO = new PluginFusioninventoryCollect_Registry_Content();
                     break;

                  case 'wmi':
                     // update wmi content
                     $pfCollect_subO = new PluginFusioninventoryCollect_Wmi_Content();
                     break;

                  case 'file':
                     if (!empty($a_values['path']) && isset($a_values['size'])) {
                        // update files content
                        $params = [
                           'machineid' => $pfAgent->fields['device_id'],
                           'uuid'      => $uuid,
                           'code'      => 'running',
                           'msg'       => "file ".$a_values['path']." | size ".$a_values['size']
                        ];
                        if (isset($a_values['sendheaders'])) {
                           $params['sendheaders'] = $a_values['sendheaders'];
                        }
                        PluginFusioninventoryCommunicationRest::updateLog($params);
                        $pfCollect_subO = new PluginFusioninventoryCollect_File_Content();
                        $a_values = [$sid => $a_values];
                     } else {
                        $add_value = false;
                     }
                     break;
               }

               if (!isset($pfCollect_subO)) {
                  // return anyway the next CSRF token to client
                  break;
               }

               if ($add_value) {
                  // add collected informations to computer
                  $pfCollect_subO->updateComputer(
                     $computers_id,
                     $a_values,
                     $sid
                  );
               }

               // change status of state table row
               $pfTaskjobstate->changeStatus($jobstate['id'],
                        PluginFusioninventoryTaskjobstate::AGENT_HAS_SENT_DATA);

               // add logs to job
               if (count($a_values)) {
                  $flag    = PluginFusioninventoryTaskjoblog::TASK_INFO;
                  $message = json_encode($a_values, JSON_UNESCAPED_SLASHES);
               } else {
                  $flag    = PluginFusioninventoryTaskjoblog::TASK_ERROR;
                  $message = __('Path not found', 'fusioninventory');
               }
                  $pfTaskjoblog->addTaskjoblog($jobstate['id'],
                                             $jobstate['items_id'],
                                             $jobstate['itemtype'],
                                             $flag,
                                             $message);
            }
            break;

         case 'jobsDone':
            $jobstate = current($pfTaskjobstate->find(
               [
                  'uniqid' => $uuid,
                  'state'  => ['!=', PluginFusioninventoryTaskjobstate::FINISHED]
               ],
               [],
               1)
            );
            $pfTaskjobstate->changeStatusFinish(
               $jobstate['id'],
               $jobstate['items_id'],
               $jobstate['itemtype']
            );

               break;
      }
      return $response;
   }


   /**
    * After purge item, delete collect data
    */
   function post_purgeItem() {

      // Delete all registry
      $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
      $items = $pfCollect_Registry->find(['plugin_fusioninventory_collects_id' => $this->fields['id']]);
      foreach ($items as $item) {
         $pfCollect_Registry->delete(['id' => $item['id']], true);
      }

      // Delete all WMI
      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      $items = $pfCollect_Wmi->find(['plugin_fusioninventory_collects_id' => $this->fields['id']]);
      foreach ($items as $item) {
         $pfCollect_Wmi->delete(['id' => $item['id']], true);
      }

      // Delete all File
      $pfCollect_File = new PluginFusioninventoryCollect_File();
      $items = $pfCollect_File->find(['plugin_fusioninventory_collects_id' => $this->fields['id']]);
      foreach ($items as $item) {
         $pfCollect_File->delete(['id' => $item['id']], true);
      }
      parent::post_deleteItem();
   }

}
