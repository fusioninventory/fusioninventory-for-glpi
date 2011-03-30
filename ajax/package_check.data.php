<?php
/*
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Alexandre delaunay
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

global $DB;

if(isset($_GET['package_id'])){
   $package_id = $_GET['package_id'];
   $render     = $_GET['render'];
} else {
   exit;
}

$render_type   = PluginFusinvdeployOrder::getRender($render);
$order_id      = PluginFusinvdeployOrder::getIdForPackage($package_id,$render_type);
$sql           = "SELECT id as {$render}id,
                      type as {$render}type,
                      path as {$render}path,
                      value as {$render}value,
                      IF (type = 'freespaceGreater' OR type = 'fileSize', '".$LANG['common'][82]."', '') as {$render}unit
                   FROM `glpi_plugin_fusinvdeploy_checks`
                   WHERE `plugin_fusinvdeploy_orders_id` = '$order_id'";

$qry  = $DB->query($sql);
$nb   = $DB->numrows($qry);
$res  = array();

while($row = $DB->fetch_array($qry)) {
   $res[$render.'checks'][] = $row;
}

echo json_encode($res);
?>
