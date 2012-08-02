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

foreach($_POST as $POST_key => $POST_value) {
   $new_key         = preg_replace('#^'.$render.'#','',$POST_key);
   $_POST[$new_key] = $POST_value;
}

$render   = PluginFusinvdeployOrder::getRender($render);
$order_id = PluginFusinvdeployOrder::getIdForPackage($package_id,$render);

$check = new PluginFusinvdeployCheck;

if (($_POST['type'] == 'fileSizeGreater'
      || $_POST['type'] == 'fileSizelower'
      || $_POST['type'] == 'fileSizeEquals'
      || $_POST['type'] == 'freespaceGreater'
   )
         && $_POST['unit'] == 'GiB') {
   $_POST['value'] *= 1024;
}
unset($_POST['unit']);


if (isset ($_POST["id"]) && !$_POST['id']) {

   $data = array( 'type'                          => $_POST['type'],
                  'path'                          => $_POST['path'],
                  'value'                         => $_POST['value'],
                  'plugin_fusinvdeploy_orders_id' => $order_id);


   //get max previous ranking
   $sql_ranking = "SELECT ranking FROM ".$check->getTable()."
      WHERE plugin_fusinvdeploy_orders_id = '$order_id' ORDER BY ranking DESC";
   $res_ranking = $DB->query($sql_ranking);
   if ($DB->numrows($res_ranking) == 0) $ranking = 0;
   else {
      $data_ranking = $DB->fetch_array($res_ranking);
      $ranking = $data_ranking['ranking']+1;
   }
   $data['ranking'] = $ranking;

   $check->add($data);
   echo "{success:true}";

} else if (isset ($_POST["id"]) && $_POST['id']) {
   //PluginFusioninventoryProfile::checkRight("fusinvdeploy", "files","w");
   $check->update($_POST);

   echo "{success:true}";
   exit();
}

?>