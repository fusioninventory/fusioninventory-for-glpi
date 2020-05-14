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
 * This file is used to manage the unmanaged device form.
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

$pfUnmanaged = new PluginFusioninventoryUnmanaged();
$ptt  = new PluginFusioninventoryTask();

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"],
        "assets", "pluginfusioninventoryunmanaged");


Session::checkRight('plugin_fusioninventory_unmanaged', READ);

PluginFusioninventoryMenu::displayMenu("mini");

$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}
if (isset ($_POST["add"])) {
   Session::checkRight('plugin_fusioninventory_unmanaged', CREATE);
   if (isset($_POST['items_id'])
          && ($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
      $_POST['itemtype'] = '1';
   }
   $pfUnmanaged->add($_POST);
   Html::back();
} else if (isset($_POST["delete"])) {
   Session::checkRight('plugin_fusioninventory_unmanaged', PURGE);

   $pfUnmanaged->check($_POST['id'], DELETE);

   $pfUnmanaged->delete($_POST);

   $pfUnmanaged->redirectToList();
} else if (isset($_POST["restore"])) {

   $pfUnmanaged->check($_POST['id'], DELETE);

   if ($pfUnmanaged->restore($_POST)) {
      Event::log($_POST["id"], "PluginFusioninventoryUnmanaged", 4, "inventory",
               $_SESSION["glpiname"]." ".__('restoration of the item', 'fusioninventory')." ".
               $pfUnmanaged->getField('name'));
   }
   $pfUnmanaged->redirectToList();

} else if (isset($_POST["purge"]) || isset($_GET["purge"])) {
   Session::checkRight('plugin_fusioninventory_unmanaged', PURGE);

   $pfUnmanaged->check($_POST['id'], PURGE);

   $pfUnmanaged->delete($_POST, 1);
   $pfUnmanaged->redirectToList();
} else if (isset($_POST["update"])) {
   $pfUnmanaged->check($_POST['id'], UPDATE);
   $pfUnmanaged->update($_POST);
   Html::back();
} else if (isset($_POST["import"])) {
   $Import = 0;
   $NoImport = 0;
   list($Import, $NoImport) = $pfUnmanaged->import($_POST['id'], $Import, $NoImport);
    Session::addMessageAfterRedirect(
            __('Number of imported devices', 'fusioninventory')." : ".$Import);
    Session::addMessageAfterRedirect(
            __('Number of devices not imported because type not defined', 'fusioninventory').
            " : ".$NoImport);
   if ($Import == "0") {
      Html::back();
   } else {
      Html::redirect(Plugin::getWebDir('fusioninventory')."/front/unmanaged.php");
   }
}

$pfUnmanaged->display($_GET);

Html::footer();

