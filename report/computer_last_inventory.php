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

//Options for GLPI 0.71 and newer : need slave db to access the report
$USEDBREPLICATE=1;
$DBCONNECTION_REQUIRED=0;

include ("../../../inc/includes.php");

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER['PHP_SELF'], "utils", "report");

Session::checkRight('computer', READ);

$nbdays = 365;
if (isset($_GET["nbdays"])) {
   $nbdays = $_GET["nbdays"];
}
$state = '';
if (isset($_GET["state"])) {
   $state = $_GET["state"];
}

echo "<form action='".$_SERVER["PHP_SELF"]."' method='get'>";
echo "<table class='tab_cadre' cellpadding='5'>";

echo "<tr>";
echo "<th colspan='2'>";
echo __('Computers not inventoried since xx days', 'fusioninventory');
echo "</th>";
echo "</tr>";

echo "<tr class='tab_bg_1' align='center'>";
echo "<td>";
echo __('Number of days (minimum) since last inventory', 'fusioninventory')." :&nbsp;";
echo "</td>";
echo "<td>";
Dropdown::showNumber("nbdays", array(
                'value' => $nbdays,
                'min'   => 1,
                'max'   => 365)
);
echo "</td>";
echo "</tr>";

echo "<tr class='tab_bg_1' align='center'>";
echo "<td>";
echo __('Status');
echo "</td>";
echo "<td>";
Dropdown::show("State", array('name'=>'state', 'value'=>$state));
echo "</td>";
echo "</tr>";

echo "<tr class='tab_bg_2'>";
echo "<td align='center' colspan='2'>";
echo "<input type='submit' value='Valider' class='submit' />";
echo "</td>";
echo "</tr>";

echo "</table>";
Html::closeForm();

$computer = new Computer();

$state_sql = "";
if (($state != "") AND ($state != "0")) {
   $state_sql = " AND `states_id` = '".$state."' ";
}

$query = "SELECT `last_fusioninventory_update`, `computers_id`
      FROM `glpi_plugin_fusioninventory_inventorycomputercomputers`
   LEFT JOIN `glpi_computers` ON `computers_id`=`glpi_computers`.`id`
WHERE ((NOW() > ADDDATE(last_fusioninventory_update, INTERVAL ".$nbdays." DAY)
      OR last_fusioninventory_update IS NULL)
   ".$state_sql.")".getEntitiesRestrictRequest("AND", "glpi_computers")."

ORDER BY last_fusioninventory_update DESC";

$result = $DB->query($query);

echo "<table class='tab_cadre_fixe' cellpadding='5' width='950'>";

echo "<tr class='tab_bg_1'>";
echo "<th colspan='5'>".__('Number of items')." : ".$DB->numrows($result)."</th>";
echo "</tr>";

echo "<tr class='tab_bg_1'>";
echo "<th>".__('Name')."</th>";
echo "<th>".__('Last inventory')."</th>";
echo "<th>".__('Serial Number')."</th>";
echo "<th>".__('Inventory number')."</th>";
echo "<th>".__('Status')."</th>";
echo "</tr>";

while ($data=$DB->fetch_array($result)) {
   echo "<tr class='tab_bg_1'>";
   echo "<td>";
   $computer->getFromDB($data['computers_id']);
   echo $computer->getLink(1);
   echo "</td>";
   echo "<td>".Html::convDateTime($data['last_fusioninventory_update'])."</td>";
   echo "<td>".$computer->fields['serial']."</td>";
   echo "<td>".$computer->fields['otherserial']."</td>";
   echo "<td>";
   echo Dropdown::getDropdownName(getTableForItemType("State"), $computer->fields['states_id']);
   echo "</td>";
   echo "</tr>";
}

echo "</table>";

Html::footer();

?>
