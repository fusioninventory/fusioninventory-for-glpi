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

include ("../../../inc/includes.php");
Session::checkLoginUser();


if (isset($_REQUEST['update_json'])) {
   $order = new PluginFusioninventoryDeployOrder();

   //flatten json to update
   $json = json_encode(json_decode($_REQUEST['json'],TRUE));
   $order->update(
      array(
         'id' => $_REQUEST['id'],
         'json' => $json
      )
   );
   Html::back();
   exit;
} elseif (isset($_REQUEST['add_item'])) {
   PluginFusioninventoryDeployPackage::alter_json('add_item', $_REQUEST);
   Html::back();
} elseif (isset($_REQUEST['save_item'])) {
   PluginFusioninventoryDeployPackage::alter_json('save_item', $_REQUEST);
   Html::back();
} elseif (isset($_REQUEST['remove_item'])) {
   PluginFusioninventoryDeployPackage::alter_json('remove_item', $_REQUEST);
   Html::back();
}

$package = new PluginFusioninventoryDeployPackage();
//general form
if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("packages", "w");
   $newID = $package->add($_POST);
   html::redirect(Toolbox::getItemTypeFormURL('PluginFusioninventoryDeployPackage')."?id=".$newID);
} else if (isset ($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("packages", "w");
   $package->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("packages", "w");
   $package->delete($_POST);
   $package->redirectToList();
}


Html::header(__('FusionInventory DEPLOY'), $_SERVER["PHP_SELF"], "plugins",
   "fusioninventory", "packages");
PluginFusioninventoryMenu::displayMenu("mini");
$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}
PluginFusioninventoryProfile::checkRight( "packages", "r");
$package->showForm($id);
Html::footer();

?>
