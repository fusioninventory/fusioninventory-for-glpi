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
 * This file is used to manage the deploy task.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Alexandre Delaunay
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

include_once(PLUGIN_FUSIONINVENTORY_DIR . "/inc/taskjobview.class.php");
include_once(PLUGIN_FUSIONINVENTORY_DIR . "/inc/taskview.class.php");
include_once(PLUGIN_FUSIONINVENTORY_DIR . "/inc/task.class.php");

/**
 * Manage the deploy task.
 */
class PluginFusioninventoryDeployTask extends PluginFusioninventoryTask {


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      if ($nb > 1) {
         return PluginFusioninventoryDeployGroup::getTypeName();
      }
      return __('Task', 'fusioninventory');
   }


   /**
    * Is this use can create a deploy task
    *
    * @return boolean
    */
   static function canCreate() {
      return true;
   }


   /**
    * Is this use can view a deploy task
    *
    * @return boolean
    */
   static function canView() {
      return true;
   }


   /**
    * Define tabs to display on form page
    *
    * @param array $options
    * @return array containing the tabs name
    */
   function defineTabs($options = []) {

      $ong = [];

      if ($this->fields['id'] > 0) {
         $this->addStandardTab(__CLASS__, $ong, $options);
      }
      return $ong;
   }


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      switch (get_class($item)) {

         case __CLASS__:
            return __('Order list', 'fusioninventory');

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
      switch (get_class($item)) {

         case __CLASS__:
            $obj = new self;
            $obj->showActions($_POST["id"]);
            return true;

      }
      return false;
   }


   /**
    * Show list of deploy tasks
    */
   function showList() {
      self::title();
      Search::show('PluginFusioninventoryDeployTask');
   }


   /**
    * Display the title of the page
    *
    * @global array $CFG_GLPI
    */
   function title() {
      global  $CFG_GLPI;

      $buttons = [];
      $title = __('Task', 'fusioninventory');

      if ($this->canCreate()) {
         $buttons["task.form.php?new=1"] = __('Add task', 'fusioninventory');
         $title = "";
      }
      Html::displayTitle($CFG_GLPI["root_doc"] . "/plugins/fusinvdeploy/pics/task.png",
                         $title, $title, $buttons);
   }


   /**
    * Show actions of the deploy task
    *
    * @param integer $id
    */
   function showActions($id) {

      //load extjs plugins library
      echo "<script type='text/javascript'>";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/Spinner.js";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/SpinnerField.js";
      echo "</script>";

      $this->getFromDB($id);
      if ($this->getField('is_active') == 1) {
         echo "<div class='box' style='margin-bottom:20px;'>";
         echo "<div class='box-tleft'><div class='box-tright'><div class='box-tcenter'>";
         echo "</div></div></div>";
         echo "<div class='box-mleft'><div class='box-mright'><div class='box-mcenter'>";
         echo __('Edit impossible, this task is active', 'fusioninventory');

         echo "</div></div></div>";
         echo "<div class='box-bleft'><div class='box-bright'><div class='box-bcenter'>";
         echo "</div></div></div>";
         echo "</div>";
      }

       echo "<table class='deploy_extjs'>
         <tbody>
            <tr>
               <td id='TaskJob'>
               </td>
            </tr>
         </tbody>
      </table>";

      // Include JS
      require GLPI_ROOT."/plugins/fusinvdeploy/js/task_job.front.php";
   }


   /**
    * Do this before delete a deploy task
    *
    * @global array $CFG_GLPI
    * @return boolean
    */
   function pre_deleteItem() {
      global $CFG_GLPI;

      //if task active, delete denied
      if ($this->getField('is_active') == 1) {
         Session::addMessageAfterRedirect(
            __('This task is active. delete denied', 'fusioninventory'));

         Html::redirect($CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/front/task.form.php?id=".
            $this->getField('id'));
         return false;
      }

      $task_id = $this->getField('id');

      $job = new PluginFusioninventoryTaskjob();
      $status = new PluginFusioninventoryTaskjobstate();
      $log = new PluginFusioninventoryTaskjoblog();

      // clean all sub-tables
      $a_taskjobs = $job->find(['plugin_fusioninventory_tasks_id' => $task_id]);
      foreach ($a_taskjobs as $a_taskjob) {
         $a_taskjobstatuss = $status->find(['plugin_fusioninventory_taskjobs_id' => $a_taskjob['id']]);
         foreach ($a_taskjobstatuss as $a_taskjobstatus) {
            $a_taskjoblogs = $log->find(['plugin_fusioninventory_taskjobstates_id' => $a_taskjobstatus['id']]);
            foreach ($a_taskjoblogs as $a_taskjoblog) {
               $log->delete($a_taskjoblog, 1);
            }
            $status->delete($a_taskjobstatus, 1);
         }
         $job->delete($a_taskjob, 1);
      }
      return true;
   }


   /**
    * Do this after added an item
    */
   function post_addItem() {
      $options = [
         'id'              => $this->getField('id'),
         'date_creation'   => date("Y-m-d H:i:s")
      ];
      $this->update($options);
   }
}
