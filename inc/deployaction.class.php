<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
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
         0 => Dropdown::EMPTY_VALUE,
         'okCode'       => __("Return code is equal to", 'fusioninventory'),
         'errorCode'    => __("Return code is not equal to", 'fusioninventory'),
         'okPattern'    => __("Command output contains", 'fusioninventory'),
         'errorPattern' => __("Command output does not contains", 'fusioninventory')
      );
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



   static function displayForm($order, $request_data, $rand, $mode) {
      global $CFG_GLPI;

      /*
       * Get element config in 'edit' mode
       */
      $config = NULL;
      if ( $mode === 'edit' && isset( $request_data['index'] ) ) {
         /*
          * Add an hidden input about element's index to be updated
          */
         echo "<input type='hidden' name='index' value='".$request_data['index']."' />";

         $c = $order->getSubElement( 'actions', $request_data['index'] );
         if ( is_array( $c ) && count( $c ) == 1 ) {
            reset( $c );
            $t = key( $c );

            $config = array(
               'type' => $t,
               'data' => $c[$t]
            );
         }
      }

      /*
       * Display start of div form
       */
      if ( in_array( $mode, array('init'), TRUE ) ) {
         echo "<div id='actions_block$rand' style='display:none'>";
      }

      /*
       * Display element's dropdownType in 'create' or 'edit' mode
       */
      if ( in_array( $mode, array('create', 'edit'), TRUE ) ) {
         self::displayDropdownType($config,$request_data, $rand, $mode);
      }

      /*
       * Display element's values in 'edit' mode only.
       * In 'create' mode, those values are refreshed with dropdownType 'change'
       * javascript event.
       */
      if ( in_array( $mode, array('create', 'edit'), TRUE ) ) {
         echo "<span id='show_action_value{$rand}'>";
         if ( $mode === 'edit' ) {
            self::displayAjaxValues( $config, $request_data, $rand, $mode );
         }
         echo "</span>";
      }


      /*
       * Close form div
       */
      if ( in_array( $mode, array('init'), TRUE ) ) {
         echo "</div>";
      }
   }



   static function displayList(PluginFusioninventoryDeployOrder $order, $datas, $rand) {
      global $CFG_GLPI;

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage->getFromDB($order->fields['plugin_fusioninventory_deploypackages_id']);

      echo "<table class='tab_cadrehov package_item_list' id='table_action_$rand'>";
      $i=0;
      foreach ($datas['jobs']['actions'] as $action) {
         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         if ($pfDeployPackage->can($pfDeployPackage->getID(), UPDATE)) {
            echo "<td class='control'>";
            Html::showCheckbox(array('name' => 'action_entries[]'));
            echo "</td>";
         }
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
         if ($pfDeployPackage->can($pfDeployPackage->getID(), UPDATE)) {
            echo "<td class='rowhandler control' title='".__('drag', 'fusioninventory').
               "'><div class='drag row'></div></td>";
         }
         echo "</tr>";
         $i++;
      }
         if ($pfDeployPackage->can($pfDeployPackage->getID(), UPDATE)) {
         echo "<tr><th>";
         Html::checkAllAsCheckbox("actionsList$rand", mt_rand());
         echo "</th><th colspan='3' class='mark'></th></tr>";
      }
      echo "</table>";
         if ($pfDeployPackage->can($pfDeployPackage->getID(), UPDATE)) {
         echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt=''>";
         echo "<input type='submit' name='delete' value=\"".
            __('Delete', 'fusioninventory')."\" class='submit'>";
      }
   }



   /* display the dropdown to select type of element
    * @param config order item configuration
    * @param request_data data from http request
    * @param rand random value used in forms
    * @param mode display mode in use
    * @return nothing
    */

   static function displayDropdownType($config, $request_data, $rand, $mode) {
      global $CFG_GLPI;
      /*
       * Build dropdown options
       */
      $dropdown_options['rand'] = $rand;
      if ($mode === 'edit') {
         $dropdown_options['value'] = $config['type'];
         $dropdown_options['readonly'] = true;
      }

      /*
       * Build actions types list
       */
      $actions_types = self::getTypes();
      array_unshift($actions_types, "---");

      /*
       * Display dropdown html
       */
      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>".__("Type", 'fusioninventory')."</th>";
      echo "<td>";
      Dropdown::showFromArray("deploy_actiontype", $actions_types, $dropdown_options);
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      //ajax update of action value span

      if ( $mode === 'create' ) {
         $params = array(
            'values'  => '__VALUE__',
            'rand'   => $rand,
            'myname' => 'method',
            'type'   => 'action',
            'mode'   => $mode
         );

         Ajax::updateItemOnEvent(
            "dropdown_deploy_actiontype$rand",
            "show_action_value$rand",
            $CFG_GLPI["root_doc"].
            "/plugins/fusioninventory/".
            "ajax/deploy_displaytypevalue.php",
            $params,
            array("change", "load")
         );
      }
   }



   static function displayAjaxValues($config, $request_data, $rand, $mode) {
      global $CFG_GLPI;

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployOrder = new PluginFusioninventoryDeployOrder();

      if (isset($request_data['orders_id'])) {
         $pfDeployOrder->getFromDB($request_data['orders_id']);
         $pfDeployPackage->getFromDB($pfDeployOrder->fields['plugin_fusioninventory_deploypackages_id']);
      } else {
         $pfDeployPackage->getEmpty();
      }

      /*
       * Get type from request params
       */
      $type = NULL;

      if ( $mode === 'create' ) {
         $type = $request_data['values'];
      } else {
         $type = $config['type'];
         $config_data = $config['data'];
      }

      /*
       * Set default values
       */
      $value_type_1 = "input";
      $value_1      = "";
      $value_2      = "";
      $retChecks    = NULL;


      /*
       * set values from element's config in 'edit' mode
       */
      switch ( $type ) {
         case 'move':
         case 'copy':
            $value_label_1 = __("From", 'fusioninventory');
            $name_label_1 = "from";
            $value_label_2 = __("To", 'fusioninventory');
            $name_label_2 = "to";
            if ( $mode === 'edit' ) {
               $value_1 = $config_data['from'];
               $value_2 = $config_data['to'];
            }
            break;
         case 'cmd':
            $value_label_1 = __("exec", 'fusioninventory');
            $name_label_1 = "exec";
            $value_label_2 = FALSE;
            $value_type_1  = "textarea";
            if ( $mode === 'edit' ) {
               $value_1 = $config_data['exec'];
               if ( isset( $config_data['retChecks'] ) ) {
                  $retChecks = $config_data['retChecks'];
               }
            }
            break;
         case 'delete':
         case 'mkdir':
            $value_label_1 = __("path", 'fusioninventory');
            $name_label_1 = "list[]";
            $value_label_2 = FALSE;
            if ( $mode === 'edit' ) {
               /*
                * TODO : Add list input like `retChecks` on `mkdir` and `delete`
                * because those methods are defined as list in specification
                */
               $value_1 = array_shift($config_data['list']);
            }
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
         PluginFusioninventoryDeployPackage::plusButton("retchecks", ".table_retchecks.template");
         echo "</th>";
         echo "<td>";
         $display = "style='display:none'";
         if ($retChecks) {
            $display = "style='display:block'";
         }
         echo "<span id='retchecks' style='display:block'>";



         if (  is_array( $retChecks )
            && count( $retChecks )
         ) {
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
               echo "<td><a class='edit' onclick='removeLine(this)'><img src='".
                  $CFG_GLPI["root_doc"]."/pics/delete.png' /></a></td>";
               echo "</tr>";
               echo "</table>";
            }
         }
         echo "<table class='table_retchecks template' style='display:none'>";
         echo "<tr>";
         echo "<td>";
         //Toolbox::logDebug(self::retchecks_entries());
         Dropdown::showFromArray('retchecks_type[]', self::retchecks_entries(), array());
         echo "</td>";
         echo "<td><input type='text' name='retchecks_value[]' /></td>";
         echo "<td><a class='edit' onclick='removeLine(this)'><img src='".
               $CFG_GLPI["root_doc"]."/pics/delete.png' /></a></td>";
         echo "</tr>";
         echo "</table>";
         echo "</span>";
         echo "</td>";
         echo "</tr>";
      }

      echo "<tr>";
      echo "<td></td><td>";
      if ($pfDeployPackage->can($pfDeployPackage->getID(), UPDATE)) {
         if ( $mode === 'edit' ) {
            echo "<input type='submit' name='save_item' value=\"".
               _sx('button', 'Save')."\" class='submit' >";
         } else {
            echo "<input type='submit' name='add_item' value=\"".
               _sx('button', 'Add')."\" class='submit' >";
         }
      }
      echo "</td>";
      echo "</tr></table>";

      echo "<script type='text/javascript'>
         function removeLine(item) {
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
      $data = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //add new entry
      $data['jobs']['actions'][] = $new_entry;

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $data);
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
      $data = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //unset index
      unset($data['jobs']['actions'][$params['index']]);

      //add new datas at index position
      //(array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($data['jobs']['actions'], $params['index'], 0, array($entry));

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $data);
   }



   static function remove_item($params) {
      if (!isset($params['action_entries'])) {
         return FALSE;
      }

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //remove selected checks
      foreach ($params['action_entries'] as $index => $checked) {
         if ($checked >= "1" || $checked == "on") {
            unset($datas['jobs']['actions'][$index]);
         }
      }

      //Ensure actions list is an array and not a dictionnary
      //Note: This happens when removing an array element from the begining
      $datas['jobs']['actions'] = array_values($datas['jobs']['actions']);

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
