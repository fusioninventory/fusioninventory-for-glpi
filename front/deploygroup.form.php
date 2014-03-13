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

if (!isset($_REQUEST["id"])) {
   $_REQUEST["id"] = "";
}

$group = new PluginFusioninventoryDeployGroup();

if (isset($_POST['save'])) {
   $group_item = new PluginFusioninventoryDeployGroup_Dynamicdata();
   if (!countElementsInTable($group_item->getTable(), "plugin_fusioninventory_deploygroups_id='".$_POST['id']."'")) {
      $values = array('plugin_fusioninventory_deploygroups_id' => $_POST['id'], 
                     'fields_array' => serialize($_POST['criteria']));
      $group_item->add($values);
   } else {
      $item = getAllDatasFromTable($group_item->getTable(), "plugin_fusioninventory_deploygroups_id='".$_POST['id']."'");
      $values = array_pop($item);
      $values['fields_array'] = serialize($_POST['criteria']);
      $group_item->update($values);
   }

   Html::back();
} elseif (isset($_POST["add"])) {
   $group->check(-1, UPDATE, $_POST);
   $newID = $group->add($_POST);
   Html::redirect(Toolbox::getItemTypeFormURL("PluginFusioninventoryDeployGroup")."?id=".$newID);

} else if (isset($_POST["delete"])) {
   $group->check($_REQUEST['id'], DELETE);
   $ok = $group->delete($_POST);

   $group->redirectToList();

} else if (isset($_REQUEST["purge"])) {
   $group->check($_REQUEST['id'], DELETE);
   $ok = $group->delete($_REQUEST, 1);

   $group->redirectToList();

} else if (isset($_POST["update"])) {
   $group->check($_REQUEST['id'], UPDATE);
   $group->update($_POST);

   Html::back();
} else {
   Html::header(__('FusionInventory DEPLOY'), $_SERVER["PHP_SELF"], "plugins",
                "pluginfusioninventorymenu", "deploygroup");

   PluginFusioninventoryMenu::displayMenu("mini");
   //$group->showForm($_GET['id']);
   $group->display(array('id' => $_REQUEST['id']));
   Html::footer();
}

?>