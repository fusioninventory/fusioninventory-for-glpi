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
 * This file is used to manage the checks before deploy a package.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
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
 * Manage the checks before deploy a package.
 */
class PluginFusioninventoryDeployCheck {

   /**
    * Get types of checks with name => description
    *
    * @return array
    */
   static function getTypes() {
      return array(
         'winkeyExists'     => __("Registry key exists", 'fusioninventory'),
         'winkeyMissing'    => __("Registry key missing", 'fusioninventory'),
         'winkeyEquals'     => __("Registry key value equals to", 'fusioninventory'),
         'fileExists'       => __("File exists", 'fusioninventory'),
         'fileMissing'      => __("File is missing", 'fusioninventory'),
         'fileSizeGreater'  => __("File size is greater than", 'fusioninventory'),
         'fileSizeEquals'   => __("File size is equal to", 'fusioninventory'),
         'fileSizeLower'    => __("File size is lower than", 'fusioninventory'),
         'fileSHA512'       => __("SHA-512 hash value is", 'fusioninventory'),
         'freespaceGreater' => __("Free space is greater than", 'fusioninventory')
      );
   }



   /**
    * Get Unit name
    *
    * @return array
    */
   static function getUnitLabel() {
      return array(
         "B"  => __("B", 'fusioninventory'),
         "KB" => __("KiB", 'fusioninventory'),
         "MB" => __("MiB", 'fusioninventory'),
         "GB" => __("GiB", 'fusioninventory')
      );
   }



   /**
    * Get the number to multiply to have in B relative to the unit
    *
    * @param string $unit the unit of number
    * @return integer the number to multiply
    */
   static function getUnitSize($unit) {
      $units = array(
         "B"  => 1,
         "KB" => 1024,
         "MB" => 1024 * 1024,
         "GB" => 1024 * 1024 * 1024
      );
      if (array_key_exists($unit,$units)) {
         return $units[$unit];
      } else {
         return 1;
      }
   }



   /**
    * Display form
    *
    * @param object $package PluginFusioninventoryDeployPackage instance
    * @param array $request_data
    * @param string $rand unique element id used to identify/update an element
    * @param string $mode possible values: init|edit|create
    */
   static function displayForm(PluginFusioninventoryDeployPackage $package, $request_data, $rand, $mode) {
      /*
       * Get element config in 'edit' mode
       */
      $config = NULL;
      if ($mode === 'edit' && isset($request_data['index'])) {
         /*
          * Add an hidden input about element's index to be updated
          */
         echo "<input type='hidden' name='index' value='".$request_data['index']."' />";

         $c = $package->getSubElement('checks', $request_data['index']);

         if (is_array($c) && count($c)) {

            $config = array(
               'type' => $c['type'],
               'data' => $c
            );
         }
      }

      /*
       * Display start of div form
       */
      if (in_array($mode, array('init'), TRUE)) {
         echo "<div id='checks_block$rand' style='display:none'>";
      }

      /*
       * Display element's dropdownType in 'create' or 'edit' mode
       */
      if (in_array($mode, array('create', 'edit'), TRUE)) {
         self::displayDropdownType($config, $rand, $mode);
      }

      /*
       * Display element's values in 'edit' mode only.
       * In 'create' mode, those values are refreshed with dropdownType 'change'
       * javascript event.
       */
      if (in_array($mode, array('create', 'edit'), TRUE)) {
         echo "<span id='show_check_value{$rand}'>";
         if ($mode === 'edit') {
            self::displayAjaxValues($config, $request_data, $rand, $mode);
         }
         echo "</span>";
      }

      /*
       * Close form div
       */
      if (in_array($mode, array('init'), TRUE)) {
         echo "</div>";
      }
   }



