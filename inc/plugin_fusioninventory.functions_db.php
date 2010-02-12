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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

function plugin_fusioninventory_createfirstaccess($ID) {

	global $DB;
	
	$plugin_fusioninventory_Profile=new PluginFusionInventoryProfile;
	if (!$plugin_fusioninventory_Profile->GetfromDB($ID)) {
		$Profile=new Profile;
		$Profile->GetfromDB($ID);
		$name=$Profile->fields["name"];

		$query = "INSERT INTO `glpi_plugin_fusioninventory_profiles` (
				    `ID`, `name`, `interface`, `is_default`, `snmp_networking`, `snmp_printers`, 
                `snmp_models`, `snmp_authentification`, `fusioninventory_task`, `snmp_discovery`,
                `general_config`, `snmp_iprange`, `snmp_agent`, `snmp_agent_infos`, `snmp_report` )
				    VALUES ('$ID', '$name','fusioninventory','0','w','w','w','w','w','w','w','w','w','w','w');";
		$DB->query($query);
	}
}

function plugin_fusioninventory_createaccess($ID) {
	global $DB;
	
	$Profile=new Profile;
	$Profile->GetfromDB($ID);
	$name=$Profile->fields["name"];
	
	$query = "INSERT INTO `glpi_plugin_fusioninventory_profiles` (
             `ID`, `name` , `interface`, `is_default`, `snmp_networking`, `snmp_printers`, 
             `snmp_models`, `snmp_authentification`, `fusioninventory_task`, `snmp_discovery`,
             `general_config`, `snmp_iprange`, `snmp_agent`, `snmp_agent_infos`, `snmp_report` )
             VALUES ('$ID', '$name','fusioninventory','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,
                     NULL,NULL);";
	$DB->query($query);
}

function plugin_fusioninventory_getIdFromUser($name) {
	
	global $DB;
	
	$query = "SELECT `ID` ".
            "FROM `glpi_users` ".
            "WHERE `name` = '".$name."';";

	if (($result = $DB->query($query))) {
		return $DB->result($result,0,"ID");
	}
	return false;
}

function plugin_fusioninventory_getDeviceFieldFromId($type, $ID, $field, $return) {
	global $DB;
	switch($type) {
		case COMPUTER_TYPE:
			$table = "`glpi_computers`";
			break;
			
		case NETWORKING_TYPE:
			$table = "`glpi_networking`";
			break;
			
		case PRINTER_TYPE:
			$table = "`glpi_printers`";
			break;
			
		case USER_TYPE:
			$table = "`glpi_users`";
			break;
			
		default:
			return $return;
			break;
	}
	
	$query = "SELECT ".$field.
            "FROM ".$table." ".
            "WHERE `ID` = '".$ID."';";
	if ($result = $DB->query($query)) {
		if (($fields=$DB->fetch_row($result)) && ($fields['0'] != NULL)) {
			return $fields['0'];
      }
	}
	return $return;
}


function plugin_fusioninventory_clean_db() {
	global $DB;
	
   $ptp = new PluginFusionInventoryPort;
   $pti = new PluginFusionInventoryIfaddr;
   $ptn = new PluginFusionInventoryNetworking2;
   $ptpr = new PluginFusionInventoryPrinters;
   $ptpc = new PluginFusionInventoryPrintersCartridges;
   $ptph = new PluginFusionInventoryPrintersHistory;

	// * Clean glpi_plugin_fusioninventory_networking_ports
	$query_select = "SELECT `glpi_plugin_fusioninventory_networking_ports`.`ID`
                    FROM `glpi_plugin_fusioninventory_networking_ports`
                          LEFT JOIN `glpi_networking_ports`
                                    ON `glpi_networking_ports`.`ID` = `FK_networking_ports`
                          LEFT JOIN `glpi_networking` ON `glpi_networking`.`ID` = `on_device`
                    WHERE `glpi_networking`.`ID` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $ptp->deleteFromDB($data["ID"],1);
	}

	// * Clean glpi_plugin_fusioninventory_networking_ifaddr
	$query_select = "SELECT `glpi_plugin_fusioninventory_networking_ifaddr`.`ID`
                    FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                          LEFT JOIN `glpi_networking` ON `glpi_networking`.`ID` = `FK_networking`
                    WHERE `glpi_networking`.`ID` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $pti->deleteFromDB($data["ID"],1);
	}

	// * Clean glpi_plugin_fusioninventory_networking
	$query_select = "SELECT `glpi_plugin_fusioninventory_networking`.`ID`
                    FROM `glpi_plugin_fusioninventory_networking`
                          LEFT JOIN `glpi_networking` ON `glpi_networking`.`ID` = `FK_networking`
                    WHERE `glpi_networking`.`ID` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $ptn->deleteFromDB($data["ID"],1);
	}
	
	// * Clean glpi_plugin_fusioninventory_printers
	$query_select = "SELECT `glpi_plugin_fusioninventory_printers`.`ID`
                    FROM `glpi_plugin_fusioninventory_printers`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`ID` = `FK_printers`
                    WHERE `glpi_printers`.`ID` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $ptpr->deleteFromDB($data["ID"],1);
	}

	// * Clean glpi_plugin_fusioninventory_printers_cartridges
	$query_select = "SELECT `glpi_plugin_fusioninventory_printers_cartridges`.`ID`
                    FROM `glpi_plugin_fusioninventory_printers_cartridges`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`ID` = `FK_printers`
                    WHERE `glpi_printers`.`ID` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $ptpc->deleteFromDB($data["ID"],1);
	}

	// * Clean glpi_plugin_fusioninventory_printers_history
	$query_select = "SELECT `glpi_plugin_fusioninventory_printers_history`.`ID`
                    FROM `glpi_plugin_fusioninventory_printers_history`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`ID` = `FK_printers`
                    WHERE `glpi_printers`.`ID` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $ptph->deleteFromDB($data["ID"],1);
	}
}

function plugin_fusioninventory_db_lock_wire_check() {
   while (1) {
      $file_lock = GLPI_PLUGIN_DOC_DIR."/fusioninventory/wire.lock";
      $fp =  fopen($file_lock,"r+");
      $lock = 1;
      fseek($fp,0);
      $lock = fgets($fp,255);
      if ($lock == 0) {
         fseek($fp,0);
         fputs($fp,1);
         fclose($fp);
         return;
      }
      fclose($fp);
      usleep(250000);
   }
}

function plugin_fusioninventory_db_lock_wire_unlock() {
   $file_lock = GLPI_PLUGIN_DOC_DIR."/fusioninventory/wire.lock";
   $fp =  fopen($file_lock,"r+");
   fputs($fp,0);
   fclose($fp);
}

?>