<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}

//$NEEDED_ITEMS=array("fusioninventory");
$NEEDED_ITEMS=array("computer","device","printer","networking","peripheral","monitor","software","infocom",
	"phone","tracking","enterprise","reservation","setup","group","registry","rulesengine","ocsng","admininfo");

include (GLPI_ROOT."/inc/includes.php");

if (plugin_fusioninventory_HaveRight("snmp_models","r")
	OR plugin_fusioninventory_HaveRight("snmp_authentification","r")
	OR plugin_fusioninventory_HaveRight("snmp_iprange","r")
	OR plugin_fusioninventory_HaveRight("snmp_agent","r")
	OR plugin_fusioninventory_HaveRight("snmp_scripts_infos","r")
	OR plugin_fusioninventory_HaveRight("snmp_agent_infos","r")
	OR plugin_fusioninventory_HaveRight("snmp_discovery","r")
	OR plugin_fusioninventory_HaveRight("snmp_report","r")
	) {
	if (plugin_fusioninventory_needUpdate() == 1) {
		commonHeader($LANG['plugin_fusioninventory']["setup"][4], $_SERVER["PHP_SELF"],"plugins","fusioninventory");
		echo "<div align='center'>";
		echo "<table class='tab_cadre' cellpadding='5'>";
		echo "<tr><th>".$LANG['plugin_fusioninventory']["setup"][3];
		echo "</th></tr>";
		echo "<tr class='tab_bg_1'><td>";
		echo "<a href='plugin_fusioninventory.install.php'>".$LANG['plugin_fusioninventory']["setup"][5]."</a></td></tr>";
		echo "</table></div>";
	} else {
		commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory");

		plugin_fusioninventory_menu();
	}
} else {
	displayRightError();
}


//$p_xml = "
//<REQUEST>
//    <DEVICE>
//      <AUTHSNMP>2</AUTHSNMP>
//      <DESCRIPTION>HP LaserJet 4100 Series</DESCRIPTION>
//      <IP>172.23.9.62</IP>
//      <MODELSNMP>Printer0006</MODELSNMP>
//      <NETBIOSNAME>P077</NETBIOSNAME>
//      <SERIAL>JPMGD12222</SERIAL>
//      <SNMPHOSTNAME>P077</SNMPHOSTNAME>
//      <TYPE>3</TYPE>
//    </DEVICE>
//</REQUEST>";
//
//$sxml = @simplexml_load_string($p_xml);
//
//foreach ($sxml->children() as $child) {
//
//      $child->MAC = strtolower($child->MAC);
//
//         $p_criteria['ip'] = $child->IP;
//         if (!empty($discovery->NETBIOSNAME)) {
//            $p_criteria['name'] = $child->NETBIOSNAME;
//         } else if (!empty($child->SNMPHOSTNAME)) {
//            $p_criteria['name'] = $child->SNMPHOSTNAME;
//         }
//         $p_criteria['serial'] = $child->SERIAL;
//         $p_criteria['macaddr'] = $child->MAC;
//
//
//
//
//   if (plugin_fusioninventory_discovery_criteria($p_criteria)) {
//      echo "In base";
//      echo $p_criteria['serial'] = $child->SERIAL." / ";
//      echo $p_criteria['ip'] = $child->IP." / ";
//      echo $p_criteria['macaddr'] = $child->MAC;
//   } else {
//      echo "Not in base";
//   }
//   echo "<br/>";
//}



commonFooter();

?>
