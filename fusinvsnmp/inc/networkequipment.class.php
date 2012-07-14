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
   @author    Vincent Mazzoni
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

require_once(GLPI_ROOT.'/plugins/fusinvsnmp/inc/commondbtm.class.php');

class PluginFusinvsnmpNetworkEquipment extends PluginFusinvsnmpCommonDBTM {
   private $oFusionInventory_networkequipment;
   private $newPorts=array(), $updatesPorts=array();

   function __construct() {
      parent::__construct("glpi_networkequipments");
      $this->dohistory=true;
      $this->oFusionInventory_networkequipment = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
      $this->oFusionInventory_networkequipment->type = 'PluginFusinvsnmpNetworkEquipment';
   }

   
   function getType() {
      return "NetworkEquipment";
   }

  
   /**
    * Load an existing networking switch
    *
    *@return nothing
    **/
   function load($p_id='') {
      global $DB;

      parent::load($p_id);

      $query = "SELECT `id`
                FROM `glpi_plugin_fusinvsnmp_networkequipments`
                WHERE `networkequipments_id` = '".$this->getValue('id')."';";
      $result = $DB->query($query);
      if ($result) {
         if ($DB->numrows($result) != 0) {
            $fusioninventory = $DB->fetch_assoc($result);
            $this->oFusionInventory_networkequipment->load($fusioninventory['id']);
            $this->ptcdLinkedObjects[]=$this->oFusionInventory_networkequipment;
         } else {
            $input = array();
            $input['networkequipments_id'] = $this->getValue('id');
            $_SESSION['glpi_plugins_fusinvsnmp_table'] = 'glpi_networkequipments';
            $id = $this->oFusionInventory_networkequipment->add($input);
            $this->oFusionInventory_networkequipment->load($id);
            $this->ptcdLinkedObjects[]=$this->oFusionInventory_networkequipment;
            $this->ptcdLinkedObjects[]=$input;
         }
      }
   }



   /**
    * Update an existing preloaded switch with the instance values
    *
    *@return nothing
    **/
   function updateDB() {

      parent::updateDB();
      //$a_networkequipment = $this->ptcdUpdates;

      // update last_fusioninventory_update even if no other update
      $this->setValue('last_fusioninventory_update', date("Y-m-d H:i:s"));
      $this->oFusionInventory_networkequipment->updateDB();
   }



