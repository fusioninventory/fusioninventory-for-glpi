<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

class PluginFusioninventoryTaskjobView extends CommonDBTM {

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $CFG_GLPI;

      $tab_names = array();
      if ( $item->getID() > 0 and $this->can('task', 'r') ) {
         $tab_names[] = __('Task Jobs');
      }

      //if (Session::haveRight('plugin_fusioninventory_task', READ)) {

      //   if ($item->getType() == 'PluginFusioninventoryTask') {

      //      if ($item->fields['id'] > 0) {

      //         //Get taskjobs list tied to the currently displayed task in advanced mode
      //         if ($item->fields["is_advancedmode"] == '1') {

      //            $pft = new PluginFusioninventoryTaskjob;

      //            $taskjobs = $pft->find(
      //               "`plugin_fusioninventory_tasks_id`='".$_GET['id'].
      //               "' AND `rescheduled_taskjob_id`='0' ",
      //               "id"
      //            );
      //            $i=0;
      //            foreach($taskjobs as $data) {
      //               $i++;

      //               $tab_names[$data['id']] =
      //                  __('Job', 'fusioninventory') . " $i - " .
      //                  $data['name'];

      //            }

      //            //Add a 'new' tab in order to create new taskjobs
      //            $tab_names['new'] = __('New action', 'fusioninventory')." <img src='".$CFG_GLPI['root_doc']."/pics/add_dropdown.png'/>";
      //         } else {

      //            //The non advanced mode display only one tab
      //            $tab_names[0] = __('FusInv', 'fusioninventory').' '. _n('Task', 'Tasks', 2);
      //         }
      //      }
      //   }
      //}

      //Return tab names if list is not empty
      if (!empty($tab_names)) {
         return $tab_names;
      } else {
         return '';
      }

   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pfTaskjob = new PluginFusioninventoryTaskjob();

