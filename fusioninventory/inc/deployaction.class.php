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
         'mkdir'   => __('mkdir'),
         'message' => __('message')
      );
   }

   static function displayForm($order_type, $packages_id, $datas, $rand) {
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

      //display stored actions datas
      if (!isset($datas['jobs']['actions'])) return;
      echo "<table class='tab_cadre' style='width:100%'>";
      foreach ($datas['jobs']['actions'] as $action) {
         echo "<tr>";
         echo "<td><input type='checkbox' /></td>";
         $keys = array_keys($action);
         $action_type = array_shift($keys);
         echo "<td>$action_type</td>";
         foreach ($action[$action_type] as $key => $value) {
            if (is_array($value)) continue;
            echo "<td>$key : $value;</td>";
         }
         echo "</tr>";
      }
      echo "<tr><td colspan='2'>";
      echo "<input type='button'  name='delete' value=\"".
         __('Delete', 'fusioninventory')."\" class='submit'>";
      echo "</td></tr>";
      echo "</table>";
   }

   static function dropdownType($rand) {
      global $CFG_GLPI;

      $actions_types = self::getTypes();
      array_unshift($actions_types, "---");


      Dropdown::showFromArray("deploy_actiontype", $actions_types, array('rand' => $rand));

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
      echo $type;

      echo "&nbsp;<input type='submit' name='itemaddaction' value=\"".
         __('Add')."\" class='submit' >";
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
