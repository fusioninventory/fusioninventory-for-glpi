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
   @author    Alexandre Delaunay
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

class PluginFusinvdeployGroup extends CommonDBTM {

   protected $static_group_types = array('Computer');

   static function getTypeName($nb=0) {
      global $LANG;

      if ($nb>1) {
         return $LANG['plugin_fusinvdeploy']['task'][1];
      }
      return $LANG['plugin_fusinvdeploy']['group'][0];
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

   function defineTabs($options=array()) {
      global $LANG;

      $ong = array();
      if ($this->fields['id'] > 0){
         $this->addStandardTab(__CLASS__, $ong, $options);
      }

      return $ong;
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      switch(get_class($item)) {
         case __CLASS__:
            switch($item->fields['type']) {
               case "STATIC":
                  return $LANG['plugin_fusinvdeploy']['group'][1];
                  break;
               case "DYNAMIC":
                  return $LANG['plugin_fusinvdeploy']['group'][2];
                  break;
            }
            break;
      }
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      switch(get_class($item)) {
         case __CLASS__:
            $obj = new self;
            $obj->getFromDB($_POST["id"]);
            switch($obj->fields['type']) {
               case "STATIC":
                  $obj->showStaticForm();
                  break;
               case "DYNAMIC":
                  $obj->showDynamicForm();
                  break;
            }
            break;
      }
   }

   function showMenu($options=array())  {

      $this->displaylist = false;

      $this->fields['id'] = -1;
      $this->showList();
   }



   function title() {
      global $LANG;

      $buttons = array();
      $title = $LANG['plugin_fusinvdeploy']['group'][0];

      if ($this->canCreate()) {
         $buttons["group.form.php?new=1"] = $LANG['plugin_fusinvdeploy']['group'][4];
         $title = "";
      }

      Html::displayTitle(GLPI_ROOT."/plugins/fusinvdeploy/pics/menu_group.png", $title, $title, $buttons);
   }

   function getSearchURL($full=true) {
      return Toolbox::getItemTypeSearchURL('PluginFusinvdeployTask', $full);
   }

   function showForm($ID, $options = array()) {
      global $LANG;

      if (isset($_SESSION['groupSearchResults'])) unset($_SESSION['groupSearchResults']);

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
         'STATIC'    => $LANG['plugin_fusinvdeploy']['group'][1],
         'DYNAMIC'   => $LANG['plugin_fusinvdeploy']['group'][2]
      );
      Dropdown::showFromArray("type", $types, array('value'=>$this->fields['type']));
      echo "</td>";
      echo "</tr>";


      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }

