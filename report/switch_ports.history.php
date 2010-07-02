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

define('GLPI_ROOT', '../../..'); 
include (GLPI_ROOT . "/inc/includes.php"); 

PluginFusioninventoryProfile::checkRight("reports","r");

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER['PHP_SELF'],"utils","report");


if (isset($_GET["networkports_id"])) {
	$ports_id = $_GET["networkports_id"];
}

echo "<form action='".$_SERVER["PHP_SELF"]."' method='get'>";
echo "<table class='tab_cadre' cellpadding='5'>";
echo "<tr class='tab_bg_1' align='center'>";

echo "<td>";
echo $LANG["reports"][46]." :&nbsp;";

$query = "SELECT `glpi_networkequipments`.`name` as `name`, `glpi_networkports`.`name` as `pname`,
                 `glpi_networkports`.`id` as `id`
          FROM `glpi_networkequipments`
               LEFT JOIN `glpi_networkports` ON `items_id` = `glpi_networkequipments`.`id`
          WHERE `itemtype`='".NETWORKING_TYPE."'
          ORDER BY `glpi_networkequipments`.`name`, `glpi_networkports`.`logical_number`;";

$result=$DB->query($query);
      $selected = '';
while ($data=$DB->fetch_array($result)) {

   if ((isset($FK_port)) AND ($data['id'] == $FK_port)) {
      $selected = $data['id'];
   }
   $ports[$data['id']] = $data['name']." - ".$data['pname'];
}

Dropdown::showFromArray("networkports_id",$ports,
			               Array('value'=>$selected));
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td align='center'>";
echo "<input type='submit' value='Valider' class='submit' />";
echo "</td>";
echo "</tr>";

echo "</table></form>";

if(isset($_GET["networkports_id"])) {
   echo PluginFusinvsnmpNetworkPortLog::showHistory($_GET["networkports_id"]);
}

echo "</form>";

commonFooter(); 

?>
