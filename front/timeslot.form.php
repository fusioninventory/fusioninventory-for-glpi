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
 * This file is used to manage the task timeslot form.
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

Session::checkRight('plugin_fusioninventory_task', READ);

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}

$pfTimeslot = new PluginFusioninventoryTimeslot();
//Add a new timeslot
if (isset($_POST["add"])) {
   $pfTimeslot->check(-1, CREATE, $_POST);
   if ($newID = $pfTimeslot->add($_POST)) {
      if ($_SESSION['glpibackcreated']) {
         Html::redirect($pfTimeslot->getFormURL()."?id=".$newID);
      }
   }
   Html::back();

   // delete a timeslot
} else if (isset($_POST["delete"])) {
   $pfTimeslot->check($_POST['id'], DELETE);
   $ok = $pfTimeslot->delete($_POST);
   $pfTimeslot->redirectToList();

} else if (isset($_POST["purge"])) {
   $pfTimeslot->check($_POST['id'], PURGE);
   $pfTimeslot->delete($_POST, 1);
   $pfTimeslot->redirectToList();

   //update a timeslot
} else if (isset($_POST["update"])) {
   $pfTimeslot->check($_POST['id'], UPDATE);
   $pfTimeslot->update($_POST);
   Html::back();

} else {//print timeslot information
   Html::header(PluginFusioninventoryTimeslot::getTypeName(2),
                $_SERVER['PHP_SELF'],
                "admin",
                "pluginfusioninventorymenu",
                "timeslot");

   PluginFusioninventoryMenu::displayMenu("mini");
   $pfTimeslot->display(['id' => $_GET["id"]]);
   Html::footer();
}
