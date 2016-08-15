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
 * This file is used to manage the batteries of laptop.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
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
 * Manage the batteries of laptop.
 */
class PluginFusioninventoryInventoryComputerBatteries extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'computer';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb=0) {
      return __('Batterie', 'fusioninventory');
   }



   /**
    * Get search function for the class
    *
    * @return array
    */
   function getSearchOptions() {

      $tab = array();
      $tab['common'] = __('Characteristics');

      return $tab;
   }



   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
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



   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         $pfBatteries = new self();
         $pfBatteries->showForm($item->getID());
         return TRUE;
      }
      return FALSE;
   }



   /**
    * Display form for batteries
    *
    * @param integer $computers_id
    * @param array $options
    * @return true
    */
   function showForm($computers_id, $options=array()) {

      $a_batteries = $this->find("`computers_id`='".$computers_id."'");

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
    * @param integer $computers_id
    */
   static function cleanComputer($computers_id) {
      $pfInventoryComputerBatteries = new PluginFusioninventoryInventoryComputerBatteries();
      $a_batteries = $pfInventoryComputerBatteries->find("`computers_id`='".$computers_id."'");
      if (count($a_batteries) > 0) {
         $input = current($a_batteries);
         $pfInventoryComputerBatteries->delete($input);
      }
   }
}

?>
