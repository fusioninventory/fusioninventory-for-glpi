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
	// logInFile("tracker_snmp", "II) Foreach device\n\n ");
	$processes_values["devices"] = 0;
	$processes_values["ports"] = 0;
	$processes_values["errors"] = 0;
	
	$plugin_tracker_snmp_auth = new plugin_tracker_snmp_auth;
	$processes = new Threads;
	
	foreach ( $ArrayListNetworking as $IDNetworking=>$ifIP )
	{
		// logInFile("tracker_snmp", "	1) Device N°".$IDNetworking." : ".$ifIP."\n\n");
		$updateNetwork = new plugin_tracker_snmp;
		
		// Get SNMP model 
		// logInFile("tracker_snmp", "		a) Get SNMP model\n\n");
		$snmp_model_ID = '';
		$snmp_model_ID = $updateNetwork->GetSNMPModel($IDNetworking);
		if (($snmp_model_ID != "") && ($IDNetworking != ""))
		{
			// ** Get oid
			// logInFile("tracker_snmp", "		b) Get oid list\n\n");
			$Array_Object_oid = $updateNetwork->GetOID($snmp_model_ID,"oid_port_dyn='0' AND oid_port_counter='0'");

			// ** Get oid of PortName
			// logInFile("tracker_snmp", "		c) Get oid Port list\n\n");
			$Array_Object_oid_ifName = $updateNetwork->GetOID($snmp_model_ID,"oid_port_counter='0' AND mapping_name='ifName'");

			// logInFile("tracker_snmp", "		c-bis) Get oid port list type\n\n");
			$Array_Object_oid_ifType = $updateNetwork->GetOID($snmp_model_ID,"oid_port_counter='0' AND mapping_name='ifType'");

			// ** Get oid of vtpVlanName
			$Array_Object_oid_vtpVlanName = $updateNetwork->GetOID($snmp_model_ID,"mapping_name='vtpVlanName'");

			// ** Get snmp version and authentification
			// logInFile("tracker_snmp", "		d) Get SNMP auth parameters from file or DB\n\n");
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
				$processes->addProcessValues($FK_process,"snmp_errors","","SNMP Query impossible");
			}
			else
			{
	
				//**
				// logInFile("tracker_snmp", "		e) Get Array logical port number => SNMP Port name\n\n");
				$ArrayPort_LogicalNum_SNMPName = $updateNetwork->GetPortsName($ifIP,$snmp_version,$snmp_auth,$Array_Object_oid_ifName);
	
				// **
				$ArrayPort_LogicalNum_SNMPNum = $updateNetwork->GetPortsSNMPNumber($ifIP,$snmp_version,$snmp_auth);
	
				// ** Get oid ports Counter
				// logInFile("tracker_snmp", "		f) Get oid Port list\n\n");
				$ArrayPort_Object_oid = tracker_snmp_GetOIDPorts($snmp_model_ID,$ifIP,$IDNetworking,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$snmp_version,$snmp_auth,$Array_Object_oid_ifType);
	
				// ** Define oid and object name
				//$updateNetwork->DefineObject($Array_Object_oid);
				// ** Get query SNMP on switch
				// logInFile("tracker_snmp", "		g) Query SNMP\n\n");
				$ArraySNMP_Object_result= $updateNetwork->SNMPQuery($Array_Object_oid,$ifIP,$snmp_version,$snmp_auth);
				$processes_values["devices"]++;
				
				// ** Define oid and object name
				//$updateNetwork->DefineObject($ArrayPort_Object_oid);
				// ** Get query SNMP of switchs ports
				// logInFile("tracker_snmp", "		h) Query SNMP Ports\n\n");
				$ArraySNMPPort_Object_result = $updateNetwork->SNMPQuery($ArrayPort_Object_oid,$ifIP,$snmp_version,$snmp_auth);
				$processes_values["ports"] = $processes_values["ports"] + count($ArrayPort_LogicalNum_SNMPNum);
	
				// ** Get link OID fields
				// logInFile("tracker_snmp", "		i) Get Relation between object and table for update\n\n");
				$Array_Object_TypeNameConstant = $updateNetwork->GetLinkOidToFields($snmp_model_ID);
	
				// ** Update fields of switchs
				// logInFile("tracker_snmp", "		j) Update infos on DB\n\n");
				tracker_snmp_UpdateGLPINetworking($ArraySNMP_Object_result,$Array_Object_TypeNameConstant,$IDNetworking);
	
				//**
				$ArrayPortDB_Name_ID = $updateNetwork->GetPortsID($IDNetworking);
	
				// ** Update ports fields of switchs
				// logInFile("tracker_snmp", "		k) Update ports infos on DB\n\n");
				UpdateGLPINetworkingPorts($ArraySNMPPort_Object_result,$Array_Object_TypeNameConstant,$IDNetworking,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID);
	
				// ** Get MAC adress of connected ports
				// logInFile("tracker_snmp", "		l) Get and update MAC and connections\n\n");
				$array_port_trunk = array();
				$array_port_trunk = GetMACtoPort($ifIP,$ArrayPortDB_Name_ID,$IDNetworking,$snmp_version,$snmp_auth,$FK_process);
	
				// Foreach VLAN ID to GET MAC Adress on each VLAN
				$updateNetwork->DefineObject($Array_Object_oid_vtpVlanName);
	
				$Array_vlan = $updateNetwork->SNMPQueryWalkAll($Array_Object_oid_vtpVlanName,$ifIP,$snmp_version,$snmp_auth);
				foreach ($Array_vlan as $objectdyn=>$vlan_name)
				{
					$explode = explode(".",$objectdyn);
					$ID_VLAN = $explode[(count($explode) - 1)];
					logInFile("tracker_snmp", "		VLAN : ".$ID_VLAN."\n\n");
					GetMACtoPort($ifIP,$ArrayPortDB_Name_ID,$IDNetworking,$snmp_version,$snmp_auth,$FK_process,$ID_VLAN,$array_port_trunk,$vlan_name);
				}
			}
		}
	} 
	return $processes_values;
}


