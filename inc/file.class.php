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
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployFile extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][12];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function getEmpty() {
      $this->fields['retention'] = 0;
   }

   function prepareInputForAdd($input) {
      if (isset($result['p2p-retention-duration'])) {
         $tmp['p2p-retention-duration'] = 0;
      }
      return $input;
   }

   static function cleanForPackage($orders_id) {
      global $DB;
      $query = "DELETE FROM `glpi_plugin_fusinvdeploy_files`
                WHERE `plugin_fusinvdeploy_orders_id`='$orders_id'";
      $DB->query($query);
   }

   static function getForOrder($orders_id) {
      $results = getAllDatasFromTable('glpi_plugin_fusinvdeploy_files',
                                      "`plugin_fusinvdeploy_orders_id`='$orders_id'");

      $files = array();
      foreach ($results as $result) {
         $tmp['uncompress']                = $result['uncompress'];
         $tmp['name']                      = $result['name'];
         $tmp['is_p2p']                    = $result['is_p2p'];
         $mirrors = PluginFusinvdeployFile_Mirror::getForFile($result['id']);
         if (!empty($mirrors)) {
            $tmp['mirrors'] = $mirrors;
         }

         $fileparts = PluginFusinvdeployFilepart::getForFile($result['id']);
         if (!empty($fileparts)) {
            $tmp['multiparts'] = $fileparts;
         }
         if (isset($result['p2p-retention-duration'])) {
            $tmp['p2p-retention-duration'] = $result['p2p-retention-duration'];
         } else {
            $tmp['p2p-retention-duration'] = 0;
         }
         $files[$result['sha512']]         = $tmp;
      }

      return $files;
   }




   function getDirBySha512 ($sha512) {
      $first = substr($sha512, 0, 1);
      $second = substr($sha512, 0, 2);

      return "$first/$second";
   }

   function registerFile ($repoPath, $filePath) {
      $sha512 = hash_file('sha512', $filePath);
      $shortSha512 = substr($sha512, 0, 6);

      $dir = $repoPath.'/'.$this->getDirBySha512($sha512);

      if (!file_exists ($dir)) {
         mkdir($dir, 0700, true);
      }
      copy ($filePath, $dir.'/'.$sha512.'.gz');




      return $sha512;
   }

   function addFileInRepo ($params) {

      $PluginFusinvdeployFilepart = new PluginFusinvdeployFilepart();

      $filename = $params['filename'];
      $is_p2p = $params['is_p2p'];
      $p2p_retention_days = $params['p2p_retention_days'];
      $order_id = $params['order_id'];
      $testMode = isset($params['testMode']);

      $maxPartSize = 1024;
      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";
      $tmpFile = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/part.tmp";

      $sha512 = $this->registerFile($repoPath, $filename);

      if (!$testMode) { # NO SQL
         $file_id = $this->add(array(
                  'name'                          => $filename,
                  'is_p2p'                        => $is_p2p? '1' : '0',
                  'p2p_retention_days'            => $p2p_retention_days,
                  'sha512'                        => $sha512,
                  'shortsha512'                   => substr($sha512, 0, 6),
                  'create_date'                   => date('Y-m-d H:i:s'),
                  'plugin_fusinvdeploy_orders_id' => $order_id
                  ));
      }





      $fdIn = fopen ( $filename , 'rb' );

      $partCpt = 0;
      $currentPartSize = 0;
      $fdPart = null;
      do {
         if (($currentPartSize > 0 && feof($fdIn)) || $currentPartSize>= $maxPartSize) {
            gzclose ($fdPart);

            $fdPart = null;
            $sha512 = $this->registerFile ($repoPath, $tmpFile);

            if (!$testMode) { # NO SQL
               $PluginFusinvdeployFilepart->add(
                     array(
                        'name'                          => $filename.'.gz',
                        'sha512'                        => $sha512,
                        'plugin_fusinvdeploy_orders_id' => $order_id,
                        'plugin_fusinvdeploy_files_id'  => $file_id)
                     );
            }

            $currentPartSize = 0;
         }
         if (!feof($fdIn)) {
            if (!$fdPart) {
               $fdPart = gzopen ($tmpFile, 'w9');
            }

            $data = fread ( $fdIn, 1024 );
            gzwrite($fdPart, $data, strlen($data));
            $currentPartSize++;
         }
      } while (!feof($fdIn) || $fdPart);

      unlink($filename);
      unlink($tmpFile);
      return $file_id;
   }

}
?>
