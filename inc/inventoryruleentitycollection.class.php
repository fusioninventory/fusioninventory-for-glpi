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
 * This file is used to manage the entity rules collection.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
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
 * Manage the entity rules collection.
 */
class PluginFusioninventoryInventoryRuleEntityCollection extends RuleCollection {


   /**
    * Set stop play rules when have the first rule of list match
    *
    * @var boolean
    */
   public $stop_on_first_match=true;

   /**
    * Define the name of menu option
    *
    * @var string
    */
   public $menu_option='test';

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_ruleentity';


   /**
    * Get name of this type by language of the user connected
    *
    * @return string name of this type
    */
   function getTitle() {
      return __('Entity rules', 'fusioninventory');
   }


   /**
    * Prepare input data for process the rule
    *
    * @param array $input
    * @param array $params
    * @return array
    */
   function prepareInputDataForProcess($input, $params) {
      return $input;
   }
}
