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
 * This file is used to display the network equipment ports history.
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

//Options for GLPI 0.71 and newer : need slave db to access the report
$USEDBREPLICATE=1;
$DBCONNECTION_REQUIRED=0;

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

Html::header(__('FusionInventory', 'fusioninventory'), filter_input(INPUT_SERVER, "PHP_SELF"), "utils", "report");

Session::checkRight('plugin_fusioninventory_reportnetworkequipment', READ);

$FK_port = filter_input(INPUT_GET, "networkports_id");

echo "<form action='".filter_input(INPUT_SERVER, "PHP_SELF")."' method='get'>";
echo "<table class='tab_cadre' cellpadding='5'>";
echo "<tr class='tab_bg_1' align='center'>";

echo "<td>";
echo _n('Network port', 'Network ports', 1)." :&nbsp;";

$query = "SELECT `glpi_networkequipments`.`name` as `name`, `glpi_networkports`.`name` as `pname`,
                 `glpi_networkports`.`id` as `id`
          FROM `glpi_networkequipments`
               LEFT JOIN `glpi_networkports` ON `items_id` = `glpi_networkequipments`.`id`
          WHERE `itemtype`='NetworkEquipment'
          ORDER BY `glpi_networkequipments`.`name`, `glpi_networkports`.`logical_number`;";

$result=$DB->query($query);
      $selected = '';
while ($data=$DB->fetchArray($result)) {

   if (($data['id'] == $FK_port)) {
      $selected = $data['id'];
   }
   $ports[$data['id']] = $data['name']." - ".$data['pname'];
}

Dropdown::showFromArray("networkports_id", $ports,
                        ['value'=>$selected]);
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td align='center'>";
echo "<input type='submit' value='" . __('Validate')  . "' class='submit' />";
echo "</td>";
echo "</tr>";

echo "</table>";
Html::closeForm();

$networkports_id = filter_input(INPUT_GET, "networkports_id");
if ($networkports_id != '') {
   echo PluginFusioninventoryNetworkPortLog::showHistory($networkports_id);
}

Html::closeForm();

Html::footer();

