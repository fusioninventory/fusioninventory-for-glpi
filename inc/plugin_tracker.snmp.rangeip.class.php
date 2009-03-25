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

if (!defined('GLPI_ROOT'))
	die("Sorry. You can't access directly to this file");


class plugin_tracker_rangeip extends CommonDBTM
{
	function __construct()
	{
		$this->table = "glpi_plugin_tracker_rangeip";
		$this->type = PLUGIN_TRACKER_SNMP_RANGEIP;
	}


	function showForm($target, $ID = '') {
		global $DB,$CFG_GLPI,$LANG,$LANGTRACKER;

		if ($ID!='')
			$this->getFromDB($ID);
		else
			$this->getEmpty();

		echo "<br>";
		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table class='tab_cadre' cellpadding='5' width='600'><tr><th colspan='2'>";
		echo $LANGTRACKER["rangeip"][2];
		echo " :</th></tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='".$this->fields["name"]."'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["rangeip"][0] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='ifaddr_start' value='".$this->fields["ifaddr_start"]."'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["rangeip"][1] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='ifaddr_end' value='".$this->fields["ifaddr_end"]."'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["agents"][0] . "</td>";
		echo "<td align='center'>";
		dropdownValue("glpi_plugin_tracker_agents","FK_tracker_agents",$this->fields["FK_tracker_agents"],0);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["discovery"][3] . "</td>";
		echo "<td align='center'>";
		dropdownYesNo("discover",$this->fields["discover"]);
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANGTRACKER["rangeip"][3] . "</td>";
		echo "<td align='center'>";
		dropdownYesNo("query",$this->fields["query"]);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		if ($ID=='')
		{
			echo "<input type='hidden' name='FK_entities' value='".$_SESSION["glpiactive_entity"]."'/>";
			echo "<div align='center'><input type='submit' name='add' value=\"" . $LANG["buttons"][8] . "\" class='submit' >";
		}
		else
		{
			echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
			echo "<div align='center'><input type='submit' name='update' value=\"" . $LANG["buttons"][7] . "\" class='submit' >";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"" . $LANG["buttons"][6] . "\" class='submit'>";
		}
		echo "</td></tr>";
		echo "</table></form></div>";

	}

}
?>