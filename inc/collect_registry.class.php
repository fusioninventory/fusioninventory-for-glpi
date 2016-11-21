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
 * This file is used to manage the windows registry collect on agent
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
 * Manage the windows registry to get in collect module.
 */
class PluginFusioninventoryCollect_Registry extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_collect';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb=0) {
      return __('Windows registry', 'fusioninventory');
   }



   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getID() > 0) {
         if ($item->fields['type'] == 'registry') {
            return __('Windows registry', 'fusioninventory');
         }
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
      $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
      $pfCollect_Registry->showRegistry($item->getID());
      $pfCollect_Registry->showForm($item->getID());
      return TRUE;
   }



   /**
    * Get Hives of the registry
    *
    * @return array list of hives
    */
   static function getHives() {
      $hives = array(
//         "HKEY_CLASSES_ROOT"   => "HKEY_CLASSES_ROOT",
//         "HKEY_CURRENT_USER"   => "HKEY_CURRENT_USER",
         "HKEY_LOCAL_MACHINE"  => "HKEY_LOCAL_MACHINE",
//         "HKEY_USERS"          => "HKEY_USERS",
//         "HKEY_CURRENT_CONFIG" => "HKEY_CURRENT_CONFIG",
//         "HKEY_DYN_DATA"       => "HKEY_DYN_DATA"
      );
      return $hives;
   }



   /**
    * Display registries defined in collect
    *
    * @param integer $collects_id id of collect
    */
   function showRegistry($collects_id) {

      $content = $this->find("`plugin_fusioninventory_collects_id`='".
                              $collects_id."'");

      echo "<div class='spaced'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th colspan=5>".__('Windows registry associated', 'fusioninventory')."</th>";
      echo "</tr>";
      echo "<tr>
      <th>".__("Name")."</th>
      <th>".__("Hive", "fusioninventory")."</th>
      <th>".__("Path", "fusioninventory")."</th>
      <th>".__("Key", "fusioninventory")."</th>
      <th>".__("Action")."</th>
      </tr>";
      foreach ($content as $data) {
         echo "<tr>";
         echo "<td align='center'>".$data['name']."</td>";
         echo "<td align='center'>".$data['hive']."</td>";
         echo "<td align='center'>".$data['path']."</td>";
         echo "<td align='center'>".$data['key']."</td>";
         echo "<td align='center'>
            <form name='form_bundle_item' action='".Toolbox::getItemTypeFormURL(__CLASS__).
                   "' method='post'>
            <input type='hidden' name='id' value='".$data['id']."'>
            <input type='image' name='delete' src='../pics/drop.png'>";
         Html::closeForm();
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      echo "</div>";
   }



   /**
    * Display form to add registry
    *
    * @param integer $collects_id id of collect
    * @param array $options
    * @return true
    */
   function showForm($collects_id, $options=array()) {

      $ID = 0;

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Name');
      echo "</td>";
      echo "<td>";
      echo "<input type='hidden' name='plugin_fusioninventory_collects_id'
               value='".$collects_id."' />";
      Html::autocompletionTextField($this,'name');
      echo "</td>";
      echo "<td>".__('Hive', 'fusioninventory')."</td>";
      echo "<td>";
      Dropdown::showFromArray('hive', PluginFusioninventoryCollect_Registry::getHives());
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Path', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='path' value='' size='80' />";
      echo "</td>";
      echo "<td>";
      echo __('Key', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='key' value='' />";
      echo "</td>";
      echo "</tr>\n";

      $this->showFormButtons($options);

      return TRUE;
   }
}

?>
