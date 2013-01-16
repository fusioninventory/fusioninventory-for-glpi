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

class PluginFusioninventoryDeployAction extends CommonDBTM {

   static function getTypeName($nb=0) {
      return __('Actions');
   }

   static function canCreate() {
      return true;
   }

   static function canView() {
      return true;
   }

   static function getTypes() {
       return array(
         'cmd'     => __('cmd'),
         'move'    => __('move'),
         'copy'    => __('copy'),
         'delete'  => __('delete'),
         'mkdir'   => __('mkdir')
      );
   }

   static function displayForm($orders_id, $datas, $rand) {
      global $CFG_GLPI;
      
      echo "<div style='display:none' id='actions_block$rand'>";

      echo "<span id='showActionType$rand'>&nbsp;</span>";
      echo "<script type='text/javascript'>";
      $params = array(
         'rand'    => $rand,
         'subtype' => "action"
      );
      Ajax::UpdateItemJsCode("showActionType$rand",
                             $CFG_GLPI["root_doc"].
                             "/plugins/fusioninventory/ajax/deploydropdown_packagesubtypes.php",
                             $params,
                             "dropdown_deploy_actiontype");
      echo "</script>";


      echo "<span id='showActionValue$rand'>&nbsp;</span>";
      
      echo "<hr>";
      echo "</div>";
      Html::closeForm();

      //display stored actions datas
      if (!isset($datas['jobs']['actions']) || empty($datas['jobs']['actions'])) return;
      echo "<form name='removeactions' method='post' action='deploypackage.form.php?remove_item'>";
      echo "<input type='hidden' name='itemtype' value='PluginFusioninventoryDeployAction' />";
      echo "<input type='hidden' name='orders_id' value='$orders_id' />";
      echo "<div id='drag_actions'>";
      echo "<table class='tab_cadrehov package_item_list' id='table_action'>";
      $i=0;
      foreach ($datas['jobs']['actions'] as $action) {
         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         echo "<td class='control'><input type='checkbox' name='action_entries[]' value='$i' /></td>";
         $keys = array_keys($action);
         $action_type = array_shift($keys);
         echo "<td>$action_type</td>";
         foreach ($action[$action_type] as $key => $value) {
            echo "<td>";
            if (is_array($value) ) {
               if ($key === "list") {
                  foreach($value as $list) {
                     echo $list;
                     echo "</td><td>";
                  }
               } 
            } else echo "$key : $value";
            echo "</td>";
         }
         echo "<td class='rowhandler control'><div class='drag row'></div></td>";
         echo "</tr>";
         $i++;
      }
      echo "<tr><td colspan='2'>";
      echo "<input type='submit' name='delete' value=\"".
         __('Delete', 'fusioninventory')."\" class='submit'>";
      echo "</td></tr>";
      echo "</table></div>";
      Html::closeForm();
   }

   static function dropdownType($rand) {
      global $CFG_GLPI;

      $actions_types = self::getTypes();
      array_unshift($actions_types, "---");

      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>".__("Type")."</th>";
      echo "<td>";
      Dropdown::showFromArray("deploy_actiontype", $actions_types, array('rand' => $rand));
      echo "</td>";
      echo "</tr></table>";

      //ajax update of action value span
       $params = array(
                      'value'  => '__VALUE__',
                      'rand'   => $rand,
                      'myname' => 'method',
                      'type'   => "action");
      Ajax::updateItemOnEvent("dropdown_deploy_actiontype".$rand,
                              "showActionValue$rand",
                              $CFG_GLPI["root_doc"].
                              "/plugins/fusioninventory/ajax/deploy_displaytypevalue.php",
                              $params,
                              array("change", "load"));

   }

