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
 * This file is used to manage the index page of the plugin.
 * It can be use in GLPI or by URL defined in agent to redirect to
 * fron/communication.php page.
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

   Html::redirect(Plugin::getWebDir('fusioninventory')."/front/menu.php");
   Html::footer();
}

