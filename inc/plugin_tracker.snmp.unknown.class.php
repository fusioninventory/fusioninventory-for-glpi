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

class PluginTrackerUnknown extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_tracker_unknown_device";
		$this->type = PLUGIN_TRACKER_MAC_UNKNOWN;
	}



	function showForm($target, $ID = '') {
		global $DB,$CFG_GLPI,$LANG;

		plugin_tracker_checkRight("snmp_networking","r");

		if ($ID!='') {
			$this->getFromDB($ID);
      } else {
			$this->getEmpty();
      }
		echo "<br>";
		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table  class='tab_cadre_fixe'>";

		echo "<tr><th colspan='2'>";
		echo "Unknown device";
		echo " :</th></tr>";

		$datestring = $LANG["common"][26].": ";
		$date = convDateTime($this->fields["date_mod"]);
		echo "<tr>";
		echo "<th align='center' width='450'>";
		echo $LANG["common"][2]." ".$this->fields["ID"];
		echo "</th>";
	
		echo "<th align='center'>";
		echo $datestring.$date;
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='" . $this->fields["name"] . "' size='35'/>";
		echo "</td>";
		echo "</tr>";

		echo "</table></form></div>";
	}

	function updateFromOldVersion_unknown_mac() {
		global $DB,$LANG;

		$snmp_queries = new PluginTrackerSNMP;
		$np=new Netport;

		$query = "SELECT DISTINCT unknow_mac,unknown_ip,port,end_FK_processes FROM glpi_plugin_tracker_unknown_mac ".
			" WHERE end_FK_processes=(select max(end_FK_processes) from glpi_plugin_tracker_unknown_mac) ";

		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				$name_unknown = plugin_tracker_search_name_ocs_servers($data["unknow_mac"]);
				// Add unknown device
				if ($name_unknown == $data["unknown_ip"]) {
					$unknown_infos["name"] = '';
            } else {
					$unknown_infos["name"] = $name_unknown;
            }
				$newID=$this->add($unknown_infos);
				unset($unknown_infos);
				// Add networking_port
				$port_add["on_device"] = $newID;
				$port_add["device_type"] = PLUGIN_TRACKER_MAC_UNKNOWN;
				$port_add["ifaddr"] = $data["unknown_ip"];
				$port_add['ifmac'] = $data["unknow_mac"];
				$port_ID = $np->add($port_add);
				unset($port_add);

				// Connection between ports (wire table in DB)
				$snmp_queries->PortsConnection($data["port"], $port_ID,$data["end_FK_processes"]);
			}
		}
	}



	function FusionUnknownKnownDevice() {
		global $DB;

//		$query = "SELECT * FROM glpi_networking_ports ".
//			" WHERE (ifmac) IN ".
//				" (SELECT ifmac FROM glpi_networking_ports ".
//					" GROUP BY 1 ".
//					" HAVING count(*)>1) ".
//			" AND ifmac !='' ".
//			" AND ifmac!='00:00:00:00:00:00' ".
//			" AND device_type=".PLUGIN_TRACKER_MAC_UNKNOWN." ";
		$query = "SELECT * FROM glpi_networking_ports ".
			" WHERE ifmac !='' ".
				" AND ifmac!='00:00:00:00:00:00' ".
				" AND device_type=".PLUGIN_TRACKER_MAC_UNKNOWN." ".
			" GROUP BY ifmac ".
			" HAVING count(*)>1 ";


		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				// $data = ID of unknown device

				$query_known = "SELECT * FROM glpi_networking_ports ".
					" WHERE ifmac='".$data["ifmac"]."' ".
						" AND device_type!=".PLUGIN_TRACKER_MAC_UNKNOWN." ".
					" LIMIT 0,1 ";
				$result_known=$DB->query($query_known);
				$data_known=$DB->fetch_array($result_known);

				$query_update = "UPDATE glpi_networking_ports ".
					" SET on_device='".$data_known["on_device"]."', ".
						" device_type='".$data_known["device_type"]."', ".
						" logical_number='".$data_known["logical_number"]."', ".
						" name='".$data_known["name"]."', ".
						" ifaddr='".$data_known["ifaddr"]."', ".
						" iface='".$data_known["iface"]."', ".
						" netpoint='".$data_known["netpoint"]."', ".
						" netmask='".$data_known["netmask"]."', ".
						" gateway='".$data_known["gateway"]."', ".
						" subnet='".$data_known["subnet"]."' ".
					" WHERE ID='".$data["ID"]."' ";
				$DB->query($query_update);

				// Delete old networking port
				$query_delete = "DELETE FROM glpi_networking_ports WHERE ID='".$data_known["ID"]."' ";
				$DB->query($query_delete);

				// Delete unknown device
				$query_delete = "DELETE FROM glpi_plugin_tracker_unknown_device WHERE ID='".$data["on_device"]."' ";
				$DB->query($query_delete);

				// Modify OCS link of this networking port
				$query = "SELECT * 
					FROM glpi_ocs_link 
					WHERE glpi_id='".$data_known["on_device"]."' ";
				$result = $DB->query($query);
				if ($DB->numrows($result) == 1) {
					$line = $DB->fetch_assoc($result);

					$import_ip = importArrayFromDB($line["import_ip"]);
					$ip_port = $import_ip[$data_known["ID"]];
					unset($import_ip[$data_known["ID"]]);
					$import_ip[$data["ID"]] = $ip_port;

					$query_update = "UPDATE glpi_ocs_link
						SET `import_ip`='" . exportArrayToDB($import_ip) . "'
						WHERE glpi_id='".$line["ID"]."' ";
					$DB->query($query_update);
				}
			}
		}
	}
}

?>