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

class PluginFusioninventorySnmpmodelMib extends CommonDBTM {


   function showFormList($id, $options=array()) {
      global $DB, $CFG_GLPI;

      if (!PluginFusioninventoryProfile::haveRight("model", "r")) {
         return FALSE;
      } else if ((isset($id)) AND (!empty($id))) {
         $query = "SELECT `itemtype`
                   FROM `glpi_plugin_fusioninventory_snmpmodels`
                   WHERE `id`='".$id."';";
         $result = $DB->query($query);
         $data = $DB->fetch_assoc($result);
         $type_model = $data['itemtype'];

         $query = "SELECT `glpi_plugin_fusioninventory_snmpmodels`.`itemtype`,
                          `glpi_plugin_fusioninventory_snmpmodelmibs`.*,
                          `glpi_plugin_fusioninventory_mappings`.`name`,
                          `glpi_plugin_fusioninventory_mappings`.`locale`
                   FROM `glpi_plugin_fusioninventory_snmpmodelmibs`
                   
                  LEFT JOIN `glpi_plugin_fusioninventory_snmpmodels`
                     ON `glpi_plugin_fusioninventory_snmpmodelmibs`.".
                        "`plugin_fusioninventory_snmpmodels_id`=
                     `glpi_plugin_fusioninventory_snmpmodels`.`id`
                     
                  LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                     ON `glpi_plugin_fusioninventory_snmpmodelmibs`.".
                        "`plugin_fusioninventory_mappings_id`=
                     `glpi_plugin_fusioninventory_mappings`.`id`
                   WHERE `glpi_plugin_fusioninventory_snmpmodels`.`id`='".$id."';";
         $result = $DB->query($query);
         if ($result) {
            $object_used = array();
            $mappings_used = array();

            $this->getFromDB($id);

            echo "<br>";
            $target = $CFG_GLPI['root_doc'].'/plugins/fusioninventory/front/snmpmodel.form.php';
            echo "<div align='center'><form method='post' name='oid_list' id='oid_list'
                       action=\"".$target."\">";

            $nb_col = 8;
            if ($type_model == NETWORKING_TYPE) {
               $nb_col++;
            }
            echo "<table class='tab_cadre_fixe'><tr><th colspan='".$nb_col."'>";
            echo __('OID list', 'fusioninventory')."</th></tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<th align='center'></th>";
            echo "<th align='center'>".__('MIB Label', 'fusioninventory')."</th>";
            echo "<th align='center'>".__('Object', 'fusioninventory')."</th>";
            echo "<th align='center'>".__('OID', 'fusioninventory')."</th>";
            echo "<th align='center'>".__('Port Counters', 'fusioninventory')."</th>";
            echo "<th align='center'>".__('Dynamic port (.x)', 'fusioninventory')."</th>";
            echo "<th align='center' width='250'>".__('Linked fields', 'fusioninventory')."</th>";
            if ($type_model == NETWORKING_TYPE) {
               echo "<th align='center'>".__('VLAN')."</th>";
            }
            echo "<th align='center'>".__('Active')."</th>";

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
               echo Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpmodelmiblabels",
                                             $data["plugin_fusioninventory_snmpmodelmiblabels_id"]);
               echo "</td>";

               echo "<td align='center'>";
               $object_used[] = $data["plugin_fusioninventory_snmpmodelmibobjects_id"];
               echo Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpmodelmibobjects",
                                    $data["plugin_fusioninventory_snmpmodelmibobjects_id"]);
               echo "</td>";

               echo "<td align='center'>";
               echo Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpmodelmiboids",
                                              $data["plugin_fusioninventory_snmpmodelmiboids_id"]);
               echo "</td>";

               echo "<td align='center'>";
               if ($data["oid_port_counter"] == "1") {
                  if ($data["is_active"] == "1") {
                     echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark.png'/>";
                  } else if ($data["is_active"] == "0") {
                     echo "<img src='".$CFG_GLPI["root_doc"].
                              "/plugins/fusioninventory/pics/bookmark_off.png'/>";
                  }
               }
               echo "</td>";

               echo "<td align='center'>";
               if ($data["oid_port_dyn"] == "1") {
                  if ($data["is_active"] == "1") {
                     echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark.png'/>";
                  } else if ($data["is_active"] == "0") {
                     echo "<img src='".$CFG_GLPI["root_doc"].
                              "/plugins/fusioninventory/pics/bookmark_off.png'/>";
                  }
               }
               echo "</td>";

               echo "<td align='left'>";
               $mapping = new PluginFusioninventoryMapping();
               $mapping->getFromDB($data['plugin_fusioninventory_mappings_id']);
               if (isset($mapping->fields['name'])) {
                  echo $mapping->getTranslation($mapping->fields)." (".
                          $mapping->fields['name'].")";
               }
               if (isset($mapping->fields['id'])) {
                  $mappings_used[$mapping->fields['id']] = 1;
               }
               echo "</td>";

