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
 * This file is used to manage the deploy package form.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

include ("../../../inc/includes.php");
Session::checkLoginUser();

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}

$template = new PluginFusioninventoryDeployUserinteractionTemplate();
//general form
if (isset ($_POST["add"])) {
   Session::checkRight('plugin_fusioninventory_userinteractiontemplate', CREATE);
   $newID = $template->add($_POST);
   Html::redirect($template->getFormURLWithID($newID));
} else if (isset ($_POST["update"])) {
   Session::checkRight('plugin_fusioninventory_userinteractiontemplate', UPDATE);
   $template->update($_POST);
   Html::back();
} else if (isset ($_POST["purge"])) {
   Session::checkRight('plugin_fusioninventory_userinteractiontemplate', PURGE);
   $template->delete($_POST, 1);
   $template->redirectToList();
}

if (isset($_GET['_in_modal']) && $_GET['_in_modal']) {
   Html::nullHeader(__('FusionInventory DEPLOY'), $_SERVER["PHP_SELF"]);
} else {
   Html::header(__('FusionInventory DEPLOY'), $_SERVER["PHP_SELF"], "admin",
      "pluginfusioninventorymenu", "deployuserinteractiontemplate");
   PluginFusioninventoryMenu::displayMenu("mini");
}
$template->display($_GET);
if (isset($_GET['_in_modal']) && $_GET['_in_modal']) {
   Html::nullFooter();
} else {
   Html::footer();
}
