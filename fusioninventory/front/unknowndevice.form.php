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

$pfUnknownDevice = new PluginFusioninventoryUnknownDevice();
$ptt  = new PluginFusioninventoryTask();

Html::header($LANG['plugin_fusioninventory']['title'][0], $_SERVER["PHP_SELF"], "plugins", "fusioninventory","unknown");

PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","r");

PluginFusioninventoryMenu::displayMenu("mini");

$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}
if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","w");
   if (isset($_POST['items_id']) 
          AND ($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
      $_POST['itemtype'] = '1';
   }
   $pfUnknownDevice->add($_POST);
   Html::back();
} else if (isset($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","w");

   $pfUnknownDevice->check($_POST['id'],'w');

   $pfUnknownDevice->delete($_POST);

   $pfUnknownDevice->redirectToList();
} else if (isset($_POST["restore"])) {
   
   $pfUnknownDevice->check($_POST['id'],'d');

   if ($pfUnknownDevice->restore($_POST)) {
      Event::log($_POST["id"],"PluginFusioninventoryUnknownDevice", 4, "inventory",
               $_SESSION["glpiname"]." ".$LANG['log'][23]." ".$pfUnknownDevice->getField('name'));
   }
   $pfUnknownDevice->redirectToList();

} else if (isset($_POST["purge"]) || isset($_GET["purge"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","w");

   $pfUnknownDevice->check($_POST['id'],'w');

   $pfUnknownDevice->delete($_POST,1);
   $pfUnknownDevice->redirectToList();
} else if (isset($_POST["update"])) {
   $pfUnknownDevice->check($_POST['id'],'w');
   $pfUnknownDevice->update($_POST);
   Html::back();
} else if (isset($_POST["import"])) {
   $Import = 0;
   $NoImport = 0;
   list($Import, $NoImport) = $pfUnknownDevice->import($_POST['id'],$Import,$NoImport);
    Session::addMessageAfterRedirect($LANG['plugin_fusioninventory']['discovery'][5]." : ".$Import);
    Session::addMessageAfterRedirect($LANG['plugin_fusioninventory']['discovery'][9]." : ".$NoImport);
   if ($Import == "0") {
      Html::back();
   } else {
      Html::redirect($CFG_GLPI['root_doc']."/plugins/fusioninventory/front/unknowndevice.php");
   }
}

$pfUnknownDevice->showForm($id);

Html::footer();

?>