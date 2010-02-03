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

$NEEDED_ITEMS=array("tracker","search");
include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANG['plugin_tracker']["title"][0],$_SERVER["PHP_SELF"],"plugins","tracker","constructdevice");

plugin_tracker_mini_menu();

manageGetValuesInSearch(PLUGIN_TRACKER_CONSTRUCT_DEVICE);

$_GET['target']="plugin_tracker.construct_device.php";
if (isset($_GET['generatemodels']) AND $_GET['generatemodels'] == '1') {
   $ptcd = new PluginTrackerConstructDevice;
   $ptcd->generatemodels();
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_GET['generatediscover']) AND $_GET['generatediscover'] == '1') {
   $ptcd = new PluginTrackerConstructDevice;
   $ptcd->generateDiscovery();
   glpi_header($_SERVER['HTTP_REFERER']);
}

echo "<a href='".$_SERVER["PHP_SELF"]."?generatemodels=1'>Creation automatique des modèles</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?generatediscover=1'>Générer le fichier de découverte</a>";
searchForm(PLUGIN_TRACKER_CONSTRUCT_DEVICE,$_GET);
showList(PLUGIN_TRACKER_CONSTRUCT_DEVICE,$_GET);


commonFooter();

?>
