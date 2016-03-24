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
   @since     2014

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryInventoryComputerStat extends CommonDBTM {

   static $rightname = 'plugin_fusioninventory_agent';

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return "Stat";
   }


   static function init() {
      global $DB;

      for ($d=1; $d<=365; $d++) {
         for ($h=0; $h<24; $h++) {
            $query = "INSERT INTO `glpi_plugin_fusioninventory_inventorycomputerstats` "
                    ."(`day`, `hour`) "
                    ."VALUES ('".$d."', '".$h."')";
            $DB->query($query);
         }
      }
   }



   static function increment() {
      global $DB;

      $query = "UPDATE `glpi_plugin_fusioninventory_inventorycomputerstats` "
                 ."SET `counter` = counter + 1 "
                 ."WHERE `day`='".date('z')."' "
                 ."   AND `hour`='".date('G')."'";
      $DB->query($query);
   }



   static function getLastHours($nb=11) {
      global $DB;

      $a_counters = array();
      $a_counters['key'] = 'test';

      $timestamp = date('U');
      for ($i=$nb; $i>=0; $i--) {
         $timestampSearch = $timestamp - ($i * 3600);
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_inventorycomputerstats` "
                    ."WHERE `day`='".date('z', $timestampSearch)."' "
                    ."   AND `hour`='".date('G', $timestampSearch)."' "
                    ."LIMIT 1";
         $result = $DB->query($query);
         $data = $DB->fetch_assoc($result);
         $a_counters['values'][] = array(
             'label' => date('G', $timestampSearch)." ".__('hour'),
             'value' => (int)$data['counter']
         );
      }
      return $a_counters;
   }
}

?>
