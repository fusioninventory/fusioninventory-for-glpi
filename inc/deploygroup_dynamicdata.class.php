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
   @author    Alexandre Delaunay
   @co-author David Durieux
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
            PluginFusioninventoryDeployGroup::showCriteria($item, $search_params);
            break;

         case 1:
            $params_dyn = array();
            foreach (array('sort', 'order', 'start') as $field) {
               if (isset($_SESSION['glpisearch']['PluginFusioninventoryComputer'][$field])) {
                  $params_dyn[$field] = $_SESSION['glpisearch']['PluginFusioninventoryComputer'][$field];
               }
            }
            $params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($item, false);
            $params['massiveactionparams']['extraparams']['id'] = $_GET['id'];

            foreach ($params_dyn as $key => $value) {
               $params[$key] = $value;
            }

            if (isset($params['metacriteria']) && !is_array($params['metacriteria'])) {
               $params['metacriteria'] = array();
            }

            $params['target'] = Toolbox::getItemTypeFormURL("PluginFusioninventoryDeployGroup" , true).
                                "?id=".$item->getID();
            self::showList('PluginFusioninventoryComputer', $params, array('2', '1'));
            break;

      }

      return true;
   }



   // override Search method to gain performance and decrease memory usage
   // we dont need to display search criteria result
   static function showList($itemtype, $params, $forcedisplay) {
      $_GET['_in_modal'] = true;
      $data = Search::prepareDatasForSearch($itemtype, $params, $forcedisplay);
      Search::constructSQL($data);
      $data['sql']['search'] = str_replace("`mainitemtype` = 'PluginFusioninventoryComputer'",
              "`mainitemtype` = 'Computer'", $data['sql']['search']);
      Search::constructDatas($data);
      if (Session::isMultiEntitiesMode()) {
         $data['data']['cols'] = array_slice($data['data']['cols'], 0, 2);
      } else {
         $data['data']['cols'] = array_slice($data['data']['cols'], 0, 1);
      }
      Search::displayDatas($data);
   }



   // override Search method to gain performance and decrease memory usage
   // we dont need to display search criteria result
   static function getDatas($itemtype, $params, array $forcedisplay=array()) {
      $data = Search::prepareDatasForSearch($itemtype, $params, $forcedisplay);
      Search::constructSQL($data);
      Search::constructDatas($data);

      return $data;
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

      $results = Search::prepareDatasForSearch('PluginFusioninventoryComputer', $search_params, array('2'));
      Search::constructSQL($results);
      $results['sql']['search'] = str_replace("`mainitemtype` = 'PluginFusioninventoryComputer'",
              "`mainitemtype` = 'Computer'", $results['sql']['search']);
      Search::constructDatas($results);

      $ids     = array();
      foreach ($results['data']['rows'] as $row) {
         $ids[$row['id']] = $row['id'];
      }
      return $ids;
   }
}

?>
