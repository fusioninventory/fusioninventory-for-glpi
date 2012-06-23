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

class PluginFusioninventoryTaskjob extends CommonDBTM {

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;
      
      return $LANG['plugin_fusioninventory']['task'][2];
   }



   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "task", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "task", "r");
   }

   
   
   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusioninventory']['task'][0];

      $tab[1]['table']          = $this->getTable();
      $tab[1]['field']          = 'name';
      $tab[1]['linkfield']      = '';
      $tab[1]['name']           = $LANG['common'][16];
      $tab[1]['datatype']       = 'itemlink';

      $tab[2]['table']           = 'glpi_entities';
      $tab[2]['field']           = 'completename';
      $tab[2]['linkfield']       = 'entities_id';
      $tab[2]['name']            = $LANG['entity'][0];

      $tab[4]['table']          = 'glpi_plugin_fusioninventory_tasks';
      $tab[4]['field']          = 'name';
      $tab[4]['linkfield']      = 'plugin_fusioninventory_tasks_id';
      $tab[4]['name']           = $LANG['plugin_fusioninventory']['task'][0];
      $tab[4]['datatype']       = 'itemlink';
      $tab[4]['itemlink_type']  = 'PluginFusioninventoryTask';
      
      $tab[5]['table']          = $this->getTable();
      $tab[5]['field']          = 'status';
      $tab[5]['linkfield']      = '';
      $tab[5]['name']           = $LANG['state'][0];

      $tab[6]['table']          = $this->getTable();
      $tab[6]['field']          = 'id';
      $tab[6]['linkfield']      = '';
      $tab[6]['name']           = 'id';

      return $tab;
   }

   

   /**
   * Display form for taskjob
   *
   * @param $items_id integer id of the taskjob
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   function showForm($id, $options=array()) {
      global $CFG_GLPI,$LANG;

      if ($id != '') {
         $this->verifyDefinitionActions($id);
      }

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader($options);
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";
      echo "<td rowspan='2'>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center' rowspan='2'>";
      echo "<textarea cols='40' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "<input type='hidden' name='plugin_fusioninventory_tasks_id' value='".$_POST['id']."' />";
      $a_methods = array();
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      foreach ($a_methods as $datas) {
         echo "<input type='hidden' name='method-".$datas['method']."' value='".PluginFusioninventoryModule::getModuleId($datas['module'])."' />";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][26]."&nbsp;:</td>";
      echo "<td align='center'>";
      $rand = $this->dropdownMethod("method", $this->fields['method']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='4' align='center'>".$LANG['plugin_fusioninventory']['task'][45];
      echo " <img src='".$CFG_GLPI['root_doc']."/pics/deplier_down.png'
            onclick='document.getElementById(\"advancedoptions1\").style.visibility=\"visible\";
            document.getElementById(\"advancedoptions2\").style.visibility=\"visible\"'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' id='advancedoptions1' style='visibility:collapse'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][24]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("retry_nb", $this->fields["retry_nb"], 0, 30);
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][31]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("periodicity_count", $this->fields['periodicity_count'], 0, 300);
      $a_time = array();
      $a_time[] = "------";
      $a_time['minutes'] = strtolower($LANG['job'][22]);
      $a_time['hours'] = strtolower($LANG['job'][21]);
      $a_time['days'] = $LANG['calendar'][12];
      $a_time['months'] = $LANG['plugin_fusioninventory']['task'][38];
      Dropdown::showFromArray("periodicity_type", $a_time, array('value'=>$this->fields['periodicity_type']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' id='advancedoptions2' style='visibility:collapse'>";
      echo "<td colspan='2'></td>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][25]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("retry_time", $this->fields["retry_time"], 0, 360);
      echo "</td>";
      echo "</tr>";

      if ($id) {
         if (count($PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$id."' AND `state` < 3")) == 0) {
            $this->showFormButtons($options);
         } else {
            $this->showFormButtons(array('candel'=>false,
                                         'canedit'=>false));
         }

         $this->manageDefinitionsActions($id, "definition");
         $this->manageDefinitionsActions($id, "action");
         $params=array('method_id'=>'__VALUE__',
               'entity_restrict'=>'',
               'rand'=>$rand,
               'myname'=>"method"
               );
         echo "<script type='text/javascript'>";
         ajaxUpdateItemJsCode("show_DefinitionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitiontype.php",$params,true,"dropdown_method".$rand);
         echo "</script>";
         echo "<script type='text/javascript'>";
         ajaxUpdateItemJsCode("show_ActionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactiontype.php",$params,true,"dropdown_method".$rand);
         echo "</script>";

         if (count($PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$id."'")) > 0) {

            $PluginFusioninventoryTaskjobstatus->stateTaskjob($id);

            // Display graph finish
            $PluginFusioninventoryTaskjoblog->graphFinish($id);
         }
      } else  {
         $this->showFormButtons($options);
      }

      return true;
   }


   
   /*
    * Manage definitions
    *
    * @param $id integer id of the taskjob
    */
   function manageDefinitionsActions($id, $type) {
      global $LANG;

      $this->getFromDB($id);

      echo "<form name='".$type."s_form' id='".$type."s_form' method='post' action=' ";
      echo getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>";
      $num = 0;
      if ($type == 'definition') {
         $num = 27;
      } else if ($type == 'action') {
         $num = 28;
      }
      echo $LANG['plugin_fusioninventory']['task'][$num];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td width='45%'>".$LANG['plugin_fusioninventory']['task'][29]."&nbsp;:</td>";
      echo "<td align='center' height='10' width='10%'>";
      echo "<span id='show_".ucfirst($type)."Type_id'>";
      echo "</span>";
      echo "</td>";
      echo "<td class='center' rowspan='2' width='45%'>";
      echo "<input type='submit' class='submit' name='".$type."_add' value='".
            $LANG['buttons'][8]." >>'>";
      echo "<br><br>";

      $a_list = importArrayFromDB($this->fields[$type]);
      if ($a_list) {
         echo "<input type='submit' class='submit' name='".$type."_delete' value='<< ".
               $LANG['buttons'][6]."'>";
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
               $name = $LANG['plugin_fusioninventory']['agents'][32];
            } else if ($item_id == '.2') {
               $name = $LANG['plugin_fusioninventory']['agents'][33];
            } else {
               $class->getFromDB($item_id);
               $name = $class->getName();
            }
            echo "<option value='".$item_type.'-'.$item_id."'>[".$class->getTypeName()."] ".$name."</option>";
         }
         echo "</select>";
      } else {
         echo "&nbsp;";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][30]."&nbsp;:</td>";
      echo "<td align='center' height='10'>";
      echo "<span id='show_".ucfirst($type)."List'>";
      echo "</span>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "<input type='hidden' name='id' value='".$id."' />";
      echo "</form>";
   }



   /*
    * Manage actions
    *
    * @param $id integer id of the taskjob
    */
   function manageActions($id) {
      
   }

   
   
   /**
   * Display methods availables
   *
   * @param $myname value name of dropdown
   * @param $value value name of the method (used for edit taskjob)
   * @param $entity_restrict restriction of entity if required
   *
   * @return value rand of the dropdown
   *
   **/
   function dropdownMethod($myname,$value=0,$entity_restrict='') {
      global $CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();

      $a_methods2 = array();
      $a_methods2[''] = "------";
      foreach ($a_methods as $datas) {
         if (!((isset($datas['hidetask']) AND $datas['hidetask'] == '1'))) {
            if (isset($datas['name'])) {
               $a_methods2[$datas['method']] = $datas['name'];
            } else {
               $a_methods2[$datas['method']] = $datas['method'];
            }
         }
      }

      $rand = Dropdown::showFromArray($myname, $a_methods2, array('value'=>$value));

      // ** List methods available
      $params=array('method_id'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname
                     );
      ajaxUpdateItemOnSelectEvent("dropdown_method".$rand,"show_DefinitionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitiontype.php",$params);

      if ($value != "0") {
         echo "<script type='text/javascript'>";
         ajaxUpdateItemJsCode("show_DefinitionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitiontype.php",$params,true,"dropdown_method".$rand);
         echo "</script>";
      }

      $params=array('method_id'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname
                     );
      ajaxUpdateItemOnSelectEvent("dropdown_method".$rand,"show_ActionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactiontype.php",$params);

      if ($value != "0") {
         echo "<script type='text/javascript'>";
         ajaxUpdateItemJsCode("show_ActionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactiontype.php",$params,true,"dropdown_method".$rand);
         echo "</script>";
      }

      return $rand;
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
   function dropdownDefinitionType($myname,$method,$value=0,$entity_restrict='') {
      global $CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_definitiontype = array();
      $a_definitiontype[''] = '------';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
            $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($module);
            if (is_callable(array($class, "task_definitiontype_".$method))) {
               $a_definitiontype = call_user_func(array($class, "task_definitiontype_".$method), $a_definitiontype);
            }

         }
      }
      
      $rand = Dropdown::showFromArray($myname, $a_definitiontype);

      $params=array('DefinitionType'=>'__VALUE__',
            'entity_restrict'=>$entity_restrict,
            'rand'=>$rand,
            'myname'=>$myname,
            'method'=>$method,
            'deftypeid'=>'dropdown_'.$myname.$rand
            );
      ajaxUpdateItemOnSelectEvent('dropdown_DefinitionType'.$rand,"show_DefinitionList",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitionlist.php",$params);

      return $rand;
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
   function dropdownDefinition($myname,$definitiontype,$method,$deftypeid,$value=0,$entity_restrict='', $title = 0) {
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

      if (is_callable(array($class, "task_definitionselection_".$definitiontype."_".$method))) {
         $rand = call_user_func(array($class, 
                                      "task_definitionselection_".$definitiontype."_".$method), 
                                $title);
      }

      $params=array('selection'=>'__VALUE__',
               'entity_restrict'=>$entity_restrict,
               'myname'=>$myname,
               'defselectadd' => 'dropdown_definitionselectiontoadd'.$rand,
               'deftypeid'=>$deftypeid
               );

      ajaxUpdateItemOnEvent('addObject','show_DefinitionListEmpty',$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitionselection.php",$params,array("click"));
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
   function dropdownActionType($myname,$method,$value=0,$entity_restrict='') {
      global $CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_actioninitiontype = array();
      $a_actioninitiontype[''] = '------';
      $a_actioninitiontype['PluginFusioninventoryAgent'] = PluginFusioninventoryAgent::getTypeName();
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
      ajaxUpdateItemOnSelectEvent('dropdown_ActionType'.$rand,"show_ActionList",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactionlist.php",$params);

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
   function dropdownAction($myname,$actiontype,$method,$actiontypeid,$value=0,$entity_restrict='') {
      global $CFG_GLPI;
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $module = '';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
         }
      }

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
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


      ajaxUpdateItemOnEvent('addAObject', 'show_ActionListEmpty',
                            $CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactionselection.php", 
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
      global $LANG;

      $array = array();
      $array[".1"] = " ".$LANG['plugin_fusioninventory']['agents'][32];
      $array[".2"] = " ".$LANG['plugin_fusioninventory']['agents'][33];
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $PluginFusioninventoryAgentmodule->getAgentsCanDo(strtoupper($module));
      foreach ($array1 as $id => $data) {
         $array[$id] = $data['name'];
      }
      asort($array);
      return $array;
   }

   

   /**
   * Start tasks have scheduled date now
   *
   * @return bool cron is ok or not
   *
   **/
   static function cronTaskscheduler() {
      global $DB;

      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTask = new PluginFusioninventoryTask();

      // Detect if running task have a problem
      $PluginFusioninventoryTaskjob->CronCheckRunnningJobs();

      $_SESSION['glpi_plugin_fusioninventory']['agents'] = array();

      // *** Search task ready
      $dateNow = date("U");

      $query = "SELECT `".$PluginFusioninventoryTaskjob->getTable()."`.*,
     `glpi_plugin_fusioninventory_tasks`.`communication`,
     `glpi_plugin_fusioninventory_tasks`.`execution_id`,
      UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp,
      CASE
         WHEN `".$PluginFusioninventoryTaskjob->getTable()."`.`periodicity_type` = 'minutes'
            THEN `".$PluginFusioninventoryTaskjob->getTable()."`.`periodicity_count` *60
         WHEN `".$PluginFusioninventoryTaskjob->getTable()."`.`periodicity_type` = 'hours'
            THEN `".$PluginFusioninventoryTaskjob->getTable()."`.`periodicity_count` *60 *60
         WHEN `".$PluginFusioninventoryTaskjob->getTable()."`.`periodicity_type` = 'days'
            THEN `".$PluginFusioninventoryTaskjob->getTable()."`.`periodicity_count` *60 *60 *24
         WHEN `".$PluginFusioninventoryTaskjob->getTable()."`.`periodicity_type` = 'months'
            THEN `".$PluginFusioninventoryTaskjob->getTable()."`.`periodicity_count` *60 *60 *24 *30
         ELSE 0
      END AS timing,
      CASE
         WHEN `".$PluginFusioninventoryTask->getTable()."`.`periodicity_type` = 'minutes'
            THEN `".$PluginFusioninventoryTask->getTable()."`.`periodicity_count` *60
         WHEN `".$PluginFusioninventoryTask->getTable()."`.`periodicity_type` = 'hours'
            THEN `".$PluginFusioninventoryTask->getTable()."`.`periodicity_count` *60 *60
         WHEN `".$PluginFusioninventoryTask->getTable()."`.`periodicity_type` = 'days'
            THEN `".$PluginFusioninventoryTask->getTable()."`.`periodicity_count` *60 *60 *24
         WHEN `".$PluginFusioninventoryTask->getTable()."`.`periodicity_type` = 'months'
            THEN `".$PluginFusioninventoryTask->getTable()."`.`periodicity_count` *60 *60 *24 *30
         ELSE 0
      END AS timing_task
      FROM ".$PluginFusioninventoryTaskjob->getTable()."
      LEFT JOIN `glpi_plugin_fusioninventory_tasks` ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
      WHERE `is_active`='1'
         AND `status` = '0'
         AND `".$PluginFusioninventoryTaskjob->getTable()."`.`execution_id`=`glpi_plugin_fusioninventory_tasks`.`execution_id`
         AND UNIX_TIMESTAMP(date_scheduled) <= '".$dateNow."' ";
      $result = $DB->query($query);
      $return = 0;
      $a_tasktiming = array();
      while ($data=$DB->fetch_array($result)) {
         // If time execution of task if this time to execute...
         if (($data['date_scheduled_timestamp'] + $data['timing']) <= $dateNow) {
            $pass = 0;
            if ($data['timing_task'] == '0' AND $data['execution_id'] > 0) {
               $pass = 0;
            } else if (!isset($a_tasktiming[$data['plugin_fusioninventory_tasks_id']])) {
               $a_tasktiming[$data['plugin_fusioninventory_tasks_id']] = $data['timing'];
               $pass = 1;
            } else {
               if ($a_tasktiming[$data['plugin_fusioninventory_tasks_id']] == $data['timing']) {
                  $pass = 1;
               }
            }

            if ($pass == '1') {
               $return = $PluginFusioninventoryTaskjob->prepareRunTaskjob($data);
               if ($return > 0) {
                  $return = 1;
               }
            }
         }
      }
      // Get taskjobs in retry mode
      $query = "SELECT `".$PluginFusioninventoryTaskjob->getTable()."`.*,
     `glpi_plugin_fusioninventory_tasks`.`communication`,
     `glpi_plugin_fusioninventory_tasks`.`execution_id`,
     `glpi_plugin_fusioninventory_tasks`.`date_scheduled`
      FROM ".$PluginFusioninventoryTaskjob->getTable()."
      LEFT JOIN `glpi_plugin_fusioninventory_tasks` ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
      WHERE `is_active`='1'
         AND `status` = '0'
         AND `".$PluginFusioninventoryTaskjob->getTable()."`.`execution_id`=`glpi_plugin_fusioninventory_tasks`.`execution_id` + 1
         ";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $query2 = "SELECT * FROM `".getTableForItemType("PluginFusioninventoryTaskjobstatus")."`
            LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs` 
               ON `plugin_fusioninventory_taskjobstatus_id` = `".getTableForItemType("PluginFusioninventoryTaskjobstatus")."`.`id`
            WHERE `plugin_fusioninventory_taskjobs_id`='".$data['id']."'
                  AND `glpi_plugin_fusioninventory_taskjoblogs`.`state`='3'
                  AND `date`>='".$data['date_scheduled']."' 
            ORDER BY `".getTableForItemType("PluginFusioninventoryTaskjobstatus")."`.`uniqid`";
         $result2 = $DB->query($query2);
         $nb_retry = 0;
         $nb_retry = $DB->numrows($result2);
         $date_last = 0;
         while ($data2=$DB->fetch_array($result2)) {
            $date_last = strtotime($data2['date']);         
         }
         
         if ($nb_retry > 0) {
            $period = 0;
            $period = $PluginFusioninventoryTaskjob->periodicityToTimestamp(
                    $data['periodicity_type'], 
                    $data['periodicity_count']);

            if (($date_last + ($data['retry_time'] * 60)) < date('U')) {
               $return = $PluginFusioninventoryTaskjob->prepareRunTaskjob($data);
               if ($return > 0) {
                  $return = 1;
               }
            }
         }
      }
      
      // Start agents must start in push mode
      foreach($_SESSION['glpi_plugin_fusioninventory']['agents'] as $agent_id=>$num) {
         $PluginFusioninventoryTaskjob->startAgentRemotly($agent_id);
      }
      unset($_SESSION['glpi_plugin_fusioninventory']['agents']);

      // Detect if running task have a problem
      $PluginFusioninventoryTaskjob->CronCheckRunnningJobs();

      return $return;
   }



   /**
   * re initialize all taskjob of a taskjob
   *
   * @param $tasks_id integer id of the task
   *
   * @return bool true if all taskjob are ready (so finished from old runnning job)
   *
   **/
   function reinitializeTaskjobs($tasks_id, $disableTimeVerification = 0) {
      global $DB;
      
      $PluginFusioninventoryTask = new PluginFusioninventoryTask();
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $query = "SELECT *, UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp
            FROM `".$PluginFusioninventoryTask->getTable()."`
         WHERE `id`='".$tasks_id."' 
            LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);

      $period = $PluginFusioninventoryTaskjob->periodicityToTimestamp($data['periodicity_type'], $data['periodicity_count']);

      // Calculate next execution from last
      $queryJob = "SELECT * FROM `".$PluginFusioninventoryTaskjob->getTable()."`
         WHERE `plugin_fusioninventory_tasks_id`='".$tasks_id."'
         ORDER BY `id` DESC";
      $resultJob = $DB->query($queryJob);
      $nb_taskjobs = $DB->numrows($resultJob);
      // get only with execution_id (same +1) as task 
      $queryJob = "SELECT * FROM `".$PluginFusioninventoryTaskjob->getTable()."`
         WHERE `plugin_fusioninventory_tasks_id`='".$tasks_id."'
            AND `execution_id`='".($data['execution_id'] + 1)."'
         ORDER BY `id` DESC";
      $finished = 2;
      $resultJob = $DB->query($queryJob);
      $nb_finished = 0;
      while ($dataJob=$DB->fetch_array($resultJob)) {
         $a_taskjobstatusuniqs = $PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$dataJob['id']."'", 'id DESC', 1);
         $a_taskjobstatusuniq = current($a_taskjobstatusuniqs);
         $a_taskjobstatus = $PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$dataJob['id']."'
            AND `uniqid`='".$a_taskjobstatusuniq['uniqid']."'");
         $taskjobstatusfinished = 0;
         
         foreach ($a_taskjobstatus as $statusdata) {
            $a_joblog = $PluginFusioninventoryTaskjoblog->find("`plugin_fusioninventory_taskjobstatus_id`='".$statusdata['id']."'
               AND (`state`='2' OR `state`='4' OR `state`='5')");
            if (count($a_joblog) > 0) {
               $taskjobstatusfinished++;
            }
         }
         if ((count($a_taskjobstatus) == $taskjobstatusfinished)
                 AND (count($a_taskjobstatus) > 0 )) {
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
            $queryJob2 = "SELECT * FROM `".$PluginFusioninventoryTaskjob->getTable()."`
            WHERE `plugin_fusioninventory_tasks_id`='".$tasks_id."'
               AND `execution_id`='".$data['execution_id']."'
            ORDER BY `id` DESC";
            $resultJob2 = $DB->query($queryJob2);
            if ($DB->numrows($resultJob2) == $nb_taskjobs) {
               $finished = 1;
               return true;
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

         $queryUpdate = "UPDATE `".$PluginFusioninventoryTaskjob->getTable()."`
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
               $data['date_scheduled'] = date("Y-m-d H:i:s", $data['date_scheduled_timestamp'] + $periodtotal);
            } else if ($data['date_scheduled_timestamp'] > date('U')) {
               // Don't update date next execution

            } else {
               $data['date_scheduled'] = date("Y-m-d H:i:s", $data['date_scheduled_timestamp'] + $period);
            }
         }
         $data['execution_id'] = $exe + 1;
         $PluginFusioninventoryTask->update($data);
         return true;
      } else {
         return false;
      }
   }



   /**
   * Force running a task
   *
   * @param $tasks_id integer id of the task
   *
   * @return nothing
   *
   **/
   function forceRunningTask($tasks_id) {
      global $LANG,$DB;
     
      $uniqid = '';

      if ($this->reinitializeTaskjobs($tasks_id, 1)) {
         $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
         $_SESSION['glpi_plugin_fusioninventory']['agents'] = array();

         $query = "SELECT `".$PluginFusioninventoryTaskjob->getTable()."`.*,
               `glpi_plugin_fusioninventory_tasks`.`communication`, 
               UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp
            FROM ".$PluginFusioninventoryTaskjob->getTable()."
            LEFT JOIN `glpi_plugin_fusioninventory_tasks` ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
            WHERE `is_active`='1'
               AND `status` = '0' 
               AND `glpi_plugin_fusioninventory_tasks`.`id`='".$tasks_id."'
            ORDER BY `id`";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $plugin = new Plugin();
            $plugin->getFromDB($data['plugins_id']);
            if ($plugin->fields['state'] == Plugin::ACTIVATED) {
               $uniqid = $PluginFusioninventoryTaskjob->prepareRunTaskjob($data);
            }
         }
         
         foreach($_SESSION['glpi_plugin_fusioninventory']['agents'] as $agent_id=>$num) {
            $PluginFusioninventoryTaskjob->startAgentRemotly($agent_id);
         }
         unset($_SESSION['glpi_plugin_fusioninventory']['agents']);         
      } else {
         $_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusioninventory']['task'][39];
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
   * Get state of agent
   *
   * @param $ip value IP address of the computer where agent is installed
   * @param $agentid integer id of the agent
   *
   * @return bool true if agent is ready
   *
   **/
   function getStateAgent($ip, $agentid) {

      $this->disableDebug();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      if (empty($ip)) {
         return false;
      }

      $ctx = stream_context_create(array(
          'http' => array(
              'timeout' => 2
              )
          )
      );

      $ret = false;
      foreach(PluginFusioninventoryAgent::getAgentStatusURLs($plugins_id, $agentid) as $url) {
         $str = @file_get_contents($url, 0, $ctx);
         if ($str !== false && strstr($str, "waiting")) {
            $ret = true;
            break;
         }
      }
      $this->reenableusemode();
      return $ret;
   }


   
   // $items_id = agent id
   function getRealStateAgent($items_id) {
      
      ini_set('display_errors','On');
      error_reporting(E_ALL | E_STRICT);
      set_error_handler('userErrorHandlerDebug');
      
      ob_start();
      ini_set("allow_url_fopen", "1");

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $ctx = stream_context_create(array(
         'http' => array(
            'timeout' => 2
            )
         )
      );

      $str="noanswer";
      foreach(PluginFusioninventoryAgent::getAgentStatusURLs($plugins_id, $items_id) as $url) {
         $str = @file_get_contents($url, false, $ctx);
         if ($str !== false) {
            break;
         }
      }
      $error = ob_get_contents();
      ob_end_clean();
      $this->reenableusemode();

      $ret = '';
      if (strstr($str, "waiting")) {
         $ret="waiting";
      } else if (strstr($str, "running")) {
         $ret="running";
      }

      if ($str == '' AND !strstr($error, "failed to open stream: Permission denied")) {
         $ret = "noanswer";
      }
      
      return $ret;
   }
   


   /**
   * Start agent remotly from server
   *
   * @param $ip value IP address of the computer where agent is installed
   * @param $token value token required to wake agent remotly
   *
   * @return bool true if agent wake up
   *
   **/
   function startAgentRemotly($agent_id) {

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $ret = false;

      $this->disableDebug();

      $ctx = stream_context_create(array('http' => array('timeout' => 2)));
      foreach (PluginFusioninventoryAgent::getAgentRunURLs($plugins_id, $agent_id) as $runURL) {
         if (@file_get_contents($runURL, 0, $ctx) !== false) {
            $ret = true;
            break;
         }
      }
      $this->reenableusemode();

      return $ret;
   }



   /**
   * Disable debug mode because we don't want the errors
   *
   **/
   function disableDebug() {
      error_reporting(0);
      set_error_handler(array(new PluginFusioninventoryTaskjob(), 'errorempty'));
   }



   /**
   * Reenable debug mode if user must have it defined in settings
   *
   **/
   function reenableusemode() {
      if ($_SESSION['glpi_use_mode'] == DEBUG_MODE){
         ini_set('display_errors','On');
         // Recommended development settings
         error_reporting(E_ALL | E_STRICT);
         set_error_handler('userErrorHandlerDebug');
      } else {
         ini_set('display_errors','Off');
         error_reporting(E_ALL);
         set_error_handler('userErrorHandlerNormal');
      }

   }



   /**
   * When disable debug, we transfer all errors in this emtpy function
   *
   **/
   function errorempty() {
      
   }



   /**
   * Display actions possible in device
   *
   * @return nothing
   *
   **/
   function showActions($items_id, $itemtype) {
      global $LANG,$CFG_GLPI;

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
      echo "<form method='post' name='' id=''  action=\"".$CFG_GLPI['root_doc'] . "/plugins/fusioninventory/front/taskjob.form.php\">";

      echo "<table  class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='4'>";
      echo $LANG['plugin_fusioninventory']['task'][21];
      echo " : </th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusioninventory']['task'][2]."&nbsp;:";
      echo "</td>";

      echo "<td align='center'>";
      $a_methods = array();
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_parseMethods = array();
      $a_parseMethods[''] = "------";
      foreach($a_methods as $data) {
         $class = $class= PluginFusioninventoryStaticmisc::getStaticmiscClass($data['directory']);
         
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
      echo $LANG['plugin_fusioninventory']['task'][14]."&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      showDateTimeFormItem("date_scheduled",date("Y-m-d H:i:s"),1);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='4'>";
      echo "<input type='hidden' name='items_id' value='".$items_id."'/>";
      echo "<input type='hidden' name='itemtype' value='".$itemtype."'/>";
      echo "<input type='submit' name='itemaddaction' value=\"".$LANG['buttons'][8]."\" class='submit' >";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "</form>";
      echo "</div>";
      
   }



   /**
   * Display task jobs 
   *
   * @param $items_id integer id of the taskjob
   * @param $width integer how large in pixel display array
   *
   * @return nothing
   *
   **/
   function showMiniAction($items_id, $width="950") {
      
      echo "<center><table class='tab_cadrehov' style='width: ".$width."px'>";

      echo "<tr>";
      echo "<th>";
      echo "Date";
      echo "</th>";
      echo "<th>";
      echo "Comment";
      echo "</th>";
      echo "</tr>";

      $a_taskjob = $this->find('`id`="'.$items_id.'" ', 'date_scheduled DESC');
      foreach ($a_taskjob as $data) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo convDateTime($data['date_scheduled']);
         echo "</td>";
         echo "<td align='center'>";
         echo $data['comment'];
         echo "</td>";
         echo "</tr>";
      }

      echo "</table></center>";
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

      $a_taskjob = $this->find("`plugin_fusioninventory_tasks_id`='".$this->fields['plugin_fusioninventory_tasks_id']."'
            AND `rescheduled_taskjob_id`='0' ", "id");
      $i = 1;
      $tab = 0;
      foreach($a_taskjob as $id=>$datas) {
         $i++;
         if ($id == $taskjobs_id) {
            $tab = $i;
         }
      }
      glpi_header(getItemTypeFormURL('PluginFusioninventoryTask')
                                     ."?itemtype=PluginFusioninventoryTask&id=".
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
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjobstatus->stateTaskjobItem($items_id, $itemtype, 'running');
      // see tasks finished
      $PluginFusioninventoryTaskjobstatus->stateTaskjobItem($items_id, $itemtype, 'nostarted');
      // see tasks finished
      $PluginFusioninventoryTaskjobstatus->stateTaskjobItem($items_id, $itemtype, 'finished');
   }



   function CronCheckRunnningJobs() {
      global $DB;

      // Get all taskjobstatus running
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();

      $a_taskjobstatus = $PluginFusioninventoryTaskjobstatus->find("`state`='0'
                                                      OR `state`='1'
                                                      OR `state`='2'
                                                      GROUP BY uniqid, plugin_fusioninventory_agents_id");
      foreach($a_taskjobstatus as $data) {
         $sql = "SELECT * FROM `glpi_plugin_fusioninventory_tasks`
            LEFT JOIN `glpi_plugin_fusioninventory_taskjobs`
               on `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
            WHERE `glpi_plugin_fusioninventory_taskjobs`.`id`='".$data['plugin_fusioninventory_taskjobs_id']."'
            LIMIT 1 ";
         $result = $DB->query($sql);
         if ($result) {
            if ($DB->numrows($result) != 0) {
               $task = $DB->fetch_assoc($result);
               if ($task['communication'] == 'pull') {
                  $has_recent_log_entries = $PluginFusioninventoryTaskjoblog->find("`plugin_fusioninventory_taskjobstatus_id`='".$data['id']."'
                           AND ADDTIME(`date`, '04:00:00') < NOW()", "id DESC", "1");
                  # No news from the agent since 1 hour. The agent is probably crached. Let's cancel the task
                  if (count($has_recent_log_entries) == 1) {
                        $a_statustmp = $PluginFusioninventoryTaskjobstatus->find("`uniqid`='".$data['uniqid']."'
                                                   AND `plugin_fusioninventory_agents_id`='".$data['plugin_fusioninventory_agents_id']."'
                                                   AND (`state`='2' OR `state`='1') ");
                        foreach($a_statustmp as $datatmp) {
                           $PluginFusioninventoryTaskjobstatus->changeStatusFinish($datatmp['id'],
                                                               0,
                                                               '',
                                                               1,
                                                               "==fusioninventory::2==");
                        }
                  }
               } else if ($task['communication'] == 'push') {

                  $a_valid = $PluginFusioninventoryTaskjoblog->find("`plugin_fusioninventory_taskjobstatus_id`='".$data['id']."'
                           AND ADDTIME(`date`, '00:10:00') < NOW()", "id DESC", "1");

                  if (count($a_valid) == '1') {
                     // Get agent status
                     $agentreturn = $this->getRealStateAgent($data['plugin_fusioninventory_agents_id']);

                     switch ($agentreturn) {

                        case 'waiting':
                           // token is bad and must force cancel task in server
                           $a_statustmp = $PluginFusioninventoryTaskjobstatus->find("`uniqid`='".$data['uniqid']."'
                                                      AND `plugin_fusioninventory_agents_id`='".$data['plugin_fusioninventory_agents_id']."'
                                                      AND (`state`='2' OR `state`='1' OR `state`='0') ");
                           foreach($a_statustmp as $datatmp) {
                              $PluginFusioninventoryTaskjobstatus->changeStatusFinish($datatmp['id'],
                                                                    0,
                                                                    '',
                                                                    1,
                                                                    "==fusioninventory::1==");
                           }
                           break;

                        case 'running':
                            // just wait and do nothing

                           break;

                        case 'noanswer':
                           // agent crash or computer is shutdown and force cancel task in server
                           $a_statustmp = $PluginFusioninventoryTaskjobstatus->find("`uniqid`='".$data['uniqid']."'
                                                      AND `plugin_fusioninventory_agents_id`='".$data['plugin_fusioninventory_agents_id']."'
                                                      AND (`state`='2' OR `state`='1') ");
                           foreach($a_statustmp as $datatmp) {
                              $PluginFusioninventoryTaskjobstatus->changeStatusFinish($datatmp['id'],
                                                                  0,
                                                                  '',
                                                                  1,
                                                                  "==fusioninventory::2==");
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
      }


      // If taskjob.status = 1 and all taskjobstatus are finished, so reinitializeTaskjobs()
      $sql = "SELECT *
      FROM `glpi_plugin_fusioninventory_taskjobs`
      WHERE (
         SELECT count( * )
         FROM glpi_plugin_fusioninventory_taskjobstatus
         WHERE plugin_fusioninventory_taskjobs_id = `glpi_plugin_fusioninventory_taskjobs`.id
         AND glpi_plugin_fusioninventory_taskjobstatus.state <3) = 0
       AND `glpi_plugin_fusioninventory_taskjobs`.`status`=1";
     $result=$DB->query($sql);
     if ($result) {
         while ($data=$DB->fetch_array($result)) {
            $this->reinitializeTaskjobs($data['plugin_fusioninventory_tasks_id'], '1');
         }
     }
   }


   
   /**
    * Verify if definition or action not deleted
    *
    * @param $items_id interge id of taskjobs
    *
    */
   function verifyDefinitionActions($items_id) {
      
      $return = true;
      $this->getFromDB($items_id);
      $input = array();
      $input['id'] = $this->fields['id'];
      $a_definitions = importArrayFromDB($this->fields['definition']);
      foreach ($a_definitions as $num=>$data) {
         $classname = key($data);
         if ($classname == '') {
            unset($a_definitions[$num]);
         } else {
            $Class = new $classname;
            if (!$Class->getFromDB(current($data))) {
               unset($a_definitions[$num]);
            }
         }
      }
      if (count($a_definitions) == '0') {
         $input['definition'] = '';
         $return = false;
      } else {
         $input['definition'] = exportArrayToDB($a_definitions);
      }
      $a_actions = importArrayFromDB($this->fields['action']);
      foreach ($a_actions as $num=>$data) {
         $classname = key($data);
         $Class = new $classname;
         if (!$Class->getFromDB(current($data))
                 AND (current($data) != ".1")
                 AND (current($data) != ".2")) {
            unset($a_actions[$num]);
         }
      }
      if (count($a_actions) == '0') {
         $input['action'] = '';
         $return = false;
      } else {
         $input['action'] = exportArrayToDB($a_actions);
      }
      $this->update($input);
      return $return;
   }



   static function purgeTaskjob($parm) {
      // $parm["id"]
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();

      // all taskjobs
      $a_taskjobstatuss = $PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$parm->fields["id"]."'");
      foreach($a_taskjobstatuss as $a_taskjobstatus) {
         $a_taskjoblogs = $PluginFusioninventoryTaskjoblog->find("`plugin_fusioninventory_taskjobstatus_id`='".$a_taskjobstatus['id']."'");
         foreach($a_taskjoblogs as $a_taskjoblog) {
            $PluginFusioninventoryTaskjoblog->delete($a_taskjoblog, 1);
         }
         $PluginFusioninventoryTaskjobstatus->delete($a_taskjobstatus, 1);
      }
   }

   function forceEnd() {
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();

      $a_taskjobstatuses =
         $PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='". $this->fields["id"]."'");

      //TODO: in order to avoid too many atomic operations on DB, convert the
      //following into a massive prepared operation (ie. ids in one massive action)
      foreach($a_taskjobstatuses as $a_taskjobstatus) {
         $PluginFusioninventoryTaskjobstatus->getFromDB($a_taskjobstatus['id']);
         if ($a_taskjobstatus['state'] != PluginFusioninventoryTaskjobstatus::FINISHED) {
               $PluginFusioninventoryTaskjobstatus->changeStatusFinish(
                     $a_taskjobstatus['id'], 0, '', 1, "Action cancelled by user", 0, 0
               );
         }
      }
      $this->reinitializeTaskjobs($this->fields['plugin_fusioninventory_tasks_id']);
   }
   
   static function getAllowurlfopen($wakecomputer=0) {
      global $LANG;
      
      if (!ini_get('allow_url_fopen')) {
         echo "<center>";
         echo "<table class='tab_cadre' height='30' width='700'>";
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'><strong>";
         if ($wakecomputer == '0') {
            echo $LANG['plugin_fusioninventory']['errors'][2]." !";
         } else {
            echo $LANG['plugin_fusioninventory']['errors'][1]." !";
         }
         echo "</strong></td>";
         echo "</tr>";
         echo "</table>";
         echo "</center>";
         echo "<br/>";
         return false;
      }
      return true;
   }



   /*
    * Display static list of taskjob
    *
    * @param $method value method name of taskjob to display
    * 
    */
   static function quickList($method) {
      global $LANG;

      $pluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $pluginFusioninventoryTask = new PluginFusioninventoryTask();

      $a_list = $pluginFusioninventoryTaskjob->find("`method`='".$method."'");

      echo "<table class='tab_cadrehov' style='width:950px'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "<th>".$LANG['common'][60]."</th>";
      echo "<th>".$LANG['plugin_fusioninventory']['task'][14]."</th>";
      echo "<th>".$LANG['plugin_fusioninventory']['task'][17]."</th>";
      echo "<th>".$LANG['plugin_fusioninventory']['task'][27]."</td>";
      echo "<th>".$LANG['plugin_fusioninventory']['task'][28]."</th>";
      echo "</tr>";

      foreach ($a_list as $data) {
         $pluginFusioninventoryTaskjob->getFromDB($data['id']);
         $pluginFusioninventoryTask->getFromDB($data['plugin_fusioninventory_tasks_id']);
         echo "<tr class='tab_bg_1'>";
         $link_item = $pluginFusioninventoryTaskjob->getFormURL();
         $link  = $link_item;
         $link .= (strpos($link,'?') ? '&amp;':'?').'id=' . $pluginFusioninventoryTaskjob->fields['id'];
         echo "<td><a href='".$link."'>".$pluginFusioninventoryTaskjob->getNameID(1)."</a></td>";
         echo "<td>".Dropdown::getYesNo($pluginFusioninventoryTask->fields['is_active'])."</td>";
         echo "<td>".$pluginFusioninventoryTask->fields['date_scheduled']."</td>";
         $a_time = '';
         switch ($pluginFusioninventoryTask->fields['periodicity_type']) {

            case 'minutes':
               $a_time = $pluginFusioninventoryTask->fields['periodicity_count']." ".
               strtolower($LANG['job'][22]);
               break;

            case 'hours':
               $a_time = $pluginFusioninventoryTask->fields['periodicity_count']." ".
                    strtolower($LANG['job'][21]);
               break;

            case 'days':
               $a_time = $pluginFusioninventoryTask->fields['periodicity_count']." ".
                    $LANG['calendar'][12];
               break;

            case 'months':
               $a_time = $pluginFusioninventoryTask->fields['periodicity_count']." ".
                    $LANG['plugin_fusioninventory']['task'][38];
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
                  $name = $LANG['plugin_fusioninventory']['agents'][32];
               } else if ($items_id == '.2') {
                  $name = $LANG['plugin_fusioninventory']['agents'][33];
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
    * Quick add or update taskjob
    *
    * @param $id integer id of taskjobs
    * @param $method value method name
    *
    */
   function showQuickForm($id, $method) {
      global $LANG, $CFG_GLPI;

      $pluginFusioninventoryTask = new PluginFusioninventoryTask();
      if (($id!='') AND ($id != '0')) {
         $this->getFromDB($id);
         $pluginFusioninventoryTask->getFromDB($this->fields['plugin_fusioninventory_tasks_id']);
      } else {
         $this->getEmpty();
         $pluginFusioninventoryTask->getEmpty();
      }

      if (strstr($_SERVER['PHP_SELF'], 'wizard')) {
         echo "<a href=\"javascript:showHideDiv('tabsbody','tabsbodyimg','".$CFG_GLPI["root_doc"].
                    "/pics/deplier_down.png','".$CFG_GLPI["root_doc"]."/pics/deplier_up.png')\">";
         echo "<img alt='' name='tabsbodyimg' src=\"".$CFG_GLPI["root_doc"]."/pics/deplier_up.png\">";
         echo "</a>&nbsp;&nbsp;";

         echo "<a href=\"".$_SERVER['PHP_SELF']."?wizz=".$_GET['wizz']."&ariane=".$_GET['ariane']."\">";
         echo $LANG['common'][53];
         echo "</a>";

      } else {
         $this->showTabs();
      }
      $this->showFormHeader(array());

      $a_methods = array();
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      foreach ($a_methods as $datas) {
         echo "<input type='hidden' name='method-".$datas['method']."' value='".PluginFusioninventoryModule::getModuleId($datas['module'])."' />";
      }
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td><input type='text' name='name' value='".$this->fields['name']."' /></td>";
      echo "<td>".$LANG['common'][60]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showYesNo("is_active", $pluginFusioninventoryTask->fields['is_active']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' style='display:none'>";
      echo "<td colspan='4'>";
      echo "<input type='hidden' name='quickform' value='1' />";
      $rand = $this->dropdownMethod("method", $method);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][33]."&nbsp;:</td>";
      echo "<td>";
      $com = array();
      $com['push'] = $LANG['plugin_fusioninventory']['task'][41];
      $com['pull'] = $LANG['plugin_fusioninventory']['task'][42];
      Dropdown::showFromArray("communication", $com, array('value'=>$pluginFusioninventoryTask->fields["communication"]));
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][17]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showInteger("periodicity_count", $pluginFusioninventoryTask->fields['periodicity_count'], 0, 300);
      $a_time = array();
      $a_time[] = "------";
      $a_time['minutes'] = $LANG['job'][22];
      $a_time['hours'] = $LANG['job'][21];
      $a_time['days'] = ucfirst($LANG['calendar'][12]);
      $a_time['months'] = ucfirst($LANG['calendar'][14]);
      Dropdown::showFromArray("periodicity_type", $a_time, array('value'=>$pluginFusioninventoryTask->fields['periodicity_type']));
      echo "</td>";
      echo "</tr>";

      if ($id) {
         $this->showFormButtons(array());

         $this->manageDefinitionsActions($id, "definition");
         $this->manageDefinitionsActions($id, "action");

         $params=array('method_id'=>'__VALUE__',
               'entity_restrict'=>'',
               'rand'=>$rand,
               'myname'=>"method"
               );
         echo "<script type='text/javascript'>";
         ajaxUpdateItemJsCode("show_DefinitionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitiontype.php",$params,true,"dropdown_method".$rand);
         echo "</script>";
         echo "<script type='text/javascript'>";
         ajaxUpdateItemJsCode("show_ActionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactiontype.php",$params,true,"dropdown_method".$rand);
         echo "</script>";
      } else  {
         $this->showFormButtons(array());
      }
   }



   /*
    * Liste of taskjob to forcerun
    */
   static function listToForcerun($method) {
      global $LANG;

      $pluginFusioninventoryTaskjob = new self();
      $a_list = $pluginFusioninventoryTaskjob->find("`method`='".$method."'");

      echo "<form name='form_ic' method='post' action='".getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe' style='width:500px'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2' align='center'>".$LANG['plugin_fusioninventory']['task'][40]."</th>";
      echo "</tr>";
      
      foreach ($a_list as $data) {
         $pluginFusioninventoryTaskjob->getFromDB($data['id']);
         echo "<tr class='tab_bg_1'>";
         echo "<td><input type='checkbox' name='taskjobstoforcerun[]' value='".$data['id']."' /></td>";
         $link_item = $pluginFusioninventoryTaskjob->getFormURL();
         $link  = $link_item;
         $link .= (strpos($link,'?') ? '&amp;':'?').'id=' . $pluginFusioninventoryTaskjob->fields['id'];
         echo "<td><a href='".$link."'>".$pluginFusioninventoryTaskjob->getNameID(1)."</a></td>";
         echo "<tr class='tab_bg_1'>";
      }

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2' align='center'>";
      echo '<input name="forcestart" value="'.$LANG['plugin_fusioninventory']['task'][40].'"
          class="submit" type="submit">';
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "</form>";
   }



   /*
    * List of last logs (uniqid) of taskjob
    */
   static function quickListLogs() {

      $pluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();

      if (isset($_SESSION["plugin_fusioninventory_forcerun"])) {
         foreach ($_SESSION["plugin_fusioninventory_forcerun"] as $taskjobs_id=>$uniqid) {
            $pluginFusioninventoryTaskjob->getFromDB($taskjobs_id);

            echo "<table class='tab_cadrehov' style='width:950px'>";
            echo "<tr class='tab_bg_1'>";
            echo "<th>".$pluginFusioninventoryTaskjob->getLink(1)."</th>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>";
            $pluginFusioninventoryTaskjoblog->showHistory($taskjobs_id, 950, array('uniqid'=>$uniqid));
            echo "</td>";

            echo "</table><br/>";
         }
      }
   }
   
   
   
   function prepareRunTaskjob($a_taskjob) {
      $pFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      
      $uniqid = 0;
      if ($pFusioninventoryTaskjob->verifyDefinitionActions($a_taskjob['id'])) {
         // Get module name
         $pluginName = PluginFusioninventoryModule::getModuleName($a_taskjob['plugins_id']);
         if (strstr($pluginName, "fusioninventory")
                 OR strstr($pluginName, "fusinv")) {

            $input = array();
            $input['id'] = $a_taskjob['id'];
            $input['execution_id'] = $a_taskjob['execution_id'] + 1;
            $pFusioninventoryTaskjob->update($input);

            $itemtype = "Plugin".ucfirst($pluginName).ucfirst($a_taskjob['method']);
            $item = new $itemtype;
            $uniqid = $item->prepareRun($a_taskjob['id']);
         }
         return $uniqid;
      }
   }
}

?>
