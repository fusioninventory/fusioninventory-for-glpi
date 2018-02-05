<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

$pfCollect_File = new PluginFusioninventoryCollect_File();

if (isset($_POST["add"])) {
   // conversions
   if ($_POST['sizetype'] != 'none'
           && $_POST['size'] != '') {
      $_POST['filter_size'.$_POST['sizetype']] = $_POST['size'];
   }
   if ($_POST['filter_nametype'] != 'none'
           && $_POST['filter_name'] != '') {
      $_POST['filter_'.$_POST['filter_nametype']] = $_POST['filter_name'];
   }
   if ($_POST['type'] == 'file') {
      $_POST['filter_is_file'] = 1;
      $_POST['filter_is_dir'] = 0;
   } else {
      $_POST['filter_is_file'] = 0;
      $_POST['filter_is_dir'] = 1;
   }

   $pfCollect_File->add($_POST);
   Html::back();
} else if (isset($_POST["delete_x"])) {
   $pfCollect_File->delete($_POST);
   Html::back();
}

