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
   

   function showForm($id, $options=array()) {
      global $LANG;

      $plugin_fusioninventory_snmp = new PluginFusinvsnmpSNMP;

      // get infos to get visible or not the counters
      $snmp_model_ID = $plugin_fusioninventory_snmp->GetSNMPModel($id, PRINTER_TYPE);
      // ** Get link OID fields
      $Array_Object_TypeNameConstant= $plugin_fusioninventory_snmp->GetLinkOidToFields($id, PRINTER_TYPE);
      $mapping_name=array();
      foreach ($Array_Object_TypeNameConstant as $object=>$mapping_type_name) {
         if ((strstr($mapping_type_name, "cartridge")) OR (strstr($mapping_type_name, "toner"))) {
            switch($mapping_type_name) {
                  CASE "cartridgeblack":
                     $mapping_name[$mapping_type_name] = "1";
                     break;

                  CASE "cartridgeblackphoto":
                     $mapping_name[$mapping_type_name] = "2";
                     break;

                  CASE "tonerblack" :
                     $mapping_name[$mapping_type_name] = "3";
                     break;

                  CASE "tonerblack2" :
                     $mapping_name[$mapping_type_name] = "4";
                     break;

                  CASE "cartridgecyan":
                     $mapping_name[$mapping_type_name] = "5";
                     break;

                  CASE "cartridgecyanlight":
                     $mapping_name[$mapping_type_name] = "6";
                     break;

                  CASE "tonercyan" :
                     $mapping_name[$mapping_type_name] = "7";
                     break;

                  CASE "cartridgemagenta":
                     $mapping_name[$mapping_type_name] = "8";
                     break;

                  CASE "cartridgemagentalight":
                     $mapping_name[$mapping_type_name] = "9";
                     break;

                  CASE "tonermagenta":
                     $mapping_name[$mapping_type_name] = "10";
                     break;

                  CASE "cartridgeyellow":
                     $mapping_name[$mapping_type_name] = "11";
                     break;

                  CASE "toneryellow":
                     $mapping_name[$mapping_type_name] = "12";
                     break;

                  CASE "drumblack":
                     $mapping_name[$mapping_type_name] = "13";
                     break;

                  CASE "drumcyan":
                     $mapping_name[$mapping_type_name] = "14";
                     break;

                  CASE "drummagenta":
                     $mapping_name[$mapping_type_name] = "15";
                     break;

                  CASE "drumyellow":
                     $mapping_name[$mapping_type_name] = "16";
                     break;

                  CASE "wastetoner":
                     $mapping_name[$mapping_type_name] = "17";
                     break;

                  CASE "maintenancekit":
                     $mapping_name[$mapping_type_name] = "18";
                     break;

                  default:
                     $mapping_name[$mapping_type_name] = "19";
            }
         }
      }
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
      foreach ($mapping_name as $cartridge_name=>$val) {
         $state = $this->cartridges_state($id, $cartridge_name);
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         $mapfields = $mapping->get('Printer', $cartridge_name);
         if ($mapfields != false) {
            echo $LANG['plugin_fusioninventory']['mapping'][$mapfields['shortlocale']];
         }
         echo " : ";
         echo "</td>";
         echo "<td align='center'>";
         echo "</td>";
         echo "<td align='center'>";
         PluginFusioninventoryDisplay::bar($state['state']);
         echo "</td>";
         echo "</tr>";
      }
      echo "</table></form>";
      echo "</div>";
   }


   function cartridges_state($FK_printers, $object_name) {

		$datas = array();
      $a_cartridge = $this->find("`FK_printers`='".$FK_printers."' AND `object_name`='".$object_name."'");
      if (count($a_cartridge) == '0') {
         $datas['FK_cartridges'] = "";
         $datas['state'] = "";
      } else {
         foreach($a_cartridge as $cartridge_id=>$data) {
            $datas['FK_cartridges'] = $data['FK_cartridges'];
            $datas['state'] = $data['state'];
            if (($datas['state']) < 0) {
               $datas['state'] = "0";
            }
         }
      }
		return $datas;
	}
      
}

?>