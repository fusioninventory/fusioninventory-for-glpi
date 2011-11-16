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
// Original Author of file: Alexandre Delaunay
// Purpose of file:
// ----------------------------------------------------------------------


// Start the session for this page
session_start();
header("Cache-control: private");

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
checkLoginUser();

// Turn off error reporting
error_reporting(0);

// Include main config file
include ("../lib/extjs/FileChooser/includes/config.inc.php");

// Include common functions
include ("../lib/extjs/FileChooser/includes/functions.inc.php");

// Include image transform class
include ("../lib/extjs/FileChooser/includes/classes/imagetransform.class.php");

$plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');
$PluginFusioninventoryConfig = new PluginFusioninventoryConfig;
$server_upload_path = $PluginFusioninventoryConfig->getValue($plugins_id, 'server_upload_path');
// Setup some variables
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'download') {
   $_POST['action'] = $_REQUEST['action'];
   $_POST['directory'] = $_REQUEST['directory'];
} else $_REQUEST['action'] = '';

if (isset($_REQUEST['directory']) && $_REQUEST['directory']) {
   $directory = $server_upload_path . $_REQUEST['directory'];
} else {
   $directory = $server_upload_path;
}

switch ($_REQUEST['action']) {
   default:
      $dir = opendir($directory);
      $i = 0;

      $results = array();

      // Get a list of all the files in the directory
      while ($temp = readdir($dir)) {
         if (stristr($temp, '_fm_')) continue; // If this is a temp file, skip it.
         if (isset($_POST['images_only']) && $_POST['images_only'] && !preg_match('/\.(jpeg|jpg|gif|png)$/', $temp)) continue; // If it isnt an image, skip it
         if (is_dir($directory . "/" . $temp)) continue; // If its a directory skip it

         $results[$i]['name'] = $temp;
         $results[$i]['size'] = filesize($directory . '/' . $temp);
         $results[$i]['type'] = filetype($directory . '/' . $temp);
         $results[$i]['permissions'] = format_permissions(fileperms($directory . '/' . $temp));
         $results[$i]['ctime'] = filectime($directory . '/' . $temp);
         $results[$i]['mtime'] = filemtime($directory . '/' . $temp);
         $results[$i]['group'] = filegroup($directory . '/' . $temp);
         $results[$i]['web_path'] = str_replace($server_upload_path, '', $directory) . '/' . $temp;
         $i++;
      }

      if (is_array($results)) {
         $data['count'] = count($results);
         $data['data'] = $results;
      } else {
         $data['count'] = 0;
         $data['data'] = '';
      }

      print json_encode($data);
      exit();
      break;
}
?>
