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

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}

class plugin_tracker_snmp extends CommonDBTM
{
	/**
	 * Query walk to get OID and values by SNMP connection where an Object have multi-lines
	 *
	 * @param $ArrayOID List of Object and OID in an array to get values
	 * @param $IP IP of the materiel we query
	 * @param $version : version of SNMP (1, 2c, 3)
	 * @param $snmp_auth array of AUTH : 
	 * 		community community name for version 1 and 2c ('public' by default)
	 * 		sec_name for v3 : the "username" used for authentication to the system
	 * 		sec_level for v3 : the authentication scheme ('noAuthNoPriv', 'authNoPriv', or 'authPriv')
	 * 		auth_protocol for v3 : the encryption protocol used for authentication ('MD5' [default] or 'SHA')
	 * 		auth_passphrase for v3 : the encrypted key to use as the authentication challenge
	 * 		priv_protocol for v3 : the encryption protocol used for protecting the protocol data unit ('DES' [default], 'AES128', 'AES192', or 'AES256')
	 * 		priv_passphrase for v3 : the key to use for encrypting the protocol data unit
	 *
	 * @return array : array with OID name and result of the query
	 *
	**/	
	function SNMPQueryWalkAll($ArrayOID,$IP,$version=1,$snmp_auth)
	//$community="public",$sec_name,$sec_level,$auth_protocol="MD5",$auth_passphrase,$priv_protocol="DES",$priv_passphrase)
	{
		$logs = new plugin_tracker_logs;
		$ArraySNMP = array();
		
		foreach($ArrayOID as $object=>$oid)
		{
			if ($version == "1")
				$SNMPValue = snmprealwalk($IP, $snmp_auth["community"],$oid,1500000,1);
			else if ($version == "2c")
				$SNMPValue = snmp2_real_walk($IP, $snmp_auth["community"],$oid,1500000,1);
			else if ($version == "3")
				$SNMPValue = snmp3_real_walk($IP, $snmp_auth["sec_name"],$snmp_auth["sec_level"],$snmp_auth["auth_protocol"],$snmp_auth["auth_passphrase"], $snmp_auth["priv_protocol"],$snmp_auth["priv_passphrase"],$oid,1500000,1);

			if (empty($SNMPValue))
				break;

			if($SNMPValue == false)
				$logs->write("tracker_snmp","SNMP QUERY WALK : ".$object."(".$oid.") = Timeout" ,$IP);
			else
			{
				foreach($SNMPValue as $oidwalk=>$value)
				{
					if ((strstr ($value, "Hex: "))
						OR (strstr ($value, "Gauge32: "))
						OR (strstr ($value, "STRING: "))
						OR (strstr ($value, "Timeticks: "))
						OR (strstr ($value, "INTEGER: "))
						OR (strstr ($value, "Counter32: "))
						OR (strstr ($value, "Hex-STRING: "))
						OR (strstr ($value, "Network Address: "))
						OR (strstr ($value, "IpAddress: "))
						OR (strstr ($value, "Wrong Type (should be Gauge32 or Unsigned32): "))
						)
					{
						$ArraySNMPValues = explode(": ", $value);
						if (!isset($ArraySNMPValues[1]))
							$ArraySNMPValues[1] = "";
						if (count($ArraySNMPValues) > 2)
						{
							for ($i=2;$i < count($ArraySNMPValues);$i++)
							{
								$ArraySNMPValues[1] .= ": ".$ArraySNMPValues[$i];
							}			
						}
						$ArraySNMPValues[1] = trim($ArraySNMPValues[1], '"');
						$ArraySNMP[$oidwalk] = $ArraySNMPValues[1];
					}
					else if (strstr ($value, "No Such Instance currently exists"))
						$ArraySNMP[$oidwalk] = "[[empty]]";
					else if (strstr ($value, "No Such Object available on this agent at this OID"))
						$ArraySNMP[$oidwalk] = "[[empty]]";
					else
						$ArraySNMP[$oidwalk] = trim($value, '"');

					$logs->write("tracker_snmp","SNMP QUERY WALK : ".$object."(".$oid.") = ".$oidwalk."=>".$value ,$IP);
				}
			}
		}
		return $ArraySNMP;
	}



