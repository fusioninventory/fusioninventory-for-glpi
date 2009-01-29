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


class plugin_tracker_model_infos extends CommonDBTM
{
	function __construct()
	{
		$this->table = "glpi_plugin_tracker_model_infos";
		$this->type = PLUGIN_TRACKER_MODEL;
	}

	function showForm($target, $ID = '')
	{
		global $DB, $CFG_GLPI, $LANG;

		plugin_tracker_checkRight("snmp_models","r");

		if ($ID!='')
			$this->getFromDB($ID);
		else
			$this->getEmpty();	

		echo "<br>";
		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table class='tab_cadre' cellpadding='5' width='600'><tr><th colspan='2'>";
		echo ($ID =='' ? $LANG['plugin_tracker']["model_info"][7] : $LANG['plugin_tracker']["model_info"][6]);
		echo " :</th></tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='" . $this->fields["name"] . "'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG["common"][17]."</td>";
		echo "<td align='center'>";

		$selected_value = $this->fields["device_type"];
		echo "<select name='device_type'>\n";
		if ($selected_value == "0"){$selected = 'selected';}else{$selected = '';}
		echo "<option value='0' ".$selected.">-----</option>\n";
		if ($selected_value == COMPUTER_TYPE){$selected = 'selected';}else{$selected = '';}
		echo "<option value='".COMPUTER_TYPE."' ".$selected.">".$LANG["Menu"][0]."</option>\n";
		if ($selected_value == NETWORKING_TYPE){$selected = 'selected';}else{$selected = '';}
		echo "<option value='".NETWORKING_TYPE."' ".$selected.">".$LANG["Menu"][1]."</option>\n";
		if ($selected_value == PRINTER_TYPE){$selected = 'selected';}else{$selected = '';}
		echo "<option value='".PRINTER_TYPE."' ".$selected.">".$LANG["Menu"][2]."</option>\n";
		if ($selected_value == PERIPHERAL_TYPE){$selected = 'selected';}else{$selected = '';}
		echo "<option value='".PERIPHERAL_TYPE."' ".$selected.">".$LANG["Menu"][16]."</option>\n";
		if ($selected_value == PHONE_TYPE){$selected = 'selected';}else{$selected = '';}
		echo "<option value='".PHONE_TYPE."' ".$selected.">".$LANG["Menu"][34]."</option>\n";
		echo "</select>";
		
		//dropdownValue("glpi_dropdown_model_networking", "FK_model_networking", $this->fields["FK_model_networking"], 1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_2'><td colspan='2'>";
		if ($ID=='') {
			echo "<div align='center'><input type='submit' name='add' value=\"" . $LANG["buttons"][8] . "\" class='submit' >";

		} else {
			echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
			echo "<div align='center'><input type='submit' name='update' value=\"" . $LANG["buttons"][7] . "\" class='submit' >";
			if (!$this->fields["deleted"])
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"" . $LANG["buttons"][6] . "\" class='submit'>";
			else {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='restore' value=\"" . $LANG["buttons"][21] . "\" class='submit'>";

				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"" . $LANG["buttons"][22] . "\" class='submit'>";
			}
		}
		echo "</td>";
		echo "</tr>";
		echo "</table></form></div>";
	}
}
?>