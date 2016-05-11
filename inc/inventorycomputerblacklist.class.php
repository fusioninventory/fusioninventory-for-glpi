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

class PluginFusioninventoryInventoryComputerBlacklist extends CommonDBTM {

   static $rightname = 'plugin_fusioninventory_blacklist';


   static function getTypeName($nb=0) {
      return _n('Blacklist', 'Blacklists', $nb);
   }



   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('BlackList');


      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'value';
      $tab[1]['linkfield'] = 'value';
      $tab[1]['name']      = __('blacklisted value', 'fusioninventory');

      $tab[2]['table']     = 'glpi_plugin_fusioninventory_inventorycomputercriterias';
      $tab[2]['field']     = 'name';
      $tab[2]['linkfield'] = 'plugin_fusioninventory_criterium_id';
      $tab[2]['name']      = __('Type');
      $tab[2]['datatype']      = 'itemlink';
      $tab[2]['itemlink_type'] = 'PluginFusioninventoryInventoryComputerCriteria';

      return $tab;
   }



   function defineTabs($options=array()){

      $pfInventoryComputerCriteria = new PluginFusioninventoryInventoryComputerCriteria();

      $ong = array();
      $i = 1;
      $fields = $pfInventoryComputerCriteria->find("");
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
   * @return bool TRUE if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader();

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('blacklisted value', 'fusioninventory')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,'value');
      echo "</td>";
      echo "<td>".__('Type')."</td>";
      echo "<td>";
      Dropdown::show('PluginFusioninventoryInventoryComputerCriteria',
                     array('name' => 'plugin_fusioninventory_criterium_id',
                           'value' => $this->fields['plugin_fusioninventory_criterium_id']));
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons();

      return TRUE;
   }



   /**
   * Remove fields in XML from agent who are blacklisted
   *
   * @param $p_xml value XML from agent
   *
   * @return value XML cleaned (without blacklisted fields)
   *
   **/
   function cleanBlacklist($a_computerinventory) {

      $pfInventoryComputerCriteria = new PluginFusioninventoryInventoryComputerCriteria();
      $fields = $pfInventoryComputerCriteria->find("");
      foreach($fields as $id=>$data) {

         switch($data['comment']) {

            case 'ssn':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($a_computerinventory['Computer']['serial']))
                      && (strtolower($a_computerinventory['Computer']['serial'])
                              == strtolower($blacklist_data['value']))) {
                     $a_computerinventory['Computer']['serial'] = "";
                  }
                  if (((!isset($a_computerinventory['Computer']['serial']))
                          || ($a_computerinventory['Computer']['serial'] == ""))
                         && isset($a_computerinventory['Computer']['mserial'])) {
                     $a_computerinventory['Computer']['serial'] = $a_computerinventory['Computer']['mserial'];
                     foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                        if ($a_computerinventory['Computer']['serial'] == $blacklist_data['value']) {
                           $a_computerinventory['Computer']['serial'] = "";
                        }
                     }
                  }
                  if (isset($a_computerinventory['monitor'])) {
                     foreach($a_computerinventory['monitor'] as $num_m=>$data_m) {
                        if ((isset($data_m['serial']))
                            && (strtolower($data_m['serial'])
                                    == strtolower($blacklist_data['value']))) {
                           $a_computerinventory['monitor'][$num_m]['serial'] = "";
                        }
                     }
                  }
               }
               break;

            case 'uuid':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($a_computerinventory['Computer']['uuid']))
                        && (strtolower($a_computerinventory['Computer']['uuid'])
                                == strtolower($blacklist_data['value']))) {
                     $a_computerinventory['Computer']['uuid'] = "";
                  }
               }
               break;

            case 'macAddress':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if (isset($a_computerinventory['networkport'])) {
                     foreach($a_computerinventory['networkport'] as $key=>$network) {
                        if ((isset($network['mac']))
                                AND (strtolower($network['mac'])
                                        == strtolower($blacklist_data['value']))) {
                           $a_computerinventory['networkport'][$key]['mac'] = "";
                        }
                     }
                  }
               }
               break;

           case 'winProdKey':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($a_computerinventory['Computer']['os_license_number']))
                          && (strtolower($a_computerinventory['Computer']['os_license_number'])
                                  == strtolower($blacklist_data['value']))) {
                     $a_computerinventory['Computer']['os_license_number'] = "";
                  }
               }
              break;

           case 'smodel':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($a_computerinventory['Computer']['computermodels_id']))
                          && (strtolower($a_computerinventory['Computer']['computermodels_id'])
                                  == strtolower($blacklist_data['value']))) {
                     $a_computerinventory['Computer']['computermodels_id'] = "";
                  }
               }
               if (isset($a_computerinventory['Computer'])) {
                  if ($a_computerinventory['Computer']['computermodels_id'] == "") {
                     if (isset($a_computerinventory['Computer']['mmodel'])) {
                        $a_computerinventory['Computer']['computermodels_id'] =
                           $a_computerinventory['Computer']['mmodel'];

                        foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                           if ((isset($a_computerinventory['Computer']['computermodels_id']))
                                   && (strtolower($a_computerinventory['Computer']['computermodels_id'])
                                           == strtolower($blacklist_data['value']))) {
                              $a_computerinventory['Computer']['computermodels_id'] = "";
                              break;
                           }
                        }
                     }
                  }
               }
               break;

            case 'storagesSerial':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

