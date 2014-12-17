<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   @author    David Durieux
   @co-author Alexandre Delaunay
   @copyright Copyright (c) 2010-2014 FusionInventory team
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

class PluginFusioninventoryDeployPackage extends CommonDBTM {

   // Tasks running with this package (updated with getRunningTasks method)
   public $running_tasks = array();

   static $rightname = 'plugin_fusioninventory_package';


   static function getTypeName($nb=0) {
      return __('Package', 'fusioninventory');
   }



   /**
    * Massive action ()
    */
   function getSpecificMassiveActions($checkitem=NULL) {

      $actions = array();
      if (strstr($_SERVER["HTTP_REFERER"], 'deploypackage.import.php')) {
         $actions['PluginFusioninventoryDeployPackage'.MassiveAction::CLASS_ACTION_SEPARATOR.'import'] = __('Import', 'fusioninventory');
         return $actions;
      }
      $actions['PluginFusioninventoryDeployPackage'.MassiveAction::CLASS_ACTION_SEPARATOR.'transfert'] = __('Transfer');
      $actions['PluginFusioninventoryDeployPackage'.MassiveAction::CLASS_ACTION_SEPARATOR.'export'] = __('Export', 'fusioninventory');

      return $actions;
   }


   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      if (strstr($_SERVER["HTTP_REFERER"], 'deploypackage.import.php')) {
         $forbidden[] = 'update';
         $forbidden[] = 'add';
         $forbidden[] = 'delete';
         $forbidden[] = 'purge';
      }
      return $forbidden;
   }



   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   static function showMassiveActionsSubForm(MassiveAction $ma) {

      switch ($ma->getAction()) {
         case 'transfert' :
            Dropdown::show('Entity');
            echo "<br><br>".Html::submit(__('Post'),
                                         array('name' => 'massiveaction'));
            return true;

      }
      return parent::showMassiveActionsSubForm($ma);
   }



   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      switch ($ma->getAction()) {

         case 'export' :
            foreach ($ids as $key) {
               if ($item->can($key, UPDATE)) {
                  $item->exportPackage($key);
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
              }
            }
            break;

         case 'transfert' :
            $pfDeployPackage = new PluginFusioninventoryDeployPackage();
            foreach ($ids as $key) {
               if ($pfDeployPackage->getFromDB($key)) {
                  $input = array();
                  $input['id'] = $key;
                  $input['entities_id'] = $ma->POST['entities_id'];
                  $pfDeployPackage->update($input);
               }
            }
            break;

         case 'import' :
            foreach ($ids as $key) {
               $item->importPackage($key);
               $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
            }
            break;

      }
      return;
   }



   /**
   *  Check if we can edit (or delete) this item
   *  If it's not possible display an error message
   **/
   function getEditErrorMessage($order_type=NULL) {

      $this->getRunningTasks();
      $error_message = "";
      $tasklist = array();
      if (isset($order_type)) {
         $tasklist = array_filter(
            $this->running_tasks,
            create_function('$task', 'return $task["taskjob"]["method"]=="deploy'.$order_type.'";')
         );
      } else {
         $tasklist = $this->running_tasks;
      }

      if (count($tasklist) > 0) {

         // Display error message
         $error_message .= "<h3 class='red'>";
         $error_message .=
            __("Modification Denied", 'fusioninventory');
         $error_message .= "</h3>\n";
         $error_message .=
            "<h4>".
               _n(
                  "The following task is running with this package",
                  "The following tasks are running with this package",
                  count($this->running_tasks), 'fusioninventory'
               ).
            "</h4>\n";

//         $taskurl_list_ids = implode( ', ',
//            array_map(
//               create_function('$task', 'return $task["task"]["id"];'),
//               $this->running_tasks
//            )
//         );

         $taskurl_list_names = implode(', ',
            array_map(
               create_function('$task', 'return "\"".$task["task"]["name"]."\"";'),
               $this->running_tasks
            )
         );


         /**
         * WARNING:
         * The following may be considered as a hack until we get
         * the Search class splitted to get a SearchUrl correctly
         * (cf. https://forge.indepnet.net/issues/2476)
         **/
         $taskurl_base =
            Toolbox::getItemTypeSearchURL("PluginFusioninventoryTaskJob", TRUE);

         $taskurl_args = implode("&",
            array(
               urlencode("field[0]"). "=4",
               urlencode("searchtype[0]") ."=contains",
               urlencode("contains[0]")."= ". urlencode('['.$taskurl_list_names.']'),
               "itemtype=PluginFusioninventoryTask",
               "start=0"
            )
         );
         $error_message .= "<a href='$taskurl_base?$taskurl_args'>";
         $error_message .=  $taskurl_list_names;
         $error_message .= "</a>";
      }
      return $error_message;
   }

   //mmmh I'm not sure if it's still used ... -- kiniou
   function post_addItem() {
      //check whether orders have not already been created
      if (!isset($_SESSION['tmp_clone_package'])) {
         //Create installation & uninstallation order
         PluginFusioninventoryDeployOrder::createOrders($this->fields['id']);
      }
   }

   // function : getRunningTasks
   // desc : get every active tasks running

   function getRunningTasks() {

      $this->running_tasks =
            PluginFusioninventoryTask::getItemsFromDB(
               array(
                  'is_active' => TRUE,
                  'is_running' => TRUE,
                  'definitions' => array(
                     __CLASS__ => $this->fields['id']
                  ),
                  'by_entities' => FALSE,
               )
            );

   }

   function getSearchOptions() {

      $tab = array();
      $tab['common']           = __('Characteristics');

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['linkfield']     = 'name';
      $tab[1]['name']          = __('Name');
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_link'] = $this->getType();

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'id';
      $tab[2]['linkfield'] = '';
      $tab[2]['name']      = __('ID');

      $tab[16]['table']     = $this->getTable();
      $tab[16]['field']     = 'comment';
      $tab[16]['linkfield'] = 'comment';
      $tab[16]['name']      = __('Comments');
      $tab[16]['datatype']  = 'text';

      $tab[19]['table']     = $this->getTable();
      $tab[19]['field']     = 'date_mod';
      $tab[19]['linkfield'] = '';
      $tab[19]['name']      = __('Last update');
      $tab[19]['datatype']  = 'datetime';

      $tab[80]['table']     = 'glpi_entities';
      $tab[80]['field']     = 'completename';
      $tab[80]['name']      = __('Entity');

      $tab[86]['table']     = $this->getTable();
      $tab[86]['field']     = 'is_recursive';
      $tab[86]['linkfield'] = 'is_recursive';
      $tab[86]['name']      = __('Child entities');
      $tab[86]['datatype']  = 'bool';

      $tab[19]['table']     = $this->getTable();
      $tab[19]['field']     = 'date_mod';
      $tab[19]['linkfield'] = '';
      $tab[19]['name']      = __('Last update');
      $tab[19]['datatype']  = 'datetime';

      return $tab;
   }



   function getAllDatas() {
      global $DB;

      $sql = " SELECT id, name
               FROM `".$this->getTable()."`
               ORDER BY name";

      $res  = $DB->query($sql);

      $nb   = $DB->numrows($res);
      $json  = array();
      $i = 0;
      while($row = $DB->fetch_assoc($res)) {
         $json['packages'][$i]['package_id'] = $row['id'];
         $json['packages'][$i]['package_name'] = $row['name'];

         $i++;
      }
      $json['results'] = $nb;

      return json_encode($json);
   }


   function cleanDBonPurge() {
      global $DB;

      $query = "DELETE FROM `glpi_plugin_fusioninventory_deployorders`
                WHERE `plugin_fusioninventory_deploypackages_id`=".$this->fields['id'];
      $DB->query($query);
   }



   function showMenu($options=array()) {

      $this->displaylist = FALSE;

      $this->fields['id'] = -1;
      $this->showList();
   }

   function showList() {
      Search::show('PluginFusioninventoryDeployPackage');
   }

   function defineTabs($options=array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      if ($this->fields['id'] > 0){
         $this->addStandardTab('PluginFusioninventoryDeployinstall', $ong, $options);
         $this->addStandardTab('PluginFusioninventoryDeployuninstall', $ong, $options);
      }
      $ong['no_all_tab'] = TRUE;
      return $ong;
   }

   function showForm($ID, $options=array()) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);
      //Add redips_clone element before displaying tabs
      //If we don't do this, dragged element won't be visible on the other tab not displayed at
      //first (for reminder, GLPI tabs are displayed dynamically on-demand)
      echo "<div id='redips_clone'></div>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."&nbsp;:</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'name', array('size' => 40));
      echo "</td>";

      echo "<td>".__('Comments')."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<textarea cols='40' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      return TRUE;
   }

   /*
    * TODO: switch to non-static to avoid the $package argument
    *       -- kiniou
    */

   static function displayOrderTypeForm($order_type, $packages_id, $package) {
      global $CFG_GLPI;

      $subtypes = array(
         'check'  => __("Audits", 'fusioninventory'),
         'file'   => __("Files", 'fusioninventory'),
         'action' => __("Actions", 'fusioninventory')
      );
      $json_subtypes = array(
         'check'  => 'checks',
         'file'   => 'associatedFiles',
         'action' => 'actions'
      );
      $rand = mt_rand();

      $order = new PluginFusioninventoryDeployOrder($order_type, $packages_id);
      $datas = json_decode($order->fields['json'], TRUE);
      $orders_id = $order->fields['id'];
      $order_type_label = PluginFusioninventoryDeployOrder::getOrderTypeLabel(
                              $order->fields['type']
                          );


      /**
       * Display an error if the package modification is not possible
       **/
      $error_msg = $package->getEditErrorMessage($order_type_label);
      if(!empty($error_msg)) {
         Session::addMessageAfterRedirect($error_msg);
         Html::displayMessageAfterRedirect();
         echo "<div id='package_order_".$orders_id."_span'>";
      }

      echo "<table class='tab_cadre_fixe' id='package_order_".$orders_id."'>";

      /**
       * Display the lists of each subtypes of a package
       **/
      foreach ($subtypes as $subtype => $label) {

         echo "<tr>";
         echo "<th id='th_title_{$subtype}_$rand'>";
         echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/pics/$subtype.png' />";
         echo "&nbsp;".__($label, 'fusioninventory');
         $package->plusButtonSubtype($package->getID(), $orders_id, $subtype, $rand);
         echo "</th>";
         echo "</tr>";

         /**
          * File's form must be encoded as multipart/form-data
          **/
         $multipart = "";
         if ($subtype == "file") {
            $multipart = "enctype='multipart/form-data'";
         }
         echo "<tr>";
         echo "<td style='vertical-align:top'>";

         /**
          * Display subtype form
          **/
         echo "<form name='addition$subtype' method='post' ".$multipart.
            " action='deploypackage.form.php'>";
         echo "<input type='hidden' name='orders_id' value='$orders_id' />";
         echo "<input type='hidden' name='itemtype' value='PluginFusioninventoryDeploy".
            ucfirst($subtype)."' />";

         $classname = "PluginFusioninventoryDeploy".ucfirst($subtype);
         $classname::displayForm($order, $datas, $rand, "init");
         Html::closeForm();

         $json_subtype = $json_subtypes[$subtype];
         /**
          * Display stored actions datas
          **/
         if (  isset($datas['jobs'][$json_subtype])
               && !empty($datas['jobs'][$json_subtype])) {
            echo  "<div id='drag_" . $order_type_label . "_". $subtype . "s'>";
            echo  "<form name='remove" . $subtype. "s' ".
                  "method='post' action='deploypackage.form.php' ".
                  "id='" . $subtype . "sList" . $rand . "'>";
            echo "<input type='hidden' name='remove_item' />";
            echo "<input type='hidden' name='itemtype' value='". $classname . "' />";
            echo "<input type='hidden' name='orders_id' value='" . $order->fields['id'] . "' />";
            $classname::displayList($order, $datas, $rand);
            Html::closeForm();
            echo "</div>";
         }

         /**
          * Initialize drag and drop on subtype lists
          **/
         echo "<script type='text/javascript'>";
         echo "redipsInit('$order_type_label', '$subtype', $orders_id);";
         echo "</script>";
         echo "</td>";
         echo "</tr>";
      }


      if ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE) {
         // === debug ===
         echo "<tr><td>";
         echo "<span id='package_json_debug'>";
         self::display_json_debug($order);
         echo "</sp3an>";
         echo "</td></tr>";
      }
      echo "</table>";
      if (!empty($error_msg)) {
         echo "</div>";
         echo "<script type='text/javascript'>
                  Ext.onReady(function() {
                     Ext.select('#package_order_".$orders_id."_span').mask();
                  });
               </script>";
      }
   }

   function plusButtonSubtype($id, $order_id, $subtype, $rand) {
      global $CFG_GLPI;

      if ($this->can($id, UPDATE)) {
         echo "&nbsp;";
         echo "<img id='plus_{$subtype}s_block{$rand}'";
         echo " onclick=\"new_subtype('{$subtype}', {$order_id}, {$rand})\" ";
         echo  " title='".__('Add')."' alt='".__('Add')."' ";
         echo  " class='pointer' src='".
               $CFG_GLPI["root_doc"].
               "/pics/add_dropdown.png' /> ";
      }
   }

   static function plusButton($dom_id, $clone = FALSE) {
      global $CFG_GLPI;

      echo  "&nbsp;";
      echo  "<img id='plus_$dom_id' ";
      if ($clone !== FALSE) {
         echo
            " onClick=\"plusbutton('$dom_id', '$clone')\" ";
      } else {
         echo
            " onClick=\"plusbutton('$dom_id')\" ";
      }
      echo  " title='".__('Add')."' alt='".__('Add')."' ";
      echo  " class='pointer' src='".
            $CFG_GLPI["root_doc"].
            "/pics/add_dropdown.png'> ";
   }

   static function display_json_debug(PluginFusioninventoryDeployOrder $order) {
      global $CFG_GLPI;

      if ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE) {

         $pfDeployPackage = new PluginFusioninventoryDeployPackage();
         $pfDeployPackage->getFromDB($order->fields['plugin_fusioninventory_deploypackages_id']);

         // === debug ===
         echo "<span class='red'><b>DEBUG</b></span>";
         echo "<form action='".$CFG_GLPI["root_doc"].
         "/plugins/fusioninventory/front/deploypackage.form.php' method='POST'>";
         echo "<textarea cols='132' rows='25' style='border:0' name='json'>";
         echo PluginFusioninventoryToolbox::formatJson($order->fields['json']);
         echo "</textarea>";
         if ($pfDeployPackage->can($pfDeployPackage->getID(), UPDATE)) {
            echo "<input type='hidden' name='orders_id' value='{$order->fields['id']}' />";
            echo "<input type='submit' name='update_json' value=\"".
               _sx('button', 'Save')."\" class='submit'>";
         }
         Html::closeForm();
         // === debug ===
      }

   }

   static function alter_json($action_type, $params) {
      //route to sub class
      $item_type = $params['itemtype'];

      if (
         in_array(
            $item_type,
            array(
               'PluginFusioninventoryDeployCheck',
               'PluginFusioninventoryDeployFile',
               'PluginFusioninventoryDeployAction'
            )
         )
      ) {
         switch ($action_type) {
            case "add_item" :
               $item_type::add_item($params);
               break;
            case "save_item" :
               $item_type::save_item($params);
               break;
            case "remove_item" :
               $item_type::remove_item($params);
               break;
            case "move_item" :
               $item_type::move_item($params);
               break;
         }
      } else {
         Toolbox::logDebug("package subtype not found : " . $params['itemtype']);
         Html::displayErrorAndDie ("package subtype not found");
      }
   }




   public function package_clone($new_name = '') {

      if ($this->getField('id') < 0) {
         return FALSE;
      }

      $_SESSION['tmp_clone_package'] = TRUE;

      //duplicate package
      $package_oldId = $this->getField('id');
      if ($new_name == "") {
         $new_name = $this->getField('name');
      }
      $params = $this->fields;
      unset($params['id']);
      $params['name'] = $new_name;
      $new_package = new PluginFusioninventoryDeployPackage;
      $package_newId = $new_package->add($params);

      //duplicate orders
      $order_obj = new PluginFusioninventoryDeployOrder;
      $orders = $order_obj->find("plugin_fusioninventory_deploypackages_id = '".$package_oldId."'");

      foreach($orders as $order_oldId => $order) {
         //create new order for this new package
         $order_param = array(
            'type' => $order['type'],
            'create_date' => date("Y-m-d H:i:s"),
            'plugin_fusioninventory_deploypackages_id' => $package_newId
         );
         $order_newId = $order_obj->add($order_param);
         unset($order_param);


         //duplicate checks
         $check_obj = new PluginFusioninventoryDeployCheck;
         $checks = $check_obj->find("plugin_fusioninventory_deployorders_id = '".$order_oldId."'");
         foreach ($checks as $check) {
            //create new check for this new order
            unset($check['id']);
            $check['plugin_fusioninventory_deployorders_id'] = $order_newId;
            $check_obj->add($check);
         }

         //duplicate files
         $file_obj = new PluginFusioninventoryDeployFile;
         $files = $file_obj->find("plugin_fusioninventory_deployorders_id = '".$order_oldId."'");
         foreach ($files as $file) {
            //create new file for this new order
            unset($file['id']);
            $file['plugin_fusioninventory_deployorders_id'] = $order_newId;
            $file_newId = $file_obj->add($file);

            //duplicate fileparts
            $filepart_obj = new PluginFusioninventoryDeployFilepart;
            $fileparts = $filepart_obj->find(
               "plugin_fusioninventory_deployfiles_id = '".$order_oldId."'");
            foreach ($fileparts as $filepart) {
               //create new filepart for this new file
               unset($filepart['id']);
               $filepart['plugin_fusioninventory_deployorders_id'] = $order_newId;
               $filepart['plugin_fusioninventory_deployfiles_id'] = $file_newId;
               $filepart_obj->add($filepart);
            }
         }

         //duplicate actions
         $action_obj = new PluginFusioninventoryDeployAction;
         $actions = $action_obj->find(
            "plugin_fusioninventory_deployorders_id = '".$order_oldId."'");
         foreach ($actions as $action) {
            //duplicate actions subitem
            $action_subitem_obj = new $action['itemtype'];
            $action_subitem_oldId = $action['items_id'];
            $action_subitem_obj->getFromDB($action_subitem_oldId);
            $params_subitem = $action_subitem_obj->fields;
            unset($params_subitem['id']);
            $action_subitem_newId = $action_subitem_obj->add($params_subitem);

            //special case for command, we need to duplicate commandstatus and commandenvvariables
            if ($action['itemtype'] == 'PluginFusioninventoryDeployAction_Command') {
               $command_oldId = $action_subitem_oldId;
               $command_newId = $action_subitem_newId;

               //duplicate commandstatus
               $commandstatus_obj = new PluginFusioninventoryDeployAction_Commandstatus;
               $commandstatus = $commandstatus_obj->find(
                  "plugin_fusioninventory_deploycommands_id = '".$command_oldId."'");
               foreach ($commandstatus as $commandstate) {
                  //create new commandstatus for this command
                  unset($commandstate['id']);
                  $commandstate['plugin_fusioninventory_deploycommands_id'] = $command_newId;
                  $commandstatus_obj->add($commandstate);
               }

               //duplicate commandenvvariables
               $commandenvvariables_obj = new PluginFusioninventoryDeployAction_Commandenvvariable;
               $commandenvvariables = $commandenvvariables_obj->find(
                  "plugin_fusioninventory_deploycommands_id = '".$command_oldId."'");
               foreach ($commandenvvariables as $commandenvvariable) {
                  //create new commandenvvariable for this command
                  unset($commandenvvariable['id']);
                  $commandenvvariable['plugin_fusioninventory_deploycommands_id'] = $command_newId;
                  $commandenvvariables_obj->add($commandenvvariable);
               }
            }

            //create new action for this new order
            unset($action['id']);
            $action['plugin_fusioninventory_deployorders_id'] = $order_newId;
            $action['items_id'] = $action_subitem_newId;
            $action_obj->add($action);
         }
      }

      if ($new_package->getName() == NOT_AVAILABLE) {
         $new_package->fields['name'] = $new_package->getTypeName()." : ".__('ID')

                                 ." ".$new_package->fields['id'];
      }
      $display = (isset($this->input['_no_message_link'])?$new_package->getNameID()
                                                         :$new_package->getLink());

      // Do not display quotes
      Session::addMessageAfterRedirect(__('Item successfully added', 'fusioninventory')."&nbsp;: ".
                                       stripslashes($display));

      unset($_SESSION['tmp_clone_package']);

      //exit;

   }



   /**
    * Used to export package
    *
    */
   function exportPackage($packages_id) {
      $this->getFromDB($packages_id);
      if (empty($this->fields['uuid'])) {
         $input = array(
             'id'   => $this->fields['id'],
             'uuid' => Rule::getUuid()
         );
         $this->update($input);
      }

      $pfDeployOrder = new PluginFusioninventoryDeployOrder();
      $pfDeployFile  = new PluginFusioninventoryDeployFile();

      // Generate JSON
      $a_xml = array();
      $input = $this->fields;
      unset($input['id']);
      $a_xml['package'] = $input;
      $a_xml['orders'] = array();
      $a_xml['files'] = array();
      $a_xml['manifests'] = array();
      $a_xml['repository'] = array();

      $a_files = array();
      $a_data = $pfDeployOrder->find("`plugin_fusioninventory_deploypackages_id`='".$this->fields['id']."'");
      foreach ($a_data as $data) {
         unset($data['id']);
         unset($data['plugin_fusioninventory_deploypackages_id']);
         $a_xml['orders'][] = $data;
         $json = json_decode($data['json'], true);
         $a_files = array_merge($a_files, $json['associatedFiles']);

      }

      // Add files
      foreach ($a_files as $files_id=>$data) {
         $a_pkgfiles = current($pfDeployFile->find("`sha512`='".$files_id."'", '', 1));
         if (count($a_pkgfiles) > 0) {
            unset($a_pkgfiles['id']);
            $a_xml['files'][] = $a_pkgfiles;
         }
      }


      // Create zip with JSON and files
      $name = preg_replace("/[^a-zA-Z0-9]/", '', $this->fields['name']);
      $filename = GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/export/".$this->fields['uuid'].".".$name.".zip";
      if (file_exists($filename)) {
         unlink($filename);
      }


      $zip = new ZipArchive();
      if($zip->open($filename) == TRUE) {
         if($zip->open($filename, ZipArchive::CREATE) == TRUE) {
            $zip->addEmptyDir('files');
            $zip->addEmptyDir('files/manifests');
            $zip->addEmptyDir('files/repository');
            foreach ($a_files as $hash=>$data) {
               $sha512 = file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/manifests/".$hash);
               $sha512 = trim($sha512);
               $zip->addFile(GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/manifests/".$hash, "files/manifests/".$hash);
               $a_xml['manifests'][] = $hash;
               $file = PluginFusioninventoryDeployFile::getDirBySha512($sha512).
                       "/".$sha512;
               $zip->addFile(GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/repository/".$file, "files/repository/".$file);
               $a_xml['repository'][] = $file;
            }

            $json_string = json_encode($a_xml);
            $zip->addFromString('information.json', $json_string);
         }
         $zip->close();
         Session::addMessageAfterRedirect(__("Package exported in", "fusioninventory")." ".GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/export/".$this->fields['uuid'].".".$name.".zip");
      }
   }



   /**
    * Used to import package
    *
    */
   function importPackage($zipfile) {

      $zip           = new ZipArchive();
      $pfDeployOrder = new PluginFusioninventoryDeployOrder();
      $pfDeployFile  = new PluginFusioninventoryDeployFile();

      $filename = GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/import/".$zipfile;

      $extract_folder = GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/import/".$zipfile.".extract";

      if ($zip->open($filename, ZipArchive::CREATE) == TRUE) {
         $zip->extractTo($extract_folder);

         $zip->close();
      }
      $json_string = file_get_contents($extract_folder."/information.json");

      $a_info = json_decode($json_string, true);

      // Find package with this uuid
      $a_packages = $this->find("`uuid`='".$a_info['package']['uuid']."'");
      if (count($a_packages) == 0) {
         // Create it
         $_SESSION['tmp_clone_package'] = true;
         $packages_id = $this->add($a_info['package']);
         unset($_SESSION['tmp_clone_package']);
         foreach ($a_info['orders'] as $input) {
            $input['plugin_fusioninventory_deploypackages_id'] = $packages_id;
            $pfDeployOrder->add($input);
            echo "|";
         }
         foreach ($a_info['files'] as $input) {
            $pfDeployFile->add($input);
         }
      } else {
         // Update current

      }
      // Copy files
      foreach ($a_info['manifests'] as $manifest) {
         rename($extract_folder."/files/manifests/".$manifest, GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/manifests/".$manifest);
      }
      foreach ($a_info['repository'] as $repository) {
         $split = explode('/', $repository);
         array_pop($split);
         $folder = '';
         foreach ($split as $dir) {
            $folder .= '/'.$dir;
            if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/repository".$folder)) {
               mkdir(GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/repository".$folder);
            }
         }
         rename($extract_folder."/files/repository/".$repository, GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/repository/".$repository);
      }

   }



   /**
    * Display list of packages to import
    */
   function listPackagesToImport() {

      $rand = mt_rand();

      echo "<div class='spaced'>";
      Html::openMassiveActionsForm('mass'.__CLASS__.$rand);

      $massiveactionparams = array('container' => 'mass'.__CLASS__.$rand);
      Html::showMassiveActions($massiveactionparams);
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='5'>";
      echo __('Packages to import', 'fusioninventory');
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th width='10'>".Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand)."</th>";
      echo "<th>";
      echo __('Name');
      echo "</th>";
      echo "<th>";
      echo __('uuid');
      echo "</th>";
      echo "<th>";
      echo __('Package to update');
      echo "</th>";
      echo "</tr>";

      foreach (glob(GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/import/*.zip") as $file) {
         echo "<tr class='tab_bg_1'>";
         $file = str_replace(GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/import/", "", $file);
         $split = explode('.', $file);
         echo "<td>";
         Html::showMassiveActionCheckBox(__CLASS__, $file);
         echo "</td>";
         echo "<td>";
         echo $split[2];
         echo "</td>";
         echo "<td>";
         echo $split[0].".".$split[1];
         echo "</td>";
         echo "<td>";
         $a_packages = current($this->find("`uuid`='".$split[0].".".$split[1]."'", '', 1));
         if (count($a_packages) > 1) {
            $this->getFromDB($a_packages['id']);
            echo $this->getLink();
         }
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      $massiveactionparams['ontop'] =false;
      Html::showMassiveActions($massiveactionparams);
      echo "</div>";
   }

}

?>
