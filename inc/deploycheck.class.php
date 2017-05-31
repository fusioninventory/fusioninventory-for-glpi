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
class PluginFusioninventoryDeployCheck extends CommonDBTM {

   /**
    * Get types of checks with name => description
    *
    * @return array
    */
   static function getTypes() {
      return [
         __('Registry', 'fusioninventory') => [
                  'winkeyExists'       => __("Registry key exists", 'fusioninventory'),
                  'winvalueExists'     => __("Registry value exists", 'fusioninventory'),
                  'winkeyMissing'      => __("Registry key missing", 'fusioninventory'),
                  'winvalueMissing'    => __("Registry value missing", 'fusioninventory'),
                  'winkeyEquals'       => __("Registry value equals to", 'fusioninventory'),
                  'winvalueType'       => __("Type of registry value equals to", 'fusioninventory')
               ],
               __('File') => [
                  'fileExists'         => __("File exists", 'fusioninventory'),
                  'fileMissing'        => __("File is missing", 'fusioninventory'),
                  'fileSizeGreater'    => __("File size is greater than", 'fusioninventory'),
                  'fileSizeEquals'     => __("File size is equal to", 'fusioninventory'),
                  'fileSizeLower'      => __("File size is lower than", 'fusioninventory'),
                  'fileSHA512'         => __("SHA-512 hash value matches", 'fusioninventory'),
                  'fileSHA512mismatch' => __("SHA-512 hash value mismatch", 'fusioninventory'),
               ],
             __('Other') => [
            'freespaceGreater'   => __("Free space is greater than", 'fusioninventory')
            ]
      ];
   }


   /**
    * Get label for a type
    * @param the type value
    * @return the type label
    */
   static function getLabelForAType($type) {
      $alltypes = [];
      foreach (self::getTypes() as $label => $types) {
         $alltypes+= $types;
      }
      if (isset($alltypes[$type])) {
         return $alltypes[$type];
      } else {
         return '';
      }
   }

   /**
    * Get Unit name
    *
    * @return array
    */
   static function getUnitLabel() {
      return [
               "B"  => __('o'),
               "KB" => __('Kio'),
               "MB" => __('Mio'),
               "GB" => __('Gio')
             ];
   }


