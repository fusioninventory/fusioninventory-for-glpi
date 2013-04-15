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

$agent = new PluginFusioninventoryAgent();

Html::header($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"], "plugins", 
             "fusioninventory", "agents");

PluginFusioninventoryProfile::checkRight("fusioninventory", "agent", "r");

PluginFusioninventoryMenu::displayMenu("mini");

if (isset($_POST['startagent'])) {
   $taskjob = new PluginFusioninventoryTaskjob();

   if ($taskjob->startAgentRemotly($_POST['agent_id'])) {
       Session::addMessageAfterRedirect($LANG['plugin_fusioninventory']['agents'][17]);
   } else {
       Session::addMessageAfterRedirect($LANG['plugin_fusioninventory']['agents'][30]);
   }
   Html::back();
} else if (isset ($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "agent", "w");
   if (isset($_POST['items_id'])) {
      if (($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
         $_POST['itemtype'] = '1';
      }
   }
   $agent->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "agent", "w");
   $agent->delete($_POST);
   $agent->redirectToList();
} else if (isset ($_POST["startagent"])) {

   Html::back();
}



if (isset($_GET["id"])) {
   $agent->showForm($_GET["id"]);
} else {
   $agent->showForm("");
}

Html::footer();

?>