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
   @author    David Durieux
   @co-author 
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

      $pfLib = new PluginFusinvinventoryLib();

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
      $size = count($a_fieldList);
      for ($i=0; $i < $size; $i++) {
         foreach ($a_mapping as $datas) {
            if (isset($item->fields['tablename'])
                    AND ($item->fields['tablename'] == getTableForItemType($datas['glpiItemtype']))
                  AND ($a_fieldList[$i] == $datas['glpiField'])) {

               // Get serialization
               $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`
                  WHERE `computers_id`='".$item->fields['items_id']."'
                     LIMIT 1";
               $result = $DB->query($query);
               if ($result) {
                  if ($DB->numrows($result) == '1') {
                     $a_serialized = $DB->fetch_assoc($result);
                     $infoSections = $pfLib->_getInfoSections($a_serialized['internal_id']);

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

      $pfLock = new PluginFusioninventoryLock();

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
            $pfLock->addLocks("Computer", $data['computers_id'], $a_fields);
         }
      }
   }
}

?>