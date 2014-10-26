<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Alexandre Delaunay
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");
Session::checkLoginUser();


if (isset($_POST['update_json'])) {
   $order = new PluginFusioninventoryDeployOrder();

   $json_clean = stripcslashes($_POST['json']);

   $json = json_decode($json_clean,TRUE);

   $ret = PluginFusioninventoryDeployOrder::updateOrderJson($_POST['orders_id'], $json);
   Html::back();
   exit;
} elseif (isset($_POST['add_item'])) {
   $data = array_map(array('Toolbox', 'stripslashes_deep'), $_POST);
   PluginFusioninventoryDeployPackage::alter_json('add_item', $data);
   Html::back();
} elseif (isset($_POST['save_item'])) {
   $data = array_map(array('Toolbox', 'stripslashes_deep'), $_POST);
   PluginFusioninventoryDeployPackage::alter_json('save_item', $data);
   Html::back();
} elseif (isset($_POST['remove_item'])) {
   $data = array_map(array('Toolbox', 'stripslashes_deep'), $_POST);
   PluginFusioninventoryDeployPackage::alter_json('remove_item', $data);
   Html::back();
}

//$data = Toolbox::stripslashes_deep($_POST);
$data = $_POST;

$package = new PluginFusioninventoryDeployPackage();
//general form
if (isset ($data["add"])) {
   PluginFusioninventoryProfile::checkRight("packages", "w");
   $newID = $package->add($data);
   html::redirect(Toolbox::getItemTypeFormURL('PluginFusioninventoryDeployPackage')."?id=".$newID);
} else if (isset ($data["update"])) {
   PluginFusioninventoryProfile::checkRight("packages", "w");
   $package->update($data);
   Html::back();
} else if (isset ($data["delete"])) {
   PluginFusioninventoryProfile::checkRight("packages", "w");
   $package->delete($data);
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
