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
   @author    Walid Nouh
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

class PluginFusioninventoryDeployCheck extends CommonDBTM {

   static function getTypeName($nb=0) {
      return __('Audits', 'fusioninventory');
   }

   
   
   static function getTypes() {
      return array(
         'winkeyExists'     => __("winkeyExists", 'fusioninventory'),
         'winkeyMissing'    => __("winkeyMissing", 'fusioninventory'),
         'winkeyEquals'     => __("winkeyEquals", 'fusioninventory'),
         'fileExists'       => __("fileExists", 'fusioninventory'),
         'fileMissing'      => __("fileMissing", 'fusioninventory'),
         'fileSizeGreater'  => __("fileSizeGreater", 'fusioninventory'),
         'fileSizeEquals'   => __("fileSizeEquals", 'fusioninventory'),
         'fileSizeLower'    => __("fileSizeLower", 'fusioninventory'),
         'fileSHA512'       => __("fileSHA512", 'fusioninventory'),
         'freespaceGreater' => __("freespaceGreater", 'fusioninventory')
      );
   }

   
   
   static function displayForm($orders_id, $datas, $rand) {
      global $CFG_GLPI;


      if (!isset($datas['index'])) {
         echo "<div style='display:none' id='checks_block$rand' >";
      } else {
         //== edit selected data ==
         
         //get current order json
         $datas_o = json_decode(PluginFusioninventoryDeployOrder::getJson($orders_id), TRUE);

         //get data on index
         $check = $datas_o['jobs']['checks'][$datas['index']];         
      }

      echo "<span id='showCheckType$rand'></span>";
      echo "<script type='text/javascript'>";
      $params = array(
         'rand'    => $rand,
         'subtype' => "check"
      );
      if (isset($check['type'])) {
         $params['edit']   = "true";
         $params['type']   = $check['type'];
         $params['index']  = $datas['index'];
         $params['path']   = addslashes($check['path']);
         $params['value']  = addslashes($check['value']);
         $params['return'] = $check['return'];
      }
      Ajax::UpdateItemJsCode("showCheckType$rand",
                             $CFG_GLPI["root_doc"].
                             "/plugins/fusioninventory/ajax/deploydropdown_packagesubtypes.php",
                             $params,
                             "dropdown_deploy_checktype");
      echo "</script>";


      echo "<span id='showCheckValue$rand'></span>";
      
      echo "<hr>";
      if (!isset($datas['index'])) {
         echo "</div>";
      } else {
         return TRUE;
      }
      Html::closeForm();

      //display stored checks datas
      echo "<form name='removecheck' method='post' action='deploypackage.form.php?remove_item' ".
         "id='checksList$rand'>";
      echo "<input type='hidden' name='orders_id' value='$orders_id' />";
      echo "<input type='hidden' name='itemtype' value='PluginFusioninventoryDeployCheck' />";
      if (!isset($datas['jobs']['checks']) || empty($datas['jobs']['checks'])) {
         return;
      }
      echo "<div id='drag_checks'>";
      echo "<table class='tab_cadrehov package_item_list' id='table_check'>";
      $i = 0;
      foreach ($datas['jobs']['checks'] as $check) {
         //specific case for filesystem size
         if (!empty($check['value']) && is_numeric($check['value'])) {
            //$check['value'] = round($check['value'] / (1024 * 1024))." MB";
            $check['value'] = PluginFusioninventoryDeployFile::processFilesize($check['value']);
         }

         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         echo "<td class='control'>";
         echo "<input type='checkbox' name='check_entries[]' value='$i' />";
         echo "</td>";
         echo "<td>";
         echo "<a class='edit' onclick='edit_check($i)'>".$check['type']."</a><br />";
         echo $check['path'];
         if (!empty($check['value'])) {
            echo "&nbsp;&nbsp;&nbsp;<b>";
            if (strpos($check['type'], "Greater") !== false) echo "&gt;";
            elseif (strpos($check['type'], "Lower") !== false) echo "&lt;";
            else echo "=";
            echo "</b>&nbsp;&nbsp;&nbsp;";
            echo $check['value'];
         }
         echo "</td>";
         echo "<td class='rowhandler control' title='".__('drag', 'fusioninventory').
            "'><div class='drag row'></div></td>";
         echo "</tr>";
         $i++;
      }
      echo "<tr><td>";
      Html::checkAllAsCheckbox("checksList$rand", $rand);
      echo "</td><td colspan='2'>";
      echo "<input type='submit' name='delete' value=\"".
         __('Delete', 'fusioninventory')."\" class='submit'>";
      echo "</td></tr>";
      echo "</table></div>";
      Html::closeForm();

      echo "<script type='text/javascript'>
         function edit_check(index) {
            if (Ext.get('plus_checks_block$rand')) Ext.get('plus_checks_block$rand').remove();
            Ext.get('checks_block$rand').setDisplayed('block');
            Ext.get('checks_block$rand').load({
                  'url': '".$CFG_GLPI["root_doc"].
                             "/plugins/fusioninventory/ajax/deploypackage_form.php',
                  'scripts': true,
                  'params' : {
                     'subtype': 'check',
                     'index': index, 
                     'orders_id': $orders_id, 
                     'rand': '$rand'
                  }
               });
         }
      </script>";
   }

   
   
