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

class PluginFusioninventoryIgnoredimportdevice extends CommonDBTM {
   
   
   static function getTypeName() {
      global $LANG;
      
   }
   
   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "iprange", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "iprange", "r");
   }

   
   function showDevices() {
      global $DB,$LANG;
      
      $rule = new PluginFusioninventoryRuleImportEquipment();
      $entity = new Entity();
      
      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }
      
      $nb_elements = countElementsInTableForMyEntities($this->getTable());
      echo "<table class='tab_cadre' >";
      echo "<tr>";
      echo "<td colspan='8'>";
      Html::printAjaxPager('',$start,$nb_elements);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<th>";
      echo $LANG['common'][16];
      echo "</th>";
      echo "<th>";
      echo $LANG['rulesengine'][102];
      echo "</th>";
      echo "<th>";
      echo $LANG['common'][27];
      echo "</th>";
      echo "<th>";
      echo $LANG['common'][17];
      echo "</th>";
      echo "<th>";
      echo $LANG['entity'][0];
      echo "</th>";
      echo "<th>";
      echo $LANG['networking'][14];
      echo "</th>";
      echo "<th>";
      echo $LANG['networking'][15];
      echo "</th>";
      echo "<th>";
      echo $LANG['plugin_fusioninventory']['task'][26];
      echo "</th>";
      echo "</tr>";
      
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE ".getEntitiesRestrictRequest("", $this->getTable(), '', '', $this->maybeRecursive())."
         ORDER BY `date`DESC
         LIMIT ".intval($start).",".intval($_SESSION['glpilist_limit']);
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo $data['name'];
         echo "</td>";
         
         echo "<td align='center'>";
         $rule->getFromDB($data['rules_id']);
         echo $rule->getLink(1);
         echo "</td>";
         
         echo "<td align='center'>";
         echo Html::convDateTime($data['date']);
         echo "</td>";
         
         echo "<td align='center'>";
         $itemtype = $data['itemtype'];
         if ($itemtype != '') {
            $item = new $itemtype();
            echo $item->getTypeName();
         } else {
            echo NOT_AVAILABLE;
         }
         echo "</td>";
         
         echo "<td align='center'>";
         $entity->getFromDB($data['entities_id']);
         echo $entity->getName();
         echo "</td>";
         
         echo "<td align='center'>";
         $a_ip = importArrayFromDB($data['ip']);
         echo implode("<br/>", $a_ip);
         echo "</td>";
         
         echo "<td align='center'>";
         $a_mac = importArrayFromDB($data['mac']);
         echo implode("<br/>", $a_mac);
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
      
      echo "<tr>";
      echo "<td colspan='7'>";
      Html::printAjaxPager('',$start,$nb_elements);
      echo "</td>";
      echo "</tr>";
      
      echo "</table>";
      
   }
}


?>