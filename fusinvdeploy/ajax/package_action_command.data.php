<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Alexandre Delaunay
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");
Session::checkLoginUser();

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if(isset($_GET['package_id'])){
   $package_id = $_GET['package_id'];
   $render = $_GET['render'];
} else {
   exit;
}

$render_type   = PluginFusinvdeployOrder::getRender($render);
$order_id      = PluginFusinvdeployOrder::getIdForPackage($package_id,$render_type);

$sql = " SELECT `glpi_plugin_fusinvdeploy_actions_commands`.id as {$render}commands_id,
                `glpi_plugin_fusinvdeploy_actions_commands`.exec as {$render}commands_name
         FROM `glpi_plugin_fusinvdeploy_actions` as `glpi_plugin_fusinvdeploy_actions`
         INNER JOIN `glpi_plugin_fusinvdeploy_actions_commands`
                        as `glpi_plugin_fusinvdeploy_actions_commands`
               ON    `glpi_plugin_fusinvdeploy_actions_commands`.id
               =     `glpi_plugin_fusinvdeploy_actions`.items_id
         WHERE `glpi_plugin_fusinvdeploy_actions`.plugin_fusinvdeploy_orders_id = '$order_id'";

$qry  = $DB->query($sql);

$nb   = $DB->numrows($qry);
$res  = array();
while($row = $DB->fetch_array($qry)) {
   $res[$render.'commands'][] = $row;
}

echo json_encode($res);

?>