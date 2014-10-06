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
   @co-author Kevin Roy <kiniou@gmail.com>
   @copyright Copyright (c) 2010-2014 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

class PluginFusioninventoryTaskjobView extends PluginFusioninventoryCommonView {

   function __construct() {
      parent::__construct();
      $this->base_urls = array_merge( $this->base_urls, array(
         'fi.job.create' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_form.php",
         'fi.job.edit' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_form.php",
         'fi.job.moduletypes' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_moduletypes.php",
         'fi.job.moduleitems' => $this->getBaseUrlFor('fi.ajax') . "/taskjob_moduleitems.php",
      ));
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $CFG_GLPI;

      $tab_names = array();
      if ( $item->getID() > 0 and $this->can('task', 'r') ) {
         $tab_names[] = __('Jobs configuration', 'fusioninventory');
      }

      //Return tab names if list is not empty
      if (!empty($tab_names)) {
         return $tab_names;
      } else {
         return '';
      }

   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pfTaskJob = new PluginFusioninventoryTaskjob();

      if ($item->getID() > 0) {
         if ($item->getType() == 'PluginFusioninventoryTask') {
            echo "<div id='taskjobs_form'>";
            echo "</div>";
            echo "<div id='taskjobs_list' class='tab_cadre_fixe'>";
            $pfTaskJob->showListForTask($item->getID());
            echo "</div>";

            //Just a sortable test (must me removed after testing)
            //echo file_get_contents('http://' . $_SERVER['HTTP_HOST'] . "/test.html");

      //      if ($item->fields['is_advancedmode'] == '0') {

      //         $taskjob = current($a_taskjob);

      //         if (!isset($taskjob["id"])) {

      //            $taskjobs_id = $pfTaskjob->add(
      //               array(
      //                     'name'=>$item->fields['name'],
      //                     'entities_id'=>$item->fields['entities_id'],
      //                     'plugin_fusioninventory_tasks_id'=>$item->getID()
      //               )
      //            );

      //            $pfTaskjob->showForm($taskjobs_id);

      //         } else {

      //            $pfTaskjob->showForm($taskjob["id"]);

      //         }
      //      } else {
      //         if ($tabnum !== 'new') {
      //            $taskjob_id = $tabnum;
      //            $pfTaskjob = new PluginFusioninventoryTaskjob();
      //            $pfTaskjob->showForm($taskjob_id);
      //            $pfTaskjob->manageTasksByObject($item->getType(), $item->getID());
      //         } else {
      //            $pfTaskjob = new PluginFusioninventoryTaskjob();
      //            $pfTaskjob->showForm('');
      //         }
      //      }
         }
      }
      return true;
   }

