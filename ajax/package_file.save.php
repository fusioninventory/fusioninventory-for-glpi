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

$PluginFusinvdeployFile = new PluginFusinvdeployFile();

if(isset($_GET['package_id'])){
   $package_id = $_GET['package_id'];
   $render     = $_GET['render'];
} else {
   exit;
}

foreach($_POST as $POST_key => $POST_value) {
   $new_key         = preg_replace('#^'.$render.'#','',$POST_key);
   $_POST[$new_key] = $POST_value;
}

foreach($_FILES as $FILES_key => $FILES_value) {
   $new_key          = preg_replace('#^'.$render.'#','',$FILES_key);
   $_FILES[$new_key] = $FILES_value;
}

$render   = PluginFusinvdeployOrder::getRender($render);
$order_id = PluginFusinvdeployOrder::getIdForPackage($package_id,$render);

if (isset ($_POST["id"]) and !$_POST['id']) {

#logDebug($_POST);
#logDebug($_FILES);

  //file uploaded?
   $filename = null;
   if (isset($_FILES['file']['tmp_name']) and !empty($_FILES['file']['tmp_name'])){
      $filename = $_FILES['file']['tmp_name'];
   } elseif(isset($_POST['filename']) and !empty($_POST['filename'])) {
      $filename = $_POST['filename'];
   } /*elseif(isset($_POST['url']) and !empty($_POST['url'])) {
      $filename = $_POST['filename'];
   }*/
   if ($filename && $PluginFusinvdeployFile->addFileInRepo(array(
      'filename' => $filename,
      'is_p2p' => isset($_POST['p2p']) && $_POST['p2p'] != 'false',
      'p2p_retention_days' => (isset($_post['p2p']) && ($_post['p2p'] != 'false')) ? $_POST['validity'] : 0,
      'order_id' => $order_id
   ), $message)) {
      print "{success:true, file:'{$filename}',msg:\"{$LANG['plugin_fusinvdeploy']['form']['action'][4]}\"}";
      exit;
   } else {
      print "{success:false, file:'{$filename}',msg:\"{$message}\"}";
      exit;
   }
}
print "{success:false, file:'none',msg:\"{$LANG['plugin_fusinvdeploy']['form']['label'][15]}\"}";
