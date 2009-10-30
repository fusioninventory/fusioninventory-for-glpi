<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginTrackerTmpConnections extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_tracker_tmp_netports";
		$this->type = PLUGIN_TRACKER_SNMP_TMP_NETPORTS;
	}



	function UpdatePort($FK_networking,$FK_networking_port,$cdp=0) {
		global $DB;

      if (isset($FK_networking_port)) {
         $query = "SELECT * FROM glpi_plugin_tracker_tmp_netports ".
            " WHERE FK_networking='".$FK_networking."' ".
               " AND FK_networking_port=".$FK_networking_port." ";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 0) {
            $datas["FK_networking"] = $FK_networking;
            $datas["FK_networking_port"] = $FK_networking_port;
            $datas["cdp"] = $cdp;
            $TMP_ID = $this->add($datas);
            return $TMP_ID;
         } else {
            $data = $DB->fetch_assoc($result);
            $datas["cdp"] = $cdp;
            $datas["ID"] = $data["ID"];
            $this->update($datas);
            return $data["ID"];
         }
      }
		return '';
	}



	function AddConnections($FK_tmp_netports,$MacAddress) {
		global $DB;

      if (($MacAddress != "") AND (!strstr($MacAddress,"]"))) {
         // Verify if macaddress is a switch or a switch port
         $insert = 0;
         $query = "SELECT * FROM glpi_networking_ports
            WHERE device_type='".NETWORKING_TYPE."'
               AND ifmac IN ('".$MacAddress."','".strtoupper($MacAddress)."')";
         $result = $DB->query($query);
         if ($DB->numrows($result) != 0) {
               $insert = 1;
         }

         $query = "SELECT * FROM glpi_networking
            WHERE ifmac IN ('".$MacAddress."','".strtoupper($MacAddress)."')";
         $result = $DB->query($query);
         if ($DB->numrows($result) != 0) {
               $insert = 1;
         }

         if ($insert == "1") {
            $query_insert = "INSERT INTO glpi_plugin_tracker_tmp_connections ".
               " (FK_tmp_netports, macaddress) ".
               " VALUES ('".$FK_tmp_netports."','".$MacAddress."') ";
            $DB->query($query_insert);
         }
      }
	}



	function WireInterSwitchs($PID) {
		global $DB;

      if ($_SESSION['tracker_logs'] == "1") $logs = new PluginTrackerLogs;
		$snmp_queries = new PluginTrackerSNMP;

      if ($_SESSION['tracker_logs'] == "1") $logs->write("tracker_fullsync",">>>>>>>>>> WireInterSwitchs <<<<<<<<<<","","",1);

		// ** port in glpi_plugin_tracker_tmp_netports is deleted = port connected ** //

      // *** Delete ifmac in glpi_plugin_tracker_tmp_connections where destination port is cdp
      $query = "SELECT * FROM glpi_plugin_tracker_tmp_connections
         WHERE macaddress IS NOT NULL";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $query_sel1 = "SELECT * FROM glpi_plugin_tracker_tmp_netports
            LEFT JOIN glpi_networking_ports ON glpi_networking_ports.ID = FK_networking_port
            WHERE ifmac='".$data['macaddress']."' ";
         $result_sel1=$DB->query($query_sel1);
         while ($data_sel1=$DB->fetch_array($result_sel1)) {
            if ($data_sel1['cdp'] == "1") {
               $query_delete = "DELETE FROM glpi_plugin_tracker_tmp_connections 
                  WHERE ID='".$data['ID']."' ";
               $DB->query($query_delete);
            }
         }
      }

		// *** Select all cdp = 1 & their mac adress
		$query = "SELECT ifmac, glpi_plugin_tracker_tmp_netports.ID FROM glpi_plugin_tracker_tmp_netports
			LEFT JOIN glpi_networking_ports ON glpi_networking_ports.ID=FK_networking_port
			WHERE cdp='1' ";
		$result=$DB->query($query);
		while ($data=$DB->fetch_array($result)) {
			$query_delete = "DELETE FROM glpi_plugin_tracker_tmp_connections ".
				" WHERE macaddress='".$data['ifmac']."' ";
			$DB->query($query_delete);
         
			//delete after port with cdp = 1
			$query_delete = "DELETE FROM glpi_plugin_tracker_tmp_netports ".
				" WHERE ID='".$data["ID"]."' ";
			$DB->query($query_delete);
		}
		// Get ports which have only one connection and connect between ports(swicths)
		$i = 1;
		while ($i != 0) {
			$i = 0;
         $query = "SELECT macaddress, glpi_plugin_tracker_tmp_netports.ID, FK_networking, FK_networking_port
            FROM glpi_plugin_tracker_tmp_netports
            LEFT JOIN glpi_plugin_tracker_tmp_connections ON glpi_plugin_tracker_tmp_connections.FK_tmp_netports = glpi_plugin_tracker_tmp_netports.ID
            WHERE  macaddress IS NOT NULL
            GROUP BY FK_networking_port
            HAVING COUNT(FK_networking_port)=1
            LIMIT 0,1 ";

			if ($result=$DB->query($query)) {
//            if ($DB->numrows($result) == "0") {
//               $query = "SELECT macaddress, glpi_plugin_tracker_tmp_netports.ID, FK_networking, FK_networking_port
//                  FROM glpi_plugin_tracker_tmp_netports
//                  LEFT JOIN glpi_plugin_tracker_tmp_connections ON glpi_plugin_tracker_tmp_connections.FK_tmp_netports = glpi_plugin_tracker_tmp_netports.ID
//                  WHERE  macaddress IS NOT NULL
//                  GROUP BY macaddress
//                  HAVING COUNT(macaddress)=1
//                  LIMIT 0,1 ";
//               $result=$DB->query($query);
//            }


				while ($data=$DB->fetch_array($result)) {
					$i++;
               $sport = $data['FK_networking_port'];

               // Search DestionationPort
               $query_sel2 = "SELECT * FROM glpi_networking_ports
						WHERE ifmac='".$data['macaddress']."'
                  LIMIT 0,1";
					$result_sel2=$DB->query($query_sel2);
					$dport = $DB->result($result_sel2, 0, "ID");

               if ($_SESSION['tracker_logs'] == "1") $logs->write("tracker_fullsync","Connection wire switch ".$sport." - ".$dport,"","",1);

					$snmp_queries->PortsConnection($sport, $dport,$PID,$vlan." [".$vlan_name."]");
					// Delete all connections with this 2 mac addresses
                  // Delete in glpi_plugin_tracker_tmp_netports
                  $query_delete = "DELETE FROM glpi_plugin_tracker_tmp_netports
							WHERE ID='".$data["ID"]."' ";
						$DB->query($query_delete);
                  // Delete in glpi_plugin_tracker_tmp_connections
                  $query_delete = "DELETE FROM glpi_plugin_tracker_tmp_connections
							WHERE FK_tmp_netports='".$data["ID"]."' ";
						$DB->query($query_delete);
                  // Delet all witch have this macaddress in glpi_plugin_tracker_tmp_connections
                  $query_delete = "DELETE FROM glpi_plugin_tracker_tmp_connections
							WHERE macaddress='".$data["macaddress"]."' ";
						$DB->query($query_delete);
                  // Delete all in glpi_plugin_tracker_tmp_connections which have mac of port source
                  $query_sel2 = "SELECT * FROM glpi_networking_ports
                     WHERE ID='".$data['FK_networking_port']."'
                     LIMIT 0,1";
                  $result_sel2=$DB->query($query_sel2);
                  $data_sel2 = $DB->fetch_assoc($result_sel2);
                  $s_ifmac = $data_sel2["ifmac"];
                  $query_delete = "DELETE FROM glpi_plugin_tracker_tmp_connections
							WHERE macaddress='".$s_ifmac."' ";
						$DB->query($query_delete);
                  // Delete in glpi_plugin_tracker_tmp_netports ports destination
                  $query_sel2 = "SELECT * FROM glpi_plugin_tracker_tmp_netports
                     WHERE FK_networking_port='".$dport."'
                     LIMIT 0,1";
                  $result_sel2=$DB->query($query_sel2);
                  if ($DB->numrows($result_sel2) != "0") {
                     $data_sel2 = $DB->fetch_assoc($result_sel2);
                     $query_delete = "DELETE FROM glpi_plugin_tracker_tmp_netports
                        WHERE ID='".$data_sel2["ID"]."' ";
                     $DB->query($query_delete);
                     // Delete in glpi_plugin_tracker_tmp_connections to tmp_netports ports destination
                     $query_delete = "DELETE FROM glpi_plugin_tracker_tmp_connections
                        WHERE FK_tmp_netports='".$data_sel2["ID"]."' ";
                     $DB->query($query_delete);
                  }
				}
			}
		}
		// Empty MySQL table glpi_plugin_tracker_tmp_netports
		$query = "TRUNCATE table glpi_plugin_tracker_tmp_netports";
		$DB->query($query);
		
		// Empty MySQL table glpi_plugin_tracker_tmp_connections
		$query = "TRUNCATE table glpi_plugin_tracker_tmp_connections";
		$DB->query($query);
	}
}

?>
