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
	 * Query OID by SNMP connection
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
	 * @return array : array with object name and result of the query
	 *
	**/
	function SNMPQuery($ArrayOID,$IP,$version=1,$snmp_auth)
	{
		$logs = new plugin_tracker_logs;
		$ArraySNMP = array();
		
		foreach($ArrayOID as $object=>$oid)
		{
			$SNMPValue = "";
			if ($oid[strlen($oid)-1] != ".")
			{
				if ($version == "1")
				{
/*					if (ereg("::",$object))
					{
						if (defined(str_replace("::","",$object)))
							runkit_constant_remove(str_replace("::","",$object));

						define(str_replace("::","",$object),$oid);
					}
					else
					{
						if (defined($object))
							runkit_constant_remove($object);

						define($object,$oid);
					}*/
//					ob_start();
					$SNMPValue = snmpget($IP, $snmp_auth["community"],$oid,1500000,1);
//					ob_end_clean();
				}
				else if ($version == "2c")
				{
//					ob_start();
					$SNMPValue = snmp2_get($IP, $snmp_auth["community"],$oid,1500000,1);
//					ob_end_clean();
				}
				else if ($version == "3")
				{
//					ob_start();
					$SNMPValue = snmp3_get($IP, $snmp_auth["sec_name"],$snmp_auth["sec_level"],$snmp_auth["auth_protocol"],$snmp_auth["auth_passphrase"], $snmp_auth["priv_protocol"],$snmp_auth["priv_passphrase"],$oid,1500000,1);
//					ob_end_clean();
				}
				if($SNMPValue == false)
					$logs->write("tracker_snmp","SNMP QUERY : ".$object."(".$oid.") = Timeout" ,$IP);
				else
				{
					$logs->write("tracker_snmp","SNMP QUERY : ".$object."(".$oid.") = ".$SNMPValue ,$IP);
					if ((ereg ("Hex: ", $SNMPValue)) 
						OR (ereg ("Gauge32: ", $SNMPValue)) 
						OR (ereg ("STRING: ", $SNMPValue))
						OR (ereg ("Timeticks: ", $SNMPValue))
						OR (ereg ("INTEGER: ", $SNMPValue))
						OR (ereg ("Counter32: ", $SNMPValue))
						OR (ereg ("Hex-STRING: ", $SNMPValue))
						OR (ereg ("Network Address: ", $SNMPValue))
						OR (ereg ("IpAddress: ", $SNMPValue))
						OR (ereg ("Wrong Type (should be Gauge32 or Unsigned32): ", $SNMPValue))
						)
					{
						$ArraySNMPValues = explode(": ", $SNMPValue);
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
						$ArraySNMP[$object] = $ArraySNMPValues[1];
					}
					else if (ereg ("No Such Instance currently exists", $SNMPValue))
						$ArraySNMP[$object] = "[[empty]]";
					else if (ereg ("No Such Object available on this agent at this OID", $SNMPValue))
						$ArraySNMP[$object] = "[[empty]]";
					else
						$ArraySNMP[$object] = trim($SNMPValue, '"');
				}
			}
		}
		return $ArraySNMP;
	}
	
	

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
			{
/*				if (ereg("::",$object))
				{
					if (defined(str_replace("::","",$object)))
						runkit_constant_remove(str_replace("::","",$object));

					define(str_replace("::","",$object),$oid);
				}
				else
				{
					if (defined($object))
						runkit_constant_remove($object);

					define($object,$oid);
				}*/
				$SNMPValue = snmprealwalk($IP, $snmp_auth["community"],$oid,1500000,1);
			}
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
					if ((ereg ("Hex: ", $value)) 
						OR (ereg ("Gauge32: ", $value)) 
						OR (ereg ("STRING: ", $value))
						OR (ereg ("Timeticks: ", $value))
						OR (ereg ("INTEGER: ", $value))
						OR (ereg ("Counter32: ", $value))
						OR (ereg ("Hex-STRING: ", $value))
						OR (ereg ("Network Address: ", $value))
						OR (ereg ("IpAddress: ", $value))
						OR (ereg ("Wrong Type (should be Gauge32 or Unsigned32): ", $value))
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
					else if (ereg ("No Such Instance currently exists", $value))
						$ArraySNMP[$oidwalk] = "[[empty]]";
					else if (ereg ("No Such Object available on this agent at this OID", $value))
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
	 * Get SNMP port name of the network materiel and assign it to logical port number
	 *
	 * @param $IP IP address of network materiel
	 * @param $snmp_version version of SNMP (1, 2c or 3)
	 * @param $snmp_auth array with authentification of SNMP
	 * @param $ArrayOID List whith just Object and OID values
	 *
	 * @return array with logical port number and port name 
	 *
	**/
	function GetPortsName($IP,$snmp_version,$snmp_auth,$ArrayOID)
	{
		$snmp_queries = new plugin_tracker_snmp;
		$logs = new plugin_tracker_logs;
		
		$Arrayportsnames = array();
		$logs->write("tracker_fullsync",">>>>>>>>>> Get network ports name <<<<<<<<<<",$IP,1);
		foreach($ArrayOID as $object=>$oid)
		{
			$Arrayportsnames = $snmp_queries->SNMPQueryWalkAll(array($object=>$oid),$IP,$snmp_version,$snmp_auth);
		}
	
		$PortsName = array();
	
		foreach($Arrayportsnames as $object=>$value)
		{
			$logs->write("tracker_fullsync",$object." = ".$value,$IP,1);
			$PortsName[] = $value;
		}
		return $PortsName;
	}



	/**
	 * Get SNMP port number of the network materiel and assign it to logical port number
	 *
	 * @param $IP IP address of network materiel
	 * @param $snmp_version version of SNMP (1, 2c or 3)
	 * @param $snmp_auth array with authentification of SNMP
	 *
	 * @return array with logical port number and SNMP port number 
	 *
	**/
	function GetPortsSNMPNumber($IP,$snmp_version,$snmp_auth)
	{
		$snmp_queries = new plugin_tracker_snmp;
		$logs = new plugin_tracker_logs;

		$logs->write("tracker_fullsync",">>>>>>>>>> Get network ports number with logical number <<<<<<<<<<",$IP,1);
		$ArrayportsSNMPNumber = $snmp_queries->SNMPQueryWalkAll(array("IF-MIB::ifIndex"=>".1.3.6.1.2.1.2.2.1.1"),$IP,$snmp_version,$snmp_auth);
	
		$PortsName = array();
		$i=0;
		foreach($ArrayportsSNMPNumber as $object=>$value)
		{
			$PortsSNMPNumber[] = $value;
			$logs->write("tracker_fullsync",$object." = ".$value."(N° logic : ".$i++.")",$IP,1);
		}
		return $PortsSNMPNumber;
	}



	/**
	 * Get port name and ID of the network materiel from DB
	 *
	 * @param $IDNetworking ID of the network materiel 
	 *
	 * @return array with port name and port ID 
	 *
	**/
	function GetPortsID($IDNetworking)
	{

		global $DB;	
	
		$PortsID = array();
		
		$query = "SELECT ID,name
		FROM glpi_networking_ports
		WHERE on_device='".$IDNetworking."'
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
	function GetLinkOidToFields($ID_Model)
	{

		global $DB,$TRACKER_MAPPING;
		
		$ObjectLink = array();
		
		$query = "SELECT mapping_type, mapping_name, 
			glpi_dropdown_plugin_tracker_mib_object.name AS name
		FROM glpi_plugin_tracker_mib_networking
		
		LEFT JOIN glpi_dropdown_plugin_tracker_mib_object
			ON glpi_plugin_tracker_mib_networking.FK_mib_object=glpi_dropdown_plugin_tracker_mib_object.ID
		
		WHERE FK_model_infos=".$ID_Model." 
			AND oid_port_counter='0' ";
		
		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				//$ObjectLink[$data["name"]] = $data["FK_links_oid_fields"];
				$ObjectLink[$data["name"]] = $data["mapping_type"]."||".$data["mapping_name"];
			}
		}
		return $ObjectLink;
	}



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
	 * Define a global var
	 *
	 * @param $ArrayOID Array with ObjectName and OID
	 *
	 * @return nothing
	 *
	**/
	function DefineObject($ArrayOID)
	{
		foreach($ArrayOID as $object=>$oid)
		{
			if (!ereg("IF-MIB::",$object))
			{
				if(defined($object))
				{
					runkit_constant_remove($object);
					define($object,$oid);
				}
				else
					define($object,$oid);

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
		
		if ( ($result = $DB->query($query)) )
		{
			if ( $DB->numrows($result) != 0 )
				return $DB->result($result, 0, "FK_model_infos");

		}	
	
	}
	
}
?>
