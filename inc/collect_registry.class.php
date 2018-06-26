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
 * This file is used to manage the windows registry collect on agent
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
 * Manage the windows registry to get in collect module.
 */
class PluginFusioninventoryCollect_Registry extends PluginFusioninventoryCollectCommon {

   public $type = 'registry';

   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return _n('Found entry', 'Found entries', $nb, 'fusioninventory');
   }

   /**
    * Get Hives of the registry
    *
    * @return array list of hives
    */
   static function getHives() {
      return [
         "HKEY_LOCAL_MACHINE"  => "HKEY_LOCAL_MACHINE",
      ];
   }

   function getListHeaders() {
      return [
         __('Name'),
         __('Hive', 'fusioninventory'),
         __("Path", "fusioninventory"),
         __("Key", "fusioninventory"),
         __("Action")
      ];
   }

   function displayOneRow($row = []) {
      return [
         $row['name'],
         $row['hive'],
         $row['path'],
         $row['key']
      ];
   }

   function displayNewSpecificities() {
      echo "<td>".__('Hive', 'fusioninventory')."</td>";
      echo "<td>";
      Dropdown::showFromArray('hive',
                              PluginFusioninventoryCollect_Registry::getHives());
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Path', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='path' value='' size='80' />";
      echo "</td>";
      echo "<td>";
      echo __('Key', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='key' value='' />";
      echo "</td>";
   }


}

