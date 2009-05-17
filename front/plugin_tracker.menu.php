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

$NEEDED_ITEMS=array("tracker");
include (GLPI_ROOT."/inc/includes.php");

if (plugin_tracker_HaveRight("snmp_models","r")
	OR plugin_tracker_HaveRight("snmp_authentification","r")
	OR plugin_tracker_HaveRight("snmp_iprange","r")
	OR plugin_tracker_HaveRight("snmp_agent","r")
	OR plugin_tracker_HaveRight("snmp_scripts_infos","r")
	OR plugin_tracker_HaveRight("snmp_agent_infos","r")
	OR plugin_tracker_HaveRight("snmp_discovery","r")
	OR plugin_tracker_HaveRight("snmp_report","r")
	)
{
	if (plugin_tracker_needUpdate() == 1)
	{
		commonHeader($LANG['plugin_tracker']["setup"][4], $_SERVER["PHP_SELF"],"plugins","tracker");
		echo "<div align='center'>";
		echo "<table class='tab_cadre' cellpadding='5'>";
		echo "<tr><th>".$LANG['plugin_tracker']["setup"][3];
		echo "</th></tr>";
		echo "<tr class='tab_bg_1'><td>";
		echo "<a href='plugin_tracker.install.php'>".$LANG['plugin_tracker']["setup"][5]."</a></td></tr>";
		echo "</table></div>";
	}else{
		commonHeader($LANG['plugin_tracker']["title"][0],$_SERVER["PHP_SELF"],"plugins","tracker");

		plugin_tracker_menu();
	}
}
else
{
	displayRightError();
}
commonFooter();

?>