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

class PluginFusioninventoryImportExport extends CommonDBTM {

	function plugin_fusioninventory_export($ID_model) {
		global $DB;
		
		PluginFusioninventoryAuth::checkRight("snmp_models","r");
		$query = "SELECT * 
                FROM `glpi_plugin_fusioninventory_modelinfos`
                WHERE `ID`='".$ID_model."';";

		if ($result=$DB->query($query)) {
			if ($DB->numrows($result) != 0) {
				$model_name = $DB->result($result, 0, "name");
				$type = $DB->result($result, 0, "itemtype");
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
                FROM `glpi_plugin_fusioninventory_mib` AS `model_t`
                WHERE `plugin_fusioninventory_modelinfos_id`='".$ID_model."';";
		
		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				$xml .= "		<oidobject>\n";
				$xml .= "			<object><![CDATA[".
               Dropdown::getDropdownName("glpi_plugin_fusioninventory_mib_object",$data["plugin_fusioninventory_mib_object_id"]).
               "]]></object>\n";
				$xml .= "			<oid><![CDATA[".
               Dropdown::getDropdownName("glpi_plugin_fusioninventory_mib_oid",$data["plugin_fusioninventory_mib_oid_id"])."]]></oid>\n";
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
	
	
	
	function showForm($ID, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;
		
		PluginFusioninventoryAuth::checkRight("snmp_models","r");
		
      $this->showTabs($options);
      $this->showFormHeader($options);
		
		echo "	<tr class='tab_bg_1'>";
		echo "		<td align='center'>";
		echo "</td>";
		echo "<td align='center'>";
		echo "<input type='file' name='importfile' value=''/>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
	}



   function showFormMassImport($target) {
		global $DB,$CFG_GLPI,$LANG;

      PluginFusioninventoryAuth::checkRight("snmp_models","r");

      echo "<form action='".$target."?add=1' method='post' enctype='multipart/form-data'>";

		echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th>";
		echo $LANG['plugin_fusioninventory']["model_info"][15]." :</th></tr>";

		echo "	<tr class='tab_bg_1'>";
		echo "<td align='center'>";
      echo $LANG['plugin_fusioninventory']["model_info"][16]."<br/>";
		echo "<input type='hidden' name='massimport' value='1'/>";
      if(PluginFusioninventory::haveRight("snmp_models","w")) {
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
			PluginFusioninventoryAuth::checkRight("snmp_models","w");
      }
		$xml = simplexml_load_file($file);

		// Verify same model exist
		$query = "SELECT ID
                FROM `glpi_plugin_fusioninventory_modelinfos`
                WHERE `name`='".$xml->name[0]."';";
		$result = $DB->query($query);
		
		if ($DB->numrows($result) > 0) {
			if ($message == '1') {
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusioninventory']["model_info"][8];
         }
			return false;
		} else {
			$query = "INSERT INTO `glpi_plugin_fusioninventory_modelinfos`
                               (`name`,`itemtype`,`discovery_key`,`comments`)
                   VALUES('".$xml->name[0]."','".$xml->type[0]."','".$xml->key[0]."','".$xml->comments[0]."');";
			$DB->query($query);
			$plugin_fusioninventory_modelinfos_id = $DB->insert_id();
			
			$i = -1;
			foreach($xml->oidlist[0] as $num) {
				$i++;
				$j = 0;
				foreach($xml->oidlist->oidobject[$i] as $item) {
					$j++;
					switch ($j) {
						case 1:
							$plugin_fusioninventory_mib_object_id = Dropdown::importExternal(
                                         "PluginFusioninventoryMib_Object",$item);
							break;

						case 2:
							$plugin_fusioninventory_mib_oid_id = Dropdown::importExternal(
                                      "PluginFusioninventoryMib_Oid",$item);
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

				$query = "INSERT INTO `glpi_plugin_fusioninventory_mib`
                                  (`plugin_fusioninventory_modelinfos_id`,`plugin_fusioninventory_mib_oid_id`,`plugin_fusioninventory_mib_object_id`,`oid_port_counter`,
                                   `oid_port_dyn`,`mapping_type`,`mapping_name`,`vlan`,`activation`)
                      VALUES('".$plugin_fusioninventory_modelinfos_id."','".$plugin_fusioninventory_mib_oid_id."','".$plugin_fusioninventory_mib_object_id."',
                             '".$oid_port_counter."', '".$oid_port_dyn."', '".$mapping_type."',
                             '".$mapping_name."', '".$vlan."', '".$activation."');";
				$DB->query($query);
			}
			if ($message == '1') {
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusioninventory']["model_info"][9].
               " : <a href='models.form.php?ID=".$plugin_fusioninventory_modelinfos_id."'>".$xml->name[0]."</a>";
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

		$walks            = new PluginFusioninventoryWalk;
      $ptap             = new PluginFusioninventoryAgentsProcesses;
      $pta              = new PluginFusioninventoryAgents;
		$config_discovery = new PluginFusioninventoryConfig;
      $np               = new Networkport;
      $ptud             = new PluginFusioninventoryUnknownDevice;

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
                         FROM `glpi_plugin_fusioninventory_modelinfos`
                         WHERE `discovery_key`='".$discovery->MODELSNMP."'
                         LIMIT 0,1;";
               $result = $DB->query($query);
               $data = $DB->fetch_assoc($result);
				$plugin_fusioninventory_modelinfos_id = $data['id'];
            } else {
				$plugin_fusioninventory_modelinfos_id = 0;
            }
            $discovery->MAC = strtolower($discovery->MAC);

			if (empty($plugin_fusioninventory_modelinfos_id)) {
				$plugin_fusioninventory_modelinfos_id = 0;
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
               $ptud->fields['entities_id'] = $discovery->ENTITY;
               $ptud->fields['serial'] = trim($discovery->SERIAL);
               $ptud->fields['contact'] = $discovery->USERSESSION;
               if (!empty($discovery->WORKGROUP)) {
               $ptud->fields['domain'] = Dropdown::importExternal("Domain",
                                             $discovery->WORKGROUP,$discovery->ENTITY);
               }
               $ptud->fields['comments'] = $discovery->DESCRIPTION;
               $ptud->fields['type'] = $discovery->TYPE;
            $ptud->fields['plugin_fusioninventory_modelinfos_id'] = $plugin_fusioninventory_modelinfos_id;

               $ptud->fields['plugin_fusioninventory_snmpauths_id'] = $discovery->AUTHSNMP;
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
            if ($ptud->fields['plugin_fusioninventory_modelinfos_id'] == '') {
               $ptud->fields['plugin_fusioninventory_modelinfos_id'] = 0;
               }
               if ($ptud->fields['plugin_fusioninventory_snmpauths_id'] == '') {
                  $ptud->fields['plugin_fusioninventory_snmpauths_id'] = 0;
               }
               if ($ptud->fields['accepted'] == '') {
                  $ptud->fields['accepted'] = 0;
               }
               $explodeprocess = explode("/", $_SESSION['glpi_plugin_fusioninventory_processnumber']);
               $ptud->fields['plugin_fusioninventory_agents_id'] = intval($explodeprocess[1]);
               $ptud->fields['hub'] = 0;

               $data = $ptud->fields;
               unset($data['id']);
               $newID = $ptud->add($data);
               unset($data);
               // Add networking_port
               $port_add["items_id"] = $newID;
               $port_add["itemtype"] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
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

               $a_lockable = PluginFusioninventoryLock::getLockFields($a_device[1], $a_device[0]);
               $data = array();
               $data['id'] = $ci->getField('id');
               $data['plugin_fusioninventory_snmpauths_id'] = 0;

               if ($a_device[1] == PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN) {
                  if ($ci->getField('name') && !in_array('name', $a_lockable)) {
                     if (!empty($discovery->NETBIOSNAME)) {
                        $data['name'] = $discovery->NETBIOSNAME;
                     } else if (!empty($discovery->SNMPHOSTNAME)) {
                        $data['name'] = $discovery->SNMPHOSTNAME;
                     }
                  }
                  if ($ci->getField('dnsname') && !in_array('dnsname', $a_lockable))
                     $data['dnsname'] = $discovery->DNSHOSTNAME;
                  if ($ci->getField('entities_id') && !in_array('entities_id', $a_lockable))
                     $data['entities_id'] = $discovery->ENTITY;
                  if ($ci->getField('serial') && !in_array('serial', $a_lockable))
                     $data['serial'] = trim($discovery->SERIAL);
                  if ($ci->getField('contact') && !in_array('contact', $a_lockable))
                     $data['contact'] = $discovery->USERSESSION;
                  if ($ci->getField('domain') && !in_array('domain', $a_lockable)) {
                     $data['domain'] = 0;
                     if (!empty($discovery->WORKGROUP)) {
                     $data['domain'] = Dropdown::importExternal("Domain",
                                             $discovery->WORKGROUP,$discovery->ENTITY);
                     }
                  }
                  if ($discovery->TYPE != "0") {
                     $data['type'] = $discovery->TYPE;
                  }
               } else {
                  if (!$ci->getField('name') && !in_array('name', $a_lockable)) {
                     if (!empty($discovery->NETBIOSNAME)) {
                        $data['name'] = $discovery->NETBIOSNAME;
                     } else if (!empty($discovery->SNMPHOSTNAME)) {
                        $data['name'] = $discovery->SNMPHOSTNAME;
                     }else if (!empty($discovery->DNSHOSTNAME)) {
                        $data['name'] = $discovery->DNSHOSTNAME;
                     }
                  }
               }
               if ($ci->getField('comments') && !in_array('comments', $a_lockable))
                  $data['comments'] = $discovery->DESCRIPTION;
               if ($ci->getField('plugin_fusioninventory_modelinfos_id') && !in_array('plugin_fusioninventory_modelinfos_id', $a_lockable));
                  $data['plugin_fusioninventory_modelinfos_id'] = $FK_model;
               if ($ci->getField('plugin_fusioninventory_snmpauths_id') && !in_array('plugin_fusioninventory_snmpauths_id', $a_lockable));
                  $data['plugin_fusioninventory_snmpauths_id'] = $discovery->AUTHSNMP;
               if ($ci->getField('snmp') && !in_array('snmp', $a_lockable)) {
                  $data['snmp'] = 0;
                  if ($discovery->AUTHSNMP != "") {
                     $data['snmp'] = 1;
                  }
               }

               $explodeprocess = explode("/", $_SESSION['glpi_plugin_fusioninventory_processnumber']);
               $data['plugin_fusioninventory_agents_id'] = intval($explodeprocess[1]);

               if ($a_device[1] == NETWORKING_TYPE) {
                  if (!in_array('ifaddr', $a_lockable))
                     $data["ifaddr"] = $discovery->IP;
                  if (!in_array('ifmac', $a_lockable))
                     $data['ifmac'] = $discovery->MAC;
               } else {
                  // TODO: manage ports
                  $np = new Networkport;
                  $query = "SELECT `id` FROM `glpi_networkports`
                     WHERE (`items_id` = '".$a_device[0]."' AND `itemtype` = '".$a_device[1]."')
                        AND `ifaddr` NOT IN ('', '127.0.0.1')
                     ORDER BY `name`, `logical_number`";
                  if ($result = $DB->query($query)) {
                     if ($DB->numrows($result) == 1) {
                        $data2 = $DB->fetch_assoc($result);
                        $np->getFromDB($data2["id"]);
                        $port = array();
                        $port['id'] = $data2["id"];
                        $port["ifaddr"] = $discovery->IP;
                        $port['ifmac'] = $discovery->MAC;
                        $port['name'] = $discovery->NETPORTVENDOR;
                        $np->update($port);
                     } else if ($DB->numrows($result) > 1) {
                        $ptae = new PluginFusionInventoryAgentsErrors;
                        $error_input['id'] = $a_device[0];
                        $error_input['TYPE'] = $a_device[1];
                        $error_input['MESSAGE'] = 'Unable to determine network port of device to update with values : '.$discovery->IP.'(ip),
                           '.$discovery->MAC.'(mac), '.$discovery->NETPORTVENDOR.'(name)';
                        $error_input['agent_type'] = 'NETDISCOVERY';
                        $ptae->addError($error_input);
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
                   WHERE `id`='".$agent_id."';";
			$DB->query($query);
 	            
			$query = "UPDATE `glpi_plugin_fusioninventory_agents_processes`
                   SET `end_time`='".$agent->end_date."',
                       `status`='3',
                       `networking_queries`='".$device_queried_networking."',
                       `printers_queries`='".$device_queried_printer."',
                       `start_time_query`='".$agent->start_time_query."',
                       `end_time_query`='".$agent->end_time_query."'
                   WHERE `process_number`='".$agent->pid."'
                        AND `plugin_fusioninventory_agents_id`='".$agent->id."';";
			$DB->query($query);           
		}		
	}

}

?>
