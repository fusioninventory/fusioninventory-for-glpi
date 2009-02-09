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

    echo "Le fichier a bien été uploadé";  

}
else if(isset($_POST['get_data']))
{
	$xml = "<snmp>\n";
	$xml.= "	<device>\n";
	
	$xml.= "		<infos>\n";
	$xml.= "			<ip>192.168.0.80</ip>\n";
	$xml.= "			<id>2</id>\n";
	$xml.= "			<entity>1</entity>\n";
	$xml.= "		</infos>\n";
	
	$xml.= "		<auth>\n";
	$xml.= "			<community>public</community>\n";
	$xml.= "			<version>2c</version>\n";
	$xml.= "		</auth>\n";
	
	$xml.= "		<get>\n";
	$xml.= "			<object>ifNumber</object>\n";
	$xml.= "			<oid>.1.3.6.1.2.1.2.1.0</oid>\n";
	$xml.= "		</get>\n";
	$xml.= "		<get>\n";
	$xml.= "			<object>sysUpTime</object>\n";
	$xml.= "			<oid>.1.3.6.1.2.1.1.3.0</oid>\n";
	$xml.= "		</get>\n";
	$xml.= "		<get>\n";
	$xml.= "			<object>chassisId</object>\n";
	$xml.= "			<oid>.1.3.6.1.4.1.9.3.6.3.0</oid>\n";
	$xml.= "		</get>\n";
	$xml.= "		<get>\n";
	$xml.= "			<object>sysLocation</object>\n";
	$xml.= "			<oid>.1.3.6.1.2.1.1.6.0</oid>\n";
	$xml.= "		</get>\n";
	$xml.= "		<get>\n";
	$xml.= "			<object>freeMem</object>\n";
	$xml.= "			<oid>.1.3.6.1.4.1.9.2.1.8.0</oid>\n";
	$xml.= "		</get>\n";
	
	$xml.= "		<walk>\n";
	$xml.= "			<object>IF-MIB::ifSpeed</object>\n";
	$xml.= "			<oid>.1.3.6.1.2.1.2.2.1.5</oid>\n";
	$xml.= "		</walk>\n";
	$xml.= "		<walk>\n";
	$xml.= "			<object>IF-MIB::ifInOctets</object>\n";
	$xml.= "			<oid>.1.3.6.1.2.1.2.2.1.10</oid>\n";
	$xml.= "		</walk>\n";
	$xml.= "		<walk>\n";
	$xml.= "			<object>cpmCPUTotal5sec</object>\n";
	$xml.= "			<oid>.1.3.6.1.4.1.9.9.109.1.1.1.1.3.1</oid>\n";
	$xml.= "		</walk>\n";
	$xml.= "		<walk>\n";
	$xml.= "			<object>IF-MIB::ifMtu</object>\n";
	$xml.= "			<oid>.1.3.6.1.2.1.2.2.1.4</oid>\n";
	$xml.= "		</walk>\n";

	$xml.= "	</device>\n";
	$xml.= "</snmp>";
	$gzdata = gzencode($xml, 9);
	echo $gzdata;
}




?>