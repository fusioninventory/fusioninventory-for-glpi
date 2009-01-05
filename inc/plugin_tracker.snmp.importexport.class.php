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
// Original Author of file: Nicolas SMOLYNIEC
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT'))
{
	die("Sorry. You can't access directly to this file");
}

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
				$model_FK_model_networking = $DB->result($result, 0, "FK_model_networking");
				$model_FK_firmware = $DB->result($result, 0, "FK_firmware");
			}
			else
			{
				exit();
			}
		}
		
		
		
		// Construction of XML file
		$xml = "<model>\n";
		$xml .= "	<name><![CDATA[".$model_name."]]></name>\n";
		$xml .= "	<networkingmodel><![CDATA[".getDropdownName("glpi_dropdown_model_networking",$model_FK_model_networking)."]]></networkingmodel>\n";
		$xml .= "	<firmware><![CDATA[".getDropdownName("glpi_dropdown_firmware",$model_FK_firmware)."]]></firmware>\n";
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
				$xml .= "		</oidobject>\n";
			}
		
		}
		
		$xml .= "	</oidlist>\n";
		$xml .= "</model>\n";
		
		return $xml;
	}
	
	
	
	function showForm($target)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
		
		plugin_tracker_checkRight("snmp_models","r");
		
		echo "<form action='".$target."?add=1' method='post' enctype='multipart/form-data'>";
		
		echo "<br>";
		echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th colspan='2'>";
		echo "Importation de modele :</th></tr>";
		
		echo "	<tr class='tab_bg_1'>";
		echo "		<td align='center'></td>";
		echo "		<td align='center'>";
		echo "			<input type='file' name='importfile' value=''/>";
		echo "			<input type='submit' value='".$LANG["buttons"][37]."'/>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		
		echo "</form>";
		
	}



	function import($file)
	{
		global $DB,$LANGTRACKER;

		plugin_tracker_checkRight("snmp_models","w");

		$xml = simplexml_load_file($_FILES['importfile']['tmp_name']);	

		// Verify same model exist
		$query = "SELECT ID ".
				 "FROM glpi_plugin_tracker_model_infos ".
				 "WHERE name='".$xml->name[0]."';";
		$result = $DB->query($query);
		
		if ($DB->numrows($result) > 0)
		{
			$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANGTRACKER["model_info"][8];
			return false;
		}
		else
		{

//			echo $xml->name[0]."<br/>";
			
//			echo $xml->networkingmodel[0]."<br/>";
				$FK_model_networking = externalImportDropdown("glpi_dropdown_model_networking",$xml->networkingmodel[0],0,$external_params["manufacturer"]=1);
//			echo $xml->firmware[0]."<br/>";
				$FK_firmware = externalImportDropdown("glpi_dropdown_firmware",$xml->firmware[0],0);
			
			$query = "INSERT INTO glpi_plugin_tracker_model_infos
			(name,FK_model_networking,FK_firmware)
			VALUES('".$xml->name[0]."','".$FK_model_networking."','".$FK_firmware."')";
			
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
					}
				   //echo $item."<br/>";
				}

				$query = "INSERT INTO glpi_plugin_tracker_mib_networking
				(FK_model_infos,FK_mib_oid,FK_mib_object,oid_port_counter,oid_port_dyn,mapping_type,mapping_name)
				VALUES('".$FK_model."','".$FK_mib_oid."','".$FK_mib_object."','".$oid_port_counter."', '".$oid_port_dyn."',
				 '".$mapping_type."', '".$mapping_name."')";
				
				$DB->query($query);
		

			}
			$_SESSION["MESSAGE_AFTER_REDIRECT"] = $LANGTRACKER["model_info"][9]." : <a href='plugin_tracker.models.form.php?ID=".$FK_model."'>".$xml->name[0]."</a>";
		}
	}

}

?>