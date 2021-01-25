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
 * This file is used to manage the agent form.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

include ("../../../inc/includes.php");


$crontask = new PluginFusioninventoryCronTask();

Html::header(
    __('Cron tasks', 'fusioninventory'),
    $_SERVER['PHP_SELF'],
    "assets",
    'PluginFusioninventoryCronTask'
);

Session::checkRight('plugin_fusioninventory_crontask', READ);


if (isset($_POST["add"])) {
   $crontask->check(-1, CREATE, $_POST);
   $crontask->add($_POST);
   Html::back();

} else if (isset($_POST["update"])) {
   $crontask->check($_POST["id"], UPDATE);
   $crontask->update($_POST);
   Html::back();

} else if (isset($_POST["purge"])) {
   $crontask->check($_POST["id"], PURGE);
   $crontask->delete($_POST);
   Html::back();
} else {
   $id = "";
   if (isset($_GET["id"])) {
      $id = $_GET["id"];
   }
   $crontask->display([
      'id' => $id,
      'itemtype' => 'PluginFusioninventoryCronTask'
   ]);
}

Html::footer();
