<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

Html::header(__('Features'), $_SERVER["PHP_SELF"],
             "plugins", "fusioninventory", "deploygroup");


$pfDeployGroup = new PluginFusioninventoryDeployGroup();
$pfDeployGroup_Dynamicdata = new PluginFusioninventoryDeployGroup_Dynamicdata();

if (isset($_GET['updaterule'])) {
   if (!isset($_GET['contains'])
        AND !isset($_GET['reset'])) {

   } else {
      $_POST = $_GET;
      $input = array();
      $input['id'] = $_POST['plugin_fusioninventory_deploygroup_dynamicdatas_id'];
      unset($_POST['_glpi_csrf_token']);
      unset($_POST['start']);
      $input['fields_array'] = exportArrayToDB($_POST);
      $pfDeployGroup_Dynamicdata->update($input);
      Html::back();
   }
} else if (isset($_GET['contains'])
        OR isset($_GET['reset'])) {
   
   if (isset($_SESSION['plugin_fusioninventory_dynamicgroup'])) {
      unset($_SESSION['plugin_fusioninventory_dynamicgroup']);
   }
   $_SESSION['plugin_fusioninventory_dynamicgroup'] = $_GET;
   $_SESSION['plugin_fusioninventory_dynamicgroup_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
   Html::back();
}
if (isset($_POST['update_group'])) {
   $pfDeployGroup_Dynamicdata->update($_POST);
   Html::back();
}
if (isset($_POST['name'])) {      
   $a_construct = array();
   foreach ($_POST as $key=>$value) {
      $a_construct[] = $key."=".$value;
   }
   $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI']."?".implode("&", $a_construct);
   Html::redirect($_SERVER['REQUEST_URI']);
}

$pfDeployGroup->showForm($_GET['id']);

Html::footer();

?>