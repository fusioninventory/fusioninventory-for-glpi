<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
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

class PluginFusioninventoryCredential extends CommonDropdown {

   public $first_level_menu  = "plugins";
   public $second_level_menu = "pluginfusioninventorymenu";
   public $third_level_menu  = "credential";

   static $rightname = 'plugin_fusioninventory_credential';

   static function getTypeName($nb=0) {
      return __('Authentication for remote devices (VMware)', 'fusioninventory');
   }



   function getAdditionalFields() {

      return array(array('name'  => 'itemtype',
                         'label' => __('Type'),
                         'type'  => 'credential_itemtype'),
                   array('name'  => 'username',
                         'label' => __('Login'),
                         'type'  => 'text'),
                   array('name'  => 'password',
                         'label' => __('Password'),
                         'type'  => 'password'));
   }



   /**
    * Display specific fields for FieldUnicity
    *
    * @param $ID
    * @param $field array
   **/
   function displaySpecificTypeField($ID, $field=array()) {

      switch ($field['type']) {
         case 'credential_itemtype' :
            $this->showItemtype($ID);
            break;
      }
   }



   /**
    * Show itemtype
    *
    * @param type $ID
    */
   function showItemtype($ID) {

      //Criteria already added : only display the selected itemtype
      if ($ID > 0) {
         $label = self::getLabelByItemtype($this->fields['itemtype']);
         if ($label) {
            echo $label;
            echo "<input type='hidden' name='itemtype' value='".$this->fields['itemtype']."'";
         }
      } else {
         //Add criteria : display dropdown
         $options = self::getCredentialsItemTypes();
         $options[''] = Dropdown::EMPTY_VALUE ;
         asort($options);
         Dropdown::showFromArray('itemtype', $options);
      }
   }



   /**
    * Add more tabs to display
    *
    * @param $options array
   **/
   function defineMoreTabs($options=array()) {
      return array();
   }



   /**
    * Display more tabs
    *
    * @param $tab
   **/
   function displayMoreTabs($tab) {
   }



   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Authentication for remote devices (VMware)', 'fusioninventory');

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['name']      = __('Name');
      $tab[1]['datatype']  = 'itemlink';

      $tab[2]['table']  = 'glpi_entities';
      $tab[2]['field']  = 'completename';
      $tab[2]['name']   = __('Entity');
      $tab[2]['datatype'] = 'dropdown';

      $tab[3]['table']           = $this->getTable();
      $tab[3]['field']           = 'itemtype';
      $tab[3]['name']            = __('Type');
      $tab[3]['massiveaction']   = FALSE;

      $tab[4]['table']  = $this->getTable();
      $tab[4]['field']  = 'username';
      $tab[4]['name']   = __('Login');