function tracker_snmp_GetOIDPorts($snmp_model_ID,$IP,$IDNetworking,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$snmp_version,$snmp_auth,$Array_Object_oid_ifType)
{
	
	global $DB,$LANG;

	$oidList = array();
	$object = "";
	$portcounter = "";

	$snmp_queries = new plugin_tracker_snmp;
	

	$return = $snmp_queries->GetOID($snmp_model_ID,"oid_port_counter='1'");	
	foreach ($return as $key=>$value)
	{
		$object = $key;
		$portcounter = $value;
	}

	// Get query SNMP to have number of ports

	if (isset($portcounter))
	{

		$snmp_queries->DefineObject(array($object=>$portcounter));
	
		$Arrayportsnumber = $snmp_queries->SNMPQuery(array($object=>$portcounter),$IP,$snmp_version,$snmp_auth);

		$portsnumber = $Arrayportsnumber[$object];
//echo "PORTNUMBER : ".$portsnumber."\n";
		// We have the number of Ports

		// Add ports in DataBase if they don't exists

		$np=new Netport();

		foreach ($Array_Object_oid_ifType as $key=>$value)
		{
			$object_ifType = $key;
			$oid_ifType = $value;
		}

		for ($i = 0; $i < $portsnumber; $i++)
		{
//echo "PORT :".$i."\n";			
			// Get type of port
			
			$snmp_queries->DefineObject(array($object_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]=>$oid_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]));
			$array_ifType = $snmp_queries->SNMPQuery(array($object_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]=>$oid_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]),$IP,$snmp_version,$snmp_auth);
