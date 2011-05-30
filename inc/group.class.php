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
      if ($canedit) {
         echo "</table></div>";

         echo "<form method='post' name='group_form$rand' id='group_form$rand' action=\"".
                $CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/front/group.form.php\">";
         echo "<div class='spaced'>";
         echo "<table class='tab_cadre_fixe'>";
         // massive action checkbox
         echo "<tr><th>&nbsp;</th>";
      } else {
         echo "<tr>";
      }
      echo "<th>".$LANG['common'][17]."</th>";
      echo "<th>".$LANG['common'][16]."</th></tr>";




      $totalnb = 0;
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

            if ($nb>$_SESSION['glpilist_limit']) {
               echo "<tr class='tab_bg_1'>";
               if ($canedit) {
                  echo "<td>&nbsp;</td>";
               }
               echo "<td class='center'>".$item->getTypeName()."&nbsp;:&nbsp;$nb</td>";
               echo "<td class='center' colspan='2'>";
               echo "<a href='". getItemTypeSearchURL($itemtype) . "?" .
                     rawurlencode("contains[0]") . "=" . rawurlencode('$$$$'.$instID) . "&amp;" .
                     rawurlencode("field[0]") . "=29&amp;sort=80&amp;order=ASC&amp;is_deleted=0".
                     "&amp;start=0". "'>" . $LANG['reports'][57]."</a></td>";
               echo "<td class='center'>-</td><td class='center'>-</td></tr>";

            } else if ($nb>0) {
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
            }
            $totalnb += $nb;
         }
      }







      echo "<tr class='tab_bg_2'>";
      echo "<td class='center' colspan='2'>".($totalnb>0? $LANG['common'][33].
             "&nbsp;=&nbsp;$totalnb</td>" : "&nbsp;</td>");
      echo "<td colspan='4'>&nbsp;</td></tr> ";

      if ($canedit) {
         echo "<tr class='tab_bg_1'><td colspan='4' class='right'>";
         Dropdown::showAllItems("items_id", 0, 0, -1, $this->static_group_types);
         echo "</td><td class='center'>";
         echo "<input type='submit' name='additem' value=\"".$LANG['buttons'][8]."\"
                class='submit'>";
         echo "</td><td>&nbsp;</td></tr>";
         echo "</table>";

         openArrowMassive("contract_form$rand", true);
         echo "<input type='hidden' name='groups_id' value='$groupID'>";
         closeArrowMassive('deleteitem', $LANG['buttons'][6]);

      } else {
         echo "</table>";
      }
      echo "</div></form>";
   }

   public function showDynamicForm() {
      global $LANG;

   }
}
