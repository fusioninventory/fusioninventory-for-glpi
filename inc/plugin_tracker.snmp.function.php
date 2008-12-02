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
// Original Author of file: Nicolas SMOLYNIEC
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}


/**
 * Get all networking list ready for SNMP query  
 *
 * @return array with ID => IP 
 *
**/
function getNetworkList()
{
	global $DB;
	
	$NetworksID = array();	
	
	$query = "SELECT active_device_state FROM glpi_plugin_tracker_config ";
	
	if ( ($result = $DB->query($query)) )
	{
		$device_state = $DB->result($result, 0, "active_device_state");
	}

	$query = "SELECT ID,ifaddr 
	FROM glpi_networking 
	WHERE deleted='0' 
		AND state='".$device_state."' ";
		
	if ( $result=$DB->query($query) )
	{
		while ( $data=$DB->fetch_array($result) )
		{
			$NetworksID[$data["ID"]] = $data["ifaddr"];
		}
	}

	return $NetworksID;

}
	


/**
 * Get and update infos of networking and its ports  
 *
 * @param $ArrayListNetworking ID => IP of the network materiel
 *
 * @return nothing
 *
**/
function UpdateNetworkBySNMP($ArrayListNetworking,$FK_process = 0,$xml_auth_rep)
{
	$processes_values["devices"] = 0;
	$processes_values["errors"] = 0;
	
	$plugin_tracker_snmp_auth = new plugin_tracker_snmp_auth;
	$processes = new Threads;
	
	foreach ( $ArrayListNetworking as $IDNetworking=>$ifIP )
	{
		$updateNetwork = new plugin_tracker_snmp;
		
		// Get SNMP model 
		$snmp_model_ID = '';
		$snmp_model_ID = $updateNetwork->GetSNMPModel($IDNetworking);
		if (($snmp_model_ID != "") && ($IDNetworking != ""))
		{
			// ** Get oid
			$Array_Object_oid = $updateNetwork->GetOID($snmp_model_ID,"oid_port_dyn='0' AND oid_port_counter='0'");

			// ** Get oid of PortName
			$Array_Object_oid_ifName = $updateNetwork->GetOID($snmp_model_ID,"oid_port_counter='0' AND mapping_name='ifName'");

			$Array_Object_oid_ifType = $updateNetwork->GetOID($snmp_model_ID,"oid_port_counter='0' AND mapping_name='ifType'");

			// ** Get oid of vtpVlanName
			$Array_Object_oid_vtpVlanName = $updateNetwork->GetOID($snmp_model_ID,"mapping_name='vtpVlanName'");

			// ** Get snmp version and authentification
			$snmp_auth = $plugin_tracker_snmp_auth->GetInfos($IDNetworking,$xml_auth_rep);
			$snmp_version = $snmp_auth["snmp_version"];

			// ** Get from SNMP, description of equipment
			// .1.3.6.1.2.1.1.1.0 sysDescr
			$updateNetwork->DefineObject(array("sysDescr"=>".1.3.6.1.2.1.1.1.0"));
			$Array_sysdescr = $updateNetwork->SNMPQuery(array("sysDescr"=>".1.3.6.1.2.1.1.1.0"),$ifIP,$snmp_version,$snmp_auth);
			if ($Array_sysdescr["sysDescr"] == "")
			{
				// SNMP error (Query impossible)
				$processes_values["errors"]++;
				$processes->addProcessValues($FK_process,"snmp_errors","","SNMP Query impossible (".$IDNetworking.")");
			}
			else
			{
	
				//**
				$ArrayPort_LogicalNum_SNMPName = $updateNetwork->GetPortsName($ifIP,$snmp_version,$snmp_auth,$Array_Object_oid_ifName);
	
				// **
				$ArrayPort_LogicalNum_SNMPNum = $updateNetwork->GetPortsSNMPNumber($ifIP,$snmp_version,$snmp_auth);

				// ** Get oid ports Counter
				$ArrayPort_Object_oid = tracker_snmp_GetOIDPorts($snmp_model_ID,$ifIP,$IDNetworking,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$snmp_version,$snmp_auth,$Array_Object_oid_ifType,$FK_process);
	
				// ** Define oid and object name
				//$updateNetwork->DefineObject($Array_Object_oid);
				// ** Get query SNMP on switch
				$ArraySNMP_Object_result= $updateNetwork->SNMPQuery($Array_Object_oid,$ifIP,$snmp_version,$snmp_auth);
				$processes_values["devices"]++;
				
				// ** Define oid and object name
				//$updateNetwork->DefineObject($ArrayPort_Object_oid);
				// ** Get query SNMP of switchs ports

				$ArraySNMPPort_Object_result = $updateNetwork->SNMPQuery($ArrayPort_Object_oid,$ifIP,$snmp_version,$snmp_auth);

				// ** Get link OID fields
				$Array_Object_TypeNameConstant = $updateNetwork->GetLinkOidToFields($snmp_model_ID);
	
				// ** Update fields of switchs
				tracker_snmp_UpdateGLPINetworking($ArraySNMP_Object_result,$Array_Object_TypeNameConstant,$IDNetworking);
	
				//**
				$ArrayPortDB_Name_ID = $updateNetwork->GetPortsID($IDNetworking);
	
				// ** Update ports fields of switchs
				UpdateGLPINetworkingPorts($ArraySNMPPort_Object_result,$Array_Object_TypeNameConstant,$IDNetworking,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID,$FK_process);

				$Array_trunk_ifIndex = cdp_trunk($ifIP,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID,$ArraySNMPPort_Object_result,$snmp_version,$snmp_auth,$FK_process);

				// ** Get MAC adress of connected ports
				$array_port_trunk = array();
				$array_port_trunk = GetMACtoPort($ifIP,$ArrayPortDB_Name_ID,$IDNetworking,$snmp_version,$snmp_auth,$FK_process,$Array_trunk_ifIndex);
	
				// Foreach VLAN ID to GET MAC Adress on each VLAN
				$updateNetwork->DefineObject($Array_Object_oid_vtpVlanName);
	
				$Array_vlan = $updateNetwork->SNMPQueryWalkAll($Array_Object_oid_vtpVlanName,$ifIP,$snmp_version,$snmp_auth);
				foreach ($Array_vlan as $objectdyn=>$vlan_name)
				{
					$explode = explode(".",$objectdyn);
					$ID_VLAN = $explode[(count($explode) - 1)];
					logInFile("tracker_snmp", "		VLAN : ".$ID_VLAN."\n\n");
					GetMACtoPort($ifIP,$ArrayPortDB_Name_ID,$IDNetworking,$snmp_version,$snmp_auth,$FK_process,$Array_trunk_ifIndex,$ID_VLAN,$array_port_trunk,$vlan_name);
				}
			}
		}
	} 
	return $processes_values;
}



