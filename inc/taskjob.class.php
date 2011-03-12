<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

class PluginFusioninventoryTaskjob extends CommonDBTM {

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;
      
      return $LANG['plugin_fusioninventory']['task'][2];
   }



   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCancel() {
      return true;
   }

   function canUndo() {
      return true;
   }

   function canValidate() {
      return true;
   }

   function canUpdate() {
      return true;
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
      $tab[5]['name']           = 'status';

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
      global $DB,$CFG_GLPI,$LANG;

      $this->verifyDefinitionActions($id);

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
      echo "<td rowspan='5'>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center' rowspan='5'>";
      echo "<textarea cols='40' rows='5' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "<input type='hidden' name='plugin_fusioninventory_tasks_id' value='".$_POST['id']."' />";
      $a_methods = array();
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      foreach ($a_methods as $datas) {
         echo "<input type='hidden' name='method-".$datas['method']."' value='".PluginFusioninventoryModule::getModuleId($datas['module'])."' />";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][31]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("periodicity_count", $this->fields['periodicity_count'], 0, 300);
      $a_time = array();
      $a_time[] = "------";
      $a_time['minutes'] = $LANG['plugin_fusioninventory']['task'][35];
      $a_time['hours'] = $LANG['plugin_fusioninventory']['task'][36];
      $a_time['days'] = $LANG['plugin_fusioninventory']['task'][37];
      $a_time['months'] = $LANG['plugin_fusioninventory']['task'][38];
      Dropdown::showFromArray("periodicity_type", $a_time, array('value'=>$this->fields['periodicity_type']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][24]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("retry_nb", $this->fields["retry_nb"], 0, 30);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][25]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("retry_time", $this->fields["retry_time"], 0, 360);
      echo "</td>";
      echo "</tr>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][26]."&nbsp;:</td>";
      echo "<td align='center'>";
      $this->dropdownMethod("method", $this->fields['method']);
      echo "</td>";
      echo "</tr>";

      // Definition   *   Action
      echo "<tr>";
      echo "<th colspan='2'>".$LANG['plugin_fusioninventory']['task'][27]."&nbsp;:</th>";
      echo "<th colspan='2'>".$LANG['plugin_fusioninventory']['task'][28]."&nbsp;:</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][29]."&nbsp;:</td>";
      echo "<td align='center' height='20'>";
      echo "<span id='show_DefinitionType_id'>";
      echo "</span>";
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][29]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<span id='show_ActionType_id'>";
      echo "</span>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td rowspan='2'>".$LANG['plugin_fusioninventory']['task'][30]."&nbsp;:</td>";
      echo "<td align='center' height='20'>";
      echo "<span id='show_DefinitionList'>";
      echo "</span>";
      echo "</td>";
      echo "<td rowspan='2'>".$LANG['plugin_fusioninventory']['task'][30]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<span id='show_ActionList'>";
      echo "</span>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      $a_list = importArrayFromDB($this->fields['definition']);
      $deflist = "";
      $deflisthidden = "";
      foreach ($a_list as $data) {
         $item_type = key($data);
         $class = new $item_type();
         $itemname = $class->getTypeName();
         $class->getFromDB(current($data));
         $name = $class->fields['name'];
         $deflist .= '<br>'.$itemname.' -> '.$name.' <img src="'.GLPI_ROOT.'/pics/delete2.png" onclick=\'deldef("'.$itemname.'->'.$name.'->'.$class->getType().'->'.$class->fields['id'].'")\'>';
         $deflisthidden .= ','.key($data).'->'.current($data);
      }
      echo "<span id='definitionselection'>";
      echo $deflist;
      echo "</span>";
      echo "<div style='visibility:hidden'>";
      echo "<textarea name='definitionlist' id='definitionlist'>".$deflisthidden."</textarea>";
      echo "<span id='show_DefinitionListEmpty'>";
      echo "</span>";
      echo "</div>";
      echo "</td>";
      echo "<td align='center'>";
      $a_list = importArrayFromDB($this->fields['action']);
      $actionlist = "";
      $actionlisthidden = "";
      foreach ($a_list as $data) {
         $item_type = key($data);
         $class = new $item_type();
         $itemname = $class->getTypeName();
         $class->getFromDB(current($data));
         $name = '';
         $idTmp = 0;
         if (current($data) == '.1') {
            $name = $LANG['plugin_fusioninventory']['agents'][32];
            $idTmp = '.1';
         } else if (current($data) == '.2') {
            $name = $LANG['plugin_fusioninventory']['agents'][33];
            $idTmp = '.2';
         } else {
            $class->getFromDB(current($data));
            $name = $class->fields['name'];
            $idTmp = $class->fields['id'];
         }         
         $actionlist .= '<br>'.$itemname.' -> '.$name.' <img src="'.GLPI_ROOT.'/pics/delete2.png" onclick=\'delaction("'.$itemname.'->'.$name.'->'.$class->getType().'->'.$idTmp.'")\'>';
         $actionlisthidden .= ','.key($data).'->'.current($data);
      }
      echo "<span id='actionselection'>";
      echo $actionlist;
      echo "</span>";
      echo "<div style='visibility:hidden'>";
      echo "<textarea name='actionlist' id='actionlist'>".$actionlisthidden."</textarea>";
      echo "<span id='show_ActionListEmpty'>";
      echo "</span>";
      echo "</div>";
      echo "</td>";
      echo "</tr>";
      
      echo "<script type='text/javascript'>
         function deldef(data) {
            var elem = data.split('->');
            document.getElementById('definitionlist').value = document.getElementById('definitionlist').value.replace(',' + elem[2] + '->' + elem[3], '');
            document.getElementById('definitionselection').innerHTML = document.getElementById('definitionselection').innerHTML.replace('<br>' + elem[0] + ' -&gt; ' + elem[1] +
            ' <img src=\"".GLPI_ROOT."/pics/delete2.png\" onclick=\'deldef(\"' + elem[0] + '->' + elem[1] + '->' + elem[2] + '->' + elem[3] + '\")\'>', '');
         }

         function delaction(data) {
            var elem = data.split('->');
            document.getElementById('actionlist').value = document.getElementById('actionlist').value.replace(',' + elem[2] + '->' + elem[3], '');
            document.getElementById('actionselection').innerHTML = document.getElementById('actionselection').innerHTML.replace('<br>' + elem[0] + ' -&gt; ' + elem[1] +
            ' <img src=\"".GLPI_ROOT."/pics/delete2.png\" onclick=\'delaction(\"' + elem[0] + '->' + elem[1] + '->' + elem[2] + '->' + elem[3] + '\")\'>', '');
         }
      </script>";


      if ($id) {
         if (count($PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$id."' AND `state` < 3")) == 0) {
            $this->showFormButtons($options);
         }
         if (count($PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$id."'")) > 0) {
 
            $PluginFusioninventoryTaskjobstatus->stateTaskjob($id);

            // Display graph finish
            $PluginFusioninventoryTaskjoblog->graphFinish($id);
            echo "<br/>";
         } 
      } else  {
         $this->showFormButtons($options);
      }

      return true;
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
      global $DB,$CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();

      $a_methods2 = array();
      $a_methods2[''] = "------";
      foreach ($a_methods as $datas) {
         $a_methods2[$datas['method']] = $datas['method'];
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
      global $DB,$CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_definitiontype = array();
      $a_definitiontype[''] = '------';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
            if (is_callable(array("Plugin".$module."Staticmisc", "task_definitiontype_".$method))) {
               $a_definitiontype = call_user_func(array("Plugin".$module."Staticmisc", "task_definitiontype_".$method), $a_definitiontype);
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
      global $DB,$CFG_GLPI, $LANG;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $module = '';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
         }
      }

      $rand = '';
      if (is_callable(array("Plugin".$module."Staticmisc", "task_definitionselection_".$definitiontype."_".$method))) {
         $rand = call_user_func(array("Plugin".$module."Staticmisc", "task_definitionselection_".$definitiontype."_".$method), $title);
      }

      echo "&nbsp;<input type='button' name='addObject' id='addObject' value='".$LANG['buttons'][8]."' class='submit'/>";

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
      global $DB,$CFG_GLPI,$LANG;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_actioninitiontype = array();
      $a_actioninitiontype[''] = '------';
      $a_actioninitiontype['PluginFusioninventoryAgent'] = PluginFusioninventoryAgent::getTypeName();
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
            if (is_callable(array("Plugin".$module."Staticmisc", "task_actiontype_".$method))) {
               $a_actioninitiontype = call_user_func(array("Plugin".$module."Staticmisc", "task_actiontype_".$method), $a_actioninitiontype);
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
      global $DB,$CFG_GLPI, $LANG;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $module = '';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
         }
      }

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $rand = '';

      if ($actiontype == "PluginFusioninventoryAgent") {
         if (is_callable(array("Plugin".$module."Staticmisc", "task_actionselection_PluginFusioninventoryAgent_".$method))) {
            $rand = call_user_func(array("Plugin".$module."Staticmisc", "task_actionselection_PluginFusioninventoryAgent_".$method));
         } else {
            $a_data = $this->get_agents($method);

            $rand = Dropdown::showFromArray('actionselectiontoadd', $a_data);
         }
      } else {
         if (is_callable(array("Plugin".$module."Staticmisc", "task_definitionselection_".$actiontype."_".$method))) {
            $rand = call_user_func(array("Plugin".$module."Staticmisc", "task_definitionselection_".$actiontype."_".$method));
         }
      }
      echo "&nbsp;<input type='button' name='addAObject' id='addAObject' value='".$LANG['buttons'][8]."' class='submit'/>";

            $params=array('selection'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'myname'=>$myname,
                     'actionselectadd' => 'dropdown_actionselectiontoadd'.$rand,
                     'actiontypeid'=>$actiontypeid
                     );


      ajaxUpdateItemOnEvent('addAObject','show_ActionListEmpty',$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactionselection.php",$params,array("click"));

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

      $PluginFusioninventoryTask = new PluginFusioninventoryTask();
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();

      $_SESSION['glpi_plugin_fusioninventory']['agents'] = array();

      // Search for task with periodicity and must be ok (so reinit state of job to 0)
      $query = "SELECT *, UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp FROM `".$PluginFusioninventoryTask->getTable()."`
         WHERE `is_active`='1'
            AND `periodicity_count` != '0'";
      
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $PluginFusioninventoryTaskjob->reinitializeTaskjobs($data['id']);
      }

      // *** Search task ready
      $dateNow = date("Y-m-d H:i:s");
      $query = "SELECT `".$PluginFusioninventoryTaskjob->getTable()."`.*,`glpi_plugin_fusioninventory_tasks`.`communication`, UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp
         FROM ".$PluginFusioninventoryTaskjob->getTable()."
         LEFT JOIN `glpi_plugin_fusioninventory_tasks` ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
         WHERE `is_active`='1'
            AND `status` = '0'
            AND `date_scheduled` <= '".$dateNow."' ";
      $result = $DB->query($query);
      $return = 0;
      while ($data=$DB->fetch_array($result)) {
         $PluginFusioninventoryTaskjob->verifyDefinitionActions($data['id']);
         $period = $PluginFusioninventoryTaskjob->periodicityToTimestamp($data['periodicity_type'], $data['periodicity_count']);
         if (($data['date_scheduled_timestamp'] + $period) <= date('U')) {
            // Get module name
            $pluginName = PluginFusioninventoryModule::getModuleName($data['plugins_id']);
            $className = "Plugin".ucfirst($pluginName).ucfirst($data['method']);
            $class = new $className;
            $class->prepareRun($data['id']);
            $return = 1;
         }
      }
      // Start agents must start in push mode
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      foreach($_SESSION['glpi_plugin_fusioninventory']['agents'] as $agents_id=>$num) {
         $a_ips = $PluginFusioninventoryAgent->getIPs($agents_id);
         foreach ($a_ips as $ip) {
            $PluginFusioninventoryAgent->getFromDB($agents_id);
            $PluginFusioninventoryTaskjob->remoteStartAgent($ip, $PluginFusioninventoryAgent->fields['token']);
         }
      }
      unset($_SESSION['glpi_plugin_fusioninventory']['agents']);

      // Detect if running task have a problem
      $PluginFusioninventoryTaskjob->CronCheckRunnningJobs();


      if ($return == '1') {
         return 1;
      }
      return 0;
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

      $query = "SELECT *, UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp FROM `".$PluginFusioninventoryTask->getTable()."`
         WHERE `id`='".$tasks_id."' 
            LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);

      $period = $PluginFusioninventoryTaskjob->periodicityToTimestamp($data['periodicity_type'], $data['periodicity_count']);

      // Calculate next execution from last
      $queryJob = "SELECT * FROM `".$PluginFusioninventoryTaskjob->getTable()."`
         WHERE `plugin_fusioninventory_tasks_id`='".$tasks_id."'
         ORDER BY `id` DESC
         LIMIT 1";

      $finished = 2;
      $resultJob = $DB->query($queryJob);
      while ($dataJob=$DB->fetch_array($resultJob)) {
         $a_taskjobstatus = $PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$dataJob['id']."'", "id DESC", 1);
         $taskjobstatusfinished = 0;
         foreach ($a_taskjobstatus as $statusdata) {
            $a_joblog = $PluginFusioninventoryTaskjoblog->find("`plugin_fusioninventory_taskjobstatus_id`='".$statusdata['id']."'");
            foreach($a_joblog as $joblogdata) {
               switch ($joblogdata['state']) {

                  case '2':
                  case '3':
                  case '4':
                  case '5':
                     // finished
                     $taskjobstatusfinished++;
                     break;

               }
            }
         }

         if ((count($a_taskjobstatus) == $taskjobstatusfinished)
                 AND ($finished != "0")
                 AND (($data['date_scheduled_timestamp'] + $period) < date('U')) ) {

            $finished = 1;
         } else if ((count($a_taskjobstatus) == $taskjobstatusfinished)
                 AND ($finished != "0")
                 AND $disableTimeVerification == "1") {

             $finished = 1;
         } else {
            $finished = 0;
         }
      }
      // if all jobs are finished, we calculate if we reinitialize all jobs
      if ($finished == "1") {
         $data['execution_id']++;
         $queryUpdate = "UPDATE `".$PluginFusioninventoryTaskjob->getTable()."`
            SET `status`='0', `execution_id`='".$data['execution_id']."'
            WHERE `plugin_fusioninventory_tasks_id`='".$data['id']."'";
         $DB->query($queryUpdate);

         if (($data['date_scheduled_timestamp'] + $period) <= date('U')) {
            $data['date_scheduled'] = date("Y-m-d H:i:s", date('U'));
         } else {
            $data['date_scheduled'] = date("Y-m-d H:i:s", $data['date_scheduled_timestamp'] + $period);
         }
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
      
      if ($this->reinitializeTaskjobs($tasks_id, 1)) {
         $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
         $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
         $_SESSION['glpi_plugin_fusioninventory']['agents'] = array();

         $query = "SELECT `".$PluginFusioninventoryTaskjob->getTable()."`.*,`glpi_plugin_fusioninventory_tasks`.`communication`, UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp
            FROM ".$PluginFusioninventoryTaskjob->getTable()."
            LEFT JOIN `glpi_plugin_fusioninventory_tasks` ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
            WHERE `is_active`='1'
               AND `status` = '0' ";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            // Get module name
            $PluginFusioninventoryTaskjob->verifyDefinitionActions($data['id']);
            $pluginName = PluginFusioninventoryModule::getModuleName($data['plugins_id']);
            $className = "Plugin".ucfirst($pluginName).ucfirst($data['method']);
            $class = new $className;
            $class->prepareRun($data['id']);
         }
         foreach($_SESSION['glpi_plugin_fusioninventory']['agents'] as $agents_id=>$num) {
            $a_ips = $PluginFusioninventoryAgent->getIPs($agents_id);
            foreach ($a_ips as $ip) {
               $PluginFusioninventoryAgent->getFromDB($agents_id);
               $PluginFusioninventoryTaskjob->remoteStartAgent($ip, $PluginFusioninventoryAgent->fields['token']);
            }
         }
         unset($_SESSION['glpi_plugin_fusioninventory']['agents']);         
      } else {
         $_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusioninventory']['task'][39];
      }
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
      global $LANG;

      $this->disableDebug();

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

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

      $url = "http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/status";

      $str = @file_get_contents($url, 0, $ctx);
      $this->reenableusemode();
      if (strstr($str, "waiting")) {
         return true;
      }
      return false;
   }


   // $items_id = agent id
   function getRealStateAgent($items_id) {
      global $LANG;

      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $a_ip = $PluginFusioninventoryAgent->getIPs($items_id);
      if (count($a_ip) > 0) {

         $this->disableDebug();

         $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

         $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

         $ctx = stream_context_create(array(
             'http' => array(
                 'timeout' => 2
                 )
             )
         );

         foreach ($a_ip as $ip) {
            $url = "http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/status";

            $str = @file_get_contents($url, 0, $ctx);
            $this->reenableusemode();
            if (strstr($str, "waiting")) {
               return "waiting";
            } else if (strstr($str, "running")) {
               return "running";
            }
         }
         return "noanswer";
      } else {
         return "noip";
      }
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
   function RemoteStartAgent($ip, $token) {

      $this->disableDebug();
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $input = '';
      $ctx = stream_context_create(array(
          'http' => array(
              'timeout' => 2
              )
          )
      );
      $data = @file_get_contents("http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/status", 0, $ctx);
      if (isset($data) && !empty($data)) {
         @file_get_contents("http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/now/".$token, 0, $ctx);
         //Agent run Now
         $this->reenableusemode();
         return true;
      } else {
         //Agent not available
         $this->reenableusemode();
         return false;
      }
   }



   /**
   * Disable debug mode because we don't want the errors
   *
   **/
   function disableDebug() {
      error_reporting(0);
      set_error_handler(array(new PluginFusioninventoryTaskjob(),'errorempty'));
   }



   /**
   * Reenable debug mode if user must have it defined in settings
   *
   **/
   function reenableusemode() {
      if ($_SESSION['glpi_use_mode']==DEBUG_MODE){
         ini_set('display_errors','On');
         error_reporting(E_ALL | E_STRICT);
         set_error_handler("userErrorHandler");
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
      global $LANG;

      // load all plugin and get method possible
      /*
       * Example :
       * * inventory
       * * snmpquery
       * * wakeonlan
       * * deploy => software
       * 
       *
       */

      echo "<div align='center'>";
      echo "<form method='post' name='' id=''  action=\"".GLPI_ROOT . "/plugins/fusioninventory/front/taskjob.form.php\">";

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
         if (is_callable(array('Plugin'.$data['module'].'Staticmisc', 'task_action_'.$data['method']))) {
            $a_itemtype = call_user_func(array('Plugin'.$data['module'].'Staticmisc', 'task_action_'.$data['method']));
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
      global $LANG;
      
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
      glpi_header(GLPI_ROOT."/plugins/fusioninventory/front/task.form.php?"
              ."itemtype=PluginFusioninventoryTask&id=".$this->fields['plugin_fusioninventory_tasks_id']."&glpi_tab=".$tab);

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
      // Create task
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTaskjob->showActions($items_id, $itemtype);
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
         if ($result = $DB->query($sql)) {
            if ($DB->numrows($result) != 0) {
               $task = $DB->fetch_assoc($result);
               if ($task['communication'] == 'push') {
                  $a_valid = $PluginFusioninventoryTaskjoblog->find("`plugin_fusioninventory_taskjobstatus_id`='".$data['id']."'
                           AND (`date`+100) < (NOW() + 0)", "", "1");

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
   }


   /**
    * Verify if definition or action not deleted
    *
    * @param $items_id interge id of taskjobs
    *
    */
   function verifyDefinitionActions($items_id) {
      $this->getFromDB($items_id);
      $a_definitions = importArrayFromDB($this->fields['definition']);
      foreach ($a_definitions as $num=>$data) {
         $classname = key($data);
         $Class = new $classname;
         if (!$Class->getFromDB(current($data))) {
            unset($a_definitions[$num]);
         }
      }
      if (count($a_definitions) == '0') {
         $this->fields['definition'] = '';
      } else {
         $this->fields['definition'] = exportArrayToDB($a_definitions);
      }
      $a_actions = importArrayFromDB($this->fields['action']);
      foreach ($a_actions as $num=>$data) {
         $classname = key($data);
         $Class = new $classname;
         if (!$Class->getFromDB(current($data))) {
            unset($a_actions[$num]);
         }
      }
      if (count($a_actions) == '0') {
         $this->fields['action'] = '';
      } else {
         $this->fields['action'] = exportArrayToDB($a_actions);
      }
      $this->update($this->fields);      
   }

}

?>