	/**
	 * Get port name and ID of the network materiel from DB
	 *
	 * @param $ID_Device : ID of device
	 * @param $type : type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
	 *
	 * @return array with port name and port ID 
	 *
	**/
	function GetPortsID($ID_Device,$type)
	{
		global $DB;	
	
		$PortsID = array();
		
		$query = "SELECT ID,name
		FROM glpi_networking_ports
		WHERE on_device='".$ID_Device."'
			AND device_type='".$type."'
		ORDER BY logical_number ";

		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				$PortsID[$data["name"]] = $data["ID"];
			}
		}
		return $PortsID;
	}
	
	
	
	/**
	 * Get OID list for the SNMP model 
	 *
	 * @param $IDModelInfos ID of the SNMP model
	 * @param $arg arg for where (ports, port_number or juste oid for device)
	 * @param $name_dyn put object dynamic
	 *
	 * @return array : array with object name and oid
	 *
	**/
	function GetOID($IDModelInfos,$arg,$name_dyn=0,$ArrayPortsSNMPNumber = "")
	{
		
		global $DB;
		
		$oidList = array();		
		
		$query = "SELECT glpi_dropdown_plugin_tracker_mib_oid.name AS oidname, 
			glpi_dropdown_plugin_tracker_mib_object.name AS objectname
		FROM glpi_plugin_tracker_mib_networking
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid
			ON glpi_plugin_tracker_mib_networking.FK_mib_oid=glpi_dropdown_plugin_tracker_mib_oid.ID
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_object
			ON glpi_plugin_tracker_mib_networking.FK_mib_object=glpi_dropdown_plugin_tracker_mib_object.ID
		
		WHERE FK_model_infos=".$IDModelInfos." 
			AND ".$arg." ";

		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				if ($name_dyn == "0")
				{
					$oidList[$data["objectname"]] = $data["oidname"];
				}
				else
				{
					for ($i=0;$i < count($ArrayPortsSNMPNumber); $i++)
					{
						if (isset($ArrayPortsSNMPNumber[$i]))
							$oidList[$data["objectname"].".".$ArrayPortsSNMPNumber[$i]] = $data["oidname"].".".$ArrayPortsSNMPNumber[$i];

					}
				}
			}
		}
		return $oidList;	
	}


	/**
	 * Get links between oid and fields 
	 *
	 * @param $ID_Model ID of the SNMP model
	 *
	 * @return array : array with object name and mapping_type||mapping_name
	 *
	**/
	function GetLinkOidToFields($ID_Device,$type)
	{
		global $DB,$TRACKER_MAPPING;
		
		$ObjectLink = array();

		if ($type == NETWORKING_TYPE)
			$query_add = "LEFT JOIN glpi_plugin_tracker_networking
				ON glpi_plugin_tracker_networking.FK_model_infos=glpi_plugin_tracker_mib_networking.FK_model_infos
			WHERE FK_networking='".$ID_Device."'
				AND glpi_plugin_tracker_networking.FK_model_infos!='0' ";
		else if($type == PRINTER_TYPE)
			$query_add = "LEFT JOIN glpi_plugin_tracker_printers
				ON glpi_plugin_tracker_printers.FK_model_infos=glpi_plugin_tracker_mib_networking.FK_model_infos
			WHERE FK_printers='".$ID_Device."'
				AND glpi_plugin_tracker_printers.FK_model_infos!='0' ";
			
			
		$query = "SELECT mapping_type, mapping_name,oid_port_dyn, 
			glpi_dropdown_plugin_tracker_mib_oid.name AS name
		FROM glpi_plugin_tracker_mib_networking
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid
			ON glpi_plugin_tracker_mib_networking.FK_mib_oid=glpi_dropdown_plugin_tracker_mib_oid.ID
		".$query_add."
			AND oid_port_counter='0' ";

		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				if ($data["oid_port_dyn"] == "1")
					$data["name"] = $data["name"].".";
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
	function MAC_Rewriting($macadresse)
	{
		// If MAC address without : (with space for separate)
		$macadresse = trim($macadresse);
		if ( substr_count($macadresse, ':') == "0"){
			$macexplode = explode(" ",$macadresse);
			$assembledmac = "";
			for($num = 0;$num < count($macexplode);$num++)
			{
				if ($num > 0)
					$assembledmac .= ":";
	
				$assembledmac .= $macexplode[$num];
			}
			$macadresse = $assembledmac;
		}	

		// Rewrite
		$macexplode = explode(":",$macadresse);
		$assembledmac = "";
		for($num = 0;$num < count($macexplode);$num++)
		{
			if ($num > 0)
				$assembledmac .= ":";
		
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
	function update_network_infos($ID, $FK_model_infos, $FK_snmp_connection)
	{
		global $DB;
		
		$query = "SELECT * FROM glpi_plugin_tracker_networking
		WHERE FK_networking='".$ID."' ";
		$result = $DB->query($query);
		if ($DB->numrows($result) == "0")
		{
			$queryInsert = "INSERT INTO glpi_plugin_tracker_networking
			(FK_networking)
			VALUES('".$ID."') ";

			$DB->query($queryInsert);
		}		
		if (empty($FK_snmp_connection))
			$FK_snmp_connection = 0;
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
	function update_printer_infos($ID, $FK_model_infos, $FK_snmp_connection)
	{
		global $DB;

		$query = "SELECT * FROM glpi_plugin_tracker_printers
		WHERE FK_printers='".$ID."' ";
		$result = $DB->query($query);
		if ($DB->numrows($result) == "0")
		{
			$queryInsert = "INSERT INTO glpi_plugin_tracker_printers
			(FK_printers)
			VALUES('".$ID."') ";

			$DB->query($queryInsert);
		}
		if (empty($FK_snmp_connection))
			$FK_snmp_connection = 0;
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
	function getPortIDfromDeviceIP($IP, $ifDescr)
	{
		global $DB;
	
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
	function PortsConnection($source_port, $destination_port,$FK_process)
	{
		global $DB;
		
		$netwire = new Netwire;
		
		$queryVerif = "SELECT *
		FROM glpi_networking_wire 
		WHERE end1 IN ('$source_port', '$destination_port')
			AND end2 IN ('$source_port', '$destination_port') ";

		if ($resultVerif=$DB->query($queryVerif))
		{
			if ( $DB->numrows($resultVerif) == "0" )
			{
//echo "QUERY :".$queryVerif."\n";
			
				//$netwire=new Netwire;
			//	if ($netwire->getOppositeContact($destination_port) != "")
			//	{
					addLogConnection("remove",$netwire->getOppositeContact($destination_port),$FK_process);
					addLogConnection("remove",$destination_port,$FK_process);
					removeConnector($destination_port);
//echo "REMOVE CONNECTOR :".$destination_port."\n";
					removeConnector($source_port);
//echo "REMOVE CONNECTOR :".$source_port."\n";
			//	}
			
				makeConnector($source_port,$destination_port);
//echo "MAKE CONNECTOR :".$source_port." - ".$destination_port."\n";
				addLogConnection("make",$destination_port,$FK_process);
				addLogConnection("make",$source_port,$FK_process);
				
				if ((isset($vlan)) AND (!empty($vlan)))
				{
					$ID_vlan = externalImportDropdown("glpi_dropdown_vlan",$vlan_name,0);
					
					// Insert into glpi_networking_vlan FK_port 	FK_vlan OR update
					// $vlan_name
				}
			}
		}
		// Remove all connections if it is
		if ($netwire->getOppositeContact($destination_port) != "")
		{
			$queryVerif2 = "SELECT *
			FROM glpi_networking_wire 
			WHERE end1='".$netwire->getOppositeContact($destination_port)."'
				AND end2!='$destination_port' ";
			
			$resultVerif2=$DB->query($queryVerif2);
			while ( $dataVerif2=$DB->fetch_array($resultVerif2) )
			{
				$query_del = "DELETE FROM glpi_networking_wire 
				WHERE ID='".$dataVerif2["ID"]."' ";
				$DB->query($query_del);
//echo "DELETE ".$dataVerif2["ID"]." - PORTS ".$end1." - ".$end2."\n";
			}
			$queryVerif2 = "SELECT *
			FROM glpi_networking_wire 
			WHERE end1='$destination_port'
				AND end2!='".$netwire->getOppositeContact($destination_port)."' ";
			
			$resultVerif2=$DB->query($queryVerif2);
			while ( $dataVerif2=$DB->fetch_array($resultVerif2) )
			{
				$query_del = "DELETE FROM glpi_networking_wire 
				WHERE ID='".$dataVerif2["ID"]."' ";
				$DB->query($query_del);
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
	function GetSNMPModel($ID_Device,$type)
	{
	
		global $DB;

		switch ($type)
		{
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
		if (isset($query))
		{
			if ( ($result = $DB->query($query)) )
			{
				if ( $DB->numrows($result) != 0 )
					return $DB->result($result, 0, "FK_model_infos");
			}
		}
	}
}
?>
