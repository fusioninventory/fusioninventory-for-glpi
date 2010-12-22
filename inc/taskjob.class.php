<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusioninventoryTaskjob extends CommonDBTM {

   function __construct() {
      $this->table = "glpi_plugin_fusioninventory_taskjobs";
      $this->type = 'PluginFusioninventoryTaskjob';
   }


   static function getTypeName() {
      global $LANG;
      
      return $LANG['plugin_fusioninventory']["task"][2];
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusioninventory']["task"][0];

      $tab[1]['table']          = $this->getTable();
      $tab[1]['field']          = 'name';
      $tab[1]['linkfield']      = '';
      $tab[1]['name']           = $LANG["common"][16];
      $tab[1]['datatype']       = 'itemlink';

      $tab[2]['table']           = 'glpi_entities';
      $tab[2]['field']           = 'completename';
      $tab[2]['linkfield']       = 'entities_id';
      $tab[2]['name']            = $LANG['entity'][0];

      $tab[3]['table']          = $this->getTable();
      $tab[3]['field']          = 'date_scheduled';
      $tab[3]['linkfield']      = '';
      $tab[3]['name']           = $LANG["common"][27];
      $tab[3]['datatype']       = 'datetime';

      $tab[4]['table']          = 'glpi_plugin_fusioninventory_tasks';
      $tab[4]['field']          = 'name';
      $tab[4]['linkfield']      = 'plugin_fusioninventory_tasks_id';
      $tab[4]['name']           = $LANG['plugin_fusioninventory']["task"][0];
      $tab[4]['datatype']       = 'itemlink';
      $tab[4]['itemlink_type']  = 'PluginFusioninventoryTask';
      
      $tab[5]['table']          = $this->getTable();
      $tab[5]['field']          = 'status';
      $tab[5]['linkfield']      = '';
      $tab[5]['name']           = 'status';

      return $tab;
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



   function  showForm($id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog;

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

$this->cronTaskscheduler();

      $this->showFormHeader($options);
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";
      echo "<td rowspan='4'>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center' rowspan='4'>";
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
      echo "<td>".$LANG['plugin_fusioninventory']["task"][14]."&nbsp;:</td>";
      echo "<td align='center'>";
      if ($id) {
         showDateTimeFormItem("date_scheduled",$this->fields["date_scheduled"],1,false);
      } else {
         showDateTimeFormItem("date_scheduled",date("Y-m-d H:i:s"),1);
      }
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

      // Actions
      echo "<tr>";
      echo "<th colspan='4'>".$LANG['rulesengine'][7]."&nbsp;:</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['rulesengine'][30]."&nbsp;:</td>";
      echo "<td align='center'>";
      $this->dropdownMethod("method_id", $this->fields['method'], $this->fields['selection_type']);
      echo "</td>";
      echo "<td>";
      echo "<span id='show_arguments_title_id'>";
      echo "</span>";
      echo "</td>";
      echo "<td>";
      echo "<span id='show_arguments_id'>";
      echo "</span>";
      echo "</td>";
      echo "</tr>";

      // Run on
      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_fusioninventory']['task'][23]."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>selection_type&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<span id='show_SelectionType_id'>";
      //$this->dropdownSelectionType("selection_type");
      echo "</span>";
      echo "</td>";
      echo "<td colspan='2'>";
      echo "<span id='show_Selection_id'>";
      echo "</span>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>Selection&nbsp;:</td>";
      echo "<td align='center' colspan='3'>";
      echo "<span id='show_selectionList'>";
      echo "</span>";
      $a_deviceList = importArrayFromDB($this->fields['selection']);
      $selection = '';
      $selectionDisplayOptions = '';
      for ($i=0; $i < count($a_deviceList) ; $i++) {
         foreach ($a_deviceList[$i] as $itemtype=>$items_id) {
            $selection .= ','.$itemtype.'-'.$items_id;
            if (!empty($itemtype)) {
               $class = new $itemtype();
               $class->getFromDB($items_id);
               $selectionDisplayOptions .= "<option value='".$itemtype.'-'.$items_id."'>".$class->fields['name']."</option>";
            }
         }
      }
      echo "<br/><select name='selectionDisplay' id='selectionDisplay' size='10' multiple='multiple'>".$selectionDisplayOptions."</select>";
      echo "<div style='visibility:hidden'>";
      echo "<textarea name='selection' id='selection'>".$selection."</textarea>";
      echo "</div>";
      echo "</td>";
      echo "</tr>";
      



      if ($id) {
         if (count($PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$id."'")) > 0) {
            echo "</table><br/>";
            if ($id) {
               $PluginFusioninventoryTaskjobstatus->stateTaskjob($id);

               // Display graph finish
               $PluginFusioninventoryTaskjoblog->graphFinish($id);
               echo "<br/>";
            }
         } else {
            $this->showFormButtons($options);
         }
      } else  {
         $this->showFormButtons($options);
      }

      return true;
   }



   function dropdownMethod($myname,$value=0,$valueType=0,$entity_restrict='') {
      global $DB,$CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();

      $a_methods2 = array();
      $a_methods2[''] = "------";
      foreach ($a_methods as $datas) {
         $a_methods2[$datas['method']] = $datas['method'];
      }
      $rand = Dropdown::showFromArray($myname, $a_methods2);

      $params=array('method_id'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname
                     );
      ajaxUpdateItemOnSelectEvent("dropdown_method_id".$rand,"show_SelectionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownMethod.php",$params);

      $params=array('method_id'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname,
                     'title'=>'1'
                     );
      ajaxUpdateItemOnSelectEvent("dropdown_method_id".$rand,"show_arguments_title_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownArgument.php",$params);

      $params=array('method_id'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname,
                     );
      ajaxUpdateItemOnSelectEvent("dropdown_method_id".$rand,"show_arguments_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownArgument.php",$params);



      if ($value != "0") {
         $i = -1;
         $numSelected = 0;
         foreach ($a_methods2 as $method) {
            $i++;
            if ($method == $value) {
               $numSelected = $i;
            } 
         }
         $params['value'] = $valueType;
         if (isset($numSelected)) {
            echo "<script type='text/javascript'>
            document.getElementById('dropdown_".$myname.$rand."').selectedIndex = ".$numSelected.";
";
            ajaxUpdateItemJsCode("show_SelectionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownMethod.php",$params,true,"dropdown_method_id".$rand);

            echo "</script>";
         }
      }

      return $rand;
   }



   function dropdownSelectionType($myname,$method,$value=0,$entity_restrict='') {
      global $DB,$CFG_GLPI;

      $a_methods = array();
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_selectiontype = array();
      $a_selectiontype[''] = "------";
      foreach ($a_methods as $datas) {
         if ($datas['method'] == $method) {
            if (isset($datas['selection_type_name'])) {
               $a_selectiontype[$datas['selection_type']] = $datas['selection_type_name'];
            } else {
               $a_selectiontype[$datas['selection_type']] = $datas['selection_type'];
            }
         }
      }

      $rand = Dropdown::showFromArray($myname, $a_selectiontype);

      $params=array('selection_type'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname,
                     'method'=>$method
                     );

      ajaxUpdateItemOnSelectEvent("dropdown_selection_type".$rand,"show_Selection_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownSelectionType.php",$params);

      if ($value != "0") {
         $i = -1;
         $numSelected = 0;
         foreach ($a_selectiontype as $type) {
            $i++;
            if ($type == $value) {
               $numSelected = $i;
            } 
         }
         if (isset($numSelected)) {
            echo "<script type='text/javascript'>
            document.getElementById('dropdown_".$myname.$rand."').selectedIndex = ".$numSelected.";
               ";
            ajaxUpdateItemJsCode("show_Selection_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownSelectionType.php",$params,true,"dropdown_selection_type".$rand);

            echo "</script>";
         }
      }

   }



   function dropdownSelection($myname,$selectiontype,$method,$value=0,$entity_restrict='') {
      global $DB,$CFG_GLPI;
      
      $types = '';
      $internal = 1;
      if ($selectiontype == "devices") {

      } else if ($selectiontype == "rules") {

      } else if ($selectiontype == "devicegroups") {
         $types = array();
         $types[] = 'Group';
      } else {
         $internal = 0;
      }
      if ($internal == "1") {
         Dropdown::showAllItems($myname, 0, 0, -1, $types);
      } else {
         // <select> personalisé :
         $a_methods = array();
         $a_list = array();
         $module = '';
         $a_methods = PluginFusioninventoryStaticmisc::getmethods();
         foreach ($a_methods as $datas) {
            if ($datas['method'] == $method) {
               $module = $datas['module'];
            }
         }
         if (is_callable(array("Plugin".$module."Staticmisc", 'task_'.$method.'_'.$selectiontype))) {
            $a_list = call_user_func(array("Plugin".$module."Staticmisc", 'task_'.$method.'_'.$selectiontype));
         }
         echo "<div style='visibility:hidden'>";
         echo "<select name='itemtype'><option value='0' selected>0</option></select>";
         echo "</div>";
         Dropdown::showFromArray($myname, $a_list);
      }
      echo "<input type='button' name='addObject' id='addObject' value='Ajouter' class='submit'/>";

      $params=array('selection'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'myname'=>$myname
                     );


      ajaxUpdateItemOnEvent('addObject','show_selectionList',$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownSelection.php",$params,array("click"));
   }



   function dropdownArgument($myname,$method,$value=0,$entity_restrict='', $title = 0) {
      global $DB,$CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $module = '';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
         }
      }

      if (is_callable(array("Plugin".$module."Staticmisc", "task_argument_".$method))) {
         call_user_func(array("Plugin".$module."Staticmisc", "task_argument_".$method), $title);
      }

   }
   
   

   function cronTaskscheduler() {
      global $DB;

      $dateNow = date("Y-m-d H:i:s");

      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;

      $remoteStartAgents = array();

      $query = "SELECT `".$this->table."`.*,`glpi_plugin_fusioninventory_tasks`.`communication`  FROM ".$this->table."
         LEFT JOIN `glpi_plugin_fusioninventory_tasks` ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
         WHERE `is_active`='1'
            AND `status` = '0'
            AND date_scheduled < '".$dateNow."' ";
      if ($result = $DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {

            // Get list of devices listed in this job
            unset($a_deviceList);
            switch ($data['selection_type']) {

               case 'devices':
                  $a_deviceList = importArrayFromDB($data['selection']);
                  break;

               case 'rules':

                  break;

               case 'devicegroups':

                  break;

               case 'fromothertasks':

                  break;

               default:
                  $a_deviceList = importArrayFromDB($data['selection']);
                  break;
            }

            if (isset($a_deviceList)) {

               // Run function of this method for each device
               foreach ($a_deviceList as $devicecomposed_id) {
                  foreach ($devicecomposed_id as $itemtype=>$items_id) {
                     
                  }
                  // Get module name
                  $pluginName = PluginFusioninventoryModule::getModuleName($data['plugins_id']);
                  $className = "Plugin".ucfirst($pluginName).ucfirst($data['method']);
                  $class = new $className;
                  $a_agents = $class->prepareRun($itemtype, $items_id, $data['communication'], $data['id']);
                  if (!$a_agents) {
                     $PluginFusioninventoryTaskjobstatus->changeStatusFinish($data['id'], 
                                                                             $items_id, 
                                                                             $itemtype,
                                                                             1,
                                                                             "Unable to find agent to run this job");
                     $this->getFromDB($data['id']);
                     $this->fields['status'] = 1;
                     $this->update($this->fields);

                  } else {
                     foreach ($a_agents as $agentsdatas) {
                        // Add jobstatus and put status (waiting on server = 0)
                        $a_input = array();
                        $a_input['plugin_fusioninventory_taskjobs_id'] = $data['id'];
                        $a_input['items_id'] = $items_id;
                        $a_input['itemtype'] = $itemtype;
                        $a_input['state'] = 0;
                        $a_input['plugin_fusioninventory_agents_id'] = $agentsdatas['agents_id'];
                        if (isset($agentsdatas['specificity'])) {
                           $a_input['specificity'] = $agentsdatas['specificity'];
                        }
                        $PluginFusioninventoryTaskjobstatus->add($a_input);

                        //Add log of taskjob
                        unset($a_input['plugin_fusioninventory_agents_id']);
                        $a_input['state'] = 1;
                        $a_input['date'] = date("Y-m-d H:i:s");
                        $PluginFusioninventoryTaskjoblog->add($a_input);

                        if ($data['communication'] == 'push') {
                           $this->remoteStartAgent($agentsdatas['ip'], $agentsdatas['token']);
                        }
                        $this->getFromDB($data['id']);
                        $this->fields['status'] = 1;
                        $this->update($this->fields);
                     }
                  }
               }
            }
         }
      }
      // remote start agents
      foreach ($remoteStartAgents as $ip=>$token) {
         $this->RemoteStartAgent($ip, $token);
      }     

   }



   function getStateAgent($ip, $agentid, $type="") {
      global $LANG;

      //PluginFusioninventoryDisplay::disableDebug();
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $state = false;
      $ctx = stream_context_create(array(
          'http' => array(
              'timeout' => 2
              )
          )
      );

      $url = "http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/status";

      $str = @file_get_contents($url, 0, $ctx);
      if (strstr($str, "waiting")) {
         return true;
      }
      return $state;
   }
   


   function RemoteStartAgent($ip, $token) {
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $input = '';
      ini_set('default_socket_timeout', 2);
      $data = file_get_contents("http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/status");
      if (isset($data) && !empty($data)) {
         $handle = fopen("http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/now/".$token, "r");
         $input = 'Agent run Now';
         fclose($fp);
         return true;
      } else {
         $input = 'Agent don\'t respond';
         return false;
      }
   }



   function showRunning() {
      global $DB;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;

      $query = "SELECT * FROM ".$this->table."
         WHERE status='1' ";

      if ($result = $DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            if ($PluginFusioninventoryTaskjobstatus->stateTaskjob($data['id'], '', 'get') < 100) {
               $PluginFusioninventoryTaskjobstatus->stateTaskjob($data['id'], '200');
            }
         }
      }
   }
   
   
   /*
    * Display actions possible in device
    *
    */
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
      echo $LANG['plugin_fusioninventory']["task"][21];
      echo " : </th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusioninventory']["task"][2]."&nbsp;:";
      echo "</td>";

      echo "<td align='center'>";
      $a_methods = array();
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_parseMethods = array();
      $a_parseMethods[''] = "------";
      foreach($a_methods as $num=>$data) {
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
      echo $LANG['plugin_fusioninventory']["task"][14]."&nbsp;:";
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
      foreach ($a_taskjob as $is=>$data) {
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


   function redirectTask($taskjobs_id) {

      $this->getFromDB($taskjobs_id);

      $a_taskjob = $this->find("`plugin_fusioninventory_tasks_id`='".$this->fields['plugin_fusioninventory_tasks_id']."'
            AND `rescheduled_taskjob_id`='0' ", "date_scheduled,id");
      $i = 1;
      foreach($a_taskjob as $id=>$datas) {
         $i++;
         if ($id == $taskjobs_id) {
            $tab = $i;
         }
      }
      glpi_header(GLPI_ROOT."/plugins/fusioninventory/front/task.form.php?"
              ."itemtype=PluginFusioninventoryTask&id=".$this->fields['plugin_fusioninventory_tasks_id']."&glpi_tab=".$tab);

   }


   function manageTasksByObject($itemtype='', $items_id=0) {
      // Create task
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;
      $PluginFusioninventoryTaskjob->showActions($items_id, $itemtype);
      // See task runing
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusioninventoryTaskjobstatus->stateTaskjobItem($items_id, $itemtype, 'running');
      // see tasks finished
      $PluginFusioninventoryTaskjobstatus->stateTaskjobItem($items_id, $itemtype, 'nostarted');
      // see tasks finished
      $PluginFusioninventoryTaskjobstatus->stateTaskjobItem($items_id, $itemtype, 'finished');
   }


}

?>