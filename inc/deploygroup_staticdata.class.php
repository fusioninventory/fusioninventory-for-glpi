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
 * This file is used to manage the static groups (add manually computers
 * in the group).
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
 * Manage the static groups (add manually computers in the group).
 */
class PluginFusioninventoryDeployGroup_Staticdata extends CommonDBRelation{

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = "plugin_fusioninventory_group";

   /**
    * Itemtype for the first part of relation
    *
    * @var string
    */
   static public $itemtype_1 = 'PluginFusioninventoryDeployGroup';

   /**
    * id field name for the first part of relation
    *
    * @var string
    */
   static public $items_id_1 = 'groups_id';

   /**
    * Itemtype for the second part of relation
    *
    * @var string
    */
   static public $itemtype_2 = 'itemtype';

   /**
    * id field name for the second part of relation
    *
    * @var string
    */
   static public $items_id_2 = 'items_id';

   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string|array name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate
          && ($item->getType() == 'PluginFusioninventoryDeployGroup')
             && $item->fields['type'] == PluginFusioninventoryDeployGroup::STATIC_GROUP) {

         $tabs[1] = _n('Criterion', 'Criteria', 2);
         $count = countElementsInTable(getTableForItemType(__CLASS__),
                                  "`itemtype`='Computer'
                                    AND `plugin_fusioninventory_deploygroups_id`='".$item->getID()."'");
         if ($_SESSION['glpishow_count_on_tabs']) {
            $tabs[2] = self::createTabEntry(_n('Associated item','Associated items', $count), $count);
         } else {
            $tabs[2] = _n('Associated item','Associated items', $count);
         }
         return $tabs;
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

         case 1:
            self::showCriteriaAndSearch($item);
            return true;

         case 2:
            self::showResults();
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
      $search_params = PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($item, true);
      //If metacriteria array is empty, remove it as it displays the metacriteria form,
      //and it's is not we want !
      if (isset($search_params['metacriteria']) && empty($search_params['metacriteria'])) {
         unset($search_params['metacriteria']);
      }
      PluginFusioninventoryDeployGroup::showCriteria($item, $search_params);

      //Add extra parameters for massive action display : only the Add action should be displayed
      $search_params['massiveactionparams']['extraparams']['id']                    = $item->getID();
      $search_params['massiveactionparams']['extraparams']['custom_action']         = 'add_to_group';
      $search_params['massiveactionparams']['extraparams']['massive_action_fields'] = ['action', 'id'];

      $data = Search::prepareDatasForSearch('PluginFusioninventoryComputer', $search_params);
      Search::constructSQL($data);
      Search::constructDatas($data);
      $data['search']['target'] = PluginFusioninventoryDeployGroup::getSearchEngineTargetURL($item->getID(), false);
      Search::displayDatas($data);
   }



   /**
    * Display result, so list of computers
    */
   static function showResults() {
      if (isset($_SESSION['glpisearch']['PluginFusioninventoryComputer'])
              && isset($_SESSION['glpisearch']['PluginFusioninventoryComputer']['show_results'])) {
         $computers_params = $_SESSION['glpisearch']['PluginFusioninventoryComputer'];
      }
      $computers_params['metacriteria'] = array();
      $computers_params['criteria'][]   = array('searchtype' => 'equals',
                                                'value' => $_GET['id'],
                                                'field' => 6000);

      $search_params = Search::manageParams('PluginFusioninventoryComputer', $computers_params);

      //Add extra parameters for massive action display : only the Delete action should be displayed
      $search_params['massiveactionparams']['extraparams']['id'] = $_GET['id'];
      $search_params['massiveactionparams']['extraparams']['custom_action'] = 'delete_from_group';
      $search_params['massiveactionparams']['extraparams']['massive_action_fields'] = array('action', 'id');
      $data = Search::prepareDatasForSearch('PluginFusioninventoryComputer', $search_params);
      Search::constructSQL($data);
      Search::constructDatas($data);
      $data['search']['target'] = PluginFusioninventoryDeployGroup::getSearchEngineTargetURL($_GET['id'], false);
      Search::displayDatas($data);
   }

   /**
   * Duplicate entries from one group to another
   * @param $source_deploygroups_id the source group ID
   * @param $target_deploygroups_id the target group ID
   * @return the duplication status, as a boolean
   */
   static function duplicate($source_deploygroups_id, $target_deploygroups_id) {
      $result        = true;
      $pfStaticGroup = new self();

      $groups = $pfStaticGroup->find("`plugin_fusioninventory_deploygroups_id`='$source_deploygroups_id'");
      foreach ($groups as $group) {
         unset($group['id']);
         $group['plugin_fusioninventory_deploygroups_id']
            = $target_deploygroups_id;
         if (!$pfStaticGroup->add($group)) {
            $result |= false;
         }
      }
      return $result;
   }
}