   /**
    * Display list of checks
    *
    * @global array $CFG_GLPI
    * @param object $package PluginFusioninventoryDeployPackage instance
    * @param array $datas array converted of 'json' field in DB where stored checks
    * @param string $rand unique element id used to identify/update an element
    */
   static function displayList(PluginFusioninventoryDeployPackage $package, $datas, $rand) {
      global $CFG_GLPI;

      $checks_types = self::getTypes();

      echo "<table class='tab_cadrehov package_item_list' id='table_check_$rand'>";
      $i = 0;
      foreach ($datas['jobs']['checks'] as $check) {
         //specific case for filesystem size
         if (is_numeric($check['value'])) {
            if ($check['type'] == "freespaceGreater") {
               $check['value'] = $check['value'] * 1024 * 1024;
            }
            $check['value'] = PluginFusioninventoryDeployFile::processFilesize($check['value']);
         }

         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         if ($package->can($package->getID(), UPDATE)) {
            echo "<td class='control'>";
            Html::showCheckbox(array('name' => 'check_entries[]'));
            echo "</td>";
         }
         echo "<td>";
         echo "<a class='edit'".
            "onclick=\"edit_subtype('check', {$package->fields['id']}, $rand ,this)\">".
            $checks_types[$check['type']].
            "</a><br />";
         echo $check['path'];
         if (!empty($check['value'])) {
            echo "&nbsp;&nbsp;&nbsp;<b>";
            if (strpos($check['type'], "Greater") !== FALSE) {
               echo "&gt;";
            } else if (strpos($check['type'], "Lower") !== FALSE) {
               echo "&lt;";
            } else {
               echo "=";
            }
            echo "</b>&nbsp;&nbsp;&nbsp;";
            echo $check['value'];
         }
         echo "</td>";
         if ($package->can($package->getID(), UPDATE)) {
            echo "<td class='rowhandler control' title='".__('drag', 'fusioninventory').
               "'><div class='drag row'></div></td>";
         }
         echo "</tr>";
         $i++;
      }
      if ($package->can($package->getID(), UPDATE)) {
         echo "<tr><th>";
         Html::checkAllAsCheckbox("checksList$rand", mt_rand());
         echo "</th><th colspan='3' class='mark'></th></tr>";
      }
      echo "</table>";
      if ($package->can($package->getID(), UPDATE)) {
         echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt='' />";
         echo "<input type='submit' name='delete' value=\"".
            __('Delete', 'fusioninventory')."\" class='submit' />";
      }
   }



   /**
    * Display the dropdown to select type of element
    *
    * @global array $CFG_GLPI
    * @param array $config order item configuration
    * @param string $rand unique element id used to identify/update an element
    * @param string $mode mode in use (create, edit...)
    */
   static function displayDropdownType($config, $rand, $mode) {
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
      $checks_types = self::getTypes();
      array_unshift($checks_types, "---");

      /*
       * Display dropdown html
       */
      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>".__("Type", 'fusioninventory')."</th>";
      echo "<td>";
      Dropdown::showFromArray("deploy_checktype", $checks_types, $dropdown_options);
      echo "</td>";
      echo "</tr></table>";

      //ajax update of check value span
      if ($mode === 'create') {
         $params = array(
            'value'  => '__VALUE__',
            'rand'   => $rand,
            'myname' => 'method',
            'type'   => "check",
            'mode'   => $mode
         );

         Ajax::updateItemOnEvent(
            "dropdown_deploy_checktype$rand",
            "show_check_value$rand",
            $CFG_GLPI["root_doc"].
            "/plugins/fusioninventory".
            "/ajax/deploy_displaytypevalue.php",
            $params,
            array("change", "load")
         );
      }
   }



