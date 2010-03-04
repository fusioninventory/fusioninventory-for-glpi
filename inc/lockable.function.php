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

/**
 * Get all about lockables fields
 *
 *@param $p_entities_id='' Entity id.
 *@param $p_itemtype='' Table id.
 *TODO:  check rights
 *
 *@return result of the query
 **/
function plugin_fusioninventory_lockable_getLockable($p_entities_id='', $p_itemtype='') {
	global $DB;

	$query = "SELECT `id`, `itemtype`, `fields`, `entities_id`, `recursive`
             FROM `glpi_plugin_fusioninventory_lockable`";
   $where = '';
   if ($p_entities_id != '') {
      $where = "`entities_id`='".$p_entities_id."'";
   }
   if ($p_itemtype != '') {
      if ($where != '') $where.=' AND ';
      $where .= "`itemtype`='".$p_itemtype."'";
   }
   if ($where != '') $query.=' WHERE '.$where.';';
	$result = $DB->query($query);

   return $result;
}

/**
 * Get lockables fields
 *
 *@param $p_entities_id='' Entity id.
 *@param $p_itemtype='' Table id.
 *TODO:  check rights
 *
 *@return array of lockable fields
 **/
function plugin_fusioninventory_lockable_getLockableFields($p_entities_id='', $p_itemtype='') {
	global $DB;

   $db_lockable = $DB->fetch_assoc(plugin_fusioninventory_lockable_getLockable($p_entities_id, $p_itemtype));
   $lockable_fields = $db_lockable["fields"];
   $lockable = importArrayFromDB($lockable_fields);

   return $lockable;
}

/**
 * Set lockables fields
 *
 *@param $p_id Lockable id. If 0 creates a new lockable record, else update.
 *@param $p_itemtype Table name.
 *@param $p_fields Array of fields to set to lockable (ex : "0=>name 1=>comments 2=>contact").
 *@param $p_entities_id Entity id.
 *@param $p_recursive Recursive lock (0/1).
 *TODO:  check rights
 *
 *@return nothing
 **/
function plugin_fusioninventory_lockable_setLockable($p_id, $p_itemtype, $p_fields, $p_entities_id, $p_recursive) {
	global $DB;

   if (!$p_id) {
      $insert = "INSERT INTO `glpi_plugin_fusioninventory_lockable` (
                  `itemtype`, `fields`, `entities_id`, `recursive` )
                 VALUES ('$p_itemtype','$p_fields','$p_entities_id','$p_recursive');";
      $DB->query($insert);
   } else {
      $update = "UPDATE `glpi_plugin_fusioninventory_lockable`
                 SET `itemtype`='$p_itemtype',
                     `fields`='$p_fields',
                     `entities_id`='$p_entities_id'
                 WHERE `id`='$p_id';";
      $DB->query($update);
   }
}

/**
 * Set lockables fields
 *
 *@param $p_id Lockable id. If 0 creates a new lockable record, else update.
 *@param $p_itemtype Table name.
 *@param $p_fields Array of fields to set to lockable (ex : "0=>name 1=>comments 2=>contact").
 *@param $p_entities_id Entity id.
 *@param $p_recursive Recursive lock (0/1).
 *TODO:  check rights
 *
 *@return nothing
 **/
function plugin_fusioninventory_lockable_setLockableForm($p_post) {
   global $DB, $LINK_ID_TABLE;
   
   $tableId = array_search($p_post["tableSelect"], $LINK_ID_TABLE);
   $_SESSION["glpi_plugin_fusioninventory_lockable_table"] = $tableId;
   
   if ( (isset($p_post['plugin_fusioninventory_lockable_add']) AND isset($p_post['columnSelect'])) // add AND columns to add
         OR (isset($_POST['plugin_fusioninventory_lockable_delete']) AND isset($p_post['columnLockable'])) ) {  // delete AND columns to delete
      $db_lockable = $DB->fetch_assoc(plugin_fusioninventory_lockable_getLockable('', $tableId));
      $lockable_id = $db_lockable["id"];
      $lockable_fields = $db_lockable["fields"];
      $lockable = importArrayFromDB($lockable_fields);


      if (isset($p_post['plugin_fusioninventory_lockable_add']) AND isset($p_post['columnSelect'])) { // add
         foreach ($p_post['columnSelect'] as $key=>$id_value) {
            array_push($lockable, $id_value);
         }
      }

      if (isset($_POST['plugin_fusioninventory_lockable_delete']) AND isset($p_post['columnLockable'])) { // delete
         foreach ($p_post['columnLockable'] as $key=>$id_value) {
            $fieldToDel = array_search($id_value, $lockable);
            if (isset($lockable[$fieldToDel])){
               $fieldName = $lockable[$fieldToDel];
               // TODO add a confirmation request before lockable deletion if locks are defined on this field
               unset($lockable[$fieldToDel]);
               // field is not lockable any more --> delete all locks on this field
               plugin_fusioninventory_lock_deleteInAllLockArray($tableId, $fieldName);
            }
         }
      }

      plugin_fusioninventory_lockable_setLockable($lockable_id, $tableId, exportArrayToDB($lockable), '', '');
   }
}

/**
 * Get multiple column select
 *
 *@param $p_itemtype Table name.
 *TODO:  check rights
 *
 *@return nothing
 **/
function plugin_fusioninventory_lockable_getColumnSelect($p_itemtype) {
   global $DB, $LINK_ID_TABLE;

   $query = "SHOW COLUMNS FROM `".$p_itemtype."`";
   if ($result=$DB->query($query)) {
      include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.mapping.fields.constant.php");
      $tableId = array_search($p_itemtype, $LINK_ID_TABLE);
      if ($tableId != 0) {
         $lockable_fields=plugin_fusioninventory_lockable_getLockableFields('', $tableId);
         echo '<SELECT NAME="columnSelect[]" MULTIPLE SIZE="15">'."\n";
         while ($data=$DB->fetch_array($result)) {
            $column=$data[0];
            if (isset($FUSIONINVENTORY_MAPPING_FIELDS[$column])) { // do not display if the column name is not translated
               if (!in_array($column,$lockable_fields)) { // do not display if the column name is already lockable
                  echo "<OPTION value='$column'>$FUSIONINVENTORY_MAPPING_FIELDS[$column]</OPTION>\n";
               }
            }
         }
         echo '</SELECT>';
      }
   }
}

/**
 * Get multiple lockable select
 *
 *@param $p_itemtype Table name.
 *TODO:  check rights
 *
 *@return nothing
 **/
function plugin_fusioninventory_lockable_getLockableSelect($p_itemtype) {
   global $DB, $LINK_ID_TABLE;

   $tableId = array_search($p_itemtype, $LINK_ID_TABLE);
   if ($tableId != 0) {
      $lockable_fields=plugin_fusioninventory_lockable_getLockableFields('', $tableId);
      include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.mapping.fields.constant.php");
      echo '<SELECT NAME="columnLockable[]" MULTIPLE SIZE="15">';
      if (count($lockable_fields)){
         foreach ($lockable_fields as $key => $val){
            echo "<OPTION value='$val'>$FUSIONINVENTORY_MAPPING_FIELDS[$val]</OPTION>\n";
         }
      }
      echo '</SELECT>';
   }
}

?>