<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Alexandre DELAUNAY
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployGroup extends CommonDBTM {

   protected $static_group_types = array('Computer');

   static function getTypeName($nb=0) {
      global $LANG;

      if ($nb>1) {
         return $LANG['plugin_fusinvdeploy']['task'][5];
      }
      return $LANG['plugin_fusinvdeploy']['task'][2];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCreateItem() {
      return true;
   }

   function showList() {
      echo "<table class='tab_cadre_navigation'><tr><td>";

      self::title();
      Search::show('PluginFusinvdeployGroup');

      echo "</td></tr></table>";
   }

   function defineTabs($options=array()) {
      global $LANG;

      $ong = array();

      if ($this->fields['id'] > 0) {
         switch($this->fields['type']) {
            case "STATIC":
               $ong[2] = $LANG['plugin_fusinvdeploy']['task'][9];
               break;
            case "DYNAMIC":
               $ong[3] = $LANG['plugin_fusinvdeploy']['task'][10];
               break;
         }
      } else { // New item
         $ong[1] = $LANG['title'][26];
      }

      return $ong;
   }

   function title() {
      global $LANG, $CFG_GLPI;

      $buttons = array();
      $title = $LANG['plugin_fusinvdeploy']['task'][2];

      if ($this->canCreate()) {
         $buttons["group.form.php?new=1"] = $LANG['plugin_fusinvdeploy']['task'][4];
         $title = "";
      }

      displayTitle($CFG_GLPI["root_doc"] . "/pics/groupes.png", $title, $title, $buttons);
   }

   function getSearchURL($full=true) {
      return getItemTypeSearchURL('PluginFusinvdeployTask', $full);
   }

   function showForm($ID, $options = array()) {
      global $LANG;

      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
      }

      $options['colspan'] = 1;

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<textarea cols='40' rows='6' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][17]."&nbsp;:</td>";
      echo "<td align='center'>";
      $types = array(
         'STATIC' => $LANG['plugin_fusinvdeploy']['task'][9],
         'DYNAMIC' => $LANG['plugin_fusinvdeploy']['task'][10]
      );
      Dropdown::showFromArray("type", $types, array('value'=>$this->fields['type']));
      echo "</td>";
      echo "</tr>";


      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }

   public function showStaticForm() {
      global $DB, $CFG_GLPI, $LANG;

      $groupID = $this->fields['id'];
      if (!$this->can($groupID,'r')) {
         return false;
      }
      $canedit = $this->can($groupID,'w');
      $rand = mt_rand();

      $query = "SELECT DISTINCT `itemtype`
                FROM `glpi_plugin_fusinvdeploy_groups_staticdatas` as `staticdatas`
                WHERE `staticdatas`.`groups_id` = '$groupID'
                ORDER BY `itemtype`";

      $result = $DB->query($query);
      $number = $DB->numrows($result);


      echo "<div class='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='5'>";
      if ($DB->numrows($result)==0) {
         echo $LANG['document'][13];
      } else {
         echo $LANG['document'][19];
      }
      echo "</th></tr>";
      $totalnb = 0;
      if ($number > 0) {
         if ($canedit) {
            echo "</table></div>";

            echo "<form method='post' name='group_form$rand' id='group_form$rand' action=\"".
                   $CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/front/group.form.php\">";
            echo "<input type='hidden' name='type' value='static' />";

            echo "<div class='spaced'>";
            echo "<table class='tab_cadre_fixe'>";
            // massive action checkbox
            echo "<tr><th>&nbsp;</th>";
         } else {
            echo "<tr>";
         }



         echo "<th>".$LANG['common'][17]."</th>";
         echo "<th>".$LANG['common'][16]."</th></tr>";




         for ($i=0 ; $i<$number ; $i++) {
            $itemtype = $DB->result($result, $i, "itemtype");
            if (!class_exists($itemtype)) {
               continue;
            }
            $item = new $itemtype();
            if ($item->canView()) {
               $itemtable = getTableForItemType($itemtype);
               $query = "SELECT `$itemtable`.*,
                                `staticdatas`.`id` AS IDD
                         FROM `glpi_plugin_fusinvdeploy_groups_staticdatas` as `staticdatas`,
                              `$itemtable`";
               $query .= " WHERE `$itemtable`.`id` = `staticdatas`.`items_id`
                                 AND `staticdatas`.`itemtype` = '$itemtype'
                                 AND `staticdatas`.`groups_id` = '$groupID'";

               if ($item->maybeTemplate()) {
                  $query .= " AND `$itemtable`.`is_template` = '0'";
               }

               $result_linked = $DB->query($query);
               $nb = $DB->numrows($result_linked);


               for ($prem=true ; $data=$DB->fetch_assoc($result_linked) ; $prem=false) {
                  $ID = "";
                  if ($_SESSION["glpiis_ids_visible"] || empty($data["name"])) {
                     $ID = " (".$data["id"].")";
                  }
                  $link = getItemTypeFormURL($itemtype);
                  $name = "<a href=\"".$link."?id=".$data["id"]."\">".$data["name"]."$ID</a>";

                  echo "<tr class='tab_bg_1'>";
                  if ($canedit) {
                     $sel = "";
                     if (isset($_GET["select"]) && $_GET["select"]=="all") {
                        $sel = "checked";
                     }
                     echo "<td width='10'>";
                     echo "<input type='checkbox' name='item[".$data["IDD"]."]' value='1' $sel></td>";
                  }
                  if ($prem) {
                     echo "<td class='center top' rowspan='$nb'>".$item->getTypeName().
                           ($nb>1?"&nbsp;:&nbsp;$nb</td>":"</td>");
                  }
                  echo "<td class='center".
                         (isset($data['is_deleted']) && $data['is_deleted'] ? " tab_bg_2_2'" : "'");
                  echo ">".$name."</td>";
                  echo "</tr>";
               }

               $totalnb += $nb;
            }
         }


      }




      echo "<tr class='tab_bg_2'>";
      echo "<td class='center' colspan='2'>".($totalnb>0? $LANG['common'][33].
             "&nbsp;=&nbsp;$totalnb</td>" : "&nbsp;</td>");
      echo "<td colspan='4'>&nbsp;</td></tr> ";

      if ($canedit && $totalnb > 0) {
         echo "</table>";

         openArrowMassive("contract_form$rand", true);
         echo "<input type='hidden' name='groups_id' value='$groupID'>";
         closeArrowMassive('deleteitem', $LANG['buttons'][6]);

      } else {
         echo "</table>";
      }


      echo "</div></form>";

      echo "<form name='group_search' method='POST' action='"
         .$CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/front/group.form.php'>";
      echo "<input type='hidden' name='groupID' value='$groupID' />";
      echo "<input type='hidden' name='type' value='static' />";
      echo "<div class='center'>";
      echo "<table class='tab_cadre_fixe'>";

      $this->showSearchFields('static');

      echo "</table>";
      echo "</div>";

      echo "<input type='button' value=\"".$LANG['buttons'][0]
            ."\" id='group_search_submit' class='submit' name='add_item' />&nbsp;";

      echo "<div id='group_results'></div>";

      echo "</form>";
   }

   public function showDynamicForm() {
      global $DB, $CFG_GLPI, $LANG;

      $groupID = $this->fields['id'];
      if (!$this->can($groupID,'r')) {
         return false;
      }
      $canedit = $this->can($groupID,'w');

      $fields = array();

      //get datas
      $dynamic_group = new PluginFusinvdeployGroup_Dynamicdata;
      $query = "SELECT *
         FROM glpi_plugin_fusinvdeploy_groups_dynamicdatas
         WHERE groups_id = '$groupID'";
      $res = $DB->query($query);
      $num = $DB->numrows($res);
      if ($num > 0) {
         $data = $DB->fetch_array($res);
         $dynamic_group->getFromDB($data['id']);
         $fields = unserialize($dynamic_group->fields['fields_array']);
      }

      //show form
      echo "<form name='group_search' method='POST' action='"
         .$CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/front/group.form.php'>";
      echo "<input type='hidden' name='groupID' value='$groupID' />";
      echo "<input type='hidden' name='type' value='dynamic' />";
      echo "<div class='center'>";
      echo "<table class='tab_cadre_fixe'>";

      $this->showSearchFields('dynamic', $fields);

      echo "</table>";
      echo "</div>";
      echo "<input type='button' value=\"".$LANG['buttons'][50]
         ."\" id='group_search_submit' />&nbsp;";
      if ($num > 0) {
         echo "<input type='hidden' name='id' value='".$data['id']."' />";
         echo "<input type='submit' value=\"".$LANG['buttons'][7]."\" class='submit' name='updateitem' />";
      }  else {
         echo "<input type='submit' value=\"".$LANG['buttons'][8]."\" class='submit' name='additem' />";
      }
      echo "</form>";


      //prepare div for ajax results
      echo "<div id='group_results'></div>";

   }

   function showSearchFields($type = 'static', $fields = array())  {
      global $DB, $CFG_GLPI, $LANG;

      if (count($fields) == 0) {
         $fields = array(
            'itemtype' => 'computer',
            'start' => '0',
            'limit' => '',
            'serial' => '',
            'otherserial' => '',
            'locations' => '0',
            'room' => '',
            'building' => ''
         );
      }

      echo "<tr><th colspan='4'>".$LANG['buttons'][0]."</th></tr>";
      echo "<tr>";

      echo "<td class='left'>".$LANG['common'][17]." : </td>";
      echo "<td class='left'>";
      $itemtype = array(
         'computer'
      );
      echo "<select name='itemtype' id='group_search_itemtype'>
      <option>computer</option>
      </select>";
      echo "</td>";

      echo "<td>".$LANG['common'][15]."&nbsp;: </td>";
      echo "<td>";
      $rand_location = mt_rand();
      Dropdown::show('Location', array(
         'value'  => $fields['locations'],
         'name'   => 'locations',
         'rand'   => $rand_location
      ));
      echo "</td>";

      echo "</tr><tr>";

      echo "<td class='left'>".$LANG['buttons'][33]." : ";
      echo "<input type='text' name='start' id='group_search_start' value='".$fields['start']
         ."' value='0' size='3' /></td>";

      echo "<td class='left'>".$LANG['pager'][4]."&nbsp;";
      echo "<input type='text' name='limit' id='group_search_limit' value='".$fields['limit']
         ."' size='3' />&nbsp;";
      echo $LANG['pager'][5];
      echo "</td>";

      echo "<td class='left'>".'room '." : </td>";
      echo "<td class='left'><input type='text' name='room' id='group_search_room' value='"
         .$fields['room']."' size='15' /></td>";

      echo "</tr><tr>";

      echo "<td class='left'>".$LANG['common'][19]." : </td>";
      echo "<td class='left'><input type='text' name='serial' id='group_search_serial' value='"
         .$fields['serial']."' size='15' /></td>";

      echo "<td class='left'>".'building '." : </td>";
      echo "<td class='left'><input type='text' name='building' id='group_search_building' value='"
         .$fields['building']."' size='15' /></td>";

      echo "</tr><tr>";

      echo "<td class='left'>".$LANG['common'][20]." : </td>";
      echo "<td class='left'><input type='text' name='otherserial' id='group_search_otherserial' value='"
         .$fields['otherserial']."' size='15' /></td>";

      echo "</tr><tr>";

      echo "<td class='center' colspan='4'>";

      $this->ajaxLoad(
         'group_search_submit',
         'group_results',
         $CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/ajax/group_results.php",
         array(
            'itemtype'     => 'group_search_itemtype',
            'start'        => 'group_search_start',
            'limit'        => 'group_search_limit',
            'location_id'  => 'dropdown_locations'.$rand_location,
            'serial'       => 'group_search_serial',
            'otherserial'  => 'group_search_otherserial'
         ),
         $type
      );
      echo "</td>";
      echo "</tr>";
   }

   function ajaxLoad($to_observe, $toupdate, $url, $params_id, $type) {
      echo "<script type='text/javascript'>
      Ext.get('$to_observe').on('click', function() {
         Ext.get('$toupdate').load({
            url: '$url',
            scripts: true,
            params: {
               type: '$type',";
               $out = "";
               foreach($params_id as $name => $id) {
                  $out .= "$name: Ext.get('$id').getValue(),";
               }
               echo substr($out, 0, -1);
            echo"}
         });
      });
      </script>";
   }

   static function showSearchResulst($params) {
      global $CFG_GLPI, $LANG;

      if(isset($params['type'])) $type  = $params['type'];
      else exit;

      $params = array(
         'type'         => $type,
         'itemtype'     => $params['itemtype'],
         'start'        => $params['start'],
         'limit'        => $params['limit'],
         'location_id'  => $params['location_id'],
         'serial'       => $params['serial'],
         'otherserial'  => $params['otherserial']
      );

      $datas = PluginFusinvdeploySearch::methodListObjects($params, '');

      echo "<div class='center'>";
      echo "<table class='tab_cadrehov'>";
      echo "<thead><tr>";
      if ($type == 'static') echo "<th></th>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "</tr></thead>";

      $stripe = true;
      foreach ($datas as $row) {
         $stripe =! $stripe;
         echo "<tr class='tab_bg_".(((int)$stripe)+1)."'>";
         if ($type == 'static')
            echo "<td><input type='checkbox' name='item[".$row["id"]."]' value='1'></td>";
         echo "<td colspan='4'>".$row['name']."</td>";
         echo "</tr>";
      }

      if ($type == 'static') {
         openArrowMassive("group_search");
         echo "<input type='submit' class='submit' value="
            .$LANG['buttons'][8]." name='additem' />";
         closeArrowMassive();
      }

      echo "</table>";
      echo "</div>";
   }

   function getAllDatas()  {
      global $DB;

      $sql = " SELECT id, name
               FROM `".$this->getTable()."`
               ORDER BY name";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
      while($row = $DB->fetch_assoc($res)) {
         $row['group_id'] = $row['id'];
         $row['group_name'] = $row['name'];

         $json['groups'][] = $row;
      }

      return json_encode($json);
   }
}
