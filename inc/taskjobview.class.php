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
 * This file is used to manage the display of task jobs.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @author    Kevin Roy <kiniou@gmail.com>
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Manage the display of task jobs.
 */
class PluginFusioninventoryTaskjobView extends PluginFusioninventoryCommonView {


   /**
    * __contruct function where initialize base URLs
    */
   function __construct() {
      parent::__construct();
      $this->base_urls = array_merge( $this->base_urls, [
         'fi.job.create' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_form.php",
         'fi.job.edit' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_form.php",
         'fi.job.moduletypes' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_moduletypes.php",
         'fi.job.moduleitems' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_moduleitems.php",
      ]);
   }


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      $tab_names = [];
      if ($item->fields['id'] > 0 and $this->can('task', READ)) {
         return __('Jobs configuration', 'fusioninventory');
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

      $pfTaskJob = new PluginFusioninventoryTaskjob();

      if ($item->fields['id'] > 0) {
         if ($item->getType() == 'PluginFusioninventoryTask') {
            echo "<div id='taskjobs_form'>";
            echo "</div>";
            echo "<div id='taskjobs_list' class='tab_cadre_fixe'>";
            $pfTaskJob->showListForTask($item->fields['id']);
            echo "</div>";
            return true;
         }
      }
      return false;
   }


   /**
    * Ajax load item
    *
    * @param array $options
    * @return integer
    */
   function ajaxLoadItem($options) {
      /*
       * The following has been borrowed from Html::display() and CommonGLPI::showTabsContent().
       *
       * TODO: maybe this can be shared through CommonView. -- Kevin Roy <kiniou@gmail.com>
       */

      if (isset($options['id'])
              and !$this->isNewID($options['id'])) {
         if (!$this->getFromDB($options['id'])) {
            Html::displayNotFoundError();
         }
      }

      // for objects not in table like central
      $ID=0;

      if (isset($this->fields['id'])) {
         $ID = $this->fields['id'];
      } else {
         if (isset($options['id'])) {
            $option_id = $options['id'];
            //Check for correct type of ID received from outside.
            if (is_string($option_id)
                    AND ctype_digit($option_id)) {
               $ID = (int)($options['id']);
            } else if (is_int($option_id)) {
               $ID = $option_id;
            } else {
               trigger_error(
                  "Using default ID($ID) ".
                  "since we can't determine correctly the type of ID ('$option_id')"
               );
            }
         }
      }
      return $ID;
   }


   /**
    * Get form in ajax
    *
    * @param array $options
    */
   function ajaxGetForm($options) {
      $ID = $this->ajaxLoadItem($options);
      $this->showForm($ID, $options);
   }


   /**
    * Display list header
    *
    * @param integer $task_id
    * @param boolean $deletion_enabled as TRUE to create the deletion check boews
    * @param boolean $addition_enabled as TRUE to create a job addition button
    */
   public function showListHeader($task_id, $deletion_enabled, $addition_enabled) {
      echo "<tr>";
      //Show checkbox to select every objects for deletion.
      if ($deletion_enabled) {
         echo "<th>";
         echo Html::getCheckAllAsCheckbox("taskjobs_list", mt_rand());
         echo "</th>";
      }
      if ($addition_enabled) {
         echo "<th colspan='2' class='center'>
               <input type='button'
                      class='submit taskjobs_create'
                      data-ajaxurl='".$this->getBaseUrlFor('fi.job.create')."'
                      data-task_id='$task_id'
                      style='padding:5px;margin:0;right:0'
                      value=' ".__('Add a job', 'fusioninventory')." '/>
            </th>";
      }
      echo "</tr>";
   }


   /**
    * Get items list
    *
    * @param string $module_type
    * @return string
    */
   public function getItemsList($module_type) {
      $items = importArrayFromDB($this->fields[$module_type]);
      $result = [];
      foreach ($items as $item) {
         $itemtype = key($item);
         $itemid = $item[$itemtype];
         $result[] = $this->getItemDisplay($module_type, $itemtype, $itemid);
      }
      return implode("\n", $result);
   }


