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
	die("Sorry. You can't access directly to this file");
}


class PluginTrackerConfigModules extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_tracker_config_modules";
//		$this->type=PLUGIN_TRACKER_SNMP_CONFIG_MODULES;
	}

	function initConfig($version) {
		global $DB,$CFG_GLPI;
		$url = str_replace("http:","https:",$CFG_GLPI["url_base"]);
		$query = "INSERT INTO ".$this->table."(
                              `id`, `snmp`, `inventoryocs`, `netdiscovery`, `remotehttpagent`)
                VALUES ('1', '0', '0', '0', '0');";
		
		$DB->query($query);
	}

   

   // Confirm if the functionality is activated, or not
	function isActivated($functionality) {
		
		if (!($this->getValue($functionality))) {
			return false;
      } else {
			return true;
      }
	}


		
	function showForm($target,$ID) {
		global $LANG,$CFG_GLPI;

		echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='5'>";
		
		echo "<tr>";
		echo "<th colspan='4'>";
		echo $LANG['plugin_tracker']['config'][1]."&nbsp;:";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td width='30%'>".$LANG['plugin_tracker']['config'][2]."&nbsp;:</td>";
		echo "<td>";
		dropdownYesNo("snmp", $this->isActivated('snmp'));
		echo "</td>";
		echo "<td width='20%'>".$LANG['plugin_tracker']['config'][4]."&nbsp;:</td>";
		echo "<td>";
		dropdownYesNo("netdiscovery", $this->isActivated('netdiscovery'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_tracker']['config'][3]."&nbsp;:</td>";
		echo "<td>";
		dropdownYesNo("inventoryocs", $this->isActivated('inventoryocs'));
		echo "</td>";
		echo "<td>".$LANG['plugin_tracker']['config'][5]."&nbsp;:</td>";
		echo "<td>";
		dropdownYesNo("remotehttpagent", $this->isActivated('remotehttpagent'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='4'>";
		echo "<input type='hidden' name='tabs' value='config' />";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";

	}
}

?>