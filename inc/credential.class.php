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

class PluginFusioninventoryCredential extends CommonDropdown {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['menu'][5];
   }

   function canCreate() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory','credential','w');
   }

   function canView() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory','credential','r');
   }

   static function getItemtypesForCredentials() {
      return array();
   }
   
   function getAdditionalFields() {
      global $LANG;

      return array(array('name'  => 'itemtype',
                         'label' => $LANG['common'][17],
                         'type'  => 'credential_itemtype'),
                   array('name'  => 'username',
                         'label' => $LANG['login'][6],
                         'type'  => 'text'),
                   array('name'  => 'password',
                         'label' => $LANG['login'][7],
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
            $this->showItemtype($ID, $this->fields['itemtype']);
            break;
      }
   }

   function showItemtype($ID, $value=0) {
      global $CFG_GLPI;

      //Criteria already added : only display the selected itemtype
      if ($ID > 0) {
          $item = new $this->fields['itemtype'];
          echo $item->getTypeName();
          echo "<input type='hidden' name='itemtype' value='".$this->fields['itemtype']."'";

      } else {
         //Add criteria : display dropdown
         $options[0] = DROPDOWN_EMPTY_VALUE;
         foreach (self::getItemtypesForCredentials() as $itemtype) {
            if (class_exists($itemtype)) {
               $item = new $itemtype();
               if ($item->can(-1,'r')) {
                  $options[$itemtype] = $item->getTypeName($itemtype);
               }
            }
         }
         asort($options);
         $rand = Dropdown::showFromArray('itemtype', $options);
      }

   }
   /**
    * Add more tabs to display
    *
    * @param $options array
   **/
   function defineMoreTabs($options=array()) {
      global $LANG;
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
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusioninventory']['menu'][5];

      $tab[1]['table'] = $this->getTable();
      $tab[1]['field'] = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name'] = $LANG['common'][16];
      $tab[1]['datatype'] = 'itemlink';

      $tab[2]['table'] = 'glpi_entities';
      $tab[2]['field'] = 'completename';
      $tab[2]['linkfield'] = 'entities_id';
      $tab[2]['name'] = $LANG['entity'][0];

      $tab[3]['table'] = $this->getTable();
      $tab[3]['field'] = 'ip_start';
      $tab[3]['linkfield'] = 'ip_start';
      $tab[3]['name'] = $LANG['plugin_fusioninventory']['iprange'][0];

      $tab[4]['table'] = $this->getTable();
      $tab[4]['field'] = 'ip_end';
      $tab[4]['linkfield'] = 'ip_end';
      $tab[4]['name'] = $LANG['plugin_fusioninventory']['iprange'][1];

      return $tab;
   }


}

?>