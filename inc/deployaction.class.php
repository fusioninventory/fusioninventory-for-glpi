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

class PluginFusioninventoryDeployAction {

   static function retchecks_entries() {
      return array(
         '--',
         'okCode'       => __("Return code is equal to", 'fusioninventory'),
         'errorCode'    => __("Return code is not equal to", 'fusioninventory'),
         'okPattern'    => __("Command output contains", 'fusioninventory'),
         'errorPattern' => __("Command output does not contains", 'fusioninventory')
      );
   }

   static function canCreate() {
      return TRUE;
   }

   static function canView() {
      return TRUE;
   }



   static function getTypes() {
       return array(
         'cmd'     => __('Command', 'fusioninventory'),
         'move'    => __('Move', 'fusioninventory'),
         'copy'    => __('Copy', 'fusioninventory'),
         'delete'  => __('Delete', 'fusioninventory'),
         'mkdir'   => __('Make directory', 'fusioninventory')
      );
   }
   
   
   
   static function getType($type) {
      $a_types = PluginFusioninventoryDeployAction::getTypes();
      if (isset($a_types[$type])) {
         return $a_types[$type];
      }
      return $type;
   }



   static function displayForm($order, $datas, $rand) {
      global $CFG_GLPI;

      if (!isset($datas['index'])) {
         echo "<div style='display:none' id='actions_block$rand'>";
      } else {
         //== edit selected data ==

         //get current order json
         $datas_o = json_decode($order->fields['json'], TRUE);

         //get data on index
         $action = $datas_o['jobs']['actions'][$datas['index']];
         $tmp = array_keys($action);
         $type = array_shift($tmp);
         $action_values = $action[$type];
      }

      echo "<span id='show_action_type$rand'>&nbsp;</span>";
      echo "<script type='text/javascript'>";
      $params = array(
         'rand'    => $rand,
         'subtype' => "action"
      );
      if (isset($datas['index'])) {
         $params['edit']    = "true";
         $params['type']    = $type;
         $params['index']   = $datas['index'];
         if (isset($action_values['from'])) {
            $params['value_1'] = urlencode(addslashes($action_values['from']));
         }
         if (isset($action_values['exec'])) {
            $params['value_1'] = urlencode(addslashes($action_values['exec']));
         }
         if (isset($action_values['list'])) {
            $tmp = array_values($action_values['list']);
            $params['value_1'] = urlencode(addslashes($tmp[0]));
         }
         if (isset($action_values['to'])) {
            $params['value_2'] = urlencode(addslashes($action_values['to']));
         }
         if (isset($action_values['retChecks']))  {
            $params['retChecks'] = json_encode($action_values['retChecks']);
         }

      }


      Ajax::updateItemJsCode("show_action_type$rand",
                             $CFG_GLPI["root_doc"].
                             "/plugins/fusioninventory/ajax/deploydropdown_packagesubtypes.php",
                             $params,
                             "dropdown_deploy_actiontype");
      echo "</script>";

      //Html::printCleanArray($params);

      echo "<span id='show_action_value$rand'>&nbsp;</span>";

      echo "<hr>";

      if (!isset($datas['index'])) {
         echo "</div>";
      } else {
         return TRUE;
      }
   }

   
   
   static function displayList($order, $datas, $rand) {
      global $CFG_GLPI;

      echo "<table class='tab_cadrehov package_item_list' id='table_action_$rand'>";
      $i=0;
      foreach ($datas['jobs']['actions'] as $action) {
         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         echo "<td class='control'>";
         echo "<input type='checkbox' name='action_entries[]' value='$i' />";
         echo "</td>";
         $keys = array_keys($action);
         $action_type = array_shift($keys);
         echo "<td>";
         echo "<a class='edit' ".
                 "onclick=\"edit_subtype('action', {$order->fields['id']}, $rand, this)\">";
         echo PluginFusioninventoryDeployAction::getType($action_type);
         echo "</a><br />";

         foreach ($action[$action_type] as $key => $value) {
            if (is_array($value) ) {
               if ($key === "list") {
                  foreach($value as $list) {
                     echo $list;
                     echo " ";
                  }
               }
            } else {
               echo "<b>";
               if ($key == 'exec') {
                  echo __('Command to execute', 'fusioninventory');
               } else {
                  echo $key;
               }
               echo "</b>";
               if ($key ==="exec") {
                  echo "<pre style='border-left:solid lightgrey 3px;margin-left: 5px;".
                          "padding-left:2px'>$value</pre>";
               } else {
                  echo " $value ";
               }
            }
         }
         if (isset($action[$action_type]['retChecks'])) {
            echo "<br><b>".__("return codes saved for this command", 'fusioninventory').
               "</b> : <ul class='retChecks'>";
            foreach ($action[$action_type]['retChecks'] as $retCheck) {
               echo "<li>";
               $retchecks_entries = self::retchecks_entries();
               echo $retchecks_entries[$retCheck['type']]." ".array_shift($retCheck['values']);
               echo "</li>";
            }
            echo "</ul>";
         }
         echo "</td>";
         echo "</td>";
         echo "<td class='rowhandler control' title='".__('drag', 'fusioninventory').
            "'><div class='drag row'></div></td>";
         echo "</tr>";
         $i++;
      }
      echo "<tr><th>";
      Html::checkAllAsCheckbox("actionsList$rand", mt_rand());
      echo "</th><th colspan='3' class='mark'></th></tr>";
      echo "</table>";
      echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt=''>";
      echo "<input type='submit' name='delete' value=\"".
         __('Delete', 'fusioninventory')."\" class='submit'>";
   }
   
   

