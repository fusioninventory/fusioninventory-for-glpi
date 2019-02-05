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

/**
 * Called by ajax function and display dropdown task definition type list.
 */
if (strpos(filter_input(INPUT_SERVER, "PHP_SELF"), "dropdowndefinitiontypelist.php")) {
   include ("../../../inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}
if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

Session::checkCentralAccess();
$pfTaskjob = new PluginFusioninventoryTaskjob;
$pfTaskjob->dropdownDefinition(
        "DefinitionList",
        filter_input(INPUT_POST, "DefinitionType"),
        filter_input(INPUT_POST, "method"),
        filter_input(INPUT_POST, "deftypeid"),
        filter_input(INPUT_POST, "taskjobs_id"));

