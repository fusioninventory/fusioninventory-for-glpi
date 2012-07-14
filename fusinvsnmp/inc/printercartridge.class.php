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
   @author    Vincent Mazzoni
   @co-author David Durieux
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

class PluginFusinvsnmpPrinterCartridge extends CommonDBTM {

   

   function showForm($id, $options=array()) {
      global $LANG;

      // ** Get link OID fields
      $mapping_name=array();
      $a_cartridges = $this->find("`printers_id`='".$id."'");

      echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'
                 action=\"".$options['target']."\">";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center' colspan='3'>";
      echo $LANG["cartridges"][16];
      echo "</th>";
      echo "</tr>";

      asort($mapping_name);
      $mapping = new PluginFusioninventoryMapping();
      foreach ($a_cartridges as $a_cartridge) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         $mapping->getFromDB($a_cartridge['plugin_fusioninventory_mappings_id']);
         echo $LANG['plugin_fusinvsnmp']['mapping'][$mapping->fields['locale']];
         echo " : ";
         echo "</td>";
         echo "<td align='center'>";
         echo "</td>";
         echo "<td align='center'>";
         PluginFusioninventoryDisplay::bar($a_cartridge['state']);
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      Html::closeForm();
      echo "</div>";
   }
}

?>