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
 * This file is used to manage the visibility of package by profile.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @author    Alexandre Delaunay
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
 * Manage the visibility of package by profile.
 */
class PluginFusioninventoryDeployPackage_Profile extends CommonDBRelation {

   /**
    * Itemtype for the first part of relation
    *
    * @var string
    */
   static public $itemtype_1          = 'PluginFusioninventoryDeployPackage';

   /**
    * id field name for the first part of relation
    *
    * @var string
    */
   static public $items_id_1          = 'plugin_fusioninventory_deploypackages_id';

   /**
    * Itemtype for the second part of relation
    *
    * @var string
    */
   static public $itemtype_2          = 'Profile';

   /**
    * id field name for the second part of relation
    *
    * @var string
    */
   static public $items_id_2          = 'profiles_id';

   /**
    * Set we don't check parent right of the second item
    *
    * @var integer
    */
   static public $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;

   /**
    * Logs for the second item are disabled
    *
    * @var type
    */
   static public $logs_for_item_2     = false;


   /**
    * Get profiles for a deploypackage
    *
    * @global object $DB
    * @param integer $deploypackages_id ID of the deploypackage
    * @return array list of profiles linked to a deploypackage
   **/
   static function getProfiles($deploypackages_id) {
      global $DB;

      $prof  = [];
      $query = "SELECT `glpi_plugin_fusioninventory_deploypackages_profiles`.*
                FROM `glpi_plugin_fusioninventory_deploypackages_profiles`
                WHERE `plugin_fusioninventory_deploypackages_id` = '$deploypackages_id'";

      foreach ($DB->request($query) as $data) {
         $prof[$data['profiles_id']][] = $data;
      }
      return $prof;
   }
}