/**
 * Get port OID list for the SNMP model && create ports in DB if they don't exists 
 *
 * @param $snmp_model_ID : ID of the SNMP model
 * @param $IP : ip of the device
 * @param $IDNetworking : ID of device
 * @param $ArrayPort_LogicalNum_SNMPName : array logical port number => SNMP Port name
 * @param $ArrayPort_LogicalNum_SNMPNum : array logical port number => SNMP port number (ifindex)
 * @param $snmp_version : version of SNMP (1, 2c or 3)
 * @param $snmp_auth : array with snmp authentification parameters
 * @param $Array_Object_oid_ifType : array of oid ifType (object => oid) (1 entry, it's not dyn)
 * @param $FK_process : PID of the process (script run by console)
 *
 * @return $oidList : array with ports object name and oid
 *
**/
function tracker_snmp_GetOIDPorts($snmp_model_ID,$IP,$IDNetworking,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$snmp_version,$snmp_auth,$Array_Object_oid_ifType,$FK_process=0)
{

	global $DB,$LANG;

	$oidList = array();
	$object = "";
	$portcounter = "";

	$snmp_queries = new plugin_tracker_snmp;
	$np=new Netport();
	
	// Get object => oid of port computer (generaly ifNumber) from SNMP model
	$return = $snmp_queries->GetOID($snmp_model_ID,"oid_port_counter='1'");	
	foreach ($return as $key=>$value)
	{
		$object = $key;
		$portcounter = $value;
	}
	// Get object => oid of type of port (generaly ifType) from SNMP model
	foreach ($Array_Object_oid_ifType as $key=>$value)
	{
		$object_ifType = $key;
		$oid_ifType = $value;
	}

	// Get query SNMP to have number of ports
	if (isset($portcounter))
	{
		$snmp_queries->DefineObject(array($object=>$portcounter));
		$Arrayportsnumber = $snmp_queries->SNMPQuery(array($object=>$portcounter),$IP,$snmp_version,$snmp_auth);
		// Get Number of port from SNMP query
		$portsnumber = $Arrayportsnumber[$object];

		// ** Add ports in DataBase if they don't exists
		for ($i = 0; $i < $portsnumber; $i++)
		{
			// Get type of port
			$snmp_queries->DefineObject(array($object_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]=>$oid_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]));
			$array_ifType = $snmp_queries->SNMPQuery(array($object_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]=>$oid_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]),$IP,$snmp_version,$snmp_auth);
			if ((ereg("ethernetCsmacd",$array_ifType[$object_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]])) OR ($array_ifType[$object_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]] == "6"))
			{
				// Increment number of port queried in process
				$query = "UPDATE glpi_plugin_tracker_processes SET ports_queries = ports_queries + 1
				WHERE process_id='".$FK_process."' ";
				$DB->query($query);
			
				$query = "SELECT ID,name
				FROM glpi_networking_ports
				WHERE on_device='".$IDNetworking."'
					AND device_type='2'
					AND logical_number='".$i."' ";
			
				if ( $result = $DB->query($query) )
				{
					if ( $DB->numrows($result) == 0 )
					{
						unset($array);
						$array["logical_number"] = $i;
						$array["name"] = $ArrayPort_LogicalNum_SNMPName[$i];
						$array["iface"] = "";
						$array["ifaddr"] = "";
						$array["ifmac"] = "";
						$array["netmask"] = "";
						$array["gateway"] = "";
						$array["subnet"] = "";
						$array["netpoint"] = "";
						$array["on_device"] = $IDNetworking;
						$array["device_type"] = "2";
						$array["add"] = "Ajouter";
						
						$IDport = $np->add($array);
						logEvent(0, "networking", 5, "inventory", "Tracker ".$LANG["log"][70]);
					}
					else
					{
						$IDport = $DB->result($result, 0, "ID");
						if ($DB->result($result, 0, "name") != $ArrayPort_LogicalNum_SNMPName[$i])
						{
							unset($array);
							$array["name"] = $ArrayPort_LogicalNum_SNMPName[$i];
							$array["ID"] = $DB->result($result, 0, "ID");
							$np->update($array);
						}
					}
							
					$queryTrackerPort = "SELECT ID
					FROM glpi_plugin_tracker_networking_ports
					WHERE FK_networking_ports='".$IDport."' ";
				
					if ( $resultTrackerPort = $DB->query($queryTrackerPort) ){
						if ( $DB->numrows($resultTrackerPort) == 0 ) {
						
							$queryInsert = "INSERT INTO glpi_plugin_tracker_networking_ports 
								(FK_networking_ports)
							VALUES ('".$IDport."') ";
							$DB->query($queryInsert);
						}
					}
				}
			}
		}
	}
	// Get oid list of ports

	$oidList = $snmp_queries->GetOID($snmp_model_ID,"oid_port_dyn='1'",$Arrayportsnumber[$object],$ArrayPort_LogicalNum_SNMPNum);
	return $oidList;

}	