   static function dropdownType($datas) {
      global $CFG_GLPI;

      $rand = $datas['rand'];

      $checks_types = self::getTypes();
      array_unshift($checks_types, "---");
      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>".__("Type", 'fusioninventory')."</th>";
      echo "<td>";
      $options['rand'] = $datas['rand'];
      if (isset($datas['edit'])) {
         $options['value'] = $datas['type'];
      }
      Dropdown::showFromArray("deploy_checktype", $checks_types, $options);
      echo "</td>";
      echo "</tr></table>";

      //ajax update of check value span
      $params = array('value'  => '__VALUE__',
                      'rand'   => $rand,
                      'myname' => 'method',
                      'type'   => "check");
      if (isset($datas['edit'])) {
         $params['edit']   = "true";
         $params['index']  = $datas['index'];
         $params['path']   = addslashes($datas['path']);
         $params['value2'] = addslashes($datas['value']);
         $params['return'] = $datas['return'];
      }
      Ajax::updateItemOnEvent("dropdown_deploy_checktype$rand",
                              "showCheckValue$rand",
                              $CFG_GLPI["root_doc"].
                                 "/plugins/fusioninventory/ajax/deploy_displaytypevalue.php",
                              $params,
                              array("change", "load"));

      if (isset($datas['edit'])) {
         echo "<script type='text/javascript'>";
         Ajax::UpdateItemJsCode("showCheckValue$rand",
                                $CFG_GLPI["root_doc"].
                                 "/plugins/fusioninventory/ajax/deploy_displaytypevalue.php",
                                $params,
                                "dropdown_deploy_checktype$rand");
         echo "</script>";
      }
   }

   
   
