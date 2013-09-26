<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryInventoryConfigurationManagement extends CommonDBTM {
   
   static $rightname = 'plugin_fusioninventory_agent';

   
   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Configuration management', 'fusioninventory');
   }



   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Agent', 'fusioninventory');

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = __('Name');
      $tab[1]['datatype']  = 'itemlink';

      return $tab;
   }
   
   
   
   /**
    * @see CommonGLPI::defineTabs()
   **/
   function defineTabs($options=array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab("PluginFusioninventoryInventoryConfigurationManagement", $ong, $options);
      return $ong;
   }

   
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $a_tabs = array();

      if ($item->fields['conform'] == 0) { // not conform
         $a_referential = unserialize(gzuncompress($item->fields['serialized_referential']));
         foreach ($a_referential as $name=>$data) {
            $a_tabs[] = "* ".$name;
         }         
      } else if ($item->fields['sha_referential'] == '') { // not validate
         $a_last = unserialize(gzuncompress($item->fields['serialized_last']));
         foreach ($a_last as $name=>$data) {
            $a_tabs[] = "* ".$name;
         } 
      }      
      return $a_tabs;
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      
      if ($tabnum >= 0) {
         $a_ref = array();
         $a_last = array();
         if ($item->fields['conform'] == 0) { // not conform
            $a_ref = unserialize(gzuncompress($item->fields['serialized_referential']));
            $a_last = unserialize(gzuncompress($item->fields['serialized_last']));
         } else if ($item->fields['sha_referential'] == '') { // not validate
            $a_last = unserialize(gzuncompress($item->fields['serialized_last']));
         } 
         $i = 0;

         foreach ($a_last as $name=>$data) {
            if ($i == $tabnum) {
               if (count($a_ref) > 0) {
                  $item->displayReferential($a_last[$name], $a_ref[$name], 1);
               } else {
                  $item->displayReferential($a_last);
               }
               return TRUE;
            }
            $i++;
         }
      }
      
      return TRUE;
   }
   
   
      
   function addLastInventory($itemtype, $items_id) {
      $elements = $this->find("`itemtype`='".$itemtype."'
                            AND `items_id`='".$items_id."'", "", 1);
      $input = array();
      if (count($elements)) {
         $input = current($elements);
      } else {
         $input = array();
         $input['itemtype'] = $itemtype;
         $input['items_id']  = $items_id;
         $input['id'] = $this->add($input);
      }
      $arrayphp = $this->constructPHPArrayFromDB($itemtype, $items_id);
      
      $serialized = gzcompress(serialize($arrayphp));
      $input['serialized_last'] = Toolbox::addslashes_deep($serialized);
      $input['sha_last'] = $this->generateSHA($arrayphp);

      if ($input['sha_referential'] != '') {
         if ($input['sha_referential'] == $input['sha_last']) {
            $input['conform'] = 1;
         } else {
            $input['conform'] = 0;
         }
      }
            
      $this->update($input);
      
      if ($input['sha_referential'] != ''
              && $input['sentnotification'] == 0
              && $input['sha_last'] != $input['sha_referential']) {
         // TODO, send a notification
         
      }
   }
   
   
   
   function constructPHPArrayFromDB($itemtype, $items_id) {
      
      $a_array = array();
      
      $item = new $itemtype();
      $item->getFromDB($items_id);
      
      // device fields
      $a_array[$itemtype] = $this->cleanFields($item->fields);
      
      
      return $a_array;
   }  
   
   
   
   function cleanFields($a_array) {

      $a_remove = array('date_mod',
                        'is_template');
      
      foreach ($a_remove as $field) {
         if (isset($a_array[$field])) {
            unset($a_array[$field]);
         }
      }      
      return $a_array;
   }
   
   
   
   function generateSHA($a_array) {
      // TODO delete parts not wanted by the config
      
      return sha1(serialize($a_array));
   }
   
   
   
   /** Display item with tabs
    *
    * @since version 0.85
    *
    * @param $options   array
   **/
   function display($options=array()) {

      if (isset($options['id'])
          && !$this->isNewID($options['id'])) {
         $this->getFromDB($options['id']);
      }

      $this->showNavigationHeader($options);
      $this->showTabsContent($options);
   }
   
   
   
   function showForm($ID, $options=array()) {
      global $CFG_GLPI, $DB;

      $this->initForm($ID, $options);
      $this->showFormHeader($options);
      
      
      
      
      
      $this->showFormButtons($options);

      return true;
   }
   
   
   
   function displayReferential($a_last, $a_ref=array(), $diff=0) {

      echo '<table>';
      PluginFusioninventoryToolbox::displaySerializedValues($a_last);
      echo '</table>';
   }
}

?>