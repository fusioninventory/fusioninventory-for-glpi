<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/inc/includes.php");

Html::header($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","constructdevice");
Session::checkLoginUser();

PluginFusioninventoryMenu::displayMenu("mini");

$_GET['target']="construct_device.php";

$pfConstructDevice = new PluginFusinvsnmpConstructDevice();

if (isset($_GET['generatemodels']) AND $_GET['generatemodels'] == '1') {
   $pfConstructDevice->generatemodels();
   Html::back();
} else if (isset($_GET['generatediscover']) AND $_GET['generatediscover'] == '1') {
   $pfConstructDevice->generateDiscovery();
   Html::back();
} else if (isset($_GET['cleanmodels']) AND $_GET['cleanmodels'] == '1') {
   $pfConstructDevice->cleanmodels();
   Html::back();
} else if (isset($_GET['exportmodels']) AND $_GET['exportmodels'] == '1') {
   $pfConstructDevice->exportmodels();
   Html::back();
} else if (isset($_GET['generatecomments']) AND $_GET['generatecomments'] == '1') {
   $pfConstructDevice->generatecomments();
   Html::back();
}

echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?generatemodels=1'>".$LANG['plugin_fusinvsnmp']['constructdevice'][1]."</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?cleanmodels=1'>".$LANG['plugin_fusinvsnmp']['constructdevice'][3]."</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?generatecomments=1'>".$LANG['plugin_fusinvsnmp']['constructdevice'][5]."</a>";
echo " | ";

echo "<br/>";

echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?generatediscover=1'>".$LANG['plugin_fusinvsnmp']['constructdevice'][2]."</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?exportmodels=1'>".$LANG['plugin_fusinvsnmp']['constructdevice'][4]."</a>";
echo " | ";

Search::show('PluginFusinvsnmpConstructDevices');

Html::footer();

?>