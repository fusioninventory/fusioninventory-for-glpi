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

if (!defined('GLPI_ROOT'))
	die("Sorry. You can't access directly to this file");


class plugin_tracker_walk extends CommonDBTM
{
/*	function __construct()
	{
		$this->table = "glpi_plugin_tracker_walks";
		$this->type = -1;
	}
*/


	function GetoidValues($device_snmp)
	{
		global $DB;

		foreach($device_snmp->get as $snmpget){
			if ($snmpget->oid == "noSuchInstance")
				$snmpget->oid = "";
			$oidvalues["$snmpget->object"]["$snmpget->vlan"] = "$snmpget->oid";
//			oid=$snmpget->object;
//			value=$snmpget->oid;
//			vlan=$snmpget->vlan;
		}
		foreach($device_snmp->walk as $snmpwalk){
			$oid = "";
			if ((!empty($snmpwalk->oid)) AND ($snmpget->oid != "noSuchInstance"))
				$oid = $snmpwalk->oid;
			$vlan = "";
			if (!empty($snmpwalk->vlan))
				$vlan = $snmpwalk->vlan;
			$oidvalues["$snmpwalk->object"]["$vlan"] = "$oid";
		}
		if (!isset($oidvalues))
			return;
		else
			return $oidvalues;
	}
	
	

	function GetoidValuesFromWalk($oidvalues,$oidsModel,$oid_dyn=0,$vlan="")
	{
		foreach ($oidvalues as $oid=>$value)
		{
			if (strstr($oid, $oidsModel."."))
			{
				if ($oid_dyn == "0")
					$List[] = $value[$vlan];
				else if ($oid_dyn == "1")
				{
					$value = str_replace($oidsModel.".", "", $oid);
					$List[] = $value;
				}
			}
		}
		if (!isset($List))
			return;
		return $List;
	}
	
	
	
	function GetValuesFromOid($oid)
	{
	
	
	
	}
}
?>