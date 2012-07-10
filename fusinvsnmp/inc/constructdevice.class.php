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

   function showForm($id, $data) {
      global $DB,$LANG,$CFG_GLPI;

      $options = array();

      echo  "<table width='950' align='center'>
         <tr>
         <td>
         <a href='".$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/constructmodel.php?reset=reset'>Revenir au menu principal</a>
         </td>
         </tr>
         </table>";
      
      echo "<form name='form' method='post' action='".$this->getFormURL()."'>";
      
      $this->manageWalks($data);

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>";
      echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit'>";
      echo "</th>";
      echo "</tr>";
      echo "</table>";
      
      echo "</form>";
      
      return;
      
      
      
      
      
      
//      if ($id!='') {
//         $this->getFromDB($id);
//      } else {
         $this->getEmpty();
//      }

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


//      echo "<div id='tabcontent'></div>";
//      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
   }


   
   function manageWalks($json) {
      global $DB,$CFG_GLPI,$LANG;

      $snmpwalk = file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/file.log");

      $a_mapping = array();
      $a_mibs = array();
      $a_mibs2 = array();
      $portcounter = '';
      if (isset($json->mibs)) {
         foreach ($json->mibs as $data) {
            if (empty($data->mapping_name)) {
               $portcounter = $data->oids_id;
            } else {
               $a_mibs[$data->oids_id."-".$data->mapping_name] = 1;
               $a_mibs2[$data->mapping_name] = 1;
            }
         }
      }

      foreach ($json->mappings as $data) {
         $a_mapping[$data->order] = $data->id;
      }
      ksort($a_mapping);
      foreach ($a_mapping as $id) {
         $data = $json->mappings->$id;
         echo "<table class='tab_cadre_fixe'>";

         echo "<tr class='tab_bg_1'>";
         echo "<th>";
         echo $data->name;
         echo "</th>";
         echo "<td width='130' align='center'>";
         echo "<img src='".$CFG_GLPI["root_doc"]."/pics/add_dropdown.png' />&nbsp;add a new oid";
         echo "</td>";
         echo "</tr>";
         
         echo "</table>";
         $a_oidfound = array();
         foreach ($json->oids as $a_oids) {
            if ($a_oids->name == $data->name) {
               // Search in the snmwpalk the oid
               $found = array();
               $iso = $a_oids->numeric_oid;
               $iso = preg_replace("/^.1./", "iso.", $iso);
               preg_match_all("/".$a_oids->numeric_oid."(\.\d+){".$a_oids->nboids_after."} = (.*\n)/", $snmpwalk, $found);
               if (isset($found[0][0])) {
                  $a_oidfound[$a_oids->id] = $found[0];
               } else {               
                  preg_match_all("/".$a_oids->mib_oid."(?:\.\d+){".$a_oids->nboids_after."} = (?:.*)\n/", $snmpwalk, $found);
                  if (isset($found[0][0])) {
                     $a_oidfound[$a_oids->id] = $found[0];
                  } else { 
                     preg_match_all("/".$iso."(\.\d+){".$a_oids->nboids_after."} = (.*\n)/", $snmpwalk, $found);
                     if (isset($found[0][0])) {
                        $a_oidfound[$a_oids->id] = $found[0];
                     }
                  }
               }
            } 
            if (isset($a_mibs2[$data->id])) { // This mapping has yet a value on server
               if (isset($a_mibs[$a_oids->id."-".$data->id])) { // if this value is related with this oid
                  if (!isset($a_oidfound[$a_oids->id])) {
                     $a_oidfound[$a_oids->id] = array('?=?');
                  }
               }
            }
         }
         if (count($a_oidfound) == '1') {
            foreach ($a_oidfound as $oid_id => $a_found) {
               if (isset($a_mibs[$oid_id."-".$data->id])) {
                  $this->displayOid($json->oids->$oid_id, $data->id, $a_found, $json->device->sysdescr, "green", $json->mibs->$id);
               } else if ($json->oids->$oid_id->percentage > 49) {
                  $this->displayOid($json->oids->$oid_id, $data->id, $a_found, $json->device->sysdescr, "blue");
               }else {
                  $this->displayOid($json->oids->$oid_id, $data->id, $a_found, $json->device->sysdescr);
               }
            }
         } else if (count($a_oidfound) > 1) {
            foreach ($a_oidfound as $oid_id => $a_found) {
               if (isset($a_mibs[$oid_id."-".$data->id])) {
                  $this->displayOid($json->oids->$oid_id, $data->id, $a_found, $json->device->sysdescr, "green", $json->mibs->$id);
               } else {
                  $this->displayOid($json->oids->$oid_id, $data->id, $a_found, $json->device->sysdescr);
               }
            }
         } else {
            if (($data->name == "dot1dTpFdbAddress"
                    OR $data->name == "dot1dTpFdbPort")
                 AND strstr($json->device->sysdescr, "Cisco")) {
               if (isset($a_mibs2[$data->id])) {
                  $this->displayOid($json->oids->$oid_id, $data->id, array(), $json->device->sysdescr, "green", $json->mibs->$id);
               } else {
                  $this->displayOid($json->oids->$oid_id, $data->id, array(), $json->device->sysdescr, "blue");
               }
            }
         }
         echo "<br/>";
      }
      $portcounteroid = "";
      foreach ($json->oids as $dataoid) {
         if ($dataoid->numeric_oid == '.1.3.6.1.2.1.2.1.0') {
            $portcounteroid = $dataoid->id;
         }
      }
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "Ports counter";
      echo "</th>";
      echo "<td width='130' align='center'>";
      echo "";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      if ($portcounter != '') {
                  $id = "0";
         $this->displayOid($json->oids->$portcounteroid, 0, array(), $json->device->sysdescr, "green", $json->mibs->$id);
      } else {
         $this->displayOid($json->oids->$portcounteroid, 0, array(), $json->device->sysdescr, "blue");
      }
   }
   
   
   
   function displayOid($a_oid, $mappings_id, $a_match, $sysdescr, $color='red', $a_mibs=array()) {
      global $CFG_GLPI,$LANG;

      $style = " style='border-color: #ff0000; border-width: 1px' ";
      $checked = '';
      if ($color == 'blue') {
         $style = " style='border-color: #0000ff; border-width: 3px' "; // 0000ff
         $checked = 'checked';
      } else if ($color == 'green') {
         $style = " style='border-color: #00d50f; border-width: 3px' ";
         $checked = 'checked';
      }

      echo "<table class='tab_cadre' cellpadding='5' width='800' ".$style.">";
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      //echo $a_oid->percentage."%";
      Html::displayProgressBar(150, $a_oid->percentage, array('simple' => true));
      echo "</th>";
      echo "<th colspan='2' style='text-align: left;'>";

      echo "&nbsp;&nbsp;&nbsp;<input type='checkbox' name='oidsselected[]' value='".$a_oid->id."-".$mappings_id."' ".$checked."/>&nbsp;";
      echo "&nbsp;<font color='#ff0000'>";
//      } else {
//         echo "&nbsp;&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
//         echo "&nbsp;<font>";
//         
//      }
      
      echo $a_oid->numeric_oid." (".$a_oid->mib_oid.")";
      echo "</font>";
      echo "</th>";
      echo "</tr>";

      $i = 0;
      foreach ($a_match as $data) {
         if ($i > 8) {
            echo "<tr class='tab_bg_1'>";
            echo "<td colspan='3'>";
            echo "[...]";
            echo "</td>";
            echo "</tr>";
            break;
         }
         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='3'>";
         echo trim($data);
         echo "</td>";
         echo "</tr>";
         $i++;
      }
      
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo $LANG['networking'][56]." : ";
      $vlan = 0;
      if (isset($a_mibs->vlan)) {
         $vlan = $a_mibs->vlan;
      }
      $mapping_pre_vlan = $this->mibVlan();
      if (isset($mapping_pre_vlan[$a_oid->numeric_oid])) {   
         if (strstr($sysdescr, "Cisco")) {
            $vlan = 1;
         }
      }
      Dropdown::showYesNo("vlan_".$a_oid->id, $vlan);

      echo "</th>";
      echo "<th width='350'>";
      if ($a_oid->numeric_oid == ".1.3.6.1.2.1.2.1.0") {
         echo $LANG['plugin_fusinvsnmp']["mib"][6]." : ";
         Dropdown::showYesNo("oid_port_counter_".$a_oid->id, 1);
      }
      echo "</th>";
      echo "<th>";
      echo $LANG['plugin_fusinvsnmp']["mib"][7]." : ";
      $oidportdyn = 0;
      if (isset($a_mibs->oid_port_dyn)) {
         $oidportdyn = $a_mibs->oid_port_dyn;
      } else if (count($a_match) > 1) {
         $oidportdyn = 1;
      }
      if (count($a_match) > 1
           OR preg_match('/^if/', $a_oid->name)
           OR preg_match('/ipAdEntAddr/',$a_oid->name)
           OR preg_match('/^cdp/i',$a_oid->name)
           OR preg_match('/ipNetToMediaPhysAddress/',$a_oid->name)) {
         $oidportdyn = 1;
      }
      Dropdown::showYesNo("oid_port_dyn_".$a_oid->id, $oidportdyn);
      echo "</th>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
      echo "</div><br/>";
      
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
   
   
   private function mibVlan() {

      $mapping_pre_vlan = array();
      $mapping_pre_vlan['.1.3.6.1.4.1.9.9.46.1.6.1.1.14'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.17.4.3.1.1'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.4.22.1.2'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.17.4.3.1.2'] = '1';
      $mapping_pre_vlan['.1.3.6.1.2.1.17.1.4.1.2'] = '1';

      return $mapping_pre_vlan;
   }
}

?>
