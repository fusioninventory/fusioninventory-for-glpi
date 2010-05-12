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


class PluginFusioninventoryConfigModules extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_config_modules";
	}

	function initConfig() {
		global $DB,$CFG_GLPI;
		$query = "INSERT INTO ".$this->table."(
                              `id`, `snmp`, `inventoryocs`, `netdiscovery`, `remotehttpagent`, `wol`)
                VALUES ('1', '0', '0', '0', '0', '0');";
		
		$DB->query($query);
	}
	
	/* Function to get the value of a field */
	function getValue($field) {
		global $DB;

		$query = "SELECT ".$field."
                FROM ".$this->table."
                WHERE `id` = '1';";
		if ($result = $DB->query($query)) {
			if ($this->fields = $DB->fetch_row($result)) {
				return $this->fields['0'];
         }
		}
		return false;
	}

	// Confirm if the functionality is activated, or not
	function isActivated($functionality) {
		
		if (!($this->getValue($functionality))) {
			return false;
      } else {
			return true;
      }
	}


		
	function showForm($id, $options=array()) {
		global $LANG,$CFG_GLPI;

		$this->showTabs($options);
      $this->showFormHeader($options);

		echo "<tr class='tab_bg_1'>";
		echo "<td width='30%'>".$LANG['plugin_fusioninventory']['config'][2]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("snmp", $this->isActivated('snmp'));
		echo "</td>";
		echo "<td width='20%'>".$LANG['plugin_fusioninventory']['config'][4]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("netdiscovery", $this->isActivated('netdiscovery'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']['config'][3]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("inventoryocs", $this->isActivated('inventoryocs'));
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']['config'][5]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("remotehttpagent", $this->isActivated('remotehttpagent'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']['config'][6]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("wol", $this->isActivated('wol'));
		echo "</td>";
		echo "<td colspan='2'>";
		echo "</td>";
		echo "</tr>";

		$this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
	}
}

?>