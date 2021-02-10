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
 * This file is used to manage SNMP credentials: v1, v2c and v3
 * support.
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
   die("Sorry. You can't access this file directly");
}

/**
 * Manage SNMP credentials: v1, v2c and v3 support.
 */
class PluginFusioninventoryConfigSecurity extends CommonDBTM {

   /**
    * We activate the history.
    *
    * @var boolean
    */
   public $dohistory = true;

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_configsecurity';


   /**
    * Define tabs to display on form page
    *
    * @param array $options
    * @return array containing the tabs name
    */
   function defineTabs($options = []) {
      $ong = [];
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }


   /**
    * Display form
    *
    * @param integer $id
    * @param array $options
    * @return true
    */
   function showForm($id, $options = []) {
      Session::checkRight('plugin_fusioninventory_configsecurity', READ);
      $this->initForm($id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . __('Name') . "</td>";
      echo "<td align='center' colspan='2'>";
      Html::autocompletionTextField($this, 'name');
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . __('SNMP version', 'fusioninventory') . "</td>";
      echo "<td align='center' colspan='2'>";
         $this->showDropdownSNMPVersion($this->fields["snmpversion"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>v 1 & v 2c</th>";
      echo "<th colspan='2'>v 3</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Community', 'fusioninventory') . "</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this, 'community');
      echo "</td>";

      echo "<td align='center'>" . __('User') . "</td>";
      echo "<td align='center'>";
      // FIXME This is a credential field so it is not in autocomplete whitelist.
      // Replace with a simple text input.
      Html::autocompletionTextField($this, 'username');
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>".__('Encryption protocol for authentication ', 'fusioninventory').
              "</td>";
      echo "<td align='center'>";
         $this->showDropdownSNMPAuth($this->fields["authentication"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . __('Password') . "</td>";
      echo "<td align='center'>";
      // FIXME This is a credential field so it is not in autocomplete whitelist.
      // Replace with a password text input, crypt it, and handle ability to "blank" it.
      Html::autocompletionTextField($this, 'auth_passphrase');
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . __('Encryption protocol for data', 'fusioninventory') . "</td>";
      echo "<td align='center'>";
         $this->showDropdownSNMPEncryption($this->fields["encryption"]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "<td align='center'>" . __('Password') . "</td>";
      echo "<td align='center'>";
      // FIXME This is a credential field so it is not in autocomplete whitelist.
      // Replace with a password text input, crypt it, and handle ability to "blank" it.
      Html::autocompletionTextField($this, 'priv_passphrase');
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
   }


   /**
    * Display SNMP version (dropdown)
    *
    * @param null|string $p_value
    */
   function showDropdownSNMPVersion($p_value = null) {
      $snmpVersions = [0 => '-----', '1', '2c', '3'];
      $options = [];
      if (!is_null($p_value)) {
         $options = ['value' => $p_value];
      }
      Dropdown::showFromArray("snmpversion", $snmpVersions, $options);
   }


   /**
    * Get real version of SNMP
    *
    * @param integer $id
    * @return string
    */
   function getSNMPVersion($id) {
      switch ($id) {

         case '1':
            return '1';

         case '2':
            return '2c';

         case '3':
            return '3';

      }
      return '';
   }


   /**
    * Display SNMP encryption protocols dropdown
    *
    * @param null|string $p_value
    */
   function showDropdownSNMPAuth($p_value = null) {
      $authentications = [0=>'-----', 'MD5', 'SHA'];
      $options = [];
      if (!is_null($p_value)) {
         $options = ['value'=>$p_value];
      }
      Dropdown::showFromArray("authentication", $authentications, $options);
   }


   /**
    * Get SNMP authentication protocol
    *
    * @param integer $id
    * @return string
    */
   function getSNMPAuthProtocol($id) {
      switch ($id) {

         case '1':
            return 'MD5';

         case '2':
            return 'SHA';

      }
      return '';
   }


   /**
    * Display SNMP encryption protocols dropdown
    *
    * @param string $p_value
    */
   function showDropdownSNMPEncryption($p_value = null) {
      $encryptions = [0 => Dropdown::EMPTY_VALUE, 'DES', 'AES128', 'Triple-DES'];
      $options     = [];
      if (!is_null($p_value)) {
         $options = ['value' => $p_value];
      }
      Dropdown::showFromArray("encryption", $encryptions, $options);
   }


   /**
    * Get SNMP encryption protocol
    *
    * @param integer $id
    * @return string
    */
   function getSNMPEncryption($id) {
      switch ($id) {

         case '1':
            return 'DES';

         case '2':
            return 'AES';

         case '5':
            return '3DES';

      }
      return '';
   }


   /**
    * Display SNMP credentials dropdown
    *
    * @param string $selected
    */
   static function authDropdown($selected = "") {

      Dropdown::show("PluginFusioninventoryConfigSecurity",
                      ['name' => "plugin_fusioninventory_configsecurities_id",
                           'value' => $selected,
                           'comment' => false]);
   }


   /**
    * Display form related to the massive action selected
    *
    * @param object $ma MassiveAction instance
    * @return boolean
    */
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      if ($ma->getAction() == 'assign_auth') {
         PluginFusioninventoryConfigSecurity::authDropdown();
         echo Html::submit(_x('button', 'Post'), ['name' => 'massiveaction']);
         return true;
      }
      return false;
   }


   /**
    * Execution code for massive action
    *
    * @param object $ma MassiveAction instance
    * @param object $item item on which execute the code
    * @param array $ids list of ID on which execute the code
    */
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      $itemtype = $item->getType();

      switch ($ma->getAction()) {

         case "assign_auth" :
            switch ($itemtype) {

               case 'NetworkEquipment':
                  $equipement = new PluginFusioninventoryNetworkEquipment();
                  break;

               case 'Printer':
                  $equipement = new PluginFusioninventoryPrinter();
                  break;

               case 'PluginFusioninventoryUnmanaged':
                  $equipement = new PluginFusioninventoryUnmanaged();
                  break;

            }
            $fk = getForeignKeyFieldForItemType($itemtype);
            foreach ($ids as $key) {
               $found = $equipement->find([$fk => $key]);
               $input = [];
               if (count($found) > 0) {
                  $current = current($found);
                  $equipement->getFromDB($current['id']);
                  $input['id'] = $equipement->fields['id'];
                  $input['plugin_fusioninventory_configsecurities_id'] =
                              $_POST['plugin_fusioninventory_configsecurities_id'];
                  $return = $equipement->update($input);
               } else {
                  $input[$fk] = $key;
                  $input['plugin_fusioninventory_configsecurities_id'] =
                              $_POST['plugin_fusioninventory_configsecurities_id'];
                  $return = $equipement->add($input);
               }

               if ($return) {
                  //set action massive ok for this item
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
               } else {
                  // KO
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
               }
            }
         break;

      }
   }


   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id'            => 'common',
         'name'          => __('Characteristics')
      ];

      $tab[] = [
         'id'            => '1',
         'table'         => $this->getTable(),
         'field'         => 'name',
         'name'          => __('Name'),
         'datatype'      => 'itemlink',
         'autocomplete'  => true,
      ];

      $tab[] = [
         'id'            => '2',
         'table'         => $this->getTable(),
         'field'         => 'community',
         'name'          => __('Community', 'fusioninventory'),
         'datatype'      => 'string',
         'massiveaction' => false,
         'autocomplete'  => true,
      ];

      return $tab;
   }
}
