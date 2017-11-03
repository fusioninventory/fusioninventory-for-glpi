<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the files to deploy.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Alexandre Delaunay
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the files to deploy.
 */
class PluginFusioninventoryDeployFile extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_package';

   const REGISTRY_NO_DB_ENTRY = 0x1;
   const REGISTRY_NO_MANIFEST = 0x2;


   /**
    * Get the 2 types to add files
    *
    * @return array
    */
   static function getTypes() {
      return array(
         'Computer' => __("Upload from computer", 'fusioninventory'),
         'Server'   => __("Upload from server", 'fusioninventory')
      );
   }



   /**
    * Display form
    *
    * @param object $package PluginFusioninventoryDeployPackage instance
    * @param array $request_data
    * @param string $rand unique element id used to identify/update an element
    * @param string $mode possible values: init|edit|create
    */
   static function displayForm(PluginFusioninventoryDeployPackage $package, $request_data, $rand, $mode) {
      /*
       * Get element config in 'edit' mode
       */
      $config = NULL;
      if ($mode === 'edit' && isset($request_data['index'])) {
         /*
          * Add an hidden input about element's index to be updated
          */
         echo "<input type='hidden' name='index' value='".$request_data['index']."' />";

         $element = $package->getSubElement('associatedFiles', $request_data['index']);
         if ($element) {

            $config = array(
               'hash' => $element,
               'data' => $package->getAssociatedFile($element)
            );
         }
      }

      /*
       * Display start of div form
       */
      if (in_array($mode, array('init'), TRUE)) {
         echo "<div id='files_block$rand' style='display:none'>";
      }

      /*
       * Display element's dropdownType in 'create' mode
       */
      if (in_array($mode, array('create'), TRUE)) {
         self::displayDropdownType($config, $rand, $mode);
      }

      /*
       * Display element's values in 'edit' mode only.
       * In 'create' mode, those values are refreshed with dropdownType 'change'
       * javascript event.
       */
      if (in_array($mode, array('create', 'edit'), TRUE)) {
         echo "<span id='show_file_value{$rand}'>";
         if ($mode === 'edit') {
            self::displayAjaxValues($config, $request_data, $rand, $mode);
         }
         echo "</span>";
      }

      /*
       * Close form div
       */
      if (in_array($mode, array('init'), TRUE)) {
         echo "</div>";
      }
   }



   /**
    * Display list of files
    *
    * @global array $CFG_GLPI
    * @param object $package PluginFusioninventoryDeployPackage instance
    * @param array $datas array converted of 'json' field in DB where stored actions
    * @param string $rand unique element id used to identify/update an element
    */
   static function displayList(PluginFusioninventoryDeployPackage $package, $datas, $rand) {
      global $CFG_GLPI;

      $o_file     = new self();
      $package_id = $package->getID();
      $canedit    = $package->canUpdateContent();

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
      foreach ($files as $file) {
         $files_mapping[$file['shortsha512']] = $file['id'];
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
         if (!$fileregistry_error) {
            $file_id = $files_mapping[$short_sha];
            $file_name = $files[$file_id]['name'];
            $file_size = $files[$file_id]['filesize'];

            //mimetype icon
            if (isset($files[$file_id]['mimetype'])) {
               $file_mimetype =
                  str_replace('/', '__', $files[$file_id]['mimetype']);
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
         if ($canedit) {
            echo "<td class='control'>";
            Html::showCheckbox(array('name' => 'file_entries['.$i.']', 'value' => 0));
            echo "</td>";
         }
         echo "<td class='filename'>";
         if (!empty($file_mimetype)
                 && file_exists(GLPI_ROOT."/plugins/fusioninventory/pics/extensions/$file_mimetype.png")) {
            echo "<img src='".$pics_path."extensions/$file_mimetype.png' />";
         } else {
            echo "<img src='".$pics_path."extensions/documents.png' />";
         }

         //filename
         echo "&nbsp;";
         if ($canedit) {
            echo "<a class='edit'
                     onclick=\"edit_subtype('file', $package_id, $rand ,this)\">";
         }
         echo $file_name;
         if ($canedit) {
            echo "</a>";
         }

         //p2p icon
         if (isset($file_p2p)
            && $file_p2p != 0) {
            echo "<a title='".__('p2p', 'fusioninventory').", "
               .__("retention", 'fusioninventory')." : ".
               $file_p2p_retention_duration." ".
               __("Minute(s)", 'fusioninventory')."' class='more'>";
            echo "<img src='".$pics_path."p2p.png' />";
            echo "<sup>".$file_p2p_retention_duration."</sup>";
            echo "</a>";
         }

         //uncompress icon
         if (isset($file_uncompress)
                 && $file_uncompress != 0) {
            echo "<a title='".
                     __('uncompress', 'fusioninventory').
                     "' class='more'><img src='".
                     $pics_path.
                     "uncompress.png' /></a>";
         }
         //sha fingerprint
         $sha_status = "good";
         if ($fileregistry_error != 0) {
            $sha_status = "bad";
         }
         echo "<div class='fingerprint'>";
         echo "<div class='fingerprint_".$sha_status."'>".$sha512;
         if ($fileregistry_error & self::REGISTRY_NO_DB_ENTRY) {
            echo "<div class='fingerprint_badmsg'>".
                  __("This file is not correctly registered in database.")."<br/>".
                  __("You can fix it by uploading or selecting the good one.");
            echo "</div>";
         }
         if ($fileregistry_error & self::REGISTRY_NO_MANIFEST) {
            echo "<div class='fingerprint_badmsg'>".
                  __("This file doesn't have any manifest file associated.")."<br/>".
                  __("You must upload the file.");
            echo "</div>";
         }
         echo "</div>";
         echo "</div>";


         //filesize
         if (!$fileregistry_error) {
            echo "<div class='size'>";
            echo __('Size').": ".self::processFilesize($file_size);
            echo "</div>";
         }
         echo "</td>";
         if ($canedit) {
            echo "<td class='rowhandler control' title='".
                    __('drag', 'fusioninventory').
                    "'><div class='drag row'></div></td>";
         }
         $i++;
      }
      if ($canedit) {
         echo "<tr><th>";
         Html::checkAllAsCheckbox("filesList$rand", mt_rand());
         echo "</th><th colspan='3' class='mark'></th></tr>";
      }
      echo "</table>";
      if ($canedit) {
         echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt=''>";
         echo "<input type='submit' name='delete' value=\"".
            __('Delete', 'fusioninventory')."\" class='submit'>";
      }
   }



   /**
    * Display the dropdown to select type of file
    *
    * @global array $CFG_GLPI
    * @param array $config order item configuration
    * @param string $rand unique element id used to identify/update an element
    * @param string $mode mode in use (create, edit...)
    */
   static function displayDropdownType($config, $rand, $mode) {
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
      if ($mode === 'edit') {
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
                 "/plugins/fusioninventory/ajax/deploy_displaytypevalue.php",
            $params,
            array("change", "load")
         );
      }
   }



   /**
    * Display different fields relative the file selected
    *
    * @global array $CFG_GLPI
    * @param array $config
    * @param array $request_data
    * @param string $rand unique element id used to identify/update an element
    * @param string $mode mode in use (create, edit...)
    * @return boolean
    */
   static function displayAjaxValues($config, $request_data, $rand, $mode) {
      global $CFG_GLPI;

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      if (isset($request_data['packages_id'])) {
         $pfDeployPackage->getFromDB($request_data['packages_id']);
      } else {
         $pfDeployPackage->getEmpty();
      }

      $p2p = 0;
      $p2p_retention_duration = 0;
      $uncompress = 0;

      if ($mode === 'create') {
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
      }

      echo "<table class='package_item'>";
      /*
       * Display file upload input only in 'create' mode
       */
      echo "<tr>";
      echo "<th>".__("File", 'fusioninventory')."</th>";
      echo "<td>";
      if ($mode === 'create') {
         switch ($source) {

            case "Computer":
               echo "<input type='file' name='file' value='".
                  __("filename", 'fusioninventory')."' />";
               echo " <i>".self::getMaxUploadSize()."</i>";
               break;

            case "Server":
               echo "<input type='text' name='filename' id='server_filename$rand'".
                  " style='width:500px;float:left' />";
               echo "<input type='button' class='submit' value='".__("Choose", 'fusioninventory').
                  "' onclick='fileModal$rand.dialog(\"open\");' />";
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
         "src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/uncompress.png' /></th>";
      echo "<td>";
      Html::showCheckbox(array('name' => 'uncompress', 'checked' => $uncompress));
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>".__("P2P", 'fusioninventory').
              "<img style='float:right' src='".$CFG_GLPI["root_doc"].
              "/plugins/fusioninventory//pics/p2p.png' /></th>";
      echo "<td>";
      Html::showCheckbox(array('name' => 'p2p', 'checked' => $p2p));
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>".__("retention", 'fusioninventory').
                  " - ".__("Minute(s)", 'fusioninventory')."</th>";
      echo "<td>";
      echo "<input type='number' name='p2p-retention-duration' value='$p2p_retention_duration' />";
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td>";
      echo "</td>";
      echo "<td>";
      if ($pfDeployPackage->can($pfDeployPackage->getID(), UPDATE)) {
         if ($mode === 'edit') {
            echo "<input type='submit' name='save_item' value=\"".
               _sx('button', 'Save')."\" class='submit' >";
         } else {
            echo "<input type='submit' name='add_item' value=\"".
               _sx('button', 'Add')."\" class='submit' >";
         }
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";
   }



   /**
    * Show files / directory on server.
    * This is used when get a file on the server
    *
    * @global array $CFG_GLPI
    * @param string $rand unique element id used to identify/update an element
    */
   static function showServerFileTree($rand) {
      global $CFG_GLPI;

      echo "<script type='text/javascript'>";
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
            click: function(node, event) {
               if (node.leaf == true) {
                  console.log('server_filename$rand');
                  Ext.get('server_filename$rand').dom.value = node.id;
                  fileModal$rand.dialog('close');
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



   /**
    * Get files / directories on server
    *
    * @param string $node
    */
   static function getServerFileTree($node) {

      $nodes = array();
      $pfConfig = new PluginFusioninventoryConfig();
      $dir = $pfConfig->getValue('server_upload_path');

      $security_problem = FALSE;
      if ($node != "-1") {
         if (strstr($node, "..")) {
            $security_problem = TRUE;
         }
         $matches = array();
         preg_match("/^(".str_replace("/", "\/", $dir).")(.*)$/", $node, $matches);
         if (count($matches) != 3) {
            $security_problem = TRUE;
         }
      }

      if (!$security_problem) {
         // leaf node
         if ($node != -1) {
            $dir = $node;
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
               $path['text']      = $entry;
               $path['id']        = $filepath;
               $path['draggable'] = FALSE;
               $path['leaf']      = FALSE;
               $path['cls']       = 'folder';

               $nodes[] = $path;
            }

            //add files in json
            foreach ($files as $filepath => $entry) {
               $path['text']      = $entry;
               $path['id']        = $filepath;
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



   /**
    * Add a new item in files of the package
    *
    * @param array $params list of fields with value of the file
    */
   static function add_item($params) {
      switch ($params['deploy_filetype']) {

         case 'Server':
            self::uploadFileFromServer($params);
            break;

         default:
            self::uploadFileFromComputer($params);

      }
   }



   /**
    * Remove an item
    *
    * @param array $params
    * @return boolean
    */
   static function remove_item($params) {
      if (!isset($params['file_entries'])) {
         return FALSE;
      }

      $shasToRemove = [];

      //get current order json
      $data = json_decode(PluginFusioninventoryDeployPackage::getJson($params['packages_id']), TRUE);

      $files = $data['jobs']['associatedFiles'];
      //remove selected checks

      foreach ($params['file_entries'] as $index => $checked) {
         if ($checked >= "1" || $checked == "on") {
            //get sha512
            $sha512 = $data['jobs']['associatedFiles'][$index];

            //remove file
            unset($files[$index]);
            unset($data['associatedFiles'][$sha512]);

            $shasToRemove[] = $sha512;
         }
      }
      $data['jobs']['associatedFiles'] = array_values($files);
      //update order
      PluginFusioninventoryDeployPackage::updateOrderJson($params['packages_id'], $data);

      //remove files in repo
      foreach ($shasToRemove as $sha512) {
         self::removeFileInRepo($sha512);
      }

      return true;
   }



   /**
    * Move an item
    *
    * @param array $params
    */
   static function move_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployPackage::getJson($params['id']), TRUE);

      //get data on old index
      $moved_check = $datas['jobs']['associatedFiles'][$params['old_index']];

      //remove this old index in json
      unset($datas['jobs']['associatedFiles'][$params['old_index']]);

      //insert it in new index (array_splice for insertion, ex : http://stackoverflow.com/a/3797526)
      array_splice($datas['jobs']['associatedFiles'], $params['new_index'], 0, array($moved_check));

      //update order
      PluginFusioninventoryDeployPackage::updateOrderJson($params['id'], $datas);
   }



   /**
    * Save the item in files
    *
    * @param array $params list of fields with value of the file
    */
   static function save_item($params) {
      //get current order json
      $datas = json_decode(PluginFusioninventoryDeployPackage::getJson($params['id']), TRUE);

      //get sha512
      $sha512 = $datas['jobs']['associatedFiles'][$params['index']];

      //get file in json
      $file = $datas['associatedFiles'][$sha512];

      //remove value in json
      unset($datas['associatedFiles'][$sha512]);

      //update values
      $file['p2p']                    = isset($params['p2p']) ? $params['p2p'] : 0;
      $file['p2p-retention-duration'] = $params['p2p-retention-duration'];
      $file['uncompress']             = isset($params['uncompress']) ? $params['uncompress'] : 0;

      //add modified entry
      $datas['associatedFiles'][$sha512] = $file;

      //update order
      PluginFusioninventoryDeployPackage::updateOrderJson($params['id'], $datas);
   }



   /**
    * Upload file from user computer
    *
    * @param array $params
    * @return boolean
    */
   static function uploadFileFromComputer($params) {
      if (isset($params["id"])) {

         //file uploaded?
         if (isset($_FILES['file']['tmp_name'])
                 && !empty($_FILES['file']['tmp_name'])) {
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

               case UPLOAD_ERR_EXTENSION:
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
         $data = array(
            'file_tmp_name' => $file_tmp_name,
            'mime_type' => $_FILES['file']['type'],
            'filesize' => $_FILES['file']['size'],
            'filename' => $filename,
            'p2p' => isset($params['p2p']) ? $params['p2p'] : 0,
            'uncompress' => isset($params['uncompress']) ? $params['uncompress'] : 0,
            'p2p-retention-duration' => (
               is_numeric($params['p2p-retention-duration'])
               ? $params['p2p-retention-duration']
               : 0
            ),
            'id' => $params['id']
         );

         //Add file in repo
         if ($filename && self::addFileInRepo($data)) {
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



   /**
    * Upload file from temp folder in server
    *
    * @param array $params
    * @return boolean
    */
   static function uploadFileFromServer($params) {

      if (preg_match('/\.\./', $params['filename'])) {
         die;
      }

      if (isset($params["id"])) {
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
         $data = array(
            'file_tmp_name' => $file_path,
            'mime_type'     => $mime_type,
            'filesize'      => $filesize,
            'filename'      => $filename,
            'p2p'           => isset($params['p2p']) ? $params['p2p'] : 0,
            'uncompress'    => isset($params['uncompress']) ? $params['uncompress'] : 0,
            'p2p-retention-duration' => (
               isset($params['p2p-retention-duration'])
               && is_numeric($params['p2p-retention-duration'])
                  ? $params['p2p-retention-duration']
                  : 0
            ),
            'id'            => $params['id']
         );

         //Add file in repo
         if ($filename && self::addFileInRepo($data)) {
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



   /**
    * Get directories based on sha512
    *
    * @param string $sha512
    * @return string the directories based on sha512
    */
   static function getDirBySha512 ($sha512) {
      $first = substr($sha512, 0, 1);
      $second = substr($sha512, 0, 2);

      return "$first/$second";
   }



   /**
    * Move uploaded file part in right/final directory
    *
    * @param string $filePath path of the file + filename
    * @param boolean $skip_creation
    * @return string
    */
   function registerFilepart ($filePath, $skip_creation=FALSE) {
      $sha512 = hash_file('sha512', $filePath);

      if (!$skip_creation) {
         $dir = PLUGIN_FUSIONINVENTORY_REPOSITORY_DIR.self::getDirBySha512($sha512);

         if (!file_exists ($dir)) {
            mkdir($dir, 0777, TRUE);
         }
         copy ($filePath, $dir.'/'.$sha512);
      }
      return $sha512;
   }



   /**
    * Add file in the fusioninventory repository
    *
    * @param array $params
    * @return boolean
    */
   static function addFileInRepo($params) {
      $deployFile = new self;

      $filename      = addslashes($params['filename']);
      $file_tmp_name = $params['file_tmp_name'];
      $maxPartSize   = 1024*1024;
      $tmpFilepart   = tempnam(GLPI_PLUGIN_DOC_DIR."/fusioninventory/", "filestore");
      $sha512        = hash_file('sha512', $file_tmp_name);
      $short_sha512  = substr($sha512, 0, 6);

      $file_present_in_repo = FALSE;
      if ($deployFile->checkPresenceFile($sha512)) {
         $file_present_in_repo = TRUE;
      }

      $file_present_in_db =
         $deployFile->getFromDBByQuery(
            "WHERE shortsha512 = '". $short_sha512 ."'"
         );

      $new_entry = array(
         'name'                   => $filename,
         'p2p'                    => $params['p2p'],
         'p2p-retention-duration' => $params['p2p-retention-duration'],
         'uncompress'             => $params['uncompress'],
      );

      $fdIn = fopen($file_tmp_name, 'rb');
      if (!$fdIn) {
         return FALSE;
      }

      $fdPart = NULL;
      $multiparts = array();
      do {
         clearstatcache();
         if (file_exists($tmpFilepart)) {
            if (feof($fdIn) || filesize($tmpFilepart)>= $maxPartSize) {
               $part_sha512 = $deployFile->registerFilepart($tmpFilepart,
                                                            $file_present_in_repo);
               unlink($tmpFilepart);

               $multiparts[] = $part_sha512;
            }
         }
         if (feof($fdIn)) {
            break;
         }

         $data = fread($fdIn, 1024*1024);
         $fdPart = gzopen ($tmpFilepart, 'a');
         gzwrite($fdPart, $data, strlen($data));
         gzclose($fdPart);
      } while (1);

      //create manifest file
      if (!$file_present_in_repo) {
         $handle = fopen(
           PLUGIN_FUSIONINVENTORY_MANIFESTS_DIR.$sha512, "w+"
         );
         if ($handle) {
            foreach ($multiparts as $sha) {
               fwrite($handle, $sha."\n");
            }
            fclose($handle);
         }
      }

      //TODO: Add a new files interface to list, create, manage entities and visibility
      // entity on a file is just anticipated and will be fully used later
      if (!$file_present_in_db) {
         $entry = array(
            "name"         => $filename,
            "filesize"     => $params['filesize'],
            "mimetype"     => $params['mime_type'],
            "sha512"       => $sha512,
            "shortsha512"  => $short_sha512,
            "comments"     => "",
            "date_mod"     => date('Y-m-d H:i:s'),
            "entities_id"  => 0,
            "is_recursive" => 1
         );
         $deployFile->add($entry);
      }
      //get current package json
      $datas = json_decode(PluginFusioninventoryDeployPackage::getJson($params['id']), TRUE);

      //add new entry
      $datas['associatedFiles'][$sha512] = $new_entry;
      if (!in_array($sha512, $datas['jobs']['associatedFiles'])) {
         $datas['jobs']['associatedFiles'][] = $sha512;
      }
      //update package
      PluginFusioninventoryDeployPackage::updateOrderJson($params['id'], $datas);

      return TRUE;
   }



   /**
    * Remove file from the repository
    *
    * @param string $sha512 sha512 of the file
    * @return boolean
    */
   static function removeFileInRepo($sha512) {
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      // try to find file in other packages
      $rows = $pfDeployPackage->find("`json` LIKE '%".substr($sha512, 0, 6 )."%'
                                  AND `json` LIKE '%$sha512%'" );

      //file found in other packages, do not remove parts in repo
      if (count($rows) > 0) {
         return false;
      }

      //get sha512 parts in manifest
      $multiparts = file(PLUGIN_FUSIONINVENTORY_MANIFESTS_DIR.$sha512);

      //parse all files part
      foreach ($multiparts as $part_sha512) {
         $firstdir = PLUGIN_FUSIONINVENTORY_REPOSITORY_DIR.substr($part_sha512, 0, 1)."/";
         $fulldir  = PLUGIN_FUSIONINVENTORY_REPOSITORY_DIR.self::getDirBySha512($part_sha512).'/';

         //delete file parts
         unlink(trim($fulldir.$part_sha512));

         //delete folders if empty
         if (is_dir($fulldir)) {
            $count_second_folder = count(scandir($fulldir)) - 2;
            if ($count_second_folder === 0) {
               rmdir($fulldir);
            }
         }
         if (is_dir($firstdir)) {
            $count_first_folder = count(scandir($firstdir)) - 2; // -2 for . and ..
            if ($count_first_folder === 0) {
               rmdir($firstdir);
            }
         }
      }


      //remove manifest
      unlink(PLUGIN_FUSIONINVENTORY_MANIFESTS_DIR.$sha512);

      return true;
   }



   /**
    * Check if the manifest relative to the sha512 exist
    *
    * @param string $sha512 sha512 of the file
    * @return boolean
    */
   function checkPresenceManifest($sha512) {
      if (!file_exists(PLUGIN_FUSIONINVENTORY_MANIFESTS_DIR.$sha512)) {
         return FALSE;
      }
      return TRUE;
   }



   /**
    * Check if the file relative to the sha512 exist
    *
    * @param string $sha512 sha512 of the file
    * @return boolean
    */
   function checkPresenceFile($sha512) {
      //Do not continue if the manifest is not found
      if (!$this->checkPresenceManifest($sha512)) {
         return FALSE;
      }

      //Does the file needs to be created ?
      // Even if fileparts exists, we need to be sure
      // the manifest file is created
      $fileparts_ok = TRUE;
      $fileparts_cnt = 0;
      $handle = fopen(PLUGIN_FUSIONINVENTORY_MANIFESTS_DIR.$sha512, "r");
      if ($handle) {
         while (($buffer = fgets($handle)) !== FALSE) {
            $fileparts_cnt++;
            $path = self::getDirBySha512($buffer)."/".trim($buffer, "\n");
            //Check if the filepart exists
            if (!file_exists(PLUGIN_FUSIONINVENTORY_REPOSITORY_DIR.$path)) {
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



   /**
    * Get the maximum size the php can accept for upload file
    *
    * @return string
    */
   static function getMaxUploadSize() {
      $max_upload = (int)(ini_get('upload_max_filesize'));
      $max_post = (int)(ini_get('post_max_size'));
      $memory_limit = (int)(ini_get('memory_limit'));

      return __('Max file size', 'fusioninventory')
         ." : ".min($max_upload, $max_post, $memory_limit).__('Mio', 'fusioninventory');
   }



   /**
    * Get the size of file
    *
    * @param integer $filesize
    * @return string
    */
   static function processFilesize($filesize) {
      if (is_numeric($filesize)) {
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



   /**
    * List number of files not used in packages
    */
   function numberUnusedFiles() {
      echo "<table width='950' class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th>";
      echo __('Unused file', 'fusioninventory');
      echo "</th>";
      echo "<th>";
      echo __('Size', 'fusioninventory');
      echo "</th>";
      echo "</tr>";

      $a_files = $this->find();
      foreach ($a_files as $data) {
         $cnt = countElementsInTable('glpi_plugin_fusioninventory_deploypackages',
                                     '`json` LIKE \'%"'.$data['sha512'].'"%\'');
         if ($cnt == 0) {
            echo "<tr class='tab_bg_1'>";
            echo "<td>";
            echo $data['name'];
            echo "</td>";
            echo "<td>";
            echo round($data['filesize'] / 1000000, 1)." ".__('Mio');
            echo "</td>";
            echo "</tr>";
         }
      }
      echo "</table>";
   }



   /**
    * Delete the files not used in packages
    */
   function deleteUnusedFiles() {

      $a_files = $this->find();
      foreach ($a_files as $data) {
         $cnt = countElementsInTable('glpi_plugin_fusioninventory_deploypackages',
                                     '`json` LIKE \'%"'.$data['sha512'].'"%\'');
         if ($cnt == 0) {
            $this->delete($data);
            $manifest_filename = PLUGIN_FUSIONINVENTORY_MANIFESTS_DIR.$data['sha512'];
            if (file_exists($manifest_filename)) {
               $handle = @fopen($manifest_filename, "r");
               if ($handle) {
                  while (!feof($handle)) {
                     $buffer = trim(fgets($handle));
                     if ($buffer != '') {
                        $part_path = self::getDirBySha512($buffer)."/".$buffer;
                        unlink(PLUGIN_FUSIONINVENTORY_REPOSITORY_DIR.$part_path);
                     }
                  }
                  fclose($handle);
               }

               unlink($manifest_filename);
            }
         }
      }
   }
}

?>
