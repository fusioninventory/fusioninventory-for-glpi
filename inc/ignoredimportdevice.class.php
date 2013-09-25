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

class PluginFusioninventoryIgnoredimportdevice extends CommonDBTM {


   static function getTypeName($nb=0) {
      return __('Equipment ignored on import', 'fusioninventory');
   }

   static function canCreate() {
      return PluginFusioninventoryProfile::haveRight("iprange", "w");
   }


   static function canView() {
      return PluginFusioninventoryProfile::haveRight("iprange", "r");
   }


   function showDevices() {
      global $DB;

      $rule = new PluginFusioninventoryInventoryRuleImport();
      $entity = new Entity();

      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      $nb_elements = countElementsInTableForMyEntities($this->getTable());
      
      Html::printPager($start, 
                       $nb_elements, 
                       Toolbox::getItemTypeSearchURL(
                               'PluginFusioninventoryIgnoredimportdevice'
                       ),
                       "");

      echo "<br/><table class='tab_cadrehov' >";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo __('Name');
      echo "</th>";

      echo "<th>";
      echo __('Rule name');
      echo "</th>";

      echo "<th>";
      echo __('Date');
      echo "</th>";

      echo "<th>";
      echo __('Type');
      echo "</th>";

      echo "<th>";
      echo __('Entity');
      echo "</th>";

      echo "<th>";
      echo __('Serial number');
      echo "</th>";

      echo "<th>";
      echo __('UUID');
      echo "</th>";

      echo "<th>";
      echo __('IP');
      echo "</th>";

      echo "<th>";
      echo __('MAC');
      echo "</th>";

      echo "<th>";
      echo __('Module', 'fusioninventory');
      echo "</th>";
      echo "</tr>";

      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE ".getEntitiesRestrictRequest("",
                                            $this->getTable(),
                                            '',
                                            '',
                                            $this->maybeRecursive())."
         ORDER BY `date`DESC
         LIMIT ".intval($start).", ".intval($_SESSION['glpilist_limit']);
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
         echo $data['serial'];
         echo "</td>";

         echo "<td align='center'>";
         echo $data['uuid'];
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

      echo "</table><br/>";

      Html::printPager($start, 
                       $nb_elements, 
                       Toolbox::getItemTypeSearchURL(
                               'PluginFusioninventoryIgnoredimportdevice'
                       ),
                       "");
   }
}

?>
