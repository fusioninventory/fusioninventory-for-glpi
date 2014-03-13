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

class PluginFusioninventoryDeployGroup_Staticdata extends CommonDBRelation{

   // From CommonDBRelation
   static public $itemtype_1 = 'PluginFusioninventoryDeployGroup';
   static public $items_id_1 = 'groups_id';

   static public $itemtype_2 = 'itemtype';
   static public $items_id_2 = 'items_id';

   function can($ID, $right, array &$input=NULL) {

      if ($ID<0) {
         // Ajout
         $group = new PluginFusioninventoryDeployGroup();

         if (!$group->getFromDB($input['groups_id'])) {
            return FALSE;
         }
      }
      return parent::can($ID, $right, $input);
   }
/*
   static function showResultsForGroup(PluginFusioninventoryDeployGroup $group) {
      global $DB, $CFG_GLPI;

      $groupID = $group->getID();
      if (!$group->can($groupID, READ)) {
         return FALSE;
      }
      $canedit = $group->can($groupID, UPDATE);
      $rand = mt_rand();

      $query = "SELECT DISTINCT `itemtype`
                FROM `glpi_plugin_fusioninventory_deploygroups_staticdatas` as `staticdatas`
                WHERE `staticdatas`.`plugin_fusioninventory_deploygroups_id` = '$groupID'
                ORDER BY `itemtype`";

      $result = $DB->query($query);
      $number = $DB->numrows($result);
      $totalnb = 0;

      echo "<form name='group_form$rand' 
                  id='group_form$rand' 
                  method='POST' 
                  action='".Toolbox::getItemTypeFormURL("PluginFusioninventoryDeployGroup")."'>";

       echo "<input type='hidden' name='type' value='static' />";

      echo "<div class='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='5'>";
      echo __('Associated items');
      echo "</th></tr>";
      if (!$number) {
         echo "<tr align='center'><td colspan='5'>";
         echo __('No associated element');
         echo "</td></tr>";
      } else {
         if ($canedit) {
            echo "</table></div>";

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
                                 AND `staticdatas`.`plugin_fusioninventory_deploygroups_id` = '$groupID'";

               if ($item->maybeTemplate()) {
                  $query .= " AND `$itemtable`.`is_template` = '0'";
               }

               $result_linked = $DB->query($query);
               $nb            = $DB->numrows($result_linked);


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

      echo "</table>";
      if ($canedit && $totalnb > 0) {

         Html::openArrowMassives("group_form$rand", TRUE);
         echo "<input type='hidden' name='groups_id' value='$groupID'>";
         Html::closeArrowMassives(array('deleteitem' => __('Delete')));

      }

      echo "</div>";
      Html::closeForm();
   }

   static function ajaxDisplaySearchTextForDropdown($id, $value, $size=4) {
      global $CFG_GLPI;

      echo "<input title=\"".__('Search')." (".$CFG_GLPI['ajax_wildcard']." ".__('for all').")\"
            type='text' value='$value' ondblclick=\"this.value='".
             $CFG_GLPI["ajax_wildcard"]."';\" id='search_$id' name='____data_$id' size='$size'>\n";
   }

   static function ajaxLoad($to_observe, $toupdate, $url, $params_id, $type) {
      $start = 0;
      if (isset($_REQUEST['start'])) {
         $start = $_REQUEST['start'];
      }

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
      if (isset($_REQUEST['start'])) {
         echo "setTimeout(function(thisObj) { loadResults(); }, 200, this);";
      }

      echo "})
      </script>";
   }
*/

