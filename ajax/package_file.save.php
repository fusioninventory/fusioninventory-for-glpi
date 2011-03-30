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

logDebug($_POST);

  //file uploaded?
   if (isset($_FILES['file']['tmp_name']) and !empty($_FILES['file']['tmp_name'])){
       if (!isset($_POST['filename'])) {
         $_POST['filename'] = $_FILES['file']['name'];
      }
	   
	   
	   // Data for table _files
	   
	   
	   $sum 		= hash_file('sha512', $_FILES['file']['tmp_name']);
	   $shortsum 	= substr($sum,0,6);
	   $extension 	= $PluginFusinvdeployFile->getExtension($_POST['filename']);
	   $filename   	= $_POST['filename'];
	  
	  
	   //Data for file spliting script
	   $Sp_parts	= $PluginFusinvdeployFile->getNumberOfPartsFromFilesize($_FILES['file']['size']);
	   $Sp_file		= $_FILES['file']['tmp_name'];
	  
       //copy($_FILES['file']['tmp_name'], GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/".$sum);
     
      
      
   //url?   
   } elseif(isset($_POST['url']) and !empty($_POST['url'])) {
      if (!isset($_POST['filename'])) {
         $_POST['filename'] = basename($_POST['url']);
      }
      
	  
	  
	  $sum 			= hash_file('sha512', $_POST['url']);
	  $shortsum 	= substr($sum,0,6);
      $filename     = $_POST['filename'];
      $extension	= $PluginFusinvdeployFile->getExtension($_POST['filename']);
	  
	  $Sp_parts		= $PluginFusinvdeployFile->getNumberOfPartsFromFilesize(filesize($_POST['url']));
	  $Sp_file 		= $_POST['url'];
	  
   } else {
      echo "{success:false, file:'{$filename}',msg:\"{$LANG['plugin_fusinvdeploy']['form']['label'][15]}\"}";
      exit();
   }
   
   $data = array( 'name'                          => $_POST['filename'],
                  'is_p2p'                        => (($_POST['p2p'] != 'false') ? 1 : 0), 
                  'p2p_retention_days'            => (($_POST['p2p'] != 'false') ? 
                                                         $_POST['validity'] : 0),
                  'sha512'                        => $sum,
                  'shortsha512'                   => $shortsum,
                  'create_date'                   => date('Y-m-d H:i:s'), 
                  'type'                          => $extension,
                  'plugin_fusinvdeploy_orders_id' => $order_id);

   $file_id	= $PluginFusinvdeployFile->add($data);
   $PluginFusinvdeployFile->splitfile($Sp_file,$Sp_parts,$order_id,$file_id);
   
   echo "{success:true, file:'{$data['name']}',msg:\"{$LANG['plugin_fusinvdeploy']['form']['action'][4]}\"}";
}