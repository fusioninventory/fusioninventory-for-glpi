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
class PluginFusioninventoryCollectCommon extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_collect';

   public $type = '';

   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return '';
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
         if ($item->fields['type'] == $this->type) {
            return __('Collect configuration');
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
      $class     = get_called_class();
      $pfCollect = new $class();
      $pfCollect->showList($item->fields['id']);
      $pfCollect->showForm($item->fields['id']);
      return true;
   }

   /**
   * Get headers to be displayed, as an array
   * @since 9.2+2.0
   *
   * @return array a list of header labels to be displayed
   */
   function getListHeaders() {
      return [
         __('Name')
      ];
   }

   /**
   * Get values for a row to display in the list
   * @since 9.2+2.0
   *
   * @param array $row the row data to be displayed
   * @return array values to be display
   */
   function displayOneRow($row = []) {
      return [
         $row['name']
      ];
   }

   /**
    * Display registries defined in collect
    *
    * @param integer $collects_id id of collect
    */
   function showList($collects_id) {
      global $DB;
      $params = [
         'FROM'  => $this->getTable(),
         'WHERE' => ['plugin_fusioninventory_collects_id' => $collects_id]
      ];
      $iterator = $DB->request($params);

      $class = get_called_class();

      $headers = $this->getListHeaders();

      echo "<div class='spaced'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th colspan=".count($headers).">"
         .__('Windows registry associated', 'fusioninventory')."</th>";
      echo "</tr>";
      echo "<tr>";
      foreach ($headers as $label) {
         echo "<th>".$label."</th>";
      }
      echo "</tr>";
      while ($data = $iterator->next()) {
         echo "<tr>";
         $row_data = $this->displayOneRow($data);
         foreach ($row_data as $value) {
            echo "<td align='center'>$value</td>";
         }
         echo "<td align='center'>";
         echo "<form name='form_bundle_item' action='".$class::getFormURL().
                   "' method='post'>";
         echo Html::hidden('id', ['value' => $data['id']]);
         echo "<input type='image' name='delete' src='../pics/drop.png'>";
         Html::closeForm();
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      echo "</div>";
   }


   function displayNewSpecificities() {

   }

   /**
    * Display form to add registry
    *
    * @param integer $collects_id id of collect
    * @param array $options
    * @return true
    */
   function showForm($collects_id, $options = []) {
      $this->initForm(0, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Name');
      echo "</td>";
      echo "<td>";
      echo Html::hidden('plugin_fusioninventory_collects_id',
                        ['value' => $collects_id]);
      Html::autocompletionTextField($this, 'name');
      echo "</td>";
      $this->displayNewSpecificities();

      echo "</tr>\n";

      $this->showFormButtons($options);

      return true;
   }

   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id'           => 'common',
         'name'         => __('Characteristics')
      ];

      $tab[] = [
         'id'           => '1',
         'table'        => $this->getTable(),
         'field'        => 'name',
         'name'         => __('Name'),
         'datatype'     => 'itemlink',
         'autocomplete' => true,
      ];

      return $tab;
   }
}
