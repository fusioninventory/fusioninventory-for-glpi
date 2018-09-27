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

/*
 * Manage the collect registry form.
 */
include ("../../../inc/includes.php");

$pfCollect_Registry = new PluginFusioninventoryCollect_Registry();

if (isset($_POST["add"])) {
   if (!preg_match('/^\/()/', $_POST['path'])) {
      $_POST['path'] = "/".$_POST['path'];
   }
   if (!preg_match('/\/$/', $_POST['path'])) {
      $_POST['path'] = $_POST['path']."/";
   }

   $pfCollect_Registry->add($_POST);
   Html::back();
} else if (isset($_POST["delete_x"])) {
   $pfCollect_Registry->delete($_POST);
   Html::back();
}

