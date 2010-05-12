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



	function CleanVlan($ports_id) {
		global $DB;

		$query="SELECT *
              FROM `glpi_networking_vlan`
              WHERE `ports_id`='$ports_id'
              LIMIT 0,1;";
		if ($result=$DB->query($query)) {
			$data=$DB->fetch_array($result);

			// Delete VLAN
			$query="DELETE FROM `glpi_networking_vlan`
                 WHERE `ports_id`='$ports_id';";
			$DB->query($query);

			// Delete Contact VLAN if set
			$np=new Networkport;
			if ($np->getContact($data['ports_id'])) {
				$query="DELETE FROM `glpi_networking_vlan`
                    WHERE `ports_id`='".$np->contact_id."'
                          AND `vlans_id`='".$data['vlans_id']."';";
				$DB->query($query);
			}
		}
   }

	function CleanVlanID($id) {
		global $DB;

		$query="SELECT *
              FROM `glpi_networking_vlan`
              WHERE `id`='$id'
              LIMIT 0,1;";
		if ($result=$DB->query($query)) {
			$data=$DB->fetch_array($result);

			// Delete VLAN
			$query="DELETE FROM `glpi_networking_vlan`
                 WHERE `id`='$id';";
			$DB->query($query);

			// Delete Contact VLAN if set
			$np=new Networkport;
			if ($np->getContact($data['ports_id'])) {
				$query="DELETE FROM `glpi_networking_vlan`
                    WHERE `ports_id`='".$np->contact_id."'
                          AND `vlans_id`='".$data['vlans_id']."';";
				$DB->query($query);
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

   /**
    * Get port OID list for the SNMP model && create ports in DB if they don't exists
    *
    * @param $ID_Device : id of device
    * @param $type : type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
    * @param $oidsModel : oid list from model SNMP
    * @param $oidvalues : list of values from agent query
    * @param $ArrayPort_LogicalNum_SNMPNum : array logical port number => SNMP port number (ifindex)
    * @param $ArrayPort_LogicalNum_SNMPName : array logical port number => SNMP Port name
    *
    * @return $oidList : array with logic number => portsID from snmp
    *
    **/
   static function getOIDPorts($ID_Device,$type,$oidsModel,$oidvalues,
                               $ArrayPort_LogicalNum_SNMPName,$ArrayPort_LogicalNum_SNMPNum) {
      global $DB,$LANG;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusioninventoryLogs;
      $manufCisco = new PluginFusioninventoryManufacturerCisco;
      $netwire=new Netwire;
      $np=new Networkport;
      $ptp = new PluginFusioninventoryPort;

      if ($_SESSION['fusioninventory_logs'] == "1")
         $logs->write("fusioninventory_fullsync",
                 ">>>>>>>>>> Get OID ports list (SNMP model) and create ports in DB if not exists <<<<<<<<<<",
                 $type,$ID_Device,1);

      $portcounter = $oidvalues[$oidsModel[1][0][""]][""];
      if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync","oid port counter : ".
                 $oidsModel[1][0][""]." = ".$portcounter,$type,$ID_Device,1);

      $oid_ifType = $oidsModel[0][1]['ifType'];
      if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync","type of port : ".
                 $oid_ifType,$type,$ID_Device,1);

      asort($ArrayPort_LogicalNum_SNMPNum);

      // Reorder ports with good logic number
      $query = "SELECT `last_PID_update`
             FROM `glpi_plugin_fusioninventory_networking`
             WHERE `networkequipments_id`='".$ID_Device."'
                   AND `last_PID_update`='0';";
      $result = $DB->query($query);
      if ($DB->numrows($result) == 1) {
         foreach ($ArrayPort_LogicalNum_SNMPNum as $num=>$ifIndex) {
            $query_update = "UPDATE `glpi_networkports`
                          SET `logical_number`='".$ifIndex."'
                          WHERE `items_id`='".$ID_Device."'
                                AND `itemtype`='".$type."'
                                AND `name`='".$ArrayPort_LogicalNum_SNMPName[$num]."';";
            $DB->query($query_update);
         }
      }



      // Get query SNMP to have number of ports
      if ((isset($portcounter)) AND (!empty($portcounter))) {
         // ** Add ports in DataBase if they don't exists
         $logicalnumberlist = "(";
         foreach ($ArrayPort_LogicalNum_SNMPNum as $num=>$ifIndex) {
            //$i is the logical number
            $logicalnumberlist .= $ifIndex.",";

            //for ($i=0 ; $i < $portcounter ; $i++) {
            // Get type of port
            $ifType = $oidvalues[$oid_ifType.".".$ArrayPort_LogicalNum_SNMPNum[$num]][""];
            $oidList[$i] = $ArrayPort_LogicalNum_SNMPNum[$num];

            if ((strstr($ifType, "ethernetCsmacd"))
                    OR ($ifType == "6")
                    OR ($ifType == "ethernet-csmacd(6)")
                    OR (strstr($ifType, "iso88023Csmacd"))
                    OR ($ifType == "7")) {


               $goodname = 1;
               if ($manufCisco->ListVirtualPorts($oidvalues[".1.3.6.1.2.1.1.1.0"][""],
                       $ArrayPort_LogicalNum_SNMPName[$num]) == true) {
                  $goodname = 0;
                  $deleteportname[] = $ifIndex;
                  unset($oidList[$ifIndex]);
               }
               if ($goodname == 1) {
                  $query = "SELECT `id`, `name`
                         FROM `glpi_networkports`
                         WHERE `items_id`='".$ID_Device."'
                               AND `itemtype`='".$type."'
                               AND `logical_number`='".$ifIndex."';";
                  $result = $DB->query($query);
                  if ($DB->numrows($result) == 0) {
                     unset($array);
                     $array["logical_number"] = $ifIndex;
                     $array["name"] = $ArrayPort_LogicalNum_SNMPName[$num];
                     $array["items_id"] = $ID_Device;
                     $array["itemtype"] = $type;

                     $IDport = $np->add($array);
                     Event::log(0, "networking", 5, "inventory", "FusionInventory ".$LANG["log"][70]);
                     if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                                "Add port in DB (glpi_networkports) : ".
                                $ArrayPort_LogicalNum_SNMPName[$i],$type,$ID_Device,1);
                  } else {
                     $IDport = $DB->result($result, 0, "id");
                     if ($DB->result($result, 0, "name") != $ArrayPort_LogicalNum_SNMPName[$num]) {
                        unset($array);
                        $array["name"] = $ArrayPort_LogicalNum_SNMPName[$num];
                        $array["id"] = $DB->result($result, 0, "id");
                        $np->update($array);
                        if ($_SESSION['fusioninventory_logs'] == "1")
                           $logs->write("fusioninventory_fullsync",
                                   "Update port in DB (glpi_networkports) : id".
                                   $DB->result($result, 0, "id")." & name ".
                                   $ArrayPort_LogicalNum_SNMPName[$i],$type,$ID_Device,1);
                     }
                  }
                  if ($type == NETWORKING_TYPE) {
                     $queryFusionInventoryPort = "SELECT `id`
                                       FROM `glpi_plugin_fusioninventory_networking_ports`
                                       WHERE `networkports_id`='".$IDport."';";

                     $resultFusionInventoryPort = $DB->query($queryFusionInventoryPort);
                     if ($DB->numrows($resultFusionInventoryPort) == 0) {
                        $queryInsert = "INSERT INTO `glpi_plugin_fusioninventory_networking_ports`
                                                 (`networkports_id`)
                                     VALUES ('".$IDport."');";
                        $DB->query($queryInsert);
                        if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                                   "Add port in DB (glpi_plugin_fusioninventory_networking_ports) : id ".$IDport,$type,
                                   $ID_Device,1);
                     }
                  }
               }
            }
         }
         $logicalnumberlist .= ")";
      }
      // Delete all ports that will be not here
      $nn = new NetworkPort_NetworkPort();
      foreach($deleteportname as $id=>$i) {
         $query = "SELECT *
                FROM `glpi_networkports`
                WHERE `items_id`='".$ID_Device."'
                      AND `itemtype`='".$type."'
                      AND `logical_number`='".$i."';";
         $result = $DB->query($query);
         $data = $DB->fetch_assoc($result);

         PluginFusioninventorySnmphistory::addLogConnection(
                 "remove",$netwire->getOppositeContact($data['id']),$plugin_fusioninventory_processes_id);
         PluginFusioninventorySnmphistory::addLogConnection("remove",$data['id'],$plugin_fusioninventory_processes_id);
         if ($nn->getFromDBForNetworkPort($data['id'])) {
            $nn->delete($data);
         }

         $ptp->deleteFromDB($data["id"],1);
         $np->delete($data);
      }

      // Delete ports where logical number in glpi_plugin_fusioninventory_networking_ports
      // not exist on switch : it's ports reorder not well
      $logicalnumberlist = str_replace(",)", ")", $logicalnumberlist);
      $query = "SELECT *
             FROM `glpi_networkports`
             WHERE `items_id`='".$ID_Device."'
                   AND `itemtype`='".$type."'
                   AND `logical_number` NOT IN ".$logicalnumberlist.";";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         PluginFusioninventorySnmphistory::addLogConnection(
                 "remove",$netwire->getOppositeContact($data['id']),$plugin_fusioninventory_processes_id);
         PluginFusioninventorySnmphistory::addLogConnection("remove",$data['id'],$plugin_fusioninventory_processes_id);
         if ($nn->getFromDBForNetworkPort($data['id'])) {
            $nn->delete($data);
         }
         $np->delete($data);
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ports`
                       WHERE `networkports_id`='".$data["id"]."';";
         $DB->query($query_delete);
      }

      return $oidList;
   }

   /**
    * Update devices with values get by SNMP
    *
    * @param $ID_Device : id of device
    * @param $type : type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
    * @param $oidsModel : oid list from model SNMP
    * @param $oidvalues : list of values from agent query
    * @param $Array_Object_TypeNameConstant : array with oid => constant in relation with fields to update
    *
    * @return $oidList : array with ports object name and oid
    *
    **/
   static function updateGLPIDevice($ID_Device,$type,$oidsModel,$oidvalues,
                                    $Array_Object_TypeNameConstant) {
      global $DB,$LANG,$CFG_GLPI,$FUSIONINVENTORY_MAPPING;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusioninventoryLogs;

      if ($_SESSION['fusioninventory_logs'] == "1")
         $logs->write("fusioninventory_fullsync",">>>>>>>>>> Update devices values <<<<<<<<<<",$type,
                 $ID_Device,1);

      // Update 'last_fusioninventory_update' field
      $query = "UPDATE ";
      if ($type == NETWORKING_TYPE) {
         $query .= "`glpi_plugin_fusioninventory_networking`
             SET `last_fusioninventory_update`='".date("Y-m-d H:i:s")."',
                 `last_PID_update`='".$_SESSION['plugin_fusioninventory_processes_id']."'
             WHERE `networkequipments_id`='".$ID_Device."';";
      }
      if ($type == PRINTER_TYPE) {
         $query .= "`glpi_plugin_fusioninventory_printers`
             SET `last_fusioninventory_update`='".date("Y-m-d H:i:s")."'
             WHERE `printers_id`='".$ID_Device."';";
      }
      $DB->query($query);

      foreach($Array_Object_TypeNameConstant as $oid=>$link) {
         if (!preg_match("/\.$/",$oid)) { // SNMPGet ONLY
            if (isset($oidvalues[$oid][""])) {
               if ((isset($FUSIONINVENTORY_MAPPING[$type][$link]['dropdown']))
                       AND (!empty($FUSIONINVENTORY_MAPPING[$type][$link]['dropdown']))) {
                  $oidvalues[$oid][""] = PluginFusioninventorySNMP::hex_to_string($oidvalues[$oid][""]);
                  if ($FUSIONINVENTORY_MAPPING[$type][$link]['dropdown'] == "glpi_networkequipmentsmodels") {
                     $oidvalues[$oid][""] =
                             Dropdown::importExternal("NetworkEquipmentModel",
                                $oidvalues[$oid][""],0,array("manufacturer"=>$oidvalues[$oid][""]));
                  } else {
                     $oidvalues[$oid][""] =
                             Dropdown::importExternal(
                                getItemTypeForTable($FUSIONINVENTORY_MAPPING[$type][$link]['dropdown']),
                                $oidvalues[$oid][""],0);
                  }
               }


               switch ($type) {
                  case NETWORKING_TYPE :
                     $Field = "networkequipments_id";
                     if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] == "glpi_networkequipments") {
                        $Field = "id";
                     }
                     break;

                  case PRINTER_TYPE :
                     $Field = "printers_id";
                     if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] == "glpi_printers") {
                        $Field = "id";
                     }
                     break;
               }
               if ($_SESSION['fusioninventory_logs'] == "1")
                  $logs->write("fusioninventory_fullsync",$link." = ".$oidvalues[$oid][""],$type,$ID_Device,1);

               // * Memory
               if (($link == "ram") OR ($link == "memory")) {
                  $oidvalues[$oid][""] = ceil(($oidvalues[$oid][""] / 1024) / 1024) ;
                  if ($type == PRINTER_TYPE) {
                     $oidvalues[$oid][""] .= " MB";
                  }
               }

               if ($link == 'macaddr') {
                  $MacAddress = PluginFusioninventoryIfmac::ifmacwalk_ifmacaddress($oidvalues[$oid][""]);

                  $oidvalues[$oid][""] = $MacAddress;
               }

               // Convert hexa in string
               $oidvalues[$oid][""] = PluginFusioninventorySNMP::hex_to_string($oidvalues[$oid][""]);

               if (strstr($oidvalues[$oid][""], "noSuchName")) {
                  // NO Update field in GLPI
               } else if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] ==
                       "glpi_plugin_fusioninventory_printers_cartridges") {
                  // * Printers cartridges
                  $object_name_clean = str_replace("MAX", "", $link);
                  $object_name_clean = str_replace("REMAIN", "", $object_name_clean);
                  if (strstr($link, "MAX")) {
                     $printer_cartridges_max_remain[$object_name_clean]["MAX"] = $oidvalues[$oid][""];
                  }
                  if (strstr($link, "REMAIN")) {
                     $printer_cartridges_max_remain[$object_name_clean]["REMAIN"] =
                             $oidvalues[$oid][""];
                  }
                  if ((isset($printer_cartridges_max_remain[$object_name_clean]["MAX"]))
                          AND (isset($printer_cartridges_max_remain[$object_name_clean]["REMAIN"]))) {
                     $pourcentage = ceil((
                             100 * $printer_cartridges_max_remain[$object_name_clean]["REMAIN"]) /
                             $printer_cartridges_max_remain[$object_name_clean]["MAX"]);
                     // Test existance of row in MySQl
                     $query_sel = "SELECT *
                                FROM ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                WHERE ".$Field."='".$ID_Device."'
                                      AND `object_name`='".$object_name_clean."';";
                     $result_sel = $DB->query($query_sel);
                     if ($DB->numrows($result_sel) == "0") {
                        $queryInsert = "INSERT INTO ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                                 (".$Field.",object_name)
                                     VALUES('".$ID_Device."', '".$object_name_clean."');";
                        $DB->query($queryInsert);
                     }

                     $queryUpdate = "UPDATE ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                  SET ".$FUSIONINVENTORY_MAPPING[$type][$link]['field']."='".$pourcentage."'
                                  WHERE ".$Field."='".$ID_Device."'
                                        AND `object_name`='".$object_name_clean."';";

                     $DB->query($queryUpdate);
                     unset($printer_cartridges_max_remain[$object_name_clean]["MAX"]);
                     unset($printer_cartridges_max_remain[$object_name_clean]["REMAIN"]);
                  } else {
                     // Test existance of row in MySQl
                     $query_sel = "SELECT *
                                FROM ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                WHERE ".$Field."='".$ID_Device."'
                                      AND `object_name`='".$link."';";
                     $result_sel = $DB->query($query_sel);
                     if ($DB->numrows($result_sel) == "0") {
                        $queryInsert = "INSERT INTO ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                                 (".$Field.",object_name)
                                     VALUES('".$ID_Device."', '".$link."');";

                        $DB->query($queryInsert);
                     }

                     $queryUpdate = "UPDATE ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                  SET ".$FUSIONINVENTORY_MAPPING[$type][$link]['field']."='".
                             $oidvalues[$oid][""]."'
                                  WHERE ".$Field."='".$ID_Device."'
                                        AND `object_name`='".$link."';";

                     $DB->query($queryUpdate);
                  }
               } else if (strstr($link, "pagecounter")) {
                  // Detect if the script has wroten a line for the counter today
                  // (if yes, don't touch, else add line)
                  $today = strftime("%Y-%m-%d", time());
                  $query_line = "SELECT *
                              FROM `glpi_plugin_fusioninventory_printers_history`
                              WHERE `date` LIKE '".$today."%'
                                    AND `printers_id`='".$ID_Device."';";
                  $result_line = $DB->query($query_line);
                  if ($DB->numrows($result_line) == "0") {
                     if (empty($oidvalues[$oid][""])) {
                        $oidvalues[$oid][""] = 0;
                     }
                     $queryInsert = "INSERT INTO ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                              (".$FUSIONINVENTORY_MAPPING[$type][$link]['field'].",".
                             $Field.", `date`)
                                  VALUES('".$oidvalues[$oid][""]."','".$ID_Device."', '".
                             $today."');";
                     $DB->query($queryInsert);
                  } else {
                     $data_line = $DB->fetch_assoc($result_line);
                     if ($data_line[$FUSIONINVENTORY_MAPPING[$type][$link]['field']] == "0") {
                        if (empty($oidvalues[$oid][""])) {
                           $oidvalues[$oid][""] = 0;
                        }
                        $queryUpdate = "UPDATE ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                     SET ".$FUSIONINVENTORY_MAPPING[$type][$link]['field']."='".
                                $oidvalues[$oid][""]."'
                                     WHERE ".$Field."='".$ID_Device."'
                                           AND `date` LIKE '".$today."%';";

                        $DB->query($queryUpdate);
                     }
                  }
               } else if (($link == "cpuuser") OR ($link ==  "cpusystem")) {
                  if ($object_name == "cpuuser") {
                     $cpu_values['cpuuser'] = $oidvalues[$oid][""];
                  }
                  if ($object_name ==  "cpusystem") {
                     $cpu_values['cpusystem'] = $oidvalues[$oid][""];
                  }
                  if ((isset($cpu_values['cpuuser'])) AND (isset($cpu_values['cpusystem']))) {
                     $queryUpdate = "UPDATE ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                  SET ".$FUSIONINVENTORY_MAPPING[$type][$link]['field']."='".
                             ($cpu_values['cpuuser'] + $cpu_values['cpusystem'])."'
                                  WHERE ".$Field."='".$ID_Device."';";

                     $DB->query($queryUpdate);
                     unset($cpu_values);
                  }
               } else if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] != "") {
                  if (($FUSIONINVENTORY_MAPPING[$type][$link]['field'] == "cpu")
                          AND (empty($oidvalues[$oid][""]))) {
                     $SNMPValue = 0;
                  }
                  if (strstr($FUSIONINVENTORY_MAPPING[$type][$link]['table'], "glpi_plugin_fusioninventory")) {
                     $queryUpdate = "UPDATE ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                  SET ".$FUSIONINVENTORY_MAPPING[$type][$link]['field']."='".$oidvalues[$oid][""]."'
                  WHERE ".$Field."='".$ID_Device."'";

                     $DB->query($queryUpdate);
                  } else {
                     $commonitem = new commonitem;
                     $commonitem->setType($type,true);

                     $tableau[$Field] = $ID_Device;
                     $tableau[$FUSIONINVENTORY_MAPPING[$type][$link]['field']] = $oidvalues[$oid][""];
                     $commonitem->obj->update($tableau);
                  }
               }
            }
         }
      }
   }

   /**
    * Update Networking ports from devices SNMP queries
    *
    * @param $ID_Device : id of device
    * @param $type : type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
    * @param $oidsModel : oid list from model SNMP
    * @param $oidvalues : list of values from agent query
    * @param $Array_Object_TypeNameConstant : array with oid => constant in relation with fields to update
    *
    **/
   static function updateGLPINetworkingPorts($ID_Device,$type,$oidsModel,$oidvalues,
                                             $Array_Object_TypeNameConstant) {
      global $DB,$LANG,$FUSIONINVENTORY_MAPPING;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusioninventoryLogs;
      $snmp_queries = new PluginFusioninventorySNMP;
      $walks = new PluginFusioninventoryWalk;
      $nn = new NetworkPort_NetworkPort();

      if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                 ">>>>>>>>>> Update ports device values <<<<<<<<<<",$type,$ID_Device,1);

      foreach($Array_Object_TypeNameConstant as $oid=>$link) {
         if ((preg_match("/\.$/",$oid)) AND (!empty($FUSIONINVENTORY_MAPPING[$type][$link]['field']))) {
            // SNMPWalk ONLY (ports)
//			print "OID : ".$oid."\n";

            // For each port
            if ($FUSIONINVENTORY_MAPPING[$type][$link]['field'] == 'mac') {
               $query = "SELECT `glpi_networkports`.`id`, `logical_number`,
                             `glpi_networkports`.`mac` as `mac`
                      FROM `glpi_networkports`
                           LEFT JOIN `glpi_plugin_fusioninventory_networking_ports`
                                     ON `networkports_id`=`glpi_networkports`.`id`
                      WHERE `items_id`='".$ID_Device."'
                            AND `itemtype`='".$type."'
                      ORDER BY `logical_number`;";
            } else {
               $query = "SELECT `glpi_networkports`.`id`, `logical_number`, ".
                       $FUSIONINVENTORY_MAPPING[$type][$link]['field']."
                      FROM `glpi_networkports`
                            LEFT JOIN `glpi_plugin_fusioninventory_networking_ports`
                                       ON `networkports_id`=`glpi_networkports`.`id`
            			 WHERE `items_id`='".$ID_Device."'
                            AND `itemtype`='".$type."'
                      ORDER BY `logical_number`;";
            }
            $result=$DB->query($query);

            while ($data=$DB->fetch_array($result)) {
               // Update Last UP
               if (($link == 'ifstatus') AND ($oidvalues[$oid.$data['logical_number']][""] == "1")) {
                  $query_update = "UPDATE `glpi_plugin_fusioninventory_networking_ports`
                                SET `lastup`='".date("Y-m-d H:i:s")."'
                                WHERE `networkports_id`='".$data["id"]."';";
                  $DB->query($query_update);
               }

               if (($link == 'ifPhysAddress')
                       AND (!strstr($oidvalues[$oid.$data['logical_number']][""], ":"))) {
                  $MacAddress = PluginFusioninventoryIfmac::ifmacwalk_ifmacaddress(
                          $oidvalues[$oid.$data['logical_number']][""]);

                  $oidvalues[$oid.$data['logical_number']][""] = $MacAddress;
                  if (empty($oidvalues[$oid.$data['logical_number']][""])) {
                     $oidvalues[$oid.$data['logical_number']][""] = "00:00:00:00:00:00";
                  }
               }
               if ($_SESSION['fusioninventory_logs'] == "1") $logs->write(
                          "fusioninventory_fullsync","****************",$type,$ID_Device,1);
               if ($_SESSION['fusioninventory_logs'] == "1") $logs->write(
                          "fusioninventory_fullsync","Oid : ".$oid,$type,$ID_Device,1);
               if ($_SESSION['fusioninventory_logs'] == "1") $logs->write(
                          "fusioninventory_fullsync","Link : ".$link,$type,$ID_Device,1);

               if (($link == "ifPhysAddress")
                       AND ($oidvalues[$oid.$data['logical_number']][""] != "")) {
                  $oidvalues[$oid.$data['logical_number']][""] =
                          $snmp_queries->MAC_Rewriting($oidvalues[$oid.$data['logical_number']][""]);
               }
               if ($link == "ip") {
                  $Arrayifaddr = $walks->GetoidValuesFromWalk(
                          $oidvalues,$oidsModel[0][1]['ip'],1);
                  for($j=0 ; $j < count($Arrayifaddr) ; $j++) {
                     if ($oidvalues[$oid.$Arrayifaddr[$j]][""] == $data['logical_number']) {
                        $data['logical_number'] = $Arrayifaddr[$j];
                     }
                  }
                  if ($_SESSION['fusioninventory_logs'] == "1")
                     $logs->write("fusioninventory_fullsync","=> ".$data['logical_number'],$type,$ID_Device,1);
               } else {
                  if (isset($oidvalues[$oid.$data['logical_number']][""])) {
                     if ($_SESSION['fusioninventory_logs'] == "1") $logs->write(
                                "fusioninventory_fullsync","=> ".$oidvalues[$oid.$data['logical_number']][""],$type,
                                $ID_Device,1);
                  }
               }
               if (isset($oidvalues[$oid.$data['logical_number']][""])) {
                  if ($data[$FUSIONINVENTORY_MAPPING[$type][$link]['field']] !=
                          $oidvalues[$oid.$data['logical_number']][""]) {
                     if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] == "glpi_networkports") {
                        $ID_field = "id";
                     } else {
                        $ID_field = "networkports_id";
                     }
                  } else {
                     if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] == "glpi_networkports") {
                        $ID_field = "id";
                     } else {
                        $ID_field = "networkports_id";
                     }
                  }
                  $queryUpdate = '';
                  if ($link == "ip") {
                     if ($data[$FUSIONINVENTORY_MAPPING[$type][$link]['field']] != $data['logical_number']) {
                        $queryUpdate = "UPDATE ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                     SET ".$FUSIONINVENTORY_MAPPING[$type][$link]['field']."='".$data['logical_number']."'
                     WHERE ".$ID_field."='".$data["id"]."'";
                     }
                  } else {
                     if ($data[$FUSIONINVENTORY_MAPPING[$type][$link]['field']] !=
                             $oidvalues[$oid.$data['logical_number']][""]) {
                        $queryUpdate = "UPDATE ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                     SET ".$FUSIONINVENTORY_MAPPING[$type][$link]['field']."='".
                                $oidvalues[$oid.$data['logical_number']][""]."'
                                     WHERE ".$ID_field."='".$data["id"]."';";
                     }
                  }
                  if ($queryUpdate != '') {
                     PluginFusioninventoryDb::lock_wire_check();
                     $DB->query($queryUpdate);
                     // Delete port wire if port is internal disable
                     if (($link == "ifinternalstatus")
                             AND (($oidvalues[$oid.$data['logical_number']][""] == "2")
                                     OR ($oidvalues[$oid.$data['logical_number']][""] == "down(2)"))) {
                        $netwire=new Netwire;
                        PluginFusioninventorySnmphistory::addLogConnection(
                                "remove",$netwire->getOppositeContact($data["id"]),$plugin_fusioninventory_processes_id);
                        PluginFusioninventorySnmphistory::addLogConnection("remove",$data["id"],$plugin_fusioninventory_processes_id);
                        if ($nn->getFromDBForNetworkPort($data['id'])) {
                           $nn->delete($data);
                        }

                     }
                     // Add log because snmp value change
                     PluginFusioninventorySnmphistory::addLog($data["id"],$FUSIONINVENTORY_MAPPING[$type][$link]['name'],
                             $data[$FUSIONINVENTORY_MAPPING[$type][$link]['field']],
                             $oidvalues[$oid.$data['logical_number']][""],$type."-".$link,
                             $_SESSION['plugin_fusioninventory_processes_id']);
                     PluginFusioninventoryDb::lock_wire_unlock();
                  }
               }
            }
         }
      }
   }

   /**
    * Associate a MAC address of a device to switch port
    *
    * @param $ID_Device
    * @param $type
    * @param $oidsModel
    * @param $oidvalues
    * @param $array_port_trunk : array with SNMP port id => 1 (from trunk oid)
    * @param $ArrayPortsID : array with port name and port id (from DB)
    * @param $vlan : VLAN number
    * @param $Array_trunk_ifIndex : array with SNMP port id => 1 (from CDP)
    *
    * @return nothing
    *
    **/
   static function getMACtoPort($ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,
           $ArrayPortsID,$vlan="",$Array_trunk_ifIndex=array()) {
      global $DB;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusioninventoryLogs;
      # * Manufacturers
      $manuf3com = new PluginFusioninventoryManufacturer3com;
      $manufCisco = new PluginFusioninventoryManufacturerCisco;
      $manufFoundryNetworks = new PluginFusioninventoryManufacturerFoundryNetworks;
      $manufHP = new PluginFusioninventoryManufacturerHP;

      switch (!false) {

         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"Cisco IOS Software, C1") :
            $sysDescr = "Cisco IOS Software, C1";
            break;

         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"Cisco") :
            $sysDescr = "Cisco";
            break;

         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"ProCurve J") :
         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"HP J4") :
         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"PROCURVE J") :
            $sysDescr = "ProCurve J";
            break;

         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"3Com IntelliJack NJ225") :
            $sysDescr = "3Com IntelliJack NJ225";
            break;

         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"Foundry Networks") :
            $sysDescr = "Foundry Networks";
            break;

      }
      if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                 ">>>>>>>>>> Networking : Get MAC associate to Port [Vlan ".$vlan."] <<<<<<<<<<",$type,
                 $ID_Device,1);

      if($sysDescr == "Cisco") {
         $manufCisco->GetMACtoPort($ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,
                 $ArrayPortsID,$vlan,$Array_trunk_ifIndex);
      } else if($sysDescr == "3Com IntelliJack NJ225") {
         $manuf3com->GetMACtoPort($ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,
                 $ArrayPortsID,$vlan,$Array_trunk_ifIndex);
      } else if($sysDescr == "ProCurve J") {
         $manufHP->GetMACtoPort($ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,$ArrayPortsID,
                 $vlan,$Array_trunk_ifIndex);
      } else if($sysDescr == "Foundry Networks") {
         $manufFoundryNetworks->GetMACtoPort($ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,
                 $ArrayPortsID,$vlan,$Array_trunk_ifIndex);
      }
   }

   /**
    * Determine CDP ports (trunk)
    *
    * @param $ID_Device : id of device
    * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
    * @param $oidsModel : oid list from model SNMP
    * @param $oidvalues : list of values from agent query
    * @param $ArrayPort_LogicalNum_SNMPNum : array logical port number => SNMP port number (ifindex)
    * @param $ArrayPortsID : array with port name and port id (from DB)
    *
    * @return array of trunk ports
    *
    **/
   static function cdp_trunk($ID_Device,$type,$oidsModel,$oidvalues,
                             $ArrayPort_LogicalNum_SNMPNum,$ArrayPortsID) {
      global $DB;

      $netwire=new Netwire;
      $nn = new NetworkPort_NetworkPort();
      $snmp_queries = new PluginFusioninventorySNMP;
      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusioninventoryLogs;
      $walks = new PluginFusioninventoryWalk;
      $Threads = new PluginFusioninventoryProcesses;
      $tmpc = new PluginFusioninventoryTmpConnections;
      $manuf3com = new PluginFusioninventoryManufacturer3com;
      $manufCisco = new PluginFusioninventoryManufacturerCisco;
      $manufHP = new PluginFusioninventoryManufacturerHP;

      $Array_cdp_ifIndex = array();
      $Array_trunk_ifIndex = array();
      $Array_multiplemac_ifIndex = array();
      //$trunk_no_cdp = array();

      if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                 ">>>>>>>>>> Networking : Get cdp trunk ports <<<<<<<<<<",$type,$ID_Device,1);

      switch (!false) {

         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"Cisco IOS Software, C1") :
            $sysDescr = "Cisco IOS Software, C1";
            break;

         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"Cisco") :
            $sysDescr = "Cisco";
            break;

         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"ProCurve J") :
         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"HP J4") :
         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"PROCURVE J") :
            $sysDescr = "ProCurve J";
            break;

         case strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"3Com IntelliJack NJ225") :
            $sysDescr = "3Com IntelliJack NJ225";
            break;

      }


      // Detect if ports are non trunk and have multiple mac addresses
      // (with list of dot1dTpFdbPort & dot1dBasePortIfIndex)
      // Get all port_number
      $pass = 0;

      //$manufCisco->NbMacEachPort

      if ($sysDescr == "Cisco") {
         $Array_vlan = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['vtpVlanName'],1);

         if ((array_count_values($Array_vlan) != 0) AND (array_count_values($Array_vlan) != 0)) {
            $pass = 1;
            // Creation of var for each port
            foreach ($ArrayPort_LogicalNum_SNMPNum AS $num=>$ifIndex) {
               $Arraydot1dTpFdbPort[$ifIndex] = 0;
            }
            foreach ($Array_vlan as $num=>$vlan) {
               $ArrayPortNumber = $walks->GetoidValuesFromWalk(
                       $oidvalues,$oidsModel[0][1]['dot1dTpFdbPort'],1,$vlan);
               foreach($ArrayPortNumber as $num=>$dynamicdata) {
                  $BridgePortNumber =
                          $oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$dynamicdata][$vlan];
                  $Arraydot1dTpFdbPort[$oidvalues[$oidsModel[0][1]['dot1dBasePortIfIndex'].".".
                                  $BridgePortNumber][$vlan]]++;
               }
            }
         }
      }
      if ($pass == "1") {
         foreach ($Arraydot1dTpFdbPort AS $ifIndex=>$num) {
            if ($num > 1) {
               $Array_multiplemac_ifIndex[$ifIndex] = 1;
            }
         }
      } else if ($pass == "0") {
         $Arraydot1dTpFdbPort = array();
         $ArrayConnectionsPort =
                 $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dTpFdbPort'],1);
         foreach($ArrayConnectionsPort as $num=>$Connectionkey) {
            $Arraydot1dTpFdbPort[] =
                    $oidvalues[$oidsModel[0][1]['dot1dTpFdbPort'].".".$Connectionkey][""];
         }
         $ArrayCount = array_count_values($Arraydot1dTpFdbPort);

         $ArrayPortNumber =
                 $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['dot1dBasePortIfIndex'],1);
         foreach($ArrayPortNumber as $num=>$PortNumber) {
            if ((isset($ArrayCount[$PortNumber])) AND ($ArrayCount[$PortNumber] > 1)) {
               $Array_multiplemac_ifIndex[$oidvalues[$oidsModel[0][1]['dot1dBasePortIfIndex'].".".
                               $PortNumber][""]] = 1;
            }
         }
      }
      if ($sysDescr == "Cisco IOS Software, C1") {
         $Array_multiplemac_ifIndex[$oidvalues[$oidsModel[0][1]['dot1dBasePortIfIndex'].
                         ".3"][$vlan]] = 1;
      }
      // End detection of ports non trunk and have multiple mac addresses

      // Initialization of Trunk ports (Trunk in Cisco AND Tagged in other switchs)
      $Array_trunk_ifIndex = array();


      // ***** Get Trunk / taged ports
      switch ($sysDescr) {

         case "Cisco" :
            $Array_trunk_ifIndex = $manufCisco->TrunkPorts($oidvalues,$oidsModel,$ID_Device,$type);
            break;

         case "ProCurve J" :
            $Array_trunk_ifIndex = $manufHP->TrunkPorts($oidvalues,$oidsModel,$ID_Device,$type);
            break;
      }

      // ***** Get CDP ports
      switch ($sysDescr) {

         case "Cisco" :
            list($Array_cdp_ifIndex, $Array_multiplemac_ifIndex) =
                    $manufCisco->CDPPorts($oidvalues,$oidsModel,$ID_Device,$type,
                    $Array_multiplemac_ifIndex);
            break;

         case "ProCurve J" :
            list($Array_cdp_ifIndex, $Array_multiplemac_ifIndex) =
                    $manufHP->CDPPorts($oidvalues,$oidsModel,$ID_Device,$type,$Array_multiplemac_ifIndex);
            break;

         case "3Com IntelliJack NJ225" :
            $Array_multiplemac_ifIndex = $manuf3com->MultiplePorts();
            break;
      }

      // ** Update for all ports on this network device the field 'trunk' in
      // glpi_plugin_fusioninventory_networking_ports
      foreach($ArrayPort_LogicalNum_SNMPNum AS $num=>$ifIndex) {
         $query = "SELECT *, `glpi_plugin_fusioninventory_networking_ports`.`id` AS `sid`
                FROM `glpi_networkports`
                     LEFT JOIN `glpi_plugin_fusioninventory_networking_ports`
                               ON `glpi_plugin_fusioninventory_networking_ports`.`networkports_id` =
                                  `glpi_networkports`.`id`
                WHERE `itemtype`='2'
                      AND `items_id`='".$ID_Device."'
                      AND `logical_number`='".$ifIndex."';";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            // If trunk => 1
            if ((isset($Array_trunk_ifIndex[$ifIndex])) AND ($Array_trunk_ifIndex[$ifIndex] == "1")) {
               if ($data['trunk'] != "1") {
                  $query_update = "UPDATE `glpi_plugin_fusioninventory_networking_ports`
                                SET `trunk`='1'
                                WHERE `id`='".$data['sid']."';";
                  $DB->query($query_update);
                  PluginFusioninventorySnmphistory::addLog(
                          $data["networkports_id"],"trunk","0","1","",$_SESSION['plugin_fusioninventory_processes_id']);
                  // Remove vlan
                  $snmp_queries->CleanVlan($data['networkports_id']);
                  $snmp_queries->CleanVlan($netwire->getOppositeContact($data['networkports_id']));
               }
               // If multiple => -1
            } else if (isset($Array_multiplemac_ifIndex[$ifIndex])
                    AND ($Array_multiplemac_ifIndex[$ifIndex] == "1")) {
               if ($data['trunk'] != "-1") {
                  $query_update = "UPDATE `glpi_plugin_fusioninventory_networking_ports`
                                SET `trunk`='-1'
                                WHERE `id`='".$data['sid']."';";
                  $DB->query($query_update);
                  PluginFusioninventorySnmphistory::addLog($data["networkports_id"],"trunk","0","-1","",
                          $_SESSION['plugin_fusioninventory_processes_id']);
                  // Remove vlan
                  PluginFusioninventoryDb::lock_wire_check();
                  PluginFusioninventorySnmphistory::addLogConnection("remove",
                          $netwire->getOppositeContact($data['networkports_id']),$plugin_fusioninventory_processes_id);
                  PluginFusioninventorySnmphistory::addLogConnection("remove",$data['networkports_id'],$plugin_fusioninventory_processes_id);
                  $snmp_queries->CleanVlan($data['networkports_id']);
                  $snmp_queries->CleanVlan($netwire->getOppositeContact($data['networkports_id']));
                  // Remove connection
                  if ($nn->getFromDBForNetworkPort($data['networkports_id'])) {
                     $nn->delete(array('id'=>$data['networkports_id']));
                  }
                  PluginFusioninventoryDb::lock_wire_unlock();
               }
            } else if($data['trunk'] != "0") {
               $query_update = "UPDATE `glpi_plugin_fusioninventory_networking_ports`
                             SET `trunk`='0'
                             WHERE `id`='".$data['sid']."';";
               $DB->query($query_update);
               PluginFusioninventorySnmphistory::addLog($data["networkports_id"],"trunk","1","0","",
                       $_SESSION['plugin_fusioninventory_processes_id']);
            }
         }
      }


      // ***** Add ports and connections in glpi_plugin_fusioninventory_tmp_* tables for connections between
      // switchs
      foreach($Array_multiplemac_ifIndex AS $ifIndex=>$val) {
         $ifName = $oidvalues[$oidsModel[0][1]['ifName'].".".$ifIndex][""];
         $TMP_ID = $tmpc->UpdatePort($ID_Device,$ArrayPortsID[$ifName]);

         switch ($sysDescr) {

            case "Cisco" :
               $manufCisco->tmpConnections($oidvalues,$oidsModel,$ifIndex,$TMP_ID,$ID_Device,$type);
               break;

            case "ProCurve J" :
               $manufHP->tmpConnections($oidvalues,$oidsModel,$ifIndex,$TMP_ID,$ID_Device,$type);
               break;

            case "3Com IntelliJack NJ225" :
               $manuf3com->tmpConnections($oidvalues,$oidsModel,$ifIndex,$TMP_ID,$ID_Device,$type);
               break;
         }
      }

      foreach($Array_cdp_ifIndex AS $ifIndex=>$val) {
         $ifName = $oidvalues[$oidsModel[0][1]['ifName'].".".$ifIndex][""];
         $TMP_ID = $tmpc->UpdatePort($ID_Device,$ArrayPortsID[$ifName],1);
         $Array_multiplemac_ifIndex[$ifIndex] = 1;
      }

      return $Array_multiplemac_ifIndex;
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

   static function networking_ifaddr($ID_Device,$type,$oidsModel,$oidvalues) {
      global $DB;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusioninventoryLogs;
      $walks = new PluginFusioninventoryWalk;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                 ">>>>>>>>>> List of IP addresses of device <<<<<<<<<<",$type,$ID_Device,1);

      $ifaddr_add = array();
      $ip = array();

      $query = "SELECT *
             FROM `glpi_plugin_fusioninventory_networking_ifaddr`
             WHERE `networkequipments_id`='".$ID_Device."';";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $ip[$data["ip"]] = $data["networkequipments_id"];
         }
      }

      $ifaddr_switch = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['ipAdEntAddr']);

      foreach($ip as $ifIP=>$networkequipments_id) {
         foreach($ifaddr_switch as $num_switch=>$ifIP_switch) {
            if ($ifIP == $ifIP_switch) {
               unset ($ip[$ifIP]);
               unset ($ifaddr_switch[$num_switch]);
            }
         }
      }

      foreach($ip as $ifaddr_snmp=>$networkequipments_id) {
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                       WHERE `networkequipments_id`='".$ID_Device."'
                             AND `ip`='".$ifaddr_snmp."';";
         $DB->query($query_delete);
      }
      foreach($ifaddr_switch as $num_snmp=>$ifaddr_snmp) {
         $query_insert = "INSERT INTO `glpi_plugin_fusioninventory_networking_ifaddr`(`networkequipments_id`,`ip`)
                       VALUES('".$ID_Device."','".$ifaddr_snmp."');";
         $DB->query($query_insert);
      }
   }
}

?>