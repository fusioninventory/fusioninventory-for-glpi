<?php
/*
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Alexandre DELAUNAY
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/inc/includes.php");

if (!isset($_REQUEST["id"])) {
   $_REQUEST["id"] = "";
}

$group = new PluginFusinvdeployGroup();

if (isset($_REQUEST['type'])) {
   if ($_REQUEST['type'] == 'static') $group_item = new PluginFusinvdeployGroup_Staticdata();
   if ($_REQUEST['type'] == 'dynamic') $group_item = new PluginFusinvdeployGroup_Dynamicdata();
}

if (isset($_POST["add"])) {
   $group->check(-1, 'w', $_POST);
   $newID = $group->add($_POST);
   glpi_header(GLPI_ROOT."/plugins/fusinvdeploy/front/group.form.php?id=".$newID);

} else if (isset($_POST["delete"])) {
   $group->check($_REQUEST['id'], 'd');
   $ok = $group->delete($_POST);

   $group->redirectToList();

} else if (isset($_REQUEST["purge"])) {
   $group->check($_REQUEST['id'], 'd');
   $ok = $group->delete($_REQUEST,1);

   $group->redirectToList();

} else if (isset($_POST["update"])) {
   $group->check($_REQUEST['id'], 'w');
   $group->update($_POST);

   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["additem"])) {
   //$group_item->check(-1,'w',$_POST);

   if ($_REQUEST['type'] == 'static') {
      if (count($_REQUEST["item"])) {
         foreach ($_REQUEST["item"] as $key => $val) {
            $group_item->add(array(
               'groups_id' => $_REQUEST['groupID'],
               'itemtype' => $_REQUEST['itemtype'],
               'items_id' => $val
            ));
         }
      }
   } elseif ($_REQUEST['type'] == 'dynamic') {
      $fields_array = array(
         'itemtype'  => $_REQUEST['itemtype'],
/*         'start'  => $_REQUEST['start'],
         'limit'  => $_REQUEST['limit'],*/
         'serial'  => $_REQUEST['serial'],
         'otherserial'  => $_REQUEST['otherserial'],
         'locations'  => $_REQUEST['locations'],
         'room'  => $_REQUEST['room'],
         'building'  => $_REQUEST['building'],
      );
      $group_item->add(array(
         'groups_id' => $_REQUEST['groupID'],
         'fields_array' => serialize($fields_array)
      ));
   }

   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["updateitem"])) {
   //$group_item->check(-1,'w',$_POST);
   if ($_REQUEST['type'] == 'dynamic') {
      $fields_array = array(
         'itemtype'  => $_REQUEST['itemtype'],
         /*'start'  => $_REQUEST['start'],
         'limit'  => $_REQUEST['limit'],*/
         'serial'  => $_REQUEST['serial'],
         'otherserial'  => $_REQUEST['otherserial'],
         'locations'  => $_REQUEST['locations'],
         'room'  => $_REQUEST['room'],
         'building'  => $_REQUEST['building'],
      );
      $group_item->update(array(
         'id' => $_REQUEST['id'],
         'fields_array' => serialize($fields_array)
      ));
   }

   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_REQUEST["deleteitem"])) {
   if ($_REQUEST['type'] == 'static') {
      if (count($_REQUEST["item"])) {
         foreach ($_REQUEST["item"] as $key => $val) {
            if ($group_item->can($key,'w')) {
               $group_item->delete(array('id' => $key));
            }
         }
      }
   } elseif ($_REQUEST['type'] == 'dynamic') {

   }

   glpi_header($_SERVER['HTTP_REFERER']);

} else {
   commonHeader($LANG['plugin_fusinvdeploy']["title"][0],$_SERVER["PHP_SELF"],"plugins",
   "fusioninventory","task");

   PluginFusioninventoryMenu::displayMenu("mini");

   $group->showForm($_REQUEST["id"]);
   commonFooter();
}

?>
