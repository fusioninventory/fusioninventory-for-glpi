<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


class PluginTrackerManufacturerHP extends CommonDBTM {



// Get trunk/tagged ports from OID
   function TrunkPorts ($oidvalues,$oidsModel,$ID_Device,$type) {
      // Same code as Cisco
      $manufCisco = new PluginTrackerManufacturerCisco;
      return $manufCisco->TrunkPorts($oidvalues,$oidsModel,$ID_Device,$type);
   }


   function CDPPorts ($oidvalues,$oidsModel,$ID_Device,$type,$Array_multiplemac_ifIndex) {
      // Same code as Cisco
      $manufCisco = new PluginTrackerManufacturerCisco;
      list($Array_cdp_ifIndex, $Array_multiplemac_ifIndex) = $manufCisco->CDPPorts($oidvalues,$oidsModel,$ID_Device,$type,$Array_multiplemac_ifIndex);
      return array ($Array_cdp_ifIndex, $Array_multiplemac_ifIndex);
   }



   function tmpConnections($oidvalues,$oidsModel,$ifIndex,$TMP_ID,$ID_Device,$type) {
      $logs = new PluginTrackerLogs;
      $tmpc = new PluginTrackerTmpConnections;
      $walks = new PluginTrackerWalk;

      $Array_vlan = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['vtpVlanName'],1);
      $Array_vlan[] = "";
      foreach ($Array_vlan as $num=>$vlan) {
         $ArrayMACAdressTable = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dTpFdbAddress'],1,$vlan);
         foreach($ArrayMACAdressTable as $num=>$dynamicdata) {
            $oidExplode = explode(".", $dynamicdata);
            // Get by SNMP query the port number (dot1dTpFdbPort)
            if (((count($oidExplode) > 3)) AND (isset($oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan]) AND ($oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan] != "0"))) {
               // Convert MAC HEX in Decimal
               $MacAddress = str_replace("0x","",$oidvalues[$oidsModel[0][1]['dot1dTpFdbAddress'].".".$dynamicdata][$vlan]);
               $MacAddress_tmp = str_split($MacAddress, 2);
               $MacAddress = $MacAddress_tmp[0];
               for($i = 1 ; $i < count($MacAddress_tmp) ; $i++) {
                  $MacAddress .= ":".$MacAddress_tmp[$i];
               }
               $BridgePortNumber = $oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan];
               $BridgePortifIndex = $oidvalues[$oidsModel[0][1]['dot1dBasePortIfIndex'].".".$BridgePortNumber][$vlan];
               if ($ifIndex == $BridgePortifIndex) {
                  $logs->write("tracker_fullsync","Add TMPConnection = ".$MacAddress."(PortID ".$TMP_ID.")",$type."][".$ID_Device,1);
                  $tmpc->AddConnections($TMP_ID, $MacAddress);
               }
            }
         }
      }
   }

}

?>