   static function showSearchFields($type = 'static', $fields = array())  {
      global $CFG_GLPI;

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

      echo "<tr><th colspan='4'>".__('Search')."</th></tr>";
      echo "<tr>";

      echo "<td class='left'></td>";
      echo "<td class='left'>";
      echo "<input type='hidden' name='itemtype' id='group_search_itemtype' value='Computer' />";
      echo "</td>";

      echo "<td>".__('Location')."&nbsp;: </td>";
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
      echo "<td class='left'>".__('Start')." : ";
      echo "<input type='text' name='start' id='group_search_start' value='".$fields['start']
         ."' value='0' size='3' /></td>";

      echo "<td class='left'>".__('Display')."&nbsp;";
      echo "<input type='text' name='limit' id='group_search_limit' value='".$fields['limit']
         ."' size='3' />&nbsp;";
      echo __('items');

      echo "</td>";
*/

      echo "<td class='left'>".__('Room number')." : </td>";
      echo "<td class='left'><input type='text' name='room' id='group_search_room' value='"
         .$fields['room']."' size='15' /></td>";

      echo "<td class='left'>".__('Building number')." : </td>";
      echo "<td class='left'><input type='text' name='building' id='group_search_building' value='"
         .$fields['building']."' size='15' /></td>";

      echo "</tr><tr>";

      echo "<td class='left'>".__('Serial Number')." : </td>";
      echo "<td class='left'><input type='text' name='serial' id='group_search_serial' value='"
         .$fields['serial']."' size='15' /></td>";

      echo "<td class='left'>".__('Computer\'s name')." : </td>";
      echo "<td class='left'><input type='text' name='name' id='group_search_name' value='"
         .$fields['name']."' size='15' /></td>";

      echo "</tr><tr>";

      echo "<td class='left'>".__('Inventory number')." : </td>";
      echo "<td class='left'><input type='text' name='otherserial' id='group_search_otherserial' ".
              "value='".$fields['otherserial']."' size='15' /></td>";

      echo "<td class='left'>".__('Operating system')." : </td>";
      echo "<td>";
      /*$rand_os = mt_rand();
      Dropdown::show('OperatingSystem', array(
         'value'  => $fields['operatingsystems_id'],
         'name'   => 'operatingsystems_id',
         'rand'   => $rand_os
      ));
      echo "<hr />";*/

      self::ajaxDisplaySearchTextForDropdown("operatingsystems_id",
                                             $fields['operatingsystem_name'],
                                             8);

      $params_os = array('searchText'     => '__VALUE__',
                             'myname'     => 'operatingsystems_id',
                             'table'      => 'glpi_operatingsystems',
                             'value'      => $fields['operatingsystems_id']);

      Ajax::updateItemOnInputTextEvent("search_operatingsystems_id",
                                       "operatingsystems_dropdown",
                                       $CFG_GLPI["root_doc"].
                                          "/plugins/fusioninventory/ajax/".
                                          "deploydropdown_operatingsystems.php",
                                       $params_os);

      //load default operatingsystems_dropdown
      Ajax::updateItem("operatingsystems_dropdown",
                       $CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/".
                          "deploydropdown_operatingsystems.php",
                       $params_os, /*false,*/ "search_operatingsystems_id");

      echo "<span id='operatingsystems_dropdown'>";
      echo "<select name='operatingsystems_id' id='operatingsystems_id'><option value='0'>".
              Dropdown::EMPTY_VALUE."</option></select>";
      echo "</span>\n";

      Html::showToolTip("* ".__('for all')."<br />".
      __('If no line in the list is selected, the text fields on the left will be used for search.')
                       );

      echo "</td>";

      echo "</tr><tr>";

      echo "<td class='center' colspan='4'>";

      self::groupAjaxLoad($type);

      echo "</td>";
      echo "</tr>";
   }
/*
   static function groupAjaxLoad($type) {
      global $CFG_GLPI;

      self::ajaxLoad(
         'group_search_submit',
         'group_results',
         $CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/deploygroup_results.php",
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
*/
   /**
    * @see CommonGLPI::getTabNameForItem()
   **/
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate
          && ($item->getType() == 'PluginFusioninventoryDeployGroup') && $item->fields['type'] == PluginFusioninventoryDeployGroup::STATIC_GROUP) {
         return array (__('Search'), __('Associated Items'));
      }
      return '';
   }
   
   /**
    * @param $item         CommonGLPI object
    * @param $tabnum       (default 1)
    * @param $withtemplate (default 0)
   **/
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      switch ($tabnum) {
         case 0:
            PluginFusioninventoryDeployGroup::showSearchForComputers($item, $_REQUEST);
            break;
         case 1:
            self::showResults($item);
            break;
      }

      return true;
   }
   
   static function showResults(PluginFusioninventoryDeployGroup $group) {
   Toolbox::logDebug($_GET);
      $computers_params['criteria'][]   = array('field' => 6000, 'searchtype' => 'equals', 'value' => $group->getID());
      $computers_params['metacriteria'] = array();
      $search_params    = Search::manageParams('PluginFusioninventoryComputer', $computers_params);
      Search::showList('PluginFusioninventoryComputer', $search_params);
   }
}

?>