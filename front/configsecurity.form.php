<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

PluginFusioninventoryProfile::checkRight("fusinvsnmp", "configsecurity","r");

$PluginFusinvsnmpConfigSecurity = new PluginFusinvsnmpConfigSecurity();
$config = new PluginFusioninventoryConfig();

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","configsecurity");

PluginFusioninventoryMenu::displayMenu("mini");


if (isset ($_POST["add"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "configsecurity","w");
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
   $new_ID = 0;
	if ($config->getValue($plugins_id, "storagesnmpauth") == "file") {
		$new_ID = $PluginFusinvsnmpConfigSecurity->add_xml();
   } else if ($config->getValue($plugins_id, "storagesnmpauth") == "DB") {
		$new_ID = $PluginFusinvsnmpConfigSecurity->add($_POST);
   }
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "configsecurity","w");
	$PluginFusinvsnmpConfigSecurity->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "configsecurity","w");
   $PluginFusinvsnmpConfigSecurity->delete($_POST);
   glpi_header("configsecurity.php");
}

$id = "";
if (isset($_GET["id"])) {
	$id = $_GET["id"];
}

$PluginFusinvsnmpConfigSecurity->showForm($id);

commonFooter();

?>