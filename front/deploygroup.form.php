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
//Store $_REQUEST params for further use : it's ugly, 
//but I cannot find a best solution...
$group->setSearchParams($_REQUEST);

if (isset($_POST['save'])) {
   $group_item = new PluginFusioninventoryDeployGroup_Dynamicdata();
   if (!countElementsInTable($group_item->getTable(), 
                             "plugin_fusioninventory_deploygroups_id='".$_POST['plugin_fusioninventory_deploygroups_id']."'")) {
      $group_item->add($_POST);
   } else {
      $item = getAllDatasFromTable($group_item->getTable(), 
                                   "plugin_fusioninventory_deploygroups_id='".$_POST['plugin_fusioninventory_deploygroups_id']."'");
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
   if (!isset($_POST['preview'])) {
      //Remove session variable to clean search engine criteria
      unset($_SESSION['glpisearch']['PluginFusioninventoryComputer']);
   }
   //Store groups_id for further use
   $_SESSION['plugin_fusioninventory_group_search_id'] = $_REQUEST['id'];
   
   $group->display(array('id' => $_REQUEST['id']));
   $res = PluginFusioninventoryDeployGroup::getTargetsForGroup($_REQUEST['id']);
   Toolbox::logDebug($res);
   Html::footer();
}

?>