   function showForm($id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if (!PluginFusioninventoryProfile::haveRight("fusinvsnmp", "networkequipment","r")) {
         return false;
      }
      $canedit = false;
      if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "networkequipment","w")) {
         $canedit = true;
      }

      $this->oFusionInventory_networkequipment->id = $id;

      $nw=new NetworkPort_NetworkPort();
      
      if (!$data = $this->oFusionInventory_networkequipment->find("`networkequipments_id`='".$id."'", '', 1)) {
         // Add in database if not exist
         $input = array();
         $input['networkequipments_id'] = $id;
         $_SESSION['glpi_plugins_fusinvsnmp_table'] = 'glpi_networkequipments';
         $ID_tn = $this->oFusionInventory_networkequipment->add($input);
         $this->oFusionInventory_networkequipment->getFromDB($ID_tn);
      } else {
         foreach ($data as $datas) {
            $this->oFusionInventory_networkequipment->fields = $datas;
         }
      }

      $PID = 0;
      $PID = $this->oFusionInventory_networkequipment->fields['last_PID_update'];

      // Form networking informations
      echo "<form name='form' method='post' action='".$options['target']."'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>";
      echo $LANG['plugin_fusinvsnmp']['title'][1];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' rowspan='3'>";
      echo $LANG['plugin_fusinvsnmp']['snmp'][4]."&nbsp;:";
      echo "</td>";
      echo "<td rowspan='3'>";
      echo "<textarea name='sysdescr' cols='45' rows='5'>";
      echo $this->oFusionInventory_networkequipment->fields['sysdescr'];
      echo "</textarea>";
      echo "<td align='center' rowspan='2'>".$LANG['plugin_fusinvsnmp']['model_info'][4]."&nbsp;:</td>";
      echo "<td align='center'>";
      $query_models = "SELECT *
                       FROM `glpi_plugin_fusinvsnmp_models`
                       WHERE `itemtype`!='NetworkEquipment'
                           AND `itemtype`!=''";
      $result_models=$DB->query($query_models);
      $exclude_models = array();
      while ($data_models=$DB->fetch_array($result_models)) {
         $exclude_models[] = $data_models['id'];
      }
      Dropdown::show("PluginFusinvsnmpModel",
                     array('name'=>"model_infos",
                           'value'=>$this->oFusionInventory_networkequipment->fields['plugin_fusinvsnmp_models_id'],
                           'comment'=>0,
                           'used'=>$exclude_models));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "<input type='submit' name='GetRightModel'
              value='".$LANG['plugin_fusinvsnmp']['model_info'][13]."' class='submit'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>".$LANG['plugin_fusinvsnmp']['model_info'][3]."&nbsp;:</td>";
      echo "<td align='center'>";
      PluginFusinvsnmpSNMP::auth_dropdown($this->oFusionInventory_networkequipment->fields['plugin_fusinvsnmp_configsecurities_id']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "</td>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']['snmp'][53]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo Html::convDateTime($this->oFusionInventory_networkequipment->fields['last_fusioninventory_update']);
      echo "</td>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']['snmp'][13]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Html::displayProgressBar(250, $this->oFusionInventory_networkequipment->fields['cpu'],
                  array('simple' => true));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']['snmp'][12]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $sysUpTime = $this->oFusionInventory_networkequipment->fields['uptime'];
      $day = 0;
      $hour = 0;
      $minute = 0;
      $sec = 0;
      $ticks = 0;
      if (strstr($sysUpTime, "days")) {
         list($day, $hour, $minute, $sec, $ticks) = sscanf($sysUpTime, "%d days, %d:%d:%d.%d");
      } else if (strstr($sysUpTime, "hours")) {
         $day = 0;
         list($hour, $minute, $sec, $ticks) = sscanf($sysUpTime, "%d hours, %d:%d.%d");
      } else if (strstr($sysUpTime, "minutes")) {
         $day = 0;
         $hour = 0;
         list($minute, $sec, $ticks) = sscanf($sysUpTime, "%d minutes, %d.%d");
      } else if($sysUpTime == "0") {
         $day = 0;
         $hour = 0;
         $minute = 0;
         $sec = 0;
      } else {
         list($hour, $minute, $sec, $ticks) = sscanf($sysUpTime, "%d:%d:%d.%d");
         $day = 0;
      }

      echo "<b>$day</b> ".$LANG['calendar'][12]." ";
      echo "<b>$hour</b> ".$LANG['gmt'][1]." ";
      echo "<b>$minute</b> ".$LANG["job"][22]." ";
      echo " ".$LANG['choice'][3]." <b>$sec</b> ".$LANG["stats"][34]." ";
      echo "</td>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']['snmp'][14]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $query2 = "SELECT *
                 FROM `glpi_networkequipments`
                 WHERE `id`='".$id."';";
      $result2 = $DB->query($query2);
      $data2 = $DB->fetch_assoc($result2);
      $ram_pourcentage = 0;
      if (!empty($data2["ram"]) AND !empty($this->oFusionInventory_networkequipment->fields['memory'])) {
         $ram_pourcentage = ceil((100 * ($data2["ram"] - $this->oFusionInventory_networkequipment->fields['memory'])) / $data2["ram"]);
      }
      if ((($data2["ram"] - $this->oFusionInventory_networkequipment->fields['memory']) < 0)
           OR (empty($this->oFusionInventory_networkequipment->fields['memory']))) {
         echo "<center><strong>".$LANG['plugin_fusinvsnmp']['snmp'][54]."</strong></center>";
      } else {
         Html::displayProgressBar(250, $ram_pourcentage,
                        array('title' => " (".($data2["ram"] - $this->oFusionInventory_networkequipment->fields['memory'])." Mo / ".
                         $data2["ram"]." Mo)"));
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_2 center'>";
      echo "<td colspan='4'>";
      echo "<input type='hidden' name='id' value='".$id."'>";
      echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();


      // ***** IP list ***** //
      PluginFusinvsnmpNetworkEquipmentIP::showIP($id);
      echo "<br/>";

// ********************************************************************************************** //
// *********************************** METTRE TABLEAU DES PORTS ********************************* //
// ********************************************************************************************** //
      $monitoring = 0;
      if (class_exists("PluginMonitoringNetworkport")) {
         $monitoring = 1;
      }
      
      $query = "
      SELECT *,glpi_plugin_fusinvsnmp_networkports.mac as ifmacinternal

      FROM glpi_plugin_fusinvsnmp_networkports

      LEFT JOIN glpi_networkports
      ON glpi_plugin_fusinvsnmp_networkports.networkports_id = glpi_networkports.id
      WHERE glpi_networkports.items_id='".$id."'
      ORDER BY logical_number ";

      echo "<script  type='text/javascript'>
function close_array(id){
   document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/collapse.png\''+
      'onClick=\'Ext.get(\"viewfollowup'+id+'\").toggle();appear_array('+id+');\' />';
}
function appear_array(id){
   document.getElementById('plusmoins'+id).innerHTML = '<img src=\'".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/expand.png\''+
      'onClick=\'Ext.get(\"viewfollowup'+id+'\").toggle();close_array('+id+');\' id=\'plusmoinsl'+id+'\' />';
}

      </script>";
      $nbcol = 5;
      if ($monitoring == '1') {
         if (PluginMonitoringProfile::haveRight("componentscatalog", 'r')) {
            echo "<form name='form' method='post' action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/networkport.form.php'>";
            echo "<input type='hidden' name='items_id' value='".$id."' />";
            echo "<input type='hidden' name='itemtype' value='NetworkEquipment' />";
         }
         $nbcol++;
      }
      echo "<table class='tab_cadre' cellpadding='".$nbcol."' width='1100'>";

      echo "<tr class='tab_bg_1'>";
      $query_array = "SELECT *
                      FROM `glpi_displaypreferences`
                      WHERE `itemtype`='PluginFusinvsnmpNetworkEquipment'
                            AND `users_id`='0'
                      ORDER BY `rank`;";
      $result_array=$DB->query($query_array);
      echo "<th colspan='".(mysql_num_rows($result_array) + 2)."'>";
      echo $LANG['plugin_fusinvsnmp']['snmp'][40];
      $result=$DB->query($query);
      echo ' ('.$DB->numrows($result).')';
      $url_legend = "https://forge.indepnet.net/wiki/fusioninventory/En_VI_visualisationsdonnees_2_reseau";
      if ($_SESSION["glpilanguage"] == "fr_FR") {
         $url_legend = "https://forge.indepnet.net/wiki/fusioninventory/Fr_VI_visualisationsdonnees_2_reseau";
      }
      echo "<a href='legend'></a>";
      echo "<div id='legendlink'><a onClick='Ext.get(\"legend\").toggle();'>[ ".$LANG['plugin_fusioninventory']['functionalities'][6]." ]</a></div>";
      echo "</th>";
      echo "</tr>";

      // Display legend
      echo "
      <tr class='tab_bg_1' style='display: none;' id='legend'>
         <td colspan='".(mysql_num_rows($result_array) + 2)."'>
         <ul>
            <li>".$LANG['plugin_fusinvsnmp']['legend'][0]."&nbsp;:</li>
         </ul>
         <img src='".$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/port_trunk.png' width='750' />
         <ul>
            <li>".$LANG['plugin_fusinvsnmp']['legend'][1]."&nbsp;:</li>
         </ul>
         <img src='".$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/connected_trunk.png' width='750' />
         </td>
      </tr>";
      echo "<script>Ext.get('legend').setVisibilityMode(Ext.Element.DISPLAY);</script>";

      echo "<tr class='tab_bg_1'>";

      echo '<th>';
      echo "<th>".$LANG["common"][16]."</th>";
      if ($monitoring == '1') {
         echo "<th>".$LANG['plugin_monitoring']['title'][0]."</th>";
      }

      $query_array = "SELECT *
                      FROM `glpi_displaypreferences`
                      WHERE `itemtype`='PluginFusinvsnmpNetworkEquipment'
                             AND `users_id`='0'
                      ORDER BY `rank`;";
      $result_array=$DB->query($query_array);
      while ($data_array=$DB->fetch_array($result_array)) {
         if ($data_array['num'] != '8'
                 AND $data_array['num'] != '9') {
            echo "<th>";
            switch ($data_array['num']) {
               case 2 :
                  echo $LANG['plugin_fusinvsnmp']['mapping'][104];
                  break;

               case 3 :
                  echo $LANG['plugin_fusinvsnmp']['mapping'][105];
                  break;

               case 4 :
                  echo $LANG['plugin_fusinvsnmp']['mapping'][106];
                  break;

               case 5 :
                  echo $LANG['plugin_fusinvsnmp']['mapping'][107];
                  break;

               case 6 :
                  echo $LANG['plugin_fusinvsnmp']['snmp'][46]." / ".
                        $LANG['plugin_fusinvsnmp']['snmp'][48];
                  break;

               case 7 :
                  echo $LANG['plugin_fusinvsnmp']['mapping'][110]." / ".
                          $LANG['plugin_fusinvsnmp']['snmp'][49];
                  break;

               case 10 :
                  echo $LANG['plugin_fusinvsnmp']['snmp'][51];
                  break;

               case 11 :
                  echo $LANG['plugin_fusinvsnmp']['mapping'][115];
                  break;

               case 12 :
                  echo $LANG["networking"][17];
                  break;

               case 13 :
                  echo $LANG['plugin_fusinvsnmp']['mapping'][114];
                  break;

               case 14 :
                  echo $LANG["networking"][56];
                  break;

               case 15 :
                  echo $LANG['plugin_fusinvsnmp']['snmp'][41];
                  break;

            }
            echo "</th>";
         }
      }
      echo "</tr>";
      // Fin de l'entête du tableau

      if ($result) {
         while ($data=$DB->fetch_array($result)) {
            $background_img = "";
            if (($data["trunk"] == "1") AND (strstr($data["ifstatus"], "up")
                  OR strstr($data["ifstatus"], "1"))) {
               $background_img = " style='background-image: url(\"".$CFG_GLPI['root_doc'].
                                    "/plugins/fusinvsnmp/pics/port_trunk.png\"); '";
            } else if (($data["trunk"] == "-1") AND (strstr($data["ifstatus"], "up")
                        OR strstr($data["ifstatus"], "1"))) {
               $background_img = " style='background-image: url(\"".$CFG_GLPI['root_doc'].
                                    "/plugins/fusinvsnmp/pics/multiple_mac_addresses.png\"); '";
            } else if (strstr($data["ifstatus"], "up") OR strstr($data["ifstatus"], "1")) {
               $background_img = " style='background-image: url(\"".$CFG_GLPI['root_doc'].
                                    "/plugins/fusinvsnmp/pics/connected_trunk.png\"); '";
            }
            echo "<tr class='tab_bg_1 center' height='40'".$background_img.">";
            echo "<td id='plusmoins".$data["id"]."'><img src='".$CFG_GLPI['root_doc'].
                     "/plugins/fusioninventory/pics/expand.png' onClick='Ext.get(\"viewfollowup".$data["id"]."\").toggle();
                     close_array(".$data["id"].");' id='plusmoinsl".$data["id"]."'\'/>";
            echo "</td>";
            echo "<td><a href='networkport.form.php?id=".$data["id"]."'>".
                     $data["name"]."</a>";
            Html::showToolTip($data['ifdescr']);        
            echo "</td>";

            if ($monitoring == '1') {
               echo "<td>";
               $state = PluginMonitoringNetworkport::isMonitoredNetworkport($data['id']);
               if (PluginMonitoringProfile::haveRight("componentscatalog", 'w')) {
                  $checked = '';
                  if ($state) {
                     $checked = 'checked';
                  }
                  echo "<input type='checkbox' name='networkports_id[]' value='".$data['id']."' ".$checked."/>";
               } else if (PluginMonitoringProfile::haveRight("componentscatalog", 'r')) {
                  echo Dropdown::getYesNo($state);
               }
               echo "</td>";
            }
            
            $query_array = "SELECT *
                            FROM `glpi_displaypreferences`
                            WHERE `itemtype`='PluginFusinvsnmpNetworkEquipment'
                                  AND `users_id`='0'
                            ORDER BY `rank`;";
            $result_array=$DB->query($query_array);
            while ($data_array=$DB->fetch_array($result_array)) {
               switch ($data_array['num']) {
                  case 2 :
                     echo "<td>".$data["ifmtu"]."</td>";
                     break;

                  case 3 :
                     echo "<td>".$this->byteSize($data["ifspeed"],1000)."bps</td>";
                     break;

                  case 4 :
                     echo "<td>";
                     if (strstr($data["ifstatus"], "up") OR strstr($data["ifinternalstatus"],"1")) {
                        echo "<img src='".$CFG_GLPI['root_doc']."/pics/greenbutton.png'/>";
                     } else if (strstr($data["ifstatus"],"down")
                                 OR strstr($data["ifinternalstatus"], "2")) {
                        echo "<img src='".$CFG_GLPI['root_doc']."/pics/redbutton.png'/>";
                     } else if (strstr($data["ifstatus"], "testing")
                                 OR strstr($data["ifinternalstatus"], "3")) {
                        echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/yellowbutton.png'/>";
                     }
                     echo "</td>";
                     break;

                  case 5 :
                     echo "<td>".$data["iflastchange"]."</td>";
                     break;

                  case 6 :
                     echo "<td>";
                     if ($data["ifinoctets"] == "0") {
                        echo "-";
                     } else {
                        echo $this->byteSize($data["ifinoctets"],1000)."o";
                     }
                     echo " / ";
                     if ($data["ifinoctets"] == "0") {
                        echo "-";
                     } else {
                        echo $this->byteSize($data["ifoutoctets"],1000)."o";
                     }
                     
                     echo "</td>";
                     break;

                  case 7 :
                     $color = '';
                     if ($data["ifinerrors"] != "0"
                             OR $data["ifouterrors"] != "0") {
                        $color = "background='#cf9b9b' class='tab_bg_1_2'";
                     }
                     if ($data["ifinerrors"] == "0") {
                        echo "<td ".$color.">-";
                     } else {
                        echo "<td ".$color.">";
                        echo $data["ifinerrors"];
                     }
                     echo " / ";
                     if ($data["ifouterrors"] == "0") {
                        echo "-";
                     } else {
                        echo $data["ifouterrors"];
                     }
                     echo "</td>";
                     break;

                  case 10 :
                     echo "<td>".$data["portduplex"]."</td>";
                     break;

                  case 11 :
                     // ** internal mac
                     echo "<td>".$data["mac"]."</td>";
                     break;

                  case 12 :
                     // ** Mac address and link to device which are connected to this port
                     $opposite_port = $nw->getOppositeContact($data["networkports_id"]);
                     if ($opposite_port != "") {
                        $query_device = "SELECT *
                                         FROM `glpi_networkports`
                                         WHERE `id`='".$opposite_port."';";

                        $result_device = $DB->query($query_device);
                        if ($DB->numrows($result_device) > 0) {
                           $data_device = $DB->fetch_assoc($result_device);

                           $item = new $data_device["itemtype"];
                           $item->getFromDB($data_device["items_id"]);
                           $link1 = $item->getLink(1);
                           $link = str_replace($item->getName(0), $data_device["mac"],
                                               $item->getLink());
                           $link2 = str_replace($item->getName(0), $data_device["ip"],
                                                $item->getLink());
                           if ($data_device["itemtype"] == 'PluginFusioninventoryUnknownDevice') {
                              if ($item->getField("accepted") == "1") {
                                 echo "<td style='background:#bfec75'
                                           class='tab_bg_1_2'>".$link1;
                              } else {
                                 echo "<td background='#cf9b9b'
                                           class='tab_bg_1_2'>".$link1;
                              }
                              if (!empty($link)) {
                                 echo "<br/>".$link;
                              }
                              if (!empty($link2)) {
                                 echo "<br/>".$link2;
                              }
                              if ($item->getField("hub") == "1") {
                                 $this->displayHubConnections($data_device["items_id"], $background_img);
                              }
                              echo "</td>";
                           } else {
                              $icon = '';
                              if ($data_device["itemtype"] == 'Computer') {
                                 $icon = "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/computer_icon.png' style='float:left'/> ";
                              } else if ($data_device["itemtype"] == 'Printer') {
                                 $icon = "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/printer_icon.png' style='float:left'/> ";
                              } else if ($data_device["itemtype"] == 'Phone') {
                                 $icon = "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/phone_icon.png' style='float:left'/> ";
                              } else if ($data_device["itemtype"] == 'NetworkEquipment') {
                                 $icon = "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/network_icon.png' style='float:left'/> ";
                              }
                              
                              echo "<td>".$icon.$link1;
                              if (!empty($link)) {
                                 echo "<br/>".$link;
                              }
                              if (!empty($link2)) {
                                 echo "<br/>".$link2;
                              }
                              echo "</td>";
                           }
                        } else {
                           echo "<td></td>";
                        }
                     } else {
                        echo "<td></td>";
                     }
                     break;

                  case 13 :
                     // ** Connection status
                     echo "<td>";
                     if (strstr($data["ifstatus"], "up") OR strstr($data["ifstatus"], "1")) {
                        echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/wired_on.png'/>";
                     } else if (strstr($data["ifstatus"], "down")
                                OR strstr($data["ifstatus"], "2")) {
                        echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/pics/wired_off.png'/>";
                     } else if (strstr($data["ifstatus"], "testing")
                                OR strstr($data["ifstatus"], "3")) {
                        echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/yellowbutton.png'/>";
                     } else if (strstr($data["ifstatus"], "dormant")
                                OR strstr($data["ifstatus"], "5")) {
                        echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/orangebutton.png'/>";
                     }
                     echo "</td>";
                     break;

                  case 14 :
                     echo "<td>";

                     $canedit = Session::haveRight("networking", "w");

                     $used = array();

                     $query_vlan = "SELECT * FROM glpi_networkports_vlans WHERE networkports_id='".$data["id"]."'";
                     $result_vlan = $DB->query($query_vlan);
                     if ($DB->numrows($result_vlan) > 0) {
                        echo "<table cellpadding='0' cellspacing='0'>";
                        while ($line = $DB->fetch_array($result_vlan)) {
                           $used[]=$line["vlans_id"];
                           $vlan = new Vlan();
                           $vlan->getFromDB($line["vlans_id"]);
                           $a_vlan = Dropdown::getDropdownName("glpi_vlans", $line["vlans_id"],1);
                           echo "<tr><td>" . $vlan->fields['name']." [".$vlan->fields['tag']."]";
                           echo "</td><td>";
                           if ($canedit) {
                              echo "<a href='" . $CFG_GLPI["root_doc"] . "/front/networkport.form.php?unassign_vlan=unassigned&amp;id=" . $line["id"] . "'>";
                              echo "<img src=\"" . $CFG_GLPI["root_doc"] . "/pics/delete.png\" alt='" . $LANG['buttons'][6] . "' title='" . $LANG['buttons'][6] . "'></a>";
                           } else
                              echo "&nbsp;";
                           echo "</td></tr>";
                        }
                        echo "</table>";
                     } else {
                        echo "&nbsp;";
                     }


                     echo "</td>";
                     break;

                  case 15 :
                     //Port description
                     echo "<td>".$data["ifdescr"]."</td>";
                     break;
               }
            }

            echo "</tr>";


            // Historique

            echo "
            <tr style='display: none;' id='viewfollowup".$data["id"]."'>";
            echo "<td colspan='".(mysql_num_rows($result_array) + 2)."' id='viewfollowuphistory".$data["id"]."'></td>";
            Ajax::UpdateItemOnEvent('plusmoinsl'.$data["id"],
                                  'viewfollowuphistory'.$data["id"],
                                  $CFG_GLPI['root_doc']."/plugins/fusinvsnmp/ajax/showporthistory.php",
                                  array('ports_id' => $data["id"]),
                                  array("click"));
            echo "</tr>
            <script>Ext.get('viewfollowup".$data["id"]."').setVisibilityMode(Ext.Element.DISPLAY);</script>
            ";
         }
      }
      if ($monitoring == '1') {
         if (PluginMonitoringProfile::haveRight("componentscatalog", 'w')) {
            echo "<tr>";
            echo "<td colspan='2'></td>";
            echo "<td class='center'>";
            echo "<input type='submit' class='submit' name='update' value='update'/>";
            echo "</td>";
            echo "<td colspan='".($nbcol - 3)."'></td>";
            echo "</tr>";
         }
      }
      echo "</table>";
      if ($monitoring == '1') {
         if (PluginMonitoringProfile::haveRight("componentscatalog", 'w')) {
            Html::closeForm();
         }
      }
   }

   
   
   /**
    * Convert size of octets
    * 
    * @param number $bytes
    * @param number $sizeoct
    * 
    * @return better size format 
    */
   private function byteSize($bytes,$sizeoct=1024) {
      $size = $bytes / $sizeoct;
      if ($size < $sizeoct) {
         $size = number_format($size, 0);
         $size .= ' K';
      } else {
         if ($size / $sizeoct < $sizeoct) {
            $size = number_format($size / $sizeoct, 0);
            $size .= ' M';
         } else if ($size / $sizeoct / $sizeoct < $sizeoct) {
            $size = number_format($size / $sizeoct / $sizeoct, 0);
            $size .= ' G';
         } else if ($size / $sizeoct / $sizeoct / $sizeoct < $sizeoct) {
            $size = number_format($size / $sizeoct / $sizeoct / $sizeoct, 0);
            $size .= ' T';
         }
      }
      return $size;
   }



   function displayHubConnections($items_id, $background_img){

      $NetworkPort = new NetworkPort();
      
      $a_ports = $NetworkPort->find("`itemtype`='PluginFusioninventoryUnknownDevice'
                                    AND `items_id`='".$items_id."'");
      echo "<table width='100%' class='tab_cadre' cellpadding='5'>";
      foreach ($a_ports as $a_port) {
         if ($a_port['name'] != "Link") {
            $id = $NetworkPort->getContact($a_port['id']);
            if ($id) {
               $NetworkPort->getFromDB($id);
               $link = '';
               $link1 = '';
               $link2 = '';
               if ($NetworkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
                  $classname = $NetworkPort->fields['itemtype'];
                  $item = new $classname;
                  $item->getFromDB($NetworkPort->fields['items_id']);
                  $link1 = $item->getLink(1);
                  $link = str_replace($item->getName(0), $NetworkPort->fields["mac"],
                                      $item->getLink());
                  $link2 = str_replace($item->getName(0), $NetworkPort->fields["ip"],
                                       $item->getLink());
                  if ($item->fields['accepted'] == 1) {
                     echo "<tr>";
                     echo "<td align='center'  style='background:#bfec75'
                                              class='tab_bg_1_2'>".$item->getLink(1);

                  } else {
                     echo "<tr>";
                     echo "<td align='center' style='background:#cf9b9b'
                                              class='tab_bg_1_2'>".$item->getLink(1);
                  }
                  if (!empty($link)) {
                     echo "<br/>".$link;
                  }
                  if (!empty($link2)) {
                     echo "<br/>".$link2;
                  }
                  echo "</td>";
                  echo "</tr>";
               } else {
                  $classname = $NetworkPort->fields['itemtype'];
                  $item = new $classname;
                  $item->getFromDB($NetworkPort->fields['items_id']);
                  $link1 = $item->getLink(1);
                  $link = str_replace($item->getName(0), $NetworkPort->fields["mac"],
                                      $item->getLink());
                  $link2 = str_replace($item->getName(0), $NetworkPort->fields["ip"],
                                       $item->getLink());
                  echo "<tr>";
                  echo "<td align='center'  ".$background_img."
                                           class='tab_bg_1_2'>".$item->getLink(1);
                  if (!empty($link)) {
                     echo "<br/>".$link;
                  }
                  if (!empty($link2)) {
                     echo "<br/>".$link2;
                  }
                  echo "</td>";
                  echo "</tr>";
                  
               }
            }
         }
      }
      echo "</table>";
   }
}

?>