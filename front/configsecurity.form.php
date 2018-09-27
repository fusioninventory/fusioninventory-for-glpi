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

/*
 * Manage the configuration security form.
 */
include ("../../../inc/includes.php");

Session::checkRight('plugin_fusioninventory_configsecurity', READ);

$pfConfigSecurity = new PluginFusioninventoryConfigSecurity();
$config = new PluginFusioninventoryConfig();

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"], "admin",
         "pluginfusioninventorymenu", "configsecurity");

PluginFusioninventoryMenu::displayMenu("mini");


if (isset ($_POST["add"])) {
   Session::checkRight('plugin_fusioninventory_configsecurity', CREATE);
   $new_ID = 0;
   $new_ID = $pfConfigSecurity->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   Session::checkRight('plugin_fusioninventory_configsecurity', UPDATE);
   $pfConfigSecurity->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   Session::checkRight('plugin_fusioninventory_configsecurity', PURGE);
   $pfConfigSecurity->delete($_POST);
   Html::redirect("configsecurity.php");
}

$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}

if (strstr($_SERVER['HTTP_REFERER'], "wizard.php")) {
   Html::redirect($_SERVER['HTTP_REFERER']."&id=".$id);
}

$pfConfigSecurity->showForm($id);

Html::footer();

