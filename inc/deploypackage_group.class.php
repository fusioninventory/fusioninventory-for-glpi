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
   @co-author Alexandre Delaunay
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

class PluginFusioninventoryDeployPackage_Group extends CommonDBRelation {

   // From CommonDBRelation
   static public $itemtype_1          = 'PluginFusioninventoryDeployPackage';
   static public $items_id_1          = 'plugin_fusioninventory_deploypackages_id';
   static public $itemtype_2          = 'Group';
   static public $items_id_2          = 'groups_id';

   static public $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
   static public $logs_for_item_2     = false;


   /**
    * Get groups for a deploypackage
    *
    * @param $deploypackages_id ID of the deploypackage
    *
    * @return array of groups linked to a deploypackage
   **/
   static function getGroups($deploypackages_id) {
      global $DB;

      $groups = array();
      $query  = "SELECT `glpi_plugin_fusioninventory_deploypackages_groups`.*
                 FROM `glpi_plugin_fusioninventory_deploypackages_groups`
                 WHERE plugin_fusioninventory_deploypackages_id = '$deploypackages_id'";

      foreach ($DB->request($query) as $data) {
         $groups[$data['groups_id']][] = $data;
      }
      return $groups;
   }

}
?>