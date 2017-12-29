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
 * This file is used to display the printer log report page.
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

$USEDBREPLICATE=1;
$DBCONNECTION_REQUIRED=0;

include ("../../../inc/includes.php");

Html::header(__('FusionInventory', 'fusioninventory'),
             $_SERVER["PHP_SELF"],
             "admin",
             "pluginfusioninventorymenu",
             "printerlogreport");

Session::checkRight('plugin_fusioninventory_reportprinter', READ);

if (isset($_POST['glpi_plugin_fusioninventory_date_start'])) {
   $_SESSION['glpi_plugin_fusioninventory_date_start'] =
                                 $_POST['glpi_plugin_fusioninventory_date_start'];
}
if (isset($_POST['glpi_plugin_fusioninventory_date_end'])) {
   $_SESSION['glpi_plugin_fusioninventory_date_end'] =
                                 $_POST['glpi_plugin_fusioninventory_date_end'];
}

if (isset($_POST['reset'])) {
   unset($_SESSION['glpi_plugin_fusioninventory_date_start']);
   unset($_SESSION['glpi_plugin_fusioninventory_date_end']);
}

if ((!isset($_SESSION['glpi_plugin_fusioninventory_date_start']))
       OR (empty($_SESSION['glpi_plugin_fusioninventory_date_start']))) {
   $_SESSION['glpi_plugin_fusioninventory_date_start'] = "2000-01-01";
}
if (!isset($_SESSION['glpi_plugin_fusioninventory_date_end'])) {
   $_SESSION['glpi_plugin_fusioninventory_date_end'] = date("Y-m-d");
}

displaySearchForm();

$_GET['target']="printerlogreport.php";

Search::show('PluginFusioninventoryPrinterLogReport');


/**
 * Display special search form
 *
 * @global array $_SERVER
 */
function displaySearchForm() {
   global $_SERVER;

   echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'>";
   echo "<table class='tab_cadre' cellpadding='5'>";
   echo "<tr class='tab_bg_1' align='center'>";
   echo "<td>";
   echo __('Starting date', 'fusioninventory')." :";
   echo "</td>";
   echo "<td width='120'>";
   Html::showDateField("glpi_plugin_fusioninventory_date_start",
                       ['value' => $_SESSION['glpi_plugin_fusioninventory_date_start']]);
   echo "</td>";

   echo "<td>";
   echo __('Ending date', 'fusioninventory')." :";
   echo "</td>";
   echo "<td width='120'>";
   Html::showDateField("glpi_plugin_fusioninventory_date_end",
                       ['value' => $_SESSION['glpi_plugin_fusioninventory_date_end']]);
   echo "</td>";

   echo "<td>";
   echo "<input type='submit' name='reset' value='reset' class='submit' />";
   echo "</td>";

   echo "<td>";
   echo "<input type='submit' value='". __('Validate') . "' class='submit' />";
   echo "</td>";

   echo "</tr>";
   echo "</table>";
   Html::closeForm();
}


Html::footer();

