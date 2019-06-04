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
 * This file is used to manage the deploy group form.
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

$group = new PluginFusioninventoryDeployGroup();

if (isset($_GET['plugin_fusioninventory_deploygroups_id'])) {
    $_SESSION['glpisearch']['PluginFusioninventoryComputer'] = $_GET;
}

if (isset($_GET['save'])) {
   $group_item = new PluginFusioninventoryDeployGroup_Dynamicdata();
   if (!countElementsInTable($group_item->getTable(),
                             ['plugin_fusioninventory_deploygroups_id' => $_GET['id']])) {
      $criteria  = ['criteria'     => $_GET['criteria'],
                         'metacriteria' => $_GET['metacriteria']];
      $values['fields_array'] = serialize($criteria);
      $values['plugin_fusioninventory_deploygroups_id'] = $_GET['id'];
      $group_item->add($values);
   } else {
      $item = getAllDataFromTable($group_item->getTable(),
                                   ['plugin_fusioninventory_deploygroups_id' => $_GET['id']]);
      $values                 = array_pop($item);

      $criteria = ['criteria'     => $_GET['criteria'],
                        'metacriteria' => $_GET['metacriteria']];
      $values['fields_array'] = serialize($criteria);
      $group_item->update($values);
   }

   Html::redirect(Toolbox::getItemTypeFormURL("PluginFusioninventoryDeployGroup")."?id=".$_GET['id']);
} else if (isset($_FILES['importcsvfile'])) {
   PluginFusioninventoryDeployGroup_Staticdata::csvImport($_POST, $_FILES);
   Html::back();
} else if (isset($_POST["add"])) {
   $group->check(-1, UPDATE, $_POST);
   $newID = $group->add($_POST);
   Html::redirect(Toolbox::getItemTypeFormURL("PluginFusioninventoryDeployGroup")."?id=".$newID);

} else if (isset($_POST["delete"])) {
   //   $group->check($_POST['id'], DELETE);
   $ok = $group->delete($_POST);

   $group->redirectToList();

} else if (isset($_POST["purge"])) {
   //   $group->check($_POST['id'], DELETE);
   $ok = $group->delete($_REQUEST, 1);

   $group->redirectToList();

} else if (isset($_POST["update"])) {
   $group->check($_POST['id'], UPDATE);
   $group->update($_POST);

   Html::back();
} else {
   Html::header(__('FusionInventory DEPLOY'), $_SERVER["PHP_SELF"], "admin",
                "pluginfusioninventorymenu", "deploygroup");

   PluginFusioninventoryMenu::displayMenu("mini");
   $values       = $_POST;
   if (!isset($_GET['id'])) {
      $id = '';
   } else {
      $id = $_GET['id'];
      if (isset($_GET['sort']) AND isset($_GET['order'])) {
         $group->getFromDB($id);
         PluginFusioninventoryDeployGroup::getSearchParamsAsAnArray($group, true);
      }
   }
   $values['id'] = $id;
   if (isset($_GET['preview'])) {
      $values['preview'] = $_GET['preview'];
   }
   $group->display($values);
   Html::footer();
}

