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
 * This file is called by ajax function and display deploy type value.
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
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();
Session::checkCentralAccess();

$rand      = filter_input(INPUT_POST, "rand");
$mode      = filter_input(INPUT_POST, "mode");
$type      = filter_input(INPUT_POST, "type");
$classname = filter_input(INPUT_POST, "class");

if (empty($rand) && (empty($type))) {
   exit();
}
//Only process class that are related to software deployment
if (!class_exists($classname)
   || !in_array($classname,
               ['PluginFusioninventoryDeployCheck',
                'PluginFusioninventoryDeployFile',
                'PluginFusioninventoryDeployAction',
                'PluginFusioninventoryDeployUserinteraction'
               ])) {
   exit();
}
$class        = new $classname();
$request_data = [
    'packages_id' => filter_input(INPUT_POST, "packages_id"),
    'orders_id'   => filter_input(INPUT_POST, "orders_id"),
    'value'       => filter_input(INPUT_POST, "value")
];
$class->displayAjaxValues(null, $request_data, $rand, $mode);