//echo "TYPEDEPORT :".$array_ifType[$object_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]]." / OID:".$oid_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]."\n";
			if ((ereg("ethernetCsmacd",$array_ifType[$object_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]])) OR ($array_ifType[$object_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$i]] == "6"))
			{		
			
				$query = "SELECT ID,name
			
				FROM glpi_networking_ports
				
				WHERE on_device='".$IDNetworking."'
					AND logical_number='".$i."' ";
			
				if ( $result = $DB->query($query) )
				{
					if ( $DB->numrows($result) == 0 )
					{
	
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
						
						$IDPort = $np->add($array);
						logEvent(0, "networking", 5, "inventory", "Tracker ".$LANG["log"][70]);
	
	
						//$queryInsert = "INSERT INTO glpi_networking_ports 
						//	(on_device,device_type,logical_number)
						
						//VALUES ('".$IDNetworking."','2','".$i."') ";
						
						//$DB->query($query);
						
						//$IDPort = mysql_insert_id();
						
						$queryInsert = "INSERT INTO glpi_plugin_tracker_networking_ports 
							(FK_networking_ports)
						
						VALUES ('".$IDPort."') ";
						
						$DB->query($queryInsert);
						tracker_snmp_addLog($IDPort,"port creation","","");
					
					}
					else
					{
						// Update if it's necessary
						// $np->update
						
						if ($DB->result($result, 0, "name") != $ArrayPort_LogicalNum_SNMPName[$i])
						{
							
							unset($array);
							$array["name"] = $ArrayPort_LogicalNum_SNMPName[$i];
							$array["ID"] = $DB->result($result, 0, "ID");
							$np->update($array);
						
						}
	
					
					
						$queryTrackerPort = "SELECT ID
					
						FROM glpi_plugin_tracker_networking_ports
						
						WHERE FK_networking_ports='".$DB->result($result, 0, "ID")."' ";
					
						if ( $resultTrackerPort = $DB->query($queryTrackerPort) ){
							if ( $DB->numrows($resultTrackerPort) == 0 ) {
							
								$queryInsert = "INSERT INTO glpi_plugin_tracker_networking_ports 
									(FK_networking_ports)
								
								VALUES ('".$DB->result($result, 0, "ID")."') ";
	
								$DB->query($queryInsert);
								tracker_snmp_addLog($DB->result($result, 0, "ID"),"SNMP port creation","","");
							
							}
						}
						
					}
				}
			}
		}

	}
	// Get oid list of ports

	$oidList = $snmp_queries->GetOID($snmp_model_ID,"oid_port_dyn='1'",$Arrayportsnumber[$object],$ArrayPort_LogicalNum_SNMPNum);

	// Debug
/*	foreach($oidList as $object=>$oid)
	{
		echo "===========>".$object." => ".$oid."\n";
	}*/
	// Debug END		
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

		$queryUpdate = "UPDATE ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
		
		SET ".$TRACKER_MAPPING[$object_type][$object_name]['field']."='".$SNMPValue."' 
		
		WHERE ".$Field."='".$IDNetworking."'";
		
		// update via :  $networking->update(array("serial"=>"tonnumero"));
		
		$DB->query($queryUpdate);
		
		//<MoYo> cleanAllItemCache($item,$group)
		//<MoYo> $item = ID
		//<MoYo> group = GLPI_ + NETWORKING_TYPE

	}
}



function UpdateGLPINetworkingPorts($ArraySNMPPort_Object_result,$Array_Object_TypeNameConstant,$IDNetworking,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID)
{

	global $DB,$LANG,$LANGTRACKER,$TRACKER_MAPPING;	
	
	$ArrayPortsList = array();
	
	$ArrayPortListTracker = array();
	
	$ArrayPort_LogicalNum_SNMPNum = array_flip($ArrayPort_LogicalNum_SNMPNum);
	
	$query = "SELECT ID, logical_number
	
	FROM glpi_networking_ports
	
	WHERE on_device='".$IDNetworking."'
	
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
					$ArrayDB_ID_FKNetPort[$dataPortsTracker["FK_networking_ports"]] = $data["logical_number"];

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
//				echo $SNMPValue."\n";
				$SNMPValue = $snmp_queries->MAC_Rewriting($SNMPValue);
			}
			if ($Field != "")
			{
				$update = 0;
				$query_select = "SELECT ".$TRACKER_MAPPING[$object_type][$object_name]['field']."
				FROM ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
				WHERE ID='".$Field."'";
				if ( $result_select=$DB->query($query_select) )
				{
					while ( $data_select=$DB->fetch_assoc($result_select) )
					{
//	echo "ALERTE :".$object_name." : ".$SNMPValue." - ".$data_select[$TRACKER_MAPPING[$object_type][$object_name]['field']]."\n";
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
						addLogConnection("remove",$netwire->getOppositeContact($Field));
						addLogConnection("remove",$Field);
						removeConnector($Field);
						
					}					
					
					if (($object_name != 'ifinoctets') AND ($object_name != 'ifoutoctets'))
					{
						tracker_snmp_addLog($Field,$TRACKER_MAPPING[$object_type][$object_name]['name'],$SNMPValue_old,$SNMPValue);
					}
				}
			}
		}				
	}
}



