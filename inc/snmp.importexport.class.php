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

class PluginFusionInventoryImportExport extends CommonDBTM {

	function plugin_fusioninventory_export($ID_model) {
		global $DB;
		
		plugin_fusioninventory_checkRight("snmp_models","r");
		$query = "SELECT * 
                FROM `glpi_plugin_fusioninventory_model_infos`
                WHERE `ID`='".$ID_model."';";

		if ($result=$DB->query($query)) {
			if ($DB->numrows($result) != 0) {
				$model_name = $DB->result($result, 0, "name");
				$type = $DB->result($result, 0, "device_type");
				$discovery_key = $DB->result($result, 0, "discovery_key");
            $comments = $DB->result($result, 0, "comments");
			} else {
				exit();
         }
		}	
		
		
		// Construction of XML file
		$xml = "<model>\n";
		$xml .= "	<name><![CDATA[".$model_name."]]></name>\n";
		$xml .= "	<type>".$type."</type>\n";
		$xml .= "	<key>".$discovery_key."</key>\n";
      $xml .= "	<comments><![CDATA[".$comments."]]></comments>\n";
		$xml .= "	<oidlist>\n";

		$query = "SELECT * 
                FROM `glpi_plugin_fusioninventory_mib_networking` AS `model_t`
                WHERE `FK_model_infos`='".$ID_model."';";
		
		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				$xml .= "		<oidobject>\n";
				$xml .= "			<object><![CDATA[".
               getDropdownName("glpi_dropdown_plugin_fusioninventory_mib_object",$data["FK_mib_object"]).
               "]]></object>\n";
				$xml .= "			<oid><![CDATA[".
               getDropdownName("glpi_dropdown_plugin_fusioninventory_mib_oid",$data["FK_mib_oid"])."]]></oid>\n";
				$xml .= "			<portcounter>".$data["oid_port_counter"]."</portcounter>\n";
				$xml .= "			<dynamicport>".$data["oid_port_dyn"]."</dynamicport>\n";
				$xml .= "			<mapping_type>".$data["mapping_type"]."</mapping_type>\n";
				$xml .= " 			<mapping_name><![CDATA[".$data["mapping_name"]."]]></mapping_name>\n";
				$xml .= "			<vlan>".$data["vlan"]."</vlan>\n";
				$xml .= "			<activation>".$data["activation"]."</activation>\n";
				$xml .= "		</oidobject>\n";
			}
		
		}
		
		$xml .= "	</oidlist>\n";
		$xml .= "</model>\n";
		
		return $xml;
	}
	
	
	
	function showForm($target) {
		global $DB,$CFG_GLPI,$LANG;
		
		plugin_fusioninventory_checkRight("snmp_models","r");
		
		echo "<form action='".$target."?add=1' method='post' enctype='multipart/form-data'>";
		
		echo "<br>";
		echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["model_info"][10]." :</th></tr>";
		
		echo "	<tr class='tab_bg_1'>";
		echo "		<td align='center'>";
		echo "</td>";
		echo "<td align='center'>";
		echo "<input type='file' name='importfile' value=''/>";
      if(plugin_fusioninventory_HaveRight("snmp_models","w")) {
         echo "&nbsp;<input type='submit' value='".$LANG["buttons"][37]."' class='submit'/>";
      }
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		
		echo "</form>";		
	}



   function showFormMassImport($target) {
		global $DB,$CFG_GLPI,$LANG;

      plugin_fusioninventory_checkRight("snmp_models","r");

      echo "<form action='".$target."?add=1' method='post' enctype='multipart/form-data'>";

		echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th>";
		echo $LANG['plugin_fusioninventory']["model_info"][15]." :</th></tr>";

		echo "	<tr class='tab_bg_1'>";
		echo "<td align='center'>";
      echo $LANG['plugin_fusioninventory']["model_info"][16]."<br/>";
		echo "<input type='hidden' name='massimport' value='1'/>";
      if(plugin_fusioninventory_HaveRight("snmp_models","w")) {
         echo "&nbsp;<input type='submit' value='".$LANG["buttons"][37]."' class='submit'/>";
      }
		echo "</td>";
		echo "</tr>";
		echo "</table>";

		echo "</form>";
   }



	function import($file,$message=1,$installation=0) {
		global $DB,$LANG;

		if ($installation != 1) {
			plugin_fusioninventory_checkRight("snmp_models","w");
      }
		$xml = simplexml_load_file($file);

		// Verify same model exist
		$query = "SELECT ID
                FROM `glpi_plugin_fusioninventory_model_infos`
                WHERE `name`='".$xml->name[0]."';";
		$result = $DB->query($query);
		
		if ($DB->numrows($result) > 0) {
			if ($message == '1') {
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusioninventory']["model_info"][8];
         }
			return false;
		} else {
			$query = "INSERT INTO `glpi_plugin_fusioninventory_model_infos`
                               (`name`,`device_type`,`discovery_key`,`comments`)
                   VALUES('".$xml->name[0]."','".$xml->type[0]."','".$xml->key[0]."','".$xml->comments[0]."');";
			$DB->query($query);
			$FK_model = $DB->insert_id();
			
			$i = -1;
			foreach($xml->oidlist[0] as $num) {
				$i++;
				$j = 0;
				foreach($xml->oidlist->oidobject[$i] as $item) {
					$j++;
					switch ($j) {
						case 1:
							$FK_mib_object = externalImportDropdown(
                                         "glpi_dropdown_plugin_fusioninventory_mib_object",$item);
							break;

						case 2:
							$FK_mib_oid = externalImportDropdown(
                                      "glpi_dropdown_plugin_fusioninventory_mib_oid",$item);
							break;

						case 3:
							$oid_port_counter = $item;
							break;

						case 4:
							$oid_port_dyn = $item;
							break;

						case 5:
							$mapping_type = $item;
							break;

						case 6:
							$mapping_name = $item;
							break;

						case 7:
							$vlan = $item;
							break;

						case 8:
							$activation = $item;
							break;
					}
				}

				$query = "INSERT INTO `glpi_plugin_fusioninventory_mib_networking`
                                  (`FK_model_infos`,`FK_mib_oid`,`FK_mib_object`,`oid_port_counter`,
                                   `oid_port_dyn`,`mapping_type`,`mapping_name`,`vlan`,`activation`)
                      VALUES('".$FK_model."','".$FK_mib_oid."','".$FK_mib_object."',
                             '".$oid_port_counter."', '".$oid_port_dyn."', '".$mapping_type."',
                             '".$mapping_name."', '".$vlan."', '".$activation."');";
				$DB->query($query);
			}
			if ($message == '1') {
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusioninventory']["model_info"][9].
               " : <a href='plugin_fusioninventory.models.form.php?ID=".$FK_model."'>".$xml->name[0]."</a>";
         }
		}
	}



   function importMass() {
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $this->import($file,0,1);
   }


   
	function import_netdiscovery($p_xml, $agentKey, $moduleversion) {
		global $DB,$LANG;
      $test = '';
      $p_criteria = array();

		$walks            = new PluginFusionInventoryWalk;
      $ptap             = new PluginFusionInventoryAgentsProcesses;
      $pta              = new PluginFusionInventoryAgents;
		$config_discovery = new PluginFusionInventoryConfig;
      $np               = new Netport;
      $ptud             = new PluginFusionInventoryUnknownDevice;

      if (isset($p_xml->AGENT->START)) {
         $ptap->updateProcess($p_xml->PROCESSNUMBER, array('start_time_discovery' => date("Y-m-d H:i:s")));
      } else if (isset($p_xml->AGENT->END)) {
         $ptap->updateProcess($p_xml->PROCESSNUMBER, array('end_time_discovery' => date("Y-m-d H:i:s")));
      } else if (isset($p_xml->AGENT->EXIT)) {
         $ptap->endProcess($p_xml->PROCESSNUMBER, date("Y-m-d H:i:s"));
      } else if (isset($p_xml->AGENT->NBIP)) {
         $ptap->updateProcess($p_xml->PROCESSNUMBER, array('discovery_nb_ip' => $p_xml->AGENT->NBIP));
      }
      if (isset($p_xml->AGENT->AGENTVERSION)) {
         $agent = $pta->InfosByKey($agentKey);
         $agent['fusioninventory_agent_version'] = $p_xml->AGENT->AGENTVERSION;
         $agent['last_agent_update'] = date("Y-m-d H:i:s");
         $pta->update($agent);
      }

		$walkdata = '';
		$count_discovery_devices = 0;
   	foreach($p_xml->DEVICE as $discovery) {
			$count_discovery_devices++;
  		}
      if ($count_discovery_devices != "0") {
         $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'], array('discovery_nb_found' => $count_discovery_devices));
         foreach($p_xml->DEVICE as $discovery) {
            // If module version is 1.0, so try to get right model (discovery file in this agent is too old
            if (($moduleversion == "1.0") AND ($discovery->AUTHSNMP != "")) {
               $pfimi = new PluginFusionInventoryModelInfos;
               $discovery->MODELSNMP = $pfimi->getrightmodel(0, 0, $discovery->DESCRIPTION);
            }
            if ($discovery->MODELSNMP != "") {
               $query = "SELECT *
                         FROM `glpi_plugin_fusioninventory_model_infos`
                         WHERE `discovery_key`='".$discovery->MODELSNMP."'
                         LIMIT 0,1;";
               $result = $DB->query($query);
               $data = $DB->fetch_assoc($result);
               $FK_model = $data['ID'];
            } else {
               $FK_model = 0;
            }
            $discovery->MAC = strtolower($discovery->MAC);

            if (empty($FK_model)) {
               $FK_model = 0;
            }

            unset($p_criteria);
            $p_criteria['ip'] = $discovery->IP;
            if (!empty($discovery->NETBIOSNAME)) {
               $p_criteria['name'] = $discovery->NETBIOSNAME;
            } else if (!empty($discovery->SNMPHOSTNAME)) {
               $p_criteria['name'] = $discovery->SNMPHOSTNAME;
            }
            if ($discovery->SERIAL == 'null') {
               $discovery->SERIAL = "";
            }
            $p_criteria['serial'] = trim($discovery->SERIAL);
            $p_criteria['macaddr'] = $discovery->MAC;

            $discovery_criteria = plugin_fusioninventory_discovery_criteria($p_criteria);
            if (!$discovery_criteria) {
               $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'], array('discovery_nb_import' => '1'));
               // Add in unknown device
               $ptud->getEmpty();
               if (!empty($discovery->NETBIOSNAME)) {
                  $ptud->fields['name'] = $discovery->NETBIOSNAME;
               } else if (!empty($discovery->SNMPHOSTNAME)) {
                  $ptud->fields['name'] = $discovery->SNMPHOSTNAME;
               }
               $ptud->fields['dnsname'] = $discovery->DNSHOSTNAME;
               $ptud->fields['FK_entities'] = $discovery->ENTITY;
               $ptud->fields['serial'] = trim($discovery->SERIAL);
               $ptud->fields['contact'] = $discovery->USERSESSION;
               if (!empty($discovery->WORKGROUP)) {
                  $ptud->fields['domain'] = externalImportDropdown(
                                     "glpi_dropdown_domain",$discovery->WORKGROUP,$discovery->ENTITY);
               }
               $ptud->fields['comments'] = $discovery->DESCRIPTION;
               $ptud->fields['type'] = $discovery->TYPE;
               $ptud->fields['FK_model_infos'] = $FK_model;

               $ptud->fields['FK_snmp_connection'] = $discovery->AUTHSNMP;
               if ($discovery->AUTHSNMP != "") {
                  $ptud->fields['snmp'] = 1;
               }
               $ptud->fields['location'] = 0;
               $ptud->fields['deleted'] = 0;
               if ($ptud->fields['domain'] == '') {
                  $ptud->fields['domain'] = 0;
               }
               if ($ptud->fields['type'] == '') {
                  $ptud->fields['type'] = 0;
               }
               if ($ptud->fields['snmp'] == '') {
                  $ptud->fields['snmp'] = 0;
               }
               if ($ptud->fields['FK_model_infos'] == '') {
                  $ptud->fields['FK_model_infos'] = 0;
               }
               if ($ptud->fields['FK_snmp_connection'] == '') {
                  $ptud->fields['FK_snmp_connection'] = 0;
               }
               if ($ptud->fields['accepted'] == '') {
                  $ptud->fields['accepted'] = 0;
               }
               $explodeprocess = explode("/", $_SESSION['glpi_plugin_fusioninventory_processnumber']);
               $ptud->fields['FK_agent'] = intval($explodeprocess[1]);
               $ptud->fields['hub'] = 0;

               $data = $ptud->fields;
               unset($data['ID']);
               $newID = $ptud->add($data);
               unset($data);
               // Add networking_port
               $port_add["on_device"] = $newID;
               $port_add["device_type"] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
               $port_add["ifaddr"] = $discovery->IP;
               $port_add['ifmac'] = $discovery->MAC;
               $port_add['name'] = $discovery->NETPORTVENDOR;
               $port_ID = $np->add($port_add);
               unset($port_add);
            } else {
               # Update device
               //echo "discovery_criteria :".$discovery_criteria;
               $a_device = explode("||", $discovery_criteria);
               // $a_device[0] == id, $a_device[1] = type
               $ci = new commonitem;
               $ci->getFromDB($a_device[1], $a_device[0]);

               $a_lockable = plugin_fusioninventory_lock_getLockFields($a_device[1], $a_device[0]);
               $data = array();
               $data['ID'] = $ci->getField('ID');
               $data['FK_snmp_connection'] = 0;

               if ($a_device[1] == PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN) {
                  if (!in_array('name', $a_lockable)) {
                     if (!empty($discovery->NETBIOSNAME)) {
                        $data['name'] = $discovery->NETBIOSNAME;
                     } else if (!empty($discovery->SNMPHOSTNAME)) {
                        $data['name'] = $discovery->SNMPHOSTNAME;
                     }
                  }
                  if (!in_array('dnsname', $a_lockable))
                     $data['dnsname'] = $discovery->DNSHOSTNAME;
                  if (!in_array('FK_entities', $a_lockable))
                     $data['FK_entities'] = $discovery->ENTITY;
                  if (!in_array('serial', $a_lockable))
                     $data['serial'] = trim($discovery->SERIAL);
                  if (!in_array('contact', $a_lockable))
                     $data['contact'] = $discovery->USERSESSION;
                  if (!in_array('domain', $a_lockable)) {
                     $data['domain'] = 0;
                     if (!empty($discovery->WORKGROUP)) {
                        $data['domain'] = externalImportDropdown(
                                        "glpi_dropdown_domain",$discovery->WORKGROUP,$discovery->ENTITY);
                     }
                  }
                  if ($discovery->TYPE != "0") {
                     $data['type'] = $discovery->TYPE;
                  }
               } else {
                  if (!in_array('name', $a_lockable)) {
                     if (!empty($discovery->NETBIOSNAME)) {
                        $data['name'] = $discovery->NETBIOSNAME;
                     } else if (!empty($discovery->SNMPHOSTNAME)) {
                        $data['name'] = $discovery->SNMPHOSTNAME;
                     }else if (!empty($discovery->DNSHOSTNAME)) {
                        $data['name'] = $discovery->DNSHOSTNAME;
                     }
                  }
               }

               if (!in_array('comments', $a_lockable))
                  $data['comments'] = $discovery->DESCRIPTION;
               if (!in_array('FK_model_infos', $a_lockable));
                  $data['FK_model_infos'] = $FK_model;
               if (!in_array('FK_snmp_connection', $a_lockable));
                  $data['FK_snmp_connection'] = $discovery->AUTHSNMP;
               if (!in_array('snmp', $a_lockable)) {
                  $data['snmp'] = 0;
                  if ($discovery->AUTHSNMP != "") {
                     $data['snmp'] = 1;
                  }
               }

               $explodeprocess = explode("/", $_SESSION['glpi_plugin_fusioninventory_processnumber']);
               $data['FK_agent'] = intval($explodeprocess[1]);

               if ($a_device[1] == NETWORKING_TYPE) {
                  if (!in_array('ifaddr', $a_lockable))
                     $data["ifaddr"] = $discovery->IP;
                  if (!in_array('ifmac', $a_lockable))
                     $data['ifmac'] = $discovery->MAC;
               } else {
                  // TODO: manage ports
                  $np = new Netport;
                  $query = "SELECT ID FROM glpi_networking_ports
                     WHERE (on_device = '".$a_device[0]."' AND device_type = '".$a_device[1]."')
                        AND `ifaddr` NOT IN ('127.0.0.1')
                     ORDER BY name, logical_number";
                  if ($result = $DB->query($query)) {
                     if ($DB->numrows($result) == 1) {
                        $data2 = $DB->fetch_assoc($result);
                        $np->getFromDB($data2["ID"]);
                        $port = array();
                        $port['ID'] = $data2["ID"];
                        $port["ifaddr"] = $discovery->IP;
                        $port['ifmac'] = $discovery->MAC;
                        $port['name'] = $discovery->NETPORTVENDOR;
                        $np->update($port);
                     } else if ($DB->numrows($result) > 1) {
                        $ptae = new PluginFusionInventoryAgentsErrors;
                        $error_input['ID'] = $a_device[0];
                        $error_input['TYPE'] = $a_device[1];
                        $error_input['MESSAGE'] = 'Unable to determine network port of device to update with values : '.$discovery->IP.'(ip),
                           '.$discovery->MAC.'(mac), '.$discovery->NETPORTVENDOR.'(name)';
                        $error_input['agent_type'] = 'NETDISCOVERY';
                        $ptae->addError($error_input);
                     } else { // noport
                        $port_add = array();
                        $port_add["on_device"] = $a_device[0];
                        $port_add["device_type"] = $a_device[1];
                        $port_add["ifaddr"] = $discovery->IP;
                        $port_add['ifmac'] = $discovery->MAC;
                        $port_add['name'] = $discovery->NETPORTVENDOR;
                        $np->add($port_add);
                     }
                  }
               }

               $ci->obj->update($data);

               $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'], array('discovery_nb_exists' => '1'));
            }
         }
      }
	}



	function import_agentonly($content_dir,$file) {
		global $DB,$LANG;
		
		$xml = simplexml_load_file($content_dir.$file);
		
		$num_files = $xml->agent->num_files;
		
		$file_modif = str_replace(".xml", "-device.xml", $file);
		$target = $content_dir.$file_modif;
		$handle = fopen($target, 'a');
		fwrite($handle, "<snmp>\n");
		//
		$dir = opendir($content_dir);
		while($file_scan = readdir($dir)) {
			if(strstr($file_scan, $xml->agent->pid."-tmp-")) {
				$c_handle = fopen($content_dir.$file_scan, 'r');
				do {
					$content = fread($c_handle,1000000);
					fwrite($handle, $content);
				}
				while (!empty($content));
				fclose($c_handle);
				unlink($content_dir.$file_scan);
			}
		}
		closedir($dir);

		fwrite($handle, "</snmp>\n");
		fclose($handle);

		$xml_device = simplexml_load_file($target);

		$device_queried_networking = 0;
		$device_queried_printer = 0;
		foreach($xml_device->device as $device) {
			if ($device->infos->type == NETWORKING_TYPE) {
				$device_queried_networking++;
         } else if ($device->infos->type == PRINTER_TYPE) {
				$device_queried_printer++;
         }
		}
		foreach($xml->agent as $agent) {
			$agent_version = $agent->version;
			$agent_id = $agent->id;
			$query = "UPDATE `glpi_plugin_fusioninventory_agents`
                   SET `last_agent_update`='".$agent->end_date."',
                       `fusioninventory_agent_version`='".$agent_version."'
                   WHERE `ID`='".$agent_id."';";
			$DB->query($query);
 	            
			$query = "UPDATE `glpi_plugin_fusioninventory_agents_processes`
                   SET `end_time`='".$agent->end_date."',
                       `status`='3',
                       `networking_queries`='".$device_queried_networking."',
                       `printers_queries`='".$device_queried_printer."',
                       `start_time_query`='".$agent->start_time_query."',
                       `end_time_query`='".$agent->end_time_query."'
                   WHERE `process_number`='".$agent->pid."'
                        AND `FK_agent`='".$agent->id."';";
			$DB->query($query);           
		}		
	}

}

?>