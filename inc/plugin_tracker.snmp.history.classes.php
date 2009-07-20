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

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


class PluginTrackerSNMPHistory extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_tracker_snmp_history";
		$this->type = PLUGIN_TRACKER_SNMP_HISTORY;
	}

	/**
	 * Insert port history with connection and disconnection
	 *
	 * @param $status status of port ('make' or 'remove')
	 * @param $array with values : $array["FK_ports"], $array["value"], $array["device_type"] and $array["device_ID"]
	 *
	 * @return ID of inserted line
	 *
	**/
	function insert_connection($status,$array,$FK_process=0) {
		global $DB,$CFG_GLPI;
		if ($status == "remove") {
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,old_value,old_device_type,old_device_ID,date_mod,FK_process)
			VALUES('".$array["FK_ports"]."','".$array["value"]."','".$array["device_type"]."','".$array["device_ID"]."','".date("Y-m-d H:i:s")."','".$FK_process."')";
		
		} else if ($status == "make") {
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,new_value,new_device_type,new_device_ID,date_mod,FK_process)
			VALUES('".$array["FK_ports"]."','".$array["value"]."','".$array["device_type"]."','".$array["device_ID"]."','".date("Y-m-d H:i:s")."','".$FK_process."')";
	
		} else if ($status == "field") {
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,field,old_value,new_value,date_mod,FK_process)
			VALUES('".$array["FK_ports"]."','".addslashes($array["field"])."','".$array["old_value"]."','".$array["new_value"]."','".date("Y-m-d H:i:s")."','".$FK_process."')";
	
		}
		$DB->query($query);
		return mysql_insert_id();
	}
}

?>
