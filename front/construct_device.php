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

include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","constructdevice");

PluginFusioninventoryDisplay::mini_menu();

manageGetValuesInSearch(PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE);

$_GET['target']="construct_device.php";
if (isset($_GET['generatemodels']) AND $_GET['generatemodels'] == '1') {
   $ptcd = new PluginFusioninventoryConstructDevice;
   $ptcd->generatemodels();
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_GET['generatediscover']) AND $_GET['generatediscover'] == '1') {
   $ptcd = new PluginFusioninventoryConstructDevice;
   $ptcd->generateDiscovery();
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_GET['cleanmodels']) AND $_GET['cleanmodels'] == '1') {
   $ptcd = new PluginFusioninventoryConstructDevice;
   $ptcd->cleanmodels();
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_GET['exportmodels']) AND $_GET['exportmodels'] == '1') {
   $ptcd = new PluginFusioninventoryConstructDevice;
   $ptcd->exportmodels();
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_GET['generatecomments']) AND $_GET['generatecomments'] == '1') {
   $ptcd = new PluginFusionInventoryConstructDevice;
   $ptcd->generatecomments();
   glpi_header($_SERVER['HTTP_REFERER']);
}

echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?generatemodels=1'>".$LANG['plugin_fusioninventory']["constructdevice"][1]."</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?cleanmodels=1'>".$LANG['plugin_fusioninventory']["constructdevice"][3]."</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?generatecomments=1'>".$LANG['plugin_fusioninventory']["constructdevice"][5]."</a>";
echo " | ";

echo "<br/>";

echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?generatediscover=1'>".$LANG['plugin_fusioninventory']["constructdevice"][2]."</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?exportmodels=1'>".$LANG['plugin_fusioninventory']["constructdevice"][4]."</a>";
echo " | ";

searchForm(PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE,$_GET);
showList(PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE,$_GET);

commonFooter();

?>