function tracker_snmp_UpdateGLPINetworking($ArraySNMP_Object_result,$Array_Object_TypeNameConstant,$IDNetworking)
{

	global $DB,$LANG,$LANGTRACKER,$TRACKER_MAPPING;	

	foreach($ArraySNMP_Object_result as $object=>$SNMPValue)
	{
		$explode = explode ("||", $Array_Object_TypeNameConstant[$object]);
		$object_type = $explode[0];
		$object_name = $explode[1];

		if ($TRACKER_MAPPING[$object_type][$object_name]['dropdown'] != "")
		{
			// Search if value of SNMP Query is in dropdown, if not, we put it
			// Wawax : si tu ajoutes le lieu manuellement tu mets un msg dans le message_after_redirect
			// 
			
			// $ArrayDropdown = getDropdownArrayNames($data["table"],"%")
			$SNMPValue = externalImportDropdown($TRACKER_MAPPING[$object_type][$object_name]['dropdown'],$SNMPValue,0);
		}
		// Update fields
		//$query_update = "UPDATE
		if ($TRACKER_MAPPING[$object_type][$object_name]['table'] == "glpi_networking")
		{
			$Field = "ID";
		}
		else
		{
			$Field = "FK_networking";
		}
		
		$SNMPValue = preg_replace('/^\"/', '',$SNMPValue);
		$SNMPValue = preg_replace('/\"$/', '',$SNMPValue);
		
		if (($object_name == "ram") OR ($object_name == "memory"))
		{
			$SNMPValue = ceil(($SNMPValue / 1024) / 1024) ;
		}
		
		if ($TRACKER_MAPPING[$object_type][$object_name]['table'] != "")
		{
			$queryUpdate = "UPDATE ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
			SET ".$TRACKER_MAPPING[$object_type][$object_name]['field']."='".$SNMPValue."' 
			WHERE ".$Field."='".$IDNetworking."'";
			
			// update via :  $networking->update(array("serial"=>"tonnumero"));
			$DB->query($queryUpdate);
		}
		//<MoYo> cleanAllItemCache($item,$group)
		//<MoYo> $item = ID
		//<MoYo> group = GLPI_ + NETWORKING_TYPE

	}
}



