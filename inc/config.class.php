<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
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
      $insert = array('storagesnmpauth'=>'DB');
      $PluginFusioninventoryConfig->initConfig($plugins_id, $insert);
   }

	function putForm($p_post) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'storagesnmpauth', $p_post['storagesnmpauth']);
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
      echo "<td colspan='2'></td>";
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