      if ($item->getID() > 0) {
         if ($item->getType() == 'PluginFusioninventoryTask') {

            $pfTaskJob->showList();


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

   public function getList() {
      // Find taskjobs tied to the selected task
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $taskjobs = $this->find(
         "`plugin_fusioninventory_tasks_id` = '".$item->getID()."'".
         " AND `rescheduled_taskjob_id` = '0' ",
         "id"
      );
      Toolbox::logDebug($taskjobs);
      return $taskjobs;
   }

   public function showList() {
      $taskjobs = $this->getList();
   }

   public function showTargets() {

   }

   public function showActors() {
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
                       AND $items_id == ".1" ) {
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

      $pfTask       = new PluginFusioninventoryTask();
      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();

      $pfTask->getFromDB($_POST['id']);

      if ($id!='') {
         if ($this->getFromDB($id)) {
            $this->verifyDefinitionActions($id);
            $this->getFromDB($id);
         } else {
            $id = '';
            $this->getEmpty();
         }
      } else {
         $this->getEmpty();
      }

      echo "<form method='post' name='form_taskjob' action='".
            $CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/taskjob.form.php''>";

      if ($id!='') {
         echo "<input type='hidden' name='id' value='".$id."' />";
      }
      echo "<table class='tab_cadre_fixe'>";

      // Optional line
      $ismultientities = Session::isMultiEntitiesMode();
      echo '<tr>';
      echo '<th colspan="4">';

      if ($id) {
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
      echo "<td>".__('Name')."&nbsp;:</td>";
      echo "<td>";
      if ($pfTask->fields["is_advancedmode"] == '0'
              AND $this->fields["name"] == '') {

         $this->fields["name"] = $pfTask->fields["name"];
      }
      Html::autocompletionTextField ($this, "name", $this->fields["name"]);
      echo "</td>";
      if ($this->fields['id'] > 0) {
         echo "<td>".__('Module', 'fusioninventory')."&nbsp;:</td>";
         echo "<td>";
         $randmethod = $this->dropdownMethod("method", $this->fields['method']);
         echo "<div style='display:none' id='methodupdate' >";
         $params = array('method' => '__VALUE__',
                         'rand'      => $randmethod,
                         'myname'    => 'method',
                         'name'      => 'methodupdate',
                         'taskjobs_id'=>$id );
         Ajax::updateItemOnEvent("dropdown_method".$randmethod,
                                 "methodupdate",
                                 $CFG_GLPI["root_doc"].
                                    "/plugins/fusioninventory/ajax/taskmethodupdate.php",
                                 $params);
         echo "</div>";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Comments')."&nbsp;:</td>";
      echo "<td>";
      echo "<textarea cols='40' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";

      echo
         "<input type='hidden' name='plugin_fusioninventory_tasks_id' ".
         "value='".$pfTask->fields['id']."' />";
      if ($this->fields['id'] > 0) {

         $a_methods = PluginFusioninventoryStaticmisc::getmethods();
         foreach ($a_methods as $datas) {
            echo
               "<input type='hidden' name='method-".$datas['method']."' "
               ."value='".PluginFusioninventoryModule::getModuleId($datas['module'])
               ."' />";
         }
      }
      echo "</td>";
      // Display Definition choices
      if ($this->fields['id'] > 0) {
      echo "<th width='25%'>";
         echo __('Definition', 'fusioninventory');

         $this->plusButton('definition'.$id);
         echo "<br/><i>".
             __('Action targets: what the action aims', 'fusioninventory').
             "</i>";
      echo "</th>";
      }

      //Display Actors choices
      if ($this->fields['id'] > 0) {
      echo "<th width='25%'>";
         echo __('Action');

         $this->plusButton('action'.$id);
         echo "<br/><i>".
             __('Action actor: what do the action', 'fusioninventory').
             "</i>";
      echo "</th>";
      }
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      $rowspan = 4;
      if ($pfTask->fields["is_advancedmode"] == '1') {
         echo "<td>";
         echo __('Time between task start and start this action', 'fusioninventory')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         Dropdown::showNumber("periodicity_count", array(
                'value' => $this->fields['periodicity_count'],
                'min'   => 0,
                'max'   => 300)
         );
         $a_time = array();
         $a_time[] = "------";
         $a_time['minutes'] = strtolower(__('Minute(s)', 'fusioninventory'));

         $a_time['hours'] = strtolower(__('hour(s)', 'fusioninventory'));

         $a_time['days'] = __('day(s)', 'fusioninventory');

         $a_time['months'] = __('months');

         Dropdown::showFromArray("periodicity_type",
                                 $a_time,
                                 array('value'=>$this->fields['periodicity_type']));
         echo "</td>";
      } else {
         if ($this->fields['id'] > 0) {
            $pfTaskjoblog->displayShortLogs($this->fields['id']);
         } else {
            echo "<td colspan='2'></td>";
         }
         $rowspan = 1;
      }

      if($this->fields['id'] > 0) {
         // ** Definitions
         echo "<td rowspan='".$rowspan."' valign='top'>";
         $this->showTaskjobItems('definition', $randmethod, $id);
         echo "</td>";

         // ** Actions
         echo "<td rowspan='".$rowspan."' valign='top'>";
         $this->showTaskjobItems('action', $randmethod, $id);
         echo "</td>";
         echo "</tr>";
      }
      if ($pfTask->fields["is_advancedmode"] == '1') {
         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Number of trials', 'fusioninventory')."&nbsp;:</td>";
         echo "<td>";
         Dropdown::showNumber("retry_nb", array(
                'value' => $this->fields['retry_nb'],
                'min'   => 0,
                'max'   => 30)
         );
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Time between 2 trials (in minutes)', 'fusioninventory')."&nbsp;:</td>";
         echo "<td>";
         Dropdown::showNumber("retry_time", array(
                'value' => $this->fields['retry_time'],
                'min'   => 0,
                'max'   => 360)
       );
         echo "</td>";
         echo "</tr>";

         echo "<tr>";
         echo "<td colspan='2'></td>";
         echo "</tr>";
      }

      echo "<tr>";
      if ($id<=0) {
         echo "<td colspan='4' valign='top' align='center'>";
         echo "<input type='submit' name='add' value=\"".__('Add')."\" class='submit'>";
         echo "</td>";
      } else {
         echo "<td valign='top' align='center' colspan='2'>";
         echo "<input type='submit' name='update' value=\"".__('Update')."\" class='submit'>";
         echo "</td>";
         echo "<td valign='top' align='center' colspan='2'>";
         echo "<input type='submit' name='delete' value=\"".__('Purge', 'fusioninventory')."\"
                         class='submit' ".
               Html::addConfirmationOnAction(__('Confirm the final deletion ?', 'fusioninventory')).
                 ">";
         echo "</td>";
      }
      echo '</tr>';

      echo "</table>";
      Html::closeForm();

      echo "<script language='javascript'>
         function expandtaskjobform() {
            document.getElementById('taskjobdisplay').style.overflow='visible';
            document.getElementById('taskjobdisplay').style.height='auto';
            document.getElementById('seemore').style.display = 'none';
         }
      </script>";

      echo "<br/>";
      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfTaskjoblog->showHistory($id);

      return TRUE;
   }

}

