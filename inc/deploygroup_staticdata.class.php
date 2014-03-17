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

class PluginFusioninventoryDeployGroup_Staticdata extends CommonDBRelation{

   // From CommonDBRelation
   static public $itemtype_1 = 'PluginFusioninventoryDeployGroup';
   static public $items_id_1 = 'groups_id';

   static public $itemtype_2 = 'itemtype';
   static public $items_id_2 = 'items_id';

   /**
    * @see CommonGLPI::getTabNameForItem()
   **/
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate
          && ($item->getType() == 'PluginFusioninventoryDeployGroup') 
             && $item->fields['type'] == PluginFusioninventoryDeployGroup::STATIC_GROUP) {
              
         $tabs[1] = _n('Criterion', 'Criteria', 2);
         if (countElementsInTable(getTableForItemType(__CLASS__), 
                                  "`itemtype`='Computer' 
                                    AND `plugin_fusioninventory_deploygroups_id`='".$item->getID()."'")) {
            $tabs[2]= _n('Associated item','Associated items', 2);
         }
         return $tabs;
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
         case 1:
            $search_params = Search::manageParams('PluginFusioninventoryComputer', $item->getSearchParams());
            PluginFusioninventoryDeployGroup::showCriteria($item, true, $search_params);
            if ($item->isPreviewRequest()) {

               Search::showList('PluginFusioninventoryComputer', $search_params);
            }
            break;
         case 2:
            self::showResults($item);
            break;
      }

      return true;
   }
   
   static function showResults(PluginFusioninventoryDeployGroup $group) {
      $computers_params['metacriteria'] = array();
      $search_params    = Search::manageParams('PluginFusioninventoryComputer', $computers_params);
      Search::showList('PluginFusioninventoryComputer', $search_params);
   }
}

?>