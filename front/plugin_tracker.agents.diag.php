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
	define('GLPI_ROOT', '../../..');
}

$NEEDED_ITEMS=array("tracker","search","printer","computer","networking","peripheral","phone","setup","rulesengine");
include (GLPI_ROOT."/inc/includes.php");

// Get conf tu know if SSL is only
$tracker_config = new PluginTrackerConfig;
$ssl = $tracker_config->getValue('ssl_only');
if (((isset($_SERVER["HTTPS"])) AND ($_SERVER["HTTPS"] == "on") AND ($ssl == "1")) OR ($ssl == "0")) {
	// echo "On continue";
} else {
	$out = "No SSL";
	$gzout = gzencode($out, 9);
	echo $gzout;
	exit();
}

if(isset($_POST['upload'])) { // si formulaire soumis
	//$content_dir = '/tmp/'; // dossier où sera déplacé le fichier
	$content_dir = GLPI_PLUGIN_DOC_DIR."/tracker/";
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/tracker')) {
		mkdir(GLPI_PLUGIN_DOC_DIR.'/tracker');
	}

   $tmp_file = $_FILES['data']['tmp_name'];

    if(!is_uploaded_file($tmp_file)) {
        exit("The file is not found");
    }

    // on vérifie maintenant l'extension
    $type_file = $_FILES['data']['type'];

    // on copie le fichier dans le dossier de destination
    $name_file = $_FILES['data']['name'];

    if(!move_uploaded_file($tmp_file, $content_dir . $name_file)) {
        exit("Impossible to copy file in $content_dir");
    }
    
	$name_file_xml = str_replace(".gz", "", $name_file);
