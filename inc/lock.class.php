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
 * This file is used to manage the lock fields in itemtype.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
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
 * Manage the lock fields in itemtype.
 */
class PluginFusioninventoryLock extends CommonDBTM{

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_lock';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return _n('Lock', 'Locks', $nb);
   }


   /**
    * Count number lock elements for item
    *
    * @param object $item
    * @return integer
    */
   static function countForLock(CommonGLPI $item) {
      $pfLock = new self();
      $a_data = current($pfLock->find(
            ['tablename' => $item->getTable(),
             'items_id'  => $item->fields['id']],
            [], 1));
      if (!is_array($a_data) || count($a_data) == 0) {
         return 0;
      }
      return count(importArrayFromDB($a_data['tablefields']));
   }


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      $items_id = $item->fields['id'];
      $itemtype = $item->getType();

      switch ($itemtype) {
         case 'PluginFusioninventoryConfig':
            return PluginFusioninventoryLock::getTypeName(2);

         case 'NetworkEquipment':
            $itemtype = 'networking';

         default:
            if (Session::haveRight(strtolower($itemtype), UPDATE)) {
               if ($_SESSION['glpishow_count_on_tabs']) {
                  return self::createTabEntry(PluginFusioninventoryLock::getTypeName(2),
                                           self::countForLock($item));
               }
               return PluginFusioninventoryLock::getTypeName(2);
            }
      }
      return '';
   }


   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return true
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {

      $pflock = new self();
      if ($item->getType()=='PluginFusioninventoryConfig') {
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr>";
         echo "<td>";
         $pflock->showFormItemtype('Computer');
         echo "</td>";
         echo "</tr>";

         echo "<table class='tab_cadre_fixe'>";
         echo "<tr>";
         echo "<td>";
         $pflock->showFormItemtype('Printer');
         echo "</td>";
         echo "</tr>";

         echo "<table class='tab_cadre_fixe'>";
         echo "<tr>";
         echo "<td>";
         $pflock->showFormItemtype('NetworkEquipment');
         echo "</td>";
         echo "</tr>";

         echo "</table>";
         return true;
      }
      if ($item->fields['id'] < 1) {
         $pflock->showForm(Toolbox::getItemTypeFormURL('PluginFusioninventoryLock'),
                           $item->getType());
      } else {
         $pflock->showForm(Toolbox::getItemTypeFormURL('PluginFusioninventoryLock').'?id='.
                              $item->fields['id'],
                           $item->getType(), $item->fields['id']);
      }
      return true;
   }


   /**
    * Display locks form for an item
    *
    * @todo check rights and entity
    *
    * @param string $p_target Target file.
    * @param string $p_itemtype Class name.
    * @param integer $p_items_id Line id.
    * @return true
    */
   function showForm($p_target, $p_itemtype, $p_items_id = 0) {

      $can = 0;
      $typeright = strtolower($p_itemtype);
      if ($typeright == "networkequipment") {
         $typeright = "networking";
      }
      if (Session::haveRight($typeright, UPDATE)) {
         $can = 1;
      }

      $tableName = getTableForItemType($p_itemtype);
      echo "<div width='50%'>";
      $locked = PluginFusioninventoryLock::getLockFields($tableName, $p_items_id);

      if (!count($locked)) {
         $locked = [];
      }
      $colspan = '2';
      if ($p_items_id > 0) {
         $colspan = '3';
      }

      $item = new $p_itemtype;
      if ($p_items_id == 0) {
         $item->getEmpty();
      } else {
         $item->getFromDB($p_items_id);
      }

      if (!strstr($p_target, "ajax/dropdownMassiveAction.php")) {
         echo "<br>";
         echo "<form method='post' action='".$p_target."'>";
      }

      echo "<input type='hidden' name='id' value='$p_items_id'>";
      echo "<input type='hidden' name='type' value='$p_itemtype'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='4'>".__('FusionInventory', 'fusioninventory')."</th></tr>";

      echo "<tr><th>"._n('Field', 'Fields', 2)."</th>";
      if ($p_items_id != '0') {
         echo "<th>".__('Values GLPI', 'fusioninventory')."</th>";
         echo "<th>".__('Values of last inventory', 'fusioninventory')."</th>";
      }
      echo "<th>"._n('Lock', 'Locks', 2, 'fusioninventory')."</th>";
      echo "</tr>";

      $checked = false;
      $a_exclude = $this->excludeFields();
      $serialized = $this->getSerializedInventoryArray($p_itemtype, $p_items_id);
      $options = Search::getOptions($p_itemtype);
      foreach ($item->fields as $key=>$val) {
         $name = "";
         $key_source = $key;
         if (!in_array($key, $a_exclude)) {
            if (in_array($key, $locked)) {
               $checked = true;
            } else {
               $checked = false;
            }

            // Get name of field
            $num = Search::getOptionNumber($p_itemtype, $key);
            if (isset($options[$num]['name'])) {
               $name = $options[$num]['name'];
            } else {
               // Get name by search in linkfields
               foreach ($options as $opt) {
                  if (isset($opt['linkfield']) && $opt['linkfield'] == $key) {
                     $name = $opt['name'];
                     break;
                  }
               }
            }
            $css_glpi_value = '';
            if (isset($serialized[$key]) && $val != $serialized[$key]) {
               $css_glpi_value = "class='tab_bg_1_2'";
            }
            // Get value of field
            $val = $this->getValueForKey($val, $key);
            echo "<tr class='tab_bg_1'>";
            $table = getTableNameForForeignKeyField($key);
            if ($name == "" && $table != "") {
               $linkItemtype = getItemTypeForTable($table);
               $class = new $linkItemtype();
               $name = $class->getTypeName();
            }
            echo "<td>".$name."</td>";
            if ($p_items_id != '0') {
               // Current value of GLPI
               echo "<td ".$css_glpi_value.">".$val."</td>";
               // Value of last inventory
               echo "<td>";
               if (isset($serialized[$key_source])) {
                  echo  $this->getValueForKey($serialized[$key_source], $key);
               }
               echo "</td>";
            }
            echo "<td align='center'>";
            Html::showCheckbox(['name'    => "lockfield_fusioninventory[$key_source]",
                                     'checked' => $checked]);
            echo "</td>";
            echo "</tr>";
         }
      }

      if ($p_items_id == '0') {
         // add option selection for add theses lock filed or remove them
         echo "<tr>";
         echo "<th colspan='2'>".__('Job', 'fusioninventory')."</th>";
         echo "<tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Add locks', 'fusioninventory')."</td>";
         echo "<td align='center'><input type='radio' name='actionlock' value='addLock' ".
                 "checked/></td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Delete locks', 'fusioninventory')."</td>";
         echo "<td align='center'><input type='radio' name='actionlock' value='deleteLock' /></td>";
         echo "</tr>";
      }
      if ($can == '1') {
         echo "<tr class='tab_bg_2'>";
         echo "<td align='center' colspan='".($colspan + 1)."'>";
         echo "<input class='submit' type='submit' name='unlock_field_fusioninventory'
                         value='" . __('Update') . "'>";
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      if (!strstr($p_target, "ajax/dropdownMassiveAction.php")) {
         Html::closeForm();
      }
      echo "</div>";
      return true;
   }


   /**
    * Display lock form for an itemtype
    *
    * @param string $itemtype
    */
   function showFormItemtype($itemtype, $start_form = true, $show_button = true) {

      $can = 0;
      $typeright = strtolower($itemtype);
      if ($typeright == "networkequipment") {
         $typeright = "networking";
      }
      if (Session::haveRight($typeright, UPDATE)) {
         $can = 1;
      }

      $tableName = getTableForItemType($itemtype);
      echo "<div width='50%'>";
      $locked = PluginFusioninventoryLock::getLockFields($tableName, 0);
      if (!count($locked)) {
         $locked = [];
      }
      $colspan = '2';

      $item = new $itemtype;
      $item->getEmpty();

      if ($start_form) {
         echo "<form method='post' action='".PluginFusioninventoryLock::getFormURL()."'>";
         echo "<input type='hidden' name='id' value='0'>";
         echo "<input type='hidden' name='type' value='$itemtype'>";
      }
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='2'>".$item->getTypeName(1)."</th>";
      echo "</tr>";

      echo "<tr><th>"._n('Field', 'Fields', 2)."</th>";
      echo "<th>"._n('Lock', 'Locks', 2, 'fusioninventory')."</th>";
      echo "</tr>";

      $checked = false;
      $a_exclude = $this->excludeFields();
      $options = Search::getOptions($itemtype);
      foreach ($item->fields as $key=>$val) {
         $name = "";
         $key_source = $key;
         if (!in_array($key, $a_exclude)) {
            if (in_array($key, $locked)) {
               $checked = true;
            } else {
               $checked = false;
            }
            // Get name of field
            $num = Search::getOptionNumber($itemtype, $key);
            if (isset($options[$num]['name'])) {
               $name = $options[$num]['name'];
            } else {
               //Get name by search in linkfields
               foreach ($options as $opt) {
                  if (isset($opt['linkfield']) && $opt['linkfield'] == $key) {
                     $name = $opt['name'];
                     break;
                  }
               }
            }
            // Get value of field
            $val = $this->getValueForKey($val, $key);
            echo "<tr class='tab_bg_1'>";
            $table = getTableNameForForeignKeyField($key);
            if ($name == "" && $table != "") {
               $linkItemtype = getItemTypeForTable($table);
               $class = new $linkItemtype();
               $name = $class->getTypeName();
            }
            echo "<td>".$name."</td>";
            echo "<td align='center'>";
            Html::showCheckbox(['name'    => "lockfield_fusioninventory[$key_source]",
                                     'checked' => $checked]);
            echo "</td>";
            echo "</tr>";
         }
      }

      if ($can == '1' && $show_button) {
         echo "<tr class='tab_bg_2'>";
         echo "<td align='center' colspan='".($colspan + 1)."'>";
         echo Html::submit(__('Update'), ['name' => 'unlock_field_fusioninventory']);
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      Html::closeForm();
      echo "</div>";
   }


   /**
   * Clean locks for an asset
   *
   * @param string $itemtype asset type
   * @param int    $items_id asset ID
   * @return Nothing
   * @since 0.90
   */
   static function cleanForAsset($itemtype, $items_id) {
      global $DB;
      $DB->delete(
         'glpi_plugin_fusioninventory_locks', [
            'tablename' => getTableForItemType($itemtype),
            'items_id'  => $items_id
         ]
      );
   }


   /**
    * Unlock a field for a record.
    *
    * @todo check rights and entity
    *
    * @param string $p_table Table name.
    * @param integer $p_items_id Line id.
    * @param string $p_fieldToDel field to unlock.
    */
   static function deleteInLockArray($p_table, $p_items_id, $p_fieldToDel) {

      $pfLock = new PluginFusioninventoryLock();
      $fieldsToLock = PluginFusioninventoryLock::getLockFields($p_table, $p_items_id);
      if (count($fieldsToLock)) {
         $fieldToDel=array_search($p_fieldToDel, $fieldsToLock);
         if (isset($fieldsToLock[$fieldToDel])) {
            unset ($fieldsToLock[$fieldToDel]);
         }
         if (count($fieldsToLock)) {       // there are still locks
            $fieldsToLock=array_values($fieldsToLock);

            $a_lines = $pfLock->find(['tablename' => $p_table, 'items_id' => $p_items_id]);
            $a_line = current($a_lines);
            $pfLock->getFromDB($a_line['id']);
            $input = [];
            $input['id'] = $pfLock->fields['id'];
            $input['tablefields'] = exportArrayToDB($fieldsToLock);
            $pfLock->update($input);
         } else {                            // no locks any more
            $a_lines = $pfLock->find(['tablename' => $p_table, 'items_id' => $p_items_id]);
            $a_line = current($a_lines);
            $pfLock->getFromDB($a_line['id']);
            $pfLock->delete($pfLock->fields);
         }
      }
   }


   /**
    * Unlock a field for all records.
    *
    * @todo check rights and entity
    *
    * @global object $DB
    * @param string $p_table Table name.
    * @param string $p_fieldToDel field to unlock.
    */
   static function deleteInAllLockArray($p_table, $p_fieldToDel) {
      global $DB;

      $query = "SELECT `items_id`
                FROM `glpi_plugin_fusioninventory_locks`
                WHERE `tablename`='".$p_table."'
                      AND `tablefields` LIKE '%".$p_fieldToDel."%';";
      $result = $DB->query($query);
      while ($data=$DB->fetchArray($result)) {
         // TODO improve the lock deletion by transmiting the old locked fields to the
         // deletion function
         PluginFusioninventoryLock::deleteInLockArray($p_table, $data['items_id'], $p_fieldToDel);
      }
   }


   /**
    * Set lock fields for a record.
    *
    * @todo check rights and entity
    *
    * @global object $DB
    * @param string $p_itemtype Table id.
    * @param integer $p_items_id Line id.
    * @param array $p_fieldsToLock Array of fields to lock.
    * @param string $massiveaction
    *
    * @return boolean
    */
   static function setLockArray($p_itemtype, $p_items_id, $p_fieldsToLock, $massiveaction = '') {
      global $DB;

      $success = false;

      $pfl = new PluginFusioninventoryLock();

      $tableName = getTableForItemType($p_itemtype);
      $result = PluginFusioninventoryLock::getLock($tableName, $p_items_id);
      if ($DB->numrows($result)) {
         $a_lines = $pfl->find(['tablename' => $tableName, 'items_id' => $p_items_id]);
         $a_line = current($a_lines);
         $pfl->getFromDB($a_line['id']);
         if ($massiveaction == 'addLock') {
            $a_lockfieldsDB = importArrayFromDB($pfl->fields['tablefields']);
            foreach ($p_fieldsToLock as $fieldtoadd) {
               if (!in_array($fieldtoadd, $a_lockfieldsDB)) {
                  $a_lockfieldsDB[] = $fieldtoadd;
               }
            }
            $pfl->fields['tablefields'] = exportArrayToDB($a_lockfieldsDB);
            $success = $pfl->update($pfl->fields);
         } else if ($massiveaction == 'deleteLock') {
            $a_lockfieldsDB = importArrayFromDB($pfl->fields['tablefields']);
            foreach ($p_fieldsToLock as $fieldtoadd) {
               if (in_array($fieldtoadd, $a_lockfieldsDB)) {
                  $key = array_search($fieldtoadd, $a_lockfieldsDB);
                  unset($a_lockfieldsDB[$key]);
               }
            }
            $pfl->fields['tablefields'] = exportArrayToDB($a_lockfieldsDB);
            $success = $pfl->update($pfl->fields);
         } else {
            if (count($p_fieldsToLock)) {       // old locks --> new locks
               $pfl->fields['tablefields'] = exportArrayToDB($p_fieldsToLock);
               $success = $pfl->update($pfl->fields);
            } else {                            // old locks --> no locks any more
               $success = $pfl->delete($pfl->fields);
            }
         }
      } else if (count($p_fieldsToLock)) {    // no locks --> new locks
         $input = [];
         $input['tablename']     = $tableName;
         $input['items_id']      = $p_items_id;
         $input['tablefields']   = exportArrayToDB($p_fieldsToLock);
         $success = (bool) $pfl->add($input);
      }

      return $success;
   }


   /**
    * Add lock fields for a record.
    *
    * @todo check rights and entity
    *
    * @global object $DB
    * @param string $p_itemtype Table id.
    * @param integer $p_items_id Line id.
    * @param array $p_fieldsToLock Array of fields to lock.
    */
   static function addLocks($p_itemtype, $p_items_id, $p_fieldsToLock) {
      global $DB;

      $tableName = getTableForItemType($p_itemtype);

      $pfl = new PluginFusioninventoryLock();
      $a_exclude = $pfl->excludeFields();
      $p_fieldsToLock = array_diff($p_fieldsToLock, $a_exclude);

      $result = PluginFusioninventoryLock::getLock($tableName, $p_items_id);
      if ($DB->numrows($result)) {
         $row = $DB->fetchAssoc($result);
         $lockedFields = importArrayFromDB($row['tablefields']);
         if (count(array_diff($p_fieldsToLock, $lockedFields))) { // old locks --> new locks
            $p_fieldsToLock = array_merge($p_fieldsToLock, $lockedFields);

            $a_lines = $pfl->find(['tablename' => $tableName, 'items_id' => $p_items_id]);
            $a_line = current($a_lines);
            $pfl->getFromDB($a_line['id']);
            $pfl->fields['tablefields'] = exportArrayToDB($p_fieldsToLock);
            $pfl->update($pfl->fields);
         }
      } else if (count($p_fieldsToLock)) {    // no locks --> new locks

         $input = [];
         $input['tablename']     = $tableName;
         $input['items_id']      = $p_items_id;
         $input['tablefields']   = exportArrayToDB($p_fieldsToLock);
         $pfl->add($input);
      }
   }


   /**
    * Get lock fields for a record.
    *
    * @todo check rights and entity
    *
    * @global object $DB
    * @param string $p_table Table name.
    * @param integer $p_items_id Line id.
    * @return object
    */
   static function getLock($p_table, $p_items_id) {
      global $DB;

      $query = "SELECT `id`, `tablefields`
                FROM `glpi_plugin_fusioninventory_locks`
                WHERE `tablename`='".$p_table."'
                      AND `items_id`='".$p_items_id."';";
      $result = $DB->query($query);
      return $result;
   }


   /**
    * Get lock fields for a record.
    *
    * @todo check rights
    *
    * @global object $DB
    * @param string $p_table Table name.
    * @param integer $p_items_id Line id.
    * @return array list of locked fields
    */
   static function getLockFields($p_table, $p_items_id) {
      global $DB;

      $db_lock = $DB->fetchAssoc(PluginFusioninventoryLock::getLock($p_table, $p_items_id));
      if ($db_lock !== null) {
         $lock_fields = $db_lock["tablefields"];
         $lock = importArrayFromDB($lock_fields);
      } else {
         $lock = [];
      }

      if ($p_items_id != 0) {
         $db_lock = $DB->fetchAssoc(PluginFusioninventoryLock::getLock($p_table, 0));
         if ($db_lock !== null) {
            $lock_fields = $db_lock["tablefields"];
            $lockItemtype = importArrayFromDB($lock_fields);
            $lock = array_merge($lock, $lockItemtype);
         }
      }

      return $lock;
   }


   /**
    * convert an array resulting from many form checks (0=>on 2=>on 5=>on ...)
    * into a classical array(0=>0 1=>2 2=>5 ...)
    *
    * @param array $p_checksArray checkbox array from form
    * @return array
    */
   static function exportChecksToArray($p_checksArray) {
      $array = [];
      foreach ($p_checksArray as $key => $value) {
         if ($value > 0 || $value == "on") {
            array_push($array, $key);
         }
      }
      return $array;
   }


   /**
    * Manage list of fields to exclude for lock
    *
    * @return array list of fields to exclude
    */
   function excludeFields() {
      $exclude = [];
      $exclude[] = "id";
      $exclude[] = "entities_id";
      $exclude[] = "is_recursive";
      $exclude[] = "date_mod";
      $exclude[] = "date_creation";
      $exclude[] = "is_deleted";
      $exclude[] = "is_dynamic";
      $exclude[] = "is_template";
      $exclude[] = "template_name";
      $exclude[] = "comment";
      $exclude[] = "ticket_tco";
      return $exclude;
   }


   /**
    * Delete locks fields and get from lib value from last inventory
    *
    * @param object $item
    */
   static function deleteLock($item) {
      global $DB;

      if ($item->fields['items_id'] == 0) {
         return;
      }
      $pfLock = new PluginFusioninventoryLock();

      $itemtype = getItemTypeForTable($item->fields['tablename']);
      $items_id = $item->fields['items_id'];

      $a_fieldList = [];
      if ($item->fields['tablefields'] == $item->input['tablefields']) {
         $a_fieldList = importArrayFromDB($item->fields['tablefields']);
      } else {
         $a_fieldListTemp = importArrayFromDB($item->fields['tablefields']);
         $a_inputList = importArrayFromDB($item->input['tablefields']);
         $a_diff = array_diff($a_fieldListTemp, $a_inputList);
         $a_fieldList = [];
         foreach ($a_diff as $value) {
            if (in_array($value, $a_fieldListTemp)) {
               $a_fieldList[] = $value;
            }
         }
      }

      // load general lock configuration
      $generalLocks = PluginFusioninventoryLock::getLockFields($item->fields['tablename'], 0);
      $a_fieldList = array_unique(array_merge($a_fieldList, $generalLocks));

      //delete all lock case (no more lock)
      if (!isset($item->updates)) {
         $a_fieldList = [];
      }

      $item_device = new $itemtype();
      $item_device->getFromDB($items_id);
      $a_serialized = $pfLock->getSerializedInventoryArray($itemtype, $items_id);
      foreach ($a_serialized as $key=>$value) {
         if (!in_array($key, $a_fieldList)) {
            $item_device->fields[$key] = $value;
         }
      }
      $exclude = $pfLock->excludeFields();
      foreach ($exclude as $key) {
         if (isset($item_device->fields[$key])
                 && $key != 'id') {
            unset($item_device->fields[$key]);
         }
      }
      $_SESSION['glpi_fusionionventory_nolock'] = true;
      $item_device->update($item_device->fields);
      unset($_SESSION['glpi_fusionionventory_nolock']);
   }


   /**
    * Import OCS locks
    *
    * @global object $DB
    */
   function importFromOcs() {
      global $DB;

      if ($DB->tableExists('glpi_ocslinks')) {
         $sql = "SELECT * FROM `glpi_ocslinks`";
         $result=$DB->query($sql);
         while ($data=$DB->fetchArray($result)) {
            $a_ocslocks = importArrayFromDB($data['computer_update']);
            $a_fields = [];
            foreach ($a_ocslocks as $field) {
               if (!strstr($field, "_version")
                     AND $field != "date_mod") {

                  $a_fields[] = $field;
               }
            }
            if (count($a_fields) > 0) {
               $this->addLocks("Computer", $data['computers_id'], $a_fields);
            }
         }
      }
   }


   /**
    * Get serialized inventory and convert to array
    *
    * @param string $itemtype
    * @param integer $items_id
    * @return array
    */
   function getSerializedInventoryArray($itemtype, $items_id) {

      $item_extend = new PluginFusioninventoryLock();
      if ($itemtype == 'Computer') {
         $item_extend = new PluginFusioninventoryInventoryComputerComputer();
      } else if ($itemtype == 'NetworkEquipment') {
         $item_extend = new PluginFusioninventoryNetworkEquipment;
      } else if ($itemtype == 'Printer') {
         $item_extend = new PluginFusioninventoryPrinter();
      }

      if ($item_extend->getType() != 'PluginFusioninventoryLock') {
         // Get device info + field 'serialized_inventory'
         $a_lists = $item_extend->find([getForeignKeyFieldForItemType($itemtype) => $items_id], [], 1);
         if (count($a_lists) == 1) {
            $a_list = current($a_lists);
            if (!empty($a_list['serialized_inventory'])) {
               $serialized = unserialize(gzuncompress($a_list['serialized_inventory']));
               return $serialized[$itemtype];
            }
         }
      }
      return [];
   }


   /**
    * Get value for key
    *
    * @param string $val
    * @param string $key
    * @return string
    */
   function getValueForKey($val, $key) {
      if ((strstr($key, "_id")
              || ($key == 'is_ocs_import'))
         AND $val == '0') {

         $val = "";
      }

      $table = getTableNameForForeignKeyField($key);
      if ($table != "") {
         $linkItemtype = getItemTypeForTable($table);
         $class = new $linkItemtype();
         if (($val == "0") OR ($val == "")) {
            $val = "";
         } else {
            $class->getFromDB($val);
            $val = $class->getName();
         }
      }
      return $val;
   }


   /**
    * Display lock icon in main item form
    *
    * @param string $itemtype
    */
   static function showLockIcon($itemtype) {
      if (isset($_GET['id'])
              && $_GET['id'] > 0) {
         $pfLock = new self();
         $a_locks = $pfLock->getLockFields(getTableForItemType($itemtype), $_GET['id']);
         foreach ($a_locks as $field) {
            $js = '$("[name='.$field.']").closest("td").prev().toggleClass("lockfield", true);';
            echo Html::scriptBlock($js);
         }
      }
   }


   /**
    * Display form related to the massive action selected
    *
    * @param object $ma MassiveAction instance
    * @return boolean
    */
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      if ($ma->getAction() == 'manage_locks') {
         //detect itemtype
         $itemtype = str_replace("massform", "", $_POST['container']);

         $pfil = new self;
         $pfil->showForm($_SERVER["PHP_SELF"], $itemtype);
         return true;
      }
      return false;
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

      $itemtype = $item->getType();

      switch ($ma->getAction()) {

         case "manage_locks":
            if ($itemtype == "NetworkEquipment"
                || $itemtype == "Printer"
                || $itemtype == "Computer") {

               foreach ($ids as $key) {
                  if (isset($_POST["lockfield_fusioninventory"])
                      && count($_POST["lockfield_fusioninventory"])) {
                     $tab=PluginFusioninventoryLock::exportChecksToArray($_POST["lockfield_fusioninventory"]);

                     //lock current item
                     if (PluginFusioninventoryLock::setLockArray($_POST['type'],
                                                             $key,
                                                             $tab,
                                                             $_POST['actionlock'])) {

                        //set action massive ok for this item
                        $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                     } else {
                        // KO
                        $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                     }

                  }
               }
            }
            break;

      }
   }


   /**
    * Say if the field is locked
    *
    * @param array $a_lockable list of fields locked
    * @param string $field field to check
    * @return boolean
    */
   static function isFieldLocked($a_lockable, $field) {
      return in_array($field, $a_lockable);
   }


   static function showLocksForAnItem(CommonDBTM $item) {
      $pflock = new self();
      $itemtype = $item->getType();
      if ($itemtype::canUpdate()) {
         if ($item->getID() < 1) {
            $pflock->showForm(Toolbox::getItemTypeFormURL(__CLASS__),
                              $item->getType());
         } else {
            $pflock->showForm(Toolbox::getItemTypeFormURL(__CLASS__).'?id='.
                              $item->getID(), $item->getType(), $item->getID());
         }
      }
      return true;
   }
}
