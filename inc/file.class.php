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
         foreach ($tasks_list as $itemtype => $status_list) {
            foreach ($status_list as $status) {
               $results_jobs = getAllDatasFromTable('glpi_plugin_fusinvdeploy_taskjobs',
                                     "`id`='".$status['plugin_fusioninventory_taskjobs_id']."'");

               foreach ($results_jobs as $jobs) {
                  $definitions = importArrayFromDB($jobs['definition']);
                  foreach($definitions as $key => $definition)
                     foreach($definition as $value) {
                        $packages_id[] = $value;
                     }
               }


               foreach ($packages_id as $package_id) {
                  $orders = getAllDatasFromTable('glpi_plugin_fusinvdeploy_orders',
                                                 "`plugin_fusinvdeploy_packages_id`='$package_id'");

                  foreach ($orders as $order) {
                     $results_files = getAllDatasFromTable('glpi_plugin_fusinvdeploy_files',
                                         "`plugin_fusinvdeploy_orders_id`='".$order['id']."'");


                     foreach ($results_files as $result_file) {
                        $tmp = array();
                        $tmp['uncompress']                = $result_file['uncompress'];
                        $tmp['name']                      = $result_file['name'];
                        $tmp['p2p']                    = $result_file['is_p2p'];

                        $mirrors = PluginFusinvdeployFile_Mirror::getForFile($result_file['id']);
                        $tmp['mirrors'] = $mirrors;

                        $fileparts = PluginFusinvdeployFilepart::getForFile($result_file['id']);
                        $tmp['multiparts'] = $fileparts;

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




   static function getDirBySha512 ($sha512) {
      $first = substr($sha512, 0, 1);
      $second = substr($sha512, 0, 2);

      return "$first/$second";
   }

   function registerFilepart ($repoPath, $filePath) {
      $sha512 = hash_file('sha512', $filePath);
      $shortSha512 = substr($sha512, 0, 6);

      $dir = $repoPath.'/'.self::getDirBySha512($sha512);

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
      $p2p = $params['is_p2p'];
      $uncompress = $params['uncompress'];
      $p2p_retention_days = $params['p2p_retention_days'];
      $order_id = $params['order_id'];
      $mime_type  = $params['mime_type'];

      $maxPartSize = 1024*1024;
      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";
      $tmpFilepart = tempnam(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/", "filestore");


      //check if file is not already present
      if ($file_id = $this->checkPresenceFile(hash_file('sha512', $file_tmp_name))) {
         $message = $LANG['plugin_fusinvdeploy']['form']['message'][3];
         return $file_id;
      }

      $sha512 = hash_file('sha512', $file_tmp_name);
      $short_sha512 = substr($sha512, 0, 6);
      $file_id = $this->add(
         array(
            'name' => $filename,
            'p2p' => $p2p,
            'mimetype' => $mime_type,
            'create_date' => date('Y-m-d H:i:s'),
            'p2p-retention-days' => $p2p_retention_days,
            'uncompress' => $uncompress,
            'sha512' => $sha512,
            'shortsha512' => $short_sha512,
            'plugin_fusinvdeploy_orders_id' => $order_id,
         )
      );


      $fdIn = fopen ( $file_tmp_name , 'rb' );

      $partCpt = 0;
      $currentPartSize = 0;
      $fdPart = null;
      do {
         clearstatcache();
         if (file_exists($tmpFilepart)) {
            if (feof($fdIn) || filesize($tmpFilepart)>= $maxPartSize) {
               $sha512 = $this->registerFilepart ($repoPath, $tmpFilepart);
               $PluginFusinvdeployFilepart->add(
                  array(
                     'sha512'                        => $sha512,
                     'plugin_fusinvdeploy_orders_id' => $order_id,
                     'plugin_fusinvdeploy_files_id'  => $file_id
                  )
               );
               unlink($tmpFilepart);
            }
         }
         if (feof($fdIn)) {
            break;
         }

         $data = fread ( $fdIn, 1024*1024 );
         $fdPart = gzopen ($tmpFilepart, 'a');
         gzwrite($fdPart, $data, strlen($data));
         gzclose($fdPart);

      } while (1);
      return $file_id;
   }

   function checkPresenceFile($hash) {
      global $DB;

      $query = "SELECT id, sha512 FROM ".$this->getTable()." WHERE shortsha512 = '".substr($hash, 0, 6 )."'";
      $res = $DB->query($query);
      $result = $DB->fetch_array($res);
      if ($hash == $result["sha512"]) {
        return $result["id"];
      }

      return false;
   }


   function removeFileInRepo($id) {
      global $DB;



      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";

      $PluginFusinvdeployFilepart = new PluginFusinvdeployFilepart();

      // Retrieve file informations
      $this->getFromDB($id);

      // Delete file in folder
      $sha512 = $this->getField('sha512');
      $filepart = $PluginFusinvdeployFilepart->getForFile($id);
      $ids = $PluginFusinvdeployFilepart->getIdsForFile($id);

      //verify that the file is not used by another package, in this case ignore file suppression
      $sql = "SELECT DISTINCT plugin_fusinvdeploy_packages_id
         FROM glpi_plugin_fusinvdeploy_orders orders
      LEFT JOIN glpi_plugin_fusinvdeploy_files files
         ON files.plugin_fusinvdeploy_orders_id = orders.id
      WHERE files.sha512 = '$sha512";
      $res = $DB->query($sql);
      if ($DB->numrows($res) == 1) {
         //unlink($repoPath.self::getDirBySha512($sha512).'/'.$sha512.'.gz');

         // Delete file parts in folder
         foreach($filepart as $filename => $hash){
            $dir = $repoPath.self::getDirBySha512($hash).'/';

            //delete file part
            unlink($dir.$hash.'.gz');
         }

         // delete parts objects
         foreach($ids as $id => $sha512){
            $PluginFusinvdeployFilepart->delete(array('id' =>$id));
         }
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
