<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
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

class PluginFusinvinventoryBlacklist extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvinventory']['menu'][2];
   }

   
   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusinvinventory", "blacklist", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusinvinventory", "blacklist", "r");
   }

   

   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusinvinventory']['menu'][2];

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'value';
      $tab[1]['linkfield'] = 'value';
      $tab[1]['name']      = $LANG['plugin_fusinvinventory']['blacklist'][0];
      $tab[1]['datatype']  = 'itemlink';

      $tab[2]['table']     = 'glpi_plugin_fusinvinventory_criterias';
      $tab[2]['field']     = 'name';
      $tab[2]['linkfield'] = 'plugin_fusioninventory_criterium_id';
      $tab[2]['name']      = $LANG['common'][16];
      $tab[2]['datetype']  = "itemlink";

      return $tab;
   }


   
   function defineTabs($options=array()){

      $pfCriteria = new PluginFusinvinventoryCriteria();

      $ong = array();
      $i = 1;
      $fields = $pfCriteria->find("");
      foreach($fields as $data) {
         $ong[$i] = $data['name'];
         $i++;
      }
      return $ong;
   }



   /**
   * Display form for black list
   *
   * @param $items_id integer id of the blacklist
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $LANG;

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader();

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusinvinventory']['blacklist'][0]."</td>";
      echo "<td>";
      echo "<input type='text' name='value' value='".$this->fields['value']."' />";
      echo "</td>";
      echo "<td>".$LANG['common'][16]."</td>";
      echo "<td>";
      Dropdown::show('PluginFusinvinventoryCriteria', array('name' => 'plugin_fusioninventory_criterium_id',
                                                            'value' => $this->fields['plugin_fusioninventory_criterium_id']));
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons();

      return true;
   }



   /**
   * Remove fields in XML from agent who are blacklisted
   *
   * @param $p_xml value XML from agent
   *
   * @return value XML cleaned (without blacklisted fields)
   *
   **/
   function cleanBlacklist($p_xml) {
      $xml = $p_xml;

      if (isset($xml->CONTENT->BIOS->SSN)) {
         $xml->CONTENT->BIOS->SSN = trim($xml->CONTENT->BIOS->SSN);
      }

      $pfCriteria = new PluginFusinvinventoryCriteria();
      $fields = $pfCriteria->find("");
      
      foreach($fields as $id=>$data) {

         switch($data['comment']) {

            case 'ssn':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->BIOS->SSN)) AND ($xml->CONTENT->BIOS->SSN ==$blacklist_data['value'])) {
                     $xml->CONTENT->BIOS->SSN = "";
                  }
               }
               break;

            case 'uuid':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->HARDWARE->UUID)) AND ($xml->CONTENT->HARDWARE->UUID == $blacklist_data['value'])) {
                     $xml->CONTENT->HARDWARE->UUID = "";
                  }
               }
               break;

            case 'macAddress':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");
               
               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if (isset($xml->CONTENT->NETWORKS)) {
                     foreach($xml->CONTENT->NETWORKS as $network) {
                        if ((isset($network->MACADDR)) AND ($network->MACADDR == $blacklist_data['value'])) {
                           $network->MACADDR = "";
                        }
                     }
                  }
               }
               break;

           case 'winProdKey':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->HARDWARE->WINPRODKEY)) AND ($xml->CONTENT->HARDWARE->WINPRODKEY == $blacklist_data['value'])) {
                     $xml->CONTENT->HARDWARE->WINPRODKEY = "";
                  }
               }
              break;

           case 'smodel':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->BIOS->SMODEL)) AND ($xml->CONTENT->BIOS->SMODEL == $blacklist_data['value'])) {
                     $xml->CONTENT->BIOS->SMODEL = "";
                  }
               }
              break;

           case 'storagesSerial':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if (isset($xml->CONTENT->STORAGES)) {
                     foreach($xml->CONTENT->STORAGES as $storage) {
                        if ((isset($storage->SERIALNUMBER)) AND ($storage->SERIALNUMBER == $blacklist_data['value'])) {
                           $storage->SERIALNUMBER = "";
                        }
                     }
                  }
               }
              break;

           case 'drivesSerial':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if (isset($xml->CONTENT->DRIVES)) {
                     foreach($xml->CONTENT->DRIVES as $drive) {
                        if ((isset($drive->SERIAL)) AND ($drive->SERIAL == $blacklist_data['value'])) {
                           $drive->SERIAL = "";
                        }
                     }
                  }
               }
              break;

           case 'assetTag':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->BIOS->ASSETTAG)) AND ($xml->CONTENT->BIOS->ASSETTAG == $blacklist_data['value'])) {
                     $xml->CONTENT->BIOS->ASSETTAG = "";
                  }
               }
              break;

           case 'manufacturer':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->BIOS->SMANUFACTURER)) AND ($xml->CONTENT->BIOS->SMANUFACTURER == $blacklist_data['value'])) {
                     $xml->CONTENT->BIOS->SMANUFACTURER = "";
                  }
               }
              break;
              
         }
      }
      // Blacklist mac of "miniport*" for windows because have same mac as principal network ports
      if (isset($xml->CONTENT->NETWORKS)) {
         foreach($xml->CONTENT->NETWORKS as $network) {
            if ((isset($network->DESCRIPTION))
                    AND ((string)$network->DESCRIPTION == "Miniport d'ordonnancement de paquets")) {
               $network->MACADDR = "";
            }
         }
      }
      return $xml;
   }
}

?>