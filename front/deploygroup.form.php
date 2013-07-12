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

if (isset($_REQUEST['type'])) {
   if ($_REQUEST['type'] == 'static') {
      $group_item = new PluginFusioninventoryDeployGroup_Staticdata();
   }
   if ($_REQUEST['type'] == 'dynamic') {
      $group_item = new PluginFusioninventoryDeployGroup_Dynamicdata();
   }  
}

if (isset($_POST["add"])) {
   $group->check(-1, 'w', $_POST);
   $newID = $group->add($_POST);
   Html::redirect($CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/deploygroup.form.php?id=".$newID);

} else if (isset($_POST["delete"])) {
   $group->check($_REQUEST['id'], 'd');
   $ok = $group->delete($_POST);

   $group->redirectToList();

} else if (isset($_REQUEST["purge"])) {
   $group->check($_REQUEST['id'], 'd');
   $ok = $group->delete($_REQUEST, 1);

   $group->redirectToList();

} else if (isset($_POST["update"])) {
   $group->check($_REQUEST['id'], 'w');
   $group->update($_POST);

   Html::back();

} else if (isset($_POST["additem"])) {
   //$group_item->check(-1, 'w', $_POST);

   if ($_REQUEST['type'] == 'static') {
      if (isset($_REQUEST["item"])) {
         if (count($_REQUEST["item"])) {
            foreach ($_REQUEST["item"] as $key => $val) {
               $group_item->add(array(
                  'groups_id' => $_REQUEST['groupID'],
                  'itemtype' => $_REQUEST['itemtype'],
                  'items_id' => $val
               ));
            }
         }
      }
   } elseif ($_REQUEST['type'] == 'dynamic') {
      $fields_array = array(
         'itemtype'              => $_REQUEST['itemtype'],
/*       'start'                 => $_REQUEST['start'],
         'limit'                 => $_REQUEST['limit'],*/
         'serial'                => $_REQUEST['serial'],
         'otherserial'           => $_REQUEST['otherserial'],
         'locations_id'          => $_REQUEST['locations_id'],
         'operatingsystems_id'   => $_REQUEST['operatingsystems_id'],
         'operatingsystem_name'  => $_REQUEST['____data_operatingsystems_id'],
         'room'                  => $_REQUEST['room'],
         'building'              => $_REQUEST['building'],
         'name'                  => $_REQUEST['name']
      );
      $group_item->add(array(
         'groups_id' => $_REQUEST['groupID'],
         'fields_array' => serialize($fields_array)
      ));
   }

   Html::back();
} else if (isset($_POST["updateitem"])) {
   //$group_item->check(-1, 'w', $_POST);
   if ($_REQUEST['type'] == 'dynamic') {
      $fields_array = array(
         'itemtype'              => $_REQUEST['itemtype'],
/*       'start'                 => $_REQUEST['start'],
         'limit'                 => $_REQUEST['limit'],*/
         'serial'                => $_REQUEST['serial'],
         'otherserial'           => $_REQUEST['otherserial'],
         'locations_id'          => $_REQUEST['locations_id'],
         'operatingsystems_id'   => $_REQUEST['operatingsystems_id'],
         'operatingsystem_name'  => $_REQUEST['____data_operatingsystems_id'],
         'room'                  => $_REQUEST['room'],
         'building'              => $_REQUEST['building'],
         'name'                  => $_REQUEST['name']
      );
      $group_item->update(array(
         'id' => $_REQUEST['id'],
         'fields_array' => serialize($fields_array)
      ));
   }

   Html::back();

} else if (isset($_REQUEST["deleteitem"])) {
   if ($_REQUEST['type'] == 'static') {
      if (count($_REQUEST["item"])) {
         foreach ($_REQUEST["item"] as $key => $val) {
            if ($group_item->can($key, 'w')) {
               $group_item->delete(array('id' => $key));
            }
         }
      }
   } elseif ($_REQUEST['type'] == 'dynamic') {

   }

   Html::back();

} else {
   Html::header(__('FusionInventory DEPLOY'), $_SERVER["PHP_SELF"], "plugins",
   "fusioninventory", "group");

   PluginFusioninventoryMenu::displayMenu("mini");
   
   if (!isset($_GET["id"])
           || $_GET["id"] == '') {
      $_GET["id"] = 0;
   }
   
   $group->showForm($_GET["id"]);
   Html::footer();
}

?>
