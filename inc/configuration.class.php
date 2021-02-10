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
 * This file is used to manage the general configuration tabs (display) in
 * plugin Fusioninventory.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
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
 * Manage the general configuration tabs (display) in plugin Fusioninventory.
 */
class PluginFusioninventoryConfiguration extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = "plugin_fusioninventory_configuration";


   /**
    * Define tabs to display on form page
    *
    * @param array $options
    * @return array containing the tabs name
    */
   function defineTabs($options = []) {

      $tabs = [];
      $moduleTabs = [];
      $tabs[1]=__('General setup');
      $tabs[2]=__('Agents modules', 'fusioninventory');

      if (isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'])) {
         $fusionTabs = $tabs;
         $moduleTabForms =
               $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'];
         if (count($moduleTabForms)) {
            foreach ($moduleTabForms as $module=>$form) {
               $plugin = new Plugin;
               if ($plugin->isActivated($module)) {
                  $tabs[] = key($form);
               }
            }
            $moduleTabs = array_diff($tabs, $fusionTabs);
         }
         $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabs'] = $moduleTabs;
      }
      return $tabs;
   }


   /**
    * Display configuration form
    *
    * @param array $options
    * @return true
    */
   function showForm($options = []) {

      $this->initForm($options);

      return true;
   }
}
