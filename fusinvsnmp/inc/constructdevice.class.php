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

class PluginFusinvsnmpConstructDevice extends CommonDBTM {

   function showForm($id, $options=array()) {
      global $DB,$LANG;

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][5].":    </td><td>";
      Dropdown::show("Manufacturer",
                     array('name'=>"manufacturers_id",
                           'value'=>$this->fields["manufacturers_id"]));
      echo "</td>";

      echo "<tr>";
      echo "<td>".$LANG['setup'][71].":    </td><td>\n";
      Dropdown::show("NetworkEquipmentFirmware",
                     array('name'=>"firmware",
                           'value'=>$this->fields["firmware"]));
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>" . $LANG['common'][25] . "</td>";
      echo "<td>";
      echo "<textarea name='sysdescr'  cols='110' rows='4' />".$this->fields["sysdescr"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>" . $LANG['common'][17] . " :</td>";
      echo "<td>";
         $type_list = array();
         $type_list[] = COMPUTER_TYPE;
         $type_list[] = NETWORKING_TYPE;
         $type_list[] = PRINTER_TYPE;
         $type_list[] = PERIPHERAL_TYPE;
         $type_list[] = PHONE_TYPE;

         // GENERIC OBJECT : Search types in generic object
         $plugin = new Plugin();
         if ($plugin->isActivated('genericobject')) {
            if (TableExists("glpi_plugin_genericobject_types")) {
               $query = "SELECT * FROM `glpi_plugin_genericobject_types`
                  WHERE `status`='1' ";
               if ($result=$DB->query($query)) {
                  while ($data=$DB->fetch_array($result)) {
                     $type_list[] = $data['itemtype'];
                  }
               }
            }
         }
         // END GENERIC OBJECT
         Device::dropdownTypes('type',$this->fields["type"],$type_list);
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
   }


   
   function manageWalks($target, $id) {
      global $DB,$CFG_GLPI,$LANG;

      $query = "SELECT * FROM glpi_plugin_fusinvsnmp_constructdevices
         WHERE id='".$id."'";
      $result = $DB->query($query);
      $a_device = $DB->fetch_assoc($result);
      $type_model = $a_device['type'];

      // List of OID with relations by default with mapping
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.23.1.2.1.1.4']      = 'cdpCacheAddress';
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.23.1.2.1.1.7']      = 'cdpCacheDevicePort';
      $mapping_pre[2]['.1.3.6.1.2.1.1.1.0']                 = 'comment';
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.109.1.1.1.1.3.1']   = 'cpu';
      $mapping_pre[2]['.1.3.6.1.2.1.17.1.4.1.2']            = 'dot1dBasePortIfIndex';
      $mapping_pre[2]['.1.3.6.1.2.1.17.4.3.1.1']            = 'dot1dTpFdbAddress';
      $mapping_pre[2]['.1.3.6.1.2.1.17.4.3.1.2']            = 'dot1dTpFdbPort';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.13.1001']    = 'entPhysicalModelName';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.9.1001']     = 'firmware';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.2']               = 'ifdescr';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.1']               = 'ifIndex';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.14']              = 'ifinerrors';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.10']              = 'ifinoctets';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.7']               = 'ifinternalstatus';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.9']               = 'iflastchange';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.4']               = 'ifmtu';
      $mapping_pre[2]['.1.3.6.1.2.1.31.1.1.1.1']            = 'ifName';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.20']              = 'ifouterrors';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.16']              = 'ifoutoctets';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.6']               = 'ifPhysAddress';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.5']               = 'ifspeed';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.8']               = 'ifstatus';
      $mapping_pre[2]['.1.3.6.1.2.1.2.2.1.3']               = 'ifType';
      $mapping_pre[2]['.1.3.6.1.2.1.4.20.1.1']              = 'ipAdEntAddr';
      $mapping_pre[2]['.1.3.6.1.2.1.4.22.1.2']              = 'ipNetToMediaPhysAddress';
      $mapping_pre[2]['.1.3.6.1.2.1.1.6.0']                 = 'location';
      $mapping_pre[2]['.1.3.6.1.2.1.17.1.1.0']              = 'macaddr';
      $mapping_pre[2]['.1.3.6.1.4.1.9.2.1.8.0']             = 'memory';
      $mapping_pre[2]['.1.3.6.1.2.1.1.5.0']                 = 'name';
      $mapping_pre[2]['.1.3.6.1.4.1.9.3.6.6.0']             = 'ram';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.11.1001']    = 'serial';
      $mapping_pre[2]['.1.3.6.1.2.1.1.3.0']                 = 'uptime';
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.46.1.6.1.1.14']     = 'vlanTrunkPortDynamicStatus';
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.68.1.2.2.1.2']      = 'vmvlan';
      $mapping_pre[2]['.1.3.6.1.4.1.9.9.46.1.3.1.1.4.1']    = 'vtpVlanName';
      $mapping_pre[2]['.1.3.6.1.2.1.17.7.1.4.3.1.1']        = 'vtpVlanName';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.11.1001']    = 'serial';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.9.1']        = 'firmware1';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.10.1']       = 'firmware2';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.11.1']       = 'serial';
      $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.13.1']       = 'entPhysicalModelName';
      // Omnistack LS6200 :
         $mapping_pre[2]['.1.3.6.1.2.1.47.1.1.1.1.11.67108992'] = 'serial';
         /*
          * firmware: iso.3.6.1.2.1.47.1.1.1.1.10.67108992
          * modele: iso.3.6.1.2.1.47.1.1.1.1.2.68420352
          * MAC: .1.3.6.1.2.1.17.1.1.0
          *
          */



      $mapping_pre[3]['.1.3.6.1.4.1.641.2.1.2.1.2.1']                = 'model';
      $mapping_pre[3]['.1.3.6.1.4.1.641.2.1.2.1.6.1']                = 'serial';
      $mapping_pre[3]['.1.3.6.1.2.1.25.2.3.1.5.1']                   = 'memory';
      $mapping_pre[3]['.1.3.6.1.2.1.43.8.2.1.14.1.1']                = 'enterprise';
      $mapping_pre[3]['.1.3.6.1.2.1.4.20.1.2']                       = 'ip';
      $mapping_pre[3]['.1.3.6.1.2.1.2.2.1.2']                        = 'ifName';
      $mapping_pre[3]['.1.3.6.1.2.1.2.2.1.6']                        = 'ifPhysAddress';
      $mapping_pre[3]['.1.3.6.1.2.1.2.2.1.3']                        = 'ifType';
      $mapping_pre[3]['.1.3.6.1.2.1.1.5.0']                          = 'name';
      $mapping_pre[3]['.1.3.6.1.2.1.1.6.0']                          = 'location';
      $mapping_pre[3]['.1.3.6.1.2.1.1.1.0']                          = 'comment';
      $mapping_pre[3]['.1.3.6.1.2.1.2.2.1.1']                        = 'ifIndex';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.1.4.0']              = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.2']         = 'cartridgescyan';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.1']         = 'cartridgesblack';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.3']         = 'cartridgesmagenta';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.4']         = 'cartridgesyellow';
      $mapping_pre[3]['.1.3.6.1.2.1.43.10.2.1.4.1.1']                = 'pagecountertotalpages';
      $mapping_pre[3]['.1.3.6.1.2.1.43.5.1.1.17.1']                  = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.13']        = 'pagecountercolorpages';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.14']        = 'pagecounterblackpages';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.2']         = 'pagecountertotalpages_copy';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.3']         = 'pagecounterblackpages_copy';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.5']         = 'pagecountercolorpages_copy';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.6']         = 'pagecountertotalpages_fax';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.8']         = 'pagecountertotalpages_print';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.20']        = 'pagecounterblackpages_print';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.19']        = 'pagecountercolorpages_print';
      $mapping_pre[3]['.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.47']        = 'pagecounterscannedpages';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.4.2.1.1.3.12.0']        = 'otherserial';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.4.2.1.1.16.4.1.1.1.0']  = 'pagecounterblackpages';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.4.2.1.1.16.4.1.3.1.0']  = 'pagecountercolorpages';
      $mapping_pre[3]['.1.3.6.1.4.1.1129.2.3.50.1.3.21.6.1.3.1.3']   = 'pagecountertotalpages_print';
      $mapping_pre[3]['.1.3.6.1.4.1.1129.2.3.50.1.3.21.6.1.3.1.3']   = 'pagecountertotalpages_print';
      $mapping_pre[3]['.1.3.6.1.4.1.1129.2.3.50.1.3.21.6.1.4.1.3']   = 'pagecountertotalpages_copy';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.1.1.7.0']               = 'informations';
      $mapping_pre[3]['.1.3.6.1.4.1.674.10898.100.2.1.2.1.6.1']      = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.1602.1.2.1.4.0']                 = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.11.2.3.9.4.2.1.1.3.3.0']         = 'serial';
      $mapping_pre[3]['.1.3.6.1.4.1.2435.2.3.9.4.2.1.5.5.1.0']       = 'serial';




      $mapping_pre[1]['.1.3.6.1.4.1.714.1.2.5.3.5.0'] = 'serial';
      $mapping_pre[1]['.1.3.6.1.2.1.2.2.1.6']         = 'ifPhysAddress';