   function ajaxLoadItem($options) {
      /*
       * The following has been borrowed from Html::display() and CommonGLPI::showTabsContent().
       *
       * TODO: maybe this can be shared through CommonView. -- Kevin Roy <kiniou@gmail.com>
       */

      if (  isset($options['id'])
            and !$this->isNewID($options['id']))
      {
         if (!$this->getFromDB($options['id'])) {
            Html::displayNotFoundError();
         }
      }

      // for objects not in table like central
      $ID=0;

      if (isset($this->fields['id'])) {
         $ID = $this->fields['id'];
      } else {
         if ( isset($options['id']) ) {
            $option_id = $options['id'];
            //Check for correct type of ID received from outside.
            if ( is_string($option_id)
               and ctype_digit($options_id)
            ) {
               $ID = (int)($options['id']);
            } else if (is_int($option_id)){
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

   function ajaxGetForm($options) {
      $ID = $this->ajaxLoadItem($options);
      $this->showForm($ID,$options);
   }

   public function showListHeader($task_id, $deletion_activated) {
      echo "<tr>";
      //Show checkbox to select every objects for deletion.
      if ($deletion_activated) {
         echo "<th>";
         echo Html::getCheckAllAsCheckbox("taskjobs_list", mt_rand());
         echo "</th>";
      }
      echo "<th colspan='2' class='center'>";
      echo implode("\n", array(
         "<input ",
         "  type='button' class='submit'",
         "  style='padding:5px;margin:0;right:0'",
         "  value=' ".__('Add a job', 'fusioninventory')." ' ",
         "  onclick='taskjobs.create(",
         "     \"".$this->getBaseUrlFor('fi.job.create')."\", ",
         "     $task_id",
         "  )'",
         "/>",
      ));
      echo "</th>";
      echo "</tr>";
   }

   public function getItemsList($module_type) {

      $items = importArrayFromDB($this->fields[$module_type]);
      $result = array();
      foreach($items as $item) {
         $itemtype = key($item);
         $itemid = $item[$itemtype];
         $result[] = $this->getItemDisplay($module_type, $itemtype, $itemid);
      }
      return implode("\n", $result);
   }

   public function getItemDisplay($module_type, $itemtype, $itemid) {

      $item = getItemForItemtype($itemtype);
      $item->getFromDB($itemid);
      $itemtype_name = $item->getTypeName();

      $item_fullid = $itemtype . '-' . $itemid;
      return implode("\n", array(
         "<div class='taskjob_item' id='" . $item_fullid . "'",
         "  >" ,
         "  <input type='checkbox'>" ,
         "  </input>" ,
         "  <span class='" . $itemtype ."'></span>",
         "  <label>",
         "     <span style='font-style:oblique'>" . $itemtype_name ."</span>" ,
         "     ". $item->getLink(array('linkoption'=>'target="_blank"')) ,
         "  </label>",
         "  <input type='hidden' name='" . $module_type ."[]' value='". $item_fullid ."'>" ,
         "  </input>" ,
         "</div>"
      ));

   }

   public function showListForTask($task_id) {

      global $CFG_GLPI;

      $taskjobs = $this->getTaskjobs($task_id);

      //Activate massive deletion if there are some.
      $deletion_activated = (count($taskjobs)>0);

      /**
       * TODO: use sortable jqueryUI widget for drag and drop
       */
      //echo implde(array("\n", array(
      //   "<script type='text/javascript'>",
      //   "$('#taskjobs_list ')",
      //   "</script>"
      //));
      echo "<form id='taskjobs_form' method='post' action='".$this->getFormURL()."'>";
      echo "<table class='tab_cadrehov package_item_list' id='taskjobs_list'>\n";
      $this->showListHeader($task_id, $deletion_activated);
      foreach( $taskjobs as $taskjob_id => $taskjob_data ) {
         echo "<tr class='tab_bg_2'>\n";
         $this->showTaskjobSummary( $taskjob_data );
         echo "</tr>\n";
      }

      $this->showListHeader($task_id, $deletion_activated);
      echo "</table>\n";

      //Show the delete button for selected object
      if ($deletion_activated) {
         echo "<div class='left'>";
         echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt=''>";
         echo "<input type='submit' name='delete_taskjobs' value=\"".
            __('Delete', 'fusioninventory')."\" class='submit'>";
         echo "</div>";
      }
      Html::closeForm();
   }

   public function getTaskjobs($task_id) {
      // Find taskjobs tied to the selected task
      $taskjobs = $this->find(
         "`plugin_fusioninventory_tasks_id` = '".$task_id."'".
         " AND `rescheduled_taskjob_id` = '0' ",
         "id"
      );
      return $taskjobs;
   }

   public function showTaskjobSummary($taskjob_data) {
      global $CFG_GLPI;
      $id = $taskjob_data['id'];
      $name = $taskjob_data['name'];
      echo implode( "\n",
         array(
            "<td class='control'>",
            "<input type='checkbox' name='taskjobs[]' value='$id' />",
            "</td>"
         )
      );
      echo implode( "\n",
         array(
            "<td id='taskjob_${id}' class='taskjob_block'>",
            "  <a ",
            "     href='#taskjobs_form'",
            "     onclick='taskjobs.edit(",
            "        \"".$this->getBaseUrlFor('fi.job.edit')."\", ",
            "        $id",
            "     )'>${name}</a>",
            "</td>",
         )
      );
      echo "<td class='rowhandler control'><div class='drag'/></td>";
   }

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
         array('' => '------'),
         $this->getTypesForModule($options['method'], $options['moduletype'])
      );
      $module_types_dropdown = $this->showDropdownFromArray(
         $title, null, $module_types
      );
      echo implode(array("\n",
         "<script type='text/javascript'>",
         "  taskjobs.register_update_items(",
         "     'dropdown_$module_types_dropdown', ",
         "     '".$options['moduletype']."', ",
         "     '".$this->getBaseUrlFor('fi.job.moduleitems')."' ",
         "  );",
         "</script>"
      ));
   }

   public function ajaxModuleItemsDropdown($options) {
      $moduletype = $options['moduletype'];
      $itemtype = $options['itemtype'];
      if ($itemtype === "") {
         return;
      }
      switch ($options['moduletype']) {
         case 'actors':
            $title = __('Actor Item', 'fusioninventory');
            break;

         case 'targets':
            $title = __('Target Item', 'fusioninventory');
            break;
      }
      /**
       * get Itemtype choices dropdown
       */
      $dropdown_rand = $this->showDropdownForItemtype(
         $title,
         $itemtype,
         array('width'=>"95%")
      );
      $item = getItemForItemtype($itemtype);
      $itemtype_name = $item->getTypeName();
      $item_key_id = $item->getForeignKeyField();
      $dropdown_rand_id = "dropdown_".$item_key_id . $dropdown_rand;
      echo implode( array("\n",
         "<div class='center' id='add_fusinv_job_item_button'>",
         "<input type='button' class=submit",
         "  value='".__('Add')." $title'",
         "  onclick='javascript:void(0)'>",
         "</input>",
         "</div>"
      ));
      echo Html::scriptBlock(implode("\n",array(
         "$('#add_fusinv_job_item_button').on('click', function() {",
         "  taskjobs.add_item(",
         "     \"$moduletype\", \"$itemtype\", \"$itemtype_name\", \"$dropdown_rand_id\"",
         "  );",
         "});",
      )));
   }

   public function getAddItemtypeButton($title, $itemtype, $method) {
      return
         implode("\n", array(
            "<a ",
            "  class='addbutton'",
            "  href='javascript:void(0)'",
            "  onclick='taskjobs.show_moduletypes(",
            "     \"".$this->getBaseUrlFor('fi.job.moduletypes')."\", ",
            "     \"".$itemtype."\",",
            "     \"".$method."\"",
            "  )'",
            ">",
            $title,
            "<img src='".$this->getBaseUrlFor('glpi.pics')."/add_dropdown.png' />",
            "</a>"
         ));
   }

   public function _showActors() {
      global $CFG_GLPI;

      //$ok = $this->getFromDB($id);
      $id = $this->fields['id'];
      $name = $this->fields['name'];
      echo "<table class='tab_cadre'>";
      $nb = 0;
      if ($ok) {
         $a_typenames = importArrayFromDB($this->fields[$name]);
         foreach ($a_typenames as $key=>$a_typename) {
            foreach ($a_typename as $itemtype=>$items_id) {
               $display = '';
               if ($itemtype == "PluginFusioninventoryAgent"
                       AND $items_id == "" ) {
                  $display = __('Auto managenement dynamic of agents', 'fusioninventory');

               } else if ($itemtype == "PluginFusioninventoryAgent"
                       AND $items_id == ".2" ) {
                  $display =
                        __('Auto managenement dynamic of agents (same subnet)', 'fusioninventory');

               } else {
                  $class = new $itemtype();
                  $class->getFromDB($items_id);
                  $display = $class->getLink(1);
               }
               echo "<tr>";
               echo "<td style='padding: 1px 2px;'>";
               echo "<input type='checkbox' name='".$name."item' value='".$key."'>";
               echo "</td>";
               echo "<td style='padding: 1px 2px;'>";
               echo $display;
               echo "</td>";
               echo "</tr>";
               $nb++;
            }
         }
      }
      echo "</table>";

      if ($nb > 0) {
         echo "<center><input type='button' id='delete".$name.$id."' name='delete".$name.$id."' ".
                 "value=\"".__('Delete', 'fusioninventory')."\" class='submit'></center>";

         $params = array($name.'item' => '__CHECKBOX__',
                         'type'      => $name,
                         'taskjobs_id'=>$id);

         $toobserve = "delete".$name.$id;
         $toupdate = "Deleteitem";
         $url = $CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/taskjobdeletetype.php";
         $parameters=$params;
         $events=array("click");
         $minsize = -1;
         $forceloadfor=array(__('Delete', 'fusioninventory'));

         echo "<script type='text/javascript'>";

         echo implode( "\n",
            array(
               "  function checkboxvalues(item) {",
               "     var inputs = document.getElementsByName(item);",
               "     var namelist = '';",
               "     for(var i = 0; i < inputs.length; i++){",
               "        if(inputs[i].checked) {",
               "           namelist += inputs[i].value + '-';",
               "        }",
               "     }",
               "     return namelist;",
               "  }"
            )
         );

         $zones = array($toobserve);
         if (is_array($toobserve)) {
            $zones = $toobserve;
         }

         foreach ($zones as $zone) {
            foreach ($events as $event) {
               echo "
                  Ext.get('$zone').on(
                   '$event',
                   function() {";
                     $condition = '';
                     if ($minsize >= 0) {
                        $condition = " Ext.get('$zone').getValue().length >= $minsize ";
                     }
                     if (count($forceloadfor)) {
                        foreach ($forceloadfor as $value) {
                           if (!empty($condition)) {
                              $condition .= " || ";
                           }
                           $condition .= "Ext.get('$zone').getValue() == '$value'";
                        }
                     }
                     if (!empty($condition)) {
                        echo "if ($condition) {";
                     }
                     //self::updateItemJsCode($toupdate, $url, $parameters, $toobserve);

                     // Get it from a Ext.Element object
                     $out = "Ext.get('$toupdate').load({
                         url: '$url',
                         scripts: true";

                     if (count($parameters)) {
                        $out .= ",
                            params:'";
                        $first = TRUE;
                        foreach ($parameters as $key => $val) {
                           if ($first) {
                              $first = FALSE;
                           } else {
                              $out .= "&";
                           }

                           $out .= $key."=";

                           if ($val==="__CHECKBOX__") {
                              $out .=  "'+checkboxvalues('".$key."')+'";

                           } else {
                              if (preg_match("/'/", $val)) {
                                 $out .=  rawurlencode($val);
                              } else {
                                 $out .=  $val;
                              }
                           }
                        }
                        echo $out."'\n";
                     }
                     echo "});";


                     if (!empty($condition)) {
                        echo "}";
                     }

             echo "});\n";
            }
         }
         echo "</script>";
         echo "<span id='Deleteitem'>&nbsp;</span>";
      }
   }


   /**
   * Display form for taskjob
   *
   * @param $items_id integer id of the taskjob
   * @param $options array
   *
   * @return bool TRUE if form is ok
   *
   **/
   function showForm($id, $options=array()) {
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
            $CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/taskjob.form.php''>";

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
      //echo "<td>".__('Name')."&nbsp;:</td>";
      //echo "<td>";
      ////if ($pfTask->fields["is_advancedmode"] == '0'
      ////        AND $this->fields["name"] == '') {

      ////   $this->fields["name"] = $pfTask->fields["name"];
      ////}
      //Html::autocompletionTextField ($this, "name", $this->fields["name"]);

      /*
       * Display Module dropdown
       */
      //echo "</td>";
      //if ($this->fields['id'] > 0) {
      //   echo "<td>".__('Module', 'fusioninventory')."&nbsp;:</td>";
      //   echo "<td>";
      //   $randmethod = $this->dropdownMethod("method", $this->fields['method']);
      //   echo "<div style='display:none' id='methodupdate' >";
      //   $params = array('method' => '__VALUE__',
      //                   'rand'      => $randmethod,
      //                   'myname'    => 'method',
      //                   'name'      => 'methodupdate',
      //                   'taskjobs_id'=>$id );
      //   Ajax::updateItemOnEvent("dropdown_method".$randmethod,
      //                           "methodupdate",
      //                           $CFG_GLPI["root_doc"].
      //                              "/plugins/fusioninventory/ajax/taskmethodupdate.php",
      //                           $params);
      //   echo "</div>";
      //}
      //echo "</td>";
      //echo "</tr>";

      //echo "<tr class='tab_bg_1'>";
      //echo "<td>".__('Comments')."&nbsp;:</td>";
      //echo "<td>";
      //echo "<textarea cols='40' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";
      //echo "</td>";
      $this->showTextArea(__('Comments'), "comment");

      $modules_methods = PluginFusioninventoryStaticmisc::getModulesMethods();
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
      //if (!$new_item) {
      //   $params = array('method' => '__VALUE__',
      //      'rand'      => $modules_methods_rand,
      //      'myname'    => 'method',
      //      'name'      => 'configuration_form',
      //      'taskjobs_id'=>$id );
      //   Ajax::updateItemOnEvent(
      //      "dropdown_method".$modules_methods_rand,
      //      "methodupdate",
      //      $CFG_GLPI["root_doc"].
      //      "/plugins/fusioninventory/ajax/taskmethodupdate.php",
      //      $params);
      //}

      //if (! $new_item) {

      //   foreach ($module_methods as $method) {
      //      echo
      //         "<input type='hidden' name='method-".$method['method']."' "
      //         ."value='".PluginFusioninventoryModule::getModuleId($method['module'])
      //         ."' />";
      //   }
      //}
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

         echo "<div id='taskjob_moduletypes_dropdown' />";
         echo "<div id='taskjob_moduleitems_dropdown' />";
         echo "</div>";
      }

      $targets_display_list = $this->getItemsList('targets');
      // Display targets and actors lists
      echo implode("\n", array(
         "<hr/>",
         "<div>",
         "  <div class='taskjob_list_header'>",
         "     <label>".__('Targets', 'fusioninventory')."&nbsp;:</label>",
         "  </div>",
         "  <div id='taskjob_targets_list'>",
         $targets_display_list,
         "  </div>",
         "  <div>",
         "     <a href='javascript:void(0)'",
         "        onclick='taskjobs.clear_list(\"targets\")'",
         "        >".__('Clear list', 'fusioninventory')."</a>",
         "        /",
         "     <a href='javascript:void(0)'",
         "        onclick='taskjobs.delete_items_selected(\"targets\")'",
         "        >".__('Delete selected items', 'fusioninventory')."</a>",
         "  </div>",
         "</div>",
      ));

      $actors_display_list = $this->getItemsList('actors');
      echo implode("\n", array(
         "<hr/>",
         "<div>",
         "  <div class='taskjob_list_header'>",
         "     <label>".__('Actors', 'fusioninventory')."&nbsp;:</label>",
         "  </div>",
         "  <div id='taskjob_actors_list'>",
         $actors_display_list,
         "  </div>",
         "  <div>",
         "     <a href='javascript:void(0)'",
         "        onclick='taskjobs.clear_list(\"actors\")'",
         "        >".__('Clear list', 'fusioninventory')."</a>",
         "        /",
         "     <a href='javascript:void(0)'",
         "        onclick='taskjobs.delete_items_selected(\"actors\")'",
         "        >".__('Delete selected items', 'fusioninventory')."</a>",
         "  </div>",
         "</div>",
      ));
      /*
       * Advanced mode related display (should be dropped)
       */
      //echo "<tr class='tab_bg_1'>";
      //$rowspan = 4;
      //if ($pfTask->fields["is_advancedmode"] == '1') {
      //   echo "<td>";
      //   echo __('Time between task start and start this action', 'fusioninventory')."&nbsp;:";
      //   echo "</td>";
      //   echo "<td>";
      //   Dropdown::showNumber("periodicity_count", array(
      //          'value' => $this->fields['periodicity_count'],
      //          'min'   => 0,
      //          'max'   => 300)
      //   );
      //   $a_time = array();
      //   $a_time[] = "------";
      //   $a_time['minutes'] = strtolower(__('Minute(s)', 'fusioninventory'));

      //   $a_time['hours'] = strtolower(__('hour(s)', 'fusioninventory'));

      //   $a_time['days'] = __('day(s)', 'fusioninventory');

      //   $a_time['months'] = __('months');

      //   Dropdown::showFromArray("periodicity_type",
      //                           $a_time,
      //                           array('value'=>$this->fields['periodicity_type']));
      //   echo "</td>";
      //} else {
      //   if ($this->fields['id'] > 0) {
      //      $pfTaskjoblog->displayShortLogs($this->fields['id']);
      //   } else {
      //      echo "<td colspan='2'></td>";
      //   }
      //   $rowspan = 1;
      //}

      if( !$new_item ) {
         // ** Definitions
         //echo "<td rowspan='".$rowspan."' valign='top'>";
         //$this->showTaskjobItems('definition', $modules_methods_rand, $id);
         //echo "</td>";

         // ** Actions
         //echo "<td rowspan='".$rowspan."' valign='top'>";
         //$this->showTaskjobItems('action', $modules_methods_rand, $id);
         //echo "</td>";
         //echo "</tr>";
      }
      //if ($pfTask->fields["is_advancedmode"] == '1') {
      //   echo "<tr class='tab_bg_1'>";
      //   echo "<td>".__('Number of trials', 'fusioninventory')."&nbsp;:</td>";
      //   echo "<td>";
      //   Dropdown::showNumber("retry_nb", array(
      //          'value' => $this->fields['retry_nb'],
      //          'min'   => 0,
      //          'max'   => 30)
      //   );
      //   echo "</td>";
      //   echo "</tr>";

      //   echo "<tr class='tab_bg_1'>";
      //   echo "<td>".__('Time between 2 trials (in minutes)', 'fusioninventory')."&nbsp;:</td>";
      //   echo "<td>";
      //   Dropdown::showNumber("retry_time", array(
      //          'value' => $this->fields['retry_time'],
      //          'min'   => 0,
      //          'max'   => 360)
      // );
      //   echo "</td>";
      //   echo "</tr>";

      //   echo "<tr>";
      //   echo "<td colspan='2'></td>";
      //   echo "</tr>";
      //}


      if ($new_item) {
         echo "<tr>";
         echo "<td colspan='4' valign='top' align='center'>";
         echo "<input type='submit' name='add' value=\"".__('Add')."\" class='submit'>";
         echo "</td>";
         echo '</tr>';
      } else {
         echo "<tr>";
         echo "<td class='center'>";
         echo "<input type='submit' name='update' value=\"".__('Update')."\" class='submit'>";
         echo "</td>";

         echo implode("\n", array(
            "<td class='center' colspan='2'>",
            "<div id='cancel_job_changes_button' style='display:none'>",
            "<input type='button' class='submit'",
            "     onclick='taskjobs.edit(",
            "        \"".$this->getBaseUrlFor('fi.job.edit')."\", ",
            "        $id",
            "     )'",
            " value=\"".__('Cancel modifications','fusioninventory')."\"/>",
            "</div>",
            "</td>",
         ));

         echo "<td class='center'>";
         echo "<input type='submit' name='delete' value=\"".__('Purge', 'fusioninventory')."\"
                         class='submit' ".
               Html::addConfirmationOnAction(__('Confirm the final deletion ?', 'fusioninventory')).
                 ">";
         echo "</td>";
         echo '</tr>';
      }

      echo "</table>";
      Html::closeForm();

      echo implode("\n", array(
         "<script type='text/javascript'>",
         "  taskjobs.register_form_changed();",
         "</script>"
      ));

      echo implode("\n", array(
         "<script language='javascript'>",
         "  function expandtaskjobform() {",
         "     document.getElementById('taskjobdisplay').style.overflow='visible';",
         "     document.getElementById('taskjobdisplay').style.height='auto';",
         "     document.getElementById('seemore').style.display = 'none';",
         "  }",
         "</script>"
      ));

      echo "<br/>";

      //$pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      //$pfTaskjoblog->showHistory($id);

      return TRUE;
   }

   function showTaskjobItems($name, $randmethod, $id) {
      global $CFG_GLPI;
/*
      echo "<div style='display:none' id='".$name.$id."' >";
      $params = array('method' => '__VALUE__',
                      'rand'      => $randmethod,
                      'myname'    => 'method',
                      'typename'  => $name,
                      'taskjobs_id'=>$id );
      Ajax::updateItemOnEvent("dropdown_method".$randmethod,
                              "show".$name."Type".$id,
                              $CFG_GLPI["root_doc"].
                                 "/plugins/fusioninventory/ajax/dropdowntype.php",
                              $params,
                              array("change", "load"));
      if ($this->fields['method'] != "") {
         echo "<script type='text/javascript'>";
         Ajax::UpdateItemJsCode("show".$name."Type".$id,
                                $CFG_GLPI["root_doc"].
                                   "/plugins/fusioninventory/ajax/dropdowntype.php",
                                $params,
                                "dropdown_method".$randmethod);
         echo "</script>";
      }
      echo "<span id='show".$name."Type".$id."'>&nbsp;</span>";
      echo "<span id='show_".ucfirst($name)."List".$id."'>&nbsp;</span>";
      echo "<hr>";
      echo "</div>";
      // Display itemname list
      echo "<script type='text/javascript'>";
      $params['taskjobs_id'] = $id;
      Ajax::UpdateItemJsCode("show".$name."list".$id."_",
                                $CFG_GLPI["root_doc"].
                                   "/plugins/fusioninventory/ajax/dropdownlist.php",
                                $params,
                                "dropdown_method".$randmethod);
      echo "</script>";
      echo "<span id='show".$name."list".$id."_'>&nbsp;</span>";
 */
   }

   /**
    * Submit Form values
    */
   public function submitForm($postvars) {
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
                  $a_listdef[] = array($postvars['DefinitionType']=>$postvars['definitionselectiontoadd']);
               }
         }
         $input = array();
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
                  $a_listact[] = array($postvars['ActionType']=>$postvars['actionselectiontoadd']);
               }
         }
         $input = array();
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
         $input = array();
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
         $input = array();
         $input['id'] = $postvars['id'];
         $input['action'] = exportArrayToDB($a_listact);
         $mytaskjob->update($input);
         Html::back();
      /**
       * Wizard related method disabled for 0.85
       * TODO: cf. TaskJob::showQuickForm()
       */
      //} else if (isset($postvars['quickform'])) {
      //   $pfTask = new PluginFusioninventoryTask();

