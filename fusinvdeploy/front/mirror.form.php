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

Html::header($LANG['plugin_fusinvdeploy']['mirror'][1],$_SERVER["PHP_SELF"],"plugins",
   "fusioninventory","mirror");

//PluginFusioninventoryProfile::checkRight("Fusioninventory", "agents","r");

PluginFusioninventoryMenu::displayMenu("mini");

$mirror = new PluginFusinvdeployMirror();

if (isset ($_POST["add"])) {
// PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package","w");
   $newID = $mirror->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
// PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package","w");
   $mirror->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
// PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package","w");
   $mirror->delete($_POST);
   Html::redirect(Toolbox::getItemTypeFormURL('PluginFusinvdeployMirror'));
}

$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}
$mirror->showForm($id);
Html::footer();

?>