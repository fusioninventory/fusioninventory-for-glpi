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

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}

function plugin_tracker_discovery_startmenu($target)
{
	global $LANG, $LANGTRACKER;	

	echo "<br>";
	echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";

	echo "<table class='tab_cadre' cellpadding='5' width='800'>";
	
	echo "<tr class='tab_bg_1'>";
	echo "<th colspan='3'>";
	echo $LANGTRACKER["menu"][0];
	echo "</th>";
	echo "</tr>";

	echo "<tr class='tab_bg_1'>";
	echo "<td align='center' rowspan='2'>".$LANGTRACKER["discovery"][0]."</td>";
	echo "<td align='center'>";
	dropdownInteger("ip1.1", "", 0, 254);
	echo " . ";
	dropdownInteger("ip1.2", "", 0, 254);
	echo " . ";
	dropdownInteger("ip1.3", "", 0, 254);
	echo " . ";
	dropdownInteger("ip1.4", "", 0, 254);
	echo "</td>";
	echo "</tr>";

	echo "<tr class='tab_bg_1'>";
	echo "<td align='center'>";
	dropdownInteger("ip2.1", "", 0, 254);
	echo " . ";
	dropdownInteger("ip2.2", "", 0, 254);
	echo " . ";
	dropdownInteger("ip2.3", "", 0, 254);
	echo " . ";
	dropdownInteger("ip2.4", "", 0, 254);
	echo "</td>";
	echo "</tr>";	

	echo "<tr class='tab_bg_1'>";
	echo "<td colspan='2'>";
	echo "<div align='center'>";
	echo "<input type='submit' name='discover' value=\"".$LANGTRACKER["buttons"][0]."\" class='submit' >";
	echo "</td>";
	echo "</tr>";

	echo "</table></form>";

}



?>