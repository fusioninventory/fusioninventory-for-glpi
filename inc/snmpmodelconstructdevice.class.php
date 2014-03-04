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
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginFusioninventorySnmpmodelConstructDevice extends CommonDBTM {
   private $suggest = 1;

   function showForm($id, $data) {
      global $CFG_GLPI;

      echo  "<table width='950' align='center'>
         <tr>
         <td>
         <a href='".$CFG_GLPI['root_doc'].
            "/plugins/fusioninventory/front/constructmodel.php?devices_id=".$id."'>".
            __('Back to device form information', 'fusioninventory')."</a>
         </td>
         </tr>
         </table>";

      $a_mapping_name = array();
      foreach ($data->mappings as $datamib) {
         if (!empty($datamib->name)
           && (strstr($datamib->name, "toner")
              || strstr($datamib->name, "drum")
              || strstr($datamib->name, "maintenancekit")
              || strstr($datamib->name, "maintenancekit"))
           && (!strstr($datamib->name, "max")
              && !strstr($datamib->name, "remaining")
              && !strstr($datamib->name, "used"))) {
            $a_mapping_name[] = $datamib->name;
         }
      }
      if ($data->device->itemtype == 'Printer') {
         echo "<script type='text/javascript'>
            function updatecartridge() {
            cartridgevalues = '';\n";
            foreach ($a_mapping_name as $mapping_name) {
               echo "   progressbar = 0;
                  if (document.getElementById('number_".$mapping_name."max').textContent > 0
                     && document.getElementById('number_".$mapping_name."used').textContent > 0) {

                     max = 0;
                     used = 0;
                     for (i=1; i <= document.getElementById('number_".$mapping_name."max').textContent; i++) {
                        if (document.getElementById('check_".$mapping_name."max[' + i + ']').checked == true) {
                           if (document.getElementById('value_".$mapping_name."max[' + i + ']').textContent > 0) {
                              max = document.getElementById('value_".$mapping_name."max[' + i + ']').textContent;
                           }
                        }
                     }
                     for (i=1; i <= document.getElementById('number_".$mapping_name."used').textContent; i++) {
                        if (document.getElementById('check_".$mapping_name."used[' + i + ']').checked == true) {
                           if (document.getElementById('value_".$mapping_name."used[' + i + ']').textContent > 0) {
                              used = document.getElementById('value_".$mapping_name."used[' + i + ']').textContent;
                           }
                        }
                     }
                     if (max > 0 && used > 0) {
                        cartridgevalues = cartridgevalues + '".$mapping_name."=' + (100 -((used * 100) / max)) + '&';
                        console.log('".$mapping_name." : ' + (100 -((used * 100) / max)));
                        progressbar = 1;
                     }
                  }
                  if (progressbar == 0
                     && document.getElementById('number_".$mapping_name."max').textContent > 0
                     && document.getElementById('number_".$mapping_name."remaining').textContent > 0) {


                     max = 0;
                     remaining = 0;
                     for (i=1; i <= document.getElementById('number_".$mapping_name."max').textContent; i++) {
                        if (document.getElementById('check_".$mapping_name."max[' + i + ']').checked == true) {
                           if (document.getElementById('value_".$mapping_name."max[' + i + ']').textContent > 0) {
                              max = document.getElementById('value_".$mapping_name."max[' + i + ']').textContent;
                           }
                        }
                     }
                     for (i=1; i <= document.getElementById('number_".$mapping_name."remaining').textContent; i++) {
                        if (document.getElementById('check_".$mapping_name."remaining[' + i + ']').checked == true) {
                           if (document.getElementById('value_".$mapping_name."remaining[' + i + ']').textContent > 0) {
                              remaining = document.getElementById('value_".$mapping_name."remaining[' + i + ']').textContent;
                           }
                        }
                     }
                     if (max > 0 && remaining > 0) {
                        cartridgevalues = cartridgevalues + '".$mapping_name."=' + ((remaining * 100) / max) + '&';
                        console.log('".$mapping_name." : ' + ((remaining * 100) / max));
                     }
                  }
               ";
            }
               echo "
                  Ext.get('displaycartridgebar').load({
                     url: '".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownsnmpmodelconstruct_progressbar.php',
                     scripts: true,
                     params: cartridgevalues
                  });
               };
         </script>";
      }

      echo "<form name='form' method='post' action='".$this->getFormURL()."'>";
      echo "<input type='hidden' name='devices_id' value='".$id."' />";

      $ret = $this->manageWalks($data, $id);

      if ($data->device->itemtype == 'Printer') {
         echo "<script type='text/javascript'>
            updatecartridge();
         </script>";
      }

      if ($ret) {
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th align='center'>";
         echo "<input type='submit' name='update' value=\"".__('Update')."\" class='submit'>";
         echo "</th>";
         echo "</tr>";
         echo "</table>";
      }

      Html::closeForm();

      echo '<script language="JavaScript">
      function popUpClosed() {
          window.location.reload();
      }
      </script>';
      return;
   }



   function manageWalks($json, $devices_id=0) {
      global $DB, $CFG_GLPI;

      if ($json->device->itemtype == 'Printer') {
         // Display gauge for printer cartridge
         echo '<div style="position:fixed;top:90px;left:0px;width:300px;
            background-color:#e1dddd; z-index:10;border: 1px solid #828282;">
            <div id="displaycartridgebar"></div>';
         echo "</div>";
      }

      $snmpwalk = '';
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks`
                   WHERE `plugin_fusioninventory_snmpmodelconstructdevices_id`='".$devices_id."'
                   LIMIT 1";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $snmpwalk = file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/walks/".$data['log']);
         }

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

      $dot1dTpFdbAddress = 0;
      $dot1dTpFdbPort = 0;
      $dot1dBasePortIfIndex = 0;
      if (strstr($json->device->sysdescr, "Cisco")) {
         foreach ($json->oids as $a_oids) {
            if ($a_oids->numeric_oid == ".1.3.6.1.2.1.17.4.3.1.1") {
               $dot1dTpFdbAddress = $a_oids->id;
            } else if ($a_oids->numeric_oid == ".1.3.6.1.2.1.17.4.3.1.2") {
               $dot1dTpFdbPort = $a_oids->id;
            }if ($a_oids->numeric_oid == ".1.3.6.1.2.1.17.1.4.1.2") {
               $dot1dBasePortIfIndex = $a_oids->id;
            }
         }
      }


      foreach ($json->mappings as $data) {
         $a_mapping[$data->order] = $data->id;
      }
      ksort($a_mapping);
      foreach ($a_mapping as $id) {
         $num = 0;
         $data = $json->mappings->$id;
         echo "<table class='tab_cadre_fixe'>";

         echo "<tr class='tab_bg_1'>";
         echo "<th>";
         echo $data->name;
         echo "</th>";
         echo "<td width='130' align='center'>";
         if ($snmpwalk != '') {
            echo "<a onclick=\"var w = window.open('".$CFG_GLPI["root_doc"].
                    "/plugins/fusioninventory/front/constructmodel.form.php?mapping=".$data->name.
                    "' , 'glpipopup', 'height=400, width=1000, top=100, left=100, scrollbars=yes')".
                    ";w.focus();\"><img src='".$CFG_GLPI["root_doc"]."/pics/add_dropdown.png' />".
                    "&nbsp;add a new oid</a>";
         }
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
               preg_match_all("/".$a_oids->numeric_oid."(\.\d+){".$a_oids->nboids_after."}".
                       " = (.*\n)/", $snmpwalk, $found);
               if (isset($found[0][0])) {
                  $a_oidfound[$a_oids->id] = $found[0];
               } else {
                  preg_match_all("/".$a_oids->mib_oid."(?:\.\d+){".$a_oids->nboids_after."}".
                          " = (?:.*)\n/", $snmpwalk, $found);
                  if (isset($found[0][0])) {
                     $a_oidfound[$a_oids->id] = $found[0];
                  } else {
                     preg_match_all("/".$iso."(\.\d+){".$a_oids->nboids_after."}".
                             " = (.*\n)/", $snmpwalk, $found);
                     if (isset($found[0][0])) {
                        $a_oidfound[$a_oids->id] = $found[0];
                     }
                  }
               }
            }
            if (isset($a_mibs2[$data->id])) { // This mapping has yet a value on server
               if (isset($a_mibs[$a_oids->id."-".$data->id])) {
                  // if this value is related with this oid
                  if (!isset($a_oidfound[$a_oids->id])) {
                     $a_oidfound[$a_oids->id] = array('?=?');
                  }
               }
            }
         }
         if (count($a_oidfound) == '1') {
            foreach ($a_oidfound as $oid_id => $a_found) {
               $num++;
               if (isset($a_mibs[$oid_id."-".$data->id])) {
                  $this->displayOid($json->oids->$oid_id,
                                    $data->id,
                                    $data->name,
                                    $a_found,
                                    $json->device->sysdescr,
                                    $num,
                                    "green",
                                    $json->mibs->$id);
               } else if ($json->oids->$oid_id->percentage->$id > 49) {
                  $this->displayOid($json->oids->$oid_id,
                                    $data->id,
                                    $data->name,
                                    $a_found,
                                    $json->device->sysdescr,
                                    $num,
                                    "blue");
               }else {
                  $this->displayOid($json->oids->$oid_id,
                                    $data->id,
                                    $data->name,
                                    $a_found,
                                    $json->device->sysdescr,
                                    $num);
               }
            }
         } else if (count($a_oidfound) > 1) {
            foreach ($a_oidfound as $oid_id => $a_found) {
               $num++;
               if (isset($a_mibs[$oid_id."-".$data->id])) {
                  $this->displayOid($json->oids->$oid_id,
                                    $data->id,
                                    $data->name,
                                    $a_found,
                                    $json->device->sysdescr,
                                    $num,
                                    "green",
                                    $json->mibs->$id);
               } else {
                  $this->displayOid($json->oids->$oid_id,
                                    $data->id,
                                    $data->name,
                                    $a_found,
                                    $json->device->sysdescr,
                                    $num);
               }
            }
         } else {
            if (($data->name == "dot1dTpFdbAddress"
                    OR $data->name == "dot1dTpFdbPort"
                    OR $data->name == "dot1dBasePortIfIndex")
                 AND strstr($json->device->sysdescr, "Cisco")) {

               $oids_id_temp = 0;
               if ($data->name == "dot1dTpFdbAddress") {
                  $oids_id_temp = $dot1dTpFdbAddress;
               } else if ($data->name == "dot1dTpFdbPort") {
                  $oids_id_temp = $dot1dTpFdbPort;
               } else if ($data->name == "dot1dBasePortIfIndex") {
                  $oids_id_temp = $dot1dBasePortIfIndex;
               }
               if (isset($a_mibs2[$data->id])) {
                  $this->displayOid($json->oids->$oids_id_temp,
                                    $data->id,
                                    $data->name,
                                    array(),
                                    $json->device->sysdescr,
                                    0,
                                    "green",
                                    $json->mibs->$id);
               } else {
                  $this->displayOid($json->oids->$oids_id_temp,
                                    $data->id,
                                    $data->name,
                                    array(),
                                    $json->device->sysdescr,
                                    0,
                                    "blue");
               }
            }
         }
         echo "<div style='visibility:hidden' id='number_".$data->name."'>".$num."</div>";
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
         $this->displayOid($json->oids->$portcounteroid,
                           0,
                           '',
                           array(),
                           $json->device->sysdescr,
                           0,
                           "green",
                           $json->mibs->$id);
      } else {
         $this->displayOid($json->oids->$portcounteroid,
                           0,
                           '',
                           array(),
                           $json->device->sysdescr,
                           '0',
                           "blue");
      }
      if ($snmpwalk == '') {
         return FALSE;
      }
      return TRUE;
   }



   function displayOid($a_oid, $mappings_id, $mapping_name, $a_match, $sysdescr, $num=0, $color='red', $a_mibs=array()) {

      $style = " style='border-color: #ff0000; border-width: 1px' ";
      $checked = '';
      if ($color == 'blue'
              AND $this->suggest == '1') {
         $style = " style='border-color: #0000ff; border-width: 3px' "; // 0000ff
         $checked = 'checked';
      } else if ($color == 'green') {
         $this->suggest = 0;
         $style = " style='border-color: #00d50f; border-width: 3px' ";
         $checked = 'checked';
      }

      echo "<table class='tab_cadre' cellpadding='5' width='800' ".$style.">";
      echo "<tr class='tab_bg_1'>";
      echo "<th width='150'>";
      //echo $a_oid->percentage."%";
      if (isset($a_oid->percentage->$mappings_id)) {
        Html::displayProgressBar(150, $a_oid->percentage->$mappings_id, array('simple' => TRUE));
      }
      echo "</th>";
      echo "<th colspan='2' style='text-align: left;'>";

      $onchange = '';
      if (strstr($mapping_name, "toner")
              || strstr($mapping_name, "drum")
              || strstr($mapping_name, "maintenancekit")
              || strstr($mapping_name, "maintenancekit")) {
         $onchange = "onChange='updatecartridge()' id='check_".$mapping_name."[".$num."]'";
      }
      echo "&nbsp;&nbsp;&nbsp;<input type='checkbox' name='oidsselected[]' value='".$a_oid->id."-".
              $mappings_id."' ".$checked." ".$onchange." />&nbsp;";
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
         if ($onchange != ''
                 && strstr($data, "Hex-STRING")) {
            $split = explode(': ', trim($data));
            $split[1] = str_replace(' ', '', $split[1]);
            $str = '';
            for ($i=0;$i< strlen($split[1]);$i+=2) {
               $str .= chr(hexdec(substr($split[1], $i, 2)));
            }
            echo $split[0].": ".$str;

         } else {
            echo trim($data);
         }
         if (count($a_match) == 1
                 && $onchange != '') {
            $split = explode(':', trim($data));
            $valuefromoid = $split[(count($split) - 1)];
            if (count($split) == 1) {
               $valuefromoid = 0;
           }
            echo "<div style='visibility:hidden' id='value_".$mapping_name."[".$num."]'>".trim($valuefromoid, '" ')."</div>";
         } else if ($onchange != '') {
            echo "<div style='visibility:hidden' id='value_".$mapping_name."[".$num."]'>0</div>";
         }
         echo "</td>";
         echo "</tr>";
         $i++;
      }


      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      if ($a_oid->numeric_oid == ".1.3.6.1.2.1.2.1.0") {
         echo "<div style='display:none'>";
         Dropdown::showYesNo("vlan_".$a_oid->id, 0);
         echo "</div>";
      } else {
         echo __('VLAN')." : ";
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
      }

      echo "</th>";
      echo "<th width='350'>";
      if ($a_oid->numeric_oid == ".1.3.6.1.2.1.2.1.0") {
         echo __('Port Counters', 'fusioninventory')." : ";
         Dropdown::showYesNo("oid_port_counter_".$a_oid->id, 1);
      }
      echo "</th>";
      echo "<th width='200'>";
      if ($a_oid->numeric_oid == ".1.3.6.1.2.1.2.1.0") {
         echo "<div style='display:none'>";
         Dropdown::showYesNo("oid_port_dyn_".$a_oid->id, 0);
         echo "</div>";
      } else {
         echo __('Dynamic port (.x)', 'fusioninventory')." : ";
         $oidportdyn = 0;
         if (isset($a_mibs->oid_port_dyn)) {
            $oidportdyn = $a_mibs->oid_port_dyn;
         } else if (count($a_match) > 1) {
            $oidportdyn = 1;
         }
         if (count($a_match) > 1
              OR preg_match('/^if/', $a_oid->name)
              OR preg_match('/ipAdEntAddr/', $a_oid->name)
              OR preg_match('/^cdp/i', $a_oid->name)
              OR preg_match('/ipNetToMediaPhysAddress/', $a_oid->name)
              OR preg_match('/^dot1d/i', $a_oid->name)) {
            $oidportdyn = 1;
         }
         Dropdown::showYesNo("oid_port_dyn_".$a_oid->id, $oidportdyn);
      }
      echo "</th>";
      echo "</tr>";

      echo "</table>";


   }



   function generatemodels() {
      global $DB;

      $ptmi = new PluginFusioninventorySnmpmodel();
      $ptmn = new PluginFusinvsnmpModelMib();

      $query = "SELECT glpi_plugin_fusinvsnmp_constructdevices.id, type
         FROM glpi_plugin_fusinvsnmp_constructdevices
         LEFT JOIN glpi_plugin_fusinvsnmp_constructdevicewalks ON ".
              "glpi_plugin_fusinvsnmp_constructdevices.id = plugin_fusinvsnmp_constructdevices_id
         WHERE type IN (1, 2, 3)
            AND log!=''";
      if ($result = $DB->query($query)) {
         while ($data = $DB->fetch_array($result)) {
            // Load mibs
            $a_mib = array();
            $count_mib = 0;
            $query_mibs = "SELECT `glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids`.*,
                  `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`,
                  `glpi_plugin_fusioninventory_mappings`.`itemtype`
               FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids`
                  LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                     ON `glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids`.".
                        "`plugin_fusioninventory_mappings_id`=
                        `glpi_plugin_fusioninventory_mappings`.`id`
               WHERE plugin_fusinvsnmp_constructdevices_id='".$data["id"]."' ";
            if ($result_mibs = $DB->query($query_mibs)) {
               while ($data_mibs = $DB->fetch_array($result_mibs)) {
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['itemtype'] =
                                 $data_mibs['itemtype'];
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['mapping_name'] =
                                 $data_mibs['mapping_name'];
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['oid_port_counter'] =
                                 $data_mibs['oid_port_counter'];
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['oid_port_dyn'] =
                                 $data_mibs['oid_port_dyn'];
                  $a_mib[$data_mibs['plugin_fusinvsnmp_miboids_id']]['vlan'] = $data_mibs['vlan'];
                  $count_mib++;
               }
            }

            // See if model exactly exists
            $query_models = "SELECT * FROM glpi_plugin_fusioninventory_snmpmodels";
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
                              ON `glpi_plugin_fusinvsnmp_modelmibs`.".
                                 "`plugin_fusioninventory_snmpmodels_id`=
                                 `glpi_plugin_fusioninventory_mappings`.`id`
                        WHERE `plugin_fusioninventory_snmpmodels_id`='".$data_models['id']."' ";
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

               $query_mibs = "SELECT `glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids`.*,
                  `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`,
                  `glpi_plugin_fusioninventory_mappings`.`itemtype`
               FROM `glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids`
                  LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                     ON `glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids`.".
                        "`plugin_fusioninventory_mappings_id`=
                        `glpi_plugin_fusioninventory_mappings`.`id`
               WHERE `plugin_fusinvsnmp_constructdevices_id`='".$data["id"]."' ";
               if ($result_mibs = $DB->query($query_mibs)) {
                  while ($data_mibs = $DB->fetch_array($result_mibs)) {
                     $a_input = array();
                     $a_input['plugin_fusioninventory_snmpmodels_id'] = $id;
                     $a_input['plugin_fusinvsnmp_miboids_id'] =
                                    $data_mibs['plugin_fusinvsnmp_miboids_id'];
                     $a_input['oid_port_counter'] = $data_mibs['oid_port_counter'];
                     $a_input['oid_port_dyn'] = $data_mibs['oid_port_dyn'];
                     $a_input['vlan'] = $data_mibs['vlan'];
                     $a_input['links_oid_fields'] =
                                    $data_mibs['itemtype']."||".$data_mibs['mapping_name'];
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
               FROM glpi_plugin_fusioninventory_snmpmodels
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
               FROM glpi_plugin_fusioninventory_snmpmodels
               WHERE (discovery_key IS NULL OR discovery_key='')
                  AND itemtype='".NETWORKING_TYPE."' ";
      if ($result = $DB->query($query)) {
         while ($data = $DB->fetch_array($result)) {
            while(strlen($num) < 4) {
               $num = "0" . $num;
               $query_update = "UPDATE glpi_plugin_fusioninventory_snmpmodels
                  SET discovery_key='Networking".$num."'
                     WHERE id='".$data['id']."'";
               $DB->query($query_update);
               $num++;
            }
         }
      }
      // Printers
      $query = "SELECT *
               FROM glpi_plugin_fusioninventory_snmpmodels
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
               FROM glpi_plugin_fusioninventory_snmpmodels
               WHERE (discovery_key IS NULL OR discovery_key='')
                  AND itemtype='".PRINTER_TYPE."' ";
      if ($result = $DB->query($query)) {
         while ($data = $DB->fetch_array($result)) {
            while(strlen($num) < 4) {
               $num = "0" . $num;

               $query_update = "UPDATE glpi_plugin_fusioninventory_snmpmodels
                  SET discovery_key='Printer".$num."'
                     WHERE id='".$data['id']."'";
               $DB->query($query_update);
               $num++;
            }
         }
      }
   }



   function cleanmodels() {
      global $DB;

      $query_models = "SELECT * FROM glpi_plugin_fusioninventory_snmpmodels";
      if ($result_models = $DB->query($query_models)) {
         while ($data_models = $DB->fetch_array($result_models)) {
            $query = "SELECT * FROM glpi_plugin_fusinvsnmp_constructdevices
               WHERE snmpmodel_id='".$data_models['id']."' ";
            if ($result = $DB->query($query)) {
               if ($DB->numrows($result) == 0) {
                  // Delete model
                  $query_delete = "DELETE FROM glpi_plugin_fusioninventory_snmpmodels
                     WHERE id='".$data_models['id']."'";
                  $DB->query($query_delete);
               }
            }
         }
       }
   }



   function exportmodels() {
      global $DB;

      $pfiie = new PluginFusioninventorySnmpmodelImportExport();

      $query_models = "SELECT * FROM glpi_plugin_fusioninventory_snmpmodels";
      if ($result_models = $DB->query($query_models)) {
         while ($data = $DB->fetch_array($result_models)) {
            $xml = $pfiie->export($data['id']);
            file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/snmpmodels/".$data['name'].
                              ".xml", $xml);
         }
      }
   }



   function generatecomments() {
      global $DB;

      $query_clean = "UPDATE `glpi_plugin_fusioninventory_snmpmodels`
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
         $query_update = "UPDATE `glpi_plugin_fusioninventory_snmpmodels`
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
