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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginTrackerSNMP extends CommonDBTM {
	/**
	 * Get port name and ID of the network materiel from DB
	 *
	 * @param $ID_Device : ID of device
	 * @param $type : type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
	 *
	 * @return array with port name and port ID 
	 *
	**/
	function GetPortsID($ID_Device,$type) {
		GLOBAL $DB;
	
		$PortsID = array();
		
		$query = "SELECT ID,name
		FROM glpi_networking_ports
		WHERE on_device='".$ID_Device."'
			AND device_type='".$type."'
		ORDER BY logical_number ";

		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				$PortsID[$data["name"]] = $data["ID"];
			}
		}
		return $PortsID;
	}
	
	
	
	/**
	 * Get links between oid and fields 
	 *
	 * @param $ID_Model ID of the SNMP model
	 *
	 * @return array : array with object name and mapping_type||mapping_name
	 *
	**/
	function GetLinkOidToFields($ID_Device,$type) {
		global $DB,$TRACKER_MAPPING;
		
		$ObjectLink = array();

		if ($type == NETWORKING_TYPE) {
			$query_add = "LEFT JOIN glpi_plugin_tracker_networking
				ON glpi_plugin_tracker_networking.FK_model_infos=glpi_plugin_tracker_mib_networking.FK_model_infos
			WHERE FK_networking='".$ID_Device."'
				AND glpi_plugin_tracker_networking.FK_model_infos!='0' ";
      } else if($type == PRINTER_TYPE) {
			$query_add = "LEFT JOIN glpi_plugin_tracker_printers
				ON glpi_plugin_tracker_printers.FK_model_infos=glpi_plugin_tracker_mib_networking.FK_model_infos
			WHERE FK_printers='".$ID_Device."'
				AND glpi_plugin_tracker_printers.FK_model_infos!='0' ";
      }
			
		$query = "SELECT mapping_type, mapping_name,oid_port_dyn, 
			glpi_dropdown_plugin_tracker_mib_oid.name AS name
		FROM glpi_plugin_tracker_mib_networking
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid
			ON glpi_plugin_tracker_mib_networking.FK_mib_oid=glpi_dropdown_plugin_tracker_mib_oid.ID
		".$query_add."
			AND oid_port_counter='0'
			AND glpi_plugin_tracker_mib_networking.activation='1' ";

		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				if ($data["oid_port_dyn"] == "1") {
					$data["name"] = $data["name"].".";
            }
				$ObjectLink[$data["name"]] = $data["mapping_name"];
			}
		}
		return $ObjectLink;
	}
	


	/**
	 * Description
	 *
	 * @param
	 * @param
	 *
	 * @return
	 *
	**/
	function MAC_Rewriting($macadresse) {
		// If MAC address without : (with space for separate)
		$macadresse = trim($macadresse);
		if (substr_count($macadresse, ':') == "0") {
			$macexplode = explode(" ",$macadresse);
			$assembledmac = "";
			for($num = 0 ; $num < count($macexplode) ; $num++) {
				if ($num > 0) {
					$assembledmac .= ":";
            }
				$assembledmac .= $macexplode[$num];
			}
			$macadresse = $assembledmac;
		}	

		// Rewrite
		$macexplode = explode(":",$macadresse);
		$assembledmac = "";
		for($num = 0 ; $num < count($macexplode) ; $num++) {
			if ($num > 0) {
				$assembledmac .= ":";
         }
			switch (strlen($macexplode[$num])) {
			case 0:
			    $assembledmac .= "00";
			    break;

			case 1:
			    $assembledmac .= "0".$macexplode[$num];
			    break;

			case 2:
			    $assembledmac .= $macexplode[$num];
			    break;
			}
		}
		return $assembledmac;
	}



	/**
	 * Description
	 *
	 * @param
	 * @param
	 *
	 * @return
	 *
	**/
	function update_network_infos($ID, $FK_model_infos, $FK_snmp_connection) {
		GLOBAL $DB;
		
		$query = "SELECT * FROM glpi_plugin_tracker_networking
		WHERE FK_networking='".$ID."' ";
		$result = $DB->query($query);
		if ($DB->numrows($result) == "0") {
			$queryInsert = "INSERT INTO glpi_plugin_tracker_networking
			(FK_networking)
			VALUES('".$ID."') ";

			$DB->query($queryInsert);
		}		
		if (empty($FK_snmp_connection)) {
			$FK_snmp_connection = 0;
      }
		$query = "UPDATE glpi_plugin_tracker_networking
		SET FK_model_infos='".$FK_model_infos."',FK_snmp_connection='".$FK_snmp_connection."'
		WHERE FK_networking='".$ID."' ";
	
		$DB->query($query);
	}
	
	

	/**
	 * Description
	 *
	 * @param
	 * @param
	 *
	 * @return
	 *
	**/
	function update_printer_infos($ID, $FK_model_infos, $FK_snmp_connection) {
		GLOBAL $DB;

		$query = "SELECT * FROM glpi_plugin_tracker_printers
		WHERE FK_printers='".$ID."' ";
		$result = $DB->query($query);
		if ($DB->numrows($result) == "0") {
			$queryInsert = "INSERT INTO glpi_plugin_tracker_printers
			(FK_printers)
			VALUES('".$ID."') ";

			$DB->query($queryInsert);
		}
		if (empty($FK_snmp_connection)) {
			$FK_snmp_connection = 0;
      }
		$query = "UPDATE glpi_plugin_tracker_printers
		SET FK_model_infos='".$FK_model_infos."',FK_snmp_connection='".$FK_snmp_connection."'
		WHERE FK_printers='".$ID."' ";
	
		$DB->query($query);
	}
	
	

	/**
	 * Description
	 *
	 * @param
	 * @param
	 *
	 * @return
	 *
	**/
	function getPortIDfromDeviceIP($IP, $ifDescr) {
		GLOBAL $DB;
	
		$query = "SELECT * FROM glpi_plugin_tracker_networking_ifaddr
		WHERE ifaddr='".$IP."' ";
		
		$result = $DB->query($query);		
		$data = $DB->fetch_assoc($result);
		
		$queryPort = "SELECT * FROM glpi_plugin_tracker_networking_ports
		LEFT JOIN glpi_networking_ports
		ON glpi_plugin_tracker_networking_ports.FK_networking_ports = glpi_networking_ports.ID
		WHERE ifdescr='".$ifDescr."' 
			AND glpi_networking_ports.on_device='".$data["FK_networking"]."'
			AND glpi_networking_ports.device_type='2' ";
		$resultPort = $DB->query($queryPort);		
		$dataPort = $DB->fetch_assoc($resultPort);

		return($dataPort["FK_networking_ports"]);
	}



	/**
	 * Description
	 *
	 * @param
	 * @param
	 *
	 * @return
	 *
	**/
	function PortsConnection($source_port, $destination_port,$FK_process,$vlan="") {
		GLOBAL $DB;
		
		$netwire = new Netwire;
		
		$queryVerif = "SELECT *
		FROM glpi_networking_wire 
		WHERE end1 IN ('$source_port', '$destination_port')
			AND end2 IN ('$source_port', '$destination_port') ";

		if ($resultVerif=$DB->query($queryVerif)) {
			if ($DB->numrows($resultVerif) == "0") {
				plugin_tracker_addLogConnection("remove",$netwire->getOppositeContact($destination_port),$FK_process);
				plugin_tracker_addLogConnection("remove",$destination_port,$FK_process);
				// Remove VLAN
				$this->CleanVlan($source_port);
				removeConnector($destination_port);
				removeConnector($source_port);
			
				makeConnector($source_port,$destination_port);
				plugin_tracker_addLogConnection("make",$destination_port,$FK_process);
				plugin_tracker_addLogConnection("make",$source_port,$FK_process);
				
				if ((!empty($vlan)) AND ($vlan != " []")) {
					$FK_vlan = externalImportDropdown("glpi_dropdown_vlan",$vlan,0);
					if ($FK_vlan != "0") {
                  $query="SELECT * FROM glpi_networking_vlan WHERE FK_port='$source_port' AND FK_vlan='$FK_vlan'  LIMIT 0,1";
                  if ($result=$DB->query($query)) {
                     if ($DB->numrows($result) == "0") {
                        assignVlan($source_port,$FK_vlan);
                     }
                  }
               }
				}
			} else {
				if ((!empty($vlan)) AND ($vlan != " []")) {
               // Verify vlan and update it if necessery
               $FK_vlan = externalImportDropdown("glpi_dropdown_vlan",$vlan,0);
               if ($FK_vlan != "0") {
                  $query = "SELECT * FROM glpi_networking_vlan ".
                     " WHERE FK_port='$source_port' ".
                     " AND FK_vlan='$FK_vlan' ";
                  if ($result=$DB->query($query)) {
                     if ($DB->numrows($result) == "0") {
                        $this->CleanVlan($source_port);
                        assignVlan($source_port,$FK_vlan);
                     } else {
                        $query2 = "SELECT * FROM glpi_networking_vlan ".
                           " WHERE FK_port='$source_port' ".
                           " AND FK_vlan!='$FK_vlan' ";
                        if ($result2=$DB->query($query2)) {
                           while ($data2=$DB->fetch_array($result2)) {
                              $this->CleanVlanID($data2["ID"]);
                           }
                        }
                     }
                  }
               }
            }
         }
		}
		// Remove all connections if it is
		if ($netwire->getOppositeContact($destination_port) != "") {
			$queryVerif2 = "SELECT *
			FROM glpi_networking_wire 
			WHERE end1='".$netwire->getOppositeContact($destination_port)."'
				AND end2!='$destination_port' ";
			
			$resultVerif2=$DB->query($queryVerif2);
			while ($dataVerif2=$DB->fetch_array($resultVerif2)) {
				$query_del = "DELETE FROM glpi_networking_wire 
				WHERE ID='".$dataVerif2["ID"]."' ";
				$DB->query($query_del);
				$this->CleanVlan($destination_port);
//echo "DELETE ".$dataVerif2["ID"]." - PORTS ".$end1." - ".$end2."\n";
			}
			$queryVerif2 = "SELECT *
			FROM glpi_networking_wire 
			WHERE end1='$destination_port'
				AND end2!='".$netwire->getOppositeContact($destination_port)."' ";
			
			$resultVerif2=$DB->query($queryVerif2);
			while ($dataVerif2=$DB->fetch_array($resultVerif2)) {
				$query_del = "DELETE FROM glpi_networking_wire 
				WHERE ID='".$dataVerif2["ID"]."' ";
				$DB->query($query_del);
				$this->CleanVlan($destination_port);
//echo "DELETE ".$dataVerif2["ID"]." - PORTS ".$end1." - ".$end2."\n";
			}
		}
	
	}



	/**
	 * Get SNMP model of the device 
	 *
	 * @param $ID_Device ID of the device
	 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
	 *
	 * @return ID of the SNMP model or nothing 
	 *
	**/
	function GetSNMPModel($ID_Device,$type) {
		GLOBAL $DB;

		switch ($type) {
			case NETWORKING_TYPE :
				$query = "SELECT FK_model_infos
				FROM glpi_plugin_tracker_networking 
				WHERE FK_networking='".$ID_Device."' ";
				break;

			case PRINTER_TYPE :
				$query = "SELECT FK_model_infos
				FROM glpi_plugin_tracker_printers 
				WHERE FK_printers='".$ID_Device."' ";
				break;
		}
		if (isset($query)) {
			if (($result = $DB->query($query))) {
				if ($DB->numrows($result) != 0) {
					return $DB->result($result, 0, "FK_model_infos");
            }
			}
		}
	}



	function CleanVlan($FK_port) {
		GLOBAL $DB;

		$query="SELECT * FROM glpi_networking_vlan WHERE FK_port='$FK_port'  LIMIT 0,1";
		if ($result=$DB->query($query)) {
			$data=$DB->fetch_array($result);

			// Delete VLAN
			$query="DELETE FROM glpi_networking_vlan WHERE FK_port='$FK_port'";
			$DB->query($query);

			// Delete Contact VLAN if set
			$np=new NetPort;
			if ($np->getContact($data['FK_port'])) {
				$query="DELETE FROM glpi_networking_vlan WHERE FK_port='".$np->contact_id."' AND FK_vlan='".$data['FK_vlan']."'";
				$DB->query($query);
			}
		}
   }



	function CleanVlanID($ID) {
		GLOBAL $DB;

		$query="SELECT * FROM glpi_networking_vlan WHERE ID='$ID' LIMIT 0,1";
		if ($result=$DB->query($query)) {
			$data=$DB->fetch_array($result);

			// Delete VLAN
			$query="DELETE FROM glpi_networking_vlan WHERE ID='$ID'";
			$DB->query($query);

			// Delete Contact VLAN if set
			$np=new NetPort;
			if ($np->getContact($data['FK_port'])) {
				$query="DELETE FROM glpi_networking_vlan WHERE FK_port='".$np->contact_id."' AND FK_vlan='".$data['FK_vlan']."'";
				$DB->query($query);
			}
		}
	}
}

?>
