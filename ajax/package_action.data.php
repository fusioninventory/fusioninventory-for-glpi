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

global $DB,$LANG;

if(isset($_GET['package_id'])){
   $package_id = $_GET['package_id'];
   $render = $_GET['render'];
} else {
   exit;
}

$render_type   = PluginFusinvdeployOrder::getRender($render);
$order_id      = PluginFusinvdeployOrder::getIdForPackage($package_id,$render_type);

$sql = " SELECT id as {$render}id,
                itemtype as {$render}itemtype,
                items_id as {$render}items_id,
                ranking as {$render}ranking
         FROM `glpi_plugin_fusinvdeploy_actions`
         WHERE `plugin_fusinvdeploy_orders_id` = '$order_id'";

$qry  = $DB->query($sql);

$nb   = $DB->numrows($qry);
$res  = array();
while($row = $DB->fetch_array($qry)) {

   $itemtype = $row[$render.'itemtype'];
   $action   = new $itemtype();
   $action->getFromDB($row[$render.'items_id']);

   if($action instanceof PluginFusinvdeployAction_Command) {
      $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['label'][2]." : </b> ";
      $row[$render.'value'].= $action->getField('exec');

      $row[$render.'exec'] = $action->getField('exec');

   } else if($action instanceof PluginFusinvdeployAction_Move) {
      $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['label'][16]." : </b> ";
      $row[$render.'value'].= $action->getField('from');
      $row[$render.'value'].= " <b>".$LANG['plugin_fusinvdeploy']['form']['label'][17]." : </b> ";
      $row[$render.'value'].= $action->getField('to');

      $row[$render.'from'] = $action->getField('from');
      $row[$render.'to']   = $action->getField('to');

   }  else if($action instanceof PluginFusinvdeployAction_Delete) {
      $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['label'][2]." : </b> ";
      $row[$render.'value'].= $action->getField('path');
      $row[$render.'path']  = $action->getField('path');

   }  else if($action instanceof PluginFusinvdeployAction_Message) {
      $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['action_message'][1].
         " : </b> ";
      $row[$render.'value'].= $action->getField('name');
      $row[$render.'value'].= " <b>".$LANG['plugin_fusinvdeploy']['form']['action_message'][2].
         " : </b> ";
      $row[$render.'value'].= $action->getField('message');
      $row[$render.'value'].= " <b>".$LANG['plugin_fusinvdeploy']['form']['action_message'][3].
         " : </b> ";
      $row[$render.'value'].= $action->getField('type');

      $row[$render.'messagename']   = $action->getField('name');
      $row[$render.'messagevalue']  = $action->getField('message');
      $row[$render.'messagetype']   = $action->getField('type');
   }

   $res[$render.'actions'][] = $row;
}

echo json_encode($res);
?>