//	$string = implode("", gzfile($content_dir . $name_file));
//	$fp = fopen($content_dir . $name_file_xml, "w");
//	fwrite($fp, $string, strlen($string));
//	fclose($fp);


	$fp = fopen($content_dir . $name_file_xml, "w") ;
	// file to be unzipped on your server
	$zp = gzopen($content_dir . $name_file, "r");
   if ($zp) {
      while (!gzeof($zp)) {
         $buff1 = gzgets ($zp, 4096) ;
         $buff1 = str_replace(chr(0),"",$buff1);
         $buff1 = str_replace(chr(0x1),"",$buff1);
         $buff1 = str_replace(chr(0x2),"",$buff1);
         $buff1 = str_replace(chr(0x3),"",$buff1);
         $buff1 = str_replace(chr(0x4),"",$buff1);
         $buff1 = str_replace(chr(0x5),"",$buff1);
         $buff1 = str_replace(chr(0x6),"",$buff1);
         $buff1 = str_replace(chr(0x7),"",$buff1);
         $buff1 = str_replace(chr(0x8),"",$buff1);
         $buff1 = str_replace(chr(0x9),"",$buff1);
         $buff1 = str_replace(chr(0x10),"",$buff1);
         $buff1 = str_replace(chr(0x11),"",$buff1);
         $buff1 = str_replace(chr(0x12),"",$buff1);
         $buff1 = str_replace(chr(0x13),"",$buff1);
         $buff1 = str_replace(chr(0x14),"",$buff1);
         $buff1 = str_replace(chr(0x15),"",$buff1);
         fputs($fp, $buff1) ;
      }
	}
	gzclose($zp) ;
	fclose($fp) ;

	
	unlink($content_dir.$name_file);
	
	// Open file for put it in DB
	$importexport = new PluginTrackerImportExport;
	if (strstr($name_file_xml,"-discovery.xml")) {
		$importexport->import_agent_discovery($content_dir,$name_file_xml);
		unlink($content_dir.$name_file_xml);
	}
	if (!strstr($name_file_xml,"-")) {
		// Recompose xml file
		$importexport->import_agentonly($content_dir,$name_file_xml);
		unlink($content_dir.$name_file_xml);
	}


    echo "The file has been successfully uploaded";
} else if(isset($_POST['get_data'])) {
	$agents_processes = new PluginTrackerAgentsProcesses;
	$xml = new PluginTrackerXML;
	$config_snmp_networking = new PluginTrackerConfigSNMPNetworking;
	$config_snmp_printer = new PluginTrackerConfigSNMPPrinter;
	$config = new PluginTrackerConfig;
	
//$_POST['key'] = "nN3HDPKVj0e8xxfgCIugjWmPzIRVxb";
	$query = "SELECT * FROM glpi_plugin_tracker_agents
	WHERE `key`='".$_POST['key']."'
	LIMIT 0,1";
	$result=$DB->query($query);
	if ($DB->numrows($result) > 0) {
		$data = $DB->fetch_assoc($result);
		if ($data["lock"] == "1") {
			$out = "Lock";
			$gzout = gzencode($out, 9);
			echo $gzout;
			exit();
		}
		$ID_agent = $data['ID'];
		if (!isset($_POST['PID'])) {
			$start_PID = "0001";
      } else {
			$start_PID = $_POST['PID'];
      }
		if (!isset($_POST['date'])) {
			$_POST['date'] = "0000-00-00 00:00:00";
      }
		// Add agent process entry
		$number_PID = $ID_agent;
		if (strlen($number_PID) == 1) {
			$number_PID = "00".$number_PID;
      }
		if (strlen($number_PID) == 2) {
			$number_PID = "0".$number_PID;
      }
		$add_agent_process['FK_agent'] = $ID_agent;
		$add_agent_process['process_number'] = $start_PID.$number_PID;
		$add_agent_process['status'] = 2;
		$add_agent_process['start_time'] = $_POST['date'];
		$agents_processes->add($add_agent_process);
		
		// Get IP ranges for devices 
		$rangeip_select = ' AND (';
		$query = "SELECT * FROM glpi_plugin_tracker_rangeip 
		WHERE FK_tracker_agents_discover='".$ID_agent."'
		AND query='1' ";
		$result=$DB->query($query);
		$exclude = array();
		$or = 0;
		while ($data=$DB->fetch_array($result)) {
			if ($or == "1") {
				$rangeip_select .= " OR ";
         }
			$rangeip_select .= " (inet_aton(ifaddr) BETWEEN inet_aton('".$data['ifaddr_start']."') AND inet_aton('".$data['ifaddr_end']."') ) ";
			$or = 1;
		}
		$rangeip_select .= ') ';
		if ($rangeip_select == " AND () ") {
			$rangeip_select = " AND 1!=1 ";
      }
		// echo $rangeip_select;

		$xml->element[0]['snmp']['element']="";

		// ********************************************************* //
		// ************************* Agent ************************* //
		// ********************************************************* //

		$xml->element[1]['agent']['element']="snmp";
		$xml->element[1]['agent']['SQL']="SELECT * FROM glpi_plugin_tracker_agents
		WHERE ID='".$ID_agent."'";
		$xml->element[1]['agent']['linkfield']['ID'] = 'id';
		$xml->element[1]['agent']['linkfield']['core_discovery'] = 'core_discovery';
		$xml->element[1]['agent']['linkfield']['threads_discovery'] = 'threads_discovery';
		$xml->element[1]['agent']['linkfield']['core_query'] = 'core_query';
		$xml->element[1]['agent']['linkfield']['threads_query'] = 'threads_query';
		$xml->element[1]['agent']['linkfield']['logs'] = 'logs';
		$xml->element[1]['agent']['linkfield']['key'] = 'key';
		$xml->element[1]['agent']['fieldvalue']['PID'] = $start_PID.$number_PID;
		$xml->element[1]['agent']['linkfield']['fragment'] = 'fragment';


		// ********************************************************* //
		// *********************** Discovery *********************** //
		
		$xml->element[1]['discovery']['element']="snmp";

		// Get all range to scan if discovery is ON
		$xml->element[2]['rangeip']['element']="discovery";
		$xml->element[2]['rangeip']['SQL']="SELECT * FROM glpi_plugin_tracker_rangeip 
		WHERE FK_tracker_agents_discover='".$ID_agent."'
			AND discover='1'";
		$xml->element[2]['rangeip']['linkfield']['ID'] = 'id';
		$xml->element[2]['rangeip']['linkfield']['ifaddr_start'] = 'ipstart';
		$xml->element[2]['rangeip']['linkfield']['ifaddr_end'] = 'ipend';
		$xml->element[2]['rangeip']['linkfield']['FK_entities'] = 'entity';
		
		$xml->element[2]['authentification']['element']="discovery";
		$xml->element[2]['authentification']['SQL']="SELECT ".
			"glpi_plugin_tracker_snmp_connection.id as IDC, community, ".
			"glpi_dropdown_plugin_tracker_snmp_version.name as namec,sec_name, ".
         "glpi_dropdown_plugin_tracker_snmp_auth_sec_level.name AS sec_level, ".
			"glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol.name as auth_protocol, ".
         "auth_passphrase, ".
         "glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol.name as priv_protocol, ".
         "priv_passphrase ".
			"FROM glpi_plugin_tracker_snmp_connection ".
		"LEFT JOIN glpi_dropdown_plugin_tracker_snmp_version ON FK_snmp_version=glpi_dropdown_plugin_tracker_snmp_version.ID ".
      "LEFT JOIN glpi_dropdown_plugin_tracker_snmp_auth_sec_level ON sec_level=glpi_dropdown_plugin_tracker_snmp_auth_sec_level.ID ".
      "LEFT JOIN glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol ON auth_protocol=glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol.ID ".
      "LEFT JOIN glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol ON priv_protocol=glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol.ID ".
		"ORDER BY glpi_dropdown_plugin_tracker_snmp_version.ID DESC";
		$xml->element[2]['authentification']['linkfield']['IDC'] = 'id';
		$xml->element[2]['authentification']['linkfield']['community'] = 'community';
		$xml->element[2]['authentification']['linkfield']['namec'] = 'version';
		$xml->element[2]['authentification']['linkfield']['sec_name'] = 'sec_name';
		$xml->element[2]['authentification']['linkfield']['sec_level'] = 'sec_level';
		$xml->element[2]['authentification']['linkfield']['auth_protocol'] = 'auth_protocol';
		$xml->element[2]['authentification']['linkfield']['auth_passphrase'] = 'auth_passphrase';
		$xml->element[2]['authentification']['linkfield']['priv_protocol'] = 'priv_protocol';
		$xml->element[2]['authentification']['linkfield']['priv_passphrase'] = 'priv_passphrase';
		
		// ********************************************************* //
		// ******************** Devices queries ******************** //
		
		$devices[] = "device_networking";
		$devices[] = "device_printer";
		$writed = array();
		$writed[1] = '';

		// List and add in value for query ID of SNMP auth from XML file
		if ($config->getValue("authsnmp") == "file") {
			$snmp_auth = new PluginTrackerSNMPAuth;
			$array_auth = $snmp_auth->plugin_tracker_snmp_connections("1");
			$Auth_id_valid = "(";
			foreach ($array_auth AS $num=>$value) {
				$Auth_id_valid .= $array_auth[$num]['IDC'].",";
			}
			$Auth_id_valid .= ")";
			$Auth_id_valid = str_replace(",)", ")",$Auth_id_valid);
		}

		for ($i=0 ; $i < count($devices) ; $i++) {
			$xml_writed = new PluginTrackerXML;
			$xml_writed->element[1][$devices[$i]]['element']="snmp";
			if ($devices[$i] == "device_networking") {
				if ($config->getValue("authsnmp") == "file") {
					$xml_writed->element[1][$devices[$i]]['SQL']="SELECT * FROM glpi_plugin_tracker_networking
					LEFT JOIN glpi_networking ON glpi_networking.ID = FK_networking
					LEFT JOIN glpi_plugin_tracker_model_infos ON FK_model_infos = glpi_plugin_tracker_model_infos.ID
					WHERE FK_model_infos != '0'
						AND glpi_networking.deleted='0'
						AND FK_snmp_connection IN ".$Auth_id_valid."
						AND state='".$config_snmp_networking->getValue('active_device_state')."'
						AND glpi_plugin_tracker_model_infos.ID>0
						".$rangeip_select." ";
				} else {
					$xml_writed->element[1][$devices[$i]]['SQL']="SELECT * FROM glpi_plugin_tracker_networking
					LEFT JOIN glpi_networking ON glpi_networking.ID = FK_networking
					LEFT JOIN glpi_plugin_tracker_model_infos ON FK_model_infos = glpi_plugin_tracker_model_infos.ID
					LEFT JOIN glpi_plugin_tracker_snmp_connection ON FK_snmp_connection = glpi_plugin_tracker_snmp_connection.ID
					WHERE FK_model_infos != '0'
						AND glpi_networking.deleted='0'
						AND FK_snmp_connection != '0'
						AND state='".$config_snmp_networking->getValue('active_device_state')."'
						AND glpi_plugin_tracker_model_infos.ID>0
						AND glpi_plugin_tracker_snmp_connection.ID>0
						".$rangeip_select." ";
				}
			} else if ($devices[$i] == "device_printer") {
				if ($config->getValue("authsnmp") == "file") {
					$xml_writed->element[1][$devices[$i]]['SQL']="SELECT DISTINCT ifaddr,FK_printers FROM glpi_networking_ports
					LEFT JOIN glpi_plugin_tracker_printers ON on_device = FK_printers
					LEFT JOIN glpi_printers ON on_device = glpi_printers.ID
					LEFT JOIN glpi_plugin_tracker_model_infos ON glpi_plugin_tracker_printers.FK_model_infos = glpi_plugin_tracker_model_infos.ID
					WHERE glpi_networking_ports.device_type='".PRINTER_TYPE."'
						AND glpi_printers.deleted='0'
						AND FK_model_infos != '0'
						AND FK_snmp_connection IN ".$Auth_id_valid."
						AND state='".$config_snmp_printer->getValue('active_device_state')."'
						AND glpi_plugin_tracker_model_infos.ID>0
						".$rangeip_select."
						AND FK_printers!=0";
				} else {
					$xml_writed->element[1][$devices[$i]]['SQL']="SELECT DISTINCT ifaddr,FK_printers FROM glpi_networking_ports
					LEFT JOIN glpi_plugin_tracker_printers ON on_device = FK_printers
					LEFT JOIN glpi_printers ON on_device = glpi_printers.ID
					LEFT JOIN glpi_plugin_tracker_model_infos ON glpi_plugin_tracker_printers.FK_model_infos = glpi_plugin_tracker_model_infos.ID
					LEFT JOIN glpi_plugin_tracker_snmp_connection ON glpi_plugin_tracker_printers.FK_snmp_connection = glpi_plugin_tracker_snmp_connection.ID
					WHERE glpi_networking_ports.device_type='".PRINTER_TYPE."'
						AND glpi_printers.deleted='0'
						AND FK_model_infos != '0'
						AND FK_snmp_connection != '0'
						AND state='".$config_snmp_printer->getValue('active_device_state')."'
						AND glpi_plugin_tracker_model_infos.ID>0
						AND glpi_plugin_tracker_snmp_connection.ID>0
						".$rangeip_select."
						AND FK_printers!=0";

				}
			}
			// Informations
			$xml_writed->element[2]['infos']['element']=$devices[$i];
			if ($devices[$i] == "device_networking") {
				$xml_writed->element[2]['infos']['SQL']="SELECT * FROM glpi_networking
				WHERE ID='[FK_networking]'";
			} else if ($devices[$i] == "device_printer") {
				$xml_writed->element[2]['infos']['SQL']="SELECT * FROM glpi_printers
				LEFT JOIN glpi_plugin_tracker_printers ON glpi_printers.ID = FK_printers
				LEFT JOIN glpi_networking_ports ON on_device = FK_printers
				WHERE glpi_printers.ID='[FK_printers]'
					AND device_type='".PRINTER_TYPE."'
					AND ifaddr!=''
					AND ifaddr!='127.0.0.1'
				LIMIT 0,1";
			}
			if ($devices[$i] == "device_networking") {
				$xml_writed->element[2]['infos']['linkfield']['ID'] = 'id';
         } else if ($devices[$i] == "device_printer") {
				$xml_writed->element[2]['infos']['linkfield']['FK_printers'] = 'id';
         }
			$xml_writed->element[2]['infos']['linkfield']['ifaddr'] = 'ip';
			$xml_writed->element[2]['infos']['linkfield']['FK_entities'] = 'entity';
			if ($devices[$i] == "device_networking") {
				$xml_writed->element[2]['infos']['fieldvalue']['type'] = NETWORKING_TYPE;
         } else if ($devices[$i] == "device_printer") {
				$xml_writed->element[2]['infos']['fieldvalue']['type'] = PRINTER_TYPE;
         }
			// Authentification
			$xml_writed->element[2]['auth']['element']=$devices[$i];
			if ($devices[$i] == "device_networking") {
				if ($config->getValue("authsnmp") == "file") {
					$xml_writed->element[2]['auth']['SQL']="SELECT FK_snmp_connection FROM glpi_plugin_tracker_networking ".
						"WHERE FK_networking='[FK_networking]'";
				} else {
					$xml_writed->element[2]['auth']['SQL']="SELECT
                  community,
                  glpi_dropdown_plugin_tracker_snmp_version.name as name,
                  sec_name,
                  glpi_dropdown_plugin_tracker_snmp_auth_sec_level.name AS sec_level,
                  glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol.name as auth_protocol,
                  auth_passphrase,
                  glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol.name as priv_protocol,
                  priv_passphrase
                  FROM glpi_plugin_tracker_networking
					LEFT JOIN glpi_plugin_tracker_snmp_connection ON FK_snmp_connection=glpi_plugin_tracker_snmp_connection.ID
					LEFT JOIN glpi_dropdown_plugin_tracker_snmp_version ON FK_snmp_version=glpi_dropdown_plugin_tracker_snmp_version.ID
               LEFT JOIN glpi_dropdown_plugin_tracker_snmp_auth_sec_level ON sec_level=glpi_dropdown_plugin_tracker_snmp_auth_sec_level.ID
               LEFT JOIN glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol ON auth_protocol=glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol.ID
               LEFT JOIN glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol ON priv_protocol=glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol.ID
					WHERE FK_networking='[FK_networking]'";
				}
			} else if ($devices[$i] == "device_printer") {
				if ($config->getValue("authsnmp") == "file") {
					$xml_writed->element[2]['auth']['SQL']="SELECT FK_snmp_connection FROM glpi_plugin_tracker_printers ".
						"WHERE FK_printers='[FK_printers]'";
				} else {
					$xml_writed->element[2]['auth']['SQL']="SELECT * FROM glpi_plugin_tracker_printers
					LEFT JOIN glpi_plugin_tracker_snmp_connection ON FK_snmp_connection=glpi_plugin_tracker_snmp_connection.ID
					LEFT JOIN glpi_dropdown_plugin_tracker_snmp_version ON FK_snmp_version=glpi_dropdown_plugin_tracker_snmp_version.ID
					WHERE FK_printers='[FK_printers]'";
				}
			}
			$xml_writed->element[2]['auth']['linkfield']['community'] = 'community';
			$xml_writed->element[2]['auth']['linkfield']['name'] = 'version';
			$xml_writed->element[2]['auth']['linkfield']['sec_name'] = 'sec_name';
			$xml_writed->element[2]['auth']['linkfield']['sec_level'] = 'sec_level';
			$xml_writed->element[2]['auth']['linkfield']['auth_protocol'] = 'auth_protocol';
			$xml_writed->element[2]['auth']['linkfield']['auth_passphrase'] = 'auth_passphrase';
			$xml_writed->element[2]['auth']['linkfield']['priv_protocol'] = 'priv_protocol';
			$xml_writed->element[2]['auth']['linkfield']['priv_passphrase'] = 'priv_passphrase';	
		
			// SNMPGet 
			$xml_writed->element[2]['get']['element']=$devices[$i];
			if ($devices[$i] == "device_networking") {
				$xml_writed->element[2]['get']['SQL']="SELECT glpi_dropdown_plugin_tracker_mib_object.name AS object,
					glpi_dropdown_plugin_tracker_mib_oid.name AS oid,
					glpi_plugin_tracker_mib_networking.vlan AS vlan FROM glpi_plugin_tracker_networking
				LEFT JOIN glpi_plugin_tracker_mib_networking ON glpi_plugin_tracker_mib_networking.FK_model_infos=glpi_plugin_tracker_networking.FK_model_infos
				LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid ON glpi_dropdown_plugin_tracker_mib_oid.ID=FK_mib_oid
			 	LEFT JOIN glpi_dropdown_plugin_tracker_mib_object ON glpi_dropdown_plugin_tracker_mib_object.ID=FK_mib_object
				WHERE FK_networking='[FK_networking]'
					AND oid_port_dyn=0
					AND glpi_plugin_tracker_mib_networking.activation=1";
			} else if ($devices[$i] == "device_printer") {
				$xml_writed->element[2]['get']['SQL']="SELECT glpi_dropdown_plugin_tracker_mib_object.name AS object,
					glpi_dropdown_plugin_tracker_mib_oid.name AS oid,
					glpi_plugin_tracker_mib_networking.vlan AS vlan FROM glpi_plugin_tracker_printers
				LEFT JOIN glpi_plugin_tracker_mib_networking ON glpi_plugin_tracker_mib_networking.FK_model_infos=glpi_plugin_tracker_printers.FK_model_infos
				LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid ON glpi_dropdown_plugin_tracker_mib_oid.ID=FK_mib_oid
			 	LEFT JOIN glpi_dropdown_plugin_tracker_mib_object ON glpi_dropdown_plugin_tracker_mib_object.ID=FK_mib_object
				WHERE FK_printers='[FK_printers]'
					AND oid_port_dyn=0
					AND glpi_plugin_tracker_mib_networking.activation=1";
			}
			$xml_writed->element[2]['get']['linkfield']['object'] = 'object';
			$xml_writed->element[2]['get']['linkfield']['oid'] = 'oid';
			$xml_writed->element[2]['get']['linkfield']['vlan'] = 'vlan';
		
			// SNMPWalk
			$xml_writed->element[2]['walk']['element']=$devices[$i];
			if ($devices[$i] == "device_networking") {
				$xml_writed->element[2]['walk']['SQL']="SELECT glpi_dropdown_plugin_tracker_mib_object.name AS object,
					glpi_dropdown_plugin_tracker_mib_oid.name AS oid,
					glpi_plugin_tracker_mib_networking.vlan AS vlan FROM glpi_plugin_tracker_networking
				LEFT JOIN glpi_plugin_tracker_mib_networking ON glpi_plugin_tracker_mib_networking.FK_model_infos=glpi_plugin_tracker_networking.FK_model_infos
				LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid ON glpi_dropdown_plugin_tracker_mib_oid.ID=FK_mib_oid
			 	LEFT JOIN glpi_dropdown_plugin_tracker_mib_object ON glpi_dropdown_plugin_tracker_mib_object.ID=FK_mib_object
				WHERE FK_networking='[FK_networking]'
					AND oid_port_dyn=1
					AND activation=1";
			} else if ($devices[$i] == "device_printer") {
				$xml_writed->element[2]['walk']['SQL']="SELECT glpi_dropdown_plugin_tracker_mib_object.name AS object,
					glpi_dropdown_plugin_tracker_mib_oid.name AS oid,
					glpi_plugin_tracker_mib_networking.vlan AS vlan FROM glpi_plugin_tracker_printers
				LEFT JOIN glpi_plugin_tracker_mib_networking ON glpi_plugin_tracker_mib_networking.FK_model_infos=glpi_plugin_tracker_printers.FK_model_infos
				LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid ON glpi_dropdown_plugin_tracker_mib_oid.ID=FK_mib_oid
			 	LEFT JOIN glpi_dropdown_plugin_tracker_mib_object ON glpi_dropdown_plugin_tracker_mib_object.ID=FK_mib_object
				WHERE FK_printers='[FK_printers]'
					AND oid_port_dyn=1
					AND activation=1";
			}
			$xml_writed->element[2]['walk']['linkfield']['object'] = 'object';
			$xml_writed->element[2]['walk']['linkfield']['oid'] = 'oid';
			$xml_writed->element[2]['walk']['linkfield']['vlan'] = 'vlan';
			
			$writed[1] .= $xml_writed->writelement(1,'snmp');
			unset($xml_writed);
		}


//		echo $xml->DoXML();
		$data = $xml->DoXML($writed);
		$gzdata = gzencode($data, 9);
		echo $gzdata;
	} else {
		echo "Not allowed !";
	}
}

?>