   static function displayAjaxValue($type, $rand) {

      $value_type_1 = "input";

      switch ($type) {
         case 'move':
         case 'copy':
            $value_label_1 = __("From");
            $name_label_1 = "from";
            $value_label_2 = __("To");
            $name_label_2 = "to";
            break;
         case 'cmd':
            $value_label_1 = __("exec");
            $name_label_1 = "exec";
            $value_label_2 = false;
            $value_type_1  = "textarea";
            break;
         case 'delete':
         case 'mkdir':
            $value_label_1 = __("path");
            $name_label_1 = "list[]";
            $value_label_2 = false;
            break;
      }

      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>$value_label_1</th>";
      echo "<td>";
      switch ($value_type_1) {
         case "input":
            echo "<input type='text' name='$name_label_1' />";
            break;
         case "textarea":
            echo "<textarea name='$name_label_1' rows='3'></textarea>";
            break;
      }
      echo "</td>";
      echo "</tr>";
      if ($value_label_2 !== false) {
         echo "<tr>";
         echo "<th>$value_label_2</th>";
         echo "<td><input type='text' name='$name_label_2'</td>";
         echo "</tr>";
      }

      //specific case for cmd : add retcheck form
      if ($type == "cmd") {
         echo "<tr>";
         echo "<th>".__("return code");
         PluginFusioninventoryDeployPackage::plusButton("retchecks$rand", "table");
         echo "</th>";
         echo "<td>";
         echo "<span id='retchecks$rand' style='display:none'>";
         $retchecks_entries = array(
            '--',
            'okCode'       => __("okCode"),
            'errorCode'    => __("errorCode"),
            'okPattern'    => __("okPattern"),
            'errorPattern' => __("errorPattern")
         );
         echo "<table class='table_retchecks'>";
         echo "<tr>";
         echo "<td>";
         Dropdown::showFromArray('retchecks_type[]', $retchecks_entries);
         echo "</td>";
         echo "<td>=</td><td><input type='text' name='retchecks_value[]' /></td>";
         echo "</tr>";
         echo "</table>";
         echo "</span>";
         echo "</td>";
         echo "</tr>";
      }

      echo "<tr>";
      echo "<td></td><td>";
      echo "&nbsp;<input type='submit' name='itemaddaction' value=\"".
         __('Add')."\" class='submit' >";
      echo "</td>";
      echo "</tr></table>";
   }

   static function add_item($params) {
      //prepare new action entry to insert in json
      if (isset($params['list'])) $tmp['list'] = $params['list'];
      if (isset($params['from'])) $tmp['from'] = $params['from'];
      if (isset($params['to']))   $tmp['to']   = $params['to'];
      if (isset($params['exec'])) $tmp['exec'] = $params['exec'];

      //process ret checks
      if (isset($params['retchecks_type']) && !empty($params['retchecks_type'])) {
         foreach ($params['retchecks_type'] as $index => $type) {
            $tmp['retChecks'][] = array(
               'type' => $type,
               'value' => array($params['retchecks_value'][$index])
            );
         }
      }

      //append prepared datas to new entry
      $new_entry[ $params['deploy_actiontype']] = $tmp;

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), true);

      //add new entry
      $datas['jobs']['actions'][] = $new_entry;

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }

   static function remove_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), true);

      //remove selected checks
      foreach ($params['action_entries'] as $index) {
         unset($datas['jobs']['actions'][$index]);
      }

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }

   static function move_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), true);

      //get data on old index
      $moved_check = $datas['jobs']['actions'][$params['old_index']];

      //remove this old index in json
      unset($datas['jobs']['actions'][$params['old_index']]);

      //insert it in new index (array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($datas['jobs']['actions'], $params['new_index'], 0, array($moved_check));

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }

   static function getForOrder($orders_id) {
      $action = new self;
      $results = $action->find("`plugin_fusioninventory_deployorders_id`='$orders_id'", 
                               "ranking ASC");
      $actions = array();

      foreach ($results as $result) {
         $tmp = call_user_func(
            array(
               $result['itemtype'],
               'getActions'
            ),
            $result['items_id'],
            $result['id']
         );

         if (!empty($tmp)) $actions[] = $tmp;
      }
      return $actions;
   }
}

?>
