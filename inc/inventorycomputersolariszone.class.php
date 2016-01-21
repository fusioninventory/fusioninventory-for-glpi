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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

class PluginFusioninventoryInventoryComputerSolariszone extends CommonDBTM {

   static $rightname = 'computer';

   static function getTypeName($nb=0) {
      return __('Solaris', 'fusioninventory');
   }

   function getSearchOptions() {

      $tab = array();
      $tab['common'] = __('Characteristics');

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = __("Name");
      $tab[1]['type']          = 'text';

      $tab[2]['table']         = $this->getTable();
      $tab[2]['field']         = 'zone_number';
      $tab[2]['name']          = __("Zone number");
      $tab[2]['type']          = 'text';

      $tab[3]['table']         = $this->getTable();
      $tab[3]['field']         = 'zone_max_swap';
      $tab[3]['name']          = __("Zone max swap");
      $tab[3]['type']          = 'text';

      $tab[4]['table']         = $this->getTable();
      $tab[4]['field']         = 'zone_max_locked_memory';
      $tab[4]['name']          = __("Zone max locked memory");
      $tab[4]['type']          = 'text';

      $tab[4]['table']         = $this->getTable();
      $tab[4]['field']         = 'zone_max_shm_memory';
      $tab[4]['name']          = __("Zone max shm memory");
      $tab[4]['type']          = 'text';

      $tab[5]['table']         = $this->getTable();
      $tab[5]['field']         = 'zone_cpu_cap';
      $tab[5]['name']          = __("Zone cpu cap");
      $tab[5]['type']          = 'text';

      $tab[6]['table']         = $this->getTable();
      $tab[6]['field']         = 'zone_dedicated_cpu';
      $tab[6]['name']          = __("Zone dedicated cpu");
      $tab[6]['type']          = 'text';

      return $tab;
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType() == 'ComputerVirtualMachine') {
         if (Session::haveRight('computer', READ)) {
            $condition = "`computers_id`=".$item->fields['computers_id']."
                           AND `uuid`='".$item->fields['uuid']."'";
            if (countElementsInTable($this->getTable(), $condition) > 0) {
               return self::createTabEntry(__('Solaris', 'fusioninventory'));

            }
         }
      } elseif ($item->getType() == 'Computer') {
         if (Session::haveRight('computer', READ)) {
            if (isset($item->fields['uuid']) && ($item->fields['uuid'] != '')) {
	       $where =
"LOWER(`uuid`)".ComputerVirtualMachine::getUUIDRestrictRequest($item->fields[
'uuid']);
               if (countElementsInTable($this->getTable(), $where) > 0) {
                  return self::createTabEntry(__('Solaris', 'fusioninventory'));
	       }
	     }
            }
         }


      return '';
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
         $pfSolarisZone = new self();

      if (($item->getType() == 'ComputerVirtualMachine'
          || $item->getType() == 'Computer') && $item->getID() > 0) {
         $condition = "`uuid`='".$item->fields['uuid']."'";
         $zones = getAllDatasFromTable(self::getTable(), $condition);
         if (!empty($zones)) {
	    $zone = array_pop($zones);
            $pfSolarisZone->showForm($zone['id']);
         }
      }

      return TRUE;
   }

   /**
   * Display form for solaris zone
   *
   * @param $items_id integer ID of the zone
   *
   * @return bool TRUE if form is ok
   *
   **/
   function showForm($items_id) {

      $this->getFromDB($items_id);

      $computer = new Computer();
      $computer->getFromDB($this->fields['computers_id']);

      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
      echo "<tr>";
      echo "<th colspan='2'>".__('Solaris Zone', 'fusioninventory')."</th>";
      echo "<th colspan='2'>".$computer->getLink()."</th>";

      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Zone number')."</td>";
      echo "<td>";
      echo $this->fields['zone_number'];
      echo "</td>";

      echo "<td>".__('Zone max swap')."</td>";
      echo "<td>";
      echo $this->fields['zone_max_swap'];
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Zone max locked memory')."</td>";
      echo "<td>";
      echo $this->fields['zone_max_locked_memory'];
      echo "</td>";

      echo "<td>".__('Zone max shm memory')."</td>";
      echo "<td>";
      echo $this->fields['zone_max_shm_memory'];
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('zone_cpu_cap')."</td>";
      echo "<td>";
      echo $this->fields['zone_cpu_cap'];
      echo "</td>";

      echo "<td>".__('Zone Dedicated CPU')."</td>";
      echo "<td>";
      echo $this->fields['zone_dedicated_cpu'];
      echo "</td></tr>";

      echo "</table>";
      return TRUE;
   }



   /**
   * Delete solariszone on computer
   *
   * @param $items_id integer id of the computer
   *
   * @return nothing
   *
   **/
   static function cleanComputer($items_id) {
      $pfInventoryComputerSolarisZone = new self();
      $pfInventoryComputerSolarisZone->deleteByCriteria(array('computers_id' => $items_id));
   }
}

?>
