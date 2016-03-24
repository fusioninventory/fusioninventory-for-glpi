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

include ("../../../inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();
Session::checkCentralAccess();

if (isset($_REQUEST['move_item'])) { //ajax request

   $json_response = array(
      "success" => TRUE,
      "reason" => ''
   );

   if (Session::haveRight('plugin_fusioninventory_package', UPDATE)) {
      PluginFusioninventoryDeployPackage::alter_json('move_item', $_REQUEST);
   } else {
      $json_response['success'] = FALSE;
      $json_response['reason'] = __('Package modification is forbidden by your profile.');
   }

   echo json_encode( $json_response );
   exit;
}

if ( !isset($_REQUEST['orders_id']) && !isset($_REQUEST['rand']) && !isset($_REQUEST['subtype'])) {
   exit;
}

if ( !is_numeric($_REQUEST['orders_id']) ) {
   Toolbox::logDebug("Error: orders_id in request is not an integer");
   Toolbox::logDebug(var_dump($_REQUEST['orders_id']) );
   exit;
}

$order = new PluginFusioninventoryDeployOrder();

$order->getFromDB($_REQUEST['orders_id']);


//TODO: In the displayForm function, $_REQUEST is somewhat too much for the '$datas' parameter
// I think we could use only $order -- Kevin 'kiniou' Roy
switch ($_REQUEST['subtype']) {
   case 'check':
      PluginFusioninventoryDeployCheck::displayForm($order, $_REQUEST, $_REQUEST['rand'], $_POST['mode']);
      break;
   case 'file':
      PluginFusioninventoryDeployFile::displayForm($order, $_REQUEST, $_REQUEST['rand'], $_POST['mode']);
      break;
   case 'action':
      PluginFusioninventoryDeployAction::displayForm($order, $_REQUEST, $_REQUEST['rand'], $_POST['mode']);
      break;
   case 'package_json_debug':
      if ( isset($order->fields['json']) ) {
         PluginFusioninventoryDeployPackage::display_json_debug($order);
      } else {
         echo "{}";
      }
      break;
}
