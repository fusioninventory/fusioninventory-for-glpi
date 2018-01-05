<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the deploy mirror form.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Alexandre Delaunay
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

include ("../../../inc/includes.php");
Session::checkLoginUser();

Html::header(__('Mirror servers'), $_SERVER["PHP_SELF"], "admin",
   "pluginfusioninventorymenu", "deploymirror");

//PluginFusioninventoryProfile::checkRight("Fusioninventory", "agents", "r");

PluginFusioninventoryMenu::displayMenu("mini");

$mirror = new PluginFusioninventoryDeployMirror();

if (isset ($_POST["add"])) {
   // PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package", "w");
   $newID = $mirror->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   // PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package", "w");
   $mirror->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   // PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package", "w");
   $mirror->delete($_POST);
   Html::redirect(Toolbox::getItemTypeFormURL('PluginFusioninventoryDeployMirror'));
}

$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}
$mirror->display(['id' => $id]);
//$mirror->showForm($id);
Html::footer();

