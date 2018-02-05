<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the wmi to get in collect module.
 */
class PluginFusioninventoryCollect_Wmi extends CommonDBTM {

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
   static function getTypeName($nb = 0) {
      return __('Windows WMI', 'fusioninventory');
   }


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if ($item->getID() > 0) {
         if ($item->fields['type'] == 'wmi') {
            return __('Windows WMI', 'fusioninventory');
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
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      $pfCollect_Wmi->showWmi($item->getID());
      $pfCollect_Wmi->showForm($item->getID());
      return true;
   }


   /**
    * Display wmi information of collect id
    *
    * @param integer $collects_id id of collect
    */
   function showWmi($collects_id) {
      $content = $this->find("`plugin_fusioninventory_collects_id`='".
                              $collects_id."'");
      echo "<div class='spaced'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th colspan=5>".__('Windows WMI associated', 'fusioninventory')."</th>";
      echo "</tr>";
      echo "<tr>
      <th>".__("Name")."</th>
      <th>".__("Moniker", "fusioninventory")."</th>
      <th>".__("Class", "fusioninventory")."</th>
      <th>".__("Properties", "fusioninventory")."</th>
      <th>".__("Action")."</th>
      </tr>";
      foreach ($content as $data) {
         echo "<tr>";
         echo "<td align='center'>".$data['name']."</td>";
         echo "<td align='center'>".$data['moniker']."</td>";
         echo "<td align='center'>".$data['class']."</td>";
         echo "<td align='center'>".$data['properties']."</td>";
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
    * Display form to add collect wmi
    *
    * @param integer $collects_id id of collect
    * @param array $options
    * @return true
    */
   function showForm($collects_id, $options = []) {

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
      echo "<input type='text' name='name' value='' />";
      echo "</td>";
      echo "<td>".__('moniker', 'fusioninventory')."</td>";
      echo "<td>";
      echo "<input type='text' name='moniker' value='' size='50' />";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Class', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='class' value='' />";
      echo "</td>";
      echo "<td>";
      echo __('Properties', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='properties' value='' size='50' />";
      echo "</td>";
      echo "</tr>\n";

      $this->showFormButtons($options);

      return true;
   }


}

