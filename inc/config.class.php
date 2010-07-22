<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: Vincent MAZZONI
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


class PluginFusinvSNMPConfig extends PluginFusioninventoryConfig {
	function initConfigModule() {
		global $DB;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $insert = array('criteria1_ip'=>'0',
                      'criteria1_name'=>'0',
                      'criteria1_serial'=>'0',
                      'criteria1_macaddr'=>'0',
                      'criteria2_ip'=>'0',
                      'criteria2_name'=>'0',
                      'criteria2_serial'=>'0',
                      'criteria2_macaddr'=>'0');
      $this->initConfig($plugins_id, $insert);
   }

	function putForm($p_post) {
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $this->updateConfigType($plugins_id, 'criteria1_ip', $p_post['criteria1_ip']);
      $this->updateConfigType($plugins_id, 'criteria1_name', $p_post['criteria1_name']);
      $this->updateConfigType($plugins_id, 'criteria1_serial', $p_post['criteria1_serial']);
      $this->updateConfigType($plugins_id, 'criteria1_macaddr', $p_post['criteria1_macaddr']);
      $this->updateConfigType($plugins_id, 'criteria2_ip', $p_post['criteria2_ip']);
      $this->updateConfigType($plugins_id, 'criteria2_name', $p_post['criteria2_name']);
      $this->updateConfigType($plugins_id, 'criteria2_serial', $p_post['criteria2_serial']);
      $this->updateConfigType($plugins_id, 'criteria2_macaddr', $p_post['criteria2_macaddr']);
   }

	function showForm($options=array()) {
		global $LANG,$CFG_GLPI;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      echo "<form name='form' method='post' action='".$options['target']."'>";
      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["discovery"][6]."&nbsp;:";
		echo "</th>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["discovery"][6]." 2&nbsp;:";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td width='500'>".$LANG["networking"][14]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_ip", $this->is_active($plugins_id, 'criteria1_ip'));
		echo "</td>";
		echo "<td width='500'>".$LANG["networking"][14]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_ip", $this->is_active($plugins_id, 'criteria2_ip'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_name", $this->is_active($plugins_id, 'criteria1_name'));
		echo "</td>";
		echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_name", $this->is_active($plugins_id, 'criteria2_name'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][19]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_serial", $this->is_active($plugins_id, 'criteria1_serial'));
		echo "</td>";
		echo "<td>".$LANG["common"][19]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_serial", $this->is_active($plugins_id, 'criteria2_serial'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['device_iface'][2]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_macaddr", $this->is_active($plugins_id, 'criteria1_macaddr'));
		echo "</td>";
		echo "<td>".$LANG['device_iface'][2]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_macaddr", $this->is_active($plugins_id, 'criteria2_macaddr'));
		echo "</td>";
		echo "</tr>";

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "w")) {
         echo "<tr class='tab_bg_2'><td align='center' colspan='4'>
               <input class='submit' type='submit' name='plugin_fusinvsnmp_config_set'
                      value='" . $LANG['buttons'][7] . "'></td></tr>";
      }
      echo "</table>";
      echo "</form>";

      return true;
	}
}

?>