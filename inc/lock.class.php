<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryLock {


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

      $PluginFusinvinventoryLib = new PluginFusinvinventoryLib();

      // Get mapping
      $a_mapping = PluginFusinvinventoryLibhook::getMapping();
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
            if (($item->fields['tablename'] == getTableForItemType($datas['glpiItemtype']))
                  AND ($a_fieldList[$i] == $datas['glpiField'])) {

               // Get serialization
               $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`
                  WHERE `computers_id`='".$item->fields['items_id']."'
                     LIMIT 1";
               if ($result = $DB->query($query)) {
                  if ($DB->numrows($result) == '1') {
                     $a_serialized = $DB->fetch_assoc($result);
                     $infoSections = $PluginFusinvinventoryLib->_getInfoSections($a_serialized['internal_id']);

                     // Modify fields
                     $table = getTableNameForForeignKeyField($datas['glpiField']);
                     if ($table != "") {
                        $itemtypeLink = getItemTypeForTable($table);
                     }
                     $itemtype = $datas['glpiItemtype'];
                     $class = new $itemtype();
                     $class->getFromDB($item->fields['items_id']);
                     if ($itemtypeLink == "User") {
                        $update_user = 0;
                        foreach($infoSections["sections"] as $sectionname=>$serializeddatas) {
                           if (strstr($sectionname, "USERS/")) {
                              if (!strstr($sectionname, "USERS/-")) {
                                 $users_id = str_replace("USERS/", "", $sectionname);
                                 $class->fields[$datas['glpiField']] = $users_id;
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
                                       $class->fields[$datas['glpiField']] = $DB->result($result_user, 0, 0);
                                    }
                                 }
                              }
                           }
                        }
                     } else if ($table != "") {
                        $libunserialized = unserialize($infoSections["sections"][$datas['xmlSection']."/".$item->fields['items_id']]);
                        $vallib = Dropdown::importExternal($itemtypeLink,$libunserialized[$datas['xmlSectionChild']]);
                        $class->fields[$datas['glpiField']] = $vallib;
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
                           $class->fields[$datas['glpiField']] = $contact;
                        } else {
                           $class->fields[$datas['glpiField']] = $libunserialized[$datas['xmlSectionChild']];
                        }
                     }
                     $class->update($class->fields);
                  }
               }               
            }
         }
      }
   }
}

?>