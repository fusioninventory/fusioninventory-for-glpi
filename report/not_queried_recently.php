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

$NEEDED_ITEMS=array("search", "computer", "infocom", "setup", "networking", "printer");

include ("../../../inc/includes.php");

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER['PHP_SELF'], "utils", "report");

Session::checkRight('plugin_fusioninventory_reportnetworkequipment', READ);

$nbdays = 1;
if (isset($_GET["nbdays"])) {
   $nbdays = $_GET["nbdays"];
}
$state = '';
if (isset($_GET["state"])) {
   $state = $_GET["state"];
}

echo "<form action='".$_SERVER["PHP_SELF"]."' method='get'>";
echo "<table class='tab_cadre' cellpadding='5'>";

echo "<tr class='tab_bg_1' align='center'>";
echo "<td>";
echo __('Number of days since last inventory')." :&nbsp;";
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




if(isset($_GET["FK_networking_ports"])) {
   echo PluginFusioninventoryNetworkPortLog::showHistory($_GET["FK_networking_ports"]);
}

Html::closeForm();

$state_sql = "";
if (($state != "") AND ($state != "0")) {
   $state_sql = " AND `states_id` = '".$state."' ";
}

$query = "SELECT * FROM (
SELECT `glpi_networkequipments`.`name`, `last_fusioninventory_update`, `serial`, `otherserial`,
   `networkequipmentmodels_id`, `glpi_networkequipments`.`id` as `network_id`, 0 as `printer_id`,
   `plugin_fusioninventory_configsecurities_id`,
   `glpi_ipaddresses`.`name` as ip, `states_id`
   FROM `glpi_plugin_fusioninventory_networkequipments`
JOIN `glpi_networkequipments` on `networkequipments_id` = `glpi_networkequipments`.`id`
LEFT JOIN `glpi_networkports`
   ON (`glpi_networkequipments`.`id` = `glpi_networkports`.`items_id`
       AND `glpi_networkports`.`itemtype` = 'NetworkEquipment')
LEFT JOIN `glpi_networknames`
     ON `glpi_networknames`.`items_id`=`glpi_networkports`.`id`
        AND `glpi_networknames`.`itemtype`='NetworkPort'
LEFT JOIN `glpi_ipaddresses`
     ON `glpi_ipaddresses`.`items_id`=`glpi_networknames`.`id`
        AND `glpi_ipaddresses`.`itemtype`='NetworkName'
WHERE ((NOW() > ADDDATE(last_fusioninventory_update, INTERVAL ".$nbdays." DAY) OR last_fusioninventory_update IS NULL)
   ".$state_sql.")
UNION
SELECT `glpi_printers`.`name`, `last_fusioninventory_update`, `serial`, `otherserial`,
   `printermodels_id`, 0 as `network_id`, `glpi_printers`.`id` as `printer_id`,
   `plugin_fusioninventory_configsecurities_id`,
   `glpi_ipaddresses`.`name` as ip, `states_id`
   FROM `glpi_plugin_fusioninventory_printers`
JOIN `glpi_printers` on `printers_id` = `glpi_printers`.`id`
LEFT JOIN `glpi_networkports`
   ON (`glpi_printers`.`id` = `glpi_networkports`.`items_id`
       AND `glpi_networkports`.`itemtype` = 'Printer')
LEFT JOIN `glpi_networknames`
     ON `glpi_networknames`.`items_id`=`glpi_networkports`.`id`
        AND `glpi_networknames`.`itemtype`='NetworkPort'
LEFT JOIN `glpi_ipaddresses`
     ON `glpi_ipaddresses`.`items_id`=`glpi_networknames`.`id`
        AND `glpi_ipaddresses`.`itemtype`='NetworkName'
WHERE (NOW() > ADDDATE(last_fusioninventory_update, INTERVAL ".$nbdays." DAY) OR last_fusioninventory_update IS NULL)
AND `glpi_networkports`.`items_id`='Printer' ".$state_sql.") as `table`

ORDER BY last_fusioninventory_update DESC";

echo "<table class='tab_cadre' cellpadding='5' width='950'>";
echo "<tr class='tab_bg_1'>";
echo "<th>".__('Name')."</th>";
echo "<th>".__('Last inventory')."</th>";
echo "<th>".__('Item type')."</th>";
echo "<th>".__('IP')."</th>";
echo "<th>".__('Serial Number')."</th>";
echo "<th>".__('Inventory number')."</th>";
echo "<th>".__('Model')."</th>";
echo "<th>".__('SNMP authentication')."</th>";
echo "<th>".__('Status')."</th>";
echo "</tr>";

if ($result=$DB->query($query)) {
   while ($data=$DB->fetch_array($result)) {
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      if ($data['network_id'] > 0) {
         $class = new NetworkEquipment();
         $class->getFromDB($data['network_id']);
      } else if ($data['printer_id'] > 0) {
         $class = new Printer();
         $class->getFromDB($data['printer_id']);
      }
      echo $class->getLink(1);
      echo "</td>";
      echo "<td>".Html::convDateTime($data['last_fusioninventory_update'])."</td>";
      echo "<td>";
      if ($data['network_id'] > 0) {
         echo __('Networks');

      } else if ($data['printer_id'] > 0) {
         echo __('Printers');

      }
      echo "</td>";
      echo "<td>".$data['ip']."</td>";
      echo "<td>".$data['serial']."</td>";
      echo "<td>".$data['otherserial']."</td>";
      if ($data['network_id'] > 0) {
         echo "<td>".Dropdown::getDropdownName("glpi_networkequipmentmodels", $data['networkequipmentmodels_id'])."</td>";
      } else if ($data['printer_id'] > 0) {
         echo "<td>".Dropdown::getDropdownName("glpi_printermodels", $data['printermodels_id'])."</td>";
      }
      echo "<td>";
      echo Dropdown::getDropdownName('glpi_plugin_fusioninventory_configsecurities', $data['plugin_fusioninventory_configsecurities_id']);
      echo "</td>";
      echo "<td>";
      echo Dropdown::getDropdownName(getTableForItemType("State"), $data['states_id']);
      echo "</td>";
      echo "</tr>";
   }
}
echo "</table>";

Html::footer();

?>
