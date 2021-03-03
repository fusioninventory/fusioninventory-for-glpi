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
 * This file is used to manage the computer inventory stats (number of
 * inventories arrived in the plugin Fusioninventory and regroued by hour).
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
 * Manage the computer inventory stats (number of inventories arrived in
 * the plugin Fusioninventory and regroued by hour).
 */
class PluginFusioninventoryInventoryComputerStat extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_agent';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return "Stat";
   }


   /**
    * Init stats
    *
    * @global object $DB
    */
   static function init() {
      global $DB;

      $insert = $DB->buildInsert(
         'glpi_plugin_fusioninventory_inventorycomputerstats', [
            'day'    => new \QueryParam(),
            'hour'   => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($insert);

      for ($d=1; $d<=365; $d++) {
         for ($h=0; $h<24; $h++) {

            $stmt->bind_param(
               'ss',
               $d,
               $h
            );
            $stmt->execute();
         }
      }
      mysqli_stmt_close($stmt);
   }


   /**
    * Increment computer states
    *
    * @global object $DB
    */
   static function increment() {
      global $DB;

      $DB->update(
         'glpi_plugin_fusioninventory_inventorycomputerstats', [
            'counter'   => new \QueryExpression($DB->quoteName('counter') . ' + 1')
         ], [
            'day'    => date('z'),
            'hour'   => date('G')
         ]
      );
   }


   /**
    * Get stats for each hours for last xx hours
    *
    * @global object $DB
    * @param integer $nb
    * @return integer
    */
   static function getLastHours($nb = 11) {
      global $DB;

      $a_counters = [];
      $a_counters['key'] = 'test';

      $timestamp = date('U');
      for ($i=$nb; $i>=0; $i--) {
         $timestampSearch = $timestamp - ($i * 3600);
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_inventorycomputerstats` "
                    ."WHERE `day`='".date('z', $timestampSearch)."' "
                    ."   AND `hour`='".date('G', $timestampSearch)."' "
                    ."LIMIT 1";
         $result = $DB->query($query);
         $data = $DB->fetchAssoc($result);
         $cnt = 0;
         if (!is_null($data)) {
            $cnt = (int)$data['counter'];
         }
         $a_counters['values'][] = [
             'label' => date('H', $timestampSearch).":00",
             'value' => $cnt
         ];
      }
      return $a_counters;
   }
}
