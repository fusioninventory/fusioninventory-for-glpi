<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2012 FusionInventory team
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


class PluginFusioninventoryLock extends CommonDBTM{


   static function getTypeName($nb=0) {

      return _n('Lock', 'Locks', 2, 'fusioninventory');

   }


   static function canCreate() {
      return true;
   }


   static function canView() {
      return true;
   }



   static function countForLock(CommonGLPI $item) {

      $pfLock = new self();
      $a_data = current($pfLock->find("`tablename`='".$item->getTable()."'
         AND `items_id`='".$item->getID()."'", "", 1));
      if (count($a_data) == '0') {
         return 0;
      } else {
         return count(importArrayFromDB($a_data['tablefields']));
      }
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $itemtype = $item->getType();
      if ($itemtype == 'NetworkEquipment') {
         $itemtype = "networking";
      }
      if (Session::haveRight(strtolower($itemtype), "w")) {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry(_n('Lock', 'Locks', 2, 'fusioninventory'), self::countForLock($item));
         }
         return _n('Lock', 'Locks', 2, 'fusioninventory');

      }
      return '';
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pflock = new self();
      if ($item->getID() < 1) {
         $pflock->showForm(Toolbox::getItemTypeFormURL('PluginFusioninventoryLock'),
                           $item->getType());
      } else {
         $pflock->showForm(Toolbox::getItemTypeFormURL('PluginFusioninventoryLock').'?id='.$item->getID(),
                           $item->getType(), $item->getID());
      }

      return true;
   }




   /**
    * Show locks form.
    *
    * @param $p_target Target file.
    * @param $p_itemtype Class name.
    * @param $p_items_id Line id.
    *TODO:  check rights and entity
    *
    * @return nothing (print the form)
    **/
   function showForm($p_target, $p_itemtype, $p_items_id=0) {

      $can = 0;
      $typeright = strtolower($p_itemtype);
      if ($typeright == "networkequipment") {
         $typeright = "networking";
      }
      if (Session::haveRight($typeright,"w")) {
        $can = 1;
      }

      $tableName = getTableForItemType($p_itemtype);
      echo "<div width='50%'>";
      $locked = PluginFusioninventoryLock::getLockFields($tableName, $p_items_id);

      if (!count($locked)){
         $locked = array();
      }
      $colspan = '2';
      if ($p_items_id != '0') {
         $colspan = '3';
      }

      $item = new $p_itemtype;
      if ($p_items_id == "0") {
         $item->getEmpty();
      } else {
         $item->getFromDB($p_items_id);
      }

      if (!strstr($p_target, "ajax/dropdownMassiveAction.php")) {
         echo "<form method='post' action='".$p_target."'>";
      }
      echo "<input type='hidden' name='id' value='$p_items_id'>";
      echo "<input type='hidden' name='type' value='$p_itemtype'>";
      echo "<table class='tab_cadre'>";
      echo "<tr><th>&nbsp;".__('Fields')."&nbsp;</th>";
      if ($p_items_id != '0') {
         echo "<th>&nbsp;".__('Values')."&nbsp;</th>";
      }
      echo "<th>&nbsp;"._n('Lock', 'Locks', 2, 'fusioninventory')."&nbsp;</th></tr>";

      $checked = '';
      $a_exclude = $this->excludeFields();
      foreach ($item->fields as $key=>$val) {
         $key_source = $key;
         if (!in_array($key, $a_exclude)) {
            if (in_array($key, $locked)) {
               $checked = 'checked';
            } else {
               $checked = '';
            }
            if ((strstr($key, "_id")
                    OR ($key == 'is_ocs_import'))
               AND $val == '0'){

               $val = "";
            }

            // Get name of field
            $array = search::getOptions($p_itemtype);
            $num = search::getOptionNumber($p_itemtype, $key);
            // Specific keys
            $key1 = $key;
            switch($key) {

               case 'users_id_tech':
                  $key1 = __('Technician in charge of the hardware');

                  break;

               case 'computermodels_id':
                  $key1 = __('Model');

                  break;

               case 'computertypes_id':
                  $key1 = __('Type');

                  break;

               case 'states_id':
                  $key1 = __('Status');

                  break;

               case 'ticket_tco':
                  $key1 = __('TCO');

                  break;

            }
            // standards keys
            if ($key1 != $key) {
               $key = $key1;
            } else {
               if (isset($array[$num]['name'])) {
                  $key = $array[$num]['name'];
               }
            }

            // Get value of field
            $table = getTableNameForForeignKeyField($key);
            if ($table != "") {
               $linkItemtype = getItemTypeForTable($table);
               $class = new $linkItemtype();
               $key = $class->getTypeName();
               if (($val == "0") OR ($val == "")) {
                  $val = "";
               } else {
                  $class->getFromDB($val);
                  $val = $class->getName();
               }
            }

         echo "<tr class='tab_bg_1'><td>" . $key."</td>";
         if ($p_items_id != '0') {
            echo "<td>".$val."</td>";
         }
            echo "<td align='center'><input type='checkbox' name='lockfield_fusioninventory[" . $key_source . "]' $checked></td></tr>";
         }
      }
      if ($p_items_id == '0') {
         // add option selection for add theses lock filed or remove them
         echo "<tr>";
         echo "<th colspan='2'>".__('Job')."</th>";
         echo "<tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Add locks')."</td>";
         echo "<td align='center'><input type='radio' name='actionlock' value='addLock' checked/></td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Delete locks')."</td>";
         echo "<td align='center'><input type='radio' name='actionlock' value='deleteLock' /></td>";
         echo "</tr>";

      }
      if ($can == '1') {
         echo "<tr class='tab_bg_2'>";
         echo "<td align='center' colspan='".$colspan."'>";
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
   }



   /**
    * Unlock a field for a record.
    *
     * @param $p_table Table name.
     * @param $p_items_id Line id.
     * @param $p_fieldToDel field to unlock.
    *TODO:  check rights and entity
    *
    * @return nothing
    **/
   static function deleteInLockArray($p_table, $p_items_id, $p_fieldToDel) {

      $pfLock = new PluginFusioninventoryLock();
      $fieldsToLock = PluginFusioninventoryLock::getLockFields($p_table, $p_items_id);
      if (count($fieldsToLock)){
         $fieldToDel=array_search($p_fieldToDel,$fieldsToLock);
         if (isset($fieldsToLock[$fieldToDel])){
            unset ($fieldsToLock[$fieldToDel]);
         }
         if (count($fieldsToLock)) {       // there are still locks
            $fieldsToLock=array_values($fieldsToLock);

            $a_lines = $pfLock->find("`tablename`='".$p_table."' AND `items_id`='".$p_items_id."'");
            $a_line = current($a_lines);
            $pfLock->getFromDB($a_line['id']);
            $input = array();
            $input['id'] = $pfLock->fields['id'];
            $input['tablefields'] = exportArrayToDB($fieldsToLock);
            $pfLock->update($input);
         } else {                            // no locks any more
            $a_lines = $pfLock->find("`tablename`='".$p_table."' AND `items_id`='".$p_items_id."'");
            $a_line = current($a_lines);
            $pfLock->getFromDB($a_line['id']);
            $pfLock->delete($pfLock->fields);
         }
      }
   }



   /**
    * Unlock a field for all records.
    *
     * @param $p_table Table name.
     * @param $p_items_id Line id.
     * @param $p_fieldToDel field to unlock.
    *TODO:  check rights and entity
    *
    * @return nothing
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
     * @param $p_itemtype Table id.
     * @param $p_items_id Line id.
     * @param $p_fieldsToLock Array of fields to lock.
    *TODO:  check rights and entity
    *
    * @return nothing
    **/
   static function setLockArray($p_itemtype, $p_items_id, $p_fieldsToLock, $massiveaction='') {
      global $DB;

      $pfl = new PluginFusioninventoryLock();

      $tableName = getTableForItemType($p_itemtype);
      $result = PluginFusioninventoryLock::getLock($tableName, $p_items_id);
      if ($DB->numrows($result)){
         $a_lines = $pfl->find("`tablename`='".$tableName."' AND `items_id`='".$p_items_id."'");
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
            $pfl->update($pfl->fields);
         } else if ($massiveaction == 'deleteLock') {
            $a_lockfieldsDB = importArrayFromDB($pfl->fields['tablefields']);
            foreach ($p_fieldsToLock as $fieldtoadd) {
               if (in_array($fieldtoadd, $a_lockfieldsDB)) {
                  $key = array_search($fieldtoadd, $a_lockfieldsDB);
                  unset($a_lockfieldsDB[$key]);
               }
            }
            $pfl->fields['tablefields'] = exportArrayToDB($a_lockfieldsDB);
            $pfl->update($pfl->fields);
         } else {
            if (count($p_fieldsToLock)) {       // old locks --> new locks
               $pfl->fields['tablefields'] = exportArrayToDB($p_fieldsToLock);
               $pfl->update($pfl->fields);
            } else {                            // old locks --> no locks any more
               $pfl->delete($pfl->fields);
            }
         }
      } elseif (count($p_fieldsToLock)) {    // no locks --> new locks
         $input = array();
         $input['tablename']     = $tableName;
         $input['items_id']      = $p_items_id;
         $input['tablefields']   = exportArrayToDB($p_fieldsToLock);
         $pfl->add($input);
      }
   }



   /**
    * Add lock fields for a record.
    *
     * @param $p_itemtype Table id.
     * @param $p_items_id Line id.
     * @param $p_fieldsToLock Array of fields to lock.
    *TODO:  check rights and entity
    *
    * @return nothing
    **/
   static function addLocks($p_itemtype, $p_items_id, $p_fieldsToLock) {
      global $DB;

      $tableName = getTableForItemType($p_itemtype);

      $pfl = new PluginFusioninventoryLock();
      $a_exclude = $pfl->excludeFields();
      $p_fieldsToLock = array_diff($p_fieldsToLock, $a_exclude);

      $result = PluginFusioninventoryLock::getLock($tableName, $p_items_id);
      if ($DB->numrows($result)){
         $row = mysql_fetch_assoc($result);
         $lockedFields = importArrayFromDB($row['tablefields']);
         if (count(array_diff($p_fieldsToLock, $lockedFields))) { // old locks --> new locks
            $p_fieldsToLock = array_merge($p_fieldsToLock, $lockedFields);

            $a_lines = $pfl->find("`tablename`='".$tableName."' AND `items_id`='".$p_items_id."'");
            $a_line = current($a_lines);
            $pfl->getFromDB($a_line['id']);
            $pfl->fields['tablefields'] = exportArrayToDB($p_fieldsToLock);
            $pfl->update($pfl->fields);
         }
      } elseif (count($p_fieldsToLock)) {    // no locks --> new locks

         $input = array();
         $input['tablename']     = $tableName;
         $input['items_id']      = $p_items_id;
         $input['tablefields']   = exportArrayToDB($p_fieldsToLock);
         $pfl->add($input);
      }
   }



   /**
    * Get lock fields for a record.
    *
    * @param $p_table Table name.
    * @param $p_items_id Line id.
    * TODO:  check rights and entity
    *
    * @return result of the query
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
    * @return array of locked fields
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



    /**
    * Manage list of fields to exclude for lock
    *
    * @return array list of fields to exclude
    **/
   function excludeFields() {
      $exclude = array();
      $exclude[] = "id";
      $exclude[] = "entities_id";
      $exclude[] = "is_recursive";
      $exclude[] = "date_mod";
      $exclude[] = "is_deleted";
      $exclude[] = "is_template";
      $exclude[] = "template_name";
      return $exclude;
   }



   /**
   * Delete locks fields and get from lib value from last inventory
   *
   * @param $item object Computer object
   *
   * @return nothing
   *
   **/
   static function deleteLock($item) {
      global $DB;

      $PluginFusinvinventoryLib = new PluginFusioninventoryInventoryComputerLib();

      // Get mapping
      $a_mapping = PluginFusioninventoryInventoryComputerLibhook::getMapping();
      $a_fieldList = array();
      if ($item->fields['tablefields'] == $item->input['tablefields']) {
         $a_fieldList = importArrayFromDB($item->fields['tablefields']);
      } else {
         $a_fieldListTemp = importArrayFromDB($item->fields['tablefields']);
         $a_inputList = importArrayFromDB($item->input['tablefields']);
         $a_diff = array_diff($a_fieldListTemp, $a_inputList);
         $a_fieldList = array();
         foreach ($a_diff as $value) {
            if (in_array($value, $a_fieldListTemp)) {
               $a_fieldList[] = $value;
            }
         }
      }
      for ($i=0; $i < count($a_fieldList); $i++) {
         foreach ($a_mapping as $datas) {
            if (isset($item->fields['tablename'])
                  AND ($item->fields['tablename'] == getTableForItemType($datas['glpiItemtype']))
                  AND ($a_fieldList[$i] == $datas['glpiField'])) {

               // Get serialization
               $query = "SELECT * FROM `glpi_plugin_fusioninventory_inventorycomputerlibserialization`
                  WHERE `computers_id`='".$item->fields['items_id']."'
                     LIMIT 1";
               $result = $DB->query($query);
               if ($result) {
                  if ($DB->numrows($result) == '1') {
                     $a_serialized = $DB->fetch_assoc($result);
                     $infoSections = $PluginFusinvinventoryLib->_getInfoSections($a_serialized['internal_id']);

                     // Modify fields
                     $table = getTableNameForForeignKeyField($datas['glpiField']);
                     $itemtypeLink = "";
                     if ($table != "") {
                        $itemtypeLink = getItemTypeForTable($table);
                     }
                     $itemtype = $datas['glpiItemtype'];
                     $class = new $itemtype();
                     $class->getFromDB($item->fields['items_id']);
                     $input = array();
                     $input['id'] = $class->fields['id'];
                     if ($itemtypeLink == "User") {
                        $update_user = 0;
                        foreach($infoSections["sections"] as $sectionname=>$serializeddatas) {
                           if (strstr($sectionname, "USERS/")) {
                              if (!strstr($sectionname, "USERS/-")) {
                                 $users_id = str_replace("USERS/", "", $sectionname);
                                 $input[$datas['glpiField']] = Toolbox::addslashes_deep($users_id);
                                 $update_user = 1;
                              }
                           }
                        }
                        if ($update_user == '0') {
                           foreach($infoSections["sections"] as $sectionname=>$serializeddatas) {
                              if (strstr($sectionname, "USERS/")) {
                                 if (strstr($sectionname, "USERS/-")) {
                                    $users_name = str_replace("USERS/-", "", $sectionname);
                                    $query_user = "SELECT `id`
                                              FROM `glpi_users`
                                              WHERE `name` = '".$users_name."';";
                                    $result_user = $DB->query($query_user);
                                    if ($DB->numrows($result_user) == 1) {
                                       $input[$datas['glpiField']] = $DB->result($result_user, 0, 0);
                                    }
                                 }
                              }
                           }
                        }
                     } else if ($table != "") {
                        $vallib = '';
                        if ($table == 'glpi_computermodels') {
                           $smodel = '';
                           $mmodel = '';
                           foreach($infoSections["sections"] as $sectionname=>$serializeddatas) {
                              if (strstr($sectionname, "BIOS/")) {
                                 $un = unserialize($serializeddatas);
                                 $smodel = $un['SMODEL'];
                                 $mmodel = $un['MMODEL'];
                              }
                           }
                           if (isset($smodel) AND $smodel != '') {
                              $ComputerModel = new ComputerModel();
                              $input[$datas['glpiField']] = $ComputerModel->importExternal($smodel);
                           } else if (isset($mmodel) AND $mmodel != '') {
                              $ComputerModel = new ComputerModel();
                              $input[$datas['glpiField']] = $ComputerModel->importExternal($mmodel);
                           }
                        } else {
                           $libunserialized = unserialize($infoSections["sections"][$datas['xmlSection']."/".$item->fields['items_id']]);
                           if ($datas['xmlSectionChild'] == "TYPE") {
                              if ($libunserialized[$datas['xmlSectionChild']] != "") {
                                 $vallib = Dropdown::importExternal($itemtypeLink,$libunserialized[$datas['xmlSectionChild']]);
                              } else {
                                 $vallib = Dropdown::importExternal($itemtypeLink,$libunserialized["MMODEL"]);
                              }
                           } else {
                              $itemdr = new $itemtypeLink();
                              $computer = new Computer();
                              $computer->getFromDB($item->fields['items_id']);
                              $vallib = $itemdr->importExternal($libunserialized[$datas['xmlSectionChild']], $computer->fields['entities_id']);
                           }
                           $input[$datas['glpiField']] = $vallib;
                        }
                    } else {
                        $libunserialized = unserialize($infoSections["sections"][$datas['xmlSection']."/".$item->fields['items_id']]);

                        if ($datas['glpiField'] == 'contact') {
                           $contact = '';
                           foreach($infoSections["sections"] as $sectionname=>$serializeddatas) {
                              if (strstr($sectionname, "USERS/")) {
                                 $unserialiseUser = unserialize($serializeddatas);
                                 if ($contact == '') {
                                    $contact .= $unserialiseUser['LOGIN'];
                                 } else {
                                    $contact .= "/".$unserialiseUser['LOGIN'];
                                 }
                              }
                           }
                           $input[$datas['glpiField']] = Toolbox::addslashes_deep($contact);
                        } else {
                           $input[$datas['glpiField']] = Toolbox::addslashes_deep($libunserialized[$datas['xmlSectionChild']]);
                        }
                     }
                     $class->update($input);
                  }
               }
            }
         }
      }
   }



    /**
    * Import OCS locks
    *
    * @return nothing
    **/
   function importFromOcs() {
      global $DB;

      if (TableExists(`glpi_ocslinks`)) {
         $sql = "SELECT * FROM `glpi_ocslinks`";
         $result=$DB->query($sql);
         while ($data=$DB->fetch_array($result)) {
            $a_ocslocks = importArrayFromDB($data['computer_update']);
            $a_fields = array();
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

}

?>