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


class PluginFusioninventoryConfig extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_configs";
	}

	/* Function to get the value of a field */
	function getValue($p_plugins_id, $p_type) {
		global $DB;

      $data = $this->find("`plugins_id`='".$p_plugins_id."'
                          AND `type`='".$p_type."'");
      $config = current($data);
		if (isset($config['value'])) {
         return $config['value'];
		}
		return false;
	}

	// Confirm if the functionality is activated, or not
//	function isActivated($p_type, $p_plugins_id=0) {
	function is_active($p_modules_id, $p_type) {
		if (!($this->getValue($p_modules_id, $p_type))) {
			return false;
      } else {
			return true;
      }
	}

   function defineTabs($options=array()){
		global $LANG,$CFG_GLPI;

      $ong = array();
		$ong[1]=$LANG['plugin_fusioninventory']["functionalities"][2];
//      $ong[2]=$LANG['plugin_fusioninventory']['config'][1];
//		$ong[3]=$LANG['plugin_fusioninventory']["functionalities"][3]." - ".$LANG['plugin_fusioninventory']["functionalities"][5];
//		$ong[3]=$LANG['plugin_fusioninventory']["functionalities"][3]." - ".$LANG['plugin_fusioninventory']["discovery"][3];

//      $ong[7]=$LANG['title'][38];
      $ong[2]=$LANG['plugin_fusioninventory']["functionalities"][7];

		return $ong;
	}

//	function showForm($id, $options=array()) {
//	function showForm($options=array()) {
	function showForm() {
		global $LANG,$CFG_GLPI;

//		$this->showTabs($options);
//      $this->showFormHeader($options);

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][27]."&nbsp;:</td>";
		echo "<td width='20%'>";
		Dropdown::showYesNo("ssl_only", $this->is_active($plugins_id, 'ssl_only'));
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][16]."&nbsp;:</td>";
		echo "<td width='20%'>";
		$ArrayValues = array();
		$ArrayValues['DB']= $LANG['plugin_fusioninventory']["functionalities"][17];
		$ArrayValues['file']= $LANG['plugin_fusioninventory']["functionalities"][18];
		Dropdown::showFromArray('storagesnmpauth', $ArrayValues,
                              array('value'=>$this->getValue($plugins_id, 'storagesnmpauth')));
		echo "</td>";
      echo "</tr>";

 		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']['config'][0]."&nbsp;:</td>";
		echo "<td>";
      Dropdown::showInteger("inventory_frequence",
                            $this->getValue($plugins_id, 'inventory_frequence'),1,240);
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][32]."</td>";
		echo "<td>";
      Dropdown::showInteger("delete_agent_process",
                            $this->getValue($plugins_id, 'delete_agent_process'),1,240);
      echo " ".$LANG['gmt'][1];
		echo "</td>";
      echo "</tr>";

//      echo "<tr class='tab_bg_1'>";
//      echo "<td>".$LANG['plugin_fusioninventory']["functionalities"][26]." (ex : https://192.168.0.1/glpi)</td>";
//      echo "<td>";
//      echo "<input type='text' name='URL_agent_conf' size='30' value='".$this->getValue('URL_agent_conf')."' />";
//      echo "</td>";
//      echo "<td>";
//      echo "</td>";
//      echo "<td>";
//      echo "</td>";
//      echo "</tr>";

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

		$this->showFormButtons();
		$this->addDivForTabs();

      return true;
	}

   /**
    * Add config
    *
    *@param $p_modules_id Module id (0 for Fusioninventory)
    *@param $p_type Config type ('ssl_only', 'URL_agent_conf'...)
    *@param $p_value Value
    *@return integer the new id of the added item (or false if fail)
    **/
   function addConfig($p_modules_id, $p_type, $p_value) {
      return $this->add(array('type'=>$p_type, `value`=>$p_value,
                       'plugin_fusioninventory_modules_id'=>$p_modules_id));
   }

   /**
    * Update config
    *
    *@param $p_id Config id
    *@param $p_modules_id Module id (0 for Fusioninventory)
    *@param $p_type Config type ('ssl_only', 'URL_agent_conf'...)
    *@param $p_value Value
    *@return boolean : true on success
    **/
   function updateConfig($p_id, $p_modules_id, $p_type, $p_value) {
      return $this->update(array('id'=>$p_id, 'type'=>$p_type, `value`=>$p_value,
                       'plugin_fusioninventory_modules_id'=>$p_modules_id));
   }

   /**
    * Delete config
    *
    *@param $p_id Config id
    *@return boolean : true on success
    **/
   function deleteConfig($p_id) {
      return $this->delete(array('id'=>$p_id));
   }

   /**
    * Clean config
    *
    *@param $p_modules_id Module id (0 for Fusioninventory)
    *@return boolean : true on success
    **/
   function cleanConfig($p_modules_id) {
      global $DB;

      $delete = "DELETE FROM ".$this->table.
                "WHERE `plugin_fusioninventory_modules_id`='".$p_modules_id."';";
      return $DB->query($delete);
   }

}

?>