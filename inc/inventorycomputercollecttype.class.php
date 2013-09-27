<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @author    
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryInventoryComputerCollectType extends CommonDBTM {

   static function getTypeName($nb=0) {
      return __('TODORegistry', 'fusioninventory');
   }

   
   
   static function canCreate() {
      return Session::haveRight('computer', 'w');
   }

   

   static function canView() {
      return Session::haveRight('computer', 'r');
   }



   function getSearchOptions() {

      $tab = array();
      $tab['common'] = __('TODOCharacteristics');

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'version';
      $tab[1]['name']          = "Version";
      $tab[1]['type']          = 'text';

      return $tab;
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType() == 'Computer') {
         if (Session::haveRight('computer', "r")) {
            $a_registry = $this->find("`computers_id`='".$item->getID()."'", '', 1);
            if (count($a_registry) > 0) {
               return self::createTabEntry(__('TODORegistry', 'fusioninventory'));

            }
         }
      }
      return '';
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         $pfRegistry = new self();
         $pfRegistry->showForm($item->getID());
      }
      return TRUE;
   }



   static function addHistory($item){

      foreach ($item->oldvalues as $field=>$old_value) {
         $changes = array();
         $changes[0] = 0;
         $changes[1] = '';
         $changes[2] = "Registry.".$field." : ".$old_value." --> ".$item->fields[$field];
         Log::history($item->fields['computers_id'],
                     "Computer",
                     $changes,
                     'PluginFusioninventoryInventoryComputerRegistry',
                     Log::HISTORY_LOG_SIMPLE_MESSAGE);
      }
   }



   /**
   * Display form for registry
   *
   * @param $items_id integer ID of the registry
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {

      $a_registry = $this->find("`computers_id`='".$items_id."'");
      $registryData = array();
      
      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
      echo "<tr>";
      echo "<th colspan='4'>".__('TODORegistry', 'fusioninventory');

      echo "</th>";
      echo "</tr>";
      
      foreach ($a_registry as $registryData) {
         echo "<tr class='tab_bg_1'>";
         echo "<th width='15%'>";
         echo __('Name')."&nbsp;:";
         echo "</th>";
         echo "<th width='35%'>";
         echo $registryData['name'];
         echo "</th>";
         echo "<td>";
         echo __('TODOActive')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($registryData['is_active']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo __('Manufacturer')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getDropdownName('glpi_manufacturers', $registryData["manufacturers_id"]);
         echo "</td>";
         echo "<td>";
         echo __('TODOUp to date', 'fusioninventory')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($registryData['uptodate']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo __('TODOVersion')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo $registryData['version'];
         echo "</td>";
         echo "<td colspan='2'>";
         echo "</td>";
         echo "</tr>";     
      }
      echo "</table>";
      return TRUE;
   }



   /**
   * Delete registry on computer
   *
   * @param $items_id integer id of the computer
   *
   * @return nothing
   *
   **/
   static function cleanComputer($items_id) {
      $pfInventoryComputerRegistry = new PluginFusioninventoryInventoryComputerRegistry();
      $a_registry = $pfInventoryComputerRegistry->find("`computers_id`='".$items_id."'");
      if (count($a_registry) > 0) {
         $input = current($a_registry);
         $pfInventoryComputerRegistry->delete($input);
      }
   }
}

?>