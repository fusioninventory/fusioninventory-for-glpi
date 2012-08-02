<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Alexandre Delaunay
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

include_once(GLPI_ROOT . "/plugins/fusioninventory/inc/task.class.php");

class PluginFusinvdeployTask extends PluginFusioninventoryTask {

   static function getTypeName($nb=0) {
      global $LANG;

      if ($nb>1) {
         return $LANG['plugin_fusinvdeploy']['group'][3];
      }
      return $LANG['plugin_fusinvdeploy']['task'][1];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function defineTabs($options=array()) {
      global $LANG;

      $ong = array();

      if ($this->fields['id'] > 0){
         $this->addStandardTab(__CLASS__, $ong, $options);
      }

      return $ong;
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      switch(get_class($item)) {
         case __CLASS__: return $LANG['plugin_fusinvdeploy']['task'][13];
      }
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      switch(get_class($item)) {
         case __CLASS__:
            $obj = new self;
            $obj->showActions($_POST["id"]);
            break;
      }
   }

   function showList() {
      self::title();
      Search::show('PluginFusinvdeployTask');
   }

   function title() {
      global $LANG, $CFG_GLPI;

      $buttons = array();
      $title = $LANG['plugin_fusinvdeploy']['task'][1];

      if ($this->canCreate()) {
         $buttons["task.form.php?new=1"] = $LANG['plugin_fusinvdeploy']['task'][3];
         $title = "";
      }

      Html::displayTitle($CFG_GLPI["root_doc"] . "/plugins/fusinvdeploy/pics/task.png", $title, $title, $buttons);
   }


   function showActions($id) {
      global $LANG, $CFG_GLPI;

      //load extjs plugins library
      echo "<script type='text/javascript'>";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/Spinner.js";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/SpinnerField.js";
      echo "</script>";

      $this->getFromDB($id);
      $disabled = "false";
      if ($this->getField('is_active') == 1) {
         $disabled = "true";
         echo "<div class='box' style='margin-bottom:20px;'>";
         echo "<div class='box-tleft'><div class='box-tright'><div class='box-tcenter'>";
         echo "</div></div></div>";
         echo "<div class='box-mleft'><div class='box-mright'><div class='box-mcenter'>";
         echo $LANG['plugin_fusinvdeploy']['task'][19];
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

   function pre_deleteItem() {
      global $LANG;

      //if task active, delete denied
      if ($this->getField('is_active') == 1) {
         Session::addMessageAfterRedirect($LANG['plugin_fusinvdeploy']['task'][20]);
         Html::redirect(GLPI_ROOT."/plugins/fusinvdeploy/front/task.form.php?id=".$this->getField('id'));
         return false;
      }

      $task_id = $this->getField('id');

      $job = new PluginFusioninventoryTaskjob();
      $status = new PluginFusioninventoryTaskjobstate();
      $log = new PluginFusioninventoryTaskjoblog();

      // clean all sub-tables
      $a_taskjobs = $job->find("`plugin_fusioninventory_tasks_id`='$task_id'");
      foreach($a_taskjobs as $a_taskjob) {
         $a_taskjobstatuss = $status->find("`plugin_fusioninventory_taskjobs_id`='".$a_taskjob['id']."'");
         foreach($a_taskjobstatuss as $a_taskjobstatus) {
            $a_taskjoblogs = $log->find("`plugin_fusioninventory_taskjobstates_id`='".$a_taskjobstatus['id']."'");
            foreach($a_taskjoblogs as $a_taskjoblog) {
               $log->delete($a_taskjoblog, 1);
            }
            $status->delete($a_taskjobstatus, 1);
         }
         $job->delete($a_taskjob, 1);

      }

      return true;
   }

   function post_addItem() {
      $options = array(
         'id'              => $this->getField('id'),
         'date_creation'   => date("Y-m-d H:i:s")
      );
      $this->update($options);
   }
}

?>