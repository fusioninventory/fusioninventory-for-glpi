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
include_once(GLPI_ROOT.'/inc/networking.class.php'); // todo a ranger
include_once(GLPI_ROOT.'/inc/networking.function.php');

class PluginFusionInventorySNMP extends CommonDBTM {

	/**
	 * Get links between oid and fields 
	 *
	 * @param $ID_Model ID of the SNMP model
	 *
	 * @return array : array with object name and mapping_type||mapping_name
	 *
	**/
	function GetLinkOidToFields($ID_Device,$type) {
		global $DB,$FUSIONINVENTORY_MAPPING;
		
		$ObjectLink = array();

		if ($type == NETWORKING_TYPE) {
			$query_add = "LEFT JOIN `glpi_plugin_fusioninventory_networking`
                                 ON `glpi_plugin_fusioninventory_networking`.`FK_model_infos`=
                                    `glpi_plugin_fusioninventory_mib_networking`.`FK_model_infos`
                    WHERE `FK_networking`='".$ID_Device."'
                          AND `glpi_plugin_fusioninventory_networking`.`FK_model_infos`!='0' ";
      } else if($type == PRINTER_TYPE) {
			$query_add = "LEFT JOIN `glpi_plugin_fusioninventory_printers`
                                 ON `glpi_plugin_fusioninventory_printers`.`FK_model_infos`=
                                    `glpi_plugin_fusioninventory_mib_networking`.`FK_model_infos`
                    WHERE `FK_printers`='".$ID_Device."'
                          AND `glpi_plugin_fusioninventory_printers`.`FK_model_infos`!='0' ";
      }
			
		$query = "SELECT `mapping_type`, `mapping_name`, `oid_port_dyn`,
                       `glpi_dropdown_plugin_fusioninventory_mib_oid`.`name` AS `name`
                FROM `glpi_plugin_fusioninventory_mib_networking`
                     LEFT JOIN `glpi_dropdown_plugin_fusioninventory_mib_oid`
                               ON `glpi_plugin_fusioninventory_mib_networking`.`FK_mib_oid`=
                                  `glpi_dropdown_plugin_fusioninventory_mib_oid`.`ID`
               ".$query_add."
                   AND `oid_port_counter`='0'
                   AND `glpi_plugin_fusioninventory_mib_networking`.`activation`='1';";

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
	function update_network_infos($ID, $FK_model_infos, $FK_snmp_connection) {
		global $DB;
		
		$query = "SELECT *
                FROM `glpi_plugin_fusioninventory_networking`
                WHERE `FK_networking`='".$ID."';";
		$result = $DB->query($query);
		if ($DB->numrows($result) == "0") {
			$queryInsert = "INSERT INTO `glpi_plugin_fusioninventory_networking`(`FK_networking`)
                         VALUES('".$ID."');";

			$DB->query($queryInsert);
		}		
		if (empty($FK_snmp_connection)) {
			$FK_snmp_connection = 0;
      }
		$query = "UPDATE `glpi_plugin_fusioninventory_networking`
                SET `FK_model_infos`='".$FK_model_infos."',
                    `FK_snmp_connection`='".$FK_snmp_connection."'
                WHERE `FK_networking`='".$ID."';";
	
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
	function update_printer_infos($ID, $FK_model_infos, $FK_snmp_connection) {
		global $DB;

		$query = "SELECT *
                FROM `glpi_plugin_fusioninventory_printers`
                WHERE `FK_printers`='".$ID."';";
		$result = $DB->query($query);
		if ($DB->numrows($result) == "0") {
			$queryInsert = "INSERT INTO `glpi_plugin_fusioninventory_printers`(`FK_printers`)
                         VALUES('".$ID."');";

			$DB->query($queryInsert);
		}
		if (empty($FK_snmp_connection)) {
			$FK_snmp_connection = 0;
      }
		$query = "UPDATE `glpi_plugin_fusioninventory_printers`
                SET `FK_model_infos`='".$FK_model_infos."',
                    `FK_snmp_connection`='".$FK_snmp_connection."'
                WHERE `FK_printers`='".$ID."';";
	
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

      $pfiud = new PluginFusionInventoryUnknownDevice;
      $np = new Netport;

      $PortID = "";
		$query = "SELECT *
                FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                WHERE `ifaddr`='".$IP."';";
		
		$result = $DB->query($query);
      if ($DB->numrows($result) == "1") {
         $data = $DB->fetch_assoc($result);

         // Delete CDP device in unknown device
         $query_unknown = "SELECT * FROM `glpi_plugin_fusioninventory_unknown_device`
            WHERE `ifaddr`='".$IP."' ";
         if ($result_unknown=$DB->query($query_unknown)) {
            while ($data_unknown=$DB->fetch_array($result_unknown)) {
               // delete ports
               $a_ports = $np->find("`on_device`='".$data_unknown['ID']."' ");
               foreach ($a_ports as $id_port=>$dataport) {
                  // Delete Wire :
                  plugin_fusioninventory_addLogConnection("remove",$id_port);
                  removeConnector($id_port);
                  // Delete port :
                  $np->deleteFromDB($id_port);
               }
               $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_unknown_device`
                  WHERE `ID`='".$data_unknown['ID']."' ";
               $DB->query($query_delete);
            }
         }                     
         // End of delete CDP device
         
         $queryPort = "SELECT *
                       FROM `glpi_plugin_fusioninventory_networking_ports`
                            INNER JOIN `glpi_networking_ports`
                                      ON `glpi_plugin_fusioninventory_networking_ports`.`FK_networking_ports`=
                                         `glpi_networking_ports`.`ID`
                       WHERE (`ifdescr`='".$ifDescr."'
                                OR `glpi_networking_ports`.`name`='".$ifDescr."')
                             AND `glpi_networking_ports`.`on_device`='".$data["FK_networking"]."'
                             AND `glpi_networking_ports`.`device_type`='2'
                       LIMIT 0,1;";
         $resultPort = $DB->query($queryPort);
         $dataPort = $DB->fetch_assoc($resultPort);
         if ($DB->numrows($resultPort) == "0") {
            // Search in other devices
//            $queryPort = "SELECT *
//                          FROM `glpi_networking_ports`
//                          WHERE `ifaddr`='".$IP."'
//                             AND
//                          ORDER BY `device_type`
//                          LIMIT 0,1;";
//            $resultPort = $DB->query($queryPort);
//            $dataPort = $DB->fetch_assoc($resultPort);
//            $PortID = $dataPort["ID"];
         } else {
            $PortID = $dataPort["FK_networking_ports"];
         }
      } else if ($DB->numrows($result) == "0") {
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_unknown_device`
            WHERE `ifaddr`='".$IP."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            // Search port and add if required
            $query1 = "SELECT *
                FROM `glpi_networking_ports`
                WHERE `device_type`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
                   AND `on_device`='".$data['ID']."'
                   AND `name`='".$ifDescr."'
                LIMIT 1";
            $result1 = $DB->query($query1);
            if ($DB->numrows($result1) == "1") {
               $data1 = $DB->fetch_assoc($result1);
               $PortID = $data1['ID'];
            } else {
               // Add port
               $input = array();
               $input['on_device'] = $data['ID'];
               $input['device_type'] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
               $input['ifaddr'] = $IP;
               $input['name'] = $ifDescr;
               $PortID = $np->add($input);
            }
            return $PortID;
         } else if($DB->numrows($result) > 1) {
            $i = 0;
            while ($data=$DB->fetch_array($result)) {
               if ($i > 0) {
                  $pfiud->deleteFromDB($data['ID']);
               } else {
                  $PortID = $data['ID'];
               }
               $i++;
            }
            return $PortID;
         }

         $query = "SELECT *
             FROM `glpi_networking_ports`
             WHERE `device_type`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
               AND `ifaddr`='".$IP."'
             LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            $PortID = $data['ID'];
            return $PortID;
         } else if($DB->numrows($result) > 1) {
            $i = 0;
            while ($data=$DB->fetch_array($result)) {
               if ($i > 0) {
                  $np->deleteFromDB($data['ID']);
                  $pfiud->deleteFromDB($data['on_device']);
               } else {
                  $PortID = $data['ID'];
               }
               $i++;
            }
            return $PortID;
         }
         // Add unknown device
         $input = array();
         $input['ifaddr'] = $IP;
         $unkonwn_id = $pfiud->add($input);
         // Add port
         $input = array();
         $input['on_device'] = $unkonwn_id;
         $input['device_type'] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
         $input['ifaddr'] = $IP;
         $input['name'] = $ifDescr;
         $PortID = $np->add($input);
         return($PortID);
      }
		return($PortID);
	}


function getPortIDfromDeviceSysname($sysname, $ifDescr) {
		global $DB;

      $pfiud = new PluginFusionInventoryUnknownDevice;
      $np = new Netport;

      $PortID = "";
		$query = "SELECT *
                FROM `glpi_networking`
                WHERE `name`='".$sysname."';";

		$result = $DB->query($query);
      if ($DB->numrows($result) == "1") {
         $data = $DB->fetch_assoc($result);

         // Delete CDP device in unknown device
         $query_unknown = "SELECT * FROM `glpi_plugin_fusioninventory_unknown_device`
            WHERE `name`='".$sysname."' ";
         if ($result_unknown=$DB->query($query_unknown)) {
            while ($data_unknown=$DB->fetch_array($result_unknown)) {
               // delete ports
               $a_ports = $np->find("`on_device`='".$data_unknown['ID']."' ");
               foreach ($a_ports as $id_port=>$dataport) {
                  // Delete Wire :
                  removeConnector($id_port);
                  // Delete port :
                  $np->deleteFromDB($id_port);
               }
               $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_unknown_device`
                  WHERE `ID`='".$data_unknown['ID']."' ";
               $DB->query($query_delete);
            }
         }
         // End of delete CDP device

         $queryPort = "SELECT *
                       FROM `glpi_plugin_fusioninventory_networking_ports`
                            INNER JOIN `glpi_networking_ports`
                                      ON `glpi_plugin_fusioninventory_networking_ports`.`FK_networking_ports`=
                                         `glpi_networking_ports`.`ID`
                       WHERE (`ifdescr`='".$ifDescr."'
                                OR `glpi_networking_ports`.`name`='".$ifDescr."')
                             AND `glpi_networking_ports`.`on_device`='".$data["ID"]."'
                             AND `glpi_networking_ports`.`device_type`='2'
                       LIMIT 0,1;";
         $resultPort = $DB->query($queryPort);
         $dataPort = $DB->fetch_assoc($resultPort);
         if ($DB->numrows($resultPort) == "0") {
            // Search in other devices
//            $queryPort = "SELECT *
//                          FROM `glpi_networking_ports`
//                          WHERE `ifaddr`='".$IP."'
//                             AND
//                          ORDER BY `device_type`
//                          LIMIT 0,1;";
//            $resultPort = $DB->query($queryPort);
//            $dataPort = $DB->fetch_assoc($resultPort);
//            $PortID = $dataPort["ID"];
         } else {
            $PortID = $dataPort["FK_networking_ports"];
         }
      } else if ($DB->numrows($result) == "0") {
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_unknown_device`
            WHERE `name`='".$sysname."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            // Search port and add if required
            $query1 = "SELECT *
                FROM `glpi_networking_ports`
                WHERE `device_type`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
                   AND `on_device`='".$data['ID']."'
                   AND `name`='".$ifDescr."'
                LIMIT 1";
            $result1 = $DB->query($query1);
            if ($DB->numrows($result1) == "1") {
               $data1 = $DB->fetch_assoc($result1);
               $PortID = $data1['ID'];
            } else {
               // Add port
               $input = array();
               $input['on_device'] = $data['ID'];
               $input['device_type'] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
               $input['name'] = $ifDescr;
               $PortID = $np->add($input);
            }
            return $PortID;
         }

         $query = "SELECT *
             FROM `glpi_networking_ports`
             WHERE `device_type`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
               AND `name`='".$sysname."'
             LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            $PortID = $data['ID'];
            return $PortID;
         }
         // Add unknown device
         $input = array();
         $input['name'] = $sysname;
         $unkonwn_id = $pfiud->add($input);
         // Add port
         $input = array();
         $input['on_device'] = $unkonwn_id;
         $input['device_type'] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
         $input['name'] = $ifDescr;
         $PortID = $np->add($input);
         return($PortID);
      }
		return($PortID);
	}




	/**
	 * Get port ID from device MAC address
	 *
	 * @param $p_mac MAC address
	 * @param $p_fromPortID Link port ID
	 *
	 * @return Port ID
	**/
	function getPortIDfromDeviceMAC($p_mac, $p_fromPortID) {
		global $DB;

      $query = "SELECT ID
                FROM `glpi_networking_ports`
                WHERE `ifmac` IN ('".$p_mac."',
                                  '".strtoupper($p_mac)."')
                      AND `ID`!='".$p_fromPortID."';"; // do not get the link port
		$result = $DB->query($query);
		$data = $DB->fetch_assoc($result);
		return($data["ID"]);
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
	function PortsConnection($source_port, $destination_port,$FK_process,$vlan="") {
		global $DB;
		
		$netwire = new Netwire;
		
		$queryVerif = "SELECT *
                     FROM `glpi_networking_wire`
                     WHERE `end1` IN ('".$source_port."', '".$destination_port."')
                           AND `end2` IN ('".$source_port."', '".$destination_port."');";

		if ($resultVerif=$DB->query($queryVerif)) {
			if ($DB->numrows($resultVerif) == "0") {
				plugin_fusioninventory_addLogConnection("remove",$source_port,$FK_process);
				$this->CleanVlan($source_port);
            removeConnector($source_port);

				plugin_fusioninventory_addLogConnection("remove",$destination_port,$FK_process);
            $this->CleanVlan($destination_port);
            removeConnector($destination_port);
						
				makeConnector($source_port,$destination_port);
				plugin_fusioninventory_addLogConnection("make",$source_port,$FK_process);
				
				if ((!empty($vlan)) AND ($vlan != " []")) {
					$FK_vlan = externalImportDropdown("glpi_dropdown_vlan",$vlan,0);
					if ($FK_vlan != "0") {
                  $ports[] = $source_port;
                  $ports[] = $destination_port;
                  foreach ($ports AS $num=>$tmp_port) {
                     $query="SELECT *
                             FROM `glpi_networking_vlan`
                             WHERE `FK_port`='$tmp_port'
                                   AND `FK_vlan`='$FK_vlan'
                             LIMIT 0,1;";
                     if ($result=$DB->query($query)) {
                        if ($DB->numrows($result) == "0") {
                           assignVlan($tmp_port,$FK_vlan);
                        }
                     }
                  }
               }
				}
			} else {
				if ((!empty($vlan)) AND ($vlan != " []")) {
               // Verify vlan and update it if necessery
               $FK_vlan = externalImportDropdown("glpi_dropdown_vlan",$vlan,0);
               if ($FK_vlan != "0") {
                  $ports[] = $source_port;
                  $ports[] = $destination_port;
                  foreach ($ports AS $num=>$tmp_port) {
                     $query = "SELECT *
                               FROM `glpi_networking_vlan`
                               WHERE `FK_port`='$tmp_port'
                                     AND `FK_vlan`='$FK_vlan'; ";
                     if ($result=$DB->query($query)) {
                        if ($DB->numrows($result) == "0") {
                           $this->CleanVlan($tmp_port);
                           assignVlan($tmp_port,$FK_vlan);
                        } else {
                           $query2 = "SELECT *
                                      FROM `glpi_networking_vlan`
                                      WHERE `FK_port`='$tmp_port'
                                            AND `FK_vlan`!='$FK_vlan';";
                           if ($result2=$DB->query($query2)) {
                              while ($data2=$DB->fetch_array($result2)) {
                                 $this->CleanVlanID($data2["ID"]);
                              }
                           }
                        }
                     }
                  }
               }
            }
         }
		}
		// Remove all connections if it is
//		plugin_fusioninventory_addLogConnection("remove",$netwire->getOppositeContact($destination_port),$FK_process);
//      plugin_fusioninventory_addLogConnection("remove",$destination_port,$FK_process);
//      $this->CleanVlan($destination_port);
//      removeConnector($destination_port);
	}



	/**
	 * Get SNMP model of the device 
	 *
	 * @param $ID_Device ID of the device
	 * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
	 *
	 * @return ID of the SNMP model or nothing 
	 *
	**/
	function GetSNMPModel($ID_Device,$type) {
		global $DB;

		switch ($type) {
			case NETWORKING_TYPE :
				$query = "SELECT FK_model_infos
				FROM glpi_plugin_fusioninventory_networking 
				WHERE FK_networking='".$ID_Device."' ";
				break;

			case PRINTER_TYPE :
				$query = "SELECT `FK_model_infos`
                      FROM `glpi_plugin_fusioninventory_printers`
                      WHERE `FK_printers`='".$ID_Device."';";
				break;
		}
		if (isset($query)) {
			if (($result = $DB->query($query))) {
				if ($DB->numrows($result) != 0) {
					return $DB->result($result, 0, "FK_model_infos");
            }
			}
		}
	}



	function CleanVlan($FK_port) {
		global $DB;

		$query="SELECT *
              FROM `glpi_networking_vlan`
              WHERE `FK_port`='$FK_port'
              LIMIT 0,1;";
		if ($result=$DB->query($query)) {
			$data=$DB->fetch_array($result);

			// Delete VLAN
			$query="DELETE FROM `glpi_networking_vlan`
                 WHERE `FK_port`='$FK_port';";
			$DB->query($query);

			// Delete Contact VLAN if set
			$np=new NetPort;
			if ($np->getContact($data['FK_port'])) {
				$query="DELETE FROM `glpi_networking_vlan`
                    WHERE `FK_port`='".$np->contact_id."'
                          AND `FK_vlan`='".$data['FK_vlan']."';";
				$DB->query($query);
			}
		}
   }



	function CleanVlanID($ID) {
		global $DB;

		$query="SELECT *
              FROM `glpi_networking_vlan`
              WHERE `ID`='$ID'
              LIMIT 0,1;";
		if ($result=$DB->query($query)) {
			$data=$DB->fetch_array($result);

			// Delete VLAN
			$query="DELETE FROM `glpi_networking_vlan`
                 WHERE `ID`='$ID';";
			$DB->query($query);

			// Delete Contact VLAN if set
			$np=new NetPort;
			if ($np->getContact($data['FK_port'])) {
				$query="DELETE FROM `glpi_networking_vlan`
                    WHERE `FK_port`='".$np->contact_id."'
                          AND `FK_vlan`='".$data['FK_vlan']."';";
				$DB->query($query);
			}
		}
	}
}

?>