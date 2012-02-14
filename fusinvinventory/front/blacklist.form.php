<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","fusinvinventory-blacklist");

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
} else if (isset ($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusinvinventory", "blacklist","w");
   $PluginFusinvinventoryBlacklist->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusinvinventory", "blacklist","w");
   $PluginFusinvinventoryBlacklist->delete($_POST);
   glpi_header("blacklist.php");
}

if (isset($_GET["id"])) {
   $PluginFusinvinventoryBlacklist->showForm($_GET["id"]);
} else {
   $PluginFusinvinventoryBlacklist->showForm("");
}

commonFooter();

?>