//      $mapping_pre[3][''] = '';
      $mapping_pre_vlan = array();
      $mapping_pre_vlan['.1.3.6.1.4.1.9.9.46.1.6.1.1.14'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.17.4.3.1.1'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.4.22.1.2'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.17.4.3.1.2'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.17.1.4.1.2'] = '1';



      // Used mapping name :
      $a_mapping_used = array();
      $query = "SELECT `glpi_plugin_fusinvsnmp_constructdevice_miboids`.*,
             `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`
         FROM `glpi_plugin_fusinvsnmp_constructdevice_miboids`
            LEFT JOIN `glpi_plugin_fusioninventory_mappings`
               ON `glpi_plugin_fusinvsnmp_constructdevice_miboids`.`plugin_fusioninventory_mappings_id`=
                  `glpi_plugin_fusioninventory_mappings`.`id`
         WHERE `plugin_fusinvsnmp_constructdevices_id`='".$id."'
            AND `mapping_name` != ''";
      if ($result = $DB->query($query)) {
         while ($data = $DB->fetch_array($result)) {
            $a_mapping_used[$data['itemtype']."||".$data['mapping_name']] =
                  $data['itemtype']."||".$data['mapping_name'];
         }
      }

      $query = "SELECT * FROM glpi_plugin_fusinvsnmp_constructdevicewalks
         WHERE plugin_fusinvsnmp_constructdevices_id='".$id."'";
      echo "<div align='center'>
         <form method='post' name='' id=''  action='".$target."' >";

      $a_oids = array();
      $a_oids1 = array();
      $a_oids2 = array();
      $a_mibs = array();
      if ($result = $DB->query($query)) {
         if ($data = $DB->fetch_array($result)) {
            $file_content = file(GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$data['log']);
            echo $data['log']."<br/>";
            $query_oid = "SELECT * FROM glpi_plugin_fusinvsnmp_miboids";
            $result_oid = $DB->query($query_oid);
            while ($fields_oid = $DB->fetch_array($result_oid)) {
               if ($fields_oid['comment'] != "") {
                  $a_oids[] = $fields_oid['comment'];
                  $a_oids1[] = $fields_oid['name'];
                  $a_oids2[] = $fields_oid['id'];
               }
            }
            $before = '';
            $oid_id_before = 0;
            foreach($file_content as $line){
                $i = 1;
               foreach($a_oids as $num=>$oid){
                  if ((strstr($line, $oid.".")) OR (strstr($line, $oid." "))) {
                     if (($i == '1') AND ($before != $a_oids1[$num])) {
                        if ($before != '') {
                           echo "</td>";
                           echo "</tr>";
                           echo "<tr>";
                           echo "<th>";
                           echo $LANG['plugin_fusioninventory']['mib'][9]." : ";
                           if (isset($a_mibs['id'])) {
                              if ($a_mibs["vlan"] == "1") {
                                 echo "<a href='".$target."?id=".$id."&vlan_update=".$oid_id_before."'>";
                                 echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                                 echo "</a>";
                              } else {
                                 echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark_off.png'/>";
                              }
                           } else {
                              if (isset($mapping_pre_vlan[$before])) {
                                 Dropdown::showYesNo("vlan_".$oid_id_before, 1);
                              } else {
                                 Dropdown::showYesNo("vlan_".$oid_id_before);
                              }
                           }
                           echo "</th>";
                           echo "<th>";
                           echo $LANG['plugin_fusioninventory']['mib'][6]." : ";
                           if (isset($a_mibs['id'])) {
                              if ($a_mibs["oid_port_counter"] == "1") {
                                 echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                              } else {
                                 echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark_off.png'/>";
                              }
                           } else {
                              Dropdown::showYesNo("oid_port_counter_".$oid_id_before);
                           }
                           echo "</th>";
                           echo "<th>";
                           echo $LANG['plugin_fusioninventory']['mib'][7]." : ";
                           if (isset($a_mibs['id'])) {
                              if ($a_mibs["oid_port_dyn"] == "1") {
                                 echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                              } else {
                                 echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark_off.png'/>";
                              }
                           } else {
                              Dropdown::showYesNo("oid_port_dyn_".$oid_id_before);
                           }
                           echo "</th>";
                           echo "</tr>";
                           echo "<tr>";
                           echo "<th colspan='3'>";
                           echo $LANG['plugin_fusioninventory']['mib'][8]." : ";
                           if (isset($a_mibs['id'])) {
                              if ($a_mibs["oid_port_counter"] == "0") {
                                 $mapping = new PluginFusioninventoryMapping();
                                 $mappings = $mapping->get($a_mibs['itemtype'], $a_mibs['mapping_name']);
                                 echo $LANG['plugin_fusinvsnmp']['mapping'][$mappings->fields['locale']]." ( ".$a_mibs["mapping_name"]." )";
                              }
                           } else {
                              $types = array();
                              $types[] = "-----";

                              $map = new PluginFusioninventoryMapping();
                              $maps = $map->find();
                              foreach ($maps as $mapfields) {
                                 if (($type_model == $type) OR ($type_model == "0")) {
                                    $types[$type."||".$mapfields['name']]=
                                       $LANG['plugin_fusinvsnmp']['mapping']
                                          [$mapfields["locale"]].
                                       " (".$mapfields['name'].")";
                                 }
                              }
                              Dropdown::showFromArray("links_oid_fields_".$oid_id_before, $types,
                                                      array('value'=>$type_model."||".$mapping_pre[$type_model][$before],
                                                            'used'=>$a_mapping_used)); //,$linkoid_used
                           }
                           echo "</th>";
                           echo "</tr>";
                           echo "</table>";
                           echo "<br/>";
                        }
                        $query_oid_mib = "SELECT *
                           FROM glpi_plugin_fusinvsnmp_constructdevice_miboids
                           WHERE plugin_fusinvsnmp_constructdevices_id='".$id."'
                              AND plugin_fusinvsnmp_miboids_id='".$a_oids2[$num]."'";
                        $a_mibs = array();
                        $result_oid_mib = $DB->query($query_oid_mib);
                        if ($DB->numrows($result_oid_mib) != "0") {
                           $a_mibs = $DB->fetch_assoc($result_oid_mib);
                        }
                        echo "<table class='tab_cadre' cellpadding='5' width='950'>";
                        echo "<tr>";
                        echo "<th colspan='3'>";
                        if (isset($a_mibs['id'])) {
                           echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                           echo "&nbsp;<font>";
                        } else {
                           echo "<input type='checkbox' name='oidsselected[]' value='".$a_oids2[$num]."' />&nbsp;";
                           echo "&nbsp;<font color='#ff0000'>";
                        }
                        echo $a_oids1[$num]."</font>";
                        if (isset($a_mibs['id'])) {
                           //echo "&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/delete.png'/>";
                        }
                        echo "</th>";
                        echo "</tr>";
                        echo "<tr class='tab_bg_1 center'>";
                        echo "<td colspan='3'>";
                     }
                     if (!isset($a_mibs['id'])) {
                        echo $line."<br/>";
                     }
                     $before = $a_oids1[$num];
                     $oid_id_before = $a_oids2[$num];
                     $i = 2;
                  }
               }              
            }
            if ($before != '') {
               echo "</td>";
               echo "</tr>";
               echo "<tr>";
               echo "<th>";
               echo $LANG['plugin_fusioninventory']['mib'][9]." : ";
               if (isset($a_mibs['id'])) {
                  if ($a_mibs["vlan"] == "1") {
                     echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                  } else {
                     echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark_off.png'/>";
                  }
               } else {
                  if (isset($mapping_pre_vlan[$before])) {
                     Dropdown::showYesNo("vlan_".$oid_id_before, 1);
                  } else {
                     Dropdown::showYesNo("vlan_".$oid_id_before);
                  }
               }
               echo "</th>";
               echo "<th>";
               echo $LANG['plugin_fusioninventory']['mib'][6]." : ";
               Dropdown::showYesNo("oid_port_counter_".$oid_id_before);
               echo "</th>";
               echo "<th>";
               echo $LANG['plugin_fusioninventory']['mib'][7]." : ";
               Dropdown::showYesNo("oid_port_dyn_".$oid_id_before);
               echo "</th>";
               echo "</tr>";
               echo "<tr>";
               echo "<th colspan='3'>";
               echo $LANG['plugin_fusioninventory']['mib'][8]." : ";
               if (isset($a_mibs['id'])) {
                  if ($a_mibs["oid_port_counter"] == "0") {
                     $mapping = new PluginFusioninventoryMapping();
                     $mappings = $mapping->get($a_mibs['itemtype'], $a_mibs['mapping_name']);
                     if ($mappings) {
                        echo $LANG['plugin_fusinvsnmp']['mapping'][$mappings->fields['locale']];
                     }
                  }
               } else {
                  $types = array();
                  $types[] = "-----";

                  $map = new PluginFusioninventoryMapping();
                  $maps = $map->find();
                  foreach ($maps as $mapfields) {
                     if (($type_model == $type) OR ($type_model == "0")) {
                        $types[$type."||".$mapfields['name']]=
                           $LANG['plugin_fusinvsnmp']['mapping']
                              [$mapfields["locale"]].
                           " (".$mapfields['name'].")";
                     }
                  }
                  Dropdown::showFromArray("links_oid_fields_".$oid_id_before, $types,
                                          array('value'=>$type_model."||".$mapping_pre[$type_model][$before],
                                                'used'=>$a_mapping_used)); //,$linkoid_used
               }
               echo "</th>";
               echo "</tr>";
               echo "</table>";
               echo "<br/>";
            }
         }
      }
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      echo "<tr class='tab_bg_1 center'>";
      echo "<td>";
      echo "<input type='hidden' name='id' value='" . $id . "'/>";
      echo "&nbsp;<input type='submit' name='mib' value=\"" . $LANG["buttons"][7] . "\" class='submit' >";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</form><br/>";

      echo "<form method='post' name='' id=''  action='".$target."' enctype=\"multipart/form-data\">";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      echo "<tr class='tab_bg_1 center'>";
      echo "<td>";
      echo "<input type='hidden' name='id' value='" . $id . "'/>";
      echo "<input type='file' name='walk'/>";
      echo "&nbsp;<input type='submit' name='addWalk' value=\"" . $LANG["buttons"][8] . "\" class='submit' >";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</form></div>";
   }

   

   function generatemodels() {
      global $DB;

      $ptmi = new PluginFusinvsnmpModel();
      $ptmn = new PluginFusinvsnmpModelMib();

      $query = "SELECT glpi_plugin_fusinvsnmp_constructdevices.id, type
         FROM glpi_plugin_fusinvsnmp_constructdevices
         LEFT JOIN glpi_plugin_fusinvsnmp_constructdevicewalks on glpi_plugin_fusinvsnmp_constructdevices.id = plugin_fusinvsnmp_constructdevices_id
         WHERE type IN (1,2,3)
            AND log!=''";
      if ($result = $DB->query($query)) {
         while ($data = $DB->fetch_array($result)) {
            // Load mibs
            $a_mib = array();
            $count_mib = 0;
            $query_mibs = "SELECT `glpi_plugin_fusinvsnmp_constructdevice_miboids`.*,
                  `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`,
                  `glpi_plugin_fusioninventory_mappings`.`itemtype`
               FROM `glpi_plugin_fusinvsnmp_constructdevice_miboids`
                  LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                     ON `glpi_plugin_fusinvsnmp_constructdevice_miboids`.`plugin_fusioninventory_mappings_id`=
                        `glpi_plugin_fusioninventory_mappings`.`id`
               WHERE plugin_fusinvsnmp_constructdevices_id='".$data["id"]."' ";
            if ($result_mibs = $DB->query($query_mibs)) {
               while ($data_mibs = $DB->fetch_array($result_mibs)) {
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['itemtype'] = $data_mibs['itemtype'];
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['mapping_name'] = $data_mibs['mapping_name'];
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['oid_port_counter'] = $data_mibs['oid_port_counter'];
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['oid_port_dyn'] = $data_mibs['oid_port_dyn'];
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['vlan'] = $data_mibs['vlan'];
                  $count_mib++;
               }
            }

            // See if model exactly exists
            $query_models = "SELECT * FROM glpi_plugin_fusinvsnmp_models";
            $existent = 0;
            if ($result_models = $DB->query($query_models)) {
               while ($data_models = $DB->fetch_array($result_models)) {
                  if ($existent != '1') {
                     $count_mib_model = 0;
                     $query_mibs_model = "SELECT `glpi_plugin_fusinvsnmp_modelmibs`.*,
                           `glpi_plugin_fusioninventory_mappings`.`itemtype`,
                           `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`
                        FROM `glpi_plugin_fusinvsnmp_modelmibs`
                           LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                              ON `glpi_plugin_fusinvsnmp_modelmibs`.`plugin_fusinvsnmp_models_id`=
                                 `glpi_plugin_fusioninventory_mappings`.`id`
                        WHERE `plugin_fusinvsnmp_models_id`='".$data_models['id']."' ";
                     if ($result_mib_model = $DB->query($query_mibs_model)) {
                        while ($data_mib_model = $DB->fetch_array($result_mib_model)) {
                           $count_mib_model++;
                           if ($existent != '-1') {
                              if (isset($a_mib[$data_mib_model['plugin_fusinvsnmp_miboids_id']]['itemtype'])) {
                                 // Oid Existe, on vérifie si tous les paramètres sont pareils
                                 if ($a_mib[$data_mib_model['plugin_fusinvsnmp_miboids_id']]['itemtype'] == $data_mib_model['itemtype'] AND
                                    $a_mib[$data_mib_model['plugin_fusinvsnmp_miboids_id']]['mapping_name'] == $data_mib_model['mapping_name'] AND
                                    $a_mib[$data_mib_model['plugin_fusinvsnmp_miboids_id']]['oid_port_counter'] == $data_mib_model['oid_port_counter'] AND
                                    $a_mib[$data_mib_model['plugin_fusinvsnmp_miboids_id']]['oid_port_dyn'] == $data_mib_model['oid_port_dyn'] AND
                                    $a_mib[$data_mib_model['plugin_fusinvsnmp_miboids_id']]['vlan'] == $data_mib_model['vlan']) {

                                 } else {
                                    $existent = '-1';
                                 }
                              } else {
                                 $existent = '-1';
                              }
                           }
                        }
                     }
                     if (($existent == '0') AND ($count_mib == $count_mib_model)) {
                        // Add number in database
                        $query_update = "UPDATE glpi_plugin_fusinvsnmp_constructdevices
                           SET snmpmodel_id='".$data_models['id']."'
                           WHERE id='".$data["id"]."'";
                        $DB->query($query_update);
                        $existent = 1;
                     } else {
                        $existent = 0;
                     }
                  }
               }
            }
            if ($existent != '1') {
               // Create model
               $a_input = array();
               $a_input['name'] = rand(10000, 10000000);
               $a_input['itemtype'] = $data["type"];
               $a_input['is_active'] = 1;
               $id = $ptmi->add($a_input);
               
               $query_mibs = "SELECT `glpi_plugin_fusinvsnmp_constructdevice_miboids`.*,
                  `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`,
                  `glpi_plugin_fusioninventory_mappings`.`itemtype`
               FROM `glpi_plugin_fusinvsnmp_constructdevice_miboids`
                  LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                     ON `glpi_plugin_fusinvsnmp_constructdevice_miboids`.`plugin_fusioninventory_mappings_id`=
                        `glpi_plugin_fusioninventory_mappings`.`id`
               WHERE `plugin_fusinvsnmp_constructdevices_id`='".$data["id"]."' ";
               if ($result_mibs = $DB->query($query_mibs)) {
                  while ($data_mibs = $DB->fetch_array($result_mibs)) {
                     $a_input = array();
                     $a_input['plugin_fusinvsnmp_models_id'] = $id;
                     $a_input['plugin_fusinvsnmp_miboids_id'] = $data_mibs['plugin_fusinvsnmp_miboids_id'];
                     $a_input['oid_port_counter'] = $data_mibs['oid_port_counter'];
                     $a_input['oid_port_dyn'] = $data_mibs['oid_port_dyn'];
                     $a_input['vlan'] = $data_mibs['vlan'];
                     $a_input['links_oid_fields'] = $data_mibs['itemtype']."||".$data_mibs['mapping_name'];
                     $a_input['is_active'] = 1;
                     $ptmn->add($a_input);
                  }
               }
               $query_update = "UPDATE glpi_plugin_fusinvsnmp_constructdevices
                  SET snmpmodel_id='".$id."'
                  WHERE id='".$data["id"]."'";
               $DB->query($query_update);

            }
         }
      }

       // Add Number
       //key : Networking0006
      $query = "SELECT *
               FROM glpi_plugin_fusinvsnmp_models
               WHERE discovery_key LIKE 'Networking%'
               ORDER BY discovery_key DESC
               LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);
      $num = 1;
      if (!empty($data['discovery_key'])) {
         $num = str_replace('Networking', '', $data['discovery_key']);
         $num++;
      }

      $query = "SELECT *
               FROM glpi_plugin_fusinvsnmp_models
               WHERE (discovery_key IS NULL OR discovery_key='')
                  AND itemtype='".NETWORKING_TYPE."' ";
      if ($result = $DB->query($query)) {
         while ($data = $DB->fetch_array($result)) {
            while(strlen($num) < 4)
               $num = "0" . $num;
            $query_update = "UPDATE glpi_plugin_fusinvsnmp_models
               SET discovery_key='Networking".$num."'
                  WHERE id='".$data['id']."'";
            $DB->query($query_update);
            $num++;
         }
      }
      // Printers
      $query = "SELECT *
               FROM glpi_plugin_fusinvsnmp_models
               WHERE discovery_key LIKE 'Printer%'
               ORDER BY discovery_key DESC
               LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);
      if (empty($data['discovery_key'])) {
         $num = '1';
      } else {
         $num = str_replace('Printer', '', $data['discovery_key']);
         $num++;
      }

      $query = "SELECT *
               FROM glpi_plugin_fusinvsnmp_models
               WHERE (discovery_key IS NULL OR discovery_key='')
                  AND itemtype='".PRINTER_TYPE."' ";
      if ($result = $DB->query($query)) {
         while ($data = $DB->fetch_array($result)) {
            while(strlen($num) < 4)
               $num = "0" . $num;
            $query_update = "UPDATE glpi_plugin_fusinvsnmp_models
               SET discovery_key='Printer".$num."'
                  WHERE id='".$data['id']."'";
            $DB->query($query_update);
            $num++;
         }
      }
   }



   function generateDiscovery() {
      global $DB;
      
      $xmlstr = "<?xml version='1.0' encoding='UTF-8'?>
<SNMPDISCOVERY>
</SNMPDISCOVERY>";
      $sxml = new SimpleXMLElement($xmlstr);
      //$sxml = simplexml_load_file($xmlstr);

      $query = "SELECT *
               FROM `".$this->getTable()."`
               WHERE type NOT IN('', 0) ";
      if ($result = $DB->query($query)) {
         while ($data = $DB->fetch_array($result)) {
            $sxml_device = $sxml->addChild('DEVICE');
            $sxml_device->addAttribute('SYSDESCR', $data['sysdescr']);
            $sxml_device->addAttribute('MANUFACTURER', $data['manufacturers_id']); //dropdown
            $sxml_device->addAttribute('TYPE', $data['type']);

            if (($data['snmpmodel_id'] !='0') AND ($data['snmpmodel_id'] != '')) {
               //$sxml_device->addAttribute('MODELSNMP', $data['snmpmodel_id']); //dropdown

               $query_modelkey = "SELECT *
                                 FROM `glpi_plugin_fusinvsnmp_models`
                                 WHERE id='".$data['snmpmodel_id']."'
                                 LIMIT 1";
               $result_modelkey=$DB->query($query_modelkey);
               if ($DB->numrows($result_modelkey)) {
                  $line = mysql_fetch_assoc($result_modelkey);
                  $sxml_device->addAttribute('MODELSNMP', $line['discovery_key']);
               }               

               $query_serial = "SELECT `glpi_plugin_fusinvsnmp_constructdevice_miboids`.*,
                     `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`
                  FROM `glpi_plugin_fusinvsnmp_constructdevice_miboids`
                     LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                        ON `glpi_plugin_fusinvsnmp_constructdevice_miboids`.`plugin_fusioninventory_mappings_id`=
                           `glpi_plugin_fusioninventory_mappings`.`id`
                  WHERE `plugin_fusinvsnmp_constructdevices_id`='".$data['id']."'
                     AND `mapping_name`='serial'
                  LIMIT 1";
               $result_serial=$DB->query($query_serial);
               if ($DB->numrows($result_serial)) {
                  $line = mysql_fetch_assoc($result_serial);
                  $sxml_device->addAttribute('SERIAL', Dropdown::getDropdownName('glpi_plugin_fusinvsnmp_miboids',
                                               $line['plugin_fusinvsnmp_miboids_id']));
               }

               $query_serial = "SELECT `glpi_plugin_fusinvsnmp_constructdevice_miboids`.*,
                     `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`,
                     `glpi_plugin_fusioninventory_mappings`.`itemtype`
                  FROM `glpi_plugin_fusinvsnmp_constructdevice_miboids`
                     LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                        ON `glpi_plugin_fusinvsnmp_constructdevice_miboids`.`plugin_fusioninventory_mappings_id`=
                           `glpi_plugin_fusioninventory_mappings`.`id`
                  WHERE `plugin_fusinvsnmp_constructdevices_id`='".$data['id']."'
                     AND ((`mapping_name`='macaddr' AND `itemtype`='NetworkEquipment')
                           OR ( `mapping_name`='ifPhysAddress' AND `itemtype`='Printer')
                           OR ( `mapping_name`='ifPhysAddress' AND `itemtype`='Computer'))
                  LIMIT 1";
               $result_serial=$DB->query($query_serial);
               if ($DB->numrows($result_serial)) {
                  $line = mysql_fetch_assoc($result_serial);
                  if ($line['mapping_name'] == "macaddr") {
                     $sxml_device->addAttribute('MAC', Dropdown::getDropdownName('glpi_plugin_fusinvsnmp_miboids',
                                                   $line['plugin_fusinvsnmp_miboids_id']));
                  } else {
                     $sxml_device->addAttribute('MACDYN', Dropdown::getDropdownName('glpi_plugin_fusinvsnmp_miboids',
                                                   $line['plugin_fusinvsnmp_miboids_id']));
                  }
               }
            }
         }
      }
      $sxml = $this->formatXmlString($sxml);
      echo $sxml->asXML();
      file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/discovery.xml", $sxml->asXML());

   }

   

   function formatXmlString($sxml) {
      $xml = str_replace("><", ">\n<", $sxml->asXML());
      $xml = str_replace("^M", "", $xml);
      $token      = strtok($xml, "\n");
      $result     = '';
      $pad        = 0;
      $matches    = array();
      $indent     = 0;

      while ($token !== false) {
         // 1. open and closing tags on same line - no change
         if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
            $indent=0;
         // 2. closing tag - outdent now
         elseif (preg_match('/^<\/\w/', $token, $matches)) :
            $pad = $pad-3;
         // 3. opening tag - don't pad this one, only subsequent tags
         elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
            $indent=3;
         else :
            $indent = 0;
         endif;

         $line    = str_pad($token, strlen($token)+$pad, '  ', STR_PAD_LEFT);
         $result .= $line . "\n";
         $token   = strtok("\n");
         $pad    += $indent;
      }
      $sxml = simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
      return $sxml;
   }


   
   function cleanmodels() {
      global $DB;

      $query_models = "SELECT * FROM glpi_plugin_fusinvsnmp_models";
      if ($result_models = $DB->query($query_models)) {
         while ($data_models = $DB->fetch_array($result_models)) {
            $query = "SELECT * FROM glpi_plugin_fusinvsnmp_constructdevices
               WHERE snmpmodel_id='".$data_models['id']."' ";
            if ($result = $DB->query($query)) {
               if ($DB->numrows($result) == 0) {
                  // Delete model
                  $query_delete = "DELETE FROM glpi_plugin_fusinvsnmp_models
                     WHERE id='".$data_models['id']."'";
                  $DB->query($query_delete);
               }
            }
         }
       }
   }

   

   function exportmodels() {
      global $DB;

      $pfiie = new PluginFusinvsnmpImportExport();

      $query_models = "SELECT * FROM glpi_plugin_fusinvsnmp_models";
      if ($result_models = $DB->query($query_models)) {
         while ($data = $DB->fetch_array($result_models)) {
            $xml = $pfiie->export($data['id']);
            file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/models/".$data['name'].".xml", $xml);
         }
      }
   }

   

   function generatecomments() {
      global $DB;

      $query_clean = "UPDATE `glpi_plugin_fusinvsnmp_models`
         SET comment='' ";
      $DB->query($query_clean);

      $a_devices = $this->find("snmpmodel_id > 0", "sysdescr");
      $a_comments = array();
      if (count($a_devices)){
         foreach ($a_devices as $device){
            if (!isset($a_comments[$device['snmpmodel_id']])) {
               $a_comments[$device['snmpmodel_id']] = "";
            }
            $a_comments[$device['snmpmodel_id']] .= $device['sysdescr']."\n\n";
         }
      }
      foreach ($a_comments as $model_id=>$comment) {
         $query_update = "UPDATE `glpi_plugin_fusinvsnmp_models`
            SET comment='".$comment."'
            WHERE id='".$model_id."' ";
         $DB->query($query_update);
      }      
   }
}

?>