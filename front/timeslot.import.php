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
 * This file is used to import timeslots from a csv file.
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

$iprange = new PluginFusioninventoryTimeslot();

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"], "admin",
    "pluginfusioninventorymenu", "timeslot");

Session::checkRight('plugin_fusioninventory_task', READ);

PluginFusioninventoryMenu::displayMenu("mini");

if (isset($_FILES["importfile"])) {
    $file = $_FILES['importfile']['tmp_name'];
    include_once GLPI_ROOT.'/plugins/fusioninventory/scripts/import_timeslots.php';
} else {
    $target = $CFG_GLPI['root_doc'].
        '/plugins/fusioninventory/front/timeslot.import.php';

    echo "<form action='".$target."' method='post' enctype='multipart/form-data'>";

    echo "<br>";
    echo "<table class='tab_cadre' cellpadding='1' width='600'>";
    echo "<tr>";
    echo "<th>";
    echo __('Import time slots from a CSV file', 'fusioninventory')." :";
    echo "</th>";
    echo "</tr>";

    echo "<tr class='tab_bg_1'>";
    echo "<td align='center'>";
    echo "<input type='file' name='importfile' value=''/>";
    echo "&nbsp;<input type='submit' value='".__('Import')."' class='submit'/>";
    echo "</td>";
    echo "</tr>";

    echo "</table>";
}

Html::footer();

?>
