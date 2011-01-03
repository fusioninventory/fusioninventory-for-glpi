<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
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

//class PluginFusinvsnmpImportExport extends CommonDBTM {
class PluginFusinvsnmpImportExport extends CommonGLPI {

	function export($ID_model) {
		global $DB;
		
		PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","r");
		$query = "SELECT * 
                FROM `glpi_plugin_fusinvsnmp_models`
                WHERE `id`='".$ID_model."';";

		if ($result=$DB->query($query)) {
			if ($DB->numrows($result) != 0) {
				$model_name = $DB->result($result, 0, "name");
				$type = $DB->result($result, 0, "itemtype");
				$discovery_key = $DB->result($result, 0, "discovery_key");
            $comment = $DB->result($result, 0, "comment");
			} else {
				exit();
         }
		}	
			
		// Construction of XML file
		$xml = "<model>\n";
		$xml .= "	<name><![CDATA[".$model_name."]]></name>\n";
		$xml .= "	<type>".$type."</type>\n";
		$xml .= "	<key>".$discovery_key."</key>\n";
      $xml .= "	<comments><![CDATA[".$comment."]]></comments>\n";
		$xml .= "	<oidlist>\n";

      $query = "SELECT `glpi_plugin_fusinvsnmp_modelmibs`.*,
         FROM `glpi_plugin_fusinvsnmp_modelmibs`
         WHERE `plugin_fusinvsnmp_models_id`='".$ID_model."';";

		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				$xml .= "		<oidobject>\n";
				$xml .= "			<object><![CDATA[".
               Dropdown::getDropdownName("glpi_plugin_fusinvsnmp_mibobjects",$data["plugin_fusinvsnmp_mibobjects_id"]).
               "]]></object>\n";
				$xml .= "			<oid><![CDATA[".
               Dropdown::getDropdownName("glpi_plugin_fusinvsnmp_miboids",$data["plugin_fusinvsnmp_miboids_id"])."]]></oid>\n";
				$xml .= "			<portcounter>".$data["oid_port_counter"]."</portcounter>\n";
				$xml .= "			<dynamicport>".$data["oid_port_dyn"]."</dynamicport>\n";
				$xml .= "			<mappings_id>".$data["plugin_fusioninventory_mappings_id"].
                                 "</mappings_id>\n";
				$xml .= "			<vlan>".$data["vlan"]."</vlan>\n";
				$xml .= "			<activation>".$data["is_active"]."</activation>\n";
				$xml .= "		</oidobject>\n";
			}
		}		
		$xml .= "	</oidlist>\n";
		$xml .= "</model>\n";
		
		return $xml;
	}
	
	
	
	function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;
		
		PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model", "r");
		
      $target = GLPI_ROOT.'/plugins/fusinvsnmp/front/model.form.php';
		echo "<form action='".$target."?add=1' method='post' enctype='multipart/form-data'>";

		echo "<br>";
		echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th colspan='2'>";
		echo $LANG['plugin_fusinvsnmp']['model_info'][10]." :</th></tr>";
      
		echo "	<tr class='tab_bg_1'>";
		echo "		<td align='center'>";
		echo "</td>";
		echo "<td align='center'>";
		echo "<input type='file' name='importfile' value=''/>";