   static function displayAjaxValue($datas) {

      $value = $datas['value'];
      $rand  = $datas['rand'];

      $path_value = isset($datas['path'])?$datas['path']:"";
      $value2 = isset($datas['value2'])?$datas['value2']:"";
      $return = isset($datas['return'])?$datas['return']:"";
      $value_type = "input";
      switch ($value) {
         case "winkeyExists":
         case "winkeyMissing":
            $path_label = __("Key", 'fusioninventory');
            $value_label = FALSE;
            break;
         case "winkeyEquals":
            $path_label = __("Key", 'fusioninventory');
            $value_label = "Key value";
            break;
         case "fileExists":
         case "fileMissing":
            $path_label = __("File", 'fusioninventory');
            $value_label = FALSE;
            break;
         case "fileSizeGreater":
         case "fileSizeEquals":
         case "fileSizeLower":
            $path_label = __("File", 'fusioninventory');
            $value_label = "Value";
            $value_type = "input+unit";
            break;
         case "fileSHA512":
            $path_label = __("File", 'fusioninventory');
            $value_label = "Value";
            $value_type = "textarea";
            break;
         case "freespaceGreater":
            $path_label = __("Disk or directory", 'fusioninventory');
            $value_label = "Value";
            $value_type = "input+unit";
            break;
      }

      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>$path_label</th>";
      echo "<td><input type='text' name='path' id='check_path$rand' value='$path_value' /></td>";
      echo "</tr>";
      if ($value_label !== FALSE) {
         echo "<tr>";
         echo "<th>$value_label</th>";
         switch ($value_type) {
            case "textarea":
               echo "<td><textarea name='value' id='check_value$rand' rows='5'>".
                  $value2."</textarea></td>";
               break;
            case "input":
               echo "<td><input type='text' name='value' id='check_value$rand' value='".
                  $value2."' /></td>";
               break;
            case "input+unit":
               $options['value'] = 'KB';
               if (isset($datas['edit'])) {
                  if ($value2 >= (1024 * 1024 * 1024)) {
                     $value2 = round($value2/ (1024 * 1024 * 1024), 1);
                     $options['value'] = 'GB';
                  } elseif ($value2 >= (1024 * 1024)) {
                     $value2 = round($value2/ (1024 * 1024), 1);
                     $options['value'] = 'MB';
                  }  elseif ($value2 >= (1024)) {
                     $value2 = round($value2/ (1024), 1);
                     $options['value'] = 'kB';
                  } else {
                     $options['value'] = 'B';
                  }
               }
               echo "<td><input type='text' name='value' id='check_value$rand' value='".
                  $value2."' /></td>";
               echo "</tr><tr>";
               echo "<th>".__("Unit", 'fusioninventory')."</th>";
               echo "<td>";

               Dropdown::showFromArray('unit', array(
                  "B"  => __("B", 'fusioninventory'),
                  "KB" => __("KiB", 'fusioninventory'),
                  "MB" => __("MiB", 'fusioninventory'),
                  "GB" => __("GiB", 'fusioninventory')
               ), $options);
               echo "</td>";
               break;

         }
         echo "</tr>";
      }

      echo "<tr>";
      echo "<th>".__("return error", 'fusioninventory')."</th>";
      echo "<td>";
      Dropdown::showFromArray('return', array(
                  "error"  => __("error", 'fusioninventory'),
                  "ignore" => __("ignore", 'fusioninventory')
               ), array('value' => $return));
      echo "</td>";
      echo "</tr>";

      echo "<tr><td></td><td>";
      if (isset($datas['edit'])) {
         echo "<input type='hidden' name='index' value='".$datas['index']."' />";
         echo "<input type='submit' name='save_item' value=\"".
            _sx('button', 'Save')."\" class='submit' >";
      } else {
         echo "<input type='submit' name='add_item' value=\"".
            _sx('button', 'Add')."\" class='submit' >";
      }
      echo "</td></tr>";
      echo "</table>";
   }

   
   
   static function add_item($params) {
      if (!isset($params['value'])) {
         $params['value'] = "";
      }

      if (!empty($params['value']) && is_numeric($params['value'])) {
         $params['value'] = $params['value']  * 1024 * 1024;
      }

      //prepare new check entry to insert in json
      $new_entry = array(
         'type'   => $params['deploy_checktype'],
         'path'   => $params['path'],
         'value'  => $params['value'],
         'return' => $params['return']
      );

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //add new entry
      $datas['jobs']['checks'][] = $new_entry;

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }

   
   
   static function save_item($params) {
      if (!isset($params['value'])) {
         $params['value'] = "";
      }

      if (!empty($params['value']) && is_numeric($params['value'])) {
         $params['value'] = $params['value']  * 1024 * 1024;
      }

      //prepare updated check entry to insert in json
      $entry = array(
         'type'   => $params['deploy_checktype'],
         'path'   => $params['path'],
         'value'  => $params['value'],
         'return' => $params['return']
      );

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //unset index 
      unset($datas['jobs']['checks'][$params['index']]);

      //add new datas at index position 
      //(array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($datas['jobs']['checks'], $params['index'], 0, array($entry));

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }
   
   

   static function remove_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //remove selected checks
      foreach ($params['check_entries'] as $index) {
         unset($datas['jobs']['checks'][$index]);
      }

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }

   
   
   static function move_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //get data on old index
      $moved_check = $datas['jobs']['checks'][$params['old_index']];

      //remove this old index in json
      unset($datas['jobs']['checks'][$params['old_index']]);

      //insert it in new index (array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($datas['jobs']['checks'], $params['new_index'], 0, array($moved_check));

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }
}

?>
