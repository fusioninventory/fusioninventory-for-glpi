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
	"networking",
   "computer",
   "infocom",
   "printer",
   "peripheral"
);

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

PluginFusioninventoryAuth::checkRight("snmp_networking","r");

$ptud = new PluginFusioninventoryUnknownDevice;
$ptt  = new PluginFusioninventoryTask;
$ptcm = new PluginFusioninventoryConfigModules;

commonHeader($LANG['plugin_fusioninventory']["title"][0], $_SERVER["PHP_SELF"], "plugins", "fusioninventory","unknown");

PluginFusioninventoryDisplay::mini_menu();

$ID = "";
if (isset($_GET["ID"])) {
	$ID = $_GET["ID"];
}

if (isset($_POST["delete"])) {
	$ptud->check($_POST['ID'],'w');

	$ptud->delete($_POST,1);

//	logEvent($_POST["ID"], "computers", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][22]);
	glpi_header($CFG_GLPI["root_doc"]."plugins/fusioninventory/front/unknown.php");
} else if (isset($_POST["restore"])) {


} else if (isset($_POST["purge"]) || isset($_GET["purge"])) {


} else if (isset($_POST["update"])) {
	$ptud->check($_POST['ID'],'w');
	$ptud->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["import"])) {
   $Import = 0;
   $NoImport = 0;
   list($Import, $NoImport) = PluginFusioninventoryDiscovery::import($_POST['ID'],$Import,$NoImport);
   addMessageAfterRedirect($LANG['plugin_fusioninventory']["discovery"][5]." : ".$Import);
   addMessageAfterRedirect($LANG['plugin_fusioninventory']["discovery"][9]." : ".$NoImport);
   if ($Import == "0") {
      glpi_header($_SERVER['HTTP_REFERER']);
   } else {
      glpi_header($CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/unknown.php");
   }
}

$ptud->showTabs($ID, '',$_SESSION['glpi_tab']);
$ptud->showForm($_SERVER["PHP_SELF"], $ID);
echo "<div id='tabcontent'></div>";
echo "<script type='text/javascript'>loadDefaultTab();</script>";

commonFooter();

?>