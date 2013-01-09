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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryDeployFile extends CommonDBTM {

   static function getTypeName($nb=0) {
      return __('Files');
   }

   static function canCreate() {
      return true;
   }

   static function canView() {
      return true;
   }

   static function displayForm($order_type, $packages_id, $datas) {
      echo "<div style='display:none' id='files_block' >";
      echo "<hr>";
      echo "</div>";

      //display stored files datas
      if (!isset($datas['jobs']['associatedFiles'])) return;
      echo "<ul>";
      foreach ($datas['jobs']['associatedFiles'] as $sha512) {
         $filename = $datas['associatedFiles'][$sha512]['name'];
         echo "<li>$filename</li>";
      }
      echo "<ul>";
   }

   static function getExtensionsWithAutoAction() {
      $ext = array();

      $ext['msi']['install']     = "msiexec /qb /i ##FILENAME## REBOOT=ReallySuppress";
      $ext['msi']['uninstall']   = "msiexec /qb /x ##FILENAME## REBOOT=ReallySuppress";

      $ext['deb']['install']     = "dpkg -i ##FILENAME## ; apt-get install -f";
      $ext['deb']['uninstall']   = "dpkg -P ##FILENAME## ; apt-get install -f";

      $ext['rpm']['install']     = "rpm -Uvh ##FILENAME##";
      $ext['rpm']['install']     = "rpm -ev ##FILENAME##";

      return $ext;
   }

   static function getForOrder($orders_id) {
      $results = getAllDatasFromTable('glpi_plugin_fusioninventory_deployfiles',
                                      "`plugin_fusioninventory_deployorders_id`='".$orders_id.
                                      "' AND sha512 <> ''");

      $files = array();
      foreach ($results as $result) {
         $files[] = $result['sha512'];
      }

      return $files;
   }

   static function getAssociatedFiles($device_id) {
      $files = array();
      $taskjoblog    = new PluginFusioninventoryTaskjoblog();
      $taskjobstate = new PluginFusioninventoryTaskjobstate();

      //Get the agent ID by his deviceid
      if ($agents_id = PluginFusioninventoryDeployJob::getAgentByDeviceID($device_id)) {


         //Get tasks associated with the agent
         $tasks_list = $taskjobstate->getTaskjobsAgent($agents_id);
         foreach ($tasks_list as $itemtype => $states_list) {
            foreach ($states_list as $status) {
               $results_jobs = getAllDatasFromTable('glpi_plugin_fusioninventory_deploytaskjobs',
                                     "`id`='".$status['plugin_fusioninventory_taskjobs_id']."'");

               foreach ($results_jobs as $jobs) {
                  $definitions = importArrayFromDB($jobs['definition']);
                  foreach($definitions as $key => $definition) {
                     foreach($definition as $value) {
                        $packages_id[] = $value;
                     }
                  }
               }


               foreach ($packages_id as $package_id) {
                  $orders = getAllDatasFromTable('glpi_plugin_fusioninventory_deployorders',
                                                 "`plugin_fusioninventory_deploypackages_id`='".
                                                 $package_id."'");

                  foreach ($orders as $order) {
                     $results_files =getAllDatasFromTable('glpi_plugin_fusioninventory_deployfiles',
                                         "`plugin_fusioninventory_deployorders_id`='".$order['id'].
                                         "' AND sha512 <> ''");


                     foreach ($results_files as $result_file) {
                        $tmp = array();
                        $tmp['uncompress']                = $result_file['uncompress'];
                        $tmp['name']                      = $result_file['name'];
                        $tmp['p2p']                    = $result_file['is_p2p'];

                        $mirrors = PluginFusioninventoryDeployFile_Mirror::getList();
                        $tmp['mirrors'] = $mirrors;

                        $fileparts = PluginFusioninventoryDeployFilepart::getForFile($result_file['id']);
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


      set_time_limit(600);

      $PluginFusioninventoryDeployFilepart = new PluginFusioninventoryDeployFilepart();

      $filename = addslashes($params['filename']);
      $file_tmp_name = $params['file_tmp_name'];
      $filesize = $params['filesize'];
      $is_p2p = $params['is_p2p'];
      $uncompress = $params['uncompress'];
      $p2p_retention_days = $params['p2p_retention_days'];
      $order_id = $params['order_id'];
      $mime_type  = $params['mime_type'];

      $maxPartSize = 1024*1024;
      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";
      $tmpFilepart = tempnam(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/", "filestore");

      $sha512 = hash_file('sha512', $file_tmp_name);
      $short_sha512 = substr($sha512, 0, 6);

      if($this->checkPresenceFile($sha512, $order_id)) {
         print "{\"success\": \"false\", \"file\": \"{$filename}\", \"msg\": \"File already exists.\"}";
         exit;
      }

      $data = array(
         'name' => $filename,
         'is_p2p' => $is_p2p,
         'mimetype' => $mime_type,
         'filesize' => $filesize,
         'create_date' => date('Y-m-d H:i:s'),
         'p2p_retention_days' => $p2p_retention_days,
         'uncompress' => $uncompress,
         'plugin_fusioninventory_deployorders_id' => $order_id
      );
      $file_id = $this->add($data);

      $fdIn = fopen ( $file_tmp_name, 'rb' );
      if (!$fdIn) {
         return;
      }

      $partCpt = 0;
      $currentPartSize = 0;
      $fdPart = null;
      do {
         clearstatcache();
         if (file_exists($tmpFilepart)) {
            if (feof($fdIn) || filesize($tmpFilepart)>= $maxPartSize) {
               $part_sha512 = $this->registerFilepart ($repoPath, $tmpFilepart);
               $part_short_sha512 = substr($part_sha512, 0, 6);
               $PluginFusioninventoryDeployFilepart->add(
                  array(
                     'sha512'                        => $part_sha512,
                     'shortsha512'                   => $part_short_sha512,
                     'plugin_fusioninventory_deployorders_id' => $order_id,
                     'plugin_fusioninventory_deployfiles_id'  => $file_id
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


      $this->update(array(
         'id' => $file_id,
         'sha512' => $sha512,
         'shortsha512' => $short_sha512,
      ));
      return $file_id;
   }

   //TODO on 0.83 rename the function into "checkPresenceFileForOrder"
   function checkPresenceFile($sha512, $order_id) {
      global $DB;

      $rows = $this->find("plugin_fusioninventory_deployorders_id = '$order_id'
            AND shortsha512 = '".substr($sha512, 0, 6 )."'
            AND sha512 = '$sha512'"
      );
      if (count($rows) > 0) {
         return true;
      }

      return false;
   }

   static function getAssociatedFilesForOrder($order_id) {
      global $DB;

      $files=array();

      $results_files = getAllDatasFromTable('glpi_plugin_fusioninventory_deployfiles',
                          "`plugin_fusioninventory_deployorders_id`='".
                          $order_id."' AND sha512 <> ''");


      foreach ($results_files as $result_file) {
         $tmp = array();
         $tmp['uncompress']   = $result_file['uncompress'];
         $tmp['name']         = $result_file['name'];
         $tmp['p2p']          = $result_file['is_p2p'];
         $tmp['filesize']     = $result_file['filesize'];
         $tmp['create_date']  = $result_file['create_date'];
         $tmp['mimetype']     = $result_file['mimetype'];

         $mirrors = PluginFusioninventoryDeployFile_Mirror::getList();
         $tmp['mirrors'] = $mirrors;

         $fileparts = PluginFusioninventoryDeployFilepart::getForFile($result_file['id']);
         $tmp['multiparts'] = $fileparts;

         if (isset($result_file['p2p_retention_days'])) {
            $tmp['p2p-retention-duration'] = $result_file['p2p_retention_days'] * 3600 * 24;
         } else {
            $tmp['p2p-retention-duration'] = 0;
         }
         $files[$result_file['sha512']]         = $tmp;
      }

      if (count($files) == 0) $files = new stdClass;
      return $files;
   }

   function removeFileInRepo($id) {
      global $DB;

      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";

      $PluginFusioninventoryDeployFilepart = new PluginFusioninventoryDeployFilepart();

      // Retrieve file informations
      $this->getFromDB($id);

      // Delete file in folder
      $sha512 = $this->getField('sha512');
      $filepart = $PluginFusioninventoryDeployFilepart->getForFile($id);
      $ids = $PluginFusioninventoryDeployFilepart->getIdsForFile($id);

      //verify that the file is not used by another package, in this case ignore file suppression
      $sql = "SELECT DISTINCT plugin_fusioninventory_deploypackages_id
         FROM glpi_plugin_fusioninventory_deployorders orders
      LEFT JOIN glpi_plugin_fusioninventory_deployfiles files
         ON files.plugin_fusioninventory_deployorders_id = orders.id
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
            $PluginFusioninventoryDeployFilepart->delete(array('id' =>$id));
         }
      }

      // Delete file in DB
      $this->delete($_POST);

      // Reply to JS
      echo "{success:true}";
   }

   static function getMaxUploadSize() {

      $max_upload = (int)(ini_get('upload_max_filesize'));
      $max_post = (int)(ini_get('post_max_size'));
      $memory_limit = (int)(ini_get('memory_limit'));

      return __('Maximum file size')

         ." : ".min($max_upload, $max_post, $memory_limit).__('Mio');

   }

   function uploadFile() {

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
      if (isset($_POST['itemtype']) && $_POST['itemtype'] == 'fileserver') {
         return $this->uploadFileFromServer();
      }

      //if file sed is from http post
      foreach($_FILES as $FILES_key => $FILES_value) {
         $new_key          = preg_replace('#^'.$render.'#','',$FILES_key);
         $_FILES[$new_key] = $FILES_value;
      }

      $render   = PluginFusioninventoryDeployOrder::getRender($render);
      $order_id = PluginFusioninventoryDeployOrder::getIdForPackage($package_id,$render);

      // Check if add a file in install and if not have file in uninstall,
      // we add too this file in unistall
      $uninstall = 0;
      $order_uninstall_id = 0;
      if ($render == 'install') {
         $render_uninstall = PluginFusioninventoryDeployOrder::getRender('uninstall');
         $order_uninstall_id = PluginFusioninventoryDeployOrder::getIdForPackage($package_id,$render_uninstall);
         $a_uninstall_files = $this->find("`plugin_fusioninventory_deployorders_id`='".
                                          $order_uninstall_id."'");
         if (count($a_uninstall_files) == 0) {
            $uninstall = 1;
         }
      }

      if (isset ($_POST["id"]) and !$_POST['id']) {

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
                  print "{success:false, file:'{$filename}',msg:\"{__('Transfer error: the file size is too big')}\"}";
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
            'filesize' => $_FILES['file']['size'],
            'filename' => $filename,
            'is_p2p' => (($_POST['p2p'] == 'true') ? 1 : 0),
            'uncompress' => (($_POST['uncompress'] == 'true') ? 1 : 0),
            'p2p_retention_days' => is_numeric($_POST['validity']) ? $_POST['validity'] : 0,
            'order_id' => $order_id
         );

         //Add file in repo
         if ($filename && $this->addFileInRepo($data)) {
            if ($uninstall == 1) {
               $data['order_id'] = $order_uninstall_id;
               $this->addFileInRepo($data);
            }
            print "{success:true, file:'{$filename}',msg:\"{$LANG['plugin_fusinvdeploy']['action'][4]}\"}";
            exit;
         } else {
            print "{success:false, file:'{$filename}',msg:\"{__('File missing')}\"}";
            exit;
         }
      }
      print "{success:false, file:'none',msg:\"{__('File missing')}\"}";
   }

   function uploadFileFromServer() {

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;
      $server_upload_path = $PluginFusioninventoryConfig->getValue($plugins_id, 
                                                                   'server_upload_path');


      $package_id = $_GET['package_id'];
      $render     = $_GET['render'];

      if (preg_match('/\.\./', $_POST['file_server'])) {
         die;
      }

      $render   = PluginFusioninventoryDeployOrder::getRender($render);
      $order_id = PluginFusioninventoryDeployOrder::getIdForPackage($package_id,$render);

      if (isset ($_POST["id"]) and !$_POST['id']) {
         $file_path = $server_upload_path.'/'.$_POST['file_server'];
         $filename = basename($file_path);
         $mime_type = @mime_content_type($file_path);
         $filesize = filesize($file_path);

         //prepare file data for insertion in repo
         $data = array(
            'file_tmp_name' => $file_path,
            'mime_type' => $mime_type,
            'filesize' => $filesize,
            'filename' => $filename,
            'is_p2p' => (($_POST['p2p'] == 'true') ? 1 : 0),
            'uncompress' => (($_POST['uncompress'] == 'true') ? 1 : 0),
            'p2p_retention_days' => is_numeric($_POST['validity']) ? $_POST['validity'] : 0,
            'order_id' => $order_id
         );

         //Add file in repo
         if ($filename && $this->addFileInRepo($data)) {
            print "{success:true, file:'{$filename}',msg:\"{__('File saved!')}\"}";
            exit;
         } else {
            print "{success:false, file:'{$filename}',msg:\"{__('Failed to copy file')}\"}";
            exit;
         }
      } print "{success:false, file:'none',msg:\"{__('File missing')}\"}";
   }

   static function processFilesize($filesize) {
      if ($filesize >= (1024 * 1024 * 1024)) {
         $filesize = round($filesize / (1024 * 1024 * 1024), 1)."GiB";
      } elseif ($filesize >= 1024 * 1024) {
         $filesize = round($filesize /  (1024 * 1024), 1)."MiB";

      } elseif ($filesize >= 1024) {
         $filesize = round($filesize / 1024, 1)."KB";

      } else {
         $filesize;
      }
      return $filesize;
   }

}

?>
