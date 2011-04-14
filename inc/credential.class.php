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

   public $first_level_menu  = "plugins";
   public $second_level_menu = "fusioninventory";

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['menu'][5];
   }

   function canCreate() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory', 'credential', 'w');
   }

   function canView() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory', 'credential', 'r');
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
         if ($label = self::getLabelByItemtype($this->fields['itemtype'])) {
            echo $label;
            echo "<input type='hidden' name='itemtype' value='".$this->fields['itemtype']."'";
         }

      } else {
         //Add criteria : display dropdown
         $options = self::getCredentialsItemTypes();
         $options[''] = DROPDOWN_EMPTY_VALUE;
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
      $tab[1]['name'] = $LANG['common'][16];
      $tab[1]['datatype'] = 'itemlink';

      $tab[2]['table'] = 'glpi_entities';
      $tab[2]['field'] = 'completename';
      $tab[2]['name'] = $LANG['entity'][0];

      $tab[3]['table']         = $this->getTable();
      $tab[3]['field']         = 'itemtype';
      $tab[3]['name']          = $LANG['common'][17];
      $tab[3]['massiveaction'] = false;

      $tab[4]['table'] = $this->getTable();
      $tab[4]['field'] = 'username';
      $tab[4]['name'] = $LANG['login'][6];

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
      global $LANG;

      if (!$input['itemtype']) {
         addMessageAfterRedirect($LANG['setup'][817], true, ERROR);
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
    * @param $credential_itemtype for example PluginFusinvinventoryVmwareESX
    * @return the label associated with the itemtype, or false if no credential found
    */
   static function getLabelByItemtype($credential_itemtype) {
      $credentialtypes = self::findItemtypeType($credential_itemtype);
      if (!empty($credentialtypes)) {
         return $credentialtypes['name'];

      } else {
         return false;
      }
   } 
   
   static function hasCredentialsForItemtype($itemtype) {
      foreach (PluginFusioninventoryModule::getAll() as $data) {
         $class = 'Plugin'.ucfirst($data['directory']).'Staticmisc';

         if (is_callable(array($class, 'credential_types'))) {
            $res = call_user_func(array($class, 'credential_types'));
            foreach ($res as $credential) {
               if (in_array($itemtype,$credential['targets'])) {
                  return true;
               }
            }
         }
      }
      return false;
   }
   
   /**
    * Find a credential by his itemtype
    */
   static function findItemtypeType($credential_itemtype) {
      foreach (PluginFusioninventoryModule::getAll() as $data) {
         $class = 'Plugin'.ucfirst($data['directory']).'Staticmisc';

         if (is_callable(array($class, 'credential_types'))) {
            $res = call_user_func(array($class, 'credential_types'));
            foreach ($res as $credential) {
               if ($credential['itemtype'] == $credential_itemtype) {
                  return $credential;
               }
            }
         }
      }
      return array();
   }
   
   /**
    * Get all modules that can declare credentials
    */
   static function getCredentialsItemTypes() {
      $itemtypes = array();
      foreach (PluginFusioninventoryModule::getAll() as $data) {
         $class = 'Plugin'.ucfirst($data['directory']).'Staticmisc';

         if (is_callable(array($class, 'credential_types'))) {
            $res = call_user_func(array($class, 'credential_types'));
            foreach ($res as $credential) {
               $itemtypes[$credential['itemtype']] = $credential['name'];
            }
         }
      }
      return $itemtypes;
   }

   static function getForItemtype($itemtype) {
      $itemtypes = array();
      foreach (PluginFusioninventoryModule::getAll() as $data) {
         $class = 'Plugin'.ucfirst($data['directory']).'Staticmisc';
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
   
   static function showForItem(CommonDBTM $item) {
      global $LANG, $CFG_GLPI;
      
      $ID = $item->fields['id'];

      if (!$item->getFromDB($ID) || !$item->can($ID, "r")) {
         return false;
      }
      $canedit = $item->can($ID, "w");

      echo "<div class='spaced center'>";

      $credentials = getAllDatasFromTable('glpi_plugin_fusioninventory_credentials_items',
                                          "`items_id` = '".$ID."' 
                                            AND `itemtype`='".get_class($item)."'");

      echo "<form method='post' action='".getItemTypeFormURL('PluginFusioninventoryCredential_Item').
         "' name='credential_form' id='credential_form'>";
      echo "<table class='tab_cadre_fixe'>";
      
      echo "<tr class='tab_bg_2'><th colspan='3'>";
      echo $LANG['plugin_fusioninventory']['credential'][1]."</th></tr>";

      echo "<tr class='tab_bg_2'><th colspan='3'>".$LANG['plugin_fusioninventory']['credential'][4].
         "</th></tr>";

      $types = self::getForItemtype(get_class($item));
      if (!empty($types)) {
         echo "<tr class='tab_bg_2'><td>";
         echo $LANG['plugin_fusioninventory']['credential'][3]."</td>"; 
         echo "<td>";
         $types[''] = DROPDOWN_EMPTY_VALUE;
         $rand = Dropdown::showFromArray('itemtype',$types);

         $params = array('itemtype' => '__VALUE__',
                         'id'       => $ID,
                         'target'   => get_class($item));
         $url = $CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownCredentials.php";
         ajaxUpdateItemOnSelectEvent("dropdown_itemtype$rand", "span_credentials",
                                     $url,
                                     $params);
         echo "&nbsp;<span id='span_credentials' name='span_credentials'></span>";
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";

      echo "<table class='tab_cadre_fixe'>";

      if (empty($credentials)) {
         echo "<tr><td colspan='2' class='center'>".$LANG['plugin_fusioninventory']['credential'][2].
            "</td></tr>";
      } else {

         $sel ="";
         if (isset ($_GET["select"]) && $_GET["select"] == "all") {
            $sel = "checked";
         }
 
         $obj = new PluginFusioninventoryCredential();
         echo "<tr><th></th><th>".$LANG['common'][17]."</td><th>".$LANG['common'][16]."</td></tr>";
         foreach ($credentials as $credential) {
            $obj->getFromDB($credential['plugin_fusioninventory_credentials_id']);

            echo "<tr><td class='center'>";
            echo "<input type='checkbox' name='item[".$credential['id']."]' value='1' $sel>";
            echo "</td>";
            echo "<td>";
            echo self::getLabelByItemtype($obj->fields['itemtype']);
            echo "</td><td>";
            echo $obj->getLink(true);
            echo "</td></tr>";
         }

         openArrowMassive("credential_form", true);
         closeArrowMassive('delete', $LANG['buttons'][6]);
         
      }
      echo "</table></form>";
      echo "</div>";

   }
   
   static function dropdownCredentialsForItemtype($params = array()) {
      global $LANG;

      if ($params['itemtype'] != '') {

         $results = getAllDatasFromTable('glpi_plugin_fusioninventory_credentials',
                                         "`itemtype`='".$params['itemtype']."'");
         $types = array();

         foreach ($results as $result) {
            $types[$result['id']] = $result['name'];
         }
         Dropdown::showFromArray('plugin_fusioninventory_credentials_id', $types);
         echo "&nbsp;<input type='submit' class='submit' name='add' value='".
            $LANG['buttons'][8]."'>";
         echo "<input type='hidden' name='itemtype' value='".$params['target']."'>";
         echo "<input type='hidden' name='items_id' value='".$params['id']."'>";
      }
   }
}

?>