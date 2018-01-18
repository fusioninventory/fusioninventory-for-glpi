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

$package = new PluginFusioninventoryDeployPackage();
if (isset($_POST['update_json'])) {
   $json_clean = stripcslashes($_POST['json']);

   $json = json_decode($json_clean, true);

   $ret = PluginFusioninventoryDeployPackage::updateOrderJson($_POST['packages_id'], $json);
   Html::back();
   exit;
} else if (isset($_POST['add_item'])) {
   $data = array_map(['Toolbox', 'stripslashes_deep'],
                     $package->escapeText($_POST));
   PluginFusioninventoryDeployPackage::alterJSON('add_item', $data);
   Html::back();
} else if (isset($_POST['save_item'])) {
   $data = array_map(['Toolbox', 'stripslashes_deep'],
                     $package->escapeText($_POST));
   PluginFusioninventoryDeployPackage::alterJSON('save_item', $data);
   Html::back();
} else if (isset($_POST['remove_item'])) {
   $data = array_map(['Toolbox', 'stripslashes_deep'],
                     $package->escapeText($_POST));
   PluginFusioninventoryDeployPackage::alterJSON('remove_item', $data);
   Html::back();
}

//$data = Toolbox::stripslashes_deep($_POST);
$data = $_POST;

//general form
if (isset ($data["add"])) {
   Session::checkRight('plugin_fusioninventory_package', CREATE);
   $newID = $package->add($data);
   Html::redirect(Toolbox::getItemTypeFormURL('PluginFusioninventoryDeployPackage')."?id=".$newID);
} else if (isset ($data["update"])) {
   Session::checkRight('plugin_fusioninventory_package', UPDATE);
   $package->update($data);
   Html::back();
} else if (isset ($data["purge"])) {
   Session::checkRight('plugin_fusioninventory_package', PURGE);
   $package->delete($data, 1);
   $package->redirectToList();
} else if (isset($_POST["addvisibility"])) {
   if (isset($_POST["_type"]) && !empty($_POST["_type"])
           && isset($_POST["plugin_fusioninventory_deploypackages_id"])
           && $_POST["plugin_fusioninventory_deploypackages_id"]) {
      $item = null;
      switch ($_POST["_type"]) {
         case 'User' :
            if (isset($_POST['users_id']) && $_POST['users_id']) {
               $item = new PluginFusioninventoryDeployPackage_User();
            }
            break;

         case 'Group' :
            if (isset($_POST['groups_id']) && $_POST['groups_id']) {
               $item = new PluginFusioninventoryDeployPackage_Group();
            }
            break;

         case 'Profile' :
            if (isset($_POST['profiles_id']) && $_POST['profiles_id']) {
               $item = new PluginFusioninventoryDeployPackage_Profile();
            }
            break;

         case 'Entity' :
            $item = new PluginFusioninventoryDeployPackage_Entity();
            break;
      }
      if (!is_null($item)) {
         $item->add($_POST);
         //         Event::log($_POST["plugin_fusioninventory_deploypackages_id"], "sla", 4, "tools",
         //                    //TRANS: %s is the user login
         //                    sprintf(__('%s adds a target'), $_SESSION["glpiname"]));
      }
   }
   Html::back();
}

Html::header(__('FusionInventory DEPLOY'), $_SERVER["PHP_SELF"], "admin",
   "pluginfusioninventorymenu", "deploypackage");
PluginFusioninventoryMenu::displayMenu("mini");
$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}
$package->display($_GET);
Html::footer();

