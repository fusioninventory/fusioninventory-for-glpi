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


class PluginFusionInventoryManufacturerFoundryNetworks extends CommonDBTM {

   function ListVirtualPorts($sysdescr,$PortName) {

      return false;
   }

  

   function GetMACtoPort($ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,$ArrayPortsID,$vlan,$Array_trunk_ifIndex) {
      global $DB;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusionInventoryLogs;
      $snmp_queries = new PluginFusionInventorySNMP;
      $ptud = new PluginFusionInventoryUnknownDevice;
      $walks = new PluginFusionInventoryWalk;


      // Get vlan name
      $vlan_name = "";
      if (!empty($vlan)) {
         $vlan_name = $oidvalues[$oidsModel[0][1]['vtpVlanName'].".".$vlan][""];
      }

      // Get by SNMP query the mac addresses and IP (ipNetToMediaPhysAddress)
      $ArrayIPMACAdressePhys = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['ipNetToMediaPhysAddress'],1,$vlan);

      if (empty($ArrayIPMACAdressePhys)) {
         return;
      }
      foreach($ArrayIPMACAdressePhys as $num=>$dynamicdata) {
         $oidExplode = explode(".", $dynamicdata);

         $BridgePortifIndex = $oidExplode[0];

         if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync","BridgePortifIndex = ".$BridgePortifIndex,$type,$ID_Device,1);

         $ifName = $oidvalues[$oidsModel[0][1]['ifName'].".".$BridgePortifIndex][""];

         if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync","** Interface = ".$ifName,$type,$ID_Device,1);

         // Convert MAC HEX in Decimal
         $MacAddress = plugin_fusioninventory_ifmacwalk_ifmacaddress($oidvalues[$oidsModel[0][1]['ipNetToMediaPhysAddress'].".".$dynamicdata][$vlan]);

         if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync","Vlan = ".$vlan,$type,$ID_Device,1);
         if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync","Mac address = ".$MacAddress,$type,$ID_Device,1);

         $queryPortEnd = "";

         if (!isset($Array_trunk_ifIndex[$BridgePortifIndex])) {
            // Verify if mac adress isn't the same than this port
            $query_verif = "SELECT `ifmac`
                            FROM `glpi_networking_ports`
                            WHERE `ID`='".$ArrayPortsID[$ifName]."';";
            $result_verif = $DB->query($query_verif);
            $data_verif = $DB->fetch_assoc($result_verif);
              if ((($data_verif['ifmac'] == $MacAddress) OR ($data_verif['ifmac'] == strtoupper($MacAddress))) AND ($data_verif['ifmac'] != "")) {
                 $queryPortEnd = "";
              } else {
                  if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync","Mac address OK",$type,$ID_Device,1);

                  $queryPortEnd = "SELECT *
                                   FROM `glpi_networking_ports`
                                   WHERE `ifmac` IN ('".$MacAddress."',
                                                     '".strtoupper($MacAddress)."')
                                         AND (`on_device`!='".$ID_Device."'
                                              OR `device_type`!='".NETWORKING_TYPE."');";
              }
         }

         if (($queryPortEnd != "")) {
            PluginFusioninventoryDb::lock_wire_check();
            $resultPortEnd=$DB->query($queryPortEnd);
            $traitement = 1;
            if ($vlan != "") {
               if (isset($array_port_trunk[$ArrayPortsID[$ifName]]) && $array_port_trunk[$ArrayPortsID[$ifName]] == "1")
                  $traitement = 0;
            }

            if (!isset($ArrayPortsID[$ifName])) {
               $traitement = 0;
            } else {
               $sport = $ArrayPortsID[$ifName]; // Networking_Port
               if (($DB->numrows($resultPortEnd) != 0) && ($traitement == "1")) {
                  $dport = $DB->result($resultPortEnd, 0, "ID"); // Port of other materiel (Computer, printer...)
                  // Connection between ports (wire table in DB)
                  $snmp_queries->PortsConnection($sport, $dport,$_SESSION['FK_process'],$vlan." [".$vlan_name."]");
               } else if ($traitement == "1") {

                  // Mac address unknown
                  if ($_SESSION['FK_process'] != "0") {
                     $ip_unknown = '';
                     $MacAddress_Hex = str_replace(":","",$MacAddress);
                     $MacAddress_Hex = "0x".$MacAddress_Hex;

                     $ip_unknown = $oidExplode[1].".".$oidExplode[2].".".$oidExplode[3].".".$oidExplode[4];

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
                     if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync","INCONNU",$type,$ID_Device,1);
                     $snmp_queries->PortsConnection($sport, $dport,$_SESSION['FK_process'],$vlan." [".$vlan_name."]");
                  }
               }
            }
            PluginFusioninventoryDb::lock_wire_unlock();
         }
      }
   }
}

?>