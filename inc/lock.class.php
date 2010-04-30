<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
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
		$this->table="glpi_plugin_fusioninventory_lock";
		$this->type=-1;
	}


	/**
    * Show locks form.
    *
    *@param $p_target Target file.
    *@param $p_itemtype Table name.
    *@param $p_items_id Line id.
    *TODO:  check rights and entity
    *
    *@return nothing (print the form)
    **/
   // si suppr du lockable --> suppr les locks
   function showForm($p_target, $p_itemtype, $p_items_id) {
      global $DB, $LANG, $SEARCH_OPTION;

      echo "<div width='50%'>";
      $lockable_fields = PluginFusioninventoryLockable::getLockableFields('', $p_itemtype);
      $locked = PluginFusioninventoryLock::getLockFields($p_itemtype, $p_items_id);
      if (count($locked)){
         foreach ($locked as $key => $val){
            if (!in_array($val, $lockable_fields)) {
               unset($locked[$key]);
            }
         }
      } else {
         $locked = array();
      }

      include_once(GLPI_ROOT.'/plugins/fusioninventory/inc_constants/plugin_fusioninventory.mapping.fields.constant.php');
      $CommonItem = new CommonItem;
      $CommonItem->getFromDB($p_itemtype, $p_items_id);

      echo "<form method='post' action=\"$p_target\">";
      echo "<input type='hidden' name='ID' value='$p_items_id'>";
      echo "<input type='hidden' name='type' value='$p_itemtype'>";
      echo "<table class='tab_cadre'>";
      echo "<tr><th>&nbsp;".$LANG['plugin_fusioninventory']["functionalities"][73]."&nbsp;</th>";
      echo "<th>&nbsp;".$LANG['plugin_fusioninventory']["functionalities"][74]."&nbsp;</th>";
      echo "<th>&nbsp;".$LANG['plugin_fusioninventory']["functionalities"][75]."&nbsp;</th></tr>";
      foreach ($lockable_fields as $key => $val) {
         if (in_array($val, $locked)) {
            $checked = 'checked';
         } else {
            $checked = '';
         }
         echo "<tr class='tab_bg_1'><td>" . $FUSIONINVENTORY_MAPPING_FIELDS[$val] . "</td>
                  <td>".$CommonItem->getField($val)."</td><td align='center'><input type='checkbox' name='lockfield_fusioninventory[" . $val . "]' $checked></td></tr>";
      }
      echo "<tr class='tab_bg_2'><td align='center' colspan='3'>
               <input class='submit' type='submit' name='unlock_field_fusioninventory'
                      value='" . $LANG['buttons'][7] . "'></td></tr>";
      echo "</table>";
      echo "</form>";
      echo "</div>";
   }

   /**
    * Unlock a field for a record.
    *
     *@param $p_itemtype Table id.
     *@param $p_items_id Line id.
     *@param $p_fieldToDel Field to unlock.
    *TODO:  check rights and entity
    *
    *@return nothing
    **/
   static function deleteInLockArray($p_itemtype, $p_items_id, $p_fieldToDel) {
      global $DB;

      $fieldsToLock = PluginFusioninventoryLock::getLockFields($p_itemtype, $p_items_id);
      if (count($fieldsToLock)){
         $fieldToDel=array_search($p_fieldToDel,$fieldsToLock);
         if (isset($fieldsToLock[$fieldToDel])){
            unset ($fieldsToLock[$fieldToDel]);
            // TODO : reindex array $fieldsToLock
         }
         if (count($fieldsToLock)) {       // there are still locks
            $update = "UPDATE `glpi_plugin_fusioninventory_lock`
                       SET `fields`='" . exportArrayToDB($fieldsToLock) . "'
                       WHERE `itemtype`='".$p_itemtype."'
                             AND `items_id`='".$p_items_id."';";
            $DB->query($update);
         } else {                            // no locks any more
            $delete = "DELETE FROM `glpi_plugin_fusioninventory_lock`
                       WHERE `itemtype`='".$p_itemtype."'
                             AND `items_id`='".$p_items_id."';";
                        $DB->query($delete);
         }
      }
   }

   /**
    * Unlock a field for all records.
    *
     *@param $p_itemtype Table id.
     *@param $p_items_id Line id.
     *@param $p_fieldToDel Field to unlock.
    *TODO:  check rights and entity
    *
    *@return nothing
    **/
   static function deleteInAllLockArray($p_itemtype, $p_fieldToDel) {
      global $DB;

      $query = "SELECT `items_id`
                FROM `glpi_plugin_fusioninventory_lock`
                WHERE `itemtype`='".$p_itemtype."'
                      AND `fields` LIKE '%=>".$p_fieldToDel." %';";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         // TODO improve the lock deletion by transmiting the old locked fields to the deletion function
         PluginFusioninventoryLock::deleteInLockArray($p_itemtype, $data['items_id'], $p_fieldToDel);
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

      $result = PluginFusioninventoryLock::getLock($p_itemtype, $p_items_id);
      if ($DB->numrows($result)){
         if (count($p_fieldsToLock)) {       // old locks --> new locks
            $update = "UPDATE `glpi_plugin_fusioninventory_lock`
                       SET `fields`='" . exportArrayToDB($p_fieldsToLock) . "'
                       WHERE `itemtype`='".$p_itemtype."'
                             AND `items_id`='".$p_items_id."';";
            $DB->query($update);
         } else {                            // old locks --> no locks any more
            $delete = "DELETE FROM `glpi_plugin_fusioninventory_lock`
                       WHERE `itemtype`='".$p_itemtype."'
                             AND `items_id`='".$p_items_id."';";
                        $DB->query($delete);
         }
      } elseif (count($p_fieldsToLock)) {    // no locks --> new locks
         $insert = "INSERT INTO `glpi_plugin_fusioninventory_lock` (`itemtype`, `items_id`, `fields`)
                    VALUES ('".$p_itemtype."', '".$p_items_id."' ,
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

      if (TableExists('glpi_plugin_fusioninventory_lockable')) {
         $result = PluginFusioninventoryLock::getLock($p_itemtype, $p_items_id);
         if ($DB->numrows($result)){
            $row = mysql_fetch_assoc($result);
            $lockedFields = importArrayFromDB($row['fields']);
            if (count(array_diff($p_fieldsToLock, $lockedFields))) { // old locks --> new locks
               $p_fieldsToLock = array_merge($p_fieldsToLock, $lockedFields);
               $update = "UPDATE `glpi_plugin_fusioninventory_lock`
                          SET `fields`='" . exportArrayToDB($p_fieldsToLock) . "'
                          WHERE `itemtype`='".$p_itemtype."'
                                AND `items_id`='".$p_items_id."';";
               $DB->query($update);
            }
         } elseif (count($p_fieldsToLock)) {    // no locks --> new locks
            $insert = "INSERT INTO `glpi_plugin_fusioninventory_lock` (`itemtype`, `items_id`, `fields`)
                       VALUES ('".$p_itemtype."', '".$p_items_id."' ,
                               '".exportArrayToDB($p_fieldsToLock) . "');";
            $DB->query($insert);
         }
      }
   }

   /**
    * Get lock fields for a record.
    *
    * @param $p_itemtype Table id.
    * @param $p_items_id Line id.
    * TODO:  check rights and entity
    *
    *@return result of the query
    **/
   static function getLock($p_itemtype, $p_items_id) {
      global $DB;

      $query = "SELECT `id`, `fields`
                FROM `glpi_plugin_fusioninventory_lock`
                WHERE `itemtype`='".$p_itemtype."'
                      AND `items_id`='".$p_items_id."';";
      $result = $DB->query($query);
      return $result;
   }

   /**
    * Get lock fields for a record.
    *
    * @param $p_itemtype Table id.
    * @param $p_items_id Line id.
    * TODO:  check rights
    *
    *@return array of locked fields
    **/
   static function getLockFields($p_itemtype, $p_items_id) {
      global $DB;

      $db_lock = $DB->fetch_assoc(PluginFusioninventoryLock::getLock($p_itemtype, $p_items_id));
      $lock_fields = $db_lock["fields"];
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