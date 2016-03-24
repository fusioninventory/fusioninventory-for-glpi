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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryCollect_Registry extends CommonDBTM {

   static $rightname = 'plugin_fusioninventory_collect';

   static function getTypeName($nb=0) {
      return __('Windows registry', 'fusioninventory');
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getID() > 0) {
         if ($item->fields['type'] == 'registry') {
            return array(__('Windows registry', 'fusioninventory'));
         }
      }
      return array();
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
      $pfCollect_Registry->showRegistry($item->getID());
      $pfCollect_Registry->showForm($item->getID());
      return TRUE;
   }



   static function getHives() {
      $hives = array(
//         "HKEY_CLASSES_ROOT"   => "HKEY_CLASSES_ROOT",
//         "HKEY_CURRENT_USER"   => "HKEY_CURRENT_USER",
         "HKEY_LOCAL_MACHINE"  => "HKEY_LOCAL_MACHINE",
//         "HKEY_USERS"          => "HKEY_USERS",
//         "HKEY_CURRENT_CONFIG" => "HKEY_CURRENT_CONFIG",
//         "HKEY_DYN_DATA"       => "HKEY_DYN_DATA"
      );
      return $hives;
   }



   function showRegistry($contents_id) {

      $content = $this->find("`plugin_fusioninventory_collects_id`='".
                              $contents_id."'");

      echo "<div class='spaced'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th colspan=5>".__('Windows registry associated', 'fusioninventory')."</th>";
      echo "</tr>";
      echo "<tr>
      <th>".__("Name")."</th>
      <th>".__("Hive", "fusioninventory")."</th>
      <th>".__("Path/key", "fusioninventory")."</th>
      <th>".__("Value", "fusioninventory")."</th>
      <th>".__("Action")."</th>
      </tr>";
      foreach ($content as $data) {
         echo "<tr>";
         echo "<td align='center'>".$data['name']."</td>";
         echo "<td align='center'>".$data['hive']."</td>";
         echo "<td align='center'>".$data['path']."</td>";
         echo "<td align='center'>".$data['key']."</td>";
         echo "<td align='center'>
            <form name='form_bundle_item' action='".Toolbox::getItemTypeFormURL(__CLASS__).
                   "' method='post'>
            <input type='hidden' name='id' value='".$data['id']."'>
            <input type='image' name='delete' src='../pics/drop.png'>";
         Html::closeForm();
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      echo "</div>";
   }



   function showForm($contents_id, $options=array()) {

      $ID = 0;

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Name');
      echo "</td>";
      echo "<td>";
      echo "<input type='hidden' name='plugin_fusioninventory_collects_id'
               value='".$contents_id."' />";
      Html::autocompletionTextField($this,'name');
      echo "</td>";
      echo "<td>".__('Hive', 'fusioninventory')."</td>";
      echo "<td>";
      Dropdown::showFromArray('hive', PluginFusioninventoryCollect_Registry::getHives());
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Path', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='path' value='' size='80' />";
      echo "</td>";
      echo "<td>";
      echo __('Key', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='key' value='' />";
      echo "</td>";
      echo "</tr>\n";

      $this->showFormButtons($options);

      return TRUE;
   }
}

?>
