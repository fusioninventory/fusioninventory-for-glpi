<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"],
        "plugins", "pluginfusioninventorymenu", "snmpmodelconstructdevice");
Session::checkLoginUser();

PluginFusioninventoryMenu::displayMenu("mini");

$_GET['target']="construct_device.php";

$pfConstructDevice = new PluginFusioninventorySnmpmodelConstructDevice();

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
echo "<a href='".$_SERVER["PHP_SELF"]."?generatemodels=1'>".__('Automatic creation of models', 'fusioninventory')."</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?cleanmodels=1'>".__('Delete models non used', 'fusioninventory')."</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?generatecomments=1'>".__('Re-create models comments', 'fusioninventory')."</a>";
echo " | ";

echo "<br/>";

echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?generatediscover=1'>".__('Generate discovery file', 'fusioninventory')."</a>";
echo " | ";
echo "<a href='".$_SERVER["PHP_SELF"]."?exportmodels=1'>".__('Export all models', 'fusioninventory')."</a>";
echo " | ";

Search::show('PluginFusioninventorySnmpmodelConstructDevice');

Html::footer();

?>
