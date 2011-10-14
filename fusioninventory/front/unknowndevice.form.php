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
   Original Author of file: David Durieux
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
$ptt  = new PluginFusioninventoryTask();

commonHeader($LANG['plugin_fusioninventory']['title'][0], $_SERVER["PHP_SELF"], "plugins", "fusioninventory","unknown");

PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","r");

PluginFusioninventoryMenu::displayMenu("mini");

$id = "";
if (isset($_GET["id"])) {
	$id = $_GET["id"];
}
if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","w");
   if (($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
      $_POST['itemtype'] = '1';
   }
   $PluginFusioninventoryUnknownDevice->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","w");

	$PluginFusioninventoryUnknownDevice->check($_POST['id'],'w');

	$PluginFusioninventoryUnknownDevice->delete($_POST);

   $PluginFusioninventoryUnknownDevice->redirectToList();
} else if (isset($_POST["restore"])) {
   
   $PluginFusioninventoryUnknownDevice->check($_POST['id'],'d');

   if ($PluginFusioninventoryUnknownDevice->restore($_POST)) {
      Event::log($_POST["id"],"PluginFusioninventoryUnknownDevice", 4, "inventory",
               $_SESSION["glpiname"]." ".$LANG['log'][23]." ".$PluginFusioninventoryUnknownDevice->getField('name'));
   }
   $PluginFusioninventoryUnknownDevice->redirectToList();

} else if (isset($_POST["purge"]) || isset($_GET["purge"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","w");

	$PluginFusioninventoryUnknownDevice->check($_POST['id'],'w');

	$PluginFusioninventoryUnknownDevice->delete($_POST,1);
   $PluginFusioninventoryUnknownDevice->redirectToList();
} else if (isset($_POST["update"])) {
	$PluginFusioninventoryUnknownDevice->check($_POST['id'],'w');
	$PluginFusioninventoryUnknownDevice->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["import"])) {
   $Import = 0;
   $NoImport = 0;
   list($Import, $NoImport) = $PluginFusioninventoryUnknownDevice->import($_POST['id'],$Import,$NoImport);
   addMessageAfterRedirect($LANG['plugin_fusioninventory']['discovery'][5]." : ".$Import);
   addMessageAfterRedirect($LANG['plugin_fusioninventory']['discovery'][9]." : ".$NoImport);
   if ($Import == "0") {
      glpi_header($_SERVER['HTTP_REFERER']);
   } else {
      glpi_header(GLPI_ROOT."/plugins/fusioninventory/front/unknowndevice.php");
   }
}

$PluginFusioninventoryUnknownDevice->showForm($id);

commonFooter();

?>