   function showStaticForm() {
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


               while ($data=$DB->fetch_assoc($result_linked)) {
                  $ID = "";
                  if ($_SESSION["glpiis_ids_visible"] || empty($data["name"])) {
                     $ID = " (".$data["id"].")";
                  }
                  $link = Toolbox::getItemTypeFormURL($itemtype);
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

                  echo "<td class='center top'>".$item->getTypeName()."</td>";
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
      echo "<td class='center' colspan='2'><b>".($totalnb>0? $LANG['common'][33].
             "&nbsp;=&nbsp;$totalnb</b></td>" : "&nbsp;</b></td>");
      echo "<td colspan='4'>&nbsp;</td></tr> ";

      if ($canedit && $totalnb > 0) {
         echo "</table>";

         Html::openArrowMassives("group_form$rand", true);
         echo "<input type='hidden' name='groups_id' value='$groupID'>";
         Html::closeArrowMassives(array('deleteitem' => $LANG['buttons'][6]));

      } else {
         echo "</table>";
      }


      echo "</div>";
      Html::closeForm();

      echo "<form name='group_search' id='group_search' method='POST' action='"
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

      Html::closeForm();
   }

   function showDynamicForm() {
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
      Html::closeForm();


      //prepare div for ajax results
      echo "<div id='group_results'></div>";

   }

   function showSearchFields($type = 'static', $fields = array())  {
      global $DB, $CFG_GLPI, $LANG;

      if (count($fields) == 0) {
         $fields = array(
            'itemtype'              => 'computer',
            'start'                 => '0',
            'limit'                 => '',
            'serial'                => '',
            'otherserial'           => '',
            'locations_id'          => '',
            'operatingsystems_id'   => '0',
            'operatingsystem_name'  => '',
            'room'                  => '',
            'building'              => '',
            'name'                  => ''
         );

         if (isset($_SESSION['groupSearchResults'])) {
            foreach($_SESSION['groupSearchResults'] as $key => $field) {
               $fields[$key] = $field;
            }
         }
      }

      echo "<tr><th colspan='4'>".$LANG['buttons'][0]."</th></tr>";
      echo "<tr>";

      echo "<td class='left'></td>";
      echo "<td class='left'>";
      echo "<input type='hidden' name='itemtype' id='group_search_itemtype' value='Computer' />";
      echo "</td>";

      echo "<td>".$LANG['common'][15]."&nbsp;: </td>";
      echo "<td>";
      $rand_location = '';
      Dropdown::show('Location', array(
         'value'  => $fields['locations_id'],
         'name'   => 'locations_id',
         'rand'   => $rand_location
      ));
      echo "</td>";

      echo "</tr><tr>";

/*
      echo "<td class='left'>".$LANG['buttons'][33]." : ";
      echo "<input type='text' name='start' id='group_search_start' value='".$fields['start']
         ."' value='0' size='3' /></td>";

      echo "<td class='left'>".$LANG['pager'][4]."&nbsp;";
      echo "<input type='text' name='limit' id='group_search_limit' value='".$fields['limit']
         ."' size='3' />&nbsp;";
      echo $LANG['pager'][5];
      echo "</td>";
*/

      echo "<td class='left'>".$LANG['setup'][100]." : </td>";
      echo "<td class='left'><input type='text' name='room' id='group_search_room' value='"
         .$fields['room']."' size='15' /></td>";

      echo "<td class='left'>".$LANG['setup'][99]." : </td>";
      echo "<td class='left'><input type='text' name='building' id='group_search_building' value='"
         .$fields['building']."' size='15' /></td>";

      echo "</tr><tr>";

      echo "<td class='left'>".$LANG['common'][19]." : </td>";
      echo "<td class='left'><input type='text' name='serial' id='group_search_serial' value='"
         .$fields['serial']."' size='15' /></td>";

      echo "<td class='left'>".$LANG['rulesengine'][25]." : </td>";
      echo "<td class='left'><input type='text' name='name' id='group_search_name' value='"
         .$fields['name']."' size='15' /></td>";

      echo "</tr><tr>";

      echo "<td class='left'>".$LANG['common'][20]." : </td>";
      echo "<td class='left'><input type='text' name='otherserial' id='group_search_otherserial' value='"
         .$fields['otherserial']."' size='15' /></td>";

      echo "<td class='left'>".$LANG['computers'][9]." : </td>";
      echo "<td>";
      /*$rand_os = mt_rand();
      Dropdown::show('OperatingSystem', array(
         'value'  => $fields['operatingsystems_id'],
         'name'   => 'operatingsystems_id',
         'rand'   => $rand_os
      ));
      echo "<hr />";*/

      self::ajaxDisplaySearchTextForDropdown("operatingsystems_id",8, $fields['operatingsystem_name']);
      $params_os = array('searchText'     => '__VALUE__',
                             'myname'     => 'operatingsystems_id',
                             'table'      => 'glpi_operatingsystems',
                             'value'      => $fields['operatingsystems_id']);

      Ajax::updateItemOnInputTextEvent("search_operatingsystems_id", "operatingsystems_dropdown",
                                     $CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/ajax/dropdown_operatingsystems.php",
                                     $params_os);

      //load default operatingsystems_dropdown
      Ajax::updateItem("operatingsystems_dropdown",
                                     $CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/ajax/dropdown_operatingsystems.php",
                                     $params_os, /*false,*/ "search_operatingsystems_id");

      echo "<span id='operatingsystems_dropdown'>";
      echo "<select name='operatingsystems_id' id='operatingsystems_id'><option value='0'>".Dropdown::EMPTY_VALUE."</option></select>";
      echo "</span>\n";

      Html::showToolTip("* ".$LANG['search'][1]."<br />".$LANG['plugin_fusinvdeploy']['group'][5]);

      echo "</td>";

      echo "</tr><tr>";

      echo "<td class='center' colspan='4'>";

      self::groupAjaxLoad($type);

      echo "</td>";
      echo "</tr>";
   }

   static function groupAjaxLoad($type) {
      global $CFG_GLPI;

      self::ajaxLoad(
         'group_search_submit',
         'group_results',
         $CFG_GLPI["root_doc"]."/plugins/fusinvdeploy/ajax/group_results.php",
         array(
            'itemtype'              => 'group_search_itemtype',
            'locations_id'          => 'dropdown_locations_id',
            'operatingsystem_name'  => 'search_operatingsystems_id',
            'operatingsystems_id'   => 'operatingsystems_id',
            'serial'                => 'group_search_serial',
            'otherserial'           => 'group_search_otherserial',
            'name'                  => 'group_search_name'
         ),
         $type
      );

   }

   static function ajaxLoad($to_observe, $toupdate, $url, $params_id, $type) {
      $start = 0;
      if (isset($_REQUEST['start'])) $start = $_REQUEST['start'];


      echo "<script type='text/javascript'>
      Ext.onReady(function() {
         function loadResults() {
            Ext.get('$toupdate').load({
               url: '$url',
               scripts: true,
               params: {
                  type: '$type',
                  start: '$start',
                  ";
                  $out = "";
                  foreach($params_id as $name => $id) {
                     $out .= "$name: Ext.get('$id').getValue(),";
                  }
                  echo substr($out, 0, -1);
               echo"}
            });
         }
         Ext.get('$to_observe').on('click', function() {
            loadResults();
         });";
      if (isset($_REQUEST['start'])) echo "setTimeout(function(thisObj) { loadResults(); }, 200, this);";

      echo "})
      </script>";
   }

   /**
    * Print pager for group list
    *
    * @param $title displayed above
    * @param $start from witch item we start
    * @param $numrows total items
    *
    * @return nothing (print a pager)
    **/
   static function printGroupPager($title, $start, $numrows) {
      global $CFG_GLPI, $LANG;

      $list_limit = 50;
      // Forward is the next step forward
      $forward = $start+$list_limit;

      // This is the end, my friend
      $end = $numrows-$list_limit;

      // Human readable count starts here
      $current_start = $start+1;

      // And the human is viewing from start to end
      $current_end = $current_start+$list_limit-1;
      if ($current_end>$numrows) {
         $current_end = $numrows;
      }
      // Empty case
      if ($current_end==0) {
         $current_start = 0;
      }
      // Backward browsing
      if ($current_start-$list_limit<=0) {
         $back = 0;
      } else {
         $back = $start-$list_limit;
      }

      // Print it
      echo "<table class='tab_cadre_pager'>";
      if ($title) {
         echo "<tr><th colspan='6'>$title</th></tr>";
      }
      echo "<tr>\n";

      // Back and fast backward button
      if (!$start==0) {
         echo "<th class='left'><a href='javascript:reloadTab(\"start=0\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/first.png' alt=\"".$LANG['buttons'][33].
                "\" title=\"".$LANG['buttons'][33]."\"></a></th>";
         echo "<th class='left'><a href='javascript:reloadTab(\"start=$back\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/left.png' alt=\"".$LANG['buttons'][12].
                "\" title=\"".$LANG['buttons'][12]."\"></th>";
      }

      echo "<td width='50%' class='tab_bg_2'>";
      Html::printPagerForm();
      echo "</td>";

      // Print the "where am I?"
      echo "<td width='50%' class='tab_bg_2 b'>";
      echo $LANG['pager'][2]."&nbsp;".$current_start."&nbsp;".$LANG['pager'][1]."&nbsp;".
           $current_end."&nbsp;".$LANG['pager'][3]."&nbsp;".$numrows."&nbsp;";
      echo "</td>\n";

      // Forward and fast forward button
      if ($forward<$numrows) {
         echo "<th class='right'><a href='javascript:reloadTab(\"start=$forward\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/right.png' alt=\"".$LANG['buttons'][11].
                "\" title=\"".$LANG['buttons'][11]."\"></a></th>";
         echo "<th class='right'><a href='javascript:reloadTab(\"start=$end\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/last.png' alt=\"".$LANG['buttons'][32].
                "\" title=\"".$LANG['buttons'][32]."\"></th>";
      }

      // End pager
      echo "</tr></table>";
   }

   static function showSearchResults($params) {
      global $CFG_GLPI, $LANG;

      if(isset($params['type'])) $type  = $params['type'];
      else exit;
      $options = array(
         'type'                  => $type,
         'itemtype'              => $params['itemtype'],
         'locations_id'          => $params['locations_id'],
         'serial'                => $params['serial'],
         'operatingsystems_id'   => $params['operatingsystems_id'],
         'operatingsystem_name'  => $params['operatingsystem_name'],
         'otherserial'           => $params['otherserial'],
         'name'                  => $params['name'],
         'limit'                 => 99999999
      );

      if ($options['operatingsystems_id'] != 0) unset($options['operatingsystem_name']);
      if ($options['operatingsystems_id'] == 0) unset($options['operatingsystems_id']);
      if ($options['locations_id'] == 0) unset($options['locations_id']);
      $nb_items = count(PluginWebservicesMethodInventaire::methodListInventoryObjects($options, ''));

      $options['limit'] = 50;
      $options['start'] = $params['start'];

      $datas = PluginWebservicesMethodInventaire::methodListInventoryObjects($options, '');

      echo "<div class='center'><br />";
      $nb_col = 5;

      echo "<table class='tab_cadrehov' style='width:950px'>";
      echo "<thead><tr>";
      if ($type == 'static') echo "<th></th>";
      echo "<th colspan='".($nb_col*2)."'>".$LANG['Menu'][0]."</th>";
      echo "</tr></thead>";

      $stripe = true;
      $computer = new Computer;
      $i=1;
      echo "<tr class='tab_bg_".(((int)$stripe)+1)."'>";
      foreach ($datas as $row) {
         $computer->getFromDB($row["id"]);
         if ($type == 'static') {
            echo "<td width='1%'>";
            echo "<input type='checkbox' name='item[".$row["id"]."]' value='".$row["id"]."'>";
            echo "</td>";
         }
         echo "<td>";
         echo $computer->getLink(true);
         echo "</td>";

         if (($i % $nb_col) == 0) {
            $stripe =! $stripe;
            echo "</tr><tr class='tab_bg_".(((int)$stripe)+1)."'>";
         }
         $i++;
      }
      echo "</tr>";
      echo "</table>";

      if ($type == 'static') {
         Html::openArrowMassives("group_search");
         echo "<input type='submit' class='submit' value="
            .$LANG['buttons'][8]." name='additem' />";
         Html::closeArrowMassives(array());
      } else echo "<br />";

      self::printGroupPager('', $params['start'], $nb_items);

      echo "</div>";
   }

   static function getAllDatas($root = 'groups')  {
      global $DB;

      $sql = " SELECT id, name
               FROM glpi_plugin_fusinvdeploy_groups
               ORDER BY name";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
       $i = 0;
      while($row = $DB->fetch_assoc($res)) {
         $json[$root][$i]['id'] = $row['id'];
         $json[$root][$i]['name'] = $row['name'];

         $i++;
      }
      $json['results'] = $nb;

      return json_encode($json);
   }


   static function ajaxDisplaySearchTextForDropdown($id, $size=4, $value) {
   global $CFG_GLPI, $LANG;

      echo "<input title=\"".$LANG['buttons'][0]." (".$CFG_GLPI['ajax_wildcard']." ".$LANG['search'][1].")\"
            type='text' value='$value' ondblclick=\"this.value='".
             $CFG_GLPI["ajax_wildcard"]."';\" id='search_$id' name='____data_$id' size='$size'>\n";
   }
}

?>