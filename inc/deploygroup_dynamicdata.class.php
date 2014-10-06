<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   @author    Alexandre Delaunay
   @co-author
   @copyright Copyright (c) 2010-2014 FusionInventory team
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

class PluginFusioninventoryDeployGroup_Dynamicdata extends CommonDBChild {

   static $rightname = "plugin_fusioninventory_group";

   // From CommonDBChild
   static public $itemtype = 'PluginFusioninventoryDeployGroup';
   static public $items_id = 'plugin_fusioninventory_deploygroups_id';

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate
          && $item->fields['type'] == PluginFusioninventoryDeployGroup::DYNAMIC_GROUP) {
         return array (_n('Criterion', 'Criteria', 2), _n('Associated item','Associated items', 2));
      }
      return '';
   }

   /**
    * @param $item         CommonGLPI object
    * @param $tabnum       (default 1)
    * @param $withtemplate (default 0)
   **/
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      switch ($tabnum) {
         case 0:
            $search_params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($item, false);
            if (isset($search_params['metacriteria']) && empty($search_params['metacriteria'])) {
               unset($search_params['metacriteria']);
            }
            PluginFusioninventoryDeployGroup::showCriteria($item, true, $search_params);
            break;
         case 1:
            $params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($item, false);
            $params['massiveactionparams']['extraparams']['id'] = $_GET['id'];
            $params['sort'] = '';
            Search::showList('PluginFusioninventoryComputer', $params, array('2'));
            break;
      }

      return true;
   }

   /**
   * Get computers belonging to a dynamic group
   * @since 0.85+1.0
   * @param group the group object
   * @return an array of computer ids
   */
   static function getTargetsByGroup(PluginFusioninventoryDeployGroup $group) {
      $search_params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($group, false,true);
      if (isset($search_params['metacriteria']) && empty($search_params['metacriteria'])) {
         unset($search_params['metacriteria']);
      }
      $search_params['sort'] = '';

      //Only retrieve computers IDs
      $results = Search::getDatas(
         'PluginFusioninventoryComputer',
         $search_params,
         array('2')
      );

      $ids     = array();
      foreach ($results['data']['rows'] as $id => $row) {
         $ids[$row['id']] = $row['id'];
      }
      return $ids;
   }

}

?>
