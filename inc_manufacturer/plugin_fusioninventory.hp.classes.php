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


class PluginFusionInventoryManufacturerHP extends CommonDBTM {



// Get trunk/tagged ports from OID
   function TrunkPorts ($oidvalues,$oidsModel,$ID_Device,$type) {
      // Same code as Cisco
      $manufCisco = new PluginFusionInventoryManufacturerCisco;
      return $manufCisco->TrunkPorts($oidvalues,$oidsModel,$ID_Device,$type);
   }


   function CDPPorts ($oidvalues,$oidsModel,$ID_Device,$type,$Array_multiplemac_ifIndex) {
      // Same code as Cisco
      $manufCisco = new PluginFusionInventoryManufacturerCisco;
      list($Array_cdp_ifIndex, $Array_multiplemac_ifIndex) = $manufCisco->CDPPorts($oidvalues,$oidsModel,$ID_Device,$type,$Array_multiplemac_ifIndex);
      return array ($Array_cdp_ifIndex, $Array_multiplemac_ifIndex);
   }



   function tmpConnections($oidvalues,$oidsModel,$ifIndex,$TMP_ID,$ID_Device,$type) {
      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusionInventoryLogs;
      $tmpc = new PluginFusionInventoryTmpConnections;
      $walks = new PluginFusionInventoryWalk;

      $Array_vlan = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['vtpVlanName'],1);
      $Array_vlan[] = "";
      foreach ($Array_vlan as $num=>$vlan) {
         $ArrayMACAdressTable = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dTpFdbAddress'],1,$vlan);
         foreach($ArrayMACAdressTable as $num=>$dynamicdata) {
            $oidExplode = explode(".", $dynamicdata);
            // Get by SNMP query the port number (dot1dTpFdbPort)
            if (((count($oidExplode) > 3)) AND (isset($oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan]) AND ($oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan] != "0"))) {
               // Convert MAC HEX in Decimal
               $MacAddress = plugin_fusioninventory_ifmacwalk_ifmacaddress($oidvalues[$oidsModel[0][1]['dot1dTpFdbAddress'].".".$dynamicdata][$vlan]);

               $BridgePortNumber = $oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan];
               $BridgePortifIndex = $oidvalues[$oidsModel[0][1]['dot1dBasePortIfIndex'].".".$BridgePortNumber][$vlan];
               if ($ifIndex == $BridgePortifIndex) {
                  if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync","Add TMPConnection = ".$MacAddress."(PortID ".$TMP_ID.")",$type,$ID_Device,1);
                  $tmpc->AddConnections($TMP_ID, $MacAddress);
               }
            }
         }
      }
   }


   function GetMACtoPort($ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,$ArrayPortsID,$vlan,$Array_trunk_ifIndex) {
      global $DB;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusionInventoryLogs;
      $snmp_queries = new PluginFusionInventorySNMP;
      $ptud = new PluginFusionInventoryUnknownDevice;
      $walks = new PluginFusionInventoryWalk;

      // Get VLAN name
      $ArrayvtpVlanName = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['vtpVlanName'],1,$vlan);
      // Get vlan port index
      $ArrayPortVlanIndex = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['PortVlanIndex'],1,$vlan);

      // Array : num => dynamic data
      $ArrayMACAdressTable = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dTpFdbAddress'],1,$vlan);

      foreach($ArrayMACAdressTable as $num=>$dynamicdata) {
         $oidExplode = explode(".", $dynamicdata);
         // Get by SNMP query the port number (dot1dTpFdbPort)
         if (((count($oidExplode) > 3)) AND (isset($oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan]) AND ($oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan] != "0"))) {
            // Convert MAC HEX in Decimal
            $MacAddress = plugin_fusioninventory_ifmacwalk_ifmacaddress($oidvalues[$oidsModel[0][1]['dot1dTpFdbAddress'].".".$dynamicdata][$vlan]);

            $BridgePortNumber = $oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan];
            $BridgePortifIndex = $oidvalues[$oidsModel[0][1]['dot1dBasePortIfIndex'].".".$BridgePortNumber][$vlan];
            $stop = 0;
            if (($BridgePortifIndex == "") OR ($BridgePortifIndex == "No Such Instance currently exists at this OID")) {
               $stop = 1;
            }
            if ((isset($Array_trunk_ifIndex[$BridgePortifIndex])) AND ($Array_trunk_ifIndex[$BridgePortifIndex] == "1")) {
               $stop = 1;
            }
            if ($stop == "0") {
               $ifName = $oidvalues[$oidsModel[0][1]['ifName'].".".$BridgePortifIndex][""];

               $queryPortEnd = "SELECT *
                                FROM `glpi_networking_ports`
                                WHERE `ifmac` IN ('".$MacAddress."','".strtoupper($MacAddress)."')
                                      AND (`on_device`!='".$ID_Device."'
                                           OR `device_type`!='".NETWORKING_TYPE."');";
               PluginFusioninventoryDb::lock_wire_check();
               $resultPortEnd=$DB->query($queryPortEnd);
               $sport = $ArrayPortsID[$ifName]; // Networking_Port

               if (($DB->numrows($resultPortEnd) != 0)) {
                  $dport = $DB->result($resultPortEnd, 0, "ID"); // Port of other materiel (Computer, printer...)

                  // Get vlan name of this port
                  $vlan_tmp = "";
                  foreach($ArrayvtpVlanName as $num1=>$vlan_ID) {
                     $key = 0;
                     $key = array_search($vlan_ID.".".$BridgePortifIndex, $ArrayPortVlanIndex);
                     if ($key>0) {
                        $vlan_tmp = $vlan_ID." [".$oidvalues[$oidsModel[0][1]['vtpVlanName'].".".$vlan_ID][$vlan]."]";
                     }
                  }
               // Connection between ports (wire table in DB)
                  $snmp_queries->PortsConnection($sport, $dport,$_SESSION['FK_process'],$vlan_tmp);
               } else if ($_SESSION['FK_process'] != "0") { // Mac address unknown
                  $ip_unknown = '';
                  $MacAddress_Hex = str_replace(":","",$MacAddress);
                  $MacAddress_Hex = "0x".$MacAddress_Hex;
                  if (empty($ip_unknown)) {
                     $ip_unknown = plugin_fusioninventory_search_ip_ocs_servers($MacAddress);
                  }
                  $name_unknown = plugin_fusioninventory_search_name_ocs_servers($MacAddress);
                  // Add unknown device
                  if ($name_unknown == $ip_unknown) {
                     $unknown_infos["name"] = '';
                  } else {
                     $unknown_infos["name"] = $name_unknown;
                  }
                  $newID=$ptud->add($unknown_infos);
                  // Add networking_port
                  $np=new Netport;
                  $port_add["on_device"] = $newID;
                  $port_add["device_type"] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
                  $port_add["ifaddr"] = $ip_unknown;
                  $port_add['ifmac'] = $MacAddress;
                  $dport = $np->add($port_add);
                  $snmp_queries->PortsConnection($sport, $dport,$_SESSION['FK_process'],$vlan." [".$vlan_name."]");
               }
               PluginFusioninventoryDb::lock_wire_unlock();
            }
         }
      }
   }

}

?>