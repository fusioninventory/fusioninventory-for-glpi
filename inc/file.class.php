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

                        $mirrors = PluginFusinvdeployFile_Mirror::getList();
                        $tmp['mirrors'] = $mirrors;

                        $fileparts = PluginFusinvdeployFilepart::getForFile($result_file['id']);
                        $tmp['multiparts'] = $fileparts;

                        if (isset($result_file['p2p_retention_days'])) {
                           $tmp['p2p-retention-duration'] = $result_file['p2p_retention_days'] * 3600 * 24;
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

      if (count($files) == 0) $files = new stdClass;

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
      $is_p2p = $params['is_p2p'];
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
      $data = array(
         'name' => $filename,
         'is_p2p' => $is_p2p,
         'mimetype' => $mime_type,
         'create_date' => date('Y-m-d H:i:s'),
         'p2p_retention_days' => $p2p_retention_days,
         'uncompress' => $uncompress,
         'sha512' => $sha512,
         'shortsha512' => $short_sha512,
         'plugin_fusinvdeploy_orders_id' => $order_id,
      );
      $file_id = $this->add($data);


      $fdIn = fopen ( $file_tmp_name , 'rb' );

      $partCpt = 0;
      $currentPartSize = 0;
      $fdPart = null;
      do {
         clearstatcache();
         if (file_exists($tmpFilepart)) {
            if (feof($fdIn) || filesize($tmpFilepart)>= $maxPartSize) {
               $sha512 = $this->registerFilepart ($repoPath, $tmpFilepart);
               $short_sha512 = substr($sha512, 0, 6);
               $PluginFusinvdeployFilepart->add(
                  array(
                     'sha512'                        => $sha512,
                     'shortsha512'                   => $short_sha512,
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
      WHERE files.sha512 = '$sha512'";
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

   function uploadFile()  {
      global $LANG;

      if(isset($_GET['package_id'])){
         $package_id = $_GET['package_id'];
         $render     = $_GET['render'];
      } else {
         exit;
      }

      foreach($_POST as $POST_key => $POST_value) {
         $new_key         = preg_replace('#^'.$render.'#','',$POST_key);
         $_POST[$new_key] = $POST_value;
      }

      //if file sent is from server
      if (isset($_POST['file_server'])) return $this->uploadFileFromServer();

      //if file sed is from http post
      foreach($_FILES as $FILES_key => $FILES_value) {
         $new_key          = preg_replace('#^'.$render.'#','',$FILES_key);
         $_FILES[$new_key] = $FILES_value;
      }

      $render   = PluginFusinvdeployOrder::getRender($render);
      $order_id = PluginFusinvdeployOrder::getIdForPackage($package_id,$render);

      if (isset ($_POST["id"]) and !$_POST['id']) {

      #logDebug($_POST);
      #logDebug($_FILES);

        //file uploaded?
         $filename = null;
         $file_tmp_name = null;
         if (isset($_FILES['file']['tmp_name']) and !empty($_FILES['file']['tmp_name'])){
            $file_tmp_name = $_FILES['file']['tmp_name'];
         } /*elseif(isset($_POST['url']) and !empty($_POST['url'])) {
            $filename = $_POST['filename'];
         }*/
         if (isset($_FILES['file']['name']) and !empty($_FILES['file']['name']))
            $filename = $_FILES['file']['name'];

         //file upload errors
         if (isset($_FILES['file']['error'])) {
            switch ($_FILES['file']['error']) {
               case UPLOAD_ERR_INI_SIZE:
               case UPLOAD_ERR_FORM_SIZE:
                  print "{success:false, file:'{$filename}',msg:\"{$LANG['plugin_fusinvdeploy']['form']['label'][20]}\"}";
                  exit;
               case UPLOAD_ERR_PARTIAL:
                  print "{success:false, file:'{$filename}',msg:\"The uploaded file was only partially uploaded.\"}";
                  exit;
               case UPLOAD_ERR_NO_FILE:
                  print "{success:false, file:'{$filename}',msg:\"No file was uploaded.\"}";
                  exit;
               case UPLOAD_ERR_NO_TMP_DIR:
                  print "{success:false, file:'{$filename}',msg:\"Missing a temporary folder.\"}";
                  exit;
               case UPLOAD_ERR_CANT_WRITE:
                  print "{success:false, file:'{$filename}',msg:\"Failed to write file to disk.\"}";
                  exit;
               case UPLOAD_ERR_CANT_WRITE:
                  print "{success:false, file:'{$filename}',msg:\"A PHP extension stopped the file upload.\"}";
                  exit;
               case UPLOAD_ERR_OK:
                  //no error, continue
            }
         }

         //prepare file data for insertion in repo
         $data = array(
            'file_tmp_name' => $file_tmp_name,
            'mime_type' => $_FILES['file']['type'],
            'filename' => $filename,
            'is_p2p' => (($_POST['p2p'] == 'true') ? 1 : 0),
            'uncompress' => (($_POST['uncompress'] == 'true') ? 1 : 0),
            'p2p_retention_days' => is_numeric($_POST['validity']) ? $_POST['validity'] : 0,
            'order_id' => $order_id
         );

         //Add file in repo
         if ($filename && $this->addFileInRepo($data)) {
            print "{success:true, file:'{$filename}',msg:\"{$LANG['plugin_fusinvdeploy']['form']['action'][4]}\"}";
            exit;
         } else {
            print "{success:false, file:'{$filename}',msg:\"{$LANG['plugin_fusinvdeploy']['form']['label'][15]}\"}";
            exit;
         }
      }
      print "{success:false, file:'none',msg:\"{$LANG['plugin_fusinvdeploy']['form']['label'][15]}\"}";
   }

   public function uploadFileFromServer() {
      global $LANG;

      $package_id = $_GET['package_id'];
      $render     = $_GET['render'];

      $render   = PluginFusinvdeployOrder::getRender($render);
      $order_id = PluginFusinvdeployOrder::getIdForPackage($package_id,$render);

      if (isset ($_POST["id"]) and !$_POST['id']) {
         $file_tmp_name = $_POST['file_server'];
         $filename = substr($file_tmp_name, strrpos($file_tmp_name, '/')+1);
         $mime_type = @mime_content_type($file_tmp_name);

         //prepare file data for insertion in repo
         $data = array(
            'file_tmp_name' => $file_tmp_name,
            'mime_type' => $mime_type,
            'filename' => $filename,
            'is_p2p' => (($_POST['p2p'] == 'true') ? 1 : 0),
            'uncompress' => (($_POST['uncompress'] == 'true') ? 1 : 0),
            'p2p_retention_days' => is_numeric($_POST['validity']) ? $_POST['validity'] : 0,
            'order_id' => $order_id
         );

         //Add file in repo
         if ($filename && $this->addFileInRepo($data)) {
            print "{success:true, file:'{$filename}',msg:\"{$LANG['plugin_fusinvdeploy']['form']['action'][4]}\"}";
            exit;
         } else {
            print "{success:false, file:'{$filename}',msg:\"{$LANG['plugin_fusinvdeploy']['form']['label'][15]}\"}";
            exit;
         }
      } print "{success:false, file:'none',msg:\"{$LANG['plugin_fusinvdeploy']['form']['label'][15]}\"}";
   }

}
?>
