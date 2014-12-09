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
   @author    Alexandre Delaunay
   @co-author
   @copyright Copyright (c) 2010-2014 FusionInventory team
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

class PluginFusioninventoryDeployGroup extends CommonDBTM {

   //Group type definition
   const STATIC_GROUP  = 'STATIC';
   const DYNAMIC_GROUP = 'DYNAMIC';

   static $rightname = "plugin_fusioninventory_group";

   protected $static_group_types = array('Computer');

   public $dohistory = TRUE;

   static function getTypeName($nb=0) {

      if ($nb>1) {
         return __('Task');
      }
      return __('Groups of computers', 'fusioninventory');
   }

   //static function canCreate() {
   //   return true;
   //}

   public function __construct() {
      $this->grouptypes = array(
            self::STATIC_GROUP  => __('Static group', 'fusioninventory'),
            self::DYNAMIC_GROUP => __('Dynamic group', 'fusioninventory')
         );
   }


   function defineTabs($options=array()) {
      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }



   /**
    * Massive action ()
    */
   function getSpecificMassiveActions($checkitem=NULL) {

      $actions = array();
      $actions['PluginFusioninventoryDeployGroup'.MassiveAction::CLASS_ACTION_SEPARATOR.'targettask'] = __('Target a task', 'fusioninventory');
      return $actions;
   }



   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      global $CFG_GLPI;