   static function dropdownType($datas) {
      global $CFG_GLPI;

      $rand = $datas['rand'];

      $actions_types = self::getTypes();
      array_unshift($actions_types, "---");
      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>".__("Type", 'fusioninventory')."</th>";
      echo "<td>";
      $options['rand'] = $datas['rand'];
      if (isset($datas['edit'])) {
         $options['value'] = $datas['type'];
      }
      Dropdown::showFromArray("deploy_actiontype", $actions_types, $options);
      echo "</td>";
      echo "</tr></table>";

      //ajax update of action value span
      $params = array('values'  => '__VALUE__',
                      'rand'   => $rand,
                      'myname' => 'method',
                      'type'   => "action");
      if (isset($datas['edit'])) {
         $params['edit']      = "true";
         $params['index']     = $datas['index'];
         $params['value_1']   = urlencode($datas['value_1']);
         if (isset($datas['value_2'])) {
            $params['value_2']   = addslashes($datas['value_2']);
         }
         if (isset($datas['retChecks'])) {
            $params['retChecks'] = $datas['retChecks'];
         }
      }
      Ajax::updateItemOnEvent("dropdown_deploy_actiontype$rand",
                              "show_action_value$rand",
                              $CFG_GLPI["root_doc"].
                              "/plugins/fusioninventory/ajax/deploy_displaytypevalue.php",
                              $params,
                              array("change", "load"));

      if (isset($datas['edit'])) {
         echo "<script type='text/javascript'>";
         Ajax::updateItemJsCode("show_action_value$rand",
                                $CFG_GLPI["root_doc"].
                                 "/plugins/fusioninventory/ajax/deploy_displaytypevalue.php",
                                $params,
                                "dropdown_deploy_actiontype$rand");
         echo "</script>";
      }
   }



   static function displayAjaxValue($datas) {
      global $CFG_GLPI;

      $type         = $datas['values'];
      $rand         = $datas['rand'];

      $value_type_1 = "input";
      $value_1      = isset($datas['value_1'])?$datas['value_1']:"";
      $value_2      = isset($datas['value_2'])?$datas['value_2']:"";
      $retChecks    = isset($datas['retChecks'])?json_decode($datas['retChecks'], TRUE):"";

      switch ($type) {
         case 'move':
         case 'copy':
            $value_label_1 = __("From", 'fusioninventory');
            $name_label_1 = "from";
            $value_label_2 = __("To", 'fusioninventory');
            $name_label_2 = "to";
            $value_1 = stripslashes($value_1);
            $value_2 = stripslashes($value_2);
            break;
         case 'cmd':
            $value_label_1 = __("exec", 'fusioninventory');
            $name_label_1 = "exec";
            $value_label_2 = FALSE;
            $value_type_1  = "textarea";
            $value_1 = stripslashes($value_1);
            break;
         case 'delete':
         case 'mkdir':
            $value_label_1 = __("path", 'fusioninventory');
            $name_label_1 = "list[]";
            $value_label_2 = FALSE;
            $value_1 = stripslashes($value_1);
            break;
         default:
            return FALSE;
      }

      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>$value_label_1</th>";
      echo "<td>";
      switch ($value_type_1) {
         case "input":
            echo "<input type='text' name='$name_label_1' value='$value_1' />";
            break;
         case "textarea":
            echo "<textarea name='$name_label_1' rows='3'>$value_1</textarea>";
            break;
      }
      echo "</td>";
      echo "</tr>";
      if ($value_label_2 !== FALSE) {
         echo "<tr>";
         echo "<th>$value_label_2</th>";
         echo "<td><input type='text' name='$name_label_2' value='$value_2'/></td>";
         echo "</tr>";
      }

      //specific case for cmd : add retcheck form
      if ($type == "cmd") {
         echo "<tr>";
         echo "<th>".__("Execution checks", 'fusioninventory');
         PluginFusioninventoryDeployPackage::plusButton("retchecks$rand", "table");
         echo "</th>";
         echo "<td>";
         $display = "style='display:none'";
         if (isset($datas['retChecks'])) {
            $display = "style='display:block'";
         }
         echo "<span id='retchecks$rand' style='display:block'>";


         echo "<table class='table_retchecks' style='display:none'>";
         echo "<tr>";
         echo "<td>";
         Dropdown::showFromArray('retchecks_type[]', self::retchecks_entries());
         echo "</td>";
         echo "<td><input type='text' name='retchecks_value[]' /></td>";
         echo "<td><a class='edit' onclick='removeLine$rand(this)'><img src='".
               $CFG_GLPI["root_doc"]."/pics/delete.png' /></a></td>";
         echo "</tr>";
         echo "</table>";

         if (isset($datas['retChecks'])) {
            foreach ($retChecks as $retcheck) {
               echo "<table class='table_retchecks'>";
               echo "<tr>";
               echo "<td>";
               Dropdown::showFromArray('retchecks_type[]', self::retchecks_entries(), array(
                  'value' => $retcheck['type']
               ));
               echo "</td>";
               echo "<td>";
               echo "<input type='text' name='retchecks_value[]' value='".
                  $retcheck['values'][0]."' />";
               echo "</td>";
               echo "<td><a class='edit' onclick='removeLine$rand(this)'><img src='".
                  $CFG_GLPI["root_doc"]."/pics/delete.png' /></a></td>";
               echo "</tr>";
               echo "</table>";
            }
         }
         echo "</span>";
         echo "</td>";
         echo "</tr>";
      }

      echo "<tr>";
      echo "<td></td><td>";
      if (isset($datas['edit'])) {
         echo "<input type='hidden' name='index' value='".$datas['index']."' />";
         echo "<input type='submit' name='save_item' value=\"".
            _sx('button', 'Save')."\" class='submit' >";
      } else {
         echo "<input type='submit' name='add_item' value=\"".
            _sx('button', 'Add')."\" class='submit' >";
      }
      echo "</td>";
      echo "</tr></table>";

      echo "<script type='text/javascript'>
         function removeLine$rand(item) {
            var tag_table = item.parentNode.parentNode.parentNode.parentNode;
            var parent = tag_table.parentNode;
               parent.removeChild(tag_table);
         }
      </script>";
   }



