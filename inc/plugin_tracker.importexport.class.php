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
		
		$query = "SELECT * 
					
		FROM glpi_plugin_tracker_model_infos
		
		WHERE ID='".$ID_model."' ";

		if ( $result=$DB->query($query) )
		{
			if ( $DB->numrows($result) != 0 )
			{
				$model_name = mysql_result($result, 0, "name");
				$model_FK_model_networking = mysql_result($result, 0, "FK_model_networking");
				$model_FK_firmware = mysql_result($result, 0, "FK_firmware");
				$model_FK_snmp_version = mysql_result($result, 0, "FK_snmp_version");
				$model_FK_snmp_connection = mysql_result($result, 0, "FK_snmp_connection");
			
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
		$xml .= "	<snmpversion><![CDATA[".getDropdownName("glpi_dropdown_plugin_tracker_snmp_version",$model_FK_snmp_version)."]]></snmpversion>\n";
		$xml .= "	<authsnmp><![CDATA[".getDropdownName("glpi_plugin_tracker_snmp_connection",$model_FK_snmp_connection)."]]></authsnmp>\n";
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
				$xml .= "			<portcounter><![CDATA[".$data["oid_port_counter"]."]]></portcounter>\n";
				$xml .= "			<dynamicport><![CDATA[".$data["oid_port_dyn"]."]]></dynamicport>\n";
				$xml .= "			<linkfield><![CDATA[".getDropdownName("glpi_plugin_tracker_links_oid_fields",$data["FK_links_oid_fields"])."]]></linkfield>\n";
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
		global $DB;
	
		$xml = simplexml_load_file($_FILES['importfile']['tmp_name']);	
	
		// $xml = simplexml_load_file("http://127.0.0.1/export.xml");
   	
		echo $xml->name[0]."<br/>";
		
		echo $xml->networkingmodel[0]."<br/>";
//			$FK_model_networking = externalImportDropdown("glpi_dropdown_model_networking",$xml->networkingmodel[0],0);
		echo $xml->firmware[0]."<br/>";
//			$FK_firmware = externalImportDropdown("glpi_dropdown_firmware",$xml->firmware[0],0);
		echo $xml->snmpversion[0]."<br/>";
		echo $xml->authsnmp[0]."<br/>";
		
		$i = -1;
		foreach($xml->oidlist[0] as $num){
			$i++;
			echo "=====================<br/>";
			$j = 0;
			foreach($xml->oidlist->oidobject[$i] as $item){
				$j++;
				switch ($j)
				{
					case 1:
//						$FK_mib_object = externalImportDropdown("glpi_dropdown_plugin_tracker_mib_object",$item);
						break;
					case 2:
//						$FK_mib_oid = externalImportDropdown("glpi_dropdown_plugin_tracker_mib_oid",$item);
						break;
				}
			   echo $item."<br/>";
			}
		}
		$_SESSION["MESSAGE_AFTER_REDIRECT"] = "Import effectué avec succès : <a href='plugin_tracker.models.form.php?ID=1'>$xml->name[0]</a>";
		displayMessageAfterRedirect();
	}

}

?>