<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: Vincent MAZZONI
   Co-authors of file: David DURIEUX
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpPrinterCartridge extends PluginFusinvsnmpCommonDBTM {

   function __construct() {
      parent::__construct("glpi_plugin_fusinvsnmp_printercartridges");
   }



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
      echo "</table></form>";
      echo "</div>";
   }
}

?>