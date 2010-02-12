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

$NEEDED_ITEMS = array (
	"setup",
	"rulesengine",
	"fusioninventory",
	"search",
	"device",
	"networking"
);

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

plugin_fusioninventory_checkRight("snmp_networking","r");

$ptud = new PluginFusionInventoryUnknownDevice;
$ptt = new PluginFusionInventoryTask;

commonHeader($LANG['plugin_fusioninventory']["title"][0], $_SERVER["PHP_SELF"], "plugins", "fusioninventory","unknown");

plugin_fusioninventory_mini_menu();

$ID = "";
if (isset($_GET["ID"])) {
	$ID = $_GET["ID"];
}

if (isset($_POST["delete"])) {
	$ptud->check($_POST['ID'],'w');

	$ptud->delete($_POST,1);

//	logEvent($_POST["ID"], "computers", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][22]);
	glpi_header($CFG_GLPI["root_doc"]."plugins/fusioninventory/front/plugin_fusioninventory.unknown.php");
} else if (isset($_POST["restore"])) {


} else if (isset($_POST["purge"]) || isset($_GET["purge"])) {


} else if (isset($_POST["update"])) {
	$ptud->check($_POST['ID'],'w');
	$ptud->update($_POST);
//	logEvent($_POST["ID"], "computers", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][21]);
	glpi_header($_SERVER['HTTP_REFERER']);
}



$ptud->showForm($_SERVER["PHP_SELF"], $ID);
if (isset($_POST)) {
   $ptt->formAddTask($_SERVER["PHP_SELF"], $_POST);
} else {
   $ptt->formAddTask($_SERVER["PHP_SELF"]);
}
showPorts($ID, PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN);
showHistory(PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN,$ID);
commonFooter();
?>