   /**
    * Get fields for the check type requested
    *
    * @param string $type the type of check
    * @param array $data fields yet defined in edit mode
    * @param string $mode mode in use (create, edit...)
    *
    * @return string|false
    */
   static function getValues($type, $data, $mode) {
      $values = array(
         'path_label'   => "",
         'path_value'   => "",
         'value_type'   => "input",
         'value_label'  => "",
         'value'       => "",
         'return'       => "error"
      );

      if ($mode === 'edit') {
         $values['path_value'] = isset($data['path'])?$data['path']:"";
         $values['value'] = isset($data['value'])?$data['value']:"";
         $values['return'] = isset($data['return'])?$data['return']:"error";
      }
      switch ($type) {

         case "winkeyExists":
         case "winkeyMissing":
            $values['path_label'] = __("Key", 'fusioninventory');
            $values['value_label'] = FALSE;
            break;

         case "winkeyEquals":
            $values['path_label'] = __("Key", 'fusioninventory');
            $values['value_label'] = __('Key value', 'fusioninventory');
            break;

         case "fileExists":
         case "fileMissing":
            $values['path_label'] = __("File", 'fusioninventory');
            $values['value_label'] = FALSE;
            break;

         case "fileSizeGreater":
         case "fileSizeEquals":
         case "fileSizeLower":
            $values['path_label'] = __("File", 'fusioninventory');
            $values['value_label'] = __('Value', 'fusioninventory');
            $values['value_type'] = "input+unit";
            break;

         case "fileSHA512":
            $values['path_label'] = __("File", 'fusioninventory');
            $values['value_label'] = __('Value', 'fusioninventory');
            $values['value_type'] = "textarea";
            break;

         case "freespaceGreater":
            $values['path_label'] = __("Disk or directory", 'fusioninventory');
            $values['value_label'] = __('Value', 'fusioninventory');
            $values['value_type'] = "input+unit";
            break;

         default:
            return FALSE;

      }
      return $values;
   }



   /**
    * Display different fields relative the check selected
    *
    * @param array $config
    * @param array $request_data
    * @param string $rand unique element id used to identify/update an element
    * @param string $mode mode in use (create, edit...)
    * @return boolean
    */
   static function displayAjaxValues($config, $request_data, $rand, $mode) {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      if (isset($request_data['packages_id'])) {
         $pfDeployPackage->getFromDB($request_data['orders_id']);
      } else {
         $pfDeployPackage->getEmpty();
      }

      /*
       * Get type from request params
       */
      $type = NULL;

      if ($mode === 'create') {
         $type = $request_data['value'];
         $config_data = NULL;
      } else {
         $type = $config['type'];
         $config_data = $config['data'];
      }

      $values = self::getValues($type, $config_data, $mode);
      if ($values === FALSE) {
         return FALSE;
      }
      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>{$values['path_label']}</th>";
      echo "<td><input type='text' name='path' id='check_path{$rand}' value='{$values['path_value']}' /></td>";
      echo "</tr>";
      if ($values['value_label'] !== FALSE) {
         echo "<tr>";
         echo "<th>{$values['value_label']}</th>";
         switch ($values['value_type']) {

            case "textarea":
               echo "<td><textarea name='value' id='check_value{$rand}' rows='5'>".
                  $values['value']."</textarea></td>";
               break;

            case "input":
               echo "<td><input type='text' name='value' id='check_value{$rand}' value='".
                  $values['value']."' /></td>";
               break;

            case "input+unit":
               $value = $values['value'];
               // freespaceGreater check is saved as MiB
               if ($type == 'freespaceGreater') {
                  $value = $value * 1024 * 1024;
               }
               $options['value'] = 'KB';
               if ($mode === 'edit') {
                  if ($value >= self::getUnitSize('GB')) {
                     $value = $value / (self::getUnitSize('GB'));
                     $options['value'] = 'GB';
                  } elseif ($value >= (self::getUnitSize('MB'))) {
                     $value = $value/ (self::getUnitSize('MB'));
                     $options['value'] = 'MB';
                  }  elseif ($value >= (self::getUnitSize('KB'))) {
                     $value = $value/ (self::getUnitSize('KB'));
                     $options['value'] = 'KB';
                  } else {
                     $options['value'] = 'B';
                  }
               }
               echo "<td>";
               echo "<input type='text' name='value' id='check_value{$rand}' "
                   . "value='{$value}' />";
               echo "</td>";
               echo "</tr><tr>";
               echo "<th>".__("Unit", 'fusioninventory')."</th>";
               echo "<td>";
               $unit_labels = self::getUnitLabel();

               /*
                * The freespaceGreater check does not need to propose KiB or B
                * because its value is based on MiB according to REST API.
                *                               -- Kevin 'kiniou' Roy
                */

               if ($type == 'freespaceGreater') {
                  unset($unit_labels['KB']);
                  unset($unit_labels['B']);
               }

               Dropdown::showFromArray('unit', $unit_labels, $options);
               echo "</td>";
               break;

         }
         echo "</tr>";
      }

      echo "<tr>";
      echo "<th>".__("In case of error", 'fusioninventory')."</th>";
      echo "<td>";
      Dropdown::showFromArray('return', array(
                  "error"  => __('Error', 'fusioninventory'),
                  "ignore" => __("Ignore", 'fusioninventory')
               ), array('value' => $values['return']));
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td>";
      echo "</td>";
      echo "<td>";
      if ($pfDeployPackage->can($pfDeployPackage->getID(), UPDATE)) {
         if ($mode === 'edit') {
            echo "<input type='submit' name='save_item' value=\"".
               _sx('button', 'Save')."\" class='submit' >";
         } else {
            echo "<input type='submit' name='add_item' value=\"".
               _sx('button', 'Add')."\" class='submit' >";
         }
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";
   }



   /**
    * Add a new item in checks of the package
    *
    * @param array $params list of fields with value of the check
    */
   static function add_item($params) {

      if (!isset($params['value'])) {
         $params['value'] = "";
      }

      if (!empty($params['value']) && is_numeric($params['value'])) {
         $params['value'] = $params['value'] * self::getUnitSize($params['unit']);

         //Make an exception for freespaceGreater check which is saved as MiB
         if ($params['deploy_checktype'] == "freespaceGreater") {
            $params['value'] = $params['value'] / (1024*1024);
         }
      }

      //prepare new check entry to insert in json
      $new_entry = array(
         'type'   => $params['deploy_checktype'],
         'path'   => $params['path'],
         'value'  => $params['value'],
         'return' => $params['return']
      );

      //get current order json
      $datas = json_decode(
              PluginFusioninventoryDeployPackage::getJson($params['id']),
              TRUE
      );

      //add new entry
      $datas['jobs']['checks'][] = $new_entry;

      //update order
      PluginFusioninventoryDeployPackage::updateOrderJson(
         $params['id'], $datas
      );
   }



   /**
    * Save the item in checks
    *
    * @param array $params list of fields with value of the check
    */
   static function save_item($params) {

      if (!isset($params['value'])) {
         $params['value'] = "";
      }

      if (!empty($params['value']) && is_numeric($params['value'])) {
         $params['value'] = $params['value'] * self::getUnitSize($params['unit']);

         //Make an exception for freespaceGreater check which is saved as MiB
         if ($params['deploy_checktype'] == "freespaceGreater") {
            $params['value'] = $params['value'] / (1024 * 1024);
         }
      }

      //prepare updated check entry to insert in json
      $entry = array(
         'type'   => $params['deploy_checktype'],
         'path'   => $params['path'],
         'value'  => $params['value'],
         'return' => $params['return']
      );

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployPackage::getJson($params['id']), TRUE);

      //unset index
      unset($datas['jobs']['checks'][$params['index']]);

      //add new datas at index position
      //(array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($datas['jobs']['checks'], $params['index'], 0, array($entry));

      //update order
      PluginFusioninventoryDeployPackage::updateOrderJson($params['id'], $datas);
   }



