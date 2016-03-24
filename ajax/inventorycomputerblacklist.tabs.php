<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if(!isset($_POST["id"])) {
   exit();
}

if (!isset($_POST["sort"])) {
   $_POST["sort"] = "";
}
if (!isset($_POST["order"])) {
   $_POST["order"] = "";
}
if (!isset($_POST["withtemplate"])) {
   $_POST["withtemplate"] = "";
}

$pfInventoryComputerBlacklist = new PluginFusioninventoryInventoryComputerBlacklist();
$pfInventoryComputerCriteria = new PluginFusioninventoryInventoryComputerCriteria();
$fields = $pfInventoryComputerCriteria->find("");

foreach ($fields as $id=>$data) {
   if (($id == $_POST['glpi_tab']) OR ($_POST['glpi_tab'] == "-1")) {
      //$pfBlacklist->showArray($id);
      echo "<br/>";
      $pfInventoryComputerBlacklist->addForm($id);
   }
}

Html::ajaxFooter();

?>