               if ($data['itemtype'] == NETWORKING_TYPE) {
                  echo "<td align='center'>";
                  if ($data["vlan"] == "1") {
                     if ($data["is_active"] == "1") {
                        echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark.png'/>";
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
                  echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/bookmark.png'/>";
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
                  <td align='center'><a onclick= \"if ( markCheckboxes('oid_list') ) return FALSE;\"
                      href='".$_SERVER['PHP_SELF']."?select=all'>".
                    __('Check All', 'fusioninventory')."</a></td>";
            echo "<td>/</td><td align='center'><a onclick= \"if ( unMarkCheckboxes('oid_list') )
                     return FALSE;\" href='".$_SERVER['PHP_SELF']."?select=none'>".
                     __('Uncheck All', 'fusioninventory')."</a>";
            echo "</td><td align='left' colspan='6' width='80%'>";
            if(PluginFusioninventoryProfile::haveRight("model", "w")) {
               echo "<input class='submit' type='submit' name='delete_oid' value='" .
                     __('Delete', 'fusioninventory') . "'>";
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
      global $CFG_GLPI;

      echo "<br>";
      $target = $CFG_GLPI['root_doc'].'/plugins/fusioninventory/front/snmpmodel.form.php';
      echo "<div align='center'><form method='post' name='oid_add' id='oid_add'
                 action=\"".$target."\">";

      echo "<br/>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'><th colspan='7'>".__('add an OID...', 'fusioninventory').

               "</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>".__('MIB Label', 'fusioninventory')."</th>";
      echo "<th align='center'>".__('Object', 'fusioninventory')."</th>";
      echo "<th align='center'>".__('OID', 'fusioninventory')."</th>";
      echo "<th align='center'>".__('Port Counters', 'fusioninventory')."</th>";
      echo "<th align='center'>".__('Dynamic port (.x)', 'fusioninventory')."</th>";
      echo "<th align='center' width='250'>".__('Linked fields', 'fusioninventory')."</th>";
      if ($type_model == NETWORKING_TYPE) {
         echo "<th align='center'>".__('VLAN')."</th>";
      }
      echo "</tr>";

      echo "<td align='center'>";
      Dropdown::show("PluginFusinvsnmpMibLabel",
                     array('name' => "plugin_fusioninventory_snmpmodelmiblabels_id",
                           'value' => 0));
      echo "</td>";

      echo "<td align='center'>";
      Dropdown::show("PluginFusioninventorySnmpmodelMibObject",
                     array('name' => "plugin_fusioninventory_snmpmodelmibobjects_id",
                           'value' => 0));
      echo "</td>";

      echo "<td align='center'>";
      Dropdown::show("PluginFusioninventorySnmpmodelMibOid",
                     array('name' => "plugin_fusioninventory_snmpmodelmiboids_id",
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
         $types[$mapping['id']] = $oMapping->getTranslation($mapping);
      }

      Dropdown::showFromArray("plugin_fusioninventory_mappings_id", $types,
                              array('used'=>$mappings_used));

      echo "</td>";

      if ($type_model == NETWORKING_TYPE) {
         echo "<td align='center'>";
         Dropdown::showYesNo("vlan");
         echo "</td>";
      }

      echo "</tr>";

      echo "<tr class='tab_bg_1'><td colspan='7' align='center'>";
      if(PluginFusioninventoryProfile::haveRight("model", "w")) {
         echo "<input type='hidden' name='plugin_fusioninventory_snmpmodels_id' value='".$id."'/>";
         echo "<input type='submit' name='add_oid' value=\"".__('Post').

               "\" class='submit' >";
      }
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
      echo "</div>";
   }



   function deleteMib($item_coche) {

      PluginFusioninventoryProfile::checkRight("model", "w");
      $size = count($item_coche);
      for ($i = 0; $i < $size; $i++) {
         $this->getFromDB($item_coche[$i]);
         $this->deleteFromDB(1);
      }
   }



   function activation($id) {

      $mib_networking = new PluginFusioninventorySnmpmodelMib();

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



   function oidList($p_sxml_node, $p_id) {
      global $DB;

      $pfToolbox = new PluginFusioninventoryToolbox();

      // oid GET
      $query = "SELECT `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`,
                       `glpi_plugin_fusioninventory_snmpmodelmibs`.*
                FROM `glpi_plugin_fusioninventory_snmpmodelmibs`
                     LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                               ON `glpi_plugin_fusioninventory_snmpmodelmibs`.".
                                      "`plugin_fusioninventory_mappings_id`=
                                  `glpi_plugin_fusioninventory_mappings`.`id`
                WHERE `plugin_fusioninventory_snmpmodels_id`='".$p_id."'
                  AND `is_active`='1'
                  AND `oid_port_counter`='0'";

      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         switch ($data['oid_port_dyn']) {
            case 0:
               $pfToolbox->addGet($p_sxml_node,
                  $data['mapping_name'],
                  Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpmodelmiboids',
                                            $data['plugin_fusioninventory_snmpmodelmiboids_id']),
                  $data['mapping_name'], $data['vlan']);
               break;

            case 1:
               $pfToolbox->addWalk($p_sxml_node,
                  $data['mapping_name'],
                  Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpmodelmiboids',
                                            $data['plugin_fusioninventory_snmpmodelmiboids_id']),
                  $data['mapping_name'], $data['vlan']);
               break;

         }
      }
      // oid WALK
   }
}

?>
