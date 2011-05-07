<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryCredentialIp extends CommonDropdown {

   public $first_level_menu  = "plugins";
   public $second_level_menu = "fusioninventory";

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['credential'][2];
   }

   function canCreate() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory', 'credentialip', 'w');
   }

   function canView() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory', 'credentialip', 'r');
   }
   
   function getAdditionalFields() {
      global $LANG;

      return array(array('name'  => 'itemtype',
                         'label' => $LANG['common'][17],
                         'type'  => 'credentials'),
                   array('name'  => 'ip',
                         'label' => $LANG['networking'][14],
                         'type'  => 'text'));
   }

   /**
    * Display specific fields for FieldUnicity
    *
    * @param $ID
    * @param $field array
   **/
   function displaySpecificTypeField($ID, $field=array()) {
      global $CFG_GLPI;
      
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
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusioninventory']['menu'][5];

      $tab[1]['table'] = $this->getTable();
      $tab[1]['field'] = 'name';
      $tab[1]['name'] = $LANG['common'][16];
      $tab[1]['datatype'] = 'itemlink';

      $tab[2]['table'] = 'glpi_entities';
      $tab[2]['field'] = 'completename';
      $tab[2]['name'] = $LANG['entity'][0];

      $tab[3]['table']         = $this->getTable();
      $tab[3]['field']         = 'name';
      $tab[3]['name']          = $LANG['plugin_fusioninventory']['menu'][5];
      $tab[3]['datatype']      = 'itemlink';
      $tab[3]['itemlink_type'] = 'PluginFusioninventoryCredential';

      $tab[4]['table']         = $this->getTable();
      $tab[4]['field']         = 'ip';
      $tab[4]['name']          = $LANG['networking'][14];
      $tab[4]['datatype']      = 'string';

      return $tab;
   }

   function title() {
      global $CFG_GLPI, $LANG;
      //Leave empty !
      $buttons = array();
      if (PluginFusioninventoryProfile::haveRight('fusioninventory', 'credential', 'r')) {
         $buttons["credential.php"] = $LANG['plugin_fusioninventory']['menu'][5];
      }
      displayTitle(GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_credentials.png", 
                   $LANG['plugin_fusioninventory']['menu'][5], "", $buttons);
      
   }
   
   function displayHeader () {
      //Common dropdown header
      parent::displayHeader();
      
      //Fusioninventory menu
      PluginFusioninventoryMenu::displayMenu("mini");
   }

   /**
    * Get all ip to inventory by credential type
    * @param credential_type the type of asset to remotly inventory
    * 
    * @return an array of credentials and ip, empty if nothing
    */
   static function getByType($credential_type = '') {
      global $DB;
      $query = "SELECT `a`.* 
                FROM `glpi_plugin_fusioninventory_credentialips` as `a` 
                LEFT JOIN `glpi_plugin_fusioninventory_credentials` as `c` 
                   ON `c`.`id` = `a`.`plugin_fusioninventory_credentials_id` 
                WHERE `c`.`itemtype`='$credential_type'";
      $query.= getEntitiesRestrictRequest(' AND','glpi_plugin_fusioninventory_credentialips');
      $results = $DB->query($query);
      $response = array();
      while ($data = $DB->query($query)) {
         $response[] = $data;
      }
      
      return $response;
   }
}

?>