<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Vincent MAZZONI
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpPrinterCartridge extends PluginFusinvsnmpCommonDBTM {

   function __construct() {
      parent::__construct("glpi_plugin_fusinvsnmp_printercartridges");
   }


   function showForm($id, $options=array()) {
      global $LANG;

      $plugin_fusioninventory_snmp = new PluginFusinvsnmpSNMP();

      // get infos to get visible or not the counters
      $snmp_model_ID = $plugin_fusioninventory_snmp->GetSNMPModel($id, PRINTER_TYPE);
      // ** Get link OID fields
      $Array_Object_TypeNameConstant= $plugin_fusioninventory_snmp->GetLinkOidToFields($id, PRINTER_TYPE);
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
         $mapping->getFromDB($a_cartridge['cartridges_id']);
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