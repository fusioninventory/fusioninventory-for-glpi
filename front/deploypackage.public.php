<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

/*
 * Manage the deploy package self-service form.
 */
include ("../../../inc/includes.php");
Session::checkLoginUser();

Html::helpHeader(__('FusionInventory'), $_SERVER["PHP_SELF"], "plugins",
                 "pluginfusioninventorymenu", "deploypackage");
$pfDeployPackage = new PluginFusioninventoryDeployPackage();

if (isset($_POST['prepareinstall'])) {
   $computers_id = false;

   foreach ($_POST as $key => $data) {
      if (strstr($key, 'deploypackages_')) {
         $computers_id = str_replace('deploypackages_', '', $key);
         foreach ($data as $packages_id) {
            $pfDeployPackage->deployToComputer($computers_id, $packages_id, $_SESSION['glpiID']);
         }
      }
   }

   //Try to wakeup the agent to perform the deployment task
   //If it's a local wakeup, local call to the agent RPC service
   switch ($_POST['wakeup_type']) {
      case 'local':
         echo '<link rel="import" href="http://127.0.0.1:62354/now">';
         echo Html::scriptBlock("setTimeout(function(){
            window.location='{$_SERVER['HTTP_REFERER']}';
         }, 500);");
         exit;
         break;
      case 'remote':
         if ($computers_id) {
            //Remote call to wakeup the agent, from the server
            $agent = new PluginFusioninventoryAgent();
            $agent->getAgentWithComputerid($computers_id);
            $agent->wakeUp();
         }
         break;
      default:
         break;
   }

   Html::back();
} else {
   Html::header(__('FusionInventory'), $_SERVER["PHP_SELF"], "plugins",
                "pluginfusioninventorymenu", "deploypackage");

   $pfDeployPackage->showPackageForMe($_SESSION['glpiID']);
   Html::footer();
}
