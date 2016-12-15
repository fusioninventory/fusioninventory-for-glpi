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
 * This file is used to manage the deploy groups.
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

/**
 * Manage the deploy groups.
 */
class PluginFusioninventoryDeployGroup extends CommonDBTM {

   /**
    * Define constant name of static group
    *
    * @var string
    */
   const STATIC_GROUP  = 'STATIC';

   /**
    * Define constant name of dynamic group
    *
    * @var string
    */
   const DYNAMIC_GROUP = 'DYNAMIC';

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = "plugin_fusioninventory_group";

   /**
    * Define the array of itemtype allowed in static groups
    *
    * @var type
    */
   protected $static_group_types = array('Computer');

   /**
    * We activate the history.
    *
    * @var boolean
    */
   public $dohistory = TRUE;



   /**
    * __contruct function used to define the 2 types of groups
    */
   public function __construct() {
      $this->grouptypes = array(
            self::STATIC_GROUP  => __('Static group', 'fusioninventory'),
            self::DYNAMIC_GROUP => __('Dynamic group', 'fusioninventory')
         );
   }



   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb=0) {
      if ($nb>1) {
         return __('Task');
      }
      return __('Groups of computers', 'fusioninventory');
   }



   /**
    * Define tabs to display on form page
    *
    * @param array $options
    * @return array containing the tabs name
    */
   function defineTabs($options=array()) {
      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }



   /**
    * Get the massive actions for this object
    *
    * @param object|null $checkitem
    * @return array list of actions
    */
   function getSpecificMassiveActions($checkitem=NULL) {
      $actions = array();
      $actions['PluginFusioninventoryDeployGroup'.MassiveAction::CLASS_ACTION_SEPARATOR.'targettask'] = __('Target a task', 'fusioninventory');
      return $actions;
   }



   /**
    * Display form related to the massive action selected
    *
    * @param object $ma MassiveAction instance
    * @return boolean
    */
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      if ($ma->getAction() == 'add_to_static_group') {
         Dropdown::show('PluginFusioninventoryDeployGroup',
                         array('condition' => "`type`='".PluginFusioninventoryDeployGroup::STATIC_GROUP."'"));
         echo Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
         return TRUE;
      }
      return parent::showMassiveActionsSubForm($ma);
   }



   /**
    * Execution code for massive action
    *
    * @param object $ma MassiveAction instance
    * @param object $item item on which execute the code
    * @param array $ids list of ID on which execute the code
    */
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {
      switch ($ma->getAction()) {

        case 'add_to_static_group' :
           if ($item->getType() == 'Computer') {
              $group_item = new PluginFusioninventoryDeployGroup_Staticdata();
              foreach ($ids as $id) {
                 //if ($group_item->can($id, UPDATE)) {
                    if (!countElementsInTable($group_item->getTable(),
                                            "`plugin_fusioninventory_deploygroups_id`='".$_POST['plugin_fusioninventory_deploygroups_id']."'
                                               AND `itemtype`='Computer'
                                               AND `items_id`='$id'")) {
                       $values = array(
                          'plugin_fusioninventory_deploygroups_id' => $_POST['plugin_fusioninventory_deploygroups_id'],
                          'itemtype' => 'Computer',
                          'items_id' => $id);
                       $group_item->add($values);
                       $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                    } else {
                       $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                    }
              //} else {
              //   $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_NORIGHT);
              //   $ma->addMessage($item->getErrorMessage(ERROR_RIGHT));
              //}
           }
        }
        parent::processMassiveActionsForOneItemtype($ma, $item, $ids);
      }
   }



   /**
    * Display title of the page
    *
    * @global array $CFG_GLPI
    */
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



   /**
    * Display form
    *
    * @param integer $ID
    * @param array $options
    * @return true
    */
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



   /**
    * Get search function for the class
    *
    * @return array
    */
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



   /**
    * Check if this group is a dynamic group or not
    *
    * @return boolean
    */
   function isDynamicGroup() {
      return ($this->fields['type'] == self::DYNAMIC_GROUP);
   }



   /**
    * Check if this group is a static group or not
    *
    * @return boolean
    */
   function isStaticGroup() {
      return ($this->fields['type'] == self::STATIC_GROUP);
   }



   /**
    * Get a specific value to display
    *
    * @param string $field
    * @param array $values
    * @param array $options
    * @return string
    */
   static function getSpecificValueToDisplay($field, $values, array $options=array()) {
      $group = new self();
      if (!is_array($values)) {
         $values = array($field => $values);
      }
      if ($field == 'type') {
         return $group->grouptypes[$values[$field]];
      }
      return '';
   }



   /**
    * Display dropdown to select dynamic of static group
    *
    * @param string $name
    * @param string $value
    * @return string
    */
   static function dropdownGroupType($name = 'type', $value = 'STATIC') {
      $group = new self();
      return Dropdown::showFromArray($name, $group->grouptypes, array('value'=>$value));
   }



   /**
    * Get specific value to select
    *
    * @param string $field
    * @param string $name
    * @param string|array $values
    * @param array $options
    * @return string
    */
   static function getSpecificValueToSelect($field, $name='', $values='', array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }

      $options['display'] = false;
      if ($field == 'type') {
         return self::dropdownGroupType($name, $values[$field]);
      }
      return parent::getSpecificValueToSelect($field, $name, $values, $options);
   }



   /**
    * Show criteria to search computers
    *
    * @param object $item PluginFusioninventoryDeployGroup instance
    * @param array $p
    */
   static function showCriteria(PluginFusioninventoryDeployGroup $item, $p) {

      $is_dynamic = $item->isDynamicGroup();
      $itemtype   = "PluginFusioninventoryComputer";
      $can_update = $item->canEdit($item->getID());

      $p['target'] = '';
      if ($can_update) {
         $p['addhidden'] = array(
             'plugin_fusioninventory_deploygroups_id' => $item->getID(),
             'id'    => $item->getID(),
             'start' => 0
         );
      }
      if ($is_dynamic) {
         $p['actionname']   = 'save';
         $p['actionvalue']  = _sx('button', 'Save');
      } else {
         $p['actionname']   = 'preview';
         $p['actionvalue']  = __('Preview');
      }
      $p['showbookmark'] = False;
      Search::showGenericSearch($itemtype, $p);
   }



   /**
    * Get targets for the group
    *
    * @param integer $groups_id id of the group
    * @return array list of computers
    */
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
    * Get search parameters as an array
    *
    * @global object $DB
    * @param object $group PluginFusioninventoryDeployGroup instance
    * @param boolean $check_post_values
    * @param boolean $getAll
    * @return array
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



   /**
    * Clean when purge a deploy group
    */
   function cleanDBOnPurge() {
      $dynamic_group = new PluginFusioninventoryDeployGroup_Dynamicdata();
      $static_group  = new PluginFusioninventoryDeployGroup_Staticdata();

      $dynamic_group->deleteByCriteria(array('plugin_fusioninventory_deploygroups_id' => $this->getID()));
      $static_group->deleteByCriteria(array('plugin_fusioninventory_deploygroups_id' => $this->getID()));
   }
}

?>