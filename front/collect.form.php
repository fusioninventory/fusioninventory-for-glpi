<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

Html::header(__('Collect management', 'fusioninventory'),
             $_SERVER["PHP_SELF"],
             "admin",
             "pluginfusioninventorymenu",
             "collect");

$pfCollect = new PluginFusioninventoryCollect();

if (isset($_POST["add"])) {
   $collects_id = $pfCollect->add($_POST);
   Html::redirect(Toolbox::getItemTypeFormURL('PluginFusioninventoryCollect').
           "?id=".$collects_id);
} else if (isset($_POST["update"])) {
   $pfCollect->update($_POST);
   Html::back();
} else if (isset($_REQUEST["purge"])) {
   $pfCollect->delete($_POST);
   $pfCollect->redirectToList();
}

PluginFusioninventoryMenu::displayMenu("mini");

if (!isset($_GET["id"])) {
   $_GET['id'] = '';
}
$pfCollect->display($_GET);

Html::footer();