      //   if (isset($postvars['update'])) {
      //      $mytaskjob->getFromDB($postvars['id']);
      //      $pfTask->getFromDB($mytaskjob->fields['plugin_fusioninventory_tasks_id']);
      //   }

      //   $inputtaskjob = array();
      //   $inputtask = array();
      //   if (isset($postvars['update'])) {
      //      $inputtaskjob['id'] = $postvars['id'];
      //      $inputtask['id'] = $mytaskjob->fields['plugin_fusioninventory_tasks_id'];
      //   }

      //   $inputtaskjob['name'] = $postvars['name'];
      //   if (isset($postvars['add']) OR $pfTask->fields['name'] == '') {
      //      $inputtask['name'] = $postvars['name'];
      //   }
      //   $inputtask['is_active'] = $postvars['is_active'];
      //   $inputtaskjob['method'] = $postvars['method'];
      //   $inputtask['communication'] = $postvars['communication'];
      //   $inputtask['periodicity_count'] = $postvars['periodicity_count'];
      //   $inputtask['periodicity_type'] = $postvars['periodicity_type'];

      //   $inputtask['entities_id'] = $_SESSION['glpiactive_entity'];
      //   $inputtaskjob['entities_id'] = $_SESSION['glpiactive_entity'];

      //   if (isset($postvars['update'])) {
      //      $mytaskjob->update($inputtaskjob);
      //      $pfTask->update($inputtask);
      //      Html::back();
      //   } else if (isset($postvars['add'])) {
      //      if (!isset($postvars['entities_id'])) {
      //         $postvars['entities_id'] = $_SESSION['glpidefault_entity'];
      //      }
      //      // Get entity of task
      //      if (isset($postvars['plugin_fusioninventory_tasks_id'])) {
      //         $pfTask = new PluginFusioninventoryTask();
      //         $pfTask->getFromDB($postvars['plugin_fusioninventory_tasks_id']);
      //         $entities_list = getSonsOf('glpi_entities', $pfTask->fields['entities_id']);
      //         if (!in_array($postvars['entities_id'], $entities_list)) {
      //            $postvars['entities_id'] = $pfTask->fields['entities_id'];
      //         }
      //      } else {
      //         $inputtask['date_scheduled'] = date("Y-m-d H:i:s");
      //         $task_id = $pfTask->add($inputtask);
      //         $inputtaskjob['plugin_fusioninventory_tasks_id'] = $task_id;
      //      }
      //      if (isset($postvars['method_id'])) {
      //         $postvars['method']  = $postvars['method_id'];
      //      }
      //      $inputtaskjob['plugins_id'] = $postvars['method-'.$postvars['method']];
      //      $taskjobs_id = $mytaskjob->add($inputtaskjob);

