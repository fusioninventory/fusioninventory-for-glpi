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
 * This file is used to manage the dynamic groups (based on search engine
 * of GLPI).
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Alexandre Delaunay
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
 * Manage the dynamic groups (based on search engine of GLPI).
 */
class PluginFusioninventoryDeployGroup_Dynamicdata extends CommonDBChild {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = "plugin_fusioninventory_group";

   /**
    * Itemtype of the item linked
    *
    * @var string
    */
   static public $itemtype = 'PluginFusioninventoryDeployGroup';

   /**
    * id field of the item linked
    *
    * @var string
    */
   static public $items_id = 'plugin_fusioninventory_deploygroups_id';


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if (!$withtemplate
          && $item->fields['type'] == PluginFusioninventoryDeployGroup::DYNAMIC_GROUP) {
         $tabs[1] = _n('Criterion', 'Criteria', 2);
         // Get the count of matching items
         $count = self::getMatchingItemsCount($item);
         if ($_SESSION['glpishow_count_on_tabs']) {
            $tabs[2] = self::createTabEntry(_n('Associated item', 'Associated items', $count), $count);
         } else {
            $tabs[2] = _n('Associated item', 'Associated items', $count);
         }
         return $tabs;
      }
      return '';
   }


   /**
    * Get the count of items matching the dynamic search criteria
    *
    * This function saves and restores the pagination parameters to avoid breaking the pagination in the
    * query results.
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getMatchingItemsCount(CommonGLPI $item) {
      // Save pagination parameters
      $pagination_params = [];
      foreach (['sort', 'order', 'start'] as $field) {
         if (isset($_SESSION['glpisearch']['PluginFusioninventoryComputer'][$field])) {
            $pagination_params[$field] = $_SESSION['glpisearch']['PluginFusioninventoryComputer'][$field];
         }
      }

      $params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($item, false);
      $params['massiveactionparams']['extraparams']['id'] = $_GET['id'];
      if (isset($params['metacriteria']) && !is_array($params['metacriteria'])) {
         $params['metacriteria'] = [];
      }
      $params['target'] = PluginFusioninventoryDeployGroup::getSearchEngineTargetURL($_GET['id'], true);

      $data = Search::prepareDatasForSearch('Computer', $params);
      Search::constructSQL($data);

      // Use our specific constructDatas function rather than Glpi function
      PluginFusioninventorySearch::constructDatas($data);

      // Restore pagination parameters
      foreach ($pagination_params as $key => $value) {
         $_SESSION['glpisearch']['PluginFusioninventoryComputer'][$field] = $pagination_params[$field];
      }
      return $data['data']['totalcount'];
   }



   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      switch ($tabnum) {

         case 1:
            self::showCriteriaAndSearch($item);
            return true;

         case 2:
            // Save pagination parameters
            $pagination_params = [];
            foreach (['sort', 'order', 'start'] as $field) {
               if (isset($_SESSION['glpisearch']['PluginFusioninventoryComputer'][$field])) {
                  $pagination_params[$field] = $_SESSION['glpisearch']['PluginFusioninventoryComputer'][$field];
               }
            }
            $params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($item, false);
            $params['massiveactionparams']['extraparams']['id'] = $_GET['id'];
            // Include pagination parameters in the provided parameters
            foreach ($pagination_params as $key => $value) {
               $params[$key] = $value;
            }
            if (isset($params['metacriteria']) && !is_array($params['metacriteria'])) {
               $params['metacriteria'] = [];
            }
            $params['target'] = PluginFusioninventoryDeployGroup::getSearchEngineTargetURL($_GET['id'], true);
            self::showList('PluginFusioninventoryComputer', $params, ['1', '2']);
            return true;

      }
      return false;
   }



   /**
    * Display criteria form + list of computers
    *
    * @param object $item PluginFusioninventoryDeployGroup instance
    */
   static function showCriteriaAndSearch(PluginFusioninventoryDeployGroup $item) {
      // Save pagination parameters
      $pagination_params = [];
      foreach (['sort', 'order', 'start'] as $field) {
         if (isset($_SESSION['glpisearch']['PluginFusioninventoryComputer'][$field])) {
            $pagination_params[$field] = $_SESSION['glpisearch']['PluginFusioninventoryComputer'][$field];
         }
      }
      // WITHOUT checking post values
      $search_params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($item, false);
      //If metacriteria array is empty, remove it as it displays the metacriteria form,
      //and it's is not we want !
      if (isset($search_params['metacriteria']) && empty($search_params['metacriteria'])) {
         unset($search_params['metacriteria']);
      }
      PluginFusioninventoryDeployGroup::showCriteria($item, $search_params);

      /* Do not display the search result on the current tab
       * @mohierf: I do not remove this code if this feature is intended to be reactivated...
       * -----
      // Include pagination parameters in the provided parameters
      foreach ($pagination_params as $key => $value) {
         $search_params[$key] = $value;
      }
      // Add extra parameters for massive action display : only the Add action should be displayed
      $search_params['massiveactionparams']['extraparams']['id']                    = $item->getID();
      $search_params['massiveactionparams']['extraparams']['custom_action']         = 'add_to_group';
      $search_params['massiveactionparams']['extraparams']['massive_action_fields'] = ['action', 'id'];

      $data = Search::prepareDatasForSearch('PluginFusioninventoryComputer', $search_params);
      Search::constructSQL($data);
      Search::constructDatas($data);
      $data['search']['target'] = PluginFusioninventoryDeployGroup::getSearchEngineTargetURL($item->getID(), false);
      Search::displayDatas($data);
      */
   }


   /**
    * Display list of computers in the group
    *
    * @param string $itemtype
    * @param array $params
    * @param array $forcedisplay
    */
   static function showList($itemtype, $params, $forcedisplay) {
      $data = Search::prepareDatasForSearch('Computer', $params, $forcedisplay);
      Search::constructSQL($data);

      // Use our specific constructDatas function rather than Glpi function
      PluginFusioninventorySearch::constructDatas($data);

      // Remove some fields from the displayed columns
      if (Session::isMultiEntitiesMode()) {
         // Remove entity and computer Id
         unset($data['data']['cols'][1]);
         unset($data['data']['cols'][2]);
      } else {
         // Remove computer Id
         unset($data['data']['cols'][1]);
      }
      Search::displayData($data);
   }


   /**
    * Get data, so computer list
    *
    * @param string $itemtype
    * @param array $params
    * @param array $forcedisplay
    * @return array
    */
   static function getDatas($itemtype, $params, array $forcedisplay = []) {
      $data = Search::prepareDatasForSearch('Computer', $params, $forcedisplay);
      Search::constructSQL($data);
      Search::constructData($data);

      return $data;
   }


   /**
    * Get computers belonging to a dynamic group
    *
    * @since 0.85+1.0
    *
    * @param group the group object
    * @param use_cache retrieve computers_id from cache (computers_id_cache field)
    * @return an array of computer ids
    */
   static function getTargetsByGroup(PluginFusioninventoryDeployGroup $group, $use_cache = false) {
      $ids = [];

      if (!$use_cache || !$ids = self::retrieveCache($group)) {
         $search_params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($group, false, true);
         if (isset($search_params['metacriteria']) && empty($search_params['metacriteria'])) {
            unset($search_params['metacriteria']);
         }

         //force no sort (Search engine will sort by id) for better performance
         $search_params['sort'] = '';

         //Only retrieve computers IDs
         $results = self::getDatas(
            'PluginFusioninventoryComputer',
            $search_params,
            ['2']
         );

         $results = Search::prepareDatasForSearch('Computer', $search_params, ['2']);
         Search::constructSQL($results);

         // Use our specific constructDatas function rather than Glpi function
         PluginFusioninventorySearch::constructDatas($results);

         foreach ($results['data']['rows'] as $id => $row) {
            $ids[$row['id']] = $row['id'];
         }

         //store results in cache (for reusing on agent communication)
         self::storeCache($group, $ids);
      }

      return $ids;
   }


   /**
    * Store a set of computers id in db
    * @param  PluginFusioninventoryDeployGroup $group the instance of fi group
    * @param  array                            $ids   the list of id to store
    * @return bool
    */
   static function storeCache(PluginFusioninventoryDeployGroup $group, $ids = []) {
      global $DB;

      $result = $DB->update(
         self::getTable(), [
            'computers_id_cache' => $DB->escape(json_encode($ids))
         ], [
            'plugin_fusioninventory_deploygroups_id' => $group->getID()
         ]
      );
      return $result;
   }


   /**
    * Retrieve the id of computer stored in db for a group
    * @param  PluginFusioninventoryDeployGroup $group the instance of the group
    * @return array                            the list of compuers id
    */
   static function retrieveCache(PluginFusioninventoryDeployGroup $group) {
      global $DB;

      $ids  = false;
      $data = getAllDataFromTable(self::getTable(),
         ['plugin_fusioninventory_deploygroups_id' => $group->getID()]);
      if (count($data)) {
         $first = array_shift($data);
         $ids   = json_decode($first['computers_id_cache'], true);
      }

      return $ids;
   }


   /**
   * Duplicate entries from one group to another
   * @param $source_deploygroups_id the source group ID
   * @param $target_deploygroups_id the target group ID
   * @return the duplication status, as a boolean
   */
   static function duplicate($source_deploygroups_id, $target_deploygroups_id) {
      $result         = true;
      $pfDynamicGroup = new self();

      $groups = $pfDynamicGroup->find(['plugin_fusioninventory_deploygroups_id' => $source_deploygroups_id]);
      foreach ($groups as $group) {
         unset($group['id']);
         $group['plugin_fusioninventory_deploygroups_id']
            = $target_deploygroups_id;
         if (!$pfDynamicGroup->add($group)) {
            $result = false;
         }
      }
      return $result;
   }


}
