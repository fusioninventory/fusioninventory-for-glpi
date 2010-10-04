<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of GLPI.

   GLPI is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

//Options for GLPI 0.71 and newer : need slave db to access the report
$USEDBREPLICATE=1;
$DBCONNECTION_REQUIRED=0;

$NEEDED_ITEMS=array("search","computer","infocom","setup","networking","printer");

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

plugin_fusioninventory_checkRight("reports","r");

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER['PHP_SELF'],"utils","report");

$nbdays = 1;
if (isset($_GET["nbdays"])) {
	$nbdays = $_GET["nbdays"];
}

echo "<form action='".$_SERVER["PHP_SELF"]."' method='get'>";
echo "<table class='tab_cadre' cellpadding='5'>";
echo "<tr class='tab_bg_1' align='center'>";

echo "<td>";
echo $LANG['plugin_fusioninventory']["report"][0]." :&nbsp;";

dropdownInteger("nbdays", $nbdays, 1, 365);

echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td align='center'>";
echo "<input type='submit' value='Valider' class='submit' />";
echo "</td>";
echo "</tr>";

echo "</table></form>";




if(isset($_GET["FK_networking_ports"])) {

   echo plugin_fusioninventory_snmp_showHistory($FK_port, 0);
}

echo "</form>";


$query = "SELECT * FROM (
SELECT `name`, `last_fusioninventory_update`, `serial`, `otherserial`,
   `model`, `glpi_networking`.`ID` as `network_id`, 0 as `printer_id`,
   `FK_model_infos`, `FK_snmp_connection` FROM `glpi_plugin_fusioninventory_networking`
JOIN `glpi_networking` on `FK_networking` = `glpi_networking`.`ID`
WHERE (NOW() > ADDDATE(last_fusioninventory_update, INTERVAL ".$nbdays." DAY) OR last_fusioninventory_update IS NULL)
UNION
SELECT `name`, `last_fusioninventory_update`, `serial`, `otherserial`,
   `model`, 0 as `network_id`, `glpi_printers`.`ID` as `printer_id`,
   `FK_model_infos`, `FK_snmp_connection` FROM `glpi_plugin_fusioninventory_printers`
JOIN `glpi_printers` on `FK_printers` = `glpi_printers`.`ID`
WHERE (NOW() > ADDDATE(last_fusioninventory_update, INTERVAL ".$nbdays." DAY) OR last_fusioninventory_update IS NULL)
) as `table`
ORDER BY last_fusioninventory_update DESC";

$CommonItem = new CommonItem;

echo "<table class='tab_cadre' cellpadding='5' width='950'>";
echo "<tr class='tab_bg_1'>";
echo "<th>".$LANG['common'][16]."</th>";
echo "<th>".$LANG['plugin_fusioninventory']["snmp"][52]."</th>";
echo "<th>".$LANG['state'][6]."</th>";
echo "<th>".$LANG['common'][19]."</th>";
echo "<th>".$LANG['common'][20]."</th>";
echo "<th>".$LANG['common'][22]."</th>";
echo "<th>".$LANG['plugin_fusioninventory']["profile"][24]."</th>";
echo "<th>".$LANG['plugin_fusioninventory']["model_info"][3]."</th>";
echo "</tr>";

if ($result=$DB->query($query)) {
   while ($data=$DB->fetch_array($result)) {
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      if ($data['network_id'] > 0) {
         $CommonItem->getFromDB(NETWORKING_TYPE,
                                   $data['network_id']);
      } else if ($data['printer_id'] > 0) {
         $CommonItem->getFromDB(PRINTER_TYPE,
                                   $data['printer_id']);
      }
      echo $CommonItem->getLink(1);
      echo "</td>";
      echo "<td>".convDateTime($data['last_fusioninventory_update'])."</td>";
      echo "<td>";
      if ($data['network_id'] > 0) {
         echo $LANG['Menu'][1];
      } else if ($data['printer_id'] > 0) {
         echo $LANG['Menu'][2];
      }
      echo "</td>";
      echo "<td>".$data['serial']."</td>";
      echo "<td>".$data['otherserial']."</td>";
      if ($data['network_id'] > 0) {
         echo "<td>".getDropdownName("glpi_dropdown_model_networking", $data['model'])."</td>";
      } else if ($data['printer_id'] > 0) {
         echo "<td>".getDropdownName("glpi_dropdown_model_printers", $data['model'])."</td>";
      }
      echo "<td>";
      echo getDropdownName('glpi_plugin_fusioninventory_model_infos', $data['FK_model_infos']);
      echo "</td>";
      echo "<td>";
      echo getDropdownName('glpi_plugin_fusioninventory_snmp_connection', $data['FK_snmp_connection']);
      echo "</td>";
      echo "</tr>";
   }
}
echo "</table>";

commonFooter();

?>