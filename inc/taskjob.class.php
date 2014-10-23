<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2014 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

class PluginFusioninventoryTaskjob extends  PluginFusioninventoryTaskjobView {

   static $rightname = 'plugin_fusioninventory_task';

   function __construct() {
      parent::__construct();
   }

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Job', 'fusioninventory');
   }


   /**
    * This class can be created by GLPI framework.
    */
   static function canCreate() {
      return true;
   }


   static function getJoinQuery() {

      return(
         array(
            'taskjobs' =>
               "LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` as taskjob\n".
               "ON taskjob.`plugin_fusioninventory_tasks_id` = task.`id`"
         )
      );
   }

   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Task');


      $tab[1]['table']          = $this->getTable();
      $tab[1]['field']          = 'name';
      $tab[1]['linkfield']      = '';
      $tab[1]['name']           = __('Name');
      $tab[1]['datatype']       = 'itemlink';

      $tab[2]['table']           = 'glpi_entities';
      $tab[2]['field']           = 'completename';
      $tab[2]['linkfield']       = 'entities_id';
      $tab[2]['name']            = __('Entity');

      $tab[4]['table']          = 'glpi_plugin_fusioninventory_tasks';
      $tab[4]['field']          = 'name';
      $tab[4]['linkfield']      = 'plugin_fusioninventory_tasks_id';
      $tab[4]['name']           = __('Task');
      $tab[4]['datatype']       = 'itemlink';
      $tab[4]['itemlink_type']  = 'PluginFusioninventoryTask';

      $tab[5]['table']          = $this->getTable();
      $tab[5]['field']          = 'status';
      $tab[5]['linkfield']      = '';
      $tab[5]['name']           = __('Status');

      $tab[6]['table']          = $this->getTable();
      $tab[6]['field']          = 'id';
      $tab[6]['linkfield']      = '';
      $tab[6]['name']           = __('ID');

      return $tab;
   }

   function getTask() {
      $pfTask = new PluginFusioninventoryTask();
      $pfTask->getFromDB($this->fields['plugin_fusioninventory_tasks_id']);
      return $pfTask;
   }

   /*
    * Manage definitions
    *
    * @param $id integer id of the taskjob
    * @param $type string type (definition or action)
    *
    * @return nothing
    */
   function manageDefinitionsActions($id, $type) {

      $this->getFromDB($id);

      echo "<form name='".$type."s_form' id='".$type."s_form' method='post' action='";
      echo Toolbox::getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>";
      if ($type == 'definition') {
         echo __('Definition', 'fusioninventory');
      } else if ($type == 'action') {
         echo __('Action', 'fusioninventory');
      }
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td width='45%'>".__('Type')."&nbsp;:</td>";
      echo "<td align='center' height='10' width='10%'>";
      echo "<span id='show_".ucfirst($type)."Type_id'>";
      echo "</span>";
      echo "</td>";
      echo "<td class='center' rowspan='2' width='45%'>";
      echo "<input type='submit' class='submit' name='".$type."_add' value='".
            __('Add')." >>'>";
      echo "<br><br>";

      $a_list = importArrayFromDB($this->fields[$type]);
      if ($a_list) {
         echo "<input type='submit' class='submit' name='".$type."_delete' value='<< ".
               __('Delete', 'fusioninventory')."'>";
      }
      echo "</td>";
      echo "<td rowspan='2'>";

      if ($a_list) {
         echo "<select name='".$type."_to_delete[]' multiple size='5'>";
         foreach ($a_list as $data) {
            print_r($data);
            $item_type = key($data);
            $item_id = current($data);
            $class = new $item_type();
            $name = '';
            if ($item_id == '.1') {
               $name = __('Auto managenement dynamic of agents', 'fusioninventory');

            } else if ($item_id == '.2') {
               $name = __('Auto managenement dynamic of agents (same subnet)', 'fusioninventory');

            } else {
               $class->getFromDB($item_id);
               $name = $class->getName();
            }
            echo "<option value='".$item_type.'-'.$item_id."'>[".$class->getTypeName()."] ".
                    $name."</option>";
         }
         echo "</select>";
      } else {
         echo "&nbsp;";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td>".__('Selection', 'fusioninventory')."&nbsp;:</td>";
      echo "<td align='center' height='10'>";
      echo "<span id='show_".ucfirst($type)."List".$id."'>";
      echo "</span>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "<input type='hidden' name='id' value='".$id."' />";
      Html::closeForm();
   }


   /**
   * Display definitions type (itemtypes)
   *
   * @param $myname value name of dropdown
   * @param $method value name of the method selected
   * @param $value value name of the definition type (used for edit taskjob)
   * @param $entity_restrict restriction of entity if required
   *
   * @return value rand of the dropdown
   *
   **/

   function dropdownType($myname, $method, $value=0, $taskjobs_id=0, $entity_restrict='') {
      global $CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_type = array();
      $a_type[''] = '------';
      if ($myname == 'action') {
         $a_type['PluginFusioninventoryAgent'] = PluginFusioninventoryAgent::getTypeName();
      }
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
            $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($module);
            if (is_callable(array($class, "task_".$myname."type_".$method))) {
               $a_type = call_user_func(array($class, "task_".$myname."type_".$method), $a_type);
            }
         }
      }

      $rand = Dropdown::showFromArray(ucfirst($myname)."Type", $a_type);

      $params=array(ucfirst($myname).'Type'=>'__VALUE__',
            'entity_restrict'=>$entity_restrict,
            'rand'=>$rand,
            'myname'=>ucfirst($myname)."Type",
            'name' => $myname,
            'method'=>$method,
            $myname.'typeid'=>'dropdown_'.ucfirst($myname).'Type'.$rand,
            'taskjobs_id'=>$taskjobs_id);

      Ajax::updateItemOnEvent(
              'dropdown_'.ucfirst($myname).'Type'.$rand,
              "show_".ucfirst($myname)."List".$taskjobs_id,
              $CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowntypelist.php",
              $params);

      return $rand;
   }

   /**
    * Get Itemtypes list for the selected method.
    */
   function getTypesForModule($method, $moduletype) {

      $available_methods = PluginFusioninventoryStaticmisc::getmethods();
      $types = array();
      //$a_type[''] = '------';
      if ($moduletype === 'actors') {
         $types['PluginFusioninventoryAgent'] = PluginFusioninventoryAgent::getTypeName();
      }

      /**
       * TODO: move staticmisc actors and targets related methods to the relevant Module classes
       * ( I don't have time for this yet and this is why i can live with a simple mapping string
       * table)
       */
      switch($moduletype) {
         case 'actors':
            $moduletype_tmp = 'action';
            break;

         case 'targets':
            $moduletype_tmp = 'definition';
            break;
      }

      foreach ($available_methods as $available_method) {
         if ($method == $available_method['method']) {
            $module = $available_method['module'];
            $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($module);
            $class_method = array($class, "task_".$moduletype_tmp."type_".$method);
            if (is_callable($class_method)) {
               $types = call_user_func($class_method, $types);
            }
         }
      }
      return $types;
   }


   /**
    * Get Items list from the Itemtype previously selected in the Module types dropdown
    */

   function getItemsForModuleItemtype($method, $itemtype) {

   }

   /**
   * Display definitions value with preselection of definition type
   *
   * @param $myname value name of dropdown
   * @param $definitiontype value name of the definition type selected
   * @param $method value name of the method selected
   * @param $deftypeid value dropdown name of definition type
   * @param $value value name of the definition (used for edit taskjob)
   * @param $entity_restrict restriction of entity if required
   *
   * @return value rand of the dropdown
   *
   **/
   function dropdownvalue($myname, $definitiontype, $method, $deftypeid, $taskjobs_id, $value=0,
                          $entity_restrict='', $title = 0) {
      global $CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $module = '';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
         }
      }

      $rand = '';
      $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($module);
      $iddropdown = '';
      if (is_callable(array($class, "task_".$_POST['name']."selection_".
            $definitiontype."_".$method))) {
         $rand = call_user_func(array($class,
                                      "task_".$_POST['name']."selection_".$definitiontype."_".
                                          $method),
                                $title);

         $iddropdown = "dropdown_".$_POST['name']."selectiontoadd";
      } else {
         $a_data = $this->get_agents($method);

         $rand = Dropdown::showFromArray($_POST['name'].'selectiontoadd', $a_data);
         $iddropdown = "dropdown_".$_POST['name']."selectiontoadd";
      }

      echo "<br/><center><input type='button' id='add_button_".$_POST['name'].$taskjobs_id."' ".
              "name='add_button_".$_POST['name']."' value=\"".__('Add').
              "\" class='submit'></center>";
      $params = array('items_id'  => '__VALUE0__',
                      'add_button_'.$_POST['name'].$taskjobs_id => '__VALUE1__',
                      'itemtype'  => $definitiontype,
                      'rand'      => $rand,
                      'myname'    => 'items_id',
                      'type'      => $_POST['name'],
                      'taskjobs_id'=>$taskjobs_id);
      Ajax::updateItemOnEvent(array($iddropdown.$rand , "add_button_".$_POST['name'].$taskjobs_id),
                              "Additem_$rand",
                              $CFG_GLPI["root_doc"].
                                 "/plugins/fusioninventory/ajax/taskjobaddtype.php",
                              $params,
                              array("click"),
                              "-1",
                              "-1",
                              array(__('Add')));


      echo "<span id='Additem_$rand'></span>";
   }






   /**
   * Display actions type (itemtypes)
   *
   * @param $myname value name of dropdown
   * @param $method value name of the method selected
   * @param $value value name of the definition type (used for edit taskjob)
   * @param $entity_restrict restriction of entity if required
   *
   * @return value rand of the dropdown
   *
   **/
   function dropdownActionType($myname, $method, $value=0, $entity_restrict='') {
      global $CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_actioninitiontype = array();
      $a_actioninitiontype[''] = '------';
      $a_actioninitiontype['PluginFusioninventoryAgent']= PluginFusioninventoryAgent::getTypeName();
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = ucfirst($datas['module']);
            $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($module);

            if (is_callable(array($class, "task_actiontype_".$method))) {
               $a_actioninitiontype = call_user_func(array($class, "task_actiontype_".$method),
                                                     $a_actioninitiontype);
            }
         }
      }

      $rand = Dropdown::showFromArray($myname, $a_actioninitiontype);

      $params=array('ActionType'=>'__VALUE__',
            'entity_restrict'=>$entity_restrict,
            'rand'=>$rand,
            'myname'=>$myname,
            'method'=>$method,
            'actiontypeid'=>'dropdown_'.$myname.$rand
            );
      Ajax::UpdateItemOnSelectEvent('dropdown_ActionType'.$rand,
                                    "show_ActionList",
                                    $CFG_GLPI["root_doc"].
                                       "/plugins/fusioninventory/ajax/dropdownactionlist.php",
                                    $params);

      return $rand;
   }



   /**
   * Display actions value with preselection of action type
   *
   * @param $myname value name of dropdown
   * @param $actiontype value name of the action type selected
   * @param $method value name of the method selected
   * @param $actiontypeid value dropdown name of action type
   * @param $value value name of the definition (used for edit taskjob)
   * @param $entity_restrict restriction of entity if required
   *
   * @return value rand of the dropdown
   *
   **/
   function dropdownAction($myname, $actiontype, $method, $actiontypeid, $value=0,
                           $entity_restrict='') {
      global $CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $module = '';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
         }
      }

      $rand = '';

      $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($module);
      if ($actiontype == "PluginFusioninventoryAgent") {
         $actionselection_method = "task_actionselection_PluginFusioninventoryAgent_".$method;
         if (is_callable(array($class, $actionselection_method))) {
            $rand = call_user_func(array($class, $actionselection_method));
         } else {
            $a_data = $this->get_agents($method);

            $rand = Dropdown::showFromArray('actionselectiontoadd', $a_data);
         }
      } else {
         $definitionselection_method = "task_definitionselection_".$actiontype."_".$method;
         if (is_callable(array($class, $definitionselection_method))) {
            $rand = call_user_func(array($class, $definitionselection_method));
         }
      }

      $params=array('selection'        => '__VALUE__',
                    'entity_restrict'  => $entity_restrict,
                    'myname'           => $myname,
                    'actionselectadd'  => 'dropdown_actionselectiontoadd'.$rand,
                    'actiontypeid'     => $actiontypeid);


      Ajax::UpdateItemOnEvent('addAObject', 'show_ActionListEmpty',
                              $CFG_GLPI["root_doc"].
                                 "/plugins/fusioninventory/ajax/dropdownactionselection.php",
                              $params, array("click"));

   }



   /**
   * Get all agents allowed to a module (task method)
   *
   * @param $module value name of dropdown
   *
   * @return array [id integed agent id] => $name value agent name
   *
   **/
   function get_agents($module) {

      $array = array();
      $array[".1"] = " ".__('Auto managenement dynamic of agents', 'fusioninventory');

      $array[".2"] = " ".__('Auto managenement dynamic of agents (same subnet)', 'fusioninventory');

      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $pfAgentmodule->getAgentsCanDo(strtoupper($module));
      foreach ($array1 as $id => $data) {
         $array[$id] = $data['name'];
      }
      asort($array);
      return $array;
   }


   /*
    * @function cronUpdateDynamicTasks
    * This function update already running tasks with dynamic groups
    */
   static function cronUpdateDynamicTasks() {
      global $DB;

      $pfTask = new PluginFusioninventoryTask();

      //Get every running tasks with dynamic groups
      $running_tasks = $pfTask->getItemsFromDB(
         array(
            'is_running'  => TRUE,
            'is_active'   => TRUE,
            'actors' => array('PluginFusioninventoryDeployGroup' => "")
         )
      );

      $pfTaskjob = new PluginFusioninventoryTaskjob();
      foreach ($running_tasks as $task) {
         $task['taskjob']['definitions_filter'] = array('PluginFusioninventoryDeployGroupDynamic', 'Group');
         $pfTaskjob->getFromDB($task['taskjob']['id']);
         $pfTaskjob->prepareRunTaskjob(
            $task['taskjob']
         );
      }

      if(isset($_SESSION['glpi_plugin_fusioninventory']['agents']) ) {
         foreach (array_keys($_SESSION['glpi_plugin_fusioninventory']['agents']) as $agents_id) {
            $pfTaskjob->startAgentRemotly($agents_id);
         }
         unset($_SESSION['glpi_plugin_fusioninventory']['agents']);
      }

      return 1;
   }


   /**
   * re initialize all taskjob of a taskjob
   *
   * @param $tasks_id integer id of the task
   *
   * @return bool TRUE if all taskjob are ready (so finished from old runnning job)
   *
   **/
   function reinitializeTaskjobs($tasks_id, $disableTimeVerification = 0) {
      global $DB;

      $pfTask         = new PluginFusioninventoryTask();
      $pfTaskjob      = new PluginFusioninventoryTaskjob();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog   = new PluginFusioninventoryTaskjoblog();
      $query = "SELECT *, UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp
            FROM `".$pfTask->getTable()."`
         WHERE `id`='".$tasks_id."'
         LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);

      $period = $pfTaskjob->periodicityToTimestamp($data['periodicity_type'],
                                                   $data['periodicity_count']);

      // Calculate next execution from last
      $queryJob = "SELECT * FROM `".$pfTaskjob->getTable()."`
         WHERE `plugin_fusioninventory_tasks_id`='".$tasks_id."'
         ORDER BY `id` DESC";
      $resultJob = $DB->query($queryJob);
      $nb_taskjobs = $DB->numrows($resultJob);
      // get only with execution_id (same +1) as task
      $queryJob = "SELECT * FROM `".$pfTaskjob->getTable()."`
         WHERE `plugin_fusioninventory_tasks_id`='".$tasks_id."'
            AND `execution_id`='".($data['execution_id'] + 1)."'
         ORDER BY `id` DESC";
      $finished = 2;
      $resultJob = $DB->query($queryJob);
      $nb_finished = 0;
      while ($dataJob=$DB->fetch_array($resultJob)) {
         $a_taskjobstateuniqs = $pfTaskjobstate->find(
                           "`plugin_fusioninventory_taskjobs_id`='".$dataJob['id']."'",
                           'id DESC',
                           1);
         $a_taskjobstateuniq = current($a_taskjobstateuniqs);
         $a_taskjobstate = $pfTaskjobstate->find(
                           "`plugin_fusioninventory_taskjobs_id`='".$dataJob['id']."'
                              AND `uniqid`='".$a_taskjobstateuniq['uniqid']."'");
         $taskjobstatefinished = 0;

         foreach ($a_taskjobstate as $statedata) {
            $a_joblog = $pfTaskjoblog->find(
                           "`plugin_fusioninventory_taskjobstates_id`='".$statedata['id']."'
                              AND (`state`='2' OR `state`='4' OR `state`='5')");
            if (count($a_joblog) > 0) {
               $taskjobstatefinished++;
            }
         }
         if ((count($a_taskjobstate) == $taskjobstatefinished)
                 AND (count($a_taskjobstate) > 0 )) {
            if ($finished == '2') {
               $finished = 1;
            }
            $nb_finished++;
         } else {
            $finished = 0;
         }
      }
      if ($nb_finished != $nb_taskjobs) {
         if ($disableTimeVerification == '1') { // Forcerun
            $queryJob2 = "SELECT * FROM `".$pfTaskjob->getTable()."`
            WHERE `plugin_fusioninventory_tasks_id`='".$tasks_id."'
               AND `execution_id`='".$data['execution_id']."'
            ORDER BY `id` DESC";
            $resultJob2 = $DB->query($queryJob2);
            if ($DB->numrows($resultJob2) == $nb_taskjobs) {
               $finished = 1;
               return TRUE;
            } else {
               $finished = 0;
            }
         } else {
            $finished = 0;
         }
      }
      // if all jobs are finished, we calculate if we reinitialize all jobs
      if ($finished == "1") {
         $exe = $data['execution_id'];
         unset($data['execution_id']);

         $queryUpdate = "UPDATE `".$pfTaskjob->getTable()."`
            SET `status`='0'
            WHERE `plugin_fusioninventory_tasks_id`='".$data['id']."'";
         $DB->query($queryUpdate);

         if ($period != '0') {
            if (is_null($data['date_scheduled_timestamp'])) {
               $data['date_scheduled_timestamp'] = date('U');
            }
            if (($data['date_scheduled_timestamp'] + $period) <= date('U')
                    AND $period =! '0') {
               $periodtotal = $period;
               for($i=2; ($data['date_scheduled_timestamp'] + $periodtotal) <= date('U'); $i++) {
                  $periodtotal = $period * $i;
               }
               $data['date_scheduled'] = date("Y-m-d H:i:s",
                                              $data['date_scheduled_timestamp'] + $periodtotal);
            } else if ($data['date_scheduled_timestamp'] > date('U')) {
               // Don't update date next execution

            } else {
               $data['date_scheduled'] = date("Y-m-d H:i:s",
                                              $data['date_scheduled_timestamp'] + $period);
            }
         }
         $data['execution_id'] = $exe + 1;
         unset($data['comment']);
         $pfTask->update($data);
         return TRUE;
      } else {
         return FALSE;
      }
   }



   /**
   * Force running a task
   *
   * @param $tasks_id integer id of the task
   *
   * @return number uniqid
   *
   **/
   function forceRunningTask($tasks_id) {
      global $DB;

      $uniqid = '';

      if ($this->reinitializeTaskjobs($tasks_id, 1)) {

         $pfTaskjob = new PluginFusioninventoryTaskjob();
         $_SESSION['glpi_plugin_fusioninventory']['agents'] = array();

         $query = "SELECT `".$pfTaskjob->getTable()."`.*,
               `glpi_plugin_fusioninventory_tasks`.`communication`,
               UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp
            FROM ".$pfTaskjob->getTable()."
            LEFT JOIN `glpi_plugin_fusioninventory_tasks`
               ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
            WHERE `is_active`='1'
               AND `status` = '0'
               AND `glpi_plugin_fusioninventory_tasks`.`id`='".$tasks_id."'
               AND `".$pfTaskjob->getTable()."`.`plugins_id` != '0'
               AND `".$pfTaskjob->getTable()."`.`method` IS NOT NULL
               AND `".$pfTaskjob->getTable()."`.`method` != ''
            ORDER BY `id`";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
//            $plugin = new Plugin();
//            $plugin->getFromDB($data['plugins_id']);
//            if ($plugin->fields['state'] == Plugin::ACTIVATED) {
               $uniqid = $pfTaskjob->prepareRunTaskjob($data);
//            }
         }
         foreach (array_keys($_SESSION['glpi_plugin_fusioninventory']['agents']) as $agents_id) {
            $pfTaskjob->startAgentRemotly($agents_id);
         }
         unset($_SESSION['glpi_plugin_fusioninventory']['agents']);
      } else {
         $_SESSION["MESSAGE_AFTER_REDIRECT"] =
               __('Unable to run task because some jobs is running yet!', 'fusioninventory');

      }
      return $uniqid;
   }



   /**
   * Get period in secondes by type and count time
   *
   * @param $periodicity_type value type of time (minutes, hours...)
   * @param $periodicity_count integer number of type time
   *
   * @return interger in seconds
   *
   **/
   function periodicityToTimestamp($periodicity_type, $periodicity_count) {
      $period = 0;
      switch($periodicity_type) {

         case 'minutes':
            $period = $periodicity_count * 60;
            break;

         case 'hours':
            $period = $periodicity_count * 60 * 60;
            break;

         case 'days':
            $period = $periodicity_count * 60 * 60 * 24;
            break;

         case 'months':
            $period = $periodicity_count * 60 * 60 * 24 * 30; //month
            break;

         default:
            $period = 0;
      }
      return $period;
   }

   /**
   * Display actions possible in device
   *
   * @return nothing
   *
   **/
   function showActions($items_id, $itemtype) {
      global $CFG_GLPI;

      // load all plugin and get method possible
      /*
       * Example :
       * * inventory
       * * snmpquery
       * * wakeonlan
       * * deploy => software
       *
       */

      echo "<div align='center'>";
      echo "<form method='post' name='' id=''  action=\"".$CFG_GLPI['root_doc'] .
               "/plugins/fusioninventory/front/taskjob.form.php\">";

      echo "<table  class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='4'>";
      echo __('Action on this device', 'fusioninventory');

      echo " : </th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo __('Job', 'fusioninventory')."&nbsp;:";
      echo "</td>";

      echo "<td align='center'>";
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_parseMethods = array();
      $a_parseMethods[''] = "------";
      foreach($a_methods as $data) {
         $class = PluginFusioninventoryStaticmisc::getStaticmiscClass($data['directory']);

         if (is_callable(array($class, 'task_action_'.$data['method']))) {
            $a_itemtype = call_user_func(array($class, 'task_action_'.$data['method']));
            if (in_array($itemtype, $a_itemtype)) {
               $a_parseMethods[$data['module']."||".$data['method']] = $data['method'];
            }
         }
      }
      Dropdown::showFromArray('methodaction', $a_parseMethods);
      echo "</td>";

      echo "<td align='center'>";
      echo __('Scheduled date', 'fusioninventory')."&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      Html::showDateTimeFormItem("date_scheduled", date("Y-m-d H:i:s"), 1);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='4'>";
      echo "<input type='hidden' name='items_id' value='".$items_id."'/>";
      echo "<input type='hidden' name='itemtype' value='".$itemtype."'/>";
      echo "<input type='submit' name='itemaddaction' value=\"".__('Add')."\" class='submit' >";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
      echo "</div>";

   }



   /**
   * Redirect url to task and right taskjob tab
   *
   * @param $taskjobs_id integer id of the taskjob
   *
   * @return nothing
   *
   **/
   function redirectTask($taskjobs_id) {

      $this->getFromDB($taskjobs_id);

      $a_taskjob = $this->find(
         "`plugin_fusioninventory_tasks_id`='".$this->fields['plugin_fusioninventory_tasks_id']."'
            AND `rescheduled_taskjob_id`='0' ", "id");
      $i = 1;
      $tab = 0;
      foreach (array_keys($a_taskjob) as $id) {
         $i++;
         if ($id == $taskjobs_id) {
            $tab = $i;
         }
      }
      Html::redirect(Toolbox::getItemTypeFormURL('PluginFusioninventoryTask').
                                     "?itemtype=PluginFusioninventoryTask&id=".
                                     $this->fields['plugin_fusioninventory_tasks_id'].
                                     "&glpi_tab=".$tab);
   }



   /**
   * Display task informations for an object
   *
   * @param $itemtype value item type of object
   * @param $items_id integer id of the object
   *
   * @return nothing
   *
   **/
   function manageTasksByObject($itemtype='', $items_id=0) {
      // See task runing
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjobstate->stateTaskjobItem($items_id, $itemtype, 'running');
      // see tasks finished
      $pfTaskjobstate->stateTaskjobItem($items_id, $itemtype, 'nostarted');
      // see tasks finished
      $pfTaskjobstate->stateTaskjobItem($items_id, $itemtype, 'finished');
   }



   /**
    * Finish task if have some problem or started for so long time
    *
    * @return nothing
    */
   function CronCheckRunnningJobs() {
      global $DB;

      // Get all taskjobstate running
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog   = new PluginFusioninventoryTaskjoblog();

      $a_taskjobstate = $pfTaskjobstate->find("`state`='0'
                                               OR `state`='1'
                                               OR `state`='2'
                                               GROUP BY uniqid, plugin_fusioninventory_agents_id");
      foreach($a_taskjobstate as $data) {
         $sql = "SELECT * FROM `glpi_plugin_fusioninventory_tasks`
            LEFT JOIN `glpi_plugin_fusioninventory_taskjobs`
               ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
            WHERE `glpi_plugin_fusioninventory_taskjobs`.`id`='".
                 $data['plugin_fusioninventory_taskjobs_id']."'
            LIMIT 1 ";
         $result = $DB->query($sql);
         if ($DB->numrows($result) != 0) {
            $task = $DB->fetch_assoc($result);
            if ($task['communication'] == 'pull') {
               $has_recent_log_entries = $pfTaskjoblog->find(
                       "`plugin_fusioninventory_taskjobstates_id`='".$data['id']."'",
                       "id DESC", "1");
               $finish = FALSE;
               if (count($has_recent_log_entries) == 1) {
                  $data2 = current($has_recent_log_entries);
                  $date = strtotime($data2['date']);
                  $date += (4 * 3600);
                  if ($date < date('U')) {
                     $finish = TRUE;
                  }
               } else {
                  $finish = TRUE;
               }

               # No news from the agent since 4 hour. The agent is probably crached.
               //Let's cancel the task
               if ($finish) {
                     $a_statustmp = $pfTaskjobstate->find("`uniqid`='".$data['uniqid']."'
                              AND `plugin_fusioninventory_agents_id`='".
                                 $data['plugin_fusioninventory_agents_id']."'
                              AND (`state`='2' OR `state`='1') ");
                     foreach($a_statustmp as $datatmp) {
                        $pfTaskjobstate->changeStatusFinish($datatmp['id'],
                                                            0,
                                                            '',
                                                            1,
                                                            "==agentcrashed==");
                     }
               }
            } else if ($task['communication'] == 'push') {
               $a_valid = $pfTaskjoblog->find(
                       "`plugin_fusioninventory_taskjobstates_id`='".$data['id']."'
                          AND ADDTIME(`date`, '00:10:00') < NOW()", "id DESC", "1");

               if (count($a_valid) == '1') {
                  // Get agent status
                  $agentreturn = $this->getRealStateAgent(
                                                $data['plugin_fusioninventory_agents_id']);

                  switch ($agentreturn) {

                     case 'waiting':
                        // token is bad and must force cancel task in server
                        $a_statetmp = $pfTaskjobstate->find("`uniqid`='".$data['uniqid']."'
                                 AND `plugin_fusioninventory_agents_id`='".
                                    $data['plugin_fusioninventory_agents_id']."'
                                 AND (`state`='2' OR `state`='1' OR `state`='0') ");
                        foreach($a_statetmp as $datatmp) {
                           $pfTaskjobstate->changeStatusFinish($datatmp['id'],
                                                                 0,
                                                                 '',
                                                                 1,
                                                                 "==badtoken==");
                        }
                        break;

                     case 'running':
                         // just wait and do nothing

                        break;

                     case 'noanswer':
                        // agent crash or computer is shutdown and force cancel task in server
                        $a_statetmp = $pfTaskjobstate->find("`uniqid`='".$data['uniqid']."'
                                       AND `plugin_fusioninventory_agents_id`='".
                                          $data['plugin_fusioninventory_agents_id']."'
                                       AND (`state`='2' OR `state`='1') ");
                        foreach($a_statetmp as $datatmp) {
                           $pfTaskjobstate->changeStatusFinish($datatmp['id'],
                                                               0,
                                                               '',
                                                               1,
                                                               "==agentcrashed==");
                        }
                        $a_valid4h = $pfTaskjoblog->find(
                                "`plugin_fusioninventory_taskjobstates_id`='".$data['id']."'",
                                "id DESC", "1");
                        $finish = FALSE;
                        if (count($a_valid4h) == 1) {
                           $datajs = current($a_valid4h);
                           $date = strtotime($datajs['date']);
                           $date += (4 * 3600);
                           if ($date < date('U')) {
                              $finish = TRUE;
                           }
                        } else {
                           $finish = TRUE;
                        }

                        if ($finish) {
                           $a_statetmp = $pfTaskjobstate->find("`uniqid`='".$data['uniqid']."'
                                                   AND `plugin_fusioninventory_agents_id`='".
                                                      $data['plugin_fusioninventory_agents_id']."'
                                                   AND `state`='0' ");
                           foreach($a_statetmp as $datatmp) {
                              $pfTaskjobstate->changeStatusFinish($datatmp['id'],
                                                                  0,
                                                                  '',
                                                                  1,
                                                                  "==agentcrashed==");
                           }


                        }
                        break;

                     case 'noip':
                        // just wait and do nothing

                        break;

                  }
               }
            }
         }
      }

      // If taskjob.status = 1 and all taskjobstates are finished, so reinitializeTaskjobs()
      $sql = "SELECT *
      FROM `glpi_plugin_fusioninventory_taskjobs`
      WHERE (
         SELECT count(*) FROM glpi_plugin_fusioninventory_taskjobstates
         WHERE plugin_fusioninventory_taskjobs_id = `glpi_plugin_fusioninventory_taskjobs`.id
            AND glpi_plugin_fusioninventory_taskjobstates.state <3) = 0
            AND `glpi_plugin_fusioninventory_taskjobs`.`status`=1";
      $result=$DB->query($sql);
      while ($data=$DB->fetch_array($result)) {
         $this->reinitializeTaskjobs($data['plugin_fusioninventory_tasks_id'], '1');
      }
   }



   /**
    * Check for configuration consistency.
    * Remove items targets or actors that have been deleted.
    *
    * @return boolean ( What does this return value mean ? -- Kevin Roy <kiniou@gmail.com> )
    */
   function checkConfiguration() {

      $return = true;
      $input = array();
      $input['id'] = $this->fields['id'];
      $targets = importArrayFromDB($this->fields['targets']);
      foreach ($targets as $num=>$data) {
         $classname = key($data);
         if ($classname == '') {
            unset($targets[$num]);
         } else {
            $Class = new $classname;
            if (!$Class->getFromDB(current($data))) {
               unset($targets[$num]);
            }
         }
      }
      if (count($targets) == '0') {
         $input['targets'] = '';
         $return = FALSE;
      } else {
         $input['targets'] = exportArrayToDB($targets);
      }
      $actors = importArrayFromDB($this->fields['actors']);
      foreach ($actors as $num=>$data) {
         $classname = key($data);
         $Class = new $classname;
         if (!$Class->getFromDB(current($data))
                 AND (current($data) != ".1")
                 AND (current($data) != ".2")) {
            unset($actors[$num]);
         }
      }
      if (count($actors) == '0') {
         $input['actors'] = '';
         $return = FALSE;
      } else {
         $input['actors'] = exportArrayToDB($actors);
      }
      $this->update($input);
      return $return;
   }



   /**
    * Purge taskjoblog/state when delete taskjob
    *
    * @param type $parm
    *
    * @return nothing
    */
   static function purgeTaskjob($parm) {
      // $parm["id"]
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog   = new PluginFusioninventoryTaskjoblog();

      // all taskjobs
      $a_taskjobstates = $pfTaskjobstate->find(
              "`plugin_fusioninventory_taskjobs_id`='".$parm->fields["id"]."'");
      foreach($a_taskjobstates as $a_taskjobstate) {
         $a_taskjoblogs = $pfTaskjoblog->find(
                 "`plugin_fusioninventory_taskjobstates_id`='".$a_taskjobstate['id']."'");
         foreach($a_taskjoblogs as $a_taskjoblog) {
            $pfTaskjoblog->delete($a_taskjoblog, 1);
         }
         $pfTaskjobstate->delete($a_taskjobstate, 1);
      }
   }



   /**
    * Force end task
    */
   function forceEnd() {
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

      $a_taskjobstates =
         $pfTaskjobstate->find("`plugin_fusioninventory_taskjobs_id`='". $this->fields["id"]."'");

      //TODO: in order to avoid too many atomic operations on DB, convert the
      //following into a massive prepared operation (ie. ids in one massive action)
      foreach($a_taskjobstates as $a_taskjobstate) {
         $pfTaskjobstate->getFromDB($a_taskjobstate['id']);
         if ($a_taskjobstate['state'] != PluginFusioninventoryTaskjobstate::FINISHED) {
               $pfTaskjobstate->changeStatusFinish(
                     $a_taskjobstate['id'], 0, '', 1, "Action cancelled by user", 0, 0
               );
         }
      }
      $this->reinitializeTaskjobs($this->fields['plugin_fusioninventory_tasks_id']);
   }

   /*
    * Display static list of taskjob
    *
    * @param $method value method name of taskjob to display
    *
    */
   static function quickList($method) {

      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTask = new PluginFusioninventoryTask();

      $a_list = $pfTaskjob->find("`method`='".$method."'");

      echo "<table class='tab_cadrehov' style='width:950px'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>".__('Name')."</th>";
      echo "<th>".__('Active')."</th>";
      echo "<th>".__('Scheduled date', 'fusioninventory')."</th>";
      echo "<th>".__('Periodicity', 'fusioninventory')."</th>";
      echo "<th>".__('Definition', 'fusioninventory')."</td>";
      echo "<th>".__('Action', 'fusioninventory')."</th>";
      echo "</tr>";

      foreach ($a_list as $data) {
         $pfTaskjob->getFromDB($data['id']);
         $pfTask->getFromDB($data['plugin_fusioninventory_tasks_id']);
         echo "<tr class='tab_bg_1'>";
         $link_item = $pfTaskjob->getFormURL();
         $link  = $link_item;
         $link .= (strpos($link, '?') ? '&amp;':'?').'id=' . $pfTaskjob->fields['id'];
         echo "<td><a href='".$link."'>".$pfTaskjob->getNameID(1)."</a></td>";
         echo "<td>".Dropdown::getYesNo($pfTask->fields['is_active'])."</td>";
         echo "<td>".$pfTask->fields['date_scheduled']."</td>";
         $a_time = '';
         switch ($pfTask->fields['periodicity_type']) {

            case 'minutes':
               $a_time = $pfTask->fields['periodicity_count']." ".
               strtolower(__('Minute(s)', 'fusioninventory'));

               break;

            case 'hours':
               $a_time = $pfTask->fields['periodicity_count']." ".
                    strtolower(__('hour(s)', 'fusioninventory'));

               break;

            case 'days':
               $a_time = $pfTask->fields['periodicity_count']." ".
                    __('day(s)', 'fusioninventory');

               break;

            case 'months':
               $a_time = $pfTask->fields['periodicity_count']." ".
                    __('months');

               break;
         }

         echo "<td>".$a_time."</td>";
         $a_defs = importArrayFromDB($data['definition']);
         echo "<td>";
         foreach ($a_defs as $datadef) {
            foreach ($datadef as $itemtype=>$items_id) {
               $class = new $itemtype;
               $class->getFromDB($items_id);
               echo $class->getLink(1)." (".$class->getTypeName().")<br/>";
            }
         }
         echo "</td>";
         echo "<td>";
         $a_acts = importArrayFromDB($data['action']);
         foreach ($a_acts as $dataact) {
            foreach ($dataact as $itemtype=>$items_id) {
               $class = new $itemtype();
               $itemname = $class->getTypeName();
               $class->getFromDB($items_id);
               $name = '';
               if ($items_id == '.1') {
                  $name = __('Auto managenement dynamic of agents', 'fusioninventory');

               } else if ($items_id == '.2') {
                  $name =
                        __('Auto managenement dynamic of agents (same subnet)', 'fusioninventory');

               } else {
                  $name = $class->getLink(1);
               }
               echo $name.' ('.$itemname.')<br/>';
            }
         }
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
   }



   /*
    * Quick add or update taskjob from Wizard
    * TODO: As of 0.85, this is disabled until we refactor the Wizard class.
    *
    * @param $id integer id of taskjobs
    * @param $method string method name
    *
    */
   //function showQuickForm($id, $method) {
   //   global $CFG_GLPI;

   //   $pfTask = new PluginFusioninventoryTask();
   //   if (($id!='') AND ($id != '0')) {
   //      $this->getFromDB($id);
   //      $pfTask->getFromDB($this->fields['plugin_fusioninventory_tasks_id']);
   //   } else {
   //      $this->getEmpty();
   //      $pfTask->getEmpty();
   //   }

   //   if (strstr($_SERVER['PHP_SELF'], 'wizard')) {
   //      echo "<a href=\"javascript:showHideDiv('tabsbody', 'tabsbodyimg', '".$CFG_GLPI["root_doc"].
   //                 "/pics/deplier_down.png', '".$CFG_GLPI["root_doc"]."/pics/deplier_up.png')\">";
   //      echo "<img alt='' name='tabsbodyimg' ".
   //              "src=\"".$CFG_GLPI["root_doc"]."/pics/deplier_up.png\">";
   //      echo "</a>&nbsp;&nbsp;";

   //      echo "<a href=\"".$_SERVER['PHP_SELF']."?wizz=".$_GET['wizz'].
   //              "&ariane=".$_GET['ariane']."\">";
   //      echo __('List');

   //      echo "</a>";

   //   } else {
   //      $this->showTabs();
   //   }
   //   $this->showFormHeader(array());

   //   $a_methods = PluginFusioninventoryStaticmisc::getmethods();
   //   foreach ($a_methods as $datas) {
   //      echo "<input type='hidden' name='method-".$datas['method']."' ".
   //              "value='".PluginFusioninventoryModule::getModuleId($datas['module'])."' />";
   //   }

   //   echo "<tr class='tab_bg_1'>";
   //   echo "<td>".__('Name')."&nbsp;:</td>";
   //   echo "<td><input type='text' name='name' value='".$this->fields['name']."' /></td>";
   //   echo "<td>".__('Active')."&nbsp;:</td>";
   //   echo "<td>";
   //   Dropdown::showYesNo("is_active", $pfTask->fields['is_active']);
   //   echo "</td>";
   //   echo "</tr>";

   //   echo "<tr class='tab_bg_1' style='display:none'>";
   //   echo "<td colspan='4'>";
   //   echo "<input type='hidden' name='quickform' value='1' />";
   //   $rand = $this->dropdownMethod("method", $method);
   //   echo "</td>";
   //   echo "</tr>";

   //   echo "<tr class='tab_bg_1'>";
   //   echo "<td>".__('Communication type', 'fusioninventory')."&nbsp;:</td>";
   //   echo "<td>";
   //   $com = array();
   //   $com['push'] = __('Server contacts the agent (push)', 'fusioninventory');

   //   $com['pull'] = __('Agent contacts the server (pull)', 'fusioninventory');

   //   Dropdown::showFromArray("communication",
   //                           $com,
   //                           array('value'=>$pfTask->fields["communication"]));
   //   echo "</td>";
   //   echo "<td>".__('Periodicity')."&nbsp;:</td>";
   //   echo "<td>";
   //   Dropdown::showNumber("periodicity_count", array(
   //             'value' => $this->fields['periodicity_count'],
   //             'min'   => 0,
   //             'max'   => 300)
   //   );
   //   $a_time = array();
   //   $a_time[] = "------";
   //   $a_time['minutes'] = __('Minute(s)', 'fusioninventory');

   //   $a_time['hours'] = ucfirst(__('hour(s)', 'fusioninventory'));

   //   $a_time['days'] = ucfirst(__('day(s)', 'fusioninventory'));

   //   $a_time['months'] = ucfirst(__('month(s)', 'fusioninventory'));

   //   Dropdown::showFromArray("periodicity_type",
   //                           $a_time,
   //                           array('value'=>$pfTask->fields['periodicity_type']));
   //   echo "</td>";
   //   echo "</tr>";

   //   if ($id) {
   //      $this->showFormButtons(array());

   //      $this->manageDefinitionsActions($id, "definition");
   //      $this->manageDefinitionsActions($id, "action");

   //      $params=array('method_id'=>'__VALUE__',
   //            'entity_restrict'=>'',
   //            'rand'=>$rand,
   //            'myname'=>"method"
   //            );
   //      echo "<script type='text/javascript'>";
   //      Ajax::UpdateItemJsCode("show_DefinitionType_id",
   //                             $CFG_GLPI["root_doc"].
   //                                "/plugins/fusioninventory/ajax/dropdowndefinitiontype.php",
   //                             $params,
   //                             TRUE,
   //                             "dropdown_method".$rand);
   //      echo "</script>";
   //      echo "<script type='text/javascript'>";
   //      Ajax::UpdateItemJsCode("show_ActionType_id",
   //                             $CFG_GLPI["root_doc"].
   //                                "/plugins/fusioninventory/ajax/dropdownactiontype.php",
   //                             $params,
   //                             TRUE,
   //                             "dropdown_method".$rand);
   //      echo "</script>";
   //   } else  {
   //      $this->showFormButtons(array());
   //   }
   //}



   /*
    * List of taskjob to forcerun
    */
   static function listToForcerun($method) {

      $pfTaskjob = new self();
      $a_list = $pfTaskjob->find("`method`='".$method."'");

      echo "<form name='form_ic' method='post' action='".Toolbox::getItemTypeFormURL(__CLASS__).
              "'>";
      echo "<table class='tab_cadre_fixe' style='width:500px'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2' align='center'>".__('Force start', 'fusioninventory')."</th>";
      echo "</tr>";

      if (isset($_SESSION['plugin_fusioninventory_wizard'])
              AND isset($_SESSION['plugin_fusioninventory_wizard']['tasks_id'])) {
         $a_tasksjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".
                 $_SESSION['plugin_fusioninventory_wizard']['tasks_id']."'");
         $data = current($a_tasksjobs);
         $pfTaskjob->getFromDB($data['id']);
         echo "<tr class='tab_bg_1'>";
         echo "<td><input type='checkbox' name='taskjobstoforcerun[]' value='".$data['id']."' ".
                 "checked /></td>";
         $link_item = $pfTaskjob->getFormURL();
         $link  = $link_item;
         $link .= (strpos($link, '?') ? '&amp;':'?').'id=' . $pfTaskjob->fields['id'];
         echo "<td><a href='".$link."'>".$pfTaskjob->getNameID(1)."</a></td>";
         echo "<tr class='tab_bg_1'>";
      } else {
         foreach ($a_list as $data) {
            $pfTaskjob->getFromDB($data['id']);
            echo "<tr class='tab_bg_1'>";
            echo "<td><input type='checkbox' name='taskjobstoforcerun[]' ".
                    "value='".$data['id']."' /></td>";
            $link_item = $pfTaskjob->getFormURL();
            $link  = $link_item;
            $link .= (strpos($link, '?') ? '&amp;':'?').'id=' . $pfTaskjob->fields['id'];
            echo "<td><a href='".$link."'>".$pfTaskjob->getNameID(1)."</a></td>";
            echo "<tr class='tab_bg_1'>";
         }
      }

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2' align='center'>";
      echo '<input name="forcestart" value="'.__('Force start', 'fusioninventory').'"
          class="submit" type="submit">';
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
   }



   /*
    * List of last logs (uniqid) of taskjob
    */
   static function quickListLogs() {

      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfTaskjob = new PluginFusioninventoryTaskjob();

      if (isset($_SESSION["plugin_fusioninventory_forcerun"])) {
         foreach ($_SESSION["plugin_fusioninventory_forcerun"] as $taskjobs_id=>$uniqid) {
            $pfTaskjob->getFromDB($taskjobs_id);

            echo "<table class='tab_cadrehov' style='width:950px'>";
            echo "<tr class='tab_bg_1'>";
            echo "<th>".$pfTaskjob->getLink(1)."</th>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>";
            $pfTaskjoblog->showHistory($taskjobs_id, 950, array('uniqid'=>$uniqid));
            echo "</td>";

            echo "</table>";
            echo "<br/>";
         }
      }
   }



   /**
    * Function used to add item in definition or action of a taskjob
    *    and hide add form
    *    and refresh type list
    *
    * @param $type value (definition or action)
    */
   function additemtodefatc($type, $itemtype, $items_id, $taskjobs_id) {
      global $CFG_GLPI;

      $this->getFromDB($taskjobs_id);
      $a_type = importArrayFromDB($this->fields[$type]);
      $add = 1;
      foreach ($a_type as $data) {
         foreach ($data as $key=>$val) {
            if ($itemtype == $key AND $items_id == $val) {
               $add = 0;
            }
         }
      }
      if ($add == '1') {
         $a_type[] = array($itemtype => $items_id);
         $input = array();
         $input['id'] = $this->fields['id'];
         $input[$type] = exportArrayToDB($a_type);
         $this->update($input);
      }

      //TODO: Clean add form
      echo "<script type='text/javascript'>
      //document.getElementById('show_".ucfirst($type)."List').innerHTML='&nbsp';

      Ext.get('".$type.$taskjobs_id."').setDisplayed('none');
      </script>";
      // reload item list
      $params = array();
      $params['taskjobs_id'] = $taskjobs_id;
      $params['typename'] = $type;
      echo "<script type='text/javascript'>";
      Ajax::UpdateItemJsCode("show".$type."list".$taskjobs_id."_",
                                $CFG_GLPI["root_doc"].
                                   "/plugins/fusioninventory/ajax/dropdownlist.php",
                                $params);
      echo "</script>";
   }



   function deleteitemtodefatc($type, $a_items_id, $taskjobs_id) {
      global $CFG_GLPI;

      $this->getFromDB($taskjobs_id);
      $a_type = importArrayFromDB($this->fields[$type]);
      $split = explode("-", $a_items_id);
      foreach ($split as $key) {
         unset($a_type[$key]);
      }
      $input = array();
      $input['id'] = $this->fields['id'];
      $input[$type] = exportArrayToDB($a_type);
      $this->update($input);

      // reload item list
      $params = array();
      $params['taskjobs_id'] = $taskjobs_id;
      $params['typename'] = $type;
      echo "<script type='text/javascript'>";
      Ajax::UpdateItemJsCode("show".$type."list".$taskjobs_id."_",
                                $CFG_GLPI["root_doc"].
                                   "/plugins/fusioninventory/ajax/dropdownlist.php",
                                $params);
      echo "</script>";
   }



   /**
    * Display + button to add definition or action
    *
    * @param $name string name of the action (here definition or action)
    *
    * @return nothing
    */
   function plusButton($name) {
      global $CFG_GLPI;

      if ($this->canUpdate()) {
         echo "&nbsp;";
         echo "<img onClick=\"Ext.get('".$name."').setDisplayed('block')\"
                    title=\"".__('Add')."\" alt=\"".__('Add')."\"
                    class='pointer'  src='".$CFG_GLPI["root_doc"]."/pics/add_dropdown.png'>";
      }
   }






   function prepareRunTaskjob($a_taskjob) {
      $pfTaskjob = new PluginFusioninventoryTaskjob();

      $uniqid = 0;
//      if ($pfTaskjob->verifyDefinitionActions($a_taskjob['id'])) {

         $input = array();
         $input['id'] = $a_taskjob['id'];
         $input['execution_id'] = $a_taskjob['execution_id'] + 1;
         $pfTaskjob->update($input);

         $itemtype = "PluginFusioninventory".ucfirst($a_taskjob['method']);
         $item = new $itemtype;

         if (
            in_array(
               $a_taskjob['method'],
               array('deployinstall', 'deployuninstall')
            ) && isset( $a_taskjob['definitions_filter'] )
         ) {
            $uniqid = $item->prepareRun($a_taskjob['id'], $a_taskjob['definitions_filter']);
         } else {
            $uniqid = $item->prepareRun($a_taskjob['id']);
         }
         return $uniqid;
//      }
   }



   static function functionWizardEnd() {
      global $CFG_GLPI;

      echo "<form method='post' name='' id=''  action=\"".$CFG_GLPI['root_doc'] .
         "/plugins/fusioninventory/front/wizard.form.php\">";

      echo "<table class='tab_cadre' width='700'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>".__('Action after finish running task', 'fusioninventory')."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td width='10'><input type='radio' name='endtask[]' value='finishdelete' /></td>";
      echo "<td>".__('Delete this task and finish', 'fusioninventory')."</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td><input type='radio' name='endtask[]' value='finish' /></td>";
      echo "<td>".__('Finish', 'fusioninventory')."</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td><input type='radio' name='endtask[]' value='runagain' /></td>";
      echo "<td>".__('Run again this task', 'fusioninventory')."</td>";
      echo "</tr>";

      echo "</table>";
   }



   function updateMethod($method, $taskjobs_id) {

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $input = array();
            $input['id'] = $taskjobs_id;
            $input['method'] = $method;
            $input['plugins_id'] = PluginFusioninventoryModule::getModuleId($datas['module']);
            $this->update($input);
         }
      }
   }



   function displayList($tasks_id) {
      global $CFG_GLPI;

      $rand = mt_rand();

      echo "<script type=\"text/javascript\">
function edit_subtype(id,el) {

   //remove all border to previous selected item (remove classes)
//   Ext.select('#table_taskjob_'+ _rand +' tr').removeClass('selected');


   var row = null;
   if (el) {
      // get parent row of the selected element
      row = jQuery(el).parents('tr:first')
   }

   if (row) {
      //add border to selected index (add class)
      row.addClass('selected');
//      params['index'] = row.index();
      // change mode to edit
//      params['mode'] = 'edit';
      arg = 'taskjobs_id=' + id;
   } else {
      arg = 'tasks_id=' + id;
   }

   //scroll to edit form
//   document.getElementById('th_title_taskjob_' + _rand).scrollIntoView();

   //show and load form
//   $('taskjobs_block' + _rand).setDisplayed('block');
   $('#taskjobs_block').load('../ajax/taskjob_form.php?' + arg);
}

/*
 * Create a new subtype element.
 * This method just override *edit_subtype* with a null element.
 */
function new_subtype(id) {
   edit_subtype(id, null);
}
</script>";

      echo "<table class='tab_cadre_fixe' id='package_order_".$tasks_id."'>";

      echo "<tr>";
      echo "<th id='th_title_taskjob_$rand'>";
      //echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/$subtype.png' />";
      echo "&nbsp;".$this->getTypeName();

      echo "&nbsp;";
      echo "<img id='plus_taskjobs_block{$rand}'";
      echo " onclick=\"new_subtype({$tasks_id})\" ";
      echo  " title='".__('Add')."' alt='".__('Add')."' ";
      echo  " class='pointer' src='".
            $CFG_GLPI["root_doc"]."/pics/add_dropdown.png' /> ";

      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<td style='vertical-align:top'>";

      /**
       * Display subtype form
       **/
      echo "<form name='additiontaskjob' method='post' ".
         " action='taskjob.form.php'>";
      echo "<input type='hidden' name='orders_id' value='$tasks_id' />";
      echo "<input type='hidden' name='itemtype' value='PluginFusioninventoryDeploy".
         ucfirst('taskjob')."' />";

      echo "<div id='taskjobs_block'></div>";
      Html::closeForm();

      $a_taskjobs = getAllDatasFromTable(
              $this->getTable(),
              "`plugin_fusioninventory_tasks_id`='".$tasks_id."'",
              FALSE,
              '`ranking`');
      echo  "<div id='drag_taskjob_taskjobs'>";
      echo "<table class='tab_cadrehov package_item_list' id='table_taskjob_$rand' style='width: 950px'>";
      $i=0;
      foreach ($a_taskjobs as $data) {
         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         echo "<td class='control'>";
         echo "<input type='checkbox' name='taskjob_entries[]' value='$i' />";
         echo "</td>";
         echo "<td>";
         echo "<a class='edit' ".
                 "onclick=\"edit_subtype({$data['id']}, this)\">";
         echo $data['name'];
         echo "</a><br />";

         echo "<b>";
         echo __('Definition', 'fusioninventory');
         echo "</b>";
         echo "<ul class='retChecks'>";
         $a_definitions = importArrayFromDB($data['definition']);
         foreach ($a_definitions as $a_definition) {
            foreach ($a_definition as $itemtype=>$items_id) {
               echo "<li>";
               $item = new $itemtype();
               $item->getFromDB($items_id);
               echo $item->getTypeName()." > ";
               echo $item->getLink();
               echo "</li>";
            }
         }
         echo "</ul>";

         echo "<b>";
         echo __('Action', 'fusioninventory');
         echo "</b>";
         echo "<ul class='retChecks'>";
         $a_actions = importArrayFromDB($data['action']);
         foreach ($a_actions as $a_action) {
            foreach ($a_action as $itemtype=>$items_id) {
               echo "<li>";
               $item = new $itemtype();
               $item->getFromDB($items_id);
               echo $item->getTypeName()." > ";
               echo $item->getLink();
               echo "</li>";
            }
         }
         echo "</ul>";


         echo "</td>";
         echo "</td>";
         echo "<td class='rowhandler control' title='".__('drag', 'fusioninventory').
            "'><div class='drag row'></div></td>";
         echo "</tr>";
         $i++;
      }
      echo "<tr><th>";
      Html::checkAllAsCheckbox("taskjobsList$rand", mt_rand());
      echo "</th><th colspan='3' class='mark'></th></tr>";
      echo "</table>";
      echo "</div>";
      echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt=''>";
      echo "<input type='submit' name='delete' value=\"".
         __('Delete', 'fusioninventory')."\" class='submit'>";


      /**
       * Initialize drag and drop on subtype lists
       **/
      echo "<script type=\"text/javascript\">
      redipsInit('taskjob', 'taskjob', $tasks_id);
</script>";

      echo "</table>";
   }

}

?>
