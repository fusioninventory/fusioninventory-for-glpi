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
 * This file is used to manage the IP of VMWARE ESX and link to
 * credentials to be able to inventory these specific systems througth the
 * webservice.
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
 * Manage the IP of VMWARE ESX and link to credentials to be able to inventory
 * these specific systems througth the webservice.
 */
class PluginFusioninventoryCredentialIp extends CommonDropdown {

   /**
    * Define first level menu name
    *
    * @var string
    */
   public $first_level_menu  = "admin";

   /**
    * Define second level menu name
    *
    * @var string
    */
   public $second_level_menu = "pluginfusioninventorymenu";

   /**
    * Define third level menu name
    *
    * @var string
    */
   public $third_level_menu  = "credentialip";

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_credentialip';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('Remote device inventory', 'fusioninventory');
   }


   /**
    * Add more fields
    *
    * @return array
    */
   function getAdditionalFields() {
      return [['name'  => 'itemtype',
                         'label' => __('Type'),
                         'type'  => 'credentials'],
                   ['name'  => 'ip',
                         'label' => __('IP'),
                         'type'  => 'text']];
   }


   /**
    * Display specific fields
    *
    * @param integer $ID
    * @param array $field
    */
   function displaySpecificTypeField($ID, $field = []) {

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


   /**
    * Get search function for the class
    *
    * @return array
    */
   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id'   => 'common',
         'name' => __('Authentication for remote devices (VMware)', 'fusioninventory'),
      ];

      $tab[] = [
         'id'       => '1',
         'table'    => $this->getTable(),
         'field'    => 'name',
         'name'     => __('Name'),
         'datatype' => 'itemlink',
      ];

      $tab[] = [
         'id'       => '2',
         'table'    => 'glpi_entities',
         'field'    => 'completename',
         'name'     => Entity::getTypeName(1),
         'datatype' => 'dropdown',
      ];

      $tab[] = [
         'id'            => '3',
         'table'         => $this->getTable(),
         'field'         => 'name',
         'name'          => __('Authentication for remote devices (VMware)', 'fusioninventory'),
         'datatype'      => 'itemlink',
         'itemlink_type' => 'PluginFusioninventoryCredential',
      ];

      $tab[] = [
         'id'       => '4',
         'table'    => $this->getTable(),
         'field'    => 'ip',
         'name'     => __('IP'),
         'datatype' => 'string',
      ];

      return $tab;
   }


   /**
    * Display a specific header
    */
   function displayHeader() {
      //Common dropdown header
      parent::displayHeader();

      //Fusioninventory menu
      PluginFusioninventoryMenu::displayMenu("mini");
   }
}
