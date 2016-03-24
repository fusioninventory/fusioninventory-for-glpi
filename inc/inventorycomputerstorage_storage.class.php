<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
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

class PluginFusioninventoryInventoryComputerStorage_Storage extends CommonDBTM {

   static $rightname = 'computer';


   static function getTypeName($nb=0) {
      return __('Storage', 'fusioninventory');
   }



   function getOpposites($id) {
      global $DB;

      $a_id = array();
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_fusioninventory_inventorycomputerstorages_id_1`='".$id."'
            OR `plugin_fusioninventory_inventorycomputerstorages_id_2`='".$id."'";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if ($data['plugin_fusioninventory_inventorycomputerstorages_id_1'] == $id) {
            $a_id[] = $data['plugin_fusioninventory_inventorycomputerstorages_id_2'];
         } else {
            $a_id[] =  $data['plugin_fusioninventory_inventorycomputerstorages_id_1'];
         }
      }
      if (count($a_id) > 0) {
         return $a_id;
      }
      return 0;
   }


   function getChildren($id, $level) {
      global $DB;

      $a_id = array();
      $query = "SELECT `glpi_plugin_fusioninventory_inventorycomputerstorages`.`id`,
         `level` FROM `".$this->getTable()."`
         LEFT JOIN `glpi_plugin_fusioninventory_inventorycomputerstorages`
            ON `plugin_fusioninventory_inventorycomputerstorages_id_1` =
               `glpi_plugin_fusioninventory_inventorycomputerstorages`.`id`
         LEFT JOIN `glpi_plugin_fusioninventory_inventorycomputerstoragetypes`
            ON `plugin_fusioninventory_inventorycomputerstoragetypes_id` =
               `glpi_plugin_fusioninventory_inventorycomputerstoragetypes`.`id`
         WHERE `plugin_fusioninventory_inventorycomputerstorages_id_2`='".$id."'
            AND `level` > '".$level."'";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $a_id[$data['id']] = $data['level'];
      }
      $query = "SELECT `glpi_plugin_fusioninventory_inventorycomputerstorages`.`id`,
         `level` FROM `".$this->getTable()."`
         LEFT JOIN `glpi_plugin_fusioninventory_inventorycomputerstorages`
            ON `plugin_fusioninventory_inventorycomputerstorages_id_2` =
               `glpi_plugin_fusioninventory_inventorycomputerstorages`.`id`
         LEFT JOIN `glpi_plugin_fusioninventory_inventorycomputerstoragetypes`
            ON `plugin_fusioninventory_inventorycomputerstoragetypes_id` =
               `glpi_plugin_fusioninventory_inventorycomputerstoragetypes`.`id`
         WHERE `plugin_fusioninventory_inventorycomputerstorages_id_1`='".$id."'
            AND `level` > '".$level."'";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $a_id[$data['id']] = $data['level'];
      }
      return $a_id;
   }


   function getParent($id, $level) {
      global $DB;

      $a_id = array();
      $query = "SELECT `glpi_plugin_fusioninventory_inventorycomputerstorages`.`id`,
         `level` FROM `".$this->getTable()."`
         LEFT JOIN `glpi_plugin_fusioninventory_inventorycomputerstorages`
            ON `plugin_fusioninventory_inventorycomputerstorages_id_1` =
               `glpi_plugin_fusioninventory_inventorycomputerstorages`.`id`
         LEFT JOIN `glpi_plugin_fusioninventory_inventorycomputerstoragetypes`
            ON `plugin_fusioninventory_inventorycomputerstoragetypes_id` =
               `glpi_plugin_fusioninventory_inventorycomputerstoragetypes`.`id`
         WHERE `plugin_fusioninventory_inventorycomputerstorages_id_2`='".$id."'
            AND `level` < '".$level."'";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $a_id[$data['id']] = $data['level'];
      }
      $query = "SELECT `glpi_plugin_fusioninventory_inventorycomputerstorages`.`id`,
         `level` FROM `".$this->getTable()."`
         LEFT JOIN `glpi_plugin_fusioninventory_inventorycomputerstorages`
            ON `plugin_fusioninventory_inventorycomputerstorages_id_2` =
               `glpi_plugin_fusioninventory_inventorycomputerstorages`.`id`
         LEFT JOIN `glpi_plugin_fusioninventory_inventorycomputerstoragetypes`
            ON `plugin_fusioninventory_inventorycomputerstoragetypes_id` =
               `glpi_plugin_fusioninventory_inventorycomputerstoragetypes`.`id`
         WHERE `plugin_fusioninventory_inventorycomputerstorages_id_1`='".$id."'
            AND `level` < '".$level."'";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $a_id[$data['id']] = $data['level'];
      }
      return $a_id;
   }
}

?>
