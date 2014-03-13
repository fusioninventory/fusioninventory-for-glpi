<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

   // From CommonDBChild
   static public $itemtype = 'PluginFusioninventoryDeployGroup';
   static public $items_id = 'plugin_fusioninventory_deploygroups_id';

   static function canCreate() {
      return TRUE;
   }

   static function canView() {
      return TRUE;
   }
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate
          && $item->fields['type'] == PluginFusioninventoryDeployGroup::DYNAMIC_GROUP) {
         return array (__('Search'), __('Associated Items'));
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
            $params = self::getSearchParamsAsAnArray($item);
            Toolbox::logDebug($params);
            PluginFusioninventoryDeployGroup::showSearchForComputers($item, $params);
            break;
         case 1:
            self::showResults($item);
            break;
      }

      return true;
   }

   static function showResults(PluginFusioninventoryDeployGroup $group) {
      global $DEPLOY_MASSIVEACTION_OPTIONS;
      
      $DEPLOY_MASSIVEACTION_OPTIONS = 'view';
      
      $computers_params['criteria']     = self::getSearchParamsAsAnArray($group);
      $computers_params['metacriteria'] = array();
      $search_params                    = Search::manageParams('PluginFusioninventoryComputer', $computers_params);
      Search::showList('PluginFusioninventoryComputer', $search_params);
   }
   
   static function getSearchParamsAsAnArray(PluginFusioninventoryDeployGroup $group) {
      global $DB;
      $computers_params = array();
      if ($group->fields['type'] == PluginFusioninventoryDeployGroup::DYNAMIC_GROUP) {
         $query = "SELECT `fields_array` 
                   FROM `glpi_plugin_fusioninventory_deploygroups_dynamicdatas` 
                   WHERE `plugin_fusioninventory_deploygroups_id`='".$group->getID()."'";
         $result = $DB->query($query);
         if ($DB->numrows($result) > 0) {
            $fields_array = $DB->result($result, 0, 'fields_array');
            $computers_params = unserialize($fields_array);
         }
      }
      $search_params = Search::manageParams('PluginFusioninventoryComputer', $computers_params);
      return $search_params;
   }
   
}

?>