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
         $files[] = $result['sha512'];
      }

      return $files;
   }

   static function getAssociatedFiles($device_id) {
      $files = array();
      $taskjoblog    = new PluginFusioninventoryTaskjoblog();
      $taskjobstatus = new PluginFusioninventoryTaskjobstatus();

      //Get the agent ID by his deviceid
      if ($agents_id = PluginFusinvdeployJob::getAgentByDeviceID($device_id)) {

         //Get tasks associated with the agent
         $tasks_list = $taskjobstatus->getTaskjobsAgent($agents_id);
         foreach ($tasks_list as $itemtype => $tasks) {
            foreach ($tasks as $task) {
               $results_jobs = getAllDatasFromTable('glpi_plugin_fusinvdeploy_taskjobs',
                                     "`plugin_fusinvdeploy_tasks_id`='".$task['id']."'");

               foreach ($results_jobs as $jobs) {
                  $definitions = importArrayFromDB($jobs['definition']);
                  foreach($definitions as $key => $definition)
                     foreach($definition as $value) {
                        $packages_id[] = $value;
                     }
               }

;
               foreach ($packages_id as $package_id) {
                  $orders = getAllDatasFromTable('glpi_plugin_fusinvdeploy_orders',
                                                 "`plugin_fusinvdeploy_packages_id`='$package_id'");

                  foreach ($orders as $order) {
                     $results_files = getAllDatasFromTable('glpi_plugin_fusinvdeploy_files',
                                         "`plugin_fusinvdeploy_orders_id`='".$order['id']."'");


                     foreach ($results_files as $result_file) {
                        $tmp['uncompress']                = $result_file['uncompress'];
                        $tmp['name']                      = $result_file['name'];
                        $tmp['is_p2p']                    = $result_file['is_p2p'];
                        $mirrors = PluginFusinvdeployFile_Mirror::getForFile($result_file['id']);
                        //if (!empty($mirrors)) {
                           $tmp['mirrors'] = $mirrors;
                        //}

                        $fileparts = PluginFusinvdeployFilepart::getForFile($result_file['id']);
                        if (!empty($fileparts)) {
                           $tmp['multiparts'][] = $fileparts;
                        }
                        if (isset($result_file['p2p-retention-duration'])) {
                           $tmp['p2p-retention-duration'] = $result_file['p2p-retention-duration'];
                        } else {
                           $tmp['p2p-retention-duration'] = 0;
                        }
                        $files[$result_file['sha512']]         = $tmp;
                     }

                  }
               }
            }
         }
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
      global $LANG;

      $PluginFusinvdeployFilepart = new PluginFusinvdeployFilepart();

      $filename = addslashes($params['filename']);
      $file_tmp_name = $params['file_tmp_name'];
      $is_p2p = $params['is_p2p'];
      $uncompress = $params['uncompress'];
      $p2p_retention_days = $params['p2p_retention_days'];
      $order_id = $params['order_id'];
      $testMode = isset($params['testMode']);
      $extension  = $params['mime_type'];

      $maxPartSize = 1024;
      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";
      $tmpFile = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/part.tmp";


      //check if file is not already present
      if ($this->checkPresenceFile(hash_file('sha512', $file_tmp_name))) {
         $message = $LANG['plugin_fusinvdeploy']['form']['message'][3];
         return false;
      }

      $sha512 = $this->registerFile($repoPath, $file_tmp_name);

      $file_id = false;
      if (!$testMode) { # NO SQL
         $file_id = $this->add(array(
                  'name'                          => $filename,
                  'mimetype'                      => $extension,
                  'uncompress'                    => $uncompress? '1' : '0',
                  'is_p2p'                        => $is_p2p? '1' : '0',
                  'p2p_retention_days'            => $p2p_retention_days,
                  'sha512'                        => $sha512,
                  'shortsha512'                   => substr($sha512, 0, 6),
                  'create_date'                   => date('Y-m-d H:i:s'),
                  'plugin_fusinvdeploy_orders_id' => $order_id
                  ));
      }




      $fdIn = fopen ( $file_tmp_name , 'rb' );

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
                        'name'                          => $filename,
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

      unlink($file_tmp_name);
      unlink($tmpFile);
      return $file_id;
   }

   function checkPresenceFile($hash) {
      global $DB;

      $query = "SELECT * FROM ".$this->getTable()." WHERE sha512 = '".$hash."'";
      $res = $DB->query($query);
      if ($DB->numrows($res) > 0) return true;

      return false;
   }


   function removeFileInRepo($id) {

      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";

      $PluginFusinvdeployFilepart = new PluginFusinvdeployFilepart();

      // Retrieve file informations
      $this->getFromDB($id);

      // Delete file in folder
      $sha512 = $this->getField('sha512');
      $filepart = $PluginFusinvdeployFilepart->getForFile($id);
      $ids = $PluginFusinvdeployFilepart->getIdsForFile($id);

      unlink($repoPath.$this->getDirBySha512($sha512).'/'.$sha512.'.gz');

      // Delete file parts in folder
      foreach($filepart as $filename => $hash){
         $dir = $repoPath.$this->getDirBySha512($hash).'/';

         //delete file part
         unlink($dir.$hash.'.gz');
      }

      // delete parts objects
      foreach($ids as $id => $filename){
         $PluginFusinvdeployFilepart->delete(array('id' =>$id));
      }



      // Delete file in DB
      $this->delete($_POST);

      // Reply to JS
      echo "{success:true}";
   }

   public static function getMaxUploadSize() {
      global $LANG;

      $max_upload = (int)(ini_get('upload_max_filesize'));
      $max_post = (int)(ini_get('post_max_size'));
      $memory_limit = (int)(ini_get('memory_limit'));

      return $LANG['plugin_fusinvdeploy']['files'][6]
         ." : ".min($max_upload, $max_post, $memory_limit).$LANG['common'][82];
   }

}
?>