function UpdateGLPINetworkingPorts($ArraySNMPPort_Object_result,$Array_Object_TypeNameConstant,$IDNetworking,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID,$FK_process=0)
{
	global $DB,$LANG,$LANGTRACKER,$TRACKER_MAPPING;	
	
	$ArrayPortsList = array();
	$ArrayPortListTracker = array();
	
	$snmp_queries = new plugin_tracker_snmp;

	// Traitement of SNMP results to dispatch by ports
	foreach ($ArraySNMPPort_Object_result as $object=>$SNMPValue)
	{
		$ArrayObject = explode (".",$object);
		$i = count($ArrayObject);
		$i--;
		$ifIndex = $ArrayObject[$i];
		$object = '';
		for ($j = 0; $j < $i;$j++)
		{
			$object .= $ArrayObject[$j];
		}
		$Array_OID[$ifIndex][$object] = $SNMPValue;
	}
	
	// For each port
	$query = "SELECT ID, logical_number
	FROM glpi_networking_ports
	WHERE on_device='".$IDNetworking."'
		AND device_type='2'
	ORDER BY logical_number";
	
	if ($result=$DB->query($query))
	{
		while ($data=$DB->fetch_array($result))
		{
			// Get ifIndex (SNMP portNumber)
			$ifIndex = $ArrayPort_LogicalNum_SNMPNum[$data["logical_number"]];
			foreach ($Array_OID[$ifIndex] as $object=>$SNMPValue)
			{
				// Get object constant in relation with object
				$explode = explode ("||", $Array_Object_TypeNameConstant[$object]);
				$object_type = $explode[0];
				$object_name = $explode[1];
				
				// Update $SNMPValue if dropdown object
				if ($TRACKER_MAPPING[$object_type][$object_name]['dropdown'] != "")
				{
					$SNMPValue = externalImportDropdown($TRACKER_MAPPING[$object_type][$object_name]['dropdown'],$SNMPValue,0);
				}
				// Rewriting MacAdress
				if ($object_name == "ifPhysAddress")
					$SNMPValue = $snmp_queries->MAC_Rewriting($SNMPValue);

				if ($TRACKER_MAPPING[$object_type][$object_name]['table'] == "glpi_networking_ports")
				{
					$ID_field = "ID";
				}
				else
				{
					$ID_field = "FK_networking_ports";
				}
				if (($TRACKER_MAPPING[$object_type][$object_name]['field'] != "") AND ($TRACKER_MAPPING[$object_type][$object_name]['table'] != ""))
				{
					// Get actual value before updating
					$query_select = "SELECT ".$TRACKER_MAPPING[$object_type][$object_name]['field']."
					FROM ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
					WHERE ".$ID_field."='".$data["ID"]."'";
					$result_select=$DB->query($query_select);
					$SNMPValue_old = $DB->result($result_select, 0, $TRACKER_MAPPING[$object_type][$object_name]['field']);
					
					// Update
					$queryUpdate = "UPDATE ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
					SET ".$TRACKER_MAPPING[$object_type][$object_name]['field']."='".$SNMPValue."' 
					WHERE ".$ID_field."='".$data["ID"]."'";
					$DB->query($queryUpdate);
					// Delete port wire if port is internal disable
					if (($object_name == "ifinternalstatus") AND (($SNMPValue == "2") OR ($SNMPValue == "down(2)")))
					{
						$netwire=new Netwire;
						addLogConnection("remove",$netwire->getOppositeContact($data["ID"]),$FK_process);
						addLogConnection("remove",$Field,$FK_process);
						removeConnector($data["ID"]);
						
					}
					// Add log if snmp value change			
					if (($object_name != 'ifinoctets') AND ($object_name != 'ifoutoctets') AND ($SNMPValue_old != $SNMPValue ))
					{
						tracker_snmp_addLog($data["ID"],$TRACKER_MAPPING[$object_type][$object_name]['name'],$SNMPValue_old,$SNMPValue,$FK_process);
					}
				}		
				
			}		
		}
	}


// ** ANCIEN CODE ****************************************************************
/*	$ArrayPort_LogicalNum_SNMPNum = array_flip($ArrayPort_LogicalNum_SNMPNum);
	$query = "SELECT ID, logical_number
	FROM glpi_networking_ports
	WHERE on_device='".$IDNetworking."'
		AND device_type='2'
	ORDER BY logical_number";
	
	if ( $result=$DB->query($query) )
	{
		while ( $data=$DB->fetch_array($result) )
		{
		
			$ArrayPortsList[$data["logical_number"]] = $data["ID"];
			
			$queryPortsTracker = "SELECT ID,FK_networking_ports
			
			FROM glpi_plugin_tracker_networking_ports
			
			WHERE FK_networking_ports='".$data["ID"]."' ";
			
			if ( $resultPortsTracker=$DB->query($queryPortsTracker) )
			{
				while ( $dataPortsTracker=$DB->fetch_assoc($resultPortsTracker) )
				{
					$ArrayPortListTracker[$data["logical_number"]] = $dataPortsTracker["ID"];
					$ArrayDB_ID_FKNetPort[$data["ID"]] = $data["logical_number"];

				}
			} 

		}
	}
	foreach($ArraySNMPPort_Object_result as $object=>$SNMPValue)
	{
		$ArrayObject = explode (".",$object);
		$i = count($ArrayObject);
		$i--;
		$PortNumber = $ArrayObject[$i];

		
		$object = '';
		
		for ($j = 0; $j < $i;$j++)
		{
			$object .= $ArrayObject[$j];
		}

		$explode = explode ("||", $Array_Object_TypeNameConstant[$object]);
		$object_type = $explode[0];
		$object_name = $explode[1];

		if ($TRACKER_MAPPING[$object_type][$object_name]['dropdown'] != "")
		{
		
			$SNMPValue = externalImportDropdown($TRACKER_MAPPING[$object_type][$object_name]['dropdown'],$SNMPValue,0);
		
		}
		else
		{
			
			if ($TRACKER_MAPPING[$object_type][$object_name]['table'] == "glpi_networking_ports")
			{
				if (isset($ArrayPortListTracker[$ArrayPort_LogicalNum_SNMPNum[$PortNumber]]))
				{
					$Field = $ArrayPortsList[$ArrayPort_LogicalNum_SNMPNum[$PortNumber]];
				}
				else
				{
					$Field = "";
				}				
			}
			else
			{
				if (isset($ArrayPortListTracker[$ArrayPort_LogicalNum_SNMPNum[$PortNumber]]))
				{
					$Field = $ArrayPortListTracker[$ArrayPort_LogicalNum_SNMPNum[$PortNumber]];
				}
				else
				{
					$Field = "";
				}

			}						

			// Detect if changes
			if ($object_name == "ifPhysAddress")
			{
				$snmp_queries = new plugin_tracker_snmp;
				$SNMPValue = $snmp_queries->MAC_Rewriting($SNMPValue);
			}
			if (($Field != "") AND ($TRACKER_MAPPING[$object_type][$object_name]['field'] != "") AND ($TRACKER_MAPPING[$object_type][$object_name]['table'] != ""))
			{
				$update = 0;
				$query_select = "SELECT ".$TRACKER_MAPPING[$object_type][$object_name]['field']."
				FROM ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
				WHERE ID='".$Field."'";
				if ( $result_select=$DB->query($query_select) )
				{
					while ( $data_select=$DB->fetch_assoc($result_select) )
					{
						if ($SNMPValue != $data_select[$TRACKER_MAPPING[$object_type][$object_name]['field']])
						{
							$update = 1;
							$SNMPValue_old = $data_select[$TRACKER_MAPPING[$object_type][$object_name]['field']];
						}
					}
				}		
				
				if ($update == "1")
				{
					$queryUpdate = "UPDATE ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
				
					SET ".$TRACKER_MAPPING[$object_type][$object_name]['field']."='".$SNMPValue."' 
					
					WHERE ID='".$Field."'";
	
					$DB->query($queryUpdate);
					
					if (($object_name == "ifinternalstatus") AND (($SNMPValue == "2") OR ($SNMPValue == "down(2)")))
					{
						
						$netwire=new Netwire;
						addLogConnection("remove",$netwire->getOppositeContact($Field),$FK_process);
						addLogConnection("remove",$Field,$FK_process);
						removeConnector($Field);
						
					}					
					
					if (($object_name != 'ifinoctets') AND ($object_name != 'ifoutoctets'))
					{
						tracker_snmp_addLog($Field,$TRACKER_MAPPING[$object_type][$object_name]['name'],$SNMPValue_old,$SNMPValue,$FK_process);
					}
				}
			}
		}				
	}*/
}



