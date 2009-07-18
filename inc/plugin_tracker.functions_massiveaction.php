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


function plugin_tracker_assign($id, $source_type, $source_field, $source_value) {
	global $DB;
	$plugin_tracker_snmp = new plugin_tracker_snmp;
	
	if ($source_field == "model") {
		// Get auth
		$snmp_auth = new plugin_tracker_snmp_auth;
		switch ($source_type) {
			case NETWORKING_TYPE :
				$FK_snmp_auth_DB = $snmp_auth->GetSNMPAuth($id,NETWORKING_TYPE);
				$plugin_tracker_snmp->update_network_infos($id, $source_value, $FK_snmp_auth_DB);
				break;

			case PRINTER_TYPE :
				$FK_snmp_auth_DB = $snmp_auth->GetSNMPAuth($id,PRINTER_TYPE);
				$plugin_tracker_snmp->update_printer_infos($id, $source_value, $FK_snmp_auth_DB);
				break;
		}
	} else if ($source_field == "auth") {
		switch ($source_type) {
			case NETWORKING_TYPE :
				// Get model
				$FK_model_DB = $plugin_tracker_snmp->GetSNMPModel($id,NETWORKING_TYPE);
				$plugin_tracker_snmp->update_network_infos($id, $FK_model_DB, $source_value);
				break;

			case PRINTER_TYPE :
				// Get model
				$FK_model_DB = $plugin_tracker_snmp->GetSNMPModel($id,PRINTER_TYPE);
				$plugin_tracker_snmp->update_printer_infos($id, $FK_model_DB, $source_value);
				break;
		}		
	}
}


?>