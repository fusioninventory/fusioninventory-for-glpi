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
Session::checkCentralAccess();

$rand = filter_input(INPUT_POST, "rand");
$mode = filter_input(INPUT_POST, "mode");
$type = filter_input(INPUT_POST, "type");

if (empty($rand) && (empty($type))) {
   exit();
}

switch ($type) {

   case 'check':
      $request_data = array(
          'packages_id' => filter_input(INPUT_POST, "packages_id"),
          'orders_id'   => filter_input(INPUT_POST, "orders_id"),
          'value'       => filter_input(INPUT_POST, "value")
      );
      PluginFusioninventoryDeployCheck::displayAjaxValues(NULL, $request_data, $rand, $mode);
      break;

   case 'file':
      $request_data = array(
          'packages_id' => filter_input(INPUT_POST, "packages_id"),
          'value'       => filter_input(INPUT_POST, "value")
      );
      PluginFusioninventoryDeployFile::displayAjaxValues( NULL, $request_data, $rand, $mode);
      break;

   case 'action':
      $request_data = array(
          'packages_id' => filter_input(INPUT_POST, "packages_id"),
          'values'      => filter_input(INPUT_POST, "values")
      );
      PluginFusioninventoryDeployAction::displayAjaxValues(NULL, $request_data, $mode);
      break;

}
