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

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
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
function plugin_tracker_discovery_update_devices($array, $target) {
	global $DB;

	foreach ($array as $key=>$value) {
		if (strstr($key, "model_infos")) {
			$explode = explode ("-", $key);
			$query = "UPDATE glpi_plugin_tracker_discovery
			SET FK_model_infos='".$value."',type='".$array['type-'.$explode[1]]."'
			WHERE ID='".$explode[1]."' ";
			$DB->query($query);
		}
	}
}



/**
 * Function to import discovered device
 *
 * @param $discovery_ID ID of the device to import
 *
 * @return nothing
 *
**/
function plugin_tracker_discovery_import($discovery_ID) {
	global $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
	
	$td = new PluginTrackerDiscovery;
	
	$td->getFromDB($discovery_ID);
	
	$Import = 0;

	switch ($td->fields['type']) {
		case PRINTER_TYPE :
			$Printer = new Printer;
			$Netport = new Netport;
			$tracker_printers = new plugin_tracker_printers;
			$tracker_config_snmp_printer = new PluginTrackerConfigSnmpPrinter;

			$tracker_config_snmp_printer->getFromDB(1);
			$data['state'] = $tracker_config_snmp_printer->fields["active_device_state"];
			if (empty($data['state'])) {
				$data['state'] = 0;
         }
			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Printer->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['ifaddr'] = $td->fields['ifaddr'];
			$Netport->add($data_Port);

			$data_tracker["FK_printers"] = $ID_Device;
			$data_tracker["FK_model_infos"] = $td->fields["FK_model_infos"];
			$data_tracker["FK_snmp_connection"] = $td->fields["FK_snmp_connection"];
			$tracker_printers->add($data_tracker);			
			
			$query_del = "DELETE FROM glpi_plugin_tracker_discovery
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;

		case NETWORKING_TYPE :
			$Netdevice = new Netdevice;
			$tracker_networking = new glpi_plugin_tracker_networking;
			$tracker_config_snmp_networking = new PluginTrackerConfigSnmpNetworking;

			$tracker_config_snmp_networking->getFromDB(1);
			$data['state'] = $tracker_config_snmp_networking->fields["active_device_state"];
			if (empty($data['state'])) {
				$data['state'] = 0;
         }
			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$data["ifaddr"] = $td->fields["ifaddr"];
			$ID_Device = $Netdevice->add($data);

			$data_tracker["FK_networking"] = $ID_Device;
			$data_tracker["FK_model_infos"] = $td->fields["FK_model_infos"];
			$data_tracker["FK_snmp_connection"] = $td->fields["FK_snmp_connection"];
			$tracker_networking->add($data_tracker);

			$query_del = "DELETE FROM glpi_plugin_tracker_discovery
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;

		case PERIPHERAL_TYPE :
			$Peripheral = new Peripheral;
			$Netport = new Netport;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Peripheral->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['ifaddr'] = $td->fields['ifaddr'];
			$Netport->add($data_Port);
			
			$query_del = "DELETE FROM glpi_plugin_tracker_discovery
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;

		case COMPUTER_TYPE :
			$Computer = new Computer;
			$Netport = new Netport;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Computer->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['ifaddr'] = $td->fields['ifaddr'];
			$Netport->add($data_Port);

			$query_del = "DELETE FROM glpi_plugin_tracker_discovery
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;
      
		case PHONE_TYPE :
			$Phone = new Phone;
			$Netport = new Netport;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Phone->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['ifaddr'] = $td->fields['ifaddr'];
			$Netport->add($data_Port);

			$query_del = "DELETE FROM glpi_plugin_tracker_discovery
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;
	}
	if ($Import != "0") {
		addMessageAfterRedirect($LANGTRACKER["discovery"][5]." : ".$Import );
   }
}

