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

class PluginFusioninventoryCredentialIp extends CommonDropdown {

   public $first_level_menu  = "plugins";
   public $second_level_menu = "pluginfusioninventorymenu";
   public $third_level_menu  = "credentialip";

   static $rightname = 'plugin_fusioninventory_credentialip';

   static function getTypeName($nb=0) {
      return __('Remote device inventory', 'fusioninventory');
   }

   function getAdditionalFields() {

      return array(array('name'  => 'itemtype',
                         'label' => __('Type'),
                         'type'  => 'credentials'),
                   array('name'  => 'ip',
                         'label' => __('IP'),
                         'type'  => 'text'));
   }



   /**
    * Display specific fields for FieldUnicity
    *
    * @param $ID
    * @param $field array
   **/
   function displaySpecificTypeField($ID, $field=array()) {

      switch ($field['type']) {
         case 'credentials' :
            if ($ID > 0) {
               $field['id'] = $this->fields['plugin_fusioninventory_credentials_id'];
            } else {
               $field['id'] = -1;
            }
            PluginFusioninventoryCredential::dropdownCredentials($field);
            break;
      }
   }



   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Authentication for remote devices (VMware)', 'fusioninventory');

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['name']      = __('Name');
      $tab[1]['datatype']  = 'itemlink';

      $tab[2]['table'] = 'glpi_entities';
      $tab[2]['field'] = 'completename';
      $tab[2]['name']  = __('Entity');
      $tab[2]['datatype'] = 'dropdown';


      $tab[3]['table'] = $this->getTable();
      $tab[3]['field'] = 'name';
      $tab[3]['name']  = __('Authentication for remote devices (VMware)', 'fusioninventory');
      $tab[3]['datatype']      = 'itemlink';
      $tab[3]['itemlink_type'] = 'PluginFusioninventoryCredential';

      $tab[4]['table'] = $this->getTable();
      $tab[4]['field'] = 'ip';
      $tab[4]['name']  = __('IP');
      $tab[4]['datatype']      = 'string';

      return $tab;
   }



   function title() {

      //Leave empty !
      $buttons = array();
      if (Session::haveRight('plugin_fusioninventory_credential', READ)) {
         $buttons["credential.php"] =
                  __('Authentication for remote devices (VMware)', 'fusioninventory');

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
