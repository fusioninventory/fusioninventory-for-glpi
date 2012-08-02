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
   @author    Alexandre Delaunay
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
Session::checkLoginUser();

Html::header($LANG['plugin_fusinvdeploy']['title'][0],$_SERVER["PHP_SELF"],"plugins",
   "fusioninventory","packages");

//PluginFusioninventoryProfile::checkRight("Fusioninventory", "agents","r");

PluginFusioninventoryMenu::displayMenu("mini");

$package = new PluginFusinvdeployPackage();

if (isset ($_POST["add"])) {
// PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package","w");
   $newID = $package->add($_POST);
   html::redirect(Toolbox::getItemTypeFormURL('PluginFusinvdeployPackage')."?id=".$newID);
} else if (isset ($_POST["update"])) {
// PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package","w");
   $package->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
// PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package","w");
   $package->delete($_POST);
   Html::redirect(Toolbox::getItemTypeFormURL('PluginFusinvdeployPackage'));
}

$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}
$package->showForm($id);
Html::footer();

?>