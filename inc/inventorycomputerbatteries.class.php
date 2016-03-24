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

class PluginFusioninventoryInventoryComputerBatteries extends CommonDBTM {

   static $rightname = 'computer';


   static function getTypeName($nb=0) {
      return __('Batterie', 'fusioninventory');
   }



   function getSearchOptions() {

      $tab = array();
      $tab['common'] = __('Characteristics');


//      $tab[1]['table']         = $this->getTable();
//      $tab[1]['field']         = 'version';
//      $tab[1]['name']          = "Version";
//      $tab[1]['type']          = 'text';

      return $tab;
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType() == 'Computer') {
         if (Session::haveRight('computer', READ)) {
            $a_antivirus = $this->find("`computers_id`='".$item->getID()."'", '', 1);
            if (count($a_antivirus) > 0) {
               return self::createTabEntry(__('Batterie', 'fusioninventory'));

            }
         }
      }
      return '';
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         $pfBatteries = new self();
         $pfBatteries->showForm($item->getID());
      }

      return TRUE;
   }



   /**
   * Display form for antivirus
   *
   * @param $items_id integer ID of the antivirus
   * @param $options array
   *
   * @return bool TRUE if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {

      $a_batteries = $this->find("`computers_id`='".$items_id."'");

      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
      echo "<tr>";
      echo "<th colspan='4'>".__('Antivirus', 'fusioninventory');

      echo "</th>";
      echo "</tr>";

      foreach ($a_batteries as $batteryData) {
         echo "<tr class='tab_bg_1'>";
         echo "<th width='15%'>";
         echo __('Name')."&nbsp;:";
         echo "</th>";
         echo "<th width='35%'>";
         echo $batteryData['name'];
         echo "</th>";
         echo "<td>";
         echo __('Active')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($batteryData['is_active']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo __('Manufacturer')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getDropdownName('glpi_manufacturers', $batteryData["manufacturers_id"]);
         echo "</td>";
         echo "<td>";
         echo __('Up to date', 'fusioninventory')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($batteryData['uptodate']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo __('Version')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo $batteryData['version'];
         echo "</td>";
         echo "<td colspan='2'>";
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      return TRUE;
   }



   /**
   * Delete batteries on computer
   *
   * @param $items_id integer id of the computer
   *
   * @return nothing
   *
   **/
   static function cleanComputer($items_id) {
      $pfInventoryComputerBatteries = new PluginFusioninventoryInventoryComputerBatteries();
      $a_batteries = $pfInventoryComputerBatteries->find("`computers_id`='".$items_id."'");
      if (count($a_batteries) > 0) {
         $input = current($a_batteries);
         $pfInventoryComputerBatteries->delete($input);
      }
   }
}

?>
