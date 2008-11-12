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
function UpdateNetworkBySNMP($ArrayListNetworking)
{
	foreach ( $ArrayListNetworking as $IDNetworking=>$ifIP )
	{
		$updateNetwork = new plugin_tracker_snmp;
		// Get SNMP model 
		$IDModelInfos = $updateNetwork->GetSNMPModel($IDNetworking);
		if (($IDModelInfos != "") && ($IDNetworking != ""))
		{
			// ** Get oid
			$ArrayOID = $updateNetwork->GetOID($IDModelInfos);
			//**
			$ArrayPortsName = $updateNetwork->GetPortsName($ifIP);
			//**
			$ArrayPortsID = $updateNetwork->GetPortsID($IDNetworking);
			// **
			$ArrayPortsSNMPNumber = $updateNetwork->GetPortsSNMPNumber($ifIP);
			// ** Get oid ports Counter
			$ArrayOIDPorts = $updateNetwork->GetOIDPorts($IDModelInfos,$ifIP,$IDNetworking,$ArrayPortsName,$ArrayPortsSNMPNumber);
			// ** Define oid and object name
			$updateNetwork->DefineObject($ArrayOID);
			// ** Get query SNMP on switch
			$ArraySNMPResult = $updateNetwork->SNMPQuery($ArrayOID,$ifIP);
			// ** Define oid and object name
			//$updateNetwork->DefineObject($ArrayOIDPorts);
			// ** Get query SNMP of switchs ports
			$ArraySNMPResultPorts = $updateNetwork->SNMPQuery($ArrayOIDPorts,$ifIP);
			// ** Get link OID fields
			$ArrayLinks = $updateNetwork->GetLinkOidToFields($IDModelInfos);
			// ** Update fields of switchs
			$updateNetwork->UpdateGLPINetworking($ArraySNMPResult,$ArrayLinks,$IDNetworking);
			// ** Update ports fields of switchs
			$updateNetwork->UpdateGLPINetworkingPorts($ArraySNMPResultPorts,$ArrayLinks,$IDNetworking,$ArrayPortsSNMPNumber);
			
			// ** Get MAC adress of connected ports
			$updateNetwork->GetMACtoPort($ifIP,$ArrayPortsID,$IDNetworking);
		}
	} 

}


?>