      return $tab;
   }



   /**
    * Perform checks to be sure that an itemtype and at least a field are selected
    *
    * @param input the values to insert in DB
    *
    * @return input the values to insert, but modified
   **/
   static function checkBeforeInsert($input) {

      if ($input['password'] == '') {
         unset($input['password']);
      }

      if (!$input['itemtype']) {
          Session::addMessageAfterRedirect(
                  __('It\'s mandatory to select a type and at least one field'), TRUE, ERROR);
         $input = array();

      }
      return $input;
   }



   function prepareInputForAdd($input) {
      return self::checkBeforeInsert($input);
   }



   function prepareInputForUpdate($input) {
      return $input;
   }



   /**
    * Get an itemtype label by his credential itemtype
    *
    * @param $credential_itemtype for example PluginFusioninventoryInventoryComputerESX
    *
    * @return the label associated with the itemtype, or FALSE if no credential found
    */
   static function getLabelByItemtype($credential_itemtype) {
      $credentialtypes = self::findItemtypeType($credential_itemtype);
      if (!empty($credentialtypes)) {
         return $credentialtypes['name'];
      }
      return FALSE;
   }



   /**
    * Find a credential by his itemtype
    *
    * @param $credential_itemtype for example PluginFusioninventoryInventoryComputerESX
    */
   static function findItemtypeType($credential_itemtype) {

      $credential = array ('itemtype'  => 'PluginFusioninventoryInventoryComputerESX', //Credential itemtype
                           'name'      => __('VMware host', 'fusioninventory'), //Label
                           'targets'   => array('Computer'));
      if ($credential['itemtype'] == $credential_itemtype) {
         return $credential;
      }
      return array();
   }



   /**
    * Get all modules that can declare credentials
    */
   static function getCredentialsItemTypes() {
     return array ('PluginFusioninventoryInventoryComputerESX' =>
                           __('VMware host', 'fusioninventory'));
   }



   static function getForItemtype($itemtype) {
      $itemtypes = array();
      foreach (PluginFusioninventoryModule::getAll() as $data) {
         $class= PluginFusioninventoryStaticmisc::getStaticmiscClass($data['directory']);
         if (is_callable(array($class, 'credential_types'))) {
            foreach (call_user_func(array($class, 'credential_types')) as $credential) {
               if (in_array($itemtype, $credential['targets'])) {
                  $itemtypes[$credential['itemtype']] = $credential['name'];
               }
            }
         }
      }
      return $itemtypes;
   }



   /**
    * See dropdown credentials
    *
    * @param type $params
    */
   static function dropdownCredentials($params = array()) {
      global $CFG_GLPI;

      $p = array();
      if ($params['id'] == -1) {
         $p['value']    = '';
         $p['itemtype'] = '';
         $p['id']       = 0;

      } else {
         $credential = new PluginFusioninventoryCredential();
         $credential->getFromDB($params['id']);
         if ($credential->getFromDB($params['id'])) {
            $p = $credential->fields;
         } else {
            $p['value']    = '';
            $p['itemtype'] = '';
            $p['id']       = 0;
         }
      }

      $types     = self::getCredentialsItemTypes();
      $types[''] = Dropdown::EMPTY_VALUE ;
      $rand      = Dropdown::showFromArray('plugin_fusioninventory_credentials_id', $types,
                                           array('value' => $p['itemtype']));
      $params    = array('itemtype' => '__VALUE__',
                         'id'       => $p['id']);
      $url       = $CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownCredentials.php";
      Ajax::UpdateItemOnSelectEvent("dropdown_plugin_fusioninventory_credentials_id$rand",
                                  "span_credentials", $url, $params);

      echo "&nbsp;<span name='span_credentials' id='span_credentials'>";
      if ($p['id']) {
         self::dropdownCredentialsForItemtype($p);
      }
      echo "</span>";
   }



   static function dropdownCredentialsForItemtype($params = array()) {

      if (empty($params['itemtype'])) {
         return;
      }

      // params
      // Array ( [itemtype] => PluginFusioninventoryInventoryComputerESX [id] => 0 )
      if ($params['itemtype'] == 'PluginFusioninventoryInventoryComputerESX') {
         $params['itemtype'] = 'PluginFusioninventoryCredential';
      }
      $value = 0;
      if (isset($params['id'])) {
         $value = $params['id'];
      }
      Dropdown::show($params['itemtype'], array('entity_sons' => TRUE,
                                                'value'       => $value));
   }



   /**
    * Check if there's at least one credential itemetype
    *
    * @return TRUE if there's at least one type, FALSE otherwise
    */
   static function hasAlLeastOneType() {
      $types = self::getCredentialsItemTypes();
      return (!empty($types));
   }



   function title() {

      $buttons = array();
      if (Session::haveRight('plugin_fusioninventory_credential', READ)) {
         $buttons["credentialip.php"] =
                  __('Remote devices to inventory (VMware)', 'fusioninventory');

      }
   }



   function displayHeader() {
      //Common dropdown header
      parent::displayHeader();

      //Fusioninventory menu
      PluginFusioninventoryMenu::displayMenu("mini");
   }

}

?>
