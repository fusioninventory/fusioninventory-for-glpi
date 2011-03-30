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

if(isset($_GET['package_id'])){
   $package_id = $_GET['package_id'];
   $render = $_GET['render'];
} else {
   echo "{success:false, message:\"_GET['package_id'] not found\"}";
   exit;
}

foreach($_POST as $POST_key => $POST_value) {
   $new_key         = preg_replace('#^'.$render.'#','',$POST_key);
   $_POST[$new_key] = $POST_value;
}

$render_type   = PluginFusinvdeployOrder::getRender($render);
$order_id = PluginFusinvdeployOrder::getIdForPackage($package_id,$render_type);

$action = new PluginFusinvdeployAction;

if (isset ($_POST["id"]) && !$_POST['id']) {

   // Adding Sub-ACTION
   $itemtype = new $_POST['itemtype']();

   if($itemtype instanceof PluginFusinvdeployAction_Command) {
      $data = array( 'exec'   => $_POST['exec']);

   } else if($itemtype instanceof PluginFusinvdeployAction_Move){
      $data = array( 'from'   => $_POST['from'],
                     'to'     => $_POST['to']);

   } else if($itemtype instanceof PluginFusinvdeployAction_Delete) {
      $data = array( 'path'   => $_POST['path']);

   } else if($itemtype instanceof PluginFusinvdeployAction_Message) {
      $data = array( 'name'      => $_POST['messagename'],
                     'message'   => $_POST['messagevalue'],
                     'type'      => $_POST['messagetype']);
   }

   $items_id = $itemtype->add($data);

   // Adding ACTION
   $data   = array('itemtype'                       => $_POST['itemtype'],
                   'items_id'                       => $items_id,
                   'plugin_fusinvdeploy_orders_id'  => $order_id);

   $newId = $action->add($data);

   echo "{success:true, newId:$newId}";

} else if (isset ($_POST["id"]) && $_POST['id']) {

   $action = new PluginFusinvdeployAction();
   $action->getFromDB($_POST['id']);

   $items_id = $action->getField('items_id');
   $itemtype = $action->getField('itemtype');

   if ($_POST['itemtype'] == $itemtype) {
      $itemtype = new $_POST['itemtype']();
      $itemtype->getFromDB($items_id);

      if($itemtype instanceof PluginFusinvdeployAction_Command) {
         $data = array( 'exec'   => $_POST['exec']);

      } else if($itemtype instanceof PluginFusinvdeployAction_Move){
         $data = array( 'from'   => $_POST['from'],
                        'to'     => $_POST['to']);

      } else if($itemtype instanceof PluginFusinvdeployAction_Delete) {
         $data = array( 'path'   => $_POST['path']);

      } else if($itemtype instanceof PluginFusinvdeployAction_Message) {
         $data = array( 'name'      => $_POST['messagename'],
                        'message'   => $_POST['messagevalue'],
                        'type'      => $_POST['messagetype']);
      }

      $data['id'] = $items_id;
      $itemtype->update($data);
      echo "{success:true}";
   } else {
      $itemtype = new $itemtype;
      $itemtype->delete(array('id'=>$items_id));

      $itemtype = new $_POST['itemtype']();

      if($itemtype instanceof PluginFusinvdeployAction_Command) {
         $data = array( 'exec'   => $_POST['exec']);

      } else if($itemtype instanceof PluginFusinvdeployAction_Move){
         $data = array( 'from'   => $_POST['from'],
                        'to'     => $_POST['to']);

      } else if($itemtype instanceof PluginFusinvdeployAction_Delete) {
         $data = array( 'path'   => $_POST['path']);

      } else if($itemtype instanceof PluginFusinvdeployAction_Message) {
         $data = array( 'name'      => $_POST['messagename'],
                        'message'   => $_POST['messagevalue'],
                        'type'      => $_POST['messagetype']);
      }

      $items_id = $itemtype->add($data);

      $data   = array('id'                         =>  $_POST["id"],
                   'itemtype'                       => $_POST['itemtype'],
                   'items_id'                       => $items_id,
                   'plugin_fusinvdeploy_orders_id'  => $order_id);
      $action->update($data);

      echo "{success:true}";
   }

   exit();
}
