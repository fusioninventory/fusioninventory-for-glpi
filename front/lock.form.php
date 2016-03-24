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
   @author    Vincent Mazzoni
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

Session::checkLoginUser();

if(isset($_POST["unlock_field_fusioninventory"])){
   $typeright = strtolower($_POST['type']);
   if ($typeright == "networkequipment") {
      $typeright = "networking";
   }
   if (Session::haveRight($typeright, UPDATE)) {
      if (isset($_POST["lockfield_fusioninventory"])
              && count($_POST["lockfield_fusioninventory"])){
         $tab=PluginFusioninventoryLock::exportChecksToArray($_POST["lockfield_fusioninventory"]);
         PluginFusioninventoryLock::setLockArray($_POST['type'], $_POST["id"], $tab);
      } else {
         PluginFusioninventoryLock::setLockArray($_POST['type'], $_POST["id"], array());
      }
   }
   Html::back();
}

Html::footer();

?>