function GetMACtoPort($IP,$ArrayPortsID,$IDNetworking,$snmp_version,$snmp_auth,$FK_process=0,$Array_trunk_ifIndex,$vlan="",$array_port_trunk=array(),$vlan_name="")
{
	global $DB;
ECHO ">>>>>>>>>>>>>>>>>>>> NETWORKING <<<<<<<<<<<<<<<<<<<<<<<<<\n";
	$processes = new Threads;
	$netwire = new Netwire;
	$snmp_queries = new plugin_tracker_snmp;

	$ArrayMACAdressTableObject = array("dot1dTpFdbAddress" => "1.3.6.1.2.1.17.4.3.1.1");
	$ArrayIPMACAdressePhysObject = array("ipNetToMediaPhysAddress" => "1.3.6.1.2.1.4.22.1.2");
	$ArrayMACAdressTableVerif = array();
	
	// $snmp_version
	$community = $snmp_auth["community"];

	if ($vlan != ""){
		$snmp_auth["community"] = $snmp_auth["community"]."@".$vlan;
	}
	// Get by SNMP query the mac addresses and IP (ipNetToMediaPhysAddress)
	$snmp_queries->DefineObject($ArrayIPMACAdressePhysObject);
	$ArrayIPMACAdressePhys = $snmp_queries->SNMPQueryWalkAll($ArrayIPMACAdressePhysObject,$IP,$snmp_version,$snmp_auth);
	if (empty($ArrayIPMACAdressePhys))
	{
	return;
	}

	// Get by SNMP query the mac addresses (dot1dTpFdbAddress)
	$snmp_queries->DefineObject($ArrayMACAdressTableObject);
	$ArrayMACAdressTable = $snmp_queries->SNMPQueryWalkAll($ArrayMACAdressTableObject,$IP,$snmp_version,$snmp_auth);
	
	foreach($ArrayMACAdressTable as $oid=>$value)
	{
		$oidExplode = explode(".", $oid);
		// Get by SNMP query the port number (dot1dTpFdbPort)
		$OIDBridgePortNumber = "1.3.6.1.2.1.17.4.3.1.2.0.".
			$oidExplode[(count($oidExplode)-5)].".".
			$oidExplode[(count($oidExplode)-4)].".".
			$oidExplode[(count($oidExplode)-3)].".".
			$oidExplode[(count($oidExplode)-2)].".".
			$oidExplode[(count($oidExplode)-1)];
		$ArraySNMPBridgePortNumber = array("dot1dTpFdbPort" => $OIDBridgePortNumber);
		$snmp_queries->DefineObject($ArraySNMPBridgePortNumber);
		$ArrayBridgePortNumber = $snmp_queries->SNMPQuery($ArraySNMPBridgePortNumber,$IP,$snmp_version,$snmp_auth);
		
		foreach($ArrayBridgePortNumber as $oidBridgePort=>$BridgePortNumber)
		{
			//echo "BRIDGEPortNumber ".$BridgePortNumber."\n";
			
			$ArrayBridgePortifIndexObject = array("dot1dBasePortIfIndex" => "1.3.6.1.2.1.17.1.4.1.2.".$BridgePortNumber);
	
			$snmp_queries->DefineObject($ArrayBridgePortifIndexObject);
	
			$ArrayBridgePortifIndex = $snmp_queries->SNMPQuery($ArrayBridgePortifIndexObject,$IP,$snmp_version,$snmp_auth);
			
			foreach($ArrayBridgePortifIndex as $oidBridgePortifIndex=>$BridgePortifIndex)
			{
				if (($BridgePortifIndex == "") OR ($BridgePortifIndex == "No Such Instance currently exists at this OID"))
					break;
					
				if ((isset($Array_trunk_ifIndex[$BridgePortifIndex])) AND ($Array_trunk_ifIndex[$BridgePortifIndex] == "1"))
					break;
					
				//echo "BridgePortifIndex : ".$BridgePortifIndex."\n";
			
				$ArrayifNameObject = array("ifName" => "1.3.6.1.2.1.31.1.1.1.1.".$BridgePortifIndex);
	
				$snmp_queries->DefineObject($ArrayifNameObject);
		
				$ArrayifName = $snmp_queries->SNMPQuery($ArrayifNameObject,$IP,$snmp_version,$snmp_auth);
				
				foreach($ArrayifName as $oidArrayifName=>$ifName)
				{
					//echo "		**ifName : *".$ifName."*\n";

					// Search portID of materiel wich we would connect to this port
					$MacAddress = trim($value);
					$MacAddress = str_replace(" ", ":", $MacAddress);
					$MacAddress = strtolower($MacAddress);
					$MacAddress = $snmp_queries->MAC_Rewriting($MacAddress);
					
// A METTRE EN DYN !!!!!!!!
$arrayTRUNKmod = array("vlanTrunkPortDynamicStatus.".$BridgePortifIndex => "1.3.6.1.4.1.9.9.46.1.6.1.1.14.".$BridgePortifIndex);

$snmp_queries->DefineObject($arrayTRUNKmod);
		
$Arraytrunktype = $snmp_queries->SNMPQuery($arrayTRUNKmod,$IP,$snmp_version,$snmp_auth);

echo "VLAN :".$vlan."\n";
echo "TRUNKSTATUS :".$Arraytrunktype["vlanTrunkPortDynamicStatus.".$BridgePortifIndex]."\n";
echo "MACADRESS :".$MacAddress."\n";
echo "INTERFACE :".$ifName."\n";
echo "================================\n";
					
					$queryPortEnd = "";	
					if ((!isset($Arraytrunktype["vlanTrunkPortDynamicStatus.".$BridgePortifIndex])) OR (empty($Arraytrunktype["vlanTrunkPortDynamicStatus.".$BridgePortifIndex])) OR ($Arraytrunktype["vlanTrunkPortDynamicStatus.".$BridgePortifIndex] == "2"))
					{
echo "PASSAGE ... OK\n";
						$queryPortEnd = "SELECT * 
						
						FROM glpi_networking_ports
						
						WHERE ifmac IN ('".$MacAddress."','".strtoupper($MacAddress)."')
							AND on_device!='".$IDNetworking."' ";
					}
					else if (($Arraytrunktype["vlanTrunkPortDynamicStatus.".$BridgePortifIndex] == "1") AND ($vlan != "")) // It's a trunk port
					{
echo "PASSAGE ... FAILED\n";
						$queryPortEnd = "";
					}
					else if ($Arraytrunktype["vlanTrunkPortDynamicStatus.".$BridgePortifIndex] == "1") // It's a trunk port
					{
echo "PASSAGE ... OK (2) => Refusé\n";
						$queryPortEnd = "SELECT * 
						
						FROM glpi_networking_ports
						
						WHERE ifmac IN ('".$MacAddress."','".strtoupper($MacAddress)."')
							AND on_device!='".$IDNetworking."' ";
						$queryPortEnd = "";
					}

					if (($queryPortEnd != ""))
					{
						if ($resultPortEnd=$DB->query($queryPortEnd))
						{
							$traitement = 1;
							if ($vlan != "")
							{
								if (isset($array_port_trunk[$ArrayPortsID[$ifName]]) && $array_port_trunk[$ArrayPortsID[$ifName]] == "1")
								{
									$traitement = 0;
								}
							}
							else
							{
								$array_port_trunk[$ArrayPortsID[$ifName]] = 1;
							}						
							
							if (!isset($ArrayPortsID[$ifName]))
							{
								$traitement = 0;
							}

							if ( ($DB->numrows($resultPortEnd) != 0) && ($traitement == "1") )
							{
echo "TRAITEMENT :".$traitement."\n";
								$dport = $DB->result($resultPortEnd, 0, "ID"); // Port of other materiel (Computer, printer...)
								$sport = $ArrayPortsID[$ifName]; // Networking_Port
								
								// Connection between ports (wire table in DB)
								$snmp_queries->PortsConnection($sport, $dport,$FK_process);
							}
							else if ( $traitement == "1" )
							{
								// Mac address unknow
								if ($FK_process != "0")
								{
									//$processes->addProcessValues($FK_process,"unknow_mac",$ArrayPortsID[$ifName],$MacAddress);
									$processes->unknownMAC($FK_process,$ArrayPortsID[$ifName],$MacAddress);
								}
							}
						}
					}
				}
			}
		}
	}
	$snmp_auth["community"] = $community;
	if ($vlan == "")
	{
		return $array_port_trunk;
	}
}


