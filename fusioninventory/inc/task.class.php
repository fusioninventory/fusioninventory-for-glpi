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
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

class PluginFusioninventoryTask extends CommonDBTM {

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['task'][1];
   }


   
   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "task", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "task", "r");
   }


   
   function getSearchOptions() {
      global $LANG;

      $sopt = array();

      $sopt['common'] = $LANG['plugin_fusioninventory']['task'][0];

      $sopt[1]['table']          = $this->getTable();
      $sopt[1]['field']          = 'name';
      $sopt[1]['linkfield']      = 'name';
      $sopt[1]['name']           = $LANG['common'][16];
      $sopt[1]['datatype']       = 'itemlink';

      $sopt[2]['table']          = $this->getTable();
      $sopt[2]['field']          = 'date_scheduled';
      $sopt[2]['linkfield']      = 'date_scheduled';
      $sopt[2]['name']           = $LANG['plugin_fusioninventory']['task'][14];
      $sopt[2]['datatype']       = 'datetime';

      $sopt[3]['table']          = 'glpi_entities';
      $sopt[3]['field']          = 'completename';
      $sopt[3]['linkfield']      = 'entities_id';
      $sopt[3]['name']           = $LANG['entity'][0];
  
      $sopt[4]['table']          = $this->getTable();
      $sopt[4]['field']          = 'comment';
      $sopt[4]['linkfield']      = 'comment';
      $sopt[4]['name']           = $LANG['common'][25];

      $sopt[5]['table']          = $this->getTable();
      $sopt[5]['field']          = 'is_active';
      $sopt[5]['linkfield']      = 'is_active';
      $sopt[5]['name']           = $LANG['common'][60];
      $sopt[5]['datatype']       = 'bool';

      $sopt[6]['table']          = $this->getTable();
      $sopt[6]['field']          = 'communication';
      $sopt[6]['linkfield']      = '';
      $sopt[6]['name']           = $LANG['plugin_fusioninventory']['task'][33];

      $sopt[30]['table']          = $this->getTable();
      $sopt[30]['field']          = 'id';
      $sopt[30]['linkfield']      = '';
      $sopt[30]['name']           = $LANG['common'][2];

      return $sopt;
   }


   
   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      $ong[1] = $LANG['title'][26];

      if ($this->fields['id'] > 0) {
         $pft = new PluginFusioninventoryTaskjob;
         $a_taskjob = $pft->find("`plugin_fusioninventory_tasks_id`='".$_GET['id']."'
               AND `rescheduled_taskjob_id`='0' ", "id");
         $i = 1;
         foreach($a_taskjob as $datas) {
            $i++;
            $ong[$i] = $LANG['plugin_fusioninventory']['task'][2]." ".($i-1);
         }

         $i++;
         $ong[$i] = $LANG['joblist'][9]." <img src='".$CFG_GLPI['root_doc']."/pics/add_dropdown.png'/>";
      }
      return $ong;
   }



   /**
   * Display form for task configuration
   *
   * @param $items_id integer ID of the task
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   function showForm($id, $options=array()) {
      global $LANG;

      $pFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $pFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      
      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }
      
      $options['colspan'] = 3;
      $this->showTabs($options);
      $this->showFormHeader($options);
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td align='center' colspan='2'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['task'][14]."&nbsp;:</td>";
      echo "<td>";
      if ($id) {
         showDateTimeFormItem("date_scheduled",$this->fields["date_scheduled"],1,false);
      } else {
         showDateTimeFormItem("date_scheduled",date("Y-m-d H:i:s"),1);
      }
      echo "</td>";
      echo "<th rowspan='2'>";
      if ($this->fields["is_active"]) {
         if ($id!='') {
            $forcerundisplay = 1;
            $a_taskjobs = $pFusioninventoryTaskjob->find("`plugin_fusioninventory_tasks_id`='".$id."'");
            foreach ($a_taskjobs as $data) {
               $statejob = $pFusioninventoryTaskjobstatus->stateTaskjob($data['id'], '930', 'value');
               if ($statejob != '') {
                  $forcerundisplay = 0;
               }
            }
            if ($forcerundisplay == '1') {
               echo '<input name="forcestart" value="'.$LANG['plugin_fusioninventory']['task'][40].'"
                      class="submit" type="submit">';
            }
         }
      }
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][60]."&nbsp;:</td>";
      echo "<td align='center' colspan='2'>";
      Dropdown::showYesNo("is_active",$this->fields["is_active"]);
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['task'][17]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showInteger("periodicity_count", $this->fields['periodicity_count'], 0, 300);
      $a_time = array();
      $a_time[] = "------";
      $a_time['minutes'] = $LANG['job'][22];
      $a_time['hours'] = $LANG['job'][21];
      $a_time['days'] = ucfirst($LANG['calendar'][12]);
      $a_time['months'] = ucfirst($LANG['calendar'][14]);
      Dropdown::showFromArray("periodicity_type", $a_time, array('value'=>$this->fields['periodicity_type']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][33]."&nbsp;:</td>";
      echo "<td align='center' colspan='2'>";
      $com = array();
      $com['push'] = $LANG['plugin_fusioninventory']['task'][41];
      $com['pull'] = $LANG['plugin_fusioninventory']['task'][42];
      Dropdown::showFromArray("communication", $com, array('value'=>$this->fields["communication"]));
      echo "</td>";
      
      echo "<td rowspan='2'>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center' rowspan='2'>";
      echo "<textarea cols='45' rows='3' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "<th rowspan='2'>";
      if ($id!='') {
         $reset = 0;
         $a_taskjobs = $pFusioninventoryTaskjob->find("`plugin_fusioninventory_tasks_id`='".$id."'");
         foreach ($a_taskjobs as $data) {
            $statejob = $pFusioninventoryTaskjobstatus->stateTaskjob($data['id'], '930', 'value');
            if ($statejob == '') {
               if ($data['execution_id'] != $this->fields['execution_id']) {
                  $reset = 1;
               }
            }
         }
         if ($reset == '1') {
            echo '<input name="reset" value="'.$LANG['plugin_fusioninventory']['task'][46].'"
                   class="submit" type="submit">';
         }
      }
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "</tr>";
      
      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }



   /**
   * Purge task and taskjob
   *
   * @param $parm object to purge
   *
   * @return nothing
   *
   **/
   static function purgeTask($parm) {
      // $parm["id"]
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();

      // all taskjobs
      $a_taskjobs = $PluginFusioninventoryTaskjob->find("`plugin_fusioninventory_tasks_id`='".$parm->fields["id"]."'");
      foreach($a_taskjobs as $a_taskjob) {
         $PluginFusioninventoryTaskjob->delete($a_taskjob, 1);
      }
   }



   /**
   * Purge task and taskjob related with method 
   *
   * @param $method value name of the method
   *
   * @return nothing
   *
   **/
   static function cleanTasksbyMethod($method) {
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTask = new PluginFusioninventoryTask();

      $a_taskjobs = $PluginFusioninventoryTaskjob->find("`method`='".$method."'");
      $task_id = 0;
      foreach($a_taskjobs as $a_taskjob) {
         $PluginFusioninventoryTaskjob->delete($a_taskjob, 1);
         if (($task_id != $a_taskjob['plugin_fusioninventory_tasks_id'])
            AND ($task_id != '0')) {

            // Search if this task have other taskjobs, if not, we will delete it
            $findtaskjobs = $PluginFusioninventoryTaskjob->find("`plugin_fusioninventory_tasks_id`='".$task_id."'");
            if (count($findtaskjobs) == '0') {
               $PluginFusioninventoryTask->delete(array('id'=>$task_id), 1);
            }
         }
         $task_id = $a_taskjob['plugin_fusioninventory_tasks_id'];         
      }
      if ($task_id != '0') {

         // Search if this task have other taskjobs, if not, we will delete it
         $findtaskjobs = $PluginFusioninventoryTaskjob->find("`plugin_fusioninventory_tasks_id`='".$task_id."'");
         if (count($findtaskjobs) == '0') {
            $PluginFusioninventoryTask->delete(array('id'=>$task_id), 1);
         }
      }
   }
}

?>
