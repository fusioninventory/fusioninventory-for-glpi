<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: HEBERT Anthony
// Purpose of file:
// ----------------------------------------------------------------------

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
      $results = getAllDatasFromTable('glpi_plugin_fusinvdeploy_fileparts',
                                      "`plugin_fusinvdeploy_files_id`='$files_id'", "glpi_plugin_fusinvdeploy_fileparts.id");

      $fileparts = array();
      # TODO, avoid the array push here.
      foreach ($results as $result) {
         array_unshift($fileparts, $result['sha512']);
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
         //send an error if filename not specified
         header("HTTP/1.1 500");
         exit;
      }
      preg_match('/.\/..\/([^\/]+)/', $params['file'], $matches);

      $sha512 = mysql_real_escape_string($matches[1]);

      $PluginFusinvdeployFilepart = new PluginFusinvdeployFilepart;
      # TODO, use sha512short here to find the filepart (See: #1106)
      $files = $PluginFusinvdeployFilepart->find("sha512='".$sha512."'");

      if (count($files) == 1) {
         $file = array_pop($files);

         $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";
         $sha512 = $file['sha512'];

         $filedir = $repoPath.PluginFusinvdeployFile::getDirBySha512($sha512).'/'.$sha512.'.gz';

         error_reporting(0);

         header('Content-Description: File Transfer');
         header('Content-Type: application/octet-stream');
         header('Content-Disposition: attachment; filename='.$filename);
         header('Content-Transfer-Encoding: binary');
         header('Expires: 0');
         header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
         header('Pragma: public');
         header('Content-Length: ' . filesize($filedir));
         ob_clean();
         flush();
         readfile($filedir);
      }
      exit;
   }
}
?>
