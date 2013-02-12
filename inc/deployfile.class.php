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
      return __('Files', 'fusioninventory');
   }

   static function canCreate() {
      return TRUE;
   }

   static function canView() {
      return TRUE;
   }

   
   
   static function getTypes() {
      return array(
         'Computer' => __("Upload from computer", 'fusioninventory'),
         'Server'   => __("Upload from server", 'fusioninventory')
      );
   }

   
   
   static function displayForm($orders_id, $datas, $rand) {
      global $CFG_GLPI;

      echo "<div style='display:none' id='files_block$rand'>";

      echo "<span id='showFileType$rand'></span>";
      echo "<script type='text/javascript'>";
      $params = array(
         'rand'    => $rand,
         'subtype' => "file"
      );
      Ajax::UpdateItemJsCode("showFileType$rand",
                             $CFG_GLPI["root_doc"].
                             "/plugins/fusioninventory/ajax/deploydropdown_packagesubtypes.php",
                             $params,
                             "dropdown_deploy_filetype");
      echo "</script>";


      echo "<span id='showFileValue$rand'></span>";
      
      echo "<hr>";
      echo "</div>";
      Html::closeForm();

      //display stored files datas
      if (!isset($datas['jobs']['associatedFiles']) || empty($datas['jobs']['associatedFiles'])) {
         return;
      }
      echo "<form name='removefiles' method='post' action='deploypackage.form.php?remove_item' ".
         "id='filesList$rand'>";
      echo "<input type='hidden' name='itemtype' value='PluginFusioninventoryDeployFile' />";
      echo "<input type='hidden' name='orders_id' value='$orders_id' />";
      echo "<div id='drag_files'>";
      echo "<table class='tab_cadrehov package_item_list' id='table_file'>";
      $i = 0;
      foreach ($datas['jobs']['associatedFiles'] as $sha512) {
         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         echo "<td class='control'><input type='checkbox' name='file_entries[]' value='$i' /></td>";
         $filename = $datas['associatedFiles'][$sha512]['name'];
         $filesize = $datas['associatedFiles'][$sha512]['filesize'];
         echo "<td class='filename'>";
         echo "<img src='".$CFG_GLPI['root_doc'].
               "/plugins/fusioninventory/pics/ext/extensions/documents.png' />";
         echo"&nbsp;<a class='edit'>$filename</a>";

         if (isset($datas['associatedFiles'][$sha512]['p2p'])) {
            echo "<a title='".__('p2p', 'fusioninventory').", "
            .__("retention", 'fusioninventory')." : ".
               $datas['associatedFiles'][$sha512]['p2p-retention-duration']." ".
               __("days", 'fusioninventory')."' class='more'><img src='".$CFG_GLPI['root_doc'].
               "/plugins/fusioninventory/pics/p2p.png' /></a>";
         }
         if (isset($datas['associatedFiles'][$sha512]['uncompress'])) {
            echo "<a title='".__('uncompress', 'fusioninventory')."' class='more'><img src='".
               $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/uncompress.png' /></a>";
         }
         echo "<br />";
         echo self::processFilesize($filesize);
         echo "</td>";
         echo "<td class='rowhandler control' title='".__('drag', 'fusioninventory').
            "'><div class='drag row'></div></td>";
         $i++;
      }
      echo "<tr><td colspan='2'>";
      Html::checkAllAsCheckbox("filesList$rand", $rand);
      echo "&nbsp;<input type='submit' name='delete' value=\"".
         __('Delete', 'fusioninventory')."\" class='submit'>";
      echo "</td></tr>";
      echo "</table></div>";
      Html::closeForm();
   }

   
   
   static function dropdownType($datas) {
      global $CFG_GLPI;

      $rand = $datas['rand'];

      $file_types = self::getTypes();
      array_unshift($file_types, "---");

      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>".__("Source", 'fusioninventory')."</th>";
      echo "<td>";
      Dropdown::showFromArray("deploy_filetype", $file_types, array('rand' => $rand));
      echo "</td>";
      echo "</tr></table>";

      //ajax update of file value span
      $params = array(
                      'value'  => '__VALUE__',
                      'rand'   => $rand,
                      'myname' => 'method',
                      'type'   => "file");
      Ajax::updateItemOnEvent("dropdown_deploy_filetype".$rand,
                              "showFileValue$rand",
                              $CFG_GLPI["root_doc"].
                              "/plugins/fusioninventory/ajax/deploy_displaytypevalue.php",
                              $params,
                              array("change", "load"));

   }

   
   
   static function displayAjaxValue($datas) {
      global $CFG_GLPI;

      $source = $datas['value'];
      $rand  = $datas['rand'];

      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>".__("File", 'fusioninventory')."</th>";
      echo "<td>";
      switch ($source) {
         case "Computer":
            echo "<input type='file' name='file' value='".__("filename", 'fusioninventory')."' />";
            break;
         case "Server":
            echo "<input type='text' name='filename' id='server_filename$rand'".
               " style='width:120px;float:left' />";
            echo "<input type='button' class='submit' value='".__("Choose", 'fusioninventory').
               "' onclick='fileModal$rand.show();' style='width:50px' />";
            Ajax::createModalWindow("fileModal$rand", 
                        $CFG_GLPI['root_doc']."/plugins/fusioninventory/ajax/deployfilemodal.php",
                        array('title' => __('Select the file on server', 'fusioninventory'), 
                        'extraparams' => array(
                           'rand' => $rand
                        )));
            break;
      }
      echo "</td>";
      echo "</tr><tr>";
      echo "<th>".__("Uncompress", 'fusioninventory')."<img style='float:right' ".
             "src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory//pics/uncompress.png' /></th>";
      echo "<td><input type='checkbox' name='uncompress' /></td>";
      echo "</tr><tr>";
      echo "<th>".__("P2p", 'fusioninventory').
              "<img style='float:right' src='".$CFG_GLPI["root_doc"].
              "/plugins/fusioninventory//pics/p2p.png' /></th>";
      echo "<td><input type='checkbox' name='p2p' /></td>";
      echo "</tr><tr>";
      echo "<th>".__("retention days", 'fusioninventory')."</th>";
      echo "<td><input type='text' name='p2p-retention-duration' style='width:30px' /></td>";
      echo "</tr><tr>";
      echo "<td>";
      if ($source === "Computer") {
         echo "<i>".self::getMaxUploadSize()."</i>";
      }
      echo "</td><td>";
      echo "&nbsp;<input type='submit' name='add_item' value=\"".
         __('Add')."\" class='submit' >";
      echo "</td>";
      echo "</tr></table>";
   }

   
   
   static function showServerFileTree($params) {
      global $CFG_GLPI;

      $rand = $params['rand'];

      echo "<script type='javascript'>";
      echo "var Tree_Category_Loader$rand = new Ext.tree.TreeLoader({
         dataUrl:'".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/serverfilestreesons.php'
      });";

      echo "var Tree_Category$rand = new Ext.tree.TreePanel({
         collapsible      : false,
         animCollapse     : false,
         border           : false,
         id               : 'tree_projectcategory$rand',
         el               : 'tree_projectcategory$rand',
         autoScroll       : true,
         animate          : false,
         enableDD         : true,
         containerScroll  : true,
         height           : 320,
         width            : 770,
         loader           : Tree_Category_Loader$rand,
         rootVisible      : false, 
         listeners: {
            click: function(node, event){
               if (node.leaf == true) {
                  console.log('server_filename$rand');
                  Ext.get('server_filename$rand').dom.value = node.id;
                  fileModal$rand.hide();
               }
            }
         }
      });";

      // SET the root node.
      echo "var Tree_Category_Root$rand = new Ext.tree.AsyncTreeNode({
         text     : '',
         draggable   : false,
         id    : '-1'                  // this IS the id of the startnode
      });
      Tree_Category$rand.setRootNode(Tree_Category_Root$rand);";

      // Render the tree.
      echo "Tree_Category$rand.render();
            Tree_Category_Root$rand.expand();";

      echo "</script>";

      echo "<div id='tree_projectcategory$rand' ></div>";
      echo "</div>";
   }

   
   
   static function getServerFileTree($params) {

      $nodes = array();

      if (isset($params['node'])) {

         //root node
         $dir = "/var/www/glpi"; // TODO : add config option as 0.83 version

         // leaf node
         if ($params['node'] != -1) {
            $dir = $params['node'];
         }
         
         if (($handle = opendir($dir))) {
            $folders = $files = array();

            //list files in dir selected
            //we store folders and files separately to sort them alphabeticaly separatly
            while (FALSE !== ($entry = readdir($handle))) {
               if ($entry != "." && $entry != "..") {
                  $filepath = $dir."/".$entry;
                  if (is_dir($filepath)) {
                     $folders[$filepath] = $entry;
                  } else {
                     $files[$filepath] = $entry;
                  }
               }
            }

            //sort folders and files (and maintain index association)
            asort($folders);
            asort($files);

            //add folders in json
            foreach ($folders as $filepath => $entry) {
               $path['text'] = $entry;
               $path['id'] = $filepath;
               $path['draggable'] = FALSE;
               $path['leaf']      = FALSE;
               $path['cls']       = 'folder';

               $nodes[] = $path;
            }

            //add files in json
            foreach ($files as $filepath => $entry) {
               $path['text'] = $entry;
               $path['id'] = $filepath;
               $path['draggable'] = FALSE;
               $path['leaf']      = TRUE;
               $path['cls']       = 'file';

               $nodes[] = $path;
            }

            closedir($handle);
         }        
      }

      print json_encode($nodes);
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

   
   
   static function add_item($params) {
      echo "file::add_item";
      Html::printCleanArray($params);
      Html::printCleanArray($_FILES);
      exit;
   }

   
   
   static function remove_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //remove selected checks
      foreach ($params['file_entries'] as $index) {
         //get sha512
         $sha512 = $datas['jobs']['associatedFiles'][$index];

         //remove file
         unset($datas['jobs']['associatedFiles'][$index]);
         unset($datas['associatedFiles'][$sha512]);
      }

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }

   
   
   static function move_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //get data on old index
      $moved_check = $datas['jobs']['associatedFiles'][$params['old_index']];

      //remove this old index in json
      unset($datas['jobs']['associatedFiles'][$params['old_index']]);

      //insert it in new index (array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($datas['jobs']['associatedFiles'], $params['new_index'], 0, array($moved_check));

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }

   
   
   static function getDirBySha512 ($sha512) {
      $first = substr($sha512, 0, 1);
      $second = substr($sha512, 0, 2);

      return "$first/$second";
   }

   
   
   function registerFilepart ($repoPath, $filePath) {
      $sha512 = hash_file('sha512', $filePath);

      $dir = $repoPath.'/'.self::getDirBySha512($sha512);

      if (!file_exists ($dir)) {
         mkdir($dir, 0700, TRUE);
      }
      copy ($filePath, $dir.'/'.$sha512.'.gz');

      return $sha512;
   }

   
   
   function addFileInRepo ($params) {
      set_time_limit(600);

      $pfDeployFilepart = new PluginFusioninventoryDeployFilepart();

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
         print "{\"success\": \"false\", \"file\": \"{".
            $filename."}\", \"msg\": \"File already exists.\"}";
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

      $fdPart = NULL;
      do {
         clearstatcache();
         if (file_exists($tmpFilepart)) {
            if (feof($fdIn) || filesize($tmpFilepart)>= $maxPartSize) {
               $part_sha512 = $this->registerFilepart ($repoPath, $tmpFilepart);
               $part_short_sha512 = substr($part_sha512, 0, 6);
               $pfDeployFilepart->add(
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

      $rows = $this->find("plugin_fusioninventory_deployorders_id = '$order_id'
            AND shortsha512 = '".substr($sha512, 0, 6 )."'
            AND sha512 = '$sha512'"
      );
      if (count($rows) > 0) {
         return TRUE;
      }

      return FALSE;
   }

   

   function removeFileInRepo($id) {
      global $DB;

      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/";

      $pfDeployFilepart = new PluginFusioninventoryDeployFilepart();

      // Retrieve file informations
      $this->getFromDB($id);

      // Delete file in folder
      $sha512 = $this->getField('sha512');
      $filepart = $pfDeployFilepart->getForFile($id);
      $ids = $pfDeployFilepart->getIdsForFile($id);

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
         foreach($filepart as $hash){
            $dir = $repoPath.self::getDirBySha512($hash).'/';

            //delete file part
            unlink($dir.$hash.'.gz');
         }

         // delete parts objects
         foreach($ids as $id => $sha512){
            $pfDeployFilepart->delete(array('id' =>$id));
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

      return __('Max file size', 'fusioninventory')

         ." : ".min($max_upload, $max_post, $memory_limit).__('Mio', 'fusioninventory');

   }

   
   
   function uploadFile() {

      //if file sent is from server
      if (isset($_POST['itemtype']) && $_POST['itemtype'] == 'fileserver') {
         return $this->uploadFileFromServer();
      }


      if (isset ($_POST["id"]) and !$_POST['id']) {

         //file uploaded?
         $filename = NULL;
         $file_tmp_name = NULL;
         if (isset($_FILES['file']['tmp_name']) and !empty($_FILES['file']['tmp_name'])){
            $file_tmp_name = $_FILES['file']['tmp_name'];
         } /*elseif(isset($_POST['url']) and !empty($_POST['url'])) {
            $filename = $_POST['filename'];
         }*/
         if (isset($_FILES['file']['name']) 
                 && !empty($_FILES['file']['name'])) {
            $filename = $_FILES['file']['name'];
         }

         //file upload errors
         if (isset($_FILES['file']['error'])) {
            $msg = "file:'{$filename}', ";
            $error = TRUE;
            switch ($_FILES['file']['error']) {
               case UPLOAD_ERR_INI_SIZE:
               case UPLOAD_ERR_FORM_SIZE:
                  $msg .= __("Transfer error: the file size is too big", 'fusioninventory');
                  break;
               case UPLOAD_ERR_PARTIAL:
                  $msg .= __("he uploaded file was only partially uploaded", 'fusioninventory');
                  break;
               case UPLOAD_ERR_NO_FILE:
                  $msg .= __("No file was uploaded", 'fusioninventory');
                  break;
               case UPLOAD_ERR_NO_TMP_DIR:
                  $msg .= __("Missing a temporary folder", 'fusioninventory');
                  break;
               case UPLOAD_ERR_CANT_WRITE:
                  $msg .= __("Failed to write file to disk", 'fusioninventory');
                  break;
               case UPLOAD_ERR_CANT_WRITE:
                  $msg .= __("PHP extension stopped the file upload", 'fusioninventory');
                  break;
               case UPLOAD_ERR_OK:
                  //no error, continue
                  $error = FALSE;
            }
            if ($error) {
               Session::addMessageAfterRedirect($msg);
               return FALSE;
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
            'orders_id' => $orders_id
         );

         //Add file in repo
         if ($filename && $this->addFileInRepo($data)) {
            $msg = "file:'{$filename}', \"{".__('File saved!', 'fusioninventory')."}\"}";
         } else {
            $msg = "file:'{$filename}', ".__('File missing', 'fusioninventory');
         }
         Session::addMessageAfterRedirect($msg);
         return TRUE;
      }
      Session::addMessageAfterRedirect(__('File missing', 'fusioninventory'));
      return FALSE;
   }

   
   
   function uploadFileFromServer() {

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');
      $pfConfig = new PluginFusioninventoryConfig;
      $server_upload_path = $pfConfig->getValue($plugins_id, 'server_upload_path');


      $package_id = $_GET['package_id'];
      $render     = $_GET['render'];

      if (preg_match('/\.\./', $_POST['file_server'])) {
         die;
      }

      $render1   = PluginFusioninventoryDeployOrder::getRender($render);
      $order_id = PluginFusioninventoryDeployOrder::getIdForPackage($package_id, $render1);

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
            print "{success:true, file:'{$filename}',".
               "msg:\"{__('File saved!', 'fusioninventory')}\"}";
            exit;
         } else {
            print "{success:false, file:'{$filename}',".
               "msg:\"{__('Failed to copy file', 'fusioninventory')}\"}";
            exit;
         }
      } print "{success:false, file:'none',msg:\"{__('File missing', 'fusioninventory')}\"}";
   }

   
   
   static function processFilesize($filesize) {
      if ($filesize >= (1024 * 1024 * 1024)) {
         $filesize = round($filesize / (1024 * 1024 * 1024), 1)."GiB";
      } elseif ($filesize >= 1024 * 1024) {
         $filesize = round($filesize /  (1024 * 1024), 1)."MiB";

      } elseif ($filesize >= 1024) {
         $filesize = round($filesize / 1024, 1)."KB";

      } else {
         $filesize = $filesize."B";
      }
      return $filesize;
   }

}

?>
