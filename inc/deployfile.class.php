<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Alexandre Delaunay
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

   const REGISTRY_NO_DB_ENTRY = 0x1;
   const REGISTRY_NO_MANIFEST = 0x2;

   static function canCreate() {
      return PluginFusioninventoryProfile::haveRight('packages', 'w');
   }

   static function canView() {
      return PluginFusioninventoryProfile::haveRight('packages', 'r');
   }

   static function canDelete() {
      return self::canEdit();
   }
   static function canEdit() {
      return PluginFusioninventoryProfile::haveRight('packages', 'w');
   }


   static function getTypes() {
      return array(
         'Computer' => __("Upload from computer", 'fusioninventory'),
         'Server'   => __("Upload from server", 'fusioninventory')
      );
   }



   static function displayForm($order, $request_data, $rand, $mode) {
      global $CFG_GLPI;

      /*
       * Get element config in 'edit' mode
       */
      $config = NULL;
      if ( $mode === 'edit' && isset( $request_data['index'] ) ) {
         /*
          * Add an hidden input about element's index to be updated
          */
         echo "<input type='hidden' name='index' value='".$request_data['index']."' />";

         $c = $order->getSubElement( 'associatedFiles', $request_data['index'] );
         if ( $c ) {

            $config = array(
               'hash' => $c,
               'data' => $order->getAssociatedFile($c)
            );
         }
      }

      /*
       * Display start of div form
       */
      if ( in_array( $mode, array('init'), TRUE ) ) {
         echo "<div id='files_block$rand' style='display:none'>";
      }

      /*
       * Display element's dropdownType in 'create' mode
       */
      if ( in_array( $mode, array('create'), TRUE ) ) {
         self::displayDropdownType($config,$request_data, $rand, $mode);
      }

      /*
       * Display element's values in 'edit' mode only.
       * In 'create' mode, those values are refreshed with dropdownType 'change'
       * javascript event.
       */
      if ( in_array( $mode, array('create', 'edit'), TRUE ) ) {
         echo "<span id='show_file_value{$rand}'>";
         if ( $mode === 'edit' ) {
            self::displayAjaxValues( $config, $request_data, $rand, $mode );
         }
         echo "</span>";
      }


      /*
       * Close form div
       */
      if ( in_array( $mode, array('init'), TRUE ) ) {
         echo "</div>";
      }

   }


   static function displayList(PluginFusioninventoryDeployOrder $order, $datas, $rand) {
      global $CFG_GLPI;

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployPackage->getFromDB($order->fields['plugin_fusioninventory_deploypackages_id']);

      $o_file = new self;

      // compute short shas to find the corresponding entries in database
      $short_shas = array();
      foreach ($datas['jobs']['associatedFiles'] as $sha512) {
         $short_shas[] = "'".substr($sha512, 0, 6)."'";
      }
      // find corresponding file entries
      $files = $o_file->find(
         "shortsha512 IN (".implode(",", $short_shas).")"
      );
      // do a quick mapping between database id and short shas
      $files_mapping = array();
      foreach($files as $f) {
         $files_mapping[$f['shortsha512']] = $f['id'];
      }

      echo "<table class='tab_cadrehov package_item_list' id='table_file_$rand'>";
      $i = 0;
      foreach ($datas['jobs']['associatedFiles'] as $sha512) {
         $short_sha = substr($sha512, 0, 6);

         $fileregistry_error = 0;
         // check if the files is registered in database
         if (!array_key_exists($short_sha, $files_mapping)) {
            $fileregistry_error |= self::REGISTRY_NO_DB_ENTRY;
         }

         if (!$o_file->checkPresenceManifest($sha512)) {
            $fileregistry_error |= self::REGISTRY_NO_MANIFEST;
         }

         // get database entries
         if ( !$fileregistry_error ) {
            $file_id = $files_mapping[$short_sha];
            // get file's name
            $file_name = $files[$file_id]['name'];
            // get file's size
            $file_size = $files[$file_id]['filesize'];

            //mimetype icon
            if (isset($files[$file_id]['mimetype']) ) {
               $file_mimetype =
                  str_replace(
                     '/', '__',
                     $files[$file_id]['mimetype']);
            } else {
               $file_mimetype = NULL;
            }
         } else {
            // get file's name from what has been saved in json
            $file_name = $datas['associatedFiles'][$sha512]['name'];
            $file_size = NULL;
            $file_mimetype = NULL;

         }
         $file_uncompress = $datas['associatedFiles'][$sha512]['uncompress'];
         $file_p2p = $datas['associatedFiles'][$sha512]['p2p'];
         $file_p2p_retention_duration =
            $datas['associatedFiles'][$sha512]['p2p-retention-duration'];

         // start new line
         $pics_path = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/";
         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         if ($pfDeployPackage->can($pfDeployPackage->getID(), 'w')) {
            echo "<td class='control'>";
            echo "<input type='checkbox' name='file_entries[]' value='$i' />";
            echo "</td>";
         }
         echo "<td class='filename'>";
         if (
               !empty($file_mimetype)
            && file_exists(
               GLPI_ROOT."/plugins/fusioninventory/pics/ext/extensions/$file_mimetype.png"
            )
         ) {
            echo
               "<img src='".$pics_path.
               "ext/extensions/$file_mimetype.png' />";
         } else {
            echo
               "<img src='".$pics_path.
               "ext/extensions/documents.png' />";
         }

         //filename
         echo  "&nbsp;".
               "<a class='edit' ".
               "  onclick=\"edit_subtype(".
               "   'file', {$order->fields['id']}, $rand, this ".
               "  )\"".
               ">$file_name</a>";

         //p2p icon
         if (isset($file_p2p)
            && $file_p2p != 0) {
            echo "<a title='".__('p2p', 'fusioninventory').", "
            .__("retention", 'fusioninventory')." : ".
               $file_p2p_retention_duration." ".
               __("days", 'fusioninventory')."' class='more'>";
               echo  "<img src='".$pics_path.
                     "p2p.png' />";
               echo "<sup>".$file_p2p_retention_duration."</sup>";
               echo "</a>";
         }

         //uncompress icon
         if (
            isset($file_uncompress)
            && $file_uncompress != 0
         ) {
               echo  "<a title='".
                     __('uncompress', 'fusioninventory').
                     "' class='more'><img src='".
                     $pics_path.
                     "uncompress.png' /></a>";
         }
         //sha fingerprint
         $sha_status = "good";
         if( $fileregistry_error != 0) {
               $sha_status = "bad";
         }
         echo  "<div class='fingerprint'>";
         echo     "<div class='fingerprint_".$sha_status."'>".
                     $sha512;
         if ($fileregistry_error & self::REGISTRY_NO_DB_ENTRY) {
            echo  "<div class='fingerprint_badmsg'>".
                  __("This file is not correctly registered in database.")."<br/>".
                  __("You can fix it by uploading or selecting the good one.");
            echo  "</div>";
         }
         if ($fileregistry_error & self::REGISTRY_NO_MANIFEST) {
            echo  "<div class='fingerprint_badmsg'>".
                  __("This file doesn't have any manifest file associated.")."<br/>".
                  __("You must upload the file.");
            echo  "</div>";
         }
         echo     "</div>";
         echo  "</div>";


         //filesize
         if(!$fileregistry_error) {
            echo "<div class='size'>";
            echo __('Size').": ".self::processFilesize($file_size);
            echo "</div>";
         }
         echo "</td>";
         if ($pfDeployPackage->can($pfDeployPackage->getID(), 'w')) {
            echo "<td class='rowhandler control' title='".__('drag', 'fusioninventory').
               "'><div class='drag row'></div></td>";
         }
         $i++;
      }
      if ($pfDeployPackage->can($pfDeployPackage->getID(), 'w')) {
         echo "<tr><th>";
         Html::checkAllAsCheckbox("filesList$rand", mt_rand());
         echo "</th><th colspan='3' class='mark'></th></tr>";
      }
      echo "</table>";
      if ($pfDeployPackage->can($pfDeployPackage->getID(), 'w')) {
         echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt=''>";
         echo "<input type='submit' name='delete' value=\"".
            __('Delete', 'fusioninventory')."\" class='submit'>";
      }
   }


   static function displayDropdownType($config, $request_data, $rand, $mode) {
      global $CFG_GLPI;

      /*
       * Build dropdown options
       */
      $dropdown_options['rand'] = $rand;
      if ($mode === 'edit') {
         $dropdown_options['value'] = $config['type'];
         $dropdown_options['readonly'] = true;
      }

      /*
       * Build actions types list
       */

      $file_types = self::getTypes();
      array_unshift($file_types, "---");

      /*
       * Display dropdown html
       */
      $style = "";
      if (isset($datas['edit'])) {
         $style = "style='display:none'";
      }
      echo "<table class='package_item' $style>";
      echo "<tr>";
      echo "<th>".__("Source", 'fusioninventory')."</th>";
      echo "<td>";
      Dropdown::showFromArray("deploy_filetype", $file_types, $dropdown_options);
      echo "</td>";
      echo "</tr></table>";

      //ajax update of file value span

      if ($mode === 'create') {
         $params = array(
            'value'  => '__VALUE__',
            'rand'   => $rand,
            'myname' => 'method',
            'type'   => "file",
            'mode'   => $mode
         );

         Ajax::updateItemOnEvent(
            "dropdown_deploy_filetype".$rand,
            "show_file_value$rand",
            $CFG_GLPI["root_doc"].
            "/plugins/fusioninventory/".
            "ajax/deploy_displaytypevalue.php",
            $params,
            array("change", "load")
         );
      }
   }



   static function displayAjaxValues($config, $request_data, $rand, $mode) {
      global $CFG_GLPI;

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployOrder = new PluginFusioninventoryDeployOrder();

      if (isset($request_data['orders_id'])) {
         $pfDeployOrder->getFromDB($request_data['orders_id']);
         $pfDeployPackage->getFromDB($pfDeployOrder->fields['plugin_fusioninventory_deploypackages_id']);
      } else {
         $pfDeployPackage->getEmpty();
      }

      $p2p = 0;
      $p2p_retention_duration = 0;
      $uncompress = 0;

      if ($mode === 'create' ) {
         $source = $request_data['value'];
         /**
          * No need to continue if there is no selected source
          */
         if ($source === '0') {
            return;
         }
      } else {
         $p2p = $config['data']['p2p'];
         $p2p_retention_duration = $config['data']['p2p-retention-duration'];
         $uncompress = $config['data']['uncompress'];
      };

      echo "<table class='package_item'>";
      /*
       * Display file upload input only in 'create' mode
       */
      echo "<tr>";
      echo "<th>".__("File", 'fusioninventory')."</th>";
      echo "<td>";
      if ( $mode === 'create' ) {
         switch ($source) {
            case "Computer":
               echo "<input type='file' name='file' value='".
                  __("filename", 'fusioninventory')."' />";
               echo "<i>".self::getMaxUploadSize()."</i>";
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
      } else {
         /*
          * Display only name in 'edit' mode
          */
         echo $config['data']['name'];
      }
      echo "</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<th>".__("Uncompress", 'fusioninventory')."<img style='float:right' ".
         "src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory//pics/uncompress.png' /></th>";
      $uncompress_check = $uncompress==1?"checked=checked":"";
      echo "<td><input type='checkbox' name='uncompress' {$uncompress_check} /></td>";
      echo "</tr><tr>";
      echo "<th>".__("P2P", 'fusioninventory').
              "<img style='float:right' src='".$CFG_GLPI["root_doc"].
              "/plugins/fusioninventory//pics/p2p.png' /></th>";

      $p2p_check = $p2p==1?"checked=checked":"";
      echo "<td><input type='checkbox' name='p2p' $p2p_check /></td>";
      echo "</tr><tr>";
      echo "<th>".__("retention days", 'fusioninventory')."</th>";
      echo "<td>";
      /*
       * TODO: use task periodicity input to propose days, months and years
       */
      Dropdown::showNumber('p2p-retention-duration', array(
             'value' => $p2p_retention_duration,
             'min'   => 0,
             'max'   => 400)
      );
      echo "</td>";
      echo "</tr><tr>";
      echo "<td>";
      echo "</td><td>";
      if ($pfDeployPackage->can($pfDeployPackage->getID(), 'w')) {
         if ( $mode === 'edit' ) {
            echo "<input type='submit' name='save_item' value=\"".
               _sx('button', 'Save')."\" class='submit' >";
         } else {
            echo "<input type='submit' name='add_item' value=\"".
               _sx('button', 'Add')."\" class='submit' >";
         }
      }
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
         $pfConfig = new PluginFusioninventoryConfig();
         $dir = $pfConfig->getValue('server_upload_path');

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
      switch ($params['deploy_filetype']) {
         case 'Server':
            self::uploadFileFromServer($params);
            break;
         default:
            self::uploadFileFromComputer($params);
      }
   }


   static function remove_item($params) {
      if (!isset($params['file_entries'])) {
         return FALSE;
      }

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      $files = $datas['jobs']['associatedFiles'];
      //remove selected checks
      foreach ($params['file_entries'] as $index) {
         //get sha512
         $sha512 = $datas['jobs']['associatedFiles'][$index];

         //remove file
         // I've commented the following piece of code because
         // if you remove the first line in the files list,
         // PHP will transform these table as a json dictionnary instead of json list.
         unset($files[$index]);
         //array_splice($datas['jobs']['associatedFiles'], $index, 1);
         unset($datas['associatedFiles'][$sha512]);

         //remove file in repo
         //self::removeFileInRepo($sha512, $params['orders_id']);
      }
      $datas['jobs']['associatedFiles'] = array_values($files);
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


   static function save_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //get sha512
      $sha512 = $datas['jobs']['associatedFiles'][$params['index']];

      //get file in json
      $file = $datas['associatedFiles'][$sha512];

      //remove value in json
      unset($datas['associatedFiles'][$sha512]);

      //update values
      $file['p2p']                    = isset($params['p2p']) ? 1 : 0;
      $file['p2p-retention-duration'] = $params['p2p-retention-duration'];
      $file['uncompress']             = isset($params['uncompress']) ? 1 : 0;

      //add modified entry
      $datas['associatedFiles'][$sha512] = $file;

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);
   }

   static function uploadFileFromComputer($params) {
      if (isset($params["orders_id"])) {

         //file uploaded?
         if (isset($_FILES['file']['tmp_name']) and !empty($_FILES['file']['tmp_name'])){
            $file_tmp_name = $_FILES['file']['tmp_name'];
         }
         if (isset($_FILES['file']['name'])
                 && !empty($_FILES['file']['name'])) {
            $filename = $_FILES['file']['name'];
         }

         //file upload errors
         if (isset($_FILES['file']['error'])) {
            $error = TRUE;
            switch ($_FILES['file']['error']) {
               case UPLOAD_ERR_INI_SIZE:
               case UPLOAD_ERR_FORM_SIZE:
                  $msg = __("Transfer error: the file size is too big", 'fusioninventory');
                  break;
               case UPLOAD_ERR_PARTIAL:
                  $msg = __("The uploaded file was only partially uploaded", 'fusioninventory');
                  break;
               case UPLOAD_ERR_NO_FILE:
                  $msg = __("No file was uploaded", 'fusioninventory');
                  break;
               case UPLOAD_ERR_NO_TMP_DIR:
                  $msg = __("Missing a temporary folder", 'fusioninventory');
                  break;
               case UPLOAD_ERR_CANT_WRITE:
                  $msg = __("Failed to write file to disk", 'fusioninventory');
                  break;
               case UPLOAD_ERR_CANT_WRITE:
                  $msg = __("PHP extension stopped the file upload", 'fusioninventory');
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
         $datas = array(
            'file_tmp_name' => $file_tmp_name,
            'mime_type' => $_FILES['file']['type'],
            'filesize' => $_FILES['file']['size'],
            'filename' => $filename,
            'p2p' => isset($_POST['p2p']) ? 1 : 0,
            'uncompress' => isset($_POST['uncompress']) ? 1 : 0,
            'p2p-retention-duration' => is_numeric($params['p2p-retention-duration']) ?
               $params['p2p-retention-duration'] : 0,
            'orders_id' => $params['orders_id']
         );

         //Add file in repo
         if ($filename && self::addFileInRepo($datas)) {
            Session::addMessageAfterRedirect(__('File saved!', 'fusioninventory'));
            return TRUE;
         } else {
            Session::addMessageAfterRedirect(__('Failed to copy file', 'fusioninventory'));
            return FALSE;
         }
      }
      Session::addMessageAfterRedirect(__('File missing', 'fusioninventory'));
      return FALSE;
   }

   static function uploadFileFromServer($params) {

      if (preg_match('/\.\./', $params['filename'])) {
         die;
      }

      if (isset($params["orders_id"])) {
         $file_path = $params['filename'];
         $filename = basename($file_path);
         if (function_exists('finfo_open')
             && ($finfo = finfo_open(FILEINFO_MIME))) {
            $mime_type = finfo_file($finfo, $file_path);
            finfo_close($finfo);

         } else if (function_exists('mime_content_type')) {
            $mime_type = mime_content_type($file_path);
         }
         $filesize = filesize($file_path);

         //prepare file data for insertion in repo
         $datas = array(
            'file_tmp_name' => $file_path,
            'mime_type' => $mime_type,
            'filesize' => $filesize,
            'filename' => $filename,
            'p2p' => isset($_POST['p2p']) ? 1 : 0,
            'uncompress' => isset($_POST['uncompress']) ? 1 : 0,
            'p2p-retention-duration' => is_numeric($_POST['p2p-retention-duration']) ?
               $_POST['p2p-retention-duration'] : 0,
            'orders_id' => $params['orders_id']
         );

         //Add file in repo
         if ($filename && self::addFileInRepo($datas)) {
            Session::addMessageAfterRedirect(__('File saved!', 'fusioninventory'));
            return TRUE;
         } else {
            Session::addMessageAfterRedirect(__('Failed to copy file', 'fusioninventory'));
            return FALSE;
         }
      }
      Session::addMessageAfterRedirect(__('File missing', 'fusioninventory'));
      return FALSE;
   }



   static function getDirBySha512 ($sha512) {
      $first = substr($sha512, 0, 1);
      $second = substr($sha512, 0, 2);

      return "$first/$second";
   }



   function registerFilepart ($repoPath, $filePath, $skip_creation = FALSE) {
      $sha512 = hash_file('sha512', $filePath);

      if (!$skip_creation) {
         $dir = $repoPath.'/'.self::getDirBySha512($sha512);

         if (!file_exists ($dir)) {
            mkdir($dir, 0777, TRUE);
         }
         copy ($filePath, $dir.'/'.$sha512);
      }

      return $sha512;
   }



   static function addFileInRepo ($params) {
      set_time_limit(600);

      $deployFile = new self;


      $filename = addslashes($params['filename']);
      $file_tmp_name = $params['file_tmp_name'];

      $maxPartSize = 1024*1024;
      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/repository/";
      $tmpFilepart = tempnam(GLPI_PLUGIN_DOC_DIR."/fusioninventory/", "filestore");

      $sha512 = hash_file('sha512', $file_tmp_name);
      $short_sha512 = substr($sha512, 0, 6);

      $file_present_in_repo = FALSE;
      if($deployFile->checkPresenceFile($sha512)) {
         $file_present_in_repo = TRUE;
      }

      $file_present_in_db =
         $deployFile->getFromDBByQuery(
            "WHERE shortsha512 = '". $short_sha512 ."'"
         );

      //Manifest files contains the multiparts list attached to the file
      $manifest_path = GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/manifests/";
      $manifest_filename = $manifest_path . $sha512;

      $new_entry = array(
         'name' => $filename,
         'p2p' => $params['p2p'],
//         'mimetype' => $params['mime_type'],
//         'filesize' => $params['filesize'],
         'p2p-retention-duration' => $params['p2p-retention-duration'],
         'uncompress' => $params['uncompress'],
      );

      $fdIn = fopen ( $file_tmp_name, 'rb' );
      if (!$fdIn) {
         return FALSE;
      }

      $fdPart = NULL;
      $multiparts = array();
      do {
         clearstatcache();
         if (file_exists($tmpFilepart)) {
            if (feof($fdIn) || filesize($tmpFilepart)>= $maxPartSize) {
               $part_sha512 = $deployFile->registerFilepart($repoPath, $tmpFilepart,
                                                            $file_present_in_repo);
               unlink($tmpFilepart);

               $multiparts[] = $part_sha512;
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

//      $new_entry['multiparts'] = $multiparts;
      //create manifest file
      if (!$file_present_in_repo) {
         $handle = fopen(
           $manifest_filename, "w+"
         );
         if ($handle) {
            foreach($multiparts as $sha) {
               fwrite($handle, $sha."\n");
            }
            fclose($handle);
         }
      }

      //TODO: Add a new files interface to list, create, manage entities and visibility
      // entity on a file is just anticipated and will be fully used later
      if (!$file_present_in_db) {
         $entry = array(
            "name" => $filename,
            "filesize" => $params['filesize'],
            "mimetype" => $params['mime_type'],
            "sha512" => $sha512,
            "shortsha512" => $short_sha512,
            "comments" => "",
            "date_mod" => date('Y-m-d H:i:s'),
            "entities_id" => 0,
            "is_recursive" => 1
         );
         $deployFile->add($entry);
      }
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($params['orders_id']), TRUE);

      //add new entry
      $datas['associatedFiles'][$sha512] = $new_entry;
      if (!in_array($sha512, $datas['jobs']['associatedFiles'])) {
         $datas['jobs']['associatedFiles'][] = $sha512;
      }

      //update order
      PluginFusioninventoryDeployOrder::updateOrderJson($params['orders_id'], $datas);

      return TRUE;
   }




   static function removeFileInRepo($sha512, $orders_id) {

      $repoPath = GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/repository/";
      $manifestsPath = GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/manifests/";

      $order = new PluginFusioninventoryDeployOrder;
      $rows = $order->find("id != '$orders_id'
            AND json LIKE '%".substr($sha512, 0, 6 )."%'
            AND json LIKE '%$sha512%'"
      );
      if (count($rows) > 0) {
         //file found in other order, do not remove part in repo
         return FALSE;
      }

      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployOrder::getJson($orders_id), TRUE);
      $multiparts = $datas['associatedFiles'][$sha512]['multiparts'];

      //parse all files part
      foreach ($multiparts as $part_sha512) {
         $dir = $repoPath.self::getDirBySha512($part_sha512).'/';

         //delete file parts
         unlink($dir.$part_sha512);
      }

      return TRUE;
   }


   function checkPresenceManifest($sha512) {

      $manifests_path =
         GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/manifests/";
      if (!file_exists($manifests_path.$sha512)) {
         return FALSE;
      }
      return TRUE;
   }

   function checkPresenceFile($sha512) {
      $manifests_path =
         GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/manifests/";
      $parts_path =
         GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/repository/";

      //Do not continue if the manifest is not found
      if ( !$this->checkPresenceManifest($sha512) ) {
         return FALSE;
      }

      //Does the file needs to be created ?
      // Even if fileparts exists, we need to be sure
      // the manifest file is created
      $fileparts_ok = TRUE;
      $fileparts_cnt = 0;
      $handle = fopen($manifests_path.$sha512, "r");
      if ($handle) {
         while( ($buffer = fgets($handle) !== FALSE) ) {
            $fileparts_cnt++;
            $path =
               substr($buffer, 0, 1).
               "/".
               substr($buffer, 0, 2).
               "/".
               $buffer;
            //Check if the filepart exists
            if( !file_exists($parts_path . $path) ) {
               $fileparts_ok = FALSE;
               break;
            }
         }
         fclose($handle);
      }
      // Does the file is empty ?
      if ($fileparts_cnt == 0) {
         return FALSE;
      }

      //Does the file needs to be replaced ?
      if (!$fileparts_ok) {
         return FALSE;
      }
      //Nothing to do because the manifest and associated fileparts seems to be fine.
      return TRUE;
   }


   static function getMaxUploadSize() {

      $max_upload = (int)(ini_get('upload_max_filesize'));
      $max_post = (int)(ini_get('post_max_size'));
      $memory_limit = (int)(ini_get('memory_limit'));

      return __('Max file size', 'fusioninventory')

         ." : ".min($max_upload, $max_post, $memory_limit).__('Mio', 'fusioninventory');

   }

   static function processFilesize($filesize) {
      if(is_numeric($filesize)) {
         if ($filesize >= (1024 * 1024 * 1024)) {
            $filesize = round($filesize / (1024 * 1024 * 1024), 1)."GiB";
         } else if ($filesize >= 1024 * 1024) {
            $filesize = round($filesize /  (1024 * 1024), 1)."MiB";

         } else if ($filesize >= 1024) {
            $filesize = round($filesize / 1024, 1)."KB";

         } else {
            $filesize = $filesize."B";
         }
         return $filesize;
      } else {
         return "N/A";
      }
   }

}

?>
