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

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', realpath('../../..'));
}
if (!defined("GLPI_PLUGIN_DOC_DIR")) {
   define("GLPI_PLUGIN_DOC_DIR", GLPI_ROOT . "/files/_plugins");
}
Session::checkLoginUser();

$docDir = GLPI_PLUGIN_DOC_DIR.'/fusioninventory';

if (isset($_GET['file'])) {
   $filename = $_GET['file'];

   // Security test : document in $docDir
   if (strstr($filename, "../") || strstr($filename, "..\\")) {
      echo "Security attack !!!";
      Event::log($filename, "sendFile", 1, "security",
                 $_SESSION["glpiname"]." tries to get a non standard file.");
      return;
   }

   $file = $docDir.'/'.$filename;
   if (!file_exists($file)) {
      echo "Error file $filename does not exist";
      return;
   } else {
      // Now send the file with header() magic
      header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
      header('Pragma: private'); /// IE BUG + SSL
      //header('Pragma: no-cache');
      header('Cache-control: private, must-revalidate'); /// IE BUG + SSL
      header("Content-disposition: filename=\"$filename\"");
      //      header("Content-type: ".$mime);

      $f=fopen($file, "r");

      if (!$f) {
         echo "Error opening file $filename";
      } else {
         // Pour que les \x00 ne devienne pas \0
         $fsize=filesize($file);

         if ($fsize) {
            echo fread($f, filesize($file));
         }
      }
   }
}
