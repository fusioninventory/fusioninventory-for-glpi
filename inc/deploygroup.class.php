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
   @author    Alexandre Delaunay
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
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryDeployGroup extends CommonDBTM {

   static $rightname = "plugin_fusioninventory_configuration";

   protected $static_group_types = array('Computer');

   public $dohistory = TRUE;


   static function getTypeName($nb=0) {

      if ($nb>1) {
         return __('Task');
      }
      return __('Groups of computers', 'fusioninventory');
   }

   static function canCreate() {
      return true;
   }

   public function __construct() {
      $this->grouptypes = array(
            'STATIC'    => __('Static group', 'fusioninventory'),
            'DYNAMIC'   => __('Dynamic group', 'fusioninventory')
         );
   }

//   function defineTabs($options=array()) {
//
//      $ong = array();
//      if ($this->fields['id'] > 0){
//         $this->addStandardTab("PluginFusioninventoryDeployGroup_Staticdata", $ong, $options);
//         $this->addStandardTab("PluginFusioninventoryDeployGroup_Dynamicdata", $ong, $options);
//      }
//      $this->addStandardTab('Log', $ong, $options);
//      return $ong;
//   }



   function showMenu($options=array())  {

      $this->displaylist = false;

      $this->fields['id'] = -1;
      $this->showList();
   }



   function title() {
      global $CFG_GLPI;

      $buttons = array();
      $title = __('Groups of computers', 'fusioninventory');

      if ($this->canCreate()) {
         $buttons["group.form.php?new=1"] = __('Add group', 'fusioninventory');
         $title = "";
      }

      Html::displayTitle($CFG_GLPI['root_doc']."/plugins/fusinvdeploy/pics/menu_group.png",
                         $title, $title, $buttons);
   }



   function showForm($ID, $options = array()) {

      if (isset($_SESSION['groupSearchResults'])) {
         unset($_SESSION['groupSearchResults']);
      }

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";

      echo "<td rowspan='2'>".__('Comments')."&nbsp;:</td>";
      echo "<td rowspan='2' align='center'>";
      echo "<textarea cols='40' rows='6' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Type')."&nbsp;:</td>";
      echo "<td align='center'>";
      self::dropdownGroupType('type', $this->fields['type']);
      echo "</td>";
      echo "</tr>";
      echo "</table></div>";
      
      $this->showFormButtons($options);

      return TRUE;
   }


/*
   function showStaticForm() {
      global $DB, $CFG_GLPI;

      $groupID = $this->fields['id'];
      if (!$this->can($groupID, 'r')) {
         return FALSE;
      }
      $canedit = $this->can($groupID, 'w');
      $rand = mt_rand();

      $query = "SELECT DISTINCT `itemtype`
                FROM `glpi_plugin_fusioninventory_deploygroups_staticdatas` as `staticdatas`
                WHERE `staticdatas`.`groups_id` = '$groupID'
                ORDER BY `itemtype`";

      $result = $DB->query($query);
      $number = $DB->numrows($result);


      echo "<div class='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='5'>";
      if ($DB->numrows($result)==0) {
         echo __('No associated element');
      } else {
         echo __('Associated items');
      }
      echo "</th></tr>";
      $totalnb = 0;
      if ($number > 0) {
         if ($canedit) {
            echo "</table></div>";

            echo "<form method='post' name='group_form$rand' id='group_form$rand' action=\"".
                   $CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/deploygroup.form.php\">";
            echo "<input type='hidden' name='type' value='static' />";

            echo "<div class='spaced'>";
            echo "<table class='tab_cadre_fixe'>";
            // massive action checkbox
            echo "<tr><th>&nbsp;</th>";
         } else {
            echo "<tr>";
         }



         echo "<th>".__('Type')."</th>";
         echo "<th>".__('Name')."</th></tr>";




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
                         FROM `glpi_plugin_fusioninventory_deploygroups_staticdatas`
                                 AS `staticdatas`, `$itemtable`";
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
                     echo "<input type='checkbox' name='item[".$data["IDD"]."]' ".
                             "value='1' $sel></td>";
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
      echo "<td class='center' colspan='2'><b>".($totalnb>0? __('Total').

             "&nbsp;=&nbsp;$totalnb</b></td>" : "&nbsp;</b></td>");
      echo "<td colspan='4'>&nbsp;</td></tr> ";

      if ($canedit && $totalnb > 0) {
         echo "</table>";

         Html::openArrowMassives("group_form$rand", TRUE);
         echo "<input type='hidden' name='groups_id' value='$groupID'>";
         Html::closeArrowMassives(array('deleteitem' => __('Delete')));


      } else {
         echo "</table>";
      }


      echo "</div>";
      Html::closeForm();

      echo "<form name='group_search' id='group_search' method='POST' action='"
         .$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/deploygroup.form.php'>";
      echo "<input type='hidden' name='groupID' value='$groupID' />";
      echo "<input type='hidden' name='type' value='static' />";
      echo "<div class='center'>";
      echo "<table class='tab_cadre_fixe'>";

      $this->showSearchFields('static');

      echo "</table>";

      echo "<input type='button' value=\"".__('Search')

            ."\" id='group_search_submit' class='submit' name='add_item' />&nbsp;";

      echo "<div id='group_results'></div>";
      echo "</div>";

      Html::closeForm();
   }
*/





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
      global $CFG_GLPI;

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
               <img src='".$CFG_GLPI["root_doc"]."/pics/first.png' alt=\"".__('Start').

                "\" title=\"".__('Start')."\"></a></th>";
         echo "<th class='left'><a href='javascript:reloadTab(\"start=$back\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/left.png' alt=\"".__('Previous').

                "\" title=\"".__('Previous')."\"></th>";
      }

      echo "<td width='50%' class='tab_bg_2'>";
      Html::printPagerForm();
      echo "</td>";

      // Print the "where am I?"
      echo "<td width='50%' class='tab_bg_2 b'>";
      echo __('from')."&nbsp;".$current_start."&nbsp;".__('to')."&nbsp;".
           $current_end."&nbsp;".__('of')."&nbsp;".$numrows."&nbsp;";
      echo "</td>\n";

      // Forward and fast forward button
      if ($forward<$numrows) {
         echo "<th class='right'><a href='javascript:reloadTab(\"start=$forward\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/right.png' alt=\"".__('Next').

                "\" title=\"".__('Next')."\"></a></th>";
         echo "<th class='right'><a href='javascript:reloadTab(\"start=$end\");'>
               <img src='".$CFG_GLPI["root_doc"]."/pics/last.png' alt=\"".__('End').

                "\" title=\"".__('End')."\"></th>";
      }

      // End pager
      echo "</tr></table>";
   }



   static function showSearchResults($params) {
      global $DB;

      if (isset($params['type'])) {
         $type  = $params['type'];
      } else {
         exit;
      }
//      $options = array(
//         'type'                  => $type,
//         'itemtype'              => $params['itemtype'],
//            'locations_id'          => $params['locations_id'],
//            'serial'                => $params['serial'],
//            'operatingsystems_id'   => $params['operatingsystems_id'],
//            'operatingsystem_name'  => $params['operatingsystem_name'],
//            'otherserial'           => $params['otherserial'],
//            'name'                  => $params['name'],
//         'limit'                 => 99999999
//      );

      $query = "SELECT `glpi_computers`.`id` FROM `glpi_computers`";
      $where = 0;
      // * Serial
      if (isset($params['serial'])
              && !empty($params['serial'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`serial` LIKE '%".$params['serial']."%'";
         $where++;
      }
      // * Otherserial
      if (isset($params['otherserial'])
              && !empty($params['otherserial'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`otherserial` LIKE '%".$params['otherserial']."%'";
         $where++;
      }
      // * name
      if (isset($params['name'])
              && !empty($params['name'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`name` LIKE '%".$params['name']."%'";
         $where++;
      }
      // * locations_id
      if (isset($params['locations_id'])
              && !empty($params['locations_id'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`locations_id`='".$params['locations_id']."'";
         $where++;
      }
      // * operatingsystems_id
      if (isset($params['operatingsystems_id'])
              && !empty($params['operatingsystems_id'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`operatingsystems_id`='".$params['operatingsystems_id']."'";
         $where++;
      }
      // * operatingsystem_name
      if (isset($params['operatingsystem_name'])
              && !empty($params['operatingsystem_name'])) {
         if ($where == 0) {
            $query .= " WHERE ";
         } else {
            $query .= " AND ";
         }
         $query .= "`operatingsystem_name`='".$params['operatingsystem_name']."'";
         $where++;
      }
      $result = $DB->query($query);

//      if ($options['operatingsystems_id'] != 0) unset($options['operatingsystem_name']);
//      if ($options['operatingsystems_id'] == 0) unset($options['operatingsystems_id']);
//      if ($options['locations_id'] == 0) unset($options['locations_id']);
//      $nb_items = count(PluginWebservicesMethodInventaire::methodListInventoryObjects(
//         $options, ''));
//
//      $options['limit'] = 50;
//      $options['start'] = $params['start'];
      $nb_items = $DB->numrows($result);
//      $datas = PluginWebservicesMethodInventaire::methodListInventoryObjects($options, '');
      $datas = array();
      while ($data=$DB->fetch_array($result)) {
         $datas[$data['id']] = $data;

      }

      echo "<div class='center'><br />";
      $nb_col = 5;

      echo "<table class='tab_cadrehov' style='width:950px'>";
      echo "<thead><tr>";
      if ($type == 'static') {
         echo "<th></th>";
      }
      echo "<th colspan='".($nb_col*2)."'>".__('Computers')."</th>";
      echo "</tr></thead>";

      $stripe = TRUE;
      $computer = new Computer();
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
         echo $computer->getLink(TRUE);
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
            .__('Add')." name='additem' />";
         Html::closeArrowMassives(array());
      } else {
         echo "<br />";
      }

      self::printGroupPager('', $params['start'], $nb_items);

      echo "</div>";
   }



   static function getAllDatas($root = 'groups')  {
      global $DB;

      $sql = " SELECT id, name
               FROM glpi_plugin_fusioninventory_deploygroups
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




   function getSearchOptions() {

      $tab = array();

      $tab['common'] = self::getTypeName();


      $tab[1]['table']          = $this->getTable();
      $tab[1]['field']          = 'name';
      $tab[1]['linkfield']      = '';
      $tab[1]['name']           = __('Name');
      $tab[1]['datatype']       = 'itemlink';
      $tab[1]['massiveaction']   = false;

      $tab[2]['table']           = $this->getTable();
      $tab[2]['field']           = 'type';
      $tab[2]['name']            = __('Type');
      $tab[2]['datatype']        = 'specific';
      $tab[2]['massiveaction']   = false;
      $tab[2]['searchtype']      = 'equals';

      return $tab;
   }

   static function getSpecificValueToDisplay($field, $values, array $options=array()) {
      $group = new self();
      if (!is_array($values)) {
         $values = array($field => $values);
      }
      switch ($field) {
         case 'type' :
            return $group->grouptypes[$values[$field]];
      }
      return '';
   }

   /**
   * Display dropdown to select dynamic of static group
   */
   static function dropdownGroupType($name = 'type', $value = 'STATIC') {
      $group = new self();
      return Dropdown::showFromArray($name, $group->grouptypes, array('value'=>$value));
   }

   static function getSpecificValueToSelect($field, $name='', $values='', array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }

      $options['display'] = false;
      switch ($field) {
         case 'type':
            return self::dropdownGroupType($name, $values[$field]);
         default:
            break;
      }
      return parent::getSpecificValueToSelect($field, $name, $values, $options);
   }
}
?>
