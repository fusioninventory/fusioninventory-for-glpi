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

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

PluginFusioninventoryProfile::checkRight("fusinvsnmp", "networking","r");

$ptud = new PluginFusinvsnmpUnknownDevice;
$ptt  = new PluginFusioninventoryTask;

commonHeader($LANG['plugin_fusioninventory']["title"][0], $_SERVER["PHP_SELF"], "plugins", "fusioninventory","unknown");

PluginFusioninventoryMenu::displayMenu("mini");

$id = "";
if (isset($_GET["id"])) {
	$id = $_GET["id"];
}

if (isset($_POST["delete"])) {
	$ptud->check($_POST['id'],'w');

	$ptud->delete($_POST,1);

//	logEvent($_POST["id"], "computers", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][22]);
	glpi_header($CFG_GLPI["root_doc"]."plugins/fusioninventory/front/unknowndevice.php");
} else if (isset($_POST["restore"])) {


} else if (isset($_POST["purge"]) || isset($_GET["purge"])) {


} else if (isset($_POST["update"])) {
	$ptud->check($_POST['id'],'w');
	$ptud->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["import"])) {
   $Import = 0;
   $NoImport = 0;
   list($Import, $NoImport) = PluginFusinvsnmpDiscovery::import($_POST['id'],$Import,$NoImport);
   addMessageAfterRedirect($LANG['plugin_fusioninventory']["discovery"][5]." : ".$Import);
   addMessageAfterRedirect($LANG['plugin_fusioninventory']["discovery"][9]." : ".$NoImport);
   if ($Import == "0") {
      glpi_header($_SERVER['HTTP_REFERER']);
   } else {
      glpi_header($CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/unknowndevice.php");
   }
}

$ptud->showForm($id);

commonFooter();

?>
