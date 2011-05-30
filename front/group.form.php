<?php
/*
 * @version $Id: computer.form.php 13703 2011-01-20 12:24:21Z remi $
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

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}

$group = new PluginFusinvdeployGroup();
$staticdata = new PluginFusinvdeployGroup_Staticdata();

if (isset($_POST["add"])) {
   $group->check(-1, 'w', $_POST);
   $newID = $group->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);


} else if (isset($_POST["delete"])) {
   $group->check($_POST['id'], 'd');
   $ok = $group->delete($_POST);

   $group->redirectToList();

} else if (isset($_REQUEST["purge"])) {
   $group->check($_REQUEST['id'], 'd');
   $ok = $group->delete($_REQUEST,1);

   $group->redirectToList();

} else if (isset($_POST["update"])) {
   $group->check($_POST['id'], 'w');
   $group->update($_POST);

   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["additem"])) {
   $staticdata->check(-1,'w',$_POST);
   $staticdata->add($_POST);

   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_REQUEST["deleteitem"])) {
   if (count($_REQUEST["item"])) {
      foreach ($_REQUEST["item"] as $key => $val) {
         if ($staticdata->can($key,'w')) {
            $staticdata->delete(array('id' => $key));
         }
      }
   }

   glpi_header($_SERVER['HTTP_REFERER']);

} else {
   commonHeader($LANG['plugin_fusinvdeploy']["title"][0],$_SERVER["PHP_SELF"],"plugins",
   "fusioninventory","task");

   PluginFusioninventoryMenu::displayMenu("mini");

   $group->showForm($_GET["id"]);
   commonFooter();
}

?>
