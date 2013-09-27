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
   @author    
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");


if (!isset($_GET["id"])) {
   $_GET["id"] = 0;
}

$collect = new PluginFusioninventoryInventoryComputerCollect();
$collectcontent = new PluginFusioninventoryInventoryComputerCollectcontent();

//Add a new collect
if (isset($_POST["add"])) {
   
$newID = $collect->add($_POST);
Html::redirect($_SERVER['HTTP_REFERER']."?id=$newID");

// delete a collect
} else if (isset($_REQUEST["purge"])) {
   $details = $collectcontent->find("plugin_fusioninventory_inventorycomputercollects_id = {$_POST['id']}");
   
   //delete the detail properties
   foreach ($details as $detail) {
      $collectcontent->delete($detail);
   }
   //delete the content
   $collect->delete($_POST,1);
   $collect->redirectToList();
//update a collect
} else if (isset($_POST["update"])) {

   $collect->getFromDB($_POST['id']);

   if ($collect->fields['plugin_fusioninventory_inventorycomputercollecttypes_id'] 
         != $_POST['plugin_fusioninventory_inventorycomputercollecttypes_id']) {
      $details = $collectcontent->find("plugin_fusioninventory_inventorycomputercollects_id = {$_POST['id']}");
      foreach ($details as $detail) {
         $collectcontent->delete($detail,1);
      }
   }
   
   $collect->update($_POST);
   Html::back();
} else {
   Html::header(__('Collect management', 'fusioninventory'),
                $_SERVER["PHP_SELF"],
                "plugins",
                "fusioninventory",
                "collect");
   $collect->showForm($_GET['id']);
   Html::footer();
}

?>