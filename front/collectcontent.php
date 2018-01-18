<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the collect content form.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

include ("../../../inc/includes.php");

Session::checkLoginUser();

if (!isset($_GET["id"])) {
   $_GET["id"] = 0;
}

$collect = new PluginFusioninventoryInventoryComputerCollectContent();

//Add a new collectcontent
if (isset($_POST["add"])) {
   //we need to rebuild the post.

   $data = [
       'plugin_fusioninventory_inventorycomputercollects_id' => $_POST['plugin_fusioninventory_inventorycomputercollects_id'],
       'plugin_fusioninventory_inventorycomputercollecttypes_id' =>
              $_POST['plugin_fusioninventory_inventorycomputercollecttypes_id'],
       'name' => $_POST['name']];

   switch ($_POST['plugin_fusioninventory_inventorycomputercollecttypes_id']) {

      case 1:
         $data['details'] = serialize([ 'hives_id' => $_POST['hives_id'],
                                             'path'     => $_POST['path'],
                                             'key'      => $_POST['key']]
            );
         break;

      //getFromWMI
      case 2:
         $data['details'] = serialize([ 'class' => $_POST['class'],
                                             'property'     => $_POST['property']]);
         break;

      //findFile
      case 3:
         $data['details'] = serialize([ 'path'         => $_POST['path'],
                                             'filename'     => $_POST['filename'],
                                             'getcontent'   => $_POST['getcontent']]);
         break;

      //runCommand
      case 4:
         $data['details'] = serialize([ 'path'         => $_POST['path'],
                                             'command'     => $_POST['command']]);
         break;
   }

   $collect->add($data);
   Html::back();
   // update the properties
} else if (isset($_POST["delete_x"])) {
   $collect->delete($_POST);
   Html::back();
} else { //shoudn't happen
   Html::back();
}

