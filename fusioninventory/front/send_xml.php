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
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
   include (GLPI_ROOT . "/inc/includes.php");
}
include_once (GLPI_ROOT . "/inc/includes.php");

if (!defined("GLPI_PLUGIN_DOC_DIR")){
   define("GLPI_PLUGIN_DOC_DIR",GLPI_ROOT . "/files/_plugins");
}
//Session::checkLoginUser();

if (!isset($_GET['pluginname'])) {
   exit;
}

$docDir = GLPI_PLUGIN_DOC_DIR.'/'.$_GET['pluginname'];

if (isset($_GET['file'])) {
   $filepath = $_GET['file'];
   $filepslit = explode("/", $filepath);
   $filename = $filepslit[1];
   
   // Security test : document in $docDir
   if (strstr($filepath,"../") || strstr($filepath,"..\\")){
      echo "Security attack !!!";
      Event::log($filepath, "sendFile", 1, "security",
                 $_SESSION["glpiname"]." tries to get a non standard file.");
      return;
   }

   $file = $docDir.'/'.$filepath;
   if (!file_exists($file)){
      echo "Error file $filepath does not exist";
      return;
   } else {
      // Now send the file with header() magic
      header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
      header('Pragma: private'); /// IE BUG + SSL
      //header('Pragma: no-cache');
      header('Cache-control: private, must-revalidate'); /// IE BUG + SSL
      header("Content-disposition: filename=\"$filename.xml\"");
      //header("Content-type: xml");
      header("Content-type: application/force-download");

      
      $f=fopen($file,"r");

      if (!$f){
         echo "Error opening file $filepath";
      } else {
         // Pour que les \x00 ne devienne pas \0
         $mc=Toolbox::get_magic_quotes_runtime();
         if ($mc) @ini_set('magic_quotes_runtime', 0);
         $fsize=filesize($file);

         if ($fsize){
            echo fread($f, filesize($file));
         } else {
            echo $LANG['document'][47];
         }

         if ($mc) @ini_set('magic_quotes_runtime', $mc);
      }
   }
}

?>