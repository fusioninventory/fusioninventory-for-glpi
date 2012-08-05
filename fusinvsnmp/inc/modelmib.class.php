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

class PluginFusinvsnmpModelMib extends CommonDBTM {
   

   function showFormList($id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if (!PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")) {
         return false;
      } else if ((isset($id)) AND (!empty($id))) {
         $query = "SELECT `itemtype`
                   FROM `glpi_plugin_fusinvsnmp_models`
                   WHERE `id`='".$id."';";
         $result = $DB->query($query);
         $data = $DB->fetch_assoc($result);
         $type_model = $data['itemtype'];

         $query = "SELECT `glpi_plugin_fusinvsnmp_models`.`itemtype`,
                          `glpi_plugin_fusinvsnmp_modelmibs`.*,
                          `glpi_plugin_fusioninventory_mappings`.`name`,
                          `glpi_plugin_fusioninventory_mappings`.`locale`
                   FROM `glpi_plugin_fusinvsnmp_modelmibs`
                        LEFT JOIN `glpi_plugin_fusinvsnmp_models`
                        ON `glpi_plugin_fusinvsnmp_modelmibs`.`plugin_fusinvsnmp_models_id`=
                           `glpi_plugin_fusinvsnmp_models`.`id`
                        LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                        ON `glpi_plugin_fusinvsnmp_modelmibs`.`plugin_fusioninventory_mappings_id`=
                           `glpi_plugin_fusioninventory_mappings`.`id`
                   WHERE `glpi_plugin_fusinvsnmp_models`.`id`='".$id."';";
         $result = $DB->query($query);
         if ($result) {
            $object_used = array();
            $mappings_used = array();

            $this->getFromDB($id);

            $target = $CFG_GLPI['root_doc'].'/plugins/fusinvsnmp/front/model.form.php';
            echo "<div align='center'><form method='post' name='oid_list' id='oid_list'
                       action=\"".$target."\">";

            $nb_col = 8;
            if ($type_model == NETWORKING_TYPE) {
               $nb_col++;
            }
            echo "<table class='tab_cadre_fixe'><tr><th colspan='".$nb_col."'>";
            echo $LANG['plugin_fusinvsnmp']['mib'][5]."</th></tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<th align='center'></th>";
            echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][1]."</th>";
            echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][2]."</th>";
            echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][3]."</th>";
            echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][6]."</th>";
            echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][7]."</th>";
            echo "<th align='center' width='250'>".$LANG['plugin_fusinvsnmp']['mib'][8]."</th>";
            if ($type_model == NETWORKING_TYPE) {
               echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mapping'][119]."</th>";
            }
            echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['model_info'][11]."</th>";

            echo "</tr>";
            while ($data=$DB->fetch_array($result)) {
               if ($data["is_active"] == "0") {
                  echo "<tr class='tab_bg_1' style='color: grey; '>";
               } else {
                  echo "<tr class='tab_bg_1'>";
               }
               echo "<td align='center'>";
               echo "<input name='item_coche[]' value='".$data["id"]."' type='checkbox'>";
               echo "</td>";

               echo "<td align='center'>";
               echo Dropdown::getDropdownName("glpi_plugin_fusinvsnmp_miblabels",$data["plugin_fusinvsnmp_miblabels_id"]);
               echo "</td>";

               echo "<td align='center'>";
               $object_used[] = $data["plugin_fusinvsnmp_mibobjects_id"];
               echo Dropdown::getDropdownName("glpi_plugin_fusinvsnmp_mibobjects",
                                    $data["plugin_fusinvsnmp_mibobjects_id"]);
               echo "</td>";

               echo "<td align='center'>";
               echo Dropdown::getDropdownName("glpi_plugin_fusinvsnmp_miboids",$data["plugin_fusinvsnmp_miboids_id"]);
               echo "</td>";

               echo "<td align='center'>";
               if ($data["oid_port_counter"] == "1") {
                  if ($data["is_active"] == "1") {
                     echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                  } else if ($data["is_active"] == "0") {
                     echo "<img src='".$CFG_GLPI["root_doc"].
                              "/plugins/fusioninventory/pics/bookmark_off.png'/>";
                  }
               }
               echo "</td>";

               echo "<td align='center'>";
               if ($data["oid_port_dyn"] == "1") {
                  if ($data["is_active"] == "1") {
                     echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                  } else if ($data["is_active"] == "0") {
                     echo "<img src='".$CFG_GLPI["root_doc"].
                              "/plugins/fusioninventory/pics/bookmark_off.png'/>";
                  }
               }
               echo "</td>";

               echo "<td align='center'>";
               $mapping = new PluginFusioninventoryMapping();
               $mapping->getFromDB($data['plugin_fusioninventory_mappings_id']);
               if (isset($mapping->fields['locale'])) {
                  if (!isset($LANG['plugin_fusinvsnmp']['mapping'][$mapping->fields['locale']])) {
                     echo "(".$mapping->fields['name'].")";
                  } else {
                     echo $LANG['plugin_fusinvsnmp']['mapping'][$mapping->fields['locale']]." (".$mapping->fields['name'].")";
                  }
               }
               if (isset($mapping->fields['id'])) {
                  $mappings_used[$mapping->fields['id']] = 1;
               }
               echo "</td>";

               if ($data['itemtype'] == NETWORKING_TYPE) {
                  echo "<td align='center'>";
                  if ($data["vlan"] == "1") {
                     if ($data["is_active"] == "1") {
                        echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                     } else if ($data["is_active"] == "0") {
                        echo "<img src='".$CFG_GLPI["root_doc"].
                                 "/plugins/fusioninventory/pics/bookmark_off.png'/>";
                     }
                  }
                  echo "</td>";
               }

               echo "<td align='center'>";
               echo "<a href='".$target."?id=".$id."&is_active=".$data["id"]."'>";
               if ($data["is_active"] == "1") {
                  echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
               } else if ($data["is_active"] == "0") {
                  echo "<img src='".$CFG_GLPI["root_doc"].
                           "/plugins/fusioninventory/pics/bookmark_off.png'/>";
               }
               echo "</a>";
               echo "</td>";

               echo "</tr>";
            }
            echo "</table>";

            echo "<div align='center'>";
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr>";
            echo "<td><img src=\"".$CFG_GLPI["root_doc"]."/pics/arrow-left.png\" alt=''></td>
                  <td align='center'><a onclick= \"if ( markCheckboxes('oid_list') ) return false;\"
                      href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG["buttons"][18]."</a></td>";
            echo "<td>/</td><td align='center'><a onclick= \"if ( unMarkCheckboxes('oid_list') )
                     return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".
                     $LANG["buttons"][19]."</a>";
            echo "</td><td align='left' colspan='6' width='80%'>";
            if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
               echo "<input class='submit' type='submit' name='delete_oid' value='" .
                     $LANG["buttons"][6] . "'>";
            }
            echo "</td>";
            echo "</tr>";
            echo "</table></div>";

            echo "</table>";
            Html::closeForm();
            echo "</div>";
            if (isset($options['create'])) {
               if (($options['create'])) {
                  $this->showFormAdd($id, $type_model, $mappings_used);
               }
            }
         }
      }
   }

   

   function showFormAdd($id, $type_model, $mappings_used) {
      global $CFG_GLPI,$LANG;

      echo "<br>";
      $target = $CFG_GLPI['root_doc'].'/plugins/fusinvsnmp/front/model.form.php';
      echo "<div align='center'><form method='post' name='oid_add' id='oid_add'
                 action=\"".$target."\">";

      echo "<br/>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'><th colspan='7'>".$LANG['plugin_fusinvsnmp']['mib'][4].
               "</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][1]."</th>";
      echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][2]."</th>";
      echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][3]."</th>";
      echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][6]."</th>";
      echo "<th align='center'>".$LANG['plugin_fusinvsnmp']['mib'][7]."</th>";
      echo "<th align='center' width='250'>".$LANG['plugin_fusinvsnmp']['mib'][8]."</th>";
      if ($type_model == NETWORKING_TYPE) {
         echo "<th align='center'>".$LANG["networking"][56]."</th>";
      }
      echo "</tr>";

      echo "<td align='center'>";
      Dropdown::show("PluginFusinvsnmpMibLabel",
                     array('name' => "plugin_fusinvsnmp_miblabels_id",
                           'value' => 0));
      echo "</td>";

      echo "<td align='center'>";
      Dropdown::show("PluginFusinvsnmpMibObject",
                     array('name' => "plugin_fusinvsnmp_mibobjects_id",
                           'value' => 0));
      echo "</td>";

      echo "<td align='center'>";
      Dropdown::show("PluginFusinvsnmpMibOid",
                     array('name' => "plugin_fusinvsnmp_miboids_id",
                           'value' => 0));
      echo "</td>";

      echo "<td align='center'>";
      Dropdown::showYesNo("oid_port_counter");
      echo "</td>";

      echo "<td align='center'>";
      Dropdown::showYesNo("oid_port_dyn");
      echo "</td>";

      echo "<td align='center'>";
      $types = array();
      $types[] = "-----";
      $oMapping = new PluginFusioninventoryMapping();
      $mappings = $oMapping->find("`itemtype`='".$type_model."'");
      foreach ($mappings as $mapping) {
         if (!isset($LANG['plugin_fusinvsnmp']['mapping'][$mapping['locale']])) {
            $types[$mapping['id']] = $mapping['name'];
         } else {
            $types[$mapping['id']]=$LANG['plugin_fusinvsnmp']['mapping'][$mapping['locale']];
         }
      }

      Dropdown::showFromArray("plugin_fusioninventory_mappings_id",$types,
                              array('used'=>$mappings_used));

      echo "</td>";

      if ($type_model == NETWORKING_TYPE) {
         echo "<td align='center'>";
         Dropdown::showYesNo("vlan");
         echo "</td>";
      }

      echo "</tr>";

      echo "<tr class='tab_bg_1'><td colspan='7' align='center'>";
      if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
         echo "<input type='hidden' name='plugin_fusinvsnmp_models_id' value='".$id."'/>";
         echo "<input type='submit' name='add_oid' value=\"".$LANG["buttons"][2].
               "\" class='submit' >";
      }
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
      echo "</div>";
   }


   
   function deleteMib($item_coche) {
      
      PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
      $size = count($item_coche);
      for ($i = 0; $i < $size; $i++) {
         $this->getFromDB($item_coche[$i]);
         $this->deleteFromDB(1);
      }
   }



   function activation($id) {
      
      $mib_networking = new PluginFusinvsnmpModelMib();
      
      $mib_networking->getFromDB($id);
      $data = array();
      $data['id'] = $id;
      $data = $mib_networking->fields;
      if ($mib_networking->fields["is_active"] == "1") {
         $data['is_active'] = 0;
      } else {
         $data['is_active'] = 1;
      }
      $mib_networking->update($data);
   }


   
   function oidList($p_sxml_node,$p_id) {
      global $DB;

      $ptc = new PluginFusinvsnmpCommunicationSNMP();

      // oid GET
      $query = "SELECT `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`,
                       `glpi_plugin_fusinvsnmp_modelmibs`.*
                FROM `glpi_plugin_fusinvsnmp_modelmibs`
                     LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                               ON `glpi_plugin_fusinvsnmp_modelmibs`.`plugin_fusioninventory_mappings_id`=
                                  `glpi_plugin_fusioninventory_mappings`.`id`
                WHERE `plugin_fusinvsnmp_models_id`='".$p_id."'
                  AND `is_active`='1'
                  AND `oid_port_counter`='0';";

      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         switch ($data['oid_port_dyn']) {
            case 0:
               $ptc->addGet($p_sxml_node,
                  $data['mapping_name'],
                  Dropdown::getDropdownName('glpi_plugin_fusinvsnmp_miboids',$data['plugin_fusinvsnmp_miboids_id']),
                  $data['mapping_name'], $data['vlan']);
               break;
            
            case 1:
               $ptc->addWalk($p_sxml_node,
                  $data['mapping_name'],
                  Dropdown::getDropdownName('glpi_plugin_fusinvsnmp_miboids',$data['plugin_fusinvsnmp_miboids_id']),
                  $data['mapping_name'], $data['vlan']);
               break;
            
         }
      }
      // oid WALK
   }
}

?>