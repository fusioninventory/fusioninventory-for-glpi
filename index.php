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

include ("../../inc/includes.php");

//Agent posting an inventory or asking for orders using REST
$rawdata = file_get_contents("php://input");
$action = filter_input(INPUT_GET, "action");
$machineid = filter_input(INPUT_GET, "machineid");
if ((!empty($action)
   && !empty($machineid))
      || !empty($rawdata)) {

   include_once("front/communication.php");

   //Fusioninventory plugin pages
} else {
   Html::header(__('FusionInventory', 'fusioninventory'), filter_input(INPUT_SERVER, "PHP_SELF"), "plugins",
                "fusioninventory");

   Html::redirect($CFG_GLPI['root_doc']."/plugins/fusioninventory/front/menu.php");
   Html::footer();
}