   static function getAuditDescription($type, $return) {
      $return_string = self::getLabelForAType($type);
      //The skip case is a litte bit different. So we notice to the user
      //that if audit is successfull, the the audit check process continue
      if ($return == 'skip') {
         $return_string.=' : '.__('continue', 'fusioninventory');
      } else {
         $return_string.=' : '.__('passed', 'fusioninventory');
      }
      $return_string.= ', '.__('otherwise', 'fusioninventory').' : ';
      $return_string.= self::getValueForReturn($return);

      return $return_string;
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


   static function getRegistryTypes() {
      return ['REG_SZ'                  => 'REG_SZ',
              'REG_DWORD'               => 'REG_DWORD',
              'REG_BINARY'              => 'REG_BINARY',
              'REG_EXPAND_SZ'           => 'REG_EXPAND_SZ',
              'REG_MULTI_SZ'            => 'REG_MULTI_SZ',
              'REG_LINK'                => 'REG_LINK',
              'REG_DWORD_BIG_ENDIAN'    => 'REG_DWORD_BIG_ENDIAN',
              'REG_NONE'                => 'REG_NONE'
             ];
   }

   static function dropdownRegistryTypes($value = 'REG_SZ') {
      return Dropdown::showFromArray('value', self::getRegistryTypes(),
                                     ['value' => $value]);
   }

   static function getRegistryTypeLabel($type) {
      $types = self::getRegistryTypes();
      if (isset($types[$type])) {
         return $types[$type];
      } else {
         return '';
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
      $package_id   = $package->getID();
      $canedit      = $package->canUpdateContent();
      echo "<table class='tab_cadrehov package_item_list' id='table_check_$rand'>";
      $i = 0;
      foreach ($datas['jobs']['checks'] as $check) {
         switch ($check['type']) {
            case 'freespaceGreater':
               $check['value'] = $check['value'] * 1024 * 1024;
            case 'fileSizeLower':
            case 'fileSizeGreater':
            case 'fileSizeEquals':
               $check['value'] = PluginFusioninventoryDeployFile::processFilesize($check['value']);
               break;
         }

         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         if ($canedit) {
            echo "<td class='control'>";
            Html::showCheckbox(array('name' => 'check_entries['.$i.']'));
            echo "</td>";
         }

         //Get the audit full description (with type and return value)
         //to be displayed in the UI
         $text = self::getAuditDescription($check['type'], $check['return']);
         if (isset($check['name']) && !empty($check['name'])) {
            $check_label = $check['name'].' ('.$text.')';
         } else {
            $check_label = $text;
         }
         echo "<td>";
         if ($canedit) {
            echo "<a class='edit'
                     onclick=\"edit_subtype('check', $package_id, $rand ,this)\">";
         }
         echo $check_label;
         if ($canedit) {
            echo "</a>";
         }
         echo "<br />";
         $type_values = self::getLabelsAndTypes($check['type'], false);
         echo $type_values['path_label'].': '.$check['path'];

         if (!empty($check['value']) && $check['value'] != NOT_AVAILABLE) {
            echo "&nbsp;&nbsp;&nbsp;<b>";
            switch ($check['type']) {
               case 'freespaceGreater':
               case 'fileSizeGreater':
                  echo "&gt;";
                  break;
               case 'fileSizeLower':
                  echo "&lt;";
                  break;
               default:
                  echo "=";
                  break;
            }
            echo "</b>&nbsp;&nbsp;&nbsp;";
            echo $check['value'];
         }

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
         Html::checkAllAsCheckbox("checksList$rand", mt_rand());
         echo "</th><th colspan='3' class='mark'></th></tr>";
      }
      echo "</table>";
      if ($canedit) {
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
      if ($mode === 'create') {
         $checks_types = self::getTypes();
      } else {
         $checks_types = [];
         foreach (self::getTypes() as $label => $data) {
            $checks_types+= $data;
         }
      }
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
         'warning_message' => false,
         'name_value'  => "",
         'name_label'  => __('Audit label', 'fusioninventory'),
         'name_type'   => "input",
         'path_label'  => "",
         'path_value'  => "",
         'path_comment'=> "",
         'value_type'  => "input",
         'value_label' => "",
         'value'       => "",
         'return'      => "error"
      );

      if ($mode === 'edit') {
         $values['name_value'] = isset($data['name'])?$data['name']:"";
         $values['path_value'] = isset($data['path'])?$data['path']:"";
         $values['value']      = isset($data['value'])?$data['value']:"";
         $values['return']     = isset($data['return'])?$data['return']:"error";
      }

      $type_values = self::getLabelsAndTypes($type, true);
      foreach ($type_values as $key => $value) {
         $values[$key] = $value;
      }
      return $values;
   }

   static function getMandatoryMark() {
      return "&nbsp;<span class='red'>*</span>";
   }
   /**
   *  Get labels and type for a check
   * @param check_type the type of check
   * @param mandatory indicates if mandatory mark must be added to the label
   * @return the labels and type for a check
   */
   static function getLabelsAndTypes($check_type, $mandatory = false) {
      $values = [];
      $mandatory_mark = ($mandatory?self::getMandatoryMark():'');

      switch ($check_type) {
         case "winkeyExists":
         case "winkeyMissing":
            $values['path_label']         = __("Path to the key", 'fusioninventory').$mandatory_mark;
            $values['value_label']     = FALSE;
            $values['path_comment']    = __('Example of registry key').': HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\';
            $values['warning_message'] = __('Fusioninventory-Agent 2.3.20 or higher recommended');
            break;

         case "winvalueExists":
         case "winvalueMissing":
            $values['path_label']      = __("Path to the value", 'fusioninventory').$mandatory_mark;
            $values['value_label']     = FALSE;
            $values['path_comment']    = __('Example of registry value').': HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server';
            $values['warning_message'] = __('Fusioninventory-Agent 2.3.20 or higher mandatory');
            break;

         case "winkeyEquals":
            $values['path_label']      = __("Path to the value", 'fusioninventory').$mandatory_mark;
            $values['value_label']     = __('Value', 'fusioninventory');
            $values['path_comment']    = __('Example of registry value').': HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server';
            $values['warning_message'] = __('Fusioninventory-Agent 2.3.20 or higher recommended');
            break;

         case "winvalueType":
            $values['path_label']      = __("Path to the value", 'fusioninventory').$mandatory_mark;
            $values['value_label']     = __('Type of value', 'fusioninventory').$mandatory_mark;
            $values['value_type']      = 'registry_type';
            $values['path_comment']    = __('Example of registry value').': HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server';
            $values['warning_message'] = __('Fusioninventory-Agent 2.3.20 or higher mandatory');            break;

         case "fileExists":
         case "fileMissing":
            $values['path_label']  = __("File", 'fusioninventory').$mandatory_mark;
            $values['value_label'] = FALSE;
            break;

         case "fileSizeGreater":
         case "fileSizeEquals":
         case "fileSizeLower":
            $values['path_label']  = __("File", 'fusioninventory').$mandatory_mark;
            $values['value_label'] = __('Value', 'fusioninventory').$mandatory_mark;
            $values['value_type']  = "input+unit";
            break;

         case "fileSHA512":
         case "fileSHA512mismatch":
            $values['path_label']  = __("File", 'fusioninventory').$mandatory_mark;
            $values['value_label'] = __('Value', 'fusioninventory').$mandatory_mark;
            $values['value_type']  = "textarea";
            break;

         case "freespaceGreater":
            $values['path_label']  = __("Disk or directory", 'fusioninventory').$mandatory_mark;
            $values['value_label'] = __('Value', 'fusioninventory').$mandatory_mark;
            $values['value_type']  = "input+unit";
            break;

         default:
            break;

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
      global $CFG_GLPI;

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
      echo "<th>".__('Audit label', 'fusioninventory')."</th>";
      echo "<td><input type='text' name='name' id='check_name{$rand}' value=\"{$values['name_value']}\" /></td>";
      echo "</tr>";
      echo "<th>{$values['path_label']}</th>";
      echo "<td><input type='text' name='path' id='check_path{$rand}' value=\"{$values['path_value']}\" />";
      if ($values['path_comment']) {
         echo "<br/><i>".$values['path_comment']."</i>";
      }
      echo "</td>";
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

            case 'registry_type':
               echo "<td>";
               self::dropdownRegistryTypes($values['value']);
               echo "</td>";
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
      echo "<th>".__("If not successfull", 'fusioninventory')."</th>";
      echo "<td>";
      Dropdown::showFromArray('return', self::getAllReturnValues(),
                              ['value' => $values['return']]);
      echo "</td>";
      echo "</tr>";

      if ($values['warning_message']) {
         echo "<tr>";
         echo "<td></td>";
         echo "<td>";
         echo "<img src='".$CFG_GLPI['root_doc']."/pics/warning_min.png'>";
         echo "<span class='red'><i>".$values['warning_message']."</i></span></td>";
         echo "</tr>";
      }

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
   * Get all possible return values for a check
   * @return an array of return values and their labels
   */
   static function getAllReturnValues() {
      return  ["error"   => __('abort job', 'fusioninventory'),
               "skip"    => __("skip job", 'fusioninventory'),
               "info"    => __("report info", 'fusioninventory'),
               "warning" => __("report warning", 'fusioninventory')
              ];
   }

   /**
   * Get the label for a return value
   * @param the check return value
   * @return the label for the return value
   */
   static function getValueForReturn($value) {
      $values = self::getAllReturnValues();
      if (isset($values[$value])) {
         return $values[$value];
      } else {
         return '';
      }
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
      if (!isset($params['name'])) {
         $params['name'] = "";
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
         'name'   => $params['name'],
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
      if (!isset($params['name'])) {
         $params['name'] = "";
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
         'name'   => $params['name'],
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
      $datas = json_decode(PluginFusioninventoryDeployPackage::getJson($params['packages_id']), TRUE);

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
      PluginFusioninventoryDeployPackage::updateOrderJson($params['packages_id'], $datas);
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
