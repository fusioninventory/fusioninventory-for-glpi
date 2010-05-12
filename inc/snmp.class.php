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

class PluginFusioninventorySNMP extends CommonDBTM {

	/**
	 * Get links between oid and fields 
	 *
	 * @param $ID_Model id of the SNMP model
	 *
	 * @return array : array with object name and mapping_type||mapping_name
	 *
	**/
	function GetLinkOidToFields($ID_Device,$type) {
		global $DB,$FUSIONINVENTORY_MAPPING;
		
		$ObjectLink = array();

		if ($type == NETWORKING_TYPE) {
			$query_add = "LEFT JOIN `glpi_plugin_fusioninventory_networking`
                                 ON `glpi_plugin_fusioninventory_networking`.`plugin_fusioninventory_modelinfos_id`=
                                    `glpi_plugin_fusioninventory_mib`.`plugin_fusioninventory_modelinfos_id`
                    WHERE `networkequipments_id`='".$ID_Device."'
                          AND `glpi_plugin_fusioninventory_networking`.`plugin_fusioninventory_modelinfos_id`!='0' ";
      } else if($type == PRINTER_TYPE) {
			$query_add = "LEFT JOIN `glpi_plugin_fusioninventory_printers`
                                 ON `glpi_plugin_fusioninventory_printers`.`plugin_fusioninventory_modelinfos_id`=
                                    `glpi_plugin_fusioninventory_mib`.`plugin_fusioninventory_modelinfos_id`
                    WHERE `printers_id`='".$ID_Device."'
                          AND `glpi_plugin_fusioninventory_printers`.`plugin_fusioninventory_modelinfos_id`!='0' ";
      }
			
		$query = "SELECT `mapping_type`, `mapping_name`, `oid_port_dyn`,
                       `glpi_plugin_fusioninventory_mib_oid`.`name` AS `name`
                FROM `glpi_plugin_fusioninventory_mib`
                     LEFT JOIN `glpi_plugin_fusioninventory_mib_oid`
                               ON `glpi_plugin_fusioninventory_mib`.`plugin_fusioninventory_mib_oid_id`=
                                  `glpi_plugin_fusioninventory_mib_oid`.`id`
               ".$query_add."
                   AND `oid_port_counter`='0'
                   AND `glpi_plugin_fusioninventory_mib`.`activation`='1';";

		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				if ($data["oid_port_dyn"] == "1") {
					$data["name"] = $data["name"].".";
            }
				$ObjectLink[$data["name"]] = $data["mapping_name"];
			}
		}
		return $ObjectLink;
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
	function update_network_infos($id, $plugin_fusioninventory_modelinfos_id, $plugin_fusioninventory_snmpauths_id) {
		global $DB;
		
		$query = "SELECT *
                FROM `glpi_plugin_fusioninventory_networking`
                WHERE `networkequipments_id`='".$id."';";
		$result = $DB->query($query);
		if ($DB->numrows($result) == "0") {
			$queryInsert = "INSERT INTO `glpi_plugin_fusioninventory_networking`(`networkequipments_id`)
                         VALUES('".$id."');";

			$DB->query($queryInsert);
		}		
		if (empty($plugin_fusioninventory_snmpauths_id)) {
			$plugin_fusioninventory_snmpauths_id = 0;
      }
		$query = "UPDATE `glpi_plugin_fusioninventory_networking`
                SET `plugin_fusioninventory_modelinfos_id`='".$plugin_fusioninventory_modelinfos_id."',
                    `plugin_fusioninventory_snmpauths_id`='".$plugin_fusioninventory_snmpauths_id."'
                WHERE `networkequipments_id`='".$id."';";
	
		$DB->query($query);
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
	function update_printer_infos($id, $plugin_fusioninventory_modelinfos_id, $plugin_fusioninventory_snmpauths_id) {
		global $DB;

		$query = "SELECT *
                FROM `glpi_plugin_fusioninventory_printers`
                WHERE `printers_id`='".$id."';";
		$result = $DB->query($query);
		if ($DB->numrows($result) == "0") {
			$queryInsert = "INSERT INTO `glpi_plugin_fusioninventory_printers`(`printers_id`)
                         VALUES('".$id."');";

			$DB->query($queryInsert);
		}
		if (empty($plugin_fusioninventory_snmpauths_id)) {
			$plugin_fusioninventory_snmpauths_id = 0;
      }
		$query = "UPDATE `glpi_plugin_fusioninventory_printers`
                SET `plugin_fusioninventory_modelinfos_id`='".$plugin_fusioninventory_modelinfos_id."',
                    `plugin_fusioninventory_snmpauths_id`='".$plugin_fusioninventory_snmpauths_id."'
                WHERE `printers_id`='".$id."';";
	
		$DB->query($query);
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
	function getPortIDfromDeviceIP($IP, $ifDescr) {
		global $DB;

      $pfiud = new PluginFusioninventoryUnknownDevice;
      $np = new Networkport;

      $PortID = "";
		$query = "SELECT *
                FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                WHERE `ip`='".$IP."';";
		
		$result = $DB->query($query);
      if ($DB->numrows($result) == "1") {
         $data = $DB->fetch_assoc($result);

         $queryPort = "SELECT *
                       FROM `glpi_plugin_fusioninventory_networking_ports`
                            LEFT JOIN `glpi_networkports`
                                      ON `glpi_plugin_fusioninventory_networking_ports`.`networkports_id`=
                                         `glpi_networkports`.`id`
                       WHERE (`ifdescr`='".$ifDescr."'
                                OR `glpi_networkports`.`name`='".$ifDescr."')
                             AND `glpi_networkports`.`items_id`='".$data["networkequipments_id"]."'
                             AND `glpi_networkports`.`itemtype`='2';";
         $resultPort = $DB->query($queryPort);
         $dataPort = $DB->fetch_assoc($resultPort);
         if ($DB->numrows($resultPort) == "0") {
            // Search in other devices
            $queryPort = "SELECT *
                          FROM `glpi_networkports`
                          WHERE `ip`='".$IP."'
                          ORDER BY `itemtype`
                          LIMIT 0,1;";
            $resultPort = $DB->query($queryPort);
            $dataPort = $DB->fetch_assoc($resultPort);
            $PortID = $dataPort["id"];
         } else {
            $PortID = $dataPort["networkports_id"];
         }
      } else {
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_unknown_device`
            WHERE `ip`='".$IP."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            // Search port and add if required
            $query1 = "SELECT *
                FROM `glpi_networkports`
                WHERE `itemtype`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
                   AND `items_id`='".$data['id']."'
                   AND `name`='".$ifDescr."'
                LIMIT 1";
            $result1 = $DB->query($query1);
            if ($DB->numrows($result1) == "1") {
               $data1 = $DB->fetch_assoc($result1);
               $PortID = $data1['id'];
            } else {
               // Add port
               $input = array();
               $input['items_id'] = $data['id'];
               $input['itemtype'] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
               $input['ip'] = $IP;
               $input['name'] = $ifDescr;
               $PortID = $np->add($input);
            }
            return $PortID;
         }

         $query = "SELECT *
             FROM `glpi_networkports`
             WHERE `itemtype`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
               AND`ip`='".$IP."'
             LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            if ($pfiud->convertUnknownToUnknownNetwork($data['items_id'])) {
               // Add port
               $input = array();
               $input['items_id'] = $data['items_id'];
               $input['itemtype'] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
               $input['ip'] = $IP;
               $input['name'] = $ifDescr;
               $PortID = $np->add($input);
               return $PortID;
            }
         }
         // Add unknown device
         $input = array();
         $input['ip'] = $IP;
         $unkonwn_id = $pfiud->add($input);
         // Add port
         $input = array();
         $input['items_id'] = $unkonwn_id;
         $input['itemtype'] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
         $input['ip'] = $IP;
         $input['name'] = $ifDescr;
         $PortID = $np->add($input);
         return($PortID);
      }
		return($PortID);
	}

	/**
	 * Get port id from device MAC address
	 *
	 * @param $p_mac MAC address
	 * @param $p_fromPortID Link port id
	 *
	 * @return Port id
	**/
	function getPortIDfromDeviceMAC($p_mac, $p_fromPortID) {
		global $DB;

      $query = "SELECT id
                FROM `glpi_networkports`
                WHERE `mac` IN ('".$p_mac."',
                                  '".strtoupper($p_mac)."')
                      AND `id`!='".$p_fromPortID."';"; // do not get the link port
		$result = $DB->query($query);
		$data = $DB->fetch_assoc($result);
		return($data["id"]);
	}

	/**
	 * Get SNMP model of the device 
	 *
	 * @param $ID_Device id of the device
	 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
	 *
	 * @return id of the SNMP model or nothing 
	 *
	**/
	function GetSNMPModel($ID_Device,$type) {
		global $DB;

		switch ($type) {
			case NETWORKING_TYPE :
				$query = "SELECT plugin_fusioninventory_modelinfos_id
				FROM glpi_plugin_fusioninventory_networking 
				WHERE networkequipments_id='".$ID_Device."' ";
				break;

			case PRINTER_TYPE :
				$query = "SELECT `plugin_fusioninventory_modelinfos_id`
                      FROM `glpi_plugin_fusioninventory_printers`
                      WHERE `printers_id`='".$ID_Device."';";
				break;
		}
		if (isset($query)) {
			if (($result = $DB->query($query))) {
				if ($DB->numrows($result) != 0) {
					return $DB->result($result, 0, "plugin_fusioninventory_modelinfos_id");
            }
			}
		}
	}

   static function auth_dropdown($selected="") {
      global $DB;

      $plugin_fusioninventory_snmp_auth = new PluginFusioninventorySnmpauth;
      $config = new PluginFusioninventoryConfig;

      if ($config->getValue("authsnmp") == "file") {
         echo $plugin_fusioninventory_snmp_auth->selectbox($selected);
      } else  if ($config->getValue("authsnmp") == "DB") {
         Dropdown::show("PluginFusioninventorySnmpauth",
                        array('name' => "plugin_fusioninventory_snmpauths_id",
                              'value' => $selected,
                              'comment' => false));
      }
   }

   static function hex_to_string($value) {
      if (strstr($value, "0x0115")) {
         $hex = str_replace("0x0115","",$value);
         $string='';
         for ($i=0; $i < strlen($hex)-1; $i+=2) {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
         }
         $value = $string;
      }
      if (strstr($value, "0x")) {
         $hex = str_replace("0x","",$value);
         $string='';
         for ($i=0; $i < strlen($hex)-1; $i+=2) {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
         }
         $value = $string;
      }
      return $value;
   }
}

?>