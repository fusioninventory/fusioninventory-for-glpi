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

// Start the session for this page
//session_start();
header("Cache-control: private");

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
Session::checkLoginUser();

header("Content-Type: text/html; charset=UTF-8");

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

$directory = $server_upload_path;
if (isset($_REQUEST['directory']) && $_REQUEST['directory']) {
   $directory = $server_upload_path . $_REQUEST['directory'];
}
$data = array();
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