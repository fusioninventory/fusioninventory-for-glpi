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
                                    `glpi_plugin_fusioninventory_mib`.`FK_model_infos`
                    WHERE `FK_networking`='".$ID_Device."'
                          AND `glpi_plugin_fusioninventory_networking`.`FK_model_infos`!='0' ";
      } else if($type == PRINTER_TYPE) {
			$query_add = "LEFT JOIN `glpi_plugin_fusioninventory_printers`
                                 ON `glpi_plugin_fusioninventory_printers`.`FK_model_infos`=
                                    `glpi_plugin_fusioninventory_mib`.`FK_model_infos`
                    WHERE `FK_printers`='".$ID_Device."'
                          AND `glpi_plugin_fusioninventory_printers`.`FK_model_infos`!='0' ";
      }
			
		$query = "SELECT `mapping_type`, `mapping_name`, `oid_port_dyn`,
                       `glpi_plugin_fusioninventory_mib_oid`.`name` AS `name`
                FROM `glpi_plugin_fusioninventory_mib`
                     LEFT JOIN `glpi_plugin_fusioninventory_mib_oid`
                               ON `glpi_plugin_fusioninventory_mib`.`FK_mib_oid`=
                                  `glpi_plugin_fusioninventory_mib_oid`.`ID`
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

         $queryPort = "SELECT *
                       FROM `glpi_plugin_fusioninventory_networking_ports`
                            LEFT JOIN `glpi_networking_ports`
                                      ON `glpi_plugin_fusioninventory_networking_ports`.`FK_networking_ports`=
                                         `glpi_networking_ports`.`ID`
                       WHERE (`ifdescr`='".$ifDescr."'
                                OR `glpi_networking_ports`.`name`='".$ifDescr."')
                             AND `glpi_networking_ports`.`on_device`='".$data["FK_networking"]."'
                             AND `glpi_networking_ports`.`device_type`='2';";
         $resultPort = $DB->query($queryPort);
         $dataPort = $DB->fetch_assoc($resultPort);
         if ($DB->numrows($resultPort) == "0") {
            // Search in other devices
            $queryPort = "SELECT *
                          FROM `glpi_networking_ports`
                          WHERE `ifaddr`='".$IP."'
                          ORDER BY `device_type`
                          LIMIT 0,1;";
            $resultPort = $DB->query($queryPort);
            $dataPort = $DB->fetch_assoc($resultPort);
            $PortID = $dataPort["ID"];
         } else {
            $PortID = $dataPort["FK_networking_ports"];
         }
      } else {
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
         }

         $query = "SELECT *
             FROM `glpi_networking_ports`
             WHERE `device_type`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
               AND`ifaddr`='".$IP."'
             LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            if ($pfiud->convertUnknownToUnknownNetwork($data['on_device'])) {
               // Add port
               $input = array();
               $input['on_device'] = $data['on_device'];
               $input['device_type'] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
               $input['ifaddr'] = $IP;
               $input['name'] = $ifDescr;
               $PortID = $np->add($input);
               return $PortID;
            }
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
				PluginFusionInventorySNMPHistory::addLogConnection("remove",$netwire->getOppositeContact($source_port),$FK_process);
				PluginFusionInventorySNMPHistory::addLogConnection("remove",$source_port,$FK_process);
				$this->CleanVlan($source_port);
            removeConnector($source_port);

				PluginFusionInventorySNMPHistory::addLogConnection("remove",$netwire->getOppositeContact($destination_port),$FK_process);
				PluginFusionInventorySNMPHistory::addLogConnection("remove",$destination_port,$FK_process);
            $this->CleanVlan($destination_port);
            removeConnector($destination_port);
						
				makeConnector($source_port,$destination_port);
				PluginFusionInventorySNMPHistory::addLogConnection("make",$destination_port,$FK_process);
				PluginFusionInventorySNMPHistory::addLogConnection("make",$source_port,$FK_process);
				
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
//		PluginFusionInventorySNMPHistory::addLogConnection("remove",$netwire->getOppositeContact($destination_port),$FK_process);
//      PluginFusionInventorySNMPHistory::addLogConnection("remove",$destination_port,$FK_process);
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

   static function auth_dropdown($selected="") {
      global $DB;

      $plugin_fusioninventory_snmp_auth = new PluginFusionInventorySNMPAuth;
      $config = new PluginFusionInventoryConfig;

      if ($config->getValue("authsnmp") == "file") {
         echo $plugin_fusioninventory_snmp_auth->selectbox($selected);
      } else  if ($config->getValue("authsnmp") == "DB") {
         dropdownValue("glpi_plugin_fusioninventory_snmp_connection","FK_snmp_connection",$selected,0);
      }
   }

   /**
    * Description
    *
    * @param $ArrayListDevice
    * @param $FK_process
    * @param $ArrayListType
    * @param $ArrayListAgentProcess
    *
    * @return
    *
    **/
   static function updateDeviceBySNMP_startprocess($ArrayListDevice,$FK_process = 0,$ArrayListType,
                                                   $ArrayListAgentProcess) {
      global $DB;

      $config_snmp_script = new PluginFusionInventoryConfigSNMPScript;
      $nb_process_query = $config_snmp_script->getValue('nb_process');
      $Thread = new PluginFusionInventoryProcesses;

      // Prepare processes
      $while = 'while (';
      for ($i = 1 ; $i <= $nb_process_query ; $i++) {
         if ($i == $nb_process_query) {
            $while .= '$t['.$i.']->isActive()';
         } else {
            $while .= '$t['.$i.']->isActive() || ';
         }
      }

      $while .= ') {';
      for ($i=1 ; $i <= $nb_process_query ; $i++) {
         $while .= 'echo $t['.$i.']->listen();';
      }
      $while .= '}';

      $close = '';
      for ($i=1 ; $i <= $nb_process_query ; $i++) {
         $close .= 'echo $t['.$i.']->close();';
      }
      // End processes

      $s = 0;
      foreach ($ArrayListDevice as $num=>$IDDevice) {
         $s++;
         $t[$s] = $Thread->create("fusioninventory_fullsync.php --update_device_process=1 --id=".$IDDevice.
                 " --FK_process=".$FK_process." --FK_agent_process=".$ArrayListAgentProcess[$num].
                 " --type=".$ArrayListType[$num]);

         if ($nb_process_query == $s) {
            eval($while);
            // Display 0 in fusioninventory_fullsync.log
            // TODO : Try to not display it
            eval($close);
            $s = 0;
         }
      }
      if ($s > 0) {
         $s++;
         for ($s ; $s <= $nb_process_query ; $s++) {
            $while = str_replace("|| \$t[".$s."]->isActive()", "", $while);
            $while = str_replace("echo \$t[".$s."]->listen();", "", $while);
            $close = str_replace("echo \$t[".$s."]->close();", "", $close);
         }
         eval($while);
         eval($close);
         $s = 0;
      }
   }

   /**
    * Get and update infos of networking and its ports
    *
    * @param $ID_Device
    * @param $FK_process
    * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
    * @param $FK_agent_process
    *
    * @return nothing
    *
    **/
   static function updateDeviceBySNMP_process($ID_Device,$FK_process = 0,$type,$FK_agent_process) {
      $ifIP = "";
      $_SESSION['FK_process'] = $FK_process;

      $plugin_fusioninventory_snmp_auth = new PluginFusionInventorySNMPAuth;
      $Threads = new PluginFusionInventoryProcesses;
      $models = new PluginFusionInventoryModelInfos;
      $walks = new PluginFusionInventoryWalk;
      $plugin_fusioninventory_snmp = new PluginFusionInventorySNMP;
      $manufOKI = new PluginFusionInventoryManufacturerOKI;
      $config = new PluginFusionInventoryConfigSNMPScript;

      if ($config->getValue("logs") != '0') {
         $_SESSION['fusioninventory_logs'] = '1';
      } else {
         $_SESSION['fusioninventory_logs'] = '0';
      }


      // Load XML Device ID
      $xml = simplexml_load_file(GLPI_PLUGIN_DOC_DIR."/fusioninventory/".$FK_agent_process."-device.xml");
      foreach($xml->device as $device) {
         if (($device->infos->id == $ID_Device) AND ($device->infos->type == $type)) {
            $device_snmp = $device;
            break;
         }
      }
      unset($xml);
      unset($device);
      // Get SNMP model oids
      $oidsModel = $models->oidlist($ID_Device,$type);
      ksort($oidsModel);

      if ((isset($oidsModel)) && ($ID_Device != "")) {
         // Get oidvalues from agents
         $oidvalues = $walks->GetoidValues($device_snmp);
         unset($device_snmp);
         if (is_array($oidvalues)) {
            ksort($oidvalues);
         } else {
            return;
         }

         // For some manufacturer
         $oidvalues = $manufOKI->CorrectFirmware($oidvalues);

         // Update count Process server script
         switch ($type) {
            case NETWORKING_TYPE :
               $Threads->updateProcess($_SESSION['FK_process'],1);
               break;

            case PRINTER_TYPE :
               $Threads->updateProcess($_SESSION['FK_process'],0,1);
               break;
         }

         // ** Get oid of vtpVlanName
         $Array_Object_oid_vtpVlanName = '';
         if (isset($oidsModel[0][0]['vtpVlanName'])) {
            $Array_Object_oid_vtpVlanName = $oidsModel[0][0]['vtpVlanName'];
         }
         // ** Get from SNMP, description of equipment
         $sysDescr = "";
         if (strstr($oidvalues[".1.3.6.1.2.1.1.1.0"][""],"Cisco")) {
            $sysDescr = "Cisco";
         }

         //**
         $ArrayPort_LogicalNum_SNMPName =
                 $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['ifName']);

         // **
         $ArrayPort_LogicalNum_SNMPNum =
                 $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['ifIndex'],1);

         // ** Get oid ports Counter
         //array with logic number => portsID from snmp
         $ArrayPort_Object_oid = PluginFusionInventorySNMP::getOIDPorts(
                 $ID_Device,$type,$oidsModel,$oidvalues,$ArrayPort_LogicalNum_SNMPName,
                 $ArrayPort_LogicalNum_SNMPNum);

         // ** Get link OID fields (oid => link)
         $Array_Object_TypeNameConstant = $plugin_fusioninventory_snmp->GetLinkOidToFields($ID_Device,$type);

         if ($type == NETWORKING_TYPE) {
            PluginFusionInventorySNMP::networking_ifaddr($ID_Device,$type,$oidsModel,$oidvalues);
         }

         // ** Update fields of switchs
         PluginFusionInventorySNMP::updateGLPIDevice(
                 $ID_Device,$type,$oidsModel,$oidvalues,$Array_Object_TypeNameConstant);

         //** From DB Array : portName => glpi_networking_ports.ID
         $ArrayPortDB_Name_ID = $plugin_fusioninventory_snmp->GetPortsID($ID_Device,$type);

         // ** Update ports fields of switchs
         if (!empty($ArrayPort_Object_oid)) {
            PluginFusionInventorySNMP::updateGLPINetworkingPorts(
                    $ID_Device,$type,$oidsModel,$oidvalues,$Array_Object_TypeNameConstant);
         }
         $Array_trunk_ifIndex = array();

         if ($type == NETWORKING_TYPE) {
            $Array_trunk_ifIndex = PluginFusionInventorySNMP::cdp_trunk(
                    $ID_Device,$type,$oidsModel,$oidvalues,$ArrayPort_LogicalNum_SNMPNum,
                    $ArrayPortDB_Name_ID);
         }
         // ** Get MAC adress of connected ports
         $array_port_trunk = array();
         if (!empty($ArrayPort_Object_oid)) {
            $array_port_trunk = PluginFusionInventorySNMP::getMACtoPort(
                    $ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,$ArrayPortDB_Name_ID,'',
                    $Array_trunk_ifIndex);
         }
         if (($type == NETWORKING_TYPE) AND ($sysDescr == "Cisco")) {
            // Foreach VLAN ID to GET MAC Adress on each VLAN
            $Array_vlan = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['vtpVlanName'],1);
            foreach ($Array_vlan as $num=>$vlan_ID) {
               PluginFusionInventorySNMP::getMACtoPort(
                       $ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,$ArrayPortDB_Name_ID,
                       $vlan_ID,$Array_trunk_ifIndex);
            }
         }
      }
   }

   /**
    * Get port OID list for the SNMP model && create ports in DB if they don't exists
    *
    * @param $ID_Device : ID of device
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

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusionInventoryLogs;
      $manufCisco = new PluginFusionInventoryManufacturerCisco;
      $netwire=new Netwire;
      $np=new Netport;
      $ptp = new PluginFusionInventoryPort;

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
             WHERE `FK_networking`='".$ID_Device."'
                   AND `last_PID_update`='0';";
      $result = $DB->query($query);
      if ($DB->numrows($result) == 1) {
         foreach ($ArrayPort_LogicalNum_SNMPNum as $num=>$ifIndex) {
            $query_update = "UPDATE `glpi_networking_ports`
                          SET `logical_number`='".$ifIndex."'
                          WHERE `on_device`='".$ID_Device."'
                                AND `device_type`='".$type."'
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
                  $query = "SELECT `ID`, `name`
                         FROM `glpi_networking_ports`
                         WHERE `on_device`='".$ID_Device."'
                               AND `device_type`='".$type."'
                               AND `logical_number`='".$ifIndex."';";
                  $result = $DB->query($query);
                  if ($DB->numrows($result) == 0) {
                     unset($array);
                     $array["logical_number"] = $ifIndex;
                     $array["name"] = $ArrayPort_LogicalNum_SNMPName[$num];
                     $array["on_device"] = $ID_Device;
                     $array["device_type"] = $type;

                     $IDport = $np->add($array);
                     logEvent(0, "networking", 5, "inventory", "FusionInventory ".$LANG["log"][70]);
                     if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                                "Add port in DB (glpi_networking_ports) : ".
                                $ArrayPort_LogicalNum_SNMPName[$i],$type,$ID_Device,1);
                  } else {
                     $IDport = $DB->result($result, 0, "ID");
                     if ($DB->result($result, 0, "name") != $ArrayPort_LogicalNum_SNMPName[$num]) {
                        unset($array);
                        $array["name"] = $ArrayPort_LogicalNum_SNMPName[$num];
                        $array["ID"] = $DB->result($result, 0, "ID");
                        $np->update($array);
                        if ($_SESSION['fusioninventory_logs'] == "1")
                           $logs->write("fusioninventory_fullsync",
                                   "Update port in DB (glpi_networking_ports) : ID".
                                   $DB->result($result, 0, "ID")." & name ".
                                   $ArrayPort_LogicalNum_SNMPName[$i],$type,$ID_Device,1);
                     }
                  }
                  if ($type == NETWORKING_TYPE) {
                     $queryFusionInventoryPort = "SELECT `ID`
                                       FROM `glpi_plugin_fusioninventory_networking_ports`
                                       WHERE `FK_networking_ports`='".$IDport."';";

                     $resultFusionInventoryPort = $DB->query($queryFusionInventoryPort);
                     if ($DB->numrows($resultFusionInventoryPort) == 0) {
                        $queryInsert = "INSERT INTO `glpi_plugin_fusioninventory_networking_ports`
                                                 (`FK_networking_ports`)
                                     VALUES ('".$IDport."');";
                        $DB->query($queryInsert);
                        if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                                   "Add port in DB (glpi_plugin_fusioninventory_networking_ports) : ID ".$IDport,$type,
                                   $ID_Device,1);
                     }
                  }
               }
            }
         }
         $logicalnumberlist .= ")";
      }
      // Delete all ports that will be not here
      foreach($deleteportname as $id=>$i) {
         $query = "SELECT *
                FROM `glpi_networking_ports`
                WHERE `on_device`='".$ID_Device."'
                      AND `device_type`='".$type."'
                      AND `logical_number`='".$i."';";
         $result = $DB->query($query);
         $data = $DB->fetch_assoc($result);

         PluginFusionInventorySNMPHistory::addLogConnection(
                 "remove",$netwire->getOppositeContact($data['ID']),$FK_process);
         PluginFusionInventorySNMPHistory::addLogConnection("remove",$data['ID'],$FK_process);
         removeConnector($data['ID']);

         $ptp->deleteFromDB($data["ID"],1);
         $np->delete($data);
      }

      // Delete ports where logical number in glpi_plugin_fusioninventory_networking_ports
      // not exist on switch : it's ports reorder not well
      $logicalnumberlist = str_replace(",)", ")", $logicalnumberlist);
      $query = "SELECT *
             FROM `glpi_networking_ports`
             WHERE `on_device`='".$ID_Device."'
                   AND `device_type`='".$type."'
                   AND `logical_number` NOT IN ".$logicalnumberlist.";";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         PluginFusionInventorySNMPHistory::addLogConnection(
                 "remove",$netwire->getOppositeContact($data['ID']),$FK_process);
         PluginFusionInventorySNMPHistory::addLogConnection("remove",$data['ID'],$FK_process);
         removeConnector($data['ID']);
         $np->delete($data);
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ports`
                       WHERE `FK_networking_ports`='".$data["ID"]."';";
         $DB->query($query_delete);
      }

      return $oidList;
   }

   /**
    * Update devices with values get by SNMP
    *
    * @param $ID_Device : ID of device
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

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusionInventoryLogs;

      if ($_SESSION['fusioninventory_logs'] == "1")
         $logs->write("fusioninventory_fullsync",">>>>>>>>>> Update devices values <<<<<<<<<<",$type,
                 $ID_Device,1);

      // Update 'last_fusioninventory_update' field
      $query = "UPDATE ";
      if ($type == NETWORKING_TYPE) {
         $query .= "`glpi_plugin_fusioninventory_networking`
             SET `last_fusioninventory_update`='".date("Y-m-d H:i:s")."',
                 `last_PID_update`='".$_SESSION['FK_process']."'
             WHERE `FK_networking`='".$ID_Device."';";
      }
      if ($type == PRINTER_TYPE) {
         $query .= "`glpi_plugin_fusioninventory_printers`
             SET `last_fusioninventory_update`='".date("Y-m-d H:i:s")."'
             WHERE `FK_printers`='".$ID_Device."';";
      }
      $DB->query($query);

      foreach($Array_Object_TypeNameConstant as $oid=>$link) {
         if (!preg_match("/\.$/",$oid)) { // SNMPGet ONLY
            if (isset($oidvalues[$oid][""])) {
               if ((isset($FUSIONINVENTORY_MAPPING[$type][$link]['dropdown']))
                       AND (!empty($FUSIONINVENTORY_MAPPING[$type][$link]['dropdown']))) {
                  $oidvalues[$oid][""] = PluginFusionInventorySNMP::hex_to_string($oidvalues[$oid][""]);
                  if ($FUSIONINVENTORY_MAPPING[$type][$link]['dropdown'] == "glpi_dropdown_model_networking") {
                     $oidvalues[$oid][""] =
                             externalImportDropdown($FUSIONINVENTORY_MAPPING[$type][$link]['dropdown'],
                             $oidvalues[$oid][""],0,array("manufacturer"=>$oidvalues[$oid][""]));
                  } else {
                     $oidvalues[$oid][""] =
                             externalImportDropdown($FUSIONINVENTORY_MAPPING[$type][$link]['dropdown'],
                             $oidvalues[$oid][""],0);
                  }
               }


               switch ($type) {
                  case NETWORKING_TYPE :
                     $Field = "FK_networking";
                     if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] == "glpi_networking") {
                        $Field = "ID";
                     }
                     break;

                  case PRINTER_TYPE :
                     $Field = "FK_printers";
                     if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] == "glpi_printers") {
                        $Field = "ID";
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
               $oidvalues[$oid][""] = PluginFusionInventorySNMP::hex_to_string($oidvalues[$oid][""]);

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
                                    AND `FK_printers`='".$ID_Device."';";
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
    * @param $ID_Device : ID of device
    * @param $type : type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
    * @param $oidsModel : oid list from model SNMP
    * @param $oidvalues : list of values from agent query
    * @param $Array_Object_TypeNameConstant : array with oid => constant in relation with fields to update
    *
    **/
   static function updateGLPINetworkingPorts($ID_Device,$type,$oidsModel,$oidvalues,
                                             $Array_Object_TypeNameConstant) {
      global $DB,$LANG,$FUSIONINVENTORY_MAPPING;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusionInventoryLogs;
      $snmp_queries = new PluginFusionInventorySNMP;
      $walks = new PluginFusionInventoryWalk;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                 ">>>>>>>>>> Update ports device values <<<<<<<<<<",$type,$ID_Device,1);

      foreach($Array_Object_TypeNameConstant as $oid=>$link) {
         if ((preg_match("/\.$/",$oid)) AND (!empty($FUSIONINVENTORY_MAPPING[$type][$link]['field']))) {
            // SNMPWalk ONLY (ports)
//			print "OID : ".$oid."\n";

            // For each port
            if ($FUSIONINVENTORY_MAPPING[$type][$link]['field'] == 'ifmac') {
               $query = "SELECT `glpi_networking_ports`.`ID`, `logical_number`,
                             `glpi_networking_ports`.`ifmac` as `ifmac`
                      FROM `glpi_networking_ports`
                           LEFT JOIN `glpi_plugin_fusioninventory_networking_ports`
                                     ON `FK_networking_ports`=`glpi_networking_ports`.`ID`
                      WHERE `on_device`='".$ID_Device."'
                            AND `device_type`='".$type."'
                      ORDER BY `logical_number`;";
            } else {
               $query = "SELECT `glpi_networking_ports`.`ID`, `logical_number`, ".
                       $FUSIONINVENTORY_MAPPING[$type][$link]['field']."
                      FROM `glpi_networking_ports`
                            LEFT JOIN `glpi_plugin_fusioninventory_networking_ports`
                                       ON `FK_networking_ports`=`glpi_networking_ports`.`ID`
            			 WHERE `on_device`='".$ID_Device."'
                            AND `device_type`='".$type."'
                      ORDER BY `logical_number`;";
            }
            $result=$DB->query($query);

            while ($data=$DB->fetch_array($result)) {
               // Update Last UP
               if (($link == 'ifstatus') AND ($oidvalues[$oid.$data['logical_number']][""] == "1")) {
                  $query_update = "UPDATE `glpi_plugin_fusioninventory_networking_ports`
                                SET `lastup`='".date("Y-m-d H:i:s")."'
                                WHERE `FK_networking_ports`='".$data["ID"]."';";
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
               if ($link == "ifaddr") {
                  $Arrayifaddr = $walks->GetoidValuesFromWalk(
                          $oidvalues,$oidsModel[0][1]['ifaddr'],1);
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
                     if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] == "glpi_networking_ports") {
                        $ID_field = "ID";
                     } else {
                        $ID_field = "FK_networking_ports";
                     }
                  } else {
                     if ($FUSIONINVENTORY_MAPPING[$type][$link]['table'] == "glpi_networking_ports") {
                        $ID_field = "ID";
                     } else {
                        $ID_field = "FK_networking_ports";
                     }
                  }
                  $queryUpdate = '';
                  if ($link == "ifaddr") {
                     if ($data[$FUSIONINVENTORY_MAPPING[$type][$link]['field']] != $data['logical_number']) {
                        $queryUpdate = "UPDATE ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                     SET ".$FUSIONINVENTORY_MAPPING[$type][$link]['field']."='".$data['logical_number']."'
                     WHERE ".$ID_field."='".$data["ID"]."'";
                     }
                  } else {
                     if ($data[$FUSIONINVENTORY_MAPPING[$type][$link]['field']] !=
                             $oidvalues[$oid.$data['logical_number']][""]) {
                        $queryUpdate = "UPDATE ".$FUSIONINVENTORY_MAPPING[$type][$link]['table']."
                                     SET ".$FUSIONINVENTORY_MAPPING[$type][$link]['field']."='".
                                $oidvalues[$oid.$data['logical_number']][""]."'
                                     WHERE ".$ID_field."='".$data["ID"]."';";
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
                        PluginFusionInventorySNMPHistory::addLogConnection(
                                "remove",$netwire->getOppositeContact($data["ID"]),$FK_process);
                        PluginFusionInventorySNMPHistory::addLogConnection("remove",$data["ID"],$FK_process);
                        removeConnector($data["ID"]);

                     }
                     // Add log because snmp value change
                     PluginFusionInventorySNMPHistory::addLog($data["ID"],$FUSIONINVENTORY_MAPPING[$type][$link]['name'],
                             $data[$FUSIONINVENTORY_MAPPING[$type][$link]['field']],
                             $oidvalues[$oid.$data['logical_number']][""],$type."-".$link,
                             $_SESSION['FK_process']);
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
    * @param $array_port_trunk : array with SNMP port ID => 1 (from trunk oid)
    * @param $ArrayPortsID : array with port name and port ID (from DB)
    * @param $vlan : VLAN number
    * @param $Array_trunk_ifIndex : array with SNMP port ID => 1 (from CDP)
    *
    * @return nothing
    *
    **/
   static function getMACtoPort($ID_Device,$type,$oidsModel,$oidvalues,$array_port_trunk,
           $ArrayPortsID,$vlan="",$Array_trunk_ifIndex=array()) {
      global $DB;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusionInventoryLogs;
      # * Manufacturers
      $manuf3com = new PluginFusionInventoryManufacturer3com;
      $manufCisco = new PluginFusionInventoryManufacturerCisco;
      $manufFoundryNetworks = new PluginFusionInventoryManufacturerFoundryNetworks;
      $manufHP = new PluginFusionInventoryManufacturerHP;

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
    * @param $ID_Device : ID of device
    * @param $type type of device (NETWORKING_TYPE, PRINTER_TYPE ...)
    * @param $oidsModel : oid list from model SNMP
    * @param $oidvalues : list of values from agent query
    * @param $ArrayPort_LogicalNum_SNMPNum : array logical port number => SNMP port number (ifindex)
    * @param $ArrayPortsID : array with port name and port ID (from DB)
    *
    * @return array of trunk ports
    *
    **/
   static function cdp_trunk($ID_Device,$type,$oidsModel,$oidvalues,
                             $ArrayPort_LogicalNum_SNMPNum,$ArrayPortsID) {
      global $DB;

      $netwire=new Netwire;
      $snmp_queries = new PluginFusionInventorySNMP;
      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusionInventoryLogs;
      $walks = new PluginFusionInventoryWalk;
      $Threads = new PluginFusionInventoryProcesses;
      $tmpc = new PluginFusionInventoryTmpConnections;
      $manuf3com = new PluginFusionInventoryManufacturer3com;
      $manufCisco = new PluginFusionInventoryManufacturerCisco;
      $manufHP = new PluginFusionInventoryManufacturerHP;

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
         $query = "SELECT *, `glpi_plugin_fusioninventory_networking_ports`.`ID` AS `sid`
                FROM `glpi_networking_ports`
                     LEFT JOIN `glpi_plugin_fusioninventory_networking_ports`
                               ON `glpi_plugin_fusioninventory_networking_ports`.`FK_networking_ports` =
                                  `glpi_networking_ports`.`ID`
                WHERE `device_type`='2'
                      AND `on_device`='".$ID_Device."'
                      AND `logical_number`='".$ifIndex."';";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            // If trunk => 1
            if ((isset($Array_trunk_ifIndex[$ifIndex])) AND ($Array_trunk_ifIndex[$ifIndex] == "1")) {
               if ($data['trunk'] != "1") {
                  $query_update = "UPDATE `glpi_plugin_fusioninventory_networking_ports`
                                SET `trunk`='1'
                                WHERE `ID`='".$data['sid']."';";
                  $DB->query($query_update);
                  PluginFusionInventorySNMPHistory::addLog(
                          $data["FK_networking_ports"],"trunk","0","1","",$_SESSION['FK_process']);
                  // Remove vlan
                  $snmp_queries->CleanVlan($data['FK_networking_ports']);
                  $snmp_queries->CleanVlan($netwire->getOppositeContact($data['FK_networking_ports']));
               }
               // If multiple => -1
            } else if (isset($Array_multiplemac_ifIndex[$ifIndex])
                    AND ($Array_multiplemac_ifIndex[$ifIndex] == "1")) {
               if ($data['trunk'] != "-1") {
                  $query_update = "UPDATE `glpi_plugin_fusioninventory_networking_ports`
                                SET `trunk`='-1'
                                WHERE `ID`='".$data['sid']."';";
                  $DB->query($query_update);
                  PluginFusionInventorySNMPHistory::addLog($data["FK_networking_ports"],"trunk","0","-1","",
                          $_SESSION['FK_process']);
                  // Remove vlan
                  PluginFusioninventoryDb::lock_wire_check();
                  PluginFusionInventorySNMPHistory::addLogConnection("remove",
                          $netwire->getOppositeContact($data['FK_networking_ports']),$FK_process);
                  PluginFusionInventorySNMPHistory::addLogConnection("remove",$data['FK_networking_ports'],$FK_process);
                  $snmp_queries->CleanVlan($data['FK_networking_ports']);
                  $snmp_queries->CleanVlan($netwire->getOppositeContact($data['FK_networking_ports']));
                  // Remove connection
                  removeConnector($data['FK_networking_ports']);
                  PluginFusioninventoryDb::lock_wire_unlock();
               }
            } else if($data['trunk'] != "0") {
               $query_update = "UPDATE `glpi_plugin_fusioninventory_networking_ports`
                             SET `trunk`='0'
                             WHERE `ID`='".$data['sid']."';";
               $DB->query($query_update);
               PluginFusionInventorySNMPHistory::addLog($data["FK_networking_ports"],"trunk","1","0","",
                       $_SESSION['FK_process']);
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

      if ($_SESSION['fusioninventory_logs'] == "1") $logs = new PluginFusionInventoryLogs;
      $walks = new PluginFusionInventoryWalk;

      if ($_SESSION['fusioninventory_logs'] == "1") $logs->write("fusioninventory_fullsync",
                 ">>>>>>>>>> List of IP addresses of device <<<<<<<<<<",$type,$ID_Device,1);

      $ifaddr_add = array();
      $ifaddr = array();

      $query = "SELECT *
             FROM `glpi_plugin_fusioninventory_networking_ifaddr`
             WHERE `FK_networking`='".$ID_Device."';";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $ifaddr[$data["ifaddr"]] = $data["FK_networking"];
         }
      }

      $ifaddr_switch = $walks->GetoidValuesFromWalk($oidvalues,$oidsModel[0][1]['ipAdEntAddr']);

      foreach($ifaddr as $ifIP=>$FK_networking) {
         foreach($ifaddr_switch as $num_switch=>$ifIP_switch) {
            if ($ifIP == $ifIP_switch) {
               unset ($ifaddr[$ifIP]);
               unset ($ifaddr_switch[$num_switch]);
            }
         }
      }

      foreach($ifaddr as $ifaddr_snmp=>$FK_networking) {
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                       WHERE `FK_networking`='".$ID_Device."'
                             AND `ifaddr`='".$ifaddr_snmp."';";
         $DB->query($query_delete);
      }
      foreach($ifaddr_switch as $num_snmp=>$ifaddr_snmp) {
         $query_insert = "INSERT INTO `glpi_plugin_fusioninventory_networking_ifaddr`(`FK_networking`,`ifaddr`)
                       VALUES('".$ID_Device."','".$ifaddr_snmp."');";
         $DB->query($query_insert);
      }
   }
}

?>