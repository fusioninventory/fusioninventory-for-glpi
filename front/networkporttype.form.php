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

Session::checkRight('config', UPDATE);

$pfNetworkporttype = new PluginFusioninventoryNetworkporttype();

if (isset($_POST['type_to_add'])) {
   foreach ($_POST['type_to_add'] as $id) {
      $input = [];
      $input['id'] = $id;
      $input['import'] = 1;
      $pfNetworkporttype->update($input);
   }
   Html::back();
} else if (isset($_POST['type_to_delete'])) {
   foreach ($_POST['type_to_delete'] as $id) {
      $input = [];
      $input['id'] = $id;
      $input['import'] = 0;
      $pfNetworkporttype->update($input);
   }
   Html::back();
}

