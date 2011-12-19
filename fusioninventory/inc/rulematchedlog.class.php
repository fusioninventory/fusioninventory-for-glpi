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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

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
   
   
   static function getTypeName() {
      global $LANG;
      
   }
   
   function canCreate() {
      return true;
   }


   function canView() {
      return true;
   }
   
   
   
   function cleanOlddata($items_id, $itemtype) {
      global $DB;
      
      $query = "DELETE FROM `glpi_plugin_fusioninventory_rulematchedlogs`
         WHERE `id` IN (
            SELECT `id` FROM `glpi_plugin_fusioninventory_rulematchedlogs`
            WHERE `items_id` = '".$items_id."'
               AND `itemtype` = '".$itemtype."'
            ORDER BY `date` DESC
            LIMIT 30,50000)";
      $DB->query($query);
   }
   
   
   
   function showForm($items_id, $itemtype) {
      global $LANG;
      
      $rule = new Rule();
      $pfAgent = new PluginFusioninventoryAgent();
      
      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
      
      echo "<tr>";
      echo "<th colspan='3'>";
      echo $LANG['plugin_fusioninventory']['rules'][20];
      echo "</th>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<th>";
      echo $LANG['common'][27];
      echo "</th>";
      echo "<th>";
      echo $LANG['rulesengine'][102];
      echo "</th>";
      echo "<th>";
      echo $LANG['plugin_fusioninventory']['agents'][28];
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
         echo "</tr>";
      }
      echo "</table>";
      
      
   }
   
}


?>