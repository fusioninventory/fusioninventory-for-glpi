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

if (isset ($_POST["id"]) && $_POST['id']){

   //file uploaded?
   if (isset($_FILES['file']['tmp_name']) && !empty($_FILES['file']['tmp_name'])){
      $sum = sha1_file($_FILES['file']['tmp_name']);
      copy($_FILES['file']['tmp_name'], GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/".$sum);
      if (!isset($_POST['filename'])) {
         $_POST['filename'] = $_FILES['file']['name'];
      }
      
      $_POST['sha1sum'] = $sum;
      $filename         = $_POST['filename'];
      $extension        = explode(".", $_POST['filename']);
      $extension        = $extension[count($extension) - 1];
      $file_unchanged   = 0;
   //url?   
   } elseif(isset($_POST['url']) && !empty($_POST['url'])) {
      $sum = sha1_file($_POST['url']);
      copy($_POST['url'], GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/".$sum);
      if (!isset($_POST['filename'])) {
         $_POST['filename'] = basename($_POST['url']);
      }
      
      $_POST['sha1sum'] = $sum;
      $filename         = $_POST['filename'];
      $extension        = explode(".", $_POST['filename']);
      $extension        = $extension[count($extension) - 1];
      $file_unchanged   = 0;

   //file unchanged
   } else{
      $file_unchanged = 1;
   }

   $data = ($file_unchanged) 
            ? array( 'id'                 => $_POST['id'],
                     'is_p2p'             => (($_POST['p2p'] != 'false') ? 1 : 0), 
                     'p2p_retention_days' => (($_POST['p2p'] != 'false') ? $_POST['validity'] : 0),
                     'create_date'        => date('Y-m-d H:i:s')) 
            : array( 'id'                 => $_POST['id'],
                     'name'               => $_POST['filename'],
                     'is_p2p'             => (($_POST['p2p'] != 'false') ? 1 : 0), 
                     'p2p_retention_days' => (($_POST['p2p'] != 'false') ? $_POST['validity'] : 0),
                     'sha512'             => $sum,
                     'create_date'        => date('Y-m-d H:i:s'),
                     'type'               => $extension);

   $PluginFusinvdeployFile->update($data);
   echo "{success:true, file:'N/A',msg:\"{$LANG['plugin_fusinvdeploy']['form']['action'][4]}\"}";
}

?>