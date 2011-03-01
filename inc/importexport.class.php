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

      $PluginFusioninventoryMapping = new PluginFusioninventoryMapping();

		$xml = simplexml_load_file($file,'SimpleXMLElement', LIBXML_NOCDATA);

		// Verify same model exist
		$query = "SELECT id
                FROM `glpi_plugin_fusinvsnmp_models`
                WHERE `name`='".(string)$xml->name."';";
		$result = $DB->query($query);

		if ($DB->numrows($result) > 0) {
			if ($message == '1') {
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusinvsnmp']['model_info'][8];
         }
			return false;
		} else {
         $type = (string)$xml->type;
         switch ($type) {

            case '1':
               $type = "Computer";
               break;

            case '2':
               $type = "NetworkEquipment";
               break;

            case '3':
               $type = "Printer";
               break;

         }

			$query = "INSERT INTO `glpi_plugin_fusinvsnmp_models`
                               (`name`,`itemtype`,`discovery_key`,`comment`)
                   VALUES('".(string)$xml->name."','".$type."','".(string)$xml->key."','".(string)$xml->comments."');";
			$DB->query($query);
			$plugin_fusinvsnmp_models_id = $DB->insert_id();

			$i = -1;
			foreach($xml->oidlist->oidobject as $child) {
            unset($plugin_fusinvsnmp_mibobjects_id);
            unset($plugin_fusinvsnmp_miboids_id);
            unset($oid_port_counter);
            unset($oid_port_dyn);
            unset($mapping_type);
            unset($mapping_name);
            unset($vlan);
            unset($is_active);
            $mappings_id = 0;

            if (isset($child->object)) {
               $plugin_fusinvsnmp_mibobjects_id = Dropdown::importExternal(
                                         "PluginFusinvsnmpMibObject",$child->object);
            }
            if (isset($child->oid)) {
               $plugin_fusinvsnmp_miboids_id = Dropdown::importExternal(
                                      "PluginFusinvsnmpMibOid",$child->oid);
            }
            if (isset($child->portcounter)) {
               $oid_port_counter = $child->portcounter;
            }
            if (isset($child->dynamicport)) {
               $oid_port_dyn = $child->dynamicport;
            }
            if (isset($child->mapping_type)) {
               switch($child->mapping_type) {

                  case '1':
                     $mapping_type = 'Computer';
                     break;

                  case '2':
                     $mapping_type = 'NetworkEquipment';
                     break;

                  case '3':
                     $mapping_type = 'Printer';
                     break;

               }
            }
            if (isset($child->mapping_name)) {
               $mapping_name = $child->mapping_name;
            }
            if (isset($child->vlan)) {
               $vlan = $child->vlan;
            }
            if (isset($child->activation)) {
               $is_active = $child->activation;
            }
            if (isset($mapping_type) AND isset($mapping_name)) {
               $a_mappings = $PluginFusioninventoryMapping->get($mapping_type, $mapping_name);
               $mappings_id = $a_mappings['id'];
            }
            if (!isset($mappings_id) OR empty($mappings_id)) {
               $mappings_id = '0';
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
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_fusinvsnmp']['model_info'][9].
               " : <a href='model.form.php?id=".$plugin_fusinvsnmp_models_id."'>".(string)$xml->name."</a>";
         }
		}
	}



   function importMass() {
      ini_set("max_execution_time", "0");
      foreach (glob(GLPI_ROOT.'/plugins/fusinvsnmp/models/*.xml') as $file) $this->import($file,0,1);
   }



	function import_netdiscovery($p_xml, $agentKey) {
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
         $agent['last_contact'] = date("Y-m-d H:i:s");
         $pta->update($agent);
      }
      $_SESSION['glpi_plugin_fusioninventory_agentid'] = $agent['id'];

		$count_discovery_devices = 0;
   	foreach($p_xml->DEVICE as $discovery) {
			$count_discovery_devices++;
  		}
      if ($count_discovery_devices != "0") {
         $ptap->updateState($_SESSION['glpi_plugin_fusioninventory_processnumber'], array('nb_found' => $count_discovery_devices), $agent['id']);
         foreach($p_xml->DEVICE as $discovery) {

            $PluginFusinvsnmpCommunicationNetDiscovery = new PluginFusinvsnmpCommunicationNetDiscovery();
            $PluginFusinvsnmpCommunicationNetDiscovery->sendCriteria($discovery);

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
