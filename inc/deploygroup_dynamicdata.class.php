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
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate
          && $item->fields['type'] == PluginFusioninventoryDeployGroup::DYNAMIC_GROUP) {
         return array(_n('Criterion', 'Criteria', 2), _n('Associated item','Associated items', 2));
      }
      return '';
   }



   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      switch ($tabnum) {

         case 0:
            $search_params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($item, false);
            if (isset($search_params['metacriteria']) && empty($search_params['metacriteria'])) {
               unset($search_params['metacriteria']);
            }
            PluginFusioninventoryDeployGroup::showCriteria($item, $search_params);
            return TRUE;

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
            return TRUE;

      }
      return FALSE;
   }



   /**
    * Display list of computers in the group
    *
    * @param string $itemtype
    * @param array $params
    * @param array $forcedisplay
    */
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



   /**
    * Get data, so computer list
    *
    * @param string $itemtype
    * @param array $params
    * @param array $forcedisplay
    * @return array
    */
   static function getDatas($itemtype, $params, array $forcedisplay=array()) {
      $data = Search::prepareDatasForSearch($itemtype, $params, $forcedisplay);
      Search::constructSQL($data);
      Search::constructDatas($data);

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
      $ids = array();

      if (!$use_cache || !$ids = self::retrieveCache($group)) {
         $search_params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($group, false,true);
         if (isset($search_params['metacriteria']) && empty($search_params['metacriteria'])) {
            unset($search_params['metacriteria']);
         }

         //force no sort (Search engine will sort by id) for better performance
         $search_params['sort'] = '';

         //Only retrieve computers IDs
         $results = self::getDatas(
            'PluginFusioninventoryComputer',
            $search_params,
            array('2')
         );

         $results = Search::prepareDatasForSearch('PluginFusioninventoryComputer', $search_params, array('2'));
         Search::constructSQL($results);
         $results['sql']['search'] = str_replace("`mainitemtype` = 'PluginFusioninventoryComputer'",
              "`mainitemtype` = 'Computer'", $results['sql']['search']);
         Search::constructDatas($results);

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

      $query = "UPDATE ".self::getTable()."
                SET `computers_id_cache` = '".$DB->escape(json_encode($ids))."'
                WHERE `plugin_fusioninventory_deploygroups_id` = '".$group->getID()."'";
      return $DB->query($query);
   }

   /**
    * Retrieve the id of computer stored in db for a group
    * @param  PluginFusioninventoryDeployGroup $group the instance of the group
    * @return array                            the list of compuers id
    */
   static function retrieveCache(PluginFusioninventoryDeployGroup $group) {
      global $DB;

      $ids  = false;
      $data = getAllDatasFromTable(self::getTable(),
                                   "`plugin_fusioninventory_deploygroups_id` = '".$group->getID()."'");
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

      $groups = $pfDynamicGroup->find("`plugin_fusioninventory_deploygroups_id`='$source_deploygroups_id'");
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

?>