   /**
    * Get the html code for item to display
    *
    * @param string $module_type
    * @param string $itemtype
    * @param integer $items_id
    * @return string
    */
   public function getItemDisplay($module_type, $itemtype, $items_id) {
      $item = getItemForItemtype($itemtype);
      $item->getFromDB($items_id);
      $itemtype_name = $item->getTypeName();

      $item_fullid = $itemtype . '-' . $items_id;
      return "<div class='taskjob_item' id='$item_fullid'>
               ".Html::getCheckbox([])."
               <span class='" . $itemtype ."'></span>
               <label>
                  <span style='font-style:oblique'>" . $itemtype_name ."</span>
                  ". $item->getLink(['linkoption' => 'target="_blank"'])."
               </label>
               <input type='hidden' name='" . $module_type ."[]' value='". $item_fullid ."'>
               </input>
             </div>";
   }


   /**
    * Show jobs list for task
    *
    * @global array $CFG_GLPI
    * @param integer $task_id
    */
   public function showListForTask($task_id) {
      global $CFG_GLPI;

      $taskjobs = $this->getTaskjobs($task_id);

      // Check if cron GLPI running
      if (count($taskjobs) > 1) {
         $message = __('Several jobs in the same task is not anymore supported because of unexpected side-effects.
         Please consider modifying this task to avoid unexpected results.', 'fusioninventory');
         Html::displayTitle($CFG_GLPI['root_doc']."/pics/warning.png", $message, $message);
      }

      //Activate massive deletion if there are some.
      $deletion_enabled = (count($taskjobs)>0);
      $addition_enabled = (count($taskjobs)==0);

      echo "<form id='taskjobs_form' method='post' action='".$this->getFormURL()."'>";
      echo "<table class='tab_cadrehov package_item_list' id='taskjobs_list'>\n";
      foreach ($taskjobs as $taskjob_data) {
         echo "<tr class='tab_bg_2'>\n";
         $this->showTaskjobSummary($taskjob_data);
         echo "</tr>\n";
      }

      $this->showListHeader($task_id, $deletion_enabled, $addition_enabled);
      echo "</table>\n";

      //Show the delete button for selected object
      if ($deletion_enabled) {
         echo "<div class='left'>";
         echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt=''>";
         echo "<input type='submit' name='delete_taskjobs' value=\"".
            __('Delete', 'fusioninventory')."\" class='submit'>";
         echo "</div>";
      }
      Html::closeForm();
   }


   /**
    * Get task jobs
    *
    * @param integer $task_id
    * @return array
    */
   public function getTaskjobs($task_id) {
      // Find taskjobs tied to the selected task
      $taskjobs = $this->find(
            ['plugin_fusioninventory_tasks_id' => $task_id,
             'rescheduled_taskjob_id'          => 0],
            ['id']);
      return $taskjobs;
   }


   /**
    * Show task job summary
    *
    * @param array $taskjob_data
    */
   public function showTaskjobSummary($taskjob_data) {
      $id = $taskjob_data['id'];
      $name = $taskjob_data['name'];
      if ($name == '') {
         $name = "($id)";
      }
      echo "<td class='control'>".
               Html::getCheckbox(['name' => 'taskjobs[]', 'value' => $id])."
            </td>
            <td id='taskjob_${id}' class='taskjob_block'>
               <a href='#taskjobs_form'
                  class='taskjobs_edit'
                  data-ajaxurl='".$this->getBaseUrlFor('fi.job.edit')."'
                  data-taskjob_id='$id'>
                  $name
               </a>
            </td>
            <td class='rowhandler control'><div class='drag'/></td>";

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-tasks", "Task job edit : " . $this->getBaseUrlFor('fi.job.edit')
      );
      if (isset($_REQUEST['edit_job'])) {
         echo Html::scriptBlock("$(document).ready(function() {
            taskjobs.edit(
              '".$this->getBaseUrlFor('fi.job.edit')."',
              ".$_REQUEST['edit_job']."
            );
         });");
      }
   }


   /**
    * Display dropdown module types called in ajax
    *
    * @param array $options
    */
   public function ajaxModuleTypesDropdown($options) {

      switch ($options['moduletype']) {

         case 'actors':
            $title = __('Actor Type', 'fusioninventory');
            break;

         case 'targets':
            $title = __('Target Type', 'fusioninventory');
            break;

      }
      /**
       * get Itemtype choices dropdown
       */
      $module_types = array_merge(
         ['' => Dropdown::EMPTY_VALUE],
         $this->getTypesForModule($options['method'], $options['moduletype'])
      );
      $module_types_dropdown = $this->showDropdownFromArray(
         $title, null, $module_types
      );
      echo Html::scriptBlock("$(document).ready(function() {
         taskjobs.register_update_items(
            'dropdown_$module_types_dropdown',
            '".$options['moduletype']."',
            '".$this->getBaseUrlFor('fi.job.moduleitems')."'
         );
      });");
   }


   /**
    * Display dropdown module items called in ajax
    *
    * @param array $options
    */
   public function ajaxModuleItemsDropdown($options) {
      global $DB;

      $moduletype = $options['moduletype'];
      $itemtype   = $options['itemtype'];
      $method     = $options['method'];
      if ($itemtype === "") {
         return;
      }
      switch ($moduletype) {

         case 'actors':
            $title = __('Actor Item', 'fusioninventory');
            break;

         case 'targets':
            $title = __('Target Item', 'fusioninventory');
            break;

      }

      if (!preg_match("/^[a-zA-Z]+$/", $method)) {
         $method = '';
      }

      // filter actor list with active agent and with current module active
      $condition = [];
      if ($moduletype == "actors"
          && in_array($itemtype, ["Computer", "PluginFusioninventoryAgent"])) {
         // remove install suffix from deploy
         $modulename = str_replace('DEPLOYINSTALL', 'DEPLOY', strtoupper($method));

         // prepare a query to retrive agent's & computer's id
         $query_filter = "SELECT agents.`id` as agents_id,
                                 agents.`computers_id`
                          FROM `glpi_plugin_fusioninventory_agents` as agents
                          LEFT JOIN `glpi_computers` as computers
                             ON computers.id = agents.computers_id
                          LEFT JOIN `glpi_plugin_fusioninventory_agentmodules` as modules
                             ON modules.`exceptions` LIKE CONCAT('%\"', agents.`id`, '\"%')
                             OR modules.`is_active` = 1
                          WHERE UPPER(modules.`modulename`) = '$modulename'
                             AND computers.is_deleted = 0
                             AND computers.is_template = 0
                          GROUP BY agents.`id`, agents.`computers_id`";
         $res_filter = $DB->query($query_filter);
         $filter_id = [];
         while ($data_filter = $DB->fetchAssoc($res_filter)) {
            if ($itemtype == 'Computer') {
               $filter_id[] =  $data_filter['computers_id'];
            } else {
               $filter_id[] =  $data_filter['agents_id'];
            }
         }

         // if we found prepare condition for dropdown
         // else prepare a false condition for dropdown
         if (count($filter_id)) {
            $condition = ['id' => $filter_id];
         } else {
            $condition = ['0'];
         }
      }

      /**
       * get Itemtype choices dropdown
       */
      $dropdown_rand = $this->showDropdownForItemtype(
         $title,
         $itemtype,
         [
            'width'     => "95%",
            'condition' => $condition
         ]
      );
      $item = getItemForItemtype($itemtype);
      $itemtype_name = $item->getTypeName();
      $item_key_id = $item->getForeignKeyField();
      $dropdown_rand_id = "dropdown_".$item_key_id . $dropdown_rand;
      echo "<div class='center'
                 id='add_fusinv_job_item_button'
                 data-moduletype='$moduletype'
                 data-itemtype='$itemtype'
                 data-itemtype_name='$itemtype_name'
                 data-dropdown_rand_id='$dropdown_rand_id'>
               <input type='button' class=submit
                      value='".__('Add')." $title' />
            </div>";
   }


   /**
    * Get html code for itemtype plus button
    *
    * @param string $title
    * @param string $itemtype
    * @param string $method
    * @return string
    */
   public function getAddItemtypeButton($title, $itemtype, $method) {
      return"<a class='addbutton show_moduletypes'
                data-ajaxurl='".$this->getBaseUrlFor('fi.job.moduletypes')."'
                data-itemtype='$itemtype'
                data-method='$method'>
            $title
            <img src='".$this->getBaseUrlFor('glpi.pics')."/add_dropdown.png' />
            </a>";
   }


   /**
    * Display form for taskjob
    *
    * @param integer $id id of the taskjob
    * @param array $options
    * @return true
    */
   function showForm($id, $options = []) {
      global $CFG_GLPI;

      $new_item = false;
      if ($id > 0) {
         if ($this->getFromDB($id)) {
            $this->checkConfiguration($id);
            $this->getFromDB($id);
         } else {
            $id = 0;
            $this->getEmpty();
            $this->fields['plugin_fusioninventory_tasks_id'] = $options['task_id'];
            $new_item = true;
         }
      } else {
         if (!array_key_exists('task_id', $options)) {
            echo $this->getMessage(
               __('A job can not be created outside a task form'),
               self::MSG_ERROR
            );
            return;
         }
         $this->getEmpty();
         $this->fields['plugin_fusioninventory_tasks_id'] = $options['task_id'];
         $new_item = true;
      }
      $pfTask = $this->getTask();

      echo "<form method='post' name='form_taskjob' action='".
            Plugin::getWebDir('fusioninventory')."/front/taskjob.form.php''>";

      if (!$new_item) {
         echo "<input type='hidden' name='id' value='".$id."' />";
      }
      echo
         "<input type='hidden' name='plugin_fusioninventory_tasks_id' ".
         "value='".$pfTask->fields['id']."' />";
      echo "<table class='tab_cadre_fixe'>";

      // Optional line
      $ismultientities = Session::isMultiEntitiesMode();
      echo '<tr>';
      echo '<th colspan="4">';

      if (!$new_item) {
         echo $this->getTypeName()." - ".__('ID')." $id ";
         if ($ismultientities) {
            echo "(".Dropdown::getDropdownName('glpi_entities', $this->fields['entities_id']) . ")";
         }
      } else {
         if ($ismultientities) {
            echo __('New action', 'fusioninventory')."&nbsp;:&nbsp;".
                 Dropdown::getDropdownName("glpi_entities", $this->fields['entities_id']);
         } else {
            echo __('New action', 'fusioninventory');
         }
      }
      echo '</th>';
      echo '</tr>';

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='4'>";
      echo "<div class='fusinv_form'>";
      $this->showTextField( __('Name'), "name");
      $this->showTextArea(__('Comments'), "comment");

      $modules_methods = PluginFusioninventoryStaticmisc::getModulesMethods();
      if (!Session::haveRight('plugin_fusioninventory_networkequipment', READ)
              AND !Session::haveRight('plugin_fusioninventory_printer', READ)) {
         if (isset($modules_methods['networkdiscovery'])) {
            unset($modules_methods['networkdiscovery']);
         }
         if (isset($modules_methods['networkinventory'])) {
            unset($modules_methods['networkinventory']);
         }
      }
      if (!Session::haveRight('plugin_fusioninventory_wol', READ)) {
         if (isset($modules_methods['wakeonlan'])) {
            unset($modules_methods['wakeonlan']);
         }
      }
      $modules_methods_rand = $this->showDropdownFromArray(
         __('Module method', 'fusioninventory'), "method",
         $modules_methods
      );
      if (!$new_item) {
         echo "<script type='text/javascript'>";
         echo "   taskjobs.register_update_method( 'dropdown_method".$modules_methods_rand."');";
         echo "</script>";

         echo "<div style='display:none' id='method_selected'>".$this->fields['method']."</div>";
      }
      echo "</div>"; // end of first inputs column wrapper

      // Display Definition choices
      if (!$new_item) {
         //Start second column of the form
         echo "<div class='fusinv_form'>";

         echo "<div class='input_wrap split_column tab_bg_4'>";
         echo $this->getAddItemtypeButton(
            __('Targets', 'fusioninventory'),
            'targets', $this->fields['method']
         );
         //echo "<br/><span class='description' style='font-size:50%;font-style:italic'>";
         echo "<br/><span class='description'>";
         echo __('The items that should be applied for this job.', 'fusioninventory');
         echo "</span>";
         echo "</div>";

         echo "<div class='input_wrap split_column tab_bg_4'>";
         echo $this->getAddItemtypeButton(
            __('Actors', 'fusioninventory'),
            'actors', $this->fields['method']
         );
         echo "<br/><span class='description'>";
         echo __('The items that should carry out those targets.', 'fusioninventory');
         echo "</span>";
         echo "</div>";

         echo "<div id='taskjob_moduletypes_dropdown'></div>";
         echo "<div id='taskjob_moduleitems_dropdown'></div>";
         echo "</div>";
      }

      if (!$new_item) {
         $targets_display_list = $this->getItemsList('targets');
         // Display targets and actors lists
         echo "<hr/>
               <div>
                  <div class='taskjob_list_header'>
                     <label>".__('Targets', 'fusioninventory')."&nbsp;:</label>
                  </div>
                  <div id='taskjob_targets_list'>
                     $targets_display_list
                  </div>
                  <div>
                     <a class='clear_list button'
                        data-clear-param='targets'>".
                        __('Clear list', 'fusioninventory')."
                     </a>
                      /
                     <a class='delete_items_selected'
                        data-delete-param='targets'>".
                        __('Delete selected items', 'fusioninventory')."
                     </a>
                  </div>
               </div>";

         $actors_display_list = $this->getItemsList('actors');
         echo "<hr/>
               <div>
                  <div class='taskjob_list_header'>
                     <label>".__('Actors', 'fusioninventory')."&nbsp;:</label>
                  </div>
                  <div id='taskjob_actors_list'>
                     $actors_display_list
                  </div>
                  <div>
                     <a class='clear_list'
                        data-clear-param='actors'>".
                        __('Clear list', 'fusioninventory')."
                     </a>
                       /
                     <a class='delete_items_selected'
                        data-delete-param='actors'>".
                        __('Delete selected items', 'fusioninventory')."
                     </a>
                  </div>
               </div>";
      }

      if ($new_item) {
         echo "<tr>";
         echo "<td colspan='4' valign='top' align='center'>";
         echo Html::submit(__('Add'), ['name' => 'add']);
         echo "</td>";
         echo '</tr>';
      } else {
         echo "<tr>";
         echo "<td class='center'>";
         echo Html::submit(__('Update'), ['name' => 'update']);
         echo "</td>";

         echo "<td class='center' colspan='2'>
                  <div id='cancel_job_changes_button' style='display:none'>
                     <input type='button' class='submit'
                            onclick='taskjobs.edit(\"".$this->getBaseUrlFor('fi.job.edit')."\", $id)'
                            value='".__('Cancel modifications', 'fusioninventory')."'/>
                  </div>
               </td>";

         echo "<td class='center'>";
         echo "<input type='submit'
                      name='delete'
                      value=\"".__('Purge', 'fusioninventory')."\"
                      class='submit' ".
                      Html::addConfirmationOnAction(__('Confirm the final deletion ?',
                                                       'fusioninventory')).">";
         echo "</td>";
         echo '</tr>';
      }

      echo "</table>";
      Html::closeForm();

      echo Html::scriptBlock("$(document).ready(function() {
         taskjobs.register_form_changed();
      });");

      echo "<br/>";

      return true;
   }


   /**
    * Manage actions when submit a form (add, update, purge...)
    *
    * @param array $postvars
    */
   public function submitForm($postvars) {
      global $CFG_GLPI;

      $jobs_id = 0;

      $mytaskjob = new PluginFusioninventoryTaskjob();
      if (isset($postvars['definition_add'])) {
         // * Add a definition
         $mytaskjob->getFromDB($postvars['id']);
         $a_listdef = importArrayFromDB($mytaskjob->fields['definition']);
         $add = 1;
         foreach ($a_listdef as $dataDB) {
            if (isset($dataDB[$postvars['DefinitionType']])
               AND $dataDB[$postvars['DefinitionType']] == $postvars['definitionselectiontoadd']) {
                  $add = 0;
                  break;
            }
         }
         if ($add == '1') {
            if (isset($postvars['DefinitionType'])
               AND $postvars['DefinitionType'] != '') {
                  $a_listdef[] = [$postvars['DefinitionType']=>$postvars['definitionselectiontoadd']];
            }
         }
         $input = [];
         $input['id'] = $postvars['id'];
         $input['definition'] = exportArrayToDB($a_listdef);
         $mytaskjob->update($input);
         Html::back();
      } else if (isset($postvars['action_add'])) {
         // * Add an action
         $mytaskjob->getFromDB($postvars['id']);
         $a_listact = importArrayFromDB($mytaskjob->fields['action']);
         $add = 1;
         foreach ($a_listact as $dataDB) {
            if (isset($dataDB[$postvars['ActionType']])
                    AND $dataDB[$postvars['ActionType']] == $postvars['actionselectiontoadd']) {
               $add = 0;
               break;
            }
         }
         if ($add == '1') {
            if (isset($postvars['ActionType'])
                    AND $postvars['ActionType'] != '') {
               $a_listact[] = [$postvars['ActionType']=>$postvars['actionselectiontoadd']];
            }
         }
         $input = [];
         $input['id'] = $postvars['id'];
         $input['action'] = exportArrayToDB($a_listact);
         $mytaskjob->update($input);
         Html::back();
      } else if (isset($postvars['definition_delete'])) {
         // * Delete definition
         $mytaskjob->getFromDB($postvars['id']);
         $a_listdef = importArrayFromDB($mytaskjob->fields['definition']);

         foreach ($postvars['definition_to_delete'] as $itemdelete) {
            $datadel = explode('-', $itemdelete);
            foreach ($a_listdef as $num=>$dataDB) {
               if (isset($dataDB[$datadel[0]]) AND $dataDB[$datadel[0]] == $datadel[1]) {
                  unset($a_listdef[$num]);
               }
            }
         }
         $input = [];
         $input['id'] = $postvars['id'];
         $input['definition'] = exportArrayToDB($a_listdef);
         $mytaskjob->update($input);
         Html::back();
      } else if (isset($postvars['action_delete'])) {
         // * Delete action
         $mytaskjob->getFromDB($postvars['id']);
         $a_listact = importArrayFromDB($mytaskjob->fields['action']);

         foreach ($postvars['action_to_delete'] as $itemdelete) {
            $datadel = explode('-', $itemdelete);
            foreach ($a_listact as $num=>$dataDB) {
               if (isset($dataDB[$datadel[0]]) AND $dataDB[$datadel[0]] == $datadel[1]) {
                  unset($a_listact[$num]);
               }
            }
         }
         $input = [];
         $input['id'] = $postvars['id'];
         $input['action'] = exportArrayToDB($a_listact);
         $mytaskjob->update($input);
         Html::back();
      } else if (isset($postvars['taskjobstoforcerun'])) {
         // * Force running many tasks (wizard)
         Session::checkRight('plugin_fusioninventory_task', UPDATE);
         $pfTask = new PluginFusioninventoryTask();
         $pfTaskjob = new PluginFusioninventoryTaskjob();
         $_SESSION["plugin_fusioninventory_forcerun"] = [];
         foreach ($postvars['taskjobstoforcerun'] as $taskjobs_id) {
            $pfTask->getFromDB($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);
            $pfTask->forceRunning();
         }
      } else if (isset($postvars['add']) || isset($postvars['update'])) {
         // * Add and update taskjob
         Session::checkRight('plugin_fusioninventory_task', CREATE);
         if (isset($postvars['add'])) {
            if (!isset($postvars['entities_id'])) {
               $postvars['entities_id'] = $_SESSION['glpidefault_entity'];
            }
            // Get entity of task
            $pfTask = new PluginFusioninventoryTask();
            $pfTask->getFromDB($postvars['plugin_fusioninventory_tasks_id']);
            $entities_list = getSonsOf('glpi_entities', $pfTask->fields['entities_id']);
            if (!in_array($postvars['entities_id'], $entities_list)) {
               $postvars['entities_id'] = $pfTask->fields['entities_id'];
            }
            $jobs_id = $this->add($postvars);
         } else {
            if (isset($postvars['method_id'])) {
               $postvars['method']  = $postvars['method_id'];
            }

            $targets = [];
            if (array_key_exists('targets', $postvars)
                    and is_array($postvars['targets'])
                    and count($postvars['targets']) > 0) {
               foreach ($postvars['targets'] as $target) {
                  list($itemtype, $itemid) = explode('-', $target);
                  $targets[] = [$itemtype => $itemid];
               }
            }

            $postvars['targets'] = exportArrayToDB($targets);

            $actors = [];
            if (array_key_exists('actors', $postvars)
                    and is_array($postvars['actors'])
                    and count($postvars['actors']) > 0) {
               foreach ($postvars['actors'] as $actor) {
                  list($itemtype, $itemid) = explode('-', $actor);
                  $actors[] = [$itemtype => $itemid];
               }
            }

            $postvars['actors'] = exportArrayToDB($actors);

            //TODO: get rid of plugins_id and just use method
            $this->update($postvars);
         }

         $add_redirect = "";
         if ($jobs_id) {
            $add_redirect = "&edit_job=$jobs_id#taskjobs_form";
         }

         Html::redirect(Plugin::getWebDir('fusioninventory')."/front/task.form.php?id=".
                                 $postvars['plugin_fusioninventory_tasks_id'].$add_redirect);
      } else if (isset($postvars["delete"])) {
         // * delete taskjob
         Session::checkRight('plugin_fusioninventory_task', PURGE);

         $this->delete($postvars);

      } else if (isset($postvars['itemaddaction'])) {
         $array                     = explode("||", $postvars['methodaction']);
         $module                    = $array[0];
         $method                    = $array[1];
         // Add task
         $mytask = new PluginFusioninventoryTask();
         $input                     = [];
         $input['name']             = $method;

         $task_id = $mytask->add($input);

         // Add job with this device
         $input = [];
         $input['plugin_fusioninventory_tasks_id'] = $task_id;
         $input['name']                            = $method;
         $input['datetime_start']                  = $postvars['datetime_start'];

         $input['plugins_id']                      = PluginFusioninventoryModule::getModuleId($module);
         $input['method']                          = $method;
         $a_selectionDB                            = [];
         $a_selectionDB[][$postvars['itemtype']]      = $postvars['items_id'];
         $input['definition']                      = exportArrayToDB($a_selectionDB);

         $taskname = "plugin_".$module."_task_selection_type_".$method;
         if (is_callable($taskname)) {
            $input['selection_type'] = call_user_func($taskname, $postvars['itemtype']);
         }
         $mytaskjob->add($input);
         // Upsate task to activate it
         $mytask->getFromDB($task_id);
         $mytask->fields['is_active'] = "1";
         $mytask->update($mytask->fields);
         // force running this job (?)

      } else if (isset($postvars['forceend'])) {
         $mytaskjobstate = new PluginFusioninventoryTaskjobstate();
         $pfTaskjob = new PluginFusioninventoryTaskjob();
         $mytaskjobstate->getFromDB($postvars['taskjobstates_id']);
         $jobstate = $mytaskjobstate->fields;
         $a_taskjobstates = $mytaskjobstate->find(['uniqid' => $mytaskjobstate->fields['uniqid']]);
         foreach ($a_taskjobstates as $data) {
            if ($data['state'] != PluginFusioninventoryTaskjobstate::FINISHED) {
               $mytaskjobstate->changeStatusFinish($data['id'],
                  0, '', 1, "Action cancelled by user");
            }
         }

         $pfTaskjob->getFromDB($jobstate['plugin_fusioninventory_taskjobs_id']);
         $pfTaskjob->reinitializeTaskjobs($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);

      } else if (isset($postvars['delete_taskjobs'])) {
         foreach ($postvars['taskjobs'] as $taskjob_id) {
            $input = ['id'=>$taskjob_id];
            $this->delete($input, true);
         }
      }
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

}