function plugin_tracker_discovery_criteria($discovery,$link_ip,$link_name,$link_serial,$link2_ip,$link2_name,$link2_serial,$agent_id,$FK_model,$criteria_pass2=0) {
	global $DB,$CFG_GLPI,$LANG,$LANGTRACKER;

	$ci = new commonitem;

	if($criteria_pass2 == "1") {
		$link_ip = $link2_ip;
		$link_name = $link2_name;
		$link_serial = $link2_serial;
	}

	$Array_criteria = array();
	if ($link_ip == "1") {
		$Array_criteria[] = "ifaddr='".$discovery->ip."'";
		$array_search[] = $discovery->ip;
	}
	if ($link_name == "1") {
		$Array_criteria[] = "name='".plugin_tracker_hex_to_string($discovery->name)."'";
		$array_search[] = plugin_tracker_hex_to_string($discovery->name);
	}
	if ($link_serial == "1") {
		$Array_criteria[] = "serial='".$discovery->serial."'";
		$array_search[] = $discovery->serial;
	}

	if (count($Array_criteria) == "0") {
		// Insert device in discovered device
		$query_sel = "SELECT * FROM glpi_plugin_tracker_discovery
		WHERE ifaddr='".$discovery->ip."'
			AND name='".plugin_tracker_hex_to_string($discovery->name)."'
			AND descr='".$discovery->description."'
			AND serialnumber='".$discovery->serial."'
			AND FK_entities='".$discovery->entity."' ";
		$result_sel = $DB->query($query_sel);
		if ($DB->numrows($result_sel) == "0") {
			$query = "INSERT INTO glpi_plugin_tracker_discovery
			(date,ifaddr,name,descr,serialnumber,type,FK_agents,FK_entities,FK_model_infos,FK_snmp_connection)
			VALUES('".$discovery->date."','".$discovery->ip."','".plugin_tracker_hex_to_string($discovery->name)."','".$discovery->description."','".$discovery->serial."', '".$discovery->type."', '".$agent_id."', '".$discovery->entity."','".$FK_model."','".$discovery->authSNMP."')";
			$DB->query($query);
		}
	} else {
		$discovery_empty = 1;
		for ($i=0 ; $i < count($array_search) ; $i++) {
			if (!empty($array_search[$i]))
				$discovery_empty = 0;
		}

		if (($discovery_empty == "1") AND ($criteria_pass2 == "0")) {
			// ** On passe aux critères 2
			plugin_tracker_discovery_criteria($discovery,$link_ip,$link_name,$link_serial,$link2_ip,$link2_name,$link2_serial,$agent_id,$FK_model,1);
			return;
		} else {
			// **  On cherche si le matos existe
			if ($discovery->type == NETWORKING_TYPE
            OR $discovery->type == 0
            OR $discovery->type == ""
            OR !isset($discovery->type)) {
            
				$query_search = "SELECT * FROM glpi_networking
				WHERE FK_entities='".$discovery->entity."'
					AND ".$Array_criteria[0];
				for ($i=1 ; $i < count($Array_criteria) ; $i++) {
					$query_search .= " AND ".$Array_criteria[$i];
            }
			} else {
				$ci->setType($discovery->type,true);
				if (!strstr($Array_criteria[0], "ifaddr")) {
					$Array_criteria[0] = $ci->obj->table.".".$Array_criteria[0];
            }
				$query_search = "SELECT ".$ci->obj->table.".name AS name,
				serial, glpi_networking_ports.ifaddr AS ifaddr
				FROM ".$ci->obj->table."
				LEFT JOIN glpi_networking_ports ON on_device=".$ci->obj->table.".ID
					AND device_type=".$discovery->type."
				WHERE FK_entities='".$discovery->entity."'
					AND ".$Array_criteria[0];
				for ($i=1 ; $i < count($Array_criteria) ; $i++) {
					$query_search .= " AND ".$ci->obj->table.".".$Array_criteria[$i];
            }
			}
			$result_search = $DB->query($query_search);
			if (($DB->numrows($result_search) == "0") AND ($criteria_pass2 == "0")) {
				// ** On passe aux critères 2
				plugin_tracker_discovery_criteria($discovery,$link_ip,$link_name,$link_serial,$link2_ip,$link2_name,$link2_serial,$agent_id,$FK_model,1);
				return;
			} else if ($DB->numrows($result_search) == "0") {
				// Insert device in discovered device
				$query_sel = "SELECT * FROM glpi_plugin_tracker_discovery
				WHERE ifaddr='".$discovery->ip."'
					AND name='".plugin_tracker_hex_to_string($discovery->name)."'
					AND descr='".$discovery->description."'
					AND serialnumber='".$discovery->serial."'
					AND FK_entities='".$discovery->entity."' ";
				$result_sel = $DB->query($query_sel);
				if ($DB->numrows($result_sel) == "0") {
					$query = "INSERT INTO glpi_plugin_tracker_discovery
					(date,ifaddr,name,descr,serialnumber,type,FK_agents,FK_entities,FK_model_infos,FK_snmp_connection)
					VALUES('".$discovery->date."','".$discovery->ip."','".plugin_tracker_hex_to_string($discovery->name)."','".$discovery->description."','".$discovery->serial."', '".$discovery->type."', '".$agent_id."', '".$discovery->entity."','".$FK_model."','".$discovery->authSNMP."')";
					$DB->query($query);
				}
			}
		}
	}
}

?>