/*
 * @param $ArrayPort_LogicalNum_SNMPName : array logical port number => SNMP Port name
 * @param $ArrayPort_LogicalNum_SNMPNum : array logical port number => SNMP port number (ifindex)
*/
function cdp_trunk($IP,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortsID,$ArraySNMPPort_Object_result,$snmp_version,$snmp_auth,$FK_process)
{
	global $DB;
	
	$snmp_queries = new plugin_tracker_snmp;

	$Array_trunk_IP_hex = array("cdpCacheAddress" => "1.3.6.1.4.1.9.9.23.1.2.1.1.4");
	$Array_trunk_ifDescr = array("cdpCacheDevicePort" => "1.3.6.1.4.1.9.9.23.1.2.1.1.7");
	$Array_trunk_ifIndex = array();
	
	$ArrayPort_LogicalNum_SNMPNum = array_flip($ArrayPort_LogicalNum_SNMPNum);
	
	// Get by SNMP query the IP addresses of the switch connected ($Array_trunk_IP_hex)
	$snmp_queries->DefineObject($Array_trunk_IP_hex);
	$Array_trunk_IP_hex_result = $snmp_queries->SNMPQueryWalkAll($Array_trunk_IP_hex,$IP,$snmp_version,$snmp_auth);

	// Get by SNMP query the Name of port (ifDescr)
	$snmp_queries->DefineObject($Array_trunk_ifDescr);
	$Array_trunk_ifDescr_result = $snmp_queries->SNMPQueryWalkAll($Array_trunk_ifDescr,$IP,$snmp_version,$snmp_auth);
var_dump($Array_trunk_ifDescr_result);
	foreach($Array_trunk_IP_hex_result AS $object=>$result)
	{
		$explode = explode(".", $object);
		$ifIndex = $explode[(count($explode)-2)];
		$end_Number = $explode[(count($explode)-1)];
		
		$Array_trunk_ifIndex[$ifIndex] = 1;
		
		// Convert IP hex to decimal
		$Array_ip_switch_trunk = explode(" ",$result);
		$ip_switch_trunk = "";
		for($i = 0; $i < 4;$i++)
		{
		$ip_switch_trunk .= hexdec($Array_ip_switch_trunk[$i]);
			if ($i < 3)
				$ip_switch_trunk .= ".";
		}

		// Search port of switch connected on this port and connect it if not connected
		$ifdescr_trunk = "";
		foreach ($Array_trunk_ifDescr_result AS $oid=>$ifdescr)
		{
			if (ereg("9.9.23.1.2.1.1.7.".$ifIndex.".".$end_Number, $oid))
			{
				$ifdescr_trunk = $ifdescr;
			}		
		}
		
		$PortID = $snmp_queries->getPortIDfromDeviceIP($ip_switch_trunk, $ifdescr_trunk);

		$query = "SELECT glpi_networking_ports.ID FROM glpi_networking_ports
		LEFT JOIN glpi_networking
		ON glpi_networking.ID = glpi_networking_ports.on_device
		WHERE logical_number='".$ArrayPort_LogicalNum_SNMPNum[$ifIndex]."' 
			AND device_type='2' 
			AND glpi_networking.ifaddr='".$IP."' ";
		$result = $DB->query($query);		
		$data = $DB->fetch_assoc($result);
//echo "QUERY :".$query."\n";
echo "PORTID :".$data["ID"]." -> ".$PortID."(".$ArrayPort_LogicalNum_SNMPNum[$ifIndex].")\n";
		if ((!empty($data["ID"])) AND (!empty($PortID)))
			$snmp_queries->PortsConnection($data["ID"], $PortID,$FK_process);
	}
	return $Array_trunk_ifIndex;
}


?>