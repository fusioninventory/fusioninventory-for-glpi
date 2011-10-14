<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$agents = new PluginFusioninventoryAgent();

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","agents");

PluginFusioninventoryProfile::checkRight("fusioninventory", "agent","r");

PluginFusioninventoryMenu::displayMenu("mini");

if (isset($_POST['startagent'])) {
   $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
   $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
   $PluginFusioninventoryAgent->getFromDB($_POST['agent_id']);
   if ($PluginFusioninventoryTaskjob->RemoteStartAgent($_POST['ip'], $PluginFusioninventoryAgent->fields['token'])) {
      addMessageAfterRedirect($LANG['plugin_fusioninventory']['agents'][17]);
   } else {
      addMessageAfterRedirect($LANG['plugin_fusioninventory']['agents'][30]);
   }
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "agent","w");
   if (($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
      $_POST['itemtype'] = '1';
   }
   $agents->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "agent","w");
   if (isset($_POST['items_id'])) {
      if (($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
         $_POST['itemtype'] = '1';
      }
   }
   $agents->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "agent","w");
   $agents->delete($_POST);
   glpi_header("agent.php");
} else if (isset ($_POST["startagent"])) {

   glpi_header($_SERVER['HTTP_REFERER']);
}



if (isset($_GET["id"])) {
   $agents->showForm($_GET["id"]);
} else {
   $agents->showForm("");
}

commonFooter();

?>