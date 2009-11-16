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
			$query = "UPDATE `glpi_plugin_tracker_discovery`
                   SET `FK_model_infos`='".$value."',`type`='".$array['type-'.$explode[1]]."'
                   WHERE `ID`='".$explode[1]."';";
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
function plugin_tracker_discovery_import($discovery_ID,$Import=0) {
	global $DB,$CFG_GLPI,$LANG;

   $Netport = new Netport;
	$td = new PluginTrackerUnknown;
	
	$td->getFromDB($discovery_ID);
   $query = "SELECT `ID`
             FROM `glpi_networking_ports`
             WHERE `on_device` = '".$discovery_ID."'
                   AND `device_type` = '".PLUGIN_TRACKER_MAC_UNKNOWN."';";
	if ($result = $DB->query($query)) {
      $data = $DB->fetch_assoc($result);
      $Netport->getFromDB($data["ID"]);
   }

	switch ($td->fields['type']) {
		case PRINTER_TYPE :
			$Printer = new Printer;
			$tracker_printers = new PluginTrackerPrinters;
			$tracker_config_snmp_printer = new PluginTrackerConfigSNMPPrinter;

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
			$data_Port['name'] = $Netport->fields["name"];
			$data_Port['ifaddr'] = $Netport->fields["ifaddr"];
         $data_Port["ifmac"] = $Netport->fields["ifmac"];
			$Netport->add($data_Port);

			$data_tracker["FK_printers"] = $ID_Device;
			$data_tracker["FK_model_infos"] = $td->fields["FK_model_infos"];
			$data_tracker["FK_snmp_connection"] = $td->fields["FK_snmp_connection"];
			$tracker_printers->add($data_tracker);			
			
			$query_del = "DELETE FROM `glpi_plugin_tracker_discovery`
                       WHERE `ID`='".$discovery_ID."';";
			$DB->query($query_del);
			$Import++;
			break;

		case NETWORKING_TYPE :
			$Netdevice = new Netdevice;
			$tracker_networking = new PluginTrackerNetworking;
			$tracker_config_snmp_networking = new PluginTrackerConfigSNMPNetworking;

			$tracker_config_snmp_networking->getFromDB(1);
			$data['state'] = $tracker_config_snmp_networking->fields["active_device_state"];
			if (empty($data['state'])) {
				$data['state'] = 0;
         }
			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
         $data["location"] = $td->fields["location"];
			$data["serial"] = $td->fields["serial"];
         $data["otherserial"] = $td->fields["otherserial"];
         $data["contact"] = $td->fields["contact"];
         $data["domain"] = $td->fields["domain"];
			$data["comments"] = $td->fields["comments"];
			$data["ifaddr"] = $Netport->fields["ifaddr"];
         $data["ifmac"] = $Netport->fields["ifmac"];
			$ID_Device = $Netdevice->add($data);

			$data_tracker["FK_networking"] = $ID_Device;
			$data_tracker["FK_model_infos"] = $td->fields["FK_model_infos"];
			$data_tracker["FK_snmp_connection"] = $td->fields["FK_snmp_connection"];
			$tracker_networking->add($data_tracker);

			$query_del = "DELETE FROM `glpi_plugin_tracker_unknown_device`
                       WHERE `ID`='".$discovery_ID."';";
			$DB->query($query_del);
			$Import++;
			break;

		case PERIPHERAL_TYPE :
			$Peripheral = new Peripheral;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Peripheral->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['name'] = $Netport->fields["name"];
			$data_Port['ifaddr'] = $Netport->fields["ifaddr"];
         $data_Port["ifmac"] = $Netport->fields["ifmac"];
			$Netport->add($data_Port);
			
			$query_del = "DELETE FROM `glpi_plugin_tracker_discovery`
                       WHERE `ID`='".$discovery_ID."';";
			$DB->query($query_del);
			$Import++;
			break;

		case COMPUTER_TYPE :
			$Computer = new Computer;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Computer->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['name'] = $Netport->fields["name"];
			$data_Port['ifaddr'] = $Netport->fields["ifaddr"];
         $data_Port["ifmac"] = $Netport->fields["ifmac"];
			$Netport->add($data_Port);

			$query_del = "DELETE FROM `glpi_plugin_tracker_discovery`
                       WHERE `ID`='".$discovery_ID."';";
			$DB->query($query_del);
			$Import++;
			break;

		case PHONE_TYPE :
			$Phone = new Phone;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Phone->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['name'] = $Netport->fields["name"];
			$data_Port['ifaddr'] = $Netport->fields["ifaddr"];
         $data_Port["ifmac"] = $Netport->fields["ifmac"];
			$Netport->add($data_Port);

			$query_del = "DELETE FROM `glpi_plugin_tracker_discovery`
                       WHERE `ID`='".$discovery_ID."';";
			$DB->query($query_del);
			$Import++;
			break;
	}
   return $Import;
}

