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

$NEEDED_ITEMS=array("tracker","search");
include (GLPI_ROOT."/inc/includes.php");

if( isset($_POST['upload']) ) // si formulaire soumis
{
	$content_dir = '/var/tmp/tracker/'; // dossier où sera déplacé le fichier
  
   $tmp_file = $_FILES['data']['tmp_name'];

    if( !is_uploaded_file($tmp_file) )
    {
        exit("Le fichier est introuvable");
    }

    // on vérifie maintenant l'extension
    $type_file = $_FILES['data']['type'];

    // on copie le fichier dans le dossier de destination
    $name_file = $_FILES['data']['name'];

    if( !move_uploaded_file($tmp_file, $content_dir . $name_file) )
    {
        exit("Impossible de copier le fichier dans $content_dir");
    }
    
	$name_file_xml = str_replace(".gz", "", $name_file);
	$string = implode("", gzfile($content_dir . $name_file));
	$fp = fopen($content_dir . $name_file_xml, "w");
	fwrite($fp, $string, strlen($string));
	fclose($fp);

	
	// Open file for put it in DB
	$importexport = new plugin_tracker_importexport;
	$importexport->import_agentfile($content_dir.$name_file_xml);


    echo "Le fichier a bien été uploadé";  

}
else if(isset($_POST['get_data']))
{
	$xml = new plugin_tracker_XML;

$ID_agent = "1";

	$xml->element[0]['snmp']['element']="";
	
	// ** Discovery
	
	$xml->element[1]['discovery']['element']="snmp";

	// Get all range to scan if discovery is ON
	$xml->element[2]['rangeip']['element']="discovery";
	$xml->element[2]['rangeip']['SQL']="SELECT * FROM glpi_plugin_tracker_rangeip 
	WHERE FK_tracker_agents='".$ID_agent."'
		AND discover='1'";
	$xml->element[2]['rangeip']['linkfield']['ID'] = 'id';
	$xml->element[2]['rangeip']['linkfield']['ifaddr_start'] = 'ipstart';
	$xml->element[2]['rangeip']['linkfield']['ifaddr_end'] = 'ipend';
	$xml->element[2]['rangeip']['linkfield']['FK_entities'] = 'entity';
	
	$xml->element[2]['authentification']['element']="discovery";
	$xml->element[2]['authentification']['SQL']="SELECT * FROM glpi_plugin_tracker_snmp_connection
	LEFT JOIN glpi_dropdown_plugin_tracker_snmp_version ON FK_snmp_version=glpi_dropdown_plugin_tracker_snmp_version.ID";
	$xml->element[2]['authentification']['linkfield']['ID'] = 'id';
	$xml->element[2]['authentification']['linkfield']['community'] = 'community';
	$xml->element[2]['authentification']['linkfield']['name'] = 'version';
	$xml->element[2]['authentification']['linkfield']['sec_name'] = 'sec_name';
	$xml->element[2]['authentification']['linkfield']['sec_level'] = 'sec_level';
	$xml->element[2]['authentification']['linkfield']['auth_protocol'] = 'auth_protocol';
	$xml->element[2]['authentification']['linkfield']['auth_passphrase'] = 'auth_passphrase';
	$xml->element[2]['authentification']['linkfield']['priv_protocol'] = 'priv_protocol';
	$xml->element[2]['authentification']['linkfield']['priv_passphrase'] = 'priv_passphrase';
	
	// ** Devices queries
	
	$xml->element[1]['device']['element']="snmp";
	$xml->element[1]['device']['SQL']="SELECT * FROM glpi_plugin_tracker_networking
	LEFT JOIN glpi_networking ON glpi_networking.ID = FK_networking";

	// Informations
	$xml->element[2]['infos']['element']="device";
	$xml->element[2]['infos']['SQL']="SELECT * FROM glpi_networking 
	WHERE ID='[FK_networking]'";
	$xml->element[2]['infos']['linkfield']['ID'] = 'id';
	$xml->element[2]['infos']['linkfield']['ifaddr'] = 'ip';
	$xml->element[2]['infos']['linkfield']['FK_entities'] = 'entity';
	
	// Authentification
	$xml->element[2]['auth']['element']="device";
	$xml->element[2]['auth']['SQL']="SELECT * FROM glpi_plugin_tracker_networking
	LEFT JOIN glpi_plugin_tracker_snmp_connection ON FK_snmp_connection=glpi_plugin_tracker_snmp_connection.ID
	LEFT JOIN glpi_dropdown_plugin_tracker_snmp_version ON FK_snmp_version=glpi_dropdown_plugin_tracker_snmp_version.ID
	WHERE FK_networking='[FK_networking]'";
	$xml->element[2]['auth']['linkfield']['community'] = 'community';
	$xml->element[2]['auth']['linkfield']['name'] = 'version';
	$xml->element[2]['auth']['linkfield']['sec_name'] = 'sec_name';
	$xml->element[2]['auth']['linkfield']['sec_level'] = 'sec_level';
	$xml->element[2]['auth']['linkfield']['auth_protocol'] = 'auth_protocol';
	$xml->element[2]['auth']['linkfield']['auth_passphrase'] = 'auth_passphrase';
	$xml->element[2]['auth']['linkfield']['priv_protocol'] = 'priv_protocol';
	$xml->element[2]['auth']['linkfield']['priv_passphrase'] = 'priv_passphrase';	

	// SNMPGet 
	$xml->element[2]['get']['element']="device";
	$xml->element[2]['get']['SQL']="SELECT glpi_dropdown_plugin_tracker_mib_object.name AS object,
		glpi_dropdown_plugin_tracker_mib_oid.name AS oid FROM glpi_plugin_tracker_networking
	LEFT JOIN glpi_plugin_tracker_mib_networking ON glpi_plugin_tracker_mib_networking.FK_model_infos=glpi_plugin_tracker_networking.FK_model_infos
	LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid ON glpi_dropdown_plugin_tracker_mib_oid.ID=FK_mib_oid
 	LEFT JOIN glpi_dropdown_plugin_tracker_mib_object ON glpi_dropdown_plugin_tracker_mib_object.ID=FK_mib_object
	WHERE FK_networking='[FK_networking]'
		AND oid_port_dyn=0";
	$xml->element[2]['get']['linkfield']['object'] = 'object';
	$xml->element[2]['get']['linkfield']['oid'] = 'oid';

	// SNMPWalk
	$xml->element[2]['walk']['element']="device";
	$xml->element[2]['walk']['SQL']="SELECT glpi_dropdown_plugin_tracker_mib_object.name AS object,
		glpi_dropdown_plugin_tracker_mib_oid.name AS oid FROM glpi_plugin_tracker_networking
	LEFT JOIN glpi_plugin_tracker_mib_networking ON glpi_plugin_tracker_mib_networking.FK_model_infos=glpi_plugin_tracker_networking.FK_model_infos
	LEFT JOIN glpi_dropdown_plugin_tracker_mib_oid ON glpi_dropdown_plugin_tracker_mib_oid.ID=FK_mib_oid
 	LEFT JOIN glpi_dropdown_plugin_tracker_mib_object ON glpi_dropdown_plugin_tracker_mib_object.ID=FK_mib_object
	WHERE FK_networking='[FK_networking]'
		AND oid_port_dyn=1";
	$xml->element[2]['walk']['linkfield']['object'] = 'object';
	$xml->element[2]['walk']['linkfield']['oid'] = 'oid';

//	echo $xml->DoXML();
	$data = $xml->DoXML();
	$gzdata = gzencode($data, 9);
	echo $gzdata;

}

?>