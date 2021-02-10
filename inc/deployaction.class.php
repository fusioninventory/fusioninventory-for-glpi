<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the actions in package for deploy system.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Alexandre Delaunay
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the actions in package for deploy system.
 */
class PluginFusioninventoryDeployAction extends PluginFusioninventoryDeployPackageItem {

   public $shortname = 'actions';
   public $json_name = 'actions';


   /**
    * Get list of return actions available
    *
    * @return array
    */
   function getReturnActionNames() {
      return [
         0              => Dropdown::EMPTY_VALUE,
         'okCode'       => __("Return code is equal to", 'fusioninventory'),
         'errorCode'    => __("Return code is not equal to", 'fusioninventory'),
         'okPattern'    => __("Command output contains", 'fusioninventory'),
         'errorPattern' => __("Command output does not contains", 'fusioninventory')
      ];
   }


   /**
    * Get types of actions with name => description
    *
    * @return array
    */
   function getTypes() {
       return [
         'cmd'     => __('Command', 'fusioninventory'),
         'move'    => __('Move', 'fusioninventory'),
         'copy'    => __('Copy', 'fusioninventory'),
         'delete'  => __('Delete directory', 'fusioninventory'),
         'mkdir'   => __('Create directory', 'fusioninventory')
       ];
   }


   /**
    * Get description of the type name
    *
    * @param string $type name of the type
    * @return string mapped with the type
    */
   function getLabelForAType($type) {
      $a_types = $this->getTypes();
      if (isset($a_types[$type])) {
         return $a_types[$type];
      }
      return $type;
   }


   /**
    * Display form
    *
    * @param object $package PluginFusioninventoryDeployPackage instance
    * @param array $request_data
    * @param string $rand unique element id used to identify/update an element
    * @param string $mode possible values: init|edit|create
    */
   function displayForm(PluginFusioninventoryDeployPackage $package, $request_data, $rand, $mode) {

      /*
       * Get element config in 'edit' mode
       */
      $config = null;
      if ($mode === self::EDIT && isset($request_data['index'])) {
         /*
          * Add an hidden input about element's index to be updated
          */
         echo "<input type='hidden' name='index' value='".$request_data['index']."' />";

         $element = $package->getSubElement($this->shortname, $request_data['index']);
         if (is_array($element) && count($element) == 1) {
            reset($element);
            $type   = key($element);
            $config = ['type' => $type, 'data' => $element[$type]];
         }
      }

      /*
       * Display start of div form
       */
      if (in_array($mode, [self::INIT], true)) {
         echo "<div id='actions_block$rand' style='display:none'>";
      }

      /*
       * Display element's dropdownType in 'create' or 'edit' mode
       */
      if (in_array($mode, [self::CREATE, self::EDIT], true)) {
         $this->displayDropdownType($package, $config, $rand, $mode);
      }

      /*
       * Display element's values in 'edit' mode only.
       * In 'create' mode, those values are refreshed with dropdownType 'change'
       * javascript event.
       */
      if (in_array($mode, [self::CREATE, self::EDIT], true)) {
         echo "<span id='show_actions_value{$rand}'>";
         if ($mode === self::EDIT) {
            $this->displayAjaxValues($config, $request_data, $rand, $mode);
         }
         echo "</span>";
      }

      /*
       * Close form div
       */
      if (in_array($mode, [self::INIT], true)) {
         echo "</div>";
      }
   }