function plugin_tracker_discovery_criteria($discovery,$nbcriteria,$typerequest) {
	global $DB,$CFG_GLPI,$LANG;

   $ci = new commonitem;
   $config_discovery = new PluginTrackerConfigDiscovery;
   $np=new Netport;
   $plugin_tracker_unknown = new PluginTrackerUnknown;

   if ($nbcriteria == "1") {
      $select = "";
   } else {
      $select = "2";
   }
   $link_ip = $config_discovery->getValue("link".$select."_ip");
   $link_name = $config_discovery->getValue("link".$select."_name");
   $link_serial = $config_discovery->getValue("link".$select."_serial");
   $link_macaddr = $config_discovery->getValue("link".$select."_macaddr");

   $Array_criteria = array();
	if ($link_ip == "1") {
		$Array_criteria[] = "ifaddr='".$discovery->ip."'";
		$array_search[] = $discovery->ip;
	}
	if ($link_name == "1") {
      if (!empty($discovery->snmphostname)) {
         $Array_criteria[] = "name='".plugin_tracker_hex_to_string($discovery->snmphostname)."'";
         $array_search[] = plugin_tracker_hex_to_string($discovery->snmphostname);
      }
	}
	if ($link_serial == "1") {
      if (!empty($discovery->serial)) {
         $Array_criteria[] = "serial='".$discovery->serial."'";
         $array_search[] = $discovery->serial;
      }
	}
   if ($link_macaddr == "1") {
		$Array_criteria[] = "ifmac='".$discovery->mac."'";
		$array_search[] = $discovery->mac;
	}

   if (count($Array_criteria) == "0") {
      return 0;
   } else {
      $Array_criteria_source = $Array_criteria;
      if ($typerequest == "0") {
         if (($discovery->type == NETWORKING_TYPE) OR ($discovery->type == "0")) {
            $query_search = "SELECT *
                             FROM `glpi_networking`
                             WHERE `FK_entities`='".$discovery->entity."'
                                   AND ".$Array_criteria[0];
				for ($i=1 ; $i < count($Array_criteria) ; $i++) {
					$query_search .= " AND ".$Array_criteria[$i];
            }
            $result_search = $DB->query($query_search);
            if ($DB->numrows($result_search) == "0") {
               if ($discovery->type == NETWORKING_TYPE) {
                  return 0;
               }
            } else {
               return 1;
            }
         } else {
            $ci->setType($discovery->type,true);
            for ($i=0; $i<count($Array_criteria); $i++) {
               if ((!strstr($Array_criteria[$i], "ifaddr")) AND (!strstr($Array_criteria[$i], "ifmac"))) {
                  $Array_criteria[$i] = $ci->obj->table.".".$Array_criteria[0];
               }
            }
				$query_search = "SELECT ".$ci->obj->table.".`name` AS `name`, `serial`,
                                    `glpi_networking_ports`.`ifaddr` AS `ifaddr`
                             FROM ".$ci->obj->table."
                                  LEFT JOIN `glpi_networking_ports`
                                            ON `on_device`=".$ci->obj->table.".`ID`
                                               AND `device_type`='".$discovery->type."'
                             WHERE `FK_entities`='".$discovery->entity."'
                                   AND ".$Array_criteria[0];
				for ($i=1 ; $i < count($Array_criteria) ; $i++) {
					$query_search .= " AND ".$ci->obj->table.".".$Array_criteria[$i];
            }
            $result_search = $DB->query($query_search);
            if ($DB->numrows($result_search) == "0") {
               return 0;
            } else {
               return 1;
            }
         }
         if ($discovery->type == "0") {
            $types[] = COMPUTER_TYPE;
            $types[] = PRINTER_TYPE;
            $types[] = PERIPHERAL_TYPE;
            $types[] = PHONE_TYPE;
            foreach($types as $type) {
               $ci->setType($type,true);
               $Array_criteria = $Array_criteria_source;
               for ($i=0; $i<count($Array_criteria); $i++) {
                  if ((!strstr($Array_criteria[$i], "ifaddr")) AND (!strstr($Array_criteria[$i], "ifmac"))) {
                     $Array_criteria[$i] = $ci->obj->table.".".$Array_criteria[0];
                  }
               }
               $query_search = "SELECT ".$ci->obj->table.".`name` AS `name`, `serial`,
                                       `glpi_networking_ports`.`ifaddr` AS `ifaddr`
                                FROM ".$ci->obj->table."
                                     LEFT JOIN `glpi_networking_ports`
                                               ON `on_device`=".$ci->obj->table.".`ID`
                                                  AND `device_type`=".$type."
                                WHERE `FK_entities`='".$discovery->entity."'
                                      AND ".$Array_criteria[0];
               for ($i=1 ; $i < count($Array_criteria) ; $i++) {
                  $query_search .= " AND ".$ci->obj->table.".".$Array_criteria[$i];
               }
               $result_search = $DB->query($query_search);
               if ($DB->numrows($result_search) != "0") {
                  return 1;
               }
            }
         }
         return 0;
      } else if ($typerequest == "5153") {
         // Search in unknown devices
         $ci->setType(PLUGIN_TRACKER_MAC_UNKNOWN,true);
         $query_search = "SELECT ".$ci->obj->table.".`ID` AS `ID`,
                                 `glpi_networking_ports`.`ID` AS `netID`
                          FROM ".$ci->obj->table."
                               LEFT JOIN `glpi_networking_ports`
                                         ON `on_device`=".$ci->obj->table.".`ID`
                          WHERE `FK_entities`='".$discovery->entity."'
                                AND `ifmac`='".$discovery->mac."';";
         $result_search = $DB->query($query_search);
         if ($DB->numrows($result_search) == "0") {
            return 0;
         } else {
            // METTRE A JOUR LES INFORMATIONS
            $data_query = $DB->fetch_assoc($result_search);
            $data = array();
            $data['ID'] = $data_query['ID'];
            if (!empty($discovery->netbiosname)) {
               $data['name'] = $discovery->netbiosname;
            } else if (!empty($discovery->snmphostname)) {
               $data['name'] = $discovery->snmphostname;
            }
            $data['dnsname'] = $discovery->dnshostname;
            $data['FK_entities'] = $discovery->entity;
            $data['serial'] = $discovery->serial;
            $data['contact'] = $discovery->usersession;
            if (!empty($discovery->workgroup)) {
               $data['domain'] = externalImportDropdown("glpi_dropdown_domain",$discovery->workgroup,$discovery->entity);
            }
            $data['comments'] = $discovery->description;
            $data['type'] = $discovery->type;
            $data['FK_model_infos'] = $FK_model;
            $data['FK_snmp_connection'] = $discovery->authSNMP;
            if ($discovery->authSNMP != "0") {
               $data['snmp'] = 1;
            }
            $plugin_tracker_unknown->update($data);
				unset($data);
            // Update networking port
            $data["ID"] = $data_query['netID'];
				$data["ifaddr"] = $discovery->ip;
            $data['name'] = $discovery->netportvendor;
				$np->update($data);
            return 1;
         }
      }
   }
}

?>