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


if (isset($_REQUEST['update_json'])) {
   $order = new PluginFusioninventoryDeployOrder();

   //flatten json to update
   $json = json_decode($_REQUEST['json'], TRUE);

   $json_error_consts = array(
      JSON_ERROR_NONE => "JSON_ERROR_NONE",
      JSON_ERROR_DEPTH => "JSON_ERROR_DEPTH",
      JSON_ERROR_STATE_MISMATCH => "JSON_ERROR_STATE_MISMATCH",
      JSON_ERROR_CTRL_CHAR => "JSON_ERROR_CTRL_CHAR",
      JSON_ERROR_SYNTAX => "JSON_ERROR_SYNTAX",
   );

   if( version_compare(phpversion(), "5.3.3", "ge") ) {
      $json_error_consts[JSON_ERROR_UTF8] = "JSON_ERROR_UTF8";
   }

   $error_json = json_last_error();

   if ( $error_json != JSON_ERROR_NONE ) {
      $error_msg = "";

      $error_msg = $json_error_consts[$error_json];

      Session::addMessageAfterRedirect(
         __("The modified JSON contained a syntax error : <br/>", "fusioninventory") .
         $error_msg, FALSE, ERROR, FALSE
      );
      Html::back();
   } else {
      $order->update(
         array(
            'id' => $_REQUEST['id'],
            'json' => addslashes(json_encode($json))
         )
      );
      Html::back();
   }
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
   Session::checkRight('plugin_fusioninventory_packages', CREATE);
   $newID = $package->add($_POST);
   html::redirect(Toolbox::getItemTypeFormURL('PluginFusioninventoryDeployPackage')."?id=".$newID);
} else if (isset ($_POST["update"])) {
   Session::checkRight('plugin_fusioninventory_packages', UPDATE);
   $package->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   Session::checkRight('plugin_fusioninventory_packages', PURGE);
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
Session::checkRight('plugin_fusioninventory_packages', READ);
$package->showForm($id);
Html::footer();

?>
