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
 * This file is used to manage the files found on computr by agent and
 * linked to the computer
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
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
 * Manage the files found by the collect module of agent.
 */
class PluginFusioninventoryCollectContentCommon extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname        = 'plugin_fusioninventory_collect';
   public $collect_itemtype = '';
   public $collect_table    = '';
   public $type             = '';

   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      $class = get_called_class();
      return $class::getTypeName();
   }

   /**
    * Get the collect associated with the content class
    * @since 9.2+2.0
    *
    * @return string the collect class name
    */
   function getCollectClass() {
      $class = get_called_class();
      $item  = new $class();
      return $item->collect_itemtype;
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
      $class            = get_called_class();
      $pfCollectContent = new $class();
      switch (get_class($item)) {
         case 'PluginFusioninventoryCollect':
            $pfCollectContent->showForCollect($item->fields['id']);
            break;
      }
      return true;
   }

   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if ($item->fields['id'] > 0) {
         $class   = $this->collect_itemtype;
         $collect = $this->getCollectClass();
         switch (get_class($item)) {
            case 'PluginFusioninventoryCollect':
               if ($item->fields['type'] == $this->type) {
                  $a_colfiles = getAllDataFromTable($collect::getTable(),
                     ['plugin_fusioninventory_collects_id' => $item->fields['id']]);
                  if (count($a_colfiles) == 0) {
                     return '';
                  }
                  $in = array_keys($a_colfiles);
                  $fk = getForeignKeyFieldForItemType($collect);
                  if ($nb = countElementsInTable($this->getTable(),
                        [$fk => $in]) > 0) {
                     return self::createTabEntry($collect::getTypeName(Session::getPluralNumber()), $nb);
                  }
               }
               break;
         }
      }
      return '';
   }

   /**
    * Delete all contents linked to the computer (most cases when delete a
    * computer)
    *
    * @param integer $computers_id
    */
   static function cleanComputer($computers_id) {
      $classname = get_called_class();
      $content   = new $classname();
      $content->deleteByCriteria(['computers_id' => $computers_id]);
   }

   /**
    * Show all files defined
    *
    * @param integer $collects_id id of collect
    */
   function showForCollect($collects_id) {
      global $DB;
      $class  = $this->collect_itemtype;
      $params = [
         'FROM'   => $class::getTable(),
         'FIELDS' => [
            'id'
         ],
         'WHERE'  => [
            'plugin_fusioninventory_collects_id' => $collects_id
         ]
      ];
      $iterator = $DB->request($params);
      while ($data = $iterator->next()) {
         $this->showContent($data['id']);
      }
   }

   function showContent($id) {

   }
}
