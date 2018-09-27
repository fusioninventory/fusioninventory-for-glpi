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
 * Manage the task form.
 */
include ("../../../inc/includes.php");

$pfTask = new PluginFusioninventoryTask();

//Submit the task form parameters
$pfTask->submitForm($_POST);

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"],
        "admin", "pluginfusioninventorymenu", "task");



Session::checkRight('plugin_fusioninventory_task', READ);

PluginFusioninventoryMenu::displayMenu("mini");

//PluginFusioninventoryTaskjob::isAllowurlfopen();

//If there is no form to submit, display the form
$pfTask->display($_GET);

Html::footer();

