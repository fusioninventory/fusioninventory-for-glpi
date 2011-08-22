<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Alexandre DELAUNAY
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployTask extends CommonDBTM {

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

      if ($this->fields['id'] > 0) {
         $ong[3] = $LANG['plugin_fusinvdeploy']['task'][13];
      } elseif ($this->fields['id'] == -1) {
         $ong[2] = $LANG['plugin_fusinvdeploy']['task'][1];
         $ong['no_all_tab']=true;
      } else { // New item
         $ong[1] = $LANG['plugin_fusinvdeploy']['task'][3];
      }

      return $ong;
   }

   function showMenu($options=array())  {

      $this->displaylist = false;

      $this->fields['id'] = -1;
      $this->showTabs($options);
      $this->addDivForTabs();
   }

   function showList() {
      echo "<table class='tab_cadre_navigation'><tr><td>";

      self::title();
      Search::show('PluginFusinvdeployTask');

      echo "</td></tr></table>";
   }

   function title() {
      global $LANG, $CFG_GLPI;

      $buttons = array();
      $title = $LANG['plugin_fusinvdeploy']['task'][1];

      if ($this->canCreate()) {
         $buttons["task.form.php?new=1"] = $LANG['plugin_fusinvdeploy']['task'][3];
         $title = "";
      }

      displayTitle($CFG_GLPI["root_doc"] . "/plugins/fusinvdeploy/pics/task.png", $title, $title, $buttons);
   }

   function showForm($ID, $options = array()) {
      global $LANG;

      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
      }

      $options['colspan'] = 2;

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
      echo "<td>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['task'][14]."&nbsp;:</td>";
      echo "<td>";
      if ($ID) {
         showDateTimeFormItem("date_scheduled",$this->fields["date_scheduled"],1,false);
      } else {
         showDateTimeFormItem("date_scheduled",date("Y-m-d H:i:s"),1);
      }
      echo "</td>";

      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][60]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showYesNo("is_active",$this->fields["is_active"]);
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['task'][17]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showInteger("periodicity_count", $this->fields['periodicity_count'], 0, 300);
      $a_time = array();
      $a_time[] = "------";
      $a_time['minutes'] = "minutes";
      $a_time['hours'] = "heures";
      $a_time['days'] = "jours";
      $a_time['months'] = "mois";
      Dropdown::showFromArray("periodicity_type", $a_time, array('value'=>$this->fields['periodicity_type']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][33]."&nbsp;:</td>";
      echo "<td>";
      $com = array();
      $com['push'] = $LANG['plugin_fusioninventory']['task'][41];
      $com['pull'] = $LANG['plugin_fusioninventory']['task'][42];
      Dropdown::showFromArray("communication", $com, array('value'=>$this->fields["communication"]));
      echo "</td>";

      echo "<td rowspan='3'>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td rowspan='3'>";
      echo "<textarea cols='45' rows='3' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][34]."&nbsp;:</td>";
      echo "<td align='center'>";
      if ($this->fields['permanent'] != NULL) {
         echo $LANG['choice'][1];
      } else {
         echo $LANG['choice'][0];
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td colspan='2'>";
      if ($this->fields["is_active"]) {
         echo '<input name="forcestart" value="'.$LANG['plugin_fusioninventory']['task'][40].'"
                class="submit" type="submit">';
      }
      echo "&nbsp; </td>";
      echo "</tr>";


      $this->showFormButtons($options);
      $this->addDivForTabs();

      //load extjs plugins library
      echo "<script type='text/javascript'>";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/Spinner.js";
      require_once GLPI_ROOT."/plugins/fusinvdeploy/lib/extjs/SpinnerField.js";
      echo "</script>";

      return true;
   }

   function showActions($id) {
      global $LANG, $CFG_GLPI;

      $this->getFromDB($id);
      $disabled = "false";
      if ($this->getField('execution_id') > 0) {
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
      $task_id = $this->getField('id');

      $job = new PluginFusioninventoryTaskjob();
      $status = new PluginFusioninventoryTaskjobstatus();
      $log = new PluginFusioninventoryTaskjoblog();

      $a_taskjobs = $job->find("`plugin_fusioninventory_tasks_id`='$task_id'");
      foreach($a_taskjobs as $a_taskjob) {
         $a_taskjobstatuss = $status->find("`plugin_fusioninventory_taskjobs_id`='".$a_taskjob['id']."'");
         foreach($a_taskjobstatuss as $a_taskjobstatus) {
            $a_taskjoblogs = $log->find("`plugin_fusioninventory_taskjobstatus_id`='".$a_taskjobstatus['id']."'");
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
