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
 * This file is called by ajax function and display deploy package form.
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

include ("../../../inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();
Session::checkCentralAccess();

$fi_move_item = filter_input(INPUT_POST, "move_item");
if (!empty($fi_move_item)) { //ajax request

   $json_response = array(
      "success" => TRUE,
      "reason"  => ''
   );

   if (Session::haveRight('plugin_fusioninventory_package', UPDATE)) {
      switch (filter_input(INPUT_POST, "itemtype")) {

         case "PluginFusioninventoryDeployCheck":
            switch ($action_type) {

               case "add_item" :
                  $params = array(
                      'value'            => filter_input(INPUT_POST, "value"),
                      'unit'             => filter_input(INPUT_POST, "unit"),
                      'deploy_checktype' => filter_input(INPUT_POST, "deploy_checktype"),
                      'path'             => filter_input(INPUT_POST, "path"),
                      'return'           => filter_input(INPUT_POST, "return"),
                      'id'               => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployCheck::add_item($params);
                  break;

               case "save_item" :
                  $params = array(
                      'value'            => filter_input(INPUT_POST, "value"),
                      'unit'             => filter_input(INPUT_POST, "unit"),
                      'deploy_checktype' => filter_input(INPUT_POST, "deploy_checktype"),
                      'path'             => filter_input(INPUT_POST, "path"),
                      'return'           => filter_input(INPUT_POST, "return"),
                      'index'            => filter_input(INPUT_POST, "index"),
                      'id'               => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployCheck::save_item($params);
                  break;

               case "remove_item" :
                  $params = array(
                      'check_entries' => filter_input(INPUT_POST, "check_entries"),
                      'id'            => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployCheck::remove_item($params);
                  break;

               case "move_item" :
                  $params = array(
                      'old_index' => filter_input(INPUT_POST, "old_index"),
                      'new_index' => filter_input(INPUT_POST, "new_index"),
                      'id'        => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployCheck::move_item($params);
                  break;

            }
            break;

         case "PluginFusioninventoryDeployFile":
            switch ($action_type) {

               case "add_item" :
                  $params = array(
                      'deploy_filetype' => filter_input(INPUT_POST, "deploy_filetype"),
                      'filename'        => filter_input(INPUT_POST, "filename"),
                      'id'              => filter_input(INPUT_POST, "id"),
                      'p2p'             => filter_input(INPUT_POST, "p2p"),
                      'uncompress'      => filter_input(INPUT_POST, "uncompress"),
                      'p2p-retention-duration' => filter_input(INPUT_POST, "p2p-retention-duration")
                  );
                  PluginFusioninventoryDeployFile::add_item($params);
                  break;

               case "save_item" :
                  $params = array(
                      'index'      => filter_input(INPUT_POST, "index"),
                      'id'         => filter_input(INPUT_POST, "id"),
                      'p2p'        => filter_input(INPUT_POST, "p2p"),
                      'uncompress' => filter_input(INPUT_POST, "uncompress"),
                      'p2p-retention-duration' => filter_input(INPUT_POST, "p2p-retention-duration")
                  );
                  PluginFusioninventoryDeployFile::save_item($params);
                  break;

               case "remove_item" :
                  $params = array(
                      'file_entries' => filter_input(INPUT_POST, "file_entries"),
                      'id'           => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployFile::remove_item($params);
                  break;

               case "move_item" :
                  $params = array(
                      'old_index' => filter_input(INPUT_POST, "old_index"),
                      'new_index' => filter_input(INPUT_POST, "new_index"),
                      'id'        => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployFile::move_item($params);
                  break;

            }
            break;

         case "PluginFusioninventoryDeployAction":
            switch ($action_type) {

               case "add_item" :
                  $params = array(
                      'list'              => filter_input(INPUT_POST, "list"),
                      'from'              => filter_input(INPUT_POST, "from"),
                      'to'                => filter_input(INPUT_POST, "to"),
                      'exec'              => filter_input(INPUT_POST, "exec"),
                      'retchecks_type'    => filter_input(INPUT_POST, "retchecks_type"),
                      'retchecks_value'   => filter_input(INPUT_POST, "retchecks_value"),
                      'deploy_actiontype' => filter_input(INPUT_POST, "deploy_actiontype"),
                      'id'                => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployAction::add_item($params);
                  break;

               case "save_item" :
                  $params = array(
                      'list'              => filter_input(INPUT_POST, "list"),
                      'from'              => filter_input(INPUT_POST, "from"),
                      'to'                => filter_input(INPUT_POST, "to"),
                      'exec'              => filter_input(INPUT_POST, "exec"),
                      'retchecks_type'    => filter_input(INPUT_POST, "retchecks_type"),
                      'retchecks_value'   => filter_input(INPUT_POST, "retchecks_value"),
                      'deploy_actiontype' => filter_input(INPUT_POST, "deploy_actiontype"),
                      'index'             => filter_input(INPUT_POST, "index"),
                      'id'                => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployAction::save_item($params);
                  break;

               case "remove_item" :
                  $params = array(
                      'action_entries' => filter_input(INPUT_POST, "action_entries"),
                      'id'             => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployAction::remove_item($params);
                  break;

               case "move_item" :
                  $params = array(
                      'old_index' => filter_input(INPUT_POST, "old_index"),
                      'new_index' => filter_input(INPUT_POST, "new_index"),
                      'id'        => filter_input(INPUT_POST, "id")
                  );
                  PluginFusioninventoryDeployAction::move_item($params);
                  break;

            }
            break;

         default :
            Toolbox::logDebug("package subtype not found : " . $params['itemtype']);
            Html::displayErrorAndDie ("package subtype not found");
            break;

      }
   } else {
      $json_response['success'] = FALSE;
      $json_response['reason'] = __('Package modification is forbidden by your profile.');
   }

   echo json_encode( $json_response );
   exit;
}

$orders_id = filter_input(INPUT_POST, "orders_id");
$rand = filter_input(INPUT_POST, "rand");
$mode = filter_input(INPUT_POST, "mode");

$fi_subtype = filter_input(INPUT_POST, "subtype");
if (empty($orders_id) && empty($rand)
        && empty($fi_subtype)) {
   exit;
}

if (!is_numeric($orders_id)) {
   Toolbox::logDebug("Error: orders_id in request is not an integer");
   Toolbox::logDebug(print_r($orders_id, TRUE));
   exit;
}

$pfDeployPackage = new PluginFusioninventoryDeployPackage();

$pfDeployPackage->getFromDB($orders_id);


//TODO: In the displayForm function, $_REQUEST is somewhat too much for the '$datas' parameter
// I think we could use only $order -- Kevin 'kiniou' Roy
$input = array(
    'index'       => filter_input(INPUT_POST, "index"),
    'value'       => filter_input(INPUT_POST, "value"),
    'packages_id' => filter_input(INPUT_POST, "packages_id"),
    'orders_id'   => filter_input(INPUT_POST, "orders_id"),
);
switch (filter_input(INPUT_POST, "subtype")) {

   case 'check':
      PluginFusioninventoryDeployCheck::displayForm(
              $pfDeployPackage, $input, $rand, $mode);
      break;

   case 'file':
      PluginFusioninventoryDeployFile::displayForm(
              $pfDeployPackage, $input, $rand, $mode);
      break;

   case 'action':
      PluginFusioninventoryDeployAction::displayForm(
              $pfDeployPackage, $input, $rand, $mode);
      break;

   case 'package_json_debug':
      if (isset($order->fields['json'])) {
         $pfDeployPackage->displayJSONDebug();
      } else {
         echo "{}";
      }
      break;

}
