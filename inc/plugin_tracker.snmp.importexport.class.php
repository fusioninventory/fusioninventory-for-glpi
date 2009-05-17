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

if (!defined('GLPI_ROOT'))
	die("Sorry. You can't access directly to this file");


class plugin_tracker_importexport extends CommonDBTM
{

	function plugin_tracker_export($ID_model)
	{
		global $DB;
		
		plugin_tracker_checkRight("snmp_models","r");
		
		$query = "SELECT * 
					
		FROM glpi_plugin_tracker_model_infos
		
		WHERE ID='".$ID_model."' ";

		if ( $result=$DB->query($query) )
		{
			if ( $DB->numrows($result) != 0 )
			{
				$model_name = $DB->result($result, 0, "name");
				$type = $DB->result($result, 0, "device_type");
				$discovery_key = $DB->result($result, 0, "discovery_key");
			}
			else
				exit();
		}
		
		
		
		// Construction of XML file
		$xml = "<model>\n";
		$xml .= "	<name><![CDATA[".$model_name."]]></name>\n";
		$xml .= "	<type>".$type."</type>\n";
		$xml .= "	<key>".$discovery_key."</key>\n";
		$xml .= "	<oidlist>\n";

		$query = "SELECT * 
					
		FROM glpi_plugin_tracker_mib_networking AS model_t

		WHERE FK_model_infos='".$ID_model."' ";
		
		if ( $result=$DB->query($query) )
		{
			while ( $data=$DB->fetch_array($result) )
			{
				$xml .= "		<oidobject>\n";
				$xml .= "			<object><![CDATA[".getDropdownName("glpi_dropdown_plugin_tracker_mib_object",$data["FK_mib_object"])."]]></object>\n";		
				$xml .= "			<oid><![CDATA[".getDropdownName("glpi_dropdown_plugin_tracker_mib_oid",$data["FK_mib_oid"])."]]></oid>\n";		
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
	
	
	
	function showForm($target)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG;
		
		plugin_tracker_checkRight("snmp_models","r");
		
		echo "<form action='".$target."?add=1' method='post' enctype='multipart/form-data'>";
		
		echo "<br>";
		echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th colspan='2'>";
		echo $LANG['plugin_tracker']["model_info"][10]." :</th></tr>";
		
		echo "	<tr class='tab_bg_1'>";
		echo "		<td align='center'>";
//		echo "<a href='http://glpi-project.org/wiki/doku.php?id=wiki:".substr($_SESSION["glpilanguage"],0,2).":plugins:tracker:models' target='_blank'>".$LANG['plugin_tracker']["profile"][19]."&nbsp;</a>";
		echo "</td>";
		echo "		<td align='center'>";
		echo "			<input type='file' name='importfile' value=''/>";
		echo "			<input type='submit' value='".$LANG["buttons"][37]."' class='submit'/>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		
		echo "</form>";
		
	}



	function import($file,$message=1,$installation=0)
	{
		global $DB,$LANG;

		if ($installation != 1)
			plugin_tracker_checkRight("snmp_models","w");

		$xml = simplexml_load_file($file);

		// Verify same model exist
		$query = "SELECT ID ".
				 "FROM glpi_plugin_tracker_model_infos ".
				 "WHERE name='".$xml->name[0]."';";
		$result = $DB->query($query);
		
		if ($DB->numrows($result) > 0)
		{
			if ($message == '1')
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_tracker']["model_info"][8];
			return false;
		}
		else
		{
			$query = "INSERT INTO glpi_plugin_tracker_model_infos
			(name,device_type,discovery_key)
			VALUES('".$xml->name[0]."','".$xml->type[0]."','".$xml->key[0]."')";
			
			$DB->query($query);
			$FK_model = $DB->insert_id();
			
			$i = -1;
			foreach($xml->oidlist[0] as $num){
				$i++;
				$j = 0;
				foreach($xml->oidlist->oidobject[$i] as $item){
					$j++;
					switch ($j)
					{
						case 1:
							$FK_mib_object = externalImportDropdown("glpi_dropdown_plugin_tracker_mib_object",$item);
							break;
						case 2:
							$FK_mib_oid = externalImportDropdown("glpi_dropdown_plugin_tracker_mib_oid",$item);
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

				$query = "INSERT INTO glpi_plugin_tracker_mib_networking
				(FK_model_infos,FK_mib_oid,FK_mib_object,oid_port_counter,oid_port_dyn,mapping_type,mapping_name,vlan,activation)
				VALUES('".$FK_model."','".$FK_mib_oid."','".$FK_mib_object."','".$oid_port_counter."', '".$oid_port_dyn."',
				 '".$mapping_type."', '".$mapping_name."', '".$vlan."', '".$activation."')";
			
				$DB->query($query);
			}
			if ($message == '1')
				$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANG['plugin_tracker']["model_info"][9]." : <a href='plugin_tracker.models.form.php?ID=".$FK_model."'>".$xml->name[0]."</a>";
		}
	}



	function import_agent_discovery($content_dir,$file)
	{
		global $DB,$LANG;

		$walks = new plugin_tracker_walk;
		$config_discovery = new plugin_tracker_config_discovery;

		// Recompose File
		$target = $content_dir.$file;
		$pid = str_replace("-discovery.xml", "", $file);
		// Get agent informations in pid-discovery.xml file
			$agent_tmp = "";
			$c_handle = fopen($target, 'r');
			do {
				$content = fread($c_handle,1000000);
				$agent_tmp .= $content;
			}
			while (!empty($content));
			fclose($c_handle);
			$agent_tmp = str_replace("</snmp>\n","",$agent_tmp);
		// End get lines
		$handle = fopen($target, 'w');
		fwrite($handle, $agent_tmp);
		//
		$dir = opendir($content_dir);
		while($file_scan = readdir($dir)) {
			if( strstr($file_scan, $pid."-tmpdiscovery-" ))
			{
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


		// Load config discovery for existence criteria
		$link_ip = $config_discovery->getValue("link_ip");
		$link_name = $config_discovery->getValue("link_name");
		$link_serial = $config_discovery->getValue("link_serial");
		$link2_ip = $config_discovery->getValue("link2_ip");
		$link2_name = $config_discovery->getValue("link2_name");
		$link2_serial = $config_discovery->getValue("link2_serial");

		$walkdata = '';
		$xml = simplexml_load_file($content_dir.$file);
		$count_discovery_devices = 0;
		foreach($xml->discovery as $discovery){
			$count_discovery_devices++;	
		}
		$device_queried_networking = 0;
		$device_queried_printer = 0;

		foreach($xml->device as $device){
			if ($device->infos->type == NETWORKING_TYPE)
				$device_queried_networking++;
			else if ($device->infos->type == PRINTER_TYPE)
				$device_queried_printer++;
		}
		foreach($xml->agent as $agent){
			$agent_version = $agent->version;
			$agent_id = $agent->id;
			$query = "UPDATE glpi_plugin_tracker_agents 
			SET last_agent_update='".$agent->end_date."', tracker_agent_version='".$agent_version."'
			WHERE ID='".$agent_id."'";
			$DB->query($query);
			
			$query = "UPDATE glpi_plugin_tracker_agents_processes 
			SET status='2', 
				start_time_discovery='".$agent->start_time_discovery."', 
				end_time_discovery='".$agent->end_time_discovery."',
				discovery_queries_total='".$agent->discovery_queries_total."',
				discovery_queries='".$count_discovery_devices."'
			WHERE process_number='".$agent->pid."'
				AND FK_agent='".$agent->id."'";
			$DB->query($query);			
		}
		foreach($xml->discovery as $discovery){
			if ($discovery->modelSNMP != "")
			{
				$query = "SELECT * FROM glpi_plugin_tracker_model_infos
				WHERE discovery_key='".$discovery->modelSNMP."'
				LIMIT 0,1";
				$result = $DB->query($query);		
				$data = $DB->fetch_assoc($result);
				$FK_model = $data['ID'];
			}
			else
				$FK_model = 0;

			plugin_tracker_discovery_criteria($discovery,$link_ip,$link_name,$link_serial,$link2_ip,$link2_name,$link2_serial,$agent_id,$FK_model);
		}
	}

	function import_agentonly($content_dir,$file)
	{
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
			if( strstr($file_scan, $xml->agent->pid."-tmp-" ))
			{
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
		foreach($xml_device->device as $device){
			if ($device->infos->type == NETWORKING_TYPE)
				$device_queried_networking++;
			else if ($device->infos->type == PRINTER_TYPE)
				$device_queried_printer++;
		}
		foreach($xml->agent as $agent){
			$agent_version = $agent->version;
			$agent_id = $agent->id;
			$query = "UPDATE glpi_plugin_tracker_agents
			SET last_agent_update='".$agent->end_date."', tracker_agent_version='".$agent_version."'
			WHERE ID='".$agent_id."'";
			$DB->query($query);
 	            
			$query = "UPDATE glpi_plugin_tracker_agents_processes
			SET end_time='".$agent->end_date."',
				status='3',
				networking_queries='".$device_queried_networking."',
				printers_queries='".$device_queried_printer."',
				start_time_query='".$agent->start_time_query."',
				end_time_query='".$agent->end_time_query."'
			WHERE process_number='".$agent->pid."'
				AND FK_agent='".$agent->id."'";
			$DB->query($query);           
		}		
	}

}

?>