      switch ($ma->getAction()) {
//         case 'add_to_static_group':
//            Dropdown::show('PluginFusioninventoryDeployGroup',
//                            array('condition' => "`type`='".PluginFusioninventoryDeployGroup::STATIC_GROUP."'"));
//            echo Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
//            return true;

         case 'targettask' :
            echo "<table class='tab_cadre' width='600'>";
            echo "<tr>";
            echo "<td>";
            echo __('Task', 'fusioninventory')."&nbsp;:";
            echo "</td>";
            echo "<td>";
            $rand = mt_rand();
            Dropdown::show('PluginFusioninventoryTask', array(
                  'name'      => "tasks_id",
                  'condition' => "is_active = 0",
                  'toupdate'  => array(
                        'value_fieldname' => "id",
                        'to_update'       => "dropdown_packages_id$rand",
                        'url'             => $CFG_GLPI["root_doc"].
                                                "/plugins/fusioninventory/ajax/dropdown_taskjob.php"
               )
            ));
            echo "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>";
            echo __('Package', 'fusioninventory')."&nbsp;:";
            echo "</td>";
            echo "<td>";
            Dropdown::show('PluginFusioninventoryDeployPackage', array(
                     'name' => "packages_id",
                     'rand' => $rand
            ));
            echo "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td colspan='2'>";
            echo "<input type='checkbox' name='separate_jobs' value='1'/>&nbsp;";
            echo __('Create a job for each group', 'fusioninventory');
            echo "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td colspan='2' align='center'>";
            echo Html::submit(__('Post'),
                              array('name' => 'massiveaction'));
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            return true;

      }
      return parent::showMassiveActionsSubForm($ma);
   }



   /**
    * @since version 0.85
    *
    * @see CommonDBTM::processMassiveActionsForOneItemtype()
   **/
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {
      global $DB;

      switch ($ma->getAction()) {

         case 'plugin_fusioninventory_deploy_target_task' :
            $taskjob = new PluginFusioninventoryDeployTaskjob();
            $tasks = array();

            //get old datas
            $oldjobs = $taskjob->find("plugin_fusioninventory_tasks_id = '".$ma->POST['tasks_id']."'");

            // TODO: rename 'tasks' variables into 'job'
            // The 'separate jobs' option allows to create a taskjob for each computer
            // (I can't see the point but it may be
            // usefull for some people ... even if it creates 500 jobs for just a
            // single deployment package targetted ... i prefer not to comment
            // furthermore :) ).

            if (array_key_exists('separate_jobs', $data)) {
               foreach ($ids as $key => $val) {
                  $task = new StdClass;
                  $task->package_id = $ma->POST['packages_id'];
                  $task->method = 'deployinstall';
                  $task->retry_nb = 3;
                  $task->retry_time = 0;
                  //add new datas
                  $task->action = array(array('PluginFusioninventoryDeployGroup' => $key));
                  $tasks[] = $task;
               }
            } else {
               $task = new StdClass;
               $task->package_id = $ma->POST['packages_id'];
               $task->method = 'deployinstall';
               $task->retry_nb = 3;
               $task->retry_time = 0;
               $task->action = array();
               //add new datas
               foreach ($ids as $key => $val) {
                  $task->action[] = array('PluginFusioninventoryDeployGroup' => $key);
               }
               $tasks[] = $task;

            }
            if ($data['tasks_id'] == 0) {
               $pfTask = new PluginFusioninventoryTask();
               $input = array();
               $input['name'] = 'Deploy';
               $input['communication'] = 'push';
               $input['date_scheduled'] = date("Y-m-d H:i:s");
               $data['tasks_id'] = $pfTask->add($input);
            }
            $params = array(
               'tasks_id' => $data['tasks_id'],
               'tasks'    => json_encode($tasks)
            );
            $taskjob->saveDatas($params);

            //reimport old jobs
            foreach($oldjobs as $job) {
               $sql = "INSERT INTO glpi_plugin_fusioninventory_taskjobs (";
               foreach ($job as $key => $val) {
                  $sql .= "`$key`, ";
               }
               $sql = substr($sql, 0, -2).") VALUES (";
               foreach ($job as $val) {
                  if (is_numeric($val) && (int)$val == $val) {
                     $sql .= "$val, ";
                  } else {
                     $sql .= "'$val', ";
                  }
               }
               $sql = substr($sql, 0, -2).");";

               $DB->query($sql);
            }
            break;


//         case 'add_to_static_group' :
//            if ($item->getType() == 'Computer') {
//               $group_item = new PluginFusioninventoryDeployGroup_Staticdata();
//               foreach ($ids as $id) {
//                  //if ($group_item->can($id, UPDATE)) {
//                     if (!countElementsInTable($group_item->getTable(),
//                                             "`plugin_fusioninventory_deploygroups_id`='".$_POST['plugin_fusioninventory_deploygroups_id']."'
//                                                AND `itemtype`='Computer'
//                                                AND `items_id`='$id'")) {
//                        $values = array(
//                           'plugin_fusioninventory_deploygroups_id' => $_POST['plugin_fusioninventory_deploygroups_id'],
//                           'itemtype' => 'Computer',
//                           'items_id' => $id);
//                        $group_item->add($values);
//                        $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
//                     } else {
//                        $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
//                     }
//               //} else {
//               //   $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_NORIGHT);
//               //   $ma->addMessage($item->getErrorMessage(ERROR_RIGHT));
//               //}
//            }
//         }
//         parent::processMassiveActionsForOneItemtype($ma, $item, $ids);
      }
   }



   function showMenu($options=array())  {
      $this->displaylist = false;
      $this->fields['id'] = -1;
      $this->showList();
   }

   function title() {
      global $CFG_GLPI;

      $buttons = array();
      $title = self::getTypeName();

      if ($this->canCreate()) {
         $buttons["group.form.php?new=1"] = __('Add group', 'fusioninventory');
         $title = "";
      }

      Html::displayTitle($CFG_GLPI['root_doc']."/plugins/fusinvdeploy/pics/menu_group.png",
                         $title, $title, $buttons);
   }

   function showForm($ID, $options = array()) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."&nbsp;:</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'name', array('size' => 40));
      echo "</td>";

      echo "<td rowspan='2'>".__('Comments')."&nbsp;:</td>";
      echo "<td rowspan='2' align='center'>";
      echo "<textarea cols='40' rows='6' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Type')."&nbsp;:</td>";
      echo "<td align='center'>";
      self::dropdownGroupType('type', $this->fields['type']);
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      return TRUE;
   }

   function getSearchOptions() {

      $tab = array();

      $tab['common'] = self::getTypeName();


      $tab[1]['table']          = $this->getTable();
      $tab[1]['field']          = 'name';
      $tab[1]['linkfield']      = '';
      $tab[1]['name']           = __('Name');
      $tab[1]['datatype']       = 'itemlink';
      $tab[1]['massiveaction']   = false;

      $tab[2]['table']           = $this->getTable();
      $tab[2]['field']           = 'type';
      $tab[2]['name']            = __('Type');
      $tab[2]['datatype']        = 'specific';
      $tab[2]['massiveaction']   = false;
      $tab[2]['searchtype']      = 'equals';

      return $tab;
   }

   function isDynamicGroup() {
      return ($this->fields['type'] == self::DYNAMIC_GROUP);
   }

   function isStaticGroup() {
      return ($this->fields['type'] == self::STATIC_GROUP);
   }

   static function getSpecificValueToDisplay($field, $values, array $options=array()) {
      $group = new self();
      if (!is_array($values)) {
         $values = array($field => $values);
      }
      switch ($field) {
         case 'type' :
            return $group->grouptypes[$values[$field]];
      }
      return '';
   }

   /**
   * Display dropdown to select dynamic of static group
   */
   static function dropdownGroupType($name = 'type', $value = 'STATIC') {
      $group = new self();
      return Dropdown::showFromArray($name, $group->grouptypes, array('value'=>$value));
   }

   static function getSpecificValueToSelect($field, $name='', $values='', array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }

      $options['display'] = false;
      switch ($field) {
         case 'type':
            return self::dropdownGroupType($name, $values[$field]);
         default:
            break;
      }
      return parent::getSpecificValueToSelect($field, $name, $values, $options);
   }

   /**
    * Displays tab content
    * This function adapted from Search::showGenericSearch with controls removed
    * @param  bool $formcontrol : display form buttons
    * @return nothing, displays a seach form
    */
   static function showCriteria(PluginFusioninventoryDeployGroup $item, $formcontrol = true, $p) {
      global $CFG_GLPI, $DB;

      $is_dynamic = $item->isDynamicGroup();
      $itemtype   = "PluginFusioninventoryComputer";
      $can_update = $item->canEdit($item->getID());

      if ($can_update) {
         //show generic search form (duplicated from Search class)
         echo "<form name='group_search_form' method='POST'>";
         echo "<input type='hidden' name='plugin_fusioninventory_deploygroups_id' value='".$item->getID()."'>";
         echo "<input type='hidden' name='id' value='".$item->getID()."'>";

         // add tow hidden fields to permit delete of (meta)criteria
         echo "<input type='hidden' name='criteria' value=''>";
         echo "<input type='hidden' name='metacriteria' value=''>";
      }

      echo "<div class='tabs_criteria'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th>"._n('Criterion', 'Criteria', 2)."</th></tr>";
      echo "<tr><td>";

      echo "<div id='searchcriteria'>";
      $nb_criteria = count($p['criteria']);
      if ($nb_criteria == 0) {
         $nb_criteria++;
      }
      $nb_meta_criteria = (isset($p['metacriteria'])?count($p['metacriteria']):0);
      $nbsearchcountvar = 'nbcriteria'.strtolower($itemtype).mt_rand();
      $nbmetasearchcountvar = 'nbmetacriteria'.strtolower($itemtype).mt_rand();
      $searchcriteriatableid = 'criteriatable'.strtolower($itemtype).mt_rand();
      // init criteria count
      $js = "var $nbsearchcountvar=".$nb_criteria.";";
      $js .= "var $nbmetasearchcountvar=".$nb_meta_criteria.";";
      echo Html::scriptBlock($js);

      echo "<table class='tab_format' id='$searchcriteriatableid'>";

      // Displays normal search parameters
      for ($i=0 ; $i<$nb_criteria ; $i++) {
         $_POST['itemtype'] = $itemtype;
         $_POST['num'] = $i ;
         include(GLPI_ROOT.'/ajax/searchrow.php');
      }

      $metanames = array();
      $linked =  Search::getMetaItemtypeAvailable('Computer');

      if (is_array($linked) && (count($linked) > 0)) {
         for ($i=0 ; $i<$nb_meta_criteria ; $i++) {

            $_POST['itemtype'] = $itemtype;
            $_POST['num'] = $i ;
            include(GLPI_ROOT.'/ajax/searchmetarow.php');
         }
      }
      echo "</table>\n";
      echo "</td>";
      echo "</tr>";
      echo "</table>\n";

      // For dropdown
      echo "<input type='hidden' name='itemtype' value='$itemtype'>";

      if ($can_update) {
         // add new button to search form (to store and preview)
         echo "<div class='center'>";

         if ($is_dynamic) {
            echo "<input type='submit' value=\" "._sx('button', 'Save').
               " \" class='submit' name='save'>";
         } else {
            echo "<input type='submit' value=\" ".__('Preview')." \" class='submit' name='preview'>";
         }
         echo "</div>";
      }

      echo "</td></tr></table>";
      echo "</div>";

      //restore search session variables
      //$_SESSION['glpisearch'] = $glpisearch_session;

      // Reset to start when submit new search
      echo "<input type='hidden' name='start' value='0'>";

      Html::closeForm();

      //clean with javascript search control
      /*
      $clean_script = "jQuery( document ).ready(function( $ ) {
         $('#parent_criteria img').remove();
         $('.tabs_criteria img[name=img_deleted').remove();
      });";
      echo Html::scriptBlock($clean_script);*/
   }

   static function getTargetsForGroup($groups_id) {
      $group = new self();
      $group->getFromDB($groups_id);

      $results = array();
      if ($group->isStaticGroup()) {
         $staticgroup = new PluginFusioninventoryDeployGroup_Staticdata();
         foreach ($staticgroup->find("`plugin_fusioninventory_deploygroups_id`='$groups_id'
                                AND `itemtype`='Computer'") as $tmpgroup) {
            $results[$tmpgroup['items_id']] = $tmpgroup['items_id'];
         }
      } else {
         $results = PluginFusioninventoryDeployGroup_Dynamicdata::getTargetsByGroup($group);
      }
      return $results;
   }

   /**
   *
   */
   static function getSearchParamsAsAnArray(PluginFusioninventoryDeployGroup $group, $check_post_values=FALSE, $getAll=FALSE) {
      global $DB;
      $computers_params = array();

      unset($_SESSION['glpisearch']['PluginFusioninventoryComputer']);
      //Check criteria from DB
      if (!$check_post_values) {
         if ($group->fields['type'] == PluginFusioninventoryDeployGroup::DYNAMIC_GROUP) {
            $query = "SELECT `fields_array`
                     FROM `glpi_plugin_fusioninventory_deploygroups_dynamicdatas`
                     WHERE `plugin_fusioninventory_deploygroups_id`='".$group->getID()."'";
            $result = $DB->query($query);
            if ($DB->numrows($result) > 0) {
               $fields_array     = $DB->result($result, 0, 'fields_array');
               $computers_params = unserialize($fields_array);
            }
         }
      } else {
         $computers_params = $_GET;
      }
      if ($getAll) {
         $computers_params['export_all'] = true;
      }
      return Search::manageParams('PluginFusioninventoryComputer', $computers_params);
   }



   function cleanDBOnPurge() {
      $dynamic_group = new PluginFusioninventoryDeployGroup_Dynamicdata();
      $static_group  = new PluginFusioninventoryDeployGroup_Staticdata();
      $dynamic_group->deleteByCriteria(array('plugin_fusioninventory_deploygroups_id' => $this->getID()));
      $static_group->deleteByCriteria(array('plugin_fusioninventory_deploygroups_id' => $this->getID()));
   }
}
?>