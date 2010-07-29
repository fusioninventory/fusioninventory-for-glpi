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


class PluginFusinvSNMPConfig extends CommonDBTM {
	function initConfigModule() {
		global $DB;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $insert = array('storagesnmpauth'=>'DB',
                      'criteria1_ip'=>'0',
                      'criteria1_name'=>'0',
                      'criteria1_serial'=>'0',
                      'criteria1_macaddr'=>'0',
                      'criteria2_ip'=>'0',
                      'criteria2_name'=>'0',
                      'criteria2_serial'=>'0',
                      'criteria2_macaddr'=>'0');
      $PluginFusioninventoryConfig->initConfig($plugins_id, $insert);
   }

	function putForm($p_post) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'storagesnmpauth', $p_post['storagesnmpauth']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'criteria1_ip', $p_post['criteria1_ip']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'criteria1_name', $p_post['criteria1_name']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'criteria1_serial', $p_post['criteria1_serial']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'criteria1_macaddr', $p_post['criteria1_macaddr']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'criteria2_ip', $p_post['criteria2_ip']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'criteria2_name', $p_post['criteria2_name']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'criteria2_serial', $p_post['criteria2_serial']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'criteria2_macaddr', $p_post['criteria2_macaddr']);
   }

	function showForm($options=array()) {
		global $LANG,$CFG_GLPI;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      echo "<form name='form' method='post' action='".$options['target']."'>";
      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][16]."&nbsp;:</td>";
		echo "<td>";
		$ArrayValues = array();
		$ArrayValues['DB']= $LANG['plugin_fusioninventory']["functionalities"][17];
		$ArrayValues['file']= $LANG['plugin_fusioninventory']["functionalities"][18];
		Dropdown::showFromArray('storagesnmpauth', $ArrayValues,
                              array('value'=>$PluginFusioninventoryConfig->getValue($plugins_id, 'storagesnmpauth')));
		echo "</td>";
      echo "<td colspan='2'></td>";;
      echo "</tr>";

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
		Dropdown::showYesNo("criteria1_ip", $PluginFusioninventoryConfig->is_active($plugins_id, 'criteria1_ip'));
		echo "</td>";
		echo "<td width='500'>".$LANG["networking"][14]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_ip", $PluginFusioninventoryConfig->is_active($plugins_id, 'criteria2_ip'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_name", $PluginFusioninventoryConfig->is_active($plugins_id, 'criteria1_name'));
		echo "</td>";
		echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_name", $PluginFusioninventoryConfig->is_active($plugins_id, 'criteria2_name'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][19]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_serial", $PluginFusioninventoryConfig->is_active($plugins_id, 'criteria1_serial'));
		echo "</td>";
		echo "<td>".$LANG["common"][19]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_serial", $PluginFusioninventoryConfig->is_active($plugins_id, 'criteria2_serial'));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['device_iface'][2]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria1_macaddr", $PluginFusioninventoryConfig->is_active($plugins_id, 'criteria1_macaddr'));
		echo "</td>";
		echo "<td>".$LANG['device_iface'][2]."&nbsp;:</td>";
		echo "<td>";
		Dropdown::showYesNo("criteria2_macaddr", $PluginFusioninventoryConfig->is_active($plugins_id, 'criteria2_macaddr'));
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