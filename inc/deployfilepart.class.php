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
   die("Sorry. You can't access directly to this file");
}

/**
 * Used to get the deploy file in many parts.
 */
class PluginFusioninventoryDeployFilepart {


   /**
    * Send file to agent
    *
    * @param string $file
    */
   static function httpSendFile($file) {
      if (empty($file)) {
         header("HTTP/1.1 500");
         exit;
      }
      $matches = [];
      preg_match('/.\/..\/([^\/]+)/', $file, $matches);

      $sha512 = $matches[1];
      //      $short_sha512 = substr($sha512, 0, 6);

      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/repository/";

      $pfDeployFile = new PluginFusioninventoryDeployFile();
      $filePath     = $repoPath.$pfDeployFile->getDirBySha512($sha512).'/'.$sha512;

      if (!is_file($filePath)) {
         header("HTTP/1.1 404");
         print "\n".$filePath."\n\n";
         exit;
      } else if (!is_readable($filePath)) {
         header("HTTP/1.1 403");
         exit;
      }

      error_reporting(0);

      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.$sha512);
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      header('Content-Length: ' . filesize($filePath));
      if (ob_get_level() > 0) {
         ob_clean();
      }
      flush();
      readfile($filePath);
      exit;
   }


}
