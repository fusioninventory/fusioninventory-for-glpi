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
 * This file is used to manage the entity configuration.
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
 * Manage the entity configuration.
 */
class PluginFusioninventoryEntity extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'entity';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return Entity::getTypeName(1);
   }


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if ($item->fields['id'] > -1) {
         if (Session::haveRight("config", READ)) {
            return self::createTabEntry('Fusioninventory');
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

      if ($item->fields['id'] > -1) {
         $pmEntity = new PluginFusioninventoryEntity();
         $pmEntity->showForm($item->fields['id']);
         return true;
      }
      return false;
   }


   /**
    * Display form
    *
    * @global array $CFG_GLPI
    * @param integer $entities_id
    * @param array $options
    * @return true
    */
   function showForm($entities_id, $options = []) {
      global $CFG_GLPI;

      $a_configs = $this->find(['entities_id' => $entities_id], [], 1);
      $id = 0;
      if (count($a_configs) == '1') {
         $a_config = current($a_configs);
         $id = $a_config['id'];
      }

      $this->initForm($id, $options);
      $this->showFormHeader($options);

      echo "<tr>";
      echo "<td colspan='2'>";
      echo __('Model for automatic computers transfer in an other entity', 'fusioninventory').
              "&nbsp:";
      echo "</td>";
      echo "<td colspan='2'>";
      $params = [
          'name'       => 'transfers_id_auto',
          'value'      => $this->fields['transfers_id_auto'],
          'emptylabel' => __('No automatic transfer')
      ];
      if ($entities_id > 0) {
         $params['toadd'] = ['-1' => __('Inheritance of the parent entity')];
      }
      Dropdown::show('Transfer', $params);
      echo Html::hidden('entities_id', ['value' => $entities_id]);
      echo "</td>";
      echo "</tr>";

      // Inheritance
      if ($this->fields['transfers_id_auto'] == '-1') {

         echo "<tr class='tab_bg_1'>";
         // if ($this->fields['transfers_id_auto'] == '-1') {
            echo "<td colspan='2'>";
            echo "</td>";
            echo "<td colspan='2' class='green'>";
            echo __('Inheritance of the parent entity')."&nbsp;:&nbsp;";
            $val = $this->getValueAncestor('transfers_id_auto', $entities_id);

         if ($val == 0) {
            echo __('No automatic transfer');
         } else {
            echo Dropdown::getDropdownName('glpi_transfers', $val);
         }
            echo "</td>";
         // } else {
            // echo "<td colspan='4'>";
            // echo "</td>";
         // }
         echo "</tr>";
      }

      echo "<tr>";
      echo "<td colspan='2'>";
      $value = $this->fields["agent_base_url"];
      $inheritedValue = $this->getValueAncestor('agent_base_url', $entities_id);
      echo __('Service URL', 'fusioninventory').'&nbsp;';
      Html::showToolTip('ex: http://192.168.20.1/glpi');
      echo " : ";

      echo "</td>";
      echo "<td colspan='2'>";
      if (empty($value) && $entities_id == 0) {
         echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" width='20' height='20' alt=\"warning\"> ";
      }
      echo "<input type='text' name='agent_base_url' value='".$value."' size='30'/>";
      echo "</td>";
      echo "</tr>";

      if (empty($value) && !empty($inheritedValue)) {
         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='2'></td>";
         echo "<td colspan='2' class='green'>";
         echo __('Inheritance of the parent entity')."&nbsp;:&nbsp;".$inheritedValue;
         echo "</td>";
         echo "</tr>";
      }

      $this->showFormButtons($options);

      return true;
   }


   /**
    * Get value of config
    *
    * @global object $DB
    * @param string $name field name
    * @param integer $entities_id
    * @return string value of field
    */
   function getValueAncestor($name, $entities_id) {
      global $DB;

      $entities_ancestors = getAncestorsOf("glpi_entities", $entities_id);

      $where = '';
      if ($name == 'agent_base_url') {
         $where = "AND `".$name."` != ''";
      }

      $nbentities = count($entities_ancestors);
      for ($i=0; $i<$nbentities; $i++) {
         $entity = array_pop($entities_ancestors);
         $query = "SELECT * FROM `".$this->getTable()."`
            WHERE `entities_id`='".$entity."'
               AND `".$name."` IS NOT NULL
               ".$where."
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) != 0) {
            $data = $DB->fetchAssoc($result);
            return $data[$name];
         }
      }
      $this->getFromDB(1);
      return $this->getField($name);
   }


   /**
    * Get the value (of this entity or parent entity or in general config
    *
    * @global object $DB
    * @param string $name field name
    * @param integer $entities_id
    * @return string value of this field
    */
   function getValue($name, $entities_id) {
      global $DB;

      $where = '';
      if ($name == 'agent_base_url') {
         $where = "AND `".$name."` != ''";
      }

      $query = "SELECT `".$name."` FROM `".$this->getTable()."`
         WHERE `entities_id`='".$entities_id."'
            AND `".$name."` IS NOT NULL
            ".$where."
         LIMIT 1";
      $result = $DB->query($query);
      if ($DB->numrows($result) > 0) {
         $data = $DB->fetchAssoc($result);
         return $data[$name];
      }
      return $this->getValueAncestor($name, $entities_id);
   }


   /**
    * Initialize a field when get empty item (default values)
    */
   function post_getEmpty() {
      $this->fields['transfers_id_auto'] = -1;
   }
}
