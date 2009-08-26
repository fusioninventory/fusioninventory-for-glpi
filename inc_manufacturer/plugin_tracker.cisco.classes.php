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


class PluginTrackerManufacturerCisco extends CommonDBTM {

   function ListVirtualPorts($sysdescr,$PortName) {
      if(strstr($sysdescr,"Cisco")) {
         if (strstr($PortName, 'VLAN-')) {
            return true;
         } else if (strstr($PortName, 'Vl')) {
            return true;
         }
      }
      return false;
   }


   // Get trunk ports from OID
   function TrunkPorts ($oidvalues,$oidsModel,$ID_Device,$type) {
      $Array_trunk_ifIndex = array();

      $logs = new PluginTrackerLogs;
      $walks = new PluginTrackerWalk;

      $Array_vlan = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['vtpVlanName'],1);
      $Array_vlan[] = "";
      foreach ($Array_vlan as $num=>$vlan) {
         // Get vlan name
         if (empty($vlan)) {
            $vlan_name = "";
         } else {
            $vlan_name = $oidvalues[$oidsModel[0][1]['vtpVlanName'].".".$vlan][""];
         }
         $Arraytrunktype = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['vlanTrunkPortDynamicStatus'],1,$vlan);

         foreach($Arraytrunktype as $IDtmp=>$snmpportID) {
            if ((isset($oidvalues[".1.3.6.1.2.1.1.1.0"][""])) AND (strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"Cisco"))) {
               if ((isset($oidvalues[$oidsModel[0][1]['vlanTrunkPortDynamicStatus'].".".$snmpportID][$vlan]))
                  AND ($oidvalues[$oidsModel[0][1]['vlanTrunkPortDynamicStatus'].".".$snmpportID][$vlan] == "1")) {

                  $Array_trunk_ifIndex[$snmpportID] = 1;
                  $logs->write("tracker_fullsync","Trunk = ".$snmpportID,$type."][".$ID_Device,1);
                  //$trunk_no_cdp[$snmpportID] = 1;
               }
            } else if ((isset($oidvalues[".1.3.6.1.2.1.1.1.0"][""])) AND (strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"ProCurve J"))) {
               if ($oidvalues[$oidsModel[0][1]['vlanTrunkPortDynamicStatus'].".".$snmpportID][$vlan] == "2") {
                  $Array_trunk_ifIndex[$snmpportID] = 1;
                  $logs->write("tracker_fullsync","Trunk = ".$snmpportID,$type."][".$ID_Device,1);
                  //$trunk_no_cdp[$snmpportID] = 1;
               }
            }
         }
      }
      return $Array_trunk_ifIndex;
   }

   

   function CDPPorts ($oidvalues,$oidsModel,$ID_Device,$type,$Array_multiplemac_ifIndex) {
      global $DB;
      
      $logs = new PluginTrackerLogs;
      $snmp_queries = new PluginTrackerSNMP;
      $unknown = new PluginTrackerUnknown;
      $walks = new PluginTrackerWalk;

      // Get by SNMP query the IP addresses of the switch connected ($Array_trunk_IP_hex)
      $Array_trunk_IP_hex_result = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['cdpCacheAddress'],1);

      // Get by SNMP query the Name of port (ifDescr) : snmp port ID => ifDescr of port of switch connected on this port
      //$Array_trunk_ifDescr_result = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['cdpCacheDevicePort'],1);
      if (!empty($Array_trunk_IP_hex_result)) {
         foreach($Array_trunk_IP_hex_result AS $num=>$snmpportID) {
            $trunk_IP = $oidvalues[$oidsModel[0][1]['cdpCacheAddress'].".".$snmpportID][""];

            // Convert IP HEX in Decimal
            if (preg_match("/^0x/",$trunk_IP)) {
               $trunk_IP = str_replace("0x","",$trunk_IP);
               $hex_ = preg_replace("/[^0-9a-fA-F]/","", $trunk_IP);
               $trunk_IP_tmp = '';
               for($i = 0 ; $i < strlen($hex_) ; $i = $i + 2) {
                  $trunk_IP_tmp .= chr(hexdec(substr($hex_, $i, 2)));
               }
               $ip_switch_trunk = ord(substr($trunk_IP_tmp, 0, 1));
               for($i = 1 ; $i < strlen($trunk_IP_tmp) ; $i = $i + 1) {
                  $ip_switch_trunk .= ".".ord(substr($trunk_IP_tmp, $i, 1));
               }
            } else {
               $ip_switch_trunk = $trunk_IP;
            }
            if (substr_count($ip_switch_trunk,'.') == 3) {
               $explode = explode(".", $snmpportID);
               $ifIndex = $explode[0];
               $end_Number = $explode[1];

              // $Array_trunk_ifIndex[$ifIndex] = 1;
               $Array_cdp_ifIndex[$ifIndex] = 1;
               if (isset($Array_multiplemac_ifIndex[$ifIndex])) {
                  unset($Array_multiplemac_ifIndex[$ifIndex]);
               }
               $logs->write("tracker_fullsync","ifIndex = ".$ifIndex,$type."][".$ID_Device,1);

               // Search port of switch connected on this port and connect it if not connected
               $logs->write("tracker_fullsync","ip = ".$ip_switch_trunk." / ifdescr = ".$oidvalues[$oidsModel[0][1]['cdpCacheDevicePort'].".".$snmpportID][""],$type."][".$ID_Device,1);
               $PortID = $snmp_queries->getPortIDfromDeviceIP($ip_switch_trunk, $oidvalues[$oidsModel[0][1]['cdpCacheDevicePort'].".".$snmpportID][""]);

               $query = "SELECT glpi_networking_ports.ID FROM glpi_networking_ports
               WHERE logical_number='".$ifIndex."'
                  AND device_type='".NETWORKING_TYPE."'
                  AND on_device='".$ID_Device."' ";
               $result = $DB->query($query);
               $data = $DB->fetch_assoc($result);

               if ((!empty($data["ID"])) AND (!empty($PortID))) {
                  //$tmpc->UpdatePort($ID_Device,$data["ID"],1);
                  $snmp_queries->PortsConnection($data["ID"], $PortID,$_SESSION['FK_process']);
               } else if ((!empty($data["ID"])) AND (empty($PortID))) { // Unknow IP of switch connected to this port
                  $unknown_infos["name"] = '';
                  $newID=$unknown->add($unknown_infos);
                  // Add networking_port
                  $np=new Netport;
                  $port_add["on_device"] = $newID;
                  $port_add["device_type"] = PLUGIN_TRACKER_MAC_UNKNOWN;
                  $port_add["ifaddr"] = $ip_switch_trunk;
                  $port_add['ifmac'] = '';
                  $dport = $np->add($port_add);
                  $snmp_queries->PortsConnection($data["ID"], $dport,$_SESSION['FK_process'],$vlan." [".$vlan_name."]");
                  //$Threads->unknownMAC($_SESSION['FK_process'],$data["ID"],$ip_switch_trunk,$data["ID"]);
               }
            }
         }
      }
      return array ($Array_cdp_ifIndex, $Array_multiplemac_ifIndex);
   }



   function tmpConnections($oidvalues,$oidsModel,$ifIndex,$TMP_ID,$ID_Device,$type) {
      $logs = new PluginTrackerLogs;
      $tmpc = new PluginTrackerTmpConnections;
      $walks = new PluginTrackerWalk;

      $Array_vlan = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['vtpVlanName'],1);
      $Array_vlan[] = "";
      foreach ($Array_vlan as $num=>$vlan) {
         $BridgePortifIndex = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dBasePortIfIndex'],1,$vlan);
         foreach($BridgePortifIndex as $num=>$BridgePortNumber) {
            $ifIndexFound = $oidvalues[$oidsModel[0][1]['dot1dBasePortIfIndex'].".".$BridgePortNumber][$vlan];
            if ($ifIndexFound == $ifIndex) {
               // Search in dot1dTpFdbPort the dynamicdata associate to this BridgePortNumber
               $ArrayBridgePortNumber = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dTpFdbPort'],1,$vlan);
               foreach($ArrayBridgePortNumber as $num=>$dynamicdata) {
                  $BridgePortifIndexFound = $oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan];
                  if ($BridgePortifIndexFound == $BridgePortNumber) {
                     $MacAddress = str_replace("0x","",$oidvalues[$oidsModel[0][1]['dot1dTpFdbAddress'].".".$dynamicdata][$vlan]);
                     $MacAddress_tmp = str_split($MacAddress, 2);
                     $MacAddress = $MacAddress_tmp[0];
                     for($i = 1 ; $i < count($MacAddress_tmp) ; $i++) {
                        $MacAddress .= ":".$MacAddress_tmp[$i];
                     }
                     $logs->write("tracker_fullsync","Add TMPConnection = ".$MacAddress."(PortID ".$TMP_ID.")",$type."][".$ID_Device,1);
                     $tmpc->AddConnections($TMP_ID, $MacAddress);
                  }
               }
            }
         }
      }
   }


   function GetMACtoPort($ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,$ArrayPortsID,$vlan,$Array_trunk_ifIndex) {
      GLOBAL $DB;

      $logs = new PluginTrackerLogs;
      $snmp_queries = new PluginTrackerSNMP;
      $unknown = new PluginTrackerUnknown;
      $walks = new PluginTrackerWalk;


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

      // Array : num => dynamic data
      $ArrayMACAdressTable = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dTpFdbAddress'],1,$vlan);

      foreach($ArrayMACAdressTable as $num=>$dynamicdata) {
         $oidExplode = explode(".", $dynamicdata);
         // Get by SNMP query the port number (dot1dTpFdbPort)
         if (((count($oidExplode) > 3)) AND (isset($oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan]))) {
            $BridgePortNumber = $oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan];

            $logs->write("tracker_fullsync","****************",$type."][".$ID_Device,1);
            $logs->write("tracker_fullsync","BRIDGEPortNumber = ".$BridgePortNumber,$type."][".$ID_Device,1);

            $BridgePortifIndex = $oidvalues[$oidsModel[0][1]['dot1dBasePortIfIndex'].".".$BridgePortNumber][$vlan];
            $stop = 0;
            if (($BridgePortifIndex == "") OR ($BridgePortifIndex == "No Such Instance currently exists at this OID")) {
               $stop = 1;
            }

            if ((isset($Array_trunk_ifIndex[$BridgePortifIndex])) AND ($Array_trunk_ifIndex[$BridgePortifIndex] == "1")) {
               $stop = 1;
            }
            if ($stop == "0") {
               $logs->write("tracker_fullsync","BridgePortifIndex = ".$BridgePortifIndex,$type."][".$ID_Device,1);

               $ifName = $oidvalues[$oidsModel[0][1]['ifName'].".".$BridgePortifIndex][""];

               $logs->write("tracker_fullsync","** Interface = ".$ifName,$type."][".$ID_Device,1);

               // Convert MAC HEX in Decimal
               $MacAddress = str_replace("0x","",$oidvalues[$oidsModel[0][1]['dot1dTpFdbAddress'].".".$dynamicdata][$vlan]);
               $MacAddress_tmp = str_split($MacAddress, 2);
               $MacAddress = $MacAddress_tmp[0];
               for($i = 1 ; $i < count($MacAddress_tmp) ; $i++) {
                  $MacAddress .= ":".$MacAddress_tmp[$i];
               }
               // Verify Trunk

               $logs->write("tracker_fullsync","Vlan = ".$vlan,$type."][".$ID_Device,1);
               $logs->write("tracker_fullsync","Mac address = ".$MacAddress,$type."][".$ID_Device,1);

               $queryPortEnd = "";

               if (!isset($Array_trunk_ifIndex[$BridgePortifIndex])) {
                  // Verify if mac adress isn't the same than this port
                  $query_verif = "SELECT ifmac
                  FROM glpi_networking_ports
                  WHERE ID='".$ArrayPortsID[$ifName]."' ";
                  $result_verif = $DB->query($query_verif);
                  $data_verif = $DB->fetch_assoc($result_verif);
                    if ((($data_verif['ifmac'] == $MacAddress) OR ($data_verif['ifmac'] == strtoupper($MacAddress))) AND ($data_verif['ifmac'] != "")) {
                       $queryPortEnd = "";
                    } else {
                        $logs->write("tracker_fullsync","Mac address OK",$type."][".$ID_Device,1);

                        $queryPortEnd = "SELECT * FROM glpi_networking_ports
                        WHERE ifmac IN ('".$MacAddress."','".strtoupper($MacAddress)."')
                           AND (on_device!='".$ID_Device."'
                           || device_type!='".NETWORKING_TYPE."') ";
                    }
               }

               if (($queryPortEnd != "")) {
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
                           foreach ($ArrayIPMACAdressePhys as $num=>$ips) {
                              if ($oidvalues[$oidsModel[0][1]['ipNetToMediaPhysAddress'].".".$ips][$vlan] == $MacAddress_Hex)
                                 $ip_unknown = preg_replace("/^1\./","",$ips);
                           }

                           // Search IP in OCS IPdiscover if OCS servers specified
                           if (empty($ip_unknown)) {
                              $ip_unknown = plugin_tracker_search_ip_ocs_servers($MacAddress);
                           }
                           $name_unknown = plugin_tracker_search_name_ocs_servers($MacAddress);
                           // Add unknown device
                           if ($name_unknown == $ip_unknown) {
                              $unknown_infos["name"] = '';
                           } else {
                              $unknown_infos["name"] = $name_unknown;
                           }
                           $newID=$unknown->add($unknown_infos);
                           // Add networking_port
                           $np=new Netport;
                           $port_add["on_device"] = $newID;
                           $port_add["device_type"] = PLUGIN_TRACKER_MAC_UNKNOWN;
                           $port_add["ifaddr"] = $ip_unknown;
                           $port_add['ifmac'] = $MacAddress;
                           $dport = $np->add($port_add);
                           $snmp_queries->PortsConnection($sport, $dport,$_SESSION['FK_process'],$vlan." [".$vlan_name."]");
                        }
                     }
                  }
               }
            }
         }
      }
   }


}

?>