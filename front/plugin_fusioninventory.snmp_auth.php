<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}

$NEEDED_ITEMS=array("fusioninventory","search","setup","rulesengine");
include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","snmp_auth");

PluginFusioninventoryAuth::checkRight("snmp_authentification","r");

$config = new PluginFusionInventoryConfig;

PluginFusioninventoryDisplay::mini_menu();

// Forms for FILE
if ($config->getValue("authsnmp") == "file") {
	$plugin_fusioninventory_snmp_auth = new PluginFusioninventorySnmpauth;
	
	if (!isset($_GET["ID"])) {
		echo $plugin_fusioninventory_snmp_auth->plugin_fusioninventory_snmp_connections();
	}
} else if ($config->getValue("authsnmp") == "DB") {
	// Forms for DB
	
	$_GET['target']="plugin_fusioninventory.snmp_auth.php";
	
	manageGetValuesInSearch(PLUGIN_FUSIONINVENTORY_SNMP_AUTH);

	searchForm(PLUGIN_FUSIONINVENTORY_SNMP_AUTH,$_GET);
	showList(PLUGIN_FUSIONINVENTORY_SNMP_AUTH,$_GET);
} else {
	echo $LANG['plugin_fusioninventory']["functionalities"][19];
}

commonFooter();

?>