function GetMACtoPort($IP,$ArrayPortsID,$IDNetworking,$snmp_version,$snmp_auth,$FK_process=0,$vlan="",$array_port_trunk=array(),$vlan_name="")
{

	global $DB;
	
	$processes = new Threads;
	
	$ArrayMACAdressTableObject = array("dot1dTpFdbAddress" => "1.3.6.1.2.1.17.4.3.1.1");
	
	$ArrayIPMACAdressePhysObject = array("ipNetToMediaPhysAddress" => "1.3.6.1.2.1.4.22.1.2");

	$snmp_queries = new plugin_tracker_snmp;
	
	// $snmp_version
	$community = $snmp_auth["community"];

	if ($vlan != ""){
		$snmp_auth["community"] = $snmp_auth["community"]."@".$vlan;
	}
	
	$snmp_queries->DefineObject($ArrayIPMACAdressePhysObject);
	
	$ArrayIPMACAdressePhys = $snmp_queries->SNMPQueryWalkAll($ArrayIPMACAdressePhysObject,$IP,$snmp_version,$snmp_auth);
	
	$snmp_queries->DefineObject($ArrayMACAdressTableObject);
	
	$ArrayMACAdressTable = $snmp_queries->SNMPQueryWalkAll($ArrayMACAdressTableObject,$IP,$snmp_version,$snmp_auth);
	
	$ArrayMACAdressTableVerif = array();
	
	foreach($ArrayMACAdressTable as $oid=>$value)
	{
	
		$oidExplode = explode(".", $oid);

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
						
					
					if ($Arraytrunktype[1] == "2")
					{
						$queryPortEnd = "SELECT * 
						
						FROM glpi_networking_ports
						
						WHERE ifmac IN ('".$MacAddress."','".strtoupper($MacAddress)."')
							AND on_device!='".$IDNetworking."' ";
					}
					else if (($Arraytrunktype[1] == "1") AND ($vlan != "")) // It's a trunk port
					{
						$queryPortEnd = "";
					}
					else if ($Arraytrunktype[1] == "1") // It's a trunk port
					{
						$queryPortEnd = "SELECT * 
						
						FROM glpi_networking_ports
						
						WHERE ifmac IN ('".$MacAddress."','".strtoupper($MacAddress)."')
							AND on_device!='".$IDNetworking."' ";
					}


					if ( $resultPortEnd=$DB->query($queryPortEnd) )
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
							$dport = $DB->result($resultPortEnd, 0, "ID"); // Port of other materiel (Computer, printer...)
							$sport = $ArrayPortsID[$ifName]; // Networking_Port
							
							$queryVerif = "SELECT *
							
							FROM glpi_networking_wire 
							
							WHERE end1 IN ('$sport', '$dport')
								AND end2 IN ('$sport', '$dport') ";

							if ($resultVerif=$DB->query($queryVerif)) {
								if ( $DB->numrows($resultVerif) == 0 )
								{
									$netwire=new Netwire;
									if ($netwire->getOppositeContact($dport) != "")
									{
										addLogConnection("remove",$netwire->getOppositeContact($dport));
										addLogConnection("remove",$dport);
										removeConnector($dport);
									}
									makeConnector($sport,$dport);
									addLogConnection("make",$dport);
									addLogConnection("make",$sport);
									
									if ($vlan != "")
									{
										$ID_vlan = externalImportDropdown("glpi_dropdown_vlan",$vlan_name,0);
										
										// Insert into glpi_networking_vlan FK_port 	FK_vlan OR update
										// $vlan_name
									}
								}
							}

						}
						else if ( $traitement == "1" )
						{
							// Mac address unknow
							if ($FK_process != "0")
							{
								//echo "MAC UNKNOW > FK_process : ".$FK_process."\n";
								//echo "MAC UNKNOW > ArrayPortsID[$ifName] : ".$ArrayPortsID[$ifName]."\n";
								//echo "MAC UNKNOW > MacAddress : ".$MacAddress."\n";
								$processes->addProcessValues($FK_process,"unknow_mac",$ArrayPortsID[$ifName],$MacAddress);
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
?>