//      $this->showFormButtons($options);
//
//      echo "<div id='tabcontent'></div>";
//      echo "<script type='text/javascript'>loadDefaultTab();</script>";
//
//      return true;
      if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
         echo "&nbsp;<input type='submit' value='".$LANG["buttons"][37]."' class='submit'/>";
      }
		echo "</td>";
		echo "</tr>";
		echo "</table>";

		echo "</form>";		
	}



   function showFormMassImport($target) {
		global $DB,$CFG_GLPI,$LANG;

      PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","r");

      echo "<form action='".$target."?add=1' method='post' enctype='multipart/form-data'>";

		echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th>";
		echo $LANG['plugin_fusinvsnmp']['model_info'][15]." :</th></tr>";

		echo "	<tr class='tab_bg_1'>";
		echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']['model_info'][16]."<br/>";
		echo "<input type='hidden' name='massimport' value='1'/>";
      if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
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
			PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
      }
		$xml = simplexml_load_file($file,'SimpleXMLElement', LIBXML_NOCDATA);

		// Verify same model exist
		$query = "SELECT id
                FROM `glpi_plugin_fusinvsnmp_models`
                WHERE `name`='".$xml->name[0]."';";
		$result = $DB->query($query);
		
		if ($DB->numrows($result) > 0) {
			if ($message == '1') {
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusioninventory']['model_info'][8];
         }
			return false;
		} else {
			$query = "INSERT INTO `glpi_plugin_fusinvsnmp_models`
                               (`name`,`itemtype`,`discovery_key`,`comment`)
                   VALUES('".$xml->name[0]."','".$xml->type[0]."','".$xml->key[0]."','".$xml->comments[0]."');";
			$DB->query($query);
			$plugin_fusinvsnmp_models_id = $DB->insert_id();
			
			$i = -1;
			foreach($xml->oidlist[0] as $num) {
				$i++;
				$j = 0;
				foreach($xml->oidlist->oidobject[$i] as $item) {
					$j++;
					switch ($j) {
						case 1:
							$plugin_fusinvsnmp_mibobjects_id = Dropdown::importExternal(
                                         "PluginFusinvsnmpMibObject",$item);
							break;

						case 2:
							$plugin_fusinvsnmp_miboids_id = Dropdown::importExternal(
                                      "PluginFusinvsnmpMibOid",$item);
							break;

						case 3:
							$oid_port_counter = $item;
							break;

						case 4:
							$oid_port_dyn = $item;
							break;

						case 5:
							$mappings_id = $item;
							break;

						case 6:
							$vlan = $item;
							break;

						case 7:
							$is_active = $item;
							break;
					}
				}
				$query = "INSERT INTO `glpi_plugin_fusinvsnmp_modelmibs`
                                  (`plugin_fusinvsnmp_models_id`,`plugin_fusinvsnmp_miboids_id`,`plugin_fusinvsnmp_mibobjects_id`,`oid_port_counter`,
                                   `oid_port_dyn`,`plugin_fusioninventory_mappings_id`,`vlan`,`is_active`)
                      VALUES('".$plugin_fusinvsnmp_models_id."','".$plugin_fusinvsnmp_miboids_id."','".$plugin_fusinvsnmp_mibobjects_id."',
                             '".$oid_port_counter."', '".$oid_port_dyn."', '".$mappings_id."',
                             '".$vlan."', '".$is_active."');";
				$DB->query($query);
			}
			if ($message == '1') {
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusioninventory']['model_info'][9].
               " : <a href='model.form.php?id=".$plugin_fusinvsnmp_models_id."'>".$xml->name[0]."</a>";
         }
		}
	}



   function importMass() {
      foreach (glob(GLPI_ROOT.'/plugins/fusinvsnmp/models/*.xml') as $file) $this->import($file,0,1);
   }


   
	function import_netdiscovery($p_xml, $agentKey, $moduleversion) {
		global $DB,$LANG;
      $test = '';
      $p_criteria = array();

		$walks            = new PluginFusinvsnmpWalk;
      $ptap             = new PluginFusinvsnmpStateDiscovery;
      $pta              = new PluginFusioninventoryAgent;
		$config_discovery = new PluginFusioninventoryConfig;
      $np               = new NetworkPort;
      $ptud             = new PluginFusioninventoryUnknownDevice;

      $agent = $pta->InfosByKey($agentKey);

      if (isset($p_xml->AGENT->START)) {
         $ptap->updateState($p_xml->PROCESSNUMBER, array('start_time' => date("Y-m-d H:i:s")), $agent['id']);
      } else if (isset($p_xml->AGENT->END)) {
         $ptap->updateState($p_xml->PROCESSNUMBER, array('end_time' => date("Y-m-d H:i:s")), $agent['id']);
      } else if (isset($p_xml->AGENT->EXIT)) {
         $ptap->endState($p_xml->PROCESSNUMBER, date("Y-m-d H:i:s"), $agent['id']);
      } else if (isset($p_xml->AGENT->NBIP)) {
         $ptap->updateState($p_xml->PROCESSNUMBER, array('nb_ip' => $p_xml->AGENT->NBIP), $agent['id']);
      }
      if (isset($p_xml->AGENT->AGENTVERSION)) {
         $agent['version'] = $p_xml->AGENT->AGENTVERSION;
         $agent['last_contact'] = date("Y-m-d H:i:s");
         $pta->update($agent);
      }

		$count_discovery_devices = 0;
   	foreach($p_xml->DEVICE as $discovery) {
			$count_discovery_devices++;
  		}
      if ($count_discovery_devices != "0") {
         $ptap->updateState($_SESSION['glpi_plugin_fusioninventory_processnumber'], array('nb_found' => $count_discovery_devices), $agent['id']);
         foreach($p_xml->DEVICE as $discovery) {
            // If module version is 1.0, so try to get right model (discovery file in this agent is too old
//            if (($moduleversion == "1.0") AND ($discovery->AUTHSNMP != "")) {
//               $pfimi = new PluginFusinvsnmpModel;
//               $discovery->MODELSNMP = $pfimi->getrightmodel(0, 0, $discovery->DESCRIPTION);
//            }
//            if ($discovery->MODELSNMP != "") {
//               $query = "SELECT *
//                         FROM `glpi_plugin_fusinvsnmp_models`
//                         WHERE `discovery_key`='".$discovery->MODELSNMP."'
//                         LIMIT 0,1;";
//               $result = $DB->query($query);
//               $data = $DB->fetch_assoc($result);
//				$plugin_fusinvsnmp_models_id = $data['id'];
//            } else {
//				$plugin_fusinvsnmp_models_id = 0;
//            }
//            $discovery->MAC = strtolower($discovery->MAC);
//
//			   if (empty($plugin_fusinvsnmp_models_id)) {
//               $plugin_fusinvsnmp_models_id = 0;
//            }

            $PluginFusinvsnmpCommunicationNetDiscovery = new PluginFusinvsnmpCommunicationNetDiscovery();
            $PluginFusinvsnmpCommunicationNetDiscovery->sendCriteria($discovery);

//            unset($p_criteria);
//            $p_criteria['ip'] = $discovery->IP;
//            if (!empty($discovery->NETBIOSNAME)) {
//               $p_criteria['name'] = $discovery->NETBIOSNAME;
//            } else if (!empty($discovery->SNMPHOSTNAME)) {
//               $p_criteria['name'] = $discovery->SNMPHOSTNAME;
//            }
//            if ($discovery->SERIAL == 'null') {
//               $discovery->SERIAL = "";
//            }
//            $p_criteria['serial'] = trim($discovery->SERIAL);
//            $p_criteria['macaddr'] = $discovery->MAC;
//
//            $discovery_criteria = PluginFusinvsnmpDiscovery::criteria($p_criteria);
//            if (!$discovery_criteria) {
//               $ptap->updateState($_SESSION['glpi_plugin_fusioninventory_processnumber'], array('nb_import' => '1'), $agent['id']);
//               // Add in unknown device
//               $ptud->getEmpty();
//               if (!empty($discovery->NETBIOSNAME)) {
//                  $ptud->fields['name'] = $discovery->NETBIOSNAME;
//               } else if (!empty($discovery->SNMPHOSTNAME)) {
//                  $ptud->fields['name'] = $discovery->SNMPHOSTNAME;
//               }
//               $ptud->fields['dnsname'] = $discovery->DNSHOSTNAME;
//               $ptud->fields['entities_id'] = $discovery->ENTITY;
//               $ptud->fields['serial'] = trim($discovery->SERIAL);
//               $ptud->fields['contact'] = $discovery->USERSESSION;
//               if (!empty($discovery->WORKGROUP)) {
//               $ptud->fields['domain'] = Dropdown::importExternal("Domain",
//                                             $discovery->WORKGROUP,$discovery->ENTITY);
//               }
//               $ptud->fields['comment'] = $discovery->DESCRIPTION;
//               $ptud->fields['type'] = $discovery->TYPE;
//            $ptud->fields['plugin_fusinvsnmp_models_id'] = $plugin_fusinvsnmp_models_id;
//
//               $ptud->fields['plugin_fusinvsnmp_configsecurities_id'] = $discovery->AUTHSNMP;
//               if ($discovery->AUTHSNMP != "") {
//                  $ptud->fields['snmp'] = 1;
//               }
//               $ptud->fields['location'] = 0;
//               $ptud->fields['is_deleted'] = 0;
//               if ($ptud->fields['domain'] == '') {
//                  $ptud->fields['domain'] = 0;
//               }
//               if ($ptud->fields['type'] == '') {
//                  $ptud->fields['type'] = 0;
//               }
//               if ($ptud->fields['snmp'] == '') {
//                  $ptud->fields['snmp'] = 0;
//               }
//            if ($ptud->fields['plugin_fusinvsnmp_models_id'] == '') {
//               $ptud->fields['plugin_fusinvsnmp_models_id'] = 0;
//               }
//               if ($ptud->fields['plugin_fusinvsnmp_configsecurities_id'] == '') {
//                  $ptud->fields['plugin_fusinvsnmp_configsecurities_id'] = 0;
//               }
//               if ($ptud->fields['accepted'] == '') {
//                  $ptud->fields['accepted'] = 0;
//               }
//               $explodeprocess = explode("/", $_SESSION['glpi_plugin_fusioninventory_processnumber']);
//               $ptud->fields['plugin_fusioninventory_agents_id'] = intval($explodeprocess[1]);
//               $ptud->fields['hub'] = 0;
//
//               $data = $ptud->fields;
//               unset($data['id']);
//               $newID = $ptud->add($data);
//               unset($data);
//               // Add networking_port
//               $port_add["items_id"] = $newID;
//               $port_add["itemtype"] = 'PluginFusioninventoryUnknownDevice';
//               $port_add["ip"] = $discovery->IP;
//               $port_add['mac'] = $discovery->MAC;
//               $port_add['name'] = $discovery->NETPORTVENDOR;
//               $port_ID = $np->add($port_add);
//               unset($port_add);
//            } else {
//               # Update device
//               //echo "discovery_criteria :".$discovery_criteria;
//               $a_device = explode("||", $discovery_criteria);
//               // $a_device[0] == id, $a_device[1] = type
//               $ci = new commonitem;
//               $ci->getFromDB($a_device[1], $a_device[0]);
//
//               $a_lockable = PluginFusioninventoryLock::getLockFields($a_device[1], $a_device[0]);
//               $data = array();
//               $data['id'] = $ci->getField('id');
//               $data['plugin_fusinvsnmp_configsecurities_id'] = 0;
//
//               if ($a_device[1] == 'PluginFusioninventoryUnknownDevice') {
//                  if ($ci->getField('name') && !in_array('name', $a_lockable)) {
//                     if (!empty($discovery->NETBIOSNAME)) {
//                        $data['name'] = $discovery->NETBIOSNAME;
//                     } else if (!empty($discovery->SNMPHOSTNAME)) {
//                        $data['name'] = $discovery->SNMPHOSTNAME;
//                     }
//                  }
//                  if ($ci->getField('dnsname') && !in_array('dnsname', $a_lockable))
//                     $data['dnsname'] = $discovery->DNSHOSTNAME;
//                  if ($ci->getField('entities_id') && !in_array('entities_id', $a_lockable))
//                     $data['entities_id'] = $discovery->ENTITY;
//                  if ($ci->getField('serial') && !in_array('serial', $a_lockable))
//                     $data['serial'] = trim($discovery->SERIAL);
//                  if ($ci->getField('contact') && !in_array('contact', $a_lockable))
//                     $data['contact'] = $discovery->USERSESSION;
//                  if ($ci->getField('domain') && !in_array('domain', $a_lockable)) {
//                     $data['domain'] = 0;
//                     if (!empty($discovery->WORKGROUP)) {
//                     $data['domain'] = Dropdown::importExternal("Domain",
//                                             $discovery->WORKGROUP,$discovery->ENTITY);
//                     }
//                  }
//                  if ($discovery->TYPE != "0") {
//                     $data['type'] = $discovery->TYPE;
//                  }
//               } else {
//                  if (!$ci->getField('name') && !in_array('name', $a_lockable)) {
//                     if (!empty($discovery->NETBIOSNAME)) {
//                        $data['name'] = $discovery->NETBIOSNAME;
//                     } else if (!empty($discovery->SNMPHOSTNAME)) {
//                        $data['name'] = $discovery->SNMPHOSTNAME;
//                     }else if (!empty($discovery->DNSHOSTNAME)) {
//                        $data['name'] = $discovery->DNSHOSTNAME;
//                     }
//                  }
//               }
//               if ($ci->getField('comment') && !in_array('comment', $a_lockable))
//                  $data['comment'] = $discovery->DESCRIPTION;
//               if ($ci->getField('plugin_fusinvsnmp_models_id') && !in_array('plugin_fusinvsnmp_models_id', $a_lockable));
//                  $data['plugin_fusinvsnmp_models_id'] = $FK_model;
//               if ($ci->getField('plugin_fusinvsnmp_configsecurities_id') && !in_array('plugin_fusinvsnmp_configsecurities_id', $a_lockable));
//                  $data['plugin_fusinvsnmp_configsecurities_id'] = $discovery->AUTHSNMP;
//               if ($ci->getField('snmp') && !in_array('snmp', $a_lockable)) {
//                  $data['snmp'] = 0;
//                  if ($discovery->AUTHSNMP != "") {
//                     $data['snmp'] = 1;
//                  }
//               }
//
//               $explodeprocess = explode("/", $_SESSION['glpi_plugin_fusioninventory_processnumber']);
//               $data['plugin_fusioninventory_agents_id'] = intval($explodeprocess[1]);
//
//               if ($a_device[1] == NETWORKING_TYPE) {
//                  if (!in_array('ip', $a_lockable))
//                     $data["ip"] = $discovery->IP;
//                  if (!in_array('mac', $a_lockable))
//                     $data['mac'] = $discovery->MAC;
//               } else {
//                  // TODO: manage ports
//                  $np = new NetworkPort;
//                  $query = "SELECT `id` FROM `glpi_networkports`
//                     WHERE (`items_id` = '".$a_device[0]."' AND `itemtype` = '".$a_device[1]."')
//                        AND `ip` NOT IN ('', '127.0.0.1')
//                     ORDER BY `name`, `logical_number`";
//                  if ($result = $DB->query($query)) {
//                     if ($DB->numrows($result) == 1) {
//                        $data2 = $DB->fetch_assoc($result);
//                        $np->getFromDB($data2["id"]);
//                        $port = array();
//                        $port['id'] = $data2["id"];
//                        $port["ip"] = $discovery->IP;
//                        $port['mac'] = $discovery->MAC;
//                        $port['name'] = $discovery->NETPORTVENDOR;
//                        $np->update($port);
//                     } else if ($DB->numrows($result) > 1) {
//                        $ptae = new PluginFusioninventoryAgentProcessError;
//                        $error_input['id'] = $a_device[0];
//                        $error_input['TYPE'] = $a_device[1];
//                        $error_input['MESSAGE'] = 'Unable to determine network port of device to update with values : '.$discovery->IP.'(ip),
//                           '.$discovery->MAC.'(mac), '.$discovery->NETPORTVENDOR.'(name)';
//                        $error_input['agent_type'] = 'NETDISCOVERY';
//                        $ptae->addError($error_input);
//                     }
//                  }
//               }
//
//               $ci->obj->update($data);
//
//               $ptap->updateState($_SESSION['glpi_plugin_fusioninventory_processnumber'], array('nb_exists' => '1'), $agent['id']);
//            }
         }
      }
	}



	function import_agentonly($content_dir,$file) {
		global $DB,$LANG;
		
		$xml = simplexml_load_file($content_dir.$file,'SimpleXMLElement', LIBXML_NOCDATA);
		
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

		$xml_device = simplexml_load_file($target,'SimpleXMLElement', LIBXML_NOCDATA);

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
 	            
			$query = "UPDATE `glpi_plugin_fusioninventory_agentprocesses`
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