   /**
    * Display list of actions
    *
    * @global array $CFG_GLPI
    * @param object $package PluginFusioninventoryDeployPackage instance
    * @param array $data array converted of 'json' field in DB where stored actions
    * @param string $rand unique element id used to identify/update an element
    */
   function displayList(PluginFusioninventoryDeployPackage $package, $data, $rand) {
      global $CFG_GLPI;

      $canedit    = $package->canUpdateContent();
      $package_id = $package->getID();
      echo "<table class='tab_cadrehov package_item_list' id='table_action_$rand'>";
      $i=0;
      foreach ($data['jobs'][$this->json_name] as $action) {
         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         if ($canedit) {
            echo "<td class='control'>";
            Html::showCheckbox(['name' => 'actions_entries['.$i.']']);
            echo "</td>";
         }
         $keys = array_keys($action);
         $action_type = array_shift($keys);
         echo "<td>";
         if ($canedit) {
            echo "<a class='edit'
                     onclick=\"edit_subtype('action', $package_id, $rand ,this)\">";
         }
         echo $this->getLabelForAType($action_type);
         if ($canedit) {
            echo "</a>";
         }
         echo "<br />";

         foreach ($action[$action_type] as $key => $value) {
            if (is_array($value)) {
               if ($key === "list") {
                  foreach ($value as $list) {
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
                          "padding-left:2px;white-space: pre-wrap;'>$value</pre>";
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
               $getReturnActionNames = $this->getReturnActionNames();
               echo $getReturnActionNames[$retCheck['type']]." ".array_shift($retCheck['values']);
               echo "</li>";
            }
            echo "</ul>";
         }
         echo "</td>";
         echo "</td>";
         if ($canedit) {
            echo "<td class='rowhandler control' title='".__('drag', 'fusioninventory').
               "'><div class='drag row'></div></td>";
         }
         echo "</tr>";
         $i++;
      }
      if ($canedit) {
         echo "<tr><th>";
         echo Html::getCheckAllAsCheckbox("actionsList$rand", mt_rand());
         echo "</th><th colspan='3' class='mark'></th></tr>";
      }
      echo "</table>";
      if ($canedit) {
         echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt=''>";
         echo "<input type='submit' name='delete' value=\"".
         __('Delete', 'fusioninventory')."\" class='submit'>";
      }
   }


   /**
    * Display different fields relative the action selected (cmd, move...)
    *
    * @param array $config
    * @param array $request_data
    * @param string $mode mode in use (create, edit...)
    * @return boolean
    */
   function displayAjaxValues($config, $request_data, $rand, $mode) {
      global $CFG_GLPI;

      $mandatory_mark  = $this->getMandatoryMark();
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      if (isset($request_data['packages_id'])) {
         $pfDeployPackage->getFromDB($request_data['packages_id']);
      } else {
         $pfDeployPackage->getEmpty();
      }

      /*
       * Get type from request params
       */
      $type = null;

      if ($mode === self::CREATE) {
         $type = $request_data['value'];
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
      $retChecks    = null;
      $name_label   = __('Action label', 'fusioninventory');
      $name_value   = (isset($config_data['name']))?$config_data['name']:"";
      $name_type    = "input";
      $logLineLimit = (isset($config_data['logLineLimit']))?$config_data['logLineLimit']:100;

      /*
       * set values from element's config in 'edit' mode
       */
      switch ($type) {

         case 'move':
         case 'copy':
            $value_label_1 = __("From", 'fusioninventory');
            $name_label_1  = "from";
            $value_label_2 = __("To", 'fusioninventory');
            $name_label_2  = "to";
            if ($mode === self::EDIT) {
               $value_1 = $config_data['from'];
               $value_2 = $config_data['to'];
            }
            break;

         case 'cmd':
            $value_label_1 = __("exec", 'fusioninventory');
            $name_label_1  = "exec";
            $value_label_2 = false;
            $value_type_1  = "textarea";
            if ($mode === self::EDIT) {
               $value_1 = $config_data['exec'];
               if (isset($config_data['retChecks'])) {
                  $retChecks = $config_data['retChecks'];
               }
            }
            break;

         case 'delete':
         case 'mkdir':
            $value_label_1 = __("path", 'fusioninventory');
            $name_label_1  = "list[]";
            $value_label_2 = false;
            if ($mode === self::EDIT) {
               /*
                * TODO : Add list input like `retChecks` on `mkdir` and `delete`
                * because those methods are defined as list in specification
                */
               $value_1 = array_shift($config_data['list']);
            }
            break;

         default:
            return false;

      }

      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>".__('Action label', 'fusioninventory')."</th>";
      echo "<td><input type='text' name='name' id='check_name' value=\"{$name_value}\" /></td>";
      echo "</tr>";
      echo "<tr>";
      echo "<th>$value_label_1&nbsp;".$mandatory_mark."</th>";
      echo "<td>";
      switch ($value_type_1) {

         case "input":
            echo "<input type='text' name='$name_label_1' value='$value_1' />";
            break;

         case "textarea":
            echo "<textarea name='$name_label_1' rows='3' style='width: 760px;'>$value_1</textarea>";
            break;

      }
      echo "</td>";
      echo "</tr>";
      if ($value_label_2 !== false) {
         echo "<tr>";
         echo "<th>".$value_label_2."&nbsp;".$mandatory_mark."</th>";
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
         echo "<span id='retchecks' style='display:block'>";

         if (is_array($retChecks)
                 && count($retChecks)) {
            foreach ($retChecks as $retcheck) {
               echo "<table class='table_retchecks'>";
               echo "<tr>";
               echo "<td>";
               Dropdown::showFromArray('retchecks_type[]', self::getReturnActionNames(),
                                       [ 'value' => $retcheck['type'],
                                         'width' => '200px'
                                       ]
               );
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
         Dropdown::showFromArray('retchecks_type[]', $this->getReturnActionNames(),
                                 ['width' => '200px']);
         echo "</td>";
         echo "<td><input type='text' name='retchecks_value[]' /></td>";
         echo "<td><a class='edit' onclick='removeLine(this)'><img src='".
               $CFG_GLPI["root_doc"]."/pics/delete.png' /></a></td>";
         echo "</tr>";

         echo "</span>";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
      }

      if ($type == 'cmd') {
         echo "<tr>";
         echo "<th>".__('Number of output lines to retrieve', 'fusioninventory')."</th>";
         echo "<td>";
         $options = ['min'   => 0,
                     'max'   => 5000,
                     'step'  => 10,
                     'toadd' => [0 => __('None'), -1 => __('All')],
                     'value' => (isset($config_data['logLineLimit']))?$config_data['logLineLimit']:10
                    ];
         Dropdown::showNumber('logLineLimit', $options);
         echo "&nbsp;<span class='red'><i>";
         echo __('Fusioninventory-Agent 2.3.20 or higher mandatory');
         echo "</i></span></td>";
         echo "</tr>";
      }

      $this->addOrSaveButton($pfDeployPackage, $mode);

      echo "<script type='text/javascript'>
         function removeLine(item) {
            var tag_table = item.parentNode.parentNode.parentNode.parentNode;
            var parent = tag_table.parentNode;
               parent.removeChild(tag_table);
         }
      </script>";
   }


   /**
    * Add a new item in actions of the package
    *
    * @param array $params list of fields with value of the action
    */
   function add_item($params) {
      //prepare new action entry to insert in json
      $fields = ['list', 'from', 'to', 'exec', 'name', 'logLineLimit'];
      foreach ($fields as $field) {
         if (isset($params[$field])) {
            $tmp[$field] = $params[$field];
         }
      }

      //process ret checks
      if (isset($params['retchecks_type'])
              && !empty($params['retchecks_type'])) {
         foreach ($params['retchecks_type'] as $index => $type) {
            if ($type !== '0') {
               $tmp['retChecks'][] = [
                  'type'  => $type,
                  'values' => [$params['retchecks_value'][$index]]
               ];
            }
         }
      }

      //append prepared data to new entry
      $new_entry[$params['actionstype']] = $tmp;

      //get current order json
      $data = json_decode($this->getJson($params['id']), true);

      //add new entry
      $data['jobs'][$this->json_name][] = $new_entry;

      //update order
      $this->updateOrderJson($params['id'], $data);
   }


   /**
    * Save the item in actions
    *
    * @param array $params list of fields with value of the action
    */
   function save_item($params) {
      $tmp    = [];
      $fields = ['list', 'from', 'to', 'exec', 'name', 'logLineLimit'];
      foreach ($fields as $field) {
         if (isset($params[$field])) {
            $tmp[$field] = $params[$field];
         }
      }

      //process ret checks
      if (isset($params['retchecks_type']) && !empty($params['retchecks_type'])) {
         foreach ($params['retchecks_type'] as $index => $type) {
            //if type == '0', this means nothing is selected
            if ($type !== '0') {
               $tmp['retChecks'][] = [
                  'type'  => $type,
                  'values' => [$params['retchecks_value'][$index]]
               ];
            }
         }
      }

      //append prepared data to new entry
      $entry[$params['actionstype']] = $tmp;

      //update order
      $this->updateOrderJson($params['id'],
                             $this->prepareDataToSave($params, $entry));
   }
}
