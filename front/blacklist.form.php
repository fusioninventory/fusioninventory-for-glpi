<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org//
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory plugins.

   FusionInventory is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","fusinvinventory-blacklist");

PluginFusioninventoryProfile::checkRight("fusinvinventory", "blacklist","r");

PluginFusioninventoryMenu::displayMenu("mini");

$PluginFusinvinventoryBlacklist = new PluginFusinvinventoryBlacklist();

if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusinvinventory", "blacklist","w");
   if (!empty($_POST['value'])) {
      $PluginFusinvinventoryBlacklist->add($_POST);
   } else {
      //TODO message
   }
   glpi_header($_SERVER['HTTP_REFERER']);
}
//} else if (isset ($_POST["update"])) {
//   PluginFusioninventoryProfile::checkRight("fusioninventory", "agents","w");
//   if (isset($_POST['items_id'])) {
//      if (($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
//         $_POST['itemtype'] = '1';
//      }
//   }
//   $agents->update($_POST);
//   glpi_header($_SERVER['HTTP_REFERER']);
//} else if (isset ($_POST["delete"])) {
//   PluginFusioninventoryProfile::checkRight("fusioninventory", "agents","w");
//   $agents->delete($_POST);
//   glpi_header("agent.php");
//} else if (isset ($_POST["startagent"])) {
//
//   glpi_header($_SERVER['HTTP_REFERER']);
//}


$PluginFusinvinventoryBlacklist->showTabs();
//if (isset($_GET["id"])) {
//   $PluginFusinvinventoryBlacklist->showForm("");
//} else {
//   $PluginFusinvinventoryBlacklist->showForm("");
//}
$PluginFusinvinventoryBlacklist->addDivForTabs();

commonFooter();

?>