      //      $redirect = $_SERVER['HTTP_REFERER'];
      //      $redirect = str_replace('&id=0', '&id='.$taskjobs_id, $redirect);
      //      Html::redirect($redirect);
      //   }
      } else if (isset($postvars['taskjobstoforcerun'])) {
         // * Force running many tasks (wizard)
         Session::checkRight('plugin_fusioninventory_task', UPDATE);
         $pfTaskjob = new PluginFusioninventoryTaskjob();
         $_SESSION["plugin_fusioninventory_forcerun"] = array();
         foreach ($postvars['taskjobstoforcerun'] as $taskjobs_id) {
            $pfTaskjob->getFromDB($taskjobs_id);
            $uniqid = $pfTaskjob->forceRunningTask($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);
            $_SESSION["plugin_fusioninventory_forcerun"][$taskjobs_id] = $uniqid;
         }
         unset($_SESSION["MESSAGE_AFTER_REDIRECT"]);
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
            //$postvars['execution_id'] = $pfTask->fields['execution_id'];
            $this->add($postvars);
         } else {
            if (isset($postvars['method_id'])) {
               $postvars['method']  = $postvars['method_id'];
            }

            $targets = array();
            if( array_key_exists('targets', $postvars)
               and is_array($postvars['targets'])
               and count($postvars['targets']) > 0
            ) {
               foreach( $postvars['targets'] as $target ) {
                  list($itemtype, $itemid) = explode('-',$target);
                  $targets[] = array($itemtype => $itemid);
               }
            }

            $postvars['targets'] = exportArrayToDB($targets);

            $actors = array();
            if(
               array_key_exists('actors', $postvars)
               and is_array($postvars['actors'])
               and count($postvars['actors']) > 0
            ) {
               foreach( $postvars['actors'] as $actor ) {
                  list($itemtype, $itemid) = explode('-',$actor);
                  $actors[] = array($itemtype => $itemid);
               }
            }

            $postvars['actors'] = exportArrayToDB($actors);

            //TODO: get rid of plugins_id and just use method
            //$postvars['plugins_id'] = $postvars['method-'.$postvars['method']];
            $this->update($postvars);
         }

      } else if (isset($postvars["delete"])) {
         // * delete taskjob
         Session::checkRight('plugin_fusioninventory_task', PURGE);

         $this->delete($postvars);

      } elseif (isset($postvars['itemaddaction'])) {
         $array                     = explode("||", $postvars['methodaction']);
         $module                    = $array[0];
         $method                    = $array[1];
         // Add task
         $mytask = new PluginFusioninventoryTask();
         $input                     = array();
         $input['name']             = $method;

         $task_id = $mytask->add($input);

         // Add job with this device
         $input = array();
         $input['plugin_fusioninventory_tasks_id'] = $task_id;
         $input['name']                            = $method;
         $input['date_scheduled']                  = $postvars['date_scheduled'];

         $input['plugins_id']                      = PluginFusioninventoryModule::getModuleId($module);
         $input['method']                          = $method;
         $a_selectionDB                            = array();
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


      } elseif (isset($postvars['forceend'])) {
         $taskjobstate = new PluginFusioninventoryTaskjobstate();
         $pfTaskjob = new PluginFusioninventoryTaskjob();
         $mytaskjobstate->getFromDB($postvars['taskjobstates_id']);
         $jobstate = $mytaskjobstate->fields;
         $a_taskjobstates = $mytaskjobstate->find("`uniqid`='".$mytaskjobstate->fields['uniqid']."'");
         foreach($a_taskjobstates as $data) {
            if ($data['state'] != PluginFusioninventoryTaskjobstate::FINISHED) {
               $mytaskjobstate->changeStatusFinish($data['id'],
                  0, '', 1, "Action cancelled by user", 0, 0);
            }
         }

         $pfTaskjob->getFromDB($jobstate['plugin_fusioninventory_taskjobs_id']);
         $pfTaskjob->reinitializeTaskjobs($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);

      } elseif (isset($postvars['delete_taskjobs'])) {
         foreach($postvars['taskjobs'] as $taskjob_id) {
            $input = array('id'=>$taskjob_id);
            $this->delete($input, true);
         }
      }
   }

}

