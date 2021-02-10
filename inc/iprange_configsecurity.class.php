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
 * This file is used to manage SNMP credentials associated with IP ranges.
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
 * Manage SNMP credentials associated with IP ranges.
 */
class PluginFusioninventoryIPRange_ConfigSecurity extends CommonDBRelation {

   /**
    * Itemtype for the first part of relation
    *
    * @var string
    */
   static public $itemtype_1    = 'PluginFusioninventoryIPRange';

   /**
    * id field name for the first part of relation
    *
    * @var string
    */
   static public $items_id_1    = 'plugin_fusioninventory_ipranges_id';

   /**
    * Restrict the first item to the current entity
    *
    * @var string
    */
   static public $take_entity_1 = true;

   /**
    * Itemtype for the second part of relation
    *
    * @var string
    */
   static public $itemtype_2    = 'PluginFusioninventoryConfigSecurity';

   /**
    * id field name for the second part of relation
    *
    * @var string
    */
   static public $items_id_2    = 'plugin_fusioninventory_configsecurities_id';

   /**
    * Not restrict the second item to the current entity
    *
    * @var string
    */
   static public $take_entity_2 = false;


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if ($item->fields['id'] > 0) {
         return __('Associated SNMP credentials', 'fusioninventory');
      }
      return '';
   }


   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return true
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      $pfIPRange_ConfigSecurity = new self();
      $pfIPRange_ConfigSecurity->showForm($item);
      return true;
   }


   /**
    * Get standard massive action forbidden (hide in massive action list)
    *
    * @return array
    */
   function getForbiddenStandardMassiveAction() {
      $forbidden = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }


   /**
    * Display form
    *
    * @param object $item
    * @param array $options
    * @return boolean
    */
   function showForm(CommonDBTM $item, $options = []) {

      $ID = $item->getField('id');

      if ($item->isNewID($ID)) {
         return false;
      }

      if (!$item->can($item->fields['id'], READ)) {
         return false;
      }
      $rand = mt_rand();

      $a_data = getAllDataFromTable('glpi_plugin_fusioninventory_ipranges_configsecurities',
                                     ['plugin_fusioninventory_ipranges_id' => $item->getID()],
                                     false,
                                     '`rank`');
      $a_used = [];
      foreach ($a_data as $data) {
         $a_used[] = $data['plugin_fusioninventory_configsecurities_id'];
      }
      echo "<div class='firstbloc'>";
      echo "<form name='iprange_configsecurity_form$rand' id='iprange_configsecurity_form$rand' method='post'
             action='".Toolbox::getItemTypeFormURL('PluginFusioninventoryIPRange_ConfigSecurity')."' >";

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_2'>";
      echo "<th colspan='2'>".__('Add SNMP credentials')."</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_2'>";
      echo "<td>";
      Dropdown::show('PluginFusioninventoryConfigSecurity', ['used' => $a_used]);
      echo "</td>";
      echo "<td>";
      echo Html::hidden('plugin_fusioninventory_ipranges_id',
                   ['value' => $item->getID()]);
      echo "<input type='submit' name='add' value=\"".
          _sx('button', 'Associate')."\" class='submit'>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
      echo "</div>";

      // Display list of auth associated with IP range
      $rand = mt_rand();

      echo "<div class='spaced'>";
      Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
      $massiveactionparams = ['container' => 'mass'.__CLASS__.$rand];
      Html::showMassiveActions($massiveactionparams);

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_2'>";
      echo "<th width='10'>".Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand)."</th>";
      echo "<th>";
      echo __('SNMP credentials', 'fusioninventory');
      echo "</th>";
      echo "<th>";
      echo __('Version', 'fusioninventory');
      echo "</th>";
      echo "<th>";
      echo __('By order of priority', 'fusioninventory');
      echo "</th>";
      echo "</tr>";

      $pfConfigSecurity = new PluginFusioninventoryConfigSecurity();
      foreach ($a_data as $data) {
         echo "<tr class='tab_bg_2'>";
         echo "<td>";
         Html::showMassiveActionCheckBox(__CLASS__, $data["id"]);
         echo "</td>";
         echo "<td>";
         $pfConfigSecurity->getFromDB($data['plugin_fusioninventory_configsecurities_id']);
         echo $pfConfigSecurity->getLink();
         echo "</td>";
         echo "<td>";
         echo $pfConfigSecurity->getSNMPVersion($pfConfigSecurity->fields['snmpversion']);
         echo "</td>";
         echo "<td>";
         echo $data['rank'];
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      $massiveactionparams['ontop'] =false;
      Html::showMassiveActions($massiveactionparams);
      echo "</div>";
      return true;
   }
}
