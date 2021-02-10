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
 * This file is used to manage the wmi by the agent
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
 * Manage the wmi to get in collect module.
 */
class PluginFusioninventoryCollect_Wmi extends PluginFusioninventoryCollectCommon {

   public $type = 'wmi';

   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return _n('Found WMI', 'Found WMIs', $nb, 'fusioninventory');
   }

   function getListHeaders() {
      return [
         __("Name"),
         __("Moniker", "fusioninventory"),
         __("Class", "fusioninventory"),
         __("Properties", "fusioninventory"),
         __("Action")
      ];
   }

   function displayOneRow($row = []) {
      return [
         $row['name'],
         $row['moniker'],
         $row['class'],
         $row['properties']
      ];
   }

   function displayNewSpecificities() {
      echo "<td>".__('moniker', 'fusioninventory')."</td>";
      echo "<td>";
      echo "<input type='text' name='moniker' value='' size='50' />";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Class', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='class' value='' />";
      echo "</td>";
      echo "<td>";
      echo __('Properties', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='properties' value='' size='50' />";
      echo "</td>";
   }
}