   /**
    * Remove an item
    *
    * @param array $params
    * @return boolean
    */
   static function remove_item($params) {
      if (!isset($params['check_entries'])) {
         return FALSE;
      }

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployPackage::getJson($params['id']), TRUE);

      //remove selected checks
      foreach ($params['check_entries'] as $index => $checked) {
         if ($checked >= "1" || $checked == "on") {
            unset($datas['jobs']['checks'][$index]);
         }
      }

      //Ensure checks is an array and not a dictionnary
      //Note: This happens when removing an array element from the begining
      $datas['jobs']['checks'] = array_values($datas['jobs']['checks']);

      //update order
      PluginFusioninventoryDeployPackage::updateOrderJson($params['id'], $datas);
      return TRUE;
   }



   /**
    * Move an item
    *
    * @param array $params
    */
   static function move_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployPackage::getJson($params['id']), TRUE);

      //get data on old index
      $moved_check = $datas['jobs']['checks'][$params['old_index']];

      //remove this old index in json
      unset($datas['jobs']['checks'][$params['old_index']]);

      //insert it in new index (array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($datas['jobs']['checks'], $params['new_index'], 0, array($moved_check));

      //update order
      PluginFusioninventoryDeployPackage::updateOrderJson($params['id'], $datas);
   }
}

?>