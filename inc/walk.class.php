<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpWalk extends CommonDBTM {

	function GetoidValues($device_snmp) {
		global $DB;

		foreach($device_snmp->get as $snmpget) {
			if ($snmpget->oid == "noSuchInstance")
				$snmpget->oid = "";
			$oidvalues["$snmpget->object"]["$snmpget->vlan"] = "$snmpget->oid";
		}
		foreach($device_snmp->walk as $snmpwalk) {
			$oid = "";
			if ((!empty($snmpwalk->oid)) AND ($snmpget->oid != "noSuchInstance")) {
				$oid = $snmpwalk->oid;
         }
			$vlan = "";
			if (!empty($snmpwalk->vlan)) {
				$vlan = $snmpwalk->vlan;
         }
			$oidvalues["$snmpwalk->object"]["$vlan"] = "$oid";
		}
		if (!isset($oidvalues)) {
			return;
      } else {
			return $oidvalues;
      }
	}
	
	

	function GetoidValuesFromWalk($oidvalues,$oidsModel,$oid_dyn=0,$vlan="") {
		foreach ($oidvalues as $oid=>$value) {
			if (strstr($oid, $oidsModel.".")) {
				if ($oid_dyn == "0") {
					$List[] = $value[$vlan];
            } else if ($oid_dyn == "1") {
					$value = str_replace($oidsModel.".", "", $oid);
					$List[] = $value;
				}
			}
		}
		if (!isset($List)) {
			return;
      }
		return $List;
	}	
}

?>