   static function add_item($params) {
      //prepare new action entry to insert in json
      if (isset($params['list'])) {
         $tmp['list'] = $params['list'];
      }
      if (isset($params['from'])) {
         $tmp['from'] = $params['from'];
      }
      if (isset($params['to'])) {
         $tmp['to']   = $params['to'];
      }
      if (isset($params['exec'])) {
         $tmp['exec'] = $params['exec'];
      }

      //process ret checks
      if (isset($params['retchecks_type']) && !empty($params['retchecks_type'])) {
         foreach ($params['retchecks_type'] as $index => $type) {
            if ($type !== '0') {
               $tmp['retChecks'][] = array(
                  'type' => $type,
                  'values' => array($params['retchecks_value'][$index])
               );
            }
         }
      }

      //append prepared datas to new entry
      $new_entry[ $params['deploy_actiontype']] = $tmp;

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //add new entry
      $datas['jobs']['actions'][] = $new_entry;

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }



   static function save_item($params) {
      //prepare updated action entry to insert in json
      if (isset($params['list'])) {
         $tmp['list'] = $params['list'];
      }
      if (isset($params['from'])) {
         $tmp['from'] = $params['from'];
      }
      if (isset($params['to'])) {
         $tmp['to']   = $params['to'];
      }
      if (isset($params['exec'])) {
         $tmp['exec'] = $params['exec'];
      }

      //process ret checks
      if (isset($params['retchecks_type']) && !empty($params['retchecks_type'])) {
         foreach ($params['retchecks_type'] as $index => $type) {
            //if type == '0', this means nothing is selected
            if ($type !== '0') {
               $tmp['retChecks'][] = array(
                  'type' => $type,
                  'values' => array($params['retchecks_value'][$index])
               );
            }
         }
      }

      //append prepared datas to new entry
      $entry[ $params['deploy_actiontype']] = $tmp;

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //unset index
      unset($datas['jobs']['actions'][$params['index']]);

      //add new datas at index position
      //(array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($datas['jobs']['actions'], $params['index'], 0, array($entry));

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }



   static function remove_item($params) {
      if (!isset($params['action_entries'])) {
         return FALSE;
      }

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //remove selected checks
      foreach ($params['action_entries'] as $index) {
         unset($datas['jobs']['actions'][$index]);
      }

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }



   static function move_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //get data on old index
      $moved_check = $datas['jobs']['actions'][$params['old_index']];

      //remove this old index in json
      unset($datas['jobs']['actions'][$params['old_index']]);

      //insert it in new index (array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($datas['jobs']['actions'], $params['new_index'], 0, array($moved_check));

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }
}

?>