//               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
//                  if (isset($arrayinventory['CONTENT']['STORAGES'])) {
//                     foreach($arrayinventory['CONTENT']['STORAGES'] as $key=>$storage) {
//                        if ((isset($storage['SERIALNUMBER']))
//                                AND ($storage['SERIALNUMBER'] == $blacklist_data['value'])) {
//                           $arrayinventory['CONTENT']['STORAGES'][$key]['SERIALNUMBER'] = "";
//                        }
//                     }
//                  }
//               }
              break;

           case 'drivesSerial':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

//               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
//                  if (isset($arrayinventory['CONTENT']['DRIVES'])) {
//                     foreach($arrayinventory['CONTENT']['DRIVES'] as $key=>$drive) {
//                        if ((isset($drive['SERIAL']))
//                                AND ($drive['SERIAL'] == $blacklist_data['value'])) {
//                           $arrayinventory['CONTENT']['DRIVES'][$key]['SERIAL'] = "";
//                        }
//                     }
//                  }
//               }
              break;

           case 'assetTag':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

//               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
//                  if ((isset($arrayinventory['CONTENT']['BIOS']['ASSETTAG']))
//                          AND ($arrayinventory['CONTENT']['BIOS']['ASSETTAG'] ==
//                               $blacklist_data['value'])) {
//                     $arrayinventory['CONTENT']['BIOS']['ASSETTAG'] = "";
//                  }
//               }
              break;

           case 'manufacturer':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($a_computerinventory['Computer']['manufacturers_id']))
                          && (strtolower($a_computerinventory['Computer']['manufacturers_id'])
                                  == strtolower($blacklist_data['value']))) {
                     $a_computerinventory['Computer']['manufacturers_id'] = "";
                     break;
                  }
               }
               if (isset($a_computerinventory['Computer'])) {
                  if ($a_computerinventory['Computer']['manufacturers_id'] == "") {
                     if (isset($a_computerinventory['Computer']['mmanufacturer'])) {
                        $a_computerinventory['Computer']['manufacturers_id'] =
                           $a_computerinventory['Computer']['mmanufacturer'];

                        foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                           if ((isset($a_computerinventory['Computer']['manufacturers_id']))
                                   && (strtolower($a_computerinventory['Computer']['manufacturers_id'])
                                           == strtolower($blacklist_data['value']))) {
                              $a_computerinventory['Computer']['manufacturers_id'] = "";
                              break;
                           }
                        }
                     }
                  }
                  if ($a_computerinventory['Computer']['manufacturers_id'] == "") {
                     if (isset($a_computerinventory['Computer']['bmanufacturer'])) {
                        $a_computerinventory['Computer']['manufacturers_id'] =
                              $a_computerinventory['Computer']['bmanufacturer'];

                        foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                           if ((isset($a_computerinventory['Computer']['manufacturers_id']))
                                   && (strtolower($a_computerinventory['Computer']['manufacturers_id'])
                                           == strtolower($blacklist_data['value']))) {
                              $a_computerinventory['Computer']['manufacturers_id'] = "";
                              break;
                           }
                        }
                     }
                  }
               }
              break;

            case 'IP':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if (isset($a_computerinventory['networkport'])) {
                     foreach ($a_computerinventory['networkport'] as $key=>$netport_data) {
                        foreach ($netport_data['ipaddress'] as $num_ip=>$ip) {
                           if ($ip == $blacklist_data['value']) {
                              unset($a_computerinventory['networkport'][$key]['ipaddress'][$num_ip]);
                           }
                        }
                     }
                  }
               }
               break;

         }
      }
      // Blacklist mac of "miniport*" for windows because have same mac as principal network ports
      if (isset($a_computerinventory['networkport'])) {
         foreach($a_computerinventory['networkport'] as $key=>$network) {
            if ((isset($network['name']))
                    AND (strtolower($network['name']) =="miniport d'ordonnancement de paquets")) {
               $a_computerinventory['networkport'][$key]['mac'] = "";
            }
         }
      }
      return $a_computerinventory;
   }
}

?>
