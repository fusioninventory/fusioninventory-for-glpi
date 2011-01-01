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
// Original Author of file: MAZZONI Vincent
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/// Plugin FusionInventory lock class
class PluginFusioninventoryLock extends CommonDBTM{

   /**
    * Constructor
   **/
   function __construct () {
      $this->table="glpi_plugin_fusioninventory_locks";
   }


   /**
    * Show locks form.
    *
    *@param $p_target Target file.
    *@param $p_itemtype Class name.
    *@param $p_items_id Line id.
    *TODO:  check rights and entity
    *
    *@return nothing (print the form)
    **/
   function showForm($p_target, $p_itemtype, $p_items_id) {
      global $DB, $LANG, $SEARCH_OPTION;

      $tableName = getTableForItemType($p_itemtype);
      echo "<div width='50%'>";
      $lockable_fields = PluginFusioninventoryLockable::getLockableFields('', $tableName);
      $locked = PluginFusioninventoryLock::getLockFields($tableName, $p_items_id);
      if (count($locked)){
         foreach ($locked as $key => $val){
            if (!in_array($val, $lockable_fields)) {
               unset($locked[$key]);
            }
         }
      } else {
         $locked = array();
      }

      $item = new $p_itemtype;
      $item->getFromDB($p_items_id);

      echo "<form method='post' action=\"$p_target\">";
      echo "<input type='hidden' name='id' value='$p_items_id'>";
      echo "<input type='hidden' name='type' value='$p_itemtype'>";
      echo "<table class='tab_cadre'>";
      echo "<tr><th>&nbsp;".$LANG['plugin_fusioninventory']['functionalities'][73]."&nbsp;</th>";
      echo "<th>&nbsp;".$LANG['plugin_fusioninventory']['functionalities'][74]."&nbsp;</th>";
      echo "<th>&nbsp;".$LANG['plugin_fusioninventory']['functionalities'][75]."&nbsp;</th></tr>";
      if (empty($lockable_fields)) {
         echo "<tr class='tab_bg_2'><td align='center' colspan='3'>
                  ".$LANG['plugin_fusioninventory']['functionalities'][76]."</td></tr>";
      } else {
         $checked = '';
         foreach ($lockable_fields as $val) {
            if (in_array($val, $locked)) {
               $checked = 'checked';
            } else {
               $checked = '';
            }
   //         echo "<tr class='tab_bg_1'><td>" . $FUSIONINVENTORY_MAPPING_FIELDS[$val] . "</td>
            echo "<tr class='tab_bg_1'><td>" . $val . "</td>
                     <td>".$item->getField($val)."</td><td align='center'><input type='checkbox' name='lockfield_fusioninventory[" . $val . "]' $checked></td></tr>";
         }
         echo "<tr class='tab_bg_2'><td align='center' colspan='3'>
                  <input class='submit' type='submit' name='unlock_field_fusioninventory'
                         value='" . $LANG['buttons'][7] . "'></td></tr>";
      }
      echo "</table>";
      echo "</form>";
      echo "</div>";
   }

   /**
    * Unlock a field for a record.
    *
     *@param $p_table Table name.
     *@param $p_items_id Line id.
     *@param $p_fieldToDel field to unlock.
    *TODO:  check rights and entity
    *
    *@return nothing
    **/
   static function deleteInLockArray($p_table, $p_items_id, $p_fieldToDel) {
      global $DB;

      $fieldsToLock = PluginFusioninventoryLock::getLockFields($p_table, $p_items_id);
      if (count($fieldsToLock)){
         $fieldToDel=array_search($p_fieldToDel,$fieldsToLock);
         if (isset($fieldsToLock[$fieldToDel])){
            unset ($fieldsToLock[$fieldToDel]);
         }
         if (count($fieldsToLock)) {       // there are still locks
            $fieldsToLock=array_values($fieldsToLock);
            $update = "UPDATE `glpi_plugin_fusioninventory_locks`
                       SET `tablefields`='" . exportArrayToDB($fieldsToLock) . "'
                       WHERE `tablename`='".$p_table."'
                             AND `items_id`='".$p_items_id."';";
            $DB->query($update);
         } else {                            // no locks any more
            $delete = "DELETE FROM `glpi_plugin_fusioninventory_locks`
                       WHERE `tablename`='".$p_table."'
                             AND `items_id`='".$p_items_id."';";
            $DB->query($delete);
         }
      }
   }

