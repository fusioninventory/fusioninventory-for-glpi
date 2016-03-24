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

class PluginFusioninventoryCollect_Wmi extends CommonDBTM {

   static $rightname = 'plugin_fusioninventory_collect';

   static function getTypeName($nb=0) {
      return __('Windows WMI', 'fusioninventory');
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getID() > 0) {
         if ($item->fields['type'] == 'wmi') {
            return array(__('Windows WMI', 'fusioninventory'));
         }
      }
      return array();
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      $pfCollect_Wmi->showWmi($item->getID());
      $pfCollect_Wmi->showForm($item->getID());
      return TRUE;
   }



   function showWmi($contents_id) {

      $content = $this->find("`plugin_fusioninventory_collects_id`='".
                              $contents_id."'");

      echo "<div class='spaced'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th colspan=5>".__('Windows WMI associated', 'fusioninventory')."</th>";
      echo "</tr>";
      echo "<tr>
      <th>".__("Name")."</th>
      <th>".__("Moniker", "fusioninventory")."</th>
      <th>".__("Class", "fusioninventory")."</th>
      <th>".__("Properties", "fusioninventory")."</th>
      <th>".__("Action")."</th>
      </tr>";
      foreach ($content as $data) {
         echo "<tr>";
         echo "<td align='center'>".$data['name']."</td>";
         echo "<td align='center'>".$data['moniker']."</td>";
         echo "<td align='center'>".$data['class']."</td>";
         echo "<td align='center'>".$data['properties']."</td>";
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
      echo "<input type='text' name='name' value='' />";
      echo "</td>";
      echo "<td>".__('moniker', 'fusioninventory')."</td>";
      echo "<td>";
      echo "<input type='text' name='moniker' value='' size='50' />";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Class', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='class' value='' />";
      echo "</td>";
      echo "<td>";
      echo __('Properties', 'fusioninventory');
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='properties' value='' size='50' />";
      echo "</td>";
      echo "</tr>\n";

      $this->showFormButtons($options);

      return TRUE;
   }
}

?>
