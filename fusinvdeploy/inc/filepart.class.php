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
   @author    Anthony Hebert
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployFilepart extends CommonDBTM {


   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][19];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   static function getForFile($files_id) {
      $filepart_obj = new self;
      $results = $filepart_obj->find("`plugin_fusinvdeploy_files_id`='$files_id'",
            "id ASC");

      $fileparts = array();
      # TODO, avoid the array push here.
      foreach ($results as $result) {
         array_push($fileparts, $result['sha512']);
      }

      return $fileparts;
   }

   static function getIdsForFile($files_id) {
      $results = getAllDatasFromTable('glpi_plugin_fusinvdeploy_fileparts',
                                      "`plugin_fusinvdeploy_files_id`='$files_id'");

      $fileparts = array();
      foreach ($results as $result) {
         $fileparts[$result['id']] = $result['sha512'];
      }

      return $fileparts;
   }

   static function httpSendFile($params) {
      if (!isset($params['file'])) {
         header("HTTP/1.1 500");
         exit;

      }
      preg_match('/.\/..\/([^\/]+)/', $params['file'], $matches);

      $sha512 = mysql_real_escape_string($matches[1]);
      $short_sha512 = substr($sha512, 0, 6);

      //search by shortsha512
      $PluginFusinvdeployFilepart = new PluginFusinvdeployFilepart;
      $files = $PluginFusinvdeployFilepart->find("shortsha512='".$short_sha512."'");

      if (count($files) > 1) {
         //find file with long sha512
         foreach ($files as $file) {
            if ($file['sha512'] == $sha512) {
               unset($files);
               $files = array($file);
               break;
            }
         }
      }
      if (count($files) == 0 ) {
         header("HTTP/1.1 404");
         exit;
      }

      if (count($files) > 1) {
         header("HTTP/1.1 500");
         exit;
      }

      $file = array_pop($files);

      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";
      $sha512 = $file['sha512'];

      $filePath = $repoPath.PluginFusinvdeployFile::getDirBySha512($sha512).'/'.$sha512.'.gz';



      if (!is_file($filePath)) {
         header("HTTP/1.1 404");
         print "\n".getcwd().'/'.$filePath."\n\n";
         exit;
      } else if (!is_readable($filePath)) {
         header("HTTP/1.1 403");
         exit;
      }


      error_reporting(0);

      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.$sha512.'.gz');
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      header('Content-Length: ' . filesize($filePath));
      ob_clean();
      flush();
      readfile($filePath);
      exit;
   }
}

?>