   /**
    * Unlock a field for all records.
    *
     *@param $p_table Table name.
     *@param $p_items_id Line id.
     *@param $p_fieldToDel field to unlock.
    *TODO:  check rights and entity
    *
    *@return nothing
    **/
   static function deleteInAllLockArray($p_table, $p_fieldToDel) {
      global $DB;

      $query = "SELECT `items_id`
                FROM `glpi_plugin_fusioninventory_locks`
                WHERE `tablename`='".$p_table."'
                      AND `tablefields` LIKE '%".$p_fieldToDel."%';";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         // TODO improve the lock deletion by transmiting the old locked fields to the deletion function
         PluginFusioninventoryLock::deleteInLockArray($p_table, $data['items_id'], $p_fieldToDel);
      }
   }

   /**
    * Set lock fields for a record.
    *
     *@param $p_itemtype Table id.
     *@param $p_items_id Line id.
     *@param $p_fieldsToLock Array of fields to lock.
    *TODO:  check rights and entity
    *
    *@return nothing
    **/
   static function setLockArray($p_itemtype, $p_items_id, $p_fieldsToLock) {
      global $DB;

      $tableName = getTableForItemType($p_itemtype);
      $result = PluginFusioninventoryLock::getLock($tableName, $p_items_id);
      if ($DB->numrows($result)){
         if (count($p_fieldsToLock)) {       // old locks --> new locks
            $update = "UPDATE `glpi_plugin_fusioninventory_locks`
                       SET `tablefields`='" . exportArrayToDB($p_fieldsToLock) . "'
                       WHERE `tablename`='".$tableName."'
                             AND `items_id`='".$p_items_id."';";
            $DB->query($update);
         } else {                            // old locks --> no locks any more
            $delete = "DELETE FROM `glpi_plugin_fusioninventory_locks`
                       WHERE `tablename`='".$tableName."'
                             AND `items_id`='".$p_items_id."';";
                        $DB->query($delete);
         }
      } elseif (count($p_fieldsToLock)) {    // no locks --> new locks
         $insert = "INSERT INTO `glpi_plugin_fusioninventory_locks` 
                       (`tablename`, `items_id`, `tablefields`)
                    VALUES ('".$tableName."', '".$p_items_id."' ,
                            '".exportArrayToDB($p_fieldsToLock) . "');";
         $DB->query($insert);
      }
   }

   /**
    * Add lock fields for a record.
    *
     *@param $p_itemtype Table id.
     *@param $p_items_id Line id.
     *@param $p_fieldsToLock Array of fields to lock.
    *TODO:  check rights and entity
    *
    *@return nothing
    **/
   static function addLocks($p_itemtype, $p_items_id, $p_fieldsToLock) {
      global $DB;

      $tableName = getTableForItemType($p_itemtype);
      if (TableExists('glpi_plugin_fusioninventory_lockables')) {
         $result = PluginFusioninventoryLock::getLock($tableName, $p_items_id);
         if ($DB->numrows($result)){
            $row = mysql_fetch_assoc($result);
            $lockedFields = importArrayFromDB($row['tablefields']);
            if (count(array_diff($p_fieldsToLock, $lockedFields))) { // old locks --> new locks
               $p_fieldsToLock = array_merge($p_fieldsToLock, $lockedFields);
               $update = "UPDATE `glpi_plugin_fusioninventory_locks`
                          SET `tablefields`='" . exportArrayToDB($p_fieldsToLock) . "'
                          WHERE `tablename`='".$tableName."'
                                AND `items_id`='".$p_items_id."';";
               $DB->query($update);
            }
         } elseif (count($p_fieldsToLock)) {    // no locks --> new locks
            $insert = "INSERT INTO `glpi_plugin_fusioninventory_locks`
                          (`tablename`, `items_id`, `tablefields`)
                       VALUES ('".$tableName."', '".$p_items_id."' ,
                               '".exportArrayToDB($p_fieldsToLock) . "');";
            $DB->query($insert);
         }
      }
   }

   /**
    * Get lock fields for a record.
    *
    * @param $p_table Table name.
    * @param $p_items_id Line id.
    * TODO:  check rights and entity
    *
    *@return result of the query
    **/
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
    * @param $p_table Table name.
    * @param $p_items_id Line id.
    * TODO:  check rights
    *
    *@return array of locked fields
    **/
   static function getLockFields($p_table, $p_items_id) {
      global $DB;

      $db_lock = $DB->fetch_assoc(PluginFusioninventoryLock::getLock($p_table, $p_items_id));
      $lock_fields = $db_lock["tablefields"];
      $lock = importArrayFromDB($lock_fields);

      return $lock;
   }

   /*
    * convert an array resulting from many form checks (0=>on 2=>on 5=>on ...)
    * into a classical array (0=>0 1=>2 2=>5 ...)
    *
    * @param $p_checksArray checkbox array from form
    * @result classical array
    */
   static function exportChecksToArray($p_checksArray) {
      $array = array();
      foreach ($p_checksArray as $key => $val) {
         array_push($array, $key);
      }
      return $array;
   }

}

?>