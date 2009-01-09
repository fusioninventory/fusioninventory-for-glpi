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
 * Get all DEVICE list ready for SNMP query  
 *
 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
 *
 * @return array with ID => IP 
 *
**/
function plugin_tracker_getDeviceList($type)
{
	global $DB;
	
	$NetworksID = array();
		
	switch ($type)
	{
		case NETWORKING_TYPE :
			$table = "glpi_plugin_tracker_config_snmp_networking";
			break;
		case PRINTER_TYPE :
			$table = "glpi_plugin_tracker_config_snmp_printer";
			break;
	}
	
	$query = "SELECT active_device_state FROM ".$table." ";
	
	if ( ($result = $DB->query($query)) )
	{
		$device_state = $DB->result($result, 0, "active_device_state");
	}

	switch ($type)
	{
		case NETWORKING_TYPE :
			$table = "glpi_networking";
			$join = "";
			$whereand = "";
			break;
		case PRINTER_TYPE :
			$table = "glpi_printers";
			$join = "LEFT JOIN glpi_networking_ports
				ON glpi_printers.ID = glpi_networking_ports.on_device";
			$whereand = "AND glpi_networking_ports.device_type='".PRINTER_TYPE."' ";
			break;
	}

	$query = "SELECT ".$table.".ID,ifaddr 
	FROM ".$table." 
	".$join."
	WHERE deleted='0' 
		AND state='".$device_state."' ".$whereand." ";
		
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
 * @param $ArrayListDeviceNetworking ID => IP of the network materiel
 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
 *
 * @return nothing
 *
**/
function plugin_tracker_UpdateDeviceBySNMP($ArrayListDevice,$FK_process = 0,$xml_auth_rep,$type)
{
	$processes_values["devices"] = 0;
	$processes_values["errors"] = 0;
	
	$plugin_tracker_snmp_auth = new plugin_tracker_snmp_auth;
	$processes = new Threads;
	
	foreach ( $ArrayListDevice as $ID_Device=>$ifIP )
	{
		$plugin_tracker_snmp = new plugin_tracker_snmp;
		
		// Get SNMP model 
		$snmp_model_ID = '';
		$snmp_model_ID = $plugin_tracker_snmp->GetSNMPModel($ID_Device,$type);
		if (($snmp_model_ID != "") && ($ID_Device != ""))
		{
			// ** Get oid of PortName
			$Array_Object_oid_ifName = $plugin_tracker_snmp->GetOID($snmp_model_ID,"oid_port_counter='0' AND mapping_name='ifName'");

			$Array_Object_oid_ifType = $plugin_tracker_snmp->GetOID($snmp_model_ID,"oid_port_counter='0' AND mapping_name='ifType'");

			// ** Get oid of vtpVlanName
			$Array_Object_oid_vtpVlanName = $plugin_tracker_snmp->GetOID($snmp_model_ID,"mapping_name='vtpVlanName'");

			// ** Get OIDs
			$Array_Object_oid = $plugin_tracker_snmp->GetOID($snmp_model_ID,"oid_port_dyn='0' AND oid_port_counter='0'");

			// ** Get snmp version and authentification
			$snmp_auth = $plugin_tracker_snmp_auth->GetInfos($ID_Device,$xml_auth_rep,$type);
			$snmp_version = $snmp_auth["snmp_version"];

			// ** Get from SNMP, description of equipment
			$plugin_tracker_snmp->DefineObject(array("sysDescr"=>".1.3.6.1.2.1.1.1.0"));
			$Array_sysdescr = $plugin_tracker_snmp->SNMPQuery(array("sysDescr"=>".1.3.6.1.2.1.1.1.0"),$ifIP,$snmp_version,$snmp_auth);
			if ($Array_sysdescr["sysDescr"] == "")
			{
				// SNMP error (Query impossible)
				$processes_values["errors"]++;
				$processes->addProcessValues($FK_process,"snmp_errors","","SNMP Query impossible (".$ID_Device.") Type ".$type." ");
			}
			else
			{
				//**
				$ArrayPort_LogicalNum_SNMPName = $plugin_tracker_snmp->GetPortsName($ifIP,$snmp_version,$snmp_auth,$Array_Object_oid_ifName);
	
				// **
				$ArrayPort_LogicalNum_SNMPNum = $plugin_tracker_snmp->GetPortsSNMPNumber($ifIP,$snmp_version,$snmp_auth);

				// ** Get oid ports Counter
				$ArrayPort_Object_oid = tracker_snmp_GetOIDPorts($snmp_model_ID,$ifIP,$ID_Device,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$snmp_version,$snmp_auth,$Array_Object_oid_ifType,$FK_process,$type);

				// ** Get query SNMP of switchs ports
				if (!empty($ArrayPort_Object_oid))
				$ArraySNMPPort_Object_result = $plugin_tracker_snmp->SNMPQuery($ArrayPort_Object_oid,$ifIP,$snmp_version,$snmp_auth);

				// ** Get query SNMP on switch
				$ArraySNMP_Object_result= $plugin_tracker_snmp->SNMPQuery($Array_Object_oid,$ifIP,$snmp_version,$snmp_auth);
				$processes_values["devices"]++;
				
				// ** Get link OID fields
				$Array_Object_TypeNameConstant = $plugin_tracker_snmp->GetLinkOidToFields($snmp_model_ID);
	
				// ** Update fields of switchs
				tracker_snmp_UpdateGLPIDevice($ArraySNMP_Object_result,$Array_Object_TypeNameConstant,$ID_Device,$type);

				//**
				$ArrayPortDB_Name_ID = $plugin_tracker_snmp->GetPortsID($ID_Device);
	
				// ** Update ports fields of switchs
				if (!empty($ArrayPort_Object_oid))
					UpdateGLPINetworkingPorts($ArraySNMPPort_Object_result,$Array_Object_TypeNameConstant,$ID_Device,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID,$FK_process,$type);
				$Array_trunk_ifIndex = array();
				if ($type == NETWORKING_TYPE)	
					$Array_trunk_ifIndex = cdp_trunk($ifIP,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID,$ArraySNMPPort_Object_result,$snmp_version,$snmp_auth,$FK_process,$ID_Device);

				// ** Get MAC adress of connected ports
				$array_port_trunk = array();
				if (!empty($ArrayPort_Object_oid))
					$array_port_trunk = GetMACtoPort($ifIP,$ArrayPortDB_Name_ID,$ID_Device,$snmp_version,$snmp_auth,$FK_process,$Array_trunk_ifIndex);
				if ($type ==  NETWORKING_TYPE)
				{
					// Foreach VLAN ID to GET MAC Adress on each VLAN
					$plugin_tracker_snmp->DefineObject($Array_Object_oid_vtpVlanName);
		
					$Array_vlan = $plugin_tracker_snmp->SNMPQueryWalkAll($Array_Object_oid_vtpVlanName,$ifIP,$snmp_version,$snmp_auth);
					foreach ($Array_vlan as $objectdyn=>$vlan_name)
					{
						$explode = explode(".",$objectdyn);
						$ID_VLAN = $explode[(count($explode) - 1)];
						logInFile("tracker_snmp", "		VLAN : ".$ID_VLAN."\n\n");
						GetMACtoPort($ifIP,$ArrayPortDB_Name_ID,$ID_Device,$snmp_version,$snmp_auth,$FK_process,$Array_trunk_ifIndex,$ID_VLAN,$array_port_trunk,$vlan_name);
					}
				}

			}
		}
	} 
	return $processes_values;
}



function plugin_tracker_UpdateDeviceBySNMP_startprocess($ArrayListDevice,$FK_process = 0,$xml_auth_rep,$type)
{
	global $DB;
	
	$Thread = new Threads;
	$conf = new plugin_tracker_config;
	
	$processes_values["devices"] = 0;
	$nb_process_query = $conf->getValue('nb_process_query');

	// Prepare processes
	$while = 'while (';
	for ($i = 1;$i <= $nb_process_query;$i++)
	{
		if ($i == $nb_process_query){
			$while .= '$t['.$i.']->isActive()';
		}else{
			$while .= '$t['.$i.']->isActive() || ';
		}
	}
	
	$while .= ') {';
	for ($i = 1;$i <= $nb_process_query;$i++)
	{
		$while .= 'echo $t['.$i.']->listen();';
	}
	$while .= '}';
	
	$close = '';
	for ($i = 1;$i <= $nb_process_query;$i++)
	{
		$close .= 'echo $t['.$i.']->close();';
	}	
	// End processes
	
	$s = 0;
	foreach ( $ArrayListDevice as $ID_Device=>$ifIP )
	{
		$s++;
		$t[$s] = $Thread->create("tracker_fullsync.php --update_device_process=1 --id=".$ID_Device." --ip=".$ifIP." --FK_process=".$FK_process." --type=".$type);

		if ($nb_process_query == $s)
		{
			eval($while);
			eval($close);
			$s = 0;
		}
		$processes_values["devices"]++;	
	}
	return $processes_values;
}



function plugin_tracker_UpdateDeviceBySNMP_process($ArrayDevice,$FK_process = 0,$xml_auth_rep,$type)
{

	$processes_values["devices"] = 0;
	$processes_values["errors"] = 0;
	
	$plugin_tracker_snmp_auth = new plugin_tracker_snmp_auth;
	$processes = new Threads;
	
	foreach ( $ArrayDevice as $ID_Device=>$ifIP )
	{
		$plugin_tracker_snmp = new plugin_tracker_snmp;

		// Get SNMP model 
		$snmp_model_ID = '';
		$snmp_model_ID = $plugin_tracker_snmp->GetSNMPModel($ID_Device,$type);
	
		if (($snmp_model_ID != "") && ($ID_Device != ""))
		{
			// ** Get oid of PortName
			$Array_Object_oid_ifName = $plugin_tracker_snmp->GetOID($snmp_model_ID,"oid_port_counter='0' AND mapping_name='ifName'");

			$Array_Object_oid_ifType = $plugin_tracker_snmp->GetOID($snmp_model_ID,"oid_port_counter='0' AND mapping_name='ifType'");

			// ** Get oid of vtpVlanName
			$Array_Object_oid_vtpVlanName = $plugin_tracker_snmp->GetOID($snmp_model_ID,"mapping_name='vtpVlanName'");

			// ** Get OIDs
			$Array_Object_oid = $plugin_tracker_snmp->GetOID($snmp_model_ID,"oid_port_dyn='0' AND oid_port_counter='0'");

			// ** Get snmp version and authentification
			$snmp_auth = $plugin_tracker_snmp_auth->GetInfos($ID_Device,$xml_auth_rep,$type);
			$snmp_version = $snmp_auth["snmp_version"];

			// ** Get from SNMP, description of equipment
			$plugin_tracker_snmp->DefineObject(array("sysDescr"=>".1.3.6.1.2.1.1.1.0"));
			$Array_sysdescr = $plugin_tracker_snmp->SNMPQuery(array("sysDescr"=>".1.3.6.1.2.1.1.1.0"),$ifIP,$snmp_version,$snmp_auth);
			if ($Array_sysdescr["sysDescr"] == "")
			{
				// SNMP error (Query impossible)
				$processes_values["errors"]++;
				$processes->addProcessValues($FK_process,"snmp_errors","","SNMP Query impossible (".$ID_Device.") Type ".$type." ");
			}
			else
			{
				//**
				$ArrayPort_LogicalNum_SNMPName = $plugin_tracker_snmp->GetPortsName($ifIP,$snmp_version,$snmp_auth,$Array_Object_oid_ifName);
	
				// **
				$ArrayPort_LogicalNum_SNMPNum = $plugin_tracker_snmp->GetPortsSNMPNumber($ifIP,$snmp_version,$snmp_auth);

				// ** Get oid ports Counter
				$ArrayPort_Object_oid = tracker_snmp_GetOIDPorts($snmp_model_ID,$ifIP,$ID_Device,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$snmp_version,$snmp_auth,$Array_Object_oid_ifType,$FK_process,$type);

				// ** Get query SNMP of switchs ports
				if (!empty($ArrayPort_Object_oid))
				$ArraySNMPPort_Object_result = $plugin_tracker_snmp->SNMPQuery($ArrayPort_Object_oid,$ifIP,$snmp_version,$snmp_auth);

				// ** Get query SNMP on switch
				$ArraySNMP_Object_result= $plugin_tracker_snmp->SNMPQuery($Array_Object_oid,$ifIP,$snmp_version,$snmp_auth);
				$processes_values["devices"]++;
				
				// ** Get link OID fields
				$Array_Object_TypeNameConstant = $plugin_tracker_snmp->GetLinkOidToFields($snmp_model_ID);
	
				// ** Update fields of switchs
				tracker_snmp_UpdateGLPIDevice($ArraySNMP_Object_result,$Array_Object_TypeNameConstant,$ID_Device,$type);

				//**
				$ArrayPortDB_Name_ID = $plugin_tracker_snmp->GetPortsID($ID_Device);
	
				// ** Update ports fields of switchs
				if (!empty($ArrayPort_Object_oid))
					UpdateGLPINetworkingPorts($ArraySNMPPort_Object_result,$Array_Object_TypeNameConstant,$ID_Device,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID,$FK_process,$type);
				$Array_trunk_ifIndex = array();

				if ($type == NETWORKING_TYPE)	
					$Array_trunk_ifIndex = cdp_trunk($ifIP,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID,$ArraySNMPPort_Object_result,$snmp_version,$snmp_auth,$FK_process,$ID_Device);

				// ** Get MAC adress of connected ports
				$array_port_trunk = array();
				if (!empty($ArrayPort_Object_oid))
					$array_port_trunk = GetMACtoPort($ifIP,$ArrayPortDB_Name_ID,$ID_Device,$snmp_version,$snmp_auth,$FK_process,$Array_trunk_ifIndex);
				if ($type ==  NETWORKING_TYPE)
				{
					// Foreach VLAN ID to GET MAC Adress on each VLAN
					$plugin_tracker_snmp->DefineObject($Array_Object_oid_vtpVlanName);
		
					$Array_vlan = $plugin_tracker_snmp->SNMPQueryWalkAll($Array_Object_oid_vtpVlanName,$ifIP,$snmp_version,$snmp_auth);
					foreach ($Array_vlan as $objectdyn=>$vlan_name)
					{
						$explode = explode(".",$objectdyn);
						$ID_VLAN = $explode[(count($explode) - 1)];
						logInFile("tracker_snmp", "		VLAN : ".$ID_VLAN."\n\n");
						GetMACtoPort($ifIP,$ArrayPortDB_Name_ID,$ID_Device,$snmp_version,$snmp_auth,$FK_process,$Array_trunk_ifIndex,$ID_VLAN,$array_port_trunk,$vlan_name);
					}
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
 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
 *
 * @return $oidList : array with ports object name and oid
 *
**/
function tracker_snmp_GetOIDPorts($snmp_model_ID,$IP,$IDNetworking,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$snmp_version,$snmp_auth,$Array_Object_oid_ifType,$FK_process=0,$type)
{

	global $DB,$LANG;

	$oidList = array();
	$object = "";
	$portcounter = "";
	$oidList = array();

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
	if ((isset($portcounter)) AND (!empty($portcounter)))
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
					AND device_type='".$type."'
					AND logical_number='".$i."' ";
			
				if ( $result = $DB->query($query) )
				{
					if ( $DB->numrows($result) == 0 )
					{
						unset($array);
						$array["logical_number"] = $i;
						$array["name"] = $ArrayPort_LogicalNum_SNMPName[$i];
						$array["iface"] = 0;
						$array["ifaddr"] = "";
						$array["ifmac"] = "";
						$array["netmask"] = "";
						$array["gateway"] = "";
						$array["subnet"] = "";
						$array["netpoint"] = 0;
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
	if (isset($Arrayportsnumber[$object]))
		$oidList = $snmp_queries->GetOID($snmp_model_ID,"oid_port_dyn='1'",$Arrayportsnumber[$object],$ArrayPort_LogicalNum_SNMPNum);
	return $oidList;

}	



/**
 * Update devices with values get by SNMP 
 *
 * @param $ArraySNMP_Object_result : array with object name => value from SNMP query
 * @param $Array_Object_TypeNameConstant : array with object name => constant in relation with fields to update 
 * @param $IDNetworking : ID of device
 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
 *
 * @return $oidList : array with ports object name and oid
 *
**/
function tracker_snmp_UpdateGLPIDevice($ArraySNMP_Object_result,$Array_Object_TypeNameConstant,$ID_Device,$type)
{

	global $DB,$LANG,$LANGTRACKER,$TRACKER_MAPPING;
	
	$printer_cartridges_max_remain = array();

	foreach($ArraySNMP_Object_result as $object=>$SNMPValue)
	{
		$explode = explode ("||", $Array_Object_TypeNameConstant[$object]);
		$object_type = $explode[0];
		$object_name = $explode[1];

		if ($TRACKER_MAPPING[$object_type][$object_name]['dropdown'] != "")
		{
			$SNMPValue = externalImportDropdown($TRACKER_MAPPING[$object_type][$object_name]['dropdown'],$SNMPValue,0);
		}
		// Update fields
		
		switch ($type)
		{
			case NETWORKING_TYPE :
				$Field = "FK_networking";
				if ($TRACKER_MAPPING[$object_type][$object_name]['table'] == "glpi_networking")
					$Field = "ID";
				break;
			case PRINTER_TYPE :
				$Field = "FK_printers";
				if ($TRACKER_MAPPING[$object_type][$object_name]['table'] == "glpi_printers")
					$Field = "ID";
				break;
		}
		
		$SNMPValue = preg_replace('/^\"/', '',$SNMPValue);
		$SNMPValue = preg_replace('/\"$/', '',$SNMPValue);
		
		if (($object_name == "ram") OR ($object_name == "memory"))
		{
			if (ereg("KBytes", $SNMPValue))
			{
				$SNMPValue = ceil($SNMPValue / 1024) ;
			}
			else
			{
				$SNMPValue = ceil(($SNMPValue / 1024) / 1024) ;
			}
			if ($object_type == PRINTER_TYPE)
				$SNMPValue .= " MB";
		}
		
		if ($TRACKER_MAPPING[$object_type][$object_name]['table'] == "glpi_plugin_tracker_printers_cartridges")
		{
			$object_name_clean = str_replace("MAX", "", $object_name);
			$object_name_clean = str_replace("REMAIN", "", $object_name_clean);
			if (ereg("MAX",$object_name))
			{
				$printer_cartridges_max_remain[$object_name_clean]["MAX"] = $SNMPValue;
			}
			if (ereg("REMAIN",$object_name))
			{
				$printer_cartridges_max_remain[$object_name_clean]["REMAIN"] = $SNMPValue;
			}
			if ((isset($printer_cartridges_max_remain[$object_name_clean]["MAX"])) AND (isset($printer_cartridges_max_remain[$object_name_clean]["REMAIN"])))
			{
				$pourcentage = ceil((100 * $printer_cartridges_max_remain[$object_name_clean]["REMAIN"]) / $printer_cartridges_max_remain[$object_name_clean]["MAX"]);
				// Test existance of row in MySQl
					$query_sel = "SELECT * FROM ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
					WHERE ".$Field."='".$ID_Device."'
						AND object_name='".$object_name_clean."' ";
					$result_sel = $DB->query($query_sel);
					if ($DB->numrows($result_sel) == "0")
					{
						$queryInsert = "INSERT INTO ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
						(".$Field.",object_name)
						VALUES('".$ID_Device."', '".$object_name_clean."') ";
			
						$DB->query($queryInsert);
					}
				$queryUpdate = "UPDATE ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
				SET ".$TRACKER_MAPPING[$object_type][$object_name]['field']."='".$pourcentage."' 
				WHERE ".$Field."='".$ID_Device."'
					AND object_name='".$object_name_clean."' ";

				$DB->query($queryUpdate);
				unset($printer_cartridges_max_remain[$object_name_clean]["MAX"]);
				unset($printer_cartridges_max_remain[$object_name_clean]["REMAIN"]);
			}
			else
			{
				$queryUpdate = "UPDATE ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
				SET ".$TRACKER_MAPPING[$object_type][$object_name]['field']."='".$SNMPValue."' 
				WHERE ".$Field."='".$ID_Device."'
					AND object_name='".$object_name."' ";
		
				$DB->query($queryUpdate);
			}
		}
		else if (ereg("pagecounter",$object_name))
		{
			// Detect if the script has wroten a line for the counter today (if yes, don't touch, else add line)
			$today = strftime("%Y-%m-%d", time());
			$query_line = "SELECT * FROM glpi_plugin_tracker_printers_history
			WHERE date LIKE '".$today."%'
				AND FK_printers='".$ID_Device."' ";
			$result_line = $DB->query($query_line);
			if ($DB->numrows($result_line) == "0")
			{
				$queryInsert = "INSERT INTO ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
				(".$TRACKER_MAPPING[$object_type][$object_name]['field'].",".$Field.", date)
				VALUES('".$SNMPValue."','".$ID_Device."', '".$today."') ";
	
				$DB->query($queryInsert);
			}
			else
			{
				$data_line = $DB->fetch_assoc($result_line);
				if ($data_line[$TRACKER_MAPPING[$object_type][$object_name]['field']] == "0")
				{
					$queryUpdate = "UPDATE ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
					SET ".$TRACKER_MAPPING[$object_type][$object_name]['field']."='".$SNMPValue."' 
					WHERE ".$Field."='".$ID_Device."'
						AND date LIKE '".$today."%' ";			
				
					$DB->query($queryUpdate);
				}
			}
		
		}
		else if ($TRACKER_MAPPING[$object_type][$object_name]['table'] != "")
		{
			if (($TRACKER_MAPPING[$object_type][$object_name]['field'] == "cpu") AND ($SNMPValue == ""))
				$SNMPValue = 0;
			$queryUpdate = "UPDATE ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
			SET ".$TRACKER_MAPPING[$object_type][$object_name]['field']."='".$SNMPValue."' 
			WHERE ".$Field."='".$ID_Device."'";

			$DB->query($queryUpdate);
		}
	}
}



function UpdateGLPINetworkingPorts($ArraySNMPPort_Object_result,$Array_Object_TypeNameConstant,$IDNetworking,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortDB_Name_ID,$FK_process=0,$type)
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
		AND device_type='".$type."'
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
					if ($DB->numrows($result_select) != "0")
					{
						$SNMPValue_old = $DB->result($result_select, 0, $TRACKER_MAPPING[$object_type][$object_name]['field']);
					}
					else
					{
						$SNMPValue_old = "";
					}					
					// Update
					if ($SNMPValue != '')
					{
						$queryUpdate = "UPDATE ".$TRACKER_MAPPING[$object_type][$object_name]['table']."
						SET ".$TRACKER_MAPPING[$object_type][$object_name]['field']."='".$SNMPValue."' 
						WHERE ".$ID_field."='".$data["ID"]."'";
	
						$DB->query($queryUpdate);
						// Delete port wire if port is internal disable
						if (($object_name == "ifinternalstatus") AND (($SNMPValue == "2") OR ($SNMPValue == "down(2)")))
						{
							$netwire=new Netwire;
							addLogConnection("remove",$netwire->getOppositeContact($data["ID"]),$FK_process);
							addLogConnection("remove",$data["ID"],$FK_process);
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
	}
}



function GetMACtoPort($IP,$ArrayPortsID,$IDNetworking,$snmp_version,$snmp_auth,$FK_process=0,$Array_trunk_ifIndex,$vlan="",$array_port_trunk=array(),$vlan_name="")
{
	global $DB;
//ECHO ">>>>>>>>>>>>>>>>>>>> NETWORKING <<<<<<<<<<<<<<<<<<<<<<<<<\n";
	$processes = new Threads;
	$netwire = new Netwire;
	$snmp_queries = new plugin_tracker_snmp;

	$ArrayMACAdressTableObject = array("dot1dTpFdbAddress" => ".1.3.6.1.2.1.17.4.3.1.1");
	$ArrayIPMACAdressePhysObject = array("ipNetToMediaPhysAddress" => ".1.3.6.1.2.1.4.22.1.2");
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
		if ((!isset($oidExplode[(count($oidExplode)-5)]))
			OR (!isset($oidExplode[(count($oidExplode)-4)]))
			OR (!isset($oidExplode[(count($oidExplode)-3)]))
			OR (!isset($oidExplode[(count($oidExplode)-2)]))
			OR (!isset($oidExplode[(count($oidExplode)-1)]))
			)
		{
			$OIDBridgePortNumber = ".1.3.6.1.2.1.17.4.3.1.2.0.".
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
				
				$ArrayBridgePortifIndexObject = array("dot1dBasePortIfIndex" => ".1.3.6.1.2.1.17.1.4.1.2.".$BridgePortNumber);
		
				$snmp_queries->DefineObject($ArrayBridgePortifIndexObject);
		
				$ArrayBridgePortifIndex = $snmp_queries->SNMPQuery($ArrayBridgePortifIndexObject,$IP,$snmp_version,$snmp_auth);
				
				foreach($ArrayBridgePortifIndex as $oidBridgePortifIndex=>$BridgePortifIndex)
				{
					if (($BridgePortifIndex == "") OR ($BridgePortifIndex == "No Such Instance currently exists at this OID"))
						break;
						
					if ((isset($Array_trunk_ifIndex[$BridgePortifIndex])) AND ($Array_trunk_ifIndex[$BridgePortifIndex] == "1"))
						break;
						
					//echo "BridgePortifIndex : ".$BridgePortifIndex."\n";
				
					$ArrayifNameObject = array("ifName" => ".1.3.6.1.2.1.31.1.1.1.1.".$BridgePortifIndex);
		
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
	$arrayTRUNKmod = array("vlanTrunkPortDynamicStatus.".$BridgePortifIndex => ".1.3.6.1.4.1.9.9.46.1.6.1.1.14.".$BridgePortifIndex);
	
	$snmp_queries->DefineObject($arrayTRUNKmod);
			
	$Arraytrunktype = $snmp_queries->SNMPQuery($arrayTRUNKmod,$IP,$snmp_version,$snmp_auth);
	
	echo "================================\n";
	echo "VLAN :".$vlan."\n";
	echo "TRUNKSTATUS :".$Arraytrunktype["vlanTrunkPortDynamicStatus.".$BridgePortifIndex]."\n";
	echo "MACADRESS :".$MacAddress."\n";
	echo "INTERFACE :".$ifName."\n";
	
						
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
							if ($vlan == "")
							{
								$array_port_trunk[$ArrayPortsID[$ifName]] = 1;
							}
							
							// Add port trunk
	/*						$query_trunk = "SELECT *,glpi_plugin_tracker_networking_ports.id AS sid  FROM glpi_networking_ports
								LEFT JOIN glpi_plugin_tracker_networking_ports
								ON glpi_plugin_tracker_networking_ports.FK_networking_ports = glpi_networking_ports.id
								WHERE device_type='2' 
									AND on_device='".$IDNetworking."' 
									AND name='".$ifName."' ";
							$result_trunk=$DB->query($query_trunk);
							while ($data_trunk=$DB->fetch_array($result_trunk))
							{
								if($data_trunk['trunk'] == "0")
								{
									$query_update = "UPDATE glpi_plugin_tracker_networking_ports
									SET trunk='1'
									WHERE id='".$data_trunk['sid']."' ";
									$DB->query($query_update);
									tracker_snmp_addLog($data_trunk["FK_networking_ports"],"trunk","0","1",$FK_process);
								}
							}
	*/
						}						
						else if ($Arraytrunktype["vlanTrunkPortDynamicStatus.".$BridgePortifIndex] == "1") // It's a trunk port
						{
	echo "PASSAGE ... OK (2) => Refusé\n";
							$queryPortEnd = "SELECT * 
							
							FROM glpi_networking_ports
							
							WHERE ifmac IN ('".$MacAddress."','".strtoupper($MacAddress)."')
								AND on_device!='".$IDNetworking."' ";
							$queryPortEnd = "";
							if ($vlan == "")
							{
								$array_port_trunk[$ArrayPortsID[$ifName]] = 1;
							}
								
							// Add port trunk
	/*						$query_trunk = "SELECT *,glpi_plugin_tracker_networking_ports.id AS sid  FROM glpi_networking_ports
								LEFT JOIN glpi_plugin_tracker_networking_ports
								ON glpi_plugin_tracker_networking_ports.FK_networking_ports = glpi_networking_ports.id
								WHERE device_type='2' 
									AND on_device='".$IDNetworking."' 
									AND name='".$ifName."' ";
							$result_trunk=$DB->query($query_trunk);
							while ($data_trunk=$DB->fetch_array($result_trunk))
							{
								if($data_trunk['trunk'] == "0")
								{
									$query_update = "UPDATE glpi_plugin_tracker_networking_ports
									SET trunk='1'
									WHERE id='".$data_trunk['sid']."' ";
									$DB->query($query_update);
									tracker_snmp_addLog($data_trunk["FK_networking_ports"],"trunk","0","1",$FK_process);
								}
							}
	*/
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
								//else
								//{
								//	$array_port_trunk[$ArrayPortsID[$ifName]] = 1;
								//}						
								
								if (!isset($ArrayPortsID[$ifName]))
								{
									$traitement = 0;
								}
	
								if ( ($DB->numrows($resultPortEnd) != 0) && ($traitement == "1") )
								{
	//echo "TRAITEMENT :".$traitement."\n";
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
function cdp_trunk($IP,$ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum,$ArrayPortsID,$ArraySNMPPort_Object_result,$snmp_version,$snmp_auth,$FK_process,$ID_Device)
{
	global $DB;

	$snmp_queries = new plugin_tracker_snmp;

	$Array_trunk_IP_hex = array("cdpCacheAddress" => ".1.3.6.1.4.1.9.9.23.1.2.1.1.4");
	$Array_trunk_ifDescr = array("cdpCacheDevicePort" => ".1.3.6.1.4.1.9.9.23.1.2.1.1.7");
	$Array_trunk_ifIndex = array();

	$ArrayPort_LogicalNum_SNMPNum = array_flip($ArrayPort_LogicalNum_SNMPNum);

	// Get trunk port directly from oid
	$arrayTRUNKmod = array("vlanTrunkPortDynamicStatus" => ".1.3.6.1.4.1.9.9.46.1.6.1.1.14");
	$snmp_queries->DefineObject($arrayTRUNKmod);
		
	$Arraytrunktype = $snmp_queries->SNMPQueryWalkAll($arrayTRUNKmod,$IP,$snmp_version,$snmp_auth);
	
	foreach($Arraytrunktype as $oidtrunkPort=>$ifIndex_by_snmp)
	{

		if ($ifIndex_by_snmp == "1")
		{
			$oidExplode = explode(".", $oidtrunkPort);
			
			$Array_trunk_ifIndex[$oidExplode[(count($oidExplode)-1)]] = 1;

		}
	}
	
	// Get trunk port from CDP


	// Get by SNMP query the IP addresses of the switch connected ($Array_trunk_IP_hex)
	$snmp_queries->DefineObject($Array_trunk_IP_hex);
	$Array_trunk_IP_hex_result = $snmp_queries->SNMPQueryWalkAll($Array_trunk_IP_hex,$IP,$snmp_version,$snmp_auth);

	// Get by SNMP query the Name of port (ifDescr)
	$snmp_queries->DefineObject($Array_trunk_ifDescr);
	$Array_trunk_ifDescr_result = $snmp_queries->SNMPQueryWalkAll($Array_trunk_ifDescr,$IP,$snmp_version,$snmp_auth);
//var_dump($Array_trunk_ifDescr_result);
	foreach($Array_trunk_IP_hex_result AS $object=>$result)
	{
		$explode = explode(".", $object);
		$ifIndex = $explode[(count($explode)-2)];
		$end_Number = $explode[(count($explode)-1)];
		
		$Array_trunk_ifIndex[$ifIndex] = 1;
//echo "IFINDEX **** > ".$ifIndex."\n";
//echo "IFINDEX NUM Logic > ".$ArrayPort_LogicalNum_SNMPNum[$ifIndex]."\n";
//echo "IFINDEX NAME Logic > ".$ArrayPort_LogicalNum_SNMPName[$ArrayPort_LogicalNum_SNMPNum[$ifIndex]]."\n";

		// Convert IP hex to decimal
		$Array_ip_switch_trunk = explode(" ",$result);
		$ip_switch_trunk = "";
		if (count($Array_ip_switch_trunk) > 2)
		{
			for($i = 0; $i < 4;$i++)
			{
				$ip_switch_trunk .= hexdec($Array_ip_switch_trunk[$i]);
				if ($i < 3)
					$ip_switch_trunk .= ".";
			}
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
		LEFT JOIN glpi_plugin_tracker_networking_ifaddr
		ON glpi_plugin_tracker_networking_ifaddr.FK_networking = glpi_networking_ports.on_device
		WHERE logical_number='".$ArrayPort_LogicalNum_SNMPNum[$ifIndex]."' 
			AND device_type='2' 
			AND glpi_plugin_tracker_networking_ifaddr.ifaddr='".$IP."' ";
//echo "QUERY CDP :".$query."\n";
		$result = $DB->query($query);		
		$data = $DB->fetch_assoc($result);
//var_dump($data);		
//echo "QUERY :".$query."\n";
//echo "PORTID :".$data["ID"]." -> ".$PortID."(".$ArrayPort_LogicalNum_SNMPNum[$ifIndex].")\n";
		if ((!empty($data["ID"])) AND (!empty($PortID)))
			$snmp_queries->PortsConnection($data["ID"], $PortID,$FK_process);
	}
	
	// ** Update for all ports on this network device the field 'trunk' in glpi_plugin_tracker_networking_ports
	foreach($ArrayPort_LogicalNum_SNMPNum AS $ifIndex=>$logical_num)
	{
		$query = "SELECT *,glpi_plugin_tracker_networking_ports.id AS sid  FROM glpi_networking_ports
			LEFT JOIN glpi_plugin_tracker_networking_ports
			ON glpi_plugin_tracker_networking_ports.FK_networking_ports = glpi_networking_ports.id
			WHERE device_type='2' 
				AND on_device='".$ID_Device."' 
				AND logical_number='".$logical_num."' ";
		$result=$DB->query($query);
		while ($data=$DB->fetch_array($result))
		{
			if ((isset($Array_trunk_ifIndex[$ifIndex])) AND ($Array_trunk_ifIndex[$ifIndex] == "1"))
			{
				if ($data['trunk'] == "0")
				{
					$query_update = "UPDATE glpi_plugin_tracker_networking_ports
					SET trunk='1'
					WHERE id='".$data['sid']."' ";
					$DB->query($query_update);
					tracker_snmp_addLog($data["FK_networking_ports"],"trunk","0","1",$FK_process);
				}
			}
			else if($data['trunk'] == "1")
			{
				$query_update = "UPDATE glpi_plugin_tracker_networking_ports
				SET trunk='0'
				WHERE id='".$data['sid']."' ";
				$DB->query($query_update);
				tracker_snmp_addLog($data["FK_networking_ports"],"trunk","1","0",$FK_process);
			}
			
		}
	}

	
	return $Array_trunk_ifIndex;
}


// * $ArrayListNetworking : array of device infos : ID => ifaddr 
function plugin_tracker_snmp_networking_ifaddr($ArrayListDevice,$xml_auth_rep)
{
	global $DB;

	$plugin_tracker_snmp_auth = new plugin_tracker_snmp_auth;
	$plugin_tracker_snmp = new plugin_tracker_snmp;

	$ifaddr_add = array();
	$ifaddr = array();

	$query = "SELECT * FROM glpi_plugin_tracker_networking_ifaddr";
	if ( $result=$DB->query($query) )
	{
		while ( $data=$DB->fetch_array($result) )
		{
			$ifaddr[$data["ifaddr"]] = $data["FK_networking"];
		}
	}

	$oid_ifaddr_switch = array("ipAdEntAddr" => ".1.3.6.1.2.1.4.20.1.1");
	
	foreach ( $ArrayListDevice as $ID_Device=>$ifIP )
	{
		// Get SNMP model 
		$snmp_model_ID = '';
		$snmp_model_ID = $plugin_tracker_snmp->GetSNMPModel($ID_Device,NETWORKING_TYPE);
		if (($snmp_model_ID != "") && ($ID_Device != ""))
		{
			// ** Get snmp version and authentification
			$snmp_auth = $plugin_tracker_snmp_auth->GetInfos($ID_Device,$xml_auth_rep,NETWORKING_TYPE);
			$snmp_version = $snmp_auth["snmp_version"];
			
			$Array_Device_ifaddr = $plugin_tracker_snmp->SNMPQueryWalkAll($oid_ifaddr_switch,$ifIP,$snmp_version,$snmp_auth);

			foreach ($Array_Device_ifaddr as $object=>$ifaddr_snmp)
			{
				if ($ifaddr[$ifaddr_snmp] == $ID_Device)
				{
					unset ($ifaddr[$ifaddr_snmp]);
				}
				else
				{
					$ifaddr_add[$ifaddr_snmp] = $ID_Device;
				}
			}
		}
	}
	foreach($ifaddr as $ifaddr_snmp=>$FK_networking)
	{
		$query_delete = "DELETE FROM glpi_plugin_tracker_networking_ifaddr
		WHERE FK_networking='".$FK_networking."'
			AND ifaddr='".$ifaddr_snmp."' ";
		$DB->query($query_delete);
	}
	foreach($ifaddr_add as $ifaddr_snmp=>$FK_networking)
	{
		$query_insert = "INSERT INTO glpi_plugin_tracker_networking_ifaddr
		(FK_networking,ifaddr)
		VALUES('".$FK_networking."','".$ifaddr_snmp."') ";
		$DB->query($query_insert);
	}
}


?>