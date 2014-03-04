<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2011 FusionInventory team
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

class PluginFusioninventoryRulematchedlog extends CommonDBTM {

   static $rightname = 'plugin_fusioninventory_ruleimport';

   static function getTypeName($nb=0) {

   }



   /**
    * @param CommonDBTM $item
   **/
   static function countForItem(CommonDBTM $item) {

      return countElementsInTable('glpi_plugin_fusioninventory_rulematchedlogs',
                                  "`itemtype` = '".$item->getType()."'
                                   AND `items_id` ='".$item->getField('id')."'");
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $array_ret = array();
      if ($item->getType() == 'PluginFusioninventoryAgent') {
         if (Session::haveRight('plugin_fusioninventory_agent', READ)) {
             $array_ret[0] = self::createTabEntry(__('Import information', 'fusioninventory'));

         }
      } else {
         $cnt = PluginFusioninventoryRulematchedlog::countForItem($item);
         $array_ret[1] = self::createTabEntry(__('Import information', 'fusioninventory'), $cnt);

      }
      return $array_ret;
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pfRulematchedlog = new self();
      if ($tabnum == '0') {
         if ($item->getID() > 0) {
            $pfRulematchedlog->showFormAgent($item->getID());
         }
      } else if ($tabnum == '1') {
         if ($item->getID() > 0) {
            $pfRulematchedlog->showForm($item->getID(), $item->getType());

            $itemtype = '';
            switch (get_class($item)) {

               case 'Computer':
                  $itemtype = new PluginFusioninventoryInventoryComputerComputer();
                  break;

               case 'Printer':
                  $itemtype = new PluginFusioninventoryPrinter();
                  break;

               case 'NetworkEquipment':
                  $itemtype = new PluginFusioninventoryNetworkEquipment();
                  break;

            }

            if (is_object($itemtype)
                    && $itemtype->canView()) {
               $itemtype->displaySerializedInventory($item->getID());
            }
         }
      }
      return TRUE;
   }




   function cleanOlddata($items_id, $itemtype) {
      global $DB;

      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_rulematchedlogs`
            WHERE `items_id` = '".$items_id."'
               AND `itemtype` = '".$itemtype."'
            ORDER BY `date` DESC
            LIMIT 30, 50000";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $this->delete(array('id'=>$data['id']));
      }
   }



   function showForm($items_id, $itemtype) {

      $rule = new PluginFusioninventoryInventoryRuleImport();
      $pfAgent = new PluginFusioninventoryAgent();

      echo "<table class='tab_cadre_fixe' cellpadding='1'>";

      echo "<tr>";
      echo "<th colspan='4'>";
      echo __('Rule import logs', 'fusioninventory');

      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>";
      echo __('Date');

      echo "</th>";
      echo "<th>";
      echo __('Rule name');

      echo "</th>";
      echo "<th>";
      echo __('Agent', 'fusioninventory');

      echo "</th>";
      echo "<th>";
      echo __('Module', 'fusioninventory');

      echo "</th>";
      echo "</tr>";

      $allData = $this->find("`itemtype`='".$itemtype."'
                              AND `items_id`='".$items_id."'", "`date` DESC");
      foreach ($allData as $data) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo Html::convDateTime($data['date']);
         echo "</td>";
         echo "<td align='center'>";
         if ($rule->getFromDB($data['rules_id'])) {
            echo $rule->getLink(1);
         }
         echo "</td>";
         echo "<td align='center'>";
         if ($pfAgent->getFromDB($data['plugin_fusioninventory_agents_id'])) {
            echo $pfAgent->getLink(1);
         }
         echo "</td>";
         echo "<td>";
         $a_methods = PluginFusioninventoryStaticmisc::getmethods();
         foreach ($a_methods as $mdata) {
            if ($mdata['method'] == $data['method']) {
               echo $mdata['name'];
            }
         }
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";


   }



   function showFormAgent($agents_id) {

      $rule = new PluginFusioninventoryInventoryRuleImport();

      echo "<table class='tab_cadre_fixe' cellpadding='1'>";

      echo "<tr>";
      echo "<th colspan='5'>";
      echo __('Rule import logs', 'fusioninventory');

      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>";
      echo __('Date');

      echo "</th>";
      echo "<th>";
      echo __('Rule name');

      echo "</th>";
      echo "<th>";
      echo __('Item type');

      echo "</th>";
      echo "<th>";
      echo __('Item');

      echo "</th>";
      echo "<th>";
      echo __('Module', 'fusioninventory');

      echo "</th>";
      echo "</tr>";

      $allData = $this->find("`plugin_fusioninventory_agents_id`='".$agents_id."'", "`date` DESC");
      foreach ($allData as $data) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo Html::convDateTime($data['date']);
         echo "</td>";
         echo "<td align='center'>";
         if ($rule->getFromDB($data['rules_id'])) {
            echo $rule->getLink(1);
         }
         echo "</td>";
         echo "<td align='center'>";
         $itemtype = $data['itemtype'];
         $item = new $itemtype();
         echo $item->getTypeName();
         echo "</td>";
         echo "<td align='center'>";
         if ($item->getFromDB($data['items_id'])) {
            echo $item->getLink(1);
         }
         echo "</td>";
         echo "<td>";
         $a_methods = PluginFusioninventoryStaticmisc::getmethods();
         foreach ($a_methods as $mdata) {
            if ($mdata['method'] == $data['method']) {
               echo $mdata['name'];
            }
         }
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
   }

}

?>
